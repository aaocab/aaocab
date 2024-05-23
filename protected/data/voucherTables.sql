

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `vch_id` int(11) NOT NULL,
  `vch_code` varchar(70) DEFAULT NULL,
  `vch_title` varchar(100) DEFAULT NULL,
  `vch_desc` varchar(400) DEFAULT NULL,
  `vch_type` smallint(6) DEFAULT NULL COMMENT '1:promo,2:wallet',
  `vch_selling_price` mediumint(9) DEFAULT NULL,
  `vch_promo_id` int(11) DEFAULT NULL,
  `vch_wallet_amt` mediumint(9) DEFAULT NULL,
  `vch_is_all_partner` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0:specific partner, 1:all partner',
  `vch_is_all_users` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0:specific user, 1:all user	',
  `vch_max_allowed_limit` mediumint(9) DEFAULT 0,
  `vch_redeem_user_limit` smallint(6) NOT NULL DEFAULT 0 COMMENT 'redeem per user counter',
  `vch_user_purchase_limit` smallint(6) NOT NULL DEFAULT 0 COMMENT 'buy per user counter',
  `vch_partner_purchase_limit` smallint(6) NOT NULL DEFAULT 0,
  `vch_sold_ctr` mediumint(9) NOT NULL DEFAULT 0,
  `vch_valid_from` datetime DEFAULT NULL,
  `vch_valid_to` datetime DEFAULT NULL,
  `vch_active` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`vch_id`, `vch_code`, `vch_title`, `vch_desc`, `vch_type`, `vch_selling_price`, `vch_promo_id`, `vch_wallet_amt`, `vch_is_all_partner`, `vch_is_all_users`, `vch_max_allowed_limit`, `vch_redeem_user_limit`, `vch_user_purchase_limit`, `vch_partner_purchase_limit`, `vch_sold_ctr`, `vch_valid_from`, `vch_valid_to`, `vch_active`) VALUES
(32, 'gozo2020', 'test voucher12', NULL, 2, 1000, NULL, 600, 1, 1, 6, 0, 0, 0, 0, '2020-05-01 00:00:00', '2020-12-25 00:00:00', 1),
(34, 'kapil2', 'test123', NULL, 2, 5000, NULL, 300, 1, 0, 3, 0, 0, 0, 0, '2020-04-22 00:00:00', '2020-08-06 00:00:00', 1),
(35, 'Ginger', 'rhrryyy', NULL, 2, 1000, 244, 600, 1, 0, 2, 0, 0, 0, 0, '2020-05-01 00:00:00', '2021-05-01 00:00:00', 1),
(36, 'CORONA', 'test kit data', NULL, 1, 2000, 185, NULL, 0, 1, 2, 0, 0, 0, 0, NULL, NULL, 1),
(39, 'Test', 'aaaa', NULL, 1, 10, 245, NULL, 1, 1, 0, 0, 0, 0, 0, NULL, '2020-05-04 00:00:00', 1),
(40, 'SBIN', 'SBI Yono', 'A product of state bank of india', 2, 55, NULL, 230, 0, 1, 3, 1, 3, 6, 0, '2020-05-15 00:00:00', '2020-11-27 00:00:00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_order`
--

CREATE TABLE `voucher_order` (
  `vor_id` int(11) NOT NULL,
  `vor_number` varchar(50) DEFAULT NULL,
  `vor_apg_id` int(11) DEFAULT NULL,
  `vor_sess_id` varchar(255) DEFAULT NULL,
  `vor_name` varchar(50) DEFAULT NULL,
  `vor_email` varchar(50) DEFAULT NULL,
  `vor_phone` varchar(50) DEFAULT NULL,
  `vor_total_price` float(16,2) DEFAULT NULL,
  `vor_bill_fullname` varchar(255) DEFAULT NULL,
  `vor_bill_contact` varchar(255) DEFAULT NULL,
  `vor_bill_email` varchar(255) DEFAULT NULL,
  `vor_bill_address` varchar(255) DEFAULT NULL,
  `vor_bill_country` varchar(255) DEFAULT NULL,
  `vor_bill_state` varchar(255) DEFAULT NULL,
  `vor_bill_city` varchar(255) DEFAULT NULL,
  `vor_bill_postalcode` varchar(255) DEFAULT NULL,
  `vor_bill_bankcode` varchar(255) DEFAULT NULL,
  `vor_date` datetime DEFAULT NULL,
  `vor_active` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `voucher_order`
--

INSERT INTO `voucher_order` (`vor_id`, `vor_number`, `vor_apg_id`, `vor_sess_id`, `vor_name`, `vor_email`, `vor_phone`, `vor_total_price`, `vor_bill_fullname`, `vor_bill_contact`, `vor_bill_email`, `vor_bill_address`, `vor_bill_country`, `vor_bill_state`, `vor_bill_city`, `vor_bill_postalcode`, `vor_bill_bankcode`, `vor_date`, `vor_active`) VALUES
(4, '14K2VZ6J', NULL, NULL, 'yy', 'dd@gmail.com', '7878787878', 30.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(5, 'ZJXR0PCU', NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', 1170.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(6, '56B9KX2A', NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', 1170.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(7, 'AG5ZHL0O', NULL, '24005718c513c54b23de47759b144e83', 'Sudipto Roy', 'ambika.sridhar030109@gmail.com', '9231828196', 2310.00, 'Sudipto Roy', '9231828196', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '700091', '', NULL, 2),
(8, '6CURBXG0', NULL, 'b07768ffcd531c4a74d0347e473ffd65', 'Sudipto Roy', 'ambika.sridhar030109@gmail.com', '9231828196', 280.00, 'Sudipto Roy', '9231828196', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '700091', '', NULL, 2),
(9, '4R5HWD9N', NULL, '4408428364fc75395d1e7c20d3f78315', 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', 2060.00, 'Sudipto Roy', '9874822471', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '700091', '', NULL, 2),
(10, 'RZOQIVK7', NULL, 'b6beff4f823ad411d17d5fbf34eb40ef', 'roma nayek', 'romanayek1810@gmail.com', '9123118963', 130.00, 'roma nayek', '9231828196', 'romanayek1810@gmail.com', 'Delhi, Delhi', 'US', 'Delhi', 'Delhi', '40001', '', NULL, 2),
(11, 'UWTZYL2H', 485784, '97e05c8b8ff6f6be7bcfbe0607eff7ff', 'roma nayek', 'romanayek1810@gmail.com', '9733720521', 295.00, 'roma nayek', '9733720521', 'romanayek1810@gmail.com', 'Delhi, Delhi', 'IN', 'Delhi', 'Delhi', '110024', '', NULL, 1),
(12, 'XPN5AES4', 485786, '131c5c14d42dba50cb5bd24c6385e622', 'roma nayek', 'romanayek1810@gmail.com', '9231828196', 205.00, 'roma nayek', '9733720521', 'romanayek1810@gmail.com', 'Delhi, Delhi', 'IN', 'Delhi', 'Delhi', '110024', '', NULL, 1),
(13, '6LXNIBU9', 485796, 'd481dd20c640f2114367b5f372a96110', 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', 2090.00, 'Sudipto Roy', '9874822471', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '100011', '', NULL, 1),
(14, 'R5I4VUGF', 485812, '6268cbfc6cf07f92fa43d2d3f3545376', 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', 300.00, 'Sudipto Roy', '9231828196', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '100011', '', NULL, 1),
(15, 'BNKXC965', 485846, 'c7577bd23b9e7f10a88a3008d109186b', 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', 300.00, 'Sudipto Roy', '9231828196', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '700091', '', NULL, 2),
(16, 'SWOUIK69', 485856, '7f2f3f856e229a88f20efe9da749b4e0', 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', 1060.00, 'Sudipto Roy', '9874822471', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '700091', '', NULL, 1),
(17, 'Y83R51GT', NULL, '2c2eaab973252333d1816577bdb4a8b6', 'Sudipta Mitra', 'sudiptaa008@gmail.com', '8017879076', 65.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(18, 'GD5T7M8B', NULL, '8c80929caf53106b49f28a9114ba5dff', 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', 2030.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(19, 'KXB4SJPG', 485872, 'aa468cb83f1ab223cb005019bd74044c', 'dd', 'dd@gmail.com', '778878787878', 5185.00, 'ss', '8778787878', 'ss@gmail.com', 'ggg', 'US', 'west  bengal', 'kolkata', '700101', '', NULL, 2),
(20, '8XYCW4JK', NULL, 'ef62d6861770fa3137987dc282528d68', 'Sudipta Mitra', 'sudiptaa008@gmail.com', '8017879076', 10.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(21, 'GETX4YA6', NULL, '2a2d7573c877faaa11edbec88788bdd3', 'Sudipta Mitra', 'sudiptaa008@gmail.com', '8017879076', 2010.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(22, 'WXO0QZNH', NULL, '17341c037734911e6c354238a2ace136', 'ggg', 'dd@gmail.copm', '5656565656', 110.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(23, 'TMI2CD90', 485957, '8ff648596512316af1cccc52a30d1c56', 'souvik', 'souvik@gmail.com', '9231828756', 2000.00, 'Sudipto Roy', '9231828196', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '700091', '', NULL, 2),
(24, 'QO7IJLDE', NULL, '23445498341cc173bb51c3a7d34f1a89', 'dd', 'dd@gmail.com', '778878787878', 55.00, 'rajesh kumar', '8956232145', 'rraji3@gmail.com', 'bel tala', 'IN', 'west  bengal', 'kolkata', '700010', '', NULL, 2),
(25, 'LD5XR2WE', 485971, '9c3e1e8dc67bb4578136faa2792cbcff', 'rajesh 55.00 kumar', 'rraji3@gmail.com', '8956232145', 65.00, 'rajesh 55.00 kumar', '8956232145', 'rraji3@gmail.com', 'bel tala', 'US', 'west  bengal', 'kolkata', '700010', '', NULL, 2),
(26, 'WM24ER0B', NULL, '7017dc949aff817381b4deb80e88c7e2', 'Sudipta Mitra', 'sudiptaa008@gmail.com', '8017879076', 55.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(27, 'MA6WHFZO', 485958, 'e9d4b1c20f1946990a7b482a867d1b98', 'roma nayek', 'romanayek1810@gmail.com', '9733720521', 2205.00, 'Sudipto Roy', '9231828196', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '700091', '', NULL, 1),
(28, 'XFKHPGMO', 485960, '0ce2750d0eee483d1be2abd72e4a6d42', 'P ROy', 'proy82@gmail.com', '7981828196', 4220.00, 'Sudipto Roy', '9874822471', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '700091', '', NULL, 1),
(29, 'XQGKT6AH', 485961, '4d221fe2e4dedd0de66dfcba550fe35e', 'Souvik Ghosh', 'souvik.ghosh@gmail.com', '9871828196', 4110.00, 'Sudipto Roy', '9231828196', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '700091', '', '2020-06-29 18:56:25', 1),
(30, 'Y1W72F0K', 485962, '415409d55fb049587ed6e1f610965ca1', 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9874822471', 4000.00, 'Sudipto Roy', '9874822471', 'sudipta.roy81@gmail.com', 'Chandigarh', 'US', 'Chandigarh', 'Chandigarh', '700091', '', '2020-06-29 19:36:55', 1),
(31, '8JP74SV2', 485963, '32009f03765d683120fcda5ab2499fca', 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', 3040.00, 'Sudipto Roy', '9231828196', 'sudipta.roy81@gmail.com', 'Chandigarh', 'US', 'Chandigarh', 'Chandigarh', '700091', '', '2020-06-29 19:58:57', 1),
(32, '3XFH1ZCD', NULL, '03c29252bb0b3f30f47b8db0f6bf6a49', NULL, NULL, NULL, 100.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(33, '8F7SJAUB', 485977, '2a2a76090958fb22d43760fc10b83210', 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9874822471', 1165.00, 'Sudipto Roy', '9874822471', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '700091', '', '2020-06-30 19:56:15', 2),
(34, 'TIJY9DN0', NULL, 'ec4ad7d8b0564e0197eb01bee57abbe5', 'Sudipta Mitra', 'sudiptaa008@gmail.com', '8017879076', 65.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(35, '2XYU3OK4', 485992, '5dcc2b9eeba7a48ce4b16e7cc0bac6a3', 'Sudipta Mitra', 'sudiptaa008@gmail.com', '8017879076', 2055.00, 'sds', '8017879076', 'sudiptaa008@gmail.com', 'Delhi, Delhi', 'IN', 'Delhi', 'Delhi', '123456', '', '2020-07-01 13:20:31', 2),
(36, '8QGVBXNR', 485995, 'b362021afa3bbc0725ee4cd728e0bb86', 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9874822471', 220.00, 'Sudipto Roy', '9874822471', 'sudipta.roy81@gmail.com', 'Chandigarh', 'IN', 'Chandigarh', 'Chandigarh', '700091', '', '2020-07-01 22:30:50', 1),
(37, '9X5LN6RK', NULL, '185eac38120e1c4c8ef9706fb3f9a597', NULL, NULL, NULL, 2220.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(38, 'YLI8A6FP', NULL, '25197471eab7dcc2cd4957d7a7bf8c8d', NULL, NULL, NULL, 2000.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(39, '4KTY1ZO3', NULL, '37faa84bbe8fdc2acca3e5895f3387bc', NULL, NULL, NULL, 100.00, 'Sudipto Roy', '8240276626', 'sudipta.roy81@gmail.com', NULL, NULL, NULL, NULL, '700036', NULL, NULL, 2),
(40, '83YAZ7D5', NULL, 'c67723fa09e0b46340c2004f04072b0f', NULL, NULL, NULL, 55.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_order_details`
--

CREATE TABLE `voucher_order_details` (
  `vod_id` int(11) NOT NULL,
  `vod_ord_id` int(11) DEFAULT NULL,
  `vod_vch_id` int(11) DEFAULT NULL,
  `vod_vch_qty` mediumint(9) DEFAULT NULL,
  `vod_vch_price` float(16,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `voucher_order_details`
--

INSERT INTO `voucher_order_details` (`vod_id`, `vod_ord_id`, `vod_vch_id`, `vod_vch_qty`, `vod_vch_price`) VALUES
(10, 5, 40, 2, 110.00),
(11, 5, 39, 6, 60.00),
(12, 5, 32, 1, 1000.00),
(13, 6, 40, 2, 110.00),
(14, 6, 39, 6, 60.00),
(15, 6, 32, 1, 1000.00),
(24, 7, 40, 4, 220.00),
(25, 7, 39, 9, 90.00),
(26, 7, 32, 1, 1000.00),
(27, 7, 35, 1, 1000.00),
(44, 8, 40, 4, 220.00),
(45, 8, 39, 6, 60.00),
(62, 9, 39, 6, 60.00),
(63, 9, 36, 1, 2000.00),
(66, 10, 39, 13, 130.00),
(67, 11, 39, 13, 130.00),
(68, 11, 40, 3, 165.00),
(70, 12, 40, 3, 165.00),
(71, 12, 39, 4, 40.00),
(76, 13, 39, 9, 90.00),
(77, 13, 35, 2, 2000.00),
(82, 14, 39, 8, 80.00),
(83, 14, 40, 4, 220.00),
(86, 15, 39, 8, 80.00),
(87, 15, 40, 4, 220.00),
(88, 16, 39, 6, 60.00),
(89, 16, 35, 1, 1000.00),
(98, 19, 40, 3, 165.00),
(99, 19, 39, 2, 20.00),
(100, 19, 34, 1, 5000.00),
(103, 20, 39, 1, 10.00),
(107, 18, 32, 2, 2000.00),
(108, 18, 39, 3, 30.00),
(110, 22, 40, 2, 110.00),
(115, 24, 40, 1, 55.00),
(116, 21, 39, 1, 10.00),
(117, 21, 36, 1, 2000.00),
(128, 26, 40, 1, 55.00),
(129, 23, 36, 1, 2000.00),
(133, 27, 39, 4, 40.00),
(134, 27, 40, 3, 165.00),
(135, 27, 36, 1, 2000.00),
(136, 28, 36, 2, 4000.00),
(137, 28, 40, 4, 220.00),
(138, 29, 40, 2, 110.00),
(139, 29, 36, 2, 4000.00),
(140, 30, 35, 2, 2000.00),
(141, 30, 36, 1, 2000.00),
(142, 31, 39, 4, 40.00),
(143, 31, 35, 1, 1000.00),
(144, 31, 36, 1, 2000.00),
(145, 32, 39, 10, 100.00),
(150, 34, 40, 1, 55.00),
(151, 34, 39, 1, 10.00),
(156, 35, 40, 1, 55.00),
(157, 35, 36, 1, 2000.00),
(160, 33, 40, 3, 165.00),
(161, 33, 35, 1, 1000.00),
(162, 36, 40, 4, 220.00),
(165, 37, 36, 1, 2000.00),
(166, 37, 40, 4, 220.00),
(172, 38, 36, 1, 2000.00),
(173, 25, 40, 1, 55.00),
(174, 25, 39, 1, 10.00),
(175, 39, 39, 10, 100.00),
(176, 40, 40, 1, 55.00);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_order_invoice`
--

CREATE TABLE `voucher_order_invoice` (
  `voi_id` int(11) NOT NULL,
  `voi_vor_id` int(11) NOT NULL,
  `voi_base_amount` mediumint(9) DEFAULT NULL,
  `voi_discount_amount` mediumint(9) DEFAULT 0,
  `voi_total_amount` mediumint(9) DEFAULT 0,
  `voi_gozo_amount` mediumint(9) DEFAULT NULL,
  `voi_corporate_credit` mediumint(9) DEFAULT 0,
  `voi_credits_used` mediumint(9) DEFAULT 0,
  `voi_advance_amount` mediumint(9) NOT NULL DEFAULT 0,
  `voi_refund_amount` mediumint(9) NOT NULL DEFAULT 0,
  `voi_cancel_refund` int(11) NOT NULL DEFAULT 0 COMMENT '# Amount to be refunded during cancellation',
  `voi_refund_approval_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT ' 0 => default 	 * 1 => refund approval needed 	 * 2 => refund not approved 	 * 3 => refund approved   	 * 4 => refund processed  	 * 5 => no refund to be proccessed',
  `voi_due_amount` mediumint(9) NOT NULL DEFAULT 0,
  `voi_additional_charge` mediumint(9) NOT NULL DEFAULT 0,
  `voi_additional_charge_remark` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `voi_convenience_charge` smallint(6) DEFAULT NULL,
  `voi_service_tax` mediumint(9) DEFAULT NULL,
  `voi_service_tax_rate` tinyint(4) DEFAULT NULL,
  `voi_igst` float(8,2) NOT NULL DEFAULT 0.00,
  `voi_cgst` float(8,2) NOT NULL DEFAULT 0.00,
  `voi_sgst` float(8,2) NOT NULL DEFAULT 0.00,
  `voi_extra_charge` mediumint(9) DEFAULT 0,
  `voi_cancel_charge` mediumint(9) DEFAULT NULL,
  `voi_extra_km` smallint(6) DEFAULT NULL,
  `voi_extra_total_km` smallint(6) DEFAULT NULL,
  `voi_extra_km_charge` mediumint(9) DEFAULT NULL,
  `voi_corporate_discount` mediumint(9) DEFAULT 0,
  `voi_promo1_id` mediumint(9) NOT NULL DEFAULT 0,
  `voi_promo1_code` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `voi_promo1_amt` mediumint(9) NOT NULL DEFAULT 0,
  `voi_promo1_coins` mediumint(9) DEFAULT 0,
  `voi_promo2_id` mediumint(9) NOT NULL DEFAULT 0,
  `voi_promo2_code` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `voi_promo2_amt` mediumint(9) NOT NULL DEFAULT 0,
  `voi_price_surge_id` mediumint(9) DEFAULT NULL,
  `voi_agent_commission` mediumint(9) NOT NULL DEFAULT 0,
  `voi_cp_comm_type` tinyint(4) DEFAULT NULL,
  `voi_cp_comm_value` decimal(8,2) DEFAULT NULL,
  `voi_chargeable_distance` smallint(6) DEFAULT NULL,
  `voi_corporate_remunerator` tinyint(4) DEFAULT NULL COMMENT '1:user,2:company',
  `voi_partner_commission` mediumint(9) DEFAULT NULL,
  `voi_wallet_used` int(11) DEFAULT 0,
  `voi_temp_credits` mediumint(9) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `voucher_order_invoice`
--

INSERT INTO `voucher_order_invoice` (`voi_id`, `voi_vor_id`, `voi_base_amount`, `voi_discount_amount`, `voi_total_amount`, `voi_gozo_amount`, `voi_corporate_credit`, `voi_credits_used`, `voi_advance_amount`, `voi_refund_amount`, `voi_cancel_refund`, `voi_refund_approval_status`, `voi_due_amount`, `voi_additional_charge`, `voi_additional_charge_remark`, `voi_convenience_charge`, `voi_service_tax`, `voi_service_tax_rate`, `voi_igst`, `voi_cgst`, `voi_sgst`, `voi_extra_charge`, `voi_cancel_charge`, `voi_extra_km`, `voi_extra_total_km`, `voi_extra_km_charge`, `voi_corporate_discount`, `voi_promo1_id`, `voi_promo1_code`, `voi_promo1_amt`, `voi_promo1_coins`, `voi_promo2_id`, `voi_promo2_code`, `voi_promo2_amt`, `voi_price_surge_id`, `voi_agent_commission`, `voi_cp_comm_type`, `voi_cp_comm_value`, `voi_chargeable_distance`, `voi_corporate_remunerator`, `voi_partner_commission`, `voi_wallet_used`, `voi_temp_credits`) VALUES
(1, 8, NULL, 0, 280, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(2, 9, NULL, 0, 2060, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(3, 10, NULL, 0, 130, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(4, 11, NULL, 0, 295, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(5, 12, NULL, 0, 205, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(6, 13, NULL, 0, 2090, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(7, 14, NULL, 0, 300, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(8, 15, NULL, 0, 300, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(9, 16, NULL, 0, 1060, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(10, 18, NULL, 0, 2030, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(11, 19, NULL, 0, 5185, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(12, 20, NULL, 0, 10, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(13, 21, NULL, 0, 2010, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(14, 22, NULL, 0, 110, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(15, 23, NULL, 0, 2000, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(16, 24, NULL, 0, 55, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(17, 25, NULL, 0, 65, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(18, 26, NULL, 0, 55, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(19, 27, NULL, 0, 2205, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(20, 28, NULL, 0, 4220, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(21, 29, NULL, 0, 4110, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(22, 30, NULL, 0, 4000, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(23, 31, NULL, 0, 3040, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(24, 32, NULL, 0, 100, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(25, 33, NULL, 0, 1165, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(26, 34, NULL, 0, 65, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(27, 35, NULL, 0, 2055, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(28, 36, NULL, 0, 220, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(29, 37, NULL, 0, 2220, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(30, 38, NULL, 0, 2000, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(31, 39, NULL, 0, 100, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(32, 40, NULL, 0, 55, NULL, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_order_user`
--

CREATE TABLE `voucher_order_user` (
  `vou_id` int(11) NOT NULL,
  `vou_order_id` int(11) DEFAULT NULL,
  `vou_user_id` int(11) DEFAULT NULL,
  `vou_user_fname` varchar(255) DEFAULT NULL,
  `vou_user_lname` varchar(255) DEFAULT NULL,
  `vou_country_code` varchar(50) DEFAULT NULL,
  `vou_contact_no` varchar(25) DEFAULT NULL,
  `vou_user_email` varchar(255) DEFAULT NULL,
  `vou_user_city` varchar(255) DEFAULT NULL,
  `vou_user_country` varchar(255) DEFAULT NULL,
  `vou_bill_fullname` varchar(255) DEFAULT NULL,
  `vou_bill_contact` varchar(255) DEFAULT NULL,
  `vou_bill_email` varchar(255) DEFAULT NULL,
  `vou_bill_address` varchar(255) DEFAULT NULL,
  `vou_bill_country` varchar(255) DEFAULT NULL,
  `vou_bill_state` varchar(255) DEFAULT NULL,
  `vou_bill_city` varchar(255) DEFAULT NULL,
  `vou_bill_postalcode` varchar(20) DEFAULT NULL,
  `vou_bill_bankcode` varchar(10) DEFAULT NULL,
  `vou_user_last_updated_on` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_partner`
--

CREATE TABLE `voucher_partner` (
  `vpr_id` int(11) NOT NULL,
  `vpr_partner_id` mediumint(9) DEFAULT NULL,
  `vpr_vch_id` int(11) DEFAULT NULL,
  `vpr_used_ctr` int(11) DEFAULT NULL,
  `vpr_max_allowed` smallint(6) DEFAULT NULL,
  `vpr_valid_till` datetime DEFAULT NULL,
  `vpr_active` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `voucher_partner`
--

INSERT INTO `voucher_partner` (`vpr_id`, `vpr_partner_id`, `vpr_vch_id`, `vpr_used_ctr`, `vpr_max_allowed`, `vpr_valid_till`, `vpr_active`) VALUES
(21, 947, 36, NULL, 2, '2020-05-06 00:00:00', 1),
(26, 689, 36, NULL, 2, '2020-05-06 00:00:00', 1),
(28, 18237, 40, NULL, 3, '2020-11-27 00:00:00', 1),
(29, 454, 40, NULL, 3, '2020-11-27 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_subscriber`
--

CREATE TABLE `voucher_subscriber` (
  `vsb_id` int(11) NOT NULL,
  `vsb_vor_id` int(11) DEFAULT NULL,
  `vsb_vch_id` int(11) DEFAULT NULL,
  `vsb_apg_id` int(11) DEFAULT NULL,
  `vsb_redeem_code` varchar(70) DEFAULT NULL,
  `vsb_redeem_date` datetime DEFAULT NULL,
  `vsb_date` datetime DEFAULT NULL,
  `vsb_purchased_by` mediumint(9) DEFAULT NULL,
  `vsb_redeemed_by` mediumint(9) DEFAULT NULL,
  `vsb_name` varchar(100) DEFAULT NULL,
  `vsb_email` varchar(100) DEFAULT NULL,
  `vsb_phone` varchar(100) DEFAULT NULL,
  `vsb_expiry_date` datetime DEFAULT NULL,
  `vsb_cost` float(16,2) DEFAULT NULL,
  `vsb_active` tinyint(4) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `voucher_subscriber`
--

INSERT INTO `voucher_subscriber` (`vsb_id`, `vsb_vor_id`, `vsb_vch_id`, `vsb_apg_id`, `vsb_redeem_code`, `vsb_redeem_date`, `vsb_date`, `vsb_purchased_by`, `vsb_redeemed_by`, `vsb_name`, `vsb_email`, `vsb_phone`, `vsb_expiry_date`, `vsb_cost`, `vsb_active`) VALUES
(1, 8, 40, NULL, '098f6bcd4621d373cade4e832627b4f6', '2020-06-23 15:14:48', NULL, NULL, 499444, NULL, NULL, NULL, NULL, 1.00, 2),
(2, 7, 39, NULL, '8215e48bd370871e71a61118277b6876', '2020-06-20 13:35:13', NULL, NULL, 499444, NULL, NULL, NULL, NULL, 5.00, 2),
(3, 12, 40, NULL, NULL, NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9231828196', NULL, 165.00, 2),
(4, 12, 39, NULL, NULL, NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9231828196', NULL, 40.00, 2),
(5, 13, 39, NULL, NULL, NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 40.00, 2),
(6, 13, 40, NULL, NULL, NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 110.00, 2),
(7, 13, 39, 485796, 'SAOW8BDUZ1L2', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 90.00, 2),
(8, 13, 35, 485796, '5L8NXBCS21P4', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 2000.00, 2),
(9, 14, 39, 485808, 'PXH3EO9082MI', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 70.00, 2),
(10, 14, 40, 485808, 'LS7VMK81CUER', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 220.00, 2),
(11, 14, 39, 485812, 'KO6VPRBFAD0T', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 80.00, 2),
(12, 14, 40, 485812, 'XW9VNPEOFUIM', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 220.00, 2),
(13, 15, 32, 485846, 'TVBAGPQESFW0', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 1000.00, 2),
(14, 15, 39, 485846, 'AJI5P17X2YLV', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 60.00, 2),
(15, 16, 39, 485856, '47082EXPQN6G', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 10.00, 2),
(16, 16, 39, 485856, 'HGBR3ID4QSX8', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 10.00, 2),
(17, 16, 39, 485856, 'KFUW5VGN16XD', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 10.00, 2),
(18, 16, 39, 485856, 'NU7V4RTX53HL', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 10.00, 2),
(19, 16, 39, 485856, 'PTHG0J12D6OM', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 10.00, 2),
(20, 16, 39, 485856, 'QD3HJ69I4M8A', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 10.00, 2),
(21, 16, 35, 485856, '0ZJIPX9HGYDT', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 1000.00, 2),
(22, 19, 39, 485872, 'BEQWPRD2I86X', NULL, NULL, NULL, NULL, 'dd', 'dd@gmail.com', '778878787878', NULL, 10.00, 2),
(23, 19, 40, 485872, '5G6KWUPR9FX7', NULL, NULL, NULL, NULL, 'dd', 'dd@gmail.com', '778878787878', NULL, 55.00, 2),
(24, 23, 40, 485949, 'SV18YETB356A', NULL, NULL, NULL, NULL, 'souvik', 'souvik@gmail.com', '9231828756', NULL, 55.00, 2),
(25, 23, 40, 485949, 'AFH7IUGZDQPR', NULL, NULL, NULL, NULL, 'souvik', 'souvik@gmail.com', '9231828756', NULL, 55.00, 2),
(26, 23, 40, 485949, '7JBAH61G9VCM', NULL, NULL, NULL, NULL, 'souvik', 'souvik@gmail.com', '9231828756', NULL, 55.00, 2),
(27, 23, 40, 485949, '0FQX3U5S8OJK', NULL, NULL, NULL, NULL, 'souvik', 'souvik@gmail.com', '9231828756', NULL, 55.00, 2),
(28, 23, 40, 485957, 'O0W4Z37HDFY9TEGP', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 55.00, 1),
(29, 23, 40, 485957, 'R3GOIFWZX6H8YJSP', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 55.00, 1),
(30, 23, 40, 485957, '5178OJ0Q6KRZWY42', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 55.00, 1),
(31, 23, 40, 485957, '8NVQB6TPLUZRM0G2', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 55.00, 1),
(32, 23, 39, 485957, '54OW32NUMK1CFLAX', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(33, 23, 39, 485957, 'SL60I7ZGDF5BNU8W', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(34, 23, 39, 485957, 'V14QAO9S62XHY3MW', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(35, 23, 39, 485957, 'IEW9NRXQUOZKC0HS', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(36, 23, 39, 485957, 'PVH1SEA67KGTDCXR', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(37, 23, 39, 485957, 'V6X1EMYK704URHNO', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(38, 23, 39, 485957, 'BC8HGTYZ1EX025VU', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(39, 23, 39, 485957, 'Q907SXRPKGMBY41U', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(40, 23, 39, 485957, 'TYJXGV9RNFMEAKC1', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(41, 23, 39, 485957, 'P7QF6WJL5DCTNRBZ', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(42, 23, 39, 485957, '0UXQ8AK3JBILT6PS', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(43, 23, 39, 485957, 'LY7Z5XESOUANWI40', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(44, 23, 39, 485957, '42TJLQO9AVGMWN1H', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 10.00, 1),
(45, 23, 35, 485957, 'JBZCGNS3DV701TEP', NULL, NULL, NULL, NULL, 'Souvik Ghosh', 'souvik.ghosh@gmail.com', '9231825975', NULL, 1000.00, 1),
(46, 27, 39, 485958, 'B41YVXZ28EO7SPRL', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 10.00, 1),
(47, 27, 39, 485958, '13IW0QF7HJ98OYTK', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 10.00, 1),
(48, 27, 39, 485958, 'ZG43QN52Y9LOH1FD', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 10.00, 1),
(49, 27, 39, 485958, 'UQRHF1YKGZ39W04B', NULL, NULL, NULL, NULL, 'Sudipto Roy', 'sudipta.roy81@gmail.com', '9231828196', NULL, 10.00, 1),
(50, 27, 40, 485958, 'YRL23184VQ6A5PEW', '2020-07-03 17:09:17', NULL, NULL, 499444, 'Souvik Ghosh', 'souvik_ghosh@gmail.com', '9231828789', NULL, 15.00, 2),
(51, 27, 40, 485958, '1JNKAVEFXSCGDH96', NULL, NULL, NULL, NULL, 'Souvik Ghosh', 'souvik_ghosh@gmail.com', '9231828789', NULL, 55.00, 1),
(52, 27, 40, 485958, 'I2XOY8UWE3JZQBKP', NULL, NULL, NULL, NULL, 'Souvik Ghosh', 'souvik_ghosh@gmail.com', '9231828789', NULL, 55.00, 1),
(53, 27, 36, 485958, '4NSFB6L9X21URDIC', NULL, NULL, NULL, NULL, 'roma nayek', 'romanayek1810@gmail.com', '9733720521', NULL, 2000.00, 1),
(54, 28, 36, 485960, '5KOWU4C8XPSJR39T', NULL, NULL, NULL, NULL, 'TEST 100 test', 'sudipta.roy81@gmail.com', '9231828196', NULL, 2000.00, 1),
(55, 28, 36, 485960, '175SMVCB3T06DI9Z', NULL, NULL, NULL, NULL, 'TEST 100 test', 'sudipta.roy81@gmail.com', '9231828196', NULL, 2000.00, 1),
(56, 28, 40, 485960, 'OM9YQH4X5S0KA81L', NULL, NULL, NULL, NULL, 'P ROy', 'proy82@gmail.com', '7981828196', NULL, 55.00, 1),
(57, 28, 40, 485960, '0OYQ5S7IMBTJN6PH', NULL, NULL, NULL, NULL, 'P ROy', 'proy82@gmail.com', '7981828196', NULL, 55.00, 1),
(58, 28, 40, 485960, 'DNJZ968KPA7GRW20', NULL, NULL, NULL, NULL, 'P ROy', 'proy82@gmail.com', '7981828196', NULL, 55.00, 1),
(59, 28, 40, 485960, 'BAJ5I2OY9RM174KN', NULL, NULL, NULL, NULL, 'P ROy', 'proy82@gmail.com', '7981828196', NULL, 55.00, 1),
(60, 29, 40, 485961, 'MGCJBA5TZN9QK371', NULL, NULL, NULL, NULL, 'TEST 100 test', 'sudipta.roy81@gmail.com', '9231828196', NULL, 55.00, 1),
(61, 29, 40, 485961, 'H5091GXB8MY3TQVE', NULL, NULL, NULL, NULL, 'TEST 100 test', 'sudipta.roy81@gmail.com', '9231828196', NULL, 55.00, 1),
(62, 29, 36, 485961, 'WXHIT5BOYM73NU6Z', NULL, NULL, NULL, NULL, 'Souvik Ghosh', 'souvik.ghosh@gmail.com', '9871828196', NULL, 2000.00, 1),
(63, 29, 36, 485961, 'BG30QSPTDY8NX71U', NULL, NULL, NULL, NULL, 'Souvik Ghosh', 'souvik.ghosh@gmail.com', '9871828196', NULL, 2000.00, 1),
(64, 30, 35, 485962, 'PGAZMFS5CN4WRQKH', NULL, NULL, NULL, NULL, 'TEST 100 test', 'sudipta.roy81@gmail.com', '9231828196', NULL, 1000.00, 1),
(65, 31, 39, 485963, 'KSTMUHYO6VIGLPDQ', NULL, NULL, NULL, NULL, 'TEST 100 test', 'sudipta.roy81@gmail.com', '9231828196', NULL, 10.00, 1),
(66, 25, 40, 485971, '6UDLOXBEF0CT59JK', NULL, NULL, NULL, NULL, 'rajesh 55.00 kumar', 'rraji3@gmail.com', '8956232145', NULL, 55.00, 1),
(67, 25, 39, 485971, 'DES91M75OXTJ4GPF', NULL, NULL, NULL, NULL, 'rajesh 55.00 kumar', 'rraji3@gmail.com', '8956232145', NULL, 10.00, 1),
(68, 33, 40, 485977, '179P3FLBD8VK0I5AE4ZH', NULL, NULL, NULL, NULL, 'TEST 100 test', 'sroywed@gmail.com', '9231828196', NULL, 55.00, 1),
(69, 35, 40, 485992, 'SXPKQ5NC4DF1TG92', NULL, NULL, NULL, NULL, 'Sudipta Mitra', 'sudiptaa008@gmail.com', '8017879076', NULL, 55.00, 1),
(70, 35, 39, 485992, 'LWFSKYUCA9H5JZR2', NULL, NULL, NULL, NULL, 'Sudipta Mitra', 'sudiptaa008@gmail.com', '8017879076', NULL, 10.00, 1),
(71, 36, 40, 485995, 'ZGA0SXD5QVWKO1N28UCE', NULL, NULL, NULL, NULL, 'test 100 test', 'ambika.sridhar030109@gmail.com', '9231828196', NULL, 55.00, 1),
(72, 36, 40, 485995, '2WBMETSZRLI6QP417XKN', NULL, NULL, NULL, NULL, 'test 100 test', 'ambika.sridhar030109@gmail.com', '9231828196', NULL, 55.00, 1),
(73, 36, 40, 485995, 'TWB6CZ7N3KH5LJMAPX4F', NULL, NULL, NULL, NULL, 'test 100 test', 'ambika.sridhar030109@gmail.com', '9231828196', NULL, 55.00, 1),
(74, 36, 40, 485995, 'JQ38O10Z7FRIGNVU26Y9', NULL, NULL, NULL, NULL, 'test 100 test', 'ambika.sridhar030109@gmail.com', '9231828196', NULL, 55.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_users`
--

CREATE TABLE `voucher_users` (
  `vus_id` int(11) NOT NULL,
  `vus_user_id` mediumint(11) DEFAULT NULL,
  `vus_vch_id` int(11) DEFAULT NULL,
  `vus_used_ctr` int(11) DEFAULT NULL,
  `vus_max_allowed` mediumint(9) DEFAULT NULL,
  `vus_valid_till` datetime DEFAULT NULL,
  `vus_active` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `voucher_users`
--

INSERT INTO `voucher_users` (`vus_id`, `vus_user_id`, `vus_vch_id`, `vus_used_ctr`, `vus_max_allowed`, `vus_valid_till`, `vus_active`) VALUES
(10, 4, 35, NULL, 2, '2020-05-08 00:00:00', 1),
(11, 3, 34, NULL, 3, '2020-06-06 00:00:00', 1),
(12, 4, 34, NULL, 3, '2020-06-03 00:00:00', 1),
(13, 16, 34, NULL, 3, '2020-07-31 00:00:00', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`vch_id`);

--
-- Indexes for table `voucher_order`
--
ALTER TABLE `voucher_order`
  ADD PRIMARY KEY (`vor_id`);

--
-- Indexes for table `voucher_order_details`
--
ALTER TABLE `voucher_order_details`
  ADD PRIMARY KEY (`vod_id`),
  ADD KEY `vod_ord_id` (`vod_ord_id`),
  ADD KEY `vod_vch_id` (`vod_vch_id`);

--
-- Indexes for table `voucher_order_invoice`
--
ALTER TABLE `voucher_order_invoice`
  ADD PRIMARY KEY (`voi_id`),
  ADD KEY `voi_vor_id` (`voi_vor_id`);

--
-- Indexes for table `voucher_order_user`
--
ALTER TABLE `voucher_order_user`
  ADD PRIMARY KEY (`vou_id`),
  ADD KEY `vou_user_id` (`vou_user_id`),
  ADD KEY `vou_order_id` (`vou_order_id`);

--
-- Indexes for table `voucher_partner`
--
ALTER TABLE `voucher_partner`
  ADD PRIMARY KEY (`vpr_id`),
  ADD KEY `vpr_vch_id` (`vpr_vch_id`);

--
-- Indexes for table `voucher_subscriber`
--
ALTER TABLE `voucher_subscriber`
  ADD PRIMARY KEY (`vsb_id`),
  ADD KEY `vsb_vhc_id` (`vsb_vch_id`);

--
-- Indexes for table `voucher_users`
--
ALTER TABLE `voucher_users`
  ADD PRIMARY KEY (`vus_id`),
  ADD KEY `vus_vch_id` (`vus_vch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `vch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `voucher_order`
--
ALTER TABLE `voucher_order`
  MODIFY `vor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `voucher_order_details`
--
ALTER TABLE `voucher_order_details`
  MODIFY `vod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `voucher_order_invoice`
--
ALTER TABLE `voucher_order_invoice`
  MODIFY `voi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `voucher_order_user`
--
ALTER TABLE `voucher_order_user`
  MODIFY `vou_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher_partner`
--
ALTER TABLE `voucher_partner`
  MODIFY `vpr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `voucher_subscriber`
--
ALTER TABLE `voucher_subscriber`
  MODIFY `vsb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `voucher_users`
--
ALTER TABLE `voucher_users`
  MODIFY `vus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `voucher_order_details`
--
ALTER TABLE `voucher_order_details`
  ADD CONSTRAINT `voucher_order_details_ibfk_1` FOREIGN KEY (`vod_ord_id`) REFERENCES `voucher_order` (`vor_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `voucher_order_details_ibfk_2` FOREIGN KEY (`vod_vch_id`) REFERENCES `vouchers` (`vch_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `voucher_order_invoice`
--
ALTER TABLE `voucher_order_invoice`
  ADD CONSTRAINT `voucher_order_invoice_ibfk_1` FOREIGN KEY (`voi_vor_id`) REFERENCES `voucher_order` (`vor_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `voucher_order_user`
--
ALTER TABLE `voucher_order_user`
  ADD CONSTRAINT `voucher_order_user_ibfk_1` FOREIGN KEY (`vou_user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `voucher_order_user_ibfk_2` FOREIGN KEY (`vou_order_id`) REFERENCES `voucher_order` (`vor_id`);

--
-- Constraints for table `voucher_partner`
--
ALTER TABLE `voucher_partner`
  ADD CONSTRAINT `voucher_partner_ibfk_1` FOREIGN KEY (`vpr_vch_id`) REFERENCES `vouchers` (`vch_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `voucher_subscriber`
--
ALTER TABLE `voucher_subscriber`
  ADD CONSTRAINT `voucher_subscriber_ibfk_8` FOREIGN KEY (`vsb_vch_id`) REFERENCES `vouchers` (`vch_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `voucher_users`
--
ALTER TABLE `voucher_users`
  ADD CONSTRAINT `voucher_users_ibfk_1` FOREIGN KEY (`vus_vch_id`) REFERENCES `vouchers` (`vch_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
