<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CsrWebUser extends CWebUser
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

	public function login(CsrIdentity $identity, $duration = null)
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

	public function checkAccess($operation, $params = array(), $allowCaching = true)
	{
		if ($allowCaching && !$this->getIsGuest() && isset(Yii::app()->session['access'][$operation]))
		{
			return Yii::app()->session['access'][$operation];
		}
		$checkAccess = Yii::app()->getAuthManager()->checkAccess($operation, $this->getId(), $params);
		if ($allowCaching && !$this->getIsGuest())
		{
			$access							 = isset(Yii::app()->session['access']) ? Yii::app()->session['access'] : array();
			$access[$operation]				 = $checkAccess;
			Yii::app()->session['access']	 = $access;
		}
		return $checkAccess;
	}

}
