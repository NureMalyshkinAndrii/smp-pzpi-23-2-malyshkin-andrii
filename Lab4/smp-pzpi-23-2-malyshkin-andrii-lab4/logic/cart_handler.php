<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
    $removeId = (int)$_POST['remove'];
    $userId = Auth::getCurrentUserId();
    
    $cart = new Cart($userId);
    $cart->removeItem($removeId);
    
    header('Location: index.php?page=cart');
    exit;
}