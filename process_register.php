<?php
require 'db_connect.php';

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form values exist and are not empty
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : ''; // Don't hash the password until all validation checks pass
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';

    // Check if all fields are filled
    if (empty($name) || empty($email) || empty($password) || empty($role) || empty($gender)) {
        $error_message = "All fields are required.";
    } else {
        // Hash password only if all fields are filled
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // SQL to insert user data into the database using prepared statement
        $sql = "INSERT INTO users (name, email, password, role, gender) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind the parameters
            $stmt->bind_param("sssss", $name, $email, $hashed_password, $role, $gender);

            // Execute the query
            if ($stmt->execute()) {
                // Registration successful
                echo "<script>
                        alert('Registration Successful! Redirecting to Login...');
                        window.location.href = 'login.php';
                      </script>";
            } else {
                // Handle SQL error
                $error_message = "Error: " . $stmt->error;
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            $error_message = "Failed to prepare SQL statement.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Register</title>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 <style>
 body {
  font-family: Arial, sans-serif;
  background-color: #f8f9fa;
  margin: 0;
  padding: 0;
 }

 .form-container {
  margin: 5% auto;
  max-width: 400px;
  padding: 25px;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
 }

 h2 {
  text-align: center;
  color: #343a40;
  margin-bottom: 20px;
 }

 .form-group label {
  font-weight: bold;
 }

 .btn-primary {
  width: 100%;
  background-color: #007bff;
  border: none;
 }

 .btn-primary:hover {
  background-color: #0056b3;
 }

 .toggle-link {
  margin-top: 15px;
  text-align: center;
 }

 .toggle-link a {
  color: #007bff;
  text-decoration: none;
 }

 .toggle-link a:hover {
  text-decoration: underline;
 }

 .alert {
  margin-bottom: 20px;
 }

 .error-message {
  color: red;
  font-size: 14px;
  margin-top: 10px;
 }
 </style>
</head>

<body>

 <div class="container">
  <!-- Registration Form -->
  <div class="form-container">
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
      <option value="salesperson">Salesperson</option> <!-- Added Salesperson role -->
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
   </form>

   <!-- Show error message if any -->
   <?php
            if (isset($error_message)) {
                echo "<div class='error-message'>{$error_message}</div>";
            }
            ?>

   <div class="toggle-link">
    <p>Already registered? <a href="login.php">Log In Here</a></p>
   </div>
  </div>
 </div>

 <!-- Bootstrap JS and jQuery -->
 <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>

</html>