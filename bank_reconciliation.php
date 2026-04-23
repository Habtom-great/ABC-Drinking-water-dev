<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Reconciliation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .header {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
            font-size: 1.8rem;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
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
            padding: 10px;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        .sorting-options {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .sorting-options select {
            flex: 1;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        Bank Reconciliation Worksheet
    </div>

    <div class="container">
        <form id="bankForm">
            <!-- Bank Selection -->
            <div class="form-group">
                <label for="bank_account">Select Bank Account:</label>
                <select id="bank_account" name="bank_account" required>
                    <option value="">-- Choose Bank Account --</option>
                    <option value="bank1">Bank Account 1</option>
                    <option value="bank2">Bank Account 2</option>
                    <option value="bank3">Bank Account 3</option>
                </select>
            </div>

            <!-- Bank Ledger Balance -->
            <div class="form-group">
                <label for="ledger_balance">Bank Ledger Balance:</label>
                <input type="text" id="ledger_balance" name="ledger_balance" readonly value="Select a bank to load balance">
            </div>

            <!-- Actual Bank Balance -->
            <div class="form-group">
                <label for="actual_bank_balance">Enter Actual Bank Balance (Manual):</label>
                <input type="number" id="actual_bank_balance" name="actual_bank_balance" step="0.01" placeholder="Enter actual balance">
            </div>

            <!-- Sorting Options -->
            <div class="sorting-options">
                <select id="sort_criteria">
                    <option value="date">Sort by Date</option>
                    <option value="amount">Sort by Amount</option>
                    <option value="reference">Sort by Reference</option>
                    <option value="description">Sort by Description</option>
                </select>
                <select id="display_type">
                    <option value="payments">Show Payments First</option>
                    <option value="collections">Show Collections First</option>
                </select>
            </div>

            <!-- Transactions Table -->
            <h3>Transactions</h3>
            <table id="transactionsTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Description</th>
                        <th>Debit (Collections)</th>
                        <th>Credit (Payments)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Transactions will be loaded dynamically -->
                </tbody>
            </table>

            <button type="button" onclick="loadTransactions()">Apply Sorting</button>
        </form>
    </div>
</body>
<script src="script.js"></script>
</html>
<script>
       const transactions = [
    { date: '2025-01-01', reference: 'INV-001', description: 'Customer Payment', debit: 1000, credit: null },
    { date: '2025-01-02', reference: 'EXP-002', description: 'Office Supplies', debit: null, credit: 150 },
    { date: '2025-01-03', reference: 'INV-003', description: 'Service Revenue', debit: 500, credit: null },
    { date: '2025-01-04', reference: 'EXP-004', description: 'Utility Bill', debit: null, credit: 200 },
];

function loadTransactions() {
    const sortCriteria = document.getElementById('sort_criteria').value;
    const displayType = document.getElementById('display_type').value;

    let sortedTransactions = [...transactions];

    // Sort transactions based on criteria
    sortedTransactions.sort((a, b) => {
        if (sortCriteria === 'amount') {
            return (b.debit || b.credit) - (a.debit || a.credit);
        } else if (sortCriteria === 'date') {
            return new Date(a.date) - new Date(b.date);
        } else if (sortCriteria === 'reference') {
            return a.reference.localeCompare(b.reference);
        } else if (sortCriteria === 'description') {
            return a.description.localeCompare(b.description);
        }
    });

    // Filter by display type
    if (displayType === 'payments') {
        sortedTransactions = [
            ...sortedTransactions.filter(t => t.credit),
            ...sortedTransactions.filter(t => t.debit),
        ];
    } else if (displayType === 'collections') {
        sortedTransactions = [
            ...sortedTransactions.filter(t => t.debit),
            ...sortedTransactions.filter(t => t.credit),
        ];
    }

    // Update table
    const tableBody = document.getElementById('transactionsTable').querySelector('tbody');
    tableBody.innerHTML = '';
    sortedTransactions.forEach(t => {
        tableBody.innerHTML += `
            <tr>
                <td>${t.date}</td>
                <td>${t.reference}</td>
                <td>${t.description}</td>
                <td>${t.debit ? t.debit.toFixed(2) : ''}</td>
                <td>${t.credit ? t.credit.toFixed(2) : ''}</td>
            </tr>
        `;
    });
}

// Initial load
loadTransactions();
         
</script>