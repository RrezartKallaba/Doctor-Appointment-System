-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 07, 2024 at 01:39 AM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doctor_appointments`
--
CREATE DATABASE IF NOT EXISTS `doctor_appointments` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `doctor_appointments`;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `appointment_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `doctor_id` int NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`appointment_id`),
  KEY `doctor_id` (`doctor_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `user_id`, `doctor_id`, `patient_name`, `appointment_date`, `created_at`) VALUES
(41, 32, 10, 'jokexade Bean', '2024-07-07', '2024-07-07 00:25:24'),
(42, 32, 10, 'jokexade Bean', '2024-07-07', '2024-07-07 00:27:05'),
(43, 32, 8, 'jokexade Bean', '2024-07-07', '2024-07-07 00:27:12'),
(44, 32, 9, 'jokexade Bean', '2024-07-07', '2024-07-07 00:27:47'),
(45, 32, 9, 'jokexade Bean', '2024-07-07', '2024-07-07 00:28:50'),
(46, 32, 8, 'jokexade Bean', '2024-07-07', '2024-07-07 00:30:09');

-- --------------------------------------------------------

--
-- Table structure for table `contactform`
--

DROP TABLE IF EXISTS `contactform`;
CREATE TABLE IF NOT EXISTS `contactform` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contactform`
--

INSERT INTO `contactform` (`id`, `user_id`, `first_name`, `last_name`, `email`, `message`) VALUES
(1, NULL, 'Fatmir', 'Krasniqi', 'fatmirkrasniqi@gmail.com', 'Përshëndetje, dua të dërgoj një mesazh për kontaktin.'),
(2, NULL, 'Aida', 'Lleshi', 'aidalleshi@gmail.com', 'Tungjatjeta, ju lutem na kontaktoni sa më shpejt të jetë e mundur.'),
(3, NULL, 'Flaka', 'Rama', 'flakarama@gmail.com', 'Mirëdita, dua të di më shumë rreth produkteve tuaja.'),
(4, NULL, 'Blerim', 'Gjoni', 'blerimgjoni@gmail.com', 'Përshëndetje, dua të di nëse keni shërbim klienti në gjuhën angleze.'),
(5, NULL, 'Era', 'Dervishi', 'eradervishi@gmail.com', 'Tungjatjeta, ju lutem më jepni informacion mbi çmimet dhe ofertat aktuale.'),
(6, 33, 'pamylyky', 'Barber', 'paquhuge@mailinator.com', 'Sint ut non debitis cum sint quo quaerat labore dolor dolor voluptatum qui eos eos animi minim'),
(7, NULL, 'vihaguxedi', 'Sellers', 'bykagucysy@mailinator.com', 'Adipisicing velit nostrum molestiae voluptatum eligendi dolor deserunt enim cumque iure et esse molestiae velit omnis minim');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE IF NOT EXISTS `doctors` (
  `doctor_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  PRIMARY KEY (`doctor_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `user_id`, `name`, `specialization`) VALUES
(1, 2, 'Dr. Adelina Dauti', 'Kardiologji'),
(2, 3, 'Dr. Arben Gashi', 'Pediatri'),
(3, 4, 'Dr. Albana Hoxha', 'Ortopedi'),
(4, 5, 'Dr. Blerina Rexhepi', 'Dermatologji'),
(5, 6, 'Dr. Besa Deda', 'Neurologji'),
(6, 7, 'Dr. Dorjan Dervishi', 'Psikiater'),
(7, 8, 'Dr. Dorina Xhaka', 'Onkologji'),
(8, 9, 'Dr. Elena Rama', 'Ortopedi'),
(9, 10, 'Dr. Elton Jashari', 'Kardiologji'),
(10, 11, 'Dr. Fatbardha Shala', 'Dermatologji');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `birthday` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'user',
  `is_banned` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `phone`, `birthday`, `password`, `status`, `is_banned`) VALUES
(1, 'Rrezart Kallaba', 'rrezartkallaba@gmail.com', NULL, '0000-00-00', '89b38ab263da5e5abe09071481b887141c21fd520aea79ff7d129ed40f31cbfa', 'admin', 'No'),
(2, 'Adelina Dauti', 'adelinadauti@gmail.com', NULL, '0000-00-00', '35cc15678f9e717a2ada15e62b1795315e03863383932d294d81d0d5d9135643', 'doctor', 'No'),
(3, 'Arben Gashi', 'arbengashi@gmail.com', NULL, '0000-00-00', 'd978261170db1e8938c939cecf8e1e18d23c091f5ea7a1ad6215f6521b7e4af8', 'doctor', 'No'),
(4, 'Albana Hoxha', 'albanahoxha@gmail.com', NULL, '0000-00-00', '0deb5c305846b5ea56d0a3b39a8f1e4cf8ee4a7cc6f9b2d6bf9aa47e44e5488b', 'doctor', 'No'),
(5, 'Blerina Rexhepi', 'blerinarexhepi@gmail.com', NULL, '0000-00-00', '9994e6aa9a9a4796587dbf6eaa317bb43e31e8202af9077ed1703a26b5614cd5', 'doctor', 'No'),
(6, 'Besa Deda', 'besadeda@gmail.com', NULL, '0000-00-00', '4e7d356f95c9ee45a540780acd057fa6e56e5a959a320c98d40490849784bb00', 'doctor', 'No'),
(7, 'Dorjan Dervishi', 'dorjandervishi@gmail.com', NULL, '0000-00-00', '7da2f1b9bcd51a2c2b362c9eb00124168b40ff9c29ec97b158acae860c9316dc', 'doctor', 'No'),
(8, 'Dorina Xhaka', 'dorinaxhaka@gmail.com', NULL, '0000-00-00', '80fa2fcd595f9ac99907223c8f37dfec3f0cb739da5a8aa5d63eca3fdfc24857', 'doctor', 'No'),
(9, 'Elena Rama', 'elenarama@gmail.com', NULL, '0000-00-00', 'f67c6cc175648903edd1f608255376ed6e5eeea701b34c459e7162727112a608', 'doctor', 'No'),
(10, 'Elton Jashari', 'eltonjashari@gmail.com', NULL, '0000-00-00', '77a112efa9d2e16e15ef02f01a033286021d90acf58d44d950aae1356be16ee6', 'doctor', 'No'),
(11, 'Fatbardha Shala', 'fatbardhashala@gmail.com', NULL, '0000-00-00', '85ac2d97cbbc06ec57431692da14fd47941688b16b5b5b12ee8f4c0307563c15', 'doctor', 'No'),
(12, 'Fatmir Gashi', 'fatmirgashi@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(13, 'Rina Krasniqi', 'rinakrasniqi@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(14, 'Shpresa Dushi', 'shpresadushi@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(15, 'Ermira Berisha', 'ermiraberisha@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(16, 'Artan Rama', 'artanrama@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(17, 'Valmir Gjoni', 'valmirgjoni@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(18, 'Elona Mustafa', 'elonamustafa@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(19, 'Drita Shala', 'dritashala@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(20, 'Ardit Avdiu', 'arditavdiu@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(21, 'Lumturie Bajrami', 'lumturiebajrami@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(22, 'Flamur Krasniqi', 'flamurkrasniqi@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(23, 'Shpëtim Mustafa', 'shpetimmustafa@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(24, 'Valbona Gashi', 'valbonagashi@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(25, 'Shkumbin Idrizi', 'shkumbinidrizi@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(26, 'Fjolla Ademi', 'fjollaademi@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(27, 'Granit Kukaj', 'granitkukaj@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(28, 'Era Qorri', 'eraqorri@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(29, 'Vlera Rama', 'vlerarama@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(30, 'Gentian Krasniqi', 'gentiankrasniqi@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(31, 'Ardita Gjoka', 'arditagjoka@gmail.com', NULL, '0000-00-00', 'bd5cf8347e036cabe6cd37323186a02ef6c3589d19daaee31eeb2ae3b1507ebe', 'user', 'No'),
(32, 'jokexade Bean', 'dejaqoz@mailinator.com', NULL, '0000-00-00', 'cfb4e4cff35327725d186b2ab037b2876bcdcbed97798ef30896f6b1a26c6888', 'user', 'No'),
(33, 'laqyjic Gallegos', 'lotyjobyf@mailinator.com', NULL, '0000-00-00', 'cfb4e4cff35327725d186b2ab037b2876bcdcbed97798ef30896f6b1a26c6888', 'user', 'No');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`);

--
-- Constraints for table `contactform`
--
ALTER TABLE `contactform`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
