<?php
require_once __DIR__ . '/db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM staff ORDER BY staff_id DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff History</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
body { background-color:#f8f9fa; font-size:13px; }
.container { margin-top:20px; }

.header {
 background:linear-gradient(90deg,#007bff,#0056b3);
 color:white;
 padding:10px;
 text-align:center;
 font-size:18px;
 font-weight:bold;
 border-radius:5px;
}

.footer {
 background:#343a40;
 color:white;
 text-align:center;
 padding:10px;
 margin-top:30px;
 border-radius:5px;
}

.table-responsive {
 background:white;
 padding:10px;
 border-radius:8px;
 box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

.profile-img {
 width:40px;
 height:40px;
 border-radius:50%;
 object-fit:cover;
 border:2px solid #007bff;
}
</style>

</head>

<body>

<div class="header">Staff History - Admin Panel</div>

<div class="container">

<!-- 🔥 YOUR ORIGINAL HEADER BLOCK (RESTORED) -->
<div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
   <h4 class="text-center flex-grow-1">Staff History</h4>
   <div class="buttons-container">
    <a href="admin_dashboard.php" class="btn btn-secondary btn-sm">← Back to Dashboard</a>
    <button id="exportExcel" class="btn btn-success btn-sm">Export to Excel</button>
    <button id="exportPDF" class="btn btn-danger btn-sm">Export to PDF</button>
    <button id="printTable" class="btn btn-primary btn-sm">Print</button>
    <a href="logout.php" class="btn btn-warning btn-sm btn-logout">Logout</a>
   </div>
</div>

<div class="table-responsive">

<table id="staffTable" class="table table-striped table-hover table-bordered">
<thead class="text-center">
<tr>
 <th>Profile</th>
 <th>ID</th>
 <th>Last</th>
 <th>Middle</th>
 <th>First</th>
 <th>Dept</th>
 <th>Position</th>
 <th>Salary</th>
 <th>Email</th>
 <th>Phone</th>
 <th>Hire</th>
 <th>End</th>
 <th>Exp</th>
 <th>Skills</th>
 <th>Action</th>
</tr>
</thead>

<tbody>

<?php while ($staff = mysqli_fetch_assoc($result)) { ?>

<tr>
 <td>
  <img src="<?= !empty($staff['profile_image']) ? $staff['profile_image'] : 'uploads/default.png'; ?>" class="profile-img">
 </td>

 <td><?= $staff['staff_id']; ?></td>
 <td><?= $staff['last_name']; ?></td>
 <td><?= $staff['middle_name']; ?></td>
 <td><?= $staff['first_name']; ?></td>
 <td><?= $staff['department']; ?></td>
 <td><?= $staff['position']; ?></td>
 <td><?= $staff['salary']; ?></td>
 <td><?= $staff['email']; ?></td>
 <td><?= $staff['telephone']; ?></td>
 <td><?= $staff['hire_date']; ?></td>
 <td><?= $staff['termination_date']; ?></td>
 <td><?= $staff['experience']; ?></td>
 <td><?= $staff['skills']; ?></td>

 <td>
   <a href="view_staff.php?staff_id=<?= $staff['staff_id']; ?>">View</a> |
   <a href="edit_staff.php?staff_id=<?= $staff['staff_id']; ?>">Edit</a> |
   <a href="delete_staff.php?staff_id=<?= $staff['staff_id']; ?>"
      onclick="return confirm('Delete this staff?')">Delete</a>
 </td>

</tr>

<?php } ?>

</tbody>
</table>

</div>
</div>

<div class="footer">ABC Company © <?= date("Y"); ?></div>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {

    let table = $('#staffTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excelHtml5', title: 'Staff_History' },
            { extend: 'pdfHtml5', title: 'Staff_History', orientation: 'landscape' },
            { extend: 'print', title: 'Staff History' }
        ]
    });

    // 🔥 connect your custom buttons
    $('#exportExcel').click(function () {
        table.button(0).trigger();
    });

    $('#exportPDF').click(function () {
        table.button(1).trigger();
    });

    $('#printTable').click(function () {
        table.button(2).trigger();
    });

});
</script>
<script>
$(document).ready(function () {
 $('#staffTable').DataTable();
});
</script>

</body>
</html>