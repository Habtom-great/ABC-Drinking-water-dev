<?php
// Database connection
$host = 'localhost';
$db = 'abc_company';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fallback: Custom number to words function (English, limited to thousands)
function numberToWords($number) {
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = [
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'forty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand'
    ];

    if (!is_numeric($number)) {
        return false;
    }

    if ($number < 0) {
        return $negative . numberToWords(abs($number));
    }

    $string = '';

    if ($number < 21) {
        $string = $dictionary[$number];
    } elseif ($number < 100) {
        $tens   = ((int) ($number / 10)) * 10;
        $units  = $number % 10;
        $string = $dictionary[$tens];
        if ($units) {
            $string .= $hyphen . $dictionary[$units];
        }
    } elseif ($number < 1000) {
        $hundreds  = (int) ($number / 100);
        $remainder = $number % 100;
        $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
        if ($remainder) {
            $string .= $conjunction . numberToWords($remainder);
        }
    } else {
        $thousands = (int) ($number / 1000);
        $remainder = $number % 1000;
        $string = numberToWords($thousands) . ' ' . $dictionary[1000];
        if ($remainder) {
            $string .= $separator . numberToWords($remainder);
        }
    }

    return ucfirst($string);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'sales_order_no' => $_POST['sales_order_no'] ?? '',
        'sales_invoice_no' => $_POST['sales_invoice_no'] ?? '',
        'invoice_date' => $_POST['invoice_date'] ?? '',
        'customer_name' => $_POST['customer_name'] ?? '',
        'customer_address' => $_POST['customer_address'] ?? '',
        'salesperson_name' => $_POST['salesperson_name'] ?? '',
        'salesperson_phone' => $_POST['salesperson_phone'] ?? '',
        'total_sales_before_vat' => floatval($_POST['total_sales_before_vat_all'] ?? 0),
        'vat' => floatval($_POST['vat'] ?? 0),
        'total_sales_after_vat' => floatval($_POST['total_sales_after_vat'] ?? 0),
        'amount_paid' => floatval($_POST['amount_paid'] ?? 0),
        'amount_due' => floatval($_POST['amount_due'] ?? 0),
    ];

    // Prepare invoice insert
    $stmt = $conn->prepare("INSERT INTO invoices (
        sales_order_no, sales_invoice_no, invoice_date, customer_name, customer_address,
        salesperson_name, salesperson_phone, total_sales_before_vat, vat,
        total_sales_after_vat, amount_paid, amount_due
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssdddddd",
        $formData['sales_order_no'],
        $formData['sales_invoice_no'],
        $formData['invoice_date'],
        $formData['customer_name'],
        $formData['customer_address'],
        $formData['salesperson_name'],
        $formData['salesperson_phone'],
        $formData['total_sales_before_vat'],
        $formData['vat'],
        $formData['total_sales_after_vat'],
        $formData['amount_paid'],
        $formData['amount_due']
    );

    if ($stmt->execute()) {
        $invoice_id = $stmt->insert_id;

        // Insert invoice items
        if (isset($_POST['item_id']) && is_array($_POST['item_id'])) {
            foreach ($_POST['item_id'] as $index => $itemId) {
                $desc = $conn->real_escape_string($_POST['item_description'][$index] ?? '');
                $cat = $conn->real_escape_string($_POST['category'][$index] ?? '');
                $uom = $conn->real_escape_string($_POST['uom'][$index] ?? '');
                $qty = floatval($_POST['quantity'][$index] ?? 0);
                $unit_price = floatval($_POST['unit_price'][$index] ?? 0);
                $subtotal = floatval($_POST['total_sales_before_vat'][$index] ?? 0);

                $itemStmt = $conn->prepare("INSERT INTO invoice_items (
                    invoice_id, item_id, description, category, uom, quantity, unit_price, total_sales_before_vat
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                if (!$itemStmt) {
                    die("Prepare failed (item): " . $conn->error);
                }

                $itemStmt->bind_param(
                    "iisssidd",
                    $invoice_id,
                    $itemId,
                    $desc,
                    $cat,
                    $uom,
                    $qty,
                    $unit_price,
                    $subtotal
                );

                $itemStmt->execute();
                $itemStmt->close();
            }
        }

        // Display confirmation
        $totalFormatted = number_format($formData['total_sales_after_vat'], 2);
        $inWords = numberToWords($formData['total_sales_after_vat']);

        echo "<div style='padding:10px; background:#d4edda; color:#155724; font-family: Arial, sans-serif;'>
                <h3>Invoice saved successfully!</h3>
                <p><strong>Total Amount:</strong> $totalFormatted</p>
                <p><strong>In Words:</strong> $inWords birr</p>
              </div>";

    } else {
        echo "<div style='padding:10px; background:#f8d7da; color:#721c24;'>Error saving invoice: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Sales Invoice</title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
 <style>
 .is-invalid {
  border-color: #dc3545;
 }

 .invalid-feedback {
  color: #dc3545;
  display: none;
 }

 body {
  background-color: #f4f4f9;
 }

 .container {
  max-width: 800px;
 }

 .card {
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

 .header img {
  max-height: 50px;
 }

 .footer {
  font-size: 14px;
  font-weight: bold;
  margin-top: 10px;
  text-align: center;
 }
 </style>
</head>

<body>
 <div></div>
 </div>
 <div class="header text-center">
  <img src="assets/images/child drinking.jpeg" alt="Company Logo">
  <h4>ABC Company PLC | Sales Invoice</h4>
  <small>Bole, Addis Ababa | Tel: +251 912 345 678</small>
 </div>

 <div class="container mt-2">
  <h2 class="mb-3 text-center">Sales Invoice</h2>
  <!-- Invoice Header -->
  <div class="invoice-header d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
   <div>

    <!-- Action Buttons -->
    <form id="sales_form" method="POST">
     <div class="d-flex flex-wrap gap-2 mb-3">
      <button type="button" class="btn btn-info text-white" onclick="window.location.href='sales_invoice_list.php'">📂
       Show Sales</button>
      <div></div>
      <button type="reset" class="btn btn-danger" onclick="clearForm()">🗑️ Clear Data</button>
      <button type="button" class="btn btn-secondary no-print" onclick="printFullInvoice()">🖨️ Print</button>
      <button type="submit" name="save_invoice" class="btn btn-success">💾 Save Invoice</button>

      <!-- close button -->
      <button type="button" onclick="window.location.href='admin_dashboard.php'" class="btn btn-danger">Close</button>
     </div>


     <!-- Success/Error Messages -->

     <div class="card">

      <!-- Sales General Information -->
      <div class="row">
       <div class="col-md-3">
        <label class="form-label">Order No.</label>
        <input type="text"
         class="form-control <?php echo (!empty($error) && empty($formData['sales_order_no']) ? 'is-invalid' : ''); ?>"
         name="sales_order_no" placeholder="Enter order number"
         value="<?php echo htmlspecialchars($formData['sales_order_no'] ?? ''); ?>" required>

       </div>
       <div class="col-md-3">
        <label class="form-label">Invoice No.</label>
        <input type="text"
         class="form-control <?php echo (!empty($error) && empty($formData['sales_invoice_no']) ? 'is-invalid' : ''); ?>"
         name="sales_invoice_no" placeholder="Enter invoice number"
         value="<?php echo htmlspecialchars($formData['sales_invoice_no'] ?? ''); ?>" required>
       </div>
       <div class="col-md-3">
        <label class="form-label">Reference</label>
        <input type="text" class="form-control" name="reference" placeholder="Enter reference"
         value="<?php echo htmlspecialchars($formData['reference'] ?? ''); ?>" required>
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
         value="<?php echo htmlspecialchars($formData['customer_id'] ?? ''); ?>" required>
       </div>
       <div class="col-md-3">
        <label class="form-label">Customer Name</label>
        <input type="text"
         class="form-control <?php echo (!empty($error) && empty($formData['customer_name']) ? 'is-invalid' : ''); ?>"
         name="customer_name" placeholder="Enter customer name"
         value="<?php echo htmlspecialchars($formData['customer_name'] ?? ''); ?>" required>
       </div>
       <div class="col-md-3">
        <label class="form-label">Branch ID</label>
        <input type="text" class="form-control" name="branch_id" placeholder="Enter branch ID"
         value="<?php echo htmlspecialchars($formData['branch_id'] ?? ''); ?>" required>
       </div>
       <div class="col-md-3">
        <label class="form-label">Branch Name</label>
        <input type="text" class="form-control" name="branch_name" placeholder="Enter branch name"
         value="<?php echo htmlspecialchars($formData['branch_name'] ?? ''); ?>" required>
       </div>
      </div>

      <!-- Salesperson & Payment Information -->
      <div class="row mt-3">
       <div class="col-md-3">
        <label class="form-label">Salesperson ID</label>
        <input type="text"
         class="form-control <?php echo (!empty($error) && empty($formData['salesperson_id']) ? 'is-invalid' : ''); ?>"
         name="salesperson_id" placeholder="Enter salesperson ID"
         value="<?php echo htmlspecialchars($formData['salesperson_id'] ?? ''); ?>" required>
       </div>
       <div class="col-md-3">
        <label class="form-label">Salesperson Name</label>
        <input type="text"
         class="form-control <?php echo (!empty($error) && empty($formData['salesperson_name']) ? 'is-invalid' : ''); ?>"
         name="salesperson_name" placeholder="Enter salesperson name"
         value="<?php echo htmlspecialchars($formData['salesperson_name'] ?? ''); ?>" required>
       </div>

       <div class="col-md-3">
        <label class="form-label">Job ID</label>
        <input type="text" class="form-control" name="job_id" placeholder="Enter job ID"
         value="<?php echo htmlspecialchars($formData['job_id'] ?? ''); ?>" required>
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

      <!-- Items Purchased -->
      <h6 class="mt-4">Items Purchased</h6>
      <div id="alertSuccess" class="alert alert-success d-none">Inventory added successfully!</div>

      <table class="table table-bordered table-sm" id="itemTable">
       <thead class="table-light">
        <tr>
         <th>Item ID</th>
         <th>Item_Description</th>
         <th>Qty</th>
         <th>Unit Price</th>
         <th>Subtotal</th>
         <th><button type="button" class="btn btn-sm btn-success" onclick="addRow()">Add Raw</button></th>
        </tr>
       </thead>
       <tbody>
        <tr>
         <td><input type="text" name="item_id[]" class="form-control form-control-sm" required></td>
         <td><input type="text" name="item_description[]" class="form-control form-control-sm" required></td>
         <td><input type="number" name="qty[]" class="form-control form-control-sm qty" oninput="updateTotals()"
           required>
         </td>
         <td><input type="number" name="unit_price[]" class="form-control form-control-sm unit-price"
           oninput="updateTotals()" required></td>
         <td><input type="text" name="subtotal[]" class="form-control form-control-sm subtotal" readonly></td>
         <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Remove Raw</button></td>
        </tr>
       </tbody>
      </table>

      <!-- Summary Section -->
      <div class="row g-2 mt-2">
       <div class="col-md-4 offset-md-8">
        <table class="table table-borderless table-sm">
         <tr>
          <td class="text-end">total_sales_before_vat:</td>
          <td>
           <input type="text" id="total_sales_before_vat" name="total_sales_before_vat"
            class="form-control form-control-sm text-end" readonly>
          </td>
         </tr>
         <tr>
          <td class="text-end">VAT (15%):</td>
          <td>
           <input type="text" id="vat" name="vat" class="form-control form-control-sm text-end" readonly>
          </td>
         </tr>
         <tr>
          <td class="text-end fw-bold">total_sales_after_vat:</td>
          <td>
           <input type="text" id="total_sales_after_vat" name="total_sales_after_vat"
            class="form-control form-control-sm text-end fw-bold" readonly>
          </td>
         </tr>
        </table>
        <label class="form-label">Amount in Words:</label>
        <textarea id="amountInWords" class="form-control form-control-sm" rows="2" readonly></textarea>
       </div>
      </div>

      <!-- JavaScript Section -->
      <script>
      // Convert number to words including cents (Birr and Cents)
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
        if (n < 1000000) return numToWords(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 ? ' ' + numToWords(n %
         1000) : '');
        if (n < 1000000000) return numToWords(Math.floor(n / 1000000)) + ' Million' + (n % 1000000 ? ' ' + numToWords(
         n % 1000000) : '');
        if (n < 1000000000000) return numToWords(Math.floor(n / 1000000000)) + ' Billion' + (n % 1000000000 ? ' ' +
         numToWords(n % 1000000000) : '');
        return 'Amount too large';
       }

       amount = parseFloat(amount);
       if (isNaN(amount)) return 'Invalid amount';

       const parts = amount.toFixed(2).split('.');
       const birr = parseInt(parts[0]);
       const cents = parseInt(parts[1]);

       let words = '';
       if (birr > 0) words += numToWords(birr) + ' Birr';
       if (cents > 0) words += (words ? ' and ' : '') + numToWords(cents) + ' Cents';
       if (!words) words = 'Zero Birr';

       return words.charAt(0).toUpperCase() + words.slice(1) + ' only';
      }

      // Format number to currency string with commas and 2 decimals
      function formatCurrency(amount) {
       return parseFloat(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
       });
      }

      // Update totals and amount in words
      function updateTotals() {
       let total = 0;
       document.querySelectorAll('#itemTable tbody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const price = parseFloat(row.querySelector('.unit-price').value) || 0;
        const total_sales_before_vat = qty * price;
        row.querySelector('.total_sales_before_vat').value = formatCurrency(total_sales_before_vat);
        total += subtotal;
       });

       const vat = total * 0.15;
       const grandTotal = total + vat;

       document.getElementById("total_sales_before_vat").value = formatCurrencyl);
      document.getElementById("vat").value = formatCurrency(vat);
      document.getElementById("total_sales_after_vat").value = formatCurrency(grandTotal);
      document.getElementById("amountInWords").value = convertToWords(grandTotal);
      }

      // Add a new row to the items table
      function addRow() {
       const table = document.querySelector('#itemTable tbody');
       const newRow = table.rows[0].cloneNode(true);
       newRow.querySelectorAll('input').forEach(input => input.value = '');
       table.appendChild(newRow);
      }

      // Remove a row from the items table
      function removeRow(button) {
       const row = button.closest('tr');
       const table = document.querySelector('#itemTable tbody');
       if (table.rows.length > 1) row.remove();
       updateTotals();
      }

      // Clear form and reset totals
      function clearForm() {
       if (confirm("Clear all entered data?")) {
        document.querySelector("form").reset();
        const rows = document.querySelectorAll("#itemTable tbody tr");
        rows.forEach((row, index) => index > 0 && row.remove());
        updateTotals();
       }
      }

      // Print invoice hiding buttons
      function printFullInvoice() {
       const buttons = document.querySelectorAll('.no-print, button');
       buttons.forEach(btn => btn.style.display = 'none');

       window.print();

       setTimeout(() => {
        buttons.forEach(btn => btn.style.display = 'inline-block');
       }, 1000);
      }
      </script>

      <style>
      @media print {

       .no-print,
       .no-print * {
        display: none !important;
       }
      }
      </style>
      <!-- Signature Section -->


      <div class="row mt-4">
       <div class="col-md-6 text-center">
        <p class="mt-4" style="border-bottom: 1px solid #000; width: 80%; margin: 0 auto;">&nbsp;</p>
        <strong>Prepared By</strong>
       </div>
       <div class="col-md-6 text-center">
        <p class="mt-4" style="border-bottom: 1px solid #000; width: 80%; margin: 0 auto;">&nbsp;</p>
        <strong>Approved By</strong>
       </div>
      </div>