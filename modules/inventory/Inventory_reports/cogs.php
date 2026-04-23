<?php
// Database Connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "abc_company";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default filters
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch Categories for Dropdown
$category_query = "SELECT DISTINCT category FROM inventory";
$category_result = $conn->query($category_query);

// SQL Query with Filters
$sql = "SELECT 
            item_id, 
            item_description, 
            category,
            beginning_inventory_quantity,
            purchased_inventory_quantity,
            ending_inventory_quantity,
            beginning_inventory_cost, 
            purchased_inventory_cost, 
            ending_inventory_cost,
            (beginning_inventory_quantity + purchased_inventory_quantity - ending_inventory_quantity) AS units_sold, 
            (COALESCE(beginning_inventory_cost, 0) + COALESCE(purchased_inventory_cost, 0)) 
                / NULLIF((COALESCE(beginning_inventory_quantity, 0) + COALESCE(purchased_inventory_quantity, 0)), 0) 
                AS avg_cost_per_unit, 
            ((beginning_inventory_quantity + purchased_inventory_quantity - ending_inventory_quantity) * 
             ((COALESCE(beginning_inventory_cost, 0) + COALESCE(purchased_inventory_cost, 0)) 
             / NULLIF((COALESCE(beginning_inventory_quantity, 0) + COALESCE(purchased_inventory_quantity, 0)), 0))) 
             AS cogs,
            date
        FROM inventory 
        WHERE date BETWEEN '$start_date' AND '$end_date'";

if (!empty($category)) {
    $sql .= " AND category = '$category'";
}

$sql .= " ORDER BY date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>COGS Report with Filters</title>
 <style>
 body {
  font-family: Arial, sans-serif;
  margin: 20px;
  background-color: #f4f4f4;
 }

 h2 {
  text-align: center;
  color: #333;
 }

 table {
  width: 95%;
  margin: 20px auto;
  border-collapse: collapse;
  background: white;
  box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.2);
  border-radius: 5px;
 }

 th,
 td {
  padding: 10px;
  text-align: center;
  border-bottom: 1px solid #ddd;
 }

 th {
  background: #007bff;
  color: white;
 }

 tr:hover {
  background: #f1f1f1;
 }

 form {
  text-align: center;
  margin-bottom: 20px;
 }

 select,
 input[type="date"],
 button {
  padding: 7px;
  margin: 5px;
 }

 button {
  background: #007bff;
  color: white;
  border: none;
  cursor: pointer;
 }

 button:hover {
  background: #0056b3;
 }
 </style>
</head>

<body>

 <h2>Cost of Goods Sold Report (Filtered by Date & Category)</h2>

 <!-- Filter Form -->
 <form method="GET">
  <label>Start Date:</label>
  <input type="date" name="start_date" value="<?= $start_date ?>" required>

  <label>End Date:</label>
  <input type="date" name="end_date" value="<?= $end_date ?>" required>

  <label>Category:</label>
  <select name="category">
   <option value="">All Categories</option>
   <?php while ($cat_row = $category_result->fetch_assoc()): ?>
   <option value="<?= $cat_row['category'] ?>" <?= ($category == $cat_row['category']) ? 'selected' : '' ?>>
    <?= $cat_row['category'] ?>
   </option>
   <?php endwhile; ?>
  </select>

  <button type="submit">Generate Report</button>
 </form>

 <table>
  <tr>
   <th>ID</th>
   <th>Product Description</th>
   <th>Category</th>
   <th>Beginning Inventory Qty</th>
   <th>Beginning Inventory Cost (Birr)</th>
   <th>Purchased Inventory Qty</th>
   <th>Purchased Inventory Cost (Birr)</th>
   <th>Ending Inventory Qty</th>
   <th>Ending Inventory Cost (Birr)</th>
   <th>Units Sold</th>
   <th>Avg Cost Per Unit (Birr)</th>
   <th>COGS (Birr)</th>
   <th>Date</th>
  </tr>

  <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['item_id']}</td>
                    <td>{$row['item_description']}</td>
                    <td>{$row['category']}</td>
                    <td>{$row['beginning_inventory_quantity']}</td>
                    <td>" . number_format((float) $row['beginning_inventory_cost'], 2) . "</td>
                    <td>{$row['purchased_inventory_quantity']}</td>
                    <td>" . number_format((float) $row['purchased_inventory_cost'], 2) . "</td>
                    <td>{$row['ending_inventory_quantity']}</td>
                    <td>" . number_format((float) $row['ending_inventory_cost'], 2) . "</td>
                    <td>{$row['units_sold']}</td>
                    <td>" . number_format((float) $row['avg_cost_per_unit'], 2) . "</td>
                    <td style='font-weight: bold; color: green;'>" . number_format((float) $row['cogs'], 2) . "</td>
                    <td>{$row['date']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='13'>No records found</td></tr>";
        }
        $conn->close();
    ?>
 </table>

</body>

</html>

kkk
<?php
// Database Connection
$servername = "localhost";
$username = "root"; // Default user in XAMPP
$password = ""; // Default is empty in XAMPP
$dbname = "abc_company";

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch COGS Data from Inventory Table
$sql = "SELECT 
            item_id, 
            item_description, 
            beginning_inventory, 
            purchased, 
          

            ending_inventory, 
            (beginning_inventory + purchased  - ending_inventory) AS cogs, 
            date
        FROM inventory 
        ORDER BY date DESC";

$result = $conn->query($sql);

// Check for Errors
if (!$result) {
    die("Error in SQL query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Cost of Goods Sold Report</title>
 <style>
 body {
  font-family: Arial, sans-serif;
  margin: 20px;
  background-color: #f4f4f4;
 }

 h2 {
  text-align: center;
  color: #333;
 }

 table {
  width: 80%;
  margin: 20px auto;
  border-collapse: collapse;
  background: white;
  box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.2);
  border-radius: 5px;
 }

 th,
 td {
  padding: 10px;
  text-align: center;
  border-bottom: 1px solid #ddd;
 }

 th {
  background: #007bff;
  color: white;
 }

 tr:hover {
  background: #f1f1f1;
 }
 </style>
</head>

<body>

 <h2>Cost of Goods Sold Report</h2>

 <table>
  <tr>
   <th>ID</th>
   <th>Product Description</th>
   <th>Beginning Inventory Birr</th>
   <th>Purchases Birr</th>

   <th>Ending Inventory Birr</th>
   <th>COGS Birr</th>
   <th>Date </th>
  </tr>

  <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['item_id']}</td>
                    <td>{$row['item_description']}</td>
                    <td>{$row['beginning_inventory']}</td>
                    <td>{$row['purchased']}</td>
                 
                    <td>{$row['ending_inventory']}</td>
                    <td style='font-weight: bold; color: green;'>{$row['cogs']}</td>
                    <td>{$row['date']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No records found</td></tr>";
        }
        $conn->close();
        ?>

 </table>

</body>

</html>