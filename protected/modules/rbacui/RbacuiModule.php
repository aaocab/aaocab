<?php

class RbacuiModule extends CWebModule
{

	public $defaultController = 'rbacui';
	public $userClass = 'User';
	public $userIdColumn = 'id';
	public $userNameColumn = 'user';
	public $userActiveScope = false;
	public $rbacUiAdmin = false;
	public $rbacUiAssign = false;
	public $rbacUiAssignRole = false;
	private $_assetsUrl;

	public function init()
	{
		$this->registerAssets();

		// import the module-level models and components
		$this->setImport(array(
			$this->id . '.models.*',
			$this->id . '.components.*',
		));

		Yii::app()->setComponents(array(
			'user' => array(
// There you go, use our 'extended' version
				'class' => 'application.components.AdminWebUser',
				'loginUrl' => array('admin'),
// enable cookie-based authentication
				'allowAutoLogin' => true),
		));
		$user = Yii::app()->user;
		/* @var $user AdminWebUser */
		$user->setStateKeyPrefix('_admin');
	}

	/**
	 * @return string base URL that contains all published asset files of this module.
	 */
	public function getAssetsUrl()
	{
		if ($this->_assetsUrl == null)
		{
			$this->_assetsUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias($this->id . '.assets')
					// Comment the line below out in production.
					, false, -1, true
			);
		}
		return $this->_assetsUrl;
	}

	/**
	 * Register the CSS and JS files for the module
	 */
	public function registerAssets()
	{
		Yii::app()->clientScript->registerCssFile($this->getAssetsUrl() . '/css/rbacui.css');
		Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
		Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl() . '/js/rbacui.js?v1', CClientScript::POS_HEAD);
	}

	public function beforeControllerAction($controller, $action)
	{
		if (parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

}
