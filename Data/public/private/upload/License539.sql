-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 28, 2017 at 09:44 AM
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
-- Table structure for table `property`
--

CREATE TABLE IF NOT EXISTS `property` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Landlord` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Company` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Tenant` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Department` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Business` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Area` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Property_Type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Rental` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `TNB` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Water` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `IWK` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Start` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `End` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Security_Deposit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Utility_Deposit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Termination_Notice` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Agreement` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Keys` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Owner` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Contact_Person` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Remarks` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`Id`, `Type`, `Address`, `Landlord`, `Company`, `Tenant`, `Department`, `Business`, `Area`, `Property_Type`, `Status`, `Rental`, `TNB`, `Water`, `IWK`, `Start`, `End`, `Security_Deposit`, `Utility_Deposit`, `Termination_Notice`, `Agreement`, `Keys`, `Owner`, `Contact_Person`, `Remarks`) VALUES
(3, 'Own Property', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(2, 'Rental', 'a', '', 'b', '', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', '06-Sep-2017', '30-Sep-2017', 'l', 'm', 'n', 'o', '', 'p', 'q', 'r');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
