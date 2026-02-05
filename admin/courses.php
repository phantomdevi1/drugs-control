<?php
session_start();
require '../config.php';

/* Доступ только администратору */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

/* Получаем курсы */
$result = $conn->query("
    SELECT id, title, description, is_active
    FROM courses
    ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Курсы</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Курсы обучения</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard_curses">

    <div class="admin-dashboard__top">
        <a href="dashboard.php" class="back_btn">← Назад</a>
        <a href="course_add.php" class="add_users-btn">+ Добавить курс</a>
    </div>

    <section class="admin-dashboard__section">
        <h3 class="admin-dashboard__title">Список курсов</h3>

        <div class="courses-table-wrapper">
            <table class="courses-table">
                <thead class="courses-table__head">
                    <tr class="courses-table__row">
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody class="courses-table__body">

                <?php if ($result->num_rows === 0): ?>
                    <tr>
                        <td colspan="4" class="courses-table__empty">
                            Курсы не созданы
                        </td>
                    </tr>
                <?php endif; ?>

                <?php while ($course = $result->fetch_assoc()): ?>
                    <tr class="courses-table__row">
                        <td class="courses-table__title">
                            <?= htmlspecialchars($course['title']) ?>
                        </td>

                        <td class="courses-table__description">
                            <?= htmlspecialchars(mb_strimwidth($course['description'], 0, 120, '…')) ?>
                        </td>
                        <td class="courses-table__actions">
                            <a href="course_edit.php?id=<?= $course['id'] ?>" class="btn btn--edit">
                                Редактировать
                            </a>
                            <a href="course_delete.php?id=<?= $course['id'] ?>"
                               class="btn btn--delete"
                               onclick="return confirm('Удалить курс?')">
                                Удалить
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>

                </tbody>
            </table>
        </div>
    </section>

</div>

</body>
</html>
