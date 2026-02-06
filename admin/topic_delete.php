<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$id = (int)$_GET['id'];

/* Проверка статей */
$check = $conn->prepare("SELECT id FROM articles WHERE topic_id = ?");
$check->bind_param('i', $id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    $_SESSION['error'] = 'Нельзя удалить тему с материалами';
    header('Location: topics.php');
    exit;
}

$del = $conn->prepare("DELETE FROM topics WHERE id = ?");
$del->bind_param('i', $id);
$del->execute();

header('Location: topics.php');
exit;
