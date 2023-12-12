<?php

class User
{
    private $conn;
    private $table = 'user';
    //User Prop

    /**
     * `user_id` int(11) NOT NULL,
     * `first_name` varchar(255) NOT NULL,
     * `last_name` varchar(255) NOT NULL,
     * `user_name` varchar(255) NOT NULL,
     * `password` varchar(255) NOT NULL,
     * `phone` varchar(255) NOT NULL,
     * `email` varchar(255) NOT NULL,
     * `userRole` int(11) NOT NULL
     */

    private $id;
    private $first_name;
    private $last_name;
    private $user_name;
    private $phone;
    private $full_name;
    private $userRole;
    private $email;
    private $password;
    private $loc_lat;
    private $loc_lan;


    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }


    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    function registerUser($data)
    {
        // insert query
        $query = "INSERT INTO " . $this->table . "
            SET
                first_name = ?,
                last_name = ?,
                user_name = ?,
                phone = ?,
                email = ?,
                password = ?,
                userRole = ?,
                loc_lat = ?,
                loc_lan = ?
               ";
        $stmt = $this->conn->prepare($query);
        // bind the values
        $password = password_hash($data->password, PASSWORD_BCRYPT);
        $loc_lan = empty($data->loc_lan) ? null : $data->loc_lan;
        $loc_lat = empty($data->loc_lat) ? null : $data->loc_lat;

        $stmt->bindParam(1, $data->first_name);
        $stmt->bindParam(2, $data->last_name);
        $stmt->bindParam(3, $data->user_name);
        $stmt->bindParam(4, $data->phone);
        $stmt->bindParam(5, $data->email);
        $stmt->bindParam(6, $password);
        $stmt->bindParam(7, $data->userRole);
        $stmt->bindParam(8, $loc_lat);
        $stmt->bindParam(9, $loc_lan);
        // execute the query, also check if query was successful
        try {
            $stmt->execute();
            $q = "Select  user_id  FROM $this->table where user_name = ? ";
            $stmt2 = $this->conn->prepare($q);
            $stmt2->bindParam(1, $data->user_name);
            $stmt2->execute();
            $id = $stmt2->fetch(PDO::FETCH_ASSOC)['user_id'];
            //201 created
            http_response_code(201);
            return json_encode(array(
                "message" => "User registered successful",
                "flag" => 1,
                "id" => $id));
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode(array(
                "message" => "error: " . $e->getMessage()
            ));
        }

    }

    public function getName($id)
    {
        $q = "select CONCAT(first_name,' ',last_name) full_name  from $this->table where user_id   = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['full_name'];
        } else {
            return "no user found!";
        }
    }

    public function userLogIn($user_name, $password)
    {
        if ($this->userExists($user_name)) {
            //check for password
            if (password_verify($password, $this->password)) {
                //
                http_response_code(200);
                return json_encode(array(
                    "message" => "successfully logged in",
                    "user_info" => array(
                        "userRole" => $this->userRole,
                        "email" => $this->email,
                        "id" => $this->id,
                        "loc_lan" => $this->loc_lan,
                        "loc_lat" => $this->loc_lat
                    ),
                    "flag" => 1
                ));
            } else {
                http_response_code(401);
                return json_encode(
                    array(
                        "message" => "Unauthorized! password error",
                        "flag" => -1
                    )
                );
            }
        } else {
            http_response_code(404);
            return json_encode(
                array(
                    "message" => "User not found! check your email",
                    "flag" => -2
                )
            );
        }
    }

    private function userExists($email)
    {

        // query to check if email exists
        $query = "SELECT user_id,password,email,userRole,loc_lat,loc_lan
            FROM " . $this->table . "
            WHERE user_name = ?
            LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare($query);
        // bind value
        $stmt->bindParam(1, $email);
        // execute the query
        $stmt->execute();
        // get number of rows
        $num = $stmt->rowCount();
        if ($num > 0) {
            //set password
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->password = $row['password'];
            $this->email = $row['email'];
            $this->userRole = $row['userRole'];
            $this->id = $row['user_id'];
            $this->loc_lan = $row['loc_lan'];
            $this->loc_lat = $row['loc_lat'];
            // return true because email exists in the database
            return true;
        }
        // return false if email does not exist in the database
        return false;
    }

    public function deleteUser($user_id)
    {
        $q = "Delete from user where user_id = ? ";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $user_id);
        try {
            $stmt->execute();
            http_response_code(200);
            return json_encode(array(
                "message" => "user deleted successfully",
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
        $q = "select Count(*) use_count  from $this->table";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            http_response_code(200);
            return json_encode(
                array("Users_count" => $row['use_count'], "flag" => 1)
            );
        } else {
            http_response_code(404);
            return json_encode(
                array("Users_count" => "no data found!", "flag" => 0)
            );
        }
    }
}