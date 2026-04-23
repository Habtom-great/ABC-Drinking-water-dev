<?php  
session_start();
include 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    // Secure password hashing
    $role = 'user'; // Default role for new users

    // Insert user into the database
    $insert_query = "INSERT INTO vendors (first_name, last_name, middle_name, email, telephone, address) 
                     VALUES ('$first_name', '$last_name', '$middle_name', '$email', '$telephone', '$address')";

    if (mysqli_query($conn, $insert_query)) {  
        $_SESSION['message'] = "Vendor added successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error adding Vendor: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }

    header("Location: add_vendor.php"); // Redirect back to the add vendor form
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Add Vendor</title>
 <link rel="stylesheet" href="assets/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

 <head>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
 </head>
</head>

<body>

 <div class="header">Add Vendor - Admin Panel</div>


 <!-- Navbar -->

 <div class="navbar">
  <button onclick="window.location.reload();" style="background: none; border: none; cursor: pointer;">
   <i class="fas fa-sync-alt" style="font-size: 24px; color: #000;"></i> Refresh
  </button>
  <a href="manage_vendors.php" class="btn btn-manage">Manage Vendors</a>
  <a href="logout.php" class="btn btn-danger">Logout</a>
 </div>
 <?php if (isset($_SESSION['message'])): ?>
 <div class="alert alert-<?php echo $_SESSION['message_type']; ?> text-center" role="alert"
  style="font-size: 16px; font-weight: bold; color:rgb(32, 158, 141)">

  <?php 
            echo $_SESSION['message']; 
            unset($_SESSION['message']); // Remove message after displaying
            unset($_SESSION['message_type']);
        ?>
 </div>
 <?php endif; ?>

 </div>


 <!DOCTYPE html>
 <html lang="en">

 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Vendor</title>
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
   font-size: 26px;
   font-weight: bold;
   margin-bottom: 20px;
  }

  .navbar {
   display: flex;
   justify-content: flex-end;
   padding: 15px;
  }

  .navbar a {
   color: white;
   text-decoration: none;
   font-weight: bold;
   padding: 10px 15px;
   border-radius: 5px;
   margin-left: 10px;
  }

  .navbar a:hover {
   background-color: #375a8c;
  }

  .container {
   text-align: left;
  }

  .form-container {
   width: 20%;
   margin: auto;
   padding: 20px;
   background-color: #fff;
   border-radius: 10px;
   box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
  }

  .form-group {
   margin-bottom: 6px;
  }

  .form-group label {
   font-weight: bold;
  }

  .form-control {
   width: 60%;
   padding: 10px;
   border-radius: 5px;
   border: 1px solid #ccc;
   font-size: 13px;
  }

  .text-center {
   display: flex;
   justify-content: center;
  }

  .btn-primary {
   background-color: #4e73df;
   color: white;
   font-weight: bold;
   padding: 10px 20px;
   border-radius: 5px;
   size: 35px;
   border: none;
  }

  .btn-primary:hover {
   background-color: rgb(55, 140, 129);
  }

  .btn-danger {
   background-color: #dc3545;
   color: white;
   font-weight: bold;
   padding: 10px 20px;
   border-radius: 5px;
   border: none;
  }

  .btn-danger:hover {
   background-color: #c82333;
  }

  .footer {
   background-color: #343a40;
   color: white;
   text-align: center;
   padding: 15px;
   margin-top: 30px;
  }

  .navbar .btn-manage {
   background-color: #17a2b8;
   color: white;
   font-weight: bold;
  }

  .navbar .btn-manage:hover {
   background-color: #138496;
  }

  .bottom-submit {
   display: flex;
   justify-content: center;
   margin-top: 30px;
  }

  .bottom-buttons {
   display: flex;
   justify-content: center;
   margin-top: 100px;
  }

  .bottom-buttons a {
   background-color: #007bff;
   color: white;
   font-weight: bold;
   padding: 10px 20px;
   border-radius: 5px;
   margin: 0 10px;
   text-decoration: none;
  }

  .bottom-buttons a:hover {
   background-color: #0056b3;
  }
  </style>
 </head>

 <body>

  <!-- Add User Form Section -->
  <div class="container">
   <div class="form-container">
    <form action="" method="POST">
     <h2 class="text-center">Add New Vender</h2>
     <div class="form-group">
      <label for="first_name">First Name:</label>
      <input type="text" class="form-control" id="first_name" name="first_name" required>
     </div>
     <div class="form-group">
      <label for="last_name">Last Name:</label>
      <input type="text" class="form-control" id="last_name" name="last_name" required>
     </div>
     <div class="form-group">
      <label for="middle_name">Middle Name:</label>
      <input type="text" class="form-control" id="middle_name" name="middle_name" required>
     </div>
     <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" name="email" required>
     </div>
     <div class="form-group">
      <label for="telephone">Phone:</label>
      <input type="text" class="form-control" id="telephone" name="telephone" required>
     </div>
     <div class="form-group">
      <label for="address">Address:</label>
      <input type="text" class="form-control" id="address" name="address" required>
     </div>
     <div class="text-center">
      <button type="submit" class="btn btn-primary">
       <i class="fas fa-check-circle"></i> Add Vendor
      </button>
    </form>

   </div>
   </form>
  </div>



  <div class="text-center mt-3">
   <a href="admin_dashboard.php">Back to Admin Dashboard</a>
  </div>
  </div>
  </div>

  <footer class="footer">
   &copy; <?php echo date("Y"); ?> Add Vendor - All Rights Reserved.
  </footer>

 </body>

 </html>