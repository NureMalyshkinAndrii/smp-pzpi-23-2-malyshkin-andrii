<?php
session_start();
include 'includes/header.php';

$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
    $removeId = (int)$_POST['remove'];
    if (isset($cart[$removeId])) {
        unset($cart[$removeId]);
        $_SESSION['cart'] = $cart;
        header('Location: cart.php');
        exit;
    }
}
?>

<h1 class="text-2xl font-bold mb-12 text-center">Кошик</h1>

<?php if (empty($cart)): ?>
    <p class="text-center text-lg">Ваш кошик порожній. 
        <a href="products.php" class="text-green-600 underline">
            Перейдіть до покупок
        </a>
    </p>
<?php else: ?>
    <div class="overflow-x-auto px-80">
        <table class="min-w-full bg-white shadow rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Назва</th>
                    <th class="px-4 py-2 text-left">Ціна</th>
                    <th class="px-4 py-2 text-left">Кількість</th>
                    <th class="px-4 py-2 text-left">Сума</th>
                    <th class="px-4 py-2 text-left">Дії</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($cart as $item):
                    $sum = $item['price'] * $item['count'];
                    $total += $sum;
                ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?= $item['id'] ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($item['name']) ?></td>
                        <td class="px-4 py-2"><?= $item['price'] ?> грн</td>
                        <td class="px-4 py-2"><?= $item['count'] ?></td>
                        <td class="px-4 py-2"><?= $sum ?> грн</td>
                        <td class="px-4 py-2">
                            <form method="POST">
                                <input type="hidden" name="remove" value="<?= $item['id'] ?>">
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">Видалити</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class="bg-gray-100 font-bold">
                    <td class="px-4 py-2">Загальна сума:</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="px-4 py-2"><?= $total ?> грн</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex w-full justify-center gap-12 mt-20">
        <a href="home.php" class="flex justify-center w-36 mt-4 bg-gray-400 text-white px-8 py-2 rounded-xl shadow hover:bg-gray-500">
            Скасувати
        </a>
        <a href="" class="flex w-36 justify-center mt-4 bg-green-600 text-white px-8 py-2 rounded-xl shadow hover:bg-green-700">
            Оплатити
        </a>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
