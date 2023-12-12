<?php
require_once "../Init/UserInit.php";
header("Access-Control-Allow-Methods: POST");
$data = UserInit::getData();
$user = UserInit::getUser();
if (!empty($data->user_name) && !empty($data->password)) {
    echo $user->userLogIn($data->user_name, $data->password);
} else {
    http_response_code(403);
    echo json_encode(array(
        "message" => "Check your data!",
        "flag" => 0
    ));
}
