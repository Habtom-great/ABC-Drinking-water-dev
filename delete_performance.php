<?php
include 'db_connection.php';

$id = $_GET['id'];
$conn->query("DELETE FROM employee_performance WHERE id = $id");

header("Location: employee_performance.php");
?>