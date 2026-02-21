<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit;
}

$test_id = (int)($_GET['test_id'] ?? 0);
if (!$test_id) {
    die('Тест не указан');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_text = trim($_POST['question_text']);

    if ($question_text === '') {
        die('Текст вопроса обязателен');
    }

    $stmt = $conn->prepare("
        INSERT INTO questions (test_id, question_text)
        VALUES (?, ?)
    ");
    $stmt->bind_param('is', $test_id, $question_text);
    $stmt->execute();

    header("Location: questions.php?test_id=$test_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Добавить вопрос</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Добавление вопроса</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">
    <a href="tests.php" class="back_btn">← Назад</a>
<form method="post" class="form form--admin">

    <div class="form__field">
        <label class="form__label">Текст вопроса</label>
        <textarea name="question_text"
                  class="form__textarea"
                  rows="4"
                  required></textarea>
    </div>

    <div class="form__actions">
        <button class="btn btn--save">Сохранить</button>
        <a href="questions.php?test_id=<?= $test_id ?>" class="btn btn--cancel">Отмена</a>
    </div>

</form>
</div>
</body>
</html>