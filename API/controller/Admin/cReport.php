<?php
require_once "../../config/headers.php";
require "../../config/Database.php";
include "../../Models/User.php";
header("Access-Control-Allow-Methods: POST");
$database = new Database();
$user = new  User($database->connect());
echo $user->getCount();