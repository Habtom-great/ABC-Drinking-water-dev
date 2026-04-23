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

// Include database connection
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $sales_order_no = $_POST['sales_order_no'];
    $salesperson_id = $_POST['salesperson_id'];
    $salesperson_name = $_POST['salesperson_name'];
    $reference = $_POST['reference'];
    $invoice_no = $_POST['invoice_no'];
    $item_id = $_POST['item_id'];
    $item_description = $_POST['item_description'];
    $uom = $_POST['uom'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $GL_account = $_POST['GL_account'];
    $unit_price = $_POST['unit_price'];
    $branch_id = $_POST['branch_id'];
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];
    $payment_method = $_POST['payment_method'];
    $job_id = $_POST['job_id'];
    $section = $_POST['section'];

    // Calculate total sales and profit
    $total_sales = $quantity * $unit_price;
    $total_profit = $total_sales * 0.2; // Assuming 20% profit margin

    // SQL query to insert sales order data
    $sql = "INSERT INTO sales_orders (
                sales_order_no, salesperson_id, salesperson_name, reference, invoice_no, 
                item_id, item_description, uom, product_id, quantity, GL_account, 
                unit_price, total_sales, total_profit, date, branch_id, 
                customer_id, customer_name, payment_method, job_id, section
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("sisssssssisdddsisssss", 
            $sales_order_no, $salesperson_id, $salesperson_name, $reference, $invoice_no, 
            $item_id, $item_description, $uom, $product_id, $quantity, $GL_account, 
            $unit_price, $total_sales, $total_profit, $branch_id, 
            $customer_id, $customer_name, $payment_method, $job_id, $section
        );

        // Execute the statement
        if ($stmt->execute()) {
            echo "Sales order inserted successfully!";
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the query: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Sales Order Form</title>
 <!-- Bootstrap CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
 <div class="container my-5">
  <h2 class="text-center">Sales Order Form</h2>
  <form action="insert_sales_order.php" method="POST">
   <div class="mb-3">
    <label for="sales_order_no" class="form-label">Sales Order Number:</label>
    <input type="text" class="form-control" id="sales_order_no" name="sales_order_no" required>
   </div>

   <div class="mb-3">
    <label for="salesperson_id" class="form-label">Salesperson ID:</label>
    <input type="number" class="form-control" id="salesperson_id" name="salesperson_id" required>
   </div>

   <div class="mb-3">
    <label for="salesperson_name" class="form-label">Salesperson Name:</label>
    <input type="text" class="form-control" id="salesperson_name" name="salesperson_name" required>
   </div>

   <div class="mb-3">
    <label for="reference" class="form-label">Reference:</label>
    <input type="text" class="form-control" id="reference" name="reference">
   </div>

   <div class="mb-3">
    <label for="invoice_no" class="form-label">Invoice Number:</label>
    <input type="text" class="form-control" id="invoice_no" name="invoice_no" required>
   </div>

   <div class="mb-3">
    <label for="item_id" class="form-label">Item ID:</label>
    <input type="number" class="form-control" id="item_id" name="item_id" required>
   </div>

   <div class="mb-3">
    <label for="item_description" class="form-label">Item Description:</label>
    <input type="text" class="form-control" id="item_description" name="item_description" required>
   </div>

   <div class="mb-3">
    <label for="uom" class="form-label">Unit of Measure:</label>
    <input type="text" class="form-control" id="uom" name="uom" required>
   </div>

   <div class="mb-3">
    <label for="product_id" class="form-label">Product ID:</label>
    <input type="number" class="form-control" id="product_id" name="product_id" required>
   </div>

   <div class="mb-3">
    <label for="quantity" class="form-label">Quantity:</label>
    <input type="number" class="form-control" id="quantity" name="quantity" required>
   </div>

   <div class="mb-3">
    <label for="GL_account" class="form-label">GL Account:</label>
    <input type="number" class="form-control" id="GL_account" name="GL_account" required>
   </div>

   <div class="mb-3">
    <label for="unit_price" class="form-label">Unit Price:</label>
    <input type="number" class="form-control" id="unit_price" name="unit_price" required>
   </div>

   <div class="mb-3">
    <label for="branch_id" class="form-label">Branch ID:</label>
    <input type="number" class="form-control" id="branch_id" name="branch_id" required>
   </div>

   <div class="mb-3">
    <label for="customer_id" class="form-label">Customer ID:</label>
    <input type="number" class="form-control" id="customer_id" name="customer_id" required>
   </div>

   <div class="mb-3">
    <label for="customer_name" class="form-label">Customer Name:</label>
    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
   </div>

   <div class="mb-3">
    <label for="payment_method" class="form-label">Payment Method:</label>
    <input type="text" class="form-control" id="payment_method" name="payment_method" required>
   </div>

   <div class="mb-3">
    <label for="job_id" class="form-label">Job ID:</label>
    <input type="number" class="form-control" id="job_id" name="job_id" required>
   </div>

   <div class="mb-3">
    <label for="section" class="form-label">Section:</label>
    <input type="text" class="form-control" id="section" name="section" required>
   </div>

   <div class="text-center">
    <button type="submit" class="btn btn-primary">Submit Sales Order</button>
   </div>
  </form>
 </div>

 <!-- Bootstrap JS and Popper -->
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>

kkkkkkkkkkkkkkk

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

// Fetch Sales Order Details
$sales_order_no = $_GET['sales_order_no']; // Sales order number from the URL
$sql = "SELECT * FROM sales_orders WHERE sales_order_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sales_order_no);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

// VAT Calculation
$vat_rate = 0.15; // 15% VAT
$total_sales = $order['total_sales'];
$vat_amount = $total_sales * $vat_rate;
$total_with_vat = $total_sales + $vat_amount;

// Close connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Sales Order Summary</title>
 <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
 <style>
 .summary-table {
  margin-top: 20px;
  width: 100%;
 }

 .summary-table td,
 .summary-table th {
  padding: 10px;
  text-align: left;
 }

 .summary-table th {
  background-color: #f8f9fa;
 }

 .total-row {
  font-weight: bold;
  background-color: #f8f9fa;
 }

 .vat-row {
  background-color: #f1f1f1;
 }

 .order-summary {
  margin-top: 30px;
 }

 .order-summary h3 {
  color: #28a745;
  font-size: 1.8em;
 }

 .order-summary .total {
  color: #ff5722;
  font-size: 1.5em;
 }

 .btn-export {
  margin-top: 30px;
  margin-bottom: 20px;
 }
 </style>
</head>

<body>
 <div class="container">
  <h2 class="my-4">Sales Order Summary</h2>

  <!-- Sales Order Info -->
  <div class="row">
   <div class="col-12 col-md-6">
    <h4>Order Information</h4>
    <table class="summary-table">
     <tr>
      <th>Sales Order No</th>
      <td><?php echo $order['sales_order_no']; ?></td>
     </tr>
     <tr>
      <th>Salesperson</th>
      <td><?php echo $order['salesperson_name']; ?></td>
     </tr>
     <tr>
      <th>Reference</th>
      <td><?php echo $order['reference']; ?></td>
     </tr>
     <tr>
      <th>Invoice No</th>
      <td><?php echo $order['invoice_no']; ?></td>
     </tr>
     <tr>
      <th>Customer Name</th>
      <td><?php echo $order['customer_name']; ?></td>
     </tr>
     <tr>
      <th>Customer ID</th>
      <td><?php echo $order['customer_id']; ?></td>
     </tr>
    </table>
   </div>

   <div class="col-12 col-md-6">
    <h4>Payment Information</h4>
    <table class="summary-table">
     <tr>
      <th>Payment Method</th>
      <td><?php echo $order['payment_method']; ?></td>
     </tr>
     <tr>
      <th>Job ID</th>
      <td><?php echo $order['job_id']; ?></td>
     </tr>
     <tr>
      <th>Section</th>
      <td><?php echo $order['section']; ?></td>
     </tr>
     <tr>
      <th>Branch</th>
      <td><?php echo $order['branch_id']; ?></td>
     </tr>
    </table>
   </div>
  </div>

  <!-- Item Information -->
  <div class="row">
   <div class="col-12">
    <h4>Item Details</h4>
    <table class="summary-table">
     <tr>
      <th>Item Description</th>
      <th>UOM</th>
      <th>Quantity</th>
      <th>Unit Price</th>
      <th>Total Sales</th>
     </tr>
     <tr>
      <td><?php echo $order['item_description']; ?></td>
      <td><?php echo $order['uom']; ?></td>
      <td><?php echo $order['quantity']; ?></td>
      <td><?php echo number_format($order['unit_price'], 2); ?></td>
      <td><?php echo number_format($total_sales, 2); ?></td>
     </tr>
    </table>
   </div>
  </div>

  <!-- VAT and Total -->
  <div class="order-summary">
   <h3>Order Summary</h3>
   <table class="summary-table">
    <tr class="vat-row">
     <th>VAT (15%)</th>
     <td><?php echo number_format($vat_amount, 2); ?></td>
    </tr>
    <tr class="total-row">
     <th>Total Amount (including VAT)</th>
     <td class="total"><?php echo number_format($total_with_vat, 2); ?></td>
    </tr>
   </table>
  </div>

  <!-- Export Buttons -->
  <div class="btn-export">
   <a href="export_excel.php?sales_order_no=<?php echo $sales_order_no; ?>" class="btn btn-success">Export to Excel</a>
   <a href="export_word.php?sales_order_no=<?php echo $sales_order_no; ?>" class="btn btn-info">Export to Word</a>
   <a href="export_pdf.php?sales_order_no=<?php echo $sales_order_no; ?>" class="btn btn-danger">Export to PDF</a>
   <button onclick="window.print();" class="btn btn-warning">Print</button>
  </div>
 </div>

 <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.0/dist/umd/popper.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>



kkkkkkkkkkkkkkkkkkkkk



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

// Include database connection
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $sales_order_no = $_POST['sales_order_no'];
    $salesperson_id = $_POST['salesperson_id'];
    $salesperson_name = $_POST['salesperson_name'];
    $reference = $_POST['reference'];
    $invoice_no = $_POST['invoice_no'];
    $item_id = $_POST['item_id'];
    $item_description = $_POST['item_description'];
    $uom = $_POST['uom'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $GL_account = $_POST['GL_account'];
    $unit_price = $_POST['unit_price'];
    $branch_id = $_POST['branch_id'];
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];
    $payment_method = $_POST['payment_method'];
    $job_id = $_POST['job_id'];
    $section = $_POST['section'];

    // Calculate total sales and profit
    $total_sales = $quantity * $unit_price;
    $total_profit = $total_sales * 0.2; // Assuming 20% profit margin

    // SQL query to insert sales order data
    $sql = "INSERT INTO sales_orders (
                sales_order_no, salesperson_id, salesperson_name, reference, invoice_no, 
                item_id, item_description, uom, product_id, quantity, GL_account, 
                unit_price, total_sales, total_profit, date, branch_id, 
                customer_id, customer_name, payment_method, job_id, section
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("sisssssssisdddsisssss", 
            $sales_order_no, $salesperson_id, $salesperson_name, $reference, $invoice_no, 
            $item_id, $item_description, $uom, $product_id, $quantity, $GL_account, 
            $unit_price, $total_sales, $total_profit, $branch_id, 
            $customer_id, $customer_name, $payment_method, $job_id, $section
        );

        // Execute the statement
        if ($stmt->execute()) {
            echo "Sales order inserted successfully!";
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the query: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Sales Order Form</title>
 <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
 <div class="container">
  <h2 class="my-4">Sales Order Form</h2>
  <form action="insert_sales_order.php" method="POST">
   <!-- Sales order form fields go here -->
   <div class="form-group">
    <label for="sales_order_no">Sales Order Number:</label>
    <input type="text" class="form-control" id="sales_order_no" name="sales_order_no" required>
   </div>

   <!-- Add other fields here -->

   <button type="submit" class="btn btn-primary">Submit Sales Order</button>
  </form>

  <br>

  <!-- Export buttons -->
  <h3>Export Options</h3>
  <a href="export_excel.php" class="btn btn-success">Export to Excel</a>
  <a href="export_word.php" class="btn btn-info">Export to Word</a>
  <a href="export_pdf.php" class="btn btn-danger">Export to PDF</a>
  <button onclick="window.print();" class="btn btn-warning">Print</button>
 </div>

 <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.0/dist/umd/popper.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>