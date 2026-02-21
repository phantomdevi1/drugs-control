<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$result = $conn->query("
    SELECT 
        t.id,
        t.is_final,
        t.passing_score,
        tp.title AS topic_title,
        c.title AS course_title
    FROM tests t
    LEFT JOIN topics tp ON tp.id = t.topic_id
    LEFT JOIN courses c ON c.id = tp.course_id
    ORDER BY t.is_final DESC, c.title, tp.title
");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тесты</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Тесты</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">

    <div class="admin-dashboard__actions">
        <a href="dashboard.php" class="back_btn">← Назад</a>
        <a href="test_add.php" class="add_users-btn">+ Добавить тест</a>
    </div>

    <section class="admin-dashboard__section">
        <table class="users-table">
            <thead>
                <tr>
                    <th>Тип</th>
                    <th>Курс</th>
                    <th>Тема</th>
                    <th>Проходной %</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows === 0): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Тесты не добавлены</td>
                </tr>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?= $row['is_final'] ? 'Итоговый экзамен' : 'Тест по теме' ?>
                        </td>
                        <td><?= htmlspecialchars($row['course_title'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($row['topic_title'] ?? '—') ?></td>
                        <td><?= (int)$row['passing_score'] ?>%</td>
                        <td class="users-table__actions">
                            <a href="questions.php?test_id=<?= $row['id'] ?>" class="btn btn--edit">
                                Вопросы
                            </a>
                            <a href="test_edit.php?id=<?= $row['id'] ?>" class="btn btn--edit">
                                Редактировать
                            </a>
                            <a href="test_delete.php?id=<?= $row['id'] ?>"
                               class="btn btn--delete"
                               onclick="return confirm('Удалить тест?')">
                                Удалить
                            </a>
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