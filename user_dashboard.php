<?php

session_start();
// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// Safely access session variables
$name = $_SESSION['name'] ?? 'Staff'; // Default to 'Staff' if name is not set
$email = $_SESSION['email'] ?? 'N/A';
$role = $_SESSION['role'] ?? 'Staff Member';

?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Staff Dashboard</title>
 <!-- Bootstrap CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
 <!-- Font Awesome -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
 <style>
 body {
  background-color: #f8f9fa;
 }

 .header {
  background-color: #343a40;
  color: white;
  padding: 15px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
 }

 .header h1 {
  margin: 0;
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

 .sidebar a:hover {
  background-color: #495057;
 }

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

 .card {
  margin-bottom: 20px;
 }
 </style>
</head>

<body>
 <!-- Header -->
 <div class="header">
  <h1>User Dashboard</h1>
  <div>
   <span>Welcome, <?= htmlspecialchars($name) ?>!</span>
   <button class="btn btn-light ms-3" onclick="logout()">Logout</button>
  </div>
 </div>

 <!-- Sidebar -->
 <div class="sidebar">
  <a href="#profile"><i class="fas fa-user"></i> Profile</a>
  <a href="#tasks"><i class="fas fa-tasks"></i> Tasks</a>
  <a href="#messages"><i class="fas fa-envelope"></i> Messages</a>
  <a href="#settings"><i class="fas fa-cog"></i> Settings</a>
 </div>

 <!-- Main Content -->
 <div class="main-content">
  <!-- Profile Section -->
  <section id="profile">
   <h2>Profile</h2>
   <div class="card">
    <div class="card-body">
     <h5 class="card-title">Your Information</h5>
     <p class="card-text"><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
     <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
     <p class="card-text"><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>
     <button class="btn btn-primary" onclick="editProfile()">Edit Profile</button>
    </div>
   </div>
  </section>

  <hr>

  <!-- Tasks Section -->
  <section id="tasks">
   <h2>Tasks</h2>
   <ul class="list-group">
    <li class="list-group-item">
     Complete the financial report <span class="badge bg-warning">Pending</span>
     <button class="btn btn-sm btn-success float-end">Mark Complete</button>
    </li>
    <li class="list-group-item">
     Attend the team meeting <span class="badge bg-success">Completed</span>
    </li>
    <li class="list-group-item">
     Review project proposals <span class="badge bg-danger">Overdue</span>
     <button class="btn btn-sm btn-success float-end">Mark Complete</button>
    </li>
   </ul>
  </section>

  <hr>

  <!-- Messages Section -->
  <section id="messages">
   <h2>Messages</h2>
   <div class="mb-3">
    <label for="newMessage" class="form-label">Send a New Message</label>
    <textarea id="newMessage" class="form-control" rows="3" placeholder="Type your message..."></textarea>
    <button class="btn btn-primary mt-2" onclick="sendMessage()">Send</button>
   </div>

   <h5>Inbox</h5>
   <ul class="list-group">
    <li class="list-group-item">
     <strong>Manager:</strong> Please update the sales report. <span class="text-muted">10 mins ago</span>
     <button class="btn btn-sm btn-danger float-end" onclick="deleteMessage(this)">Delete</button>
    </li>
    <li class="list-group-item">
     <strong>HR:</strong> Reminder about the upcoming training session. <span class="text-muted">2 hrs ago</span>
     <button class="btn btn-sm btn-danger float-end" onclick="deleteMessage(this)">Delete</button>
    </li>
   </ul>
  </section>

  <hr>

  <!-- Settings Section -->
  <section id="settings">
   <h2>Settings</h2>
   <p>Settings options will be added soon!</p>
  </section>
 </div>

 <!-- Footer -->
 <div class="footer">
  <p>&copy; 2025 ABC Company. All Rights Reserved.</p>
 </div>

 <!-- JavaScript -->
 <script>
 function logout() {
  alert('You have been logged out!');
  window.location.href = 'logout.php';
 }

 function editProfile() {
  alert('Profile editing functionality coming soon!');
 }

 function sendMessage() {
  const message = document.getElementById('newMessage').value;
  if (message.trim() === '') {
   alert('Please type a message.');
  } else {
   alert('Message sent successfully!');
   document.getElementById('newMessage').value = '';
  }
 }

 function deleteMessage(element) {
  if (confirm('Are you sure you want to delete this message?')) {
   element.closest('li').remove();
   alert('Message deleted!');
  }
 }
 // Function to send a new message
 function sendMessage() {
  const newMessage = document.getElementById('newMessage').value.trim();

  if (newMessage === '') {
   alert('Please type a message before sending.');
   return;
  }

  // Create a new message item dynamically
  const messageList = document.getElementById('messageList');
  const newMessageItem = document.createElement('li');
  newMessageItem.className = 'list-group-item d-flex justify-content-between align-items-center';
  newMessageItem.innerHTML = `
        <div>
            <strong>You:</strong> ${newMessage}
            <span class="text-muted d-block">Just now</span>
        </div>
        <button class="btn btn-sm btn-danger" onclick="deleteMessage(this)">Delete</button>
    `;

  // Append the new message to the list
  messageList.appendChild(newMessageItem);

  // Clear the textarea
  document.getElementById('newMessage').value = '';
  alert('Message sent successfully!');
 }

 // Function to delete a message
 function deleteMessage(button) {
  if (confirm('Are you sure you want to delete this message?')) {
   button.closest('li').remove();
   alert('Message deleted!');
  }
 }
 </script>
</body>

</html>
<section id="messages">
 <h2>Messages</h2>

 <!-- Form to Send a New Message -->
 <div class="mb-3">
  <label for="newMessage" class="form-label">Send a New Message</label>
  <textarea id="newMessage" class="form-control" rows="3" placeholder="Type your message here..."></textarea>
  <button class="btn btn-primary mt-2" onclick="sendMessage()">Send</button>
 </div>

 <!-- Inbox Section -->
 <h5>Inbox</h5>
 <ul id="messageList" class="list-group">
  <li class="list-group-item d-flex justify-content-between align-items-center">
   <div>
    <strong>Manager:</strong> Please update the sales report.
    <span class="text-muted d-block">10 mins ago</span>
   </div>
   <button class="btn btn-sm btn-danger" onclick="deleteMessage(this)">Delete</button>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-center">
   <div>
    <strong>HR:</strong> Reminder about the upcoming training session.
    <span class="text-muted d-block">2 hrs ago</span>
   </div>
   <button class="btn btn-sm btn-danger" onclick="deleteMessage(this)">Delete</button>
  </li>
 </ul>
</section>
<style>
#messages {
 margin-top: 20px;
}

#messageList .list-group-item {
 background-color: #fff;
 border: 1px solid #ddd;
 border-radius: 5px;
 margin-bottom: 10px;
 transition: 0.3s;
}

#messageList .list-group-item:hover {
 background-color: #f9f9f9;
}

#newMessage {
 border: 1px solid #ddd;
 border-radius: 5px;
 resize: none;
}
</style>