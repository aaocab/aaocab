<?php

/**
 * Description of BillingDetails
 *
 * @author Dev
 * @property string $fullName
 * @property \Beans\contact\Person $owner
 */

namespace Beans\common;

class BillingDetails extends \Stub\common\Person
{

	public $fullName;

	public static function setData(\BookingUser $model)
	{
		if ($model == null)
		{
			return false;
		}
		$obj						 = new BillingDetails();
		$obj->fullName				 = ($model->bkg_bill_fullname != '') ? $model->bkg_bill_fullname : $model->bkg_user_fname . " " . $model->bkg_user_lname;
		$obj->email					 = ($model->bkg_bill_email != '') ? $model->bkg_bill_email : $model->bkg_user_email;
		$obj->address				 = $model->bkg_bill_address;
		$obj->pincode				 = $model->bkg_bill_postalcode;
		$obj->city					 = $model->bkg_bill_city;
		$obj->state					 = $model->bkg_bill_state;
		$obj->country				 = $model->bkg_bill_country;
		$obj->primaryContact->number = (int) ($model->bkg_bill_contact != '') ? $model->bkg_bill_contact : $model->bkg_contact_no;
		
		return $obj;
	}

}
