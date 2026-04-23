<?php include 'header.php'; ?>

<?php
session_start();
include 'db_connect.php'; // Database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate input fields
    $sales_order_no   = $_POST['sales_order_no'] ?? ''; 
    $salesperson_id   = isset($_POST['salesperson_id']) ? intval($_POST['salesperson_id']) : 0;
    $salesperson_name = $_POST['salesperson_name'] ?? 'Unknown';
    $reference        = $_POST['reference'] ?? '';
    $invoice_no       = $_POST['invoice_no'] ?? '';
    $uom              = $_POST['uom'] ?? '';
    $item_id          = $_POST['item_id'] ?? '';
    $qty        = isset($_POST['qty']) ? intval($_POST['qty']) : 0;
    $GL_account       = $_POST['GL_account'] ?? '';
    $unit_price       = isset($_POST['unit_price']) ? floatval($_POST['unit_price']) : 0.0;
    $total_sales      = isset($_POST['total_sales']) ? floatval($_POST['total_sales']) : 0.0;
    $total_profit     = isset($_POST['total_profit']) ? floatval($_POST['total_profit']) : 0.0;
    $date             = !empty($_POST['date']) ? $_POST['date'] : date('Y-m-d');

    // Ensure branch_id is an integer
    $branch_id = isset($_POST['branch_id']) && !is_array($_POST['branch_id']) ? intval($_POST['branch_id']) : 0;

    // Ensure customer_id is an integer
    $customer_id   = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : NULL;
    $customer_name = $_POST['customer_name'] ?? 'Unknown';
    $payment_method = $_POST['payment_method'] ?? 'Not Specified';

    // Ensure job_id is not an array
    $job_id = isset($_POST['job_id']) && !is_array($_POST['job_id']) ? $_POST['job_id'] : NULL;

    $section = $_POST['section'] ?? '';

    // Convert array to string (if needed)
    $item_id = is_array($_POST['item_id']) ? implode(', ', $_POST['item_id']) : $_POST['item_id'];
    $item_description = is_array($_POST['item_description']) ? implode(', ', $_POST['item_description']) : $_POST['item_description'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO sales 
        (sales_order_no, salesperson_id, salesperson_name, reference, invoice_no, 
         item_id, item_description, uom, product_id, qty, GL_account, 
         unit_price, total_sales, total_profit, date, branch_id, 
         customer_id, customer_name, payment_method, job_id, section)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Error in preparing the statement: " . $conn->error);
    }

    // Bind parameters (corrected types)
    $stmt->bind_param("sisssssssisdddsisssss", 
        $sales_order_no, $salesperson_id, $salesperson_name, $reference, $invoice_no, 
        $item_id, $item_description, $uom, $product_id, $qty, $GL_account, 
        $unit_price, $total_sales, $total_profit, $date, $branch_id, 
        $customer_id, $customer_name, $payment_method, $job_id, $section
    );

    // Execute the statement
    if ($stmt->execute()) {
        echo "<p style='color:green;'>Sale added successfully.</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

} else {
    echo "<p style='color:red;'>Invalid request.</p>";
}
?>


<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
 background-color: #f4f4f9;
}

.card {
 border-radius: 10px;
 box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.footer {
 margin-top: 20px;
 background-color: #343a40;
 color: white;
 padding: 10px;
 text-align: center;
}

.form-label {
 font-weight: bold;
}

.table-excel {
 width: 100%;
 border-collapse: collapse;
 margin-top: 20px;
 background-color: #fff;
 border: 1px solid #ddd;
}

.table-excel th,
.table-excel td {
 border: 1px solid #ddd;
 padding: 10px;
 text-align: left;
 font-size: 14px;
}

.table-excel th {
 background-color: #f4f4f9;
 font-weight: bold;
}

.table-excel tr:nth-child(even) {
 background-color: #f9f9f9;
}

.table-excel tr:hover {
 background-color: #f1f1f1;
}

.table-excel input[type="number"],
.table-excel input[type="text"] {
 width: 60%;
 padding: 5px;
 font-size: 14px;
}

.btn-back {
 margin-top: 20px;
}

.row-input {
 margin-bottom: 15px;
}

.table-excel input {
 width: 100%;
}

.summary-section {

 background-color: #f8f9fa;
 padding: 10px;
 margin-top: 20px;
 border-radius: 5px;
 box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.summary-section h5 {
 font-weight: bold;
}

.summary-section p {
 font-size: 12px;
}
</style>
</head>

<body>
 <div class="container my-4">
  <h1 class="text-center">ABC Company</h1>
  <h1 class="text-center">Sales Invoice</h1>
  <h6 class="text-center">
   <Address> Bole , XXXXXX,Telephone , email </Address>
  </h6>

  <!-- Display success or error message -->
  <?php if (isset($success_message)): ?>
  <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
  <?php elseif (isset($error_message)): ?>
  <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
  <?php endif; ?>

  <!-- Sale Form -->
  <div class="card p-4">
   <form action="add_sale.php" method="POST" id="sales_form">
    <!-- Sales General Information in One Row -->
    <div class="row row-input">
     <div class="col-md-2">
      <label for="sales_order_no" class="form-label">Sales Order No.</label>
      <input type="text" class="form-control" id="sales_order_no" name="sales_order_no">
     </div>
     <div class="col-md-2">
      <label for="sales_invoice_no" class="form-label">Sales Invoice No.</label>
      <input type="text" class="form-control" id="sales_invoice_no" name="sales_invoice_no" required>
     </div>
     <div class="col-md-2">
      <label for="reference" class="form-label">Reference</label>
      <input type="text" class="form-control" id="reference" name="reference" required>
     </div>

     <div class="col-md-2">
      <label for="invoiceDate" class="form-label">Invoice Date:</label>
      <input type="date" class="form-control" id="invoiceDate" required>
     </div>

     <!-- Customer Information in One Row -->
     <div class="row row-input">

      <div class="col-md-2">
       <label for="customer_id" class="form-label">Customer ID</label>
       <input type="text" class="form-control" id="customer_id" name="customer_id" required>
      </div>
      <div class="col-md-2">
       <label for="customer_Company_name" class="form-label">Customer Company Name</label>
       <input type="text" class="form-control" id="customer_company_name" name="customer_company_name" required>
      </div>
      <div class="col-md-3">
       <label for="customer_name" class="form-label">Customer Name</label>
       <input type="text" class="form-control" id="customer_name" name="customer_name" required>
      </div>
      <div class="col-md-2">
       <label for="customer_TIN No." class="form-label">Customer TIN No</label>
       <input type="text" class="form-control" id="customer_TIN No" name="customer_TIN No" required>
      </div>
      <div class="col-md-2">
       <label for="customer_Telephone No." class="form-label">Customer Telephone No</label>
       <input type="text" class="form-control" id="customer_Telephone No." name="customer_Telephone No." required>
      </div>
      <div class="row row-input">
       <div class="col-md-2">
        <label for="salesperson_id" class="form-label">Sales Person ID</label>
        <input type="text" class="form-control" id="salesperson_id" name="salesperson_id" required>
       </div>

       <div class="col-md-2">
        <label for="salesperson_name" class="form-label">Sales Person Name</label>
        <input type="text" class="form-control" id="salesperson_name" name="salesperson_name" required>
       </div>
       <div class="col-md-2">
        <label for="payment_method" class="form-label">Payment Method</label>
        <select class="form-control" id="payment_method" name="payment_method" required>
         <option value="cash">Cash</option>
         <option value="cash">Cheque</option>
         <option value="bank">Bank Transfer</option>
         <option value="cash">Other</option>
        </select>
       </div>
      </div>

      <!-- Item Details Table (Excel-style) -->

      <script>
      function calculateTotal(input) {
       const row = input.closest("tr");
       const item_id = parseFloat(row.querySelector('[name="item_id[]"]').value) || 0;
       const qty = parseFloat(row.querySelector('[name="qty[]"]').value) || 0;
       const unitPrice = parseFloat(row.querySelector('[name="unit_price[]"]').value) || 0;
       const totalSales = qty* unitPrice;
       row.querySelector('[name="total_sales[]"]').value = totalSales.toFixed(2);
       updateSalesSummary();
      }

      function addRow() {
       const table = document.getElementById("item_table").getElementsByTagName("tbody")[0];
       const newRow = table.insertRow();

       newRow.innerHTML = `
                <td><input type="text" class="form-control" name="item_id[]" required></td>
                <td><input type="text" class="form-control" name="item_description[]" required></td>
                <td><input type="text" class="form-control" name="gl_account[]" required></td>
                <td><input type="number" class="form-control" name="qty[]" step="1" min="1" oninput="calculateTotal(this)" required></td>
                <td><input type="number" class="form-control" name="unit_price[]" step="0.01" min="0.01" oninput="calculateTotal(this)" required></td>
                <td><input type="number" class="form-control" name="total_sales[]" readonly></td>
                <td><input type="text" class="form-control" name="job_id[]"></td>
                <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
            `;
      }

      function removeRow(button) {
       const row = button.closest("tr");
       row.remove();
       updateSalesSummary();
      }

      function updateSalesSummary() {
       let totalBeforeVAT = 0;
       document.querySelectorAll('[name="total_sales[]"]').forEach(input => {
        totalBeforeVAT += parseFloat(input.value) || 0;
       });

       const vatAmount = totalBeforeVAT * 0.15;
       const totalWithVAT = totalBeforeVAT + vatAmount;

       document.getElementById("total_sales_before_vat").textContent = totalBeforeVAT.toFixed(2);
       document.getElementById("vat_amount").textContent = vatAmount.toFixed(2);
       document.getElementById("sales_with_vat").textContent = totalWithVAT.toFixed(2);

       document.getElementById("amount_in_words").textContent = `Total in Words: ${toWords(totalWithVAT)} Birr only.`;
      }

      function toWords(amount) {
       const ones = [
        "", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine",
        "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen",
        "Seventeen", "Eighteen", "Nineteen",
       ];
       const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
       const scales = ["", "Thousand", "Million", "Billion"];

       if (amount === 0) return "Zero";

       const words = [];
       const numStr = Math.floor(amount).toString();
       const numParts = numStr.match(/.{1,3}(?=(.{3})*$)/g).reverse();

       numParts.forEach((part, index) => {
        if (parseInt(part) === 0) return;

        let str = "";
        const hundreds = Math.floor(part / 100);
        const remainder = part % 100;
        if (hundreds > 0) str += ones[hundreds] + " Hundred ";
        if (remainder < 20) str += ones[remainder];
        else str += tens[Math.floor(remainder / 10)] + " " + ones[remainder % 10];

        words.push(str + (scales[index] ? " " + scales[index] : ""));
       });

       return words.reverse().join(" ").trim();
      }
      </script>
      </head>

      <body>
       <div class="container mt-3">


        <table id="item_table" class="table table-bordered">
         <thead>
          <tr>
           <th>Item ID</th>
           <th>Description</th>
           <th>GL Account</th>
           <th>Quantity</th>
           <th>Unit Price</th>
           <th>Total Sales</th>
           <th>Job ID</th>
           <th>Action</th>
          </tr>
         </thead>
         <tbody>
          <!-- Rows will be added dynamically -->
         </tbody>
        </table>

        <button type="button" class="btn btn-primary mb-4" onclick="addRow()">Add Item</button>

        <div>
         <p><strong>Total Before VAT:</strong> Birr <span id="total_sales_before_vat"> 0.00</span></p>
         <p><strong>VAT (15%):</strong> Birr <span id="vat_amount"> 0.00</span></p>
         <p><strong>Total Sales with VAT:</strong> Birr <span id="sales_with_vat"> 0.00</span></p>
         <p id="amount_in_words"><strong>Total in Words: </strong> Zero Birr only.</p>
        </div>
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


     <div class="text-center mt-4">
      <button type="submit" class="btn btn-primary">Save Sales</button>
     </div>

</body>

</html>
<style>
body {
 background-color: #f8f9fa;
 font-family: Arial, sans-serif;
}

.container {
 max-width: 1000px;
 margin: 20px auto;
 background: #fff;
 padding: 20px;
 box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1,
h2 {
 text-align: center;
 margin-bottom: 20px;
}

table {
 width: 100%;
 border-collapse: collapse;
 margin-bottom: 20px;
}

table th,
table td {
 border: 1px solid #dee2e6;
 padding: 20px;
 text-align: center;
}

table th {
 background-color: #f1f1f1;
}

.signature {
 display: flex;
 justify-content: space-between;
 margin-top: 30px;
}

.signature div {
 width: 30%;
 text-align: center;
}
</style>