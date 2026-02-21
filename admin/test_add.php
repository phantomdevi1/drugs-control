<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$topics = $conn->query("
    SELECT t.id, t.title, c.title AS course_title
    FROM topics t
    JOIN courses c ON c.id = t.course_id
    ORDER BY c.title, t.title
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topic_id = $_POST['topic_id'] !== '' ? (int)$_POST['topic_id'] : null;
    $is_final = isset($_POST['is_final']) ? 1 : 0;
    $passing_score = (int)$_POST['passing_score'];

    if ($is_final) {
        $topic_id = null;
    }

    $stmt = $conn->prepare("
        INSERT INTO tests (topic_id, is_final, passing_score)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param('iii', $topic_id, $is_final, $passing_score);
    $stmt->execute();

    header('Location: tests.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить тест</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Добавление теста</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>


<div class="admin-dashboard">
<form method="post" class="form form--admin">
    <a href="tests.php" class="back_btn">← Назад</a>

    <div class="form__field">
        <label class="form__label">
            <input type="checkbox" name="is_final">
            Итоговый экзамен
        </label>
    </div>

    <div class="form__field">
        <label class="form__label">Тема</label>
        <select name="topic_id" class="form__select">
            <option value="">— не выбрано —</option>
            <?php while ($t = $topics->fetch_assoc()): ?>
                <option value="<?= $t['id'] ?>">
                    <?= htmlspecialchars($t['course_title'].' / '.$t['title']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form__field">
        <label class="form__label">Проходной процент</label>
        <input type="number" name="passing_score" class="form__input" value="70" min="1" max="100" required>
    </div>

    <div class="form__actions">
        <button class="btn btn--save">Сохранить</button>
        <a href="tests.php" class="btn btn--cancel">Отмена</a>
    </div>

</form>
</div>
</body>
</html>