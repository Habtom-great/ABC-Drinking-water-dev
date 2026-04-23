<?php 
session_start();
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
  overflow: hidden;
 }

 th {
  background-color: #007bff;
  color: white;
 }

 tr:hover {
  background-color: #f1f1f1;
  cursor: pointer;
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
    <tr onclick="window.location.href='inventory_details.php?id=<?php echo $row['item_id']; ?>'">
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