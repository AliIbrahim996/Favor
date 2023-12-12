<?php

class Replay
{
    private  $conn;
    private  $table = 'replay';


    /**
     * `id` int(11)
     * `comment_id`
     * `owner_id `
     * `user_id`
     * `replay`
     */


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function replay($data)
    {
        $q = "Insert into $this->table SET 
               comment_id = ?, owner_id = ? , user_id = ?, replay = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $data->comment_id);
        $stmt->bindParam(2, $data->owner_id);
        $stmt->bindParam(3, $data->user_id);
        $stmt->bindParam(4, $data->replay);
        try {
            $stmt->execute();
            //201 created
            http_response_code(201);
            return json_encode(array(
                "message" => "reply Done!",
                "flag" => 1));
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode(array(
                "message" => "error: " . $e->getMessage()
            ));
        }
    }

}