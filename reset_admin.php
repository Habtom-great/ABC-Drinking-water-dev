<?php
require_once "db.php";

$newPass = password_hash("123456", PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    UPDATE users 
    SET password = ? 
    WHERE email = 'admin@erp.com'
");

$stmt->bind_param("s", $newPass);

if ($stmt->execute()) {
    echo "Admin password reset successfully to 123456";
} else {
    echo "Error: " . $conn->error;
}
?>