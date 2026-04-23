<?php
if (isset($_GET['success']) && $_GET['success'] == "1") {
    echo '<div class="alert alert-success">Sales information has been successfully added.</div>';
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

 .alert-success {
  display: none;
 }
 </style>
</head>

<body>

 <div class="header text-center">
  <img src="logo.png" alt="Company Logo">
  <h4>ABC Company PLC | Sales Invoice</h4>
 </div>

 <div class="container mt-2">
  <h2 class="mb-3 text-center">Sales Invoice</h2>

  <!-- Success Message -->
  <div class="alert alert-success" id="success-message">Successfully Added!</div>

  <div class="card">
   <form id="sales_form" action="save_sale.php" method="POST">
    <!-- Sales General Information -->
    <div class="row">
     <div class="col-md-2">
      <label class="form-label">Order No.</label>
      <input type="text" class="form-control" name="sales_order_no" placeholder="Enter order number" required>
     </div>
     <div class="col-md-2">
      <label class="form-label">Invoice No.</label>
      <input type="text" class="form-control" name="sales_invoice_no" placeholder="Enter invoice number" required>
     </div>
     <div class="col-md-2">
      <label class="form-label">Reference</label>
      <input type="text" class="form-control" name="reference" placeholder="Enter reference" required>
     </div>
     <div class="col-md-2">
      <label class="form-label">Invoice Date</label>
      <input type="date" class="form-control" name="invoiceDate" required>
     </div>
    </div>

    <!-- Customer Information -->
    <div class="row mt-3">
     <div class="col-md-3">
      <label class="form-label">Customer ID</label>
      <input type="text" class="form-control" name="customer_id" placeholder="Enter customer ID" required>
     </div>
     <div class="col-md-3">
      <label class="form-label">Company Name</label>
      <input type="text" class="form-control" name="customer_company_name" placeholder="Enter company name" required>
     </div>
     <div class="col-md-3">
      <label class="form-label">Customer Name</label>
      <input type="text" class="form-control" name="customer_name" placeholder="Enter customer name" required>
     </div>
     <div class="col-md-3">
      <label class="form-label">TIN No</label>
      <input type="text" class="form-control" name="customer_tin_no" placeholder="Enter TIN number" required>
     </div>
    </div>

    <!-- Salesperson & Payment Information -->
    <div class="row mt-3">
     <div class="col-md-3">
      <label class="form-label">Salesperson ID</label>
      <input type="text" class="form-control" name="salesperson_id" placeholder="Enter salesperson ID" required>
     </div>
     <div class="col-md-3">
      <label class="form-label">Salesperson Name</label>
      <input type="text" class="form-control" name="salesperson_name" placeholder="Enter salesperson name" required>
     </div>
     <div class="col-md-3">
      <label class="form-label">Job ID</label>
      <input type="text" class="form-control" name="job_id" placeholder="Enter job ID" required>
     </div>
     <div class="col-md-3">
      <label class="form-label">Payment Method</label>
      <select class="form-control" name="payment_method" required>
       <option value="">Select Payment Method</option>
       <option value="cash">Cash</option>
       <option value="cheque">Cheque</option>
       <option value="bank">Bank Transfer</option>
       <option value="other">Other</option>
      </select>
     </div>
    </div>

    <!-- Items Table -->
    <div class="mt-4">
     <table id="item_table" class="table table-bordered">
      <thead class="table-dark">
       <tr>
        <th>Item</th>
        <th>UOM</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total</th>
        <th>Action</th>
       </tr>
      </thead>
      <tbody>
       <tr>
        <td><input type="text" class="form-control" name="item_name[]"></td>
        <td>
         <select class="form-control" name="uom[]">
          <option value="pcs">pcs</option>
          <option value="kg">kg</option>
          <option value="liters">liters</option>
         </select>
        </td>
        <td><input type="number" class="form-control quantity" name="quantity[]" value="1"></td>
        <td><input type="text" class="form-control unit_price" name="unit_price[]"></td>
        <td><input type="text" class="form-control total" name="total[]" readonly></td>
        <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
       </tr>
      </tbody>
     </table>
    </div>

    <button type="button" class="btn btn-primary" id="add_row">Add Item</button>
    <button type="submit" class="btn btn-success">Submit</button>

    <div class="summary-section mt-3">
     <p>Subtotal: <span id="subtotal">0.00</span></p>
     <p>VAT (15%): <span id="vat">0.00</span></p>
     <p><strong>Total: <span id="grand_total">0.00</span></strong></p>
     <p class="amount-in-words" id="amount_in_words">Total in Words: Zero Birr only.</p>
    </div>

    <div class="signatures">
     <div>Authorized By</div>
     <div>Received By</div>
    </div>

    <button type="submit" class="btn btn-info mt-3">Save</button>
    <button type="button" class="btn btn-success mt-3" id="print_invoice">Print</button>
    <button type="button" class="btn btn-secondary mt-3" id="export_pdf">Export to PDF</button>
    <div class="footer">Thank you for your business!</div>
   </form>
  </div>
 </div>

 <script>
 $(document).ready(function() {
  function updateSummary() {
   let subtotal = 0;
   $('.total').each(function() {
    subtotal += parseFloat($(this).val().replace(/,/g, '')) || 0;
   });

   let vat = subtotal * 0.15;
   let grandTotal = subtotal + vat;

   $('#subtotal').text(subtotal.toLocaleString());
   $('#vat').text(vat.toLocaleString());
   $('#grand_total').text(grandTotal.toLocaleString());

   // Convert total to words (using a library or function)
   let amountInWords = convertNumberToWords(grandTotal);
   $('#amount_in_words').text(`Total in Words: ${amountInWords}`);
  }

  function convertNumberToWords(amount) {
   // Convert the amount into words
   // For simplicity, returning a fixed text here. You can use an actual number-to-words library or code.
   return "Zero Birr only"; // You can improve this with a proper library
  }

  // Add new row
  $('#add_row').click(function() {
   let newRow = $('#item_table tbody tr:first').clone();
   newRow.find('input').val('');
   $('#item_table tbody').append(newRow);
  });

  // Remove row
  $(document).on('click', '.remove-row', function() {
   $(this).closest('tr').remove();
   updateSummary();
  });

  // Update totals when quantity or unit price changes
  $(document).on('input', '.quantity, .unit_price', function() {
   let row = $(this).closest('tr');
   let quantity = row.find('.quantity').val();
   let unitPrice = row.find('.unit_price').val();
   let total = quantity * unitPrice;
   row.find('.total').val(total.toFixed(2));
   updateSummary();
  });

  // Print invoice
  $('#print_invoice').click(function() {
   window.print();
  });

  // Export to PDF
  $('#export_pdf').click(function() {
   const {
    jsPDF
   } = window.jspdf;
   const doc = new jsPDF();

   doc.text('Sales Invoice', 10, 10);
   doc.text(`Subtotal: ${$('#subtotal').text()}`, 10, 20);
   doc.text(`VAT: ${$('#vat').text()}`, 10, 30);
   doc.text(`Total: ${$('#grand_total').text()}`, 10, 40);
   doc.text($('#amount_in_words').text(), 10, 50);

   doc.save('invoice.pdf');
  });

  // Submit form
  $('#sales_form').submit(function(e) {
   e.preventDefault();
   // Handle your form submission logic (AJAX or simple redirect)
   $('#success-message').show();
  });
 });
 </script>

</body>

</html>
<script></script>