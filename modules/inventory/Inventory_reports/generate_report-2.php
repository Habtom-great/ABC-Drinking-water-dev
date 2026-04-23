<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection setup
$servername = "localhost"; // Database server
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "abc_company"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$report_type = null;
$result = null;

// Generate a report based on the selected type
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['report_type']) && $_POST['report_type'] === 'inventory') {
    // Initialize variables with default values
    $branch = $_POST['branch'] ?? '';
    $salesperson = $_POST['salesperson'] ?? '';
    $date_option = $_POST['date_option'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $specific_date = $_POST['specific_date'] ?? '';

    // Build the SQL query for the inventory report with filters
    $sql = "SELECT 
            item_id, 
            item_name, 
            branch, 
       
            salesperson, 
            begin_qty, 
            purchase_qty, 
            sold_qty, 
            (begin_qty + purchase_qty - sold_qty) AS ending_qty, 
            (begin_qty * unit_cost) AS begin_cost, 
            (purchase_qty * unit_cost) AS purchase_cost, 
            (sold_qty * unit_cost) AS cost_of_sold, 
            ((begin_qty + purchase_qty - sold_qty) * unit_cost) AS ending_cost 
        FROM inventory 
        WHERE 1";

    // Add filters dynamically based on user input
    if (!empty($branch)) {
        $sql .= " AND branch = '$branch'";
    }
    if (!empty($salesperson)) {
        $sql .= " AND salesperson = '$salesperson'";
    }
    if ($date_option === 'range' && !empty($start_date) && !empty($end_date)) {
        $sql .= " AND date BETWEEN '$start_date' AND '$end_date'";
    } elseif ($date_option === 'specific' && !empty($specific_date)) {
        $sql .= " AND date = '$specific_date'";
    }

    $sql .= " ORDER BY item_name ASC";

    // Execute the query
    $result = $conn->query($sql);
    if ($result === false) {
        die("SQL Error: " . $conn->error);
    }

    $report_type = 'inventory';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inventory Report</title>
 <!-- Bootstrap CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
 <!-- Custom CSS -->
 <style>
 body {
  background-color: #f8f9fa;
 }

 .header {
  background-color: #343a40;
  color: white;
  padding: 20px;
  text-align: center;
 }

 .container {
  margin-top: 30px;
 }

 .table-container {
  margin-top: 20px;
  background: white;
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  border-radius: 8px;
 }
 </style>
</head>

<body>
 <div class="header">
  <h1>ABC Company</h1>
  <h2>Inventory Report</h2>
  <p>Filter inventory report by branch, salesperson, and date range.</p>
  <a href="manage_inventory.php" class="btn btn-light">Back to manage_inventory Dashboard</a>
 </div>

 <div class="container">
  <!-- Form to Select Report Type and Filters -->
  <form method="POST" action="generate_report.php" class="mb-4">
   <div class="row">
    <div class="col-md-4">
     <label for="branch" class="form-label">Branch</label>
     <select name="branch" class="form-select">
      <option value="">Select Branch</option>
      <!-- Populate with branches from the database -->
      <option value="Branch A">Addis Ababa</option>
      <option value="Branch B">Hawassa</option>
      <option value="Branch C">Mojjo</option>
     </select>
    </div>
    <div class="col-md-4">
     <label for="salesperson" class="form-label">Salesperson</label>
     <select name="salesperson" class="form-select">
      <option value="">Select Salesperson</option>
      <!-- Populate with salespersons from the database -->
      <option value="Asmamaw">Asmamaw</option>
      <option value="Tesfaye">Tesfaye</option>
      <option value="Kidst">Kidst</option>
     </select>
    </div>
    <div class="col-md-4">
     <label for="date_option" class="form-label">Date Option</label>
     <select name="date_option" class="form-select">
      <option value="">Select Date Option</option>
      <option value="range">Range</option>
      <option value="specific">Specific Date</option>
     </select>
    </div>
   </div>
   <div class="row mt-2">
    <div class="col-md-4">
     <label for="start_date" class="form-label">Start Date</label>
     <input type="date" name="start_date" class="form-control">
    </div>
    <div class="col-md-4">
     <label for="end_date" class="form-label">End Date</label>
     <input type="date" name="end_date" class="form-control">
    </div>
    <div class="col-md-4">
     <label for="specific_date" class="form-label">Specific Date</label>
     <input type="date" name="specific_date" class="form-control">
    </div>
   </div>
   <div class="row mt-2">
    <div class="col-md-12">
     <button type="submit" name="report_type" value="inventory" class="btn btn-primary w-100 mt-3">Generate
      Report</button>
    </div>
   </div>
  </form>

  <!-- Display the Report -->
  <?php if ($report_type === 'inventory' && $result->num_rows > 0): ?>
  <div class="table-container">
   <h3>Report for Selected Filters</h3>
   <table class="table table-striped table-bordered">
    <thead class="table-dark">
     <tr>
      <th>Item ID</th>
      <th>Item Name</th>
      <th>Branch</th>

      <th>Salesperson</th>
      <th>Beginning Quantity</th>
      <th>Purchased Quantity</th>
      <th>Sold Quantity</th>
      <th>Ending Quantity</th>
      <th>Beginning Cost</th>
      <th>Purchase Cost</th>
      <th>Cost of Sold</th>
      <th>Ending Cost</th>
     </tr>
    </thead>
    <tbody>
     <?php while ($row = $result->fetch_assoc()): ?>
     <tr>
      <td><?php echo htmlspecialchars($row['item_id']); ?></td>
      <td><?php echo htmlspecialchars($row['item_name']); ?></td>
      <td><?php echo htmlspecialchars($row['branch']); ?></td>

      <td><?php echo htmlspecialchars($row['salesperson']); ?></td>
      <td><?php echo htmlspecialchars($row['begin_qty']); ?></td>
      <td><?php echo htmlspecialchars($row['purchase_qty']); ?></td>
      <td><?php echo htmlspecialchars($row['sold_qty']); ?></td>
      <td><?php echo htmlspecialchars($row['ending_qty']); ?></td>
      <td><?php echo htmlspecialchars($row['begin_cost']); ?></td>
      <td><?php echo htmlspecialchars($row['purchase_cost']); ?></td>
      <td><?php echo htmlspecialchars($row['cost_of_sold']); ?></td>
      <td><?php echo htmlspecialchars($row['ending_cost']); ?></td>
     </tr>
     <?php endwhile; ?>
    </tbody>
   </table>
  </div>
  <?php elseif ($report_type === 'inventory'): ?>
  <p class="text-center">No records found for the selected filters.</p>
  <?php endif; ?>
 </div>
</body>

</html>