<?php
// Include TCPDF library
require 'tcpdf/tcpdf.php';

$pdf = new TCPDF();
$pdf->AddPage();

// Set title
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Sales Order', 0, 1, 'C');

// Add sales order data
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Sales Order Number: ' . $sales_order_no, 0, 1);
$pdf->Cell(0, 10, 'Salesperson ID: ' . $salesperson_id, 0, 1);
$pdf->Cell(0, 10, 'Salesperson Name: ' . $salesperson_name, 0, 1);
// Add more fields here

// Output PDF
$pdf->Output('sales_order.pdf', 'D');
?>