-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 24 avr. 2026 à 02:15
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
-- Base de données : `car_rental`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `idAdmin` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idAdmin`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `idAvis` int NOT NULL AUTO_INCREMENT,
  `note` int DEFAULT NULL,
  `commentaire` text,
  `date` date DEFAULT NULL,
  `idClient` int DEFAULT NULL,
  `idVoiture` int DEFAULT NULL,
  PRIMARY KEY (`idAvis`),
  KEY `idClient` (`idClient`),
  KEY `idVoiture` (`idVoiture`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`idAvis`, `note`, `commentaire`, `date`, `idClient`, `idVoiture`) VALUES
(1, 5, 'excellente voiture je recommande', '2026-04-22', 2, 6),
(2, 1, 'jaime bien', '2026-04-22', 1, 3),
(3, 5, 'très utile', '2026-04-22', 1, 12),
(4, 5, 'très utile', '2026-04-22', 1, 12);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `idClient` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `motDePasse` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'default.png',
  PRIMARY KEY (`idClient`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`idClient`, `nom`, `email`, `motDePasse`, `image`) VALUES
(1, 'hanane bf', 'hananeboufadene@gmail.com', 'hanane20055', '1776875786_autumn fit.jpg'),
(2, 'lina bff', 'linabouaifel@gmail.com', 'lina20055', 'default.png'),
(3, 'Admin', 'admin@gmail.com', '1234', 'default.png');

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

DROP TABLE IF EXISTS `favoris`;
CREATE TABLE IF NOT EXISTS `favoris` (
  `idFavoris` int NOT NULL AUTO_INCREMENT,
  `idClient` int DEFAULT NULL,
  `idVoiture` int DEFAULT NULL,
  PRIMARY KEY (`idFavoris`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `favoris`
--

INSERT INTO `favoris` (`idFavoris`, `idClient`, `idVoiture`) VALUES
(1, 2, 1),
(17, 3, 1),
(25, 1, 3),
(16, 1, 1),
(22, 1, 11);

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `idPaiement` int NOT NULL AUTO_INCREMENT,
  `montant` decimal(10,2) DEFAULT NULL,
  `mode` varchar(50) DEFAULT NULL,
  `transactionID` varchar(100) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `idReservation` int DEFAULT NULL,
  PRIMARY KEY (`idPaiement`),
  KEY `idReservation` (`idReservation`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`idPaiement`, `montant`, `mode`, `transactionID`, `statut`, `idReservation`) VALUES
(1, 38000.00, 'carte', 'TRX1776871025758', 'payé', 5),
(2, 38000.00, 'carte', 'TRX1776871154144', 'payé', 6),
(3, 58000.00, 'carte', 'TRX1776875583944', 'payé', 7),
(4, 47000.00, 'carte', 'TRX1776884630623', 'payé', 8),
(5, 50000.00, 'carte', 'TRX1776884777904', 'payé', 9),
(6, 45000.00, 'carte', 'TRX1776891874983', 'payé', 10),
(7, 45000.00, 'carte', 'TRX1776893293363', 'payé', 11),
(8, 45000.00, 'carte', 'TRX1776893690231', 'payé', 12),
(9, 45000.00, 'carte', 'TRX1776893774111', 'payé', 13),
(10, 50000.00, 'carte', 'TRX1776893796622', 'payé', 14),
(11, 50000.00, 'carte', 'TRX1776894202582', 'payé', 15),
(12, 50000.00, 'carte', 'TRX1776894205387', 'payé', 16),
(13, 45000.00, 'carte', 'TRX1776895087649', 'payé', 0),
(14, 50000.00, 'carte', 'TRX1776895381966', 'payé', 0),
(15, 52000.00, 'carte', 'TRX1776895411811', 'payé', 0),
(16, 60000.00, 'carte', 'TRX1776895995400', 'payé', 0),
(17, 48000.00, 'carte', 'TRX1776896563140', 'payé', 0),
(18, 58000.00, 'carte', 'TRX1776897622630', 'payé', 0),
(19, 60000.00, 'carte', 'TRX1776898299479', 'payé', 0),
(20, 7000.00, 'carte', 'TRX1776898765386', 'payé', 0),
(21, 45000.00, 'carte', 'TRX1776900633673', 'payé', 0),
(22, 4300.00, 'carte', 'TRX1776972934989', 'payé', 0),
(23, 5000.00, 'carte', 'TRX1776972991250', 'payé', 0),
(24, 58000.00, 'carte', 'TRX1776978157362', 'payé', 0),
(25, 60000.00, 'carte', 'TRX1776980576524', 'payé', 0),
(26, 55000.00, 'carte', 'TRX1776981305826', 'payé', 0),
(27, 57000.00, 'carte', 'TRX1776981981838', 'payé', 0),
(28, 60000.00, 'carte', 'TRX1776982018568', 'payé', 0),
(29, 60000.00, 'carte', 'TRX1776982690660', 'payé', 0),
(30, 60000.00, 'carte', 'TRX1776982709436', 'payé', 0),
(31, 60000.00, 'carte', 'TRX1776982977456', 'payé', 0),
(32, 40000.00, 'carte', 'TRX1776985118303', 'payé', 0),
(33, 38000.00, 'carte', 'TRX1776989634980', 'payé', 0),
(34, 53000.00, 'carte', 'TRX1776989881161', 'payé', 0),
(35, 50000.00, 'carte', 'TRX1776990098259', 'payé', 0),
(36, 55000.00, 'carte', 'TRX1776990209458', 'payé', 0);

-- --------------------------------------------------------

--
-- Structure de la table `proprietaire`
--

DROP TABLE IF EXISTS `proprietaire`;
CREATE TABLE IF NOT EXISTS `proprietaire` (
  `idProprietaire` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `motDePasse` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `soldeCompte` decimal(10,2) DEFAULT NULL,
  `statutVerification` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`idProprietaire`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `idReservation` int NOT NULL AUTO_INCREMENT,
  `dateDebut` date DEFAULT NULL,
  `dateFin` date DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `idClient` int DEFAULT NULL,
  `idVoiture` int DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `cin` varchar(50) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `lieu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idReservation`),
  KEY `idClient` (`idClient`),
  KEY `idVoiture` (`idVoiture`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`idReservation`, `dateDebut`, `dateFin`, `statut`, `idClient`, `idVoiture`, `nom`, `prenom`, `cin`, `telephone`, `lieu`) VALUES
(25, '2026-04-24', '2026-04-25', 'en attente', 3, 13, 'boufadene', 'hanane', 'jj', '0789589620', 'bejaia souk el tenine'),
(4, '2026-04-19', '2026-04-22', 'en attente', 2, 1, 'boufadene', 'hanane', '05221485', '0794194909', NULL),
(8, '2026-04-23', '2026-04-24', 'en attente', 3, 14, 'boufadene', 'hanane', '05221485', '0769582651', NULL),
(10, '2026-04-24', '2026-04-25', 'en attente', 3, 1, 'boufadene', 'houda', '12345678', '0696950577', 'bejaia souk el tenine'),
(11, '2026-04-24', '2026-04-25', 'en attente', 3, 1, 'boufadene', 'houda', '12345678', '0696950577', 'bejaia souk el tenine'),
(12, '2026-04-24', '2026-04-25', 'en attente', 3, 1, 'boufadene', 'houda', '12345678', '0696950577', 'bejaia souk el tenine'),
(13, '2026-04-24', '2026-04-25', 'en attente', 3, 1, 'boufadene', 'houda', '12345678', '0696950577', 'bejaia souk el tenine'),
(14, '2026-04-24', '2026-04-25', 'en attente', 3, 2, 'boufadene', 'houda', '12345678', '0696950577', 'bejaia souk el tenine'),
(15, '2026-04-24', '2026-04-25', 'en attente', 3, 2, 'boufadene', 'houda', '12345678', '0696950577', 'bejaia souk el tenine'),
(16, '2026-04-24', '2026-04-25', 'en attente', 3, 2, 'boufadene', 'houda', '12345678', '0696950577', 'bejaia souk el tenine'),
(28, '2026-04-24', '2026-04-25', 'en attente', 1, 6, 'boufadene', 'hanane', '14589625', '0189589620', 'bejaia souk el tenine'),
(26, '2026-04-24', '2026-04-25', 'en attente', 1, 33, 'boufadene', 'hanane', '14589625', '0189589620', 'bejaia souk el tenine');

-- --------------------------------------------------------

--
-- Structure de la table `voiture`
--

DROP TABLE IF EXISTS `voiture`;
CREATE TABLE IF NOT EXISTS `voiture` (
  `idVoiture` int NOT NULL AUTO_INCREMENT,
  `marque` varchar(100) DEFAULT NULL,
  `modele` varchar(100) DEFAULT NULL,
  `annee` int DEFAULT NULL,
  `prixParJour` decimal(10,2) DEFAULT NULL,
  `localisation` varchar(255) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT '1',
  `photos` varchar(255) DEFAULT NULL,
  `idProprietaire` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `couleur` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `telephoneProprietaire` varchar(30) DEFAULT NULL,
  `statut` varchar(20) DEFAULT 'pending',
  `nbPlaces` int DEFAULT '5',
  `categorie` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idVoiture`),
  KEY `idProprietaire` (`idProprietaire`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `voiture`
--

INSERT INTO `voiture` (`idVoiture`, `marque`, `modele`, `annee`, `prixParJour`, `localisation`, `disponible`, `photos`, `idProprietaire`, `image`, `description`, `couleur`, `status`, `telephoneProprietaire`, `statut`, `nbPlaces`, `categorie`) VALUES
(1, 'Lamborghini', 'Huracan EVO', 2023, 45000.00, 'Alger', 0, NULL, NULL, 'lamborghini_huracan.jpg', NULL, 'Yellow', 'pending', NULL, 'approved', 2, 'Sport'),
(2, 'Lamborghini', 'Urus', 2024, 50000.00, 'Oran', 0, NULL, NULL, 'lamborghini_urus.jpg', NULL, 'Mat Black', 'pending', NULL, 'approved', 5, 'SUV'),
(3, 'Ferrari', '488 Spider', 2022, 48000.00, 'Alger', 0, NULL, NULL, 'ferrari_488.jpg', NULL, 'Red', 'pending', NULL, 'approved', 2, 'Sport'),
(4, 'Ferrari', 'F8 Tributo', 2023, 52000.00, 'Constantine', 0, NULL, NULL, 'ferrari_f8.jpg', NULL, 'Red', 'pending', NULL, 'approved', 2, 'Sport'),
(5, 'Rolls Royce', 'Phantom', 2024, 60000.00, 'Alger', 1, NULL, NULL, 'rolls_phantom.jpg', NULL, 'White', 'pending', NULL, 'approved', 5, 'Berline'),
(6, 'Rolls Royce', 'Cullinan', 2023, 58000.00, 'Oran', 0, NULL, NULL, 'rolls_cullinan.jpg', NULL, 'Black', 'pending', NULL, 'approved', 5, 'SUV'),
(7, 'Bentley', 'Continental GT', 2023, 55000.00, 'Setif', 1, NULL, NULL, 'bentley_gt.jpg', NULL, 'Green', 'pending', NULL, 'approved', 5, NULL),
(8, 'Bentley', 'Bentayga', 2024, 57000.00, 'Alger', 1, NULL, NULL, 'bentley_bentayga.jpg', NULL, 'Black', 'pending', NULL, 'approved', 5, 'SUV'),
(9, 'Mercedes', 'AMG GT R', 2023, 42000.00, 'Oran', 1, NULL, NULL, 'amg_gtr.jpg', NULL, 'Yellow', 'pending', NULL, 'approved', 2, 'Sport'),
(10, 'Mercedes', 'S Class Maybach', 2024, 60000.00, 'Alger', 1, NULL, NULL, 'maybach_s.jpg', NULL, 'Black', 'pending', NULL, 'approved', 5, 'Berline'),
(11, 'BMW', 'M8 Competition', 2023, 40000.00, 'Constantine', 1, NULL, NULL, 'bmw_m8.jpg', NULL, 'Blue', 'pending', NULL, 'approved', 5, 'Luxe'),
(12, 'BMW', 'X7 M60i', 2024, 38000.00, 'Alger', 1, NULL, NULL, 'bmw_x7.jpg', NULL, 'Black', 'pending', NULL, 'approved', 5, 'Luxe'),
(13, 'Porsche', '911 Turbo S', 2023, 45000.00, 'Oran', 0, NULL, NULL, 'porsche_911.jpg', NULL, 'Silver', 'pending', NULL, 'approved', 2, 'Sport'),
(14, 'Porsche', 'Cayenne Turbo GT', 2024, 47000.00, 'Setif', 1, NULL, NULL, 'porsche_cayenne.jpg', NULL, 'Grey', 'pending', NULL, 'approved', 5, 'SUV'),
(15, 'Aston Martin', 'DB11', 2023, 50000.00, 'Alger', 1, NULL, NULL, 'aston_db11.jpg', NULL, 'Green', 'pending', NULL, 'approved', 2, 'Sport'),
(16, 'Aston Martin', 'Vantage', 2022, 46000.00, 'Oran', 1, NULL, NULL, 'aston_vantage.jpg', NULL, 'Blue', 'pending', NULL, 'approved', 2, 'Sport'),
(17, 'McLaren', '720S', 2023, 55000.00, 'Alger', 1, NULL, NULL, 'mclaren_720s.jpg', NULL, 'Orange', 'pending', NULL, 'approved', 2, 'Sport'),
(18, 'McLaren', 'GT', 2024, 53000.00, 'Constantine', 1, NULL, NULL, 'mclaren_gt.jpg', NULL, 'White', 'pending', NULL, 'approved', 2, 'Sport'),
(19, 'Range Rover', 'Autobiography', 2024, 45000.00, 'Alger', 1, NULL, NULL, 'range_rover_autobiography.jpg', NULL, 'Black', 'pending', NULL, 'approved', 5, 'SUV'),
(20, 'Range Rover', 'Sport SVR', 2023, 43000.00, 'Setif', 1, NULL, NULL, 'range_rover_sport.jpg', NULL, 'Grey', 'pending', NULL, 'approved', 5, 'SUV'),
(21, 'Peageot ', '2008', 2008, 7000.00, 'bejaia', 1, NULL, 1, 'peageot_2008.jpg', 'bon etat ', 'grey', 'pending', '0769582651', 'approved', 5, 'Citadine'),
(23, 'Toyota Corolla', 'corolla', 2019, 5000.00, 'bejaia', 1, NULL, NULL, 'Toyota Corolla 2019 estreia mais barato.jpg', '', 'white', 'pending', NULL, 'approved', 5, 'Citadine'),
(24, 'Volkswagen Golf', 'golf', 2018, 5000.00, 'bejaia', 1, NULL, NULL, 'Volkswagen Golf generation VIII, factory press photo, 10_2019.jpg', '', 'white', 'pending', NULL, 'approved', 5, 'Citadine'),
(25, 'Renault', 'Clio', 2022, 4500.00, 'Alger', 1, NULL, NULL, '1776887205_Renault Clio.jpg', '', 'White', 'pending', NULL, 'approved', 5, 'Citadine'),
(26, 'Peugeot', '208', 2023, 4800.00, 'Oran', 1, NULL, NULL, '1776887308_2014 208 GTi Peugeot.jpg', '', 'Red', 'pending', NULL, 'approved', 5, 'Citadine'),
(27, 'Toyota', 'Yaris', 2021, 4000.00, 'Setif', 1, NULL, NULL, '1776887394_Toyota Yaris to be Launched at Auto Expo  2018.jpg', '', 'Blue', 'pending', NULL, 'approved', 5, 'Citadine'),
(28, 'Hyundai', 'i20', 2022, 4200.00, 'Constantine', 1, NULL, NULL, '1776887459_HYUNDAI I 20.jpg', '', 'Grey', 'pending', NULL, 'approved', 5, 'Citadine'),
(29, 'Kia', 'Picanto', 2023, 3800.00, 'Alger', 1, NULL, NULL, '1776887539_Kia Picanto.jpg', '', 'Black', 'pending', NULL, 'approved', 5, 'Citadine'),
(30, 'Volkswagen', 'Polo', 2022, 4700.00, 'Oran', 1, NULL, NULL, '1776887674_Monkey Motor - Noticias de autos y lanzamientos en Argentina.jpg', '', 'White', 'pending', NULL, 'approved', 5, 'Citadine'),
(31, 'Fiat', '500', 2021, 3900.00, 'Bejaia', 1, NULL, NULL, '1776887727_New Cars & SUVs _ Harper Maserati _ Knoxville TN - Maserati Levante or Ghibli.jpg', '', 'Yellow', 'pending', NULL, 'approved', 5, 'Citadine'),
(32, 'Dacia', 'Sandero', 2023, 3600.00, 'Setif', 1, NULL, NULL, '1776887774_Ablagematten kompatibel mit Dacia Sandero III 2020-2022, Silber.jpg', '', 'Blue', 'pending', NULL, 'approved', 5, 'Citadine'),
(33, 'Opel', 'Corsa', 2022, 4300.00, 'Alger', 0, NULL, NULL, '1776887837_New Opel Specials on Offer - Best Opel Car Deals - CFAO Mobility.jpg', '', 'Red', 'pending', NULL, 'approved', 5, 'Citadine'),
(34, 'Ford', 'Fiesta', 2021, 4100.00, 'Oran', 1, NULL, NULL, '1776887891_Ford Fiesta.jpg', '', 'Grey', 'pending', NULL, 'approved', 5, 'Citadine'),
(35, 'Suzuki', 'Swift', 2023, 4000.00, 'Constantine', 1, NULL, NULL, '1776887948_télécharger (23).jpg', '', 'White', 'pending', NULL, 'approved', 5, 'Citadine'),
(36, 'Nissan', 'Micra', 2022, 4200.00, 'Setif', 1, NULL, NULL, '1776887999_Nissan Micra Photos and Specs_ Photo_ Micra Nissan specs and 25 perfect photos of Nissan Micra.jpg', '', 'Black', 'pending', NULL, 'approved', 5, 'Citadine'),
(37, 'Chevrolet', 'Spark', 2021, 3500.00, 'Alger', 1, NULL, NULL, '1776888043_Discontinued Chevrolet Cars, Trucks, and SUVs.jpg', '', 'Blue', 'pending', NULL, 'approved', 5, 'Citadine'),
(38, 'Skoda', 'Fabia', 2023, 4400.00, 'Oran', 1, NULL, NULL, '1776888085_télécharger (24).jpg', '', 'Grey', 'pending', NULL, 'approved', 5, 'Citadine'),
(39, 'Seat', 'Ibiza', 2022, 4600.00, 'Bejaia', 1, NULL, NULL, '1776888127_Skoda Fabia Monte Carlo, press photo, Czech Rep_, 2_2022.jpg', '', 'Red', 'pending', NULL, 'approved', 5, 'Citadine'),
(40, 'Renault', 'Megane', 2020, 9000.00, 'Alger', 1, NULL, NULL, '1776888439_télécharger (25).jpg', '', 'White', 'pending', NULL, 'approved', 5, 'Berline'),
(41, 'Peugeot', '308', 2019, 12000.00, 'Oran', 1, NULL, NULL, '1776888489_Peugeot 308 GT 2020.jpg', '', 'Blue', 'pending', NULL, 'approved', 5, 'Citadine'),
(42, 'Toyota', 'Corolla', 2019, 8000.00, 'Bejaia', 1, NULL, NULL, '1776888522_télécharger (26).jpg', '', 'Black', 'pending', NULL, 'approved', 5, 'Citadine'),
(43, 'Volkswagen', 'Golf 7', 2018, 9500.00, 'Setif', 1, NULL, NULL, '1776888569_télécharger (27).jpg', '', 'Grey', 'pending', NULL, 'approved', 5, 'Citadine'),
(44, 'Hyundai', 'Tucson', 2019, 12000.00, 'Constantine', 1, NULL, NULL, '1776888611_Hyundai Tucson.jpg', '', 'White', 'pending', NULL, 'approved', 5, 'SUV'),
(45, 'Nissan', 'Qashqai', 2019, 13000.00, 'Oran', 1, NULL, NULL, '1776888643_Awesome Nissan 2017_ Nissan Qashqai y X-Trail Black Edition de edición limitada Cars Check more at http___carboard_pro_Cars-Gallery_2017_nissan-2017-nissan-qashqai-y-x-trail-black-edition-de-edicion-limitada-cars_.jpg', '', 'Black', 'pending', NULL, 'approved', 5, 'SUV'),
(46, 'Dacia', 'Duster', 2020, 10000.00, 'Alger', 1, NULL, NULL, '1776888714_télécharger (28).jpg', '', 'Grey', 'pending', NULL, 'approved', 5, 'SUV'),
(47, 'Ford', 'Focus', 2018, 8500.00, 'Bejaia', 1, NULL, NULL, '1776888747_2014 Ford Focus _ Bill Knight Ford _ 9607 S Memorial Dr _ Tulsa, OK 74133 _ (918) 301-1000 _ billknightford_com.jpg', '', 'Red', 'pending', NULL, 'approved', 5, 'Berline'),
(48, 'Honda ', 'civic', 2020, 9500.00, 'alger', 0, NULL, 3, 'Honda Civic Hatchback.jpg', 'bon etat', 'blanc', 'pending', '0769582651', 'pending', 5, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
