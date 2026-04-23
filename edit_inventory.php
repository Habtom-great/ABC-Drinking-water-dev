<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Check invoice ID
if (!isset($_GET['invoice_id']) || empty($_GET['invoice_id'])) {
    die("No invoice ID provided.");
}

$invoice_id = intval($_GET['invoice_id']);

// Fetch invoice info
$stmt = $conn->prepare("SELECT * FROM invoices WHERE id = ?");
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$invoice_result = $stmt->get_result();

if ($invoice_result->num_rows === 0) {
    die("Invoice not found.");
}
$invoice = $invoice_result->fetch_assoc();

// Fetch invoice items
$item_stmt = $conn->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
$item_stmt->bind_param("i", $invoice_id);
$item_stmt->execute();
$item_result = $item_stmt->get_result();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total_before_vat = 0;

    // Delete items that were removed
    $existing_ids = array_column($item_result->fetch_all(MYSQLI_ASSOC), 'id');
    $submitted_ids = isset($_POST['qty']) ? array_keys($_POST['qty']) : [];
    $ids_to_delete = array_diff($existing_ids, $submitted_ids);

    if (!empty($ids_to_delete)) {
        $in = implode(',', array_map('intval', $ids_to_delete));
        $conn->query("DELETE FROM invoice_items WHERE id IN ($in)");
    }

    // Update existing items and add new items
    foreach ($_POST['item_id'] as $key => $item_id) {
        $description = $_POST['description'][$key];
        $qty = intval($_POST['qty'][$key]);
        $unit_cost = floatval($_POST['unit_cost'][$key]);
        $subtotal = $qty * $unit_cost;
        $vat = $subtotal * 0.15;
        $total = $subtotal + $vat;

        if (!empty($_POST['db_id'][$key])) {
            // Update existing
            $update_stmt = $conn->prepare("
                UPDATE invoice_items 
                SET item_id=?, description=?, quantity=?, unit_cost=?, subtotal=?, vat=?, total=? 
                WHERE id=?
            ");
            $update_stmt->bind_param("ssiddddi", $item_id, $description, $qty, $unit_cost, $subtotal, $vat, $total, $_POST['db_id'][$key]);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            // Insert new
            $insert_stmt = $conn->prepare("
                INSERT INTO invoice_items (invoice_id, item_id, description, quantity, unit_cost, subtotal, vat, total)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insert_stmt->bind_param("issidddd", $invoice_id, $item_id, $description, $qty, $unit_cost, $subtotal, $vat, $total);
            $insert_stmt->execute();
            $insert_stmt->close();
        }

        $total_before_vat += $subtotal;
    }

    // Update invoice totals
    $vat_total = $total_before_vat * 0.15;
    $grand_total = $total_before_vat + $vat_total;
    $update_invoice_stmt = $conn->prepare("
        UPDATE invoices SET total_before_vat=?, vat=?, total_after_vat=? WHERE id=?
    ");
    $update_invoice_stmt->bind_param("dddi", $total_before_vat, $vat_total, $grand_total, $invoice_id);
    $update_invoice_stmt->execute();
    $update_invoice_stmt->close();

    $success = "Invoice updated successfully!";

    // Refresh items
    $item_stmt->execute();
    $item_result = $item_stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Invoice #<?= htmlspecialchars($invoice['id']) ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
.numeric-cell { text-align: right; }
.alert { padding: 10px; margin-bottom: 10px; }
.alert-success { background: #d4edda; color: #155724; }
.alert-danger { background: #f8d7da; color: #721c24; }
</style>
<script>
function addRow() {
    let table = document.getElementById("itemsTable").getElementsByTagName('tbody')[0];
    let row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" name="item_id[]" class="form-control" required></td>
        <td><input type="text" name="description[]" class="form-control" required></td>
        <td><input type="number" name="qty[]" class="form-control" min="0" value="0" required></td>
        <td><input type="number" step="0.01" name="unit_cost[]" class="form-control" min="0" value="0" required></td>
        <td>0.00</td>
        <td>0.00</td>
        <td>0.00</td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">X</button></td>
        <input type="hidden" name="db_id[]" value="">
    `;
}
function deleteRow(btn) {
    let row = btn.closest("tr");
    row.remove();
}
</script>
</head>
<body>
<div class="container mt-4">
    <h2>Edit Invoice #<?= $invoice['id'] ?></h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <table class="table table-bordered" id="itemsTable">
            <thead class="table-light">
                <tr>
                    <th>Item ID</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit Cost</th>
                    <th>Subtotal</th>
                    <th>VAT</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = $item_result->fetch_assoc()): ?>
                <tr>
                    <td><input type="text" name="item_id[]" value="<?= htmlspecialchars($item['item_id']) ?>" class="form-control" required></td>
                    <td><input type="text" name="description[]" value="<?= htmlspecialchars($item['description']) ?>" class="form-control" required></td>
                    <td><input type="number" name="qty[]" value="<?= $item['quantity'] ?>" class="form-control" min="0" required></td>
                    <td><input type="number" step="0.01" name="unit_cost[]" value="<?= $item['unit_cost'] ?>" class="form-control" min="0" required></td>
                    <td class="numeric-cell"><?= number_format($item['subtotal'], 2) ?></td>
                    <td class="numeric-cell"><?= number_format($item['vat'], 2) ?></td>
                    <td class="numeric-cell"><?= number_format($item['total'], 2) ?></td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">X</button></td>
                    <input type="hidden" name="db_id[]" value="<?= $item['id'] ?>">
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button type="button" class="btn btn-success" onclick="addRow()">+ Add Item</button>
        <button type="submit" class="btn btn-primary">Update Invoice</button>
        <a href="invoice_list.php" class="btn btn-secondary">Back to List</a>
    </form>
</div>
</body>
</html>
