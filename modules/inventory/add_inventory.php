<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

// Authentication check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// CSRF protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF token check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    // Sanitize input
    $order_no = $_POST['purchase_order_no'] ?? '';
    $invoice_no = $_POST['purchase_invoice_no'] ?? '';
    $reference = $_POST['reference'] ?? '';
    $invoice_date = $_POST['invoice_date'] ?? '';
    $vendor_id = $_POST['vendor_id'] ?? '';
    $vendor_company_name = $_POST['vendor_company_name'] ?? '';
    $vendor_name = $_POST['vendor_name'] ?? '';
    $vendor_tin_no = $_POST['vendor_tin_no'] ?? '';
    $vendor_phone = $_POST['vendor_phone'] ?? '';
    $purchaser_id = $_POST['purchase_person_id'] ?? '';
    $purchaser_name = $_POST['purchase_person_name'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $created_by = $_SESSION['user_id'];

    // Items arrays
    $item_ids = $_POST['item_id'] ?? [];
    $descriptions = $_POST['description'] ?? [];
    $uoms = $_POST['uom'] ?? [];
    $qtys = $_POST['qty'] ?? [];
    $unit_prices = $_POST['unit_price'] ?? [];

    // Calculate totals
    $total_before_vat = 0;
    for ($i = 0; $i < count($qtys); $i++) {
        $total_before_vat += (float)$qtys[$i] * (float)$unit_prices[$i];
    }
    $vat = $total_before_vat * 0.15; // 15% VAT
    $total_after_vat = $total_before_vat + $vat;

    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO inventory
            (order_no, invoice_no, reference, invoice_date,
            vendor_id, vendor_company_name, vendor_name, vendor_tin_no, vendor_telephone,
            purchaser_id, purchaser_name, payment_method,
            total_before_vat, vat, total_after_vat, created_by, created_at,
            item_id, description, uom, qty, unit_price)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)");

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        // Loop through each item and insert
        for ($i = 0; $i < count($item_ids); $i++) {
            $stmt->bind_param(
                "ssssssssssssdddisssdd",
                $order_no,
                $invoice_no,
                $reference,
                $invoice_date,
                $vendor_id,
                $vendor_company_name,
                $vendor_name,
                $vendor_tin_no,
                $vendor_phone,
                $purchaser_id,
                $purchaser_name,
                $payment_method,
                $total_before_vat,
                $vat,
                $total_after_vat,
                $created_by,
                $item_ids[$i],
                $descriptions[$i],
                $uoms[$i],
                $qtys[$i],
                $unit_prices[$i]
            );
            $stmt->execute();
        }

        $conn->commit();
        $_SESSION['success'] = "Invoice saved successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error saving invoice: " . $e->getMessage();
    }

    header("Location: add_inventory.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Receive/Purchase Inventory Invoice Entry</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
 <style>
 body {
  background-color: #f0f2f5;
 }

 @media print {
  .no-print {
   display: none !important;
  }
 }

 .invoice-container {
  background: #fff;
  padding: 25px;
  border-radius: 10px;
  max-width: 950px;
  width: 100%;
 }

 .invoice-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 2px solid #ccc;
  padding-bottom: 15px;
  margin-bottom: 25px;
 }

 .invoice-logo {
  height: 60px;
  width: auto;
 }

 .form-control,
 .form-select {
  font-size: 12px;
  padding: 6px 10px;
 }

 button[type="submit"] {
  padding: 8px 20px;
  font-size: 14px;
 }

 .table td input {
  width: 100%;
  font-size: 13px;
 }

    /* Set UOM column width only */
    th.uom-column, td.uom-column {
        width: 95px; /* adjust this value as needed */
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    table th, table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
 </style>
</head>

<body>
 <div class="d-flex justify-content-center align-items-center min-vh-100 py-4">
  <div class="invoice-container shadow-sm">
   <!-- Invoice Header -->
   <div class="invoice-header d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
    <div>
     <h4 class="mb-0">ABC Company</h4>
     <small>Bole, Addis Ababa | Tel: +251 912 345 678</small>
    </div>
    <img src="assets/images/child drinking.jpeg" alt="Company Logo" class="invoice-logo" style="height: 60px;">
   </div>

   <!-- Display success/error messages -->
   <?php if (isset($_SESSION['success'])): ?>
   <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
   <?php endif; ?>
   <?php if (isset($_SESSION['error'])): ?>
   <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
   <?php endif; ?>

<!--<button type="reset" class="btn btn-danger" onclick="clearForm()">🗑️ Clear Data</button>
 <button type="submit" name="save_invoice" class="btn btn-success">💾 Save Invoice</button>-->
   <!-- Action Buttons -->
   <div class="d-flex flex-wrap gap-2 mb-3">
    <button type="button" class="btn btn-info text-white" onclick="showInvoiceLists()">📂 Show Invoice Lists</button>
    
    <button type="button" class="btn btn-secondary no-print" onclick="printFullInvoice()">🖨️ Print</button>
    <button type="button" onclick="window.location.href='manage_inventory.php'" class="btn btn-danger">Close</button>
   
   </div>

   <!-- Invoice Form Container -->
   <div id="invoice-form" class="invoice-container">
    <!-- Form Start -->
    <form method="POST" enctype="multipart/form-data">
     <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

     <!-- Basic Information -->
     <div class="row g-2 mb-2">
      <div class="col-md-3"><input type="text" name="purchase_order_no" class="form-control" placeholder="Order No"
        required></div>
      <div class="col-md-3"><input type="text" name="purchase_invoice_no" class="form-control" placeholder="Invoice No"
        required></div>
      <div class="col-md-3"><input type="text" name="reference" class="form-control" placeholder="Reference" required>
      </div>
      <div class="col-md-3"><input type="date" name="invoice_date" class="form-control" required></div>
     </div>

     <div class="row g-2 mb-2">
      <div class="col-md-3"><input type="text" name="vendor_id" class="form-control" placeholder="Vendor ID" required>
      </div>
      <div class="col-md-3"><input type="text" name="vendor_company_name" class="form-control"
        placeholder="Company Name" required></div>
      <div class="col-md-3"><input type="text" name="vendor_name" class="form-control" placeholder="Vendor Name"
        required></div>
      <div class="col-md-3"><input type="text" name="vendor_tin_no" class="form-control" placeholder="TIN No" required>
      </div>
     </div>

     <div class="row g-2 mb-2">
      <div class="col-md-3"><input type="text" name="vendor_phone" class="form-control" placeholder="Phone" required>
      </div>
      <div class="col-md-3"><input type="text" name="purchaser_id" class="form-control" placeholder="Purchaser ID" required></div>
      <div class="col-md-3"><input type="text" name="purchaser_name" class="form-control" placeholder="Purchaser Name" required></div>
      <div class="col-md-3">
       <select class="form-select" name="payment_method" required>
        <option value="">Payment Method</option>
        <option value="Cash">Cash</option>
        <option value="Cheque">Cheque</option>
        <option value="Bank Transfer">Bank Transfer</option>
       </select>
      </div>
     </div>

     <!-- Items Purchased -->
     <h6 class="mt-4">Items Purchased</h6>
     <table class="table table-bordered table-sm" id="itemTable">
      <thead class="table-light">
       <tr>
         <th class="uom-column">Item ID</th>
        <th>Description</th>
         <th class="uom-column">UOM</th>
          <th class="uom-column">Qty</th>
      
         <th class="uom-column">Unit Price</th>
       
        <th>Subtotal</th>
        <th><button type="button" class="btn btn-sm btn-success" onclick="addRow()">+</button></th>
       </tr>
      </thead>
      <tbody>
       <tr>
        <td><input type="text" name="item_id[]" class="form-control form-control-sm" required></td>
        <td><input type="text" name="description[]" class="form-control form-control-sm" required></td>
         <td><input type="text" name="uom[]" class="form-control form-control-sm" required></td>
        <td><input type="number" name="qty[]" class="form-control form-control-sm qty" oninput="updateTotals()"
          required></td>
        <td><input type="number" name="unit_price[]" class="form-control form-control-sm unit-price"
          oninput="updateTotals()" required></td>
        <td><input type="text" name="subtotal[]" class="form-control form-control-sm subtotal" readonly></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">-</button></td>
       </tr>
      </tbody>
     </table>

     <!-- Summary Section -->
     <div class="row g-2 mt-2">
      <div class="col-md-4 offset-md-8">
       <table class="table table-borderless table-sm">
        <tr>
         <td class="text-end">Total:</td>
         <td><input type="text" id="total" name="total" class="form-control form-control-sm text-end" readonly></td>
        </tr>
        <tr>
         <td class="text-end">VAT (15%):</td>
         <td><input type="text" id="vat" name="vat" class="form-control form-control-sm text-end" readonly></td>
        </tr>
        <tr>
         <td class="text-end fw-bold">Grand Total:</td>
         <td><input type="text" id="grandTotal" name="grand_total" class="form-control form-control-sm text-end fw-bold"
           readonly></td>
        </tr>
       </table>
       <label class="form-label">Amount in Words:</label>
       <textarea id="amountInWords" class="form-control form-control-sm" rows="2" readonly></textarea>
      </div>
     </div>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

    <!-- all your inputs -->

    <div class="d-flex justify-content-end gap-2 mt-4">
        <button type="reset" class="btn btn-danger">🗑️ Clear</button>
        <button type="submit" name="save_invoice" class="btn btn-success">
            💾 Save Invoice
        </button>
    </div>
</form>


    </form>

    <!-- Signature Section -->
    <div class="row mt-4">
     <div class="col-md-6 text-center">
      <p class="mt-1"><u>___________________________</u><br><strong>Prepared By</strong></p>
     </div>
     <div class="col-md-6 text-center">
      <p class="mt-1"><u>___________________________</u><br><strong>Approved By</strong></p>
     </div>
    </div>
   </div>
  </div>
 </div>

 <script>
 function updateTotals() {
  let rows = document.querySelectorAll('#itemTable tbody tr');
  let total = 0;

  rows.forEach(row => {
   const qty = parseFloat(row.querySelector('[name="qty[]"]').value) || 0;
   const price = parseFloat(row.querySelector('[name="unit_price[]"]').value) || 0;
   const subtotal = qty * price;
   row.querySelector('[name="subtotal[]"]').value = subtotal.toFixed(2);
   total += subtotal;
  });

  const vat = total * 0.15;
  const grandTotal = total + vat;

  document.getElementById('total').value = total.toFixed(2);
  document.getElementById('vat').value = vat.toFixed(2);
  document.getElementById('grandTotal').value = grandTotal.toFixed(2);
  document.getElementById('amountInWords').value = convertToWords(grandTotal);
 }

 function addRow() {
  const table = document.querySelector('#itemTable tbody');
  const newRow = table.rows[0].cloneNode(true);
  newRow.querySelectorAll('input').forEach(input => input.value = '');
  table.appendChild(newRow);
 }

 function removeRow(btn) {
  const row = btn.closest('tr');
  const table = document.querySelector('#itemTable tbody');
  if (table.rows.length > 1) row.remove();
  updateTotals();
 }

 function clearForm() {
  if (confirm("Clear all entered data?")) {
   document.querySelector("form").reset();
   const rows = document.querySelectorAll("#itemTable tbody tr");
   rows.forEach((row, index) => index > 0 && row.remove());
   updateTotals();
  }
 }

 function printFullInvoice() {
  window.print();
 }

 function showInvoiceLists() {
  window.location.href = "invoice_lists.php";
 }

 function convertToWords(amount) {
  const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
   'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
  ];
  const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

  function numToWords(n) {
   if (n === 0) return '';
   if (n < 20) return ones[n];
   if (n < 100) return tens[Math.floor(n / 10)] + (n % 10 ? ' ' + ones[n % 10] : '');
   if (n < 1000) return ones[Math.floor(n / 100)] + ' Hundred' + (n % 100 ? ' and ' + numToWords(n % 100) : '');
   if (n < 1000000) return numToWords(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 ? ' ' + numToWords(n % 1000) :
    '');
   if (n < 1000000000) return numToWords(Math.floor(n / 1000000)) + ' Million' + (n % 1000000 ? ' ' + numToWords(n %
    1000000) : '');
   if (n < 1000000000000) return numToWords(Math.floor(n / 1000000000)) + ' Billion' + (n % 1000000000 ? ' ' +
    numToWords(n % 1000000000) : '');
   return 'Amount too large'; // Above a trillion
  }

  amount = parseFloat(amount);
  if (isNaN(amount)) return 'Invalid amount';

  const parts = amount.toFixed(2).split('.');
  const birr = parseInt(parts[0]);
  const cents = parseInt(parts[1]);

  let words = '';
  if (birr > 0) words += numToWords(birr) + ' Birr';
  if (cents > 0) words += ' and ' + numToWords(cents) + ' Cents';
  if (words === '') words = 'Zero Birr';

  return words.charAt(0).toUpperCase() + words.slice(1) + ' only';
 }
 </script>
</body>

</html>

