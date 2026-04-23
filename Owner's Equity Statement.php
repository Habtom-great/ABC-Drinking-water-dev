<?php
// Query for owner's equity data
$query = "SELECT * FROM general_ledger WHERE account_type = 'Equity'";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner's Equity Statement</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Owner's Equity Statement</h1>
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

        <button class="btn-export" onclick="exportReport('owners_equity')">Export as CSV</button>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Your Company Name | All Rights Reserved</p>
    </footer>
</body>
</html>
<?php
$type = $_GET['type'];

// Query to get the data based on the type
switch ($type) {
    case 'balance_sheet':
        $query = "SELECT * FROM general_ledger";
        break;
    case 'income_statement':
        $query = "SELECT * FROM general_ledger WHERE account_type IN ('Revenue', 'Expenses')";
        break;
    case 'cash_flow':
        $query = "SELECT * FROM general_ledger WHERE account_type IN ('Operating', 'Investing', 'Financing')";
        break;
    case 'owners_equity':
        $query = "SELECT * FROM general_ledger WHERE account_type = 'Equity'";
        break;
    default:
        die('Invalid type');
}

$result = mysqli_query($conn, $query);

// Generate CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="' . $type . '_report.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array('Account Name', 'Account Type', 'Balance'));

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
?>
