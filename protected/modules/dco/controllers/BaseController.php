<?php

class BaseController extends Controller
{

	protected $_identity;
	public $pageHeader	 = '';
	public $current_page = '';
	public $email_receipient;
	public $fixedTop	 = '';
	public $pageTitle1	 = 'Gozocabs - Online Cab Booking | One Way | Delhi (NCR) | Chandigarh | Jaipur';
	public $pageDesc	 = 'A delight to travel! Delhi - Chandigarh | Delhi - Jaipur | Book Online. Fares starting Rs. 1999. Book Indica, Indigo, Innova, Etios, Dzire.';

	public function beforeAction($action)
	{

		if(!Yii::app()->user->isGuest)
		{
			$sess = Yii::app()->getSession()->getSessionId();
			UserLog::model()->updateLastActive($sess);
		}
		if(!Yii::app()->user->isGuest && Yii::app()->request->getParam('personalize') != null && Yii::app()->request->getParam('personalize') != '')
		{
			Yii::app()->user->setPersonalization(Yii::app()->request->getParam('personalize'));
		}
		return true;
	}
	
	public function afterAction($action)
	{
		Yii::app()->session->destroy();
		parent::afterAction($action);
	}

	protected function getDriverId($strictValidate = true)
	{
		$userId = UserInfo::getUserId();
		if(empty($userId) || !$userId)
		{
			throw new Exception("Error in login.", ReturnSet::ERROR_UNAUTHORISED);
		}
//		$contactData = ContactProfile::getEntitybyUserId($userId);

		$refContactData	 = ContactProfile::getEntitybyUserId($userId);
		$contactData	 = ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);

		if((empty($contactData) || !$contactData['cr_is_driver']) && $strictValidate)
		{
			throw new Exception("Unable to link user", ReturnSet::ERROR_FAILED);
		}
		$drvId = $contactData['cr_is_driver'];
		return $drvId;
	}

	protected function getVendorId($strictValidate = true)
	{
		$userId = UserInfo::getUserId();
		if(empty($userId) || !$userId)
		{
			throw new Exception("Error in login.", ReturnSet::ERROR_UNAUTHORISED);
		}
//		$contactData = ContactProfile::getEntitybyUserId($userId);

		$refContactData	 = ContactProfile::getEntitybyUserId($userId);
		$contactData	 = ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);

		if((empty($contactData) || !$contactData['cr_is_vendor']) && $strictValidate)
		{
			throw new Exception("Unable to link user", ReturnSet::ERROR_FAILED);
		}
		$vndId = $contactData['cr_is_vendor'];
		return $vndId;
	}

	protected function getContactId()
	{
		$userId = UserInfo::getUserId();

		$contactData = ContactProfile::getEntitybyUserId($userId);

		if(empty($contactData) || !$contactData['primaryContact'])
		{
			throw new Exception("Unable to link user", ReturnSet::ERROR_FAILED);
		}
		$cttId = $contactData['primaryContact'];
		return $cttId;
	}
}
