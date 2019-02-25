-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1:3306
-- Vytvořeno: Pon 25. úno 2019, 00:35
-- Verze serveru: 5.7.24
-- Verze PHP: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `cds`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `cds`
--

DROP TABLE IF EXISTS `cds`;
CREATE TABLE IF NOT EXISTS `cds` (
  `cd_id` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `delka` time NOT NULL,
  `autor` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `datum_vydani` date NOT NULL,
  `FK_user_id` int(11) NOT NULL,
  PRIMARY KEY (`cd_id`),
  KEY `FK_user_id` (`FK_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `cds`
--

INSERT INTO `cds` (`cd_id`, `nazev`, `delka`, `autor`, `datum_vydani`, `FK_user_id`) VALUES
(20, 'trjr', '07:37:00', '7373', '7373-03-07', 13),
(21, 'jejetjetk', '04:37:00', 'etjej', '0098-09-07', 13),
(15, 'ztkt', '23:37:00', 'tktk', '3773-07-05', 13),
(16, 'tzkt', '05:37:00', 'tzkk', '0737-03-07', 13),
(17, 'asga', '05:37:00', 'whwh', '7637-05-08', 13),
(18, 'CD-2', '04:59:00', 'weh', '7871-08-07', 13),
(19, 'qwwg', '07:59:00', 'kmrk', '7373-03-31', 13),
(26, 'asg', '23:04:00', 'Ty', '4645-05-15', 13),
(23, 'aaaa', '04:59:00', 'fbrg', '0737-08-07', 13),
(24, 'bbbb', '07:37:00', '7373', '3737-08-07', 13),
(25, 'ehehe', '07:32:00', '78273', '7337-03-07', 13);

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`) VALUES
(3, 'Admin', '$2y$10$PCNrHB67JD8u1ySuVyrzJuJ.rgjwFOImGbpLm7HgcXOO6bgyFbe0C', 'Ondrejhavlicek98@gmail.com'),
(7, 'Admina', '$2y$10$VGc34UTEFFMgIXkJo5dcI.A5O7saoAvNE2YQGlN0I6sa39rU1OIgu', 'a'),
(13, 'a', '$2y$10$wmu9qPmgEVp/ZvZmGYFBDe80HgnH6BO4fXKr4NrNRWjhizHUXDNYm', 'a');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
