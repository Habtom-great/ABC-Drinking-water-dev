<?php
require_once "logger.php";
require_once __DIR__ . '/../db.php';

function checkPermission($page, $action = 'view')
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['role'])) {
        die("Access denied. No role found.");
    }

    global $conn;

    $role = $_SESSION['role'];

    $stmt = $conn->prepare("
        SELECT can_view, can_create, can_edit, can_delete 
        FROM permissions 
        WHERE role = ? AND page = ?
        LIMIT 1
    ");

    $stmt->bind_param("ss", $role, $page);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("No permission defined.");
    }

    $perm = $result->fetch_assoc();

    $map = [
        'view'   => 'can_view',
        'create' => 'can_create',
        'edit'   => 'can_edit',
        'delete' => 'can_delete'
    ];

    if (!isset($map[$action]) || $perm[$map[$action]] != 1) {
        die("⛔ Access Denied: You don't have permission.");
    }
}
?>