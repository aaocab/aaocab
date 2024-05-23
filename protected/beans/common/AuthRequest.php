<?php

namespace Beans\common;

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthRequest
 *
 * @author Dev
 * 
 * 
 * @property integer $type 
 * @property \Beans\common\SocialResponse $socialResponse
 * @property string $userName
 * @property string $password
 * @property string $encodedHash
 * @property integer $approvalStatus
 * @property \Beans\common\DeviceInfo $device
 */
class AuthRequest
{

	/**  @var int $type   */
	public $type; // 1=>Password, 2=>OTP, 3=>Google, 4=>Facebooks 

	/** @var \Beans\common\SocialResponse socialResponse */
	public $socialResponse;
	public $userName;
	public $password;
	public $encodedHash;

	/** @var \Beans\common\DeviceInfo $device */
	public $device;

	public $approvalStatus;

	/**
	 * 
	 * @return \Users
	 */
	public function getUserLoginModel()
	{
		if (!empty($this->userName))
		{
			$userEmail	 = $this->userName;
			$userPass	 = $this->password;
		}
		$model						 = new \Users('login');
		$model->email				 = $userEmail;
		$model->usr_email			 = $userEmail;
		$model->usr_password		 = $userPass;
		$model->usr_create_platform	 = \Users::Platform_App;
		return $model;
	}

}

?>