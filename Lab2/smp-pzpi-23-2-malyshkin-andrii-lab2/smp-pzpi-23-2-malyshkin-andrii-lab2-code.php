#!/usr/bin/env php
<?php

declare(strict_types=1);

class DB
{
    private SQLite3 $db;

    public function __construct(string $file)
    {
        $this->db = new SQLite3($file);

        $this->db->exec("CREATE TABLE IF NOT EXISTS cart (
            product_id INTEGER PRIMARY KEY,
            quantity INTEGER
        )");

        $this->db->exec("CREATE TABLE IF NOT EXISTS profile (
            id INTEGER PRIMARY KEY,
            name TEXT,
            age INTEGER
        )");
    }

    public function getCart(): array
    {
        $res = $this->db->query("SELECT product_id, quantity FROM cart");
        $cart = [];
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $cart[$row['product_id']] = $row['quantity'];
        }
        return $cart;
    }

    public function updateCart(int $pid, int $qty): void
    {
        if ($qty > 0) {
            $this->db->exec("REPLACE INTO cart 
                (product_id, quantity) VALUES ($pid, $qty)"
            );
        } else {
            $this->db->exec("DELETE FROM cart 
                WHERE product_id = $pid"
            );
        }
    }

    public function setProfile(string $name, int $age): void
    {
        $this->db->exec("DELETE FROM profile");
        $stmt = $this->db->prepare("INSERT INTO profile 
            (name, age) VALUES (:name, :age)"
        );
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
        $stmt->execute();
    }
}

class App
{
    private DB $db;
    private array  $products;
    private string $productsMenu;
    private string $mainMenu;
    private string $dbFile = __DIR__ . '/smp-pzpi-23-2-malyshkin-andrii-lab2.db';

    public function __construct()
    {
        $this->db = new DB($this->dbFile);
        $this->products = [
            1 => ['name' => 'Молоко пастеризоване', 'price' => 12],
            2 => ['name' => 'Хліб чорний',          'price' =>  9],
            3 => ['name' => 'Сир білий',            'price' => 21],
            4 => ['name' => 'Сметана 20%',          'price' => 25],
            5 => ['name' => 'Кефір 1%',             'price' => 19],
            6 => ['name' => 'Вода газована',        'price' => 18],
            7 => ['name' => 'Печиво "Весна"',       'price' => 14],
        ];
        $this->buildProductsMenu();
        $this->mainMenu = "\n"
            . "1 Вибрати товари\n"
            . "2 Отримати підсумковий рахунок\n"
            . "3 Налаштувати свій профіль\n"
            . "0 Вийти з програми\n"
            . "Введіть команду: ";
    }

    public function run(): void
    {
        while (true) {
            $this->menu();
            $cmd = (int) $this->rltrim($this->mainMenu);
            match ($cmd) {
                1 => $this->shop(),
                2 => $this->checkout(),
                3 => $this->profile(),
                0 => exit,
                default => print("ПОМИЛКА! Введіть правильну команду" . PHP_EOL),
            };
        }
    }

    private function pad(string $str, int $len): string
    {
        return $str . str_repeat(' ', max(0, $len - mb_strlen($str)));
    }

    private function buildProductsMenu(): void
    {
        $padName = 0;
        $count = count($this->products);
        foreach ($this->products as $p) {
            $padName = max($padName, mb_strlen($p['name']));
        }
        $padName += 2;
        $padNum = strlen((string)$count) + 2;

        $menu = PHP_EOL;
        $menu .= $this->pad('№', $padNum) . 
                 $this->pad('НАЗВА', $padName) . 'ЦІНА' . PHP_EOL;

        foreach ($this->products as $i => $p) {
            $menu .= $this->pad((string)$i, $padNum) . 
                     $this->pad($p['name'], $padName) . $p['price'] . PHP_EOL;
        }
        $menu .= $this->pad('',  $padNum) . '-----------' . PHP_EOL;
        $menu .= $this->pad('0', $padNum) . 'ПОВЕРНУТИСЯ' . PHP_EOL;

        $this->productsMenu = $menu;
    }

    private function rltrim(string $prompt): string
    {
        return trim(readline($prompt));
    }

    private function menu(): void
    {
        echo "\n"
           . "################################\n"
           . "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n"
           . "################################\n";
    }

    private function shop(): void
    {
        while (true) {
            echo $this->productsMenu;
            $pid = (int) $this->rltrim('Виберіть товар: ');
            if ($pid === 0) break;
            if (!isset($this->products[$pid])) {
                echo 'ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ' . PHP_EOL;
                continue;
            }

            echo PHP_EOL . 'Вибрано: ' . $this->products[$pid]['name'] . PHP_EOL;
            $qty = (int) $this->rltrim('Введіть кількість, штук: ');
            if ($qty < 0 || $qty > 100) {
                echo 'ПОМИЛКА! Кількість товару має бути від 1 до 100' . PHP_EOL;
                continue;
            }

            if ($qty === 0) print('ВИДАЛЯЮ З КОШИКА' . PHP_EOL);
            $this->db->updateCart($pid, $qty);
            $cart = $this->db->getCart();
            if (empty($cart)) {
                echo 'КОШИК ПОРОЖНІЙ' . PHP_EOL;
                continue;
            }

            $padName = 0;
            foreach ($cart as $id => $q) {
                $padName = max($padName, mb_strlen($this->products[$id]['name']));
            }
            $padName += 2;

            echo PHP_EOL . $this->pad('НАЗВА', $padName) . '   КІЛЬКІСТЬ' . PHP_EOL;
            foreach ($cart as $id => $q) {
                echo $this->pad($this->products[$id]['name'], $padName) . '   ' . $q . PHP_EOL;
            }
        }
    }

    private function checkout(): void
    {
        $cart = $this->db->getCart();
        if (empty($cart)) {
            echo PHP_EOL . 'КОШИК ПОРОЖНІЙ' . PHP_EOL;
            return;
        }

        $padName = 0;
        $count = count($cart);
        foreach ($cart as $id => $q) {
            $padName = max($padName, mb_strlen($this->products[$id]['name']));
        }
        
        $padName += 2;
        $padNum = strlen((string)$count) + 2;
        echo PHP_EOL . $this->pad('№', $padNum) . 
                       $this->pad('НАЗВА', $padName) . 'ЦІНА  КІЛЬКІСТЬ  ВАРТІСТЬ' . PHP_EOL;
        $i = 0; $total = 0;

        foreach ($cart as $id => $q) {
            $p = $this->products[$id];
            $cost = $p['price'] * $q;
            echo $this->pad((string)++$i, $padNum)
               . $this->pad($p['name'], $padName)
               . $this->pad((string)$p['price'], 6)
               . $this->pad((string)$q, 11)
               . $cost . PHP_EOL;
            $total += $cost;
        }
        echo 'РАЗОМ ДО СПЛАТИ: ' . $total . PHP_EOL;
    }

    private function profile(): void
    {
        do {
            $name = $this->rltrim("\nВаше ім'я: ");
        } while (
            !preg_match('/[\p{L}]/u', $name) && 
            print('ПОМИЛКА! Імʼя має містити хоча б одну літеру' . PHP_EOL)
        );
        do {
            $age = (int) $this->rltrim('Ваш вік: ');
        } while (
            ($age < 7 || $age > 150) && 
            print('ПОМИЛКА! Вік має бути від 7 до 150' . PHP_EOL)
        );
        $this->db->setProfile($name, $age);
    }
}

$app = new App();
$app->run();