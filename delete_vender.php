<?php 
include 'db_connection.php';
session_start(); // Start the session to store messages

if (isset($_GET['vendor_id'])) {
    $vendor_id = $_GET['vendor_id'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM vendors WHERE vendor_id = ?");
    $stmt->bind_param("i", $vendor_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Vendor has been deleted successfully!";
        $_SESSION['message_type'] = "success"; // You can use this to style the message
    } else {
        $_SESSION['message'] = "Error deleting vendor: " . $conn->error;
        $_SESSION['message_type'] = "error";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to manage_vendors.php
    header("Location: manage_venders.php");
    exit(); // Ensure script stops execution after redirection
}
?>