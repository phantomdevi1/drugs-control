<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id'])) {
    die('Тема не указана');
}

$topicId = (int)$_GET['id'];

/* Текущая тема */
$stmt = $conn->prepare("
    SELECT id, title, course_id
    FROM topics
    WHERE id = ?
");
$stmt->bind_param('i', $topicId);
$stmt->execute();
$topic = $stmt->get_result()->fetch_assoc();

if (!$topic) {
    die('Тема не найдена');
}

/* Курсы */
$courses = $conn->query("SELECT id, title FROM courses ORDER BY title");

/* Обновление */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $course_id = (int)$_POST['course_id'];

    $update = $conn->prepare("
        UPDATE topics SET title = ?, course_id = ?
        WHERE id = ?
    ");
    $update->bind_param('sii', $title, $course_id, $topicId);
    $update->execute();

    header('Location: topics.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать тему</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Редактирование темы</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">
  <div class="admin-dashboard__top">
   <a href="topics.php" class="back_btn">← Назад</a>
   </div>
    <form method="post" class="form form--admin">
        <div class="form__field">
            <label class="form__label">Курс</label>
            <select name="course_id" class="form__input" required>
                <?php while ($course = $courses->fetch_assoc()): ?>
                    <option value="<?= $course['id'] ?>"
                        <?= $course['id'] == $topic['course_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($course['title']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form__field">
            <label class="form__label">Название темы</label>
            <input type="text" name="title" class="form__input"
                   value="<?= htmlspecialchars($topic['title']) ?>" required>
        </div>

        <div class="form__actions">
            <button class="btn btn--save">Сохранить</button>
            <a href="topics.php" class="btn btn--cancel">Отмена</a>
        </div>
    </form>
</div>

</body>
</html>
