<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SocialAuth
 *
 * @author Abhishek Khetan
 * 
 * 
 * @property \Users $soaUser
 */
class SocialAuth extends UserOAuth
{

	const Provider_Facebook	 = 'Facebook';
	const Provider_Google		 = 'Google';
	const Provider_aaocab	 = 'aaocab';
	const Eml_Facebook		 = 3;
	const Eml_Google			 = 2;
	const Eml_aaocab		 = 1;

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('provider, identifier, user_id', 'required', 'on' => 'insert'),
			array('provider', 'checkProvider'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('provider, identifier', 'safe'),
		);
	}

	public function checkProvider($attribute, $params)
	{
		if (!in_array($this->provider, [self::Provider_Facebook, self::Provider_Google, self::Provider_aaocab]))
		{
			$this->addError($attribute, "Provider not supported");
		}
		return !$this->hasErrors();
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'soaUser' => array(self::HAS_ONE, 'Users', 'user_id'),
		);
	}

	/** @return static */
	public function findByIdentifier($provider, $identifier)
	{
		return $this->findByAttributes(['provider' => $provider, 'identifier' => $identifier]);
	}

	/**
	 * 
	 * @param AppTokens $model
	 * @param string $token
	 * @return boolean
	 */
	public function validateToken($token)
	{
		/** @var Hybrid_Provider_Model $provider */
		$provider = $this->connectProvider($token);
		try
		{
			$profile = $provider->getUserProfile();
		}
		catch (Exception $e)
		{
			$profile = $provider->getUserProfile();
		}
		return ($profile->identifier == $this->identifier && $this->identifier != null);
	}

	/** @return Hybrid_Provider_Model Description */
	public function connectProvider($token)
	{
		/** @var Hybrid_Provider_Model $provider */
		$provider = $this->hybridAuth->getAdapter($this->provider)->adapter;
		if (!$provider->isUserConnected())
		{
			$provider->setUserConnected();
		}
		$provider->token('access_token', $token);
		$provider->initialize();

		return $provider;
	}

	/**
	 * @return UserOAuth
	 * */
	public function linkContact($token, $contactId, $forceLink = false)
	{
		Logger::trace("Contact ID: " . $contactId);
		$userId = ContactProfile::getUserId($contactId);
		Logger::trace("User ID: " . $userId);
		if (!$userId)
		{
			$userModel	 = Users::createbyContact($contactId);
			$userId		 = $userModel->user_id;
		}

		$oAuth	 = $this->linkUser($token, $userId, $forceLink);
		$profile = json_decode($oAuth->getProfileCache());
		Logger::trace("User Profile: " . $profile);
		$type	 = 0;
		if ($this->provider == SocialAuth::Provider_Google)
		{
			$type = SocialAuth::Eml_Google;
		}
		if ($this->provider == SocialAuth::Provider_Facebook)
		{
			$type = SocialAuth::Eml_Facebook;
		}

		ContactEmail::model()->addNew($contactId, $profile->email, $type, 1);

		return $oAuth;
	}

	/**
	 * @param boolean $forceLink if true then it will override existing linked user account
	 * @return UserOAuth 
	 * @throws Exception
	 */
	public function linkUser($token, $userId, $forceLink = false)
	{
		/** @var Hybrid_Provider_Model $provider */
		$provider	 = $this->connectProvider($token);
		$userProfile = $provider->getUserProfile();
		Logger::trace("Provider: {$this->provider}::{$this->identifier}");
		$oAuth		 = $this->findByIdentifier($this->provider, $this->identifier);

		Logger::trace("oAuth:" . json_encode($oAuth));

		if (!$forceLink && $oAuth !== null && !ContactProfile::getMergedContactUser($userId, $oAuth->user_id))
		{
			$oAuth->addError("user_id", "Account already linked with another user");
			throw new Exception(json_encode($oAuth->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		if ($oAuth !== null && $userProfile->identifier != $this->identifier)
		{
			$oAuth->addError("identifier", "Identifier Mismatched");
			throw new Exception(json_encode($oAuth->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		if ($oAuth == null)
		{
			$oAuth				 = new UserOAuth();
			$oAuth->identifier	 = $userProfile->identifier;
			$oAuth->provider	 = $this->provider;
		}
		$oAuth->user_id			 = $userId;
		$oAuth->session_data	 = $this->hybridauth->getSessionData();
		$oAuth->profile_cache	 = $oAuth->getProfileCache();
		$oAuth->save();

		end:
		return $oAuth;
	}

	/** @return Users|null */
	public function getUserModel()
	{
		$userModel	 = null;
		$model		 = $this->findByIdentifier($this->provider, $this->identifier);
		if ($model)
		{
			$userId		 = $model->user_id;
			$userModel	 = Users::model()->findByPk($userId);
		}
		return $userModel;
	}

	/**
	 * 
	 * @param string $provider
	 * @return integer
	 */
	public static function getTypeByProvider($provider = Provider_aaocab)
	{
		$providerVal = 0;
		switch ($provider)
		{
			case 'aaocab';
				$providerVal = self::Eml_aaocab;
				break;
			case 'Facebook';
				$providerVal = self::Eml_Facebook;
				break;
			case 'Google';
				$providerVal = self::Eml_Google;
				break;
		}
		return $providerVal;
	}

	/**
	 * unlink user from imp user auth table
	 * @param type $userId
	 * @param type $type
	 * @param type $identifier
	 * @return type
	 */
	public static function unlink($userId, $type = "", $identifier = "")
	{
		$cond = '';
		if ($type != "")
		{
			$cond .= " AND provider = '" . $type . "'";
		}
		if ($identifier != "")
		{
			$cond .= " AND identifier = '" . $identifier . "'";
		}
		$sql = "DELETE FROM `imp_user_oauth` WHERE `user_id`=$userId $cond ";
		return DBUtil::command($sql, DBUtil::MDB())->execute();
	}

	public static function findProfileEmail($identifier)
	{
		$params				 = array("identifier" => $identifier);
		$sql				 = "SELECT profile_cache FROM imp_user_oauth WHERE identifier= :identifier ";
		$result				 = DBUtil::queryRow($sql, null, $params);
		$profileEmailArr	 = explode('"email";', $result['profile_cache']);
		$profileEmailArr	 = explode(';', $profileEmailArr[1]);
		$profileEmailData	 = explode(':"', $profileEmailArr[0]);
		#$profileEmail = $profileEmailArr[1];
		$profileEmail		 = trim($profileEmailData[1], '"');
		return $profileEmail;
	}

	/**
	 * 
	 * @param integer $userId
	 * @return type
	 */
	public static function findByUserId($userId)
	{
		$params	 = array("userId" => $userId);
		$sql	 = "SELECT * FROM imp_user_oauth WHERE user_id=:userId";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

}
