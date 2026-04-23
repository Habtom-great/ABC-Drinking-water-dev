<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule Water Quality Test</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4 text-center">Schedule Water Quality Test</h2>

    <form action="process_test.php" method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label>Customer Name</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Location</label>
            <input type="text" name="location" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Preferred Date</label>
            <input type="date" name="test_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-warning">Schedule Test</button>
        <a href="services.php" class="btn btn-secondary">Back</a>
    </form>
</div>

</body>
</html>
