<?php
session_start();
include 'db_connection.php';

// Ensure admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Validate staff ID
if (!isset($_GET['staff_id']) || !is_numeric($_GET['staff_id'])) {
    die("Invalid staff ID.");
}

$staff_id = $_GET['staff_id'];

// Fetch staff details
$staff_query = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
$staff_query->bind_param("i", $staff_id);
$staff_query->execute();
$staff_result = $staff_query->get_result();
$staff = $staff_result->fetch_assoc();

if (!$staff) {
    die("Staff member not found.");
}

// Fetch performance history

$performance_query = $conn->prepare("SELECT * FROM employee_performance WHERE staff_id = ?");
if (!$performance_query) {
    die("Performance query prepare failed: " . $conn->error);// shows the actual error
}
$performance_query->bind_param("i", $staff_id);

if (!$performance_query->execute()) {
    die("Performance query execution failed: " . $performance_query->error);
}

$performance_result = $performance_query->get_result();

if ($performance_result && $performance_result->num_rows > 0) {
    // Display results
} else {
    echo "<p>No performance data found.</p>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <title>Staff History</title>
 <link rel="stylesheet" href="assets/css/bootstrap.min.css">
 <style>
 body {
  background-color: #f9fbfd;
  font-family: 'Segoe UI', sans-serif;
 }

 .container {
  margin-top: 40px;
 }

 .profile-image {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
 }

 .table th {
  background-color: #4e73df;
  color: white;
 }

 .back-btn {
  margin-top: 20px;
 }
 </style>
</head>

<body>

 <div class="container">
  <h2 class="text-center">Performance History for Employee ID: <?php echo htmlspecialchars($staff_id); ?></h2>
  <hr>

  <!-- Staff Basic Info -->
  <div class="row mb-4">
   <div class="col-md-3 text-center">
    <img src="<?php echo htmlspecialchars($staff['profile_image']); ?>" class="profile-image" alt="Profile">
   </div>
   <div class="col-md-9">
    <p><strong>Name:</strong>
     <?php echo htmlspecialchars($staff['last_name'] . ' ' . $staff['middle_name'] . ' ' . $staff['first_name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($staff['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($staff['telephone']); ?></p>
    <p><strong>Position:</strong> <?php echo htmlspecialchars($staff['position']); ?></p>
    <p><strong>Department:</strong> <?php echo htmlspecialchars($staff['department']); ?></p>
    <p><strong>Salary:</strong> $<?php echo number_format($staff['salary'], 2); ?></p>
    <p><strong>Experience:</strong> <?php echo htmlspecialchars($staff['experience']); ?> years</p>
    <p><strong>Skills:</strong> <?php echo htmlspecialchars($staff['skills']); ?></p>
   </div>
  </div>

  <!-- Performance Table -->
  <h4>Performance Records</h4>
  <div class="table-responsive">
   <table class="table table-bordered table-striped">
    <thead>
     <tr>
      <th>Review Date</th>
      <th>Punctuality</th>
      <th>Teamwork</th>
      <th>Initiative</th>
      <th>Technical Skill</th>
      <th>Productivity</th>
      <th>Leadership</th>
      <th>Communication</th>
      <th>Remarks</th>
     </tr>
    </thead>
    <tbody>
     <?php if ($performance_result->num_rows > 0): ?>
     <?php while ($row = $performance_result->fetch_assoc()): ?>
     <tr>
      <td><?php echo htmlspecialchars($row['review_date']); ?></td>
      <td><?php echo htmlspecialchars($row['punctuality']); ?></td>
      <td><?php echo htmlspecialchars($row['teamwork']); ?></td>
      <td><?php echo htmlspecialchars($row['initiative']); ?></td>
      <td><?php echo htmlspecialchars($row['technical_skill']); ?></td>
      <td><?php echo htmlspecialchars($row['productivity']); ?></td>
      <td><?php echo htmlspecialchars($row['leadership']); ?></td>
      <td><?php echo htmlspecialchars($row['communication']); ?></td>
      <td><?php echo htmlspecialchars($row['remarks']); ?></td>
     </tr>
     <?php endwhile; ?>
     <?php else: ?>
     <tr>
      <td colspan="9" class="text-center text-muted">No performance data found.</td>
     </tr>
     <?php endif; ?>
    </tbody>
   </table>
  </div>

  <a href="manage_staff.php" class="btn btn-secondary back-btn">Back to Staff List</a>
 </div>

</body>

</html>