<link rel="stylesheet" href="styles.css">
<header>
 <div class="navbar">
  <img src="assets/images/OIP (1).jpeg" alt="ABC Water Company Logo" class="logo">
  <div>ABC Drinking Water Company - Pure and Refreshing</div>
  <nav>
   <ul>
    <li><a href="index.php" class="active">Home</a></li>
    <li><a href="about.php" class="active">About Us</a></li>
    <li><a href="products.php" class="active">Products</a></li>
    <li><a href="services.php" class="active">Services</a></li>
    <li><a href="contact.php" class="active">Contact Us</a></li>
    <?php if (isset($_SESSION['user_logged_in'])): ?>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
    <li><a href="login.php" class="active">Login</a></li>
    <li><a href="register.php" class="active">Register</a></li>
    <?php endif; ?>
   </ul>
  </nav>
 </div>
</header>
<!-- Font Awesome -->