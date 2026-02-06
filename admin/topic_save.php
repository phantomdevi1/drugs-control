<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: topics.php');
    exit;
}

$title = trim($_POST['title']);
$course_id = (int)$_POST['course_id'];

if ($title === '' || !$course_id) {
    die('Некорректные данные');
}

$stmt = $conn->prepare("
    INSERT INTO topics (title, course_id)
    VALUES (?, ?)
");
$stmt->bind_param('si', $title, $course_id);
$stmt->execute();

header('Location: topics.php');
exit;
