-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 19, 2025 at 12:44 AM
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
-- Database: `php_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` text NOT NULL,
  `admin_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_name`, `admin_email`, `admin_password`) VALUES
(1, 'Moaz', 'moaz@gmail.com', '$2y$10$aNY5RnLYkifX.KOG90xPE.jPIUrCDC3OiDgMgkPD7yYI2m6IMRWAO'),
(2, 'Yusuf', 'yusuf@gmail.com', '$2y$10$5NwNf3CasqORe2UTTOerCuKpaaWIPLYw5GYqdpPjiuKDxpHkmNY2C'),
(3, 'Zeyad', 'zeyad@gmail.com', '$2y$10$XPlXVAhrVlFCR0XbNujuzegcHOD4mkyTgVAgaeC8Mqgh/5KTBNJAO');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_cost` decimal(8,2) NOT NULL,
  `order_status` varchar(100) NOT NULL DEFAULT 'unpaid',
  `user_id` int(11) NOT NULL,
  `user_phone` int(11) NOT NULL,
  `user_city` varchar(255) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_cost`, `order_status`, `user_id`, `user_phone`, `user_city`, `user_address`, `order_date`) VALUES
(26, 3050.00, 'delivered', 2, 123456789, 'Damietta', 'Damietta', '2025-02-02 22:17:52'),
(27, 9750.00, 'unpaid', 3, 123456789, 'Damietta', 'Damietta', '2025-02-02 22:18:13'),
(28, 4500.00, 'unpaid', 4, 123456789, 'Damietta', 'Damietta', '2025-02-02 22:18:41'),
(29, 5700.00, 'unpaid', 5, 123456789, 'Damietta', 'Damietta', '2025-02-02 22:19:13'),
(30, 5000.00, 'unpaid', 1, 1234567899, 'Damietta', 'Damietta', '2025-02-07 14:09:07');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_price` decimal(8,2) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_id`, `product_name`, `product_image`, `product_price`, `product_quantity`, `user_id`, `order_date`) VALUES
(43, 26, 2, 'Large Black Sofa', '2.jpg', 2150.00, 1, 2, '2025-02-02 22:17:52'),
(44, 26, 3, 'Small Grey Sofa', '3.jpg', 900.00, 1, 2, '2025-02-02 22:17:52'),
(45, 27, 6, 'Large Bed (White)', '6.jpg', 3250.00, 3, 3, '2025-02-02 22:18:13'),
(46, 28, 5, 'Large Bed (Beige)', '5.jpg', 3050.00, 1, 4, '2025-02-02 22:18:41'),
(47, 28, 10, 'Armchair (Black)', '10.jpg', 1450.00, 1, 4, '2025-02-02 22:18:41'),
(48, 29, 8, 'Large Bed (Whitesmoke)', '8.jpg', 3500.00, 1, 5, '2025-02-02 22:19:13'),
(49, 29, 11, 'Beige Table', '11.jpg', 1000.00, 1, 5, '2025-02-02 22:19:13'),
(50, 29, 12, 'Beige Dining Table', '12.jpg', 1200.00, 1, 5, '2025-02-02 22:19:13'),
(51, 30, 1, 'White Sofa', '1.jpg', 1250.00, 4, 1, '2025-02-07 14:09:07');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_category` varchar(100) NOT NULL,
  `product_description` varchar(255) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_image2` varchar(255) NOT NULL,
  `product_image3` varchar(255) NOT NULL,
  `product_image4` varchar(255) NOT NULL,
  `product_price` decimal(6,2) NOT NULL,
  `product_special_offer` int(2) NOT NULL,
  `product_color` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_category`, `product_description`, `product_image`, `product_image2`, `product_image3`, `product_image4`, `product_price`, `product_special_offer`, `product_color`) VALUES
(1, 'White Sofa', 'Sofa', '120cm x 70cm', '1.jpg', '1_2.jpg', '1_3.jpg', '1_4.jpg', 1250.00, 0, 'white'),
(2, 'Large Black Sofa', 'Sofa', 'A large comfortable modern sofa. High quality sofa.', '2.jpg', '2_2.jpg', '2_3.jpg', '2_4.jpg', 2150.00, 0, 'black'),
(3, 'Small Grey Sofa', 'Sofa', 'Modern design, comfortable, high quality small size sofa.', '3.jpg', '3_2.jpg', '3_3.jpg', '3_4.jpg', 900.00, 0, 'grey'),
(4, 'Brown Sofa', 'Sofa', 'High quality, comfortable and modern sofa.', '4.jpg', '4_2.jpg', '4_3.jpg', '4_4.jpg', 1200.00, 0, 'brown'),
(5, 'Large Bed (Beige)', 'Bed', 'High quality, comfortable and modern bed.', '5.jpg', '5_2.jpg', '5_3.jpg', '5_4.jpg', 3050.00, 0, 'beige'),
(6, 'Large Bed (White)', 'Bed', 'High quality, comfortable and modern bed.', '6.jpg', '6_2.jpg', '6_3.jpg', '6_4.jpg', 3250.00, 0, 'white'),
(7, 'Large Bed (Grey)', 'Bed', 'High quality, comfortable and modern bed.', '7.jpg', '7_2.jpg', '7_3.jpg', '7_4.jpg', 4000.00, 0, 'grey'),
(8, 'Large Bed (Whitesmoke)', 'Bed', 'High quality, comfortable and modern bed.', '8.jpg', '8_2.jpg', '8_3.jpg', '8_4.jpg', 3500.00, 0, 'white'),
(9, 'Grey Armchair', 'Armchair', 'High quality, comfortable and modern armchair.', '9.jpg', '9_2.jpg', '9_3.jpg', '9_4.jpg', 1500.00, 0, 'grey'),
(10, 'Armchair (Black)', 'Armchair', 'High quality, comfortable and modern armchair.', '10.jpg', '10_2.jpg', '10_3.jpg', '10_4.jpg', 1450.00, 0, 'black'),
(11, 'Beige Table', 'Table', 'High quality, comfortable and modern table.', '11.jpg', '11_2.jpg', '11_3.jpg', '11_4.jpg', 1000.00, 0, 'beige'),
(12, 'Beige Dining Table', 'Table', 'High quality, comfortable and modern dining table.', '12.jpg', '12_2.jpg', '12_3.jpg', '12_4.jpg', 1200.00, 0, 'beige');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`) VALUES
(1, 'Yusuf', 'yusuf123@gmail.com', '$2y$10$5lCyXzWt8egRq8epCIjnkeetA0o9qAZ80aHFQIVbCKw5QiC3rsu1u'),
(10, 'User2', 'user2@gmail.com', '$2y$10$DoR601fFIog.k6zlXvYbGeXhpiQAGYOXHh3vh0vT.ahS23KL1kU4q');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
