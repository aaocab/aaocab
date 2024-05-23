<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\transferz;

/**
 * Description of TransferzUpdateRequest
 *
 * @author Pankaj
 */
class UpdateRequest
{

	public $id, $preferred, $meetingPointRequired, $expiry, $status, $replacement, $hash, $code, $pickupTime, $vehicleCategory, $pickup, $dropoff, $distance;

	/** @var \Beans\transferz\Fleet $fleet */
	public $fleet;

	/** @var \Beans\transferz\Journey $journey */
	public $journey;

	/** @var \Beans\transferz\MeetingPoints[] $meetingPoints */
	public $meetingPoints;
	
	public function setData($model, $jsonObj)
	{
//		if ($model == null)
//		{
//			$model = new \Booking();
//		}
		
		$model->bkgUserInfo->bkg_user_fname	 = ($jsonObj->travellerInfo->firstName != $model->bkgUserInfo->bkg_user_fname) ? $jsonObj->travellerInfo->firstName : $model->bkgUserInfo->bkg_user_fname;
		$model->bkgUserInfo->bkg_user_lname	 = ($jsonObj->travellerInfo->lastName != $model->bkgUserInfo->bkg_user_lname) ? $jsonobj->travellerInfo->lastName : $model->bkgUserInfo->bkg_user_lname;
		$model->bkgUserInfo->bkg_user_email	 = ($jsonObj->travellerInfo->email != $model->bkgUserInfo->bkg_user_email) ? $jsonobj->travellerInfo->email : $model->bkgUserInfo->bkg_user_email;
		$model->bkgUserInfo->bkg_contact_no	 = ($jsonObj->travellerInfo->phone != $model->bkgUserInfo->bkg_contact_no) ? $jsonobj->travellerInfo->phone : $model->bkgUserInfo->bkg_contact_no;
		$model->bkgAddInfo->bkg_no_person	 = ($jsonObj->travellerInfo->passengerCount != $model->bkgAddInfo->bkg_no_person) ? $jsonobj->travellerInfo->passengerCount : $model->bkgAddInfo->bkg_no_person;
		$model->bkgAddInfo->bkg_flight_no	 = ($jsonObj->travellerInfo->flightNumber != $model->bkgAddInfo->bkg_flight_no) ? $jsonobj->travellerInfo->flightNumber : $model->bkgAddInfo->bkg_flight_no;
		$model->bkgAddInfo->save();
		$model->bkgUserInfo->save();
		$model->bkgPref->save();
		return $model;
	}

}
