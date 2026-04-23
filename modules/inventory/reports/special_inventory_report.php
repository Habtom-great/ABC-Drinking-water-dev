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

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//Assume you have a connection to the database already set up like:
// $conn = new mysqli($servername, $username, $password, $dbname);

// Check for form submission
$branch = $_POST['branch'] ?? '';
$salesperson = $_POST['salesperson'] ?? '';
$product_type = $_POST['product_type'] ?? '';
$date_option = $_POST['date_option'] ?? '';
$specific_date = $_POST['specific_date'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$sort_by = $_POST['sort_by'] ?? 'date'; // Default sort by date

// Build the SQL query for the inventory report with filters
$sql = "SELECT date, item_id, item_description, branch, salesperson, product_type, begin_qty AS beginning_quantity, 
purchase_qty AS purchased_quantity, sold_qty AS sold_quantity, 
(begin_qty + purchase_qty - sold_qty) AS ending_quantity, 
(begin_qty * unit_cost) AS beginning_cost, 
(purchase_qty * unit_cost) AS purchase_cost, 
(sold_qty * unit_cost) AS cost_of_quantity_sold, 
((begin_qty + purchase_qty - sold_qty) * unit_cost) AS ending_cost 
FROM inventory WHERE 1";

// Add filters based on form inputs
$params = [];
$types = "";
if (!empty($branch)) {
    $sql .= " AND branch = ?";
    $params[] = $branch;
    $types .= "s";
}

if (!empty($salesperson)) {
    $sql .= " AND salesperson = ?";
    $params[] = $salesperson;
    $types .= "s";
}

if (!empty($product_type)) {
    $sql .= " AND product_type = ?";
    $params[] = $product_type;
    $types .= "s";
}

if ($date_option === "specific" && !empty($specific_date)) {
    $sql .= " AND date = ?";
    $params[] = $specific_date;
    $types .= "s";
} elseif ($date_option === "range" && !empty($start_date) && !empty($end_date)) {
    $sql .= " AND date BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
    $types .= "ss";
} elseif ($date_option === "month" && !empty($start_date)) {
    $sql .= " AND MONTH(date) = MONTH(?) AND YEAR(date) = YEAR(?)";
    $params[] = $start_date;
    $params[] = $start_date;
    $types .= "ss";
}

// Add sorting to the query
if ($sort_by == 'branch') {
    $sql .= " ORDER BY branch ASC";
} elseif ($sort_by == 'salesperson') {
    $sql .= " ORDER BY salesperson ASC";
} elseif ($sort_by == 'date') {
    $sql .= " ORDER BY date ASC";
} elseif ($sort_by == 'month') {
    $sql .= " ORDER BY MONTH(date) ASC, YEAR(date) ASC";
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inventory Report</title>
 <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

 <div class="container mt-4">
  <h2>Inventory Report</h2>

  <!-- Filter and Sorting Form -->
  <form method="POST" class="mb-3">
   <div class="row">
    <!-- Branch Filter -->
    <div class="col-md-3">
     <label for="branch" class="form-label">Branch</label>
     <input type="text" name="branch" class="form-control" value="<?= htmlspecialchars($branch) ?>">
    </div>

    <!-- Salesperson Filter -->
    <div class="col-md-3">
     <label for="salesperson" class="form-label">Salesperson</label>
     <input type="text" name="salesperson" class="form-control" value="<?= htmlspecialchars($salesperson) ?>">
    </div>

    <!-- Product Type Filter -->
    <div class="col-md-3">
     <label for="product_type" class="form-label">Product Type</label>
     <input type="text" name="product_type" class="form-control" value="<?= htmlspecialchars($product_type) ?>">
    </div>

    <!-- Date Option Filter -->
    <div class="col-md-3">
     <label for="date_option" class="form-label">Date Option</label>
     <select name="date_option" class="form-select">
      <option value="">Select Date</option>
      <option value="specific" <?= $date_option === 'specific' ? 'selected' : '' ?>>Specific Date</option>
      <option value="range" <?= $date_option === 'range' ? 'selected' : '' ?>>Date Range</option>
      <option value="month" <?= $date_option === 'month' ? 'selected' : '' ?>>Month</option>
     </select>
    </div>
   </div>

   <!-- Date Filters -->
   <div class="row mt-3">
    <div class="col-md-3">
     <label for="specific_date" class="form-label">Specific Date</label>
     <input type="date" name="specific_date" class="form-control" value="<?= htmlspecialchars($specific_date) ?>"
      <?= $date_option !== 'specific' ? 'disabled' : '' ?>>
    </div>
    <div class="col-md-3">
     <label for="start_date" class="form-label">Start Date</label>
     <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>"
      <?= $date_option !== 'range' ? 'disabled' : '' ?>>
    </div>
    <div class="col-md-3">
     <label for="end_date" class="form-label">End Date</label>
     <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>"
      <?= $date_option !== 'range' ? 'disabled' : '' ?>>
    </div>
   </div>

   <!-- Sorting Options -->
   <div class="row mt-3">
    <div class="col-md-3">
     <label for="sort_by" class="form-label">Sort By</label>
     <select name="sort_by" class="form-select">
      <option value="branch" <?= $sort_by == 'branch' ? 'selected' : '' ?>>Branch</option>
      <option value="salesperson" <?= $sort_by == 'salesperson' ? 'selected' : '' ?>>Salesperson</option>
      <option value="date" <?= $sort_by == 'date' ? 'selected' : '' ?>>Date</option>
      <option value="month" <?= $sort_by == 'month' ? 'selected' : '' ?>>Month</option>
     </select>
    </div>

    <!-- Submit Button -->
    <div class="col-md-3">
     <button type="submit" class="btn btn-primary mt-4">Apply Filters</button>
    </div>
   </div>
  </form>

  <!-- Report Table -->
  <table class="table table-bordered mt-3">
   <thead>
    <tr>
     <th>Date</th>
     <th>Item ID</th>
     <th>Description</th>
     <th>Branch</th>
     <th>Salesperson</th>
     <th>Beginning Quantity</th>
     <th>Purchased Quantity</th>
     <th>Sold Quantity</th>
     <th>Ending Quantity</th>
     <th>Beginning Cost</th>
     <th>Purchase Cost</th>
     <th>Cost of Quantity Sold</th>
     <th>Ending Cost</th>
    </tr>
   </thead>
   <tbody>
    <?php
            // Display data in table rows
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['date']}</td>";
                echo "<td>{$row['item_id']}</td>";
                echo "<td>{$row['item_description']}</td>";
                echo "<td>{$row['branch']}</td>";
                echo "<td>{$row['salesperson']}</td>";
                echo "<td>{$row['beginning_quantity']}</td>";
                echo "<td>{$row['purchased_quantity']}</td>";
                echo "<td>{$row['sold_quantity']}</td>";
                echo "<td>{$row['ending_quantity']}</td>";
                echo "<td>{$row['beginning_cost']}</td>";
                echo "<td>{$row['purchase_cost']}</td>";
                echo "<td>{$row['cost_of_quantity_sold']}</td>";
                echo "<td>{$row['ending_cost']}</td>";
                echo "</tr>";
            }
            ?>
   </tbody>
  </table>
 </div>

 <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

kkkkkkkk
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

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume you have other parts of your code here (form handling, etc.)

// Check for form submission
$branch = $_POST['branch'] ?? '';
$salesperson = $_POST['salesperson'] ?? '';
$product_type = $_POST['product_type'] ?? '';
$date_option = $_POST['date_option'] ?? '';
$specific_date = $_POST['specific_date'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$sort_by = $_POST['sort_by'] ?? 'date'; // Default sort by date

// Build the SQL query for the inventory report with filters
$sql = "SELECT date, item_id, item_description, branch, salesperson, product_type, begin_qty AS beginning_quantity, 
purchase_qty AS purchased_quantity, sold_qty AS sold_quantity, 
(begin_qty + purchase_qty - sold_qty) AS ending_quantity, 
(begin_qty * unit_cost) AS beginning_cost, 
(purchase_qty * unit_cost) AS purchase_cost, 
(sold_qty * unit_cost) AS cost_of_quantity_sold, 
((begin_qty + purchase_qty - sold_qty) * unit_cost) AS ending_cost 
FROM inventory WHERE 1";

// Add filters based on form inputs
$params = [];
$types = "";
if (!empty($branch)) {
    $sql .= " AND branch = ?";
    $params[] = $branch;
    $types .= "s";
}

if (!empty($salesperson)) {
    $sql .= " AND salesperson = ?";
    $params[] = $salesperson;
    $types .= "s";
}

if (!empty($product_type)) {
    $sql .= " AND product_type = ?";
    $params[] = $product_type;
    $types .= "s";
}

if ($date_option === "specific" && !empty($specific_date)) {
    $sql .= " AND date = ?";
    $params[] = $specific_date;
    $types .= "s";
} elseif ($date_option === "range" && !empty($start_date) && !empty($end_date)) {
    $sql .= " AND date BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
    $types .= "ss";
} elseif ($date_option === "month" && !empty($start_date)) {
    $sql .= " AND MONTH(date) = MONTH(?) AND YEAR(date) = YEAR(?)";
    $params[] = $start_date;
    $params[] = $start_date;
    $types .= "ss";
}

// Add sorting to the query
if ($sort_by == 'branch') {
    $sql .= " ORDER BY branch ASC";
} elseif ($sort_by == 'salesperson') {
    $sql .= " ORDER BY salesperson ASC";
} elseif ($sort_by == 'date') {
    $sql .= " ORDER BY date ASC";
} elseif ($sort_by == 'month') {
    $sql .= " ORDER BY MONTH(date) ASC, YEAR(date) ASC";
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inventory Report</title>
 <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

 <div class="container mt-4">
  <h2>Inventory Report</h2>

  <!-- Filter and Sorting Form -->
  <form method="POST" class="mb-3">
   <div class="row">
    <!-- Branch Filter -->
    <div class="col-md-3">
     <label for="branch" class="form-label">Branch</label>
     <input type="text" name="branch" class="form-control" value="<?= htmlspecialchars($branch) ?>">
    </div>

    <!-- Salesperson Filter -->
    <div class="col-md-3">
     <label for="salesperson" class="form-label">Salesperson</label>
     <input type="text" name="salesperson" class="form-control" value="<?= htmlspecialchars($salesperson) ?>">
    </div>

    <!-- Product Type Filter -->
    <div class="col-md-3">
     <label for="product_type" class="form-label">Product Type</label>
     <input type="text" name="product_type" class="form-control" value="<?= htmlspecialchars($product_type) ?>">
    </div>

    <!-- Date Option Filter -->
    <div class="col-md-3">
     <label for="date_option" class="form-label">Date Option</label>
     <select name="date_option" class="form-select">
      <option value="">Select Date</option>
      <option value="specific" <?= $date_option === 'specific' ? 'selected' : '' ?>>Specific Date</option>
      <option value="range" <?= $date_option === 'range' ? 'selected' : '' ?>>Date Range</option>
      <option value="month" <?= $date_option === 'month' ? 'selected' : '' ?>>Month</option>
     </select>
    </div>
   </div>

   <!-- Date Filters -->
   <div class="row mt-3">
    <div class="col-md-3">
     <label for="specific_date" class="form-label">Specific Date</label>
     <input type="date" name="specific_date" class="form-control" value="<?= htmlspecialchars($specific_date) ?>"
      <?= $date_option !== 'specific' ? 'disabled' : '' ?>>
    </div>
    <div class="col-md-3">
     <label for="start_date" class="form-label">Start Date</label>
     <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>"
      <?= $date_option !== 'range' ? 'disabled' : '' ?>>
    </div>
    <div class="col-md-3">
     <label for="end_date" class="form-label">End Date</label>
     <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>"
      <?= $date_option !== 'range' ? 'disabled' : '' ?>>
    </div>
   </div>

   <!-- Sorting Options -->
   <div class="row mt-3">
    <div class="col-md-3">
     <label for="sort_by" class="form-label">Sort By</label>
     <select name="sort_by" class="form-select">
      <option value="branch" <?= $sort_by == 'branch' ? 'selected' : '' ?>>Branch</option>
      <option value="salesperson" <?= $sort_by == 'salesperson' ? 'selected' : '' ?>>Salesperson</option>
      <option value="date" <?= $sort_by == 'date' ? 'selected' : '' ?>>Date</option>
      <option value="month" <?= $sort_by == 'month' ? 'selected' : '' ?>>Month</option>
     </select>
    </div>

    <!-- Submit Button -->
    <div class="col-md-3">
     <button type="submit" class="btn btn-primary mt-4">Apply Filters</button>
    </div>
   </div>
  </form>

  <!-- Report Table -->
  <table class="table table-bordered mt-3">
   <thead>
    <tr>
     <th>Date</th>
     <th>Item ID</th>
     <th>Description</th>
     <th>Branch</th>
     <th>Salesperson</th>
     <th>Beginning Quantity</th>
     <th>Purchased Quantity</th>
     <th>Sold Quantity</th>
     <th>Ending Quantity</th>
     <th>Beginning Cost</th>
     <th>Purchase Cost</th>
     <th>Cost of Quantity Sold</th>
     <th>Ending Cost</th>
    </tr>
   </thead>
   <tbody>
    <?php
            // Display data in table rows
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['date']}</td>";
                echo "<td>{$row['item_id']}</td>";
                echo "<td>{$row['item_description']}</td>";
                echo "<td>{$row['branch']}</td>";
                echo "<td>{$row['salesperson']}</td>";
                echo "<td>{$row['beginning_quantity']}</td>";
                echo "<td>{$row['purchased_quantity']}</td>";
                echo "<td>{$row['sold_quantity']}</td>";
                echo "<td>{$row['ending_quantity']}</td>";
                echo "<td>{$row['beginning_cost']}</td>";
                echo "<td>{$row['purchase_cost']}</td>";
                echo "<td>{$row['cost_of_quantity_sold']}</td>";
                echo "<td>{$row['ending_cost']}</td>";
                echo "</tr>";
            }
            ?>
   </tbody>
  </table>
 </div>

 <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>