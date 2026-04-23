<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bankAccount = $_POST['bank_account'];

    // Dummy ledger balances
    $balances = [
        'bank1' => 5000,
        'bank2' => 10000,
        'bank3' => 15000,
    ];

    echo json_encode([
        'ledger_balance' => $balances[$bankAccount] ?? 'Not Found',
    ]);
}
?>
