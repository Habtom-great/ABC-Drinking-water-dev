<?php  
session_start();
include 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch the user details for editing
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$query = "SELECT * FROM users WHERE user_id = '$user_id' AND role = 'user'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching user: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update user details
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $update_query = "UPDATE users SET first_name='$first_name', last_name='$last_name', middle_name='$middle_name', email='$email', telephone='$telephone', address='$address' WHERE user_id='$user_id'";
    
    if (mysqli_query($conn, $update_query)) {
        echo "User updated successfully!";
        header("Location: manage_users.php"); // Redirect to manage users page
        exit();
    } else {
        echo "Error updating user: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Edit User</title>
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
  padding: 30px;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
 }

 .form-group {
  margin-bottom: 20px;
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
  border: none;
  ;
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
  margin-top: 15px;
 }

 .navbar .btn {
  background-color: #28a745;
  /* Add User button color */
  color: white;
  font-weight: bold;
 }

 .navbar .btn:hover {
  background-color: #218838;
  /* Hover effect for Add User button */
 }

 .navbar .btn-logout {
  background-color: #dc3545;
  /* Logout button color */
  color: white;
  font-weight: bold;
 }

 .navbar .btn-logout:hover {
  background-color: #c82333;
  /* Hover effect for Logout button */
 }

 .navbar .btn-manage {
  background-color: #17a2b8;
  /* Manage Users button color */
  color: white;
  font-weight: bold;
 }

 .navbar .btn-manage:hover {
  background-color: #138496;
  /* Hover effect for Manage Users button */
 }

 .bottom-submit {
  display: flex;
  justify-content: center;
  margin-top: 30px;
 }

 .bottom-buttons {
  display: flex;
  justify-content: center;
  margin-top: 30px;
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

 <!-- Header -->
 <div class="header">
  Edit User - Admin Panel
 </div>

 <!-- Navbar -->
 <div class="navbar">
  <a href="manage_users.php" class="btn btn-manage">Manage Users</a>
  <a href="logout.php" class="btn btn-logout">Logout</a>
 </div>

 <!-- Edit User Form Section -->
 <div class="container">
  <div class="form-container">
   <form action="" method="POST">
    <h2 class="text-center">Edit User Details</h2>
    <div class="form-group">
     <label for="first_name">First Name:</label>
     <input type="text" class="form-control" id="first_name" name="first_name"
      value="<?php echo $user['first_name']; ?>" required>
    </div>
    <div class="form-group">
     <label for="last_name">Last Name:</label>
     <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>"
      required>
    </div>
    <div class="form-group">
     <label for="middle_name">Middle Name:</label>
     <input type="text" class="form-control" id="middle_name" name="middle_name"
      value="<?php echo $user['middle_name']; ?>" required>
    </div>
    <div class="form-group">
     <label for="email">Email:</label>
     <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
    </div>
    <div class="form-group">
     <label for="telephone">Phone:</label>
     <input type="text" class="form-control" id="telephone" name="telephone" value="<?php echo $user['telephone']; ?>"
      required>
    </div>
    <div class="form-group">
     <label for="address">Address:</label>
     <input type="text" class="form-control" id="address" name="address" value="<?php echo $user['address']; ?>"
      required>

    </div>
    <div class="text-center">
     <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
   </form>
  </div>

  <!-- Back to Admin Dashboard Button -->
  <div class="bottom-buttons">
   <a href="admin_dashboard.php">Back to Admin Dashboard</a>
  </div>
 </div>

 <!-- Footer -->
 <footer class="footer">
  &copy; <?php echo date("Y"); ?> Edit User - All Rights Reserved.
 </footer>

</body>

</html>