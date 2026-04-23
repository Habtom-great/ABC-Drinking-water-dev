<?php
// PHP code to save ledger entry
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account_name = $_POST['account_name'];
    $account_type = $_POST['account_type'];
    $balance = $_POST['balance'];

    // Insert into the database (example query)
    $query = "INSERT INTO general_ledger (account_name, account_type, balance) VALUES ('$account_name', '$account_type', '$balance')";
    mysqli_query($conn, $query);  // Assuming $conn is your database connection
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create/Edit General Ledger Accounts</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Create/Edit General Ledger Accounts</h1>
        </header>

        <form action="" method="POST">
            <div class="form-group">
                <label for="account_name">Account Name:</label>
                <input type="text" id="account_name" name="account_name" required>
            </div>
            <div class="form-group">
                <label for="account_type">Account Type:</label>
                <select name="account_type" id="account_type" required>
                    <option value="Assets">Assets</option>
                    <option value="Liabilities">Liabilities</option>
                    <option value="Equity">Equity</option>
                    <option value="Revenue">Revenue</option>
                    <option value="Expenses">Expenses</option>
                </select>
            </div>
            <div class="form-group">
                <label for="balance">Balance:</label>
                <input type="number" id="balance" name="balance" required>
            </div>
            <button type="submit" class="btn-submit">Save Account</button>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Your Company Name | All Rights Reserved</p>
    </footer>
</body>
</html>
kkkkkkkkkkkk
<?php
// Fetch general ledger accounts from the database
$query = "SELECT * FROM general_ledger";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet Report</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Balance Sheet Report</h1>
        </header>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Account Name</th>
                    <th>Account Type</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['account_name']; ?></td>
                        <td><?php echo $row['account_type']; ?></td>
                        <td><?php echo number_format($row['balance'], 2); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <button class="btn-export" onclick="exportReport('balance_sheet')">Export as CSV</button>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Your Company Name | All Rights Reserved</p>
    </footer>

    <script>
        function exportReport(type) {
            window.location.href = 'export.php?type=' + type;
        }
    </script>
</body>
</html>
