<?php

namespace Beans\booking;

class BillingDetails extends \Beans\contact\Contact
{

	public $gstin, $pan;

	/**
	 * 
	 * @param \Beans\Booking $obj
	 * @return boolean|\BookingUser
	 */
	public static function getModel($obj)
	{
		if ($obj->id == null || $obj->id == 0)
		{
			return false;
		}
		$model = \BookingUser::model()->getByBkgId($obj->id);
		if (!$model)
		{
			$model				 = new \BookingUser();
			$model->bui_bkg_id	 = $obj->id;
		}
		$model->bkg_bill_gst		 = $obj->billing->gstin;
		$model->bkg_bill_fullname	 = $obj->billing->name;
		$model->bkg_bill_address	 = $obj->billing->address->address;
		$model->bkg_bill_postalcode	 = $obj->billing->address->pincode;
		$model->bkg_bill_city		 = $obj->billing->address->city->name;
		$model->bkg_bill_state		 = $obj->billing->address->city->state->name;
		$model->bkg_bill_country	 = $obj->billing->address->city->state->country;

		$model->bkg_bill_email	 = $obj->billing->email->address;
		$model->bkg_bill_contact = $obj->billing->phone->number;

		return $model;
	}

	/**
	 * 
	 * @param \BookingUser $model
	 * @return boolean|\Beans\booking\BillingDetails
	 */
	public static function setModel(\BookingUser $model)
	{
		if ($model == null)
		{
			return false;
		}
		$obj							 = new BillingDetails();
		$obj->name						 = $model->bkg_bill_fullname;
		$obj->gstin						 = $model->bkg_bill_gst;
		$obj->address->address			 = $model->bkg_bill_address;
		$obj->address->pincode			 = $model->bkg_bill_postalcode;
		$obj->address->city->name		 = $model->bkg_bill_city;
		$obj->address->city->state->name = $model->bkg_bill_state;

		if ($model->bkg_bill_email != '')
		{
			$obj->email[] = \Beans\contact\Email::setUserEmail($model->bkg_bill_email);
		}

		if ($model->bkg_bill_contact != '')
		{
			$phoneData			 = [];
			$phoneData['code']	 = $model->bkg_country_code;
			$phoneData['number'] = $model->bkg_bill_contact;
			$obj->phone[]		 = \Beans\contact\Phone::setUserPhone($phoneData);
		}

		return $obj;
	}

}

?>
