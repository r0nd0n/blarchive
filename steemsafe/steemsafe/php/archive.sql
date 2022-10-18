-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2019 at 03:35 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `archive`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounting`
--

CREATE TABLE `accounting` (
  `tx` varchar(40) NOT NULL,
  `user` varchar(16) NOT NULL,
  `paid` decimal(10,3) NOT NULL DEFAULT '0.000',
  `fileid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lookup`
--

CREATE TABLE `lookup` (
  `fileid` int(15) NOT NULL,
  `filepath` varchar(512) NOT NULL,
  `fileparts` int(11) NOT NULL DEFAULT '-1',
  `token` int(20) UNSIGNED NOT NULL,
  `token2` int(11) NOT NULL,
  `filetime` bigint(20) NOT NULL DEFAULT '0',
  `added` varchar(40) NOT NULL DEFAULT '0',
  `blockchain` varchar(40) NOT NULL DEFAULT '0',
  `user` varchar(15) DEFAULT NULL,
  `trans` varchar(40) NOT NULL DEFAULT '0',
  `payment` decimal(10,3) NOT NULL DEFAULT '0.000',
  `warn` int(11) NOT NULL DEFAULT '0',
  `bot` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `fileid` int(15) NOT NULL,
  `fileindex` int(7) NOT NULL,
  `tickerid` varchar(40) NOT NULL,
  `filedate` bigint(20) NOT NULL,
  `filename` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounting`
--
ALTER TABLE `accounting`
  ADD PRIMARY KEY (`tx`,`fileid`);

--
-- Indexes for table `lookup`
--
ALTER TABLE `lookup`
  ADD PRIMARY KEY (`fileid`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`fileid`,`fileindex`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lookup`
--
ALTER TABLE `lookup`
  MODIFY `fileid` int(15) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
