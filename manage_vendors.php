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
$limit = 10; // users per page
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

// Check if there's a message and store it in a variable
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';

// Remove message after first display
unset($_SESSION['message']);
unset($_SESSION['message_type']);
?>

<?php
// Sorting logic
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'last_name';
$sort_order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

// Pagination settings
$limit = 10; // Vendors per page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch vendors with sorting and pagination
$query = "SELECT * FROM vendors ORDER BY $sort_column $sort_order LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching vendors: " . mysqli_error($conn));
}

// Count total vendors for pagination
$total_vendors_query = "SELECT COUNT(*) AS total FROM vendors";
$total_vendors_result = mysqli_query($conn, $total_vendors_query);
$total_vendors = mysqli_fetch_assoc($total_vendors_result)['total'];
$total_pages = ceil($total_vendors / $limit);
?>

<!-- Show Success or Error Message -->
<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-<?php echo $_SESSION['message_type']; ?> text-center" role="alert">
 <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); // Remove message after displaying
                unset($_SESSION['message_type']);
            ?>
</div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Vendor Management | Admin Panel</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
 <style>
 :root {
  --primary: #4e73df;
  --primary-dark: #375a8c;
  --secondary: #3f37c9;
  --success: #11a07c;
  --info: #076aff;
  --warning: #ffc107;
  --danger: #dc3545;
  --light: #f8f9fa;
  --dark: #343a40;
  --border-radius: 8px;
  --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  --transition: all 0.3s ease;
 }

 body {
  background-color: #f4f7fc;
  font-family: 'Segoe UI', system-ui, sans-serif;
  color: var(--dark);
 }

 .admin-header {
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  color: white;
  padding: 1.2rem 0;
  box-shadow: var(--box-shadow);
  margin-bottom: 1.5rem;
 }

 .header-content {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 1.5rem;
 }

 .admin-navbar {
  background-color: white;
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  padding: 0.8rem;
  margin: 0 auto 1.5rem;
  max-width: 1400px;
 }

 .nav-btn {
  padding: 0.5rem 1rem;
  border-radius: var(--border-radius);
  font-weight: 500;
  transition: var(--transition);
  margin: 0 0.5rem;
  font-size: 0.85rem;
 }

 .vendor-table-container {
  background-color: white;
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  padding: 1.2rem;
  margin: 0 auto 2rem;
  max-width: 1400px;
 }

 .vendor-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
 }

 .vendor-table thead {
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  color: white;
  font-size: 0.8rem;
 }

 .vendor-table th {
  padding: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  position: sticky;
  top: 0;
 }

 .vendor-table td {
  padding: 0.8rem;
  vertical-align: middle;
  border-bottom: 1px solid #f0f0f0;
 }

 .vendor-table tbody tr:hover {
  background-color: rgba(78, 115, 223, 0.05);
 }

 .sort-link {
  color: white;
  text-decoration: none;
  display: flex;
  align-items: center;
  justify-content: center;
 }

 .sort-link:hover {
  color: rgba(255, 255, 255, 0.8);
 }

 .sort-icon {
  margin-left: 0.3rem;
  font-size: 0.7rem;
 }

 .action-btn {
  padding: 0.35rem 0.7rem;
  font-size: 0.75rem;
  margin: 0 0.2rem;
  border-radius: var(--border-radius);
  transition: var(--transition);
 }

 .action-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
 }

 .btn-add {
  background-color: var(--success);
  border-color: var(--success);
 }

 .btn-edit {
  background-color: var(--warning);
  border-color: var(--warning);
 }

 .btn-view {
  background-color: var(--info);
  border-color: var(--info);
 }

 .btn-delete {
  background-color: var(--danger);
  border-color: var(--danger);
 }

 .footer {
  background-color: var(--dark);
  color: white;
  padding: 1rem 0;
  font-size: 0.8rem;
  width: 100%;
 }

 .footer-content {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 1.5rem;
 }

 .footer a {
  color: var(--info);
  text-decoration: none;
  transition: var(--transition);
 }

 .footer a:hover {
  color: white;
  text-decoration: underline;
 }

 .back-link {
  display: inline-block;
  margin-top: 1rem;
  color: var(--primary);
  font-weight: 500;
  transition: var(--transition);
 }

 .back-link:hover {
  color: var(--primary-dark);
  transform: translateX(-3px);
 }

 .empty-state {
  text-align: center;
  padding: 2rem;
  color: #6c757d;
 }

 .empty-state i {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  color: #dee2e6;
 }

 .pagination {
  margin-top: 1.5rem;
  justify-content: center;
 }

 .page-link {
  color: var(--primary);
  border: 1px solid var(--primary);
  margin: 0 0.2rem;
  border-radius: var(--border-radius) !important;
 }

 .page-link:hover {
  background-color: var(--primary);
  color: white;
 }

 .page-item.active .page-link {
  background-color: var(--primary);
  border-color: var(--primary);
 }

 .refresh-btn {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--dark);
  transition: var(--transition);
 }

 .refresh-btn:hover {
  color: var(--primary);
  transform: rotate(180deg);
 }

 .alert-message {
  max-width: 1400px;
  margin: 0 auto 1.5rem;
  border-radius: var(--border-radius);
 }

 @media (max-width: 992px) {
  .vendor-table-container {
   padding: 0.8rem;
  }

  .vendor-table th,
  .vendor-table td {
   padding: 0.6rem;
   font-size: 0.8rem;
  }

  .action-btns {
   display: flex;
   flex-wrap: wrap;
   gap: 0.3rem;
  }

  .action-btn {
   padding: 0.3rem 0.5rem;
   margin: 0.1rem;
  }
 }

 @media (max-width: 768px) {
  .admin-navbar .buttons-container {
   flex-wrap: wrap;
   justify-content: center;
  }

  .nav-btn {
   margin: 0.2rem;
   padding: 0.4rem 0.8rem;
  }

  .table-responsive {
   overflow-x: auto;
   -webkit-overflow-scrolling: touch;
  }
 }
 </style>
</head>

<body>
 <!-- Full-width Header -->
 <header class="admin-header">
  <div class="header-content">
   <h1 class="mb-0"><i class="fas fa-store-alt me-2"></i>Vendor Management</h1>
   <p class="mb-0 small opacity-75">Admin Dashboard</p>
  </div>
 </header>

 <!-- Main Content Container -->
 <div class="container-fluid px-0">
  <!-- Alert Message -->
  <?php if (!empty($message)): ?>
  <div class="alert-message alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
   <i class="fas <?php echo $message_type == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> me-2"></i>
   <?php echo $message; ?>
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <!-- Navigation Bar -->
  <div class="admin-navbar">
   <div class="d-flex justify-content-between align-items-center">
    <button class="refresh-btn" onclick="window.location.reload();">
     <i class="fas fa-sync-alt me-1"></i> Refresh
    </button>
    <div class="buttons-container">
     <a href="logout.php" class="btn btn-danger nav-btn">
      <i class="fas fa-sign-out-alt me-1"></i> Logout
     </a>
    </div>
   </div>
  </div>

  <!-- Vendor Table Section -->
  <div class="vendor-table-container">
   <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0"><i class="fas fa-list-ul me-2"></i>Vendor Directory</h2>
    <a href="add_vendor.php" class="btn btn-add text-white">
     <i class="fas fa-plus me-1"></i> Add New Vendor
    </a>
   </div>

   <div class="table-responsive">
    <table class="vendor-table">
     <thead>
      <tr>
       <th>
        <a
         href="?sort=vendor_id&order=<?php echo ($sort_column == 'vendor_id' && $sort_order == 'ASC') ? 'desc' : 'asc'; ?>"
         class="sort-link">
         Vendor ID
         <i
          class="fas fa-sort<?php echo $sort_column == 'vendor_id' ? ($sort_order == 'ASC' ? '-up' : '-down') : ''; ?> sort-icon"></i>
        </a>
       </th>
       <th>
        <a
         href="?sort=last_name&order=<?php echo ($sort_column == 'last_name' && $sort_order == 'ASC') ? 'desc' : 'asc'; ?>"
         class="sort-link">
         Last Name
         <i
          class="fas fa-sort<?php echo $sort_column == 'last_name' ? ($sort_order == 'ASC' ? '-up' : '-down') : ''; ?> sort-icon"></i>
        </a>
       </th>
       <th>
        <a
         href="?sort=middle_name&order=<?php echo ($sort_column == 'middle_name' && $sort_order == 'ASC') ? 'desc' : 'asc'; ?>"
         class="sort-link">
         Middle
         <i
          class="fas fa-sort<?php echo $sort_column == 'middle_name' ? ($sort_order == 'ASC' ? '-up' : '-down') : ''; ?> sort-icon"></i>
        </a>
       </th>
       <th>
        <a
         href="?sort=first_name&order=<?php echo ($sort_column == 'first_name' && $sort_order == 'ASC') ? 'desc' : 'asc'; ?>"
         class="sort-link">
         First Name
         <i
          class="fas fa-sort<?php echo $sort_column == 'first_name' ? ($sort_order == 'ASC' ? '-up' : '-down') : ''; ?> sort-icon"></i>
        </a>
       </th>
       <th>Email</th>
       <th>Phone</th>
       <th>Address</th>
       <th>Date Added</th>
       <th>Actions</th>
      </tr>
     </thead>
     <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($vendor = mysqli_fetch_assoc($result)): ?>
      <tr>
       <td><?php echo htmlspecialchars($vendor['vendor_id']); ?></td>
       <td><?php echo htmlspecialchars($vendor['last_name']); ?></td>
       <td><?php echo htmlspecialchars($vendor['middle_name']); ?></td>
       <td><?php echo htmlspecialchars($vendor['first_name']); ?></td>
       <td><a
         href="mailto:<?php echo htmlspecialchars($vendor['email']); ?>"><?php echo htmlspecialchars($vendor['email']); ?></a>
       </td>
       <td><a
         href="tel:<?php echo htmlspecialchars($vendor['telephone']); ?>"><?php echo htmlspecialchars($vendor['telephone']); ?></a>
       </td>
       <td><?php echo htmlspecialchars($vendor['address']); ?></td>
       <td><?php echo date("d-M-Y", strtotime(htmlspecialchars($vendor['created_at']))); ?></td>
       <td>
        <div class="action-btns">
         <a href="edit_vendor.php?vendor_id=<?php echo urlencode($vendor['vendor_id']); ?>"
          class="btn btn-edit text-white action-btn">
          <i class="fas fa-edit"></i>
         </a>
         <a href="delete_vendor.php?vendor_id=<?php echo urlencode($vendor['vendor_id']); ?>"
          class="btn btn-delete text-white action-btn"
          onclick="return confirm('Are you sure you want to delete this vendor?')">
          <i class="fas fa-trash-alt"></i>
         </a>
         <a href="vendor_history.php?vendor_id=<?php echo urlencode($vendor['vendor_id']); ?>"
          class="btn btn-view text-white action-btn">
          <i class="fas fa-history"></i>
         </a>
        </div>
       </td>
      </tr>
      <?php endwhile; ?>
      <?php else: ?>
      <tr>
       <td colspan="9">
        <div class="empty-state py-4">
         <i class="fas fa-store-slash"></i>
         <h4 class="h5 mt-3">No Vendors Found</h4>
         <p class="mb-3">Add your first vendor to get started</p>
         <a href="add_vendor.php" class="btn btn-add text-white">
          <i class="fas fa-plus me-1"></i> Add Vendor
         </a>
        </div>
       </td>
      </tr>
      <?php endif; ?>
     </tbody>
    </table>
   </div>

   <!-- Pagination -->
   <?php if (mysqli_num_rows($result) > 0): ?>
   <nav aria-label="Vendor pagination">
    <ul class="pagination">
     <?php if ($page > 1): ?>
     <li class="page-item">
      <a class="page-link"
       href="?page=<?php echo $page - 1; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>">
       <i class="fas fa-chevron-left"></i> Previous
      </a>
     </li>
     <?php endif; ?>

     <?php for ($i = 1; $i <= $total_pages; $i++): ?>
     <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
      <a class="page-link"
       href="?page=<?php echo $i; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>">
       <?php echo $i; ?>
      </a>
     </li>
     <?php endfor; ?>

     <?php if ($page < $total_pages): ?>
     <li class="page-item">
      <a class="page-link"
       href="?page=<?php echo $page + 1; ?>&sort=<?php echo $sort_column; ?>&order=<?php echo $sort_order; ?>">
       Next <i class="fas fa-chevron-right"></i>
      </a>
     </li>
     <?php endif; ?>
    </ul>
   </nav>
   <?php endif; ?>

   <a href="admin_dashboard.php" class="back-link">
    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
   </a>
  </div>
 </div>

 <!-- Full-width Footer -->
 <footer class="footer">
  <div class="footer-content d-flex justify-content-between align-items-center">
   <div>&copy; <?php echo date("Y"); ?> Vendor Management System</div>
   <div>
    <a href="privacy_policy.php" class="me-3"><i class="fas fa-shield-alt me-1"></i> Privacy</a>
    <a href="terms_conditions.php"><i class="fas fa-file-contract me-1"></i> Terms</a>
   </div>
  </div>
 </footer>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <script>
 // Add animation to table rows
 document.querySelectorAll('tbody tr').forEach((row, index) => {
  row.style.opacity = '0';
  row.style.transform = 'translateY(20px)';
  row.style.transition = `all 0.3s ease ${index * 0.05}s`;

  setTimeout(() => {
   row.style.opacity = '1';
   row.style.transform = 'translateY(0)';
  }, 100);
 });

 // Confirm before deleting
 document.querySelectorAll('.btn-delete').forEach(button => {
  button.addEventListener('click', function(e) {
   if (!confirm('Are you sure you want to delete this vendor?')) {
    e.preventDefault();
   }
  });
 });
 </script>
</body>

</html>