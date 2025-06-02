<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <title>ПрМ «Весна»</title>
    <link rel="shortcut icon" href="images/image.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">
    <?php $current_page = $_GET['page'] ?? 'home'; ?>
    <?php
    $current_page = $_GET['page'] ?? 'home';
    $profile = Auth::getCurrentUserProfile();
    ?>
    <header class="bg-white shadow py-4 px-8 flex justify-between items-center relative">
        <a href="index.php?page=home" class="text-xl font-bold hover:underline">ПрМ «Весна»</a>
        <nav class="space-x-8 flex items-center relative">
            <?php if (Auth::isLoggedIn()): ?>
                <a href="index.php?page=home"
                    class="flex gap-2 h-full text-lg font-bold 
                    <?= $current_page === 'home'
                    ? 'border-b-2 border-green-800 text-green-800'
                    : 'border-b-2 border-white text-green-600 hover:border-green-600 hover:text-green-600' ?>
                    transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-house-icon lucide-house">
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                        <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    </svg>
                    Home
                </a>

                <a href="index.php?page=products"
                    class="flex gap-2 h-full text-lg font-bold 
                    <?= $current_page === 'products'
                    ? 'border-b-2 border-green-800 text-green-800'
                    : 'border-b-2 border-white text-green-600 hover:border-green-600 hover:text-green-600 ' ?>
                    transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-shopping-cart-icon lucide-shopping-cart">
                        <circle cx="8" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                    </svg>
                    Products
                </a>

                <a href="index.php?page=cart"
                    class="flex gap-2 h-full text-lg font-bold 
                    <?= $current_page === 'cart'
                    ? 'border-b-2 border-green-800 text-green-800'
                    : 'border-b-2 border-white text-green-600 hover:border-green-600 hover:text-green-600' ?>
                    transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-shopping-basket-icon lucide-shopping-basket">
                        <path d="m15 11-1 9" />
                        <path d="m19 11-4-7" />
                        <path d="M2 11h20" />
                        <path d="m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.7-7.4" />
                        <path d="M4.5 15.5h15" />
                        <path d="m5 11 4-7" />
                        <path d="m9 11 1 9" />
                    </svg>
                    Cart
                </a>

                <div class="relative ml-4">
                    <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                        <span class="text-gray-700 font-semibold"><?= htmlspecialchars($profile['username']) ?></span>
                        <?php if (!empty($profile['photo_path']) && file_exists($profile['photo_path'])): ?>
                            <img src="<?= htmlspecialchars($profile['photo_path']) ?>"
                                alt="Аватар"
                                class="w-10 h-10 object-cover rounded-full border border-gray-300">
                        <?php else: ?>
                            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold">
                                <?= strtoupper($profile['username'][0] ?? '?') ?>
                            </div>
                        <?php endif; ?>
                    </button>

                    <div id="userMenu" class="absolute right-0 mt-2 w-40 bg-white shadow-md rounded-xl border border-gray-200 hidden z-50">
                        <a href="index.php?page=profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Профіль</a>
                        <a href="index.php?page=logout" class="block px-4 py-2 text-red-600 hover:bg-red-100">Вийти</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="index.php?page=login"
                    class="<?= $current_page === 'login' ? 'underline text-green-800' : 'text-green-600' ?> text-lg font-bold hover:underline">
                    Login
                </a>
            <?php endif; ?>
        </nav>
    </header>

    <script>
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function() {
                if (!userMenu.classList.contains('hidden')) {
                    userMenu.classList.add('hidden');
                }
            });
        }
    </script>

    <main class="p-6 flex-grow">