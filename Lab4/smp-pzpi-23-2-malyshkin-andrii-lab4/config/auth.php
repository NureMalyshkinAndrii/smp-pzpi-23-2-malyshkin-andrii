<?php

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profile.php';

class Auth {
    private static $user = null;

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && 
               isset($_SESSION['username']) && 
               isset($_SESSION['login_time']);
    }

    public static function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public static function getCurrentUser() {
        if (self::$user === null && self::isLoggedIn()) {
            $user = new User();
            self::$user = $user->findById(self::getCurrentUserId());
        }
        return self::$user;
    }

    public static function login($username, $password) {
        $user = new User();

        if ($user->authenticate($username, $password)) {
            self::setSession($user);
            return true;
        }

        $existingUser = $user->findByUsername($username);
        if (!$existingUser) {
            $userId = $user->create($username, $password);
            if ($userId) {
                $newUser = $user->findById($userId);
                self::setSession($newUser);
                return true;
            }
        }

        return false;
    }

    public static function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['login_time']);
        
        session_destroy();
        
        header('Location: index.php?page=login');

        exit;
    }

    public static function getCurrentUserProfile() {
        if (!self::isLoggedIn()) {
            return null;
        }

        $profile = new Profile();
        $profileObj = $profile->findByUserId(self::getCurrentUserId());
        
        if (!$profileObj) {
            return null;
        }

        return [
            'id' => $profileObj->getUserId(),
            'username' => self::getCurrentUser()->getUsername(),
            'first_name' => $profileObj->getFirstName(),
            'last_name' => $profileObj->getLastName(),
            'birth_date' => $profileObj->getBirthDate(),
            'bio' => $profileObj->getBio(),
            'photo_path' => $profileObj->getPhotoPath(),
            'created_at' => self::getCurrentUser()->getCreatedAt()
        ];
    }

    private static function setSession($user) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['login_time'] = date('Y-m-d H:i:s');
        self::$user = $user;
    }
}