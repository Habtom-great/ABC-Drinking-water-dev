<?php
// Include database connection
include('db_connection.php');
include('header.php'); // Include header

// Check if the account ID is set in the URL
if (!isset($_GET['account_id']) || empty($_GET['account_id'])) {
    die("<div class='container mt-5'><h3 class='text-danger'>❌ Invalid request! Account ID is missing.</h3></div>");
}

$account_id = intval($_GET['account_id']);

// Fetch existing account details
$query = "SELECT * FROM charts_of_accounts WHERE account_id = ?";
$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    die("<div class='container mt-5'><h3 class='text-danger'>❌ Database error: " . mysqli_error($conn) . "</h3></div>");
}

mysqli_stmt_bind_param($stmt, "i", $account_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    die("<div class='container mt-5'><h3 class='text-danger'>❌ Account not found!</h3></div>");
}

$account = mysqli_fetch_assoc($result);

// Handle form submission for update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_name = trim($_POST['account_name']);
    $account_type = trim($_POST['account_type']);
    $account_classification = trim($_POST['account_classification']);
    $balance = floatval($_POST['balance']);
    $beginning_balance = floatval($_POST['beginning_balance']);

    // Validate input
    if (empty($account_name) || empty($account_type) || empty($account_classification)) {
        echo "<div class='container mt-3'><h3 class='text-danger'>❌ Please fill in all required fields.</h3></div>";
    } else {
        $update_query = "UPDATE charts_of_accounts SET account_name=?, account_type=?, account_classification=?, balance=?, beginning_balance=? WHERE account_id=?";
        $stmt = mysqli_prepare($conn, $update_query);

        if (!$stmt) {
            die("<div class='container mt-5'><h3 class='text-danger'>❌ SQL Error: " . mysqli_error($conn) . "</h3></div>");
        }

        mysqli_stmt_bind_param($stmt, "sssddi", $account_name, $account_type, $account_classification, $balance, $beginning_balance, $account_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='container mt-3'><h3 class='text-success'>✅ Account updated successfully!</h3></div>";
            echo "<script>setTimeout(() => { window.location.href='manage_charts_of_accounts.php'; }, 2000);</script>";
        } else {
            echo "<div class='container mt-3'><h3 class='text-danger'>❌ Error updating account: " . mysqli_error($conn) . "</h3></div>";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="mb-4 text-center">Edit Account</h2>
        <form method="post">
            <div class="mb-3">
                <label for="account_name" class="form-label">Account Name</label>
                <input type="text" class="form-control" id="account_name" name="account_name" value="<?= htmlspecialchars($account['account_name']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="account_type" class="form-label">Account Type</label>
                <select class="form-select" id="account_type" name="account_type" required>
                    <option value="Asset" <?= ($account['account_type'] == 'Asset') ? 'selected' : '' ?>>Asset</option>
                    <option value="Liability" <?= ($account['account_type'] == 'Liability') ? 'selected' : '' ?>>Liability</option>
                    <option value="Equity" <?= ($account['account_type'] == 'Equity') ? 'selected' : '' ?>>Equity</option>
                    <option value="Revenue" <?= ($account['account_type'] == 'Revenue') ? 'selected' : '' ?>>Revenue</option>
                    <option value="Expense" <?= ($account['account_type'] == 'Expense') ? 'selected' : '' ?>>Expense</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="account_classification" class="form-label">Account Classification</label>
                <input type="text" class="form-control" id="account_classification" name="account_classification" value="<?= htmlspecialchars($account['account_classification']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="balance" class="form-label">Balance</label>
                <input type="number" step="0.01" class="form-control" id="balance" name="balance" value="<?= $account['balance'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="beginning_balance" class="form-label">Beginning Balance</label>
                <input type="number" step="0.01" class="form-control" id="beginning_balance" name="beginning_balance" value="<?= $account['beginning_balance'] ?>" required>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary me-2">Update Account</button>
                <a href="manage_charts_of_accounts.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('footer.php'); ?>
