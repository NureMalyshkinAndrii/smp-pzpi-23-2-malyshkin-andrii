<?php
$products = Product::getAll();
?>

<h1 class="text-2xl font-bold mb-6 text-center">Список товарів</h1>

<div class="flex w-full justify-center">
    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-8 flex w-[30rem] justify-center">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
</div>

<form method="POST" class="flex flex-col gap-4 items-center">
    <?php foreach ($products as $product): ?>
        <div class="bg-white w-[30rem] p-4 shadow rounded-xl flex justify-between items-center">
            <div>
                <p class="font-semibold"><?= htmlspecialchars($product['name']) ?></p>
                <p class="text-sm text-gray-600">Ціна: <?= $product['price'] ?> грн</p>
            </div>
            <div>
                <label for="quantity_<?= $product['id'] ?>" class="mr-2">Кількість:</label>
                <input 
                    type="number" 
                    name="quantities[<?= $product['id'] ?>]" 
                    id="quantity_<?= $product['id'] ?>" min="0" 
                    class="border rounded px-2 py-1 w-20" 
                    value="0"
                >
            </div>
        </div>
    <?php endforeach; ?>

    <button type="submit" class="flex mt-4 bg-green-600 text-white px-8 py-2 rounded-xl shadow hover:bg-green-700">
        Додати в кошик
    </button>
</form>