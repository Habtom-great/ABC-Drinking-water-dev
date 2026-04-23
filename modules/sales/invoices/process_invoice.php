<?php
include('db_connection.php'); // Ensure the database connection file is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and assign form data
    $invoice_no = isset($_POST['invoice_no']) ? $_POST['invoice_no'] : die("Error: Invoice Number is required.");
    $reference = isset($_POST['reference']) ? $_POST['reference'] : "N/A"; // Optional field with default value
    $grand_total = isset($_POST['grand_total']) ? $_POST['grand_total'] : die("Error: Grand Total is required.");

    // Insert data into the database
    $query = "INSERT INTO invoice_no (invoice_no, reference, grand_total_before_vat)
              VALUES ('$invoice_no', '$reference', '$grand_total')";

    if (mysqli_query($conn, $query)) {
        echo "Invoice processed successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
if (isset($_POST['grand_total']) && !empty($_POST['grand_total'])) {
                 $grand_total = $_POST['grand_total'];
             } else {
                 die("Error: Grand Total is required.");
             }
             
?>
<div class="invoice-box">
                 <form action="save_invoice.php" method="post" id="invoiceForm">

                                  <!-- Header Section -->
                                  <div class="row mb-4">
                                                   <div class="col-md-6">
                                                                    <h2>PURCHASE INVOICE</h2>
                                                                    <div class="form-group">
                                                                                     <label>Supplier:</label>
                                                                                     <select class="form-control"
                                                                                                      name="supplier_id"
                                                                                                      id="supplierSelect"
                                                                                                      required>
                                                                                                      <option value="">Select
                                                                                                                       Supplier
                                                                                                      </option>
                                                                                                      <?php while($row = $suppliers->fetch_assoc()): ?>
                                                                                                      <option
                                                                                                                       value="<?= $row['id'] ?>">
                                                                                                                       <?= $row['name'] ?>
                                                                                                      </option>
                                                                                                      <?php endwhile; ?>
                                                                                     </select>
                                                                    </div>
                                                                    <div id="supplierInfo"></div>
                                                   </div>

                                                   <div class="col-md-6 text-right">
                                                                    <div class="form-group">
                                                                                     <label>Rederences:</label>
                                                                                     <input type="text"
                                                                                                      name="references"
                                                                                                      class="form-control"
                                                                                                      value="<?= $references ?>"
                                                                                                      readonly>
                                                                    </div>
                                                                    <div class="col-md-6 text-right">
                                                                                     <div class="form-group">
                                                                                                      <label>Invoice
                                                                                                                       Number:</label>
                                                                                                      <input type="text"
                                                                                                                       name="invoice_no"
                                                                                                                       class="form-control"
                                                                                                                       value="<?= $invoice_number ?>"
                                                                                                                       readonly>
                                                                                     </div>
                                                                                     <div class="form-group">
                                                                                                      <label>Date:</label>
                                                                                                      <input type="date"
                                                                                                                       name="invoice_date"
                                                                                                                       class="form-control"
                                                                                                                       value="<?= date('Y-m-d') ?>"
                                                                                                                       required>
                                                                                     </div>
                                                                    </div>
                                                   </div>

                                                   <!-- Transaction Details -->
                                                   <div class="row mb-4">
                                                                    <div class="col-md-4">
                                                                                     <div class="form-group">
                                                                                                      <label>Branch:</label>
                                                                                                      <select class="form-control"
                                                                                                                       name="branch_id"
                                                                                                                       required>
                                                                                                                       <?php while($row = $branches->fetch_assoc()): ?>
                                                                                                                       <option
                                                                                                                                        value="<?= $row['id'] ?>">
                                                                                                                                        <?= $row['branch_name'] ?>
                                                                                                                       </option>
                                                                                                                       <?php endwhile; ?>
                                                                                                      </select>
                                                                                     </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                                     <div class="form-group">
                                                                                                      <label>Cash
                                                                                                                       Account:</label>
                                                                                                      <select class="form-control"
                                                                                                                       name="cash_account"
                                                                                                                       required>
                                                                                                                       <?php while($row = $accounts->fetch_assoc()): ?>
                                                                                                                       <option
                                                                                                                                        value="<?= $row['account_code'] ?>">
                                                                                                                                        <?= $row['account_name'] ?>
                                                                                                                       </option>
                                                                                                                       <?php endwhile; ?>
                                                                                                      </select>
                                                                                     </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                                     <div class="form-group">
                                                                                                      <label>Reference:</label>
                                                                                                      <input type="text"
                                                                                                                       name="reference"
                                                                                                                       class="form-control">
                                                                                     </div>
                                                                    </div>
                                                   </div>

                                                   <!-- Items Table -->
                                                   <table class="table table-bordered" id="itemsTable">
                                                                    <thead>
                                                                                     <tr>
                                                                                                      <th>Item ID</th>
                                                                                                      <th>Description
                                                                                                      </th>
                                                                                                      <th>UOM</th>
                                                                                                      <th>Quantity</th>
                                                                                                      <th>Unit Cost</th>
                                                                                                      <th>Total Cost
                                                                                                      </th>
                                                                                                      <th>Action</th>
                                                                                     </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                                     <tr class="item-row">
                                                                                                      <td>
                                                                                                                       <select class="form-control item-select"
                                                                                                                                        name="item_id[]">
                                                                                                                                        <option
                                                                                                                                                         value="">
                                                                                                                                                         Select
                                                                                                                                                         Item
                                                                                                                                        </option>
                                                                                                                                        <?php while($row = $items->fetch_assoc()): ?>
                                                                                                                                        <option value="<?= $row['item_id'] ?>"
                                                                                                                                                         data-uom="<?= $row['uom'] ?>"
                                                                                                                                                         data-unitcost="<?= $row['unit_cost'] ?>">
                                                                                                                                                         <?= $row['item_name'] ?>
                                                                                                                                        </option>
                                                                                                                                        <?php endwhile; ?>
                                                                                                                       </select>
                                                                                                      </td>
                                                                                                      <td><input type="text" name="description[]"
                                                                                                                                        class="form-control">
                                                                                                      </td>
                                                                                                      <td><input type="text" name="uom[]"
                                                                                                                                        class="form-control uom"
                                                                                                                                        readonly>
                                                                                                      </td>
                                                                                                      <td><input type="number" name="quantity[]"
                                                                                                                                        class="form-control qty"
                                                                                                                                        step="0.01">
                                                                                                      </td>
                                                                                                      <td><input type="number" name="unit_cost[]"
                                                                                                                                        class="form-control unit-cost"
                                                                                                                                        step="0.01">
                                                                                                      </td>
                                                                                                      <td><input type="number" name="total_cost[]"
                                                                                                                                        class="form-control total-cost"
                                                                                                                                        readonly>
                                                                                                      </td>
                                                                                                      <td><button type="button"
                                                                                                                                        class="btn btn-danger remove-row">×</button>
                                                                                                      </td>
                                                                                     </tr>
                                                                    </tbody>
                                                   </table>
                                                   <button type="button" class="btn btn-primary" id="addRow">Add
                                                                    Item</button>