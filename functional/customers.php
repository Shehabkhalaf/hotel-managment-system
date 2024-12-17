<?php

function createDbConnection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel";
    return new \mysqli($servername, $username, $password, $dbname);
}

// Higher-order function to execute queries
function executeQuery($conn, $query, $params = [], $returnResult = false)
{
    $stmt = $conn->prepare($query);
    if ($params) {
        $stmt->bind_param(...$params);
    }
    $stmt->execute();

    return $returnResult ? $stmt->get_result() : true;
}

// Index function: Immutable fetch of all customers
function index()
{
    $conn = createDbConnection();
    $sql = "SELECT id, name, email, phone FROM customers";
    $result = executeQuery($conn, $sql, [], true);
    $customers = $result->fetch_all(MYSQLI_ASSOC);
    extract(['customers' => $customers]);
    require_once(VIEWS . 'customers/index.php');
}

// Create function (no changes here, keeps immutability)
function create()
{
    require_once(VIEWS . 'customers/add.php');
}

// Immutable store function
function store()
{
    $conn = createDbConnection();
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $query = "INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)";
    executeQuery($conn, $query, ["sss", $name, $email, $phone]);

    header('Location: index');
    exit();
}

// Search function with basic immutability
function search()
{
    $conn = createDbConnection();
    $name = $_GET['name'];
    $sql = "SELECT * FROM customers WHERE name LIKE ?";
    $customers = executeQuery($conn, $sql, ["s", "%$name%"], true);

    extract(['customers' => $customers]);
    require_once(VIEWS . 'customers/index.php');
}

// Show function: Retrieve a specific customer by ID
function show($id)
{
    $conn = createDbConnection();
    $stmt = $conn->prepare("SELECT id, name, email, phone FROM customers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_all(MYSQLI_ASSOC);

    extract(['customer' => $customer]);
    require_once(VIEWS . 'customers/edit.php');
}

// Update function
function update()
{
    $conn = createDbConnection();
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $query = "UPDATE customers SET name = ?, email = ?, phone = ? WHERE id = ?";
    executeQuery($conn, $query, ["sssi", $name, $email, $phone, $id]);

    header('Location: index');
    exit();
}