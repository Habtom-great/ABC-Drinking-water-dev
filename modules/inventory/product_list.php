<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <label class="checkbox-container">
                    <input type="checkbox">
                    <span class="checkmark"></span>
                </label>
            </th>
            <th>Product</th>
            <th>Product Code</th>
            <th>Category</th>
            <th>Status</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Price</th>
            <th>Added By</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <label class="checkbox-container">
                    <input type="checkbox">
                    <span class="checkmark"></span>
                </label>
            </td>
            <td class="product-img-name">
                <a href="javascript:void(0);" class="product-img">
                    <img src="assets/img/product/product5.jpg" alt="Avocat">
                </a>
                <a href="javascript:void(0);" class="product-name">Avocat</a>
            </td>
            <td>PT005</td>
            <td>Accessories</td>
            <td>In Stock</td>
            <td>10.00</td>
            <td>pc</td>
            <td>$150.00</td>
            <td>Admin</td>
            <td>
                <a class="action-icon" href="product-details.html" title="View Details">
                    <img src="assets/img/icons/eye.svg" alt="View">
                </a>
                <a class="action-icon" href="editproduct.html" title="Edit Product">
                    <img src="assets/img/icons/edit.svg" alt="Edit">
                </a>
                <a class="action-icon confirm-text" href="javascript:void(0);" title="Delete Product">
                    <img src="assets/img/icons/delete.svg" alt="Delete">
                </a>
            </td>
        </tr>
        <!-- Repeat similar rows for other products -->
    </tbody>
</table>

<style>
    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-family: 'Arial', sans-serif;
        background-color: #f9f9f9;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .table th {
        background-color: #007bff;
        color: #fff;
        text-align: left;
        padding: 12px;
        font-weight: bold;
        border-bottom: 2px solid #ddd;
    }

    .table td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    .product-img-name {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .product-img img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .product-name {
        font-weight: 500;
        color: #007bff;
        text-decoration: none;
    }

    .product-name:hover {
        text-decoration: underline;
    }

    .action-icon {
        margin-right: 10px;
        display: inline-block;
    }

    .action-icon img {
        width: 20px;
        height: 20px;
        transition: transform 0.2s;
    }

    .action-icon:hover img {
        transform: scale(1.1);
    }

    .checkbox-container {
        display: inline-block;
        position: relative;
        cursor: pointer;
    }

    .checkbox-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkmark {
        position: relative;
        height: 20px;
        width: 20px;
        background-color: #eee;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .checkbox-container input:checked ~ .checkmark {
        background-color: #007bff;
    }

    .checkbox-container input:checked ~ .checkmark:after {
        content: '';
        position: absolute;
        left: 6px;
        top: 2px;
        width: 6px;
        height: 12px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
</style>
     