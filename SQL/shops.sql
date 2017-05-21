-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 13, 2016 at 10:21 AM
-- Server version: 5.7.12-0ubuntu1
-- PHP Version: 7.0.4-7ubuntu2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `find-every`
--

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `shop_id` int(11) NOT NULL,
  `shop_cat` int(2) NOT NULL,
  `shop_start_date` date NOT NULL,
  `shop_end_date` date NOT NULL,
  `shop_name` tinytext NOT NULL,
  `shop_lat` float(15,11) NOT NULL,
  `shop_lng` float(15,11) NOT NULL,
  `shop_email` varchar(64) NOT NULL,
  `shop_password` varchar(64) NOT NULL,
  `shop_city` int(6) NOT NULL,
  `shop_pincode` int(6) NOT NULL,
  `shop_contact_no` varchar(11) NOT NULL,
  `shop_add_line_1` tinytext NOT NULL,
  `shop_add_line_2` tinytext NOT NULL,
  `shop_descr` tinytext NOT NULL,
  `shop_category` int(6) NOT NULL,
  `shop_approved` int(1) NOT NULL,
  `edit_shop_approved` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`shop_id`),
  ADD UNIQUE KEY `shop_email` (`shop_email`),
  ADD UNIQUE KEY `shop_contact_no` (`shop_contact_no`),
  ADD KEY `shops_ibfk_1` (`shop_category`),
  ADD KEY `shops_ibfk_2` (`shop_city`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `shop_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `shops`
--
ALTER TABLE `shops`
  ADD CONSTRAINT `shops_ibfk_1` FOREIGN KEY (`shop_category`) REFERENCES `category` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shops_ibfk_2` FOREIGN KEY (`shop_city`) REFERENCES `city` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
