<?php
session_start();

// Redirect to login page if not logged in or not a salesperson
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'salesperson') {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db_connection.php';

// Fetch user details from the session
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'] ?? 'Salesperson';

// Fetch total sales and revenue
$sales_query = "SELECT COUNT(*) AS total_sales, SUM(total_sales_before_vat) AS total_revenue FROM sales WHERE salesperson_id = ?";
$stmt = $conn->prepare($sales_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_sales, $total_revenue);
$stmt->fetch();
$stmt->close();

// Fetch recent sales (last 5 sales)
$sales_table_query = "SELECT id, date, invoice_no, customer_name, item_description, quantity, unit_price, total_sales_before_vat, vat, total_sales_after_vat 
                      FROM sales WHERE salesperson_id = ? ORDER BY date DESC LIMIT 5";
$stmt = $conn->prepare($sales_table_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Salesperson Dashboard</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <style>
 body {
  background-color: #f4f4f9;
 }

 .card {
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
 }

 .welcome-banner {
  background: linear-gradient(90deg, #4e73df, #1cc88a);
  color: white;
  padding: 20px;
  border-radius: 10px;
  margin-bottom: 20px;
 }

 .footer {
  margin-top: 20px;
  background-color: #343a40;
  color: white;
  padding: 10px;
  text-align: center;
 }
 </style>
</head>

<body>
 <div class="container my-4">
  <div class="welcome-banner">
   <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
   <p>Your sales performance dashboard is here.</p>
  </div>

  <div class="row g-4">
   <div class="col-md-4">
    <div class="card text-center">
     <div class="card-body">
      <h5 class="card-title">Total Sales</h5>
      <p class="card-text fs-3"><?php echo htmlspecialchars($total_sales ?? 0); ?></p>
     </div>
    </div>
   </div>
   <div class="col-md-4">
    <div class="card text-center">
     <div class="card-body">
      <h5 class="card-title">Total Revenue</h5>
      <p class="card-text fs-3">$<?php echo number_format($total_revenue ?? 0, 2); ?></p>
     </div>
    </div>
   </div>
   <div class="col-md-4">
    <div class="card text-center">
     <div class="card-body">
      <h5 class="card-title">Sales Target</h5>
      <p class="card-text fs-3">$10,000</p>
     </div>
    </div>
   </div>
  </div>

  <div class="mt-4">
   <h2>Recent Sales</h2>
   <table class="table table-hover">
    <thead class="table-dark">
     <tr>
      <th>#</th>
      <th>Date</th>
      <th>Invoice No.</th>
      <th>Customer Name</th>
      <th>Item Description</th>
      <th>Qty</th>
      <th>Unit Price</th>
      <th>Total Before VAT</th>
      <th>VAT</th>
      <th>Total After VAT</th>
     </tr>
    </thead>
    <tbody>
     <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . htmlspecialchars($row['id']) . "</td>
                                <td>" . htmlspecialchars($row['date']) . "</td>
                                <td>" . htmlspecialchars($row['invoice_no']) . "</td>
                                <td>" . htmlspecialchars($row['customer_name']) . "</td>
                                <td>" . htmlspecialchars($row['item_description']) . "</td>
                                <td>" . htmlspecialchars($row['quantity']) . "</td>
                                <td>$" . number_format($row['unit_price'], 2) . "</td>
                                <td>$" . number_format($row['total_sales_before_vat'], 2) . "</td>
                                <td>$" . number_format($row['vat'], 2) . "</td>
                                <td>$" . number_format($row['total_sales_after_vat'], 2) . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10' class='text-center'>No recent sales</td></tr>";
                    }
                    $stmt->close();
                    ?>
    </tbody>
   </table>
  </div>

  <div class="mt-4">
   <a href="add_sale.php" class="btn btn-success btn-lg">Add Sale</a>
   <a href="logout.php" class="btn btn-danger btn-lg">Logout</a>
  </div>
 </div>

 <footer class="footer">
  <p>&copy; <?php echo date('Y'); ?> Sales Management System</p>
 </footer>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>