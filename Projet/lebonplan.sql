-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 10 mars 2025 à 19:57
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lebonplan`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

DROP TABLE IF EXISTS `annonces`;
CREATE TABLE IF NOT EXISTS `annonces` (
  `entreprise` text NOT NULL,
  `titre` varchar(255) NOT NULL COMMENT 'titre de l''annonce',
  `description` varchar(1024) NOT NULL,
  `compétences` varchar(255) NOT NULL,
  `salaire` int NOT NULL,
  `date` date NOT NULL COMMENT 'date du stage',
  `stagiaire` int NOT NULL COMMENT 'nombre de stagiaires ayant déja postulé (s''incrémente depuis le site)',
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `connexion`
--

DROP TABLE IF EXISTS `connexion`;
CREATE TABLE IF NOT EXISTS `connexion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `mdp` text NOT NULL,
  `utilisateur` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `connexion`
--

INSERT INTO `connexion` (`id`, `email`, `mdp`, `utilisateur`) VALUES
(1, 'etudiant@viacesi.fr', '$2y$10$i5n3CojJKm3vzsm9wJo4FOmFrQTDtc45z1aGbVneti6i9Y0ACtdP.', 0),
(2, 'pilote@cesi.fr', '$2y$10$yNBkTryIEpvik6kXBqHUGujZUS3auuhy8PDev7VREH3J7giTidVpq', 1),
(3, 'admin@cesi.fr', '$2y$10$ewp1wmXc62tXJIW9ixi61ujaA0NP9Bsi.HKHT9SbSH6uxCBkUdqWO', 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
