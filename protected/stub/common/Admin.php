<?php

namespace Stub\common;

class Admin extends Person
{
	/** @var \Stub\common\Person $profile */
	//public $profile;

	/** @var \Stub\common\Teams $team */
	//public $team;
	
	/** @var \Stub\common\ShiftRequest $shift*/
	public $shift;

	public function init(\Admins $model = null)
	{
		if ($model == null)
		{
			$model = new \Admins();
		}

		return $model;
	}

	/**
	 * This function is used for getting the model
	 */
	public function getModel($model = null)
	{
		$admModel = $this->init($model);
		return $admModel;
	}

	public function setProfile($model)
	{
		$this->id	 = (int) $model->adm_id;
		$this->name	 = $model->adm_fname;
		$this->email = $model->adm_email;
		$this->fieldExecutive = $this->checkFieldExecutive($model->adm_id);
	}

	public function setModel(\Admins $model = null, $sessionId = null)
	{
		if ($model == null)
		{
			$model = new \Admins();
		}
		$this->setProfile($model);
		$this->sessionId = $sessionId;
		$this->setTeam($model);
		$this->setShift($model);
	}
    public function setShift($model)
	{
		$obj		 = new \Stub\common\ShiftRequest();
		$this->shift = $obj->setModelData($model->adm_id);
	}
	public function setTeam($model)
	{
		$obj		 = new \Stub\common\Teams();
		$this->teams = $obj->setAdminData($model);
	}

	 
	public function setData($data, $sessionId)
	{

		$this->setProfileData($data[0]);
		$this->sessionId = $sessionId;
		$this->setTeamData($data);
	}

	public function setProfileData($data)
	{
		$this->id	 = (int) $data['adm_id'];
		$this->name	 = $data['adm_fname'];
		$this->email = $data['adm_email'];
	}
	public function getData($dataArr)
	{
		foreach ($dataArr as $row)
		{
			
			$obj				 = new \Stub\common\Admin();
			$obj->fillData($row);
			$this->data[]	 = $obj;
		}
		return $this->data;
	}
	
	public function fillData($row)
	{
		$this->id	 = (int) $row['adm_id'];
		$this->text	 = $row['gozen'];
	}
	
	
	public function checkFieldExecutive($admId)
	{
		$fieldExecutive = 0;
		$arrData = \Yii::app()->authManager->getRoles($admId);
		if($arrData)
		{
			$arrRoles = array_keys($arrData);
			if(in_array('9 - Field Executive', $arrRoles))
			{
				$fieldExecutive = 1;
			}
		}
		return $fieldExecutive;
	}


}
