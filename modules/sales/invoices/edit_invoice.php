<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* =========================
   1. GET INVOICE NUMBER
========================= */
if (!isset($_GET['invoice_no'])) {
    die("Invoice number missing");
}

$invoice_no = $_GET['invoice_no'];

/* =========================
   2. FETCH INVOICE HEADER
========================= */
$stmt = $conn->prepare("
    SELECT * FROM inventory 
    WHERE invoice_no = ?
    LIMIT 1
");
$stmt->bind_param("s", $invoice_no);
$stmt->execute();
$invoice = $stmt->get_result()->fetch_assoc();

if (!$invoice) {
    die("Invoice not found");
}

/* =========================
   3. FETCH ITEMS
========================= */
$stmt = $conn->prepare("
    SELECT item_id, description, uom, qty, unit_price
    FROM inventory
    WHERE invoice_no = ?
");
$stmt->bind_param("s", $invoice_no);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* =========================
   4. UPDATE LOGIC
========================= */
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $order_no       = $_POST['order_no'];
    $reference      = $_POST['reference'];
    $invoice_date   = $_POST['invoice_date'];
    $vendor_id      = $_POST['vendor_id'];
    $vendor_company = $_POST['vendor_company_name'];
    $vendor_name    = $_POST['vendor_name'];
    $vendor_tin     = $_POST['vendor_tin_no'];
    $vendor_phone   = $_POST['vendor_phone'];
    $purchaser_id   = $_POST['purchaser_id'];
    $purchaser_name = $_POST['purchaser_name'];
    $payment_method = $_POST['payment_method'];

    $item_ids    = $_POST['item_id'];
    $descs       = $_POST['description'];
    $uoms        = $_POST['uom'];
    $qtys        = $_POST['qty'];
    $prices      = $_POST['unit_price'];

    $total_before_vat = 0;
    foreach ($qtys as $i => $q) {
        $total_before_vat += $q * $prices[$i];
    }
    $vat = $total_before_vat * 0.15;
    $total_after_vat = $total_before_vat + $vat;

    $conn->begin_transaction();

    try {
        /* DELETE OLD ITEMS */
        $del = $conn->prepare("DELETE FROM inventory WHERE invoice_no = ?");
        $del->bind_param("s", $invoice_no);
        $del->execute();

        /* INSERT UPDATED ITEMS */
        $ins = $conn->prepare("
            INSERT INTO inventory (
                order_no, invoice_no, reference, invoice_date,
                vendor_id, vendor_company_name, vendor_name,
                vendor_tin_no, vendor_telephone,
                purchaser_id, purchaser_name, payment_method,
                total_before_vat, vat, total_after_vat,
                created_by, created_at,
                item_id, description, uom, qty, unit_price
            ) VALUES (
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),?,?,?,?,?
            )
        ");

        foreach ($item_ids as $i => $item) {
            $ins->bind_param(
                "ssssssssssssdddssssdd",
                $order_no,
                $invoice_no,
                $reference,
                $invoice_date,
                $vendor_id,
                $vendor_company,
                $vendor_name,
                $vendor_tin,
                $vendor_phone,
                $purchaser_id,
                $purchaser_name,
                $payment_method,
                $total_before_vat,
                $vat,
                $total_after_vat,
                $_SESSION['user_id'],
                $item_ids[$i],
                $descs[$i],
                $uoms[$i],
                $qtys[$i],
                $prices[$i]
            );
            $ins->execute();
        }

        $conn->commit();
        $success = "Invoice updated successfully";

    } catch (Exception $e) {
        $conn->rollback();
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Invoice #<?= htmlspecialchars($invoice_no) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
input { font-size: 0.9rem; }
.table td input { width: 100%; }
</style>
</head>
<body>
<div class="container mt-4">
<h4>Edit Invoice #<?= htmlspecialchars($invoice_no) ?></h4>

<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

<form method="POST">
<div class="row g-2">
<input name="order_no" value="<?= $invoice['order_no'] ?>" class="form-control col" placeholder="Order No" required>
<input name="reference" value="<?= $invoice['reference'] ?>" class="form-control col" placeholder="Reference">
<input type="date" name="invoice_date" value="<?= $invoice['invoice_date'] ?>" class="form-control col" required>
</div>

<div class="row g-2 mt-2">
<input name="vendor_id" value="<?= $invoice['vendor_id'] ?>" class="form-control col" placeholder="Vendor ID" required>
<input name="vendor_company_name" value="<?= $invoice['vendor_company_name'] ?>" class="form-control col" placeholder="Company Name" required>
<input name="vendor_name" value="<?= $invoice['vendor_name'] ?>" class="form-control col" placeholder="Vendor Name" required>
<input name="vendor_tin_no" value="<?= $invoice['vendor_tin_no'] ?>" class="form-control col" placeholder="TIN No" required>
</div>

<div class="row g-2 mt-2">
<input name="vendor_phone" value="<?= $invoice['vendor_telephone'] ?>" class="form-control col" placeholder="Phone" required>
<input name="purchaser_id" value="<?= $invoice['purchaser_id'] ?>" class="form-control col" placeholder="Purchaser ID" required>
<input name="purchaser_name" value="<?= $invoice['purchaser_name'] ?>" class="form-control col" placeholder="Purchaser Name" required>
<select name="payment_method" class="form-select col" required>
<option value="">Payment Method</option>
<option value="Cash" <?= $invoice['payment_method']=='Cash'?'selected':'' ?>>Cash</option>
<option value="Cheque" <?= $invoice['payment_method']=='Cheque'?'selected':'' ?>>Cheque</option>
<option value="Bank Transfer" <?= $invoice['payment_method']=='Bank Transfer'?'selected':'' ?>>Bank Transfer</option>
</select>
</div>

<table class="table table-bordered mt-3">
<thead>
<tr>
<th>Item</th><th>Description</th><th>UOM</th>
<th>Qty</th><th>Price</th><th>Action</th>
</tr>
</thead>
<tbody id="items">
<?php foreach ($items as $it): ?>
<tr>
<td><input name="item_id[]" value="<?= $it['item_id'] ?>" class="form-control" required></td>
<td><input name="description[]" value="<?= $it['description'] ?>" class="form-control"></td>
<td><input name="uom[]" value="<?= $it['uom'] ?>" class="form-control"></td>
<td><input name="qty[]" value="<?= $it['qty'] ?>" class="form-control" required></td>
<td><input name="unit_price[]" value="<?= $it['unit_price'] ?>" class="form-control" required></td>
<td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">X</button></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<button type="button" class="btn btn-success btn-sm" onclick="addRow()">+ Add Item</button>
<button type="submit" class="btn btn-primary">Update Invoice</button>
<a href="invoice_lists.php" class="btn btn-secondary">Back</a>
</form>
</div>

<script>
function addRow() {
    const tbody = document.getElementById('items');
    const row = tbody.rows[0].cloneNode(true);
    row.querySelectorAll('input').forEach(i=>i.value='');
    tbody.appendChild(row);
}
</script>
</body>
</html>