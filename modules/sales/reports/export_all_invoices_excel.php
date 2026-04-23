<?php
require 'db_connection.php';

// Fetch all invoices (you might need DISTINCT invoice_no if items are repeated per invoice)
$result = $conn->query("
    SELECT invoice_no, vendor_name, invoice_date, purchaser_name, payment_method, total_before_vat, total_after_vat
    FROM inventory
    GROUP BY invoice_no
    ORDER BY invoice_date DESC
");

if (!$result || $result->num_rows == 0) {
    exit("No invoices found.");
}

// Set headers for Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=All_Invoices.xls");
header("Cache-Control: max-age=0");

// Start table
echo "<table border='1'>";
echo "<tr>
        <th>Invoice No</th>
        <th>Vendor Name</th>
        <th>Invoice Date</th>
        <th>Purchaser Name</th>
        <th>Payment Method</th>
        <th>Total Before VAT</th>
        <th>Total After VAT</th>
      </tr>";

// Loop through all invoices
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['invoice_no']}</td>
            <td>{$row['vendor_name']}</td>
            <td>{$row['invoice_date']}</td>
            <td>{$row['purchaser_name']}</td>
            <td>{$row['payment_method']}</td>
            <td>{$row['total_before_vat']}</td>
            <td>{$row['total_after_vat']}</td>
          </tr>";
}

echo "</table>";
exit;
