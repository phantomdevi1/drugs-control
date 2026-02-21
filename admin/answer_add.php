<?php
session_start();
require '../config.php';
if ($_SESSION['role'] !== 'admin') exit;

$questionId = (int)($_GET['question_id'] ?? 0);
if (!$questionId) die('Вопрос не указан');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['answer_text']);
    $isCorrect = isset($_POST['is_correct']) ? 1 : 0;

    if ($isCorrect) {
        $conn->query("
            UPDATE answers SET is_correct = 0
            WHERE question_id = $questionId
        ");
    }

    $stmt = $conn->prepare("
        INSERT INTO answers (question_id, answer_text, is_correct)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param('isi', $questionId, $text, $isCorrect);
    $stmt->execute();

    header("Location: answers.php?question_id=$questionId");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Добавить ответ</title>
<link rel="stylesheet" href="../style.css">
<link rel="shortcut icon" href="../img/favicon.ico">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Добавить ответ</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">

    <div class="admin-dashboard__actions">
        <a href="answers.php?question_id=<?= $questionId ?>" class="back_btn">← Назад</a>
    </div>

    <section class="admin-dashboard__section">
        <form method="post" class="form form--admin">
            <input type="text" name="answer_text" required placeholder="Текст ответа">
            <label>
                <input type="checkbox" name="is_correct"> Правильный ответ
            </label>
            <button class="btn btn--save">Сохранить</button>
        </form>
    </section>

</div>
</body>
</html>