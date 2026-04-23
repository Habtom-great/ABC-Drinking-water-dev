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
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Dashboard</title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
 <link rel="stylesheet" href="assets/css/style.css">
 <style>
 .card {
  border: none;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
 }

 .card-title {
  font-weight: 600;
  color: #4a4a4a;
 }

 .table th {
  background-color: #f8f9fa;
 }

 .dropdown-toggle::after {
  margin-left: 0.5rem;
 }

 .product-img img {
  width: 50px;
  height: 50px;
  border-radius: 5px;
 }

 .dataview {
  margin-top: 20px;
 }

 .btn-white {
  color: #4a4a4a;
  border-color: #e0e0e0;
 }

 .btn-white:hover {
  background-color: #f0f0f0;
 }
 </style>
</head>

<body>
 <div class="container-fluid py-4">
  <div class="row">
   <!-- Purchase & Sales Section -->
   <div class="col-lg-7 col-sm-12 mb-4">
    <div class="card">
     <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Purchase & Sales</h5>
      <div class="dropdown">
       <button class="btn btn-white btn-sm dropdown-toggle" type="button" id="yearDropdown" data-bs-toggle="dropdown"
        aria-expanded="false">
        2022 <img src="assets/img/icons/dropdown.svg" alt="dropdown">
       </button>
       <ul class="dropdown-menu" aria-labelledby="yearDropdown">
        <li><a class="dropdown-item" href="#">2022</a></li>
        <li><a class="dropdown-item" href="#">2021</a></li>
        <li><a class="dropdown-item" href="#">2020</a></li>
       </ul>
      </div>
     </div>
     <div class="card-body">
      <div id="sales_charts" style="height: 250px;"></div>
     </div>
    </div>
   </div>

   <!-- Recently Added Products Section -->
   <div class="col-lg-5 col-sm-12 mb-4">
    <div class="card">
     <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Recently Added Products</h5>
      <div class="dropdown">
       <a href="#" class="text-secondary" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-ellipsis-v"></i>
       </a>
       <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="productlist.html">Product List</a></li>
        <li><a class="dropdown-item" href="addproduct.html">Add Product</a></li>
       </ul>
      </div>
     </div>
     <div class="card-body">
      <div class="table-responsive">
       <table class="table table-striped">
        <thead>
         <tr>
          <th>Sno</th>
          <th>Products</th>
          <th>Price</th>
         </tr>
        </thead>
        <tbody>
         <tr>
          <td>1</td>
          <td class="productimgname">
           <a href="productlist.html" class="product-img">
            <img src="assets/img/product/product22.jpg" alt="product">
           </a>
           <a href="productlist.html">Apple Earpods</a>
          </td>
          <td>$891.2</td>
         </tr>
         <tr>
          <td>2</td>
          <td class="productimgname">
           <a href="productlist.html" class="product-img">
            <img src="assets/img/product/product23.jpg" alt="product">
           </a>
           <a href="productlist.html">iPhone 11</a>
          </td>
          <td>$668.51</td>
         </tr>
         <tr>
          <td>3</td>
          <td class="productimgname">
           <a href="productlist.html" class="product-img">
            <img src="assets/img/product/product24.jpg" alt="product">
           </a>
           <a href="productlist.html">Samsung</a>
          </td>
          <td>$522.29</td>
         </tr>
         <tr>
          <td>4</td>
          <td class="productimgname">
           <a href="productlist.html" class="product-img">
            <img src="assets/img/product/product6.jpg" alt="product">
           </a>
           <a href="productlist.html">Macbook Pro</a>
          </td>
          <td>$291.01</td>
         </tr>
        </tbody>
       </table>
      </div>
     </div>
    </div>
   </div>

   <!-- Expired Products Section -->
   <div class="col-12">
    <div class="card">
     <div class="card-body">
      <h5 class="card-title">Expired Products</h5>
      <div class="table-responsive">
       <table class="table table-bordered table-hover">
        <thead>
         <tr>
          <th>SNo</th>
          <th>Product Code</th>
          <th>Product Name</th>
          <th>Brand Name</th>
          <th>Category</th>
          <th>Expiry Date</th>
         </tr>
        </thead>
        <tbody>
         <tr>
          <td>1</td>
          <td>IT0001</td>
          <td>Orange</td>
          <td>N/A</td>
          <td>Fruits</td>
          <td>12-12-2022</td>
         </tr>
         <tr>
          <td>2</td>
          <td>IT0002</td>
          <td>Pineapple</td>
          <td>N/A</td>
          <td>Fruits</td>
          <td>25-11-2022</td>
         </tr>
         <tr>
          <td>3</td>
          <td>IT0003</td>
          <td>Strawberry</td>
          <td>N/A</td>
          <td>Fruits</td>
          <td>19-11-2022</td>
         </tr>
         <tr>
          <td>4</td>
          <td>IT0004</td>
          <td>Avocado</td>
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
  </div>
 </div>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
 <script src="assets/js/script.js"></script>
 <script>
 // Initialize sales charts (Example)
 var options = {
  chart: {
   type: 'line',
   height: '250px'
  },
  series: [{
    name: 'Sales',
    data: [10, 20, 30, 40, 50]
   },
   {
    name: 'Purchase',
    data: [15, 25, 35, 45, 55]
   }
  ],
  xaxis: {
   categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May']
  }
 };
 var chart = new ApexCharts(document.querySelector("#sales_charts"), options);
 chart.render();
 </script>
</body>

</html>





kkkkkkkkkkkkkkkk
kkkkkkkkkk
<?php
require_once 'config.php';

// Fetch inventory data
$query = "SELECT product_id, product_name, unit_price, stock_quantity FROM products";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error fetching inventory: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Inventory</title>
 <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
 <div class="container">
  <h2>Inventory Management</h2>
  <table border="1">
   <thead>
    <tr>
     <th>Product ID</th>
     <th>Product Name</th>
     <th>Unit Price</th>
     <th>Stock Quantity</th>
    </tr>
   </thead>
   <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
    <tr>
     <td><?= $row['product_id']; ?></td>
     <td><?= $row['product_name']; ?></td>
     <td><?= $row['unit_price']; ?></td>
     <td><?= $row['stock_quantity']; ?></td>
    </tr>
    <?php endwhile; ?>
   </tbody>
  </table>
 </div>
</body>

</html>


kkkkkkkkkkkkkkkkkkkkkkk
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection setup
$servername = "localhost"; // Database server
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "abc_company"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize report type and records per page
$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : '';
$records_per_page = 10;

// Get the current page number, default to 1 if not set
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

// Check if the report_type is set
if ($report_type) {
    switch ($report_type) {
        case 'sales':
            $sql = "SELECT sale_id, salesperson_id, purchaseperson_id, branch_name, sale_date, total_amount FROM sales ORDER BY sale_date DESC LIMIT $start_from, $records_per_page";
            $title = "Sales Report";
            break;
        case 'purchases':
            $sql = "SELECT purchase_id, purchaseperson_id, branch_name, purchase_date, total_amount FROM purchases ORDER BY purchase_date DESC LIMIT $start_from, $records_per_page";
            $title = "Purchases Report";
            break;
        case 'salesperson':
            $sql = "SELECT salesperson_id, last_name, middle_name, first_name, email, telephone, branch_name FROM salesperson ORDER BY last_name, middle_name, first_name DESC LIMIT $start_from, $records_per_page";
            $title = "Sales Person Report";
            break;
        case 'vendors':
            $sql = "SELECT vendor_id, last_name, middle_name, first_name, email, telephone, Remained_balance, address FROM vendors ORDER BY last_name, middle_name, first_name DESC LIMIT $start_from, $records_per_page";
            $title = "Vendors Report";
            break;
        case 'inventory':
            $sql = "SELECT item_id, item_description, category, quantity, unit_cost, unit_price, total_sales_before_vat, vat, total_sales_after_vat, date FROM inventory ORDER BY date DESC LIMIT $start_from, $records_per_page";
            $title = "Inventory Report";
            break;
        case 'activity_log':
            $sql = "SELECT log_id, user_name, action, details, timestamp FROM activity_log ORDER BY timestamp DESC LIMIT $start_from, $records_per_page";
            $title = "Activity Log Report";
            break;
        default:
            die("Invalid report type selected.");
    }

    $result = $conn->query($sql);
    if ($result === false) {
        die("SQL Error: " . $conn->error);
    }

    // Get the total number of records for pagination
    $total_records_sql = "SELECT COUNT(*) FROM " . strtolower($report_type);
    $total_records_result = $conn->query($total_records_sql);
    $total_records_row = $total_records_result->fetch_row();
    $total_records = $total_records_row[0];

    // Calculate total number of pages
    $total_pages = ceil($total_records / $records_per_page);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Generate Reports</title>
 <!-- Bootstrap CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
 <style>
 body {
  background-color: #f8f9fa;
 }

 .header {
  background-color: #343a40;
  color: white;
  padding: 10px;
  text-align: center;
 }

 .container {
  margin-top: 15px;
 }

 .table-container {
  margin-top: 20px;
  background: white;
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  border-radius: 8px;
  page-break-before: always;
 }

 .footer {
  background-color: #343a40;
  color: white;
  text-align: center;
  padding: 10px;
  position: fixed;
  bottom: 0;
  width: 100%;
 }

 /* Ensure pagination works well when printing */
 @media print {
  .pagination {
   display: none;
  }

  .table-container {
   page-break-after: always;
  }

  .footer {
   position: absolute;
   bottom: 0;
   width: 100%;
  }

  .btn {
   display: none;
  }
 }
 </style>
</head>

<body>
 <div class="header">
  <h1>Generate Reports</h1>
  <p>Select a report type to view and download</p>
  <a href="admin_dashboard.php" class="btn btn-light">Back to Dashboard</a>
 </div>

 <div class="container">
  <!-- Form to Select Report Type -->
  <form method="GET" action="generate_report-1.php" class="mb-3">
   <div class="row">
    <div class="col-md-8">
     <select name="report_type" class="form-select" required>
      <option value="">Select Report Type</option>
      <option value="sales" <?php echo ($report_type == 'sales') ? 'selected' : ''; ?>>Sales</option>
      <option value="purchases" <?php echo ($report_type == 'purchases') ? 'selected' : ''; ?>>Purchases</option>
      <option value="salesperson" <?php echo ($report_type == 'salesperson') ? 'selected' : ''; ?>>Sales Person</option>
      <option value="vendors" <?php echo ($report_type == 'vendors') ? 'selected' : ''; ?>>Vendors</option>
      <option value="inventory" <?php echo ($report_type == 'inventory') ? 'selected' : ''; ?>>Inventory</option>
      <option value="activity_log" <?php echo ($report_type == 'activity_log') ? 'selected' : ''; ?>>Activity Log
      </option>
     </select>
    </div>
    <div class="col-md-4">
     <button type="submit" class="btn btn-primary w-100">Generate Report</button>
    </div>
   </div>
  </form>

  <!-- Display the Report -->
  <?php if (isset($report_type) && $report_type && $result->num_rows > 0): ?>
  <div class="table-container">
   <h3><?php echo htmlspecialchars($title); ?></h3>
   <table class="table table-striped table-bordered">
    <thead class="table-dark">
     <tr>
      <?php
                            $columns = array_keys($result->fetch_assoc());
                            $result->data_seek(0);
                            foreach ($columns as $col): ?>
      <th><?php echo htmlspecialchars(ucwords(str_replace("_", " ", $col))); ?></th>
      <?php endforeach; ?>
     </tr>
    </thead>
    <tbody>
     <?php while ($row = $result->fetch_assoc()): ?>
     <tr>
      <?php foreach ($row as $value): ?>
      <td><?php echo htmlspecialchars(number_format($value, 2)); ?></td> <!-- Format amount with commas -->
      <?php endforeach; ?>
     </tr>
     <?php endwhile; ?>
    </tbody>
   </table>

   <!-- Pagination Controls -->
   <nav>
    <ul class="pagination justify-content-center">
     <?php if ($current_page > 1): ?>
     <li class="page-item">
      <a class="page-link"
       href="generate_report-1.php?report_type=<?php echo urlencode($report_type); ?>&page=<?php echo $current_page - 1; ?>">Previous</a>
     </li>
     <?php endif; ?>

     <?php for ($i = 1; $i <= $total_pages; $i++): ?>
     <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
      <a class="page-link"
       href="generate_report-1.php?report_type=<?php echo urlencode($report_type); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
     </li>
     <?php endfor; ?>

     <?php if ($current_page < $total_pages): ?>
     <li class="page-item">
      <a class="page-link"
       href="generate_report-1.php?report_type=<?php echo urlencode($report_type); ?>&page=<?php echo $current_page + 1; ?>">Next</a>
     </li>
     <?php endif; ?>
    </ul>
   </nav>

   <!-- Export Buttons -->
   <div class="d-flex justify-content-between">
    <a href="download_report.php?report_type=<?php echo urlencode($report_type); ?>" class="btn btn-success">Download as
     PDF</a>
    <a href="export_excel.php?report_type=<?php echo urlencode($report_type); ?>" class="btn btn-info">Export to
     Excel</a>
    <a href="export_word.php?report_type=<?php echo urlencode($report_type); ?>" class="btn btn-warning">Export to
     Word</a>
    <button class="btn btn-primary" onclick="window.print()">Print</button>
   </div>
  </div>
  <?php endif; ?>
 </div>

 <!-- Footer -->
 <div class="footer">
  <p>&copy; <?php echo date("Y"); ?> ABC Company</p>
 </div>
</body>

</html>