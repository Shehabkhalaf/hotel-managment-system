<?php

namespace MVC\controllers;

class reservationcontroller
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

}