<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Redirect based on role (ERP standard)
    switch ($_SESSION['role']) {

        case 'admin':
            header("Location: modules/admin/views/dashboard.php");
            break;

        case 'staff':
            header("Location: modules/hr/dashboard.php");
            break;

        case 'salesperson':
            header("Location: modules/sales/dashboard.php");
            break;

        default:
            header("Location: index.php");
            break;
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ABC ERP Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(120deg, #0f172a, #1e293b);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-card {
    width: 380px;
    padding: 30px;
    border-radius: 15px;
    background: white;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.title {
    text-align: center;
    font-weight: bold;
    margin-bottom: 20px;
    color: #1e293b;
}

.btn-primary {
    width: 100%;
}
</style>
</head>

<body>

<div class="login-card">

    <h3 class="title">ABC ERP SYSTEM</h3>
    <p class="text-center text-muted">Sign in to continue</p>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="process_login.php">

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-primary">Login</button>

    </form>

</div>

</body>
</html>