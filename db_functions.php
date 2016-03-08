<?php

class Database {
    var $servername, $username, $password, $database;
    var $conn;

    function Database(){
        $this->servername = DB_SERVERNAME;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->database = DB_DATABASE;

        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    function query($query){
        return $this->conn->query($query);
    }

    function multi_query($query){
        return $this->conn->multi_query($query);
    }

    function add_summoner($name,$location,$long,$lat){
        $sql = "
            INSERT INTO users (summoner_name,location,longitude,latitude)
            VALUES ('".$name."','".$location."','".$long."','".$lat."');

            UPDATE cities SET population = population + 1
            WHERE name = '".$location."';

            INSERT INTO cities (name,population)
            VALUES ('".$location."','1');
        ";
        $this->multi_query($sql);
    }

    function update_summoner_level($name,$level){
        $sql = "
            INSERT INTO user_level (summoner_name,level)
            VALUES ('".$name."',".$level.")
            ON DUPLICATE KEY UPDATE level=".$level.";
        ";
        $this->query($sql);
    }

    function getUsers(){
        $result = $this->query("
            SELECT * FROM users
        ");

        $result_array = array();
        while ($row = $result->fetch_assoc()) {
            array_push($result_array, $row);
        }

        return $result_array;
    }

    function getUsersJS(){
        $users = $this->getUsers();

        return json_encode($users);
    }

    function getCities(){
        $result_array = array();

        $result = $this->query("
            SELECT * FROM cities
        ");

        $cities = array();
        while ($row = $result->fetch_assoc()) {
            array_push($cities, $row);
        }

        foreach ($cities as $c=>$city) {
            array_push($result_array,$city);
        }

        return $result_array;
    }

    function getCitiesJS(){
        $cities = $this->getCities();

        return json_encode($cities);
    }

}



?>
