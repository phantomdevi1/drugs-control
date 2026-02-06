<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['topic_id'])) {
    echo "<script>alert('Тема не указана'); window.location.href='articles.php';</script>";
    exit;
}

$topic_id = (int)$_GET['topic_id'];

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $contentType = $_POST['content_type'];
    $filePath = '';

    if (empty($title)) {
        $error = 'Введите название материала';
    } elseif (empty($_FILES['file']['name'])) {
        $error = 'Файл обязателен для добавления материала';
    } else {
        $allowed = [
            'pdf'   => ['application/pdf'],
            'txt'   => ['text/plain'],
            'video' => ['video/mp4','video/webm','video/ogg']
        ];

        if (!isset($allowed[$contentType])) {
            $error = 'Недопустимый тип материала';
        } elseif (!in_array($_FILES['file']['type'], $allowed[$contentType])) {
            $error = 'Недопустимый тип файла для выбранного материала';
        } else {
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $newName = uniqid('article_') . '.' . $ext;
            $uploadDir = "../uploads/{$contentType}/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $newName)) {
                $error = 'Ошибка при загрузке файла';
            } else {
                $filePath = "uploads/{$contentType}/" . $newName;
            }
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("
            INSERT INTO articles (topic_id, title, content_type, file_path)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param('isss', $topic_id, $title, $contentType, $filePath);
        $stmt->execute();

        header("Location: articles.php?topic_id=$topic_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить материал</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>
<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Добавление материала</h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="admin-dashboard">

<?php if ($error): ?>
    <script>alert("<?= addslashes($error) ?>");</script>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="form form--admin">

    <div class="form__field">
        <label class="form__label">Название материала</label>
        <input type="text" name="title" class="form__input" value="<?= isset($title) ? htmlspecialchars($title) : '' ?>" required>
    </div>

    <div class="form__field">
        <label class="form__label">Тип материала</label>
        <select name="content_type" class="form__select">
            <option value="pdf" <?= (isset($contentType) && $contentType=='pdf')?'selected':'' ?>>PDF</option>
            <option value="txt" <?= (isset($contentType) && $contentType=='txt')?'selected':'' ?>>Текст</option>
            <option value="video" <?= (isset($contentType) && $contentType=='video')?'selected':'' ?>>Видео</option>
        </select>
    </div>

    <div class="form__field">
        <label class="form__label">Загрузить файл</label>
        <input type="file" name="file" class="form__input" required>
    </div>

    <div class="form__actions">
        <button class="btn btn--save">Сохранить</button>
        <a href="articles.php?topic_id=<?= $topic_id ?>" class="btn btn--cancel">Отмена</a>
    </div>

</form>
</div>

</body>
</html>
