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

$q = $conn->query("SELECT test_id FROM questions WHERE id = $id")->fetch_assoc();
if (!$q) {
    die('Вопрос не найден');
}

$conn->query("DELETE FROM questions WHERE id = $id");

header("Location: questions.php?test_id=".$q['test_id']);
exit;