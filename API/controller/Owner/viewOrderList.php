<?php
require_once "../Init/OrderInit.php";
include "../../Models/User.php";
include "../../Models/Meal.php";
header("Access-Control-Allow-Methods: POST");

$data = OrderInit::getData();
$user = new User(OrderInit::getConn());
$order = OrderInit::getOrder();
$meal = new Meal(OrderInit::getConn());
if (!empty($data->restaurant_id)) {
    $result = $order->viewOrderList($data->restaurant_id);
    $num = $result->rowCount();
    if ($num > 0) {
        $orderArr = array();
        $orderArr['data'] = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $userName = $user->getName($row['user_id']);
            $mealName = $meal->getMealName($row['meal_id']);
            $orderItem = array(
                "user_id" => $row['user_id'],
                "user_name" => $userName,
                "meal_name" => $mealName,
                "meal_id" => $row['meal_id'],
                "quantity" => $row['quantity'],
                "date" => $row['date'],
                "time" => $row['time']
            );
            array_push($orderArr['data'], $orderItem);
        }
        http_response_code(200);
        echo json_encode(
            array(
                "Order_list" => $orderArr,
                "flag" => 1
            )
        );
    } else {
        http_response_code(404);
        echo json_encode(
            array(
                "Order_list" => "no data found",
                "flag" => -1
            )
        );
    }
} else {
    http_response_code(204);
    echo json_encode(
        array(
            "message" => "check your data",
            "flag" => 1
        )
    );

}
