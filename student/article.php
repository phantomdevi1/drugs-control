<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Проверка роли
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'student') {
    die('Доступ запрещён');
}

require '../config.php';

$userId = $_SESSION['user_id'];

// Данные пользователя
$userStmt = $conn->prepare("
    SELECT last_name, first_name, middle_name, position, rank
    FROM users
    WHERE id = ?
");
$userStmt->bind_param('i', $userId);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

// Получаем ID статьи из GET
$articleId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($articleId <= 0) die('Статья не выбрана');

// Данные статьи
$articleStmt = $conn->prepare("
    SELECT a.title AS article_title, a.content_type, a.file_path, t.id AS topic_id, t.title AS topic_title, c.id AS course_id
    FROM articles a
    JOIN topics t ON a.topic_id = t.id
    JOIN courses c ON t.course_id = c.id
    WHERE a.id = ?
");
$articleStmt->bind_param('i', $articleId);
$articleStmt->execute();
$article = $articleStmt->get_result()->fetch_assoc();
if (!$article) die('Статья не найдена');

// Отмечаем прогресс пользователя (если ещё не отмечен)
$checkProgress = $conn->prepare("SELECT id FROM article_progress WHERE user_id = ? AND article_id = ?");
$checkProgress->bind_param('ii', $userId, $articleId);
$checkProgress->execute();
if ($checkProgress->get_result()->num_rows === 0) {
    $insertProgress = $conn->prepare("INSERT INTO article_progress (user_id, article_id, completed_at) VALUES (?, ?, NOW())");
    $insertProgress->bind_param('ii', $userId, $articleId);
    $insertProgress->execute();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($article['article_title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2><?= htmlspecialchars($user['last_name']) ?> <?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['middle_name']) ?></h2>
    <p><?= htmlspecialchars($user['position']) ?>, <?= htmlspecialchars($user['rank']) ?></p>
    <a href="../logout.php">Выйти</a>
</header>

<div class="student_content">
    <!-- Кнопка Назад к курсу -->
    <div style="margin: 20px 0;">
        <a href="course.php?id=<?= $article['course_id'] ?>" class="back_btn">← Назад к курсу</a>
    </div>

    <h3><?= htmlspecialchars($article['article_title']) ?></h3>
    <p><strong>Тема:</strong> <?= htmlspecialchars($article['topic_title']) ?></p>

<!-- Контент статьи -->
<div class="article_content" style="margin-top: 20px;">
    <?php if ($article['content_type'] === 'pdf'): ?>
        <iframe src="../<?= htmlspecialchars($article['file_path']) ?>" width="100%" height="600px"></iframe>
    <?php elseif ($article['content_type'] === 'video'): ?>
        <video width="100%" height="400" controls>
            <source src="../<?= htmlspecialchars($article['file_path']) ?>" type="video/mp4">
            Ваш браузер не поддерживает видео.
        </video>
<?php elseif ($article['content_type'] === 'text'): ?>
    <?php
        if (!empty($article['file_path'])) {
            $filePath = __DIR__ . '/../' . ltrim($article['file_path'], '/');
        } else {
            $filePath = '';
        }

        if ($filePath && file_exists($filePath) && filesize($filePath) > 0) {
            $textContent = file_get_contents($filePath);
            echo '<div style="
                display: flex;
                justify-content: center;
                margin-top: 30px;
            ">
                <pre style="
                    max-width: 800px;
                    font-size: 20px;
                    white-space: pre-wrap; 
                    word-wrap: break-word; 
                    text-align: left;
                    line-height: 1.5;
                ">' . htmlspecialchars($textContent) . '</pre>
            </div>';
        } else {
            echo '<p style="
                text-align: center; 
                font-size: 18px; 
                color: #666; 
                margin-top: 50px;
            ">Сейчас эта часть ещё не добавлена</p>';
        }
    ?>
<?php endif; ?>


</div>


</div>
</body>
</html>
