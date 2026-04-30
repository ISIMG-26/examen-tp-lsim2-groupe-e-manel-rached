-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 30 avr. 2026 à 21:30
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `covoiturage_tn`
--

-- --------------------------------------------------------

--
-- Structure de la table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `seats_booked` int(11) NOT NULL DEFAULT 1,
  `booking_status` enum('confirmed','cancelled') DEFAULT 'confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bookings`
--

INSERT INTO `bookings` (`id`, `ride_id`, `passenger_id`, `seats_booked`, `booking_status`, `created_at`) VALUES
(1, 1, 1, 1, 'confirmed', '2026-04-19 17:57:22'),
(2, 1, 4, 1, 'confirmed', '2026-04-19 18:09:02'),
(3, 8, 4, 4, 'confirmed', '2026-04-19 18:45:22'),
(4, 4, 4, 1, 'confirmed', '2026-04-19 18:45:36'),
(5, 9, 4, 1, 'confirmed', '2026-04-19 18:46:54'),
(20, 6, 4, 2, 'confirmed', '2026-04-19 19:29:25'),
(41, 10, 4, 2, 'confirmed', '2026-04-19 21:13:07'),
(64, 3, 5, 1, 'confirmed', '2026-04-20 10:55:25'),
(65, 5, 5, 2, 'confirmed', '2026-04-20 10:55:47'),
(66, 9, 5, 1, 'confirmed', '2026-04-20 11:00:46'),
(67, 11, 5, 1, 'confirmed', '2026-04-20 11:01:52'),
(68, 3, 6, 1, 'confirmed', '2026-04-20 11:03:25'),
(69, 4, 6, 1, 'confirmed', '2026-04-20 11:12:10'),
(70, 7, 6, 2, 'confirmed', '2026-04-20 21:23:26'),
(71, 12, 6, 3, 'confirmed', '2026-04-20 21:29:13'),
(72, 14, 6, 1, 'confirmed', '2026-04-20 21:56:37'),
(73, 15, 6, 1, 'confirmed', '2026-04-24 17:39:56'),
(74, 15, 8, 1, 'confirmed', '2026-04-24 17:58:59'),
(76, 13, 7, 1, 'confirmed', '2026-04-26 17:54:13'),
(77, 13, 8, 2, 'confirmed', '2026-04-27 10:52:02'),
(78, 1, 8, 2, 'confirmed', '2026-04-27 11:10:41'),
(80, 20, 8, 2, 'confirmed', '2026-04-27 13:17:54');

-- --------------------------------------------------------

--
-- Structure de la table `rides`
--

CREATE TABLE `rides` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `driver_name` varchar(120) NOT NULL,
  `driver_phone` varchar(20) NOT NULL,
  `from_city` varchar(120) NOT NULL,
  `to_city` varchar(120) NOT NULL,
  `from_lat` decimal(10,7) DEFAULT NULL,
  `from_lng` decimal(10,7) DEFAULT NULL,
  `to_lat` decimal(10,7) DEFAULT NULL,
  `to_lng` decimal(10,7) DEFAULT NULL,
  `ride_date` date NOT NULL,
  `ride_time` time NOT NULL,
  `total_seats` int(11) NOT NULL,
  `available_seats` int(11) NOT NULL CHECK (`available_seats` >= 0),
  `price` decimal(10,2) NOT NULL,
  `note` text DEFAULT NULL,
  `status` enum('active','full','cancelled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `rides`
--

INSERT INTO `rides` (`id`, `driver_id`, `driver_name`, `driver_phone`, `from_city`, `to_city`, `from_lat`, `from_lng`, `to_lat`, `to_lng`, `ride_date`, `ride_time`, `total_seats`, `available_seats`, `price`, `note`, `status`, `created_at`) VALUES
(1, 1, 'Manel', '12345678', 'Tunis', 'Gabes', NULL, NULL, NULL, NULL, '2026-06-10', '10:00:00', 2, 0, 30.00, 'trajet rapide', 'active', '2026-04-13 22:13:39'),
(3, 4, 'ali', '26242523', 'gabes', 'sfax', NULL, NULL, NULL, NULL, '2026-04-28', '15:20:00', 2, 0, 25.00, 'asdf', 'active', '2026-04-14 14:20:50'),
(4, 4, 'ghj', '25836914', 'gdhs', 'dbjd', NULL, NULL, NULL, NULL, '2026-05-16', '15:21:00', 2, 0, 25.00, 'fsgshw', 'active', '2026-04-14 14:21:51'),
(5, 4, 'ahmed', '14725836', 'hgfd', 'dghj', NULL, NULL, NULL, NULL, '2026-04-27', '15:27:00', 2, 0, 25.00, 'ryggh', 'active', '2026-04-14 14:27:48'),
(6, 4, 'test', '23552554', 'gabes', 'tunis', NULL, NULL, NULL, NULL, '2026-04-19', '17:47:00', 2, 0, 40.00, 'fhgj', 'active', '2026-04-19 12:48:12'),
(7, 4, 'ryu', '14725836', 'chh', 'fhj', NULL, NULL, NULL, NULL, '2026-04-28', '20:05:00', 2, 0, 65.00, 'dghy', 'active', '2026-04-19 13:05:46'),
(8, 4, 'yessin', '12345678', 'turkey', 'france', NULL, NULL, NULL, NULL, '2026-04-22', '15:30:00', 4, 0, 90.00, 'qwer', 'active', '2026-04-19 14:30:23'),
(9, 4, 'makram', '25638789', 'medenin', 'djerba', NULL, NULL, NULL, NULL, '2026-06-16', '19:46:00', 2, 0, 15.00, 'vshsh', 'active', '2026-04-19 18:46:46'),
(10, 4, 'amor', '25369685', 'tozeur', 'mahdia', NULL, NULL, NULL, NULL, '2026-07-16', '22:12:00', 2, 0, 50.00, 'mnbvvc', 'active', '2026-04-19 21:12:58'),
(11, 5, 'karim', '58476932', 'libya', 'tunis', NULL, NULL, NULL, NULL, '2026-04-30', '12:01:00', 3, 2, 60.00, 'lkjg', 'active', '2026-04-20 11:01:43'),
(12, 7, 'Makram', '23568987', 'Gabes', 'Medenin', NULL, NULL, NULL, NULL, '2026-06-16', '19:09:00', 3, 0, 20.00, 'mnbvcx', 'active', '2026-04-20 18:09:36'),
(13, 7, 'Ali', '56892354', 'tunis', 'sfax', NULL, NULL, NULL, NULL, '2026-04-21', '20:10:00', 3, 0, 30.00, 'rdztfx', 'active', '2026-04-20 18:10:39'),
(14, 6, 'Amine', '58697423', 'Zaghouan', 'Gafsa', NULL, NULL, NULL, NULL, '2026-06-25', '22:56:00', 3, 2, 35.00, 'bznsns', 'active', '2026-04-20 21:56:13'),
(15, 6, 'Sami', '98765432', 'Kassrine', 'Kef', NULL, NULL, NULL, NULL, '2026-07-08', '17:51:00', 4, 2, 30.00, 'aujourd\'hui', 'active', '2026-04-24 16:51:22'),
(16, 8, 'Khaled', '58565952', 'Medenin', 'Gabès', NULL, NULL, NULL, NULL, '2026-06-24', '18:59:00', 3, 3, 15.00, 'don\'t be late 😊', 'active', '2026-04-24 18:00:29'),
(20, 8, 'Manel', '32145698', 'Sousse', 'Bizerte', NULL, NULL, NULL, NULL, '2026-06-19', '14:16:00', 3, 1, 25.00, 'mnbvcxz', 'active', '2026-04-27 13:17:23');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rating` decimal(2,1) DEFAULT 4.8,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password_hash`, `rating`, `created_at`) VALUES
(1, 'Manel', 'manel@gmail.com', '12345678', '123456', 4.8, '2026-04-13 22:07:39'),
(2, 'Test User New', 'test2@gmail.com', '98765432', '$2y$10$jo/oUegOmQbZOJoyQ9Od4ug5eenSfeXyCdafSR70ASgpK7gjLEEXu', 4.8, '2026-04-14 00:21:11'),
(3, 'Test User 3', 'test3@gmail.com', '11112222', '$2y$10$c0Rrc243tuIPtnIADPFEy.X9w8Aed1zDX4jYgjaoUehVAEtgAmVr.', 4.8, '2026-04-14 00:24:54'),
(4, 'makram', 'makram@gmail.com', '25836912', '$2y$10$wnqliucWlJFTMVMdNUohjOkRSoSg9OERWyRusuqrMHPKzMPQp2ie.', 4.8, '2026-04-14 13:48:20'),
(5, 'makram', 'mm@gmail.com', '23568914', '$2y$10$9Zk/ci6MLmzGlTCrx/gr/ucVjkkS7iD33Au00usbSTxxxDDjCDMle', 4.8, '2026-04-20 10:54:36'),
(6, 'user', 'user@gmail.com', '36251478', '$2y$10$JWZKgP1xsn5HuPW6xrNUH.zqjmZuK.aK1yE2o3aij13wzWeeClmU6', 4.8, '2026-04-20 11:03:11'),
(7, 'Ahmed', 'ahmed@gmail.com', '24272826', '$2y$10$2OQzowuR/Xl8fSRnAMSTYuljM15YklUl/SQvTxukC1LvXqeYidNou', 4.8, '2026-04-20 18:08:39'),
(8, 'Manel', 'manel2@gmail.com', '56238974', '$2y$10$ivE46JFL33i55wxeMRt25.rAFw8N3NlmaCUsFpLuoZlMGz4kJ.NkK', 4.8, '2026-04-24 17:51:46');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_booking` (`ride_id`,`passenger_id`),
  ADD KEY `fk_book_passenger` (`passenger_id`);

--
-- Index pour la table `rides`
--
ALTER TABLE `rides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rides_driver` (`driver_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT pour la table `rides`
--
ALTER TABLE `rides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_book_passenger` FOREIGN KEY (`passenger_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_book_ride` FOREIGN KEY (`ride_id`) REFERENCES `rides` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `rides`
--
ALTER TABLE `rides`
  ADD CONSTRAINT `fk_rides_driver` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
