<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

/* Получаем курсы */
$courses = $conn->query("SELECT id, title FROM courses ORDER BY title");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить тему</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Добавление темы</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">
  <div class="admin-dashboard__top">
   <a href="topics.php" class="back_btn">← Назад</a>
   </div>
    <form method="post" class="form form--admin" action="topic_save.php">
        <div class="form__field">
            <label class="form__label">Курс</label>
            <select name="course_id" class="form__input" required>
                <option value="">— Выберите курс —</option>
                <?php while ($course = $courses->fetch_assoc()): ?>
                    <option value="<?= $course['id'] ?>">
                        <?= htmlspecialchars($course['title']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form__field">
            <label class="form__label">Название темы</label>
            <input type="text" name="title" class="form__input" required>
        </div>

        <div class="form__actions">
            <button class="btn btn--save">Сохранить</button>
            <a href="topics.php" class="btn btn--cancel">Отмена</a>
        </div>
    </form>
</div>

</body>
</html>
