<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MeterdownWebUser extends CWebUser
{

	protected $_model;
	protected $_pmodel;

	// Load user model.
	public function loadUser()
	{
		if ($this->_model === null)
		{
			$this->_model = Vendors::model()->findByPk($this->id);
		}
		return $this->_model;
	}

	public function login(MeterdownIdentity $identity, $duration = null)
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
