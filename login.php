

<?php
session_start();
if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success text-center'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
}
?>


<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Log In / Register</title>

 <!-- Link to Bootstrap CSS -->
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

 <!-- FontAwesome for Icons -->
 <script src="https://kit.fontawesome.com/a076d05399.js"></script>

 <style>
 body {
  background-color: #f9f9f9;
  font-family: Arial, sans-serif;
  color: #333;
  display: flex;
  flex-direction: column;
  height: 125vh;
 }

 .container {
  display: flex;
  justify-content: center;
  align-items: center;
  flex: 1;
  /* Makes container take up remaining space */
 }

 .form-container {
  background-color: #ffffff;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  border: 1px solid #ddd;
 }

 .form-container h2 {
  text-align: center;
  font-size: 30px;
  color: #007bff;
  margin-bottom: 20px;
 }

 .form-group label {
  font-size: 16px;
  color: #444;
 }

 .form-control {
  font-size: 16px;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #ddd;
  margin-bottom: 15px;
  box-sizing: border-box;
 }

 .form-control:focus {
  border-color: #007bff;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
 }

 .btn-primary {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 8px;
  font-size: 16px;
  width: 100%;
  cursor: pointer;
  transition: background-color 0.3s ease;
 }

 .btn-primary:hover {
  background-color: #0056b3;
 }

 .toggle-link {
  text-align: center;
  font-size: 16px;
  margin-top: 20px;
 }

 .toggle-link a {
  color: #007bff;
  text-decoration: none;
 }

 .toggle-link a:hover {
  text-decoration: underline;
 }

 .error-message {
  color: red;
  font-size: 14px;
  text-align: center;
 }

 /* Footer Styles */
 footer {
  background-color: #333;
  color: white;
  padding: 40px 0;
  text-align: center;
  margin-top: auto;
  /* This pushes the footer to the bottom */
 }

 footer .social-links a {
  color: #fff;
  margin: 0 15px;
  font-size: 20px;
  transition: color 0.3s;
 }

 footer .social-links a:hover {
  color: #007bff;
 }

 footer .footer-info p {
  margin: 5px 0;
 }

 footer .footer-bottom {
  padding-top: 20px;
  border-top: 1px solid #444;
  font-size: 14px;
 }
 </style>
</head>

<body>

 <div class="container">
  <!-- Login Form -->
  <div class="form-container card" id="login-form">
   <h2>Log In</h2>
   <form method="post" action="process_login.php">
    <div class="form-group">
     <label for="login-email">E-mail</label>
     <input type="email" class="form-control" id="login-email" name="email" placeholder="Enter your email" required>
    </div>
    <div class="form-group">
     <label for="login-password">Password</label>
     <input type="password" class="form-control" id="login-password" name="password" placeholder="Enter your password"
      required>
    </div>
    <button type="submit" class="btn btn-primary">Log In</button>
    <div class="toggle-link">
     <p>Not registered? <a href="register.php" class="active">Register Here</a></p>

    </div>
   </form>
  </div>

  <!-- Registration Form (Hidden by default) -->
  <div class="form-container card" id="register-form" style="display: none;">
   <h2>Register</h2>
   <form method="POST">
    <div class="form-group">
     <label for="name">Full Name</label>
     <input type="text" class="form-control" name="name" required>
    </div>
    <div class="form-group">
     <label for="email">Email</label>
     <input type="email" class="form-control" name="email" required>
    </div>
    <div class="form-group">
     <label for="password">Password</label>
     <input type="password" class="form-control" name="password" required>
    </div>
    <div class="form-group">
     <label for="role">Role</label>
     <select class="form-control" name="role" required>
      <option value="admin">Admin</option>
      <option value="staff">Staff</option>
      <option value="salesperson">Salesperson</option>
      <option value="user">User</option>
     </select>
    </div>
    <div class="form-group">
     <label for="gender">Gender</label><br>
     <input type="radio" name="gender" value="Male" required> Male
     <input type="radio" name="gender" value="Female" required> Female
     <input type="radio" name="gender" value="Secret" required> Secret
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
    <div class="toggle-link">
     <p>Already registered? <a href="#" onclick="toggleForms('login-form')">Log In Here</a></p>
    </div>
   </form>
  </div>
 </div>

 <!-- Link to jQuery -->
 <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
 <!-- Link to Bootstrap JS -->
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

 <script>
 function toggleForms(formId) {
  document.getElementById('login-form').style.display = formId === 'login-form' ? 'block' : 'none';
  document.getElementById('register-form').style.display = formId === 'register-form' ? 'block' : 'none';
 }
 </script>

 <!-- Include Footer -->
 <?php include 'footer.php'; ?>

</body>

</html>