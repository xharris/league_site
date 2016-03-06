<?php
    require_once "load.php";

    $DB->add_summoner(
        $_POST['summoner_name'],
        $_POST['location'],
        $_POST['longitude'],
        $_POST['latitude']
    );

    echo $_POST['summoner_name'];
 ?>
