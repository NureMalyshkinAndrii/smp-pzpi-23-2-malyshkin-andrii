<?php
session_start();

require_once 'models/Database.php';
require_once 'models/Cart.php';
require_once 'models/Product.php';
require_once 'models/Profile.php';
require_once 'models/User.php';

require_once 'config/auth.php';

Database::getInstance()->initializeDatabase();

$page = $_GET['page'] ?? 'home';

if (!Auth::isLoggedIn() && $page !== 'login') {
    $page = '404';
}

if ($page === 'logout') {
    Auth::logout();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    match ($page) {
        'login'   => require_once 'logic/auth_handler.php',
        'products'=> require_once 'logic/product_handler.php',
        'cart'    => require_once 'logic/cart_handler.php',
        'profile' => require_once 'logic/profile_handler.php',
        default   => null, 
    };
}

include 'includes/header.php';

match ($page) {
    'home'     => require_once 'pages/home.php',
    'products' => require_once 'pages/products.php',
    'cart'     => require_once 'pages/cart.php',
    'profile'  => require_once 'pages/profile.php',
    'login'    => require_once 'pages/login.php',
    '404'      => require_once 'pages/page404.php',
    default    => require_once 'pages/page404.php',
};

include 'includes/footer.php';
?>