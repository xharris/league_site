<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db_functions.php");

$DB = new Database();
$cities = $DB->getCitiesJS();

include("update_db.php");

 ?>
