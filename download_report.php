<?php
// Include PhpWord
require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Database connection
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";
$database = "abc_company"; // Change to your actual database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch report data
$sql = "SELECT title, description, date FROM reports ORDER BY date DESC";
$result = $conn->query($sql);

// Create a new Word document
$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Add title
$section->addText("Company Report", ['bold' => true, 'size' => 16]);

// Add report data
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $section->addText("\nTitle: " . $row['title'], ['bold' => true]);
        $section->addText("Date: " . $row['date']);
        $section->addText("Description: " . $row['description']);
        $section->addText("----------------------------------");
    }
} else {
    $section->addText("No reports available.");
}

// Close database connection
$conn->close();

// Generate Word file
$filename = "Company_Report_" . date("Y-m-d") . ".docx";
header("Content-Description: File Transfer");
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Transfer-Encoding: binary');

// Save the file to PHP output
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save("php://output");

exit;
?>