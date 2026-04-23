<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$name  = $_SESSION['name'] ?? 'Staff';
$email = $_SESSION['email'] ?? 'N/A';
$role  = $_SESSION['role'] ?? 'Staff Member';
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
body { background-color: #f8f9fa; }

.header {
    background-color: #343a40;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sidebar {
    width: 250px;
    background-color: #343a40;
    color: white;
    height: 100vh;
    position: fixed;
    padding-top: 20px;
}

.sidebar a {
    color: white;
    text-decoration: none;
    display: block;
    padding: 10px 20px;
}

.sidebar a:hover { background-color: #495057; }

.main-content {
    margin-left: 250px;
    padding: 20px;
}

.footer {
    background-color: #343a40;
    color: white;
    text-align: center;
    padding: 10px;
    position: fixed;
    bottom: 0;
    width: 100%;
    left: 250px;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h1>Staff Dashboard</h1>
    <div>
        Welcome, <?= htmlspecialchars($name) ?>
        <button class="btn btn-light ms-3" onclick="logout()">Logout</button>
    </div>
</div>

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="#profile"><i class="fas fa-user"></i> Profile</a>
    <a href="#tasks"><i class="fas fa-tasks"></i> Tasks</a>
    <a href="#messages"><i class="fas fa-envelope"></i> Messages</a>
</div>

<!-- MAIN -->
<div class="main-content">

<!-- PROFILE -->
<section id="profile">
    <h2>Profile</h2>
    <div class="card p-3">
        <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>
    </div>
</section>

<hr>

<!-- TASKS -->
<section id="tasks">
    <h2>Tasks</h2>
    <ul class="list-group">
        <li class="list-group-item">Complete report <span class="badge bg-warning">Pending</span></li>
        <li class="list-group-item">Attend meeting <span class="badge bg-success">Done</span></li>
    </ul>
</section>

<hr>

<!-- MESSAGES (ONLY ONE SECTION FIXED) -->
<section id="messages">
    <h2>Messages</h2>

    <div class="mb-3">
        <textarea id="newMessage" class="form-control" rows="3"></textarea>
        <button class="btn btn-primary mt-2" onclick="sendMessage()">Send</button>
    </div>

    <ul id="messageList" class="list-group">
        <li class="list-group-item d-flex justify-content-between">
            <div><strong>Manager:</strong> Update report</div>
            <button class="btn btn-sm btn-danger" onclick="deleteMessage(this)">Delete</button>
        </li>
    </ul>
</section>

</div>

<!-- FOOTER -->
<div class="footer">
    &copy; 2025 ABC Company
</div>

<script>
function logout() {
    window.location.href = "logout.php";
}

function sendMessage() {
    const msg = document.getElementById("newMessage").value.trim();
    if (msg === "") return alert("Please type message");

    const li = document.createElement("li");
    li.className = "list-group-item d-flex justify-content-between";
    li.innerHTML = `
        <div><strong>You:</strong> ${msg}</div>
        <button class="btn btn-sm btn-danger" onclick="deleteMessage(this)">Delete</button>
    `;

    document.getElementById("messageList").appendChild(li);
    document.getElementById("newMessage").value = "";
}

function deleteMessage(btn) {
    btn.closest("li").remove();
}
</script>

</body>
</html>