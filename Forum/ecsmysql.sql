-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2017 at 08:06 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecsmysql`
--

-- --------------------------------------------------------

--
-- Table structure for table `ban`
--

CREATE TABLE `ban` (
  `Username` varchar(64) NOT NULL,
  `ForumName` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chatroom`
--

CREATE TABLE `chatroom` (
  `RoomNumber` int(11) NOT NULL,
  `Content` text,
  `StartUser` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chatuser`
--

CREATE TABLE `chatuser` (
  `RoomNumber` int(11) NOT NULL,
  `Username` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forum`
--

CREATE TABLE `forum` (
  `ForumName` varchar(64) NOT NULL,
  `Picture` longblob,
  `Description` varchar(128) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `Moderator` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mailbox`
--


--
-- Table structure for table `pmailbox`
--

CREATE TABLE `pmailbox` (
  `MessageID` int(11) NOT NULL,
  `Subject` varchar(64) NOT NULL,
  `MsgTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MsgText` text,
  `Sender` varchar(32) NOT NULL,
  `Receiver` varchar(32) NOT NULL,
  `Status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `PostNumber` int(11) NOT NULL,
  `ThreadNumber` int(11) NOT NULL,
  `DateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `PostText` text,
  `User` varchar(64) DEFAULT NULL,
  `Status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `puser`
--

CREATE TABLE `puser` (
  `UserFullName` varchar(64) NOT NULL,
  `Username` varchar(32) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Status` varchar(14) DEFAULT NULL,
  `Banned` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `puser`
--

INSERT INTO `puser` (`UserFullName`, `Username`, `Password`, `Status`, `Banned`) VALUES
('Shawn Wang', 'swang', 'swang', 'administrator', 0),
('Carla Le', 'cle', '1234', 'user', 0),
('Karmi Le', 'kle', '1234', 'user', 0),
('Liam Le', 'lle', '1234', 'user', 0),
('Mark Le', 'mle', '1234', 'moderator', 0),
('Noah Le', 'nle', '1234', 'moderator', 0),
('Ryan Le', 'rle', '1234', 'user', 0),
('Valarie Le', 'vle', '1234', 'user', 0),
('Zion Le', 'zle', '1234', 'user', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rank`
--

CREATE TABLE `rank` (
  `Username` varchar(64) NOT NULL,
  `ThreadNumber` int(11) NOT NULL,
  `Ranking` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `thread`
--

CREATE TABLE `thread` (
  `ThreadNumber` int(11) NOT NULL,
  `Title` varchar(64) NOT NULL,
  `ForumName` varchar(64) NOT NULL,
  `DateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `StartUser` varchar(64) DEFAULT NULL,
  `Status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


ALTER TABLE `ban`
  ADD PRIMARY KEY (`Username`,`ForumName`);

--
-- Indexes for table `chatroom`
--
ALTER TABLE `chatroom`
  ADD PRIMARY KEY (`RoomNumber`);

--
-- Indexes for table `chatuser`
--
ALTER TABLE `chatuser`
  ADD PRIMARY KEY (`RoomNumber`,`Username`);

--
-- Indexes for table `forum`
--
ALTER TABLE `forum`
  ADD PRIMARY KEY (`ForumName`);

--
-- Indexes for table `pmailbox`
--
ALTER TABLE `pmailbox`
  ADD PRIMARY KEY (`MessageID`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`PostNumber`);

--
-- Indexes for table `puser`
--
ALTER TABLE `puser`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `rank`
--
ALTER TABLE `rank`
  ADD PRIMARY KEY (`Username`,`ThreadNumber`);

--
-- Indexes for table `thread`
--
ALTER TABLE `thread`
  ADD PRIMARY KEY (`ThreadNumber`);


--
-- AUTO_INCREMENT for table `chatroom`
--
ALTER TABLE `chatroom`
  MODIFY `RoomNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `pmailbox`
--
ALTER TABLE `pmailbox`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `PostNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `thread`
--
ALTER TABLE `thread`
  MODIFY `ThreadNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
