
<?php
session_start();
include 'db_connection.php';

// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and sanitize form data
    $first_name = $_POST['first_name'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $address = $_POST['address'] ?? '';
    $name = trim("$first_name $middle_name $last_name");
    $department = $_POST['department'] ?? '';
    $position = $_POST['position'] ?? '';
    $salary = floatval($_POST['salary'] ?? 0);
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? 'staff';
    $hire_date = $_POST['hire_date'] ?? '';
    $termination_date = $_POST['termination_date'] ?? '';
    $experience = $_POST['experience'] ?? '';
    $skills = $_POST['skills'] ?? '';

    // Handle image upload
    $upload_dir = __DIR__ . "/uploads/";
    if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

    $profile_image = "";
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $image_name = basename($_FILES['profile_image']['name']);
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        if (!in_array($image_ext, $allowed_ext)) {
            $error = "Only JPG, JPEG, and PNG files are allowed.";
        } else {
            $new_image_name = time() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $image_name);
            $destination = $upload_dir . $new_image_name;

            if (!is_writable($upload_dir)) {
                $error = "Upload directory not writable: $upload_dir";
            } elseif (!is_uploaded_file($_FILES['profile_image']['tmp_name'])) {
                $error = "Temp file not found or not uploaded: " . $_FILES['profile_image']['tmp_name'];
            } elseif (move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination)) {
                $profile_image = "uploads/" . $new_image_name;
            } else {
                $error = "Failed to upload image. Check folder permissions.";
            }
        }
    }

    // Insert into database if no errors
    if (!$error) {
        $sql = "INSERT INTO staff 
                (first_name, middle_name, last_name, gender, telephone, address, name, department, position, salary, email, role, hire_date, termination_date, experience, skills, profile_image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $error = "SQL Error: " . $conn->error;
        } else {
            // Fixed bind_param: 16 variables, only 1 double for salary
            $stmt->bind_param(
                "ssssssssdssssssss",
                $first_name, $middle_name, $last_name, $gender, $telephone, $address, $name,
                $department, $position, $salary, $email, $role, $hire_date, $termination_date,
                $experience, $skills, $profile_image
            );

            if ($stmt->execute()) {
                $success = "Staff added successfully!";
                // Redirect to manage_staff.php after 2 seconds
                header("Refresh:2; url=manage_staff.php");
            } else {
                $error = "Error adding staff: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Add New Staff</title>
 <link rel="stylesheet" href="assets/css/bootstrap.min.css">
 <style>
 body { background-color: #f4f7fc; font-family: 'Arial', sans-serif; }
 .header { background-color: #4e73df; color: white; text-align: center; padding: 20px; font-size: 28px; font-weight: bold; margin-bottom: 30px; }
 .navbar { margin-bottom: 30px; }
 .form-container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0px 5px 20px rgba(0,0,0,0.1); margin-top: 10px; }
 .form-container h3 { color: #4e73df; margin-bottom: 20px; text-align:center; }
 .btn-custom { background-color: #4e73df; color: white; font-weight: bold; padding: 12px; border-radius: 8px; width: 100%; }
 .btn-custom:hover { background-color: #375a8c; }
 .alert { margin-top: 15px; text-align: center; }
 .footer { background-color: #343a40; color: white; text-align: center; padding: 15px; margin-top: 50px; }
 .profile-image { border-radius: 16px; width: 80px; height: 80px; object-fit: cover; margin-bottom: 15px; }
 </style>
</head>
<body>

<div class="header">Add New Staff - Admin Panel</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
 <div class="container">
  <a class="navbar-brand fw-bold" href="admin_dashboard.php">Admin Dashboard</a>
  <div class="ms-auto">
   <a href="manage_staff.php" class="btn btn-light me-2">Manage Staff</a>
   <a href="logout.php" class="btn btn-danger">Logout</a>
  </div>
 </div>
</nav>

<div class="form-container">
 <h3>✏️ Add New Staff</h3>

 <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>
 <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

 <form method="post" enctype="multipart/form-data">
  <div class="mb-3 text-center">
   <img src="<?= isset($profile_image) && $profile_image ? $profile_image : 'default.jpg' ?>" class="profile-image" alt="Profile Image">
   <input type="file" name="profile_image" class="form-control mt-2" accept="image/*">
  </div>

  <div class="mb-3"><label>First Name</label><input type="text" name="first_name" class="form-control" value="<?= $_POST['first_name'] ?? '' ?>" required></div>
  <div class="mb-3"><label>Middle Name</label><input type="text" name="middle_name" class="form-control" value="<?= $_POST['middle_name'] ?? '' ?>" required></div>
  <div class="mb-3"><label>Last Name</label><input type="text" name="last_name" class="form-control" value="<?= $_POST['last_name'] ?? '' ?>" required></div>
  <div class="mb-3"><label>Gender</label>
   <select name="gender" class="form-control" required>
    <option value="male" <?= ($_POST['gender'] ?? '') == 'male' ? 'selected' : '' ?>>Male</option>
    <option value="female" <?= ($_POST['gender'] ?? '') == 'female' ? 'selected' : '' ?>>Female</option>
   </select>
  </div>

  <div class="mb-3"><label>Department</label><input type="text" name="department" class="form-control" value="<?= $_POST['department'] ?? '' ?>" required></div>
  <div class="mb-3"><label>Position</label><input type="text" name="position" class="form-control" value="<?= $_POST['position'] ?? '' ?>" required></div>
  <div class="mb-3"><label>Salary</label><input type="number" name="salary" class="form-control" value="<?= $_POST['salary'] ?? '' ?>" required></div>
  <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= $_POST['email'] ?? '' ?>" required></div>
  <div class="mb-3"><label>Telephone</label><input type="text" name="telephone" class="form-control" value="<?= $_POST['telephone'] ?? '' ?>" required></div>
  <div class="mb-3"><label>Address</label><input type="text" name="address" class="form-control" value="<?= $_POST['address'] ?? '' ?>" required></div>
  <div class="mb-3"><label>Role</label>
   <select name="role" class="form-control" required>
    <option value="staff" <?= ($_POST['role'] ?? '') == 'staff' ? 'selected' : '' ?>>Staff</option>
    <option value="salesperson" <?= ($_POST['role'] ?? '') == 'salesperson' ? 'selected' : '' ?>>Salesperson</option>
    <option value="admin" <?= ($_POST['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
   </select>
  </div>
  <div class="mb-3"><label>Hire Date</label><input type="date" name="hire_date" class="form-control" value="<?= $_POST['hire_date'] ?? '' ?>" required></div>
  <div class="mb-3"><label>Termination Date</label><input type="date" name="termination_date" class="form-control" value="<?= $_POST['termination_date'] ?? '' ?>"></div>
  <div class="mb-3"><label>Experience</label><textarea name="experience" class="form-control" rows="3"><?= $_POST['experience'] ?? '' ?></textarea></div>
  <div class="mb-3"><label>Skills</label><textarea name="skills" class="form-control" rows="3"><?= $_POST['skills'] ?? '' ?></textarea></div>

  <button type="submit" class="btn btn-custom">Add New Staff</button>
 </form>
</div>

<footer class="footer">&copy; <?= date("Y"); ?> Add New Staff - All Rights Reserved.</footer>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
