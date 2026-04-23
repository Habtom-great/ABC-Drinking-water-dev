<?php
include 'db_connection.php';

// Fetch employee names
$employeeNames = [];
$empRes = $conn->query("SELECT id, last_name FROM employees");
if (!$empRes) {
    die("Employee query failed: " . $conn->error);
}
while ($row = $empRes->fetch_assoc()) {
    $employeeNames[$row['id']] = $row['last_name'];
}

// Fetch staff names
$staffNames = [];
$staffRes = $conn->query("SELECT staff_id, last_name FROM staff");
if (!$staffRes) {
    die("Staff query failed: " . $conn->error);
}
while ($row = $staffRes->fetch_assoc()) {
    $staffNames[$row['staff_id']] = $row['last_name'];
}

// Fetch performance records
$result = $conn->query("SELECT * FROM employee_performance ORDER BY review_date DESC");
if (!$result) {
    die("Performance query failed: " . $conn->error);
}


$host = "localhost";
$username = "root";
$password = "";
$database = "abc_company";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load employee and staff names for dropdowns and display
$employeeNames = [];
$staffNames = [];
$sql = "SELECT * FROM employee_performance ORDER BY review_date DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Error in performance query: " . $conn->error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employee_id = !empty($_POST["employee_id"]) ? $_POST["employee_id"] : null;
    $staff_id = !empty($_POST["staff_id"]) ? $_POST["staff_id"] : null;
    $review_date = $_POST["review_date"];
    $punctuality = $_POST["punctuality"];
    $work_quality = $_POST["work_quality"];
    $teamwork = $_POST["teamwork"];
    $leadership = $_POST["leadership"];
    $attendance = $_POST["attendance"];
    $communication_skills = $_POST["communication_skills"];
    $innovation = $_POST["innovation"];
    $training_completed = $_POST["training_completed"];
    $supervisor_comments = $_POST["supervisor_comments"];

    if ($employee_id || $staff_id) {
        $stmt = $conn->prepare("INSERT INTO employee_performance (
            employee_id, staff_id, review_date, punctuality, work_quality, teamwork,
            leadership, attendance, communication_skills, innovation, training_completed,
            supervisor_comments
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "iissssssssss",
            $employee_id,
            $staff_id,
            $review_date,
            $punctuality,
            $work_quality,
            $teamwork,
            $leadership,
            $attendance,
            $communication_skills,
            $innovation,
            $training_completed,
            $supervisor_comments
        );

        $stmt->execute();
        $stmt->close();
        echo "<p style='color:green;'>Performance record saved successfully!</p>";
    } else {
        echo "<p style='color:red;'>Please select either an Employee or Staff.</p>";
    }
}

// Fetch existing records
$records = $conn->query("SELECT * FROM employee_performance ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>

<head>
 <title>Employee Performance</title>
 <style>
 label {
  display: block;
  margin-top: 8px;
 }

 select,
 input,
 textarea {
  width: 100%;
  padding: 5px;
  margin-top: 4px;
 }

 table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
 }

 th,
 td {
  border: 1px solid #ccc;
  padding: 6px;
  text-align: left;
 }

 th {
  background-color: #f0f0f0;
 }

 .form-container {
  max-width: 800px;
  margin: auto;
  padding: 20px;
  border: 1px solid #ddd;
 }
 </style>
 <script>
 function validateForm() {
  const employee = document.getElementById("employee_id").value;
  const staff = document.getElementById("staff_id").value;
  if (!employee && !staff) {
   alert("Please select either an Employee or a Staff.");
   return false;
  }
  return true;
 }
 </script>
</head>

<body>

 <div class="form-container">
  <h2>Performance Review Form</h2>
  <form method="post" onsubmit="return validateForm();">
   <label for="employee_id">Select Employee (if applicable):</label>
   <select name="employee_id" id="employee_id">
    <option value="">-- Select Employee --</option>
    <?php foreach ($employeeNames as $id => $name): ?>
    <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
    <?php endforeach; ?>
   </select>

   <label for="staff_id">Select Staff (if applicable):</label>
   <select name="staff_id" id="staff_id">
    <option value="">-- Select Staff --</option>
    <?php foreach ($staffNames as $id => $name): ?>
    <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
    <?php endforeach; ?>
   </select>

   <label>Review Date:</label>
   <input type="date" name="review_date" required>

   <label>Punctuality:</label>
   <input type="text" name="punctuality" value="Excellent">

   <label>Work Quality:</label>
   <input type="text" name="work_quality" value="Excellent">

   <label>Teamwork:</label>
   <input type="text" name="teamwork" value="Excellent">

   <label>Leadership:</label>
   <input type="text" name="leadership" value="Excellent">

   <label>Attendance:</label>
   <input type="text" name="attendance" value="Excellent">

   <label>Communication Skills:</label>
   <input type="text" name="communication_skills" value="Excellent">

   <label>Innovation:</label>
   <input type="text" name="innovation" value="Excellent">

   <label>Training Completed:</label>
   <input type="text" name="training_completed" value="Excellent">

   <label>Supervisor Comments:</label>
   <textarea name="supervisor_comments" rows="3"></textarea>

   <br><br>
   <button type="submit">Submit Performance</button>
  </form>
 </div>

 <h2 style="text-align:center;">Performance Records</h2>
 <table>
  <thead>
   <tr>
    <th>ID</th>
    <th>Employee Name</th>
    <th>Staff Name</th>
    <th>Date</th>
    <th>Punctuality</th>
    <th>Work Quality</th>
    <th>Teamwork</th>
    <th>Leadership</th>
    <th>Attendance</th>
    <th>Communication</th>
    <th>Innovation</th>
    <th>Training</th>
    <th>Comments</th>
   </tr>
  </thead>
  <tbody>
   <?php while ($row = $records->fetch_assoc()): ?>
   <tr>
    <td><?= $row['id'] ?></td>
    <td><?= isset($employeeNames[$row['employee_id']]) ? $employeeNames[$row['employee_id']] : '' ?></td>
    <td><?= isset($staffNames[$row['staff_id']]) ? $staffNames[$row['staff_id']] : '' ?></td>
    <td><?= $row['review_date'] ?></td>
    <td><?= $row['punctuality'] ?></td>
    <td><?= $row['work_quality'] ?></td>
    <td><?= $row['teamwork'] ?></td>
    <td><?= $row['leadership'] ?></td>
    <td><?= $row['attendance'] ?></td>
    <td><?= $row['communication_skills'] ?></td>
    <td><?= $row['innovation'] ?></td>
    <td><?= $row['training_completed'] ?></td>
    <td><?= $row['supervisor_comments'] ?></td>
   </tr>
   <?php endwhile; ?>
  </tbody>
 </table>

</body>

</html>