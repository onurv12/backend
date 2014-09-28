-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 28, 2014 at 12:28 PM
-- Server version: 5.5.37
-- PHP Version: 5.4.4-14+deb7u9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `paperdreamer`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admins`
--

CREATE TABLE IF NOT EXISTS `Admins` (
  `UserID` int(11) NOT NULL,
  `Deleteable` tinyint(1) NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Asset`
--

CREATE TABLE IF NOT EXISTS `Asset` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(512) NOT NULL,
  `UploaderID` int(10) unsigned NOT NULL,
  `ProjectID` int(10) unsigned NOT NULL,
  `Global` tinyint(1) NOT NULL,
  `Filename` varchar(128) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `Asset2Canvas`
--

CREATE TABLE IF NOT EXISTS `Asset2Canvas` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AssetID` int(10) unsigned NOT NULL,
  `CanvasID` int(10) unsigned NOT NULL,
  `Index` int(11) NOT NULL,
  `top` double unsigned NOT NULL,
  `left` double unsigned NOT NULL,
  `scaleX` double unsigned NOT NULL,
  `scaleY` double unsigned NOT NULL,
  `flipX` tinyint(1) NOT NULL,
  `flipY` tinyint(1) NOT NULL,
  `angle` float NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `Canvas`
--

CREATE TABLE IF NOT EXISTS `Canvas` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Title` varchar(512) NOT NULL,
  `Description` text NOT NULL,
  `Notes` text NOT NULL,
  `ProjectID` int(10) unsigned NOT NULL,
  `PositionIndex` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `Comments`
--

CREATE TABLE IF NOT EXISTS `Comments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ProjectID` int(11) NOT NULL,
  `Title` text NOT NULL,
  `Text` text NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Projects`
--

CREATE TABLE IF NOT EXISTS `Projects` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL DEFAULT 'New Project',
  `Description` text,
  `Approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `Session`
--

CREATE TABLE IF NOT EXISTS `Session` (
  `UserID` int(11) NOT NULL,
  `Token` varchar(255) NOT NULL,
  `Expiration` datetime NOT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Token` (`Token`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Tag`
--

CREATE TABLE IF NOT EXISTS `Tag` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `CreatorUserID` int(10) unsigned NOT NULL,
  `ParentTagID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Tag2Asset`
--

CREATE TABLE IF NOT EXISTS `Tag2Asset` (
  `TagID` int(10) unsigned NOT NULL,
  `AssetID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`TagID`,`AssetID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Fullname` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PasswordHash` text NOT NULL,
  `GravatarEmail` varchar(255) NOT NULL,
  `Suspended` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

-- --------------------------------------------------------

--
-- Table structure for table `UsersInProjects`
--

CREATE TABLE IF NOT EXISTS `UsersInProjects` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `Role` enum('Director','Supervisor','Artist','Observer') NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `UserID` (`UserID`),
  KEY `ProjectID` (`ProjectID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Admins`
--
ALTER TABLE `Admins`
  ADD CONSTRAINT `Admins_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Comments`
--
ALTER TABLE `Comments`
  ADD CONSTRAINT `Comments_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE SET NULL;

--
-- Constraints for table `Session`
--
ALTER TABLE `Session`
  ADD CONSTRAINT `Session_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `UsersInProjects`
--
ALTER TABLE `UsersInProjects`
  ADD CONSTRAINT `UsersInProjects_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UsersInProjects_ibfk_2` FOREIGN KEY (`ProjectID`) REFERENCES `Projects` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
