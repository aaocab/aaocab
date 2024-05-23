<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class AdminIdentity extends CUserIdentity
{

	private $_id;

	const ERROR_INACTIVE_USER = 5;

	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$model = Admins::model()->findByEmail($this->username);
		if (!$model)
		{
			$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
		}
		else
		{
			Yii::app()->getSecurityManager()->generateRandomString($length);

			if ($this->password === null || CPasswordHelper::verifyPassword($this->password, $model->adm_passwd))
			{
				if ($model->adm_active != 1)
				{
					$this->errorCode = self::ERROR_INACTIVE_USER;
				}
				else
				{
					$this->_id		 = $model->adm_id;
					$this->errorCode = self::ERROR_NONE;
				}
			}
			else
			{
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
			}
		}
		return !$this->errorCode;
	}

	public function getId()
	{
		return $this->_id;
	}

}
