<?php $current_page = $_GET['page'] ?? 'home'; ?>
</main>
<footer class="bg-white shadow p-6 mt-15 text-center">
    <?php if (Auth::isLoggedIn()): ?>
        <nav class="space-x-4">
            <a href="index.php?page=home"
                class="<?= $current_page === 'home' ? 'underline text-gray-800' : 'text-gray-600' ?> hover:underline">
                Home
            </a>
            <a href="index.php?page=products"
                class="<?= $current_page === 'products' ? 'underline text-gray-800' : 'text-gray-600' ?> hover:underline">
                Products
            </a>
            <a href="index.php?page=cart"
                class="<?= $current_page === 'cart' ? 'underline text-gray-800' : 'text-gray-600' ?> hover:underline">
                Cart
            </a>
            <a href="index.php?page=profile"
                class="<?= $current_page === 'profile' ? 'underline text-gray-800' : 'text-gray-600' ?> hover:underline">
                Profile
            </a>
        </nav>
    <?php endif; ?>
    <p class="text-sm text-gray-400 <?= Auth::isLoggedIn() ? 'mt-6' : '' ?>">© 2025 Продовольчий магазин «Весна»</p>
</footer>
</body>

</html>