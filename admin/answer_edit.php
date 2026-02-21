<?php
session_start();
require '../config.php';
if ($_SESSION['role'] !== 'admin') exit;

$id = (int)($_GET['id'] ?? 0);
$answer = $conn->query("SELECT * FROM answers WHERE id = $id")->fetch_assoc();
if (!$answer) die('Ответ не найден');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['answer_text']);
    $isCorrect = isset($_POST['is_correct']) ? 1 : 0;

    if ($isCorrect) {
        $conn->query("
            UPDATE answers SET is_correct = 0
            WHERE question_id = {$answer['question_id']}
        ");
    }

    $stmt = $conn->prepare("
        UPDATE answers SET answer_text = ?, is_correct = ?
        WHERE id = ?
    ");
    $stmt->bind_param('sii', $text, $isCorrect, $id);
    $stmt->execute();

    header("Location: answers.php?question_id={$answer['question_id']}");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Редактировать ответ</title>
<link rel="stylesheet" href="../style.css">
<link rel="shortcut icon" href="../img/favicon.ico">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Редактировать ответ</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">

    <div class="admin-dashboard__actions">
        <a href="answers.php?question_id=<?= $answer['question_id'] ?>" class="back_btn">← Назад</a>
    </div>

    <section class="admin-dashboard__section">
        <form method="post" class="form form--admin">
            <input type="text" name="answer_text"
                   value="<?= htmlspecialchars($answer['answer_text']) ?>" required placeholder="Текст ответа">
            <label>
                <input type="checkbox" name="is_correct" <?= $answer['is_correct'] ? 'checked' : '' ?>>
                Правильный ответ
            </label>
            <button class="btn btn--save">Сохранить</button>
        </form>
    </section>

</div>
</body>
</html>