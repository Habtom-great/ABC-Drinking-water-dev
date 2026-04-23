<?php
session_start();

// Only allow logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - ABC Drinking Water</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .header a.logout-btn {
            position: absolute;
            top: 20px;
            right: 30px;
        }

        .service-card {
            margin-bottom: 20px;
            transition: transform 0.3s ease-in-out;
        }

        .service-card:hover {
            transform: scale(1.05);
        }

        .footer {
            text-align: center;
            padding: 15px;
            background-color: #343a40;
            color: white;
            margin-top: 30px;
        }

        .container {
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <h1>ABC Drinking Water - Our Services</h1>
        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong></p>
        <a href="logout.php" class="btn btn-danger logout-btn">Logout</a>
    </div>

    <div class="container">
        <div class="row">

            <!-- Bottled Water Delivery -->
            <div class="col-md-4">
                <div class="card border-primary shadow service-card">
                    <div class="card-header bg-primary text-white">Bottled Water Delivery</div>
                    <div class="card-body">
                        <p>Fast and reliable delivery of purified drinking water bottles to homes and offices.</p>
                        <a href="order_water.php" class="btn btn-primary">Order Now</a>
                    </div>
                </div>
            </div>

            <!-- Water Tank Installation -->
            <div class="col-md-4">
                <div class="card border-success shadow service-card">
                    <div class="card-header bg-success text-white">Water Tank Installation</div>
                    <div class="card-body">
                        <p>Professional installation of water tanks and filtration systems for residential and commercial use.</p>
                        <a href="installation.php" class="btn btn-success">Learn More</a>
                    </div>
                </div>
            </div>

            <!-- Water Quality Testing -->
            <div class="col-md-4">
                <div class="card border-warning shadow service-card">
                    <div class="card-header bg-warning text-dark">Water Quality Testing</div>
                    <div class="card-body">
                        <p>Comprehensive water testing services to ensure safety and quality of your drinking water.</p>
                        <a href="testing.php" class="btn btn-warning">Schedule Test</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; <?php echo date("Y"); ?> ABC Drinking Water | All Rights Reserved
    </div>

    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>
