-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2026 at 02:35 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_management`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `task_id`, `user_id`, `content`, `created_at`) VALUES
(16, 21, 2, 'This is comment for Task 2', '2026-03-10 09:57:45'),
(19, 21, 2, 'This is comment for Task 2', '2026-04-03 20:37:30'),
(23, 21, 1, '213231 1 32132 132', '2026-04-05 16:33:11');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `user_id`, `type`, `title`, `body`, `task_id`, `project_id`, `team_id`, `read_at`, `created_at`) VALUES
(1, 1, 'task_assigned', 'New task assigned', 'You were assigned: \"Finish Project Setup\" in project \"Project Management App\".', 32, 109, NULL, '2026-04-06 15:42:32', '2026-04-06 13:41:22'),
(2, 9, 'task_reassigned', 'Task assigned to you', 'You are now assigned to \"Finish Project Setup\" (Project Management App).', 32, 109, NULL, '2026-04-06 15:46:49', '2026-04-06 13:42:51'),
(3, 1, 'task_reassigned', 'Task assigned to you', 'You are now assigned to \"Finish Project Setup\" (Project Management App).', 32, 109, NULL, '2026-04-06 15:45:25', '2026-04-06 13:43:05'),
(4, 9, 'task_reassigned', 'Task assigned to you', 'You are now assigned to \"Finish Project Setup\" (Project Management App).', 32, 109, NULL, '2026-04-06 15:46:50', '2026-04-06 13:45:06'),
(5, 1, 'task_reassigned', 'Task assigned to you', 'You are now assigned to \"Finish Project Setup\" (Project Management App).', 32, 109, NULL, '2026-04-06 15:48:08', '2026-04-06 13:48:01'),
(6, 1, 'comment_on_task', 'New comment on your task', 'A manager added a comment on task \"Finish Project Setup\" (Project Management App).', 32, 109, NULL, '2026-04-06 15:48:33', '2026-04-06 13:48:24'),
(7, 1, 'comment_on_task', 'New comment on your task', 'A manager added a comment on task \"Finish Project Setup\" (Project Management App).', 32, 109, NULL, '2026-04-06 15:49:23', '2026-04-06 13:49:12'),
(8, 1, 'project_status_changed', 'Project status updated', 'Project \"Project Management App\" status changed from \"Planned\" to \"In Progress\".', NULL, 109, NULL, '2026-04-06 15:50:20', '2026-04-06 13:49:44'),
(9, 9, 'project_status_changed', 'Project status updated', 'Project \"Project Management App\" status changed from \"Planned\" to \"In Progress\".', NULL, 109, NULL, NULL, '2026-04-06 13:49:44'),
(10, 1, 'project_status_changed', 'Project status updated', 'Project \"Project Management App\" status changed from \"In Progress\" to \"Planned\".', NULL, 109, NULL, '2026-04-06 15:56:15', '2026-04-06 13:53:45'),
(11, 9, 'project_status_changed', 'Project status updated', 'Project \"Project Management App\" status changed from \"In Progress\" to \"Planned\".', NULL, 109, NULL, NULL, '2026-04-06 13:53:45'),
(12, 1, 'team_renamed', 'Team renamed', 'Team \"PM App Team 1\" was renamed to \"PM App Team\".', NULL, NULL, 26, '2026-04-06 15:56:23', '2026-04-06 13:56:07'),
(13, 9, 'team_renamed', 'Team renamed', 'Team \"PM App Team 1\" was renamed to \"PM App Team\".', NULL, NULL, 26, NULL, '2026-04-06 13:56:07'),
(14, 1, 'team_joined', 'Added to a team', 'You were added to the team \"New super team 2.0\".', NULL, NULL, 28, '2026-04-06 15:57:20', '2026-04-06 13:57:14'),
(15, 9, 'team_joined', 'Added to a team', 'You were added to the team \"New super team 2.0\".', NULL, NULL, 28, NULL, '2026-04-06 13:57:34'),
(16, 1, 'project_status_changed', 'Project status updated', 'Project \"Project Management App\" status changed from \"Planned\" to \"In Progress\".', NULL, 109, NULL, '2026-04-06 19:17:26', '2026-04-06 17:17:05'),
(17, 9, 'project_status_changed', 'Project status updated', 'Project \"Project Management App\" status changed from \"Planned\" to \"In Progress\".', NULL, 109, NULL, NULL, '2026-04-06 17:17:05'),
(18, 9, 'task_reassigned', 'Task assigned to you', 'You are now assigned to \"Task 5\" (Project Management App).', 27, 109, NULL, NULL, '2026-04-06 17:29:45'),
(19, 1, 'task_reassigned', 'Task assigned to you', 'You are now assigned to \"Task 5\" (Project Management App).', 27, 109, NULL, '2026-04-06 19:30:07', '2026-04-06 17:29:52'),
(20, 9, 'task_reassigned', 'Task assigned to you', 'You are now assigned to \"Task 5\" (Project Management App).', 27, 109, NULL, NULL, '2026-04-06 17:30:12'),
(21, 1, 'task_reassigned', 'Task assigned to you', 'You are now assigned to \"Task 5\" (Project Management App).', 27, 109, NULL, NULL, '2026-04-06 17:30:15');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `team_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `name`, `description`, `start_date`, `end_date`, `status`, `team_id`, `created_at`, `updated_at`) VALUES
(109, 'Project Management App', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc porta, diam et lacinia conse', '2026-03-10', '2026-04-05', 'In Progress', 26, '2026-03-10 09:55:20', '2026-04-06 17:17:05'),
(148, 'Website Redesign', 'Complete redesign of company website', '2025-01-10', '2025-04-15', 'In Progress', 27, '2026-03-21 18:56:31', '2026-03-21 18:57:17'),
(149, 'Mobile App Development', 'Developing a cross-platform mobile application', '2025-02-01', '2025-07-30', 'In Progress', 26, '2026-03-21 18:56:31', '2026-03-21 18:56:31'),
(150, 'CRM Integration', 'Integration with new CRM system', '2025-03-05', '2025-05-20', 'Planned', 26, '2026-03-21 18:56:31', '2026-03-21 18:56:31'),
(151, 'Internal Dashboard', 'Creating internal analytics dashboard', '2025-01-20', '2025-03-30', 'Completed', 26, '2026-03-21 18:56:31', '2026-03-21 18:56:31'),
(152, 'Marketing Campaign Platform', 'Platform for managing marketing campaigns', '2025-04-01', '2025-08-15', 'Planned', 26, '2026-03-21 18:56:31', '2026-03-21 18:56:31'),
(153, 'Customer Portal', 'Online portal for customer account management', '2025-02-15', '2025-06-10', 'In Progress', 26, '2026-03-21 18:56:31', '2026-03-21 18:56:31'),
(154, 'Bug Tracking System', 'Internal bug tracking application', '2025-03-10', '2025-05-10', 'Completed', 26, '2026-03-21 18:56:31', '2026-03-21 18:56:31'),
(155, 'API Gateway', 'Centralized API gateway for microservices', '2025-04-05', '2025-09-01', 'Planned', 26, '2026-03-21 18:56:31', '2026-03-21 18:56:31'),
(156, 'Inventory Management', 'Warehouse inventory tracking system', '2025-01-05', '2025-06-01', 'In Progress', 26, '2026-03-21 18:56:31', '2026-03-21 18:56:31'),
(157, 'Website Redesign', 'Complete redesign of company website', '2025-01-10', '2025-04-15', 'In Progress', 26, '2026-04-05 12:30:33', '2026-04-05 12:30:33'),
(158, 'Mobile App Development', 'Developing a cross-platform mobile application', '2025-02-01', '2025-07-30', 'In Progress', 26, '2026-04-05 12:30:33', '2026-04-05 12:30:33'),
(159, 'CRM Integration', 'Integration with new CRM system', '2025-03-05', '2025-05-20', 'Planned', 26, '2026-04-05 12:30:33', '2026-04-05 12:30:33'),
(160, 'Internal Dashboard', 'Creating internal analytics dashboard', '2025-01-20', '2025-03-30', 'Completed', 26, '2026-04-05 12:30:33', '2026-04-05 12:30:33'),
(161, 'Marketing Campaign Platform', 'Platform for managing marketing campaigns', '2025-04-01', '2025-08-15', 'Planned', 26, '2026-04-05 12:30:33', '2026-04-05 12:30:33'),
(162, 'Customer Portal', 'Online portal for customer account management', '2025-02-15', '2025-06-10', 'In Progress', 26, '2026-04-05 12:30:33', '2026-04-05 12:30:33'),
(163, 'Bug Tracking System', 'Internal bug tracking application', '2025-03-10', '2025-05-10', 'Completed', 26, '2026-04-05 12:30:33', '2026-04-05 12:30:33'),
(164, 'API Gateway', 'Centralized API gateway for microservices', '2025-04-05', '2025-09-01', 'Planned', 26, '2026-04-05 12:30:33', '2026-04-05 12:30:33'),
(166, 'New super project 3.0', 'New super project 3.0', '2026-04-05', '2026-04-15', 'Planned', 26, '2026-04-05 16:27:05', '2026-04-05 16:27:05');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `due_date` date NOT NULL,
  `project_id` int(11) NOT NULL,
  `assigned_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `title`, `description`, `priority`, `status`, `due_date`, `project_id`, `assigned_user_id`, `created_at`, `updated_at`) VALUES
(21, 'Task 2', 'Quisque ac diam non ligula laoreet ornare. Cras felis nisl, consectetur sit amet sagittis', 'Medium', 'In Progress', '2026-03-20', 109, 1, '2026-03-10 09:56:45', '2026-04-05 16:35:53'),
(24, 'Task 4', 'Task 4', 'Low', 'Completed', '2026-03-25', 109, 1, '2026-03-14 16:41:23', '2026-04-05 16:36:04'),
(25, 'Task 1', 'Task 1', 'Low', 'In Progress', '2026-03-30', 109, 1, '2026-03-29 01:55:29', '2026-04-05 16:36:17'),
(26, 'Task 3', 'Task 3', 'Medium', 'Cancelled', '2026-04-05', 109, 1, '2026-03-29 01:56:20', '2026-04-05 16:36:11'),
(27, 'Task 5', 'Task 5', 'High', 'Completed', '2026-04-01', 109, 1, '2026-03-29 01:57:14', '2026-04-06 17:30:15');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `name`, `created_at`) VALUES
(26, 'PM App Team', '2026-03-10 09:53:59'),
(27, 'Jakub Team', '2026-03-10 10:31:51'),
(28, 'New super team 2.0', '2026-03-28 18:38:46');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `team_user`
--

CREATE TABLE `team_user` (
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `team_user`
--

INSERT INTO `team_user` (`team_id`, `user_id`) VALUES
(26, 1),
(26, 2),
(26, 9),
(27, 1),
(28, 2),
(28, 9);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_approved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `created_at`, `updated_at`, `is_approved`) VALUES
(1, 'Adam', 'Nowak', 'adam.nowak@wp.pl', '$2y$10$3VUlhTj3EFTWP9nekwA2au1RD.Gl3Vy7Ge2gRD5TrLwaOHb4i9iy2', 'Team Member', '2025-02-06 14:15:04', '2026-04-05 16:36:40', 1),
(2, 'Jan', 'Kowalski', 'jan.kowalski@wp.pl', '$2y$10$KGFAWx5gS7QQQVhQZFPQ5u6Jm5MbqyDpcp33PO7ynJNkRb1ZhiF/K', 'Project Manager', '2025-02-06 14:15:04', '2026-04-05 12:28:26', 1),
(3, 'Kamil', 'Wójcik ', 'kamil.wojcik@wp.pl', '$2y$10$RPZ0dqvJmrD7z3ycI2uPbO4AjL8/of.EckwXUR5rwOR8Umv63RWRi', 'Admin', '2025-02-06 14:17:14', '2025-04-16 15:43:27', 1),
(9, 'Jakub', 'Wojciechowski', 'jakub.wojciechowski.682@gmail.com', '$2y$10$yNZJJXqVuNhys3Q8IR/JVumueNAbM5s5y9VmLInKiBVGmdrXvDSxW', 'Team Member', '2026-03-21 19:04:37', '2026-03-21 19:06:24', 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notification_user_read` (`user_id`,`read_at`),
  ADD KEY `idx_notification_user_created` (`user_id`,`created_at`),
  ADD KEY `idx_notification_task` (`task_id`),
  ADD KEY `idx_notification_project` (`project_id`),
  ADD KEY `idx_notification_team` (`team_id`);

--
-- Indeksy dla tabeli `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indeksy dla tabeli `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indeksy dla tabeli `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `assigned_user_id` (`assigned_user_id`);

--
-- Indeksy dla tabeli `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeksy dla tabeli `team_user`
--
ALTER TABLE `team_user`
  ADD PRIMARY KEY (`team_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_ibfk_2` FOREIGN KEY (`assigned_user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `team_user`
--
ALTER TABLE `team_user`
  ADD CONSTRAINT `team_user_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
