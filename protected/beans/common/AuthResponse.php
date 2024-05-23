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
 * @property integer $loginType 
 * @property \Beans\common\UserSession $profile
 * @property string $userName
 * @property string $password
 * @property string $encodedHash
 * @property integer $approvalStatus
 * @property \Beans\common\DeviceInfo $device
 */
class AuthResponse
{

	/**  @var int $loginType   */
	public $loginType; // 1=>Password, 2=>OTP, 3=>Google, 4=>Facebooks 
	public $jwtoken;

	/** @var \Beans\common\UserSession $profile */
	public $profile;
	public $encodedHash;
	public $missingData;
	public $approvalStatus;
	public $isExistingUser;
	public $url;

	public function setData($resArr)
	{
		$this->loginType = $resArr['type'];

		$this->jwtoken		 = $resArr['jwtoken'];
		$this->profile		 = $resArr['profile'];
		$this->encodedHash	 = $resArr['encodedHash'];

		$this->approvalStatus	 = $resArr['approvalStatus'];
		$this->isExistingUser	 = $resArr['isExistingUser'];
		$this->missingData		 = $resArr['missingData'];
		$this->url				 = $resArr['url'];
	}

}

?>