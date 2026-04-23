<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abc_company";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form or query parameters
$branch = isset($_POST['branch_id']) ? $_POST['branch_id'] : 'All';
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
$section = isset($_POST['section']) ? $_POST['section'] : null;
$salesperson = isset($_POST['salesperson']) ? $_POST['salesperson'] : null;

// Initialize totals
$total_quantity = 0;
$total_value = 0;

// Construct SQL query based on input
$query = "SELECT * FROM sales WHERE 1 = 1";

if ($branch !== 'All') {
    $query .= " AND branch = '" . $conn->real_escape_string($branch) . "'";
}
if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND sale_date BETWEEN '" . $conn->real_escape_string($start_date) . "' AND '" . $conn->real_escape_string($end_date) . "'";
}
if (!empty($section)) {
    $query .= " AND section = '" . $conn->real_escape_string($section) . "'";
}
if (!empty($salesperson)) {
    $query .= " AND salesperson_id = '" . $conn->real_escape_string($salesperson) . "'";
}

// Execute the query
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h1, h2, h3 {
            text-align: center;
        }
        table {
            width: 85%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-header h2, .report-header h3 {
            margin: 5px 0;
        }
        tfoot td {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>ABC Company</h1>
        <h2>Sales Report</h2>
        <h3>Branch: <?= htmlspecialchars($branch) ?></h3>
        <h3>Date Range: <?= htmlspecialchars($start_date ?: 'N/A') ?> to <?= htmlspecialchars($end_date ?: 'N/A') ?></h3>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Invoice No.</th>
                    <th>Item ID</th>
                    <th>Item Description</th>
                    <th>UOM</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Value</th>
                    <th>Salesperson ID</th>
                    <th>Salesperson Name</th>
                    <th>Branch</th>
                    <th>Section</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    // Accumulate totals
                    $total_quantity += $row['quantity'];
                    $total_value += $row['total_price'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['sale_date']) ?></td>
                        <td><?= htmlspecialchars($row['reference']) ?></td>
                        <td><?= htmlspecialchars($row['invoice_no']) ?></td>
                        <td><?= htmlspecialchars($row['item_id']) ?></td>
                        <td><?= htmlspecialchars($row['item_description']) ?></td>
                        <td><?= htmlspecialchars($row['uom']) ?></td>
                        <td><?= number_format($row['quantity'], 2) ?></td>
                        <td><?= number_format($row['unit_price'], 2) ?></td>
                        <td><?= number_format($row['total_price'], 2) ?></td>
                        <td><?= htmlspecialchars($row['salesperson_id']) ?></td>
                        <td><?= htmlspecialchars($row['salesperson_name']) ?></td>
                        <td><?= isset($row['branch']) ? htmlspecialchars($row['branch']) : 'N/A' ?></td>
                        <td><?= htmlspecialchars($row['section']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6" style="text-align: right;">Total:</th>
                    <th><?= number_format($total_quantity, 2) ?></th>
                    <th></th>
                    <th><?= number_format($total_value, 2) ?></th>
                    <th colspan="3"></th>
                </tr>
            </tfoot>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No records found for the given criteria.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>
