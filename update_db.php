<?php



// Create database
$sql = "CREATE DATABASE lolcation";

$sql .= "CREATE TABLE IF NOT EXISTS `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `summoner_name` text NOT NULL,
      `ip` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";

$DB->query($sql);

 ?>
