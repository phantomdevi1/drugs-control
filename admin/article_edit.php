<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id'])) {
    die('Не указан материал');
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("
    SELECT id, topic_id, title, content_type, file_path
    FROM articles
    WHERE id = ?
");
$stmt->bind_param('i', $id);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();

if (!$article) {
    die('Материал не найден');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $contentType = $_POST['content_type'];
    $filePath = $article['file_path'];

    /* если загружен новый файл */
    if (!empty($_FILES['file']['name'])) {

        $allowed = [
            'pdf'   => ['application/pdf'],
            'txt'   => ['text/plain'],
            'video' => ['video/mp4','video/webm','video/ogg']
        ];

        if (!isset($allowed[$contentType]) ||
            !in_array($_FILES['file']['type'], $allowed[$contentType])) {
            die('Недопустимый тип файла');
        }

        /* удаляем старый файл */
        if (is_file('../'.$article['file_path'])) {
            unlink('../'.$article['file_path']);
        }

        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $newName = uniqid('article_').'.'.$ext;
        $uploadDir = "../uploads/{$contentType}/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file(
            $_FILES['file']['tmp_name'],
            $uploadDir.$newName
        );

        $filePath = "uploads/{$contentType}/".$newName;
    }

    $upd = $conn->prepare("
        UPDATE articles
        SET title = ?, content_type = ?, file_path = ?
        WHERE id = ?
    ");
    $upd->bind_param('sssi', $title, $contentType, $filePath, $id);
    $upd->execute();

    header('Location: articles.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать материал</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>
<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Редактирование материала</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">
<form method="post" enctype="multipart/form-data" class="form form--admin">

    <div class="form__field">
        <label class="form__label">Название материала</label>
        <input type="text" name="title" class="form__input"
               value="<?= htmlspecialchars($article['title']) ?>" required>
    </div>

    <div class="form__field">
        <label class="form__label">Тип материала</label>
        <select name="content_type" class="form__select">
            <option value="pdf" <?= $article['content_type']=='pdf'?'selected':'' ?>>PDF</option>
            <option value="video" <?= $article['content_type']=='video'?'selected':'' ?>>Видео</option>
        </select>
    </div>

    <div class="form__field">
        <label class="form__label">Текущий файл</label>
        <a href="../<?= $article['file_path'] ?>" target="_blank" class="view_file-btn">
            Посмотреть текущий файл
        </a>
    </div>

    <div class="form__field">
        <label class="form__label">Загрузить новый файл (необязательно)</label>
        <input type="file" name="file" class="form__input">
    </div>

    <div class="form__actions">
        <button class="btn btn--save">Сохранить</button>
        <a href="articles.php?topic_id=<?= $article['topic_id'] ?>" class="btn btn--cancel">Отмена</a>
    </div>

</form>

</div>

</body>
</html>
