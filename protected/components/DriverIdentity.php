<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class DriverIdentity extends CUserIdentity
{

	private $_id;

	//  private $type;

	/**
	 * Constructor.
	 * @param string $username username
	 * @param string $password password
	 */
//    public function __construct($username, $password, $type = 0)
//    {
//        parent::__construct($username, $password);
//        $this->type = $type;
//    }

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
		$model = Drivers::model()->findByUsernamenPhone($this->username);
		if (!$model)
		{
			$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
		}
		else
		{
			if ($this->password === null || $this->password == $model->drv_password)
			{
				$this->_id		 = $model->drv_id;
				$this->errorCode = self::ERROR_NONE;
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
