<?php
include 'header.php';

// =========================
// SESSION + ERRORS
// =========================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// =========================
// DB CONNECTION
// =========================
require 'db_connect.php';

// =========================
// MESSAGE HANDLING
// =========================
$message = "";

if (isset($_SESSION['success'])) {
    $message = "<div class='alert alert-success text-center'>{$_SESSION['success']}</div>";
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    $message = "<div class='alert alert-danger text-center'>{$_SESSION['error']}</div>";
    unset($_SESSION['error']);
}

// =========================
// FORM SUBMISSION
// =========================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username    = trim($_POST['username'] ?? '');
    $last_name   = trim($_POST['last_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $first_name  = trim($_POST['first_name'] ?? '');
    $gender      = $_POST['gender'] ?? '';
    $telephone   = trim($_POST['telephone'] ?? '');
    $address     = trim($_POST['address'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $password    = $_POST['password'] ?? '';
    $role        = $_POST['role'] ?? '';

    // =========================
    // VALIDATION
    // =========================
    if (
        empty($username) || empty($last_name) || empty($first_name) ||
        empty($email) || empty($password) || empty($gender) || empty($role)
    ) {
        $_SESSION['error'] = "Please fill all required fields.";
        header("Location: register.php");
        exit();
    }

    // =========================
    // CHECK EMAIL EXISTS
    // =========================
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Email already registered.";
        header("Location: register.php");
        exit();
    }

    // =========================
    // PROFILE IMAGE (OPTIONAL)
    // =========================
    $profile_image = null;

    if (!empty($_FILES['profile_image']['name'])) {

        $upload_dir = "uploads/users/";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $profile_image = uniqid("user_") . "." . $ext;

        move_uploaded_file(
            $_FILES['profile_image']['tmp_name'],
            $upload_dir . $profile_image
        );
    }

    // =========================
    // PASSWORD HASH
    // =========================
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $full_name = trim("$first_name $middle_name $last_name");

    // =========================
    // INSERT USER
    // =========================
    $sql = "
        INSERT INTO users
        (username, last_name, middle_name, first_name, email, telephone, address, name, password, role, gender, profile_image)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssssssss",
        $username,
        $last_name,
        $middle_name,
        $first_name,
        $email,
        $telephone,
        $address,
        $full_name,
        $hashed_password,
        $role,
        $gender,
        $profile_image
    );

    if ($stmt->execute()) {

        $_SESSION['success'] = "Registration successful. Please login.";
        header("Location: register.php");
        exit();

    } else {
        $_SESSION['error'] = "Registration failed: " . $stmt->error;
        header("Location: register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Registration</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<style>
.register-wrapper {
    max-width: 520px;
    margin: 40px auto;
}

.register-card {
    padding: 22px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.form-control, select {
    font-size: 14px;
    padding: 8px 10px;
}

label {
    font-weight: 600;
    font-size: 13px;
}
</style>
</head>

<body>

<div class="container">

    <div class="register-wrapper">

        <?= $message ?>

        <form method="POST" enctype="multipart/form-data" class="card register-card">

            <h4 class="text-center mb-3">User Registration</h4>

            <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>

            <div class="row">
                <div class="col">
                    <input type="text" name="last_name" class="form-control mb-2" placeholder="Last Name" required>
                </div>
                <div class="col">
                    <input type="text" name="middle_name" class="form-control mb-2" placeholder="Middle Name">
                </div>
                <div class="col">
                    <input type="text" name="first_name" class="form-control mb-2" placeholder="First Name" required>
                </div>
            </div>

            <select name="gender" class="form-control mb-2" required>
                <option value="">Select Gender</option>
                <option>Male</option>
                <option>Female</option>
            </select>

            <input type="text" name="telephone" class="form-control mb-2" placeholder="Telephone">
            <input type="text" name="address" class="form-control mb-2" placeholder="Address">

            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

            <select name="role" class="form-control mb-2" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="salesperson">Salesperson</option>
                <option value="user">User</option>
            </select>

            <label>Profile Image (Optional)</label>
            <input type="file" name="profile_image" class="form-control mb-3">

            <button type="submit" class="btn btn-primary btn-block">Register</button>

        </form>

    </div>

</div>

</body>
</html>

<?php include 'footer.php'; ?>