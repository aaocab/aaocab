<?php

class RcsrModule extends CWebModule
{

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		// import the module-level models and components
		$this->setImport(array(
			'rcsr.models.*',
			'rcsr.components.*',
		));
		Yii::app()->setComponents(array(
			'user' => array(
// There you go, use our 'extended' version
				'class'			 => 'application.components.CsrWebUser',
				'loginUrl'		 => array('rcsr'),
				'authTimeout'	 => 1200,
// enable cookie-based authentication
				'allowAutoLogin' => true),
		));
		$params	 = [
			'RestfullYii' => require( Yii::app()->basePath . '/config/restAdmin.php'),
		];
		Yii::app()->setParams($params);
		$user	 = Yii::app()->user;
		/* @var $user AdminWebUser */
		$user->setStateKeyPrefix('_rcsr');
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
			$httpsCheck	 = Yii::app()->params['adminHTTPS'];
			$id			 = Yii::app()->user->getId();
			$admModel	 = Admins::model()->findByPk($id);
			$est_date	 = date('Y-m-d H:i:s', strtotime($admModel->adm_last_password_change . '+ ' . 15 . ' day'));
			if ($est_date != "")
			{
				$d1	 = new DateTime();
				$d2	 = new DateTime($est_date);
				if ($d1 > $d2 && $_SERVER['REQUEST_URI'] != "/rcsr/index/changepassword")
				{
					$url1 = "http://" . $_SERVER['HTTP_HOST'] . "/rcsr/index/changepassword";
					Yii::app()->request->redirect($url1);
					exit();
				}
			}
			$uri	 = $_SERVER['REQUEST_URI'];
			$prefix	 = '/admin';

			if (substr($uri, 0, strlen($prefix)) == $prefix)
			{
				$homeUrl = Yii::app()->getBaseUrl(true);
				header("Location: $homeUrl");
				exit;
			}
			if ($httpsCheck && ($_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on"))
			{
				if ($_SERVER['SERVER_NAME'] != 'localhost' && $_SERVER['SERVER_NAME'] != '127.0.0.1' && substr($_SERVER['SERVER_NAME'], 0, strlen("192.168.1.")) != "192.168.1." && substr($_SERVER['SERVER_NAME'], 0, strlen("203.163.247.10")) != "203.163.247.10")
				{

					$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
					header("Location: $url");
					Yii::app()->request->redirect($url);
					exit;
				}
			}
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
