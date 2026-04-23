<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: inline-block;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 12px;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        .add-row-btn {
            background-color: #28a745;
            color: white;
            margin-top: 15px;
            padding: 12px;
        }

        .add-row-btn:hover {
            background-color: #218838;
        }

        .remove-row-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
        }

        .remove-row-btn:hover {
            background-color: #c82333;
        }

        .form-group button[type="submit"] {
            width: auto;
            padding: 12px 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment Form</h2>
        <form action="process_payment.php" method="post">
            <!-- Bank or Cash Selection -->
            <label for="cash_account">Cash/Bank Account</label>
<select id="cash_account" name="cash_account">
    <option value="">Select a Cash/Bank Account</option>
    <option value="Bank1">Bank 1</option>
    <option value="Bank2">Bank 2</option>
</select>

<label for="accounts_payable">Accounts Payable Account</label>
<select id="accounts_payable" name="accounts_payable">
    <option value="">Select an Accounts Payable Account</option>
    <option value="AP1">Accounts Payable 1</option>
    <option value="AP2">Accounts Payable 2</option>
</select>


            <!-- Payment Details Table -->
            <table id="paymentTable">
                <thead>
                    <tr>
                        <th>Invoice No.</th>
                        <th>Reference</th>
                        <th>Vendor ID</th>
                        <th>Vendor Name</th>
                        <th>Description</th>
                        <th>Account ID</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Initial Row -->
                    <tr>
                        <td><input type="text" name="invoice_no[]" required></td>
                        <td><input type="text" name="reference[]" required></td>
                        <td><input type="text" name="vendor_id[]" required></td>
                        <td><input type="text" name="vendor_name[]" required></td>
                        <td><input type="text" name="description[]" required></td>
                        <td>
                            <select name="account_id[]" required>
                                <option value="">-- Select Account ID --</option>
                                <option value="ledger1">Ledger 1</option>
                                <option value="ledger2">Ledger 2</option>
                                <option value="ledger3">Ledger 3</option>
                            </select>
                        </td>
                        <td><input type="number" name="amount[]" step="0.01" required></td>
                        <td>
                            <button type="button" class="remove-row-btn" onclick="removeRow(this)">Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="add-row-btn" onclick="addRow()">Add Row</button>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit">Submit Payment</button>
            </div>
        </form>
    </div>

    <script>
        // Function to add a new row to the table
        function addRow() {
            const table = document.getElementById('paymentTable').querySelector('tbody');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td><input type="text" name="invoice_no[]" required></td>
                <td><input type="text" name="reference[]" required></td>
                <td><input type="text" name="vendor_id[]" required></td>
                <td><input type="text" name="vendor_name[]" required></td>
                <td><input type="text" name="description[]" required></td>
                <td>
                    <select name="account_id[]" required>
                        <option value="">-- Select Account ID --</option>
                        <option value="ledger1">Ledger 1</option>
                        <option value="ledger2">Ledger 2</option>
                        <option value="ledger3">Ledger 3</option>
                    </select>
                </td>
                <td><input type="number" name="amount[]" step="0.01" required></td>
                <td>
                    <button type="button" class="remove-row-btn" onclick="removeRow(this)">Remove</button>
                </td>
            `;

            table.appendChild(newRow);
        }

        // Function to remove a row from the table
        function removeRow(button) {
            const row = button.closest('tr');
            row.remove();
        }
    </script>
</body>
</html>
