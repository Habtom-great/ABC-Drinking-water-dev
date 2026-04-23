<pre><?php print_r($_SESSION); ?></pre>


<?php  
session_start();
require_once "db.php";

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Sorting logic
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'last_name';
$sort_order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

// Pagination settings
$limit = 10; // Users per page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch users with sorting and pagination
$query = "SELECT * FROM users WHERE role = 'user' ORDER BY $sort_column $sort_order LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching users: " . mysqli_error($conn));
}

// Count total users for pagination
$total_users_query = "SELECT COUNT(*) AS total FROM users WHERE role = 'user'";
$total_users_result = mysqli_query($conn, $total_users_query);
$total_users = mysqli_fetch_assoc($total_users_result)['total'];
$total_pages = ceil($total_users / $limit);

?>


<!DOCTYPE html>
<html lang="en">
<?php

// Check if there's a message and store it in a variable
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';

// Remove message after first display
unset($_SESSION['message']);
unset($_SESSION['message_type']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Manage Users</title>
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
  text-align: center;
 }

 .user-table {
  width: 72.15%;
  margin: auto;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
  background-color: #fff;
  border-collapse: collapse;
 }

 .user-table thead {
  background-color: #4e73df;
  color: white;
  font-weight: bold;
 }

 .user-table th,
 .user-table td {
  text-align: center;
  padding: 15px;
  font-size: 13.6px;
 }

 .user-table th a {
  color: white;
  text-decoration: none;
 }

 .user-table th a:hover {
  text-decoration: underline;
 }

 .user-table tbody tr:nth-child(odd) {
  background-color: #f9f9f9;
 }

 .user-table tbody tr:hover {
  background-color: #f1f1f1;
 }

 .pagination {
  margin-top: 20px;
  display: flex;
  justify-content: center;
 }

 .pagination a {
  color: #4e73df;
  font-weight: bold;
  text-decoration: none;
  padding: 8px 12px;
  border-radius: 5px;
  border: 1px solid #4e73df;
  margin: 0 5px;
 }

 .pagination a:hover {
  background-color: #4e73df;
  color: white;
 }

 .footer {
  background-color: #343a40;
  color: white;
  text-align: center;
  padding: 15px;
  margin-top: 50px;
 }

 .actions a {
  text-decoration: none;
  margin: 0 5px;
 }

 .actions .edit-btn {
  background-color: #ffc107;
  color: white;
  padding: 6px 12px;
  border-radius: 5px;
 }

 .actions .delete-btn {
  background-color: #dc3545;
  color: white;
  padding: 6px 12px;
  border-radius: 5px;
 }

 .actions .edit-btn:hover {
  background-color: #e0a800;
 }

 .actions .delete-btn:hover {
  background-color: #c82333;
 }

 .back-btn {
  background-color: #007bff;
  color: white;
  padding: 8px 16px;
  text-decoration: none;
  border-radius: 5px;
  margin-top: 20px;
  display: inline-block;
 }

 .back-btn:hover {
  background-color: #0056b3;
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
 </style>


</head>

<body>

 <!-- Header -->
<!-- Header -->
<div class="header">
    Manage Users - Admin Panel
</div>

<!-- Centered Alert Message -->
<?php session_start(); ?>
<?php if (isset($_SESSION['message'])): ?>
<div class="d-flex justify-content-center mt-4">
    <div class="alert alert-<?php echo $_SESSION['message_type']; ?> text-center shadow"
         role="alert"
         style="
            max-width: 600px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            border-radius: 10px;
            border-left: 6px solid <?php echo $_SESSION['message_type'] === 'success' ? '#28a745' : '#dc3545'; ?>;
         ">
        <?php echo htmlspecialchars($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php
unset($_SESSION['message']);
unset($_SESSION['message_type']);
endif;
?>

 <!-- Navbar -->
 <div class="navbar">
  <button onclick="window.location.reload();" style="background: none; border: none; cursor: pointer;">
   <i class="fas fa-sync-alt" style="font-size: 24px; color: #000;"></i> Refresh
  </button>
  <a href="add_user.php" class="btn">Add User</a>
  <a href="logout.php" class="btn btn-logout">Logout</a>
 </div>


 <!-- user Table Section -->
 <div class="container">
  <h2 class="text-center">User Members</h2>

  <div class="user-table">
   <table class="table table-striped">
    <thead>
     <tr>
      <th><a
        href="?sort=user_id&order=<?php echo ($sort_column == 'user_id' && $sort_order == 'ASC') ? 'desc' : 'asc'; ?>">User
        ID</a></th>
      <th><a
        href="?sort=last_name&order=<?php echo ($sort_column == 'last_name' && $sort_order == 'ASC') ? 'desc' : 'asc'; ?>">Last
        Name</a></th>
      <th><a
        href="?sort=middle_name&order=<?php echo ($sort_column == 'middle_name' && $sort_order == 'ASC') ? 'desc' : 'asc'; ?>">Middle
        Name</a></th>
      <th><a
        href="?sort=first_name&order=<?php echo ($sort_column == 'first_name' && $sort_order == 'ASC') ? 'desc' : 'asc'; ?>">First
        Name</a></th>
        <th><a
        href="?sort=gender&order=<?php echo ($sort_column == 'gender' && $sort_order == 'ASC') ? 'desc' : 'asc'; ?>">Gender</a></th>
      <th><a
        href="?sort=email&order=<?php echo ($sort_column == 'email' && $sort_order == 'ASC') ? 'desc' : 'asc'; ?>">Email</a>
      </th>
      <th><a
        href="?sort=telephone&order=<?php echo ($sort_column == 'telephone' && $sort_order == 'ASC') ? 'desc' : 'asc'; ?>">Phone</a>
      </th>
      <th>Address</th>
      <th>Role</th>
      <th><a
        href="?sort=created_at&order=<?php echo ($sort_column == 'created_at' && $sort_order == 'ASC') ? 'desc' : 'asc'; ?>">Registration
        Date</a></th>
      <th>Actions</th>
     </tr>
    </thead>

    <tbody>
     <?php while ($user = mysqli_fetch_assoc($result)) : ?>
     <tr>
      <td><?php echo $user['user_id']; ?></td>
      <td><?php echo $user['last_name']; ?></td>
      <td><?php echo $user['middle_name']; ?></td>
      <td><?php echo $user['first_name']; ?></td>
      <td><?php echo $user['gender']; ?></td>
      <td><?php echo $user['email']; ?></td>
      <td><?php echo $user['telephone']; ?></td>
      <td><?php echo $user['address']; ?></td>
      <td><?php echo $user['role']; ?></td>
      <td><?php echo date("d-M-Y", strtotime($user['created_at'])); ?></td>
      <td class="actions">
       <a href="edit_user.php?user_id=<?php echo urlencode($user['user_id']); ?>" class="edit-btn">Edit</a>
       <a href="delete_user.php?user_id=<?php echo urlencode($user['user_id']); ?>" class="delete-btn"
        onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
      </td>
     </tr>
     <?php endwhile; ?>
    </tbody>
   </table>
  </div>


  <!-- Pagination -->
  <div class="pagination">
   <?php if ($page > 1): ?>
   <a
    href="?page=<?php echo $page - 1; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>">Previous</a>
   <?php endif; ?>
   <?php if ($page < $total_pages): ?>
   <a href="?page=<?php echo $page + 1; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>">Next</a>
   <?php endif; ?>
  </div>

  <!-- Back to Dashboard Button -->
  <a href="admin_dashboard.php" class="back-btn">Back to Admin Dashboard</a>
 </div>

 <!-- Footer -->
 <footer class="footer">
  &copy; <?php echo date("Y"); ?> Manage Users - All Rights Reserved.
 </footer>

</body>

</html>