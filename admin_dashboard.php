<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Admin Dashboard</title>

 <!-- Local Bootstrap CSS -->
 <link rel="stylesheet" href="css/bootstrap.min.css">

 <!-- Custom Styles -->
 <style>
 body {
  background-color: #f4f7fc;
  font-family: Arial, sans-serif;
 }

 .dashboard-header {
  background-color: #212529;
  color: white;
  padding: 20px;
  text-align: center;
  position: relative;
 }

 .logout-btn {
  position: absolute;
  top: 20px;
  right: 30px;
 }

 .card {
  margin: 15px 0;
  transition: transform 0.3s ease-in-out;
 }

 .card:hover {
  transform: scale(1.05);
 }

 .footer {
  text-align: center;
  margin-top: 30px;
  padding: 15px;
  background: #343a40;
  color: white;
  font-size: 14px;
 }

 .container {
  margin-top: 30px;
 }
 </style>
</head>

<body>

 <!-- Header -->
 <div class="dashboard-header">
  <h1>Admin Dashboard</h1>
  <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong></p>
  <a href="logout.php" class="btn btn-danger logout-btn">Logout</a>
 </div>


 <div class="container">
  <div class="row">
   <!-- Customers Management -->
   <div class="col-md-4">
    <div class="card border-primary shadow">
     <div class="card-header bg-primary text-white">Customers Management</div>
     <div class="card-body">
      <p>Manage Customers, view details.</p>
      <a href="manage_users.php" class="btn btn-primary">Go to Customers Management</a>
     </div>
    </div>
   </div>

   <!-- Suppliers/Venders Management -->
   <div class="col-md-4">
    <div class="card border-primary shadow">
     <div class="card-header bg-primary text-white">Suppliers/Venders Management</div>
     <div class="card-body">
      <p>Manage Suppliers/Venders, view details.</p>
      <a href="manage_vendors.php" class="btn btn-primary">Go to Suppliers/Venders Management</a>
     </div>
    </div>
   </div>


   <!-- Charts of Accounts Management -->
   <div class="col-md-4">
    <div class="card border-primary shadow">
     <div class="card-header bg-primary text-white">Charts of Accounts Management</div>
     <div class="card-body">
      <p>Manage Charts of Accounts, view details.</p>
      <a href="manage_charts_of_accounts.php" class="btn btn-primary">Go to Charts of Accounts Management</a>
     </div>
    </div>
   </div>

   <div class="container">
    <div class="row">
     <!-- User Management -->
     <div class="col-md-4">
      <div class="card border-primary shadow">
       <div class="card-header bg-primary text-white">User Management</div>
       <div class="card-body">
        <p>Manage users, view details, and assign roles.</p>
        <a href="manage_users.php" class="btn btn-primary">Go to User Management</a>
       </div>
      </div>
     </div>

     <!-- Staff Management -->
     <div class="col-md-4">
      <div class="card border-success shadow">
       <div class="card-header bg-success text-white">Staff/Employees Management</div>
       <div class="card-body">
        <p>Manage staff details and assign inventory tasks.</p>
        <a href="manage_staff.php" class="btn btn-success">Go to Staff Management</a>
       </div>
      </div>
     </div>

     <!-- Inventory Management -->
     <div class="col-md-4">
      <div class="card border-warning shadow">
       <div class="card-header bg-warning text-dark">Inventory Management</div>
       <div class="card-body">
        <p>Track inventory, manage stock, and generate reports.</p>
        <a href="manage_inventory.php" class="btn btn-warning">Go to Inventory Management</a>
       </div>
      </div>
     </div>
    </div>




    <div class="row">
     <!-- Reports -->
     <div class="col-md-4">
      <div class="card border-info shadow">
       <div class="card-header bg-info text-white">Reports</div>
       <div class="card-body">
        <p>Generate and view detailed reports of the system.</p>
        <a href="generate_report-1.php" class="btn btn-info">View Reports</a>
       </div>
      </div>
     </div>

     <!-- Settings -->
     <div class="col-md-4">
      <div class="card border-secondary shadow">
       <div class="card-header bg-secondary text-white">Settings</div>
       <div class="card-body">
        <p>Configure system settings and user permissions.</p>
        <a href="settings.php" class="btn btn-secondary">Go to Settings</a>
       </div>
      </div>
     </div>

     <!-- Activity Log -->
     <div class="col-md-4">
      <div class="card border-danger shadow">
       <div class="card-header bg-danger text-white">Activity Log</div>
       <div class="card-body">
        <p>View and track system activity logs.</p>
        <a href="activity_log.php" class="btn btn-danger">View Logs</a>
       </div>
      </div>
     </div>
    </div>
   </div>
</div>
</div>
   <!-- Footer -->
   <div class="footer">
    &copy; <?php echo date("Y"); ?> Inventory Management System | All Rights Reserved.
   </div>

   <!-- Local Bootstrap JS -->
   <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>