<?php

class BaseController extends Controller
{

	protected $_identity;
	public $pageHeader	 = '';
	public $current_page = '';
	public $email_receipient;
	public $fixedTop	 = '';
	public $pageTitle1	 = 'aaocab - Online Cab Booking | One Way | Delhi (NCR) | Chandigarh | Jaipur';
	public $pageDesc	 = 'A delight to travel! Delhi - Chandigarh | Delhi - Jaipur | Book Online. Fares starting Rs. 1999. Book Indica, Indigo, Innova, Etios, Dzire.';

	public function beforeAction($action)
	{

		if (!Yii::app()->user->isGuest)
		{
			$sess = Yii::app()->getSession()->getSessionId();
			UserLog::model()->updateLastActive($sess);
		}
		if (!Yii::app()->user->isGuest && Yii::app()->request->getParam('personalize') != null && Yii::app()->request->getParam('personalize') != '')
		{
			Yii::app()->user->setPersonalization(Yii::app()->request->getParam('personalize'));
		}
		return true;
	}

}
