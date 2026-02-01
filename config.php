<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drug_control_training";

// Создаем подключение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>