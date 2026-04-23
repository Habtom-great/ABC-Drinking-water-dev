<?php
include 'header.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abc_company";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Initialize form data
$formData = [
    'sales_order_no' => '', 'sales_invoice_no' => '', 'reference' => '', 'invoiceDate' => '',
    'customer_id' => '', 'customer_name' => '', 'branch_id' => '', 'branch_name' => '',
    'salesperson_id' => '', 'salesperson_name' => '', 'job_id' => '', 'payment_method' => '',
    'items' => [], 'total_sales_before_vat' => '', 'vat' => '', 'grand_total' => ''
];

$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_invoice'])) {
    foreach ($formData as $key => $value) {
        if ($key !== 'items') {
            $formData[$key] = filter_var($_POST[$key] ?? '', FILTER_SANITIZE_STRING);
        }
    }
    $formData['total_sales_before_vat'] = filter_var($_POST['total_sales_before_vat'] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $formData['vat'] = filter_var($_POST['vat'] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $formData['grand_total'] = filter_var($_POST['grand_total'] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $items = [];
    if (isset($_POST['item_id'])) {
        foreach ($_POST['item_id'] as $i => $itemId) {
            $items[] = [
                'item_id' => filter_var($itemId, FILTER_SANITIZE_STRING),
                'item_description' => filter_var($_POST['item_description'][$i] ?? '', FILTER_SANITIZE_STRING),
                'category' => filter_var($_POST['category'][$i] ?? '', FILTER_SANITIZE_STRING),
                'uom' => filter_var($_POST['uom'][$i] ?? '', FILTER_SANITIZE_STRING),
                'quantity' => filter_var($_POST['quantity'][$i] ?? '', FILTER_SANITIZE_NUMBER_INT),
                'GL_account' => filter_var($_POST['GL_account'][$i] ?? '', FILTER_SANITIZE_STRING),
                'unit_price' => filter_var($_POST['unit_price'][$i] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'total_price' => filter_var($_POST['total_price'][$i] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            ];
        }
    }
    $formData['items'] = json_encode($items);

    $stmt = $conn->prepare("INSERT INTO sales (
        sales_order_no, invoice_no, reference, date,
        customer_id, customer_name, branch_id, branch_name,
        salesperson_id, salesperson_name, job_id, payment_method,
        total_sales_before_vat, vat, total_sales_after_vat, items
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssssssssssssddds",
            $formData['sales_order_no'], $formData['sales_invoice_no'], $formData['reference'], $formData['invoiceDate'],
            $formData['customer_id'], $formData['customer_name'], $formData['branch_id'], $formData['branch_name'],
            $formData['salesperson_id'], $formData['salesperson_name'], $formData['job_id'], $formData['payment_method'],
            $formData['total_sales_before_vat'], $formData['vat'], $formData['grand_total'], $formData['items']
        );

        if ($stmt->execute()) {
            $successMessage = "✅ Invoice saved successfully!";
            foreach ($formData as &$val) {
                $val = ($val === $formData['items']) ? [] : '';
            }
        } else {
            $successMessage = "❌ Error saving invoice: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $successMessage = "❌ SQL Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <title>Sales Invoice</title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
 <style>
 body {
  font-family: 'Segoe UI', sans-serif;
 }

 .logo-header {
  text-align: center;
  margin-bottom: 20px;
 }

 .logo-header img {
  height: 80px;
 }

 .logo-header h4 {
  margin: 0;
  font-weight: bold;
 }

 .invoice-box {
  border: 1px solid #ddd;
  padding: 20px;
  background: #f9f9f9;
 }
 </style>
</head>
<div class="alert alert-info"> <?= $successMessage ?> </div>

<body>
 <div class="container mt-4">
  <div class="logo-header">
   <img src="assets/images/1.png" alt="Company Logo">
   <h4>ABC Company PLC</h4>
   <p>Address: Bole Road, Addis Ababa | Phone: +251-11-123-4567</p>
  </div>
  <div class="invoice-box">
   <h2 class="text-center">Sales Invoice</h2>
   <?php if (!empty($successMessage)) : ?>
   <?php endif; ?>

   <form method="POST">
    <div class="text-center mt-4">
     <button type="submit" name="save_invoice" class="btn btn-success px-5">💾 Save Invoice</button>
     <button type="reset" class="btn btn-secondary px-5">🗑️ Clear</button>
    </div>
  </div>

  <!-- Items section and totals would be inserted here dynamically with JS or PHP -->

  <!-- Sales General Information -->
  <div class="row">
   <div class="col-md-3">
    <label class="form-label">Order No.</label>
    <input type="text"
     class="form-control <?php echo (!empty($error) && empty($formData['sales_order_no']) ? 'is-invalid' : ''); ?>"
     name="sales_order_no" placeholder="Enter order number"
     value="<?php echo htmlspecialchars($formData['sales_order_no']); ?>" required>
   </div>
   <div class="col-md-3">
    <label class="form-label">Invoice No.</label>
    <input type="text"
     class="form-control <?php echo (!empty($error) && empty($formData['sales_invoice_no']) ? 'is-invalid' : ''); ?>"
     name="sales_invoice_no" placeholder="Enter invoice number"
     value="<?php echo htmlspecialchars($formData['sales_invoice_no']); ?>" required>
   </div>
   <div class="col-md-3">
    <label class="form-label">Reference</label>
    <input type="text" class="form-control" name="reference" placeholder="Enter reference"
     value="<?php echo htmlspecialchars($formData['reference']); ?>">
   </div>
   <div class="col-md-3">
    <label class="form-label">Invoice Date</label>
    <input type="date"
     class="form-control <?php echo (!empty($error) && empty($formData['invoiceDate']) ? 'is-invalid' : ''); ?>"
     name="invoiceDate" value="<?php echo htmlspecialchars($formData['invoiceDate']); ?>" required>
   </div>
  </div>

  <!-- Customer Information -->
  <div class="row mt-3">
   <div class="col-md-3">
    <label class="form-label">Customer ID</label>
    <input type="text"
     class="form-control <?php echo (!empty($error) && empty($formData['customer_id']) ? 'is-invalid' : ''); ?>"
     name="customer_id" placeholder="Enter customer ID"
     value="<?php echo htmlspecialchars($formData['customer_id']); ?>" required>
   </div>
   <div class="col-md-3">
    <label class="form-label">Customer Name</label>
    <input type="text"
     class="form-control <?php echo (!empty($error) && empty($formData['customer_name']) ? 'is-invalid' : ''); ?>"
     name="customer_name" placeholder="Enter customer name"
     value="<?php echo htmlspecialchars($formData['customer_name']); ?>" required>
   </div>
   <div class="col-md-3">
    <label class="form-label">Branch ID</label>
    <input type="text" class="form-control" name="branch_id" placeholder="Enter branch ID"
     value="<?php echo htmlspecialchars($formData['branch_id']); ?>">
   </div>
   <div class="col-md-3">
    <label class="form-label">Branch Name</label>
    <input type="text" class="form-control" name="branch_name" placeholder="Enter branch name"
     value="<?php echo htmlspecialchars($formData['branch_name']); ?>">
   </div>
  </div>

  <!-- Salesperson & Payment Information -->
  <div class="row mt-3">
   <div class="col-md-3">
    <label class="form-label">Salesperson ID</label>
    <input type="text"
     class="form-control <?php echo (!empty($error) && empty($formData['salesperson_id']) ? 'is-invalid' : ''); ?>"
     name="salesperson_id" placeholder="Enter salesperson ID"
     value="<?php echo htmlspecialchars($formData['salesperson_id']); ?>" required>
   </div>
   <div class="col-md-3">
    <label class="form-label">Salesperson Name</label>
    <input type="text"
     class="form-control <?php echo (!empty($error) && empty($formData['salesperson_name']) ? 'is-invalid' : ''); ?>"
     name="salesperson_name" placeholder="Enter salesperson name"
     value="<?php echo htmlspecialchars($formData['salesperson_name']); ?>" required>
   </div>

   <div class="col-md-3">
    <label class="form-label">Job ID</label>
    <input type="text" class="form-control" name="job_id" placeholder="Enter job ID"
     value="<?php echo htmlspecialchars($formData['job_id']); ?>">
   </div>
   <div class="col-md-3">
    <label class="form-label">Payment Method</label>
    <select
     class="form-control <?php echo (!empty($error) && empty($formData['payment_method']) ? 'is-invalid' : ''); ?>"
     name="payment_method" required>
     <option value="">Select Payment Method</option>
     <option value="cash" <?php echo ($formData['payment_method'] === 'cash') ? 'selected' : ''; ?>>Cash</option>
     <option value="cheque" <?php echo ($formData['payment_method'] === 'cheque') ? 'selected' : ''; ?>>Cheque
     </option>
     <option value="bank" <?php echo ($formData['payment_method'] === 'bank') ? 'selected' : ''; ?>>Bank Transfer
     </option>
     <option value="other" <?php echo ($formData['payment_method'] === 'other') ? 'selected' : ''; ?>>Other
     </option>
    </select>
   </div>
  </div>

  <!-- Items Sold -->
  <h6 class="mt-4">Items Sold</h6>
  <div id="alertSuccess" class="alert alert-success d-none">Inventory added successfully!</div>

  <table class="table table-bordered table-sm" id="itemTable">
   <thead class="table-light">
    <tr>
     <th>Item ID</th>
     <th>Description</th>
     <th>Qty</th>
     <th>Unit Price</th>
     <th>Subtotal</th>
     <th><button type="button" class="btn btn-sm btn-success" onclick="addRow()">+Add Raw</button></th>
    </tr>
   </thead>
   <tbody>
    <tr>
     <td><input type="text" name="item_id[]" class="form-control form-control-sm" required></td>
     <td><input type="text" name="description[]" class="form-control form-control-sm" required></td>
     <td><input type="number" name="qty[]" class="form-control form-control-sm qty" oninput="updateTotals()" required>
     </td>
     <td><input type="number" name="unit_price[]" class="form-control form-control-sm unit-price"
       oninput="updateTotals()" required></td>
     <td><input type="text" name="subtotal[]" class="form-control form-control-sm subtotal" readonly></td>
     <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">-Remove Raw</button></td>
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



 </div>
 </form>
 <!-- ✅ FORM END -->
 </div>

</body>

</html>
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
}

function clearForm() {
 if (confirm("Clear all entered data?")) {
  document.querySelector("form").reset();
  updateTotals();
 }
}

function printFullInvoice() {
 window.print();
}
</script>


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
</form>
</div>


<!-- JavaScript Section -->
<script>
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


<script>
function addRow() {
 const table = document.querySelector('#itemTable tbody');
 const newRow = table.rows[0].cloneNode(true);
 newRow.querySelectorAll('input').forEach(input => input.value = '');
 table.appendChild(newRow);
}

function removeRow(button) {
 const row = button.closest('tr');
 const table = document.querySelector('#itemTable tbody');
 if (table.rows.length > 1) row.remove();
}

function updateTotals() {
 let total = 0;
 document.querySelectorAll('#itemTable tbody tr').forEach(row => {
  const qty = parseFloat(row.querySelector('.qty').value) || 0;
  const price = parseFloat(row.querySelector('.unit-price').value) || 0;
  const subtotal = qty * price;
  row.querySelector('.subtotal').value = formatCurrency(subtotal);
  total += subtotal;
 });

 const vat = total * 0.15;
 const grandTotal = total + vat;

 document.getElementById("total").value = formatCurrency(total);
 document.getElementById("vat").value = formatCurrency(vat);
 document.getElementById("grandTotal").value = formatCurrency(grandTotal);
 document.getElementById("amountInWords").value = convertToWords(grandTotal) + " birr only";
}

function clearForm() {
 document.querySelector("form").reset();
 const rows = document.querySelectorAll("#itemTable tbody tr");
 rows.forEach((row, index) => index > 0 && row.remove());
 updateTotals();
}

function showInvoiceLists() {
 window.location.href = "invoice_lists.php";
}

function printFullInvoice() {
 const content = document.querySelector(".invoice-container").innerHTML;
 const win = window.open('', '', 'width=800,height=600');
 win.document.write(`<html><head><title>Print Invoice</title></head><body>${content}</body></html>`);
 win.document.close();
 win.print();
}

function formatCurrency(amount) {
 return parseFloat(amount).toLocaleString('en-US', {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
 });
}

// Simple number to words converter (for demo purposes)
</script>

<!-- Close button -->
<script>
function closeInvoice(id) {
 const invoiceBox = document.getElementById(id);
 if (invoiceBox) invoiceBox.style.display = 'none';
}
</script>
<div class="text-center mt-4">
 <button type="submit" name="save_invoice" class="btn btn-success px-5">💾 Save Invoice</button>
 <button type="reset" class="btn btn-secondary px-5">🗑️ Clear</button>
</div>
</form>
</div>
</div>
</body>

</html>