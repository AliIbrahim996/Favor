<?php

require_once "../../config/headers.php";
require_once "../../config/Database.php";
include "../../Models/Restaurant.php";
include "Validation.php";

class RestInit
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

    static function getRestaurant()
    {
        return new Restaurant(self::getConn());
    }

}