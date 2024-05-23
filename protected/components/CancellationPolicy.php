<?php

class CancellationPolicy extends CComponent
{

	const POLICY_TYPE_FLEXI			 = "FLEXI";
	const POLICY_TYPE_SUPER_FLEXI		 = "SUPER_FLEXI";
	const POLICY_TYPE_NON_REFUNDABLE	 = "NON_REFUNDABLE";

	public Static $policies = [
		"0"	 => ["1" => ["9" => CancellationPolicy::POLICY_TYPE_NON_REFUNDABLE], "2" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "3" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "4" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "5" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "6" => ["9" => CancellationPolicy::POLICY_TYPE_NON_REFUNDABLE]],
		"1"	 => ["1" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "2" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "3" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "4" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "5" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "6" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI]],
		"2"	 => ["1" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "2" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "3" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "4" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "5" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI], "6" => ["4" => CancellationPolicy::POLICY_TYPE_FLEXI]]
	];


	public static function initiateRequest($bookingModel)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$rule		 = $bookingModel->bkgPref->bkg_cancel_rule_id;
		$methodName	 = "applyRule" . $rule;
		Logger::trace("initiateRequest  Booking Id: " . $bookingModel->bkg_id . "bkg_cancel_rule_id:" . $rule . "applyRule" . $rule);
		$refundArr	 = CancellationPolicy::$methodName($bookingModel);
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $refundArr;
	}

	/**
	 * 
	 * @deprecated since 08/06/2020 by ramala   new added---> CancellationPolicyDetails::getRulesData()
	 */
	public static function rulesData()
	{
		$rules = [
			'1'	 => [
				"minCharges"	 => ["default" => ["type" => 1, "value" => 25], "min" => ["type" => 2, "value" => 500], "max" => ["type" => 2, 0]],
				"timeRules"		 => ["freeCancelMinute" => 0, "workingMinuteBeforePickup" => false, "minutesBeforePickup" => 360, "noShowTime" => 0],
				"chargesNoShow"	 => ["default" => ["type" => 1, "value" => 30], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]]
			],
			'2'	 => [
				"minCharges"	 => ["default" => ["type" => 1, "value" => 25], "min" => ["type" => 2, "value" => 500], "max" => ["type" => 2, 0]],
				"chargesNoShow"	 => ["default" => ["type" => 1, "value" => 30], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]],
				"timeRules"		 => ["freeCancelMinute" => 0, "workingMinuteBeforePickup" => false, "minutesBeforePickup" => 240, "noShowTime" => 0]
			],
			'3'	 => [
				"minCharges"	 => ["default" => ["type" => 1, "value" => 25], "min" => ["type" => 2, "value" => 500], "max" => ["type" => 2, 0]],
				"chargesNoShow"	 => ["default" => ["type" => 1, "value" => 30], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]],
				"timeRules"		 => ["freeCancelMinute" => 0, "workingMinuteBeforePickup" => false, "minutesBeforePickup" => 120, "noShowTime" => 0]
			],
			'4'	 => [
				"minCharges"	 => ["default" => ["type" => 1, "value" => 50], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 1000]],
				"timeRules"		 => ["freeCancelMinute" => 30, "workingMinuteBeforePickup" => false, "minutesBeforePickup" => 360, "noShowTime" => 60],
				"chargesNoShow"	 => ["default" => ["type" => 1, "value" => 100], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]]
			],
			'5'	 => [
				"minCharges"	 => ["default" => ["type" => 1, "value" => 100], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]],
				"timeRules"		 => ["freeCancelMinute" => 30, "workingMinuteBeforePickup" => false, "minutesBeforePickup" => 0, "noShowTime" => 60],
				"chargesNoShow"	 => ["default" => ["type" => 1, "value" => 100], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]]
			],
			'6'	 => [
				"minCharges"	 => ["default" => ["type" => 1, "value" => 25], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]],
				"chargesNoShow"	 => ["default" => ["type" => 1, "value" => 25], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]],
				"timeRules"		 => ["freeCancelMinute" => 0, "workingMinuteBeforePickup" => false, "minutesBeforePickup" => 240, "noShowTime" => 60]
			],
			'7'	 => [
				"minCharges"	 => ["default" => ["type" => 1, "value" => 25], "min" => ["type" => 2, "value" => 200], "max" => ["type" => 2, 500]],
				"chargesNoShow"	 => ["default" => ["type" => 1, "value" => 30], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]],
				"timeRules"		 => ["freeCancelMinute" => 0, "workingMinuteBeforePickup" => false, "minutesBeforePickup" => 120, "noShowTime" => 60]
			],
			'8'	 => [
				"minCharges"	 => ["default" => ["type" => 1, "value" => 25], "min" => ["type" => 2, "value" => 500], "max" => ["type" => 2, 0]],
				"chargesNoShow"	 => ["default" => ["type" => 1, "value" => 30], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]],
				"timeRules"		 => ["freeCancelMinute" => 0, "workingMinuteBeforePickup" => false, "minutesBeforePickup" => 240, "noShowTime" => 0]
			],
			'9'	 => [
				"minCharges"	 => ["default" => ["type" => 1, "value" => 100], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]],
				"timeRules"		 => ["freeCancelMinute" => 30, "workingMinuteBeforePickup" => false, "minutesBeforePickup" => false, "noShowTime" => 0],
				"chargesNoShow"	 => ["default" => ["type" => 1, "value" => 100], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]]
			],
			'17' => [
				"minCharges"	 => ["default" => ["type" => 1, "value" => 25], "min" => ["type" => 2, "value" => 500], "max" => ["type" => 2, 0]],
				"timeRules"		 => ["freeCancelMinute" => 0, "workingMinuteBeforePickup" => false, "minutesBeforePickup" => 360, "noShowTime" => 0],
				"chargesNoShow"	 => ["default" => ["type" => 1, "value" => 30], "min" => ["type" => 2, "value" => 0], "max" => ["type" => 2, 0]]
			]
		];

		return $rules;
	}

	public static function getRule($id)
	{
		$rules = CancellationPolicyDetails::getRulesData();
		return $rules[$id];
	}

	/**
	 * This function is used to get cancel rule Id
	 * @param integer $agentId
	 * @param integer $svcId
	 * @param integer $sccId
	 * @return integer $ruleId
	 */
	public static function getCancelRuleId($agentId, $svcId, $fcity, $tcity, $tripType, $isGozonow = 0, $fromTopZoneCat = false)
	{
		$isB2C = (UserInfo::getUserType() == UserInfo::TYPE_CONSUMER);
		if ($agentId == '' || $agentId == Yii::app()->params['gozoChannelPartnerId'] || $isB2C)
		{
			$agentId = Yii::app()->params['gozoChannelPartnerId'];
		}
		//$ruleId = PartnerSvcSettings::getCancelRuleId($svcId, $agentId);
		$ruleId = PartnerCancelRule::getCancelRuleId($fcity, $tcity, $svcId, $tripType, $agentId, $isGozonow, $fromTopZoneCat);
		if (!$ruleId)
		{
			$sccId		 = SvcClassVhcCat::model()->getClassById($svcId);
			$svcModel	 = ServiceClass::model()->findByPk($sccId);
			$ruleId		 = $svcModel->scc_cancel_rule;
		}
		return $ruleId;
	}

	/**
	 * This function is used for calculate cancellation charges
	 * Applicable for b2c value tier as on 2021-07-16
	 * free Cancellation until 6 hours of pick up
	 * Cancellation until 2 working hours of pickup
	 * 25% or max 500  cancellation fee after 6 hours
	 * Free Cancellation within 15 mins of booking
	 * 30 % cancellation fee after pickup time
	 * @param Booking $bkgModel
	 * @return Stub\common\CancelCharges
	 */
	public static function applyRule1($bkgModel)
	{
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}

		$commission	 = 0;
		$advance	 = 0;

		$rule = self::getRule("1");

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;
		if ($confirmTime == '')
		{
			$confirmTime = Filter::getDBDateTime();
		}
		$pickupDate = $bkgModel->bkg_pickup_date;

		$freeCancelTime	 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime		 = self::getNowShowTime($timeRules, $pickupDate);

		$slabs[$freeCancelTime] = 0;

		if ($bkgModel->bkgInvoice->bkg_advance_amount > 0)
		{
			$advance = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		}
		else
		{
			if ($bkgModel->bkg_agent_id == null)
			{
				$advance = $bkgModel->bkgInvoice->calculateMinPayment();
			}
		}

		$cancelCharge	 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge	 = min([$advance, $cancelCharge]);

		$slabs[$noShowTime]	 = $cancelCharge;
		$cancelCharge		 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge		 = min([$advance, $cancelCharge]);

		$slabs["-1"]		 = $cancelCharge;
		$objCancelCharges	 = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		return $objCancelCharges;
	}

	/**
	 * This function is used for calculate cancellation charges
	 * Applicable for b2c value plus tier as on 2021-07-16
	 * free Cancellation until 4 hours of pick up
	 * Cancellation until 2 working hours of pickup
	 * 25% or max 500  cancellation fee after 4 hours
	 * Free Cancellation within 15 mins of booking
	 * 30 % cancellation fee after pickup time
	 * @param Booking $bkgModel
	 * @return Stub\common\CancelCharges
	 */
	public static function applyRule2($bkgModel)
	{
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}

		$commission	 = 0;
		$advance	 = 0;

		$rule = self::getRule("2");

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;
		if ($confirmTime == '')
		{
			$confirmTime = Filter::getDBDateTime();
		}
		$pickupDate = $bkgModel->bkg_pickup_date;

		$freeCancelTime	 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime		 = self::getNowShowTime($timeRules, $pickupDate);

		$slabs[$freeCancelTime] = 0;

		if ($bkgModel->bkgInvoice->bkg_advance_amount > 0)
		{
			$advance = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		}
		else
		{
			if ($bkgModel->bkg_agent_id == null)
			{
				$advance = $bkgModel->bkgInvoice->calculateMinPayment();
			}
		}

		$cancelCharge	 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge	 = min([$advance, $cancelCharge]);

		$slabs[$noShowTime]	 = $cancelCharge;
		$cancelCharge		 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge		 = min([$advance, $cancelCharge]);

		$slabs["-1"] = $cancelCharge;

		$objCancelCharges = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		return $objCancelCharges;
	}

	/**
	 * This function is used for calculate cancellation charges
	 * Applicable for b2c select tier as on 2021-07-16
	 * free Cancellation until 2 hours of pick up
	 * Cancellation until 2 working hours of pickup
	 * 25% or max 500  cancellation fee after 2 hours
	 * Free Cancellation within 15 mins of booking
	 * 30 % cancellation fee after pickup time
	 * @param Booking $bkgModel
	 * @return Stub\common\CancelCharges
	 */
	public static function applyRule3($bkgModel)
	{
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}
		$commission	 = 0;
		$advance	 = 0;

		$rule = self::getRule("3");

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;
		if ($confirmTime == '')
		{
			$confirmTime = Filter::getDBDateTime();
		}
		$pickupDate = $bkgModel->bkg_pickup_date;

		$freeCancelTime	 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime		 = self::getNowShowTime($timeRules, $pickupDate);

		$slabs[$freeCancelTime] = 0;
		if ($bkgModel->bkgInvoice->bkg_advance_amount > 0)
		{
			$advance = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		}
		else
		{
			if ($bkgModel->bkg_agent_id == null)
			{
				$advance = $bkgModel->bkgInvoice->calculateMinPayment();
			}
		}
		$cancelCharge	 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge	 = min([$advance, $cancelCharge]);

		$slabs[$noShowTime]	 = $cancelCharge;
		$cancelCharge		 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge		 = min([$advance, $cancelCharge]);

		$slabs["-1"] = $cancelCharge;

		$objCancelCharges = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		return $objCancelCharges;
	}

	/**
	 * This function is used for calculate cancellation charges
	 * Applicable for Flexi Go-MMT  (MMT value tier and value+) as on 2021-07-16
	 * free Cancellation until 6 hours of pick up
	 * 50% or max 1000  cancellation fee after 6 hours
	 * Free Cancellation within 30 mins of booking 
	 * No show time 60 min implemented
	 * 100 % cancellation fee after pickup time
	 * @param Booking $bkgModel
	 * @return Stub\common\CancelCharges
	 */
	public static function applyRule4($bkgModel)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}
		$commission = 0;

		$rule = self::getRule("4");

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;
		if ($confirmTime == '' && in_array($bkgModel->bkg_status, [1, 15]))
		{
			$confirmTime = Filter::getDBDateTime();
		}
		if ($confirmTime == '')
		{
			$confirmTime = $bkgModel->bkg_create_date;
		}
		$pickupDate = $bkgModel->bkg_pickup_date;

		$freeCancelTime	 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime		 = self::getNowShowTime($timeRules, $pickupDate);

		$slabs[$freeCancelTime]	 = 0;
		$advance				 = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		if (in_array($bkgModel->bkg_status, [1, 15]))
		{
			$advance = $bkgModel->bkgInvoice->calculateMinPayment($bkgModel->bkgInvoice->bkg_total_amount);
		}
		if ($bkgModel->bkg_status == 9)
		{
			$advance = $bkgModel->bkgInvoice->bkg_advance_amount;
		}
		$cancelCharge	 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge	 = min([$advance, $cancelCharge]);

		$slabs[$noShowTime]	 = $cancelCharge;
		$cancelCharge		 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge		 = min([$advance, $cancelCharge]);

		$slabs["-1"] = $cancelCharge;
		Logger::trace("applyRule4 ---- Booking Id: " . $bkgModel->bkg_id . "slabs:" . json_encode($slabs));

		$objCancelCharges = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $objCancelCharges;
	}

	/**
	 * This function is used for calculate cancellation charges
	 * Applicable for Super Flexi Go-MMT  (select plus tier) as on 2021-07-16
	 * Free Cancellation within 30 mins of booking
	 * 100 % cancellation fee after pickup time
	 * No show time 60 min implemented
	 * @param Booking $bkgModel
	 * @return Stub\common\CancelCharges
	 */
	public static function applyRule5($bkgModel)
	{
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}
		$commission = 0;

		$rule = self::getRule("5");

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;
		if ($confirmTime == '' && in_array($bkgModel->bkg_status, [1, 15]))
		{
			$confirmTime = Filter::getDBDateTime();
		}
		if ($confirmTime == '')
		{
			$confirmTime = $bkgModel->bkg_create_date;
		}
		$pickupDate		 = $bkgModel->bkg_pickup_date;
		Logger::trace("applyRule5 cancelDate:{$cancelDate}, total_amount: {$amount}, confirmTime: {$confirmTime}, bkg_status: {$bkgModel->bkg_status} ");
		$freeCancelTime	 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime		 = self::getNowShowTime($timeRules, $pickupDate);

		$slabs[$freeCancelTime]	 = 0;
		$advance				 = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		if (in_array($bkgModel->bkg_status, [1, 15]))
		{
			$advance = $bkgModel->bkgInvoice->calculateMinPayment($bkgModel->bkgInvoice->bkg_total_amount);
			Logger::trace("calculateMinPayment advance: {$advance}");
		}
		if ($bkgModel->bkg_status == 9)
		{
			$advance = $bkgModel->bkgInvoice->bkg_advance_amount;
		}
		$cancelCharge	 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge	 = min([$advance, $cancelCharge]);

		$slabs[$noShowTime]	 = $cancelCharge;
		$cancelCharge		 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge		 = min([$advance, $cancelCharge]);

		$slabs["-1"]		 = $cancelCharge;
		Logger::trace("advance: {$advance} slabs: " . json_encode($slabs));
		$objCancelCharges	 = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		return $objCancelCharges;
	}

	/**
	 * This function is used for calculate cancellation charges
	 * Applicable for EMT as on 2021-07-16
	 * free Cancellation until 4 hours of pick up
	 * Cancellation until 2 working hours of pickup
	 * 25% cancellation fee after 4 hours
	 * Free Cancellation within 15 mins of booking
	 * 30 % cancellation fee after pickup time
	 * No show time 60 mins
	 * @param Booking $bkgModel
	 * @return Stub\common\CancelCharges
	 */
	public static function applyRule6($bkgModel)
	{
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}
		$commission = 0;

		$rule = self::getRule("6");

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;
		if ($confirmTime == '')
		{
			$confirmTime = Filter::getDBDateTime();
		}
		$pickupDate = $bkgModel->bkg_pickup_date;

		$freeCancelTime	 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime		 = self::getNowShowTime($timeRules, $pickupDate);

		$slabs[$freeCancelTime]	 = 0;
		$advance				 = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		$cancelCharge			 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge			 = min([$advance, $cancelCharge]);

		$slabs[$noShowTime]	 = $cancelCharge;
		$cancelCharge		 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge		 = min([$advance, $cancelCharge]);

		$slabs["-1"] = $cancelCharge;

		$objCancelCharges = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		return $objCancelCharges;
	}

	/**
	 * This function is used for calculate cancellation charges
	 * Applicable for Ebix as on 2021-07-16
	 * Cancellation until 2 hours of pick up
	 * Cancellation until 2 working hours of pickup
	 * 25% or min 200 and max 500 cancellation fee after 2  hours
	 * Free Cancellation within 15 mins of booking
	 * 30 % cancellation fee after pickup time
	 * No show time 60 mins
	 * @param Booking $bkgModel
	 * @return Stub\common\CancelCharges
	 */
	public static function applyRule7($bkgModel)
	{
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}
		$commission = 0;

		$rule = self::getRule("7");

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;
		if ($confirmTime == '')
		{
			$confirmTime = Filter::getDBDateTime();
		}
		$pickupDate = $bkgModel->bkg_pickup_date;

		$freeCancelTime	 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime		 = self::getNowShowTime($timeRules, $pickupDate);

		$slabs[$freeCancelTime]	 = 0;
		$advance				 = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		$cancelCharge			 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge			 = min([$advance, $cancelCharge]);

		$slabs[$noShowTime]	 = $cancelCharge;
		$cancelCharge		 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge		 = min([$advance, $cancelCharge]);

		$slabs["-1"] = $cancelCharge;

		$objCancelCharges = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		return $objCancelCharges;
	}

	/**
	 * This function is used for calculate cancellation charges
	 * Initially applicable for upCurve as on 2021-07-16
	 * Cancellation until 4 hours of pick up
	 * Cancellation until 2 working hours of pickup
	 * 25% or 500 cancellation fee after 2  hours
	 * Free Cancellation within 15 mins of booking
	 * 30 % cancellation fee after pickup time
	 * @param Booking $bkgModel
	 * @return Stub\common\CancelCharges
	 */
	public static function applyRule8($bkgModel)
	{
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}
		$commission = 0;

		$rule = self::getRule("8");

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;
		if ($confirmTime == '')
		{
			$confirmTime = Filter::getDBDateTime();
		}
		$pickupDate = $bkgModel->bkg_pickup_date;

		$freeCancelTime	 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime		 = self::getNowShowTime($timeRules, $pickupDate);

		$slabs[$freeCancelTime]	 = 0;
		$advance				 = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		$cancelCharge			 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge			 = min([$advance, $cancelCharge]);

		$slabs[$noShowTime]	 = $cancelCharge;
		$cancelCharge		 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge		 = min([$advance, $cancelCharge]);

		$slabs["-1"] = $cancelCharge;

		$objCancelCharges = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		return $objCancelCharges;
	}

	/**
	 * This function is used for calculate cancellation charges
	 * Applicable for MMT as on 2021-07-16
	 * 100 % cancellation fee after pickup time
	 * @param Booking $bkgModel
	 * @return Stub\common\CancelCharges
	 */
	public static function applyRule9($bkgModel)
	{
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}
		$commission = 0;

		$rule = self::getRule("9");

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;

		if ($confirmTime == '' && in_array($bkgModel->bkg_status, [1, 15]))
		{
			$confirmTime = Filter::getDBDateTime();
		}
		if ($confirmTime == '')
		{
			$confirmTime = $bkgModel->bkg_create_date;
		}
		$pickupDate = $bkgModel->bkg_pickup_date;

		$freeCancelTime	 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime		 = self::getNowShowTime($timeRules, $pickupDate);

		$slabs[$freeCancelTime]	 = 0;
		$advance				 = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		if (in_array($bkgModel->bkg_status, [1, 15]))
		{
			$advance = $bkgModel->bkgInvoice->calculateMinPayment($bkgModel->bkgInvoice->bkg_total_amount);
		}
		if ($bkgModel->bkg_status == 9)
		{
			$advance = $bkgModel->bkgInvoice->bkg_advance_amount;
		}
		$cancelCharge	 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge	 = min([$advance, $cancelCharge]);

		$slabs[$noShowTime]	 = $cancelCharge;
		$cancelCharge		 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge		 = min([$advance, $cancelCharge]);

		$slabs["-1"] = $cancelCharge;

		$objCancelCharges = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		return $objCancelCharges;
	}

	public static function applyRule17($bkgModel)
	{
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}
		$commission = 0;

		$rule = self::getRule("17");

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;
		if ($confirmTime == '')
		{
			$confirmTime = Filter::getDBDateTime();
		}
		$pickupDate				 = $bkgModel->bkg_pickup_date;
		$freeCancelTime			 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime				 = self::getNowShowTime($timeRules, $pickupDate);
		$slabs[$freeCancelTime]	 = 0;
		$advance				 = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		$cancelCharge			 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge			 = min([$advance, $cancelCharge]);
		$slabs[$noShowTime]		 = $cancelCharge;
		$cancelCharge			 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge			 = min([$advance, $cancelCharge]);
		$slabs["-1"]			 = $cancelCharge;
		$objCancelCharges		 = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		return $objCancelCharges;
	}

	/**
	 * This function is used for calculate cancel charge
	 * Applicable for b2c value plus and select tier
	 * @param array $charges 
	 * 			["default" => ["type"=>1, "value"=>5], "min" => ["type"=>2, "value"=>0], "max" => ["type"=>2, "value"=>500] ]
	 * @param array $timeRules 
	 * 		["freeCancelMinute"=>15, "workingMinuteBeforePickup" => 60, "minutesBeforePickup" => 120, "noShowTime" => 0]
	 * @param Booking $bkgModel
	 * @param array $noRefundCharges 
	 * 			["default" => ["type"=>1, "value"=>100], "min" => ["type"=>2, "value"=>0], "max" => ["type"=>2, "value"=>0] ]
	 * @return int
	 */
	public static function CalculateRule1($bkgModel, $charges, $timeRules, $noRefundCharges = [])
	{
		if (CancelReasons::model()->excludeCancellationCharge($bkgModel->bkg_cancel_id))
		{
			$cancelCharge = 0;
			return $cancelCharge;
		}
		if ($bkgModel->bkgTrack->btk_drv_details_viewed == 1)
		{
			$cancelCharge = $bkgModel->bkgInvoice->getAdvanceReceived(false);
			return $cancelCharge;
		}
		$amount = $bkgModel->bkgInvoice->bkg_total_amount;

		$confirmTimeElapsed		 = Filter::getTimeDiff(Filter::getDBDateTime(), $bkgModel->bkgTrail->bkg_confirm_datetime);
		$minutesToPickup		 = Filter::getTimeDiff($bkgModel->bkg_pickup_date); //Filter::getDBDateTime()
		$workingMinutesToPickup	 = Filter::CalcWorkingMinutes(Filter::getDBDateTime(), $bkgModel->bkg_pickup_date);

		$cancelCharge = self::CalculateCharges($charges, $amount);

		if ($timeRules["noShowTime"] != false && $minutesToPickup < $timeRules["noShowTime"])
		{
			$cancelCharge = self::CalculateCharges($noRefundCharges, $amount);
		}

		if ($confirmTimeElapsed < $timeRules["freeCancelMinute"])
		{
			$cancelCharge = 0;
		}

		if ($timeRules["minutesBeforePickup"] !== false && $minutesToPickup > $timeRules["minutesBeforePickup"])
		{
			$cancelCharge = 0;
		}

		if ($timeRules["workingMinuteBeforePickup"] !== false && $workingMinutesToPickup > $timeRules["workingMinuteBeforePickup"])
		{
			$cancelCharge = 0;
		}


		return $cancelCharge;
	}

	/**
	 * This function is used for calculate cancellation charges
	 * Applicable for B2C (Z1,Z2 CNG) (Z2 value) 
	 * free Cancellation until 24 hours of pick up
	 * 25% or max 500  cancellation fee after 24 hours
	 * Free Cancellation within 30 mins of booking 
	 * No show time 0 min implemented No show charge 30%
	 * 100 % cancellation fee after pickup time
	 * @param Booking $bkgModel
	 * @return Stub\common\CancelCharges
	 */
	public static function applyRule18($bkgModel)
	{
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}
		$commission = 0;

		$rule = self::getRule("18");

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;
		if ($confirmTime == '' && in_array($bkgModel->bkg_status, [1, 15]))
		{
			$confirmTime = Filter::getDBDateTime();
		}
		if ($confirmTime == '')
		{
			$confirmTime = $bkgModel->bkg_create_date;
		}
		$pickupDate = $bkgModel->bkg_pickup_date;

		$freeCancelTime	 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime		 = self::getNowShowTime($timeRules, $pickupDate);

		$slabs[$freeCancelTime]	 = 0;
		$advance				 = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		if (in_array($bkgModel->bkg_status, [1, 15]))
		{
			$advance = $bkgModel->bkgInvoice->calculateMinPayment($bkgModel->bkgInvoice->bkg_total_amount);
		}
		if ($bkgModel->bkg_status == 9)
		{
			$advance = $bkgModel->bkgInvoice->bkg_advance_amount;
		}
		$cancelCharge	 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge	 = min([$advance, $cancelCharge]);

		$slabs[$noShowTime]	 = $cancelCharge;
		$cancelCharge		 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge		 = min([$advance, $cancelCharge]);

		$slabs["-1"] = $cancelCharge;

		$objCancelCharges = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		return $objCancelCharges;
	}

	/**
	 * @param array $charges ["default" => ["type"=>1, "value"=>5], "min" => ["type"=>2, "value"=>0], "max" => ["type"=>2, "value"=>500] ]
	 * @return int
	 */
	public static function CalculateCharges($charges, $amount)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		Logger::trace("CalculateCharges start1 : amount:" . $amount);

		$defaultConfig	 = $charges["default"];
		$defaultCharge	 = self::calculateChargeType($defaultConfig["type"], $defaultConfig["value"], $amount);
		Logger::trace("CalculateCharges step_1 : defaultCharge: " . $defaultCharge . "type" . $defaultConfig["type"] . "value" . $defaultConfig["value"] . "amount:" . $amount);

		$minConfig	 = $charges["min"];
		$minCharge	 = self::calculateChargeType($minConfig["type"], $minConfig["value"], $amount);
		Logger::trace("CalculateCharges step_2 : minCharge: " . $minCharge . "type" . $minConfig["type"] . "value" . $minConfig["value"] . "amount:" . $amount);

		$maxConfig	 = $charges["max"];
		$maxCharge	 = self::calculateChargeType($maxConfig["type"], $maxConfig["value"], $amount);
		Logger::trace("CalculateCharges step_3 : maxCharge: " . $maxCharge . "type" . $maxConfig["type"] . "value" . $maxConfig["value"] . "amount:" . $amount);

		$charge = $defaultCharge;

		if ($minCharge > 0)
		{
			$charge = max($minCharge, $charge);
		}

		if ($maxCharge > 0)
		{
			$charge = min($maxCharge, $charge);
		}
		Logger::trace("CalculateCharges step_4 : return Charge: " . $charge);
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $charge;
	}

	/**
	 * @param int $type
	 * @param int $value
	 * @param int $amount Description
	 * @return int
	 */
	public static function calculateChargeType($type, $value, $amount)
	{
		$charges = ($type == 1) ? round($value * $amount * 0.01) : $value;
		$charges = max([0, $charges]);
		return $charges;
	}

	public static function getPolicy($cityCategory, $sccId)
	{
		$cancelPolicy = self::$policies[$cityCategory][$sccId];
		if (!isset($cancelPolicy) || count($cancelPolicy) <= 0)
		{
			$cancelPolicy = self::$policies[0][$sccId];
		}
		$arrCancelRule['ruleId'] = key($cancelPolicy);
		$arrCancelRule['rule']	 = current($cancelPolicy);

		return $arrCancelRule;
	}

	public static function getApplicableCancelTime($timeRules, $confirmDate, $pickupDate)
	{
		$freeCancelTime	 = DateTimeFormat::SQLDateTimeToDateTime($confirmDate)->add(new DateInterval('PT' . $timeRules["freeCancelMinute"] . 'M'));
		$freeCancelTime	 = DateTimeFormat::DateTimeToSQLDateTime($freeCancelTime);
		$type			 = 0;
		$time			 = $timeRules["freeCancelMinute"];
		if ($timeRules["workingMinuteBeforePickup"] !== false)
		{
			$date		 = Filter::subWorkingMinutes($timeRules["workingMinuteBeforePickup"], $pickupDate);
			$dateTime	 = DateTimeFormat::SQLDateTimeToDateTime($date);
			if ($freeCancelTime < $dateTime)
			{
				$type	 = 1;
				$time	 = $timeRules["workingMinuteBeforePickup"];
			}

			$freeCancelTime = max([$freeCancelTime, $dateTime]);
		}
		if ($timeRules["minutesBeforePickup"] !== false)
		{
			$date		 = DateTimeFormat::SQLDateTimeToDateTime($pickupDate)->sub(new DateInterval('PT' . $timeRules["minutesBeforePickup"] . 'M'));
			$dateTime	 = DateTimeFormat::DateTimeToSQLDateTime($date);
			if ($freeCancelTime < $dateTime)
			{
				$type	 = 2;
				$time	 = $timeRules["minutesBeforePickup"];
			}

			$freeCancelTime = max([$freeCancelTime, $dateTime]);
		}

		return ["type" => $type, "duration" => $time, "cancelTime" => $freeCancelTime];
	}

	public static function getCancelTimeText($ruleId, $pickupDate, $confirmDate = null)
	{
		$text = false;
		try
		{
			$rules	 = CancellationPolicyDetails::getRulesData();
			$rule	 = $rules[$ruleId]["timeRules"];
			if ($confirmDate == null)
			{
				$confirmDate = Filter::getDBDateTime();
			}

			$applicableTime = self::getApplicableCancelTime($rule, $confirmDate, $pickupDate);

			if ($applicableTime["type"] == 0)
			{
				$text = ($applicableTime["duration"] == 0) ? "" : "within " . Filter::getTimeDurationbyMinute($applicableTime["duration"], 1) . " of booking";
			}
			if ($applicableTime["type"] == 1)
			{
				$text = "before " . DateTimeFormat::SQLDateTimeToLocaleDateTime($applicableTime["cancelTime"]) . " of pickup";
			}
			if ($applicableTime["type"] == 2)
			{
				$text = "before " . Filter::getTimeDurationbyMinute($applicableTime["duration"], 1) . " of pickup";
			}
		}
		catch (Exception $exc)
		{
			Logger::error($exc);
		}

		return $text;
	}

	public static function getFreeCancelTime($timeRules, $confirmDate, $pickupDate)
	{
		$freeCancelTime	 = DateTimeFormat::SQLDateTimeToDateTime($confirmDate)->add(new DateInterval('PT' . $timeRules["freeCancelMinute"] . 'M'));
		$freeCancelTime	 = DateTimeFormat::DateTimeToSQLDateTime($freeCancelTime);
		if ($timeRules["workingMinuteBeforePickup"] !== false)
		{
			$date			 = Filter::subWorkingMinutes($timeRules["workingMinuteBeforePickup"], $pickupDate);
			$dateTime		 = DateTimeFormat::SQLDateTimeToDateTime($date);
			$freeCancelTime	 = max([$freeCancelTime, $dateTime]);
		}
		if ($timeRules["minutesBeforePickup"] !== false)
		{
			$date			 = DateTimeFormat::SQLDateTimeToDateTime($pickupDate)->sub(new DateInterval('PT' . $timeRules["minutesBeforePickup"] . 'M'));
			$dateTime		 = DateTimeFormat::DateTimeToSQLDateTime($date);
			$freeCancelTime	 = max([$freeCancelTime, $dateTime]);
		}
		return $freeCancelTime;
	}

	public static function getNowShowTime($timeRules, $pickupDate)
	{
		$noShowTime	 = DateTimeFormat::SQLDateTimeToDateTime($pickupDate)->sub(new DateInterval('PT' . $timeRules["noShowTime"] . 'M'));
		$noShowTime	 = DateTimeFormat::DateTimeToSQLDateTime($noShowTime);
		return $noShowTime;
	}

	/**
	 * @deprecated since version 08/06/2022 by ramala  new added  CancellationPolicyDetails::getCodeById(id)
	 * 
	 * This function is used to return the policy type based on cancel rule 
	 * @param type (int) $cancelId
	 * @param type (int) $agentId
	 * @return string $policyType
	 */
	public static function getPolicyType($cancelId, $agentId = NULL)
	{
		switch ($cancelId)
		{
			case 1:
				if ($agentId)
				{
					if (!in_array($agentId, [18190, 450, 30242, 30228, 3936]))
					{
						$policyType = "B2B_VALUE";
					}
				}
				else
				{
					$policyType = "B2C_VALUE";
				}
				break;
			case 2:
				if ($agentId)
				{
					if ($agentId == 18190 || $agentId == 450)
					{
						$policyType = "MMT_SELECT_PLUS";
					}
					if (!in_array($agentId, [18190, 450, 30242, 30228, 3936]))
					{
						$policyType = "B2B_VALUE_PLUS";
					}
				}
				else
				{
					$policyType = "B2C_VALUE_PLUS";
				}
				break;
			case 3:
				if ($agentId)
				{
					if (!in_array($agentId, [18190, 450, 30242, 30228, 3936]))
					{
						$policyType = "B2B_SELECT";
					}
				}
				else
				{
					$policyType = "B2C_SELECT";
				}
				break;
			case 4:
				$policyType	 = "FLEXI";
				break;
			case 5:
				$policyType	 = "SUPER_FLEXI";
				break;
			case 6:
			case 14:
				if ($agentId == 30228)
				{
					$policyType = ($cancelId == 14) ? "EMT_OLD" : "EMT";
				}
				else if ($agentId == NULL || $agentId == '' || $agentId == 0)
				{
					$policyType = "B2C_VALUE_OLD";
				}
				break;
			case 7:
			case 15:
				if ($agentId == 18190 || $agentId == 450)
				{
					$policyType = "MMT_VALUE";
				}
				else if ($agentId == 30242)
				{
					$policyType = ($cancelId == 15) ? "EBIX_OLD" : "EBIX";
				}
				break;
			case 8:
				$policyType = "UPCURVE";
				break;
			case 9:
				if ($agentId == 18190 || $agentId == 450)
				{
					$policyType = "NON_REFUNDABLE";
				}
				else if ($agentId == NULL || $agentId == '' || $agentId == 0)
				{
					$policyType = "B2C_VALUE_PLUS";
				}
				break;
			case 10:
				$policyType	 = "B2C_SELECT_OLD";
				break;
			case 13:
				$policyType	 = "MMT_VALUE_PLUS";
				break;
			default:
				$policyType	 = "B2C_VALUE";
				break;
		}
		return $policyType;
	}

	/**
	 * This function is used for calculate cancellation charges
	 * Applicable for B2C (Z1,Z2 CNG) (Z2 value) 
	 * free Cancellation until 24 hours of pick up
	 * 100%  cancellation fee after 24 hours
	 * Free Cancellation within 30 mins of booking 
	 * No show time 0 min implemented No show charge 30%
	 * 100 % cancellation fee after pickup time
	 * @param Booking $bkgModel
	 * @return Stub\common\CancelCharges
	 */
	public static function applyRule19($bkgModel)
	{
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}
		$commission = 0;

		$rule = self::getRule("19");

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;
		if ($confirmTime == '' && in_array($bkgModel->bkg_status, [1, 15]))
		{
			$confirmTime = Filter::getDBDateTime();
		}
		if ($confirmTime == '')
		{
			$confirmTime = $bkgModel->bkg_create_date;
		}
		$pickupDate = $bkgModel->bkg_pickup_date;

		$freeCancelTime	 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime		 = self::getNowShowTime($timeRules, $pickupDate);

		$slabs[$freeCancelTime]	 = 0;
		$advance				 = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		if (in_array($bkgModel->bkg_status, [1, 15]))
		{
			$advance = $bkgModel->bkgInvoice->calculateMinPayment($bkgModel->bkgInvoice->bkg_total_amount);
		}
		if ($bkgModel->bkg_status == 9)
		{
			$advance = $bkgModel->bkgInvoice->bkg_advance_amount;
		}
		$cancelCharge	 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge	 = min([$advance, $cancelCharge]);

		$slabs[$noShowTime]	 = $cancelCharge;
		$cancelCharge		 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge		 = min([$advance, $cancelCharge]);

		$slabs["-1"] = $cancelCharge;

		$objCancelCharges = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		return $objCancelCharges;
	}

	/**
	 * This function is used for calculate cancellation charges
	 * free Cancellation not available
	 * 100%  cancellation fee at any time
	 * @param Booking $bkgModel
	 * @return Stub\common\CancelCharges
	 */
	public static function applyRule20($bkgModel)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$cancelDate = $bkgModel->bkgTrail->btr_cancel_date;
		if ($cancelDate == '')
		{
			$cancelDate = Filter::getDBDateTime();
		}
		$commission = 0;

		$rule		 = self::getRule("20");
		$bkgId		 = $bkgModel->bkg_id;
		$minAdvJson	 = BookingPref::getMinAdvanceParams($bkgId);
		if (!$minAdvJson)
		{
			$minPerc	 = Config::getMinAdvancePercent($bkgModel->bkg_agent_id, $bkgModel->bkg_booking_type, $bkgModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $bkgModel->bkgPref->bkg_is_gozonow);
			BookingPref::updateMinAdvanceParams($bkgModel->bkg_id, $minPerc, $bkgModel->bkgInvoice->bkg_total_amount);
			$minAdvJson	 = BookingPref::getMinAdvanceParams($bkgId);
		}

		$minCharges		 = $rule["minCharges"];
		$timeRules		 = $rule["timeRules"];
		$chargesNoShow	 = $rule["chargesNoShow"];

		if ($minAdvJson )
		{
			$minAdvData = json_decode($minAdvJson, true);

			$minCharges['default']['value']	 = ($minCharges['default']['type'] == 1) ? $minAdvData['value'] : $minAdvData['amount'];
			$minCharges['max']				 = $minCharges['default'];

			$chargesNoShow['default']['value']	 = ($chargesNoShow['default']['type'] == 1) ? $minAdvData['value'] : $minAdvData['amount'];
			$chargesNoShow['max']				 = $chargesNoShow['default'];
		}

		$slabs		 = [];
		$amount		 = $bkgModel->bkgInvoice->bkg_total_amount;
		$confirmTime = $bkgModel->bkgTrail->bkg_confirm_datetime;
		if ($confirmTime == '' && in_array($bkgModel->bkg_status, [1, 15]))
		{
			$confirmTime = Filter::getDBDateTime();
		}
		if ($confirmTime == '')
		{
			$confirmTime = $bkgModel->bkg_create_date;
		}
		$pickupDate = $bkgModel->bkg_pickup_date;

		$freeCancelTime	 = self::getFreeCancelTime($timeRules, $confirmTime, $pickupDate);
		$noShowTime		 = self::getNowShowTime($timeRules, $pickupDate);

		$advance = $bkgModel->bkgInvoice->getAdvanceReceived(false);
		if (in_array($bkgModel->bkg_status, [1, 15]))
		{
			$advance = $bkgModel->bkgInvoice->calculateMinPayment($bkgModel->bkgInvoice->bkg_total_amount);
		}
		if ($bkgModel->bkg_status == 9)
		{
			$advance = $bkgModel->bkgInvoice->bkg_advance_amount;
		}
		$cancelCharge			 = self::CalculateCharges($minCharges, $amount);
		$cancelCharge			 = min([$advance, $cancelCharge]);
		$slabs[$freeCancelTime]	 = $cancelCharge;
		$slabs[$noShowTime]		 = $cancelCharge;
		$cancelCharge			 = self::CalculateCharges($chargesNoShow, $amount);
		$cancelCharge			 = min([$advance, $cancelCharge]);

		$slabs["-1"] = $cancelCharge;
		Logger::trace("applyRule20 ---- Booking Id: " . $bkgModel->bkg_id . "slabs:" . $slabs);

		$objCancelCharges = Stub\common\CancelCharges::init($slabs, $cancelDate, $advance, $commission, $bkgModel->bkg_id, $bkgModel->bkg_cancel_id);
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $objCancelCharges;
	}

}
