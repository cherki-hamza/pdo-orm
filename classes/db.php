<?php

// connect to database
$dsn = 'mysql:host=localhost;dbname=pdo-orm';
$username = 'hamza';
$password = 'root';
$params = array(
  PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
  PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
);

try {
    $connect = new PDO($dsn , $username , $password , $params);
    //echo '<span class="text-primary">connect with success</span>';
}catch (PDOException $ex){
    echo '<span class="text-danger">Oops error to connect to database '. $ex->getMessage() .'</span>';
}



