<?php
include('db.php');

// Allowed columns for sorting
$allowed_sort = [
    'invoice_no' => 'sales.invoice_no',
    'invoice_date' => 'sales.invoice_date',
    'item_id' => 'inventory.item_id',
    'customer_name' => 'sales.customer_name',
    'salesperson_name' => 'sales.salesperson_name',
    'payment_method' => 'sales.payment_method',
    'total_sales_before_vat' => 'sales.total_sales_before_vat'
];

// Determine sort key
$sort_key = $_GET['sort'] ?? 'invoice_no';
$order_by = $allowed_sort[$sort_key] ?? 'sales.invoice_no';

// Handle deletion
if (isset($_GET['invoice_no'])) {
    $invoice_no = $_GET['invoice_no'];
    $stmt = $conn->prepare("DELETE FROM sales WHERE invoice_no = ?");
    $stmt->bind_param("s", $invoice_no);
    if ($stmt->execute()) {
        header("Location: sales_invoice_list.php?success=Deleted");
        exit();
    } else {
        echo "Error deleting invoice: " . $conn->error;
    }
}

// Optional search
$search = $_GET['search'] ?? '';
$search_sql = '';
$params = [];
$types = '';

if ($search) {
    $search_sql = "WHERE sales.invoice_no LIKE ? OR sales.customer_name LIKE ? OR sales.invoice_date LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
    $types = "sss";
}

// Fetch sales invoices with inventory
$sql = "SELECT sales.*, inventory.item_id, inventory.description 
        FROM sales
        LEFT JOIN inventory ON sales.item_id = inventory.item_id
        $search_sql
        ORDER BY $order_by DESC";

$stmt = $conn->prepare($sql);
if ($search && $stmt) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$total_invoices = $result->num_rows;
?>


<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <title>Sales Invoice List</title>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Open+Sans:wght@300;400&display=swap"
  rel="stylesheet">
 <style>
 body {
  background-color: #f5f5f5;
  font-family: 'Roboto', sans-serif;
  color: #333;
 }

 h2 {
  color: #fff;
  font-size: 36px;
  text-align: center;
  margin-bottom: 20px;
 }

 .container-fluid {
  background-color: #2d3748;
  padding: 30px;
  border-radius: 8px;
 }

 .invoice-count {
  text-align: center;
  font-size: 18px;
  color: #ddd;
  margin-bottom: 30px;
 }

 .table thead th {
  background-color: #0d6efd;
  color: #fff;
  text-align: center;
  font-size: 16px;
  font-weight: 500;
 }

 .table td,
 .table th {
  text-align: center;
  vertical-align: middle;
  font-size: 14px;
  padding: 10px;
 }

 .btn-group .btn {
  padding: 8px 15px;
  font-size: 14px;
  transition: all 0.3s;
 }

 .btn-print {
  background-color: #28a745;
  color: white;
 }

 .btn-edit {
  background-color: #ffc107;
  color: black;
 }

 .btn-delete {
  background-color: #dc3545;
  color: white;
 }

 .btn:hover {
  opacity: 0.85;
  transform: scale(1.05);
 }

 .table-responsive {
  margin-top: 20px;
 }

 .no-data {
  text-align: center;
  font-style: italic;
  color: #aaa;
  padding: 20px;
 }

 footer {
  text-align: center;
  padding: 20px;
  margin-top: 40px;
  color: #666;
  background-color: #2d3748;
  border-radius: 8px;
 }

 /* Responsive Design */
 @media (max-width: 768px) {

  .table td,
  .table th {
   font-size: 12px;
  }

  .btn-group .btn {
   font-size: 12px;
   padding: 5px 10px;
  }

  .invoice-count {
   font-size: 14px;
  }

  h2 {
   font-size: 28px;
  }
 }
 </style>
</head>

<body>

 <div class="container-fluid">
  <h2>📄 Sales Invoice List</h2>
  <div class="invoice-count">
   Total Invoices: <strong><?= $total_invoices ?></strong>
  </div>
  <form method="GET">
   <input type="text" name="search" placeholder="Search invoice/customer/date" value="<?= $_GET['search'] ?? '' ?>">
   <button type="submit">🔍 Search</button>
  </form>
  <!-- Table -->
  <div class="table-responsive">
   <table class="table table-bordered table-striped table-hover">
    <thead>
     <tr>
      <th><a href="?sort=invoice_no" class="text-white">Invoice No</a></th>
      <th><a href="?sort=date" class="text-white">Invoice Date</a></th>
      <th><a href="?sort=item_id" class="text-white">Item ID</a></th>
      <th><a href="?sort=customer_name" class="text-white">Customer</a></th>
      <th><a href="?sort=salesperson_name" class="text-white">Salesperson</a></th>
      <th><a href="?sort=payment_method" class="text-white">Payment Method</a></th>
      <th>Total Before VAT</th>
      <th>VAT</th>
      <th>Total After VAT</th>
      <th>Action</th>
     </tr>
    </thead>
    <tbody>
     <?php if ($result && $result->num_rows > 0): ?>
     <?php while ($row = $result->fetch_assoc()): ?>
     <tr>
      <td><?= htmlspecialchars($row['invoice_no'] ?? 'N/A') ?></td>
      <td><?= htmlspecialchars($row['date'] ?? 'N/A') ?></td>
      <td><?= htmlspecialchars($row['item_id'] ?? '-') ?></td>
      <td><?= htmlspecialchars($row['customer_name'] ?? '-') ?></td>
      <td><?= htmlspecialchars($row['salesperson_name'] ?? '-') ?></td>
      <td><?= htmlspecialchars($row['payment_method'] ?? '-') ?></td>
      <td><?= number_format((float)($row['total_sales_before_vat'] ?? 0), 2) ?></td>
      <td><?= number_format((float)($row['vat'] ?? 0), 2) ?></td>
      <td><?= number_format((float)($row['total_sales_after_vat'] ?? 0), 2) ?></td>
      <td>
       <div class="btn-group" role="group">
        <a href="invoice.php?invoice_no=<?= urlencode($row['invoice_no']) ?>" class="btn btn-sm btn-print"
         title="Print">🖨️</a>
        <a href="add_sales.php?sales_invoice_no=<?= urlencode($row['invoice_no']) ?>&edit=1" class="btn btn-sm btn-edit"
         title="Edit">✏️</a>
        <a href="sales_invoice_list.php?invoice_no=<?= urlencode($row['invoice_no']) ?>"
         onclick="return confirm('Are you sure you want to delete this invoice?');" class="btn btn-sm btn-delete"
         title="Delete">🗑️</a>
       </div>
      </td>
     </tr>
     <?php endwhile; ?>
     <?php else: ?>
     <tr>
      <td colspan="10" class="no-data">🚫 No invoices found.</td>
     </tr>
     <?php endif; ?>
    </tbody>
   </table>
  </div>
 </div>

 <footer>
  &copy; <?= date("Y") ?> ABC Company PLC. All Rights Reserved.
 </footer>

</body>

</html>