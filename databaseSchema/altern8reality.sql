-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2017 at 10:49 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `altern8reality`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `permissions` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `permissions`) VALUES
(1, 'Standard user', ''),
(2, 'Administrator', '{"admin": 1}');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` int(11) NOT NULL,
  `shape` varchar(30) NOT NULL,
  `color` varchar(30) NOT NULL,
  `image_path` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `value`, `shape`, `color`, `image_path`) VALUES
(3, 'Jupiter', 12, 'sphere', 'FFFFFF', 'jupiter.jpg'),
(15, 'Sun', 22, 'sphere', 'FFFFFF', 'sun.jpg'),
(16, 'Earth', 12, 'sphere', 'FFFFFF', 'earth.jpg'),
(17, 'Robin Cube', 100, 'cube', 'FFFFFF', 'robin.jpg'),
(18, 'Eight Cube', 8, 'cube', 'FFFFFF', 'cube8.png'),
(19, 'Mars', 33, 'sphere', 'FFFFFF', 'mars.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `joined` datetime NOT NULL,
  `group` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `username`, `password`, `salt`, `joined`, `group`, `score`) VALUES
(2, 'Tigg Dilkie', 'tig@mail.com', '123 456', 'tiggy', 'c50240d9b697e4e410760f4d66d51d877a80216707326d0afc558ed96116b8f7', '?^„©†÷ÁÖ‹Ü?~	‰œr³ž¶~[iÄª…±öà¢Ë', '2017-03-14 13:13:37', 1, 34),
(3, 'MK Dickie', 'mk@ting.com', '25354653', 'mkd', '5a204e01fe98cc50dc125c8a7dc90cda020e644291b3532c2d9da8af585315ab', '°‡ù÷æþùîÙÜÏOÉt\0×nqÔýŽõ', '2017-03-16 22:34:37', 1, 8),
(4, 'Admin', 'admin@altern8reality.com', '123 456 788', 'admin', 'a10d403ea061721d45fc735504b6d206f70b99fc403bcf8f70d032c17f2b8bd4', 'ÔÝqñä€·Kƒ«x’$úuÇI_Èó«\n$fU¸oÌ¥]', '2017-05-11 11:01:53', 2, 0),
(5, 'Test Testy', 'test@test.org', '8564567', 'testy', 'baa1eb8344495163b68251617dc97ff89a904fdc2efeaeb4f9615a9d5c22c2a9', '?¢³`tßšòVA5%vCãmìÍq¡£A\nQeØ1×', '2017-05-13 13:42:01', 1, 21),
(6, 'John Smith', 'js@aol.com', '64653563', 'johnsmith', 'c310ffaa6e06c385220ea46a8feaa7332192556f8152d99a96e7831e5cbd29fe', 'Bj™“œ‰&Ô÷ÒþÁÖ ðcs9b•ó&Á¨Xx', '2017-05-14 16:19:45', 1, 5),
(7, 'Robin Wilkie', 'robinwilkie@hotmail.co.uk', '654 321', 'robin', 'f7ec672448e05a1dc38c49c32c2096a0abfc4ee80c478b014294ce80c73c775e', 'Ú	‹Ç_²hÌfÚ?§+FÍ¸%œ­ÇRìjí>#Œ«·', '2017-05-14 19:11:36', 1, 37),
(8, 'R Wilkie', 'robinwilkieglasgow@hotmail.com', '654653457', 'robinwilkie', 'c7c7de23db5d2590ba1927c92442c6505cc48f797324fa9d57f71f018e2450a9', 'Àj=[ÌÅ³tO\nF\n³ê­:q`½¨õK…ã?Ko«„8', '2017-05-15 15:09:50', 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `users_session`
--

CREATE TABLE `users_session` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_session`
--

INSERT INTO `users_session` (`id`, `user_id`, `hash`) VALUES
(1, 2, 'cd0eae1ffc2cff59894403f0ce41b4179f4b337dbd0aa570df945419301caadf'),
(3, 3, '2cccf3f926b6210fb6053c47326d17f53a39434f25cd0bd4ea53425957c979f3'),
(12, 5, 'bd1ba272e2ef472a281bc40c75862eba00b55f80a75245f70af90152c95d8c64');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_session`
--
ALTER TABLE `users_session`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `users_session`
--
ALTER TABLE `users_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
