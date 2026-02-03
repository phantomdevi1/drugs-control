<?php
session_start();
if (!isset($_SESSION['user_id'])) header('Location: ../index.php');
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'student') die('Доступ запрещён');

require '../config.php';
$userId = $_SESSION['user_id'];

// Получаем ID курса
$courseId = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
if ($courseId <= 0) die('Курс не выбран');

// Данные курса
$courseStmt = $conn->prepare("SELECT title FROM courses WHERE id = ? AND is_active = 1");
$courseStmt->bind_param('i', $courseId);
$courseStmt->execute();
$course = $courseStmt->get_result()->fetch_assoc();
if (!$course) die('Курс не найден');

// Получаем вопросы и варианты
$query = "
SELECT q.id AS question_id, q.question_text, a.id AS answer_id, a.answer_text, a.is_correct
FROM questions q
JOIN tests t ON q.test_id = t.id
JOIN topics tp ON t.topic_id = tp.id
LEFT JOIN answers a ON a.question_id = q.id
WHERE tp.course_id = ?
ORDER BY q.id, a.id
";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $courseId);
$stmt->execute();
$result = $stmt->get_result();

// Формируем массив вопросов с вариантами
$questions = [];
while ($row = $result->fetch_assoc()) {
    $qid = $row['question_id'];
    if (!isset($questions[$qid])) {
        $questions[$qid] = [
            'text' => $row['question_text'],
            'answers' => []
        ];
    }
    if ($row['answer_id']) {
        $questions[$qid]['answers'][] = [
            'id' => $row['answer_id'],
            'text' => $row['answer_text'],
            'is_correct' => $row['is_correct']
        ];
    }
}

// Обработка отправки формы
$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userAnswers = $_POST['answer'] ?? [];
    $totalQuestions = count($questions);
    $correctAnswers = 0;

    foreach ($questions as $qid => $q) {
        if (isset($userAnswers[$qid])) {
            foreach ($q['answers'] as $ans) {
                if ($ans['id'] == $userAnswers[$qid] && $ans['is_correct']) {
                    $correctAnswers++;
                }
            }
        }
    }

if ($totalQuestions > 0 && ($correctAnswers / $totalQuestions) >= 0.7) {
    $success = true;

    // Отмечаем курс пройденным
    $check = $conn->prepare("SELECT id FROM course_progress WHERE user_id = ? AND course_id = ?");
    $check->bind_param('ii', $userId, $courseId);
    $check->execute();
    $checkResult = $check->get_result();
    
    if ($checkResult->num_rows === 0) {
        $insert = $conn->prepare("INSERT INTO course_progress (user_id, course_id, passed_at) VALUES (?, ?, NOW())");
        $insert->bind_param('ii', $userId, $courseId);
        $insert->execute();
    }
}
 else {
        $errors[] = "Вы ответили правильно на $correctAnswers из $totalQuestions вопросов. Для прохождения нужно 70% и выше.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Тест курса: <?= htmlspecialchars($course['title']) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../style.css">
</head>
<body>
<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>Тест по курсу: <?= htmlspecialchars($course['title']) ?></h2>
    <img src="../img/auth_img.png" alt="" class="header_logo_right">
</header>

<div class="student_content">
    <div style="margin: 20px 0;">
        <a href="course.php?id=<?= $courseId ?>" class="back_btn">← Назад к курсу</a>
    </div>

    <?php if ($success): ?>
        <div style="padding:20px; background-color:#d4edda; color:#155724; border-radius:8px; margin-bottom:20px;">
            Поздравляем! Курс успешно пройден.
        </div>
        <div style="text-align:center;">
            <a href="dashboard.php" class="final_exam_btn">Вернуться в личный кабинет</a>
        </div>
    <?php else: ?>
        <?php if ($errors): ?>
            <div style="padding:20px; background-color:#f8d7da; color:#721c24; border-radius:8px; margin-bottom:20px;">
                <?php foreach ($errors as $err) echo htmlspecialchars($err) . "<br>"; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <?php $qNum = 1; ?>
            <?php foreach ($questions as $qid => $q): ?>
                <div class="question-block">
                    <p><strong>Вопрос <?= $qNum ?>:</strong> <?= htmlspecialchars($q['text']) ?></p>
                    <?php foreach ($q['answers'] as $ans): ?>
                        <label>
                            <input type="radio" name="answer[<?= $qid ?>]" value="<?= $ans['id'] ?>">
                            <?= htmlspecialchars($ans['text']) ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>
                <hr>
                <?php $qNum++; ?>
            <?php endforeach; ?>
            <button type="submit" class="final_exam_btn">Отправить ответы</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
ч