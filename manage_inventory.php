<?php
session_start();

// Simulated login
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = 'Admin';
}

// Database credentials
$host = 'localhost';
$db   = 'abc_company';
$user = 'root';
$pass = '';

// Initialize chart data
$data = [
    "labels" => [],
    "values" => [],
];

// Initialize summary data
$summary = [
    "total_value" => 0,
    "total_items" => 0,
    "active_branches" => 0,
    "low_stock_items" => 0
];

try {
    // Establish PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query inventory data for chart
    $query = $pdo->query("
        SELECT 
            DATE_FORMAT(date, '%M') AS month_name,
            SUM(quantity * unit_price) AS total_value
        FROM inventory
        GROUP BY MONTH(date)
        ORDER BY MONTH(date)
    ");

    $labels = [];
    $values = [];

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $row['month_name'];
        $values[] = (float) $row['total_value'];
    }

    $data = [
        "labels" => $labels,
        "values" => $values,
    ];

    // Query summary data
    $summary_query = $pdo->query("
        SELECT 
            SUM(quantity * unit_price) AS total_value,
            COUNT(DISTINCT item_id) AS total_items,
            (SELECT COUNT(DISTINCT branch_id) FROM branches) AS active_branches,
            (SELECT COUNT(*) FROM inventory WHERE quantity < 10) AS low_stock_items
        FROM inventory
    ");
    
    if ($summary_row = $summary_query->fetch(PDO::FETCH_ASSOC)) {
        $summary = [
            "total_value" => number_format($summary_row['total_value'] ?? 0, 2),
            "total_items" => number_format($summary_row['total_items'] ?? 0),
            "active_branches" => $summary_row['active_branches'] ?? 0,
            "low_stock_items" => $summary_row['low_stock_items'] ?? 0
        ];
    }

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    // Optionally show a friendly message
}

// Report Dropdown
$report_types = [
    'inventory_reports/generate_report-1.php' => 'Generate Report-1',
    'inventory_reports/generate_report-2.php' => 'Generate Report-2',
    'inventory_reports/inventory_summary.php' => 'Inventory Summary',
    'inventory_reports/inventory_summary-2.php' => 'Detailed Summary',
    'inventory_reports/daily_inventory.php' => 'Daily Reports',
    'inventory_reports/other_report.php' => 'Other Reports',
    'inventory_reports/low_stock.php' => 'Low Stock Items',
    'inventory_reports/out_of_stock.php' => 'Out of Stock',
    'inventory_reports/filtered_inventory.php' => 'Filtered Inventory',
    'inventory_reports/sales_report.php' => 'Sales Report',
    'special_inventory_report.php' => 'Special Report'
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inventory Management Dashboard</title>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
 <style>
 :root {
  --primary: #2e7d32;
  --primary-light: #4caf50;
  --primary-dark: #1b5e20;
  --secondary: #333;
  --accent: #ff9800;
  --danger: #f44336;
  --light: #f4f6f9;
  --dark: #333;
  --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  --transition: all 0.3s ease;
 }

 * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
 }

 body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: var(--light);
  color: var(--dark);
  line-height: 1.6;
 }

 header {
  background-color: var(--primary);
  color: white;
  padding: 1.5rem;
  text-align: center;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
 }

 header h1 {
  font-size: 1.8rem;
  margin-bottom: 0.5rem;
 }

 .user-info {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  font-size: 1rem;
 }

 nav {
  background: var(--secondary);
  display: flex;
  justify-content: center;
  position: sticky;
  top: 0;
  z-index: 100;
 }

 nav ul {
  display: flex;
  flex-wrap: wrap;
  list-style: none;
  max-width: 1200px;
  width: 100%;
 }

 nav ul li {
  position: relative;
 }

 nav ul li a {
  color: white;
  padding: 1rem 1.5rem;
  display: block;
  text-decoration: none;
  transition: var(--transition);
  font-size: 0.95rem;
 }

 nav ul li a:hover {
  background: var(--primary-light);
 }

 nav ul li a i {
  margin-right: 0.5rem;
 }

 .dropdown-content {
  display: none;
  position: absolute;
  background-color: #444;
  min-width: 220px;
  z-index: 1;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
 }

 .dropdown-content a {
  padding: 0.8rem 1rem;
  color: white;
  display: block;
  transition: var(--transition);
 }

 .dropdown-content a:hover {
  background: var(--primary);
 }

 .dropdown:hover .dropdown-content {
  display: block;
 }

 main {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
 }

 .summary-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2.5rem;
 }

 .card {
  background: white;
  padding: 1.5rem;
  border-radius: 0.5rem;
  box-shadow: var(--card-shadow);
  text-align: center;
  transition: var(--transition);
  border-top: 4px solid var(--primary);
 }

 .card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
 }

 .card h3 {
  font-size: 1rem;
  margin-bottom: 0.8rem;
  color: var(--primary);
 }

 .card p {
  font-size: 1.8rem;
  font-weight: bold;
  color: var(--dark);
 }

 .card.low-stock {
  border-top-color: var(--accent);
 }

 .card.low-stock h3 {
  color: var(--accent);
 }

 .card.danger {
  border-top-color: var(--danger);
 }

 .card.danger h3 {
  color: var(--danger);
 }

 .section-title {
  text-align: center;
  margin: 2rem 0 1.5rem;
  color: var(--primary-dark);
  position: relative;
 }

 .section-title:after {
  content: '';
  display: block;
  width: 80px;
  height: 3px;
  background: var(--primary);
  margin: 0.5rem auto 0;
 }

 .chart-container {
  background: white;
  padding: 1.5rem;
  border-radius: 0.5rem;
  box-shadow: var(--card-shadow);
  margin: 2rem 0;
 }

 .updates {
  background: white;
  padding: 1.5rem;
  border-radius: 0.5rem;
  box-shadow: var(--card-shadow);
 }

 .updates h2 {
  margin-bottom: 1rem;
  color: var(--primary-dark);
 }

 .updates ul {
  list-style: none;
 }

 .updates li {
  padding: 0.8rem 0;
  border-bottom: 1px solid #eee;
  display: flex;
  align-items: center;
  gap: 0.8rem;
 }

 .updates li:last-child {
  border-bottom: none;
 }

 .updates li i {
  font-size: 1.2rem;
 }

 .update-new {
  background-color: rgba(76, 175, 80, 0.1);
  border-left: 3px solid var(--primary);
  padding-left: 0.8rem;
 }

 footer {
  background: var(--primary-dark);
  color: white;
  text-align: center;
  padding: 1.5rem;
  margin-top: 3rem;
 }

 @media (max-width: 768px) {
  nav ul {
   flex-direction: column;
  }

  .dropdown-content {
   position: static;
   width: 100%;
  }

  main {
   padding: 1rem;
  }
 }
 </style>
</head>

<body>
 <header>
  <h1>Inventory Management Dashboard</h1>
  <div class="user-info">
   <i class="fas fa-user-circle"></i>
   <span>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</span>
  </div>
 </header>

 <nav>
  <ul>
   <li><a href="add_inventory.php"><i class="fas fa-plus-square"></i> Add Inventory</a></li>
   <li><a href="edit_inventory.php"><i class="fas fa-edit"></i> Edit Inventory</a></li>
   <li><a href="delete_inventory.php"><i class="fas fa-trash-alt"></i> Delete Inventory</a></li>
   <li><a href="adjust_inventory.php"><i class="fas fa-sliders-h"></i> Adjust Inventory</a></li>
   <li class="dropdown">
    <a href="#"><i class="fas fa-chart-line"></i> Reports <i class="fas fa-caret-down"></i></a>
    <div class="dropdown-content">
     <?php foreach ($report_types as $file => $name): ?>
     <a href="<?php echo htmlspecialchars($file); ?>"><?php echo htmlspecialchars($name); ?></a>
     <?php endforeach; ?>
    </div>
   </li>
   <li><a href="salesperson_performance.php"><i class="fas fa-user-tie"></i> Performance</a></li>
  </ul>
 </nav>

 <main>
  <h2 class="section-title">Dashboard Summary</h2>
  <div class="summary-cards">
   <div class="card">
    <h3><i class="fas fa-dollar-sign"></i> Total Inventory Value</h3>
    <p>$<?php echo $summary['total_value']; ?></p>
   </div>
   <div class="card">
    <h3><i class="fas fa-boxes"></i> Total Items</h3>
    <p><?php echo $summary['total_items']; ?></p>
   </div>
   <div class="card">
    <h3><i class="fas fa-store"></i> Active Branches</h3>
    <p><?php echo $summary['active_branches']; ?></p>
   </div>
   <div class="card low-stock">
    <h3><i class="fas fa-exclamation-triangle"></i> Low Stock Items</h3>
    <p><?php echo $summary['low_stock_items']; ?></p>
   </div>
  </div>

  <div class="chart-container">
   <h2 class="section-title">Monthly Inventory Growth</h2>
   <canvas id="inventoryChart"></canvas>
  </div>

  <div class="updates">
   <h2 class="section-title">Recent Updates</h2>
   <ul>
    <li class="update-new">
     <i class="fas fa-box-open"></i>
     <div>
      <strong>Added:</strong> 100 units of Product Z to Warehouse A
      <div class="update-time">Today, 10:30 AM</div>
     </div>
    </li>
    <li>
     <i class="fas fa-exchange-alt"></i>
     <div>
      <strong>Adjusted:</strong> Removed 5 units of Product X from Warehouse B
      <div class="update-time">Yesterday, 3:45 PM</div>
     </div>
    </li>
    <li>
     <i class="fas fa-tags"></i>
     <div>
      <strong>Updated:</strong> Pricing for seasonal items
      <div class="update-time">Monday, 9:15 AM</div>
     </div>
    </li>
    <li>
     <i class="fas fa-bell"></i>
     <div>
      <strong>Alert:</strong> 3 items approaching reorder level
      <div class="update-time">Last Friday</div>
     </div>
    </li>
   </ul>
  </div>
 </main>

 <footer>
  <p>&copy; <?php echo date('Y'); ?> Inventory Manager Pro | Built with <i class="fas fa-heart"
    style="color: #ff6b6b;"></i></p>
 </footer>

 <script>
 const graphData = <?php echo json_encode($data); ?>;

 document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById("inventoryChart").getContext("2d");
  new Chart(ctx, {
   type: 'line',
   data: {
    labels: graphData.labels,
    datasets: [{
     label: 'Inventory Value ($)',
     data: graphData.values,
     backgroundColor: 'rgba(76, 175, 80, 0.1)',
     borderColor: '#4CAF50',
     borderWidth: 3,
     tension: 0.3,
     pointBackgroundColor: "#4CAF50",
     pointRadius: 5,
     pointHoverRadius: 7
    }]
   },
   options: {
    responsive: true,
    plugins: {
     legend: {
      position: 'top',
      labels: {
       font: {
        size: 14
       }
      }
     },
     tooltip: {
      callbacks: {
       label: function(context) {
        return `$${context.raw.toLocaleString()}`;
       }
      }
     },
     datalabels: {
      display: false
     }
    },
    scales: {
     x: {
      title: {
       display: true,
       text: 'Month',
       font: {
        weight: 'bold'
       }
      },
      grid: {
       display: false
      }
     },
     y: {
      title: {
       display: true,
       text: 'Inventory Value ($)',
       font: {
        weight: 'bold'
       }
      },
      ticks: {
       callback: function(value) {
        return '$' + value.toLocaleString();
       }
      }
     }
    },
    interaction: {
     intersect: false,
     mode: 'index'
    }
   }
  });
 });
 </script>
</body>

</html>

kkkkkkkkk
<?php
// config.php content here, or require your config.php that sets up $conn
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abc_company";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$months = ["January", "February", "March", "April", "May", "June",
           "July", "August", "September", "October", "November", "December"];

// Defaults
$year = date("Y");
$fromMonth = 1;
$toMonth = 12;
$chartType = "bar";

// Override defaults if POST submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $year = intval($_POST['year']) ?: $year;
    $fromMonth = intval($_POST['fromMonth']);
    $toMonth = intval($_POST['toMonth']);
    $chartType = $_POST['chartType'] ?? $chartType;

    // Validate month range
    if ($fromMonth < 1) $fromMonth = 1;
    if ($toMonth > 12) $toMonth = 12;
    if ($toMonth < $fromMonth) {
        // Swap if invalid range
        $tmp = $fromMonth;
        $fromMonth = $toMonth;
        $toMonth = $tmp;
    }
}

$inventoryData = [];
$salesData = [];
$selectedMonths = [];

for ($i = $fromMonth; $i <= $toMonth; $i++) {
    // Inventory query
    $stmt1 = $conn->prepare("SELECT SUM(quantity) as total_inventory FROM inventory WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?");
    if (!$stmt1) {
        die("Inventory prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt1->bind_param("ii", $i, $year);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $row1 = $result1->fetch_assoc();
    $inventoryData[] = $row1['total_inventory'] ?? 0;
    $stmt1->close();

    // Sales query
    $stmt2 = $conn->prepare("SELECT SUM(quantity) as total_sales FROM sales WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?");
    if (!$stmt2) {
        die("Sales prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt2->bind_param("ii", $i, $year);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $row2 = $result2->fetch_assoc();
    $salesData[] = $row2['total_sales'] ?? 0;
    $stmt2->close();

    $selectedMonths[] = $months[$i - 1];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1" />
 <title>Inventory vs Sales Report</title>
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <style>
 body {
  font-family: Arial, sans-serif;
  margin: 40px;
  background-color: #f4f6f8;
 }

 h2 {
  text-align: center;
  color: #333;
 }

 #chartContainer {
  width: 90%;
  max-width: 1000px;
  margin: 0 auto 40px;
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
 }

 form {
  max-width: 600px;
  margin: 0 auto 40px;
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 1px 8px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  justify-content: center;
 }

 label {
  font-weight: bold;
  margin-right: 8px;
 }

 select,
 input[type="number"] {
  padding: 6px 10px;
  font-size: 1rem;
  border-radius: 4px;
  border: 1px solid #ccc;
  min-width: 120px;
 }

 button {
  padding: 10px 20px;
  background-color: #36a2eb;
  border: none;
  border-radius: 6px;
  color: white;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
 }

 button:hover {
  background-color: #2a82c9;
 }
 </style>
</head>

<body>

 <h2>Inventory vs Sales Report</h2>

 <form method="post">
  <div>
   <label for="year">Year:</label>
   <input type="number" id="year" name="year" min="2000" max="2100" value="<?= htmlspecialchars($year) ?>" required />
  </div>

  <div>
   <label for="fromMonth">From Month:</label>
   <select id="fromMonth" name="fromMonth" required>
    <?php foreach ($months as $index => $m): ?>
    <option value="<?= $index + 1 ?>" <?= ($fromMonth === ($index + 1)) ? 'selected' : '' ?>><?= $m ?></option>
    <?php endforeach; ?>
   </select>
  </div>

  <div>
   <label for="toMonth">To Month:</label>
   <select id="toMonth" name="toMonth" required>
    <?php foreach ($months as $index => $m): ?>
    <option value="<?= $index + 1 ?>" <?= ($toMonth === ($index + 1)) ? 'selected' : '' ?>><?= $m ?></option>
    <?php endforeach; ?>
   </select>
  </div>

  <div>
   <label for="chartType">Chart Type:</label>
   <select id="chartType" name="chartType" required>
    <option value="bar" <?= $chartType === 'bar' ? 'selected' : '' ?>>Bar Chart</option>
    <option value="line" <?= $chartType === 'line' ? 'selected' : '' ?>>Line Chart</option>
    <option value="pie" <?= $chartType === 'pie' ? 'selected' : '' ?>>Pie Chart</option>
   </select>
  </div>

  <div style="align-self: center;">
   <button type="submit">Generate Report</button>
  </div>
 </form>

 <div id="chartContainer">
  <canvas id="inventorySalesChart"></canvas>
 </div>

 <script>
 const ctx = document.getElementById('inventorySalesChart').getContext('2d');

 // Data for chart
 const labels = <?= json_encode($selectedMonths) ?>;

 const inventoryData = <?= json_encode($inventoryData) ?>;
 const salesData = <?= json_encode($salesData) ?>;

 let chartConfig;

 if ('<?= $chartType ?>' === 'pie') {
  // Pie chart: show Inventory and Sales total sums only
  const totalInventory = inventoryData.reduce((a, b) => a + b, 0);
  const totalSales = salesData.reduce((a, b) => a + b, 0);

  chartConfig = {
   type: 'pie',
   data: {
    labels: ['Inventory Total', 'Sales Total'],
    datasets: [{
     label: 'Totals',
     data: [totalInventory, totalSales],
     backgroundColor: [
      'rgba(54, 162, 235, 0.7)',
      'rgba(255, 99, 132, 0.7)'
     ],
     borderColor: [
      'rgba(54, 162, 235, 1)',
      'rgba(255, 99, 132, 1)'
     ],
     borderWidth: 1
    }]
   },
   options: {
    responsive: true,
    plugins: {
     legend: {
      position: 'top'
     },
     title: {
      display: true,
      text: 'Inventory vs Sales Totals (' + <?= json_encode($year) ?> + ')'
     }
    }
   }
  };
 } else {
  // Bar or Line chart: show monthly data
  chartConfig = {
   type: '<?= $chartType ?>',
   data: {
    labels: labels,
    datasets: [{
      label: 'Inventory',
      data: inventoryData,
      backgroundColor: 'rgba(54, 162, 235, 0.7)',
      borderColor: 'rgba(54, 162, 235, 1)',
      borderWidth: 1,
      fill: false,
      tension: 0.1
     },
     {
      label: 'Sales',
      data: salesData,
      backgroundColor: 'rgba(255, 99, 132, 0.7)',
      borderColor: 'rgba(255, 99, 132, 1)',
      borderWidth: 1,
      fill: false,
      tension: 0.1
     }
    ]
   },
   options: {
    responsive: true,
    scales: {
     y: {
      beginAtZero: true,
      title: {
       display: true,
       text: 'Quantity'
      }
     }
    },
    plugins: {
     title: {
      display: true,
      text: 'Inventory vs Sales (' + <?= json_encode($year) ?> + ')'
     },
     legend: {
      position: 'top'
     }
    }
   }
  };
 }

 // Create and render chart
 const inventorySalesChart = new Chart(ctx, chartConfig);
 </script>

</body>

</html>