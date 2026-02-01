<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: student/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $personalNumber = trim($_POST['personal_number']);
    $password = $_POST['password'];

    if ($personalNumber === '' || $password === '') {
        $error = 'Введите личный номер и пароль';
    } else {
        $stmt = $conn->prepare(
            "SELECT id, password_hash, role 
             FROM users 
             WHERE personal_number = ? 
             LIMIT 1"
        );
        $stmt->bind_param('s', $personalNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: student/dashboard.php');
                }
                exit;
            } else {
                $error = 'Неверный пароль';
            }
        } else {
            $error = 'Пользователь не найден';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-container">
  <img src="img/auth_img.png" alt="" class="login_logo">
    <h2>Электронная академия МВД</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="login_form"> 
            <input type="text" name="personal_number" class="input_login" required placeholder="Личный номер">           
            <input type="password" name="password" class="input_login" required placeholder="Пароль">
        <button type="submit" class="login_btn">Войти</button>
    </form>
</div>

</body>
</html>
