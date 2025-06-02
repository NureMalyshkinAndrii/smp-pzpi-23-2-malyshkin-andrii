<?php
$cart = new Cart(Auth::getCurrentUserId());
$cartItems = $cart->getItems();
?>

<h1 class="text-2xl font-bold mb-12 text-center">Кошик</h1>

<?php if (empty($cart)): ?>
    <p class="text-center text-lg">Ваш кошик порожній. <a href="index.php?page=products" class="text-green-600 underline">Перейдіть до покупок</a></p>
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
                foreach ($cartItems as $item):
                    $sum = $item['price'] * $item['quantity'];
                    $total += $sum;
                ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?= $item['product_id'] ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($item['name']) ?></td>
                        <td class="px-4 py-2"><?= $item['price'] ?> грн</td>
                        <td class="px-4 py-2"><?= $item['quantity'] ?></td>
                        <td class="px-4 py-2"><?= $sum ?> грн</td>
                        <td class="px-4 py-2">
                            <form method="POST">
                                <input type="hidden" name="remove" value="<?= $item['product_id'] ?>">
                                <button type="submit" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-trash2-icon lucide-trash-2">
                                        <path d="M3 6h18" />
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                        <line x1="10" x2="10" y1="11" y2="17" />
                                        <line x1="14" x2="14" y1="11" y2="17" />
                                    </svg>
                                </button>
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
        <a href="index.php?page=home" class="flex justify-center w-36 mt-4 bg-gray-400 text-white px-8 py-2 rounded-xl shadow hover:bg-gray-500">
            Скасувати
        </a>
        <a href="" class="flex w-36 justify-center mt-4 bg-green-600 text-white px-8 py-2 rounded-xl shadow hover:bg-green-700">
            Оплатити
        </a>
    </div>
<?php endif; ?>