<?php
session_start();
require_once "db.php";

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all staff from the database
$query = "SELECT * FROM staff";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching staff: " . mysqli_error($conn));
}

// Default image path for all staff except Habt.jpg
$defaultImage = "uploads/staff/profile_images/Habt.jpg";
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Staff Management | Admin Panel</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
 <style>
 :root {
  --primary: #4361ee;
  --primary-dark: #3a56d4;
  --secondary: #3f37c9;
  --success: #4cc9f0;
  --danger: #f72585;
  --warning: #f8961e;
  --light: #f8f9fa;
  --dark: #212529;
  --border-radius: 6px;
  --box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  --transition: all 0.2s ease;
 }

 body {
  background-color: #f1f5f9;
  font-family: 'Segoe UI', system-ui, sans-serif;
  color: var(--dark);
  font-size: 0.875rem;
  line-height: 1.4;
 }

 .admin-header {
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  color: white;
  padding: 0.8rem 0;
  box-shadow: var(--box-shadow);
  margin-bottom: 1.2rem;
 }

 .header-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
 }

 .admin-navbar {
  background-color: white;
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  padding: 0.6rem;
  margin: 0 auto 1.2rem;
  max-width: 1200px;
 }

 .nav-btn {
  padding: 0.4rem 0.9rem;
  border-radius: var(--border-radius);
  font-weight: 500;
  transition: var(--transition);
  margin: 0 0.3rem;
  font-size: 0.8rem;
 }

 .staff-table-container {
  background-color: white;
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  padding: 1rem;
  margin: 0 auto 1.5rem;
  max-width: 1100px;
 }

 .table thead {
  background-color: var(--primary);
  color: white;
  font-size: 0.75rem;
 }

 .table th,
 .table td {
  padding: 0.6rem;
  vertical-align: middle;
 }

 .profile-img {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  object-fit: cover;
  border: 1px solid var(--primary);
 }

 .action-btn {
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
  margin: 0 0.15rem;
  border-radius: var(--border-radius);
 }

 .footer {
  background-color: var(--dark);
  color: white;
  padding: 0.8rem 0;
  font-size: 0.75rem;
  width: 100%;
 }

 .footer-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
 }

 .footer a {
  color: var(--success);
  text-decoration: none;
  font-size: 0.75rem;
 }

 .back-link {
  font-size: 0.8rem;
  margin: 0 auto 1rem;
  max-width: 1200px;
  display: block;
  padding: 0 1rem;
 }

 h1 {
  font-size: 1.2rem;
  margin: 0;
 }

 h2 {
  font-size: 1rem;
  margin-bottom: 0.8rem;
 }

 .empty-state i {
  font-size: 2rem;
 }

 @media (max-width: 768px) {
  .admin-navbar .buttons-container {
   flex-wrap: wrap;
   justify-content: center;
  }

  .nav-btn {
   margin: 0.15rem;
   padding: 0.3rem 0.6rem;
  }

  .action-btns {
   display: flex;
   flex-wrap: wrap;
   justify-content: center;
  }

  .action-btn {
   margin: 0.1rem;
  }
 }
 </style>
</head>

<body>
 <!-- Full-width Header -->
 <header class="admin-header">
  <div class="header-content">
   <h1><i class="fas fa-users-cog me-1"></i>Staff Management</h1>
  </div>
 </header>

 <!-- Main Content Container -->
 <div class="container-fluid px-0">
  <!-- Navigation Bar -->
  <div class="admin-navbar">
   <div class="d-flex justify-content-end">
    <div class="buttons-container">
     <!-- Back Link -->
     <a href="admin_dashboard.php" class="btn btn-primary nav-btn">
      <i class="fas fa-history me-1"></i>Back to Dashboard
     </a>
     <a href="view_all_staff_history.php" class="btn btn-primary nav-btn">
      <i class="fas fa-history me-1"></i>History
     </a>
     <a href="add_staff.php" class="btn btn-success nav-btn">
      <i class="fas fa-user-plus me-1"></i>Add
     </a>
     <a href="payroll.php" class="btn btn-warning text-white nav-btn">
      <i class="fas fa-money-bill-wave me-1"></i>Payroll
     </a>
     <a href="logout.php" class="btn btn-danger nav-btn">
      <i class="fas fa-sign-out-alt me-1"></i>Logout
     </a>
    </div>
   </div>
  </div>

  <!-- Staff Table Section -->
  <div class="staff-table-container">
   <h2 class="text-center"><i class="fas fa-users me-1"></i>Staff Members</h2>

   <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message_type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show text-center" role="alert">
        <?php echo $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
endif;
?>

   <div class="table-responsive">
    <table class="table table-hover align-middle">
     <thead>
      <tr>
       <th>ID</th>
       <th>Last Name</th>
       <th>Middle</th>
       <th>First</th>
       <th>Email</th>
       <th>Phone</th>
       <th>Photo</th>
       <th>Actions</th>
      </tr>
     </thead>
     <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($staff = mysqli_fetch_assoc($result)): ?>
      <tr>
       <td><?php echo htmlspecialchars($staff['staff_id']); ?></td>
       <td><?php echo htmlspecialchars($staff['last_name']); ?></td>
       <td><?php echo htmlspecialchars($staff['middle_name']); ?></td>
       <td><?php echo htmlspecialchars($staff['first_name']); ?></td>
       <td><a href="mailto:<?php echo htmlspecialchars($staff['email']); ?>"><?php echo htmlspecialchars($staff['email']); ?></a></td>
       <td><a href="tel:<?php echo htmlspecialchars($staff['telephone']); ?>"><?php echo htmlspecialchars($staff['telephone']); ?></a></td>
     <td>
    <?php
        $img = !empty($staff['profile_image']) ? $staff['profile_image'] : $defaultImage;
    ?>
    <img src="<?php echo htmlspecialchars($img); ?>?v=<?= time(); ?>" class="profile-img" alt="Profile Photo">
</td>

       <td>
        <div class="action-btns">
         <a href="edit_staff.php?staff_id=<?php echo urlencode($staff['staff_id']); ?>" class="btn btn-sm btn-primary action-btn">
          <i class="fas fa-edit"></i>
         </a>
         <a href="delete_staff.php?staff_id=<?php echo urlencode($staff['staff_id']); ?>" class="btn btn-sm btn-danger action-btn" onclick="return confirm('Delete this staff member?')">
          <i class="fas fa-trash-alt"></i>
         </a>
         <a href="staff_history.php?staff_id=<?php echo urlencode($staff['staff_id']); ?>" class="btn btn-sm btn-info action-btn">
          <i class="fas fa-history"></i>
         </a>
        </div>
       </td>
      </tr>
      <?php endwhile; ?>
      <?php else: ?>
      <tr>
       <td colspan="8" class="text-center py-3">
        <div class="empty-state">
         <i class="fas fa-user-slash text-muted"></i>
         <p class="mb-1">No staff members found</p>
         <a href="add_staff.php" class="btn btn-sm btn-primary">
          <i class="fas fa-user-plus me-1"></i>Add Staff
         </a>
        </div>
       </td>
      </tr>
      <?php endif; ?>
     </tbody>
    </table>
   </div>
  </div>
 </div>

 <!-- Full-width Footer -->
 <footer class="footer">
  <div class="footer-content d-flex justify-content-between align-items-center">
   <div>&copy; <?php echo date("Y"); ?> Staff Management System</div>
   <div>
    <a href="privacy_policy.php" class="me-2">Privacy</a>
    <a href="terms_conditions.php">Terms</a>
   </div>
  </div>
 </footer>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <script>
 // Simple row hover effect
 document.querySelectorAll('tbody tr').forEach(row => {
  row.addEventListener('mouseenter', () => {
   row.style.transform = 'translateX(2px)';
  });
  row.addEventListener('mouseleave', () => {
   row.style.transform = 'translateX(0)';
  });
 });
 </script>
</body>
</html>
