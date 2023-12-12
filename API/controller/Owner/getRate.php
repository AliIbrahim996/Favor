<?php
require_once "../Init/RateInit.php";
include "../../Models/User.php";
header("Access-Control-Allow-Methods: POST");

$data = RateInit::getData();
$rate = RateInit::getRate();
$user = new User(RateInit::getConn());
if (!empty($data->rest_id)) {
    $result = $rate->getRate($data->rest_id);
    $num = $result->rowCount();
    if ($num > 0) {
        $rateArr = array();
        $rateArr['data'] = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $userName = $user->getName($row['user_id']);
            $rateItem = array(
                "user_name" => $userName,
                "rateValue" => $row['value'],
                "content" => $row['content']
            );
            array_push($rateArr['data'], $rateItem);
        }
        http_response_code(200);
        echo json_encode(
            array(
                "Rate_info" => $rateArr,
                "flag" => 1
            )
        );
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "no data found!", "flag" => -1)
        );
    }
} else {
    http_response_code(204);
    echo json_encode(
        array("message" => "check your data!", "flag" => 0)
    );
}
