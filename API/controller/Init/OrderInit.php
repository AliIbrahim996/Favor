<?php
require_once "../../config/headers.php";
require_once "../../config/Database.php";
include "../../Models/Order.php";
include "Validation.php";

class OrderInit
{
    static function getData()
    {
        return json_decode(file_get_contents("php://input"));
    }

    static function getDatabase()
    {
        return new Database();
    }

    static function getConn()
    {
        return self::getDatabase()->connect();
    }

    static function getOrder()
    {
        return new Order(self::getConn());
    }

}