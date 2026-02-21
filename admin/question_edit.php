<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    die('Вопрос не указан');
}

$question = $conn->query("
    SELECT * FROM questions WHERE id = $id
")->fetch_assoc();

if (!$question) {
    die('Вопрос не найден');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['question_text']);

    if ($text === '') {
        die('Текст вопроса обязателен');
    }

    $stmt = $conn->prepare("
        UPDATE questions SET question_text = ?
        WHERE id = ?
    ");
    $stmt->bind_param('si', $text, $id);
    $stmt->execute();

    header("Location: questions.php?test_id=".$question['test_id']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Редактировать вопрос</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

<header class="student_header">
    <h2>Редактирование вопроса</h2>
</header>

<div class="admin-dashboard">
<form method="post" class="form form--admin">

    <div class="form__field">
        <label class="form__label">Текст вопроса</label>
        <textarea name="question_text"
                  class="form__textarea"
                  rows="4"
                  required><?= htmlspecialchars($question['question_text']) ?></textarea>
    </div>

    <div class="form__actions">
        <button class="btn btn--save">Сохранить</button>
        <a href="questions.php?test_id=<?= $question['test_id'] ?>" class="btn btn--cancel">Отмена</a>
    </div>

</form>
</div>
</body>
</html>