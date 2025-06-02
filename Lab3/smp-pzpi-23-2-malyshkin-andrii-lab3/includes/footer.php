<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
</main>
<footer class="bg-white shadow p-6 mt-15 text-center">
    <nav class="space-x-4">
        <a href="home.php"
           class="<?= $current_page === 'home.php' ? 'underline text-gray-800' : 'text-gray-600' ?> hover:underline">
            Home
        </a>
        <a href="products.php"
           class="<?= $current_page === 'products.php' ? 'underline text-gray-800' : 'text-gray-600' ?> hover:underline">
            Products
        </a>
        <a href="cart.php"
           class="<?= $current_page === 'cart.php' ? 'underline text-gray-800' : 'text-gray-600' ?> hover:underline">
            Cart
        </a>
    </nav>
    <p class="text-sm text-gray-400 mt-6">© 2025 Продовольчий магазин «Весна»</p>
</footer>
</body>
</html>