<?php

namespace MVC\controllers;
use MVC\core\Controller;
use MVC\models\customer;

class CustomerController
{
    public function index()
    {
        $customers = Customer::all();
        Controller::view('customers\index', ['customers' => $customers]);
    }
    public function create()
    {
        Controller::view('customers\add');
    }
    public function store()
    {
        $customer['name'] = $_POST['name'];
        $customer['email'] = $_POST['email'];
        $customer['phone'] = $_POST['phone'];

        customer::store($customer);
        header('location: index');
    }
    public function search()
    {
        $name = $_GET['name'];
        $customers = customer::get($name);
        Controller::view('customers\index', ['customers' => $customers]);
    }

    public function show($id)
    {
        $customer = customer::find($id);
        controller::view('customers\edit', ['customer' => $customer]);
    }
    public function update()
    {

        $id = ['id' => $_POST['id']];
        $customer['name'] = $_POST['name'];
        $customer['email'] = $_POST['email'];
        $customer['phone'] = $_POST['phone'];
        customer::update($customer, $id);
        header('location: index');
    }
}