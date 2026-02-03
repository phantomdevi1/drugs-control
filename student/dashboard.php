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

// Курсы и прогресс по статьям (для отображения)
$coursesQuery = "
SELECT 
    c.id,
    c.title,
    c.description,
    COUNT(DISTINCT a.id) AS total_articles,
    COUNT(DISTINCT ap.article_id) AS completed_articles
FROM courses c
LEFT JOIN topics t ON t.course_id = c.id
LEFT JOIN articles a ON a.topic_id = t.id
LEFT JOIN article_progress ap 
    ON ap.article_id = a.id AND ap.user_id = ?
WHERE c.is_active = 1
GROUP BY c.id
";

$coursesStmt = $conn->prepare($coursesQuery);
$coursesStmt->bind_param('i', $userId);
$coursesStmt->execute();
$courses = $coursesStmt->get_result();

$coursesArr = [];
while ($course = $courses->fetch_assoc()) {
    $coursesArr[] = $course;
}

// ===== ПРОВЕРКА ДОСТУПА К ИТОГОВОМУ ЭКЗАМЕНУ =====

// Всего активных курсов
$totalCoursesStmt = $conn->query("
    SELECT COUNT(*) AS total
    FROM courses
    WHERE is_active = 1
");
$totalCourses = (int)$totalCoursesStmt->fetch_assoc()['total'];

// Пройденные курсы пользователем
$passedCoursesStmt = $conn->prepare("
    SELECT COUNT(*) AS passed
    FROM course_progress
    WHERE user_id = ?
");
$passedCoursesStmt->bind_param('i', $userId);
$passedCoursesStmt->execute();
$passedCourses = (int)$passedCoursesStmt->get_result()->fetch_assoc()['passed'];

// Итог
$allCoursesCompleted = ($totalCourses > 0 && $totalCourses === $passedCourses);

// ===== ПРОВЕРКА, СДАН ЛИ ИТОГОВЫЙ ЭКЗАМЕН =====
$finalExamStmt = $conn->prepare("
    SELECT COUNT(*) AS passed_final
    FROM test_results
    WHERE user_id = ? AND test_id IS NULL AND passed = 1
");
$finalExamStmt->bind_param('i', $userId);
$finalExamStmt->execute();
$passedFinal = (int)$finalExamStmt->get_result()->fetch_assoc()['passed_final'] > 0;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<header class="student_header">
    <img src="../img/auth_img.png" alt="">
    <h2>
        <?= htmlspecialchars($user['last_name']) ?>
        <?= htmlspecialchars($user['first_name']) ?>
        <?= htmlspecialchars($user['middle_name']) ?>
    </h2>
    <p><?= htmlspecialchars($user['position']) ?>, <?= htmlspecialchars($user['rank']) ?></p>
    <a href="../logout.php">Выйти</a>
</header>

<div class="student_content">

    <!-- Итоговый экзамен -->
    <div style="text-align:center; margin-bottom:30px;">

        <?php if ($passedFinal): ?>
            <!-- Кнопка посмотреть сертификат -->
            <form action="certificate.php" method="post" style="margin-bottom:10px;" target="_blank">
                <button type="submit" class="final_exam_btn">Посмотреть сертификат</button>
            </form>
        <?php endif; ?>

        <a href="<?= $allCoursesCompleted ? 'final_exam.php' : '#' ?>"
           class="final_exam_btn <?= $allCoursesCompleted ? '' : 'disabled' ?>">
            Пройти итоговый экзамен
        </a>

        <?php if (!$allCoursesCompleted): ?>
            <div style="margin-top:8px; color:#666; font-size:14px;">
                Для доступа пройдите все курсы
            </div>
        <?php endif; ?>
    </div>

    <h3>Доступные курсы</h3>

    <div class="curses_block">
        <?php if (empty($coursesArr)): ?>
            <p>Курсы не назначены</p>
        <?php else: ?>
            <?php foreach ($coursesArr as $course):
                $total = (int)$course['total_articles'];
                $completed = (int)$course['completed_articles'];
                $progress = ($total > 0) ? round(($completed / $total) * 100) : 0;
            ?>
                <div class="course-card">
                    <h4><?= htmlspecialchars($course['title']) ?></h4>
                    <p><?= htmlspecialchars($course['description']) ?></p>

                    <div class="progress" data-progress="<?= $progress ?>">
                        <span>Прогресс: <?= $progress ?>%</span>
                    </div>

                    <a href="course.php?id=<?= $course['id'] ?>">
                        Перейти к курсу
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
