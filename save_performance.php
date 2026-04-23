<?php
include 'db_connection.php';

$stmt = $conn->prepare("INSERT INTO employee_performance (employee_id, review_date, punctuality, work_quality, teamwork, leadership, attendance, communication_skills, innovation, training_completed, supervisor_comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
  "issssssssss",
  $_POST['employee_id'],
  $_POST['review_date'],
  $_POST['punctuality'],
  $_POST['work_quality'],
  $_POST['teamwork'],
  $_POST['leadership'],
  $_POST['attendance'],
  $_POST['communication_skills'],
  $_POST['innovation'],
  $_POST['training_completed'],
  $_POST['supervisor_comments']
);

if ($stmt->execute()) {
  header("Location: employee_performance.php?success=1");
} else {
  echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>