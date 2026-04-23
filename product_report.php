<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
<div class="container-fluid py-4">
    <div class="row">
        <!-- Purchase & Sales Section -->
        <div class="col-lg-7 col-sm-12 col-12 d-flex">
            <div class="card flex-fill shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h5 class="card-title mb-0">Purchase & Sales</h5>
                    <div class="graph-sets">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><span class="badge bg-light text-dark">Sales</span></li>
                            <li class="list-inline-item"><span class="badge bg-light text-dark">Purchase</span></li>
                        </ul>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                2022 <img src="assets/img/icons/dropdown.svg" alt="Dropdown Icon" class="ms-2">
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#" class="dropdown-item">2022</a></li>
                                <li><a href="#" class="dropdown-item">2021</a></li>
                                <li><a href="#" class="dropdown-item">2020</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="sales_charts"></div>
                </div>
            </div>
        </div>

        <!-- Recently Added Products Section -->
        <div class="col-lg-5 col-sm-12 col-12 d-flex">
            <div class="card flex-fill shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                    <h4 class="card-title mb-0">Recently Added Products</h4>
                    <div class="dropdown">
                        <a href="#" data-bs-toggle="dropdown" class="text-white">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="productlist.html" class="dropdown-item">Product List</a></li>
                            <li><a href="addproduct.html" class="dropdown-item">Add Product</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <a href="productlist.html">
                                            <img src="assets/img/product/product22.jpg" alt="Apple Earpods" class="img-thumbnail" width="50">
                                            Apple Earpods
                                        </a>
                                    </td>
                                    <td>$891.2</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <a href="productlist.html">
                                            <img src="assets/img/product/product23.jpg" alt="iPhone 11" class="img-thumbnail" width="50">
                                            iPhone 11
                                        </a>
                                    </td>
                                    <td>$668.51</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <a href="productlist.html">
                                            <img src="assets/img/product/product24.jpg" alt="Samsung" class="img-thumbnail" width="50">
                                            Samsung
                                        </a>
                                    </td>
                                    <td>$522.29</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>
                                        <a href="productlist.html">
                                            <img src="assets/img/product/product6.jpg" alt="Macbook Pro" class="img-thumbnail" width="50">
                                            Macbook Pro
                                        </a>
                                    </td>
                                    <td>$291.01</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expired Products Section -->
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h4 class="card-title mb-3">Expired Products</h4>
            <div class="table-responsive">
                <table class="table table-bordered datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><a href="#">IT0001</a></td>
                            <td>
                                <a href="productlist.html">
                                    <img src="assets/img/product/product2.jpg" alt="Orange" class="img-thumbnail" width="50">
                                    Orange
                                </a>
                            </td>
                            <td>N/A</td>
                            <td>Fruits</td>
                            <td>12-12-2022</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><a href="#">IT0002</a></td>
                            <td>
                                <a href="productlist.html">
                                    <img src="assets/img/product/product3.jpg" alt="Pineapple" class="img-thumbnail" width="50">
                                    Pineapple
                                </a>
                            </td>
                            <td>N/A</td>
                            <td>Fruits</td>
                            <td>25-11-2022</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td><a href="#">IT0003</a></td>
                            <td>
                                <a href="productlist.html">
                                    <img src="assets/img/product/product4.jpg" alt="Strawberry" class="img-thumbnail" width="50">
                                    Strawberry
                                </a>
                            </td>
                            <td>N/A</td>
                            <td>Fruits</td>
                            <td>19-11-2022</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td><a href="#">IT0004</a></td>
                            <td>
                                <a href="productlist.html">
                                    <img src="assets/img/product/product5.jpg" alt="Avocado" class="img-thumbnail" width="50">
                                    Avocado
                                </a>
                            </td>
                            <td>N/A</td>
                            <td>Fruits</td>
                            <td>20-11-2022</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="assets/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="assets/plugins/apexchart/chart-data.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>

kkkkkkkkkkkkkkk
<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include header
include('header.php');
?>
<link rel="stylesheet" href="styles.css">

<div class="product-report-container">
    <h1 class="report-title">Product Movement Report</h1>
    <form class="report-form" method="POST" action="generate_report.php">
        <div class="form-group">
            <label for="branch">Branch:</label>
            <input type="text" id="branch" name="branch" placeholder="Enter branch name" required>
        </div>

        <div class="form-group date-range">
            <label for="period">Period:</label>
            <input type="date" id="start_date" name="start_date" required>
            <span>to</span>
            <input type="date" id="end_date" name="end_date" required>
        </div>

        <div class="form-group">
            <label for="section">Section:</label>
            <input type="text" id="section" name="section" placeholder="Enter section" required>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" placeholder="Enter minimum quantity" required>
        </div>

        <div class="form-group">
            <label for="value">Value:</label>
            <input type="number" id="value" name="value" placeholder="Enter minimum value" required>
        </div>

        <div class="form-group">
            <label for="salesperson">Salesperson:</label>
            <input type="text" id="salesperson" name="salesperson" placeholder="Enter salesperson name" required>
        </div>

        <button type="submit" class="submit-btn">Generate Report</button>
    </form>
</div>
<style>
    /* Reset default margin and padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7fc;
    color: #333;
}

/* Container for the form */
.product-report-container {
    max-width: 700px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Title */
.report-title {
    text-align: center;
    font-size: 24px;
    color: #2c3e50;
    margin-bottom: 20px;
}

/* Form layout */
.report-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

/* Individual form fields */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

label {
    font-size: 14px;
    font-weight: bold;
    color: #555;
}

input {
    padding: 12px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 5px;
    transition: border-color 0.3s ease;
}

input:focus {
    border-color: #3498db;
    outline: none;
}

/* Styling for the date range input */
.date-range {
    display: flex;
    gap: 10px;
    align-items: center;
}

/* Button */
.submit-btn {
    padding: 12px;
    font-size: 16px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px;
}

.submit-btn:hover {
    background-color: #2980b9;
}

/* Add success message styles */
.success-message {
    color: green;
    text-align: center;
    font-size: 16px;
    margin-top: 20px;
}

</style>
<?php include('footer.php'); ?>
