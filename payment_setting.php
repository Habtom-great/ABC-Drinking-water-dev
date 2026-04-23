<?php
// Include database connection
include('db_connect.php');{
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_payment'])) {
    $name = $_POST['payment_name'];
    $status = $_POST['status'];
    $query = "INSERT INTO payment_settings (name, status) VALUES ('$name', '$status')";
    $conn->query($query);
    header("Location: payment_settings.php");
    exit;
}

// Handle Edit Payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_payment'])) {
    $id = $_POST['id'];
    $name = $_POST['payment_name'];
    $status = $_POST['status'];
    $query = "UPDATE payment_settings SET name = '$name', status = '$status' WHERE id = $id";
    $conn->query($query);
    header("Location: payment_settings.php");
    exit;
}

// Handle Delete Payment
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM payment_settings WHERE id = $id";
    $conn->query($query);
    header("Location: payment_settings.php");
    exit;
}

// Fetch Payment Types
$query = "SELECT * FROM payment_settings";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Settings</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
</head>
<body>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Payment Settings</h4>
                <h6>Manage Payment Settings</h6>
            </div>
            <div class="page-btn">
                <a class="btn btn-added" data-bs-toggle="modal" data-bs-target="#addpayment"><img src="assets/img/icons/plus.svg" alt="img" class="me-2">Add Payment Settings</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                        <tr>
                            <th>Payment Type Name</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name']); ?></td>
                                <td>
                                    <div class="status-toggle d-flex justify-content-between align-items-center">
                                        <input type="checkbox" id="user<?= $row['id']; ?>" class="check" <?= $row['status'] === 'Active' ? 'checked' : ''; ?>>
                                        <label for="user<?= $row['id']; ?>" class="checktoggle">checkbox</label>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a class="me-3" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editpayment" onclick="editPayment(<?= $row['id']; ?>, '<?= htmlspecialchars($row['name']); ?>', '<?= $row['status']; ?>')">
                                        <img src="assets/img/icons/edit.svg" alt="img">
                                    </a>
                                    <a class="me-3 confirm-text" href="?delete_id=<?= $row['id']; ?>">
                                        <img src="assets/img/icons/delete.svg" alt="img">
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addpayment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment Type</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Payment Name</label>
                        <input type="text" name="payment_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_payment" class="btn btn-submit">Add</button>
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Payment Modal -->
<div class="modal fade" id="editpayment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Payment Type</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label>Payment Name</label>
                        <input type="text" name="payment_name" id="edit-name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="edit-status" class="form-control" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_payment" class="btn btn-submit">Update</button>
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function editPayment(id, name, status) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-status').value = status;
}
</script>
</body>
</html>
