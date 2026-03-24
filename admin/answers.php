<?php
session_start();
require '../config.php';
if ($_SESSION['role'] !== 'admin') exit;

$questionId = (int)($_GET['question_id'] ?? 0);
if (!$questionId) die('Вопрос не указан');

$answers = $conn->query("
    SELECT id, answer_text, is_correct
    FROM answers
    WHERE question_id = $questionId
");
$question = $conn->query("
    SELECT test_id FROM questions WHERE id = $questionId
")->fetch_assoc();

if (!$question) {
    die('Вопрос не найден');
}

$testId = (int)$question['test_id'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Ответы</title>
<link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Варианты ответов</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">

    <div class="admin-dashboard__actions">
        <a href="questions.php?test_id=<?= $testId ?>" class="back_btn">
    ← Назад
</a>
            <a href="answer_add.php?question_id=<?= $questionId ?>" class="add_users-btn">
        + Добавить ответ
    </a>
    </div>
<div class="admin-dashboard">


<section class="admin-dashboard__section">
    <table class="users-table">
        <thead>
            <tr>
                <th>Ответ</th>
                <th>Правильный</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($answers->num_rows === 0): ?>
            <tr>
                <td colspan="3" style="text-align:center;">Ответы не добавлены</td>
            </tr>
        <?php else: ?>
            <?php while ($a = $answers->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($a['answer_text']) ?></td>
                    <td><?= $a['is_correct'] ? '✅' : '' ?></td>
                    <td class="users-table__actions">
                        <a href="answer_edit.php?id=<?= $a['id'] ?>" class="btn btn--edit">✏</a>
                        <a href="answer_delete.php?id=<?= $a['id'] ?>"
                           class="btn btn--delete"
                           onclick="return confirm('Удалить ответ?')">❌</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
    </table>
</section>
</div>
</body>
</html>