<?php
session_start();

require '../config.php';

/* Доступ только для администратора */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

/* Получаем все темы с курсами */
$result = $conn->query("
    SELECT 
        t.id,
        t.title,
        t.created_at,
        c.title AS course_title
    FROM topics t
    JOIN courses c ON c.id = t.course_id
    ORDER BY c.id, t.created_at
");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Темы курсов</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Темы курсов</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">

    <div class="admin-dashboard__top">
        <a href="dashboard.php" class="back_btn">← Назад</a>
        <a href="topic_add.php" class="btn btn--add">+ Добавить тему</a>
    </div>

    <section class="admin-dashboard__section">
        <h3 class="admin-dashboard__title">Список тем</h3>

        <div class="topics-table-wrapper">
            <table class="topics-table">
                <thead class="topics-table__head">
                    <tr class="topics-table__row">
                        <th>Курс</th>
                        <th>Тема</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                </thead>

                <tbody class="topics-table__body">
                <?php if ($result->num_rows === 0): ?>
                    <tr>
                        <td colspan="4" class="topics-table__empty">
                            Темы пока не добавлены
                        </td>
                    </tr>
                <?php else: ?>
                    <?php while ($topic = $result->fetch_assoc()): ?>
                        <tr class="topics-table__row">
                            <td><?= htmlspecialchars($topic['course_title']) ?></td>
                            <td><?= htmlspecialchars($topic['title']) ?></td>
                            <td><?= date('d.m.Y', strtotime($topic['created_at'])) ?></td>
                            <td class="topics-table__actions">
                                <a href="articles.php?topic_id=<?= $topic['id'] ?>" class="btn btn--view">
                                    Материалы
                                </a>
                                <a href="topic_edit.php?id=<?= $topic['id'] ?>" class="btn btn--edit">
                                    Редактировать
                                </a>
                                <a href="topic_delete.php?id=<?= $topic['id'] ?>"
                                   class="btn btn--delete"
                                   onclick="return confirm('Удалить тему?')">
                                    Удалить
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</div>

</body>
</html>
