<?php

namespace MVC\controllers;


class CustomerController
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
        $sql = "SELECT id, name, email, phone FROM customers"; // Replace 'customers' with your table name
        $customers = $this->conn->query($sql);
        extract(['customers' => $customers]);
        require_once(VIEWS . 'customers\index.php');
    }
    public function create()
    {
        require_once(VIEWS . 'customers\add.php');
    }
    public function store()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $stmt = $this->conn->prepare("INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)");

        $stmt->bind_param("sss", $name, $email, $phone);
        $stmt->execute();
        header('Location: index');
        exit;
    }
    public function search()
    {
        $name = $_GET['name'];
        $sql = "SELECT * FROM customers WHERE name LIKE '%$name%'"; // Replace 'customers' with your table name
        $customers = $this->conn->query($sql);

        extract(['customers' => $customers]);
        require_once(VIEWS . 'customers\index.php');
    }

    public function show($id)
    {
        $stmt = $this->conn->prepare("SELECT id, name, email, phone FROM customers WHERE id = ?");
        $stmt->bind_param("i", $id);

        $stmt->execute();
        $result = $stmt->get_result();

        $customer = $result->fetch_all(MYSQLI_ASSOC);

        extract(['customer' => $customer]);
        require_once(VIEWS . 'customers\edit.php');
    }
    public function update()
    {

        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $stmt = $this->conn->prepare("UPDATE customers SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
        $stmt->execute();
        header('Location: index');
        exit();
    }
}