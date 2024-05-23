<?php
$rest = require __DIR__ . '/rest.php';
return [
	'req.auth.user' => function($application_id, $username, $password) {
		Yii::log("DATA rest admin app: \n\t" . $username, CLogger::LEVEL_INFO, 'system.api');
		$username = str_replace(',', '', $username);
		UserLog::$deviceType = 2;
		
		if($username == "")
		{
			return false;
		}
		
		$appToken = AppTokens::model()->getByToken($username);
		if (!$appToken) {
			return false;
		}
		$appToken->apt_last_login = new CDbExpression('NOW()');
		$appToken->save();
		$adminModel = Admins::model()->resetScope()->find('adm_id=:id AND adm_active IN (1)', ['id' => $appToken->apt_user_id]); //Vendors::model()->findByPk($appToken->apt_user_id);
		if (!$adminModel) {
			return false;
		}
		$identity = new AdminIdentity($adminModel->adm_user, null);
		if ($identity->authenticate())
		{
			if(Yii::app()->user->login($identity))
			{
				return true;
			}
		}
		return false;
	},
				] + $rest;
		