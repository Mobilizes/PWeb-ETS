CREATE DATABASE IF NOT EXISTS `simple_todo_db`;
USE `simple_todo_db`;

CREATE TABLE IF NOT EXISTS `todo_users` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
);

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `status` boolean NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `todo_users`(`id`)
);

INSERT INTO `todo_users` (`name`, `password`) VALUES ('mob', 'mobpass');
INSERT INTO `todo_users` (`name`, `password`) VALUES ('user', 'userpass');

CREATE USER IF NOT EXISTS 'root'@'localhost' IDENTIFIED BY 'pass';
GRANT ALL PRIVILEGES ON `simple_todo_db`.* TO 'root'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
