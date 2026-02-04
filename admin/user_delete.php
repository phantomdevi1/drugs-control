<?php
session_start();



if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require '../config.php';

if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit;
}

$userId = (int)$_GET['id'];

/* 🚫 Запрет удаления самого себя */
if ($userId === (int)$_SESSION['user_id']) {
    $_SESSION['error'] = 'Нельзя удалить самого себя';
    header('Location: users.php');
    exit;
}

/* ✅ Удаление (каскады сработают автоматически) */
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param('i', $userId);
$stmt->execute();

$_SESSION['success'] = 'Пользователь успешно удалён';
header('Location: users.php');
exit;
