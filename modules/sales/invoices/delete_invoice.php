<?php
session_start();
include('db.php');

// 1️⃣ Authentication check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2️⃣ CSRF protection
if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token");
}

// 3️⃣ Check invoice_no parameter
if (!isset($_GET['invoice_no']) || empty(trim($_GET['invoice_no']))) {
    die("Invoice number not specified.");
}

$invoice_no = trim($_GET['invoice_no']);
$deleted_by = $_SESSION['user_id'];

// 4️⃣ Check if invoice exists
$stmt_check = $conn->prepare("SELECT * FROM inventory WHERE TRIM(invoice_no) = ?");
$stmt_check->bind_param("s", $invoice_no);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    $_SESSION['error'] = "Invoice '$invoice_no' not found in the database.";
    header("Location: invoice_lists.php");
    exit();
}

// Optional: get invoice info for message
$invoice = $result_check->fetch_assoc();

// 5️⃣ Delete invoice (and optionally related items)
$stmt_delete = $conn->prepare("DELETE FROM inventory WHERE TRIM(invoice_no) = ?");
$stmt_delete->bind_param("s", $invoice_no);

if ($stmt_delete->execute()) {
    $_SESSION['success'] = "Invoice <strong>{$invoice['invoice_no']}</strong> deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting invoice: " . $stmt_delete->error;
}

// 6️⃣ Close statements
$stmt_check->close();
$stmt_delete->close();

// 7️⃣ Redirect back to invoice list
header("Location: invoice_lists.php");
exit();
?>
