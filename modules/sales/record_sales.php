<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abc_company";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding sales data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $reference = $_POST['reference'];
    $invoice_no = $_POST['invoice_no'];
    $item_id = $_POST['item_id'];
    $item_description = $_POST['item_description'];
    $uom = $_POST['uom'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];
    $total_value = $quantity * $unit_price;
    $salesperson_id = $_POST['salesperson_id'];
    $salesperson_name = $_POST['salesperson_name'];
    $sales_branch = $_POST['branch'];
    $section = $_POST['section'];

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO sales (date, reference, invoice_no, item_id, item_description, uom, quantity, unit_price, total_value, salesperson_id, salesperson_name, branch, section)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdidssss",$date, $reference, $invoice_no, $item_id, $item_description, $uom, $quantity, $unit_price, $total_value, $salesperson_id, $salesperson_name, $sales_branch, $section);

    if ($stmt->execute()) {
        echo "<script>alert('Sales data added successfully!');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Retrieve sales records for the table
$sql = "SELECT * FROM sales";
$result = $conn->query($sql);

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Record</title>
    <style>
      @media screen and (max-width: 680px) {
  .header, .mainform, input[type=submit] {
    margin: 0px 0px 0px 0px;
  }
}
          
    input[type=text], select, textarea {
    display: flex;
    width: 80%;
    padding: 2px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-top: 6px;
    margin-bottom: 16px;
    resize: vertical;
}

        .form-container{
    margin: 50px 230px 50px 230px;
    padding: 2rem;
    border: 1px solid rgb(224,224,224) ;
    border-radius: 15px;
    box-shadow: 10px 7px 0 rgb(224,224,224);
    background-color: white;
    display: flex;
    }

    </style>
</head>
<body>
    <div class="form-container">
        <h3>Record Sales</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="reference">Reference</label>
                <input type="text" id="reference" name="reference" required>
            </div>
            <div class="form-group">
                <label for="invoice_no">Invoice No.</label>
                <input type="text" id="invoice_no" name="invoice_no" required>
            </div>
            <div class="form-group">
                <label for="item_id">Item ID</label>
                <input type="text" id="item_id" name="item_id" required>
            </div>
            <div class="form-group">
                <label for="item_description">Item Description</label>
                <input type="text" id="item_description" name="item_description" required>
                <label for="Select Item Type"><b>Select Item type</b></label>
    <select id="area" name="area" >
      <option value="----">----</option>
      <option value="I.J Colony">1/2 liter </option>
      <option value="Defence Colony">1 liter</option>
      <option value="Dinga">2 liter</option>
      <option value="Gazi Colony">3 liter</option>
      <option value="Naseera">5 liter</option>
      <option value="Tanveer Town">other</option>
    </select>
    <div id="formsection">

        <label><b>What is the category of complain you are facing?</b></label>

        <p><input type="checkbox" name="section" value="sports">Sports</p>
        <p><input type="checkbox" name="section" value="business">Business</p>
        <p><input type="checkbox" name="section" value="health">Health</p>
        <label for="subject"><b>additional Details:</b></label>

<textarea id="subject" name="subject" placeholder="Enter your additional  details......." style="height:200px"></textarea>

      </div>

            </div>
            <div class="form-group">
                <label for="uom">Unit of Measure (UOM)</label>
                <select id="uom" name="uom" required>
                    <option value="">Select UOM</option>
                    <option value="kg">Kilograms (kg)</option>
                    <option value="pcs">Pieces (pcs)</option>
                    <option value="liters">Liters</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" required>
            </div>
            <div class="form-group">
                <label for="unit_price">Unit Price ($)</label>
                <input type="number" id="unit_price" name="unit_price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="salesperson_id">Salesperson ID</label>
                <input type="number" id="salesperson_id" name="salesperson_id" required>
            </div>
            <div class="form-group">
                <label for="salesperson_name">Salesperson Name</label>
                <input type="text" id="salesperson_name" name="salesperson_name" required>
            </div>
            <div class="form-group">
                <label for="branch">Branch</label>
                <input type="text" id="branch" name="branch" required>
            </div>
            <div class="form-group">
                <label for="section">Section</label>
                <input type="text" id="section" name="section" required>
            </div>
            <div class="form-group">
                <button type="submit">Add Sales</button>
            </div>
        </form>

        <div class="report-button">
            <a href="sales_report.php">View Sales Report</a>
        </div>
    </div>
kkkkkkk

 kkkkkkk
 
 kkkkkkkk
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h1, h2, h3 {
            text-align: center;
        }
        table {
            width: 85%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-header h2, .report-header h3 {
            margin: 5px 0;
        }
        tfoot td {
            font-weight: bold;
        }
    </style>
