<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BookingSub extends Booking
{

	const CODE_STATUS_QUOTED			 = 1;
	const CODE_STATUS_CONFIRMED		 = 2;
	const CODE_STATUS_INPROGRESS		 = 3;
	const CODE_STATUS_COMPLETED		 = 4;
	const CODE_STATUS_CANCELLED		 = 5;
	const CODE_STATUS_QUOTE_EXPRIED	 = 6;

	public $from_date;
	public $to_date;
	public $date;
	public $search;
	public $csrSearch;
	public $vnd_code;
	public $gnowType;
	public $nonProfitable = 0;
	public $weekDays;
	public $sourcezone, $region, $state, $local, $outstation;
	public $assignMode, $manualAssignment, $criticalAssignment, $includeTFR;

	/** @return BookingSub */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getBusinessTrendReport()
	{
		$sql = "SELECT * FROM
                (
				SELECT
					ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_today,
					ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_today1,
					ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_today2,
					ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_month1,
					ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_month2,
					ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_month3,
					ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_mtd,
					ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_ytd,
					ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_last_year,
					ROUND(SUM(booking_invoice.bkg_total_amount),2) as gmv_lifetime,

					SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today_cnt,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today1_cnt,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today2_cnt,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month1_cnt,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month2_cnt,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month3_cnt,
					SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_mtd_cnt,
					SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as gmv_ytd_cnt,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as gmv_last_year_cnt,
					COUNT(1) as gmv_lifetime_cnt
					FROM `booking` INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id WHERE 1
					AND booking.bkg_active=1
					AND booking.bkg_status IN (2,3,5,6,7,9)
					AND booking.bkg_create_date > '2015-10-01'
                )a,
                (
                    SELECT * FROM (
                        SELECT
                        ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_today_active,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_today1_active,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_today2_active,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_month1_active,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_month2_active,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_month3_active,
                        ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_mtd_active,
                        ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_ytd_active,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_last_year_active,
                        ROUND(SUM(booking_invoice.bkg_total_amount),2) as gmv_lifetime_active,

                        SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today_cnt_active,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today1_cnt_active,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today2_cnt_active,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month1_cnt_active,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month2_cnt_active,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month3_cnt_active,
                        SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_mtd_cnt_active,
                        SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as gmv_ytd_cnt_active,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as gmv_last_year_cnt_active,
                        COUNT(1) as gmv_lifetime_cnt_active
                        FROM `booking` INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id WHERE 1
                        AND booking.bkg_active=1
                        AND booking.bkg_status IN (2,3,5)
                        AND booking.bkg_create_date > '2015-10-01'
                    )bb1,
                    (
                        SELECT
                        ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_today_comp,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_today1_comp,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_today2_comp,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_month1_comp,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_month2_comp,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_month3_comp,
                        ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_mtd_comp,
                        ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_ytd_comp,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_last_year_comp,
                        ROUND(SUM(booking_invoice.bkg_total_amount),2) as gmv_lifetime_comp,

                        SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today_cnt_comp,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today1_cnt_comp,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today2_cnt_comp,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month1_cnt_comp,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month2_cnt_comp,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month3_cnt_comp,
                        SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_mtd_cnt_comp,
                        SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as gmv_ytd_cnt_comp,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as gmv_last_year_cnt_comp,
                        COUNT(1) as gmv_lifetime_cnt_comp
                        FROM `booking` INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id WHERE 1
                        AND booking.bkg_active=1
                        AND booking.bkg_status IN (6,7)
                        AND booking.bkg_create_date > '2015-10-01'
                    )bb2
                )a3,
                (
                     SELECT
                        ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_today_excl_cancel,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_today1_excl_cancel,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_today2_excl_cancel,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_month1_excl_cancel,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_month2_excl_cancel,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_month3_excl_cancel,
                        ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_mtd_excl_cancel,
                        ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_ytd_excl_cancel,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),booking_invoice.bkg_total_amount,0)),2) as gmv_last_year_excl_cancel,
                        ROUND(SUM(booking_invoice.bkg_total_amount),2) as gmv_lifetime_excl_cancel,

                        SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today_cnt_excl_cancel,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today1_cnt_excl_cancel,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today2_cnt_excl_cancel,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month1_cnt_excl_cancel,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month2_cnt_excl_cancel,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month3_cnt_excl_cancel,
                        SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_mtd_cnt_excl_cancel,
                        SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as gmv_ytd_cnt_excl_cancel,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as gmv_last_year_cnt_excl_cancel,
                        COUNT(1) as gmv_lifetime_cnt_excl_cancel,

                        SUM(IF(booking.bkg_agent_id>0 AND DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today_cnt_excl_cancel_b2b,
                        SUM(IF(booking.bkg_agent_id>0 AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today1_cnt_excl_cancel_b2b,
                        SUM(IF(booking.bkg_agent_id>0 AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_today2_cnt_excl_cancel_b2b,
                        SUM(IF(booking.bkg_agent_id>0 AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month1_cnt_excl_cancel_b2b,
                        SUM(IF(booking.bkg_agent_id>0 AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month2_cnt_excl_cancel_b2b,
                        SUM(IF(booking.bkg_agent_id>0 AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_month3_cnt_excl_cancel_b2b,
                        SUM(IF(booking.bkg_agent_id>0 AND DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_mtd_cnt_excl_cancel_b2b,
                        SUM(IF(booking.bkg_agent_id>0 AND booking.bkg_agent_id>0 AND DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as gmv_ytd_cnt_excl_cancel_b2b,
                        SUM(IF(booking.bkg_agent_id>0 AND DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as gmv_last_year_cnt_excl_cancel_b2b,
                        SUM(IF(booking.bkg_agent_id>0,1,0)) as gmv_lifetime_cnt_excl_cancel_b2b
                        FROM `booking` INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id WHERE 1
                        AND booking.bkg_active=1
                        AND booking.bkg_status IN (2,3,5,6,7)
                        AND booking.bkg_create_date > '2015-10-01'
                )a2,
                (
                    SELECT
                        ROUND(booking_gmv_advance_today,2) as gmv_advance_today,
                        ROUND(booking_gmv_advance_today1,2) as gmv_advance_today1,
                        ROUND(booking_gmv_advance_today2,2) as gmv_advance_today2,
                        ROUND(booking_gmv_advance_month1,2) as gmv_advance_month1,
                        ROUND(booking_gmv_advance_month2,2) as gmv_advance_month2,
                        ROUND(booking_gmv_advance_month3,2) as gmv_advance_month3,
                        ROUND(booking_gmv_advance_mtd,2) as gmv_advance_mtd,
                        ROUND(booking_gmv_advance_ytd,2) as gmv_advance_ytd,
                        ROUND(booking_gmv_advance_last_year,2) as gmv_advance_last_year,
                        ROUND(booking_gmv_advance_lifetime,2) as gmv_advance_lifetime,

                        ROUND(booking_advance_today,2) as advance_today,
                        ROUND(booking_advance_today1,2) as advance_today1,
                        ROUND(booking_advance_today2,2) as advance_today2,
                        ROUND(booking_advance_month1,2) as advance_month1,
                        ROUND(booking_advance_month2,2) as advance_month2,
                        ROUND(booking_advance_month3,2) as advance_month3,
                        ROUND(booking_advance_mtd,2) as advance_mtd,
                        ROUND(booking_advance_ytd,2) as advance_ytd,
                        ROUND(booking_advance_last_year,2) as advance_last_year,
                        ROUND(booking_advance_lifetime,2) as advance_lifetime,

                        booking_advance_today_count as advance_today_count,
                        booking_advance_today1_count as advance_today1_count,
                        booking_advance_today2_count as advance_today2_count,
                        booking_advance_month1_count as advance_month1_count,
                        booking_advance_month2_count as advance_month2_count,
                        booking_advance_month3_count as advance_month3_count,
                        booking_advance_mtd_count as advance_mtd_count,
                        booking_advance_ytd_count as advance_ytd_count,
                        booking_advance_last_year_count as advance_last_year_count,
                        booking_advance_lifetime_count as advance_lifetime_count
                        FROM
                        (
							SELECT
							SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)) as booking_gmv_advance_today,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)) as booking_gmv_advance_today1,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),booking_invoice.bkg_total_amount,0)) as booking_gmv_advance_today2,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)) as booking_gmv_advance_month1,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)) as booking_gmv_advance_month2,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)) as booking_gmv_advance_month3,
							SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),booking_invoice.bkg_total_amount,0)) as booking_gmv_advance_mtd,
							SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),booking_invoice.bkg_total_amount,0)) as booking_gmv_advance_ytd,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),booking_invoice.bkg_total_amount,0)) as booking_gmv_advance_last_year,
							SUM(booking_invoice.bkg_total_amount) as booking_gmv_advance_lifetime,

							SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),(booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount),0)) as booking_advance_today,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),(booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount),0)) as booking_advance_today1,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),(booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount),0)) as booking_advance_today2,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),(booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount),0)) as booking_advance_month1,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),(booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount),0)) as booking_advance_month2,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),(booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount),0)) as booking_advance_month3,
							SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),(booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount),0)) as booking_advance_mtd,
							SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),(booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount),0)) as booking_advance_ytd,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),(booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount),0)) as booking_advance_last_year,
							SUM(booking_invoice.bkg_advance_amount) as booking_advance_lifetime,

							SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as booking_advance_today_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as booking_advance_today1_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as booking_advance_today2_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),1,0)) as booking_advance_month1_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),1,0)) as booking_advance_month2_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),1,0)) as booking_advance_month3_count,
							SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),1,0)) as booking_advance_mtd_count,
							SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as booking_advance_ytd_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as booking_advance_last_year_count,
							COUNT(booking.bkg_id) as booking_advance_lifetime_count
							FROM `booking` INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id
							WHERE booking.bkg_active=1
							AND booking.bkg_status IN (2,3,5,6,7)
							AND booking_invoice.bkg_advance_amount>0
							AND booking.bkg_create_date > '2015-10-01'
                        )a
                )b,
                (
                    SELECT COUNT(1) as review_total,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(ratings.rtg_customer_date,'%m%Y'),1,0)) as review_month1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(ratings.rtg_customer_date,'%m%Y'),1,0)) as review_month2,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(ratings.rtg_customer_date,'%m%Y'),1,0)) as review_month3,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(ratings.rtg_customer_date,'%m%Y'),1,0)) as review_mtd,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(ratings.rtg_customer_date,'%Y'),1,0)) as review_ytd
                    FROM `ratings`
                    WHERE rtg_active=1
                    AND date(`rtg_customer_date`)>'2015-10-01'
                )b1,
                (
                    SELECT COUNT(1) as comp_total,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%m%Y'),1,0)) as comp_month1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%m%Y'),1,0)) as comp_month2,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%m%Y'),1,0)) as comp_month3,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%m%Y'),1,0)) as comp_mtd,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%Y'),1,0)) as comp_ytd
                    FROM `booking`
                    WHERE booking.bkg_active=1
                    AND booking.bkg_status IN (6,7)
                    AND DATE(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE)) >'2015-10-01'
                )b2,
                (
                    SELECT
                    SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(DATE(match_date),'%d%m%Y'),1,0)) as match_today_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(match_date),'%d%m%Y'),1,0)) as match_today1_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(DATE(match_date),'%d%m%Y'),1,0)) as match_today2_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(DATE(match_date),'%m%Y'),1,0)) as match_month1_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(DATE(match_date),'%m%Y'),1,0)) as match_month2_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(DATE(match_date),'%m%Y'),1,0)) as match_month3_cnt,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(DATE(match_date),'%m%Y'),1,0)) as match_mtd_cnt,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(DATE(match_date),'%Y'),1,0)) as match_ytd_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(DATE(match_date),'%Y'),1,0)) as match_last_year_cnt,
                    COUNT(1) as match_lifetime_cnt
                    FROM
                    (
                        SELECT trip_id,
                        MAX(blg_created) as match_date
                        FROM
                        (
                            SELECT booking.bkg_booking_id,
                            booking.bkg_bcb_id as trip_id,
                            blg_created
                            FROM `booking`
                            INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
                            LEFT JOIN (
								SELECT booking_log.blg_created,
								booking_log.blg_booking_id,
								booking_log.blg_trip_id
								FROM `booking_log`
								LEFT JOIN `admins` ON admins.adm_id=booking_log.blg_user_id
								WHERE booking_log.blg_event_id=91
								AND booking_log.blg_trip_id IS NOT NULL
								GROUP BY booking_log.blg_booking_id
                            )blg ON blg.blg_booking_id=booking.bkg_id
                            WHERE booking.bkg_bcb_id IN (
                                SELECT booking_cab.bcb_id
                                FROM `booking_cab`
                                WHERE booking_cab.bcb_trip_type=1 AND booking_cab.bcb_active=1
                            )
                            AND booking.bkg_active=1 AND booking.bkg_status IN (2,3,5,6,7,9)
                            GROUP BY booking.bkg_id
                        )a GROUP BY trip_id
					)b ORDER BY match_date DESC
                )b3,
                (
                    SELECT
                    SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(DATE(log_created),'%d%m%Y'),1,0)) as cancel_today_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(log_created),'%d%m%Y'),1,0)) as cancel_today1_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(DATE(log_created),'%d%m%Y'),1,0)) as cancel_today2_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(DATE(log_created),'%m%Y'),1,0)) as cancel_month1_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(DATE(log_created),'%m%Y'),1,0)) as cancel_month2_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(DATE(log_created),'%m%Y'),1,0)) as cancel_month3_cnt,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(DATE(log_created),'%m%Y'),1,0)) as cancel_mtd_cnt,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(DATE(log_created),'%Y'),1,0)) as cancel_ytd_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(DATE(log_created),'%Y'),1,0)) as cancel_last_year_cnt,
                    COUNT(1) as cancel_lifetime_cnt
                    FROM (
                        SELECT COUNT(1),booking.bkg_id,MAX(booking_log.blg_created) as log_created
                        FROM `booking`
                        INNER JOIN `booking_log` ON booking_log.blg_booking_id=booking.bkg_id AND booking_log.blg_event_id=10
                        WHERE booking.bkg_status=9
                        GROUP BY booking.bkg_id
                    )a
                )b4,
                (
                    SELECT
                    SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(DATE(log_created),'%d%m%Y'),1,0)) as cancel_unv_today_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(log_created),'%d%m%Y'),1,0)) as cancel_unv_today1_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(DATE(log_created),'%d%m%Y'),1,0)) as cancel_unv_today2_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(DATE(log_created),'%m%Y'),1,0)) as cancel_unv_month1_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(DATE(log_created),'%m%Y'),1,0)) as cancel_unv_month2_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(DATE(log_created),'%m%Y'),1,0)) as cancel_unv_month3_cnt,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(DATE(log_created),'%m%Y'),1,0)) as cancel_unv_mtd_cnt,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(DATE(log_created),'%Y'),1,0)) as cancel_unv_ytd_cnt,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(DATE(log_created),'%Y'),1,0)) as cancel_unv_last_year_cnt,
                    COUNT(1) as cancel_unv_lifetime_cnt
                    FROM (
                        SELECT COUNT(1),booking.bkg_id,MAX(booking_log.blg_created) as log_created
                        FROM `booking`
                        INNER JOIN `booking_log` ON booking_log.blg_booking_id=booking.bkg_id AND booking_log.blg_event_id=10
                        WHERE booking.bkg_status=10
                        GROUP BY booking.bkg_id
                    )a
                )b5,
                (
                    SELECT * FROM (
						SELECT TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps_lifetime
						FROM `ratings`
						WHERE DATE(ratings.rtg_customer_date) > '2015-10-01'
					)a,
					(
						SELECT 	TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps_mtd
						FROM `ratings`
						WHERE DATE(ratings.rtg_customer_date) BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND CURDATE()
					)a2,
					(
						SELECT 	TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps_month1
						FROM `ratings`
						WHERE DATE(ratings.rtg_customer_date) BETWEEN DATE_SUB(DATE_FORMAT(NOW() ,'%Y-%m-01'), INTERVAL 1 MONTH) AND DATE_SUB(DATE_FORMAT(NOW() ,'%Y-%m-31'), INTERVAL 1 MONTH)
					)a3,
					(
						SELECT 	TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps_month2
						FROM `ratings`
						WHERE DATE(ratings.rtg_customer_date) BETWEEN DATE_SUB(DATE_FORMAT(NOW() ,'%Y-%m-01'), INTERVAL 2 MONTH) AND DATE_SUB(DATE_FORMAT(NOW() ,'%Y-%m-31'), INTERVAL 2 MONTH)

					)a4,
					(
						SELECT 	TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps_ytd
						FROM `ratings`
						WHERE DATE(ratings.rtg_customer_date) BETWEEN DATE_FORMAT(NOW() ,'%Y-01-01') AND CURDATE()

					)a5
                )b6";

		return DBUtil::queryRow($sql);
	}

	public function getBusinessReport()
	{

		$sql = "SELECT * FROM
                        (
                            SELECT SUM(
                                IF(DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%d%m%Y'),1,0)
                            ) as booking_adv_tommrrow_count,
                            SUM(
                                IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%d%m%Y'),1,0)
                            ) as booking_adv_today_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%d%m%Y'),1,0)
                            ) as booking_adv_today1_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%d%m%Y'),1,0)
                            ) as booking_adv_today2_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%d%m%Y'),1,0)
                            ) as booking_adv_today3_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%m%Y'),1,0)
                            ) as booking_adv_month1_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%m%Y'),1,0)
                            ) as booking_adv_month2_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%m%Y'),1,0)
                            ) as booking_adv_month3_count,
                            SUM(
                                IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%m%Y'),1,0)
                            ) as booking_adv_mtd_count,
                            SUM(
                                IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%Y'),1,0)
                            ) as booking_adv_ytd_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%Y'),1,0)
                            ) as booking_adv_last_year_count,
                            COUNT(DISTINCT booking.bkg_id) as booking_adv_lifetime_count
                            FROM `booking`
                            WHERE booking.bkg_active=1 AND booking.bkg_status IN (2,3,5,6,7)
                            AND (booking.bkg_advance_amount-booking.bkg_refund_amount)>0
                        )a,
                        (
                            SELECT SUM(
                                IF(DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%d%m%Y'),1,0)
                            ) as booking_cod_tommrrow_count,
                            SUM(
                                IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%d%m%Y'),1,0)
                            ) as booking_cod_today_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%d%m%Y'),1,0)
                            ) as booking_cod_today1_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%d%m%Y'),1,0)
                            ) as booking_cod_today2_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%d%m%Y'),1,0)
                            ) as booking_cod_today3_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%m%Y'),1,0)
                            ) as booking_cod_month1_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%m%Y'),1,0)
                            ) as booking_cod_month2_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%m%Y'),1,0)
                            ) as booking_cod_month3_count,
                            SUM(
                                IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%m%Y'),1,0)
                            ) as booking_cod_mtd_count,
                            SUM(
                                IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%Y'),1,0)
                            ) as booking_cod_ytd_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE),'%Y'),1,0)
                            ) as booking_cod_last_year_count,
                            COUNT(DISTINCT booking.bkg_id) as booking_cod_lifetime_count
                            FROM `booking`
                            WHERE booking.bkg_active=1 AND booking.bkg_status IN (2,3,5,6,7)
                            AND (booking.bkg_advance_amount-booking.bkg_refund_amount)<=0
                        )b,
                        (
                            SELECT
                                SUM(
                                    IF(date(bcb_created) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)
                                ) AS booking_match_last_week_count,
                                SUM(
                                    IF(date(bcb_created) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)
                                ) AS booking_match_week_to_date_count,
                                SUM(
                                    IF(DATE(DATE_ADD(CURDATE(), INTERVAL 1 DAY)) = DATE(bcb_created),1,0)
                                ) as booking_match_tommrrow_count,
                                SUM(
                                    IF(DATE(CURDATE()) = DATE(bcb_created),1,0)
                                ) as booking_match_today_count,
                                SUM(
                                    IF(DATE(DATE_SUB(CURDATE(), INTERVAL 1 DAY)) = DATE(bcb_created),1,0)
                                ) as booking_match_today1_count,
                                SUM(
                                    IF(DATE(DATE_SUB(CURDATE(), INTERVAL 2 DAY)) = DATE(bcb_created),1,0)
                                ) as booking_match_today2_count
                                FROM
                                (
                                    SELECT COUNT(1) as cout,booking_cab.bcb_created
                                    FROM `booking_cab`
                                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                                    WHERE booking.bkg_status IN (2,3,5,6,7)
                                    GROUP BY booking_cab.bcb_id
                                    HAVING cout>1
                                )a
                        )c";
		return DBUtil::queryRow($sql);
	}

	public static function fetchQuotedBkgByCreatedate()
	{
		$sql = "SELECT
                            SUM(
                                IF(DATE(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)
				) as booking_quoted_last_week_count,
                            SUM(
                                IF(DATE(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)
				)  as booking_quoted_wtd_count,
                            SUM(
                                IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_quoted_today_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_quoted_today1_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_quoted_today2_count
				FROM `booking` INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id
				WHERE booking.bkg_active=1
				AND booking.bkg_status IN (15)
				AND booking.bkg_create_date>'2015-10-01 00:00:00'";
		return DBUtil::queryRow($sql);
	}

	public static function fetchCancelBkgByCreateAnytime()
	{
		$sql = "SELECT SUM(
					IF(DATE(CURDATE()) = DATE(log_created),1,0)
					) as cancel_today_cnt,
                            SUM(
						IF(DATE(DATE_SUB(CURDATE(), INTERVAL 1 DAY)) = DATE(log_created),1,0)
					) as cancel_today1_cnt,
					SUM(
						IF(DATE(DATE_SUB(CURDATE(), INTERVAL 2 DAY)) = DATE(log_created),1,0)
					) as cancel_today2_cnt,
					SUM(
						IF(DATE(DATE_ADD(CURDATE(), INTERVAL 1 DAY)) = DATE(log_created),1,0)
					) as cancel_tommrrow_cnt,
				   SUM(
						IF(date(log_created) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)
					) AS cancel_wtd_cnt,
					SUM(
						IF(date(log_created) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)
				   ) AS cancel_last_week_cnt
					FROM
                    (
                        SELECT
							COUNT(1),
							booking.bkg_id,
							MAX(booking_log.blg_created) AS log_created,
							booking.bkg_create_date
						FROM `booking`
						INNER JOIN `booking_log` ON booking_log.blg_booking_id = booking.bkg_id AND booking_log.blg_event_id = 10 AND booking.bkg_status = 9
						GROUP BY booking.bkg_id
					)a";
		return DBUtil::queryRow($sql);
	}

	public static function fetchCancelBkgByCreateToday()
	{
		$sql = "SELECT
				SUM(IF(DATE(CURDATE()) = DATE(log_created), 1, 0)) AS cancel_same_today_cnt,
				SUM(IF(DATE(DATE_SUB(CURDATE(), INTERVAL 1 DAY)) = DATE(log_created),1, 0)) AS cancel_same_today1_cnt,
				SUM(IF(DATE(DATE_SUB(CURDATE(), INTERVAL 2 DAY)) = DATE(log_created), 1,0))  AS cancel_same_today2_cnt,
				SUM( IF(DATE(DATE_ADD(CURDATE(), INTERVAL 1 DAY)) = DATE(log_created),1, 0)) AS cancel_same_tommrrow_cnt,
				SUM( IF(DATE(log_created) BETWEEN (DATE_SUB( CURDATE(), INTERVAL (dayofweek(CURDATE()) - 2) DAY))AND CURDATE(),1,0))AS cancel_same_wtd_cnt,
				SUM( IF(DATE(log_created) BETWEEN (DATE_SUB(  CURDATE(), INTERVAL (dayofweek(CURDATE()) + 5) DAY))  AND (DATE_SUB( CURDATE(), INTERVAL (dayofweek(CURDATE()) - 1) DAY)), 1, 0))  AS cancel_same_last_week_cnt
				FROM
                   (
						  SELECT COUNT(1),
						  booking.bkg_id,
						  MAX(booking_log.blg_created) AS log_created,
						  booking.bkg_create_date
						  FROM `booking`
					      INNER JOIN `booking_log` ON     booking_log.blg_booking_id = booking.bkg_id  AND (booking_log.blg_event_id = 10 OR booking.bkg_status = 9)
						  WHERE  booking.bkg_create_date BETWEEN (DATE_SUB( CURDATE(),INTERVAL (  dayofweek(CURDATE())+ 5) DAY)) AND DATE_ADD(CURDATE(),INTERVAL 1 DAY)
						  GROUP BY booking.bkg_id
						  HAVING booking.bkg_create_date BETWEEN CONCAT(DATE_FORMAT(log_created,'%Y-%m-%d'),' 00:00:00') AND CONCAT( DATE_FORMAT(log_created,'%Y-%m-%d'),' 23:59:59')
					) a";
		return DBUtil::queryRow($sql);
	}

	public static function fetchTripMatchByCreatedDate()
	{
		$sql = " SELECT
                            SUM(
                                IF(date(bcb_created) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)
                            ) AS booking_match_last_week_count,
                            SUM(
                                IF(date(bcb_created) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)
                            ) AS booking_match_week_to_date_count,
                            SUM(
                                IF(DATE(DATE_ADD(CURDATE(), INTERVAL 1 DAY)) = DATE(bcb_created),1,0)
                            ) as booking_match_tommrrow_count,
                            SUM(
                                IF(DATE(CURDATE()) = DATE(bcb_created),1,0)
                            ) as booking_match_today_count,
                            SUM(
                                IF(DATE(DATE_SUB(CURDATE(), INTERVAL 1 DAY)) = DATE(bcb_created),1,0)
                            ) as booking_match_today1_count,
                            SUM(
                                IF(DATE(DATE_SUB(CURDATE(), INTERVAL 2 DAY)) = DATE(bcb_created),1,0)
                            ) as booking_match_today2_count
                            FROM
                            (
                                SELECT COUNT(1) as cout,booking_cab.bcb_created
                                FROM `booking_cab`
						INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1 AND booking.bkg_create_date>'2015-10-01 00:00:00' WHERE booking.bkg_status IN (2,3,5,6,7)
                                GROUP BY booking_cab.bcb_id
                                HAVING cout>1
					)a";
		return DBUtil::queryRow($sql);
	}

	public static function fetchCODBkgByCreatedDate()
	{
		$sql = "SELECT
                            SUM(
                                IF(DATE(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)
				) as booking_cod_last_week_count,
                            SUM(
                                IF(DATE(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)
				) as booking_cod_wtd_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_cod_tommrrow_count,
                            SUM(
                                IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_cod_today_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_cod_today1_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_cod_today2_count
				FROM `booking`
				INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id AND (booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount)<=0 AND booking.bkg_status IN (2,3,5,6,7)
				WHERE booking.bkg_active=1
				AND booking.bkg_create_date >'2015-10-01 23:59:59'";
		return DBUtil::queryRow($sql);
	}

	public static function fetchAdvanceCountBkgByCreatedDate()
	{
		$sql = "SELECT
                            SUM(
					IF(DATE(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)
				) as booking_adv_last_week_count,
                            SUM(
					IF(DATE(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)
				) as booking_adv_wtd_count,
                            SUM(
					IF(DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_adv_tommrrow_count,
                            SUM(
					IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_adv_today_count,
                           SUM(
					IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_adv_today1_count,
                            SUM(
					IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_adv_today2_count
                                FROM `booking`
				INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id AND (booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount)>0 AND booking.bkg_status IN (2,3,5,6,7) 				   WHERE booking.bkg_active=1
				AND booking.bkg_create_date>'2015-10-01 00:00:00'";
		return DBUtil::queryRow($sql);
	}

	public static function fetchAdvancePaymentBkgByActDate()
	{

		$sql = "SELECT
				FORMAT(SUM(IF(date(account_transactions.act_date) BETWEEN DATE_SUB( CURDATE( ) , INTERVAL (dayofweek(CURDATE())+5) DAY ) AND DATE_SUB( CURDATE( ) , INTERVAL (dayofweek(CURDATE())-1) DAY ),account_trans_details.adt_amount,0)),2) adv_pay_last_week,
				FORMAT(SUM(IF(date(account_transactions.act_date)   BETWEEN (DATE_SUB( CURDATE( ) , INTERVAL (dayofweek(CURDATE())-2) DAY )) AND  CURDATE( ),account_trans_details.adt_amount,0)),2) adv_pay_week_to_date,
				FORMAT(SUM(IF(date(account_transactions.act_date)= DATE_SUB(CURDATE(),INTERVAL 2 DAY),account_trans_details.adt_amount,0)),2) adv_pay_day2_before,
				FORMAT(SUM(IF(date(account_transactions.act_date)=DATE_SUB(CURDATE(),INTERVAL 1 DAY),account_trans_details.adt_amount,0)),2) adv_pay_day1_before,
				FORMAT(SUM(IF(date(account_transactions.act_date)=CURDATE(),account_trans_details.adt_amount,0)),2) adv_pay_today
				FROM `account_trans_details`
				INNER JOIN `account_transactions` ON account_transactions.act_id = account_trans_details.adt_trans_id
				WHERE account_transactions.act_active=1
				AND account_trans_details.adt_status=1
				AND account_trans_details.adt_amount IS NOT NULL
				AND account_trans_details.adt_ledger_id IN(1,16,17,18,19,20,21,23,26,29,30)
				AND account_transactions.act_date >= DATE_SUB( CONCAT(CURDATE( ), ' 00:00:00') , INTERVAL (dayofweek(CURDATE())+5) DAY )";
		return DBUtil::queryRow($sql);
	}

	public static function fetchGMVAdvancePayBkgByCreateDate()
	{
		$sql = "SELECT
					FORMAT(SUM(IF(date(booking.bkg_create_date) BETWEEN (DATE_SUB( CURDATE( ) , INTERVAL (dayofweek(CURDATE())+5) DAY )) AND ( DATE_SUB( CURDATE( ) , INTERVAL (dayofweek(CURDATE())-1) DAY )),booking_invoice.bkg_total_amount,0)),2) gmv_adv_last_week,
					FORMAT(SUM(IF(date(booking.bkg_create_date) BETWEEN (DATE_SUB( CURDATE( ),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),booking_invoice.bkg_total_amount,0)),2) gmv_adv_wtd,
					FORMAT(SUM(IF(date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 2 DAY),booking_invoice.bkg_total_amount,0)),2) gmv_adv_today2,
					FORMAT(SUM(IF(date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 1 DAY),booking_invoice.bkg_total_amount,0)),2) gmv_adv_today1,
					FORMAT(SUM(IF(date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 0 DAY),booking_invoice.bkg_total_amount,0)),2) gmv_adv_today
					FROM `booking`
					INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id AND ((booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount) > 0) AND booking.bkg_status IN (2,3,4,5,6,7) AND booking.bkg_create_date>'2015-10-01 00:00:00'
					WHERE booking.bkg_active=1";
		return DBUtil::queryRow($sql);
	}

	public static function fetchGMVTotalPayBkgByCreateDate()
	{
		$sql = "SELECT
				FORMAT(SUM(IF(date(booking.bkg_create_date) BETWEEN (DATE_SUB( CURDATE( ) , INTERVAL (dayofweek(CURDATE())+5) DAY )) AND ( DATE_SUB( CURDATE( ) , INTERVAL (dayofweek(CURDATE())-1) DAY )),booking_invoice.bkg_total_amount,0)),2) gmv_last_week,
				FORMAT(SUM(IF(date(booking.bkg_create_date) BETWEEN (DATE_SUB( CURDATE( ),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),booking_invoice.bkg_total_amount,0)),2) gmv_wtd,
				FORMAT(SUM(IF(date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 2 DAY),booking_invoice.bkg_total_amount,0)),2) gmv_today2,
				FORMAT(SUM(IF(date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 1 DAY),booking_invoice.bkg_total_amount,0)),2) gmv_today1,
				FORMAT(SUM(IF(date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 0 DAY),booking_invoice.bkg_total_amount,0)),2) gmv_today
				FROM `booking` INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id
				WHERE booking.bkg_status IN(2,3,4,5,6,7)
				AND booking.bkg_active=1
				AND booking.bkg_create_date>'2015-10-01'";
		return DBUtil::queryRow($sql);
	}

	public static function fetchTripStartedByPickupDate()
	{
		$sql = "SELECT
				SUM(IF(date(booking.bkg_pickup_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)) AS trip_started_last_week,
				SUM(IF(date(booking.bkg_pickup_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)) AS trip_started_wtd,
				SUM(IF(date(booking.bkg_pickup_date)= DATE_SUB(CURDATE(),INTERVAL 2 DAY),1,0)) AS trip_started_today2,
				SUM(IF(date(booking.bkg_pickup_date)= DATE_SUB(CURDATE(),INTERVAL 1 DAY),1,0)) AS trip_started_today1,
				SUM(IF(date(booking.bkg_pickup_date)= CURDATE(),1,0)) AS trip_started_today,
				SUM(IF(date(booking.bkg_pickup_date)= DATE_ADD(CURDATE(),INTERVAL 1 DAY),1,0)) AS trip_started_tomorrow
				FROM `booking`
				INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id  AND booking.bkg_status IN(2,3,4,5,6,7) AND booking_cab.bcb_active=1 AND booking.bkg_active=1
				WHERE booking.bkg_create_date>'2015-10-01 00:00:00'";
		return DBUtil::queryRow($sql);
	}

	public static function fetchTripOntheWayByPickupDate()
	{
		$sql = "SELECT
				SUM(IF(date(bkg_pickup_date) <= (DATE_SUB( CURDATE() , INTERVAL (dayofweek(CURDATE())+5) DAY )) AND date(DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) >= ( DATE_SUB( CURDATE( ) , INTERVAL (dayofweek(CURDATE())-1) DAY )),1,0)) AS trip_onway_last_week,
				SUM(IF(date(bkg_pickup_date) <= (DATE_SUB( CURDATE() , INTERVAL (dayofweek(CURDATE())-2) DAY )) AND date(DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) >= CURDATE(),1,0)) AS trip_onway_wtd,
				SUM(IF(date(bkg_pickup_date) <= DATE_SUB(CURDATE(),INTERVAL 2 DAY) AND date(DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) >= DATE_SUB(CURDATE(),INTERVAL 2 DAY),1,0)) AS trip_onway_today2,
				SUM(IF(date(bkg_pickup_date) <= DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND date(DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) >= DATE_SUB(CURDATE(),INTERVAL 1 DAY),1,0)) AS trip_onway_today1,
				SUM(IF(date(bkg_pickup_date) <= DATE_SUB(CURDATE(),INTERVAL 0 DAY) AND date(DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) >= DATE_SUB(CURDATE(),INTERVAL 0 DAY),1,0)) AS trip_onway_today,
				SUM(IF(date(bkg_pickup_date) <= DATE_ADD(CURDATE(),INTERVAL 1 DAY) AND date(DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) >= DATE_ADD(CURDATE(),INTERVAL 1 DAY),1,0)) AS trip_onway_tomorrow
				FROM `booking` WHERE 1
				AND booking.bkg_status IN (2,3,4,5,6,7,9)
				AND booking.bkg_active=1
				AND booking.bkg_create_date>'2015-10-01 00:00:00'";
		return DBUtil::queryRow($sql);
	}

	public static function fetchTentativeBkgByCreateDate()
	{
		$sql = "SELECT
                        SUM(
					IF(DATE(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)
				) as booking_ten_last_week_count,
                        SUM(
					IF(DATE(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)
				) as booking_ten_wtd_count,
                        SUM(
					IF(DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_ten_tommrrow_count,
                        SUM(
					IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_ten_today_count,
                        SUM(
					IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_ten_today1_count,
                        SUM(
					IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as booking_ten_today2_count,
				SUM(
					IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%m%Y'),1,0)
				) as booking_ten_mtd_count,
				COUNT(DISTINCT booking.bkg_id) as booking_ten_lifetime_count
                           FROM `booking`
				INNER JOIN `booking_pref` on booking.bkg_id = booking_pref.bpr_bkg_id AND booking_pref.bkg_tentative_booking=1 AND booking.bkg_status IN (2,3,5,6,7)
				WHERE booking.bkg_active=1 AND booking.bkg_create_date>'2015-10-01 00:00:00'";
		return DBUtil::queryRow($sql);
	}

	public static function fetchNpsScoreOnRating()
	{
		$sql = "SELECT * FROM (
                            SELECT TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps_today
                            FROM `ratings`
                            WHERE DATE(ratings.rtg_customer_date) = CURDATE()
                            )a,
                            (
                                SELECT 	TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps_today1
                                FROM `ratings`
                                WHERE DATE(ratings.rtg_customer_date) = DATE_SUB(CURDATE(),INTERVAL 1 DAY)
                            )a2,
                            (
                                SELECT 	TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps_today2
                                FROM `ratings`
                                WHERE DATE(ratings.rtg_customer_date) = DATE_SUB(CURDATE(),INTERVAL 2 DAY)
                            )a3,
                            (
                                SELECT 	TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps_wtd
                                FROM `ratings`
                                WHERE DATE(ratings.rtg_customer_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE()
                            )a4,
                            (
                                SELECT 	TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps_last_week
                                FROM `ratings`
                                WHERE DATE(ratings.rtg_customer_date)
                                BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY ))
                            	AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY ))
					)a5";
		return DBUtil::queryRow($sql);
	}

	public static function fetchConfirmCashByCreateDate()
	{
		$sql = "SELECT
  							 SUM(
                                IF(DATE(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)
				) as confirm_cash_last_week_count,
                           SUM(
                               IF(DATE(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)
				) as confirm_cash_wtd_count,
                            SUM(
					IF(DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as confirm_cash_tommrrow_count,
				SUM(
                                IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as confirm_cash_today_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as confirm_cash_today1_count,
                            SUM(
                                IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%d%m%Y'),1,0)
				) as confirm_cash_today2_count,
				SUM(
					IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(DATE(booking.bkg_create_date),'%m%Y'),1,0)
				) as confirm_cash_mtd_count,
				COUNT(DISTINCT booking.bkg_id) as confirm_cash_lifetime_count
                FROM  `booking`
                INNER JOIN `booking_pref` ON booking_pref.bpr_bkg_id=booking.bkg_id AND booking_pref.bkg_is_confirm_cash=1
                INNER JOIN `booking_invoice` ON booking.bkg_id=booking_invoice.biv_bkg_id AND booking_invoice.bkg_advance_amount =0
				WHERE booking.bkg_active=1 AND booking.bkg_create_date>'2015-10-01 00:00:00'";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public function getBusinessReportRevise()
	{
// Trips Booked	 : (a)
// Trips on the Way  : (b)
// Trips Started/ing : (c)
// GMV	 : (d)
// GMV (Advanced) : (e)
// Trips Booked (Advance Payments)  : (f)
// Trips Booked (COD)	 : (g)
// Trips Matched : (h)
// Trips Booked (Tentative) : (i)

		$sql = "SELECT * FROM  (
                        SELECT
                            SUM(IF(date(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)) AS trip_book_last_week,
                            SUM(IF(date(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )) AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_last_week_excl,
                            SUM(IF(booking.bkg_agent_id!='' AND date(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )) AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_last_week_excl_b2b,
							SUM(IF(booking.bkg_agent_id=450 AND date(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )) AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_last_week_excl_mmt,

                            SUM(IF(date(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)) AS trip_book_wtd,
                            SUM(IF(date(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE() AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_wtd_excl,
                            SUM(IF(booking.bkg_agent_id!='' AND date(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE() AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_wtd_excl_b2b,
							SUM(IF(booking.bkg_agent_id=450 AND date(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE() AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_wtd_excl_mmt,

                            SUM(IF(date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 2 DAY),1,0)) AS trip_book_today2,
                            SUM(IF(date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 2 DAY) AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_today2_excl,
                            SUM(IF(booking.bkg_agent_id!='' AND date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 2 DAY) AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_today2_excl_b2b,
							SUM(IF(booking.bkg_agent_id=450 AND date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 2 DAY) AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_today2_excl_mmt,

                            SUM(IF(date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 1 DAY),1,0)) AS trip_book_today1,
                            SUM(IF(date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_today1_excl,
                            SUM(IF(booking.bkg_agent_id!='' AND date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_today1_excl_b2b,
							SUM(IF(booking.bkg_agent_id=450 AND date(booking.bkg_create_date)= DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_today1_excl_mmt,

                            SUM(IF(date(booking.bkg_create_date)= CURDATE(),1,0)) AS trip_book_today,
                            SUM(IF(date(booking.bkg_create_date)= CURDATE() AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_today_excl,
                            SUM(IF(booking.bkg_agent_id!='' AND date(booking.bkg_create_date)= CURDATE() AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_today_excl_b2b,
							SUM(IF(booking.bkg_agent_id=450 AND date(booking.bkg_create_date)= CURDATE() AND booking.bkg_status IN (2,3,5,6,7),1,0)) AS trip_book_today_excl_mmt
                            FROM `booking` WHERE booking.bkg_status IN (2,3,4,5,6,7,9)
                            AND bkg_active=1
                            AND booking.bkg_create_date>'2015-10-01 00:00:00'
                    )a";
		return DBUtil::queryRow($sql);
	}

	public function cancellationTrendReport()
	{
		$sql = "SELECT * FROM
                        (
                            SELECT
							SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_cancel_adv_today_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_cancel_adv_today1_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_cancel_adv_today2_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_adv_month1_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_adv_month2_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_adv_month3_count,
							SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_adv_mtd_count,
							SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),'1','0')) as booking_cancel_adv_ytd_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),'1','0')) as booking_cancel_adv_last_year_count,
							COUNT(booking.bkg_id) as booking_cancel_adv_lifetime_count
							FROM `booking`
							INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
							WHERE booking.bkg_active=1
							AND booking.bkg_status=9
							AND (biv.bkg_advance_amount-biv.bkg_refund_amount)>0
                        )a,
                        (
                            SELECT
							SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_cancel_cod_today_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_cancel_cod_today1_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_cancel_cod_today2_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_cod_month1_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_cod_month2_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_cod_month3_count,
							SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_cod_mtd_count,
							SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),'1','0')) as booking_cancel_cod_ytd_count,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),'1','0')) as booking_cancel_cod_last_year_count,
							COUNT(booking.bkg_id) as booking_cancel_cod_lifetime_count
							FROM `booking`
							INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
							WHERE booking.bkg_active=1
							AND booking.bkg_status=9
							AND (biv.bkg_advance_amount-biv.bkg_refund_amount)<=0
                        ) b,
                        (
                            SELECT
                            SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_cancel_new_today_count,
                            SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_cancel_new_today1_count,
                            SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_cancel_new_today2_count,
                            SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_new_month1_count,
                            SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_new_month2_count,
                            SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_new_month3_count,
                            SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_new_mtd_count,
                            SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),'1','0')) as booking_cancel_new_ytd_count,
                            SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),'1','0')) as booking_cancel_new_last_year_count,
                            COUNT(booking.bkg_id) as booking_cancel_new_lifetime_count
                            FROM `booking`
                            WHERE booking.bkg_active=1
                            AND booking.bkg_status=9
                        ) c,
                        (
                            SELECT
                            SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_cancel_unv_today_count,
                            SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_cancel_unv_today1_count,
                            SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_cancel_unv_today2_count,
                            SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_unv_month1_count,
                            SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_unv_month2_count,
                            SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_unv_month3_count,
                            SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_cancel_unv_mtd_count,
                            SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),'1','0')) as booking_cancel_unv_ytd_count,
                            SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),'1','0')) as booking_cancel_unv_last_year_count,
                            COUNT(booking.bkg_id) as booking_cancel_unv_lifetime_count
                            FROM `booking`
                            WHERE booking.bkg_active=1
                            AND booking.bkg_status=10
                        ) d";
		return DBUtil::queryRow($sql);
	}

	public function cancellationTrendReport2()
	{
		$sql = "SELECT
                        SUM(
                            IF(
                                DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') && booking.bkg_status IN (2,3,5,6,7),'1','0'
                            )
                        ) as booking_created_today_count,
                        SUM(
                            IF(
                                DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') && booking.bkg_status IN (9),'1','0'
                            )
                        ) as booking_cancel_today_count,
                        SUM(
                            IF(
                                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') && booking.bkg_status IN (2,3,5,6,7),'1','0'
                            )
                        ) as booking_created_today1_count,
                        SUM(
                            IF(
                                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') && booking.bkg_status IN (9),'1','0'
                            )
                        ) as booking_cancel_today1_count,
                        SUM(
                            IF(
                                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') && booking.bkg_status IN (2,3,5,6,7),'1','0'
                              )
                        ) as booking_created_today2_count,
                        SUM(
                            IF(
                                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') && booking.bkg_status IN (9),'1','0'
                              )
                        ) as booking_cancel_today2_count,
                        SUM(
                            IF(
                                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y')  && booking.bkg_status IN (2,3,5,6,7),'1','0'
                              )
                        ) as booking_created_month1_count,
                        SUM(
                            IF(
                                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y')  && booking.bkg_status IN (9),'1','0'
                              )
                        ) as booking_cancel_month1_count,
                        SUM(
                            IF(
                                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') && booking.bkg_status IN (2,3,5,6,7),'1','0'
                              )
                        ) as booking_created_month2_count,
                        SUM(
                            IF(
                                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') && booking.bkg_status IN (9),'1','0'
                              )
                        ) as booking_cancel_month2_count,
                        SUM(
                            IF(
                                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') && booking.bkg_status IN (2,3,5,6,7),'1','0'
                              )
                        ) as booking_created_month3_count,
                        SUM(
                            IF(
                                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') && booking.bkg_status IN (9),'1','0'
                              )
                        ) as booking_cancel_month3_count,
                        SUM(
                            IF(
                                DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') && booking.bkg_status IN (2,3,5,6,7),'1','0'
                            )
                        ) as booking_created_mtd_count,
                        SUM(
                            IF(
                                DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') && booking.bkg_status IN (9),'1','0'
                            )
                        ) as booking_cancel_mtd_count,
                        SUM(
                            IF(
                                DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') && booking.bkg_status IN (2,3,5,6,7),'1','0'
                            )
                        ) as booking_created_ytd_count,
                        SUM(
                            IF(
                                DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') && booking.bkg_status IN (9),'1','0'
                            )
                        ) as booking_cancel_ytd_count,
                        SUM(
                            IF(
                                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') && booking.bkg_status IN (2,3,5,6,7),'1','0'
                              )
                        ) as booking_created_last_year_count,
                        SUM(
                            IF(
                                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') && booking.bkg_status IN (9),'1','0'
                              )
                        ) as booking_cancel_last_year_count,
                        SUM(IF(booking.bkg_status IN (2,3,5,6,7),'1','0')) as booking_created_lifetime_count,
                        SUM(IF(booking.bkg_status IN (9),'1','0')) as booking_cancel_lifetime_count,
                        btr.bkg_platform
                        FROM `booking`
                        INNER JOIN booking_trail as btr ON btr.btr_bkg_id=bkg_id
                        WHERE booking.bkg_active=1
                        AND booking.bkg_status IN (2,3,5,6,7,9)
                        AND btr.bkg_platform IN (1,2)
                        GROUP BY btr.bkg_platform";
		return DBUtil::queryAll($sql);
	}

	public function businessBookingReport()
	{
		$result					 = [];
		$query_booked			 = "SELECT
									SUM(IF((`bkg_create_date`) BETWEEN (DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) + 5) DAY)) AND CONCAT((DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) - 1) DAY)), ' 23:59:59'), 1, 0)) AS last_week,
									SUM(IF((`bkg_create_date`) BETWEEN (DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) - 2) DAY)) AND NOW(), 1, 0)) AS week_to_date,
									SUM(IF(bkg_create_date BETWEEN (DATE_SUB(CURDATE(), INTERVAL 2 DAY)) AND CONCAT((DATE_SUB(CURDATE(), INTERVAL 2 DAY)), ' 23:59:59'), 1, 0)) AS 2day_before,
									SUM(IF(bkg_create_date BETWEEN (DATE_SUB(CURDATE(), INTERVAL 1 DAY)) AND CONCAT((DATE_SUB(CURDATE(), INTERVAL 1 DAY)), ' 23:59:59'), 1, 0)) AS 1day_before,
									SUM(IF(bkg_create_date BETWEEN CURDATE() AND NOW(), 1, 0)) AS today
									FROM   booking
									WHERE  bkg_status IN (2, 3, 4, 5, 6, 7, 9) AND bkg_active = 1 AND booking.bkg_create_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 month) AND CONCAT(CURDATE(), ' 23:59:59') LIMIT 0,1";
		$result['result_booked'] = DBUtil::queryRow($query_booked, DBUtil::SDB());

		$query_started				 = "SELECT
										SUM(IF((`bkg_pickup_date`) BETWEEN (DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) + 5) DAY)) AND CONCAT((DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) - 1) DAY)), ' 23:59:59'), 1, 0)) AS last_week,
										SUM(IF((`bkg_pickup_date`) BETWEEN (DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) - 2) DAY)) AND CONCAT(CURDATE(), ' 23:59:59'), 1, 0)) AS week_to_date,
										SUM(IF(bkg_pickup_date BETWEEN (DATE_SUB(CURDATE(), INTERVAL 2 DAY)) AND CONCAT((DATE_SUB(CURDATE(), INTERVAL 2 DAY)), ' 23:59:59'), 1, 0)) AS 2day_before,
										SUM(IF(bkg_pickup_date BETWEEN (DATE_SUB(CURDATE(), INTERVAL 1 DAY)) AND CONCAT((DATE_SUB(CURDATE(), INTERVAL 1 DAY)), ' 23:59:59'), 1, 0)) AS 1day_before,
										SUM(IF(bkg_pickup_date BETWEEN CURDATE() AND CONCAT(CURDATE(), ' 23:59:59'), 1, 0)) AS today,
										SUM(IF(bkg_pickup_date BETWEEN CONCAT(DATE_ADD(CURDATE(),INTERVAL 1 DAY), ' 00:00:00') AND CONCAT(DATE_ADD(CURDATE(),INTERVAL 1 DAY), ' 23:59:59'), 1, 0)) AS tomorrow
										FROM booking WHERE bkg_status IN(2,3,4,5,6,7) AND bkg_active=1
										AND booking.bkg_pickup_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 month) AND CONCAT(CURDATE(), ' 23:59:59') LIMIT 0,1";
		$result['result_started']	 = DBUtil::queryRow($query_started, DBUtil::SDB());

		$query_ontheway				 = "SELECT
										SUM(IF((bkg_pickup_date) <= (DATE_SUB(CURDATE() , INTERVAL (dayofweek(CURDATE())+5) DAY )) AND (DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) >= (DATE_SUB(CURDATE() ,INTERVAL (dayofweek(CURDATE())-1) DAY )),1,0)) AS last_week,
										SUM(IF((bkg_pickup_date) <= (DATE_SUB(CURDATE() , INTERVAL (dayofweek(CURDATE())-2) DAY )) AND (DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) >= CURDATE(),1,0)) AS week_to_date,
										SUM(IF((bkg_pickup_date) <= DATE_SUB(CURDATE(),INTERVAL 2 DAY) AND (DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) >= DATE_SUB(CURDATE(),INTERVAL 2 DAY),1,0)) AS 2day_before,
										SUM(IF((bkg_pickup_date) <= DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND (DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) >= DATE_SUB(CURDATE(),INTERVAL 1 DAY),1,0)) AS 1day_before,
										SUM(IF((bkg_pickup_date) <= DATE_SUB(CURDATE(),INTERVAL 0 DAY) AND (DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) >= DATE_SUB(CURDATE(),INTERVAL 0 DAY),1,0)) AS today,
										SUM(IF((bkg_pickup_date) <= DATE_ADD(CURDATE(),INTERVAL 1 DAY) AND (DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) >= DATE_ADD(CURDATE(),INTERVAL 1 DAY),1,0)) AS tomorrow
										FROM booking WHERE bkg_status IN(2,3,4,5,6,7,9) AND bkg_active=1 AND booking.bkg_pickup_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 month) AND CONCAT(CURDATE(), ' 23:59:59') limit 0,1";
		$result['result_ontheway']	 = DBUtil::queryRow($query_ontheway, DBUtil::SDB());

		$query_advance_paid				 = "SELECT SUM(account_trans_details.adt_amount) last_week
											FROM `account_trans_details`
											JOIN account_ledger ON account_ledger.ledgerId = account_trans_details.adt_ledger_id
											LEFT JOIN payment_gateway apg ON apg.apg_id=account_trans_details.adt_trans_ref_id
											JOIN account_transactions ON account_transactions.act_id = account_trans_details.adt_trans_id
											WHERE account_trans_details.adt_status=1 AND account_trans_details.adt_amount IS NOT NULL AND account_transactions.act_type = 1
											AND account_ledger.accountGroupId IN (27,28)  AND account_transactions.act_active = 1
											and account_transactions.act_date   BETWEEN DATE_SUB(CURDATE() , INTERVAL (dayofweek(CURDATE())+5) DAY )  AND CONCAT(DATE_SUB( CURDATE( ),INTERVAL (dayofweek(CURDATE())-1) DAY),' 23:59:59')
											LIMIT 0,1";
		$result['result_advancepaid']	 = DBUtil::queryRow($query_advance_paid, DBUtil::SDB());

		$query_cancelled = "SELECT
							COUNT(DISTINCT CASE WHEN (booking.bkg_create_date) BETWEEN (DATE_SUB( CURDATE( ) , INTERVAL (dayofweek(CURDATE())+5) DAY )) AND  CONCAT(DATE_SUB(CURDATE( ) , INTERVAL (dayofweek(CURDATE())-1) DAY),' 23:59:59') THEN booking.bkg_id END) last_week,
							SUM(IF((booking.`bkg_create_date`) BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAYOFWEEK(CURDATE())-2) DAY) AND NOW(),1,0)) as week_to_date,
							COUNT(DISTINCT CASE WHEN booking.bkg_create_date BETWEEN DATE_SUB(CURDATE(),INTERVAL 2 DAY) AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 2 DAY), ' 23:59:59') THEN booking.bkg_id END) 2day_before,
							COUNT(DISTINCT CASE WHEN booking.bkg_create_date BETWEEN DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY), ' 23:59:59') THEN booking.bkg_id END) 1day_before,
							COUNT(DISTINCT CASE WHEN booking.bkg_create_date BETWEEN CURDATE() AND NOW() THEN booking.bkg_id END) today
							FROM `booking`
							INNER JOIN booking_log  ON booking_log.blg_booking_id=booking.bkg_id
							WHERE booking.bkg_status IN (9) AND booking_log.blg_event_id IN (10)
							AND booking.bkg_create_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 month) AND CONCAT(CURDATE(), ' 23:59:59') LIMIT 0,1";

		$result['result_cancelled'] = DBUtil::queryRow($query_cancelled, DBUtil::SDB());

		$query_gmv				 = "SELECT
									SUM(IF(bkg_create_date BETWEEN (DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) + 5) DAY)) AND CONCAT((DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) - 1) DAY)), ' 23:59:59'), bkg_total_amount, 0)) AS last_week,
									SUM(IF(bkg_create_date BETWEEN (DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) - 2) DAY)) AND NOW() ,bkg_total_amount, 0)) AS week_to_date,
									SUM(IF(bkg_create_date BETWEEN (DATE_SUB(CURDATE(), INTERVAL 2 DAY)) AND CONCAT((DATE_SUB(CURDATE(), INTERVAL 2 DAY)), ' 23:59:59'), bkg_total_amount, 0)) AS 2day_before,
									SUM(IF(bkg_create_date BETWEEN (DATE_SUB(CURDATE(), INTERVAL 1 DAY)) AND CONCAT((DATE_SUB(CURDATE(), INTERVAL 1 DAY)), ' 23:59:59'), bkg_total_amount, 0)) AS 1day_before,
									SUM(IF(bkg_create_date BETWEEN CURDATE() AND NOW(),bkg_total_amount, 0)) AS today
									FROM   booking
									JOIN `booking_invoice` ON booking.bkg_id=booking_invoice.biv_bkg_id
									WHERE bkg_status IN(2,3,4,5,6,7) AND bkg_active=1 AND bkg_total_amount IS NOT NULL
									AND booking.bkg_create_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 month) AND CONCAT(CURDATE(), ' 23:59:59') LIMIT 0,1";
		$result['result_gmv']	 = DBUtil::queryRow($query_gmv, DBUtil::SDB());
		return $result;
	}

	public function businessNps()
	{
		$array				 = [];
		$sqlNpsLastWeek		 = "SELECT IF(TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10, 1, 0)) - SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6, 1, 0))) / SUM(IF(rtg_customer_recommend IS NOT NULL, 1, 0))) * 100, 2) IS NULL, 0.00, TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10, 1, 0)) - SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6, 1, 0))) / SUM(IF(rtg_customer_recommend IS NOT NULL, 1, 0))) * 100, 2)) AS nps_lastweek
								FROM   ratings WHERE  rtg_active = 1 AND (`rtg_customer_date` BETWEEN (DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) + 5) DAY)) AND CONCAT(DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) - 1) DAY),' 23:59:59') )";
		$data				 = DBUtil::queryRow($sqlNpsLastWeek, DBUtil::SDB());
		$array['last_week']	 = $data['nps_lastweek'];

		$sqlNpsWeektoDate		 = "SELECT IF(TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10, 1, 0)) - SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6, 1, 0))) / SUM(IF(rtg_customer_recommend IS NOT NULL, 1, 0))) * 100, 2) IS NULL, 0.00, TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10, 1, 0)) - SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6, 1, 0))) / SUM(IF(rtg_customer_recommend IS NOT NULL, 1, 0))) * 100, 2)) AS nps_weektodate
									FROM   ratings	WHERE  rtg_active = 1 AND (rtg_customer_date BETWEEN (DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) - 2) DAY))  AND NOW() )";
		$data1					 = DBUtil::queryRow($sqlNpsWeektoDate, DBUtil::SDB());
		$array['week_to_date']	 = $data1['nps_weektodate'];

		return $array;
	}

	public function businessSourceZones()
	{
		$monthSub1StartDate	 = date('Y-m-d', strtotime("first day of -1 month")) . " 00:00:00";
		$monthSub1EndDate	 = date('Y-m-d', strtotime("last day of -1 month")) . " 23:59:59";
		$monthStartdate		 = date("Y-m-01") . " 00:00:00";
		$status				 = [2, 3, 4, 5, 6, 7, 9];
		$status				 = implode(',', $status);
		$sql				 = "SELECT wktd.zon_name AS zone_name, IF(lstmt.zone_last_month_count IS NULL, 0, lstmt.zone_last_month_count) AS last_month_count, IF(mttd.zone_month_to_date_count IS NULL, 0, mttd.zone_month_to_date_count) AS month_to_date_count, IF(lstwk.zone_last_week_count IS NULL, 0, lstwk.zone_last_week_count) AS last_week_count, IF(wktd.zone_week_to_date_count IS NULL, 0, wktd.zone_week_to_date_count) AS week_to_date_count
FROM   (SELECT   zon_id, zon_name, count(zct_zon_id) AS zone_week_to_date_count
        FROM     booking
                 LEFT JOIN zone_cities ON bkg_from_city_id = zct_cty_id
                 LEFT JOIN zones ON zct_zon_id = zon_id
        WHERE    bkg_status IN ($status) AND bkg_create_date BETWEEN Concat((DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) - 2) DAY)), ' 00:00:00')
                                                                 AND CONCAT(CURDATE(), ' 23:59:59')
        GROUP BY zct_zon_id
        ORDER BY zone_week_to_date_count DESC
        LIMIT    10) wktd
       LEFT JOIN (SELECT   zon_id, count(zct_zon_id) AS zone_last_month_count
                  FROM     booking
                           LEFT JOIN zone_cities ON bkg_from_city_id = zct_cty_id
                           LEFT JOIN zones ON zct_zon_id = zon_id
                  WHERE    bkg_status IN ($status) AND bkg_create_date BETWEEN '$monthSub1StartDate' AND '$monthSub1EndDate'
                  GROUP BY zct_zon_id) lstmt
         ON lstmt.zon_id = wktd.zon_id
       LEFT JOIN (SELECT   zon_id, count(zct_zon_id) AS zone_month_to_date_count
                  FROM     booking
                           LEFT JOIN zone_cities ON bkg_from_city_id = zct_cty_id
                           LEFT JOIN zones ON zct_zon_id = zon_id
                  WHERE    bkg_status IN ($status) AND bkg_create_date BETWEEN '$monthStartdate' AND CONCAT(CURDATE(), ' 23:59:59')
                  GROUP BY zct_zon_id) mttd
         ON mttd.zon_id = wktd.zon_id
       LEFT JOIN
       (SELECT   zon_id, count(zct_zon_id) AS zone_last_week_count
        FROM     booking
                 LEFT JOIN zone_cities ON bkg_from_city_id = zct_cty_id
                 LEFT JOIN zones ON zct_zon_id = zon_id
        WHERE    bkg_status IN ($status) AND bkg_create_date BETWEEN CONCAT((DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) + 5) DAY)), ' 00:00:00')
                                                                 AND CONCAT(DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) - 1) DAY), ' 23:59:59')
        GROUP BY zct_zon_id) lstwk
         ON lstwk.zon_id = wktd.zon_id";
		return DBUtil::queryAll($sql);
	}

	public function businessDestinationZones()
	{
		$monthSub1StartDate	 = date('Y-m-d', strtotime("first day of -1 month")) . " 00:00:00";
		$monthSub1EndDate	 = date('Y-m-d', strtotime("last day of -1 month")) . " 23:59:59";
		$monthStartdate		 = date("Y-m-01") . " 00:00:00";
		$status				 = [2, 3, 4, 5, 6, 7, 9];
		$status				 = implode(',', $status);
		$sql				 = "SELECT wktd.zon_name AS zone_name, IF(lstmt.zone_last_month_count IS NULL, 0, lstmt.zone_last_month_count) AS last_month_count, IF(mttd.zone_month_to_date_count IS NULL, 0, mttd.zone_month_to_date_count) AS month_to_date_count, IF(lstwk.zone_last_week_count IS NULL, 0, lstwk.zone_last_week_count) AS last_week_count, IF(wktd.zone_week_to_date_count IS NULL, 0, wktd.zone_week_to_date_count) AS week_to_date_count
FROM   (SELECT   zon_id, zon_name, count(zct_zon_id) AS zone_week_to_date_count
        FROM     booking
                 LEFT JOIN zone_cities ON bkg_to_city_id = zct_cty_id
                 LEFT JOIN zones ON zct_zon_id = zon_id
        WHERE    bkg_status IN ($status) AND bkg_create_date BETWEEN (DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) - 2) DAY))   AND CONCAT(CURDATE(), ' 23:59:59')
        GROUP BY zct_zon_id
        ORDER BY zone_week_to_date_count DESC
        LIMIT    10) wktd
       LEFT JOIN (SELECT   zon_id, count(zct_zon_id) AS zone_last_month_count
                  FROM     booking
                           LEFT JOIN zone_cities ON bkg_to_city_id = zct_cty_id
                           LEFT JOIN zones ON zct_zon_id = zon_id
                  WHERE    bkg_status IN ($status) AND bkg_create_date BETWEEN '$monthSub1StartDate' AND '$monthSub1EndDate'
                  GROUP BY zct_zon_id) lstmt
         ON lstmt.zon_id = wktd.zon_id
       LEFT JOIN (SELECT   zon_id, count(zct_zon_id) AS zone_month_to_date_count
                  FROM     booking
                           LEFT JOIN zone_cities ON bkg_to_city_id = zct_cty_id
                           LEFT JOIN zones ON zct_zon_id = zon_id
                  WHERE    bkg_status IN ($status) AND bkg_create_date BETWEEN '$monthStartdate' AND CONCAT(CURDATE(), ' 23:59:59')
                  GROUP BY zct_zon_id) mttd
         ON mttd.zon_id = wktd.zon_id
       LEFT JOIN
       (SELECT   zon_id, count(zct_zon_id) AS zone_last_week_count
        FROM     booking
                 LEFT JOIN zone_cities ON bkg_to_city_id = zct_cty_id
                 LEFT JOIN zones ON zct_zon_id = zon_id
        WHERE    bkg_status IN ($status) AND bkg_create_date BETWEEN CONCAT((DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) + 5) DAY)), ' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(), INTERVAL (dayofweek(CURDATE()) - 1) DAY), ' 23:59:59')
        GROUP BY zct_zon_id) lstwk
         ON lstwk.zon_id = wktd.zon_id";
		return DBUtil::queryAll($sql);
	}

	public function cancellationBookingReport()
	{
		$data	 = array();
		$sql	 = "SELECT
                    booking.bkg_booking_id as booking_id,booking.bkg_create_date as created_date,(booking.bkg_pickup_date) as pickup_date,
                    biv.bkg_total_amount as booking_aount, biv.bkg_advance_amount as advance_amount,
                    booking.bkg_cancel_delete_reason as cancellation_notes,TIMESTAMPDIFF(HOUR, MAX(booking_log.blg_created),
                    booking.bkg_pickup_date) AS hrs_before_pickup,booking_log.blg_desc as cancel_by,cancel_reasons.cnr_reason,booking_log.blg_user_type,
                    (CASE booking_log.blg_user_type
                     	WHEN 1 then CONCAT(bui.bkg_user_fname,' ',bui.bkg_user_lname)
                     	WHEN 4 THEN CONCAT(admins.adm_fname,' ',admins.adm_lname)
                     	WHEN 10 THEN 'System'
                      END
                    ) as cancelled_by, CONCAT(frmCity.cty_name, ' -> ',toCity.cty_name) as route
                    FROM `booking`
				    INNER JOIN `booking_log` ON booking.bkg_id=booking_log.blg_booking_id AND booking_log.blg_created BETWEEN CONCAT(CURDATE(),' 00:00:00') AND CONCAT(CURDATE(), ' 23:59:59')
					JOIN `booking_invoice` biv ON biv.biv_bkg_id = booking.bkg_id
					JOIN `booking_user` bui ON bui.bui_bkg_id = booking.bkg_id
                  	JOIN `cities` as frmCity ON frmCity.cty_id=booking.bkg_from_city_id
					JOIN `cities` as toCity ON toCity.cty_id=booking.bkg_to_city_id
                    JOIN `cancel_reasons` ON cancel_reasons.cnr_id=booking.bkg_cancel_id
                    LEFT JOIN `admins` ON admins.adm_id=booking_log.blg_admin_id
                    WHERE booking.bkg_status IN (9)
                    AND booking_log.blg_event_id IN (10,82)
                    GROUP BY booking.bkg_booking_id
                    ORDER BY booking_log.blg_created DESC";

		$sql1	 = "SELECT   GROUP_CONCAT(sms_log.slg_type) AS slg_types, sms_log.booking_id
                    FROM     `sms_log`
                    WHERE    sms_log.slg_type IN (4, 5)
                    GROUP BY sms_log.booking_id";
		$data[0] = DBUtil::queryAll($sql);
		$data[1] = DBUtil::queryAll($sql1);
		return $data;
	}

	public function cancellationReasonReport()
	{
		$sql = "SELECT cnr_reason,
                 SUM(IF(cancelLogCreated BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)) AS can_wtd,
                SUM(IF(cancelLogCreated BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),bkg_total_amount,0)) AS can_amt_wtd,
                SUM(IF(cancelLogCreated BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)) AS can_week1,
                SUM(IF(cancelLogCreated BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),bkg_total_amount,0)) AS can_amt_week1,
                SUM(IF(cancelLogCreated= DATE_SUB(CURDATE(),INTERVAL 2 DAY),1,0)) AS can_today2,
                SUM(IF(cancelLogCreated= DATE_SUB(CURDATE(),INTERVAL 2 DAY),bkg_total_amount,0)) AS can_amt_today2,
                SUM(IF(cancelLogCreated= DATE_SUB(CURDATE(),INTERVAL 1 DAY),1,0)) AS can_today1,
                SUM(IF(cancelLogCreated= DATE_SUB(CURDATE(),INTERVAL 1 DAY),bkg_total_amount,0)) AS can_amt_today1,
                SUM(IF(cancelLogCreated= CURDATE(),1,0)) AS can_today,
                SUM(IF(cancelLogCreated= CURDATE(),bkg_total_amount,0)) AS can_amt_today,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(cancelLogCreated,'%m%Y'),1,0)
                ) as can_month1,
                 SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(cancelLogCreated,'%m%Y'),bkg_total_amount,0)
                ) as can_amt_month1,
                SUM(
                    IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(cancelLogCreated,'%m%Y'),1,0)
                ) as can_mtd,
                SUM(
                    IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(cancelLogCreated,'%m%Y'),bkg_total_amount,0)
                ) as can_amt_mtd,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(cancelLogCreated,'%m%Y'),'1','0')
                ) as cancel_month2,
                SUM(
                    IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(cancelLogCreated,'%Y'),'1','0')
                ) as cancel_ytd,
                COUNT(1) as cancel_lifetime
                FROM
                (
                    SELECT
                    booking.bkg_booking_id as booking_id,
                    biv.bkg_total_amount,
                    booking.bkg_create_date as created_date,
                    booking.bkg_pickup_date as pickup_date,
                    cancel_reasons.cnr_id,
                    cancel_reasons.cnr_reason,
                    temp.blg_user_type,
                    DATE(temp.blg_created) as cancelLogCreated
                    FROM `booking`
                    INNER JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
                    INNER JOIN
                    (
                        SELECT booking_log.blg_user_type,booking_log.blg_created,booking_log.blg_booking_id from booking_log where 1  AND booking_log.blg_event_id IN (10,82)  AND  booking_log.blg_created >= (DATE_SUB(NOW(), INTERVAL 1 YEAR)) and  booking_log.blg_event_id IN (10, 82)
                        GROUP BY blg_booking_id ORDER BY booking_log.blg_created desc
                    )  temp on temp.blg_booking_id=booking.bkg_id  AND booking.bkg_status IN (9)
                    INNER JOIN `cancel_reasons` ON cancel_reasons.cnr_id=booking.bkg_cancel_id AND cancel_reasons.cnr_active=1
                    WHERE 1 GROUP BY booking.bkg_booking_id
                )a GROUP BY cnr_id";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function cancellationSourceReport()
	{
		$sql = "SELECT 	cancelled_by ,
                SUM(IF(cancelLogCreated BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)) AS can_wtd,
                SUM(IF(cancelLogCreated BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)) AS can_week1,
                SUM(IF(cancelLogCreated= DATE_SUB(CURDATE(),INTERVAL 2 DAY),1,0)) AS can_today2,
                SUM(IF(cancelLogCreated= DATE_SUB(CURDATE(),INTERVAL 1 DAY),1,0)) AS can_today1,
                SUM(IF(cancelLogCreated= CURDATE(),1,0)) AS can_today,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(cancelLogCreated,'%m%Y'),1,0)
                ) as can_month1,
                SUM(
                    IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(cancelLogCreated,'%m%Y'),1,0)
                ) as can_mtd
                FROM
                (
                    SELECT
                    booking.bkg_booking_id as booking_id,booking.bkg_create_date as created_date,
                    cancel_reasons.cnr_reason, temp.blg_user_type, DATE(temp.blg_created) as cancelLogCreated ,
                    (CASE temp.blg_user_type
                     	WHEN 1 THEN 'Consumer'
                     	WHEN 4 THEN 'Admin'
                     	WHEN 5 THEN  'Agent'
                     	WHEN 10 THEN 'System'
                      END
                    ) as cancelled_by
                    FROM `booking`
                    INNER JOIN
                    (
                        SELECT booking_log.blg_user_type,booking_log.blg_created,booking_log.blg_booking_id from booking_log where 1  AND booking_log.blg_event_id IN (10,82)  AND  booking_log.blg_created >= (DATE_SUB(NOW(), INTERVAL 1 YEAR)) and  booking_log.blg_event_id IN (10, 82)
                        GROUP BY blg_booking_id ORDER BY booking_log.blg_created desc
                    )  temp on temp.blg_booking_id=booking.bkg_id  AND booking.bkg_status IN (9)
                    INNER JOIN `cancel_reasons` ON cancel_reasons.cnr_id=booking.bkg_cancel_id AND cancel_reasons.cnr_active=1
                    WHERE 1
                    GROUP BY booking.bkg_booking_id)a GROUP BY cancelled_by";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function bookingByZoneReport($limit = 0)
	{
		$lastMonthStartDate	 = date('Y-m-d', strtotime("first day of -1 month"));
		$lastMonthEndDate	 = date('Y-m-d', strtotime("last day of -1 month"));
		$monthStartDate		 = date('Y-m-01');
		$monthEndDate		 = date('Y-m-d');
		$yearfirstDate		 = date("Y-01-01");

		$sql = "SELECT 	DISTINCT(zones.zon_id), zones.zon_name,
		IF(last_month_booking_created>0,last_month_booking_created,0) as last_month_booking_created,
                IF(last_month_booking_completed>0,last_month_booking_completed,0) as last_month_booking_completed,
                IF(month_booking_created>0,month_booking_created,0) as month_booking_created,
                IF(month_booking_completed>0,month_booking_completed,0) as month_booking_completed,
                IF(yld_booking_created>0,yld_booking_created,0) as yld_booking_created,
                IF(yld_booking_completed>0,yld_booking_completed,0) as yld_booking_completed
                FROM zones
                LEFT JOIN (
                    SELECT COUNT(1) as last_month_booking_created, zone_cities.zct_zon_id
                    FROM booking
                    INNER JOIN zone_cities ON zone_cities.zct_cty_id=booking.bkg_from_city_id
                    INNER JOIN zones ON zones.zon_id=zone_cities.zct_zon_id
                    WHERE date(booking.bkg_create_date) BETWEEN '$lastMonthStartDate' AND '$lastMonthEndDate'
                    AND booking.bkg_status IN (2,3,5,6,7,9)
                    AND booking.bkg_active=1
                    GROUP BY zones.zon_id
                ) a ON a.zct_zon_id=zones.zon_id
                LEFT JOIN (
                    SELECT COUNT(1) as last_month_booking_completed, zone_cities.zct_zon_id
                    FROM booking
                    INNER JOIN zone_cities ON zone_cities.zct_cty_id=booking.bkg_from_city_id
                    INNER JOIN zones ON zones.zon_id=zone_cities.zct_zon_id
                    WHERE date(booking.bkg_pickup_date) BETWEEN '$lastMonthStartDate' AND '$lastMonthEndDate'
                    AND booking.bkg_active=1
                    AND booking.bkg_status IN (6,7)
                    GROUP BY zones.zon_id
                ) b ON b.zct_zon_id=zones.zon_id
                LEFT JOIN (
                    SELECT COUNT(1) as month_booking_created, zone_cities.zct_zon_id
                    FROM booking
                    INNER JOIN zone_cities ON zone_cities.zct_cty_id=booking.bkg_from_city_id
                    INNER JOIN zones ON zones.zon_id=zone_cities.zct_zon_id
                    WHERE date(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND CURDATE()
                    AND booking.bkg_status IN (2,3,5,6,7,9)
                    AND booking.bkg_active=1
                    GROUP BY zones.zon_id
                ) c ON c.zct_zon_id=zones.zon_id
                LEFT JOIN (
                    SELECT COUNT(1) as month_booking_completed, zone_cities.zct_zon_id
                    FROM booking
                    INNER JOIN zone_cities ON zone_cities.zct_cty_id=booking.bkg_from_city_id
                    INNER JOIN zones ON zones.zon_id=zone_cities.zct_zon_id
                    WHERE date(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND NOW()
                    AND booking.bkg_active=1
                    AND booking.bkg_status IN (6,7)
                    GROUP BY zones.zon_id
                ) d ON d.zct_zon_id=zones.zon_id
                LEFT JOIN (
                    SELECT COUNT(1) as yld_booking_created, zone_cities.zct_zon_id
                    FROM booking
                    INNER JOIN zone_cities ON zone_cities.zct_cty_id=booking.bkg_from_city_id
                    INNER JOIN zones ON zones.zon_id=zone_cities.zct_zon_id
                    WHERE date(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-01-01') AND CURDATE()
                    AND booking.bkg_status IN (2,3,5,6,7,9)
                    AND booking.bkg_active=1
                    GROUP BY zones.zon_id
                ) e ON e.zct_zon_id=zones.zon_id
                LEFT JOIN (
                    SELECT COUNT(1) as yld_booking_completed, zone_cities.zct_zon_id
                    FROM booking
                    INNER JOIN zone_cities ON zone_cities.zct_cty_id=booking.bkg_from_city_id
                    INNER JOIN zones ON zones.zon_id=zone_cities.zct_zon_id
                    WHERE date(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(NOW(),'%Y-01-01') AND CURDATE()
                    AND booking.bkg_active=1
                    AND booking.bkg_status IN (6,7)
                    GROUP BY zones.zon_id
                ) f ON f.zct_zon_id=zones.zon_id
                WHERE zones.zon_active=1";
		if($limit > 0)
		{
			$sql .= " LIMIT 0,$limit";
		}
		return DBUtil::queryAll($sql);
	}

	public function advancePaymentReport()
	{
		$month1StartDate = date('Y-m-d', strtotime("first day of -1 month"));
		$month1EndDate	 = date('Y-m-d', strtotime("last day of -1 month"));
		$month2StartDate = date('Y-m-d', strtotime("first day of -2 month"));
		$month2EndDate	 = date('Y-m-d', strtotime("last day of -2 month"));
		$month3StartDate = date('Y-m-d', strtotime("first day of -3 month"));
		$month3EndDate	 = date('Y-m-d', strtotime("last day of -3 month"));
		$mldFirstDate	 = date("Y-m-01");
		$mldEndDate		 = date("Y-m-d");
		$status			 = [2, 3, 4, 5, 6, 7];
		$status			 = implode(',', $status);
		$sql			 = "SELECT total_count_mtd, total_count_month1, total_count_month2, total_count_month3,
                        total_amt_mtd, total_amt_month1, total_amt_month2, total_amt_month3,
                        advance_count_month1, advance_count_month2, advance_count_month3, advance_count_mtd,
                        advance_amt_month1 ,advance_amt_month2, advance_amt_month3, advance_amt_mtd
                  FROM (
                        SELECT
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as total_count_month1,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as total_count_month2,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as total_count_month3,
                        SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as total_count_mtd,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_total_amount,'0')) as total_amt_month1,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_total_amount,'0')) as total_amt_month2,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_total_amount,'0')) as total_amt_month3,
                        SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_total_amount,'0')) as total_amt_mtd
                        FROM booking
                        INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                        WHERE booking.bkg_active=1 AND booking.bkg_status IN (2)
                        AND date(booking.bkg_create_date) >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%Y-%m-01')
                      ) a,
                        (
                             SELECT
                                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as advance_count_month1,
                                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as advance_count_month2,
                                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as advance_count_month3,
                                SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as advance_count_mtd,
                                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_advance_amount,'0')) as advance_amt_month1,
                                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_advance_amount,'0')) as advance_amt_month2,
                                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_advance_amount,'0')) as advance_amt_month3,
                                SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_advance_amount,'0')) as advance_amt_mtd

                             FROM `booking`
                             INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                             WHERE booking.bkg_active=1 AND booking.bkg_status IN (2)
                             AND date(booking.bkg_create_date) >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%Y-%m-01')
                             AND biv.bkg_advance_amount>0
                        ) b";
		return DBUtil::queryRow($sql);
	}

	public function currentFutureBookingReport()
	{
		$status		 = [2, 3, 4, 5, 6, 7, 9];
		$status		 = implode(',', $status);
		$startDate	 = date('Y-m-d', strtotime("first day of -3 month"));
		$endDate	 = date("Y-m-d");
		$sql		 = "SELECT  total_count_month1, total_count_month2, total_count_month3, total_count_mtd, total_count ,
                        total_pickup30_count, total_count_pickup30_month1, total_count_pickup30_month2, total_count_pickup30_month3, total_count_pickup_30_mtd,
                        total_count_pickup90_month1, total_count_pickup90_month2, total_count_pickup90_month3, total_count_pickup90_mtd,
                        total_count_pickup180_month1 , total_count_pickup180_month2, total_count_pickup180_month3, total_count_pickup180_mtd,
                        total_count_pickup_this_month1, total_count_pickup_this_month2, total_count_pickup_this_month3, total_count_pickup_this_mtd
	          FROM (
                   SELECT
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as total_count_month1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as total_count_month2,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as total_count_month3,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as total_count_mtd,
                    COUNT(1) as total_count
                    FROM `booking`
                    WHERE booking.bkg_active=1 AND booking.bkg_status IN ($status)
                    AND date(booking.bkg_create_date) >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%Y-%m-01')
                ) a,
		(
			SELECT
	                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL 30 DAY),'%m%Y'),'1','0')) as total_count_pickup30_month1,
                    	SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL 30 DAY),'%m%Y'),'1','0')) as total_count_pickup30_month2,
                    	SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL 30 DAY),'%m%Y'),'1','0')) as total_count_pickup30_month3,
                    	SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL 30 DAY),'%m%Y'),'1','0')) as total_count_pickup_30_mtd,
                    	COUNT(1) as total_pickup30_count
                            FROM `booking`
                            WHERE booking.bkg_active=1 AND booking.bkg_status IN ($status)
                            AND date(booking.bkg_create_date) >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%Y-%m-01')
		) b,
                (
			SELECT
	                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL 90 DAY),'%m%Y'),'1','0')) as total_count_pickup90_month1,
                    	SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL 90 DAY),'%m%Y'),'1','0')) as total_count_pickup90_month2,
                    	SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL 90 DAY),'%m%Y'),'1','0')) as total_count_pickup90_month3,
                    	SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL 90 DAY),'%m%Y'),'1','0')) as total_count_pickup90_mtd,
                    	COUNT(1) as total_pickup90_count
                            FROM `booking`
                            WHERE booking.bkg_active=1 AND booking.bkg_status IN ($status)
                            AND date(booking.bkg_create_date) >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%Y-%m-01')
		) c,
                (
			SELECT
	                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL 180 DAY),'%m%Y'),'1','0')) as total_count_pickup180_month1,
                    	SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL 180 DAY),'%m%Y'),'1','0')) as total_count_pickup180_month2,
                    	SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL 180 DAY),'%m%Y'),'1','0')) as total_count_pickup180_month3,
                    	SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL 180 DAY),'%m%Y'),'1','0')) as total_count_pickup180_mtd,
                    	COUNT(1) as total_pickup180_count
                            FROM `booking`
                            WHERE booking.bkg_active=1 AND booking.bkg_status IN ($status)
                            AND date(booking.bkg_create_date) >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%Y-%m-01')
		) d,
                (
			SELECT
	                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE),'%m%Y'),'1','0')) as total_count_pickup_this_month1,
                    	SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE),'%m%Y'),'1','0')) as total_count_pickup_this_month2,
                    	SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE),'%m%Y'),'1','0')) as total_count_pickup_this_month3,
                    	SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(DATE_ADD(booking.bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE),'%m%Y'),'1','0')) as total_count_pickup_this_mtd,
                    	COUNT(1) as total_pickup_this_count
                            FROM `booking`
                            WHERE booking.bkg_active=1 AND booking.bkg_status IN ($status)
                            AND date(booking.bkg_create_date) >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%Y-%m-01')
		) e";
		return DBUtil::queryRow($sql);
	}

	public function lifetimeTripReport()
	{
		$status	 = [2, 3, 4, 5, 6, 7, 9];
		$status	 = implode(',', $status);
		$sql	 = "SELECT trip_1,COUNT(1) as trip_2,trip_5,trip_10 FROM (
	   SELECT IFNULL(bui.bkg_user_id,IF(bui.bkg_user_email ='' OR bui.bkg_user_email IS NULL, bui.bkg_contact_no, bui.bkg_user_email ))as userid ,
		MIN(bkg_create_date) as first_date, COUNT(1) as total_trip
		FROM booking INNER JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id WHERE bkg_status IN ($status) GROUP BY userid HAVING (total_trip>=2 AND total_trip<=5)
		ORDER BY `first_date`  DESC
	    ) a
            ,
            (
                SELECT COUNT(1) as trip_5 FROM (
                        SELECT IFNULL(bui.bkg_user_id,IF(bui.bkg_user_email ='' OR bui.bkg_user_email IS NULL, bui.bkg_contact_no, bui.bkg_user_email ))as userid ,
                        MIN(bkg_create_date) as first_date, COUNT(1) as total_trip
                        FROM booking INNER JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id WHERE bkg_status IN ($status) GROUP BY userid HAVING (total_trip>=5 AND total_trip<=10)
                        ORDER BY `first_date`  DESC
                    ) b
                )bb
            ,
            (
                SELECT COUNT(1) as trip_10 FROM (
                    SELECT IFNULL(bui.bkg_user_id,IF(bui.bkg_user_email ='' OR bui.bkg_user_email IS NULL, bui.bkg_contact_no, bui.bkg_user_email ))as userid ,
                    MIN(bkg_create_date) as first_date, COUNT(1) as total_trip
                    FROM booking INNER JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id WHERE bkg_status IN ($status) GROUP BY userid HAVING (total_trip>10)
                    ORDER BY `first_date`  DESC
    		) c
            ) cc
            ,
            (
                SELECT COUNT(1) as trip_1 FROM (
                    SELECT IFNULL(bui.bkg_user_id,IF(bui.bkg_user_email ='' OR bui.bkg_user_email IS NULL, bui.bkg_contact_no, bui.bkg_user_email ))as userid ,
                    MIN(bkg_create_date) as first_date, COUNT(1) as total_trip
                    FROM booking INNER JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id WHERE bkg_status IN ($status) GROUP BY userid HAVING (total_trip=1)
                    ORDER BY `first_date`  DESC
                    ) d
                ) dd";
		return DBUtil::queryRow($sql);
	}

	public function newRepeatCustomerReport()
	{
		$date	 = date("Y-m-01", strtotime("-3 Months"));
		$status	 = [2, 3, 5, 6, 7, 9];
		$status	 = implode(',', $status);
		$sql	 = "SELECT rep.month, rep.year, repeat_customer, new_customer
                FROM
                (SELECT MONTH(bkg_create_date) as month, YEAR(bkg_create_date) as year , MONTHNAME(bkg_create_date) as MonthName, COUNT(DISTINCT b.userid) as Repeat_Customer, COUNT(b.userid) as Repeat_Booking
                    FROM (
                        SELECT IFNULL(bui.bkg_user_id,IF(bui.bkg_user_email ='' OR bui.bkg_user_email IS NULL, bui.bkg_contact_no, bui.bkg_user_email )) as userid, bkg_create_date
                        FROM booking INNER JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id WHERE booking.bkg_status IN($status) AND booking.bkg_create_date>='$date') b
                    INNER JOIN (
                        SELECT IFNULL(bui.bkg_user_id,IF(bui.bkg_user_email ='' OR bui.bkg_user_email IS NULL, bui.bkg_contact_no, bui.bkg_user_email ))as userid , MIN(bkg_create_date) as first_date, COUNT(1) as total_trip
                        FROM booking INNER JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id WHERE bkg_status IN ($status) GROUP BY userid HAVING total_trip>1) a ON a.userid=b.userid AND a.first_date<>b.bkg_create_date WHERE 1
                        GROUP BY MONTH(bkg_create_date), YEAR(bkg_create_date) ORDER BY year, month
                     ) rep
                    LEFT JOIN (
                        SELECT MONTH(bkg_create_date) as month, YEAR(bkg_create_date) as year , MONTHNAME(bkg_create_date) as MonthName, COUNT(DISTINCT b.userid) as new_Customer
                        FROM (
                            SELECT IFNULL(bui.bkg_user_id,IF(bui.bkg_user_email ='' OR bui.bkg_user_email IS NULL, bui.bkg_contact_no, bui.bkg_user_email )) as userid, bkg_create_date
                            FROM booking INNER JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id  WHERE booking.bkg_status IN($status) AND booking.bkg_create_date>='$date') b
                        INNER JOIN
                        (
                            SELECT IFNULL(bui.bkg_user_id,IF(bui.bkg_user_email ='' OR bui.bkg_user_email IS NULL, bui.bkg_contact_no, bui.bkg_user_email ))as userid ,
                            MIN(bkg_create_date) as first_date, COUNT(1) as total_trip
                            FROM booking INNER JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id WHERE bkg_status IN ($status) GROUP BY userid
                        ) a ON a.userid=b.userid AND a.first_date=b.bkg_create_date WHERE 1 GROUP BY MONTH(bkg_create_date), YEAR(bkg_create_date) ORDER BY year, month
                    )new ON new.month=rep.month
                    LEFT JOIN (
                        SELECT MONTH(bkg_create_date) as month, COUNT(*) as total_booking, COUNT(DISTINCT IFNULL(bui.bkg_user_id,IF(bui.bkg_user_email ='' OR bui.bkg_user_email IS NULL, bui.bkg_contact_no, bui.bkg_user_email ))) as total_customer
                        FROM booking INNER JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id WHERE bkg_status IN ($status) and booking.bkg_create_date>'$date' GROUP BY MONTH(bkg_create_date)
                    ) total ON rep.month=total.month";

		return DBUtil::query($sql, DBUtil::SDB());
	}

	public function bookingByRatingReport()
	{
		$status	 = [2, 3, 4, 5, 6, 7, 9];
		$status	 = implode(',', $status);
		$sql	 = "SELECT
                    ratings.rtg_customer_overall,
                    COUNT(booking.bkg_id) as review_count_lifetime,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as review_count_mtd,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(bkg_create_date,'%Y'),'1','0')) as review_count_ytd,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as review_count_month1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as review_count_month2
                    FROM `ratings`
                    INNER JOIN `booking` ON booking.bkg_id=ratings.rtg_booking_id
                    WHERE 1
					AND ratings.rtg_customer_overall IS NOT NULL
                    AND booking.bkg_active=1
                    AND booking.bkg_status IN ($status)
                    GROUP BY ratings.rtg_customer_overall";
		return DBUtil::queryAll($sql);
		;
	}

	public function bookingByPlatformReport()
	{
		$status	 = [2, 3, 4, 5, 6, 7, 9];
		$status	 = implode(',', $status);
		$sql	 = "SELECT
                    btr.bkg_platform,
                    (
                        CASE btr.bkg_platform
                            WHEN 1 THEN 'User/Web'
                            WHEN 2 THEN 'Admin'
                            WHEN 3 THEN 'App'
                            END
                     ) as platform,
                    COUNT(booking.bkg_id) as booking_count_lifetime,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_count_mtd,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(bkg_create_date,'%Y'),'1','0')) as booking_count_ytd,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_count_month1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_count_month2,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y'),'1','0')) as booking_count_today,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y'),'1','0')) as booking_count_today_1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y'),'1','0')) as booking_count_today_2
                    FROM `booking`
                    INNER JOIN booking_trail as btr ON btr.btr_bkg_id=bkg_id
                    WHERE booking.bkg_active=1 AND booking.bkg_status IN ($status) and btr.bkg_platform IN (1,2,3)
                    GROUP BY btr.bkg_platform";
		return DBUtil::queryAll($sql);
	}

	public function cancelReasonReport()
	{
		$status	 = [2, 3, 4, 5, 6, 7, 9];
		$status	 = implode(',', $status);
		$sql	 = "SELECT
                cancel_reasons.cnr_reason, COUNT(booking.bkg_id) as cancel_count_lifetime,
                SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as cancel_count_mtd,
                SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(bkg_create_date,'%Y'),'1','0')) as cancel_count_ytd,
                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as cancel_count_month1,
                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as cancel_count_month2
                FROM `cancel_reasons`
                INNER JOIN `booking` ON booking.bkg_cancel_id=cancel_reasons.cnr_id
                WHERE booking.bkg_active=1 AND booking.bkg_status IN ($status)
                GROUP BY booking.bkg_cancel_id";
		return DBUtil::queryAll($sql);
	}

	public function bookingBySourceReport()
	{
		$status	 = [2, 3, 4, 5, 6, 7, 9];
		$status	 = implode(',', $status);
		$sql	 = "SELECT IF(booking_add_info.bkg_info_source !='',booking_add_info.bkg_info_source,'No answer') as sourceInfo,
                COUNT(booking.bkg_id) as source_count_lifetime,
                SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(bkg_create_date,'%Y'),'1','0')) as source_count_ytd,
                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as source_count_month3,
                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as source_count_month2,
                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as source_count_month1,
                SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as source_count_mtd
                FROM `booking` INNER JOIN `booking_add_info` ON booking.bkg_id = booking_add_info.bad_bkg_id
                WHERE booking.bkg_active=1 AND booking.bkg_status IN ($status)
                GROUP BY booking_add_info.bkg_info_source
                HAVING booking_add_info.bkg_info_source IN ('Internet','Newspaper','Hoarding','SMS','Ixigo','Quikr','Upsell SMS','Leaflet','Just Dial','Kiosk','Word Of Mouth','Movie Theatre','Other Media','Google','Facebook','Print media','Radio','Friend','Other',' ')
                ORDER BY sourceInfo ASC";
		return DBUtil::queryAll($sql);
	}

	public function getRevenueReport()
	{
		$status		 = [2, 3, 4, 5, 6, 7];
		$status		 = implode(',', $status);
		$sql_primary = "SELECT * FROM ($sql) a ";
		$sql		 = "SELECT
							SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y'),biv.bkg_total_amount,0)) as booking_gmv_today,
							SUM(IF((DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,0)) as booking_gmv_active_today,
							SUM(IF((DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,0)) as booking_gmv_comp_today,
							SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y'),biv.bkg_vendor_amount,0)) as booking_vendor_today,
							SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y'),biv.bkg_advance_amount,0)) as booking_advance_today,
							SUM(IF((DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5,6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_today,
							SUM(IF((DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_comp_today,
							SUM(IF((DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_act_today,
							SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y'),biv.bkg_convenience_charge,0)) as booking_cod_today,
							SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(bkg_create_date,'%d%m%Y'),biv.bkg_service_tax,0)) as booking_stax_today,

							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_total_amount,0)) as booking_gmv_today1,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,0)) as booking_gmv_active_today1,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,0)) as booking_gmv_comp_today1,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_vendor_amount,0)) as booking_vendor_today1,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_advance_amount,0)) as booking_advance_today1,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5,6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_today1,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_comp_today1,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_act_today1,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_convenience_charge,0)) as booking_cod_today1,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_service_tax,0)) as booking_stax_today1,

							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_total_amount,0)) as booking_gmv_today2,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,0)) as booking_gmv_active_today2,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,0)) as booking_gmv_comp_today2,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_vendor_amount,0)) as booking_vendor_today2,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_advance_amount,0)) as booking_advance_today2,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5,6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_today2,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_comp_today2,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_act_today2,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_convenience_charge,0)) as booking_cod_today2,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_service_tax,0)) as booking_stax_today2,

							SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_total_amount,0)) as booking_gmv_mtd,
							SUM(IF((DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,0)) as booking_gmv_active_mtd,
							SUM(IF((DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,0)) as booking_gmv_comp_mtd,
							SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_vendor_amount,0)) as booking_vendor_mtd,
							SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_advance_amount,0)) as booking_advance_mtd,
							SUM(IF((DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y') AND booking.bkg_status IN (2,3,5,6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_mtd,
							SUM(IF((DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y') AND booking.bkg_status IN (6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_comp_mtd,
							SUM(IF((DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y') AND booking.bkg_status IN (2,3,5)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_act_mtd,
							SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_convenience_charge,0)) as booking_cod_mtd,
							SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_service_tax,0)) as booking_stax_mtd,

							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_total_amount,0)) as booking_gmv_month1,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,0)) as booking_gmv_active_month1,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,0)) as booking_gmv_comp_month1,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_vendor_amount,0)) as booking_vendor_month1,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_advance_amount,0)) as booking_advance_month1,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y') AND booking.bkg_status IN (2,3,5,6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gozo_month1,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y') AND booking.bkg_status IN (6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gozo_comp_month1,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y') AND booking.bkg_status IN (2,3,5)),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gozo_act_month1,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_convenience_charge,'0')) as booking_cod_month1,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_service_tax,'0')) as booking_stax_month1,

							SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_total_amount,0)) as booking_gmv_ytd,
							SUM(IF((DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,0)) as booking_gmv_active_ytd,
							SUM(IF((DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,0)) as booking_gmv_comp_ytd,
							SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_vendor_amount,0)) as booking_vendor_ytd,
							SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_advance_amount,0)) as booking_advance_ytd,
							SUM(IF((DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (2,3,5,6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_ytd,
							SUM(IF((DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_comp_ytd,
							SUM(IF((DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (2,3,5)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_act_ytd,
							SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_convenience_charge,0)) as booking_cod_ytd,
							SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_service_tax,0)) as booking_stax_ytd,

							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_total_amount,0)) as booking_gmv_last_year,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,0)) as booking_gmv_active_last_year,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,0)) as booking_gmv_comp_last_year,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_vendor_amount,0)) as booking_vendor_last_year,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_advance_amount,0)) as booking_advance_last_year,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (2,3,5,6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_last_year,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (6,7)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_comp_last_year,
							SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (2,3,5)),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as booking_gozo_act_last_year,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_convenience_charge,0)) as booking_cod_last_year,
							SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_service_tax,0)) as booking_stax_last_year
							FROM `booking`
							INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=booking.bkg_id
							WHERE booking.bkg_status IN (2,3,4,5,6,7) AND booking.bkg_active=1";
		$sql_revenue = "SELECT * FROM ($sql) a ";
		$sql_revenue .= ", (
                            SELECT SUM(totTrans) as receive_pending FROM(
                            SELECT vnd_id, vnd_name , SUM(adt.adt_amount) totTrans, vnd.vnd_active
                            FROM `vendors` vnd
                             JOIN account_trans_details adt ON vnd.vnd_id = adt.adt_trans_ref_id
                             JOIN account_transactions act ON act.act_id = adt.adt_trans_id
                            WHERE act.act_active=1 AND adt_active=1 AND adt_status=1 AND adt_type=2 AND adt_ledger_id=14
                            GROUP BY adt.adt_trans_ref_id
                        )b
                        ) c";
		$sql_revenue .= ", (
                            SELECT SUM(IF(DATE_FORMAT(CURDATE(), '%d%m%Y') = DATE_FORMAT(act.act_date, '%d%m%Y'), adt.adt_amount, '0')) AS ven_trans_today
                          , SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%d%m%Y') = DATE_FORMAT(act.act_date, '%d%m%Y'),
                          adt.adt_amount, '0')) AS ven_trans_today1
                          , SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY), '%d%m%Y') = DATE_FORMAT(act.act_date, '%d%m%Y'),
                          adt.adt_amount, '0')) AS ven_trans_today2
                          , SUM(IF(DATE_FORMAT(CURDATE(), '%m%Y') = DATE_FORMAT(act.act_date, '%m%Y'),
                          adt.adt_amount, '0')) AS ven_trans_mtd
                          , SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%m%Y') = DATE_FORMAT(act.act_date, '%m%Y'),
                          adt.adt_amount, '0')) AS ven_trans_month1
                          , SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH), '%m%Y') = DATE_FORMAT(act.act_date, '%m%Y'),
                          adt.adt_amount, '0')) AS ven_trans_month2
                          , SUM(IF(DATE_FORMAT(CURDATE(), '%Y') = DATE_FORMAT(act.act_date, '%Y'),
                          adt.adt_amount, '0')) AS ven_trans_ytd
                          , SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR), '%Y') = DATE_FORMAT(act.act_date, '%Y'),
                          adt.adt_amount, '0')) AS ven_trans_last_year
                   FROM   account_trans_details adt
                   JOIN account_transactions act ON act.act_id = adt.adt_trans_id
                   WHERE act.act_active=1 AND adt_active = 1 AND adt_active = 1 AND adt_amount > 0 AND adt_type=2 AND adt_ledger_id=14
              )d,
                         (
                        SELECT SUM(IF(DATE_FORMAT(CURDATE(), '%d%m%Y') = DATE_FORMAT(act.act_date, '%d%m%Y'),adt.adt_amount, '0')) AS ven_trans_today
                        , SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%d%m%Y') = DATE_FORMAT(act.act_date, '%d%m%Y'),
                        adt.adt_amount, '0')) AS ven_trans_today1
                        , SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY), '%d%m%Y') = DATE_FORMAT(act.act_date, '%d%m%Y'),
                        adt.adt_amount, '0')) AS ven_trans_today2
                        , SUM(IF(DATE_FORMAT(CURDATE(), '%m%Y') = DATE_FORMAT(act.act_date, '%m%Y'),
                        adt.adt_amount, '0')) AS ven_trans_mtd
                        , SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%m%Y') = DATE_FORMAT(act.act_date, '%m%Y'),
                        adt.adt_amount, '0')) AS ven_trans_month1
                        , SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH), '%m%Y') = DATE_FORMAT(act.act_date, '%m%Y'),
                        adt.adt_amount, '0')) AS ven_trans_month2
                        , SUM(IF(DATE_FORMAT(CURDATE(), '%Y') = DATE_FORMAT(act.act_date, '%Y'),
                        adt.adt_amount, '0')) AS ven_trans_ytd
                        , SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR), '%Y') = DATE_FORMAT(act.act_date, '%Y'),
                        adt.adt_amount, '0')) AS ven_trans_last_year
                       FROM   account_trans_details adt
                        JOIN account_transactions act ON act.act_id = adt.adt_trans_id
                       WHERE act.act_active=1 AND adt_active = 1 AND adt_active = 1 AND adt_amount > 0 AND adt_type=2 AND adt_ledger_id=14
                            )e";

		return DBUtil::command($sql_revenue)->queryRow();
	}

	public function getRevenueReportByPickup()
	{

		$sql = "SELECT  * FROM
                (
                    SELECT  SUM(IF(biv.bkg_total_amount>0,biv.bkg_total_amount,0)) as rev_mtd_comp,
						SUM(IF(biv.bkg_vendor_amount>0,biv.bkg_vendor_amount,0)) as vamt_mtd_comp,
						SUM(IF(biv.bkg_gozo_amount>0,(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as gamt_mtd_comp,
						SUM(IF(biv.bkg_advance_amount>0,(biv.bkg_advance_amount-biv.bkg_refund_amount),0)) as adv_mtd_comp,
						SUM(IF(biv.bkg_convenience_charge>0,biv.bkg_convenience_charge,0)) as cod_mtd_comp,
						SUM(IF(biv.bkg_service_tax>0,biv.bkg_service_tax,0)) as stax_mtd_comp
						FROM `booking_cab`
						INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1 AND booking.bkg_status IN (6,7)
						INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
						WHERE 1
						AND booking_cab.bcb_active=1
						AND booking.bkg_pickup_date BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01 00:00:00') AND  DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-01 23:59:59'), INTERVAL 30 DAY)
                )a,
                (
                    SELECT  SUM(IF(biv.bkg_total_amount>0,biv.bkg_total_amount,0)) as rev_ytd_comp,
                            SUM(IF(biv.bkg_vendor_amount>0,biv.bkg_vendor_amount,0)) as vamt_ytd_comp,
                            SUM(IF(biv.bkg_gozo_amount>0,(biv.bkg_gozo_amount-biv.bkg_service_tax),0)) as gamt_ytd_comp,
                            SUM(IF(biv.bkg_advance_amount>0,(biv.bkg_advance_amount-biv.bkg_refund_amount),0)) as adv_ytd_comp,
                            SUM(IF(biv.bkg_convenience_charge>0,biv.bkg_convenience_charge,0)) as cod_ytd_comp,
                            SUM(IF(biv.bkg_service_tax>0,biv.bkg_service_tax,0)) as stax_ytd_comp
                            FROM `booking_cab`
                            INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id
                            AND booking.bkg_active=1 AND booking.bkg_status IN (6,7)
                            INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                            WHERE 1
                    	    AND booking_cab.bcb_active=1
                            AND booking.bkg_pickup_date BETWEEN DATE_FORMAT(NOW(),'%Y-01-01 00:00:00') AND  CONCAT(CURDATE(),' 23:59:59')
                )b,
                (
                    SELECT  SUM(biv.bkg_total_amount) as rev_mtd_active,
							SUM(biv.bkg_vendor_amount) as vamt_mtd_active,
							SUM(biv.bkg_gozo_amount-biv.bkg_service_tax) as gamt_mtd_active,
							SUM(biv.bkg_advance_amount-biv.bkg_refund_amount) as adv_mtd_active,
							SUM(biv.bkg_convenience_charge) as cod_mtd_active,
							SUM(biv.bkg_service_tax) as stax_mtd_active
							FROM `booking_cab`
							INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1 AND booking.bkg_status IN (2,3,5)
							INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
							WHERE 1
							AND booking_cab.bcb_active=1
							AND booking.bkg_pickup_date BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01 00:00:00') AND  DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-01 23:59:59'), INTERVAL 30 DAY)

                )c,
                (
                     SELECT SUM(biv.bkg_total_amount) as rev_mtd_future,
							SUM(biv.bkg_vendor_amount) as vamt_mtd_future ,
							SUM(biv.bkg_gozo_amount-biv.bkg_service_tax) as gamt_mtd_future,
							SUM(biv.bkg_advance_amount-biv.bkg_refund_amount) as adv_mtd_future,
							SUM(biv.bkg_convenience_charge) as cod_mtd_future,
							SUM(biv.bkg_service_tax) as stax_mtd_future
							FROM `booking_cab`
							INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1 AND booking.bkg_status IN (2,3,5)
							INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
							WHERE 1
							AND booking_cab.bcb_active=1
							AND booking.bkg_pickup_date BETWEEN DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-01 00:00:00'), INTERVAL 30 DAY) AND  DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-01 23:59:59'), INTERVAL 60 DAY)

                )d,
                (
                    SELECT
                        ABS(SUM(
                            IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(vendor_transactions.ven_trans_date,'%m%Y'),vendor_transactions.ven_trans_amount,'0')
                        )) as payable_mtd,
                        ABS(SUM(
                            IF(
                                (DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(vendor_transactions.ven_trans_date,'%m%Y')) AND ven_trip_id IS NULL AND ven_ptp_id <> 7
                        ,vendor_transactions.ven_trans_amount,'0')
                        )) as paid_mtd,
                        ABS(SUM(
                            IF(
                                DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(vendor_transactions.ven_trans_date,'%Y'),vendor_transactions.ven_trans_amount,'0')
                        )) as payable_ytd,
                        ABS(SUM(
                            IF(
                                    (DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(vendor_transactions.ven_trans_date,'%Y')) AND ven_trip_id IS NULL AND ven_ptp_id <> 7 ,vendor_transactions.ven_trans_amount,'0')
                        )) as paid_ytd
                        FROM `vendor_transactions`
                        WHERE ven_trans_active=1
                        AND ven_trans_amount<0
                )e,
                (
                    SELECT
                        ABS(SUM(
                            IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(vendor_transactions.ven_trans_date,'%m%Y'),vendor_transactions.ven_trans_amount,'0')
                        )) as receivable_mtd,
                        ABS(SUM(
                            IF(
                                (DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(vendor_transactions.ven_trans_date,'%m%Y')) AND ven_trip_id IS NULL AND ven_ptp_id <> 7
                        ,vendor_transactions.ven_trans_amount,'0')
                        )) as receive_mtd,
                        ABS(SUM(
                            IF(
                                DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(vendor_transactions.ven_trans_date,'%Y'),vendor_transactions.ven_trans_amount,'0')
                        )) as receivable_ytd,
                        ABS(SUM(
                            IF(
                                (DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(vendor_transactions.ven_trans_date,'%Y')) AND ven_trip_id IS NULL AND ven_ptp_id <> 7 ,vendor_transactions.ven_trans_amount,'0')
                        )) as receive_ytd
                        FROM `vendor_transactions`
                        WHERE ven_trans_active=1
                        AND ven_trans_amount>0
                )f";

		return DBUtil::queryRow($sql);
	}

	public function getPLTrendReport()
	{

		$sql = "SELECT * FROM
                (
                    SELECT
                    SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_complete_today_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_complete_today1_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_complete_today2_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_complete_today3_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_complete_month1_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_complete_month2_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_complete_month3_count,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_complete_mtd_count,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),'1','0')) as booking_complete_ytd_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),'1','0')) as booking_complete_last_year_count,
                    COUNT(DISTINCT booking.bkg_id) as booking_complete_lifetime_count
                    FROM `booking`
                    WHERE booking.bkg_active=1
                    AND booking.bkg_status IN (6,7)
                )a,
                (
                    SELECT
                    SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_incomplete_today_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_incomplete_today1_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_incomplete_today2_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),'1','0')) as booking_incomplete_today3_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_incomplete_month1_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_incomplete_month2_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_incomplete_month3_count,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1','0')) as booking_incomplete_mtd_count,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),'1','0')) as booking_incomplete_ytd_count,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),'1','0')) as booking_incomplete_last_year_count,
                    COUNT(DISTINCT booking.bkg_id) as booking_incomplete_lifetime_count
                    FROM `booking`
                    WHERE booking.bkg_active=1
                    AND booking.bkg_status IN (2,3,5)
                ) b,
                (
                    SELECT
                    SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_total_amount,'0')) as booking_gmv_today,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_total_amount,'0')) as booking_gmv_today1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_total_amount,'0')) as booking_gmv_today2,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_total_amount,'0')) as booking_gmv_today3,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_total_amount,'0')) as booking_gmv_month1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_total_amount,'0')) as booking_gmv_month2,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_total_amount,'0')) as booking_gmv_month3,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_total_amount,'0')) as booking_gmv_mtd,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_total_amount,'0')) as booking_gmv_ytd,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_total_amount,'0')) as booking_gmv_last_year,
                    SUM(biv.bkg_total_amount) as booking_gmv_lifetime,

                    SUM(IF((DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,'0')) as booking_gmv_active_today,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,'0')) as booking_gmv_active_today1,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,'0')) as booking_gmv_active_today2,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,'0')) as booking_gmv_active_today3,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,'0')) as booking_gmv_active_month1,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,'0')) as booking_gmv_active_month2,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,'0')) as booking_gmv_active_month3,
                    SUM(IF((DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,'0')) as booking_gmv_active_mtd,
                    SUM(IF((DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,'0')) as booking_gmv_active_ytd,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (2,3,5)),biv.bkg_total_amount,'0')) as booking_gmv_active_last_year,
                    SUM(IF(booking.bkg_status IN (2,3,5),biv.bkg_total_amount,'0')) as booking_gmv_active_lifetime,

                    SUM(IF((DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,'0')) as booking_gmv_comp_today,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,'0')) as booking_gmv_comp_today1,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,'0')) as booking_gmv_comp_today2,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,'0')) as booking_gmv_comp_today3,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,'0')) as booking_gmv_comp_month1,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,'0')) as booking_gmv_comp_month2,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,'0')) as booking_gmv_comp_month3,
                    SUM(IF((DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,'0')) as booking_gmv_comp_mtd,
                    SUM(IF((DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,'0')) as booking_gmv_comp_ytd,
                    SUM(IF((DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y') AND booking.bkg_status IN (6,7)),biv.bkg_total_amount,'0')) as booking_gmv_comp_last_year,
                    SUM(IF(booking.bkg_status IN (6,7),biv.bkg_total_amount,'0')) as booking_gmv_comp_lifetime
                    FROM `booking`
                    INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                    WHERE booking.bkg_active=1
                    AND booking.bkg_status IN (2,3,5,6,7)
                ) c,
                 (
                    SELECT
                    SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_service_tax,'0')) as booking_stax_today,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_service_tax,'0')) as booking_stax_today1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_service_tax,'0')) as booking_stax_today2,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_service_tax,'0')) as booking_stax_today3,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_service_tax,'0')) as booking_stax_month1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_service_tax,'0')) as booking_stax_month2,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_service_tax,'0')) as booking_stax_month3,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_service_tax,'0')) as booking_stax_mtd,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_service_tax,'0')) as booking_stax_ytd,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_service_tax,'0')) as booking_stax_last_year,
                    SUM(biv.bkg_service_tax) as booking_stax_lifetime
                    FROM `booking`
                    INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                    WHERE booking.bkg_active=1
                    AND booking.bkg_status IN (2,3,5,6,7)
                ) d,
                (
                    SELECT
                    SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_gozo_amount,'0')) as booking_gamt_today,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_gozo_amount,'0')) as booking_gamt_today1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_gozo_amount,'0')) as booking_gamt_today2,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_gozo_amount,'0')) as booking_gamt_today3,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_gozo_amount,'0')) as booking_gamt_month1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_gozo_amount,'0')) as booking_gamt_month2,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_gozo_amount,'0')) as booking_gamt_month3,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_gozo_amount,'0')) as booking_gamt_mtd,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_gozo_amount,'0')) as booking_gamt_ytd,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_gozo_amount,'0')) as booking_gamt_last_year,
                    SUM(biv.bkg_gozo_amount) as booking_gamt_lifetime
                    FROM `booking`
                    INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                    WHERE booking.bkg_active=1
                    AND booking.bkg_status IN (2,3,5,6,7)
                ) e1,
                 (
                    SELECT
                    SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gprofit_today,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gprofit_today1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gprofit_today2,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gprofit_today3,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gprofit_month1,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gprofit_month2,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gprofit_month3,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gprofit_mtd,
                    SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gprofit_ytd,
                    SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),'0')) as booking_gprofit_last_year,
                    SUM((biv.bkg_gozo_amount-biv.bkg_service_tax)) as booking_gproft_lifetime
                    FROM `booking`
                    INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                    WHERE booking.bkg_active=1
                    AND booking.bkg_status IN (2,3,5,6,7)
                ) e2,
                (
                    SELECT
					SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_vendor_amount,'0')) as booking_vamt_today,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_vendor_amount,'0')) as booking_vamt_today1,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_vendor_amount,'0')) as booking_vamt_today2,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_vendor_amount,'0')) as booking_vamt_today3,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_vendor_amount,'0')) as booking_vamt_month1,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_vendor_amount,'0')) as booking_vamt_month2,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_vendor_amount,'0')) as booking_vamt_month3,
					SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_vendor_amount,'0')) as booking_vamt_mtd,
					SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_vendor_amount,'0')) as booking_vamt_ytd,
					SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_vendor_amount,'0')) as booking_vamt_last_year,
					SUM(biv.bkg_vendor_amount) as booking_vamt_lifetime
					FROM `booking`
					INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
					WHERE booking.bkg_active=1
					AND booking.bkg_status IN (2,3,5,6,7)
                ) f,
                (
                    SELECT
                        SUM(
                            IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),IF(btr.bkg_non_profit_flag=1,1,0),0)
                        ) as booking_non_profit_today,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),IF(btr.bkg_non_profit_flag=1,1,0),0)
                        ) as booking_non_profit_today1,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),IF(btr.bkg_non_profit_flag=1,1,0),0)
                        ) as booking_non_profit_today2,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),IF(btr.bkg_non_profit_flag=1,1,0),0)
                        ) as booking_non_profit_today3,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),IF(btr.bkg_non_profit_flag=1,1,0),0)
                        ) as booking_non_profit_month1,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),IF(btr.bkg_non_profit_flag=1,1,0),0)
                        ) as booking_non_profit_month2,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),IF(btr.bkg_non_profit_flag=1,1,0),0)
                        ) as booking_non_profit_month3,
                        SUM(
                            IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),IF(btr.bkg_non_profit_flag=1,1,0),0)
                        ) as booking_non_profit_mtd,
                        SUM(
                            IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),IF(btr.bkg_non_profit_flag=1,1,0),0)
                        ) as booking_non_profit_ytd,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),IF(btr.bkg_non_profit_flag=1,1,0),0)
                        ) as booking_non_profit_last_year,
                        SUM(IF(btr.bkg_non_profit_flag=1,1,0)) as booking_non_profit_lifetime
                        FROM `booking`
                        INNER JOIN booking_trail as btr ON btr.btr_bkg_id=bkg_id
                        WHERE booking.bkg_active=1
                        AND booking.bkg_status IN (2,3,5,6,7,9)
                )g,
                (
                    SELECT
                        SUM(
                            IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),IF(btr.bkg_non_profit_flag=1,(biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax),0),0)
                        ) as booking_non_profit_amt_today,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),IF(btr.bkg_non_profit_flag=1,(biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax),0),0)
                        ) as booking_non_profit_amt_today1,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),IF(btr.bkg_non_profit_flag=1,(biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax),0),0)
                        ) as booking_non_profit_amt_today2,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),IF(btr.bkg_non_profit_flag=1,(biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax),0),0)
                        ) as booking_non_profit_amt_today3,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),IF(btr.bkg_non_profit_flag=1,(biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax),0),0)
                        ) as booking_non_profit_amt_month1,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),IF(btr.bkg_non_profit_flag=1,(biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax),0),0)
                        ) as booking_non_profit_amt_month2,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),IF(btr.bkg_non_profit_flag=1,(biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax),0),0)
                        ) as booking_non_profit_amt_month3,
                        SUM(
                            IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),IF(btr.bkg_non_profit_flag=1,(biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax),0),0)
                        ) as booking_non_profit_amt_mtd,
                        SUM(
                            IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),IF(btr.bkg_non_profit_flag=1,(biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax),0),0)
                        ) as booking_non_profit_amt_ytd,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),IF(btr.bkg_non_profit_flag=1,(biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax),0),0)
                        ) as booking_non_profit_amt_last_year,
                        SUM(IF(btr.bkg_non_profit_flag=1,(biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax),0)) as booking_non_profit_amt_lifetime
                        FROM `booking`
                        INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                        INNER JOIN booking_trail as btr ON btr.btr_bkg_id=bkg_id
                        WHERE booking.bkg_active=1
                        AND booking.bkg_status IN (2,3,5,6,7,9)
                )g1,
                (
                    SELECT
                        SUM(
                            IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),IF(btr.bkg_non_profit_flag=1 AND btr.bkg_non_profit_override_flag=1,1,0),0)
                        ) as booking_non_profit_override_today,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),IF(btr.bkg_non_profit_flag=1 AND btr.bkg_non_profit_override_flag=1,1,0),0)
                        ) as booking_non_profit_override_today1,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),IF(btr.bkg_non_profit_flag=1 AND btr.bkg_non_profit_override_flag=1,1,0),0)
                        ) as booking_non_profit_override_today2,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),IF(btr.bkg_non_profit_flag=1 AND btr.bkg_non_profit_override_flag=1,1,0),0)
                        ) as booking_non_profit_override_today3,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),IF(btr.bkg_non_profit_flag=1 AND btr.bkg_non_profit_override_flag=1,1,0),0)
                        ) as booking_non_profit_override_month1,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),IF(btr.bkg_non_profit_flag=1 AND btr.bkg_non_profit_override_flag=1,1,0),0)
                        ) as booking_non_profit_override_month2,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),IF(btr.bkg_non_profit_flag=1 AND btr.bkg_non_profit_override_flag=1,1,0),0)
                        ) as booking_non_profit_override_month3,
                        SUM(
                            IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),IF(btr.bkg_non_profit_flag=1 AND btr.bkg_non_profit_override_flag=1,1,0),0)
                        ) as booking_non_profit_override_mtd,
                        SUM(
                            IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),IF(btr.bkg_non_profit_flag=1 AND btr.bkg_non_profit_override_flag=1,1,0),0)
                        ) as booking_non_profit_override_ytd,
                        SUM(
                            IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),IF(btr.bkg_non_profit_flag=1 AND btr.bkg_non_profit_override_flag=1,1,0),0)
                        ) as booking_non_profit_override_last_year,
                        SUM(
                            IF(btr.bkg_non_profit_flag=1 AND btr.bkg_non_profit_override_flag=1,1,0)
                        ) as booking_non_profit_override_lifetime
                        FROM `booking`
                        INNER JOIN booking_trail as btr ON btr.btr_bkg_id=bkg_id
                        WHERE booking.bkg_active=1
                        AND booking.bkg_status IN (2,3,5,6,7,9)

                )h";
		return DBUtil::queryRow($sql);
	}

	public function inventoryMetricsReport()
	{
		$sql = "SELECT * FROM (
                        SELECT COUNT(DISTINCT d2.drv_id) as count_drivers_system,
                        SUM(
                            IF(d2.drv_approved=1,1,0)
                        ) as count_drivers_approved,
                        SUM(
                            IF(d2.drv_ver_adrs_proof=1,1,0)
                        ) as cout_drivers_adddress_proof,
                        SUM(
                            IF(d2.drv_ver_licence=1,1,0)
                        ) as cout_drivers_licence_proof,
                        SUM(
                            IF(d2.drv_ver_police_certificate=1,1,0)
                        ) as cout_drivers_police_certificate
                        FROM `drivers`
						INNER JOIN drivers d2 on d2.drv_id=drivers.drv_ref_code
                        WHERE d2.drv_active=1
                    )a,
                    (
                        SELECT COUNT(1) as count_car_system,
                        SUM(
                            IF(vehicles.vhc_active=1,1,0)
                        ) as count_car_active,
                        SUM(
                            IF(vehicles.vhc_active=2,1,0)
                        ) as count_car_blocked,
                        SUM(
                            IF(vehicles.vhc_approved=1,1,0)
                        ) as count_car_approved,
                        SUM(
                            IF(vehicles.vhc_approved=2,1,0)
                        ) as count_car_pending_approved,
                        SUM(
                            IF(vehicles.vhc_approved=3,1,0)
                        ) as count_car_rejected,
                        SUM(
                            IF(vehicles.vhc_is_commercial=1,1,0)
                        ) as count_car_commercial,
                        SUM(
                            IF((vhc_pollution_certificate IS NOT NULL AND vhc_reg_certificate IS NOT NULL AND vhc_permits_certificate IS NOT NULL AND vhc_fitness_certificate IS NOT NULL),1,0)
                        ) as count_car_missing_paper
                        FROM `vehicles` WHERE 1
                    )b,
                    (
                        SELECT COUNT(DISTINCT d2.drv_id) as count_drivers_license
                        FROM `drivers`
						INNER JOIN drivers d2 ON d2.drv_id=drivers.drv_ref_code
						INNER JOIN contact_profile AS cp ON cp.cr_is_driver =  d2.drv_id AND cp.cr_status=1
						INNER JOIN contact ON cp.cr_contact_id = contact.ctt_id AND contact.ctt_active =1
						#INNER JOIN contact ON d2.drv_contact_id = contact.ctt_id
						WHERE contact.ctt_license_no IS NOT NULL AND contact.ctt_license_doc_id IS NOT NULL AND d2.drv_active=1
                    )c,
                    (
                        SELECT COUNT(1) as cout_insurance_proof
                        FROM `vehicles`
                        WHERE (vehicles.vhc_insurance_proof IS NOT NULL
                        AND vehicles.vhc_insurance_exp_date > NOW()
                        AND vehicles.vhc_active=1
                        AND vehicles.vhc_ver_insurance=1)

                    )d,
                    (
                        SELECT COUNT(1) as cout_pollution_control_certificate
                        FROM `vehicles` WHERE (
                            (vehicles.vhc_pollution_certificate IS NOT NULL OR vehicles.vhc_pollution_certificate!='') AND vehicles.vhc_pollution_exp_date > NOW()
                        )
                    )e,
                    (
                        SELECT COUNT(1) as cout_reg_certificate  FROM `vehicles` WHERE (
                            (vehicles.vhc_reg_certificate IS NOT NULL OR vehicles.vhc_reg_certificate!='') AND vehicles.vhc_reg_exp_date > NOW()
                         )
                    )f,
                    (
                        SELECT COUNT(1) as cout_fitness_certificate  FROM `vehicles` WHERE (
                            (vehicles.vhc_fitness_certificate IS NOT NULL OR vehicles.vhc_fitness_certificate!='') AND vehicles.vhc_fitness_cert_end_date > NOW() AND vehicles.vhc_ver_fitness=1
                        )
                    )g,
                    (
                        SELECT COUNT(1) as cout_commercial_certificate FROM `vehicles` WHERE (
                            (vehicles.vhc_ver_license_commercial IS NOT NULL OR vehicles.vhc_ver_license_commercial!='') AND vehicles.vhc_commercial_exp_date > NOW()
                        )
                    )h,
                    (
                        SELECT COUNT(1) as cout_commercial_verified FROM `vehicles` WHERE vehicles.vhc_is_commercial=1
                    )i";
		return DBUtil::queryRow($sql);
	}

	public function getDistributionByBookingType()
	{
		$sql = "SELECT SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(bkg_create_date,'%Y'),'1',0)) as booking_count_ytd,
                FORMAT(SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(bkg_create_date,'%Y'),biv.bkg_total_amount,0)),2) as booking_total_ytd,
                FORMAT(SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(bkg_create_date,'%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)),2) as booking_gamt_ytd,
				SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(bkg_create_date,'%Y'),'1',0)) as booking_count_year1,
                FORMAT(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(bkg_create_date,'%Y'), biv.bkg_total_amount ,'0')),2) as booking_total_year1,
                FORMAT(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(bkg_create_date,'%Y'), (biv.bkg_gozo_amount-biv.bkg_service_tax) ,'0')),2) as booking_gamt_year1,
                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1',0)) as booking_count_month3,
                FORMAT(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_total_amount,0)),2) as booking_total_month3,
                FORMAT(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)),2) as booking_gamt_month3,
                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1',0)) as booking_count_month2,
                FORMAT(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_total_amount,0)),2) as booking_total_month2,
                FORMAT(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)),2) as booking_gamt_month2,
                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1',0)) as booking_count_month1,
                FORMAT(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_total_amount,0)),2) as booking_total_month1,
                FORMAT(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)),2) as booking_gamt_month1,
                SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),'1',0)) as booking_count_mtd,
                FORMAT(SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),biv.bkg_total_amount,0)),2) as booking_total_mtd,
                FORMAT(SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(bkg_create_date,'%m%Y'),(biv.bkg_gozo_amount-biv.bkg_service_tax),0)),2) as booking_gamt_mtd,
                (
                    CASE booking.bkg_booking_type
                    WHEN 1 THEN 'OW'
                    WHEN 2 THEN 'RT'
                    WHEN 3 THEN 'MW'
                    WHEN 4 THEN 'AT'
                    WHEN 5 THEN 'PT'
					WHEN 6 THEN 'FP'
                    WHEN 7 THEN 'SH'
                    WHEN 8 THEN 'CT'
                    WHEN 9 THEN 'DR'
                    WHEN 10 THEN 'DR'
                    WHEN 11 THEN 'DR'
					WHEN 15 THEN 'LT'
                    END
                ) as booking_type
                FROM `booking`
                INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                WHERE booking.bkg_active=1
                AND booking.bkg_status IN (2,3,4,6,7,9)
                AND booking.bkg_booking_type IN (1,2,3,4,5,6,7,8,9,10,11,15)
                GROUP BY booking.bkg_booking_type";
		return DBUtil::queryAll($sql);
	}

	public function getActiveBookingByMY($time = '', $m = 0, $y = 0, $isCancel = 0)
	{
		$sql = "SELECT COUNT(DISTINCT booking.bkg_id) as totalBooking
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                WHERE booking_cab.bcb_active=1
                AND booking.bkg_pickup_date BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01')
                AND DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-01'),INTERVAL 1 YEAR ) ";
		if($isCancel == 0)
		{
			$sql .= " AND booking.bkg_status IN (2,3,5,6,7)";
		}
		else
		{
			$sql .= " AND booking.bkg_status IN (9)";
		}
		if($time != '')
		{
			switch($time)
			{
				case 'mtd':
					$sql .= " AND booking.bkg_create_date BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01')  AND DATE_FORMAT(NOW(),'%Y-%m-31')";
					break;
				case 'week':
					$sql .= " AND booking.bkg_create_date BETWEEN DATE_SUB(NOW(),INTERVAL 7 DAY) AND NOW()";
					break;
				case 'today2':
					$sql .= " AND booking.bkg_create_date BETWEEN DATE_SUB(NOW(),INTERVAL 48 HOUR) AND NOW()";
					break;
				case 'today1':
					$sql .= " AND booking.bkg_create_date BETWEEN DATE_SUB(NOW(),INTERVAL 24 HOUR) AND NOW()";
					break;
				case 'today':
					$sql .= " AND DATE(booking.bkg_create_date) = DATE(NOW())";
					break;
			}
		}
		if($m > 0 && $y > 0)
		{
			$sql .= " AND MONTH(booking.bkg_pickup_date)=$m AND YEAR(booking.bkg_pickup_date)=$y";
		}
		$sql .= " GROUP BY MONTH(booking.bkg_pickup_date),YEAR(booking.bkg_pickup_date)";

		if($m == 0 && $y == 0)
		{
			return DBUtil::queryAll($sql);
		}
		else
		{
			return DBUtil::command($sql)->queryScalar();
		}
	}

	public function getRegionalBookingDist()
	{
		$sql = "SELECT region, SUM(lifetime) as lifetime,
                SUM(today) as today,
                SUM(today1) as today1,
                SUM(today2) as today2,
                SUM(today3) as today3,
                SUM(mtd) as mtd,
                SUM(month1) as month1,
                SUM(month2) as month2,
                SUM(ytd) as ytd,
                SUM(year1) as year1
                FROM (
                    SELECT
                    (CASE states.stt_zone WHEN 1 THEN 'North'
                        WHEN 2 THEN 'West'
                        WHEN 3 THEN 'Central'
                        WHEN 4 THEN 'South'
                        WHEN 5 THEN 'East'
                        WHEN 6 THEN 'North East'
                        WHEN 7 THEN 'South'
                    END) as region,
                    COUNT(1) as lifetime ,
                    SUM(
                        IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)
                    ) as  today,
                    SUM(
                        IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)
                    ) as  today1,
                    SUM(
                        IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)
                    ) as  today2,
                    SUM(
                        IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)
                    ) as  today3,
                     SUM(
                         IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)
                     ) as mtd,
                     SUM(
                         IF(DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)
                     ) as month1,
                     SUM(
                         IF(DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)
                     ) as month2,
                    SUM(
                         IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)
                    ) as ytd,
                    SUM(
                         IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)
                    ) as year1
                    FROM `booking`
                    JOIN `cities` ON cities.cty_id=booking.bkg_from_city_id
                    JOIN `states` ON states.stt_id=cities.cty_state_id
                    WHERE 1
                    AND booking.bkg_status IN (2,3,4,5,6,7,9)
                    AND booking.bkg_create_date > '2015-10-15 00:00:00'
                    AND booking.bkg_active=1
                    GROUP BY states.stt_zone
                    ORDER BY region
                ) a
                GROUP BY region";

		return DBUtil::queryAll($sql);
	}

	public function getVendorAssignmentReport()
	{
		$sqlManualAssigned		 = "SELECT COUNT(1) as manual_assigned_bookings,
					SUM(
						IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking_trail.bkg_assigned_at,'%d%m%Y'),1,0)
					) as manual_assigned_bookings_now,
					SUM(
						IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking_trail.bkg_assigned_at,'%d%m%Y'),1,0)
					) as manual_assigned_bookings_now1,
					SUM(
						IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking_trail.bkg_assigned_at,'%d%m%Y'),1,0)
					) as manual_assigned_bookings_now2,
					SUM(
						IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking_trail.bkg_assigned_at,'%m%Y'),1,0)
					) as manual_assigned_bookings_mtd,
					SUM(
						IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking_trail.bkg_assigned_at,'%m%Y'),1,0)
					) as manual_assigned_bookings_month1,
					SUM(
						IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking_trail.bkg_assigned_at,'%Y'),1,0)
					) as manual_assigned_bookings_ytd
					FROM booking
					INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
					WHERE booking.bkg_status IN (3,6,5,7,9)
					AND booking.bkg_active = 1
					AND booking_trail.bkg_assigned_at IS NOT NULL
					AND booking_trail.bkg_assign_mode = 0   AND booking.bkg_create_date >= '2015-11-01'";
		$manualAssignedResult	 = DBUtil::queryRow($sqlManualAssigned, DBUtil::SDB());

		$sqlSystemAssignend		 = " SELECT COUNT(1) as system_assigned_bookings,
					SUM(
						IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking_trail.bkg_assigned_at,'%d%m%Y'),1,0)
					) as system_assigned_bookings_now,
					SUM(
						IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking_trail.bkg_assigned_at,'%d%m%Y'),1,0)
					) as system_assigned_bookings_now1,
					SUM(
						IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking_trail.bkg_assigned_at,'%d%m%Y'),1,0)
					) as system_assigned_bookings_now2,
					SUM(
						IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking_trail.bkg_assigned_at,'%m%Y'),1,0)
					) as system_assigned_bookings_mtd,
					SUM(
						IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking_trail.bkg_assigned_at,'%m%Y'),1,0)
					) as system_assigned_bookings_month1,
					SUM(
						IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking_trail.bkg_assigned_at,'%Y'),1,0)
					) as system_assigned_bookings_ytd
					FROM booking
					INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
					WHERE booking.bkg_status IN (3,6,5,7,9)
					AND booking.bkg_active = 1
					AND booking_trail.bkg_assigned_at IS NOT NULL
					AND booking_trail.bkg_assign_mode = 1  AND booking.bkg_create_date >= '2015-11-01'";
		$systemAssignedResult	 = DBUtil::queryRow($sqlSystemAssignend, DBUtil::SDB());
		$totalAssigned			 = array_merge($systemAssignedResult, $manualAssignedResult);
		return $totalAssigned;
	}

	public static function checkBookingConfirmedSms($bkgId)
	{
		$sql = "SELECT COUNT(1) AS chkConfirmSms
				FROM `booking`
				INNER JOIN `sms_log` ON sms_log.slg_ref_id = booking.bkg_id AND sms_log.slg_type = 1
				WHERE booking.bkg_id = '$bkgId'
				AND booking.bkg_active = 1
				AND sms_log.message LIKE '%Cab request received%'";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar();
	}

	/**
	 *
	 * @param integer $bkgId
	 * @return array
	 */
	public static function getVendorCabDriverDetails($bkgId)
	{
		if($bkgId == null || $bkgId == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params	 = array("bkgId" => $bkgId);
		$sql	 = "SELECT
			booking.bkg_id,
			booking.bkg_booking_id,
			v1.vnd_id,
			v1.vnd_code,
			drv_id,
			drv_code,
			vehicles.vhc_id,
			vehicles.vhc_code
			FROM `booking`
			INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 AND booking.bkg_active = 1
			INNER JOIN `vendors` ON vendors.vnd_id = booking_cab.bcb_vendor_id AND vendors.vnd_active=1
			INNER JOIN vendors  v1 ON v1.vnd_id = vendors.vnd_ref_code
			LEFT JOIN `drivers` ON drivers.drv_id = booking_cab.bcb_driver_id AND drivers.drv_active=1
			LEFT JOIN `vehicles` ON vehicles.vhc_id = booking_cab.bcb_cab_id AND vehicles.vhc_active=1
			WHERE booking.bkg_id =:bkgId";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	public function getMissingDrivers()
	{
		$returnSet = Yii::app()->cache->get('getMissingDrivers');
		if($returnSet === false)
		{
			$sql		 = "SELECT COUNT(DISTINCT booking.bkg_id) as count
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_status=2
                WHERE 1=1
                AND booking_cab.bcb_driver_id IS NULL
                AND booking.bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(),INTERVAL 36 HOUR) LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getMissingDrivers', $returnSet, 600);
		}
		return $returnSet;
	}

	public function getUnassignedVendors()
	{
		$returnSet = Yii::app()->cache->get('getUnassignedVendors');
		if($returnSet === false)
		{
			$sql		 = "SELECT COUNT(DISTINCT booking.bkg_id) as count
                FROM `booking`
                WHERE booking.bkg_active=1
                AND booking.bkg_status=2
                AND booking.bkg_pickup_date BETWEEN NOW() AND  DATE_ADD(NOW(),INTERVAL 48 HOUR) LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getUnassignedVendors', $returnSet, 600);
		}
		return $returnSet;
	}

	public function getUnverifiedLeeds()
	{
		$returnSet = Yii::app()->cache->get('getUnverifiedLeeds');
		if($returnSet === false)
		{
			$sql		 = "SELECT COUNT(DISTINCT booking.bkg_id) FROM `booking` WHERE booking.bkg_status=1 AND booking.bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(),INTERVAL 90 DAY) LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getUnverifiedLeeds', $returnSet, 600);
		}
		return $returnSet;
	}

	public static function getAccountsAttention()
	{
		$returnSet = Yii::app()->cache->get('getAccountsAttention');
		if($returnSet === false)
		{
			$sql		 = "SELECT
				IFNULL(COUNT(DISTINCT booking.bkg_id),0) AS cnt,
				IFNULL(
					SUM(
						IF(
							booking.bkg_agent_id = 450,
							1,
							0
						)
					),
					0
				) AS countMMT,
				IFNULL(
					SUM(
						IF((booking.bkg_agent_id > 0 AND booking.bkg_agent_id != 450), 1, 0)
					),
					0
				) AS countB2B,
				IFNULL(
					SUM(
						IF(
							booking.bkg_agent_id IS NULL,
							1,
							0
						)
					),
					0
				) AS countB2C
				FROM `booking`
				INNER JOIN `booking_pref` ON booking.bkg_id = booking_pref.bpr_bkg_id
				WHERE bkg_pickup_date BETWEEN (DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND (DATE_ADD(NOW(), INTERVAL 11 MONTH))
				AND booking.bkg_active = 1
                AND booking_pref.bkg_account_flag=1
				AND booking.bkg_status IN(1, 2, 3, 5, 6, 7, 9, 13, 15) LIMIT 0,1";
			$returnSet	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getAccountsAttention', $returnSet, 600);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return array
	 */
	public static function getActiveEscalations()
	{
		$returnSet = Yii::app()->cache->get('getActiveEscalations');
		if($returnSet === false)
		{
			$sql		 = "SELECT
				COUNT(DISTINCT booking.bkg_id) AS cnt,
				SUM(
					IF(booking_trail.btr_escalation_level = 3, 1, 0)
				) AS countBlue,
				SUM(
					IF(booking_trail.btr_escalation_level = 4, 1, 0)
				) AS countYellow,
                SUM(
					IF(booking_trail.btr_escalation_level = 5, 1, 0)
				) AS countOrange,
                SUM(
					IF(booking_trail.btr_escalation_level = 6, 1, 0)
				) AS countRed
                        FROM `booking_cab`
				INNER JOIN `booking` ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking_cab.bcb_active = 1 AND booking.bkg_active = 1
				INNER JOIN `booking_trail` ON booking.bkg_id = booking_trail.btr_bkg_id AND booking_trail.bkg_escalation_status = 1
				WHERE  booking.bkg_status IN(2, 3, 5, 6, 7, 9) AND booking_trail.btr_escalation_assigned_team IS NOT NULL AND booking_trail.btr_escalation_assigned_team<> ''
				LIMIT 0,1";
			$row		 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			$returnSet	 = ['activeEscalationCnt'	 => $row['cnt'],
				'activeEscalationRed'	 => $row['countRed'],
				'activeEscalationOrange' => $row['countOrange'],
				'activeEscalationYellow' => $row['countYellow'],
				'activeEscalationBlue'	 => $row['countBlue']];
			Yii::app()->cache->set('getActiveEscalations', $returnSet, 600);
		}
		return $returnSet;
	}

	public function getCountUndocumentCarsNonCommercial()
	{
		$returnSet = Yii::app()->cache->get('getCountUndocumentCarsNonCommercial');
		if($returnSet === false)
		{
			$sql = "SELECT COUNT(DISTINCT booking.bkg_id) as cout
                    FROM `booking_cab`
                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                    WHERE booking_cab.bcb_cab_id IN (
                        SELECT vhc_id FROM (
                            SELECT vehicles.vhc_id, round((
                                sum(IFNULL(vehicles.vhc_insurance_proof,0)) +
                                sum(IFNULL(vehicles.vhc_ver_fitness,0)) +
                                sum(IFNULL(vehicles.vhc_ver_front_license,0)) +
                                sum(IFNULL(vehicles.vhc_ver_rear_license,0)) +
                                sum(IFNULL(vehicles.vhc_ver_license_commercial,0)) +
                                sum(IFNULL(vehicles.vhc_fitness_certificate,0)) +
                                sum(IFNULL(vehicles.vhc_pollution_certificate,0)) +
                                sum(IFNULL(vehicles.vhc_permits_certificate,0))
                            )) as totalDocs
                            FROM `vehicles` WHERE vehicles.vhc_is_commercial=0
                            GROUP BY vehicles.vhc_id
                            HAVING  totalDocs < 2
                         )vhc
                    )
                    AND booking.bkg_pickup_date BETWEEN NOW() AND  DATE_ADD(NOW(),INTERVAL 48 HOUR
                ) LIMIT 0,1";

			$returnSet = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getCountUndocumentCarsNonCommercial', $returnSet, 600);
		}
		return $returnSet;
	}

	public function getCountUndocumentCarsCommercial()
	{
		$returnSet = Yii::app()->cache->get('getCountUndocumentCarsCommercial');
		if($returnSet === false)
		{
			$sql		 = "SELECT COUNT(DISTINCT booking.bkg_id) as cout
                    FROM `booking_cab`
                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                    WHERE booking_cab.bcb_cab_id IN (
                        SELECT vhc_id FROM (
                            SELECT vehicles.vhc_id, round((
                                sum(IFNULL(vehicles.vhc_insurance_proof,0)) +
                                sum(IFNULL(vehicles.vhc_ver_fitness,0)) +
                                sum(IFNULL(vehicles.vhc_ver_front_license,0)) +
                                sum(IFNULL(vehicles.vhc_ver_rear_license,0)) +
                                sum(IFNULL(vehicles.vhc_ver_license_commercial,0)) +
                                sum(IFNULL(vehicles.vhc_fitness_certificate,0)) +
                                sum(IFNULL(vehicles.vhc_pollution_certificate,0)) +
                                sum(IFNULL(vehicles.vhc_permits_certificate,0))
                            )) as totalDocs
                            FROM `vehicles` WHERE vehicles.vhc_approved<>1
                            AND vehicles.vhc_is_commercial=1
                            GROUP BY vehicles.vhc_id
                            HAVING  totalDocs < 2
                         )vhc
                    ) AND booking.bkg_pickup_date BETWEEN NOW() AND  DATE_ADD(NOW(),INTERVAL 48 HOUR
                ) LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getCountUndocumentCarsCommercial', $returnSet, 600);
		}
		return $returnSet;
	}

	public function fetchBkgIDByProfitability()
	{
		$sql = "SELECT DISTINCT booking.bkg_id
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                WHERE booking.bkg_status IN (2,3,4,5,6,7,9)
                AND booking_cab.bcb_active=1
                AND booking.bkg_non_profit_flag IS NULL
                GROUP BY booking.bkg_id
                ORDER BY booking.bkg_id DESC";
		return DBUtil::queryAll($sql);
	}

//	public function fetchBkgIdByBookCashback()
//	{
//		$sql = "SELECT booking.bkg_id
//                FROM `booking`
//                INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
//                WHERE booking.bkg_active=1
//                AND  booking.bkg_status IN (2,3,5)
//                AND booking.bkg_advance_amount=0
//                AND (booking.bkg_create_date < DATE_SUB(NOW(),INTERVAL 24 HOUR)) AND (bkg_pickup_date > DATE_ADD(NOW(),INTERVAL 12 HOUR))";
//		return DBUtil::queryAll($sql);
//	}

	/**
	 *
	 * @return array
	 */
	public static function getCountReconfirmPending36hrs()
	{
		$returnSet = Yii::app()->cache->get('getCountReconfirmPending36hrs');
		if($returnSet === false)
		{
			$sql		 = "SELECT
				IFNULL(COUNT(DISTINCT booking.bkg_id),0) AS cnt,
				IFNULL(
					SUM(
						IF(
							booking.bkg_agent_id = 450,
							1,
							0
						)
					),
					0
				) AS countMMT,
				IFNULL(
					SUM(
						IF((booking.bkg_agent_id > 0 AND booking.bkg_agent_id != 450), 1, 0)
					),
					0
				) AS countB2B,
				IFNULL(
					SUM(
						IF(
							booking.bkg_agent_id IS NULL,
							1,
							0
						)
					),
					0
				) AS countB2C
                    FROM `booking_cab`
				INNER JOIN `booking` ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking.bkg_active = 1 AND booking.bkg_status IN(2, 3, 5)
					INNER JOIN `booking_invoice` ON booking.bkg_id=booking_invoice.biv_bkg_id
                    WHERE booking_cab.bcb_active=1
                    AND (booking.bkg_reconfirm_flag=0 OR booking.bkg_reconfirm_flag IS NULL)
                AND booking.bkg_pickup_date BETWEEN NOW() AND  DATE_ADD(NOW(),INTERVAL 36 HOUR)
				AND booking_invoice.bkg_advance_amount <= 0 LIMIT 0,1";
			$returnSet	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getCountReconfirmPending36hrs', $returnSet, 600);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return array
	 */
	public static function getCountNonProfitable()
	{
		$returnSet = Yii::app()->cache->get('getCountNonProfitable');
		if($returnSet === false)
		{
			$sql		 = "SELECT  IFNULL(COUNT(DISTINCT booking.bkg_id), 0) AS cnt,
						IFNULL(SUM(IF(booking.bkg_agent_id IN (450,18190), 1, 0)), 0) AS countMMT,
						IFNULL(SUM(IF((booking.bkg_agent_id > 0 AND booking.bkg_agent_id NOT IN (450,18190)), 1, 0)), 0)
								AS countB2B,
						IFNULL(SUM(IF(booking.bkg_agent_id IS NULL, 1, 0)), 0) AS countB2C
				FROM   `booking`
				INNER JOIN `booking_trail` ON booking.bkg_id = booking_trail.btr_bkg_id
						AND booking_trail.bkg_non_profit_flag = 1
				WHERE	(booking.bkg_pickup_date > DATE_SUB(NOW(), INTERVAL 2 WEEK))
						AND bkg_status IN (15, 2, 3, 5, 6, 7)
				LIMIT  0, 1";
			$returnSet	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getCountNonProfitable', $returnSet, 600);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return Array
	 */
	public static function getCountCompletionOverdue()
	{
		$returnSet = Yii::app()->cache->get('getCountCompletionOverdue');
		if($returnSet === false)
		{

			$sql		 = "SELECT
				COUNT(DISTINCT booking.bkg_id) AS CNT,
				IFNULL(
					SUM(
						IF(
							booking.bkg_agent_id = 18190,
							1,
							0
						)
					),
					0
				) AS countMMT,
				IFNULL(
					SUM(
						IF((booking.bkg_agent_id > 0 AND booking.bkg_agent_id != 18190), 1, 0)
					),
					0
				) AS countB2B,
				IFNULL(
					SUM(
						IF(
							booking.bkg_agent_id IS NULL,
							1,
							0
						)
					),
					0
				) AS countB2C
			FROM `booking`
				INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
                INNER JOIN `booking_track` ON booking.bkg_id = booking_track.btk_bkg_id  AND booking_track.bkg_is_no_show = 0

				INNER JOIN `booking_pref` ON booking.bkg_id = booking_pref.bpr_bkg_id AND booking_pref.bkg_account_flag = 0  AND booking_pref.bkg_duty_slip_required = 0
				WHERE booking.bkg_status = 5 AND booking.bkg_active=1 AND DATE_ADD(booking.bkg_pickup_date, INTERVAL booking.bkg_trip_duration + 360  MINUTE) < NOW() LIMIT 0,1";
			$returnSet	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getCountCompletionOverdue', $returnSet, 600);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return Array
	 */
	public static function getCountPickupOverdue()
	{
		$returnSet = Yii::app()->cache->get('getCountPickupOverdue');
		if($returnSet === false)
		{
			$sql		 = "SELECT
				COUNT(DISTINCT booking.bkg_id) AS CNT,
				IFNULL(
					SUM(
						IF(
							booking.bkg_agent_id = 450,
							1,
							0
						)
					),
					0
				) AS countMMT,
				IFNULL(
					SUM(
						IF((booking.bkg_agent_id > 0 AND booking.bkg_agent_id != 450), 1, 0)
					),
					0
				) AS countB2B,
				IFNULL(
					SUM(
						IF(
							booking.bkg_agent_id IS NULL,
							1,
							0
						)
					),
					0
				) AS countB2C
			FROM `booking`
				INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1
			WHERE booking.bkg_status IN (2, 3)
			AND booking.bkg_pickup_date < NOW() LIMIT 0,1";
			$returnSet	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getCountPickupOverdue', $returnSet, 600);
		}
		return $returnSet;
	}

	public function countVendorsForApproval()
	{
		$sql = "SELECT COUNT(1) as cout
				FROM `vendors` vnd
				INNER JOIN vendors vnd1 ON vnd1.vnd_id = vnd.vnd_ref_code
				INNER JOIN contact_profile cp on cp.cr_is_vendor = vnd1.vnd_id and cp.cr_status =1
				INNER JOIN contact ON contact.ctt_id=cp.cr_contact_id and contact.ctt_active =1 and contact.ctt_id = contact.ctt_ref_code
				LEFT JOIN vendor_agreement ON vendor_agreement.vag_vnd_id = vnd1.vnd_id AND vendor_agreement.vag_digital_flag = 1
                LEFT JOIN document docPAN ON docPAN.doc_id = contact.ctt_pan_doc_id AND docPAN.doc_type = 4 AND docPAN.doc_status = 1
                LEFT JOIN document docAdhar ON docAdhar.doc_id = contact.ctt_aadhar_doc_id AND docAdhar.doc_type = 3 AND docAdhar.doc_status = 1
                LEFT JOIN document docVoter ON docVoter.doc_id = contact.ctt_voter_doc_id AND docVoter.doc_type = 2 AND docVoter.doc_status = 1
                LEFT JOIN document docLicense ON docLicense.doc_id = contact.ctt_license_doc_id AND docLicense.doc_type = 5 AND docLicense.doc_status = 1
                WHERE
				vnd1.vnd_active IN (3)
                     AND (
                       docPAN.doc_id IS NULL OR (docAdhar.doc_id IS NULL AND docVoter.doc_id IS NULL AND docLicense.doc_id IS NULL)
                       OR vendor_agreement.vag_id IS NULL
                     ) LIMIT 0,1";
		return DBUtil::command($sql)->queryScalar();
	}

	/*
	 * @deprecated since 2021-12-31
	 */

	public function countDriversForApproval()
	{
		$sql = " SELECT
				 COUNT(DISTINCT d2.drv_id) as cout
				 FROM drivers d2
				 JOIN contact ON contact.ctt_id = d2.drv_contact_id AND (contact.ctt_license_doc_id IS NOT NULL AND contact.ctt_license_doc_id <> '')
				 JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id AND contact_phone.phn_active = 1 AND contact_phone.phn_is_primary = 1
				 WHERE 1 AND d2.drv_active = 1  AND (d2.drv_name IS NOT NULL AND d2.drv_name <> '')  AND d2.drv_approved IN (2) AND d2.drv_id = d2.drv_ref_code LIMIT 0,1";
		return DBUtil::command($sql)->queryScalar();
	}

	public function countCarForApproval()
	{
		$sql = "SELECT COUNT(DISTINCT vehicles.vhc_id) as cout
                FROM `vehicles`
                JOIN `vehicle_types` ON vehicle_types.vht_id=vehicles.vhc_type_id AND vehicle_types.vht_active=1
                WHERE 1
                AND (vehicle_types.vht_model!='' OR vehicle_types.vht_model <> NULL)
                AND (vehicles.vhc_year!='' OR vehicles.vhc_year <> NULL)
                AND (vehicles.vhc_insurance_proof <> NULL OR vehicles.vhc_insurance_proof <>'')
                AND (vehicles.vhc_pollution_certificate <> NULL OR vehicles.vhc_pollution_certificate <>'')
                AND (vehicles.vhc_reg_certificate <> NULL OR vehicles.vhc_reg_certificate <>'')
                AND (vehicles.vhc_fitness_certificate <> NULL OR vehicles.vhc_fitness_certificate <>'')
                AND (vehicles.vhc_permits_certificate <> NULL OR vehicles.vhc_permits_certificate <>'')
                AND vehicles.vhc_approved IN (2) LIMIT 0,1";
		return DBUtil::command($sql)->queryScalar();
	}

	public function countHigherVendorAmount()
	{
		$sql = "SELECT COUNT(DISTINCT booking.bkg_id) as cout
                FROM `booking` WHERE booking.bkg_bcb_id IN
                (
                   SELECT bcb_id FROM
                   (
                        SELECT booking_cab.bcb_id,booking_cab.bcb_vendor_amount as bcb_vendor_amt,GROUP_CONCAT(booking.bkg_id) , SUM(booking_invoice.bkg_vendor_amount) as bkg_vendor_amount
                        FROM `booking_cab`
                        INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1 AND booking.bkg_status IN (2,3,5,6,7)
						INNER JOIN `booking_invoice` ON booking.bkg_id=booking_invoice.biv_bkg_id
                        WHERE booking_cab.bcb_active=1 AND booking.bkg_pickup_date > CURDATE()
                        GROUP BY booking_cab.bcb_id
                        HAVING (bcb_vendor_amt>bkg_vendor_amount)
                    )a
                ) LIMIT 0,1";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getTripInfoByBkgId($bkg_id)
	{
		$sql = "SELECT GROUP_CONCAT(booking.bkg_id) as bkg_ids,
                ROUND(SUM(booking.bkg_total_amount),2) as booking_amount,
                ROUND(SUM(booking.bkg_service_tax),2) as service_tax,
                ROUND(booking_cab.bcb_vendor_amount,2) as vendor_amount,
                SUM(booking.bkg_vendor_amount)  as bkg_vendor_amount
                FROM `booking`
                INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1
                INNER JOIN
                (
                    SELECT booking_log.blg_booking_id,booking_log.blg_event_id
                    FROM `booking_log`
                    WHERE booking_log.blg_event_id IN (91) AND booking_log.blg_active=1
                    GROUP BY booking_log.blg_booking_id
                )blg ON blg.blg_booking_id=booking.bkg_id
                WHERE booking.bkg_bcb_id IN ( SELECT booking.bkg_bcb_id FROM `booking` WHERE  booking.bkg_id=$bkg_id AND booking.bkg_active=1)
                AND booking.bkg_status IN (2,3,5,6,7) AND booking_cab.bcb_active=1 AND booking_cab.bcb_trip_type=1";
		return DBUtil::queryRow($sql);
	}

	public function preAutoCancelBooking($Interval)
	{
		$status	 = [2, 3, 5];
		$status	 = implode(',', $status);
		$sql	 = "SELECT booking.bkg_id, booking.bkg_booking_id, booking.bkg_pickup_date,
					bui.bkg_contact_no as bkg_contact_no,
					max(email_log.elg_created) as elg_last_sent
					FROM `booking`
					JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id
					JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
					JOIN booking_pref as bpr ON bpr.bpr_bkg_id=bkg_id
					LEFT JOIN `email_log` ON email_log.elg_ref_id=booking.bkg_id AND email_log.elg_ref_type=1 AND email_log.elg_type IN (13)
					WHERE bkg_status IN ($status)
					AND bkg_id NOT IN
					(
						SELECT booking_log.blg_booking_id FROM booking_log WHERE booking_log.blg_event_id IN (74,75)
					)
					AND booking.bkg_pickup_date BETWEEN DATE_ADD(NOW(), INTERVAL 4 HOUR) AND DATE_ADD(NOW(), INTERVAL 36 HOUR)
					AND booking.bkg_create_date < DATE_SUB(NOW(), INTERVAL $Interval HOUR)
					AND biv.bkg_advance_amount<=0 AND bkg_reconfirm_flag=0
					AND bpr.bkg_tentative_booking=0  AND (booking.bkg_agent_id = 0 OR booking.bkg_agent_id IS NULL)
					GROUP BY bkg_id
					HAVING elg_last_sent IS NULL OR elg_last_sent < DATE_SUB(NOW(), INTERVAL 12 HOUR)";

		return DBUtil::queryAll($sql);
	}

	public function postAutoCancelBooking($Interval)
	{
		$status		 = [2, 3, 5];
		$status		 = implode(',', $status);
		$createInv	 = 24;
		$pickupInv	 = 18;
		$sql		 = "SELECT  booking.bkg_id, booking.bkg_booking_id, booking.bkg_create_date,
						bui.bkg_contact_no as bkg_contact_no,
                    COUNT(DISTINCT elg_last_date) as elg_count, MAX(DISTINCT elg_last_date) as elg_last_date,
                    MIN(DISTINCT elg_last_date) as elg_min_date, COUNT(DISTINCT slg_last_date) as slg_count,
                    MAX(DISTINCT slg_last_date) as slg_last_date, MIN(DISTINCT slg_last_date) as slg_min_date,
                    DATE_SUB(booking.bkg_pickup_date, INTERVAL 18 HOUR), NOW(), booking.bkg_pickup_date,
                    biv.bkg_advance_amount
                FROM booking
                JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id
                JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                LEFT JOIN (
                    SELECT email_log.elg_ref_id, email_log.elg_created as elg_last_date
                    FROM `email_log`
                    WHERE email_log.elg_ref_type=1 AND email_log.elg_type=13

                )elg ON elg.elg_ref_id=booking.bkg_id
                LEFT JOIN (
                     SELECT sms_log.slg_ref_id, sms_log.date_sent as slg_last_date
                     FROM `sms_log`
                     WHERE sms_log.slg_ref_type=1  AND sms_log.slg_type=19
                )slg ON slg.slg_ref_id=booking.bkg_id
                WHERE bkg_status IN (2, 3, 5) AND bkg_pickup_date>NOW()  AND bkg_id NOT IN (
                        SELECT booking_log.blg_booking_id FROM booking_log WHERE booking_log.blg_event_id IN (74,75)
                    )
					AND bkg_reconfirm_flag=0
                AND ((TIMESTAMPDIFF(HOUR,bkg_create_date, NOW()) > 24
							AND TIMESTAMPDIFF(HOUR, NOW(), bkg_pickup_date)<18)
						OR
					 (TIMESTAMPDIFF(HOUR,bkg_create_date, NOW()) > 12
							AND TIMESTAMPDIFF(HOUR, NOW(), bkg_pickup_date)<14)
						OR
					 (TIMESTAMPDIFF(HOUR,bkg_create_date, NOW()) > 4
							AND TIMESTAMPDIFF(HOUR, NOW(), bkg_pickup_date)<9)
						OR
					 (TIMESTAMPDIFF(HOUR,bkg_create_date, NOW()) > 1
							AND TIMESTAMPDIFF(HOUR, NOW(), bkg_pickup_date)<4)
				)
                AND (biv.bkg_advance_amount<=0 OR biv.bkg_corporate_credit<=0)
                GROUP BY booking.bkg_id";
		return DBUtil::queryAll($sql);
	}

	public function reconfirmAlertInHrs($Interval)
	{
		$status	 = [2, 3, 5];
		$status	 = implode(',', $status);
		$sql	 = "SELECT booking.bkg_id, booking.bkg_booking_id, booking.bkg_pickup_date,
					bui.bkg_contact_no as bkg_contact_no,
                    CONCAT(bui.bkg_user_fname,' ',bui.bkg_user_lname) as user_name,
                    MAX(email_log.elg_created) as email_last_sent,
                    MAX(sms_log.date_sent) as sms_last_sent
                    FROM `booking`
                    JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id
                    JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                    LEFT JOIN `email_log` ON booking.bkg_id=elg_ref_id AND elg_ref_type=1 AND elg_type IN (13,20)
                    LEFT JOIN `sms_log` ON booking.bkg_id=slg_ref_id AND sms_log.slg_ref_type=1 AND sms_log.slg_type IN (16,19)
		            WHERE bkg_status IN (2)
                     AND (booking.bkg_agent_id =0 OR booking.bkg_agent_id IS NULL)

                    AND bkg_id NOT IN (
                    	SELECT booking_log.blg_booking_id FROM booking_log WHERE  booking_log.blg_event_id IN (74,75)
                    )
                    AND booking.bkg_create_date < DATE_SUB(NOW(), INTERVAL 24 HOUR)
                    AND NOW() BETWEEN DATE_SUB(booking.bkg_pickup_date, INTERVAL $Interval MINUTE)
                    AND DATE_SUB(booking.bkg_pickup_date, INTERVAL 12 HOUR) AND biv.bkg_advance_amount<=0
                    GROUP BY bkg_id
                        HAVING ((
                            email_last_sent IS NULL
                            OR
                            email_last_sent < DATE_SUB(NOW(), INTERVAL 8 HOUR))
                            AND (
                            sms_last_sent IS NULL
                            OR
                            sms_last_sent < DATE_SUB(NOW(), INTERVAL 8 HOUR)
                            )
                        )";
		return DBUtil::queryAll($sql);
	}

	public function updateOnAutoCancel($bkgId)
	{
		if($bkgId <> '')
		{
			$bkgId = Booking::model()->canBooking($bkgId, 'Customer did not reconfirm', 18);
			if($bkgId)
			{
				$bookingModel	 = Booking::model()->findByPk($bkgId);
				$oldModel		 = $bookingModel;
				$eventId		 = BookingLog::AUTOCANCEL_BOOKING;

				$desc							 = "Booking auto-cancelled by system. Reconfirm not received.";
				$params['blg_booking_status']	 = $bookingModel->bkg_status;
				BookingLog::model()->createLog($bookingModel->bkg_id, $desc, UserInfo::model(), $eventId, $oldModel, $params);

				/* @var $emailObj emailWrapper */
				$emailObj = new emailWrapper();
				$emailObj->bookingAutoCancellationMail($bookingModel->bkg_id);
			}
			echo "\nAUTO-Cancelled ->" . $bkgId . " -> " . $desc;
			return true;
		}
		else
		{
			return false;
		}
	}

	public function unverifiedAutoCanel($bkgId)
	{
		Logger::writeToConsole("UnverifiedAutoCanel STARTED");
		$success = false;
		$userInfo			 = UserInfo::getInstance();
		$userInfo->userType	 = UserInfo::TYPE_SYSTEM;
		if($bkgId <> '')
		{
			$bkgId = Booking::model()->canBooking($bkgId, 'Time Out', 20, $userInfo);
			if($bkgId)
			{
				$model = Booking::model()->findByPk($bkgId);

				if($model->bkg_status == 9)
				{
					Logger::writeToConsole("Check Cancel Status");
				}

				$oldModel						 = $model;
				$desc							 = "Booking auto-cancelled by system. (Reason: " . $model->bkg_cancel_delete_reason . ")";
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($model->bkg_id, $desc, UserInfo::model(), BookingLog::AUTOCANCEL_UV_BOOKING, $oldModel, $params);
				$success						 = true;
			}
		}
		return $success;
	}

	public function setReschedule($bkgId, $rescheduleDate, $rescheduleTime, $rescheduleAddr)
	{

		/* @var $model Booking */
		$model						 = Booking::model()->findByPk($bkgId);
		$oldModel					 = clone $model;
		$model->bkg_reconfirm_flag	 = 2;
//$model->save();
		if($model->save())
		{
			$emailWrapper					 = new emailWrapper();
			$emailWrapper->planDelayedEmail($model->bkg_id, $rescheduleDate, $rescheduleTime, $rescheduleAddr);
			$desc							 = "Booking rescheduled as per  traveller request.";
			$params['blg_booking_status']	 = $model->bkg_status;
			BookingLog::model()->createLog($model->bkg_id, $desc, UserInfo::getInstance(), BookingLog::RESCHEDULE_BOOKING, $oldModel, $params);
			return true;
		}
		else
		{
			return false;
		}
	}

	public function bookGozoAgain($isStatus = 0)
	{
		$status	 = [5, 6, 7];
		$status	 = implode($status, ',');

		if($isStatus == 1)
		{
			$where = " AND booking.bkg_status IN ($status)";
		}

		$sql = "SELECT
					CONCAT(bui.bkg_user_fname,' ',bui.bkg_user_lname) as user_name,
					bui.bkg_user_email as user_email,
					bui.bkg_user_id as user_id
                    FROM booking
                    INNER JOIN ratings ON ratings.rtg_booking_id=booking.bkg_id $where
                    INNER JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id
                    LEFT JOIN
                    (
                        SELECT email_log.elg_address as email_id,email_log.elg_ref_id,email_log.elg_ref_type
                        FROM email_log WHERE email_log.elg_type=11 AND email_log.elg_ref_type=2 AND email_log.elg_created>DATE_SUB(NOW(),INTERVAL 2160 HOUR)  GROUP BY email_log.elg_ref_id
                    ) temp ON temp.elg_ref_id=bui.bkg_user_id
					 WHERE 1
                    AND ratings.rtg_customer_overall<=4
                    AND bui.bkg_user_email !=''
                    AND booking.bkg_pickup_date < DATE_SUB(NOW(),INTERVAL 2160 HOUR)
                    AND bui.bkg_user_email NOT IN( SELECT unsubscribe.usb_email FROM `unsubscribe` WHERE unsubscribe.usb_active=1)
                    AND temp.email_id IS NULL
                    GROUP BY bui.bkg_user_id ORDER BY booking.bkg_pickup_date DESC";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

//DurationScore 20 points
//same_cab_type 30 points
//reconfirm_matching_status 10 points
//source_matching_dest 10 points
//dest_matching_source 10 points
//source_matching_adv_amt 10 points
//dest_matching_adv_amt 10 points
//quality_match 10 points
//distance_match 10 points
	public function getMatchedList($bcbTypeMatched = 0)
	{
		$records = array();
		foreach($bcbTypeMatched as $bcbTypeMatch)
		{
			$condition = ' AND bcb_trip_type = ' . $bcbTypeMatch;
			if($bcbTypeMatch == 1)
			{
				$matchedList = " AND bk1.bkg_bcb_id = bkg.bkg_bcb_id"; // Matched
			}
			else
			{
				$matchedList = " AND bk1.bkg_bcb_id <> bkg.bkg_bcb_id";
			}
			$dataprovider = $this->smartMatch($condition, $matchedList);
			for($i = 0; $i < $dataprovider->totalItemCount; $i++)
			{
				$data = $dataprovider->data[$i];
				array_push($records, $data);
			}
		}


		$provAll = new CArrayDataProvider($records, array(
			'sort'		 => array(//optional and sortring
				'attributes' => array(
					'id',
					'title'
				),
			),
			'pagination' => array('pageSize' => 10),
				//optional add a pagination
				)
		);
		return $provAll;
	}

	public function smartMatch($condition, $matchedList)
	{
		$sql = "SELECT *, ROUND((DurationScore + same_cab_type + reconfirm_matching_status +source_matching_dest+dest_matching_source+source_matching_adv_amt+dest_matching_adv_amt+quality_match+distance_match)*100/120,2) as MatchScore FROM (
		SELECT DISTINCT  bk1.bkg_id as up_bkg_id,bk1.bkg_booking_id as up_bkg_booking_id,
            bk1.bkg_tentative_booking as up_tentative_booking,
            bpr.bkg_tentative_booking as down_tentative_booking,
            bk1.bkg_bcb_id as up_bkg_bcb_id,
            bkg.bkg_bcb_id as down_bkg_bcb_id,
            IF(bk1.bkg_bcb_id = bkg.bkg_bcb_id, 1, 0) as bcbTypeMatched,
            bk1.bkg_from_city_id as up_bkg_from_city_id,
            bkg.bkg_from_city_id as down_bkg_from_city_id,
            bk1.bkg_to_city_id as up_bkg_to_city_id,
            bkg.bkg_to_city_id as down_bkg_to_city_id,
            bkg.bkg_id as down_bkg_id,bkg.bkg_booking_id as down_bkg_booking_id,
            bk1.bkg_pickup_date as up_bkg_pickup_date,
            bkg.bkg_pickup_date as down_bkg_pickup_date,
            cityFrom.cty_name as down_bkg_from_city,
            DATE_ADD(DATE_ADD(bk1.bkg_pickup_date, INTERVAL bk1.bkg_trip_duration MINUTE), INTERVAL FLOOR(bk1.bkg_trip_distance/100) HOUR) as MinReturnTime,
            DATE_ADD(DATE(bk1.bkg_pickup_date), INTERVAL CEIL((bk1.bkg_trip_distance)/250) DAY) as MaxReturnTime,
            CASE
            WHEN bkg.bkg_pickup_date>DATE_ADD(DATE_ADD(bk1.bkg_pickup_date, INTERVAL bk1.bkg_trip_duration MINUTE), INTERVAL FLOOR(bk1.bkg_trip_distance/100) HOUR) AND DATE(DATE_ADD(bkg.bkg_pickup_date, INTERVAL bkg.bkg_trip_duration MINUTE)) <= DATE( DATE_ADD(DATE(bk1.bkg_pickup_date), INTERVAL CEIL((bk1.bkg_trip_distance)/250) DAY))
              THEN 20
            WHEN bkg.bkg_pickup_date>DATE_ADD(DATE_ADD(bk1.bkg_pickup_date, INTERVAL bk1.bkg_trip_duration MINUTE), INTERVAL FLOOR(bk1.bkg_trip_distance/100) HOUR) AND DATE(DATE_ADD(bkg.bkg_pickup_date, INTERVAL bkg.bkg_trip_duration MINUTE)) <= DATE( DATE_ADD(bk1.bkg_pickup_date, INTERVAL FLOOR((bk1.bkg_trip_distance + bkg.bkg_trip_distance)/250) DAY))
              THEN 15
            WHEN bkg.bkg_pickup_date>DATE_ADD(DATE_ADD(bk1.bkg_pickup_date, INTERVAL bk1.bkg_trip_duration MINUTE), INTERVAL FLOOR(bk1.bkg_trip_distance/100) HOUR) AND DATE(DATE_ADD(bkg.bkg_pickup_date, INTERVAL bkg.bkg_trip_duration MINUTE)) <= DATE( DATE_ADD(bk1.bkg_pickup_date, INTERVAL CEIL((bk1.bkg_trip_distance + bkg.bkg_trip_distance)/250) DAY))
              THEN 5
            ELSE
              0
            END AS DurationScore,
            bkg.bkg_status as down_bkg_status,bk1.bkg_status as up_bkg_status,
            cityTo.cty_name as down_bkg_to_city, cityTo.cty_lat as down_city_lat, cityTo.cty_long  as down_city_long,vendor_name, vendor_city,
            vendor_total_trip, vendor_rating, bk1.bkg_total_amount as bkg1_total_amount, bk1.bkg_advance_amount as bkg1_advance_amount,
            biv.bkg_total_amount, biv.bkg_advance_amount, vendors.vnd_name as down_booking_vendor_name,
            vndcity.cty_name as down_booking_vendor_city, vrs_total_trips as down_booking_vendor_total_trips, vrs_vnd_overall_rating as down_booking_vendor_rating,
            bk1.bkg_trip_duration as up_booking_duration, bk1.bkg_reconfirm_flag as up_booking_confirm,bkg.bkg_reconfirm_flag as dn_booking_confirm,
            vehicleCat.vct_desc as down_vht_model,up_vht_model,vehicleCat.vct_label as down_vht_make,up_vht_make, vehicleCat.vct_id as down_vht_id,up_vht_id,
            CASE
            WHEN bk1.bkg_reconfirm_flag = 1 AND bkg.bkg_reconfirm_flag = 1 THEN 10
                ELSE 0
            END AS reconfirm_matching_status,
            CASE
            WHEN vehicleCat.vct_label = up_vht_make THEN 30
                ELSE 0
            END AS same_cab_type,
            CASE
            WHEN bk1.bkg_from_city_id = bkg.bkg_to_city_id THEN 10
                ELSE 0
            END AS source_matching_dest,
            CASE
            WHEN bk1.bkg_to_city_id = bkg.bkg_from_city_id THEN 10
                ELSE 0
            END AS dest_matching_source,
            CASE
            WHEN TIMESTAMPDIFF(HOUR, DATE_ADD(bk1.bkg_pickup_date, INTERVAL bk1.bkg_trip_duration MINUTE), bkg.bkg_pickup_date) BETWEEN IF(bk1.bkg_trip_duration <=240, 2,IF(bk1.bkg_trip_duration <=540, 3, 6)) AND IF(bk1.bkg_trip_duration <=240, 6,IF(bk1.bkg_trip_duration <=540, 8, 10)) THEN 20
            WHEN TIMESTAMPDIFF(HOUR, DATE_ADD(bk1.bkg_pickup_date, INTERVAL bk1.bkg_trip_duration MINUTE), bkg.bkg_pickup_date) BETWEEN IF(bk1.bkg_trip_duration <=240, 6,IF(bk1.bkg_trip_duration <=540, 8, 10)) AND IF(bk1.bkg_trip_duration <=240, 12,IF(bk1.bkg_trip_duration <=540, 14, 16)) THEN 10
            WHEN TIMESTAMPDIFF(HOUR, DATE_ADD(bk1.bkg_pickup_date, INTERVAL bk1.bkg_trip_duration MINUTE), bkg.bkg_pickup_date) BETWEEN IF(bk1.bkg_trip_duration <=240, 12,IF(bk1.bkg_trip_duration <=540, 14, 16)) AND 24 THEN 5
                ELSE 0
            END AS time_interval,
            CASE
            WHEN bk1.bkg_advance_amount THEN 10
                ELSE 0
            END AS source_matching_adv_amt,
            CASE
            WHEN biv.bkg_advance_amount THEN 10
                ELSE 0
            END AS dest_matching_adv_amt,
            CASE
            WHEN SQRT( POW(69.1 * (cityFrom.cty_lat - up_city_lat), 2) + POW(69.1 * (up_city_long - cityFrom.cty_long) * COS(cityFrom.cty_lat / 57.3), 2)) BETWEEN  0 AND 5 THEN 10
            WHEN SQRT( POW(69.1 * (cityFrom.cty_lat - up_city_lat), 2) + POW(69.1 * (up_city_long - cityFrom.cty_long) * COS(cityFrom.cty_lat / 57.3), 2)) BETWEEN  6 AND 10 THEN 5
            WHEN SQRT( POW(69.1 * (cityFrom.cty_lat - up_city_lat), 2) + POW(69.1 * (up_city_long - cityFrom.cty_long) * COS(cityFrom.cty_lat / 57.3), 2)) BETWEEN  11 AND 25 THEN 0
                ELSE 0
            END AS quality_match,
            CASE
            WHEN ROUND((bk1.bkg_trip_distance + bkg.bkg_trip_distance)/((DATEDIFF(DATE_ADD(bkg.bkg_pickup_date, INTERVAL bkg.bkg_trip_duration MINUTE), bk1.bkg_pickup_date)*250))*100) BETWEEN  125 AND 150 THEN 2.5
            WHEN ROUND((bk1.bkg_trip_distance + bkg.bkg_trip_distance)/((DATEDIFF(DATE_ADD(bkg.bkg_pickup_date, INTERVAL bkg.bkg_trip_duration MINUTE), bk1.bkg_pickup_date)*250))*100) BETWEEN  151 AND 175 THEN 5
            WHEN ROUND((bk1.bkg_trip_distance + bkg.bkg_trip_distance)/((DATEDIFF(DATE_ADD(bkg.bkg_pickup_date, INTERVAL bkg.bkg_trip_duration MINUTE), bk1.bkg_pickup_date)*250))*100) BETWEEN  176 AND 200 THEN 7.5
            WHEN ROUND((bk1.bkg_trip_distance + bkg.bkg_trip_distance)/((DATEDIFF(DATE_ADD(bkg.bkg_pickup_date, INTERVAL bkg.bkg_trip_duration MINUTE), bk1.bkg_pickup_date)*250))*100) > 200 THEN 10
                ELSE 0
            END AS distance_match,
            up_bkg_from_city,up_bkg_to_city,bkg.bkg_return_id as down_bkg_return_id,bk1.bkg_return_id as up_bkg_return_id,
			SQRT( POW(69.1 * (cityFrom.cty_lat - up_city_lat), 2) + POW(69.1 * (up_city_long - cityFrom.cty_long) * COS(cityFrom.cty_lat / 57.3), 2)) as distance,
			IF(bkg.bkg_vehicle_type_id = bk1.bkg_vehicle_type_id, 1, 0) as vhtMatched

                FROM `booking` bkg
                                    INNER JOIN booking_cab ON bkg.bkg_bcb_id=bcb_id AND bcb_active=1 $condition  AND bkg.bkg_status IN (2,3,5)
                INNER JOIN booking_invoice biv ON bkg.bkg_id=biv.biv_bkg_id
				INNER JOIN booking_pref bpr ON bkg.bkg_id=bpr.bpr_bkg_id
				LEFT JOIN zone_cities zct1From ON bkg.bkg_from_city_id=zct1From.zct_cty_id
                LEFT JOIN zone_cities zct1To ON bkg.bkg_to_city_id=zct1To.zct_cty_id
                LEFT JOIN cities cityFrom ON cityFrom.cty_id=zct1From.zct_cty_id
                LEFT JOIN cities cityTo ON cityTo.cty_id=zct1To.zct_cty_id
				LEFT JOIN svc_class_vhc_cat ON bkg.bkg_vehicle_type_id = svc_class_vhc_cat.scv_id
                LEFT JOIN vehicle_category vehicleCat ON svc_class_vhc_cat.scv_vct_id=vehicleCat.vct_id
                LEFT JOIN vendors ON vnd_id=booking_cab.bcb_vendor_id
				LEFT JOIN contact ON vnd_contact_id = ctt_id
                LEFT JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
                LEFT JOIN cities vndcity ON contact.ctt_city=vndcity.cty_id
                INNER JOIN booking_route brt ON brt.brt_bkg_id = bkg.bkg_id
                INNER JOIN
                (
                    SELECT DISTINCT booking.*,booking_pref.bkg_tentative_booking,
					booking_invoice.*, zct2From.zct_cty_id as zct_from_city, zct2From.zct_zon_id as zct_from_zone,
						zct2To.zct_cty_id as zct_to_city, zct2To.zct_zon_id as zct_to_zone,
            (SELECT booking_route.brt_trip_distance FROM booking_route WHERE brt_active=1 AND booking_route.brt_bkg_id = booking.bkg_id ORDER BY booking_route.brt_pickup_datetime DESC LIMIT 0,1) as lastDistance,
						city2From.cty_name as up_bkg_from_city, city2From.cty_lat as up_from_city_lat, city2From.cty_long  as up_from_city_long,
						city2To.cty_name as up_bkg_to_city, city2To.cty_lat as up_city_lat, city2To.cty_long  as up_city_long,
						vehicleCat2.vct_desc as up_vht_model, vnd.vnd_name as vendor_name,
						vndcity.cty_name as vendor_city, vrs.vrs_total_trips as vendor_total_trip,
						vrs.vrs_vnd_overall_rating as vendor_rating, vehicleCat2.vct_label as up_vht_make, vehicleCat2.vct_id as up_vht_id
                    FROM booking
					          INNER JOIN booking_cab ON booking.bkg_bcb_id=bcb_id $condition  AND  bcb_active=1
					 JOIN booking_pref ON booking.bkg_id=booking_pref.bpr_bkg_id
					 JOIN booking_invoice ON booking.bkg_id=booking_invoice.biv_bkg_id
                    LEFT JOIN zone_cities zct2From ON bkg_from_city_id=zct2From.zct_cty_id AND zct2From.zct_active=1
                    LEFT JOIN zone_cities zct2To ON bkg_to_city_id=zct2To.zct_cty_id AND zct2To.zct_active=1
                    LEFT JOIN cities city2From ON city2From.cty_id=zct2From.zct_cty_id
                    LEFT JOIN cities city2To ON city2To.cty_id=zct2To.zct_cty_id
					LEFT JOIN svc_class_vhc_cat scv ON bkg_vehicle_type_id=scv.scv_id
                    LEFT JOIN vehicle_category vehicleCat2 ON scv.scv_vct_id=vehicleCat2.vct_id
                    LEFT JOIN vendors vnd ON vnd_id=booking_cab.bcb_vendor_id
					LEFT JOIN contact ctt ON vnd.vnd_contact_id = ctt.ctt_id
                    LEFT JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id
					LEFT JOIN cities vndcity ON ctt.ctt_city=vndcity.cty_id
                    WHERE bkg_active=1 AND bkg_booking_type=1 AND bkg_status IN (2,3,5) AND bkg_pickup_date <= DATE_ADD(NOW(), INTERVAL 10 DAY)
                ) bk1 ON
			SQRT( POW(69.1 * (cityFrom.cty_lat - up_city_lat), 2) + POW(69.1 * (up_city_long - cityFrom.cty_long) * COS(cityFrom.cty_lat / 57.3), 2)) < 30
                    AND bk1.bkg_from_city_id <> bkg.bkg_from_city_id AND bk1.bkg_to_city_id <> bkg.bkg_to_city_id
                    AND (SQRT( POW(69.1 * (cityTo.cty_lat - up_from_city_lat), 2) + POW(69.1 * (up_city_long - cityTo.cty_long) * COS(cityTo.cty_lat / 57.3), 2)) < 30)
                    AND bk1.bkg_pickup_date > DATE_ADD(NOW(), INTERVAL 2 HOUR)
                    AND bk1.bkg_advance_amount > 0
		    AND bkg.bkg_vehicle_type_id = bk1.bkg_vehicle_type_id
                    AND bkg.bkg_pickup_date >= DATE_ADD(DATE_ADD(bk1.bkg_pickup_date, INTERVAL bk1.bkg_trip_duration MINUTE), INTERVAL 60 MINUTE)
                    AND DATE(bkg.bkg_pickup_date) <= DATE( DATE_ADD(bk1.bkg_pickup_date, INTERVAL ROUND((bk1.bkg_trip_distance + bkg.bkg_trip_distance)/250)-1 DAY)) AND bk1.bkg_id<>bkg.bkg_id
                    AND bk1.bkg_tentative_booking = 0 AND bpr.bkg_tentative_booking = 0
                WHERE bkg.bkg_booking_type IN (1,3) AND biv.bkg_advance_amount > 0 AND bkg.bkg_pickup_date > DATE_ADD(NOW(), INTERVAL 2 HOUR) $matchedList) a";

//echo $sql; exit();
//$count = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['MatchScore', 'up_bkg_id', 'up_bkg_booking_id', 'match_trip', 'down_bkg_bcb_id', 'up_bkg_pickup_date', 'down_bkg_booking_id', 'up_bkg_bcb_id', 'down_bkg_id', 'down_bkg_from_city_id', 'up_bkg_to_city_id', 'down_bkg_to_city_id', 'up_bkg_pickup_date', 'down_bkg_pickup_date'],
				'defaultOrder'	 => 'MatchScore DESC, vhtMatched DESC, up_bkg_pickup_date ASC, up_bkg_id ASC, down_bkg_pickup_date ASC'], 'pagination'	 => false,
		]);
//echo $sql; exit();
// $dataProvider = new CActiveDataProvider($this->together(), array('criteria' => $criteria, 'pagination' => ['pageSize' => 50]));
		return $dataprovider;
	}

	public function getSmartMatchList($today = 0, $command = false, $smart_broken = '', $smart_successful = '', $tripId = '')
	{
		$smartQuery = '';
		if($smart_broken > 0 && $smart_successful == 0)
		{
			$smartQuery .= " AND booking_cab.bcb_trip_type=0";
		}
		if($smart_successful > 0 && $smart_broken == 0)
		{
			$smartQuery .= " AND booking_cab.bcb_trip_type=1";
		}
		if(isset($tripId) && $tripId != "")
		{
			$smartQuery .= " AND (booking.bkg_bcb_id LIKE '%" . $tripId . "%')";
		}
		$sql = "SELECT trip_id, booking_ids, from_city_ids, to_city_ids,trip_status,
                FORMAT(trip_amount,2) as trip_amount,
                FORMAT(vendor_amount,2) as vendor_amount_original,
                FORMAT(smart_vendor_amount,2) as vendor_amount_smart_match,
                FORMAT(service_tax_amount,2) as service_tax_amount,
                FORMAT(gozo_amount,2) as gozo_amount_original,
                FORMAT(service_gozo_amount,2) as gozo_amount_smart_match,
                FORMAT(((gozo_amount)/trip_amount),2) as margin_original,
                FORMAT(((service_gozo_amount)/trip_amount),2) as margin_smart_match,
                match_date,
                name,
                type,
                matchtype
                FROM
                (
                    SELECT trip_id,
                    GROUP_CONCAT(bkg_booking_id SEPARATOR ',') as booking_ids,
                    GROUP_CONCAT(from_city SEPARATOR ',') as from_city_ids,
                    GROUP_CONCAT(to_city SEPARATOR ',') as to_city_ids,
                    SUM(bkg_total_amount) as trip_amount,
                    SUM(bkg_vendor_amount) as vendor_amount,
                    SUM(bkg_service_tax) as service_tax_amount,
                    SUM(bkg_gozo_amount-bkg_service_tax) as gozo_amount,
                    smart_vendor_amount,
                    (SUM(bkg_total_amount)-smart_vendor_amount-SUM(bkg_service_tax)) as service_gozo_amount,
                    MAX(blg_created) as match_date, name, type, matchtype,trip_status
                    FROM
                    (
                        SELECT 	booking.bkg_booking_id,booking.bkg_bcb_id as trip_id,booking.bkg_status as trip_status,
                                IF(booking_cab.bcb_matched_type IN (0,1),'Manual','Auto') as matchtype,

                                frmCity.cty_name as from_city, toCity.cty_name as to_city,
                                bkg_total_amount, bkg_vendor_amount,
                                bkg_service_tax, bkg_gozo_amount,
                                SUM(booking_cab.bcb_vendor_amount) as smart_vendor_amount,
                                blg_created, name, type
                                    FROM `booking`
									JOIN `booking_invoice` ON booking_invoice.biv_bkg_id=booking.bkg_id
                                    JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
                                    JOIN `cities` as frmCity ON frmCity.cty_id=booking.bkg_from_city_id
                                    JOIN `cities` as toCity ON toCity.cty_id=booking.bkg_to_city_id
                                    LEFT JOIN
                                    (
                                        SELECT booking_log.blg_created,
                                        booking_log.blg_user_type as type,
                                        CONCAT(admins.adm_fname,' ',admins.adm_lname) as name,
                                        booking_log.blg_booking_id,
                                        booking_log.blg_trip_id
                                        FROM `booking_log`
                                        LEFT JOIN `admins` ON admins.adm_id=booking_log.blg_user_id
                                        WHERE booking_log.blg_event_id=91
                                        AND booking_log.blg_trip_id IS NOT NULL
                                        GROUP BY booking_log.blg_booking_id
                                    )blg ON blg.blg_booking_id=booking.bkg_id
                                    WHERE EXISTS
                                    (
                                        SELECT bcb_id FROM (
                                            SELECT booking_cab.bcb_id,blg_event_id,bcb_trip_type FROM `booking_cab`
                                            INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id  AND booking.bkg_status IN (1,2,3,5,6,7,9)
                                            LEFT JOIN (
                                                SELECT booking_log.blg_booking_id,booking_log.blg_event_id
                                                FROM `booking_log`
                                                WHERE booking_log.blg_event_id IN (91)
                                                GROUP BY booking_log.blg_booking_id
                                            )blg ON blg.blg_booking_id=booking.bkg_id
                                            WHERE booking_cab.bcb_active=1  AND  (bcb_trip_type = 1 OR blg_event_id IN (91)) $smartQuery
                                            GROUP BY bcb_id
                                        )a where a.bcb_id=bkg_bcb_id
		                    ) AND booking.bkg_active=1 AND booking.bkg_status IN (1,2,3,5,6,7,9)
                       		GROUP BY booking.bkg_id
                     )a GROUP BY trip_id ";

		$sqlCount = "SELECT
	trip_id,
	match_date
	FROM
	(SELECT
	   trip_id,
	   MAX(blg_created) as match_date
    FROM
     (SELECT
      booking.bkg_bcb_id  AS trip_id
      ,blg_created
      FROM
       `booking`
       JOIN `booking_invoice` ON booking_invoice.biv_bkg_id = booking.bkg_id
       JOIN `booking_cab`  ON booking_cab.bcb_id = booking.bkg_bcb_id AND  booking_cab.bcb_active = 1
       JOIN `cities` AS frmCity ON frmCity.cty_id = booking.bkg_from_city_id
       JOIN `cities` AS toCity ON toCity.cty_id = booking.bkg_to_city_id
       LEFT JOIN
       (
          SELECT  booking_log.blg_created,booking_log.blg_booking_id,booking_log.blg_trip_id
          FROM   `booking_log`
          LEFT JOIN `admins` ON admins.adm_id = booking_log.blg_user_id
          WHERE  booking_log.blg_event_id = 91 AND  booking_log.blg_trip_id IS NOT NULL
          GROUP BY  booking_log.blg_booking_id
        ) blg  ON blg.blg_booking_id = booking.bkg_id
      WHERE
       exists (
                  SELECT
                        bcb_id
                              FROM
                               (
                                  SELECT
                                  booking_cab.bcb_id
                                  ,
								 blg_event_id
                                  ,bcb_trip_type
                                  FROM `booking_cab`
                                  INNER JOIN `booking` ON booking.bkg_bcb_id = booking_cab.bcb_id AND  booking.bkg_status IN (1,2,3,5,6,7,9)
                                  LEFT JOIN
                                      (
                                          SELECT
                                          booking_log.blg_booking_id
                                          ,booking_log.blg_event_id
                                          FROM `booking_log`
                                          WHERE booking_log.blg_event_id IN (91)
                                          GROUP BY booking_log.blg_booking_id
                                      ) blg  ON blg.blg_booking_id = booking.bkg_id
                                  WHERE  booking_cab.bcb_active = 1 and  (bcb_trip_type = 1 OR blg_event_id IN (91)) $smartQuery
                                  GROUP BY bcb_id
                                ) a where a.bcb_id=bkg_bcb_id
              ) AND booking.bkg_active = 1 AND  booking.bkg_status IN (1 ,2,3,5 ,6 ,7,9)
      GROUP BY booking.bkg_id) a
    GROUP BY trip_id ";

		if($today == 1)
		{
			$sql		 .= " HAVING match_date between CONCAT(DATE_SUB(curdate(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(curdate(),INTERVAL 1 DAY),' 23:59:59')";
			$sqlCount	 .= " HAVING match_date between CONCAT(DATE_SUB(curdate(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(curdate(),INTERVAL 1 DAY),' 23:59:59')";
		}
		$sql		 .= ")b  ";
		$sqlCount	 .= ") b ";
		if($command == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['trip_id', 'trip_amount', 'vendor_amount_original', 'vendor_amount_smart_match',
						'service_tax_amount', 'gozo_amount_original', 'gozo_amount_smart_match', 'margin_original', 'margin_smart_match'],
					'defaultOrder'	 => 'match_date DESC'],
				'pagination'	 => ['pageSize' => 25],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
	}

	public static function getAutoSmartMatch($bcbTypeMatched = 0)
	{
		$condition = " AND bcb_trip_type IN($bcbTypeMatched)";

		$sql = "SELECT *,
       ROUND(
            (  DurationScore
             + same_cab_type
             + same_class_type
             + reconfirm_matching_status
             + source_matching_dest
             + dest_matching_source
             + source_matching_adv_amt
             + dest_matching_adv_amt
             + quality_match
             + distance_match)
          * 100
          / 120,
          2)
          AS MatchScore
FROM (SELECT DISTINCT
             bk1.bkg_id
                AS up_bkg_id,
             bk1.bkg_booking_id
                AS up_bkg_booking_id,
             bk1.bkg_tentative_booking
                AS up_tentative_booking,
             bpr.bkg_tentative_booking
                AS down_tentative_booking,
             bk1.bkg_bcb_id
                AS up_bkg_bcb_id,
             bkg.bkg_bcb_id
                AS down_bkg_bcb_id,
             IF(bk1.bkg_bcb_id = bkg.bkg_bcb_id, 1, 0)
                AS bcbTypeMatched,
             bk1.bkg_from_city_id
                AS up_bkg_from_city_id,
             bkg.bkg_from_city_id
                AS down_bkg_from_city_id,
             bk1.bkg_to_city_id
                AS up_bkg_to_city_id,
             bkg.bkg_to_city_id
                AS down_bkg_to_city_id,
             bkg.bkg_id
                AS down_bkg_id,
             bkg.bkg_booking_id
                AS down_bkg_booking_id,
             bk1.bkg_pickup_date
                AS up_bkg_pickup_date,
             bkg.bkg_pickup_date
                AS down_bkg_pickup_date,
             cityFrom.cty_name
                AS down_bkg_from_city,
             DATE_ADD(
                DATE_ADD(bk1.bkg_pickup_date,
                         INTERVAL bk1.bkg_trip_duration MINUTE),
                INTERVAL FLOOR(bk1.bkg_trip_distance / 100) HOUR)
                AS MinReturnTime,
             DATE_ADD(DATE(bk1.bkg_pickup_date),
                      INTERVAL CEIL((bk1.bkg_trip_distance) / 250) DAY)
                AS MaxReturnTime,
             CASE
                WHEN     bkg.bkg_pickup_date >
                         DATE_ADD(
                            DATE_ADD(bk1.bkg_pickup_date,
                                     INTERVAL bk1.bkg_trip_duration MINUTE),
                            INTERVAL FLOOR(bk1.bkg_trip_distance / 100) HOUR)
                     AND DATE(
                            DATE_ADD(bkg.bkg_pickup_date,
                                     INTERVAL bkg.bkg_trip_duration MINUTE)) <=
                         DATE(
                            DATE_ADD(
                               DATE(bk1.bkg_pickup_date),
                               INTERVAL CEIL((bk1.bkg_trip_distance) / 250) DAY))
                THEN
                   20
                WHEN     bkg.bkg_pickup_date >
                         DATE_ADD(
                            DATE_ADD(bk1.bkg_pickup_date,
                                     INTERVAL bk1.bkg_trip_duration MINUTE),
                            INTERVAL FLOOR(bk1.bkg_trip_distance / 100) HOUR)
                     AND DATE(
                            DATE_ADD(bkg.bkg_pickup_date,
                                     INTERVAL bkg.bkg_trip_duration MINUTE)) <=
                         DATE(
                            DATE_ADD(
                               bk1.bkg_pickup_date,
                               INTERVAL FLOOR(
                                             (  bk1.bkg_trip_distance
                                              + bkg.bkg_trip_distance)
                                           / 250) DAY))
                THEN
                   15
                WHEN     bkg.bkg_pickup_date >
                         DATE_ADD(
                            DATE_ADD(bk1.bkg_pickup_date,
                                     INTERVAL bk1.bkg_trip_duration MINUTE),
                            INTERVAL FLOOR(bk1.bkg_trip_distance / 100) HOUR)
                     AND DATE(
                            DATE_ADD(bkg.bkg_pickup_date,
                                     INTERVAL bkg.bkg_trip_duration MINUTE)) <=
                         DATE(
                            DATE_ADD(
                               bk1.bkg_pickup_date,
                               INTERVAL CEIL(
                                             (  bk1.bkg_trip_distance
                                              + bkg.bkg_trip_distance)
                                           / 250) DAY))
                THEN
                   5
                ELSE
                   0
             END
                AS DurationScore,
             bkg.bkg_status
                AS down_bkg_status,
             bk1.bkg_status
                AS up_bkg_status,
             cityTo.cty_name
                AS down_bkg_to_city,
             cityTo.cty_lat
                AS down_city_lat,
             cityTo.cty_long
                AS down_city_long,
             vendor_name,
             vendor_city,
             vendor_total_trip,
             vendor_rating,
             bk1.bkg_total_amount
                AS bkg1_total_amount,
             bk1.bkg_advance_amount
                AS bkg1_advance_amount,
             biv.bkg_total_amount,
             biv.bkg_advance_amount,
             vendors.vnd_name
                AS down_booking_vendor_name,
             vndcity.cty_name
                AS down_booking_vendor_city,
             vendor_stats.vrs_vnd_total_trip
                AS down_booking_vendor_total_trips,
             vendor_stats.vrs_vnd_overall_rating
                AS down_booking_vendor_rating,
             bk1.bkg_trip_duration
                AS up_booking_duration,
             bk1.bkg_reconfirm_flag
                AS up_booking_confirm,
             bkg.bkg_reconfirm_flag
                AS dn_booking_confirm,
             vctdown.vct_desc
                AS down_vht_model,
             vctdown.vct_label
                AS down_vht_make,
             CASE
                WHEN     bk1.bkg_reconfirm_flag = 1
                     AND bkg.bkg_reconfirm_flag = 1
                THEN
                   10
                ELSE
                   0
             END
                AS reconfirm_matching_status,
             CASE
                WHEN    FIND_IN_SET(vctdown.vct_id, up_vht_match_type)
                     OR FIND_IN_SET(up_vht_id, vctdown.vct_matching_type)
                THEN
                   10
                ELSE
                   0
             END
                AS same_cab_type,
             CASE
                WHEN (CASE
                         WHEN vctdown.vct_id = 1 THEN 1
                         WHEN vctdown.vct_id = 2 THEN 3
                         WHEN vctdown.vct_id = 3 THEN 2
                         ELSE NULL
                      END) =
                     up_vht_class
                THEN
                   10
                ELSE
                   0
             END
                AS same_class_type,
             CASE
                WHEN bk1.bkg_from_city_id = bkg.bkg_to_city_id THEN 10
                ELSE 0
             END
                AS source_matching_dest,
             CASE
                WHEN bk1.bkg_to_city_id = bkg.bkg_from_city_id THEN 10
                ELSE 0
             END
                AS dest_matching_source,
             CASE
                WHEN TIMESTAMPDIFF(
                        HOUR,
                        DATE_ADD(bk1.bkg_pickup_date,
                                 INTERVAL bk1.bkg_trip_duration MINUTE),
                        bkg.bkg_pickup_date) BETWEEN IF(
                                                        bk1.bkg_trip_duration <=
                                                        240,
                                                        2,
                                                        IF(
                                                           bk1.bkg_trip_duration <=
                                                           540,
                                                           3,
                                                           6))
                                                 AND IF(
                                                        bk1.bkg_trip_duration <=
                                                        240,
                                                        6,
                                                        IF(
                                                           bk1.bkg_trip_duration <=
                                                           540,
                                                           8,
                                                           10))
                THEN
                   20
                WHEN TIMESTAMPDIFF(
                        HOUR,
                        DATE_ADD(bk1.bkg_pickup_date,
                                 INTERVAL bk1.bkg_trip_duration MINUTE),
                        bkg.bkg_pickup_date) BETWEEN IF(
                                                        bk1.bkg_trip_duration <=
                                                        240,
                                                        6,
                                                        IF(
                                                           bk1.bkg_trip_duration <=
                                                           540,
                                                           8,
                                                           10))
                                                 AND IF(
                                                        bk1.bkg_trip_duration <=
                                                        240,
                                                        12,
                                                        IF(
                                                           bk1.bkg_trip_duration <=
                                                           540,
                                                           14,
                                                           16))
                THEN
                   10
                WHEN TIMESTAMPDIFF(
                        HOUR,
                        DATE_ADD(bk1.bkg_pickup_date,
                                 INTERVAL bk1.bkg_trip_duration MINUTE),
                        bkg.bkg_pickup_date) BETWEEN IF(
                                                        bk1.bkg_trip_duration <=
                                                        240,
                                                        12,
                                                        IF(
                                                           bk1.bkg_trip_duration <=
                                                           540,
                                                           14,
                                                           16))
                                                 AND 24
                THEN
                   5
                ELSE
                   0
             END
                AS time_interval,
             CASE WHEN bk1.bkg_advance_amount > 0 THEN 15 ELSE 0 END
                AS source_matching_adv_amt,
             CASE WHEN biv.bkg_advance_amount > 0 THEN 15 ELSE 0 END
                AS dest_matching_adv_amt,
             CASE
                WHEN SQRT(
                          POW(69.1 * (cityFrom.cty_lat - up_city_lat), 2)
                        + POW(
                               69.1
                             * (up_city_long - cityFrom.cty_long)
                             * COS(cityFrom.cty_lat / 57.3),
                             2)) BETWEEN 0
                                     AND 8
                THEN
                   10
                WHEN SQRT(
                          POW(69.1 * (cityFrom.cty_lat - up_city_lat), 2)
                        + POW(
                               69.1
                             * (up_city_long - cityFrom.cty_long)
                             * COS(cityFrom.cty_lat / 57.3),
                             2)) BETWEEN 8
                                     AND 16
                THEN
                   5
                WHEN SQRT(
                          POW(69.1 * (cityFrom.cty_lat - up_city_lat), 2)
                        + POW(
                               69.1
                             * (up_city_long - cityFrom.cty_long)
                             * COS(cityFrom.cty_lat / 57.3),
                             2)) BETWEEN 16
                                     AND 25
                THEN
                   0
                ELSE
                   0
             END
                AS quality_match,
             CASE
                WHEN ROUND(
                          (bk1.bkg_trip_distance + bkg.bkg_trip_distance)
                        / ((  DATEDIFF(
                                 DATE_ADD(
                                    bkg.bkg_pickup_date,
                                    INTERVAL bkg.bkg_trip_duration MINUTE),
                                 bk1.bkg_pickup_date)
                            * 250))
                        * 100) BETWEEN 125
                                   AND 150
                THEN
                   2.5
                WHEN ROUND(
                          (bk1.bkg_trip_distance + bkg.bkg_trip_distance)
                        / ((  DATEDIFF(
                                 DATE_ADD(
                                    bkg.bkg_pickup_date,
                                    INTERVAL bkg.bkg_trip_duration MINUTE),
                                 bk1.bkg_pickup_date)
                            * 250))
                        * 100) BETWEEN 151
                                   AND 175
                THEN
                   5
                WHEN ROUND(
                          (bk1.bkg_trip_distance + bkg.bkg_trip_distance)
                        / ((  DATEDIFF(
                                 DATE_ADD(
                                    bkg.bkg_pickup_date,
                                    INTERVAL bkg.bkg_trip_duration MINUTE),
                                 bk1.bkg_pickup_date)
                            * 250))
                        * 100) BETWEEN 176
                                   AND 200
                THEN
                   7.5
                WHEN ROUND(
                          (bk1.bkg_trip_distance + bkg.bkg_trip_distance)
                        / ((  DATEDIFF(
                                 DATE_ADD(
                                    bkg.bkg_pickup_date,
                                    INTERVAL bkg.bkg_trip_duration MINUTE),
                                 bk1.bkg_pickup_date)
                            * 250))
                        * 100) >
                     200
                THEN
                   10
                ELSE
                   0
             END
                AS distance_match,
             up_bkg_from_city,
             up_bkg_to_city,
             bkg.bkg_return_id
                AS down_bkg_return_id,
             bk1.bkg_return_id
                AS up_bkg_return_id,
             SQRT(
                  POW(69.1 * (cityFrom.cty_lat - up_city_lat), 2)
                + POW(
                       69.1
                     * (up_city_long - cityFrom.cty_long)
                     * COS(cityFrom.cty_lat / 57.3),
                     2))
                AS distance,
             IF(bkg.bkg_vehicle_type_id = bk1.bkg_vehicle_type_id, 1, 0)
                AS vhtMatched,
             booking_cab.bcb_vendor_id
                down_vendor_id,
             bk1.up_vendor_id,
             bkg.bkg_vehicle_type_id
                down_vehicle_type,
             bk1.bkg_vehicle_type_id
                up_vehicle_type
      FROM booking bkg
           INNER JOIN booking_pref AS bpr ON bpr.bpr_bkg_id = bkg_id
           INNER JOIN booking_invoice AS biv ON biv.biv_bkg_id = bkg_id
           INNER JOIN booking_cab
              ON     bkg.bkg_bcb_id = bcb_id
                 AND bcb_active = 1
                 $condition
                 AND bkg.bkg_status IN (2)
           LEFT JOIN zone_cities zct1From
              ON bkg.bkg_from_city_id = zct1From.zct_cty_id
           LEFT JOIN zone_cities zct1To
              ON bkg.bkg_to_city_id = zct1To.zct_cty_id
           LEFT JOIN cities cityFrom ON cityFrom.cty_id = zct1From.zct_cty_id
           LEFT JOIN cities cityTo ON cityTo.cty_id = zct1To.zct_cty_id
           LEFT JOIN svc_class_vhc_cat scvdown
              ON bkg_vehicle_type_id = scvdown.scv_id
           LEFT JOIN vehicle_category vctdown
              ON scvdown.scv_vct_id = vctdown.vct_id
           LEFT JOIN vendors ON vnd_id = booking_cab.bcb_vendor_id
           LEFT JOIN contact ON vnd_contact_id = ctt_id
           LEFT JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
           LEFT JOIN cities vndcity ON contact.ctt_city = vndcity.cty_id
           LEFT JOIN booking_route brt ON brt.brt_bkg_id = bkg.bkg_id
           INNER JOIN
           (SELECT DISTINCT
                   booking.*,
                   bpr1.bkg_tentative_booking,
                   biv1.bkg_total_amount,
                   biv1.bkg_advance_amount,
                   zct2From.zct_cty_id
                      AS zct_from_city,
                   zct2From.zct_zon_id
                      AS zct_from_zone,
                   zct2To.zct_cty_id
                      AS zct_to_city,
                   zct2To.zct_zon_id
                      AS zct_to_zone,
                   (SELECT booking_route.brt_trip_distance
                    FROM booking_route
                    WHERE     brt_active = 1
                          AND booking_route.brt_bkg_id = booking.bkg_id
                    ORDER BY booking_route.brt_pickup_datetime DESC
                    LIMIT 0, 1)
                      AS lastDistance,
                   city2From.cty_name
                      AS up_bkg_from_city,
                   city2From.cty_lat
                      AS up_from_city_lat,
                   city2From.cty_long
                      AS up_from_city_long,
                   city2To.cty_name
                      AS up_bkg_to_city,
                   city2To.cty_lat
                      AS up_city_lat,
                   city2To.cty_long
                      AS up_city_long,
                   vnd.vnd_name
                      AS vendor_name,
                   vndcity.cty_name
                      AS vendor_city,
                   vrs.vrs_vnd_total_trip
                      AS vendor_total_trip,
                   vrs.vrs_vnd_overall_rating
                      AS vendor_rating,
                   vctUp.vct_matching_type
                      AS up_vht_match_type,
                   CASE
                      WHEN vctUp.vct_id = 1 THEN 1
                      WHEN vctUp.vct_id = 2 THEN 3
                      WHEN vctUp.vct_id = 3 THEN 2
                      ELSE NULL
                   END
                      AS up_vht_class,
                   vctUp.vct_id
                      AS up_vht_id,
                   booking_cab.bcb_vendor_id
                      up_vendor_id
            FROM booking
                 INNER JOIN booking_pref AS bpr1 ON bpr1.bpr_bkg_id = bkg_id
                 INNER JOIN booking_invoice AS biv1
                    ON biv1.biv_bkg_id = bkg_id
                 INNER JOIN booking_cab
                    ON     booking.bkg_bcb_id = bcb_id
                       AND bcb_active = 1
                       $condition
                 LEFT JOIN zone_cities zct2From
                    ON     bkg_from_city_id = zct2From.zct_cty_id
                       AND zct2From.zct_active = 1
                 LEFT JOIN zone_cities zct2To
                    ON     bkg_to_city_id = zct2To.zct_cty_id
                       AND zct2To.zct_active = 1
                 LEFT JOIN cities city2From
                    ON city2From.cty_id = zct2From.zct_cty_id
                 LEFT JOIN cities city2To
                    ON city2To.cty_id = zct2To.zct_cty_id
                 LEFT JOIN svc_class_vhc_cat scvUp
                    ON bkg_vehicle_type_id = scvUp.scv_id
                 LEFT JOIN vehicle_category vctUp
                    ON scvUp.scv_vct_id = vctUp.vct_id
                 LEFT JOIN vendors vnd ON vnd_id = booking_cab.bcb_vendor_id
                 LEFT JOIN contact ctt ON vnd.vnd_contact_id = ctt.ctt_id
                 LEFT JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id
                 LEFT JOIN cities vndcity ON ctt.ctt_city = vndcity.cty_id
            WHERE     bkg_active = 1
                  AND bkg_booking_type = 1
                  AND bkg_status IN (2)
                  AND bkg_pickup_date <= DATE_ADD(NOW(), INTERVAL 6 DAY)) bk1
              ON     SQRT(
                          POW(69.1 * (cityFrom.cty_lat - up_city_lat), 2)
                        + POW(
                               69.1
                             * (up_city_long - cityFrom.cty_long)
                             * COS(cityFrom.cty_lat / 57.3),
                             2)) <
                     30
                 AND SQRT(
                          POW(69.1 * (cityTo.cty_lat - up_from_city_lat), 2)
                        + POW(
                               69.1
                             * (up_from_city_long - cityTo.cty_long)
                             * COS(cityTo.cty_lat / 57.3),
                             2)) <
                     30
                 AND bk1.bkg_from_city_id <> bkg.bkg_from_city_id
                 AND bk1.bkg_to_city_id <> bkg.bkg_to_city_id
                 AND bk1.bkg_pickup_date >
                     DATE_ADD(
                        NOW(),
                        INTERVAL IF(
                                    bk1.bkg_status = 2 AND bkg.bkg_status = 2,
                                    4,
                                    IF(
                                           bk1.bkg_status = 2
                                       AND bkg.bkg_status > 2,
                                       7,
                                       16)) HOUR)
                 AND bk1.bkg_advance_amount > 0
                 AND bkg.bkg_vehicle_type_id = bk1.bkg_vehicle_type_id
                 AND bkg.bkg_pickup_date >=
                     DATE_ADD(
                        DATE_ADD(bk1.bkg_pickup_date,
                                 INTERVAL bk1.bkg_trip_duration MINUTE),
                        INTERVAL 60 MINUTE)
                 AND DATE(bkg.bkg_pickup_date) <=
                     DATE(
                        DATE_ADD(
                           bk1.bkg_pickup_date,
                           INTERVAL   ROUND(
                                           (  bk1.bkg_trip_distance
                                            + bkg.bkg_trip_distance)
                                         / 250)
                                    - 1 DAY))
                 AND bk1.bkg_id <> bkg.bkg_id
                 AND bpr.bkg_tentative_booking = 0
                 AND bk1.bkg_tentative_booking = 0
      WHERE     bkg.bkg_reconfirm_flag = 1
            AND bk1.bkg_reconfirm_flag = 1
            AND bkg.bkg_booking_type IN (1, 3)
            AND biv.bkg_advance_amount > 0
            AND bkg.bkg_pickup_date > DATE_ADD(NOW(), INTERVAL 2 HOUR)) a
HAVING MatchScore > 70
ORDER BY MatchScore DESC";
		return DBUtil::queryAll($sql);
	}

	public function getRoutePerformanceReport()
	{
		$sql = "SELECT COUNT(1) as cout, zone, frmZone, toZone, frmCity , toCity, ((frmZonId*toZonId)+(frmZonId+toZonId)) as zonVal , frmZonId , toZonId,
                SUM(
                    IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(create_date,'%m%Y'),1,0)
                ) as zone_mtd_cout,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(create_date,'%m%Y'),1,0)
                ) as zone_month1_cout,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(create_date,'%m%Y'),1,0)
                ) as zone_month2_cout,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH),'%m%Y') = DATE_FORMAT(create_date,'%m%Y'),1,0)
                ) as zone_month3_cout,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 4 MONTH),'%m%Y') = DATE_FORMAT(create_date,'%m%Y'),1,0)
                ) as zone_month4_cout,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH),'%m%Y') = DATE_FORMAT(create_date,'%m%Y'),1,0)
                ) as zone_month5_cout,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 6 MONTH),'%m%Y') = DATE_FORMAT(create_date,'%m%Y'),1,0)
                ) as zone_month6_cout,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 7 MONTH),'%m%Y') = DATE_FORMAT(create_date,'%m%Y'),1,0)
                ) as zone_month7_cout,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 8 MONTH),'%m%Y') = DATE_FORMAT(create_date,'%m%Y'),1,0)
                ) as zone_month8_cout,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 9 MONTH),'%m%Y') = DATE_FORMAT(create_date,'%m%Y'),1,0)
                ) as zone_month9_cout,
                SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 10 MONTH),'%m%Y') = DATE_FORMAT(create_date,'%m%Y'),1,0)
                ) as zone_month10_cout,
                 SUM(
                    IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 11 MONTH),'%m%Y') = DATE_FORMAT(create_date,'%m%Y'),1,0)
                ) as zone_month11_cout,
                SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(create_date,'%Y'),1,0)) as zone_ytd_count,
                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(create_date,'%Y'),1,0)) as zone_year1_count,
                SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 YEAR),'%Y') = DATE_FORMAT(create_date,'%Y'),1,0)) as zone_year2_count
                FROM
                (
                    SELECT booking.bkg_id, booking.bkg_create_date as create_date,
                    zon1.zon_name as frmZone,zon2.zon_name as toZone, city1.cty_name as frmCity, city2.cty_name as toCity,
                    CONCAT(REPLACE(zon1.zon_name, 'Z-', ' '),'-',REPLACE(zon2.zon_name, 'Z-', ' ')) as zone,zon1.zon_id as frmZonId,zon2.zon_id as toZonId
                    FROM `booking`
                    LEFT JOIN `zone_cities` as zcity1 ON zcity1.zct_cty_id=booking.bkg_from_city_id
                    LEFT JOIN `zone_cities` as zcity2 ON zcity2.zct_cty_id=booking.bkg_to_city_id
                    LEFT JOIN `zones` as zon1 ON zon1.zon_id=zcity1.zct_zon_id
                    LEFT JOIN `zones` as zon2 ON zon2.zon_id=zcity2.zct_zon_id
                   INNER  JOIN `cities` as city1 ON city1.cty_id=booking.bkg_from_city_id
                    INNER  JOIN `cities` as city2 ON city2.cty_id=booking.bkg_to_city_id
                    WHERE booking.bkg_status IN (6,7,9)
                    AND booking.bkg_active=1 AND DATE(booking.bkg_create_date)>='2015-10-01'
                )a GROUP BY zonVal HAVING zone IS NOT NULL ORDER BY zone";
		return DBUtil::queryAll($sql);
	}

	public function getZoneCancellationReport($city = 'from', $command = false)
	{
		$city_id = ($city == 'from') ? 'bkg_from_city_id' : 'bkg_to_city_id';
		$sql	 = "SELECT zon_name,
                createdMtd , cancelMtd, ROUND(cancelMtd * 100/createdMtd) as cancelMtdRatio , advanceMtd ,
                createdYtd, cancelYtd , ROUND(cancelYtd * 100/createdYtd) as cancelYtdRatio , advanceYtd,
                createdMonth1, cancelMonth1, ROUND(cancelMonth1 * 100/createdMonth1) as cancelMonth1Ratio , advanceMonth1,
                createdMonth2, cancelMonth2, ROUND(cancelMonth2 * 100/createdMonth2) as cancelMonth2Ratio , advanceMonth2
                FROM (
                    SELECT zones.zon_name ,
                    createdMtd , cancelMtd, advanceMtd,
                    createdYtd, cancelYtd , advanceYtd ,
                    createdMonth1, cancelMonth1, advanceMonth1 ,
                    createdMonth2, cancelMonth2, advanceMonth2
                    FROM `zones`
                    LEFT JOIN (
                        SELECT COUNT(1) as createdYtd,
                        zone_cities.zct_zon_id ,
                        SUM(IF(biv.bkg_advance_amount>0,1,0)) as advanceYtd,
                        SUM(IF(booking.bkg_status=9,1,0)) as cancelYtd,
                        SUM(
                            IF((DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_pickup_date,'%m%Y') AND booking.bkg_status IN (2,3,5,6,7,9)),1,0)
                        ) as createdMtd,
                        SUM(
                            IF((DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_pickup_date,'%m%Y') AND booking.bkg_status IN (9)),1,0)
                        ) as cancelMtd,
                        SUM(
                            IF((DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_pickup_date,'%m%Y') AND biv.bkg_advance_amount>0),1,0)
                        ) as advanceMtd,
                                SUM(
                            IF((DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_pickup_date,'%m%Y') AND booking.bkg_status IN (2,3,5,6,7,9)),1,0)
                        ) as createdMonth1,
                        SUM(
                            IF((DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_pickup_date,'%m%Y') AND booking.bkg_status IN (9)),1,0)
                        ) as cancelMonth1,
                        SUM(
                            IF((DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_pickup_date,'%m%Y') AND biv.bkg_advance_amount>0),1,0)
                        ) as advanceMonth1,
                        SUM(
                             IF((DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_pickup_date,'%m%Y') AND booking.bkg_status IN (2,3,5,6,7,9)),1,0)
                        ) as createdMonth2,
                        SUM(
                            IF((DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_pickup_date,'%m%Y') AND booking.bkg_status IN (9)),1,0)
                        ) as cancelMonth2,
                        SUM(
                            IF((DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_pickup_date,'%m%Y') AND biv.bkg_advance_amount>0),1,0)
                        ) as advanceMonth2
                        FROM `booking`
                        INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                        INNER JOIN `zone_cities` ON zone_cities.zct_cty_id=$city_id
                        INNER JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id
                        WHERE booking.bkg_status IN (2,3,5,6,7,9)
                        AND date(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(NOW(),'%Y-01-01') AND CURDATE()
                        AND booking.bkg_active=1
                        GROUP BY zones.zon_id
                    )b ON b.zct_zon_id=zones.zon_id

                )b HAVING cancelMonth1 >5 ORDER BY cancelMonth1Ratio DESC";

		if($command == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['zon_name'],
					'defaultOrder'	 => 'cancelMonth1Ratio DESC'],
				'pagination'	 => ['pageSize' => 25],
			]);
			return $dataprovider;
		}
		else
		{
			$sql .= " LIMIT 0,20";
			return DBUtil::queryAll($sql);
		}
	}

	public function getNonProfitBookingsByMtd()
	{
		$sql = "SELECT DISTINCT booking.bkg_id, booking.bkg_booking_id, biv.bkg_total_amount, biv.bkg_vendor_amount,
                biv.bkg_service_tax, booking.bkg_create_date, booking.bkg_pickup_date, booking.bkg_modified_on,
                btr.bkg_non_profit_flag, booking.bkg_status,
                (biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax) as loss_amount
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                INNER JOIN booking_trail as btr ON btr.btr_bkg_id=bkg_id
                WHERE booking.bkg_status IN (2,3,4,5,6,7,9)
                AND booking_cab.bcb_active=1
                AND booking.bkg_create_date BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND CURDATE()
                GROUP BY booking.bkg_id
                HAVING ((biv.bkg_total_amount-biv.bkg_vendor_amount-biv.bkg_service_tax)<0)
                ORDER BY booking.bkg_id DESC";
		return DBUtil::queryAll($sql);
	}

	public function getExpTimeAdvPromo($createDate, $pickTime, $app = false)
	{
		$now				 = new DateTime(date('Y-m-d H:i:s'));
		$add8hrCreateDate	 = new DateTime(date('Y-m-d H:i:s', strtotime($createDate . '+8 hour')));
		$sub24pickDate		 = new DateTime(date('Y-m-d H:i:s', strtotime($pickTime . '-24 hour')));
		$sub12pickDate		 = new DateTime(date('Y-m-d H:i:s', strtotime($pickTime . '-12 hour')));
		$sub8pickdate		 = new DateTime(date('Y-m-d H:i:s', strtotime($pickTime . '-8 hour')));
		$sub18pickdate		 = new DateTime(date('Y-m-d H:i:s', strtotime($pickTime . '-18 hour')));

		if($app)
		{
			if($now < $sub24pickDate)
			{
				return 2;
			}
			if($now < $sub12pickDate)
			{
				return 3;
			}
			else
			{
				return 4;
			}
			return 0;
		}

		if($now < $add8hrCreateDate && $now < $sub8pickdate)
		{
			return 1;
		}
		if($now < $sub18pickdate)
		{
			return 1;
		}
		else if($now > $sub18pickdate && $now < $sub8pickdate)
		{
			return 3;
		}
		else if($now > $sub8pickdate)
		{
			return 0;
		}

		return 0;
	}

	public function bookingReport($date1 = '', $date2 = '', $from = '', $to = '', $vendor = '', $platform = '', $status = '', $type = 'data', $agent = '')
	{
		$sql = "SELECT booking_user.bkg_user_id, booking.bkg_id,booking.bkg_booking_id, booking_user.bkg_user_fname AS firstName,booking_user.bkg_user_lname AS lastName,
			    booking_user.bkg_user_email AS email,booking_user.bkg_contact_no AS phone,booking_user.bkg_country_code AS countryCode, booking_invoice.bkg_total_amount, booking_invoice.bkg_vendor_amount, booking_invoice.bkg_advance_amount,
                booking_invoice.bkg_due_amount, booking_invoice.bkg_gozo_amount,  booking.bkg_create_date, booking.bkg_pickup_date, booking.bkg_pickup_address, booking.bkg_drop_address,
                booking.bkg_return_date, booking_trail.bkg_platform, booking.bkg_status, cancellation_datetime, booking.bkg_booking_type, booking_add_info.bkg_info_source,
                booking.bkg_create_date as created, sourceZone, destinationZone,
				vct.vct_label AS serviceClass,
                CONCAT(fromCity.cty_name,' - ',toCity.cty_name) as cities, fromCity.cty_name as fromCity, toCity.cty_name as toCity,
                booking_cab.bcb_vendor_id as vendor_id, vendors.vnd_name as vendor_name, phn.phn_phone_no AS vnd_phone,
                drv_name as driver_name, vehicles.vhc_number as cab_number , If(booking.bkg_status IN(2,3, 9), vct.vct_desc, vht.vht_model ) vht_model,
                (CASE
                    WHEN (stt_zone='1') THEN 'North' WHEN (stt_zone='2') THEN 'West' WHEN (stt_zone='3') THEN 'Central'
                    WHEN (stt_zone='4') THEN 'South' WHEN (stt_zone='5') THEN 'East' WHEN (stt_zone='6') THEN 'North East'
                END
                ) as region, IF(booking.bkg_agent_id>0,'B2B','B2C') as book_type, CONCAT(agents.agt_fname,' ',agents.agt_lname) as agent_name,
                (CASE
                	WHEN (booking_pref.bkg_tentative_booking='0') THEN 'Not Tentative'
                 	WHEN (booking_pref.bkg_tentative_booking='1') THEN 'Tentative'
                 	WHEN (booking_pref.bkg_tentative_booking='2') THEN 'Was Tentative'
                END) as tentative_flag,
				IF(bkg_status <> 9 AND bkg_reconfirm_flag=1, (bkg_net_base_amount), 0) AS ry_booking_amount,
				IF(bkg_status <> 9 AND bkg_reconfirm_flag=1, bkg_gozo_amount- IFNULL(bkg_credits_used,0),0) AS ry_gozo_amount,
				IF(bkg_status <> 9 AND bkg_reconfirm_flag=1, bkg_total_amount-bkg_quoted_vendor_amount-IFNULL(bkg_credits_used,0)-IFNULL(bkg_service_tax,0)-IFNULL(bkg_partner_commission,0), 0) AS ry_quote_vendor_amount,
				bkg_base_amount AS base_fare,
				bkg_discount_amount AS discount,
				bkg_quoted_vendor_amount,
				bcb_vendor_amount AS trip_vendor_amount,

				(
					CASE booking.bkg_booking_type WHEN 1 THEN 'OW' WHEN 2 THEN 'RT' WHEN 3 THEN 'MW' WHEN 4 THEN 'AT' WHEN 5 THEN 'PT' WHEN 6 THEN 'FL' WHEN 7 THEN 'SH' WHEN 8 THEN 'CT'
					WHEN 9 THEN 'DR(4hr-40km)' WHEN 10 THEN 'DR(8hr-80km)' WHEN 11 THEN 'DR(12hr-120km)' WHEN 12 THEN 'AP' WHEN 15 THEN 'LT'
				END
				) AS serviceType,
                                 '' AS TotalVendorAssignedCount,
               '' AS LVendorAssignmentDate,
               '' AS FVendorAssignmentDate,
               '' AS LVendorAmount,
               '' AS FVendorAmount,
               '' AS LVendorID,
               '' AS FVendorID,
               bpc.bkg_dzpp_surge_factor AS dzpp_surge
                FROM `booking`
                JOIN `booking_user` ON booking_user.bui_bkg_id=booking.bkg_id AND booking.bkg_active=1 AND booking.bkg_status IN (2,3,5,6,7,9)
                JOIN booking_price_factor bpc ON bpc.bpf_bkg_id  =booking.bkg_id
                JOIN `booking_invoice` ON booking_invoice.biv_bkg_id=booking.bkg_id
                JOIN `booking_add_info` ON booking_add_info.bad_bkg_id=booking.bkg_id
                JOIN `booking_trail` ON booking_trail.btr_bkg_id=booking.bkg_id
                JOIN `booking_pref` ON booking_pref.bpr_bkg_id=booking.bkg_id
                JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
                JOIN `cities` fromCity ON fromCity.cty_id=booking.bkg_from_city_id
                JOIN `cities` toCity ON toCity.cty_id=booking.bkg_to_city_id
                LEFT JOIN `vendors` ON vendors.vnd_id=booking_cab.bcb_vendor_id and vendors.vnd_active IN (1,2)
                LEFT JOIN `contact` ctt ON ctt.ctt_id=vendors.vnd_contact_id
                LEFT JOIN `contact_phone` phn ON phn.phn_contact_id=ctt.ctt_id
                LEFT JOIN `vehicles` ON vehicles.vhc_id=booking_cab.bcb_cab_id and vehicles.vhc_active=1
                LEFT JOIN `vehicle_types` vht ON vht.vht_id=vehicles.vhc_type_id AND vht.vht_active>0
                INNER JOIN svc_class_vhc_cat scv ON scv.scv_id=booking.bkg_vehicle_type_id
                INNER JOIN vehicle_category vct ON vct.vct_id=scv.scv_vct_id AND vct.vct_active>0
                INNER JOIN service_class sc ON scv.scv_scc_id = sc.scc_id
                LEFT JOIN `drivers` ON drivers.drv_id=booking_cab.bcb_driver_id
                LEFT JOIN `agents` ON agents.agt_id=booking.bkg_agent_id
                LEFT JOIN
                (
                    SELECT zones.zon_name as sourceZone,zone_cities.zct_cty_id
                    FROM `zone_cities`
                    INNER JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id
                ) zon1 ON zon1.zct_cty_id=booking.bkg_from_city_id
                LEFT JOIN
                (
                    SELECT zones.zon_name as destinationZone,zone_cities.zct_cty_id
                    FROM `zone_cities`
                    INNER JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id
                ) zon2 ON zon2.zct_cty_id=booking.bkg_to_city_id
                LEFT JOIN
                (
                    SELECT states.stt_name as stateName,cities.cty_id,states.stt_zone
                    FROM `states`
                    INNER JOIN `cities` ON cities.cty_state_id=states.stt_id
                    GROUP BY cities.cty_id
                ) state1 ON state1.cty_id=booking.bkg_from_city_id
                LEFT JOIN(
                    SELECT MAX(booking_log.blg_created) as cancellation_datetime,blg_booking_id
                    FROM `booking_log` WHERE booking_log.blg_event_id IN (10,82)
                    GROUP BY booking_log.blg_booking_id
                )blg ON blg.blg_booking_id=booking.bkg_id
                WHERE 1";

		$sqlCount = "
				SELECT booking.bkg_id
				FROM `booking`  JOIN `booking_user`
				  ON     booking_user.bui_bkg_id = booking.bkg_id
					 AND booking.bkg_active = 1
					 AND booking.bkg_status IN (2,
												3,
												5,
												6,
												7,
												9)
				JOIN `booking_invoice`
				  ON booking_invoice.biv_bkg_id = booking.bkg_id
				JOIN `booking_add_info`
				  ON booking_add_info.bad_bkg_id = booking.bkg_id
				JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id
				JOIN `booking_pref` ON booking_pref.bpr_bkg_id = booking.bkg_id
				JOIN `booking_cab`
				  ON     booking_cab.bcb_id = booking.bkg_bcb_id
					 AND booking_cab.bcb_active = 1
                                JOIN booking_price_factor bpc ON bpc.bpf_bkg_id  = booking.bkg_id
                WHERE   1 ";

		if(isset($vendor) && $vendor != '')
		{
			$sql		 .= " AND booking_cab.bcb_vendor_id='" . $vendor . "'";
			$sqlCount	 .= " AND booking_cab.bcb_vendor_id='" . $vendor . "'";
		}
		if(isset($status) && $status != '')
		{
			$sql		 .= " AND booking.bkg_status='" . $status . "'";
			$sqlCount	 .= " AND booking.bkg_status='" . $status . "'";
		}
		if($this->bkg_agent_id != '')
		{
			$sql		 .= " AND booking.bkg_agent_id=$this->bkg_agent_id";
			$sqlCount	 .= " AND booking.bkg_agent_id=$this->bkg_agent_id";
		}
		if(isset($platform) && $platform != '')
		{
			$sql		 .= " AND booking_trail.bkg_platform='" . $platform . "'";
			$sqlCount	 .= " AND booking_trail.bkg_platform='" . $platform . "'";
		}
		if((isset($from) && $from != '') && (isset($to) && $to != ''))
		{
			$sql		 .= " AND booking.bkg_id IN (SELECT booking.bkg_id FROM booking WHERE bkg_from_city_id='" . $from . "' AND bkg_to_city_id='" . $to . "')";
			$sqlCount	 .= " AND booking.bkg_id IN (SELECT booking.bkg_id FROM booking WHERE bkg_from_city_id='" . $from . "' AND bkg_to_city_id='" . $to . "')";
		}
		if((isset($from) && $from != ''))
		{
			$sql		 .= " AND booking.bkg_id IN (SELECT booking.bkg_id FROM booking WHERE bkg_from_city_id='" . $from . "')";
			$sqlCount	 .= " AND booking.bkg_id IN (SELECT booking.bkg_id FROM booking WHERE bkg_from_city_id='" . $from . "')";
		}
		if((isset($to) && $to != ''))
		{
			$sql		 .= " AND booking.bkg_id IN (SELECT booking.bkg_id FROM booking WHERE bkg_to_city_id='" . $to . "')";
			$sqlCount	 .= " AND booking.bkg_id IN (SELECT booking.bkg_id FROM booking WHERE bkg_to_city_id='" . $to . "')";
		}
		if(($date1 != '' && $date1 != '') && ($date2 != '' && $date2 != ''))
		{
			$sql		 .= "  AND (booking.bkg_create_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59') ";
			$sqlCount	 .= "  AND (booking.bkg_create_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59')";
		}
		$sql .= " GROUP BY booking.bkg_id ";

		if($type == 'data')
		{
			$count			 = Yii::app()->db1->createCommand("SELECT COUNT(*) FROM ($sqlCount) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => Yii::app()->db1,
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['bkg_user_fname', 'bkg_user_id', 'bkg_booking_id', 'from_city_name', 'to_city_name', 'bkg_total_amount', 'bkg_create_date', 'bkg_pickup_date', 'bkg_return_date', 'vendor_name', 'bkg_vehicle_id', 'bkg_info_source', 'bkg_platform', 'bkg_booking_type', 'book_type', 'agent_name', 'tentative_flag', 'dzpp_surge'],
					'defaultOrder'	 => ''], 'pagination'	 => ['pageSize' => 25],
			]);

			return $dataprovider;
		}
		else if($type == 'command')
		{
			$sql .= " ORDER BY bkg_create_date DESC";
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
	}

	public function agentAccountsDashboard($from = '', $to = '', $search = '', $agentId, $userId = '', $status = '', $type = false, $arr)
	{
		$sql = "SELECT booking.bkg_booking_id, booking.bkg_id, booking.bkg_agent_ref_code, booking.bkg_create_date, booking.bkg_pickup_date, booking_invoice.bkg_base_amount, booking_invoice.bkg_additional_charge, booking_invoice.bkg_driver_allowance_amount,
                booking_invoice.bkg_toll_tax, booking_invoice.bkg_state_tax,booking_invoice.bkg_airport_entry_fee, booking_invoice.bkg_service_tax, booking_invoice.bkg_total_amount, booking_invoice.bkg_agent_markup, booking_invoice.bkg_advance_amount, booking_invoice.bkg_refund_amount, booking.bkg_status, '' as remarks, IF(booking_pref.bkg_settled_flag = 1, 'Yes', 'No') as settled, bkg_settled_flag,
                (CASE
                    WHEN (booking.bkg_status = 2) THEN 'Pending' WHEN (booking.bkg_status = 3) THEN 'Pending' WHEN (booking.bkg_status = 5) THEN 'Pending'
                    WHEN (booking.bkg_status = 6) THEN 'Completed' WHEN (booking.bkg_status = 7) THEN 'Completed' WHEN (booking.bkg_status = 9) THEN 'Cancelled'
                END
                ) as status, bkg_extra_km, bkg_extra_total_km, bkg_extra_km_charge, bkg_discount_amount, round(bkg_partner_commission/1.18) as bkg_partner_commission, round(bkg_partner_commission - bkg_partner_commission/1.18) as commissionGst, bkg_partner_extra_commission, bkg_extra_min, bkg_extra_total_min_charge
                FROM `booking`
				 JOIN `booking_invoice` ON booking.bkg_id=booking_invoice.biv_bkg_id
				 JOIN `booking_pref` ON booking.bkg_id=booking_pref.bpr_bkg_id
                WHERE booking.bkg_active = 1 AND booking.bkg_agent_id = " . $agentId . "";

		if($from != '' && $to != '')
		{
			$sql .= " AND booking.bkg_from_city_id = {$from} AND booking.bkg_to_city_id = {$to}";
		}
		if($from != '' && $to == '')
		{
			$sql .= " AND booking.bkg_from_city_id = {$from} ";
		}
		if($to != '' && $from == '')
		{
			$sql .= " AND booking.bkg_to_city_id = {$to}";
		}
		if(isset($search) && $search != '')
		{
			$fields		 = ['bkg_booking_id', 'bkg_id', 'bkg_agent_ref_code'];
			$arrSearch	 = array_filter(explode(" ", $search));
			$search1	 = [];
			foreach($arrSearch as $val)
			{
				$arr = [];
				foreach($fields as $field)
				{
					$arr[] = "$field LIKE '%{$val}%'";
				}
				$search1[] = "(" . implode(' OR ', $arr) . ")";
			}
			$sql .= " AND " . implode(" AND ", $search1);
		}
		if($status != '')
		{
			if($status == 1)
			{
				$sql .= " AND booking.bkg_status IN (2,3,5)";
			}
			if($status == 2)
			{
				$sql .= " AND booking.bkg_status IN (6,7)";
			}
			if($status == 3)
			{
				$sql .= " AND booking.bkg_status IN (9)";
			}
		}
		else
		{
			$sql .= " AND booking.bkg_status IN (2,3,5,6,7,9)";
		}
		if($arr['bkg_create_date1'] != "" && $arr['bkg_create_date2'] != "")
		{
			$sql .= " AND (DATE(bkg_create_date) BETWEEN '{$arr['bkg_create_date1']}' AND '{$arr['bkg_create_date2']}' )";
		}
		if($arr['bkg_pickup_date1'] != "" && $arr['bkg_pickup_date2'] != "")
		{
			$sql .= " AND (DATE(bkg_pickup_date) BETWEEN '{$arr['bkg_pickup_date1']}' AND '{$arr['bkg_pickup_date2']}' )";
		}
		if($type == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['bkg_discount_amount', 'bkg_extra_km', 'bkg_extra_km_charge', 'bkg_booking_id', 'bkg_agent_ref_code', 'bkg_total_amount', 'bkg_create_date', 'bkg_pickup_date', 'bkg_base_amount', 'bkg_additional_charge', 'bkg_driver_allowance_amount', 'bkg_toll_tax', 'bkg_state_tax', 'bkg_service_tax', 'bkg_airport_entry_fee', 'bkg_agent_markup', 'bkg_advance_amount', 'bkg_extra_min', 'bkg_extra_total_min_charge', 'bkg_refund_amount', 'status', 'settled'],
					'defaultOrder'	 => 'bkg_create_date DESC'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			$sql .= " ORDER BY bkg_create_date DESC";
			return DBUtil::query($sql);
		}
	}

	public function pickupReport($date1 = '', $date2 = '', $from = '', $to = '', $vendor = '', $platform = '', $status = '', $bkgType = '', $type = 'data', $serviceTire = '')
	{
		$randomNumber	 = rand();
		$tempTable		 = "AccountingPartner$randomNumber";

		DBUtil::dropTempTable($tempTable, DBUtil::SDB3());

		$sqlCreate = "(INDEX my_index_name (temp_bkg_id))
                          SELECT bkg.bkg_id AS temp_bkg_id,
                            SUM(adt1.adt_amount) AS partnerPayableAmount,
                           SUM(IF(adt1.adt_ledger_id IN (35),adt1.adt_amount,0)) as adtCommission,
                           SUM(IF(adt1.adt_ledger_id IN (26,49),adt1.adt_amount,0)) as adtPartnerWallet
                          FROM account_transactions act
                          INNER JOIN account_trans_details adt ON act.act_id = adt.adt_trans_id  AND adt.adt_active=1 AND adt.adt_ledger_id=13
                          INNER JOIN account_trans_details adt1 ON act.act_id = adt1.adt_trans_id  AND adt1.adt_active=1 AND adt1.adt_ledger_id IN(15,26,49,35)
                          INNER JOIN booking bkg ON bkg.bkg_id=adt.adt_trans_ref_id
                            WHERE act.act_active=1 AND (act.act_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59')
                          GROUP BY bkg.bkg_id ";

		DBUtil::createTempTable($tempTable, $sqlCreate, DBUtil::SDB3());

		$sqlData = "SELECT bkg.bkg_id,bkg.bkg_trip_distance,bcb.bcb_vendor_amount,
       booking_invoice.bkg_due_amount,booking_invoice.bkg_extra_km,booking_invoice.bkg_extra_per_min_charge,booking_invoice.bkg_extra_min,
       bkg.bkg_booking_id,
	   booking_user.bkg_user_fname,
       booking_invoice.bkg_advance_amount,
	   booking_invoice.bkg_convenience_charge,
	   booking_user.bkg_user_lname,
	   booking_user.bkg_country_code,
	   booking_user.bkg_contact_no,
	   booking_user.bkg_user_email,
       bkg.bkg_status,
       booking_invoice.biv_id,
       booking_invoice.bkg_total_amount,
       booking_invoice.bkg_vendor_amount,
       booking_invoice.bkg_base_amount,
       booking_invoice.bkg_discount_amount,
	   booking_invoice.bkg_extra_discount_amount,
       booking_invoice.bkg_refund_amount,
       booking_invoice.bkg_credits_used,
       booking_invoice.bkg_net_advance_amount - booking_invoice.bkg_credits_used as cancelCharge,
       booking_invoice.bkg_additional_charge,
       booking_invoice.bkg_cancel_charge,
       booking_invoice.bkg_parking_charge,
       booking_invoice.bkg_extra_km_charge,
	   booking_invoice.bkg_partner_commission,
	   booking_invoice.bkg_partner_extra_commission,
       booking_invoice.bkg_corporate_credit,
       booking_invoice.bkg_vendor_collected,
       bkg_cancel_rule_id,
       round(booking_invoice.bkg_toll_tax)
          bkg_toll_tax,
       round(booking_invoice.bkg_state_tax)
          bkg_state_tax,
       vct.vct_label
          AS serviceClass,
		  scv.scv_label AS serviceTire,
       round(IFNULL(booking_invoice.bkg_extra_toll_tax, 0))
          bkg_extra_toll_tax,
       round(IFNULL(booking_invoice.bkg_extra_state_tax, 0))
          bkg_extra_state_tax,
       round(
          (  booking_invoice.bkg_toll_tax
           + IFNULL(booking_invoice.bkg_extra_toll_tax, 0)))
          total_toll_tax,
       round(
          (  booking_invoice.bkg_state_tax
           + IFNULL(booking_invoice.bkg_extra_state_tax, 0)))
          total_state_tax,
       booking_invoice.bkg_service_tax,
       booking_invoice.bkg_airport_entry_fee,
       booking_trail.bkg_platform,
       bkg.bkg_create_date,
       bkg.bkg_pickup_date,
       bkg.bkg_return_date,
       bkg.bkg_booking_type,
       booking_add_info.bkg_info_source,
       agt.vnd_name
          AS vendor_name,
       drv.drv_name
          AS drv_name,
       booking_invoice.bkg_driver_allowance_amount
          AS drv_allowance,
       booking_user.bkg_user_id,
       bkg.bkg_agent_ref_code,
	   bkg.bkg_agent_id,
       CONCAT(fromCity.cty_name, ' - ', toCity.cty_name)
          AS cities,
       fromCity.cty_name
          AS fromCity,
       toCity.cty_name
          AS toCity,
       (if(bkg.bkg_status IN (2, 3), vct.vct_desc, vht.vht_model))
          vht_model,
       vhc.vhc_number,
       bkg_pickup_address,
       bkg_drop_address,
       cttphn.phn_phone_no
          AS vnd_phone,
       sourceZone,
       destinationZone,
       cnr.cnr_reason,
       cnr.cnr_admin_text
          AS cancel_remarks,
       (CASE
           WHEN (stt_zone = '1') THEN 'North'
           WHEN (stt_zone = '2') THEN 'West'
           WHEN (stt_zone = '3') THEN 'Central'
           WHEN (stt_zone = '4') THEN 'South'
           WHEN (stt_zone = '5') THEN 'East'
           WHEN (stt_zone = '6') THEN 'North East'
        END)
          AS region,
          btr_cancel_date  cancellation_datetime,

       IF(bkg.bkg_agent_id > 0, 'B2B', 'B2C')
          AS book_type,
       IF(agt_company IS NOT NULL AND agt_company != '',
          agt_company,
          CONCAT(agents.agt_fname, ' ', agents.agt_lname))
          AS agent_name,
       (CASE
           WHEN (booking_pref.bkg_tentative_booking = '0')
           THEN
              'Not Tentative'
           WHEN (booking_pref.bkg_tentative_booking = '1')
           THEN
              'Tentative'
           WHEN (booking_pref.bkg_tentative_booking = '2')
           THEN
              'Was Tentative'
        END)
          AS tentative_flag,
		  booking_invoice.bkg_extra_km_charge,
			booking_invoice.bkg_parking_charge,
			  agt_company    partnerName,
			booking_invoice.bkg_partner_commission,
			booking_invoice.bkg_partner_extra_commission,
		 $tempTable.adtCommission,$tempTable.adtPartnerWallet,
		  '' AS payment_mode,
           bkg_agent_id,
           $tempTable.partnerPayableAmount,
                '' AS TotalVendorAssignedCount,
               '' AS LVendorAssignmentDate,
               '' AS FVendorAssignmentDate,
               '' AS LVendorAmount,
               '' AS FVendorAmount,
               '' AS LVendorID,
               '' AS FVendorID,
               bpc.bkg_dzpp_surge_factor AS dzpp_surge
                FROM `booking` bkg
                LEFT JOIN $tempTable ON $tempTable.temp_bkg_id=bkg.bkg_id
                INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id=bkg.bkg_id AND bkg.bkg_active=1 AND bkg.bkg_status IN (2,3,5,6,7,9)
                INNER JOIN booking_user ON booking_user.bui_bkg_id=bkg.bkg_id
                INNER JOIN booking_trail ON booking_trail.btr_bkg_id=bkg.bkg_id
                INNER JOIN booking_add_info ON booking_add_info.bad_bkg_id=bkg.bkg_id
                INNER JOIN booking_pref ON booking_pref.bpr_bkg_id=bkg.bkg_id
                INNER JOIN cities fromCity ON bkg.bkg_from_city_id=fromCity.cty_id
                INNER  JOIN cities toCity ON bkg.bkg_to_city_id=toCity.cty_id
                INNER  JOIN booking_cab bcb ON bcb.bcb_id=bkg.bkg_bcb_id AND bcb_active=1
                INNER JOIN svc_class_vhc_cat scv ON scv.scv_id=bkg.bkg_vehicle_type_id
                INNER JOIN vehicle_category vct ON vct.vct_id=scv.scv_vct_id AND vct.vct_active>0
                INNER JOIN service_class sc ON scv.scv_scc_id = sc.scc_id
                INNER JOIN booking_price_factor bpc ON bpc.bpf_bkg_id  = bkg.bkg_id
                LEFT JOIN vendors agt ON agt.vnd_id=bcb.bcb_vendor_id AND vnd_active>0
                LEFT JOIN contact_phone cttphn ON cttphn.phn_contact_id=agt.vnd_contact_id and (cttphn.phn_is_primary=1) and cttphn.phn_active=1
                LEFT JOIN `vehicles` vhc ON vhc.vhc_id=bcb.bcb_cab_id
                LEFT JOIN `vehicle_types` vht ON vht.vht_id=vhc.vhc_type_id
                LEFT JOIN `drivers` drv ON drv.drv_id=bcb.bcb_driver_id AND drv.drv_active>0
                LEFT JOIN `agents` ON agents.agt_id=bkg.bkg_agent_id
                LEFT JOIN `cancel_reasons` cnr ON cnr.cnr_id=bkg.bkg_cancel_id AND bkg.bkg_status  = 9
                LEFT JOIN (
                        SELECT zones.zon_name as sourceZone,zone_cities.zct_cty_id
                        FROM `zone_cities`
                        INNER JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id
						GROUP BY zone_cities.zct_cty_id
                    ) zon1 ON zon1.zct_cty_id=bkg.bkg_from_city_id
                    LEFT JOIN (
                        SELECT zones.zon_name as destinationZone,zone_cities.zct_cty_id
                        FROM `zone_cities`
                        INNER JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id
						GROUP BY zone_cities.zct_cty_id
                    ) zon2 ON zon2.zct_cty_id=bkg.bkg_to_city_id
                    LEFT JOIN(
                            SELECT states.stt_name as stateName,cities.cty_id,states.stt_zone
                            FROM `states`
                            INNER JOIN `cities` ON cities.cty_state_id=states.stt_id
                            GROUP BY cities.cty_id
                    ) state1 ON state1.cty_id=bkg.bkg_from_city_id
                    WHERE 1 ";

		$sqlCount = "SELECT bkg.bkg_id
                    FROM `booking` bkg
                    INNER JOIN booking_trail ON btr_bkg_id=bkg.bkg_id and  bkg.bkg_active=1	AND bkg.bkg_status IN (2,3,5,6,7,9)
                    INNER JOIN booking_cab bcb ON bcb.bcb_id=bkg.bkg_bcb_id AND bcb_active=1
                    INNER JOIN booking_price_factor bpc ON bpc.bpf_bkg_id  = bkg.bkg_id
					INNER JOIN svc_class_vhc_cat scv ON scv.scv_id=bkg.bkg_vehicle_type_id
                    WHERE 1 ";

		if(isset($vendor) && $vendor != '')
		{
			$sql .= " AND bcb.bcb_vendor_id='" . $vendor . "'";
		}
		if(isset($status) && $status > 0)
		{
			$sql .= " AND bkg.bkg_status='" . $status . "'";
		}
		if(($date1 != '' && $date1 != '') && ($date2 != '' && $date2 != ''))
		{
			$sql .= " AND (bkg.bkg_pickup_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59') ";
		}
		if(isset($platform) && $platform != '')
		{
			$sql .= " AND booking_trail.bkg_platform='" . $platform . "'";
		}
		if($this->bkg_agent_id != '')
		{
			$sql .= " AND bkg.bkg_agent_id=$this->bkg_agent_id";
		}
		if((isset($from) && $from != '') && (isset($to) && $to != ''))
		{
			$sql .= " AND bkg.bkg_id IN (SELECT booking.bkg_id FROM booking WHERE bkg_from_city_id='" . $from . "' AND bkg_to_city_id='" . $to . "')";
		}
		if((isset($from) && $from != ''))
		{
			$sql .= " AND bkg.bkg_id IN (SELECT booking.bkg_id FROM booking WHERE bkg_from_city_id='" . $from . "')";
		}
		if((isset($to) && $to != ''))
		{
			$sql .= " AND bkg.bkg_id IN (SELECT booking.bkg_id FROM booking WHERE bkg_to_city_id='" . $to . "')";
		}
		if(count($bkgType) > 0)
		{
			$bkgTypeStr	 = implode(",", $bkgType);
			$sql		 .= " AND bkg_booking_type IN ($bkgTypeStr) ";
		}
		if(count($serviceTire) > 0)
		{
			$svcType = implode(",", $serviceTire);
			$sql	 .= " AND scv.scv_scc_id IN ($svcType)";
		}

		$sql		 .= " GROUP BY bkg.bkg_id ";
		$sqlQuery	 = $sqlData . $sql;
		$sqlCount	 = $sqlCount . $sql;
		if($type == 'data')
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) TEMP ", DBUtil::SDB3())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sqlQuery, [
				'db'			 => DBUtil::SDB3(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['bkg_user_name', 'bkg_user_id', 'bkg_booking_id', 'from_city_name', 'bkg_vnd_name', 'to_city_name', 'bkg_total_amount', 'bkg_create_date', 'bkg_pickup_date', 'bkg_return_date', 'vendor_name', 'drv_name', 'drv_allowance', 'vhc_number', 'bkg_pickup_address', 'bkg_drop_address', 'fromCity', 'toCity', 'bkg_advance_amount', 'bkg_due_amount', 'bkg_platform', 'book_type', 'agent_name', 'tentative_flag', 'dzpp_surge'],
					'defaultOrder'	 => 'bkg_pickup_date DESC'], 'pagination'	 => ['pageSize' => 25],
			]);
			return $dataprovider;
		}
		else if($type == 'command')
		{
			$sqlQuery	 .= " ORDER BY bkg.bkg_pickup_date DESC";
			$recordset	 = DBUtil::query($sqlQuery, DBUtil::SDB3());
			return $recordset;
		}
	}

	public function pickupGeneralReport($date1 = '', $date2 = '', $from = '', $to = '', $vendor = '', $platform = '', $status = '', $type = 'data', $bkgTripId = '', $bkgType = '')
	{
		$randomNumber	 = rand();
		$tempTable		 = "AccountingPartner$randomNumber";

		DBUtil::dropTempTable($tempTable, DBUtil::SDB3());

		$sqlCreate = "(INDEX my_index_name (temp_bkg_id))
                          SELECT bkg.bkg_id AS temp_bkg_id,
                            SUM(adt1.adt_amount) AS partnerPayableAmount,
                           SUM(IF(adt1.adt_ledger_id IN (35),adt1.adt_amount,0)) as adtCommission,
                           SUM(IF(adt1.adt_ledger_id IN (26,49),adt1.adt_amount,0)) as adtPartnerWallet
                          FROM account_transactions act
                          INNER JOIN account_trans_details adt ON act.act_id = adt.adt_trans_id  AND adt.adt_active=1 AND adt.adt_ledger_id=13
                          INNER JOIN account_trans_details adt1 ON act.act_id = adt1.adt_trans_id  AND adt1.adt_active=1 AND adt1.adt_ledger_id IN(15,26,49,35)
                          INNER JOIN booking bkg ON bkg.bkg_id=adt.adt_trans_ref_id
                            WHERE act.act_active=1 AND (act.act_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59')
                          GROUP BY bkg.bkg_id ";

		DBUtil::createTempTable($tempTable, $sqlCreate, DBUtil::SDB3());

		$sqlData = "SELECT bkg.bkg_id,bkg_trip_distance,bcb.bcb_vendor_amount,
       booking_invoice.bkg_due_amount,booking_invoice.bkg_extra_km,booking_invoice.bkg_extra_total_min_charge,booking_invoice.bkg_extra_min,
       bkg.bkg_booking_id,
	   booking_user.bkg_user_fname,
       booking_invoice.bkg_advance_amount,
	   booking_invoice.bkg_convenience_charge,
	   booking_user.bkg_user_lname,
	   booking_user.bkg_country_code,
	   booking_user.bkg_contact_no,
	   booking_user.bkg_user_email,
       bkg.bkg_status,
       booking_invoice.biv_id,
       booking_invoice.bkg_total_amount,
       booking_invoice.bkg_vendor_amount,
       booking_invoice.bkg_base_amount,
       booking_invoice.bkg_discount_amount,
	   booking_invoice.bkg_extra_discount_amount,
       booking_invoice.bkg_refund_amount,
       booking_invoice.bkg_credits_used,
       booking_invoice.bkg_net_advance_amount - booking_invoice.bkg_credits_used as cancelCharge,
       booking_invoice.bkg_additional_charge,
       booking_invoice.bkg_cancel_charge,
       booking_invoice.bkg_parking_charge,
       booking_invoice.bkg_extra_km_charge,
	   booking_invoice.bkg_partner_commission,
	   booking_invoice.bkg_partner_extra_commission,
       booking_invoice.bkg_corporate_credit,
       booking_invoice.bkg_vendor_collected,
	   btk.bkg_is_no_show,
	   btk.bkg_ride_start,
	   btk.bkg_ride_complete,
	   btk.bkg_no_show_time,
	   btk.bkg_trip_arrive_time,
	   btk.bkg_trip_start_time,
	   btk.bkg_trip_end_time,
	   btk.bkg_arrived_for_pickup,
       bkg_cancel_rule_id,
       round(booking_invoice.bkg_toll_tax)
          bkg_toll_tax,
       round(booking_invoice.bkg_state_tax)
          bkg_state_tax,
       vct.vct_label
          AS serviceClass,
       round(IFNULL(booking_invoice.bkg_extra_toll_tax, 0))
          bkg_extra_toll_tax,
       round(IFNULL(booking_invoice.bkg_extra_state_tax, 0))
          bkg_extra_state_tax,
       round(
          (  booking_invoice.bkg_toll_tax
           + IFNULL(booking_invoice.bkg_extra_toll_tax, 0)))
          total_toll_tax,
       round(
          (  booking_invoice.bkg_state_tax
           + IFNULL(booking_invoice.bkg_extra_state_tax, 0)))
          total_state_tax,
       booking_invoice.bkg_service_tax,
       booking_invoice.bkg_airport_entry_fee,
       booking_trail.bkg_platform,
       bkg.bkg_create_date,
       bkg.bkg_pickup_date,
       bkg.bkg_return_date,
       bkg.bkg_booking_type,
       agt.vnd_name
          AS vendor_name,
	   drv.drv_name
          AS driver_name,
       booking_invoice.bkg_driver_allowance_amount
          AS drv_allowance,
       booking_user.bkg_user_id,
       bkg.bkg_agent_ref_code,
       fromCity.cty_name
          AS fromCity,
       toCity.cty_name
          AS toCity,
       vhc.vhc_number,
       bkg_pickup_address,
       bkg_drop_address,
       sourceZone,
       destinationZone,
       cnr.cnr_reason,
       cnr.cnr_admin_text
          AS cancel_remarks,
          btr_cancel_date  cancellation_datetime,
       IF(bkg.bkg_agent_id > 0, 'B2B', 'B2C')
          AS book_type,
       IF(agt_company IS NOT NULL AND agt_company != '',
          agt_company,
          CONCAT(agents.agt_fname, ' ', agents.agt_lname))
          AS agent_name,
		  booking_invoice.bkg_extra_km_charge,
			booking_invoice.bkg_parking_charge,
			booking_invoice.bkg_partner_extra_commission, 
			  agt_company    partnerName,
                         booking_invoice.bkg_partner_commission,
		 $tempTable.adtCommission,$tempTable.adtPartnerWallet,
		  '' AS payment_mode,
           bkg_agent_id,
           $tempTable.partnerPayableAmount,
                '' AS TotalVendorAssignedCount,
               '' AS LVendorAssignmentDate,
               '' AS FVendorAssignmentDate,
               '' AS LVendorAmount,
               '' AS FVendorAmount,
               '' AS LVendorID,
               '' AS FVendorID
                FROM `booking` bkg
                LEFT JOIN $tempTable ON $tempTable.temp_bkg_id=bkg.bkg_id
                INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id=bkg.bkg_id AND bkg.bkg_active=1 AND bkg.bkg_status IN (2,3,5,6,7,9)
                INNER JOIN booking_user ON booking_user.bui_bkg_id=bkg.bkg_id
                INNER JOIN booking_trail ON booking_trail.btr_bkg_id=bkg.bkg_id
                INNER JOIN booking_pref ON booking_pref.bpr_bkg_id=bkg.bkg_id
                INNER JOIN cities fromCity ON bkg.bkg_from_city_id=fromCity.cty_id
                INNER  JOIN cities toCity ON bkg.bkg_to_city_id=toCity.cty_id
                INNER  JOIN booking_cab bcb ON bcb.bcb_id=bkg.bkg_bcb_id AND bcb_active=1
                INNER JOIN svc_class_vhc_cat scv ON scv.scv_id=bkg.bkg_vehicle_type_id
                INNER JOIN vehicle_category vct ON vct.vct_id=scv.scv_vct_id AND vct.vct_active>0
                INNER JOIN service_class sc ON scv.scv_scc_id = sc.scc_id
                INNER JOIN booking_track btk ON btk.btk_bkg_id = bkg.bkg_id
                LEFT JOIN vendors agt ON agt.vnd_id=bcb.bcb_vendor_id 
				LEFT JOIN drivers drv ON drv.drv_id=bcb.bcb_driver_id 
                LEFT JOIN `vehicles` vhc ON vhc.vhc_id=bcb.bcb_cab_id
                LEFT JOIN `vehicle_types` vht ON vht.vht_id=vhc.vhc_type_id
                LEFT JOIN `agents` ON agents.agt_id=bkg.bkg_agent_id
                LEFT JOIN `cancel_reasons` cnr ON cnr.cnr_id=bkg.bkg_cancel_id AND bkg.bkg_status  = 9
                LEFT JOIN (
                        SELECT zones.zon_name as sourceZone,zone_cities.zct_cty_id
                        FROM `zone_cities`
                        INNER JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id
						GROUP BY zone_cities.zct_cty_id
                    ) zon1 ON zon1.zct_cty_id=bkg.bkg_from_city_id
                    LEFT JOIN (
                        SELECT zones.zon_name as destinationZone,zone_cities.zct_cty_id
                        FROM `zone_cities`
                        INNER JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id
						GROUP BY zone_cities.zct_cty_id
                    ) zon2 ON zon2.zct_cty_id=bkg.bkg_to_city_id
                    LEFT JOIN(
                            SELECT states.stt_name as stateName,cities.cty_id,states.stt_zone
                            FROM `states`
                            INNER JOIN `cities` ON cities.cty_state_id=states.stt_id
                            GROUP BY cities.cty_id
                    ) state1 ON state1.cty_id=bkg.bkg_from_city_id
                    WHERE 1 ";

		$sqlCount = "SELECT bkg.bkg_id
                    FROM `booking` bkg
                    INNER JOIN booking_trail ON btr_bkg_id=bkg.bkg_id and  bkg.bkg_active=1	AND bkg.bkg_status IN (2,3,5,6,7,9)
                    INNER JOIN booking_cab bcb ON bcb.bcb_id=bkg.bkg_bcb_id AND bcb_active=1
                    WHERE 1 ";

		if(isset($vendor) && $vendor != '')
		{
			$sql .= " AND bcb.bcb_vendor_id='" . $vendor . "'";
		}
		if(isset($status) && $status != '')
		{
			$sql .= " AND bkg.bkg_status IN($status)";
		}
		if(($date1 != '' && $date1 != '') && ($date2 != '' && $date2 != ''))
		{
			$sql .= " AND (bkg.bkg_pickup_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59') ";
		}
		if(isset($platform) && $platform != '')
		{
			$sql .= " AND booking_trail.bkg_platform='" . $platform . "'";
		}
		if($this->bkg_agent_id != '')
		{
			$sql .= " AND bkg.bkg_agent_id=$this->bkg_agent_id";
		}
		if((isset($from) && $from != '') && (isset($to) && $to != ''))
		{
			$sql .= " AND bkg.bkg_id IN (SELECT booking.bkg_id FROM booking WHERE bkg_from_city_id='" . $from . "' AND bkg_to_city_id='" . $to . "')";
		}
		if((isset($from) && $from != ''))
		{
			$sql .= " AND bkg.bkg_id IN (SELECT booking.bkg_id FROM booking WHERE bkg_from_city_id='" . $from . "')";
		}
		if((isset($to) && $to != ''))
		{
			$sql .= " AND bkg.bkg_id IN (SELECT booking.bkg_id FROM booking WHERE bkg_to_city_id='" . $to . "')";
		}
		if(isset($bkgTripId) && $bkgTripId != '')
		{
			$sql .= " AND (  bkg_booking_id LIKE '%$bkgTripId%' OR  bkg_bcb_id LIKE '%$bkgTripId%'  OR bkg_agent_ref_code LIKE '%$bkgTripId%' )";
		}
		if(count($bkgType) > 0)
		{
			$bkgTypeStr	 = implode(",", $bkgType);
			$sql		 .= " AND bkg_booking_type IN ($bkgTypeStr) ";
		}
		$sql		 .= " GROUP BY bkg.bkg_id ";
		$sqlQuery	 = $sqlData . $sql;
		$sqlCount	 = $sqlCount . $sql;
		if($type == 'data')
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) TEMP ", DBUtil::SDB3())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sqlQuery, [
				'db'			 => DBUtil::SDB3(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['bkg_booking_id'],
					'defaultOrder'	 => 'bkg_pickup_date DESC'], 'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else if($type == 'command')
		{
			$sqlQuery	 .= " ORDER BY bkg.bkg_pickup_date DESC";
			$recordset	 = DBUtil::query($sqlQuery, DBUtil::SDB3());
			return $recordset;
		}
	}

	public function getRevenueReportHtml()
	{
		$data	 = $this->getRevenueReport();
		$html	 = "<b>Bookings :</b> (<i> By create date && status [2,3,4,5,6,7] </i>) <br/>
                 <table width='70%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr><th></th>
                    <th style='text-align:center'>Today</th>
                    <th style='text-align:center'>Today-1</th>
                    <th style='text-align:center'>Today-2</th>
                    <th style='text-align:center'>MTD</th>
                    <th style='text-align:center'>month-1</th>
                    <th style='text-align:center'>YTD</th>
                    <th style='text-align:center'>Last Year</th>
                </tr>
                <tr><th style='text-align:left'>" . "Bookings (Active+Completed)" . "</th>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_today'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_today1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_today2'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_month1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_last_year'], 2) . "</td>
                </tr>
                 <tr><th style='text-align:left'>" . "Bookings (Active)" . "</th>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_active_today'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_active_today1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_active_today2'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_active_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_active_month1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_active_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_active_last_year'], 2) . "</td>
                </tr>
                <tr><th style='text-align:left'>" . "Bookings (Completed)" . "</th>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_comp_today'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_comp_today1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_comp_today2'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_comp_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_comp_month1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_comp_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gmv_comp_last_year'], 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Vendor Amount" . "</th>
                    <td style='text-align:right'>" . number_format($data['booking_vendor_today'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_vendor_today1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_vendor_today2'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_vendor_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_vendor_month1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_vendor_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_vendor_last_year'], 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Gozo Amount<br>(w/o Svc Tax)/Gross Profit" . "</th>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_today'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_today1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_today2'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_month1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_last_year'], 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Gozo Amount (Active)<br>(w/o Svc Tax)/Gross Profit" . "</th>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_act_today'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_act_today1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_act_today2'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_act_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_act_month1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_act_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_act_last_year'], 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Gozo Amount (Completed)<br>(w/o Svc Tax)/Gross Profit" . "</th>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_comp_today'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_comp_today1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_comp_today2'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_comp_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_comp_month1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_comp_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_gozo_comp_last_year'], 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "GST" . "</th>
                    <td style='text-align:right'>" . number_format($data['booking_stax_today'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_stax_today1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_stax_today2'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_stax_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_stax_month1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_stax_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_stax_last_year'], 2) . "</td>
                </tr>
                <tr><th style='text-align:left'>" . "Advance collected" . "</th>
                    <td style='text-align:right'>" . number_format($data['booking_advance_today'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_advance_today1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_advance_today2'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_advance_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_advance_month1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_advance_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_advance_last_year'], 2) . "</td>
                </tr>
                <tr><th style='text-align:left'>" . "COD" . "</th>
                    <td style='text-align:right'>" . number_format($data['booking_cod_today'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_cod_today1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_cod_today2'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_cod_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_cod_month1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_cod_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['booking_cod_last_year'], 2) . "</td>
                </tr>
                <tr><th style='text-align:left'>" . "Collections Received" . "</th>
                    <td style='text-align:right'>" . number_format(($data['trans_today'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . number_format(($data['trans_today1'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . number_format(($data['trans_today2'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . number_format(($data['trans_mtd'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . number_format(($data['trans_month1'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . number_format(($data['trans_ytd'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . number_format(($data['trans_last_year'] * -1), 2) . "</td>
                </tr>
                <tr><th style='text-align:left'>" . "Vendor payments made" . "</th>
                    <td style='text-align:right'>" . number_format($data['ven_trans_today'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['ven_trans_today1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['ven_trans_today2'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['ven_trans_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['ven_trans_month1'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['ven_trans_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . number_format($data['ven_trans_last_year'], 2) . "</td>
                </tr>
                <tr><th style='text-align:left'>" . "Receivables Pending" . "</th>
                    <td style='text-align:right'>" . number_format($data['receive_pending'], 2) . "</td>
                    <td style='text-align:right'>NA</td>
                    <td style='text-align:right'>NA</td>
                    <td style='text-align:right'>NA</td>
                    <td style='text-align:right'>NA</td>
                    <td style='text-align:right'>NA</td>
                    <td style='text-align:right'>NA</td>
                </tr>
                </table><br/>";
		return $html;
	}

	public function getRevenueReportHtmlByPickup()
	{
		$data	 = $this->getRevenueReportByPickup();
		$html	 = "<b>Bookings pipeline :</b> (<i> By Pickup Date && status [2,3,4,5,6,7] </i>) <br/>
                    <table width='70%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                            <th></th>
                            <th style='text-align:center'>YTD (completed)</th>
                            <th style='text-align:center'>this month (completed)</th>
                            <th style='text-align:center'>this month (active)</th>
                            <th style='text-align:center'>future month (active)</th>
                    </tr>
                    <tr><th style='text-align:left'>" . "Bookings" . "</th>
                        <td style='text-align:right'>" . number_format($data['rev_ytd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['rev_mtd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['rev_mtd_active'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['rev_mtd_future'], 2) . "</td>
                    </tr>
                    <tr><th style='text-align:left'>" . "Vendor Amount" . "</th>
                        <td style='text-align:right'>" . number_format($data['vamt_ytd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['vamt_mtd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['vamt_mtd_active'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['vamt_mtd_future'], 2) . "</td>
                    </tr>
                    <tr><th style='text-align:left'>" . "Gozo Amount<br>(w/o Svc Tax)/Gross Profit" . "</th>
                        <td style='text-align:right'>" . number_format($data['gamt_ytd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gamt_mtd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gamt_mtd_active'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gamt_mtd_future'], 2) . "</td>
                    </tr>
                    <tr><th style='text-align:left'>" . "GST" . "</th>
                        <td style='text-align:right'>" . number_format($data['stax_ytd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['stax_mtd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['stax_mtd_active'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['stax_mtd_future'], 2) . "</td>
                    </tr>
                    <tr><th style='text-align:left'>" . "Advance Collected" . "</th>
                        <td style='text-align:right'>" . number_format($data['adv_ytd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['adv_mtd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['adv_mtd_active'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['adv_mtd_future'], 2) . "</td>
                    </tr>
                    <tr><th style='text-align:left'>" . "COD" . "</th>
                        <td style='text-align:right'>" . number_format($data['cod_ytd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['cod_mtd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['cod_mtd_active'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['cod_mtd_future'], 2) . "</td>
                    </tr>
                    <tr>
                     <td colspan='5'>&nbsp;</th>
                    </tr>
            </table><br>";
		return $html;
	}

	public function getDistributionByBookingTypeHtml()
	{
		$data	 = $this->getDistributionByBookingType();
		$html	 = "<b>Bookings Type distribution report :</b> (<i> By Create Date && status [2,3,4,5,6,7,9] </i>)<br/>
                 <table width='70%' border=\"1px\" style=\"border-collapse: collapse;\" cellpadding=\"5\">
                    <tr><th style='text-align:left'>Count</th>
                        <th>MTD</th>
                        <th>MONTH-1</th>
                        <th>MONTH-2</th>
                        <th>MONTH-3</th>
                        <th>YTD</th>
                        <th>YEAR-1</th>
                    </tr>";
		if(count($data) > 0)
		{
			foreach($data as $d)
			{
				$html .= "<tr>
                            <th style='text-align:left'>" . $d['booking_type'] . "</th>
                            <td style='text-align:right'>" . $d['booking_count_mtd'] . "</td>
                            <td style='text-align:right'>" . $d['booking_count_month1'] . "</td>
                            <td style='text-align:right'>" . $d['booking_count_month2'] . "</td>
                            <td style='text-align:right'>" . $d['booking_count_month3'] . "</td>
                            <td style='text-align:right'>" . $d['booking_count_ytd'] . "</td>
                            <td style='text-align:right'>" . $d['booking_count_year1'] . "</td>
                        </tr>";
			}
		}
		$html .= "<tr><th style='text-align:left'>Rs</th>
                        <th>MTD</th>
                        <th>MONTH-1</th>
                        <th>MONTH-2</th>
                        <th>MONTH-3</th>
                        <th>YTD</th>
                        <th>YEAR-1</th>
                    </tr>";
		if(count($data) > 0)
		{
			foreach($data as $d)
			{
				$html .= "<tr>
                            <th style='text-align:left'>" . $d['booking_type'] . "</th>
                            <td style='text-align:right'>" . $d['booking_total_mtd'] . "</td>
                            <td style='text-align:right'>" . $d['booking_total_month1'] . "</td>
                            <td style='text-align:right'>" . $d['booking_total_month2'] . "</td>
                            <td style='text-align:right'>" . $d['booking_total_month3'] . "</td>
                            <td style='text-align:right'>" . $d['booking_total_ytd'] . "</td>
                            <td style='text-align:right'>" . $d['booking_total_year1'] . "</td>
                        </tr>";
			}
		}
		$html .= "<tr><th style='text-align:left'>Gozo amount w/o GST</th>
                        <th>MTD</th>
                        <th>MONTH-1</th>
                        <th>MONTH-2</th>
                        <th>MONTH-3</th>
                        <th>YTD</th>
                        <th>YEAR-1</th>
                    </tr>";
		if(count($data) > 0)
		{
			foreach($data as $d)
			{
				$html .= "<tr>
                            <th style='text-align:left'>" . $d['booking_type'] . "</th>
                            <td style='text-align:right'>" . $d['booking_gamt_mtd'] . "</td>
                            <td style='text-align:right'>" . $d['booking_gamt_month1'] . "</td>
                            <td style='text-align:right'>" . $d['booking_gamt_month2'] . "</td>
                            <td style='text-align:right'>" . $d['booking_gamt_month3'] . "</td>
                            <td style='text-align:right'>" . $d['booking_gamt_ytd'] . "</td>
                            <td style='text-align:right'>" . $d['booking_gamt_year1'] . "</td>
                        </tr>";
			}
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getPLTrendReportHtml()
	{
		$month		 = date("m", strtotime(date('Y-m-d')));
		$month1		 = date("m", strtotime(" -1 months"));
		$month2		 = date("m", strtotime(" -2 months"));
		$monthAvg	 = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
		$month1Avg	 = cal_days_in_month(CAL_GREGORIAN, $month1, date('Y'));
		$month2Avg	 = cal_days_in_month(CAL_GREGORIAN, $month2, date('Y'));

		$yearFromDate	 = date("Y-01-01");
		$monthFromDate	 = date("Y-m-01");
		$toDate			 = date("Y-m-d");
		$filterObj		 = new Filter();
		$MTDDays		 = ($filterObj->dateCount($monthFromDate, $toDate) + 1);
		$YTDDays		 = ($filterObj->dateCount($yearFromDate, $toDate) + 1);

		$data	 = $this->getPLTrendReport();
		$html	 = "<b>P&L Trend Report :</b> (<i> By create date </i>)<br/>
                <table width='90%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                <tr>
                    <th></th>
                    <th>Lifetime</th>
                    <th>YTD</th>
                    <th>Month–2</th>
                    <th>Month–1</th>
                    <th>Month to Date</th>
                    <th>Today-3</th>
                    <th>Today-2</th>
                    <th>Today-1</th>
                    <th>Today</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>Avg/day Month-2</th>
                    <th>Avg/day month-1</th>
                    <th>Avg/day YTD</th>
                    <th>Avg/day MTD</th>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Bookings (Active+Completed)" . "</th>
                    <td style='text-align:right'>" . round($data['booking_gmv_lifetime'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_month2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_month1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_today3'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_today2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_today1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_today'], 2) . "</td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'>" . round(($data['booking_gmv_month2'] / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gmv_month1'] / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gmv_ytd'] / $YTDDays), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gmv_mtd'] / $MTDDays), 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Bookings (Active)" . "</th>
                    <td style='text-align:right'>" . round($data['booking_gmv_active_lifetime'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_active_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_active_month2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_active_month1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_active_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_active_today3'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_active_today2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_active_today1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_active_today'], 2) . "</td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'>" . round(($data['booking_gmv_active_month2'] / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gmv_active_month1'] / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gmv_active_ytd'] / $YTDDays), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gmv_active_mtd'] / $MTDDays), 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Bookings (Completed)" . "</th>
                    <td style='text-align:right'>" . round($data['booking_gmv_comp_lifetime'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_comp_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_comp_month2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_comp_month1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_comp_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_comp_today3'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_comp_today2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_comp_today1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gmv_comp_today'], 2) . "</td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'>" . round(($data['booking_gmv_comp_month2'] / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gmv_comp_month1'] / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gmv_comp_ytd'] / $YTDDays), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gmv_comp_mtd'] / $MTDDays), 2) . "</td>
                </tr>


                <tr>
                    <th style='text-align:left'>" . "Vendor Amount" . "</th>
                    <td style='text-align:right'>" . round($data['booking_vamt_lifetime'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_vamt_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_vamt_month2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_vamt_month1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_vamt_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_vamt_today3'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_vamt_today2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_vamt_today1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_vamt_today'], 2) . "</td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'>" . round(($data['booking_vamt_month2'] / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_vamt_month1'] / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_vamt_ytd'] / $YTDDays), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_vamt_mtd'] / $MTDDays), 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Gozo Amount<br>(Incl. GST)" . "</th>
                    <td style='text-align:right'>" . round($data['booking_gamt_lifetime'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gamt_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gamt_month2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gamt_month1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gamt_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gamt_today3'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gamt_today2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gamt_today1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gamt_today'], 2) . "</td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'>" . round(($data['booking_gamt_month2'] / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gamt_month1'] / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gamt_ytd'] / $YTDDays), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gamt_mtd'] / $MTDDays), 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "GST" . "</th>
                    <td style='text-align:right'>" . round($data['booking_stax_lifetime'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_stax_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_stax_month2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_stax_month1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_stax_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_stax_today3'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_stax_today2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_stax_today1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_stax_today'], 2) . "</td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'>" . round(($data['booking_stax_month2'] / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_stax_month1'] / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_stax_ytd'] / $YTDDays), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_stax_mtd'] / $MTDDays), 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Gozo Amount<br>(excl Svc Tax)/Gross Profit" . "</th>
                    <td style='text-align:right'>" . round($data['booking_gproft_lifetime'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gprofit_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gprofit_month2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gprofit_month1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gprofit_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gprofit_today3'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gprofit_today2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gprofit_today1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_gprofit_today'], 2) . "</td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'>" . round(($data['booking_gprofit_month2'] / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gprofit_month1'] / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gprofit_ytd'] / $YTDDays), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_gprofit_mtd'] / $MTDDays), 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Not Profitable" . "</th>
                    <td style='text-align:right'>" . round($data['booking_non_profit_lifetime'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_month2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_month1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_today3'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_today2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_today1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_today'], 2) . "</td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_month2'] / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_month1'] / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_ytd'] / $YTDDays), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_mtd'] / $MTDDays), 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Total Loss From NP Bookings" . "</th>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_amt_lifetime'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_amt_ytd'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_amt_month2'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_amt_month1'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_amt_mtd'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_amt_today3'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_amt_today2'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_amt_today1'] * -1), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_amt_today'] * -1), 2) . "</td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'>" . round((($data['booking_non_profit_amt_month2'] * -1) / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . round((($data['booking_non_profit_amt_month1'] * -1) / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . round((($data['booking_non_profit_amt_ytd'] * -1) / $YTDDays), 2) . "</td>
                    <td style='text-align:right'>" . round((($data['booking_non_profit_amt_mtd'] * -1) / $MTDDays), 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Profitability Overide" . "</th>
                    <td style='text-align:right'>" . round($data['booking_non_profit_override_lifetime'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_override_ytd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_override_month2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_override_month1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_override_mtd'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_override_today3'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_override_today2'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_override_today1'], 2) . "</td>
                    <td style='text-align:right'>" . round($data['booking_non_profit_override_today'], 2) . "</td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_override_month2'] / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_override_month1'] / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_override_ytd'] / $YTDDays), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_non_profit_override_mtd'] / $MTDDays), 2) . "</td>
                </tr>
                <tr>
                    <td colspan='16'>&nbsp;</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Completed Count" . "</th>
                    <td style='text-align:right'>" . $data['booking_complete_lifetime_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_complete_ytd_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_complete_month2_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_complete_month1_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_complete_mtd_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_complete_today3_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_complete_today2_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_complete_today1_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_complete_today_count'] . "</td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'>" . round(($data['booking_complete_month2_count'] / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_complete_month1_count'] / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_complete_ytd_count'] / $YTDDays), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_complete_mtd_count'] / $MTDDays), 2) . "</td>
                </tr>
                <tr>
                    <th style='text-align:left'>" . "Pending Completion/Active" . "</th>
                    <td style='text-align:right'>" . $data['booking_incomplete_lifetime_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_incomplete_ytd_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_incomplete_month2_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_incomplete_month1_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_incomplete_mtd_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_incomplete_today3_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_incomplete_today2_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_incomplete_today1_count'] . "</td>
                    <td style='text-align:right'>" . $data['booking_incomplete_today_count'] . "</td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'>" . round(($data['booking_incomplete_month2_count'] / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_incomplete_month1_count'] / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_incomplete_ytd_count'] / $YTDDays), 2) . "</td>
                    <td style='text-align:right'>" . round(($data['booking_incomplete_mtd_count'] / $MTDDays), 2) . "</td>
                 </tr>
                </table><br/>";
		return $html;
	}

	public function getCancellationTrendReportHtml()
	{
		$month		 = date("m", strtotime(date('Y-m-d')));
		$month1		 = date("m", strtotime(" -1 months"));
		$month2		 = date("m", strtotime(" -2 months"));
		$monthAvg	 = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
		$month1Avg	 = cal_days_in_month(CAL_GREGORIAN, $month1, date('Y'));
		$month2Avg	 = cal_days_in_month(CAL_GREGORIAN, $month2, date('Y'));

		$yearFromDate	 = date("Y-01-01");
		$monthFromDate	 = date("Y-m-01");
		$toDate			 = date("Y-m-d");
		$filterObj		 = new Filter();
		$MTDDays		 = ($filterObj->dateCount($monthFromDate, $toDate) + 1);
		$YTDDays		 = ($filterObj->dateCount($yearFromDate, $toDate) + 1);

		$data	 = $this->cancellationTrendReport();
		$data2	 = $this->cancellationTrendReport2();

		$html = "<b>Cancellation Trend Report :</b> ( <i> By create date </i> )<br/>
                    <table width='90%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                        <tr>
                            <th></th>
                            <th>Lifetime</th>
                            <th>YTD</th>
                            <th>Month – 2</th>
                            <th>Month – 1</th>
                            <th>Month to Date</th>
                            <th>&nbsp;</th>
                            <th>Avg/day Month-2</th>
                            <th>Avg/day month-1</th>
                            <th>Avg/day YTD</th>
                            <th>Avg/day MTD</th>
                         </tr>
                        <tr>
                            <th style='text-align:left'>" . "Bookings Cancelled (New)" . "</th>
                            <td style='text-align:right'>" . $data['booking_cancel_new_lifetime_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_new_ytd_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_new_month2_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_new_month1_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_new_mtd_count'] . "</td>
                            <td style='text-align:right'></td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_new_month2_count'] / $month2Avg), 2) . "</td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_new_month1_count'] / $month1Avg), 2) . "</td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_new_ytd_count'] / $YTDDays), 2) . "</td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_new_mtd_count'] / $MTDDays), 2) . "</td>
                        </tr>
                        <tr>
                            <th style='text-align:left'>" . "Bookings Cancelled (Unverified)" . "</th>
                            <td style='text-align:right'>" . $data['booking_cancel_unv_lifetime_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_unv_ytd_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_unv_month2_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_unv_month1_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_unv_mtd_count'] . "</td>
                            <td style='text-align:right'></td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_unv_month2_count'] / $month2Avg), 2) . "</td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_unv_month1_count'] / $month1Avg), 2) . "</td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_unv_ytd_count'] / $YTDDays), 2) . "</td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_unv_mtd_count'] / $MTDDays), 2) . "</td>
                        </tr>
                        <tr>
                            <th style='text-align:left'>" . "Bookings Cancelled (Advance Received)" . "</th>
                            <td style='text-align:right'>" . $data['booking_cancel_adv_lifetime_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_adv_ytd_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_adv_month2_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_adv_month1_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_adv_mtd_count'] . "</td>
                            <td style='text-align:right'></td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_adv_month2_count'] / $month2Avg), 2) . "</td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_adv_month1_count'] / $month1Avg), 2) . "</td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_adv_ytd_count'] / $YTDDays), 2) . "</td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_adv_mtd_count'] / $MTDDays), 2) . "</td>
                        </tr>
                        <tr>
                            <th style='text-align:left'>" . "Bookings Cancelled (COD)" . "</th>
                            <td style='text-align:right'>" . $data['booking_cancel_cod_lifetime_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_cod_ytd_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_cod_month2_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_cod_month1_count'] . "</td>
                            <td style='text-align:right'>" . $data['booking_cancel_cod_mtd_count'] . "</td>
                            <td style='text-align:right'></td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_cod_month2_count'] / $month2Avg), 2) . "</td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_cod_month1_count'] / $month1Avg), 2) . "</td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_cod_ytd_count'] / $YTDDays), 2) . "</td>
                            <td style='text-align:right'>" . round(($data['booking_cancel_cod_mtd_count'] / $MTDDays), 2) . "</td>
                        </tr>";
		if(count($data2) > 0)
		{
			foreach($data2 as $row)
			{
				if($row['bkg_platform'] == 1)
				{
					$user = 'Created by user';
				}
				else if($row['bkg_platform'] == 2)
				{
					$user = 'Created by admin';
				}
				$today		 = number_format((100 * ($row['booking_cancel_today_count'] / $row['booking_created_today_count'])), 2);
				$today2		 = number_format((100 * ($row['booking_cancel_today2_count'] / $row['booking_created_today2_count'])), 2);
				$month1		 = number_format((100 * ($row['booking_cancel_month1_count'] / $row['booking_created_month1_count'])), 2);
				$month2		 = number_format((100 * ($row['booking_cancel_month2_count'] / $row['booking_created_month2_count'])), 2);
				$month3		 = number_format((100 * ($row['booking_cancel_month3_count'] / $row['booking_created_month3_count'])), 2);
				$mtd		 = number_format((100 * ($row['booking_cancel_mtd_count'] / $row['booking_created_mtd_count'])), 2);
				$yld		 = number_format((100 * ($row['booking_cancel_ytd_count'] / $row['booking_created_ytd_count'])), 2);
				$lastYear	 = number_format((100 * ($row['booking_cancel_last_year_count'] / $row['booking_created_last_year_count'])), 2);
				$lifeTime	 = number_format((100 * ($row['booking_cancel_lifetime_count'] / $row['booking_created_lifetime_count'])), 2);
				$html		 .= "<tr>
                            <th style='text-align:left'>Bookings ( $user ) cancellation %</th>
                            <td style='text-align:right'>" . $lifeTime . "</td>
                            <td style='text-align:right'>" . $yld . "</td>
                            <td style='text-align:right'>" . $month2 . "</td>
                            <td style='text-align:right'>" . $month1 . "</td>
                            <td style='text-align:right'>" . $mtd . "</td>
                            <td style='text-align:right'></td>
                            <td style='text-align:right'>NA</td>
                            <td style='text-align:right'>NA</td>
                            <td style='text-align:right'>NA</td>
                            <td style='text-align:right'>NA</td>
                        </tr>";
			}
		}
		$html .= "</table><br>";
		return $html;
	}

	public function getAdvancePaymentReportHtml()
	{
		$data	 = $this->advancePaymentReport();
		$html	 = "<b>Advance payments received :</b> ( <i> By create date && status [2,3,4,5,6,7] </i> )<br/>
                  <table width='50%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                        <tr>
                            <th width='15%'></th>
                            <th>#advance paid bookings</th>
                            <th>#bookings created</th>
                            <th>Total advance collected</th>
                            <th>Total bookings</th>
                        </tr>
                        <tr>
                            <td><b>MTD</b></td>
                            <td style='text-align:right'>" . $data['advance_count_mtd'] . "</td>
                            <td style='text-align:right'>" . $data['total_count_mtd'] . "</td>
                            <td style='text-align:right'>" . number_format($data['advance_amt_mtd'], 2) . "</td>
                            <td style='text-align:right'>" . number_format($data['total_amt_mtd'], 2) . "</td>
                        </tr>
                        <tr>
                            <td><b>Month-1</b></td>
                            <td style='text-align:right'>" . $data['advance_count_month1'] . "</td>
                            <td style='text-align:right'>" . $data['total_count_month1'] . "</td>
                            <td style='text-align:right'>" . number_format($data['advance_amt_month1'], 2) . "</td>
                            <td style='text-align:right'>" . number_format($data['total_amt_month1'], 2) . "</td>
                        </tr>
                        <tr>
                            <td><b>Month-2</b></td>
                            <td style='text-align:right'>" . $data['advance_count_month2'] . "</td>
                            <td style='text-align:right'>" . $data['total_count_month2'] . "</td>
                            <td style='text-align:right'>" . number_format($data['advance_amt_month2'], 2) . "</td>
                            <td style='text-align:right'>" . number_format($data['total_amt_month2'], 2) . "</td>
                        </tr>
                        <tr>
                            <td><b>Month-3</b></td>
                            <td style='text-align:right'>" . $data['advance_count_month3'] . "</td>
                            <td style='text-align:right'>" . $data['total_count_month3'] . "</td>
                            <td style='text-align:right'>" . number_format($data['advance_amt_month3'], 2) . "</td>
                            <td style='text-align:right'>" . number_format($data['total_amt_month3'], 2) . "</td>
                        </tr>
                    </table><br/>";
		return $html;
	}

	public function getNewRepeatCustomerHtml()
	{
		$rows			 = $this->newRepeatCustomerReport();
		$html			 = "<b>Repeat vs New Customers :</b> ( <i> By create date && status [2,3,4,5,6,7,9] </i> )<br/>
                  <table width='50%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th style='text-align:center'>Customer count</th>
                        <th>Repeat Customer</th>
                        <th>New Customer</th>
                    </tr>";
		$total_repeat	 = 0;
		$total_customer	 = 0;
		if(count($rows) > 0)
		{
			$rep = 3;
			foreach($rows as $row)
			{
				$total_repeat	 = ($total_repeat + $row['repeat_customer']);
				$total_customer	 = ($total_customer + $row['new_customer']);
				$html			 .= "<tr>
                            <th style='text-align:center'>" . Booking::model()->getMonthAlphabetic($rep, $row['month'], $row['year']) . "</th>
                            <td style='text-align:center'>" . $row['repeat_customer'] . "</td>
                            <td style='text-align:center'>" . $row['new_customer'] . "</td>
                         </tr>";
				$rep			 = ($rep - 1);
			}
			$html .= "<tr>
                        <th style='text-align:center'><b>Total</b></th>
                        <td style='text-align:center'>" . $total_repeat . "</td>
                        <td style='text-align:center'>" . $total_customer . "</td>
                     </tr>";
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getLifetimeTripReportHtml()
	{
		$data	 = $this->lifetimeTripReport();
		$html	 .= "<b>Life-Time Trips :</b> ( <i> By create date && status [2,3,4,5,6,7,9] </i> )<br/>
                    <table width='50%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                        <tr>
                            <th width='50%'>Customer life-time trips</th>
                            <th>Count</th>
                        </tr>
                        <tr>
                            <td><b>1 Trip</b></td>
                            <td style='text-align:center'>" . ($data['trip_1']) . "</td>
                        </tr>
                        <tr>
                            <td><b>2-5 Trips</b></td>
                            <td style='text-align:center'>" . ($data['trip_2']) . "</td>
                        </tr>
                        <tr>
                            <td><b>5-10 Trips</b></td>
                            <td style='text-align:center'>" . ($data['trip_5']) . "</td>
                        </tr>
                        <tr>
                            <td><b>10+ Trips</b></td>
                            <td style='text-align:center'>" . ($data['trip_10']) . "</td>
                        </tr>
                         <tr>
                            <td><b>Total</b></td>
                            <td style='text-align:center'>" . ($data['trip_1'] + $data['trip_2'] + $data['trip_5'] + $data['trip_10']) . "</td>
                        </tr>
                    </table><br/>";
		return $html;
	}

	public function getBookingByRatingReportHtml()
	{
		$rows			 = $this->bookingByRatingReport();
		$html			 = "<b>Reviews :</b> ( <i> By create date && status [2,3,4,5,6,7,9] </i> ) <br/>
                   <table width='50%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                       <tr>
                           <th style='text-align:left'>Reviews</th>
                           <th>MTD</th>
                           <th>Month-1</th>
                           <th>Month-2</th>
                           <th>YTD</th>
                           <th>Lifetime</th>
                       </tr>";
		$totalMtd		 = 0;
		$totalMonth1	 = 0;
		$totalMonth2	 = 0;
		$totalYtd		 = 0;
		$totalLifetime	 = 0;
		if(count($rows) > 0)
		{
			foreach($rows as $row)
			{
				$totalMtd		 = ($totalMtd + $row['review_count_mtd']);
				$totalMonth1	 = ($totalMonth1 + $row['review_count_month1']);
				$totalMonth2	 = ($totalMonth2 + $row['review_count_month2']);
				$totalYtd		 = ($totalYtd + $row['review_count_ytd']);
				$totalLifetime	 = ($totalLifetime + $row['review_count_lifetime']);
				$html			 .= "<tr>
                           <th style='text-align:center'>" . $row['rtg_customer_overall'] . "</th>
                           <td style='text-align:center'>" . $row['review_count_mtd'] . "</td>
                           <td style='text-align:center'>" . $row['review_count_month1'] . "</td>
                           <td style='text-align:center'>" . $row['review_count_month2'] . "</td>
                           <td style='text-align:center'>" . $row['review_count_ytd'] . "</td>
                           <td style='text-align:center'>" . $row['review_count_lifetime'] . "</td>
                        </tr>";
			}
			$html .= "<tr>
                       <th style='text-align:center'><b>Total</b></th>
                       <td style='text-align:center'>" . $totalMtd . "</td>
                       <td style='text-align:center'>" . $totalMonth1 . "</td>
                       <td style='text-align:center'>" . $totalMonth2 . "</td>
                       <td style='text-align:center'>" . $totalYtd . "</td>
                       <td style='text-align:center'>" . $totalLifetime . "</td>
                   </tr>";
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getBookingByPlatformReportHtml()
	{
		$rows			 = $this->bookingByPlatformReport();
		$html			 = "<b>Bookings Platform :</b> ( <i> By create date && status [2,3,4,5,6,7,9] </i> ) <br/>
                  <table width='50%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th style='text-align:center'>Bookings</th>
                        <th>Today</th>
                        <th>Today-1</th>
                        <th>Today-2</th>
                        <th>MTD</th>
                        <th>Month-1</th>
                        <th>Month-2</th>
                        <th>YTD</th>
                        <th>Lifetime</th>
                    </tr>";
		$countToday		 = $countToday1	 = $countToday2	 = $countMld		 = $countMonth1	 = $countMonth2	 = $countYtd		 = $countLifetime	 = 0;
		if(count($rows) > 0)
		{
			foreach($rows as $row)
			{
				$countToday		 = ($countToday + $row['booking_count_today']);
				$countToday1	 = ($countToday1 + $row['booking_count_today_1']);
				$countToday2	 = ($countToday2 + $row['booking_count_today_2']);
				$countMld		 = ($countMld + $row['booking_count_mtd']);
				$countMonth1	 = ($countMonth1 + $row['booking_count_month1']);
				$countMonth2	 = ($countMonth2 + $row['booking_count_month2']);
				$countYtd		 = ($countYtd + $row['booking_count_ytd']);
				$countLifetime	 = ($countLifetime + $row['booking_count_lifetime']);
				$html			 .= "<tr>
                            <th style='text-align:center'>" . $row['platform'] . "</th>
                            <td style='text-align:center'>" . $row['booking_count_today'] . "</td>
                            <td style='text-align:center'>" . $row['booking_count_today_1'] . "</td>
                            <td style='text-align:center'>" . $row['booking_count_today_2'] . "</td>
                            <td style='text-align:center'>" . $row['booking_count_mtd'] . "</td>
                            <td style='text-align:center'>" . $row['booking_count_month1'] . "</td>
                            <td style='text-align:center'>" . $row['booking_count_month2'] . "</td>
                            <td style='text-align:center'>" . $row['booking_count_ytd'] . "</td>
                            <td style='text-align:center'>" . $row['booking_count_lifetime'] . "</td>
                        </tr>";
			}
			$html .= "<tr>
                        <th style='text-align:center'><b>Total</b></th>
                        <td style='text-align:center'>" . $countToday . "</td>
                        <td style='text-align:center'>" . $countToday1 . "</td>
                        <td style='text-align:center'>" . $countToday2 . "</td>
                        <td style='text-align:center'>" . $countMld . "</td>
                        <td style='text-align:center'>" . $countMonth1 . "</td>
                        <td style='text-align:center'>" . $countMonth2 . "</td>
                        <td style='text-align:center'>" . $countYtd . "</td>
                        <td style='text-align:center'>" . $countLifetime . "</td>
                    </tr>";
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getBusinessSourceZoneHtml()
	{
		$sourceRows	 = $this->businessSourceZones();
		$html		 = "<b>Top 10 Source Zones :</b><br/>
                    <table width='50%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                        <tr>
                            <th>Zone</th>
                            <th>Last Month</th>
                            <th>Month to Date</th>
                            <th>Last Week</th>
                            <th>Week to Date</th></tr>";
		if(count($sourceRows) > 0)
		{
			for($i = 0; $i < count($sourceRows); $i++)
			{
				$html .= "<tr>
                        <th style='text-align:center'>" . strtoupper($sourceRows[$i]['zone_name']) . "</th>
                        <td style='text-align:center'>" . $sourceRows[$i]['last_month_count'] . "</td>
                        <td style='text-align:center'>" . $sourceRows[$i]['month_to_date_count'] . "</td>
                        <td style='text-align:center'>" . $sourceRows[$i]['last_week_count'] . "</td>
                        <td style='text-align:center'>" . $sourceRows[$i]['week_to_date_count'] . "</td>
                      </tr>";
			}
		}
		else
		{
			$html .= "<tr><td colspan='5'>No Records Yet Found.</td></tr>";
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getBusinessDestinationZoneHtml()
	{
		$destZoneRows	 = $this->businessDestinationZones();
		$html			 = "<b>Top 10 Destination Zones :</b><br/>
                    <table width='50%'  border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                        <tr>
                            <th>Zone</th>
                            <th>Last Month</th>
                            <th>Month to Date</th>
                            <th>Last Week</th>
                            <th>Week to Date</th>
                        </tr>";
		if(count($destZoneRows) > 0)
		{
			for($i = 0; $i < count($destZoneRows); $i++)
			{
				$html .= "<tr>
                            <th style='text-align:center'>" . strtoupper($destZoneRows[$i]['zone_name']) . "</th>
                            <td style='text-align:center'>" . $destZoneRows[$i]['last_month_count'] . "</td>
                            <td style='text-align:center'>" . $destZoneRows[$i]['month_to_date_count'] . "</td>
                            <td style='text-align:center'>" . $destZoneRows[$i]['last_week_count'] . "</td>
                            <td style='text-align:center'>" . $destZoneRows[$i]['week_to_date_count'] . "</td>
                        </tr>";
			}
		}
		else
		{
			$html .= "<tr><td colspan='5'>No Records Yet Found.</td></tr>";
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getCancellationReasonReportHtml()
	{
		$rows	 = $this->cancellationReasonReport();
		$html	 = "<b>Cancellation Reason Report (created anytime, cancelled as in column) :</b>" . count($rows) . "
                 <table width='90%'  border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th></th>
                        <th>today</th>
                        <th>today -1</th>
                        <th>today -2</th>
                        <th>this week</th>
                        <th>week -1</th>
                        <th>mtd</th>
                        <th>month -1</th>
                    </tr>";
		if(count($rows) > 0)
		{
			$sum_today	 = $sum_today1	 = $sum_today2	 = $sum_wtd	 = $sum_week1	 = $sum_mtd	 = $sum_month1	 = 0;
			foreach($rows as $row)
			{
				$sum_today	 = ($sum_today + $row['can_today']);
				$sum_today1	 = ($sum_today1 + $row['can_today1']);
				$sum_today2	 = ($sum_today2 + $row['can_today2']);
				$sum_wtd	 = ($sum_wtd + $row['can_wtd']);
				$sum_week1	 = ($sum_week1 + $row['can_week1']);
				$sum_mtd	 = ($sum_mtd + $row['can_mtd']);
				$sum_month1	 = ($sum_month1 + $row['can_month1']);
				$html		 .= "<tr>
                            <td style='text-align:left'>" . ($row['cnr_reason']) . "</td>
                            <td style='text-align:center'>" . ($row['can_today']) . "</td>
                            <td style='text-align:center'>" . ($row['can_today1']) . "</td>
                            <td style='text-align:center'>" . ($row['can_today2']) . "</td>
                            <td style='text-align:center'>" . ($row['can_wtd']) . "</td>
                            <td style='text-align:center'>" . ($row['can_week1']) . "</td>
                            <td style='text-align:center'>" . ($row['can_mtd']) . "</td>
                            <td style='text-align:center'>" . ($row['can_month1']) . "</td>
                        </tr>";
			}
			$html .= "<tr>
                            <td style='text-align:left'><b>Total</b></td>
                            <td style='text-align:center'>" . $sum_today . "</td>
                            <td style='text-align:center'>" . $sum_today1 . "</td>
                            <td style='text-align:center'>" . $sum_today2 . "</td>
                            <td style='text-align:center'>" . $sum_wtd . "</td>
                            <td style='text-align:center'>" . $sum_week1 . "</td>
                            <td style='text-align:center'>" . $sum_mtd . "</td>
                            <td style='text-align:center'>" . $sum_month1 . "</td>
                        </tr>";
		}
		else
		{
			$html .= "<tr><td style='text-align:center' colspan='8'>No Cancellations Yet Found.<td></tr>";
		}
		$html .= "</table><br/>";

		$html .= "<b>Cancellation Reason Report (created anytime, cancelled as in column) :</b>" . count($rows) . "
                 <table width='90%'  border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th></th>
                        <th>today</th>
                        <th>today -1</th>
                        <th>today -2</th>
                        <th>this week</th>
                        <th>week -1</th>
                        <th>mtd</th>
                        <th>month -1</th>
                    </tr>";
		if(count($rows) > 0)
		{
			$sum_amt_today	 = $sum_amt_today1	 = $sum_amt_today2	 = $sum_amt_wtd	 = $sum_amt_week1	 = $sum_amt_mtd	 = $sum_amt_month1	 = 0;
			foreach($rows as $row)
			{
				$sum_amt_today	 = ($sum_amt_today + $row['can_amt_today']);
				$sum_amt_today1	 = ($sum_amt_today1 + $row['can_amt_today1']);
				$sum_amt_today2	 = ($sum_amt_today2 + $row['can_amt_today2']);
				$sum_amt_wtd	 = ($sum_amt_wtd + $row['can_amt_wtd']);
				$sum_amt_week1	 = ($sum_amt_week1 + $row['can_amt_week1']);
				$sum_amt_mtd	 = ($sum_amt_mtd + $row['can_amt_mtd']);
				$sum_amt_month1	 = ($sum_amt_month1 + $row['can_amt_month1']);
				$html			 .= "<tr>
                            <td style='text-align:left'>" . ($row['cnr_reason']) . "</td>
                            <td style='text-align:right'>" . number_format($row['can_amt_today'], 2) . "</td>
                            <td style='text-align:right'>" . number_format($row['can_amt_today1'], 2) . "</td>
                            <td style='text-align:right'>" . number_format($row['can_amt_today2'], 2) . "</td>
                            <td style='text-align:right'>" . number_format($row['can_amt_wtd'], 2) . "</td>
                            <td style='text-align:right'>" . number_format($row['can_amt_week1'], 2) . "</td>
                            <td style='text-align:right'>" . number_format($row['can_amt_mtd'], 2) . "</td>
                            <td style='text-align:right'>" . number_format($row['can_amt_month1'], 2) . "</td>
                        </tr>";
			}
			$html .= "<tr>
                            <td style='text-align:left'><b>Total</b></td>
                            <td style='text-align:right'>" . number_format($sum_amt_today, 2) . "</td>
                            <td style='text-align:right'>" . number_format($sum_amt_today1, 2) . "</td>
                            <td style='text-align:right'>" . number_format($sum_amt_today2, 2) . "</td>
                            <td style='text-align:right'>" . number_format($sum_amt_wtd, 2) . "</td>
                            <td style='text-align:right'>" . number_format($sum_amt_week1, 2) . "</td>
                            <td style='text-align:right'>" . number_format($sum_amt_mtd, 2) . "</td>
                            <td style='text-align:right'>" . number_format($sum_amt_month1, 2) . "</td>
                        </tr>";
		}
		else
		{
			$html .= "<tr><td style='text-align:center' colspan='8'>No Cancellations Yet Found.<td></tr>";
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getCancellationSourceReportHtml()
	{
		$rows	 = $this->cancellationSourceReport();
		$html	 = "<b>Cancellation Source Report (created anytime, cancelled as in column) :</b>" . count($rows) . "
                 <table width='90%'  border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th></th>
                        <th>today</th>
                        <th>today -1</th>
                        <th>today -2</th>
                        <th>this week</th>
                        <th>week -1</th>
                        <th>mtd</th>
                        <th>month -1</th>
                    </tr>";
		if(count($rows) > 0)
		{
			$sum_today	 = $sum_today1	 = $sum_today2	 = $sum_wtd	 = $sum_week1	 = $sum_mtd	 = $sum_month1	 = 0;
			foreach($rows as $row)
			{
				$sum_today	 = ($sum_today + $row['can_today']);
				$sum_today1	 = ($sum_today1 + $row['can_today1']);
				$sum_today2	 = ($sum_today2 + $row['can_today2']);
				$sum_wtd	 = ($sum_wtd + $row['can_wtd']);
				$sum_week1	 = ($sum_week1 + $row['can_week1']);
				$sum_mtd	 = ($sum_mtd + $row['can_mtd']);
				$sum_month1	 = ($sum_month1 + $row['can_month1']);
				$html		 .= "<tr>
                            <td style='text-align:left'>" . ($row['cancelled_by']) . "</td>
                            <td style='text-align:center'>" . ($row['can_today']) . "</td>
                            <td style='text-align:center'>" . ($row['can_today1']) . "</td>
                            <td style='text-align:center'>" . ($row['can_today2']) . "</td>
                            <td style='text-align:center'>" . ($row['can_wtd']) . "</td>
                            <td style='text-align:center'>" . ($row['can_week1']) . "</td>
                            <td style='text-align:center'>" . ($row['can_mtd']) . "</td>
                            <td style='text-align:center'>" . ($row['can_month1']) . "</td>
                        </tr>";
			}
			$html .= "<tr>
                            <td style='text-align:left'><b>Total</b></td>
                            <td style='text-align:center'>" . $sum_today . "</td>
                            <td style='text-align:center'>" . $sum_today1 . "</td>
                            <td style='text-align:center'>" . $sum_today2 . "</td>
                            <td style='text-align:center'>" . $sum_wtd . "</td>
                            <td style='text-align:center'>" . $sum_week1 . "</td>
                            <td style='text-align:center'>" . $sum_mtd . "</td>
                            <td style='text-align:center'>" . $sum_month1 . "</td>
                        </tr>";
		}
		else
		{
			$html .= "<tr><td style='text-align:center' colspan='8'>No Cancellations Yet Found.<td></tr>";
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getCancellationBookingReportHtml()
	{
		$rows = $this->cancellationBookingReport();

		$html = "<b>Cancellations today :</b>" . count($rows[0]) . "
                 <table width='90%'  border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th>Booking ID</th>
                        <th>Route</th>
                        <th>Cancel #Hrs</th>
                        <th>Created Date</th>
                        <th>Pickup Date</th>
                        <th>Booking Amount</th>
                        <th>Advance Paid</th>
                        <th>Cancellation Reason Code</th>
                        <th>Cancellation Notes</th>
                        <th>Cancelled By (Username)</th>
                        <th>Cancellation Platform</th>
                        <th>Driver/Customer Info Sent</th>
                    </tr>";
		if(count($rows[0]) > 0)
		{
			foreach($rows[0] as $row)
			{
				$platform = '';
				if($row['blg_user_type'] == 1)
				{
					$platform = 'User';
				}
				else if($row['blg_user_type'] == 4)
				{
					$platform = 'Admin';
				}
				else if($row['blg_user_type'] == 10)
				{
					$platform = 'Auto';
				}

				$slgTypes	 = array_search($row['booking_id'], array_column($rows[1], 'booking_id'));
				$slgInfo	 = $slgTypes ? 'Y' : 'N';
				$html		 .= "<tr>
                            <td style='text-align:center'>" . ($row['booking_id']) . "</td>
                            <td style='text-align:center'>" . ($row['route']) . "</td>
                            <td style='text-align:center'>" . $row['hrs_before_pickup'] . "</td>
                            <td style='text-align:center'>" . date("d-m-Y", strtotime($row['created_date'])) . "</td>
                            <td style='text-align:center'>" . date("d-m-Y", strtotime($row['pickup_date'])) . "</td>
                            <td style='text-align:center'>Rs. " . $row['booking_aount'] . "</td>
                            <td style='text-align:center'>Rs. " . $row['advance_amount'] . "</td>
                            <td style='text-align:center'>" . $row['cnr_reason'] . "</td>
                            <td style='text-align:center'>" . $row['cancellation_notes'] . "</td>
                            <td style='text-align:center'>" . $row['cancelled_by'] . "</td>
                            <td style='text-align:center'>" . $platform . "</td>
                            <td style='text-align:center'>" . $slgInfo . "</td>
                        </tr>";
			}
		}
		else
		{
			$html .= "<tr><td style='text-align:center' colspan='12'>No Cancellations Yet Found.<td></tr>";
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getInventoryMetricsReportHtml()
	{
		$data = $this->inventoryMetricsReport();

		$html = "<b>Inventory Metrics:</b><br/>
                    <table width='50%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                       <tr>
                           <td style='text-align:left'>Drivers in system</td>
                           <td style='text-align:right'>" . ($data['count_drivers_system']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Verified Address proof</td>
                           <td style='text-align:right'>" . ($data['cout_drivers_adddress_proof']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Verified License number against License proof</td>
                           <td style='text-align:right'>" . ($data['cout_drivers_licence_proof']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Verified Drivers police verification certificate</td>
                           <td style='text-align:right'>" . ($data['cout_drivers_police_certificate']) . "</td>
                       </tr>

                       <tr>
                           <td style='text-align:left'>Drivers license on hand</td>
                           <td style='text-align:right'>" . ($data['count_drivers_license']) . "</td>
                       </tr>
                        <tr>
                           <td style='text-align:left'>Drivers approved</td>
                           <td style='text-align:right'>" . ($data['count_drivers_approved']) . "</td>
                       </tr>
                        <tr>
                           <td colspan=2>&nbsp;</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Cars in system</td>
                           <td style='text-align:right'>" . ($data['count_car_system']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Blocked</td>
                           <td style='text-align:right'>" . ($data['count_car_blocked']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Active</td>
                           <td style='text-align:right'>" . ($data['count_car_active']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Approved</td>
                           <td style='text-align:right'>" . ($data['count_car_approved']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Rejected</td>
                           <td style='text-align:right'>" . ($data['count_car_rejected']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Papers missing</td>
                           <td style='text-align:right'>" . ($data['count_car_missing_paper']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Commercially approved</td>
                           <td style='text-align:right'>" . ($data['count_car_commercial']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Pending approval</td>
                           <td style='text-align:right'>" . ($data['count_car_pending_approved']) . "</td>
                       </tr>
                       <tr>
                           <td colspan=2>&nbsp;</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Verified as commercial</td>
                           <td style='text-align:right'>" . ($data['cout_commercial_verified']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Insurance proof available & valid</td>
                           <td style='text-align:right'>" . ($data['cout_insurance_proof']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Registration Certificate available & valid</td>
                           <td style='text-align:right'>" . ($data['cout_reg_certificate']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Pollution Control Certificate available & valid</td>
                           <td style='text-align:right'>" . ($data['cout_pollution_control_certificate']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Commercial Certificate available& valid</td>
                           <td style='text-align:right'>" . ($data['cout_commercial_certificate']) . "</td>
                       </tr>
                       <tr>
                           <td style='text-align:left'>Fitness Certificate available & valid</td>
                           <td style='text-align:right'>" . ($data['cout_fitness_certificate']) . "</td>
                       </tr>
                    </table><br/>";
		return $html;
	}

	public function getBookingBySourceReportHtml()
	{
		$rows			 = $this->bookingBySourceReport();
		$html			 = "<b>Where they heard about us :</b> (<i> By create date && status [2,3,4,5,6,7,9] </i>)<br/>
                    <table width='50%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                        <tr>
                            <th style='text-align:left'></th>
                            <th>Lifetime</th>
                            <th>YTD</th>
                            <th>Month-3</th>
                            <th>Month-2</th>
                            <th>Month-1</th>
                            <th>MTD</th>
                        </tr>";
		$countLifetime	 = $countYtd		 = $countMonth3	 = $countMonth2	 = $countMonth1	 = $countMld		 = 0;
		if(count($rows) > 0)
		{
			$ctr = 1;
			foreach($rows as $row)
			{
				$countLifetime	 = ($countLifetime + $row['source_count_lifetime']);
				$countYtd		 = ($countYtd + $row['source_count_ytd']);
				$countMonth3	 = ($countMonth3 + $row['source_count_month3']);
				$countMonth2	 = ($countMonth2 + $row['source_count_month2']);
				$countMonth1	 = ($countMonth1 + $row['source_count_month1']);
				$countMld		 = ($countMld + $row['source_count_mtd']);
				$html			 .= "<tr>
                            <th style='text-align:left'>" . $row['sourceInfo'] . "</th>
                            <td style='text-align:center'>" . $row['source_count_lifetime'] . "</td>
                            <td style='text-align:center'>" . $row['source_count_ytd'] . "</td>
                            <td style='text-align:center'>" . $row['source_count_month3'] . "</td>
                            <td style='text-align:center'>" . $row['source_count_month2'] . "</td>
                            <td style='text-align:center'>" . $row['source_count_month1'] . "</td>
                            <td style='text-align:center'>" . $row['source_count_mtd'] . "</td>
                        </tr>";
				$ctr++;
			}
			$html .= "<tr>
                        <th style='text-align:left'><b>Total</b></th>
                        <td style='text-align:center'>" . $countLifetime . "</td>
                        <td style='text-align:center'>" . $countYtd . "</td>
                        <td style='text-align:center'>" . $countMonth3 . "</td>
                        <td style='text-align:center'>" . $countMonth2 . "</td>
                        <td style='text-align:center'>" . $countMonth1 . "</td>
                        <td style='text-align:center'>" . $countMld . "</td>
                    </tr>";
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getCancelReasonReportHtml()
	{
//$rows = $this->cancelReasonReport();
		$rows	 = $this->cancellationReasonReport();
		$html	 = "<b>Cancellation Reasons :</b> (" . count($rows) . ") <br/>
                   <table width='50%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                       <tr>
                           <th>Cancellation Reasons</th>
                           <th>MTD</th>
                           <th>Month-1</th>
                           <th>Month-2</th>
                           <th>YTD</th>
                           <th>Lifetime</th>
                       </tr>";
		if(count($rows) > 0)
		{
			foreach($rows as $row)
			{
				$html .= "<tr>
                            <th style='text-align:left'>" . $row['cnr_reason'] . "</th>
                            <td style='text-align:center'>" . $row['can_mtd'] . "</td>
                            <td style='text-align:center'>" . $row['can_month1'] . "</td>
                            <td style='text-align:center'>" . $row['cancel_month2'] . "</td>
                            <td style='text-align:center'>" . $row['cancel_ytd'] . "</td>
                            <td style='text-align:center'>" . $row['cancel_lifetime'] . "</td>
                        </tr>";
			}
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getNonProfitBookingsByMtdHtml()
	{
		$rows	 = $this->getNonProfitBookingsByMtd();
		$html	 = "<b>Non Profitable Bookings :</b> (This Month) : " . count($rows) . "<br/>
                 <table width='70%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th>Booking ID</th>
                        <th>Booking Date</th>
                        <th>Pickup Date</th>
                        <th>Last Modified</th>
                        <th>Status</th>
                        <th>Booking Amount inc Svc Tax</th>
                        <th>Vendor Amount</th>
                        <th>Gozo Loss</th>
                    </tr>";
		if(count($rows) > 0)
		{
			foreach($rows as $row)
			{
				$status	 = Booking::model()->getBookingStatus($row['bkg_status']);
				$html	 .= "<tr>
                            <td style='text-align:center'>" . ($row['bkg_booking_id']) . "</td>
                            <td style='text-align:center'>" . date("d-m-Y", strtotime($row['bkg_create_date'])) . "</td>
                            <td style='text-align:center'>" . date("d-m-Y", strtotime($row['bkg_pickup_date'])) . "</td>
                            <td style='text-align:center'>" . date("d-m-Y", strtotime($row['bkg_modified_on'])) . "</td>
                            <td style='text-align:center'>" . $status . "</td>
                            <td style='text-align:right'>" . number_format(($row['bkg_total_amount'] + $row['bkg_service_tax']), 2) . "</td>
                            <td style='text-align:right'>" . number_format(($row['bkg_vendor_amount']), 2) . "</td>
                            <td style='text-align:right'>" . number_format(($row['loss_amount']), 2) . "</td>
                        </tr>";
			}
		}
		else
		{
			$html .= "<tr>
                        <td style='text-align:center' colspan='8'>No Non Profitable Bookings Yet Found.<td>
                    </tr>";
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getBookingByZoneReportHtml()
	{
		$rows	 = $this->bookingByZoneReport(20);
		$html	 = "<b>Zone Today :</b> (<i> By create date && status [2,3,4,5,6,7,9] </i>)
                 <table width='70%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th colspan='3' align='center'>Last Month Update (by zone)</th>
                        <th colspan='2' align='center'>This month</th>
                        <th colspan='2' align='center'>YTD</th>
                    </tr>
                    <tr>
                        <th>Zone</th>
                        <th>Bookings Created</th>
                        <th>Bookings Completed</th>
                        <th>Bookings Created</th>
                        <th>Bookings Completed</th>
                        <th>Bookings Created</th>
                        <th>Bookings Completed</th>
                    </tr>";
		if(count($rows) > 0)
		{
			$total_last_month_booking_created	 = 0;
			$total_last_month_booking_completed	 = 0;
			$total_month_booking_created		 = 0;
			$total_month_booking_completed		 = 0;
			$total_yld_booking_created			 = 0;
			$total_yld_booking_completed		 = 0;
			foreach($rows as $row)
			{
				$html								 .= "<tr>
                            <th style='text-align:center'>" . ($row['zon_name']) . "</th>
                            <td style='text-align:center'>" . $row['last_month_booking_created'] . "</td>
                            <td style='text-align:center'>" . $row['last_month_booking_completed'] . "</td>
                            <td style='text-align:center'>" . $row['month_booking_created'] . "</td>
                            <td style='text-align:center'>" . $row['month_booking_completed'] . "</td>
                            <td style='text-align:center'>" . $row['yld_booking_created'] . "</td>
                            <td style='text-align:center'>" . $row['yld_booking_completed'] . "</td>
                         </tr>";
				$total_last_month_booking_created	 = ($total_last_month_booking_created + $row['last_month_booking_created']);
				$total_last_month_booking_completed	 = ($total_last_month_booking_completed + $row['last_month_booking_completed']);
				$total_month_booking_created		 = ($total_month_booking_created + $row['month_booking_created']);
				$total_month_booking_completed		 = ($total_month_booking_completed + $row['month_booking_completed']);
				$total_yld_booking_created			 = ($total_yld_booking_created + $row['yld_booking_created']);
				$total_yld_booking_completed		 = ($total_yld_booking_completed + $row['yld_booking_completed']);
			}
			$html .= "<tr>
                       <th style='text-align:center'>Total</th>
                       <td style='text-align:center'>" . $total_last_month_booking_created . "</td>
                       <td style='text-align:center'>" . $total_last_month_booking_completed . "</td>
                       <td style='text-align:center'>" . $total_month_booking_created . "</td>
                       <td style='text-align:center'>" . $total_month_booking_completed . "</td>
                       <td style='text-align:center'>" . $total_yld_booking_created . "</td>
                       <td style='text-align:center'>" . $total_yld_booking_completed . "</td>
                    </tr>";
		}
		$html .= "</table><br/><br/>";
		return $html;
	}

	public function listByAgent($agent, $paramArray, $corpCode = '', $type = false)
	{
		$where = '';
		if($paramArray['bkg_booking_id'])
		{
			$where .= " AND bkg_booking_id LIKE '%" . $paramArray['bkg_booking_id'] . "%' ";
		}
		if($paramArray['traveller_name'])
		{
			$where .= " AND (booking_user.bkg_user_fname LIKE '%" . $paramArray['traveller_name'] . "%' OR booking_user.bkg_user_lname LIKE '%" . $paramArray['traveller_name'] . "%') ";
		}
		if($paramArray['bkg_user_email1'])
		{
			$where .= " AND booking_user.bkg_user_email LIKE '%" . $paramArray['bkg_user_email1'] . "%' ";
		}
		if($paramArray['bkg_contact_no1'])
		{
			$where .= " AND booking_user.bkg_contact_no LIKE '%" . $paramArray['bkg_contact_no1'] . "%' ";
		}
		if($paramArray['bkg_from_city_id'])
		{
			$where .= " AND bkg_from_city_id = {$paramArray['bkg_from_city_id']}";
		}
		if($paramArray['bkg_to_city_id'])
		{
			$where .= " AND bkg_to_city_id = {$paramArray['bkg_to_city_id']}";
		}

		if($paramArray['bkg_status'] == 1)
		{
			$where .= " AND booking.bkg_status IN (2,3,5)";
		}
		else if($paramArray['bkg_status'] == 2)
		{
			$where .= " AND booking.bkg_status IN(2)";
		}
		else if($paramArray['bkg_status'] == 3)
		{
			$where .= " AND booking.bkg_status IN(3)";
		}
		else if($paramArray['bkg_status'] == 5)
		{
			$where .= " AND booking.bkg_status IN(5)";
		}
		else if($paramArray['bkg_status'] == 6)
		{
			$where .= " AND booking.bkg_status IN(6)";
		}
		else if($paramArray['bkg_status'] == 9)
		{
			$where .= " AND booking.bkg_status IN(9)";
		}
		else if($paramArray['bkg_status'] == 10)
		{
			$where .= " AND booking.bkg_status IN(10)";
		}
		else
		{
			$where .= " AND booking.bkg_status IN (2,3,5,6,7,9)";
		}

		if($paramArray['bkg_create_date1'] != "" && $paramArray['bkg_create_date2'] != "")
		{
			$where .= " AND (DATE(bkg_create_date) BETWEEN '{$paramArray['bkg_create_date1']}' AND '{$paramArray['bkg_create_date2']}' )";
		}
		if($paramArray['bkg_pickup_date1'] != "" && $paramArray['bkg_pickup_date2'] != "")
		{
			$where .= " AND (DATE(bkg_pickup_date) BETWEEN '{$paramArray['bkg_pickup_date1']}' AND '{$paramArray['bkg_pickup_date2']}' )";
		}

//		echo "<pre>";
//		print_r($paramArray);
		
		$search = $paramArray['search'];
		if($search != "")
		{
			$fields		 = ['bkg_booking_id', 'bkg_user_fname', 'bkg_user_lname', 'bkg_contact_no',
				'bkg_alt_contact_no', 'bkg_user_email', 'bkg_agent_ref_code',
				'bkg_pickup_address', 'bkg_drop_address', 'bkg_bcb_id',
				'bkg_instruction_to_driver_vendor'];
			$arrSearch	 = array_filter(explode(" ", $search));
			$search1	 = [];
			foreach($arrSearch as $val)
			{
				$arr = [];
				$key = array_search($val, $this->getTags());
				if($key > 0)
				{
					$arr[] = "FIND_IN_SET($key,REPLACE(bkg_tags,' ',''))";
				}
				foreach($fields as $field)
				{
					$arr[] = "$field LIKE '%{$val}%'";
				}
				$search1[] = "(" . implode(' OR ', $arr) . ")";
			}

			$where .= " AND " . implode(" AND ", $search1);
		}

		if($paramArray['bkg_status_name'])
		{
			$statusList		 = Booking::model()->getActiveBookingStatus();
			$statusKeyArr	 = ['0'];
			foreach($statusList as $key => $value)
			{
				if(false !== strpos(strtolower(trim($value)), strtolower(trim($paramArray['bkg_status_name']))))
				{
					$statusKeyArr[] = $key;
				}
			}
			$statusKey = implode(',', $statusKeyArr);
			if(trim($statusKey) != '')
			{
				$where = " AND bkg_status IN ($statusKey) ";
			}
		}
		$condAgentStr = " bkg_agent_id=$agent ";
		if($corpCode != '')
		{
			$condAgentStr = " bkg_agent_id=$agent ";
		}


		$sql = "SELECT
             bkg_id,bkg_bcb_id,bkg_booking_id,bkg_agent_id,bkg_agent_ref_code,
                    trim(concat(bkg_user_fname,' ',bkg_user_lname)) bkg_user_name,
                    bkg_user_fname,bkg_user_lname,
                    bkg_corporate_credit,bkg_agent_markup,
                    bkg_user_id,
                    CONCAT(bkg_country_code, bkg_contact_no) bkg_contact_no,bkg_user_email,bkg_status,
                    (CASE
                    WHEN (bkg_status = 2) THEN 'Confirmed' WHEN (bkg_status = 3) THEN 'Confirmed' WHEN (bkg_status = 5) THEN 'Confirmed'
                    WHEN (bkg_status = 6) THEN 'Completed' WHEN (bkg_status = 7) THEN 'Completed' WHEN (bkg_status = 9) THEN 'Cancelled'
					END
					) as status,
                    bkg_platform,bkg_vehicle_type_id,bkg_pickup_date,bkg_create_date,bkg_pickup_address,bkg_drop_address,bkg_total_amount,bkg_advance_amount,
                    bkg_instruction_to_driver_vendor,bkg_payment_expiry_time,round(bkg_partner_commission/1.18) as bkg_partner_commission, round(bkg_partner_commission - bkg_partner_commission/1.18) as commissionGst,
					bkg_partner_extra_commission,
				usr_mark_customer_count,
                    GROUP_CONCAT(fromCity.cty_name SEPARATOR ' - ') AS fromCities,GROUP_CONCAT(toCity.cty_name SEPARATOR ' - ') AS toCities,
                    IF(bkg_pickup_date>NOW(),0,1) deforder
                FROM
                  `booking`
						 JOIN `booking_user` ON booking.bkg_id=booking_user.bui_bkg_id
						 JOIN `booking_invoice` ON booking.bkg_id=booking_invoice.biv_bkg_id
						 JOIN `booking_trail` ON booking.bkg_id=booking_trail.btr_bkg_id
                        INNER JOIN booking_route ON booking.bkg_id = brt_bkg_id AND brt_active = 1
                        INNER JOIN cities fromCity ON booking_route.brt_from_city_id = fromCity.cty_id
                        INNER JOIN cities toCity ON booking_route.brt_to_city_id = toCity.cty_id
						LEFT JOIN users usr ON booking_user.bkg_user_id = usr.user_id
                        WHERE 1 AND $condAgentStr $where GROUP by bkg_id ";
//		die($sql);
		if($type == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(1) FROM ($sql) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 =>
				['attributes'	 =>
					['bkg_user_name', 'bkg_booking_id', 'bkg_id', 'from_city_name', 'bkg_status',
						'to_city_name', 'bkg_total_amount', 'bkg_create_date', 'bkg_pickup_date',
						'bkg_return_date'],
					'defaultOrder'	 => 'deforder,bkg_id desc'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql);
		}
	}

	public function getBusinessReportHtml()
	{
		$data				 = $this->getBusinessReportRevise();
		$quotedBkg			 = BookingSub::fetchQuotedBkgByCreatedate();
		$tripOnTheWayBkg	 = BookingSub::fetchTripOntheWayByPickupDate();
		$tripStartedBkg		 = BookingSub::fetchTripStartedByPickupDate();
		$gmvTotalBkg		 = BookingSub::fetchGMVTotalPayBkgByCreateDate();
		$advGmvPaymentBkg	 = BookingSub::fetchGMVAdvancePayBkgByCreateDate();
		$advPaymentBkg		 = BookingSub::fetchAdvancePaymentBkgByActDate();
		$advCountBkg		 = BookingSub::fetchAdvanceCountBkgByCreatedDate();
		$codBkg				 = BookingSub::fetchCODBkgByCreatedDate();
		$tentativeBkg		 = BookingSub::fetchTentativeBkgByCreateDate();
		$cancelToday		 = BookingSub::fetchCancelBkgByCreateToday();
		$cancelCreateAnytime = BookingSub::fetchCancelBkgByCreateAnytime();
		$tripMatched		 = BookingSub::fetchTripMatchByCreatedDate();
		$npsScore			 = BookingSub::fetchNpsScoreOnRating();
		$confirmCash		 = BookingSub::fetchConfirmCashByCreateDate();

		$othersdata1 = ($data['trip_book_last_week_excl_b2b'] - $data['trip_book_last_week_excl_mmt']);
		$othersdata2 = ($data['trip_book_wtd_excl_b2b'] - $data['trip_book_wtd_excl_mmt']);
		$othersdata3 = ($data['trip_book_today2_excl_b2b'] - $data['trip_book_today2_excl_mmt']);
		$othersdata4 = ($data['trip_book_today1_excl_b2b'] - $data['trip_book_today1_excl_mmt']);
		$othersdata5 = ($data['trip_book_today_excl_b2b'] - $data['trip_book_today_excl_mmt']);

		$html	 = "<b>Business Report : </b>(<i> By create date && status [2,3,4,5,6,7] </i>)
                 <table width='90%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th width='20%'></th>
                        <th align='center'>Last Week</th>
                        <th align='center'>Week to Date</th>
                        <th align='center'>Today –2</th>
                        <th align='center'>Today –1</th>
                        <th align='center'>Today</th>
                        <th align='center'>Tomorrow</th>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Trips Booked (Inc cancel)</b></td>
                        <td style='text-align:right'>" . $data['trip_book_last_week'] . "</td>
                        <td style='text-align:right'>" . $data['trip_book_wtd'] . "</td>
                        <td style='text-align:right'>" . $data['trip_book_today2'] . "</td>
                        <td style='text-align:right'>" . $data['trip_book_today1'] . "</td>
                        <td style='text-align:right'>" . $data['trip_book_today'] . "</td>
                        <td style='text-align:right'>N/A</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Bookings quoted  (Active)</b></td>
                        <td style='text-align:right'>" . $quotedBkg['booking_quoted_last_week_count'] . "</td>
                        <td style='text-align:right'>" . $quotedBkg['booking_quoted_wtd_count'] . "</td>
                        <td style='text-align:right'>" . $quotedBkg['booking_quoted_today2_count'] . "</td>
                        <td style='text-align:right'>" . $quotedBkg['booking_quoted_today1_count'] . "</td>
                        <td style='text-align:right'>" . $quotedBkg['booking_quoted_today_count'] . "</td>
                        <td style='text-align:right'>N/A</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Trips Booked (Active)</b></td>
                        <td style='text-align:right'>" . $data['trip_book_last_week_excl'] . "</td>
                        <td style='text-align:right'>" . $data['trip_book_wtd_excl'] . "</td>
                        <td style='text-align:right'>" . $data['trip_book_today2_excl'] . "</td>
                        <td style='text-align:right'>" . $data['trip_book_today1_excl'] . "</td>
                        <td style='text-align:right'>" . $data['trip_book_today_excl'] . "</td>
                        <td style='text-align:right'>N/A</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Trips booked B2C (Active)</b></td>
                        <td style='text-align:right'>" . ($data['trip_book_last_week_excl'] - $data['trip_book_last_week_excl_b2b']) . "</td>
                        <td style='text-align:right'>" . ($data['trip_book_wtd_excl'] - $data['trip_book_wtd_excl_b2b']) . "</td>
                        <td style='text-align:right'>" . ($data['trip_book_today2_excl'] - $data['trip_book_today2_excl_b2b']) . "</td>
                        <td style='text-align:right'>" . ($data['trip_book_today1_excl'] - $data['trip_book_today1_excl_b2b']) . "</td>
                        <td style='text-align:right'>" . ($data['trip_book_today_excl'] - $data['trip_book_today_excl_b2b']) . "</td>
                        <td style='text-align:right'>N/A</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Trips booked B2B (MMT/Others) (Active)</b></td>
						<td style='text-align:right'>" . (($data['trip_book_last_week_excl_b2b'] > 0) ? $data['trip_book_last_week_excl_b2b'] . '(' . $data['trip_book_last_week_excl_mmt'] . '/' . $othersdata1 . ')' : '0') . "</td>
						<td style='text-align:right'>" . (($data['trip_book_wtd_excl_b2b'] > 0) ? $data['trip_book_wtd_excl_b2b'] . '(' . $data['trip_book_wtd_excl_mmt'] . '/' . $othersdata2 . ')' : '0') . "</td>
						<td style='text-align:right'>" . (($data['trip_book_today2_excl_b2b'] > 0) ? $data['trip_book_today2_excl_b2b'] . '(' . $data['trip_book_today2_excl_mmt'] . '/' . $othersdata3 . ')' : '0') . "</td>
						<td style='text-align:right'>" . (($data['trip_book_today1_excl_b2b'] > 0) ? $data['trip_book_today1_excl_b2b'] . '(' . $data['trip_book_today1_excl_mmt'] . '/' . $othersdata4 . ')' : '0') . "</td>
						<td style='text-align:right'>" . (($data['trip_book_today_excl_b2b'] > 0) ? $data['trip_book_today_excl_b2b'] . '(' . $data['trip_book_today_excl_mmt'] . '/' . $othersdata5 . ')' : '0') . "</td>
						<td style='text-align:right'>N/A</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Trips on the Way</b></td>
                        <td style='text-align:right'>" . $tripOnTheWayBkg['trip_onway_last_week'] . "</td>
                        <td style='text-align:right'>" . $tripOnTheWayBkg['trip_onway_wtd'] . "</td>
                        <td style='text-align:right'>" . $tripOnTheWayBkg['trip_onway_today2'] . "</td>
                        <td style='text-align:right'>" . $tripOnTheWayBkg['trip_onway_today1'] . "</td>
                        <td style='text-align:right'>" . $tripOnTheWayBkg['trip_onway_today'] . "</td>
                        <td style='text-align:right'>" . $tripOnTheWayBkg['trip_onway_tomorrow'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Trips Started/ing</b></td>
                        <td style='text-align:right'>" . $tripStartedBkg['trip_started_last_week'] . "</td>
                        <td style='text-align:right'>" . $tripStartedBkg['trip_started_wtd'] . "</td>
                        <td style='text-align:right'>" . $tripStartedBkg['trip_started_today2'] . "</td>
                        <td style='text-align:right'>" . $tripStartedBkg['trip_started_today1'] . "</td>
                        <td style='text-align:right'>" . $tripStartedBkg['trip_started_today'] . "</td>
                        <td style='text-align:right'>" . $tripStartedBkg['trip_started_tomorrow'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Total Bookings (Active)</b></td>
                        <td style='text-align:right'>" . $gmvTotalBkg['gmv_last_week'] . "</td>
                        <td style='text-align:right'>" . $gmvTotalBkg['gmv_wtd'] . "</td>
                        <td style='text-align:right'>" . $gmvTotalBkg['gmv_today2'] . "</td>
                        <td style='text-align:right'>" . $gmvTotalBkg['gmv_today1'] . "</td>
                        <td style='text-align:right'>" . $gmvTotalBkg['gmv_today'] . "</td>
                        <td style='text-align:right'>N/A</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Bookings (Advanced paid)</b></td>
                        <td style='text-align:right'>" . $advGmvPaymentBkg['gmv_adv_last_week'] . "</td>
                        <td style='text-align:right'>" . $advGmvPaymentBkg['gmv_adv_wtd'] . "</td>
                        <td style='text-align:right'>" . $advGmvPaymentBkg['gmv_adv_today2'] . "</td>
                        <td style='text-align:right'>" . $advGmvPaymentBkg['gmv_adv_today1'] . "</td>
                        <td style='text-align:right'>" . $advGmvPaymentBkg['gmv_adv_today'] . "</td>
                        <td style='text-align:right'>N/A</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Advance Payment received</b></td>
                        <td style='text-align:right'>" . $advPaymentBkg['adv_pay_last_week'] . "</td>
                        <td style='text-align:right'>" . $advPaymentBkg['adv_pay_week_to_date'] . "</td>
                        <td style='text-align:right'>" . $advPaymentBkg['adv_pay_day2_before'] . "</td>
                        <td style='text-align:right'>" . $advPaymentBkg['adv_pay_day1_before'] . "</td>
                        <td style='text-align:right'>" . $advPaymentBkg['adv_pay_today'] . "</td>
                        <td style='text-align:right'>N/A</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#Trips booked (Advance Payments)</b></td>
                        <td style='text-align:right'>" . $advCountBkg['booking_adv_last_week_count'] . "</td>
                        <td style='text-align:right'>" . $advCountBkg['booking_adv_wtd_count'] . "</td>
                        <td style='text-align:right'>" . $advCountBkg['booking_adv_today2_count'] . "</td>
                        <td style='text-align:right'>" . $advCountBkg['booking_adv_today1_count'] . "</td>
                        <td style='text-align:right'>" . $advCountBkg['booking_adv_today_count'] . "</td>
                        <td style='text-align:right'>" . $advCountBkg['booking_adv_tommrrow_count'] . "</td>
                    </tr>

                    <tr>
                        <td style='text-align:left'><b>#Trips booked [Tentative]</b></td>
                        <td style='text-align:right'>" . $tentativeBkg['booking_ten_last_week_count'] . "</td>
                        <td style='text-align:right'>" . $tentativeBkg['booking_ten_wtd_count'] . "</td>
                        <td style='text-align:right'>" . $tentativeBkg['booking_ten_today2_count'] . "</td>
                        <td style='text-align:right'>" . $tentativeBkg['booking_ten_today1_count'] . "</td>
                        <td style='text-align:right'>" . $tentativeBkg['booking_ten_today_count'] . "</td>
                        <td style='text-align:right'>" . $tentativeBkg['booking_ten_tommrrow_count'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#Confirm as cash</b></td>
                        <td style='text-align:right'>" . $confirmCash['confirm_cash_last_week_count'] . "</td>
                        <td style='text-align:right'>" . $confirmCash['confirm_cash_wtd_count'] . "</td>
                        <td style='text-align:right'>" . $confirmCash['confirm_cash_today2_count'] . "</td>
                        <td style='text-align:right'>" . $confirmCash['confirm_cash_today1_count'] . "</td>
                        <td style='text-align:right'>" . $confirmCash['confirm_cash_today_count'] . "</td>
                        <td style='text-align:right'>" . $confirmCash['confirm_cash_tommrrow_count'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Cancellations (create & cxl today)</b></td>
                        <td style='text-align:right'>" . $cancelToday['cancel_same_last_week_cnt'] . "</td>
                        <td style='text-align:right'>" . $cancelToday['cancel_same_wtd_cnt'] . "</td>
                        <td style='text-align:right'>" . $cancelToday['cancel_same_today2_cnt'] . "</td>
                        <td style='text-align:right'>" . $cancelToday['cancel_same_today1_cnt'] . "</td>
                        <td style='text-align:right'>" . $cancelToday['cancel_same_today_cnt'] . "</td>
                        <td style='text-align:right'>" . $cancelToday['cancel_same_tommrrow_cnt'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Cancellations (create anytime & cxl today)</b></td>
                        <td style='text-align:right'>" . $cancelCreateAnytime['cancel_last_week_cnt'] . "</td>
                        <td style='text-align:right'>" . $cancelCreateAnytime['cancel_wtd_cnt'] . "</td>
                        <td style='text-align:right'>" . $cancelCreateAnytime['cancel_today2_cnt'] . "</td>
                        <td style='text-align:right'>" . $cancelCreateAnytime['cancel_today1_cnt'] . "</td>
                        <td style='text-align:right'>" . $cancelCreateAnytime['cancel_today_cnt'] . "</td>
                        <td style='text-align:right'>" . $cancelCreateAnytime['cancel_tommrrow_cnt'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#Trips Matched</b></td>
                        <td style='text-align:right'>" . $tripMatched['booking_match_last_week_count'] . "</td>
                        <td style='text-align:right'>" . $tripMatched['booking_match_week_to_date_count'] . "</td>
                        <td style='text-align:right'>" . $tripMatched['booking_match_today2_count'] . "</td>
                        <td style='text-align:right'>" . $tripMatched['booking_match_today1_count'] . "</td>
                        <td style='text-align:right'>" . $tripMatched['booking_match_today_count'] . "</td>
                        <td style='text-align:right'>" . $tripMatched['booking_match_tommrrow_count'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>NPS Score</b></td>
                        <td style='text-align:right'>" . $npsScore['nps_last_week'] . "</td>
                        <td style='text-align:right'>" . (($npsScore['nps_wtd'] > 0) ? $npsScore['nps_wtd'] : '0') . "</td>
                        <td style='text-align:right'>" . (($npsScore['nps_today2'] > 0) ? $npsScore['nps_today2'] : '0') . "</td>
                        <td style='text-align:right'>" . (($npsScore['nps_today1'] > 0) ? $npsScore['nps_today1'] : '0') . "</td>
                        <td style='text-align:right'>" . (($npsScore['nps_today'] > 0) ? $npsScore['nps_today'] : '0') . "</td>
                        <td style='text-align:right'>NA</td>
                    </tr>";
		$html	 .= "</table><br/><br/>";
		return $html;
	}

	public function getBusinessTrendReportHtml()
	{
		$data		 = $this->getBusinessTrendReport();
		$month		 = date("m", strtotime(date('Y-m-d')));
		$month1		 = date("m", strtotime(" -1 months"));
		$month2		 = date("m", strtotime(" -2 months"));
		$monthAvg	 = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
		$month1Avg	 = cal_days_in_month(CAL_GREGORIAN, $month1, date('Y'));
		$month2Avg	 = cal_days_in_month(CAL_GREGORIAN, $month2, date('Y'));

		$yearFromDate	 = date("Y-01-01");
		$monthFromDate	 = date("Y-m-01");
		$toDate			 = date("Y-m-d");
		$filterObj		 = new Filter();
		$MTDDays		 = ($filterObj->dateCount($monthFromDate, $toDate) + 1);
		$YTDDays		 = ($filterObj->dateCount($yearFromDate, $toDate) + 1);

		$html	 = "<b>Business Trend Report : </b> (<i> By create date && status [2,3,4,5,6,7(9)] </i>)
                 <table width='90%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th width='20%'></th>
                        <th align='center'>Lifetime</th>
                        <th align='center'>YTD</th>
                        <th align='center'>Month –2</th>
                        <th align='center'>Month –1</th>
                        <th align='center'>Month to Date</th>
                        <th align='center'>Avg/day Month-2</th>
                        <th align='center'>Avg/day Month-1</th>
                        <th align='center'>Avg/day YTD</th>
                        <th align='center'>Avg/day MTD</th>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Bookings (Inc Cancel) </b></td>
                        <td style='text-align:right'>" . number_format($data['gmv_lifetime'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_ytd'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_month2'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_month1'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_mtd'], 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month2'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month1'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_ytd'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_mtd'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Bookings (Active+Completed) </b></td>
                        <td style='text-align:right'>" . number_format($data['gmv_lifetime_excl_cancel'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_ytd_excl_cancel'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_month2_excl_cancel'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_month1_excl_cancel'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_mtd_excl_cancel'], 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month2_excl_cancel'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month1_excl_cancel'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_ytd_excl_cancel'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_mtd_excl_cancel'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Bookings (Active) </b></td>
                        <td style='text-align:right'>" . number_format($data['gmv_lifetime_active'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_ytd_active'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_month2_active'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_month1_active'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_mtd_active'], 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month2_active'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month1_active'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_ytd_active'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_mtd_active'] / $MTDDays), 2) . "</td>
                    </tr>
                     <tr>
                        <td style='text-align:left'><b>Bookings (Completed) </b></td>
                        <td style='text-align:right'>" . number_format($data['gmv_lifetime_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_ytd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_month2_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_month1_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_mtd_comp'], 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month2_comp'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month1_comp'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_ytd_comp'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_mtd_comp'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Bookings (Advanced paid)</b></td>
                        <td style='text-align:right'>" . number_format($data['gmv_advance_lifetime'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_advance_ytd'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_advance_month2'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_advance_month1'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['gmv_advance_mtd'], 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_advance_month2'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_advance_month1'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_advance_ytd'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_advance_mtd'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Advance Payment Received</b></td>
                        <td style='text-align:right'>" . number_format($data['advance_lifetime'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['advance_ytd'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['advance_month2'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['advance_month1'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($data['advance_mtd'], 2) . "</td>
                        <td style='text-align:right'>" . round(($data['advance_month2'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . round(($data['advance_month1'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . round(($data['advance_ytd'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . round(($data['advance_mtd'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#bookings created (Inc Cancel)</b></td>
                        <td style='text-align:right'>" . $data['gmv_lifetime_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_ytd_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_month2_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_month1_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_mtd_cnt'] . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month2_cnt'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month1_cnt'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_ytd_cnt'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_mtd_cnt'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#bookings created (Active+Completed)</b></td>
                        <td style='text-align:right'>" . $data['gmv_lifetime_cnt_excl_cancel'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_ytd_cnt_excl_cancel'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_month2_cnt_excl_cancel'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_month1_cnt_excl_cancel'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_mtd_cnt_excl_cancel'] . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month2_cnt_excl_cancel'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month1_cnt_excl_cancel'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_ytd_cnt_excl_cancel'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_mtd_cnt_excl_cancel'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>B2C #bookings created (Active+Completed)</b></td>
                        <td style='text-align:right'>" . ($data['gmv_lifetime_cnt_excl_cancel'] - $data['gmv_lifetime_cnt_excl_cancel_b2b']) . "</td>
                        <td style='text-align:right'>" . ($data['gmv_ytd_cnt_excl_cancel'] - $data['gmv_ytd_cnt_excl_cancel_b2b']) . "</td>
                        <td style='text-align:right'>" . ($data['gmv_month2_cnt_excl_cancel'] - $data['gmv_month2_cnt_excl_cancel_b2b']) . "</td>
                        <td style='text-align:right'>" . ($data['gmv_month1_cnt_excl_cancel'] - $data['gmv_month1_cnt_excl_cancel_b2b']) . "</td>
                        <td style='text-align:right'>" . ($data['gmv_mtd_cnt_excl_cancel'] - $data['gmv_mtd_cnt_excl_cancel_b2b']) . "</td>
                        <td style='text-align:right'>" . number_format((($data['gmv_month2_cnt_excl_cancel'] - $data['gmv_month2_cnt_excl_cancel_b2b']) / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format((($data['gmv_month1_cnt_excl_cancel'] - $data['gmv_month1_cnt_excl_cancel_b2b']) / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format((($data['gmv_ytd_cnt_excl_cancel'] - $data['gmv_ytd_cnt_excl_cancel_b2b']) / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format((($data['gmv_mtd_cnt_excl_cancel'] - $data['gmv_mtd_cnt_excl_cancel_b2b']) / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>B2B #bookings created (Active+Completed)</b></td>
                        <td style='text-align:right'>" . $data['gmv_lifetime_cnt_excl_cancel_b2b'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_ytd_cnt_excl_cancel_b2b'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_month2_cnt_excl_cancel_b2b'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_month1_cnt_excl_cancel_b2b'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_mtd_cnt_excl_cancel_b2b'] . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month2_cnt_excl_cancel_b2b'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month1_cnt_excl_cancel_b2b'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_ytd_cnt_excl_cancel_b2b'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_mtd_cnt_excl_cancel_b2b'] / $MTDDays), 2) . "</td>
                    </tr>



                    <tr>
                        <td style='text-align:left'><b>#bookings created (Active)</b></td>
                        <td style='text-align:right'>" . $data['gmv_lifetime_cnt_active'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_ytd_cnt_active'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_month2_cnt_active'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_month1_cnt_active'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_mtd_cnt_active'] . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month2_cnt_active'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month1_cnt_active'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_ytd_cnt_active'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_mtd_cnt_active'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#bookings created (Completed)</b></td>
                        <td style='text-align:right'>" . $data['gmv_lifetime_cnt_comp'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_ytd_cnt_comp'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_month2_cnt_comp'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_month1_cnt_comp'] . "</td>
                        <td style='text-align:right'>" . $data['gmv_mtd_cnt_comp'] . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month2_cnt_comp'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_month1_cnt_comp'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_ytd_cnt_comp'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['gmv_mtd_cnt_comp'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#bookings created [advance payments]</b></td>
                        <td style='text-align:right'>" . $data['advance_lifetime_count'] . "</td>
                        <td style='text-align:right'>" . $data['advance_ytd_count'] . "</td>
                        <td style='text-align:right'>" . $data['advance_month2_count'] . "</td>
                        <td style='text-align:right'>" . $data['advance_month1_count'] . "</td>
                        <td style='text-align:right'>" . $data['advance_mtd_count'] . "</td>
                        <td style='text-align:right'>" . number_format(($data['advance_month2_count'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['advance_month1_count'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['advance_ytd_count'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['advance_mtd_count'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Bookings Complete (created anytime, by Pickup Date)</b></td>
                        <td style='text-align:right'>" . $data['comp_total'] . "</td>
                        <td style='text-align:right'>" . $data['comp_ytd'] . "</td>
                        <td style='text-align:right'>" . $data['comp_month2'] . "</td>
                        <td style='text-align:right'>" . $data['comp_month1'] . "</td>
                        <td style='text-align:right'>" . $data['comp_mtd'] . "</td>
                        <td style='text-align:right'>" . number_format(($data['comp_month2'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['comp_month1'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['comp_ytd'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['comp_mtd'] / $MTDDays), 2) . "</td>
                    </tr>

                    <tr>
                        <td style='text-align:left'><b>Bookings Cancelled ( New )</b></td>
                        <td style='text-align:right'>" . $data['cancel_lifetime_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['cancel_ytd_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['cancel_month2_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['cancel_month1_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['cancel_mtd_cnt'] . "</td>
                        <td style='text-align:right'>" . number_format(($data['cancel_month2_cnt'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['cancel_month1_cnt'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['cancel_ytd_cnt'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['cancel_mtd_cnt'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Bookings Cancelled ( Unverified )</b></td>
                        <td style='text-align:right'>" . $data['cancel_unv_lifetime_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['cancel_unv_ytd_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['cancel_unv_month2_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['cancel_unv_month1_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['cancel_unv_mtd_cnt'] . "</td>
                        <td style='text-align:right'>" . number_format(($data['cancel_unv_month2_cnt'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['cancel_unv_month1_cnt'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['cancel_unv_ytd_cnt'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['cancel_unv_mtd_cnt'] / $MTDDays), 2) . "</td>
                    </tr>

                    <tr>
                        <td style='text-align:left'><b>Reviews Received</b></td>
                        <td style='text-align:right'>" . $data['review_total'] . "</td>
                        <td style='text-align:right'>" . $data['review_ytd'] . "</td>
                        <td style='text-align:right'>" . $data['review_month2'] . "</td>
                        <td style='text-align:right'>" . $data['review_month1'] . "</td>
                        <td style='text-align:right'>" . $data['review_mtd'] . "</td>
                        <td style='text-align:right'>" . number_format(($data['review_month2'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['review_month1'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['review_ytd'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['review_mtd'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Trips Matched</b></td>
                        <td style='text-align:right'>" . $data['match_lifetime_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['match_ytd_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['match_month2_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['match_month1_cnt'] . "</td>
                        <td style='text-align:right'>" . $data['match_mtd_cnt'] . "</td>
                        <td style='text-align:right'>" . number_format(($data['match_month2_cnt'] / $month2Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['match_month1_cnt'] / $month1Avg), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['match_ytd_cnt'] / $YTDDays), 2) . "</td>
                        <td style='text-align:right'>" . number_format(($data['match_mtd_cnt'] / $MTDDays), 2) . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>NPS Score</b></td>
                        <td style='text-align:right'>" . $data['nps_lifetime'] . "</td>
                        <td style='text-align:right'>" . $data['nps_ytd'] . "</td>
                        <td style='text-align:right'>" . $data['nps_month2'] . "</td>
                        <td style='text-align:right'>" . $data['nps_month1'] . "</td>
                        <td style='text-align:right'>" . $data['nps_mtd'] . "</td>
                        <td style='text-align:right'>NA</td>
                        <td style='text-align:right'>NA</td>
                        <td style='text-align:right'>NA</td>
                        <td style='text-align:right'>NA</td>
                    </tr>";
		$html	 .= "</table><br/><br/>";
		return $html;
	}

	public function getVendorAssignmentReportHtml()
	{
		$data	 = $this->getVendorAssignmentReport();
		$html	 = "<b>Vendor Assignments : </b> (<i>Created anytime, Assigned by date and status [2,3,5,6,7,9]</i>)
                 <table width='70%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th width='40%'></th>
                        <th align='center' width='30%'>Manually</th>
                        <th align='center' width='30%'>Automatic</th>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#bookings assigned YTD</b></td>
                        <td style='text-align:right'>" . $data['manual_assigned_bookings_ytd'] . "</td>
                        <td style='text-align:right'>" . $data['system_assigned_bookings_ytd'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#bookings assigned Month –1</b></td>
                        <td style='text-align:right'>" . $data['manual_assigned_bookings_month1'] . "</td>
                        <td style='text-align:right'>" . $data['system_assigned_bookings_month1'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#bookings assigned MTD</b></td>
                        <td style='text-align:right'>" . $data['manual_assigned_bookings_mtd'] . "</td>
                        <td style='text-align:right'>" . $data['system_assigned_bookings_mtd'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#bookings assigned today-2</b></td>
                        <td style='text-align:right'>" . $data['manual_assigned_bookings_now2'] . "</td>
                        <td style='text-align:right'>" . $data['system_assigned_bookings_now2'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#bookings assigned today-1</b></td>
                        <td style='text-align:right'>" . $data['manual_assigned_bookings_now1'] . "</td>
                        <td style='text-align:right'>" . $data['system_assigned_bookings_now1'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>#bookings assigned today</b></td>
                        <td style='text-align:right'>" . $data['manual_assigned_bookings_now'] . "</td>
                        <td style='text-align:right'>" . $data['system_assigned_bookings_now'] . "</td>
                    </tr>";
		$html	 .= "</table><br/><br/>";
		return $html;
	}

	public function getRegionalBookingDistributionHtml()
	{
		$rows	 = $this->getRegionalBookingDist();
		$html	 = "<b>Regional booking trend : </b> (<i>By Create Date and status [2,3,5,6,7,9]</i>)
                 <table width='90%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th align='center'>Bookings Created</th>
                        <th align='center'>Today</th>
                        <th align='center'>Today-1</th>
                        <th align='center'>Today-2</th>
                        <th align='center'>Today-3</th>
                        <th align='center'>MTD</th>
                        <th align='center'>Month-1</th>
                        <th align='center'>Month-2</th>
                        <th align='center'>YTD</th>
                        <th align='center'>Year-1</th>
                    </tr>";
		if(count($rows) > 0)
		{
			$sum_today	 = $sum_today1	 = $sum_today2	 = $sum_today3	 = $sum_mtd	 = $sum_month1	 = $sum_month2	 = $sum_ytd	 = $sum_year1	 = 0;
			foreach($rows as $row)
			{
				$html		 .= "<tr>
                            <th style='text-align:left;padding-left:50px;'>" . ($row['region']) . "</th>
                            <td style='text-align:center'>" . $row['today'] . "</td>
                            <td style='text-align:center'>" . $row['today1'] . "</td>
                            <td style='text-align:center'>" . $row['today2'] . "</td>
                            <td style='text-align:center'>" . $row['today3'] . "</td>
                            <td style='text-align:center'>" . $row['mtd'] . "</td>
                            <td style='text-align:center'>" . $row['month1'] . "</td>
                            <td style='text-align:center'>" . $row['month2'] . "</td>
                            <td style='text-align:center'>" . $row['ytd'] . "</td>
                            <td style='text-align:center'>" . $row['year1'] . "</td>
                         </tr>";
				$sum_today	 = ($sum_today + $row['today']);
				$sum_today1	 = ($sum_today1 + $row['today1']);
				$sum_today2	 = ($sum_today2 + $row['today2']);
				$sum_today3	 = ($sum_today3 + $row['today3']);
				$sum_mtd	 = ($sum_mtd + $row['mtd']);
				$sum_month1	 = ($sum_month1 + $row['month1']);
				$sum_month2	 = ($sum_month2 + $row['month2']);
				$sum_ytd	 = ($sum_ytd + $row['ytd']);
				$sum_year1	 = ($sum_year1 + $row['year1']);
			}
			$html .= "<tr>
                        <th style='text-align:left;padding-left:50px;'>Total</th>
                        <td style='text-align:center'>" . $sum_today . "</td>
                        <td style='text-align:center'>" . $sum_today1 . "</td>
                        <td style='text-align:center'>" . $sum_today2 . "</td>
                        <td style='text-align:center'>" . $sum_today3 . "</td>
                        <td style='text-align:center'>" . $sum_mtd . "</td>
                        <td style='text-align:center'>" . $sum_month1 . "</td>
                        <td style='text-align:center'>" . $sum_month2 . "</td>
                        <td style='text-align:center'>" . $sum_ytd . "</td>
                        <td style='text-align:center'>" . $sum_year1 . "</td>
                    </tr>";
		}
		$html .= "</table><br/><br/>";
		return $html;
	}

	public function getSmartMatchHtml()
	{
		$rows	 = $this->getSmartMatchList(1, true, 0, 1);
		$html	 = "<b>Smart Match update (today-1) </b>
                 <table width='90%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th align='center'>Trip Id</th>
                        <th align='center'>Booking Id(s)</th>
                        <th align='center'>From City(s)</th>
                        <th align='center'>To City(s)</th>
                        <th align='center'>Trip Amount</th>
                        <th align='center'>Vendor Amount Original</th>
                        <th align='center'>Vendor Amount Matched</th>
                        <th align='center'>GST</th>
                        <th align='center'>Gozo Amount</th>
                        <th align='center'>Gozo Amount Matched</th>
                        <th align='center'>Margin Original</th>
                        <th align='center'>Margin Matched</th>
                        <th align='center'>Date</th>
                        <th align='center'>Match Type</th>
                        <th align='center'>Matched By</th>
                    </tr>";
		if(count($rows) > 0)
		{
			foreach($rows as $row)
			{
				$html .= "<tr>
                            <td style='text-align:left;padding-left:50px;'>" . ($row['trip_id']) . "</td>
                            <td style='text-align:center'>" . $row['booking_ids'] . "</td>
                            <td style='text-align:center'>" . $row['from_city_ids'] . "</td>
                            <td style='text-align:center'>" . $row['to_city_ids'] . "</td>
                            <td style='text-align:right'>" . $row['trip_amount'] . "</td>
                            <td style='text-align:right'>" . $row['vendor_amount_original'] . "</td>
                            <td style='text-align:right'>" . $row['vendor_amount_smart_match'] . "</td>
                            <td style='text-align:right'>" . $row['service_tax_amount'] . "</td>
                            <td style='text-align:right'>" . $row['gozo_amount_original'] . "</td>
                            <td style='text-align:right'>" . $row['gozo_amount_smart_match'] . "</td>
                            <td style='text-align:center'>" . ($row['margin_original'] * 100) . "%</td>
                            <td style='text-align:center'>" . ($row['margin_smart_match'] * 100) . "%</td>
                            <td style='text-align:center'>" . $row['match_date'] . "</td>
                            <td style='text-align:center'>" . $row['matchtype'] . "</td>
                            <td style='text-align:center'>" . $row['name'] . "</td>
                         </tr>";
			}
		}
		else
		{
			$html .= "<tr><td colspan=15>No Smart Match Found.</td></tr>";
		}
		$html .= "</table><br/><br/>";
		return $html;
	}

	public function getZoneCancellationReportHtml($city = 'from')
	{
		$rows	 = $this->getZoneCancellationReport($city, true);
		$html	 = "<b>Zonal Cancellation : </b> (<i> By pickup date && $city city </i>)
                 <table width='90%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th align='left'>Zone</th>
                        <th align='left' colspan='3'>MTD</th>
                        <th align='left' colspan='3'>Month-1</th>
                        <th align='left' colspan='3'>Month-2</th>
                        <th align='left' colspan='3'>YTD</th>
                    </tr>
                    <tr>
                        <th align='left'></th>
                        <th align='left'>Created</th>
                        <th align='center'>Cancelled</th>
                        <th align='center'>Cancellation%</th>
                        <th align='left'>Created</th>
                        <th align='center'>Cancelled</th>
                        <th align='center'>Cancellation%</th>
                        <th align='left'>Created</th>
                        <th align='center'>Cancelled</th>
                        <th align='center'>Cancellation%</th>
                        <th align='left'>Created</th>
                        <th align='center'>Cancelled</th>
                        <th align='center'>Cancellation%</th>
                    </tr>";
		if(count($rows) > 0)
		{
			foreach($rows as $row)
			{
				$html .= "<tr>
                            <td style='text-align:left;'>" . ($row['zon_name']) . "</td>
                            <td style='text-align:center'>" . $row['createdMtd'] . "</td>
                            <td style='text-align:center'>" . $row['cancelMtd'] . "</td>
                            <td style='text-align:center'>" . $row['cancelMtdRatio'] . "</td>
                            <td style='text-align:center'>" . $row['createdMonth1'] . "</td>
                            <td style='text-align:center'>" . $row['cancelMonth1'] . "</td>
                            <td style='text-align:center'>" . $row['cancelMonth1Ratio'] . "</td>
                            <td style='text-align:center'>" . $row['createdMonth2'] . "</td>
                            <td style='text-align:center'>" . $row['cancelMonth2'] . "</td>
                            <td style='text-align:center'>" . $row['cancelMonth2Ratio'] . "</td>
                            <td style='text-align:center'>" . $row['createdYtd'] . "</td>
                            <td style='text-align:center'>" . $row['cancelYtd'] . "</td>
                            <td style='text-align:center'>" . $row['cancelYtdRatio'] . "</td>
                         </tr>";
			}
		}
		else
		{
			$html .= "<tr><td colspan=13>No Records Found.</td></tr>";
		}
		$html .= "</table><br/><br/>";
		return $html;
	}

	public function getActiveBookingHtml()
	{
		$currentMonth	 = (int) date('m');
		$totalCount		 = 0;
		$html			 = "<b>Active Bookings In System :</b> (<i>By Pickup Date and status [2,3,5,6,7]</i>) : <br/>
                   <table width='90%' border='1px' cellpadding='5' cellpadding='5' style=\"border-collapse: collapse;\" >
                       <tr>
                           <th>&nbsp;</th>";
		for($x = $currentMonth; $x < $currentMonth + 12; $x++)
		{
			$html .= "<th>" . date('M', mktime(0, 0, 0, $x, 1)) . "</th>";
		}
		$html	 .= "<th>Total</th>";
		$html	 .= "</tr>";
		$html	 .= "<tr>
                   <th style='text-align:center'>COUNT</th>";

		for($x = $currentMonth, $ctr = 0; $x < $currentMonth + 12; $x++, $ctr++)
		{

			$curYear	 = date('Y', strtotime('+' . $ctr . ' months'));
			$curMonth	 = $x;

			$cout		 = $this->getActiveBookingByMY('', $curMonth, $curYear);
			$cout		 = ($cout > 0) ? $cout : 0;
			$totalCount	 = ($totalCount + $cout);
			$html		 .= "<td style='text-align:center'>$cout</td>";
		}
		$html	 .= '<th>' . $totalCount . '</th>';
		$html	 .= "</tr></table><br/><br/>";
		return $html;
	}

	public function getBookingCreatedPatternHtml()
	{


		$data1	 = $this->bookingCreatedByToday();
		$data2	 = $this->bookingCreatedByToday1();
		$data3	 = $this->bookingCreatedByToday2();
		$data4	 = $this->bookingCreatedByWtd();
		$data5	 = $this->bookingCreatedByMonth();

		$totalMtd	 = ($data5['totalBook1'] + $data5['totalBook2'] + $data5['totalBook3'] + $data5['totalBook4'] + $data5['totalBook5'] + $data5['totalBook6'] + $data5['totalBook7'] + $data5['totalBook8'] + $data5['totalBook9'] + $data5['totalBook10'] + $data5['totalBook11'] + $data5['totalBook12']);
		$totalWtd	 = ($data4['totalBook1'] + $data4['totalBook2'] + $data4['totalBook3'] + $data4['totalBook4'] + $data4['totalBook5'] + $data4['totalBook6'] + $data4['totalBook7'] + $data4['totalBook8'] + $data4['totalBook9'] + $data4['totalBook10'] + $data4['totalBook11'] + $data4['totalBook12']);
		$totalToday2 = ($data3['totalBook1'] + $data3['totalBook2'] + $data3['totalBook3'] + $data3['totalBook4'] + $data3['totalBook5'] + $data3['totalBook6'] + $data3['totalBook7'] + $data3['totalBook8'] + $data3['totalBook9'] + $data3['totalBook10'] + $data3['totalBook11'] + $data3['totalBook12']);
		$totalToday1 = ($data2['totalBook1'] + $data2['totalBook2'] + $data2['totalBook3'] + $data2['totalBook4'] + $data2['totalBook5'] + $data2['totalBook6'] + $data2['totalBook7'] + $data2['totalBook8'] + $data2['totalBook9'] + $data2['totalBook10'] + $data2['totalBook11'] + $data2['totalBook12']);
		$totalToday	 = ($data1['totalBook1'] + $data1['totalBook2'] + $data1['totalBook3'] + $data1['totalBook4'] + $data1['totalBook5'] + $data1['totalBook6'] + $data1['totalBook7'] + $data1['totalBook8'] + $data1['totalBook9'] + $data1['totalBook10'] + $data1['totalBook11'] + $data1['totalBook12']);
		$html		 = "<b>Bookings creation pattern :</b> (<i>By Pickup Date and status [2,3,5,6,7]</i>) : <br/>
                    <table width='90%' border='1px' cellpadding='5' cellpadding='5' style=\"border-collapse: collapse;\" >
                        <tr>
                            <th style='text-align:center'>Created</th>
                            <th style='text-align:center'>" . $data5['monthName1'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName2'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName3'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName4'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName5'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName6'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName7'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName8'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName9'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName10'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName11'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName12'] . "</th><th>&nbsp;</th>";
		$html		 .= "</tr>";
		$html		 .= "<tr>
                    <td style='text-align:center'>This Month</td>
                    <td style='text-align:center'>" . $data5['totalBook1'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook2'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook3'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook4'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook5'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook6'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook7'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook8'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook9'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook10'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook11'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook12'] . "</td>";
		$html		 .= "<td style='text-align:center'>" . ($totalMtd) . "</td></tr>";
		$html		 .= "<tr>
                    <td style='text-align:center'>This Week</td>
                    <td style='text-align:center'>" . $data4['totalBook1'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook2'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook3'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook4'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook5'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook6'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook7'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook8'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook9'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook10'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook11'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook12'] . "</td>";
		$html		 .= "<td style='text-align:center'>" . $totalWtd . "</td></tr>";
		$html		 .= "<tr>
                    <td style='text-align:center'>Today-2</td>
                    <td style='text-align:center'>" . $data3['totalBook1'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook2'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook3'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook4'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook5'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook6'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook7'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook8'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook9'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook10'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook11'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook12'] . "</td>";
		$html		 .= "<td style='text-align:center'>" . $totalToday2 . "</td></tr>";
		$html		 .= "<tr>
                    <td style='text-align:center'>Today-1</td>
                    <td style='text-align:center'>" . $data2['totalBook1'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook2'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook3'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook4'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook5'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook6'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook7'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook8'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook9'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook10'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook11'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook12'] . "</td>";
		$html		 .= "<td style='text-align:center'>" . $totalToday1 . "</td></tr>";
		$html		 .= "<tr>
                    <td style='text-align:center'>Today</td>
                    <td style='text-align:center'>" . $data1['totalBook1'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook2'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook3'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook4'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook5'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook6'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook7'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook8'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook9'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook10'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook11'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook12'] . "</td>";
		$html		 .= "<td style='text-align:center'>" . $totalToday . "</td></tr>";
		$html		 .= "</table><br/><br/>";
		return $html;
	}

	public function getBookingCancellationPatternHtml()
	{
		$data1		 = $this->bookingCreatedByToday(1);
		$data2		 = $this->bookingCreatedByToday1(1);
		$data3		 = $this->bookingCreatedByToday2(1);
		$data4		 = $this->bookingCreatedByWtd(1);
		$data5		 = $this->bookingCreatedByMonth(1);
		$data6		 = $this->bookingCreatedByMonth1(1);
		$totalMonth1 = ($data6['totalBook1'] + $data6['totalBook2'] + $data6['totalBook3'] + $data6['totalBook4'] + $data6['totalBook5'] + $data6['totalBook6'] + $data6['totalBook7'] + $data6['totalBook8'] + $data6['totalBook9'] + $data6['totalBook10'] + $data6['totalBook11'] + $data6['totalBook12']);
		$totalMtd	 = ($data5['totalBook1'] + $data5['totalBook2'] + $data5['totalBook3'] + $data5['totalBook4'] + $data5['totalBook5'] + $data5['totalBook6'] + $data5['totalBook7'] + $data5['totalBook8'] + $data5['totalBook9'] + $data5['totalBook10'] + $data5['totalBook11'] + $data5['totalBook12']);
		$totalWtd	 = ($data4['totalBook1'] + $data4['totalBook2'] + $data4['totalBook3'] + $data4['totalBook4'] + $data4['totalBook5'] + $data4['totalBook6'] + $data4['totalBook7'] + $data4['totalBook8'] + $data4['totalBook9'] + $data4['totalBook10'] + $data4['totalBook11'] + $data4['totalBook12']);
		$totalToday2 = ($data3['totalBook1'] + $data3['totalBook2'] + $data3['totalBook3'] + $data3['totalBook4'] + $data3['totalBook5'] + $data3['totalBook6'] + $data3['totalBook7'] + $data3['totalBook8'] + $data3['totalBook9'] + $data3['totalBook10'] + $data3['totalBook11'] + $data3['totalBook12']);
		$totalToday1 = ($data2['totalBook1'] + $data2['totalBook2'] + $data2['totalBook3'] + $data2['totalBook4'] + $data2['totalBook5'] + $data2['totalBook6'] + $data2['totalBook7'] + $data2['totalBook8'] + $data2['totalBook9'] + $data2['totalBook10'] + $data2['totalBook11'] + $data2['totalBook12']);
		$totalToday	 = ($data1['totalBook1'] + $data1['totalBook2'] + $data1['totalBook3'] + $data1['totalBook4'] + $data1['totalBook5'] + $data1['totalBook6'] + $data1['totalBook7'] + $data1['totalBook8'] + $data1['totalBook9'] + $data1['totalBook10'] + $data1['totalBook11'] + $data1['totalBook12']);
		$html		 = "<b>Bookings cancellation pattern :</b> (<i>By Pickup Date and status [9]</i>) : <br/>
                    <table width='90%' border='1px' cellpadding='5' cellpadding='5' style=\"border-collapse: collapse;\" >
                        <tr>
                            <th style='text-align:center'>Created</th>
                            <th style='text-align:center'>" . $data5['monthName1'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName2'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName3'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName4'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName5'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName6'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName7'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName8'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName9'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName10'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName11'] . "</th>
                            <th style='text-align:center'>" . $data5['monthName12'] . "</th><th>&nbsp;</th>";
		$html		 .= "</tr>";
		$html		 .= "<tr>
                    <td style='text-align:center'>Created in past months</td>
                    <td style='text-align:center'>" . $data6['totalBook1'] . "</td>
                    <td style='text-align:center'>" . $data6['totalBook2'] . "</td>
                    <td style='text-align:center'>" . $data6['totalBook3'] . "</td>
                    <td style='text-align:center'>" . $data6['totalBook4'] . "</td>
                    <td style='text-align:center'>" . $data6['totalBook5'] . "</td>
                    <td style='text-align:center'>" . $data6['totalBook6'] . "</td>
                    <td style='text-align:center'>" . $data6['totalBook7'] . "</td>
                    <td style='text-align:center'>" . $data6['totalBook8'] . "</td>
                    <td style='text-align:center'>" . $data6['totalBook9'] . "</td>
                    <td style='text-align:center'>" . $data6['totalBook10'] . "</td>
                    <td style='text-align:center'>" . $data6['totalBook11'] . "</td>
                    <td style='text-align:center'>" . $data6['totalBook12'] . "</td>";
		$html		 .= "<td style='text-align:center'>" . ($totalMonth1) . "</td></tr>";
		$html		 .= "<tr>
                    <td style='text-align:center'>This Month</td>
                    <td style='text-align:center'>" . $data5['totalBook1'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook2'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook3'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook4'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook5'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook6'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook7'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook8'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook9'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook10'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook11'] . "</td>
                    <td style='text-align:center'>" . $data5['totalBook12'] . "</td>";
		$html		 .= "<td style='text-align:center'>" . ($totalMtd) . "</td></tr>";
		$html		 .= "<tr>
                    <td style='text-align:center'>This Week</td>
                    <td style='text-align:center'>" . $data4['totalBook1'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook2'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook3'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook4'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook5'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook6'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook7'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook8'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook9'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook10'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook11'] . "</td>
                    <td style='text-align:center'>" . $data4['totalBook12'] . "</td>";
		$html		 .= "<td style='text-align:center'>" . $totalWtd . "</td></tr>";
		$html		 .= "<tr>
                    <td style='text-align:center'>Today-2</td>
                    <td style='text-align:center'>" . $data3['totalBook1'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook2'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook3'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook4'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook5'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook6'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook7'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook8'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook9'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook10'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook11'] . "</td>
                    <td style='text-align:center'>" . $data3['totalBook12'] . "</td>";
		$html		 .= "<td style='text-align:center'>" . $totalToday2 . "</td></tr>";
		$html		 .= "<tr>
                    <td style='text-align:center'>Today-1</td>
                    <td style='text-align:center'>" . $data2['totalBook1'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook2'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook3'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook4'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook5'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook6'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook7'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook8'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook9'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook10'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook11'] . "</td>
                    <td style='text-align:center'>" . $data2['totalBook12'] . "</td>";
		$html		 .= "<td style='text-align:center'>" . $totalToday1 . "</td></tr>";
		$html		 .= "<tr>
                    <td style='text-align:center'>Today</td>
                    <td style='text-align:center'>" . $data1['totalBook1'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook2'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook3'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook4'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook5'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook6'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook7'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook8'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook9'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook10'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook11'] . "</td>
                    <td style='text-align:center'>" . $data1['totalBook12'] . "</td>";
		$html		 .= "<td style='text-align:center'>" . $totalToday . "</td></tr>";
		$html		 .= "</table><br/><br/>";
		return $html;
	}

	public function sendReconfirmSms($bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if($model->bkgUserInfo->bkg_contact_no != '')
		{
			/* var @model smsWrappper */
			$msgCom		 = new smsWrapper();
			$hash		 = Yii::app()->shortHash->hash($model->bkg_id);
			$url		 = 'gozocabs.com/bkconfirm/' . $model->bkg_id . '/' . $hash;
			$pickupTime	 = strtotime($model->bkg_pickup_date);
			$timeLeft	 = round(($pickupTime - time()) / 3600);
			$txtChanges	 = $model->bkg_booking_id . ' starts in ' . $timeLeft . ' hours. Reconfirm at ' . $url . ' or by phone.';
			$smsChanges	 = 'Trip ' . $txtChanges . ' Trip will auto-cancel and you will be liable for cancellation charge if not reconfirmed 24hours before pickup. - Gozocabs';
			$msgCom->beforePickUpSmsCustomer('91', $model->bkgUserInfo->bkg_contact_no, $model->bkg_booking_id, $smsChanges, $model->bkg_id);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function sendReconfirmEmail($bkgId)
	{
		/* @var $model Booking */
		$model = Booking::model()->findByPk($bkgId);

		if($model->bkgUserInfo->bkg_user_email != '')
		{
			/* var @model emailWrapper */
			$emailCom = new emailWrapper();
			$emailCom->beforePickUpMail($bkgId);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function reminderOnPriceGuarantee()
	{
		$sql = "SELECT  booking.bkg_id,
                        DATE(booking.bkg_pickup_date) as pickupDate, DATE(DATE_ADD(NOW(), INTERVAL 90 DAY)) as add90Day,
                        DATE(DATE_ADD(NOW(), INTERVAL 60 DAY)) as add60Day, DATE(DATE_ADD(NOW(), INTERVAL 45 DAY)) as add45Day,
                        DATE(DATE_ADD(NOW(), INTERVAL 30 DAY)) as add30Day, DATE(DATE_ADD(NOW(), INTERVAL 15 DAY)) as add15Day,
                        DATE(DATE_ADD(NOW(), INTERVAL 10 DAY)) as add10Day, DATE(DATE_ADD(NOW(), INTERVAL 5 DAY)) as add5Day,
                        last_date, CURDATE()
                FROM `booking`
                JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                LEFT JOIN (
                    SELECT DATE(MAX(email_log.elg_created)) as last_date,
                    email_log.elg_ref_id
                    FROM `email_log` WHERE email_log.elg_type=24
                    GROUP BY email_log.elg_ref_id
                )log ON log.elg_ref_id=booking.bkg_id
                WHERE booking.bkg_active=1
                AND biv.bkg_advance_amount<=0
                AND (booking.bkg_agent_id IS NULL OR booking.bkg_agent_id='')
                AND booking.bkg_status IN (2,3,5,6,7)
                AND (
                        DATE(booking.bkg_pickup_date) = DATE(DATE_ADD(NOW(), INTERVAL 90 DAY))
                    OR
                        DATE(booking.bkg_pickup_date) = DATE(DATE_ADD(NOW(), INTERVAL 60 DAY))
                    OR
                        DATE(booking.bkg_pickup_date) = DATE(DATE_ADD(NOW(), INTERVAL 45 DAY))
                    OR
                        DATE(booking.bkg_pickup_date) = DATE(DATE_ADD(NOW(), INTERVAL 30 DAY))
                    OR
                        DATE(booking.bkg_pickup_date) = DATE(DATE_ADD(NOW(), INTERVAL 15 DAY))
                    OR
                        DATE(booking.bkg_pickup_date) = DATE(DATE_ADD(NOW(), INTERVAL 10 DAY))
                    OR
                        DATE(booking.bkg_pickup_date) = DATE(DATE_ADD(NOW(), INTERVAL 5 DAY))
                )
                HAVING (
                    DATE_ADD(last_date, INTERVAL 5 DAY) != add5Day AND CURDATE() > last_date
                    OR DATE_ADD(last_date, INTERVAL 10 DAY) != add10Day AND CURDATE() > last_date
                    OR DATE_ADD(last_date, INTERVAL 15 DAY) != add15Day AND CURDATE() > last_date
                    OR DATE_ADD(last_date, INTERVAL 30 DAY) != add30Day AND CURDATE() > last_date
                    OR DATE_ADD(last_date, INTERVAL 45 DAY) != add45Day AND CURDATE() > last_date
                    OR DATE_ADD(last_date, INTERVAL 60 DAY) != add60Day AND CURDATE() > last_date
                    OR DATE_ADD(last_date, INTERVAL 90 DAY) != add90Day AND CURDATE() > last_date
                    OR last_date IS NULL
                )
                ORDER BY booking.bkg_id DESC";
		return DBUtil::queryAll($sql);
	}

	public function getCancellationList($model, $type = DBUtil::ReturnType_Provider, $agents = '', $btocbooking = '')
	{
		$date1				 = $model->bkg_create_date1;
		$date2				 = $model->bkg_create_date2;
		$region				 = $model->bkg_region;
		$cancelCustomer		 = $model->bkgCancelCustomer;
		$cancelAdmin		 = $model->bkgCancelAdmin;
		$cancelAgent		 = $model->bkgCancelAgent;
		$cancelSystem		 = $model->bkgCancelSystem;
		$searchIsDBO		 = $model->searchIsDBO;
		$IsGozoCancel		 = $model->IsGozoCancel;
		$IsCustomerCancel	 = $model->IsCustomerCancel;
		$sameDayCancellation = $model->sameDayCancellation;
		$isGozonow			 = $model->isGozonow;
		$bkgCancelId		 = $model->bkg_cancel_id;
		$condition			 = "";
		if($date1 != '' && $date2 != '')
		{
			$condition = "(booking.bkg_pickup_date BETWEEN '$date1 00:00:00' AND '$date2 23:59:59')";
		}
		else
		{
			$condition = "(booking.bkg_pickup_date BETWEEN CONCAT(CURDATE(),' 00:00:00') AND CONCAT(CURDATE(), ' 23:59:59'))";
		}
		$userTypes	 = array();
		$where		 = '';
		$having		 = '';

		if(isset($region) && $region != '')
		{
			$sqlState	 = " JOIN `cities` fromCity ON booking.bkg_from_city_id = fromCity.cty_id
						      JOIN `states` ON states.stt_id = fromCity.cty_state_id ";
			$setRegion	 = ($region == 4) ? '4,7' : $region;
			$where		 .= " AND states.stt_zone IN ($setRegion)";
		}
		if(isset($searchIsDBO) && $searchIsDBO > 0 && $sameDayCancellation == 0)
		{
			$sqlDbo	 = "   JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id	";
			$where	 .= "  AND DATE(booking.bkg_create_date)<=DATE(booking_trail.btr_cancel_date)  AND   booking_trail.btr_is_dbo_applicable=" . $searchIsDBO . "";
		}
		else if(isset($searchIsDBO) && $searchIsDBO > 0 && $sameDayCancellation == 2)
		{
			$sqlDbo	 = "  JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id	";
			$where	 .= " AND DATE(booking.bkg_create_date)<>DATE(booking_trail.btr_cancel_date)  AND   booking_trail.btr_is_dbo_applicable=" . $searchIsDBO . "";
		}
		else if(isset($searchIsDBO) && $searchIsDBO > 0 && $sameDayCancellation == 1)
		{
			$sqlDbo	 = "  JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id ";
			$where	 .= " AND DATE(booking.bkg_create_date)= DATE(booking_trail.btr_cancel_date)  AND   booking_trail.btr_is_dbo_applicable=" . $searchIsDBO . "";
		}
		else if(isset($searchIsDBO) && $searchIsDBO > 0)
		{
			$sqlDbo	 = "  JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id ";
			$where	 .= "  AND  booking_trail.btr_is_dbo_applicable=" . $searchIsDBO . "";
		}
		else if($sameDayCancellation == 0)
		{
			$sqlDbo	 = "  JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id ";
			$where	 .= " AND DATE(booking.bkg_create_date)<=DATE(booking_trail.btr_cancel_date)";
		}
		else if($sameDayCancellation == 1)
		{
			$sqlDbo	 = "  JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id  ";
			$where	 .= " AND DATE(booking.bkg_create_date)=DATE(booking_trail.btr_cancel_date)";
		}
		else if($sameDayCancellation == 2)
		{
			$sqlDbo	 = "  JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id  ";
			$where	 .= " AND DATE(booking.bkg_create_date)<>DATE(booking_trail.btr_cancel_date)";
		}

		if(count($model->bkg_service_class) > 0 && $model->bkg_service_class != '')
		{
			$svcType = implode(",", $model->bkg_service_class);
			$where	 .= " AND scv.scv_scc_id IN ($svcType)";
		}

		if($IsGozoCancel > 0 && $IsCustomerCancel == 0)
		{
			$where .= " AND cancel_reasons.cnr_id IN(3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38,40)";
		}
		if($IsCustomerCancel > 0 && $IsGozoCancel == 0)
		{
			$where .= " AND cancel_reasons.cnr_id IN(1,2,4,5,6,7,10,11,12,13,14,15,18,21,23,24,25,31,32)";
		}
		if($IsGozoCancel > 0 && $IsCustomerCancel > 0)
		{
			$where .= "AND (cancel_reasons.cnr_id IN(3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38,40) OR cancel_reasons.cnr_id IN(1,2,4,5,6,7,10,11,12,13,14,15,18,21,23,24,25,31,32))";
		}
		if($bkgCancelId > 0)
		{
			$where .= " AND cancel_reasons.cnr_id IN ({$bkgCancelId}) ";
		}
		if($agents->agt_id > 0)
		{
			$where .= " AND agents.agt_id = $agents->agt_id ";
		}
		if($isGozonow == 1)
		{
			$where .= " AND bpr.bkg_is_gozonow = $isGozonow ";
		}
		if($btocbooking == 1)
		{
			$where .= " AND booking.bkg_agent_id IS NULL ";
		}
		if($cancelCustomer == 1 || $cancelAdmin == 1 || $cancelAgent == 1 || $cancelSystem == 1)
		{
			if($cancelCustomer == 1)
			{
				$userTypes[] = 1;
			}
			if($cancelAdmin == 1)
			{
				$userTypes[] = 4;
			}
			if($cancelAgent == 1)
			{
				$userTypes[] = 5;
			}
			if($cancelSystem == 1)
			{
				$userTypes[] = 10;
			}
			$having = ' AND (booking_trail.bkg_cancel_user_type IN (' . implode(', ', $userTypes) . '))';
		}
		if(sizeof($model->bkgtypes) > 0)
		{
			$bkgtypes	 = implode(',', $model->bkgtypes);
			$where		 .= " AND (bkg_booking_type IN ($bkgtypes))";
		}

		$sql = "SELECT
				booking.bkg_id
				,'' as workingHour
				, booking_trail.bkg_cancel_user_type
				, booking.bkg_booking_id
				, booking.bkg_booking_type
				, booking.bkg_create_date
				, booking.bkg_pickup_date
				, btk.bkg_trip_arrive_time as arrive_time
                , booking_trail.btr_cancel_date
				, booking_invoice.bkg_total_amount
				, booking.bkg_cancel_delete_reason
                , booking.bkg_agent_id
				, CONCAT(booking_user.bkg_user_fname, ' ', booking_user.bkg_user_lname) AS username
				, cancel_reasons.cnr_reason
				, CONCAT(fromCity.cty_name, ' -> ', toCity.cty_name) AS booking_route
                , CONCAT(agents.agt_fname, ' ', agents.agt_lname) AS agent_name
                , agents.agt_company
				, states.stt_zone
				, IF(booking_trail.btr_is_dbo_applicable > 0, 'ON', 'OFF') AS is_dbo
				, booking_trail.btr_dbo_amount
				, booking_invoice.bkg_cust_compensation_amount
				, IF((booking.bkg_cancel_id = 17 || booking.bkg_cancel_id = 9) AND booking_trail.btr_is_dbo_applicable = 1 AND (booking_trail.btr_cancel_date < DATE_SUB(booking.bkg_pickup_date, INTERVAL 5 DAY) OR booking_trail.btr_cancel_date > DATE_ADD(booking.bkg_create_date, INTERVAL 24 HOUR)), booking_trail.btr_dbo_amount, 0) AS refund_amount
			   ,(CASE booking_trail.bkg_cancel_user_type
				WHEN 1 THEN CONCAT('Customer -', ' ', booking_user.bkg_user_fname,' ',booking_user.bkg_user_lname)
				WHEN 4 THEN CONCAT('CSR -',' ',admins.adm_fname,' ',admins.adm_lname)
				WHEN 5 THEN CONCAT('Partner -',' ',agents.agt_company)
				WHEN 10 THEN 'System : auto-cancelled'
				END) as cancelBy,
				scv.scv_scc_id,
				bkg_cancel_charge,
                bkg_agent_ref_code
				FROM `booking`
				JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
				JOIN `booking_invoice` ON booking_invoice.biv_bkg_id = booking.bkg_id
				JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id
				JOIN `booking_pref` bpr ON bpr.bpr_bkg_id = booking.bkg_id
				INNER JOIN booking_track btk ON btk.btk_bkg_id = booking.bkg_id
				JOIN `booking_user` ON booking_user.bui_bkg_id = booking.bkg_id
				JOIN `cancel_reasons` ON cancel_reasons.cnr_id = booking.bkg_cancel_id
				JOIN `cities` fromCity ON booking.bkg_from_city_id = fromCity.cty_id
				JOIN `states` ON states.stt_id = fromCity.cty_state_id
				JOIN `cities` toCity ON booking.bkg_to_city_id = toCity.cty_id
				LEFT JOIN `admins` ON admins.adm_id=booking_trail.bkg_cancel_user_id
				LEFT JOIN `agents` ON agents.agt_id=booking.bkg_agent_id
				WHERE $condition AND booking.bkg_active = 1 AND booking.bkg_status IN(9)
                $where $having
				";

		if($type == DBUtil::ReturnType_Provider)
		{
			$dataprovider	 = array();
			$sqlCount		 = "SELECT
								CASE
									WHEN temp.bkg_cancel_user_type = 1 THEN 'Customer'
									WHEN temp.bkg_cancel_user_type = 4 THEN 'Admin'
									WHEN temp.bkg_cancel_user_type =5 THEN 'Agent'
									WHEN temp.bkg_cancel_user_type = 10 THEN 'System'
								END AS cancellation_type ,
								temp.bkg_cancel_user_type AS blg_user_type,
								SUM(IF(temp.GozoCancel=1,1,null)) AS GozoCancel,
								SUM(IF(temp.CustomerCancel=1,1,null)) AS CustomerCancel,
								COUNT(1) AS cnt
								FROM
								(
										SELECT
                                        booking.bkg_id,
                                        booking.bkg_booking_id,
										booking.bkg_booking_type,
									    booking_trail.bkg_cancel_user_type,
										IF(cancel_reasons.cnr_id IN(3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38,40),1,0) as GozoCancel,
										IF(cancel_reasons.cnr_id IN(1,2,4,5,6,7,10,11,12,13,14,15,18,21,23,24,25,31,32,37),1,0) as CustomerCancel
										FROM  `booking`
										JOIN `booking_pref` bpr ON bpr.bpr_bkg_id = booking.bkg_id
										$sqlState
										$sqlDbo
										JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
										JOIN `cancel_reasons` ON cancel_reasons.cnr_id = booking.bkg_cancel_id
										LEFT JOIN `admins` ON admins.adm_id=booking_trail.bkg_cancel_user_id
										LEFT JOIN `agents` ON agents.agt_id=booking.bkg_agent_id
										WHERE $condition AND booking.bkg_active = 1 AND booking.bkg_status IN(9)  $where $having
						        ) temp GROUP BY  blg_user_type";

			$summary		 = DBUtil::queryAll($sqlCount, DBUtil::SDB());
			$dataprovider[0] = new CSqlDataProvider($sql, [
				'totalItemCount' => array_sum(array_column($summary, 'cnt')),
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['username', 'bkg_booking_id', 'bkg_create_date', 'bkg_pickup_date', 'btr_cancel_date'],
					'defaultOrder'	 => 'bkg_create_date DESC'], 'pagination'	 => ['pageSize' => 500],
			]);
			$dataprovider[1] = $summary;
			return $dataprovider;
		}
		else
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
	}

	/**
	 *
	 * @param string $date1
	 * @param string $date2
	 * @param integer $cancelCustomer
	 * @param integer $cancelAdmin
	 * @param integer $cancelAgent
	 * @param integer $cancelSystem
	 * @return array
	 */
	public static function getSummaryCancelReasonByDate($model)
	{
		$date1				 = $model->bkg_create_date1;
		$date2				 = $model->bkg_create_date2;
		$region				 = $model->bkg_region;
		$cancelCustomer		 = $model->bkgCancelCustomer;
		$cancelAdmin		 = $model->bkgCancelAdmin;
		$cancelAgent		 = $model->bkgCancelAgent;
		$cancelSystem		 = $model->bkgCancelSystem;
		$searchIsDBO		 = $model->searchIsDBO;
		$IsGozoCancel		 = $model->IsGozoCancel;
		$IsCustomerCancel	 = $model->IsCustomerCancel;
		$userTypes			 = array();
		if($cancelCustomer == 1 || $cancelAdmin == 1 || $cancelAgent == 1 || $cancelSystem == 1)
		{
			if($cancelCustomer == 1)
			{
				$userTypes[] = 1;
			}
			if($cancelAdmin == 1)
			{
				$userTypes[] = 4;
			}
			if($cancelAgent == 1)
			{
				$userTypes[] = 5;
			}
			if($cancelSystem == 1)
			{
				$userTypes[] = 10;
			}
			$having = ' AND  (blg_user_type IN (' . implode(', ', $userTypes) . '))';
		}
		if($date1 != '' && $date2 != '')
		{
			$subquery = "INNER  JOIN
                       (
							SELECT   blg1.blg_booking_id,blg1.blg_user_type,blg1.blg_created,blg1.blg_user_id
							FROM     booking_log AS blg1
							WHERE    (blg1.blg_created  BETWEEN '$date1 00:00:00' AND '$date2 23:59:59') AND blg1.blg_active = 1 AND blg1.blg_event_id IN (10, 82)
							GROUP BY blg1.blg_created
					    ) blg on blg.blg_booking_id= booking.bkg_id";
		}
		else
		{
			$subquery = "INNER  JOIN
            (
					SELECT   blg1.blg_booking_id,blg1.blg_user_type,blg1.blg_created,blg1.blg_user_id
					FROM     booking_log AS blg1
					WHERE    (blg1.blg_created  BETWEEN CONCAT(CURDATE(),' 00:00:00') AND CONCAT(CURDATE(), ' 23:59:59')) AND blg1.blg_active = 1 AND blg1.blg_event_id IN (10, 82)
					GROUP BY blg1.blg_created
		    ) blg on blg.blg_booking_id= booking.bkg_id ";
		}
		$sql = "SELECT
				(
						CASE blg_user_type
						WHEN 1 THEN 'Cancelled by user'
						WHEN 4 THEN 'Cancelled by Admin'
						WHEN 5 THEN 'Cancelled by Agent'
						WHEN 10 THEN 'Cancelled by System' END
				) as cancellation_type,
				blg_user_type, COUNT(1) as cnt
				FROM (
				SELECT
				booking.bkg_id,
				blg.blg_user_type,
				blg.blg_created,
				booking.bkg_booking_id,
				booking.bkg_booking_type
				FROM  `booking`
				$subquery
               	WHERE	booking.bkg_active = 1 	AND booking.bkg_status IN (9) 	 $having
				GROUP BY blg.blg_booking_id
				) a  GROUP By blg_user_type ";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	/**
	 *
	 * @param integer $bkgId
	 * @return string
	 */
	public static function getNameCancelBy($bkgId)
	{
		$sql = "SELECT
				(CASE booking_log.blg_user_type
					  WHEN 1 THEN CONCAT('Customer -', ' ', booking_user.bkg_user_fname,' ',booking_user.bkg_user_lname)
					  WHEN 4 THEN CONCAT('CSR -',' ',admins.adm_fname,' ',admins.adm_lname)
                 	  WHEN 5 THEN CONCAT('Partner -',' ',agents.agt_company)
                 	  WHEN 10 THEN 'System : auto-cancelled'
				 END) as cancelBy
				FROM `booking_log`
				JOIN `booking` ON booking.bkg_id=booking_log.blg_booking_id
				JOIN `booking_user` ON booking_user.bui_bkg_id=booking_log.blg_booking_id
				LEFT JOIN `admins` ON admins.adm_id=booking_log.blg_user_id
                LEFT JOIN `agents` ON agents.agt_id=booking.bkg_agent_id
				WHERE booking_log.blg_booking_id='$bkgId'
                AND
                (
                    (booking_log.blg_event_id IN (10,82,83) AND booking_log.blg_user_type>0 AND booking_log.blg_user_id>0)
                    OR
                    (booking_log.blg_event_id IN (10,82,83) AND booking_log.blg_user_type=10 AND booking_log.blg_user_id IS NULL)
                )
				ORDER BY booking_log.blg_id DESC LIMIT 0,1";
		return DBUtil::command($sql)->queryScalar();
	}

	public static function getBookingCancelStatus($stid = 0)
	{
		$arrStatus = [
			4	 => 'Confirmed',
			10	 => 'Cancelled (Unverified)',
			11	 => 'Unable to confirm',
			12	 => 'Assigned Old',
			13	 => 'Converted to Lead'
		];
		if($stid != 0)
		{
			return $arrStatus[$stid];
		}
		else
		{
			return $arrStatus;
		}
	}

	public function bookingCreatedByMonth1($isCancel = 0)
	{
		$sql = "SELECT
                    SUM(
                        IF(
                            (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31'))
                        AND (DATE(booking.bkg_create_date) <= DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-31'),INTERVAL 1 MONTH)),1,0)
                   	) as totalBook1,
                    MONTHNAME(DATE_FORMAT(NOW(),'%Y-%m-01')) as monthName1,
                    SUM(
                        IF(
                            (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-31'))
                        AND (DATE(booking.bkg_create_date) <= DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-31'),INTERVAL 1 MONTH)),1,0)
                    ) as totalBook2,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01')) as monthName2,
                    SUM(
                        IF(
                            (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-31'))
                        AND (DATE(booking.bkg_create_date) <= DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-31'),INTERVAL 1 MONTH)),1,0)
                    ) as totalBook3,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01')) as monthName3,
                    SUM(
                        IF(
                            (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-31'))
                        AND (DATE(booking.bkg_create_date) <= DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-31'),INTERVAL 1 MONTH)),1,0)
                    ) as totalBook4,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01')) as monthName4,
                    SUM(
                        IF(
                            (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-31'))
                        AND (DATE(booking.bkg_create_date) <= DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-31'),INTERVAL 1 MONTH)),1,0)
                    ) as totalBook5,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01')) as monthName5,
                    SUM(
                        IF(
                            (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-31'))
                        AND (DATE(booking.bkg_create_date) <= DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-31'),INTERVAL 1 MONTH)),1,0)
                    ) as totalBook6,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01')) as monthName6,
                    SUM(
                        IF(
                            (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-31'))
                        AND (DATE(booking.bkg_create_date) <= DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-31'),INTERVAL 1 MONTH)),1,0)
                    ) as totalBook7,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01')) as monthName7,
                    SUM(
                        IF(
                            (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-31'))
                        AND (DATE(booking.bkg_create_date) <= DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-31'),INTERVAL 1 MONTH)),1,0)
                    ) as totalBook8,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01')) as monthName8,
                    SUM(
                        IF(
                            (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-31'))
                        AND (DATE(booking.bkg_create_date) <= DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-31'),INTERVAL 1 MONTH)),1,0)
                    ) as totalBook9,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01')) as monthName9,
                    SUM(
                        IF(
                            (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-31'))
                        AND (DATE(booking.bkg_create_date) <= DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-31'),INTERVAL 1 MONTH)),1,0)
                    ) as totalBook10,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01')) as monthName10,
                    SUM(
                        IF(
                            (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-31'))
                        AND (DATE(booking.bkg_create_date) <= DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-31'),INTERVAL 1 MONTH)),1,0)
                    ) as totalBook11,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01')) as monthName11,
                    SUM(
                        IF(
                            (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-31'))
                        AND (DATE(booking.bkg_create_date) <= DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-31'),INTERVAL 1 MONTH)),1,0)
                    ) as totalBook12,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01')) as monthName12
                    FROM `booking_cab`
                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                    WHERE booking_cab.bcb_active=1
                    AND booking.bkg_pickup_date BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01')
                    AND DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-01'),INTERVAL 1 YEAR )";
		$sql .= ($isCancel > 0) ? " AND booking.bkg_status IN (9)" : " AND booking.bkg_status IN (2,3,5,6,7)";
		return DBUtil::queryRow($sql);
	}

	public function bookingCreatedByMonth($isCancel = 0)
	{
		$sql = "SELECT
                    SUM(IF(
                        (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31'))
                    AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)) as totalBook1,
                    MONTHNAME(DATE_FORMAT(NOW(),'%Y-%m-01')) as monthName1,
                    SUM(IF(
                        (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-31'))
                    AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)) as totalBook2,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01')) as monthName2,
                    SUM(IF(
                        (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-31'))
                    AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)) as totalBook3,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01')) as monthName3,
                    SUM(IF(
                        (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-31'))
                    AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)) as totalBook4,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01')) as monthName4,
                    SUM(IF(
                        (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-31'))
                    AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)) as totalBook5,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01')) as monthName5,
                    SUM(IF(
                        (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-31'))
                    AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)) as totalBook6,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01')) as monthName6,
                    SUM(IF(
                        (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-31'))
                    AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)) as totalBook7,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01')) as monthName7,
                    SUM(IF(
                        (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-31'))
                    AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)) as totalBook8,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01')) as monthName8,
                    SUM(IF(
                        (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-31'))
                    AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)) as totalBook9,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01')) as monthName9,
                    SUM(IF(
                        (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-31'))
                    AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)) as totalBook10,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01')) as monthName10,
                    SUM(IF(
                        (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-31'))
                    AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)) as totalBook11,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01')) as monthName11,
                    SUM(IF(
                        (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-31'))
                    AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)) as totalBook12,
                    MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01')) as monthName12
                    FROM `booking_cab`
                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                    WHERE booking_cab.bcb_active=1
                    AND booking.bkg_pickup_date BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01')
                    AND DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-01'),INTERVAL 1 YEAR )";
		$sql .= ($isCancel > 0) ? " AND booking.bkg_status IN (9)" : " AND booking.bkg_status IN (2,3,5,6,7)";
		return DBUtil::queryRow($sql);
	}

	public function bookingCreatedByWtd($isCancel = 0)
	{
		$days_from_monday	 = date("N") - 1;
		$monday				 = date("Y-m-d", strtotime("- {$days_from_monday} Days"));
		$sql				 = "SELECT
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31') AND DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-01'), INTERVAL 7 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook1,
                MONTHNAME(DATE_FORMAT(NOW(),'%Y-%m-01')) as monthName1,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01') AND DATE_ADD(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01'),INTERVAL 7 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook2,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01')) as monthName2,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01') AND DATE_ADD(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01'),INTERVAL 7 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook3,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01')) as monthName3,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01') AND DATE_ADD(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01'),INTERVAL 7 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook4,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01')) as monthName4,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01') AND DATE_ADD(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01'),INTERVAL 7 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook5,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01')) as monthName5,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01') AND DATE_ADD(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01'),INTERVAL 7 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook6,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01')) as monthName6,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01') AND DATE_ADD(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01'),INTERVAL 7 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook7,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01')) as monthName7,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01') AND DATE_ADD(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01'),INTERVAL 7 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook8,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01')) as monthName8,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01') AND DATE_ADD(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01'),INTERVAL 7 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook9,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01')) as monthName9,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01') AND DATE_ADD(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01'),INTERVAL 7 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook10,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01')) as monthName10,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01') AND DATE_ADD(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01'),INTERVAL 7 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook11,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01')) as monthName11,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01') AND DATE_ADD(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01'),INTERVAL 7 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook12,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01')) as monthName12
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                WHERE booking_cab.bcb_active=1
                AND booking.bkg_pickup_date BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01')
                AND DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-01'),INTERVAL 1 YEAR )";
		$sql				 .= ($isCancel > 0) ? " AND booking.bkg_status IN (9)" : " AND booking.bkg_status IN (2,3,5,6,7)";
		return DBUtil::queryRow($sql);
	}

	public function bookingCreatedByToday2($isCancel = 0)
	{
		$sql = "SELECT
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-%d'),INTERVAL 2 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook1,
                MONTHNAME(DATE_FORMAT(NOW(),'%Y-%m-01')) as monthName1,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-%d'),INTERVAL 2 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook2,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01')) as monthName2,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-%d'),INTERVAL 2 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook3,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01')) as monthName3,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-%d'),INTERVAL 2 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook4,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01')) as monthName4,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-%d'),INTERVAL 2 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook5,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01')) as monthName5,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-%d'),INTERVAL 2 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook6,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01')) as monthName6,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-%d'),INTERVAL 2 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook7,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01')) as monthName7,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-%d'),INTERVAL 2 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook8,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01')) as monthName8,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-%d'),INTERVAL 2 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook9,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01')) as monthName9,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-%d'),INTERVAL 2 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook10,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01')) as monthName10,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-%d'),INTERVAL 2 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook11,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01')) as monthName11,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-%d'),INTERVAL 2 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook12,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01')) as monthName12
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                WHERE booking_cab.bcb_active=1
                AND booking.bkg_pickup_date BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01')
                AND DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-01'),INTERVAL 1 YEAR )";
		$sql .= ($isCancel > 0) ? " AND booking.bkg_status IN (9)" : " AND booking.bkg_status IN (2,3,5,6,7)";
		return DBUtil::queryRow($sql);
	}

	public function bookingCreatedByToday1($isCancel = 0)
	{
		$sql = "SELECT
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(NOW(),'%Y-%m-%d'),INTERVAL 1 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook1,
                MONTHNAME(DATE_FORMAT(NOW(),'%Y-%m-01')) as monthName1,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-%d'),INTERVAL 1 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook2,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01')) as monthName2,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-%d'),INTERVAL 1 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook3,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01')) as monthName3,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-%d'),INTERVAL 1 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook4,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01')) as monthName4,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-%d'),INTERVAL 1 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook5,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01')) as monthName5,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-%d'),INTERVAL 1 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook6,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01')) as monthName6,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-%d'),INTERVAL 1 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook7,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01')) as monthName7,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-%d'),INTERVAL 1 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook8,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01')) as monthName8,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-%d'),INTERVAL 1 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook9,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01')) as monthName9,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-%d'),INTERVAL 1 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook10,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01')) as monthName10,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-%d'),INTERVAL 1 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook11,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01')) as monthName11,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_SUB(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-%d'),INTERVAL 1 DAY))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook12,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01')) as monthName12
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                WHERE booking_cab.bcb_active=1
                AND booking.bkg_pickup_date BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01')
                AND DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-01'),INTERVAL 1 YEAR )";
		$sql .= ($isCancel > 0) ? " AND booking.bkg_status IN (9)" : " AND booking.bkg_status IN (2,3,5,6,7)";
		return DBUtil::queryRow($sql);
	}

	public function bookingCreatedByToday($isCancel = 0)
	{
		$sql = "SELECT
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_FORMAT(NOW(),'%Y-%m-%d'))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook1,
                MONTHNAME(DATE_FORMAT(NOW(),'%Y-%m-01')) as monthName1,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-%d'))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook2,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 MONTH),'%Y-%m-01')) as monthName2,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-%d'))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook3,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 2 MONTH),'%Y-%m-01')) as monthName3,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-%d'))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook4,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-01')) as monthName4,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-%d'))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook5,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 4 MONTH),'%Y-%m-01')) as monthName5,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-%d'))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook6,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 5 MONTH),'%Y-%m-01')) as monthName6,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-%d'))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook7,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 6 MONTH),'%Y-%m-01')) as monthName7,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-%d'))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook8,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 7 MONTH),'%Y-%m-01')) as monthName8,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-%d'))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook9,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 8 MONTH),'%Y-%m-01')) as monthName9,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-%d'))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook10,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 9 MONTH),'%Y-%m-01')) as monthName10,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-%d'))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook11,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 10 MONTH),'%Y-%m-01')) as monthName11,
                SUM(IF(
                    (DATE(booking.bkg_pickup_date) BETWEEN DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01') AND DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-31') AND DATE(booking.bkg_pickup_date)=DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-%d'))
                AND (DATE(booking.bkg_create_date) BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND DATE_FORMAT(NOW(),'%Y-%m-31')),1,0)
                ) as totalBook12,
                MONTHNAME(DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 11 MONTH),'%Y-%m-01')) as monthName12
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                WHERE booking_cab.bcb_active=1
                AND booking.bkg_pickup_date BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01')
                AND DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-01'),INTERVAL 1 YEAR )";
		$sql .= ($isCancel > 0) ? " AND booking.bkg_status IN (9)" : " AND booking.bkg_status IN (2,3,5,6,7)";
		return DBUtil::queryRow($sql);
	}

	public function getAgentActiveBookingStatusList($agentId, $corpCode = '')
	{

		$sql			 = "SELECT   DISTINCT bkg_status
                FROM     booking
                WHERE    bkg_agent_id = $agentId
                ORDER BY bkg_status;";
		$agentBkgStatus	 = DBUtil::command($sql)->queryColumn();
		$statusList		 = Booking::model()->getActiveBookingStatus();
		$allowedStatus	 = [2, 3, 5, 6, 7, 9];
		$statusList[2]	 = 'Confirmed';
		$filteredStataus = [];
		foreach($agentBkgStatus as $m)
		{
			if(in_array($m, $allowedStatus))
			{
				$filteredStataus[$m] = $statusList[$m];
			}
		}
		return $filteredStataus;
	}

	public function getAgentCreditsByAgentArr($agentArr = [])
	{
		$agentIDs = '';
		if(sizeof($agentArr) > 0)
		{
			$agentIDs .= ' and bkg_agent_id IN (' . implode(',', $agentArr) . ')  ';
		}
		$activeStatus	 = 'bkg.bkg_status>=2 && bkg.bkg_status<=7';
		$sql			 = "SELECT
							bkg.bkg_agent_id,
							count(bkg.bkg_id) totBookings,
							sum(if($activeStatus,1,0)) totActiveBookings,
							sum(if($activeStatus,biv.bkg_corporate_credit,0))  totCredit,
							sum(if($activeStatus,biv.bkg_agent_markup,0))  totCommission,
							cty.cty_name agt_city_name,
							agt.agt_type,
							agt.agt_lname,
							agt.agt_fname,
							agt.agt_company_type,
							agt.agt_owner_name,
							agt.agt_company,
							agt.agt_commission_value,
							agt.agt_commission,
							agt.agt_opening_deposit,
							agt.agt_phone,
							agt.agt_phone_country_code,
							agt.agt_phone_two,
							agt.agt_phone_three,
							agt.agt_email,
							agt.agt_email_two,
							agt.agt_fax,
							agt.agt_address,
							agt.agt_credit_limit,
							agt.agt_effective_credit_limit,
							agt.agt_bank,
							agt.agt_branch_name,
							agt.agt_ifsc_code,
							agt.agt_bank_account,
							agt.agt_active,
							agt.agt_create_date,
							agt.agt_agent_id,
							agt.agt_license_expiry_date,
							agt.agt_approved,
							CONCAT(admins.adm_fname,' ',admins.adm_lname) as approve_by_name,
							(CASE agt.agt_approved
                        WHEN 0 THEN 'Not Verified'
                 	WHEN 1 THEN 'Approved'
                 	WHEN 2 THEN 'Pending Approval'
                 	WHEN 3 THEN 'Rejected'
					WHEN 4 THEN 'Ready For Approval'
                END) as approve_status
							FROM booking bkg
							JOIN booking_invoice biv ON bkg.bkg_id=biv.biv_bkg_id
							JOIN agents agt ON agt.agt_id = bkg.bkg_agent_id   $agentIDs
							LEFT JOIN cities cty ON cty.cty_id = agt.agt_city
							 LEFT JOIN admins ON admins.adm_id=agt.agt_approved_by
							WHERE  1
							GROUP BY bkg.bkg_agent_id";
		return DBUtil::queryAll($sql);
	}

	public function getApplicable($fromCityId, $toCityId, $flag)
	{
		if($flag == 1)
		{
			$isApplicale1 = Route::model()->getRutidbyCities($fromCityId, $toCityId, 1)->rut_is_promo_code_apply;
		}
		if($flag == 2)
		{
			$isApplicale1 = Route::model()->getRutidbyCities($fromCityId, $toCityId, 1)->rut_is_promo_gozo_coins_apply;
		}
		if($flag == 3)
		{
			$isApplicale1 = Route::model()->getRutidbyCities($fromCityId, $toCityId, 1)->rut_is_cod_apply;
		}
		$isApplicale2 = ZoneCities::model()->getApplyByZone($fromCityId, $flag);
		if(($isApplicale1 == 1 || $isApplicale1 == '') && ($isApplicale2 || $isApplicale2 == ''))
		{
			$isApplicale = true;
		}
		else
		{
			$isApplicale = false;
		}
		return $isApplicale;
	}

	public function checkAirport($cityArr = [])
	{
		$isAirport = [];
		if(sizeof($cityArr) > 0)
		{
			$ctyIds	 = implode(',', $cityArr);
			$sql	 = "SELECT cty_id, cty_name, cty_is_airport,cty_garage_address
                    FROM   cities
                    WHERE  cty_id IN ($ctyIds) AND cty_is_airport = 1";
			$result	 = DBUtil::queryAll($sql);

			if(sizeof($result) > 0)
			{
				foreach($result as $k => $v)
				{
					$isAirport[array_search($v['cty_id'], $cityArr)] = ['id' => $v['cty_id'], 'name' => $v['cty_garage_address']];
				}
			}
		}
		return $isAirport;
	}

	/**
	 *
	 * @deprecated please check SvcClassVhcCat::getexcludedCabTypes()
	 * @param int $fromCtyId
	 * @param int $toCtyId
	 * @return array()
	 */
	public function getexcludedCabTypes($fromCtyId, $toCtyId)
	{
		$data = SvcClassVhcCat::getExcludedCabTypes($fromCtyId, $toCtyId);
		return $data;
	}

	public function getIdsByCodesArr($bidArr = [])
	{
		if(count($bidArr) > 0)
		{
			$bidList = implode("','", $bidArr);
			$sql	 = "SELECT bkg_id FROM booking WHERE bkg_booking_id IN ('$bidList') AND bkg_status = 2 AND bkg_pickup_date >= NOW()";
			$dataSet = DBUtil::query($sql);
			if(count($dataSet) > 0)
			{
				$arr = [];
				foreach($dataSet as $val)
				{
					$arr[] = $val['bkg_id'];
				}
				return $arr;
			}
		}
		return false;
	}

	public function moneyReport($date = '', $category = '', $type = 'data')
	{

		$dataprovider				 = [];
		$CurrentMonthDay			 = date('Y-m-01') . ' 00:00:00';
		$CurrentlastMonthDate		 = date("Y-m-t", strtotime(date('Y-m-01'))) . ' 23:59:59';
		$yearMonth					 = date("Ym", strtotime(date($CurrentMonthDay)));
		$sqlBookingCreatedThisMonth	 = "
       SELECT 'Booking created (this month)' AS category,
       seq,
       month,
       SUM(currentCount) AS currentCount1,
       SUM(futuretCount) AS fCount,
       SUM(totalCount) AS totalCount1,
       SUM(currentAmount) AS CurrentAmount,
       SUM(futureAmount) AS FutureAmount,
       SUM(bcb_vendor_amount) AS totalVendorAmount,
       SUM(gozoAmount) AS GozoAmount,
       SUM(serviceTax) AS ServiceTax
        FROM ( SELECT
               1 AS seq,
               DATE_FORMAT(MIN(bkg_create_date), '%Y%m')
                AS month,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                              bkg_pickup_date,
                                                              '%Y%m'),
                   1,
                   0))
                AS currentCount,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m') <
                   DATE_FORMAT(bkg_pickup_date, '%Y%m'),
                   1,
                   0))
                AS futuretCount,
             COUNT(DISTINCT booking.bkg_id)
                AS totalCount,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                              bkg_pickup_date,
                                                              '%Y%m'),
                   booking_invoice.bkg_total_amount,
                   0))
                AS currentAmount,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m') <
                   DATE_FORMAT(bkg_pickup_date, '%Y%m'),
                   booking_invoice.bkg_total_amount,
                   0))
                AS futureAmount,
             booking_cab.bcb_vendor_amount,
             (  SUM(
                     booking_invoice.bkg_total_amount
                   - booking_invoice.bkg_service_tax)
              - bcb_vendor_amount)
                AS gozoAmount,
             SUM(booking_invoice.bkg_service_tax)
                AS serviceTax
        FROM booking
           INNER JOIN booking_cab
              ON     booking_cab.bcb_id = booking.bkg_bcb_id
                 AND booking_cab.bcb_active = 1
           JOIN booking_invoice
              ON booking.bkg_id = booking_invoice.biv_bkg_id
            WHERE     booking.bkg_status IN (2,
                                       3,
                                       5,
                                       6,
                                       7)
             AND bkg_create_date BETWEEN   '$CurrentMonthDay' and  '$CurrentlastMonthDate'
			GROUP BY booking.bkg_bcb_id) a
			WHERE month = '$yearMonth' and a.seq in ({$category})
			GROUP BY month";
		$rowBookingCreatedThisMonth	 = DBUtil::queryAll($sqlBookingCreatedThisMonth, DBUtil::SDB());

		if($rowBookingCreatedThisMonth != null && $rowBookingCreatedThisMonth[0]['seq'] != null)
		{
			$dataprovider[] = $rowBookingCreatedThisMonth;
		}

		$sqlAdvanceCollectedThisMonth = "

SELECT * From (

SELECT 'Advance collected (this month)' AS category,
       2 AS seq,
       DATE_FORMAT(account_transactions.act_date, '%Y%m') AS month,
       COUNT(
          DISTINCT IF(
                      DATE_FORMAT(account_transactions.act_date, '%Y%m')   = DATE_FORMAT(
                                                                               bkg_pickup_date,
                                                                               '%Y%m'),
                      bkg_id,
                      0))
          AS currentCount1,
       COUNT(
          DISTINCT IF(  DATE_FORMAT(account_transactions.act_date, '%Y%m') < DATE_FORMAT(bkg_pickup_date, '%Y%m'),   bkg_id, 0))   AS fCount,
         COUNT(DISTINCT bkg_id) AS totalCount1,
       SUM(
          IF(
             DATE_FORMAT(account_transactions.act_date, '%Y%m')   = DATE_FORMAT(
                                                                      bkg_pickup_date,
                                                                      '%Y%m'),
             account_trans_details.adt_amount,
             0))
          AS CurrentAmount,
       SUM(
          IF(
             DATE_FORMAT(account_transactions.act_date, '%Y%m') <
             DATE_FORMAT(bkg_pickup_date, '%Y%m'),
             account_trans_details.adt_amount,
             0))
          AS FutureAmount,
       0
          AS totalVendorAmount,
       0
          AS GozoAmount,
       0
          AS ServiceTax
FROM booking
     JOIN account_transactions
        ON account_transactions.act_ref_id = booking.bkg_id
     JOIN account_trans_details
        ON account_trans_details.adt_trans_id = account_transactions.act_id
     JOIN account_ledger
        ON account_ledger.ledgerId = account_trans_details.adt_ledger_id
     LEFT JOIN payment_gateway apg
        ON apg.apg_id = account_trans_details.adt_trans_ref_id
WHERE     account_ledger.accountGroupId IN (27, 28)
      AND account_transactions.act_type = 1
      AND account_transactions.act_active = 1
      AND account_transactions.act_date BETWEEN   '$CurrentMonthDay' and  '$CurrentlastMonthDate'
 GROUP BY month DESC ) abc
where  1 AND seq in ($category)";

		$rowAdvanceCollectedThisMonth = DBUtil::queryAll($sqlAdvanceCollectedThisMonth, DBUtil::SDB());
		if($rowAdvanceCollectedThisMonth != null && $rowAdvanceCollectedThisMonth[0]['seq'] != null)
		{
			$dataprovider[] = $rowAdvanceCollectedThisMonth;
		}

		$sqlBookingsCompletedCreatedThisMonth = "
SELECT 'Bookings completed (created this month)' AS category,
       seq,
       month,
       SUM(currentCount) AS currentCount1,
       SUM(futuretCount) AS fCount,
       SUM(totalCount) AS totalCount1,
       SUM(currentAmount) AS CurrentAmount,
       SUM(futureAmount) AS FutureAmount,
       SUM(bcb_vendor_amount) AS totalVendorAmount,
       SUM(gozoAmount) AS GozoAmount,
       SUM(serviceTax) AS ServiceTax
FROM (SELECT   3 AS seq,
DATE_FORMAT(
                MAX(
                   DATE_ADD(bkg_pickup_date,
                            INTERVAL bkg_trip_duration MINUTE)),
                '%Y%m')
                AS month,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                              DATE_ADD(
                                                                 bkg_pickup_date,
                                                                 INTERVAL bkg_trip_duration MINUTE),
                                                              '%Y%m'),
                   1,
                   0))
                AS currentCount,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m') <
                   DATE_FORMAT(
                      DATE_ADD(bkg_pickup_date,
                               INTERVAL bkg_trip_duration MINUTE),
                      '%Y%m'),
                   1,
                   0))
                AS futuretCount,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                              DATE_ADD(
                                                                 bkg_pickup_date,
                                                                 INTERVAL bkg_trip_duration MINUTE),
                                                              '%Y%m'),
                   booking_invoice.bkg_total_amount,
                   0))
                AS currentAmount,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m') <
                   DATE_FORMAT(
                      DATE_ADD(bkg_pickup_date,
                               INTERVAL bkg_trip_duration MINUTE),
                      '%Y%m'),
                   booking_invoice.bkg_total_amount,
                   0))
                AS futureAmount,
             COUNT(DISTINCT booking.bkg_id)
                AS totalCount,
             booking_cab.bcb_vendor_amount,
             (  SUM(
                     booking_invoice.bkg_total_amount
                   - booking_invoice.bkg_service_tax)
              - bcb_vendor_amount)
                AS gozoAmount,
             SUM(booking_invoice.bkg_service_tax)
                AS serviceTax
      FROM booking
           INNER JOIN booking_cab
              ON     booking_cab.bcb_id = booking.bkg_bcb_id
                 AND booking_cab.bcb_active = 1
           JOIN booking_invoice
              ON booking.bkg_id = booking_invoice.biv_bkg_id
      WHERE     booking.bkg_status IN (6, 7)
            AND DATE_ADD(bkg_pickup_date, INTERVAL bkg_trip_duration MINUTE) <NOW()
            AND DATE_FORMAT(DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE),'%Y%m') =DATE_FORMAT(bkg_create_date, '%Y%m')
           AND booking.bkg_create_date BETWEEN   '$CurrentMonthDay' and  '$CurrentlastMonthDate'
      GROUP BY booking.bkg_bcb_id) a
	  where 1 and seq in ($category)
GROUP BY month";

		$rowBookingsCompletedCreatedThisMonth = DBUtil::queryAll($sqlBookingsCompletedCreatedThisMonth, DBUtil::SDB());
		if($rowBookingsCompletedCreatedThisMonth != null && $rowBookingsCompletedCreatedThisMonth[0]['seq'] != null)
		{
			$dataprovider[] = $rowBookingsCompletedCreatedThisMonth;
		}

		$sqlBookingsCompletedCreatedThisPast = "
SELECT 'Bookings completed (created in past) ' AS category,
       seq,
       month,
       SUM(currentCount) AS currentCount1,
       SUM(futuretCount) AS fCount,
       SUM(totalCount) AS totalCount1,
       SUM(currentAmount) AS CurrentAmount,
       SUM(futureAmount) AS FutureAmount,
       SUM(bcb_vendor_amount) AS totalVendorAmount,
       SUM(gozoAmount) AS GozoAmount,
       SUM(serviceTax) AS ServiceTax
FROM (SELECT  4 AS seq,DATE_FORMAT(
                MAX(
                   DATE_ADD(bkg_pickup_date,
                            INTERVAL bkg_trip_duration MINUTE)),
                '%Y%m')
                AS month,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                              DATE_ADD(
                                                                 bkg_pickup_date,
                                                                 INTERVAL bkg_trip_duration MINUTE),
                                                              '%Y%m'),
                   1,
                   0))
                AS currentCount,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m') <
                   DATE_FORMAT(
                      DATE_ADD(bkg_pickup_date,
                               INTERVAL bkg_trip_duration MINUTE),
                      '%Y%m'),
                   1,
                   0))
                AS futuretCount,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                              DATE_ADD(
                                                                 bkg_pickup_date,
                                                                 INTERVAL bkg_trip_duration MINUTE),
                                                              '%Y%m'),
                   booking_invoice.bkg_total_amount,
                   0))
                AS currentAmount,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m') <
                   DATE_FORMAT(
                      DATE_ADD(bkg_pickup_date,
                               INTERVAL bkg_trip_duration MINUTE),
                      '%Y%m'),
                   booking_invoice.bkg_total_amount,
                   0))
                AS futureAmount,
             COUNT(DISTINCT booking.bkg_id)
                AS totalCount,
             booking_cab.bcb_vendor_amount,
             (  SUM(
                     booking_invoice.bkg_total_amount
                   - booking_invoice.bkg_service_tax)
              - bcb_vendor_amount)
                AS gozoAmount,
             SUM(booking_invoice.bkg_service_tax)
                AS serviceTax
      FROM booking
           INNER JOIN booking_cab  ON  booking_cab.bcb_id = booking.bkg_bcb_id  AND booking_cab.bcb_active = 1
           JOIN booking_invoice  ON booking.bkg_id = booking_invoice.biv_bkg_id
      WHERE
		booking.bkg_status IN (6, 7)
		AND DATE_ADD(bkg_pickup_date, INTERVAL bkg_trip_duration MINUTE) < NOW()
		AND DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE) > bkg_create_date
		AND (DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)) BETWEEN   '$CurrentMonthDay' and  '$CurrentlastMonthDate'
      GROUP BY booking.bkg_bcb_id) a
WHERE a.month = '$yearMonth'  and seq in ($category)
GROUP BY month";

		$rowBookingsCompletedCreatedThisPast = DBUtil::queryAll($sqlBookingsCompletedCreatedThisPast, DBUtil::SDB());
		if($rowBookingsCompletedCreatedThisPast != null && $rowBookingsCompletedCreatedThisPast[0]['seq'] != null)
		{
			$dataprovider[] = $rowBookingsCompletedCreatedThisPast;
		}

		$sqlBookingsCancelledCreatedThisMonth	 = "
Select * from (SELECT 'Bookings cancelled (created this month) '
          AS category,
       5
          AS seq,
       DATE_FORMAT(cancelled_date, '%Y%m')
          AS month,
       SUM(
          IF(
             DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                        cancelled_date,
                                                        '%Y%m'),
             1,
             0))
          AS currentCount1,
       SUM(
          IF(
             DATE_FORMAT(bkg_create_date, '%Y%m') <
             DATE_FORMAT(cancelled_date, '%Y%m'),
             1,
             0))
          AS fCount,
       COUNT(*)
          AS totalCount1,
       SUM(
          IF(
             DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                        cancelled_date,
                                                        '%Y%m'),
             booking_invoice.bkg_total_amount,
             0))
          AS CurrentAmount,
       SUM(
          IF(
             DATE_FORMAT(bkg_create_date, '%Y%m') <
             DATE_FORMAT(cancelled_date, '%Y%m'),
             booking_invoice.bkg_total_amount,
             0))
          AS FutureAmount,
       0
          AS totalVendorAmount,
       0
          AS GozoAmount,
       0
          AS ServiceTax
FROM booking
     INNER JOIN
     (
				SELECT bkg_id, MAX(booking_log.blg_created) AS cancelled_date
				FROM booking
				INNER JOIN booking_log
				ON     booking.bkg_id = booking_log.blg_booking_id
				AND bkg_status = 9
				AND booking_log.blg_event_id = 10
				AND booking_log.blg_created between '$CurrentMonthDay' and '$CurrentlastMonthDate'
				GROUP BY bkg_id

       ) a
        ON booking.bkg_id = a.bkg_id
     JOIN booking_invoice ON booking.bkg_id = booking_invoice.biv_bkg_id
WHERE     DATE_FORMAT(cancelled_date, '%Y%m')= DATE_FORMAT(bkg_create_date, '%Y%m')
           AND cancelled_date between '$CurrentMonthDay' and '$CurrentlastMonthDate'
GROUP BY month
) abc
where 1 and seq in ($category)";
		$rowBookingsCancelledCreatedThisMonth	 = DBUtil::queryAll($sqlBookingsCancelledCreatedThisMonth, DBUtil::SDB());
		if($rowBookingsCancelledCreatedThisMonth != null && $rowBookingsCancelledCreatedThisMonth[0]['seq'] != null)
		{
			$dataprovider[] = $rowBookingsCancelledCreatedThisMonth;
		}

		$sqlBookingsCancelledCreatedThisPast = "
Select * from (SELECT 'Bookings cancelled (created in past) '
          AS category,
       6   AS seq,
       DATE_FORMAT(cancelled_date, '%Y%m')
          AS month,
       SUM(
          IF(
             DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                        cancelled_date,
                                                        '%Y%m'),
             1,
             0))
          AS currentCount1,
       SUM(
          IF(
             DATE_FORMAT(bkg_create_date, '%Y%m') <
             DATE_FORMAT(cancelled_date, '%Y%m'),
             1,
             0))
          AS fCount,
       COUNT(*)
          AS totalCount1,
       SUM(
          IF(
             DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                        cancelled_date,
                                                        '%Y%m'),
             booking_invoice.bkg_total_amount,
             0))
          AS CurrentAmount,
       SUM(
          IF(
             DATE_FORMAT(bkg_create_date, '%Y%m') <
             DATE_FORMAT(cancelled_date, '%Y%m'),
             booking_invoice.bkg_total_amount,
             0))
          AS FutureAmount,
        0 AS totalVendorAmount,
       0 AS GozoAmount,
       0 AS ServiceTax
FROM booking
     INNER JOIN
     (SELECT bkg_id, MAX(booking_log.blg_created) AS cancelled_date
      FROM booking
           INNER JOIN booking_log
              ON     booking.bkg_id = booking_log.blg_booking_id
                 AND bkg_status = 9
                 AND booking_log.blg_event_id = 10
                 AND booking_log.blg_created between '$CurrentMonthDay' and '$CurrentlastMonthDate'
      GROUP BY bkg_id) a
        ON booking.bkg_id = a.bkg_id
     JOIN booking_invoice ON booking.bkg_id = booking_invoice.biv_bkg_id
WHERE   cancelled_date > bkg_create_date AND cancelled_date between '$CurrentMonthDay' and '$CurrentlastMonthDate'
GROUP BY month) abc
where 1 and seq in ($category)";

		$rowBookingsCancelledCreatedThisPast = DBUtil::queryAll($sqlBookingsCancelledCreatedThisPast, DBUtil::SDB());
		if($rowBookingsCancelledCreatedThisPast != null && $rowBookingsCancelledCreatedThisPast[0]['seq'] != null)
		{
			$dataprovider[] = $rowBookingsCancelledCreatedThisPast;
		}


		$sqlBookingsActiveCreatedThisMonth = "


SELECT 'Bookings active (created this month) ' AS category,
       seq,
       month,
       SUM(currentCount) AS currentCount1,
       SUM(futuretCount) AS fCount,
       SUM(totalCount) AS totalCount1,
       SUM(currentGMV) AS CurrentAmount,
       SUM(futuretGMV) AS FutureAmount,
       SUM(bcb_vendor_amount) AS totalVendorAmount,
       SUM(gozoAmount) AS GozoAmount,
       SUM(serviceTax) AS ServiceTax
FROM (SELECT 7 AS seq ,DATE_FORMAT(MIN(bkg_pickup_date), '%Y%m')
                AS month,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                              bkg_pickup_date,
                                                              '%Y%m'),
                   1,
                   0))
                AS currentCount,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m') <
                   DATE_FORMAT(bkg_pickup_date, '%Y%m'),
                   1,
                   0))
                AS futuretCount,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                              bkg_pickup_date,
                                                              '%Y%m'),
                   booking_invoice.bkg_total_amount,
                   0))
                AS currentGMV,
             SUM(
                IF(
                   DATE_FORMAT(bkg_create_date, '%Y%m') <
                   DATE_FORMAT(bkg_pickup_date, '%Y%m'),
                   booking_invoice.bkg_total_amount,
                   0))
                AS futuretGMV,
             COUNT(DISTINCT booking.bkg_id)
                AS totalCount,
             booking_cab.bcb_vendor_amount,
             (  SUM(
                     booking_invoice.bkg_total_amount
                   - booking_invoice.bkg_service_tax)
              - bcb_vendor_amount)
                AS gozoAmount,
             SUM(booking_invoice.bkg_service_tax)
                AS serviceTax
      FROM booking
           INNER JOIN booking_cab
              ON     booking_cab.bcb_id = booking.bkg_bcb_id
                 AND booking_cab.bcb_active = 1
           JOIN booking_invoice
              ON booking.bkg_id = booking_invoice.biv_bkg_id
      WHERE     booking.bkg_status IN (2,3,5,6,7)
                AND bkg_create_date   BETWEEN   '$CurrentMonthDay' and  '$CurrentlastMonthDate'
                AND bkg_pickup_date   BETWEEN   '$CurrentMonthDay' and  '$CurrentlastMonthDate'
      GROUP BY   booking.bkg_bcb_id AND DATE_FORMAT(bkg_pickup_date, '%Y%m') = $yearMonth) a
WHERE 1 AND a.month ='$yearMonth' AND seq in ($category)
";

		$rowBookingsActiveCreatedThisMonth = DBUtil::queryAll($sqlBookingsActiveCreatedThisMonth, DBUtil::SDB());
		if($rowBookingsActiveCreatedThisMonth != null && $rowBookingsActiveCreatedThisMonth[0]['seq'] != null)
		{
			$dataprovider[] = $rowBookingsActiveCreatedThisMonth;
		}
		$sqlBookingsActiveCreatedThisPast	 = "

SELECT 'Bookings active (created in past) ' AS category,
            seq,
             month,
             SUM(currentCount) AS currentCount1,
             SUM(futuretCount) AS fCount,
             SUM(totalCount) AS totalCount1,
             SUM(currentGMV) AS CurrentAmount,
             SUM(futuretGMV) AS FutureAmount,
             SUM(bcb_vendor_amount) AS totalVendorAmount,
             SUM(gozoAmount) AS GozoAmount,
             SUM(serviceTax) AS ServiceTax
      FROM (SELECT  8 AS seq,DATE_FORMAT(MIN(bkg_pickup_date), '%Y%m')
                      AS month,
                   SUM(
                      IF(
                         DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                                    bkg_pickup_date,
                                                                    '%Y%m'),
                         1,
                         0))
                      AS currentCount,
                   SUM(
                      IF(
                         DATE_FORMAT(bkg_create_date, '%Y%m') <
                         DATE_FORMAT(bkg_pickup_date, '%Y%m'),
                         1,
                         0))
                      AS futuretCount,
                   SUM(
                      IF(
                         DATE_FORMAT(bkg_create_date, '%Y%m')   = DATE_FORMAT(
                                                                    bkg_pickup_date,
                                                                    '%Y%m'),
                         booking_invoice.bkg_total_amount,
                         0))
                      AS currentGMV,
                   SUM(
                      IF(
                         DATE_FORMAT(bkg_create_date, '%Y%m') <
                         DATE_FORMAT(bkg_pickup_date, '%Y%m'),
                         booking_invoice.bkg_total_amount,
                         0))
                      AS futuretGMV,
                   COUNT(DISTINCT booking.bkg_id)
                      AS totalCount,
                   booking_cab.bcb_vendor_amount,
                   (  SUM(
                           booking_invoice.bkg_total_amount
                         - booking_invoice.bkg_service_tax)
                    - bcb_vendor_amount)
                      AS gozoAmount,
                   SUM(booking_invoice.bkg_service_tax)
                      AS serviceTax
            FROM booking
                 INNER JOIN booking_cab ON  booking_cab.bcb_id = booking.bkg_bcb_id  AND booking_cab.bcb_active = 1
                 JOIN booking_invoice  ON booking.bkg_id = booking_invoice.biv_bkg_id
            WHERE booking.bkg_status IN (2,3,5,6,7) AND (bkg_pickup_date) > (bkg_create_date) AND bkg_pickup_date   BETWEEN   '$CurrentMonthDay' and  '$CurrentlastMonthDate'
            GROUP BY booking.bkg_bcb_id ) a
            where 1   and a.month='$yearMonth' and seq in ($category)
      GROUP BY month";
		$rowBookingsActiveCreatedThisPast	 = DBUtil::queryAll($sqlBookingsActiveCreatedThisPast, DBUtil::SDB());
		if($rowBookingsActiveCreatedThisPast != null && $rowBookingsActiveCreatedThisPast[0]['seq'] != null)
		{
			$dataprovider[] = $rowBookingsActiveCreatedThisPast;
		}
		return $dataprovider;
	}

	public function getYear()
	{
		$year = [];
		for($i = 0; $i <= 10; $i++)
		{
			$year[] = ['id' => date('Y', strtotime("last day of -$i year")), 'text' => date('Y', strtotime("last day of -$i year"))];
		}
		return json_encode($year);
	}

	public function getMonth()
	{
		$month	 = [];
		$i		 = 0;
		for($i = 0; $i <= 11; $i++)
		{
			$month[] = ['id' => date('m', strtotime("first day of +$i month")), 'text' => date('F', strtotime("first day of +$i month"))];
		}
		return json_encode($month);
	}

	public function getCategory()
	{
		$category = [];
		array_push($category, ['id' => '1', 'text' => 'Booking created (this month)']);
		array_push($category, ['id' => '2', 'text' => 'Advance collected (this month)']);
		array_push($category, ['id' => '3', 'text' => 'Bookings completed (created this month)']);
		array_push($category, ['id' => '4', 'text' => 'Bookings completed (created in past)']);
		array_push($category, ['id' => '5', 'text' => 'bookings cancelled (created this month)']);
		array_push($category, ['id' => '6', 'text' => 'bookings cancelled (created in past)']);
		array_push($category, ['id' => '7', 'text' => 'Bookings active (created this month)']);
		array_push($category, ['id' => '8', 'text' => 'Bookings active (created in past)']);
		return json_encode($category);
	}

	public function getMmtUnverified()
	{
		$sql = "SELECT booking.bkg_id, booking.bkg_agent_id FROM `booking`
                WHERE (booking.bkg_agent_id=450 OR bkg_pickup_date < NOW())
                AND (booking.bkg_create_date) < DATE_SUB(NOW(),INTERVAL 45 MINUTE)
                AND booking.bkg_status=1 ORDER BY bkg_id DESC";
		return DBUtil::queryAll($sql);
	}

	public function getTrainmanUnverified()
	{
		$sql = "SELECT booking.bkg_id, booking.bkg_agent_id FROM `booking`
                WHERE ((bkg_agent_id IS NULL AND bkg_pickup_date < DATE_ADD(NOW(), INTERVAL 2 HOUR)) OR
                    (booking.bkg_agent_id>0 AND (booking.bkg_create_date < DATE_SUB(NOW(),INTERVAL 2880 MINUTE) OR bkg_pickup_date < DATE_ADD(NOW(), INTERVAL 2 HOUR))))
                AND booking.bkg_status=1";
		return DBUtil::queryAll($sql);
	}

	public function getCabDetailsByHour($hr = 360)
	{
		$sql = "SELECT   booking.bkg_id, booking.bkg_pickup_date, DATE_SUB(booking.bkg_pickup_date, INTERVAL $hr MINUTE) AS pickup_by_min, max_sent_date, cntEMAIL
				FROM     `booking_cab`
						 INNER JOIN `booking` ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking.bkg_active = 1 AND booking.bkg_status IN (5)
						 LEFT JOIN (SELECT   email_log.elg_ref_id, email_log.elg_type, MAX(email_log.elg_status_date) AS max_sent_date, COUNT(1) AS cntEMAIL
									FROM     `email_log` JOIN `booking` ON booking.bkg_id = email_log.elg_ref_id
									WHERE    email_log.elg_type = 3
									GROUP BY email_log.elg_ref_id) elg
						   ON elg.elg_ref_id = booking.bkg_id
				WHERE    booking_cab.bcb_active = 1 AND booking.bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL $hr MINUTE) AND (max_sent_date IS NULL OR cntEMAIL <= 1)
				GROUP BY booking.bkg_id";

		return DBUtil::queryAll($sql);
	}

	public function getBookingsByToday()
	{
		$where = "";
		if($this->from_date != null && $this->to_date != null)
		{
			$where = " AND (booking.bkg_create_date BETWEEN '$this->from_date' AND '$this->to_date') ";
		}
		else
		{
			$where = " AND (booking.bkg_create_date BETWEEN CONCAT(CURDATE(),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59')) ";
		}

		$sql = "SELECT NOW() as lastRefeshDate,

				IF(total_book>0,total_book,0) as total_book,
				IF(total_book_local>0,total_book_local,0) as total_book_local,
				IF(total_book_outstation>0,total_book_outstation,0) as total_book_outstation,

				IF(total_mmtb2b>0,total_mmtb2b,0) as total_mmtb2b,
				IF(total_mmtb2b_local>0,total_mmtb2b_local,0) as total_mmtb2b_local,
				IF(total_mmtb2b_outstation>0,total_mmtb2b_outstation,0) as total_mmtb2b_outstation,
				IF(total_ibibob2b>0,total_ibibob2b,0) as total_ibibob2b,

				IF(total_ibibob2b_local>0,total_ibibob2b_local,0) as total_ibibob2b_local,
				IF(total_ibibob2b_outstation>0,total_ibibob2b_outstation,0) as total_ibibob2b_outstation,

                IF(total_b2b>0,total_b2b,0) as total_b2b,
                IF(total_b2b_local>0,total_b2b_local,0) as total_b2b_local,
                IF(total_b2b_outstation>0,total_b2b_outstation,0) as total_b2b_outstation,

				IF(total_gozoSuttleb2c>0,total_gozoSuttleb2c,0) as total_gozoSuttleb2c,
				IF(total_b2c>0,total_b2c,0) as total_b2c,
				IF(total_b2c_local>0,total_b2c_local,0) as total_b2c_local,
				IF(total_b2c_outstation>0,total_b2c_outstation,0) as total_b2c_outstation,

				IF(total_b2c_user>0,total_b2c_user,0) as total_b2c_user,
				IF(total_b2c_other>0,total_b2c_other,0) as total_b2c_other,
				IF(total_b2c_gn>0,total_b2c_gn,0) as total_b2c_gn,
				IF(total_b2c_adminAssisted>0,total_b2c_adminAssisted,0) as total_b2c_adminAssisted,
				IF(total_b2c_admin>0,total_b2c_admin,0) as total_b2c_admin,
				IF(total_b2c_admin_gn>0,total_b2c_admin_gn,0) as total_b2c_admin_gn,
				IF(total_b2c_quot>0,total_b2c_quot,0) as total_b2c_quot,
				IF(total_b2c_quot_crt>0,total_b2c_quot_crt,0) as total_b2c_quot_crt,
                IF(total_b2c_unv>0,total_b2c_unv,0) as total_b2c_unv
                FROM (
                    SELECT
                    SUM(IF(bkg_status IN (2,3,5,6,7),1,0)) as total_book,
                    SUM(IF(bkg_status IN (2,3,5,6,7) AND bkg_booking_type IN (4,9,10,11,12,14,15,16),1,0)) as total_book_local,
                    SUM(IF(bkg_status IN (2,3,5,6,7) AND bkg_booking_type NOT IN (4,9,10,11,12,14,15,16),1,0)) as total_book_outstation,

                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_agent_id=450),1,0)) as total_mmtb2b,
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type IN (4,9,10,11,12,14,15,16) AND bkg_agent_id=450),1,0)) as total_mmtb2b_local,
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) AND bkg_agent_id=450),1,0)) as total_mmtb2b_outstation,
                   
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_agent_id=18190),1,0)) as total_ibibob2b,
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type IN (4,9,10,11,12,14,15,16) AND bkg_agent_id=18190),1,0)) as total_ibibob2b_local,
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) AND bkg_agent_id=18190),1,0)) as total_ibibob2b_outstation,

                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_agent_id NOT IN(450,18190) AND bkg_agent_id IS NOT NULL),1,0)) as total_b2b,
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type IN (4,9,10,11,12,14,15,16) AND bkg_agent_id NOT IN(450,18190) AND bkg_agent_id IS NOT NULL),1,0)) as total_b2b_local,
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) AND bkg_agent_id NOT IN(450,18190) AND bkg_agent_id IS NOT NULL),1,0)) as total_b2b_outstation,

                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type=7),1,0)) as total_gozoSuttleb2c,
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL ),1,0)) as total_b2c,
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type IN (4,9,10,11,12,14,15,16)  AND bkg_booking_type<>7 AND bkg_agent_id IS NULL ),1,0)) as total_b2c_local,
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL ),1,0)) as total_b2c_outstation,
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL AND bkg_create_user_type=1 AND bkg_confirm_user_type=1),1,0)) as total_b2c_user,
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL AND bkg_create_user_type=1 AND bkg_confirm_user_type<>1 ),1,0)) as total_b2c_other,
					SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL AND bkg_create_user_type=1 AND bkg_confirm_user_type IS NULL ),1,0)) as total_b2c_gn,
					SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL AND bkg_create_user_type=4 AND bkg_confirm_user_type=1 ),1,0)) as total_b2c_adminAssisted,
                    SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL AND bkg_create_user_type=4 AND bkg_confirm_user_type=4 ),1,0)) as total_b2c_admin,
					SUM(IF((bkg_status IN (2,3,5,6,7) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL AND bkg_create_user_type=4 AND bkg_confirm_user_type IS NULL ),1,0)) as total_b2c_admin_gn,
                    SUM(IF((bkg_status IN (15) AND bkg_agent_id IS NULL),1,0)) as total_b2c_quot,
                    SUM(IF((bkg_create_type IN (1) AND bkg_agent_id IS NULL),1,0)) as total_b2c_quot_crt,
                    SUM(IF((bkg_status IN (1) AND bkg_agent_id IS NULL),1,0)) as total_b2c_unv
                    FROM(
                        SELECT DISTINCT bkg_id,bkg_status,bkg_agent_id,bkg_create_type,bkg_booking_type,bkg_confirm_type,bkg_create_user_type,bkg_confirm_user_type
                        FROM `booking`
                        INNER JOIN booking_trail ON booking_trail.btr_bkg_id = bkg_id
			WHERE bkg_active=1 AND bkg_status IN (1,2,3,4,5,6,7,15)
                        $where
                    )a
                )a2";
		return DBUtil::queryRow($sql, DBUtil::SDB3());
	}

	public function getSmsNotification()
	{

		$sql = "SELECT booking_vendor_request.*, booking.bkg_pickup_date, from_city , to_city , vendors.vnd_id, contact_phone.phn_phone_no as vnd_phone, contact_phone.phn_phone_country_code as vnd_phone_country_code
                FROM `booking_vendor_request`
                INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking_vendor_request.bvr_bcb_id AND bcb_active=1 AND booking_vendor_request.bvr_active=1 AND booking_vendor_request.bvr_app_notification=0
                INNER JOIN booking ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_status=2
                JOIN (
                    SELECT cities.cty_name as from_city,cities.cty_id FROM `cities` WHERE 1
                ) frmtbl ON frmtbl.cty_id=booking.bkg_from_city_id
                JOIN (
                    SELECT cities.cty_name as to_city,cities.cty_id FROM `cities` WHERE 1
                ) totbl ON totbl.cty_id=booking.bkg_to_city_id

                INNER JOIN  `vendors` ON vendors.vnd_id=booking_vendor_request.bvr_vendor_id
                INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
                INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id
                INNER JOIN contact ON contact.ctt_id = vendors.vnd_contact_id
                INNER JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id AND contact_phone.phn_is_primary=1
                WHERE 1 AND  booking_vendor_request.bvr_sms_notification=0
                AND vendor_stats.vrs_vnd_overall_rating>=4 AND vendors.vnd_active=1 AND vendor_pref.vnp_is_freeze=0
                AND booking.bkg_create_date < DATE_SUB(NOW(),INTERVAL 8 HOUR)
                AND booking.bkg_pickup_date < DATE_ADD(NOW(),INTERVAL 36 HOUR)
                GROUP BY booking_vendor_request.bvr_bcb_id";
		return DBUtil::queryAll($sql);
	}

	public function getSmsNotificationData()
	{
		$result	 = [];
		$count	 = 0;
		$sql	 = "SELECT bkg.bkg_pickup_date,bkg.bkg_from_city_id,bkg.bkg_to_city_id,btr.btr_id FROM
				booking_cab bcb
				INNER JOIN booking bkg ON bkg.bkg_bcb_id=bcb.bcb_id AND bkg.bkg_active=1
				INNER JOIN booking_trail btr ON btr.btr_bkg_id=bkg.bkg_id
				LEFT JOIN booking_vendor_request bvr ON bvr.bvr_bcb_id=bcb.bcb_id AND bvr.bvr_active=1
				WHERE bkg.bkg_create_date < DATE_SUB(NOW(),INTERVAL 8 HOUR)
				AND bkg.bkg_pickup_date < DATE_ADD(NOW(),INTERVAL 36 HOUR)
				AND bkg.bkg_status=2 AND bvr.bvr_id IS NULL
				AND bcb.bcb_active=1";
		$res	 = DBUtil::queryAll($sql);
		if(count($res) > 0)
		{
			foreach($res as $row)
			{
				$sql1	 = "SELECT DISTINCT vnd1.vnd_id, contact_phone.phn_phone_no as vnd_phone, contact_phone.phn_phone_country_code as vnd_phone_country_code
						FROM cities cty
						INNER JOIN zone_cities zct ON cty.cty_id = zct.zct_cty_id
						INNER JOIN vendor_pref vnp ON FIND_IN_SET(zct.zct_zon_id, vnp.vnp_accepted_zone)
						INNER JOIN vendors vnd ON vnd.vnd_id = vnp.vnp_vnd_id
						INNER JOIN vendors  vnd1 ON vnd1.vnd_id = vnd.vnd_ref_code
						INNER JOIN contact ON contact.ctt_id = vnd.vnd_contact_id
						INNER JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id AND contact_phone.phn_is_primary=1
						WHERE
						cty.cty_id IN(" . $row['bkg_from_city_id'] . "," . $row['bkg_to_city_id'] . ") AND vnd1.vnd_active=1 AND vnp.vnp_is_freeze=0 AND vnp.vnp_cod_freeze=0";
				$res1	 = DBUtil::queryAll($sql1);
				if(count($res1) > 0)
				{
					foreach($res1 as $row1)
					{
						$result[$count]['bkg_pickup_date']			 = $row['bkg_pickup_date'];
						$result[$count]['bkg_from_city_id']			 = $row['bkg_from_city_id'];
						$result[$count]['bkg_to_city_id']			 = $row['bkg_to_city_id'];
						$result[$count]['vnd_id']					 = $row1['vnd_id'];
						$result[$count]['vnd_phone']				 = $row1['vnd_phone'];
						$result[$count]['vnd_phone_country_code']	 = $row1['vnd_phone_country_code'];
						$result[$count]['btr_id']					 = $row['btr_id'];
						$count										 += 1;
					}
				}
			}
		}
		return $result;
	}

	public function getBookingCount($by = 1)
	{
		if($by == 11)
		{
			if($this->from_date != null && $this->to_date != null)
			{
				$cond = " AND (bkg_agent_id IN(450,18190) OR bkg_agent_id IS NULL) AND (bkg_create_date BETWEEN '$this->from_date' AND '$this->to_date')";
			}
			else
			{
				$cond = " AND (bkg_agent_id IN(450,18190) OR bkg_agent_id IS NULL) AND (bkg_create_date BETWEEN CONCAT(CURDATE(),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59'))";
			}
		}
		else if($by == 1)
		{
			$cond = " AND (bkg_create_date BETWEEN CONCAT(CURDATE(),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59'))";
		}
		else if($by == 2)
		{
			$cond = " AND (bkg_pickup_date  BETWEEN CONCAT(CURDATE(),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59'))";
		}
		else if($by == 3)
		{
			$cond = " AND (bkg_create_date  BETWEEN CONCAT(SUBDATE(CURDATE(),1),' 00:00:00') AND CONCAT(SUBDATE(CURDATE(),1),' 23:59:59'))";
		}
		else
		{
			$cond = " AND (bkg_pickup_date  BETWEEN CONCAT(SUBDATE(CURDATE(),1),' 00:00:00') AND CONCAT(SUBDATE(CURDATE(),1),' 23:59:59'))";
		}

		$sql = "SELECT 
					IF(agt_id IS NULL,'B2C', IF(agents.agt_company IS NULL OR agents.agt_company='', CONCAT(agt_fname,' ',agt_lname), agents.agt_company)) as name, '0000' as seq
					
						, SUM(IF(bkg_status <> 9, 1, 0)) AS ry_booking_count
					, SUM(IF(bkg_status <> 9 AND booking.bkg_booking_type IN (4,9,10,11,12,14,15,16), 1, 0)) AS ry_booking_count_local
					, SUM(IF(bkg_status <> 9 AND booking.bkg_booking_type NOT IN (4,9,10,11,12,14,15,16), 1, 0)) AS ry_booking_count_outstation

						, SUM(IF(bkg_advance_amount > 0 AND booking.bkg_status <> 9, 1, 0)) AS ry_adv_booking_count
					, SUM(IF(bkg_advance_amount > 0 AND booking.bkg_booking_type IN (4,9,10,11,12,14,15,16) AND booking.bkg_status <> 9, 1, 0)) AS ry_adv_booking_count_local
					, SUM(IF(bkg_advance_amount > 0  AND booking.bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) AND booking.bkg_status <> 9, 1, 0)) AS ry_adv_booking_count_outstation
					
						, SUM(IF(bkg_status = 9, 1, 0)) AS ry_cancelled_booking_count
					, SUM(IF(bkg_status = 9 AND booking.bkg_booking_type IN (4,9,10,11,12,14,15,16), 1, 0)) AS ry_cancelled_booking_count_local
					, SUM(IF(bkg_status = 9 AND booking.bkg_booking_type NOT IN (4,9,10,11,12,14,15,16), 1, 0)) AS ry_cancelled_booking_count_outstation

						 ,SUM(IF(bkg_status = 9 and bkg_cancel_id IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38) , 1, 0)) AS ry_gozo_cancelled_booking_count
					,SUM(IF(bkg_status = 9 AND booking.bkg_booking_type IN (4,9,10,11,12,14,15,16) and bkg_cancel_id IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38) , 1, 0)) AS ry_gozo_cancelled_booking_count_local
					,SUM(IF(bkg_status = 9 AND booking.bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) and bkg_cancel_id IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38) , 1, 0)) AS ry_gozo_cancelled_booking_count_outstation
					
						, SUM(IF(bkg_status <> 9 AND bkg_reconfirm_flag=1, (bkg_net_base_amount), 0)) AS ry_booking_amount
					, SUM(IF(bkg_status <> 9 AND booking.bkg_booking_type IN (4,9,10,11,12,14,15,16)  AND bkg_reconfirm_flag=1, (bkg_net_base_amount), 0)) AS ry_booking_amount_local
					, SUM(IF(bkg_status <> 9 AND booking.bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) AND bkg_reconfirm_flag=1, (bkg_net_base_amount), 0)) AS ry_booking_amount_outstation

						, SUM(IF(bkg_status <> 9 AND bkg_reconfirm_flag=1, bkg_gozo_amount- IFNULL(bkg_credits_used,0),0)) AS ry_gozo_amount
					, SUM(IF(bkg_status <> 9 AND booking.bkg_booking_type IN (4,9,10,11,12,14,15,16) AND bkg_reconfirm_flag=1, bkg_gozo_amount- IFNULL(bkg_credits_used,0),0)) AS ry_gozo_amount_local
					, SUM(IF(bkg_status <> 9 AND booking.bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) AND bkg_reconfirm_flag=1, bkg_gozo_amount- IFNULL(bkg_credits_used,0),0)) AS ry_gozo_amount_outstation


						, SUM(IF(bkg_status <> 9 AND bkg_reconfirm_flag=1, bkg_total_amount-bkg_quoted_vendor_amount-IFNULL(bkg_credits_used,0)-IFNULL(bkg_service_tax,0)-IFNULL(bkg_partner_commission,0), 0)) AS ry_quote_vendor_amount
					, SUM(IF(bkg_status <> 9 AND booking.bkg_booking_type IN (4,9,10,11,12,14,15,16) AND bkg_reconfirm_flag=1, bkg_total_amount-bkg_quoted_vendor_amount-IFNULL(bkg_credits_used,0)-IFNULL(bkg_service_tax,0)-IFNULL(bkg_partner_commission,0), 0)) AS ry_quote_vendor_amount_local
					, SUM(IF(bkg_status <> 9 AND booking.bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) AND bkg_reconfirm_flag=1, bkg_total_amount-bkg_quoted_vendor_amount-IFNULL(bkg_credits_used,0)-IFNULL(bkg_service_tax,0)-IFNULL(bkg_partner_commission,0), 0)) AS ry_quote_vendor_amount_outstation
						, SUM(IF(bkg_status = 9, (bkg_gozo_amount - bkg_service_tax - ROUND(IF(agents.agt_type=2,IFNULL(agt_commission,0),0) * (bkg_net_base_amount)*.01)), 0)) AS ry_cancelled_gozo_amount
					, SUM(IF(bkg_status = 9 AND booking.bkg_booking_type IN (4,9,10,11,12,14,15,16) , (bkg_gozo_amount - bkg_service_tax - ROUND(IF(agents.agt_type=2,IFNULL(agt_commission,0),0) * (bkg_net_base_amount)*.01)), 0)) AS ry_cancelled_gozo_amount_local
					, SUM(IF(bkg_status = 9 AND booking.bkg_booking_type NOT IN (4,9,10,11,12,14,15,16), (bkg_gozo_amount - bkg_service_tax - ROUND(IF(agents.agt_type=2,IFNULL(agt_commission,0),0) * (bkg_net_base_amount)*.01)), 0)) AS ry_cancelled_gozo_amount_outstation
						 FROM   `booking`
						 INNER JOIN booking_cab ON bcb_id=bkg_bcb_id AND bkg_active = 1
						 INNER JOIN booking_invoice ON bkg_id=biv_bkg_id
						 INNER JOIN booking_pref ON bkg_id=bpr_bkg_id AND (bkg_status IN (2, 3, 5, 6, 7) OR (bkg_status=9 AND bkg_cancel_id NOT IN (7,24)) AND bkg_tentative_booking=0)
						 LEFT JOIN agents ON agt_id=bkg_agent_id
						 WHERE  1  $cond GROUP BY bkg_agent_id ";

		$recordset = DBUtil::query($sql, DBUtil::SDB3());
		return $recordset;
	}

	public function getAgentGatewayStatus($bkgId)
	{
		try
		{
			if($bkgId)
			{
				$sql = "SELECT bkg.bkg_id,agt.agt_id,agt.agt_use_gateway as gateway FROM booking bkg
                    LEFT JOIN agents agt ON agt.agt_id = bkg.bkg_agent_id
                WHERE bkg.bkg_id = $bkgId";
				return DBUtil::queryAll($sql);
			}
		}
		catch(Exception $e)
		{
			throw new Exception("Unknown exception");
			Logger::exception($e);
		}
	}

	public function getBkgId($bkgId)
	{
		$sql = "SELECT bkg.bkg_id as bkgId FROM booking bkg
                WHERE bkg.bkg_booking_id = '$bkgId'";
		return DBUtil::queryRow($sql);
	}

	public function checkPenaltyByBcbId($bcbId)
	{
		$sql = "SELECT isPenalty  FROM
                    (
                        SELECT
                        IF(pickup_datetime>pickup12_datetime,1,0) as is_12hr ,
                        IF(pickup_datetime>pickup4_datetime,1,0) as is_4hr ,
                        pickup4_datetime , pickup12_datetime, pickup_datetime,
                        vendor_assign_date, vendor_assign_date_1hr,
                        (CASE WHEN ((pickup_datetime < pickup4_datetime) AND (vendor_assign_date_1hr>vendor_assign_date)) THEN '1' WHEN ((pickup_datetime < pickup12_datetime)  AND (vendor_assign_date_1hr>vendor_assign_date)) THEN '2' ELSE '0' END) as isPenalty
                        FROM
                        (
                            SELECT
                            booking_cab.bcb_vendor_id,
                            DATE_ADD(NOW(), INTERVAL 4 HOUR) AS pickup4_datetime,
                            DATE_ADD(NOW(), INTERVAL 12 HOUR) AS pickup12_datetime,
                            DATE_SUB(NOW(), INTERVAL 1 HOUR) AS vendor_assign_date_1hr,
                            booking_invoice.bkg_gozo_amount,
                            MAX(booking_log.blg_created) AS vendor_assign_date,
                            MAX(booking.bkg_pickup_date) AS pickup_datetime
                            FROM
                            `booking_cab`
                            INNER JOIN `booking` ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking.bkg_active = 1 AND booking_cab.bcb_active = 1
                            INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id = booking.bkg_id
                            LEFT JOIN `vendors` ON vendors.vnd_id = booking_cab.bcb_vendor_id
                            LEFT JOIN `booking_log` ON booking_log.blg_booking_id = booking.bkg_id AND booking_log.blg_active = 1 AND booking_log.blg_event_id IN(7)
                            WHERE
                            booking_cab.bcb_id = $bcbId
                            GROUP BY
                            booking_cab.bcb_id
                            )a
                    )final   ";

		$isPenalty = DBUtil::command($sql)->queryScalar();
		return $isPenalty;
	}

	public function getUpcomingBookingsByAgent($agentId)
	{
		if(trim($agentId) == null || trim($agentId) == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params		 = array('agentId' => $agentId);
		$removeVal	 = '"';
		$sql		 = "SELECT
					bkg_booking_id
					, REPLACE(JSON_EXTRACT(`bkg_route_city_names`, '$[0]'), '$removeVal', '') AS fromcity
					, REPLACE(JSON_EXTRACT(`bkg_route_city_names`, CONCAT('$[', JSON_LENGTH(`bkg_route_city_names`) - 1, ']')), '$removeVal', '') AS tocity
					, bkg_booking_type
					, booking_user.bkg_user_fname,
					booking_user.bkg_user_lname,
					booking_user.bui_id,
					booking_user.bkg_contact_no,
					booking_user.bkg_user_email,
					bkg_pickup_address,
					bkg_drop_address,
					bkg_pickup_date,
					drv.drv_name,
					contact_phone.phn_phone_no AS drv_phone,
					bkg_agent_ref_code,
					vc.vct_label cabType,
					CONCAT(vct.vht_model, ' (', vehicles.vhc_number, ')') cab
					FROM  booking
					JOIN booking_cab bcab ON bcab.bcb_id = bkg_bcb_id
					JOIN booking_user ON booking.bkg_id = booking_user.bui_bkg_id
					JOIN svc_class_vhc_cat scvhc ON scvhc.scv_id = booking.bkg_vehicle_type_id
					INNER JOIN vehicle_category vc ON scvhc.scv_vct_id = vc.vct_id
					LEFT JOIN vehicles ON vehicles.vhc_id = bcab.bcb_cab_id
					LEFT JOIN vehicle_types vct ON vct.vht_id = vehicles.vhc_type_id
						LEFT JOIN drivers drv ON drv.drv_id = bcab.bcb_driver_id and drv.drv_id = drv.drv_ref_code and drv.drv_active =1
						LEFT JOIN contact_profile as cp on cp.cr_is_driver = drv.drv_id and cp.cr_status =1
						LEFT JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_active =1
					LEFT JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id AND contact_phone.phn_is_primary = 1 AND contact_phone.phn_active = 1
						WHERE bkg_status IN (2, 3, 5) AND bkg_pickup_date > NOW() AND bkg_agent_id = :agentId GROUP BY bkg_id
					ORDER BY bkg_pickup_date ASC";
		return DBUtil::queryAll($sql, DBUtil::MDB(), $params);
	}

	public function getManualAssignmentBkg()
	{
		$sql = "SELECT booking.bkg_id, booking.bkg_create_date, booking.bkg_pickup_date, NOW(),booking_pref.bkg_manual_assignment
                FROM `booking`
                INNER JOIN booking_pref ON booking_pref.bpr_bkg_id = booking.bkg_id
                INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
                WHERE booking.bkg_status=2 AND bkg_reconfirm_flag=1 AND booking.bkg_active=1 AND booking_pref.bkg_manual_assignment!=1
                AND
                (
					(booking.bkg_create_date <= DATE_SUB(NOW(),INTERVAL 60 HOUR) AND booking.bkg_pickup_date <= DATE_ADD(NOW(),INTERVAL 60 HOUR))
					OR
                    (booking.bkg_create_date <= DATE_SUB(NOW(),INTERVAL 24 HOUR) AND booking.bkg_pickup_date <= DATE_ADD(NOW(),INTERVAL 48 HOUR))
                    OR
                    (booking.bkg_create_date <= DATE_SUB(NOW(),INTERVAL 12 HOUR) AND booking.bkg_pickup_date <= DATE_ADD(NOW(),INTERVAL 36 HOUR))
                    OR
                    (booking.bkg_create_date <= DATE_SUB(NOW(),INTERVAL 6 HOUR) AND booking.bkg_pickup_date <= DATE_ADD(NOW(),INTERVAL 24 HOUR))
                    OR
                    (booking.bkg_create_date <= DATE_SUB(NOW(),INTERVAL 3 HOUR) AND booking.bkg_pickup_date <= DATE_ADD(NOW(),INTERVAL 12 HOUR))
                    OR
                    (booking.bkg_pickup_date <= DATE_ADD(NOW(),INTERVAL 8 HOUR))
                )";
		return DBUtil::queryAll($sql);
	}

	public function getRegionWiseTodaysBooking()
	{
		$where = "";
		if($this->from_date != null && $this->to_date != null)
		{
			$where = " AND (booking.bkg_create_date BETWEEN '$this->from_date' AND '$this->to_date') ";
		}
		else
		{
			$where = " AND (booking.bkg_create_date  BETWEEN CONCAT(CURDATE(),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59')) ";
		}
		$sql = "SELECT
                    region,
					SUM(cntb2bmmt) AS cntb2bmmt,
					SUM(cntb2bibibo) AS cntb2bibibo,
                    SUM(cntb2bothers) AS cntb2bothers,
                    SUM(cntb2c) AS countB2C,
                    SUM(countBook) AS countBook
                FROM
                    (
                    SELECT
					COUNT(DISTINCT IF(booking.bkg_agent_id = 450,bkg_id,NULL)) AS cntb2bmmt,
					COUNT(DISTINCT IF(booking.bkg_agent_id = 18190,bkg_id,NULL)) AS cntb2bibibo,
					COUNT(DISTINCT IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id NOT IN (450,18190) ,bkg_id,NULL)) AS cntb2bothers,
                    COUNT(DISTINCT IF(booking.bkg_agent_id IS NULL,bkg_id,NULL)) AS cntb2c,
                        states.stt_zone,
                        (
                            CASE states.stt_zone WHEN 1 THEN 'North' WHEN 2 THEN 'West' WHEN 3 THEN 'Central' WHEN 4 THEN 'South' WHEN 5 THEN 'East' WHEN 6 THEN 'North East' WHEN 7 THEN 'South'
                        END
                ) AS region,
                COUNT(1) AS countBook
                FROM
                `booking`
                JOIN `cities` ON cities.cty_id = booking.bkg_from_city_id
                JOIN `states` ON states.stt_id = cities.cty_state_id
                WHERE booking.bkg_status IN(2, 3, 5, 6, 7) $where AND booking.bkg_active = 1
                GROUP BY states.stt_zone
                ORDER BY region) a
                GROUP BY region";

		return DBUtil::queryAll($sql, DBUtil::SDB3());
	}

	public function validateMatch($upBookingID, $downBookingID)
	{
		$sql		 = "SELECT Count(1) as cnt FROM booking
                        INNER JOIN booking_cab ON bkg_bcb_id=bcb_id
						LEFT JOIN agents ON bkg_agent_id = agt_id AND agt_allow_smartmatch = 1
                    WHERE bkg_status IN (2) AND (bkg_agent_id IS NULL OR agt_id IS NOT NULL) AND bkg_id IN ($upBookingID,$downBookingID) HAVING cnt>=2";
		$recordset	 = DBUtil::queryRow($sql);
		return $recordset['cnt'];
	}

	public function getPartnerBusinessReport($week = false)
	{

		$sql = "SELECT  (CASE   WHEN agents.agt_company <>'' THEN agents.agt_company
                                WHEN agents.agt_owner_name <> '' THEN agents.agt_owner_name
                                WHEN agents.agt_fname <> '' AND agents.agt_lname <> '' THEN CONCAT(agents.agt_company,' ', agents.agt_lname)
                        END) as partner_name,
                        DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY) as week_start_today,
                        CURDATE() as today,
                        SUM(IF(date(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)) AS gmv_agmt_last_week_cnt,
                        ROUND(SUM(IF(date(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),biv.bkg_total_amount,0)),2) AS gmv_agmt_last_week,
                        SUM(IF(date(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)) AS gmv_agmt_wtd_cnt,
						ROUND(SUM(IF(date(booking.bkg_create_date) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),biv.bkg_total_amount,0)),2) AS gmv_agmt_wtd,
                        SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_agmt_today_cnt,
                        ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_total_amount,0)),2) as gmv_agmt_today,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_agmt_today1_cnt,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_total_amount,0)),2) as gmv_agmt_today1,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),1,0)) as gmv_agmt_today2_cnt,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_total_amount,0)),2) as gmv_agmt_today2,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%d%m%Y'),biv.bkg_total_amount,0)),2) as gmv_agmt_tommrrow,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_agmt_month1_cnt,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_total_amount,0)),2) as gmv_agmt_month1,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_agmt_month2_cnt,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_total_amount,0)),2) as gmv_agmt_month2,
                        SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),1,0)) as gmv_agmt_mtd_cnt,
                        ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%m%Y') = DATE_FORMAT(booking.bkg_create_date,'%m%Y'),biv.bkg_total_amount,0)),2) as gmv_agmt_mtd,
                        SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as gmv_agmt_ytd_cnt,
                        ROUND(SUM(IF(DATE_FORMAT(CURDATE(),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_total_amount,0)),2) as gmv_agmt_ytd,
                        SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),1,0)) as gmv_agmt_last_year_cnt,
                        ROUND(SUM(IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 YEAR),'%Y') = DATE_FORMAT(booking.bkg_create_date,'%Y'),biv.bkg_total_amount,0)),2) as gmv_agmt_last_year,
                        ROUND(SUM(biv.bkg_total_amount),2) as gmv_agmt_lifetime,
                        COUNT(1) as cout_agmt_lifetime
                        FROM `booking`
					    INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
                        INNER JOIN `agents` ON agents.agt_id=booking.bkg_agent_id
                        WHERE booking.bkg_active=1
                        AND booking.bkg_status IN (2,3,5,6,7,9)
                        AND booking.bkg_create_date > '2015-10-01 23:59:59'
                        AND booking.bkg_agent_id>0";
		if($week == true)
		{
			$sql .= " AND booking.bkg_create_date BETWEEN DATE_SUB(NOW(),INTERVAL 7 DAY) AND NOW()";
		}

		$sql .= " GROUP BY booking.bkg_agent_id ORDER BY gmv_agmt_wtd_cnt DESC, gmv_agmt_mtd_cnt DESC, gmv_agmt_month1_cnt DESC LIMIT 0,10";
		return DBUtil::queryAll($sql);
	}

	public function getPartnerReportHtml1()
	{
		$data	 = $this->getPartnerBusinessReport(true);
		$html	 = "<b>Top 10 partners this week inc. cancel :</b> (<i> By create date && status [2,3,4,5,6,7,9] </i>) <br/>
                 <table width='90%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr><th>Partner</th>
                    <th style='text-align:center'>Last Week (Count)</th>
                    <th style='text-align:center'>Week To Date (Count)</th>
                    <th style='text-align:center'>Today-1 (Count)</th>
                    <th style='text-align:center'>Today-2 (Count)</th>
                    <th style='text-align:center'>Today (Count)</th>
                    <th style='text-align:center'>Tommrrow</th>
                </tr>";
		if(count($data) > 0)
		{
			foreach($data as $row)
			{
				$html .= "<tr><th style='text-align:left'>" . $row['partner_name'] . "</th>
                    <td style='text-align:right'>" . number_format($row['gmv_agmt_last_week'], 2) . " (" . $row['gmv_agmt_last_week_cnt'] . ")</td>
                    <td style='text-align:right'>" . number_format($row['gmv_agmt_wtd'], 2) . " (" . $row['gmv_agmt_wtd_cnt'] . ")</td>
                    <td style='text-align:right'>" . number_format($row['gmv_agmt_today1'], 2) . " (" . $row['gmv_agmt_today1_cnt'] . ")</td>
                    <td style='text-align:right'>" . number_format($row['gmv_agmt_today2'], 2) . " (" . $row['gmv_agmt_today2_cnt'] . ")</td>
                    <td style='text-align:right'>" . number_format($row['gmv_agmt_today'], 2) . " (" . $row['gmv_agmt_today_cnt'] . ")</td>
                    <td style='text-align:center'>NA</td>
                </tr>";
			}
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function getPartnerReportHtml2()
	{
		$month			 = date("m", strtotime(date('Y-m-d')));
		$month1			 = date("m", strtotime(" -1 months"));
		$month2			 = date("m", strtotime(" -2 months"));
		$yearFromDate	 = date("Y-01-01");
		$monthFromDate	 = date("Y-m-01");
		$toDate			 = date("Y-m-d");
		$filterObj		 = new Filter();

		$monthAvg	 = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
		$month1Avg	 = cal_days_in_month(CAL_GREGORIAN, $month1, date('Y'));
		$month2Avg	 = cal_days_in_month(CAL_GREGORIAN, $month2, date('Y'));
		$MTDDays	 = ($filterObj->dateCount($monthFromDate, $toDate) + 1);
		$YTDDays	 = ($filterObj->dateCount($yearFromDate, $toDate) + 1);

		$data	 = $this->getPartnerBusinessReport(false);
		$html	 = "<b>Top 10 partners bookings inc. cancel :</b> (<i> By create date && status [2,3,4,5,6,7,9] </i>) <br/>
                 <table width='90%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr><th>Partner</th>
                    <th style='text-align:center'>Lifetime (Count)</th>
                    <th style='text-align:center'>YTD (Count)</th>
                    <th style='text-align:center'>Month-2 (Count)</th>
                    <th style='text-align:center'>Month-1 (Count)</th>
                    <th style='text-align:center'>Month To Date (Count)</th>
                    <th style='text-align:center'>Avg/Day (Month-2)</th>
                    <th style='text-align:center'>Avg/Day (Month-1)</th>
                    <th style='text-align:center'>Avg/Day (MTD)</th>
                    <th style='text-align:center'>Avg/Day (YTD)</th>
                </tr>";
		if(count($data) > 0)
		{
			foreach($data as $row)
			{
				$html .= "<tr><th style='text-align:left'>" . $row['partner_name'] . "</th>
                    <td style='text-align:right'>" . number_format($row['gmv_agmt_lifetime'], 2) . " (" . $row['cout_agmt_lifetime'] . ")</td>
                    <td style='text-align:right'>" . number_format($row['gmv_agmt_ytd'], 2) . " (" . $row['gmv_agmt_ytd_cnt'] . ")</td>
                    <td style='text-align:right'>" . number_format($row['gmv_agmt_month2'], 2) . " (" . $row['gmv_agmt_month2_cnt'] . ")</td>
                    <td style='text-align:right'>" . number_format($row['gmv_agmt_month1'], 2) . " (" . $row['gmv_agmt_month1_cnt'] . ")</td>
                    <td style='text-align:right'>" . number_format($row['gmv_agmt_mtd'], 2) . " (" . $row['gmv_agmt_mtd_cnt'] . ")</td>
                    <td style='text-align:right'>" . number_format(($row['gmv_agmt_month2'] / $month2Avg), 2) . "</td>
                    <td style='text-align:right'>" . number_format(($row['gmv_agmt_month1'] / $month1Avg), 2) . "</td>
                    <td style='text-align:right'>" . number_format(($row['gmv_agmt_mtd'] / $MTDDays), 2) . "</td>
                    <td style='text-align:right'>" . number_format(($row['gmv_agmt_ytd'] / $YTDDays), 2) . "</td>
                </tr>";
			}
		}
		$html .= "</table><br/>";
		return $html;
	}

	public function addExtraCharges($bkgId, $bkgExtraCharge = 0, $bkgExtraTotalKm = 0, $bkgExtraTollTax = 0, $bkgExtraStateTax = 0, $bkgParkingCharge = 0, UserInfo $userInfo = null, $vendorActualCollected = 0, $bkgExtraMin = 0, $bkgExtraMinCharges)
	{
		////$transaction = DBUtil::beginTransaction();
		try
		{
			$success				 = false;
			/* @var $model Booking */
			$model					 = Booking::model()->findByPk($bkgId);
			$bkgExtraCharge			 = ($bkgExtraCharge > 0 && $bkgExtraTotalKm > 0) ? $bkgExtraCharge : round($bkgExtraTotalKm * $model->bkgInvoice->bkg_rate_per_km_extra);
			$bkgTotalExtraMinCharge	 = ($bkgExtraMin > 0 && $bkgExtraMinCharges > 0) ? round($bkgExtraMin * $bkgExtraMinCharges) : 0;
			$oldModel				 = $model;
			if(!$model->bkg_id > 0)
			{
				throw new Exception("Booking Id not found", ReturnSet::ERROR_INVALID_DATA);
			}
			if($bkgExtraCharge > 0 || $bkgExtraTollTax > 0 || $bkgExtraStateTax > 0 || $bkgParkingCharge > 0)
			{
				$gstRate										 = $model->bkgInvoice->getServiceTaxRate();
				$bkg_extra_charge								 = $model->bkgInvoice->bkg_rate_per_km_extra * $bkgExtraTotalKm;
				//$model->bkgInvoice->bkg_extra_charge          = round($bkg_extra_charge * (100 / (100 + $gstRate)));
				$model->bkgInvoice->bkg_extra_km				 = round($bkgExtraTotalKm);
				$model->bkgInvoice->bkg_extra_km_charge			 = round($bkgExtraCharge);
				$model->bkgInvoice->bkg_extra_min				 = round($bkgExtraMin);
				$model->bkgInvoice->bkg_extra_per_min_charge	 = round($bkgExtraMinCharges);
				$model->bkgInvoice->bkg_extra_total_min_charge	 = round($bkgTotalExtraMinCharge);
				$model->bkgInvoice->bkg_extra_toll_tax			 = $bkgExtraTollTax; //round($bkgExtraTollTax * (100 / (100 + $gstRate)));
				$model->bkgInvoice->bkg_extra_state_tax			 = $bkgExtraStateTax; //round($bkgExtraStateTax * (100 / (100 + $gstRate)));
				$model->bkgInvoice->bkg_parking_charge			 = $bkgParkingCharge; //round($bkgParkingCharge * (100 / (100 + $gstRate)));
				$model->bkgInvoice->bkg_vendor_actual_collected	 = round($vendorActualCollected);
			}
			$params									 = array('blg_ref_id' => BookingLog::REF_RIDE_COMPLETE, 'blg_booking_status' => $model->bkg_status);
			$totalExtraCharge						 = $model->bkgInvoice->getExtraCollected();
			$totalExtraVendorCharge					 = $model->bkgInvoice->getVendorShareExtraCharges();
			$model->bkgInvoice->populateAmount(true, false, true, false, $model->bkg_agent_id);
			$model->bkgInvoice->bkg_vendor_amount	 = round($model->bkgInvoice->bkg_vendor_amount + $model->bkgInvoice->getVendorShareExtraCharges());

			// Extra Charge, State, Toll, Parking Charges
			$totalExtra = $model->bkgInvoice->grossExtraCharges();

			$dueAmount								 = ($model->bkgInvoice->bkg_total_amount - ($model->bkgInvoice->bkg_advance_amount + $model->bkgInvoice->bkg_credits_used));
			$model->bkgInvoice->bkg_vendor_collected = round($dueAmount);

			if($model->bkgInvoice->bkg_corporate_remunerator == 2)
			{
				#$model->bkgInvoice->bkg_advance_amount = $model->bkgInvoice->bkg_advance_amount + $model->bkgInvoice->bkg_vendor_collected - $model->bkgInvoice->bkg_vendor_actual_collected;
				$model->bkgInvoice->bkg_vendor_collected = 0 + $model->bkgInvoice->bkg_vendor_actual_collected;
			}

			if($model->bkgInvoice->save())
			{
				//update partner commission and gozoamount
				$model->bkgInvoice->refresh();
				//$model->bkgInvoice->calculateDues();
				$model->bkgInvoice->calculateTotal();
				$model->bkgInvoice->save();
				if($model->bkg_bcb_id)
				{
					$modelBcb					 = BookingCab::model()->findByPk($model->bkg_bcb_id);
					$modelBcb->bcb_vendor_amount = round($modelBcb->bcb_vendor_amount + $totalExtraVendorCharge);
					$modelBcb->save();
				}
			}
			else
			{
				throw new Exception("Extra Charges can not saved.\n\t\t" . json_encode($model->bkgInvoice->getErrors()));
			}

			//DBUtil::commitTransaction($transaction);
			$success = true;
		}
		catch(Exception $e)
		{
			//	DBUtil::rollbackTransaction($transaction);
			throw $e;
		}
		return $success;
	}

	public function getMMTBkgId($refId)
	{
		$sql	 = "SELECT bkg_id FROM `booking` WHERE `bkg_agent_ref_code` = '$refId' ";
		/* @var $cdb CDbCommand */
		$data	 = DBUtil::queryRow($sql);
		return $data['bkg_id'];
	}

	public function getDuplicateBkgId($bkgId)
	{
		$sql	 = "SELECT COUNT(rtg_booking_id) rtgcount FROM `ratings` WHERE `rtg_booking_id` = '$bkgId' ";
		/* @var $cdb CDbCommand */
		$data	 = DBUtil::queryRow($sql);
		return $data['rtgcount'];
	}

	public function getBkgsByFlexxiBkg($bkgId)
	{
		$bcbId = Booking::getTripId($bkgId);

		$sql = "SELECT
				booking.bkg_id,bkg_ride_start,booking_track.bkg_ride_complete
				FROM
					`booking`
				JOIN booking_track ON booking.bkg_id = booking_track.btk_bkg_id
				WHERE
					(bkg_bcb_id='$bcbId' AND booking.bkg_flexxi_type IN(1,2)) OR (booking.bkg_id = '$bkgId' AND booking.bkg_booking_type <> 6)";
		return DBUtil::queryAll($sql);
	}

	public function pushMegaCancel($bkgId)
	{
		try
		{
			$model	 = Booking::model()->findByPk($bkgId);
			$booking = $model->bkg_booking_id; //$this->bkg_booking_id; //OW180280338
			$data	 = [
				"bookingId"			 => $booking,
				"bookingStatusCode"	 => $model->bkg_status,
				"bookingStatus"		 => "Cancelled",
				"cancelReasonId"	 => $model->bkg_cancel_id,
				"cancellationReason" => $model->bkg_cancel_delete_reason,
				"cancellationCharge" => $model->bkg_cancel_charge,
				"refundAmount"		 => $model->bkg_refund_amount,
				"cancelledBy"		 => "Customer"
			];

			$fName					 = 'vendorCancellation';
			$responseParamList		 = $this->callMegaCabAPI($fName, $data);
			$typeAction				 = 3;
			$updatePartnerAPIDetails = $this->partnerApiTracking($typeAction, $data, $responseParamList, $bkgId);
			return $responseParamList;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function getFinancialDataByPickup($type = '', $fromDate = '', $toDate = '')
	{
		$whereCondition = '';
		If($fromDate != '' && $toDate != '')
		{
			$whereCondition .= " AND booking.bkg_pickup_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59'";
		}
		$sql = "SELECT   DATE_FORMAT(createDate, '%Y -%m') AS pickupDateRange,
         SUM(GMV) AS gmv_created,
         SUM(isTripCreated) AS tripCreated,
         SUM(isBookingCreated) AS bookingCreated,
         SUM(isTripCompleted) AS tripCompleted,
         SUM(isBookingCompleted) AS bookingCompleted,
         SUM(isMatchTripCompleted) AS matchTripCompleted,
         SUM(isUnmatchTripCompleted) AS unmatchTripCompleted,
         SUM(isTripCancelled) AS tripCancelled,
         SUM(isBookingCancelled) AS bookingCancelled,
         SUM(isB2BCompleted) AS B2bCompleted,
         SUM(isB2CCompleted) AS B2cCompleted,
         SUM(isB2BBKGCompleted) AS B2BBKGCompleted,
         SUM(isB2CBKGCompleted) AS B2CBKGCompleted,
         SUM(matchTotalAmount) AS matchTotalAmount,
         SUM(unMatchTotalAmount) AS unMatchTotalAmount,
         SUM(totalAmount) AS gmv_completed,
         SUM(matchVendorAmount) AS matchVendorAmount,
         SUM(unMatchVendorAmount) AS unMatchVendorAmount,
         SUM(bcb_vendor_amount) AS vendorAmount,
         (SUM(matchTotalAmount) - SUM(matchVendorAmount)) AS matchGozoAmount,
         (SUM(unMatchTotalAmount) - SUM(unMatchVendorAmount)) AS unmatchGozoAmount,
         (SUM(totalAmount) - SUM(bcb_vendor_amount)) AS gozoAmount,
         (SUM(B2CTotalAmount) - SUM(B2CVendorAmount)) AS B2CgozoAmount,
         (SUM(B2BTotalAmount) - SUM(B2BVendorAmount)) AS B2BgozoAmount
FROM     (SELECT   bcb_id
                   , SUM(booking_invoice.bkg_total_amount) AS GMV
                   , (SUM(booking_invoice.bkg_total_amount) - booking_cab.bcb_vendor_amount) AS gozoAmount
                   , (SUM(booking_invoice.bkg_total_amount - booking_invoice.bkg_vendor_amount)) AS gozoUnmatchedAmount
                   , MIN(booking.bkg_create_date) AS createDate
                   , IF(booking.bkg_status IN (6, 7), booking_cab.bcb_vendor_amount, 0) AS bcb_vendor_amount
                   , IF(booking.bkg_status IN (2, 6, 7, 9), 1, 0) AS isTripCreated
                   , SUM(IF(booking.bkg_status IN (2, 6, 7, 9), 1, 0)) AS isBookingCreated
                   , IF(booking.bkg_status IN (6, 7), 1, 0) AS isTripCompleted
                   , SUM(IF(booking.bkg_status IN (6, 7), 1, 0)) AS isBookingCompleted
                   , IF((booking_cab.bcb_trip_type = 1 AND booking.bkg_status IN (6, 7)), 1, 0) AS isMatchTripCompleted
                   , IF((booking_cab.bcb_trip_type = 0 AND booking.bkg_status IN (6, 7)), 1, 0) AS isUnmatchTripCompleted
                   , IF(booking.bkg_status IN (9), 1, 0) AS isTripCancelled
                   , SUM(IF(booking.bkg_status IN (9), 1, 0)) AS isBookingCancelled
                   , IF(booking.bkg_agent_id > 0 AND booking.bkg_status IN (6, 7), 1, 0) AS isB2BCompleted
                   , IF(((booking.bkg_agent_id IS NULL OR booking.bkg_agent_id = '') AND booking.bkg_status IN (6, 7)), 1, 0) AS isB2CCompleted
                   , SUM(IF(booking.bkg_agent_id > 0 AND booking.bkg_status IN (6, 7), 1, 0)) AS isB2BBKGCompleted
                   , SUM(IF(((booking.bkg_agent_id IS NULL OR booking.bkg_agent_id = '') AND booking.bkg_status IN (6, 7)), 1, 0)) AS isB2CBKGCompleted
                   , SUM(IF(booking_cab.bcb_trip_type = 1 AND booking.bkg_status IN (6, 7), booking_invoice.bkg_total_amount, 0)) AS matchTotalAmount
                   , SUM(IF(booking_cab.bcb_trip_type = 0 AND booking.bkg_status IN (6, 7), booking_invoice.bkg_total_amount, 0)) AS unMatchTotalAmount
                   , IF(booking_cab.bcb_trip_type = 1 AND booking.bkg_status IN (6, 7), (booking_cab.bcb_vendor_amount), 0) AS matchVendorAmount
                   , IF(booking_cab.bcb_trip_type = 0 AND booking.bkg_status IN (6, 7), (booking_cab.bcb_vendor_amount), 0) AS unMatchVendorAmount
                   , SUM(IF(booking.bkg_status IN (6, 7), booking_invoice.bkg_total_amount, 0)) AS totalAmount
                   , IF((booking.bkg_agent_id IS NULL OR booking.bkg_agent_id = '') AND booking.bkg_status IN (6, 7), (booking_cab.bcb_vendor_amount), 0) AS B2CVendorAmount
                   , IF(booking.bkg_agent_id > 0 AND booking.bkg_status IN (6, 7), (booking_cab.bcb_vendor_amount), 0) AS B2BVendorAmount
                   , SUM(IF((booking.bkg_agent_id IS NULL OR booking.bkg_agent_id = '') AND booking.bkg_status IN (6, 7), booking_invoice.bkg_total_amount, 0)) AS B2CTotalAmount
                   , SUM(IF(booking.bkg_agent_id > 0 AND booking.bkg_status IN (6, 7), booking_invoice.bkg_total_amount, 0)) AS B2BTotalAmount
          FROM     `booking_cab`
                   INNER JOIN `booking` ON booking_cab.bcb_id = booking.bkg_bcb_id
                   INNER JOIN booking_invoice ON booking.bkg_id = booking_invoice.biv_id
                   LEFT JOIN `agents` ON bkg_agent_id = agents.agt_id
          WHERE    1 AND bkg_status IN (2, 6, 7, 9) AND booking.bkg_active = 1 AND booking_cab.bcb_active = 1 AND (booking.bkg_create_date) >'2015-10-15 00:00:00' $whereCondition
          GROUP BY booking_cab.bcb_id) a
GROUP BY pickupDateRange
ORDER BY pickupDateRange DESC";

		if($type == 'command')
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
		else
		{
			$sqlCount = "
				                SELECT
				                DATE_FORMAT(createDate, '%Y -%m') AS pickupDateRange
								FROM     (
								SELECT
								    bcb_id,
									booking.bkg_create_date AS createDate
								FROM   `booking_cab`
								INNER JOIN `booking` ON booking_cab.bcb_id = booking.bkg_bcb_id
								INNER JOIN booking_invoice ON booking.bkg_id = booking_invoice.biv_id
								WHERE  booking.bkg_create_date > '2015-10-15 00:00:00'  AND bkg_status IN (2, 6, 7, 9) AND booking.bkg_active = 1 AND booking_cab.bcb_active =1 and booking_cab.bcb_denied_reason_id=0   $whereCondition ) a
								GROUP BY pickupDateRange   ";

			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['tripCreated', 'tripCompleted', 'tripCancelled'],
					'defaultOrder'	 => ''],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
	}

	public function getUnapproveAssignmentByPickup($type = '', $fromDate = '', $toDate = '')
	{
		$whereCondition = '';
		If($fromDate != '' && $toDate != '')
		{
			$whereCondition .= " AND (booking.bkg_pickup_date) BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59'";
		}
		$sql = "SELECT   (CASE states.stt_zone WHEN 1 THEN 'North' WHEN 2 THEN 'West' WHEN 3 THEN 'Central' WHEN 4 THEN 'South' WHEN 5 THEN 'East' WHEN 6 THEN 'North East' WHEN 7 THEN 'South Kerala' END) AS region, COUNT(1) AS count_bookings, SUM(IF(d2.drv_approved <> 1, 1, 0)) AS unapproved_drivers, SUM(IF(vehicles.vhc_approved <> 1, 1, 0)) AS unapproved_cars, DATE_FORMAT(booking.bkg_pickup_date, '%Y -%m') AS pickupDateRange, DATE_FORMAT(booking.bkg_pickup_date, '%Y / %M') AS DateRange
				FROM  `booking`
				INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 AND booking.bkg_status IN(6, 7)
				INNER JOIN `drivers` ON drivers.drv_id = booking_cab.bcb_driver_id AND drivers.drv_active = 1
				INNER JOIN drivers d2 ON d2.drv_id = drivers.drv_ref_code
				INNER JOIN `vehicles` ON vehicles.vhc_id = booking_cab.bcb_cab_id AND vehicles.vhc_active = 1
				JOIN `cities` ON cities.cty_id = booking.bkg_from_city_id
				JOIN `states` ON states.stt_id = cities.cty_state_id
				WHERE 1 $whereCondition
				GROUP BY states.stt_zone, pickupDateRange";

		if($type == 'command')
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
		else
		{
			$sqlCount		 = "SELECT  DATE_FORMAT(booking.bkg_pickup_date, '%Y -%m') AS pickupDateRange
			FROM  `booking`
			INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 AND booking.bkg_status IN(6,7)
			JOIN `cities` ON cities.cty_id = booking.bkg_from_city_id
			JOIN `states` ON states.stt_id = cities.cty_state_id
			WHERE 1 $whereCondition
			GROUP BY states.stt_zone, pickupDateRange";
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['region', 'DateRange'],
					'defaultOrder'	 => 'pickupDateRange DESC,region ASC'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
	}

	public function getBookingsByPickupAddress()
	{
		$removeVal	 = '"';
		$sql		 = "
			         SELECT
					 booking.bkg_booking_id
					,REPLACE(JSON_EXTRACT(`bkg_route_city_names`, '$[0]'), '$removeVal', '')  AS from_city
					 ,REPLACE(JSON_EXTRACT(`bkg_route_city_names`, CONCAT('$[', JSON_LENGTH(`bkg_route_city_names`) - 1, ']')),'$removeVal','') AS to_city
					, booking.bkg_pickup_address
					, booking.bkg_drop_address
					, (IF(booking.bkg_agent_id IS NOT NULL, 'B2B', 'B2C')) AS 'B2B/B2C'
					, (IF(booking.bkg_agent_id IS NOT NULL, CONCAT(a1.agt_fname, ' ', a1.agt_lname), CONCAT(booking_user.bkg_user_fname, ' ', booking_user.bkg_user_lname))) AS 'Name'
       				FROM   `booking`
					INNER JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id
					LEFT JOIN `agents` AS a1 ON a1.agt_id = booking.bkg_agent_id
					WHERE  booking.bkg_active = 1 AND booking.bkg_status IN (2, 3, 5, 6, 7) AND (booking.bkg_create_date) BETWEEN DATE(DATE_SUB(NOW(), INTERVAL 24 HOUR)) AND NOW()";
		return DBUtil::queryAll($sql);
	}

	public function getCompletedBookingsToday2()
	{
		$sql = "SELECT
					booking.bkg_booking_id,
					bkg_agent_id,
					GROUP_CONCAT(booking_user.`bkg_user_fname`, booking_user.`bkg_user_lname`) AS CustName,
					DATE(
						DATE_ADD(
							booking.bkg_pickup_date,
							INTERVAL booking.bkg_trip_duration MINUTE
						)
					) AS trip_completion_date,
					IFNULL(ratings.rtg_customer_overall,'') as rtg_customer_overall,
					DATE(
						DATE_SUB(NOW(), INTERVAL 48 HOUR)) AS today_2,
						GROUP_CONCAT(
							booking_user.`bkg_country_code`,
							booking_user.`bkg_contact_no`
						) AS PhoneNumber,
						booking_user.`bkg_user_email`,
						DATE(
							DATE_SUB(NOW(), INTERVAL 48 HOUR)),
							DATE(booking.bkg_pickup_date),
							c1.cty_name AS from_city,
							c2.cty_name AS to_city,
							CONCAT(c1.cty_name, ' - ', c2.cty_name) AS route,
							booking.bkg_pickup_date,
							DATE_FORMAT(booking.bkg_pickup_date,'%d/%m/%Y %H:%i:%s') AS pickup_date,
							DATE_FORMAT(
								DATE_ADD(booking.bkg_pickup_date, INTERVAL booking.bkg_trip_duration MINUTE),
								'%d/%m/%Y %H:%i:%s'
							) AS pickup_date_time
						FROM
							`booking`
						INNER JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id
						INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1
						LEFT JOIN `ratings` ON ratings.rtg_booking_id=booking.bkg_id
						JOIN `cities` AS c1
						ON
							c1.cty_id = booking.bkg_from_city_id
						JOIN `cities` AS c2
						ON
							c2.cty_id = booking.bkg_to_city_id
						WHERE
							DATE(DATE_ADD(booking.bkg_pickup_date,INTERVAL booking.bkg_trip_duration MINUTE )) = DATE(DATE_SUB(NOW(), INTERVAL 72 HOUR))
							AND bkg_status IN(6, 7)
							AND booking.bkg_active = 1
							AND (booking.bkg_agent_id IS NULL OR booking.bkg_agent_id ='')
							GROUP BY booking.bkg_id
							ORDER BY bkg_pickup_date DESC";
		return DBUtil::queryAll($sql);
	}

	public function getMatchedFlexxiBookings($model, $command = false)
	{
		if($model->bkg_flexxi_time_slot == '')
		{
			$dateRange = " AND '" . $model->bkg_pickup_date . "' BETWEEN DATE_SUB(bkg_pickup_date,INTERVAL 60 MINUTE) AND DATE_ADD(bkg_pickup_date,INTERVAL 60 MINUTE)";
		}
		else
		{
			$timeArr		 = explode('-', $model->bkg_flexxi_time_slot);
			$searchDate		 = date('Y-m-d', strtotime($model->bkg_pickup_date));
			$searchTime_from = date("H:i:s", strtotime($timeArr[0]));
			$searchTime_to	 = date("H:i:s", strtotime($timeArr[1]));

			$pickupDate_from = $searchDate . " " . $searchTime_from;
			if($searchTime_to == "00:00:00")
			{
				$searchDate = date('Y-m-d', strtotime($searchDate . " +1 day"));
			}
			$pickupDate_to	 = $searchDate . " " . $searchTime_to;
			$dateRange		 = " AND bkg_pickup_date >='" . $pickupDate_from . "' AND bkg_pickup_date <='" . $pickupDate_to . "'";
		}
		$sql_checkData	 = "SELECT
								bkg_flexxi_type,
								bkg_bcb_id,
								booking_add_info.bkg_no_person
							   FROM
								booking
								INNER JOIN booking_add_info ON booking_add_info.bad_bkg_id = booking.bkg_id
							   WHERE
								bkg_id =$model->bkg_id";
		$checkData		 = DBUtil::command($sql_checkData)->queryRow();
		if($checkData['bkg_flexxi_type'] == 1)
		{

			$sql = "SELECT
						bkg_booking_id,
						booking_add_info.bkg_no_person,
						bkg_pickup_date,
						bkg_total_amount,
						bkg_flexxi_type,
						bkg_id,
						c1.cty_name fromcity,
						c2.cty_name tocity
					   FROM
						booking
						INNER JOIN booking_add_info ON booking_add_info.bad_bkg_id = booking.bkg_id
						INNER JOIN booking_user ON booking_user.bui_bkg_id = bkg_id
						INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id = bkg_id
						JOIN cities c1 ON bkg_from_city_id = c1.cty_id
						JOIN cities c2 ON bkg_to_city_id = c2.cty_id
						LEFT JOIN users ON users.user_id = booking_user.bkg_user_id
					   WHERE
						bkg_from_city_id = $model->bkg_from_city_id AND
						bkg_to_city_id = $model->bkg_to_city_id $dateRange AND
						booking.bkg_flexxi_type = 2 AND
						bkg_status IN (2,
									   3) AND
						booking_add_info.bkg_no_person <=
						(SELECT
						  (vehicle_category.vct_capacity -
						   SUM(
							 IFNULL(
							   bad.bkg_no_person,
							   0)))
						 FROM
						  booking b1 INNER JOIN booking_add_info bad ON bad.bad_bkg_id = b1.bkg_id
									 INNER JOIN svc_class_vhc_cat scv ON b1.bkg_vehicle_type_id = scv.scv_id
									 INNER JOIN vehicle_category ON scv.scv_vct_id = vct_id
						 WHERE
						  b1.bkg_bcb_id = booking.bkg_bcb_id) AND
						booking.bkg_fp_id IS NULL AND
						bkg_status IN (2,
									   3,
									   5) AND
						bkg_bcb_id <> " . $checkData['bkg_bcb_id'] . " AND
						users.usr_gender =" . $model->bkgUserInfo->bkgUser->usr_gender;
		}
		else
		{
			$sql_validate	 = "SELECT COUNT(*) FROM booking WHERE bkg_flexxi_type=1 AND bkg_status IN(2,3,5) AND bkg_bcb_id=" . $checkData['bkg_bcb_id'];
			$result_validate = DBUtil::command($sql_validate)->queryScalar();
			$flexxiType		 = " AND bkg_flexxi_type IN(1,2) ";
			$sql			 = "SELECT
					GROUP_CONCAT(bkg_booking_id)
					  bkg_booking_id,
					booking_add_info.bkg_no_person,
					bkg_pickup_date,
					booking_invoice.bkg_total_amount,
					bkg_flexxi_type,
					bkg_id,
					(vct_capacity -
					 SUM(
					   IFNULL(
						 booking_add_info.bkg_no_person,
						 0)))
					  remaining,
					c1.cty_name
					  fromcity,
					c2.cty_name
					  tocity
				   FROM
					booking
					INNER JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id
					INNER JOIN booking_add_info ON booking_add_info.bad_bkg_id = booking.bkg_id
					INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id = booking.bkg_id
					JOIN cities c1 ON bkg_from_city_id = c1.cty_id
					JOIN cities c2 ON bkg_to_city_id = c2.cty_id
					LEFT JOIN users ON users.user_id = booking_user.bkg_user_id
					INNER JOIN svc_class_vhc_cat scv ON bkg_vehicle_type_id = scv.scv_id
					INNER JOIN vehicle_category ON scv.scv_vct_id = vct_id
				   WHERE
					bkg_from_city_id = $model->bkg_from_city_id AND
					bkg_to_city_id = $model->bkg_to_city_id $dateRange $flexxiType  AND
					bkg_status IN (2,
								   3) AND
					bkg_bcb_id <> " . $checkData['bkg_bcb_id'] . " AND
					bkg_status IN (2,
								   3,
								   5) AND
					users.usr_gender =" . $model->bkgUserInfo->bkgUser->usr_gender . "
				   GROUP BY
					booking.bkg_bcb_id
				   HAVING
					remaining > 0";
		}

		if($command)
		{
			return DBUtil::queryRow($sql);
		}

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getSelectedFlexxiBookings($model, $selectedBkgIds = 0)
	{
		$sql = "SELECT sum(bkg_no_person) totSeats,SUM(IF(bkg_flexxi_type=1,1,0)) totFp FROM booking WHERE bkg_flexxi_type IN(1,2) AND bkg_id IN(" . implode(',', $selectedBkgIds) . ")";
		return DBUtil::queryRow($sql);
	}

	public function machedFlexxiBooking($bkgId = 0, $selectedBkgId = 0)
	{
		$noPerson			 = 0;
		$selectedNoPerson	 = 0;
		$data				 = DBUtil::command("SELECT bkg_id,bkg_bcb_id,bkg_flexxi_type,booking_add_info.bkg_no_person,bkg_vehicle_type_id,bkg_status FROM booking INNER JOIN booking_add_info ON booking_add_info.bad_bkg_id=bkg_id WHERE bkg_bcb_id = (SELECT bkg_bcb_id FROM booking WHERE bkg_id = $bkgId) AND bkg_status IN(2,3,5)")->queryAll();
		$selectedData		 = DBUtil::command("SELECT bkg_id,bkg_bcb_id,bkg_flexxi_type,booking_add_info.bkg_no_person,bkg_vehicle_type_id,bkg_status FROM booking INNER JOIN booking_add_info ON booking_add_info.bad_bkg_id=bkg_id WHERE bkg_bcb_id = (SELECT bkg_bcb_id FROM booking WHERE bkg_id = $selectedBkgId) AND bkg_status IN(2,3,5)")->queryAll();

		if($data != '[]' && $selectedData != '[]')
		{
			try
			{
				$transaction = DBUtil::beginTransaction();

				$vctCapacity = DBUtil::command("SELECT vct_capacity FROM svc_class_vhc_cat INNER JOIN vehicle_category ON scv_vct_id=vct_id  WHERE scv_id = " . $data[0]['bkg_vehicle_type_id'])->queryScalar();
				foreach($selectedData as $value)
				{
					$bcbId		 = $value['bkg_bcb_id'];
					$bkgStatus	 = $value['bkg_status'];
					if($value['bkg_flexxi_type'] == 1)
					{
						$promoterSelected	 = $value['bkg_id'];
						$condition			 = ", bkg_fp_id = " . $value['bkg_id'];
						$bcbIdFpSelected	 = $bcbId;
					}
					$selectedNoPerson = $selectedNoPerson + $value['bkg_no_person'];
				}

				foreach($data as $value)
				{
					$bcbId		 = $value['bkg_bcb_id'];
					$bkgStatus	 = $value['bkg_status'];
					if($value['bkg_flexxi_type'] == 1)
					{
						$promoter	 = $value['bkg_id'];
						$condition	 = ", bkg_fp_id = " . $value['bkg_id'];
						$bcbIdFp	 = $bcbId;
					}

					$noPerson = $noPerson + $value['bkg_no_person'];
				}

				if(($promoter != '' && $promoterSelected != '') || (($noPerson + $selectedNoPerson) > $vctCapacity))
				{
					return false;
				}
				$dataToUpdate = $selectedData;
				if($bcbIdFp != '')
				{
					$bcbId			 = $bcbIdFp;
					$dataToUpdate	 = $selectedData;
				}
				if($bcbIdFpSelected != '')
				{
					$bcbId			 = $bcbIdFpSelected;
					$dataToUpdate	 = $data;
				}

				foreach($dataToUpdate as $value)
				{
					DBUtil::command("UPDATE booking_cab SET bcb_active=0 WHERE bcb_id=" . $value['bkg_bcb_id'])->execute();
					DBUtil::command("UPDATE booking SET bkg_status=" . $bkgStatus . ",bkg_bcb_id=" . $bcbId . $condition . " WHERE bkg_id=" . $value['bkg_id'])->execute();
					DBUtil::command("UPDATE booking_route SET brt_bcb_id=" . $bcbId . " WHERE brt_bkg_id=" . $value['bkg_id'])->execute();
				}
				if($promoter != '' || $promoterSelected != '')
				{
					$promoter													 = ($promoter != '') ? $promoter : $promoterSelected;
					$promoterBooking											 = Booking::model()->findByPk($promoter);
					$subsBkgId													 = ($promoter != '') ? $selectedBkgId : $bkgId;
					$fareDetails												 = $promoterBooking->bkgInvoice->calculatePromoterFare($subsBkgId, false);
					$promoterBooking->bkgInvoice->bkg_base_amount				 = $fareDetails->baseAmount;
					$promoterBooking->bkgInvoice->bkg_state_tax					 = $fareDetails->stateTax;
					$promoterBooking->bkgInvoice->bkg_toll_tax					 = $fareDetails->tollTaxAmount;
					$promoterBooking->bkgInvoice->bkg_driver_allowance_amount	 = $fareDetails->driverAllowance;
					$promoterBooking->bkgInvoice->bkg_service_tax				 = $fareDetails->gst;
					$promoterBooking->bkgInvoice->populateAmount(false, true, true, false, $promoterBooking->bkg_agent_id);
					$promoterBooking->bkgInvoice->save();
				}

				DBUtil::commitTransaction($transaction);
				return true;
			}
			catch(Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				return false;
			}
		}
		return false;
	}

	public function getPromoterIdForFlexxiBooking($bkgBcbId)
	{
		$sql = "SELECT bkg_id FROM booking WHERE bkg_flexxi_type=1 AND bkg_bcb_id=$bkgBcbId";
		$res = DBUtil::command($sql)->queryScalar();
		return $res;
	}

	public function getIdsOfMatchedFlexxiBooking($bookingID)
	{
		$model	 = Booking::model()->findByPk($bookingID);
		$sql	 = "SELECT bkg_id FROM booking WHERE bkg_bcb_id=$model->bkg_bcb_id";
		$res	 = DBUtil::queryAll($sql);
		return $res;
	}

	function getFlexxiBookingsToMatch()
	{
		$sql = "SELECT
					bkg_id,
					(vct_capacity - sum(booking_add_info.bkg_no_person)) remain
				   FROM
					booking
						INNER JOIN booking_add_info ON booking_add_info.bad_bkg_id = booking.bkg_id AND bkg_flexxi_type IN (1,2) AND bkg_status IN (2,3,5) AND bkg_pickup_date > NOW()
						INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = bkg_vehicle_type_id
						INNER JOIN vehicle_category ON vct_id = scv.scv_vct_id
				   WHERE 1
				   GROUP BY
					bkg_bcb_id
				   HAVING
					remain > 0";

		$res = DBUtil::queryAll($sql);
		return $res;
	}

	public function getAvailableFlexxiSlots($date, $fromCity, $toCity)
	{
		$sql = "SELECT   SUM(remainingSeats) totseats, a.bkg_pickup_date, a.slot
				FROM     (SELECT   GROUP_CONCAT(bkg_bcb_id), vct_capacity - SUM(bkg_no_person) remainingSeats,bkg_flexxi_type, bkg_pickup_date,
						 (
							CASE   WHEN bkg_pickup_date BETWEEN CONCAT(DATE(bkg_pickup_date), ' ', '06:00:00') AND CONCAT(DATE(bkg_pickup_date), ' ', '09:00:00') THEN '6 AM - 9 AM'
								   WHEN bkg_pickup_date BETWEEN CONCAT(DATE(bkg_pickup_date), ' ', '09:01:00') AND CONCAT(DATE(bkg_pickup_date), ' ', '12:00:00') THEN '9 AM - 12 PM'
								   WHEN bkg_pickup_date BETWEEN CONCAT(DATE(bkg_pickup_date), ' ', '12:01:00') AND CONCAT(DATE(bkg_pickup_date), ' ', '15:00:00') THEN '12 PM - 3 PM'
								   WHEN bkg_pickup_date BETWEEN CONCAT(DATE(bkg_pickup_date), ' ', '15:01:00') AND CONCAT(DATE(bkg_pickup_date), ' ', '18:00:00') THEN '3 PM -  6 PM'
								   WHEN bkg_pickup_date BETWEEN CONCAT(DATE(bkg_pickup_date), ' ', '18:01:00') AND CONCAT(DATE(bkg_pickup_date), ' ', '21:00:00') THEN '6 PM -  9 PM'
							 ELSE 'At other times' END
						  ) slot
						  FROM     booking
							INNER JOIN booking_add_info ON booking.bkg_id=booking_add_info.bad_bkg_id AND bkg_status IN (2, 3, 5) AND bkg_active = 1 AND DATE(bkg_pickup_date) >='$date' AND bkg_from_city_id=$fromCity AND bkg_to_city_id=$toCity
							INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = bkg_vehicle_type_id
							INNER JOIN vehicle_category ON vct_id = scv.scv_vct_id
						  WHERE 1
						  GROUP BY bkg_bcb_id
						  HAVING   remainingSeats > 0) a  WHERE bkg_flexxi_type=1
				GROUP BY a.slot,date(bkg_pickup_date)
				ORDER BY bkg_pickup_date
				LIMIT    3";

		$res = DBUtil::queryAll($sql);
		return $res;
	}

	public static function getBcbByPickupDate($pickup, $fromCity, $toCity)
	{
		$sql		 = "SELECT
					booking.bkg_bcb_id, booking.bkg_pickup_date, booking.bkg_from_city_id, booking.bkg_to_city_id
				FROM
					`booking`
				WHERE
					booking.bkg_pickup_date = '$pickup'
					AND booking.bkg_from_city_id = '$fromCity'
					AND booking.bkg_to_city_id = '$toCity'
					AND booking.bkg_flexxi_type = '2'
					AND booking.bkg_bcb_id IS NOT NULL
					AND booking.bkg_status IN (2,3,5)
				ORDER BY
					booking.bkg_id DESC
				LIMIT 0, 1";
		$row		 = DBUtil::queryRow($sql);
		$returnBcbId = ($row['bkg_bcb_id'] > 0) ? $row['bkg_bcb_id'] : 0;
		return $returnBcbId;
	}

	public function findUserByRouteCities($pickupDate, $frmCity, $toCity, $user_id)
	{
		$sql	 = "SELECT IF(COUNT(1)>0,1,0) as saleStatus
				FROM `booking`
				INNER JOIN `booking_invoice` ON booking.bkg_id=booking_invoice.biv_bkg_id
				INNER JOIN `booking_user` ON booking.bkg_id=booking_user.bui_bkg_id
				INNER JOIN `booking_cab`
				ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1
				WHERE booking.bkg_pickup_date='$pickupDate'
				AND booking.bkg_from_city_id = '$frmCity'
				AND booking.bkg_to_city_id ='$toCity'
				AND booking_invoice.bkg_promo1_code= 'FLATRE1'
				AND booking_user.bkg_user_id = '$user_id'
				AND booking.bkg_status =2";
		$status	 = DBUtil::command($sql)->queryScalar();
		return $status;
	}

	function getBkgCountByPickupDateWithRoute($date, $frmcityId, $tocityId)
	{
		$date	 = date('Y-m-d', strtotime($date));
		$sql	 = "SELECT count(bkg_id) as count FROM booking WHERE (bkg_pickup_date BETWEEN '$date 00:00:00' AND '$date 23:59:59') AND bkg_from_city_id = $frmcityId AND bkg_to_city_id = $tocityId AND bkg_status IN (2,3,4,5)";
//$sql = "SELECT count(bkg_id) as count FROM booking WHERE date(bkg_pickup_date) = '$date'  AND bkg_from_city_id = $frmcityId AND bkg_to_city_id = $tocityId AND bkg_status IN (2,3,4,5)";
		$res	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $res[0]['count'];
	}

	/**
	 *
	 * @return array
	 */
	public static function counterDelegatedManager()
	{
		$returnSet = Yii::app()->cache->get('counterDelegatedManager');
		if($returnSet === false)
		{
			$sql		 = "SELECT
				IFNULL(COUNT(DISTINCT booking.bkg_id),0) AS cnt,
				IFNULL(SUM(
					IF(
						booking.bkg_agent_id = 450,
						1,
						0
					)
				),0) AS countMMT,
				IFNULL(SUM(
					IF((booking.bkg_agent_id > 0 AND booking.bkg_agent_id != 450), 1, 0)
				),0) AS countB2B,
				IFNULL(SUM(
					IF(
						booking.bkg_agent_id IS NULL,
						1,
						0
					)
				),0) AS countB2C
				FROM `booking`
				INNER JOIN `booking_pref` ON booking.bkg_id = booking_pref.bpr_bkg_id
				WHERE bkg_pickup_date BETWEEN (DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND (DATE_ADD(NOW(), INTERVAL 11 MONTH))
				AND booking_pref.bpr_assignment_level IN (2, 3) AND bkg_status = 2 LIMIT 0,1";
			$returnSet	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('counterDelegatedManager', $returnSet, 600);
		}
		return $returnSet;
	}

	/**
	 *
	 * @param integer $eventId
	 * @return array
	 */
	public static function countEscalatedAssignment()
	{
		$list = self::counterDelegatedManager();
		return $list['cnt'];
	}

	/**
	 *
	 * @param integer $type
	 * @return Array
	 */
	public static function countAssignment($type)
	{
		$returnSet = Yii::app()->cache->get('countAssignment_' . $type);
		if($returnSet === false)
		{
			$sql = "SELECT COUNT(DISTINCT booking.bkg_id) as cnt,
				IFNULL(SUM(
					IF(booking.bkg_agent_id = 450, 1, 0)
				),0) AS countMMT,
				IFNULL(SUM(IF((booking.bkg_agent_id > 0 AND booking.bkg_agent_id != 450), 1, 0)),0) AS countB2B,
				IFNULL(SUM(
					IF(
						booking.bkg_agent_id IS NULL,
						1,
						0
					)
				),0) AS countB2C
				FROM `booking`
				INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1
				INNER JOIN `booking_pref` ON booking_pref.bpr_bkg_id=booking.bkg_id
				WHERE bkg_pickup_date BETWEEN (DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND (DATE_ADD(NOW(), INTERVAL 11 MONTH))
				AND booking.bkg_status IN (2) ";
			if($type == 'manual')
			{
				$sql		 .= " AND booking_pref.bkg_manual_assignment=1 AND booking_pref.bkg_critical_assignment=0 LIMIT 0,1";
				$row		 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
				$returnSet	 = ['manualCnt'	 => $row['cnt'],
					'manualMMT'	 => $row['countMMT'],
					'manualB2B'	 => $row['countB2B'],
					'manualB2C'	 => $row['countB2C']];
				Yii::app()->cache->set('countAssignment_' . $type, $returnSet, 600);
			}
			else if($type == 'critical')
			{
				$sql		 .= " AND booking_pref.bkg_critical_assignment=1 LIMIT 0,1";
				$row		 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
				$returnSet	 = ['criticalCnt'	 => $row['cnt'],
					'criticalMMT'	 => $row['countMMT'],
					'criticalB2B'	 => $row['countB2B'],
					'criticalB2C'	 => $row['countB2C']];
				Yii::app()->cache->set('countAssignment_' . $type, $returnSet, 600);
			}
		}
		return $returnSet;
	}

	/**
	 *
	 * @param string $type
	 * @param string $event
	 * @return string
	 */
	public static function getAssignmentCount($type, $event = 'manualCnt')
	{
		$assignList = self::countAssignment($type);
		return $assignList[$event];
	}

	/**
	 *
	 * @return array
	 */
	public static function countRiskBooking()
	{
		$returnSet = Yii::app()->cache->get('countRiskBooking');
		if($returnSet === false)
		{
			$sql		 = "SELECT IFNULL(count(DISTINCT booking.bkg_id),0) as cnt,
                        IFNULL(SUM(
                                IF(booking.bkg_agent_id = 450, 1, 0)
                        ),0) AS countMMT,
                        IFNULL(SUM(IF((booking.bkg_agent_id > 0 AND booking.bkg_agent_id != 450), 1, 0)),0) AS countB2B,
                        IFNULL(SUM(
                                IF(
                                        booking.bkg_agent_id IS NULL,
                                        1,
                                        0
                                )
                        ),0) AS countB2C
                        FROM `booking`
                        INNER JOIN `booking_trail` ON booking.bkg_id=booking_trail.btr_bkg_id AND booking.bkg_status=2
                        WHERE booking_trail.btr_is_dem_sup_misfire=1
                        AND booking.bkg_pickup_date BETWEEN (DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND (DATE_ADD(NOW(), INTERVAL 11 MONTH))
                        AND booking.bkg_reconfirm_flag=1  LIMIT 0,1 ";
			$returnSet	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('countRiskBooking', $returnSet, 600);
		}
		return $returnSet;
	}

	public static function getCountDemandSupplyMisfire()
	{
		$list = self::countRiskBooking();
		return $list['cnt'];
	}

	/**
	 * @deprecated since version 15-10-2019
	 * @author ramala
	 */
	public function mappingCp($data)
	{
		$model										 = new Booking('new');
		$model->bkgUserInfo							 = new BookingUser();
		$model->bkgInvoice							 = new BookingInvoice();
		$model->bkgTrail							 = new BookingTrail();
		$model->bkgAddInfo							 = new BookingAddInfo();
		$model->bkgPref								 = new BookingPref();
		$model->bkgTrack							 = new BookingTrack();
		$model->bkgPf								 = new BookingPriceFactor();
		$model->bkgUserInfo->bkg_user_fname			 = $data['customer']['firstName'];
		$model->bkgUserInfo->bkg_user_lname			 = $data['customer']['lastName'];
		$model->bkgUserInfo->bkg_country_code		 = $data['customer']['mobileCountryCode'];
		$model->bkgUserInfo->bkg_contact_no			 = $data['customer']['mobile'];
		$model->bkgUserInfo->bkg_user_email			 = $data['customer']['email'];
		$model->bkgUserInfo->bkg_alt_contact_no		 = $data['customer']['alternateMobile'];
		$model->bkgUserInfo->bkg_alt_country_code	 = $data['customer']['alternateMobileCountryCode'];
		$model->bkg_vehicle_type_id					 = $data['cabId'];
		if($data['additional']['specialInstructions'] != '')
		{
			$model->bkg_instruction_to_driver_vendor = $data['additional']['specialInstructions'];
		}
		$model->bkgTrail->bkg_user_ip		 = $data['customer']['ip'];
		$model->bkgTrail->bkg_user_device	 = $data['customer']['device'];
		if($data['additional']['noOfPerson'] != '')
		{
			$model->bkgAddInfo->bkg_no_person = $data['additional']['noOfPerson'];
		}
		if($data['additional']['sendEmail'] != '')
		{
			$model->bkgPref->bkg_send_email = $data['additional']['sendEmail'];
		}
		if($data['additional']['sendSms'] != '')
		{
			$model->bkgPref->bkg_send_sms = $data['additional']['sendSms'];
		}
		if($data['additional']['noOfLargeBags'] != '')
		{
			$model->bkgAddInfo->bkg_num_large_bag = $data['additional']['noOfLargeBags'];
		}
		if($data['additional']['noOfSmallBags'] != '')
		{
			$model->bkgAddInfo->bkg_num_small_bag = $data['additional']['noOfSmallBags'];
		}
		if($data['additional']['seniorCitizenTravelling'] != '')
		{
			$model->bkgAddInfo->bkg_spl_req_senior_citizen_trvl = $data['additional']['seniorCitizenTravelling'];
		}
		if($data['additional']['kidsTravelling'] != '')
		{
			$model->bkgAddInfo->bkg_spl_req_kids_trvl = $data['additional']['kidsTravelling'];
		}
		if($data['additional']['womanTravelling'] != '')
		{
			$model->bkgAddInfo->bkg_spl_req_woman_trvl = $data['additional']['womanTravelling'];
		}
		if($data['additional']['otherRequests'] != '')
		{
			$model->bkgAddInfo->bkg_spl_req_other = $data['additional']['otherRequests'];
		}
		if($data['additional']['carrierRequired'] != '')
		{
			$model->bkgAddInfo->bkg_spl_req_carrier = $data['additional']['carrierRequired'];
		}
		if($data['additional']['englishSpeakingDriver'] != '')
		{
			$model->bkgAddInfo->bkg_spl_req_driver_english_speaking = $data['additional']['englishSpeakingDriver'];
		}
		if($data['additional']['hindiSpeakingDriver'] != '')
		{
			$model->bkgAddInfo->bkg_spl_req_driver_hindi_speaking = $data['additional']['hindiSpeakingDriver'];
		}
		if($data['tnc'] != '')
		{
			$model->bkgTrail->bkg_tnc = $data['tnc'];
		}
		if($data['advanceReceived'] != '')
		{
			$model->bkgInvoice->bkg_corporate_credit = $data['advanceReceived'];
		}
		else
		{
			$model->bkgInvoice->bkg_corporate_credit = 0;
		}
		return $model;
	}

	public function newReverseMapping(Booking $model)
	{
		$bookingStatus		 = '';
		$bookingStatusCode	 = '';

		if($model->bkg_status == 1)
		{
			$bookingStatus		 = 'Not Confirmed';
			$bookingStatusCode	 = "0";
		}
		if($model->bkg_status == 2 || $model->bkg_status == 3 || $model->bkg_status == 4)
		{
			$bookingStatus		 = 'Confirmed';
			$bookingStatusCode	 = "1";
		}
		if($model->bkg_status == 5)
		{
			$pickup_date = date('Y-m-d H:i:s', strtotime($model->bkg_pickup_date));
			if($pickup_date != "")
			{
				$d1	 = new DateTime();
				$d2	 = new DateTime($pickup_date);
				if($d1 < $d2)
				{
					$bookingStatus		 = 'Cab Assigned';
					$bookingStatusCode	 = "2";
				}
				else
				{
					$bookingStatus		 = 'Allocated';
					$bookingStatusCode	 = "3";
				}
			}
		}
		if($model->bkg_status == 6 || $model->bkg_status == 7)
		{
			$bookingStatus		 = 'Completed';
			$bookingStatusCode	 = "4";
		}
		if($model->bkg_status == 8)
		{
			$bookingStatus		 = 'Deleted';
			$bookingStatusCode	 = "5";
		}
		if($model->bkg_status == 9)
		{
			$bookingStatus		 = 'Cancelled';
			$bookingStatusCode	 = "6";
		}
		$transModels							 = AccountTransDetails::model()->getByBookingID($model->bkg_id);
		$transactionDetails						 = AccountTransDetails::model()->mapping($transModels);
		$cabmodel								 = $model->getBookingCabModel();
		$array['bookingId']						 = $model->bkg_booking_id;
		$array['bookingStatus']					 = $bookingStatus;
		$array['bookingStatusCode']				 = $bookingStatusCode;
		$array['creditsUsed']					 = $model->bkgInvoice->bkg_credits_used;
		$array['traveller details']['firstName'] = $model->bkgUserInfo->bkg_user_fname;
		$array['traveller details']['lastName']	 = $model->bkgUserInfo->bkg_user_lname;
		if($model->bkgUserInfo->bkg_user_email != '')
		{
			$array['traveller details']['customerEmail'] = $model->bkgUserInfo->bkg_user_email;
		}
		if($model->bkgUserInfo->bkg_contact_no != '')
		{
			$array['traveller details']['customerMobile'] = "+" . $model->bkgUserInfo->bkg_country_code . $model->bkgUserInfo->bkg_contact_no;
		}
		if($model->bkgUserInfo->bkg_alt_contact_no != '')
		{
			$array['traveller details']['customerAlternateMobile'] = "+" . $model->bkgUserInfo->bkg_alt_country_code . $model->bkgUserInfo->bkg_alt_contact_no;
		}
		$array['trip details']['tripType']				 = $model->bkg_booking_type;
		$array['trip details']['estimatedTripDistance']	 = $model->trip_distance_format;
		$array['trip details']['estimatedTripDuration']	 = $model->trip_duration_format;
		$array['trip details']['tripDescription']		 = $model->bkg_booking_type == 1 ? 'One Way' : (($model->bkg_booking_type == 2) ? 'Return' : '');
		$array['trip details']['tripPickupDate']		 = date("Y-m-d", strtotime($model->bkg_pickup_date));
		$array['trip details']['tripPickupTime']		 = date("h:i A", strtotime($model->bkg_pickup_date)); //$model->bkg_pickup_time;

		$array['transactions'] = $transactionDetails;

		$array['cab details']['cabId']		 = $model->bkgSvcClassVhcCat->scv_vct_id;
		$array['cab details']['cab']		 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$array['cab details']['cabModel']	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc;

		$array['route details']['fromCityName']	 = $model->bkgFromCity->cty_name;
		$array['route details']['toCityName']	 = $model->bkgToCity->cty_name;
		$array['route details']['routeName']	 = BookingRoute::model()->getRouteName($model->bkg_id);
		$array['route details']['pickupAddress'] = $model->bkg_pickup_address;
		$array['route details']['dropAddress']	 = $model->bkg_drop_address;

		$array['fair details']['advance']			 = $model->bkgInvoice->getAdvanceReceived();
		$array['fair details']['driverAllowance']	 = $model->bkgInvoice->bkg_driver_allowance_amount;
		$array['fair details']['tollTax']			 = (($model->bkgInvoice->bkg_toll_tax | 0) + ($model->bkgInvoice->bkg_extra_toll_tax | 0));
		$array['fair details']['stateTax']			 = (($model->bkgInvoice->bkg_state_tax | 0) + ($model->bkgInvoice->bkg_extra_state_tax | 0));
		$array['fair details']['isTollTaxIncluded']	 = $model->bkgInvoice->bkg_is_toll_tax_included;
		$array['fair details']['isStateTaxIncluded'] = $model->bkgInvoice->bkg_is_state_tax_included;
		$array['fair details']['baseAmt']			 = $model->bkgInvoice->bkg_base_amount;
		$array['fair details']['serviceTax']		 = $model->bkgInvoice->bkg_service_tax;
		$array['fair details']['additionalAmount']	 = $model->bkgInvoice->bkg_additional_charge | 0;
		$array['fair details']['commissionAmount']	 = $model->bkgInvoice->bkg_agent_markup | 0;
		$array['fair details']['discount']			 = $model->bkgInvoice->bkg_discount_amount;
		$array['fair details']['totalAmount']		 = $model->bkgInvoice->bkg_total_amount;
		$array['fair details']['isNightPickup']		 = $model->bkgInvoice->bkg_night_pickup_included;
		$array['fair details']['isNightDrop']		 = $model->bkgInvoice->bkg_night_drop_included;

		if($cabmodel->bcbCab->vhc_number != '')
		{
			$vehicleModel = $cabmodel->bcbCab->vhcType->vht_model;
			if($cabmodel->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
			{
				$vehicleModel = OperatorVehicle::getCabModelName($cabmodel->bcb_vendor_id, $cabmodel->bcb_cab_id);
			}
			$array['cabAssigned'] = $cabmodel->bcbCab->vhcType->vht_make . " " . $vehicleModel . " (" . $cabmodel->bcbCab->vhc_number . ")";
		}
		if($cabmodel->bcb_driver_name != '')
		{
			$array['driverName'] = $cabmodel->bcb_driver_name;
		}
		if($cabmodel->bcb_driver_phone != '')
		{
			$array['driverMobile'] = $cabmodel->bcb_driver_phone;
		}
		return $array;
	}

	public function mappingMmt($data, $dropCity)
	{
		$model							 = new Booking('new');
		$bkgUserModel					 = new BookingUser();
		$bkgPrefModel					 = new BookingPref();
		$bkgTrailModel					 = new BookingTrail();
		$bkgInvoiceModel				 = new BookingInvoice();
		$bkgPfModel						 = new BookingPriceFactor();
		$model->bkg_agent_ref_code		 = $data['bookingId'];
		$bkgUserModel->bkg_user_fname	 = $data['customerDetails']['firstName'];
		$bkgUserModel->bkg_user_lname	 = $data['customerDetails']['lastName'];
		$model->bkg_pickup_address		 = $data['tripDetails']['pickupAddress'];
		if($data['tripDetails']['tripType'] == 'OW')
		{
			$model->bkg_booking_type = 1;
			if($data['tripDetails']['destinationLocation']['address'] != '')
			{
				$model->bkg_drop_address = $data['tripDetails']['destinationLocation']['address'];
			}
			else
			{
				$model->bkg_drop_address = $dropCity;
			}
		}
		if($data['tripDetails']['tripType'] == 'AT')
		{
			$model->bkg_booking_type = 4;
			if($data['tripDetails']['destinationLocation']['address'] != '')
			{
				$model->bkg_drop_address = $data['tripDetails']['destinationLocation']['address'];
			}
			else
			{
				$model->bkg_drop_address = $dropCity;
			}
		}
		if($data['tripDetails']['tripType'] == 'RT')
		{
			$model->bkg_booking_type = 2;
			$model->bkg_drop_address = $data['tripDetails']['pickupAddress'];
			$returnDate				 = DateTimeFormat::DatePickerToDate($data['tripDetails']['returnDate']);
			$returnTime				 = date('H:i:s', strtotime($data['tripDetails']['dropTime']));
			$returnDateTime			 = $returnDate . ' ' . $returnTime;

			$bmodel->bkg_return_date = $returnDateTime;
// $bmodel->bkg_return_time = $returnTime;
		}
		$bkgUserModel->bkg_country_code	 = 91;
		$bkgUserModel->bkg_contact_no	 = $data['customerDetails']['mobileNo'];
		$bkgUserModel->bkg_user_email	 = $data['customerDetails']['emailId'];
		$vModel							 = new Vehicles();
		$model->bkg_vehicle_type_id		 = $vModel->getCabId(strtolower($data['vehicleType']));
		$bkgPrefModel->bkg_send_email	 = 0;
		$bkgPrefModel->bkg_send_sms		 = 0;
		$bkgTrailModel->bkg_tnc			 = 1;
		if($data['advancePaid'] != '')
		{
			$bkgInvoiceModel->bkg_corporate_credit = $data['advancePaid'];
		}
		else
		{
			$bkgInvoiceModel->bkg_corporate_credit = 0;
		}

		$arrayModel = ['booking' => $model, 'bkgUserModel' => $bkgUserModel, 'bkgPrefModel' => $bkgPrefModel, 'bkgTrailModel' => $bkgTrailModel, 'bkgInvoiceModel' => $bkgInvoiceModel];
		return $arrayModel;
	}

	/**
	 * Function for archiving booking & related data
	 * @param $archiveDB
	 */
	public static function archiveBookingData($archiveDB, $upperLimit = 50000, $lowerLimit = 500)
	{
		$arrBookingRelatedTables							 = array();
		$arrBookingRelatedTables['booking_add_info']		 = 'bad_bkg_id';
		$arrBookingRelatedTables['booking_alert']			 = 'alr_bkg_id';
		$arrBookingRelatedTables['booking_invoice']			 = 'biv_bkg_id';
		$arrBookingRelatedTables['booking_messages']		 = 'bkg_booking_id';
		$arrBookingRelatedTables['booking_pay_docs']		 = 'bpay_bkg_id';
		$arrBookingRelatedTables['booking_penalties']		 = 'bookingId';
		$arrBookingRelatedTables['booking_pref']			 = 'bpr_bkg_id';
		$arrBookingRelatedTables['booking_price_factor']	 = 'bpf_bkg_id';
		$arrBookingRelatedTables['booking_route']			 = 'brt_bkg_id';
		$arrBookingRelatedTables['booking_schedule_event']	 = 'bse_bkg_id';
		$arrBookingRelatedTables['booking_track']			 = 'btk_bkg_id';
		$arrBookingRelatedTables['booking_track_log']		 = 'btl_bkg_id';
		$arrBookingRelatedTables['booking_trail']			 = 'btr_bkg_id';
		$arrBookingRelatedTables['booking_unreg_vendor']	 = 'buv_bkg_id';
		$arrBookingRelatedTables['booking_user']			 = 'bui_bkg_id';
		$arrBookingRelatedTables['booking_vendor_request']	 = 'bvr_booking_id';
		$arrBookingRelatedTables['booking_log']				 = 'blg_booking_id';
		$transaction										 = null;
		try
		{
			$i			 = 0;
			$chk		 = true;
			$totRecords	 = $upperLimit;
			$limit		 = $lowerLimit;
			DBUtil::execute("SET FOREIGN_KEY_CHECKS=0;");
			while($chk)
			{
				$sql = "SELECT GROUP_CONCAT(bkg_id) as bkg_id, GROUP_CONCAT(bkg_bcb_id) as bkg_bcb_id FROM ( SELECT bkg_id, bkg_bcb_id FROM `booking` WHERE bkg_status IN (8,10) AND bkg_pickup_date < DATE(DATE_SUB(NOW(), INTERVAL 3 MONTH)) ORDER BY bkg_id LIMIT 0, $limit) AS TEMP";
				$row = DBUtil::queryRow($sql);
				if(!is_null($row) && $row != '')
				{
					$transaction = DBUtil::beginTransaction();
					$bkgId		 = $row['bkg_id'];
					$bkgBcbId	 = $row['bkg_bcb_id'];
					if(trim($bkgId) != '')
					{
						DBUtil::getINStatement($bkgId, $bindString, $params);
						foreach($arrBookingRelatedTables as $tableName => $fieldName)
						{
							$sql	 = "INSERT INTO " . $archiveDB . "." . $tableName . " (SELECT * FROM " . $tableName . " WHERE " . $fieldName . " IN ($bindString))";
							$rows	 = DBUtil::execute($sql, $params);
							if($rows > 0)
							{
								$sql	 = "DELETE FROM " . $tableName . " WHERE " . $fieldName . " IN ($bkgId)";
								$rowsDel = DBUtil::execute($sql, $params);
							}
						}

						$sql	 = "INSERT INTO " . $archiveDB . ".`booking` (SELECT * FROM `booking` WHERE bkg_id IN ($bindString))";
						$rows	 = DBUtil::execute($sql, $params);
						if($rows > 0)
						{
							$sql	 = "DELETE FROM `booking` WHERE bkg_id IN ($bindString)";
							$rowsDel = DBUtil::execute($sql, $params);
						}

						if(trim($bkgBcbId) != '')
						{
							DBUtil::getINStatement($bkgBcbId, $bindString1, $params1);
							$sql	 = "INSERT INTO " . $archiveDB . ".`booking_cab` (SELECT * FROM `booking_cab` WHERE bcb_id IN ($bindString1))";
							$rows	 = DBUtil::execute($sql, $params1);
							if($rows > 0)
							{
								$sql	 = "DELETE FROM `booking_cab` WHERE bcb_id IN ($bindString1)";
								$rowsDel = DBUtil::execute($sql, $params1);
							}
						}
					}
					DBUtil::commitTransaction($transaction);
				}
				$i += $limit;
				if($row <= 0 || $totRecords <= $i)
				{
					break;
				}
			}
			DBUtil::execute("SET FOREIGN_KEY_CHECKS=1;");
		}
		catch(Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			DBUtil::execute("SET FOREIGN_KEY_CHECKS=1;");
			Logger::exception($e);
			echo $e->getMessage();
		}
	}

	public static function getDailyPickupCount($b2c = true)
	{
		$partnerStr = '';
		if($b2c)
		{
			$partnerStr = "  AND (bmtd.bkg_agent_id = 0 OR bmtd.bkg_agent_id IS NULL OR bmtd.bkg_agent_id = '') ";
		}
		else
		{
			$partnerStr = " AND bmtd.bkg_agent_id > 0  ";
		}

		$sql = "
			SELECT \"Quoted\" category,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(bmtd.bkg_create_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH),1,0)) bmt_1,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE ) AND MONTH(bmtd.bkg_create_date) = MONTH(CURRENT_DATE ),1,0)) bmtd,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE - INTERVAL 1 WEEK) AND WEEK(bmtd.bkg_create_date) = WEEK(CURRENT_DATE  - INTERVAL 1 WEEK),1,0)) bwk_1,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE) AND WEEK(bmtd.bkg_create_date) = WEEK(CURRENT_DATE),1,0)) bwk ,
SUM(IF( DATE(bmtd.bkg_create_date) = DATE(CURRENT_DATE - INTERVAL 2 DAY),1,0)) bd_2,
SUM(IF( DATE(bmtd.bkg_create_date) = DATE(CURRENT_DATE - INTERVAL 1 DAY),1,0)) bd_1,
SUM(IF( DATE(bmtd.bkg_create_date) = CURRENT_DATE,1,0)) btd
FROM   booking bmtd WHERE bkg_status IN (15) $partnerStr
UNION
SELECT \"Booked\" category,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(bmtd.bkg_create_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH),1,0)) bmt_1,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE ) AND MONTH(bmtd.bkg_create_date) = MONTH(CURRENT_DATE ),1,0)) bmtd,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE - INTERVAL 1 WEEK) AND WEEK(bmtd.bkg_create_date) = WEEK(CURRENT_DATE  - INTERVAL 1 WEEK),1,0)) bwk_1,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE) AND WEEK(bmtd.bkg_create_date) = WEEK(CURRENT_DATE),1,0)) bwk ,
SUM(IF( DATE(bmtd.bkg_create_date) = DATE(CURRENT_DATE - INTERVAL 2 DAY),1,0)) bd_2,
SUM(IF( DATE(bmtd.bkg_create_date) = DATE(CURRENT_DATE - INTERVAL 1 DAY),1,0)) bd_1,
SUM(IF( DATE(bmtd.bkg_create_date) = CURRENT_DATE,1,0)) btd
FROM   booking bmtd WHERE bkg_status IN (2,3,5,6,7,9) $partnerStr
UNION
SELECT \"Served\" category,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(bmtd.bkg_pickup_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH),1,0)) bmt_1,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE ) AND MONTH(bmtd.bkg_pickup_date) = MONTH(CURRENT_DATE ),1,0)) bmtd,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE - INTERVAL 1 WEEK) AND WEEK(bmtd.bkg_pickup_date) = WEEK(CURRENT_DATE  - INTERVAL 1 WEEK),1,0)) bwk_1,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE) AND WEEK(bmtd.bkg_pickup_date) = WEEK(CURRENT_DATE),1,0)) bwk ,
SUM(IF( DATE(bmtd.bkg_pickup_date) = DATE(CURRENT_DATE - INTERVAL 2 DAY),1,0)) bd_2,
SUM(IF( DATE(bmtd.bkg_pickup_date) = DATE(CURRENT_DATE - INTERVAL 1 DAY),1,0)) bd_1,
SUM(IF( DATE(bmtd.bkg_pickup_date) = CURRENT_DATE,1,0)) btd
FROM   booking bmtd WHERE bkg_status IN (6,7) $partnerStr
UNION
SELECT \"Cancelled Booking (All)\" category,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(bmtd.bkg_create_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH),1,0)) bmt_1,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE ) AND MONTH(bmtd.bkg_create_date) = MONTH(CURRENT_DATE ),1,0)) bmtd,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE - INTERVAL 1 WEEK) AND WEEK(bmtd.bkg_create_date) = WEEK(CURRENT_DATE  - INTERVAL 1 WEEK),1,0)) bwk_1,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE) AND WEEK(bmtd.bkg_create_date) = WEEK(CURRENT_DATE),1,0)) bwk ,
SUM(IF( DATE(bmtd.bkg_create_date) = DATE(CURRENT_DATE - INTERVAL 2 DAY),1,0)) bd_2,
SUM(IF( DATE(bmtd.bkg_create_date) = DATE(CURRENT_DATE - INTERVAL 1 DAY),1,0)) bd_1,
SUM(IF( DATE(bmtd.bkg_create_date) = CURRENT_DATE,1,0)) btd
FROM   booking bmtd WHERE bkg_status IN (9) $partnerStr
UNION
SELECT \"Cancelled loss bookings\" category,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(bmtd.bkg_create_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH),cntbkg,0)) bmt_1,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE ) AND MONTH(bmtd.bkg_create_date) = MONTH(CURRENT_DATE ),cntbkg,0)) bmtd,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE - INTERVAL 1 WEEK) AND WEEK(bmtd.bkg_create_date) = WEEK(CURRENT_DATE  - INTERVAL 1 WEEK),cntbkg,0)) bwk_1,
SUM(IF( YEAR(bmtd.bkg_create_date) = YEAR(CURRENT_DATE) AND WEEK(bmtd.bkg_create_date) = WEEK(CURRENT_DATE),cntbkg,0)) bwk ,
SUM(IF( DATE(bmtd.bkg_create_date) = DATE(CURRENT_DATE - INTERVAL 2 DAY),cntbkg,0)) bd_2,
SUM(IF( DATE(bmtd.bkg_create_date) = DATE(CURRENT_DATE - INTERVAL 1 DAY),cntbkg,0)) bd_1,
SUM(IF( DATE(bmtd.bkg_create_date) = CURRENT_DATE,cntbkg,0)) btd
FROM
(SELECT distinct bcb_id, count(bkg_id) cntbkg, min(bkg_pickup_date) bkg_pickup_date, min(bkg_create_date) bkg_create_date,
(SUM(bkg_total_amount- bkg_service_tax - (IF(agents.agt_type=2,IF(agents.agt_commission_value = 1, ROUND(bkg_base_amount * IFNULL(agents.agt_commission, 0) * 0.01), IFNULL(agents.agt_commission, 0) ),0)))- if(bcb_trip_type=0,bcb_vendor_amount,SUM(bkg_vendor_amount)))      profit
FROM booking bmtd
JOIN booking_invoice bkginv ON bmtd.bkg_id = bkginv.biv_bkg_id
JOIN booking_cab bcb ON bmtd.bkg_bcb_id = bcb.bcb_id
LEFT JOIN agents ON agents.agt_id = bmtd.bkg_agent_id
WHERE bkg_status IN (9)  $partnerStr
AND bkg_create_date > date_sub(now(), INTERVAL 2 MONTH)
group by bcb.bcb_id HAVING profit < 0) bmtd
UNION
SELECT \"Served loss bookings\" category,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(bmtd.bkg_pickup_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH),cntbkg,0)) bmt_1,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE ) AND MONTH(bmtd.bkg_pickup_date) = MONTH(CURRENT_DATE ),cntbkg,0)) bmtd,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE - INTERVAL 1 WEEK) AND WEEK(bmtd.bkg_pickup_date) = WEEK(CURRENT_DATE  - INTERVAL 1 WEEK),cntbkg,0)) bwk_1,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE) AND WEEK(bmtd.bkg_pickup_date) = WEEK(CURRENT_DATE),cntbkg,0)) bwk ,
SUM(IF( DATE(bmtd.bkg_pickup_date) = DATE(CURRENT_DATE - INTERVAL 2 DAY),cntbkg,0)) bd_2,
SUM(IF( DATE(bmtd.bkg_pickup_date) = DATE(CURRENT_DATE - INTERVAL 1 DAY),cntbkg,0)) bd_1,
SUM(IF( DATE(bmtd.bkg_pickup_date) = CURRENT_DATE,cntbkg,0)) btd
FROM
(SELECT distinct bcb_id, count(bkg_id) cntbkg, min(bkg_pickup_date) bkg_pickup_date,
(SUM(bkg_total_amount- bkg_service_tax - (IF(agents.agt_type=2,IF(agents.agt_commission_value = 1, ROUND(bkg_base_amount * IFNULL(agents.agt_commission, 0) * 0.01), IFNULL(agents.agt_commission, 0) ),0)))- if(bcb_trip_type=0,bcb_vendor_amount,SUM(bkg_vendor_amount)))      profit
FROM booking bmtd
JOIN booking_invoice bkginv ON bmtd.bkg_id = bkginv.biv_bkg_id
JOIN booking_cab bcb ON bmtd.bkg_bcb_id = bcb.bcb_id
LEFT JOIN agents ON agents.agt_id = bmtd.bkg_agent_id
WHERE bkg_status IN (6,7)  $partnerStr
AND bkg_pickup_date > date_sub(now(), INTERVAL 2 MONTH)
group by bcb.bcb_id HAVING profit < 0) bmtd ";
		$res = DBUtil::queryAll($sql, DBUtil::SDB());
		return $res;
	}

	public static function getDailyPickupAmount($b2c = true)
	{
		$partnerStr = '';
		if($b2c)
		{
			$partnerStr = " AND (bmtd.bkg_agent_id = 0 OR bmtd.bkg_agent_id IS NULL OR bmtd.bkg_agent_id = '') ";
		}
		else
		{
			$partnerStr = " AND bmtd.bkg_agent_id > 0  ";
		}

		$sql = "
SELECT \"Served\" category,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(bmtd.bkg_pickup_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH),bkginv.bkg_total_amount,0)) bmt_1,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE) AND MONTH(bmtd.bkg_pickup_date) = MONTH(CURRENT_DATE ),bkginv.bkg_total_amount,0)) bmtd,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE - INTERVAL 1 WEEK) AND WEEK(bmtd.bkg_pickup_date) = WEEK(CURRENT_DATE  - INTERVAL 1 WEEK),bkginv.bkg_total_amount,0)) bwk_1,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE) AND WEEK(bmtd.bkg_pickup_date) = WEEK(CURRENT_DATE),bkginv.bkg_total_amount,0)) bwk ,
SUM(IF( DATE(bmtd.bkg_pickup_date) = DATE(CURRENT_DATE - INTERVAL 2 DAY),bkginv.bkg_total_amount,0)) bd_2,
SUM(IF( DATE(bmtd.bkg_pickup_date) = DATE(CURRENT_DATE - INTERVAL 1 DAY),bkginv.bkg_total_amount,0)) bd_1,
SUM(IF( DATE(bmtd.bkg_pickup_date) = CURRENT_DATE,bkginv.bkg_total_amount,0)) btd
FROM   booking bmtd
JOIN booking_invoice bkginv ON bmtd.bkg_id = bkginv.biv_bkg_id
WHERE bkg_status IN (6,7) $partnerStr
UNION

SELECT \"Served Profit\" category,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(bmtd.bkg_pickup_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH),bmtd.profit,0)) bmt_1,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE) AND MONTH(bmtd.bkg_pickup_date) = MONTH(CURRENT_DATE ),bmtd.profit,0)) bmtd,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE - INTERVAL 1 WEEK) AND WEEK(bmtd.bkg_pickup_date) = WEEK(CURRENT_DATE  - INTERVAL 1 WEEK),bmtd.profit,0)) bwk_1,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE) AND WEEK(bmtd.bkg_pickup_date) = WEEK(CURRENT_DATE),bmtd.profit,0)) bwk ,
SUM(IF( DATE(bmtd.bkg_pickup_date) = DATE(CURRENT_DATE - INTERVAL 2 DAY),bmtd.profit,0)) bd_2,
SUM(IF( DATE(bmtd.bkg_pickup_date) = DATE(CURRENT_DATE - INTERVAL 1 DAY),bmtd.profit,0)) bd_1,
SUM(IF( DATE(bmtd.bkg_pickup_date) = CURRENT_DATE,bmtd.profit,0)) btd
FROM
(SELECT distinct bcb_id, count(bkg_id) cntbkg, min(bkg_pickup_date) bkg_pickup_date,
(SUM(bkg_total_amount- bkg_service_tax - (IF(agents.agt_type=2,IF(agents.agt_commission_value = 1, ROUND(bkg_base_amount * IFNULL(agents.agt_commission, 0) * 0.01), IFNULL(agents.agt_commission, 0) ),0)))- if(bcb_trip_type=0,bcb_vendor_amount,SUM(bkg_vendor_amount)))      profit
FROM booking bmtd
JOIN booking_invoice bkginv ON bmtd.bkg_id = bkginv.biv_bkg_id
JOIN booking_cab bcb ON bmtd.bkg_bcb_id = bcb.bcb_id
LEFT JOIN agents ON agents.agt_id = bmtd.bkg_agent_id
WHERE bkg_status IN (6,7)  $partnerStr
AND bkg_pickup_date > date_sub(now(), INTERVAL 2 MONTH)
group by bcb.bcb_id) bmtd

UNION
SELECT \"Sum of negatives\" category,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(bmtd.bkg_pickup_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH),bmtd.profit,0)) bmt_1,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE) AND MONTH(bmtd.bkg_pickup_date) = MONTH(CURRENT_DATE ),bmtd.profit,0)) bmtd,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE - INTERVAL 1 WEEK) AND WEEK(bmtd.bkg_pickup_date) = WEEK(CURRENT_DATE  - INTERVAL 1 WEEK),bmtd.profit,0)) bwk_1,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE) AND WEEK(bmtd.bkg_pickup_date) = WEEK(CURRENT_DATE),bmtd.profit,0)) bwk ,
SUM(IF( DATE(bmtd.bkg_pickup_date) = DATE(CURRENT_DATE - INTERVAL 2 DAY),bmtd.profit,0)) bd_2,
SUM(IF( DATE(bmtd.bkg_pickup_date) = DATE(CURRENT_DATE - INTERVAL 1 DAY),bmtd.profit,0)) bd_1,
SUM(IF( DATE(bmtd.bkg_pickup_date) = CURRENT_DATE,bmtd.profit,0)) btd
FROM  (select min(bkg_pickup_date) bkg_pickup_date, group_concat(bkg_total_amount),bcb_vendor_amount,group_concat(bkg_vendor_amount),bcb_trip_type ,if(bcb_trip_type=0,bcb_vendor_amount,SUM(bkg_vendor_amount)) vamount,
(SUM(bkg_total_amount- bkg_service_tax - (IF(agents.agt_type=2,IF(agents.agt_commission_value = 1, ROUND(bkg_base_amount * IFNULL(agents.agt_commission, 0) * 0.01), IFNULL(agents.agt_commission, 0) ),0)))- if(bcb_trip_type=0,bcb_vendor_amount,SUM(bkg_vendor_amount)))      profit
FROM booking bmtd
JOIN booking_invoice bkginv ON bmtd.bkg_id = bkginv.biv_bkg_id
JOIN booking_cab bcb ON bmtd.bkg_bcb_id = bcb.bcb_id
LEFT JOIN agents ON agents.agt_id = bmtd.bkg_agent_id
WHERE bkg_status IN (6,7) $partnerStr

AND ( (bmtd.bkg_pickup_date) >= DATE_SUB(CURRENT_DATE , INTERVAL 2 MONTH) )
  group by bcb.bcb_id  having profit <0 ) bmtd

UNION
 SELECT \"Sum of positives\" category,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(bmtd.bkg_pickup_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH),bmtd.profit,0)) bmt_1,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE) AND MONTH(bmtd.bkg_pickup_date) = MONTH(CURRENT_DATE ),bmtd.profit,0)) bmtd,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE - INTERVAL 1 WEEK) AND WEEK(bmtd.bkg_pickup_date) = WEEK(CURRENT_DATE  - INTERVAL 1 WEEK),bmtd.profit,0)) bwk_1,
SUM(IF( YEAR(bmtd.bkg_pickup_date) = YEAR(CURRENT_DATE) AND WEEK(bmtd.bkg_pickup_date) = WEEK(CURRENT_DATE),bmtd.profit,0)) bwk ,
SUM(IF( DATE(bmtd.bkg_pickup_date) = DATE(CURRENT_DATE - INTERVAL 2 DAY),bmtd.profit,0)) bd_2,
SUM(IF( DATE(bmtd.bkg_pickup_date) = DATE(CURRENT_DATE - INTERVAL 1 DAY),bmtd.profit,0)) bd_1,
SUM(IF( DATE(bmtd.bkg_pickup_date) = CURRENT_DATE,bmtd.profit,0)) btd
FROM  (select min(bkg_pickup_date) bkg_pickup_date, group_concat(bkg_total_amount),bcb_vendor_amount,group_concat(bkg_vendor_amount),bcb_trip_type ,if(bcb_trip_type=0,bcb_vendor_amount,SUM(bkg_vendor_amount)) vamount,
(SUM(bkg_total_amount- bkg_service_tax - (IF(agents.agt_type=2,IF(agents.agt_commission_value = 1, ROUND(bkg_base_amount * IFNULL(agents.agt_commission, 0) * 0.01), IFNULL(agents.agt_commission, 0) ),0)))- if(bcb_trip_type=0,bcb_vendor_amount,SUM(bkg_vendor_amount)))      profit
FROM booking bmtd
JOIN booking_invoice bkginv ON bmtd.bkg_id = bkginv.biv_bkg_id
JOIN booking_cab bcb ON bmtd.bkg_bcb_id = bcb.bcb_id
LEFT JOIN agents ON agents.agt_id = bmtd.bkg_agent_id
WHERE bkg_status IN (6,7) $partnerStr

AND ( (bmtd.bkg_pickup_date) >= DATE_SUB(CURRENT_DATE , INTERVAL 2 MONTH) )
  group by bcb.bcb_id  having profit >0 ) bmtd
  ";

		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public static function getOverall()
	{
		$sql		 = "
			SELECT   'Operator' type ,
			SUM(if(adt.adt_amount>0,adt_amount,0)) 'Receivable',
			SUM(if(adt.adt_amount<0,adt_amount,0)) 'Payable' from
			(SELECT  SUM(adt_amount) adt_amount
		FROM account_trans_details adt
		INNER JOIN `vendors` vnd ON vnd.vnd_id = adt.adt_trans_ref_id AND vnd_id <> 43
		INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id
		WHERE act.act_active=1 AND adt.adt_type=2 AND adt.adt_ledger_id=14
		AND adt.adt_active=1 AND adt.adt_status=1
		GROUP BY vnd_id) adt
		UNION
			SELECT    'Partner' type,
			SUM(if(adt.adt_amount>0,adt_amount,0)) 'Receivable',
			SUM(if(adt.adt_amount<0,adt_amount,0)) 'Payable' from
			(SELECT SUM(adt_amount)  adt_amount
			FROM   account_trans_details adt
		INNER JOIN agents agt ON adt.adt_trans_ref_id = agt.agt_id AND adt.adt_type = 3
		INNER JOIN account_transactions act
		ON act.act_id = adt.adt_trans_id
		where adt_ledger_id = 15 AND adt.adt_status = 1 AND act.act_active = 1 AND adt.adt_active = 1
		GROUP BY agt_id) adt
";
		$cdb		 = Yii::app()->db1->createCommand($sql);
		$rowset		 = $cdb->queryAll();
		$resultVal	 = [];

		foreach($rowset as $row)
		{

			$resultVal[] = [$row['type'] . ' Receivable', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', round($row['Receivable'])];
			$resultVal[] = [$row['type'] . ' Payable', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', round($row['Payable'])];
		}

		return $resultVal;
	}

	public static function getTotPayble()
	{


		$sql = "  SELECT   SUM(adt_amount) tot,  SUM(if(adt.adt_amount>0,adt_amount,0)) 'Partner Receivable',
			SUM(if(adt.adt_amount<0,adt_amount,0)) 'Partner Payable'
			FROM   account_trans_details adt
			INNER JOIN agents agt ON adt.adt_trans_ref_id = agt.agt_id AND adt.adt_type = 3
			INNER JOIN account_transactions act
			ON act.act_id = adt.adt_trans_id
			where adt_ledger_id = 15 AND adt.adt_status = 1 AND act.act_active = 1 AND adt.adt_active = 1";

		$cdb	 = Yii::app()->db1->createCommand($sql);
		$result	 = $cdb->queryAll();
		return $result;
	}

	public static function getNPSDiff()
	{
		$selSum	 = "TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/count(rtg_customer_recommend))*100,3)";
		$selSum1 = "(SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))";
		$selStr	 = "(SELECT $selSum from ratings where ";
		$sql	 = "SELECT \"(%Promoters - %Detractors)\" category,
$selStr YEAR(rtg_customer_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(rtg_customer_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND rtg_customer_recommend > 0) bmt_1,
$selStr YEAR(rtg_customer_date) = YEAR(CURRENT_DATE) AND MONTH(rtg_customer_date) = MONTH(CURRENT_DATE ) AND rtg_customer_recommend > 0) bmtd,
$selStr YEAR(rtg_customer_date) = YEAR(CURRENT_DATE - INTERVAL 1 WEEK) AND WEEK(rtg_customer_date) = WEEK(CURRENT_DATE  - INTERVAL 1 WEEK) AND rtg_customer_recommend > 0) bwk_1,
$selStr YEAR(rtg_customer_date) = YEAR(CURRENT_DATE) AND WEEK(rtg_customer_date) = WEEK(CURRENT_DATE) AND rtg_customer_recommend > 0) bwk,
$selStr DATE(rtg_customer_date) = DATE(CURRENT_DATE - INTERVAL 2 DAY) AND rtg_customer_recommend > 0) bd_2,
$selStr DATE(rtg_customer_date) = DATE(CURRENT_DATE - INTERVAL 2 DAY) AND rtg_customer_recommend > 0) bd_1,
(SELECT IFNULL($selSum,0) from ratings where DATE(rtg_customer_date) = CURRENT_DATE AND rtg_customer_recommend > 0) btd
  ";
		$cdb	 = Yii::app()->db1->createCommand($sql);
		$result	 = $cdb->queryAll();
		return $result;
	}

	public static function getDailyReportData($repId)
	{
		$ReportArr = [];
		switch($repId)
		{
			case 1:
				$report		 = BookingSub::getDailyPickupCount(false);
				$captionText = "B2B Count";
				break;
			case 2:
				$report		 = BookingSub::getDailyPickupCount(true);
				$captionText = "B2C Count";
				break;
			case 3:
				$report		 = BookingSub::getDailyPickupAmount(false);
				$captionText = "B2B Amount";
				break;
			case 4:
				$report		 = BookingSub::getDailyPickupAmount(true);
				$captionText = "B2C Amount";
				break;
			case 5:
				$report		 = BookingSub::getOverall();

				$captionText = "Overall  ";
				break;
			case 6:
				$report		 = BookingSub::getNPSDiff();
				$captionText = "NPS  ";
				break;
		}
		return ['report' => $report, 'captionText' => $captionText];
	}

	public function getHourDetailsForPenaltyByTrip($bcbId)
	{

		$sql = "SELECT
                    vendor_assign_date,
                    AssignedWorkingHours,
                    TIMESTAMPDIFF(HOUR, vendor_assign_date,NOW() )as AssignedHours,
                    pickup_datetime,
                    PickupWorkingHours,
                    TIMESTAMPDIFF(HOUR, NOW(), pickup_datetime)as PickupHours,
                    TIMESTAMPDIFF(HOUR, vendor_assign_date,pickup_datetime )as GivenHours,
                    bkg_total_Amount,bcb_assign_mode
                    FROM
				(
                                SELECT booking_cab.bcb_vendor_id,booking_invoice.bkg_total_Amount AS bkg_total_Amount,booking_cab.bcb_assign_mode AS bcb_assign_mode,
				booking_trail.bkg_assigned_at AS vendor_assign_date,
				MAX(booking.bkg_pickup_date) AS pickup_datetime,

				ROUND(((DATEDIFF(NOW(), booking_trail.bkg_assigned_at) +1) * 14 * 60 - IF(HOUR(booking_trail.bkg_assigned_at)<22,
                                GREATEST(ROUND(TIME_TO_SEC(TIME(booking_trail.bkg_assigned_at))/60) - 8*60,0),14*60) - IF(HOUR(NOW())<8,14*60,
                                GREATEST(22*60 - ROUND(TIME_TO_SEC(NOW())/60),0 )))/60) as AssignedWorkingHours,

                        ROUND(((DATEDIFF(bkg_pickup_date, NOW()) +1) * 14 * 60
- IF(HOUR(NOW())<22, GREATEST(ROUND(TIME_TO_SEC(TIME(NOW()))/60) - 8*60,0),14*60)
- IF(HOUR(bkg_pickup_date)<8,14*60, GREATEST(22*60 - ROUND(TIME_TO_SEC(bkg_pickup_date)/60),0 )))/60) as PickupWorkingHours
                FROM
                `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking.bkg_active = 1 AND booking_cab.bcb_active = 1
                INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id = booking.bkg_id
                INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
                LEFT JOIN `vendors` ON vendors.vnd_id = booking_cab.bcb_vendor_id

			WHERE
			booking_cab.bcb_id = $bcbId

			GROUP BY
			booking_cab.bcb_id)a";

		$row = DBUtil::queryRow($sql);
		return $row;
	}

	public function checkCabUpdateCount($bkgId)
	{
		$sql = "SELECT count(1) count FROM agent_api_tracking  WHERE aat_booking_id = $bkgId AND aat_type=9 AND aat_status=1";
		$row = DBUtil::queryRow($sql);
		return $row['count'];
	}

	public function checkPartnerCabUpdateCount($bkgId)
	{
		$sql = "SELECT count(1) count FROM partner_api_tracking  WHERE pat_booking_id = $bkgId AND pat_type=1 AND pat_status=1";
		$row = DBUtil::queryRow($sql);
		return $row['count'];
	}

	public function findZonewiseBookingCount($fromDate, $toDate)
	{
		$arrZones					 = Vendors::getRegionList();
		$arrZones[7]				 = 'Kerela';
		$countReport				 = [];
		$createdLeadCount			 = $this->createdLeadCount($fromDate, $toDate);
		$countReport[0]['status']	 = "Count of Lead Created";
		foreach($createdLeadCount as $val)
		{
			$zoneName					 = str_replace(" ", "", strtolower($arrZones[$val['zone']]));
			$countReport[0][$zoneName]	 = $val['cnt'];
		}
		$countFollowedup			 = $this->totalFollowedupCountRegionwise($fromDate, $toDate);
		$countReport[1]['status']	 = "Count of Total Followed up";
		foreach($countFollowedup as $val)
		{
			$zoneName					 = str_replace(" ", "", strtolower($arrZones[$val['zone']]));
			$countReport[1][$zoneName]	 = $val['cnt'];
		}
		$countFollowedup			 = $this->totalUniqueFollowedupCountRegionwise($fromDate, $toDate);
		$countReport[2]['status']	 = "Count of Unique Followed up";
		foreach($countFollowedup as $val)
		{
			$zoneName					 = str_replace(" ", "", strtolower($arrZones[$val['zone']]));
			$countReport[2][$zoneName]	 = $val['cnt'];
		}
		$countFollowedup			 = $this->totalActiveLeadCountRegionwise($fromDate, $toDate);
		$countReport[3]['status']	 = "Count of Active Leads";
		foreach($countFollowedup as $val)
		{
			$zoneName					 = str_replace(" ", "", strtolower($arrZones[$val['zone']]));
			$countReport[3][$zoneName]	 = $val['cnt'];
		}
		$countFollowedup			 = $this->totalInactiveLeadCountRegionwise($fromDate, $toDate);
		$countReport[4]['status']	 = "Count of Inactive Leads";
		foreach($countFollowedup as $val)
		{
			$zoneName					 = str_replace(" ", "", strtolower($arrZones[$val['zone']]));
			$countReport[4][$zoneName]	 = $val['cnt'];
		}
		$countUnverified			 = $this->unverifiedBookingCount($fromDate, $toDate);
		$countReport[5]['status']	 = "Count of Unverified";
		foreach($countUnverified as $val)
		{
			$zoneName					 = str_replace(" ", "", strtolower($arrZones[$val['zone']]));
			$countReport[5][$zoneName]	 = $val['cnt'];
		}
		$countQuote					 = $this->quoteBookingCount($fromDate, $toDate);
		$countReport[6]['status']	 = "Count of Quote";
		foreach($countQuote as $val)
		{
			$zoneName					 = str_replace(" ", "", strtolower($arrZones[$val['zone']]));
			$countReport[6][$zoneName]	 = $val['cnt'];
		}
		$countQuoteToUnverified		 = $this->quoteToUnverifiedBookingCount($fromDate, $toDate);
		$countReport[7]['status']	 = "Quote to Unverified";
		foreach($countQuoteToUnverified as $val)
		{
			$zoneName					 = str_replace(" ", "", strtolower($arrZones[$val['zone']]));
			$countReport[7][$zoneName]	 = $val['cnt'];
		}
		$countQuoteToNew			 = $this->quoteToNewBookingCount($fromDate, $toDate);
		$countReport[8]['status']	 = "Quote to New";
		foreach($countQuoteToNew as $val)
		{
			$zoneName					 = str_replace(" ", "", strtolower($arrZones[$val['zone']]));
			$countReport[8][$zoneName]	 = $val['cnt'];
		}
		$countUnverifiedToNew		 = $this->unverifiedToNewBookingCount($fromDate, $toDate);
		$countReport[9]['status']	 = "Unverified to New";
		foreach($countUnverifiedToNew as $val)
		{
			$zoneName					 = str_replace(" ", "", strtolower($arrZones[$val['zone']]));
			$countReport[9][$zoneName]	 = $val['cnt'];
		}
		$createdBookingCount		 = $this->createdNewBookingCount($fromDate, $toDate);
		$countReport[10]['status']	 = "Count of New";
		foreach($createdBookingCount as $val)
		{
			$zoneName					 = str_replace(" ", "", strtolower($arrZones[$val['zone']]));
			$countReport[10][$zoneName]	 = $val['cnt'];
		}
		return $countReport;
	}

	public function unverifiedBookingCount($fromDate, $toDate)
	{
		$sql = "SELECT COUNT(bkg_id) as cnt, states.stt_zone as zone
				FROM booking
				INNER JOIN cities ON cty_id = bkg_from_city_id
				INNER JOIN states ON states.stt_id=cities.cty_state_id
				WHERE bkg_status = 1 AND bkg_create_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' AND bkg_active=1
				GROUP BY states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function quoteBookingCount($fromDate, $toDate)
	{
		$sql = "SELECT COUNT(bkg_id) as cnt, states.stt_zone as zone
				FROM booking
				INNER JOIN cities ON cty_id = bkg_from_city_id
				INNER JOIN states ON states.stt_id=cities.cty_state_id
				WHERE bkg_status = 15 AND bkg_create_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' AND bkg_active=1
				GROUP BY states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function quoteToUnverifiedBookingCount($fromDate, $toDate)
	{
		$sql = "SELECT COUNT(bkg_id) as cnt, states.stt_zone as zone
				FROM booking
				INNER JOIN booking_trail ON btr_bkg_id = bkg_id AND bkg_confirm_type = 5 AND bkg_create_type = 1
				INNER JOIN cities ON cty_id = bkg_from_city_id
				INNER JOIN states ON states.stt_id=cities.cty_state_id
				WHERE bkg_status = 1 AND bkg_create_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' AND bkg_active=1
				GROUP BY states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function quoteToNewBookingCount($fromDate, $toDate)
	{
		$sql = "SELECT COUNT(bkg_id) as cnt, states.stt_zone as zone
				FROM booking
				INNER JOIN booking_trail ON btr_bkg_id = bkg_id AND bkg_confirm_type = 1 AND bkg_create_type = 1
				INNER JOIN cities ON cty_id = bkg_from_city_id
				INNER JOIN states ON states.stt_id=cities.cty_state_id
				WHERE bkg_status = 2 AND bkg_create_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' AND bkg_active=1
				GROUP BY states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function unverifiedToNewBookingCount($fromDate, $toDate)
	{
		$sql = "SELECT COUNT(bkg_id) as cnt, states.stt_zone as zone
				FROM booking
				INNER JOIN booking_trail ON btr_bkg_id = bkg_id AND bkg_create_type = 3 AND bkg_confirm_type = 2
				INNER JOIN cities ON cty_id = bkg_from_city_id
				INNER JOIN states ON states.stt_id=cities.cty_state_id
				WHERE bkg_status = 2 AND bkg_create_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' AND bkg_active=1
				GROUP BY states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function createdLeadCount($fromDate, $toDate)
	{
		$sql = "SELECT COUNT(bkg_id) as cnt,states.stt_zone as zone
				FROM booking_temp
				INNER JOIN cities ON cty_id = bkg_from_city_id
				INNER JOIN states ON states.stt_id = cities.cty_state_id
				WHERE bkg_create_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' AND bkg_active=1
				GROUP BY states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function createdNewBookingCount($fromDate, $toDate)
	{
		$sql = "SELECT COUNT(bkg_id) as cnt, states.stt_zone as zone
				FROM booking
				INNER JOIN cities ON cty_id = bkg_from_city_id
				INNER JOIN states ON states.stt_id=cities.cty_state_id
				WHERE bkg_create_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' AND booking.bkg_status=2 AND bkg_active=1
				GROUP BY states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function totalFollowedupCountRegionwise($fromDate, $toDate)
	{
		$sql = "SELECT COUNT(blg_booking_id) as cnt,states.stt_zone as zone
				FROM lead_log
				inner join booking_temp btemp on btemp.bkg_id=blg_booking_id
				INNER JOIN cities ON cty_id = btemp.bkg_from_city_id
				INNER JOIN states ON states.stt_id = cities.cty_state_id
				WHERE blg_created BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' AND btemp.bkg_active=1 AND blg_admin_id IS NOT NULL
				GROUP BY states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function totalUniqueFollowedupCountRegionwise($fromDate, $toDate)
	{
		$sql = "SELECT COUNT(DISTINCT blg_booking_id) as cnt,states.stt_zone as zone
				FROM lead_log
				inner join booking_temp btemp on btemp.bkg_id=blg_booking_id
				INNER JOIN cities ON cty_id = btemp.bkg_from_city_id
				INNER JOIN states ON states.stt_id = cities.cty_state_id
				WHERE blg_created BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' AND btemp.bkg_active=1 AND blg_admin_id IS NOT NULL
				GROUP BY states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function totalActiveLeadCountRegionwise($fromDate, $toDate)
	{
		$sql = "SELECT sum(if(btemp.bkg_follow_up_status in(0, 1, 2, 3, 15, 16), 1, 0)) as cnt,states.stt_zone as zone
				FROM lead_log
				inner join booking_temp btemp on btemp.bkg_id=blg_booking_id
				INNER JOIN cities ON cty_id = btemp.bkg_from_city_id
				INNER JOIN states ON states.stt_id = cities.cty_state_id
				WHERE blg_created BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' AND btemp.bkg_active=1 AND blg_admin_id IS NOT NULL
				GROUP BY states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function totalInactiveLeadCountRegionwise($fromDate, $toDate)
	{
		$sql = "SELECT sum(if(btemp.bkg_follow_up_status in(4, 5, 6, 7, 8, 9, 10, 14), 1, 0)) as cnt,states.stt_zone as zone
				FROM lead_log
				inner join booking_temp btemp on btemp.bkg_id=blg_booking_id
				INNER JOIN cities ON cty_id = btemp.bkg_from_city_id
				INNER JOIN states ON states.stt_id = cities.cty_state_id
				WHERE blg_created BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' AND btemp.bkg_active=1 AND blg_admin_id IS NOT NULL
				GROUP BY states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public static function getFinalFollowup()
	{
		$sql = "SELECT
					booking.bkg_id,
					booking.bkg_create_date,
					booking.bkg_pickup_date,
					booking_user.bkg_contact_no,
					booking_user.bkg_user_email,
					booking.bkg_status,
					TIMESTAMPDIFF(HOUR, booking.bkg_create_date,NOW()) as age
				FROM `booking`
				JOIN `booking_user` ON booking.bkg_id = booking_user.bui_bkg_id
				JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking_user.bui_bkg_id
				WHERE booking.bkg_pickup_date > DATE_ADD(NOW(), INTERVAL 5 HOUR)
				AND booking.bkg_create_date BETWEEN DATE_SUB(NOW(), INTERVAL 12 HOUR) AND DATE_SUB(NOW(), INTERVAL 30 MINUTE)
				AND booking.bkg_status = 1
				AND(bkg_agent_id = 0 OR bkg_agent_id IS NULL)
				AND booking_trail.btr_cron_final_followup_ctr = 0
				GROUP BY booking.bkg_id
				ORDER BY bkg_id DESC";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function findLeadTeamPerformanceDetails($fromDate, $toDate)
	{

		$sql			 = "select
				distinct CONCAT(adm_fname,' ',adm_lname) as name,
				totQuote,
				qtNew,
				qtUn,
				totBook,
				srvBook,
				activeBooking,
				totAmount,
				gozoAmount,
				servedGozoAmount,
				ROUND( ( (gozoAmount - serviceTax) * 100 ) / totAmount ) as marginPercent,
				uniqueLead,
				ROUND( totQuote * 100 / totBook,2 ) as qtRatio,
				ROUND( activeBooking * 100 / totBook,2 ) as newRatio,
				ROUND( (unNew + ldNew) * 100 / activeBooking,2 ) as converRatio
			from
				admins
			inner join authassignment on
				userid = adm_id and itemname = '1 - CSR' and adm_active = 1
			left join (
				select
					count(*) as totBook,
					sum( if((bkg5.bkg_status = 6 or bkg5.bkg_status = 7), 1 , 0)) as srvBook,
					sum( if(bkg5.bkg_status = 2, 1, 0)) as activeBooking,
					sum( if(bkg5.bkg_status = 15, 1 , 0 )) as totQuote,
					sum( if(bkg5.bkg_status = 2 and btr5.bkg_confirm_type = 3, 1 , 0 )) as ldNew,
					sum( if(bkg5.bkg_status = 2 and btr5.bkg_confirm_type = 2 and btr5.bkg_create_type = 3, 1 , 0 )) as unNew,
					sum( if(bkg5.bkg_status = 2 and btr5.bkg_confirm_type = 1 and btr5.bkg_create_type = 1, 1 , 0 )) as qtNew,
					sum( if(bkg5.bkg_status = 1 and btr5.bkg_confirm_type = 5 and btr5.bkg_create_type = 1, 1 , 0 )) as qtUn,
					sum( if( (bkg5.bkg_status <> 1 or bkg5.bkg_status <> 15), biv.bkg_total_amount - biv.bkg_service_tax, 0 ) ) as totAmount,
					sum( if( (bkg5.bkg_status <> 1 or bkg5.bkg_status <> 15), biv.bkg_total_amount - bcb_vendor_amount, 0 ) ) as gozoAmount,
					sum( if( (bkg5.bkg_status = 6 or bkg5.bkg_status = 7), biv.bkg_total_amount - bcb_vendor_amount - biv.bkg_service_tax, 0) ) as servedGozoAmount,
					sum( biv.bkg_service_tax) as serviceTax,
					btr5.bkg_create_user_id
				from
					booking bkg5
				inner join booking_cab on
					booking_cab.bcb_id = bkg5.bkg_bcb_id
					and booking_cab.bcb_active = 1
				inner join booking_trail btr5 on
					btr5.btr_bkg_id = bkg5.bkg_id
					and btr5.bkg_create_user_type = 4
				inner join booking_invoice biv on
					biv.biv_bkg_id = bkg5.bkg_id
				where
					bkg5.bkg_active = 1
					and bkg5.bkg_create_date between '$fromDate 00:00:00' and '$toDate 23:59:59'
					and bkg_status in(1,2,3,4,5,6,7,9,15)
				group by
					btr5.bkg_create_user_id )ta on
				ta.bkg_create_user_id = adm_id
			left join(
				select
					blg_admin_id,
					count(distinct blg_booking_id) as uniqueLead
				from
					`lead_log`
				inner join booking_temp on
					lead_log.blg_booking_id = booking_temp.bkg_id
					and lead_log.blg_desc not like 'Lead assigned %'
					and lead_log.blg_desc not like 'Lead Locked %'
					and lead_log.blg_desc not like '%Duplicate%'
					and lead_log.blg_desc not like '%Invalid%'
					and booking_temp.bkg_follow_up_status <> 7
				inner join admins on
					admins.adm_id = lead_log.blg_admin_id
					where
					blg_created between '$fromDate 00:00:00' and '$toDate 23:59:59'
				group by
					blg_admin_id ) ld on
				ld.blg_admin_id = adm_id ";
		$count			 = Yii::app()->db1->createCommand("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => [],
				'defaultOrder'	 => 'totBook desc'],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public static function getCountByRoutes($date, $frmcityId, $tocityId, $tripType = '1')
	{
		$tripTypeQry = '';
		if(in_array($tripType, [1, 2, 3]))
		{
			$tripTypeQry = " AND bkg_booking_type IN (1,2,3)";
		}
		if(in_array($tripType, [9, 10, 11, 4, 12, 15]))
		{
			$tripTypeQry = " AND bkg_booking_type IN (4, 9, 10, 11, 12, 15)";
		}
		$date	 = date('Y-m-d', strtotime($date));
		$sql	 = "SELECT @cTotal:=ROUND(@cActive+@cQuoted/4) as total FROM (SELECT @cActive:=count(DISTINCT IF(bkg_status IN (2,3,4,5), bkg_id, null)) as countActive,
									@cQuoted:=count(DISTINCT IF(bkg_status IN (15), bkg_id, null)) as countQuoted
								FROM booking
								WHERE (bkg_pickup_date BETWEEN '$date 00:00:00' AND '$date 23:59:59')
								AND  (bkg_agent_id IS NULL OR  bkg_agent_id  IN (450,1249,18190))
								AND bkg_from_city_id = $frmcityId AND bkg_to_city_id = $tocityId AND bkg_status IN (2,3,4,5,15) $tripTypeQry) a";
		$rows	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 300, CacheDependency::Type_TransactionStats);
		$data	 = $rows['total'];
		$data	 += isset($GLOBALS['ddbpBkgCount']) ? $GLOBALS['ddbpBkgCount'] : 0;
		return $data;
	}

	/**
	 *  @param int $filterType {1: Pickup Date, 2: Create Date}
	 */
	public static function getCountByZoneRoutes($date, $frmcityId, $tocityId, $tripType = '1', $filterType = 1)
	{
		$key	 = "countByZoneRoutes_{$date}_{$frmcityId}_{$tocityId}_{$tripType}";
		$data	 = Yii::app()->cache->get($key);
		if($data == false)
		{
			$filterQuery = "bkg_pickup_date";
			if($filterType == 2)
			{
				$filterQuery = "bkg_create_date";
			}
			$tripTypeQry = '';
			if(in_array($tripType, [1, 2, 3]))
			{
				$tripTypeQry = " AND bkg_booking_type IN (1,2,3)";
			}
			if(in_array($tripType, [9, 10, 11, 4, 12, 15]))
			{
				$tripTypeQry = " AND bkg_booking_type IN (4, 9, 10, 11, 12, 15)";
			}
			$date		 = date('Y-m-d', strtotime($date));
			$frmZonId	 = Zones::model()->getByCityId($frmcityId);
			$toZonId	 = Zones::model()->getByCityId($tocityId);
			$sql		 = "SELECT @cTotal:=ROUND(@cActive+@cQuoted/5) as total FROM (SELECT @cActive:=count(DISTINCT IF(bkg_status IN (2,3,4,5), bkg_id, null)) as countActive,
									@cQuoted:=count(DISTINCT IF(bkg_status IN (15), bkg_id, null)) as countQuoted
								FROM booking bkg
								INNER JOIN zone_cities fromZoneCities ON bkg.bkg_from_city_id = fromZoneCities.zct_cty_id
								INNER JOIN zone_cities toZoneCities ON bkg.bkg_to_city_id = toZoneCities.zct_cty_id
								WHERE $filterQuery BETWEEN '$date 00:00:00' AND '$date 23:59:59'
									AND  (bkg_agent_id IS NULL OR  bkg_agent_id  IN (450,18190))
									AND bkg_status IN (2,3,4,5,15) AND bkg.bkg_active=1 $tripTypeQry
									AND fromZoneCities.zct_zon_id IN ($frmZonId) AND toZoneCities.zct_zon_id IN ($toZonId))a
						";
			$rows		 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 300, CacheDependency::Type_TransactionStats);
			$data		 = $rows['total'];
			Yii::app()->cache->set($key, $data, 300, new CacheDependency('countByZoneRoutes'));
		}
		return $data;
	}

	/**
	 *  @param int $filterType {1: Pickup Date, 2: Create Date}
	 */
	public function getCountByZoneStateRoutes($date, $frmcityId, $tocityId, $tripType = '1', $filterType = 1)
	{
		$key	 = "countByZoneStateRoutes_{$date}_{$frmcityId}_{$tocityId}_{$tripType}";
		$data	 = Yii::app()->cache->get($key);
		if($data == false)
		{
			$filterQuery = "bkg_pickup_date";
			if($filterType == 2)
			{
				$filterQuery = "bkg_create_date";
			}
			$tripTypeQry = '';
			if(in_array($tripType, [1, 2, 3]))
			{
				$tripTypeQry = " AND bkg_booking_type IN (1,2,3)";
			}
			if(in_array($tripType, [9, 10, 11, 4, 12, 15]))
			{
				$tripTypeQry = " AND bkg_booking_type IN (4, 9, 10, 11, 12, 15)";
			}
			$date		 = date('Y-m-d', strtotime($date));
			$frmZonId	 = Zones::model()->getByCityId($frmcityId);
			$toStateId	 = States::model()->getByCityId($tocityId);
			$sql		 = "SELECT @cTotal:=ROUND(@cActive+@cQuoted/5) as total FROM (SELECT @cActive:=count(DISTINCT IF(bkg_status IN (2,3,4,5), bkg_id, null)) as countActive,
									@cQuoted:=count(DISTINCT IF(bkg_status IN (15), bkg_id, null)) as countQuoted
								FROM booking bkg
                                INNER JOIN zone_cities fromZoneCities ON fromZoneCities.zct_cty_id = bkg.bkg_from_city_id
                                INNER JOIN cities cty ON cty.cty_id = bkg.bkg_to_city_id
                                INNER JOIN states stt on stt.stt_id = cty.cty_state_id
                                    WHERE $filterQuery BETWEEN '$date 00:00:00' AND '$date 23:59:59'
									AND  (bkg_agent_id IS NULL OR  bkg_agent_id  IN (450,18190))
									AND bkg_status IN (2,3,4,5,15) AND bkg.bkg_active=1 $tripTypeQry
									AND fromZoneCities.zct_zon_id IN ($frmZonId) AND cty.cty_state_id IN ($toStateId) )a";

			$rows	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 300, CacheDependency::Type_TransactionStats);
			$data	 = $rows['total'];
			Yii::app()->cache->set($key, $data, 300, new CacheDependency('countByZoneRoutes'));
		}
		return $data;
	}

	/**
	 *  @param int $filterType {1: Pickup Date, 2: Create Date}
	 */
	public static function getCountByZone($date, $frmcityId, $tripType = '1', $filterType = 1)
	{
		$frmZonId = Zones::model()->getByCityId($frmcityId);
		if($frmZonId != '')
		{

			$key	 = "countByZones_{$date}_{$frmZonId}_{$tripType}";
			$data	 = Yii::app()->cache->get($key);
			if($data == false)
			{
				$filterQuery = "bkg_pickup_date";
				if($filterType == 2)
				{
					$filterQuery = "bkg_create_date";
				}
				$tripTypeQry = " AND bkg_booking_type IN (1,2,3)";
				if(in_array($tripType, [9, 10, 11, 4, 12, 15]))
				{
					$tripTypeQry = " AND bkg_booking_type IN (4, 9, 10, 11, 12, 15)";
				}
				$date	 = date('Y-m-d', strtotime($date));
//			$toStateId	 = States::model()->getByCityId($tocityId);
				$sql	 = "SELECT @cTotal:=ROUND(@cActive+@cQuoted/4) as total FROM (SELECT @cActive:=count(DISTINCT IF(bkg_status IN (2,3,4,5), bkg_id, null)) as countActive,
									@cQuoted:=count(DISTINCT IF(bkg_status IN (15), bkg_id, null)) as countQuoted
							FROM booking bkg
							INNER JOIN zone_cities fromZoneCities ON fromZoneCities.zct_cty_id = bkg.bkg_from_city_id
							INNER JOIN cities cty ON cty.cty_id = bkg.bkg_to_city_id
							INNER JOIN states stt on stt.stt_id = cty.cty_state_id
							WHERE $filterQuery BETWEEN '$date 00:00:00' AND '$date 23:59:59'
								AND  (bkg_agent_id IS NULL OR  bkg_agent_id  IN (450,18190))
								AND bkg_status IN (2,3,4,5,15) AND bkg.bkg_active=1 $tripTypeQry
								AND fromZoneCities.zct_zon_id IN ($frmZonId))a";

				$rows	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 300, CacheDependency::Type_TransactionStats);
				$data	 = $rows['total'];
				Yii::app()->cache->set($key, $data, 300, new CacheDependency('countByZones'));
			}
		}
		else
		{
			$data = 5;
		}
		return $data;
	}

	public function processCriticalitySteps()
	{
		Logger::info("BookingPref::model()->updateCriticalityScore START");
		//Criticalityscore
		BookingPref::model()->updateCriticalityScore();
		Logger::info("BookingPref::model()->updateCriticalityScore END\n");

		Logger::info("BookingTrail::startVendorAutoAssignment START");
		//VendorAutoAssignStartLog
		BookingTrail::startVendorAutoAssignment();

		Logger::info("BookingTrail::startVendorAutoAssignment END\n");

		Logger::info("BookingTrail::setDemSupMisFire START");

		//SetDemSupMisFire
		BookingTrail::setDemSupMisFire();
		Logger::info("BookingTrail::setDemSupMisFire END\n");

		Logger::info("BookingPref::processManualAssignments START");

		//UpdateManualAssignment
		BookingPref::processManualAssignments();
		Logger::info("BookingPref::processManualAssignments END\n");

		Logger::info("BookingPref::processCriticalAssignments START");

		//MarkCriticalAssignment
		BookingPref::processCriticalAssignments();
		Logger::info("BookingPref::processCriticalAssignments END\n");

		Logger::info("BookingVendorRequest::autoVendorAssignments START");
		BookingVendorRequest::autoVendorAssignments();
		Logger::info("BookingVendorRequest::autoVendorAssignments END\n");

		Logger::info("BookingCab::model()->updateCriticalTripAmount START");
		//CriticalTripAmount
		BookingCab::model()->updateCriticalTripAmount();
		Logger::info("BookingCab::model()->updateCriticalTripAmount END\n");
	}

	public function autoAssignReport($model, $param, $command = false)
	{
		$isamt				 = $param['is_advance_amount'];
		$isdbo				 = $param['is_dbo_applicable'];
		$isreconfirm		 = $param['is_reconfirm_flag'];
		$is_New				 = $param['is_New'];
		$is_Assigned		 = $param['is_Assigned'];
		$is_manual			 = $param['is_Manual'];
		$cfromDate			 = $model->bkg_create_date1;
		$ctoDate			 = $model->bkg_create_date2;
		$pdate1				 = $model->bkg_pickup_date1;
		$pdate2				 = $model->bkg_pickup_date2;
		$adate1				 = $model->tripAssignmnetFromTime;
		$adate2				 = $model->tripAssignmnetToTime;
		$bkg_service_class	 = $model->bkg_service_class;
		$var				 = "N/A";
		$where				 = " 1 ";
		if($isamt != null)
		{
			$where .= " AND bkg_advance_amount != 0 ";
		}
		if($isdbo != null)
		{
			$where .= " AND btr_is_dbo_applicable = 1  ";
		}
		if($isreconfirm != null)
		{
			$where .= " AND bkg_reconfirm_flag = 1  ";
		}
		if($is_New != null)
		{
			$where .= " AND booking.bkg_status =2 ";
		}
		if($is_Assigned != null && $is_manual == null)
		{
			$where .= " AND booking.bkg_status IN(3,5,6,7,9) AND bcb_assign_mode =1  ";
		}
		if($is_manual != null && $is_Assigned == null)
		{
			$where .= " AND booking.bkg_status IN(3,5,6,7,9) AND bcb_assign_mode =0 ";
		}
		if($is_manual != null && $is_Assigned != null)
		{
			$where .= " AND booking.bkg_status IN(3,5,6,7,9)";
		}
		if($adate1 != null && $adate2 != null)
		{
			$where .= " AND bkg_assigned_at between '$adate1 00:00:00' and '$adate2 23:59:59' ";
		}
		if(count($bkg_service_class) > 0 && $bkg_service_class != '')
		{
			$sccarr = [];
			foreach($bkg_service_class as $k => $bc)
			{
				if($bc == '1')
				{
					$sccarr[] = $k;
				}
			}
			if(count($sccarr) > 0)
			{
				$svcType = implode(",", $sccarr);
				$where	 .= " AND scv.scv_scc_id IN ($svcType)";
			}
		}

		if(($cfromDate != '' && $ctoDate != '') && ($pdate1 != '' && $pdate2 != ''))
		{
			$whereDate = " AND booking.bkg_create_date between '$cfromDate 00:00:00' and '$ctoDate 23:59:59'  AND booking.bkg_pickup_date between '$pdate1 00:00:00' and '$pdate2 23:59:59'";
		}
		else
		{
			$whereDate = " ";
		}
		$sql = "SELECT
				bkg_assigned_at,
				bcb_cab_assignmenttime,
				bcb_bkg_id1,
				bcb_id,GROUP_CONCAT(bkg_id),
				GROUP_CONCAT(bkg_booking_id SEPARATOR ', ') as bbkID,
				bkg_agent_id,GROUP_CONCAT(agt_company) as company,
				GROUP_CONCAT(bkg_create_date) as createdt,
				GROUP_CONCAT(bkg_pickup_date) as pickup,
				GROUP_CONCAT(IF(btr_is_bid_started = 1,
				btr_bid_start_time,0)) as bid,
				GROUP_CONCAT(if(btr_is_dbo_applicable=1,'ON','OFF')) as dbapply,
				GROUP_CONCAT(btr_dbo_amount)as dboAmt,
				GROUP_CONCAT(bkg_critical_score) as cs,
				GROUP_CONCAT(IF(bkg_manual_assignment=1,
				btr_manual_assign_date,'N/A')) as ma,
				GROUP_CONCAT(IF(bkg_critical_assignment=1,
				btr_critical_assign_date,'N/A')) as ca,
				GROUP_CONCAT(bkg_advance_amount)as baa,bcb_max_allowable_vendor_amount,
				GROUP_CONCAT(bkg_vendor_amount)as bva,
				bcb_vendor_amount,bkg_bcb_id,
				GROUP_CONCAT(IF(btr_is_dem_sup_misfire = 1,'ON','OFF')) as demsup_misfire,
				GROUP_CONCAT(IF(bkg_reconfirm_flag=1,'YES','NO')) as reconfirm,
				(SUM(bkg_gozo_amount)) as gozoAmount,
				temp.avgBid,
				temp.maxBid,
				temp.minBid,
				temp.bidCount, scv.scv_scc_id
				FROM booking_cab
				INNER JOIN booking ON booking_cab.bcb_id = booking.bkg_bcb_id
				INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
				INNER JOIN booking_pref ON booking_pref.bpr_bkg_id = booking.bkg_id
				INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
				INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id = booking.bkg_id
				INNER JOIN
				(
					SELECT bvr_bcb_id, AVG(bvr_bid_amount) as avgBid,MAX(bvr_bid_amount) AS maxBid,MIN(bvr_bid_amount) AS minBid,COUNT(bvr_vendor_id) as bidCount
					FROM `booking_vendor_request`
					WHERE 1 AND bvr_accepted = 1
					GROUP BY bvr_bcb_id
				) temp on temp.bvr_bcb_id= booking_cab.bcb_id
				LEFT JOIN agents ON agents.agt_id = booking.bkg_agent_id
				WHERE $where $whereDate AND btr_is_bid_started=1 GROUP BY bcb_id";

		if(!$command)
		{
			$sqlCount = "SELECT count(*)	FROM booking_cab
				INNER JOIN booking ON booking_cab.bcb_id = booking.bkg_bcb_id
				INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
				INNER JOIN booking_pref ON booking_pref.bpr_bkg_id = booking.bkg_id
				INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
				INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id = booking.bkg_id
				INNER JOIN
				(
					SELECT bvr_bcb_id, AVG(bvr_bid_amount) as avgBid,MAX(bvr_bid_amount) AS maxBid,MIN(bvr_bid_amount) AS minBid,COUNT(bvr_vendor_id) as bidCount
					FROM `booking_vendor_request`
					WHERE 1 AND bvr_accepted = 1
					GROUP BY bvr_bcb_id
				) temp on temp.bvr_bcb_id= booking_cab.bcb_id
				WHERE $where $whereDate AND btr_is_bid_started=1 GROUP BY bcb_id";

			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) a", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['bkg_critical_score', 'gozoAmount'],
					'defaultOrder'	 => ''], 'pagination'	 => ['pageSize' => 500],
			]);

			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
	}

	public function getpickupTo42WH($bkgID, $hr)
	{
		$sql = " SELECT SubWorkingMinutes($hr*60,bkg_pickup_date) as pickup42
          FROM booking WHERE bkg_id = $bkgID";
		return $res = DBUtil::command($sql)->queryScalar();
	}

	public function getVendorDueBookings($bookingIds)
	{
		$having = " HAVING
				(gozo < vnd AND booking_track.bkg_ride_complete = 1)
				OR (gozo > vnd)
				OR (booking.bkg_booking_type = 7)
				OR (		booking.bkg_agent_id =	10158
						OR	booking.bkg_agent_id =  9291
						OR	booking.bkg_agent_id =	8841
						OR	booking.bkg_agent_id =	9001
						OR	booking.bkg_agent_id =	450
						OR	booking.bkg_agent_id =	8482
					)";

		$sql		 = "SELECT
				`bkg_id`,bkg_booking_type,bkg_agent_id,
				(
					booking_invoice.bkg_gozo_amount + booking_invoice.bkg_partner_commission
				) AS gozo,
				(
					IF(((
						booking_invoice.bkg_advance_amount + booking_invoice.bkg_credits_used
					) - booking_invoice.bkg_refund_amount),((
						booking_invoice.bkg_advance_amount + booking_invoice.bkg_credits_used
					) - booking_invoice.bkg_refund_amount),0)
				) AS vnd,
				booking_track.bkg_ride_complete
			FROM
				`booking`
			INNER JOIN booking_track ON booking_track.btk_bkg_id = booking.bkg_id
			INNER JOIN booking_invoice ON booking.bkg_id = booking_invoice.biv_bkg_id
			WHERE
				booking.bkg_id IN($bookingIds)
		";
		$resultSet	 = DBUtil::queryAll($sql);
		return $resultSet;
	}

	public function getInventorySortage($model, $command = DBUtil::ReturnType_Provider)
	{
		$countdem_sup_misfireCount	 = 0;
		$cdate1						 = $model->bkg_create_date1;
		$cdate2						 = $model->bkg_create_date2;
		$pdate1						 = $model->bkg_pickup_date1;
		$pdate2						 = $model->bkg_pickup_date2;
		$IsCustomerCancel			 = implode(",", $model->bkg_cancel_id);
		if($IsCustomerCancel == "")
		{
			$IsCustomerCancel = "9,17";
		}

		if(($model->bkg_create_date1 != '' && $model->bkg_create_date1 != '1970-01-01') && ($model->bkg_create_date2 != '' && $model->bkg_create_date2 != '1970-01-01'))
		{
			$whereCreate = "AND bkg.bkg_create_date between '$cdate1 00:00:00' and '$cdate2 23:59:59'";
		}
		if(($model->bkg_pickup_date1 != '' && $model->bkg_pickup_date1 != '1970-01-01') && ($model->bkg_pickup_date2 != '' && $model->bkg_pickup_date2 != '1970-01-01'))
		{
			$wherePickup = "AND bkg.bkg_pickup_date between '$pdate1 00:00:00' and '$pdate2 23:59:59'";
		}
		if($model->dem_sup_misfireCount > 0)
		{
			$countdem_sup_misfireCount = $model->dem_sup_misfireCount;
		}
		if($model->total_completedCount)
		{
			$having = " AND complete>= $model->total_completedCount";
		}
		if($model->zero_percent)
		{
			$having = " AND percentage > 0";
		}


		$sql = " SELECT fzone.zon_name as fzoneName,tzone.zon_name as tzoneName,SUM(IF(btr_is_dem_sup_misfire=1,1,0)) as cntdemsup,
					SUM(IF(btr_nmi_flag=1,1,0)) as cntnmi,
					(SUM(IF(btr_is_dem_sup_misfire=1,1,0))+SUM(IF(btr_nmi_flag=1,1,0))+SUM(IF(bkg.bkg_cancel_id IN($IsCustomerCancel) AND bkg.bkg_status IN(9,10),1,0))) as tot,
					SUM(IF(bkg.bkg_status IN(6,7),1,0)) as complete,
					SUM(IF(bkg.bkg_cancel_id IN($IsCustomerCancel),1,0)) as cntreason,
					ROUND(((SUM(IF(bkg.bkg_cancel_id IN($IsCustomerCancel) AND bkg.bkg_status IN(9,10),1,0)))/SUM(IF(bkg.bkg_status IN(6,7),1,0)))*100 ,1)as percentage
					FROM booking bkg
					LEFT JOIN cancel_reasons ON cnr_id= bkg.bkg_cancel_id
					INNER JOIN booking_trail ON booking_trail.btr_bkg_id = bkg.bkg_id
					INNER JOIN zone_cities fzonecity ON fzonecity.zct_cty_id = bkg.bkg_from_city_id
					INNER JOIN zone_cities tzonecity ON tzonecity.zct_cty_id = bkg.bkg_to_city_id
					INNER JOIN zones fzone ON fzone.zon_id = fzonecity.zct_zon_id
					INNER JOIN zones tzone ON tzone.zon_id = tzonecity.zct_zon_id
					WHERE 1=1     $wherePickup $whereCreate  $where
					GROUP BY fzone.zon_id,tzone.zon_id HAVING tot>0 AND cntdemsup>=$countdem_sup_misfireCount  $having ";
		if($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 =>
				['attributes'	 => ['cntdemsup', 'cntnmi', 'cntreason', 'tot', 'complete', 'percentage'],
					'defaultOrder'	 => 'percentage DESC'
				],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
	}

	public function getNmiAppliedZone()
	{
		$returnSet = Yii::app()->cache->get('getNmiAppliedZone');
		if($returnSet === false)
		{
			$sql		 = "SELECT COUNT(DISTINCT irq_from_zone_id) as tot FROM inventory_request WHERE irq_status=1 LIMIT 0,1 ";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getNmiAppliedZone', $returnSet, 600);
		}
		return $returnSet;
	}

	public static function getFloatedBookingsToBid()
	{
		$sql = "SELECT bkg_id,bkg_from_city_id,bcb_start_time,bcb_end_time,btr_bid_floated_logged_id FROM booking
										INNER JOIN booking_trail ON btr_bkg_id = bkg_id AND btr_is_bid_started = 1
										AND  booking.bkg_pickup_date > NOW() AND bkg_active = 1
                                        INNER JOIN booking_cab ON bkg_bcb_id = bcb_id AND bcb_active = 1
				WHERE (bkg_status=2 OR (bkg_status IN (3,5) AND btr_bid_floated_logged_id = 0))";
		//	echo $sql.'<br><br>';
		return DBUtil::queryAll($sql);
	}

	public function quoteMeasure($cdate1, $cdate2, $qt)
	{
		if($qt == 1)
		{
			$where = " AND blg_event_id IN (131,130)";
		}
		else
		{
			$where = "  AND  bkg_status = 15 ";
		}
		$sql = "SELECT count(DISTINCT bkg_id) as total FROM booking INNER JOIN booking_log ON booking.bkg_id = booking_log.blg_booking_id
WHERE  1=1 $where
AND `bkg_create_date` BETWEEN '$cdate1 00:00:00' AND '$cdate2 23:59:59'";
		return $res = DBUtil::command($sql)->queryScalar();
	}

	public function quoteMeasureByRange($cdate1, $cdate2)
	{
		$sql = " SELECT SUM(IF(TIMESTAMPDIFF(HOUR,bkg_create_date,bkg_pickup_date)>=48,1,0)) bd_2,
SUM(IF(TIMESTAMPDIFF(HOUR,bkg_create_date,bkg_pickup_date)>= 24 AND   TIMESTAMPDIFF(HOUR,bkg_create_date,bkg_pickup_date)<= 48,1,0)) bd_1,
 SUM(IF(TIMESTAMPDIFF(HOUR,bkg_create_date,bkg_pickup_date)<24,1,0)) bd_0
FROM booking WHERE bkg_status = 15
AND `bkg_create_date` BETWEEN '$cdate1 00:00:00' AND '$cdate2 23:59:59'";
		return $res = DBUtil::queryRow($sql);
	}

	public function createMeasure($cdate1, $cdate2)
	{
		$sql = "SELECT COUNT(bkg_id) as totCreatedBooking ,
SUM(IF(bkg_platform=2,1,0)) as adminCreateBooking,
SUM(IF(bkg_platform=1,1,0)) as userCreateBooking,
SUM(IF(bkg_platform=3 ,1,0)) as userCreateBooking2,
SUM(IF(bkg_platform=4 AND bkg_agent_id = 450 ,1,0)) as mmtCreateBooking,
SUM(IF(bkg_platform=4 AND bkg_agent_id!= 450 ,1,0)) as othrtAgentCreateBooking,
SUM(IF(bkg_platform=5 ,1,0)) as spot
FROM booking INNER JOIN booking_trail ON bkg_id=btr_bkg_id WHERE bkg_create_date BETWEEN  '$cdate1 00:00:00' AND '$cdate2 23:59:59'";
		return $res = DBUtil::queryRow($sql);
	}

	public function createMeasureByRange($cdate1, $cdate2)
	{
		$sql = "SELECT
SUM(IF(TIMESTAMPDIFF(HOUR,bkg_create_date,bkg_pickup_date)>=48,1,0)) 48plus,
SUM(IF(TIMESTAMPDIFF(HOUR,bkg_create_date,bkg_pickup_date)>=24  AND   TIMESTAMPDIFF(HOUR,bkg_create_date,bkg_pickup_date)<= 48,1,0)) 24plus,
SUM(IF(TIMESTAMPDIFF(HOUR,bkg_create_date,bkg_pickup_date)<24,1,0)) 24minus
FROM booking
WHERE `bkg_create_date` BETWEEN '$cdate1 00:00:00' AND '$cdate2 23:59:59'";
		return $res = DBUtil::queryRow($sql);
	}

	public function cancelMeasure($cdate1, $cdate2)
	{
		$sql = "SELECT
	SUM(IF(bkg_cancel_user_type=4,1,0)) as gozoCanceltot,
	SUM(IF(bkg_cancel_user_type=4 AND TIMESTAMPDIFF(HOUR,btr_cancel_date,bkg_pickup_date)>=48,1,0)) as gozoCancel48plus,
	SUM(IF(bkg_cancel_user_type=4 AND TIMESTAMPDIFF(HOUR,btr_cancel_date,bkg_pickup_date)>=24 AND  TIMESTAMPDIFF(HOUR,btr_cancel_date,bkg_pickup_date)<= 48,1,0)) as gozoCancel24plus,
	SUM(IF(bkg_cancel_user_type=4 AND TIMESTAMPDIFF(HOUR,btr_cancel_date,bkg_pickup_date)<24,1,0)) as gozoCancel24less,
	SUM(IF(bkg_cancel_user_type=1,1,0)) as userCanceltot,
	SUM(IF(bkg_cancel_user_type=1 AND TIMESTAMPDIFF(HOUR,btr_cancel_date,bkg_pickup_date)>=48,1,0)) as userCancel48plus,
	SUM(IF(bkg_cancel_user_type=1 AND TIMESTAMPDIFF(HOUR,btr_cancel_date,bkg_pickup_date)>=24  AND  TIMESTAMPDIFF(HOUR,btr_cancel_date,bkg_pickup_date)<= 48,1,0)) as userCancel24plus,
	SUM(IF(bkg_cancel_user_type=1 AND TIMESTAMPDIFF(HOUR,btr_cancel_date,bkg_pickup_date)<24,1,0)) as userCancel24less,

	SUM(IF(bkg_cancel_user_type=5,1,0)) as partnerCanceltot,
	SUM(IF(bkg_cancel_user_type=5 AND TIMESTAMPDIFF(HOUR,btr_cancel_date,bkg_pickup_date)>=48,1,0)) as partnerCancel48plus,
	SUM(IF(bkg_cancel_user_type=5 AND TIMESTAMPDIFF(HOUR,btr_cancel_date,bkg_pickup_date)>=24  AND  TIMESTAMPDIFF(HOUR,btr_cancel_date,bkg_pickup_date)<= 48,1,0)) as partnerCancel24plus,
	SUM(IF(bkg_cancel_user_type=5 AND TIMESTAMPDIFF(HOUR,btr_cancel_date,bkg_pickup_date)<24,1,0)) as partnerCancel24less

	FROM booking
	INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
	WHERE bkg_create_date BETWEEN '$cdate1 00:00:00' AND '$cdate2 23:59:59'";
		return $res = DBUtil::queryRow($sql);
	}

	public function countAssignmentByType($cdate1, $cdate2)
	{
		$sql = "  SELECT
  round1,(totMinround1/round1)as round1avg,
  round2,(totMinround2/round2)as round2avg,
  round3,(totMinround3/round3)as round3avg,
  autoAssign,(totMinautoAssign/autoAssign)as autoAssignavg,
  manualAssign,(totMinManualAssign/manualAssign)as manualAssignavg
    from(
    SELECT
  SUM(IF(( bkg_critical_assignment = 1 AND bkg_manual_assignment =1 AND btr_is_bid_started=1
  AND bkg_assign_mode=1) , TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_assigned_at) , 0 )) as totMinround1,

 SUM(IF(( bkg_critical_assignment = 1 AND bkg_manual_assignment =1 AND btr_is_bid_started=1
  AND bkg_assign_mode=1) , 1 , 0 )) AS round1,

  SUM(IF(( bkg_critical_assignment = 0 AND bkg_manual_assignment =1 AND
  btr_is_bid_started=1  AND bkg_assign_mode=1) ,
  TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_assigned_at) , 0 )) AS totMinround2,

  SUM(IF(( bkg_critical_assignment = 0 AND bkg_manual_assignment =1 AND btr_is_bid_started=1
  AND bkg_assign_mode=1) , 1 , 0 )) AS round2,

  	SUM(IF(( bkg_critical_assignment = 0 AND bkg_manual_assignment = 0
    AND btr_is_bid_started = 1 AND bkg_assign_mode=1) , TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_assigned_at) , 0 )) AS totMinround3,

    	SUM(IF(( bkg_critical_assignment = 0 AND bkg_manual_assignment = 0
      AND btr_is_bid_started = 1 AND bkg_assign_mode=1) , 1 , 0 )) AS round3,

      SUM(IF(bkg_assign_mode=1,TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_assigned_at),0)) as totMinautoAssign,
      SUM(IF(bkg_assign_mode=1,1,0)) as autoAssign,


      SUM(IF(bkg_assign_mode=0,TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_assigned_at),0)) as totMinManualAssign,
      SUM(IF(bkg_assign_mode=0,1,0)) as manualAssign

	FROM booking
	INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
	INNER JOIN booking_pref ON booking_pref.bpr_bkg_id = booking.bkg_id
	WHERE bkg_assign_mode IS NOT NULL  AND  btr_is_bid_started=1
	AND DATE(`bkg_pickup_date`) BETWEEN '$cdate1 00:00:00' AND '$cdate2 23:59:59'
	AND bkg_critical_score >0) a;";
		return $res = DBUtil::queryRow($sql);
	}

	public function getEscalationlist($teams, $command = DBUtil::ReturnType_Provider)
	{
		if($teams != null)
		{
			$var = explode(',', $teams);
			foreach($var as $key => $value)
			{
				$condition .= " FIND_IN_SET('$value',btr_escalation_assigned_team) " . 'OR';
			}
			$condition = " AND ( " . rtrim($condition, 'OR') . " ) ";
		}
		else
		{
			$condition = " AND btr_escalation_assigned_team IS NOT NULL AND btr_escalation_assigned_team<> '' ";
		}
		$sql		 = "SELECT
						bkg_id,
						bkg_booking_id,
						bkg_pickup_date,
						bkg_status,
						btr_escalation_level,
						btr_escalation_assigned_lead,
						btr_escalation_assigned_team,
						booking_log.blg_user_id AS escaltion_usr_id,
						btr_escalation_fdate,
						btr_escalation_ldate,
						DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE) AS trip_completion_time
						FROM booking
						INNER JOIN `booking_trail` ON booking.bkg_id = booking_trail.btr_bkg_id AND booking_trail.bkg_escalation_status = 1
						INNER JOIN booking_log ON booking.bkg_id = booking_log.blg_booking_id AND booking_log.blg_event_id = 37
						WHERE  booking.bkg_status IN (2, 3, 5, 6, 7, 9) $condition GROUP BY booking_log.blg_booking_id";
		$sqlCount	 = "SELECT
						COUNT(DISTINCT booking.bkg_id) as bkg_id
						FROM booking
						INNER JOIN `booking_trail` ON booking.bkg_id = booking_trail.btr_bkg_id AND booking_trail.bkg_escalation_status = 1
						INNER JOIN booking_log ON booking.bkg_id = booking_log.blg_booking_id AND booking_log.blg_event_id = 37
						WHERE  booking.bkg_status IN(2, 3, 5, 6, 7, 9) $condition  ";

		if($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes' => [], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
	}

	public static function getEscalations($searchId = '')
	{
		$searchId	 = trim($searchId);
		$params		 = [':searchId' => $searchId];
		$where		 = "";
		if($searchId != '')
		{
			$where .= "AND (bkg.bkg_booking_id LIKE CONCAT('%', :searchId, '%')) OR (bkg.bkg_id  LIKE CONCAT('%', :searchId, '%'))";
		}
		$where .= " AND btr.btr_escalation_assigned_team IS NOT NULL AND btr.btr_escalation_assigned_team <> '' ";

		$sql = "SELECT
							bkg.bkg_id,
							bkg.bkg_booking_id,
							bkg.bkg_pickup_date,
							bkg.bkg_create_date,
							bkg.bkg_status,
							bkg.bkg_booking_type,
							bkg.bkg_trip_distance,
                                                        bkg.bkg_trip_duration,
							btr.btr_escalate_info_all,
							btr.btr_escalation_assigned_lead,
							btr.btr_escalation_level,
					                btr.bkg_escalation_status,
							btr.btr_escalation_assigned_team,
							btr.btr_escalation_assigned_team AS tea_id,
							btr.btr_escalation_fdate,
							btr.btr_escalation_ldate,
							DATE_ADD(bkg.bkg_pickup_date,INTERVAL bkg.bkg_trip_duration MINUTE) AS completion_time
							FROM booking bkg
							INNER JOIN `booking_trail` btr ON bkg.bkg_id = btr.btr_bkg_id AND btr.bkg_escalation_status = 1
							WHERE  bkg.bkg_status IN (2, 3, 5, 6, 7, 9)  $where ORDER BY CASE WHEN bkg.bkg_pickup_date IS NOT NULL THEN 0 ELSE 1 END,
                            bkg.bkg_pickup_date DESC, btr.btr_escalation_level DESC";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	public static function getRelatedQuoteIds($leadId)
	{
		$sql = "SELECT GROUP_CONCAT(DISTINCT u.bkg_id) as leadIds
				FROM booking t,
					booking_user tbu, booking_trail tbtr,
					booking u, booking_user rbu, booking_trail rbtr
				WHERE
					t.bkg_id = tbtr.btr_bkg_id AND t.bkg_id = tbu.bui_bkg_id AND t.bkg_status IN (1,15) AND
					u.bkg_id = rbtr.btr_bkg_id AND u.bkg_id = rbu.bui_bkg_id AND u.bkg_status IN (1,15) AND
					t.bkg_id=:leadId AND t.bkg_id<>u.bkg_id AND ((abs(TIMESTAMPDIFF(MINUTE, t.bkg_create_date, u.bkg_create_date))<240 AND
					(((rbu.bkg_user_email <> '' AND rbu.bkg_user_email = tbu.bkg_user_email) OR
					 (rbu.bkg_contact_no <> '' AND rbu.bkg_contact_no = tbu.bkg_contact_no)))) OR
					 (abs(TIMESTAMPDIFF(MINUTE, t.bkg_create_date, u.bkg_create_date))<30 AND rbtr.bkg_user_ip = tbtr.bkg_user_ip AND trim(rbtr.bkg_user_ip) <> '')) AND rbtr.bkg_create_user_type<>4";
		return DBUtil::command($sql)->queryScalar(['leadId' => $leadId]);
	}

	public static function assignRelatedIds($leadId, $csr)
	{
		$success = false;
		$leadIds = self::getRelatedQuoteIds($leadId);
		if(!$leadIds)
		{
			goto end;
		}

		$sql	 = "UPDATE booking, booking_trail SET bkg_assign_csr=:csr WHERE bkg_id=btr_bkg_id AND bkg_status IN (1,15) AND bkg_id IN ($leadIds) AND bkg_agent_id IS NULL";
		$numrows = DBUtil::command($sql)->execute(['csr' => $csr]);
		if($numrows == 0)
		{
			goto end;
		}

		$arrLead = explode(",", $leadIds);
		foreach($arrLead as $lead)
		{
			$aname	 = Admins::model()->findByPk($csr)->getName();
			$desc	 = "Related Quote assigned to $aname (Source Quote: $leadId)";
			BookingLog::model()->createLog($lead, $desc, UserInfo::model(), BookingLog::CSR_ASSIGN, false, false);
		}
		$success = true;
		end:
		return $success;
	}

	public function countEscalations($cdate1, $cdate2)
	{
		$sql = "SELECT SUM(IF((bkg_escalation_status=0 AND btr_escalation_level=2),1,0)) as removed ,
	SUM(IF((bkg_escalation_status=1 AND btr_escalation_level!=0),1,0)) as created ,
	SUM(IF((bkg_escalation_status=1 AND btr_escalation_level!=2),1,0)) as active
	FROM booking INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
	WHERE 1=1 AND DATE(`bkg_pickup_date`) BETWEEN '$cdate1 00:00:00' AND '$cdate2 23:59:59'";
		return DBUtil::queryRow($sql);
	}

	public function countAccountingFlag($cdate1, $cdate2)
	{
		$sql = "SELECT SUM(IF(booking_pref.bkg_account_flag=0 AND booking_log.blg_event_id=66,1,0)) as solvedCount ,
    SUM(IF(booking_pref.bkg_account_flag=1 AND booking_log.blg_event_id=65,1,0)) as pendingCount
    FROM booking
    INNER JOIN booking_pref ON booking_pref.bpr_bkg_id = booking.bkg_id
    INNER JOIN booking_log ON booking.bkg_id= booking_log.blg_booking_id
    WHERE booking.bkg_pickup_date BETWEEN '$cdate1 00:00:00' AND '$cdate2 23:59:59'";
		return DBUtil::queryRow($sql);
	}

	public function countPickupCancel($cdate1, $cdate2)
	{
		$sql = "SELECT totCancel, ROUND((totCancelbyGozo*100)/totCancel) AS gozoCancel,
ROUND((totCancelbyCustomer*100)/totCancel) AS customerCancel FROM(
SELECT SUM(IF(bkg_cancel_id>0,1,0)) as totCancel,SUM(IF(bkg_cancel_id=9 OR bkg_cancel_id=17,1,0)) as totCancelbyGozo ,
SUM(IF(bkg_cancel_id=1 OR bkg_cancel_id=2 OR bkg_cancel_id=3 OR bkg_cancel_id=4 OR bkg_cancel_id=5 OR bkg_cancel_id=6 OR bkg_cancel_id=7 OR
bkg_cancel_id=10 OR bkg_cancel_id=11 OR bkg_cancel_id=12 OR bkg_cancel_id=13 OR bkg_cancel_id=14 OR bkg_cancel_id=15 OR bkg_cancel_id=25 OR
bkg_cancel_id=31 OR bkg_cancel_id=32 OR bkg_cancel_id=24,1,0)) as totCancelbyCustomer
FROM booking
WHERE bkg_status =9 AND bkg_cancel_id>0 AND
booking.bkg_pickup_date BETWEEN '$cdate1 00:00:00' AND '$cdate2 23:59:59') a;";
		return DBUtil::queryRow($sql);
	}

	public function counttotPickup($cdate1, $cdate2)
	{
		$sql = "SELECT totalbooking,ROUND((totpickupdone*100)/totalbooking) AS pickup,
ROUND((totpickupcompleted*100)/totalbooking) AS completed FROM(
SELECT count(bkg_id) as totalbooking ,SUM(IF(booking_trail.btr_drv_score>=70,1,0)) as totpickupdone,
SUM(IF(booking_trail.btr_drv_score>=120,1,0)) as totpickupcompleted
FROM booking
INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
WHERE bkg_pickup_date < NOW() AND bkg_pickup_date
BETWEEN '$cdate1 00:00:00' AND '$cdate2 23:59:59' AND bkg_status IN(5,6,7)) a";
		return DBUtil::queryRow($sql);
	}

	public function getAdmBkgDetails($tripId, $status = NULL, $vendorId = 0)
	{

		$uberAgentId = Yii::app()->params['uberAgentId'];
		$qry		 = "SELECT DISTINCT
			bkg.bkg_id,
			IF(bkg_flexxi_type=1,true,false) isPromoter,
			IF(bkg_flexxi_type IN(1,2),true,false) isFlexxi,
			bcb.bcb_id,
			bkg.bkg_return_date,
			bkg.bkg_booking_id,
			bkg.bkg_modified_on,
			bkg.bkg_pickup_address,
			bkg.bkg_drop_address,
			bkg.bkg_create_date,
			bkg.bkg_reconfirm_flag,
			bkg.bkg_agent_id,
			CONCAT(bkguser.bkg_user_fname,' ', bkguser.bkg_user_lname) AS bkg_user_name,
			bkg.bkg_trip_distance,
			vht.vht_model AS bkg_cab_assigned,
			bkg.bkg_status,
			bkgaddinfo.bkg_no_person,
			bkguser.bkg_user_lname,
			bkg.bkg_pickup_date,
			bkguser.bkg_country_code,
			bkginv.bkg_night_pickup_included,
			bkginv.bkg_night_drop_included,
			bkginv.bkg_rate_per_km_extra,
						bkginv.bkg_extra_km_charge,
						bkginv.bkg_extra_km,
			bkginv.bkg_toll_tax,
			bkginv.bkg_state_tax,
			bkginv.bkg_extra_toll_tax,
			bkginv.bkg_extra_state_tax,
			bkginv.bkg_parking_charge,
			IF(bkginv.bkg_additional_charge > 0,bkginv.bkg_additional_charge,0) AS bkg_additional_charge,
			IF(bkginv.bkg_convenience_charge > 0, bkginv.bkg_convenience_charge, 0) AS bkg_convenience_charge,
			bkginv.bkg_driver_allowance_amount,
			bkginv.bkg_base_amount,
			bkginv.bkg_service_tax,
			bkginv.bkg_discount_amount,
			IF(bkginv.bkg_refund_amount > 0,bkginv.bkg_refund_amount,0) AS bkg_refund_amount,
			IF(bkginv.bkg_corporate_credit > 0,bkginv.bkg_corporate_credit,0) AS bkg_corporate_credit,
			bkginv.bkg_quoted_vendor_amount AS quoted_vendor_amount,
            phn.phn_phone_no AS bkg_driver_number,
			agt.vnd_name AS bkg_vendor_name,
			drv.drv_name AS bkg_driver_name,
			vhc.vhc_number AS bkg_cab_number,
			bkg.bkg_trip_duration,
			IF(bkg.bkg_pickup_date <= DATE_ADD(NOW(), INTERVAL 240 MINUTE), bkguser.bkg_contact_no,'') AS bkg_contact_no,
			bkguser.bkg_alt_contact_no as bkg_alternate_contact,
			bkguser.bkg_user_email,
			bkgtrack.bkg_ride_start,
			bkgtrack.bkg_ride_complete,
			bkgtrack.bkg_is_trip_verified,
			bkgtrail.btr_is_dbo_applicable,
			bkgtrail.btr_is_dem_sup_misfire,
			bkgtrail.bkg_escalation_status,
			IF(bkgtrack.bkg_ride_start > 0 AND DATE_ADD(bkg.bkg_pickup_date , INTERVAL (bkg.bkg_trip_duration/2) MINUTE) < NOW() ,bkgtrack.bkg_ride_complete,1) as bkg_ride_complete_old,
			bkginv.bkg_total_amount,
			bkginv.bkg_advance_amount,
			bkginv.bkg_service_tax,
			bkginv.bkg_service_tax_rate,
			bkginv.bkg_vendor_collected,
			bkginv.bkg_vendor_amount,
			bkginv.bkg_partner_commission,
			bkgtrail.bkg_non_profit_flag,
			(bkginv.bkg_vendor_amount - bkginv.bkg_vendor_collected) AS vendor_receives,
			vct.vct_desc AS bkg_cab_type,
			bkg.bkg_booking_type,
			bkg.bkg_instruction_to_driver_vendor,
			vhc.vhc_id,
			drv.drv_id,
			bkginv.bkg_is_toll_tax_included,
			DATE_ADD( brt1.brt_pickup_datetime, INTERVAL brt1.brt_trip_duration MINUTE ) AS trip_completion_time,
			bkginv.bkg_is_state_tax_included,
			bkg.bkg_reconfirm_flag as bkg_reconfirm_id,
			bkgtrack.bkg_is_no_show,
			IF(bpr.bkg_critical_score <> NULL,bpr.bkg_critical_score,0) as bkg_critical_score,
			bpr.bpr_vnd_recmnd,
			bpr.bkg_critical_assignment,
			bpr.bkg_duty_slip_required is_duty_slip_required, bpr.bkg_cng_allowed AS is_cng_allowed ,bpr.bkg_driver_app_required,
			vct.vct_label cab_model,
			vehicleCat.vct_label cab_model_assigned,
            '0' AS show_total_amount,
            rtg.rtg_vendor_customer,
            rtg.rtg_vendor_csr,
            rtg.rtg_vendor_review,
            brt1.brt_pickup_datetime,
            brt1.brt_trip_duration,
            IF(bkg.bkg_agent_id > 0, 1, 0) AS is_agent,
			IF( vct.vct_id IN(5, 6), '1', '0' ) AS is_assured,
			if(bkgtrack.bkg_is_trip_verified=1,2,bpr.bkg_trip_otp_required) bpr_trip_otp_required,
			IF(DATE_ADD(NOW(), INTERVAL 13 HOUR) >= bkg.bkg_pickup_date AND bkg.bkg_reconfirm_flag=1,0,1) AS is_biddable,
			bvr.bvr_id,bkg.bkg_booking_type,
			bvr.bvr_bid_amount,
			bcb.bcb_vendor_amount AS trip_vendor_ammount,
			bcb.bcb_assign_mode,
			IFNULL(bcb.bcb_vendor_id,0) AS vnd_id
			FROM
                `booking` bkg
			LEFT JOIN booking_cab bcb ON
			bkg.bkg_bcb_id = bcb.bcb_id
         	LEFT JOIN booking_vendor_request bvr ON bvr.bvr_bcb_id = bcb.bcb_id AND bvr_vendor_id = $vendorId
            INNER JOIN booking_user bkguser ON
                bkg.bkg_id = bkguser.bui_bkg_id
            INNER JOIN booking_add_info bkgaddinfo ON
                bkg.bkg_id = bkgaddinfo.bad_bkg_id
            INNER JOIN booking_invoice bkginv ON
                bkg.bkg_id = bkginv.biv_bkg_id
            INNER JOIN booking_track bkgtrack ON
                bkg.bkg_id = bkgtrack.btk_bkg_id
            INNER JOIN booking_trail bkgtrail ON
                bkg.bkg_id = bkgtrail.btr_bkg_id
			INNER JOIN booking_pref bpr ON
	        bkg.bkg_id = bpr.bpr_bkg_id
            INNER JOIN booking_route brt1 ON  brt1.brt_bkg_id = bkg.bkg_id AND brt1.brt_active = 1
            INNER JOIN cities ct1 ON ct1.cty_id = brt1.brt_from_city_id
            INNER JOIN cities ct2 ON ct2.cty_id = brt1.brt_to_city_id
            INNER JOIN(
                SELECT
                    MAX(brt2.brt_pickup_datetime) AS MAX,
                    brt2.brt_bkg_id
                FROM booking_route brt2
                WHERE brt2.brt_active = 1
                GROUP BY brt2.brt_bkg_id
            ) a
            ON
                brt1.brt_pickup_datetime = MAX AND bkg.bkg_id = a.brt_bkg_id
            LEFT JOIN vendors agt ON
                agt.vnd_id = bcb.bcb_vendor_id
            LEFT JOIN `vehicles` vhc ON
                vhc.vhc_id = bcb.bcb_cab_id
            LEFT JOIN `vehicle_types` vht ON vht.vht_id = vhc.vhc_type_id
			LEFT JOIN vcv_cat_vhc_type vcv ON vcv.vcv_id = vht.vht_id
            LEFT JOIN vehicle_category vehicleCat ON vehicleCat.vct_id = vcv.vcv_vct_id
						LEFT JOIN `drivers` drv ON drv.drv_id = bcb.bcb_driver_id AND drv.drv_id = drv.drv_ref_code
						LEFT JOIN contact_profile AS cp on cp.cr_is_driver = drv.drv_id AND cp.cr_status =1
						LEFT JOIN contact ctt ON ctt.ctt_id = cp.cr_contact_id and ctt.ctt_id =ctt.ctt_ref_code AND ctt.ctt_active =1
			LEFT JOIN contact_phone phn ON phn.phn_contact_id=ctt.ctt_id AND phn.phn_is_primary=1 AND phn.phn_active=1
           LEFT JOIN svc_class_vhc_cat scv ON bkg.bkg_vehicle_type_id = scv.scv_id
            LEFT JOIN `vehicle_category` vct ON vct.vct_id = scv.scv_vct_id
            LEFT JOIN `ratings` rtg ON
                bkg.bkg_id = rtg.rtg_booking_id
			LEFT JOIN `agents` agts ON
                bkg.bkg_agent_id = agts.agt_id
			WHERE
              bkg.bkg_id = $tripId ";

		$recordset = DBUtil::queryAll($qry);
		foreach($recordset as $key => $val)
		{
			if($val['agent_name'] == 'UBER')
			{
				$recordset[$key]['bkg_pickup_date'] = BookingCab::model()->getPickupDateTime("Y-m-d H:i:s", $recordset[$key]['bkg_pickup_date'], $uberAgentId);
			}
			if($val['bkg_id'] > 0)
			{
				$recordset[$key]['bkg_instruction_to_driver_vendor'] = Booking::model()->getFullInstructionsByid($val['bkg_id']);
			}
			$agentModel = Agents::model()->findByPk($val['bkg_agent_id']);
//
			if($val['is_cng_allowed'] > 0)
			{
				$recordset[$key]['is_cng_allowed'] = $val['is_cng_allowed'];
			}
			if($val['bkg_driver_app_required'] > 0)
			{
				$recordset[$key]['bkg_driver_app_required'] = (int) $val['bkg_driver_app_required'];
			}
			$recordset[$key]['bkg_driver_allowance_amount']	 = (int) $val['bkg_driver_allowance_amount'];
			$recordset[$key]['bkg_parking_charge']			 = (int) $val['bkg_parking_charge'];
			$recordset[$key]['bpr_trip_otp_required']		 = (int) $val['bpr_trip_otp_required'];
			$recordset[$key]['bkg_route_name']				 = BookingRoute::model()->getRouteName($val['bkg_id']);
			$recordset[$key]['checkAccess']					 = Yii::app()->user->checkAccess('ConfidentialBookingDetails');
			$model											 = Booking::model()->findByPk($val['bkg_id']);
			$gozoAmount										 = ($model->bkgInvoice->bkg_gozo_amount != '') ? $model->bkgInvoice->bkg_gozo_amount : $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->bkg_vendor_amount;
			$recordset[$key]['bkg_critical_score']			 = (float) round($model->bkgPref->bkg_critical_score, 2);
			$recordset[$key]['bkg_followup_active']			 = (int) $model->bkgTrail->bkg_followup_active;
			$recordset[$key]['gozoReceives']				 = ($gozoAmount - $model->bkgInvoice->getAdvanceReceived());
			$recordset[$key]['bkg_gozo_amount']				 = $gozoAmount;
			$recordset[$key]['amt_exc_tax']					 = $model->bkgInvoice->calculateGrossAmount();
			$recordset[$key]['bkg_due_amount']				 = ($model->bkgInvoice->bkg_due_amount != '') ? $model->bkgInvoice->bkg_due_amount : $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->getTotalPayment();
			$recordset[$key]['profit']						 = ($model->bkgTrail->bkg_non_profit_flag == 1) ? (($model->bkgInvoice->bkg_gozo_amount) * -1) : $model->bkgInvoice->bkg_gozo_amount;
			$recordset[$key]['infosource']					 = $model->bkgAddInfo->bkg_info_source;
			$recordset[$key]['needSupply']					 = (int) InventoryRequest::model()->checkInventoryByFromCity($model->bkg_from_city_id);
			$recordset[$key]['agt_type']					 = $agentModel->agt_type;
			$recordset[$key]['partnerName']					 = null;
			$recordset[$key]['refferalBkgID']				 = null;
			if($val['bkg_agent_id'] > 0)
			{
				if($agentModel->agt_type == 1)
				{
					$recordset[$key]['partnerName'] = "CORPORATE (" . $agentModel->agt_company . ")";
				}
				else
				{
					$recordset[$key]['partnerName'] = "PARTNER (" . $agentModel->agt_company . "-" . (($agentModel->agt_owner_name != '') ? $agentModel->agt_owner_name : ($agentModel->agt_fname . " " . $agentModel->agt_lname)) . ")";
				}
				$recordset[$key]['refferalBkgID'] = ($model->bkg_agent_ref_code != null) ? $model->bkg_agent_ref_code : "";
			}
			if($val['vnd_id'] > 0)
			{
				$vModel								 = Vendors::model()->findByPk($val['vnd_id']);
				$recordset[$key]['vnd_name']		 = $vModel->vnd_name;
				$recordset[$key]['vnd_code']		 = $vModel->vnd_code;
				$recordset[$key]['vnd_is_freeze']	 = $vModel->vendorPrefs->vnp_is_freeze;
			}
			if($val['drv_id'] > 0)
			{
				$dModel							 = Drivers::getDetailsById($val['drv_id']);
				$recordset[$key]['driver_name']	 = $dModel['drv_name'];
				$recordset[$key]['driver_phone'] = $dModel['drv_phone'];
				$recordset[$key]['drv_status']	 = $dModel['approve_status'];
			}
			if($val['vhc_id'] > 0)
			{
				$vhcModel							 = Vehicles::model()->findByPk($val['vhc_id']);
				$recordset[$key]['vehicle_model']	 = $vhcModel->vhcType->vht_make . ' ' . $vhcModel->vhcType->vht_model;
				$recordset[$key]['vehicle_number']	 = $vhcModel->vhc_number;
				$recordset[$key]['vehicle_status']	 = $vhcModel->vhc_approved;
			}
		}
		return $recordset;
	}

	public static function getRelatedShuttleBookings($leadId)
	{
		$sql = "SELECT GROUP_CONCAT(DISTINCT u.bkg_id) as bkgIds
				FROM booking t,
					booking_user tbu, booking_trail tbtr,
					booking u, booking_user rbu, booking_trail rbtr
				WHERE
					t.bkg_id = tbtr.btr_bkg_id AND t.bkg_id = tbu.bui_bkg_id AND t.bkg_status IN (1,15) AND
					u.bkg_id = rbtr.btr_bkg_id AND u.bkg_id = rbu.bui_bkg_id AND u.bkg_status IN (1,15) AND
					t.bkg_id=:leadId AND t.bkg_id<>u.bkg_id AND ((abs(TIMESTAMPDIFF(MINUTE, t.bkg_create_date, u.bkg_create_date))<240 AND
					(((rbu.bkg_user_email <> '' AND rbu.bkg_user_email = tbu.bkg_user_email) OR
					 (rbu.bkg_contact_no <> '' AND rbu.bkg_contact_no = tbu.bkg_contact_no)))) OR
					 (abs(TIMESTAMPDIFF(MINUTE, t.bkg_create_date, u.bkg_create_date))<30
					AND rbtr.bkg_user_ip = tbtr.bkg_user_ip AND trim(rbtr.bkg_user_ip) <> ''))
				AND rbtr.bkg_create_user_type<>4
				AND u.bkg_shuttle_id = t.bkg_shuttle_id";
		return DBUtil::command($sql)->queryScalar(['leadId' => $leadId]);
	}

	public static function cancellingRelatedQuotedShuttle($BkgId)
	{
		$success = false;
		$BkgIds	 = self::getRelatedShuttleBookings($BkgId);
		if(!$BkgIds)
		{
			goto end;
		}
		$cancelStatus	 = Booking::STATUS_VERIFY_CANCELLED;
		$sql			 = "UPDATE booking  SET bkg_status =$cancelStatus WHERE   bkg_status IN (1,15) AND bkg_id IN ($BkgIds) AND bkg_agent_id IS NULL";
		$numrows		 = DBUtil::command($sql)->execute();
		if($numrows == 0)
		{
			goto end;
		}

		$arrBkgIds = explode(",", $BkgIds);
		foreach($arrBkgIds as $bkg)
		{

			$desc = "Related unverified / quoted shuttle booking is cancelled (Source Quote: $BkgId)";
			BookingLog::model()->createLog($bkg, $desc, UserInfo::model(), BookingLog::BOOKING_CANCELLED, false, false);
		}
		$success = true;
		end:
		return $success;
	}

	public function getAssignedtoOM()
	{
		$count = BookingSub::model()->getDelegatedBkgIds();
		if($count['cnt'] > 0)
		{
			$sql		 = 'SELECT `apt_user_id` FROM `app_tokens` WHERE `apt_user_type`= 6 AND `apt_status` = 1 AND `apt_device_token` IS NOT NULL';
			$recordset	 = DBUtil::queryAll($sql);
			foreach($recordset as $value)
			{
				$notificationId	 = substr(round(microtime(true) * 1000), -5);
				$payLoadData	 = ['EventCode' => Booking::CODE_DELEGATED_OM];
				$result			 = AppTokens::model()->notifyAdmin($value['apt_user_id'], $payLoadData, $notificationId, $count['bookingIDs'] . " bookings are delegated to Operation Manager", $count['cnt'] . " Booking Delegated to OM ");
			}
		}
	}

	public function getDelegatedBkgIds()
	{
		$sql = 'SELECT
				COUNT(bkg_id) AS cnt,
				GROUP_CONCAT(bkg_booking_id) AS bookingIDs
				FROM
				`booking`
				INNER JOIN `booking_pref` ON booking.bkg_id = booking_pref.bpr_bkg_id
				WHERE
				booking_pref.bpr_assignment_level IN(2, 3) AND bkg_status = 2';
		return DBUtil::queryRow($sql);
	}

	public function getSourceZoneTodaysBooking()
	{
		$sql			 = "SELECT zones.zon_name zonName , COUNT(bkg_id) cntBkg1
				FROM
					`booking`
				JOIN `zone_cities` ON zone_cities.zct_cty_id = booking.bkg_from_city_id
				JOIN `zones` ON zones.zon_id = zone_cities.zct_zon_id
				WHERE
					booking.bkg_status IN(2, 3, 5, 6, 7) AND (booking.bkg_create_date between  CONCAT(CURDATE(),' 00:00:00') and CONCAT(CURDATE(),' 23:59:59'))  AND booking.bkg_active = 1
				group BY zones.zon_id";
		$data			 = DBUtil::queryRow("select count(*) as cnt,sum(cntBkg1) as bkgcount,NOW() as lastRefeshDate from ($sql)abc");
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $data['cnt'],
			'db'			 => DBUtil::SDB(),
			'pagination'	 => false,
			'sort'			 => ['attributes'	 => ['cntBkg1'],
				'defaultOrder'	 => 'cntBkg1 DESC'],
		]);
		$arr			 = array();
		$arr[0]			 = $dataprovider;
		$arr[1]			 = $data['bkgcount'];
		$arr[2]			 = $data['lastRefeshDate'];
		return $arr;
	}

	public function getDestZoneTodaysBooking()
	{
		$sql			 = "SELECT zones.zon_name zonName , COUNT(bkg_id) cntBkg2
				FROM
					`booking`
				JOIN `zone_cities` ON zone_cities.zct_cty_id = booking.bkg_to_city_id
				JOIN `zones` ON zones.zon_id = zone_cities.zct_zon_id
				WHERE
					booking.bkg_status IN(2, 3, 5, 6, 7) AND (booking.bkg_create_date BETWEEN CONCAT(CURDATE(),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59')) AND booking.bkg_active = 1
				group BY zones.zon_id";
		$data			 = DBUtil::queryRow("select count(*) as cnt,sum(cntBkg2) as bkgcount from ($sql)abc");
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $data['cnt'],
			'db'			 => DBUtil::SDB(),
			'pagination'	 => false,
			'sort'			 => ['attributes'	 => ['cntBkg2'],
				'defaultOrder'	 => 'cntBkg2 DESC'],
		]);
		$arr			 = array();
		$arr[0]			 = $dataprovider;
		$arr[1]			 = $data['bkgcount'];
		return $arr;
	}

	public function getCarCategoryTodaysBooking()
	{
		$where = "";
		if($this->from_date != null && $this->to_date != null)
		{
			$where = " AND (booking.bkg_create_date BETWEEN '$this->from_date' AND '$this->to_date') ";
		}
		else
		{
			$where = " AND (booking.bkg_create_date  BETWEEN CONCAT(CURDATE(),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59')) ";
		}
		$sql = "SELECT
				SUM(IF((bkg_agent_id = 450),1,0)) as total_mmtb2b,
				SUM(IF((bkg_agent_id = 18190),1,0)) as total_ibibob2b,
				SUM(IF((bkg_agent_id NOT IN (450,18190) AND bkg_agent_id IS NOT NULL),1,0)) as total_b2bothers,
                SUM(IF((bkg_agent_id IS NULL),1,0)) as total_b2c,
				vct_label catName,
				COUNT(bkg_id) cntCarCat,
				service_class.scc_label as  tierName
				FROM
				`booking`
				JOIN `svc_class_vhc_cat` ON  svc_class_vhc_cat.scv_id = booking.bkg_vehicle_type_id
				JOIN `service_class` ON  service_class.scc_id = svc_class_vhc_cat.scv_scc_id
				JOIN `vehicle_category` ON  vehicle_category.vct_id = svc_class_vhc_cat.scv_vct_id
				WHERE
				booking.bkg_status IN(2, 3, 5, 6, 7) $where  AND booking.bkg_active = 1
				GROUP BY svc_class_vhc_cat.scv_id";
		$arr = DBUtil::query($sql, DBUtil::SDB3());
		return $arr;
	}

	public function getServiceTierTodaysBooking()
	{
		$where = "";
		if($this->from_date != null && $this->to_date != null)
		{
			$where = " AND (booking.bkg_create_date BETWEEN '$this->from_date' AND '$this->to_date') ";
		}
		else
		{
			$where = ' AND (booking.bkg_create_date BETWEEN CONCAT(CURDATE()," 00:00:00") AND CONCAT(CURDATE()," 23:59:59"))';
		}
		$sql = "SELECT
				service_class.scc_label tierName,
				COUNT(bkg_id) cntServiceTier,
				SUM(IF(bkg_reconfirm_flag = 1, (bkg_net_base_amount), 0)) AS booking_amount,
				SUM(IF(bkg_reconfirm_flag = 1, bkg_gozo_amount- IFNULL(bkg_credits_used,0), 0)) AS gozo_amount,
				SUM(IF(bkg_reconfirm_flag=1, bkg_total_amount-bkg_quoted_vendor_amount-IFNULL(bkg_credits_used,0)-IFNULL(bkg_service_tax,0)-IFNULL(bkg_partner_commission,0), 0)) AS quote_vendor_amount
                FROM `booking`
				JOIN `svc_class_vhc_cat` ON  svc_class_vhc_cat.scv_id = booking.bkg_vehicle_type_id
				JOIN `service_class` ON  service_class.scc_id = svc_class_vhc_cat.scv_scc_id
				JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
				JOIN booking_cab ON bcb_id=bkg_bcb_id AND bkg_active = 1
				WHERE
				booking.bkg_status IN(2, 3, 5, 6, 7) $where  AND booking.bkg_active = 1
				GROUP BY service_class.scc_id";
		$arr = DBUtil::queryAll($sql, DBUtil::SDB3());
		return $arr;
	}

	public function getServiceTypeTodaysBooking()
	{
		$where = "";
		if($this->from_date != null && $this->to_date != null)
		{
			$where = " AND (booking.bkg_create_date BETWEEN '$this->from_date' AND '$this->to_date') ";
		}
		else
		{
			$where = ' AND (booking.bkg_create_date BETWEEN CONCAT(CURDATE()," 00:00:00") AND CONCAT(CURDATE()," 23:59:59"))';
		}
		$sql = "SELECT
				SUM(IF((bkg_agent_id = 450),1,0)) as total_mmtb2b,
				SUM(IF((bkg_agent_id = 18190),1,0)) as total_ibibob2b,
				SUM(IF((bkg_agent_id NOT IN(450,18190) AND bkg_agent_id IS NOT NULL),1,0)) as total_b2bothers,
                SUM(IF((bkg_agent_id IS NULL),1,0)) as total_b2c,
				booking.bkg_booking_type,
				(
					CASE booking.bkg_booking_type WHEN 1 THEN 'OW' WHEN 2 THEN 'RT' WHEN 3 THEN 'MW' WHEN 4 THEN 'AT' WHEN 5 THEN 'PT' WHEN 6 THEN 'FL' WHEN 7 THEN 'SH' WHEN 8 THEN 'CT'
					WHEN 9 THEN 'DR(4hr-40km)' WHEN 10 THEN 'DR(8hr-80km)' WHEN 11 THEN 'DR(12hr-120km)' WHEN 12 THEN 'AP' WHEN 15 THEN 'LT'
				END
				) AS serviceType,
				COUNT(bkg_id) cntServiceType
				FROM
					`booking`
				WHERE
					booking.bkg_status IN(2, 3, 5, 6, 7) $where AND booking.bkg_active = 1
				GROUP BY
					booking.bkg_booking_type
				";

		$arr = DBUtil::queryAll($sql, DBUtil::SDB3());
		return $arr;
	}

	public function getZonewiseBookingCount($date1, $date2, $type = '')
	{
		$cond = '';
		if($date1 == '' || $date2 == '')
		{
			$date2	 = date('Y-m-d');
			$cond	 = " AND bkg.bkg_pickup_date BETWEEN ('" . $date2 . "' - INTERVAL 180 DAY) AND '" . $date2 . "'";
		}
		else
		{
			$cond = " AND bkg.bkg_pickup_date BETWEEN (" . $date2 . " - INTERVAL 180 DAY) AND '" . $date2 . "'";
		}
		$sql = "SELECT  s.stt_zone AS Region,
								 z.zon_name
									AS Source_Zone,
								 COUNT(
									DISTINCT IF(
												bkg.bkg_pickup_date >=
												DATE_SUB('$date2', INTERVAL 180 DAY),
												bkg_id,
												NULL))
									AS Count_180,
								 COUNT(
									DISTINCT IF(
												bkg.bkg_pickup_date >=
												DATE_SUB('$date2' , INTERVAL 90 DAY),
												bkg_id,
												NULL))
									AS Count_90
						  FROM booking bkg
							   INNER JOIN cities fcity ON fcity.cty_id = bkg.bkg_from_city_id
							   INNER JOIN states s ON s.stt_id = fcity.cty_state_id
							   INNER JOIN zone_cities zc
								  ON bkg.bkg_from_city_id = zc.zct_cty_id AND zc.zct_active = 1
							   INNER JOIN zones z ON zc.zct_zon_id = z.zon_id AND z.zon_active = 1
						  WHERE     bkg.bkg_status IN (6, 7)
								AND bkg.bkg_active = 1
								$cond
						  GROUP BY Source_Zone ";

		if($type == 'Command')
		{
			$sqlCount		 = "SELECT  z.zon_name	AS Source_Zone
								FROM booking bkg
								INNER JOIN cities fcity ON fcity.cty_id = bkg.bkg_from_city_id
								INNER JOIN states s ON s.stt_id = fcity.cty_state_id
								INNER JOIN zone_cities zc  ON bkg.bkg_from_city_id = zc.zct_cty_id AND zc.zct_active = 1
								INNER JOIN zones z ON zc.zct_zon_id = z.zon_id AND z.zon_active = 1
								WHERE  bkg.bkg_status IN (6, 7)	AND bkg.bkg_active = 1	$cond
								GROUP BY Source_Zone ";
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) a", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['Count_180', 'Count_90'],
					'defaultOrder'	 => 'Count_180 DESC'], 'pagination'	 => ['pageSize' => 20],
			]);
			return $dataprovider;
		}
		else
		{
			$recordSet = DBUtil::queryAll($sql, DBUtil::SDB());
			return $recordSet;
		}
	}

	public function getVendorWiseBookingCount($date1, $date2, $type = '')
	{
		$cond = '';
		if($date1 == '' || $date2 == '')
		{
			$date2	 = date('Y-m-d');
			$cond	 = " AND bkg.bkg_pickup_date BETWEEN ('" . $date2 . "' - INTERVAL 180 DAY) AND '" . $date2 . "'";
		}
		else
		{
			$cond = " AND bkg.bkg_pickup_date BETWEEN ('" . $date2 . "' - INTERVAL 180 DAY) AND '" . $date2 . "'";
		}
		$sql = "SELECT
						'' AS region,
						vnp.vnp_home_zone,
						vnd.vnd_id,
						vnd.vnd_name,
						IFNULL(vrs.vrs_vnd_overall_rating,'NA') AS vrs_vnd_overall_rating,
						COUNT(
						   DISTINCT IF(
									   bkg.bkg_pickup_date >=
									   DATE_SUB('$date2', INTERVAL 180 DAY),
									   bkg_id,
									   NULL))
						   AS Count_180,
						COUNT(
						   DISTINCT IF(
									   bkg.bkg_pickup_date >= DATE_SUB('$date2', INTERVAL 90 DAY),
									   bkg_id,
									   NULL))
						   AS Count_90
				 FROM booking bkg
					  INNER JOIN booking_cab bcb
						 ON bkg.bkg_bcb_id = bcb.bcb_id AND bcb.bcb_active = 1
					  INNER JOIN vendors vnd ON bcb.bcb_vendor_id = vnd.vnd_id
					  INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id
					  LEFT JOIN vendor_pref vnp ON vnd.vnd_id = vnp.vnp_vnd_id
				 WHERE     bkg.bkg_status IN (6, 7)
					   AND bkg.bkg_active = 1
					   $cond
				 GROUP BY vnd.vnd_id";

		if($type == 'Command')
		{
			$sqlCount		 = " Select	vnd.vnd_id
					FROM booking bkg
					INNER JOIN booking_cab bcb ON bkg.bkg_bcb_id = bcb.bcb_id AND bcb.bcb_active = 1
					INNER JOIN vendors vnd ON bcb.bcb_vendor_id = vnd.vnd_id
					INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id
					LEFT JOIN vendor_pref vnp ON vnd.vnd_id = vnp.vnp_vnd_id
					WHERE bkg.bkg_status IN (6, 7) AND bkg.bkg_active = 1  $cond	 GROUP BY vnd.vnd_id";
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) a", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['Count_180', 'Count_90'],
					'defaultOrder'	 => 'Count_180 DESC'], 'pagination'	 => ['pageSize' => 20],
			]);
			return $dataprovider;
		}
		else
		{
			$recordSet = DBUtil::queryAll($sql, DBUtil::SDB());
			return $recordSet;
		}
	}

	public function getMarginByServiceType()
	{
		$where = "";
		if($this->from_date != null && $this->to_date != null)
		{
			$where = " AND (booking.bkg_create_date BETWEEN '$this->from_date' AND '$this->to_date') ";
		}
		else
		{
			$where = ' AND (booking.bkg_create_date BETWEEN CONCAT(CURDATE()," 00:00:00") AND CONCAT(CURDATE()," 23:59:59"))';
		}
		$sql = "SELECT
			COUNT(bkg_id) AS cnt,
		    (CASE booking.bkg_booking_type
			       WHEN 1 THEN 'OW'
			       WHEN 2 THEN 'RT'
			       WHEN 3 THEN 'MW'
			       WHEN 4 THEN 'AT'
			       WHEN 5 THEN 'PT'
			       WHEN 6 THEN 'FL'
			       WHEN 7 THEN 'SH'
			       WHEN 8 THEN 'CT'
			       WHEN 9 THEN 'DR(4hr-40km)'
			       WHEN 10 THEN 'DR(8hr-80km)'
			       WHEN 11 THEN 'DR(12hr-120km)'
				   WHEN 12 THEN 'AP'
                   WHEN 15 THEN 'LT'
			    END)
			      AS serviceType,
		    SUM(IF((bkg_agent_id = 450), (biv.bkg_gozo_amount- IFNULL(biv.bkg_credits_used,0)), 0)) AS total_mmtb2b,
		    SUM(IF((bkg_agent_id = 18190), (biv.bkg_gozo_amount- IFNULL(biv.bkg_credits_used,0)), 0))  AS total_ibibob2b,
		    SUM(IF((bkg_agent_id NOT IN(450,18190) AND bkg_agent_id IS NOT NULL),  (biv.bkg_gozo_amount- IFNULL(biv.bkg_credits_used,0)), 0)) AS total_b2bothers,
		    SUM(IF((bkg_agent_id IS NULL), (biv.bkg_gozo_amount- IFNULL(biv.bkg_credits_used,0)), 0)) AS total_b2c,
			   booking.bkg_booking_type,
		    SUM((biv.bkg_gozo_amount- IFNULL(biv.bkg_credits_used,0)))  totalmargin
		    FROM `booking`
		    INNER JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
		    WHERE     booking.bkg_status IN (2,
						     3,
						     5,
						     6,
						     7)
			 $where
			  AND booking.bkg_active = 1
		    GROUP BY booking.bkg_booking_type";
		$arr = DBUtil::queryAll($sql, DBUtil::SDB3());
		return $arr;
	}

	public function getOfflineDriverCount()
	{
		$returnSet = Yii::app()->cache->get('getOfflineDriverCount');
		if($returnSet === false)
		{
			$sql		 = "SELECT   COUNT(DISTINCT bkg_id) as count  FROM
					(
					SELECT bkg.bkg_id,bkg.bkg_bcb_id,bkg.bkg_status,bkg_pickup_date,apt_last_login from booking bkg
					JOIN booking_cab bcb ON bkg.bkg_bcb_id = bcb.bcb_id
					LEFT JOIN app_tokens apt ON apt.apt_entity_id = bcb.bcb_driver_id AND apt.apt_status = 1 AND apt.apt_user_type = 5
					AND DATE_SUB(bkg.bkg_pickup_date,INTERVAL 4 HOUR) < apt.apt_last_login
					WHERE bkg.bkg_status=5 AND DATE_SUB(bkg.bkg_pickup_date,INTERVAL 4 HOUR) < NOW()
					AND DATE_ADD(bkg.bkg_pickup_date,INTERVAL 150 MINUTE) > NOW() AND
					apt.apt_id IS NULL
					)a ";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getOfflineDriverCount', $returnSet, 600);
		}
		return $returnSet;
	}

	public function getCountDriverNotLeftforPickup()
	{
		$returnSet = Yii::app()->cache->get('getCountDriverNotLeftforPickup');
		if($returnSet === false)
		{
			$sql		 = "SELECT   COUNT(DISTINCT bkg_id) as count  FROM
					(
					SELECT bkg.bkg_id, bkg.bkg_status,bkg_pickup_date  from booking bkg
					LEFT JOIN booking_track_log btl ON btl.btl_bkg_id = bkg.bkg_id AND btl.btl_event_type_id = 201
					WHERE bkg.bkg_status=5 AND DATE_SUB(bkg.bkg_pickup_date,INTERVAL 4 HOUR) < NOW()
					AND DATE_ADD(bkg.bkg_pickup_date,INTERVAL 150 MINUTE) > NOW() AND
					btl.btl_id IS NULL
					)a ";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getCountDriverNotLeftforPickup', $returnSet, 600);
		}
		return $returnSet;
	}

	public function getBookingQuotationCounReport($date1, $date2)
	{
		$params	 = ['date1' => $date1, 'date2' => $date2];
		$sql	 = "SELECT   DATE_FORMAT(booking.bkg_create_date, '%Y-%m') date, count(1) as enquiriesCount FROM booking WHERE  booking.bkg_create_date BETWEEN :date1 AND :date2 AND bkg_status=15 GROUP BY date";
		return DBUtil::queryAll($sql, DBUtil::SDB(), $params);
	}

	public function getStickyBookingReport($date1, $date2)
	{
		$params	 = ['date1' => $date1 . ' 00:00:00', 'date2' => $date2 . ' 23:59:59'];
		$sql	 = "SELECT
					date,
					sum(cnt) AS TotalBookingcars,
					Sum(IF(temp.cnt < 5, temp.cnt, 0)) AS CountnonStickyBookingcars,
					Sum(IF(temp.cnt > 15, temp.cnt, 0)) AS CountSuperstickyBookingcars,
					Sum(IF(temp.cnt >= 5 AND temp.cnt <= 15, temp.cnt, 0)) AS CountStickyBookingcars
					FROM
					(
						SELECT
						DATE_FORMAT(bkg_pickup_date, '%Y-%m') AS date,
						count(booking_cab.bcb_cab_id) AS cnt
						FROM     booking
						INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id
						INNER JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id
						INNER JOIN users ON users.user_id = booking_user.bkg_user_id
						WHERE    bkg_status IN (6,7) AND booking.bkg_pickup_date BETWEEN :date1 and :date2
						GROUP BY date, booking_cab.bcb_cab_id
					) temp GROUP BY date ";
		return DBUtil::queryAll($sql, DBUtil::SDB3(), $params);
	}

	public function getPartnerWiseCountBookingReport($date1, $date2, $agentId = 0, $command = false, $pickupFromDate='', $pickupToDate='')
	{
		$where = "";
		if($agentId > 0)
		{
			$where .= " AND bkg_agent_id=$agentId ";
		}
		if($date1 != '' && $date2 != '')
		{
			$where .= " AND (booking.bkg_create_date BETWEEN '{$date1} 00:00:00' and '{$date2} 23:59:59' ) ";
		}
		if($pickupFromDate != '' && $pickupToDate != '')
		{
			$where .= " AND (booking.bkg_pickup_date BETWEEN '{$pickupFromDate} 00:00:00' and '{$pickupToDate} 23:59:59' ) ";
		}
		
		$sql = "SELECT
					bkg_agent_id,pts_wallet_balance,pts_ledger_balance,(pts_ledger_balance-pts_wallet_balance) accountBalance,
					SUM(IF(bkg_status IN (6,7) AND bkg_reconfirm_flag = 1,(bkg_net_base_amount), 0)) AS net_base_amount,
					SUM(IF(bkg_status IN (6,7) AND bkg_reconfirm_flag = 1,(bkg_total_amount), 0)) AS totalamount,
					SUM(IF(bkg_status IN (6,7) AND bkg_reconfirm_flag = 1,bkg_gozo_amount, 0)) AS gozoamount,
					COUNT(IF(bkg_status IN (2, 3, 5, 6, 7, 9), bkg_id, NULL)) AS cnt,
					SUM(IF(bkg_status IN (6,7),1,0)) AS total_served_booking,
					SUM(IF(bkg_status IN (15),1,0)) AS quoted_booking,
					SUM(IF(bkg_status IN (9),1,0)) AS cancelled_booking,
					SUM(IF(bkg_status IN (9) AND bkg_cancel_id IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38,40),1,0)) AS gozo_intiated_cancel,
					SUM(IF(bkg_booking_type IN (4,9,10,11,12,14,15,16),1,0)) AS total_book_local,
					SUM(IF(bkg_booking_type NOT IN (4,9,10,11,12,14,15,16),1,0)) AS total_book_outstation,
					ROUND(((SUM(IF(bkg_status IN (6,7) AND bkg_reconfirm_flag = 1, bkg_gozo_amount, 0))/SUM(IF(bkg_status IN (6,7) AND bkg_reconfirm_flag = 1, (bkg_net_base_amount), 0))  )*100),2) AS netgrossmargin,
					ROUND(((SUM(IF(bkg_status IN (6,7) AND bkg_reconfirm_flag = 1, bkg_gozo_amount, 0))/SUM(IF(bkg_status IN (6,7) AND bkg_reconfirm_flag = 1, (bkg_total_amount), 0))  )*100),2) AS totalgrossmargin,
					agt_company as partnername,
					GROUP_CONCAT(DISTINCT booking.bkg_id SEPARATOR ', ') AS booking_id,DATE(MAX(bkg_create_date)) AS lastBookingReceivedDate
				FROM   `booking`
					INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
					INNER JOIN booking_invoice ON bkg_id=biv_bkg_id
					INNER JOIN agents ON agents.agt_id=bkg_agent_id
					INNER JOIN partner_stats ON pts_agt_id = bkg_agent_id
				WHERE 1
					AND	booking.bkg_active = 1
					AND booking.bkg_status IN (2, 3, 5, 6, 7, 9, 15)
					AND bkg_agent_id IS NOT NULL
					$where
					AND bkg_agent_id NOT IN (450, 18190)
				GROUP by bkg_agent_id";

		if(!$command)
		{
			$sqlCount = "SELECT
						bkg_agent_id
						FROM   `booking`
						INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
						INNER JOIN booking_invoice ON bkg_id=biv_bkg_id
						INNER JOIN agents ON agents.agt_id=bkg_agent_id
						WHERE
						booking.bkg_active = 1
						AND booking.bkg_status IN (2, 3, 5, 6, 7, 9, 15) 
						AND bkg_agent_id IS NOT NULL 
						$where
						AND bkg_agent_id NOT IN (450, 18190)
						GROUP by bkg_agent_id";

			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['partnername', 'cnt', 'totalamount', 'gozoamount'],
					'defaultOrder'	 => 'bkg_agent_id DESC'
				],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
	}

	public function getTodayScheduledPickup($date1, $date2)
	{
		$params	 = array('date1' => $date1, 'date2' => $date2);
		$sql	 = "SELECT
				service_class.scc_label tierName,
				COUNT(1) cnt,
				SUM(IF(bkg_status=2, 1, 0)) AS new,
				SUM(IF(bkg_status=3, 1, 0)) AS assigned,
				SUM(IF(bkg_status=5, 1, 0)) AS ontheway,
				SUM(IF(bkg_status in (6,7), 1, 0)) AS completed,
				SUM(IF(bkg_status=9, 1, 0)) AS cancelled,
				SUM(IF(bkg_status = 9 and bkg_cancel_id    IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38) , 1, 0)) AS cancelledbygozo,
				SUM(IF(bkg_status = 9 and bkg_cancel_id  NOT  IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38) , 1, 0)) AS notcancelledbygozo,
				SUM(IF(bkg_status =6 AND bkg_reconfirm_flag=1, bkg_gozo_amount- IFNULL(bkg_credits_used,0),0)) AS ry_gozo_amount,
				SUM(IF(bkg_status =6 AND bkg_reconfirm_flag=1, (bkg_net_base_amount), 0)) AS ry_booking_amount,
				SUM(IF(bkg_reconfirm_flag=1, bkg_gozo_amount- IFNULL(bkg_credits_used,0),0)) AS ry_gozo_amount_all,
				SUM(IF(bkg_reconfirm_flag=1, (bkg_net_base_amount), 0)) AS ry_booking_amount_all
				FROM	`booking`
				JOIN `svc_class_vhc_cat` ON  svc_class_vhc_cat.scv_id = booking.bkg_vehicle_type_id
				JOIN `service_class` ON  service_class.scc_id = svc_class_vhc_cat.scv_scc_id
				JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
				JOIN booking_cab ON bcb_id=bkg_bcb_id AND bkg_active = 1
                JOIN booking_pref ON     bkg_id = bpr_bkg_id
				WHERE ( booking.bkg_pickup_date BETWEEN :date1 AND :date2 )  AND booking.bkg_active = 1 AND (bkg_status IN (2, 3,5, 6,7) OR (bkg_status = 9 AND bkg_cancel_id NOT IN (7, 24)) AND bkg_tentative_booking = 0)
				GROUP BY service_class.scc_id";
		return DBUtil::query($sql, DBUtil::SDB3(), $params);
	}

	public function getTodayBookingCancellation($date1, $date2)
	{
		$params	 = array('date1' => $date1, 'date2' => $date2);
		$sql	 = "SELECT
				service_class.scc_label tierName,
				SUM(IF(bkg_status=9, 1, 0)) AS cancelled,
				SUM(IF(bkg_status = 9 and bkg_cancel_id    IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38) , 1, 0)) AS cancelledbygozo,
				SUM(IF(bkg_status = 9 and bkg_cancel_id  NOT  IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38) , 1, 0)) AS notcancelledbygozo
				FROM	`booking`
				JOIN booking_trail on  btr_bkg_id= booking.bkg_id and booking.bkg_status IN(9) AND ( booking_trail.btr_cancel_date BETWEEN  :date1 AND :date2 )  AND booking.bkg_active = 1
				JOIN `svc_class_vhc_cat` ON  svc_class_vhc_cat.scv_id = booking.bkg_vehicle_type_id
				JOIN `service_class` ON  service_class.scc_id = svc_class_vhc_cat.scv_scc_id
				JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
				JOIN booking_cab ON bcb_id=bkg_bcb_id AND bkg_active = 1
				JOIN booking_pref ON     bkg_id = bpr_bkg_id
				WHERE 1 and (bkg_status = 9 AND bkg_cancel_id NOT IN (7, 24)) AND bkg_tentative_booking = 0
				GROUP BY service_class.scc_id";
		return DBUtil::query($sql, DBUtil::SDB3(), $params);
	}

	public static function getFreeCancelEndTime($pickupDate, $sccId = 1)
	{
		switch($sccId)
		{
			case 1:
				$freeCancelEnd	 = date('d M Y h:i A', strtotime('-24 hour', strtotime($pickupDate)));
				break;
			case 2:
				$sql			 = "SELECT SubWorkingMinutes(:minutes, '$pickupDate') FROM dual";
				$res			 = DBUtil::SDB()->createCommand($sql)->queryScalar(['minutes' => 12 * 60]);
				$freeCancelEnd	 = date('d M Y h:i A', strtotime($res));
				break;
			case 4:
				$sql			 = "SELECT SubWorkingMinutes(:minutes, '$pickupDate') FROM dual";
				$res			 = DBUtil::SDB()->createCommand($sql)->queryScalar(['minutes' => 8 * 60]);
				$freeCancelEnd	 = date('d M Y h:i A', strtotime($res));
				break;
			default:
				$freeCancelEnd	 = date('d M Y h:i A', strtotime('-24 hour', strtotime($pickupDate)));
				break;
		}
		return $freeCancelEnd;
	}

	public function getAutoCancelBookingCount()
	{
		$returnSet = Yii::app()->cache->get('getAutoCancelBookingCount');
		if($returnSet === false)
		{
			$sql		 = 'SELECT COUNT(DISTINCT booking.bkg_id) as count
                FROM `booking` 	JOIN booking_trail ON     bkg_id = btr_bkg_id
	            WHERE 1 AND booking.bkg_status=2  AND booking.bkg_active=1 AND booking_trail.btr_auto_cancel_value IS NOT NULL AND booking_trail.btr_auto_cancel_reason_id!=33 LIMIT 0,1';
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getAutoCancelBookingCount', $returnSet, 600);
		}
		return $returnSet;
	}

	public static function getUserDetails($bkgId)
	{
		$params ['bkgid']	 = $bkgId;
		$sql				 = "SELECT 	booking.bkg_id,booking.bkg_booking_id,booking_user.bkg_user_fname as bkg_user_fname,booking_user.bkg_user_email as bkg_user_email,
                booking_user.bkg_user_lname as bkg_user_lname FROM booking
                INNER JOIN booking_user ON bui_bkg_id = booking.bkg_id WHERE booking.bkg_id=:bkgid";
		$data				 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 *  @param integer $bkgId
	 * @return Array $data
	 */
	public static function getCancellationDetails($bkgId)
	{
		$eventIds			 = [10, 82, 122];
		DBUtil::getINStatement($eventIds, $bindString, $params);
		$sql				 = "SELECT 	booking.bkg_id,
                booking.bkg_booking_id,booking.bkg_status,booking.bkg_reconfirm_flag,
                booking_user.bkg_user_fname as bkg_user_fname,
		        booking_user.bkg_user_email as bkg_user_email,
                booking_user.bkg_user_lname as bkg_user_lname,
                booking.bkg_cancel_id,
                booking.bkg_cancel_delete_reason,
                blg_user_type,
                blg_desc,
                cancel_reasons.cnr_reason,(
                    CASE WHEN blg_user_type=1 THEN 'Consumer'
                         WHEN blg_user_type=4 THEN 'Admin'
                         WHEN blg_user_type=10 THEN 'System'
                     END
                ) as user_type,
				biv.bkg_advance_amount, biv.bkg_cancel_charge,biv.bkg_cancel_gst, biv.bkg_refund_amount,
				agents.agt_company,
                IF(agents.agt_company!='',agents.agt_company,CONCAT(agents.agt_fname,' ',agents.agt_lname)) as agent_name,
				partner_settings.pts_send_invoice_to,partner_settings.pts_generate_invoice_to

                FROM booking
                INNER JOIN booking_user ON bui_bkg_id = booking.bkg_id
				INNER JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
                INNER JOIN (
                    SELECT booking_log.blg_user_type, booking_log.blg_booking_id, booking_log.blg_desc
                    FROM booking_log
                    WHERE booking_log.blg_event_id IN ($bindString)
                )blg ON blg.blg_booking_id=booking.bkg_id
                LEFT JOIN cancel_reasons ON cancel_reasons.cnr_id=booking.bkg_cancel_id AND cancel_reasons.cnr_active=1
				LEFT JOIN `agents` ON agents.agt_id=booking.bkg_agent_id
				LEFT JOIN `partner_settings` on partner_settings.pts_agt_id=agents.agt_id
                WHERE booking.bkg_id=:bkgid AND booking.bkg_status IN(9)";
		$params ['bkgid']	 = $bkgId;
		$data				 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public static function getCompletedBookingCountByMMT($date1, $date2)
	{
		$params	 = ['date1' => $date1, 'date2' => $date2];
		$sql	 = "SELECT   temp.date, SUM(temp.completedMMT) AS completedMMT	FROM
				(
					SELECT
					DATE_FORMAT(booking_trail.btr_mark_complete_date, '%Y-%m') AS date,
					COUNT(bkg_id) as completedMMT
					FROM   booking
					INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id
					INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
					WHERE bkg_status IN (6,7)  AND booking.bkg_agent_id IN (450, 18190) AND booking_trail.btr_mark_complete_date  BETWEEN :date1 AND :date2
					GROUP BY date
				) temp GROUP BY temp.date";
		return DBUtil::queryAll($sql, DBUtil::SDB(), $params);
	}

	public static function blockVendorPayment($bcbid, $bkgid, $statusType, $desc, $escalation_level, $assigned_lead, $assigned_team)
	{
		$userInfo									 = UserInfo::model();
		$model										 = BookingCab::model()->findByPk($bcbid);
		$trailModel									 = BookingTrail::model()->findByPk($model->bookings[0]->bkgTrail->btr_id);
		$trailModel->bkg_escalation_status			 = 1;
		$trailModel->btr_escalation_level			 = $escalation_level;
		$trailModel->btr_escalation_assigned_lead	 = $assigned_lead;
		$trailModel->btr_escalation_assigned_team	 = $assigned_team;
		$model->bcb_lock_vendor_payment				 = $statusType;
		$trailModel->btr_count_escalation			 = $trailModel->btr_count_escalation + 1;
		if($trailModel->btr_escalation_fdate == NULL || $trailModel->btr_escalation_fdate == "")
		{
			$trailModel->btr_escalation_fdate = DBUtil::getCurrentTime();
		}
		$trailModel->btr_escalation_ldate = DBUtil::getCurrentTime();
		if($model->save() && $trailModel->save())
		{
			$eventid				 = BookingLog::BOOKING_ESCALATION_SET;
			$params['blg_ref_id']	 = $bkgid;
			BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, false, $params);
		}
	}

	/*
	 * getUrgentPickup is old function | getUrgentPickup_v1 new model.
	 * Depricated
	 */

	public function getUrgentPickup($bkgType = 0)
	{
		DBUtil::getINStatement("3,5", $bindString, $params1);
		$params2 = array('hour' => 3);
		$params	 = array_merge($params2, $params1);
		if($bkgType > 0)
		{
			$whereType = "AND bkg.bkg_booking_type= $bkgType";
		}
		//$where	 .= "AND bkg.bkg_pickup_date <= DATE_ADD(NOW(), INTERVAL :hour hour)AND bkg.bkg_status IN($bindString)";


		$where .= "AND bkg.bkg_pickup_date <= date_add(NOW(),interval :hour hour)AND bkg.bkg_status IN($bindString) AND DATE(bkg.bkg_pickup_date)=DATE(NOW())AND  bkg_ride_start = 0 ";

//$cond	 = "AND bkg.bkg_pickup_date BETWEEN now() AND date_add(now(),interval 3 hour)AND bkg.bkg_status IN(3,4,5) AND bkg.bkg_status IN(3,4,5) ";

		$sql			 = "SELECT  btk_last_event,btk_last_event_time,vnd_code,vnd_id,drv_id,drv_code,vnd_contact_id,drv_contact_id,bkg.bkg_pickup_lat,bkg.bkg_pickup_long,bkg.bkg_bcb_id,bkg.bkg_id,bkg.bkg_booking_id,bkg.bkg_pickup_date, bcb.bcb_vendor_id, bcb.bcb_driver_id, bcb.bcb_cab_id,drv_last_loc_lat,drv_last_loc_long,drv_last_loc_date FROM booking bkg
INNER JOIN booking_cab bcb ON bcb.bcb_id=bkg.bkg_bcb_id

LEFT JOIN driver_stats ON drs_drv_id =bcb.bcb_driver_id
LEFT JOIN drivers ON drv_id =bcb.bcb_driver_id
LEFT JOIN vendors ON vnd_id =bcb.bcb_vendor_id
LEFT JOIN booking_track ON bkg.bkg_id = btk_bkg_id
WHERE 1=1 {$where}{$whereType} ORDER BY bkg.bkg_pickup_date ASC";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_pickup_date'],
				'defaultOrder'	 => 'bkg.bkg_pickup_date DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);

		return $dataprovider;
	}

	public function getUrgentPickupCount($type = 0)
	{
		$returnSet = Yii::app()->cache->get('getUrgentPickupCount_' . $type);
		if($returnSet === false)
		{
			DBUtil::getINStatement("3,5", $bindString, $params1);
			$params2 = array('hour' => 3);
			$params	 = array_merge($params2, $params1);
			if($type > 0)
			{
				$whereType = "AND bkg.bkg_booking_type= $type";
			}
			//$where	 = "AND bkg.bkg_pickup_date BETWEEN NOW() AND date_add(NOW(),interval :hour hour)AND bkg.bkg_status IN($bindString)";
			$where	 .= "AND bkg.bkg_pickup_date <= date_add(NOW(),interval :hour hour)AND bkg.bkg_status IN($bindString) AND DATE(bkg.bkg_pickup_date)=DATE(NOW())  AND bkg_ride_start = 0 ";
			$sql	 = "SELECT COUNT(bkg.bkg_id) FROM booking bkg
                    INNER JOIN booking_cab bcb ON bcb.bcb_id=bkg.bkg_bcb_id
                    LEFT JOIN booking_track ON bkg.bkg_id = btk_bkg_id
                    WHERE 1=1 {$where}{$whereType}";

			$returnSet = DBUtil::queryScalar($sql, DBUtil::SDB(), $params, 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getUrgentPickupCount_' . $type, $returnSet, 600);
		}
		return $returnSet;
	}

	public static function getCodebyUserIdnId($userId, $refId)
	{
		$params	 = ['userid' => $userId, 'bkgid' => $refId, 'bkgcode' => $refId];
		$sql	 = "SELECT bkg_booking_id from booking bkg
			JOIN booking_user bui ON bui.bui_bkg_id = bkg.bkg_id AND bui.bkg_user_id =:userid
			WHERE bkg.bkg_id =:bkgid OR bkg.bkg_booking_id =:bkgcode";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public static function getCodebyVndIdnId($vndId, $refId)
	{
		$params	 = ['vndId' => $vndId, 'bkgid' => $refId, 'bkgcode' => $refId];
		$sql	 = "SELECT bkg_booking_id from booking bkg
			JOIN booking_cab bcb ON bcb.bcb_vendor_id = :vndId AND bkg.bkg_bcb_id = bcb.bcb_id
			WHERE bkg.bkg_id =:bkgid OR bkg.bkg_booking_id =:bkgcode";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	/**
	 * @var string $bookID
	 * @var int $pickupDuration Pickup Duration in month
	 */
	public static function getbyBookingLastDigits($bookID, $pickupDuration = 6)
	{
		$params		 = ['bkgid' => $bookID];
		$bkgidSize	 = strlen($bookID);
		$sql		 = "SELECT bkg_id from booking
						WHERE bkg_status IN (2,3,5,6,7,9,15) AND SUBSTRING(bkg_booking_id,-$bkgidSize) =:bkgid
						AND bkg_pickup_date > date_sub(NOW(),INTERVAL $pickupDuration MONTH)
						ORDER BY bkg_id DESC";
		$resBkgId	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $resBkgId;
	}

	public static function getFbgBookings()
	{
		$interval	 = Yii::app()->params['fbgCancellationInterval'];
		$params		 = ['interval' => $interval];
		$sql		 = "SELECT bkg.bkg_id,bkg.bkg_status FROM booking bkg
                INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id AND bkg.bkg_status = 2
                WHERE bpr.bkg_is_fbg_type =1 AND bkg.bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL :interval MINUTE)";
		return DBUtil::queryAll($sql, DBUtil::SDB(), $params);
	}

	/**
	 * This function is used for getting all driver 1.bookings having drivers not left for pickup by app  2. bookings having drivers not logged in their app before 2 hours of pickup
	 * @return queryObject array of bookingID
	 */
	public static function getAllDriverLate()
	{
		/*  1.bookings having drivers not left for pickup by app  2. bookings having drivers not logged in their app before 1.5 hours of pickup          */
		$sql = "SELECT bkg.bkg_id
				FROM booking bkg LEFT JOIN booking_track_log btl ON  btl.btl_bkg_id = bkg.bkg_id AND btl.btl_event_type_id = 201
				WHERE bkg.bkg_status = 5
				AND bkg.bkg_pickup_date BETWEEN NOW() AND  DATE_ADD(NOW(), INTERVAL 150 MINUTE)
				AND btl.btl_id IS NULL

				UNION

				SELECT bkg.bkg_id
				FROM booking bkg
				JOIN booking_cab bcb ON bkg.bkg_bcb_id = bcb.bcb_id
				LEFT JOIN app_tokens apt ON  apt.apt_entity_id = bcb.bcb_driver_id
					  AND apt.apt_status = 1
					  AND apt.apt_user_type = 5
					  AND DATE_SUB(bkg.bkg_pickup_date, INTERVAL 150 MINUTE) <apt.apt_last_login
				WHERE bkg.bkg_status = 5
				AND bkg.bkg_pickup_date BETWEEN NOW() AND  DATE_ADD(NOW(), INTERVAL 150 MINUTE)
				AND apt.apt_id IS NULL";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public function getUrgentPickup_v1($bkgType = 0, $date1, $date2, $type)
	{
		DBUtil::getINStatement("3,5", $bindString, $params1);
		//$params2 = array('hour' => 3);

		if($bkgType > 0)
		{
			$whereType = " AND bkg.bkg_booking_type = $bkgType";
		}
		//$where	 .= "AND bkg.bkg_pickup_date <= DATE_ADD(NOW(), INTERVAL :hour hour)AND bkg.bkg_status IN($bindString)";
		if($type == 1)
		{
			DBUtil::getINStatement("3,5,9,10,6", $bindString, $params1);
			$whereTable1 = "AND bkg.bkg_pickup_date <= NOW() AND bkg.bkg_status IN($bindString) AND  (booking_track.bkg_trip_arrive_time > bkg.bkg_pickup_date) AND bkg_trip_arrive_time IS NOT NULL";
		}
		else if($type == 2)
		{
			$whereTable1 = "AND bkg.bkg_pickup_date <= NOW() AND bkg.bkg_status IN($bindString) AND booking_track.bkg_trip_arrive_time IS NULL";
		}
		else
		{
			$whereTable1 = "AND bkg.bkg_pickup_date > NOW() AND bkg.bkg_status IN($bindString) AND booking_track.bkg_trip_arrive_time IS NULL";
		}

		$params = $params1;

		$sql			 = "SELECT  btk_last_event,btk_last_event_time,vnd_code,vnd_id,drv_id,drv_code,vnd_contact_id,drv_contact_id,bkg.bkg_pickup_lat,bkg.bkg_pickup_long,bkg.bkg_bcb_id,bkg.bkg_id,bkg.bkg_booking_id,bkg.bkg_pickup_date, bcb.bcb_vendor_id, bcb.bcb_driver_id, bcb.bcb_cab_id,drv_last_loc_lat,drv_last_loc_long,drv_last_loc_date
            FROM booking bkg
            INNER JOIN booking_cab bcb ON bcb.bcb_id=bkg.bkg_bcb_id
            LEFT JOIN driver_stats ON drs_drv_id =bcb.bcb_driver_id
            LEFT JOIN drivers ON drv_id =bcb.bcb_driver_id
            LEFT JOIN vendors ON vnd_id =bcb.bcb_vendor_id
            LEFT JOIN booking_track ON bkg.bkg_id = btk_bkg_id
            WHERE 1=1 {$whereTable1}{$whereType} AND (bkg.bkg_pickup_date BETWEEN '$date1 00:00:00' and '$date2 23:59:59' ) ORDER BY bkg.bkg_pickup_date ASC";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_pickup_date'],
				'defaultOrder'	 => 'bkg.bkg_pickup_date DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);

		return $dataprovider;
	}

	/**
	 * This function is used for getting no show booking
	 * @return queryObject array
	 */
	public static function getNoShowBooking()
	{

		$sql	 = "SELECT bkg.bkg_id, bkg.bkg_status FROM booking bkg
                    INNER JOIN  booking_track btk ON btk.btk_bkg_id = bkg.bkg_id
                    WHERE btk.btk_last_event = 204 AND bkg_is_no_show =1 AND  DATE_ADD(btk.bkg_no_show_time , interval 1 hour) <= now() AND bkg.bkg_status NOT IN(6,7,9)";
		$result	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $result;
	}

	public static function getIdByRef($ref)
	{
		$sql = "SELECT bkg_id FROM booking WHERE bkg_booking_id = '$ref'  OR bkg_id= '$ref'  OR bkg_agent_ref_code = '$ref' ";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	public static function checkRisk($bkgID)
	{
		$params	 = ['bkgID' => $bkgID];
		$sql	 = "SELECT count(*) as risk FROM `booking`
				INNER JOIN `booking_trail` ON booking.bkg_id=booking_trail.btr_bkg_id AND booking.bkg_status=2
				WHERE booking_trail.btr_is_dem_sup_misfire=1
                AND booking.bkg_pickup_date BETWEEN (DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND (DATE_ADD(NOW(), INTERVAL 11 MONTH))
                AND booking.bkg_reconfirm_flag=1 AND booking.bkg_id =:bkgID ";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public function checkPreAssignmentValidation($model, $bid_amount)
	{
		// Check PreAssignAccess
		$checkPreAssignAccess = Yii::app()->user->checkAccess('PreAssignAccess');
		if($checkPreAssignAccess)
		{
			goto skipCs;
		}

		/* @var $model Booking */
		$bcbVendorAmt = $model->bkgBcb->bcb_vendor_amount;
		if($bid_amount <= $bcbVendorAmt)
		{
			goto skipCs;
		}

//		$checkNightlyVendorAssignment = Booking::checkVendor($model->bkg_pickup_date, $model->bkg_id);
//		if ($checkNightlyVendorAssignment)
//		{
//			goto skipCs;
//		}
		// Check for booking allocated to current loggedin csr
		$checkPreAssignVendorWithMargin = Yii::app()->user->checkAccess('PreAssignVendorWithMargin');
		if((!$checkPreAssignVendorWithMargin && $model->bkgPref->bpr_assignment_id != UserInfo::getUserId()) || UserInfo::getUserType() != UserInfo::TYPE_ADMIN)
		{
			return false;
		}

		// Profit Percentage Calculation
		$baseAmount		 = $model->bkgInvoice->bkg_net_base_amount;
		$maxCreditUsed	 = round(0.15 * $baseAmount);
		$creditUsed		 = ($model->bkgInvoice->bkg_credits_used != '' && $model->bkgInvoice->bkg_credits_used > 0) ? $model->bkgInvoice->bkg_credits_used : 0;
		$creditUsed		 = min($creditUsed, $maxCreditUsed);
		$netProfit		 = $model->bkgInvoice->bkg_gozo_amount - $creditUsed;

		// Calculating difference amount between trip vendor & bid amount
		$diffProfitAmt = ($bid_amount - $bcbVendorAmt);

		// Calculating Profit Percentage
		$profitAmt			 = ($netProfit - $diffProfitAmt);
		$profitPercentage	 = (($profitAmt / $baseAmount) * 100);

		// Checking Risk Booking & Critical Score
		$isRiskBooking			 = BookingSub::checkRisk($model->bkg_id);
		$confirmTime			 = $model->bkgTrail->bkg_confirm_datetime;
		$sql					 = "SELECT CS('" . $model->bkgTrail->bkg_confirm_datetime . "', '" . $model->bkg_pickup_date . "','" . $confirmTime . "') AS actualCriticalityScore;";
		$actualCriticalityScore	 = DButil::queryScalar($sql);

		// If Risk Booking, then speeding CS
		if($isRiskBooking)
		{
			$actualCriticalityScore = round(max(1, $actualCriticalityScore * 1.1), 2);
		}

		// Pre Assign Vendor With Margin
		$checkMargin = self::checkPreAssignVendorWithMargin($actualCriticalityScore, $profitPercentage, $profitAmt);
		if(!$checkMargin)
		{
			return false;
		}
		skipCs:
		return true;
	}

	public static function getPreAssignWithMarginSetting($criticalityScore)
	{
		$value		 = null;
		$arr		 = [];
		$arr[65]	 = ['margin' => 13, 'minCS' => 0.65, 'maxCS' => 0.74, 'maxLoss' => 0];
		$arr[74]	 = ['margin' => 10, 'minCS' => 0.74, 'maxCS' => 0.84, 'maxLoss' => 0];
		$arr[82]	 = ['margin' => 5, 'minCS' => 0.84, 'maxCS' => 0.88, 'maxLoss' => 0];
		$arr[88]	 = ['margin' => 0, 'minCS' => 0.88, 'maxCS' => 0.92, 'maxLoss' => 0];
		$arr[92]	 = ['margin' => -5, 'minCS' => 0.92, 'maxCS' => 0.96, 'maxLoss' => -400];
		$arr[96]	 = ['margin' => -10, 'minCS' => 0.96, 'maxCS' => 1, 'maxLoss' => -600];
		$arr[100]	 = ['margin' => -12, 'minCS' => 1, 'maxCS' => null, 'maxLoss' => -800];

		if($criticalityScore > 0)
		{
			foreach($arr as $arrValue)
			{
				if($criticalityScore >= $arrValue['minCS'] && ($arrValue['maxCS'] = null || $criticalityScore < $arrValue['maxCS']))
				{
					$value = $arrValue;
					break;
				}
			}
		}

		return $value;
	}

	public static function checkPreAssignVendorWithMargin($criticalityScore, $profitPercentage, $profitAmt)
	{
		$value = self::getPreAssignWithMarginSetting($criticalityScore);

		$allowed = false;
		if($value != null && is_array($value))
		{
			if($profitPercentage >= $value['margin'] && $profitAmt >= $value['maxLoss'])
			{
				$allowed = true;
			}
		}

		return $allowed;
	}

	/*
	 * This function is used in margin Percentage report in general report section
	 * param input $arr as array
	 * param output dataprovider
	 */

	public function marginPercentageReport($arr = [])
	{
		$where = '';
		if($arr['bkg_pickup_date1'] != '' && $arr['bkg_pickup_date2'] != '')
		{
			$fromDate	 = $arr['bkg_pickup_date1'];
			$toDate		 = $arr['bkg_pickup_date2'];
			$where		 .= " AND bkg_pickup_date between '" . $fromDate . "' AND '" . $toDate . "'";
		}
		$sql			 = "SELECT
								DATE_FORMAT(bkg_pickup_date,'%Y-%m') as date, COUNT(1) as totalBooking, SUM(netGozoAmount) as gozoAmount,
								SUM(bkg_net_base_amount) as netBaseAmount,
								(SUM(IF(blg_user_type=4, 1,0))*100/SUM(1)) as ManualAssignPercent,
								(SUM(IF(blg_user_type=4, netGozoAmount,0))*100/SUM(IF(blg_user_type=4, bkg_net_base_amount,0))) as ManualMargin,
								SUM(IF(blg_user_type=4, netGozoAmount,0)) as ManualGozoAmount,
								(SUM(IF(blg_user_type<>4, 1,0))*100/SUM(1)) as AutoAssignPercent,
								(SUM(IF(blg_user_type<>4, netGozoAmount,0))*100/SUM(IF(blg_user_type<>4, bkg_net_base_amount,0))) as AutoMargin,
								SUM(IF(blg_user_type<>4, netGozoAmount,0)) as AutoGozoAmount,
								(SUM(IF(blg_user_type=10, 1,0))*100/SUM(1)) as BidAssignPercent,
								(SUM(IF(blg_user_type=10, netGozoAmount,0))*100/SUM(IF(blg_user_type=10, bkg_net_base_amount,0))) as BidAssignMargin,
								SUM(IF(blg_user_type=10, netGozoAmount,0)) as BidGozoAmount,
								(SUM(IF(blg_user_type=2, 1,0))*100/SUM(1)) as DirectAssignPercent,
								(SUM(IF(blg_user_type=2, netGozoAmount,0))*100/SUM(IF(blg_user_type=2, bkg_net_base_amount,0))) as DirectAssignMargin,
								SUM(IF(blg_user_type=2, netGozoAmount,0)) as DirectGozoAmount,
								(SUM(netGozoAmount)*100/SUM(bkg_net_base_amount)) as TotalMargin
							FROM (
								SELECT bkg_id, bkg_pickup_date,
										(biv.bkg_gozo_amount-biv.bkg_credits_used) as netGozoAmount, biv.bkg_net_base_amount, blg.blg_user_type
										  FROM booking
										  INNER JOIN booking_cab ON bkg_bcb_id=bcb_id AND bkg_status IN (3,5,6,7)
										  INNER JOIN booking_invoice biv ON biv_bkg_id=bkg_id
										  INNER JOIN booking_log blg ON blg_booking_id=bkg_id AND blg_event_id=7
										  INNER JOIN (SELECT blg_booking_id, MAX(blg.blg_id) as assignLogId
														FROM booking
														INNER JOIN booking_log blg ON bkg_id=blg.blg_booking_id AND blg_event_id=7
														 WHERE bkg_status IN (3,5,6,7) AND bkg_pickup_date>='2021-10-01 00:00:00'
														  GROUP BY bkg_id) a ON a.blg_booking_id=bkg_id AND blg_id=assignLogId
										WHERE 1 " . $where . "
								) a
							GROUP BY date
							";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['date'], 'defaultOrder' => 'date DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	/*
	 * This function is used in margin Percentage report in general report section
	 * param output dataprovider
	 */

	public function getVendorLockedPayments()
	{
		$vndcode = $this->vnd_code;
		if($vndcode != '')
		{
			$code .= " AND vendors.vnd_code LIKE '%$vndcode%'";
		}
		$sql			 = "SELECT GROUP_CONCAT(bkg_id SEPARATOR ', ') as bkg_ids, bcb_id, GROUP_CONCAT(agt_company SEPARATOR ', ') as agt_company_names,
				GROUP_CONCAT(bkg_pickup_date SEPARATOR ', ') as bkg_pickup_dates, vnd_id, vnd_name, vnd_code, bcb_vendor_amount
                ,blg_desc
				FROM booking_cab
				INNER JOIN booking ON bcb_id = bkg_bcb_id AND bkg_status IN (3,5,6,7) AND bcb_lock_vendor_payment = 1 AND bkg_pickup_date >= '2020-04-01 00:00:00'
				INNER JOIN booking_invoice ON bkg_id = biv_bkg_id
				INNER JOIN vendors ON vnd_id = bcb_vendor_id $code
				LEFT JOIN agents ON agt_id = bkg_agent_id
                INNER JOIN booking_log blg ON  blg.blg_booking_id = bkg_id AND blg.blg_event_id=136
				GROUP BY bcb_id";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['bcb_id'], 'defaultOrder' => 'bcb_id DESC'],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	/*
	 * This function is used in margin Percentage report in general report section
	 * param output dataprovider
	 */

	public function getVendorLockedPaymentsExport()
	{
		$vndcode = $this->vnd_code;
		if($vndcode != '')
		{
			$code .= " AND vendors.vnd_code LIKE '%$vndcode%'";
		}
		$sql	 = "SELECT GROUP_CONCAT(bkg_id SEPARATOR ' | ') as bkg_ids, bcb_id, GROUP_CONCAT(agt_company SEPARATOR ' | ') as agt_company_names,
				GROUP_CONCAT(bkg_pickup_date SEPARATOR ' | ') as bkg_pickup_dates, vnd_id, vnd_name, vnd_code, bcb_vendor_amount, blg_desc
				FROM booking_cab
				INNER JOIN booking ON bcb_id = bkg_bcb_id AND bkg_status IN (3,5,6,7) AND bcb_lock_vendor_payment = 1 AND bkg_pickup_date >= '2020-04-01 00:00:00'
				INNER JOIN booking_invoice ON bkg_id = biv_bkg_id
				INNER JOIN vendors ON vnd_id = bcb_vendor_id $code
				LEFT JOIN agents ON agt_id = bkg_agent_id
				INNER JOIN booking_log blg ON  blg.blg_booking_id = bkg_id AND blg.blg_event_id=136
				GROUP BY bcb_id
				ORDER BY bcb_id DESC";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		return $result;
	}

	/**
	 *
	 * @param type $bkgId
	 * @param type $vendorAmount
	 * @return type
	 * @throws Exception
	 */
	public static function getModelForGNowFromVendorAmount($bkgId, $vendorAmount)
	{
		$model		 = Booking::model()->findByPk($bkgId);
		$platform	 = $model->bkgTrail->bkg_platform;
		if(!$model)
		{
			throw new Exception("Invalid booking", ReturnSet::ERROR_INVALID_DATA);
		}
		if($model->bkgPref->bkg_is_gozonow != 1)
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$carType = $model->bkg_vehicle_type_id;

		$quotes				 = [];
		$quotedVendorAmount	 = $model->bkgInvoice->bkg_quoted_vendor_amount;
		$quotes				 = Quote::populateFromModel($model, $carType, false, false, false, $vendorAmount);
		$quote				 = $quotes[$carType];
		$model->populateFromQuote($quote);
		if($platform == Booking::Platform_Admin)
		{
			$model->bkgInvoice->processAdminFee(false);
			$model->bkgInvoice->bkg_discount_amount = 0;
		}
		$model->bkgInvoice->calculateTotal();
		$model->bkgInvoice->bkg_quoted_vendor_amount = $quotedVendorAmount;
		$model->bkgBcb->bcb_vendor_amount			 = $model->bkgInvoice->bkg_vendor_amount;
		return $model;
	}

	/**
	 *
	 * @param type $bkgId
	 * @param type $vendorAmount
	 * @return boolean
	 * @throws Exception
	 */
	public static function processForGNowFromVendorAmount($bkgId, $vendorAmount, $vndId)
	{
		$model = BookingSub::getModelForGNowFromVendorAmount($bkgId, $vendorAmount);

		$transaction = DBUtil::beginTransaction();
		try
		{
			if($model->save() && $model->bkgBcb->save() && $model->bkgInvoice->save())
			{
				DBUtil::commitTransaction($transaction);
				$userInfo	 = UserInfo::getInstance();
				$vndModel	 = Vendors::model()->findByPk($vndId);
				$vendorName	 = $vndModel->vnd_name;
				$desc		 = "Consumer accepted offer from $vendorName, amount: &#x20B9;$vendorAmount";
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog::VENDOR_OFFER_ACCEPTED, false);
				$title		 = 'Bid accepted';
				$message	 = "Congratulations, your bid for the Trip ID: $model->bkg_bcb_id has been accepted by the customer.";
				$success	 = AppTokens::notifyVendorGnowBooking($vndId, $model->bkgBcb->bcb_id, $message, $title);

				return $model;
			}
			else
			{
				throw new Exception(json_encode($model->bkgInvoice->getErrors()));
			}
		}
		catch(Exception $e)
		{
			echo json_encode($e->getMessage());
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($e);
			return false;
		}
	}

	/**
	 *
	 * @param type $bkgId
	 * @param type $vendorAmount
	 * @return boolean
	 * @throws Exception
	 */
	public static function processForManualGNowFromVendorAmount($model)
	{


		$transaction = DBUtil::beginTransaction();
		try
		{
			$bkgId = $model->bkg_id;
			BookingCab::assignPreferredVendorDriverCab($model->bkg_bcb_id);

			DBUtil::commitTransaction($transaction);

			return $model;
		}
		catch(Exception $e)
		{
			echo json_encode($e->getMessage());
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($e);
			return false;
		}
	}

	public function getPastBookings($fromDate, $toDate)
	{
		if($fromDate != null && $toDate != null)
		{
			$sql = "SELECT

				'$toDate' as lastRefeshDate,

                IF(total_book>0,total_book,0) as total_book,
                IF(total_book_local>0,total_book_local,0) as total_book_local,
                IF(total_book_outstation>0,total_book_outstation,0) as total_book_outstation,
                
                IF(total_mmtb2b>0,total_mmtb2b,0) as total_mmtb2b,
                IF(total_mmtb2b_local>0,total_mmtb2b_local,0) as total_mmtb2b_local,
                IF(total_mmtb2b_outstation>0,total_mmtb2b_outstation,0) as total_mmtb2b_outstation,

                IF(total_ibibob2b>0,total_ibibob2b,0) as total_ibibob2b,
                IF(total_ibibob2b_local>0,total_ibibob2b_local,0) as total_ibibob2b_local,
                IF(total_ibibob2b_outstation>0,total_ibibob2b_outstation,0) as total_ibibob2b_outstation,

                IF(total_b2b>0,total_b2b,0) as total_b2b,
                IF(total_b2b_local>0,total_b2b_local,0) as total_b2b_local,
                IF(total_b2b_outstation>0,total_b2b_outstation,0) as total_b2b_outstation,

				IF(total_b2c>0,total_b2c,0) as total_b2c,
				IF(total_b2c_local>0,total_b2c_local,0) as total_b2c_local,
				IF(total_b2c_outstation>0,total_b2c_outstation,0) as total_b2c_outstation,

                IF(total_gozoSuttleb2c>0,total_gozoSuttleb2c,0) as total_gozoSuttleb2c,
                IF(total_b2c>0,total_b2c,0) as total_b2c,
                IF(total_b2c_user>0,total_b2c_user,0) as total_b2c_user,
				IF(total_b2c_other>0,total_b2c_other,0) as total_b2c_other,
				IF(total_b2c_gn>0,total_b2c_gn,0) as total_b2c_gn,
				IF(total_b2c_adminAssisted>0,total_b2c_adminAssisted,0) as total_b2c_adminAssisted,
                IF(total_b2c_admin>0,total_b2c_admin,0) as total_b2c_admin,
				IF(total_b2c_admin_gn>0,total_b2c_admin_gn,0) as total_b2c_admin_gn,
                IF(total_b2c_quot>0,total_b2c_quot,0) as total_b2c_quot,
                IF(total_b2c_quot_crt>0,total_b2c_quot_crt,0) as total_b2c_quot_crt,
                IF(total_b2c_unv>0,total_b2c_unv,0) as total_b2c_unv
                FROM (
                    SELECT

                    SUM(IF(bkg_status IN  (2,3,5,6,7,9),1,0)) as total_book,
                    SUM(IF(bkg_status  IN (2,3,5,6,7,9) AND bkg_booking_type IN (4,9,10,11,12,14,15,16),1,0)) as total_book_local,
                    SUM(IF(bkg_status  IN (2,3,5,6,7,9) AND bkg_booking_type NOT IN (4,9,10,11,12,14,15,16),1,0)) as total_book_outstation,

                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_agent_id=450),1,0)) as total_mmtb2b,
                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type IN (4,9,10,11,12,14,15,16) AND bkg_agent_id=450),1,0)) as total_mmtb2b_local,
                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) AND bkg_agent_id=450),1,0)) as total_mmtb2b_outstation,

                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_agent_id=18190),1,0)) as total_ibibob2b,
                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type IN (4,9,10,11,12,14,15,16) AND bkg_agent_id=18190),1,0)) as total_ibibob2b_local,
                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) AND bkg_agent_id=18190),1,0)) as total_ibibob2b_outstation,

                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_agent_id NOT IN(450,18190) AND bkg_agent_id IS NOT NULL),1,0)) as total_b2b,
                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type IN (4,9,10,11,12,14,15,16) AND bkg_agent_id NOT IN(450,18190) AND bkg_agent_id IS NOT NULL),1,0)) as total_b2b_local,
                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) AND bkg_agent_id NOT IN(450,18190) AND bkg_agent_id IS NOT NULL),1,0)) as total_b2b_outstation,

					SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL),1,0)) as total_b2c,
					SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type IN (4,9,10,11,12,14,15,16) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL),1,0)) as total_b2c_local,
					SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type NOT IN (4,9,10,11,12,14,15,16) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL),1,0)) as total_b2c_outstation,

                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type=7),1,0)) as total_gozoSuttleb2c,
                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL AND bkg_create_user_type=1 AND bkg_confirm_user_type=1),1,0)) as total_b2c_user,
                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL AND (bkg_create_user_type=1 AND bkg_confirm_user_type<>1) ),1,0)) as total_b2c_other,
					SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL AND (bkg_create_user_type=1 AND bkg_confirm_user_type IS NULL) ),1,0)) as total_b2c_gn,
					SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL AND bkg_create_user_type=4 AND bkg_confirm_user_type=1),1,0)) as total_b2c_adminAssisted,
                    SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL AND (bkg_create_user_type=4 AND bkg_confirm_user_type=4) ),1,0)) as total_b2c_admin,
					SUM(IF((bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type<>7 AND bkg_agent_id IS NULL AND (bkg_create_user_type=4 AND bkg_confirm_user_type IS NULL) ),1,0)) as total_b2c_admin_gn,
                    SUM(IF((bkg_status IN (15,9) AND bkg_agent_id IS NULL),1,0)) as total_b2c_quot,
                    SUM(IF((bkg_create_type IN (1) AND bkg_agent_id IS NULL),1,0)) as total_b2c_quot_crt,
                    SUM(IF((bkg_status IN (1) AND bkg_agent_id IS NULL),1,0)) as total_b2c_unv
                    FROM(
                        SELECT DISTINCT booking.bkg_id,booking.bkg_status,bkg_agent_id,bkg_create_type,bkg_booking_type,bkg_confirm_type,bkg_confirm_user_type, bkg_create_user_type
                        FROM `booking`
                        INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
			WHERE booking.bkg_active=1 AND bkg_reconfirm_flag=1 AND (booking.bkg_status IN (1,2,3,4,5,6,7,15) OR (  (booking.bkg_create_date BETWEEN '$fromDate' AND '$toDate') AND booking.bkg_status IN (9) AND TIMESTAMPDIFF(HOUR,bkg_create_date,btr_cancel_date)>=8 ))
                        AND (booking.bkg_create_date BETWEEN '$fromDate' AND '$toDate')
                    )a
                )a2";
			return DBUtil::queryRow($sql, DBUtil::SDB3());
		}
		else
		{
			return array();
		}
	}

	public static function isAirportDestination($bkgId)
	{
		$sql = "SELECT cty_is_airport FROM booking INNER JOIN cities ON cities.cty_id = booking.bkg_to_city_id WHERE bkg_id= $bkgId";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	/**
	 * driver not logged in 2 hour before pickup.
	 */
	public static function penaltyDriverNotLoggedIn()
	{
		$sql	 = "SELECT bkg.bkg_pickup_date,bkg.bkg_id,bkg_booking_id,bcb.bcb_id,bcb.bcb_driver_id,bcb.bcb_vendor_id
					FROM booking bkg
				INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id
				WHERE
					bkg.bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(),INTERVAL 2 HOUR)
                AND bkg.bkg_status = 5";
		$records = DBUtil::query($sql);
		foreach($records as $row)
		{
			try
			{
				$loggedIn = AppTokens::isDriverLoggedIn($row['bcb_driver_id']);
				if(!$loggedIn)
				{
					$penaltyType	 = PenaltyRules::PTYPE_DRIVER_NOT_LOGGED_IN;
					$prows			 = PenaltyRules::getValueByPenaltyType($penaltyType);
					$penaltyAmount	 = $prows['plt_value'];

					$model = Booking::model()->findByPk($row['bkg_id']);
					if($penaltyAmount > 0)
					{
						$bkgID			 = $row['bkg_id'];
						$bkg_booking_id	 = $row['bkg_booking_id'];
						$vendorId		 = $row['bcb_vendor_id'];
						$remarks		 = "Driver not logged in 2 hours before pickup time ($bkg_booking_id)";
						$result			 = AccountTransactions::checkAppliedPenaltyByType($bkgID, $penaltyType);
						if($result)
						{
							AccountTransactions::model()->addVendorPenalty($bkgID, $vendorId, $penaltyAmount, $remarks, '', $penaltyType);
						}
					}
				}
			}
			catch(Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return driver/vehicle id by giving bkg_id respective whether he/she is active or not
	 * @param type $bkgId
	 * @return queryRow Array
	 */
	public static function getDriverCabDetailsByBkgId($bkgId)
	{
		$sql = "SELECT
				booking.bkg_id,
				booking_cab.bcb_driver_id,
				booking_cab.bcb_cab_id
				FROM `booking`
				INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 AND booking.bkg_active = 1
				WHERE booking.bkg_id =:bkgId";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['bkgId' => $bkgId]);
	}

	public static function getIdListByDriverId($drvId, $qry = [])
	{
		$lastStartTime = BookingCab::showLastStartTime($drvId);

		if($lastStartTime != null || $lastStartTime != "")
		{
			$con = " AND bkg_pickup_date >= '$lastStartTime'";
		}
		$sql = "SELECT bkg.bkg_id
				FROM `booking_cab` bcb
				INNER JOIN `booking` bkg ON bcb.bcb_id = bkg.bkg_bcb_id AND bkg.bkg_status = 5
				AND bcb.bcb_active = 1 AND bkg.bkg_active = 1
				INNER JOIN booking_trail btr ON bkg.bkg_id = btr.btr_bkg_id
				INNER JOIN booking_track ON bkg.bkg_id = booking_track.btk_bkg_id AND booking_track.bkg_ride_complete = 0
				WHERE bcb.bcb_driver_id =:drvId
				AND bkg.bkg_pickup_date > DATE_SUB(NOW(),INTERVAL 1 MONTH) $con
				ORDER BY bkg.bkg_pickup_date ";
		return DBUtil::query($sql, DBUtil::SDB(), ['drvId' => $drvId]);
	}

	public static function populateAssignedListForDriver($entId)
	{
		$returnSet		 = new ReturnSet();
		$bkgIdDataList	 = BookingSub::getIdListByDriverId($entId);
		$count			 = count($bkgIdDataList);

		if(!$bkgIdDataList)
		{
			throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		//$bkgIdDataList	 = explode(',', $bkgIdData);
		$datalist	 = [];
		$view		 = 'driver';
		try
		{
			$success = false;
			foreach($bkgIdDataList as $bkgId)
			{
				$bkgId		 = $bkgId['bkg_id'];
				$objBooking	 = \Beans\Booking::setDataById($bkgId, $view, null, true);

				$datalist[] = $objBooking;
			}
			if(empty($datalist))
			{
				throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($datalist);
			
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public static function getServedIdListByEntity($entId, $entType, $qry = [])
	{
		$pageSize	 = (isset($qry['pageSize'])) ? $qry['pageSize'] : 10;
		$pageNo		 = (isset($qry['pageNo'])) ? $qry['pageNo'] : 0;
		$offSet		 = $pageNo * $pageSize;

		$where = '';
		if($entType == UserInfo::TYPE_VENDOR)
		{
			$where = " AND bcb.bcb_vendor_id =:entId ";
		}
		if($entType == UserInfo::TYPE_DRIVER)
		{
			$where = " AND bcb.bcb_driver_id =:entId ";
		}
		$where	 .= "AND (bkg.bkg_status IN (6,7) 
						OR (booking_track.bkg_ride_complete = 1 AND bkg.bkg_status IN (5,6,7))) 
							 ";
		$params	 = ['entId' => $entId];
		$sql	 = "SELECT  bkg.bkg_id
				FROM `booking_cab` bcb
				INNER JOIN `booking` bkg ON bcb.bcb_id = bkg.bkg_bcb_id
					AND bcb.bcb_active = 1
					AND bkg.bkg_active = 1
				INNER JOIN booking_trail btr ON bkg.bkg_id = btr.btr_bkg_id
				INNER JOIN booking_track ON bkg.bkg_id = booking_track.btk_bkg_id
				WHERE bkg.bkg_pickup_date > DATE_SUB(NOW(),INTERVAL 1 MONTH)
				AND bcb.bcb_start_time IS NOT NULL
				$where
				ORDER BY bkg.bkg_pickup_date DESC
				LIMIT {$offSet},{$pageSize}";
		return DBUtil::command($sql, DBUtil::SDB())->queryColumn($params);
	}

	public static function getTotalServedIdList($entId, $entType, $qry = [])
	{
		$pageSize	 = (isset($qry['pageSize'])) ? $qry['pageSize'] : 10;
		$pageNo		 = (isset($qry['pageNo'])) ? $qry['pageNo'] : 0;
		$offSet		 = $pageNo * $pageSize;

		$where = '';
		if($entType == UserInfo::TYPE_VENDOR)
		{
			$where = " AND bcb.bcb_vendor_id =:entId ";
		}
		if($entType == UserInfo::TYPE_DRIVER)
		{
			$where = " AND bcb.bcb_driver_id =:entId ";
		}
		//$where	 .= "AND (bkg.bkg_status IN (5,6,7))";
		$params	 = ['entId' => $entId];
		$sql	 = "SELECT  bkg.bkg_id
				FROM `booking_cab` bcb
				INNER JOIN `booking` bkg ON bcb.bcb_id = bkg.bkg_bcb_id
					AND bcb.bcb_active = 1
					AND bkg.bkg_active = 1
				INNER JOIN booking_trail btr ON bkg.bkg_id = btr.btr_bkg_id 
				INNER JOIN booking_track ON bkg.bkg_id = booking_track.btk_bkg_id AND booking_track.bkg_ride_complete = 1
				WHERE bkg.bkg_pickup_date > DATE_SUB(NOW(),INTERVAL 1 MONTH)
				AND bcb.bcb_start_time IS NOT NULL
				AND bkg.bkg_status IN (5,6,7) $where
				ORDER BY bkg.bkg_pickup_date DESC
				LIMIT {$offSet},{$pageSize}";

		return DBUtil::command($sql, DBUtil::SDB())->queryColumn($params);
	}

	public static function populateServedIdListByEntity($entId, $entType, $qry = [])
	{

		$returnSet	 = new ReturnSet();
		$bkgIdList	 = BookingSub::getServedIdListByEntity($entId, $entType, $qry);
		if(count($bkgIdList) == 0)
		{
			throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}

		$datalist = [];
		if($entType == UserInfo::TYPE_DRIVER)
		{
			$view = 'driver';
		}
		try
		{
			$hideDocDetails = true;
			foreach($bkgIdList as $bkgid)
			{
//				$bkgid = $bkgid1['bkg_id'];
				$objBooking = \Beans\Booking::setDataById($bkgid, $view, null, $hideDocDetails);

				$datalist[] = $objBooking;
			}
			$returnSet->setStatus(true);
			$returnSet->setData($datalist);
		}
		catch(Exception $ex)
		{

			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public static function populateGetServedIdListByTrip($vndId, $entType, $qry = [])
	{
		$returnSet	 = new ReturnSet();
		$bkgIdList	 = BookingSub::getTotalServedIdList($vndId, $entType, $qry);
		if(count($bkgIdList) == 0)
		{
			throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}

		$datalist = [];
		if($entType == UserInfo::TYPE_DRIVER)
		{
			$view = 'driver';
		}
		try
		{
			foreach($bkgIdList as $bkgid)
			{
//				$bkgid = $bkgid1['bkg_id'];
				$objBooking = \Beans\Booking::setDataById($bkgid, $view);

				$datalist[] = $objBooking;
			}
			$returnSet->setStatus(true);
			$returnSet->setData($datalist);
		}
		catch(Exception $ex)
		{

			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public static function getListForDCO($entType, $entId, $listType, $pageCount = 0, $offSetCount = 20)
	{
		if($entType = UserInfo::TYPE_VENDOR)
		{
			$vendorId = $entId;
		}
		$vndInfo = VendorPref::getInfoById($vendorId);

		$row		 = AccountTransDetails::getTotTransByVndId($vendorId);
		$carTypeRes	 = VehicleTypes::vendorCabType($vendorId);
		$carType	 = $carTypeRes['vhcLabelId'];
		$car_arr	 = explode(",", $carType);

		$carValArr	 = BookingVendorRequest::carAccess($car_arr);
		$carVal		 = implode(",", $carValArr);
		#print_r($carVal);exit;

		$totTrans						 = ($row['totTrans'] > 0) ? $row['totTrans'] : 0;
		$vndIsGozonowEnabled			 = ($row['vnp_gozonow_enabled'] < 2) ? 1 : 0;
		$vndIsFreeze					 = ($row['vnd_is_freeze'] > 0) ? $row['vnd_is_freeze'] : 0;
		$vndCodFreeze					 = ($row['vnd_cod_freeze'] > 0) ? $row['vnd_cod_freeze'] : 0;
		$vndInfo['vnp_accepted_zone']	 = ($vndInfo['vnp_accepted_zone'] == '') ? -1 : trim($vndInfo['vnp_accepted_zone'], ',');
		$vndInfo['vnp_excluded_cities']	 = ($vndInfo['vnp_excluded_cities'] == '') ? -1 : trim($vndInfo['vnp_excluded_cities'], ',');
		$vndInfo['vnp_home_zone']		 = ($vndInfo['vnp_home_zone'] == '') ? -1 : trim($vndInfo['vnp_home_zone'], ',');
		$vndBoostEnable					 = ($vndInfo['vnp_boost_enabled'] > 0) ? $vndInfo['vnp_boost_enabled'] : 0;
		$vendorStatus					 = $vndInfo['vnd_active'];

		$vndStatInfo = VendorStats::model()->fetchMetric($vendorId);

		$vndRating		 = ($vndStatInfo['vrs_vnd_overall_rating'] == null) ? 0 : $vndStatInfo['vrs_vnd_overall_rating'];
		$vndStickyScr	 = ($vndStatInfo['vrs_sticky_score'] == null) ? 4 : $vndStatInfo['vrs_sticky_score'];
		$vndPenaltyCount = $vndStatInfo['vrs_penalty_count'];
		$vndDriverApp	 = $vndStatInfo['vrs_driver_app_used'];
		$vndDependency	 = ($vndStatInfo['vrs_dependency'] == null || $vndStatInfo['vrs_dependency'] == '') ? 0 : $vndStatInfo['vrs_dependency'];
		$vndBoostPercent = ($vndStatInfo['vrs_boost_percentage'] == null) ? 0 : $vndStatInfo['vrs_boost_percentage'];

		$acptBidPercent = ($vndBoostEnable > 0) ? (5 - $vndBoostPercent * 0.01 * 2) : 5;

		$sqlServiceZone	 = "SELECT hsz_service_id FROM home_service_zones WHERE hsz_home_id IN ({$vndInfo['vnp_home_zone']})";
		$query			 = "AND ((zct.zct_zon_id IN ($sqlServiceZone) AND zct.zct_cty_id NOT IN (" . $vndInfo['vnp_excluded_cities'] . ")) OR zct.zct_zon_id IN (" . $vndInfo['vnp_home_zone'] . "))";

		if($vndIsFreeze > 0 && $row['vnp_low_rating_freeze'] <= 0 && $row['vnp_doc_pending_freeze'] <= 0 && $row['vnp_manual_freeze'] <= 0)
		{
			$vndIsFreeze = 0;
		}
		if($vndInfo['vnd_cat_type'] == 1)
		{
			// Check DCO bookings to be excluded if he is already assigned with another trip at the same time
			$sql			 = "SELECT GROUP_CONCAT(bkgnew.bkg_id)
					FROM   booking_cab bcb
					INNER JOIN booking ON  bcb.bcb_id = booking.bkg_bcb_id AND bkg_status IN (3, 5, 6, 7) AND bcb_vendor_id = $vendorId AND (bcb_start_time > NOW() OR bcb_end_time > NOW())
					INNER JOIN booking_cab bcbnew ON (bcbnew.bcb_start_time BETWEEN bcb.bcb_start_time AND bcb.bcb_end_time OR bcbnew.bcb_end_time BETWEEN bcb.bcb_start_time AND bcb.bcb_end_time)
					INNER JOIN booking bkgnew ON bkgnew.bkg_bcb_id = bcbnew.bcb_id AND bkgnew.bkg_status = 2 AND bkgnew.bkg_from_city_id
					INNER JOIN zone_cities zct ON zct.zct_cty_id = bkgnew.bkg_from_city_id
					WHERE  bcb.bcb_active = 1 AND bcbnew.bcb_active = 1 $query";
			$excludeBookings = DBUtil::querySCalar($sql, DBUtil::SDB());
		}
		$excludeBookings = ($excludeBookings == null || $excludeBookings == '') ? -1 : $excludeBookings;

		/** According to discussion on 24/08/22 with AK & KG in pending list all types of booking will not shown except service added with vendor* */
		/** According to AK for in case of airport booking if vendor dependency score>60 then able accept booking if  airport service not added to that vendor * */
		if($vndInfo['vnp_oneway'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(1)";
		}
		if($vndInfo['vnp_round_trip'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(2)";
		}
		if($vndInfo['vnp_multi_trip'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(3)";
		}
		/* if ($vndInfo['vnp_airport'] != 1 &&  $vndDependency < 60)
		  {
		  $condSelectProfile .= " AND booking.bkg_booking_type NOT IN(4,12)";
		  } */
		if($vndInfo['vnp_package'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(5)";
		}
		if($vndInfo['vnp_flexxi'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(6)";
		}
		if($vndInfo['vnp_daily_rental'] != 1)
		{
			$condSelectProfile .= " AND booking.bkg_booking_type NOT IN(9,10,11)";
		}



		$condSelectTierCheck		 = " AND service_class.scc_id IN ({$vndInfo['vnp_is_allowed_tier']}) ";
		$condSelectTierCheckMatched	 = " AND scc.scc_id IN ({$vndInfo['vnp_is_allowed_tier']}) ";

		$allowedZones = implode(',', array_filter(explode(',', $vndInfo['vnp_accepted_zone'] . ',' . $vndInfo['vnp_home_zone'])));

		$search_qry .= "  AND ((fzc.zct_zon_id IN ({$sqlServiceZone}) OR tzc.zct_zon_id IN ({$sqlServiceZone}))
							AND (fzc.zct_cty_id NOT IN ({$vndInfo['vnp_excluded_cities']}) OR tzc.zct_cty_id NOT IN ({$vndInfo['vnp_excluded_cities']}))
							AND  booking.bkg_id NOT IN($excludeBookings)
						)";

//		if ($filterModel->sort == 'newestBooking')
//		{
//			$sortCond = "ORDER BY bkgIds DESC,isGozoNow DESC";
//		}
//		if ($filterModel->sort == 'earliestBooking')
//		{
//			$sortCond = "ORDER BY bkg_pickup_date ASC";
//		}

		$limitCond = "LIMIT $pageCount, $offSetCount";

		if(1 == 1)
		{
			$bidStatus	 = 0;
			$filter_qry	 = "";
			if($bidStatus == 1)
			{

				$filter_qry .= " AND (bvr_id IS NOT NULL AND bvr_accepted = 1 AND bvr_vendor_id = $vendorId)";
			}
			$serviceType = 'all';
			if($serviceType == 'local')
			{
				$filter_qry .= " AND booking.bkg_booking_type IN(4,9,10,11,12,15)";
			}
			if($serviceType == 'outstation')
			{
				$filter_qry .= " AND booking.bkg_booking_type IN(1,2,3,5,6,7,8)";
			}
			if($serviceType == 'all')
			{
				$filter_qry .= " AND booking.bkg_booking_type IN(1,2,3,5,6,7,8,4,9,10,11,12,15)";
			}


			$filter_qry .= " AND booking.bkg_pickup_date > DATE_SUB(NOW(), INTERVAL 1 DAY) ";
		}

		$val		 = '"';
		$sortCond	 = "ORDER BY bkg_pickup_date ASC";

		$acceptBidPercent	 = "GetVendorAcceptMargin2('{$vndInfo['vnp_home_zone']}', '{$vndInfo['vnp_accepted_zone']}', GROUP_CONCAT(DISTINCT fzc.zct_zon_id), GROUP_CONCAT(DISTINCT tzc.zct_zon_id), $vndBoostEnable, $vndBoostPercent, IFNULL(bkg_critical_score,0.65),IFNULL(btr_is_dem_sup_misfire,0))";
		//$acceptBidPercent	 = "GetVendorAcceptMargin1('{$vndInfo['vnp_home_zone']}', '{$vndInfo['vnp_accepted_zone']}', GROUP_CONCAT(DISTINCT fzc.zct_zon_id), GROUP_CONCAT(DISTINCT tzc.zct_zon_id), $vndBoostEnable, $vndBoostPercent, IFNULL(bkg_critical_score,0.65))";
		$acceptableAmount	 = "ROUND(booking_cab.bcb_vendor_amount * 0.01 * $acceptBidPercent)";
		/* $lowSMTAmount is used when smt score is less than 0 */
		$lowSMTAmount		 = "ROUND(booking_cab.bcb_vendor_amount * 0.01 * ($acceptBidPercent - 5))";

		$calculateSMTSql = "CalculateSMT(bcb_vendor_amount + SUM(bkg_gozo_amount),booking_cab.bcb_vendor_amount,
					    $acceptableAmount, $vndRating, $vndStickyScr, $vndPenaltyCount, $vndDriverApp, $vndDependency, $vndBoostPercent)";

		$isAcceptAllowed			 = "IsDirectAcceptAllowed('{$vndInfo['vnp_home_zone']}', GROUP_CONCAT(DISTINCT fzc.zct_zon_id), GROUP_CONCAT(DISTINCT tzc.zct_zon_id), bkg_manual_assignment, $calculateSMTSql, bkg_critical_score, MIN(booking.bkg_pickup_date), GREATEST(IFNULL(bcb_bid_start_time, MAX(bkg_confirm_datetime)), MAX(bkg_confirm_datetime)))";
		$validateAcceptableAmountSQL = "IF(bkg_critical_assignment=1 OR bkg_manual_assignment=1 OR booking.bkg_booking_type = 12, ROUND(booking_cab.bcb_vendor_amount * 0.98), IF($calculateSMTSql>=0, $acceptableAmount, $lowSMTAmount))";

		$calRecomendedAmount = "IF(bkg_critical_assignment=1 OR bkg_manual_assignment=1 , ROUND(booking_cab.bcb_vendor_amount * 0.98), ROUND(booking_cab.bcb_vendor_amount * 0.98))";

		$showBookingCnd = " AND booking.bkg_status=2 AND (booking.bkg_reconfirm_flag=1 OR booking_pref.bkg_is_gozonow=1 ) ";

		// Temporary Table
		$randomNumber	 = rand();
		$createTempTable = "tmpbvr_{$vendorId}_{$randomNumber}";
		DBUtil::dropTempTable($createTempTable);

		$sqlTemp = " (INDEX index_tmpbvr (bvr_bcb_id)) (
						SELECT bvr_id, bvr_accepted, bvr_vendor_id, bvr_bcb_id, bvr_bid_amount FROM booking
						INNER JOIN booking_vendor_request ON bvr_bcb_id = bkg_bcb_id
						AND bvr_active = 1 AND bkg_status = 2
						AND bvr_accepted <> 2
						AND bvr_vendor_id = $vendorId $filter_qry
					) ";
		DBUtil::createTempTable($createTempTable, $sqlTemp);
		$sqlMain = "SET STATEMENT max_statement_time=10 FOR
			SELECT
			    0 AS matchType,
			    IF(booking_pref.bkg_cng_allowed =1 AND (bkgaddinfo.bkg_num_large_bag < 2  OR bkgaddinfo.bkg_num_large_bag >1 ) ,1,0 ) AS is_cng_allowed,
			    IF(booking.bkg_booking_type IN(4,9,10,11,12,15), 'local', 'outstation')AS businesstype,
			    IF(booking.bkg_flexxi_type IN(1,2),true,false) isFlexxi,
			    bcb_id,booking.bkg_create_date,booking.bkg_trip_distance,booking.bkg_trip_duration,booking.bkg_booking_type,booking.bkg_status,booking.bkg_route_city_names as bkg_route_name,
			    GROUP_CONCAT(DISTINCT bkg_id) bkgIds,
			    GROUP_CONCAT(DISTINCT bkg_booking_id) bkgBookingIds,
			    trim(replace(replace(replace(replace(`bkg_route_city_names`,'[',''),']',''),'$val,',' -'),'$val','')) AS bkg_route_name,vehicle_types.vht_make,vehicle_types.vht_model,
			    bcb_bid_start_time, btr.btr_manual_assign_date, btr.btr_critical_assign_date,
			    GREATEST(COALESCE(bcb_bid_start_time,0), COALESCE(btr_manual_assign_date,0), COALESCE(btr_critical_assign_date,0)) as booking_priority_date,
			    CASE bkg_booking_type WHEN 1 THEN IF(bcb_trip_type=1,'MATCHED','ONE WAY') WHEN 2 THEN 'ROUND TRIP' WHEN 3 THEN 'MULTI WAY' WHEN 4 THEN 'ONE WAY' WHEN 5 THEN 'PACKAGE' WHEN 8 THEN 'PACKAGE' WHEN 9 THEN 'DAY RENTAL 4hr-40km' WHEN 10 THEN 'DAY RENTAL 8hr-80km' WHEN 11 THEN 'DAY RENTAL 12hr-120km' WHEN 12 THEN 'Airport Packages' WHEN 15 THEN 'Local Transfers' ELSE 'SHARED' END AS booking_type,
			    IF((booking.bkg_booking_type IN (4,12)) OR ($isAcceptAllowed AND booking.bkg_reconfirm_flag=1 AND bkg_block_autoassignment=0), IF(bkg_status IN (3,5), 1,IF($vendorStatus=2,1,0)),1) AS is_biddable,
			    IF(booking.bkg_agent_id > 0, 1, 0) AS is_agent,
			     vehicle_category.vct_label AS cab_model,
			    $calRecomendedAmount AS recommended_vendor_amount,
				service_class.scc_id as cab_lavel_id,
			    service_class.scc_label AS cab_lavel,
				 biv.bkg_promo1_id,biv.bkg_promo1_code,biv.bkg_promo2_id,biv.bkg_promo2_code,biv.bkg_discount_amount,biv.bkg_discount_amount,
			    booking_cab.bcb_vendor_amount AS max_bid_amount,
				(booking_cab.bcb_vendor_amount * 0.9) AS minAllowableVendorAmount,
				IF(bcb_max_allowable_vendor_amount>0,bcb_max_allowable_vendor_amount,(booking_cab.bcb_vendor_amount+(bkg_gozo_amount-bkg_credits_used))) AS maxAllowableVendorAmount,
			    (booking_cab.bcb_vendor_amount * 0.7) AS min_bid_amount,
			    IF(booking_cab.bcb_matched_type > 0, 1, 0) AS is_matched,
			    IF(vehicle_category.vct_id IN(5, 6),1,0) AS is_assured,
				IF(bkgaddinfo.bkg_no_person > 0,bkgaddinfo.bkg_no_person,vehicle_category.vct_capacity) AS seatingCapacity,
                IF(bkgaddinfo.bkg_num_large_bag > 0,bkgaddinfo.bkg_num_large_bag,vehicle_category.vct_big_bag_capacity) AS bigBagCapacity,
                IF(bkgaddinfo.bkg_num_small_bag > 0,bkgaddinfo.bkg_num_small_bag,vehicle_category.vct_small_bag_capacity) AS bagCapacity,
			    MIN(booking.bkg_pickup_date) bkg_pickup_date,
			    bkg_return_date,
				IF(bkg_is_gozonow = 1, 1, 0) AS isGozoNow,
				bkg_is_gozonow,
			    bcb_end_time trip_completion_time,
			    biv.bkg_total_amount,
				biv.bkg_quoted_vendor_amount as quoteVendorAmt,
			    $calculateSMTSql AS smtScore,
			    $validateAcceptableAmountSQL AS acceptAmount,
			    IFNULL(bvr_bid_amount,0) AS bvr_bid_amount,
			    (CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv.bkg_advance_amount <=(biv.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) AS payment_due,
					(
                    CASE (CASE WHEN (($vndIsFreeze = 1) AND ($totTrans > 0) AND biv.bkg_advance_amount <=(biv.bkg_total_amount * 0.3)) THEN '1' WHEN($vndIsFreeze = 1) THEN '2' ELSE '0' END) WHEN '1' THEN CONCAT(
                        'Your amount due is ',
                        ABS($totTrans),
                        '. Please send payment immediately'
                    ) WHEN '2' THEN 'Your Gozo Account is temporarily frozen. Please contact your Account Manager or Gozo Team to have it resolved.' WHEN '0' THEN ''
                    END
					) AS payment_msg,bkg_night_pickup_included,bkg_night_drop_included
			FROM booking
			INNER JOIN booking_cab ON bcb_id = bkg_bcb_id AND bcb_active = 1
			INNER JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id

			INNER JOIN booking_add_info bkgaddinfo ON  booking.bkg_id = bkgaddinfo.bad_bkg_id
			INNER JOIN booking_pref ON bpr_bkg_id = booking.bkg_id
			INNER JOIN booking_trail btr ON btr_bkg_id = booking.bkg_id
			INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
			LEFT JOIN vehicle_types ON vehicle_types.vht_id = scv.scv_model AND scv.scv_model > 0
			INNER JOIN vehicle_category ON vehicle_category.vct_id = scv.scv_vct_id
			INNER JOIN service_class ON service_class.scc_id = scv.scv_scc_id
			INNER JOIN booking_route ON brt_bkg_id = booking.bkg_id
			INNER JOIN cities ct1 ON ct1.cty_id = booking_route.brt_from_city_id
			INNER JOIN cities ct2 ON ct2.cty_id = booking_route.brt_to_city_id
			INNER JOIN zone_cities fzc ON fzc.zct_cty_id=ct1.cty_id AND fzc.zct_active=1
			INNER JOIN zone_cities tzc ON tzc.zct_cty_id=ct2.cty_id AND tzc.zct_active=1

			LEFT JOIN $createTempTable bvr ON bvr.bvr_bcb_id = bkg_bcb_id
			WHERE  bcb_active = 1
			 $filter_qry $condSelectProfile $condSelectTierCheck $showBookingCnd $search_qry
			GROUP BY bcb_id $sortCond  $limitCond";
		//Logger::trace("BookingCab::model()->getRevenueBreakup($tripId) :: " . json_encode($revenueDetails));
		Logger::info('SQL MAIN ===>' . $sqlMain);

		$data = DBUtil::query($sqlMain, DBUtil::SDB());

		DBUtil::dropTempTable($createTempTable);

		return $data;
	}

	/**
	 *
	 * @param type $reasonId
	 * @return type
	 */
	public static function getGNowBidDenyReasonList($reasonId = 0)
	{
		$denyReason = Config::get('booking.gozoNow.denyReason');

		$reasonList = CJSON::decode($denyReason);
		if($reasonId > 0)
		{
			return $reasonList[$reasonId];
		}
		return $reasonList;
	}

	/**
	 *
	 * @param int $bkgId
	 * @param int $vendorId
	 * @return boolean|string
	 */
	public static function getBKVNUrl($bkgId, $vendorId)
	{
		if($vendorId > 0)
		{
			$hashBkgId	 = Yii::app()->shortHash->hash($bkgId);
			$hashVndId	 = Yii::app()->shortHash->hash($vendorId);
			$bkvnUrl	 = Yii::app()->params['fullBaseURL'] . '/bkvn/' . $hashBkgId . '/' . $hashVndId;
			return $bkvnUrl;
		}
		return false;
	}

	public static function getUnverifiedForAutoCancel()
	{
		$sql = "SELECT bkg_id FROM booking
				INNER JOIN booking_invoice ON booking.bkg_id = booking_invoice.biv_bkg_id
				WHERE bkg_status = 1 AND bkg_pickup_date <= DATE_SUB(NOW(), INTERVAL 30 DAY) AND bkg_advance_amount <= 0 
				ORDER BY bkg_id DESC 
				LIMIT 0, 2000";

		$result = DBUtil::query($sql);

		return $result;
	}

	/**
	 * @internal Transferred from BI
	 * @param Booking $model
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function marginLastMinBooking($model, $command = DBUtil::ReturnType_Provider)
	{
		$sqlBkgType = "";
		if($model->bkg_booking_type != null)
		{
			$sqlBkgType = " AND bkg_booking_type IN ($model->bkg_booking_type)";
		}
		$sql		 = "SELECT
					COUNT(temp.bkg_id) AS TotalBookingCnt,
					CASE WHEN temp.bkg_booking_type = 1 THEN 'OW' WHEN temp.bkg_booking_type IN(2, 3) THEN 'RT/MT' WHEN temp.bkg_booking_type = 4 THEN 'AT' WHEN temp.bkg_booking_type = 5 THEN 'PT' WHEN temp.bkg_booking_type = 6 THEN 'FL' WHEN temp.bkg_booking_type = 7 THEN 'SH' WHEN temp.bkg_booking_type = 8 THEN 'CT' WHEN temp.bkg_booking_type = 9 THEN 'DR_4HR' WHEN temp.bkg_booking_type = 10 THEN 'DR_8HR' WHEN temp.bkg_booking_type = 11 THEN 'DR_12HR' WHEN temp.bkg_booking_type = 12 THEN 'AP' WHEN temp.bkg_booking_type = 14 THEN 'P2P'
				END AS tripType,
				CASE WHEN temp.diff_create_pickup < 1 THEN 'D00-D01' WHEN temp.diff_create_pickup >= 1 AND temp.diff_create_pickup < 2 THEN 'D01-D02' WHEN temp.diff_create_pickup >= 2 AND temp.diff_create_pickup < 3 THEN 'D02-D03' WHEN temp.diff_create_pickup >= 3 AND temp.diff_create_pickup < 4 THEN 'D03-D04' WHEN temp.diff_create_pickup >= 4 AND temp.diff_create_pickup < 5 THEN 'D04-D05' WHEN temp.diff_create_pickup >= 5 AND temp.diff_create_pickup < 6 THEN 'D05-D06' WHEN temp.diff_create_pickup >= 6 AND temp.diff_create_pickup < 7 THEN 'D06-D07' WHEN temp.diff_create_pickup >= 7 AND temp.diff_create_pickup < 8 THEN 'D07-D08' WHEN temp.diff_create_pickup >= 8 AND temp.diff_create_pickup < 9 THEN 'D08-D09' WHEN temp.diff_create_pickup >= 9 AND temp.diff_create_pickup < 10 THEN 'D09-D10' WHEN temp.diff_create_pickup >= 10 THEN 'D10+'
				END AS PickupBins,
				ROUND(
					(
						SUM(temp.Profit) / COUNT(temp.bkg_id)
					),
					2
				) AS Profit
				FROM
					(
					SELECT
						bkg_id,
						bkg_booking_type,
						ROUND(
							TIMESTAMPDIFF(
								MINUTE,
								bkg_create_date,
								bkg_pickup_date
							) / 60
						) AS diff_create_pickup,
						bkg_create_date AS bks_create_date,
						bkg_pickup_date AS bks_pickup_date,
						ROUND(
							(
								(
									(
										bkg_gozo_amount - IFNULL(bkg_credits_used, 0)
									) /(bkg_total_amount)
								) * 100
							),
							2
						) AS Profit
					FROM
						booking
					JOIN booking_cab ON booking.bkg_bcb_id = booking_cab.bcb_id AND bkg_active = 1 AND booking_cab.bcb_active = 1
					JOIN booking_invoice ON booking_invoice.biv_bkg_id = bkg_id
					WHERE
						1 AND bkg_pickup_date BETWEEN '$model->bkg_pickup_date1' AND '$model->bkg_pickup_date2' AND bkg_status IN(6, 7) $sqlBkgType
				) temp
				WHERE 1
				GROUP BY temp.bkg_booking_type,PickupBins";
		$sqlCount	 = "SELECT
								COUNT(1) AS cnt
							FROM
								(
								SELECT CASE WHEN
									temp.diff_create_pickup < 1 THEN 'D00-D01' WHEN temp.diff_create_pickup >= 1 AND temp.diff_create_pickup < 2 THEN 'D01-D02' WHEN temp.diff_create_pickup >= 2 AND temp.diff_create_pickup < 3 THEN 'D02-D03' WHEN temp.diff_create_pickup >= 3 AND temp.diff_create_pickup < 4 THEN 'D03-D04' WHEN temp.diff_create_pickup >= 4 AND temp.diff_create_pickup < 5 THEN 'D04-D05' WHEN temp.diff_create_pickup >= 5 AND temp.diff_create_pickup < 6 THEN 'D05-D06' WHEN temp.diff_create_pickup >= 6 AND temp.diff_create_pickup < 7 THEN 'D06-D07' WHEN temp.diff_create_pickup >= 7 AND temp.diff_create_pickup < 8 THEN 'D07-D08' WHEN temp.diff_create_pickup >= 8 AND temp.diff_create_pickup < 9 THEN 'D08-D09' WHEN temp.diff_create_pickup >= 9 AND temp.diff_create_pickup < 10 THEN 'D09-D10' WHEN temp.diff_create_pickup >= 10 THEN 'D10+'
							END AS PickupBins
							FROM
								(
								SELECT
									bkg_id,
									bkg_booking_type,
									ROUND(
										TIMESTAMPDIFF(
											MINUTE,
											bkg_create_date,
											bkg_pickup_date
										) / 60
									) AS diff_create_pickup,
									bkg_create_date AS bks_create_date,
									bkg_pickup_date AS bks_pickup_date,
									ROUND(
										(
											(
												(
													bkg_gozo_amount - IFNULL(bkg_credits_used, 0)
												) /(bkg_total_amount)
											) * 100
										),
										2
									) AS Profit
								FROM
									booking
								JOIN booking_cab ON booking.bkg_bcb_id = booking_cab.bcb_id AND bkg_active = 1 AND booking_cab.bcb_active = 1
								JOIN booking_invoice ON booking_invoice.biv_bkg_id = bkg_id
								WHERE
									1 AND bkg_pickup_date BETWEEN '$model->bkg_pickup_date1' AND '$model->bkg_pickup_date2' AND bkg_status IN(6, 7) $sqlBkgType
							) temp
							WHERE
								1
							GROUP BY
								temp.bkg_booking_type,
								PickupBins
							) a";

		if($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['TotalBookingCnt', 'tripType', 'Profit'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	/**
	 * @internal Transferred from BI
	 * @param Booking $model
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function bookingHistoryByCreateDate($model, $command = DBUtil::ReturnType_Provider)
	{
		$sql = "SELECT
				DATE(bkg_create_date) AS createDate,
				SUM(IF(bkg_status IN (1,2,3,4,5,6,7,9,15) AND booking.bkg_agent_id IN (450,18190),1,0)) AS AllCntMMT,
				SUM(IF(bkg_status IN (1) AND booking.bkg_agent_id IN (450,18190),1,0)) AS UnVerifiedCntMMT,
				SUM(IF(bkg_status IN (15) AND booking.bkg_agent_id IN (450,18190),1,0)) AS QuotedCntMMT,
				SUM(IF(bkg_status IN (2,3,5,6,7) AND booking.bkg_agent_id IN (450,18190),1,0)) AS ServedCntMMT,
				SUM(IF(bkg_status IN (9) AND booking.bkg_agent_id IN (450,18190) ,1,0)) AS CancelledCntMMT,
				SUM(IF(bkg_status IN (1,2,3,4,5,6,7,9,15) AND booking.bkg_agent_id IN (30228),1,0)) AS AllCntEMT,
				SUM(IF(bkg_status IN (1) AND booking.bkg_agent_id IN (30228),1,0)) AS UnVerifiedCntEMT,
				SUM(IF(bkg_status IN (15) AND booking.bkg_agent_id IN (30228),1,0)) AS QuotedCntEMT,
				SUM(IF(bkg_status IN (2,3,5,6,7) AND booking.bkg_agent_id IN (30228),1,0)) AS ServedCntEMT,
				SUM(IF(bkg_status IN (9) AND booking.bkg_agent_id IN (30228) ,1,0)) AS CancelledCntEMT,
				SUM(IF(bkg_status IN (1,2,3,4,5,6,7,9,15) AND booking.bkg_agent_id IN (34928),1,0)) AS AllCntSPICE,
				SUM(IF(bkg_status IN (1) AND booking.bkg_agent_id IN (34928),1,0)) AS UnVerifiedCntSPICE,
				SUM(IF(bkg_status IN (15) AND booking.bkg_agent_id IN (34928),1,0)) AS QuotedCntSPICE,
				SUM(IF(bkg_status IN (2,3,5,6,7) AND booking.bkg_agent_id IN (34928),1,0)) AS ServedCntSPICE,
				SUM(IF(bkg_status IN (9) AND booking.bkg_agent_id IN (34928) ,1,0)) AS CancelledCntSPICE,
				SUM(IF(bkg_status IN (1,2,3,4,5,6,7,9,15) AND bkg_agent_id NOT IN (34928,30228,450,18190),1,0)) AS AllCntB2B,
				SUM(IF(bkg_status IN (1) AND bkg_agent_id NOT IN (34928,30228,450,18190),1,0)) AS UnVerifiedCntB2B,
				SUM(IF(bkg_status IN (15) AND bkg_agent_id NOT IN (34928,30228,450,18190),1,0)) AS QuotedCntB2B,
				SUM(IF(bkg_status IN (2,3,5,6,7) AND bkg_agent_id NOT IN (34928,30228,450,18190),1,0)) AS ServedCntB2B,
				SUM(IF(bkg_status IN (9) AND bkg_agent_id NOT IN (34928,30228,450,18190),1,0)) AS CancelledCntB2B,
				SUM(IF(bkg_status IN (1,2,3,4,5,6,7,9,15) AND bkg_agent_id IS NULL,1,0)) AS AllCntB2C,
				SUM(IF(bkg_status IN (1) AND bkg_agent_id IS NULL,1,0)) AS UnVerifiedCntB2C,
				SUM(IF(bkg_status IN (15) AND bkg_agent_id IS NULL,1,0)) AS QuotedCntB2C,
				SUM(IF(bkg_status IN (2,3,5,6,7) AND bkg_agent_id IS NULL,1,0)) AS ServedCntB2C,
				SUM(IF(bkg_status IN (9) AND bkg_agent_id IS NULL,1,0)) AS CancelledCntB2C,
				SUM(IF(bkg_status IN (1,2,3,4,5,6,7,9,15),1,0)) AS AllCnt,
				SUM(IF(bkg_status IN (1),1,0)) AS AllUnVerifiedCnt,
				SUM(IF(bkg_status IN (15),1,0)) AS AllQuotedCnt,
				SUM(IF(bkg_status IN (2,3,5,6,7),1,0)) AS AllServedCnt,
				SUM(IF(bkg_status IN (9) ,1,0)) AS AllCancelledCnt
				FROM `booking`
				INNER JOIN `booking_cab` ON bkg_bcb_id=booking_cab.bcb_id AND bcb_active = 1
				INNER JOIN `svc_class_vhc_cat` scv ON scv.scv_id = booking.bkg_vehicle_type_id
				INNER JOIN `booking_user` ON booking.bkg_id=booking_user.bui_bkg_id
				INNER JOIN `booking_trail` ON booking.bkg_id=booking_trail.btr_bkg_id
				INNER JOIN `booking_invoice` ON booking.bkg_id=booking_invoice.biv_bkg_id
				INNER JOIN `booking_track` ON booking.bkg_id=booking_track.btk_bkg_id
				INNER JOIN `booking_pref` bkgPref ON bkgPref.bpr_bkg_id=booking.bkg_id
				WHERE 1 AND booking.bkg_create_date BETWEEN  '$model->bkg_create_date1' AND  '$model->bkg_create_date2'
				GROUP BY createDate
				ORDER BY createDate DESC";

		$sqlCount = "
					SELECT
					DATE(booking.bkg_create_date) AS createDate
					FROM `booking`
					INNER JOIN `booking_cab` ON bkg_bcb_id=booking_cab.bcb_id AND bcb_active = 1
					INNER JOIN `svc_class_vhc_cat` scv ON scv.scv_id = booking.bkg_vehicle_type_id
					INNER JOIN `booking_user` ON booking.bkg_id=booking_user.bui_bkg_id
					INNER JOIN `booking_trail` ON booking.bkg_id=booking_trail.btr_bkg_id
					INNER JOIN `booking_invoice` ON booking.bkg_id=booking_invoice.biv_bkg_id
					INNER JOIN `booking_track` ON booking.bkg_id=booking_track.btk_bkg_id
					INNER JOIN `booking_pref` bkgPref ON bkgPref.bpr_bkg_id=booking.bkg_id
					WHERE 1 AND booking.bkg_create_date BETWEEN  '$model->bkg_create_date1' AND '$model->bkg_create_date2'
					GROUP BY createDate
							";
		if($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount ) temp", DBUtil::SDB3());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['createDate'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	/**
	 * @internal Transferred from BI
	 * @param Booking $model
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function cancelHistoryByPickupDate($model, $command = DBUtil::ReturnType_Provider)
	{
		$sql		 = "SELECT
							Temp.pickupDate,
							Temp.MMTSC,Temp.MMTTC,Temp.MMTCI,Temp.MMTGI,
							Temp.EMTSC,Temp.EMTTC,Temp.EMTCI,Temp.EMTGI,
							Temp.SPICESC,Temp.SPICETC,Temp.SPICECI,Temp.SPICEGI,
							Temp.B2CSC,Temp.B2CTC,Temp.B2CCI,Temp.B2CGI,
							ROUND(((Temp.MMTGI * 100) / Temp.MMTTC), 2) AS MMT_GI_TC_PER,
							ROUND(((Temp.EMTGI * 100) / Temp.EMTTC),2) AS EMT_GI_TC_PER,
							ROUND(((Temp.SPICEGI * 100) / Temp.SPICETC),2) AS SPICE_GI_TC_PER,
							ROUND(((Temp.B2CGI * 100) / Temp.B2CTC),2) AS B2C_GI_TC_PER
							FROM
							(
							SELECT
							DATE(bkg_pickup_date) AS pickupDate,
							COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (450,18190) AND bkg_status IN (2,3,5,6,7),1,NULL)) AS MMTSC,
							COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (450,18190) AND bkg_status IN (9) ,1,NULL)) AS MMTTC,
							-- COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (450,18190) AND bkg_status IN (9) AND booking.bkg_cancel_id IN (1,2,4,5,6,7,10,11,12,13,14,15,18,21,23,24,25,31,32),1,NULL)) AS MMTCI,
							COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (450,18190) AND bkg_status IN (9) AND booking.bkg_cancel_id IN (1,2,4,5,6,7,10,11,12,13,14,15,18,19,20,21,23,24,25,27,29,31,32,34,33,37),1,NULL)) AS MMTCI,
							COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (450,18190) AND bkg_status IN (9) AND booking.bkg_cancel_id IN (6,9,16,17,22,26,28,30,35,36),1,NULL)) AS MMTGI,

							COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (30228) AND bkg_status IN (6,7),1,NULL)) AS EMTSC,
							COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (30228) AND bkg_status IN (9) ,1,NULL)) AS EMTTC,
							-- COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (30228) AND bkg_status IN (9) AND booking.bkg_cancel_id IN (1,2,4,5,6,7,10,11,12,13,14,15,18,21,23,24,25,31,32),1,NULL)) AS EMTCI,
							COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (30228) AND bkg_status IN (9) AND booking.bkg_cancel_id IN (1,2,4,5,6,7,10,11,12,13,14,15,18,19,20,21,23,24,25,27,29,31,32,34,33,37),1,NULL)) AS EMTCI,
							-- COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (30228) AND bkg_status IN (9) AND booking.bkg_cancel_id IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36),1,NULL)) AS EMTGI,
							COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (30228) AND bkg_status IN (9) AND booking.bkg_cancel_id IN (6,9,16,17,22,26,28,30,35,36),1,NULL)) AS EMTGI,



							COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (34928) AND bkg_status IN (6,7),1,NULL)) AS SPICESC,
							COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (34928) AND bkg_status IN (9) ,1,NULL)) AS SPICETC,
							-- COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (34928) AND bkg_status IN (9) AND booking.bkg_cancel_id IN (1,2,4,5,6,7,10,11,12,13,14,15,18,21,23,24,25,31,32),1,NULL)) AS SPICECI,
							COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (34928) AND bkg_status IN (9) AND booking.bkg_cancel_id IN (1,2,4,5,6,7,10,11,12,13,14,15,18,19,20,21,23,24,25,27,29,31,32,34,33,37),1,NULL)) AS SPICECI,
							-- COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (34928) AND bkg_status IN (9) AND booking.bkg_cancel_id IN(3,9,16,17,19,20,22,26,28,29,30,33,34,35,36),1,NULL)) AS SPICEGI,
							COUNT(IF(booking.bkg_agent_id IS NOT NULL AND booking.bkg_agent_id IN (34928) AND bkg_status IN (9) AND booking.bkg_cancel_id IN (6,9,16,17,22,26,28,30,35,36),1,NULL)) AS SPICEGI,



							COUNT(IF(booking.bkg_agent_id IS NULL AND bkg_status IN (6,7),1,NULL)) AS B2CSC,
							COUNT(IF(booking.bkg_agent_id IS NULL AND bkg_status IN (9) ,1,NULL)) AS B2CTC,
							-- COUNT(IF(booking.bkg_agent_id IS NULL AND bkg_status IN (9) AND booking.bkg_cancel_id IN (1,2,4,5,6,7,10,11,12,13,14,15,18,21,23,24,25,31,32),1,NULL)) AS B2CCI,
							COUNT(IF(booking.bkg_agent_id IS NULL AND bkg_status IN (9) AND booking.bkg_cancel_id IN (1,2,4,5,6,7,10,11,12,13,14,15,18,19,20,21,23,24,25,27,29,31,32,34,33,37),1,NULL)) AS B2CCI,
							-- COUNT(IF(booking.bkg_agent_id IS NULL AND bkg_status IN (9) AND booking.bkg_cancel_id IN(3,9,16,17,19,20,22,26,28,29,30,33,34,35,36),1,NULL)) AS B2CGI
							COUNT(IF(booking.bkg_agent_id IS NULL AND bkg_status IN (9) AND booking.bkg_cancel_id IN (6,9,16,17,22,26,28,30,35,36),1,NULL)) AS B2CGI

							FROM booking
							WHERE 1
							AND booking.bkg_pickup_date BETWEEN '$model->bkg_pickup_date1' AND '$model->bkg_pickup_date2'
							AND booking.bkg_active = 1
							GROUP BY pickupDate
							) Temp WHERE 1";
		$sqlCount	 = "SELECT
							SUM(IF(pickupDate != '0000-00-00', 1, 0)) AS countCancel
						FROM
						(
							SELECT
								DATE(bkg_pickup_date) AS pickupDate
							FROM
								booking
							WHERE
								booking.bkg_pickup_date BETWEEN '$model->bkg_pickup_date1' AND '$model->bkg_pickup_date2' AND booking.bkg_active = 1
							GROUP BY
								pickupDate
						) a;";
		if($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => [], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	/**
	 * @internal Transferred from BI
	 * @param Booking $model
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function localBookingByCreateDate($model, $command = DBUtil::ReturnType_Provider)
	{
		$sql		 = "SELECT
				DATE_FORMAT(booking.bkg_create_date, '%Y-%m-%d') AS DATE,
				SUM(IF(bkg_status IN (2,3,4,5,6,7,9) AND bkg_agent_id IN (450,18190),1,0)) AS \"MMT/IBIBO_Booking\",
				SUM(IF(bkg_status IN (6,7) AND bkg_agent_id IN (450,18190),1,0)) AS \"MMT/IBIBO_Completed\",
				SUM(IF(bkg_status IN (9) AND bkg_agent_id IN (450,18190),1,0)) AS \"MMT/IBIBO_Cancel\",
				SUM(IF(bkg_status IN (2,3,4,5,6,7,9) AND bkg_agent_id IN (30228),1,0)) AS \"EMT_Booking\",
				SUM(IF(bkg_status IN (6,7) AND bkg_agent_id IN (30228),1,0)) AS \"EMT_Completed\",
				SUM(IF(bkg_status IN (9) AND bkg_agent_id IN (30228),1,0)) AS \"EMT_Cancel\",
				SUM(IF(bkg_status IN (2,3,4,5,6,7,9) AND bkg_agent_id IN (34928),1,0)) AS \"SPICEJET_Booking\",
				SUM(IF(bkg_status IN (6,7) AND bkg_agent_id IN (34928),1,0)) AS \"SPICEJET_Completed\",
				SUM(IF(bkg_status IN (9) AND bkg_agent_id IN (34928),1,0)) AS \"SPICEJET_Cancel\",
				SUM(IF(bkg_status IN (2,3,4,5,6,7,9) AND bkg_agent_id NOT IN (34928,30228,450,18190),1,0)) AS \"OTHER_B2B_Booking\",
				SUM(IF(bkg_status IN (6,7) AND bkg_agent_id NOT IN (34928,30228,450,18190),1,0)) AS \"OTHER_B2B_Completed\",
				SUM(IF(bkg_status IN (9) AND bkg_agent_id NOT IN (34928,30228,450,18190),1,0)) AS \"OTHER_B2B_Cancel\",
				SUM(IF(bkg_status IN (2,3,4,5,6,7,9) AND bkg_agent_id IS NULL,1,0)) AS \"B2C_Booking\",
				SUM(IF(bkg_status IN (6,7) AND bkg_agent_id IS NULL,1,0)) AS \"B2C_Completed\",
				SUM(IF(bkg_status IN (9) AND bkg_agent_id IS NULL,1,0)) AS \"B2C_Cancel\",
				SUM(IF(bkg_status IN (2,3,4,5,6,7,9),1,0)) AS \"Total_Booking\",
				SUM(IF(bkg_status IN (6,7),1,0)) AS \"Total_Completed\",
				SUM(IF(bkg_status IN (9),1,0)) AS \"Total_Cancel\"
				FROM `booking`
				WHERE bkg_create_date BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2'
				AND bkg_booking_type IN (4,9,10,11,12)
				AND booking.bkg_active=1
				GROUP BY DATE
				ORDER BY DATE ASC";
		$sqlCount	 = "SELECT COUNT(1) FROM (
						SELECT
						DATE_FORMAT(booking.bkg_create_date, '%Y-%m-%d') AS DATE
						FROM `booking` WHERE booking.bkg_create_date BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2'
						AND bkg_booking_type IN (4,9,10,11,12)
						AND booking.bkg_active=1
						GROUP BY DATE
						ORDER BY DATE ASC
					) a";
		if($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => [], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	/**
	 * @internal Transferred from BI
	 * @param Booking $model
	 * @param integer $type
	 * @return \CSqlDataProvider
	 */
	public static function bookingByAssignment($model, $type = DBUtil::ReturnType_Provider)
	{
		$sql					 = "SELECT
				bkg_id,
				admins.gozen AS gozen,
				booking.bkg_route_city_names AS route_name,
				bkg_create_date AS create_date,
				bkg_pickup_date AS pickup_date,
				bkg_total_amount AS booking_amount,
				(bkg_gozo_amount - IFNULL(bkg_credits_used,0)) AS gozo_amount,
				ROUND((((bkg_gozo_amount - IFNULL(bkg_credits_used,0))*100)/bkg_total_amount),2) AS profit
				FROM `booking`
				INNER JOIN `booking_cab` on booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
				INNER JOIN `booking_invoice` on booking_invoice.biv_bkg_id = booking.bkg_id
				INNER JOIN `booking_trail` on booking_trail.btr_bkg_id = booking.bkg_id
				INNER JOIN
				(
					SELECT
					MAX(booking_log.blg_id) AS MaxBlgId,
					booking_log.blg_booking_id
					FROM `booking_log`
					INNER JOIN `booking` ON booking.bkg_id=booking_log.blg_booking_id
					WHERE bkg_pickup_date BETWEEN '$model->bkg_pickup_date1' AND '$model->bkg_pickup_date2'
					AND booking_log.blg_event_id=7
					AND booking_log.blg_active=1
					GROUP BY booking_log.blg_booking_id
				)
				tempBlg ON tempBlg.blg_booking_id=booking.bkg_id
				INNER JOIN booking_log ON booking_log.blg_id=tempBlg.MaxBlgId
				LEFT JOIN admins ON admins.adm_id=booking_log.blg_user_id AND booking_log.blg_user_type=4
				WHERE 1
				AND booking.bkg_status IN (6,7)
				AND booking.bkg_pickup_date BETWEEN :pickupDate1 AND :pickupDate2 AND booking.bkg_reconfirm_flag=1";
		$params["pickupDate1"]	 = $model->bkg_pickup_date1;
		$params["pickupDate2"]	 = $model->bkg_pickup_date2;
		if($model->bkg_create_date1 != '' && $model->bkg_create_date2 != '')
		{
			$sql					 .= " AND booking.bkg_create_date BETWEEN :createDate1 AND :createDate2";
			$params["createDate1"]	 = $model->bkg_create_date1;
			$params["createDate2"]	 = $model->bkg_create_date2;
		}

		if($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql ) temp", DBUtil::SDB3(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'params'		 => $params,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['bkg_id', 'route_name', 'create_date', 'pickup_date', 'booking_amount', 'gozo_amount', 'profit'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3(), $params);
		}
	}

	/**
	 * @internal Transferred from BI
	 * @param Booking $model
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function cancellationReasonList($model, $command = DBUtil::ReturnType_Provider)
	{
		$sql = "SELECT
				booking.bkg_id,
				booking_user.bkg_user_fname,
				booking_user.bkg_user_lname,
				booking.bkg_booking_type,
				states.stt_zone,
				booking.bkg_create_date,
				booking.bkg_pickup_date,
				fromCity.cty_name as from_cty_name,
				toCity.cty_name as to_cty_name,
				btk.bkg_trip_arrive_time AS Arrival_Time,
				booking_trail.btr_cancel_date AS Cancel_Date,
				booking_trail.btr_is_dbo_applicable AS is_dbo,
				booking_invoice.bkg_total_amount AS TotalAmount,
				booking.bkg_cancel_delete_reason AS DeleteReason,
				cancel_reasons.cnr_reason AS CancelReason,
				IF(
					(
						booking.bkg_cancel_id = 17 || booking.bkg_cancel_id = 9
					) AND booking_trail.btr_is_dbo_applicable = 1 AND(
						booking_trail.btr_cancel_date < DATE_SUB(
							booking.bkg_pickup_date,
							INTERVAL 5 DAY
						) OR booking_trail.btr_cancel_date > DATE_ADD(
							booking.bkg_create_date,
							INTERVAL 24 HOUR
						)
					),
					booking_trail.btr_dbo_amount,
					0
				) AS Refund_Amount,
				(
					CASE booking_trail.bkg_cancel_user_type WHEN 1 THEN CONCAT(
						'Customer -',
						' ',
						booking_user.bkg_user_fname,
						' ',
						booking_user.bkg_user_lname
					) WHEN 4 THEN CONCAT(
						'CSR -',
						' ',
						admins.adm_fname,
						' ',
						admins.adm_lname
					) WHEN 5 THEN CONCAT(
						'Partner -',
						' ',
						agents.agt_company
					) WHEN 10 THEN 'System : auto-cancelled'
				END
			) AS Cancel_By,
			bkg_cancel_charge AS Cancel_Charge
			FROM `booking`
			JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
			JOIN `booking_invoice` ON booking_invoice.biv_bkg_id = booking.bkg_id
			JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id
			INNER JOIN booking_track btk ON btk.btk_bkg_id = booking.bkg_id
			JOIN `booking_user` ON booking_user.bui_bkg_id = booking.bkg_id
			JOIN `cancel_reasons` ON cancel_reasons.cnr_id = booking.bkg_cancel_id
			JOIN `cities` fromCity ON booking.bkg_from_city_id = fromCity.cty_id
			JOIN `states` ON states.stt_id = fromCity.cty_state_id
			JOIN `cities` toCity ON booking.bkg_to_city_id = toCity.cty_id
			LEFT JOIN `admins` ON admins.adm_id = booking_trail.bkg_cancel_user_id
			LEFT JOIN `agents` ON agents.agt_id = booking.bkg_agent_id
			WHERE (booking.bkg_create_date) <=(booking_trail.btr_cancel_date)
			AND booking.bkg_active = 1
			AND booking.bkg_status IN(9)
			AND booking.bkg_create_date BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2'";

		$sqlCount = "SELECT SUM(IF(bkg_id>0,1,0)) totalCount FROM
					(

						SELECT
						booking.bkg_id,
						booking_user.bkg_user_fname,
						booking_user.bkg_user_lname,
						booking.bkg_booking_type,
						states.stt_zone,
						booking.bkg_create_date,
						booking.bkg_pickup_date,
						fromCity.cty_name as fromCityName,
						toCity.cty_name as toCityName,
						btk.bkg_trip_arrive_time AS Arrival_Time,
						booking_trail.btr_cancel_date AS Cancel_Date,
						booking_trail.btr_is_dbo_applicable AS is_dbo,
						booking_invoice.bkg_total_amount AS TotalAmount,
						booking.bkg_cancel_delete_reason AS DeleteReason,
						cancel_reasons.cnr_reason AS CancelReason,
						IF(
							(
								booking.bkg_cancel_id = 17 || booking.bkg_cancel_id = 9
							) AND booking_trail.btr_is_dbo_applicable = 1 AND(
								booking_trail.btr_cancel_date < DATE_SUB(
									booking.bkg_pickup_date,
									INTERVAL 5 DAY
								) OR booking_trail.btr_cancel_date > DATE_ADD(
									booking.bkg_create_date,
									INTERVAL 24 HOUR
								)
							),
							booking_trail.btr_dbo_amount,
							0
						) AS Refunud_Amount,
						(
							CASE booking_trail.bkg_cancel_user_type WHEN 1 THEN CONCAT(
								'Customer -',
								' ',
								booking_user.bkg_user_fname,
								' ',
								booking_user.bkg_user_lname
							) WHEN 4 THEN CONCAT(
								'CSR -',
								' ',
								admins.adm_fname,
								' ',
								admins.adm_lname
							) WHEN 5 THEN CONCAT(
								'Partner -',
								' ',
								agents.agt_company
							) WHEN 10 THEN 'System : auto-cancelled'
						END
					) AS Cancel_By,
					bkg_cancel_charge AS Cancel_Charge
					FROM `booking`
					JOIN `svc_class_vhc_cat` scv ON scv.scv_id = booking.bkg_vehicle_type_id
					JOIN `booking_invoice` ON booking_invoice.biv_bkg_id = booking.bkg_id
					JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id
					INNER JOIN `booking_track` btk ON btk.btk_bkg_id = booking.bkg_id
					JOIN `booking_user` ON booking_user.bui_bkg_id = booking.bkg_id
					JOIN `cancel_reasons` ON cancel_reasons.cnr_id = booking.bkg_cancel_id
					JOIN `cities` fromCity ON booking.bkg_from_city_id = fromCity.cty_id
					JOIN `states` ON states.stt_id = fromCity.cty_state_id
					JOIN `cities` toCity ON booking.bkg_to_city_id = toCity.cty_id
					LEFT JOIN `admins` ON admins.adm_id = booking_trail.bkg_cancel_user_id
					LEFT JOIN `agents` ON agents.agt_id = booking.bkg_agent_id
					WHERE (booking.bkg_create_date) <=(booking_trail.btr_cancel_date)
					AND booking.bkg_active = 1
					AND booking.bkg_status IN(9)
					AND booking.bkg_create_date BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2'
					) a";
		if($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['bkg_id', 'bkg_create_date', 'bkg_pickup_date', 'btr_cancel_date', 'bkg_total_amount', 'Refund_Amount', 'bkg_cancel_charge'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	/**
	 * @internal Transferred from BI
	 * @param Booking $model
	 * @param integer $type
	 * @return \CSqlDataProvider
	 */
	public static function getUpperTierList($model, $type = DBUtil::ReturnType_Provider)
	{
		$sql					 = "SELECT
					DATE(bkg_pickup_date) AS pickup_date,
					COUNT(bkg_id) AS booking_count,
					SUM(bkg_total_amount) AS total_booking_count,
					SUM(bkg_gozo_amount - IFNULL(bkg_credits_used, 0)) AS profit
				FROM `booking`
				JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id
				JOIN `booking_invoice`  ON booking_invoice.biv_bkg_id = booking.bkg_id
				JOIN `svc_class_vhc_cat` scvc ON scvc.scv_id = booking.bkg_vehicle_type_id AND scvc.scv_active = 1
				JOIN `service_class` sc ON scvc.scv_scc_id = sc.scc_id AND sc.scc_active = 1
				WHERE sc.scc_id NOT IN(1, 6)
				AND booking.bkg_pickup_date BETWEEN :pickupDate1 AND :pickupDate2
				AND bkg_status IN(5, 6, 7)
				GROUP BY DATE(bkg_pickup_date)";
		$params["pickupDate1"]	 = $model->bkg_pickup_date1;
		$params["pickupDate2"]	 = $model->bkg_pickup_date2;
		if($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql ) temp", DBUtil::SDB3(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'params'		 => $params,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['pickup_date', 'booking_count', 'total_booking_count', 'profit'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3(), $params);
		}
	}

	/**
	 * @internal Transferred from BI
	 * @param Booking $model
	 * @param integer $type
	 * @return \CSqlDataProvider
	 */
	public static function getBookingCountByStates($model, $type = DBUtil::ReturnType_Provider)
	{
		$sql					 = "SELECT
				COUNT(DISTINCT bkg_id) AS cnt,
				DATE(bkg_pickup_date) AS PickupDate,
				CONCAT(states.stt_name , '(', cty_state_id, ')') AS StateName,
				cty_state_id
				FROM  `booking`
				JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND bcb_active = 1
				JOIN `cities` ON cities.cty_id = booking.bkg_from_city_id AND cities.cty_active = 1
				JOIN `states` ON states.stt_id = cities.cty_state_id AND stt_active = '1'
				WHERE bkg_status IN(2, 3, 4, 5, 6, 7) AND bkg_pickup_date BETWEEN :pickupDate1 AND :pickupDate2 AND bkg_reconfirm_flag = 1
				GROUP BY
					stt_id,
					PickupDate";
		$sqlCount				 = "SELECT COUNT(1) FROM (
							SELECT
								COUNT(DISTINCT bkg_id) AS cnt,
								DATE(bkg_pickup_date) AS PickupDate,
								cty_state_id
								FROM  `booking`
								JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND bcb_active = 1
								JOIN `cities` ON cities.cty_id = booking.bkg_from_city_id AND cities.cty_active = 1
								JOIN `states` ON states.stt_id = cities.cty_state_id AND stt_active = '1'
								WHERE bkg_status IN(2, 3, 4, 5, 6, 7) AND bkg_pickup_date BETWEEN :pickupDate1 AND :pickupDate2 AND bkg_reconfirm_flag = 1
								GROUP BY
									stt_id,
									PickupDate
								ORDER BY
									stt_id,
									PickupDate ASC
								) a";
		$params["pickupDate1"]	 = $model->bkg_pickup_date1;
		$params["pickupDate2"]	 = $model->bkg_pickup_date2;
		if($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'params'		 => $params,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['cnt', 'PickupDate', 'StateName'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3(), $params);
		}
	}

	/**
	 * @internal Transferred from BI
	 * @param Booking $model
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function getSalesAssistedBooking($model, $command = DBUtil::ReturnType_Provider)
	{
		$sql = "SELECT
					DATE(booking.bkg_create_date) AS `bkg_create_date`,
					COUNT(*) AS `count`
				FROM `booking`
				INNER JOIN `admin_profiles` ON booking.bkg_admin_id = admin_profiles.adp_adm_id
				WHERE
					(
						admin_profiles.adp_team_leader_id = 467 AND
						(
						   booking.bkg_status IN (2,3,5,6,7)
						)
						AND booking.bkg_create_date BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2'
					)
				GROUP BY DATE(booking.bkg_create_date)
				ORDER BY DATE(booking.bkg_create_date) ASC";

		$sqlCount = "SELECT COUNT(1) as totalCount FROM (
						SELECT
						DATE(booking.bkg_create_date) AS `bkg_create_date`,
						COUNT(*) AS `count`
						FROM `booking`
						INNER JOIN `admin_profiles`  ON booking.bkg_admin_id = admin_profiles.adp_adm_id
						WHERE
							(
								admin_profiles.adp_team_leader_id = 467 AND
								(
								   booking.bkg_status IN (2,3,5,6,7)
								)
								AND booking.bkg_create_date BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2'
							)
						GROUP BY DATE(booking.bkg_create_date)
						ORDER BY DATE(booking.bkg_create_date) ASC
					)a";

		if($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => [], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	/**
	 * @internal Transferred from BI
	 * @todo #OptimizeSQL
	 * @param Booking $model
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function getMonthlyVolumeByServiceTier($model, $command = DBUtil::ReturnType_Provider)
	{
		$sql = "SELECT
					STR_TO_DATE(
						CONCAT(
							DATE_FORMAT(booking.bkg_pickup_date, '%Y-%m'),
							'-01'
						),
						'%Y-%m-%d'
					) AS `bkg_pickup_date`,
					service_class.scc_label,
					COUNT(*) AS `count`
				FROM `booking`
				INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id
				INNER JOIN `svc_class_vhc_cat` ON booking.bkg_vehicle_type_id = svc_class_vhc_cat.scv_id
				INNER JOIN `service_class` ON  svc_class_vhc_cat.scv_scc_id = service_class.scc_id
				WHERE booking.bkg_status IN (6,7)
				AND booking.bkg_pickup_date BETWEEN  '$model->bkg_pickup_date1' AND '$model->bkg_pickup_date2'
				GROUP BY
					STR_TO_DATE(
						CONCAT(
							DATE_FORMAT(booking.bkg_pickup_date, '%Y-%m'),
							'-01'
						),
						'%Y-%m-%d'
					),
					service_class.scc_label
				ORDER BY
					STR_TO_DATE(
						CONCAT(
							DATE_FORMAT(booking.bkg_pickup_date, '%Y-%m'),
							'-01'
						),
						'%Y-%m-%d'
					) ASC,
				   service_class.scc_label ASC";

		$sqlCount = "SELECT
							COUNT(1)
						FROM
							(
							SELECT
								STR_TO_DATE(
									CONCAT(
										DATE_FORMAT(booking.bkg_pickup_date, '%Y-%m'),
										'-01'
									),
									'%Y-%m-%d'
								) AS `bkg_pickup_date`,
								service_class.scc_label,
								COUNT(*) AS `count`
							FROM
								`booking`
							INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id
							INNER JOIN `svc_class_vhc_cat` ON booking.bkg_vehicle_type_id = svc_class_vhc_cat.scv_id
							INNER JOIN `service_class` ON svc_class_vhc_cat.scv_scc_id = service_class.scc_id
							WHERE
								(
									booking.bkg_status IN (6,7) AND booking.bkg_pickup_date BETWEEN '$model->bkg_pickup_date1' AND '$model->bkg_pickup_date2'
								)
							GROUP BY
								STR_TO_DATE(
									CONCAT(
										DATE_FORMAT(booking.bkg_pickup_date, '%Y-%m'),
										'-01'
									),
									'%Y-%m-%d'
								),
								service_class.scc_label
							ORDER BY
								STR_TO_DATE(
									CONCAT(
										DATE_FORMAT(booking.bkg_pickup_date, '%Y-%m'),
										'-01'
									),
									'%Y-%m-%d'
								) ASC,
								service_class.scc_label ASC
						) a";

		if($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => [], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	public static function getCustomerOngoingTrip()
	{
		$sql = 'SELECT DISTINCT bkg_id
				FROM booking
				INNER JOIN booking_track ON booking_track.btk_bkg_id=booking.bkg_id
				WHERE 1
				AND bkg_agent_id IS NULL AND bkg_booking_type IN (1,2,3) AND bkg_status=5 
				AND bkg_pickup_date < DATE_SUB(NOW(), INTERVAL 30 MINUTE) 
				AND NOW() BETWEEN DATE_ADD(bkg_pickup_date, INTERVAL ROUND((bkg_trip_duration/2), 0) MINUTE) AND DATE_ADD(bkg_pickup_date, INTERVAL (ROUND((bkg_trip_duration/2), 0) + 60) MINUTE)';
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * Check the booking cancel status with MMT team against any booking and same updated on our portal
	 */
	public static function cancelUnverifiedTFRBookings()
	{
		$sql = "SELECT bkg.bkg_id,bkg.bkg_status, btr.btr_assigned_fdate, bkg.bkg_create_date, bkg.bkg_pickup_date 
				FROM booking bkg 
				INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id AND bkg.bkg_status IN (2) 
				INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id 
				WHERE btr.btr_assigned_fdate IS NULL AND bpr.bkg_is_fbg_type =1 
				AND bkg.bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 365 DAY) AND DATE_SUB(NOW(), INTERVAL 30 DAY)";

		$rows = DBUtil::query($sql, DBUtil::SDB());
		foreach ($rows as $data)
		{
			$bkgId = $data['bkg_id'];
			
			Logger::writeToConsole("BkgId: " . $bkgId);
			
			$bmodel		 = Booking::model()->findByPk($data['bkg_id']);
			$typeAction	 = AgentApiTracking::TYPE_GET_PASSENGER_DETAILS;
			$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
			
			Logger::writeToConsole("Status: " . $mmtResponse->status);
			
			if ($mmtResponse->status == 2)
			{
				$cancelReason		 = CancelReasons::getTFRCancelReason();
				$cancellation_reason = $cancelReason['cnr_reason'];
				$reasonId			 = $cancelReason['cnr_id'];
				$success			 = $bmodel->canbooking($bmodel->bkg_id, $cancellation_reason, $reasonId);
				
				Logger::writeToConsole("Success: " . $success);
			}
		}
	}

	/**
	 * @param Booking $model
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function mmtcancelbookingList($model)
	{
		$cond	 = '';
		$cond1	 = '';

		$frompickdate	 = $model->bkg_pickup_date1 . ' 00:00:00';
		$topickdate		 = $model->bkg_pickup_date2 . ' 23:59:59';
		$fromcreatedate	 = $model->bkg_create_date1 . ' 00:00:00';
		$tocreatedate	 = $model->bkg_create_date2 . ' 23:59:59';

		if($model->bkg_pickup_date1 != '' || $model->bkg_pickup_date2 != '')
		{
			$cond = " AND bkg.bkg_pickup_date BETWEEN  '$frompickdate' AND  '$topickdate'";
		}
		else
		{
			$cond = '';
		}
		if($model->bkg_create_date1 != '' || $model->bkg_create_date2 != '')
		{
			$cond1 = " AND bkg.bkg_create_date BETWEEN  '$fromcreatedate' AND  '$tocreatedate'";
		}
		else
		{
			$cond1 = '';
		}
		$sql = "SELECT bkg.bkg_id,bkg.bkg_booking_id,bkg.bkg_pickup_date,bkg.bkg_create_date,
					   bkg.bkg_route_city_names,bkg.bkg_from_city_id,bkg.bkg_to_city_id,
				       bui.bkg_user_fname,bui.bkg_user_lname,
					   bui.bkg_contact_no,bui.bkg_user_email,cty.cty_name ctyFrm,cty1.cty_name ctyTo
				FROM booking bkg
				INNER JOIN booking_user bui ON bui.bui_bkg_id = bkg.bkg_id AND (bui.bkg_contact_no IS NOT NULL || bui.bkg_user_email IS NOT NULL)
				INNER JOIN cities cty ON cty.cty_id = bkg.bkg_from_city_id
				INNER JOIN cities cty1 ON cty1.cty_id = bkg.bkg_to_city_id
				WHERE bkg.bkg_status = 9 AND bkg.bkg_agent_id = 18190
				$cond1 $cond
				ORDER BY bkg.bkg_create_date DESC";

		$sqlCount = "SELECT bkg.bkg_id,bkg.bkg_booking_id,bkg.bkg_pickup_date,bkg.bkg_create_date,
					   bkg.bkg_route_city_names,bkg.bkg_from_city_id,bkg.bkg_to_city_id,
				       bui.bkg_user_fname,bui.bkg_user_lname,
					   bui.bkg_contact_no,bui.bkg_user_email,cty.cty_name ctyFrm,cty1.cty_name ctyTo
				FROM booking bkg
				INNER JOIN booking_user bui ON bui.bui_bkg_id = bkg.bkg_id AND (bui.bkg_contact_no IS NOT NULL || bui.bkg_user_email IS NOT NULL)
				INNER JOIN cities cty ON cty.cty_id = bkg.bkg_from_city_id
		        INNER JOIN cities cty1 ON cty1.cty_id = bkg.bkg_to_city_id
				WHERE bkg.bkg_status = 9 AND bkg.bkg_agent_id = 18190
				$cond1 $cond
				ORDER BY bkg.bkg_create_date DESC
				";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount ) temp", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes' => ['createDate'], 'defaultOrder' => ''],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	/**
	 * @param Booking $model
	 * @return \CSqlDataProvider
	 */
	public static function getPartnerBookings($model)
	{
		$agtId			 = $model->bkg_agent_id;
		$createFromDate	 = $model->bkg_create_date1;
		$createToDate	 = $model->bkg_create_date2;
		$pickupFromDate	 = $model->bkg_pickup_date1;
		$pickupToDate	 = $model->bkg_pickup_date2;

		$cond = "";
		if($createFromDate != '' && $createToDate != '')
		{
			$cond .= " AND bkg.bkg_create_date BETWEEN '{$createFromDate}' AND '{$createToDate}' ";
		}
		if($pickupFromDate != '' && $pickupToDate != '')
		{
			$cond .= " AND bkg.bkg_pickup_date BETWEEN '{$pickupFromDate}' AND '{$pickupToDate}' ";
		}

		$sql			 = "SELECT bkg.bkg_id,bkg.bkg_booking_id,bkg.bkg_pickup_date,bkg.bkg_create_date, 
				bkg.bkg_route_city_names,bkg.bkg_from_city_id,bkg.bkg_to_city_id, 
				bui.bkg_user_fname,bui.bkg_user_lname, 
				bui.bkg_contact_no, bui.bkg_user_email, fromCty.cty_name ctyFrm, toCty.cty_name ctyTo 
				FROM booking bkg 
				INNER JOIN booking_user bui ON bui.bui_bkg_id = bkg.bkg_id 
				INNER JOIN cities fromCty ON fromCty.cty_id = bkg.bkg_from_city_id 
				INNER JOIN cities toCty ON toCty.cty_id = bkg.bkg_to_city_id 
				WHERE bkg.bkg_status IN (1,10,15) AND bkg.bkg_agent_id = {$agtId} 
				{$cond} 
				ORDER BY bkg.bkg_pickup_date ASC ";
		#echo $sql;die();
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) temp", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes' => ['bkg_pickup_date', 'bkg_create_date'], 'defaultOrder' => 'bkg_pickup_date ASC'],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public static function checkCustomerExistingOngoingBooking($callerNumber, $bookingId = null)
	{
		$params = ['number' => null, 'code' => null];
		if($callerNumber && Filter::validatePhoneNumber($callerNumber))
		{
			Filter::parsePhoneNumber($callerNumber, $code, $number);
			$params = ['number' => $number, 'code' => $code];
		}
		$where = '';

		if($bookingId)
		{
			$params['bkgid'] = $bookingId;
			$where			 = " OR bkg.bkg_id=:bkgid";
		}
		$sql = "SELECT bui_bkg_id,bkg.bkg_pickup_date,bkg_trip_duration
					FROM booking_user bui  
                    INNER JOIN booking bkg ON bkg.bkg_id = bui.bui_bkg_id
                    LEFT JOIN contact_profile cp ON cp.cr_is_consumer=bui.bkg_user_id AND cp.cr_status = 1
                    LEFT JOIN contact_phone cph ON cph.phn_contact_id=cp.cr_contact_id AND cph.phn_active=1
					WHERE ((bui.bkg_country_code=:code AND bui.bkg_contact_no=:number) 
							OR (cph.phn_id IS NOT NULL AND cph.phn_phone_country_code=:code AND cph.phn_phone_no=:number ) $where) 
						AND bkg_status IN (2,3,5) AND bkg_trip_duration IS NOT NULL
						AND bkg.bkg_pickup_date <  DATE_ADD(NOW(),INTERVAL 30 MINUTE) 
						AND DATE_ADD(bkg.bkg_pickup_date, INTERVAL (bkg_trip_duration + 90) MINUTE) > NOW()";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	public static function checkDriverExistingOngoingBooking($callerNumber, $bookingId = null)
	{
		$params = ['number' => null, 'code' => null];
		if($callerNumber && Filter::validatePhoneNumber($callerNumber))
		{
			Filter::parsePhoneNumber($callerNumber, $code, $number);
			$params = ['number' => $number, 'code' => $code];
		}
		$where = '';

		if($bookingId)
		{
			$params['bkgid'] = $bookingId;
			$where			 = " OR bkg.bkg_id=:bkgid";
		}
		$sql = "SELECT
				bkg_id,
				bkg_bcb_id,
				bkg.bkg_pickup_date,
				bkg_trip_duration
			FROM
				booking bkg
			INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id
			INNER JOIN booking_track btk ON btk.btk_bkg_id = bkg.bkg_id				
			LEFT JOIN contact_profile cp ON cp.cr_is_driver = bcb.bcb_driver_id AND cp.cr_status = 1
			LEFT JOIN contact_phone cph ON cph.phn_contact_id = cp.cr_contact_id AND cph.phn_active = 1
			WHERE
				(bcb.bcb_driver_phone = :number OR (cph.phn_id IS NOT NULL AND cph.phn_phone_country_code = :code AND cph.phn_phone_no = :number) $where) 
				AND bkg_status = 5 AND bkg_trip_duration IS NOT NULL 
				AND bkg.bkg_pickup_date < DATE_ADD(NOW(), INTERVAL 30 MINUTE) 
				AND IF(btk.bkg_ride_complete = 1 AND btk.bkg_trip_end_time IS NOT NULL, 
						DATE_ADD(btk.bkg_trip_end_time, INTERVAL(btk.bkg_trip_end_time + 30) MINUTE ),
						DATE_ADD(bkg.bkg_pickup_date, INTERVAL(bkg_trip_duration + 90) MINUTE )) 
					> NOW()";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	public static function getWhatsAppRef($bkgmodel, $orderby = 'date')
	{
		$where		 = '';
		$dateRange	 = '';

		if(!$bkgmodel->bkg_create_date1 || !$bkgmodel->bkg_create_date2)
		{
			$dateRange = " AND bkg.bkg_create_date > DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) ";
		}
		else
		{
			$fromDate	 = $bkgmodel->bkg_create_date1;
			$toDate		 = $bkgmodel->bkg_create_date2;
			$dateRange	 = " AND bkg.bkg_create_date<= '$toDate 23:59:59' AND bkg.bkg_create_date>= '$fromDate 00:00:00' ";
		}
		$sql = "SELECT
				DATE_FORMAT(date, '%Y-%m-%d') AS date,	
				DATE_FORMAT(date, '%x-%v') AS week,	
				CONCAT(DATE_FORMAT(date, '%x-%v'), ' (',DATE_FORMAT(SUBDATE(date, WEEKDAY(date)), '%D %b'),' - ',DATE_FORMAT(DATE_ADD(date, INTERVAL(6 -WEEKDAY(date)) DAY), '%D %b'),')') as weekLabel,
				DATE_FORMAT(date, '%b-%Y') AS monthname,	
				DATE_FORMAT(date, '%Y-%m') AS month, 
				'$orderby' groupType,					 
					SUM(cntBkg) totBkg,
					SUM(IF(catType = 1, cntBkg, 0)) cntBooking,
					SUM(IF(catType = 2, cntBkg, 0)) cntLead
				FROM
				(
					SELECT
						1 catType,
						COUNT(bkg_id) cntBkg,
						DATE_FORMAT(bkg.bkg_create_date, '%Y-%m-%d') AS date,	
						DATE_FORMAT(bkg.bkg_create_date, '%x-%v') AS week,				 
						DATE_FORMAT(bkg.bkg_create_date, '%Y-%m') AS month 						 
					FROM booking bkg
					JOIN `booking_add_info` bad ON
						bad.bad_bkg_id = bkg.bkg_id
					WHERE bkg.bkg_create_date > '2023-09-22 00:00:00' AND `bkg_info_source` IN (7,21) $dateRange
					GROUP BY $orderby
						UNION
					SELECT
						2 catType,
						COUNT(bkg_id) bkg,
						DATE_FORMAT(bkg.bkg_create_date, '%Y-%m-%d') AS date,	
						DATE_FORMAT(bkg.bkg_create_date, '%x-%v') AS week,	  
						DATE_FORMAT(bkg.bkg_create_date, '%Y-%m') AS month 					
					FROM booking_temp bkg
					WHERE bkg.bkg_create_date > '2023-09-22 00:00:00' $dateRange
						AND bkg.bkg_lead_source IN (6,14)
					GROUP BY $orderby
				) a
				GROUP BY $orderby";

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 =>
				[],
				'defaultOrder'	 => "$orderby DESC"],
			'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public static function getCancellationPenaltyCharges($bkgId)
	{
		$param	 = ['bkgId' => $bkgId];
		$sql	 = "SELECT bkg.bkg_id,bkg.bkg_status,
				COALESCE(btr.btr_vendor_unassign_penalty,0) vndPenalty, COALESCE(biv.bkg_cancel_charge,0) custCancelCharge 
			FROM booking bkg 
			JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id
			JOIN booking_trail btr ON btr.btr_bkg_id  = bkg.bkg_id 
				WHERE bkg.bkg_id=:bkgId";
		$row	 = DBUtil::queryRow($sql, null, $param);
		return $row;
	}

	public static function setAccountingFlagForZeroCancellationPenalty($bkgId, $userInfo)
	{
		$row = BookingSub::getCancellationPenaltyCharges($bkgId);
		if($row && (($row['vndPenalty'] + $row['custCancelCharge']) == 0 ))
		{
			$bPrefModel	 = BookingPref::model()->getByBooking($bkgId);
			$desc		 = "Accounting flag set for no cancellation and no penalty charged for cancelled booking.";
			$bPrefModel->setAccountingFlag($desc, $userInfo);
		}
	}

	/**
	 * 
	 * @param type $date1
	 * @param type $date2
	 * @param type $agentId
	 * @param type $command
	 * @return type
	 */
	public static function getPartnerTotalCountBookingReport($date1, $date2, $agentId = 0, $command = false)
	{
		if($agentId > 0)
		{
			$where = " AND bkg_agent_id=$agentId ";
		}
		$sql = "SELECT 
					SUM(a.net_base_amount) net_base_amount,
					SUM(a.totalamount) totalamount,
					SUM(a.gozoamount) gozoamount,
					SUM(a.total_served_booking) total_served_booking,
					SUM(a.total_book_local) total_book_local,
					SUM(a.total_book_outstation) total_book_outstation,
					SUM(IF(a.accountBalance>0,a.accountBalance,0)) receivable,
					SUM(IF(a.accountBalance<0,a.accountBalance,0)) payable,
					SUM(a.pts_wallet_balance) pts_wallet_balance
					
					FROM (
				SELECT
					bkg_agent_id,
					pts_wallet_balance,
					pts_ledger_balance,
					(pts_ledger_balance-pts_wallet_balance) accountBalance,
					SUM(IF(bkg_reconfirm_flag = 1,(bkg_net_base_amount), 0)) AS net_base_amount,
					SUM(IF(bkg_reconfirm_flag = 1,(bkg_total_amount), 0)) AS totalamount,
					SUM(IF(bkg_reconfirm_flag = 1,bkg_gozo_amount, 0)) AS gozoamount,
					COUNT(bkg_agent_id) AS cnt,
					SUM(IF(bkg_status IN (6,7),1,0)) AS total_served_booking,
					SUM(IF(bkg_booking_type IN (4,9,10,11,12,14,15,16),1,0)) AS total_book_local,
					SUM(IF(bkg_booking_type NOT IN (4,9,10,11,12,14,15,16),1,0)) AS total_book_outstation,
					ROUND(((SUM(IF(bkg_reconfirm_flag = 1, bkg_gozo_amount, 0))/SUM(IF(bkg_reconfirm_flag = 1, (bkg_net_base_amount), 0))  )*100),2) AS netgrossmargin,
					ROUND(((SUM(IF(bkg_reconfirm_flag = 1, bkg_gozo_amount, 0))/SUM(IF(bkg_reconfirm_flag = 1, (bkg_total_amount), 0))  )*100),2) AS totalgrossmargin,
					agt_company as partnername,
					GROUP_CONCAT(DISTINCT booking.bkg_id SEPARATOR ', ') AS booking_id,DATE(MAX(bkg_create_date)) AS lastBookingReceivedDate
				FROM   `booking`
					INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
					INNER JOIN booking_invoice ON bkg_id=biv_bkg_id
					INNER JOIN agents ON agents.agt_id=bkg_agent_id
					INNER JOIN partner_stats ON pts_agt_id = bkg_agent_id
				WHERE 1
					AND	booking.bkg_active = 1
					AND booking.bkg_status IN (2, 3, 4, 5, 6, 7)
					AND (booking.bkg_create_date BETWEEN '$date1 00:00:00' and '$date2 23:59:59')
					$where
					AND bkg_agent_id IS NOT NULL				
					AND bkg_agent_id NOT IN (450, 18190)
				GROUP by bkg_agent_id)a";
		return DBUtil::query($sql);
	}

	public static function revenue($params, $orderby = 'date', $partnerId, $type = DBUtil::ReturnType_Provider)
	{
		$from_date	 = $params['from_date'];
		$to_date	 = $params['to_date'];

		$where = '';

		if($from_date != '' && $to_date != '')
		{
			$where .= " AND (bkg_pickup_date BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59')";
		}

		$sqlData = "SELECT DATE_FORMAT(bkg_pickup_date, '%Y-%m-%d') AS date,DATE_FORMAT(bkg_pickup_date, '%x-%v') AS week,	
                        CONCAT(DATE_FORMAT(bkg_pickup_date, '%x-%v'), '\n', DATE_FORMAT(MIN(bkg_pickup_date), '%D %b'),' - ',DATE_FORMAT(MAX(bkg_pickup_date), '%D %b')) as weekLabel,
                        DATE_FORMAT(bkg_pickup_date, '%b-%Y') AS monthname,	DATE_FORMAT(bkg_pickup_date, '%Y-%m') AS month, 
                        DATE(MIN(bkg_pickup_date)) as minDate,'$orderby' groupType, SUM(atd.adt_amount) as totalAmount, 
                        COUNT(DISTINCT bkg_id) as totalBookings,
                        COUNT(DISTINCT IF(biv.bkg_net_advance_amount<>0 AND bkg_status=9, bkg_id, NULL)) as totalBookingsCancelled,
                        SUM(IF(bkg.bkg_status=9, atd1.adt_amount*-1, 0)) AS cancelCharge,
                        SUM(IF(bkg.bkg_id IS NOT NULL AND bkg.bkg_status<>9, atd1.adt_amount*-1, 0)) AS advanceUsed,
                        SUM(IF(atd1.adt_ledger_id=35, atd1.adt_amount*-1, 0)) AS commissionCredited
                    FROM `account_trans_details` atd
                    INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 49 AND act.act_active = 1 AND atd.adt_active = 1
                    INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id AND atd1.adt_ledger_id IN (13,35) AND atd1.adt_active = 1
                    INNER JOIN booking bkg ON bkg.bkg_id=atd1.adt_trans_ref_id AND atd1.adt_type=1 
                    INNER JOIN booking_invoice biv ON biv.biv_bkg_id=bkg.bkg_id 
                    WHERE atd.adt_trans_ref_id= $partnerId $where 
                    GROUP BY $orderby";

		if($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlData ) temp", DBUtil::SDB3(), $params);
			$dataprovider	 = new CSqlDataProvider($sqlData, array(
				"totalItemCount" => $count,
				"params"		 => $params,
				'db'			 => DBUtil ::SDB3(),
				"pagination"	 => array("pageSize" => 50),
				'sort'			 => array('attributes'	 => array('minDate,totalBookings'),
					'defaultOrder'	 => 'minDate DESC'
				)
			));
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sqlData, DBUtil::SDB3(), $params);
		}
	}

	/**
	 * Get Stats info for Rental type Bookings
	 * @param type $type
	 * @return array
	 */
	public static function getRentalStatsInfo($type = 0)
	{
		$arrData = ['9'	 => [
				'label'		 => 'Day Rental(4hr-40km)',
				'distance'	 => 40,
				'duration'	 => 240
			],
			'10' => [
				'label'		 => 'Day Rental(8hr-80km)',
				'distance'	 => 80,
				'duration'	 => 480
			],
			'16' => [
				'label'		 => 'Day Rental(10hr-100km)',
				'distance'	 => 100,
				'duration'	 => 600
			],
			'11' => [
				'label'		 => 'Day Rental(12hr-120km)',
				'distance'	 => 100,
				'duration'	 => 720
			]
		];

		if($type > 0 && in_array($type, [9, 10, 11, 16]))
		{
			return $arrData[$type];
		}

		return $arrData;
	}
    
    
    
    public static function getTrackTrip($params)
    {
            $from_date = $params['from_date'];
            $to_date   = $params['to_date'];

            $where = '1=1';

            if ($from_date != '' && $to_date != '')
            {
                $where .= " AND (bkg_pickup_date BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59')";
            }

            $sqlData = "SELECT DATE(bkg_pickup_date) pickupDate, count( DISTINCT bkg_id) as tot, GROUP_CONCAT(DISTINCT bkg_id) as bookings, GROUP_CONCAT(DISTINCT blg_user_type) as usrtTypes,
    COUNT(DISTINCT IF(btr.bkg_trip_end_time IS NOT NULL, bkg_id, NULL)) as totalCompletedTrips,
    COUNT( DISTINCT IF(blg_event_id = 215 AND blg_user_type  = 4,bkg_id,NULL)) as tripStartAdmin,
    COUNT( DISTINCT IF(blg_event_id = 216 AND blg_user_type  = 4,bkg_id,NULL)) as tripStopAdmin,
    COUNT( DISTINCT IF(blg_event_id = 93 AND blg_user_type  = 4,bkg_id,NULL)) as carArrivedAdmin,
    COUNT(DISTINCT IF(blg_event_id = 215 AND blg_user_type<>4  AND blg_user_type IS NOT NULL,bkg_id,NULL)) as tripStartDriver,
    COUNT(DISTINCT IF(blg_event_id = 216 AND blg_user_type<>4  AND blg_user_type IS NOT NULL,bkg_id,NULL)) as tripStopDriver,
    COUNT(DISTINCT IF(blg_event_id = 93 AND blg_user_type<>4  AND blg_user_type IS NOT NULL,bkg_id,NULL)) as carArrivedDriver
    FROM `booking`
    INNER JOIN booking_track btr ON btr.btk_bkg_id=bkg_id
    LEFT JOIN booking_log ON bkg_id = blg_booking_id 
    WHERE $where AND bkg_status IN(5,6,7) AND blg_event_id IN (215,216,93) 
      GROUP BY pickupDate";

//        $sqlData = "SELECT  count( DISTINCT bkg_id) as tot, GROUP_CONCAT(DISTINCT bkg_id) as bookings,DATE(bkg_pickup_date) pickupDate,
//
//COUNT( DISTINCT IF(blg_event_id = 215 AND blg_user_type  = 10,blg_id,NULL)) as tripStartAdmin,
//COUNT( DISTINCT IF(blg_event_id = 216 AND blg_user_type  = 10,blg_id,NULL)) as tripStopAdmin,
//COUNT( DISTINCT IF(blg_event_id = 93 AND blg_user_type  = 10,blg_id,NULL)) as carArrivedAdmin,
//
//
//COUNT(DISTINCT IF(blg_event_id = 215 AND blg_user_type  = 3,blg_id,NULL)) as tripStartDriver,
//COUNT(DISTINCT IF(blg_event_id = 216 AND blg_user_type  = 3,blg_id,NULL)) as tripStopDriver,
//COUNT(DISTINCT IF(blg_event_id = 93 AND blg_user_type  = 3,blg_id,NULL)) as carArrivedDriver
//
//
//FROM `booking`
//JOIN booking_log ON bkg_id = blg_booking_id  WHERE $where AND bkg_status IN(5,6,7) AND blg_event_id IN (215,216,93)
//  GROUP BY pickupDate ";
        
       // bkg_pickup_date>'2024-01-01 05:00:00'

//        if ($type == DBUtil::ReturnType_Provider)
//        {
            $count        = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlData ) temp", DBUtil::SDB3(), $params);
            $dataprovider = new CSqlDataProvider($sqlData, array(
                "totalItemCount" => $count,
                "params"         => $params,
                'db'             => DBUtil ::SDB3(),
                "pagination"     => array("pageSize" => 50),
              'sort'           => array('attributes'   => array('tripStartAdmin,tripStartDriver'),
                    'defaultOrder' => 'pickupDate DESC'
                )
            ));
            return $dataprovider;
//        }
//        else
//        {
//            return DBUtil::query($sqlData, DBUtil::SDB3(), $params);
//        }
    }

}
