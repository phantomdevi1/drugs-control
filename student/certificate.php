<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die('Доступ запрещён');
}

require '../config.php';

$userId = $_SESSION['user_id'];

// Пользователь
$stmt = $conn->prepare("
    SELECT last_name, first_name, middle_name, position, rank
    FROM users
    WHERE id = ?
");
$stmt->bind_param('i', $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die('Пользователь не найден');
}

// Проверка итогового экзамена
$examStmt = $conn->prepare("
    SELECT id, completed_at
    FROM test_results
    WHERE user_id = ? AND test_id IS NULL AND passed = 1
    ORDER BY completed_at DESC
    LIMIT 1
");
$examStmt->bind_param('i', $userId);
$examStmt->execute();
$exam = $examStmt->get_result()->fetch_assoc();

if (!$exam) {
    die('Вы ещё не прошли итоговую аттестацию');
}

// Номер сертификата (простой пример)
$certificateNumber = 'NK-' . str_pad($exam['id'], 6, '0', STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Сертификат</title>

<style>
body {
    background: #f2f2f2;
    font-family: "Times New Roman", serif;
}

.certificate {
    width: 1100px;
    margin: 40px auto;
    background: #fff;
    padding: 70px 90px;
    border: 10px double #000;
}

.header {
    text-align: center;
    margin-bottom: 40px;
}

.header img {
    height: 90px;
    margin-bottom: 15px;
}

.header-title {
    font-size: 24px;
    font-weight: bold;
    text-transform: uppercase;
}

.sub-title {
    font-size: 18px;
    margin-top: 10px;
}

.main-text {
    margin-top: 40px;
    font-size: 20px;
    line-height: 1.6;
    text-align: center;
}

.name {
    margin: 25px 0;
    font-size: 26px;
    font-weight: bold;
    text-decoration: underline;
}

.details {
    margin-top: 40px;
    font-size: 18px;
}

.footer {
    margin-top: 60px;
    display: flex;
    justify-content: space-between;
    font-size: 16px;
}

.print-btn {
    margin: 30px auto;
    display: block;
    padding: 12px 26px;
    font-size: 16px;
    cursor: pointer;
}

@media print {
    body {
        background: none;
    }
    .print-btn {
        display: none;
    }
}
</style>
</head>
<body>

<button class="print-btn" onclick="window.print()">
    Скачать / распечатать PDF
</button>

<div class="certificate">

    <div class="header">
        <img src="../img/auth_img.png" alt="Логотип">
        <div class="header-title">
            Сертификат
        </div>
        <div class="sub-title">
            о прохождении профессионального обучения
        </div>
    </div>

    <div class="main-text">
        Настоящий сертификат подтверждает, что
        <div class="name">
            <?= htmlspecialchars($user['last_name']) ?>
            <?= htmlspecialchars($user['first_name']) ?>
            <?= htmlspecialchars($user['middle_name']) ?>
        </div>

        замещающий должность <b><?= htmlspecialchars($user['position']) ?></b>,
        имеющий специальное звание <b><?= htmlspecialchars($user['rank']) ?></b>,
        успешно прошёл обучение и итоговую аттестацию
        по программе повышения квалификации
        <br><br>
        <b>
            «Противодействие незаконному обороту наркотических средств и психотропных веществ»
        </b>
    </div>

    <div class="details">
        Обучение проведено в рамках ведомственной подготовки сотрудников
        подразделений, осуществляющих функции в сфере наркоконтроля.
    </div>

    <div class="footer">
        <div>
            Дата выдачи:<br>
            <?= date('d.m.Y', strtotime($exam['completed_at'])) ?>
        </div>

        <div>
            Регистрационный номер:<br>
            <?= $certificateNumber ?>
        </div>
    </div>

</div>

</body>
</html>
