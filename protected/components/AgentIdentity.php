<?php

/**
 * MeterdownIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class AgentIdentity extends CUserIdentity
{

	private $_id, $_cname;

	//private $type;

	/**
	 * Constructor.
	 * @param string $username username
	 * @param string $password password
	 */
	public function __construct($username, $password)
	{
		parent::__construct($username, $password);
	}

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
		$model = null;
		if ($this->getId() != '')
		{
			$model = Agents::model()->findByPk($this->getId());
		}

		if (!$model && $this->username != '')
		{
			$model = Agents::model()->findByEmail($this->username);
		}

		if (!$model)
		{
			$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
			goto result;
		}

		if ($this->password === null || $this->password == $model->agt_password)
		{
			$this->_id		 = $model->agt_id;
			$this->errorCode = self::ERROR_NONE;
		}
		else
		{
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}

		result:
		return !$this->errorCode;
	}

	public function setId($id)
	{
		$this->_id = $id;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getCompanyName()
	{
		return $this->_cname;
	}

}
