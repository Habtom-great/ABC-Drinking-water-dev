<?php
// Include PHPWord library
require 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;

$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Set the title
$section->addText('Sales Order');

$section->addText('Sales Order Number: ' . $sales_order_no);
$section->addText('Salesperson ID: ' . $salesperson_id);
$section->addText('Salesperson Name: ' . $salesperson_name);
// Add more fields here

// Save Word document
$filename = 'sales_order.docx';
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="' . $filename . '"');
$phpWord->save('php://output');
?>