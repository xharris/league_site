<?php

class User {

    var $name, $location;

    function User($database,$name) {
        $this->DB = $database;
        $this->name = $name;
        $this->address = $this->DB->query("
                SELECT location FROM users WHERE location=
            ");
    }

    function getLevel() {
        $sql = "
            SELECT level FROM user_level WHERE summoner_name='".$this->name."';
        ";
        $result = $this->DB->query($sql)->fetch_row();
        if ($result) {
            return $result[0];
        } else {
            return '?';
        }
    }

    function updateLevel($new_level) {
        $sql = "
            INSERT INTO user_level (summoner_name,level)
            VALUES ('".$this->name."','".$level."');
        ";
        $this->DB->query($sql);
    }

    function getAddress() {
        return $this->address;
    }
}

 ?>
