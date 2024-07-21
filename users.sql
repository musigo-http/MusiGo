-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 21 juil. 2024 à 13:18
-- Version du serveur : 5.7.44
-- Version de PHP : 7.3.29-to-be-removed-in-future-macOS

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `utilisateurs`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `pseudo` text NOT NULL,
  `date` text NOT NULL,
  `privileges` text NOT NULL,
  `abonnement` text NOT NULL,
  `amis` text NOT NULL,
  `likes_musique` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `pseudo`, `date`, `privileges`, `abonnement`, `amis`, `likes_musique`) VALUES
(1, 'mateoledevdu89@gmail.com', 'M0T_D3_P@§§3', 'Mateo - ADMIN', '2024-03-24 12:30:12', 'ADMIN', 'kali - ADMIN, yay, ', ', ', ''),
(2, 'mateoapidu89@gmail.com', 'P@ssW0rd!2024', 'API - ADMIN', '2024-03-24 13:13:00', 'ADMIN', '', '', 'Ziak - Double Dash (Prod. Munroe x TR), GAZO - RAPPEL, Ziak - Akimbo (Prod. Focus Beatz X Hellboy), Panama, Le pacte, GAZO - HAINE&SEX (partie 1), Tiakola - Meuda (Clip officiel), Ziak - Seinen  (Prod. Lowonstage X Grks), '),
(3, 'mateotayeb@gmail.com', 'Mat.at89', 'Mat89 - ADMIN', '2024-03-24 17:32:41', 'ADMIN', '', '', ''),
(10, 'test@test.com', 'Mat.at89', 'kali - ADMIN', '2024-03-29 20:29:25', 'ADMIN', 'yay, Mat89 - ADMIN, Mateo - ADMIN, ', '', ''),
(11, 'autre@autre.com', 'Mat.at89', 'autre', '2024-03-29 20:44:36', 'user', '', '', ''),
(12, 'salut@salut.com', 'P@ssW0rd!2023', 'yay', '2024-04-14 09:52:30', 'user', 'Mat89 - ADMIN, Mateo - ADMIN, ', '', ''),
(13, 'tty@gmail.com', 'daccord', 'poof', '2024-07-18 20:54:59', 'user', '0', '0', '0'),
(14, 'pilepof@gmail.com', 'azerty', 'pite', '2024-07-18 20:56:57', 'user', '', '', ''),
(15, 'mrnolimit42@gmail.com', 'MRNOLIMIT.38', 'aze3', '2024-07-19 11:36:11', 'user', '', '', ''),
(16, 'team.agora83@gmail.com', 'azerty', 'AZERT', '2024-07-19 18:34:16', 'user', '', '', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
