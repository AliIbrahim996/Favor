<?php

error_reporting(E_ALL);
ini_set('display_errors','on');
require_once "../Init/UserInit.php";
header("Access-Control-Allow-Methods: POST");
$database = new Database();
$conn = $database->connect();
$data = UserInit::getData();
$user = UserInit::getUser();
if (Validation::checUserEmptyData($data)) {
    echo $user->registerUser($data);
}
else{
    http_response_code(403);
    echo json_encode(array(
        "message" => "Check your data!",
        "flag" => 0
    ));
}
