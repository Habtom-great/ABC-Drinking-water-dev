<?php
// Database connection
$host = "localhost"; // Replace with your database host
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "ABC_Company"; // Replace with your database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form inputs
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $quantity = (int)$_POST['quantity'];
    $unit_cost = (float)$_POST['unit_cost'];
    $unit_price = (float)$_POST['unit_price'];
    $branch = $conn->real_escape_string($_POST['branch']);
    $uom = $conn->real_escape_string($_POST['uom']);
    $purchase_date = $conn->real_escape_string($_POST['purchase_date']);

    // SQL query to insert data into the inventory table
    $sql = "INSERT INTO inventory (item_name, quantity, unit_cost, unit_price, branch, uom, purchase_date) 
            VALUES ('$item_name', $quantity, $unit_cost, $unit_price, '$branch', '$uom', '$purchase_date')";

    // Execute query
    if ($conn->query($sql) === TRUE) {
        echo "<h3>Data entry saved successfully!</h3>";
        echo "<a href='index.html'>Go back</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>


kkkkkkkkkkkkkk

<?php
// Initialize variables to prevent undefined index warnings
$companyName = isset($_POST['Company Name']) ? $_POST['Company Name'] : '';
$address = isset($_POST['Address']) ? $_POST['Address'] : '';
$date = isset($_POST['Date']) ? $_POST['Date'] : '';
$invoiceNo = isset($_POST['Invoice No']) ? $_POST['Invoice No'] : '';
$vat = isset($_POST['VAT (%)']) ? (float)$_POST['VAT (%)'] : 0.0;
$withhold = isset($_POST['Withhold']) ? (float)$_POST['Withhold'] : 0.0;
$subTotal = isset($_POST['Sub Total']) ? (float)$_POST['Sub Total'] : 0.0;
$netTotal = isset($_POST['Net Total']) ? (float)$_POST['Net Total'] : 0.0;
$netTotalWords = isset($_POST['Net Total in Words']) ? $_POST['Net Total in Words'] : '';

// Calculate VAT Amount
$vatAmount = $subTotal * ($vat / 100);

// Process the items array
$items = [];
if (isset($_POST['Items']) && is_array($_POST['Items'])) {
    foreach ($_POST['Items'] as $item) {
        $itemId = isset($item['Item ID']) ? $item['Item ID'] : '';
        $description = isset($item['Description']) ? $item['Description'] : '';
        $uom = isset($item['UoM']) ? $item['UoM'] : '';
        $quantity = isset($item['Quantity']) ? (float)$item['Quantity'] : 0.0;
        $unitCost = isset($item['Unit Cost']) ? (float)$item['Unit Cost'] : 0.0;
        $totalCost = $quantity * $unitCost;

        // Store each item in an array
        $items[] = [
            'Item ID' => $itemId,
            'Description' => $description,
            'UoM' => $uom,
            'Quantity' => $quantity,
            'Unit Cost' => $unitCost,
            'Total Cost' => $totalCost,
        ];
    }
}

// Output the processed purchase data (for debugging or confirmation purposes)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Purchase Details</h1>

    <div class="purchase-summary">
        <h2>Company Information</h2>
        <p><strong>Company Name:</strong> <?php echo htmlspecialchars($companyName); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($date); ?></p>
        <p><strong>Invoice No:</strong> <?php echo htmlspecialchars($invoiceNo); ?></p>
        <p><strong>Sub Total:</strong> $<?php echo number_format($subTotal, 2); ?></p>
        <p><strong>VAT (%):</strong> <?php echo htmlspecialchars($vat); ?>%</p>
        <p><strong>VAT Amount:</strong> $<?php echo number_format($vatAmount, 2); ?></p>
        <p><strong>Withhold:</strong> $<?php echo number_format($withhold, 2); ?></p>
        <p><strong>Net Total:</strong> $<?php echo number_format($netTotal, 2); ?></p>
        <p><strong>Net Total in Words:</strong> <?php echo htmlspecialchars($netTotalWords); ?></p>
    </div>

    <h2>Items</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Item ID</th>
                <th>Description</th>
                <th>UoM</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Total Cost</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['Item ID']); ?></td>
                        <td><?php echo htmlspecialchars($item['Description']); ?></td>
                        <td><?php echo htmlspecialchars($item['UoM']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($item['Quantity'], 2)); ?></td>
                        <td>$<?php echo number_format($item['Unit Cost'], 2); ?></td>
                        <td>$<?php echo number_format($item['Total Cost'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No items found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

kkkkkkkkkk
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data with validation
    $supplierName = $_POST['supplierName'] ?? 'N/A';
    $purchaseDate = $_POST['purchaseDate'] ?? 'N/A';
    $referenceNo = $_POST['referenceNo'] ?? 'N/A';
    $ledgerAccount = $_POST['ledgerAccount'] ?? 'N/A';
    $invoiceNo = $_POST['invoice_no'] ?? 'N/A';
    $orderNo = $_POST['order_no'] ?? 'N/A';
    $purchaseNo = $_POST['purchase_no'] ?? 'N/A';
    $location = $_POST['location'] ?? 'N/A';
    $description = $_POST['description'] ?? 'N/A';

    // Retrieve product details
    $items = $_POST['items'] ?? [];
    $itemDescriptions = $_POST['itemDescription'] ?? [];
    $uoms = $_POST['uom'] ?? [];
    $quantities = $_POST['quantity'] ?? [];
    $unitPrices = $_POST['unit_price'] ?? [];
    $discounts = $_POST['discount'] ?? [];
    $taxes = $_POST['tax'] ?? [];
    $totalCosts = $_POST['total_cost'] ?? [];

    // Invoice summary calculations
    $subTotal = array_sum($totalCosts);
    $vat = $_POST['vat'] ?? 0;
    $vatAmount = ($vat / 100) * $subTotal;
    $withhold = $_POST['withhold'] ?? 0;
    $netTotal = $subTotal + $vatAmount - $withhold;
    $netTotalWords = ucwords(number_format($netTotal, 2)) . " Dollars"; // Example conversion

    // Output invoice details
    echo "<h2>Purchase Details</h2>";
    echo "<p><strong>Supplier Name:</strong> $supplierName</p>";
    echo "<p><strong>Purchase Date:</strong> $purchaseDate</p>";
    echo "<p><strong>Reference No:</strong> $referenceNo</p>";
    echo "<p><strong>Ledger Account:</strong> $ledgerAccount</p>";
    echo "<p><strong>Invoice No:</strong> $invoiceNo</p>";
    echo "<p><strong>Order No:</strong> $orderNo</p>";
    echo "<p><strong>Purchase No:</strong> $purchaseNo</p>";
    echo "<p><strong>Location:</strong> $location</p>";

    echo "<table>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Description</th>
                    <th>UOM</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody>";

    foreach ($items as $index => $itemId) {
        $itemDescription = $itemDescriptions[$index] ?? 'N/A';
        $uom = $uoms[$index] ?? 'N/A';
        $quantity = $quantities[$index] ?? 0;
        $unitPrice = $unitPrices[$index] ?? 0.00;
        $totalCost = $totalCosts[$index] ?? 0.00;

        echo "<tr>
                <td>$itemId</td>
                <td>$itemDescription</td>
                <td>$uom</td>
                <td>$quantity</td>
                <td>$" . number_format($unitPrice, 2) . "</td>
                <td>$" . number_format($totalCost, 2) . "</td>
              </tr>";
    }

    echo "</tbody>
          </table>";

    echo "<div class='invoice-summary'>
            <h3>Invoice Summary</h3>
            <p><strong>Sub Total:</strong> $" . number_format($subTotal, 2) . "</p>
            <p><strong>VAT (%):</strong> $vat%</p>
            <p><strong>VAT Amount:</strong> $" . number_format($vatAmount, 2) . "</p>
            <p><strong>Withhold:</strong> $" . number_format($withhold, 2) . "</p>
            <p><strong>Net Total:</strong> $" . number_format($netTotal, 2) . "</p>
            <p><strong>Net Total in Words:</strong> $netTotalWords</p>
          </div>";

    echo "<p><strong>Description:</strong> $description</p>";
} else {
    echo "<p style='color: red;'>Invalid request!</p>";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        padding: 0;
        background-color: #f9f9f9;
        color: #333;
    }
    h2, h3 {
        text-align: center;
        color: #0056b3;
    }
    table {
        margin: 20px auto;
        width: 90%;
        border-collapse: collapse;
    }
    table th, table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }
    table th {
        background-color: #0056b3;
        color: #fff;
    }
    .invoice-summary {
        margin: 20px auto;
        width: 80%;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
</style>

