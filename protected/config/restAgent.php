<?php

$rest = require __DIR__ . '/rest.php';
return [
	'config.application.id' => function () {
		#$app = isset($_SERVER['HTTP_X_REST_APP']) ? $_SERVER['HTTP_X_REST_APP'] : (isset($_REQUEST['api']) ? 1 : 0);

		if (isset($_SERVER['HTTP_X_REST_APP']))
		{
			return $_SERVER['HTTP_X_REST_APP'];
		}
		if (isset($_REQUEST['api']))
		{
			return 1;
		}
		$authHeader = Yii::app()->request->getAuthorizationHeader();
		if ($authHeader != '')
		{
			return 1;
		}

		return 0;
	},
	'req.auth.user' => function ($application_id, $username, $password) {
		UserLog::$deviceType = 2;
		if ($username == "")
		{
			return false;
		}
		if ($application_id != 1)
		{
			$appToken = AppTokens::model()->getByToken($username);
			if (!$appToken)
			{
				return false;
			}
			$appToken->apt_last_login = new CDbExpression('NOW()');
			$appToken->save();

			$agentUsers = AgentUsers::model()->findAll('agu_user_id=:user', ['user' => $appToken->apt_user_id]);
			if (!$agentUsers || count($agentUsers) == 0)
			{
				return false;
			}
			if (count($agentUsers) > 1)
			{
				foreach ($agentUsers as $agtUser)
				{
					$agentModel = Agents::model()->findByPk($agtUser->agu_agent_id, 'agt_type IN (0,2)');
					if ($agentModel != '')
					{
						break;
					}
				}
			}
			else if (count($agentUsers) == 1)
			{
				$agentModel = Agents::model()->findByPk($agentUsers->agu_agent_id);
			}
			if (!$agentModel)
			{
				return false;
			}
			$identity = new UserIdentity($agentModel->agt_username, null);
			if ($identity->authenticate())
			{
				Yii::app()->user->loginAgentUser($identity);
				Yii::app()->user->setAgentId($agentModel->agt_id);
				return true;
			}
		}
		else
		{
			Logger::profile("auth.user: START");
			$ip			 = Filter::getUserIP();
			$agentModel	 = Agents::model()->findByApiKey($username);
			if (!$agentModel)
			{
				return false;
			}
//			$ipAddresses	 = array_filter(explode(",", $agentModel['agt_allowed_ip']));
//
			$bypassIPCheck	 = Yii::app()->params['bypassIPCheck'];
//			$success		 = true;
//			Logger::create("auth.user 1:\t", CLogger::LEVEL_PROFILE);
//
//			if (in_array($ip, $ipAddresses) || $bypassIPCheck)
//			{
//				goto skipSecureCheck;
//			}

			/* @var $ipCheck filter */
			$ipCheck = Filter::checkIpAllowed($agentModel['agt_allowed_ip']);
			if($ipCheck || $bypassIPCheck)
			{
				goto skipSecureCheck;
			}

			$success					 = false;
			Yii::app()->user->isSecured	 = false;
			$secretKey					 = $agentModel['agt_secret_key'];
			if ($secretKey != '')
			{
				$data = Yii::app()->request->getParam('data');
				if ($data == "")
				{
					$data = Yii::app()->request->rawBody;
				}
				if ($data)
				{
					$hash		 = Yii::app()->request->getParam('hash');
					$secretKey	 = $agentModel['agt_secret_key'];
					//Yii::app()->user->isSecured = (!$secretKey) ? true : false;
					$hash1		 = md5($secretKey . $data);
					if ($hash == $hash1)
					{
						$success = true;
					}
				}
				else
				{
					$success = true;
				}
			}
			if (!$success)
			{
				Yii::log("CP IP Check:\t{$ip}\t{$username}\t" . json_encode($ipCheck) . "", CLogger::LEVEL_WARNING, 'config.security.ipcheck.');
			}

			skipSecureCheck:
			if ($success || Yii::app()->user->isSecured)
			{
				$identity = new AgentIdentity($agentModel['agt_username'], null);
				$identity->setId($agentModel['agt_id']);
				if ($identity->authenticate())
				{
					Yii::app()->user->login($identity);
					return true;
				}
			}
		}
		return false;
	},
		] + $rest;
