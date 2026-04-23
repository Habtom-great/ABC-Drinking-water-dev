<?php
session_start();

// 🔐 Check login
function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /ABC-Drinking-water/login.php");
        exit();
    }
}

// 👤 Check role
function checkRole($allowed_roles = []) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        echo "<h3 style='color:red;'>🚫 Access Denied</h3>";
        exit();
    }
}
?>