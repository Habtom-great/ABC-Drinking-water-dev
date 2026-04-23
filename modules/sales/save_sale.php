<?php
function numberToWords($number) {
    $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    $intPart = floor($number);
    $decimalPart = round(($number - $intPart) * 100);

    $words = ucfirst($f->format($intPart)) . ' birr';
    if ($decimalPart > 0) {
        $words .= ' and ' . $f->format($decimalPart) . ' cents';
    }
    return $words . ' only';
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_database", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // General Information
        $sales_order_no   = $_POST['sales_order_no'] ?? '';
        $invoice_no       = $_POST['invoice_no'] ?? '';
        $reference        = $_POST['reference'] ?? '';
        $date             = $_POST['date'] ?? date('Y-m-d');
        $customer_id      = $_POST['customer_id'] ?? '';
        $customer_name    = $_POST['customer_name'] ?? '';
        $branch_id        = $_POST['branch_id'] ?? '';
        $branch_name      = $_POST['branch_name'] ?? '';
        $salesperson_id   = $_POST['salesperson_id'] ?? '';
        $salesperson_name = $_POST['salesperson_name'] ?? '';
        $job_id           = $_POST['job_id'] ?? '';
        $payment_method   = $_POST['payment_method'] ?? '';

        // Validate items
        if (!isset($_POST['item_id']) || !is_array($_POST['item_id'])) {
            throw new Exception("No items provided for the sale.");
        }

        foreach ($_POST['item_id'] as $i => $item_id) {
            $category     = $_POST['category'][$i] ?? '';
            $uom          = $_POST['uom'][$i] ?? '';
            $quantity     = floatval($_POST['quantity'][$i] ?? 0);
            $unit_price   = floatval($_POST['unit_price'][$i] ?? 0);
            $total        = $quantity * $unit_price;
            $vat          = round($total * 0.15, 2);
            $total_with_vat = $total + $vat;

            // Insert into sales table
            $stmt = $pdo->prepare("
                INSERT INTO sales (
                    sales_order_no, invoice_no, reference, date,
                    customer_id, customer_name, branch_id, branch_name,
                    salesperson_id, salesperson_name, job_id, payment_method,
                    item_id, category, uom, quantity, unit_price,
                    total_sales_before_vat, vat, total_sales_after_vat, total_in_words
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )
            ");

            $total_in_words = numberToWords($total_with_vat);

            $stmt->execute([
                $sales_order_no, $invoice_no, $reference, $date,
                $customer_id, $customer_name, $branch_id, $branch_name,
                $salesperson_id, $salesperson_name, $job_id, $payment_method,
                $item_id, $category, $uom, $quantity, $unit_price,
                $total, $vat, $total_with_vat, $total_in_words
            ]);

            // Update inventory
            $inv_stmt = $pdo->prepare("
                INSERT INTO inventory (item_id, quantity, transaction_type, date)
                VALUES (?, ?, 'sale', ?)
            ");
            $inv_stmt->execute([$item_id, $quantity, $date]);
        }

        header("Location: index.php?success=1");
        exit();
    }
} catch (Exception $e) {
    echo "<div style='color: red; font-weight: bold;'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>