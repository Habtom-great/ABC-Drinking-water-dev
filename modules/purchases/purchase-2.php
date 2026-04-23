<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Add Purchase - Peachtree Style</title>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 <style>
 body {
  font-family: 'Segoe UI', Arial, sans-serif;
  background-color: #f5f5f5;
  margin: 0;
  padding: 0;
 }

 .header,
 .footer {
  background-color: #004b8d;
  color: white;
  text-align: center;
  padding: 10px 0;
 }

 .header h1,
 .footer p {
  margin: 0;
 }

 .container {
  max-width: 850px;
  margin: 20px auto;
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
 }

 .form-title {
  color: #004b8d;
  font-size: 20px;
  margin-bottom: 20px;
  text-align: center;
  border-bottom: 1px solid #ddd;
  padding-bottom: 10px;
 }

 .form-group {
  display: flex;
  margin-bottom: 15px;
  align-items: center;
 }

 .form-group label {
  flex: 1.5;
  font-size: 14px;
  color: #333;
 }

 .form-group input,
 .form-group select,
 .form-group textarea {
  flex: 2.5;
  padding: 8px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 4px;
  outline: none;
 }

 .form-group input:focus,
 .form-group select:focus,
 .form-group textarea:focus {
  border-color: #004b8d;
 }

 .btn-group {
  text-align: center;
  margin-top: 20px;
 }

 .btn-group button {
  background-color: #004b8d;
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 14px;
  border-radius: 4px;
  cursor: pointer;
  margin: 0 10px;
 }

 .btn-group button:hover {
  background-color: #003366;
 }

 .footer-links {
  display: flex;
  justify-content: center;
  margin: 10px 0;
 }

 .footer-links a {
  color: white;
  margin: 0 10px;
  text-decoration: none;
  font-size: 14px;
 }

 .footer-links a:hover {
  text-decoration: underline;
 }
 </style>
</head>

<body>
 <div class="header">
  <h1>ABC Company</h1>
 </div>

 <div class="container">
  <div class="form-title">Add Purchase</div>
  <form action="process_purchase.php" method="POST">
   <!-- Invoice No -->
   <div class="form-group">
    <label for="invoice_no">Invoice No:</label>
    <input type="text" id="invoice_no" name="invoice_no" placeholder="Enter invoice number">
   </div>

   <!-- Order No -->
   <div class="form-group">
    <label for="order_no">Order No:</label>
    <input type="text" id="order_no" name="order_no" placeholder="Enter order number">
   </div>

   <!-- Purchase No -->
   <div class="form-group">
    <label for="purchase_no">Purchase No:</label>
    <input type="text" id="purchase_no" name="purchase_no" placeholder="Enter purchase number">
   </div>

   <!-- Item Type -->
   <div class="form-group">
    <label for="item_type">Item Type:</label>
    <select id="item_type" name="item_type">
     <option value="">Select Item Type</option>
     <option value="product">Product</option>
     <option value="service">Service</option>
    </select>
   </div>

   <!-- UOM -->
   <div class="form-group">
    <label for="uom">Unit of Measure (UOM):</label>
    <select id="uom" name="uom">
     <option value="">Select UOM</option>
     <option value="piece">Piece</option>
     <option value="kg">Kilogram</option>
     <option value="litre">Litre</option>
    </select>
   </div>

   <!-- GL Sales -->
   <div class="form-group">
    <label for="gl_sales">GL Sales Account:</label>
    <input type="text" id="gl_sales" name="gl_sales" placeholder="Enter GL sales account">
   </div>

   <!-- GL Inventory -->
   <div class="form-group">
    <label for="gl_inventory">GL Inventory Account:</label>
    <input type="text" id="gl_inventory" name="gl_inventory" placeholder="Enter GL inventory account">
   </div>

   <!-- GL Cost of Sales -->
   <div class="form-group">
    <label for="gl_cost_of_sales">GL Cost of Sales:</label>
    <input type="text" id="gl_cost_of_sales" name="gl_cost_of_sales" placeholder="Enter GL cost of sales account">
   </div>

   <!-- Location -->
   <div class="form-group">
    <label for="location">Location:</label>
    <input type="text" id="location" name="location" placeholder="Enter location">
   </div>

   <!-- Discount -->
   <div class="form-group">
    <label for="discount">Discount (%):</label>
    <input type="number" id="discount" name="discount" placeholder="Enter discount" step="0.01" min="0" max="100">
   </div>

   <!-- Description -->
   <div class="form-group">
    <label for="description">Description:</label>
    <textarea id="description" name="description" rows="4" placeholder="Enter purchase details"></textarea>
   </div>

   <div class="btn-group">
    <button type="submit">Submit</button>
    <button type="reset">Cancel</button>
   </div>
  </form>
 </div>

 <div class="footer">
  <p>&copy; 2025 ABC Company. All rights reserved.</p>
  <div class="footer-links">
   <a href="#">Home</a>
   <a href="#">Invoices</a>
   <a href="#">Orders</a>
   <a href="#">Ledger</a>
  </div>
 </div>
</body>

</html>