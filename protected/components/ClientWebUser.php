<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ClientWebUser extends CWebUser
{

	protected $_model;
	protected $_pmodel;
	private $_personalizeId = 0;

	// Load user model.
	public function loadUser()
	{
		if ($this->_model === null)
		{
			$this->_model = Users::model()->findByPk($this->id);
		}
		return $this->_model;
	}

//	public function loadProfile()
//	{
//		if ($this->_pmodel === null)
//		{
//			$this->_pmodel = ProfileCompleteness::model()->getByUserId($this->id);
//		}
//		return $this->_pmodel;
//	}

	


	public function login($identity, $duration = null)
	{
		if ($duration == null)
		{
			$duration = 3600 * 24 * 7;
		}
		if ($identity->authenticate())
		{
			$ret = parent::login($identity, $duration);
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

	public function setPersonalization($biz_id)
	{
		$this->setState('__personalize', $biz_id);
	}

	public function getPersonalization()
	{
		return $this->getState('__personalize');
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

}
