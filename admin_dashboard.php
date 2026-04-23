<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link rel="stylesheet" href="css/bootstrap.min.css">

<style>
body {
    background: #f4f6f9;
    font-family: Arial, sans-serif;
}

.header {
    background: #1e293b;
    color: white;
    padding: 20px;
    text-align: center;
    position: relative;
}

.logout {
    position: absolute;
    right: 20px;
    top: 20px;
}

.card-box {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    transition: 0.3s;
    height: 100%;
}

.card-box:hover {
    transform: translateY(-5px);
}

.icon {
    font-size: 30px;
    margin-bottom: 10px;
}

.title {
    font-weight: bold;
}

.desc {
    font-size: 14px;
    color: gray;
}
a {
    text-decoration: none;
}
</style>
</head>

<body>

<div class="header">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></p>
    <a class="btn btn-danger logout" href="logout.php">Logout</a>
</div>

<div class="container mt-4">

<div class="row g-3">

    <div class="col-md-4">
        <a href="manage_users.php">
        <div class="card-box">
            <div class="icon">👥</div>
            <div class="title">Customers</div>
            <div class="desc">Manage customers & details</div>
        </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="manage_vendors.php">
        <div class="card-box">
            <div class="icon">🚚</div>
            <div class="title">Suppliers / Vendors</div>
            <div class="desc">Manage suppliers & vendors</div>
        </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="manage_charts_of_accounts.php">
        <div class="card-box">
            <div class="icon">📊</div>
            <div class="title">Chart of Accounts</div>
            <div class="desc">Financial account management</div>
        </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="manage_users.php">
        <div class="card-box">
            <div class="icon">👤</div>
            <div class="title">User Management</div>
            <div class="desc">Roles & permissions</div>
        </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="manage_staff.php">
        <div class="card-box">
            <div class="icon">🧑‍💼</div>
            <div class="title">Staff</div>
            <div class="desc">Employees management</div>
        </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="manage_inventory.php">
        <div class="card-box">
            <div class="icon">📦</div>
            <div class="title">Inventory</div>
            <div class="desc">Stock tracking system</div>
        </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="generate_report-1.php">
        <div class="card-box">
            <div class="icon">📑</div>
            <div class="title">Reports</div>
            <div class="desc">System reports</div>
        </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="settings.php">
        <div class="card-box">
            <div class="icon">⚙️</div>
            <div class="title">Settings</div>
            <div class="desc">System configuration</div>
        </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="activity_log.php">
        <div class="card-box">
            <div class="icon">📜</div>
            <div class="title">Activity Log</div>
            <div class="desc">System tracking</div>
        </div>
        </a>
    </div>

</div>

</div>

<div class="text-center mt-4 text-muted">
© <?php echo date("Y"); ?> Inventory Management System
</div>

</body>
</html>