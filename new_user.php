<?php
    require_once "load.php";

    $DB->add_summoner($_POST['summoner_name'],$_POST['location']);

    echo $_POST['summoner_name'];
 ?>
