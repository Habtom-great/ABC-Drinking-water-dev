<?php
// =========================
// SESSION INIT (ONLY ONCE)
// =========================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =========================
// CHECK LOGIN
// =========================
function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /ABC-Drinking-water-dev/login.php");
        exit();
    }
}

// =========================
// CHECK ROLE
// =========================
function checkRole($allowed_roles = []) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        header("Location: /ABC-Drinking-water-dev/unauthorized.php");
        exit();
    }
}

// =========================
// CURRENT USER
// =========================
function currentUser() {
    return [
        'id'    => $_SESSION['user_id'] ?? null,
        'name'  => $_SESSION['name'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'role'  => $_SESSION['role'] ?? null,
    ];
}

// =========================
// IS LOGGED IN
// =========================
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>