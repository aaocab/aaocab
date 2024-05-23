<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of User
 *
 * @author Roy
 */
class User extends \Stub\common\Person
{

	public $profilePic;

	/** @var \Stub\common\Fare $totalGozocoins */
	public $totalGozocoins;

	/**
	 * @param \Users $model
	 */
	public function getData(\Users $model = null)
	{
		if ($model == null)
		{
			$model = new \Users();
		}
		$model->usr_name		 = $this->firstName;
		$model->usr_lname		 = $this->lastName;
		$model->usr_gender		 = $this->gender;
		$model->usr_address1	 = $this->address;
		$model->usr_zip			 = (int) $this->pincode;
		$model->usr_country_code = (int) $this->primaryContact->code;
		$model->usr_mobile		 = (int) $this->primaryContact->number;
		$model->usr_state		 = (int) $this->state;
		$model->usr_country		 = (int) $this->country;
		return $model;
	}

	/**
	 * 
	 * @param \Users $model
	 */
	public function setData(\Users $model)
	{

		if (!$model)
		{
			return false;
		}
		$this->firstName				 = $model->usr_name;
		$this->lastName					 = $model->usr_lname;
		$this->email					 = $model->usr_email;
		$this->pincode					 = (int)$model->usr_zip;
		$this->country					 = (int)$model->usr_country;
		$this->state					 = (int)$model->usr_state;
		$this->address					 = $model->usr_address1;
		$this->gender					 = $model->usr_gender;
		$this->profilePic				 = \Users::getImageUrl($model->usr_profile_pic);
		$this->primaryContact->code		 = (int)$model->usr_country_code;
		$this->primaryContact->number	 = $model->usr_mobile;
	}
	
	
	
	
	public function setUsrData($dataArr)
	{

		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\common\User();
			$obj->allData($row);
			$this->data[]	 = $obj;
		}
		return $this->data;
	}
	public function allData($row)
	{
		$this->id			 = (int) $row['user_id'];
		$this->text	 = $row['ctt_first_name'].' '.$row['ctt_last_name'];
	}
}
