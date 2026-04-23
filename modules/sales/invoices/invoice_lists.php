

<?php
include('db.php');
session_start();

// Authentication check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// CSRF protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Initialize variables
$search = $start_date = $end_date = '';
$where = [];
$params = [];
$types = '';

// Sorting configuration
$allowed_sort = ['invoice_no', 'invoice_date', 'vendor_name', 'total_after_vat'];
$sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort) ? $_GET['sort'] : 'invoice_no';
$order = isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC' ? 'ASC' : 'DESC';

// Search filter
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = trim($_GET['search']);
    $where[] = "(invoice_no LIKE ? OR vendor_name LIKE ? OR purchase_id LIKE ? OR reference LIKE ?)";
    $params = array_merge($params, ["%$search%", "%$search%", "%$search%", "%$search%"]);
    $types .= 'ssss';
}

// Date filter
if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
    $where[] = "(invoice_date BETWEEN ? AND ?)";
    $params = array_merge($params, [$start_date, $end_date]);
    $types .= 'ss';
}

// Build WHERE clause
$where_clause = '';
if (!empty($where)) $where_clause = 'WHERE ' . implode(' AND ', $where);

// Pagination
$per_page = 15;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $per_page;

// Count total records
$count_sql = "SELECT COUNT(*) as total FROM inventory $where_clause";
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) $count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$total_records = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_records / $per_page);

// Fetch data with pagination
$sql = "SELECT * FROM inventory $where_clause ORDER BY $sort $order LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$params_page = array_merge($params, [$per_page, $offset]);
$types_page = $types . 'ii';
$stmt->bind_param($types_page, ...$params_page);
$stmt->execute();
$result = $stmt->get_result();

// Summary totals
$summary_sql = "SELECT 
    COALESCE(SUM(total_before_vat),0) as total_before_vat,
    COALESCE(SUM(vat),0) as total_vat,
    COALESCE(SUM(total_after_vat),0) as grand_total_including_Vat,
    COALESCE(SUM(qty),0) as total_quantity
    FROM inventory $where_clause";
$summary_stmt = $conn->prepare($summary_sql);
if (!empty($where)) {
    $summary_stmt->bind_param($types, ...$params);
}
$summary_stmt->execute();
$summary = $summary_stmt->get_result()->fetch_assoc();

if(isset($_SESSION['success'])): ?>
<div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
:root {
 --primary-color:#2c3e50; --secondary-color:#34495e; --accent-color:#3498db;
 --success-color:#2ecc71; --warning-color:#f39c12; --danger-color:#e74c3c;
 --light-bg:#ecf0f1; --dark-text:#2c3e50; --light-text:#fff;
}
body { background-color: var(--light-bg); font-family: 'Segoe UI', sans-serif; color: var(--dark-text); padding:20px;}
.header-container { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); padding:2rem; border-radius:.5rem; margin-bottom:2rem; color: var(--light-text); text-align:center; }
.table-container { background:white; border-radius:.5rem; overflow:hidden; box-shadow:0 2px 4px rgba(0,0,0,.05);}
.table thead th { background-color:var(--accent-color); color:white; position:sticky; top:0;}
.table tbody tr:hover { background-color: rgba(52,152,219,0.05);}
.numeric-cell { text-align:right; font-family:'Courier New', monospace;}
.action-cell { white-space:nowrap;}
</style>
</head>
<body>
<div class="container">
<div class="header-container">
 <h1><i class="bi bi-file-earmark-text"></i> Invoice Management</h1>
 <p>Total records: <?= number_format($total_records) ?></p>
</div>


<!-- Summary -->
<div class="row mb-4 text-center">
 <div class="col-md-3"><div class="card"><div class="card-body">
  <h6>Total Qty</h6><p><?= number_format($summary['total_quantity']) ?></p>
 </div></div></div>
 <div class="col-md-3"><div class="card"><div class="card-body">
  <h6>Total Before VAT</h6><p><?= number_format($summary['total_before_vat'],2) ?></p>
 </div></div></div>
 <div class="col-md-3"><div class="card"><div class="card-body">
  <h6>Total VAT</h6><p><?= number_format($summary['total_vat'],2) ?></p>
 </div></div></div>
 <div class="col-md-3"><div class="card"><div class="card-body">
  <h6>Grand Total Including Vat</h6><p><?= number_format($summary['grand_total_including_Vat'],2) ?></p>
 </div></div></div>
</div>

<!-- Search & Filter -->
<div class="card mb-4"><div class="card-body">
<form method="GET" class="row g-3">
 <div class="col-md-6"><input type="text" class="form-control" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>"></div>
 <div class="col-md-3"><input type="date" class="form-control" name="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>"></div>
 <div class="col-md-3"><input type="date" class="form-control" name="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>"></div>
 <div class="col-12 mt-2">
  <button class="btn btn-primary">Apply Filters</button>
  <a href="invoice_lists.php" class="btn btn-outline-secondary">Reset</a>
  <a href="add_inventory.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> New Invoice</a>
<!-- Export to Excel -->
    <a href="export_all_invoices_excel.php?invoice_no=<?= urlencode($row['invoice_no']) ?>" 
       class="btn btn-sm btn-success" title="Export to Excel">📄 Excel</a>
 <!-- Export to PDF 
  
     <a href="export_all_invoices_pdf.php?invoice_no=<?= urlencode($row['invoice_no']) ?>" 
       class="btn btn-sm btn-success" title="Export to Excel"> 📄 PDF</a>-->
  <!-- Back to dashboard -->
<a href="admin_dashboard.php?invoice_no=<?= urlencode($row['invoice_no']) ?>" 
       class="btn btn-sm btn-success" title="Back to dashboard">📄 Back to dashboard</a>
</div>
</form>
</div></div>

<!-- Table -->
<div class="table-container table-responsive">
<table class="table table-hover">
 <thead>
 <tr>
  <th onclick="sortTable('invoice_no')">Invoice No <?= $sort==='invoice_no'?($order==='ASC'? '↑':'↓'):'' ?></th>
  <th onclick="sortTable('invoice_date')">Date <?= $sort==='invoice_date'?($order==='ASC'? '↑':'↓'):'' ?></th>
  <th>Reference</th>
  <th>Item ID</th>
  
  <th>Description</th>
  <th class="uom-column">UOM</th>
  <th class="numeric-cell">Qty</th>
  <th class="numeric-cell">Unit Cost</th>
  <th class="numeric-cell">total Before Vat</th>
  <th class="numeric-cell">VAT</th>
  <th class="numeric-cell">Total + Vat</th>
  <th class="action-cell">Actions</th>
 </tr>
 </thead>
 <tbody>
 <?php if($result && $result->num_rows>0): ?>
 <?php while($row=$result->fetch_assoc()): ?>
 <tr>
  <td><?= htmlspecialchars($row['invoice_no']) ?></td>
  <td><?= date('d/m/Y', strtotime($row['invoice_date'])) ?></td>
  <td><?= htmlspecialchars($row['reference']) ?></td>
  <td><?= htmlspecialchars($row['item_id']) ?></td>
  
  <td><?= htmlspecialchars($row['description']) ?></td>
  <td><?= htmlspecialchars($row['uom']) ?></td>
  <td class="numeric-cell"><?= number_format($row['qty']) ?></td>
  <td class="numeric-cell"><?= number_format($row['unit_price'],2) ?></td>
  <td class="numeric-cell"><?= number_format($row['total_before_vat'],2) ?></td>
  <td class="numeric-cell"><?= number_format($row['vat'],2) ?></td>
  <td class="numeric-cell"><?= number_format($row['total_after_vat'],2) ?></td>
  <td class="action-cell">
   <div class="btn-group btn-group-sm">
     <!-- Print Invoice -->
   <a href="invoice.php?invoice_no=<?= urlencode($row['invoice_no']) ?>" class="btn btn-success" title="Print"><i class="bi bi-printer"></i></a>
    <!-- Edit Invoice -->
    <a href="edit_invoice.php?invoice_no=<?= urlencode($row['invoice_no']) ?>&edit=1" class="btn btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
    <!-- Delete Invoice -->
    <a href="delete_invoice.php?invoice_no=<?= urlencode($row['invoice_no']) ?>&csrf_token=<?= $_SESSION['csrf_token'] ?>" class="btn btn-danger" title="Delete" 
    onclick="return confirm('Are you sure you want to delete this invoice?');"><i class="bi bi-trash"></i></a>
   

   

</div>


  </td>
 </tr>
 <?php endwhile; ?>
 <?php else: ?>
 <tr><td colspan="11" class="text-center py-4">No invoices found</td></tr>
 <?php endif; ?>
 </tbody>
</table>
</div>


<!-- Pagination -->
<?php if($total_pages>1): ?>
<nav>
<ul class="pagination justify-content-center mt-3">
<li class="page-item <?= $page<=1?'disabled':'' ?>"><a class="page-link" href="?page=<?= $page-1 ?>">Prev</a></li>
<?php for($i=1;$i<=$total_pages;$i++): ?>
<li class="page-item <?= $i==$page?'active':'' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
<?php endfor; ?>
<li class="page-item <?= $page>=$total_pages?'disabled':'' ?>"><a class="page-link" href="?page=<?= $page+1 ?>">Next</a></li>
</ul>
</nav>
<?php endif; ?>

</div> 


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function sortTable(col){
 let url=new URL(window.location.href);
 let sort=url.searchParams.get('sort');
 let order=url.searchParams.get('order')||'DESC';
 if(sort===col) order=order==='DESC'?'ASC':'DESC';
 url.searchParams.set('sort',col);
 url.searchParams.set('order',order);
 window.location.href=url;
}
</script>
</body>
</html>
