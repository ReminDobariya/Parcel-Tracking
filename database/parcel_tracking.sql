-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 06, 2024 at 12:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parcel_tracking`
--

-- --------------------------------------------------------

--
-- Table structure for table `parcels`
--

CREATE TABLE `parcels` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tracking_number` varchar(255) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_street` varchar(255) NOT NULL,
  `sender_city` varchar(255) NOT NULL,
  `sender_state` varchar(255) NOT NULL,
  `sender_zip` varchar(10) NOT NULL,
  `sender_phone` varchar(15) NOT NULL,
  `receiver_name` varchar(255) NOT NULL,
  `receiver_street` varchar(255) NOT NULL,
  `receiver_city` varchar(255) NOT NULL,
  `receiver_state` varchar(255) NOT NULL,
  `receiver_zip` varchar(10) NOT NULL,
  `receiver_phone` varchar(15) NOT NULL,
  `parcel_weight` decimal(5,2) NOT NULL,
  `delivery_type` enum('Standard','Express') NOT NULL,
  `status` enum('Pending','Sent','Delivered') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parcels`
--

INSERT INTO `parcels` (`id`, `user_id`, `tracking_number`, `sender_name`, `sender_street`, `sender_city`, `sender_state`, `sender_zip`, `sender_phone`, `receiver_name`, `receiver_street`, `receiver_city`, `receiver_state`, `receiver_zip`, `receiver_phone`, `parcel_weight`, `delivery_type`, `status`, `created_at`, `cost`) VALUES
(9, 8, 'PKL061124U008C984', 'Reminkumar Dobariya', '98, Danev Park Gate No.1, Jivanvadi, Nikol', 'Ahmedabad', 'Gujarat', '382350', '7069743726', 'Umang Manvar', '114, Swastik Recidency', 'Jaipur', 'Maharashtra', '326732', '8141394743', 29.50, 'Express', 'Sent', '2024-11-06 09:33:17', 742.50),
(10, 8, 'PKL061124U008M474', 'Reminkumar Dobariya', '98, Danev Park Gate No.1, Jivanvadi, Nikol', 'Ahmedabad', 'Gujarat', '382350', '7069743726', 'Harsh Sangani', '12, Vrundavan Park', 'Rajkot', 'Gujarat', '329735', '7863452671', 34.00, 'Express', 'Pending', '2024-11-06 09:52:22', 810.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zip` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `password`, `phone`, `street`, `city`, `state`, `zip`, `created_at`) VALUES
(8, 'Reminkumar Dobariya', 'Remin', 'remindobariya001@gamil.com', '$2y$10$2ym0Prwvkr4e7gk918EGAOVZOsdaryqoaW8BIOSfMnifyclcER.Ce', '7069743726', '98, Danev Park Gate No.1, Jivanvadi, Nikol', 'Ahmedabad', 'Gujarat', '382350', '2024-11-06 09:18:32'),
(9, '', 'admin', 'admin@example.com', '$2y$10$KbQq5.V0x7nPgdNwi5NfWe1H.zsZeZASs6BpHgdDaGCr9axBfy6Qu', '', '', '', '', '', '2024-11-06 09:56:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `parcels`
--
ALTER TABLE `parcels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_number` (`tracking_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `parcels`
--
ALTER TABLE `parcels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `parcels`
--
ALTER TABLE `parcels`
  ADD CONSTRAINT `parcels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
