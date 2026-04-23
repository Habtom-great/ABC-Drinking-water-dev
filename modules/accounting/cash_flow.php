<?php
// Query to fetch the necessary data for cash flow
$query = "SELECT * FROM general_ledger WHERE account_type IN ('Operating', 'Investing', 'Financing')";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Flow Statement</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Cash Flow Statement</h1>
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

        <button class="btn-export" onclick="exportReport('cash_flow')">Export as CSV</button>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Your Company Name | All Rights Reserved</p>
    </footer>
</body>
</html>
