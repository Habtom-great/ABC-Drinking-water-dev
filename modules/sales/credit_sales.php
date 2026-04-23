<?php
session_start();
include 'header.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ABC_Company";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check for form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sales_type = $_POST['sales_type'];
    $customer_name = $_POST['customer_name'];
    $customer_id = $_POST['customer_id'];
    $item_description = $_POST['item_description'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];
    $payment_method = $_POST['payment_method'];
    $due_date = isset($_POST['due_date']) ? $_POST['due_date'] : null;
    $payment_status = ($sales_type == 'credit') ? 'Pending' : 'Paid';

    $total_sales = $quantity * $unit_price;
    $vat_rate = 0.15; // 15% VAT
    $vat_amount = $total_sales * $vat_rate;
    $total_with_vat = $total_sales + $vat_amount;

    // Insert the sales data into the database
    $sql = "INSERT INTO sales_orders (sales_type, customer_name, customer_id, item_description, quantity, unit_price, total_sales, vat_amount, total_with_vat, payment_method, payment_status, due_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $sales_type, $customer_name, $customer_id, $item_description, $quantity, $unit_price, $total_sales, $vat_amount, $total_with_vat, $payment_method, $payment_status, $due_date);
    if ($stmt->execute()) {
        $message = "Sales order successfully recorded!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Credit and Cash Sales Form</title>
 <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
 <style>
 .form-container {
  margin-top: 20px;
 }

 .form-container h2 {
  margin-bottom: 30px;
  text-align: center;
 }

 .btn-submit {
  width: 100%;
 }

 .alert {
  margin-top: 20px;
 }
 </style>
</head>

<body>
 <div class="container form-container">
  <h2>Sales Order Form</h2>

  <!-- Display success or error message -->
  <?php if (isset($message)) { ?>
  <div class="alert alert-success" role="alert">
   <?php echo $message; ?>
  </div>
  <?php } ?>

  <form action="" method="POST">
   <!-- Customer Details Section -->
   <div class="row">
    <div class="col-md-6">
     <div class="form-group">
      <label for="customer_name">Customer Name</label>
      <input type="text" class="form-control" id="customer_name" name="customer_name" required>
     </div>
    </div>
    <div class="col-md-6">
     <div class="form-group">
      <label for="customer_id">Customer ID</label>
      <input type="text" class="form-control" id="customer_id" name="customer_id" required>
     </div>
    </div>
   </div>

   <!-- Sales Type Section (Credit or Cash) -->
   <div class="form-group">
    <label for="sales_type">Sales Type</label>
    <select class="form-control" id="sales_type" name="sales_type" required>
     <option value="credit">Credit</option>
     <option value="cash">Cash</option>
    </select>
   </div>

   <!-- Item Details Section -->
   <div class="row">
    <div class="col-md-6">
     <div class="form-group">
      <label for="item_description">Item Description</label>
      <input type="text" class="form-control" id="item_description" name="item_description" required>
     </div>
    </div>
    <div class="col-md-3">
     <div class="form-group">
      <label for="quantity">Quantity</label>
      <input type="number" class="form-control" id="quantity" name="quantity" required>
     </div>
    </div>
    <div class="col-md-3">
     <div class="form-group">
      <label for="unit_price">Unit Price</label>
      <input type="number" class="form-control" id="unit_price" name="unit_price" required>
     </div>
    </div>
   </div>

   <!-- Payment Method Section (Cash or Credit) -->
   <div class="form-group">
    <label for="payment_method">Payment Method</label>
    <select class="form-control" id="payment_method" name="payment_method" required>
     <option value="cash">Cash</option>
     <option value="credit">Credit</option>
    </select>
   </div>

   <!-- Due Date for Credit Sales -->
   <div class="form-group" id="due_date_section" style="display: none;">
    <label for="due_date">Due Date</label>
    <input type="date" class="form-control" id="due_date" name="due_date">
   </div>

   <!-- Submit Button -->
   <button type="submit" class="btn btn-primary btn-submit">Submit Sales Order</button>
  </form>
 </div>

 <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.0/dist/umd/popper.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

 <script>
 // Show the due date field if 'Credit' sales type is selected
 document.getElementById('sales_type').addEventListener('change', function() {
  var salesType = this.value;
  if (salesType === 'credit') {
   document.getElementById('due_date_section').style.display = 'block';
  } else {
   document.getElementById('due_date_section').style.display = 'none';
  }
 });
 </script>
</body>

</html>