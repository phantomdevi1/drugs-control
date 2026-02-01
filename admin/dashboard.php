<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Проверка роли
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    die('Доступ запрещён');
}

require '../config.php';

// Получаем данные администратора
$userId = $_SESSION['user_id'];
$userStmt = $conn->prepare("
    SELECT last_name, first_name, middle_name, position, rank
    FROM users
    WHERE id = ?
");
$userStmt->bind_param('i', $userId);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

// Получаем статистику по системе (кол-во пользователей, курсов и т.д.)
$stats = [];
$res = $conn->query("SELECT COUNT(*) AS count FROM users");
$stats['users'] = $res->fetch_assoc()['count'];

$res = $conn->query("SELECT COUNT(*) AS count FROM courses");
$stats['courses'] = $res->fetch_assoc()['count'];

$res = $conn->query("SELECT COUNT(*) AS count FROM topics");
$stats['topics'] = $res->fetch_assoc()['count'];

$res = $conn->query("SELECT COUNT(*) AS count FROM articles");
$stats['articles'] = $res->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админка</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">

    <header>
        <h2>Админ: <?= htmlspecialchars($user['last_name']) ?> <?= htmlspecialchars($user['first_name']) ?></h2>
        <p><?= htmlspecialchars($user['position']) ?>, <?= htmlspecialchars($user['rank']) ?></p>
        <a href="../logout.php">Выйти</a>
    </header>

    <hr>

    <h3>Статистика системы</h3>
    <ul>
        <li>Пользователи: <?= $stats['users'] ?></li>
        <li>Курсы: <?= $stats['courses'] ?></li>
        <li>Темы: <?= $stats['topics'] ?></li>
        <li>Статьи: <?= $stats['articles'] ?></li>
    </ul>

    <h3>Управление</h3>
    <ul>
        <li><a href="users.php">Пользователи</a></li>
        <li><a href="courses.php">Курсы</a></li>
        <li><a href="topics.php">Темы</a></li>
        <li><a href="articles.php">Статьи</a></li>
        <li><a href="tests.php">Тесты</a></li>
    </ul>

</div>

</body>
</html>
