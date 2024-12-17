<?php
namespace MVC;

class database
{
    public $conn;
    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "hotel";


        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("connection error: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8");
    }
}


