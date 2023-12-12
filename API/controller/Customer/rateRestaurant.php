<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once "../Init/RateInit.php";
include "../../Models/Comment.php";
header("Access-Control-Allow-Methods: POST");
$data = RateInit::getData();
$rate = RateInit::getRate();
$comment = new Comment(RateInit::getConn());
if (Validation::checkEmptyRateData($data)) {
    $rateF = $rate->createRate($data);
    $cF = $comment->addComment($data);
    echo $rateF;
} else {
    http_response_code(204);
    echo json_encode(array(
        "message" => "check your data!",
        "flag" => 0
    ));
}