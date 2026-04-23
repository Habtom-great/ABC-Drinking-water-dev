<?php
// Database Connection
$host = 'localhost';
$db = 'ABC_company';
$user = 'root';
$pass = '';
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch Data
try {
    // Daily Summary
    $dailySummary = $conn->query("
        SELECT transaction_type, SUM(quantity) as total
        FROM transactions
        WHERE transaction_date = CURDATE()
        GROUP BY transaction_type
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Branch-Wise Transactions
    $branchSummary = $conn->query("
        SELECT branch_name, transaction_type, SUM(quantity) as total
        FROM transactions
        WHERE transaction_date = CURDATE()
        GROUP BY branch_name, transaction_type
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Top Items
    $topItems = $conn->query("
        SELECT item_name, SUM(quantity) as total
        FROM transactions
        WHERE transaction_date = CURDATE()
        GROUP BY item_name
        ORDER BY total DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inventory Reports</title>
 <link rel="stylesheet" href="styles.css">
 <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
</head>

<body>
 <header>
  <h1>Daily Inventory Reports</h1>
 </header>
 <main>
  <section class="report-section">
   <h2>Daily Summary</h2>
   <div class="summary-cards">
    <?php foreach ($dailySummary as $row): ?>
    <div class="card">
     <h3><?php echo htmlspecialchars($row['transaction_type']); ?></h3>
     <p><?php echo htmlspecialchars($row['total']); ?> items</p>
    </div>
    <?php endforeach; ?>
   </div>
  </section>
  <section class="report-section">
   <h2>Branch-Wise Transactions</h2>
   <table>
    <thead>
     <tr>
      <th>Branch</th>
      <th>Transaction Type</th>
      <th>Total Quantity</th>
     </tr>
    </thead>
    <tbody>
     <?php foreach ($branchSummary as $row): ?>
     <tr>
      <td><?php echo htmlspecialchars($row['branch_name']); ?></td>
      <td><?php echo htmlspecialchars($row['transaction_type']); ?></td>
      <td><?php echo htmlspecialchars($row['total']); ?></td>
     </tr>
     <?php endforeach; ?>
    </tbody>
   </table>
  </section>
  <section class="report-section">
   <h2>Top Items</h2>
   <canvas id="topItemsChart"></canvas>
  </section>
 </main>
 <footer>
  <p>&copy; 2025 Inventory Management System</p>
 </footer>
 <script>
 document.addEventListener("DOMContentLoaded", () => {
  const topItems = <?php echo json_encode($topItems); ?>;

  const labels = topItems.map(item => item.item_name);
  const data = topItems.map(item => item.total);

  const ctx = document.getElementById('topItemsChart').getContext('2d');
  new Chart(ctx, {
   type: 'bar',
   data: {
    labels: labels,
    datasets: [{
     label: 'Top Items',
     data: data,
     backgroundColor: 'rgba(75, 192, 192, 0.2)',
     borderColor: 'rgba(75, 192, 192, 1)',
     borderWidth: 1
    }]
   },
   options: {
    responsive: true,
    scales: {
     y: {
      beginAtZero: true
     }
    }
   }
  });
 });
 </script>
</body>

</html>
<style>
body {
 font-family: Arial, sans-serif;
 background-color: #f9f9f9;
 margin: 0;
 padding: 0;
}

header {
 background-color: #4CAF50;
 color: white;
 text-align: center;
 padding: 10px;
}

h1,
h2 {
 margin: 10px 0;
 color: #333;
}

main {
 padding: 20px;
}

.report-section {
 margin-bottom: 30px;
}

.summary-cards {
 display: flex;
 gap: 15px;
 justify-content: space-around;
}

.card {
 background: white;
 padding: 15px;
 border-radius: 5px;
 box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
 text-align: center;
 width: 30%;
}

table {
 width: 100%;
 border-collapse: collapse;
 margin-top: 10px;
}

table th,
table td {
 border: 1px solid #ddd;
 padding: 10px;
 text-align: center;
}

table th {
 background-color: #4CAF50;
 color: white;
}
</style>
kkkkkkkk
<?php
// Assuming a connection to the database is established
include('db_connection.php');

// Fetch inventory data
$query = "SELECT * FROM inventory";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error fetching inventory data: ' . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inventory Report</title>
 <link rel="stylesheet" href="styles.css">
</head>

<body>

 <h1>Inventory Report</h1>

 <table border="1">
  <thead>
   <tr>
    <th>Item ID</th>
    <th>Item Description</th>
    <th>Category</th>
    <th>Quantity</th>
    <th>Price</th>
   </tr>
  </thead>
  <tbody>
   <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['item_id'] . "</td>";
                echo "<td>" . $row['item_description'] . "</td>";
                echo "<td>" . $row['category'] . "</td>";
                echo "<td>" . $row['quantity'] . "</td>";
                echo "<td>" . $row['unit_price'] . "</td>";
                echo "</tr>";
            }
            ?>
  </tbody>
 </table>

 <a href="export_report.php">Export Report</a>

</body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>

kkkkkk
<?php
// Database Connection
$host = 'localhost';
$db = 'ABC_company';
$user = 'root';
$pass = '';
try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch Filter Options
$branches = $conn->query("SELECT DISTINCT branch_name FROM transactions")->fetchAll(PDO::FETCH_ASSOC);
$productTypes = $conn->query("SELECT DISTINCT transaction_type FROM transactions")->fetchAll(PDO::FETCH_ASSOC);
$salesPersonName = $conn->query("SELECT DISTINCT salesperson_name FROM transactions")->fetchAll(PDO::FETCH_ASSOC);


// Initialize Filters
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';
$productType = $_GET['product_type'] ?? '';
$branch = $_GET['branch'] ?? '';
$salesperson_name = $_GET['salesperson_name'] ?? '';

// Build Query with Filters
$query = "SELECT * FROM transactions WHERE 1=1";
$params = [];

if (!empty($dateFrom) && !empty($dateTo)) {
    $query .= " AND transaction_date BETWEEN :dateFrom AND :dateTo";
    $params[':dateFrom'] = $dateFrom;
    $params[':dateTo'] = $dateTo;
}

if (!empty($productType)) {
    $query .= " AND transaction_type = :productType";
    $params[':productType'] = $productType;
}

if (!empty($branch)) {
    $query .= " AND branch_name = :branch";
    $params[':branch'] = $branch;
}

if (!empty($salesRep)) {
    $query .= " AND sales_rep = :salesRep";
    $params[':salesRep'] = $salesRep;
}

$transactions = $conn->prepare($query);
$transactions->execute($params);
$results = $transactions->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Filtered Inventory Report</title>
 <link rel="stylesheet" href="styles.css">
</head>

<body>
 <header>
  <h1>Filtered Inventory Report</h1>
  <p class="report-date">Date: <?php echo date('F j, Y'); ?></p>
 </header>
 <main>
  <section class="filter-section">
   <form method="GET" action="">
    <div class="filter-group">
     <label for="date_from">Date From:</label>
     <input type="date" id="date_from" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>">

     <label for="date_to">Date To:</label>
     <input type="date" id="date_to" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>">
    </div>

    <div class="filter-group">
     <label for="product_type">Product Type:</label>
     <select id="product_type" name="product_type">
      <option value="">All</option>
      <?php foreach ($productTypes as $type): ?>
      <option value="<?php echo htmlspecialchars($type['transaction_type']); ?>"
       <?php echo ($type['transaction_type'] === $productType) ? 'selected' : ''; ?>>
       <?php echo htmlspecialchars($type['transaction_type']); ?>
      </option>
      <?php endforeach; ?>
     </select>

     <label for="branch">Branch:</label>
     <select id="branch" name="branch">
      <option value="">All</option>
      <?php foreach ($branches as $branchOption): ?>
      <option value="<?php echo htmlspecialchars($branchOption['branch_name']); ?>"
       <?php echo ($branchOption['branch_name'] === $branch) ? 'selected' : ''; ?>>
       <?php echo htmlspecialchars($branchOption['branch_name']); ?>
      </option>
      <?php endforeach; ?>
     </select>
    </div>

    <div class="filter-group">
     <label for="sales_rep">Sales Representative:</label>
     <select id="sales_rep" name="sales_rep">
      <option value="">All</option>
      <?php foreach ($salesReps as $rep): ?>
      <option value="<?php echo htmlspecialchars($rep['sales_rep']); ?>"
       <?php echo ($rep['sales_rep'] === $salesRep) ? 'selected' : ''; ?>>
       <?php echo htmlspecialchars($rep['sales_rep']); ?>
      </option>
      <?php endforeach; ?>
     </select>
    </div>

    <button type="submit" class="filter-btn">Filter</button>
   </form>
  </section>

  <section class="report-section">
   <h2>Transactions Report</h2>
   <?php if (!empty($results)): ?>
   <table>
    <thead>
     <tr>
      <th>Date</th>
      <th>Branch</th>
      <th>Product Type</th>
      <th>Salesperson</th>
      <th>Quantity</th>
     </tr>
    </thead>
    <tbody>
     <?php foreach ($results as $row): ?>
     <tr>
      <td><?php echo htmlspecialchars($row['transaction_date']); ?></td>
      <td><?php echo htmlspecialchars($row['branch_name']); ?></td>
      <td><?php echo htmlspecialchars($row['transaction_type']); ?></td>
      <td><?php echo htmlspecialchars($row['salesperson_name']); ?></td>
      <td><?php echo htmlspecialchars($row['quantity']); ?></td>
     </tr>
     <?php endforeach; ?>
    </tbody>
   </table>
   <?php else: ?>
   <p class="no-data">No transactions found for the selected filters.</p>
   <?php endif; ?>
  </section>
 </main>
 <footer>
  <p>&copy; 2025 Inventory Management System</p>
 </footer>
 <style>
 body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f9;
  margin: 0;
  padding: 0;
 }

 header {
  background-color: #4CAF50;
  color: white;
  text-align: center;
  padding: 15px 0;
 }

 .filter-section {
  padding: 20px;
  background: #fff;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  margin: 20px auto;
  max-width: 800px;
  border-radius: 8px;
 }

 .filter-group {
  display: flex;
  gap: 20px;
  margin-bottom: 15px;
  flex-wrap: wrap;
 }

 label {
  font-weight: bold;
 }

 input,
 select {
  padding: 8px;
  font-size: 1em;
  width: 100%;
  max-width: 200px;
 }

 .filter-btn {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
 }

 .filter-btn:hover {
  background-color: #45a049;
 }

 .report-section {
  margin: 20px;
 }

 table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
 }

 table th,
 table td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: center;
 }

 table th {
  background-color: #4CAF50;
  color: white;
 }

 table tbody tr:nth-child(odd) {
  background-color: #f9f9f9;
 }

 table tbody tr:hover {
  background-color: #f1f1f1;
 }

 .no-data {
  text-align: center;
  color: #888;
  font-style: italic;
  margin: 20px 0;
 }

 footer {
  text-align: center;
  padding: 10px 0;
  background: #4CAF50;
  color: white;
  position: fixed;
  bottom: 0;
  width: 100%;
 }
 </style>
</body>

</html>

kkkkkkkkkkkkkkk
<?php 
session_start();
// Simulated login
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = 'Admin'; // Simulated user login
}

// Simulated data for graph (replace with database data in a real application)
$data = [
    "labels" => ["January", "February", "March", "April", "May", "June"],
    "values" => [500, 700, 1000, 1200, 1500, 1800],
];
// Fetch report types from the database
$report_types = [
    'inventory_summary' => 'Inventory Summary',
    'Daily Inventory Reports' => 'Daily Inventory Reports',
    'low_stock' => 'Low Stock Items',
    'out_of_stock' => 'Out of Stock',
    'sales_report' => 'Sales Report'
];

$selected_report = isset($_POST['report_type']) ? $_POST['report_type'] : 'inventory_summary';

?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inventory Management Dashboard</title>
 <link rel="stylesheet" href="styles.css">
 <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script> <!-- Include Chart.js -->
 <script src="scripts.js" defer></script>
</head>

<body>
 <header>
  <h1>Welcome, <?php echo $_SESSION['user']; ?>!</h1>
 </header>
 <nav>
  <ul>
   <li><a href="add_inventory.php">Add Inventory</a></li>
   <li><a href="edit_inventory.php">Edit Inventory</a></li>
   <li><a href="delete_inventory.php">Delete Inventory</a></li>
   <li><a href="adjust_inventory.php">Adjust Inventory</a></li>
   <li><a href="inventory_reports.php">Reports</a></li>
   <li><a href="salesperson_performance.php">Performance</a></li>
  </ul>
 </nav>
 <main>
  <section class="summary">
   <h2>Dashboard Summary</h2>
   <div class="summary-cards">
    <div class="card">
     <h3>Total Inventory Value</h3>
     <p>$12,500</p>
    </div>
    <div class="card">
     <h3>Total Items</h3>
     <p>650</p>
    </div>
    <div class="card">
     <h3>Active Branches</h3>
     <p>7</p>
    </div>
   </div>
  </section>
  <section class="graph-report">
   <h2>Monthly Inventory Growth</h2>
   <canvas id="inventoryChart"></canvas>
  </section>
  <section class="updates">
   <h2>Recent Updates</h2>
   <ul>
    <li>📦 Added: 100 units of Product Z</li>
    <li>🔄 Adjusted: 5 units of Product X removed</li>
    <li>✨ Updated pricing for seasonal items</li>
   </ul>
  </section>
 </main>
 <footer>
  <p>&copy; 2025 Inventory Manager Pro | Powered by Technology</p>
 </footer>
 <script>
 const graphData = <?php echo json_encode($data); ?>; // Pass PHP data to JavaScript
 </script>
</body>

</html>
<script>
document.addEventListener("DOMContentLoaded", () => {
 const cards = document.querySelectorAll(".card");

 // Add hover animation to cards
 cards.forEach(card => {
  card.addEventListener("mouseenter", () => {
   card.style.transform = "scale(1.05)";
   card.style.transition = "transform 0.3s ease-in-out";
  });

  card.addEventListener("mouseleave", () => {
   card.style.transform = "scale(1)";
  });
 });

 console.log("Dashboard loaded and interactive.");

 // Render Chart.js Graph
 const ctx = document.getElementById("inventoryChart").getContext("2d");

 new Chart(ctx, {
  type: "line", // Change to 'bar', 'pie', etc., for other types
  data: {
   labels: graphData.labels, // Months
   datasets: [{
    label: "Inventory Value ($)",
    data: graphData.values, // Inventory values
    borderColor: "#2d89ef",
    backgroundColor: "rgba(45, 137, 239, 0.1)",
    borderWidth: 2,
    tension: 0.4, // Smooth curve
   }, ],
  },
  options: {
   responsive: true,
   plugins: {
    legend: {
     display: true,
     position: "top",
    },
   },
   scales: {
    x: {
     title: {
      display: true,
      text: "Months",
     },
    },
    y: {
     title: {
      display: true,
      text: "Inventory Value ($)",
     },
    },
   },
  },
 });
});
</script>
<style>
/* Graph Report Styling */
.graph-report {
 margin: 30px auto;
 padding: 20px;
 max-width: 800px;
 background: white;
 border-radius: 10px;
 box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
 text-align: center;
}

.graph-report h2 {
 margin-bottom: 20px;
 font-size: 1.5rem;
 color: #333;
}

canvas {
 max-width: 100%;
}
</style>


<style>
body {
 font-family: Arial, sans-serif;
 margin: 0;
 padding: 0;
 background-color: #f9f9f9;
}

header {
 background-color: #4CAF50;
 color: white;
 padding: 10px;
 text-align: center;
}

nav {
 background-color: #333;
 color: white;
}

nav ul {
 list-style: none;
 padding: 0;
 margin: 0;
 display: flex;
 justify-content: space-around;
}

nav ul li {
 padding: 10px;
}

nav ul li a {
 color: white;
 text-decoration: none;
}

main {
 padding: 20px;
}

.summary-cards {
 display: flex;
 gap: 10px;
 justify-content: space-around;
}

.card {
 background-color: #fff;
 padding: 10px;
 border-radius: 5px;
 box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
 text-align: center;
}

footer {
 background-color: #4CAF50;
 color: white;
 text-align: center;
 padding: 10px;
 position: fixed;
 bottom: 0;
 width: 100%;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", () => {
 console.log("Dashboard loaded successfully.");
});
</script>
kkkkkk
<?php

include 'db_connection.php'; // Ensure this file contains your database connection setup

// Default report type
$report_type = isset($_POST['report_type']) ? $_POST['report_type'] : 'all';

// Generate SQL query based on selected report
switch ($report_type) {
    case 'low_stock':
        $sql = "SELECT * FROM inventory WHERE quantity < 10"; // Example: Low stock items
        break;
    case 'out_of_stock':
        $sql = "SELECT * FROM inventory WHERE quantity = 0"; // Out-of-stock items
        break;
    case 'created_at':
        $sql = "SELECT * FROM inventory ORDER BY created_at DESC LIMIT 10"; // Recently added
        break;
    default:
        $sql = "SELECT * FROM inventory"; // Default: All inventory
        break;
}

$result = $conn->query($sql);

if (!$result) {
    die("Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inventory Reports</title>
 <link rel="stylesheet" href="styles.css">
</head>

<body>
 <header>
  <h1>Inventory Reports</h1>
  <h2>Welcome, <?php echo $_SESSION['user'] ?? 'Guest'; ?>!</h2>
 </header>

 <form method="POST" action="">
  <label for="report_type">Select Report Type:</label>
  <select name="report_type" id="report_type" onchange="this.form.submit()">
   <option value="all" <?php if ($report_type == 'all') echo 'selected'; ?>>All Inventory</option>
   <option value="low_stock" <?php if ($report_type == 'low_stock') echo 'selected'; ?>>Low Stock</option>
   <option value="out_of_stock" <?php if ($report_type == 'out_of_stock') echo 'selected'; ?>>Out of Stock</option>
   <option value="created_at" ?php if ($report_type=='created_at' ) echo 'selected' ; ?>>Recently Added
   </option>
  </select>
 </form>

 <table border="1">
  <thead>
   <tr>
    <th>ID</th>
    <th>Item Name</th>
    <th>Quantity</th>
    <th>Category</th>
    <th>Price</th>
    <th>Date Added</th>
   </tr>
  </thead>
  <tbody>
   <?php if ($result->num_rows > 0): ?>
   <?php while ($row = $result->fetch_assoc()): ?>
   <tr>
    <td><?php echo $row['item_id']; ?></td>
    <td><?php echo $row['item_description']; ?></td>
    <td><?php echo $row['quantity']; ?></td>
    <td><?php echo $row['category']; ?></td>
    <td>$<?php echo number_format($row['unit_price'], 2); ?></td>
    <td><?php echo $row['date']; ?></td>
   </tr>
   <?php endwhile; ?>
   <?php else: ?>
   <tr>
    <td colspan="6">No records found.</td>
   </tr>
   <?php endif; ?>
  </tbody>
 </table>

 <footer>
  <p>&copy; 2025 Inventory Manager Pro | Powered by Technology</p>
 </footer>
</body>

</html>

<?php $conn->close(); ?>

kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk
<?php

include 'db_connection.php'; // Ensure your database connection is correct

// Fetch inventory data
$query = "SELECT * FROM inventory";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inventory Report</title>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
 <style>
 body {
  background-color: #f8f9fa;
  font-family: Arial, sans-serif;
 }

 .container {
  margin-top: 30px;
 }

 .table {
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
 }

 th {
  background-color: #007bff;
  color: white;
 }

 tr:nth-child(even) {
  background-color: #f2f2f2;
 }

 .card {
  border-radius: 10px;
  transition: transform 0.2s;
 }

 .card:hover {
  transform: scale(1.05);
 }
 </style>
</head>

<body>
 <div class="container">
  <h2 class="text-center mb-4">Inventory Report</h2>
  <div class="row text-center mb-4">
   <div class="col-md-4">
    <div class="card p-3 bg-primary text-white">
     <h4>Total Items</h4>
     <h2><i class="fas fa-box"></i> <?php echo mysqli_num_rows($result); ?></h2>
    </div>
   </div>
   <div class="col-md-4">
    <div class="card p-3 bg-success text-white">
     <h4>In Stock</h4>
     <h2><i class="fas fa-check-circle"></i> <?php echo rand(50, 150); ?></h2>
    </div>
   </div>
   <div class="col-md-4">
    <div class="card p-3 bg-danger text-white">
     <h4>Out of Stock</h4>
     <h2><i class="fas fa-times-circle"></i> <?php echo rand(5, 20); ?></h2>
    </div>
   </div>
  </div>
  <table class="table table-striped table-hover text-center">
   <thead>
    <tr>
     <th>ID</th>
     <th>Product Name</th>
     <th>Category</th>
     <th>Stock</th>
     <th>Price</th>
    </tr>
   </thead>
   <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
     <td><?php echo $row['item_id']; ?></td>
     <td><?php echo $row['item_description']; ?></td>
     <td><?php echo $row['category']; ?></td>
     <td><?php echo $row['quantity']; ?></td>
     <td>$<?php echo number_format($row['unit_price'], 2); ?></td>
    </tr>
    <?php } ?>
   </tbody>
  </table>
 </div>
</body>

</html>