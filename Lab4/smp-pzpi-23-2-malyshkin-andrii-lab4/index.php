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
    switch ($page) {
        case 'login':
            require_once 'logic/auth_handler.php';
            break;
        case 'products':
            require_once 'logic/product_handler.php';
            break;
        case 'cart':
            require_once 'logic/cart_handler.php';
            break;
        case 'profile':
            require_once 'logic/profile_handler.php';
            break;
    }
}

include 'includes/header.php';

switch ($page) {
    case 'home':
        require_once 'pages/home.php';
        break;
    case 'products':
        require_once 'pages/products.php';
        break;
    case 'cart':
        require_once 'pages/cart.php';
        break;
    case 'profile':
        require_once 'pages/profile.php';
        break;
    case 'login':
        require_once 'pages/login.php';
        break;
    case 'logout':
        require_once 'logic/auth_handler.php';
        break;
    case '404':
    default:
        require_once 'pages/page404.php';
        break;
}

include 'includes/footer.php';
?>