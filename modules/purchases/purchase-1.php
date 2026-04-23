kkkkkkk
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
  max-width: 400px;
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
  flex: 1;
  font-size: 14px;
  color: #333;
 }

 .form-group input,
 .form-group select,
 .form-group textarea {
  flex: 2;
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
  font-size: 12px;
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
  font-size: 12px;
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
   <div class="form-group">
    <label for="supplier">Supplier Name:</label>
    <select id="supplier" name="supplier">
     <option value="">Select Supplier</option>
     <option value="supplier1">Supplier 1</option>
     <option value="supplier2">Supplier 2</option>
    </select>
   </div>

   <div class="form-group">
    <label for="purchase_date">Purchase Date:</label>
    <input type="date" id="purchase_date" name="purchase_date">
   </div>

   <div class="form-group">
    <label for="ledger_account">Ledger Account:</label>
    <select id="ledger_account" name="ledger_account">
     <option value="">Select Account</option>
     <option value="expense">Expense Account</option>
     <option value="liability">Liability Account</option>
    </select>
   </div>

   <div class="form-group">
    <label for="reference_no">Reference No.:</label>
    <input type="text" id="reference_no" name="reference_no" placeholder="Enter reference number">
   </div>

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