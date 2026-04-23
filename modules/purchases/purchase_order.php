<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4">Purchase Order Form</h2>

        <!-- Form Start -->
        <form id="purchaseForm" action="#" method="POST">

            <div class="row mb-3">
                <label for="supplierName" class="col-sm-2 col-form-label">Supplier Name</label>
                <div class="col-sm-10">
                    <select id="supplierName" name="supplierName" class="form-select" required>
                        <option value="" selected disabled>Select Supplier</option>
                        <option value="Supplier A">Supplier A</option>
                        <option value="Supplier B">Supplier B</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="purchaseDate" class="col-sm-2 col-form-label">Purchase Date</label>
                <div class="col-sm-10">
                    <input type="date" id="purchaseDate" name="purchaseDate" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="referenceNo" class="col-sm-2 col-form-label">Reference No.</label>
                <div class="col-sm-10">
                    <input type="text" id="referenceNo" name="referenceNo" class="form-control" placeholder="Enter Reference No.">
                </div>
            </div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Tax (%)</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="product[]" class="form-control" placeholder="Product Name" required></td>
                            <td><input type="number" name="quantity[]" class="form-control" value="1" min="1" oninput="calculateTotal(this)" required></td>
                            <td><input type="number" name="price[]" class="form-control" oninput="calculateTotal(this)" required></td>
                            <td><input type="number" name="discount[]" class="form-control" value="0" oninput="calculateTotal(this)"></td>
                            <td><input type="number" name="tax[]" class="form-control" value="0" oninput="calculateTotal(this)"></td>
                            <td><input type="text" name="total[]" class="form-control" readonly></td>
                            <td><button type="button" class="btn btn-danger" onclick="deleteRow(this)">Delete</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-end mb-3">
                <label for="grandTotal" class="form-label">Grand Total ($):</label>
                <input type="text" id="grandTotal" class="form-control" readonly>
            </div>

            <div class="row mb-3">
                <label for="ledgerAccount" class="col-sm-2 col-form-label">Ledger Account</label>
                <div class="col-sm-10">
                    <select id="ledgerAccount" name="ledgerAccount" class="form-select" required>
                        <option value="" selected disabled>Select Account</option>
                        <option value="Cash/Bank">Cash/Bank</option>
                        <option value="Accounts Payable">Accounts Payable</option>
                        <option value="Expense Account">Expense Account</option>
                        <option value="Liability Account">Liability Account</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="supportingDocs" class="form-label">Attach Supporting Documents</label>
                <input type="file" id="supportingDocs" name="supportingDocs" class="form-control">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <!-- Form End -->

    </div>

    <script>
        // Add product row dynamically
        function addRow() {
            const table = document.querySelector('.table tbody');
            const newRow = table.rows[0].cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            table.appendChild(newRow);
        }

        // Delete product row
        function deleteRow(button) {
            const row = button.closest('tr');
            if (row.parentElement.children.length > 1) row.remove();
            calculateTotal();
        }

        // Calculate total for each product and update grand total
        function calculateTotal(element) {
            const row = element.closest('tr');
            const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value || 0);
            const price = parseFloat(row.querySelector('[name="price[]"]').value || 0);
            const discount = parseFloat(row.querySelector('[name="discount[]"]').value || 0);
            const tax = parseFloat(row.querySelector('[name="tax[]"]').value || 0);

            let total = (quantity * price) - discount;
            total += total * (tax / 100);

            row.querySelector('[name="total[]"]').value = total.toFixed(2);

            let grandTotal = 0;
            document.querySelectorAll('[name="total[]"]').forEach(input => {
                grandTotal += parseFloat(input.value || 0);
            });
            document.getElementById('grandTotal').value = grandTotal.toFixed(2);
        }

        // Submit validation for required fields
        document.getElementById('purchaseForm').addEventListener('submit', function(event) {
            const ledgerAccount = document.getElementById('ledgerAccount').value;
            if (!ledgerAccount || !['Cash/Bank', 'Accounts Payable', 'Expense Account', 'Liability Account'].includes(ledgerAccount)) {
                alert('Please select a valid ledger account.');
                event.preventDefault();
            }
        });
    </script>

</body>

</html>
