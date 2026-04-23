<?php
session_start();
require_once "db.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

// ======================
// ONLY ALLOW POST
// ======================
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['error'] = "Invalid request method";
    header("Location: login.php");
    exit();
}

// ======================
// INPUTS
// ======================
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// ======================
// VALIDATION
// ======================
if ($email === '' || $password === '') {
    $_SESSION['error'] = "Email and Password are required";
    header("Location: login.php");
    exit();
}

// ======================
// FIND USER
// ======================
$stmt = $conn->prepare("SELECT user_id, name, email, password, role FROM users WHERE email = ?");
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// ======================
// CHECK USER EXISTS
// ======================
if ($result->num_rows === 0) {
    $_SESSION['error'] = "Invalid email or user not found";
    header("Location: login.php");
    exit();
}

$user = $result->fetch_assoc();

// ======================
// PASSWORD VERIFY
// ======================
if (!password_verify($password, $user['password'])) {
    $_SESSION['error'] = "Incorrect password";
    header("Location: login.php");
    exit();
}

// ======================
// SET SESSION
// ======================
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['name']    = $user['name'];
$_SESSION['email']   = $user['email'];
$_SESSION['role']    = $user['role'];

$_SESSION['success'] = "Welcome " . $user['name'];

// ======================
// ROLE REDIRECTION (FIXED - NO dashboard.php)
// ======================
switch ($user['role']) {

    case 'admin':
        header("Location: admin_dashboard.php");
        break;

    case 'staff':
        header("Location: staff_dashboard.php");
        break;

    case 'salesperson':
        header("Location: salesperson_dashboard.php");
        break;

    default:
        header("Location: user_dashboard.php");
        break;
}

exit();
?>