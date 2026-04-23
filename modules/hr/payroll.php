<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ================= DATABASE CONNECTION =================
$conn = new mysqli("localhost","root","","abc_company");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ================= PAYROLL CALCULATION =================
function calculatePayroll($basicSalary, $otherTaxes){
    $taxBrackets = [
        [600,0,0],
        [1650,0.10,60],
        [3200,0.15,142.5],
        [5250,0.20,302.5],
        [7800,0.25,565],
        [10900,0.30,955],
        [PHP_INT_MAX,0.35,1500]
    ];

    $salaryTax = 0;
    foreach($taxBrackets as $b){
        if($basicSalary <= $b[0]){
            $salaryTax = max(0, ($basicSalary * $b[1]) - $b[2]);
            break;
        }
    }

    $empPen = $basicSalary * 0.07;
    $coPen = $basicSalary * 0.11;
    $totalDeduct = $salaryTax + $empPen + $otherTaxes;
    $netPay = $basicSalary - $totalDeduct;

    return [$salaryTax, $empPen, $coPen, $totalDeduct, $netPay];
}

// ================= ADD/UPDATE EMPLOYEE =================
$message = "";
$editData = ['id'=>'','last_name'=>'','middle_name'=>'','first_name'=>'','position'=>'','department'=>'','basic_salary'=>'','others'=>0];

if(isset($_GET['edit_id'])){
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT * FROM payroll WHERE id=?");
    $stmt->bind_param("i",$edit_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows) $editData = $res->fetch_assoc();
    $stmt->close();
}

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['add_employee'])){
    $lastName = trim($_POST['last_name'] ?? '');
    $middleName = trim($_POST['middle_name'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $basicSalary = floatval($_POST['basic_salary'] ?? 0);
    $otherTaxes = floatval($_POST['others'] ?? 0);

    if($lastName && $firstName && $position && $department && $basicSalary>0){
        list($incomeTax,$empPen,$coPen,$totalDeduct,$netPay) = calculatePayroll($basicSalary,$otherTaxes);

        if(!empty($_POST['edit_id'])){
            $id = intval($_POST['edit_id']);
            $stmt = $conn->prepare("
                UPDATE payroll SET
                last_name=?, middle_name=?, first_name=?, position=?, department=?,
                basic_salary=?, others=?, income_tax=?, emp_pension=?, co_pension=?,
                total_deductions=?, net_pay=? WHERE id=?
            ");
            $stmt->bind_param("sssssdddddddi",
                $lastName,$middleName,$firstName,$position,$department,
                $basicSalary,$otherTaxes,$incomeTax,$empPen,$coPen,
                $totalDeduct,$netPay,$id
            );
            $stmt->execute();
            $stmt->close();
            $message = "Employee updated successfully!";
        } else {
            $stmt = $conn->prepare("
                INSERT INTO payroll
                (last_name,middle_name,first_name,position,department,basic_salary,others,
                 income_tax,emp_pension,co_pension,total_deductions,net_pay)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
            ");
            $stmt->bind_param("sssssddddddd",
                $lastName,$middleName,$firstName,$position,$department,
                $basicSalary,$otherTaxes,$incomeTax,$empPen,$coPen,
                $totalDeduct,$netPay
            );
            $stmt->execute();
            $stmt->close();
            $message = "Employee added successfully!";
        }
        header("Location: payroll.php");
        exit;
    } else {
        $message = "Please fill all required fields.";
    }
}

// ================= DELETE =================
if(isset($_GET['delete_id'])){
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM payroll WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();
    header("Location: payroll.php");
    exit;
}

// ================= FETCH =================
$result = $conn->query("SELECT * FROM payroll ORDER BY id DESC");

// ================= EXPORT TO EXCEL =================
if(isset($_GET['export']) && $_GET['export']=='excel'){
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=payroll.xls");
    echo "ID\tLast\tMiddle\tFirst\tPosition\tDept\tSalary\tTax\tEmp Pen\tCo Pen\tOther\tDeduct\tNet\n";
    while($row=$result->fetch_assoc()){
        list($tax,$emp,$co,$ded,$net)=calculatePayroll($row['basic_salary'],$row['others']);
        echo "{$row['id']}\t{$row['last_name']}\t{$row['middle_name']}\t{$row['first_name']}\t{$row['position']}\t{$row['department']}\t{$row['basic_salary']}\t$tax\t$emp\t$co\t{$row['others']}\t$ded\t$net\n";
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payroll Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f4f6f9}
.box{background:#fff;padding:25px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,.08)}
th{background:#0d6efd;color:#fff}
td, th{text-align:center; vertical-align:middle;}
</style>
</head>
<body>
<div class="container mt-5">
<div class="box">

<h3 class="text-center mb-4">Employee Payroll Management</h3>
<?php if($message): ?><div class="alert alert-success"><?= $message ?></div><?php endif; ?>

<!-- FORM -->
<form method="POST" class="row g-3 mb-4">
<input type="hidden" name="edit_id" value="<?= $editData['id'] ?>">
<div class="col-md-2"><label>Last Name</label><input type="text" name="last_name" value="<?= htmlspecialchars($editData['last_name']) ?>" class="form-control" required></div>
<div class="col-md-2"><label>Middle Name</label><input type="text" name="middle_name" value="<?= htmlspecialchars($editData['middle_name']) ?>" class="form-control"></div>
<div class="col-md-2"><label>First Name</label><input type="text" name="first_name" value="<?= htmlspecialchars($editData['first_name']) ?>" class="form-control" required></div>
<div class="col-md-2"><label>Position</label><input type="text" name="position" value="<?= htmlspecialchars($editData['position']) ?>" class="form-control" required></div>
<div class="col-md-2"><label>Department</label><input type="text" name="department" value="<?= htmlspecialchars($editData['department']) ?>" class="form-control" required></div>
<div class="col-md-2"><label>Salary</label><input type="number" step="0.01" name="basic_salary" value="<?= htmlspecialchars($editData['basic_salary']) ?>" class="form-control" required></div>
<div class="col-md-2"><label>Other</label><input type="number" step="0.01" name="others" value="<?= htmlspecialchars($editData['others']) ?>" class="form-control"></div>
<div class="col-md-3 d-grid"><button type="submit" name="add_employee" class="btn btn-primary mt-4"><?= $editData['id']?'Update Employee':'Add Employee' ?></button></div>
</form>

<!-- EXPORT / PRINT -->
<a href="?export=excel" class="btn btn-success mb-3">Export Excel</a>
<button onclick="printReport()" class="btn btn-info mb-3">Print Payroll Report</button>

<!-- TABLE -->
<table class="table table-bordered">
<colgroup>
<col style="width:50px"><col style="width:120px"><col style="width:120px">
<col style="width:120px"><col style="width:150px"><col style="width:150px">
<col style="width:100px"><col style="width:100px"><col style="width:100px">
<col style="width:100px"><col style="width:100px"><col style="width:120px">
<col style="width:120px"><col style="width:180px">
</colgroup>
<thead>
<tr>
<th>ID</th><th>Last</th><th>Middle</th><th>First</th><th>Position</th><th>Dept</th>
<th>Salary</th><th>Tax</th><th>Emp Pen</th><th>Co Pen</th><th>Other</th><th>Deduct</th><th>Net</th><th>Action</th>
</tr>
</thead>
<tbody>
<?php
$totalSalary=$totalTax=$totalEmpPen=$totalCoPen=$totalOther=$totalDeduct=$totalNet=0;
$result->data_seek(0);
while($r=$result->fetch_assoc()):
    list($tax,$emp,$co,$ded,$net)=calculatePayroll($r['basic_salary'],$r['others']);
    $totalSalary+=$r['basic_salary'];
    $totalTax+=$tax;
    $totalEmpPen+=$emp;
    $totalCoPen+=$co;
    $totalOther+=$r['others'];
    $totalDeduct+=$ded;
    $totalNet+=$net;
?>
<tr>
<td><?= $r['id'] ?></td>
<td><?= htmlspecialchars($r['last_name']) ?></td>
<td><?= htmlspecialchars($r['middle_name']) ?></td>
<td><?= htmlspecialchars($r['first_name']) ?></td>
<td><?= $r['position'] ?></td>
<td><?= $r['department'] ?></td>
<td><?= number_format($r['basic_salary'],2) ?></td>
<td><?= number_format($tax,2) ?></td>
<td><?= number_format($emp,2) ?></td>
<td><?= number_format($co,2) ?></td>
<td><?= number_format($r['others'],2) ?></td>
<td><?= number_format($ded,2) ?></td>
<td><?= number_format($net,2) ?></td>
<td>
<a href="?edit_id=<?= $r['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="?delete_id=<?= $r['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
<tfoot class="fw-bold">
<tr>
<td colspan="6">TOTAL</td>
<td><?= number_format($totalSalary,2) ?></td>
<td><?= number_format($totalTax,2) ?></td>
<td><?= number_format($totalEmpPen,2) ?></td>
<td><?= number_format($totalCoPen,2) ?></td>
<td><?= number_format($totalOther,2) ?></td>
<td><?= number_format($totalDeduct,2) ?></td>
<td><?= number_format($totalNet,2) ?></td>
<td></td>
</tr>
</tfoot>
</table>
</div></div>

<script>
function printReport(){
    var now = new Date();
    var monthYear = now.toLocaleString('default',{month:'long',year:'numeric'});
    var tableHTML = document.querySelector('table').outerHTML;

    var printWindow = window.open('','_blank','height=900,width=1000');
    printWindow.document.write('<html><head><title>Payroll Report</title>');
    printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
    printWindow.document.write('<style>th{background:#0d6efd;color:#fff;} body{padding:20px;} h2,h4{text-align:center;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h2>ABC Company</h2>');
    printWindow.document.write('<h4>Payroll Report for '+monthYear+'</h4><br>');

    // Add table with Employee signature column
    var rows = document.querySelectorAll('table tbody tr');
    var table = '<table class="table table-bordered"><thead><tr>';
    table+='<th>ID</th><th>Last</th><th>Middle</th><th>First</th><th>Position</th><th>Dept</th>';
    table+='<th>Salary</th><th>Tax</th><th>Emp Pen</th><th>Co Pen</th><th>Other</th><th>Deduct</th><th>Net</th><th>Employee Signature</th></tr></thead><tbody>';
    rows.forEach(r=>{
        table+='<tr>';
        r.querySelectorAll('td').forEach((td,i)=>{
            if(i<13) table+='<td>'+td.innerHTML+'</td>';
        });
        table+='<td>________________</td>';
        table+='</tr>';
    });
    var totals = document.querySelector('table tfoot tr').innerHTML;
    table+='<tfoot><tr>'+totals.replace('</tr>','<th colspan="1">-</th></tr>')+'</tfoot>';
    table+='</tbody></table>';
    printWindow.document.write(table);

    // Signatories
    printWindow.document.write('<br><br><div class="d-flex justify-content-between" style="margin-top:40px;">');
    printWindow.document.write('<div style="text-align:center;"><strong>HR Manager</strong><br>_________________</div>');
    printWindow.document.write('<div style="text-align:center;"><strong>Finance Manager</strong><br>_________________</div>');
    printWindow.document.write('</div>');

    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>
</body>
</html>
