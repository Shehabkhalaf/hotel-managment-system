<?php
// Database connection function (still first-class)
function getDbConnection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel";
    return new \mysqli($servername, $username, $password, $dbname);
}

// Higher-order function to interact with the database
function dbQuery($query, $params = [], $callback = null)
{
    $conn = getDbConnection();
    $stmt = $conn->prepare($query);
    if ($params) {
        $stmt->bind_param(...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($callback) {
        return $callback($result);
    }
    return $result;
}

// Immutable operation: Get rooms, higher-order
function getRooms()
{
    return dbQuery("SELECT id, type, availability, price FROM rooms", [], function ($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    });
}

// Immutable operation: Get customers, higher-order
function getCustomers()
{
    return dbQuery("SELECT * FROM customers", [], function ($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    });
}

// Create a room (immutable)
function createRoom($type, $availability, $price)
{
    $query = "INSERT INTO rooms (type, availability, price) VALUES (?, ?, ?)";
    dbQuery($query, ["sii", $type, $availability, $price]);
    return ["type" => $type, "availability" => $availability, "price" => $price];
}

// Recursive function to book rooms (higher-order and first-class)
function bookRoomRecursive($customerId, $roomId, $leaveTime, $retryCount = 3)
{
    return dbQuery("SELECT availability, price FROM rooms WHERE id = ?", ["i", $roomId], function ($result) use ($customerId, $roomId, $leaveTime, $retryCount) {
        if ($result->num_rows > 0) {
            $room = $result->fetch_assoc();
            if ($room['availability'] == 1) {
                // Book the room and generate a bill
                return handleBooking($customerId, $roomId, $leaveTime, $room['price']);
            } else {
                // Retry logic
                if ($retryCount > 0) {
                    echo "Room is not available, retrying...\n";
                    return bookRoomRecursive($customerId, $roomId, $leaveTime, $retryCount - 1);
                } else {
                    die("Sorry, this room is not available after multiple attempts.");
                }
            }
        } else {
            die("Room not found.");
        }
    });
}

// Separate function to handle room booking and bill generation (first-class)
function handleBooking($customerId, $roomId, $leaveTime, $price)
{
    $conn = getDbConnection();
    $reservationDate = date('Y-m-d H:i:s');
    $updateSql = "UPDATE rooms SET availability = 0 WHERE id = ?";
    dbQuery($updateSql, ["i", $roomId]);

    $insertReservationSql = "INSERT INTO reservations (room_id, customer_id, reservation_date, leave_date) VALUES (?, ?, ?, ?)";
    dbQuery($insertReservationSql, ["iiss", $roomId, $customerId, $reservationDate, $leaveTime]);

    // Calculate the duration and total price
    $reservationTimestamp = strtotime($reservationDate);
    $leaveTimestamp = strtotime($leaveTime);
    $duration = round(($leaveTimestamp - $reservationTimestamp) / (60 * 60 * 24)); // Duration in days
    $totalPrice = $duration * $price;

    $insertBillSql = "INSERT INTO bills (reservation_id, duration, total_price) VALUES (?, ?, ?)";
    dbQuery($insertBillSql, ["iii", $conn->insert_id, $duration, $totalPrice]);

    echo "Room successfully booked and bill generated!";
    return true;
}

// Release room
function releaseRoom($roomId)
{
    $currentAvailability = dbQuery("SELECT availability FROM rooms WHERE id = ?", ["i", $roomId], function ($result) {
        return $result->fetch_assoc()['availability'];
    });

    if ($currentAvailability == 1) {
        echo "Room is already available.";
        return;
    }

    $updateSql = "UPDATE rooms SET availability = 1 WHERE id = ?";
    dbQuery($updateSql, ["i", $roomId]);
    echo "Room successfully released and set to available!";
}

// Higher-order function for routing
function route($action, $params = [])
{
    $actions = [
        'index' => 'index',
        'create' => 'create',
        'store' => 'store',
        'bookRoom' => 'bookRoomRecursive',
        'releaseRoom' => 'releaseRoom'
    ];

    if (isset($actions[$action])) {
        $function = $actions[$action];
        return call_user_func_array($function, $params);
    } else {
        die("Action not found.");
    }
}

function index()
{
    $rooms = getRooms();
    $customers = getCustomers();
    extract(['rooms' => $rooms, 'customers' => $customers]);
    require_once(VIEWS . 'rooms/index.php');
}

function create()
{
    require_once(VIEWS . 'rooms/add.php');
}

function store()
{
    $type = $_POST['type'];
    $availability = $_POST['availability'] == 'available' ? 1 : 0;
    $price = $_POST['price'];
    createRoom($type, $availability, $price);
    header('Location: index');
    exit();
}

function exportRoomData($offset = 0, $batchSize = 10)
{
    $rooms = dbQuery("SELECT * FROM rooms LIMIT ?, ?", ["ii", $offset, $batchSize], function ($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    });

    if (empty($rooms)) {
        echo "Export completed.\n";
        return;
    }

    foreach ($rooms as $room) {
        // Export room data (e.g., write to a file or send to an API)
        echo "Exporting Room ID: " . $room['id'] . "\n";
    }

    exportRoomData($offset + $batchSize, $batchSize);
}

// Example usage of route function
route('index');
