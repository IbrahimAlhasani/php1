-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 19 أكتوبر 2024 الساعة 18:48
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `science_forum`
--

-- --------------------------------------------------------

--
-- بنية الجدول `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `content`, `created_at`) VALUES
(1, 1, 'ععهه', '2024-10-03 20:39:25'),
(2, 1, 'ععهه', '2024-10-03 20:46:26'),
(3, 5, 'jkk', '2024-10-03 21:42:08'),
(4, 6, 'jbjb', '2024-10-05 09:39:58'),
(5, 7, 'الدكور علي حيدر: \"هذا المنشور معجبناااااش والموقع تحريضي\"', '2024-10-05 10:18:27'),
(6, 7, 'ابراهيم البكاري:اتفق معك يابش مهندس', '2024-10-05 10:19:34'),
(7, 8, 'رسام ما بيعلق عليك', '2024-10-08 14:04:44');

-- --------------------------------------------------------

--
-- بنية الجدول `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `likes` int(11) DEFAULT 0,
  `dislikes` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `posts`
--

INSERT INTO `posts` (`id`, `content`, `created_at`, `likes`, `dislikes`, `image`) VALUES
(1, 'السلام عليك\r\nم\r\n\r\n\r\n', '2024-10-03 20:38:57', 1, 0, NULL),
(2, 'هذا منشوري الثاني برا برا برا', '2024-10-03 20:50:54', 0, 0, NULL),
(5, 'ياحيا ومرحبا بكوووووم بالموقع حقناااااا', '2024-10-03 21:29:01', 3, 0, NULL),
(6, 'hfvhgfgcfyf', '2024-10-05 09:39:45', 5, 0, NULL),
(7, 'السلام عليكم \r\nهذا المنتدى مستقل تم انشائه كم قبل الطالب \"رسام طلعت رسام مهيوب ابراهيم\" \r\nولا علاقة له بالجامعة كـتحكم او ادارة', '2024-10-05 10:16:02', 126, 1, NULL),
(8, 'هذا المنشور نشره زكريا', '2024-10-08 14:04:24', 2, 2, NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `reactions`
--

CREATE TABLE `reactions` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reaction_type` enum('like','dislike') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `number_login` bigint(30) NOT NULL,
  `pass_login` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` enum('منتظم','غير منتظم') DEFAULT NULL,
  `current_level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `number_login`, `pass_login`, `name`, `email`, `status`, `current_level`) VALUES
(1, 202211300152, 123456, 'زكريا عبدالله عبدالله علي أغا', 'Zakariaaagha667788@gmail.com', 'منتظم', 3),
(2, 202211300003, 12345, 'رسام طلعت رسام', 'rassamtrassam@gmail.com', 'منتظم', 3),
(3, 202211300247, 1234567, 'احمد امین محمد محمد', 'ahmadaminm@example.com', 'غير منتظم', 3),
(4, 202311302914, 1234567, 'احمد مروان احمد الاصبحي', 'ahmadmarwan@example.com', 'منتظم', 3),
(5, 202311302117, 1234567, 'اریام فكري عبدالمجید ناشر', 'aryam@example.com', 'منتظم', 3),
(6, 202311302116, 1234567, 'اریج مفید عبدالغني عبدالله', 'arij@example.com', 'منتظم', 3),
(7, 202311303272, 1234567, 'اصیل ولید علي قائد', 'aseel@example.com', 'منتظم', 3),
(8, 202311302120, 1234567, 'افنان یحیى محمد ھمام', 'afnan@example.com', 'منتظم', 3),
(9, 202311302110, 1234567, 'البراء عبدالخالق عبدالله ردمان', 'albaraa@example.com', 'منتظم', 3),
(10, 202311302115, 1234567, 'العز عبدالسلام قائد ناصر', 'alazz@example.com', 'منتظم', 3),
(11, 202311302113, 1234567, 'امیمة امین احمد مرعي مھیوب', 'umeema@example.com', 'منتظم', 3),
(12, 202311302121, 1234567, 'انفال محمد احمد الیوسفي', 'anfal@example.com', 'منتظم', 3),
(13, 202311302232, 1234567, 'ایاد منصور احمد عبده الصراري', 'eyad@example.com', 'منتظم', 3),
(14, 202110201204, 1234567, 'ایمن خالد حسین العسلي', 'ayman@example.com', 'منتظم', 3),
(15, 202311302099, 1234567, 'بشیر عبده احمد سلام', 'bashir@example.com', 'منتظم', 3),
(16, 202311302916, 1234567, 'بلال محمد نجیب علي احمد', 'bilal@example.com', 'منتظم', 3),
(17, 202311302233, 1234567, 'حذیفھ عادل فیصل حسن', 'hudhifa@example.com', 'منتظم', 3),
(18, 202311303113, 1234567, 'حسام عبدالسلام مقبل سیف', 'hossam@example.com', 'منتظم', 3),
(19, 202311302123, 1234567, 'حسان محمد حسان محمد عبدالله', 'hassan@example.com', 'منتظم', 3),
(20, 202311302231, 1234567, 'حسین فھمي عقلان خالد غالب', 'hussein@example.com', 'منتظم', 3),
(21, 202311302234, 1234567, 'حمدي محمد خضر ثابت', 'hamdi@example.com', 'منتظم', 3),
(22, 202311302237, 1234567, 'خالد عبدالله احمد سعید', 'khaled@example.com', 'منتظم', 3),
(23, 202311302236, 1234567, 'خلیفھ طاھر محمد سیف', 'khaleefa@example.com', 'منتظم', 3),
(24, 202311302240, 1234567, 'ذانون علي امین احمد غالب', 'dhanoun@example.com', 'منتظم', 3),
(25, 202311302112, 1234567, 'رافت محمد عبدالعزیز عبده', 'rafat@example.com', 'منتظم', 3),
(26, 202311302239, 1234567, 'رامز طلال عبده ھزاع', 'ramez@example.com', 'منتظم', 3),
(27, 202311302241, 1234567, 'روؤف عبدالباسط حازم محمد', 'raouf@example.com', 'منتظم', 3),
(28, 202311302107, 1234567, 'زكریا عبده قائد سعید', 'zakaria@example.com', 'منتظم', 3),
(29, 202311302104, 1234567, 'زیاد عبدالله مارش ھزاع', 'ziad@example.com', 'منتظم', 3),
(30, 202311302243, 1234567, 'سامي ولید مصطفى عبدالرحمن', 'sami@example.com', 'منتظم', 3),
(31, 202311302882, 1234567, 'سمیر منصور محمد غالب', 'sameer@example.com', 'منتظم', 3),
(32, 202311302244, 1234567, 'شمس جمال حسن علي الجھیم', 'shams@example.com', 'منتظم', 3),
(33, 202311302245, 1234567, 'شھاب خالد محمد حیدر قحطان', 'shahab@example.com', 'منتظم', 3),
(34, 202311302246, 1234567, 'صابرین عبدالقوي محمد خالد', 'saber@example.com', 'منتظم', 3),
(35, 202311302247, 1234567, 'صلاح الدین غالب محمد صالح الحبشي', 'salah@example.com', 'منتظم', 3),
(36, 202311302890, 1234567, 'طارق علي علي قاید الفاطمي', 'tareq@example.com', 'منتظم', 3),
(37, 202311302108, 1234567, 'عاصم عبده محمد علي حسن', 'asim@example.com', 'منتظم', 3),
(38, 202311302106, 1234567, 'عبدالرحمن حسن محمد علي', 'abdrahman@example.com', 'منتظم', 3),
(39, 202311302252, 1234567, 'عبدالرحمن عبدالله قائد علي', 'abdrahmanabdullah@example.com', 'منتظم', 3),
(40, 202311302253, 1234567, 'عبدالرحمن عمر منصور علي حسن', 'abdrahmanomar@example.com', 'منتظم', 3),
(41, 202311302140, 1234567, 'عبدالغني ادیب طاھر احمد', 'abdulghani@example.com', 'منتظم', 3),
(42, 202211300162, 1234567, 'عبدالله عبده ثابت محمد', 'abdallah@example.com', 'غير منتظم', 3),
(43, 202311302256, 1234567, 'لؤي انور فیصل احمد قائد', 'loay@example.com', 'منتظم', 3),
(44, 202311302254, 1234567, 'مازن نبیل علي قائد', 'mazen@example.com', 'منتظم', 3),
(45, 202311302257, 1234567, 'محمد اسماعیل احمد محمد الشرعبي', 'mohamed@example.com', 'منتظم', 3),
(46, 202311302258, 1234567, 'محمد امین عبد الباقي محمد', 'mohamedamin@example.com', 'منتظم', 3),
(47, 202311302260, 1234567, 'محمد صادق عبدالله احمد', 'mohamedsaad@example.com', 'منتظم', 3),
(48, 202311302999, 1234567, 'محمد طلال جمیل مھیوب', 'mohamedtalal@example.com', 'منتظم', 3),
(49, 202311302263, 1234567, 'محمد عبدالفتاح طاھر غالب', 'mohamedabdul@example.com', 'منتظم', 3),
(50, 202311303114, 1234567, 'محمد عبدالقوي عائض ثابت رباش', 'mohamedqawi@example.com', 'منتظم', 3),
(51, 202311302262, 1234567, 'محمد عبدالله سعید سیف عامر', 'mohamedabdullah@example.com', 'منتظم', 3),
(52, 202311302100, 1234567, 'محمد عبدالله عبدالواحد احمد', 'mohamedwahed@example.com', 'منتظم', 3),
(53, 202311302261, 1234567, 'محمد مكتوم عبدالرقیب القاضي', 'mohamedmaktoom@example.com', 'منتظم', 3),
(54, 202311302265, 1234567, 'محمد نبیل محمد قاید', 'mohamednabeel@example.com', 'منتظم', 3),
(55, 202311302266, 1234567, 'مروى مالك محمد قحطان الملیك', 'marwa@example.com', 'منتظم', 3),
(56, 202311302267, 1234567, 'مریم غالب سفیان ھزبر', 'maryam@example.com', 'منتظم', 3),
(57, 202311302268, 1234567, 'معاذ عبده خالد عبدالكریم', 'muath@example.com', 'منتظم', 3),
(58, 202311302269, 1234567, 'منال مازن عبدالرحمن عبدالله', 'manal@example.com', 'منتظم', 3),
(59, 202311302270, 1234567, 'منیب عبدالنور ابراھیم سفیان', 'munib@example.com', 'منتظم', 3),
(60, 202311302271, 1234567, 'منیة حزام عبدالله احمد', 'monia@example.com', 'منتظم', 3),
(61, 202311302111, 1234567, 'میسون محمد عبده احمد', 'mison@example.com', 'منتظم', 3),
(62, 202311302273, 1234567, 'نادر ولید عبدالله یحیي', 'nader@example.com', 'غير منتظم', 3),
(63, 202311302274, 1234567, 'نبیل طھ محمد احمد', 'nabeel@example.com', 'منتظم', 3),
(64, 202311302105, 1234567, 'نجاة عبدالكریم عبده احمد', 'najah@example.com', 'منتظم', 3),
(65, 202311302249, 1234567, 'نسیبة عبده احمد سلام', 'nasiba@example.com', 'منتظم', 3),
(66, 202311302102, 1234567, 'نوري محمد حسان مرشد', 'nuri@example.com', 'منتظم', 3),
(67, 202211300171, 1234567, 'واصل محمد بجاش سعید نصر', 'wasel@example.com', '', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reactions`
--
ALTER TABLE `reactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reactions`
--
ALTER TABLE `reactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `reactions`
--
ALTER TABLE `reactions`
  ADD CONSTRAINT `reactions_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
