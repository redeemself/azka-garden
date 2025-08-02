-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 02, 2025 at 08:21 AM
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
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `label` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_address` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `label`, `recipient`, `phone_number`, `full_address`, `city`, `zip_code`, `is_primary`, `interface_id`, `created_at`, `updated_at`, `state`, `postal_code`, `address`, `latitude`, `longitude`) VALUES
(1, 5, 'Alamat Saya', 'Roberto', '081281349115', 'Taman Manggis Indah, Tole Iskandar, Sukamaju, Depok, West Java, Java, 16415, Indonesia', 'Depok', '16415', 1, 1, '2025-07-31 19:40:03', '2025-07-31 19:40:03', NULL, NULL, NULL, -6.40795200, 106.85772890),
(2, 5, '2121', 'Roberto', '081281349115', 'Margonda, Jakarta Outer Ring Road 2, Kemirimuka, Beji, Depok, West Java, Java, 16235, Indonesia', 'Depok', '16415', 0, 1, '2025-08-01 04:55:27', '2025-08-01 04:55:27', NULL, NULL, NULL, -6.37693560, 106.83430970),
(3, 6, 'Alamat Saya', 'Roberto', '081281349115', 'Taman Manggis Indah, Tole Iskandar, Sukamaju, Depok, West Java, Java, 16415, Indonesia', 'Depok', '16415', 1, 1, '2025-08-01 16:40:08', '2025-08-01 16:40:08', NULL, NULL, NULL, -6.40795200, 106.85772890),
(4, 7, 'Alamat Saya', 'Roberto', '081281349115', 'Taman Manggis Indah, Tole Iskandar, Sukamaju, Depok, West Java, Java, 16415, Indonesia', 'Depok', '16415', 1, 1, '2025-08-02 00:42:35', '2025-08-02 00:42:35', NULL, NULL, NULL, -6.40795200, 106.85772890);

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_permissions`
--

CREATE TABLE `admin_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED NOT NULL,
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT '0',
  `can_create` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete` tinyint(1) NOT NULL DEFAULT '0',
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_roles`
--

CREATE TABLE `admin_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `enum_admin_role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_sessions`
--

CREATE TABLE `admin_sessions` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_statuses`
--

CREATE TABLE `admin_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `enum_admin_status_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_documentations`
--

CREATE TABLE `api_documentations` (
  `id` bigint UNSIGNED NOT NULL,
  `endpoint_id` bigint UNSIGNED NOT NULL,
  `version` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `examples` json DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_endpoints`
--

CREATE TABLE `api_endpoints` (
  `id` bigint UNSIGNED NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `auth_required` tinyint(1) NOT NULL DEFAULT '0',
  `rate_limit` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_metrics`
--

CREATE TABLE `api_metrics` (
  `id` bigint UNSIGNED NOT NULL,
  `endpoint_id` bigint UNSIGNED NOT NULL,
  `timestamp` datetime NOT NULL,
  `response_time` int NOT NULL,
  `status_code` int NOT NULL,
  `error_rate` decimal(5,2) DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `recorded_by` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `image`, `link`, `position`, `start_date`, `end_date`, `status`, `created_at`, `interface_id`) VALUES
(1, 'Promo Tanaman Hias Juli', 'images/banners/banner1.png', '/products', 'homepage', '2025-07-01 00:00:00', '2025-07-31 00:00:00', 1, '2025-07-22 18:33:18', 1),
(9, 'Promo Tanaman Hias Juli', 'images/banners/banner1.jpg', '/products', 'homepage', '2025-07-01 00:00:00', '2025-07-31 00:00:00', 1, '2025-07-24 00:53:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bug_reports`
--

CREATE TABLE `bug_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `severity` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_to` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_exception`
--

CREATE TABLE `business_exception` (
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `errorCode` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `cache_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cache_value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `lock_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locked_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locked_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `promo_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` int NOT NULL DEFAULT '0',
  `price` int NOT NULL DEFAULT '0',
  `note` text COLLATE utf8mb4_unicode_ci,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `promo_code`, `discount`, `price`, `note`, `interface_id`, `created_at`, `updated_at`) VALUES
(26, 6, 56, 1, NULL, 0, 25000, NULL, 1, '2025-08-01 23:16:43', '2025-08-01 23:16:43'),
(27, 6, 55, 1, NULL, 0, 125000, NULL, 1, '2025-08-01 23:16:56', '2025-08-01 23:16:56'),
(28, 6, 54, 1, NULL, 0, 70000, NULL, 1, '2025-08-01 23:26:59', '2025-08-01 23:26:59'),
(29, 6, 58, 1, NULL, 0, 40000, NULL, 1, '2025-08-01 23:27:41', '2025-08-01 23:27:41'),
(30, 6, 59, 1, NULL, 0, 40000, NULL, 1, '2025-08-01 23:27:56', '2025-08-01 23:27:56'),
(31, 6, 57, 1, NULL, 0, 75000, NULL, 1, '2025-08-01 23:40:35', '2025-08-01 23:40:35'),
(32, 6, 60, 1, NULL, 0, 10000, NULL, 1, '2025-08-01 23:42:09', '2025-08-01 23:42:09'),
(33, 6, 61, 1, NULL, 0, 50000, NULL, 1, '2025-08-01 23:47:26', '2025-08-01 23:47:26'),
(34, 7, 55, 2, NULL, 0, 125000, NULL, 1, '2025-08-02 00:42:45', '2025-08-02 00:47:45'),
(35, 7, 54, 1, NULL, 0, 70000, NULL, 1, '2025-08-02 00:49:32', '2025-08-02 00:49:32');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `status`, `interface_id`, `created_at`, `updated_at`) VALUES
(1, 'Tanaman Hias', 'Tanaman hias indoor dan outdoor', NULL, 1, 1, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(2, 'Pot', 'Berbagai jenis pot taman', NULL, 1, 1, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(3, 'Batu Hias', 'Batu taman hias', NULL, 1, 1, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(4, 'Tanah', 'Media tanah kemasan', NULL, 1, 1, '2025-08-02 05:51:24', '2025-08-02 05:51:24');

-- --------------------------------------------------------

--
-- Table structure for table `charts`
--

CREATE TABLE `charts` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `promo_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `message`, `promo_code`, `created_at`, `updated_at`) VALUES
(1, 'Newsletter Subscriber', 'gohs01381@gmail.com', NULL, 'newsletter', 'PROMO-4BL48Q', '2025-07-31 23:44:53', '2025-07-31 23:44:53');

-- --------------------------------------------------------

--
-- Table structure for table `customer_support`
--

CREATE TABLE `customer_support` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `ticket_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dashboards`
--

CREATE TABLE `dashboards` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `layout` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `database_backups`
--

CREATE TABLE `database_backups` (
  `id` bigint UNSIGNED NOT NULL,
  `db_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `backup_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `database_configs`
--

CREATE TABLE `database_configs` (
  `id` bigint UNSIGNED NOT NULL,
  `db_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `host` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deployments`
--

CREATE TABLE `deployments` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `developers`
--

CREATE TABLE `developers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `specialization` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_profile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `developer_logs`
--

CREATE TABLE `developer_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `developer_id` bigint UNSIGNED NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `developer_permissions`
--

CREATE TABLE `developer_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `developer_id` bigint UNSIGNED NOT NULL,
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT '0',
  `can_commit` tinyint(1) NOT NULL DEFAULT '0',
  `can_merge` tinyint(1) NOT NULL DEFAULT '0',
  `can_deploy` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dev_roles`
--

CREATE TABLE `dev_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `enum_dev_role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dev_statuses`
--

CREATE TABLE `dev_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `enum_dev_status_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispute_management`
--

CREATE TABLE `dispute_management` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resolution` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enum_admin_role`
--

CREATE TABLE `enum_admin_role` (
  `id` bigint UNSIGNED NOT NULL,
  `value` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enum_admin_role`
--

INSERT INTO `enum_admin_role` (`id`, `value`) VALUES
(5, 'CONTENT_ADMIN'),
(4, 'CUSTOMER_SERVICE'),
(6, 'FINANCE_ADMIN'),
(3, 'ORDER_ADMIN'),
(2, 'PRODUCT_ADMIN'),
(1, 'SUPER_ADMIN');

-- --------------------------------------------------------

--
-- Table structure for table `enum_admin_status`
--

CREATE TABLE `enum_admin_status` (
  `id` bigint UNSIGNED NOT NULL,
  `value` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enum_admin_status`
--

INSERT INTO `enum_admin_status` (`id`, `value`) VALUES
(1, 'ACTIVE'),
(4, 'DELETED'),
(2, 'INACTIVE'),
(3, 'SUSPENDED');

-- --------------------------------------------------------

--
-- Table structure for table `enum_dev_role`
--

CREATE TABLE `enum_dev_role` (
  `id` bigint UNSIGNED NOT NULL,
  `value` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enum_dev_role`
--

INSERT INTO `enum_dev_role` (`id`, `value`) VALUES
(2, 'BACKEND_DEVELOPER'),
(4, 'DATABASE_ADMIN'),
(5, 'DEVOPS_ENGINEER'),
(3, 'FRONTEND_DEVELOPER'),
(1, 'LEAD_DEVELOPER'),
(6, 'SECURITY_ENGINEER');

-- --------------------------------------------------------

--
-- Table structure for table `enum_dev_status`
--

CREATE TABLE `enum_dev_status` (
  `id` bigint UNSIGNED NOT NULL,
  `value` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enum_dev_status`
--

INSERT INTO `enum_dev_status` (`id`, `value`) VALUES
(1, 'ACTIVE'),
(2, 'INACTIVE'),
(3, 'ON_LEAVE'),
(4, 'TERMINATED');

-- --------------------------------------------------------

--
-- Table structure for table `enum_order_status`
--

CREATE TABLE `enum_order_status` (
  `id` bigint UNSIGNED NOT NULL,
  `value` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enum_order_status`
--

INSERT INTO `enum_order_status` (`id`, `value`) VALUES
(5, 'CANCELED'),
(4, 'COMPLETED'),
(2, 'PROCESSING'),
(3, 'SHIPPED'),
(1, 'WAITING_PAYMENT');

-- --------------------------------------------------------

--
-- Table structure for table `enum_payment_status`
--

CREATE TABLE `enum_payment_status` (
  `id` bigint UNSIGNED NOT NULL,
  `value` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enum_payment_status`
--

INSERT INTO `enum_payment_status` (`id`, `value`) VALUES
(4, 'EXPIRED'),
(3, 'FAILED'),
(1, 'PENDING'),
(2, 'SUCCESS');

-- --------------------------------------------------------

--
-- Table structure for table `enum_report_type`
--

CREATE TABLE `enum_report_type` (
  `id` bigint UNSIGNED NOT NULL,
  `value` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enum_report_type`
--

INSERT INTO `enum_report_type` (`id`, `value`) VALUES
(1, 'DAILY_SALES'),
(6, 'FINANCE_STATEMENT'),
(3, 'INVENTORY_STATUS'),
(2, 'MONTHLY_REVENUE'),
(5, 'ORDER_SUMMARY'),
(4, 'USER_ACTIVITY');

-- --------------------------------------------------------

--
-- Table structure for table `enum_roles`
--

CREATE TABLE `enum_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enum_roles`
--

INSERT INTO `enum_roles` (`id`, `value`, `created_at`, `updated_at`) VALUES
(1, 'CUSTOMER', '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(2, 'GUEST', '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(3, 'ADMIN', '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(4, 'USER', '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(5, 'DEVELOPER', '2025-07-31 19:37:22', '2025-07-31 19:37:22');

-- --------------------------------------------------------

--
-- Table structure for table `enum_stats_type`
--

CREATE TABLE `enum_stats_type` (
  `id` bigint UNSIGNED NOT NULL,
  `value` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enum_stats_type`
--

INSERT INTO `enum_stats_type` (`id`, `value`) VALUES
(3, 'CUSTOMERS'),
(6, 'INVENTORY'),
(2, 'ORDERS'),
(4, 'PRODUCTS'),
(5, 'REVENUE'),
(1, 'SALES');

-- --------------------------------------------------------

--
-- Table structure for table `environments`
--

CREATE TABLE `environments` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `error_logs`
--

CREATE TABLE `error_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `level` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stack_trace` text COLLATE utf8mb4_unicode_ci,
  `source` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expired_orders`
--

CREATE TABLE `expired_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_date` datetime NOT NULL,
  `enum_order_status_id` bigint UNSIGNED NOT NULL,
  `total_price` decimal(14,2) NOT NULL,
  `shipping_cost` decimal(12,2) NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `question` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `order` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `jenis` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `rating` tinyint DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `global_payment_methods`
-- (See below for the actual view)
--
CREATE TABLE `global_payment_methods` (
`id` bigint unsigned
,`code` varchar(30)
,`name` varchar(50)
,`type` enum('LOCAL','GLOBAL')
,`config` json
,`status` tinyint(1)
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interfaces`
--

CREATE TABLE `interfaces` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `interfaces`
--

INSERT INTO `interfaces` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'User Interface', 'Interface untuk user biasa', NULL, NULL),
(8, 'Admin Interface', 'Interface untuk admin', NULL, NULL),
(11, 'Developer Interface', 'Interface untuk developer', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `interface_methods`
--

CREATE TABLE `interface_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `interface_id` bigint UNSIGNED NOT NULL,
  `method_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `return_type` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `local_payment_methods`
-- (See below for the actual view)
--
CREATE TABLE `local_payment_methods` (
`id` bigint unsigned
,`code` varchar(30)
,`name` varchar(50)
,`type` enum('LOCAL','GLOBAL')
,`config` json
,`status` tinyint(1)
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2056, '2025_07_23_034931_create_product_likes_table', 1),
(4218, '2025_07_30_073840_update_addresses_table', 2),
(4219, '2025_07_30_073913_add_new_fields_to_addresses_table', 2),
(5072, '2025_06_21_000000_create_interfaces_table', 3),
(5073, '2025_06_21_000001_create_interface_methods_table', 3),
(5074, '2025_06_21_000002_create_enum_roles_table', 3),
(5075, '2025_06_21_000003_create_enum_order_status_table', 3),
(5076, '2025_06_21_000004_create_enum_payment_status_table', 3),
(5077, '2025_06_21_000005_create_enum_admin_role_table', 3),
(5078, '2025_06_21_000006_create_enum_admin_status_table', 3),
(5079, '2025_06_21_000007_create_enum_stats_type_table', 3),
(5080, '2025_06_21_000008_create_enum_report_type_table', 3),
(5081, '2025_06_21_000009_create_enum_dev_role_table', 3),
(5082, '2025_06_21_000010_create_enum_dev_status_table', 3),
(5083, '2025_06_21_000011_create_roles_table', 3),
(5084, '2025_06_21_000012_create_users_table', 3),
(5085, '2025_06_21_000013_create_addresses_table', 3),
(5086, '2025_06_21_000014_create_categories_table', 3),
(5087, '2025_06_21_000015_create_products_table', 3),
(5088, '2025_06_21_000016_create_product_images_table', 3),
(5089, '2025_06_21_000017_create_reviews_table', 3),
(5090, '2025_06_21_000018_create_carts_table', 3),
(5091, '2025_06_21_000019_create_orders_table', 3),
(5092, '2025_06_21_000020_create_order_details_table', 3),
(5093, '2025_06_21_000021_create_payment_methods_table', 3),
(5094, '2025_06_21_000022_create_payments_table', 3),
(5095, '2025_06_21_000023_create_shippings_table', 3),
(5096, '2025_06_21_000024_create_admin_roles_table', 3),
(5097, '2025_06_21_000025_create_admin_statuses_table', 3),
(5098, '2025_06_21_000026_create_admins_table', 3),
(5099, '2025_06_21_000027_create_admin_permissions_table', 3),
(5100, '2025_06_21_000028_create_admin_logs_table', 3),
(5101, '2025_06_21_000029_create_dashboards_table', 3),
(5102, '2025_06_21_000030_create_statistics_table', 3),
(5103, '2025_06_21_000031_create_banners_table', 3),
(5104, '2025_06_21_000032_create_promotions_table', 3),
(5105, '2025_06_21_000033_create_newsletters_table', 3),
(5106, '2025_06_21_000034_create_order_management_table', 3),
(5107, '2025_06_21_000035_create_refund_management_table', 3),
(5108, '2025_06_21_000036_create_dispute_management_table', 3),
(5109, '2025_06_21_000037_create_stock_management_table', 3),
(5110, '2025_06_21_000038_create_supplier_management_table', 3),
(5111, '2025_06_21_000039_create_purchase_orders_table', 3),
(5112, '2025_06_21_000040_create_customer_support_table', 3),
(5113, '2025_06_21_000041_create_faq_table', 3),
(5114, '2025_06_21_000042_create_feedback_table', 3),
(5115, '2025_06_21_000043_create_audit_logs_table', 3),
(5116, '2025_06_21_000044_create_security_logs_table', 3),
(5117, '2025_06_21_000045_create_admin_sessions_table', 3),
(5118, '2025_06_21_000046_create_dev_roles_table', 3),
(5119, '2025_06_21_000047_create_dev_statuses_table', 3),
(5120, '2025_06_21_000048_create_developers_table', 3),
(5121, '2025_06_21_000049_create_developer_permissions_table', 3),
(5122, '2025_06_21_000050_create_developer_logs_table', 3),
(5123, '2025_06_21_000051_create_api_endpoints_table', 3),
(5124, '2025_06_21_000052_create_api_documentations_table', 3),
(5125, '2025_06_21_000053_create_api_metrics_table', 3),
(5126, '2025_06_21_000054_create_system_health_table', 3),
(5127, '2025_06_21_000055_create_error_logs_table', 3),
(5128, '2025_06_21_000056_create_performances_table', 3),
(5129, '2025_06_21_000057_create_database_configs_table', 3),
(5130, '2025_06_21_000058_create_query_optimizations_table', 3),
(5131, '2025_06_21_000059_create_database_backups_table', 3),
(5132, '2025_06_21_000060_create_security_audits_table', 3),
(5133, '2025_06_21_000061_create_vulnerabilities_table', 3),
(5134, '2025_06_21_000062_create_security_configs_table', 3),
(5135, '2025_06_21_000063_create_deployments_table', 3),
(5136, '2025_06_21_000064_create_environments_table', 3),
(5137, '2025_06_21_000065_create_release_notes_table', 3),
(5138, '2025_06_21_000066_create_test_cases_table', 3),
(5139, '2025_06_21_000067_create_test_reports_table', 3),
(5140, '2025_06_21_000068_create_bug_reports_table', 3),
(5141, '2025_06_21_000069_create_business_exception_table', 3),
(5142, '2025_06_21_000070_create_validation_exception_table', 3),
(5143, '2025_06_21_000071_create_resource_not_found_exception_table', 3),
(5144, '2025_06_21_000072_create_payment_exception_table', 3),
(5145, '2025_06_21_000073_create_shipping_exception_table', 3),
(5146, '2025_06_30_000074_create_sessions_table', 3),
(5147, '2025_06_30_000075_create_stats_types_table', 3),
(5148, '2025_07_04_000076_create_cache_table', 3),
(5149, '2025_07_07_000077_create_subscribers_table', 3),
(5150, '2025_07_08_000078_create_policy_acceptances_table', 3),
(5151, '2025_07_09_000079_create_testimonials_table', 3),
(5152, '2025_07_09_000080_add_is_featured_to_products_table', 3),
(5153, '2025_07_11_000081_create_payment_methods_views', 3),
(5154, '2025_07_11_000082_create_report_types_table', 3),
(5155, '2025_07_11_000083_create_reports_table', 3),
(5156, '2025_07_11_000084_create_charts_table', 3),
(5157, '2025_07_14_000085_create_role_user_table', 3),
(5158, '2025_07_14_000086_drop_role_id_from_users_table', 3),
(5159, '2025_07_15_000087_create_contacts_table', 3),
(5160, '2025_07_16_082209_create_seeder_status_table', 3),
(5161, '2025_07_16_114644_create_enum_roles_table', 3),
(5162, '2025_07_18_000000_create_faq_table', 3),
(5163, '2025_07_20_141500_make_message_nullable_on_contacts_table', 3),
(5164, '2025_07_21_000000_add_promo_code_and_discount_to_carts_table', 3),
(5165, '2025_07_21_000000_add_promo_code_to_contacts_table', 3),
(5166, '2025_07_21_000000_create_newsletter_subscribers_table', 3),
(5167, '2025_07_23_040342_add_promo_fields_to_promotions_table', 3),
(5168, '2025_07_23_042518_add_promo_code_to_promotions_table', 3),
(5169, '2025_07_23_082921_create_product_likes_table', 3),
(5170, '2025_07_24_000001_add_payment_method_to_orders_table', 3),
(5171, '2025_07_25_000001_add_plain_password_to_users_table', 3),
(5172, '2025_07_29_040847_add_price_discount_to_carts_table', 3),
(5173, '2025_07_29_110000_add_description_to_payment_methods_table', 3),
(5174, '2025_07_29_131408_create_shipping_methods_table', 3),
(5175, '2025_07_29_131408_update_shippings_table', 3),
(5176, '2025_07_29_153301_update_orders_table', 3),
(5177, '2025_07_30_074735_add_missing_fields_to_addresses_table', 3),
(5178, '2025_07_31_150505_add_indexes_to_sessions_table', 3),
(5179, '2025_07_31_172032_fix_promotions_table_structure', 3),
(5180, '2025_08_01_000000_add_shipping_method_to_orders_table', 3),
(5181, '2025_08_01_000001_add_total_to_orders_table', 4),
(5182, '2025_08_01_000002_add_status_to_orders_table', 5),
(5183, '2025_08_01_000003_make_order_code_nullable_on_orders_table', 6),
(5184, '2025_08_01_000004_make_order_date_nullable_on_orders_table', 7),
(5185, '2025_08_01_000005_make_enum_order_status_id_nullable_on_orders_table', 8),
(5186, '2025_08_01_000006_make_total_price_nullable_on_orders_table', 9),
(5187, '2025_08_01_000007_make_shipping_cost_nullable_on_orders_table', 10),
(5188, '2025_08_01_025303_create_order_items_table', 11),
(5189, '2023_01_01_000001_create_payments_table', 12),
(5190, '2025_08_01_153045_add_sort_column_to_payment_methods_table', 13),
(5191, '2025_08_02_010025_create_product_comments_table', 14),
(5192, '2025_08_02_010028_create_product_likes_table', 15),
(5193, '2025_08_02_012215_create_images_table', 16);

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE `newsletters` (
  `id` bigint UNSIGNED NOT NULL,
  `subject` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `recipient_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheduled_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redeem_code` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `promotion_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `shipping_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT NULL,
  `enum_order_status_id` bigint UNSIGNED DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `shipping_cost` decimal(15,2) DEFAULT '0.00',
  `note` text COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `shipping_method`, `order_code`, `order_date`, `enum_order_status_id`, `total_price`, `shipping_cost`, `note`, `payment_method`, `total`, `status`, `interface_id`, `created_at`, `updated_at`) VALUES
(2001, 1, NULL, 'ORD-2001', '2025-08-02 05:51:25', 1, 100000.00, 10000.00, NULL, NULL, NULL, 'pending', 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(2002, 1, NULL, 'ORD-2002', '2025-08-02 05:51:25', 1, 150000.00, 15000.00, NULL, NULL, NULL, 'pending', 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(2003, 1, NULL, 'ORD-2003', '2025-08-02 05:51:25', 1, 200000.00, 20000.00, NULL, NULL, NULL, 'pending', 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(2004, 1, NULL, 'ORD-2004', '2025-08-02 05:51:25', 1, 250000.00, 25000.00, NULL, NULL, NULL, 'pending', 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(2005, 1, NULL, 'ORD-2005', '2025-08-02 05:51:25', 1, 120000.00, 12000.00, NULL, NULL, NULL, 'pending', 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(2006, 1, NULL, 'ORD-2006', '2025-08-02 05:51:25', 1, 140000.00, 14000.00, NULL, NULL, NULL, 'pending', 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(2007, 1, NULL, 'ORD-2007', '2025-08-02 05:51:25', 1, 150000.00, 15000.00, NULL, NULL, NULL, 'pending', 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(2008, 1, NULL, 'ORD-2008', '2025-08-02 05:51:25', 1, 100000.00, 0.00, NULL, NULL, NULL, 'pending', 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(14,2) NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_management`
--

CREATE TABLE `order_management` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `method_id` bigint UNSIGNED NOT NULL,
  `transaction_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` decimal(14,2) NOT NULL,
  `enum_payment_status_id` bigint UNSIGNED NOT NULL,
  `proof_of_payment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expired_at` datetime DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_exception`
--

CREATE TABLE `payment_exception` (
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `errorCode` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('LOCAL','GLOBAL') COLLATE utf8mb4_unicode_ci NOT NULL,
  `config` json DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `code`, `name`, `description`, `type`, `config`, `status`, `sort`, `created_at`, `updated_at`) VALUES
(12, 'CASH', 'Uang Tunai di Tempat', 'Bayar langsung secara tunai kepada kurir saat barang diterima di alamat tujuan.', 'LOCAL', '{}', 1, 0, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(13, 'COD_QRIS', 'COD dengan QRIS/E-Wallet', 'Bayar di tempat tujuan melalui QRIS atau E-Wallet (Scan QR, OVO, GoPay, DANA, dll) kepada kurir.', 'LOCAL', '{}', 1, 0, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(14, 'QRIS', 'Pembayaran QRIS', 'Bayar secara instan melalui QRIS dari semua aplikasi e-wallet. Transaksi digital, aman, dan cepat.', 'LOCAL', '{}', 1, 0, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(15, 'EWALLET', 'E-Wallet', 'Pembayaran digital melalui OVO, GoPay, DANA, dan e-wallet lainnya. Transaksi instan dan tercatat.', 'LOCAL', '{}', 1, 0, '2025-08-02 05:51:25', '2025-08-02 05:51:25');

-- --------------------------------------------------------

--
-- Table structure for table `performances`
--

CREATE TABLE `performances` (
  `id` bigint UNSIGNED NOT NULL,
  `metric_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `policy_acceptances`
--

CREATE TABLE `policy_acceptances` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `policy_version` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accepted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `stock` int NOT NULL DEFAULT '0',
  `price` decimal(12,2) NOT NULL,
  `weight` decimal(8,2) NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `stock`, `price`, `weight`, `image_url`, `status`, `interface_id`, `is_featured`, `created_at`, `updated_at`) VALUES
(54, 1, 'Jamani Dolar', 'Jamani Dolar (Zamioculcas zamiifolia) merupakan tanaman perennial tropis dari keluarga Araceae yang berasal dari Afrika Timur seperti Kenya, Tanzania, dan Afrika Selatan. Tumbuh dari rimpang tebal yang menyimpan cadangan air, tanaman ini menghasilkan daun majemuk menyirip berwarna hijau pekat dan mengkilap dengan 6–8 pasang foliol oval sepanjang 7–15 cm. ZZ Plant sangat toleran terhadap cahaya rendah hingga sedang dan mampu bertahan lama dalam kondisi kekeringan. Harga pasaran tanaman ini sekitar Rp70.000.', 10, 70000.00, 1.00, 'images/produk/jamani_dolar.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(55, 1, 'Dragon Sekel', 'Dragon Sekel atau Tengkorak (Alocasia baginda \'Dragon Scale\') adalah varietas Alocasia dari keluarga Araceae yang terkenal dengan motif daun unik menyerupai sisik naga. Daunnya hijau zamrud dengan urat perak metalik yang mencolok, memberikan kesan eksotis dan elegan. Tanaman ini sangat cocok untuk dekorasi interior karena toleransinya terhadap cahaya rendah hingga sedang serta kemampuannya bertahan dalam kondisi kering. Harga pasaran sekitar Rp125.000.', 8, 125000.00, 1.00, 'images/produk/dragon_sekel_atau_tengkorak.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(56, 1, 'Pakis Kuning', 'Pakis Kuning (Nephrolepis exaltata \'Golden\') adalah varietas pakis hias yang memiliki daun muda berwarna kuning cerah yang berubah menjadi hijau saat dewasa. Daunnya berbentuk pedang dan tumbuh merumpun, menciptakan tampilan alami dan menyegarkan. Tanaman ini ideal ditempatkan di area teduh dengan cahaya matahari tidak langsung dan mudah dirawat, memberikan sentuhan hijau segar pada lingkungan sekitar. Harga pasaran sekitar Rp25.000.', 20, 25000.00, 1.00, 'images/produk/pakis_kuning.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(57, 1, 'Kuping Gajah', 'Kuping Gajah (Anthurium crystallinum) adalah varietas Anthurium dari keluarga Araceae dengan daun besar berbentuk hati dan permukaan berkilau. Urat daun berwarna keputih-putihan yang mencolok menambah kesan elegan dan eksotis. Tanaman ini cocok untuk dekorasi interior, memiliki toleransi terhadap cahaya rendah hingga sedang serta tahan pada periode kekeringan. Harga pasaran sekitar Rp75.000.', 15, 75000.00, 1.00, 'images/produk/kuping_gajah.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(58, 1, 'Cemara Ekor Tupai', 'Cemara Ekor Tupai (Asparagus densiflorus) merupakan tanaman tahunan hijau abadi dari keluarga Asparagaceae yang berasal dari Afrika Selatan. Daunnya menyerupai ekor tupai dengan daun kecil berwarna hijau cerah yang tumbuh rimbun dan mengerucut. Tanaman ini cocok sebagai tanaman hias interior karena toleransi terhadap cahaya rendah hingga sedang serta kemampuannya bertahan pada kondisi kering. Harga pasaran sekitar Rp40.000.', 12, 40000.00, 1.00, 'images/produk/cemara_ekor_tupay.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(59, 2, 'Pot Tanah Liat', 'Pot Tanah Liat diameter 15 cm dibuat dari bahan tanah liat berkualitas tinggi dengan desain minimalis yang sesuai untuk berbagai tanaman hias kecil hingga sedang. Pot ini tersedia dalam warna coklat, hitam, dan putih, memberikan pilihan dekorasi menarik serta harga terjangkau untuk menambah estetika tanaman di rumah Anda. Harga pot ini sekitar Rp40.000.', 50, 40000.00, 2.00, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_15.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(60, 1, 'Puting Cabe', 'Puting Cabe (Euphorbia milii) adalah tanaman hias berbunga dari keluarga Euphorbiaceae yang memiliki bunga kecil cerah serta duri tajam pada batangnya. Daunnya hijau rapat dengan bunga muncul dalam kelompok kecil, menciptakan tampilan eksotis. Tanaman ini tahan terhadap cahaya rendah hingga sedang dan mampu bertahan dalam kondisi kering berkat cadangan air pada batangnya. Harga pasaran sekitar Rp10.000.', 30, 10000.00, 0.30, 'images/produk/puting_cabe.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(61, 1, 'Cemara Perak', 'Cemara Perak (Juniperus chinensis) merupakan tanaman konifer hijau kekuningan berbentuk rimbun menyerupai pohon cemara mini. Tanaman ini cocok untuk taman, halaman, maupun sebagai tanaman indoor, memberikan kesan alami dan segar. Harga pasaran sekitar Rp50.000.', 10, 50000.00, 2.00, 'images/produk/cemara_perak.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(62, 1, 'Bringin Korea Tinggi 2M', 'Bringin Korea (Ficus microcarpa) adalah tanaman hias populer untuk taman dan interior dengan tinggi sekitar 2 meter, batang kokoh, dan daun hijau mengkilap yang memberikan suasana alami dan sejuk. Harga pasaran sekitar Rp2.000.000, mencerminkan kualitas dan ukuran yang besar. Tanaman ini mudah beradaptasi dengan berbagai kondisi cahaya dan perawatan sehingga cocok untuk pemula maupun penghobi.', 2, 2000000.00, 8.00, 'images/produk/bringin_korea_tinggi_2M.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(63, 1, 'Gestrum Kuning', 'Gestrum Kuning (Gestrum coromandelianum) adalah tanaman tropis dengan bunga kuning cerah dan daun hijau lebat yang dapat tumbuh hingga 2 meter. Tanaman ini cocok untuk taman atau halaman rumah, tahan berbagai kondisi cuaca dan mudah dirawat. Harga pasar sekitar Rp30.000.', 15, 30000.00, 1.00, 'images/produk/gestrum_kuning.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(64, 1, 'Brokoli Hijau', 'Brokoli Hijau adalah tanaman hias dengan daun hijau segar yang menyerupai sayur brokoli. Tanaman ini sering digunakan sebagai tanaman hias unik yang menambah sentuhan alami pada taman atau ruangan. Harga pasaran sekitar Rp10.000.', 25, 10000.00, 0.30, 'images/produk/brokoli_hijau.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(65, 1, 'Siklok', 'Siklok (Agave attenuata) atau Foxtail Agave adalah tanaman sukulen asal Meksiko dengan daun panjang runcing berwarna hijau keabu-abuan dengan pinggiran putih membentuk roseta yang elegan. Tahan terhadap panas dan kekeringan, cocok untuk taman tropis maupun subtropis serta perawatan mudah. Harga sekitar Rp70.000.', 10, 70000.00, 2.00, 'images/produk/siklok.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(66, 1, 'Sampang Dara', 'Sampang Dara (Excoecaria cochinchinensis) adalah perdu tropis dengan daun hijau cerah di bagian atas dan merah gelap di bagian bawah, tumbuh hingga 1–2 meter. Memberikan tampilan alami dan eksotis, tanaman ini cocok untuk taman indoor maupun outdoor. Harga sekitar Rp16.000, namun perlu hati-hati karena getahnya beracun saat perawatan.', 15, 16000.00, 1.00, 'images/produk/sampang_dara.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(68, 1, 'Teratai', 'Teratai (Nymphaea) adalah tanaman air dengan bunga besar indah yang mengapung di permukaan air. Warnanya bervariasi dari putih, merah muda hingga ungu, sering digunakan untuk mempercantik kolam atau taman air. Harga pasaran sekitar Rp75.000.', 10, 75000.00, 2.00, 'images/produk/teratai.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(69, 1, 'Airis Brazil', 'Airis Brazil (Iris variegata) adalah tanaman hias outdoor dengan daun panjang hijau cerah bergaris putih yang memberikan tampilan segar dan menarik, cocok untuk taman dan pot. Harga pasar sekitar Rp10.000.', 10, 10000.00, 0.30, 'images/produk/airis_brazil.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(70, 3, 'Batu Taman Hitam Putih', 'Batu Taman Hitam dan Putih adalah batu hias yang digunakan untuk dekorasi taman, tersedia dalam warna hitam dan putih yang memberikan kontras alami dan estetis pada taman. Harga sekitar Rp30.000.', 100, 30000.00, 2.00, 'images/produk/batu_taman_hitam_putih.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(71, 1, 'Maranti Bali', 'Maranti Bali (Stromanthe sanguinea) adalah tanaman hias tropis dari hutan hujan Brasil yang memiliki daun berwarna-warni merah, hijau, dan putih mencolok, sangat populer di kalangan penggemar tanaman hias. Harga pasaran sekitar Rp15.000.', 15, 15000.00, 0.70, 'images/produk/maranti_bali.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(72, 1, 'Kadaka Tanduk', 'Kadaka Tanduk (Platycerium) adalah tanaman paku-pakuan epifit yang biasanya hidup menempel pada batang tanaman lain, namun dapat juga ditanam dalam pot dan umum ditemukan di daerah lembap. Harga sekitar Rp30.000.', 10, 30000.00, 0.50, 'images/produk/kadaka_tanduk.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(73, 1, 'Jayen', 'Jayen (Episcia) adalah tanaman hias indoor dengan daun berbentuk hati dan bunga kecil berwarna cerah, cocok untuk dekorasi meja atau rak tanaman dalam ruangan. Harga sekitar Rp80.000.', 5, 80000.00, 0.20, 'images/produk/jayen.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(74, 1, 'Alamanda Kuning', 'Alamanda Kuning (Allamanda cathartica) adalah tanaman hias berbunga terompet emas berwarna kuning cerah dengan diameter 5–7,5 cm, populer untuk taman dan pagar hidup. Harga pasar sekitar Rp75.000.', 10, 75000.00, 1.00, 'images/produk/alamanda_kuning.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(75, 1, 'Sarbena Putih', 'Sarbena Putih (Sabrina) adalah tanaman hias gantung dengan bunga putih kecil yang menawan, ideal untuk taman minimalis atau teras rumah. Harga sekitar Rp10.000.', 20, 10000.00, 0.30, 'images/produk/sarbena_putih.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(76, 1, 'Sarbena Hijau', 'Sarbena Hijau adalah varian tanaman hias dengan daun hijau cerah yang memberikan kesan segar dan alami pada ruang hijau. Harga sekitar Rp10.000.', 20, 10000.00, 0.30, 'images/produk/sarbena_hijau.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(77, 1, 'Pitalub Kecil', 'Pitalub Kecil adalah tanaman hias kecil dengan daun lebat berwarna hijau, cocok sebagai penghias meja atau sudut ruangan, mudah dirawat dan sesuai untuk pemula. Harga pasaran sekitar Rp30.000.', 20, 30000.00, 0.30, 'images/produk/pitalub_kecil.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(78, 1, 'Aglonema Valentin', 'Aglonema Valentin adalah tanaman hias dengan daun hijau-merah muda yang populer untuk dekorasi interior dan mudah tumbuh subur di tempat teduh. Harga sekitar Rp70.000.', 10, 70000.00, 0.40, 'images/produk/aglonema_valentin.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(79, 2, 'Pot Kapsul', 'Pot Kapsul Coklat dan Hitam (diameter 35 cm, tinggi 60 cm) adalah pot dengan desain kapsul elegan yang cocok untuk tanaman besar atau bonsai. Harga sekitar Rp85.000.', 10, 85000.00, 3.00, 'images/produk/pot_kapsul_hitam_coklat_hitam_diameter_35_tinggi_60.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(80, 2, 'Pot Tanah Minimalis', 'Pot Tanah Coklat, Putih, dan Bintik Hitam (diameter 30 cm) adalah pot tanah liat minimalis yang sesuai untuk berbagai tanaman hias. Harga sekitar Rp65.000.', 15, 65000.00, 2.50, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_30.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(81, 2, 'Pot Hitam Besar', 'Pot Hitam Diameter 40 cm adalah pot plastik hitam berukuran besar yang tahan lama dan ideal untuk tanaman hias berukuran sedang hingga besar. Pot ini dapat digunakan di dalam maupun luar ruangan. Harga sekitar Rp40.000.', 30, 40000.00, 2.50, 'images/produk/pot_hitam_diameter_40.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(82, 1, 'Cemara Tretes', 'Cemara Tretes (tinggi 120 cm) adalah tanaman cemara mini yang memberikan kesan asri dan elegan, sangat cocok untuk taman dan penghias ruang luar. Harga pasaran sekitar Rp250.000.', 3, 250000.00, 5.00, 'images/produk/cemara_tretes_tinggi_120cm.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(83, 1, 'Pitalub Tinggi', 'Pitalub Tinggi 70 cm adalah tanaman hias berukuran sedang dengan daun lebat, mudah dirawat dan sesuai sebagai penghias taman, khususnya bagi pemula. Harga sekitar Rp80.000.', 5, 80000.00, 0.80, 'images/produk/pitalub_tinggi_70cm.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(84, 1, 'Ketapang Kaligata', 'Ketapang Kaligata Tinggi 60 cm adalah tanaman hias kecil dengan daun khas yang memberikan kesan asri, sangat sesuai untuk taman minimalis. Harga sekitar Rp35.000.', 10, 35000.00, 0.60, 'images/produk/ketapang_kaligata_tinggi_60cm.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(85, 1, 'Berekele', 'Berekele adalah tanaman hias yang menambah warna dan tekstur pada taman tropis maupun sebagai tanaman pagar hidup. Harga sekitar Rp15.000.', 30, 15000.00, 0.30, 'images/produk/berekele.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(86, 4, 'Media Tanah', 'Media Tanah adalah media tanam berkualitas tinggi yang mendukung pertumbuhan berbagai tanaman hias dan dapat digunakan untuk tanaman dalam pot maupun di tanah terbuka. Harga sekitar Rp15.000 per kemasan.', 100, 15000.00, 1.00, 'images/produk/media_tanah.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(87, 1, 'Jamani Cobra', 'Jamani Cobra adalah tanaman hias eksotis dengan bentuk unik dan harga tinggi, sangat cocok untuk koleksi tanaman langka. Harga pasar sekitar Rp300.000.', 3, 300000.00, 0.60, 'images/produk/jamani_cobra.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(88, 1, 'Kamboja Jepang', 'Kamboja Jepang adalah tanaman hias berbunga cantik dan harum yang sering digunakan sebagai tanaman pekarangan di daerah tropis. Harga sekitar Rp50.000.', 8, 50000.00, 1.20, 'images/produk/kamboja_jepang.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(89, 1, 'Bringin Putih', 'Bringin Putih adalah tanaman hias dengan daun putih hijau yang menawan, memberikan kesan elegan untuk taman dan halaman. Harga sekitar Rp50.000.', 6, 50000.00, 1.00, 'images/produk/bringin_putih.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(90, 1, 'Bromelian Baby Pink', 'Bromelian Baby Pink adalah bromeliad dengan bunga pink kecil yang cantik, menjadi favorit tanaman eksotis untuk dekorasi interior. Harga sekitar Rp125.000.', 5, 125000.00, 0.60, 'images/produk/bromilian_baby_pink.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(91, 1, 'Asoka India', 'Asoka India adalah tanaman berbunga kecil yang sering digunakan sebagai pagar hidup, mudah dirawat dan sesuai untuk pemula. Harga sekitar Rp10.000.', 30, 10000.00, 0.20, 'images/produk/asoka_india.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(92, 1, 'Pandan Bali', 'Pandan Bali adalah tanaman pandan beraroma khas yang digunakan sebagai tanaman hias dan bumbu dapur di daerah tropis. Harga sekitar Rp150.000.', 10, 150000.00, 5.00, 'images/produk/pandan_bali.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(93, 1, 'Lidah Mertua', 'Lidah Mertua adalah tanaman hias indoor dengan daun panjang tajam yang mudah dirawat dan sesuai untuk dekorasi meja atau rak tanaman. Harga sekitar Rp25.000.', 15, 25000.00, 0.50, 'images/produk/lidah_mertua.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(94, 1, 'Bringin Korea Micro', 'Bringin Korea Micro adalah varian kecil dari Bringin Korea yang cocok untuk koleksi bonsai dengan bentuk daun menarik dan perawatan mudah. Harga pasar sekitar Rp1.500.000.', 2, 1500000.00, 3.00, 'images/produk/bringin_korea_micro.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(95, 1, 'Marigool', 'Marigool adalah tanaman berbunga oranye cerah yang populer sebagai tanaman hias dan penangkal serangga di taman rumah. Harga sekitar Rp25.000.', 25, 25000.00, 0.20, 'images/produk/marigool.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(96, 1, 'Kaktus Koboy', 'Kaktus Koboy (tinggi 70 cm) adalah kaktus besar berbentuk unik yang tahan kering dan mudah dirawat, sangat cocok untuk dekorasi rumah. Harga sekitar Rp150.000.', 12, 150000.00, 1.20, 'images/produk/kaktus_koboy_tinggi_70cm.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(97, 1, 'Bonsai Gestrum L', 'Bonsai Gestrum Ukuran L adalah bonsai besar dengan daun Gestrum yang indah, cocok untuk koleksi eksklusif dengan perawatan khusus. Harga pasar sekitar Rp1.200.000.', 1, 1200000.00, 3.00, 'images/produk/bonsai_gestrum(L).jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(98, 1, 'Bonsai Gestrum M', 'Bonsai Gestrum Ukuran M adalah bonsai berukuran sedang dengan daun Gestrum yang cantik, memberikan kesan elegan di rumah atau kantor. Harga sekitar Rp500.000.', 2, 500000.00, 2.00, 'images/produk/bonsai_gestrum(M).jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(99, 1, 'Bonsai Cemara Udang', 'Bonsai Cemara Udang adalah bonsai cemara unik yang menyerupai udang dan merupakan tanaman koleksi menarik dengan perawatan khusus. Harga pasar sekitar Rp650.000.', 1, 650000.00, 2.00, 'images/produk/bonsai_cemara_udang.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(100, 1, 'Bunga Kertas', 'Bunga Kertas adalah tanaman hias dengan warna-warni cerah yang mudah dirawat dan cocok untuk memperindah pagar atau taman. Tanaman ini sangat sesuai bagi pemula. Harga sekitar Rp30.000.', 20, 30000.00, 0.40, 'images/produk/bunga_kertas.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(101, 1, 'Jambu Kanci', 'Jambu Kanci (tinggi 50 cm) adalah tanaman buah jambu kanci kecil yang juga dapat dijadikan tanaman hias, cocok untuk taman dan kebun rumah. Harga pasar sekitar Rp60.000.', 8, 60000.00, 1.00, 'images/produk/jambu_kanci_tinggi_50cm.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(102, 1, 'Jeruk Lemon', 'Jeruk Lemon adalah tanaman buah jeruk lemon kecil yang memberikan aroma segar dan cocok untuk taman maupun kebun rumah. Harga sekitar Rp60.000.', 7, 60000.00, 1.00, 'images/produk/jeruk_lemon.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(103, 1, 'Asoka Singapur', 'Asoka Singapur adalah tanaman berbunga kecil yang populer sebagai pagar hidup, mudah dirawat dan sesuai untuk pemula. Harga sekitar Rp25.000.', 20, 25000.00, 0.20, 'images/produk/asoka_singapur.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(104, 1, 'Sikas', 'Sikas (tinggi 70 cm) adalah tanaman sikas berukuran besar yang cocok sebagai tanaman hias eksklusif dengan perawatan khusus. Harga pasar sekitar Rp1.700.000.', 1, 1700000.00, 6.00, 'images/produk/sikas_tinggi_70cm.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(105, 1, 'Kadaka Tempel', 'Kadaka Tempel adalah tanaman hias dengan daun menarik yang mudah dirawat dan sesuai untuk taman tropis maupun sebagai tanaman pagar hidup. Harga sekitar Rp35.000.', 10, 35000.00, 0.60, 'images/produk/kadaka_tempel.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(106, 1, 'Pucuk Merah', 'Pucuk Merah (tinggi 250 cm) adalah tanaman pucuk merah tinggi yang sering digunakan sebagai pagar hidup atau dekorasi taman, memberikan warna cerah yang menarik dan menambah estetika lingkungan. Harga sekitar Rp350.000.', 4, 350000.00, 2.20, 'images/produk/pucuk_merah_tinggi_250cm.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24'),
(107, 1, 'Kana', 'Kana (Canna indica) adalah tanaman tropis dengan daun lebar hijau cerah dan bunga besar berwarna merah, kuning, atau oranye yang mencolok. Tumbuh hingga 1–2 meter, cocok untuk taman dan halaman, tahan berbagai kondisi cuaca dan mudah dirawat sehingga sesuai untuk pemula. Harga pasar sekitar Rp30.000.', 25, 30000.00, 0.60, 'images/produk/kana.jpg', 1, 1, 0, '2025-08-02 05:51:24', '2025-08-02 05:51:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_comments`
--

CREATE TABLE `product_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL DEFAULT '5',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `is_primary`, `interface_id`, `created_at`, `updated_at`) VALUES
(213, 54, 'images/produk/jamani_dolar.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(214, 54, 'images/produk/jamani_dolar.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(215, 55, 'images/produk/dragon_sekel_atau_tengkorak.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(216, 55, 'images/produk/dragon_sekel_atau_tengkorak.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(217, 56, 'images/produk/pakis_kuning.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(218, 56, 'images/produk/pakis_kuning.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(219, 57, 'images/produk/kuping_gajah.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(220, 57, 'images/produk/kuping_gajah.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(221, 58, 'images/produk/cemara_ekor_tupay.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(222, 58, 'images/produk/cemara_ekor_tupay.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(223, 59, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_15.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(224, 59, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_15.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(225, 60, 'images/produk/puting_cabe.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(226, 60, 'images/produk/puting_cabe.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(227, 61, 'images/produk/cemara_perak.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(228, 61, 'images/produk/cemara_perak.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(229, 62, 'images/produk/bringin_korea_tinggi_2M.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(230, 62, 'images/produk/bringin_korea_tinggi_2M.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(231, 63, 'images/produk/gestrum_kuning.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(232, 63, 'images/produk/gestrum_kuning.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(233, 64, 'images/produk/brokoli_hijau.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(234, 64, 'images/produk/brokoli_hijau.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(235, 65, 'images/produk/siklok.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(236, 65, 'images/produk/siklok.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(237, 66, 'images/produk/sampang_dara.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(238, 66, 'images/produk/sampang_dara.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(239, 68, 'images/produk/teratai.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(240, 68, 'images/produk/teratai.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(241, 69, 'images/produk/airis_brazil.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(242, 69, 'images/produk/airis_brazil.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(243, 70, 'images/produk/batu_taman_hitam_putih.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(244, 70, 'images/produk/batu_taman_hitam_putih.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(245, 71, 'images/produk/maranti_bali.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(246, 71, 'images/produk/maranti_bali.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(247, 72, 'images/produk/kadaka_tanduk.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(248, 72, 'images/produk/kadaka_tanduk.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(249, 73, 'images/produk/jayen.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(250, 73, 'images/produk/jayen.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(251, 74, 'images/produk/alamanda_kuning.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(252, 74, 'images/produk/alamanda_kuning.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(253, 75, 'images/produk/sarbena_putih.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(254, 75, 'images/produk/sarbena_putih.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(255, 76, 'images/produk/sarbena_hijau.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(256, 76, 'images/produk/sarbena_hijau.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(257, 77, 'images/produk/pitalub_kecil.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(258, 77, 'images/produk/pitalub_kecil.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(259, 78, 'images/produk/aglonema_valentin.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(260, 78, 'images/produk/aglonema_valentin.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(261, 79, 'images/produk/pot_kapsul_hitam_coklat_hitam_diameter_35_tinggi_60.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(262, 79, 'images/produk/pot_kapsul_hitam_coklat_hitam_diameter_35_tinggi_60.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(263, 80, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_30.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(264, 80, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_30.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(265, 81, 'images/produk/pot_hitam_diameter_40.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(266, 81, 'images/produk/pot_hitam_diameter_40.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(267, 82, 'images/produk/cemara_tretes_tinggi_120cm.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(268, 82, 'images/produk/cemara_tretes_tinggi_120cm.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(269, 83, 'images/produk/pitalub_tinggi_70cm.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(270, 83, 'images/produk/pitalub_tinggi_70cm.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(271, 84, 'images/produk/ketapang_kaligata_tinggi_60cm.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(272, 84, 'images/produk/ketapang_kaligata_tinggi_60cm.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(273, 85, 'images/produk/berekele.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(274, 85, 'images/produk/berekele.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(275, 86, 'images/produk/media_tanah.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(276, 86, 'images/produk/media_tanah.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(277, 87, 'images/produk/jamani_cobra.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(278, 87, 'images/produk/jamani_cobra.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(279, 88, 'images/produk/kamboja_jepang.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(280, 88, 'images/produk/kamboja_jepang.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(281, 89, 'images/produk/bringin_putih.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(282, 89, 'images/produk/bringin_putih.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(283, 90, 'images/produk/bromilian_baby_pink.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(284, 90, 'images/produk/bromilian_baby_pink.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(285, 91, 'images/produk/asoka_india.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(286, 91, 'images/produk/asoka_india.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(287, 92, 'images/produk/pandan_bali.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(288, 92, 'images/produk/pandan_bali.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(289, 93, 'images/produk/lidah_mertua.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(290, 93, 'images/produk/lidah_mertua.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(291, 94, 'images/produk/bringin_korea_micro.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(292, 94, 'images/produk/bringin_korea_micro.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(293, 95, 'images/produk/marigool.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(294, 95, 'images/produk/marigool.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(295, 96, 'images/produk/kaktus_koboy_tinggi_70cm.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(296, 96, 'images/produk/kaktus_koboy_tinggi_70cm.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(297, 97, 'images/produk/bonsai_gestrum(L).jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(298, 97, 'images/produk/bonsai_gestrum(L).png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(299, 98, 'images/produk/bonsai_gestrum(M).jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(300, 98, 'images/produk/bonsai_gestrum(M).png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(301, 99, 'images/produk/bonsai_cemara_udang.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(302, 99, 'images/produk/bonsai_cemara_udang.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(303, 100, 'images/produk/bunga_kertas.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(304, 100, 'images/produk/bunga_kertas.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(305, 101, 'images/produk/jambu_kanci_tinggi_50cm.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(306, 101, 'images/produk/jambu_kanci_tinggi_50cm.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(307, 102, 'images/produk/jeruk_lemon.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(308, 102, 'images/produk/jeruk_lemon.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(309, 103, 'images/produk/asoka_singapur.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(310, 103, 'images/produk/asoka_singapur.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(311, 104, 'images/produk/sikas_tinggi_70cm.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(312, 104, 'images/produk/sikas_tinggi_70cm.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(313, 105, 'images/produk/kadaka_tempel.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(314, 105, 'images/produk/kadaka_tempel.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(315, 106, 'images/produk/pucuk_merah_tinggi_250cm.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(316, 106, 'images/produk/pucuk_merah_tinggi_250cm.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(317, 107, 'images/produk/kana.jpg', 1, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(318, 107, 'images/produk/tanaman_kana.png', 0, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25');

-- --------------------------------------------------------

--
-- Table structure for table `product_likes`
--

CREATE TABLE `product_likes` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint UNSIGNED NOT NULL,
  `promo_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `discount_type` enum('fixed','percent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `discount_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `minimum_purchase` decimal(15,2) DEFAULT NULL,
  `maximum_discount` decimal(15,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int NOT NULL DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `promo_code`, `title`, `description`, `discount_type`, `discount_value`, `minimum_purchase`, `maximum_discount`, `usage_limit`, `used_count`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `interface_id`) VALUES
(2, 'PROMOJULI10', 'Diskon 10% untuk Tanaman Hias', 'Dapatkan diskon 10% untuk pembelian minimal Rp200.000 tanaman hias.', 'percent', 10.00, NULL, NULL, NULL, 0, '2025-07-01 00:00:00', '2025-07-31 00:00:00', 1, '2025-07-24 00:53:22', NULL, 1),
(3, 'PROMO-SV294G', 'Promo Newsletter untuk wdawdaaw02@gmail.com', 'Promo khusus subscriber newsletter.', 'percent', 10.00, NULL, NULL, NULL, 0, '2025-07-24 11:43:32', '2025-08-23 11:43:32', 1, '2025-07-24 04:43:32', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(14,2) DEFAULT NULL,
  `payment_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `query_optimizations`
--

CREATE TABLE `query_optimizations` (
  `id` bigint UNSIGNED NOT NULL,
  `query_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `execution_time` int NOT NULL,
  `suggested_optimization` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refund_management`
--

CREATE TABLE `refund_management` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(14,2) DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processed_by` bigint UNSIGNED DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `release_notes`
--

CREATE TABLE `release_notes` (
  `id` bigint UNSIGNED NOT NULL,
  `deployment_id` bigint UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint UNSIGNED NOT NULL,
  `type_id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` json DEFAULT NULL,
  `data` json DEFAULT NULL,
  `format` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_types`
--

CREATE TABLE `report_types` (
  `id` bigint UNSIGNED NOT NULL,
  `enum_report_type_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resource_not_found_exception`
--

CREATE TABLE `resource_not_found_exception` (
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `errorCode` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `enum_role_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `enum_role_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 3, 'ADMIN', '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(2, 4, 'USER', '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(3, 2, 'GUEST', '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(4, 1, 'CUSTOMER', '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(5, 5, 'DEVELOPER', '2025-07-31 19:37:22', '2025-07-31 19:37:22');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(5, 2, '2025-07-31 19:39:29', '2025-07-31 19:39:29'),
(5, 3, '2025-07-31 19:39:29', '2025-07-31 19:39:29'),
(5, 4, '2025-07-31 19:39:29', '2025-07-31 19:39:29'),
(6, 2, '2025-08-01 16:39:44', '2025-08-01 16:39:44'),
(6, 3, '2025-08-01 16:39:44', '2025-08-01 16:39:44'),
(6, 4, '2025-08-01 16:39:44', '2025-08-01 16:39:44'),
(7, 2, '2025-08-02 00:41:39', '2025-08-02 00:41:39'),
(7, 3, '2025-08-02 00:41:39', '2025-08-02 00:41:39'),
(7, 4, '2025-08-02 00:41:39', '2025-08-02 00:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `security_audits`
--

CREATE TABLE `security_audits` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `severity` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `findings` text COLLATE utf8mb4_unicode_ci,
  `developer_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `security_configs`
--

CREATE TABLE `security_configs` (
  `id` bigint UNSIGNED NOT NULL,
  `component` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_value` text COLLATE utf8mb4_unicode_ci,
  `is_encrypted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `security_logs`
--

CREATE TABLE `security_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `related_to` bigint UNSIGNED DEFAULT NULL,
  `event` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seeder_status`
--

CREATE TABLE `seeder_status` (
  `id` bigint UNSIGNED NOT NULL,
  `seeder_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ran_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Laravel sessions table for database session driver - Azka Garden E-Commerce';

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`, `token`, `expires_at`, `created_at`, `updated_at`) VALUES
('wpB8Pxh4hpBRajjUkHwe5sWS2AonKgx2mK3SUaPj', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRXJUWjF4cjA3dU9SRzZQbGxEMzJSakJOaEtaNUthMWVWZzd0WDhnZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9hemthLWdhcmRlbi50ZXN0L3Byb2R1Y3RzLzU0Ijt9czoxMDoiY2FydF9jb3VudCI7czoxOiIzIjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo3O30=', 1754120983, NULL, NULL, NULL, NULL);

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
(17, 2001, 'KURIR TOKO', 'Internal', NULL, 10000.00, 'WAITING_DELIVERY', NULL, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(18, 2002, 'KURIR TOKO', 'Internal', NULL, 15000.00, 'WAITING_DELIVERY', NULL, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(19, 2003, 'KURIR TOKO', 'Internal', NULL, 20000.00, 'WAITING_DELIVERY', NULL, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(20, 2004, 'GOSEND', 'Sameday', NULL, 25000.00, 'WAITING_PICKUP', NULL, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(21, 2005, 'JNE', 'REG', NULL, 12000.00, 'WAITING_PICKUP', NULL, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(22, 2006, 'JNT', 'EZ', NULL, 14000.00, 'WAITING_PICKUP', NULL, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(23, 2007, 'SICEPAT', 'BEST', NULL, 15000.00, 'WAITING_PICKUP', NULL, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25'),
(24, 2008, 'AMBIL_SENDIRI', '-', NULL, 0.00, 'READY_FOR_PICKUP', NULL, 1, '2025-08-02 05:51:25', '2025-08-02 05:51:25');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_exception`
--

CREATE TABLE `shipping_exception` (
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `errorCode` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode metode pengiriman',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama metode pengiriman',
  `service` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Jenis layanan',
  `cost` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Biaya pengiriman default',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Deskripsi metode pengiriman',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status aktif',
  `sort_order` int NOT NULL DEFAULT '0',
  `sort` int NOT NULL DEFAULT '0' COMMENT 'Urutan tampilan',
  `start_date` date DEFAULT NULL COMMENT 'Tanggal mulai aktif',
  `end_date` date DEFAULT NULL COMMENT 'Tanggal berakhir aktif',
  `settings` json DEFAULT NULL COMMENT 'Pengaturan tambahan dalam JSON',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `code`, `name`, `service`, `cost`, `description`, `is_active`, `sort_order`, `sort`, `start_date`, `end_date`, `settings`, `created_at`, `updated_at`) VALUES
(1, 'JNT', 'J&T Express', 'EZ', 14000.00, 'Pengiriman reguler via J&T Express (Rp14,000)', 1, 0, 1, NULL, NULL, NULL, '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(2, 'GOSEND', 'GoSend', 'Sameday', 25000.00, 'Pengiriman cepat via GoSend (estimasi Rp25,000 sesuai jarak)', 1, 0, 4, NULL, NULL, NULL, '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(3, 'JNE', 'JNE', 'REG', 12000.00, 'Pengiriman reguler via JNE (Rp12,000)', 1, 0, 2, NULL, NULL, NULL, '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(4, 'SICEPAT', 'SiCepat', 'BEST', 15000.00, 'Pengiriman reguler via SiCepat (Rp15,000)', 1, 0, 3, NULL, NULL, NULL, '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(5, 'KURIR_TOKO_DEKAT', 'Kurir Toko (<5km)', 'Internal-Dekat', 10000.00, 'Pengiriman langsung dari toko Azka Garden (jarak <5km)', 1, 0, 5, NULL, NULL, '{\"max_distance\": 5, \"distance_range\": \"less_than_5km\"}', '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(6, 'KURIR_TOKO', 'Kurir Toko (5-10km)', 'Internal', 15000.00, 'Pengiriman langsung dari toko Azka Garden (jarak 5-10km)', 1, 0, 6, NULL, NULL, '{\"max_distance\": 10, \"min_distance\": 5, \"distance_range\": \"5_to_10km\"}', '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(7, 'KURIR_TOKO_JAUH', 'Kurir Toko (>10km)', 'Internal-Jauh', 20000.00, 'Pengiriman langsung dari toko Azka Garden (jarak >10km)', 1, 0, 7, NULL, NULL, '{\"min_distance\": 10, \"distance_range\": \"more_than_10km\"}', '2025-07-31 19:37:22', '2025-07-31 19:37:22'),
(8, 'AMBIL_SENDIRI', 'Ambil Sendiri', '-', 0.00, 'Ambil langsung di toko (GRATIS)', 1, 0, 0, NULL, NULL, NULL, '2025-07-31 19:37:22', '2025-07-31 19:37:22');

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
  `id` bigint UNSIGNED NOT NULL,
  `enum_stats_type_id` bigint UNSIGNED NOT NULL,
  `period` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stats_types`
--

CREATE TABLE `stats_types` (
  `stats_type_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_management`
--

CREATE TABLE `stock_management` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `subscriber_id` bigint UNSIGNED NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_management`
--

CREATE TABLE `supplier_management` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_health`
--

CREATE TABLE `system_health` (
  `id` bigint UNSIGNED NOT NULL,
  `component` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cpu_usage` decimal(5,2) DEFAULT NULL,
  `memory_usage` decimal(5,2) DEFAULT NULL,
  `disk_usage` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `testimonial_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_cases`
--

CREATE TABLE `test_cases` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `test_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expected_result` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_reports`
--

CREATE TABLE `test_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `test_id` bigint UNSIGNED NOT NULL,
  `actual_result` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `executed_by` bigint UNSIGNED DEFAULT NULL,
  `executed_at` datetime DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` char(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plain_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `plain_password`, `phone`, `last_login`, `interface_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@azkagarden.com', '2025-07-31 19:37:23', '$2y$12$wybXULajlOxD42WBe3OnqufAsjUPFu9RU8NnGDAbW69JS8sHiwN6a', NULL, NULL, NULL, 1, 'KPcpqNZUuC', '2025-07-31 19:37:23', '2025-07-31 19:37:23'),
(2, 'Miss Irma Friesen I', 'monahan.omari@example.net', '2025-07-31 19:37:24', '$2y$12$C7.aLZQKt2ZT6FtlMtyWvexLBtWE3QVW5WKp4NeWhN44bcg6ebsuW', NULL, '+18316161432', NULL, 1, '9ExMNbsrUL', '2025-07-31 19:37:24', '2025-07-31 19:37:24'),
(3, 'Rita Sipes DDS', 'trenton35@example.com', '2025-07-31 19:37:24', '$2y$12$GY9wumQ0px9uFW/oQkrNLugSu1T29FGTU8hXQ/8jKhV4msnDHY8GG', NULL, '763.304.2349', NULL, 1, 'CI634gEALd', '2025-07-31 19:37:24', '2025-07-31 19:37:24'),
(4, 'Salvatore Gleason DDS', 'bogan.corbin@example.org', '2025-07-31 19:37:25', '$2y$12$IdHhM/Jm2TzKTFhXl6CrguAU10WIaixXO/IVNy.1TVax/GLGjuETm', NULL, '+1-731-398-6606', NULL, 1, 'Naz4raLwaa', '2025-07-31 19:37:25', '2025-07-31 19:37:25'),
(5, 'Roberto', 'gohs01381@gmail.com', NULL, '$2y$12$FgXFQPnp3awQAHe/W0F9Ge2z9uFE1LORKC.RcrebgMg6XdsG4Yvbe', 'Robee204', '08123456789', '2025-08-01 02:39:14', 1, NULL, '2025-07-31 19:39:14', '2025-07-31 19:39:30'),
(6, 'Lion', 'robee2025@gmail.com', NULL, '$2y$12$3lGYQnPx1ySQQUv0DWEvfuKq1.836aKlwpmxhmCzJjV8fYfyIZRaa', 'Robee2025', '08123456789', '2025-08-01 23:39:24', 1, NULL, '2025-08-01 16:39:24', '2025-08-01 16:39:44'),
(7, 'Roberto', 'robee20252@gmail.com', NULL, '$2y$12$kdMukOJ6vI.yYVdn1CDsxubZJmMUf26sdML9jgL8C.ImoKSvhcy/K', 'Robee2025', '08123456789', '2025-08-02 07:17:01', 1, NULL, '2025-08-02 00:17:01', '2025-08-02 00:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `validation_exception`
--

CREATE TABLE `validation_exception` (
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `errorCode` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vulnerabilities`
--

CREATE TABLE `vulnerabilities` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `severity` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fix_details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`),
  ADD KEY `addresses_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_username_unique` (`username`),
  ADD UNIQUE KEY `admins_email_unique` (`email`),
  ADD KEY `admins_role_id_foreign` (`role_id`),
  ADD KEY `admins_status_id_foreign` (`status_id`),
  ADD KEY `admins_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_logs_admin_id_foreign` (`admin_id`),
  ADD KEY `admin_logs_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_permissions_admin_id_foreign` (`admin_id`),
  ADD KEY `admin_permissions_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_roles_enum_admin_role_id_foreign` (`enum_admin_role_id`);

--
-- Indexes for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_sessions_admin_id_foreign` (`admin_id`),
  ADD KEY `admin_sessions_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `admin_statuses`
--
ALTER TABLE `admin_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_statuses_enum_admin_status_id_foreign` (`enum_admin_status_id`);

--
-- Indexes for table `api_documentations`
--
ALTER TABLE `api_documentations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_documentations_endpoint_id_foreign` (`endpoint_id`),
  ADD KEY `api_documentations_updated_by_foreign` (`updated_by`),
  ADD KEY `api_documentations_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `api_endpoints`
--
ALTER TABLE `api_endpoints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_endpoints_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `api_metrics`
--
ALTER TABLE `api_metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_metrics_endpoint_id_foreign` (`endpoint_id`),
  ADD KEY `api_metrics_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banners_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `bug_reports`
--
ALTER TABLE `bug_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bug_reports_assigned_to_foreign` (`assigned_to`),
  ADD KEY `bug_reports_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`cache_key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`lock_key`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_product_id_foreign` (`product_id`),
  ADD KEY `carts_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `charts`
--
ALTER TABLE `charts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `charts_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contacts_email_unique` (`email`);

--
-- Indexes for table `customer_support`
--
ALTER TABLE `customer_support`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_support_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_support_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `dashboards`
--
ALTER TABLE `dashboards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dashboards_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `database_backups`
--
ALTER TABLE `database_backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `database_backups_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `database_configs`
--
ALTER TABLE `database_configs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `database_configs_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `deployments`
--
ALTER TABLE `deployments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deployments_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `developers`
--
ALTER TABLE `developers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `developers_username_unique` (`username`),
  ADD KEY `developers_role_id_foreign` (`role_id`),
  ADD KEY `developers_status_id_foreign` (`status_id`),
  ADD KEY `developers_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `developer_logs`
--
ALTER TABLE `developer_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `developer_logs_developer_id_foreign` (`developer_id`),
  ADD KEY `developer_logs_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `developer_permissions`
--
ALTER TABLE `developer_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `developer_permissions_developer_id_foreign` (`developer_id`),
  ADD KEY `developer_permissions_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `dev_roles`
--
ALTER TABLE `dev_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dev_roles_enum_dev_role_id_foreign` (`enum_dev_role_id`);

--
-- Indexes for table `dev_statuses`
--
ALTER TABLE `dev_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dev_statuses_enum_dev_status_id_foreign` (`enum_dev_status_id`);

--
-- Indexes for table `dispute_management`
--
ALTER TABLE `dispute_management`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dispute_management_order_id_foreign` (`order_id`),
  ADD KEY `dispute_management_customer_id_foreign` (`customer_id`),
  ADD KEY `dispute_management_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `enum_admin_role`
--
ALTER TABLE `enum_admin_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enum_admin_role_value_unique` (`value`);

--
-- Indexes for table `enum_admin_status`
--
ALTER TABLE `enum_admin_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enum_admin_status_value_unique` (`value`);

--
-- Indexes for table `enum_dev_role`
--
ALTER TABLE `enum_dev_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enum_dev_role_value_unique` (`value`);

--
-- Indexes for table `enum_dev_status`
--
ALTER TABLE `enum_dev_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enum_dev_status_value_unique` (`value`);

--
-- Indexes for table `enum_order_status`
--
ALTER TABLE `enum_order_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enum_order_status_value_unique` (`value`);

--
-- Indexes for table `enum_payment_status`
--
ALTER TABLE `enum_payment_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enum_payment_status_value_unique` (`value`);

--
-- Indexes for table `enum_report_type`
--
ALTER TABLE `enum_report_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enum_report_type_value_unique` (`value`);

--
-- Indexes for table `enum_roles`
--
ALTER TABLE `enum_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enum_roles_value_unique` (`value`);

--
-- Indexes for table `enum_stats_type`
--
ALTER TABLE `enum_stats_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enum_stats_type_value_unique` (`value`);

--
-- Indexes for table `environments`
--
ALTER TABLE `environments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `environments_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `error_logs`
--
ALTER TABLE `error_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `error_logs_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `expired_orders`
--
ALTER TABLE `expired_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_enum_order_status_id_foreign` (`enum_order_status_id`),
  ADD KEY `orders_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faq_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_customer_id_foreign` (`customer_id`),
  ADD KEY `feedback_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `images_product_id_foreign` (`product_id`);

--
-- Indexes for table `interfaces`
--
ALTER TABLE `interfaces`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `interfaces_name_unique` (`name`);

--
-- Indexes for table `interface_methods`
--
ALTER TABLE `interface_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `interface_methods_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `newsletters_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `newsletter_subscribers_email_unique` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_interface_id_foreign` (`interface_id`),
  ADD KEY `idx_orders_user_status` (`user_id`,`enum_order_status_id`),
  ADD KEY `idx_orders_date` (`order_date`),
  ADD KEY `idx_orders_status` (`enum_order_status_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_product_id_foreign` (`product_id`),
  ADD KEY `order_details_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`);

--
-- Indexes for table `order_management`
--
ALTER TABLE `order_management`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_management_order_id_foreign` (`order_id`),
  ADD KEY `order_management_admin_id_foreign` (`admin_id`),
  ADD KEY `order_management_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_method_id_foreign` (`method_id`),
  ADD KEY `payments_enum_payment_status_id_foreign` (`enum_payment_status_id`),
  ADD KEY `payments_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_methods_code_unique` (`code`);

--
-- Indexes for table `performances`
--
ALTER TABLE `performances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `performances_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `policy_acceptances`
--
ALTER TABLE `policy_acceptances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `policy_acceptances_user_id_foreign` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `product_comments`
--
ALTER TABLE `product_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_comments_user_id_foreign` (`user_id`),
  ADD KEY `product_comments_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`),
  ADD KEY `product_images_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `product_likes`
--
ALTER TABLE `product_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_likes_product_id_foreign` (`product_id`),
  ADD KEY `product_likes_user_id_foreign` (`user_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promotions_promo_code_unique` (`promo_code`),
  ADD KEY `promotions_interface_id_foreign` (`interface_id`),
  ADD KEY `promotions_status_index` (`status`),
  ADD KEY `promotions_start_date_index` (`start_date`),
  ADD KEY `promotions_end_date_index` (`end_date`),
  ADD KEY `promotions_discount_type_index` (`discount_type`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_orders_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `query_optimizations`
--
ALTER TABLE `query_optimizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `query_optimizations_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `refund_management`
--
ALTER TABLE `refund_management`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refund_management_order_id_foreign` (`order_id`),
  ADD KEY `refund_management_processed_by_foreign` (`processed_by`),
  ADD KEY `refund_management_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `release_notes`
--
ALTER TABLE `release_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `release_notes_deployment_id_foreign` (`deployment_id`),
  ADD KEY `release_notes_created_by_foreign` (`created_by`),
  ADD KEY `release_notes_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_type_id_foreign` (`type_id`),
  ADD KEY `reports_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `report_types`
--
ALTER TABLE `report_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_types_enum_report_type_id_foreign` (`enum_report_type_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`),
  ADD KEY `roles_enum_role_id_foreign` (`enum_role_id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `security_audits`
--
ALTER TABLE `security_audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `security_audits_developer_id_foreign` (`developer_id`),
  ADD KEY `security_audits_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `security_configs`
--
ALTER TABLE `security_configs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `security_configs_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `security_logs_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `seeder_status`
--
ALTER TABLE `seeder_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `seeder_status_seeder_name_unique` (`seeder_name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_activity_idx` (`user_id`,`last_activity`),
  ADD KEY `sessions_activity_expiry_idx` (`last_activity`,`expires_at`),
  ADD KEY `sessions_user_token_idx` (`user_id`,`token`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`),
  ADD KEY `sessions_token_index` (`token`),
  ADD KEY `sessions_expires_at_index` (`expires_at`),
  ADD KEY `sessions_user_activity_index` (`user_id`,`last_activity`);

--
-- Indexes for table `shippings`
--
ALTER TABLE `shippings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shippings_order_id_foreign` (`order_id`),
  ADD KEY `shippings_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipping_methods_code_unique` (`code`),
  ADD KEY `shipping_methods_is_active_sort_index` (`is_active`,`sort`),
  ADD KEY `shipping_methods_code_index` (`code`);

--
-- Indexes for table `statistics`
--
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `statistics_enum_stats_type_id_foreign` (`enum_stats_type_id`),
  ADD KEY `statistics_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `stats_types`
--
ALTER TABLE `stats_types`
  ADD PRIMARY KEY (`stats_type_id`),
  ADD UNIQUE KEY `stats_types_code_unique` (`code`);

--
-- Indexes for table `stock_management`
--
ALTER TABLE `stock_management`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_management_product_id_foreign` (`product_id`),
  ADD KEY `stock_management_created_by_foreign` (`created_by`),
  ADD KEY `stock_management_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`subscriber_id`),
  ADD UNIQUE KEY `subscribers_email_unique` (`email`);

--
-- Indexes for table `supplier_management`
--
ALTER TABLE `supplier_management`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_management_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `system_health`
--
ALTER TABLE `system_health`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_health_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`testimonial_id`),
  ADD KEY `testimonials_user_id_foreign` (`user_id`);

--
-- Indexes for table `test_cases`
--
ALTER TABLE `test_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_cases_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `test_reports`
--
ALTER TABLE `test_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_reports_test_id_foreign` (`test_id`),
  ADD KEY `test_reports_executed_by_foreign` (`executed_by`),
  ADD KEY `test_reports_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_interface_id_foreign` (`interface_id`);

--
-- Indexes for table `vulnerabilities`
--
ALTER TABLE `vulnerabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vulnerabilities_interface_id_foreign` (`interface_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_roles`
--
ALTER TABLE `admin_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_statuses`
--
ALTER TABLE `admin_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_documentations`
--
ALTER TABLE `api_documentations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_endpoints`
--
ALTER TABLE `api_endpoints`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_metrics`
--
ALTER TABLE `api_metrics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `bug_reports`
--
ALTER TABLE `bug_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `charts`
--
ALTER TABLE `charts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_support`
--
ALTER TABLE `customer_support`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dashboards`
--
ALTER TABLE `dashboards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `database_backups`
--
ALTER TABLE `database_backups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `database_configs`
--
ALTER TABLE `database_configs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deployments`
--
ALTER TABLE `deployments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `developers`
--
ALTER TABLE `developers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `developer_logs`
--
ALTER TABLE `developer_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `developer_permissions`
--
ALTER TABLE `developer_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_roles`
--
ALTER TABLE `dev_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_statuses`
--
ALTER TABLE `dev_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dispute_management`
--
ALTER TABLE `dispute_management`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enum_admin_role`
--
ALTER TABLE `enum_admin_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `enum_admin_status`
--
ALTER TABLE `enum_admin_status`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `enum_dev_role`
--
ALTER TABLE `enum_dev_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `enum_dev_status`
--
ALTER TABLE `enum_dev_status`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `enum_order_status`
--
ALTER TABLE `enum_order_status`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `enum_payment_status`
--
ALTER TABLE `enum_payment_status`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `enum_report_type`
--
ALTER TABLE `enum_report_type`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `enum_roles`
--
ALTER TABLE `enum_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `enum_stats_type`
--
ALTER TABLE `enum_stats_type`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `environments`
--
ALTER TABLE `environments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `error_logs`
--
ALTER TABLE `error_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expired_orders`
--
ALTER TABLE `expired_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interfaces`
--
ALTER TABLE `interfaces`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `interface_methods`
--
ALTER TABLE `interface_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5194;

--
-- AUTO_INCREMENT for table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2014;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_management`
--
ALTER TABLE `order_management`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `performances`
--
ALTER TABLE `performances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `policy_acceptances`
--
ALTER TABLE `policy_acceptances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `product_comments`
--
ALTER TABLE `product_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=319;

--
-- AUTO_INCREMENT for table `product_likes`
--
ALTER TABLE `product_likes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `query_optimizations`
--
ALTER TABLE `query_optimizations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refund_management`
--
ALTER TABLE `refund_management`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `release_notes`
--
ALTER TABLE `release_notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_types`
--
ALTER TABLE `report_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `security_audits`
--
ALTER TABLE `security_audits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `security_configs`
--
ALTER TABLE `security_configs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `security_logs`
--
ALTER TABLE `security_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seeder_status`
--
ALTER TABLE `seeder_status`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shippings`
--
ALTER TABLE `shippings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `statistics`
--
ALTER TABLE `statistics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stats_types`
--
ALTER TABLE `stats_types`
  MODIFY `stats_type_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_management`
--
ALTER TABLE `stock_management`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `subscriber_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_management`
--
ALTER TABLE `supplier_management`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_health`
--
ALTER TABLE `system_health`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `testimonial_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_cases`
--
ALTER TABLE `test_cases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_reports`
--
ALTER TABLE `test_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vulnerabilities`
--
ALTER TABLE `vulnerabilities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Structure for view `global_payment_methods`
--
DROP TABLE IF EXISTS `global_payment_methods`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `global_payment_methods`  AS SELECT `payment_methods`.`id` AS `id`, `payment_methods`.`code` AS `code`, `payment_methods`.`name` AS `name`, `payment_methods`.`type` AS `type`, `payment_methods`.`config` AS `config`, `payment_methods`.`status` AS `status`, `payment_methods`.`created_at` AS `created_at`, `payment_methods`.`updated_at` AS `updated_at` FROM `payment_methods` WHERE (`payment_methods`.`type` = 'GLOBAL') ;

-- --------------------------------------------------------

--
-- Structure for view `local_payment_methods`
--
DROP TABLE IF EXISTS `local_payment_methods`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `local_payment_methods`  AS SELECT `payment_methods`.`id` AS `id`, `payment_methods`.`code` AS `code`, `payment_methods`.`name` AS `name`, `payment_methods`.`type` AS `type`, `payment_methods`.`config` AS `config`, `payment_methods`.`status` AS `status`, `payment_methods`.`created_at` AS `created_at`, `payment_methods`.`updated_at` AS `updated_at` FROM `payment_methods` WHERE (`payment_methods`.`type` = 'LOCAL') ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`),
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`),
  ADD CONSTRAINT `admins_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `admin_roles` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `admins_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `admin_statuses` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_logs_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD CONSTRAINT `admin_permissions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_permissions_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`);

--
-- Constraints for table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD CONSTRAINT `admin_roles_enum_admin_role_id_foreign` FOREIGN KEY (`enum_admin_role_id`) REFERENCES `enum_admin_role` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD CONSTRAINT `admin_sessions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `admin_sessions_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `admin_statuses`
--
ALTER TABLE `admin_statuses`
  ADD CONSTRAINT `admin_statuses_enum_admin_status_id_foreign` FOREIGN KEY (`enum_admin_status_id`) REFERENCES `enum_admin_status` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `api_documentations`
--
ALTER TABLE `api_documentations`
  ADD CONSTRAINT `api_documentations_endpoint_id_foreign` FOREIGN KEY (`endpoint_id`) REFERENCES `api_endpoints` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `api_documentations_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `api_documentations_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `developers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `api_endpoints`
--
ALTER TABLE `api_endpoints`
  ADD CONSTRAINT `api_endpoints_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `api_metrics`
--
ALTER TABLE `api_metrics`
  ADD CONSTRAINT `api_metrics_endpoint_id_foreign` FOREIGN KEY (`endpoint_id`) REFERENCES `api_endpoints` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `api_metrics_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `banners`
--
ALTER TABLE `banners`
  ADD CONSTRAINT `banners_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `bug_reports`
--
ALTER TABLE `bug_reports`
  ADD CONSTRAINT `bug_reports_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `developers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bug_reports_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`),
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`);

--
-- Constraints for table `charts`
--
ALTER TABLE `charts`
  ADD CONSTRAINT `charts_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `customer_support`
--
ALTER TABLE `customer_support`
  ADD CONSTRAINT `customer_support_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_support_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `dashboards`
--
ALTER TABLE `dashboards`
  ADD CONSTRAINT `dashboards_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `database_backups`
--
ALTER TABLE `database_backups`
  ADD CONSTRAINT `database_backups_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `database_configs`
--
ALTER TABLE `database_configs`
  ADD CONSTRAINT `database_configs_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `deployments`
--
ALTER TABLE `deployments`
  ADD CONSTRAINT `deployments_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `developers`
--
ALTER TABLE `developers`
  ADD CONSTRAINT `developers_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `developers_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `dev_roles` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `developers_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dev_statuses` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `developer_logs`
--
ALTER TABLE `developer_logs`
  ADD CONSTRAINT `developer_logs_developer_id_foreign` FOREIGN KEY (`developer_id`) REFERENCES `developers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `developer_logs_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `developer_permissions`
--
ALTER TABLE `developer_permissions`
  ADD CONSTRAINT `developer_permissions_developer_id_foreign` FOREIGN KEY (`developer_id`) REFERENCES `developers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `developer_permissions_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `dev_roles`
--
ALTER TABLE `dev_roles`
  ADD CONSTRAINT `dev_roles_enum_dev_role_id_foreign` FOREIGN KEY (`enum_dev_role_id`) REFERENCES `enum_dev_role` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `dev_statuses`
--
ALTER TABLE `dev_statuses`
  ADD CONSTRAINT `dev_statuses_enum_dev_status_id_foreign` FOREIGN KEY (`enum_dev_status_id`) REFERENCES `enum_dev_status` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `dispute_management`
--
ALTER TABLE `dispute_management`
  ADD CONSTRAINT `dispute_management_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dispute_management_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `dispute_management_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `environments`
--
ALTER TABLE `environments`
  ADD CONSTRAINT `environments_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `error_logs`
--
ALTER TABLE `error_logs`
  ADD CONSTRAINT `error_logs_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `faq`
--
ALTER TABLE `faq`
  ADD CONSTRAINT `faq_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `interface_methods`
--
ALTER TABLE `interface_methods`
  ADD CONSTRAINT `interface_methods_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `newsletters`
--
ALTER TABLE `newsletters`
  ADD CONSTRAINT `newsletters_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_enum_order_status_id_foreign` FOREIGN KEY (`enum_order_status_id`) REFERENCES `enum_order_status` (`id`),
  ADD CONSTRAINT `orders_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`),
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`),
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_management`
--
ALTER TABLE `order_management`
  ADD CONSTRAINT `order_management_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `order_management_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `order_management_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_enum_payment_status_id_foreign` FOREIGN KEY (`enum_payment_status_id`) REFERENCES `enum_payment_status` (`id`),
  ADD CONSTRAINT `payments_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `payments_method_id_foreign` FOREIGN KEY (`method_id`) REFERENCES `payment_methods` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `performances`
--
ALTER TABLE `performances`
  ADD CONSTRAINT `performances_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `policy_acceptances`
--
ALTER TABLE `policy_acceptances`
  ADD CONSTRAINT `policy_acceptances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `products_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`);

--
-- Constraints for table `product_comments`
--
ALTER TABLE `product_comments`
  ADD CONSTRAINT `product_comments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_likes`
--
ALTER TABLE `product_likes`
  ADD CONSTRAINT `product_likes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `promotions_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_management` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `query_optimizations`
--
ALTER TABLE `query_optimizations`
  ADD CONSTRAINT `query_optimizations_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `refund_management`
--
ALTER TABLE `refund_management`
  ADD CONSTRAINT `refund_management_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `refund_management_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `refund_management_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `release_notes`
--
ALTER TABLE `release_notes`
  ADD CONSTRAINT `release_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `developers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `release_notes_deployment_id_foreign` FOREIGN KEY (`deployment_id`) REFERENCES `deployments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `release_notes_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `reports_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `report_types` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `report_types`
--
ALTER TABLE `report_types`
  ADD CONSTRAINT `report_types_enum_report_type_id_foreign` FOREIGN KEY (`enum_report_type_id`) REFERENCES `enum_report_type` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`),
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_enum_role_id_foreign` FOREIGN KEY (`enum_role_id`) REFERENCES `enum_roles` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `security_audits`
--
ALTER TABLE `security_audits`
  ADD CONSTRAINT `security_audits_developer_id_foreign` FOREIGN KEY (`developer_id`) REFERENCES `developers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `security_audits_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `security_configs`
--
ALTER TABLE `security_configs`
  ADD CONSTRAINT `security_configs_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD CONSTRAINT `security_logs_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shippings`
--
ALTER TABLE `shippings`
  ADD CONSTRAINT `shippings_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `shippings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `statistics`
--
ALTER TABLE `statistics`
  ADD CONSTRAINT `statistics_enum_stats_type_id_foreign` FOREIGN KEY (`enum_stats_type_id`) REFERENCES `enum_stats_type` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `statistics_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `stock_management`
--
ALTER TABLE `stock_management`
  ADD CONSTRAINT `stock_management_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_management_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `stock_management_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `supplier_management`
--
ALTER TABLE `supplier_management`
  ADD CONSTRAINT `supplier_management_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `system_health`
--
ALTER TABLE `system_health`
  ADD CONSTRAINT `system_health_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `testimonials_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `test_cases`
--
ALTER TABLE `test_cases`
  ADD CONSTRAINT `test_cases_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `test_reports`
--
ALTER TABLE `test_reports`
  ADD CONSTRAINT `test_reports_executed_by_foreign` FOREIGN KEY (`executed_by`) REFERENCES `developers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `test_reports_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `test_reports_test_id_foreign` FOREIGN KEY (`test_id`) REFERENCES `test_cases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `vulnerabilities`
--
ALTER TABLE `vulnerabilities`
  ADD CONSTRAINT `vulnerabilities_interface_id_foreign` FOREIGN KEY (`interface_id`) REFERENCES `interfaces` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
