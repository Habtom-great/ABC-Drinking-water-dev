<?php
require_once(__DIR__ . "/../db.php");

function logActivity($conn, $action, $module = null, $details = null)
{
    // Ensure session exists
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Stop if user not logged in
    if (!isset($_SESSION['user_id'])) return;

    $user_id   = $_SESSION['user_id'];
    $user_name = $_SESSION['username'] ?? 'Unknown';
    $ip        = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

    // Optional: sanitize inputs (extra safety)
    $action  = trim($action);
    $module  = $module ? trim($module) : null;
    $details = $details ? trim($details) : null;

    $stmt = $conn->prepare("
        INSERT INTO activity_log 
        (user_id, user_name, action, module, details, ip_address)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        error_log("Logger Prepare Error: " . $conn->error);
        return;
    }

    $stmt->bind_param(
        "isssss",
        $user_id,
        $user_name,
        $action,
        $module,
        $details,
        $ip
    );

    if (!$stmt->execute()) {
        error_log("Logger Execute Error: " . $stmt->error);
    }

    $stmt->close();
}
?>