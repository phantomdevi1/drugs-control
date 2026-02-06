<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$topicId = isset($_GET['topic_id']) ? (int)$_GET['topic_id'] : null;

$sql = "
    SELECT a.id, a.title, t.title AS topic_title, c.title AS course_title
    FROM articles a
    JOIN topics t ON t.id = a.topic_id
    JOIN courses c ON c.id = t.course_id
";

if ($topicId) {
    $sql .= " WHERE a.topic_id = $topicId";
}

$sql .= " ORDER BY c.title, t.title, a.title";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Материалы</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>
<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Материалы обучения</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

    <div class="admin-dashboard">
    <div class="admin-dashboard__top">
      <a href="topics.php" class="back_btn">← Назад</a>
         <a href="article_add.php?topic_id=<?= $topicId ?>" class="add_users-btn">
            + Добавить материал
        </a>
    </div>

    <table class="users-table">
        <thead>
        <tr>
            <th>Тема</th>
            <th>Название</th>
            <th>Действия</th>
        </tr>
        </thead>

        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['topic_title']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td class="topics-table__actions">
                    <a href="article_edit.php?id=<?= $row['id'] ?>" class="btn btn--edit">Редактировать</a>
                    <a href="article_delete.php?id=<?= $row['id'] ?>"
                       onclick="return confirm('Удалить материал?')"
                       class="btn btn--delete">Удалить</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>
