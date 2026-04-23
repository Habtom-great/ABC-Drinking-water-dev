<?php
$servername = "localhost";
$username = "root";  // Your MySQL username
$password = "";      // Your MySQL password
$dbname = "abc_company";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // You can print a message for debugging purposes (optional)
    // echo "Connection successful!";
}
?>




