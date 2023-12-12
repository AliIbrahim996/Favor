<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
require_once "../Init/RestInit.php";
header("Access-Control-Allow-Methods: POST");
$data = RestInit::getData();
$rest = RestInit::getRestaurant();
if (!empty($data->id)) {
    echo $rest->getRestData($data->id);
}else {
    http_response_code(204);
    echo json_encode(
        array("message" => "check your data!", "flag" => 1)
    );
}
