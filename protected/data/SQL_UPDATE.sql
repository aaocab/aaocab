--6-July-2020-------------ROY----------------


ALTER TABLE `payment_gateway` ADD `apg_model_id` INT(11) NULL DEFAULT NULL AFTER `apg_trans_id`, ADD `apg_model_type` TINYINT(4) NULL DEFAULT NULL COMMENT '1=> booking , 2=> Voucher, 3=> Vendor' AFTER `apg_model_id`;



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


-------------------------------------------------------------

--6-July-2020-------------SUBHRADIP----------------
CREATE TABLE `teams` (
  `tea_id` smallint(5) UNSIGNED NOT NULL,
  `tea_name` varchar(200) NOT NULL,
  `tea_status` tinyint(4) NOT NULL DEFAULT 1,
  `tea_created` datetime NOT NULL,
  `tea_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Holds team details';

INSERT INTO `teams` (`tea_id`, `tea_name`, `tea_status`, `tea_created`, `tea_modified`) VALUES
(1, 'Retail Sales', 1, '2020-06-04 12:25:29', '2020-06-04 12:25:29'),
(2, 'Software', 1, '2020-06-04 12:25:29', '2020-06-04 12:25:29'),
(3, 'Vendor Onboarding', 1, '2020-06-04 12:25:29', '2020-06-04 12:25:29'),
(4, 'Dispatch', 1, '2020-06-04 12:25:29', '2020-06-04 12:25:29'),
(5, 'Customer support', 1, '2020-06-04 12:25:29', '2020-06-04 12:25:29'),
(6, 'Customer/Vendor Chat', 1, '2020-06-04 12:25:29', '2020-06-04 12:25:29'),
(7, 'Vendor Training', 1, '2020-06-04 12:25:29', '2020-06-04 12:25:29'),
(8, 'Adwords', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(9, 'Vendor support', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(10, 'Corp Sales', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(11, 'General Accounts', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(12, 'Staff - Peon', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(13, 'Vendor Advocacy', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(14, 'Customer Advocacy', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(15, 'Digital Marketing', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(16, 'General Compliance', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(17, 'Shuttle/Package Support', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(18, 'Price Analyst', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(19, 'Front Desk', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(20, 'Business Development', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(21, 'Corp Accounts', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(22, 'IT Operations', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(23, 'SEO', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(24, 'Exec', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(25, 'Admin', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(26, 'HR', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(27, 'Field Operations- South', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(28, 'Field Operations- East/NE', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(29, 'Field Operations- West', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(30, 'Field Operations- North', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(31, 'Business Development- East/NE', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(32, 'Business Development- North', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(33, 'Business Development- South', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(34, 'Business Development- West', 1, '2020-06-04 12:25:30', '2020-06-04 12:25:30'),
(35, 'Analysis', 1, '2020-06-04 00:00:00', '2020-06-04 00:00:00');

ALTER TABLE `teams`
  ADD PRIMARY KEY (`tea_id`);
ALTER TABLE `teams`
  MODIFY `tea_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;


CREATE TABLE `cat_depart_team_map` (
  `cdt_id` mediumint(8) UNSIGNED NOT NULL,
  `cdt_cat_id` smallint(5) UNSIGNED NOT NULL,
  `cdt_dpt_id` mediumint(8) UNSIGNED NOT NULL,
  `cdt_tea_id` smallint(5) UNSIGNED NOT NULL,
  `cdt_status` tinyint(4) NOT NULL DEFAULT 1,
  `cdt_created` datetime NOT NULL,
  `cdt_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Holds catagory depatment team map';

--
-- Dumping data for table `cat_depart_team_map`
--

INSERT INTO `cat_depart_team_map` (`cdt_id`, `cdt_cat_id`, `cdt_dpt_id`, `cdt_tea_id`, `cdt_status`, `cdt_created`, `cdt_modified`) VALUES
(1, 2, 3, 2, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(2, 3, 7, 26, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(3, 1, 4, 14, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(4, 3, 6, 16, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(5, 1, 1, 27, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(6, 1, 2, 10, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(7, 1, 2, 1, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(8, 3, 6, 11, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(9, 1, 1, 3, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(10, 1, 4, 5, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(11, 1, 1, 28, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(12, 3, 7, 12, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(13, 1, 1, 4, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(14, 1, 1, 30, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(15, 1, 4, 9, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(16, 1, 1, 0, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(17, 1, 1, 9, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(18, 1, 1, 7, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(19, 2, 3, 22, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(20, 1, 4, 13, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(21, 1, 1, 29, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(22, 3, 7, 25, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(23, 1, 2, 31, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(24, 1, 1, 6, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(25, 1, 5, 35, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(26, 3, 6, 21, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(27, 2, 3, 18, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(28, 3, 7, 19, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(29, 1, 2, 32, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(30, 1, 2, 33, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(31, 1, 5, 15, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(32, 1, 5, 8, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43'),
(33, 1, 2, 34, 1, '2020-06-04 12:41:43', '2020-06-04 12:41:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cat_depart_team_map`
--
ALTER TABLE `cat_depart_team_map`
  ADD PRIMARY KEY (`cdt_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cat_depart_team_map`
--
ALTER TABLE `cat_depart_team_map`
  MODIFY `cdt_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

CREATE TABLE `designation` (
  `des_id` mediumint(8) UNSIGNED NOT NULL,
  `des_name` varchar(200) NOT NULL,
  `des_status` tinyint(4) NOT NULL DEFAULT 1,
  `des_created` datetime NOT NULL,
  `des_modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Master designation table';

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`des_id`, `des_name`, `des_status`, `des_created`, `des_modified`) VALUES
(1, 'Executive Assistant - Level I', 1, '2020-07-06 13:08:32', '2020-07-06 13:08:32'),
(2, 'Executive Assistant - Level II', 1, '2020-07-06 13:08:32', '2020-07-06 13:08:32'),
(3, 'Executive Assistant - Sr. Level I ', 1, '2020-07-06 13:08:32', '2020-07-06 13:08:32'),
(4, 'Executive Assistant - Sr. Level II', 1, '2020-07-06 13:08:32', '2020-07-06 13:08:32'),
(5, 'Associate - Level I', 1, '2020-07-06 13:08:32', '2020-07-06 13:08:32'),
(6, 'Associate - Level II', 1, '2020-07-06 13:08:32', '2020-07-06 13:08:32'),
(7, 'Associate - Sr. Level I ', 1, '2020-07-06 13:08:32', '2020-07-06 13:08:32'),
(8, 'Associate - Sr. Level II', 1, '2020-07-06 13:08:32', '2020-07-06 13:08:32'),
(9, 'Senior Associate - Level I', 1, '2020-07-06 13:08:32', '2020-07-06 13:08:32'),
(10, 'Senior Associate - Level II', 1, '2020-07-06 13:08:32', '2020-07-06 13:08:32'),
(11, 'Senior Associate - Sr. Level I ', 1, '2020-07-06 13:08:32', '2020-07-06 13:08:32'),
(12, 'Senior Associate - Sr. Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(13, 'Lead - Level I', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(14, 'Lead - Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(15, 'Lead - Sr. Level I ', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(16, 'Lead - Sr. Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(17, 'Group Lead - Level I', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(18, 'Group Lead - Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(19, 'Group Lead - Sr. Level I ', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(20, 'Group Lead - Sr. Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(21, 'Manager - Level I', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(22, 'Manager - Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(23, 'Manager - Sr. Level I ', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(24, 'Manager - Sr. Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(25, 'Asst Vice President - Level I', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(26, 'Asst Vice President - Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(27, 'Vice President  - Level I', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(28, 'Vice President  - Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(29, 'Vice President  - Sr. Level I ', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(30, 'Vice President  - Sr. Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(31, 'Technology Expert - Level I', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(32, 'Technology Expert - Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(33, 'Technology Expert - Sr. Level I ', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(34, 'Technology Expert - Sr. Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(35, 'Technology Master - Level I', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(36, 'Technology Master - Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(37, 'Technology Master - Sr. Level I ', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(38, 'Technology Master - Sr. Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(39, 'Distinguished Expert - Level I', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(40, 'Distinguished Expert - Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(41, 'Distinguished Expert - Sr. Level I ', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33'),
(42, 'Distinguished Expert - Sr. Level II', 1, '2020-07-06 13:08:33', '2020-07-06 13:08:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`des_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `des_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

CREATE TABLE `departments` (
  `dpt_id` mediumint(8) UNSIGNED NOT NULL,
  `dpt_name` varchar(200) NOT NULL,
  `dpt_status` tinyint(4) NOT NULL DEFAULT 1,
  `dpt_created` datetime NOT NULL,
  `dpt_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Holds department tables';

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`dpt_id`, `dpt_name`, `dpt_status`, `dpt_created`, `dpt_modified`) VALUES
(1, 'Operations', 1, '2020-06-01 16:37:36', '2020-06-01 16:37:36'),
(2, 'Sales', 1, '2020-06-01 16:37:36', '2020-06-01 16:37:36'),
(3, 'Engineering', 1, '2020-06-01 16:37:36', '2020-06-01 16:37:36'),
(4, 'Support', 1, '2020-06-01 16:37:36', '2020-06-01 16:37:36'),
(5, 'Marketing', 1, '2020-06-01 16:37:36', '2020-06-01 16:37:36'),
(6, 'Finance', 1, '2020-06-01 16:37:36', '2020-06-01 16:37:36'),
(7, 'Admin', 1, '2020-06-01 16:37:36', '2020-06-01 16:37:36'),
(8, 'Exec', 1, '2020-06-01 16:37:36', '2020-06-01 16:37:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`dpt_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `dpt_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

CREATE TABLE `categories` (
  `cat_id` smallint(5) UNSIGNED NOT NULL,
  `cat_name` varchar(200) NOT NULL,
  `cat_status` tinyint(4) NOT NULL DEFAULT 1,
  `cat_created` datetime NOT NULL,
  `cat_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Holds catagory details';

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`, `cat_status`, `cat_created`, `cat_modified`) VALUES
(1, 'SO&M', 1, '2020-06-01 16:37:36', '2020-06-01 16:37:36'),
(2, 'R&D', 1, '2020-06-01 16:37:36', '2020-06-01 16:37:36'),
(3, 'G&A', 1, '2020-06-01 16:37:36', '2020-06-01 16:37:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

CREATE TABLE `admin_profiles` (
  `adp_id` int(10) UNSIGNED NOT NULL,
  `adp_adm_id` int(10) UNSIGNED NOT NULL,
  `adp_emp_code` varchar(100) DEFAULT NULL,
  `adp_hiring_date` date DEFAULT NULL,
  `adp_depart_date` date DEFAULT NULL,
  `adp_designation_id` varchar(100) DEFAULT NULL,
  `adp_team_leader_id` int(10) UNSIGNED DEFAULT NULL,
  `adp_cdt_id` int(10) UNSIGNED DEFAULT NULL,
  `adp_location` varchar(200) DEFAULT NULL,
  `adp_status` tinyint(4) NOT NULL DEFAULT 1,
  `adp_created` datetime NOT NULL,
  `adp_modified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='holds admin profiles';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_profiles`
--
ALTER TABLE `admin_profiles`
  ADD PRIMARY KEY (`adp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_profiles`
--
ALTER TABLE `admin_profiles`
  MODIFY `adp_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
---------------------------------------------------------------------------------------------
-------------------------------------------------------------

--9-July-2020-------------PUJA----------------
ALTER TABLE `booking_track`  ADD `btk_is_selfie` TINYINT NOT NULL DEFAULT '0' COMMENT '1:have;0:not have'  AFTER `btk_drv_details_viewed_datetime`,  ADD `btk_is_sanitization_kit` TINYINT NOT NULL DEFAULT '0' COMMENT '1:have;0:not have'  AFTER `btk_is_selfie`,  ADD `btk_aarogya_setu` TINYINT NOT NULL DEFAULT '0' COMMENT '1:have;0:not have'  AFTER `btk_is_sanitization_kit`;
ALTER TABLE `booking_track` ADD `btk_safetyterm_agree` TINYINT NOT NULL DEFAULT '0' COMMENT '1:agreed;2:notagreed' AFTER `btk_aarogya_setu`;
ALTER TABLE `booking_track` CHANGE `btk_safetyterm_agree` `btk_safetyterm_agree` VARCHAR(1000) NULL DEFAULT NULL;
----------------------------------------------------------------------------

---13---July--2020-------Pankaj----------------

CREATE TABLE `assign_log` (
  `alg_id` int(11) NOT NULL,
  `alg_user_id` int(11) DEFAULT NULL,
  `alg_event_id` mediumint(9) NOT NULL,
  `alg_role_id` int(11) DEFAULT NULL,
  `alg_role_contact_id` int(11) DEFAULT NULL,
  `alg_ref_id` int(11) NOT NULL,
  `alg_ref_type` smallint(6) NOT NULL COMMENT '1:lead;2:booking;3:callmeback',
  `alg_associated_record` varchar(255) DEFAULT NULL,
  `alg_adm_user_id` int(11) NOT NULL,
  `alg_adm_user_type` smallint(6) NOT NULL,
  `alg_desc` varchar(255) DEFAULT NULL,
  `alg_notes` varchar(255) DEFAULT NULL,
  `alg_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `alg_active` tinyint(4) NOT NULL DEFAULT 1,
  `alg_status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE call_status ADD cst_type TINYINT NOT NULL DEFAULT '1' COMMENT 'default:1;leadcall2' AFTER cst_modified;

ALTER TABLE call_status ADD cst_csr_id INT(11) NULL DEFAULT NULL COMMENT 'csr id' AFTER cst_type;

ALTER TABLE call_status ADD UNIQUE(cst_id);

ALTER TABLE call_status DROP PRIMARY KEY;

ALTER TABLE call_status ADD cst_call_id INT(11) NOT NULL FIRST;

ALTER TABLE call_status ADD PRIMARY KEY(cst_call_id);

ALTER TABLE call_status CHANGE cst_call_id cst_call_id INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE call_status CHANGE cst_id cst_id VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE cst_lead_id cst_lead_id VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE cst_phone_code cst_phone_code VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE cst_phone cst_phone VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE cst_did cst_did VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE cst_agent_name cst_agent_name VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE cst_recording_file_name cst_recording_file_name VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE cst_group cst_group VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE cst_camp cst_camp VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

---------------------------------------------------------------------------------------------------------------------------------------------

---16---July--2020-------Ankesh----------------
ALTER TABLE `vendor_vehicle` ADD `vvhc_is_lou_required` TINYINT NOT NULL DEFAULT '0' AFTER `vvhc_lou_approveby`;
UPDATE vendor_vehicle SET vvhc_lou_approved = 0  WHERE `vvhc_lou_approved` = 3 AND `vvhc_owner_license_id` IS NOT NULL AND `vvhc_owner_pan_id` IS NOT NULL

---------------------------------------------------------------------------------------------------------------------------------------------------------

------27---July---2020----Pankaj---------------
ALTER TABLE `assign_log` ADD `alg_csr_rank` INT(11) NULL DEFAULT NULL AFTER `alg_status`;

--------------------------------------------------------------------------------------------------------

------28---July---2020----Ankesh---------------

ALTER TABLE `contact` ADD `ctt_is_name_dl_match` TINYINT(4) NOT NULL DEFAULT '0' AFTER `ctt_police_doc_id`;
ALTER TABLE `contact` CHANGE `ctt_is_name_dl_match` `ctt_is_name_dl_matched` TINYINT(4) NOT NULL DEFAULT '0';

--------------------------------------------------------------------------------------------------------


--29 JULY --2020--------SUBHRADIP----------------
TRUNCATE TABLE `admin_profiles`;
INSERT INTO `admin_profiles` (`adp_id`, `adp_adm_id`, `adp_emp_code`, `adp_hiring_date`, `adp_depart_date`, `adp_designation_id`, `adp_team_leader_id`, `adp_cdt_id`, `adp_location`, `adp_status`, `adp_created`, `adp_modified`) VALUES
(1, 1, '150004', '2016-01-02', NULL, NULL, 8, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(2, 2, '150007', '2015-01-11', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(3, 3, '150009', '2015-01-11', NULL, NULL, 8, 2, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(4, 4, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(5, 5, '150013', '2015-07-12', NULL, NULL, 8, 3, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(6, 6, '190281', '2019-10-06', NULL, NULL, 158, 3, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(7, 7, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(8, 8, '150001', NULL, NULL, NULL, 8, 36, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(9, 9, '150010', '2016-02-01', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(10, 10, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(11, 11, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(12, 12, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(13, 13, '150005', '2016-01-02', NULL, NULL, 8, 4, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(14, 14, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(15, 15, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(16, 16, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(17, 17, '150012', '2015-07-11', NULL, NULL, 575, 5, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(18, 18, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(19, 19, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(20, 20, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(21, 21, '150100', NULL, NULL, NULL, 8, 32, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(22, 22, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(23, 23, '150015', '2016-02-15', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(24, 24, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(25, 25, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(26, 26, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(27, 27, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(28, 28, '150016', '2016-01-01', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(29, 29, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(30, 30, '170102', '2015-01-11', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(31, 31, '150017', '2016-03-15', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(32, 32, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(33, 33, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(34, 34, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(35, 35, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(36, 36, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(37, 37, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(38, 38, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(39, 39, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(40, 40, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(41, 41, '160102', '2016-11-04', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(42, 42, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(43, 43, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(44, 44, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(45, 45, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(46, 46, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(47, 47, '150014', '2016-04-29', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(48, 48, '190356', '2019-11-16', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(49, 49, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(50, 50, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(51, 51, '190332', '2019-10-18', NULL, NULL, 373, 8, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(52, 52, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(53, 53, '160103', '2016-06-13', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(54, 54, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(55, 55, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(56, 56, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(57, 57, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(58, 58, '160105', '2016-09-08', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(59, 59, '160107', '2016-10-08', NULL, NULL, 8, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(60, 60, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(61, 61, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(62, 62, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(63, 63, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(64, 64, '160106', '2016-08-26', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(65, 65, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(66, 66, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(67, 67, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(68, 68, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(69, 69, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(70, 70, '160104', '2016-04-08', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(71, 71, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(72, 72, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(73, 73, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(74, 74, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(75, 76, '160108', '2016-11-16', NULL, NULL, 575, 5, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(76, 77, '160109', '2016-11-17', NULL, NULL, 8, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(77, 78, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(78, 79, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(79, 80, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(80, 81, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(81, 82, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(82, 84, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(83, 85, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(84, 86, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(85, 87, '150006', '2015-01-11', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(86, 88, '160110', '2016-12-31', NULL, NULL, 13, 11, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(87, 89, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(88, 90, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(89, 91, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(90, 93, '160111', '2017-06-02', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(91, 94, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(92, 95, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(93, 97, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(94, 98, '160113', '2016-04-25', NULL, NULL, 112, 14, 'Gurgaon', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(95, 99, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(96, 100, '180171', '2018-10-31', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(97, 101, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(98, 102, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(99, 103, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(100, 104, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(101, 105, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(102, 106, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(103, 107, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(104, 108, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(105, 109, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(106, 110, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(107, 111, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(108, 112, '170101', '2017-08-05', NULL, NULL, 8, 14, 'Gurgaon', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(109, 113, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(110, 114, '0', NULL, '2019-10-11', NULL, 13, 12, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(111, 115, '170104', '2017-06-21', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(112, 116, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(113, 117, '170107', '2017-08-22', NULL, NULL, 373, 8, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(114, 118, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(115, 119, '170106', '2017-08-21', NULL, NULL, 112, 14, 'Chandigarh', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(116, 120, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(117, 121, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(118, 122, '170109', '2017-11-09', NULL, NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(119, 123, '170110', '2017-12-09', NULL, NULL, 112, 37, 'Gurgaon', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(120, 124, '170112', '2017-04-10', NULL, NULL, 112, 14, 'Gurgaon', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(121, 125, '170200', NULL, '2019-11-30', NULL, 13, 11, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(122, 126, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(123, 127, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(124, 128, '170113', '2017-04-11', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(125, 129, '170108', '2017-01-09', NULL, NULL, 158, 3, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(126, 130, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(127, 131, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(128, 132, '170115', '2017-11-11', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(129, 133, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(130, 134, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(131, 135, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(132, 136, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(133, 137, '170117', '2017-11-15', NULL, NULL, 13, 11, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(134, 138, '170118', '2017-11-18', NULL, NULL, 575, 11, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(135, 139, '170119', '2017-11-20', NULL, NULL, 112, 14, 'Dehradun', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(136, 140, '170114', '2017-06-11', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(137, 141, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(138, 142, '170121', '2017-05-12', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(139, 143, '170120', NULL, '2020-01-30', NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(140, 144, '170122', '2017-06-12', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(141, 145, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(142, 146, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(143, 147, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(144, 148, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(145, 149, '180102', '2018-04-01', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(146, 150, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(147, 151, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(148, 152, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(149, 153, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(150, 154, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(151, 155, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(152, 156, '190227', NULL, NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(153, 157, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(154, 158, '180106', '2018-06-03', NULL, NULL, 8, 3, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(155, 159, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(156, 160, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(157, 161, '180107', '2018-03-14', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(158, 162, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(159, 163, '170103', '2016-07-27', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(160, 164, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(161, 165, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(162, 166, '180108', '2018-03-21', NULL, NULL, 373, 8, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(163, 167, '180109', '2018-03-23', '2020-05-01', NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(164, 168, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(165, 169, '180110', '2018-03-30', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(166, 170, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(167, 171, '180112', '2018-07-04', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(168, 172, '180113', '2018-10-04', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(169, 173, '180115', '2018-12-04', NULL, NULL, 8, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(170, 174, '180114', '2018-10-04', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(171, 175, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(172, 176, '180116', '2018-04-13', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(173, 177, '180117', '2018-04-30', NULL, NULL, 8, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(174, 178, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(175, 179, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(176, 180, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(177, 181, '180119', '2018-11-05', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(178, 182, '180102', '2018-11-05', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(179, 183, '170111', '2017-09-13', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(180, 184, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(181, 186, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(182, 187, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(183, 188, '180103', '2018-02-15', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(184, 189, '180124', '2018-05-18', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(185, 190, '180300', '2018-05-16', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(186, 191, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(187, 192, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(188, 193, '180123', NULL, NULL, NULL, 467, 7, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(189, 194, '180125', '2018-05-21', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(190, 195, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(191, 196, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(192, 197, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(193, 198, '180127', '2018-05-23', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(194, 199, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(195, 200, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(196, 201, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(197, 202, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(198, 203, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(199, 204, '180128', '2018-01-06', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(200, 205, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(201, 206, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(202, 207, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(203, 208, '170116', '2017-11-13', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(204, 209, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(205, 210, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(206, 211, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(207, 212, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(208, 213, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(209, 214, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(210, 215, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(211, 216, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(212, 217, '180131', '2018-07-13', NULL, NULL, 8, 18, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(213, 218, '180133', '2018-07-20', '2020-05-01', NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(214, 219, '180132', '2018-07-18', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(215, 220, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(216, 221, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(217, 222, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(218, 223, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(219, 224, '180135', '2018-07-25', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(220, 225, '180134', '2018-07-24', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(221, 226, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(222, 227, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(223, 228, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(224, 231, '180136', '2018-07-31', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(225, 235, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(226, 237, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(227, 241, '180139', '2018-08-13', '2020-05-01', NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(228, 246, '180138', '2018-01-08', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(229, 249, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(230, 253, '180141', '2018-08-28', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(231, 257, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(232, 261, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(233, 265, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(234, 269, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(235, 271, '180142', '2018-03-09', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(236, 273, '180143', '2018-03-09', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(237, 277, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(238, 281, '180145', '2018-11-09', NULL, NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(239, 285, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(240, 287, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(241, 291, '180148', '2018-09-19', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(242, 295, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(243, 297, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(244, 301, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(245, 303, '180150', '2018-09-26', '2020-05-01', NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(246, 307, '180151', '2018-09-28', NULL, NULL, 177, 20, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(247, 311, '180153', '2018-01-10', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(248, 315, '180155', '2018-03-10', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(249, 319, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(250, 323, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(251, 325, '180154', '2018-03-10', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(252, 327, '180152', '2018-09-29', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(253, 329, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(254, 333, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(255, 337, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(256, 343, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(257, 345, '180161', '2018-11-10', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(258, 347, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(259, 351, '180162', '2018-11-10', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(260, 355, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(261, 359, '180130', '2018-04-06', NULL, NULL, 1, 19, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(262, 363, '180164', '2018-10-23', NULL, NULL, 158, 3, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(263, 365, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(264, 367, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(265, 369, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(266, 371, '180163', '2018-10-18', NULL, NULL, 112, 14, 'Gurgaon', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(267, 373, '180166', '2018-10-24', NULL, NULL, 8, 8, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(268, 377, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(269, 379, '180167', '2018-10-25', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(270, 381, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(271, 383, '180168', '2018-10-25', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(272, 387, '180165', '2018-10-23', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(273, 389, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(274, 393, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(275, 397, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(276, 401, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(277, 405, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(278, 407, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(279, 411, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(280, 415, '180173', '2018-03-11', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(281, 417, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(282, 421, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(283, 425, '180175', '2018-09-11', NULL, NULL, 177, 20, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(284, 429, '180178', '2018-12-11', NULL, NULL, 13, 4, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(285, 433, '180174', '2018-08-11', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(286, 435, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(287, 439, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(288, 443, '180176', '2018-09-11', NULL, NULL, 13, 21, 'Nashik', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(289, 445, '180177', '2018-10-11', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(290, 449, '180180', '2018-11-13', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(291, 453, '180182', '2018-11-14', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(292, 455, '180181', '2018-11-14', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(293, 459, '190255', '2019-01-05', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(294, 463, '180179', '2018-11-13', NULL, NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(295, 467, '180184', '2018-11-16', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(296, 471, '190294', '2016-01-02', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(297, 472, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(298, 473, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(299, 474, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(300, 475, '180191', '2018-11-22', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(301, 476, '180183', '2018-11-15', NULL, NULL, 373, 8, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(302, 477, '180190', '2018-11-22', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(303, 478, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(304, 479, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(305, 480, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(306, 481, '180189', '2018-11-22', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(307, 482, '180188', '2018-11-22', NULL, NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(308, 483, '180186', '2018-11-22', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(309, 484, '180187', '2018-11-22', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(310, 485, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(311, 486, '180193', '2018-03-12', NULL, NULL, 8, 22, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(312, 487, '180195', '2018-04-12', NULL, NULL, 5, 3, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(313, 488, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(314, 489, '180196', '2018-04-12', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(315, 490, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(316, 491, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(317, 492, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(318, 493, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(319, 494, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(320, 495, '180197', '2018-12-12', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(321, 496, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(322, 497, '180199', '2018-12-17', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(323, 498, '180200', '2018-12-17', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(324, 499, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(325, 500, '180210', '2018-12-17', NULL, NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(326, 501, '180201', '2018-12-26', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(327, 502, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(328, 503, '180202', '2018-12-26', NULL, NULL, 177, 24, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(329, 504, '180203', '2018-12-26', NULL, NULL, 177, 24, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(330, 505, '190205', '2019-03-01', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(331, 506, '190206', '2019-03-01', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(332, 507, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(333, 508, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(334, 509, '190212', '2019-10-01', NULL, NULL, 13, 26, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(335, 510, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(336, 511, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(337, 512, '190218', '2019-10-01', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(338, 513, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(339, 514, '190217', '2019-10-01', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(340, 515, '190216', '2019-10-01', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(341, 516, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(342, 517, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(343, 518, '190214', '2019-10-01', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(344, 519, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(345, 520, '190213', '2019-10-01', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(346, 521, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(347, 522, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(348, 523, '190244', '2019-01-14', NULL, NULL, 13, 21, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(349, 524, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(350, 525, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(351, 526, '190220', '2019-01-14', NULL, NULL, 177, 24, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(352, 527, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(353, 528, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(354, 529, '190222', '2019-01-14', '2020-05-01', NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(355, 530, '190223', '2019-01-16', NULL, NULL, 13, 27, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(356, 531, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(357, 532, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(358, 533, '190224', '2019-01-21', NULL, NULL, 177, 24, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(359, 534, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(360, 535, '190217', '2019-01-02', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(361, 536, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(362, 537, '190292', '2019-04-02', NULL, NULL, 509, 26, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(363, 538, '190218', NULL, NULL, NULL, 173, 6, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(364, 539, '190219', '2019-02-15', NULL, NULL, 13, 4, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(365, 540, '190209', '2019-08-01', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(366, 541, '190220', '2019-02-22', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(367, 542, '190221', '2019-02-22', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(368, 543, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(369, 544, '190222', '2019-02-28', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(370, 545, '190223', '2019-08-03', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(371, 546, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(372, 547, '190226', '2019-02-04', NULL, NULL, 8, 27, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(373, 548, '190225', NULL, '2020-09-01', NULL, 575, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(374, 549, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(375, 550, '190232', '2019-03-04', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(376, 551, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(377, 552, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(378, 553, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(379, 554, '190230', '2019-03-04', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(380, 555, '190229', NULL, '2019-12-27', NULL, 467, 7, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(381, 556, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(382, 557, '190233', '2019-08-04', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(383, 558, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(384, 559, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(385, 560, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(386, 561, '190234', NULL, NULL, NULL, 467, 7, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(387, 562, '190237', '2019-09-04', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(388, 563, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(389, 564, '190236', NULL, '2020-01-22', NULL, 467, 7, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(390, 565, '190239', '2019-04-17', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(391, 566, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(392, 567, '190240', '2019-04-20', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(393, 568, '190241', '2019-04-20', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(394, 569, '190242', '2019-04-23', '2020-05-01', NULL, 112, 14, 'Gurgaon', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(395, 570, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(396, 571, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(397, 572, '190257', '2019-04-05', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(398, 573, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(399, 574, '190258', '2019-04-05', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(400, 575, '190256', '2019-02-05', NULL, NULL, 8, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(401, 576, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(402, 577, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(403, 578, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(404, 579, '190259', '2019-09-05', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(405, 580, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(406, 581, '190260', '2019-10-05', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(407, 582, '190261', '2019-10-05', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(408, 583, '190262', '2019-10-05', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(409, 584, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(410, 585, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(411, 586, '190264', '2019-05-17', NULL, NULL, 177, 24, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(412, 587, '190266', '2019-05-17', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(413, 588, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(414, 589, '190268', '2019-05-20', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(415, 590, '190269', '2019-05-20', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(416, 591, '190271', '2019-05-23', '2020-05-01', NULL, 177, 20, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(417, 592, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(418, 593, '190274', '2019-05-28', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(419, 594, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(420, 595, '190273', '2019-05-27', NULL, NULL, 173, 6, 'Bangalore', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(421, 596, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(422, 597, '190277', '2019-07-06', NULL, NULL, 177, 24, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(423, 598, '190278', '2019-07-06', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(424, 599, '190279', NULL, '2019-04-12', NULL, 467, 7, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(425, 600, '190276', '2019-06-06', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(426, 601, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(427, 602, '190283', '2019-10-06', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(428, 603, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(429, 604, '190282', '2019-10-06', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(430, 605, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(431, 606, '190286', '2019-06-20', NULL, NULL, 8, 34, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(432, 607, '190287', '2019-06-20', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(433, 608, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(434, 609, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(435, 610, '190301', '2019-02-07', NULL, NULL, 177, 24, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(436, 611, '190302', '2019-11-07', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(437, 612, '190303', '2019-11-07', NULL, NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(438, 613, '190304', '2019-11-07', '2020-05-01', NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(439, 614, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(440, 615, '190295', '2019-07-23', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(441, 616, '0', NULL, NULL, NULL, 467, 7, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(442, 617, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(443, 618, '190305', '2019-01-08', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(444, 619, '190308', '2019-06-08', NULL, NULL, 373, 8, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(445, 620, '190310', '2019-08-16', NULL, NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(446, 621, '190331', '2019-06-08', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(447, 622, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(448, 623, '190312', '2019-08-23', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(449, 624, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(450, 625, '190313', '2019-08-23', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(451, 626, '190314', NULL, NULL, NULL, 467, 7, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(452, 627, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(453, 628, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(454, 629, '160296', '2019-07-24', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(455, 630, '190316', '2019-08-30', '2020-05-01', NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(456, 631, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(457, 632, '190319', '2019-04-09', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(458, 633, '190317', NULL, '2020-08-01', NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(459, 634, '190320', '2019-12-09', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(460, 635, '190323', '2019-08-07', NULL, NULL, 112, 14, 'Gurgaon', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(461, 636, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(462, 637, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(463, 638, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(464, 639, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(465, 640, NULL, '2019-08-18', NULL, NULL, 13, 5, 'Hyderabad', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(466, 641, '190327', '2019-09-27', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(467, 642, '190328', '2019-09-27', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(468, 643, '190329', '2019-09-27', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(469, 644, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(470, 645, '190326', NULL, '2020-01-18', NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(471, 646, '190324', NULL, '2020-01-18', NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(472, 647, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(473, 648, '190346', '2019-09-10', '2020-05-01', NULL, 13, 5, 'Bangalore', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(474, 649, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(475, 650, '190333', NULL, '2019-12-20', NULL, 467, 7, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34');
INSERT INTO `admin_profiles` (`adp_id`, `adp_adm_id`, `adp_emp_code`, `adp_hiring_date`, `adp_depart_date`, `adp_designation_id`, `adp_team_leader_id`, `adp_cdt_id`, `adp_location`, `adp_status`, `adp_created`, `adp_modified`) VALUES
(476, 651, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(477, 652, '190334', NULL, NULL, NULL, 77, 10, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(478, 653, '190335', '2019-10-18', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(479, 654, '190336', '2019-10-18', NULL, NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(480, 655, '190337', '2019-10-18', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(481, 656, '190341', '2019-01-11', NULL, NULL, 177, 20, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(482, 657, '190338', '2019-10-24', '2020-05-01', NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(483, 658, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(484, 659, '190339', '2019-10-01', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(485, 660, '190340', '2019-10-29', NULL, NULL, 13, 28, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(486, 661, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(487, 662, '190344', '2019-08-11', '2020-05-01', NULL, 158, 3, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(488, 663, '190345', '2019-08-11', '2020-05-01', NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(489, 664, '190400', NULL, '2019-11-30', NULL, 467, 7, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(490, 665, '190347', '2019-08-11', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(491, 666, '190348', NULL, '2019-12-20', NULL, 467, 7, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(492, 667, '190349', '2019-08-11', NULL, NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(493, 668, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(494, 669, '190351', '2019-08-11', '2020-05-01', NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(495, 670, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(496, 671, '190352', '2019-11-16', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(497, 672, '190353', '2019-11-16', '2020-05-01', NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(498, 673, '190354', '2019-11-16', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(499, 674, '190355', '2019-11-16', '2020-05-01', NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(500, 675, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(501, 676, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(502, 677, '190357', '2019-11-16', '2020-05-01', NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(503, 678, '190359', '2019-11-18', NULL, NULL, 373, 8, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(504, 679, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(505, 680, '190361', '2019-11-25', NULL, NULL, 173, 29, 'Gurgaon', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(506, 681, '190219', '2019-10-01', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(507, 682, '190342', '2019-01-11', NULL, NULL, 1, 1, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(508, 683, '190363', '2019-11-28', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(509, 684, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(510, 685, '190365', '2019-11-28', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(511, 686, '190366', '2019-11-28', '2020-05-01', NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(512, 690, '190367', '2019-11-28', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(513, 691, '190368', '2019-11-28', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(514, 692, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(515, 693, '190369', '2019-05-12', NULL, NULL, 373, 8, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(516, 694, '190371', '2019-12-13', NULL, NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(517, 695, '190372', '2019-12-13', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(518, 696, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(519, 697, '190373', '2019-12-13', '2020-05-01', NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(520, 698, '190374', '2019-12-18', NULL, NULL, 173, 30, 'Bangalore', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(521, 699, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(522, 700, '190370', '2019-12-19', NULL, NULL, 8, 31, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(523, 701, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(524, 702, '190375', '2019-12-23', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(525, 703, '190376', '2019-12-23', NULL, NULL, 575, 13, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(526, 704, '190290', '2020-02-01', NULL, NULL, 59, 9, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(527, 705, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(528, 706, '200101', '2020-06-01', NULL, NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(529, 707, '200102', '2020-06-01', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(530, 708, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(531, 709, '200103', '2020-06-01', '2020-05-01', NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(532, 710, '200104', '2020-06-01', NULL, NULL, 177, 15, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(533, 711, '200105', '2020-07-01', '2020-05-01', NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(534, 712, '200107', '2020-01-18', NULL, NULL, 177, 20, 'Kolkata', 1, '2020-07-28 23:55:34', '2020-07-28 23:55:34'),
(535, 713, '200108', '2020-01-18', '2020-05-01', NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(536, 714, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(537, 715, '200114', '2020-01-18', NULL, NULL, 177, 20, 'Kolkata', 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(538, 716, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(539, 717, '200109', '2020-01-18', '2020-05-01', NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(540, 718, '200110', '2020-01-18', NULL, NULL, 77, 10, 'Kolkata', 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(541, 719, '200111', '2020-01-18', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(542, 720, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(543, 721, '200113', '2020-01-18', '2020-05-01', NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(544, 722, '200112', '2020-01-18', NULL, NULL, 467, 7, 'Kolkata', 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(545, 725, '200115', '2020-01-21', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(546, 726, '200106', '2020-01-15', NULL, NULL, 173, 6, 'Bangalore', 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(547, 727, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(548, 728, '190306', '2019-01-08', NULL, NULL, 173, 6, 'Kolkata', 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(549, 729, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(550, 730, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35'),
(551, 731, '200122', NULL, NULL, NULL, 173, 6, 'Mumbai', 1, '2020-07-28 23:55:35', '2020-07-28 23:55:35');
COMMIT;

TRUNCATE TABLE `cat_depart_team_map`;
INSERT INTO `cat_depart_team_map` (`cdt_id`, `cdt_cat_id`, `cdt_dpt_id`, `cdt_tea_id`, `cdt_status`, `cdt_created`, `cdt_modified`) VALUES
(1, 2, 3, 2, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(2, 3, 7, 26, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(3, 1, 4, 14, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(4, 3, 6, 16, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(5, 1, 1, 27, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(6, 1, 2, 10, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(7, 1, 2, 1, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(8, 3, 6, 11, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(9, 1, 1, 3, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(10, 1, 4, 5, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(11, 1, 1, 28, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(12, 3, 7, 12, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(13, 1, 1, 4, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(14, 1, 1, 30, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(15, 1, 4, 9, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(18, 1, 1, 7, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(19, 2, 3, 22, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(20, 1, 4, 13, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(21, 1, 1, 29, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(22, 3, 7, 25, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(23, 1, 2, 31, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(24, 1, 1, 6, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(25, 1, 5, 35, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(26, 3, 6, 21, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(27, 2, 3, 18, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(28, 3, 7, 19, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(29, 1, 2, 32, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(30, 1, 2, 33, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(31, 1, 5, 15, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(32, 1, 5, 8, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(33, 1, 2, 34, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(34, 1, 1, 17, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(35, 1, 5, 23, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(36, 3, 8, 24, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(37, 1, 1, 36, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00'),
(38, 1, 2, 37, 1, '2020-06-04 12:41:00', '2020-06-04 12:41:00');
COMMIT;


DROP TABLE IF EXISTS `designation`;
CREATE TABLE `designation` (
  `des_id` mediumint(8) UNSIGNED NOT NULL,
  `des_org_stack` float UNSIGNED DEFAULT NULL,
  `des_name` varchar(200) NOT NULL,
  `des_is_manage` varchar(255) DEFAULT NULL,
  `des_status` tinyint(4) NOT NULL DEFAULT 1,
  `des_created` datetime NOT NULL,
  `des_modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Master designation table';

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`des_id`, `des_org_stack`, `des_name`, `des_is_manage`, `des_status`, `des_created`, `des_modified`) VALUES
(1, 8.1, 'Executive Assistant - Level I', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(2, 8.2, 'Executive Assistant - Level II', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(3, 8.3, 'Executive Assistant - Sr. Level I ', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(4, 8.4, 'Executive Assistant - Sr. Level II', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(5, 8.1, 'Associate - Level I', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(6, 8.2, 'Associate - Level II', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(7, 8.3, 'Associate - Sr. Level I ', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(8, 8.4, 'Associate - Sr. Level II', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(9, 7.1, 'Senior Associate - Level I', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(10, 7.2, 'Senior Associate - Level II', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(11, 7.3, 'Senior Associate - Sr. Level I ', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(12, 7.4, 'Senior Associate - Sr. Level II', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(13, 7.1, 'Lead - Level I', '(2-4)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(14, 7.2, 'Lead - Level II', '(2-4)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(15, 7.3, 'Lead - Sr. Level I ', '(2-4)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(16, 7.4, 'Lead - Sr. Level II', '(2-4)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(17, 6.1, 'Group Lead - Level I', '(4-6)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(18, 6.2, 'Group Lead - Level II', '(4-6)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(19, 6.3, 'Group Lead - Sr. Level I ', '(4-6)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(20, 6.4, 'Group Lead - Sr. Level II', '(4-6)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(21, 5.1, 'Manager - Level I', '(max 10)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(22, 5.2, 'Manager - Level II', '(max 10)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(23, 5.3, 'Manager - Sr. Level I ', '(max 10)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(24, 5.4, 'Manager - Sr. Level II', '(max 10)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(25, 4.1, 'Asst Vice President - Level I', '(manages group leads)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(26, 4.2, 'Asst Vice President - Level II', '(manages group leads and managers)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(27, 2.1, 'Vice President  - Level I', '(manages managers)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(28, 2.2, 'Vice President  - Level II', '(manages managers)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(29, 2.3, 'Vice President  - Sr. Level I ', '(manages managers)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(30, 2.4, 'Vice President  - Sr. Level II', '(manages managers)', 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(31, 6.1, 'Technology Expert - Level I', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(32, 6.2, 'Technology Expert - Level II', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(33, 6.3, 'Technology Expert - Sr. Level I', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(34, 6.4, 'Technology Expert - Sr. Level II', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(35, 4.1, 'Technology Master - Level I', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(36, 4.2, 'Technology Master - Level II', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(37, 4.3, 'Technology Master - Sr. Level I ', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(38, 4.4, 'Technology Master - Sr. Level II', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(39, 2.1, 'Distinguished Expert - Level I', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(40, 2.2, 'Distinguished Expert - Level II', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(41, 2.3, 'Distinguished Expert - Sr. Level I ', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05'),
(42, 2.4, 'Distinguished Expert - Sr. Level II', NULL, 1, '2020-07-28 21:38:05', '2020-07-28 21:38:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`des_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `des_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

TRUNCATE TABLE `teams`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `gozo_00529`
--

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`tea_id`, `tea_name`, `tea_status`, `tea_created`, `tea_modified`) VALUES
(1, 'Retail Sales', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(2, 'Software', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(3, 'Vendor Onboarding', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(4, 'Dispatch', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(5, 'Customer support', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(6, 'Customer/Vendor Chat', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(7, 'Vendor Training', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(8, 'Adwords', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(9, 'Vendor support', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(10, 'Corp Sales', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(11, 'General Accounts', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(12, 'Staff - Peon', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(13, 'Vendor Advocacy', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(14, 'Customer Advocacy', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(15, 'Digital Marketing', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(16, 'General Compliance', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(17, 'Shuttle/Package Support', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(18, 'Price Analyst', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(19, 'Front Desk', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(20, 'Business Development', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(21, 'Corp Accounts', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(22, 'IT Operations', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(23, 'SEO', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(24, 'Exec', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(25, 'Admin', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(26, 'HR', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(27, 'Field Operations- South', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(28, 'Field Operations- East/NE', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(29, 'Field Operations- West', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(30, 'Field Operations- North', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(31, 'Business Development- East/NE', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(32, 'Business Development- North', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(33, 'Business Development- South', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(34, 'Business Development- West', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(35, 'Analysis', 1, '2020-06-04 00:00:00', '2020-06-04 00:00:00'),
(36, 'Field Operations- Central', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00'),
(37, 'Business Development- Central', 1, '2020-06-04 12:25:00', '2020-06-04 12:25:00');
COMMIT;


30-07-2020 ----------------- ROY  ----------------------------------------

ALTER TABLE `voucher_order` ADD `vor_user_id` INT(11) NULL DEFAULT NULL AFTER `vor_number`;
ALTER TABLE `vouchers` CHANGE `vch_desc` `vch_desc` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;


---------------------------------------------------------------------------


---------------------------------------------------------------------------



-- SUVAJIT - 30-07-2020
-- --------------------------------------------------------

--
-- Table structure for table `follow_ups`
--

CREATE TABLE `follow_ups` (
  `fwp_id` int(11) NOT NULL,
  `fwp_platform` tinyint(3) UNSIGNED DEFAULT NULL,
  `fwp_contact_id` int(11) DEFAULT NULL,
  `fwp_ref_id` int(11) DEFAULT NULL,
  `fwp_ref_type` int(11) DEFAULT NULL,
  `fwp_call_entity_type` tinyint(2) NOT NULL COMMENT 'Call to customer or vendor or driver',
  `fwp_team_id` int(10) UNSIGNED DEFAULT NULL,
  `fwp_desc` varchar(1000) DEFAULT NULL,
  `fwp_prefered_time` datetime DEFAULT NULL,
  `fwp_assigned_csr` int(11) DEFAULT NULL,
  `fwp_csr_assign_time` datetime DEFAULT NULL,
  `fwp_csr_remarks` varchar(255) DEFAULT NULL,
  `fwp_follow_up_time` datetime DEFAULT NULL,
  `fwp_follow_up_by` int(11) UNSIGNED DEFAULT NULL,
  `fwp_follow_up_status` tinyint(2) UNSIGNED DEFAULT 0,
  `fwp_status` int(11) NOT NULL DEFAULT 1,
  `fwp_created` datetime DEFAULT NULL,
  `fwp_modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Used for follow up scheduling';



--
-- Indexes for dumped tables
--

--
-- Indexes for table `follow_ups`
--
ALTER TABLE `follow_ups`
  ADD PRIMARY KEY (`fwp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `follow_ups`
--
ALTER TABLE `follow_ups`
  MODIFY `fwp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
----------------------------------------------------------------------------------



-- --------------------------------------------------------

--
-- Table structure for table `followup_log`
--

CREATE TABLE `followup_log` (
  `fpl_id` int(10) UNSIGNED NOT NULL,
  `fpl_fwp_id` int(11) NOT NULL,
  `fpl_remarks` varchar(2000) NOT NULL,
  `fpl_event_id` tinyint(4) NOT NULL,
  `fpl_user_type` tinyint(4) NOT NULL,
  `fpl_user_id` int(10) UNSIGNED NOT NULL,
  `fpl_create_date` datetime NOT NULL,
  `fpl_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='holds follow up event details';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `followup_log`
--
ALTER TABLE `followup_log`
  ADD PRIMARY KEY (`fpl_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `followup_log`
--
ALTER TABLE `followup_log`
  MODIFY `fpl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-------------------------------------------------------------------------------------------
---01---Aug--2020-------Ankesh----------------

ALTER TABLE `contact` ADD `ctt_is_name_pan_matched` TINYINT NOT NULL DEFAULT '0' AFTER `ctt_is_name_dl_matched`;

-------------------------------------------------------------------------------------------

-------------------------------------------------------------------------------------------
---04---Aug--2020-------rakesh----------------
 
CREATE TABLE `auto_cancel_rule` (
  `acr_id` int(11) NOT NULL,
  `acr_demsupmisfire` tinyint(4) DEFAULT NULL,
  `acr_cs` float DEFAULT NULL,
  `acr_rule_rank` smallint(6) NOT NULL,
  `acr_is_assigned` tinyint(4) DEFAULT NULL,
  `acr_is_allocated` tinyint(4) DEFAULT NULL,
  `acr_bkg_type` varchar(255) DEFAULT NULL,
  `acr_addresses_given` tinyint(4) DEFAULT NULL,
  `acr_service_tier` varchar(255) DEFAULT NULL,
  `acr_time_create` int(11) DEFAULT NULL,
  `acr_time_to_pickup` int(11) DEFAULT NULL,
  `acr_time_confirm` int(11) DEFAULT NULL,
  `acr_time_bidstarted` int(11) DEFAULT NULL,
  `acr_auto_cancel_value` tinyint(4) NOT NULL DEFAULT 1,
   `acr_auto_cancel_code` tinyint(4) NOT NULL,
  `acr_status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

ALTER TABLE `auto_cancel_rule`  ADD PRIMARY KEY (`acr_id`);
  
  
ALTER TABLE `auto_cancel_rule`  MODIFY `acr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-------------------------------------------------------------------------------------------

---05--Aug--2020---------Pankaj------------------

ALTER TABLE `agents` ADD `agt_effective_credit_limit` MEDIUMINT(9) NOT NULL DEFAULT '0' AFTER `agt_pref_req_other`;
ALTER TABLE `agents` ADD `agt_effective_overdue_days` MEDIUMINT(9) NULL DEFAULT '0' AFTER `agt_effective_credit_limit`;
ALTER TABLE `agents` ADD `agt_grace_days` MEDIUMINT(9) NULL DEFAULT '0' AFTER `agt_effective_overdue_days`;
ALTER TABLE `agents` CHANGE `agt_effective_overdue_days` `agt_overdue_days` MEDIUMINT(9) NULL DEFAULT '0';
ALTER TABLE `agents` CHANGE `agt_effective_credit_limit` `agt_effective_credit_limit` MEDIUMINT(9) NULL DEFAULT '0';
ALTER TABLE `agents` CHANGE `agt_effective_credit_limit` `agt_effective_credit_limit` INT(11) NULL DEFAULT '0';
UPDATE agents SET agt_overdue_days = 30;
UPDATE agents SET agt_effective_credit_limit = agt_credit_limit;

---011--Aug--2020---------Rakesh------------------

CREATE TABLE `cancellation_policy_rule` (
  `cpr_id` int(11) NOT NULL,
  `cpr_charge` float DEFAULT NULL,
  `cpr_hours` int(11) DEFAULT NULL,
  `cpr_is_working_hour` tinyint(4) DEFAULT NULL COMMENT '0=>Calender Hour, 1 =>Working Hour',
  `cpr_service_tier` varchar(255) DEFAULT NULL,
  `cpr_mark_initiator` varchar(255) DEFAULT NULL COMMENT '1=>Consumer, 2 => Admin, 3 =>Vendor, 4=> Driver,5=>System,6=>partner',
  `cpr_status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

ALTER TABLE `cancellation_policy_rule`  ADD PRIMARY KEY (`cpr_id`);
ALTER TABLE `cancellation_policy_rule`  MODIFY `cpr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

---====---11--Aug--2020------------------------Puja behalf of Madhumita----------------------------------------------------------------------------------


--
-- Table structure for table `vendor_boost`
--

CREATE TABLE `vendor_boost` (
  `vbt_id` int(11) NOT NULL,
  `vbt_vendor_id` int(11) NOT NULL,
  `vbt_vhc_id` varchar(150) NOT NULL,
  `vbt_mailing_address` text NOT NULL,
  `vbt_sticker_sent_date` datetime DEFAULT NULL,
  `vbt_sticker_received` tinyint(4) DEFAULT NULL COMMENT '0=>Pending, 1=>Received, 2=> Not Received',
  `vbt_sticker_received_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vendor_boost`
--
ALTER TABLE `vendor_boost`
  ADD PRIMARY KEY (`vbt_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vendor_boost`
--
ALTER TABLE `vendor_boost`
  MODIFY `vbt_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;



-----------------------------------------------------------------------------------------------------
---12--Aug--2020---------Sudhansu behalf Deepak------------------------------------------------------
Table:payment_gateway
---------------------------------
apg_first_api_status_type => ALTER TABLE `payment_gateway` ADD `apg_first_api_status_type` TINYINT NOT NULL DEFAULT '0' AFTER `apg_active`; 

apg_first_api_status => ALTER TABLE `payment_gateway` ADD `apg_first_api_status` TINYINT NOT NULL DEFAULT '0' AFTER `apg_first_api_status_type`;

apg_last_api_status_type => ALTER TABLE `payment_gateway` ADD `apg_last_api_status_type` TINYINT NOT NULL DEFAULT '0' AFTER `apg_first_api_status`;

apg_first_response_details => ALTER TABLE `payment_gateway` ADD `apg_first_response_details` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `apg_start_datetime`;

apg_first_response_time => ALTER TABLE `payment_gateway` ADD `apg_first_response_time` DATETIME NULL DEFAULT NULL AFTER `apg_response_details`;


ALTER TABLE `payment_gateway` CHANGE `apg_last_api_status_type` `apg_last_api_status_type` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '1=> soft , 2=> hard';

-------------------------------------------------------------------------------------------------------------------------
--------------------------17-Aug---2020-----------------Pankaj-----------------------------------------------

ALTER TABLE `agents` ADD `agt_approved_untill_date` DATETIME NULL AFTER `agt_grace_days`;
ALTER TABLE `agents` ADD `agt_approved_by` INT(11) NULL AFTER `agt_approved_untill_date`;
ALTER TABLE `agent_rel` ADD `arl_operating_managers` VARCHAR(250) NULL AFTER `arl_driver_license_path`;

----------------------------------------------------------------------------------------------------------------------------
ALTER TABLE `payment_gateway` CHANGE `apg_last_api_status_type` `apg_last_api_status_type` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '1=> soft , 2=> hard';


---====---13--Aug--2020------------------------ Madhumita- behalf of Rituparna---------------------------------------------------------------------------------

ALTER TABLE vendor_boost ADD vbt_sticker_count_send INT(20) NULL DEFAULT '0' AFTER vbt_sticker_sent_date;LE vendor_boost ADD vbt_sticker_count_send INT(20) NULL DEFAULT '0' AFTER vbt_sticker_sent_date;
ALTER TABLE vendor_boost CHANGE vbt_sticker_count_send vbt_sticker_send_count INT(20) NULL DEFAULT '0';


---====---19--Aug--2020------------------------ Madhumita- ---------------------------------------------------------------------------------

ALTER TABLE vendor_boost ADD vbt_tracking_number VARCHAR(150) NULL AFTER vbt_sticker_received_date;
ALTER TABLE vendor_boost ADD vbt_delivered_courier ENUM('yes','no') NOT NULL DEFAULT 'no' AFTER vbt_tracking_number;
ALTER TABLE vendor_boost CHANGE vbt_delivered_courier vbt_delivered_courier TINYINT NOT NULL DEFAULT '0' COMMENT '0=off,1=on';

----------------------------------------------------------------------------------------------
--------20----Aug---2020--------Pankaj---------------------------

UPDATE agents 
INNER JOIN agent_rel ON agt_id = agent_rel.arl_agt_id
SET agent_rel.arl_operating_managers = agt_admin_id
WHERE agt_admin_id IS NOT NULL

------------------------------------------------------------------------


---- 24 AUG 2020-----------------------------ROY --------------------------------------------

CREATE TABLE `config` (
  `cfg_id` int(11) NOT NULL,
  `cfg_name` varchar(255) DEFAULT NULL,
  `cfg_value` varchar(255) DEFAULT NULL,
  `cfg_env` varchar(30) NOT NULL DEFAULT '',
  `cfg_description` varchar(100) NOT NULL,
  `cfg_active` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`cfg_id`, `cfg_name`, `cfg_value`, `cfg_env`, `cfg_description`, `cfg_active`) VALUES
(1, 'CUST_PHONE_ADMIN_VISIBLE', '1', '', 'Customer phone number visible to admin users based on this. 0: Not Visible, 1: Visible', 1),
(2, 'booking.pickup.mintime.0', '120', '', 'Minimum pickup time for all trip type', 1),
(3, 'booking.pickup.mintime.1', '120', '', 'Minimum pickup time for one way booking', 1),
(4, 'booking.score.critical', '88', '', '', 1),
(5, 'booking.score.manual', '84', '', '', 1),
(6, 'booking.assignment.margin.default', '2', '', '', 1),
(7, 'booking.assignment.margin.manual', '0', '', '', 1),
(8, 'booking.assignment.margin.critical', '-5', '', '', 1),
(10, 'booking.assignment.margin.critical', '-7', 'development2', '', 1),
(11, 'booking.pickup.mintime.7', '12', '', 'Minimum pickup time for Shuttle', 1),
(12, 'cities.32007.booking.pickup.mintime.4', '120', '', 'Minimum pickup time for Hyderabad Airport (Airport Transfer)', 1),
(13, 'cities.31001.booking.pickup.mintime.4', '120', '', 'Minimum pickup time for Bangalore Airport (Airport Transfer)', 1),
(14, 'user.referral.invitee.type', '1', '', '1 => Percentage, 2 => Fixed', 1),
(15, 'user.referral.invitee.value', '20', '', '', 1),
(16, 'user.referral.invitee.max', '0', '', '', 1),
(19, 'user.referral.invitee.min', '0', '', '', 1),
(21, 'user.referral.invitee.calType', '1', '', '1=>Percentage, 2=>Fixed', 1),
(23, 'user.referral.limitType', '1', '', '0 = actual,\r\n1 = minimum of invitee/joiner,\r\n2 = maximum of invitee/joiner', 1),
(24, 'user.referral.joiner.type', '1', '', '1=> Percentage, 2=> Fixed', 1),
(25, 'user.referral.joiner.value', '0', '', '', 1),
(26, 'user.referral.joiner.max', '0', '', '', 1),
(27, 'user.referral.joiner.min', '0', '', '', 1),
(28, 'user.referral.joiner.calType', '1', '', '1=>Percentage, 2=>Fixed', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`cfg_id`),
  ADD UNIQUE KEY `cfg_name_2` (`cfg_name`,`cfg_env`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `cfg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;


ALTER TABLE `users` ADD `usr_referred_id` INT(11) NULL DEFAULT NULL AFTER `usr_referred_code`;


 -- ***************** Update user referral id ************************
UPDATE  `users` b 	
    INNER JOIN `users` a ON a.usr_refer_code=b.usr_referred_code
    SET b.usr_referred_id=a.user_id
	WHERE b.usr_referred_code != ''

-- ****************** JOING BONUS LEDGER created *******************
INSERT INTO `account_ledger` (`ledgerId`, `accountGroupId`, `ledgerName`, `openingBalance`, `isDefault`, `crOrDr`, `narration`, `mailingName`, `address`, `phone`, `mobile`, `email`, `creditPeriod`, `creditLimit`, `pricinglevelId`, `billByBill`, `tin`, `cst`, `pan`, `routeId`, `bankAccountNumber`, `branchName`, `branchCode`, `extraDate`, `extra1`, `extra2`, `areaId`) VALUES (51, 15, 'Joining Bonus', '0.00000', 1, 'Dr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-01-03 00:00:00', NULL, NULL, NULL)


---------------------------------------------------

-- SUVAJIT - 25-08-2020 - Alter Queries for Follow Up/ Follow Up Log/ Call Status tables
-- START
  ALTER TABLE `call_status` ADD `cst_ref_id` INT UNSIGNED NULL DEFAULT NULL AFTER `cst_lead_id`, ADD `cst_ref_type` TINYINT NULL DEFAULT NULL AFTER `cst_ref_id`;

  ALTER TABLE `call_status` CHANGE `cst_type` `cst_type` TINYINT(4) NOT NULL DEFAULT '1' COMMENT 'default:1;leadcall2: 3=>Followup';

  ALTER TABLE `call_status` CHANGE `cst_ref_type` `cst_ref_record` TINYINT(4) NULL DEFAULT NULL;

  ALTER TABLE `call_status` CHANGE `cst_ref_record` `cst_ref_record` TINYINT(4) NULL DEFAULT '1';

  ALTER TABLE `follow_ups` CHANGE `fwp_follow_up_status` `fwp_follow_up_status` TINYINT(2) UNSIGNED NULL DEFAULT '0' COMMENT '1=>Auto Assigned, 2=>Manually Assigned, 3=>FollowUp Transfer, 4=>FollowUp Completed';

  ALTER TABLE `followup_log` CHANGE `fpl_remarks` `fpl_remarks` VARCHAR(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
-- END
---------------------------------------------------------------------------------------

-- ******Deepak**** 01-09-2020 ******** Enables user to use his wallet *******************
INSERT INTO `config` (`cfg_id`, `cfg_name`, `cfg_value`, `cfg_env`, `cfg_description`, `cfg_active`) VALUES (NULL, 'user.useWallet', '1', '', 'Enables user to use his wallet as primary payment instrument.', '1');
---------------------------------------------------------------------------------------

--------------------------------------------------------------
MADHUMITA 22-09
ALTER TABLE `vendor_pref` ADD `vnp_vhc_boost_count` INT NOT NULL DEFAULT '0' AFTER `vnp_is_allowed_tier`; 
ALTER TABLE `vendor_pref` ADD `vnp_boost_enabled` TINYINT NOT NULL DEFAULT '0' AFTER `vnp_vhc_boost_count`; 

ALTER TABLE `vehicle_stats` ADD `vhs_boost_enabled` TINYINT NOT NULL DEFAULT '0' AFTER `vhs_active`; 
ALTER TABLE `vehicle_stats` ADD `vhs_boost_approved_date` DATE NULL AFTER `vhs_boost_enabled`; 
ALTER TABLE `vehicle_stats` ADD `vhs_boost_expiry_date` DATE NULL AFTER `vhs_boost_approved_date`; 

--------------------------

---------------------Pankaj----------------------25-09-2020---------------------------------

ALTER TABLE `account_trans_details` ADD `adt_modified` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL AFTER `adt_addt_params`;

UPDATE `account_trans_details`
INNER JOIN account_transactions ON act_id = adt_trans_id 
SET adt_modified = act_date

ALTER TABLE `partner_stats` ADD `pts_ledger_balance` FLOAT(16,2) NULL DEFAULT '0.00' AFTER `pts_24hours_booking`, ADD `pts_wallet_balance` FLOAT(16,2) NULL DEFAULT '0.00' AFTER `pts_ledger_balance`;

----------------------------------------------------------------- Madhumita 07/10/2020-----------------------------------------------------------


CREATE DEFINER=`root`@`localhost` FUNCTION `CalculateSMT` (`vendorAmount` INT, `expectedVendorAmount` INT, `bidAmount` INT, `rating` FLOAT, `stickScore` INT, `penaltyCount` INT, `driverAppUsed` FLOAT, `vrsDependency` FLOAT, `vrsBoostPercentage` FLOAT) RETURNS FLOAT BEGIN
  DECLARE bidScore float;
  DECLARE ratingStick float;
  DECLARE margin float;
  DECLARE SMT float;
  DECLARE overallScore float;
  DECLARE totalRating VARCHAR(20);
  DECLARE boostBid float;
  
 
  SET rating = IFNULL(rating, 4.5);
  SET totalRating = rating - (penaltyCount * 0.25);
  SET stickScore =  IFNULL(stickScore, 100);
  SET boostBid = bidAmount - vrsBoostPercentage;
  SET bidScore = (expectedVendorAmount/boostBid) - 1;
  SET ratingStick = ((rating * stickScore)/5) * 100;
  SET margin = ((expectedVendorAmount-bidAmount)/vendorAmount)*100;
  SET SMT = margin + ratingStick;

  SET overallScore = bidScore * (( totalRating * stickScore) + driverAppUsed+vrsDependency)/3;
  RETURN overallScore;
END$$


---------------------------------------------------------Deepak 09/10/20------------------------------------------------------

SET FOREIGN_KEY_CHECKS=0; 
START TRANSACTION; 

CREATE TABLE `partner_airport_transfer` (
  `pat_id` int(11) NOT NULL,
  `pat_city_id` int(11) NOT NULL,
  `pat_transfer_type` tinyint(4) NOT NULL COMMENT '1=> pickup, 2=> dropoff',
  `pat_vehicle_type` tinyint(3) UNSIGNED NOT NULL,
  `pat_vendor_amount` smallint(5) UNSIGNED NOT NULL,
  `pat_total_fare` mediumint(8) UNSIGNED NOT NULL,
  `pat_minimum_km` smallint(5) UNSIGNED NOT NULL,
  `pat_extra_per_km_rate` float NOT NULL,
  `pat_partner_id` int(11) NOT NULL,
  `pat_active` tinyint(4) NOT NULL DEFAULT 1,
  `pat_log` text NOT NULL,
  `pat_created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `pat_modified_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
ALTER TABLE `partner_airport_transfer`
  ADD PRIMARY KEY (`pat_id`);
 
ALTER TABLE `partner_airport_transfer`
  MODIFY `pat_id` int(11) NOT NULL AUTO_INCREMENT;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

----------------------------------------------------Madhumita----------------------------------------------------

ALTER TABLE `vehicle_stats` ADD `vhs_verify_car` SMALLINT NOT NULL DEFAULT '0' AFTER `vhs_boost_expiry_date`, ADD `vhs_verification_date` DATETIME NULL AFTER `vhs_verify_car`, ADD `vhs_verify_bkgId` INT NULL AFTER `vhs_verification_date`;

ALTER TABLE `booking_track`  ADD `bkg_force_verification` TINYINT NOT NULL DEFAULT '0' COMMENT '0=>no_verify,1=>ask _for_verify'  AFTER `btk_safetyterm_agree`;
----------------------------------------------------------------------------------------------------------------
------------------------------Deepak-----30/12/20----------------
ALTER TABLE `follow_ups` ADD `fwp_contact_phone_no` VARCHAR(15) NULL DEFAULT NULL AFTER `fwp_contact_id`;


----------------------------------------------------------------------------------------------------------------
------------------------------Deepak-----07/01/21----------------

ALTER TABLE `call_status` ADD INDEX( `cst_ref_id`); 

ALTER TABLE `admin_profiles` ADD INDEX( `adp_adm_id`);
ALTER TABLE `admin_profiles` ADD INDEX( `adp_cdt_id`);

ALTER TABLE `cat_depart_team_map` ADD INDEX( `cdt_cat_id`);
ALTER TABLE `cat_depart_team_map` ADD INDEX( `cdt_dpt_id`);
ALTER TABLE `cat_depart_team_map` ADD INDEX( `cdt_tea_id`);


ALTER TABLE `assign_log` CHANGE `alg_created` `alg_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `assign_log` ADD `alg_closed_at` TIMESTAMP NULL AFTER `alg_status`;


------------------- Pankaj 18/03/2021-------------------

CREATE TABLE `penalty_rules` (
  `plt_id` int(11) NOT NULL,
  `plt_code` int(11) NOT NULL,
  `plt_desc` text DEFAULT NULL,
  `plt_entity_type` tinyint(4) NOT NULL COMMENT 'vendor=2',
  `plt_event_id` int(11) NOT NULL COMMENT 'penalty type',
  `plt_min_value` int(11) NOT NULL,
  `plt_max_value` int(11) NOT NULL,
  `plt_value` float(16,2) NOT NULL,
  `plt_value_type` tinyint(4) NOT NULL COMMENT 'percent=1,fixed=2',
  `plt_rules` text DEFAULT NULL,
  `plt_active` tinyint(4) NOT NULL DEFAULT 1,
  `plt_create_date` datetime NOT NULL,
  `plt_modify_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `penalty_rules` (`plt_id`, `plt_code`, `plt_desc`, `plt_entity_type`, `plt_event_id`, `plt_min_value`, `plt_max_value`, `plt_value`, `plt_value_type`, `plt_rules`, `plt_active`, `plt_create_date`, `plt_modify_date`) VALUES
(1, 101, 'Not allocated Cab/Driver in specified time', 2, 201, 200, 200, 200.00, 2, NULL, 0, '2021-02-12 16:12:50', '2021-02-12 16:14:28'),
(2, 102, 'OTP not verified', 2, 202, 200, 200, 200.00, 2, NULL, 1, '2021-02-12 16:16:23', '2021-02-12 16:17:00'),
(5, 103, 'Ride not completed by driver for booking ID', 2, 203, 200, 200, 200.00, 2, NULL, 0, '2021-02-12 16:18:29', '2021-02-12 16:19:08'),
(6, 104, 'Ride start overdue for booking ID', 2, 204, 200, 200, 200.00, 2, NULL, 1, '2021-02-12 16:19:09', '2021-02-12 16:19:56'),
(7, 105, 'Late OTP verification of booking', 2, 205, 200, 200, 200.00, 2, '{\r\n  \"range\": {\r\n    \"minimumCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"maximumCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"diffrentCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"minimumDistance\": \"\",\r\n    \"maximumDistance\": \"\",\r\n    \"diffrentDistance\": \"\"\r\n  },\r\n  \"time\": {\r\n    \"minimumCharge\": {\r\n      \"value\": \"50\",\r\n      \"type\": \"2\"\r\n    },\r\n    \"maximumCharge\": {\r\n      \"value\": \"200\",\r\n      \"type\": \"2\"\r\n    },\r\n    \"diffrentCharge\": {\r\n      \"value\": \"100\",\r\n      \"type\": \"2\"\r\n    },\r\n    \"minimumTime\": \"0\",\r\n    \"maximumTime\": \"120\",\r\n    \"diffrentTime\": \"30\"\r\n  }\r\n}', 1, '2021-02-12 16:19:56', '2021-02-12 16:21:41'),
(8, 106, 'Vendor freeze due to Cab verification failed by System', 2, 206, 2000, 2000, 2000.00, 2, NULL, 0, '2021-02-12 16:21:41', '2021-02-12 16:25:22'),
(9, 107, 'Arrived location and pickup location are different', 2, 207, 200, 200, 200.00, 2, NULL, 0, '2021-02-12 16:25:22', '2021-02-12 16:26:45'),
(10, 108, 'Late complete booking by app', 2, 208, 200, 200, 200.00, 2, NULL, 0, '2021-02-12 16:29:59', '2021-02-12 16:30:25'),
(11, 109, 'Auto-Penalized of booking for cancellation reason is car no show.', 2, 209, 1000, 1000, 1000.00, 2, NULL, 1, '2021-02-12 16:30:25', '2021-02-12 16:32:15'),
(12, 110, 'Driver arrived late', 2, 210, 50, 300, 300.00, 2, '{\r\n  \"range\": {\r\n    \"minimumCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"maximumCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"differentCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"minimumDistance\": \"\",\r\n    \"maximumDistance\": \"\",\r\n    \"differentDistance\": \"\"\r\n  },\r\n  \"time\": {\r\n    \"minimumCharge\": {\r\n      \"value\": \"50\",\r\n      \"type\": \"2\"\r\n    },\r\n    \"maximumCharge\": {\r\n      \"value\": \"300\",\r\n      \"type\": \"2\"\r\n    },\r\n    \"differentCharge\": {\r\n      \"value\": \"75\",\r\n      \"type\": \"2\"\r\n    },\r\n    \"minimumTime\": \"15\",\r\n    \"maximumTime\": \"60\",\r\n    \"differentTime\": \"30\"\r\n  }\r\n}', 1, '2021-02-17 16:44:43', '2021-02-17 16:46:57'),
(13, 111, 'Driver arrived far from pickup location', 2, 211, 50, 200, 200.00, 2, '{\r\n  \"range\": {\r\n    \"minimumCharge\": {\r\n      \"value\": \"50\",\r\n      \"type\": \"2\"\r\n    },\r\n    \"maximumCharge\": {\r\n      \"value\": \"0.10\",\r\n      \"type\": \"1\"\r\n    },\r\n    \"diffrentCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"minimumDistance\": \"3\",\r\n    \"maximumDistance\": \"6\",\r\n    \"diffrentDistance\": \"\"\r\n  },\r\n  \"time\": {\r\n    \"minimumCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"maximumCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"diffrentCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"minimumTime\": \"\",\r\n    \"maximumTime\": \"\",\r\n    \"diffrentTime\": \"\"\r\n  }\r\n}', 1, '2021-02-17 16:46:58', '2021-02-17 16:49:02'),
(14, 112, 'Vendor unassigned', 2, 212, 500, 2000, 2000.00, 2, '{\r\n  \"range\": {\r\n    \"minimumCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"maximumCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"diffrentCharge\": {\r\n      \"value\": \"\",\r\n      \"type\": \"\"\r\n    },\r\n    \"minimumDistance\": \"\",\r\n    \"maximumDistance\": \"\",\r\n    \"diffrentDistance\": \"\"\r\n  },\r\n  \"time\": {\r\n    \"minimumCharge\": {\r\n      \"value\": \"500\",\r\n      \"pvalue\": \"0.25\",\r\n      \"type\": \"1\"\r\n    },\r\n    \"maximumCharge\": {\r\n      \"value\": \"2000\",\r\n      \"pvalue\": \"\",\r\n      \"type\": \"2\"\r\n    },\r\n    \"diffrentCharge_1\": {\r\n      \"value\": \"1000\",\r\n      \"pvalue\": \"0.5\",\r\n      \"type\": \"1\"\r\n    },\r\n    \"diffrentCharge_2\": {\r\n      \"value\": \"1500\",\r\n      \"pvalue\": \"0.75\",\r\n      \"type\": \"1\"\r\n    },\r\n    \"minimumAssignedWorkingHours\": \"0\",\r\n    \"maximumAssignedWorkingHours\": \"4\",\r\n    \"minimumPickupWorkingHours\": \"2\",\r\n    \"maximumPickupWorkingHours\": \"12\",\r\n    \"diffrentPickupWorkingHours_1\": \"4\",\r\n    \"diffrentPickupWorkingHours_2\": \"8\"\r\n  }\r\n}', 1, '2021-02-17 16:49:06', '2021-02-17 16:50:06'),
(15, 113, 'Unregistered vehicle', 2, 213, 1000, 1000, 1000.00, 2, NULL, 1, '2021-02-17 16:50:09', '2021-02-17 16:52:09'),
(16, 114, 'Unregistered Driver', 2, 214, 1000, 1000, 1000.00, 2, NULL, 1, '2021-02-17 16:52:12', '2021-02-17 16:52:56');

ALTER TABLE `penalty_rules`
  ADD PRIMARY KEY (`plt_id`),
  ADD UNIQUE KEY `plt_event_id` (`plt_event_id`),
  ADD UNIQUE KEY `plt_code` (`plt_code`),
  ADD UNIQUE KEY `plt_id` (`plt_id`);

ALTER TABLE `penalty_rules`
  MODIFY `plt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
  
  ------------------------------------------------------RAMALA----14/06/2021----------------------------------------------
  
  ALTER TABLE `booking_trail` ADD `btr_vendor_last_unassigned` DATETIME NULL DEFAULT NULL AFTER `btr_api_sync_error`;
  
  
  -----------------------------------------------RAMALA---30/06/2021--------------------------------------------------------
  ALTER TABLE `vendor_stats` ADD `vrs_pending_cars` INT NULL DEFAULT '0' AFTER `vrs_3mnth_rejected_trip`;
ALTER TABLE `vendor_stats` ADD `vrs_pending_drivers` INT NULL DEFAULT '0' AFTER `vrs_pending_cars`;
ALTER TABLE `vendor_stats` ADD `vrs_rejected_cars` INT NULL DEFAULT '0' AFTER `vrs_pending_drivers`;
ALTER TABLE `vendor_stats` ADD `vrs_rejected_drivers` INT NULL DEFAULT '0' AFTER `vrs_rejected_cars`;


--------------------------------------------RAMALA---12/07/2021--------------------------------------
ALTER TABLE `partner_settings` ADD `pts_schedule_time` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Value should consider in minute' AFTER `pts_generate_invoice_to`;

ALTER TABLE `partner_settings` CHANGE `pts_schedule_time` `pts_schedule_time` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT 'Value should consider in minute';

ALTER TABLE `booking_schedule_event` CHANGE `bse_schedule_time` `bse_schedule_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `partner_settings` ADD `pts_drv_share_min` INT(11) NOT NULL DEFAULT '0' COMMENT 'Value should consider in minute' AFTER `pts_generate_invoice_to`;

