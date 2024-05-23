<?php

/**
 * This is the model class for table "contact_profile".
 *
 * The followings are the available columns in table 'contact_profile':
 * @property string $cr_id
 * @property integer $cr_contact_id
 * @property integer $cr_is_driver
 * @property integer $cr_is_vendor
 * @property integer $cr_is_consumer
 * @property integer $cr_is_partner
 * @property integer $cr_status
 * @property string $cr_created
 * 
 * The followings are the available model relations:
 * @property Contact $contactProfile
 */
class ContactProfile extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact_profile';
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "cr_status = 1",
		);
		return $arr;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cr_contact_id', 'required'),
			array('cr_is_driver, cr_is_vendor, cr_is_consumer, cr_is_partner, cr_status', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cr_id, cr_contact_id, cr_is_driver, cr_is_vendor, cr_is_consumer, cr_is_partner, cr_status, cr_created', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array
			(
			'contactProfile' => array(self::HAS_ONE, 'Contact', 'cr_contact_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cr_id'			 => 'Cr',
			"cr_contact_id"	 => "Cr Contact Id",
			'cr_is_driver'	 => 'Cr Is Driver',
			'cr_is_vendor'	 => 'Cr Is Vendor',
			'cr_is_consumer' => 'Cr Is Consumer',
			'cr_is_partner'	 => 'Cr Is Partner',
			'cr_status'		 => 'Cr Status',
			'cr_created'	 => 'Cr Created',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('cr_id', $this->cr_id, true);
		$criteria->compare('cr_is_driver', $this->cr_is_driver);
		$criteria->compare('cr_is_vendor', $this->cr_is_agent);
		$criteria->compare('cr_is_consumer', $this->cr_is_consumer);
		$criteria->compare('cr_is_partner', $this->cr_is_partner);
		$criteria->compare('cr_status', $this->cr_status);
		$criteria->compare('cr_created', $this->cr_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContactProfile the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used for setting up the profile for a contact
	 * 	-	Create Profile
	 * 	-	Update Profile
	 * 
	 * Both Create and Update
	 * 
	 * @param type $contactId - Unique Id		-	Mandatory   
	 * @param int $userType - UserInfo::Type_*	-	Mandatory
	 * @param type $userId - - Entity UserId			-	Mandatory
	 * @return type
	 */
	public function setProfile($contactId, $userType)
	{
		$returnset = new ReturnSet();
		if(empty($contactId) || empty($userType))
		{
			throw new Exception("Invalid Parameters", ReturnSet::ERROR_INVALID_DATA);
		}
		//Check for contact id whether its new or old
		$isExist = ContactProfile::checkExists($contactId);
		if(!$isExist)
		{
			$returnset = ContactProfile::model()->createProfile($contactId, $userType);
		}
		return $returnset;
	}

	/**
	 * This function returns the model for a specific contact Id
	 *
	 * @param int $contactId - Unique Id	- Mandatory
	 * 
	 * @return CActiveRecord - YII Default
	 */
	public function findByContactId($contactId)
	{
		$model = ContactProfile::model()->find("cr_contact_id=:cId", ['cId' => $contactId]);
		return $model;

//		$model = ContactProfile::model()->findAll(array("condition" => "cr_contact_id =$contactId"));
//		return $model;
	}

	public static function chekDco($vendorId)
	{
		$sql		 = "SELECT cp.cr_is_driver FROM contact_profile cp
						INNER JOIN contact c ON c.ctt_id=cp.cr_contact_id
						INNER JOIN contact c1 ON c1.ctt_id=c.ctt_ref_code
						WHERE cp.cr_is_vendor =:id
					";
		$params		 = ["id" => $vendorId];
		$driverId	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return ($driverId > 0) ? $driverId : false;
	}

	public static function getUserId($contactId)
	{
		$sql	 = "SELECT IFNULL(cp1.cr_is_consumer, cp.cr_is_consumer) as userId FROM contact c
						INNER JOIN contact_profile cp ON c.ctt_id=cp.cr_contact_id
						INNER JOIN contact c1 ON c1.ctt_id=c.ctt_ref_code
						INNER JOIN contact_profile cp1 ON c1.ctt_id=cp1.cr_contact_id
						WHERE c.ctt_id=:id
					";
		$params	 = ["id" => $contactId];
		$userId	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return ($userId > 0) ? $userId : false;
	}

	/**
	 * This function checks whether the contact id exists or not
	 * 
	 * @param type $contactId	- Unique Id		- Mandatory
	 * 
	 * @return boolean
	 */
	public static function checkExists($contactId)
	{
		$sql	 = "SELECT COUNT(1) FROM contact_profile WHERE cr_contact_id = :id";
		$count	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $contactId]);
		return ($count > 0);
	}

	public static function isDriver($contactId)
	{
		return (ContactProfile::getDrvId($contactId) !== false);
	}

	public static function isVendor($contactId)
	{
		return (ContactProfile::getVndId($contactId) !== false);
	}

	/**
	 * This function is used for updating the contact profile
	 * 
	 * @param int $contactId					- unique Id		- Mandatory
	 * @param int $userType - UserInfo::Type_*	- User type		- Mandatory
	 * @param int $userId	- Entity UserId		- UserId		-	Mandatory
	 * 
	 * @return \ReturnSet
	 */
	public static function updateProfile($contactId, $userType)
	{
		Logger::profile("ContactProfile:updateProfile Started");
		$returnset	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$contactProfile = ContactProfile::model()->findByContactId($contactId);

			$DBId = $contactProfile->cr_id;

			if(empty($contactProfile))
			{
				throw new Exception("Contact not found", ReturnSet::ERROR_VALIDATION);
			}

			switch($userType)
			{
				case UserInfo::TYPE_DRIVER:
					$drvId							 = self::getDrvId($contactId);
					$contactProfile->cr_is_driver	 = (int) $drvId;
					break;

				case UserInfo::TYPE_VENDOR:
					$vndId							 = self::getVndId($contactId);
					$contactProfile->cr_is_vendor	 = (int) $vndId;
					break;

				case UserInfo::TYPE_CONSUMER:
					$userId							 = self::getUsrId($contactId);
					$contactProfile->cr_is_consumer	 = $userId;
					break;

				case UserInfo::TYPE_AGENT:
					$agentId						 = self::getAgtId($contactId);
					$contactProfile->cr_is_partner	 = $agentId;
					break;

				default:
					break;
			}

			//$contactProfile->cr_id		 = (int) $contactProfile->cr_id;
			$contactProfile->cr_status	 = 1;
			$contactProfile->cr_created	 = new CDbExpression('now()');

			if(!$contactProfile->save())
			{
				throw new Exception(json_encode($contactProfile->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			DBUtil::commitTransaction($transaction);

			$returnset->setStatus(true);
			$returnset->setMessage("Updated the record");
		}
		catch(Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}
		Logger::profile("ContactProfile:updateProfile Ended");
		return $returnset;
	}

	/**
	 * Get driver id from profile
	 * @param type $contactId
	 * @return type
	 * @throws Exception
	 */
	public static function getDrvId($contactId = 0)
	{
		if($contactId == 0)
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		$sql = "SELECT drv_id FROM drivers WHERE drv_contact_id = :id AND drv_active > 0";
		return DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $contactId]);
	}

	/**
	 * Get vendor Id from profile
	 * @param type $contactId
	 * @return type
	 * @throws Exception
	 */
	public static function getVndId($contactId = 0)
	{
		if($contactId == 0)
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		$sql = "SELECT vnd_id FROM vendors WHERE vnd_contact_id = :id AND vnd_active > 0";
		return DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $contactId]);
	}

	/**
	 * Get user Id from profile
	 * @param type $contactId
	 * @return type
	 * @throws Exception
	 */
	public static function getUsrId($contactId = 0)
	{
		if($contactId == 0)
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql = "SELECT user_id FROM users WHERE usr_contact_id = :id AND usr_active > 0";
		return DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $contactId]);
	}

	/**
	 * Get agent Id from profile
	 * @param type $contactId
	 * @return type
	 * @throws Exception
	 */
	public static function getAgtId($contactId = 0)
	{
		if($contactId == 0)
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql = "SELECT agt_id FROM agents WHERE agt_contact_id = :id AND agt_active > 0";
		return DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $contactId]);
	}

	/**
	 * This function is used for creating a contact profile
	 * 
	 * @param type $contactId	- unique Id				-	Mandatory
	 * @param type $userType	- UserInfo::Type_*		-	Mandatory
	 * @param type $userId		- Entity UserId			-	Mandatory
	 * 
	 * @return \ReturnSet
	 */
	public function createProfile($contactId, $userType)
	{
		Logger::profile("ContactProfile:createProfile Started");
		$returnset	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$model	 = new ContactProfile();
			$count	 = ContactProfile::checkExists($contactId);
			if($count)
			{
				throw new Exception("Failed to create profile. This contact id exists", ReturnSet::ERROR_INVALID_DATA);
			}

			$model->cr_contact_id	 = $contactId;
			$oldUserId				 = $model->cr_is_consumer;
			switch($userType)
			{
				case UserInfo::TYPE_DRIVER:

					$sql	 = "SELECT drv_id FROM drivers WHERE drv_contact_id = :id";
					$drvId	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $contactId]);

					$model->cr_is_driver = (int) $drvId;
					break;

				case UserInfo::TYPE_VENDOR:

					$sql	 = "SELECT vnd_id FROM vendors WHERE vnd_contact_id = :id";
					$vndId	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $contactId]);

					$model->cr_is_vendor = (int) $vndId;
					break;

				case UserInfo::TYPE_CONSUMER:
					$sql					 = "SELECT user_id FROM users WHERE usr_contact_id = :id";
					$userId					 = DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $contactId]);
					$model->cr_is_consumer	 = $userId;
					break;

				case UserInfo::TYPE_AGENT:
					$sql					 = "SELECT agt_id FROM agents WHERE agt_contact_id = :id ";
					$agentId				 = DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $contactId]);
					$model->cr_is_partner	 = $agentId;
					break;
			}

			$model->cr_status	 = 1;
			$model->cr_created	 = new CDbExpression('now()');

			if(!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			if(($oldUserId == "" || $oldUserId == "0") && $model->cr_is_consumer > 0)
			{
				Users::addSignUpBonus($userId);
			}
			$returnset->setStatus(true);
			$returnset->setMessage("Profile created");
			DBUtil::commitTransaction($transaction);
		}
		catch(Exception $ex)
		{
			Logger::exception($ex);
			ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		Logger::profile("ContactProfile:updateProfile Ended");
		return $returnset;
	}

	/**
	 * @return array()  contact_profile row data
	 */
	public static function getProfileByCttId($contactId)
	{
		if(trim($contactId) == null || trim($contactId) == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params	 = array('contactId' => $contactId);
		$sql	 = "SELECT cr_contact_id, cr_is_vendor, cr_is_driver, cr_is_consumer, cr_is_partner
					FROM contact_profile WHERE cr_contact_id = :contactId AND cr_status = 1";

		return DBUtil::queryRow($sql, null, $params);
	}

	/**
	 * 
	 * @param type $contactId
	 * @throws Exception
	 */
	public static function deactivate($contactId)
	{
		if(empty($contactId))
		{
			throw new Exception("Invalid data passed", ReturnSet::ERROR_INVALID_DATA);
		}
		Logger::error('contactProfile deactivate: set cr_status = 0. $contactId:' . $contactId, true);
		$deactivateProfile = " UPDATE contact_profile SET cr_status = 0 WHERE cr_contact_id = $contactId";
		DBUtil::command($deactivateProfile)->execute();
	}

	/**
	 * 
	 * @param type $contactId
	 * @throws Exception
	 */
	public static function activate($contactId)
	{
		if(empty($contactId))
		{
			throw new Exception("Invalid data passed", ReturnSet::ERROR_INVALID_DATA);
		}

		$activate = " UPDATE contact_profile SET cr_status = 1 WHERE cr_contact_id = $contactId";
		DBUtil::command($activate)->execute();
	}

	/**
	 * 
	 * @param int $contactId
	 * @param int $entityId
	 * @param int $entityType
	 * @return bool
	 * @throws Exception
	 */
	public static function updateEntity($contactId, $entityId, $entityType = UserInfo::TYPE_CONSUMER)
	{
		$model = self::findByContactId($contactId);
		if(!$model)
		{
			$model					 = new ContactProfile();
			$model->cr_contact_id	 = $contactId;
		}
		switch($entityType)
		{
			case UserInfo::TYPE_CONSUMER:
				$model->cr_is_consumer	 = $entityId;
				break;
			case UserInfo::TYPE_DRIVER:
				$model->cr_is_driver	 = $entityId;
				break;
			case UserInfo::TYPE_VENDOR:
				$model->cr_is_vendor	 = $entityId;
				break;
			case UserInfo::TYPE_AGENT:
				$model->cr_is_partner	 = $entityId;
				break;
			default:
				break;
		}
		if($model->validate())
		{
			return $model->save();
		}
	}

	/**
	 * This function is used for finding the entity id from profile
	 * @param type $contactId
	 * @param type $entityType
	 * @return type
	 */
	public static function getEntityById($contactId, $entityType)
	{
		switch($entityType)
		{
			case UserInfo::TYPE_DRIVER:
				$profileId	 = "cr_is_driver";
				break;
			case UserInfo::TYPE_VENDOR:
				$profileId	 = "cr_is_vendor";
				break;
			case UserInfo::TYPE_CONSUMER:
				$profileId	 = "cr_is_consumer";
				break;
			case UserInfo::TYPE_AGENT:
				$profileId	 = "cr_is_partner";
				break;
			default:
				break;
		}

		$sql		 = "  SELECT $profileId as id FROM contact_profile  WHERE cr_contact_id = :contactId AND cr_status = 1";
		$arrProfile	 = DBUtil::queryRow($sql, DBUtil::SDB(), ['contactId' => $contactId]);
		return $arrProfile;
	}

	public static function verifyEntities($entityData, $type)
	{
		switch($type)
		{
			CASE '1':
				$entityId	 = "cr_is_vendor";
				break;
			CASE '2':
				$entityId	 = "cr_is_driver";
				break;
			CASE '3':
				$entityId	 = "cr_is_consumer";
				break;
			default:
				break;
		}
		$transaction = DBUtil::beginTransaction();
		try
		{
			if(empty($entityData[$entityId]) || $entityData[$entityId] == 0)
			{
				exit();
			}
			$sql	 = "SELECT cr_contact_id from contact_profile WHERE cr_status = 1 AND $entityId =" . $entityData[$entityId];
			$data	 = DBUtil::query($sql);
			foreach($data as $data)
			{
				if($type === '1')
				{
					$vendorModel = Vendors::model()->findByContactID($data['cr_contact_id']);
					if($vendorModel)
					{
						$id			 = $vendorModel[0]->vnd_contact_id;
						$updateData	 = "UPDATE contact_profile SET cr_status = 0 WHERE cr_contact_id<>$id AND $entityId=" . $entityData[$entityId];
						DBUtil::command($updateData)->execute();
					}
				}
				if($type === '2')
				{
					$driverModel = Drivers::model()->findByContactID($data['cr_contact_id']);
					if($driverModel)
					{
						$id			 = $driverModel[0]->drv_contact_id;
						$updateData	 = "UPDATE contact_profile SET cr_status = 0 WHERE cr_contact_id<>$id AND $entityId=" . $entityData[$entityId];
						DBUtil::command($updateData)->execute();
					}
				}
				if($type === '3')
				{
					$userModel = Users::model()->findByContactID($data['cr_contact_id']);
					if($userModel)
					{
						$id			 = $userModel[0]->usr_contact_id;
						$updateData	 = "UPDATE contact_profile SET cr_status = 0 WHERE cr_contact_id<>$id AND $entityId=" . $entityData[$entityId];
						DBUtil::command($updateData)->execute();
					}
				}
			}
			DBUtil::commitTransaction($transaction);
			Logger::error('contactProfile verifyEntities: set cr_status = 0. Request:' . json_encode($entityData) . ' Type: ' . $type, true);
		}
		catch(Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::error($e->getMessage());
			$returnset->setException($e);
		}
	}

	public static function getByUserId($userid = 0)
	{
		if($userid == 0)
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql = "SELECT cr_contact_id FROM contact_profile WHERE cr_is_consumer = :id AND cr_status =1";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['id' => $userid]);
	}

	public static function getByVndId($vndid = 0)
	{
		if($vndid == 0)
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql = "SELECT cr_contact_id FROM contact_profile WHERE cr_is_vendor = :id AND cr_status =1";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['id' => $vndid]);
	}

	public static function getByDrvId($drvid = 0)
	{
		if($drvid == 0)
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql = "SELECT cr_contact_id FROM contact_profile WHERE cr_is_driver = :id AND cr_status =1";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['id' => $drvid]);
	}

	/**
	 * This function is used for finding the entity id from profile
	 * @param type $contactId
	 * @param type $entityType
	 * @return type
	 */
	public static function getByEntityId($entityId, $entityType = UserInfo::TYPE_CONSUMER)
	{
		switch($entityType)
		{
			case UserInfo::TYPE_DRIVER:
				$profileId	 = "cr_is_driver";
				break;
			case UserInfo::TYPE_VENDOR:
				$profileId	 = "cr_is_vendor";
				break;
			case UserInfo::TYPE_CONSUMER:
				$profileId	 = "cr_is_consumer";
				break;
			case UserInfo::TYPE_AGENT:
				$profileId	 = "cr_is_partner";
				break;
			default:
				break;
		}

		$sql		 = "SELECT cttPrimary.ctt_id  FROM contact_profile
				INNER JOIN contact ctt ON ctt_id=cr_contact_id
				INNER JOIN contact cttPrimary ON ctt.ctt_ref_code=cttPrimary.ctt_id
				WHERE $profileId=:id AND cr_status=1";
		$arrProfile	 = DBUtil::queryScalar($sql, \DBUtil::MDB(), ['id' => $entityId]);
		return $arrProfile;
	}

	public static function getByVendorId($entityId)
	{
		$sql		 = "SELECT cttPrimary.ctt_id  FROM contact_profile
						INNER JOIN contact ctt ON ctt_id=cr_contact_id
						INNER JOIN contact cttPrimary ON ctt.ctt_ref_code=cttPrimary.ctt_id
						WHERE cr_is_vendor=:id ";
		$arrProfile	 = DBUtil::queryScalar($sql, DBUtil::SDB(), ['id' => $entityId]);
		return $arrProfile;
	}

	/**
	 * show user id from agent id
	 * @param type $agentId
	 * @return type
	 */
	public static function getUserByAgentId($agentId)
	{
		$sql		 = "SELECT contact_profile.cr_is_consumer as user_id FROM contact_profile
						INNER JOIN contact ctt ON ctt_id=cr_contact_id
						INNER JOIN contact cttPrimary ON ctt.ctt_ref_code=cttPrimary.ctt_id
						WHERE cr_is_partner=:id ";
		$arrProfile	 = DBUtil::queryScalar($sql, DBUtil::MDB(), ['id' => $agentId]);
		return $arrProfile;
	}

	public static function getProfilebyPhone($phoneNo)
	{
		Filter::parsePhoneNumber($phoneNo, $code, $number);

		$params	 = ['number' => $number, 'code' => $code];
		$sql	 = "SELECT  cr.cr_contact_id, cr.cr_is_driver,cr.cr_is_vendor,cr.cr_is_consumer
					FROM   `contact` con
					INNER JOIN contact_phone cp ON con.ctt_id=cp.phn_contact_id AND cp.phn_phone_no<>'' AND cp.phn_phone_no IS NOT NULL
					INNER JOIN contact_profile cr ON cr.cr_contact_id=con.ctt_id AND cr.cr_status=1
					WHERE  phn_phone_no=:number AND phn_phone_country_code=:code 
						AND phn_active=1 AND con.ctt_active=1 ORDER BY phn_is_primary DESC, phn_is_verified DESC";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $result;
	}

	public static function getCodeByCttId($contactId)
	{
		$params	 = array('contactId' => $contactId);
		$sql	 = "SELECT cr_contact_id,
					cr_is_vendor,
					vnd_code,
					cr_is_driver,
					drv_code,
					cr_is_partner,
					cr_is_consumer,
					agt_code
					FROM contact_profile
					LEFT JOIN agents ON agt_id = cr_is_partner AND agt_active > 0
					LEFT JOIN drivers ON drv_id = cr_is_driver AND drv_active > 0
					LEFT JOIN vendors ON vnd_id = cr_is_vendor AND vnd_active > 0
					WHERE cr_contact_id = :contactId AND cr_status = 1";
		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function is used for fetching contact details by contact id
	 * @param integer $contactId
	 * @return array
	 */
	public static function getDriverData($contactId)
	{
		$entityId		 = 0;
		$contactDetails	 = ContactProfile::findByContactId($contactId);
		if(!empty($contactDetails))
		{
			$entityId = $contactDetails->cr_is_driver;
		}
		return ["entityId" => $entityId, "consumerId" => $contactDetails->cr_is_consumer];
	}

	/**
	 * @return ContactProfile
	 * @throws Exception
	 * */
	public static function linkUserId($contactId, $userId)
	{
		$model = ContactProfile::model()->findByContactId($contactId);
		if(!$model)
		{
			$model					 = new ContactProfile();
			$model->cr_contact_id	 = $contactId;
		}
		$model->cr_is_consumer = $userId;
		if(!$model->save())
		{
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		return $model;
	}

	/** Match two user id for merged contacts. Will return the matched contact profile if found
	 *
	 * @return array()|false 
	 *  */
	public static function getMergedContactUser($refUserId, $matchUserId)
	{
		$sql = "SELECT cp1.* 
				FROM contact_profile cp
				INNER JOIN contact c ON cp.cr_contact_id=c.ctt_id AND cp.cr_is_consumer=:refUserId
				INNER JOIN contact c1 ON c.ctt_ref_code=c1.ctt_ref_code
				INNER JOIN contact_profile cp1 ON cp1.cr_contact_id=c1.ctt_id AND cp1.cr_is_consumer=:matchUserId
					";

		$row = DBUtil::queryRow($sql, DBUtil::SDB(), ["refUserId" => $refUserId, 'matchUserId' => $matchUserId]);
		return $row;
	}

//	public static function getProfilebyEmail($email)
//	{
//		$params	 = ['email' => $email];
//		$sql	 = "SELECT  cr.cr_contact_id, cr.cr_is_driver,cr.cr_is_vendor,cr.cr_is_consumer
//					FROM   `contact` con
//                    INNER JOIN contact ctt ON ctt.ctt_ref_code = con.ctt_id
//					INNER JOIN contact_email ce ON con.ctt_id=ce.eml_contact_id AND ce.eml_email_address<>'' AND ce.eml_email_address IS NOT NULL
//					INNER JOIN contact_profile cr ON cr.cr_contact_id=con.ctt_id AND cr.cr_status=1
//					WHERE eml_email_address =:email 
//					AND eml_active=1 AND con.ctt_active=1 ORDER BY eml_is_primary DESC, eml_is_verified DESC";
//		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
//		return $result;
//	} 

	public static function getProfilebyNumber($phoneNo)
	{
		Filter::parsePhoneNumber($phoneNo, $code, $number);

		$params	 = ['number' => $number, 'code' => $code];
		$sql	 = "SELECT  cr.cr_contact_id, cr.cr_is_driver,cr.cr_is_vendor,cr.cr_is_consumer
					FROM   `contact` con
                    INNER JOIN contact ctt ON ctt.ctt_ref_code = con.ctt_id
					INNER JOIN contact_phone cp ON con.ctt_id=cp.phn_contact_id AND cp.phn_phone_no<>'' AND cp.phn_phone_no IS NOT NULL
					INNER JOIN contact_profile cr ON cr.cr_contact_id=con.ctt_id AND cr.cr_status=1
					WHERE  phn_phone_no=:number AND phn_phone_country_code=:code 
						AND phn_active=1 AND con.ctt_active=1 ORDER BY phn_is_primary DESC, phn_is_verified DESC";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $result;
	}

	/**
	 * 
	 * @param type $agentId
	 * @param type $userId
	 * @throws Exception
	 */
	public static function updateAgentByUser($agentId, $userId)
	{
		$returnset = new ReturnSet();
		try
		{
			$contactUserId = ContactProfile::getUserByAgentId($agentId);
			if(!$contactUserId)
			{
				$profileModel = ContactProfile::model()->find('cr_is_consumer=:userId ', ['userId' => $userId]);
				if($profileModel)
				{
					$profileModel->cr_is_partner = $agentId;
					if(!$profileModel->save())
					{
						throw new Exception(CJSON::encode($profileModel->getErrors()), ReturnSet::ERROR_INVALID_DATA);
					}
					$returnset->setMessage("Updated the record");
				}
			}
			elseif($contactUserId != $userId)
			{
				throw new Exception("Contact user id: {$contactUserId} is not same as user id: {$userId} !!!", ReturnSet::ERROR_INVALID_DATA);
			}
			$returnset->setStatus(true);
		}
		catch(Exception $ex)
		{
			$returnset->setMessage($ex->getMessage());
			$returnset->setStatus(false);
			$returnset->setException($ex);
		}

		return $returnset;
	}

	/**
	 * This function is used for finding the count entity id from profile
	 * @param type $contactId
	 * @param type $entityType
	 * @return type
	 */
	public static function getCountByEntityId($entityId, $entityType = UserInfo::TYPE_CONSUMER)
	{
		switch($entityType)
		{
			case UserInfo::TYPE_DRIVER:
				$profileId	 = "cr_is_driver";
				break;
			case UserInfo::TYPE_VENDOR:
				$profileId	 = "cr_is_vendor";
				break;
			case UserInfo::TYPE_CONSUMER:
				$profileId	 = "cr_is_consumer";
				break;
			case UserInfo::TYPE_AGENT:
				$profileId	 = "cr_is_partner";
				break;
			default:
				break;
		}

		$sql			 = "SELECT COUNT(cttPrimary.ctt_id) as cnt 
						FROM contact_profile
						INNER JOIN contact ctt ON ctt_id=cr_contact_id
						INNER JOIN contact cttPrimary ON ctt.ctt_ref_code=cttPrimary.ctt_id
						WHERE $profileId=:id AND cr_status=1";
		$profileCount	 = DBUtil::queryScalar($sql, DBUtil::SDB(), ['id' => $entityId]);
		return $profileCount;
	}

	public static function getEntitybyUserId($userId)
	{
		$params	 = ['userId' => $userId];
		$sql	 = "SELECT  cttPrimary.ctt_id primaryContact,cttPrimary.ctt_id, cr.cr_contact_id, cr.cr_is_driver, cr.cr_is_vendor,
						cr.cr_is_consumer, vnd.vnd_active, drv.drv_active,
						IF(vnd.vnd_active=1, 1 ,0) as vndRank,
						IF(drv.drv_active=1, 1 ,0) as drvRank
					FROM  users usr 
					INNER JOIN contact_profile cr ON cr.cr_is_consumer=usr.user_id AND cr.cr_status=1
					INNER JOIN `contact` con ON cr.cr_contact_id=con.ctt_id AND con.ctt_active=1
					INNER JOIN contact cttPrimary ON con.ctt_ref_code=cttPrimary.ctt_id
					LEFT JOIN vendors vnd ON vnd.vnd_id = cr.cr_is_vendor AND vnd.vnd_id=vnd.vnd_ref_code
					LEFT JOIN drivers drv ON drv.drv_id = cr.cr_is_driver AND drv.drv_id=drv.drv_ref_code
					WHERE usr.user_id = :userId AND usr.usr_active=1  
					ORDER BY vndRank DESC, drvRank DESC";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $result;
	}

	/**
	 * 
	 * @param type $userId
	 * @return type
	 */
	public static function getPreferredUserType($userId)
	{

		$user = Yii::app()->user;

		$user instanceof DcoWebUser;
		$userType	 = $user->getUserType();
		$contactData = ContactProfile::getEntitybyUserId($userId);
		if($contactData['cr_is_vendor'] > 0 && $userType == UserInfo::TYPE_VENDOR)
		{
			return UserInfo::TYPE_VENDOR;
		}
		else if($contactData['cr_is_driver'] > 0 && $userType == UserInfo::TYPE_DRIVER)
		{
			return UserInfo::TYPE_DRIVER;
		}
		else if($contactData['cr_is_consumer'] > 0)
		{
			return UserInfo::TYPE_CONSUMER;
		}
		else
		{
			return null;
		}
	}

	/**
	 * 
	 * @param array() $contactData
	 * @return array()
	 */
	public static function getEntityListByData($contactData)
	{
		$userTypes = [];
		if($contactData['cr_is_consumer'] > 0)
		{
			$userTypes[] = UserInfo::TYPE_CONSUMER;
		}
		if($contactData['cr_is_vendor'] > 0 && $contactData['vnd_active'] > 0)
		{
			$userTypes[] = UserInfo::TYPE_VENDOR;
		}
		if($contactData['cr_is_driver'] > 0 && $contactData['drv_active'] > 0)
		{
			$userTypes[] = UserInfo::TYPE_DRIVER;
		}

		return $userTypes;
	}

	public static function getPrimaryEntitiesByContact($cttId)
	{
		$relCttIds = Contact::getRelatedIds($cttId);

		if($relCttIds)
		{
			$primaryCtt	 = Contact::getPrimaryByIds($relCttIds);
			$cttId		 = (int) $primaryCtt['ctt_id'];
		}

		$relUsersList	 = Users::getRelatedByCttIds($relCttIds);
		$relDriverList	 = Drivers::getRelatedByCttIds($relCttIds);
		$relVendorList	 = Vendors::getRelatedByCttIds($relCttIds);

		if($relDriverList)
		{
			$primaryDrv = Drivers::getPrimaryByIds($relDriverList);
		}
		if($relVendorList)
		{
			$primaryVnd = Vendors::getPrimaryByIds($relVendorList);
		}
		if($relUsersList)
		{
			$primaryUser = Users::getPrimaryByIds($relUsersList);
		}
		$data = [
			'primaryContact' => $cttId,
			'cr_contact_id'	 => $cttId,
			'cr_is_driver'	 => (int) $primaryDrv['drv_id'],
			'cr_is_vendor'	 => (int) $primaryVnd['vnd_id'],
			'cr_is_consumer' => (int) $primaryUser['user_id'],
			'vnd_active'	 => (int) $primaryVnd['vnd_active'],
			'drv_active'	 => (int) $primaryDrv['drv_active']];
		return $data;
	}
}
