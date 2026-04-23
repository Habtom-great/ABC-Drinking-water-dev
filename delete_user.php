
<?php
include 'db_connection.php';
session_start(); // Start the session to store messages

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

 // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    // 1️⃣ Get user last name BEFORE deleting
    $getStmt = $conn->prepare("SELECT last_name FROM users WHERE user_id = ?");
    $getStmt->bind_param("i", $user_id);
    $getStmt->execute();
    $getStmt->bind_result($last_name);
    $getStmt->fetch();
    $getStmt->close();

    // 2️⃣ Delete user
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User member '{$last_name}' has been deleted successfully!";
        $_SESSION['message_type'] = "danger";

    } else {
        $_SESSION['message'] = "Error deleting user '{$last_name}'.";
        $_SESSION['message_type'] = "error";
    }

    $stmt->close();
    $conn->close();

    header("Location: manage_users.php");
    exit();
}