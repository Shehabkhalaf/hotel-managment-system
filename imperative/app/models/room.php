<?php
namespace MVC\models;

use Dcblogdev\PdoWrapper\Database;
use MVC\core\dbcontroller;
use PDOException;

class room
{
    public static function all()
    {
        $db = new dbcontroller();
        return $db->select("SELECT * FROM rooms");
    }

    public static function add($data)
    {
        $db = new dbcontroller();
        return $db->insert("rooms", $data);
    }
}