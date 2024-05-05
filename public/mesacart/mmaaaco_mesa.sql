-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 22, 2020 at 06:37 PM
-- Server version: 10.0.38-MariaDB-cll-lve
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mmaaaco_mesa`
--

-- --------------------------------------------------------

--
-- Table structure for table `mesa_admin`
--

CREATE TABLE `mesa_admin` (
  `id` int(5) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mesa_admin`
--

INSERT INTO `mesa_admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$hxq1qZuHxuyTJHsv7kJg4.3KV/6.4O2ittpta86nWdjND5lLvCWE2');

-- --------------------------------------------------------

--
-- Table structure for table `mesa_attributes`
--

CREATE TABLE `mesa_attributes` (
  `prodid` int(5) NOT NULL,
  `labelid` int(5) NOT NULL,
  `value` varchar(255) NOT NULL,
  `price` decimal(9,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mesa_attributes`
--

INSERT INTO `mesa_attributes` (`prodid`, `labelid`, `value`, `price`) VALUES
(1, 1, 'Small', '0.00'),
(1, 1, 'Medium', '15.99'),
(1, 1, 'Large', '20.99'),
(1, 2, '4OZ.', '6.99'),
(1, 2, '6OZ.', '9.99');

-- --------------------------------------------------------

--
-- Table structure for table `mesa_cartitems`
--

CREATE TABLE `mesa_cartitems` (
  `productid` int(5) NOT NULL,
  `qty` int(5) NOT NULL,
  `sessionid` varchar(255) NOT NULL,
  `label` int(5) NOT NULL,
  `attribute` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mesa_cartitems`
--

INSERT INTO `mesa_cartitems` (`productid`, `qty`, `sessionid`, `label`, `attribute`) VALUES
(1, 4, '7f2ba3a76075e3929c4515e1c02b8c36', 0, ''),
(3, 2, '7f2ba3a76075e3929c4515e1c02b8c36', 0, ''),
(1, 1, '20c9a6017858e89d71e3bf9e93f7da84', 0, ''),
(2, 1, '20c9a6017858e89d71e3bf9e93f7da84', 0, ''),
(3, 1, '20c9a6017858e89d71e3bf9e93f7da84', 0, ''),
(3, 1, 'e9c63ea5c247b76244b5ebfac3871fa5', 0, ''),
(1, 2, '23848273492394792379', 1, 'Small'),
(2, 1, '23848273492394792379', 2, '6OZ'),
(2, 1, 'f4d5c4d3f5253c0bf2f868e2a0bf7086', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `mesa_categories`
--

CREATE TABLE `mesa_categories` (
  `catid` int(5) NOT NULL,
  `catname` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mesa_categories`
--

INSERT INTO `mesa_categories` (`catid`, `catname`) VALUES
(1, 'CBD for Dogs'),
(2, 'CBD for arthritis'),
(3, 'CBD for baldness');

-- --------------------------------------------------------

--
-- Table structure for table `mesa_labels`
--

CREATE TABLE `mesa_labels` (
  `labelId` int(5) NOT NULL,
  `labelname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mesa_labels`
--

INSERT INTO `mesa_labels` (`labelId`, `labelname`) VALUES
(1, 'Sizes'),
(2, 'Volumes'),
(3, 'Colors');

-- --------------------------------------------------------

--
-- Table structure for table `mesa_products`
--

CREATE TABLE `mesa_products` (
  `prodid` int(5) NOT NULL,
  `prodname` varchar(255) NOT NULL,
  `proddesc` text NOT NULL,
  `prodprice` decimal(9,2) NOT NULL,
  `link` varchar(255) NOT NULL,
  `featured` enum('yes','no') NOT NULL,
  `catid` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mesa_products`
--

INSERT INTO `mesa_products` (`prodid`, `prodname`, `proddesc`, `prodprice`, `link`, `featured`, `catid`) VALUES
(1, 'Dog Massage Oil', 'Keep spot loose in his older years...', '39.99', 'Dog-CBD', 'yes', 1),
(2, 'CBD Shampoo', 'Yeah, it doesn\'t work...but you think it does', '59.99', 'CBD-Shampoo', 'yes', 3),
(3, 'Arthritis pain relief gel', 'Blah, blah', '24.99', '', 'yes', 2),
(4, 'Dasuquin', 'JOints', '39.99', 'Joint-Supplements', 'yes', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mesa_admin`
--
ALTER TABLE `mesa_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mesa_categories`
--
ALTER TABLE `mesa_categories`
  ADD PRIMARY KEY (`catid`);

--
-- Indexes for table `mesa_labels`
--
ALTER TABLE `mesa_labels`
  ADD PRIMARY KEY (`labelId`);

--
-- Indexes for table `mesa_products`
--
ALTER TABLE `mesa_products`
  ADD PRIMARY KEY (`prodid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mesa_admin`
--
ALTER TABLE `mesa_admin`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mesa_categories`
--
ALTER TABLE `mesa_categories`
  MODIFY `catid` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mesa_labels`
--
ALTER TABLE `mesa_labels`
  MODIFY `labelId` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mesa_products`
--
ALTER TABLE `mesa_products`
  MODIFY `prodid` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
