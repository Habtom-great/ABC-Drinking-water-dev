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
    <title>Order Drinking Water</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4 text-center">Order Drinking Water</h2>

    <form action="process_order.php" method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label>Customer Name</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Quantity (Bottles)</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Delivery Address</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Place Order</button>
        <a href="services.php" class="btn btn-secondary">Back</a>
    </form>
</div>

</body>
</html>
