<?php
// This is your existing layout file
// Make sure $content is defined before including this file
if (!isset($dashboardContent)) {
    $dashboardContent = '<div class="alert alert-danger">Content not found</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ERP System - ABC Drinking Water</title>

<link rel="stylesheet" href="/ABC-Drinking-water/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
}

/* ================= SIDEBAR ================= */
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    background: #111827;
    color: white;
    transition: 0.3s;
    overflow-y: auto;
    z-index: 1001;
}

.sidebar h2 {
    text-align: center;
    padding: 20px;
    font-size: 18px;
    background: #0f172a;
    margin: 0;
    letter-spacing: 1px;
}

.sidebar a {
    display: block;
    color: #cbd5e1;
    padding: 12px 20px;
    text-decoration: none;
    transition: 0.2s;
    font-size: 14px;
}

.sidebar a:hover {
    background: #1f2937;
    color: white;
    padding-left: 25px;
}

.sidebar i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

/* ================= TOPBAR ================= */
.topbar {
    margin-left: 250px;
    height: 60px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 25px;
    border-bottom: 1px solid #e5e7eb;
    position: fixed;
    width: calc(100% - 250px);
    top: 0;
    z-index: 1000;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.system-title {
    font-weight: 600;
    font-size: 16px;
    color: #1f2937;
}

.badge-live {
    background: #10b981;
    color: white;
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 10px;
    margin-left: 10px;
    font-weight: 500;
}

.user-box {
    display: flex;
    align-items: center;
    gap: 20px;
}

.user-box i {
    font-size: 18px;
    color: #6b7280;
    cursor: pointer;
    transition: color 0.2s;
}

.user-box i:hover {
    color: #4f46e5;
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #4f46e5;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    cursor: pointer;
}

/* ================= CONTENT ================= */
.content {
    margin-left: 250px;
    padding: 80px 25px 25px;
    min-height: 100vh;
}

/* ================= SCROLLBAR ================= */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #e5e7eb;
}

::-webkit-scrollbar-thumb {
    background: #9ca3af;
    border-radius: 3px;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        z-index: 1050;
    }
    .sidebar.open {
        transform: translateX(0);
    }
    .topbar {
        margin-left: 0;
        width: 100%;
    }
    .content {
        margin-left: 0;
    }
    .menu-toggle {
        display: block !important;
    }
}

.menu-toggle {
    display: none;
    font-size: 20px;
    cursor: pointer;
    background: none;
    border: none;
    color: #4f46e5;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <h2>
        <i class="fas fa-tint me-2"></i>ABC Water
    </h2>

    <a href="/ABC-Drinking-water/admin_dashboard.php">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>

    <a href="/ABC-Drinking-water/modules/sales/">
        <i class="fas fa-shopping-cart"></i> Sales
    </a>

    <a href="/ABC-Drinking-water/modules/inventory/">
        <i class="fas fa-boxes"></i> Inventory
    </a>

    <a href="/ABC-Drinking-water/modules/purchases/">
        <i class="fas fa-truck"></i> Purchases
    </a>

    <a href="/ABC-Drinking-water/modules/accounting/">
        <i class="fas fa-coins"></i> Accounting
    </a>

    <a href="/ABC-Drinking-water/manage_users.php">
        <i class="fas fa-users"></i> Users
    </a>

    <a href="/ABC-Drinking-water/activity_log.php">
        <i class="fas fa-history"></i> Activity Log
    </a>

    <a href="/ABC-Drinking-water/reports/">
        <i class="fas fa-file-alt"></i> Reports
    </a>

    <a href="/ABC-Drinking-water/logout.php">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <div class="d-flex align-items-center">
        <button class="menu-toggle" id="menuToggle" style="background: none; border: none; font-size: 20px; margin-right: 15px;">
            <i class="fas fa-bars"></i>
        </button>
        <div class="system-title">
            <i class="fas fa-chalkboard-user me-1"></i> ERP Dashboard
            <span class="badge-live">LIVE</span>
        </div>
    </div>

    <div class="user-box">
        <i class="fas fa-bell"></i>
        <i class="fas fa-envelope"></i>
        <div class="user-avatar">
            <span>A</span>
        </div>
        <span style="font-size: 14px; font-weight: 500;">Admin</span>
    </div>
</div>

<!-- CONTENT -->
<div class="content">
    <?= $dashboardContent ?>
</div>

<script>
// Mobile menu toggle
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');

if (menuToggle) {
    menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('open');
    });
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
            sidebar.classList.remove('open');
        }
    }
});
</script>

</body>
</html>