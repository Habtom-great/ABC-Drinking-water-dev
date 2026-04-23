<?php
// Database connection (using default XAMPP credentials)
$servername = "localhost";
$username = "root";  // Default MySQL username for XAMPP is 'root'
$password = "";  // Default password for XAMPP is an empty string
$dbname = "ABC_Company";  // Replace with your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>