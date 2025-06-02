<?php

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantities = $_POST['quantities'] ?? [];
    $userId = Auth::getCurrentUserId();
    $valid = false;

    $cart = new Cart($userId);

    foreach ($quantities as $productId => $count) {
        if (is_numeric($count) && (int)$count > 0) {
            $valid = true;
            $productId = (int)$productId;
            $count = (int)$count;
            $cart->addItem($productId, $count);
        }
    }

    if ($valid) {
        header("Location: index.php?page=cart");
        exit;
    } else {
        $error = 'Перевірте будь ласка введені дані';
    }
}