<?php
echo 'Current script dir: ' . __DIR__ . "<br>";
echo 'Uploads folder writable? ';
var_dump(is_writable(__DIR__ . '/uploads'));
?>
