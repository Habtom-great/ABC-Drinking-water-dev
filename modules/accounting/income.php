<?php
// Query to get income statement data
$query = "SELECT * FROM general_ledger WHERE account_type IN ('Revenue', 'Expenses')";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Statement Report</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Income Statement Report</h1>
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
                <?php
                $total_revenue = 0;
                $total_expenses = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['account_type'] == 'Revenue') {
                        $total_revenue += $row['balance'];
                    } else {
                        $total_expenses += $row['balance'];
                    }
                ?>
                    <tr>
                        <td><?php echo $row['account_name']; ?></td>
                        <td><?php echo $row['account_type']; ?></td>
                        <td><?php echo number_format($row['balance'], 2); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th>Total Revenue</th>
                    <td></td>
                    <td><?php echo number_format($total_revenue, 2); ?></td>
                </tr>
                <tr>
                    <th>Total Expenses</th>
                    <td></td>
                    <td><?php echo number_format($total_expenses, 2); ?></td>
                </tr>
                <tr>
                    <th>Net Income</th>
                    <td></td>
                    <td><?php echo number_format($total_revenue - $total_expenses, 2); ?></td>
                </tr>
            </tbody>
        </table>

        <button class="btn-export" onclick="exportReport('income_statement')">Export as CSV</button>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Your Company Name | All Rights Reserved</p>
    </footer>
</body>
</html>
