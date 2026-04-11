-- Tabela powiadomień w aplikacji (Team Member — start; rozszerzenia później)
-- Uruchom w phpMyAdmin lub: mysql -u root project_management < sql/add_notifications_table.sql

CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `body` text COLLATE utf8_polish_ci DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notification_user_read` (`user_id`, `read_at`),
  KEY `idx_notification_user_created` (`user_id`, `created_at`),
  KEY `idx_notification_task` (`task_id`),
  KEY `idx_notification_project` (`project_id`),
  KEY `idx_notification_team` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
