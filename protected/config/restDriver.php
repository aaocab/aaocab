<?php
$rest			 = require __DIR__ . '/rest.php';
return [
	'req.auth.user'	 => function($application_id, $username, $password){
		UserInfo::$platform = AppTokens::Platform_Driver;
		Yii::log("DATA rest driver app: \n\t" . $username, CLogger::LEVEL_INFO, 'system.api');
		UserLog::$deviceType = 2;
		
		if($username == "")
		{
			return false;
		}
		
		$appToken			 = AppTokens::model()->getByToken($username);
		
		if (!$appToken)
		{
			return false;
		}
		$appToken->apt_last_login = new CDbExpression('NOW()');
		$appToken->save();
		
		
		

		#$driverModel = Drivers::model()->findByPk($appToken->apt_user_id);
		$userModel = Users::model()->findByPk($appToken->apt_user_id);
		
		
		
	/*	if (!$driverModel)
		{
			return false;
		}
		if (!$userModel) {
			return false;
		}*/
		
		
		
		//echo $userModel->usr_email; die();
		//$identity = new DriverIdentity($driverModel->drv_phone, null);
		$identity = new UserIdentity($userModel->usr_email, $userModel->usr_password);
		$identity->userId = $userModel->user_id;
		/*if ($identity->authenticate())
		{
			Yii::app()->user->login($identity);
			return true;
		}*/
		if ($identity->authenticate()) 
		{			
			$userID		 = $identity->getId();			
			$identity->setEntityID($appToken->apt_entity_id);
			$driver_id	 = $identity->entityId;
			//print_r($identity);
			if(Yii::app()->user->login($identity))
			{
				return true;
			}
		}		
		return false;
	},
		] + $rest;
