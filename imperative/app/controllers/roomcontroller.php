<?php

namespace MVC\controllers;
use MVC\models\room;
use MVC\core\Controller;

class RoomController
{
    private $conn;
    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "hotel";
        $this->conn = new \mysqli($servername, $username, $password, $dbname);
    }
    public function index()
    {
        $sql = "SELECT id, type, availability, price FROM rooms";
        $result = $this->conn->query($sql);
        $rooms = $result;
        $sql = "SELECT * FROM customers";
        $result = $this->conn->query($sql);
        $customers = $result;
        extract(['rooms' => $rooms, 'customers' => $customers]);
        require_once(VIEWS . 'rooms\index.php');
    }

    public function create()
    {
        require_once(VIEWS . 'rooms\add.php');
    }

    public function store()
    {
        $type = $_POST['type'];
        $availability = $_POST['availability'] == 'available' ? 1 : 0;
        $price = $_POST['price'];
        $stmt = $this->conn->prepare("INSERT INTO rooms (type, availability, price) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $type, $availability, $price);
        $stmt->execute();
        header('Location: index');
        exit();
    }
    public function bookRoom()
    {
        $customerId = $_POST['customer_id'];
        $roomId = $_POST['room_id'];
        $leaveTime = $_POST['leave_time'];
        $reservationDate = date('Y-m-d H:i:s');

        // Fetch room details, including price per night
        $sql = "SELECT availability, price FROM rooms WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $roomId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $room = $result->fetch_assoc();

            if ($room['availability'] == 1) {
                $updateSql = "UPDATE rooms SET availability = 0 WHERE id = ?";
                $updateStmt = $this->conn->prepare($updateSql);
                $updateStmt->bind_param("i", $roomId);
                $updateStmt->execute();

                $insertReservationSql = "INSERT INTO reservations (room_id, customer_id, reservation_date, leave_date) VALUES (?, ?, ?, ?)";
                $insertReservationStmt = $this->conn->prepare($insertReservationSql);
                $insertReservationStmt->bind_param("iiss", $roomId, $customerId, $reservationDate, $leaveTime);
                $insertReservationStmt->execute();

                // Get the reservation ID from the inserted reservation
                $reservationId = $this->conn->insert_id;

                // Calculate duration in days
                $reservationTimestamp = strtotime($reservationDate);
                $leaveTimestamp = strtotime($leaveTime);
                $duration = round(($leaveTimestamp - $reservationTimestamp) / (60 * 60 * 24)); // Duration in days

                // Calculate the total price (price per night * duration)
                $totalPrice = $duration * $room['price'];

                // Insert a new bill for the reservation
                $insertBillSql = "INSERT INTO bills (`reservation_id`, `duration`, `total_pirce`) VALUES (?, ?, ?)";
                $insertBillStmt = $this->conn->prepare($insertBillSql);
                $insertBillStmt->bind_param("iii", $reservationId, $duration, $totalPrice);
                $insertBillStmt->execute();

                echo "Room successfully booked and bill generated!";
                header('Location: index');
            } else {
                die("Sorry, this room is not available.");
            }
        } else {
            die("Room not found.");
        }
    }


    public function releaseRoom()
    {
        $roomId = $_POST['room_id'];

        $updateSql = "UPDATE rooms SET availability = 1 WHERE id = ?";
        $updateStmt = $this->conn->prepare($updateSql);
        $updateStmt->bind_param("i", $roomId);
        if ($updateStmt->execute()) {
            echo "Room successfully released and set to available!";
        } else {
            die("Error releasing room: " . $this->conn->error);
        }

        // Redirect to the rooms page or wherever appropriate
        header('Location: index'); // Update the URL if needed
    }
}
