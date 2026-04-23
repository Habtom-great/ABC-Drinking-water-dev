<?php
// Database connection
$host = "localhost"; // Replace with your database host
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "abc_company"; // Replace with your database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form inputs
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $quantity = (int)$_POST['quantity'];
    $unit_cost = (float)$_POST['unit_cost'];
    $unit_price = (float)$_POST['unit_price'];
    $branch = $conn->real_escape_string($_POST['branch']);
    $uom = $conn->real_escape_string($_POST['uom']);
    $date = $conn->real_escape_string($_POST['date']);

    // SQL query to insert data into the inventory table
    $sql = "INSERT INTO inventory (item_name, quantity, unit_cost, unit_price, branch, uom, date) 
            VALUES ('$item_name', $quantity, $unit_cost, $unit_price, '$branch', '$uom', '$date')";

    // Execute query
    if ($conn->query($sql) === TRUE) {
        echo "<h3>Data entry saved successfully!</h3>";
        echo "<a href='index.html'>Go back</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
