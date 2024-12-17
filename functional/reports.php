<?php

// Database connection function (no change)
function getDbConnection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel";
    $conn = new \mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Helper function for applying recursion in report printing
function printReportsRecursively($rows, $index = 0)
{
    // Base case: when we've printed all rows
    if ($index >= count($rows)) {
        return;
    }

    // Print the current report row
    $row = $rows[$index];
    echo "Reservation ID: " . $row['id'] . " | Room ID: " . $row['room_id'] . " | Customer ID: " . $row['customer_id'] . " | Total Price: $" . $row['total_price'] . "\n";

    // Recursively call the function for the next row
    printReportsRecursively($rows, $index + 1);
}

// Higher-Order Function: applies a report generating function
function applyReportFunction($conn, $reportFunc, ...$params)
{
    return $reportFunc($conn, ...$params);
}
function roomOccupancyRate($conn)
{
    $sql = "SELECT type, COUNT(*) as total_rooms, SUM(CASE WHEN availability = 1 THEN 1 ELSE 0 END) as available_rooms FROM rooms GROUP BY type";
    $result = $conn->query($sql);

    // Fetch all rows first (immutable)
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    // Use recursion to print all reports
    printReportsRecursively($rows);
}

// Revenue Report function (uses immutability)
function revenueReport($conn, $startDate, $endDate)
{
    $sql = "SELECT SUM(total_price) as total_revenue FROM bills WHERE reservation_date BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the result as an immutable array
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    if (count($rows) > 0) {
        $revenue = $rows[0]['total_revenue'];
        echo "Total Revenue from " . $startDate . " to " . $endDate . ": $" . $revenue . "\n";
    } else {
        echo "No revenue data found for the given date range.\n";
    }
}

// Customer Statistics function (uses immutability)
function customerStatistics($conn)
{
    $sql = "SELECT COUNT(*) as total_customers, COUNT(DISTINCT customer_id) as unique_customers FROM reservations";
    $result = $conn->query($sql);

    // Immutable result processing
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    if (count($rows) > 0) {
        $row = $rows[0];
        echo "Total Customers: " . $row['total_customers'] . " | Unique Customers: " . $row['unique_customers'] . "\n";
    } else {
        echo "No customer data found.\n";
    }
}

// General report generator using higher-order functions
function generateReport($conn, $type, $startDate, $endDate)
{
    $reportFunctions = [
        'daily' => 'dailyReport',
        'weekly' => 'weeklyReport',
        'monthly' => 'monthlyReport'
    ];

    if (array_key_exists($type, $reportFunctions)) {
        applyReportFunction($conn, $reportFunctions[$type], $startDate, $endDate);
    } else {
        echo "Invalid report type. Please select 'daily', 'weekly', or 'monthly'.\n";
    }
}

// Daily Report function (recursive approach to fetch rows)
function dailyReport($conn, $date)
{
    $sql = "SELECT * FROM reservations WHERE DATE(reservation_date) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Immutable rows
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    if (count($rows) > 0) {
        echo "Daily Report for " . $date . ":\n";
        printReportsRecursively($rows);  // Recursion to print the report
    } else {
        echo "No reservations found for the given date.\n";
    }
}

// Weekly Report function (recursive approach to fetch rows)
function weeklyReport($conn, $startDate, $endDate)
{
    $sql = "SELECT * FROM reservations WHERE reservation_date BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    // Immutable rows
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    if (count($rows) > 0) {
        echo "Weekly Report from " . $startDate . " to " . $endDate . ":\n";
        printReportsRecursively($rows);  // Recursion to print the report
    } else {
        echo "No reservations found for the given date range.\n";
    }
}

// Monthly Report function (recursive approach to fetch rows)
function monthlyReport($conn, $startDate, $endDate)
{
    $sql = "SELECT * FROM reservations WHERE reservation_date BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    // Immutable rows
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    if (count($rows) > 0) {
        echo "Monthly Report from " . $startDate . " to " . $endDate . ":\n";
        printReportsRecursively($rows);  // Recursion to print the report
    } else {
        echo "No reservations found for the given date range.\n";
    }
}

// Example usage
$conn = getDbConnection();
//roomOccupancyRate($conn);
//revenueReport($conn, '2024-01-01', '2024-12-31');
//customerStatistics($conn);
generateReport($conn, 'monthly', '2024-01-01', '2024-12-31');
