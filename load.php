<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "config.php";
include_once "db_functions.php";

$DB = new Database();
$cities = $DB->getCitiesJS();

include_once "php/User.php";

 ?>
