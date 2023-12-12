<?php

class Comment
{
    private $conn;
    private $table = 'comment';


    /**
     * `id` int(11) NOT NULL,
     * `content` varchar(255) NOT NULL,
     * `time` varchar(255) NOT NULL,
     * `user_id` varchar(255) NOT NULL,
     * `restaurant_id` int(11) NOT NULL
     */


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function addComment($data)
    {
        $q = "insert into comment set 
               content = ? , time = ?, user_id = ? , restaurant_id = ?  ";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $data->content);
        $stmt->bindParam(2, $data->time);
        $stmt->bindParam(3, $data->user_id);
        $stmt->bindParam(4, $data->restaurant_id);
        try {
            $stmt->execute();
            http_response_code(201);
            return json_encode(
                array(
                    "message" => "comment added successfully",
                    "flag" => 1
                )
            );
        } catch (Exception $e) {
            http_response_code(401);
            return json_encode(
                array(
                    "message" => "something went wrong! " . $e->getMessage(),
                    "flag" => -1
                )
            );
        }
    }

    function updateComment($data)
    {
        $q = "Update comment set 
                content = ? , time = ?,
                user_id = ? ,
                restaurant_id = ? 
                where id = ? ";


        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $data->content);
        $stmt->bindParam(2, $data->time);
        $stmt->bindParam(3, $data->user_id);
        $stmt->bindParam(4, $data->restaurant_id);
        $stmt->bindParam(5, $data->comment_id);
        try {
            $stmt->execute();
            http_response_code(200);
            return json_encode(
                array(
                    "message" => "comment updated successfully",
                    "flag" => 1
                )
            );
        } catch (Exception $e) {
            http_response_code(401);
            return json_encode(
                array(
                    "message" => "something went wrong! " . $e->getMessage(),
                    "flag" => -1
                )
            );
        }
    }

    public function delete($c_id)
    {
        $q = "Delete from $this->table where id = ? ";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $c_id);
        try {
            $stmt->execute();
            http_response_code(200);
            return json_encode(array(
                "message" => "comment deleted successfully",
                "flag" => 1
            ));
        } catch (Exception $e) {
            http_response_code(401);
            return json_encode(array(
                "message" => "something went wrong! " . $e->getMessage(),
                "flag" => -1
            ));
        }
    }

    public function getCount()
    {
        $q = "select Count(*) co_count  from $this->table";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            http_response_code(200);
            return json_encode(
                array("Comments_count" => $row['co_count'], "flag" => 1)
            );
        } else {
            http_response_code(404);
            return json_encode(
                array("Comments_count" => "no data found!", "flag" => 0)
            );
        }
    }

    public function getComment($rest_id)
    {
        $q = "select * from comment where restaurant_id = ? ";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $rest_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $commentData = array();
            include_once "User.php";
            $u = new User($this->conn);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $name = $u->getName($row['user_id']);
                $commentItem = array(
                    "id" => $row['id'],
                    "content" => $row['content'],
                    "user_id" => $row['user_id'],
                    "user_name" => $name,
                    "time" => $row['time']
                );
                array_push($commentData, $commentItem);
            }
            http_response_code(200);
            return json_encode(array("Comments" => $commentData));
        }
        else{
            http_response_code(404);
            return json_encode(array("Comments" => "no data found!"));
        }

    }
}