<?php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/auth.php';

// ======================
// BOOTSTRAP ERP ENGINE
// ======================

$root = dirname(__DIR__, 3);

require_once $root . '/includes/db.php';
require_once $root . '/includes/auth.php';

checkAuth();
checkRole(['admin']);

$user = currentUser();

/* ======================
   SAFE QUERY FUNCTION
====================== */
function getValue($conn, $sql, $key = 'total') {
    $result = mysqli_query($conn, $sql);
    if (!$result) return 0;
    $row = mysqli_fetch_assoc($result);
    return $row[$key] ?? 0;
}

/* ======================
   ERP KPI DATA
====================== */

$totalSales     = getValue($conn, "SELECT SUM(total_sales_after_vat) AS total FROM sales");
$totalStock     = getValue($conn, "SELECT SUM(quantity) AS total FROM inventory");
$totalPurchase  = getValue($conn, "SELECT SUM(total_amount) AS total FROM purchases");
$totalEmp       = getValue($conn, "SELECT COUNT(*) AS total FROM staff");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ERP Dashboard | ABC System</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: #f4f6f9;
    font-family: "Segoe UI", sans-serif;
}

/* ================= SIDEBAR ================= */
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    background: #0f172a;
    color: white;
    padding-top: 20px;
    overflow-y: auto;
}

.sidebar h4 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    color: #cbd5e1;
    text-decoration: none;
    transition: 0.2s;
    font-size: 14px;
}

.sidebar a:hover {
    background: #1e293b;
    color: #fff;
    padding-left: 25px;
}

/* ================= CONTENT ================= */
.content {
    margin-left: 250px;
    padding: 20px;
}

/* ================= TOPBAR ================= */
.topbar {
    background: #fff;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* ================= KPI CARDS ================= */
.kpi-card {
    border: none;
    border-radius: 14px;
    padding: 20px;
    text-align: center;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: 0.2s;
}

.kpi-card:hover {
    transform: translateY(-5px);
}

.kpi-title {
    font-size: 14px;
    color: #6b7280;
}

.kpi-value {
    font-size: 26px;
    font-weight: bold;
    margin-top: 5px;
}
</style>
</head>

<body>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
    <h4 class="text-center">ABC ERP</h4>

    <a href="<?= BASE_URL ?>admin_dashboard.php">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="<?= BASE_URL ?>modules/sales/">
        <i class="bi bi-cart"></i> Sales
    </a>

    <a href="<?= BASE_URL ?>modules/inventory/">
        <i class="bi bi-box"></i> Inventory
    </a>

    <a href="<?= BASE_URL ?>modules/purchases/">
        <i class="bi bi-truck"></i> Purchases
    </a>

    <a href="<?= BASE_URL ?>modules/accounting/">
        <i class="bi bi-cash-stack"></i> Accounting
    </a>

    <a href="<?= BASE_URL ?>modules/hr/">
        <i class="bi bi-people"></i> HR
    </a>

    <a href="<?= BASE_URL ?>modules/reports/">
        <i class="bi bi-file-earmark-text"></i> Reports
    </a>
</div>
<!-- ================= CONTENT ================= -->
<div class="content">

    <!-- TOP BAR -->
    <div class="topbar">
        <div>
            <h5 class="m-0">ERP Dashboard</h5>
            <small class="text-muted">Welcome back</small>
        </div>

        <div>
            <i class="bi bi-bell fs-5 me-3"></i>
            <i class="bi bi-person-circle fs-5"></i>
            <strong class="ms-2"><?= htmlspecialchars($user['name']) ?></strong>
        </div>
    </div>

    <!-- KPI CARDS -->
    <div class="row g-3">

        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-title">Total Sales</div>
                <div class="kpi-value">$<?= number_format($totalSales, 2) ?></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-title">Inventory Stock</div>
                <div class="kpi-value"><?= $totalStock ?></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-title">Purchases</div>
                <div class="kpi-value">$<?= number_format($totalPurchase, 2) ?></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-title">Employees</div>
                <div class="kpi-value"><?= $totalEmp ?></div>
            </div>
        </div>

    </div>

    <!-- ACTION PANEL -->
    <div class="row mt-4">

        <div class="col-md-8">
            <div class="kpi-card text-start">
                <h6>Sales Overview</h6>
                <p class="text-muted mb-0">Chart.js integration ready (next step)</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="kpi-card text-start">
                <h6>Quick Actions</h6>

                <a href="modules/sales/new.php" class="btn btn-primary btn-sm w-100 mb-2">New Sale</a>
                <a href="modules/inventory/add.php" class="btn btn-success btn-sm w-100 mb-2">Add Inventory</a>
                <a href="modules/reports/" class="btn btn-warning btn-sm w-100">Generate Report</a>

            </div>
        </div>

    </div>

</div>

</body>
</html>