<?php
require "../../config/headers.php";
require "../../config/Database.php";
include "../../Models/Comment.php";
include "Validation.php";

class CommentInit
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

    static function getComment()
    {
        return new Comment(self::getConn());
    }
}