<?php
// Example assuming you're using MySQLi or PDO to connect

include 'db_connection.php'; // Adjust to your DB connection file

$id = $_POST['id'] ?? null;
$date = $_POST['date'] ?? '';
$sales_order_no = $_POST['sales_order_no'] ?? '';
$salesperson_id = $_POST['salesperson_id'] ?? '';
$branch_id = $_POST['branch_id'] ?? '';
$invoice_no = $_POST['invoice_no'] ?? '';
$item_description = $_POST['item_description'] ?? '';
$quantity = $_POST['quantity'] ?? 0;
$unit_price = $_POST['unit_price'] ?? 0;
$total_sales_before_vat = $_POST['total_sales_before_vat'] ?? 0;
$vat = $_POST['vat'] ?? 0;
$total_sales_after_vat = $_POST['total_sales_after_vat'] ?? 0;

// Validate required data
if ($id !== null) {
    $sql = "UPDATE sales SET 
                date = '$date',
                sales_order_no = '$sales_order_no',
                salesperson_id = '$salesperson_id',
                branch_id = '$branch_id',
                invoice_no = '$invoice_no',
                item_description = '$item_description',
                quantity = $quantity,
                unit_price = $unit_price,
                total_sales_before_vat = $total_sales_before_vat,
                vat = $vat,
                total_sales_after_vat = $total_sales_after_vat
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    echo "Missing sales ID for update.";
}

$conn->close();
?>