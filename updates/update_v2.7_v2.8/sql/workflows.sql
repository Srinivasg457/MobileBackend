-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Aug 09, 2024 at 09:14 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

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
-- Table structure for table `workflows`
--

CREATE TABLE `workflows` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `workflows`
--

INSERT INTO `workflows` (`id`, `title`, `details`, `image`, `thumb`, `status`, `created_at`) VALUES
(2, 'Secure and Reliable Data Management', '<p><span xss=removed>You can trust SaaS accounting software to keep their financial data secure with encryption, regular backups, and advanced security measures, ensuring safe and reliable data management.</span><br></p>', 'assets/images/security_medium-512x512.png', 'assets/images/security_medium-512x512.png', '1', '2024-08-03 12:15:40'),
(3, 'Automated Financial Processes', '<p><span xss=removed>SaaS accounting software automates invoicing, expense tracking, and bank reconciliation, reducing manual effort and errors. This saves time and lets clients focus on growing their business.</span><br></p>', 'assets/images/validating-ticket_medium-512x512.png', 'assets/images/validating-ticket_medium-512x512.png', '1', '2024-08-03 12:15:10'),
(4, 'Instant Access Anytime, Anywhere', '<p><span xss=removed>SaaS accounting software lets clients access financial data from any internet-connected device. Whether in the office or on the go, clients can log in, view transactions, and generate real-time financial reports, staying informed about their financial status.</span><br></p>', 'assets/images/cloud_medium-512x512.png', 'assets/images/cloud_medium-512x512.png', '1', '2024-08-03 12:05:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `workflows`
--
ALTER TABLE `workflows`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `workflows`
--
ALTER TABLE `workflows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
