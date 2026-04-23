<?php
// Include PhpSpreadsheet
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set column headers
$sheet->setCellValue('A1', 'Sales Order Number')
      ->setCellValue('B1', 'Salesperson ID')
      ->setCellValue('C1', 'Salesperson Name')
      // Add more columns here

// Fetch sales order data from database
// Example query
$query = "SELECT * FROM sales_orders";
$result = $conn->query($query);
$rowNumber = 2;

while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNumber, $row['sales_order_no']);
    $sheet->setCellValue('B' . $rowNumber, $row['salesperson_id']);
    $sheet->setCellValue('C' . $rowNumber, $row['salesperson_name']);
    // Add more columns here
    $rowNumber++;
}

$writer = new Xlsx($spreadsheet);
$writer->save('sales_order.xlsx');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="sales_order.xlsx"');
$writer->save('php://output');
?>