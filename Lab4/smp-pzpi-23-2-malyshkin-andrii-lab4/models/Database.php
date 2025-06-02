<?php

class Database {
    private static $instance = null;
    private $connection;
    private $dbPath;

    private function __construct() {
        $this->dbPath = __DIR__ . '/../database/shop.sqlite';
        $this->connect();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect() {
        try {
            $dbDir = dirname($this->dbPath);
            if (!is_dir($dbDir)) {
                mkdir($dbDir, 0755, true);
            }

            $this->connection = new SQLite3($this->dbPath);
            $this->connection->enableExceptions(true);
        } catch (Exception $e) {
            die('Помилка підключення до бази даних: ' . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function initializeDatabase() {
        $this->createTables();
        $this->seedProducts();
    }

    private function createTables() {
        $queries = [
            'CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )',

            'CREATE TABLE IF NOT EXISTS profiles (
                user_id INTEGER PRIMARY KEY,
                first_name TEXT,
                last_name TEXT,
                birth_date DATE,
                bio TEXT,
                photo_path TEXT,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )',

            'CREATE TABLE IF NOT EXISTS products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                price DECIMAL(10,2) NOT NULL
            )',
            
            'CREATE TABLE IF NOT EXISTS cart_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                product_id INTEGER NOT NULL,
                quantity INTEGER NOT NULL DEFAULT 1,
                added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                UNIQUE(user_id, product_id)
            )'
        ];

        foreach ($queries as $query) {
            $this->connection->exec($query);
        }
    }

    private function seedProducts() {
        $checkProducts = $this->connection->querySingle('SELECT COUNT(*) FROM products');
        if ($checkProducts == 0) {
            $products = [
                ['name' => 'Молоко пастеризоване', 'price' => 12],
                ['name' => 'Хліб чорний', 'price' => 9],
                ['name' => 'Сир білий', 'price' => 21],
                ['name' => 'Сметана 20%', 'price' => 25],
                ['name' => 'Кефір 1%', 'price' => 19],
                ['name' => 'Вода газована', 'price' => 18],
                ['name' => 'Печиво "Весна"', 'price' => 14]
            ];

            $stmt = $this->connection->prepare('INSERT INTO products (name, price) VALUES (?, ?)');
            foreach ($products as $product) {
                $stmt->bindValue(1, $product['name'], SQLITE3_TEXT);
                $stmt->bindValue(2, $product['price'], SQLITE3_FLOAT);
                $stmt->execute();
            }
        }
    }

    public function __destruct() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}