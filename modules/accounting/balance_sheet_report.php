<?php
// Include database connection
include('db_connect.php');

// Fetch ledger data categorized as assets, liabilities, and other categories
$query = "SELECT * FROM general_ledger WHERE category IN ('Expense', 'Receivables', 'Inventory', 'Cost of Sales', 'Current Assets', 'Current Liabilities', 'Retained Earnings', 'Capital')";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet Report</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Add your custom stylesheet -->
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <h1>Balance Sheet Report</h1>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="reports.php">Reports</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>

    <!-- Main Content Section -->
    <div class="container">
        <div class="report-section">
            <h2>Balance Sheet</h2>

            <!-- Expense Section -->
            <h3>Expenses</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display Expense accounts
                    mysqli_data_seek($result, 0);  // Reset result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['category'] == 'Expense') {
                            echo "<tr>
                                    <td>{$row['account_id']}</td>
                                    <td>{$row['account_name']}</td>
                                    <td>" . number_format($row['balance'], 2) . "</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <!-- Receivables Section -->
            <h3>Receivables</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display Receivables accounts
                    mysqli_data_seek($result, 0);  // Reset result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['category'] == 'Receivables') {
                            echo "<tr>
                                    <td>{$row['account_id']}</td>
                                    <td>{$row['account_name']}</td>
                                    <td>" . number_format($row['balance'], 2) . "</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <!-- Inventory Section -->
            <h3>Inventory</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display Inventory accounts
                    mysqli_data_seek($result, 0);  // Reset result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['category'] == 'Inventory') {
                            echo "<tr>
                                    <td>{$row['account_id']}</td>
                                    <td>{$row['account_name']}</td>
                                    <td>" . number_format($row['balance'], 2) . "</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <!-- Cost of Sales Section -->
            <h3>Cost of Sales</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display Cost of Sales accounts
                    mysqli_data_seek($result, 0);  // Reset result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['category'] == 'Cost of Sales') {
                            echo "<tr>
                                    <td>{$row['account_id']}</td>
                                    <td>{$row['account_name']}</td>
                                    <td>" . number_format($row['balance'], 2) . "</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <!-- Current Assets Section -->
            <h3>Current Assets</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display Current Assets accounts
                    mysqli_data_seek($result, 0);  // Reset result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['category'] == 'Current Assets') {
                            echo "<tr>
                                    <td>{$row['account_id']}</td>
                                    <td>{$row['account_name']}</td>
                                    <td>" . number_format($row['balance'], 2) . "</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <!-- Current Liabilities Section -->
            <h3>Current Liabilities</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display Current Liabilities accounts
                    mysqli_data_seek($result, 0);  // Reset result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['category'] == 'Current Liabilities') {
                            echo "<tr>
                                    <td>{$row['account_id']}</td>
                                    <td>{$row['account_name']}</td>
                                    <td>" . number_format($row['balance'], 2) . "</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <!-- Retained Earnings Section -->
            <h3>Retained Earnings</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display Retained Earnings accounts
                    mysqli_data_seek($result, 0);  // Reset result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['category'] == 'Retained Earnings') {
                            echo "<tr>
                                    <td>{$row['account_id']}</td>
                                    <td>{$row['account_name']}</td>
                                    <td>" . number_format($row['balance'], 2) . "</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <!-- Capital Section -->
            <h3>Capital</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display Capital accounts
                    mysqli_data_seek($result, 0);  // Reset result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['category'] == 'Capital') {
                            echo "<tr>
                                    <td>{$row['account_id']}</td>
                                    <td>{$row['account_name']}</td>
                                    <td>" . number_format($row['balance'], 2) . "</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2025 Your Company Name</p>
    </footer>
</body>
</html>

kkkkkkkkkk
<?php
// Include database connection at the top
include('db_connect.php');  // Assuming you save the above database connection in 'db_connect.php'

// Fetch current assets, non-current assets, current liabilities, and non-current liabilities from the database
$query = "SELECT * FROM general_ledger WHERE category IN ('Current Asset', 'Non-Current Asset', 'Current Liability', 'Non-Current Liability')";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet Report</title>
    <link rel="stylesheet" href="assets/css/style.css">  <!-- Include external CSS file for styling -->
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="header-content">
            <h1>Balance Sheet Report</h1>
            <nav class="navbar">
                <a href="index.php">Home</a>
                <a href="reports.php">Reports</a>
                <a href="contact.php">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Main Content Section -->
    <div class="container">
        <div class="report-section">
            <h2>Balance Sheet</h2>

            <!-- Report Filters: Date, Option, and Title -->
            <div class="report-filters">
                <form method="GET" action="">
                    <div class="filter-item">
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date">
                    </div>
                    <div class="filter-item">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date">
                    </div>
                    <div class="filter-item">
                        <button type="submit" class="btn-filter">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Balance Sheet Details -->
            <h3>Assets</h3>
            <h4>Current Assets</h4>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the result and display current assets
                    mysqli_data_seek($result, 0); // Reset the result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['category'] == 'Current Asset') {
                            echo "<tr>
                                    <td>{$row['account_id']}</td>
                                    <td>{$row['account_name']}</td>
                                    <td>" . number_format($row['balance'], 2) . "</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <h4>Non-Current Assets</h4>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the result and display non-current assets
                    mysqli_data_seek($result, 0); // Reset the result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['category'] == 'Non-Current Asset') {
                            echo "<tr>
                                    <td>{$row['account_id']}</td>
                                    <td>{$row['account_name']}</td>
                                    <td>" . number_format($row['balance'], 2) . "</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <h3>Liabilities</h3>
            <h4>Current Liabilities</h4>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the result and display current liabilities
                    mysqli_data_seek($result, 0); // Reset the result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['category'] == 'Current Liability') {
                            echo "<tr>
                                    <td>{$row['account_id']}</td>
                                    <td>{$row['account_name']}</td>
                                    <td>" . number_format($row['balance'], 2) . "</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <h4>Non-Current Liabilities</h4>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the result and display non-current liabilities
                    mysqli_data_seek($result, 0); // Reset the result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['category'] == 'Non-Current Liability') {
                            echo "<tr>
                                    <td>{$row['account_id']}</td>
                                    <td>{$row['account_name']}</td>
                                    <td>" . number_format($row['balance'], 2) . "</td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <!-- Export Button -->
            <div class="export-btn-container">
                <button class="btn-export" onclick="exportReport('balance_sheet')">Export as CSV</button>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2025 Your Company Name | All Rights Reserved</p>
            <nav class="footer-nav">
                <a href="privacy.php">Privacy Policy</a>
                <a href="terms.php">Terms & Conditions</a>
            </nav>
        </div>
    </footer>

    <!-- Export Script -->
    <script>
        function exportReport(type) {
            window.location.href = 'export.php?type=' + type;
        }
    </script>
</body>
</html>

<?php
// Close the database connection at the very end of the script
mysqli_close($conn);
?>


kkkkkkkk
<?php
// Include database connection at the top
include('db_connect.php');  // Assuming you save the above database connection in 'db_connect.php'

// Fetch general ledger accounts from the database
$query = "SELECT * FROM general_ledger";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet Report</title>
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Header Styling */
        .header {
            background-color: #333;
            color: white;
            padding: 20px;
        }

        .header-content h1 {
            margin: 0;
            font-size: 24px;
        }

        .navbar a {
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            display: inline-block;
        }

        .navbar a:hover {
            background-color: #575757;
        }

        /* Main Container */
        .container {
            width: 50%;
            margin: 20px auto;
        }

        .report-section {
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .report-section h2 {
            font-size: 20px;
        }

        .report-filters {
            margin-bottom: 20px;
        }

        .filter-item {
            margin: 10px 0;
        }

        .filter-item label {
            display: block;
            font-size: 14px;
        }

        .filter-item input, .filter-item select {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .filter-item button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        .filter-item button:hover {
            background-color: #45a049;
        }

        /* Table Styling */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .report-table th, .report-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .report-table th {
            background-color: #f2f2f2;
        }

        .export-btn-container {
            margin-top: 20px;
            text-align: right;
        }

        .export-btn-container .btn-export {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        .export-btn-container .btn-export:hover {
            background-color: #0056b3;
        }

        /* Footer Styling */
        .footer {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .footer-nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }

        .footer-nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <header class="header">
        <div class="header-content">
            <h1>Balance Sheet Report</h1>
            <nav class="navbar">
                <a href="index.php">Home</a>
                <a href="reports.php">Reports</a>
                <a href="contact.php">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Main Content Section -->
    <div class="container">
        <div class="report-section">
            <h2>General Ledger Accounts</h2>

            <!-- Report Filters: Date, Option, and Title -->
            <div class="report-filters">
                <form method="GET" action="">
                    <div class="filter-item">
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date">
                    </div>
                    <div class="filter-item">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date">
                    </div>
                    <div class="filter-item">
                        <label for="account_type">Account Type</label>
                        <select id="account_type" name="account_type">
                            <option value="">Select Account Type</option>
                            <option value="Asset">Asset</option>
                            <option value="Liability">Liability</option>
                            <option value="Equity">Equity</option>
                            <option value="Revenue">Revenue</option>
                            <option value="Expense">Expense</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="account_id">Account ID</label>
                        <input type="text" id="account_id" name="account_id" placeholder="Enter Account ID">
                    </div>
                    <div class="filter-item">
                        <button type="submit" class="btn-filter">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Table to Display the General Ledger -->
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Account Type</th>
                        <th>Balance</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['account_id']; ?></td>
                            <td><?php echo $row['account_name']; ?></td>
                            <td><?php echo $row['account_type']; ?></td>
                            <td><?php echo number_format($row['balance'], 2); ?></td>
                            <td><?php echo $row['date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Export Button -->
            <div class="export-btn-container">
                <button class="btn-export" onclick="exportReport('balance_sheet')">Export as CSV</button>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2025 Your Company Name | All Rights Reserved</p>
            <nav class="footer-nav">
                <a href="privacy.php">Privacy Policy</a>
                <a href="terms.php">Terms & Conditions</a>
            </nav>
        </div>
    </footer>

    <!-- Export Script -->
    <script>
        function exportReport(type) {
            window.location.href = 'export.php?type=' + type;
        }
    </script>

<?php
// Close the database connection at the very end of the script
mysqli_close($conn);
?>

</body>
</html>
