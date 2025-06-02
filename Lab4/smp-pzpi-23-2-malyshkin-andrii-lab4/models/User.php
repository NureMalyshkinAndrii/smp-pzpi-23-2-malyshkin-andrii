<?php

require_once __DIR__ . '/Database.php';

class User {
    private $db;
    private $id;
    private $username;
    private $password;
    private $createdAt;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function authenticate($username, $password) {
        $stmt = $this->db->prepare('SELECT id, username, password FROM users WHERE username = ?');
        $stmt->bindValue(1, $username, SQLITE3_TEXT);
        $result = $stmt->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $this->loadUserData($user);
            return true;
        }

        return false;
    }

    public function create($username, $password) {
        if ($this->findByUsername($username)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare('INSERT INTO users (username, password, created_at) VALUES (?, ?, ?)');
        $stmt->bindValue(1, $username, SQLITE3_TEXT);
        $stmt->bindValue(2, $hashedPassword, SQLITE3_TEXT);
        $stmt->bindValue(3, date('Y-m-d H:i:s'), SQLITE3_TEXT);
        
        if ($stmt->execute()) {
            $userId = $this->db->lastInsertRowID();
            
            $profileStmt = $this->db->prepare('INSERT INTO profiles (user_id) VALUES (?)');
            $profileStmt->bindValue(1, $userId, SQLITE3_INTEGER);
            $profileStmt->execute();
            
            return $userId;
        }

        return false;
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->bindValue(1, $username, SQLITE3_TEXT);
        $result = $stmt->execute();
        $userData = $result->fetchArray(SQLITE3_ASSOC);

        if ($userData) {
            $this->loadUserData($userData);
            return $this;
        }

        return null;
    }

    public function findById($id) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bindValue(1, $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $userData = $result->fetchArray(SQLITE3_ASSOC);

        if ($userData) {
            $this->loadUserData($userData);
            return $this;
        }

        return null;
    }

    private function loadUserData($userData) {
        $this->id = $userData['id'];
        $this->username = $userData['username'];
        $this->password = $userData['password'];
        $this->createdAt = $userData['created_at'];
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }
}