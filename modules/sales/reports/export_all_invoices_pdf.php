


<?php
ini_set('memory_limit', '1024M'); // increase to 1GB
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'fpdf/fpdf.php';
include 'db_connection.php'; // your DB connection

// Fetch all invoices
$sql = "SELECT * FROM inventory ORDER BY invoice_no ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'ABC Drinking Water - All Invoices', 0, 1, 'C');
$pdf->Ln(5);

// Table headers
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(200, 200, 200);
$headers = ['Invoice No', 'Order No', 'Vendor Name', 'Invoice Date', 'Total Before VAT', 'VAT', 'Total After VAT'];
$widths = [30, 30, 50, 25, 25, 20, 25];

foreach ($headers as $i => $header) {
    $pdf->Cell($widths[$i], 7, $header, 1, 0, 'C', true);
}
$pdf->Ln();

// Table rows
$pdf->SetFont('Arial', '', 10);

while ($row = $result->fetch_assoc()) {
    $pdf->Cell($widths[0], 6, $row['invoice_no'], 1);
    $pdf->Cell($widths[1], 6, $row['order_no'], 1);
    $pdf->Cell($widths[2], 6, $row['vendor_name'], 1);
    $pdf->Cell($widths[3], 6, $row['invoice_date'], 1);
    $pdf->Cell($widths[4], 6, number_format($row['total_before_vat'], 2), 1, 0, 'R');
    $pdf->Cell($widths[5], 6, number_format($row['vat'], 2), 1, 0, 'R');
    $pdf->Cell($widths[6], 6, number_format($row['total_after_vat'], 2), 1, 0, 'R');
    $pdf->Ln();
}

// Output PDF to browser
$pdf->Output('I', 'All_Invoices.pdf');
exit;
