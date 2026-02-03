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

// Получаем ID курса из GET
$courseId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($courseId <= 0) die('Курс не выбран');

// Данные курса
$courseStmt = $conn->prepare("
    SELECT title, description
    FROM courses
    WHERE id = ? AND is_active = 1
");
$courseStmt->bind_param('i', $courseId);
$courseStmt->execute();
$course = $courseStmt->get_result()->fetch_assoc();
if (!$course) die('Курс не найден');

// Получаем темы и статьи курса
$topicsQuery = "
SELECT t.id AS topic_id, t.title AS topic_title,
       a.id AS article_id, a.title AS article_title
FROM topics t
LEFT JOIN articles a ON a.topic_id = t.id
WHERE t.course_id = ?
ORDER BY t.id, a.id
";
$topicsStmt = $conn->prepare($topicsQuery);
$topicsStmt->bind_param('i', $courseId);
$topicsStmt->execute();
$result = $topicsStmt->get_result();

// Формируем массив тем и статей
$topics = [];
$allArticleIds = []; // Для проверки прогресса
while ($row = $result->fetch_assoc()) {
    $topics[$row['topic_id']]['title'] = $row['topic_title'];
    if ($row['article_id']) {
        $topics[$row['topic_id']]['articles'][] = [
            'id' => $row['article_id'],
            'title' => $row['article_title']
        ];
        $allArticleIds[] = $row['article_id'];
    }
}

// Проверяем прогресс пользователя по статьям
$totalArticles = count($allArticleIds);
$completedArticles = 0;

if ($totalArticles > 0) {
    $placeholders = implode(',', array_fill(0, $totalArticles, '?'));
    $types = str_repeat('i', $totalArticles);
    $stmtProgress = $conn->prepare("
        SELECT COUNT(*) AS completed_count
        FROM article_progress
        WHERE user_id = ? AND article_id IN ($placeholders)
    ");
    $stmtProgress->bind_param('i' . $types, $userId, ...$allArticleIds);
    $stmtProgress->execute();
    $completedArticles = $stmtProgress->get_result()->fetch_assoc()['completed_count'];
}

// Флаг: все статьи пройдены?
$allArticlesCompleted = ($totalArticles > 0 && $completedArticles === $totalArticles);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($course['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>
<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2><?= htmlspecialchars($user['last_name']) ?> <?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['middle_name']) ?></h2>
    <p><?= htmlspecialchars($user['position']) ?>, <?= htmlspecialchars($user['rank']) ?></p>
    <a href="../logout.php">Выйти</a>
</header>

<div class="student_content">
    <!-- Кнопка Назад -->
    <div style="margin: 20px 0;">
        <a href="dashboard.php" class="back_btn">← Назад</a>
    </div>

    <h3><?= htmlspecialchars($course['title']) ?></h3>
    <p><?= htmlspecialchars($course['description']) ?></p>

    <?php if (!empty($topics)): ?>
        <?php foreach ($topics as $topic): ?>
            <div class="topic-block">
                <h4><?= htmlspecialchars($topic['title']) ?></h4>
                <?php if (!empty($topic['articles'])): ?>
                    <ul>
                        <?php foreach ($topic['articles'] as $article): ?>
                            <li>
                                <a href="article.php?id=<?= $article['id'] ?>">
                                    <?= htmlspecialchars($article['title']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Статей пока нет</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Тем пока нет</p>
    <?php endif; ?>

    <!-- Блок кнопки "Пройти тест курса" -->
    <div style="text-align:center; margin-top:50px;">
        <a href="<?= $allArticlesCompleted ? 'course_test.php?course_id=' . $courseId : '#' ?>"
           class="final_exam_btn <?= $allArticlesCompleted ? '' : 'disabled' ?>">
           Пройти тест курса
        </a>
        <?php if (!$allArticlesCompleted): ?>
            <div style="margin-top:10px; color:#666; font-size:14px;">
                Для доступа пройдите все статьи
            </div>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
