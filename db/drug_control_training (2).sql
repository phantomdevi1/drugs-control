-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 24 2026 г., 11:30
-- Версия сервера: 5.7.39
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `drug_control_training`
--

-- --------------------------------------------------------

--
-- Структура таблицы `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer_text` text COLLATE utf8mb4_unicode_ci,
  `is_correct` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer_text`, `is_correct`) VALUES
(1, 1, 'Нарушается координация, появляются шаткость и неуверенные движения', 1),
(2, 1, 'Координация улучшается', 0),
(3, 1, 'Движения остаются без изменений', 0),
(4, 2, 'Часто нарушается давление и частота сердечных сокращений', 1),
(5, 2, 'Показатели остаются в норме', 0),
(6, 2, 'Улучшается острота зрения', 0),
(7, 3, 'Снижение веса и хроническая усталость могут свидетельствовать', 1),
(8, 3, 'Повышение физической активности', 0),
(9, 3, 'Улучшение памяти', 0),
(10, 4, 'Беспокойство, гиперактивность, расширенные зрачки', 1),
(11, 4, 'Сонливость и апатия', 0),
(12, 4, 'Снижение давления', 0),
(13, 5, 'Расширенные зрачки', 1),
(14, 5, 'Суженные зрачки', 0),
(15, 5, 'Нет изменений', 0),
(16, 6, 'Температура может слегка повышаться', 1),
(17, 6, 'Температура снижается', 0),
(18, 6, 'Температура не меняется', 0),
(19, 7, 'Покраснение глаз, расслабленность, замедленная реакция', 1),
(20, 7, 'Сухость кожи и учащение дыхания', 0),
(21, 7, 'Нарушение речи', 0),
(22, 8, 'Поведение становится более расслабленным и замедленным', 1),
(23, 8, 'Повышение агрессии', 0),
(24, 8, 'Чрезмерная активность', 0),
(25, 9, 'Повышение аппетита, учащение сердцебиения', 1),
(26, 9, 'Снижение давления', 0),
(27, 9, 'Отсутствие изменений', 0),
(28, 10, 'Задержка внимания, неустойчивость в движениях', 1),
(29, 10, 'Повышение внимательности', 0),
(30, 10, 'Полная неподвижность', 0),
(31, 11, 'Красные глаза, потливость, возбуждение', 1),
(32, 11, 'Бледность и сонливость', 0),
(33, 11, 'Слабая координация', 0),
(34, 12, 'Визуальный осмотр и тест на реакцию зрачка', 1),
(35, 12, 'Полное игнорирование состояния', 0),
(36, 12, 'Проверка по часам', 0),
(37, 13, 'Гиперактивность, потливость, учащенное сердцебиение', 1),
(38, 13, 'Сонливость и апатия', 0),
(39, 13, 'Пониженный пульс', 0),
(40, 14, 'Беспокойство, резкие движения', 1),
(41, 14, 'Снижение активности', 0),
(42, 14, 'Полная неподвижность', 0),
(43, 15, 'Повышение давления и частоты дыхания', 1),
(44, 15, 'Нормальные показатели', 0),
(45, 15, 'Снижение давления', 0),
(46, 16, 'Проверки мест хранения и анализ каналов сбыта', 1),
(47, 16, 'Игнорирование подозрительных действий', 0),
(48, 16, 'Случайные проверки без планирования', 0),
(49, 17, 'Конфискация, предупреждения, аресты', 1),
(50, 17, 'Разрешение распространения', 0),
(51, 17, 'Обход законов', 0),
(52, 18, 'МВД, прокуратура, полиция', 1),
(53, 18, 'Только частные лица', 0),
(54, 18, 'Не требуется взаимодействия', 0),
(55, 19, 'Изучение действия веществ на организм', 1),
(56, 19, 'Игнорирование токсикологии', 0),
(57, 19, 'Только психология', 0),
(58, 20, 'Совместная работа с медэкспертами', 1),
(59, 20, 'Игнорирование медэкспертов', 0),
(60, 20, 'Работа только с коллегами', 0),
(61, 21, 'Анализы крови и мочи', 1),
(62, 21, 'Ничего не проверять', 0),
(63, 21, 'Случайные наблюдения', 0),
(64, 22, 'Заполнять протоколы корректно и полно', 1),
(65, 22, 'Игнорировать детали', 0),
(66, 22, 'Писать что попало', 0),
(67, 23, 'Отчеты по каждому случаю опьянения', 1),
(68, 23, 'Не делать отчеты', 0),
(69, 23, 'Отчеты только по желанию', 0),
(70, 24, 'Хранить документы в безопасном месте и передавать в установленном порядке', 1),
(71, 24, 'Оставлять документы на столе', 0),
(72, 24, 'Отправлять любому сотруднику', 0),
(73, 25, 'МВД, прокуратура, медики', 1),
(74, 25, 'Только медики', 0),
(75, 25, 'Никто не участвует', 0),
(76, 26, 'Правильное взаимодействие и координация действий', 1),
(77, 26, 'Игнорировать инструкции', 0),
(78, 26, 'Действовать самостоятельно', 0),
(79, 27, 'Планирование, координация, совместные операции', 1),
(80, 27, 'Отдельные действия без согласования', 0),
(81, 27, 'Без плана', 0),
(82, 25, 'ntcn jndtnf', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_type` enum('pdf','video','text') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_index` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `articles`
--

INSERT INTO `articles` (`id`, `topic_id`, `title`, `content_type`, `file_path`, `order_index`) VALUES
(1, 1, 'Опьянение каннабисом', 'pdf', '/uploads/pdf/cannabis.pdf', 1),
(2, 1, 'Опьянение кокаином', 'pdf', '/uploads/pdf/cocaine.pdf', 2),
(3, 1, 'Опьянение мефедроном', 'video', '/uploads/video/mefedron.mp4', 3),
(7, 2, 'Признаки употребления психостимуляторов', 'pdf', '/uploads/pdf/stimulants.pdf', 1),
(8, 2, 'Определение состояния опьянения на месте', 'video', '/uploads/video/observation.mp4', 2),
(9, 2, 'Алгоритм проверки', 'text', '/uploads/text/all_detections.txt', 3),
(10, 3, 'Методы выявления каннабиса', 'pdf', '/uploads/pdf/cannabis.pdf', 1),
(11, 3, 'Документирование случаев', 'pdf', '/uploads/pdf/document_cannabis.pdf', 2),
(12, 4, 'Признаки употребления кокаина', 'pdf', '/uploads/video/cocaine_signs.mp4', 1),
(13, 4, 'Правильное оформление протоколов', 'pdf', '/uploads/pdf/protocol.pdf', 2),
(14, 5, 'Признаки употребления мефедрона', 'video', '/uploads/video/mefedron.mp4', 1),
(15, 5, 'Методы проверки', 'video', '/uploads/video/mefedron_check.mp4', 2),
(16, 5, 'Оформление протоколов', 'pdf', '/uploads/pdf/protocol_primer.pdf', 3),
(17, 6, 'Методы пресечения незаконного оборота', 'pdf', '/uploads/pdf/drug_control.pdf', 1),
(18, 6, 'Тактика работы на местах', 'text', '/uploads/text/tactical.txt', 2),
(19, 7, 'Основы токсикологии', 'video', '/uploads/video/tocsik_base.mp4', 1),
(20, 7, 'Взаимодействие с медэкспертами', 'pdf', '/uploads/pdf/med_experts.pdf', 2),
(21, 8, 'Оформление протоколов', 'pdf', '/uploads/pdf/protocol_primer.pdf', 1),
(22, 8, 'Составление отчетов', 'pdf', '/uploads/pdf/otchet.pdf', 2),
(23, 9, 'Координация с МВД', 'pdf', '/uploads/pdf/mvd.pdf', 1),
(24, 9, 'Взаимодействие с медиками и прокуратурой', 'text', '/uploads/text/coordination.txt', 2),
(25, 3, 'Алгоритм проверки употребления каннабиса', 'pdf', '/uploads/pdf/cannabis_check.pdf', 1),
(26, 4, 'Методы выявления употребления кокаина', 'pdf', '/uploads/pdf/cocaine_detection.pdf', 1),
(27, 5, 'Признаки употребления мефедрона', 'text', '/uploads/text/mefedron_signs.txt', 1),
(28, 6, 'Методы пресечения незаконного оборота', 'video', '/uploads/video/drug_control_methods.mp4', 1),
(29, 7, 'Основы токсикологии', 'text', '/uploads/text/toxicology_basics.txt', 1),
(30, 8, 'Правильное оформление протоколов', 'text', '/uploads/text/reporting_rules.txt', 1),
(31, 9, 'Скоординированные действия с МВД и медэкспертами', 'text', '/uploads/text/interagency_cooperation.txt', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `article_progress`
--

CREATE TABLE `article_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `is_completed` tinyint(4) DEFAULT '0',
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `article_progress`
--

INSERT INTO `article_progress` (`id`, `user_id`, `article_id`, `is_completed`, `completed_at`) VALUES
(32, 3, 1, 1, '2026-02-03 17:41:41'),
(33, 3, 2, 1, '2026-02-03 17:41:41'),
(34, 3, 3, 1, '2026-02-03 17:41:41'),
(35, 3, 7, 1, '2026-02-03 17:41:41'),
(36, 3, 8, 1, '2026-02-03 17:41:41'),
(37, 3, 9, 1, '2026-02-03 17:41:41'),
(38, 3, 10, 1, '2026-02-03 17:41:41'),
(39, 3, 11, 1, '2026-02-03 17:41:41'),
(40, 3, 25, 1, '2026-02-03 17:41:41'),
(41, 3, 12, 1, '2026-02-03 17:41:41'),
(42, 3, 13, 1, '2026-02-03 17:41:41'),
(43, 3, 26, 1, '2026-02-03 17:41:41'),
(44, 3, 14, 1, '2026-02-03 17:41:41'),
(45, 3, 15, 1, '2026-02-03 17:41:41'),
(46, 3, 16, 1, '2026-02-03 17:41:41'),
(47, 3, 27, 1, '2026-02-03 17:41:41'),
(48, 3, 17, 1, '2026-02-03 17:41:41'),
(49, 3, 18, 1, '2026-02-03 17:41:41'),
(50, 3, 28, 1, '2026-02-03 17:41:41'),
(51, 3, 19, 1, '2026-02-03 17:41:41'),
(52, 3, 20, 1, '2026-02-03 17:41:41'),
(53, 3, 29, 1, '2026-02-03 17:41:41'),
(54, 3, 21, 1, '2026-02-03 17:41:41'),
(55, 3, 22, 1, '2026-02-03 17:41:41'),
(56, 3, 30, 1, '2026-02-03 17:41:41'),
(57, 3, 23, 1, '2026-02-03 17:41:41'),
(58, 3, 24, 1, '2026-02-03 17:41:41'),
(59, 3, 31, 1, '2026-02-03 17:41:41'),
(60, 4, 1, 0, '2026-02-04 12:43:49'),
(61, 4, 22, 0, '2026-02-04 12:44:12'),
(62, 4, 30, 0, '2026-02-04 12:44:16'),
(63, 4, 21, 0, '2026-02-04 12:44:42');

-- --------------------------------------------------------

--
-- Структура таблицы `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `is_active`) VALUES
(1, 'Подготовка сотрудников', 'Базовая подготовка по выявлению наркотического опьянения', 1),
(2, 'Определение состояния наркотического опьянения', 'Как распознавать признаки опьянения различными веществами', 1),
(3, 'Работа с каннабисом', 'Особенности выявления и документирования случаев употребления каннабиса', 1),
(4, 'Работа с кокаином', 'Определение признаков употребления кокаина и меры реагирования', 1),
(5, 'Работа с мефедроном', 'Признаки, методы проверки и оформление протоколов', 1),
(6, 'Противодействие распространению наркотиков', 'Методы выявления и пресечения незаконного оборота', 1),
(7, 'Токсикология и медицинская экспертиза', 'Основы токсикологии, взаимодействие с медэкспертами', 1),
(8, 'Документирование и отчётность', 'Правильное оформление протоколов и отчетов по фактам опьянения', 1),
(9, 'Взаимодействие с другими ведомствами', 'Скоординированные действия с МВД, медиками и прокуратурой', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `course_progress`
--

CREATE TABLE `course_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `passed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `course_progress`
--

INSERT INTO `course_progress` (`id`, `user_id`, `course_id`, `passed_at`) VALUES
(12, 3, 1, '2026-02-03 17:40:27'),
(13, 3, 2, '2026-02-03 17:40:27'),
(14, 3, 3, '2026-02-03 17:40:27'),
(15, 3, 4, '2026-02-03 17:40:27'),
(16, 3, 5, '2026-02-03 17:40:27'),
(17, 3, 6, '2026-02-03 17:40:27'),
(18, 3, 7, '2026-02-03 17:40:27'),
(19, 3, 8, '2026-02-03 17:40:27'),
(20, 3, 9, '2026-02-03 17:40:27'),
(21, 4, 8, '2026-02-04 12:44:59');

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `test_id` int(11) DEFAULT NULL,
  `question_text` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`id`, `test_id`, `question_text`) VALUES
(1, 1, 'Как меняется координация движений при опьянении?'),
(2, 1, 'Какой физиологический показатель часто нарушается?'),
(3, 1, 'Что может свидетельствовать о длительном употреблении веществ?'),
(4, 2, 'Какие признаки опьянения кокаином наиболее характерны?'),
(5, 2, 'Какая реакция зрачков чаще всего наблюдается?'),
(6, 2, 'Как изменяется температура тела при употреблении кокаина?'),
(7, 3, 'Какие признаки употребления каннабиса наиболее явные?'),
(8, 3, 'Что обычно изменяется в поведении человека под воздействием каннабиса?'),
(9, 3, 'Какие физиологические реакции можно заметить при каннабисе?'),
(10, 4, 'Как проявляется употребление кокаина на работе?'),
(11, 4, 'Какие внешние признаки можно заметить при употреблении кокаина?'),
(12, 4, 'Какие методы проверки состояния рекомендуются?'),
(13, 5, 'Какие признаки употребления мефедрона наиболее выражены?'),
(14, 5, 'Что меняется в поведении человека при употреблении мефедрона?'),
(15, 5, 'Какие физиологические показатели можно проверить?'),
(16, 6, 'Какие методы выявления незаконного оборота наркотиков существуют?'),
(17, 6, 'Какие меры пресечения распространения эффективны?'),
(18, 6, 'Какие организации участвуют в противодействии?'),
(19, 7, 'Что изучается в токсикологии?'),
(20, 7, 'Как взаимодействовать с медицинскими экспертами?'),
(21, 7, 'Какие анализы позволяют подтвердить опьянение?'),
(22, 8, 'Как правильно оформлять протоколы?'),
(23, 8, 'Какие отчеты необходимы при выявлении фактов опьянения?'),
(24, 8, 'Как хранить и передавать документы по случаям опьянения?'),
(25, 9, 'Какие ведомства участвуют в скоординированных действиях?'),
(26, 9, 'Как правильно взаимодействовать с МВД и прокуратурой?'),
(27, 9, 'Какие шаги предпринимаются при совместных операциях?');

-- --------------------------------------------------------

--
-- Структура таблицы `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `is_final` tinyint(4) DEFAULT '0',
  `passing_score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tests`
--

INSERT INTO `tests` (`id`, `topic_id`, `is_final`, `passing_score`) VALUES
(1, 1, 0, 70),
(2, 2, 0, 70),
(3, 3, 0, 70),
(4, 4, 0, 70),
(5, 5, 0, 70),
(6, 6, 0, 70),
(7, 7, 0, 70),
(8, 8, 0, 70),
(9, 9, 0, 70);

-- --------------------------------------------------------

--
-- Структура таблицы `test_results`
--

CREATE TABLE `test_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `test_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `passed` tinyint(4) DEFAULT NULL,
  `attempt` int(11) DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `test_results`
--

INSERT INTO `test_results` (`id`, `user_id`, `test_id`, `score`, `passed`, `attempt`, `completed_at`) VALUES
(3, 3, 1, 100, 1, 1, '2026-02-03 17:42:02'),
(4, 3, 2, 100, 1, 1, '2026-02-03 17:42:02'),
(5, 3, 3, 100, 1, 1, '2026-02-03 17:42:02'),
(6, 3, 4, 100, 1, 1, '2026-02-03 17:42:02'),
(7, 3, 5, 100, 1, 1, '2026-02-03 17:42:02'),
(8, 3, 6, 100, 1, 1, '2026-02-03 17:42:02'),
(9, 3, 7, 100, 1, 1, '2026-02-03 17:42:02'),
(10, 3, 8, 100, 1, 1, '2026-02-03 17:42:02'),
(11, 3, 9, 100, 1, 1, '2026-02-03 17:42:02'),
(18, 3, NULL, 95, 1, 1, '2026-02-03 17:42:43');

-- --------------------------------------------------------

--
-- Структура таблицы `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `order_index` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `topics`
--

INSERT INTO `topics` (`id`, `course_id`, `title`, `description`, `order_index`) VALUES
(1, 1, 'Определение состояния наркотического опьянения', 'Признаки и методы определения различных веществ', 1),
(2, 2, 'Признаки опьянения различными веществами', 'Определение состояния наркотического опьянения', 1),
(3, 3, 'Работа с каннабисом', 'Особенности выявления и документирования случаев употребления каннабиса', 1),
(4, 4, 'Работа с кокаином', 'Определение признаков употребления кокаина и меры реагирования', 1),
(5, 5, 'Работа с мефедроном', 'Признаки, методы проверки и оформление протоколов', 1),
(6, 6, 'Противодействие распространению наркотиков', 'Методы выявления и пресечения незаконного оборота', 1),
(7, 7, 'Токсикология и медицинская экспертиза', 'Основы токсикологии, взаимодействие с медэкспертами', 1),
(8, 8, 'Документирование и отчётность', 'Правильное оформление протоколов и отчетов по фактам опьянения', 1),
(9, 9, 'Взаимодействие с другими ведомствами', 'Скоординированные действия с МВД, медиками и прокуратурой', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `personal_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middle_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `passport_encrypted` text COLLATE utf8mb4_unicode_ci,
  `position` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rank` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('student','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `personal_number`, `last_name`, `first_name`, `middle_name`, `birth_date`, `passport_encrypted`, `position`, `rank`, `role`, `password_hash`, `created_at`) VALUES
(1, 'ADM-0001', 'Александров', 'Станислав', 'Васильевич', '1980-01-01', NULL, 'Администратор системы', 'майор полции', 'admin', '$2y$10$yI57BYSbqY90StZFWE402ei5WgAs8uorWL/blhCz4oMA/wJmnzxLC', '2026-02-01 09:05:41'),
(3, 'ST-0002', 'Петров', 'Пётр', 'Петрович', '1990-08-15', NULL, 'Стажёр', 'ефрейтор', 'student', '$2y$10$WG6hXpqkW1/ejP9wKKh4/e4FC.QKL//4ylgNwstBs939bjarcZsPS', '2026-02-03 12:35:26'),
(4, 'PN-004', 'Петров', 'Сергей', 'Александрович', '1995-07-12', 'encrypted_passport_data', 'Оперуполномоченный', 'старший лейтенант полиции', 'student', '$2y$10$WG6hXpqkW1/ejP9wKKh4/e4FC.QKL//4ylgNwstBs939bjarcZsPS', '2026-02-04 09:34:43'),
(5, 'РМ-08935', 'Сурков', 'Дмитрий', 'Алексеевич', NULL, NULL, 'Начальник оперативного отдела', 'полковник', 'student', '$2y$10$WG6hXpqkW1/ejP9wKKh4/e4FC.QKL//4ylgNwstBs939bjarcZsPS', '2026-02-05 11:51:24');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Индексы таблицы `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Индексы таблицы `article_progress`
--
ALTER TABLE `article_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_progress` (`user_id`,`article_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Индексы таблицы `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `course_progress`
--
ALTER TABLE `course_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_progress` (`user_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- Индексы таблицы `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Индексы таблицы `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `test_results_ibfk_1` (`user_id`);

--
-- Индексы таблицы `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_personal_number` (`personal_number`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT для таблицы `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `article_progress`
--
ALTER TABLE `article_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT для таблицы `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `course_progress`
--
ALTER TABLE `course_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `test_results`
--
ALTER TABLE `test_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `article_progress`
--
ALTER TABLE `article_progress`
  ADD CONSTRAINT `article_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `article_progress_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Ограничения внешнего ключа таблицы `course_progress`
--
ALTER TABLE `course_progress`
  ADD CONSTRAINT `course_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `course_progress_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tests`
--
ALTER TABLE `tests`
  ADD CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `test_results`
--
ALTER TABLE `test_results`
  ADD CONSTRAINT `test_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `test_results_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`);

--
-- Ограничения внешнего ключа таблицы `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
