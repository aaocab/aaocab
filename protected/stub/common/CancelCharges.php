<?php

namespace Stub\common;

class CancelCharges
{

	public $refund, $charges, $commission;

	/**
	 * @param array $slabs
	 * @param string $cancelDate
	 * @param integer $advance
	 * @param integer $commission
	 * @param integer $bkgId
	 * @param integer $cancelId
	 * @return integer $cancelCharge
	 */
	public static function init($slabs, $cancelDate, $advance, $commission = 0, $bkgId, $cancelId = 0)
	{
		$cancelCharge	 = self::getCharges($slabs, $cancelDate);
		$bkgModel		 = \Booking::model()->findByPk($bkgId);
		\Logger::trace("CancelCharges stub::getCharges cancelCharge: {$cancelCharge}");
		
		$detailsViewedDiffMinutes = \Filter::getTimeDiff($bkgModel->bkg_pickup_date, $bkgModel->bkgTrack->btk_drv_details_viewed_datetime);
		if($bkgModel->bkgTrack->btk_drv_details_viewed == 1 && $bkgModel->bkg_agent_id == null && $detailsViewedDiffMinutes > 120)
		{
			$cancelCharge = $advance;
			foreach($slabs as $key => $value)
			{
				if($value > 0)
				{
					$cancelCharge = ($advance > $value) ? $value : $advance;
					break;
				}
			}
			$refund = $advance - $cancelCharge;
			\Logger::trace("btk_drv_details_viewed cancelCharge: {$cancelCharge}");
		}
		
		if($cancelId > 0)
		{
			$cusPenalizedRule	 = \CancelReasons::getCustomerPenalizeRuleById($cancelId);
			$venPenalizedRule	 = \CancelReasons::getVendorPenalizeRuleById($cancelId);
			$cancelCharge		 = ($cusPenalizedRule <= 1) ? 0 : $cancelCharge;
		}
		\Logger::trace("CancelCharges stub:: cancel id condition cancelId:{$cancelId} cancelCharge: {$cancelCharge}");
		$refund	 = $advance - $cancelCharge;
		$refund	 = ($refund < 0) ? 0 : $refund;
		
		$obj			 = new CancelCharges();
		$obj->slabs		 = $slabs;
		$obj->charges	 = $cancelCharge;
		$obj->refund	 = $refund;
		$obj->commission = $commission;

		return $obj;
	}

	/**
	 * @param array $slabs
	 * @param integer $cancelDate
	 * @return integer $cancelCharge
	 */
	public static function getCharges($slabs, $cancelDate)
	{
		foreach($slabs as $key => $value)
		{
			if($key == -1)
			{
				$cancelCharge = $value;
				continue;
			}
			$slabDate	 = \DateTimeFormat::SQLDateTimeToDateTime($key);
			$slabDate	 = \DateTimeFormat::DateTimeToSQLDateTime($slabDate);
			if($cancelDate <= $slabDate)
			{
				$cancelCharge = $value;
				break;
			}
		}
		return $cancelCharge;
	}
}
