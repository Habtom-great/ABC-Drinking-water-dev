<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch inputs from the form
    $bank_balance = floatval($_POST['bank_balance']);
    $ledger_balance = floatval($_POST['ledger_balance']);
    $outstanding_checks = floatval($_POST['outstanding_checks']);
    $deposits_in_transit = floatval($_POST['deposits_in_transit']);
    $adjustments = floatval($_POST['adjustments']);

    // Calculate reconciled balance
    $adjusted_bank_balance = $bank_balance - $outstanding_checks + $deposits_in_transit + $adjustments;
    $discrepancy = $adjusted_bank_balance - $ledger_balance;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Reconciliation Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            background-color: #007bff;
            color: #fff;
            padding: 20px 0;
            border-radius: 10px;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #444;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .report-table th, .report-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: right;
        }
        .report-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .highlight {
            color: #d9534f;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Bank Reconciliation Report</h1>
    </header>
    <div class="container">
        <h2>Reconciliation Summary</h2>
        <table class="report-table">
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>Bank Statement Balance</td>
                <td><?php echo number_format($bank_balance, 2); ?></td>
            </tr>
            <tr>
                <td>Less: Outstanding Checks</td>
                <td><?php echo number_format($outstanding_checks, 2); ?></td>
            </tr>
            <tr>
                <td>Add: Deposits in Transit</td>
                <td><?php echo number_format($deposits_in_transit, 2); ?></td>
            </tr>
            <tr>
                <td>Add: Adjustments</td>
                <td><?php echo number_format($adjustments, 2); ?></td>
            </tr>
            <tr>
                <th>Adjusted Bank Balance</th>
                <th><?php echo number_format($adjusted_bank_balance, 2); ?></th>
            </tr>
            <tr>
                <td>Company Ledger Balance</td>
                <td><?php echo number_format($ledger_balance, 2); ?></td>
            </tr>
            <tr>
                <th>Discrepancy</th>
                <th class="highlight"><?php echo number_format($discrepancy, 2); ?></th>
            </tr>
        </table>
    </div>
    <footer class="footer">
        <p>&copy; 2025 Professional Accounting Solutions</p>
    </footer>
</body>
</html>
