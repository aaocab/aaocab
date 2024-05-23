<?php

return [
	'req.auth.username' => function() {
		return Yii::app()->request->getAuthorizationCode();
	},
	'req.auth.user' => function($application_id, $username, $password) {
		UserInfo::$platform	 = 2;
		UserLog::$deviceType = 2;
		Yii::log("DATA rest user app: \n\t" . $username, CLogger::LEVEL_INFO, 'system.api');
		try
		{
			if($username == "")
			{
				return false;
			}
			
			/* @var $model AppTokens */
			$model					 = AppTokens::validateToken($username);
			$model->apt_last_login	 = new CDbExpression('NOW()');
			$model->save();
			$userModel				 = Users::model()->findByPk($model->apt_user_id);
			if (!$userModel)
			{
				throw new Exception("Invalid data: ", ReturnSet::ERROR_INVALID_DATA);
			}

			$identity = new UserIdentity($userModel->usr_email, null);
			$identity->userId = $userModel->user_id;
			if ($identity->authenticate())
			{
				if(UserInfo::getUserType()==UserInfo::TYPE_CONSUMER)
				{
					$identity->setEntityID($identity->userId);
				}
				Yii::app()->user->login($identity);
				return true;
			}
		}
		catch (Exception $ex)
		{
			return false;
		}
	},
	'req.cors.access.control.allow.origin' => function() {
		if (isset($_SERVER['HTTP_ORIGIN']))
		{
			return [$_SERVER['HTTP_ORIGIN']];
		}
		else
		{
			return null;
		}
	},
	'req.auth.type' => function () {
		return 2;
	},
	'post.filter.req.cors.access.control.allow.headers' => function($allowed_headers) {
		array_push($allowed_headers, 'HTTP_X_REST_TOKEN', 'HTTP_X_REST_APN');
		return $allowed_headers; //Array
	}
];
