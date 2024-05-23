<?php
$rest				 = require __DIR__ . '/rest.php';
return [
	'config.application.id' => function () {
		//	return UserInfo::TYPE_VENDOR;
	},
	'req.auth.username'	 => function () {
		$authHeader = Yii::app()->request->getAuthorizationHeader(false);
		Logger::trace("req.auth.username: {$authHeader}");
		return $authHeader;
	},
	'req.auth.user' => function ($application_id, $username, $password) {
		UserInfo::$platform	 = AppTokens::Platform_DCO;
		Logger::trace("req.auth.user: {$application_id}  {$username} {$password}");
		$token				 = $username;
		if (empty($token))
		{
			Logger::warning("Error empty token");
			return false;
		}
		try
		{
			Logger::trace("$token");
			$res = JWTokens::validateAppToken($token);
		}
		catch (Exception $e)
		{
			if ($e instanceof \Firebase\JWT\ExpiredException)
			{
				$message = "Session Expired";
			}
			else
			{
				$message = "Unauthorised";
			}

			ReturnSet::setException($e);
		}

		try
		{
			$entityId = $res->aud;
			if (empty($res->token))
			{
				return false;
			}
			$userModel = AppTokens::getUserById($res->token);
			if (!$userModel)
			{
				Logger::trace("User model not found: {$res->token}");
				throw new Exception(json_encode("User model not found: {$res->token}"), ReturnSet::ERROR_VALIDATION);
			}
			if ($userModel->hasErrors())
			{
				Logger::trace("Error in user model: {$res->token}");
				throw new Exception(json_encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			if (!$entityId)
			{
				$entityId = AppTokens::getEntity($res->token);
			}

			$userName	 = ($userModel->usr_email == '') ? $userModel->usr_mobile : $userModel->usr_email;
			$identity	 = new UserIdentity($userName, $userModel->usr_password);
			$identity->userId = $userModel->user_id;
			Logger::create("RET: " . json_encode($identity), CLogger::LEVEL_TRACE);
			if ($identity->authenticate())
			{
				$userID = $identity->getId();
				$identity->setEntityID($entityId);
				$identity->setUserType($res->sub);

				if (Yii::app()->user->login($identity))
				{
					return true;
				}
			}
			return false;
		}
		catch (Exception $e)
		{
			ReturnSet::setException($e);
			return false;
		}
	},
	'req.exception' => function ($errorCode, $message = null) {
		$returnSet = ReturnSet::setException(new Exception($message, $errorCode));
		return $this->renderJSON($returnSet);
	}
		] + $rest;

