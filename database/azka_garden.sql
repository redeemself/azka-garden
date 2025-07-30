-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 30, 2025 at 12:18 PM
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
(1, 5, 'Alamat Saya', 'Roberto', '081281349115', 'Jl. Swadarma Raya Blok F No.8Sukamaju, Kec. Cilodong, Kota Depok, Jawa Barat 16415', 'Depok', '16415', 1, 1, '2025-07-30 01:03:49', '2025-07-30 01:55:26', NULL, NULL, NULL, NULL, NULL),
(2, 6, 'Alamat Saya', 'Roberto Ocaviantyo Tahta Laksmana', '081281349115', 'jl dwdadaw', 'Depok', '16415', 1, 1, '2025-07-30 01:45:33', '2025-07-30 01:45:33', NULL, NULL, NULL, NULL, NULL);

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
(3, 5, 55, 2, 'PROMO-INY4MR', 12500, 0, NULL, 1, '2025-07-30 04:38:49', '2025-07-30 05:09:57'),
(4, 5, 56, 2, 'PROMO-INY4MR', 2500, 0, NULL, 1, '2025-07-30 04:39:19', '2025-07-30 05:09:57');

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
(1, 'Tanaman Hias', 'Tanaman hias indoor dan outdoor', NULL, 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(2, 'Pot', 'Berbagai jenis pot taman', NULL, 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(3, 'Batu Hias', 'Batu taman hias', NULL, 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(4, 'Tanah', 'Media tanah kemasan', NULL, 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06');

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
(1, 'Newsletter Subscriber', 'redeemself0@gmail.com', NULL, 'newsletter', 'PROMO-INY4MR', '2025-07-30 04:39:55', '2025-07-30 04:39:55');

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
(1, 'CUSTOMER', '2025-07-30 00:51:31', '2025-07-30 00:51:31'),
(2, 'GUEST', '2025-07-30 00:51:31', '2025-07-30 00:51:31'),
(3, 'ADMIN', '2025-07-30 00:51:31', '2025-07-30 00:51:31'),
(4, 'USER', '2025-07-30 00:51:31', '2025-07-30 00:51:31'),
(5, 'DEVELOPER', '2025-07-30 00:51:31', '2025-07-30 00:51:31');

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
`code` varchar(30)
,`config` json
,`created_at` timestamp
,`id` bigint unsigned
,`name` varchar(50)
,`status` tinyint(1)
,`type` enum('LOCAL','GLOBAL')
,`updated_at` timestamp
);

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
`code` varchar(30)
,`config` json
,`created_at` timestamp
,`id` bigint unsigned
,`name` varchar(50)
,`status` tinyint(1)
,`type` enum('LOCAL','GLOBAL')
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
(4220, '2025_06_21_000000_create_interfaces_table', 3),
(4221, '2025_06_21_000001_create_interface_methods_table', 3),
(4222, '2025_06_21_000002_create_enum_roles_table', 3),
(4223, '2025_06_21_000003_create_enum_order_status_table', 3),
(4224, '2025_06_21_000004_create_enum_payment_status_table', 3),
(4225, '2025_06_21_000005_create_enum_admin_role_table', 3),
(4226, '2025_06_21_000006_create_enum_admin_status_table', 3),
(4227, '2025_06_21_000007_create_enum_stats_type_table', 3),
(4228, '2025_06_21_000008_create_enum_report_type_table', 3),
(4229, '2025_06_21_000009_create_enum_dev_role_table', 3),
(4230, '2025_06_21_000010_create_enum_dev_status_table', 3),
(4231, '2025_06_21_000011_create_roles_table', 3),
(4232, '2025_06_21_000012_create_users_table', 3),
(4233, '2025_06_21_000013_create_addresses_table', 3),
(4234, '2025_06_21_000014_create_categories_table', 3),
(4235, '2025_06_21_000015_create_products_table', 3),
(4236, '2025_06_21_000016_create_product_images_table', 3),
(4237, '2025_06_21_000017_create_reviews_table', 3),
(4238, '2025_06_21_000018_create_carts_table', 3),
(4239, '2025_06_21_000019_create_orders_table', 3),
(4240, '2025_06_21_000020_create_order_details_table', 3),
(4241, '2025_06_21_000021_create_payment_methods_table', 3),
(4242, '2025_06_21_000022_create_payments_table', 3),
(4243, '2025_06_21_000023_create_shippings_table', 3),
(4244, '2025_06_21_000024_create_admin_roles_table', 3),
(4245, '2025_06_21_000025_create_admin_statuses_table', 3),
(4246, '2025_06_21_000026_create_admins_table', 3),
(4247, '2025_06_21_000027_create_admin_permissions_table', 3),
(4248, '2025_06_21_000028_create_admin_logs_table', 3),
(4249, '2025_06_21_000029_create_dashboards_table', 3),
(4250, '2025_06_21_000030_create_statistics_table', 3),
(4251, '2025_06_21_000031_create_banners_table', 3),
(4252, '2025_06_21_000032_create_promotions_table', 3),
(4253, '2025_06_21_000033_create_newsletters_table', 3),
(4254, '2025_06_21_000034_create_order_management_table', 3),
(4255, '2025_06_21_000035_create_refund_management_table', 3),
(4256, '2025_06_21_000036_create_dispute_management_table', 3),
(4257, '2025_06_21_000037_create_stock_management_table', 3),
(4258, '2025_06_21_000038_create_supplier_management_table', 3),
(4259, '2025_06_21_000039_create_purchase_orders_table', 3),
(4260, '2025_06_21_000040_create_customer_support_table', 3),
(4261, '2025_06_21_000041_create_faq_table', 3),
(4262, '2025_06_21_000042_create_feedback_table', 3),
(4263, '2025_06_21_000043_create_audit_logs_table', 3),
(4264, '2025_06_21_000044_create_security_logs_table', 3),
(4265, '2025_06_21_000045_create_admin_sessions_table', 3),
(4266, '2025_06_21_000046_create_dev_roles_table', 3),
(4267, '2025_06_21_000047_create_dev_statuses_table', 3),
(4268, '2025_06_21_000048_create_developers_table', 3),
(4269, '2025_06_21_000049_create_developer_permissions_table', 3),
(4270, '2025_06_21_000050_create_developer_logs_table', 3),
(4271, '2025_06_21_000051_create_api_endpoints_table', 3),
(4272, '2025_06_21_000052_create_api_documentations_table', 3),
(4273, '2025_06_21_000053_create_api_metrics_table', 3),
(4274, '2025_06_21_000054_create_system_health_table', 3),
(4275, '2025_06_21_000055_create_error_logs_table', 3),
(4276, '2025_06_21_000056_create_performances_table', 3),
(4277, '2025_06_21_000057_create_database_configs_table', 3),
(4278, '2025_06_21_000058_create_query_optimizations_table', 3),
(4279, '2025_06_21_000059_create_database_backups_table', 3),
(4280, '2025_06_21_000060_create_security_audits_table', 3),
(4281, '2025_06_21_000061_create_vulnerabilities_table', 3),
(4282, '2025_06_21_000062_create_security_configs_table', 3),
(4283, '2025_06_21_000063_create_deployments_table', 3),
(4284, '2025_06_21_000064_create_environments_table', 3),
(4285, '2025_06_21_000065_create_release_notes_table', 3),
(4286, '2025_06_21_000066_create_test_cases_table', 3),
(4287, '2025_06_21_000067_create_test_reports_table', 3),
(4288, '2025_06_21_000068_create_bug_reports_table', 3),
(4289, '2025_06_21_000069_create_business_exception_table', 3),
(4290, '2025_06_21_000070_create_validation_exception_table', 3),
(4291, '2025_06_21_000071_create_resource_not_found_exception_table', 3),
(4292, '2025_06_21_000072_create_payment_exception_table', 3),
(4293, '2025_06_21_000073_create_shipping_exception_table', 3),
(4294, '2025_06_30_000074_create_sessions_table', 3),
(4295, '2025_06_30_000075_create_stats_types_table', 3),
(4296, '2025_07_04_000076_create_cache_table', 3),
(4297, '2025_07_07_000077_create_subscribers_table', 3),
(4298, '2025_07_08_000078_create_policy_acceptances_table', 3),
(4299, '2025_07_09_000079_create_testimonials_table', 3),
(4300, '2025_07_09_000080_add_is_featured_to_products_table', 3),
(4301, '2025_07_11_000081_create_payment_methods_views', 3),
(4302, '2025_07_11_000082_create_report_types_table', 3),
(4303, '2025_07_11_000083_create_reports_table', 3),
(4304, '2025_07_11_000084_create_charts_table', 3),
(4305, '2025_07_14_000085_create_role_user_table', 3),
(4306, '2025_07_14_000086_drop_role_id_from_users_table', 3),
(4307, '2025_07_15_000087_create_contacts_table', 3),
(4308, '2025_07_16_082209_create_seeder_status_table', 3),
(4309, '2025_07_16_114644_create_enum_roles_table', 3),
(4310, '2025_07_18_000000_create_faq_table', 3),
(4311, '2025_07_20_141500_make_message_nullable_on_contacts_table', 3),
(4312, '2025_07_21_000000_add_promo_code_and_discount_to_carts_table', 3),
(4313, '2025_07_21_000000_add_promo_code_to_contacts_table', 3),
(4314, '2025_07_21_000000_create_newsletter_subscribers_table', 3),
(4315, '2025_07_23_040342_add_promo_fields_to_promotions_table', 3),
(4316, '2025_07_23_042518_add_promo_code_to_promotions_table', 3),
(4317, '2025_07_23_082921_create_product_likes_table', 3),
(4318, '2025_07_24_000001_add_payment_method_to_orders_table', 3),
(4319, '2025_07_25_000001_add_plain_password_to_users_table', 3),
(4320, '2025_07_29_040847_add_price_discount_to_carts_table', 3),
(4321, '2025_07_29_110000_add_description_to_payment_methods_table', 3),
(4322, '2025_07_29_131408_create_shipping_methods_table', 3),
(4323, '2025_07_29_131408_update_shippings_table', 3),
(4324, '2025_07_29_153301_update_orders_table', 3),
(4325, '2025_07_30_074735_add_missing_fields_to_addresses_table', 3);

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
  `order_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_date` datetime NOT NULL,
  `enum_order_status_id` bigint UNSIGNED NOT NULL,
  `total_price` decimal(14,2) NOT NULL,
  `shipping_cost` decimal(12,2) NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interface_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_code`, `order_date`, `enum_order_status_id`, `total_price`, `shipping_cost`, `note`, `payment_method`, `interface_id`, `created_at`, `updated_at`) VALUES
(2001, 1, 'ORD-2001', '2025-07-30 14:58:06', 1, 100000.00, 10000.00, NULL, NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(2002, 1, 'ORD-2002', '2025-07-30 14:58:06', 1, 150000.00, 15000.00, NULL, NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(2003, 1, 'ORD-2003', '2025-07-30 14:58:06', 1, 200000.00, 20000.00, NULL, NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(2004, 1, 'ORD-2004', '2025-07-30 14:58:06', 1, 250000.00, 25000.00, NULL, NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(2005, 1, 'ORD-2005', '2025-07-30 14:58:06', 1, 120000.00, 12000.00, NULL, NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(2006, 1, 'ORD-2006', '2025-07-30 14:58:06', 1, 140000.00, 14000.00, NULL, NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(2007, 1, 'ORD-2007', '2025-07-30 14:58:06', 1, 150000.00, 15000.00, NULL, NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(2008, 1, 'ORD-2008', '2025-07-30 14:58:06', 1, 100000.00, 0.00, NULL, NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(2009, 5, 'ORD-20250730-NYSOJU', '2025-07-30 11:28:22', 1, 50000.00, 25000.00, NULL, 'CASH', 1, '2025-07-30 04:28:22', '2025-07-30 04:28:22'),
(2010, 5, 'ORD-20250730-MWP2X0', '2025-07-30 11:33:49', 1, 40000.00, 15000.00, NULL, 'CASH', 1, '2025-07-30 04:33:49', '2025-07-30 04:33:49');

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

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `subtotal`, `note`, `interface_id`, `created_at`, `updated_at`) VALUES
(1, 2009, 56, 1, 25000.00, 25000.00, NULL, 1, '2025-07-30 04:28:22', '2025-07-30 04:28:22'),
(2, 2010, 56, 1, 25000.00, 25000.00, NULL, 1, '2025-07-30 04:33:49', '2025-07-30 04:33:49');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `code`, `name`, `description`, `type`, `config`, `status`, `created_at`, `updated_at`) VALUES
(4, 'CASH', 'Uang Tunai di Tempat', 'Bayar langsung secara tunai kepada kurir saat barang diterima di alamat tujuan.', 'LOCAL', '{}', 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(5, 'COD_QRIS', 'COD dengan QRIS/E-Wallet', 'Bayar di tempat tujuan melalui QRIS atau E-Wallet (Scan QR, OVO, GoPay, DANA, dll) kepada kurir.', 'LOCAL', '{}', 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(6, 'QRIS', 'Pembayaran QRIS', 'Bayar secara instan melalui QRIS dari semua aplikasi e-wallet. Transaksi digital, aman, dan cepat.', 'LOCAL', '{}', 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(7, 'EWALLET', 'E-Wallet', 'Pembayaran digital melalui OVO, GoPay, DANA, dan e-wallet lainnya. Transaksi instan dan tercatat.', 'LOCAL', '{}', 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06');

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
(54, 1, 'Jamani Dolar', 'Jamani Dolar (Zamioculcas zamiifolia) merupakan tanaman perennial tropis dari keluarga Araceae yang berasal dari Afrika Timur seperti Kenya, Tanzania, dan Afrika Selatan. Tumbuh dari rimpang tebal yang menyimpan cadangan air, tanaman ini menghasilkan daun majemuk menyirip berwarna hijau pekat dan mengkilap dengan 6–8 pasang foliol oval sepanjang 7–15 cm. ZZ Plant sangat toleran terhadap cahaya rendah hingga sedang dan mampu bertahan lama dalam kondisi kekeringan. Harga pasaran tanaman ini sekitar Rp70.000.', 10, 70000.00, 1.00, 'images/produk/jamani_dolar.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(55, 1, 'Dragon Sekel', 'Dragon Sekel atau Tengkorak (Alocasia baginda \'Dragon Scale\') adalah varietas Alocasia dari keluarga Araceae yang terkenal dengan motif daun unik menyerupai sisik naga. Daunnya hijau zamrud dengan urat perak metalik yang mencolok, memberikan kesan eksotis dan elegan. Tanaman ini sangat cocok untuk dekorasi interior karena toleransinya terhadap cahaya rendah hingga sedang serta kemampuannya bertahan dalam kondisi kering. Harga pasaran sekitar Rp125.000.', 8, 125000.00, 1.00, 'images/produk/dragon_sekel_atau_tengkorak.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(56, 1, 'Pakis Kuning', 'Pakis Kuning (Nephrolepis exaltata \'Golden\') adalah varietas pakis hias yang memiliki daun muda berwarna kuning cerah yang berubah menjadi hijau saat dewasa. Daunnya berbentuk pedang dan tumbuh merumpun, menciptakan tampilan alami dan menyegarkan. Tanaman ini ideal ditempatkan di area teduh dengan cahaya matahari tidak langsung dan mudah dirawat, memberikan sentuhan hijau segar pada lingkungan sekitar. Harga pasaran sekitar Rp25.000.', 18, 25000.00, 1.00, 'images/produk/pakis_kuning.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 04:33:48'),
(57, 1, 'Kuping Gajah', 'Kuping Gajah (Anthurium crystallinum) adalah varietas Anthurium dari keluarga Araceae dengan daun besar berbentuk hati dan permukaan berkilau. Urat daun berwarna keputih-putihan yang mencolok menambah kesan elegan dan eksotis. Tanaman ini cocok untuk dekorasi interior, memiliki toleransi terhadap cahaya rendah hingga sedang serta tahan pada periode kekeringan. Harga pasaran sekitar Rp75.000.', 15, 75000.00, 1.00, 'images/produk/kuping_gajah.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(58, 1, 'Cemara Ekor Tupai', 'Cemara Ekor Tupai (Asparagus densiflorus) merupakan tanaman tahunan hijau abadi dari keluarga Asparagaceae yang berasal dari Afrika Selatan. Daunnya menyerupai ekor tupai dengan daun kecil berwarna hijau cerah yang tumbuh rimbun dan mengerucut. Tanaman ini cocok sebagai tanaman hias interior karena toleransi terhadap cahaya rendah hingga sedang serta kemampuannya bertahan pada kondisi kering. Harga pasaran sekitar Rp40.000.', 12, 40000.00, 1.00, 'images/produk/cemara_ekor_tupay.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(59, 2, 'Pot Tanah Liat', 'Pot Tanah Liat diameter 15 cm dibuat dari bahan tanah liat berkualitas tinggi dengan desain minimalis yang sesuai untuk berbagai tanaman hias kecil hingga sedang. Pot ini tersedia dalam warna coklat, hitam, dan putih, memberikan pilihan dekorasi menarik serta harga terjangkau untuk menambah estetika tanaman di rumah Anda. Harga pot ini sekitar Rp40.000.', 50, 40000.00, 2.00, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_15.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(60, 1, 'Puting Cabe', 'Puting Cabe (Euphorbia milii) adalah tanaman hias berbunga dari keluarga Euphorbiaceae yang memiliki bunga kecil cerah serta duri tajam pada batangnya. Daunnya hijau rapat dengan bunga muncul dalam kelompok kecil, menciptakan tampilan eksotis. Tanaman ini tahan terhadap cahaya rendah hingga sedang dan mampu bertahan dalam kondisi kering berkat cadangan air pada batangnya. Harga pasaran sekitar Rp10.000.', 30, 10000.00, 0.30, 'images/produk/puting_cabe.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(61, 1, 'Cemara Perak', 'Cemara Perak (Juniperus chinensis) merupakan tanaman konifer hijau kekuningan berbentuk rimbun menyerupai pohon cemara mini. Tanaman ini cocok untuk taman, halaman, maupun sebagai tanaman indoor, memberikan kesan alami dan segar. Harga pasaran sekitar Rp50.000.', 10, 50000.00, 2.00, 'images/produk/cemara_perak.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(62, 1, 'Bringin Korea Tinggi 2M', 'Bringin Korea (Ficus microcarpa) adalah tanaman hias populer untuk taman dan interior dengan tinggi sekitar 2 meter, batang kokoh, dan daun hijau mengkilap yang memberikan suasana alami dan sejuk. Harga pasaran sekitar Rp2.000.000, mencerminkan kualitas dan ukuran yang besar. Tanaman ini mudah beradaptasi dengan berbagai kondisi cahaya dan perawatan sehingga cocok untuk pemula maupun penghobi.', 2, 2000000.00, 8.00, 'images/produk/bringin_korea_tinggi_2M.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(63, 1, 'Gestrum Kuning', 'Gestrum Kuning (Gestrum coromandelianum) adalah tanaman tropis dengan bunga kuning cerah dan daun hijau lebat yang dapat tumbuh hingga 2 meter. Tanaman ini cocok untuk taman atau halaman rumah, tahan berbagai kondisi cuaca dan mudah dirawat. Harga pasar sekitar Rp30.000.', 15, 30000.00, 1.00, 'images/produk/gestrum_kuning.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(64, 1, 'Brokoli Hijau', 'Brokoli Hijau adalah tanaman hias dengan daun hijau segar yang menyerupai sayur brokoli. Tanaman ini sering digunakan sebagai tanaman hias unik yang menambah sentuhan alami pada taman atau ruangan. Harga pasaran sekitar Rp10.000.', 25, 10000.00, 0.30, 'images/produk/brokoli_hijau.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(65, 1, 'Siklok', 'Siklok (Agave attenuata) atau Foxtail Agave adalah tanaman sukulen asal Meksiko dengan daun panjang runcing berwarna hijau keabu-abuan dengan pinggiran putih membentuk roseta yang elegan. Tahan terhadap panas dan kekeringan, cocok untuk taman tropis maupun subtropis serta perawatan mudah. Harga sekitar Rp70.000.', 10, 70000.00, 2.00, 'images/produk/siklok.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(66, 1, 'Sampang Dara', 'Sampang Dara (Excoecaria cochinchinensis) adalah perdu tropis dengan daun hijau cerah di bagian atas dan merah gelap di bagian bawah, tumbuh hingga 1–2 meter. Memberikan tampilan alami dan eksotis, tanaman ini cocok untuk taman indoor maupun outdoor. Harga sekitar Rp16.000, namun perlu hati-hati karena getahnya beracun saat perawatan.', 15, 16000.00, 1.00, 'images/produk/sampang_dara.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(68, 1, 'Teratai', 'Teratai (Nymphaea) adalah tanaman air dengan bunga besar indah yang mengapung di permukaan air. Warnanya bervariasi dari putih, merah muda hingga ungu, sering digunakan untuk mempercantik kolam atau taman air. Harga pasaran sekitar Rp75.000.', 10, 75000.00, 2.00, 'images/produk/teratai.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(69, 1, 'Airis Brazil', 'Airis Brazil (Iris variegata) adalah tanaman hias outdoor dengan daun panjang hijau cerah bergaris putih yang memberikan tampilan segar dan menarik, cocok untuk taman dan pot. Harga pasar sekitar Rp10.000.', 10, 10000.00, 0.30, 'images/produk/airis_brazil.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(70, 3, 'Batu Taman Hitam Putih', 'Batu Taman Hitam dan Putih adalah batu hias yang digunakan untuk dekorasi taman, tersedia dalam warna hitam dan putih yang memberikan kontras alami dan estetis pada taman. Harga sekitar Rp30.000.', 100, 30000.00, 2.00, 'images/produk/batu_taman_hitam_putih.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(71, 1, 'Maranti Bali', 'Maranti Bali (Stromanthe sanguinea) adalah tanaman hias tropis dari hutan hujan Brasil yang memiliki daun berwarna-warni merah, hijau, dan putih mencolok, sangat populer di kalangan penggemar tanaman hias. Harga pasaran sekitar Rp15.000.', 15, 15000.00, 0.70, 'images/produk/maranti_bali.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(72, 1, 'Kadaka Tanduk', 'Kadaka Tanduk (Platycerium) adalah tanaman paku-pakuan epifit yang biasanya hidup menempel pada batang tanaman lain, namun dapat juga ditanam dalam pot dan umum ditemukan di daerah lembap. Harga sekitar Rp30.000.', 10, 30000.00, 0.50, 'images/produk/kadaka_tanduk.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(73, 1, 'Jayen', 'Jayen (Episcia) adalah tanaman hias indoor dengan daun berbentuk hati dan bunga kecil berwarna cerah, cocok untuk dekorasi meja atau rak tanaman dalam ruangan. Harga sekitar Rp80.000.', 5, 80000.00, 0.20, 'images/produk/jayen.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(74, 1, 'Alamanda Kuning', 'Alamanda Kuning (Allamanda cathartica) adalah tanaman hias berbunga terompet emas berwarna kuning cerah dengan diameter 5–7,5 cm, populer untuk taman dan pagar hidup. Harga pasar sekitar Rp75.000.', 10, 75000.00, 1.00, 'images/produk/alamanda_kuning.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(75, 1, 'Sarbena Putih', 'Sarbena Putih (Sabrina) adalah tanaman hias gantung dengan bunga putih kecil yang menawan, ideal untuk taman minimalis atau teras rumah. Harga sekitar Rp10.000.', 20, 10000.00, 0.30, 'images/produk/sarbena_putih.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(76, 1, 'Sarbena Hijau', 'Sarbena Hijau adalah varian tanaman hias dengan daun hijau cerah yang memberikan kesan segar dan alami pada ruang hijau. Harga sekitar Rp10.000.', 20, 10000.00, 0.30, 'images/produk/sarbena_hijau.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(77, 1, 'Pitalub Kecil', 'Pitalub Kecil adalah tanaman hias kecil dengan daun lebat berwarna hijau, cocok sebagai penghias meja atau sudut ruangan, mudah dirawat dan sesuai untuk pemula. Harga pasaran sekitar Rp30.000.', 20, 30000.00, 0.30, 'images/produk/pitalub_kecil.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(78, 1, 'Aglonema Valentin', 'Aglonema Valentin adalah tanaman hias dengan daun hijau-merah muda yang populer untuk dekorasi interior dan mudah tumbuh subur di tempat teduh. Harga sekitar Rp70.000.', 10, 70000.00, 0.40, 'images/produk/aglonema_valentin.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(79, 2, 'Pot Kapsul', 'Pot Kapsul Coklat dan Hitam (diameter 35 cm, tinggi 60 cm) adalah pot dengan desain kapsul elegan yang cocok untuk tanaman besar atau bonsai. Harga sekitar Rp85.000.', 10, 85000.00, 3.00, 'images/produk/pot_kapsul_hitam_coklat_hitam_diameter_35_tinggi_60.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(80, 2, 'Pot Tanah Minimalis', 'Pot Tanah Coklat, Putih, dan Bintik Hitam (diameter 30 cm) adalah pot tanah liat minimalis yang sesuai untuk berbagai tanaman hias. Harga sekitar Rp65.000.', 15, 65000.00, 2.50, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_30.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(81, 2, 'Pot Hitam Besar', 'Pot Hitam Diameter 40 cm adalah pot plastik hitam berukuran besar yang tahan lama dan ideal untuk tanaman hias berukuran sedang hingga besar. Pot ini dapat digunakan di dalam maupun luar ruangan. Harga sekitar Rp40.000.', 30, 40000.00, 2.50, 'images/produk/pot_hitam_diameter_40.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(82, 1, 'Cemara Tretes', 'Cemara Tretes (tinggi 120 cm) adalah tanaman cemara mini yang memberikan kesan asri dan elegan, sangat cocok untuk taman dan penghias ruang luar. Harga pasaran sekitar Rp250.000.', 3, 250000.00, 5.00, 'images/produk/cemara_tretes_tinggi_120cm.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(83, 1, 'Pitalub Tinggi', 'Pitalub Tinggi 70 cm adalah tanaman hias berukuran sedang dengan daun lebat, mudah dirawat dan sesuai sebagai penghias taman, khususnya bagi pemula. Harga sekitar Rp80.000.', 5, 80000.00, 0.80, 'images/produk/pitalub_tinggi_70cm.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(84, 1, 'Ketapang Kaligata', 'Ketapang Kaligata Tinggi 60 cm adalah tanaman hias kecil dengan daun khas yang memberikan kesan asri, sangat sesuai untuk taman minimalis. Harga sekitar Rp35.000.', 10, 35000.00, 0.60, 'images/produk/ketapang_kaligata_tinggi_60cm.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(85, 1, 'Berekele', 'Berekele adalah tanaman hias yang menambah warna dan tekstur pada taman tropis maupun sebagai tanaman pagar hidup. Harga sekitar Rp15.000.', 30, 15000.00, 0.30, 'images/produk/berekele.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(86, 4, 'Media Tanah', 'Media Tanah adalah media tanam berkualitas tinggi yang mendukung pertumbuhan berbagai tanaman hias dan dapat digunakan untuk tanaman dalam pot maupun di tanah terbuka. Harga sekitar Rp15.000 per kemasan.', 100, 15000.00, 1.00, 'images/produk/media_tanah.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(87, 1, 'Jamani Cobra', 'Jamani Cobra adalah tanaman hias eksotis dengan bentuk unik dan harga tinggi, sangat cocok untuk koleksi tanaman langka. Harga pasar sekitar Rp300.000.', 3, 300000.00, 0.60, 'images/produk/jamani_cobra.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(88, 1, 'Kamboja Jepang', 'Kamboja Jepang adalah tanaman hias berbunga cantik dan harum yang sering digunakan sebagai tanaman pekarangan di daerah tropis. Harga sekitar Rp50.000.', 8, 50000.00, 1.20, 'images/produk/kamboja_jepang.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(89, 1, 'Bringin Putih', 'Bringin Putih adalah tanaman hias dengan daun putih hijau yang menawan, memberikan kesan elegan untuk taman dan halaman. Harga sekitar Rp50.000.', 6, 50000.00, 1.00, 'images/produk/bringin_putih.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(90, 1, 'Bromelian Baby Pink', 'Bromelian Baby Pink adalah bromeliad dengan bunga pink kecil yang cantik, menjadi favorit tanaman eksotis untuk dekorasi interior. Harga sekitar Rp125.000.', 5, 125000.00, 0.60, 'images/produk/bromilian_baby_pink.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(91, 1, 'Asoka India', 'Asoka India adalah tanaman berbunga kecil yang sering digunakan sebagai pagar hidup, mudah dirawat dan sesuai untuk pemula. Harga sekitar Rp10.000.', 30, 10000.00, 0.20, 'images/produk/asoka_india.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(92, 1, 'Pandan Bali', 'Pandan Bali adalah tanaman pandan beraroma khas yang digunakan sebagai tanaman hias dan bumbu dapur di daerah tropis. Harga sekitar Rp150.000.', 10, 150000.00, 5.00, 'images/produk/pandan_bali.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(93, 1, 'Lidah Mertua', 'Lidah Mertua adalah tanaman hias indoor dengan daun panjang tajam yang mudah dirawat dan sesuai untuk dekorasi meja atau rak tanaman. Harga sekitar Rp25.000.', 15, 25000.00, 0.50, 'images/produk/lidah_mertua.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(94, 1, 'Bringin Korea Micro', 'Bringin Korea Micro adalah varian kecil dari Bringin Korea yang cocok untuk koleksi bonsai dengan bentuk daun menarik dan perawatan mudah. Harga pasar sekitar Rp1.500.000.', 2, 1500000.00, 3.00, 'images/produk/bringin_korea_micro.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(95, 1, 'Marigool', 'Marigool adalah tanaman berbunga oranye cerah yang populer sebagai tanaman hias dan penangkal serangga di taman rumah. Harga sekitar Rp25.000.', 25, 25000.00, 0.20, 'images/produk/marigool.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(96, 1, 'Kaktus Koboy', 'Kaktus Koboy (tinggi 70 cm) adalah kaktus besar berbentuk unik yang tahan kering dan mudah dirawat, sangat cocok untuk dekorasi rumah. Harga sekitar Rp150.000.', 12, 150000.00, 1.20, 'images/produk/kaktus_koboy_tinggi_70cm.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(97, 1, 'Bonsai Gestrum L', 'Bonsai Gestrum Ukuran L adalah bonsai besar dengan daun Gestrum yang indah, cocok untuk koleksi eksklusif dengan perawatan khusus. Harga pasar sekitar Rp1.200.000.', 1, 1200000.00, 3.00, 'images/produk/bonsai_gestrum(L).jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(98, 1, 'Bonsai Gestrum M', 'Bonsai Gestrum Ukuran M adalah bonsai berukuran sedang dengan daun Gestrum yang cantik, memberikan kesan elegan di rumah atau kantor. Harga sekitar Rp500.000.', 2, 500000.00, 2.00, 'images/produk/bonsai_gestrum(M).jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(99, 1, 'Bonsai Cemara Udang', 'Bonsai Cemara Udang adalah bonsai cemara unik yang menyerupai udang dan merupakan tanaman koleksi menarik dengan perawatan khusus. Harga pasar sekitar Rp650.000.', 1, 650000.00, 2.00, 'images/produk/bonsai_cemara_udang.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(100, 1, 'Bunga Kertas', 'Bunga Kertas adalah tanaman hias dengan warna-warni cerah yang mudah dirawat dan cocok untuk memperindah pagar atau taman. Tanaman ini sangat sesuai bagi pemula. Harga sekitar Rp30.000.', 20, 30000.00, 0.40, 'images/produk/bunga_kertas.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(101, 1, 'Jambu Kanci', 'Jambu Kanci (tinggi 50 cm) adalah tanaman buah jambu kanci kecil yang juga dapat dijadikan tanaman hias, cocok untuk taman dan kebun rumah. Harga pasar sekitar Rp60.000.', 8, 60000.00, 1.00, 'images/produk/jambu_kanci_tinggi_50cm.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(102, 1, 'Jeruk Lemon', 'Jeruk Lemon adalah tanaman buah jeruk lemon kecil yang memberikan aroma segar dan cocok untuk taman maupun kebun rumah. Harga sekitar Rp60.000.', 7, 60000.00, 1.00, 'images/produk/jeruk_lemon.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(103, 1, 'Asoka Singapur', 'Asoka Singapur adalah tanaman berbunga kecil yang populer sebagai pagar hidup, mudah dirawat dan sesuai untuk pemula. Harga sekitar Rp25.000.', 20, 25000.00, 0.20, 'images/produk/asoka_singapur.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(104, 1, 'Sikas', 'Sikas (tinggi 70 cm) adalah tanaman sikas berukuran besar yang cocok sebagai tanaman hias eksklusif dengan perawatan khusus. Harga pasar sekitar Rp1.700.000.', 1, 1700000.00, 6.00, 'images/produk/sikas_tinggi_70cm.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(105, 1, 'Kadaka Tempel', 'Kadaka Tempel adalah tanaman hias dengan daun menarik yang mudah dirawat dan sesuai untuk taman tropis maupun sebagai tanaman pagar hidup. Harga sekitar Rp35.000.', 10, 35000.00, 0.60, 'images/produk/kadaka_tempel.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(106, 1, 'Pucuk Merah', 'Pucuk Merah (tinggi 250 cm) adalah tanaman pucuk merah tinggi yang sering digunakan sebagai pagar hidup atau dekorasi taman, memberikan warna cerah yang menarik dan menambah estetika lingkungan. Harga sekitar Rp350.000.', 4, 350000.00, 2.20, 'images/produk/pucuk_merah_tinggi_250cm.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(107, 1, 'Kana', 'Kana (Canna indica) adalah tanaman tropis dengan daun lebar hijau cerah dan bunga besar berwarna merah, kuning, atau oranye yang mencolok. Tumbuh hingga 1–2 meter, cocok untuk taman dan halaman, tahan berbagai kondisi cuaca dan mudah dirawat sehingga sesuai untuk pemula. Harga pasar sekitar Rp30.000.', 25, 30000.00, 0.60, 'images/produk/kana.jpg', 1, 1, 0, '2025-07-30 07:58:06', '2025-07-30 07:58:06');

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
(1, 54, 'images/produk/jamani_dolar.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(2, 54, 'images/produk/jamani_dolar.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(3, 55, 'images/produk/dragon_sekel_atau_tengkorak.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(4, 55, 'images/produk/dragon_sekel_atau_tengkorak.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(5, 56, 'images/produk/pakis_kuning.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(6, 56, 'images/produk/pakis_kuning.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(7, 57, 'images/produk/kuping_gajah.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(8, 57, 'images/produk/kuping_gajah.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(9, 58, 'images/produk/cemara_ekor_tupay.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(10, 58, 'images/produk/cemara_ekor_tupay.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(11, 59, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_15.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(12, 59, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_15.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(13, 60, 'images/produk/puting_cabe.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(14, 60, 'images/produk/puting_cabe.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(15, 61, 'images/produk/cemara_perak.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(16, 61, 'images/produk/cemara_perak.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(17, 62, 'images/produk/bringin_korea_tinggi_2M.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(18, 62, 'images/produk/bringin_korea_tinggi_2M.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(19, 63, 'images/produk/gestrum_kuning.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(20, 63, 'images/produk/gestrum_kuning.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(21, 64, 'images/produk/brokoli_hijau.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(22, 64, 'images/produk/brokoli_hijau.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(23, 65, 'images/produk/siklok.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(24, 65, 'images/produk/siklok.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(25, 66, 'images/produk/sampang_dara.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(26, 66, 'images/produk/sampang_dara.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(27, 68, 'images/produk/teratai.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(28, 68, 'images/produk/teratai.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(29, 69, 'images/produk/airis_brazil.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(30, 69, 'images/produk/airis_brazil.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(31, 70, 'images/produk/batu_taman_hitam_putih.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(32, 70, 'images/produk/batu_taman_hitam_putih.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(33, 71, 'images/produk/maranti_bali.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(34, 71, 'images/produk/maranti_bali.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(35, 72, 'images/produk/kadaka_tanduk.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(36, 72, 'images/produk/kadaka_tanduk.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(37, 73, 'images/produk/jayen.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(38, 73, 'images/produk/jayen.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(39, 74, 'images/produk/alamanda_kuning.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(40, 74, 'images/produk/alamanda_kuning.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(41, 75, 'images/produk/sarbena_putih.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(42, 75, 'images/produk/sarbena_putih.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(43, 76, 'images/produk/sarbena_hijau.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(44, 76, 'images/produk/sarbena_hijau.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(45, 77, 'images/produk/pitalub_kecil.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(46, 77, 'images/produk/pitalub_kecil.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(47, 78, 'images/produk/aglonema_valentin.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(48, 78, 'images/produk/aglonema_valentin.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(49, 79, 'images/produk/pot_kapsul_hitam_coklat_hitam_diameter_35_tinggi_60.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(50, 79, 'images/produk/pot_kapsul_hitam_coklat_hitam_diameter_35_tinggi_60.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(51, 80, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_30.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(52, 80, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_30.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(53, 81, 'images/produk/pot_hitam_diameter_40.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(54, 81, 'images/produk/pot_hitam_diameter_40.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(55, 82, 'images/produk/cemara_tretes_tinggi_120cm.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(56, 82, 'images/produk/cemara_tretes_tinggi_120cm.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(57, 83, 'images/produk/pitalub_tinggi_70cm.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(58, 83, 'images/produk/pitalub_tinggi_70cm.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(59, 84, 'images/produk/ketapang_kaligata_tinggi_60cm.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(60, 84, 'images/produk/ketapang_kaligata_tinggi_60cm.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(61, 85, 'images/produk/berekele.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(62, 85, 'images/produk/berekele.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(63, 86, 'images/produk/media_tanah.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(64, 86, 'images/produk/media_tanah.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(65, 87, 'images/produk/jamani_cobra.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(66, 87, 'images/produk/jamani_cobra.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(67, 88, 'images/produk/kamboja_jepang.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(68, 88, 'images/produk/kamboja_jepang.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(69, 89, 'images/produk/bringin_putih.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(70, 89, 'images/produk/bringin_putih.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(71, 90, 'images/produk/bromilian_baby_pink.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(72, 90, 'images/produk/bromilian_baby_pink.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(73, 91, 'images/produk/asoka_india.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(74, 91, 'images/produk/asoka_india.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(75, 92, 'images/produk/pandan_bali.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(76, 92, 'images/produk/pandan_bali.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(77, 93, 'images/produk/lidah_mertua.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(78, 93, 'images/produk/lidah_mertua.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(79, 94, 'images/produk/bringin_korea_micro.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(80, 94, 'images/produk/bringin_korea_micro.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(81, 95, 'images/produk/marigool.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(82, 95, 'images/produk/marigool.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(83, 96, 'images/produk/kaktus_koboy_tinggi_70cm.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(84, 96, 'images/produk/kaktus_koboy_tinggi_70cm.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(85, 97, 'images/produk/bonsai_gestrum(L).jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(86, 97, 'images/produk/bonsai_gestrum(L).png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(87, 98, 'images/produk/bonsai_gestrum(M).jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(88, 98, 'images/produk/bonsai_gestrum(M).png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(89, 99, 'images/produk/bonsai_cemara_udang.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(90, 99, 'images/produk/bonsai_cemara_udang.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(91, 100, 'images/produk/bunga_kertas.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(92, 100, 'images/produk/bunga_kertas.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(93, 101, 'images/produk/jambu_kanci_tinggi_50cm.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(94, 101, 'images/produk/jambu_kanci_tinggi_50cm.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(95, 102, 'images/produk/jeruk_lemon.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(96, 102, 'images/produk/jeruk_lemon.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(97, 103, 'images/produk/asoka_singapur.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(98, 103, 'images/produk/asoka_singapur.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(99, 104, 'images/produk/sikas_tinggi_70cm.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(100, 104, 'images/produk/sikas_tinggi_70cm.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(101, 105, 'images/produk/kadaka_tempel.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(102, 105, 'images/produk/kadaka_tempel.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(103, 106, 'images/produk/pucuk_merah_tinggi_250cm.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(104, 106, 'images/produk/pucuk_merah_tinggi_250cm.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(105, 107, 'images/produk/kana.jpg', 1, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(106, 107, 'images/produk/tanaman_kana.png', 0, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06');

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

INSERT INTO `promotions` (`id`, `promo_code`, `title`, `description`, `discount_type`, `discount_value`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `interface_id`) VALUES
(2, 'PROMOJULI10', 'Diskon 10% untuk Tanaman Hias', 'Dapatkan diskon 10% untuk pembelian minimal Rp200.000 tanaman hias.', 'percent', 10.00, '2025-07-01 00:00:00', '2025-07-31 00:00:00', 1, '2025-07-24 00:53:22', NULL, 1),
(3, 'PROMO-SV294G', 'Promo Newsletter untuk wdawdaaw02@gmail.com', 'Promo khusus subscriber newsletter.', 'percent', 10.00, '2025-07-24 11:43:32', '2025-08-23 11:43:32', 1, '2025-07-24 04:43:32', NULL, 1),
(4, 'PROMO-INY4MR', 'Promo Newsletter untuk redeemself0@gmail.com', 'Promo khusus subscriber newsletter.', 'percent', 10.00, '2025-07-30 11:39:55', '2025-08-29 11:39:55', 1, '2025-07-30 11:39:55', NULL, 1);

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
(1, 3, 'ADMIN', '2025-07-30 00:51:31', '2025-07-30 00:51:31'),
(2, 4, 'USER', '2025-07-30 00:51:31', '2025-07-30 00:51:31'),
(3, 2, 'GUEST', '2025-07-30 00:51:31', '2025-07-30 00:51:31'),
(4, 1, 'CUSTOMER', '2025-07-30 00:51:31', '2025-07-30 00:51:31'),
(5, 5, 'DEVELOPER', '2025-07-30 00:51:31', '2025-07-30 00:51:31');

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
(5, 2, '2025-07-30 01:03:22', '2025-07-30 01:03:22'),
(5, 3, '2025-07-30 01:03:22', '2025-07-30 01:03:22'),
(5, 4, '2025-07-30 01:03:22', '2025-07-30 01:03:22'),
(6, 2, '2025-07-30 01:45:14', '2025-07-30 01:45:14'),
(6, 3, '2025-07-30 01:45:14', '2025-07-30 01:45:14'),
(6, 4, '2025-07-30 01:45:14', '2025-07-30 01:45:14');

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
  `session_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 2001, 'KURIR TOKO', 'Internal', NULL, 10000.00, 'WAITING_DELIVERY', NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(2, 2002, 'KURIR TOKO', 'Internal', NULL, 15000.00, 'WAITING_DELIVERY', NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(3, 2003, 'KURIR TOKO', 'Internal', NULL, 20000.00, 'WAITING_DELIVERY', NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(4, 2004, 'GOSEND', 'Sameday', NULL, 25000.00, 'WAITING_PICKUP', NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(5, 2005, 'JNE', 'REG', NULL, 12000.00, 'WAITING_PICKUP', NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(6, 2006, 'JNT', 'EZ', NULL, 14000.00, 'WAITING_PICKUP', NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(7, 2007, 'SICEPAT', 'BEST', NULL, 15000.00, 'WAITING_PICKUP', NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(8, 2008, 'AMBIL_SENDIRI', '-', NULL, 0.00, 'READY_FOR_PICKUP', NULL, 1, '2025-07-30 07:58:06', '2025-07-30 07:58:06'),
(9, 2009, 'GOSEND', 'Sameday', NULL, 25000.00, 'WAITING_PICKUP', NULL, 1, '2025-07-30 04:28:22', '2025-07-30 04:28:22'),
(10, 2010, 'SICEPAT', 'BEST', NULL, 15000.00, 'WAITING_PICKUP', NULL, 1, '2025-07-30 04:33:49', '2025-07-30 04:33:49');

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
  `sort_order` int NOT NULL DEFAULT '0' COMMENT 'Urutan tampilan',
  `settings` json DEFAULT NULL COMMENT 'Pengaturan tambahan dalam JSON',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `code`, `name`, `service`, `cost`, `description`, `is_active`, `sort_order`, `settings`, `created_at`, `updated_at`) VALUES
(1, 'JNT', 'J&T Express', 'EZ', 14000.00, 'Pengiriman reguler via J&T Express (Rp14,000)', 1, 1, NULL, '2025-07-30 00:51:32', '2025-07-30 00:51:32'),
(2, 'GOSEND', 'GoSend', 'Sameday', 25000.00, 'Pengiriman cepat via GoSend (estimasi Rp25,000 sesuai jarak)', 1, 4, NULL, '2025-07-30 00:51:32', '2025-07-30 00:51:32'),
(3, 'JNE', 'JNE', 'REG', 12000.00, 'Pengiriman reguler via JNE (Rp12,000)', 1, 2, NULL, '2025-07-30 00:51:32', '2025-07-30 00:51:32'),
(4, 'SICEPAT', 'SiCepat', 'BEST', 15000.00, 'Pengiriman reguler via SiCepat (Rp15,000)', 1, 3, NULL, '2025-07-30 00:51:32', '2025-07-30 00:51:32'),
(5, 'KURIR_TOKO_DEKAT', 'Kurir Toko (<5km)', 'Internal-Dekat', 10000.00, 'Pengiriman langsung dari toko Azka Garden (jarak <5km)', 1, 5, '{\"max_distance\": 5, \"distance_range\": \"less_than_5km\"}', '2025-07-30 00:51:32', '2025-07-30 00:51:32'),
(6, 'KURIR_TOKO', 'Kurir Toko (5-10km)', 'Internal', 15000.00, 'Pengiriman langsung dari toko Azka Garden (jarak 5-10km)', 1, 6, '{\"max_distance\": 10, \"min_distance\": 5, \"distance_range\": \"5_to_10km\"}', '2025-07-30 00:51:32', '2025-07-30 00:51:32'),
(7, 'KURIR_TOKO_JAUH', 'Kurir Toko (>10km)', 'Internal-Jauh', 20000.00, 'Pengiriman langsung dari toko Azka Garden (jarak >10km)', 1, 7, '{\"min_distance\": 10, \"distance_range\": \"more_than_10km\"}', '2025-07-30 00:51:32', '2025-07-30 00:51:32'),
(8, 'AMBIL_SENDIRI', 'Ambil Sendiri', '-', 0.00, 'Ambil langsung di toko (GRATIS)', 1, 0, NULL, '2025-07-30 00:51:32', '2025-07-30 00:51:32');

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
(1, 'Admin User', 'admin@azkagarden.com', '2025-07-30 00:51:32', '$2y$12$l5rgIqK3X8FdFBZHWaCcH.Wnle.ZC257uYtpvIh/1U2CMgPlweeMq', NULL, NULL, NULL, 1, 'hBNGwYHOoq', '2025-07-30 00:51:32', '2025-07-30 00:51:32'),
(2, 'Lisandro Hyatt IV', 'lonnie.kilback@example.net', '2025-07-30 00:51:33', '$2y$12$fXUW9l0dyoMVvIr3/wGpnO2xpQeEYJQPkO3e4HKk.2KiRYjYDWhr6', NULL, '1-813-708-4070', NULL, 1, 'pyQ2ff03bl', '2025-07-30 00:51:33', '2025-07-30 00:51:33'),
(3, 'Prof. Irwin Stroman', 'elang@example.com', '2025-07-30 00:51:33', '$2y$12$d1bZQutXb6InQ2ChoyZ/Tuk.heMxoZ7/KG9VwJPpxYFOrWk.0FHC.', NULL, '1-219-604-6203', NULL, 1, 'D9D7qcAj8l', '2025-07-30 00:51:33', '2025-07-30 00:51:33'),
(4, 'Jazmyn Schulist', 'vincenzo.kunze@example.net', '2025-07-30 00:51:34', '$2y$12$50WRd0QpxFo7LV3couTXbezDSl3p0LYlQh/SvrzTN6S6/AGU4AHTS', NULL, '(680) 330-9635', NULL, 1, '28CH70nl6S', '2025-07-30 00:51:34', '2025-07-30 00:51:34'),
(5, 'Robee', 'redeemself0@gmail.com', NULL, '$2y$12$w7yEa/fo/ltquzKvvYeIJ.aUfwviGgj47f.Ye24Q56SJv7Dozb2k.', 'Robee2025', '081281349115', '2025-07-30 07:57:51', 1, NULL, '2025-07-30 00:57:51', '2025-07-30 01:03:22'),
(6, 'Roberto Ocaviantyo Tahta Laksmana', 'Robee@gmail.com', NULL, '$2y$12$4u6oZgOJdUrP8jaQBHfY3OjWobZhWXUFteOnJQRKw/CcGRl2Eg31S', 'Roberto2025', '088212121221', '2025-07-30 08:44:44', 1, NULL, '2025-07-30 01:44:44', '2025-07-30 01:45:14');

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
  ADD KEY `promotions_interface_id_foreign` (`interface_id`);

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
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `sessions_user_id_foreign` (`user_id`);

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
  ADD KEY `shipping_methods_is_active_sort_order_index` (`is_active`,`sort_order`),
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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4326;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2011;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

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
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shippings`
--
ALTER TABLE `shippings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
