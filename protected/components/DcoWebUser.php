<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DcoWebUser extends CWebUser
{

	protected $_model;
	protected $_vmodel;
	protected $_dmodel;

	// Load user model.
	public function loadUser()
	{
		if ($this->_model === null)
		{
			$this->_model = Users::model()->findByPk($this->id);
		}
		return $this->_model;
	}

	public function loadVendor()
	{
		if ($this->_vmodel === null)
		{
			$this->_vmodel = Vendors::model()->findByPk($this->getEntityID());
		}
		return $this->_vmodel;
	}

	public function loadDriver()
	{
		if ($this->_dmodel === null)
		{
			$this->_dmodel = Drivers::model()->findByPk($this->getEntityID());
		}
		return $this->_dmodel;
	}

	public function getEntityID()
	{
		return $this->getState("EntityID");
	}

	public function setEntityID($id)
	{
		$this->setState("EntityID", $id); 
	}

	public function getUserType()
	{
		return $this->getState("UserType");
	}

	public function setUserType($id)
	{
		$this->setState("UserType", $id);
	}

	public function login(UserIdentity $identity, $duration = null)
	{
		if ($duration == null)
		{
			$duration = 3600 * 24 * 7;
		}

		if ($identity->authenticate())
		{
			$ret = parent::login($identity, $duration);
			Logger::create("RET: " . json_encode($ret), CLogger::LEVEL_TRACE);
			if ($ret)
			{
				$this->setEntityID($identity->getEntityID());
				$this->setUserType($identity->getUserType());
			}
			return $ret;
		}
		else
			throw new CException(Yii::t('yii', 'Failed to login', array('{class}' => get_class($this))));
	}

}
