<?php

    require_once "../load.php";
    $user = new User($DB,$_POST['name']);

    echo $user->getLevel();

 ?>
