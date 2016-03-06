<?php
    require_once "load.php";

    $name = $_POST['summoner_name'];
    if (trim($name) == '') {
        echo '';
    } else {
        $DB->add_summoner(
            $name,
            $_POST['location'],
            $_POST['longitude'],
            $_POST['latitude']
        );

        setcookie("user",$name);

        $cities = $DB->getCitiesJS();

        echo '["'.$_POST['summoner_name'].'","'.$_POST['location'].'"]';
    }

 ?>
