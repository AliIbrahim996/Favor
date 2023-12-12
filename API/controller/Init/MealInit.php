<?php

require "../../config/headers.php";
require "../../config/Database.php";
include "../../Models/Meal.php";
include "Validation.php";

class  MealInit
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

    static function getMeal()
    {
        return new Meal(self::getConn());
    }

}