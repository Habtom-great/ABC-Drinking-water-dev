<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/db_connect.php';

// 🔐 admin check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// staff id check
if (!isset($_GET['staff_id'])) {
    die("Staff ID is required");
}

$staff_id = intval($_GET['staff_id']);

$stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Staff not found");
}

$staff = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Staff History</title>

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

/* CARD */
.container-box{
    width:90%;
    margin:auto;
    margin-top:15px;
    background:#fff;
    padding:15px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* PROFILE */
.profile{
    display:flex;
    gap:15px;
    align-items:center;
    border-bottom:1px solid #ddd;
    padding-bottom:10px;
}

.profile img{
    width:90px;
    height:90px;
    border-radius:50%;
    object-fit:cover;
}

.name{
    font-size:18px;
    font-weight:bold;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px;
    margin-top:10px;
    font-size:13px;
}

.box{
    background:#f8f9fc;
    padding:8px;
    border-radius:6px;
}

/* SECTION */
.section-title{
    margin-top:15px;
    font-weight:bold;
    color:#4e73df;
}

/* BUTTON */
.back{
    display:inline-block;
    margin-top:15px;
    padding:8px 12px;
    background:#6c757d;
    color:#fff;
    text-decoration:none;
    border-radius:5px;
}

/* HISTORY TIMELINE STYLE */
.timeline{
    margin-top:10px;
    padding:10px;
    border-left:3px solid #4e73df;
}

.timeline-item{
    margin-bottom:10px;
    padding-left:10px;
}

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

<div class="header">
    <i class="fa fa-user"></i> Staff Full History
</div>

<div class="container-box">

    <!-- PROFILE -->
    <div class="profile">
        <img src="<?= !empty($staff['profile_image']) ? $staff['profile_image'] : 'assets/default.png' ?>">
        <div>
            <div class="name">
                <?= $staff['first_name'].' '.$staff['middle_name'].' '.$staff['last_name'] ?>
            </div>
            <div><?= $staff['position'] ?> | <?= $staff['department'] ?></div>
        </div>
    </div>

    <!-- DETAILS -->
    <div class="section-title">Basic Information</div>

    <div class="grid">
        <div class="box"><b>Email:</b> <?= $staff['email'] ?></div>
        <div class="box"><b>Phone:</b> <?= $staff['telephone'] ?></div>
        <div class="box"><b>Address:</b> <?= $staff['address'] ?></div>

        <div class="box"><b>Age:</b> <?= $staff['age'] ?></div>
        <div class="box"><b>Gender:</b> <?= $staff['gender'] ?></div>
        <div class="box"><b>Salary:</b> <?= $staff['salary'] ?></div>

        <div class="box"><b>Hire Date:</b> <?= $staff['hire_date'] ?></div>
        <div class="box"><b>Termination:</b> <?= $staff['termination_date'] ?></div>
        <div class="box"><b>Experience:</b> <?= $staff['experience'] ?></div>

        <div class="box"><b>Skills:</b> <?= $staff['skills'] ?></div>
        <div class="box"><b>Education:</b> <?= $staff['educational_level'] ?></div>
        <div class="box"><b>Major:</b> <?= $staff['major'] ?></div>
    </div>

    <!-- PERFORMANCE -->
    <div class="section-title">Performance History</div>

    <div class="timeline">

        <div class="timeline-item">
            <b>Rating:</b> <?= $staff['rating'] ?>
        </div>

        <div class="timeline-item">
            <b>Score:</b> <?= $staff['performance_score'] ?>
        </div>

        <div class="timeline-item">
            <b>Feedback:</b> <?= $staff['feedback'] ?>
        </div>

        <div class="timeline-item">
            <b>Comments:</b> <?= $staff['comments'] ?>
        </div>

        <div class="timeline-item">
            <b>Record Date:</b> <?= $staff['date'] ?>
        </div>

    </div>

    <!-- BACK BUTTON -->
    <a class="back" href="view_all_staff_history.php">
        ⬅ Back to Staff List
    </a>

</div>

<div class="footer">
    © <?= date('Y') ?> HR Staff Management System
</div>

</body>
</html>