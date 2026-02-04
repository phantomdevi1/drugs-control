<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require '../config.php';

if (!isset($_GET['id'])) {
    die('Не указан пользователь');
}

$userId = (int)$_GET['id'];

/* Получаем пользователя */
$stmt = $conn->prepare("
    SELECT id, personal_number, last_name, first_name, middle_name, position, rank, role
    FROM users
    WHERE id = ?
");
$stmt->bind_param('i', $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die('Пользователь не найден');
}

/* Обработка формы */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $personal_number = trim($_POST['personal_number']);
    $last_name = trim($_POST['last_name']);
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $position = trim($_POST['position']);
    $rank = trim($_POST['rank']);
    $role = $_POST['role'];

    $update = $conn->prepare("
        UPDATE users
        SET personal_number = ?, last_name = ?, first_name = ?, middle_name = ?,
            position = ?, rank = ?, role = ?
        WHERE id = ?
    ");
    $update->bind_param(
        'sssssssi',
        $personal_number,
        $last_name,
        $first_name,
        $middle_name,
        $position,
        $rank,
        $role,
        $userId
    );

    $update->execute();

    header('Location: users.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование пользователя</title>    
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>
        <header class="student_header">
        <img src="../img/auth_img.png" alt="">
        <h2>Редактирование пользователя</h2>
        <img src="../img/auth_img.png" alt="" class="header_logo_right">
    </header>

<div class="admin-dashboard">
   

    <form method="post" class="admin-user-edit__form form">

            <div class="form__body">

                <label class="form__field">
                    <span class="form__label">Личный номер</span>
                    <input
                        type="text"
                        name="personal_number"
                        class="form__input"
                        required
                        value="<?= htmlspecialchars($user['personal_number']) ?>"
                    >
                </label>

                <label class="form__field">
                    <span class="form__label">Фамилия</span>
                    <input
                        type="text"
                        name="last_name"
                        class="form__input"
                        required
                        value="<?= htmlspecialchars($user['last_name']) ?>"
                    >
                </label>

                <label class="form__field">
                    <span class="form__label">Имя</span>
                    <input
                        type="text"
                        name="first_name"
                        class="form__input"
                        required
                        value="<?= htmlspecialchars($user['first_name']) ?>"
                    >
                </label>

                <label class="form__field">
                    <span class="form__label">Отчество</span>
                    <input
                        type="text"
                        name="middle_name"
                        class="form__input"
                        value="<?= htmlspecialchars($user['middle_name']) ?>"
                    >
                </label>

                <label class="form__field">
                    <span class="form__label">Должность</span>
                    <input
                        type="text"
                        name="position"
                        class="form__input"
                        value="<?= htmlspecialchars($user['position']) ?>"
                    >
                </label>

                <label class="form__field">
                    <span class="form__label">Звание</span>
                    <input
                        type="text"
                        name="rank"
                        class="form__input"
                        value="<?= htmlspecialchars($user['rank']) ?>"
                    >
                </label>

                <label class="form__field">
                    <span class="form__label">Роль</span>
                    <select name="role" class="form__select">
                        <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>
                            Пользователь
                        </option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>
                            Администратор
                        </option>
                    </select>
                </label>

                <div class="form__actions">
                    <button type="submit" class="btn btn--save">Сохранить</button>
                    <a href="users.php" class="btn btn--cancel">Отмена</a>
                </div>

            </div>
        </form>

</div>

</body>
</html>
