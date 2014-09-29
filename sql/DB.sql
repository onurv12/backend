-- phpMyAdmin SQL Dump
-- version 4.2.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 28. Sep 2014 um 14:40
-- Server Version: 5.6.19
-- PHP-Version: 5.5.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
--
-- Datenbank: `paperdreamer`

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Admins`
--

CREATE TABLE IF NOT EXISTS `Admins` (
  `UserID` int(11) NOT NULL,
  `Deleteable` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `Admins`
--

INSERT INTO `Admins` (`UserID`, `Deleteable`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Asset`
--

CREATE TABLE IF NOT EXISTS `Asset` (
`ID` int(10) unsigned NOT NULL,
  `Name` varchar(512) NOT NULL,
  `UploaderID` int(10) unsigned NOT NULL,
  `ProjectID` int(10) unsigned NOT NULL,
  `Global` tinyint(1) NOT NULL,
  `Filename` varchar(128) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Asset2Canvas`
--

CREATE TABLE IF NOT EXISTS `Asset2Canvas` (
`ID` int(10) unsigned NOT NULL,
  `AssetID` int(10) unsigned NOT NULL,
  `CanvasID` int(10) unsigned NOT NULL,
  `Index` int(11) NOT NULL DEFAULT '1',
  `top` double NOT NULL DEFAULT '0',
  `left` double NOT NULL DEFAULT '0',
  `scaleX` double NOT NULL DEFAULT '1',
  `scaleY` double NOT NULL DEFAULT '1',
  `flipX` tinyint(1) NOT NULL DEFAULT '0',
  `flipY` tinyint(1) NOT NULL DEFAULT '0',
  `angle` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Canvas`
--

CREATE TABLE IF NOT EXISTS `Canvas` (
`ID` int(10) unsigned NOT NULL,
  `Title` varchar(512) NOT NULL,
  `Description` text NOT NULL,
  `Notes` text NOT NULL,
  `ProjectID` int(10) unsigned NOT NULL,
  `PositionIndex` int(10) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Projects`
--

CREATE TABLE IF NOT EXISTS `Projects` (
`ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL DEFAULT 'New Project',
  `Description` text,
  `Approved` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Session`
--

CREATE TABLE IF NOT EXISTS `Session` (
  `UserID` int(11) NOT NULL,
  `Token` varchar(255) NOT NULL,
  `Expiration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Tag`
--

CREATE TABLE IF NOT EXISTS `Tag` (
`ID` int(10) unsigned NOT NULL,
  `Name` varchar(50) NOT NULL,
  `CreatorUserID` int(10) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Tag2Asset`
--

CREATE TABLE IF NOT EXISTS `Tag2Asset` (
  `TagID` int(10) unsigned NOT NULL,
  `AssetID` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
`ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Fullname` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PasswordHash` text NOT NULL,
  `GravatarEmail` varchar(255) NOT NULL,
  `Suspended` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Daten für Tabelle `Users`
--

INSERT INTO `Users` (`ID`, `Name`, `Fullname`, `Email`, `PasswordHash`, `GravatarEmail`, `Suspended`) VALUES
(1, 'admin', 'Admin', '', 'f6fdffe48c908deb0f4c3bd36c032e72', '', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `UsersInProjects`
--

CREATE TABLE IF NOT EXISTS `UsersInProjects` (
`ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `Role` enum('Director','Supervisor','Artist','Observer') NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admins`
--
ALTER TABLE `Admins`
 ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `Asset`
--
ALTER TABLE `Asset`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Asset2Canvas`
--
ALTER TABLE `Asset2Canvas`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `Canvas`
--
ALTER TABLE `Canvas`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Projects`
--
ALTER TABLE `Projects`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Session`
--
ALTER TABLE `Session`
 ADD PRIMARY KEY (`UserID`), ADD UNIQUE KEY `Token` (`Token`), ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `Tag`
--
ALTER TABLE `Tag`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `Tag2Asset`
--
ALTER TABLE `Tag2Asset`
 ADD PRIMARY KEY (`TagID`,`AssetID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `UsersInProjects`
--
ALTER TABLE `UsersInProjects`
 ADD PRIMARY KEY (`ID`), ADD KEY `UserID` (`UserID`), ADD KEY `ProjectID` (`ProjectID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Asset`
--
ALTER TABLE `Asset`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `Asset2Canvas`
--
ALTER TABLE `Asset2Canvas`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `Canvas`
--
ALTER TABLE `Canvas`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `Projects`
--
ALTER TABLE `Projects`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Tag`
--
ALTER TABLE `Tag`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `UsersInProjects`
--
ALTER TABLE `UsersInProjects`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `Admins`
--
ALTER TABLE `Admins`
ADD CONSTRAINT `Admins_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `Session`
--
ALTER TABLE `Session`
ADD CONSTRAINT `Session_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `UsersInProjects`
--
ALTER TABLE `UsersInProjects`
ADD CONSTRAINT `UsersInProjects_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `UsersInProjects_ibfk_2` FOREIGN KEY (`ProjectID`) REFERENCES `Projects` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
