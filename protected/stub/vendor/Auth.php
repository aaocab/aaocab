<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\vendor;

/**
 * Description of Auth
 *
 * @author Roy
 */
class Auth
{

	public $authId;	
	public $version;
	public $sessionCheck = false;	
	public $versionCheck = false;
	/** @var \Stub\common\Vendor $profile */
	public $profile;
	/**
	 * 
	 * @param type $authId
	 * @param type $version
	 * @param \Vendors $model
	 */
	public function setModelData($authId, $version = null, \Vendors $model)
	{
		if ($model == null)
		{
			$model = new \Vendors();
		}
		$this->authId						 = $authId;
		$this->profile->id					 = (int) $model->vnd_id;
		$this->profile->code				 = $model->vnd_code;
		$this->profile->uniqueName			 = $model->vnd_name;
		$this->profile->approveStatus		 = (int) $model->vnd_active;
		$this->profile->tier				 = (int) $model->vnd_rel_tier;
		$this->profile->contact->firstName	 = $model->vndContact->ctt_first_name;
		$this->profile->contact->lastName	 = $model->vndContact->ctt_last_name;
		#return $this;
	}

	public function setSessionCheck($authId)
	{
		if ($authId != null && $authId > 0)
		{
			$this->sessionCheck = true;
		}
	}

	public function setVersionCheck($version)
	{
		if ($version != null && $version > 0)
		{
			$this->versionCheck = true;
		}
	}

	/**
	 * 
	 * @param type $authId
	 * @param \Vendors $model
	 * @return $this
	 */
	public function setData($authId, \Vendors $model)
	{
		$this->profile->isDco	 = (int) $model->vnd_cat_type;
		$this->profile->type	 = (int) $model->vnd_type;
		$this->profile->status	 = (int) $model->vendorPrefs->vnp_is_freeze;
		$this->setModelData($authId, $model);
		return $this;
	}

}
