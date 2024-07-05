-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 04 juil. 2024 à 17:44
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `umoja_vote`
--

-- --------------------------------------------------------

--
-- Structure de la table `candidats`
--

DROP TABLE IF EXISTS `candidats`;
CREATE TABLE IF NOT EXISTS `candidats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text,
  `categorie` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `candidats`
--

INSERT INTO `candidats` (`id`, `nom`, `description`, `categorie`) VALUES
(3, 'Eddydfff', 'danse', 'Danse Moderne'),
(4, 'Eddyddd', 'd,d,d:,', 'Danse Moderne');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `utilisateur_id` int NOT NULL,
  `candidat_id` int NOT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `etat` enum('attente','confirme') DEFAULT 'attente',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_utilisateur` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `token_activation` varchar(255) DEFAULT NULL,
  `date_inscription` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('utilisateur','admin') DEFAULT 'utilisateur',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom_utilisateur`, `email`, `mot_de_passe`, `token_activation`, `date_inscription`, `role`) VALUES
(1, 'eddy', 'user2@gmail.com', '$2y$10$jL5ZHK074X0FTjfXtZE1AeHqQMXQvubqo/K1g9n0LDdI6fnIp0EUe', NULL, '2024-07-04 08:49:48', 'admin'),
(2, 'eddy', 'user12@gmail.com', '$2y$10$TPKAKBMltFOC7TVm964DqugdCssfYWMvVBVTmPDvZiRtEEEH12dj.', NULL, '2024-07-04 08:50:03', 'utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE IF NOT EXISTS `votes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_candidat` int NOT NULL,
  `est_paye` tinyint(1) DEFAULT '0',
  `date_vote` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `jetons` int NOT NULL,
  `type_jeton` varchar(50) DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `admin_id` int NOT NULL,
  `vote_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_candidat` (`id_candidat`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `votes`
--

INSERT INTO `votes` (`id`, `id_utilisateur`, `id_candidat`, `est_paye`, `date_vote`, `jetons`, `type_jeton`, `montant`, `admin_id`, `vote_id`) VALUES
(1, 1, 1, 0, '2024-07-04 10:00:25', 0, NULL, NULL, 0, 0),
(2, 2, 3, 0, '2024-07-04 16:17:18', 0, NULL, NULL, 1, 0),
(3, 2, 3, 127, '2024-07-04 16:46:21', 3, 'normal', NULL, 1, 0),
(4, 2, 3, 127, '2024-07-04 16:49:49', 5, 'vert', NULL, 1, 0),
(5, 2, 3, 0, '2024-07-04 17:04:03', 3, 'normal', 1500.00, 1, 0),
(6, 2, 3, 0, '2024-07-04 17:04:34', 6, 'vert', 6000.00, 1, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
