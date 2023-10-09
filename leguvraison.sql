-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 12 avr. 2021 à 16:49
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `base_mini-projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant commande',
  `idClient` int(11) NOT NULL COMMENT 'identifiant commanditaire',
  `idLivreur` int(11) NOT NULL COMMENT 'identifiant livreur',
  `date` date NOT NULL COMMENT 'date à laquelle la livraison sera effectuée',
  `heure` time NOT NULL COMMENT 'heure à laquelle la livraison sera effectuée',
  `statut` text NOT NULL COMMENT 'Etat de la commande (N pour nouvelle,P pour préparée,E pour envoyée,L pour livrée)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `idClient`, `idLivreur`, `date`, `heure`, `statut`) VALUES
(1, 6, 4, '2021-04-01', '09:10:00', 'P'),
(2, 1, 5, '2021-04-10', '02:01:00', 'N');

-- --------------------------------------------------------

--
-- Structure de la table `contenu`
--

DROP TABLE IF EXISTS `contenu`;
CREATE TABLE IF NOT EXISTS `contenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant contenu',
  `idCommande` int(11) NOT NULL COMMENT 'identifiant commande',
  `idProduit` int(11) NOT NULL COMMENT 'identifiant produit',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `contenu`
--

INSERT INTO `contenu` (`id`, `idCommande`, `idProduit`) VALUES
(1, 1, 1),
(2, 1, 4),
(3, 2, 5),
(4, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant produit',
  `idProducteur` int(11) NOT NULL COMMENT 'identifiant producteur',
  `nom` text NOT NULL COMMENT 'nom produit',
  `quantiteDisponible` int(11) NOT NULL DEFAULT '0' COMMENT 'quantité disponible',
  `lot` text NOT NULL COMMENT 'description lot (quantité + unité)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `idProducteur`, `nom`, `quantiteDisponible`, `lot`) VALUES
(1, 3, 'Pommes de terre Agria', 8, '1 kg'),
(2, 3, 'Pommes de terre Rattes', 2, '500 g'),
(3, 3, 'Carottes', 3, '1 botte'),
(4, 7, 'Broccoli', 1, '1 tête'),
(5, 7, 'Durian', 2, '1 fruit');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'identifiant utilisateur',
  `prenom` text NOT NULL COMMENT 'prénom utilisateur',
  `nom` text NOT NULL COMMENT 'nom utilisateurs',
  `pass` text NOT NULL COMMENT 'mdp utilisateur',
  `role` text NOT NULL COMMENT 'role (C pour client, P pour producteur, L pour livreur), client par défaut',
  `adresse` text NOT NULL COMMENT 'adresse utilisateur',
  `mail` text COMMENT 'mail (optionnel)',
  `telephone` text COMMENT 'telephone (optionnel)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `prenom`, `nom`, `pass`, `role`, `adresse`, `mail`, `telephone`) VALUES
(1, 'user1', 'nomuser1', 'pass1', 'C', 'appartement F311', 'adresse1@adresse.com', '06 00 00 00 00'),
(2, 'user2', 'nomuser2', 'pass2', 'C', 'E106', 'adresse2@adresse.com', '0600000001'),
(3, 'prod1', 'nomprod1', 'passprod1', 'P', 'ferme 1', 'adresse3@adresse.com', '0600000002'),
(4, 'livreur1', 'nomliv1', 'passliv1', 'L', 'Local de distribution', 'adresse4@adresse.com', '060000 00 03'),
(5, 'livreur2', 'nomliv2', 'passliv2', 'L', 'Local de distribution', 'adresse5@adresse.com', '0600000004'),
(6, 'user3', 'nomuser3', 'pass3', 'C', 'B201', 'adresse6@adresse.com', '0600000005'),
(7, 'prod2', 'nomprod2', 'passprod2', 'P', 'ferme 2', 'adresse7@adresse.com', '0600000006');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
