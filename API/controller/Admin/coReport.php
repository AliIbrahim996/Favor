<?php
require_once "../../config/headers.php";
require     "../../config/Database.php";
include     "../../Models/Comment.php";
header("Access-Control-Allow-Methods: POST");
$database = new Database();
$co = new Comment($database->connect());
echo $co->getCount();