<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";

function post($k){ return $_POST[$k] ?? ''; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $first_name = post('first_name');
    $last_name = post('last_name');
    $middle_name = post('middle_name');
    $age = post('age');
    $gender = post('gender');
    $department = post('department');
    $hire_date = post('hire_date');
    $termination_date = post('termination_date');
    $email = post('email');
    $telephone = post('telephone');
    $address = post('address');
    $salary = post('salary');
    $position = post('position');
    $experience = post('experience');
    $skills = post('skills');
    $educational_level = post('educational_level');
    $major = post('major');
    $performance_score = post('performance_score');
    $feedback = post('feedback');
    $date = post('date');
    $rating = post('rating');
    $comments = post('comments');

    $password = password_hash(post('password'), PASSWORD_DEFAULT);
    $role = "staff";

    // IMAGE
    $profile_image = "";
    $upload_dir = __DIR__ . "/uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    if (!empty($_FILES['profile_image']['name'])) {
        $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $file = time()."_".uniqid().".".$ext;
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_dir.$file)) {
            $profile_image = "uploads/".$file;
        }
    }

    $sql = "INSERT INTO staff 
    (first_name,last_name,middle_name,age,gender,department,hire_date,termination_date,
    email,telephone,address,password,role,profile_image,salary,position,experience,skills,
    educational_level,major,performance_score,feedback,date,rating,comments)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssssssssssssss",
        $first_name,$last_name,$middle_name,$age,$gender,$department,$hire_date,$termination_date,
        $email,$telephone,$address,$password,$role,$profile_image,$salary,$position,$experience,$skills,
        $educational_level,$major,$performance_score,$feedback,$date,$rating,$comments
    );

    $message = $stmt->execute() ? "Staff added successfully!" : "Error: ".$stmt->error;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Staff</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    background:#eef2f7;
    font-family:Arial;
}

/* HEADER */
.header{
    background:#4e73df;
    color:#fff;
    padding:15px;
    text-align:center;
    font-size:22px;
    font-weight:bold;
}

/* NAV */
.navbar{
    display:flex;
    justify-content:flex-end;
    padding:10px;
    gap:10px;
}
.navbar a{
    padding:8px 12px;
    color:#fff;
    text-decoration:none;
    border-radius:5px;
    font-weight:bold;
}
.manage{background:#17a2b8;}
.logout{background:#dc3545;}
.dashboard{background:#28a745;}

/* CARD */
.card{
    width:95%;
    margin:auto;
    background:#fff;
    padding:15px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* GRID FORM */
.grid{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:8px;
}

.group{
    display:flex;
    flex-direction:column;
}

label{
    font-size:11px;
    font-weight:bold;
    margin-bottom:3px;
}

input,select{
    padding:6px;
    font-size:12px;
    border:1px solid #ccc;
    border-radius:5px;
}

/* BUTTONS */
.actions{
    text-align:center;
    margin-top:15px;
}

.btn-save{
    background:#4e73df;
    color:#fff;
    padding:10px 25px;
    border:none;
    border-radius:6px;
    font-weight:bold;
}

.btn-save:hover{background:#2e59d9;}

.bottom-buttons{
    text-align:center;
    margin-top:15px;
}

.bottom-buttons a{
    margin:5px;
    padding:8px 15px;
    text-decoration:none;
    color:#fff;
    border-radius:5px;
    font-weight:bold;
}

.back{background:#6c757d;}
.manage2{background:#17a2b8;}

.footer{
    text-align:center;
    padding:10px;
    margin-top:10px;
    background:#343a40;
    color:#fff;
    font-size:12px;
}
</style>
</head>

<body>

<div class="header">Add Staff - Admin Panel</div>

<div class="navbar">
    <a class="dashboard" href="admin_dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
    <a class="manage" href="manage_staff.php">Manage Staff</a>
    <a class="logout" href="logout.php">Logout</a>
</div>

<?php if($message): ?>
<div style="text-align:center;font-weight:bold;color:green;">
    <?= $message ?>
</div>
<?php endif; ?>

<div class="card">

<form method="POST" enctype="multipart/form-data">

<div class="grid">

<div class="group"><label>First</label><input name="first_name" required></div>
<div class="group"><label>Last</label><input name="last_name" required></div>
<div class="group"><label>Middle</label><input name="middle_name"></div>
<div class="group"><label>Age</label><input type="number" name="age"></div>
<div class="group"><label>Gender</label>
<select name="gender"><option>Male</option><option>Female</option></select></div>

<div class="group"><label>Dept</label><input name="department"></div>
<div class="group"><label>Hire</label><input type="date" name="hire_date"></div>
<div class="group"><label>Terminate</label><input type="date" name="termination_date"></div>
<div class="group"><label>Email</label><input name="email"></div>
<div class="group"><label>Phone</label><input name="telephone"></div>

<div class="group"><label>Address</label><input name="address"></div>
<div class="group"><label>Salary</label><input name="salary"></div>
<div class="group"><label>Position</label><input name="position"></div>
<div class="group"><label>Experience</label><input name="experience"></div>
<div class="group"><label>Skills</label><input name="skills"></div>

<div class="group"><label>Edu Level</label><input name="educational_level"></div>
<div class="group"><label>Major</label><input name="major"></div>
<div class="group"><label>Score</label><input name="performance_score"></div>
<div class="group"><label>Feedback</label><input name="feedback"></div>
<div class="group"><label>Date</label><input name="date"></div>

<div class="group">
<label>Rating</label>
<select name="rating">
<option>excellent</option>
<option>very good</option>
</select>
</div>

<div class="group"><label>Comments</label><input name="comments"></div>
<div class="group"><label>Password</label><input type="password" name="password"></div>
<div class="group"><label>Image</label><input type="file" name="profile_image"></div>

</div>

<div class="actions">
    <button class="btn-save">Save Staff</button>
</div>

<div class="bottom-buttons">
    <a class="back" href="admin_dashboard.php">Back Dashboard</a>
    <a class="manage2" href="manage_staff.php">Manage Staff</a>
</div>

</form>
</div>

<div class="footer">© <?= date('Y') ?> Staff Management System</div>

</body>
</html>