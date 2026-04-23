<?php
session_start();
include 'db_connection.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    if (empty($_POST['supplier_name']) || empty($_POST['account_name']) || empty($_POST['phone'])) {
        die("Error: Missing required fields.");
    }

    // Sanitize input data
    $supplier_name = mysqli_real_escape_string($conn, trim($_POST['supplier_name']));
    $account_name = mysqli_real_escape_string($conn, trim($_POST['account_name']));
    $contact_info = mysqli_real_escape_string($conn, trim($_POST['contact_info'] ?? ''));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $address = mysqli_real_escape_string($conn, trim($_POST['address'] ?? ''));
    $ledger_account = isset($_POST['ledger_account']) ? mysqli_real_escape_string($conn, trim($_POST['ledger_account'])) : '';

    if (empty($ledger_account)) {
        echo "<script>alert('Ledger Account is required.');</script>";
    } else {
        // Insert supplier data into database
        $supplier_query = "INSERT INTO suppliers (name, account_name, contact_info, phone, email, address, ledger_account) 
                           VALUES ('$supplier_name', '$account_name', '$contact_info', '$phone', '$email', '$address', '$ledger_account')";
        
        if (mysqli_query($conn, $supplier_query)) {
            echo "<script>alert('Supplier added successfully!'); window.location.href='add_supplier.php';</script>";
        } else {
            echo "<script>alert('Error adding supplier: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Add Supplier</title>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
  font-size: 26px;
  font-weight: bold;
  margin-bottom: 20px;
 }

 .container {
  width: 50%;
  margin: auto;
  padding: 20px;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
 }

 .btn-primary {
  background-color: #4e73df;
  color: white;
  font-weight: bold;
  padding: 10px 20px;
  border-radius: 5px;
  border: none;
 }

 .btn-primary:hover {
  background-color: #375a8c;
 }
 </style>
</head>

<body>
 <div class="header">Add Supplier - Admin Panel</div>
 <div class="container">
  <form method="POST" action="">
   <div class="form-group">
    <label for="supplier_name">Supplier Name</label>
    <input type="text" name="supplier_name" class="form-control" id="supplier_name" required>
   </div>
   <div class="form-group">
    <label for="account_name">Account Name</label>
    <input type="text" name="account_name" class="form-control" id="account_name" required>
   </div>
   <div class="form-group">
    <label for="contact_info">Contact Information</label>
    <input type="text" name="contact_info" class="form-control" id="contact_info">
   </div>
   <div class="form-group">
    <label for="phone">Phone</label>
    <input type="text" name="phone" class="form-control" id="phone" required>
   </div>
   <div class="form-group">
    <label for="email">Email</label>
    <input type="email" name="email" class="form-control" id="email">
   </div>
   <div class="form-group">
    <label for="address">Address</label>
    <input type="text" name="address" class="form-control" id="address">
   </div>
   <div class="form-group">
    <label for="ledger_account">Ledger Account</label>
    <input type="text" name="ledger_account" class="form-control" id="ledger_account" required>
   </div>
   <button type="submit" class="btn btn-primary btn-block">Add Supplier</button>
  </form>
 </div>
</body>

</html>