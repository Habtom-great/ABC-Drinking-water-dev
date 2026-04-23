<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the selected cash or bank account
    $cashAccount = $_POST['cash_account'];

    // Retrieve payment details
    $invoiceNos = $_POST['invoice_no'];
    $references = $_POST['reference'];
    $vendorIds = $_POST['vendor_id'];
    $vendorNames = $_POST['vendor_name'];
    $descriptions = $_POST['description'];
    $accountIds = $_POST['account_id'];
    $amounts = $_POST['amount'];

    // Loop through each row of payment details and process
    foreach ($invoiceNos as $index => $invoiceNo) {
        $reference = $references[$index];
        $vendorId = $vendorIds[$index];
        $vendorName = $vendorNames[$index];
        $description = $descriptions[$index];
        $accountId = $accountIds[$index];
        $amount = $amounts[$index];

        // Perform database insertion (replace with actual DB code)
        // For example:
        // $query = "INSERT INTO payments (cash_account, invoice_no, reference, vendor_id, vendor_name, description, account_id, amount)
        //           VALUES ('$cashAccount', '$invoiceNo', '$reference', '$vendorId', '$vendorName', '$description', '$accountId', '$amount')";
        // mysqli_query($connection, $query);

        echo "Payment for Invoice No: $invoiceNo successfully processed.<br>";
    }
}
?>
