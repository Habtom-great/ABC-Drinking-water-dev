<?php
// Include database connection
require_once 'db_connection.php';

// Check if $pdo is defined
if (!isset($pdo)) {
    die("Error: Database connection not established.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $item_name = trim($_POST['item_name']);
    $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
    $unit_cost = filter_var($_POST['unit_cost'], FILTER_VALIDATE_FLOAT);
    $unit_price = filter_var($_POST['unit_price'], FILTER_VALIDATE_FLOAT);
    $branch = trim($_POST['branch']);

    // Validate inputs
    if (empty($item_name)) {
        echo "<p>Error: Item name is required.</p>";
    } elseif ($quantity === false || $quantity <= 0) {
        echo "<p>Error: Quantity must be greater than zero.</p>";
    } elseif ($unit_cost === false || $unit_cost <= 0) {
        echo "<p>Error: Unit cost must be greater than zero.</p>";
    } elseif ($unit_price === false || $unit_price <= 0) {
        echo "<p>Error: Unit price must be greater than zero.</p>";
    } elseif (empty($branch)) {
        echo "<p>Error: Branch is required.</p>";
    } else {
        try {
            // Insert into database
            $sql = "INSERT INTO inventory (item_name, quantity, unit_cost, unit_price, branch, created_at) 
                    VALUES (:item_name, :quantity, :unit_cost, :unit_price, :branch, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':item_name', $item_name, PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':unit_cost', $unit_cost, PDO::PARAM_STR);
            $stmt->bindParam(':unit_price', $unit_price, PDO::PARAM_STR);
            $stmt->bindParam(':branch', $branch, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "<p>Inventory item added successfully.</p>";
            } else {
                echo "<p>Error: Failed to add inventory item.</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    }
} else {
    echo "<p>Invalid request method.</p>";
}
?>
