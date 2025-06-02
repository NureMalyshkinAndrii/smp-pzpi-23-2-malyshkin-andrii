<?php
$profile = Auth::getCurrentUserProfile();
?>

<h1 class="text-2xl font-bold mt-6 mb-12 text-center">Профіль користувача</h1>

<div class="flex justify-center">
    <div class="bg-white w-[50rem] p-8 shadow rounded-xl">
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="flex gap-8">
                <div class="flex-1 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Ім'я
                            </label>
                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                value="<?= htmlspecialchars($profile['first_name'] ?? '') ?>"
                                placeholder="Введіть ім'я">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Прізвище
                            </label>
                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                value="<?= htmlspecialchars($profile['last_name'] ?? '') ?>"
                                placeholder="Введіть прізвище">
                        </div>
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Дата народження
                        </label>
                        <input
                            type="date"
                            id="birth_date"
                            name="birth_date"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            value="<?= htmlspecialchars($profile['birth_date'] ?? '') ?>">
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                            Біографія <span class="text-sm text-gray-500">(мінімум 50 символів)</span>
                        </label>
                        <textarea
                            id="bio"
                            name="bio"
                            required
                            rows="6"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"
                            placeholder="Розкажіть про себе..."><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
                        <div class="text-sm text-gray-500 mt-1">
                            Поточна довжина: <span id="bioLength">0</span> символів
                        </div>
                    </div>
                </div>

                <div class="w-48 mt-7 mb-7 flex flex-col">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center bg-gray-50 mb-4 flex-1 flex flex-col justify-center items-center min-h-[200px]">
                        <?php if (!empty($profile['photo_path']) && file_exists($profile['photo_path'])): ?>
                            <img src="<?= htmlspecialchars($profile['photo_path']) ?>"
                                alt="Поточне фото"
                                class="w-full h-full object-cover rounded-lg border max-h-48"
                                id="profileImage">
                        <?php else: ?>
                            <div class="text-gray-400 mb-4" id="placeholderImage">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-sm">Фото профілю</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <input
                            type="file"
                            id="photo"
                            name="photo"
                            accept="image/*"
                            class="hidden"
                            onchange="previewImage(this)">
                        <button
                            type="button"
                            onclick="document.getElementById('photo').click()"
                            class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 font-medium border border-gray-300">
                            Upload
                        </button>
                        <div class="text-xs text-gray-500 mt-2 text-center">
                            JPEG, PNG, GIF, WebP<br>
                            Макс. 5MB
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-6 border-t">
                <a
                    href="index.php?page=home"
                    class="flex-1 text-center bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 font-medium border border-gray-300">
                    Скасувати
                </a>

                <button
                    type="submit"
                    class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 font-medium">
                    Зберегти
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('bio').addEventListener('input', function() {
        const length = this.value.length;
        document.getElementById('bioLength').textContent = length;

        const lengthElement = document.getElementById('bioLength');
        if (length < 50) {
            lengthElement.className = 'text-red-500 font-bold';
        } else {
            lengthElement.className = 'text-green-500 font-bold';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const bioTextarea = document.getElementById('bio');
        const event = new Event('input');
        bioTextarea.dispatchEvent(event);
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('profileImage');
                const placeholder = document.getElementById('placeholderImage');

                if (img) {
                    img.src = e.target.result;
                } else {
                    placeholder.innerHTML =
                        '<img src="' +
                        e.target.result +
                        '" alt="Попередній перегляд" class="w-full h-full object-cover rounded-lg border max-h-48" id="profileImage">';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>