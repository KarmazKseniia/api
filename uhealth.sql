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
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
