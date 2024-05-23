-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 05, 2016 at 03:50 PM
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
-- Table structure for table `driver_info`
--

CREATE TABLE `driver_info` (
  `dif_id` int(11) NOT NULL,
  `dif_agent_id` int(11) DEFAULT NULL,
  `dif_driver_id` int(11) DEFAULT NULL,
  `dif_name` varchar(255) NOT NULL,
  `dif_country_code` smallint(6) DEFAULT NULL,
  `dif_phone` varchar(100) NOT NULL,
  `dif_alt_phone` varchar(100) DEFAULT NULL,
  `dif_email` varchar(255) DEFAULT NULL,
  `dif_doj` date DEFAULT NULL,
  `dif_photo_path` varchar(500) DEFAULT NULL,
  `dif_lic_number` varchar(255) DEFAULT NULL,
  `dif_issue_auth` varchar(255) DEFAULT NULL,
  `dif_lic_exp_date` varchar(100) DEFAULT NULL,
  `dif_address` varchar(255) DEFAULT NULL,
  `dif_city` mediumint(9) DEFAULT NULL,
  `dif_state` smallint(6) DEFAULT NULL,
  `dif_zip` int(11) DEFAULT NULL,
  `dif_bg_checked` tinyint(4) DEFAULT NULL COMMENT '1=>Yes, 2=>No',
  `dif_aadhaar_img_path` varchar(500) DEFAULT NULL,
  `dif_pan_img_path` varchar(500) DEFAULT NULL,
  `dif_voter_id_img_path` varchar(500) DEFAULT NULL,
  `dif_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dif_modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `dif_active` tinyint(4) NOT NULL DEFAULT '1',
  `dif_is_attached` tinyint(4) NOT NULL DEFAULT '0',
  `dif_vif_licence` varchar(250) DEFAULT NULL,
  `dif_adrs_proof1` varchar(250) DEFAULT NULL,
  `dif_adrs_proof2` varchar(250) DEFAULT NULL,
  `dif_police_certificate` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `driver_info`
--
ALTER TABLE `driver_info`
  ADD PRIMARY KEY (`dif_id`),
  ADD KEY `drv_state` (`dif_state`),
  ADD KEY `drv_zip` (`dif_zip`),
  ADD KEY `drv_city` (`dif_city`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `driver_info`
--
ALTER TABLE `driver_info`
  MODIFY `dif_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
