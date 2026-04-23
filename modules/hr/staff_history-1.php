<?php

include 'db_connection.php';

if (!isset($_GET['staff_id'])) {
    die("Error: Staff ID is missing.");
}

$staff_id = $_GET['staff_id'];

// Fetch staff details including image
$staff_query = "SELECT staff_id, first_name, middle_name, last_name, profile_image, email, telephone, position, department, salary, hire_date, termination_date, experience, skills FROM staff WHERE staff_id = ?";
$staff_stmt = $conn->prepare($staff_query);

// Check if prepare() was successful
if (!$staff_stmt) {
    die("SQL Error: " . $conn->error);
}

$staff_stmt->bind_param("i", $staff_id);
$staff_stmt->execute();
$staff_result = $staff_stmt->get_result();
$staff = $staff_result->fetch_assoc();
$staff_stmt->close();

if (!$staff) {
    echo "<script>
        alert('Staff not found.');
        window.location.href = 'manage_staff.php';
    </script>";
    exit();
}

// Fetch staff performance
$performance_query = "SELECT * FROM staff WHERE staff_id = ?";
$performance_stmt = $conn->prepare($performance_query);

// Check if prepare() was successful for performance query
if (!$performance_stmt) {
    die("SQL Error: " . $conn->error);
}

$performance_stmt->bind_param("i", $staff_id);
$performance_stmt->execute();
$performance_result = $performance_stmt->get_result();
$performance = $performance_result->fetch_assoc();
$performance_stmt->close();

// Check if the form is submitted for updating
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Handle file upload for profile image
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $upload_dir = 'uploads/';
        $file_name = $_FILES['profile_image']['name'];
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $new_image_name = time() . '_' . $file_name;
        $upload_path = $upload_dir . $new_image_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Update the staff record with the new image name
            $update_query = "UPDATE staff SET 
                first_name = ?, 
                middle_name = ?, 
                last_name = ?, 
                email = ?, 
                telephone = ?, 
                address = ?, 
                role = ?, 
                department = ?, 
                position = ?, 
                salary = ?, 
                hire_date = ?, 
                termination_date = ?, 
                experience = ?, 
                skills = ?, 
                profile_image = ? 
                WHERE staff_id = ?";
            $update_stmt = $conn->prepare($update_query);
            if (!$update_stmt) {
                die("SQL Error: " . $conn->error);
            }

            $update_stmt->bind_param(
                "sssssssssssssssi",
                $first_name,
                $middle_name,
                $last_name,
                $email,
                $telephone,
                $address,
                $role,
                $department,
                $position,
                $salary,
                $hire_date,
                $termination_date,
                $experience,
                $skills,
                $new_image_name,
                $staff_id
            );
        } else {
            echo "<script>
                alert('Failed to upload image.');
                window.location.href = 'manage_staff.php';
            </script>";
            exit();
        }
    } else {
        // If no new image, update without changing the image
        $update_query = "UPDATE staff SET 
            first_name = ?, 
            middle_name = ?, 
            last_name = ?, 
            email = ?, 
            telephone = ?, 
            address = ?, 
            role = ?, 
            department = ?, 
            position = ?, 
            salary = ?, 
            hire_date = ?, 
            termination_date = ?, 
            experience = ?, 
            skills = ? 
            WHERE staff_id = ?";
        $update_stmt = $conn->prepare($update_query);
        if (!$update_stmt) {
            die("SQL Error: " . $conn->error);
        }

        $update_stmt->bind_param(
            "sssssssssssssi",
            $first_name,
            $middle_name,
            $last_name,
            $email,
            $telephone,
            $address,
            $role,
            $department,
            $position,
            $salary,
            $hire_date,
            $termination_date,
            $experience,
            $skills,
            $staff_id
        );
    }

    if ($update_stmt->execute()) {
        echo "<script>
            alert('Staff details updated successfully.');
            window.location.href = 'manage_staff.php?staff_id=" . $staff_id . "';
        </script>";
    } else {
        echo "<script>
            alert('Failed to update staff details.');
            window.location.href = 'manage_staff.php';
        </script>";
    }
    $update_stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Staff Profile</title>
 <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body>
 <!-- Header -->
 <div class="header">
  Staff profile - Admin Panel
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



 <!-- Footer -->
 <footer class="footer">
  &copy; <?php echo date("Y"); ?> Staff Staff profile- All Rights Reserved.
 </footer>

 <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<style>
/* General Page Styles */
body {
 font-family: 'Arial', sans-serif;
 background-color: #f4f4f9;
 margin: 0;
 padding: 0;
 display: flex;
 justify-content: center;
 align-items: center;
 height: 100vh;
}

/* Container for the page */
.container {
 width: 90%;
 max-width: 1200px;
 margin: 30px auto;
 padding: 30px;
 background-color: #fff;
 border-radius: 15px;
 box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Header */
header {
 text-align: center;
 margin-bottom: 20px;
}

header h1 {
 font-size: 32px;
 color: #333;
 margin-bottom: 10px;
}

header p {
 font-size: 18px;
 color: #666;
}

/* Staff Profile Section */
.staff-profile {
 display: flex;
 align-items: center;
 margin-bottom: 30px;
}

.staff-profile img {
 width: 120px;
 height: 120px;
 border-radius: 50%;
 object-fit: cover;
 margin-right: 30px;
 border: 4px solid #3498db;
}

.staff-profile .details {
 color: #333;
}

.staff-profile .details h2 {
 font-size: 24px;
 font-weight: 600;
 margin-bottom: 10px;
 color: #2980b9;
}

.staff-profile .details p {
 font-size: 16px;
 margin: 5px 0;
 color: #555;
}

/* Performance Table */
.performance-table {
 width: 100%;
 border-collapse: collapse;
 margin-top: 40px;
}

.performance-table th,
.performance-table td {
 padding: 15px;
 text-align: left;
 border-bottom: 1px solid #ddd;
}

.performance-table th {
 background-color: #2980b9;
 color: white;
}

.performance-table tr:hover {
 background-color: #f1f1f1;
}

.performance-table td {
 color: #333;
}

/* Buttons */
.btn {
 padding: 10px 20px;
 background-color: #3498db;
 color: white;
 border: none;
 border-radius: 5px;
 cursor: pointer;
 font-size: 16px;
 margin-top: 20px;
}

.btn:hover {
 background-color: #2980b9;
}
</style>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Staff History</title>
 <link rel="stylesheet" href="style.css">
</head>

<body>
 <div class="container">
  <header>
   <h1>Staff History</h1>
   <p>Here are the details and performance history of the staff member.</p>
  </header>

  <!-- Staff Profile Section -->
  <div class="staff-profile">

   <td><img src="<?php echo $staff['profile_image']; ?>" alt="Profile" width="50" height="50"></td>
   <div class="details">
    <h2><?php echo $staff['first_name'] . ' ' . $staff['middle_name'] . ' ' . $staff['last_name']; ?></h2>
    <p><strong>Email:</strong> <?php echo $staff['email']; ?></p>
    <p><strong>Telephone:</strong> <?php echo $staff['telephone']; ?></p>
    <p><strong>Position:</strong> <?php echo $staff['position']; ?></p>
    <p><strong>Department:</strong> <?php echo $staff['department']; ?></p>
    <p><strong>Salary:</strong> $<?php echo $staff['salary']; ?></p>
    <p><strong>Experience:</strong> <?php echo $staff['experience']; ?> years</p>
    <p><strong>Skills:</strong> <?php echo $staff['skills']; ?></p>
   </div>
  </div>

  <!-- Performance Table Section -->
  <h3>Staff Performance</h3>
  <table class="performance-table">
   <thead>
    <tr>
     <th>Date</th>
     <th>Performance Rating</th>
     <th>Comments</th>
    </tr>
   </thead>
   <tbody>
    <?php
                if ($performance) {
                    do {
                        echo "<tr>";
                        echo "<td>" . $performance['date'] . "</td>";
                        echo "<td>" . $performance['rating'] . "</td>";
                        echo "<td>" . $performance['comments'] . "</td>";
                        echo "</tr>";
                    } while ($performance = $performance_result->fetch_assoc());
                } else {
                    echo "<tr><td colspan='3'>No performance data available.</td></tr>";
                }
                ?>
   </tbody>
  </table>

  <!-- Update Button -->
  <a href="edit_staff.php?staff_id=<?php echo $staff['staff_id']; ?>">
   <button class="btn">Update Staff Info</button>
  </a>
 </div>
</body>

</html>