<?php

require_once "../../config/headers.php";
require_once "../../config/Database.php";
include "../../Models/User.php";
include "Validation.php";
class UserInit
{

    static function getData(){
        return json_decode(file_get_contents("php://input"));
    }

    static function getDatabase(){
        return new Database();
    }

    static function getConn(){
       return self::getDatabase()->connect();
    }

    static function getUser(){
        return new User(self::getConn());
    }

}