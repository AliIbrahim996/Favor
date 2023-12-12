<?php
require_once "../Init/RestInit.php";
header("Access-Control-Allow-Methods: DELETE");
$data = RestInit::getData();
$rest = RestInit::getRestaurant();
if (!empty($data->restaurant_id)) {
    echo $rest->delete($data->restaurant_id);
} else {
    http_response_code(204);
    echo json_encode(
        array("message" => "something went wrong!", "flag" => 0)
    );
}

