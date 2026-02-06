<?php
session_start();
require '../config.php';

/* Доступ только администратору */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$error = '';
$success = '';
$newUserData = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $personal_number = trim($_POST['personal_number']);
    $last_name       = trim($_POST['last_name']);
    $first_name      = trim($_POST['first_name']);
    $middle_name     = trim($_POST['middle_name']);
    $position        = trim($_POST['position']);
    $rank            = trim($_POST['rank']);
    $role            = $_POST['role'];
    $password        = $_POST['password'];

    /* Проверка уникальности */
    $check = $conn->prepare("SELECT id FROM users WHERE personal_number = ?");
    $check->bind_param('s', $personal_number);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = 'Пользователь с таким личным номером уже существует';
    }

    if (!$error) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users 
            (personal_number, last_name, first_name, middle_name, position, rank, role, password_hash, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->bind_param(
            'ssssssss',
            $personal_number,
            $last_name,
            $first_name,
            $middle_name,
            $position,
            $rank,
            $role,
            $password_hash
        );

        $stmt->execute();

        $newUserData = [
            'personal_number' => $personal_number,
            'fio' => "$last_name $first_name $middle_name",
            'position' => $position,
            'rank' => $rank,
            'role' => $role,
            'password' => $password
        ];

        $success = 'Пользователь успешно добавлен';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавление пользователя</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Добавление пользователя</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">
    <?php if ($error): ?>
        <script>alert('<?= htmlspecialchars($error) ?>');</script>
    <?php endif; ?>

    <?php if ($success): ?>
        <script>
            alert('<?= htmlspecialchars($success) ?>');
            if (confirm('Напечатать данные пользователя для передачи?')) {
                window.print();
            }
        </script>
    <?php endif; ?>

    <form method="post" class="user-form">

        <div class="user-form__section">
            <h3 class="user-form__title">Основные данные</h3>

            <div class="user-form__grid">
                <div class="form__group">
                    <label class="form__label">Личный номер *</label>
                    <input type="text" name="personal_number" class="form__input" required>
                </div>

                <div class="form__group">
                    <label class="form__label">Фамилия *</label>
                    <input type="text" name="last_name" class="form__input" required>
                </div>

                <div class="form__group">
                    <label class="form__label">Имя *</label>
                    <input type="text" name="first_name" class="form__input" required>
                </div>

                <div class="form__group">
                    <label class="form__label">Отчество</label>
                    <input type="text" name="middle_name" class="form__input">
                </div>

                <div class="form__group">
                    <label class="form__label">Должность</label>
                    <input type="text" name="position" class="form__input">
                </div>

                <div class="form__group">
                    <label class="form__label">Звание</label>
                    <input type="text" name="rank" class="form__input">
                </div>
            </div>
        </div>

        <div class="user-form__section">
            <h3 class="user-form__title">Доступ в систему</h3>

            <div class="user-form__grid">
                <div class="form__group">
                    <label class="form__label">Роль</label>
                    <select name="role" class="form__input">
                        <option value="student">Пользователь</option>
                        <option value="admin">Администратор</option>
                    </select>
                </div>

                <div class="form__group">
                    <label class="form__label">Пароль *</label>

                    <div class="form__password">
                        <input
                            type="text"
                            name="password"
                            id="generatedPassword"
                            class="form__input"
                            readonly
                            required
                        >
                        <button type="button" class="btn btn--generate" onclick="generatePassword()">
                            Сгенерировать
                        </button>
                    </div>

                    <div class="form__hint">
                        Скопируйте пароль и передайте пользователю
                    </div>
                </div>
            </div>
        </div>

        <div class="user-form__actions">
            <button type="submit" class="btn btn--save">Сохранить</button>
            <a href="users.php" class="btn btn--cancel">Отмена</a>
        </div>

    </form>

    <?php if ($newUserData): ?>
    <div class="print-card">
        <h3>Данные пользователя</h3>
        <p><strong>Личный номер:</strong> <?= htmlspecialchars($newUserData['personal_number']) ?></p>
        <p><strong>ФИО:</strong> <?= htmlspecialchars($newUserData['fio']) ?></p>
        <p><strong>Должность:</strong> <?= htmlspecialchars($newUserData['position']) ?></p>
        <p><strong>Звание:</strong> <?= htmlspecialchars($newUserData['rank']) ?></p>
        <p><strong>Роль:</strong> <?= htmlspecialchars($newUserData['role']) ?></p>
        <p><strong>Пароль:</strong> <?= htmlspecialchars($newUserData['password']) ?></p>
    </div>
    <?php endif; ?>

</div>

<script>
function generatePassword(length = 12) {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
    let password = '';
    for (let i = 0; i < length; i++) {
        password += chars[Math.floor(Math.random() * chars.length)];
    }
    document.getElementById('generatedPassword').value = password;
}
</script>

</body>
</html>
