<?php

namespace MVC\controllers;

class reportcontroller
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
    public function roomOccupancyRate()
    {
        $sql = "SELECT type, COUNT(*) as total_rooms, SUM(CASE WHEN availability = 1 THEN 1 ELSE 0 END) as available_rooms FROM rooms GROUP BY type";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "Room Type: " . $row['type'] . " | Total Rooms: " . $row['total_rooms'] . " | Available Rooms: " . $row['available_rooms'] . " | Occupancy Rate: " . (($row['total_rooms'] - $row['available_rooms']) / $row['total_rooms']) * 100 . "%\n";
            }
        } else {
            echo "No data found for room occupancy rates.";
        }
    }
    public function revenueReport($startDate, $endDate)
    {
        $sql = "SELECT SUM(total_price) as total_revenue FROM bills WHERE reservation_date BETWEEN ? AND ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "Total Revenue from " . $startDate . " to " . $endDate . ": $" . $row['total_revenue'] . "\n";
        } else {
            echo "No revenue data found for the given date range.";
        }
    }
    public function customerStatistics()
    {
        $sql = "SELECT COUNT(*) as total_customers, COUNT(DISTINCT customer_id) as unique_customers FROM reservations";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "Total Customers: " . $row['total_customers'] . " | Unique Customers: " . $row['unique_customers'] . "\n";
        } else {
            echo "No customer data found.";
        }
    }
    public function generateReport($type, $startDate, $endDate)
    {
        switch ($type) {
            case 'daily':
                $this->dailyReport($startDate);
                break;
            case 'weekly':
                $this->weeklyReport($startDate, $endDate);
                break;
            case 'monthly':
                $this->monthlyReport($startDate, $endDate);
                break;
            default:
                echo "Invalid report type. Please select 'daily', 'weekly', or 'monthly'.\n";
        }
    }
    private function dailyReport($date)
    {
        $sql = "SELECT * FROM reservations WHERE DATE(reservation_date) = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Daily Report for " . $date . ":\n";
            while ($row = $result->fetch_assoc()) {
                echo "Reservation ID: " . $row['id'] . " | Room ID: " . $row['room_id'] . " | Customer ID: " . $row['customer_id'] . " | Total Price: $" . $row['total_price'] . "\n";
            }
        } else {
            echo "No reservations found for the given date.\n";
        }
    }
    private function weeklyReport($startDate, $endDate)
    {
        $sql = "SELECT * FROM reservations WHERE reservation_date BETWEEN ? AND ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Weekly Report from " . $startDate . " to " . $endDate . ":\n";
            while ($row = $result->fetch_assoc()) {
                echo "Reservation ID: " . $row['id'] . " | Room ID: " . $row['room_id'] . " | Customer ID: " . $row['customer_id'] . " | Total Price: $" . $row['total_price'] . "\n";
            }
        } else {
            echo "No reservations found for the given date range.\n";
        }
    }

    /**
     * Generate monthly report
     */
    private function monthlyReport($startDate, $endDate)
    {
        $sql = "SELECT * FROM reservations WHERE reservation_date BETWEEN ? AND ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Monthly Report from " . $startDate . " to " . $endDate . ":\n";
            while ($row = $result->fetch_assoc()) {
                echo "Reservation ID: " . $row['id'] . " | Room ID: " . $row['room_id'] . " | Customer ID: " . $row['customer_id'] . " | Total Price: $" . $row['total_price'] . "\n";
            }
        } else {
            echo "No reservations found for the given date range.\n";
        }
    }
}

