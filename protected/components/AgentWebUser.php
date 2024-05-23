<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AgentWebUser extends CWebUser
{

	protected $_model;
	protected $_pmodel;
	public $isSecured = true;

	// Load user model.
	public function loadUser()
	{
		if ($this->_model === null)
		{
			$this->_model = Agents::model()->findByPk($this->id);
		}
		return $this->_model;
	}

	public function loadAgentUser()
	{
		if ($this->_model === null)
		{
			$this->_model = Users::model()->findByPk($this->id);
		}
		return $this->_model;
	}

	public function login(AgentIdentity $identity, $duration = null)
	{
		if ($duration == null)
		{
			$duration = '3600';
		}
		if ($identity->authenticate())
		{
			return parent::login($identity, $duration);
		}
		else
			throw new CException(Yii::t('yii', 'Failed to login', array('{class}' => get_class($this))));
	}

	public function loginAgentUser(UserIdentity $identity, $duration = null)
	{
		if ($duration == null)
		{
			$duration = '3600';
		}
		if ($identity->authenticate())
		{
			return parent::login($identity, $duration);
		}
		else
			throw new CException(Yii::t('yii', 'Failed to login', array('{class}' => get_class($this))));
	}

	public function setAgentId($agentId)
	{
		$this->setState('__agentid', $agentId);
	}

	public function getAgentId()
	{
		return $this->getState('__agentid');
	}

	public function setCorpCode($corpcode)
	{
		$this->setState('__corpcode', $corpcode);
	}

	public function getCorpCode()
	{
		return $this->getState('__corpcode', '');
	}

	public function setCompanyName($comname)
	{
		$this->setState('__company', $comname);
	}

	public function getCompanyName()
	{
		return $this->getState('__company');
	}

}
