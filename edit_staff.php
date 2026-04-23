
<?php
session_start();
require_once __DIR__ . '/db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

// Check if staff ID is passed in the URL
if (isset($_GET['staff_id'])) {
    $staff_id = $_GET['staff_id'];

    // Fetch staff details using the correct column name 'staff_id'
    $query = "SELECT * FROM staff WHERE staff_id = $staff_id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        $error = "Error fetching staff details: " . mysqli_error($conn) . "<br>SQL Query: $query";
    } else {
        $staff = mysqli_fetch_assoc($result);
        if (!$staff) {
            $error = "Staff not found!";
        }
    }
} else {
    header("Location: manage_staff.php");
    exit();
}

// Handle form submission for updating staff
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $address = $_POST['address'];
    $role = $_POST['role'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $hire_date = $_POST['hire_date'];
    $termination_date = $_POST['termination_date'];
    $experience = $_POST['experience'];
    $skills = $_POST['skills'];

 // Start with current image
$profile_image = $staff['profile_image']; // important: initialize with existing image

// Ensure uploads folder exists and is writable
$upload_dir = __DIR__ . "/uploads/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true); // full write permission
}

// Handle image upload
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    $image_name = basename($_FILES['profile_image']['name']);
    $image_tmp = $_FILES['profile_image']['tmp_name'];
    $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

    if (!in_array($image_ext, ['jpg','jpeg','png'])) {
        $error = "Only JPG, JPEG, and PNG files are allowed.";
    } else {
        $new_image_name = time() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $image_name);
        $destination = $upload_dir . $new_image_name;

        if (is_uploaded_file($image_tmp)) {
            if (move_uploaded_file($image_tmp, $destination)) {
                // Delete old image if exists and not default
                if ($staff['profile_image'] && file_exists(__DIR__ . "/" . $staff['profile_image'])) {
                    unlink(__DIR__ . "/" . $staff['profile_image']);
                }
                $profile_image = "uploads/" . $new_image_name;
            } else {
                $error = "Failed to move uploaded file. Check folder permissions.";
            }
        } else {
            $error = "Temporary file not found. Upload failed.";
        }
    }
}

    // Only run update if no error
    if (!$error) {
        $update_query = "UPDATE staff SET 
            first_name = '$first_name', 
            middle_name = '$middle_name', 
            last_name = '$last_name', 
            email = '$email', 
            telephone = '$telephone', 
            address = '$address', 
            role = '$role',
            department = '$department',
            position = '$position',
            salary = '$salary',
            hire_date = '$hire_date',
            termination_date = '$termination_date',
            experience = '$experience',
            skills = '$skills',
            profile_image = '$profile_image'
            WHERE staff_id = $staff_id";

        if (mysqli_query($conn, $update_query)) {
            $success = "Staff updated successfully!";
            // Update $staff array so the form shows new image immediately
            $staff['profile_image'] = $profile_image;
        } else {
            $error = "Error updating staff: " . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Edit Staff</title>
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
  margin-bottom: 30px;
 }

 .form-container {
  max-width: 350px;
  margin: auto;
  background: white;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
  margin-top: 10px;
 }

 .form-container h3 {
  color: #4e73df;
  margin-bottom: 20px;
 }

 .form-container input,
 .form-container select,
 .form-container textarea,
 .form-container button {
  font-size: 16px;
 }

 .form-container .btn-custom {
  background-color: #4e73df;
  color: white;
  font-weight: bold;
  padding: 12px;
  border-radius: 8px;
  width: 100%;
  text-align: center;
 }

 .form-container .btn-custom:hover {
  background-color: #375a8c;
 }

 .alert {
  margin-top: 15px;
  text-align: center;
 }

 .footer {
  background-color: #343a40;
  color: white;
  text-align: center;
  padding: 15px;
  margin-top: 50px;
 }

 .form-group label {
  font-weight: bold;
 }

 .form-group textarea {
  resize: vertical;
 }

 .profile-image {
  border-radius: 16px;
  width: 50px;
  height: 50px;
  object-fit: cover;
  margin-bottom: 15px;
 }

 .file-upload {
  background-color: #f4f7fc;
  padding: 10px;
  border-radius: 8px;
  display: inline-block;
  cursor: pointer;
 }
 </style>
</head>

<body>

 <!-- Header -->
 <div class="header">
  Edit Staff - Admin Panel
 </div>

 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
   <a class="navbar-brand fw-bold" href="admin_dashboard.php">Admin Dashboard</a>
   <div class="ml-auto">
    <a href="manage_staff.php" class="btn btn-light me-2">Manage Staff</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
   </div>
  </div>
 </nav>

 <!-- Form Section -->
 <div class="container">
  <?php if ($success) {
            echo "<div class='alert alert-success'>$success</div>";
        } ?>
  <?php if ($error) {
            echo "<div class='alert alert-danger'>$error</div>";
        } ?>

  <div class="form-container">
   <h3 class="text-center">✏️ Edit Staff</h3>
   <form method="post" enctype="multipart/form-data">

    <div class="form-group">

     <input type="file" name="profile_image" accept="image/*">

     <label></label>
     <img src="<?= isset($staff['profile_image']) ? $staff['profile_image'] : 'default.jpg' ?>" class="profile-image"
      alt="Profile Image">
     <div class="file-upload">
     </div>
     <div class="form-group">
      <label>Staff ID</label>
      <input type="text" class="form-control" value="<?= isset($staff['staff_id']) ? $staff['staff_id'] : '' ?>"
       readonly>

     </div>

     <div class="form-group">
      <label>First_name</label>
      <input type="text" name="first_name" class="form-control"
       value="<?= isset($staff['first_name']) ? $staff['first_name'] : '' ?>" required>
     </div>
     <div class="form-group">
      <label>Middle_name</label>
      <input type="text" name="middle_name" class="form-control"
       value="<?= isset($staff['middle_name']) ? $staff['middle_name'] : '' ?>" required>
     </div>
     <div class="form-group">
      <label>Last_name</label>
      <input type="text" name="last_name" class="form-control"
       value="<?= isset($staff['last_name']) ? $staff['last_name'] : '' ?>" required>
     </div>
     <div class="form-group">
      <label>Department</label>
      <input type="text" name="department" class="form-control"
       value="<?= isset($staff['department']) ? $staff['department'] : '' ?>" required>
     </div>
     <div class="form-group">
      <label>Position</label>
      <input type="text" name="position" class="form-control"
       value="<?= isset($staff['position']) ? $staff['position'] : '' ?>" required>
     </div>
     <div class="form-group">
      <label>Salary</label>
      <input type="number" name="salary" class="form-control"
       value="<?= isset($staff['salary']) ? $staff['salary'] : '' ?>" required>
     </div>
     <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="<?= isset($staff['email']) ? $staff['email'] : '' ?>"
       required>
     </div>
     <div class="form-group">
      <label>Telephone</label>
      <input type="text" name="telephone" class="form-control"
       value="<?= isset($staff['telephone']) ? $staff['telephone'] : '' ?>" required>
     </div>
     <div class="form-group">
      <label>Address</label>
      <input type="text" name="address" class="form-control"
       value="<?= isset($staff['address']) ? $staff['address'] : '' ?>" required>
     </div>
     <div class="form-group">
      <label for="role">Role</label>
      <select class="form-control" name="role" required>
       <option value="staff">Staff</option>
       <option value="salesperson">Salesperson</option> <!-- Added Salesperson role -->

      </select>
     </div>
     <div class="form-group">
      <label>Hire Date</label>
      <input type="date" name="hire_date" class="form-control"
       value="<?= isset($staff['hire_date']) ? $staff['hire_date'] : '' ?>" required>
     </div>
     <div class="form-group">
      <label>Termination Date</label>
      <input type="date" name="termination_date" class="form-control"
       value="<?= isset($staff['termination_date']) ? $staff['termination_date'] : '' ?>">
     </div>
     <div class="form-group">
      <label>Experience</label>
      <textarea name="experience" class="form-control"
       rows="4"><?= isset($staff['experience']) ? $staff['experience'] : '' ?></textarea>
     </div>
     <div class="form-group">
      <label>Skills</label>
      <textarea name="skills" class="form-control"
       rows="4"><?= isset($staff['skills']) ? $staff['skills'] : '' ?></textarea>
     </div>

    </div>
    <button type="submit" class="btn btn-custom">Update Staff</button>
   </form>
  </div>
 </div>

 <!-- Footer -->
 <footer class="footer">
  &copy; <?php echo date("Y"); ?> Edit Staff - All Rights Reserved.
 </footer>

 <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>