<?php
session_start();


require '../config.php';

// здесь предполагаем, что доступ только у администратора
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Получаем список пользователей
$result = $conn->query("
    SELECT id, personal_number, last_name, first_name, middle_name, position, rank, role, created_at
    FROM users
    ORDER BY created_at DESC
");
?>
<?php if (!empty($_SESSION['error'])): ?>
<script>
    alert('<?= htmlspecialchars($_SESSION['error']) ?>');
</script>
<?php unset($_SESSION['error']); endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
<script>
    alert('<?= htmlspecialchars($_SESSION['success']) ?>');
</script>
<?php unset($_SESSION['success']); endif; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Пользователи системы</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>
      <header class="student_header">
        <img src="../img/auth_img.png" alt="">
        <h2>Пользователи системы</h2>
        <img src="../img/auth_img.png" alt="" class="header_logo_right">
    </header>

<div class="admin-dashboard">
    <div style="margin: 20px 0;">
        <a href="dashboard.php" class="back_btn">← Назад</a>
    </div>
    <section class="admin-dashboard__section">
        <h3 class="admin-dashboard__title">Пользователи системы</h3>
        
            <a href="user_add.php" class="add_users-btn">+ Добавить пользователя</a>
        
        <div class="users-table-wrapper">
            <table class="users-table">
                <thead class="users-table__head">
                    <tr class="users-table__row">
                        <th>Личный номер</th>
                        <th>ФИО</th>
                        <th>Должность</th>
                        <th>Звание</th>
                        <th>Роль</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody class="users-table__body">
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr class="users-table__row">
                        <td><?= htmlspecialchars($user['personal_number']) ?></td>

                        <td>
                            <?= htmlspecialchars(
                                $user['last_name'].' '.
                                $user['first_name'].' '.
                                $user['middle_name']
                            ) ?>
                        </td>
                        <td><?= htmlspecialchars($user['position']) ?></td>
                        <td><?= htmlspecialchars($user['rank']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= date('d.m.Y', strtotime($user['created_at'])) ?></td>
                        <td class="users-table__actions">
                            <a href="user_edit.php?id=<?= $user['id'] ?>" class="btn btn--edit">Редактировать</a>
                            <a href="user_delete.php?id=<?= $user['id'] ?>"
                               class="btn btn--delete"
                               onclick="return confirm('Удалить пользователя?')">
                               Удалить
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

</div>

</body>
</html>
