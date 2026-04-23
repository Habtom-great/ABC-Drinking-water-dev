<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abc_company";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize report type and records per page
$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : '';
$records_per_page = 10;

// Get the current page number, default to 1 if not set
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

// Check if the report_type is set
if ($report_type) {
    switch ($report_type) {
        case 'users':
            $sql = "SELECT user_id, name, email, telephone, address, role, created_at FROM users ORDER BY created_at DESC LIMIT $start_from, $records_per_page";
            $title = "Users Report";
            break;
        case 'staff':
            $sql = "SELECT staff_id, department, last_name, middle_name, first_name, email, telephone, hire_date FROM staff ORDER BY last_name, middle_name, first_name, hire_date DESC LIMIT $start_from, $records_per_page";
            $title = "Staff Report";
            break;
        case 'salesperson':
            $sql = "SELECT salesperson_id, last_name, middle_name, first_name, email, telephone, branch_name FROM salesperson ORDER BY last_name, middle_name, first_name DESC LIMIT $start_from, $records_per_page";
            $title = "Sales Person Report";
            break;
        case 'vendors':
            $sql = "SELECT vendor_id, last_name, middle_name, first_name, email, telephone, Remained_balance, address FROM vendors ORDER BY last_name, middle_name, first_name DESC LIMIT $start_from, $records_per_page";
            $title = "Vendors Report";
            break;
        case 'inventory':
            $sql = "SELECT item_id, invoice_no, invoice_date, description, qty, unit_price, total_before_vat, vat, total_after_vat FROM inventory ORDER BY invoice_date DESC LIMIT $start_from, $records_per_page";
            $title = "Inventory Report";
            break;
        case 'purchases':
            $sql = "SELECT item_id, item_description, category, quantity, unit_cost, unit_price, total_purchased_before_vat, vat, total_purchased_after_vat, invoice_date FROM inventory ORDER BY invoice_date DESC LIMIT $start_from, $records_per_page";
            $title = "Purchase Report";
            break;
        case 'sales':
            $sql = "SELECT item_id, item_description, category, quantity, unit_price, total_sales_before_vat, vat, total_sales_after_vat, date FROM sales ORDER BY timestamp DESC LIMIT $start_from, $records_per_page";
            $title = "Sales Report";
            break;
        case 'cogs':
            $sql = "SELECT id, item_description, category, quantity, unit_cost, timestamp FROM cogs ORDER BY timestamp DESC LIMIT $start_from, $records_per_page";
            $title = "COGS Report";
            break;
        case 'activity_log':
            $sql = "SELECT log_id, user_name, action, details, timestamp FROM activity_log ORDER BY timestamp DESC LIMIT $start_from, $records_per_page";
            $title = "Activity Log Report";
            break;
        default:
            die("Invalid report type selected.");
    }

    // Query the database for report data
    $result = $conn->query($sql);
    if ($result === false) {
        die("SQL Error: " . $conn->error); // Show the error message if the query failed
    }

    // Get the total number of records for pagination
    $total_records_sql = "SELECT COUNT(*) FROM " . strtolower($report_type);
    $total_records_result = $conn->query($total_records_sql);

    // Check if the query for total records was successful
    if ($total_records_result === false) {
        die("SQL Error for total records: " . $conn->error); // Display error if query fails
    }

    $total_records_row = $total_records_result->fetch_row();
    $total_records = $total_records_row[0];

    // Calculate total number of pages
    $total_pages = ceil($total_records / $records_per_page);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Generate Reports</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
 <style>
 body {
  background-color: #f8f9fa;
 }

 .header {
  background-color: #343a40;
  color: white;
  padding: 10px;
  text-align: center;
 }

 .container {
  margin-top: 15px;
 }

 .table-container {
  margin-top: 20px;
  background: white;
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  border-radius: 8px;
  page-break-before: always;
 }

 .footer {
  background-color: #343a40;
  color: white;
  text-align: center;
  padding: 10px;
  position: fixed;
  bottom: 0;
  width: 100%;
 }

 @media print {
  .pagination {
   display: none;
  }

  .table-container {
   page-break-after: always;
  }

  .footer {
   position: absolute;
   bottom: 0;
   width: 100%;
  }

  .btn {
   display: none;
  }
 }
 </style>
</head>

<body>
 <div class="header">
  <h1>Generate Reports</h1>
  <p>Select a report type to view and download</p>
  <a href="admin_dashboard.php" class="btn btn-light">Back to Dashboard</a>
 </div>

 <div class="container">
  <!-- Form to Select Report Type -->
  <form method="GET" action="generate_report-1.php" class="mb-3">
   <div class="row">
    <div class="col-md-8">
     <select name="report_type" class="form-select" required>
      <option value="">Select Report Type</option>
      <option value="users" <?php echo ($report_type == 'users') ? 'selected' : ''; ?>>Users</option>
      <option value="staff" <?php echo ($report_type == 'staff') ? 'selected' : ''; ?>>Staff</option>
      <option value="salesperson" <?php echo ($report_type == 'salesperson') ? 'selected' : ''; ?>>Sales Person</option>
      <option value="vendors" <?php echo ($report_type == 'vendors') ? 'selected' : ''; ?>>Vendors</option>
      <option value="inventory" <?php echo ($report_type == 'inventory') ? 'selected' : ''; ?>>Inventory</option>
      <option value="purchase" <?php echo ($report_type == 'purchase') ? 'selected' : ''; ?>>Purchases</option>
      <option value="sales" <?php echo ($report_type == 'sales') ? 'selected' : ''; ?>>Sales</option>
      <option value="cogs" <?php echo ($report_type == 'cogs') ? 'selected' : ''; ?>>COGS</option>
      <option value="activity_log" <?php echo ($report_type == 'activity_log') ? 'selected' : ''; ?>>Activity Log
      </option>
     </select>
    </div>
    <div class="col-md-4">
     <button type="submit" class="btn btn-primary w-100">Generate Report</button>
    </div>
   </div>
  </form>

  <!-- Display the Report -->
  <?php if (isset($report_type) && $report_type && $result->num_rows > 0): ?>
  <div class="table-container">
   <h3><?php echo htmlspecialchars($title); ?></h3>
   <table class="table table-striped table-bordered">
    <thead class="table-dark">
     <tr>
      <?php
                            $columns = array_keys($result->fetch_assoc());
                            $result->data_seek(0);
                            foreach ($columns as $col): ?>
      <th><?php echo htmlspecialchars(ucwords(str_replace("_", " ", $col))); ?></th>
      <?php endforeach; ?>
     </tr>
    </thead>
    <tbody>
     <?php while ($row = $result->fetch_assoc()): ?>
     <tr>
      <?php foreach ($row as $value): ?>
      <td><?php echo htmlspecialchars($value); ?></td>
      <?php endforeach; ?>
     </tr>
     <?php endwhile; ?>
    </tbody>
   </table>

   <!-- Pagination Controls -->
   <nav>
    <ul class="pagination justify-content-center">
     <?php if ($current_page > 1): ?>
     <li class="page-item">
      <a class="page-link"
       href="generate_report-1.php?report_type=<?php echo urlencode($report_type); ?>&page=<?php echo $current_page - 1; ?>">Previous</a>
     </li>
     <?php endif; ?>

     <?php for ($i = 1; $i <= $total_pages; $i++): ?>
     <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
      <a class="page-link"
       href="generate_report-1.php?report_type=<?php echo urlencode($report_type); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
     </li>
     <?php endfor; ?>

     <?php if ($current_page < $total_pages): ?>
     <li class="page-item">
      <a class="page-link"
       href="generate_report-1.php?report_type=<?php echo urlencode($report_type); ?>&page=<?php echo $current_page + 1; ?>">Next</a>
     </li>
     <?php endif; ?>
    </ul>
   </nav>
  </div>
  <?php elseif ($report_type): ?>
  <div class="alert alert-warning">No records found for this report type.</div>
  <?php endif; ?>
 </div>

 <div class="footer">
  <p>&copy; <?php echo date("Y"); ?> ABC Company | All Rights Reserved</p>
 </div>

 <!-- Bootstrap JS -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>