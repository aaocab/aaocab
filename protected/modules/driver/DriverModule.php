<?php

class DriverModule extends CWebModule
{

	public function init()
	{
		$this->setImport(array(
			'driver.models.*',
			'driver.components.*',
		));
		Yii::app()->setComponents(array(
			'user' => array(
				'class'			 => 'application.components.DriverWebUser',
				'loginUrl'		 => array('driver/index/index'),
				'allowAutoLogin' => true),
		));
		$params	 = [
			'RestfullYii' => require( Yii::app()->basePath . '/config/restDriver.php'),
		];
		Yii::app()->setParams($params);
		$user	 = Yii::app()->user;
		/* @var $user GWebUser */
		$user->setStateKeyPrefix('_driver');
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
