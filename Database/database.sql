-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: 89.46.111.168
-- Generato il: Mar 03, 2020 alle 17:08
-- Versione del server: 5.7.29-32-log
-- Versione PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Sql1406421_1`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` char(75) NOT NULL,
  `pass` char(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1184131731 ;

--
-- Dump dei dati per la tabella `account`
--

INSERT INTO `account` (`id`, `email`, `pass`) VALUES
(1184131712, 'stefan@admin.com', 'admin'),
(1184131718, 'admin@admin.com', 'admin'),
(1184131721, 'htvgamer@gmail.com', '1234'),
(1184131726, 'staicustefan0001@gmail.com', '12345'),
(1184131728, 'lorenzosalmaso20000@gmail.com', '1234'),
(1184131729, 'citixe8962@jalcemail.net', '1234'),
(1184131730, 'andreibobirica99@gmail.com', '1234');

-- --------------------------------------------------------

--
-- Struttura della tabella `alert`
--

CREATE TABLE IF NOT EXISTS `alert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` char(75) NOT NULL,
  `check-in` date NOT NULL,
  `check-out` date NOT NULL,
  `account` int(11) NOT NULL,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `success` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `account` (`account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=92 ;

--
-- Dump dei dati per la tabella `alert`
--

INSERT INTO `alert` (`id`, `location`, `check-in`, `check-out`, `account`, `create`, `success`) VALUES
(90, 'Madrid', '2020-03-12', '2020-03-13', 1184131730, '2020-03-03 05:10:18', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `alert_filter`
--

CREATE TABLE IF NOT EXISTS `alert_filter` (
  `alert` int(11) NOT NULL,
  `priceMax` double DEFAULT NULL,
  `priceMin` double NOT NULL DEFAULT '0',
  `rest` tinyint(1) DEFAULT NULL,
  `park` tinyint(1) DEFAULT NULL,
  `wifi` tinyint(1) DEFAULT NULL,
  `break` tinyint(1) DEFAULT NULL,
  `privpark` tinyint(1) DEFAULT NULL,
  `pets` tinyint(1) DEFAULT NULL,
  `disab` tinyint(1) DEFAULT NULL,
  `ac` tinyint(1) DEFAULT NULL,
  `privbath` tinyint(1) DEFAULT NULL,
  `tv` tinyint(1) DEFAULT NULL,
  `minibar` tinyint(1) DEFAULT NULL,
  `safe` tinyint(1) DEFAULT NULL,
  `tecaffe` tinyint(1) DEFAULT NULL,
  `phone` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`alert`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `alert_filter`
--

INSERT INTO `alert_filter` (`alert`, `priceMax`, `priceMin`, `rest`, `park`, `wifi`, `break`, `privpark`, `pets`, `disab`, `ac`, `privbath`, `tv`, `minibar`, `safe`, `tecaffe`, `phone`) VALUES
(90, 1500, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `alert_hotel`
--

CREATE TABLE IF NOT EXISTS `alert_hotel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alert` int(11) NOT NULL,
  `hotel` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hotel` (`hotel`),
  KEY `alert` (`alert`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=89 ;

--
-- Dump dei dati per la tabella `alert_hotel`
--

INSERT INTO `alert_hotel` (`id`, `alert`, `hotel`) VALUES
(87, 90, 92);

-- --------------------------------------------------------

--
-- Struttura della tabella `alert_room`
--

CREATE TABLE IF NOT EXISTS `alert_room` (
  `nRooms` int(11) NOT NULL,
  `totpeop` int(11) NOT NULL,
  `alert` int(11) NOT NULL,
  PRIMARY KEY (`alert`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `alert_room`
--

INSERT INTO `alert_room` (`nRooms`, `totpeop`, `alert`) VALUES
(1, 1, 90);

-- --------------------------------------------------------

--
-- Struttura della tabella `alert_roompeople`
--

CREATE TABLE IF NOT EXISTS `alert_roompeople` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alert` int(11) NOT NULL,
  `people` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `alert` (`alert`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=72 ;

--
-- Dump dei dati per la tabella `alert_roompeople`
--

INSERT INTO `alert_roompeople` (`id`, `alert`, `people`) VALUES
(70, 90, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `book`
--

CREATE TABLE IF NOT EXISTS `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `since` date NOT NULL,
  `until` date NOT NULL,
  `account` int(11) NOT NULL,
  `tot` double NOT NULL,
  `hotel` int(11) NOT NULL,
  `resDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `book_detail`
--

CREATE TABLE IF NOT EXISTS `book_detail` (
  `room_type` int(11) NOT NULL,
  `priceR` double NOT NULL,
  `priceNR` double NOT NULL,
  `nRPR` int(11) NOT NULL,
  `nRPNR` int(11) NOT NULL,
  `book` int(11) NOT NULL,
  PRIMARY KEY (`room_type`,`book`),
  KEY `book` (`book`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `hotel`
--

CREATE TABLE IF NOT EXISTS `hotel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(75) NOT NULL,
  `location` int(11) NOT NULL,
  `hotelchain` int(11) NOT NULL,
  `img` char(75) DEFAULT NULL,
  `descr` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hotel_ibfk_1` (`hotelchain`),
  KEY `location` (`location`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=93 ;

--
-- Dump dei dati per la tabella `hotel`
--

INSERT INTO `hotel` (`id`, `name`, `location`, `hotelchain`, `img`, `descr`) VALUES
(90, 'Hotel Padovano', 80, 6, 'upload/57123520.jpg', 'Questo hotel in centro a padova, presenta una vista spettacolare sulla piazza principale ed circondato da attrazioni per tutte le eta .'),
(91, 'Bruginese', 81, 6, 'upload/bruginese23.PNG', 'Hotel in centro a Brugine nel padovano, ubicato in una ex villa Neoclassica.'),
(92, 'Hotel Felix Americas', 82, 6, 'upload/hotelcamericas.PNG', 'Hotel Felix Americas, las casas de los gringos');

-- --------------------------------------------------------

--
-- Struttura della tabella `hotelchain`
--

CREATE TABLE IF NOT EXISTS `hotelchain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prop` int(11) NOT NULL,
  `name` char(75) NOT NULL,
  `img` char(75) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hotelchain_ibfk_1` (`prop`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=7 ;

--
-- Dump dei dati per la tabella `hotelchain`
--

INSERT INTO `hotelchain` (`id`, `prop`, `name`, `img`) VALUES
(4, 1184131712, 'StefanelHotels', 'https://upload.wikimedia.org/wikipedia/commons/9/9a/Stefanel_logo.png'),
(6, 1184131718, 'Bob Andrei HotelChain', 'https://bit.ly/3cgrI5N');

-- --------------------------------------------------------

--
-- Struttura della tabella `hotel_detail`
--

CREATE TABLE IF NOT EXISTS `hotel_detail` (
  `hotel` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stars` int(11) NOT NULL,
  `dis` tinyint(1) DEFAULT NULL,
  `rest` tinyint(1) DEFAULT NULL,
  `park` tinyint(1) DEFAULT NULL,
  `wifi` tinyint(1) DEFAULT NULL,
  `break` tinyint(1) DEFAULT NULL,
  `privpark` tinyint(1) DEFAULT NULL,
  `animal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hotel_detail_ibfk_1` (`hotel`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=86 ;

--
-- Dump dei dati per la tabella `hotel_detail`
--

INSERT INTO `hotel_detail` (`hotel`, `id`, `stars`, `dis`, `rest`, `park`, `wifi`, `break`, `privpark`, `animal`) VALUES
(90, 83, 3, 1, 1, 0, 1, 1, 0, 1),
(91, 84, 2, 1, 0, 1, 1, 0, 0, 1),
(92, 85, 5, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lati` char(75) NOT NULL,
  `longi` char(75) NOT NULL,
  `street` char(75) NOT NULL,
  `mun` char(75) NOT NULL,
  `state` char(75) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

--
-- Dump dei dati per la tabella `location`
--

INSERT INTO `location` (`id`, `lati`, `longi`, `street`, `mun`, `state`) VALUES
(78, '', '', 'Madrid Centro', 'Madrid', 'Spain'),
(79, '', '', 'Via Ardoneghe', 'Brugine', 'Italy'),
(80, '45.406515150000004', '11.871260233394674', 'Duomo di Padova', 'Padova', ' Italia'),
(81, '45.397876883994776', '11.892519849579601', 'Via Antonio Simeone Sografi Stanga Padova', ' Veneto 35128', ' Italia'),
(82, '40.36330015', '-3.6707150824840764', 'Puente de Vallecas Madrid', ' Area metropolitana de Madrid y Corredor del Henares Community of Madrid 28', ' Spagna');

-- --------------------------------------------------------

--
-- Struttura della tabella `price_date`
--

CREATE TABLE IF NOT EXISTS `price_date` (
  `date` date NOT NULL,
  `disp` int(11) NOT NULL DEFAULT '0',
  `book` int(11) NOT NULL DEFAULT '0',
  `priceNR` double NOT NULL DEFAULT '0',
  `priceR` double NOT NULL DEFAULT '0',
  `room` int(11) NOT NULL,
  PRIMARY KEY (`date`,`room`),
  KEY `room` (`room`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `price_date`
--

INSERT INTO `price_date` (`date`, `disp`, `book`, `priceNR`, `priceR`, `room`) VALUES
('2020-05-31', 4, 0, 89.56, 98.23, 62),
('2020-05-31', 5, 0, 99.99, 121, 63),
('2020-05-31', 7, 0, 45.26, 59.86, 64),
('2020-05-31', 9, 0, 69, 112.23, 65),
('2020-06-01', 4, 0, 89.56, 98.23, 62),
('2020-06-01', 5, 0, 99.99, 121, 63),
('2020-06-01', 7, 0, 45.26, 59.86, 64),
('2020-06-01', 9, 0, 69, 112.23, 65),
('2020-06-02', 4, 0, 89.56, 98.23, 62),
('2020-06-02', 5, 0, 99.99, 121, 63),
('2020-06-02', 7, 0, 45.26, 59.86, 64),
('2020-06-02', 9, 0, 69, 112.23, 65),
('2020-06-03', 4, 0, 89.56, 98.23, 62),
('2020-06-03', 5, 0, 99.99, 121, 63),
('2020-06-03', 7, 0, 45.26, 59.86, 64),
('2020-06-03', 9, 0, 69, 112.23, 65),
('2020-06-04', 4, 0, 89.56, 98.23, 62),
('2020-06-04', 5, 0, 99.99, 121, 63),
('2020-06-04', 7, 0, 45.26, 59.86, 64),
('2020-06-04', 9, 0, 69, 112.23, 65),
('2020-06-05', 4, 0, 89.56, 98.23, 62),
('2020-06-05', 5, 0, 99.99, 121, 63),
('2020-06-05', 7, 0, 45.26, 59.86, 64),
('2020-06-05', 9, 0, 69, 112.23, 65),
('2020-06-06', 4, 0, 89.56, 98.23, 62),
('2020-06-06', 5, 0, 99.99, 121, 63),
('2020-06-06', 7, 0, 45.26, 59.86, 64),
('2020-06-06', 9, 0, 69, 112.23, 65),
('2020-06-07', 4, 0, 89.56, 98.23, 62),
('2020-06-07', 5, 0, 99.99, 121, 63),
('2020-06-07', 7, 0, 45.26, 59.86, 64),
('2020-06-07', 9, 0, 69, 112.23, 65),
('2020-06-08', 4, 0, 89.56, 98.23, 62),
('2020-06-08', 5, 0, 99.99, 121, 63),
('2020-06-08', 7, 0, 45.26, 59.86, 64),
('2020-06-08', 9, 0, 69, 112.23, 65),
('2020-06-09', 4, 0, 89.56, 98.23, 62),
('2020-06-09', 5, 0, 99.99, 121, 63),
('2020-06-09', 7, 0, 45.26, 59.86, 64),
('2020-06-09', 9, 0, 69, 112.23, 65),
('2020-06-10', 4, 0, 89.56, 98.23, 62),
('2020-06-10', 5, 0, 99.99, 121, 63),
('2020-06-10', 7, 0, 45.26, 59.86, 64),
('2020-06-10', 9, 0, 69, 112.23, 65),
('2020-06-11', 4, 0, 89.56, 98.23, 62),
('2020-06-11', 5, 0, 99.99, 121, 63),
('2020-06-11', 7, 0, 45.26, 59.86, 64),
('2020-06-11', 9, 0, 69, 112.23, 65),
('2020-06-12', 4, 0, 89.56, 98.23, 62),
('2020-06-12', 5, 0, 99.99, 121, 63),
('2020-06-12', 7, 0, 45.26, 59.86, 64),
('2020-06-12', 9, 0, 69, 112.23, 65),
('2020-06-13', 4, 0, 89.56, 98.23, 62),
('2020-06-13', 5, 0, 99.99, 121, 63),
('2020-06-13', 7, 0, 45.26, 59.86, 64),
('2020-06-13', 9, 0, 69, 112.23, 65),
('2020-06-14', 4, 0, 89.56, 98.23, 62),
('2020-06-14', 5, 0, 99.99, 121, 63),
('2020-06-14', 7, 0, 45.26, 59.86, 64),
('2020-06-14', 9, 0, 69, 112.23, 65),
('2020-06-15', 4, 0, 89.56, 98.23, 62),
('2020-06-15', 5, 0, 99.99, 121, 63),
('2020-06-15', 7, 0, 45.26, 59.86, 64),
('2020-06-15', 9, 0, 69, 112.23, 65),
('2020-06-16', 4, 0, 89.56, 98.23, 62),
('2020-06-16', 5, 0, 99.99, 121, 63),
('2020-06-16', 7, 0, 45.26, 59.86, 64),
('2020-06-16', 9, 0, 69, 112.23, 65),
('2020-06-17', 4, 0, 89.56, 98.23, 62),
('2020-06-17', 5, 0, 99.99, 121, 63),
('2020-06-17', 7, 0, 45.26, 59.86, 64),
('2020-06-17', 9, 0, 69, 112.23, 65),
('2020-06-18', 4, 0, 89.56, 98.23, 62),
('2020-06-18', 5, 0, 99.99, 121, 63),
('2020-06-18', 7, 0, 45.26, 59.86, 64),
('2020-06-18', 9, 0, 69, 112.23, 65),
('2020-06-19', 4, 0, 89.56, 98.23, 62),
('2020-06-19', 5, 0, 99.99, 121, 63),
('2020-06-19', 7, 0, 45.26, 59.86, 64),
('2020-06-19', 9, 0, 69, 112.23, 65),
('2020-06-20', 4, 0, 89.56, 98.23, 62),
('2020-06-20', 5, 0, 99.99, 121, 63),
('2020-06-20', 7, 0, 45.26, 59.86, 64),
('2020-06-20', 9, 0, 69, 112.23, 65),
('2020-06-21', 4, 0, 89.56, 98.23, 62),
('2020-06-21', 5, 0, 99.99, 121, 63),
('2020-06-21', 7, 0, 45.26, 59.86, 64),
('2020-06-21', 9, 0, 69, 112.23, 65),
('2020-06-22', 4, 0, 89.56, 98.23, 62),
('2020-06-22', 5, 0, 99.99, 121, 63),
('2020-06-22', 7, 0, 45.26, 59.86, 64),
('2020-06-22', 9, 0, 69, 112.23, 65),
('2020-06-23', 4, 0, 89.56, 98.23, 62),
('2020-06-23', 5, 0, 99.99, 121, 63),
('2020-06-23', 7, 0, 45.26, 59.86, 64),
('2020-06-23', 9, 0, 69, 112.23, 65),
('2020-06-24', 4, 0, 89.56, 98.23, 62),
('2020-06-24', 5, 0, 99.99, 121, 63),
('2020-06-24', 7, 0, 45.26, 59.86, 64),
('2020-06-24', 9, 0, 69, 112.23, 65),
('2020-06-25', 4, 0, 89.56, 98.23, 62),
('2020-06-25', 5, 0, 99.99, 121, 63),
('2020-06-25', 7, 0, 45.26, 59.86, 64),
('2020-06-25', 9, 0, 69, 112.23, 65),
('2020-06-26', 4, 0, 89.56, 98.23, 62),
('2020-06-26', 5, 0, 99.99, 121, 63),
('2020-06-26', 7, 0, 45.26, 59.86, 64),
('2020-06-26', 9, 0, 69, 112.23, 65),
('2020-06-27', 4, 0, 89.56, 98.23, 62),
('2020-06-27', 5, 0, 99.99, 121, 63),
('2020-06-27', 7, 0, 45.26, 59.86, 64),
('2020-06-27', 9, 0, 69, 112.23, 65),
('2020-06-28', 4, 0, 89.56, 98.23, 62),
('2020-06-28', 5, 0, 99.99, 121, 63),
('2020-06-28', 7, 0, 45.26, 59.86, 64),
('2020-06-28', 9, 0, 69, 112.23, 65),
('2020-06-29', 4, 0, 89.56, 98.23, 62),
('2020-06-29', 5, 0, 99.99, 121, 63),
('2020-06-29', 7, 0, 45.26, 59.86, 64),
('2020-06-29', 9, 0, 69, 112.23, 65),
('2020-06-30', 4, 0, 89.56, 98.23, 62),
('2020-06-30', 5, 0, 99.99, 121, 63),
('2020-06-30', 7, 0, 45.26, 59.86, 64),
('2020-06-30', 9, 0, 69, 112.23, 65),
('2020-07-01', 4, 0, 89.56, 98.23, 62),
('2020-07-01', 5, 0, 99.99, 121, 63),
('2020-07-01', 7, 0, 45.26, 59.86, 64),
('2020-07-01', 9, 0, 69, 112.23, 65),
('2020-07-02', 4, 0, 89.56, 98.23, 62),
('2020-07-02', 5, 0, 99.99, 121, 63),
('2020-07-02', 7, 0, 45.26, 59.86, 64),
('2020-07-02', 9, 0, 69, 112.23, 65),
('2020-07-03', 4, 0, 89.56, 98.23, 62),
('2020-07-03', 5, 0, 99.99, 121, 63),
('2020-07-03', 7, 0, 45.26, 59.86, 64),
('2020-07-03', 9, 0, 69, 112.23, 65),
('2020-07-04', 4, 0, 89.56, 98.23, 62),
('2020-07-04', 5, 0, 99.99, 121, 63),
('2020-07-04', 7, 0, 45.26, 59.86, 64),
('2020-07-04', 9, 0, 69, 112.23, 65),
('2020-07-05', 4, 0, 89.56, 98.23, 62),
('2020-07-05', 5, 0, 99.99, 121, 63),
('2020-07-05', 7, 0, 45.26, 59.86, 64),
('2020-07-05', 9, 0, 69, 112.23, 65),
('2020-07-06', 4, 0, 89.56, 98.23, 62),
('2020-07-06', 5, 0, 99.99, 121, 63),
('2020-07-06', 7, 0, 45.26, 59.86, 64),
('2020-07-06', 9, 0, 69, 112.23, 65),
('2020-07-07', 4, 0, 89.56, 98.23, 62),
('2020-07-07', 5, 0, 99.99, 121, 63),
('2020-07-07', 7, 0, 45.26, 59.86, 64),
('2020-07-07', 9, 0, 69, 112.23, 65),
('2020-07-08', 4, 0, 89.56, 98.23, 62),
('2020-07-08', 5, 0, 99.99, 121, 63),
('2020-07-08', 7, 0, 45.26, 59.86, 64),
('2020-07-08', 9, 0, 69, 112.23, 65),
('2020-07-09', 4, 0, 89.56, 98.23, 62),
('2020-07-09', 5, 0, 99.99, 121, 63),
('2020-07-09', 7, 0, 45.26, 59.86, 64),
('2020-07-09', 9, 0, 69, 112.23, 65),
('2020-07-10', 4, 0, 89.56, 98.23, 62),
('2020-07-10', 5, 0, 99.99, 121, 63),
('2020-07-10', 7, 0, 45.26, 59.86, 64),
('2020-07-10', 9, 0, 69, 112.23, 65),
('2020-07-11', 4, 0, 89.56, 98.23, 62),
('2020-07-11', 5, 0, 99.99, 121, 63),
('2020-07-11', 7, 0, 45.26, 59.86, 64),
('2020-07-11', 9, 0, 69, 112.23, 65),
('2020-07-12', 4, 0, 89.56, 98.23, 62),
('2020-07-12', 5, 0, 99.99, 121, 63),
('2020-07-12', 7, 0, 45.26, 59.86, 64),
('2020-07-12', 9, 0, 69, 112.23, 65),
('2020-07-13', 4, 0, 89.56, 98.23, 62),
('2020-07-13', 5, 0, 99.99, 121, 63),
('2020-07-13', 7, 0, 45.26, 59.86, 64),
('2020-07-13', 9, 0, 69, 112.23, 65),
('2020-07-14', 4, 0, 89.56, 98.23, 62),
('2020-07-14', 5, 0, 99.99, 121, 63),
('2020-07-14', 7, 0, 45.26, 59.86, 64),
('2020-07-14', 9, 0, 69, 112.23, 65),
('2020-07-15', 4, 0, 89.56, 98.23, 62),
('2020-07-15', 5, 0, 99.99, 121, 63),
('2020-07-15', 7, 0, 45.26, 59.86, 64),
('2020-07-15', 9, 0, 69, 112.23, 65),
('2020-07-16', 4, 0, 89.56, 98.23, 62),
('2020-07-16', 5, 0, 99.99, 121, 63),
('2020-07-16', 7, 0, 45.26, 59.86, 64),
('2020-07-16', 9, 0, 69, 112.23, 65),
('2020-07-17', 4, 0, 89.56, 98.23, 62),
('2020-07-17', 5, 0, 99.99, 121, 63),
('2020-07-17', 7, 0, 45.26, 59.86, 64),
('2020-07-17', 9, 0, 69, 112.23, 65),
('2020-07-18', 4, 0, 89.56, 98.23, 62),
('2020-07-18', 5, 0, 99.99, 121, 63),
('2020-07-18', 7, 0, 45.26, 59.86, 64),
('2020-07-18', 9, 0, 69, 112.23, 65),
('2020-07-19', 4, 0, 89.56, 98.23, 62),
('2020-07-19', 5, 0, 99.99, 121, 63),
('2020-07-19', 7, 0, 45.26, 59.86, 64),
('2020-07-19', 9, 0, 69, 112.23, 65),
('2020-07-20', 4, 0, 89.56, 98.23, 62),
('2020-07-20', 5, 0, 99.99, 121, 63),
('2020-07-20', 7, 0, 45.26, 59.86, 64),
('2020-07-20', 9, 0, 69, 112.23, 65),
('2020-07-21', 4, 0, 89.56, 98.23, 62),
('2020-07-21', 5, 0, 99.99, 121, 63),
('2020-07-21', 7, 0, 45.26, 59.86, 64),
('2020-07-21', 9, 0, 69, 112.23, 65),
('2020-07-22', 4, 0, 89.56, 98.23, 62),
('2020-07-22', 5, 0, 99.99, 121, 63),
('2020-07-22', 7, 0, 45.26, 59.86, 64),
('2020-07-22', 9, 0, 69, 112.23, 65),
('2020-07-23', 4, 0, 89.56, 98.23, 62),
('2020-07-23', 5, 0, 99.99, 121, 63),
('2020-07-23', 7, 0, 45.26, 59.86, 64),
('2020-07-23', 9, 0, 69, 112.23, 65),
('2020-07-24', 4, 0, 89.56, 98.23, 62),
('2020-07-24', 5, 0, 99.99, 121, 63),
('2020-07-24', 7, 0, 45.26, 59.86, 64),
('2020-07-24', 9, 0, 69, 112.23, 65),
('2020-07-25', 4, 0, 89.56, 98.23, 62),
('2020-07-25', 5, 0, 99.99, 121, 63),
('2020-07-25', 7, 0, 45.26, 59.86, 64),
('2020-07-25', 9, 0, 69, 112.23, 65),
('2020-07-26', 4, 0, 89.56, 98.23, 62),
('2020-07-26', 5, 0, 99.99, 121, 63),
('2020-07-26', 7, 0, 45.26, 59.86, 64),
('2020-07-26', 9, 0, 69, 112.23, 65),
('2020-07-27', 4, 0, 89.56, 98.23, 62),
('2020-07-27', 5, 0, 99.99, 121, 63),
('2020-07-27', 7, 0, 45.26, 59.86, 64),
('2020-07-27', 9, 0, 69, 112.23, 65),
('2020-07-28', 4, 0, 89.56, 98.23, 62),
('2020-07-28', 5, 0, 99.99, 121, 63),
('2020-07-28', 7, 0, 45.26, 59.86, 64),
('2020-07-28', 9, 0, 69, 112.23, 65),
('2020-07-29', 4, 0, 89.56, 98.23, 62),
('2020-07-29', 5, 0, 99.99, 121, 63),
('2020-07-29', 7, 0, 45.26, 59.86, 64),
('2020-07-29', 9, 0, 69, 112.23, 65),
('2020-07-30', 4, 0, 89.56, 98.23, 62),
('2020-07-30', 5, 0, 99.99, 121, 63),
('2020-07-30', 7, 0, 45.26, 59.86, 64),
('2020-07-30', 9, 0, 69, 112.23, 65),
('2020-07-31', 4, 0, 89.56, 98.23, 62),
('2020-07-31', 5, 0, 99.99, 121, 63),
('2020-07-31', 7, 0, 45.26, 59.86, 64),
('2020-07-31', 9, 0, 69, 112.23, 65),
('2020-08-01', 4, 0, 89.56, 98.23, 62),
('2020-08-01', 5, 0, 99.99, 121, 63),
('2020-08-01', 7, 0, 45.26, 59.86, 64),
('2020-08-01', 9, 0, 69, 112.23, 65),
('2020-08-02', 4, 0, 89.56, 98.23, 62),
('2020-08-02', 5, 0, 99.99, 121, 63),
('2020-08-02', 7, 0, 45.26, 59.86, 64),
('2020-08-02', 9, 0, 69, 112.23, 65),
('2020-08-03', 4, 0, 89.56, 98.23, 62),
('2020-08-03', 5, 0, 99.99, 121, 63),
('2020-08-03', 7, 0, 45.26, 59.86, 64),
('2020-08-03', 9, 0, 69, 112.23, 65),
('2020-08-04', 4, 0, 89.56, 98.23, 62),
('2020-08-04', 5, 0, 99.99, 121, 63),
('2020-08-04', 7, 0, 45.26, 59.86, 64),
('2020-08-04', 9, 0, 69, 112.23, 65),
('2020-08-05', 4, 0, 89.56, 98.23, 62),
('2020-08-05', 5, 0, 99.99, 121, 63),
('2020-08-05', 7, 0, 45.26, 59.86, 64),
('2020-08-05', 9, 0, 69, 112.23, 65),
('2020-08-06', 4, 0, 89.56, 98.23, 62),
('2020-08-06', 5, 0, 99.99, 121, 63),
('2020-08-06', 7, 0, 45.26, 59.86, 64),
('2020-08-06', 9, 0, 69, 112.23, 65),
('2020-08-07', 4, 0, 89.56, 98.23, 62),
('2020-08-07', 5, 0, 99.99, 121, 63),
('2020-08-07', 7, 0, 45.26, 59.86, 64),
('2020-08-07', 9, 0, 69, 112.23, 65),
('2020-08-08', 4, 0, 89.56, 98.23, 62),
('2020-08-08', 5, 0, 99.99, 121, 63),
('2020-08-08', 7, 0, 45.26, 59.86, 64),
('2020-08-08', 9, 0, 69, 112.23, 65),
('2020-08-09', 4, 0, 89.56, 98.23, 62),
('2020-08-09', 5, 0, 99.99, 121, 63),
('2020-08-09', 7, 0, 45.26, 59.86, 64),
('2020-08-09', 9, 0, 69, 112.23, 65),
('2020-08-10', 4, 0, 89.56, 98.23, 62),
('2020-08-10', 5, 0, 99.99, 121, 63),
('2020-08-10', 7, 0, 45.26, 59.86, 64),
('2020-08-10', 9, 0, 69, 112.23, 65),
('2020-08-11', 4, 0, 89.56, 98.23, 62),
('2020-08-11', 5, 0, 99.99, 121, 63),
('2020-08-11', 7, 0, 45.26, 59.86, 64),
('2020-08-11', 9, 0, 69, 112.23, 65),
('2020-08-12', 4, 0, 89.56, 98.23, 62),
('2020-08-12', 5, 0, 99.99, 121, 63),
('2020-08-12', 7, 0, 45.26, 59.86, 64),
('2020-08-12', 9, 0, 69, 112.23, 65),
('2020-08-13', 4, 0, 89.56, 98.23, 62),
('2020-08-13', 5, 0, 99.99, 121, 63),
('2020-08-13', 7, 0, 45.26, 59.86, 64),
('2020-08-13', 9, 0, 69, 112.23, 65),
('2020-08-14', 4, 0, 89.56, 98.23, 62),
('2020-08-14', 5, 0, 99.99, 121, 63),
('2020-08-14', 7, 0, 45.26, 59.86, 64),
('2020-08-14', 9, 0, 69, 112.23, 65),
('2020-08-15', 4, 0, 89.56, 98.23, 62),
('2020-08-15', 5, 0, 99.99, 121, 63),
('2020-08-15', 7, 0, 45.26, 59.86, 64),
('2020-08-15', 9, 0, 69, 112.23, 65),
('2020-08-16', 4, 0, 89.56, 98.23, 62),
('2020-08-16', 5, 0, 99.99, 121, 63),
('2020-08-16', 7, 0, 45.26, 59.86, 64),
('2020-08-16', 9, 0, 69, 112.23, 65),
('2020-08-17', 4, 0, 89.56, 98.23, 62),
('2020-08-17', 5, 0, 99.99, 121, 63),
('2020-08-17', 7, 0, 45.26, 59.86, 64),
('2020-08-17', 9, 0, 69, 112.23, 65),
('2020-08-18', 4, 0, 89.56, 98.23, 62),
('2020-08-18', 5, 0, 99.99, 121, 63),
('2020-08-18', 7, 0, 45.26, 59.86, 64),
('2020-08-18', 9, 0, 69, 112.23, 65),
('2020-08-19', 4, 0, 89.56, 98.23, 62),
('2020-08-19', 5, 0, 99.99, 121, 63),
('2020-08-19', 7, 0, 45.26, 59.86, 64),
('2020-08-19', 9, 0, 69, 112.23, 65),
('2020-08-20', 4, 0, 89.56, 98.23, 62),
('2020-08-20', 5, 0, 99.99, 121, 63),
('2020-08-20', 7, 0, 45.26, 59.86, 64),
('2020-08-20', 9, 0, 69, 112.23, 65),
('2020-08-21', 4, 0, 89.56, 98.23, 62),
('2020-08-21', 5, 0, 99.99, 121, 63),
('2020-08-21', 7, 0, 45.26, 59.86, 64),
('2020-08-21', 9, 0, 69, 112.23, 65),
('2020-08-22', 4, 0, 89.56, 98.23, 62),
('2020-08-22', 5, 0, 99.99, 121, 63),
('2020-08-22', 7, 0, 45.26, 59.86, 64),
('2020-08-22', 9, 0, 69, 112.23, 65),
('2020-08-23', 4, 0, 89.56, 98.23, 62),
('2020-08-23', 5, 0, 99.99, 121, 63),
('2020-08-23', 7, 0, 45.26, 59.86, 64),
('2020-08-23', 9, 0, 69, 112.23, 65),
('2020-08-24', 4, 0, 89.56, 98.23, 62),
('2020-08-24', 5, 0, 99.99, 121, 63),
('2020-08-24', 7, 0, 45.26, 59.86, 64),
('2020-08-24', 9, 0, 69, 112.23, 65),
('2020-08-25', 4, 0, 89.56, 98.23, 62),
('2020-08-25', 5, 0, 99.99, 121, 63),
('2020-08-25', 7, 0, 45.26, 59.86, 64),
('2020-08-25', 9, 0, 69, 112.23, 65),
('2020-08-26', 4, 0, 89.56, 98.23, 62),
('2020-08-26', 5, 0, 99.99, 121, 63),
('2020-08-26', 7, 0, 45.26, 59.86, 64),
('2020-08-26', 9, 0, 69, 112.23, 65),
('2020-08-27', 4, 0, 89.56, 98.23, 62),
('2020-08-27', 5, 0, 99.99, 121, 63),
('2020-08-27', 7, 0, 45.26, 59.86, 64),
('2020-08-27', 9, 0, 69, 112.23, 65),
('2020-08-28', 4, 0, 89.56, 98.23, 62),
('2020-08-28', 5, 0, 99.99, 121, 63),
('2020-08-28', 7, 0, 45.26, 59.86, 64),
('2020-08-28', 9, 0, 69, 112.23, 65),
('2020-08-29', 4, 0, 89.56, 98.23, 62),
('2020-08-29', 5, 0, 99.99, 121, 63),
('2020-08-29', 7, 0, 45.26, 59.86, 64),
('2020-08-29', 9, 0, 69, 112.23, 65),
('2020-08-30', 4, 0, 89.56, 98.23, 62),
('2020-08-30', 5, 0, 99.99, 121, 63),
('2020-08-30', 7, 0, 45.26, 59.86, 64),
('2020-08-30', 9, 0, 69, 112.23, 65),
('2020-08-31', 4, 0, 89.56, 98.23, 62),
('2020-08-31', 5, 0, 99.99, 121, 63),
('2020-08-31', 7, 0, 45.26, 59.86, 64),
('2020-08-31', 9, 0, 69, 112.23, 65),
('2020-09-01', 4, 0, 89.56, 98.23, 62),
('2020-09-01', 5, 0, 99.99, 121, 63),
('2020-09-01', 7, 0, 45.26, 59.86, 64),
('2020-09-01', 9, 0, 69, 112.23, 65),
('2020-09-02', 4, 0, 89.56, 98.23, 62),
('2020-09-02', 5, 0, 99.99, 121, 63),
('2020-09-02', 7, 0, 45.26, 59.86, 64),
('2020-09-02', 9, 0, 69, 112.23, 65),
('2020-09-03', 4, 0, 89.56, 98.23, 62),
('2020-09-03', 5, 0, 99.99, 121, 63),
('2020-09-03', 7, 0, 45.26, 59.86, 64),
('2020-09-03', 9, 0, 69, 112.23, 65),
('2020-09-04', 4, 0, 89.56, 98.23, 62),
('2020-09-04', 5, 0, 99.99, 121, 63),
('2020-09-04', 7, 0, 45.26, 59.86, 64),
('2020-09-04', 9, 0, 69, 112.23, 65),
('2020-09-05', 4, 0, 89.56, 98.23, 62),
('2020-09-05', 5, 0, 99.99, 121, 63),
('2020-09-05', 7, 0, 45.26, 59.86, 64),
('2020-09-05', 9, 0, 69, 112.23, 65),
('2020-09-06', 4, 0, 89.56, 98.23, 62),
('2020-09-06', 5, 0, 99.99, 121, 63),
('2020-09-06', 7, 0, 45.26, 59.86, 64),
('2020-09-06', 9, 0, 69, 112.23, 65);

-- --------------------------------------------------------

--
-- Struttura della tabella `prop`
--

CREATE TABLE IF NOT EXISTS `prop` (
  `company` char(75) NOT NULL,
  `locationProp` int(11) NOT NULL,
  `account` int(11) NOT NULL,
  PRIMARY KEY (`account`),
  KEY `locationProp` (`locationProp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `prop`
--

INSERT INTO `prop` (`company`, `locationProp`, `account`) VALUES
('Stefan Holding SPA', 78, 1184131712),
('Andrei''s Company SRL', 79, 1184131718);

-- --------------------------------------------------------

--
-- Struttura della tabella `room_detail`
--

CREATE TABLE IF NOT EXISTS `room_detail` (
  `ac` tinyint(1) DEFAULT NULL,
  `privatebath` tinyint(1) DEFAULT NULL,
  `tv` tinyint(1) DEFAULT NULL,
  `tecaffe` tinyint(1) DEFAULT NULL,
  `minibar` tinyint(1) DEFAULT NULL,
  `safe` tinyint(1) DEFAULT NULL,
  `tel` tinyint(1) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_detail_ibfk_1` (`room`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=87 ;

--
-- Dump dei dati per la tabella `room_detail`
--

INSERT INTO `room_detail` (`ac`, `privatebath`, `tv`, `tecaffe`, `minibar`, `safe`, `tel`, `id`, `room`) VALUES
(1, 0, 1, 0, 1, 1, 0, 83, 62),
(1, 1, 1, 1, 1, 1, 1, 84, 63),
(0, 1, 1, 0, 0, 0, 0, 85, 64),
(1, 1, 1, 1, 1, 1, 1, 86, 65);

-- --------------------------------------------------------

--
-- Struttura della tabella `room_img`
--

CREATE TABLE IF NOT EXISTS `room_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room` int(11) NOT NULL,
  `name` char(75) NOT NULL,
  `img` char(75) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room` (`room`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=112 ;

--
-- Dump dei dati per la tabella `room_img`
--

INSERT INTO `room_img` (`id`, `room`, `name`, `img`) VALUES
(105, 62, '', 'upload/camInd.PNG'),
(106, 62, '', 'upload/camind24.5.PNG'),
(107, 63, '', 'upload/doppiasuperior232.PNG'),
(108, 64, '', 'upload/tripleroom3432.PNG'),
(109, 64, '', 'upload/tripleroom342.PNG'),
(110, 65, '', 'upload/lux34232.PNG'),
(111, 65, '', 'upload/lux384273.PNG');

-- --------------------------------------------------------

--
-- Struttura della tabella `room_type`
--

CREATE TABLE IF NOT EXISTS `room_type` (
  `hotel` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod` char(75) DEFAULT NULL,
  `nameBea` char(75) DEFAULT NULL,
  `bedSingle` int(11) NOT NULL DEFAULT '0',
  `bedDouble` int(11) NOT NULL DEFAULT '0',
  `sofa` int(11) NOT NULL DEFAULT '0',
  `room` int(11) NOT NULL DEFAULT '1',
  `quantity` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `hotel` (`hotel`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=66 ;

--
-- Dump dei dati per la tabella `room_type`
--

INSERT INTO `room_type` (`hotel`, `id`, `cod`, `nameBea`, `bedSingle`, `bedDouble`, `sofa`, `room`, `quantity`) VALUES
(90, 62, 'IND', 'Camera Individuale', 1, 0, 0, 1, 5),
(90, 63, 'DBL Superior', 'Doppia Super', 0, 1, 1, 2, 4),
(91, 64, 'TRPL', 'Camera Tripla', 1, 1, 1, 2, 6),
(92, 65, 'IND', 'Individual Superior With guest', 2, 0, 1, 2, 10);

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `name` char(75) NOT NULL,
  `surname` char(75) NOT NULL,
  `birthdate` date DEFAULT '1970-12-31',
  `sex` char(1) DEFAULT NULL,
  `account` int(11) NOT NULL,
  `photo` char(75) DEFAULT NULL,
  PRIMARY KEY (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`name`, `surname`, `birthdate`, `sex`, `account`, `photo`) VALUES
('Stefan', 'Staicu', '1970-12-31', '', 1184131712, NULL),
('Administrator', 'Admin', '1970-12-31', 'M', 1184131718, NULL),
('Andrei Cristian', 'Bobirica', '1970-12-31', 'M', 1184131721, NULL),
('Stefan', 'Staicu', '1970-12-31', 'M', 1184131726, NULL),
('Lorenzo', 'Salmaso', '1970-12-31', 'M', 1184131728, NULL),
('citixe8962@jalcemail.net', 'citixe8962@jalcemail.net', '1970-12-31', 'M', 1184131729, NULL),
('Andrei', 'Cristian', '1970-12-31', 'M', 1184131730, NULL);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `alert`
--
ALTER TABLE `alert`
  ADD CONSTRAINT `alert_ibfk_1` FOREIGN KEY (`account`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `alert_filter`
--
ALTER TABLE `alert_filter`
  ADD CONSTRAINT `alert_filter_ibfk_1` FOREIGN KEY (`alert`) REFERENCES `alert` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `alert_hotel`
--
ALTER TABLE `alert_hotel`
  ADD CONSTRAINT `alert_hotel_ibfk_1` FOREIGN KEY (`hotel`) REFERENCES `hotel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `alert_hotel_ibfk_2` FOREIGN KEY (`alert`) REFERENCES `alert` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `alert_room`
--
ALTER TABLE `alert_room`
  ADD CONSTRAINT `alert_room_ibfk_1` FOREIGN KEY (`alert`) REFERENCES `alert` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `alert_roompeople`
--
ALTER TABLE `alert_roompeople`
  ADD CONSTRAINT `alert_roompeople_ibfk_1` FOREIGN KEY (`alert`) REFERENCES `alert` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `book_detail`
--
ALTER TABLE `book_detail`
  ADD CONSTRAINT `book_detail_ibfk_1` FOREIGN KEY (`book`) REFERENCES `book` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `hotel`
--
ALTER TABLE `hotel`
  ADD CONSTRAINT `hotel_ibfk_1` FOREIGN KEY (`hotelchain`) REFERENCES `hotelchain` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hotel_ibfk_2` FOREIGN KEY (`location`) REFERENCES `location` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `hotelchain`
--
ALTER TABLE `hotelchain`
  ADD CONSTRAINT `hotelchain_ibfk_1` FOREIGN KEY (`prop`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `hotel_detail`
--
ALTER TABLE `hotel_detail`
  ADD CONSTRAINT `hotel_detail_ibfk_1` FOREIGN KEY (`hotel`) REFERENCES `hotel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `price_date`
--
ALTER TABLE `price_date`
  ADD CONSTRAINT `price_date_ibfk_1` FOREIGN KEY (`room`) REFERENCES `room_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `prop`
--
ALTER TABLE `prop`
  ADD CONSTRAINT `prop_ibfk_2` FOREIGN KEY (`account`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prop_ibfk_3` FOREIGN KEY (`locationProp`) REFERENCES `location` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `room_detail`
--
ALTER TABLE `room_detail`
  ADD CONSTRAINT `room_detail_ibfk_1` FOREIGN KEY (`room`) REFERENCES `room_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `room_img`
--
ALTER TABLE `room_img`
  ADD CONSTRAINT `room_img_ibfk_1` FOREIGN KEY (`room`) REFERENCES `room_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `room_type`
--
ALTER TABLE `room_type`
  ADD CONSTRAINT `room_type_ibfk_1` FOREIGN KEY (`hotel`) REFERENCES `hotel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`account`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
