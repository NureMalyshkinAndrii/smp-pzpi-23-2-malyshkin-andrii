<h1 class="text-2xl font-bold mb-12 text-center">Вхід до системи</h1>

<div class="flex justify-center">
    <div class="bg-white w-[30rem] p-8 shadow rounded-xl">
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                    Ім'я користувача
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    placeholder="Введіть ім'я користувача">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Пароль
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Введіть пароль">
            </div>

            <button
                type="submit"
                class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 font-medium">
                Увійти або зареєструватися
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Якщо користувача з таким ім'ям ще не було зареєстровано - буде створено нового користувача</p>
        </div>
    </div>
</div>