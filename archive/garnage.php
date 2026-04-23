sales Invoice #<?php
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

staf history #
<?php

include 'db_connection.php';

if (!isset($_GET['staff_id'])) {
    die("Error: Staff ID is missing.");
}

$staff_id = $_GET['staff_id'];

// Fetch staff details including image
$staff_query = "SELECT staff_id, first_name, middle_name, last_name, profile_image, email, telephone, position, department, salary, hire_date, termination_date, experience, skills FROM staff WHERE staff_id = ?";
$staff_stmt = $conn->prepare($staff_query);

if (!$staff_stmt) {
    die("SQL Error: " . $conn->error);
}

$staff_stmt->bind_param("i", $staff_id);
$staff_stmt->execute();
$staff_result = $staff_stmt->get_result();
$staff = $staff_result->fetch_assoc();
$staff_stmt->close();

if (!$staff) {
    echo "<script>
        alert('Staff not found.');
        window.location.href = 'manage_staff.php';
    </script>";
    exit();
}

// Fetch staff performance, rating, and comments
$performance_query = "SELECT * FROM staff WHERE staff_id = ?";
$performance_stmt = $conn->prepare($performance_query);

if (!$performance_stmt) {
    die("SQL Error: " . $conn->error);
}

$performance_stmt->bind_param("i", $staff_id);
$performance_stmt->execute();
$performance_result = $performance_stmt->get_result();
$performance = $performance_result->fetch_assoc();
$performance_stmt->close();

// Check if the form is submitted for updating
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $address = $_POST['address'];
    $role = $_POST['role'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $hire_date = $_POST['hire_date'];
    $termination_date = $_POST['termination_date'];
    $experience = $_POST['experience'];
    $skills = $_POST['skills'];
    $performance_rating = $_POST['rating'];
    $comments = $_POST['comments'];

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $upload_dir = 'uploads/';
        $file_name = $_FILES['profile_image']['name'];
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $new_image_name = time() . '_' . $file_name;
        $upload_path = $upload_dir . $new_image_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $update_query = "UPDATE staff SET 
                first_name = ?, 
                middle_name = ?, 
                last_name = ?, 
                email = ?, 
                telephone = ?, 
                address = ?, 
                role = ?, 
                department = ?, 
                position = ?, 
                salary = ?, 
                hire_date = ?, 
                termination_date = ?, 
                experience = ?, 
                skills = ?, 
                profile_image = ? 
                WHERE staff_id = ?";
            $update_stmt = $conn->prepare($update_query);
            if (!$update_stmt) {
                die("SQL Error: " . $conn->error);
            }

            $update_stmt->bind_param(
                "sssssssssssssssi",
                $first_name,
                $middle_name,
                $last_name,
                $email,
                $telephone,
                $address,
                $role,
                $department,
                $position,
                $salary,
                $hire_date,
                $termination_date,
                $experience,
                $skills,
                $new_image_name,
                $staff_id
            );
        } else {
            echo "<script>
                alert('Failed to upload image.');
                window.location.href = 'manage_staff.php';
            </script>";
            exit();
        }
    } else {
        $update_query = "UPDATE staff SET 
            first_name = ?, 
            middle_name = ?, 
            last_name = ?, 
            email = ?, 
            telephone = ?, 
            address = ?, 
            role = ?, 
            department = ?, 
            position = ?, 
            salary = ?, 
            hire_date = ?, 
            termination_date = ?, 
            experience = ?, 
            skills = ? 
            WHERE staff_id = ?";
        $update_stmt = $conn->prepare($update_query);
        if (!$update_stmt) {
            die("SQL Error: " . $conn->error);
        }

        $update_stmt->bind_param(
            "sssssssssssssi",
            $first_name,
            $middle_name,
            $last_name,
            $email,
            $telephone,
            $address,
            $role,
            $department,
            $position,
            $salary,
            $hire_date,
            $termination_date,
            $experience,
            $skills,
            $staff_id
        );
    }

    // Update performance data
    $performance_query = "UPDATE staff_performance SET performance_rating = ?, comments = ? WHERE staff_id = ?";
    $performance_stmt = $conn->prepare($performance_query);
    if (!$performance_stmt) {
        die("SQL Error: " . $conn->error);
    }

    $performance_stmt->bind_param("ssi", $performance_rating, $comments, $staff_id);
    if ($performance_stmt->execute()) {
        echo "<script>
            alert('Staff details and performance updated successfully.');
            window.location.href = 'manage_staff.php?staff_id=" . $staff_id . "';
        </script>";
    } else {
        echo "<script>
            alert('Failed to update performance.');
            window.location.href = 'manage_staff.php';
        </script>";
    }
    $performance_stmt->close();
    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>View Staff History</title>
 <link rel="stylesheet" href="assets/css/bootstrap.min.css">
 <style>
 body {
  background-color: #f4f7fc;
  font-family: 'Arial', sans-serif;
 }

 .header {
  background-color: #4e73df;
  color: white;
  text-align: center;
  padding: 20px;
  font-size: 28px;
  font-weight: bold;
  margin-bottom: 30px;
 }

 .navbar {
  margin-bottom: 30px;
 }

 .form-container {
  max-width: 350px;
  margin: auto;
  background: white;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
  margin-top: 10px;
 }

 .form-container h3 {
  color: #4e73df;
  margin-bottom: 20px;
 }

 .form-container input,
 .form-container select,
 .form-container textarea,
 .form-container button {
  font-size: 16px;
 }

 .form-container .btn-custom {
  background-color: #4e73df;
  color: white;
  font-weight: bold;
  padding: 12px;
  border-radius: 8px;
  width: 100%;
  text-align: center;
 }

 .form-container .btn-custom:hover {
  background-color: #375a8c;
 }

 .alert {
  margin-top: 15px;
  text-align: center;
 }

 .footer {
  background-color: #343a40;
  color: white;
  text-align: center;
  padding: 15px;
  margin-top: 50px;
 }

 .form-group label {
  font-weight: bold;
 }

 .form-group textarea {
  resize: vertical;
 }

 .profile-image {
  border-radius: 16px;
  width: 50px;
  height: 50px;
  object-fit: cover;
  margin-bottom: 15px;
 }

 .file-upload {
  background-color: #f4f7fc;
  padding: 10px;
  border-radius: 8px;
  display: inline-block;
  cursor: pointer;
 }
 </style>
</head>

<body>

 <!-- Header -->
 <div class="header">
  View Staff History- Admin Panel
 </div>

 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
   <a class="navbar-brand fw-bold" href="admin_dashboard.php">Admin Dashboard</a>
   <div class="ml-auto">
    <a href="manage_staff.php" class="btn btn-light me-2">Manage Staff</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
   </div>
  </div>
 </nav>
 <div class="form-group">

  <input type="file" name="profile_image" accept="image/*">

  <label></label>
  <img src="<?= isset($staff['profile_image']) ? $staff['profile_image'] :'' ?>" class="profile-image">

 </div>
 <form action="" method="POST" enctype="multipart/form-data">
  <label for="first_name">First Name:</label>
  <input type="text" name="first_name" value="<?= htmlspecialchars($staff['first_name']) ?>" required><br>

  <label for="middle_name">Middle Name:</label>
  <input type="text" name="middle_name" value="<?= htmlspecialchars($staff['middle_name']) ?>"><br>

  <label for="last_name">Last Name:</label>
  <input type="text" name="last_name" value="<?= htmlspecialchars($staff['last_name']) ?>" required><br>

  <label for="email">Email:</label>
  <input type="email" name="email" value="<?= htmlspecialchars($staff['email']) ?>" required><br>

  <label for="telephone">Telephone:</label>
  <input type="text" name="telephone" value="<?= htmlspecialchars($staff['telephone']) ?>" required><br>

  <div class="form-group">
   <label>Address</label>
   <input type="text" name="address" class="form-control"
    value="<?= isset($staff['address']) ? $staff['address'] : '' ?>" required>
  </div>
  <div class="form-group">
   <label for="role">Role</label>
   <select class="form-control" name="role" required>
    <option value="staff">Staff</option>
    <option value="salesperson">Salesperson</option> <!-- Added Salesperson role -->


    <label for="department">Department:</label>
    <input type="text" name="department" value="<?= htmlspecialchars($staff['department']) ?>"><br>

    <label for="position">position:</label>
    <input type="text" name="position" value="<?= htmlspecialchars($staff['position']) ?>"><br>

    <label for="salary">Salary:</label>
    <input type="text" name="salary" value="<?= htmlspecialchars($staff['salary']) ?>"><br>

    <label for="hire_date">Hire Date:</label>
    <input type="date" name="hire_date" value="<?= htmlspecialchars($staff['hire_date']) ?>"><br>

    <label for="termination_date">Termination Date:</label>
    <input type="date" name="termination_date" value="<?= htmlspecialchars($staff['termination_date']) ?>"><br>

    <label for="experience">Experience:</label>
    <input type="text" name="experience" value="<?= htmlspecialchars($staff['experience']) ?>"><br>

    <label for="skills">Skills:</label>
    <input type="text" name="skills" value="<?= htmlspecialchars($staff['skills']) ?>"><br>

    <label for="profile_image">Profile Image:</label>
    <input type="file" name="profile_image"><br>

    <!-- Performance Section -->
    <h3>Performance</h3>
    <label for="rating">Rating (1-5):</label>
    <input type="number" name="rating" min="1" max="5" value="<?= htmlspecialchars($performance['rating']) ?>"><br>

    <label for="comments">Comments:</label>
    <textarea name="comments" rows="4"><?= htmlspecialchars($performance['comments']) ?></textarea><br>

    <button type="submit">Update Staff</button>
 </form>
</body>

</html>
kkkkkkkkkkkkk

kkkk
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
 <meta name="description" content="POS - Bootstrap Admin Template">
 <meta name="keywords" content="admin, dashboard, responsive, template, projects">
 <meta name="author" content="Dreamguys - Bootstrap Admin Template">
 <meta name="robots" content="noindex, nofollow">
 <title>Dreams POS Admin Template</title>

 <!-- Favicon -->
 <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg">

 <!-- CSS Files -->
 <link rel="stylesheet" href="assets/css/bootstrap.min.css">
 <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
 <link rel="stylesheet" href="assets/css/animate.css">
 <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
 <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
 <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
 <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
 <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
 <div id="global-loader">
  <div class="whirly-loader"></div>
 </div>

 <div class="main-wrapper">
  <!-- Header -->
  <div class="header">
   <div class="header-left">
    <a href="index.html" class="logo">
     <img src="assets/img/logo.png" alt="Logo">
    </a>
    <a href="index.html" class="logo-small">
     <img src="assets/img/logo-small.png" alt="Logo Small">
    </a>
    <a id="toggle_btn" href="javascript:void(0);"></a>
   </div>
   <a id="mobile_btn" class="mobile_btn" href="#sidebar">
    <span class="bar-icon">
     <span></span>
     <span></span>
     <span></span>
    </span>
   </a>
   <ul class="nav user-menu">
    <!-- Search -->
    <li class="nav-item">
     <div class="top-nav-search">
      <a href="javascript:void(0);" class="responsive-search"><i class="fa fa-search"></i></a>
      <form action="#">
       <div class="searchinputs">
        <input type="text" placeholder="Search Here ...">
        <div class="search-addon">
         <img src="assets/img/icons/closes.svg" alt="Close">
        </div>
       </div>
       <button type="submit" class="btn"><img src="assets/img/icons/search.svg" alt="Search"></button>
      </form>
     </div>
    </li>

    <!-- Notifications -->
    <li class="nav-item dropdown">
     <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
      <img src="assets/img/icons/notification-bing.svg" alt="Notifications">
      <span class="badge rounded-pill">4</span>
     </a>
     <div class="dropdown-menu notifications">
      <div class="topnav-dropdown-header">
       <span>Notifications</span>
       <a href="#" class="clear-noti">Clear All</a>
      </div>
      <div class="noti-content">
       <ul class="notification-list">
        <li class="notification-message">
         <a href="activities.html">
          <div class="media">
           <span class="avatar">
            <img src="assets/img/profiles/avatar-02.jpg" alt="User">
           </span>
           <div class="media-body">
            <p class="noti-details"><strong>John Doe</strong> added a new task <strong>Patient appointment
              booking</strong></p>
            <p class="noti-time">4 mins ago</p>
           </div>
          </div>
         </a>
        </li>
        <!-- More notifications... -->
       </ul>
      </div>
      <div class="topnav-dropdown-footer">
       <a href="activities.html">View All Notifications</a>
      </div>
     </div>
    </li>

    <!-- User Menu -->
    <li class="nav-item dropdown has-arrow">
     <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
      <span class="user-img"><img src="assets/img/profiles/avatar-01.jpg" alt="User">
       <span class="status online"></span>
      </span>
     </a>
     <div class="dropdown-menu">
      <div class="profilename">
       <div class="profileset">
        <span class="user-img"><img src="assets/img/profiles/avatar-01.jpg" alt="User"></span>
        <div class="profilesets">
         <h6>John Doe</h6>
         <span>Admin</span>
        </div>
       </div>
      </div>
      <hr>
      <a class="dropdown-item" href="profile.html"><i data-feather="user"></i> My Profile</a>
      <a class="dropdown-item" href="generalsettings.html"><i data-feather="settings"></i> Settings</a>
      <hr>
      <a class="dropdown-item logout" href="signin.html"><i data-feather="log-out"></i> Logout</a>
     </div>
    </li>
   </ul>
  </div>
  <!-- /Header -->

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
   <div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
     <ul>
      <li><a href="index.html"><img src="assets/img/icons/dashboard.svg" alt="Dashboard"> Dashboard</a></li>
      <!-- Additional sidebar items here -->
     </ul>
    </div>
   </div>
  </div>
  <!-- /Sidebar -->
 </div>

 <!-- JS Files -->
 <script src="assets/js/jquery.min.js"></script>
 <script src="assets/js/bootstrap.bundle.min.js"></script>
 <script src="assets/plugins/select2/js/select2.min.js"></script>
 <script src="assets/js/script.js"></script>
</body>

</html>
Purchase


kkk
<?php
function convertNumberToWords($number) {
    $hyphen = '-';
    $conjunction = ' and ';
    $separator = ', ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = [
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
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion'
    ];

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // Overflow
        return false;
    }

    if ($number < 0) {
        return $negative . convertNumberToWords(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int) ($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convertNumberToWords($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convertNumberToWords($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = [];
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyName = $_POST['companyName'];
    $address = $_POST['address'];
    $date = $_POST['date'];
    $invoiceNo = $_POST['invoiceNo'];
    $vat = $_POST['vat'];
    $withhold = $_POST['withhold'];
    $items = $_POST['itemId'];
    $descriptions = $_POST['description'];
    $uoms = $_POST['uom'];
    $qtys = $_POST['qty'];
    $unitCosts = $_POST['unitCost'];

    $invoiceDetails = [
        'Company Name' => $companyName,
        'Address' => $address,
        'Date' => $date,
        'Invoice No' => $invoiceNo,
        'Sub Total' => 0,
        'VAT (%)' => $vat,
        'VAT Amount' => 0,
        'Withhold' => $withhold,
        'Net Total' => 0,
        'Items' => []
    ];

    $subTotal = 0;

    foreach ($items as $key => $item) {
        $quantity = $qtys[$key];
        $unitCost = $unitCosts[$key];
        $totalCost = $quantity * $unitCost;

        $invoiceDetails['Items'][] = [
            'Item ID' => $item,
            'Description' => $descriptions[$key],
            'UoM' => $uoms[$key],
            'Quantity' => $quantity,
            'Unit Cost' => number_format($unitCost, 2),
            'Total Cost' => number_format($totalCost, 2),
        ];

        $subTotal += $totalCost;
    }

    $vatAmount = $subTotal * ($vat / 100);
    $netTotal = $subTotal + $vatAmount - $withhold;

    $invoiceDetails['Sub Total'] = number_format($subTotal, 2);
    $invoiceDetails['VAT Amount'] = number_format($vatAmount, 2);
    $invoiceDetails['Net Total'] = number_format($netTotal, 2);

    $invoiceDetails['Net Total in Words'] = convertNumberToWords($netTotal);

    echo "<pre>";
    print_r($invoiceDetails);
    echo "</pre>";
}
?>

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Purchase Form</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

 <div class="container mt-5">
  <h2 class="mb-4">Purchase Order Form</h2>

  <!-- Form Start -->
  <form id="purchaseForm" action="#" method="POST">

   <div class="row mb-3">
    <label for="supplierName" class="col-sm-2 col-form-label">Supplier Name</label>
    <div class="col-sm-10">
     <select id="supplierName" name="supplierName" class="form-select" required>
      <option value="" selected disabled>Select Supplier</option>
      <option value="Supplier A">Supplier A</option>
      <option value="Supplier B">Supplier B</option>
     </select>
    </div>
   </div>

   <div class="row mb-3">
    <label for="purchaseDate" class="col-sm-2 col-form-label">Purchase Date</label>
    <div class="col-sm-10">
     <input type="date" id="purchaseDate" name="purchaseDate" class="form-control" required>
    </div>
   </div>

   <div class="row mb-3">
    <label for="referenceNo" class="col-sm-2 col-form-label">Reference No.</label>
    <div class="col-sm-10">
     <input type="text" id="referenceNo" name="referenceNo" class="form-control" placeholder="Enter Reference No.">
    </div>
   </div>

   <div class="table-responsive mb-3">
    <table class="table table-bordered">
     <thead>
      <tr>
       <th>Product</th>
       <th>Quantity</th>
       <th>Price</th>
       <th>Discount</th>
       <th>Tax (%)</th>
       <th>Total</th>
       <th>Actions</th>
      </tr>
     </thead>
     <tbody>
      <tr>
       <td><input type="text" name="product[]" class="form-control" placeholder="Product Name" required></td>
       <td><input type="number" name="quantity[]" class="form-control" value="1" min="1" oninput="calculateTotal(this)"
         required></td>
       <td><input type="number" name="price[]" class="form-control" oninput="calculateTotal(this)" required></td>
       <td><input type="number" name="discount[]" class="form-control" value="0" oninput="calculateTotal(this)"></td>
       <td><input type="number" name="tax[]" class="form-control" value="0" oninput="calculateTotal(this)"></td>
       <td><input type="text" name="total[]" class="form-control" readonly></td>
       <td><button type="button" class="btn btn-danger" onclick="deleteRow(this)">Delete</button></td>
      </tr>
     </tbody>
    </table>
   </div>

   <div class="text-end mb-3">
    <label for="grandTotal" class="form-label">Grand Total ($):</label>
    <input type="text" id="grandTotal" class="form-control" readonly>
   </div>

   <div class="row mb-3">
    <label for="ledgerAccount" class="col-sm-2 col-form-label">Ledger Account</label>
    <div class="col-sm-10">
     <select id="ledgerAccount" name="ledgerAccount" class="form-select" required>
      <option value="" selected disabled>Select Account</option>
      <option value="Cash/Bank">Cash/Bank</option>
      <option value="Accounts Payable">Accounts Payable</option>
      <option value="Expense Account">Expense Account</option>
      <option value="Liability Account">Liability Account</option>
     </select>
    </div>
   </div>

   <div class="mb-3">
    <label for="supportingDocs" class="form-label">Attach Supporting Documents</label>
    <input type="file" id="supportingDocs" name="supportingDocs" class="form-control">
   </div>

   <div class="text-center">
    <button type="submit" class="btn btn-primary">Submit</button>
   </div>
  </form>
  <!-- Form End -->

 </div>

 <script>
 // Add product row dynamically
 function addRow() {
  const table = document.querySelector('.table tbody');
  const newRow = table.rows[0].cloneNode(true);
  newRow.querySelectorAll('input').forEach(input => input.value = '');
  table.appendChild(newRow);
 }

 // Delete product row
 function deleteRow(button) {
  const row = button.closest('tr');
  if (row.parentElement.children.length > 1) row.remove();
  calculateTotal();
 }

 // Calculate total for each product and update grand total
 function calculateTotal(element) {
  const row = element.closest('tr');
  const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value || 0);
  const price = parseFloat(row.querySelector('[name="price[]"]').value || 0);
  const discount = parseFloat(row.querySelector('[name="discount[]"]').value || 0);
  const tax = parseFloat(row.querySelector('[name="tax[]"]').value || 0);

  let total = (quantity * price) - discount;
  total += total * (tax / 100);

  row.querySelector('[name="total[]"]').value = total.toFixed(2);

  let grandTotal = 0;
  document.querySelectorAll('[name="total[]"]').forEach(input => {
   grandTotal += parseFloat(input.value || 0);
  });
  document.getElementById('grandTotal').value = grandTotal.toFixed(2);
 }

 // Submit validation for required fields
 document.getElementById('purchaseForm').addEventListener('submit', function(event) {
  const ledgerAccount = document.getElementById('ledgerAccount').value;
  if (!ledgerAccount || !['Cash/Bank', 'Accounts Payable', 'Expense Account', 'Liability Account'].includes(
    ledgerAccount)) {
   alert('Please select a valid ledger account.');
   event.preventDefault();
  }
 });
 </script>

</body>

</html