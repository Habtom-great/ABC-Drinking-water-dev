
<?php
require_once __DIR__ . "/../db.php";
?>
<?php
$servername = "localhost";
$username = "root";  // Change this to your DB username
$password = "";      // Change this to your DB password
$dbname = "abc_company";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
