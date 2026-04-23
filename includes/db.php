<?php

$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "ABC_Company";
$port = 3306;

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("DB Connection Failed: " . mysqli_connect_error());
}

?>