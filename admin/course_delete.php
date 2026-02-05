<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id'])) {
    die('Курс не указан');
}

$courseId = (int)$_GET['id'];

/*
ВАЖНО:
если у курса есть темы / статьи / тесты —
лучше сначала их удалить или запретить удаление
*/

$stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
$stmt->bind_param('i', $courseId);
$stmt->execute();

header('Location: courses.php');
exit;
