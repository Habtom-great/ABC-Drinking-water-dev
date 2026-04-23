<?php
include 'db_connection.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=performance_data.csv');

$output = fopen("php://output", "w");
fputcsv($output, ['ID', 'Employee ID', 'Review Date', 'Punctuality', 'Work Quality', 'Teamwork', 'Leadership', 'Attendance', 'Communication Skills', 'Innovation', 'Training Completed', 'Supervisor Comments']);

$result = $conn->query("SELECT * FROM employee_performance");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
?>