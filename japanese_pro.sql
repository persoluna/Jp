-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2024 at 04:56 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `japanese_pro`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'shakib', 'shakibmunshi007@outlook.com ', '2222'),
(2, 'admin2', 'mrkillbill2004@gmail.com', '2222');

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `chapter_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `image` varchar(191) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `popular` tinyint(4) NOT NULL,
  `slug` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`chapter_id`, `name`, `description`, `image`, `status`, `popular`, `slug`) VALUES
(14, 'Basic-1', 'Words for the first lesson', '1703660696.jpg', 0, 0, 'FIRST');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `chapter_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flashcards`
--

CREATE TABLE `flashcards` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flashcards`
--

INSERT INTO `flashcards` (`id`, `question`, `answer`, `chapter_id`, `status`) VALUES
(16, 'すし', 'sushi', 14, 0),
(17, 'みず', 'Water', 14, 0),
(18, 'おちゃ', 'Green tea', 14, 0),
(19, 'ごはん', 'Rice', 14, 0),
(20, 'ください', 'Please', 14, 0),
(21, 'です', 'It is', 14, 0),
(22, 'と', 'And', 14, 0);

-- --------------------------------------------------------

--
-- Table structure for table `options_multiple_choice`
--

CREATE TABLE `options_multiple_choice` (
  `option_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `options_multiple_choice`
--

INSERT INTO `options_multiple_choice` (`option_id`, `question_id`, `option_text`, `is_correct`) VALUES
(70, 31, 'Sushi', 0),
(71, 31, 'Green tea', 0),
(72, 31, 'Rice', 0),
(73, 31, 'Water', 1),
(74, 32, 'Green tea', 1),
(75, 32, 'Rice', 0),
(76, 32, 'Sushi', 0),
(77, 32, 'Water', 0),
(78, 33, 'Water', 0),
(79, 33, 'Rice', 1),
(80, 33, 'Sushi', 0),
(81, 33, 'Green tea', 0),
(90, 40, '1', 0),
(91, 40, '2', 0),
(92, 40, '3', 0),
(93, 40, '4', 1),
(94, 41, 'ひと', 1),
(95, 41, 'じん', 0),
(96, 41, 'ひとじん', 0),
(97, 41, 'じゃん', 0),
(98, 42, 'りんご', 1),
(99, 42, 'アパル', 0),
(100, 42, 'ハハ', 0),
(101, 42, 'アアニグル', 0);

-- --------------------------------------------------------

--
-- Table structure for table `options_order_type`
--

CREATE TABLE `options_order_type` (
  `option_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_1` text NOT NULL,
  `option_2` text NOT NULL,
  `option_3` text NOT NULL,
  `option_4` text NOT NULL,
  `option_5` text NOT NULL,
  `option_6` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `options_order_type`
--

INSERT INTO `options_order_type` (`option_id`, `question_id`, `option_1`, `option_2`, `option_3`, `option_4`, `option_5`, `option_6`) VALUES
(13, 34, 'Sushi', 'and', 'green', 'tea', 'please', '.'),
(14, 35, 'It', 'is', 'rice', 'and', 'green', 'tea'),
(15, 36, 'water', 'rice', 'sushi', 'and', 'green', 'tea'),
(16, 37, 'Excuse', 'me', 'water', 'and', 'rice', 'please'),
(17, 43, 'dsad', 'dasd', 'dasd', 'dasd', 'dsad', 'dasd');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `qlesson_id` int(11) NOT NULL,
  `question_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `qlesson_id`, `question_text`) VALUES
(31, 34, 'みず'),
(32, 34, 'おちゃ'),
(33, 34, 'ごはん'),
(34, 34, 'すしとおちゃ、ください。'),
(35, 34, 'ごはんとおちゃです。'),
(36, 34, 'みず、ごはん、すしとおちゃ'),
(37, 34, 'すみません、みずとごはん、ください'),
(40, 41, 'dasdasd'),
(41, 44, 'People'),
(42, 44, 'Apple'),
(43, 44, 'dasdsad');

-- --------------------------------------------------------

--
-- Table structure for table `quizlessons`
--

CREATE TABLE `quizlessons` (
  `qlesson_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizlessons`
--

INSERT INTO `quizlessons` (`qlesson_id`, `title`, `created_at`) VALUES
(34, 'Basic-1', '2023-12-27 06:16:34'),
(41, 'Nothing here..', '2024-01-16 15:04:40'),
(44, 'Test 2', '2024-02-19 04:01:09');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `image`) VALUES
(17, 'Quba', 'bb746605@gmail.com', '$2y$10$FOw03U31oQA5Z59du64.SehJ3inBS7xOmPHgOcpikOmHv.SworNwy', '1704454762.png'),
(18, 'shakib', 'shakibmunshi007@outlook.com', '$2y$10$MpEmxdmZO.g8Iovbqh/32OWrbM.KKG0P9iUkxvvsNYLCa18azIWYG', '1704772177.jpg'),
(19, 'Shakil A Munshi', 'widevideovision@gmail.com', '$2y$10$yT5ANQfwtzWDaTn2g5m19.7KC36HEdJyYmIfCSKIUbPgQLgmU7TkO', '1707967591.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_streak`
--

CREATE TABLE `user_streak` (
  `user_id` int(11) NOT NULL,
  `last_completed_date` date NOT NULL,
  `total_days` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_streak`
--

INSERT INTO `user_streak` (`user_id`, `last_completed_date`, `total_days`) VALUES
(17, '2024-02-17', 0),
(18, '2024-02-19', 1),
(19, '2024-02-16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_xp`
--

CREATE TABLE `user_xp` (
  `user_id` int(11) NOT NULL,
  `xp` int(11) DEFAULT 0,
  `email_sent` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_xp`
--

INSERT INTO `user_xp` (`user_id`, `xp`, `email_sent`) VALUES
(17, 446, 0),
(18, 5754, 1),
(19, 110, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_xp_chart`
--

CREATE TABLE `user_xp_chart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `xp_earned` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_xp_chart`
--

INSERT INTO `user_xp_chart` (`id`, `user_id`, `xp_earned`, `date`) VALUES
(1, 18, 20, '2024-02-16'),
(2, 18, 26, '2024-02-17'),
(3, 17, 18, '2024-02-16'),
(4, 17, 25, '2024-02-16'),
(5, 17, 1, '2024-02-15'),
(6, 18, 10, '2024-02-17'),
(7, 18, -2, '2024-02-17'),
(8, 18, -2, '2024-02-17'),
(9, 18, 110, '2024-02-19'),
(10, 18, 18, '2024-02-19'),
(11, 18, 10, '2024-02-19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`chapter_id`),
  ADD KEY `chapter_id` (`chapter_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `chapter_id` (`chapter_id`);

--
-- Indexes for table `flashcards`
--
ALTER TABLE `flashcards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `options_multiple_choice`
--
ALTER TABLE `options_multiple_choice`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `options_order_type`
--
ALTER TABLE `options_order_type`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `qlesson_id` (`qlesson_id`);

--
-- Indexes for table `quizlessons`
--
ALTER TABLE `quizlessons`
  ADD PRIMARY KEY (`qlesson_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user_streak`
--
ALTER TABLE `user_streak`
  ADD PRIMARY KEY (`user_id`,`last_completed_date`);

--
-- Indexes for table `user_xp`
--
ALTER TABLE `user_xp`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_xp_chart`
--
ALTER TABLE `user_xp_chart`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `chapter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `flashcards`
--
ALTER TABLE `flashcards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `options_multiple_choice`
--
ALTER TABLE `options_multiple_choice`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `options_order_type`
--
ALTER TABLE `options_order_type`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `quizlessons`
--
ALTER TABLE `quizlessons`
  MODIFY `qlesson_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_xp_chart`
--
ALTER TABLE `user_xp_chart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`);

--
-- Constraints for table `options_multiple_choice`
--
ALTER TABLE `options_multiple_choice`
  ADD CONSTRAINT `options_multiple_choice_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`);

--
-- Constraints for table `options_order_type`
--
ALTER TABLE `options_order_type`
  ADD CONSTRAINT `options_order_type_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`qlesson_id`) REFERENCES `quizlessons` (`qlesson_id`);

--
-- Constraints for table `user_streak`
--
ALTER TABLE `user_streak`
  ADD CONSTRAINT `user_streak_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_xp`
--
ALTER TABLE `user_xp`
  ADD CONSTRAINT `user_xp_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
