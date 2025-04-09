-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 16, 2023 at 05:09 PM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accufy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendence`
--

CREATE TABLE `attendence` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `check_in` varchar(255) NOT NULL,
  `check_out` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payouts`
--

CREATE TABLE `payouts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payout_method` varchar(255) NOT NULL,
  `method_details` varchar(255) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` int(11) NOT NULL,
  `referrar_id` varchar(255) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `amount` varchar(255) DEFAULT NULL,
  `commision` varchar(255) DEFAULT NULL,
  `commision_amount` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `referral_settings`
--

CREATE TABLE `referral_settings` (
  `id` int(11) NOT NULL,
  `is_enable` varchar(255) DEFAULT NULL,
  `referral_policy` text,
  `commision_rate` varchar(255) DEFAULT NULL,
  `minimum_payout` varchar(255) DEFAULT '0',
  `payment_method` varchar(255) DEFAULT NULL,
  `referral_guideline` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `referral_settings`
--

INSERT INTO `referral_settings` (`id`, `is_enable`, `referral_policy`, `commision_rate`, `minimum_payout`, `payment_method`, `referral_guideline`) VALUES
(1, '1', '2', '10', '50', 'Paypal', '<p>Referral guideline</p>');

-- --------------------------------------------------------

--
-- Table structure for table `salary`
--

CREATE TABLE `salary` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `acount` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendence`
--
ALTER TABLE `attendence`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payouts`
--
ALTER TABLE `payouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_settings`
--
ALTER TABLE `referral_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary`
--
ALTER TABLE `salary`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendence`
--
ALTER TABLE `attendence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payouts`
--
ALTER TABLE `payouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_settings`
--
ALTER TABLE `referral_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `salary`
--
ALTER TABLE `salary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
