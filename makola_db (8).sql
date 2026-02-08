-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2026 at 11:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `makola_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link_url` varchar(500) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT 'Shop Now',
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `description`, `image`, `link_url`, `button_text`, `display_order`, `is_active`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 'Welcome to Makola', NULL, NULL, 'hero banner.png', 'index.php?page=home', 'Shop Now', 1, 1, '2026-01-18 18:58:20', NULL, '2026-01-18 18:58:20', '2026-01-18 19:51:30'),
(2, 'Welcome to Makola', NULL, NULL, 'hero banner 2.png', 'index.php?page=home', 'Shop Now', 1, 1, '2026-01-18 18:58:20', NULL, '2026-01-18 18:58:20', '2026-01-18 19:51:30'),
(3, 'Shop Makola', 'Your Premier Marketplace', 'Discover amazing products from trusted sellers', 'banner-video.mp4', 'index.php', 'Explore Now', 4, 1, '2026-01-18 20:24:32', NULL, '2026-01-18 20:24:32', '2026-01-18 20:24:32');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `created_at`) VALUES
(1, 'Electronics', 'electronics', 'Electronic devices and gadgets', NULL, '2025-12-12 00:53:50'),
(2, 'Fashion', 'fashion', 'Clothing and accessories', NULL, '2025-12-12 00:53:50'),
(3, 'Home & Garden', 'home-garden', 'Home improvement and garden supplies', NULL, '2025-12-12 00:53:50'),
(4, 'Sports & Outdoors', 'sports-outdoors', 'Sports equipment and outdoor gear', NULL, '2025-12-12 00:53:50'),
(5, 'Books', 'books', 'Books and literature', NULL, '2025-12-12 00:53:50'),
(6, 'Toys & Games', 'toys-games', 'Toys and games for all ages', NULL, '2025-12-12 00:53:50');

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `commission_amount` decimal(10,2) NOT NULL,
  `seller_earnings` decimal(10,2) NOT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commissions`
--

INSERT INTO `commissions` (`id`, `order_item_id`, `seller_id`, `order_id`, `commission_amount`, `seller_earnings`, `status`, `created_at`) VALUES
(1, 1, 3, 1, 0.50, 4.50, 'pending', '2025-12-17 10:14:46'),
(3, 3, 3, 3, 0.20, 1.80, 'pending', '2026-01-06 14:49:59'),
(4, 4, 3, 4, 0.20, 1.80, 'pending', '2026-01-06 16:06:12'),
(7, 7, 3, 7, 0.50, 4.50, 'pending', '2026-01-18 21:54:03'),
(8, 8, 3, 8, 0.20, 1.80, 'pending', '2026-01-23 00:50:07'),
(9, 9, 3, 9, 0.20, 1.80, 'pending', '2026-01-27 16:52:36'),
(10, 10, 3, 10, 0.20, 1.80, 'pending', '2026-01-27 16:54:33'),
(11, 11, 13, 11, 0.10, 0.90, 'pending', '2026-01-27 17:50:23');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_method` varchar(50) DEFAULT 'standard',
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `tracking_number` varchar(100) DEFAULT NULL,
  `shipping_carrier` varchar(50) DEFAULT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `actual_delivery` timestamp NULL DEFAULT NULL,
  `delivery_confirmed` tinyint(1) DEFAULT 0,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `payment_reference` varchar(255) DEFAULT NULL,
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `order_number`, `total_amount`, `shipping_address`, `shipping_method`, `shipping_cost`, `tracking_number`, `shipping_carrier`, `estimated_delivery`, `actual_delivery`, `delivery_confirmed`, `latitude`, `longitude`, `payment_status`, `payment_reference`, `order_status`, `created_at`, `updated_at`) VALUES
(1, 2, 'ORD-20251217-6942829603DC6', 5.00, 'Lat: 5.613158, Lng: -0.196608, Accra, Ghana', 'standard', 0.00, NULL, NULL, NULL, NULL, 0, 5.61315840, -0.19660800, 'paid', 'PAY-ORD-20251217-6942829603DC6-1765966486-7361', 'delivered', '2025-12-17 10:14:46', '2025-12-17 10:57:39'),
(3, 2, 'ORD-20260106-695D21170913A', 2.00, 'mile 11, Kumasi, Ghana', 'standard', 0.00, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'PAY-ORD-20260106-695D21170913A-1767710999-1920', 'delivered', '2026-01-06 14:49:59', '2026-01-06 16:02:42'),
(4, 2, 'ORD-20260106-695D32F4BEFCF', 2.00, 'mapw, Ho, Ghana', 'standard', 0.00, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'PAY-ORD-20260106-695D32F4BEFCF-1767715572-5099', 'shipped', '2026-01-06 16:06:12', '2026-01-06 16:15:45'),
(7, 2, 'ORD-20260118-696D567BDE79D', 50.00, 'Bortianor , Sekondi, Ghana', 'standard', 0.00, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'PAY-ORD-20260118-696D567BDE79D-1768773243-6298', 'pending', '2026-01-18 21:54:03', '2026-01-18 21:54:03'),
(8, 2, 'ORD-20260123-6972C5BFB7872', 27.00, 'mile 11, Accra, Ghana', 'standard', 0.00, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'PAY-ORD-20260123-6972C5BFB7872-1769129407-4081', 'pending', '2026-01-23 00:50:07', '2026-01-23 00:50:07'),
(9, 12, 'ORD-20260127-6978ED54E31A4', 47.00, 'Adenta, Accra, Ghana', 'standard', 0.00, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'pending', 'PAY-ORD-20260127-6978ED54E31A4-1769532757-9658', 'pending', '2026-01-27 16:52:36', '2026-01-27 16:52:37'),
(10, 12, 'ORD-20260127-6978EDC972D8E', 2.00, 'Adenta, Accra, Ghana', 'standard', 0.00, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'PAY-ORD-20260127-6978EDC972D8E-1769532873-5483', 'delivered', '2026-01-27 16:54:33', '2026-01-31 09:37:51'),
(11, 2, 'ORD-20260127-6978FADF9EACA', 1.00, 'Adenta, Accra, Ghana', 'standard', 0.00, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'paid', 'PAY-ORD-20260127-6978FADF9EACA-1769536223-6306', 'delivered', '2026-01-27 17:50:23', '2026-01-31 09:37:48');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `commission_rate` decimal(5,2) DEFAULT 10.00,
  `commission_amount` decimal(10,2) NOT NULL,
  `seller_earnings` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `seller_id`, `quantity`, `price`, `subtotal`, `commission_rate`, `commission_amount`, `seller_earnings`, `created_at`) VALUES
(1, 1, 1, 3, 1, 5.00, 5.00, 10.00, 0.50, 4.50, '2025-12-17 10:14:46'),
(3, 3, 7, 3, 1, 2.00, 2.00, 10.00, 0.20, 1.80, '2026-01-06 14:49:59'),
(4, 4, 7, 3, 1, 2.00, 2.00, 10.00, 0.20, 1.80, '2026-01-06 16:06:12'),
(7, 7, 1, 3, 1, 5.00, 5.00, 10.00, 0.50, 4.50, '2026-01-18 21:54:03'),
(8, 8, 7, 3, 1, 2.00, 2.00, 10.00, 0.20, 1.80, '2026-01-23 00:50:07'),
(9, 9, 7, 3, 1, 2.00, 2.00, 10.00, 0.20, 1.80, '2026-01-27 16:52:36'),
(10, 10, 7, 3, 1, 2.00, 2.00, 10.00, 0.20, 1.80, '2026-01-27 16:54:33'),
(11, 11, 10, 13, 1, 1.00, 1.00, 10.00, 0.10, 0.90, '2026-01-27 17:50:23');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `method_type` enum('card','mobile_money') DEFAULT 'card',
  `provider` varchar(50) DEFAULT NULL,
  `last_four` varchar(4) DEFAULT NULL,
  `card_holder_name` varchar(100) DEFAULT NULL,
  `expiry_month` tinyint(4) DEFAULT NULL,
  `expiry_year` smallint(6) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `weight` decimal(5,2) DEFAULT 0.00,
  `length` decimal(5,2) DEFAULT 0.00,
  `width` decimal(5,2) DEFAULT 0.00,
  `height` decimal(5,2) DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `is_featured` tinyint(1) DEFAULT 0,
  `is_deal` tinyint(1) DEFAULT 0,
  `deal_price` decimal(10,2) DEFAULT NULL,
  `deal_end_date` datetime DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `sales_count` int(11) DEFAULT 0,
  `average_rating` decimal(3,2) DEFAULT 0.00,
  `review_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `seller_id`, `category_id`, `subcategory_id`, `title`, `slug`, `description`, `price`, `stock`, `weight`, `length`, `width`, `height`, `image`, `status`, `is_featured`, `is_deal`, `deal_price`, `deal_end_date`, `views`, `sales_count`, `average_rating`, `review_count`, `created_at`, `updated_at`) VALUES
(1, 3, 2, NULL, 'Gucci Men’s Leather Loafers', 'gucci-men-s-leather-loafers', '0', 5.00, 27, 0.00, 0.00, 0.00, 0.00, '693b6c47d2df7.jpg', 'approved', 0, 0, NULL, NULL, 37, 0, 0.00, 0, '2025-12-12 01:13:43', '2026-01-18 21:54:03'),
(3, 5, 1, 7, 'Microsoft Surface Laptop Go 3', 'microsoft-surface-laptop-go-3', '0', 598.26, 2, 0.00, 0.00, 0.00, 0.00, '693b81d8a62d8_0.jpg', 'approved', 0, 0, NULL, NULL, 13, 0, 0.00, 0, '2025-12-12 02:45:44', '2026-01-31 09:16:47'),
(5, 3, 2, 1, 'Men loafers', 'men-loafers', '0', 10.00, 5, 0.00, 0.00, 0.00, 0.00, '694294650f9ea_0.jpg', 'approved', 0, 0, NULL, NULL, 9, 0, 0.00, 0, '2025-12-17 11:30:45', '2026-01-31 08:33:48'),
(6, 5, 2, 1, 'Boys Cable Knit Sweater Vest', 'boys-cable-knit-sweater-vest', 'nice and comfy', 10.00, 12, 0.00, 0.00, 0.00, 0.00, '6944231a814e0_0.jpg', 'approved', 0, 0, NULL, NULL, 9, 0, 0.00, 0, '2025-12-18 15:51:54', '2026-01-06 17:20:43'),
(7, 3, 2, 1, 'Fashion Loafers', 'fashion-loafers', '0', 2.00, 5, 0.00, 0.00, 0.00, 0.00, '695d1f15e802b_0.jpg', 'approved', 0, 0, NULL, NULL, 9, 0, 0.00, 0, '2026-01-06 14:41:25', '2026-01-31 10:10:26'),
(8, 3, 3, 11, 'White Mordent Fort Modern Contemporary Sofa Couch', 'white-mordent-fort-modern-contemporary-sofa-couch', 'This sofa chair is designed to provide comfortable seating for living rooms, bedrooms, offices, and lounges. Made with a sturdy frame and soft cushioning, it offers reliable support for everyday use while maintaining a modern and simple appearance.\r\n\r\nFeatures:\r\n\r\nErgonomic backrest for comfortable sitting\r\n\r\nSoft, high-density foam cushion\r\n\r\nStrong and durable frame construction\r\n\r\nSmooth fabric upholstery, skin-friendly and breathable\r\n\r\nStable legs with anti-slip design\r\n\r\nSuitable for living room, bedroom, office, or waiting area\r\n\r\nSpecifications:\r\n\r\nMaterial: Fabric + high-density foam + solid frame\r\n\r\nStyle: Modern / Minimalist\r\n\r\nSeating Capacity: 1 person\r\n\r\nColor Options: Multiple colors available\r\n\r\nAssembly: Easy assembly required\r\n\r\nPackage Includes:\r\n\r\n1 × Sofa Chair\r\n\r\nAssembly accessories\r\n\r\nUser manual\r\n\r\nNotes:\r\n\r\nPlease allow slight measurement differences due to manual measurement\r\n\r\nActual color may vary slightly due to lighting and screen settings', 400.00, 10, 0.00, 0.00, 0.00, 0.00, '697dc3aa85171_1769849770.jpg', 'approved', 0, 0, NULL, NULL, 8, 0, 0.00, 0, '2026-01-18 22:11:39', '2026-01-31 09:18:05'),
(9, 3, 3, 11, 'Corvus Aosta Tufted Velvet Loveseat and Sofa', 'corvus-aosta-tufted-velvet-loveseat-and-sofa', '0', 500.00, 100, 0.00, 0.00, 0.00, 0.00, '696d5cdf994ed_0.jpg', 'approved', 0, 0, NULL, NULL, 16, 0, 5.00, 1, '2026-01-18 22:21:19', '2026-01-31 09:17:36'),
(10, 13, 5, 23, 'KG1 Books', 'kg1-books', '0', 1.00, 199, 0.00, 0.00, 0.00, 0.00, '6978f258c38dd_0.jpg', 'approved', 0, 0, NULL, NULL, 4, 0, 0.00, 0, '2026-01-27 17:14:00', '2026-01-31 08:57:08'),
(11, 3, 2, 1, 'Boyfriend Style Men\'s Casual Jacket', 'boyfriend-style-men-s-casual-jacket', 'Boyfriend Style Men\'s Casual Jacket And Pants Set, Autumn Sporty Tracksuit For Young Fashionable Boyfriend Style Men', 10.00, 30, 0.00, 0.00, 0.00, 0.00, '697dcbffdfbeb_0.jpg', 'approved', 0, 0, NULL, NULL, 4, 0, 0.00, 0, '2026-01-31 09:31:43', '2026-01-31 10:04:30');

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_attributes`
--

INSERT INTO `product_attributes` (`id`, `product_id`, `name`, `created_at`) VALUES
(2, 5, 'size', '2025-12-17 11:30:45'),
(3, 5, 'color', '2025-12-17 11:30:45'),
(4, 6, 'size', '2025-12-18 15:51:54'),
(5, 6, 'color', '2025-12-18 15:51:54'),
(6, 7, 'size', '2026-01-06 14:41:25'),
(7, 7, 'color', '2026-01-06 14:41:25'),
(8, 8, 'lenght', '2026-01-18 22:11:39'),
(9, 8, 'color', '2026-01-18 22:11:39'),
(10, 9, 'lenght', '2026-01-18 22:21:19'),
(11, 9, 'color', '2026-01-18 22:21:19'),
(12, 10, 'color', '2026-01-27 17:14:00'),
(13, 11, 'size', '2026-01-31 09:31:43'),
(14, 11, 'color', '2026-01-31 09:31:43');

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_attribute_values`
--

INSERT INTO `product_attribute_values` (`id`, `attribute_id`, `value`, `created_at`) VALUES
(4, 2, '39', '2025-12-17 11:30:45'),
(5, 2, '40', '2025-12-17 11:30:45'),
(6, 2, '41', '2025-12-17 11:30:45'),
(7, 2, '42', '2025-12-17 11:30:45'),
(8, 2, '43', '2025-12-17 11:30:45'),
(9, 2, '44', '2025-12-17 11:30:45'),
(10, 2, '45', '2025-12-17 11:30:45'),
(11, 3, 'Black', '2025-12-17 11:30:45'),
(12, 3, 'Red', '2025-12-17 11:30:45'),
(13, 3, 'white', '2025-12-17 11:30:45'),
(14, 3, 'Brown', '2025-12-17 11:30:45'),
(15, 4, 'S', '2025-12-18 15:51:54'),
(16, 4, 'M', '2025-12-18 15:51:54'),
(17, 4, 'L', '2025-12-18 15:51:54'),
(18, 4, 'XL', '2025-12-18 15:51:54'),
(19, 5, 'Black', '2025-12-18 15:51:54'),
(20, 5, 'white', '2025-12-18 15:51:54'),
(21, 5, 'Brown', '2025-12-18 15:51:54'),
(22, 6, '39', '2026-01-06 14:41:25'),
(23, 6, '40', '2026-01-06 14:41:25'),
(24, 6, '41', '2026-01-06 14:41:25'),
(25, 6, '42', '2026-01-06 14:41:25'),
(26, 6, '43', '2026-01-06 14:41:25'),
(27, 6, '44', '2026-01-06 14:41:25'),
(28, 6, '45', '2026-01-06 14:41:25'),
(29, 7, 'Black', '2026-01-06 14:41:25'),
(30, 8, '30cm', '2026-01-18 22:11:39'),
(31, 9, 'white', '2026-01-18 22:11:39'),
(32, 10, '30cm', '2026-01-18 22:21:19'),
(33, 11, 'velvet', '2026-01-18 22:21:19'),
(34, 12, 'Black', '2026-01-27 17:14:00'),
(35, 12, 'Red', '2026-01-27 17:14:00'),
(36, 12, 'white', '2026-01-27 17:14:00'),
(37, 12, 'Brown', '2026-01-27 17:14:00'),
(38, 13, 'S', '2026-01-31 09:31:43'),
(39, 13, 'M', '2026-01-31 09:31:43'),
(40, 13, 'L', '2026-01-31 09:31:43'),
(41, 13, 'XL', '2026-01-31 09:31:43'),
(42, 14, 'Blue-Black', '2026-01-31 09:31:43'),
(43, 14, 'Orange', '2026-01-31 09:31:43'),
(44, 14, 'white', '2026-01-31 09:31:43'),
(45, 14, 'Green', '2026-01-31 09:31:43');

-- --------------------------------------------------------

--
-- Table structure for table `product_comparisons`
--

CREATE TABLE `product_comparisons` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`product_ids`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `display_order`, `created_at`) VALUES
(1, 3, '693b81d8a62d8_0.jpg', 0, '2025-12-12 02:45:44'),
(3, 5, '694294650f9ea_0.jpg', 0, '2025-12-17 11:30:45'),
(4, 6, '6944231a814e0_0.jpg', 0, '2025-12-18 15:51:54'),
(5, 6, '6944231a81868_1.jpg', 1, '2025-12-18 15:51:54'),
(6, 6, '6944231a81b1e_2.jpg', 2, '2025-12-18 15:51:54'),
(7, 7, '695d1f15e802b_0.jpg', 0, '2026-01-06 14:41:25'),
(8, 8, '696d5a9bbdf8a_0.jpg', 0, '2026-01-18 22:11:39'),
(9, 9, '696d5cdf994ed_0.jpg', 0, '2026-01-18 22:21:19'),
(10, 10, '6978f258c38dd_0.jpg', 0, '2026-01-27 17:14:00'),
(11, 11, '697dcbffdfbeb_0.jpg', 0, '2026-01-31 09:31:43'),
(12, 11, '697dcbffe0ef5_1.jpg', 1, '2026-01-31 09:31:43'),
(13, 11, '697dcbffe1072_2.jpg', 2, '2026-01-31 09:31:43');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `title` varchar(255) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `verified_purchase` tinyint(1) DEFAULT 0,
  `helpful_count` int(11) DEFAULT 0,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `user_id`, `order_id`, `rating`, `title`, `review_text`, `verified_purchase`, `helpful_count`, `status`, `created_at`, `updated_at`) VALUES
(6, 9, 2, NULL, 5, 'good', 'nice and comfortable', 0, 1, 'approved', '2026-01-31 06:57:43', '2026-01-31 06:58:07');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_values`
--

CREATE TABLE `product_variant_values` (
  `id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recently_viewed`
--

CREATE TABLE `recently_viewed` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recently_viewed`
--

INSERT INTO `recently_viewed` (`id`, `user_id`, `product_id`, `viewed_at`) VALUES
(12, 3, 9, '2026-01-31 09:04:23'),
(13, 2, 9, '2026-01-31 08:37:30'),
(17, 2, 8, '2026-01-30 14:20:23'),
(18, 2, 10, '2026-01-31 07:01:16'),
(19, 3, 8, '2026-01-31 08:56:34'),
(20, 3, 10, '2026-01-31 08:57:08'),
(21, 3, 3, '2026-01-31 09:07:15'),
(22, 12, 3, '2026-01-31 09:16:47'),
(23, 12, 9, '2026-01-31 09:17:36'),
(24, 12, 8, '2026-01-31 09:18:05'),
(25, 3, 11, '2026-01-31 09:36:28'),
(26, 4, 11, '2026-01-31 09:38:12'),
(27, 2, 11, '2026-01-31 10:04:30'),
(28, 2, 7, '2026-01-31 10:10:26');

-- --------------------------------------------------------

--
-- Table structure for table `review_helpful`
--

CREATE TABLE `review_helpful` (
  `id` int(11) NOT NULL,
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_helpful` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review_helpful`
--

INSERT INTO `review_helpful` (`id`, `review_id`, `user_id`, `is_helpful`, `created_at`) VALUES
(1, 6, 2, 1, '2026-01-31 06:58:07');

-- --------------------------------------------------------

--
-- Table structure for table `seller_payout_info`
--

CREATE TABLE `seller_payout_info` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `payout_method` enum('paystack','bank','mobile_money') DEFAULT 'paystack',
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(100) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `mobile_money_provider` varchar(50) DEFAULT NULL,
  `mobile_money_number` varchar(20) DEFAULT NULL,
  `paystack_recipient_code` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller_payout_info`
--

INSERT INTO `seller_payout_info` (`id`, `seller_id`, `payout_method`, `bank_name`, `account_number`, `account_name`, `mobile_money_provider`, `mobile_money_number`, `paystack_recipient_code`, `created_at`, `updated_at`) VALUES
(1, 3, 'mobile_money', '', '', '', 'MTN', '0538510162', '', '2025-12-17 10:19:18', '2025-12-17 10:19:18');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_rates`
--

CREATE TABLE `shipping_rates` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `region` varchar(100) NOT NULL,
  `weight_from` decimal(5,2) DEFAULT 0.00,
  `weight_to` decimal(5,2) DEFAULT 999.99,
  `shipping_method` varchar(50) DEFAULT 'standard',
  `rate` decimal(10,2) NOT NULL,
  `estimated_days` int(11) DEFAULT 3,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_rates`
--

INSERT INTO `shipping_rates` (`id`, `seller_id`, `region`, `weight_from`, `weight_to`, `shipping_method`, `rate`, `estimated_days`, `is_active`, `created_at`, `updated_at`) VALUES
(28, 3, 'Greater Accra', 0.00, 1.00, 'standard', 15.00, 2, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(29, 3, 'Greater Accra', 1.01, 5.00, 'standard', 25.00, 2, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(30, 3, 'Greater Accra', 5.01, 10.00, 'standard', 35.00, 3, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(31, 3, 'Greater Accra', 0.00, 5.00, 'express', 45.00, 1, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(32, 3, 'Greater Accra', 0.00, 10.00, 'yango', 25.00, 1, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(33, 3, 'Ashanti Region', 0.00, 1.00, 'standard', 20.00, 3, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(34, 3, 'Ashanti Region', 1.01, 5.00, 'standard', 35.00, 3, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(35, 3, 'Ashanti Region', 5.01, 10.00, 'standard', 50.00, 4, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(36, 3, 'Ashanti Region', 0.00, 5.00, 'express', 65.00, 2, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(37, 3, 'Ashanti Region', 0.00, 10.00, 'yango', 35.00, 1, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(38, 3, 'Other Regions', 0.00, 1.00, 'standard', 25.00, 4, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(39, 3, 'Other Regions', 1.01, 5.00, 'standard', 45.00, 4, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(41, 3, 'Other Regions', 0.00, 5.00, 'express', 85.00, 3, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32'),
(42, 3, 'Other Regions', 0.00, 10.00, 'yango', 45.00, 1, 1, '2026-01-06 16:57:32', '2026-01-06 16:57:32');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_tracking`
--

CREATE TABLE `shipping_tracking` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `carrier` varchar(50) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`, `slug`, `description`, `created_at`) VALUES
(1, 2, 'Men\'s Wear', 'mens-wear', 'Men\'s clothing and apparel', '2025-12-12 01:58:16'),
(2, 2, 'Women\'s Wear', 'womens-wear', 'Women\'s clothing and apparel', '2025-12-12 01:58:16'),
(3, 2, 'Jewelries', 'jewelries', 'Jewelry and accessories', '2025-12-12 01:58:16'),
(4, 2, 'Shoes & Bags', 'shoes-bags', 'Footwear and bags', '2025-12-12 01:58:16'),
(5, 2, 'Others', 'fashion-others', 'Other fashion items', '2025-12-12 01:58:16'),
(6, 1, 'Mobile Phones', 'mobile-phones', 'Smartphones and mobile devices', '2025-12-12 01:58:16'),
(7, 1, 'Computers & Laptops', 'computers-laptops', 'Computers, laptops and accessories', '2025-12-12 01:58:16'),
(8, 1, 'TV & Audio', 'tv-audio', 'Televisions and audio equipment', '2025-12-12 01:58:16'),
(9, 1, 'Cameras', 'cameras', 'Cameras and photography equipment', '2025-12-12 01:58:16'),
(10, 1, 'Others', 'electronics-others', 'Other electronic devices', '2025-12-12 01:58:16'),
(11, 3, 'Furniture', 'furniture', 'Home furniture', '2025-12-12 01:58:16'),
(12, 3, 'Kitchen & Dining', 'kitchen-dining', 'Kitchen and dining items', '2025-12-12 01:58:16'),
(13, 3, 'Home Decor', 'home-decor', 'Home decoration items', '2025-12-12 01:58:16'),
(14, 3, 'Garden Tools', 'garden-tools', 'Garden and outdoor tools', '2025-12-12 01:58:16'),
(15, 3, 'Others', 'home-garden-others', 'Other home and garden items', '2025-12-12 01:58:16'),
(16, 4, 'Fitness Equipment', 'fitness-equipment', 'Fitness and exercise equipment', '2025-12-12 01:58:16'),
(17, 4, 'Outdoor Gear', 'outdoor-gear', 'Camping and outdoor gear', '2025-12-12 01:58:16'),
(18, 4, 'Sports Apparel', 'sports-apparel', 'Sports clothing and apparel', '2025-12-12 01:58:16'),
(19, 4, 'Water Sports', 'water-sports', 'Water sports equipment', '2025-12-12 01:58:16'),
(20, 4, 'Others', 'sports-others', 'Other sports and outdoor items', '2025-12-12 01:58:16'),
(21, 5, 'Fiction', 'fiction', 'Fiction books', '2025-12-12 01:58:16'),
(22, 5, 'Non-Fiction', 'non-fiction', 'Non-fiction books', '2025-12-12 01:58:16'),
(23, 5, 'Educational', 'educational', 'Educational and textbooks', '2025-12-12 01:58:16'),
(24, 5, 'Children\'s Books', 'childrens-books', 'Books for children', '2025-12-12 01:58:16'),
(25, 5, 'Others', 'books-others', 'Other books', '2025-12-12 01:58:16'),
(26, 6, 'Action Figures', 'action-figures', 'Action figures and collectibles', '2025-12-12 01:58:16'),
(27, 6, 'Board Games', 'board-games', 'Board games and puzzles', '2025-12-12 01:58:16'),
(28, 6, 'Educational Toys', 'educational-toys', 'Educational and learning toys', '2025-12-12 01:58:16'),
(29, 6, 'Video Games', 'video-games', 'Video games and consoles', '2025-12-12 01:58:16'),
(30, 6, 'Others', 'toys-games-others', 'Other toys and games', '2025-12-12 01:58:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `role` enum('buyer','seller','admin') DEFAULT 'buyer',
  `seller_verified` tinyint(1) DEFAULT 0,
  `google_id` varchar(255) DEFAULT NULL,
  `google_email` varchar(255) DEFAULT NULL,
  `auth_provider` enum('local','google') DEFAULT 'local',
  `profile_picture` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `address`, `avatar`, `profile_image`, `role`, `seller_verified`, `google_id`, `google_email`, `auth_provider`, `profile_picture`, `created_at`, `updated_at`) VALUES
(2, 'Amoako Charles', 'charlesamoako020@gmail.com', '$2y$10$aaDjQTjVNIqwfKkRYjBR7.jdJS0fu7o4g5cEY0dDa1s2MGZXDceyi', 'Amoako Charles', '0206441490', 'Finney hospital, mile 11', 'user_2_1766068661.jpg', 'avatar_2_697daa6dd0fad.png', 'buyer', 1, NULL, NULL, 'local', NULL, '2025-12-12 01:03:23', '2026-01-31 07:32:53'),
(3, 'Esther Amoako', 'nba394444@gmail.com', '$2y$10$CjLU4/vRxqGbT4/CqE4RbOq.lRP085NOLpTzCPA63tiJlK9BK7dl.', 'Esther Amoako', '0538510162', '', NULL, 'user_3_1765967726.jpg', 'seller', 1, NULL, NULL, 'local', NULL, '2025-12-12 01:08:40', '2025-12-17 10:35:26'),
(4, 'admin', 'admin@makola.com', '$2y$10$cmTWIHxmLxrP77NRSuNDPOE0QRCtqTO1ZxTs/j1XhrrTmeRVjLRTu', 'Administrator', NULL, NULL, NULL, NULL, 'admin', 1, NULL, NULL, 'local', NULL, '2025-12-12 01:21:00', '2025-12-12 01:24:40'),
(5, 'kanta55', 'truetechitsolutions982@gmail.com', '$2y$10$JeZ350Q9EuClaGpi.agO5.J4mJXp/v/ecKHi6O5gfqNpOaEC8Cr2W', 'Kanta Wan', '0555028599', NULL, NULL, NULL, 'seller', 1, NULL, NULL, 'local', NULL, '2025-12-12 02:18:37', '2025-12-12 02:27:05'),
(11, '', 'amoakocharles5522@gmail.com', '', 'Amoako Charles', NULL, NULL, NULL, NULL, 'buyer', 0, '115339070110042227823', NULL, 'google', 'https://lh3.googleusercontent.com/a/ACg8ocL-RC3J1wkhWmSs5epSv_ES3DVDYZMP3S7bWpshJq1YkAuOC3M=s96-c', '2026-01-23 00:27:16', '2026-01-23 00:27:16'),
(12, 'Mykill', 'michaelkwesi@gmail.com', '$2y$10$0u7HrIKWf6KPFEWClu4./enrLLY6OZNU9DuPpx6sRciZ1rfszhJ1O', 'Michael Kwesi', '', NULL, NULL, NULL, 'buyer', 1, NULL, NULL, 'local', NULL, '2026-01-27 16:47:16', '2026-01-27 16:47:16'),
(13, 'Makarios Boy', 'makariousfocus3@gmail.com', '$2y$10$oTwzWewf.fTl0Q4IXkvbDOKcYyEWhxS7gCc5UYENjcVjm7PSh8h2C', 'Makarios Willison', '', NULL, NULL, NULL, 'seller', 1, NULL, NULL, 'local', NULL, '2026-01-27 17:03:41', '2026-01-27 17:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_type` enum('home','work','other') DEFAULT 'home',
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'Ghana',
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `address_type`, `full_name`, `phone`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `is_default`, `created_at`, `updated_at`) VALUES
(4, 2, 'home', 'Amoako Charles', '0206441490', 'New Bortianor', '', 'Accra', 'Greater Accra', '35004', 'Ghana', 1, '2026-01-31 08:17:56', '2026-01-31 08:18:22');

-- --------------------------------------------------------

--
-- Table structure for table `user_payment_methods`
--

CREATE TABLE `user_payment_methods` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('card','mobile_money') NOT NULL,
  `card_number` varchar(20) DEFAULT NULL,
  `card_holder` varchar(255) DEFAULT NULL,
  `expiry_month` int(11) DEFAULT NULL,
  `expiry_year` int(11) DEFAULT NULL,
  `mobile_money_number` varchar(20) DEFAULT NULL,
  `mobile_money_provider` varchar(50) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_payment_methods`
--

INSERT INTO `user_payment_methods` (`id`, `user_id`, `type`, `card_number`, `card_holder`, `expiry_month`, `expiry_year`, `mobile_money_number`, `mobile_money_provider`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 2, 'mobile_money', NULL, NULL, NULL, NULL, '0538510162', 'MTN', 0, '2026-01-31 07:28:14', '2026-01-31 07:28:14');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(13, 12, 10, '2026-01-27 17:18:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_order` (`display_order`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_id` (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_seller` (`seller_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_buyer` (`buyer_id`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_location` (`latitude`,`longitude`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_seller` (`seller_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_default` (`user_id`,`is_default`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_seller` (`seller_id`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_subcategory` (`subcategory_id`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_deal` (`is_deal`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_name` (`name`);

--
-- Indexes for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_attribute` (`attribute_id`);

--
-- Indexes for table `product_comparisons`
--
ALTER TABLE `product_comparisons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_review` (`product_id`,`user_id`,`order_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_rating` (`rating`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_sku` (`sku`);

--
-- Indexes for table `product_variant_values`
--
ALTER TABLE `product_variant_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_variant_attribute` (`variant_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`),
  ADD KEY `value_id` (`value_id`);

--
-- Indexes for table `recently_viewed`
--
ALTER TABLE `recently_viewed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_viewed` (`user_id`,`viewed_at`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `review_helpful`
--
ALTER TABLE `review_helpful`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_helpful` (`review_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `seller_payout_info`
--
ALTER TABLE `seller_payout_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `seller_id` (`seller_id`),
  ADD KEY `idx_seller` (`seller_id`);

--
-- Indexes for table `shipping_rates`
--
ALTER TABLE `shipping_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_seller` (`seller_id`),
  ADD KEY `idx_region` (`region`),
  ADD KEY `idx_method` (`shipping_method`);

--
-- Indexes for table `shipping_tracking`
--
ALTER TABLE `shipping_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_tracking` (`tracking_number`),
  ADD KEY `idx_carrier` (`carrier`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_subcategory` (`category_id`,`slug`),
  ADD KEY `idx_category` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`),
  ADD UNIQUE KEY `google_email` (`google_email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_default` (`user_id`,`is_default`);

--
-- Indexes for table `user_payment_methods`
--
ALTER TABLE `user_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_default` (`user_id`,`is_default`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`product_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `product_comparisons`
--
ALTER TABLE `product_comparisons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variant_values`
--
ALTER TABLE `product_variant_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recently_viewed`
--
ALTER TABLE `recently_viewed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `review_helpful`
--
ALTER TABLE `review_helpful`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `seller_payout_info`
--
ALTER TABLE `seller_payout_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shipping_rates`
--
ALTER TABLE `shipping_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `shipping_tracking`
--
ALTER TABLE `shipping_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_payment_methods`
--
ALTER TABLE `user_payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_ibfk_1` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commissions_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `commissions_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD CONSTRAINT `product_attributes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_comparisons`
--
ALTER TABLE `product_comparisons`
  ADD CONSTRAINT `product_comparisons_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variant_values`
--
ALTER TABLE `product_variant_values`
  ADD CONSTRAINT `product_variant_values_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_values_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_values_ibfk_3` FOREIGN KEY (`value_id`) REFERENCES `product_attribute_values` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recently_viewed`
--
ALTER TABLE `recently_viewed`
  ADD CONSTRAINT `recently_viewed_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recently_viewed_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review_helpful`
--
ALTER TABLE `review_helpful`
  ADD CONSTRAINT `review_helpful_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `product_reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_helpful_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seller_payout_info`
--
ALTER TABLE `seller_payout_info`
  ADD CONSTRAINT `seller_payout_info_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_rates`
--
ALTER TABLE `shipping_rates`
  ADD CONSTRAINT `shipping_rates_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_tracking`
--
ALTER TABLE `shipping_tracking`
  ADD CONSTRAINT `shipping_tracking_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_payment_methods`
--
ALTER TABLE `user_payment_methods`
  ADD CONSTRAINT `user_payment_methods_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
