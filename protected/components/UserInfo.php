<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserInfo
 *
 * @author Abhishek
 */
class UserInfo
{

	const TYPE_CONSUMER	 = 1;
	const TYPE_VENDOR		 = 2;
	const TYPE_DRIVER		 = 3;
	const TYPE_ADMIN		 = 4;
	const TYPE_AGENT		 = 5;
	const TYPE_SYSTEM		 = 10;
	const TYPE_CORPORATE	 = 6;
	const TYPE_INTERNAL	 = 11;

	public static $platform	 = 1;
	public $userType		 = 10;
	public $userId			 = 0;

	//put your code here
	public function __construct($userType = self::TYPE_SYSTEM, $userId = 0)
	{
		$this->userType	 = $userType;
		$this->userId	 = $userId;
	}

	public static function model($userType = self::TYPE_SYSTEM, $userId = 0)
	{
		$obj = new UserInfo($userType, $userId);
		return $obj;
	}

	public static function getInstance()
	{
		$userInfo			 = UserInfo::model();
		$userInfo->userId	 = self::getUserId();
		$userInfo->userType	 = self::getUserType();
//Logger::create("Driver UserInfo test : " . $userInfo->userType."TEST  USER", CLogger::LEVEL_INFO);
		return $userInfo;
	}

	/** @return CWebUser */
	public static function getUser()
	{
		$user = null;
		if (Yii::app()->hasProperty("user"))
		{
			$user = Yii::app()->user;
		}

		return $user;
	}

	public static function getUserId()
	{
		$userId	 = 0;
		$user	 = self::getUser();
		if ($user != null && $user instanceof CWebUser && $user->getId() != null)
		{
			$userId = $user->getId();
		}
		return $userId;
	}

	public static function getEntityId()
	{
		$entityId	 = 0;
		$user		 = self::getUser();
		if ($user != null && $user instanceof GWebUser && $user->getId() != null)
		{
			$entityId = $user->getEntityID();
		}
		else if ($user != null && $user instanceof DcoWebUser && in_array(self::getUserType(), [UserInfo::TYPE_VENDOR, UserInfo::TYPE_DRIVER]))
		{
			$entityId = $user->getEntityID();
		}
		else if ($user != null && $user instanceof ClientWebUser && in_array(self::getUserType(), [UserInfo::TYPE_CONSUMER]))
		{
			$entityId = $user->getEntityID();
		}
		return $entityId;
	}

	public static function isLoggedIn()
	{
		$isLoggedIn	 = false;
		$user		 = self::getUser();
		if ($user != null && $user instanceof CWebUser)
		{
			$isLoggedIn = !$user->isGuest;
		}
		return $isLoggedIn;
	}

	public static function getUserType()
	{
		$userType = UserInfo::TYPE_SYSTEM;

		$user = self::getUser();
		if ($user == null)
		{
			goto result;
		}
		switch (true)
		{
			case ($user instanceof AdminWebUser || $user instanceof CsrWebUser):
				$userType	 = UserInfo::TYPE_ADMIN;
				break;
			case $user instanceof ClientWebUser:
				$userType	 = UserInfo::TYPE_CONSUMER;
				break;
			case $user instanceof DriverWebUser:
				$userType	 = UserInfo::TYPE_DRIVER;
				break;
			case $user instanceof GWebUser:
				$userType	 = UserInfo::TYPE_VENDOR;
				break;
			case $user instanceof AgentWebUser:
				$userType	 = UserInfo::TYPE_AGENT;
				break;
			case $user instanceof DcoWebUser:
				$userType	 = UserInfo::TYPE_VENDOR;
				if (self::getUserId() > 1)
				{
					$userType = ContactProfile::getPreferredUserType(self::getUserId());
				}
				break;
			default:
				$userType = UserInfo::TYPE_SYSTEM;
				break;
		}
		result:
		return $userType;
	}

	public static function getUserTypeDesc($type = null)
	{
		$arr = [
			self::TYPE_CONSUMER	 => 'Consumer',
			self::TYPE_VENDOR	 => 'Vendor',
			self::TYPE_DRIVER	 => 'Driver',
			self::TYPE_ADMIN	 => 'Admin',
			self::TYPE_AGENT	 => 'Channel partner',
			self::TYPE_SYSTEM	 => 'System',
			self::TYPE_CORPORATE => 'Corporate'
		];

		if ($type == null)
		{
			$type = self::getUserType();
		}

		return $arr[$type];
	}

	public static function checkAccess($access)
	{
		if (UserInfo::getUserType() == 4)
		{
			return UserInfo::getUser()->checkAccess($access);
		}
		return false;
	}

	public static function getIP()
	{
		if (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$real_ip_adress = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$real_ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$real_ip_adress = $_SERVER['REMOTE_ADDR'];
		}
		if ($real_ip_adress == '::1')
		{
			$real_ip_adress = '122.163.41.5';
		}
		return $real_ip_adress;
	}

	public static function getEntityList()
	{
		$arr = [
			self::TYPE_CONSUMER	 => 'Consumer',
			self::TYPE_VENDOR	 => 'Vendor',
			self::TYPE_DRIVER	 => 'Driver',
			self::TYPE_ADMIN	 => 'Admin',
			self::TYPE_AGENT	 => 'Channel partner',
			self::TYPE_SYSTEM	 => 'System',
			self::TYPE_CORPORATE => 'Corporate',
			self::TYPE_INTERNAL	 => 'Internal'
		];
		return $arr;
	}

	public static function getGA4UserId()
	{
		$GA4UserID = 0;
		if(UserInfo::getUserType() == UserInfo::TYPE_CONSUMER)
		{
			$GA4UserID = UserInfo::getUserId();
		}
		if(isset($GLOBALS["GA4_USER_ID"]))
		{
			$GA4UserID = $GLOBALS["GA4_USER_ID"];
		}
		
		return $GA4UserID;
	}
}
