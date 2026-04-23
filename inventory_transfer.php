<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADD Transfer</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 0;
        }
        .page-header {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .page-title h4 {
            margin: 0;
        }
        .card {
            width: 80%;
            max-width: 1200px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-submit {
            margin-right: 10px;
        }
        .btn-cancel {
            background-color: #ccc;
        }
        .footer {
            background-color: #333;
            color: white;
            padding: 20px 0;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .footer a {
            color: #fff;
            margin: 0 10px;
            text-decoration: none;
        }
        .table th, .table td {
            padding: 10px;
            text-align: center;
        }
        .table {
            margin-top: 20px;
        }
        .total-order {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        .total-order li {
            margin-bottom: 10px;
        }
        .total-order h4 {
            font-size: 18px;
            color: #333;
        }
        .total-order h5 {
            font-size: 16px;
            color: #007bff;
        }
        .input-groupicon input {
            padding-left: 40px;
        }
        .input-group-text {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="page-header">
        <div class="page-title">
        <h2>Inventory/Stocks from to Transfer</h2>
            <h6>Transfer your stocks to one store another store.</h6>
        </div>
    </header>

    <div class="page-wrapper">
        <div class="content">
            <!-- Main content -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Date</label>
                                <div class="input-groupicon">
                                    <input type="text" placeholder="DD-MM-YYYY" class="datetimepicker">
                                    <div class="addonset">
                                        <img src="assets/img/icons/calendars.svg" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>From</label>
                                <select class="select">
                                    <option>Choose</option>
                                    <option>Store 1</option>
                                    <option>Store 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>To</label>
                                <select class="select">
                                    <option>Choose</option>
                                    <option>Store 1</option>
                                    <option>Store 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Product Name</label>
                                <div class="input-groupicon">
                                    <input type="text" placeholder="Scan/Search Product by code and select...">
                                    <div class="addonset">
                                        <img src="assets/img/icons/scanners.svg" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product table -->
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>QTY</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Discount</th>
                                        <th>Tax</th>
                                        <th>Total Cost ($)</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bor-b1">
                                        <td class="productimgname">
                                            <a class="product-img">
                                                <img src="assets/img/product/product7.jpg" alt="product">
                                            </a>
                                            <a href="javascript:void(0);">Apple Earpods</a>
                                        </td>
                                        <td>
                                            <div class="input-group form-group mb-0">
                                                <a class="scanner-set input-group-text">
                                                    <img src="assets/img/icons/plus1.svg" alt="img">
                                                </a>
                                                <input type="text" value="1" class="calc-no">
                                                <a class="scanner-set input-group-text">
                                                    <img src="assets/img/icons/minus.svg" alt="img">
                                                </a>
                                            </div>
                                        </td>
                                        <td>1500.00</td>
                                        <td>50.00</td>
                                        <td>0.00</td>
                                        <td>0.00</td>
                                        <td>1500.00</td>
                                        <td>
                                            <a href="javascript:void(0);" class="delete-set">
                                                <img src="assets/img/icons/delete.svg" alt="svg">
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Order summary -->
                    <div class="row">
                        <div class="col-lg-12 float-md-right">
                            <div class="total-order">
                                <ul>
                                    <li>
                                        <h4>Order Tax</h4>
                                        <h5>$ 0.00 (0.00%)</h5>
                                    </li>
                                    <li>
                                        <h4>Discount</h4>
                                        <h5>$ 0.00</h5>
                                    </li>
                                    <li>
                                        <h4>Shipping</h4>
                                        <h5>$ 0.00</h5>
                                    </li>
                                    <li class="total">
                                        <h4>Grand Total</h4>
                                        <h5>$ 0.00</h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form for Order Details -->
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Order Tax</label>
                                <input type="text">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Discount</label>
                                <input type="text">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Shipping</label>
                                <input type="text">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="select">
                                    <option>Choose Status</option>
                                    <option>Completed</option>
                                    <option>Inprogress</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <a href="javascript:void(0);" class="btn btn-submit me-2">Submit</a>
                            <a href="transferlist.html" class="btn btn-cancel">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 ABC Company Name | All Rights Reserved</p>
        <a href="#">Facebook</a> | <a href="#">YouTube</a> | <a href="#">Telegram</a> | <a href="#">WhatsApp</a>
    </footer>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
        }
        .page-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .content {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 30px;
        }
        .page-header {
            margin-bottom: 20px;
        }
        .page-header .page-title h4 {
            font-size: 24px;
            font-weight: bold;
        }
        .page-header .page-title h6 {
            font-size: 14px;
            color: #888;
        }
        .card {
            border: none;
            margin-top: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .input-groupicon {
            position: relative;
        }
        .input-groupicon input {
            padding-right: 40px;
        }
        .input-groupicon .addonset {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .table th, .table td {
            text-align: center;
            padding: 12px 15px;
        }
        .table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .total-order {
            display: flex;
            justify-content: space-between;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .total-order li {
            list-style: none;
        }
        .total-order h4 {
            margin-bottom: 5px;
        }
        .total-order h5 {
            font-weight: bold;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-transform: uppercase;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .btn-submit {
            background-color: #4CAF50;
            color: white;
        }
        .btn-cancel {
            background-color: #f44336;
            color: white;
        }
        .footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: 30px;
        }
    </style>
