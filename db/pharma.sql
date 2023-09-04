-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2023 at 07:46 AM
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
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `brand_name` text NOT NULL,
  `brand_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand_name`, `brand_description`) VALUES
(1, 'Name', 'Description'),
(6, 'Test', 'Test'),
(7, 'Test2', 'Test'),
(8, 'Testt', 'Test'),
(9, 'Test1', 'Test'),
(10, 'Test23', 'Test'),
(11, 'Test123', 'Test'),
(12, 'Test124', 'Test'),
(13, 'Testtest', 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `inventory_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('pending','done','canceled') NOT NULL,
  `date_created` date NOT NULL DEFAULT current_timestamp(),
  `checkout_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `inventory_id`, `user_id`, `order_id`, `quantity`, `status`, `date_created`, `checkout_date`) VALUES
(1, 2, 2, 3, 3, 'canceled', '2023-08-30', '2023-08-30'),
(2, 3, 2, 3, 2, 'canceled', '2023-08-30', '2023-08-30'),
(3, 2, 1, NULL, 1, 'pending', '2023-09-04', NULL),
(4, 1, 2, 4, 1, 'pending', '2023-09-04', '2023-09-04');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `description`) VALUES
(1, 'Category', 'Description'),
(4, 'Testt', 'Test'),
(5, 'Test1', 'Test'),
(6, 'Test', 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_general`
--

CREATE TABLE `inventory_general` (
  `id` int(11) NOT NULL,
  `medicine_id` int(11) DEFAULT NULL,
  `price_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `date_received` date NOT NULL,
  `expiration_date` date NOT NULL,
  `serial_number` text NOT NULL,
  `product_number` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_general`
--

INSERT INTO `inventory_general` (`id`, `medicine_id`, `price_id`, `supplier_id`, `quantity`, `date_received`, `expiration_date`, `serial_number`, `product_number`) VALUES
(1, 1, 2, 1, 26, '2023-08-21', '2023-09-07', '098awdawd123', 'PROD23A0001'),
(2, 6, 3, 3, 50, '2023-08-21', '2023-09-06', '098awdawd123', 'PROD23A0002'),
(3, 1, 2, 1, 26, '2023-08-21', '2023-09-07', '098awdawd123', 'PROD23A0003'),
(4, 6, 3, 3, 49, '2023-08-21', '2023-09-06', '098awdawd123', 'PROD23A0004'),
(5, 1, 2, 1, 26, '2023-08-21', '2023-09-07', '098awdawd123', 'PROD23A0005'),
(6, 6, 3, 3, 50, '2023-08-21', '2023-09-06', '098awdawd123', 'PROD23A0006'),
(7, 1, 2, 1, 25, '2023-08-21', '2023-09-07', '098awdawd123', 'PROD23A0007'),
(8, 6, 3, 3, 50, '2023-08-21', '2023-09-06', '098awdawd123', 'PROD23A0008'),
(9, 1, 2, 1, 22, '2023-08-21', '2023-09-07', '098awdawd123', 'PROD23A0009'),
(10, 6, 3, 3, 50, '2023-08-21', '2023-09-06', '098awdawd123', 'PROD23A0010'),
(11, 1, 2, 1, 26, '2023-08-21', '2023-09-07', '098awdawd123', 'PROD23A0011'),
(12, 6, 3, 3, 50, '2023-08-21', '2023-09-06', '098awdawd123', 'PROD23A0012'),
(13, 1, 2, 1, 26, '2023-08-21', '2023-09-07', '098awdawd123', 'PROD23A0013'),
(14, 6, 3, 3, 50, '2023-08-21', '2023-09-06', '098awdawd123', 'PROD23A0014'),
(15, 1, 2, 1, 26, '2023-08-21', '2023-09-07', '098awdawd123', 'PROD23A0015'),
(16, 6, 3, 3, 50, '2023-08-21', '2023-09-06', '098awdawd123', 'PROD23A0016');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'cashier id',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `payment_id`, `order_id`, `user_id`, `date_created`) VALUES
(1, 1, 1, 1, '2023-08-21 02:46:48'),
(2, 2, 2, 1, '2023-08-21 04:09:24'),
(3, 3, 5, 1, '2023-09-04 00:17:04');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_profile`
--

CREATE TABLE `medicine_profile` (
  `id` int(11) NOT NULL,
  `medicine_name` varchar(100) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `generic_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `dosage` text NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_profile`
--

INSERT INTO `medicine_profile` (`id`, `medicine_name`, `category_id`, `image`, `brand_id`, `generic_name`, `description`, `dosage`, `deleted`) VALUES
(1, 'Name', NULL, '08222023-083205_Formet.jpg', 11, 'Generic', 'Description', '30', 0),
(4, 'Test', 1, '08222023-083214_IMG_1833.JPG', 1, 'Generic', 'Test', '12', 0),
(5, 'Test', 1, '08222023-083224_Skelan.png', 6, 'Test', 'Test', '12', 0),
(6, 'Test 1', 4, '08222023-083233_Poten-Cee.jpg', 6, 'Test 1', 'Test ', 'test', 0),
(7, 'Test1 ', 5, NULL, 1, 'Test 1 ', 'Test', 'awd', 1),
(8, 'Test 123', 1, NULL, 1, 'Test 123', 'Test', '12', 1),
(9, 'Test 31', 1, NULL, 1, 'Test 21', 'Test', '21', 1),
(10, 'Test T', 1, NULL, 1, 'Test 2', 'Test', '21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_subtotal` decimal(11,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `inventory_general_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `order_subtotal`, `quantity`, `inventory_general_id`) VALUES
(1, 1, 24.00, 2, 1),
(2, 1, 93.00, 3, 2),
(3, 2, 24.00, 2, 1),
(4, 2, 62.00, 2, 2),
(5, 3, 36.00, 3, 1),
(6, 3, 62.00, 2, 2),
(7, 4, 31.00, 1, 4),
(8, 5, 48.00, 4, 9),
(9, 5, 12.00, 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `order_tbl`
--

CREATE TABLE `order_tbl` (
  `id` int(11) NOT NULL,
  `order_number` varchar(32) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'set null if walk in',
  `subtotal` decimal(11,2) DEFAULT NULL,
  `discount` decimal(11,2) DEFAULT NULL,
  `overall_total` decimal(11,2) DEFAULT NULL,
  `type` enum('walk_in','online') NOT NULL,
  `date_ordered` date NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','preparing','to claim','claimed','declined','canceled') DEFAULT NULL COMMENT 'pending, preparing, to claim, claimed, declined, canceled',
  `note` text DEFAULT NULL,
  `prescription` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_tbl`
--

INSERT INTO `order_tbl` (`id`, `order_number`, `user_id`, `subtotal`, `discount`, `overall_total`, `type`, `date_ordered`, `status`, `note`, `prescription`) VALUES
(1, 'ORD23A0001', NULL, 117.00, 23.40, 93.60, 'walk_in', '2023-08-21', 'claimed', NULL, '0'),
(2, 'ORD23A0002', NULL, 86.00, 17.20, 68.80, 'walk_in', '2023-08-21', 'claimed', NULL, '0'),
(3, 'ORD23A0003', 2, 98.00, 0.00, 98.00, 'online', '2023-08-30', 'canceled', 'User Canceled', '08302023-073804_bg_1.jpg'),
(4, 'ORD23A0004', 2, 31.00, 0.00, 31.00, 'online', '2023-09-04', 'to claim', NULL, '09042023-072620_Capture.PNG'),
(5, 'ORD23A0005', NULL, 60.00, 12.00, 48.00, 'walk_in', '2023-09-04', 'claimed', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `paid_amount` decimal(11,2) NOT NULL,
  `customer_change` decimal(11,2) NOT NULL,
  `date_paid` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `order_id`, `paid_amount`, `customer_change`, `date_paid`) VALUES
(1, 1, 100.00, 6.40, '2023-08-21'),
(2, 2, 100.00, 31.20, '2023-08-21'),
(3, 5, 500.00, 452.00, '2023-09-04');

-- --------------------------------------------------------

--
-- Table structure for table `price`
--

CREATE TABLE `price` (
  `id` int(11) NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `price`
--

INSERT INTO `price` (`id`, `price`, `status`) VALUES
(1, 21.00, 'active'),
(2, 12.00, 'active'),
(3, 31.00, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `medicine_id` int(11) DEFAULT NULL,
  `creation_date` date NOT NULL,
  `payment_amount` decimal(11,2) NOT NULL,
  `payment_date` date NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`id`, `supplier_id`, `created_by`, `medicine_id`, `creation_date`, `payment_amount`, `payment_date`, `quantity`) VALUES
(3, 1, 1, 1, '2023-08-09', 5012.00, '2023-08-09', 5);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `total_quantity_sold` int(11) NOT NULL,
  `sales_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_id`, `total_quantity_sold`, `sales_date`) VALUES
(2, 1, 5, '2023-08-21'),
(3, 2, 4, '2023-08-21'),
(4, 3, 5, '2023-09-04');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `supplier_name` text NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `supplier_name`, `address`, `contact`) VALUES
(1, 'Supplier', 'Address', '09876543'),
(3, 'Test', 'Test', '098765'),
(4, 'Test1', 'Test', 'Test'),
(5, 'Test2', 'Test', 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uname` text NOT NULL,
  `fname` text NOT NULL,
  `mname` text DEFAULT NULL,
  `lname` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `avatar` text DEFAULT NULL,
  `isNew` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uname`, `fname`, `mname`, `lname`, `email`, `password`, `role`, `avatar`, `isNew`) VALUES
(1, 'Admin', 'Super', 'A', 'Admin', 'admin@email.com', '$argon2i$v=19$m=65536,t=4,p=1$cEdNeDRRRWUwR1VQMGtoRQ$FlSDL4rCgkTy/L2ceA2fmIgPWoeN73f5CgKmj8Fykdw', 'admin', '08232023-082651_person_5.jpg', NULL),
(2, 'uname1', 'John', NULL, 'Montemar', 'montemar@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$aEVtY3pyRmZtQTNPM2FXdA$DuH66gPeocjaRTJxtwzzRT+tLb529XQiD0PsLjBGX5c', 'user', NULL, NULL),
(3, 'uname2', 'Test', 'Test', 'Test', 'awd@awd', '$argon2i$v=19$m=65536,t=4,p=1$elhZdzlSQVBTaFguQ3Qvag$IXVjsB6M0sxE9jYH/HnOmSalRZYFHZL49UiFoJy4RBA', 'admin', NULL, NULL),
(4, 'uname3', 'Test', 'Test', 'Test', 'test@test', '$argon2i$v=19$m=65536,t=4,p=1$czZVOXBrbFRFemtqd3NJeQ$2X5i31DVAt9YMdv6/CQcp2MF1EGQH1CT7rJDDxSRnEc', 'admin', NULL, NULL),
(5, 'Test2', 'Test', 'Test', 'Test', 'test4@email.com', '$argon2i$v=19$m=65536,t=4,p=1$QlJ2QVdmWnlPc3NZbzFBRQ$dlwlblh78LPeXFY2xZhAv2Kn64HDkDg1BYV7dFHlTlE', 'admin', NULL, NULL),
(6, 'Awd', 'Awd', 'Awd', 'Awd', 'awd@awd.com', '$argon2i$v=19$m=65536,t=4,p=1$Li5Zd2pWQXR4eExkdmRLaA$7FW0dekryGVwIiqJcwwhM5YPvct/tdqG1LRYqdfju/w', 'admin', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_id` (`inventory_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_general`
--
ALTER TABLE `inventory_general`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicine_id` (`medicine_id`),
  ADD KEY `price_id` (`price_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `medicine_profile`
--
ALTER TABLE `medicine_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_general_id` (`inventory_general_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `order_tbl`
--
ALTER TABLE `order_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_ibfk_1` (`order_id`);

--
-- Indexes for table `price`
--
ALTER TABLE `price`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `medicine_id` (`medicine_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory_general`
--
ALTER TABLE `inventory_general`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `medicine_profile`
--
ALTER TABLE `medicine_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_tbl`
--
ALTER TABLE `order_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `price`
--
ALTER TABLE `price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`inventory_id`) REFERENCES `inventory_general` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `order_tbl` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `inventory_general`
--
ALTER TABLE `inventory_general`
  ADD CONSTRAINT `inventory_general_ibfk_1` FOREIGN KEY (`medicine_id`) REFERENCES `medicine_profile` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `inventory_general_ibfk_2` FOREIGN KEY (`price_id`) REFERENCES `price` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `inventory_general_ibfk_3` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `order_tbl` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `medicine_profile`
--
ALTER TABLE `medicine_profile`
  ADD CONSTRAINT `medicine_profile_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `medicine_profile_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`inventory_general_id`) REFERENCES `inventory_general` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `order_tbl` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_tbl`
--
ALTER TABLE `order_tbl`
  ADD CONSTRAINT `order_tbl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order_tbl` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD CONSTRAINT `purchase_order_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicine_profile` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_ibfk_3` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
