-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 26, 2017 at 03:09 PM
-- Server version: 5.5.57-cll
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `softoyac_midascom`
--

-- --------------------------------------------------------

--
-- Table structure for table `servicecontact`
--

CREATE TABLE IF NOT EXISTS `servicecontact` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Company` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Services` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Contact_Person` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Contact_No` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `servicecontact`
--

INSERT INTO `servicecontact` (`Id`, `Company`, `Services`, `Contact_Person`, `Contact_No`) VALUES
(1, 'YAP CHEONG AIR-COND SERVICES', 'AIR-COND SERVICES', 'YAP CHEONG ', '012-283 1222');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
