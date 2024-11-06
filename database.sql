CREATE DATABASE IF NOT EXISTS `simple_todo_db`;
USE `simple_todo_db`;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `status` boolean NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE USER IF NOT EXISTS 'root'@'localhost' IDENTIFIED BY 'pass';
GRANT ALL PRIVILEGES ON `simple_todo_db`.* TO 'root'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
