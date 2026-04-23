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
 kkkkkkkkkkkkkkkkkkkkk
 <!-- Item Type -->
 <div class="form-group">
  <label for="item_type">Item Type:</label>
  <select id="item_type" name="item_type">
   <option value="">Select Item Type</option>
   <option value="product">Product</option>
   <option value="service">Service</option>
  </select>
 </div>

 <!-- UOM -->
 <div class="form-group">
  <label for="uom">Unit of Measure (UOM):</label>
  <select id="uom" name="uom">
   <option value="">Select UOM</option>
   <option value="piece">Piece</option>
   <option value="kg">Kilogram</option>
   <option value="litre">Litre</option>
  </select>
 </div>

 <!-- GL Sales -->
 <div class="form-group">
  <label for="gl_sales">GL Sales Account:</label>
  <input type="text" id="gl_sales" name="gl_sales" placeholder="Enter GL sales account">
 </div>

 <!-- GL Inventory -->
 <div class="form-group">
  <label for="gl_inventory">GL Inventory Account:</label>
  <input type="text" id="gl_inventory" name="gl_inventory" placeholder="Enter GL inventory account">
 </div>

 <!-- GL Cost of Sales -->
 <div class="form-group">
  <label for="gl_cost_of_sales">GL Cost of Sales:</label>
  <input type="text" id="gl_cost_of_sales" name="gl_cost_of_sales" placeholder="Enter GL cost of sales account">
 </div>

 <!-- Location -->
 <div class="form-group">
  <label for="location">Location:</label>
  <input type="text" id="location" name="location" placeholder="Enter location">
 </div>

 <!-- Discount -->
 <div class="form-group">
  <label for="discount">Discount (%):</label>
  <input type="number" id="discount" name="discount" placeholder="Enter discount" step="0.01" min="0" max="100">
 </div>

 <!-- Description -->
 <div class="form-group">
  <label for="description">Description:</label>
  <textarea id="description" name="description" rows="4" placeholder="Enter purchase details"></textarea>
 </div>


 jkkkkkkkkkkkkkkk
 <footer>
  <p>&copy; 2025 Inventory Manager Pro | Powered by Technology</p>
 </footer>
</body>

</html>

<?php $conn->close(); ?>