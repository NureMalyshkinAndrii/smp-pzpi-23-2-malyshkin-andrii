<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>ПрМ «Весна»</title>
    <link rel="shortcut icon" href="images/image.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">
<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<header class="bg-white shadow py-4 px-8 flex justify-between items-center">
    <a href="home.php" class="text-xl font-bold hover:underline">ПрМ «Весна»</a>
    <nav class="space-x-6">
        <a href="home.php"
           class="<?= $current_page === 'home.php' ? 'underline text-green-800' : 'text-green-600' ?> text-lg font-bold hover:underline">
            Home
        </a>
        <a href="products.php"
           class="<?= $current_page === 'products.php' ? 'underline text-green-800' : 'text-green-600' ?> text-lg font-bold hover:underline">
            Products
        </a>
        <a href="cart.php"
           class="<?= $current_page === 'cart.php' ? 'underline text-green-800' : 'text-green-600' ?> text-lg font-bold hover:underline">
            Cart
        </a>
    </nav>
</header>

<main class="p-6 flex-grow">