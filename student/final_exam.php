<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'student') {
    die('Доступ запрещён');
}

require '../config.php';

$userId = $_SESSION['user_id'];

// Данные пользователя
$userStmt = $conn->prepare("
    SELECT last_name, first_name, middle_name, position, rank
    FROM users
    WHERE id = ?
");
$userStmt->bind_param('i', $userId);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

// Получаем 20 случайных вопросов из всех курсов
$questionsQuery = "
    SELECT q.id, q.question_text
    FROM questions q
    JOIN tests t ON q.test_id = t.id
    JOIN topics tp ON t.topic_id = tp.id
    JOIN courses c ON tp.course_id = c.id
    ORDER BY RAND()
    LIMIT 20
";
$questionsResult = $conn->query($questionsQuery);

// Получаем ответы для этих вопросов
$questions = [];
$questionIds = [];
while ($q = $questionsResult->fetch_assoc()) {
    $questions[$q['id']] = [
        'text' => $q['question_text'],
        'answers' => []
    ];
    $questionIds[] = $q['id'];
}

if (!empty($questionIds)) {
    $ids = implode(',', $questionIds);
    $answersResult = $conn->query("
        SELECT question_id, answer_text, id, is_correct
        FROM answers
        WHERE question_id IN ($ids)
        ORDER BY RAND()
    ");
    while ($a = $answersResult->fetch_assoc()) {
        $questions[$a['question_id']]['answers'][] = [
            'id' => $a['id'],
            'text' => $a['answer_text'],
            'is_correct' => $a['is_correct']
        ];
    }
}

// Обработка отправки формы
$errors = [];
$success = false;
$score = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userAnswers = $_POST['answer'] ?? [];
    $totalQuestions = count($questions);
    $correctAnswers = 0;

    foreach ($questions as $qId => $q) {
        if (isset($userAnswers[$qId])) {
            foreach ($q['answers'] as $a) {
                if ($a['id'] == $userAnswers[$qId] && $a['is_correct']) {
                    $correctAnswers++;
                    break;
                }
            }
        }
    }

    $score = round(($correctAnswers / $totalQuestions) * 100);
    $passed = ($score >= 70) ? 1 : 0;

    // Сохраняем результат итогового экзамена
    $stmt = $conn->prepare("
        INSERT INTO test_results (user_id, test_id, score, passed, attempt, completed_at)
        VALUES (?, NULL, ?, ?, 1, NOW())
    ");
    $stmt->bind_param('iii', $userId, $score, $passed);
    $stmt->execute();

    $success = true;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Итоговый экзамен</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../style.css">
<link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>
<header class="student_header">
    <h2>Итоговый экзамен</h2>
    <p><?= htmlspecialchars($user['last_name'].' '.$user['first_name'].' '.$user['middle_name']) ?></p>
    <a href="../logout.php">Выйти</a>
</header>

<div class="student_content">
    <div style="margin: 20px 0;">
        <a href="dashboard.php" class="back_btn">← Назад</a>
    </div>

    <?php if ($success): ?>
        <div style="padding:20px; background-color:<?= $passed ? '#d4edda' : '#f8d7da' ?>; color:<?= $passed ? '#155724' : '#721c24' ?>; border-radius:8px; margin-bottom:20px;">
            <strong>Вы <?= $passed ? 'сдали' : 'не сдали' ?> экзамен.</strong><br>
            Ваш результат: <?= $score ?>%
        </div>
        <?php if ($passed): ?>
            <div style="text-align:center; margin-bottom: 20px;">
                <form action="certificate.php" method="post">
                    <button type="submit" class="final_exam_btn">Скачать сертификат</button>
                </form>
            </div>
        <?php endif; ?>
        <div style="text-align:center;">
            <a href="dashboard.php" class="final_exam_btn">Вернуться в личный кабинет</a>
        </div>
    <?php else: ?>
        <?php if (empty($questions)): ?>
            <p>Вопросы пока не добавлены.</p>
        <?php else: ?>
            <form method="post">
                <?php $num = 1; ?>
                <?php foreach ($questions as $qId => $q): ?>
                    <div class="question-block">
                        <p><strong>Вопрос <?= $num ?>:</strong> <?= htmlspecialchars($q['text']) ?></p>
                        <?php foreach ($q['answers'] as $a): ?>
                            <label>
                                <input type="radio" name="answer[<?= $qId ?>]" value="<?= $a['id'] ?>"> <?= htmlspecialchars($a['text']) ?>
                            </label><br>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                    <?php $num++; ?>
                <?php endforeach; ?>
                <button type="submit" class="final_exam_btn">Отправить ответы</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
