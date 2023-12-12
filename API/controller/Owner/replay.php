<?php
require_once "../Init/ReplayInit.php";
header("Access-Control-Allow-Methods: POST");
$data = ReplayInit::getData();
$r = ReplayInit::getReplay();
if (Validation::checkEmptyReplayData($data)) {
    echo $r->replay($data);
} else {
    http_response_code(204);
    echo json_encode(
        array("message" => "check your data!", "flag" => 0)
    );
}
