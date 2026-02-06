<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$id = (int)$_GET['id'];

/* Узнаём тему */
$stmt = $conn->prepare("SELECT topic_id FROM articles WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();

if (!$article) {
    die('Материал не найден');
}

$del = $conn->prepare("DELETE FROM articles WHERE id = ?");
$del->bind_param('i', $id);
$del->execute();

header("Location: articles.php?topic_id=".$article['topic_id']);
exit;
