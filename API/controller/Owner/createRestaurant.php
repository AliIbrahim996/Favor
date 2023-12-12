<?php
require_once "../Init/RestInit.php";
header("Access-Control-Allow-Methods: POST");
$data = RestInit::getData();
$rest = RestInit::getRestaurant();
if (Validation::checkEmptyRestData($data)) {
    echo $rest->createRest($data);
} else {
    http_response_code(204);
    echo json_encode(
        array("message" => "check your data!", "flag" => 0)
    );
}
