<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "abc_company");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if update form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Get data from POST
    $id = $_POST['account_id'];
    $name = $_POST['account_name'];
    $type = $_POST['account_type'];
    $class = $_POST['account_classification'];
    $normal = $_POST['normal_balance'];
    $beginning = $_POST['beginning_balance'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE charts_of_accounts 
        SET account_name = ?, 
            account_type = ?, 
            account_classification = ?, 
            normal_balance = ?, 
            beginning_balance = ? 
        WHERE account_id = ?");

    // Check if prepare succeeded
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind the parameters: 3 strings, 1 string, 1 double, 1 integer
    $stmt->bind_param("sss sdi", $name, $type, $class, $normal, $beginning, $id);

    // Execute and check success
    if ($stmt->execute()) {
        echo "<script>alert('Account updated successfully.'); window.location.href='manage_charts_of_accounts.php';</script>";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>


<!-- === STYLE === -->
<style>
body {
 font-family: sans-serif;
 background: #f4f4f4;
 padding: 20px;
}

.container {
 background: white;
 padding: 20px;
 border-radius: 8px;
 max-width: 1100px;
 margin: auto;
}

h2 {
 text-align: center;
}

table {
 width: 100%;
 border-collapse: collapse;
 margin-top: 20px;
}

th,
td {
 border: 1px solid #ddd;
 padding: 8px;
 text-align: center;
}

th {
 background: #333;
 color: white;
}

tr:nth-child(even) {
 background: #f9f9f9;
}

input,
select {
 padding: 5px;
}

.total-row {
 font-weight: bold;
 background: #d9f2e6;
}

.delete-btn {
 color: red;
 text-decoration: none;
}
</style>

<!-- === JS LOGIC === -->
<script>
function toggleBalanceFields(selectElem) {
 const classification = selectElem.value;
 const parent = selectElem.closest("form");
 const debitInput = parent.querySelector("input[name='beginning_debit']");
 const creditInput = parent.querySelector("input[name='beginning_credit']");

 if (['Current Assets', 'Non-Current Assets', 'Expenses', 'COGS', 'Purchases'].includes(classification)) {
  debitInput.style.display = '';
  creditInput.style.display = 'none';
 } else {
  debitInput.style.display = 'none';
  creditInput.style.display = '';
 }
}
</script>

<!-- === MAIN INTERFACE === -->
<div class="container">
 <h2>Chart of Accounts (with Live Ledger Balance)</h2>

 <!-- Add Form -->
 <form method="POST">
  <input type="text" name="account_name" placeholder="Account Name" required>
  <select name="account_type" required>
   <option value="">-- Type --</option>
   <?php foreach ($account_types as $t): ?>
   <option value="<?= $t ?>"><?= $t ?></option>
   <?php endforeach; ?>
  </select>
  <select name="account_classification" onchange="toggleBalanceFields(this)" required>
   <option value="">-- Classification --</option>
   <?php foreach ($order_map as $class => $ord): ?>
   <option value="<?= $class ?>"><?= $class ?></option>
   <?php endforeach; ?>
  </select>
  <input type="number" name="beginning_balance" placeholder="Beginning Balance" step="0.01">
  <button type="submit" name="add_account">Add Account</button>
 </form>

 <!-- Table -->
 <table>
  <thead>
   <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Type</th>
    <th>Classification</th>
    <th>Beginning</th>
    <th>Current Balance</th>
    <th>Actions</th>
   </tr>
  </thead>
  <tbody>
   <?php
        $total_debit = 0;
        $total_credit = 0;

        foreach ($accounts as $group) {
            foreach ($group as $row):
                $id = $row['account_id'];
                $current_balance = get_general_ledger_balance($conn, $id);
                $normal = get_normal_balance($row['account_classification']);
                if ($normal === 'Debit') $total_debit += $current_balance;
                else $total_credit += $current_balance;

                $beginning = floatval($row['beginning_balance']);
                $beginning_debit = $beginning > 0 ? $beginning : '';
                $beginning_credit = $beginning < 0 ? abs($beginning) : '';
        ?>
   <tr>
    <form method="POST">
     <input type="hidden" name="account_id" value="<?= $id ?>">
     <td><?= $id ?></td>
     <td><input name="account_name" value="<?= htmlspecialchars($row['account_name']) ?>"></td>
     <td>
      <select name="account_type">
       <?php foreach ($account_types as $t): ?>
       <option value="<?= $t ?>" <?= $row['account_type'] === $t ? 'selected' : '' ?>><?= $t ?></option>
       <?php endforeach; ?>
      </select>
     </td>
     <td>
      <select name="account_classification" onchange="toggleBalanceFields(this)">
       <?php foreach ($order_map as $class => $o): ?>
       <option value="<?= $class ?>" <?= $row['account_classification'] === $class ? 'selected' : '' ?>><?= $class ?>
       </option>
       <?php endforeach; ?>
      </select>
     </td>
     <td>
      <input type="number" name="beginning_debit" placeholder="Debit" step="0.01" value="<?= $beginning_debit ?>"
       style="<?= $beginning >= 0 ? '' : 'display:none;' ?>">
      <input type="number" name="beginning_credit" placeholder="Credit" step="0.01" value="<?= $beginning_credit ?>"
       style="<?= $beginning < 0 ? '' : 'display:none;' ?>">
     </td>
     <td><?= number_format($current_balance, 2) ?></td>
     <td>
      <button name="update_account">Save</button>
      <a href="?delete=<?= $id ?>" class="delete-btn" onclick="return confirm('Delete this account?')">Del</a>
     </td>
    </form>
   </tr>
   <?php endforeach; } ?>
   <tr class="total-row">
    <td colspan="5">Total Debit</td>
    <td colspan="2"><?= number_format($total_debit, 2) ?></td>
   </tr>
   <tr class="total-row">
    <td colspan="5">Total Credit</td>
    <td colspan="2"><?= number_format($total_credit, 2) ?></td>
   </tr>
   <tr class="total-row">
    <td colspan="5">Balanced?</td>
    <td colspan="2" style="color: <?= $total_debit == $total_credit ? 'green' : 'red' ?>">
     <?= $total_debit == $total_credit ? '✅ Balanced' : '❌ Not Balanced' ?>
    </td>
   </tr>
  </tbody>
 </table>
</div>