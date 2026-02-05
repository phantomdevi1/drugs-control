<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require '../config.php';

if (!isset($_GET['id'])) {
    die('Не указан пользователь');
}

$userId = (int)$_GET['id'];

/* Запрет удаления самого себя */
if ($userId === (int)$_SESSION['user_id']) {
    echo "<script>
        alert('Нельзя удалить самого себя');
        window.location.href = 'users.php';
    </script>";
    exit;
}

/* Удаление */
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param('i', $userId);
$stmt->execute();

header('Location: users.php');
exit;
