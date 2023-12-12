<?php
require_once "../Init/MealInit.php";
header("Access-Control-Allow-Methods: POST");
$data = MealInit::getData();
$meal = MealInit::getMeal();
if ($data->rest_id) {
    echo $meal->browsMeals($data->rest_id);
} else {
    http_response_code(204);
    echo json_encode(array(
        "message" => "check your data",
        "flag" => 0
    ));
}