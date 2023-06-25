-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2023 at 04:34 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

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
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `medicine_id` int(11) NOT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `code` varchar(32) NOT NULL,
  `classification` text NOT NULL,
  `generic_name` text NOT NULL,
  `brand_name` text NOT NULL,
  `dose` varchar(32) NOT NULL,
  `price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `expiration` date NOT NULL,
  `image` text NOT NULL,
  `description` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`medicine_id`, `manufacturer_id`, `type_id`, `code`, `classification`, `generic_name`, `brand_name`, `dose`, `price`, `quantity`, `expiration`, `image`, `description`, `created`) VALUES
(1, 1, 1, 'SYS2023A0002', 'test', 'test', 'test', '12mg', 20, 100, '2023-06-30', 'bio.png', '', '2023-06-23 05:38:07');

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
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`medicine_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `type_id` (`type_id`);

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
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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

--
-- Constraints for dumped tables
--

--
-- Constraints for table `medicines`
--
ALTER TABLE `medicines`
  ADD CONSTRAINT `medicines_ibfk_1` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturers` (`manufacturer_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `medicines_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `medicine_types` (`type_id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
