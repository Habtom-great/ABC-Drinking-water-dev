<?php
require_once __DIR__ . '/db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['staff_id'])) {

    $staff_id = intval($_GET['staff_id']);

    // 1️⃣ Get staff last name safely
    $last_name = "Unknown";

    $getStmt = $conn->prepare("SELECT last_name FROM staff WHERE staff_id = ?");
    $getStmt->bind_param("i", $staff_id);

    if ($getStmt->execute()) {
        $getStmt->bind_result($last_name);
        $getStmt->fetch();
    }
    $getStmt->close();

    // 2️⃣ Delete staff
    $stmt = $conn->prepare("DELETE FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staff_id);

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Staff '{$last_name}' deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "No staff found to delete!";
            $_SESSION['message_type'] = "warning";
        }

    } else {
        $_SESSION['message'] = "Delete failed: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    $conn->close();

    header("Location: manage_staff.php");
    exit();
}
?>