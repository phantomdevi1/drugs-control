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
    <title>Административная страница</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>

    <header class="student_header">
        <img src="../img/auth_img.png" alt="">
        <h2>
            <?= htmlspecialchars($user['last_name']) ?>
            <?= htmlspecialchars($user['first_name']) ?>
            <?= htmlspecialchars($user['middle_name']) ?>
        </h2>
        <p><?= htmlspecialchars($user['position']) ?>, <?= htmlspecialchars($user['rank']) ?></p>
        <a href="../logout.php">Выйти</a>
        <img src="../img/auth_img.png" alt="" class="header_logo_right">
    </header>
<div class="admin-dashboard">

    <section class="admin-dashboard__section admin-dashboard__section--stats">
        <h3 class="admin-dashboard__title">Статистика системы</h3>

        <ul class="stats-list">
            <li class="stats-list__item">
                <span class="stats-list__label">Пользователи</span>
                <span class="stats-list__value"><?= $stats['users'] ?></span>
            </li>
            <li class="stats-list__item">
                <span class="stats-list__label">Курсы</span>
                <span class="stats-list__value"><?= $stats['courses'] ?></span>
            </li>
            <li class="stats-list__item">
                <span class="stats-list__label">Темы</span>
                <span class="stats-list__value"><?= $stats['topics'] ?></span>
            </li>
            <li class="stats-list__item">
                <span class="stats-list__label">Статьи</span>
                <span class="stats-list__value"><?= $stats['articles'] ?></span>
            </li>
        </ul>
    </section>

    <section class="admin-dashboard__section admin-dashboard__section--management">
        <h3 class="admin-dashboard__title">Управление системой</h3>

        <ul class="admin-menu">
            <li class="admin-menu__item">
                <a class="admin-menu__link" href="users.php">Пользователи</a>
            </li>
            <li class="admin-menu__item">
                <a class="admin-menu__link" href="courses.php">Курсы</a>
            </li>
            <li class="admin-menu__item">
                <a class="admin-menu__link" href="topics.php">Темы</a>
            </li>
            <li class="admin-menu__item">
                <a class="admin-menu__link" href="articles.php">Статьи</a>
            </li>
            <li class="admin-menu__item">
                <a class="admin-menu__link" href="tests.php">Тесты</a>
            </li>
        </ul>
    </section>

</div>


</body>
</html>
