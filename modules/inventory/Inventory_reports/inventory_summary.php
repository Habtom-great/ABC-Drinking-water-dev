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
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inventory Summary</title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
 <style>
 body {
  background-color: #f8f9fa;
 }

 .container {
  margin-top: 50px;
 }

 table {
  background: #ffffff;
  border-radius: 8px;
  overflow: hidden;
 }

 th {
  background-color: #007bff;
  color: white;
  text-align: center;
 }

 td,
 th {
  padding: 10px;
  border: 1px solid #dee2e6;
 }

 .low-stock {
  background-color: #ffc107;
  color: black;
  font-weight: bold;
 }
 </style>
</head>

<body>

 <div class="container">
  <h2 class="text-center text-primary mb-4">📦 Inventory Summary</h2>

  <div class="table-responsive">
   <table class="table table-bordered text-center">
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
     <?php while ($row = mysqli_fetch_assoc($result)): ?>
     <tr class="<?= ($row['quantity'] < 10) ? 'low-stock' : '' ?>">
      <td><?= htmlspecialchars($row['item_description']) ?></td>
      <td><?= htmlspecialchars($row['category']) ?></td>
      <td><?= htmlspecialchars($row['quantity']) ?></td>
      <td>$<?= number_format($row['unit_price'], 2) ?></td>
      <td><?= htmlspecialchars($row['vendor_name']) ?></td>
      <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
     </tr>
     <?php endwhile; ?>
    </tbody>
   </table>
  </div>

 </div>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>