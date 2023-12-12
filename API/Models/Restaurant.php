<?php

class Restaurant
{
    private $conn;
    private $table = 'restaurant';
    private $server_ip;
    private $dir;
    private $server_dir;
    /**
     * `id` int(11) NOT NULL,
     * `name` varchar(255) NOT NULL,
     * `details` varchar(255) NOT NULL,
     * `image` varchar(255) NOT NULL,
     * `location_lan` varchar(255) NOT NULL,
     * `location_lat` varchar(255) NOT NULL,
     * `owner_id` varchar(255) NOT NULL
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
        $this->dir = "https://smarttracks.org/test/students/favor/API/Models/restaurantImageAssets/";
        $this->server_dir = "restaurantImageAssets/";

    }

    function createRest($data)
    {
        //insert query
        $query = "INSERT INTO " . $this->table . "
            SET
                name = ?,
                location_lan = ?,
                details = ?,
                location_lat = ?,
                owner_id = ?,
                image = ?
               ";
        $stmt = $this->conn->prepare($query);

        $decode_str = base64_decode($data->ImageData);
        $extension = "jpg";
        $file = uniqid() . '.' . $extension;
        $baseUrl = $_SERVER["DOCUMENT_ROOT"];
        $file_dir = $baseUrl . "/test/students/favor/API/Models/restaurantImageAssets/" . $file;
        // bind the values
        $stmt->bindParam(1, $data->name);
        $stmt->bindParam(2, $data->location_lan);
        $stmt->bindParam(3, $data->details);
        $stmt->bindParam(4, $data->location_lat);
        $stmt->bindParam(5, $data->owner_id);
        $stmt->bindParam(6, $file);
        // execute the query, also check if query was successful
        try {
            $stmt->execute();
            //  file_put_contents($file_dir,$decode_str);
            $handle = fopen($file_dir, "w");
            fwrite($handle, base64_decode($data->ImageData));
            fclose($handle);
            //201 created
            http_response_code(201);
            return json_encode(array(
                "message" => "Restaurant created successful",
                "flag" => 1));
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode(array(
                "message" => "error: " . $e->getMessage()
            ));
        }

    }


    function updateRset($data)
    {
        //insert query
        $query = "Update restaurant
            SET
                name = ?,
                location_lan = ?,
                details = ?,
                location_lat = ?,
                owner_id = ?,
                image = ?
            where id = ?

               ";
        $stmt = $this->conn->prepare($query);
        $extension = "jpg";
        $file = uniqid() . '.' . $extension;
        $baseUrl = $_SERVER["DOCUMENT_ROOT"];
        $file_dir = $baseUrl . "/test/students/favor/API/Models/restaurantImageAssets/" . $file;
        // bind the values
        $stmt->bindParam(1, $data->name);
        $stmt->bindParam(2, $data->location_lan);
        $stmt->bindParam(3, $data->details);
        $stmt->bindParam(4, $data->location_lat);
        $stmt->bindParam(5, $data->owner_id);
        $stmt->bindParam(6, $file);
        $stmt->bindParam(7, $data->restId);
        // execute the query, also check if query was successful
        try {
            $stmt->execute();
            $handle = fopen($file_dir, "w");
            fwrite($handle, base64_decode($data->ImageData));
            fclose($handle);
            //201 created
            http_response_code(201);
            return json_encode(array(
                "message" => "Restaurant updated successful",
                "flag" => 1));
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode(array(
                "message" => "error: " . $e->getMessage()
            ));
        }
    }

    function search($q)
    {
        $query = "select * from restaurant where name like '%$q%' ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $q);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            $restArr = array();
            $restArr['data'] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                //get rate
                include_once "Rate.php";
                $r = new Rate($this->conn);
                $rate = $r->getAvgRate($row['id'])==null?0:$r->getAvgRate($row['id']);
                $restItme = array(
                    "name" => $row['name'],
                    "id" => $row['id'],
                    "details" => $row['details'],
                    "location_lan" => $row['location_lan'],
                    "location_lat" => $row['location_lat'],
                    "owner_id" => $row['owner_id'],
                    "rate" => $rate,
                    "imageUrl" => "https://smarttracks.org/test/students/favor/API/Models/restaurantImageAssets/" . $row['image']
                );
                array_push($restArr['data'], $restItme);
            }
            http_response_code(200);
            return json_encode(array(
                "result" => $restArr,
                "flag" => 1
            ));
        } else {
            http_response_code(404);
            return json_encode(array(
                "result" => "no data found!",
                "flag" => 1
            ));
        }
    }

    function delete($rest_id)
    {
        $q = "Delete from $this->table where id = ? ";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $rest_id);
        try {
            $stmt->execute();
            http_response_code(200);
            return json_encode(array(
                "message" => "restaurant deleted successfully",
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
        $q = "select Count(*) rest_count  from $this->table";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            http_response_code(200);
            return json_encode(
                array("Restaurant_count" => $row['rest_count'], "flag" => 1)
            );
        } else {
            http_response_code(404);
            return json_encode(
                array("Restaurant_count" => "no data found!", "flag" => 0)
            );
        }
    }

    public function getRestLanLang($c_lat, $c_lan)
    {
        $q = "Select * , 
                  (6371 * acos(
                     cos( radians(?) ) 
                     * cos( radians( location_lat ) )
                     * cos( radians( location_lan ) - radians(?) )
                    + sin( radians(?) ) 
                     * sin( radians( location_lat ) )
                    ) ) as distance
                     from $this->table 
                     having distance <10.0
                     order by distance desc";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1, $c_lat);
        $stmt->bindParam(2, $c_lan);
        $stmt->bindParam(3, $c_lat);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $restArr = array();
            $restArr['data'] = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                //getRate
                include_once "Rate.php";
                $r = new Rate($this->conn);
                $rate = $r->getAvgRate($row['id']);
                $restItem = array(
                    "name" => $row['name'],
                    "id" => $row['id'],
                    "details" => $row['details'],
                    "location_lan" => $row['location_lan'],
                    "location_lat" => $row['location_lat'],
                    "owner_id" => $row['owner_id'],
                    "rate" => $rate,
                    "imageUrl" => "https://smarttracks.org/test/students/favor/API/Models/restaurantImageAssets/" . $row['image'],
                    "distance" => $row['distance']
                );
                array_push($restArr['data'], $restItem);
            }
            http_response_code(200);
            return json_encode(array(
                "result" => $restArr,
                "flag" => 1
            ));
        } else {
            http_response_code(404);
            return json_encode(array(
                "result" => "no data found!",
                "flag" => 0
            ));
        }
    }

    public function getRestData($id)
    {
        $query = "select * from restaurant where owner_id = ? ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            //get rate
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            include_once "Rate.php";
            $r = new Rate($this->conn);
            $rate = $r->getAvgRate($row['id'])==null?0:$r->getAvgRate($row['id']);
            http_response_code(200);
            return json_encode(array(
                "name" => $row['name'],
                "id" => $row['id'],
                "details" => $row['details'],
                "location_lan" => $row['location_lan'],
                "location_lat" => $row['location_lat'],
                "owner_id" => $row['owner_id'],
                "rate" => $rate,
                "imageUrl" => "https://smarttracks.org/test/students/favor/API/Models/restaurantImageAssets/" . $row['image'],
                "flag" => 1
            ));
        } else {
            http_response_code(404);
            return json_encode(array(
                "result" => "no data found!",
                "flag" => 0
            ));
        }

    }
}

/**
 *
 *
 */