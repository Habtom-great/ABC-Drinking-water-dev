<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abc_company";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$companyName = $_POST['Company_Name'] ?? '';
$address = $_POST['Address'] ?? '';
$date = $_POST['Date'] ?? '';
$invoiceNo = $_POST['Invoice_No'] ?? '';
$vat = is_numeric($_POST['VAT'] ?? null) ? floatval($_POST['VAT']) : 0;
$withhold = is_numeric($_POST['Withhold'] ?? null) ? floatval($_POST['Withhold']) : 0;
$subTotal = is_numeric($_POST['Sub_Total'] ?? null) ? floatval($_POST['Sub_Total']) : 0;
$netTotal = is_numeric($_POST['Net_Total'] ?? null) ? floatval($_POST['Net_Total']) : 0;
$netTotalWords = $_POST['Net_Total_in_Words'] ?? '';

// Calculate VAT amount
$vatAmount = $subTotal * ($vat / 100);

// Format numbers
$subTotalFormatted = number_format($subTotal, 2);
$vatAmountFormatted = number_format($vatAmount, 2);
$withholdFormatted = number_format($withhold, 2);
$netTotalFormatted = number_format($netTotal, 2);

// Fetch items
$items = [];
if (isset($_POST['Items']) && is_array($_POST['Items'])) {
    foreach ($_POST['Items'] as $item) {
        $items[] = [
            'Item ID' => $item['Item_ID'] ?? '',
            'Description' => $item['Description'] ?? '',
            'UoM' => $item['UoM'] ?? '',
            'Quantity' => is_numeric($item['Quantity'] ?? null) ? floatval($item['Quantity']) : 0,
            'Unit Cost' => is_numeric($item['Unit_Cost'] ?? null) ? floatval($item['Unit_Cost']) : 0,
            'Total Cost' => is_numeric($item['Total_Cost'] ?? null) ? floatval($item['Total_Cost']) : 0,
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Purchase Invoice</title>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
 <style>
 body {
  background-color: #f9f9f9;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
 }

 .container {
  max-width: 800px;
  margin: 20px auto;
  background: #fff;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
 }

 h1,
 h2 {
  text-align: center;
  margin-bottom: 15px;
 }

 table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 15px;
 }

 table th,
 table td {
  border: 1px solid #dee2e6;
  padding: 8px;
  text-align: center;
  font-size: 12px;
 }

 table th {
  background-color: #f8f9fa;
 }

 .summary p {
  font-size: 14px;
 }

 .summary strong {
  color: #007bff;
 }

 .signature {
  display: flex;
  justify-content: space-between;
  margin-top: 30px;
  font-size: 13px;
 }

 .signature div {
  width: 30%;
  text-align: center;
 }

 .signature p {
  margin: 0;
 }
 </style>
</head>

<body>
 <div class="container">
  <h1>Purchase Invoice</h1>

  <h2>Company Information</h2>
  <p><strong>Company Name:</strong> <?= htmlspecialchars($companyName) ?></p>
  <p><strong>Address:</strong> <?= htmlspecialchars($address) ?></p>
  <p><strong>Date:</strong> <?= htmlspecialchars($date) ?></p>
  <p><strong>Invoice No:</strong> <?= htmlspecialchars($invoiceNo) ?></p>

  <h2>Items</h2>
  <table class="table table-bordered">
   <thead>
    <tr>
     <th>Item ID</th>
     <th>Description</th>
     <th>UoM</th>
     <th>Quantity</th>
     <th>Unit Cost</th>
     <th>Total Cost</th>
    </tr>
   </thead>
   <tbody>
    <?php if (!empty($items)): ?>
    <?php foreach ($items as $item): ?>
    <tr>
     <td><?= htmlspecialchars($item['Item ID']) ?></td>
     <td><?= htmlspecialchars($item['Description']) ?></td>
     <td><?= htmlspecialchars($item['UoM']) ?></td>
     <td><?= number_format($item['Quantity'], 2) ?></td>
     <td>$<?= number_format($item['Unit Cost'], 2) ?></td>
     <td>$<?= number_format($item['Total Cost'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
     <td colspan="6">No items found.</td>
    </tr>
    <?php endif; ?>
   </tbody>
  </table>

  <h2>Summary</h2>
  <div class="summary">
   <p><strong>Subtotal:</strong> $<?= $subTotalFormatted ?></p>
   <p><strong>VAT (<?= $vat ?>%):</strong> $<?= $vatAmountFormatted ?></p>
   <p><strong>Withhold:</strong> $<?= $withholdFormatted ?></p>
   <p><strong>Net Total:</strong> $<?= $netTotalFormatted ?> (<?= htmlspecialchars($netTotalWords) ?>)</p>
  </div>

  <div class="signature">
   <div>
    <p>Prepared By:</p>
    <p>____________________</p>
   </div>
   <div>
    <p>Checked By:</p>
    <p>____________________</p>
   </div>
   <div>
    <p>Approved By:</p>
    <p>____________________</p>
   </div>
  </div>
 </div>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Add Purchase</title>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
 <style>
 body {
  background-color: #f4f4f9;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
 }

 .container {
  max-width: 700px;
  margin: 20px auto;
  background: #fff;
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
 }

 h2 {
  text-align: center;
  margin-bottom: 25px;
 }

 .form-label {
  font-size: 12px;
 }

 .form-control,
 .form-select {
  font-size: 13px;
  height: 35px;
 }

 .row .col-md-2 {
  padding-bottom: 15px;
 }

 .table-responsive {
  margin-top: 15px;
 }

 .form-control::placeholder {
  font-size: 12px;
 }
 </style>
</head>

<body>
 <div class="container">
  <h2>Add Purchase</h2>
  <form id="purchaseForm" action="process_purchase.php" method="POST">
   <div class="row mb-3">
    <div class="col-md-4">
     <label for="supplierName" class="form-label">Supplier Name</label>
     <select id="supplierName" name="supplierName" class="form-select">
      <option>Select Supplier</option>
      <option>Supplier A</option>
      <option>Supplier B</option>
     </select>
    </div>
    <div class="col-md-4">
     <label for="purchaseDate" class="form-label">Purchase Date</label>
     <input type="date" id="