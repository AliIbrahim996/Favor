<?php

class Meal
{
    private $conn;
    private $table = 'meal';
    private $server_ip;
    private $dir;
    private $server_dir;

    /**
     * `id` int(11) NOT NULL,
     * `name` varchar(255) NOT NULL,
     * `description` varchar(255) NOT NULL,
     * `image` varchar(255) NOT NULL,
     * `price` double NOT NULL,
     * `restaurant_id` int(11) NOT NULL
     */


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
        $this->server_ip = getHostByName(getHostName());
        $this->dir = "http://" . $this->server_ip . "/favor/API/controller/restaurantImageAssets/";
        $this->server_dir = "/favor/API/controller/restaurantImageAssets/";
    }

    function createMeal($data)
    {
        // insert query
        $query = "INSERT INTO " . $this->table . "
            SET
                name = ?,
                description = ?,
                image = ?,
                price = ?,
                restaurant_id = ?
               ";
        $stmt = $this->conn->prepare($query);
        // bind the values
        $new_Name = str_replace(' ', '', $data->name);
        $extension = "jpg";
        $imageName = $new_Name . "_" . $data->restaurant_id . "_" . $data->image . ".jpg";
        $file = $imageName . uniqid() . '.' . $extension;
        $baseUrl = $_SERVER["DOCUMENT_ROOT"];
        $file_dir = $baseUrl . "/test/students/favor/API/Models/restaurantImageAssets/" . $file;

        $stmt->bindParam(1, $data->name);
        $stmt->bindParam(2, $data->description);
        $stmt->bindParam(3, $file);
        $stmt->bindParam(4, $data->price);
        $stmt->bindParam(5, $data->restaurant_id);
        // execute the query, also check if query was successful
        try {
            $stmt->execute();
            $handle = fopen($file_dir, "w");
            fwrite($handle, base64_decode($data->ImageData));
            fclose($handle);
            //201 created
            http_response_code(201);
            return json_encode(array(
                "message" => "Meal created successful",
                "flag" => 1));
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode(array(
                "message" => "error: " . $e->getMessage()
            ));
        }

    }

    public function getMealName($id)
    {
        $q = "select name  from $this->table where id   = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['name'];
        } else {
            return "no meal found!";
        }
    }

    function updateMeal($data)
    {
        // insert query
        $query = "Update $this->table
                SET
                name = ?,
                description = ?,
                image = ?,
                price = ?,
                restaurant_id = ?
                where id = ? 
               ";
        $stmt = $this->conn->prepare($query);
        // bind the values
        $new_Name = str_replace(' ', '', $data->name);
        $extension = "jpg";
        $imageName = $new_Name . "_" . $data->restaurant_id . "_" . $data->image . ".jpg";
        $file = $imageName . uniqid() . '.' . $extension;
        $baseUrl = $_SERVER["DOCUMENT_ROOT"];
        $file_dir = $baseUrl . "/test/students/favor/API/Models/restaurantImageAssets/" . $file;
        $stmt->bindParam(1, $data->name);
        $stmt->bindParam(2, $data->description);
        $stmt->bindParam(3, $file);
        $stmt->bindParam(4, $data->price);
        $stmt->bindParam(5, $data->restaurant_id);
        $stmt->bindParam(6, $data->meal_id);
        // execute the query, also check if query was successful
        try {
            $stmt->execute();
            $handle = fopen($file, "w");
            fwrite($handle, base64_decode($data->ImageData));
            fclose($handle);
            //201 created
            http_response_code(201);
            return json_encode(array(
                "message" => "Meal updated successful",
                "flag" => 1));
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode(array(
                "message" => "error: " . $e->getMessage()
            ));
        }

    }

    function browsMeals($rest_id)
    {
        $q = "select * from meal where restaurant_id = ? ";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $rest_id);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            $mealArr = array();
            $mealArr['data'] = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mealItem = array(
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "description" => $row['description'],
                    "image" => "https://smarttracks.org/test/students/favor/API/Models/restaurantImageAssets/" . $row['image'],
                    "price" => $row['price']
                );
                array_push($mealArr['data'], $mealItem);
            }
            http_response_code(200);
            return json_encode(array(
                "result" => $mealArr,
                "flag" => 1
            ));
        } else {
            http_response_code(404);
            return json_encode(array(
                "result" => "no data found!",
                "flag" => -1
            ));
        }
    }
}