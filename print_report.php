<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";
$database = "abc_company"; // Change to your actual database name

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch report data
$sql = "SELECT title, description, date FROM reports ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Print Report</title>
 <style>
 body {
  font-family: Arial, sans-serif;
  padding: 20px;
 }

 .report-container {
  border: 1px solid #ccc;
  padding: 20px;
  background: #f9f9f9;
 }

 @media print {
  body {
   visibility: visible;
  }

  /* Ensure content is printed */
 }
 </style>
 <script>
 // Automatically open the print dialog when the page loads
 window.onload = function() {
  window.print();
  setTimeout(() => window.close(), 500); // Auto-close the window after printing
 };
 </script>
</head>

<body>

 <div class="report-container">
  <h2>Company Report</h2>
  <hr>
  <?php if ($result->num_rows > 0): ?>
  <?php while ($row = $result->fetch_assoc()): ?>
  <h3><?php echo $row['title']; ?></h3>
  <p><strong>Date:</strong> <?php echo $row['date']; ?></p>
  <p><?php echo nl2br($row['description']); ?></p>
  <hr>
  <?php endwhile; ?>
  <?php else: ?>
  <p>No reports available.</p>
  <?php endif; ?>
 </div>

</body>

</html>

<?php
$conn->close();
?>

kkkkkkkk

<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";
$database = "abc_company"; // Change to your actual database name

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch report data
$sql = "SELECT title, description, date FROM reports ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Print Report</title>
 <style>
 body {
  font-family: Arial, sans-serif;
  padding: 20px;
 }

 .report-container {
  border: 1px solid #ccc;
  padding: 20px;
  background: #f9f9f9;
 }

 @media print {
  body {
   visibility: visible;
  }
 }
 </style>
 <script>
 window.onload = function() {
  window.print();
  setTimeout(() => window.close(), 500);
 };
 </script>
</head>

<body>

 <div class="report-container">
  <h2>Company Report</h2>
  <hr>
  <?php if ($result->num_rows > 0): ?>
  <?php while ($row = $result->fetch_assoc()): ?>
  <h3><?php echo $row['title']; ?></h3>
  <p><strong>Date:</strong> <?php echo $row['date']; ?></p>
  <p><?php echo nl2br($row['description']); ?></p>
  <hr>
  <?php endwhile; ?>
  <?php else: ?>
  <p>No reports available.</p>
  <?php endif; ?>
 </div>

</body>

</html>

<?php
$conn->close();
?>