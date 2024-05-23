-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 05, 2016 at 03:51 PM
-- Server version: 5.7.6-m16-log
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gozo_60606`
--

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_info`
--

CREATE TABLE `vehicle_info` (
  `vif_id` int(11) NOT NULL,
  `vif_vehicle_id` int(11) DEFAULT NULL,
  `vif_number_plate` varchar(250) DEFAULT NULL,
  `vif_insurance_proof` varchar(250) DEFAULT NULL,
  `vif_front_plate` varchar(250) DEFAULT NULL,
  `vif_rear_plate` varchar(250) DEFAULT NULL,
  `vif_pollution_certificate` varchar(250) DEFAULT NULL,
  `vif_reg_certificate` varchar(250) DEFAULT NULL,
  `vif_permits_certificate` varchar(250) DEFAULT NULL,
  `vif_status` tinyint(4) NOT NULL,
  `vif_modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `vif_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `vif_type_id` int(11) DEFAULT NULL,
  `vif_year` mediumint(9) DEFAULT NULL,
  `vif_color` varchar(100) DEFAULT NULL,
  `vif_insurance_exp_date` date DEFAULT NULL,
  `vif_tax_exp_date` date DEFAULT NULL,
  `vif_dop` timestamp NULL DEFAULT NULL,
  `vif_is_attached` tinyint(4) DEFAULT '0',
  `vif_owned_or_rented` tinyint(4) DEFAULT NULL,
  `vif_agent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vehicle_info`
--
ALTER TABLE `vehicle_info`
  ADD PRIMARY KEY (`vif_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vehicle_info`
--
ALTER TABLE `vehicle_info`
  MODIFY `vif_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
