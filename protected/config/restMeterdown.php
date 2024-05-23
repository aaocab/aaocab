<?php

$rest = require __DIR__ . '/rest.php';
return [
	'req.auth.user' => function($application_id, $username, $password) {
		Yii::log("DATA rest meterdown app: \n\t" . $username, CLogger::LEVEL_INFO, 'system.api');
		UserLog::$deviceType = 2;
		$appToken = AppTokens::model()->getByToken($username);
		
		if (!$appToken) {
			return false;
		}
		$appToken->apt_last_login = new CDbExpression('NOW()');
		$appToken->save();

		$vendorModel = Vendors::model()->resetScope()->find('vnd_id=:id AND vnd_active IN(1,3)', ['id' => $appToken->apt_entity_id]);
		$userModel = Users::model()->findByPk($appToken->apt_user_id);
		if (!$vendorModel) {
			return false;
		}
		if (!$userModel) {
			return false;
		}
		//$identity = new MeterdownIdentity($vendorModel->vnd_username, null);
		
		$identity = new UserIdentity($userModel->usr_email, $userModel->usr_password);
		/*if ($identity->authenticate()) {
			Yii::app()->user->login($identity);
			return true;
		}*/
		if ($identity->authenticate()) {
			$userID		 = $identity->getId();
			$identity->setEntityID($appToken->apt_entity_id);
			if(Yii::app()->user->login($identity))
			{
				return true;
			}
		}
		return false;
	},
		] + $rest;
