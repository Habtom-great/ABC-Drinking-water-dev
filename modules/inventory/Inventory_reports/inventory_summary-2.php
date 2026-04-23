<?php
session_start();
 // Adjust path based on your folder structure
include '../db_connection.php'; // Ensure correct path

// Fetch inventory data
$query = "SELECT * FROM inventory ORDER BY quantity ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}


// Fetch inventory data from the database
$query = "SELECT item_description, category, qty, unit_price, vendor_name, created_at FROM inventory";
$result = mysqli_query($conn, $query);

$inventory_data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $inventory_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inventory Summary</title>
 <link rel="stylesheet" href="styles.css">
 <script src="js/inventory.js" defer></script>
 <style>
 body {
  font-family: Arial, sans-serif;
  background-color: #f9f9f9;
  margin: 0;
  padding: 0;
 }

 .container {
  width: 90%;
  margin: 20px auto;
  background: white;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
 }

 h2 {
  color: #333;
  text-align: center;
  margin-bottom: 20px;
 }

 .filter-section {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
 }

 .filter-section input {
  padding: 8px;
  width: 300px;
  border: 1px solid #ccc;
  border-radius: 5px;
 }

 .filter-section button {
  padding: 8px 15px;
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 5px;
 }

 .filter-section button:hover {
  background-color: #45a049;
 }

 table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
 }

 th,
 td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
 }

 th {
  background-color: #4CAF50;
  color: white;
 }

 tr:hover {
  background-color: #f1f1f1;
 }

 .low-stock {
  background-color: #ffdddd;
  color: red;
  font-weight: bold;
 }

 .export-btn {
  padding: 8px 15px;
  background-color: #007BFF;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  margin-top: 10px;
 }

 .export-btn:hover {
  background-color: #0056b3;
 }
 </style>
</head>

<body>

 <div class="container">
  <h2>📦 Inventory Summary</h2>

  <div class="filter-section">
   <input type="text" id="search" placeholder="🔍 Search by name or category...">
   <button onclick="exportCSV()">📥 Export to CSV</button>
  </div>

  <table id="inventoryTable">
   <thead>
    <tr>
     <th>Item Name</th>
     <th>Category</th>
     <th>Stock</th>
     <th>Price (Birr)</th>
     <th>Supplier</th>
     <th>Last Updated</th>
    </tr>
   </thead>
   <tbody>
    <?php if (!empty($inventory_data)): ?>
    <?php foreach ($inventory_data as $item): ?>
    <tr class="<?= ($item['quantity'] < 10) ? 'low-stock' : '' ?>">
     <td><?= htmlspecialchars($item['item_description']) ?></td>
     <td><?= htmlspecialchars($item['category']) ?></td>
     <td><?= htmlspecialchars($item['quantity']) ?></td>
     <td><?= number_format($item['unit_price'], 2) ?></td>
     <td><?= htmlspecialchars($item['vendor_name']) ?></td>
     <td><?= date('Y-m-d', strtotime($item['created_at'])) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
     <td colspan="6" style="text-align: center; color: red;">No inventory data available</td>
    </tr>
    <?php endif; ?>
   </tbody>
  </table>
 </div>

 <script>
 document.getElementById("search").addEventListener("keyup", function() {
  let searchValue = this.value.toLowerCase();
  let rows = document.querySelectorAll("#inventoryTable tbody tr");

  rows.forEach(row => {
   let itemName = row.cells[0].innerText.toLowerCase();
   let category = row.cells[1].innerText.toLowerCase();

   if (itemName.includes(searchValue) || category.includes(searchValue)) {
    row.style.display = "";
   } else {
    row.style.display = "none";
   }
  });
 });

 function exportCSV() {
  let csvContent = "data:text/csv;charset=utf-8,";
  csvContent += "Item Name,Category,Stock,Price (Birr),Supplier,Last Updated\n";

  let rows = document.querySelectorAll("#inventoryTable tbody tr");
  rows.forEach(row => {
   let columns = row.querySelectorAll("td");
   let rowData = [];

   columns.forEach(col => {
    rowData.push(col.innerText);
   });

   csvContent += rowData.join(",") + "\n";
  });

  let encodedUri = encodeURI(csvContent);
  let link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", "inventory_summary.csv");
  document.body.appendChild(link);
  link.click();
 }
 </script>

</body>

</html>

<?php
mysqli_close($conn);
?>