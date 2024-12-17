<?php

namespace MVC\controllers;
use MVC\models\room;
use MVC\core\Controller;

class RoomController
{
    public function index()
    {
        $rooms = room::all();
        controller::view('rooms\index', ['rooms' => $rooms]);
    }

    public function create()
    {
        controller::view('rooms\add');
    }

    public function store()
    {
        $room = [
            'type' => $_POST['type'],
            'availability' => $_POST['availability']=='available' ? 1 : 0,
            'price' => $_POST['price'],
        ];
        room::add($room);
        header('location: index');
    }

}
