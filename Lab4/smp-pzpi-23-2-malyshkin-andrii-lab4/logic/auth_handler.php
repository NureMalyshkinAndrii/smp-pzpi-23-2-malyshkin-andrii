<?php

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Будь ласка, заповніть всі поля';
    } elseif (strlen($username) < 3) {
        $error = 'Ім\'я користувача має містити мінімум 3 символи';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль має містити мінімум 6 символів';
    } else {
        $user = new User();
        $existingUser = $user->findByUsername($username);
        $wasNewUser = !$existingUser;

        if (Auth::login($username, $password)) {
            if ($wasNewUser) {
                header('Location: index.php?page=products');
            } else {
                header('Location: index.php?page=products');
                exit;
            }
        } else {
            if ($existingUser) {
                $error = 'Невірний пароль';
            } else {
                $error = 'Помилка створення акаунту';
            }
        }
    }
}