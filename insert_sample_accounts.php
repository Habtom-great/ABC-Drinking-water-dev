<?php
// Include database connection
include('db_connection.php');

// Define sample data
$sql = "
INSERT INTO charts_of_accounts (account_name, account_type, account_classification, balance, beginning_balance) VALUES
('Cash', 'Asset', 'Current', 5000.00, 5000.00),
('Accounts Receivable', 'Asset', 'Current', 12000.00, 10000.00),
('Inventory', 'Asset', 'Current', 15000.00, 14000.00),
('Equipment', 'Asset', 'Non-Current', 30000.00, 32000.00),
('Buildings', 'Asset', 'Non-Current', 100000.00, 98000.00),
('Land', 'Asset', 'Non-Current', 50000.00, 50000.00),
('Accounts Payable', 'Liability', 'Current', 8000.00, 7500.00),
('Salaries Payable', 'Liability', 'Current', 3000.00, 2800.00),
('Loan Payable', 'Liability', 'Non-Current', 50000.00, 52000.00),
('Retained Earnings', 'Equity', 'Non-Current', 60000.00, 58000.00),
('Common Stock', 'Equity', 'Non-Current', 70000.00, 70000.00),
('Service Revenue', 'Revenue', 'Current', 45000.00, 42000.00),
('Sales Revenue', 'Revenue', 'Current', 55000.00, 50000.00),
('Rent Expense', 'Expense', 'Current', 2000.00, 1800.00),
('Utilities Expense', 'Expense', 'Current', 1500.00, 1300.00),
('Depreciation Expense', 'Expense', 'Non-Current', 5000.00, 4800.00)";

// Execute query and handle errors
if (mysqli_query($conn, $sql)) {
    echo "<h3 style='color:green;'>✅ Sample accounts inserted successfully!</h3>";
} else {
    echo "<h3 style='color:red;'>❌ Error inserting data: " . mysqli_error($conn) . "</h3>";
}

// Close the connection
mysqli_close($conn);
?>
