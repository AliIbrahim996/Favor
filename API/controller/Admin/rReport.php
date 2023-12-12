<?php
require_once "../Init/RestInit.php";
header("Access-Control-Allow-Methods: POST");
$rest = RestInit::getRestaurant();
echo $rest->getCount();