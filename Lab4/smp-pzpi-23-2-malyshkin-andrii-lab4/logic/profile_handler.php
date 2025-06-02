<?php

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $birthDate = trim($_POST['birth_date'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $userId = Auth::getCurrentUserId();

    $errors = [];

    if (empty($firstName)) $errors[] = 'Ім\'я не може бути порожнім';
    if (empty($lastName)) $errors[] = 'Прізвище не може бути порожнім';
    if (empty($birthDate)) $errors[] = 'Дата народження не може бути порожньою';
    if (empty($bio)) $errors[] = 'Біографія не може бути порожньою';

    if (strlen($firstName) < 2) $errors[] = 'Ім\'я має містити більше одного символу';
    if (strlen($lastName) < 2) $errors[] = 'Прізвище має містити більше одного символу';

    if ($birthDate) {
        $birthDateTime = new DateTime($birthDate);
        $now = new DateTime();
        $age = $now->diff($birthDateTime)->y;
        if ($age < 16) {
            $errors[] = 'Користувачеві має бути не менше 16 років';
        }
    }

    if (strlen($bio) < 50) {
        $errors[] = 'Біографія має містити не менше 50 символів';
    }

    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
            $errors[] = 'Дозволені тільки файли зображень (JPEG, PNG, GIF, WebP)';
        }

        if ($_FILES['photo']['size'] > $maxSize) {
            $errors[] = 'Розмір файлу не повинен перевищувати 5MB';
        }

        if (empty($errors)) {
            $uploadDir = 'uploads/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = 'user_' . $userId . '_' . time() . '.' . $extension;
            $photoPath = $uploadDir . $filename;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                $errors[] = 'Помилка завантаження файлу';
                $photoPath = null;
            }
        }
    }

    if (empty($errors)) {
        $profile = new Profile();
        if ($profile->update($userId, $firstName, $lastName, $birthDate, $bio, $photoPath)) {
            $success = 'Профіль успішно оновлено!';
        } else {
            $error = 'Помилка збереження профілю';
        }
    } else {
        $error = implode('<br>', $errors);
    }
}
?>