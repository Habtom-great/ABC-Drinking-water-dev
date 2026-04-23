<?php
include 'db.php';

if ($conn) {
    echo "✅ ERP DATABASE CONNECTED SUCCESSFULLY";
} else {
    echo "❌ Connection failed";
}
?>