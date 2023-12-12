<?php
require_once "../Init/OrderInit.php";
header("Access-Control-Allow-Methods: POST");
$data = OrderInit::getData();
$order = OrderInit::getOrder();
if (Validation::checkEmptyOrderData($data)) {
    echo $order->createOrder($data);
} else {
    http_response_code(204);
    echo json_encode(
        array(
            "message" => "check your data!",
            "flag" => 0
        )
    );
}

