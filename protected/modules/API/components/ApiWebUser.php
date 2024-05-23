<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ApiWebUser extends CWebUser
{

	protected $_model;
	protected $_cmodel;

	// Load user model.
	public function loadAdmin()
	{
		if ($this->_model === null)
		{
			$this->_model = Admins::model()->findByPk($this->admin_id);
		}
		return $this->_model;
	}

	public function login(AdminIdentity $identity, $duration = null)
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

}
