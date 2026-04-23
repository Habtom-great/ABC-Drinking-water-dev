<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/db.php'; // ✅ FIXED (was db_connection.php)

/* Fetch activity logs */
$sql = "SELECT log_id, user_name, action, details, created_at 
        FROM activity_log 
        ORDER BY created_at DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity Log | Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }
        .page-header {
            background: #212529;
            color: #fff;
            padding: 20px;
            border-radius: 6px;
        }
        .table-container {
            background: #ffffff;
            padding: 25px;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            margin-top: 20px;
        }
        .table th {
            background-color: #343a40;
            color: #fff;
            white-space: nowrap;
        }
        .badge-action {
            font-size: 0.85rem;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>

<body>

<div class="container my-4">

    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-0">Activity Log</h3>
            <small>System audit trail and user activities</small>
        </div>
        <a href="admin_dashboard.php" class="btn btn-outline-light btn-sm">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Table -->
    <div class="table-container">
        <h5 class="mb-3">Recent Activities</h5>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['log_id']) ?></td>
                                <td><?= htmlspecialchars($row['user_name']) ?></td>
                                <td>
                                    <span class="badge bg-info badge-action">
                                        <?= htmlspecialchars($row['action']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['details']) ?></td>
                                <td><?= date("Y-m-d H:i", strtotime($row['created_at'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                No activity records found.
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; <?= date("Y"); ?> Inventory Management System | Activity Monitoring
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
