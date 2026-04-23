<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "abc_company";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get vendor ID from URL parameter
$vendor_id = isset($_GET['vendor_id']) ? intval($_GET['vendor_id']) : 0;

if ($vendor_id <= 0) {
    die("Invalid vendor ID");
}

// Fetch vendor details
$vendor_sql = "SELECT * FROM vendors WHERE vendor_id = $vendor_id";
$vendor_result = $conn->query($vendor_sql);

if ($vendor_result->num_rows !== 1) {
    die("Vendor not found");
}

$vendor = $vendor_result->fetch_assoc();

// Fetch vendor history (purchases, updates, etc.)
$history_sql = "SELECT * FROM vendor_history 
                WHERE vendor_id = $vendor_id 
                ORDER BY action_date DESC";
$history_result = $conn->query($history_sql);
?>
<!DOCTYPE html>
<html lang="en">
<!-- Rest of your HTML head section -->

<head>
 <!-- ... existing head content ... -->
</head>

<body>
 <!-- Header and navigation code ... -->

 <!-- Vendor Profile Card -->
 <div class="vendor-card">
  <div class="row align-items-center">
   <div class="col-md-2 text-center">
    <img
     src="<?php echo htmlspecialchars(isset($vendor['profile_image']) && $vendor['profile_image'] ? $vendor['profile_image'] : 'assets/images/default-profile.png'); ?>"
     alt="Vendor Profile" class="profile-img">
   </div>
   <!-- Rest of your HTML body ... -->
  </div>
 </div>

 <!-- Rest of your HTML ... -->
</body>

</html>
<?php
$conn->close();
?>
kkkkk

<?php
// Database connection (update with your database details)
$servername = "localhost";  // Replace with your MySQL username
$username = "root";  // Replace with your MySQL username, default for XAMPP is 'root'
$password = "";  // Replace with your MySQL password, default for XAMPP is empty
$dbname = "ABC_Company";  // Replace with your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get vendor ID (ensure it's set and not empty)
if (isset($_GET['vendor_id']) && !empty($_GET['vendor_id'])) {
    $vendor_id = mysqli_real_escape_string($conn, $_GET['vendor_id']);
} else {
    die("Vendor ID is required.");
}

// SQL query to fetch vendor history and vendor details based on the vendor ID
$query = "
    SELECT vh.*, vendor_name, v.address, v.telephone, v.email 
    FROM vendor_history vh
    JOIN vendors v ON vh.vendor_id = v.vendor_id
    WHERE vh.vendor_id = '$vendor_id'
";
$history_result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$history_result) {
    die('Query Failed: ' . mysqli_error($conn));  // Output query error if it fails
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Vendor History</title>
 <style>
 /* Add your styling here */
 body {
  font-family: 'Arial', sans-serif;
  background-color: #f4f4f9;
  margin: 0;
  padding: 0;
 }

 h1 {
  text-align: center;
  color: #333;
  margin-top: 30px;
  font-size: 2em;
 }

 .container {
  width: 80%;
  margin: 0 auto;
  background-color: #fff;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 40px;
 }

 table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
 }

 th,
 td {
  border: 1px solid #ddd;
  padding: 12px;
  text-align: left;
  font-size: 1em;
 }

 th {
  background-color: #4CAF50;
  color: white;
 }

 tr:nth-child(even) {
  background-color: #f2f2f2;
 }

 tr:hover {
  background-color: #f1f1f1;
 }

 .error-message {
  color: red;
  text-align: center;
  font-size: 1.2em;
  margin-top: 20px;
 }

 /* Vendor Details Styling */
 .vendor-details {
  border: 1px solid #ddd;
  padding: 15px;
  background-color: #f9f9f9;
  margin-top: 20px;
  border-radius: 8px;
 }

 .vendor-details h2 {
  text-align: center;
  color: #333;
  font-size: 1.5em;
 }

 .vendor-details p {
  font-size: 1.1em;
  margin: 8px 0;
 }

 /* Responsive design */
 @media (max-width: 768px) {
  table {
   font-size: 0.9em;
  }

  .container {
   width: 95%;
  }
 }
 </style>
</head>

<body>
 <div class="container">
  <h1>Vendor History</h1>

  <?php
        // Check if the vendor history exists
        if (mysqli_num_rows($history_result) > 0) {
            // Display vendor details
            $vendor_info = mysqli_fetch_assoc($history_result);
            echo "<div class='vendor-details'>";
            echo "<h2>Vendor Details</h2>";
            echo "<p><strong>Vendor Name:</strong> " . htmlspecialchars($vendor_info['vendor_name']) . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($vendor_info['address']) . "</p>";
            echo "<p><strong>Telephone:</strong> " . htmlspecialchars($vendor_info['telephone']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($vendor_info['email']) . "</p>";
            echo "</div>";

            // Reset the result pointer to fetch the history rows again
            mysqli_data_seek($history_result, 0);

            // Display the vendor history table
            echo "<table>";
            echo "<tr><th>Invoice No</th><th>Date</th><th>Purchase</th><th>Amount</th><th>Balance</th></tr>";

            // Fetch and display data for each row
            while ($row = mysqli_fetch_assoc($history_result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['invoice no']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['purchase']) . "</td>";
                echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                echo "<td>" . htmlspecialchars($row['balance']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p class='error-message'>No history found for this vendor.</p>";
        }

        // Free result and close connection
        mysqli_free_result($history_result);
        mysqli_close($conn);
        ?>
 </div>
</body>

</html>