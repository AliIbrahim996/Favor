<?php

error_reporting(E_ALL);
ini_set('display_errors','on');
require_once "../Init/RestInit.php";
header("Access-Control-Allow-Methods: POST");
$data = RestInit::getData();
$rest = RestInit::getRestaurant();
if (!empty($data->c_lat) && !empty($data->c_lan)) {
    echo $rest->getRestLanLang($data->c_lat, $data->c_lan);
} else {
    http_response_code(204);
    echo json_encode(
        array("message" => "check your data!", "flag" => 1)
    );
}
