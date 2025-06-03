<?php

require_once __DIR__ . '/Database.php';

class Profile {
    private $db;
    private $userId;
    private $firstName;
    private $lastName;
    private $birthDate;
    private $bio;
    private $photoPath;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByUserId($userId) {
        $stmt = $this->db->prepare('
            SELECT u.*, p.first_name, p.last_name, p.birth_date, p.bio, p.photo_path
            FROM users u
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE u.id = ?
        ');
        $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $profileData = $result->fetchArray(SQLITE3_ASSOC);

        if ($profileData) {
            $this->loadProfileData($profileData);
            return $this;
        }

        return null;
    }

    public function update($userId, $firstName, $lastName, $birthDate, $bio, $photoPath = null) {
        $stmt = $this->db->prepare('SELECT user_id FROM profiles WHERE user_id = ?');
        $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $exists = $result->fetchArray(SQLITE3_ASSOC);

        if ($exists) {
            return $this->updateExisting($userId, $firstName, $lastName, $birthDate, $bio, $photoPath);
        } else {
            return $this->createNew($userId, $firstName, $lastName, $birthDate, $bio, $photoPath);
        }
    }

    private function updateExisting($userId, $firstName, $lastName, $birthDate, $bio, $photoPath) {
        if ($photoPath) {
            $stmt = $this->db->prepare('
                UPDATE profiles 
                SET first_name = ?, last_name = ?, birth_date = ?, bio = ?, photo_path = ?
                WHERE user_id = ?
            ');
            $stmt->bindValue(5, $photoPath, SQLITE3_TEXT);
            $stmt->bindValue(6, $userId, SQLITE3_INTEGER);
        } else {
            $stmt = $this->db->prepare('
                UPDATE profiles 
                SET first_name = ?, last_name = ?, birth_date = ?, bio = ?
                WHERE user_id = ?
            ');
            $stmt->bindValue(5, $userId, SQLITE3_INTEGER);
        }

        $stmt->bindValue(1, $firstName, SQLITE3_TEXT);
        $stmt->bindValue(2, $lastName, SQLITE3_TEXT);
        $stmt->bindValue(3, $birthDate, SQLITE3_TEXT);
        $stmt->bindValue(4, $bio, SQLITE3_TEXT);

        return $stmt->execute();
    }

    private function createNew($userId, $firstName, $lastName, $birthDate, $bio, $photoPath) {
        if ($photoPath) {
            $stmt = $this->db->prepare('
                INSERT INTO profiles (user_id, first_name, last_name, birth_date, bio, photo_path)
                VALUES (?, ?, ?, ?, ?, ?)
            ');
            $stmt->bindValue(6, $photoPath, SQLITE3_TEXT);
        } else {
            $stmt = $this->db->prepare('
                INSERT INTO profiles (user_id, first_name, last_name, birth_date, bio)
                VALUES (?, ?, ?, ?, ?)
            ');
        }

        $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $firstName, SQLITE3_TEXT);
        $stmt->bindValue(3, $lastName, SQLITE3_TEXT);
        $stmt->bindValue(4, $birthDate, SQLITE3_TEXT);
        $stmt->bindValue(5, $bio, SQLITE3_TEXT);

        return $stmt->execute();
    }

    private function loadProfileData($profileData) {
        $this->userId = $profileData['id'];
        $this->firstName = $profileData['first_name'];
        $this->lastName = $profileData['last_name'];
        $this->birthDate = $profileData['birth_date'];
        $this->bio = $profileData['bio'];
        $this->photoPath = $profileData['photo_path'];
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getFullName() {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    public function getBirthDate() {
        return $this->birthDate;
    }

    public function getBio() {
        return $this->bio;
    }

    public function getPhotoPath() {
        return $this->photoPath;
    }
}