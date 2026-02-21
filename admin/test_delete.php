<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit;
}

$id = (int)$_GET['id'];

$conn->query("DELETE FROM tests WHERE id = $id");

header('Location: tests.php');
exit;