<?php

class Order
{
    private  $conn;
    private  $table = 'orders';


    /**
     * `id` int(11) NOT NULL,
     * `user_id` varchar(255) NOT NULL,
     * `restaurant_id` int(11) NOT NULL,
     * `meal_id` int(11) NOT NULL,
     * `quantity` int(11) NOT NULL,
     * `date` varchar(255) NOT NULL,
     * `time` varchar(255) NOT NULL
     */


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function viewOrderList($restaurant_id)
    {
        $q = "select * from orders where restaurant_id = ? ";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $restaurant_id);
        $stmt->execute();
        return $stmt;
    }

    public function createOrder($data)
    {
        $q = "Insert into orders SET 
                 user_id = ?, restaurant_id = ?, meal_id = ?,
                    quantity = ?, date = ?, time = ?  ";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $data->user_id);
        $stmt->bindParam(2, $data->restaurant_id);
        $stmt->bindParam(3, $data->meal_id);
        $stmt->bindParam(4, $data->quantity);
        $stmt->bindParam(5, $data->date);
        $stmt->bindParam(6, $data->time);
        try {
            $stmt->execute();
            http_response_code(201);
            return json_encode(
                array("message" => "new order created successfully",
                    "flag" => 1)
            );
        } catch (Exception $e) {
            http_response_code(401);
            return json_encode(
                array("message" => "something went wrong! " . $e->getMessage(),
                    "flag" => -1)
            );
        }

    }

}