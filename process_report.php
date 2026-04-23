<?php
include 'config.php';

$branch = $_POST['branch'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$section = $_POST['section'] ?? '';
$quantity = $_POST['quantity'] ?? 0;
$value = $_POST['value'] ?? 0;
$salesperson = $_POST['salesperson'] ?? '';

$sql = "SELECT * FROM product_movements WHERE 1=1";

if (!empty($branch)) {
    $sql .= " AND branch LIKE '%$branch%'";
}
if (!empty($start_date) && !empty($end_date)) {
    $sql .= " AND movement_date BETWEEN '$start_date' AND '$end_date'";
}
if (!empty($section)) {
    $sql .= " AND section LIKE '%$section%'";
}
if (!empty($quantity)) {
    $sql .= " AND quantity >= $quantity";
}
if (!empty($value)) {
    $sql .= " AND value >= $value";
}
if (!empty($salesperson)) {
    $sql .= " AND salesperson LIKE '%$salesperson%'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Product Name</th><th>Branch</th><th>Section</th><th>Quantity</th><th>Value</th><th>Salesperson</th><th>Date</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['product_name']}</td>
                <td>{$row['branch']}</td>
                <td>{$row['section']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['value']}</td>
                <td>{$row['salesperson']}</td>
                <td>{$row['movement_date']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No records found.";
}
?>
