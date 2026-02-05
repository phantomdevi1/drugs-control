<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($title === '') {
        $error = 'Название курса обязательно';
    } else {
        $stmt = $conn->prepare("
            INSERT INTO courses (title, description, is_active)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param('ssi', $title, $description, $is_active);
        $stmt->execute();

        header('Location: courses.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить курс</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Добавление курса</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">

    <a href="courses.php" class="back_btn">← Назад</a>

    <form method="post" class="form form--course">

        <?php if ($error): ?>
            <div class="form__error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <label class="form__field">
            <span class="form__label">Название курса</span>
            <input type="text" name="title" class="form__input" required>
        </label>

        <label class="form__field">
            <span class="form__label">Описание</span>
            <textarea name="description" class="form__textarea"></textarea>
        </label>

        <label class="form__checkbox">
            <input type="checkbox" name="is_active" checked>
            <span>Курс активен</span>
        </label>

        <div class="form__actions">
            <button type="submit" class="btn btn--save">Создать курс</button>
        </div>

    </form>

</div>
</body>
</html>
