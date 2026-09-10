-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql200.infinityfree.com
-- Generation Time: Sep 10, 2026 at 10:41 AM
-- Server version: 11.4.13-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41852698_url_shortener`
--

-- --------------------------------------------------------

--
-- Table structure for table `urls`
--

CREATE TABLE `urls` (
  `id` int(10) UNSIGNED NOT NULL,
  `original_url` text NOT NULL,
  `short_code` varchar(32) NOT NULL,
  `clicks` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `urls`
--

INSERT INTO `urls` (`id`, `original_url`, `short_code`, `clicks`, `created_at`, `expires_at`, `is_active`) VALUES
(1, 'https://www.artofliving.com', 'lake', 0, '2026-09-06 13:25:55', '2026-09-06 10:25:55', 1),
(2, 'https://singhaniauniversity.ac.in/', 'portfolio123', 1, '2026-09-09 12:30:31', NULL, 1),
(3, 'https://www.rottentomatoes.com', 'tYsRHrf6', 1, '2026-09-09 13:08:18', '2026-09-09 10:08:17', 1),
(4, 'https://singhaniauniversity.ac.in/', 'beasty', 3, '2026-09-10 06:51:02', '2026-09-10 03:51:02', 1),
(5, 'https://roshan1208.github.io/sponsorsignal/', 'beastly', 1, '2026-09-10 07:09:18', '2026-09-10 04:09:18', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `urls`
--
ALTER TABLE `urls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short_code` (`short_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `urls`
--
ALTER TABLE `urls`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
