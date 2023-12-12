<?php
require_once "../../config/headers.php";
require "../../config/Database.php";
include "../../Models/User.php";
header("Access-Control-Allow-Methods: DELETE");
$database = new Database();
$user = new  User($database->connect());
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->user_id)) {
    echo $user->deleteUser($data->user_id);
} else {
    http_response_code(204);
    echo json_encode(array(
        "message" => "check your data!",
        "flag" => 0
    ));
}