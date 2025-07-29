-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 29, 2025 at 12:49 PM
-- Server version: 8.0.36
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `azka_garden`
--

-- --------------------------------------------------------

--
-- Table structure for table `shippings`
--

CREATE TABLE `shippings` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `courier` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tracking_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cost` decimal(12,2) NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shippings`
--

INSERT INTO `shippings` (`id`, `order_id`, `courier`, `service`, `tracking_number`, `shipping_cost`, `status`, `estimated_delivery`, `interface_id`, `created_at`, `updated_at`) VALUES
(10, 2001, 'KURIR TOKO', 'Internal', NULL, 10000.00, 'WAITING_DELIVERY', NULL, 1, '2025-07-29 11:19:10', '2025-07-29 11:19:10'),
(11, 2002, 'KURIR TOKO', 'Internal', NULL, 15000.00, 'WAITING_DELIVERY', NULL, 1, '2025-07-29 11:19:10', '2025-07-29 11:19:10'),
(12, 2003, 'KURIR TOKO', 'Internal', NULL, 20000.00, 'WAITING_DELIVERY', NULL, 1, '2025-07-29 11:19:10', '2025-07-29 11:19:10'),
(13, 2004, 'GOSEND', 'Sameday', NULL, 25000.00, 'WAITING_PICKUP', NULL, 1, '2025-07-29 11:19:10', '2025-07-29 11:19:10'),
(14, 2005, 'JNE', 'REG', NULL, 12000.00, 'WAITING_PICKUP', NULL, 1, '2025-07-29 11:19:10', '2025-07-29 11:19:10'),
(15, 2006, 'JNT', 'EZ', NULL, 14000.00, 'WAITING_PICKUP', NULL, 1, '2025-07-29 11:19:10', '2025-07-29 11:19:10'),
(16, 2007, 'SICEPAT', 'BEST', NULL, 15000.00, 'WAITING_PICKUP', NULL, 1, '2025-07-29 11:19:10', '2025-07-29 11:19:10'),
(17, 2008, 'AMBIL_SENDIRI', '-', NULL, 0.00, 'READY_FOR_PICKUP', NULL, 1, '2025-07-29 11:19:10', '2025-07-29 11:19:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shippings`
--
ALTER TABLE `shippings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shippings_order_id_foreign` (`order_id`),
  ADD KEY `shippings_interface_id_foreign` (`interface_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shippings`
--
ALTER TABLE `shippings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `shippings`
--
ALTER TABLE `shippings`
  ADD CONSTRAINT `shippings_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `shippings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
