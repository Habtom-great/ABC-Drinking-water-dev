<?php
// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

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

// Get filter values from POST
$branch = $_POST['branch'] ?? '';
$salesperson = $_POST['salesperson'] ?? '';
$date_option = $_POST['date_option'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$specific_date = $_POST['specific_date'] ?? '';

// Base query
$query = "SELECT item_id, date, item_id, item_description, branch, salesperson, beginning_inventory_qty, purchased_inventory_qty, sold_inventory_qty,
         (beginning_inventory_qty + purchased_inventory_qty - sold_inventory_qty) AS ending_inventory_qty,
         (beginning_inventory_qty * unit_cost) AS beginning_inventory_cost,
         (purchased_inventory_qty * unit_cost) AS purchased_inventory_cost,
         (sold_inventory_qty * unit_cost) AS cost_of_sold,
         ((beginning_inventory_qty + purchased_inventory_qty - sold_inventory_qty) * unit_cost) AS ending_inventory_cost
         FROM inventory WHERE 1=1";

$params = [];
$types = "";

// Apply filters
if (!empty($branch)) {
    $query .= " AND branch = ?";
    $params[] = $branch;
    $types .= "s";
}

if (!empty($salesperson)) {
    $query .= " AND salesperson = ?";
    $params[] = $salesperson;
    $types .= "s";
}

if ($date_option === "specific" && !empty($specific_date)) {
    $query .= " AND date = ?";
    $params[] = $specific_date;
    $types .= "s";
} elseif ($date_option === "range" && !empty($start_date) && !empty($end_date)) {
    $query .= " AND date BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
    $types .= "ss";
}

// Prepare statement




// Generate Report Title dynamically
$title = "Inventory Report";
$filters = [];

if (!empty($branch)) {
    $filters[] = "Branch: " . htmlspecialchars($branch);
}
if (!empty($salesperson)) {
    $filters[] = "Salesperson: " . htmlspecialchars($salesperson);
}
if ($date_option === "specific" && !empty($specific_date)) {
    $filters[] = "Date: " . htmlspecialchars($specific_date);
} elseif ($date_option === "range" && !empty($start_date) && !empty($end_date)) {
    $filters[] = "From: " . htmlspecialchars($start_date) . " to " . htmlspecialchars($end_date);
}

if (!empty($filters)) {
    $title .= " | " . implode(" | ", $filters);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title><?php echo $title; ?></title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
 <div class="container mt-4">
  <h1 class="text-center">ABC Company</h1>
  <h2 class="text-center mb-4">Inventory Report</h2>

  <form method="POST" class="mb-4 border p-3 bg-light rounded">
   <div class="row g-3">
    <!-- Branch Filter -->

    <!-- Salesperson Filter -->
    <div class="container">
     <form method="POST" action="" class="mb-4">
      <div class="row">
       <div class="col-md-4">
        <label class="form-label">Branch</label>
        <select name="branch" class="form-select">
         <option value="">Select Branch</option>
         <option value="Addis Ababa">Addis Ababa</option>
         <option value="Hawassa">Hawassa</option>
         <option value="Mojjo">Mojjo</option>
        </select>
       </div>

       <!-- Add more salespersons as needed -->
       <div class="col-md-4">
        <label class="form-label">Salesperson</label>
        <select name="salesperson" class="form-select">
         <option value="">Select Salesperson</option>
         <option value="Asmamaw">Asmamaw</option>
         <option value="Tesfaye">Tesfaye</option>
         <option value="Kidst">Kidst</option>
        </select>
       </div>

       <!-- Date Filter -->
       <div class="col-md-3">
        <label for="date_option">Date Filter:</label>
        <select name="date_option" id="date_option" class="form-select">
         <option value="">Select</option>
         <option value="all" <?php if ($date_option == "all") echo "selected"; ?>>All Dates</option>
         <option value="specific" <?php if ($date_option == "specific") echo "selected"; ?>>Specific Date</option>
         <option value="range" <?php if ($date_option == "range") echo "selected"; ?>>Date Range</option>
        </select>
       </div>
      </div>

      <div class="row mt-2">
       <!-- Specific Date Input -->
       <div class="col-md-3" id="specific_date_group"
        style="<?php echo ($date_option == 'specific') ? '' : 'display: none;'; ?>">
        <label for="specific_date">Specific Date:</label>
        <input type="date" name="specific_date" id="specific_date" class="form-control"
         value="<?php echo htmlspecialchars($specific_date); ?>">
       </div>

       <!-- Date Range Inputs -->
       <div class="col-md-3" id="start_date_group"
        style="<?php echo ($date_option == 'range') ? '' : 'display: none;'; ?>">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" class="form-control"
         value="<?php echo htmlspecialchars($start_date); ?>">
       </div>

       <div class="col-md-3" id="end_date_group"
        style="<?php echo ($date_option == 'range') ? '' : 'display: none;'; ?>">
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" class="form-control"
         value="<?php echo htmlspecialchars($end_date); ?>">
       </div>

       <!-- Filter Button -->
       <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
       </div>
      </div>
     </form>

     <h4 class="text-center mb-3"><?php echo $title; ?></h4>

     <script>
     // Show or hide specific fields based on the selected date option
     document.getElementById('date_option').addEventListener('change', function() {
      var dateOption = this.value;
      document.getElementById('specific_date_group').style.display = (dateOption === 'specific') ? '' : 'none';
      document.getElementById('start_date_group').style.display = (dateOption === 'range') ? '' : 'none';
      document.getElementById('end_date_group').style.display = (dateOption === 'range') ? '' : 'none';
     });
     </script>


     <!-- Report Table -->
     <div class="table-responsive">
      <table class="table table-bordered table-striped">
       <thead class="table-dark text-center">
        <tr>
         <th>Date</th>
         <th>Item ID</th>
         <th>Item Description</th>
         <th>Branch</th>
         <th>Salesperson</th>
         <th>Beginning Qty</th>
         <th>Purchased Qty</th>
         <th>Sold Qty</th>
         <th>Ending Qty</th>
         <th>Beginning Cost</th>
         <th>Purchase Cost</th>
         <th>Cost of Sold</th>
         <th>Ending Cost</th>
         <th>Actions</th>
        </tr>
       </thead>
       <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
         <td><?php echo htmlspecialchars($row['date']); ?></td>
         <td><?php echo htmlspecialchars($row['item_id']); ?></td>
         <td><?php echo htmlspecialchars($row['item_description']); ?></td>
         <td><?php echo htmlspecialchars($row['branch']); ?></td>
         <td><?php echo htmlspecialchars($row['salesperson']); ?></td>
         <td class="text-end"><?php echo htmlspecialchars($row['beginning_inventory_qty']); ?></td>
         <td class="text-end"><?php echo htmlspecialchars($row['purchased_inventory_qty']); ?></td>
         <td class="text-end"><?php echo htmlspecialchars($row['sold_inventory_qty']); ?></td>
         <td class="text-end"><?php echo htmlspecialchars($row['ending_inventory_qty']); ?></td>
         <td class="text-end"><?php echo number_format($row['beginning_inventory_cost'], 2); ?></td>
         <td class="text-end"><?php echo number_format($row['purchased_inventory_cost'], 2); ?></td>
         <td class="text-end"><?php echo number_format($row['cost_of_sold'], 2); ?></td>
         <td class="text-end"><?php echo number_format($row['ending_inventory_cost'], 2); ?></td>
         <td class="text-center">
          <a href="manage_inventory.php?action=edit&id=<?php echo $row['item_id']; ?>"
           class="btn btn-sm btn-warning">Edit</a>
          <a href="manage_inventory.php?action=delete&id=<?php echo $row['item_id']; ?>" class="btn btn-sm btn-danger"
           onclick="return confirm('Delete this record?')">Delete</a>
         </td>
        </tr>
        <?php } ?>
       </tbody>
      </table>
     </div>

     <!-- Export Options -->
     <div class="text-center mt-4">
      <a href="#" class="btn btn-success btn-sm">Export to Excel</a>
      <a href="#" class="btn btn-info btn-sm">Export to Word</a>
      <a href="#" class="btn btn-danger btn-sm">Export to PDF</a>
      <button onclick="window.print()" class="btn btn-primary btn-sm">Print</button>
     </div>

     <a href="manage_inventory.php" class="btn btn-secondary mt-3">Back to Inventory Dashboard</a>
    </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>