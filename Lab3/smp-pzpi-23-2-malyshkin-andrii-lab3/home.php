<?php
session_start();
include 'includes/header.php';
?>

<h1 class="text-2xl font-bold text-center mt-40">Ласкаво просимо до продовольчого магазину «Весна»</h1>
<p class="text-center mt-4 text-gray-600">
    Оберіть 
    <a href="products.php" class="text-green-600 underline">товари</a> 
    для покупки або перегляньте 
    <a href="cart.php" class="text-green-600 underline">кошик</a>.
</p>

<?php include 'includes/footer.php'; ?>