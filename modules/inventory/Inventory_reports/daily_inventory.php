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