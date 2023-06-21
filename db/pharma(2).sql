-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2023 at 09:50 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharma`
--

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE `manufacturers` (
  `manufacturer_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manufacturers`
--

INSERT INTO `manufacturers` (`manufacturer_id`, `name`, `status`) VALUES
(1, 'Natrapharm', 'active'),
(4, 'Getz Pharma', 'active'),
(5, 'Takeda/ Zuellig', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_types`
--

CREATE TABLE `medicine_types` (
  `type_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_types`
--

INSERT INTO `medicine_types` (`type_id`, `name`, `status`) VALUES
(1, 'Tabs', 'active'),
(3, 'Syrup', 'active'),
(4, 'Nebule', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(55) NOT NULL,
  `mname` varchar(55) DEFAULT NULL,
  `lname` varchar(55) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `role` enum('user','admin') NOT NULL COMMENT 'user, admin',
  `isNew` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `mname`, `lname`, `email`, `password`, `avatar`, `role`, `isNew`, `createdAt`) VALUES
(1, 'Super', NULL, 'Admin', 'admin@email.com', '$argon2i$v=19$m=65536,t=4,p=1$Y1YzSWRVNmNRWGdEZk9NYQ$v8M9BPPgZfJNn0P6TCYDtZepQPzxi8l/oGqKIYDw0R0', NULL, 'admin', NULL, '2023-06-19 02:34:24'),
(3, 'John', NULL, 'Montemar', 'montemar@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$aEVtY3pyRmZtQTNPM2FXdA$DuH66gPeocjaRTJxtwzzRT+tLb529XQiD0PsLjBGX5c', NULL, 'user', NULL, '2023-06-19 01:29:23'),
(5, 'Test', 'Test', 'Test', 'awd@awd', '$argon2i$v=19$m=65536,t=4,p=1$elhZdzlSQVBTaFguQ3Qvag$IXVjsB6M0sxE9jYH/HnOmSalRZYFHZL49UiFoJy4RBA', NULL, 'admin', NULL, '2023-06-19 23:30:33'),
(6, 'Test', 'Test', 'Test', 'test@test', '$argon2i$v=19$m=65536,t=4,p=1$czZVOXBrbFRFemtqd3NJeQ$2X5i31DVAt9YMdv6/CQcp2MF1EGQH1CT7rJDDxSRnEc', NULL, 'admin', NULL, '2023-06-19 23:32:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `manufacturers`
--
ALTER TABLE `manufacturers`
  ADD PRIMARY KEY (`manufacturer_id`);

--
-- Indexes for table `medicine_types`
--
ALTER TABLE `medicine_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
  MODIFY `manufacturer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `medicine_types`
--
ALTER TABLE `medicine_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
