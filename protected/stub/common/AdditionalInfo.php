<?php

namespace Stub\common;

class AdditionalInfo
{

	public $specialInstructions, $noOfPerson, $noOfLargeBags;
	public $noOfSmallBags, $carrierRequired;
	public $kidsTravelling, $seniorCitizenTravelling, $womanTravelling;
	public $driverEnglishSpeaking, $driverHindiSpeaking, $flightNumber;

	public function getModel(\BookingAddInfo $model = null)
	{
		/** @var BookingAddInfo $model */
		if ($model == null)
		{
			$model = new \BookingAddInfo();
		}
		$model->bkg_spl_req_other					 = $this->specialInstructions;
		$model->bkg_no_person						 = (int) $this->noOfPerson;
		$model->bkg_num_large_bag					 = (int) $this->noOfLargeBags;
		$model->bkg_num_small_bag					 = (int) $this->noOfSmallBags;
		$model->bkg_spl_req_carrier					 = (int) $this->carrierRequired;
		$model->bkg_spl_req_kids_trvl				 = (int) $this->kidsTravelling;
		$model->bkg_spl_req_senior_citizen_trvl		 = (int) $this->seniorCitizenTravelling;
		$model->bkg_spl_req_woman_trvl				 = (int) $this->womanTravelling;
		$model->bkg_spl_req_driver_english_speaking	 = (int) $this->driverEnglishSpeaking;
		$model->bkg_spl_req_driver_hindi_speaking	 = (int) $this->driverHindiSpeaking;
		$model->bkg_flight_no						 = $this->flightNumber;
		return $model;
	}

	public function setModel(\BookingAddInfo $model = null)
	{
		$this->specialInstructions		 = $model->bkg_spl_req_other;
		$this->noOfPerson				 = $model->bkg_no_person;
		$this->noOfLargeBags			 = $model->bkg_num_large_bag;
		$this->noOfSmallBags			 = $model->bkg_num_small_bag;
		$this->carrierRequired			 = $model->bkg_spl_req_carrier;
		$this->kidsTravelling			 = $model->bkg_spl_req_kids_trvl;
		$this->seniorCitizenTravelling	 = $model->bkg_spl_req_senior_citizen_trvl;
		$this->womanTravelling			 = $model->bkg_spl_req_woman_trvl;
		$this->driverEnglishSpeaking	 = $model->bkg_spl_req_driver_english_speaking;
		$this->driverHindiSpeaking		 = $model->bkg_spl_req_driver_hindi_speaking;
		$this->tripType					 = $model->bkg_user_trip_type;
		$this->flightNumber				 = $model->bkg_flight_no;
		return $this;
}

}
