SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE `uhealth` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `uhealth`;

CREATE TABLE IF NOT EXISTS `exercise` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `goal` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

INSERT INTO `exercise` (`id`, `title`, `goal`) VALUES
(1, 'Выпады с гантелями', 'Для четырехглавых мышц бедер и больших ягодичных мышц.'),
(2, 'Приседания с гантелями', 'Для четырехглавых мышц бедер и ягодичных мышц.'),
(3, 'Махи ногой в сторону, лежа на боку', 'Для средних и малых ягодичных мышц.'),
(4, 'Подъем на носок одной ноги', 'Для трицепса голени.'),
(5, 'Махи ногой назад на полу', 'Для большой ягодичной мышцы.'),
(6, 'Широкие приседания с гантелями', 'Для внутренней части бедра.');

CREATE TABLE IF NOT EXISTS `product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `proteins` float NOT NULL,
  `fats` float NOT NULL,
  `carbohydrates` float NOT NULL,
  `kcal` float NOT NULL,
  `icon` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `recipe` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `steps` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `recipebook` (
  `userId` int(10) unsigned NOT NULL,
  `recipeId` bigint(20) unsigned NOT NULL,
  `userComments` text,
  PRIMARY KEY (`userId`,`recipeId`),
  KEY `recipeId` (`recipeId`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `recipeproductlist` (
  `recipeId` bigint(20) unsigned NOT NULL,
  `productId` bigint(20) unsigned NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`recipeId`,`productId`),
  KEY `productId` (`productId`),
  KEY `recipeId` (`recipeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `stature` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `workout` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `goal` text NOT NULL,
  `cover` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `workoutexerciselist` (
  `userId` int(10) unsigned NOT NULL DEFAULT '0',
  `workoutId` bigint(20) unsigned NOT NULL,
  `exerciseId` bigint(20) unsigned NOT NULL,
  `userComments` text,
  PRIMARY KEY (`userId`,`workoutId`,`exerciseId`),
  KEY `userId` (`userId`,`workoutId`,`exerciseId`),
  KEY `workoutId` (`workoutId`),
  KEY `exerciseId` (`exerciseId`),
  KEY `userId_2` (`userId`),
  KEY `userId_3` (`userId`,`workoutId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `workoutschedule` (
  `userId` int(10) unsigned NOT NULL,
  `weekDay` enum('Mon','Tue','Wed','Thu','Fri','Sat','Sun') NOT NULL,
  `workoutId` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`userId`,`day`,`workoutId`),
  KEY `userId` (`userId`,`day`),
  KEY `workoutId` (`workoutId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `recipebook`
  ADD CONSTRAINT `recipebook_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `recipebook_ibfk_1` FOREIGN KEY (`recipeId`) REFERENCES `recipe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `recipeproductlist`
  ADD CONSTRAINT `recipeproductlist_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `recipeproductlist_ibfk_2` FOREIGN KEY (`recipeId`) REFERENCES `recipe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `workoutexerciselist`
  ADD CONSTRAINT `workoutexerciselist_ibfk_3` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `workoutexerciselist_ibfk_1` FOREIGN KEY (`workoutId`) REFERENCES `workout` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `workoutexerciselist_ibfk_2` FOREIGN KEY (`exerciseId`) REFERENCES `exercise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `workoutschedule`
  ADD CONSTRAINT `workoutschedule_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `workoutschedule_ibfk_1` FOREIGN KEY (`workoutId`) REFERENCES `workout` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
