<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Quotation Add</h4>
                <h6>Add/Update Quotation</h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Customer Name -->
                    <div class="col-lg-1 col-sm-2 col-6">
                        <div class="form-group">
                            <label>Customer Name</label>
                            <div class="row">
                                <div class="col-lg-10 col-sm-10 col-10">
                                    <select class="select">
                                        <option>Select Customer</option>
                                        <option>Customer</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-sm-2 col-2 ps-0">
                                    <div class="add-icon">
                                        <a href="javascript:void(0);"><img src="assets/img/icons/plus1.svg" alt="img"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Quotation Date -->
                    <div class="col-lg-1 col-sm-2 col-6">
                        <div class="form-group">
                            <label>Quotation Date </label>
                            <div class="input-groupicon">
                                <input type="text" placeholder="DD-MM-YYYY" class="datetimepicker">
                                <div class="addonset">
                                    <img src="assets/img/icons/calendars.svg" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Reference No. -->
                    <div class="col-lg-1 col-sm-2 col-6">
                        <div class="form-group">
                            <label>Reference No.</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <!-- Product Name -->
                    <div class="col-lg-1 col-sm-2 col-6">
                        <div class="form-group">
                            <label>Product Name</label>
                            <div class="input-groupicon">
                                <input type="text" placeholder="Scan/Search Product by code and select..." class="form-control">
                                <div class="addonset">
                                    <img src="assets/img/icons/scanners.svg" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table for Products -->
                <div class="row">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Net Unit Price($)</th>
                                    <th>Stock</th>
                                    <th>Qty</th>
                                    <th>Discount($)</th>
                                    <th>Tax %</th>
                                    <th class="text-end">Subtotal ($)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="productimgname">
                                        <a class="product-img">
                                            <img src="assets/img/product/product7.jpg" alt="product">
                                        </a>
                                        <a href="javascript:void(0);">Apple Earpods</a>
                                    </td>
                                    <td>150</td>
                                    <td>500</td>
                                    <td>500</td>
                                    <td>100</td>
                                    <td>250</td>
                                    <td class="text-end">500</td>
                                    <td>
                                        <a href="javascript:void(0);" class="delete-set"><img src="assets/img/icons/delete.svg" alt="svg"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="productimgname">
                                        <a class="product-img">
                                            <img src="assets/img/product/product6.jpg" alt="product">
                                        </a>
                                        <a href="javascript:void(0);">Macbook Pro</a>
                                    </td>
                                    <td>15.00</td>
                                    <td>6000.00</td>
                                    <td>100.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td class="text-end">1000.00</td>
                                    <td>
                                        <a href="javascript:void(0);" class="delete-set"><img src="assets/img/icons/delete.svg" alt="svg"></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Total and Order Summary -->
                <div class="row">
                    <div class="col-lg-12 float-md-right">
                        <div class="total-order">
                            <ul>
                                <li>
                                    <h4>Order Tax</h4>
                                    <h5>$ 0.00 (0.00%)</h5>
                                </li>
                                <li>
                                    <h4>Discount</h4>
                                    <h5>$ 0.00</h5>
                                </li>
                                <li>
                                    <h4>Shipping</h4>
                                    <h5>$ 0.00</h5>
                                </li>
                                <li class="total">
                                    <h4>Grand Total</h4>
                                    <h5>$ 0.00</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Order Tax</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Discount</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Shipping</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="select form-control">
                                <option>Choose Status</option>
                                <option>Completed</option>
                                <option>Inprogress</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <a href="javascript:void(0);" class="btn btn-submit me-2">Submit</a>
                        <a href="quotationList.html" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Buttons for Export and Print -->
<div class="actions">
    <button onclick="window.print();" class="btn btn-primary">Print</button>
    <button onclick="exportToExcel();" class="btn btn-success">Export to Excel</button>
    <button onclick="exportToWord();" class="btn btn-warning">Export to Word</button>
    <button onclick="exportToPDF();" class="btn btn-danger">Export to PDF</button>
</div>

<!-- Script for export -->
<script>
function exportToExcel() {
    // Add logic for exporting to Excel
}

function exportToWord() {
    // Add logic for exporting to Word
}

function exportToPDF() {
    // Add logic for exporting to PDF using jsPDF library
}
</script>

<style>
/* Customizing width for a cleaner view */
.col-lg-1, .col-sm-2, .col-6 {
    width: 8% !important; /* Reduced width */
    padding-left: 5px;
    padding-right: 5px;
}

.table {
    width: 100%;
}

.table th, .table td {
    padding: 8px;
    text-align: center;
}

.page-wrapper {
    padding: 15px;
    max-width: 80%; /* Reduce overall width */
}

.card-body {
    padding: 15px;
}

.btn {
    font-size: 12px;
    padding: 8px 16px;
}

.actions button {
    margin-right: 5px;
    margin-top: 15px;
}

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header, .footer {
            background-color: #343a40;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }

        .header a, .footer a {
            color: #ffc107;
            text-decoration: none;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-submit {
            background-color: #28a745;
            color: #fff;
        }

        .btn-cancel {
            background-color: #dc3545;
            color: #fff;
        }

        table {
            margin-top: 20px;
        }

        textarea {
            resize: none;
        }

        /* Style for responsive tables */
        .table-responsive {
            overflow-x: auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        /* Style for table row inputs */
        .form-control, .form-select {
            width: 100%;
        }

        /* Styling for delete button */
        .btn-danger {
            margin-top: 5px;
        }
  
</style>
