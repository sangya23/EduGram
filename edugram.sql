-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2026 at 01:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edugram`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 1,
  `title` varchar(255) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `priority` varchar(20) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `is_done` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `user_id`, `title`, `subject`, `priority`, `due_date`, `is_done`) VALUES
(1, 1, 'Assignment 3', 'MATH 208', 'Low', '2026-02-05', 1),
(2, 1, 'Proposal', 'COMP 206', 'High', '2025-11-14', 1),
(3, 1, 'Assignment 4', 'MATH 208', 'Medium', '2026-02-12', 0),
(4, 1, 'Assignment 1', 'COMP 206', 'High', '2026-02-12', 0),
(5, 3, 'Report', 'COMP 206', 'High', '2026-02-09', 0),
(6, 3, 'Assignment 2', 'COMP 202', 'High', '2026-02-10', 0),
(7, 3, 'Assignment 3', 'MATH 208', 'Medium', '2026-02-05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_date` date NOT NULL,
  `subject` varchar(255) NOT NULL,
  `exam_type` varchar(100) NOT NULL,
  `full_marks` float DEFAULT NULL,
  `achieved_marks` float DEFAULT NULL,
  `goal_score` float DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `record` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `user_id`, `exam_date`, `subject`, `exam_type`, `full_marks`, `achieved_marks`, `goal_score`, `notes`, `record`) VALUES
(1, 3, '2026-02-20', 'EEEG 202', 'End Semester Exam', 100, 36, 80, 'Digital Logic', '2026-02-05 00:35:38');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(10) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id`, `email`, `token`, `expires_at`, `used`, `created_at`) VALUES
(1, 'n30609998@gmail.com', '436805', '2026-01-25 00:28:28', 1, '2026-01-24 23:13:28'),
(2, 'n30609998@gmail.com', '163034', '2026-01-25 00:28:34', 0, '2026-01-24 23:13:34');

-- --------------------------------------------------------

--
-- Table structure for table `study_logs`
--

CREATE TABLE `study_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 1,
  `study_date` date NOT NULL,
  `study_minutes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `study_logs`
--

INSERT INTO `study_logs` (`id`, `user_id`, `study_date`, `study_minutes`) VALUES
(1, 1, '2026-01-25', 27),
(2, 1, '2026-01-26', 10);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `color`, `user_id`) VALUES
(1, 'MCSC 201', '#32b6c8', 1),
(2, 'MATH 208', '#c270a4', 1),
(3, 'COMP 206', '#ec0909', 1),
(5, 'COMP 202', '#f28b82', 1),
(6, 'MATH 208', '#f28b82', 3),
(8, 'COMP 206', '#db0f38', 3),
(9, 'COMP 202', '#bfe798', 3),
(10, 'EEEG 202', '#bede21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task` varchar(255) NOT NULL,
  `due` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `task`, `due`, `created_at`) VALUES
(3, 3, 'Grocery Shopping', '2026-02-07 18:00:00', '2026-02-05 23:42:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `google_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`, `google_id`) VALUES
(1, 'KLM', 'abc@gmail.com', '$2y$10$QpkTtLfPI1Z9b.ejiRj7ButdRT6DRcJuF0NYuDcH5t7bPi0AhGu/2', '2026-01-13 13:24:07', '2026-01-13 13:24:07', NULL),
(2, 'KLM', 'johnd@gmail.com', '$2y$10$Fg6a6rDn.6ik2F.tmtBC2uJBNMqBMH0CqpyQg4/Zev13j7hBOAYby', '2026-01-13 13:34:19', '2026-01-13 13:34:19', NULL),
(3, 'Orient', 'orient@gmail.com', '$2y$10$33A/OGHVlMxB0/7x6TJ9O.v8tOCAIM9OpHGr.Uo9yHftkwGbIw06e', '2026-01-13 15:33:34', '2026-01-13 15:33:34', NULL),
(4, 'klm', 'hjk@gmail.com', '$2y$10$YFIIc9GH23gIGvOGXs.WB.R/5WKlYlfta11.Ay/nk1zVP3.6i0tS2', '2026-01-14 05:23:46', '2026-01-14 05:23:46', NULL),
(5, 'Test User', 'test@example.com', '$2y$10$wY/K.xLTIQj8H3GjEA5dA.Ml10yGxcfBZXPOfd8rjGZjNV0ckuY2K', '2026-01-24 06:58:12', '2026-01-24 06:58:12', NULL),

(11, 'Noname', 'n30609998@gmail.com', '$2y$10$xxBEuc3WmO4H7eAqqnQY1ODqETs5eDmdkTgJNZuE91py5dP7sH.xO', '2026-01-24 23:12:27', '2026-01-24 23:14:08', NULL),
(12, 'Noname', 'creation@gmail.com', '$2y$10$XlT0El5ZRVbp4UyDgshKC.shvGnHv8Kao/MI61VOEoxMEQWeuK7kC', '2026-02-06 00:19:11', '2026-02-06 00:19:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_questionnaire`
--

CREATE TABLE `user_questionnaire` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `education_level` varchar(50) NOT NULL,
  `current_year` int(11) NOT NULL,
  `major_subject` varchar(255) NOT NULL,
  `study_hours_daily` int(11) NOT NULL,
  `goals` text NOT NULL,
  `challenges` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_questionnaire`
--

INSERT INTO `user_questionnaire` (`id`, `user_id`, `education_level`, `current_year`, `major_subject`, `study_hours_daily`, `goals`, `challenges`, `created_at`, `updated_at`) VALUES
(1, 1, 'bachelor', 2, 'CE', 1, 'Grades', 'Time management', '2026-01-13 13:25:04', '2026-01-13 13:25:04'),
(2, 2, 'high_school', 1, 'cs', 3, 'Skills', 'Time management', '2026-01-13 13:34:43', '2026-01-13 13:34:43'),
(3, 3, 'high_school', 2, 'Physics', 1, 'Prepare for exams', 'Procastination', '2026-01-13 15:34:11', '2026-01-13 15:34:11'),
(5, 11, 'bachelor', 4, 'Engineering', 3, 'Improve grades', 'Procastination', '2026-01-24 23:12:57', '2026-01-24 23:12:57'),
(6, 12, 'high_school', 1, 'Biology', 1, 'Improve grades', 'Procastination', '2026-02-06 00:19:40', '2026-02-06 00:19:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_email_token` (`email`,`token`);

--
-- Indexes for table `study_logs`
--
ALTER TABLE `study_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_log` (`user_id`,`study_date`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_subject` (`user_id`,`name`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_questionnaire`
--
ALTER TABLE `user_questionnaire`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `study_logs`
--
ALTER TABLE `study_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_questionnaire`
--
ALTER TABLE `user_questionnaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_questionnaire`
--
ALTER TABLE `user_questionnaire`
  ADD CONSTRAINT `user_questionnaire_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
