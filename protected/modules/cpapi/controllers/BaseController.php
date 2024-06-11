<?php

class BaseController extends Controller
{

	protected $_identity;
	public $pageHeader	 = '';
	public $current_page = '';
	public $email_receipient;
	public $fixedTop	 = '';
	public $subTitle	 = "";

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

	public function afterAction($action)
	{
		Yii::app()->session->destroy();
		parent::afterAction($action);
	}
}
