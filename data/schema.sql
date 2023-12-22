CREATE DATABASE IF NOT EXISTS `database`; 

CREATE TABLE IF NOT EXISTS `database`.`task` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `description` VARCHAR(100) NOT NULL,
  `created_at` DATETIME NULL DEFAULT now(),
  `status` INT NULL DEFAULT 1,
  PRIMARY KEY (`id`));
