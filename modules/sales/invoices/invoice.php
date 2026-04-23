<?php
include 'db_connection.php';

// Ensure the purchase_invoice_no is set
if (isset($_GET['purchase_invoice_no'])) {
    $purchase_invoice_no = $_GET['purchase_invoice_no'];

    // Prepare the SQL statement to fetch data
    $stmt = $conn->prepare("SELECT purchase_invoice_no, reference, description, quantity, unit_cost, total_purchased_before_vat, total_purchased_after_vat, vat, date 
                            FROM inventory 
                            WHERE purchase_invoice_no = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind and execute the statement
    $stmt->bind_param("s", $purchase_invoice_no);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if results are returned
    if ($result->num_rows > 0) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "<h3 style='color:red;'>No data found for Invoice No: $purchase_invoice_no</h3>";
        exit;
    }
} else {
    echo "<h3 style='color:red;'>No invoice number provided.</h3>";
    exit;
}

// Function to convert numbers to words
function numberToWords($num) {
    $ones = array(
        0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 
        5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 
        14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
        18 => 'Eighteen', 19 => 'Nineteen'
    );

    $tens = array(
        2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty', 
        6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
    );

    $thousands = array(
        0 => '', 1 => 'Thousand', 2 => 'Million', 3 => 'Billion', 
        4 => 'Trillion'
    );

    if ($num == 0) {
        return 'Zero';
    }

    $num_str = number_format($num, 2, '.', ''); // Format to two decimals

    // Split into the integer and decimal parts
    list($integer, $decimal) = explode('.', $num_str);

    $integer_words = convertIntegerToWords($integer, $ones, $tens, $thousands);
    $decimal_words = convertIntegerToWords($decimal, $ones, $tens, $thousands);

    // Combine the words and decimal
    return $integer_words . ' and ' . $decimal_words . ' Cents';
}

function convertIntegerToWords($num, $ones, $tens, $thousands) {
    $num = (int) $num;
    if ($num == 0) return '';

    $result = '';
    $i = 0;

    // Process thousands, millions, etc.
    while ($num > 0) {
        if ($num % 1000 != 0) {
            $result = convertHundreds($num % 1000) . $thousands[$i] . ' ' . $result;
        }
        $num = floor($num / 1000);
        $i++;
    }

    return $result;
}

function convertHundreds($num) {
    $ones = array(
        0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 
        5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 
        14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
        18 => 'Eighteen', 19 => 'Nineteen'
    );

    $tens = array(
        2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty', 
        6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
    );

    $result = '';
    if ($num >= 100) {
        $result .= $ones[intval($num / 100)] . ' Hundred ';
        $num = $num % 100;
    }

    if ($num >= 20) {
        $result .= $tens[intval($num / 10)] . ' ';
        $num = $num % 10;
    }

    if ($num > 0) {
        $result .= $ones[$num] . ' ';
    }

    return $result;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Invoice - <?php echo htmlspecialchars($purchase_invoice_no); ?></title>
 <style>
 body {
  font-family: Arial, sans-serif;
  background-color: #f0f2f5;
 }

 .invoice-box {
  background: #fff;
  padding: 25px;
  border-radius: 10px;
  width: 100%;
  max-width: 950px;
  margin: 0 auto;
  border: 1px solid #ddd;
 }

 .invoice-box h2 {
  text-align: center;
  color: #333;
 }

 .invoice-box img {
  max-width: 100px;
  margin-bottom: 20px;
 }

 .company-details {
  text-align: center;
  margin-bottom: 20px;
 }

 .company-details p {
  margin: 5px 0;
  font-size: 14px;
 }

 .invoice-info {
  margin-bottom: 20px;
 }

 table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
 }

 th,
 td {
  padding: 10px;
  text-align: left;
  border: 1px solid #ddd;
 }

 th {
  background-color: #f4f4f4;
 }

 .total-row td {
  font-weight: bold;
 }

 .words {
  margin-top: 20px;
 }

 .print-btn {
  margin-top: 20px;
  text-align: right;
 }

 .small-label {
  font-size: 12px;
  color: #888;
 }

 .sub-info {
  font-size: 12px;
  color: #555;
 }

 @media print {
  .print-btn {
   display: none;
  }
 }
 </style>
</head>

<body>

 <div class="invoice-box">
  <div class="company-details">
   <img src="path/to/your/logo.png" alt="Company Logo"> <!-- Update the logo path -->
   <h2>Company Name</h2>
   <p>Company Address</p>
   <p>Phone: +1234567890 | Email: info@company.com</p>
   <p>Website: www.company.com</p>
  </div>

  <div class="invoice-info">
   <h3>Invoice Details</h3>
   <p><strong>Invoice No:</strong> <?php echo htmlspecialchars($purchase_invoice_no); ?></p>
   <p><strong>Date:</strong> <?php echo isset($rows[0]['date']) ? htmlspecialchars($rows[0]['date']) : 'N/A'; ?></p>
   <p><strong>Reference:</strong> <?php echo htmlspecialchars($rows[0]['reference']); ?></p>
  </div>

  <table>
   <thead>
    <tr>
     <th>#</th>
     <th>Reference</th>
     <th>Description</th>
     <th>Qty</th>
     <th>Unit Cost</th>
     <th>Total Before VAT</th>
    </tr>
   </thead>
   <tbody>
    <?php
                $grand_total = 0;
                // Iterate over the rows and display the data
                if (!empty($rows)) {
                    foreach ($rows as $index => $row) {
                        $unit_cost = floatval($row['unit_cost'] ?? 0);
                        $before_vat = floatval($row['total_purchased_before_vat'] ?? 0);
                        $vat = floatval($row['vat'] ?? 0);
                        $after_vat = floatval($row['total_purchased_after_vat'] ?? 0);
                        $grand_total += $after_vat;

                        echo "<tr>
                                <td>" . ($index + 1) . "</td>
                                <td>" . htmlspecialchars($row['reference']) . "</td>
                                <td>" . htmlspecialchars($row['description']) . "</td>
                                <td>" . htmlspecialchars($row['quantity']) . "</td>
                                <td>" . number_format($unit_cost, 2) . "</td>
                                <td>" . number_format($before_vat, 2) . "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No items found in this invoice.</td></tr>";
                }
                ?>
   </tbody>
   <tfoot>
    <tr class="total-row">
     <td colspan="5" style="text-align: right;">VAT:</td>
     <td><?php echo number_format($vat, 2); ?></td>
    </tr>
    <tr class="total-row">
     <td colspan="5" style="text-align: right;">Total After VAT:</td>
     <td><?php echo number_format((float)$grand_total, 2); ?></td>
    </tr>
   </tfoot>
  </table>

  <div class="words">
   <p><strong>Amount in Words:</strong> <?php echo numberToWords($grand_total); ?></p>
  </div>

  <div class="print-btn">
   <button onclick="window.print()">Print Invoice</button>
  </div>
 </div>

</body>

</html>