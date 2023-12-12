<?php

class Rate
{
    private $conn;
    private $table = 'rate';


    /**
     * `id` int(11) NOT NULL,
     * `content` double NOT NULL,
     * `value` varchar(255) NOT NULL,
     * `time` varchar(255) NOT NULL,
     * `restaurant_id` int(11) NOT NULL,
     * `user_id` varchar(255) NOT NULL,
     * `owner_id` varchar(255) NOT NULL
     */


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    function createRate($data)
    {
        // insert query
        $query = "INSERT INTO " . $this->table . "
            SET
                content = ?,
                value = ?,
                time = ?,
                restaurant_id = ?,
                user_id = ?,
                owner_id = ?
               ";
        $stmt = $this->conn->prepare($query);
        // bind the values
        $stmt->bindParam(1, $data->content);
        $stmt->bindParam(2, $data->value);
        $stmt->bindParam(3, $data->time);
        $stmt->bindParam(4, $data->restaurant_id);
        $stmt->bindParam(5, $data->user_id);
        $stmt->bindParam(6, $data->owner_id);
        // execute the query, also check if query was successful
        try {
            $stmt->execute();
            //201 created
            http_response_code(201);
            return json_encode(array(
                "message" => "rate done",
                "flag" => 1));
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode(array(
                "message" => "error: " . $e->getMessage()
            ));
        }

    }

    function getRate($rest_id)
    {
        $q = "Select * from $this->table where restaurant_id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $rest_id);
        $stmt->execute();
        return $stmt;
    }

    function getAvgRate($rest_id)
    {
        $q = "Select Avg(value) as rate from $this->table where restaurant_id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $rest_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['rate'];
        } else {
            return 0;
        }

    }
}