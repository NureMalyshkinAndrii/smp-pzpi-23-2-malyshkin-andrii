<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Product.php';

class Cart {
    private $db;
    private $userId;

    public function __construct($userId) {
        $this->db = Database::getInstance()->getConnection();
        $this->userId = $userId;
    }

    public function getItems() {
        $stmt = $this->db->prepare('
            SELECT ci.*, p.name, p.price 
            FROM cart_items ci 
            JOIN products p ON ci.product_id = p.id 
            WHERE ci.user_id = ?
            ORDER BY ci.added_at DESC
        ');
        $stmt->bindValue(1, $this->userId, SQLITE3_INTEGER);
        $result = $stmt->execute();

        $items = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $items[] = [
                'id' => $row['id'],
                'product_id' => $row['product_id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => $row['quantity'],
                'total' => $row['price'] * $row['quantity'],
                'added_at' => $row['added_at']
            ];
        }

        return $items;
    }

    public function addItem($productId, $quantity = 1) {
        $stmt = $this->db->prepare('SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ?');
        $stmt->bindValue(1, $this->userId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $productId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $existing = $result->fetchArray(SQLITE3_ASSOC);

        if ($existing) {
            $newQuantity = $existing['quantity'] + $quantity;
            $stmt = $this->db->prepare('UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?');
            $stmt->bindValue(1, $newQuantity, SQLITE3_INTEGER);
            $stmt->bindValue(2, $this->userId, SQLITE3_INTEGER);
            $stmt->bindValue(3, $productId, SQLITE3_INTEGER);
        } else {
            $stmt = $this->db->prepare('INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)');
            $stmt->bindValue(1, $this->userId, SQLITE3_INTEGER);
            $stmt->bindValue(2, $productId, SQLITE3_INTEGER);
            $stmt->bindValue(3, $quantity, SQLITE3_INTEGER);
        }

        return $stmt->execute();
    }

    public function removeItem($productId) {
        $stmt = $this->db->prepare('DELETE FROM cart_items WHERE user_id = ? AND product_id = ?');
        $stmt->bindValue(1, $this->userId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $productId, SQLITE3_INTEGER);

        return $stmt->execute();
    }
}