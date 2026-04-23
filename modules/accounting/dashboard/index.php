<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../db.php';


require_once("../../../includes/auth.php");

checkAuth();
checkRole(['admin','accountant']);

// Helper function to get single value from query
function getCount($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        return 0;
    }
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

// Helper to get sum value
function getSum($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        return 0;
    }
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

// Get main KPI data using correct table columns
$totalSales = getCount($conn, "SELECT COUNT(*) as total FROM sales");
$totalProducts = getCount($conn, "SELECT COUNT(*) as total FROM products");
$lowStock = getCount($conn, "SELECT COUNT(*) as total FROM inventory WHERE qty < 10");
$users = getCount($conn, "SELECT COUNT(*) as total FROM users");

// Get revenue data from sales table (using total_sales_after_vat)
$totalRevenue = getSum($conn, "SELECT SUM(total_sales_after_vat) as total FROM sales WHERE total_sales_after_vat IS NOT NULL");
$totalPaid = getSum($conn, "SELECT SUM(amount_paid) as total FROM sales WHERE amount_paid IS NOT NULL");
$totalDue = getSum($conn, "SELECT SUM(amount_due) as total FROM sales WHERE amount_due IS NOT NULL");
$averageOrderValue = $totalSales > 0 ? round($totalRevenue / $totalSales, 2) : 0;

// Get monthly sales for chart using invoice_date
$currentYear = date('Y');
$monthlySales = [];
for ($i = 1; $i <= 12; $i++) {
    $sql = "SELECT COALESCE(SUM(total_sales_after_vat), 0) as total 
            FROM sales 
            WHERE YEAR(invoice_date) = $currentYear 
            AND MONTH(invoice_date) = $i";
    $monthlySales[] = getSum($conn, $sql);
}

// Get recent low stock items
$lowStockItems = [];
$result = $conn->query("SELECT p.name, i.qty FROM inventory i JOIN products p ON i.product_id = p.id WHERE i.qty < 10 ORDER BY i.qty ASC LIMIT 5");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $lowStockItems[] = $row;
    }
}

// Get recent sales from your actual sales table
$recentSales = [];
$result = $conn->query("SELECT id, sale_id, sales_order_no, customer_name, total_sales_after_vat, invoice_date, amount_paid, amount_due 
                        FROM sales 
                        WHERE invoice_date IS NOT NULL 
                        ORDER BY invoice_date DESC, id DESC 
                        LIMIT 5");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recentSales[] = $row;
    }
}

// Get top performing salesperson
$topSalesperson = [];
$result = $conn->query("SELECT salesperson_name, COUNT(*) as sale_count, SUM(total_sales_after_vat) as total_sales 
                        FROM sales 
                        WHERE salesperson_name IS NOT NULL AND total_sales_after_vat IS NOT NULL
                        GROUP BY salesperson_name 
                        ORDER BY total_sales DESC 
                        LIMIT 1");
if ($result && $result->num_rows > 0) {
    $topSalesperson = $result->fetch_assoc();
}

// Get total VAT collected
$totalVAT = getSum($conn, "SELECT SUM(vat) as total FROM sales WHERE vat IS NOT NULL");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC Company ERP Dashboard</title>
    <link rel="stylesheet" href="/ABC-Drinking-water/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            color: #1e293b;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #0f172a;
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #1e293b;
        }

        .sidebar-header h3 {
            font-size: 18px;
            margin: 0;
            font-weight: 600;
        }

        .sidebar-header p {
            font-size: 11px;
            color: #94a3b8;
            margin: 5px 0 0;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
        }

        .sidebar-nav a:hover {
            background: #1e293b;
            color: white;
        }

        .sidebar-nav a i {
            width: 20px;
            font-size: 16px;
        }

        .sidebar-nav .active {
            background: #4f46e5;
            color: white;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 20px 30px;
        }

        /* Topbar */
        .topbar {
            background: white;
            border-radius: 16px;
            padding: 12px 24px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #4f46e5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-trend {
            font-size: 11px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Main 2-Column Layout */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 24px;
            margin-bottom: 28px;
        }

        /* Cards */
        .dashboard-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
        }

        .card-badge {
            font-size: 11px;
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 30px;
            color: #475569;
        }

        .chart-container {
            padding: 20px;
            height: 300px;
        }

        /* Lists */
        .list-item {
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .item-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .item-name {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }

        .item-meta {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .item-amount {
            font-weight: 600;
            font-size: 14px;
        }

        .status-badge {
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-block;
            margin-top: 4px;
        }

        .status-paid {
            background: #dcfce7;
            color: #15803d;
        }

        .status-due {
            background: #fee2e2;
            color: #dc2626;
        }

        .text-success { color: #15803d; }
        .text-danger { color: #dc2626; }

        .bg-primary-light { background: #eef2ff; color: #4f46e5; }
        .bg-success-light { background: #dcfce7; color: #15803d; }
        .bg-warning-light { background: #fef3c7; color: #d97706; }
        .bg-danger-light { background: #fee2e2; color: #dc2626; }
        .bg-info-light { background: #e0f2fe; color: #0284c7; }
        .bg-purple-light { background: #f3e8ff; color: #7c3aed; }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-grid { grid-template-columns: 1fr; }
            .stats-grid { gap: 15px; }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: 0.3s; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .menu-toggle { display: block !important; }
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            margin-right: 15px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-tint me-2"></i>ABC Water</h3>
        <p>Enterprise Resource Planning</p>
    </div>
    <div class="sidebar-nav">
        <a href="/ABC-Drinking-water/admin_dashboard.php" class="active">
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
        <a href="/ABC-Drinking-water/logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="d-flex align-items-center">
            <button class="menu-toggle" id="menuToggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="page-title">
                <i class="fas fa-chalkboard-user me-2" style="color: #4f46e5;"></i>Dashboard
            </div>
        </div>
        <div class="user-info">
            <i class="fas fa-bell" style="color: #64748b; cursor: pointer;"></i>
            <i class="fas fa-envelope" style="color: #64748b; cursor: pointer;"></i>
            <div class="user-avatar">A</div>
            <span style="font-weight: 500;">Admin</span>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h4 style="font-weight: 600; margin-bottom: 4px;">Welcome back, Administrator</h4>
            <p style="color: #64748b; margin: 0; font-size: 13px;">Here's what's happening with your business today.</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <span class="badge bg-success" style="padding: 8px 16px;">
                <i class="far fa-calendar-alt me-1"></i> <?= date('l, F j, Y') ?>
            </span>
        </div>
    </div>

    <!-- KPI Cards Row 1 -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon bg-primary-light">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-value"><?= number_format($totalSales) ?></div>
            <div class="stat-label">Total Sales</div>
            <div class="stat-trend text-success">
                <i class="fas fa-arrow-up"></i> <span>Active orders</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-success-light">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-value">$<?= number_format($totalRevenue, 2) ?></div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-trend">
                <i class="fas fa-receipt"></i> <span>AOV: $<?= $averageOrderValue ?></span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-info-light">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-value"><?= number_format($totalProducts) ?></div>
            <div class="stat-label">Products</div>
            <div class="stat-trend">
                <i class="fas fa-package"></i> <span>In catalog</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-danger-light">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value <?= $lowStock > 0 ? 'text-danger' : '' ?>"><?= number_format($lowStock) ?></div>
            <div class="stat-label">Low Stock Alert</div>
            <?php if($lowStock > 0): ?>
            <div class="stat-trend text-danger">
                <i class="fas fa-clock"></i> <span>Reorder needed</span>
            </div>
            <?php else: ?>
            <div class="stat-trend text-success">
                <i class="fas fa-check-circle"></i> <span>Stock OK</span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- KPI Cards Row 2 -->
    <div class="stats-grid" style="margin-top: -8px;">
        <div class="stat-card">
            <div class="stat-icon bg-purple-light">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value"><?= number_format($users) ?></div>
            <div class="stat-label">System Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning-light">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-value">$<?= number_format($totalPaid, 2) ?></div>
            <div class="stat-label">Amount Collected</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-danger-light">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">$<?= number_format($totalDue, 2) ?></div>
            <div class="stat-label">Outstanding Due</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-info-light">
                <i class="fas fa-percent"></i>
            </div>
            <div class="stat-value">$<?= number_format($totalVAT, 2) ?></div>
            <div class="stat-label">VAT Collected</div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-grid">
        <!-- Chart Section -->
        <div class="dashboard-card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-chart-bar me-2" style="color: #4f46e5;"></i>Monthly Sales Performance</span>
                <span class="card-badge"><?= $currentYear ?></span>
            </div>
            <div class="chart-container">
                <canvas id="salesChart" style="width: 100%; height: 260px;"></canvas>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div>
            <!-- Top Salesperson -->
            <?php if(!empty($topSalesperson)): ?>
            <div class="dashboard-card mb-4">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-trophy me-2" style="color: #f59e0b;"></i>Top Performer</span>
                    <span class="card-badge">This Month</span>
                </div>
                <div style="padding: 16px 20px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="item-name" style="font-size: 16px;"><?= htmlspecialchars($topSalesperson['salesperson_name']) ?></div>
                            <div class="item-meta">Salesperson</div>
                        </div>
                        <div class="text-end">
                            <div class="item-amount" style="font-size: 18px;">$<?= number_format($topSalesperson['total_sales'], 2) ?></div>
                            <div class="item-meta"><?= $topSalesperson['sale_count'] ?> sales</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Recent Sales -->
            <div class="dashboard-card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-clock me-2" style="color: #4f46e5;"></i>Recent Transactions</span>
                    <a href="/ABC-Drinking-water/modules/sales/" style="font-size: 11px; color: #4f46e5; text-decoration: none;">View All →</a>
                </div>
                <div>
                    <?php if(count($recentSales) > 0): ?>
                        <?php foreach($recentSales as $sale): ?>
                        <div class="list-item">
                            <div class="item-info">
                                <div class="item-icon bg-primary-light">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div>
                                    <div class="item-name"><?= htmlspecialchars($sale['customer_name'] ?? 'Walk-in Customer') ?></div>
                                    <div class="item-meta">
                                        <?= $sale['sales_order_no'] ?? 'SO-' . $sale['id'] ?>
                                        • <?= $sale['invoice_date'] ? date('M d, Y', strtotime($sale['invoice_date'])) : 'Date N/A' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="item-amount">$<?= number_format($sale['total_sales_after_vat'] ?? 0, 2) ?></div>
                                <span class="status-badge <?= ($sale['amount_due'] ?? 0) > 0 ? 'status-due' : 'status-paid' ?>">
                                    <?= ($sale['amount_due'] ?? 0) > 0 ? 'Due: $' . number_format($sale['amount_due'], 2) : 'Paid' ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="padding: 40px 20px; text-align: center;">
                            <i class="fas fa-inbox" style="font-size: 40px; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                            <span style="font-size: 13px; color: #64748b;">No recent sales</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Low Stock Summary -->
            <div class="dashboard-card mt-4">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-box-open me-2" style="color: #dc2626;"></i>Low Stock Items</span>
                    <a href="/ABC-Drinking-water/modules/inventory/" style="font-size: 11px; color: #4f46e5; text-decoration: none;">Manage →</a>
                </div>
                <div>
                    <?php if(count($lowStockItems) > 0): ?>
                        <?php foreach($lowStockItems as $item): ?>
                        <div class="list-item">
                            <div class="item-info">
                                <div class="item-icon bg-danger-light">
                                    <i class="fas fa-cube"></i>
                                </div>
                                <div>
                                    <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                                    <div class="item-meta">Critical level</div>
                                </div>
                            </div>
                            <div class="item-amount text-danger"><?= $item['qty'] ?> units</div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="padding: 40px 20px; text-align: center;">
                            <i class="fas fa-check-circle" style="font-size: 40px; color: #15803d; margin-bottom: 10px; display: block;"></i>
                            <span style="font-size: 13px; color: #64748b;">All stock levels are healthy</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-4 pt-3" style="border-top: 1px solid #e2e8f0;">
        <p style="font-size: 12px; color: #64748b;">
            <i class="fas fa-chart-pie me-1"></i> ABC Company ERP System · Real-time Dashboard · Secure Enterprise Platform
        </p>
    </div>
</div>

<script>
// Sales Chart
const canvas = document.getElementById('salesChart');
if (canvas) {
    const ctx = canvas.getContext('2d');
    const monthlyData = <?= json_encode($monthlySales) ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue ($)',
                data: monthlyData,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.05)',
                borderWidth: 2.5,
                pointRadius: 4,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: 'white',
                pointBorderWidth: 2,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: $' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#e2e8f0', drawBorder: false },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        },
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
}

// Toggle sidebar for mobile
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
}
</script>

</body>
</html>