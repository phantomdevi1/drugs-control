<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') exit;

$testId = (int)($_GET['test_id'] ?? 0);
if (!$testId) die('Тест не указан');

$questions = $conn->query("
    SELECT q.id, q.question_text,
           (SELECT COUNT(*) FROM answers a WHERE a.question_id = q.id) AS answers_count
    FROM questions q
    WHERE q.test_id = $testId
");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Вопросы</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Вопросы теста</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">
  <a href="tests.php" class="back_btn">← Назад</a>
    <a href="question_add.php?test_id=<?= $testId ?>" class="add_users-btn">
        + Добавить вопрос
    </a>

    <table class="users-table">
        <tr>
            <th>Вопрос</th>
            <th>Ответы</th>
            <th>Действия</th>
        </tr>

        <?php while ($q = $questions->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($q['question_text']) ?></td>
            <td><?= $q['answers_count'] ?></td>
            <td class="users-table__actions">
                <a href="answers.php?question_id=<?= $q['id'] ?>" class="btn btn--edit">
                    Ответы
                </a>
                <a href="question_edit.php?id=<?= $q['id'] ?>" class="btn btn--edit">✏</a>
                <a href="question_delete.php?id=<?= $q['id'] ?>"
                   onclick="return confirm('Удалить вопрос?')"
                   class="btn btn--delete">❌</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>