-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 21, 2021 at 06:31 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `friendsdb`
--
CREATE DATABASE IF NOT EXISTS `friendsdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `friendsdb`;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `from_user` int(11) NOT NULL,
  `to_user` int(11) NOT NULL,
  `date` date NOT NULL,
  `response` tinyint(1) NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `from_user`, `to_user`, `date`, `response`, `body`) VALUES
(1, 1, 2, '1970-01-01', 0, 'Accepted your friend request'),
(2, 1, 3, '1970-01-01', 0, 'Accepted your friend request'),
(3, 2, 3, '1970-01-01', 0, 'Accepted your friend request'),
(4, 3, 4, '1970-01-01', -1, 'Sent you a friend request'),
(5, 3, 5, '1970-01-01', 0, 'Accepted your friend request');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(256) NOT NULL,
  `birthday` date NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `city` varchar(20) NOT NULL,
  `country` varchar(3) NOT NULL,
  `profile_image` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `password`, `birthday`, `gender`, `city`, `country`, `profile_image`) VALUES
(1, 'ghyath', 'noureddine', 'ghyath@gmail.com', 'd6d804cc4a448c603748876defba925e9e3f780cee339eab0f580771fe190942', '2000-01-12', 1, 'Beirut', 'LBN', '../images/users/ghyath.png'),
(2, 'Charbel', 'Daoud', 'charbel@gmail.com', '4a96c83b0d3300c4e5d878b0cac4f3424360bf089bfbf1f0f5ae5c7dff953f62', '1980-01-01', 1, 'Beirut', 'LBN', '../images/users/male.jpg'),
(3, 'Elias', 'Chamoun', 'elias@gmail.com', 'de7ee76e7373c19bf7418e69cadecba96107bbb6d99524c516e1d37605dca829', '1981-02-01', 1, 'Beirut', 'LBN', '../images/users/male.jpg'),
(4, 'Male', 'user', 'male@gmail.com', '0d248e82c62c9386878327d491c762a002152d42ab2c391a31c44d9f62675ddf', '1984-06-10', 1, 'Belvord', 'BEL', '../images/users/male.jpg'),
(5, 'Female', 'user', 'female@gmail.com', '9f165139a8c2894a47aea23b77d330eca847264224a44d5a17b19db8b9a72c08', '1989-07-01', 0, 'New York', 'USA', '../images/users/female.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_block_list`
--

CREATE TABLE `user_block_list` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_block_list`
--

INSERT INTO `user_block_list` (`id`, `user_id`, `friend_id`) VALUES
(2, 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_friend_list`
--

CREATE TABLE `user_friend_list` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_friend_list`
--

INSERT INTO `user_friend_list` (`id`, `user_id`, `friend_id`) VALUES
(1, 2, 1),
(2, 3, 1),
(3, 3, 2),
(4, 5, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_user_id` (`from_user`),
  ADD KEY `to_user_id` (`to_user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_block_list`
--
ALTER TABLE `user_block_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_block_list_id` (`user_id`),
  ADD KEY `user_block_list_user` (`friend_id`);

--
-- Indexes for table `user_friend_list`
--
ALTER TABLE `user_friend_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `friend_id` (`friend_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_block_list`
--
ALTER TABLE `user_block_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_friend_list`
--
ALTER TABLE `user_friend_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `from_user_id` FOREIGN KEY (`from_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `to_user_id` FOREIGN KEY (`to_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_block_list`
--
ALTER TABLE `user_block_list`
  ADD CONSTRAINT `user_block_list_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_block_list_user` FOREIGN KEY (`friend_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_friend_list`
--
ALTER TABLE `user_friend_list`
  ADD CONSTRAINT `friend_id` FOREIGN KEY (`friend_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
