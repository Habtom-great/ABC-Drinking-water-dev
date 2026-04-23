<?php if (isset($success_message)): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
<?php elseif (isset($error_message)): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>

<!-- Sale Form -->
<div class="card shadow-lg p-5 mb-4 bg-light border-primary rounded">
 <form action="add_sale.php" method="POST" id="sales_form">
  <h3 class="text-center mb-4 text-primary">Sales Form</h3>

  <!-- Sales General Information in One Row -->
  <div class="row g-3 mb-4">
   <div class="col-md-2">
    <label for="sales_order_no" class="form-label">Sales Order No.</label>
    <input type="text" class="form-control" id="sales_order_no" name="sales_order_no" required>
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
  </div>

  <!-- Customer Information in One Row -->
  <div class="row g-3 mb-4">
   <div class="col-md-2">
    <label for="customer_id" class="form-label">Customer ID</label>
    <input type="text" class="form-control" id="customer_id" name="customer_id" required>
   </div>
   <div class="col-md-3">
    <label for="customer_company_name" class="form-label">Customer Company Name</label>
    <input type="text" class="form-control" id="customer_company_name" name="customer_company_name" required>
   </div>
   <div class="col-md-3">
    <label for="customer_name" class="form-label">Customer Name</label>
    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
   </div>
   <div class="col-md-2">
    <label for="customer_TIN_No" class="form-label">Customer TIN No</label>
    <input type="text" class="form-control" id="customer_TIN_No" name="customer_TIN_No" required>
   </div>
   <div class="col-md-2">
    <label for="customer_Telephone_No" class="form-label">Customer Telephone No</label>
    <input type="text" class="form-control" id="customer_Telephone_No" name="customer_Telephone_No" required>
   </div>
  </div>

  <!-- Sales Person Information in One Row -->
  <div class="row g-3 mb-4">
   <div class="col-md-3">
    <label for="salesperson_id" class="form-label">Sales Person ID</label>
    <input type="text" class="form-control" id="salesperson_id" name="salesperson_id" required>
   </div>
   <div class="col-md-3">
    <label for="salesperson_name" class="form-label">Sales Person Name</label>
    <input type="text" class="form-control" id="salesperson_name" name="salesperson_name" required>
   </div>
   <div class="col-md-3">
    <label for="payment_method" class="form-label">Payment Method</label>
    <select class="form-control" id="payment_method" name="payment_method" required>
     <option value="cash">Cash</option>
     <option value="cheque">Cheque</option>
     <option value="bank">Bank Transfer</option>
     <option value="other">Other</option>
    </select>
   </div>
  </div>

  <!-- Item Details Table -->
  <div class="table-responsive mb-4">
   <table id="item_table" class="table table-bordered table-striped">
    <thead class="table-dark">
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
  </div>

  <div class="sales-summary mb-4">
   <p><strong>Total Before VAT:</strong> Birr <span id="total_sales_before_vat">0.00</span></p>
   <p><strong>VAT (15%):</strong> Birr <span id="vat_amount">0.00</span></p>
   <p><strong>Total Sales with VAT:</strong> Birr <span id="sales_with_vat">0.00</span></p>
   <p id="amount_in_words"><strong>Total in Words:</strong> Zero Birr only.</p>
  </div>

  <!-- Signature Section -->
  <div class="signature mb-4">
   <div class="row">
    <div class="col text-center">
     <p>Prepared By:</p>
     <p>____________________</p>
    </div>
    <div class="col text-center">
     <p>Checked By:</p>
     <p>____________________</p>
    </div>
    <div class="col text-center">
     <p>Approved By:</p>
     <p>____________________</p>
    </div>
   </div>
  </div>

  <div class="text-center">
   <button type="submit" class="btn btn-success">Save Sales</button>
  </div>
 </form>
</div>

<!-- Custom Styles -->
<style>
body {
 background-color: #f8f9fa;
 font-family: Arial, sans-serif;
}

.card {
 border-radius: 15px;
 box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.table th,
.table td {
 text-align: center;
}

.table-dark {
 background-color: #343a40;
 color: white;
}

.sales-summary p {
 font-size: 1.2rem;
 font-weight: bold;
}

.signature p {
 font-size: 1rem;
 font-weight: bold;
}

.signature .row div {
 text-align: center;
}

.btn-primary,
.btn-success {
 padding: 10px 20px;
 border-radius: 8px;
}

.btn-primary:hover,
.btn-success:hover {
 background-color: #0056b3;
 border-color: #0056b3;
}

.signature {
 margin-top: 30px;
}

.sales-summary {
 margin-top: 20px;
 font-size: 16px;
 font-weight: bold;
}

.card h3 {
 color: #007bff;
 font-weight: bold;
}

.table-bordered th,
.table-bordered td {
 border: 1px solid #ddd;
}

.table-striped tbody tr:nth-of-type(odd) {
 background-color: #f9f9f9;
}

.table-responsive {
 max-width: 100%;
 margin-bottom: 20px;
}

.text-center {
 text-align: center;
}

.form-label {
 font-weight: bold;
}

.form-control {
 border-radius: 8px;
}

.btn-primary {
 background-color: #007bff;
 border: none;
}

.btn-primary:focus,
.btn-primary:hover {
 background-color: #0056b3;
}

.alert {
 margin-top: 20px;
 border-radius: 10px;
}
</style>

<script>
function calculateTotal(input) {
 const row = input.closest("tr");
 const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value) || 0;
 const unitPrice = parseFloat(row.querySelector('[name="unit_price[]"]').value) || 0;
 const totalSales = quantity * unitPrice;
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
      <td><input type="number" class="form-control" name="quantity[]" step="1" min="1" oninput="calculateTotal(this)" required></td>
      <td><input type="number" class="form-control" name="unit_price[]" step="0.01" min="0.01" oninput="calculateTotal(this)" required></td>
      <td><input type="number" class="form-control" name="total_sales[]" readonly></td>
      <td><input type="text" class="form-control" name="job_id[]"></td>
      <td><button type="button" class="btn btn-danger" onclick="deleteRow(this)">Delete</button></td>
    `;
}

function deleteRow(button) {
 const row = button.closest("tr");
 row.remove();
 updateSalesSummary();
}

function updateSalesSummary() {
 let totalBeforeVAT = 0;
 const rows = document.querySelectorAll("#item_table tbody tr");
 rows.forEach(row => {
  const totalSales = parseFloat(row.querySelector('[name="total_sales[]"]').value) || 0;
  totalBeforeVAT += totalSales;
 });

 const vatAmount = totalBeforeVAT * 0.15;
 const salesWithVAT = totalBeforeVAT + vatAmount;
 document.getElementById("total_sales_before_vat").textContent = totalBeforeVAT.toFixed(2);
 document.getElementById("vat_amount").textContent = vatAmount.toFixed(2);
 document.getElementById("sales_with_vat").textContent = salesWithVAT.toFixed(2);

 const totalInWords = convertNumberToWords(salesWithVAT);
 document.getElementById("amount_in_words").textContent = `Total in Words: ${totalInWords} Birr only.`;
}

function convertNumberToWords(amount) {
 // A simple number-to-words conversion function (can be expanded)
 const words = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
 const num = Math.floor(amount);
 return words[num] || 'Invalid Amount';
}
</script>