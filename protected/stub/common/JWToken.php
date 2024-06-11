<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Stub\common;

/**
 * Description of JWToken
 *
 * @author Dev
 * 
 * @property Platform $device Description
 */
class JWToken
{

	public $iss; //(issuer): Issuer of the JWT
	public $sub; //(subject): Subject of the JWT (the user)
	public $aud; //(audience): Recipient for which the JWT is intended
	public $exp; //(expiration time): Time after which the JWT expires
	public $nbf; //(not before time): Time before which the JWT must not be accepted for processing
	public $iat; //(issued at time): Time at which the JWT was issued; can be used to determine age of the JWT
	public $jti; //(JWT ID): Unique identifier; can be used to prevent the JWT from being replayed (allows a token to be used only once), 
	public $token;

	/** @var Platform $device */
	public $device;

	public function setByAppToken(\AppTokens $appTokenModel = null, $validDuration = 24 * 60 * 60)
	{
		$timeNow		 = strtotime(\Filter::getDBDateTime());
		$this->iss		 = "http://www.aaocab.com/";
		$this->sub		 = $appTokenModel->apt_user_type;
		$this->aud		 = $appTokenModel->apt_entity_id;
		$this->iat		 = $timeNow;
		$this->exp		 = $timeNow + $validDuration;
		$this->jti		 = null;
		$this->token	 = $appTokenModel->apt_token_id;
		$this->device	 = new Platform();
		$this->device->setData($appTokenModel);
	}

	public function setByUsers($model, $validDuration = 24 * 60 * 60)
	{
		$timeNow		 = strtotime(\Filter::getDBDateTime());
		$this->iss		 = "http://www.aaocab.com/";
		$this->sub		 = 1;
		$this->aud		 = $model->user_id | 0;
		$this->iat		 = $timeNow;
		$this->exp		 = $timeNow + $validDuration;
		$this->jti		 = null;
		$this->token	 = base64_encode($model->user_id | 0);
		$this->device	 = new Platform();
		$this->device->setUserData($model);
	}

}
