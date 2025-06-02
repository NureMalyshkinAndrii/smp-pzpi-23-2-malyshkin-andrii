<?php

require_once __DIR__ . '/Database.php';

class Product {
    private $db;
    private $id;
    private $name;
    private $price;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public static function getAll() {
        $instance = new self();
        $result = $instance->db->query('SELECT * FROM products ORDER BY id');

        $products = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $products[] = $row; 
        }

        return $products;
    }
}