<?php
include 'db_connect.php'; // Ensure database connection is included

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $total = $quantity * $price; // Calculate total price

    // Format amounts with commas and cents
    $formatted_price = number_format($price, 2, '.', ',');
    $formatted_total = number_format($total, 2, '.', ',');

    // Convert amount to words (including cents)
    function convertToWords($number) {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = [
            0  => 'Zero',
            1  => 'One',
            2  => 'Two',
            3  => 'Three',
            4  => 'Four',
            5  => 'Five',
            6  => 'Six',
            7  => 'Seven',
            8  => 'Eight',
            9  => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety',
            100 => 'Hundred',
            1000 => 'Thousand',
            1000000 => 'Million',
            1000000000 => 'Billion'
        ];

        if ($number < 0) {
            return $negative . convertToWords(abs($number));
        }

        $string = '';

        if ($number < 21) {
            $string = $dictionary[$number];
        } elseif ($number < 100) {
            $tens = ((int) ($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
        } elseif ($number < 1000) {
            $hundreds = (int) ($number / 100);
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convertToWords($remainder);
            }
        } else {
            foreach ([1000000000, 1000000, 1000] as $value) {
                if ($number >= $value) {
                    $num_units = (int) ($number / $value);
                    $remainder = $number % $value;
                    $string = convertToWords($num_units) . ' ' . $dictionary[$value];
                    if ($remainder) {
                        $string .= $separator . convertToWords($remainder);
                    }
                    break;
                }
            }
        }

        return $string;
    }

    function amountToWords($amount) {
        $whole = floor($amount);
        $cents = round(($amount - $whole) * 100);
        $words = convertToWords($whole) . " Dollars";
        if ($cents > 0) {
            $words .= " and " . convertToWords($cents) . " Cents";
        }
        return $words;
    }

    $amount_in_words = amountToWords($total);

    // Start transaction to ensure atomicity
    $conn->begin_transaction();

    try {
        // Check inventory before proceeding
        $check_stock = $conn->prepare("SELECT stock FROM inventory WHERE product_id = ?");
        $check_stock->bind_param("i", $product_id);
        $check_stock->execute();
        $result = $check_stock->get_result();
        $row = $result->fetch_assoc();

        if (!$row || $row['stock'] < $quantity) {
            throw new Exception("Insufficient stock available.");
        }

        // Reduce inventory stock
        $update_inventory = $conn->prepare("UPDATE inventory SET stock = stock - ? WHERE product_id = ?");
        $update_inventory->bind_param("ii", $quantity, $product_id);
        $update_inventory->execute();

        // Insert into sales table
        $insert_sale = $conn->prepare("INSERT INTO sales (product_id, quantity, price, total, total_in_words, sale_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $insert_sale->bind_param("iidds", $product_id, $quantity, $price, $total, $amount_in_words);
        $insert_sale->execute();

        // Commit transaction
        $conn->commit();

        // Success response
        echo json_encode([
            "status" => "success",
            "message" => "Sale processed successfully.",
            "product_id" => $product_id,
            "quantity" => $quantity,
            "price" => $formatted_price,
            "total" => $formatted_total,
            "total_in_words" => $amount_in_words
        ]);
    } catch (Exception $e) {
        $conn->rollback(); // Rollback on failure
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

    // Close connections
    $check_stock->close();
    $update_inventory->close();
    $insert_sale->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>

kkkkk
<?php
header("Content-Type: application/json"); // Set JSON response format
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Read JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if required fields are present
    if (!isset($data["sale_id"]) || !isset($data["amount"]) || !isset($data["customer_id"])) {
        echo json_encode(["status" => "error", "message" => "Invalid request. Missing required fields."]);
        exit;
    }

    // Sanitize input
    $sale_id = intval($data["sale_id"]);
    $amount = floatval($data["amount"]);
    $customer_id = intval($data["customer_id"]);

    // Simulated database response (replace this with actual DB queries)
    $response = [
        "status" => "success",
        "message" => "Request processed successfully.",
        "sale_id" => $sale_id,
        "amount" => $amount,
        "customer_id" => $customer_id
    ];

    echo json_encode($response);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method. Use POST."]);
}
?>
kkkkkk
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("submitBtn").addEventListener("click", function () {
        let saleData = {
            sale_id: 101,
            amount: 250.50,
            customer_id: 5
        };

        fetch("process_request.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(saleData)
        })
        .then(response => response.json())
        .then(data => {
            console.log("Response:", data);
            document.getElementById("response").innerText = JSON.stringify(data, null, 2);
        })
        .catch(error => {
            console.error("Error:", error);
            document.getElementById("response").innerText = "Error processing request.";
        });
    });
});
