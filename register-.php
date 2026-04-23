<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<style>
.form-section {
 padding: 30px;
 max-width: 850px;
 margin: 40px auto;
 background: #f5f5f5;
 border-radius: 15px;
 box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
 font-size: 14px;
}

.form-section h2 {
 text-align: center;
 margin-bottom: 25px;
 color: #333;
}

.form-group {
 margin-bottom: 15px;
}

.form-control,
.form-select {
 height: 32px;
 padding: 4px 10px;
 font-size: 13px;
}

label {
 font-weight: 600;
 font-size: 13px;
}

.btn-primary {
 padding: 6px 16px;
 font-size: 13px;
}
</style>

<div class="container form-section">
 <h2>Registration Form</h2>
 <form action="insert_student.php" method="POST" enctype="multipart/form-data">

  <div class="row">
   <div class="col-md-4 form-group">
    <label>Last Name</label>
    <input type="text" name="last_name" class="form-control" required>
   </div>
   <div class="col-md-4 form-group">
    <label>Middle Name</label>
    <input type="text" name="middle_name" class="form-control" required>
   </div>
   <div class="col-md-4 form-group">
    <label>First Name</label>
    <input type="text" name="first_name" class="form-control" required>
   </div>
  </div>

  <div class="row">
   <div class="col-md-3 form-group">
    <label>Sex</label>
    <select name="sex" class="form-select" required>
     <option value="">Choose</option>
     <option>Male</option>
     <option>Female</option>
    </select>
   </div>
   <div class="col-md-3 form-group">
    <label>Age</label>
    <input type="number" name="age" class="form-control" min="1" required>
   </div>
   <div class="col-md-6 form-group">
    <label>Country</label>
    <select name="country" class="form-select" required>
     <option value="">Select Country</option>
     <option>Ethiopia</option>
     <option>Kenya</option>
     <option>USA</option>
     <option>Canada</option>
     <!-- Add more countries -->
    </select>
   </div>
  </div>

  <div class="row">
   <div class="col-md-4 form-group">
    <label>Telephone</label>
    <input type="tel" name="telephone" class="form-control" placeholder="+251-000-000000" required>
   </div>
   <div class="col-md-4 form-group">
    <label>WhatsApp</label>
    <input type="tel" name="whatsapp" class="form-control" placeholder="+251-000-000000">
   </div>
   <div class="col-md-4 form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control" placeholder="example@example.com" required>
   </div>
  </div>

  <div class="form-group">
   <label>Upload Image</label>
   <input type="file" name="student_image" class="form-control">
  </div>

  <div class="text-center">
   <button type="submit" name="register_btn" class="btn btn-primary">Register</button>
  </div>

 </form>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>