<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 *  
 * @property int $carrierRequired
 * @property int $seniorCitizen  
 * @property int $kidsOnboard
 * @property int $womanTravelling
 * @property int $englishSpeakingDriver
 * @property int $hindiSpeakingDriver
 * @property int $lunchBreakRequired
 * @property int $smallBagNumber
 * @property int $largeBagNumber
 * @property int $noOfPerson
 * 
 * @property string $notes
 */

namespace Beans\booking;

/**
 * Description of AdditionalInfo
 *
 * @author Deepak
 */
class AdditionalInfo
{

	public $carrierRequired, $seniorCitizen, $kidsOnboard, $womanTravelling,
			$englishSpeakingDriver, $hindiSpeakingDriver, $lunchBreakRequired,
			$largeBagNumber, $smallBagNumber, $noOfPerson;
	public $notes;

	/** @var \BookingAddInfo $model */
	public static function setByAddInfoModel($model)
	{
		$obj					 = new AdditionalInfo();
		$obj->carrierRequired	 = (int) $model->bkg_spl_req_carrier;
		$obj->seniorCitizen		 = (int) $model->bkg_spl_req_senior_citizen_trvl;
		$obj->kidsOnboard		 = (int) $model->bkg_spl_req_kids_trvl;
		$obj->womanTravelling	 = (int) $model->bkg_spl_req_woman_trvl;

		$obj->englishSpeakingDriver	 = (int) $model->bkg_spl_req_driver_english_speaking;
		$obj->hindiSpeakingDriver	 = (int) $model->bkg_spl_req_driver_hindi_speaking;
		$obj->lunchBreakRequired	 = (int) $model->bkg_spl_req_lunch_break_time;
		$obj->flightNumber           = $model->bkg_flight_no;
		if ($model instanceof \BookingAddInfo)
		{
			$obj->largeBagNumber = (int) $model->bkg_num_large_bag;
			$obj->smallBagNumber = (int) $model->bkg_num_small_bag;
			$obj->noOfPerson	 = (int) $model->bkg_no_person;
		}
		else
		{
			$obj->largeBagNumber = (int) $model->bigBagCapacity;
			$obj->smallBagNumber = (int) $model->bagCapacity;
			$obj->noOfPerson	 = (int) $model->seatingCapacity;
		}
		if (isset($model->bkg_instruction_to_driver_vendor))
		{
			$obj->notes = trim($model->bkg_instruction_to_driver_vendor);
		}
		else if ($model instanceof \BookingAddInfo)
		{
			$obj->notes = trim($model->baddInfoBkg->getFullInstructions(1));
		}
		return $obj;
	}

}
