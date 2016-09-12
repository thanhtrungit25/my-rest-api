-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2016 at 12:35 AM
-- Server version: 5.7.11
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `robot`
--

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(70) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`id`, `name`) VALUES
(1, 'head'),
(2, 'body'),
(3, 'foot');

-- --------------------------------------------------------

--
-- Table structure for table `robots`
--

CREATE TABLE `robots` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(70) NOT NULL,
  `type` varchar(32) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `robots`
--

INSERT INTO `robots` (`id`, `name`, `type`, `year`) VALUES
(1, 'Robotina', 'mechanical', 1972),
(2, 'Astro Boy', 'mechanical', 1952),
(3, 'Terminator', 'virtual', 2000);

-- --------------------------------------------------------

--
-- Table structure for table `robots_parts`
--

CREATE TABLE `robots_parts` (
  `id` int(10) UNSIGNED NOT NULL,
  `robots_id` int(10) NOT NULL,
  `parts_id` int(10) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `robots_parts`
--

INSERT INTO `robots_parts` (`id`, `robots_id`, `parts_id`, `created_at`) VALUES
(1, 1, 1, '2016-03-12'),
(2, 1, 2, '2016-03-12'),
(3, 1, 3, '2016-09-12'),
(4, 2, 2, '2016-09-12'),
(5, 3, 1, '2016-09-12'),
(6, 3, 3, '2016-09-12');

-- --------------------------------------------------------

--
-- Table structure for table `robots_similar`
--

CREATE TABLE `robots_similar` (
  `id` int(10) UNSIGNED NOT NULL,
  `robots_id` int(10) UNSIGNED NOT NULL,
  `similar_robots_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `robots_similar`
--

INSERT INTO `robots_similar` (`id`, `robots_id`, `similar_robots_id`) VALUES
(1, 3, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `robots`
--
ALTER TABLE `robots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `robots_parts`
--
ALTER TABLE `robots_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `robots_id` (`robots_id`),
  ADD KEY `parts_id` (`parts_id`);

--
-- Indexes for table `robots_similar`
--
ALTER TABLE `robots_similar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `robots_id` (`robots_id`),
  ADD KEY `similar_robots_id` (`similar_robots_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `robots`
--
ALTER TABLE `robots`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `robots_parts`
--
ALTER TABLE `robots_parts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `robots_similar`
--
ALTER TABLE `robots_similar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
