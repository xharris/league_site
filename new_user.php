<?php
    require_once "load.php";

    var_dump($_POST);

    $DB->add_summoner($_POST['summoner_name'],$_POST['location']);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
 ?>
