<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id'])) {
    die('Курс не указан');
}

$courseId = (int)$_GET['id'];

$stmt = $conn->prepare("
    SELECT * FROM courses WHERE id = ?
");
$stmt->bind_param('i', $courseId);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

if (!$course) {
    die('Курс не найден');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $update = $conn->prepare("
        UPDATE courses
        SET title = ?, description = ?, is_active = ?
        WHERE id = ?
    ");
    $update->bind_param('ssii', $title, $description, $is_active, $courseId);
    $update->execute();

    header('Location: courses.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать курс</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Редактирование курса</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">

    <a href="courses.php" class="back_btn">← Назад</a>

    <form method="post" class="form form--course">

        <label class="form__field">
            <span class="form__label">Название курса</span>
            <input type="text" name="title" class="form__input"
                   value="<?= htmlspecialchars($course['title']) ?>" required>
        </label>

        <label class="form__field">
            <span class="form__label">Описание</span>
            <textarea name="description" class="form__textarea" style="min-width: 300px; min-height: 150px;"><?= htmlspecialchars($course['description']) ?></textarea>
        </label>

        <label class="form__checkbox">
            <input type="checkbox" name="is_active" <?= $course['is_active'] ? 'checked' : '' ?>>
            <span>Курс активен</span>
        </label>

        <div class="form__actions">
            <button type="submit" class="btn btn--save">Сохранить</button>
        </div>

    </form>

</div>
</body>
</html>
