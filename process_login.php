<?php
session_start();

require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/auth.php';
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

$email = trim($_POST['email']);
$password = $_POST['password'];

if ($email == "" || $password == "") {
    $_SESSION['error'] = "Email and password required";
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT user_id, name, email, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "User not found";
    header("Location: login.php");
    exit();
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    $_SESSION['error'] = "Incorrect password";
    header("Location: login.php");
    exit();
}

// SESSION
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['name'] = $user['name'];
$_SESSION['role'] = $user['role'];

// SUCCESS MESSAGE
$_SESSION['success'] = "Welcome " . $user['name'];

// REDIRECT BY ROLE
if ($user['role'] == "admin") {
    header("Location: modules/admin/views/dashboard.php");
    exit();
}

if ($user['role'] == "staff") {
    header("Location: staff_dashboard.php");
    exit();
}

if ($user['role'] == "salesperson") {
    header("Location: salesperson_dashboard.php");
    exit();
}

// default
header("Location: user_dashboard.php");
exit();
?>