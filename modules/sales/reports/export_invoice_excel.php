<?php
// export_invoice_excel.php
require 'db_connection.php';

if (!isset($_GET['invoice_no']) || empty($_GET['invoice_no'])) {
    exit("No invoice number specified.");
}

$invoice_no = $conn->real_escape_string($_GET['invoice_no']);

// Fetch invoice header info (you may need only first row since all items share invoice_no)
$invoice_result = $conn->query("SELECT * FROM inventory WHERE invoice_no='$invoice_no' LIMIT 1");

if ($invoice_result->num_rows == 0) {
    exit("Invoice not found.");
}

$invoice = $invoice_result->fetch_assoc();

// Fetch all items for this invoice
$items_result = $conn->query("SELECT * FROM inventory WHERE invoice_no='$invoice_no'");

if ($items_result->num_rows == 0) {
    exit("No items found for this invoice.");
}

// Set headers for Excel file download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Invoice_$invoice_no.xls");
header("Cache-Control: max-age=0");

// Start table
echo "<table border='1'>";
echo "<tr><th colspan='6'>Invoice #{$invoice_no}</th></tr>";
echo "<tr><th>Vendor</th><th>Invoice Date</th><th>Purchaser</th><th>Payment Method</th><th>Total Before VAT</th><th>Total After VAT</th></tr>";
echo "<tr>
        <td>{$invoice['vendor_name']}</td>
        <td>{$invoice['invoice_date']}</td>
        <td>{$invoice['purchaser_name']}</td>
        <td>{$invoice['payment_method']}</td>
        <td>{$invoice['total_before_vat']}</td>
        <td>{$invoice['total_after_vat']}</td>
      </tr>";
echo "</table><br>";

// Table of items
echo "<table border='1'>";
echo "<tr>
        <th>Item ID</th>
        <th>Description</th>
        <th>UOM</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Total</th>
      </tr>";

while ($item = $items_result->fetch_assoc()) {
    $total = $item['qty'] * $item['unit_price'];
    echo "<tr>
            <td>{$item['item_id']}</td>
            <td>{$item['description']}</td>
            <td>{$item['uom']}</td>
            <td>{$item['qty']}</td>
            <td>{$item['unit_price']}</td>
            <td>$total</td>
          </tr>";
}

echo "</table>";
exit;
?>
