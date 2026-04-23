<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ledger Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            background-color: #dff0d8;
            color: #3c763d;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .form-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex: 1 1 calc(50% - 20px); /* Each row takes up half the container width */
        }
        .form-group label {
            flex-basis: 30%;
            font-weight: bold;
        }
        .form-group input,
        .form-group select {
            flex-basis: 70%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            padding: 10px 0;
            background-color: #333;
            color: #fff;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Create New Ledger Account</h1>
    </header>

    <div class="container">
        <?php if (isset($message)): ?>
            <div class="message">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="create_ledger.php">
            <div class="form-group">
                <label for="account_id">Account ID:</label>
                <input type="text" id="account_id" name="account_id" required>
            </div>

            <div class="form-group">
                <label for="account_name">Account Name:</label>
                <input type="text" id="account_name" name="account_name" required>
            </div>

            <div class="form-group">
                <label for="category">Account Type:</label>
                <select name="category" id="category" required>
                    <option value="Expense">Expense</option>
                    <option value="Receivables">Receivables</option>
                    <option value="Inventory">Inventory</option>
                    <option value="Cost of Sales">Cost of Sales</option>
                    <option value="Current Assets">Current Assets</option>
                    <option value="Current Liabilities">Current Liabilities</option>
                    <option value="Retained Earnings">Retained Earnings</option>
                    <option value="Capital">Capital</option>
                </select>
            </div>

            <div class="form-group">
                <label for="balance">Balance:</label>
                <input type="number" id="balance" name="balance" required step="0.01">
            </div>

            <button type="submit" name="submit">Create Ledger Account</button>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Your Company Name</p>
    </footer>
</body>
</html>
