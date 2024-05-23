<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

	private $_id;
	private $_entityId;
	private $_userType;
	public $userId = '';

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
		$success = false;
		$rows	 = [];
		if ($this->userId != '')
		{
			/** @todo Remove this after verifying $this-userId  usage */
			$sql	 = "SELECT user_id FROM users WHERE user_id=:userId AND usr_active=1";
			$rows	 = DBUtil::query($sql, DBUtil::MDB(), ["userId" => $this->userId]);
			goto validate;
		}

		if (Filter::validateEmail($this->username))
		{
			$rows = Users::getIdsByEmail($this->username);
		}
		else if (Filter::validatePhoneNumber($this->username))
		{
			$rows = Users::getIdsByPhone($this->username);
		}
		validate:
		foreach ($rows as $row)
		{
			$success = $this->validateUser($row["user_id"]);
			if ($success)
			{
				break;
			}
		}

		if ($success)
		{
			goto result;
		}

		if (filter_var($this->username, FILTER_VALIDATE_EMAIL))
		{
			$email = $this->username;
		}
		else
		{
			$phone = $this->username;
		}

		if ($email != '')
		{
			/** @todo remove once we confirm that all user data in present in contact table. */
			$sql	 = "SELECT user_id FROM users WHERE usr_email=:email AND usr_email<>'' AND usr_active=1";
			$rows	 = DBUtil::query($sql, DBUtil::SDB(), ["email" => $email]);
		}
		if ($phone != '')
		{
			/** @todo remove once we confirm that all user data in present in contact table. */
			$sql	 = "SELECT user_id FROM users WHERE usr_mobile=:mobile AND usr_mobile<>'' AND usr_active=1";
			$rows	 = DBUtil::query($sql, DBUtil::SDB(), ["mobile" => $phone]);
		}
		foreach ($rows as $row)
		{
			$success = $this->validateUser($row["user_id"]);
			if ($success)
			{
				break;
			}
		}

		result:
		return $success;
	}

	private function validateUser($userId)
	{
		$model = Users::model()->findByPk($userId);
		if ($this->password === null || $this->password == $model->usr_password)
		{
			$this->_id		 = $model->user_id;
			$this->errorCode = self::ERROR_NONE;
		}
		else
		{
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}
		return ($this->errorCode == self::ERROR_NONE);
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getEntityID()
	{
		return $this->_entityId;
	}

	public function setEntityID($id)
	{
		$this->_entityId = $id;
	}

	public function getUserType()
	{
		return $this->_userType;
	}

	public function setUserType($type)
	{
		$this->_userType = $type;
	}

}
