<?php

class MeterdownModule extends CWebModule
{

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		// import the module-level models and components
		$this->setImport(array(
			'meterdown.models.*',
			'meterdown.components.*',
		));
		Yii::app()->setComponents(array(
			'user' => array(
// There you go, use our 'extended' version
				//'class' => 'application.components.MeterdownWebUser',
				'class'			 => 'application.components.GWebUser',
				'loginUrl'		 => array('meterdown/index/index'),
// enable cookie-based authentication
				'allowAutoLogin' => true),
		));
		$params	 = [
			'RestfullYii' => require( Yii::app()->basePath . '/config/restVendor.php'),
				//'RestfullYii' => require( Yii::app()->basePath . '/config/restVender.php'),
		];
		Yii::app()->setParams($params);
		$user	 = Yii::app()->user;

		/* @var $user GWebUser */
		$user->setStateKeyPrefix('_vendor');
		//$user->setUserType(UserInfo::TYPE_CONSUMER);
	}

	/**
	 * The pre-filter for controller actions.
	 * This method is invoked before the currently requested controller action and all its filters
	 * are executed. 
	 * 
	 * @param CController $controller the controller
	 * @param CAction $action the action
	 * @return boolean whether the action should be executed.
	 */
	public function beforeControllerAction($controller, $action)
	{
		Logger::setActionCategory($this->id, $controller->id, $action->id);
		if (parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

	public function afterControllerAction($controller, $action)
	{
		Logger::unsetActionCategory($this->id, $controller->id, $action->id);
		parent::afterControllerAction($controller, $action);
	}

}
