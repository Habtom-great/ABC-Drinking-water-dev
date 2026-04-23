<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /ABC-Drinking-water-dev/login.php");
    exit();
}

$pageTitle = $pageTitle ?? "ERP System";
$content = $content ?? "<div>No Content</div>";
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $pageTitle ?></title>

    <link rel="stylesheet" href="/ABC-Drinking-water-dev/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { margin:0; font-family: Arial; background:#f4f6f9; }

        /* SIDEBAR */
        .sidebar {
            width:260px;
            height:100vh;
            position:fixed;
            background:#111827;
            color:white;
        }

        .sidebar a {
            display:block;
            padding:12px;
            color:#cbd5e1;
            text-decoration:none;
        }

        .sidebar a:hover { background:#1f2937; }

        /* CONTENT */
        .content {
            margin-left:260px;
            padding:20px;
        }

        /* TOPBAR */
        .topbar {
            margin-left:260px;
            height:60px;
            background:white;
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:0 20px;
            border-bottom:1px solid #ddd;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h3 style="padding:15px;">ABC ERP</h3>

    <a href="/ABC-Drinking-water-dev/views/admin_dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
    <a href="/ABC-Drinking-water-dev/modules/sales/"><i class="fa fa-cart-shopping"></i> Sales</a>
    <a href="/ABC-Drinking-water-dev/modules/inventory/"><i class="fa fa-box"></i> Inventory</a>
    <a href="/ABC-Drinking-water-dev/modules/purchases/"><i class="fa fa-truck"></i> Purchases</a>
    <a href="/ABC-Drinking-water-dev/modules/accounting/"><i class="fa fa-coins"></i> Accounting</a>
    <a href="/ABC-Drinking-water-dev/logout.php"><i class="fa fa-sign-out"></i> Logout</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <div><?= $pageTitle ?></div>
    <div>👤 <?= $_SESSION['name'] ?? 'User' ?></div>
</div>

<!-- CONTENT -->
<div class="content">
    <?= $content ?>
</div>

</body>
</html>