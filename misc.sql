-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 16, 2019 at 05:18 PM
-- Server version: 5.7.25
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `misc`
--
CREATE DATABASE IF NOT EXISTS `misc` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `misc`;

-- --------------------------------------------------------

--
-- Table structure for table `Position`
--

CREATE TABLE `Position` (
  `position_id` int(11) NOT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Position`
--

INSERT INTO `Position` (`position_id`, `profile_id`, `rank`, `year`, `description`) VALUES
(1, 10, 0, 123, 'fghfhfg'),
(2, 10, 1, 234, 'dsadasd'),
(3, 11, 0, 123, 'dasdas'),
(4, 11, 1, 234, 'yujyuj'),
(5, 11, 2, 345, 'sfsdasd'),
(6, 11, 3, 456, 'gjghj'),
(7, 12, 0, 987, 'asds'),
(8, 12, 1, 789, 'fdsfsf'),
(9, 13, 0, 123, 'igjhgjgjh'),
(10, 13, 1, 76876, 'drgdgfdg'),
(11, 16, 0, 123, 'sadasd');

-- --------------------------------------------------------

--
-- Table structure for table `Profile`
--

CREATE TABLE `Profile` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` text,
  `last_name` text,
  `email` text,
  `headline` text,
  `summary` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Profile`
--

INSERT INTO `Profile` (`profile_id`, `user_id`, `first_name`, `last_name`, `email`, `headline`, `summary`) VALUES
(2, 3, 'Vivienne', 'Bennett', 'vbennett@gmail.com', 'Web Designer', 'I love to design!'),
(3, 3, 'Jon', 'Smith', 'jsmith@gmail.com', 'PHP Developer', 'Looking for freelance PHP development work.'),
(4, 3, 'Simon', 'Kelly', 'skelly@gmail.com', 'CSS Coder', 'Looking for some CSS work. Contact me!'),
(5, 3, 'Nicholas', 'Davies', 'ndavies@gmail.com', 'asdasd', 'asdasdasda'),
(6, 3, 'Vivienne', 'Bennett', 'ndavies@gmail.com', 'asd', 'dsa'),
(7, 3, 'Nicholas', 'Davies', 'ndavies@gmail.com', 'qwewqeqw', 'dsadassd'),
(8, 3, 'Nicholas', 'Davies', 'ndavies@gmail.com', 'qwewqeqw', 'dsadassd'),
(9, 3, 'Nicholas', 'Davies', 'ndavies@gmail.com', 'dasdd', 'vxvcvxc'),
(10, 3, 'Nicholas', 'Davies', 'ndavies@gmail.com', 'asd', 'dsa'),
(11, 3, 'Vivienne', 'Bennett', 'ndavies@gmail.com', 'daasdas', 'fdsfsdf'),
(12, 3, 'Nicholas', 'Davies', 'ndavies@gmail.com', 'asdas', 'asdasdas'),
(13, 3, 'Vivienne', 'Bennett', 'ndavies@gmail.com', 'jgjhgh', 'hgjhgjhg'),
(16, 3, 'Nicholas', 'Davies', 'ndavies@gmail.com', 'asdasd', 'fsfdsfsdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`) VALUES
(1, 'Chuck', 'csev@umich.edu', '1a52e17fa899cf40fb04cfc42e6352f1'),
(2, 'UMSI', 'umsi@umich.edu', '1a52e17fa899cf40fb04cfc42e6352f1'),
(3, 'Nicholas', 'ndavies@gmail.com', '1a52e17fa899cf40fb04cfc42e6352f1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Position`
--
ALTER TABLE `Position`
  ADD PRIMARY KEY (`position_id`),
  ADD KEY `position_ibfk_1` (`profile_id`);

--
-- Indexes for table `Profile`
--
ALTER TABLE `Profile`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `profile_ibfk_2` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `email` (`email`),
  ADD KEY `email_2` (`email`),
  ADD KEY `password` (`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Position`
--
ALTER TABLE `Position`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `Profile`
--
ALTER TABLE `Profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Position`
--
ALTER TABLE `Position`
  ADD CONSTRAINT `position_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `Profile` (`profile_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Profile`
--
ALTER TABLE `Profile`
  ADD CONSTRAINT `profile_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
