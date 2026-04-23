<?php
function logAudit($conn, $module, $record_id, $action, $changes = []) {

    if (!isset($_SESSION['user_id'])) return;

    $user_id   = $_SESSION['user_id'];
    $user_name = $_SESSION['username'];
    $ip        = $_SERVER['REMOTE_ADDR'];

    foreach ($changes as $field => $values) {

        $old = $values['old'] ?? null;
        $new = $values['new'] ?? null;

        if ($old == $new) continue; // ignore no change

        $stmt = $conn->prepare("
            INSERT INTO audit_trail 
            (user_id, user_name, module, record_id, action, field_name, old_value, new_value, ip_address)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ississsss",
            $user_id,
            $user_name,
            $module,
            $record_id,
            $action,
            $field,
            $old,
            $new,
            $ip
        );

        $stmt->execute();
    }
}
?>