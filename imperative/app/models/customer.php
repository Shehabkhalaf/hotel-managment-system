<?php

namespace MVC\models;
use Dcblogdev\PdoWrapper\Database;
use MVC\core\dbcontroller;

class customer
{
    public static function all()
    {
        $db = new dbcontroller();
        $customers = $db->select('select * from customers');
        return $customers;
    }
    public static function store($data)
    {
        $db = new dbcontroller();
        $customer = $db->insert('customers', $data);
        return $customer;
    }
    public static function get($name = null)
    {
        $db = new dbcontroller();
        $customers = $db->select("SELECT * FROM customers WHERE name LIKE '%$name%'");
        return $customers;
    }
    public static function find($id)
    {
        $db = new dbcontroller();
        $customer = $db->select("SELECT * FROM customers WHERE id = '$id'");
        return $customer;
    }
    public static function update($data, $id)
    {
        $db = new dbcontroller();
        $db->update('customers', $data, $id);
    }
}