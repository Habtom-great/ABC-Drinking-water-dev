<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In / Register</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .form-container {
            margin: 6% auto;
            max-width: 400px;
            padding: 25px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #343a40;
            margin-bottom: 25px;
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
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Login Form -->
    <div class="form-container" id="login-form">
        <h2>Log In</h2>
        <form method="post" action="process_login.php">
            <div class="form-group">
                <label for="login-email">E-mail</label>
                <input type="email" class="form-control" id="login-email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="login-password">Password</label>
                <input type="password" class="form-control" id="login-password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary">Log In</button>
            <div class="toggle-link">
                <p>Not registered? <a href="#" onclick="toggleForms('register-form')">Register Here</a></p>
            </div>
        </form>
    </div>

    <!-- Registration Form -->
    <div class="form-container" id="register-form" style="display: none;">
        <h2>Register</h2>
        <form method="post" action="process_register.php" id="registrationForm">
            <div class="form-group">
                <label for="register-name">Full Name</label>
                <input type="text" class="form-control" id="register-name" name="name" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label for="register-email">E-mail</label>
                <input type="email" class="form-control" id="register-email" name="email" placeholder="Enter your email" required>
                <small class="error" id="emailError"></small>
            </div>
            <div class="form-group">
                <label for="register-password">Password</label>
                <input type="password" class="form-control" id="register-password" name="password" placeholder="Create a password" required>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <div>
                    <label><input type="radio" name="gender" value="Male" required> Male</label>
                    <label><input type="radio" name="gender" value="Female" required> Female</label>
                </div>
            </div>
            <div class="form-group">
                <label>Role</label>
                <div>
                    <label><input type="radio" name="role" value="user" required> User</label>
                    <label><input type="radio" name="role" value="admin" required> Admin</label>
                    <label><input type="radio" name="role" value="salesperson" required> Salesperson</label>
                    <label><input type="radio" name="role" value="staff" required> Staff</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <div class="toggle-link">
                <p>Already registered? <a href="#" onclick="toggleForms('login-form')">Log In Here</a></p>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleForms(formId) {
        const forms = ['login-form', 'register-form'];
        forms.forEach(id => {
            document.getElementById(id).style.display = id === formId ? 'block' : 'none';
        });
    }

    // Email validation
    document.getElementById("registrationForm").addEventListener("submit", function (event) {
        const email = document.getElementById("register-email").value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const emailError = document.getElementById("emailError");

        if (!emailPattern.test(email)) {
            emailError.textContent = "Please enter a valid email address.";
            event.preventDefault();
        } else {
            emailError.textContent = "";
        }
    });
</script>

</body>
</html>
<?php include 'footer.php'; ?>
