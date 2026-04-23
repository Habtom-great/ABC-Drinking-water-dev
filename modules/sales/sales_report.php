<?php 
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abc_company";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch distinct salespeople
$salespeople_query = "SELECT DISTINCT salesperson_id, salesperson_name FROM sales ORDER BY salesperson_name";
$salespeople_result = $conn->query($salespeople_query);

// Fetch distinct branches
$branches_query = "SELECT DISTINCT branch FROM sales ORDER BY branch";
$branches_result = $conn->query($branches_query);

// Retrieve form input
$branch = $_POST['branch_id'] ?? 'All';
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$salesperson = $_POST['salesperson'] ?? null;
$sort_by = $_POST['sort_by'] ?? 'date';

// Initialize totals
$total_quantity = 0;
$total_sales = 0;
$total_vat = 0;


// Build query
$query = "SELECT * FROM sales WHERE 1=1";
if ($branch !== 'All') {
    $query .= " AND branch = '" . $conn->real_escape_string($branch) . "'";
}
if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND sale_date BETWEEN '" . $conn->real_escape_string($start_date) . "' AND '" . $conn->real_escape_string($end_date) . "'";
}
if (!empty($salesperson)) {
    $query .= " AND salesperson_id = '" . $conn->real_escape_string($salesperson) . "'";
}
$query .= " ORDER BY " . $conn->real_escape_string($sort_by) . " ASC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <title>Sales Report - ABC Company</title>
 <style>
 body {
  font-family: Arial, sans-serif;
 }

 h1,
 h2,
 h3 {
  text-align: center;
 }

 table {
  width: 100%;
  border-collapse: collapse;
 }

 th,
 td {
  padding: 8px;
  text-align: left;
  border: 1px solid #ddd;
 }

 th {
  background-color: #4CAF50;
  color: white;
  cursor: pointer;
 }

 tr:nth-child(even) {
  background-color: #f2f2f2;
 }
 </style>
 <script>
 function confirmDelete(id) {
  return confirm("Are you sure you want to delete this record?");
 }

 function exportTable(format) {
  window.location.href = 'export.php?format=' + format;
 }
 </script>
</head>

<body>

 <h1>ABC Company</h1>
 <h2>Sales Report</h2>
 <h3>Branch: <?= htmlspecialchars($branch) ?> | Date Range: <?= htmlspecialchars($start_date ?? 'N/A') ?> to
  <?= htmlspecialchars($end_date ?? 'N/A') ?></h3>



 <form method="POST">
  <label>Branch Name:</label>
  <select name="branch_id">
   <option value="All">All</option>
   <option value="Branch Name">Branch Name</option>

  </select>

  <label>Start Date:</label>
  <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">

  <label>End Date:</label>
  <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">

  <label>Salesperson:</label>
  <input type="text" name="salesperson" value="<?= htmlspecialchars($salesperson) ?>">

  <label>Sort By:</label>
  <select name="sort_by">
   <option value="date">Date</option>
   <option value="invoice_no">Invoice No.</option>
   <option value="item_description">Item</option>
   <option value="salesperson_name">Salesperson</option>
   <option value="branch_name">Branch Name</option>
  </select>

  <button type="submit">Filter</button>
 </form>

 <table>
  <thead>
   <tr>
    <th onclick="sortTable(0)">Date</th>
    <th>Invoice No.</th>
    <th>Item</th>
    <th>Quantity</th>
    <th>Unit Price</th>
    <th>Total Sales Before VAT</th>
    <th>VAT</th>
    <th>Total Sales After VAT</th>
    <th>Salesperson</th>
    <th>Branch</th>
    <th>Actions</th>
   </tr>
  </thead>
  <tbody>
   <?php while ($row = $result->fetch_assoc()): ?>
   <?php
                    $quantity = floatval($row['quantity'] ?? 0);
                    $unit_price = floatval($row['unit_price'] ?? 0);
                    $sales_before_vat = floatval($row['total_sales_before_vat'] ?? 0);
                    $vat = floatval($row['vat'] ?? 0);
                    $total_sales_value = floatval($row['total_sales_after_vat'] ?? 0);
                    $branch_name = $row['branch_name'] ?? 'N/A';

                    $total_quantity += $quantity;
                    $total_sales += $sales_before_vat;
                    $total_vat += $vat;
                ?>
   <tr>
    <td><?= htmlspecialchars($row['sale_date'] ?? '') ?></td>
    <td><?= htmlspecialchars($row['invoice_no'] ?? '') ?></td>
    <td><?= htmlspecialchars($row['item_description'] ?? '') ?></td>
    <td><?= number_format($quantity, 2) ?></td>
    <td><?= number_format($unit_price, 2) ?></td>
    <td><?= number_format($sales_before_vat, 2) ?></td>
    <td><?= number_format($vat, 2) ?></td>
    <td><?= number_format($total_sales_value, 2) ?></td>
    <td><?= htmlspecialchars($row['salesperson_name'] ?? '') ?></td>
    <td><?= htmlspecialchars($branch_name) ?></td>
    <td>
     <a href='edit_sales.php?id=<?= $row['id'] ?>'>Edit</a> |
     <a href='delete.php?id=<?= $row['id'] ?>' onclick='return confirmDelete(<?= $row['id'] ?>)'>Delete</a>
    </td>
   </tr>
   <?php endwhile; ?>
  </tbody>
  <tfoot>
   <tr>
    <th colspan="3" style="text-align: right;">Total:</th>
    <th><?= number_format($total_quantity, 2) ?></th>
    <th></th>
    <th><?= number_format($total_sales, 2) ?></th>
    <th><?= number_format($total_vat, 2) ?></th>
    <th><?= number_format($total_sales + $total_vat, 2) ?></th>
    <th colspan="3"></th>
   </tr>
  </tfoot>
 </table>

 <button onclick="exportTable('excel')">Export to Excel</button>
 <button onclick="exportTable('word')">Export to Word</button>
 <button onclick="exportTable('pdf')">Export to PDF</button>

</body>

</html>

<?php $conn->close(); ?>