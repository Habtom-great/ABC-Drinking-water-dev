<?php
$conn = new mysqli("localhost", "root", "", "abc_company");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$formData = [];
$items = [];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM sales WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $formData = $result->fetch_assoc();
        if (!empty($formData['items'])) {
            $items = json_decode($formData['items'], true);
        }
    } else {
        echo "<h3 style='color:red;'>Failed to fetch invoice data!</h3>";
        exit;
    }
    $stmt->close();
} else {
    echo "<h3 style='color:red;'>Missing sales ID in URL!</h3>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_invoice'])) {
    $order_no = $_POST['sales_order_no'] ?? '';
    $invoice_no = $_POST['sales_invoice_no'] ?? '';
    $reference = $_POST['reference'] ?? '';
    $date = $_POST['date'] ?? '';
    $customer_id = $_POST['customer_id'] ?? '';
    $customer_name = $_POST['customer_name'] ?? '';
    $branch_id = $_POST['branch_id'] ?? '';
    $branch_name = $_POST['branch_name'] ?? '';
    $salesperson_id = $_POST['salesperson_id'] ?? '';
    $salesperson_name = $_POST['salesperson_name'] ?? '';
    $job_id = $_POST['job_id'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';

    $item_ids = $_POST['item_id'] ?? [];
    $item_descs = $_POST['item_description'] ?? [];
    $qtys = $_POST['qty'] ?? [];
    $unit_prices = $_POST['unit_price'] ?? [];

    $items_arr = [];
    $count = min(count($item_ids), count($qtys), count($unit_prices));

    for ($i = 0; $i < $count; $i++) {
        $item_id = trim($item_ids[$i]);
        $description = trim($item_descs[$i] ?? '');
        $qty = floatval($qtys[$i]);
        $unit_price = floatval($unit_prices[$i]);
        $total_before_vat = $qty * $unit_price;

        if ($item_id !== '' && $qty > 0 && $unit_price > 0) {
            $items_arr[] = [
                'item_id' => $item_id,
                'item_description' => $description,
                'qty' => $qty,
                'unit_price' => $unit_price,
                'total_before_vat' => $total_before_vat
            ];
        }
    }

    $items_json = json_encode($items_arr);

    $total_sales_before_vat = array_sum(array_column($items_arr, 'total_before_vat'));
    $vat = round($total_sales_before_vat * 0.15, 2);
    $total_sales_after_vat = round($total_sales_before_vat + $vat, 2);

    $amount_in_words = convertAmountToWords($total_sales_after_vat);

    $update_stmt = $conn->prepare("UPDATE sales SET 
        sales_order_no = ?, invoice_no = ?, reference = ?, date = ?, customer_id = ?, customer_name = ?, 
        branch_id = ?, branch_name = ?, salesperson_id = ?, salesperson_name = ?, job_id = ?, payment_method = ?, items = ?, 
        total_sales_before_vat = ?, vat = ?, total_sales_after_vat = ?, amount_in_words = ? 
        WHERE id = ?");

    if (!$update_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Note: bind_param types: s=string, d=double, i=integer
    // total_sales_before_vat, vat, total_sales_after_vat are doubles (d)
    // amount_in_words is string (s)
    // id is integer (i)
    $update_stmt->bind_param(
        "ssssssssssssssddds",
        $order_no, $invoice_no, $reference, $date, $customer_id, $customer_name,
        $branch_id, $branch_name, $salesperson_id, $salesperson_name, $job_id, $payment_method,
        $items_json, $total_sales_before_vat, $vat, $total_sales_after_vat, $amount_in_words, $id
    );

    if ($update_stmt->execute()) {
        echo "<div class='alert alert-success text-center'>Invoice updated successfully!</div>";
        // Clear form data and items so the form is empty after saving
        $formData = [];
        $items = [];
    } else {
        echo "<div class='alert alert-danger text-center'>Failed to update invoice: " . $conn->error . "</div>";
    }

    $update_stmt->close();
}

$conn->close();

function convertAmountToWords($number) {
    $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    $whole = floor($number);
    $fraction = round(($number - $whole) * 100);

    $words = ucfirst($f->format($whole)) . " Birr";
    if ($fraction > 0) {
        $words .= " and " . ucfirst($f->format($fraction)) . " Cents";
    }
    return $words;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1" />
 <title>Edit Sales Invoice</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
 <style>
 body {
  background-color: #f4f4f9;
 }

 .container {
  max-width: 900px;
 }

 .card {
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  background: white;
 }

 .summary-section {
  background: #f8f9fa;
  padding: 10px;
  text-align: right;
  font-size: 1rem;
  font-weight: bold;
 }

 .amount-in-words {
  font-style: italic;
  color: #007bff;
  font-weight: bold;
 }

 table th,
 table td {
  text-align: center;
  vertical-align: middle;
 }

 .signatures {
  margin-top: 30px;
  display: flex;
  justify-content: space-between;
 }

 .signatures div {
  width: 45%;
  border-top: 1px solid #000;
  padding-top: 5px;
  text-align: center;
  font-weight: bold;
 }
 </style>
</head>

<body>
 <div class="container mt-4">
  <h2 class="mb-4 text-center">Edit Sales Invoice</h2>

  <form id="sales_form" method="POST">
   <!-- Control Buttons -->
   <div class="d-flex flex-wrap gap-2 mb-3">
    <button type="button" class="btn btn-info text-white" onclick="window.location.href='sales_invoice_list.php'">📂
     Show Sales</button>
    <button type="button" class="btn btn-info text-white" onclick="window.location.href='invoice_lists.php'">📂
     back to Sales Invoice lists</button>

    <button type="button" class="btn btn-secondary" onclick="printFullInvoice()">🖨️ Print</button>
    <button type="submit" name="save_invoice" class="btn btn-success">💾 Save Invoice</button>
    <button type="button" onclick="downloadPDF()" class="btn btn-primary">📄 Download PDF</button>
    <button type="button" onclick="window.location.href='admin_dashboard.php'" class="btn btn-dark">Close</button>
   </div>

   <!-- Form Card -->
   <div class="card">

    <!-- Invoice Header Info -->
    <div class="row g-3">
     <div class="col-md-3">
      <label class="form-label">Order No.</label>
      <input type="text" class="form-control" name="sales_order_no"
       value="<?= htmlspecialchars($formData['sales_order_no'] ?? '') ?>" required />
     </div>
     <div class="col-md-3">
      <label class="form-label">Invoice No.</label>
      <input type="text" class="form-control" name="sales_invoice_no"
       value="<?= htmlspecialchars($formData['sales_invoice_no'] ?? '') ?>" required />
     </div>
     <div class="col-md-3">
      <label class="form-label">Reference</label>
      <input type="text" class="form-control" name="reference"
       value="<?= htmlspecialchars($formData['reference'] ?? '') ?>" required />
     </div>
     <div class="col-md-3">
      <label class="form-label">Invoice Date</label>
      <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($formData['date'] ?? '') ?>"
       required />
     </div>
    </div>


    <!-- Customer Info -->
    <div class="row g-3 mt-3">
     <div class="col-md-3">
      <label class="form-label">Customer ID</label>
      <input type="text" class="form-control" name="customer_id"
       value="<?= htmlspecialchars($formData['customer_id'] ?? '') ?>" required />
     </div>
     <div class="col-md-3">
      <label class="form-label">Customer Name</label>
      <input type="text" class="form-control" name="customer_name"
       value="<?= htmlspecialchars($formData['customer_name'] ?? '') ?>" required />
     </div>
     <div class="col-md-3">
      <label class="form-label">Branch ID</label>
      <input type="text" class="form-control" name="branch_id"
       value="<?= htmlspecialchars($formData['branch_id'] ?? '') ?>" required />
     </div>
     <div class="col-md-3">
      <label class="form-label">Branch Name</label>
      <input type="text" class="form-control" name="branch_name"
       value="<?= htmlspecialchars($formData['branch_name'] ?? '') ?>" required />
     </div>
    </div>

    <!-- Salesperson Info -->
    <div class="row g-3 mt-3">
     <div class="col-md-3">
      <label class="form-label">Salesperson ID</label>
      <input type="text" class="form-control" name="salesperson_id"
       value="<?= htmlspecialchars($formData['salesperson_id'] ?? '') ?>" required />
     </div>
     <div class="col-md-3">
      <label class="form-label">Salesperson Name</label>
      <input type="text" class="form-control" name="salesperson_name"
       value="<?= htmlspecialchars($formData['salesperson_name'] ?? '') ?>" required />
     </div>
     <div class="col-md-3">
      <label class="form-label">Job ID</label>
      <input type="text" class="form-control" name="job_id" value="<?= htmlspecialchars($formData['job_id'] ?? '') ?>"
       required />
     </div>
     <div class="col-md-3">
      <label class="form-label">Payment Method</label>
      <input type="text" class="form-control" name="payment_method"
       value="<?= htmlspecialchars($formData['payment_method'] ?? '') ?>" required />
     </div>
    </div>
    <!DOCTYPE html>
    <html lang="en">

    <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Edit Sales Invoice</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>



    <div class="card p-3">

     <!-- Item Table -->
     <div class="table-responsive mt-3">
      <table class="table table-bordered" id="items_table">
       <thead class="table-primary">
        <tr>
         <th>Item ID</th>
         <th>Description</th>
         <th>Qty</th>
         <th>Unit Price</th>
         <th>Total Before VAT</th>
         <th>Action</th>
        </tr>
       </thead>
       <tbody>
        <tr>
         <td><input name="item_id[]" class="form-control" required></td>
         <td><input name="item_description[]" class="form-control" required></td>
         <td><input type="number" name="qty[]" class="form-control qty" min="0" value="0" required></td>
         <td><input type="number" name="unit_price[]" class="form-control unit_price" step="0.01" min="0" value="0.00"
           required></td>
         <td><input type="number" name="total_sales_before_vat[]" class="form-control total_sales_before_vat" readonly
           value="0.00"></td>
         <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
        </tr>
       </tbody>
      </table>
      <button type="button" id="add_row" class="btn btn-primary btn-sm mt-2">Add Item</button>
     </div>

     <!-- Summary Section -->
     <div class="summary-section mt-4">
      <div>Total Before VAT: <span id="total_sales_before_vat">0.00</span></div>
      <div>VAT (15%): <span id="vat_display">0.00</span></div>
      <div>Total After VAT: <span id="total_sales_after_vat">0.00</span></div>
      <div class="amount-in-words">Amount in words: <span id="amount_in_words"></span></div>
     </div>

    </div>
  </form>
 </div>

 <!-- Scripts -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

 <script>
 function calculateTotals() {
  let totalBeforeVAT = 0;

  $('#items_table tbody tr').each(function() {
   const qty = parseFloat($(this).find('.qty').val()) || 0;
   const price = parseFloat($(this).find('.unit_price').val()) || 0;
   const rowTotal = qty * price;
   $(this).find('.total_sales_before_vat').val(rowTotal.toFixed(2));
   totalBeforeVAT += rowTotal;
  });

  const vat = totalBeforeVAT * 0.15;
  const totalAfterVAT = totalBeforeVAT + vat;

  $('#total_sales_before_vat').text(formatNumber(totalBeforeVAT));
  $('#vat_display').text(formatNumber(vat));
  $('#total_sales_after_vat').text(formatNumber(totalAfterVAT));

  $('#amount_in_words').text(numberToWords(Math.floor(totalAfterVAT)) + " Birr");
 }

 function formatNumber(num) {
  return parseFloat(num).toLocaleString('en-US', {
   minimumFractionDigits: 2,
   maximumFractionDigits: 2
  });
 }

 $('#items_table').on('input', '.qty, .unit_price', calculateTotals);

 $('#add_row').click(function() {
  $('#items_table tbody').append(`
      <tr>
        <td><input name="item_id[]" class="form-control" required></td>
        <td><input name="item_description[]" class="form-control" required></td>
        <td><input type="number" name="qty[]" class="form-control qty" min="0" value="0" required></td>
        <td><input type="number" name="unit_price[]" class="form-control unit_price" step="0.01" min="0" value="0.00" required></td>
        <td><input type="number" name="total_sales_before_vat[]" class="form-control total_sales_before_vat" readonly value="0.00"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
      </tr>`);
 });

 $('#items_table').on('click', '.remove-row', function() {
  $(this).closest('tr').remove();
  calculateTotals();
 });

 $(document).ready(() => {
  calculateTotals();
 });

 // Full Number to Words Converter for whole numbers
 function numberToWords(num) {
  const a = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine",
   "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen",
   "Sixteen", "Seventeen", "Eighteen", "Nineteen"
  ];
  const b = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

  function inWords(n) {
   if (n < 20) return a[n];
   if (n < 100) return b[Math.floor(n / 10)] + (n % 10 ? " " + a[n % 10] : "");
   if (n < 1000) return a[Math.floor(n / 100)] + " Hundred" + (n % 100 ? " " + inWords(n % 100) : "");
   if (n < 1000000) return inWords(Math.floor(n / 1000)) + " Thousand" + (n % 1000 ? " " + inWords(n % 1000) : "");
   if (n < 1000000000) return inWords(Math.floor(n / 1000000)) + " Million" + (n % 1000000 ? " " + inWords(n %
    1000000) : "");
   return inWords(Math.floor(n / 1000000000)) + " Billion" + (n % 1000000000 ? " " + inWords(n % 1000000000) : "");
  }

  return num === 0 ? "Zero" : inWords(num);
 }
 </script>
 // Prevent default form submit

 <script>
 document.getElementById("invoiceForm").addEventListener("submit", function(e) {
  e.preventDefault(); // Prevent default form submit// clear data after saving 

  const formData = new FormData(this);

  fetch("save_invoice.php", {
    method: "POST",
    body: formData
   })
   .then(response => response.json())
   .then(data => {
    if (data.success) {
     alert("Invoice saved successfully!");
     document.getElementById("invoiceForm").reset(); // Clears all inputs
     clearDynamicRows(); // Also clear dynamically added rows
     updateTotal(); // Reset total
     document.getElementById("amountInWords").textContent = ""; // Clear words
    } else {
     alert("Error: " + data.message);
    }
   })
   .catch(error => {
    console.error("Error:", error);
    alert("Error occurred while saving.");
   });
 });

 // Optional: Clear added item rows
 function clearDynamicRows() {
  const itemList = document.getElementById("itemList");
  itemList.innerHTML = ''; // Remove all item rows
 }
 </script>


</body>

</html>

</body>

</html>



kkkkk
<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "abc_company");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$formData = [];

// Check if ID is provided in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM sales WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $formData = $result->fetch_assoc();
    }

    $stmt->close();
} else {
    echo "<h3 style='color:red;'>Missing sales ID in URL!</h3>";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
 <title>Edit Sales Record</title>
 <style>
 .form-row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-bottom: 20px;
 }

 .form-group {
  flex: 1 1 22%;
  display: flex;
  flex-direction: column;
 }

 label {
  font-weight: bold;
 }

 input,
 select {
  padding: 5px;
  font-size: 14px;
 }

 .container {
  max-width: 1200px;
  margin: auto;
 }

 h2 {
  text-align: center;
 }
 </style>
</head>

<body>
 <div class="container">
  <h2>Edit Sales Record</h2>
  <form method="post" action="update_sales.php">
   <input type="hidden" name="id" value="<?php echo $formData['id']; ?>">

   <!-- Row 1 -->
   <div class="form-row">
    <div class="form-group">
     <label>Order No</label>
     <input type="text" name="order_no" value="<?php echo htmlspecialchars($formData['order_no'] ?? ''); ?>">
    </div>
    <div class="form-group">
     <label>Invoice No</label>
     <input type="text" name="invoice_no" value="<?php echo htmlspecialchars($formData['invoice_no'] ?? ''); ?>">
    </div>
    <div class="form-group">
     <label>Reference</label>
     <input type="text" name="reference" value="<?php echo htmlspecialchars($formData['reference'] ?? ''); ?>">
    </div>
    <div class="form-group">
     <label>Invoice Date</label>
     <input type="date" name="invoice_date" value="<?php echo htmlspecialchars($formData['invoice_date'] ?? ''); ?>">
    </div>
   </div>

   <!-- Row 2 -->
   <div class="form-row">
    <div class="form-group">
     <label>Customer ID</label>
     <input type="text" name="customer_id" value="<?php echo htmlspecialchars($formData['customer_id'] ?? ''); ?>">
    </div>
    <div class="form-group">
     <label>Customer Name</label>
     <input type="text" name="customer_name" value="<?php echo htmlspecialchars($formData['customer_name'] ?? ''); ?>">
    </div>
    <div class="form-group">
     <label>Branch ID</label>
     <input type="text" name="branch_id" value="<?php echo htmlspecialchars($formData['branch_id'] ?? ''); ?>">
    </div>
    <div class="form-group">
     <label>Branch Name</label>
     <input type="text" name="branch_name" value="<?php echo htmlspecialchars($formData['branch_name'] ?? ''); ?>">
    </div>
   </div>

   <!-- Row 3 -->
   <div class="form-row">
    <div class="form-group">
     <label>Salesperson ID</label>
     <input type="text" name="salesperson_id"
      value="<?php echo htmlspecialchars($formData['salesperson_id'] ?? ''); ?>">
    </div>
    <div class="form-group">
     <label>Salesperson Name</label>
     <input type="text" name="salesperson_name"
      value="<?php echo htmlspecialchars($formData['salesperson_name'] ?? ''); ?>">
    </div>
    <div class="form-group">
     <label>Job ID</label>
     <input type="text" name="job_id" value="<?php echo htmlspecialchars($formData['job_id'] ?? ''); ?>">
    </div>
    <div class="form-group">
     <label>Payment Method</label>
     <input type="text" name="payment_method"
      value="<?php echo htmlspecialchars($formData['payment_method'] ?? ''); ?>">
    </div>
   </div>

   <!-- Add more rows as needed -->

   <div style="text-align: center;">
    <button type="submit">Update Record</button>
   </div>
  </form>
 </div>
</body>

</html>

<?php if (!empty($message)) echo $message; ?>

<style>
.alert {
 padding: 15px;
 margin-bottom: 20px;
 border-radius: 5px;
 font-weight: bold;
 text-align: center;
}

.alert.success {
 background-color: #d4edda;
 color: #155724;
 border: 1px solid #c3e6cb;
}

.alert.error {
 background-color: #f8d7da;
 color: #721c24;
 border: 1px solid #f5c6cb;
}
</style>
<!DOCTYPE html>
<html>

<head>
 <title>Sales Invoice with VAT</title>
 <style>
 body {
  font-family: Arial, sans-serif;
  max-width: 800px;
  margin: 20px auto;
  padding: 20px;
  background: #f9f9f9;
 }

 h2,
 h3 {
  color: #333;
  margin-top: 0;
 }

 .form-group {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  margin-bottom: 15px;
 }

 .form-group div {
  flex: 1 1 45%;
 }

 label {
  font-weight: bold;
 }

 input[type="text"],
 input[type="number"],
 input[type="date"],
 select {
  width: 100%;
  padding: 6px;
  font-size: 14px;
 }

 table {
  width: 100%;
  margin-top: 20px;
  border-collapse: collapse;
  font-size: 14px;
 }

 table th,
 table td {
  border: 1px solid #ddd;
  padding: 6px;
  text-align: center;
 }

 .button {
  padding: 6px 14px;
  background-color: #007BFF;
  color: white;
  border: none;
  margin-top: 10px;
  cursor: pointer;
 }

 .button:hover {
  background-color: #0056b3;
 }

 .totals {
  margin-top: 20px;
  font-size: 15px;
  font-weight: bold;
 }

 .back-button {
  margin-bottom: 15px;
  display: inline-block;
 }
 </style>

 <script>
 function addRow() {
  const table = document.getElementById("itemsTable").getElementsByTagName('tbody')[0];
  const newRow = table.insertRow();
  newRow.innerHTML = `
                <td><input type="text" name="item_id[]" required></td>
                <td><input type="text" name="item_description[]" required></td>
                <td><input type="number" name="qty[]" step="1" min="0" required oninput="calculateSubtotal(this)"></td>
                <td><input type="number" name="unit_price[]" step="0.01" min="0" required oninput="calculateSubtotal(this)"></td>
                <td><input type="text" name="subtotal[]" readonly></td>
                <td><button type="button" class="button" onclick="deleteRow(this)">Delete</button></td>
            `;
 }

 function deleteRow(button) {
  button.closest("tr").remove();
  updateTotal();
 }

 function calculateSubtotal(input) {
  const row = input.closest("tr");
  const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
  const price = parseFloat(row.querySelector('input[name="unit_price[]"]').value) || 0;
  const subtotal = (qty * price).toFixed(2);
  row.querySelector('input[name="subtotal[]"]').value = subtotal;
  updateTotal();
 }

 function updateTotal() {
  let total = 0;
  document.querySelectorAll('input[name="subtotal[]"]').forEach(input => {
   total += parseFloat(input.value) || 0;
  });

  const vatRate = 0.15;
  const vatAmount = total * vatRate;
  const grandTotal = total + vatAmount;

  document.getElementById("grandTotal").textContent = total.toFixed(2);
  document.getElementById("vatAmount").textContent = vatAmount.toFixed(2);
  document.getElementById("totalAfterVat").textContent = grandTotal.toFixed(2);
 }
 </script>
</head>

<body>

 <a href="sales_report.php" class="button back-button">← Back to Sales Report</a>
 <h2>Sales Invoice</h2>

 <form method="post" action="">
  <div class="form-group">
   <div>
    <label>Order No.</label>
    <input type="text" name="order_no" value="<?= $formData['order_no'] ?>">
   </div>
   <div>
    <label>Invoice No.</label>
    <input type="text" name="invoice_no" value="<?= $formData['invoice_no'] ?>">
   </div>
   <div>
    <label>Reference</label>
    <input type="text" name="reference" value="<?= $formData['reference'] ?>">
   </div>
   <div>
    <label>Invoice Date</label>
    <input type="date" name="invoice_date" value="<?= $formData['invoice_date'] ?>">
   </div>
   <div>
    <label>Customer ID</label>
    <input type="text" name="customer_id" value="<?= $formData['customer_id'] ?>">
   </div>
   <div>
    <label>Customer Name</label>
    <input type="text" name="customer_name" value="<?= $formData['customer_name'] ?>">
   </div>
   <div>
    <label>Branch ID</label>
    <input type="text" name="branch_id" value="<?= $formData['branch_id'] ?>">
   </div>
   <div>
    <label>Branch Name</label>
    <input type="text" name="branch_name" value="<?= $formData['branch_name'] ?>">
   </div>
   <div>
    <label>Salesperson ID</label>
    <input type="text" name="salesperson_id" value="<?= $formData['salesperson_id'] ?>">
   </div>
   <div>
    <label>Salesperson Name</label>
    <input type="text" name="salesperson_name" value="<?= $formData['salesperson_name'] ?>">
   </div>
   <div>
    <label>Job ID</label>
    <input type="text" name="job_id" value="<?= $formData['job_id'] ?>">
   </div>
   <div>
    <label>Payment Method</label>
    <select name="payment_method">
     <option value="">Select Payment Method</option>
     <option value="cash" <?= $formData['payment_method'] === 'Cash' ? 'selected' : '' ?>>Cash</option>
     <option value="bank_transfer" <?= $formData['payment_method'] === 'Bank Transfer' ? 'selected' : '' ?>>Bank
      Transfer</option>
     <option value="credit" <?= $formData['payment_method'] === 'Credit' ? 'selected' : '' ?>>Credit</option>
    </select>
   </div>
  </div>

  <h3>Items Purchased</h3>
  <table id="itemsTable">
   <thead>
    <tr>
     <th>Item ID</th>
     <th>Description</th>
     <th>Qty</th>
     <th>Unit Price</th>
     <th>Subtotal</th>
     <th>Action</th>
    </tr>
   </thead>
   <tbody>
    <tr>
     <td><input type="text" name="item_id[]" value="ITM001"></td>
     <td><input type="text" name="item_description[]" value="Office Chair"></td>
     <td><input type="number" name="qty[]" value="10" oninput="calculateSubtotal(this)"></td>
     <td><input type="number" step="0.01" name="unit_price[]" value="5000" oninput="calculateSubtotal(this)"></td>
     <td><input type="text" name="subtotal[]" readonly></td>
     <td><button type="button" class="button" onclick="deleteRow(this)">Delete</button></td>
    </tr>
   </tbody>
  </table>

  <button type="button" class="button" onclick="addRow()">Add Row</button>

  <div class="totals">
   <div>Subtotal: $<span id="grandTotal">0.00</span></div>
   <div>VAT (15%): $<span id="vatAmount">0.00</span></div>
   <div>Total After VAT: $<span id="totalAfterVat">0.00</span></div>
  </div>

  <button type="submit" class="button">Submit Invoice</button>
 </form>

 <script>
 // Trigger initial subtotal calculation for pre-filled data
 document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('input[name="qty[]"], input[name="unit_price[]"]').forEach(input => {
   input.dispatchEvent(new Event('input'));
  });
 });
 </script>

</body>

</html>


kkkkkk
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "abc_company";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("Missing sales ID.");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM sales WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows !== 1) {
    die("Sales record not found.");
}

$sale = $result->fetch_assoc();

// Sanitize and validate form data
$date = !empty($_POST['date']) ? $_POST['date'] : $sale['date'];
$sales_order_no = !empty($_POST['sales_order_no']) ? mysqli_real_escape_string($conn, $_POST['sales_order_no']) : $sale['sales_order_no'];
$salesperson_id = !empty($_POST['salesperson_id']) ? $_POST['salesperson_id'] : $sale['salesperson_id'];
$salesperson_name = !empty($_POST['salesperson_name']) ? mysqli_real_escape_string($conn, $_POST['salesperson_name']) : $sale['salesperson_name'];
$branch_id = !empty($_POST['branch_id']) ? $_POST['branch_id'] : $sale['branch_id'];
$branch_name = !empty($_POST['branch_name']) ? mysqli_real_escape_string($conn, $_POST['branch_name']) : $sale['branch_name'];
$invoice_no = !empty($_POST['invoice_no']) ? mysqli_real_escape_string($conn, $_POST['invoice_no']) : $sale['invoice_no'];
$reference = !empty($_POST['reference']) ? mysqli_real_escape_string($conn, $_POST['reference']) : $sale['reference'];
$item_description = !empty($_POST['item_description']) ? mysqli_real_escape_string($conn, $_POST['item_description']) : $sale['item_description'];
$quantity = !empty($_POST['quantity']) ? $_POST['quantity'] : $sale['quantity'];
$unit_price = isset($_POST['unit_price']) ? $_POST['unit_price'] : $sale['unit_price'];
$customer_id = !empty($_POST['customer_id']) ? $_POST['customer_id'] : $sale['customer_id'];
$customer_name = !empty($_POST['customer_name']) ? mysqli_real_escape_string($conn, $_POST['customer_name']) : $sale['customer_name'];
$payment_method = !empty($_POST['payment_method']) ? mysqli_real_escape_string($conn, $_POST['payment_method']) : $sale['payment_method'];
$job_id = !empty($_POST['job_id']) ? $_POST['job_id'] : $sale['job_id'];
$section = !empty($_POST['section']) ? $_POST['section'] : $sale['section'];

// Ensure unit_price and quantity are not empty or invalid
if (empty($unit_price) || !is_numeric($unit_price)) {
    $unit_price = 0;  // Default value if invalid
}

if (empty($quantity) || !is_numeric($quantity)) {
    $quantity = 0;  // Default value if invalid
}

// Calculate totals
$total_sales_before_vat = $quantity * $unit_price;
$vat = $total_sales_before_vat * 0.15;
$total_sales_after_vat = $total_sales_before_vat + $vat;

// Format values to avoid any unexpected issues
$total_sales_before_vat = number_format($total_sales_before_vat, 2, '.', '');
$vat = number_format($vat, 2, '.', '');
$total_sales_after_vat = number_format($total_sales_after_vat, 2, '.', '');

// SQL query for updating record
$sql = "UPDATE sales SET
    date = '$date',
    sales_order_no = '$sales_order_no',
    salesperson_id = '$salesperson_id',
    salesperson_name = '$salesperson_name',
    branch_id = '$branch_id',
    branch_name = '$branch_name',
    invoice_no = '$invoice_no',
    reference = '$reference',
    item_description = '$item_description',
    quantity = '$quantity',
    unit_price = '$unit_price',
    total_sales_before_vat = '$total_sales_before_vat',
    vat = '$vat',
    total_sales_after_vat = '$total_sales_after_vat',
    customer_id = '$customer_id',
    customer_name = '$customer_name',
    payment_method = '$payment_method',
    job_id = '$job_id',
    section = '$section'
WHERE id = $id";

// Uncomment the line below for debugging purposes
// echo $sql;  // This will print the SQL query, allowing you to debug the generated query


$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
 <title>Edit Sales Record</title>
 <style>
 body {
  font-family: 'Segoe UI', sans-serif;
  background: #f9f9f9;
  padding: 30px;
 }

 .form-container {
  background: #fff;
  padding: 25px 30px;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  max-width: 800px;
  margin: auto;
 }

 .form-row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-bottom: 15px;
 }

 .form-group {
  flex: 1;
  min-width: 240px;
 }

 label {
  display: block;
  margin-bottom: 5px;
  font-weight: 600;
 }

 input {
  width: 100%;
  padding: 8px 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
 }

 .submit-btn {
  background: #007bff;
  color: #fff;
  padding: 10px 25px;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
  margin-top: 15px;
 }

 .submit-btn:hover {
  background: #0056b3;
 }

 .result {
  margin-top: 15px;
  font-weight: bold;
 }

 .readonly {
  background-color: #f0f0f0;
 }
 </style>
</head>
<?php
// Database connection and data retrieval
$servername = "localhost";
$username = "root";
$password = "";
$database = "abc_company";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("Missing sales ID.");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM sales WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows !== 1) {
    die("Sales record not found.");
}

$sale = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form data
    $date = mysqli_real_escape_string($conn, $_POST['date'] ?? $sale['date']);
    $sales_order_no = mysqli_real_escape_string($conn, $_POST['sales_order_no'] ?? $sale['sales_order_no']);
    $salesperson_id = mysqli_real_escape_string($conn, $_POST['salesperson_id'] ?? $sale['salesperson_id']);
    $salesperson_name = mysqli_real_escape_string($conn, $_POST['salesperson_name'] ?? $sale['salesperson_name']);
    $branch_id = mysqli_real_escape_string($conn, $_POST['branch_id'] ?? $sale['branch_id']);
    $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name'] ?? $sale['branch_name']);
    $invoice_no = mysqli_real_escape_string($conn, $_POST['invoice_no'] ?? $sale['invoice_no']);
    $reference = mysqli_real_escape_string($conn, $_POST['reference'] ?? $sale['reference']);
    $item_description = mysqli_real_escape_string($conn, $_POST['item_description'] ?? $sale['item_description']);
    $quantity = floatval($_POST['quantity'] ?? $sale['quantity']);
    $unit_price = floatval($_POST['unit_price'] ?? $sale['unit_price']);
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id'] ?? $sale['customer_id']);
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name'] ?? $sale['customer_name']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method'] ?? $sale['payment_method']);
    $job_id = mysqli_real_escape_string($conn, $_POST['job_id'] ?? $sale['job_id']);
    $section = mysqli_real_escape_string($conn, $_POST['section'] ?? $sale['section']);

    // Calculate totals
    $total_sales_before_vat = $quantity * $unit_price;
    $vat = $total_sales_before_vat * 0.15;
    $total_sales_after_vat = $total_sales_before_vat + $vat;

    // Update record
    $update_sql = "UPDATE sales SET
        date = ?,
        sales_order_no = ?,
        salesperson_id = ?,
        salesperson_name = ?,
        branch_id = ?,
        branch_name = ?,
        invoice_no = ?,
        reference = ?,
        item_description = ?,
        quantity = ?,
        unit_price = ?,
        total_sales_before_vat = ?,
        vat = ?,
        total_sales_after_vat = ?,
        customer_id = ?,
        customer_name = ?,
        payment_method = ?,
        job_id = ?,
        section = ?
    WHERE id = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssssdddddsssssi", 
        $date, $sales_order_no, $salesperson_id, $salesperson_name,
        $branch_id, $branch_name, $invoice_no, $reference, $item_description,
        $quantity, $unit_price, $total_sales_before_vat, $vat, $total_sales_after_vat,
        $customer_id, $customer_name, $payment_method, $job_id, $section, $id
    );

    if ($stmt->execute()) {
        $success_message = "Record updated successfully";
        // Refresh the data
        $result = $conn->query("SELECT * FROM sales WHERE id = $id");
        $sale = $result->fetch_assoc();
    } else {
        $error_message = "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Edit Sales Record | ABC Company</title>
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 <style>
 :root {
  --primary: #4361ee;
  --primary-dark: #3a56d4;
  --secondary: #3f37c9;
  --light: #f8f9fa;
  --dark: #212529;
  --success: #4cc9f0;
  --danger: #f72585;
  --border-radius: 8px;
  --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  --transition: all 0.3s ease;
 }

 * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
 }

 body {
  font-family: 'Poppins', sans-serif;
  background-color: #f5f7fb;
  color: var(--dark);
  line-height: 1.6;
 }

 .container {
  max-width: 1000px;
  margin: 40px auto;
  padding: 0 20px;
 }

 .card {
  background: white;
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  overflow: hidden;
  margin-bottom: 30px;
 }

 .card-header {
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  color: white;
  padding: 20px 25px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
 }

 .card-header h2 {
  font-weight: 600;
  font-size: 1.5rem;
  display: flex;
  align-items: center;
 }

 .card-header h2 i {
  margin-right: 10px;
 }

 .card-body {
  padding: 25px;
 }

 .alert {
  padding: 15px;
  margin-bottom: 20px;
  border-radius: var(--border-radius);
 }

 .alert-success {
  background-color: #d4edda;
  color: #155724;
 }

 .alert-danger {
  background-color: #f8d7da;
  color: #721c24;
 }

 .form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
 }

 .form-group {
  margin-bottom: 15px;
 }

 .form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #555;
  font-size: 0.9rem;
 }

 .form-control {
  width: 100%;
  padding: 10px 15px;
  border: 1px solid #ddd;
  border-radius: var(--border-radius);
  font-family: inherit;
  font-size: 0.95rem;
  transition: var(--transition);
  background-color: white;
 }

 .form-control:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
 }

 .readonly {
  background-color: #f8f9fa;
  color: #666;
 }

 .btn {
  display: inline-block;
  padding: 10px 25px;
  background-color: var(--primary);
  color: white;
  border: none;
  border-radius: var(--border-radius);
  font-weight: 500;
  cursor: pointer;
  transition: var(--transition);
  text-align: center;
  font-size: 1rem;
 }

 .btn:hover {
  background-color: var(--primary-dark);
  transform: translateY(-2px);
 }

 .btn-block {
  display: block;
  width: 100%;
 }

 .result-box {
  background-color: #f8f9fa;
  border-left: 4px solid var(--primary);
  padding: 15px;
  margin-top: 20px;
  border-radius: 0 var(--border-radius) var(--border-radius) 0;
 }

 .result-title {
  font-weight: 600;
  color: var(--primary);
  margin-bottom: 5px;
  font-size: 0.9rem;
 }

 .result-value {
  font-size: 1.1rem;
 }

 @media (max-width: 768px) {
  .form-grid {
   grid-template-columns: 1fr;
  }

  .container {
   margin: 20px auto;
  }

  .card-header h2 {
   font-size: 1.3rem;
  }
 }

 /* Animation */
 @keyframes fadeIn {
  from {
   opacity: 0;
   transform: translateY(10px);
  }

  to {
   opacity: 1;
   transform: translateY(0);
  }
 }

 .card-body {
  animation: fadeIn 0.4s ease-out;
 }
 </style>
</head>

<body>
 <div class="container">
  <div class="card">
   <div class="card-header">
    <h2><i class="fas fa-edit"></i> Edit Sales Record</h2>
   </div>
   <div class="card-body">
    <?php if (isset($success_message)): ?>
    <div class="alert alert-success">
     <?= $success_message ?>
    </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
    <div class="alert alert-danger">
     <?= $error_message ?>
    </div>
    <?php endif; ?>

    <form action="?id=<?= $id ?>" method="POST" id="salesForm">
     <input type="hidden" name="id" value="<?= htmlspecialchars($sale['id']) ?>">

     <div class="form-grid">
      <div class="form-group">
       <label for="date">Date</label>
       <input type="date" name="date" id="date" class="form-control" value="<?= htmlspecialchars($sale['date']) ?>"
        required>
      </div>
      <div class="form-group">
       <label for="sales_order_no">Sales Order No</label>
       <input type="text" name="sales_order_no" id="sales_order_no" class="form-control"
        value="<?= htmlspecialchars($sale['sales_order_no']) ?>" required>
      </div>
     </div>

     <div class="form-grid">
      <div class="form-group">
       <label for="salesperson_id">Salesperson ID</label>
       <input type="text" name="salesperson_id" id="salesperson_id" class="form-control"
        value="<?= htmlspecialchars($sale['salesperson_id']) ?>" required>
      </div>
      <div class="form-group">
       <label for="salesperson_name">Salesperson Name</label>
       <input type="text" name="salesperson_name" id="salesperson_name" class="form-control"
        value="<?= htmlspecialchars($sale['salesperson_name']) ?>" required>
      </div>
     </div>

     <div class="form-grid">
      <div class="form-group">
       <label for="branch_id">Branch ID</label>
       <input type="text" name="branch_id" id="branch_id" class="form-control"
        value="<?= htmlspecialchars($sale['branch_id']) ?>" required>
      </div>
      <div class="form-group">
       <label for="branch_name">Branch Name</label>
       <input type="text" name="branch_name" id="branch_name" class="form-control"
        value="<?= htmlspecialchars($sale['branch_name']) ?>" required>
      </div>
     </div>

     <div class="form-grid">
      <div class="form-group">
       <label for="invoice_no">Invoice No</label>
       <input type="text" name="invoice_no" id="invoice_no" class="form-control"
        value="<?= htmlspecialchars($sale['invoice_no']) ?>" required>
      </div>
      <div class="form-group">
       <label for="reference">Reference</label>
       <input type="text" name="reference" id="reference" class="form-control"
        value="<?= htmlspecialchars($sale['reference']) ?>">
      </div>
     </div>
     <div class="form-grid">
      <div class="form-group">
       <label for="customer_id">Customer ID</label>
       <input type="text" name="customer_id" id="customer_id" class="form-control"
        value="<?= htmlspecialchars($sale['customer_id']) ?>" required>
      </div>
      <div class="form-group">
       <label for="customer_name">Customer Name</label>
       <input type="text" name="customer_name" id="customer_name" class="form-control"
        value="<?= htmlspecialchars($sale['customer_name']) ?>" required>
      </div>
     </div>

     <div class="form-grid">
      <div class="form-group">
       <label for="payment_method">Payment Method</label>
       <input type="text" name="payment_method" id="payment_method" class="form-control"
        value="<?= htmlspecialchars($sale['payment_method']) ?>" required>
      </div>
      <div class="form-group">
       <label for="job_id">Job ID</label>
       <input type="text" name="job_id" id="job_id" class="form-control"
        value="<?= htmlspecialchars($sale['job_id']) ?>">
      </div>
      <div class="form-group">
       <label for="section">Section</label>
       <input type="text" name="section" id="section" class="form-control"
        value="<?= htmlspecialchars($sale['section']) ?>">
      </div>
     </div>
     <div class="form-grid">
      <div class="form-group">
       <label for="item_description">Item Description</label>
       <input type="text" name="item_description" id="item_description" class="form-control"
        value="<?= htmlspecialchars($sale['item_description']) ?>" required>
      </div>
      <div class="form-group">
       <label for="quantity">Quantity</label>
       <input type="number" name="quantity" id="quantity" class="form-control"
        value="<?= htmlspecialchars($sale['quantity']) ?>" step="any" min="0" required>
      </div>
      <div class="form-group">
       <label for="unit_price">Unit Price</label>
       <input type="number" name="unit_price" id="unit_price" class="form-control"
        value="<?= htmlspecialchars($sale['unit_price']) ?>" step="any" min="0" required>
      </div>
     </div>

     <div class="form-grid">
      <div class="form-group">
       <label>Total Sales Before VAT</label>
       <input type="text" name="total_sales_before_vat" id="before_vat" class="form-control readonly" readonly>
      </div>
      <div class="form-group">
       <label>VAT (15%)</label>
       <input type="text" name="vat" id="vat" class="form-control readonly" readonly>
      </div>
      <div class="form-group">
       <label>Total Sales After VAT</label>
       <input type="text" name="total_sales_after_vat" id="after_vat" class="form-control readonly" readonly>
      </div>
     </div>
     <div class="result-box" id="inWords">
      <div class="result-title">Total Amount in Words</div>
      <div class="result-value" id="amountInWords"></div>
     </div>


     <button type="submit" class="btn btn-block">
      <i class="fas fa-save"></i> Update Sales Record
     </button>


    </form>
   </div>
  </div>
 </div>

 <script>
 const qty = document.getElementById('quantity');
 const price = document.getElementById('unit_price');
 const beforeVAT = document.getElementById('before_vat');
 const vat = document.getElementById('vat');
 const afterVAT = document.getElementById('after_vat');
 const inWords = document.getElementById('amountInWords');

 function formatNumber(num) {
  return parseFloat(num).toLocaleString(undefined, {
   minimumFractionDigits: 2,
   maximumFractionDigits: 2
  });
 }

 function numberToWords(n) {
  const a = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven",
   "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"
  ];
  const b = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

  if ((n = n.toString()).length > 9) return 'Overflow';
  let num = ('000000000' + n).substr(-9).match(/^(\d{3})(\d{2})(\d{2})(\d{2})$/);
  if (!num) return;
  let str = '';
  str += (num[1] != 0) ? (a[Number(num[1])] || b[num[1][0]] + ' ' + a[num[1][1]]) + ' Million ' : '';
  str += (num[2] != 0) ? (a[Number(num[2])] || b[num[2][0]] + ' ' + a[num[2][1]]) + ' Thousand ' : '';
  str += (num[3] != 0) ? (a[Number(num[3])] || b[num[3][0]] + ' ' + a[num[3][1]]) + ' Hundred ' : '';
  str += (num[4] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(num[4])] || b[num[4][0]] + ' ' + a[num[4][1]]) : '';
  return str.trim() + ' Birr Only';
 }

 function calculateTotals() {
  const q = parseFloat(qty.value) || 0;
  const p = parseFloat(price.value) || 0;
  const totalBefore = q * p;
  const vatAmount = totalBefore * 0.15;
  const totalAfter = totalBefore + vatAmount;

  beforeVAT.value = formatNumber(totalBefore);
  vat.value = formatNumber(vatAmount);
  afterVAT.value = formatNumber(totalAfter);
  inWords.textContent = numberToWords(Math.round(totalAfter));
 }

 // Event listeners
 qty.addEventListener('input', calculateTotals);
 price.addEventListener('input', calculateTotals);
 window.addEventListener('load', calculateTotals);
 </script>
</body>

</html>
<?php
$conn->close();
?>