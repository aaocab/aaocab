<?php

$rest						 = require __DIR__ . '/rest.php';
return [
	'req.auth.user' => function($application_id, $username, $password)
	{
		Logger::create("username: " . json_encode($username), CLogger::LEVEL_TRACE);
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
		$appToken->apt_last_login	 = new CDbExpression('NOW()');
		//print_r($appToken->apt_entity_id);
		$appToken->save();

		$vendorModel = Vendors::model()->resetScope()->find('vnd_id=:id AND vnd_active>0', ['id' => $appToken->apt_entity_id]); //Vendors::model()->findByPk($appToken->apt_user_id);
		$userModel	 = Users::model()->findByPk($appToken->apt_user_id);

		if (!$vendorModel)
		{
			return false;
		}
		if (!$userModel)
		{
			return false;
		}


		//$identity = new VendorIdentity($vendorModel->vnd_username, null);

		$identity = new UserIdentity($userModel->usr_email, $userModel->usr_password);
		Logger::create("RET: " . json_encode($identity), CLogger::LEVEL_TRACE);
		$identity->userId = $userModel->user_id;
		if ($identity->authenticate())
		{
			$userID = $identity->getId();
			$identity->setEntityID($appToken->apt_entity_id);
			if (Yii::app()->user->login($identity))
			{
				return true;
			}
		}
		return false;
	},
	'req.exception' => function($errorCode, $message = null)
	{
		$returnSet = ReturnSet::setException(new Exception($message, $errorCode));
		return $this->renderJSON($returnSet);
	}
		] + $rest;
