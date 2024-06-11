<?php

/**
 * This is the model class for table "contact_email".
 *
 * The followings are the available columns in table 'contact_email':
 * @property integer $eml_id
 * @property integer $eml_contact_id
 * @property string $eml_email_address
 * @property integer $eml_type
 * @property integer $eml_is_verified
 * @property integer $eml_is_primary
 * @property integer $eml_verify_count
 * @property integer $eml_active
 * @property integer $eml_is_expired
 * @property string $eml_verified_date
 * @property string $eml_create_date
 *
 * The followings are the available model relations:
 * @property Contact $emlContact
 */
class ContactEmail extends CActiveRecord
{

	public $mediumType;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact_email';
	}

	public function defaultScope()
	{
		$ta	 = $this->getTableAlias(false, false);
		$arr = array(
			'condition'	 => "eml_active > 0",
			'order'		 => "eml_is_primary desc "
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
			array('eml_email_address', 'required'),
			array('eml_email_address', 'email', 'message' => 'Please enter valid email address', 'checkMX' => true),
			array('eml_email_address', 'length', 'max' => 100, 'on' => 'insert,update,insertApp,vendorjoin', 'message' => 'Email maximun length should be of 100 character',),
			array('eml_contact_id, eml_is_verified, eml_is_primary, eml_active', 'numerical', 'integerOnly' => true),
			array('eml_email_address', 'findEmail', 'on' => 'insert,update,insertApp'),
			array('eml_email_address', 'checkDuplicateUserByVendor', 'on' => 'vendoruserjoin'),
			array('eml_email_address', 'email', 'on' => 'insert,update,insertApp,vendorjoin', 'message' => 'Please enter valid email address', 'checkMX' => true),
			array('vnd_email, validatePhoneEmail', 'required', 'on' => 'unregVendorJoin'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('eml_id, eml_contact_id, eml_email_address, eml_is_verified, eml_is_primary, eml_verify_count, eml_active,eml_is_expired, eml_verified_date, eml_create_date', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'emlContact' => array(self::BELONGS_TO, 'Contact', 'eml_contact_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'eml_id'			 => 'Eml',
			'eml_contact_id'	 => 'Eml Contact',
			'eml_email_address'	 => 'Email',
			'eml_is_verified'	 => 'Eml Is Verified',
			'eml_is_primary'	 => 'Eml Is Primary',
			'eml_active'		 => 'Eml Active',
			'eml_is_expired'	 => 'Eml Is Expired',
			'eml_verified_date'	 => 'Eml Verified Date',
			'eml_create_date'	 => 'Eml Create Date',
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

		$criteria->compare('eml_id', $this->eml_id);
		$criteria->compare('eml_contact_id', $this->eml_contact_id);
		$criteria->compare('eml_email_address', $this->eml_email_address, true);
		$criteria->compare('eml_is_verified', $this->eml_is_verified);
		$criteria->compare('eml_is_primary', $this->eml_is_primary);
		$criteria->compare('eml_active', $this->eml_active);
		$criteria->compare('eml_is_expired', $this->eml_is_expired);
		$criteria->compare('eml_verified_date', $this->eml_verified_date, true);
		$criteria->compare('eml_create_date', $this->eml_create_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContactEmail the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function validatePhoneEmail($attribute, $params)
	{
		$scenario	 = $this->scenario;
		$checkEmail	 = self::model()->findByEmailAddress($attribute);

		if ($checkEmail)
		{
			$this->addError($attribute, 'Email Address already exists');
			return false;
		}

		return true;
	}

	public function findByEmailAddress($email)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('eml_email_address', $email);
		$criteria->compare("eml_active", ">0");
		return $this->find($criteria);
	}

	public function checkDuplicateEmail($attribute, $params)
	{
		if ($this->eml_email_address != "")
		{
			$sql = "";
			if ($this->eml_contact_id > 0)
			{
				$sql = "SELECT COUNT('contact_email.eml_email_address') as cnt FROM `contact_email` WHERE  contact_email.eml_email_address='" . $this->eml_email_address . "' and eml_active=1 and contact_email.eml_contact_id!=" . $this->eml_contact_id;
			}
			else
			{
				$sql = "SELECT COUNT('contact_email.eml_email_address') as cnt FROM `contact_email` WHERE  eml_active=1 and contact_email.eml_email_address='" . $this->eml_email_address . "'";
			}
			$cntemail = DBUtil::command($sql)->queryScalar();
			if ($cntemail > 0)
			{
				$this->addError($attribute, "This Emailid  already registered");
				return false;
			}
		}

		return true;
	}

	public function checkDuplicateEmailByDriver($attribute, $params)
	{
		if ($this->eml_email_address != "")
		{
			$sql = "";
			if ($this->eml_contact_id > 0)
			{
				$sql = "SELECT COUNT('contact_email.eml_email_address') as cnt FROM `contact_email` INNER JOIN drivers ON drivers.drv_contact_id = contact_email.eml_contact_id WHERE  contact_email.eml_email_address='" . $this->eml_email_address . "' and eml_active=1 and contact_email.eml_contact_id!=" . $this->eml_contact_id;
			}
			else
			{
				$sql = "SELECT COUNT('contact_email.eml_email_address') as cnt FROM `contact_email` INNER JOIN drivers ON drivers.drv_contact_id = contact_email.eml_contact_id WHERE  eml_active=1 and contact_email.eml_email_address='" . $this->eml_email_address . "'";
			}
			$cntemail = DBUtil::command($sql)->queryScalar();
			if ($cntemail > 0)
			{
				$this->addError($attribute, "1");
				return false;
			}
		}

		return true;
	}

	/**
	 * @deprecated since  2022
	 * not used function
	 */
	public function checkDuplicateEmailByVendor($attribute, $params)
	{

		if ($this->eml_email_address != "")
		{
			$sql = "";
			if ($this->eml_contact_id > 0)
			{
				$sql = "SELECT COUNT('contact_email.eml_email_address') as cnt FROM `vendors` INNER JOIN `contact` ON contact.ctt_id=vendors.vnd_contact_id AND contact.ctt_active=1 INNER JOIN `contact_email` ON contact_email.eml_contact_id=contact.ctt_id AND contact_email.eml_active=1 WHERE contact_email.eml_email_address='$this->eml_email_address' and  contact_email.eml_contact_id!=" . $this->eml_contact_id;
			}
			else
			{
				$sql = "SELECT COUNT('contact_email.eml_email_address') as cnt FROM `vendors` INNER JOIN `contact` ON contact.ctt_id=vendors.vnd_contact_id AND contact.ctt_active=1 INNER JOIN `contact_email` ON contact_email.eml_contact_id=contact.ctt_id AND contact_email.eml_active=1 WHERE contact_email.eml_email_address='$this->eml_email_address'";
			}
			$cntemail = DBUtil::command($sql)->queryScalar();
			if ($cntemail > 0)
			{
				$this->addError($attribute, "This emailId  already registered as vendor");
				return false;
			}
		}
		return true;
	}

	public function findByEmail2($email)
	{
		$sql = "SELECT COUNT(1) as cnt FROM `contact_email` WHERE eml_active=1 AND eml_email_address='$email'";
		$cnt = DBUtil::command($sql)->queryScalar();
		return $cnt;
	}

	/**
	 * @deprecated since version 2020
	 * new function getPrimaryEmail
	 */
	public function findPrmryEmailByContactId($cttid)
	{
		$sql	 = "SELECT eml_email_address FROM `contact_email` WHERE eml_active=1 AND eml_is_primary=1 AND eml_contact_id='$cttid'";
		$result	 = DBUtil::command($sql)->queryScalar();
		return $result;
	}

	public function findByContactID($cttId)
	{
		if ($cttId == "" || $cttId == null)
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$model = ContactEmail::model()->findAll(array("condition" => "eml_contact_id =$cttId AND eml_active > 0 AND eml_email_address !=''", "order" => "eml_is_primary desc, eml_is_verified DESC"));
		return $model;
	}

	public function findEmailIdByEmail($email)
	{
		$model = ContactEmail::model()->find('eml_email_address=:emailid AND eml_active=1', ['emailid' => $email]);
		return $model;
	}

	public function findEmailIdByEmailInActive($email)
	{
		$model = ContactEmail::model()->find('eml_email_address=:emailid AND eml_active=0', ['emailid' => $email]);
		return $model;
	}

	public function findByEmailAndContact($email, $contactId)
	{
		return $this->find("eml_email_address=:emailId AND eml_contact_id=:contactId AND eml_active=1", ['emailId' => $email, 'contactId' => $contactId]);
	}

	public function checkEmailFormat($attribute, $params)
	{

		if ($this->eml_email_address != NULL)
		{
			if (!filter_var($this->eml_email_address, FILTER_VALIDATE_EMAIL))
			{
				$this->addError($attribute, 'Please check your email id format');
				return false;
			}
		}
		return true;
	}

	public function checkEmailLength($attribute, $params)
	{

		if ($this->eml_email_address == NULL || $this->eml_email_address == '' || strlen(trim($this->eml_email_address)) > 100)
		{
			$this->addError($attribute, 'Email number length must be less than 100 character');
			return false;
		}
		return true;
	}

	public function removeEmail($arrayemail, $cttid)
	{
		$sql = "Update `contact_email` set eml_is_primary=0, eml_active=0 WHERE eml_email_address not in ($arrayemail) and eml_contact_id=$cttid";
		$cnt = DBUtil::command($sql)->execute();
		return $cnt;
	}

	public function removeEmailByEmail($email, $cttid)
	{
		$params	 = ['email' => $email, 'contact' => $cttid];
		$sql	 = "Update `contact_email` set eml_is_primary=0, eml_active=0 WHERE eml_email_address=:email and eml_contact_id=:contact";
		$cnt	 = DBUtil::execute($sql, $params);
		return $cnt;
	}

	public function updateEmailByContactId($email, $cttid)
	{
		$sql = "Update `contact_email` set eml_email_address='" . $email . "' WHERE  eml_is_primary=1 AND eml_active=1 AND eml_contact_id='" . $cttid . "'";
		return DBUtil::command($sql)->execute();
	}

	public function CheckEmailRequired($attribute, $params)
	{
		if ($this->eml_email_address == NULL || $this->eml_email_address == '')
		{
			$this->addError($attribute, 'Please provide your email id');
			return false;
		}
		return true;
	}

	public function getContactEmailById($Id = 0)
	{
		if ($Id > 0)
		{
			$email	 = "select eml_email_address From contact_email where eml_is_primary = 1 AND eml_active=1 AND eml_contact_id =" . $Id;
			$sql	 = DBUtil::command($email)->queryScalar();
		}
		return $sql;
	}

	public function getAlternateEmailById($cttId)
	{
		$arrTotal = $this->findBySql("Select eml_email_address as altEmail 
				From contact
				INNER JOIN contact_email eml ON eml.eml_contact_id = contact.ctt_id 
				where contact.ctt_id = $cttId AND eml.eml_is_primary != 1 
				ORDER BY eml.eml_create_date DESC LIMIT 1");
		return $arrTotal;
	}

	public function checkDuplicateUserByVendor($attribute, $params)
	{
		$email	 = $this->eml_email_address;
		$usersId = Users::model()->linkUserid($email, $phone);
		$vndId	 = Vendors::getVendorIdByUserId($usersId);
		if ($vndId != "")
		{
			$this->addError($attribute, "This Email Address already taken by another vendor");
			return false;
		}
		return true;
	}

	public static function getObject($email, $contactId, $isPrimary = NULL, $createNew = true, $type)
	{
		$model = ContactEmail::model()->findByEmailAndContact($email, $contactId);
		if (!$model && $createNew)
		{
			$model						 = new ContactEmail();
			$model->eml_contact_id		 = $contactId;
			$model->eml_email_address	 = $email;
			$model->eml_type			 = $type;
		}
		$model->eml_is_primary = $isPrimary == NULL ? 0 : $isPrimary;
		if ($email == null)
		{
			return false;
		}
		return $model;
	}

	public function remove($model = null)
	{
		if ($model == NULL)
		{
			$model = $this;
		}
		$model->eml_active		 = 0;
		$model->eml_is_primary	 = 0;
		$model->update();
		$desc					 = "Contact email remove: $model->eml_email_address ";
		$event					 = ContactLog::CONTACT_EMAIL_REMOVE;
		ContactLog::model()->createLog($model->eml_contact_id, $desc, $event, null);
	}

	public static function saveEmailsT($emailModels, $cttId)
	{
		$oldModels	 = ContactEmail::model()->findByContactID($cttId);
		$i			 = 0;
		foreach ($oldModels as $model)
		{
			if (trim($model->eml_email_address) != trim($emailModels[$i]->eml_email_address))
			{
				$model->remove();
			}
			$i++;
		}
		foreach ($emailModels as $model)
		{
			if ($model->eml_email_address != NULL)
			{
				$model->eml_contact_id	 = $cttId;
				$res					 = $model->save();
				if (!$res)
				{
					throw new Exception(json_encode($model->getErrors()));
				}
			}
		}
	}

	/**
	 * This function gets the model of the all records based on contact Id
	 * @param type $cttId
	 * @return type
	 */
	public function findContactEmail($cttId)
	{
		$sql	 = "SELECT * FROM contact_email WHERE eml_contact_id = :cttId AND eml_active = 1 GROUP By eml_email_address";
		$model	 = ContactEmail::model()->findAllBySql($sql,
				[
					":cttId" => $cttId,
		]);

		return $model;
	}

	public static function getByVerifiedContactId($cttId)
	{
		$params	 = ['id' => $cttId];
		$sql	 = "select eml_email_address FROM contact_email WHERE 	eml_is_verified=1 AND eml_contact_id= :id ORDER BY eml_email_address ASC LIMIT 1";
		$email	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return $email;
	}

	/**
	 * This function is used for inserting and updating the Email Address details
	 * @param type modelObject $emailModels
	 * @param type (int) $cttId
	 * @throws Exception
	 */
	public static function saveEmails($emailModels, $cttId)
	{
		$returnSet	 = new ReturnSet();
		$oldModels	 = ContactEmail::model()->findContactEmail($cttId);
		$emailArray	 = [];
		$transaction = DBUtil::beginTransaction();
		try
		{
			foreach ($oldModels as $model)
			{
				array_push($emailArray, $model->eml_email_address);
			}
			// validate and compare the email address record for new and old model data 
			foreach ($emailModels as $newModel)
			{
				$oldModel = self::model()->isExists($newModel->eml_email_address, $cttId);

				//Compare the newmodel and oldmodel for email and update the matched record as well as check for status zero 
				// if found then update the status for the same to one 
				if (trim($newModel->eml_email_address) === trim($oldModel->eml_email_address) && trim($oldModel->eml_email_address) != null && $oldModel->eml_active == 0)
				{
					$status = ContactEmail::countDuplicateItems($newModel->eml_email_address, $cttId);
					if (!$status)
					{
						$params	 = array('eml_contact_id' => $cttId, 'eml_email_address' => $oldModel->eml_email_address, 'eml_id' => $oldModel->eml_id);
						$sql	 = "UPDATE contact_email 
									SET    eml_active = 1, eml_verified_date = Now() 
									WHERE  eml_contact_id = :eml_contact_id AND eml_email_address = :eml_email_address AND eml_id= :eml_id";
						DBUtil::execute($sql, $params);
						$returnSet->setStatus(true);
					}
					continue;
				}
				// checking for matched part and status one(active record)
				else if (trim($newModel->eml_email_address) === trim($oldModel->eml_email_address) && $oldModel->eml_active == 1)
				{
					/** @var ContactEmail $newModel */
					if ($newModel->eml_is_primary != $oldModel->eml_is_primary && $oldModel->eml_active == 1)
					{
						$provider	 = ($newModel->mediumType > 1) ? $newModel->mediumType : SocialAuth::Eml_aaocab;
						$returnSet	 = ContactEmail::model()->addNew($cttId, $newModel->eml_email_address, $provider, $newModel->eml_is_primary, 1);
					}
					continue;
				}
				//  checking the newmodel email number existence in oldmodel data and if not add the record
				if (!(in_array($newModel->eml_email_address, $emailArray)))
				{
					$provider	 = ($newModel->mediumType > 1) ? $newModel->mediumType : SocialAuth::Eml_aaocab;
					$returnSet	 = ContactEmail::model()->addNew($cttId, $newModel->eml_email_address, $provider, $newModel->eml_is_primary, 1);
					break;
				}
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public static function setPrimaryEmail($cttId)
	{
		$sql	 = "SELECT eml_id FROM contact_email WHERE eml_active=1 AND eml_contact_id={$cttId} ORDER BY eml_is_primary DESC, eml_is_verified DESC, eml_create_date ASC LIMIT 1";
		$eml_id	 = DBUtil::command($sql)->queryScalar();
		$model	 = self::model()->findByPk($eml_id);
		if ($eml_id != NULL)
		{
			$model->eml_is_primary	 = 1;
			$model->save();
			$sql					 = "UPDATE contact_email SET eml_is_primary=0 WHERE eml_id<>$eml_id AND eml_contact_id={$cttId}";
			DBUtil::command($sql)->execute();
		}
	}

	public function updateEmailByContact($email, $cttid)
	{
//		$sql = "Update `contact_email` set eml_email_address='" . $email . "' WHERE  eml_is_primary=1 AND eml_active=1 AND eml_contact_id='" . $cttid . "'";
//		return DBUtil::command($sql)->execute();
		$model = ContactEmail::model()->find('eml_email_address=:emailid ', ['emailid' => $email]);
		if (!$model)
		{
			$model						 = new ContactEmail();
			$model->eml_contact_id		 = $cttid;
			$model->eml_email_address	 = $email;
			$model->save();
		}
		//return $model;
	}

	/**
	 * This function is used for validating the email data
	 * @param type $requestData
	 * @return type
	 */
	public function validateData($contactId, $contactValue, $sourceType)
	{
		switch ($sourceType)
		{
			case SocialAuth::Eml_aaocab:
				$response = $this->checkEmailId($contactId, $contactValue, $sourceType);
				break;

			default:
				$response = $this->updateContacts($contactId, $contactValue, $sourceType);
				break;
		}

		return $response;
	}

	/**
	 * This function is used for check whether is exists or not and if 
	 * exists then with whom
	 * 
	 * @param int	 $contactId		- Unique Person Id				-	Optional
	 * @param string $value			- Email Address					-	Mandatory
	 * @param int	 $returnType	- 1: Array , 0: boolean			-	Optional
	 * @param int	 $type			- SocialAuth::Eml_*				-	Optional
	 * 
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function checkData($value, $returnType = 0, $contactId = 0, $type = 0, $validate = 0)
	{
		$returnSet = new ReturnSet();

		try
		{
			if (empty($value))
			{
				throw new Exception("Invalid paramaters", ReturnSet::ERROR_INVALID_DATA);
			}
			$param			 = ['email' => trim(str_replace(' ', '', $value))];
			//Finds all the contact related to a particular email id
			$findContactIds	 = "
				SELECT eml_id, eml_contact_id
				FROM `contact_email`
				WHERE eml_email_address = :email
					AND eml_active = 1
			";
			//Email Type check
			if ($type > 0)
			{
				$findContactIds .= " AND eml_type = $type";
			}
			//Particular contact Id check
			if ($contactId > 0)
			{
				$findContactIds .= " AND eml_contact_id = $contactId";
			}
			if ($validate > 0)
			{
				$findContactIds .= " AND eml_is_verified = 1";
			}


			$arrEmailDetails = DBUtil::queryAll($findContactIds, DBUtil::SDB(), $param);

			/**
			 * Case 1 : If return type 1 and data is there Do Set data, set status = true
			 * Case 2 : If return type null and data is also blank , default status = false
			 */
			if ($returnType)
			{
				$returnSet->setData($arrEmailDetails);
			}

			if (!empty($arrEmailDetails))
			{
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$returnSet->setException($ex);
		}

		return $returnSet;
	}

	/**
	 * This function is used for validating the email based on MX Records
	 * 
	 * @param type $email
	 * @param type $domain
	 * @return type
	 */
	public static function mxrecordValidate($email, $domain)
	{
		$arr = dns_get_record($domain, DNS_MX);
		return $arr;
	}

	public function findEmail($value, $type = 0, $returnType = 0, $isAdmin = 0)
	{
		$value		 = Filter::addQuotes($value);
		$returnset	 = new ReturnSet();
		try
		{
			if (empty($value))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}

			$domain = substr(strrchr($value, "@"), 1);
			if (strpos($domain, "'") !== false)
			{
				$domain = substr($domain, 0, -1);
			}
			if (!ContactEmail::mxrecordValidate($value, $domain))
			{
				$returnset->setErrors("Invalid email format", ReturnSet::ERROR_VALIDATION);
				goto skipAll;
			}

			$sql = " SELECT 
							ctt.ctt_id,
							ctt.ctt_business_name,
							ctt.ctt_first_name,
							ctt.ctt_last_name,
							ctt.ctt_license_no,
							ce.eml_contact_id,
                            ce.eml_is_verified,
							ce.eml_email_address,
							cp.cr_is_vendor,
							cp.cr_is_driver,
							cp.cr_is_consumer,
							cp.cr_is_partner
					FROM    contact_email ce
							INNER JOIN contact ctt ON ctt.ctt_id = ce.eml_contact_id
							INNER JOIN contact_profile cp ON cp.cr_contact_id = ce.eml_contact_id
					WHERE   eml_email_address IN (" . $value . ")
						AND     eml_is_verified = 1
						AND     eml_active = 1
			      ";

			if (!$isAdmin)
			{
				if ($type > 0)
				{
					$sql .= " AND eml_type IN (2,3)";
				}
				else
				{
					$sql .= " AND eml_type IN (1)"; //Default Gozo Email
				}
			}
			$arrEmailDetails = DBUtil::queryAll($sql, DBUtil::SDB());

			/**
			 * Case 1 : If return type 1 and data is there Do Set data, set status = true
			 * Case 2 : If return type null and data is also blank , default status = false
			 */
			if ($returnType)
			{
				$response	 = [];
				$index		 = 0;
				foreach ($arrEmailDetails as $email)
				{
					$response[$index]["ctt_id"]				 = $email["ctt_id"];
					$response[$index]["ctt_business_name"]	 = $email["ctt_business_name"];
					$response[$index]["ctt_first_name"]		 = $email["ctt_first_name"];
					$response[$index]["ctt_last_name"]		 = $email["ctt_last_name"];
					$response[$index]["ctt_license_no"]		 = $email["ctt_license_no"];
					$response[$index]["eml_contact_id"]		 = $email["eml_contact_id"];
					$response[$index]["eml_email_address"]	 = $email["eml_email_address"];
					$response[$index]["eml_is_verified"]	 = $email["eml_is_verified"];

					if (!empty($email["cr_is_vendor"]))
					{
						$vendorModel = Vendors::model()->findByPk($email["cr_is_vendor"]);

						$response[$index]["cr_is_vendor"]	 = $email["cr_is_vendor"];
						$response[$index]["vnd_code"]		 = $vendorModel->vnd_code;
					}

					if (!empty($email["cr_is_driver"]))
					{
						$driverModel = Drivers::model()->findByPk($email["cr_is_driver"]);

						$vendorDriverList					 = VendorDriver::model()->getActiveVendorListbyDriverId($email["cr_is_driver"]);
						$response[$index]["cr_is_driver"]	 = $email["cr_is_driver"];
						$response[$index]["drv_code"]		 = $driverModel->drv_code;
						$response[$index]["mapVendors"]		 = $vendorDriverList;
					}
					if (!empty($email["cr_is_consumer"]))
					{
						$userModel							 = Users::model()->findByPk($email["cr_is_consumer"]);
						$response[$index]["cr_is_consumer"]	 = $email["cr_is_consumer"];
					}
					$index++;
				}

				$returnset->setData($response);
			}

			if (!empty($arrEmailDetails))
			{
				$returnset->setStatus(true);
			}
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		skipAll:
		return $returnset;
	}

	/**
	 * This function is updating the email for social logins
	 * @param type $requestData
	 * @return type
	 */

	/**
	 * This function is used for updating the verified email id
	 * @param type $contactId       - contact unique id
	 * @param type $contactValue	- Email Address/ Phone
	 * @param type $sourceType      - Social Auth *
	 * @param type $isVerify        - 
	 */
	public function updateContacts($contactId, $contactValue, $sourceType, $isVerify = 0)
	{
		$returnSet = new ReturnSet();

		try
		{
			if (empty($contactId) || empty($sourceType))
			{
				throw new Exception("Invalid paramaters", ReturnSet::ERROR_INVALID_DATA);
			}

			if (!is_numeric($sourceType))
			{
				if ($sourceType == "Facebook")
				{
					$sourceType = SocialAuth::Eml_Facebook;
				}

				if ($sourceType == "Google")
				{
					$sourceType = SocialAuth::Eml_Google;
				}
			}

			//Finds all the contact related to a particular email id
			$response = ContactEmail::checkData($contactValue, 1, 0, $sourceType);
			/**
			 * Case 1: If Empty Records, Treat as new entry
			 * Case 2: If Not Empty Records, Perform following
			 * 			1 - Checks for email type and for self id match
			 * 			2 - Inactive all ids which don't match with contact id
			 */
			if (!$response->getStatus())
			{
				/**
				 * validate Primary Email
				 */
				$isPrimary	 = ContactEmail::model()->validatePrimary($contactId);
				$returnSet	 = ContactEmail::addNew($contactId, $contactValue, $sourceType, $isPrimary);
				goto skipToReturn;
			}

			/**
			 * Loops through Ids which are having the email ids
			 */
			$otherIdCount	 = 0;
			$selfIdState	 = 0;
			foreach ($response->getData() as $emailDetail)
			{
				/**
				 * Case 1: If Current contact don't exists, then update others
				 * Case 2: If Current contact exists, then check for platform
				 */
				if ($emailDetail["eml_contact_id"] == $contactId)
				{
					/**
					 * 1 - Added for the platform
					 * 0 - Exists already
					 */
					if ($sourceType > 1)
					{
						$isVerify = 1;
					}
					$isExists = ContactEmail::checkData($contactValue, $contactId, 0, $sourceType);

					if (!$isExists)
					{
						/**
						 * validate Primary Email
						 */
						$isPrimary = ContactEmail::model()->validatePrimary($contactId);

						$returnSet = ContactEmail::addNew($contactId, $contactValue, $sourceType, $isPrimary);
					}

					if ($isVerify)
					{
						$model = ContactEmail::model()->findByConId($contactId);
						foreach ($model as $contactModel)
						{
							$contactModel->eml_is_verified	 = $isVerify;
							$contactModel->eml_verified_date = new CDbExpression('NOW()');
							$contactModel->eml_verify_count++;
							$contactModel->eml_is_expired	 = 1;
							if (!$contactModel->save())
							{
								throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_FAILED);
							}
							$returnSet->setStatus(true);
						}
					}
					$selfIdState++;
				}
				else
				{
					$dbContactId = $emailDetail["eml_contact_id"];
					$updateQuery = " UPDATE contact_email SET eml_is_verified = 0, eml_active = 0 
								WHERE eml_contact_id = $dbContactId 
								AND eml_email_address = '$contactValue' AND eml_type = $sourceType";

					DBUtil::command($updateQuery)->execute();

					$otherIdCount++;
				}
			}


			/**
			 * Make the social entry in case the current contact id is
			 * not a part of the above validation
			 */
			if ($selfIdState == 0)
			{
				/**
				 * validate Primary Email
				 */
				$isPrimary = ContactEmail::model()->validatePrimary($contactId);

				$returnSet = ContactEmail::addNew($contactId, $contactValue, $sourceType, $isPrimary);
			}

			skipToReturn:
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function validateNSaveEmail($contactId, $contactValue)
	{
		$returnSet = new ReturnSet();

		$index			 = 0;
		$count			 = sizeof($contactValue);
		$successCount	 = 0;
		foreach ($contactValue as $emailValue)
		{
			if (!empty($emailValue[$index]->eml_email_address))
			{
				$returnSet = $this->addNew($contactId, $emailValue[$index]->eml_email_address, SocialAuth::Eml_aaocab);
			}

			if ($returnSet->getStatus())
			{
				$successCount++;
			}

			$index++;
		}

		if ($successCount == $count)
		{
			$returnSet->setStatus(true);
		}

		return $returnSet;
	}

	/**
	 * This function is used for email verifications 
	 * @param type $contactId
	 * @param type $contactValue
	 * 
	 * @return type
	 * @throws Exception
	 */
	public function checkEmailId($contactId, $contactValue)
	{
		$returnSet = new ReturnSet();

		try
		{
			if (empty($contactId) || empty($contactValue))
			{
				throw new Exception("Invalid paramaters", ReturnSet::ERROR_INVALID_DATA);
			}

			$returnSet = $this->checkData($contactValue, 1);
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$returnset->setException($ex);
		}

		return $returnSet;
	}

	/**
	 * This function is used for updating the verified email id
	 * @param type $contactId -	 Unique Id		-	Mandatory
	 * @param int   $isVerify	- 1: Verify , 0: not verify	
	 * @param type $type    -	SocialAuth::Eml_*	-	Mandatory
	 * @throws Exception
	 */
	public function updateGozoEmail($contactId, $isVerify, $type)
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($contactId) || empty($type))
			{
				throw new Exception("Invalid paramaters", ReturnSet::ERROR_INVALID_DATA);
			}

			$contactEmailModel = ContactEmail::model()->findByConId($contactId);
			foreach ($contactEmailModel as $model)
			{
				/** @var ContactEmail $model */
				$isExists = $this->checkData($model->eml_email_address, 0, $contactId, $type);

				/**
				 * Case 1: If Email exists for that user, for that Type , then update the state and counter
				 * Case 2: If Email don't exists for that user for that type, then insert new data and unlink
				 * 		   the user from other accounts. 
				 */
				if (!$isExists)
				{
					$returnset = $this->addNew($contactId, $model->eml_email_address, $type);
				}
				else
				{
					$model->eml_is_verified		 = $isVerify;
					$model->eml_verified_date	 = new CDbExpression('NOW()');
					$model->eml_verify_count++;

					if (!$model->save())
					{
						throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_FAILED);
					}
					$returnset->setStatus(true);
				}


				//Unlink other accounts
				$unlinkQuery = "	UPDATE contact_email 
                    SET  eml_active = 0, eml_is_verified = 0 
                	WHERE  eml_email_address = '" . $model->eml_email_address . "' 
                    AND eml_contact_id <> $contactId
			    ";

				$result = DBUtil::command($unlinkQuery)->execute();
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$returnset->setException($ex);
		}
		return $returnset;
	}

	/**
	 * This function is used for adding the new email id for a contact id
	 * 
	 * @param type $contactId		-	Unique Id			-	Mandatory
	 * @param type $value			-	Email Address		-	Mandatory
	 * @param type $type			-	SocialAuth::Eml_*	-	Mandatory
	 * 
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function addNew($contactId, $value, $type = SocialAuth::Eml_aaocab, $emlPrimary = 0, $updateProfile = 1, $isVerified = 0)
	{
		Logger::profile("ContactEmail:addNew Started");
		$returnset = new ReturnSet();

		try
		{
			if (empty($contactId) || empty($type))
			{
				throw new Exception("Invalid paramaters", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty($value))
			{
				goto skipAll;
			}
			$model		 = ContactEmail::model()->findByEmailAndContact(trim(str_replace(' ', '', $value)), $contactId);
			$isverify	 = ($model->eml_is_verified) ? $model->eml_is_verified : 0;
			$isNewRecord = false;
			if (empty($model))
			{
				$isNewRecord				 = true;
				$model						 = new ContactEmail();
				$model->eml_is_primary		 = trim($emlPrimary);
				$model->eml_email_address	 = trim($value);
				$model->eml_contact_id		 = trim($contactId);
				$model->eml_create_date		 = new CDbExpression('now()');
				$model->eml_type			 = trim($type);
			}

			switch ($type)
			{
				case SocialAuth::Eml_Facebook:
				case SocialAuth::Eml_Google:
					$isVerified				 = 1;
					$model->eml_is_verified	 = 1;
					$model->eml_verify_count = 1;
					//	$model->eml_is_expired	 = 1;
					break;
				default:
					$model->eml_is_verified	 = $isverify;
					break;
			}

			$model->eml_active = 1;
			if ($isVerified == 1)
			{
				$model->eml_type			 = trim($type);
				$model->eml_is_verified		 = $isVerified;
				$model->eml_verified_date	 = new CDbExpression('now()');
			}
			$validation = ($updateProfile > 1) ? false : true;
			if (!$model->save($validation))
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$returnset->setStatus(true);
			$returnset->setData($model->eml_id);
			Logger::profile("ContactEmail:addNew Ended");
		}
		catch (Exception $ex)
		{
			$returnset = ReturnSet::setException($ex);
		}
		skipAll:
		return $returnset;
	}

	/**
	 * This function is used for add email for a contact Id
	 * 
	 * @param string	 $emailId		-	Email Address	-	Mandatory
	 * @param int		 $contactId		-	Unique Id		-	Mandatory
	 * @param int		 $addSocial		-	Flag			-	Optional -	This determines whether we need to add the 
	 * 																		social account in contact email table
	 * @return \ReturnSet
	 */
	public function add($emailId, $contactId, $addSocial = 0)
	{
		$returnSet = new ReturnSet();

		try
		{
			/*
			 * Step 1: Find the logged in user social account.
			 * Step 2: If empty enter the gozo email details
			 * 		   Else inactive other users associated with the social account 
			 * 				based on provider identifier and activate the social link
			 * 				for the current contact Id at verified state and also add the
			 * 				Gozo mail at unverified state
			 */

			if ($addSocial > 0)
			{
				$socialUserId	 = UserInfo::getUserId();
				$userDetails	 = Users::fetchUserDetails($socialUserId);

				//For social details - ADD or Update State
				if ($userDetails->getStatus())
				{
					foreach ($userDetails->getData() as $detail)
					{
						$returnSet = ContactEmail::updateContacts($contactId, $detail["emailId"], $detail["provider"], 1);
						if (strtolower($detail["emailId"]) === strtolower($emailId))
						{
							$returnSet->setStatus(true);
							goto skipToAll;
						}
					}
				}
			}

			//GOZO Add
			$emailAddResponse = ContactEmail::addNew($contactId, $emailId, SocialAuth::Eml_aaocab, 0);

			if ($emailAddResponse)
			{
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnSet->setException($ex);
		}
		skipToAll:
		return $returnSet;
	}

	/**
	 * This function returns the model for a specific contact Id
	 *
	 * @param int $contactId - Unique Id	- Mandatory
	 * 
	 * @return static[]
	 */
	public function findByConId($contactId)
	{
		$model = ContactEmail::model()->findAll(array("condition" => "eml_contact_id =$contactId", "order" => "eml_is_primary desc"));
		return $model;
	}

	public function verify($contactId, $emlType)
	{
		$sql = "SELECT COUNT(eml_email_address),eml_type FROM contact_email WHERE eml_contact_id= :id AND eml_type = :emlType GROUP BY eml_email_address HAVING COUNT(eml_email_address)>1";
		$eml = DBUtil::command($sql, DBUtil::SDB())->queryScalar(['id' => $contactId, 'emlType' => $emlType]);
		if ($eml)
		{
			emailWrapper::sendVerificationLink($contactId, $eml->eml_email_address);
		}
	}

	/**
	 * This function is used for saving he model data
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function dataSave($contactId)
	{
		$returnset = new ReturnSet();

		try
		{
			if (empty($contactId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			$this->eml_contact_id = $contactId;
			if (!$this->save())
			{
				throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_FAILED);
			}

			$returnset->setStatus(true);
			$returnset->setData($this->eml_id);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		return $returnset;
	}

	public static function expireLink($email)
	{
		$returnSet = new ReturnSet();
		try
		{
			$contactModel = ContactEmail::model()->findByEmailAddress($email);

			/** @var ContactEmail $contactModel */
			$contactModel->eml_id			 = $contactModel->eml_id;
			$contactModel->eml_is_expired	 = 1;
			if (!$contactModel->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_FAILED);
			}
			$returnSet->setStatus(true);
		}
		catch (Exception $exc)
		{
			Logger::error($ex->getMessage());
			$returnSet->setException($ex);
		}


		return $returnSet;
	}

	/**
	 * 
	 * @param type $contactId
	 * validate email address as primary 
	 * @return int 
	 */
	public function validatePrimary($contactId)
	{
		Logger::profile("ContactEmail:validatePrimary Started");
		/** @var ContactEmail $contactEmailModel */
		$contactEmailModel	 = ContactEmail::model()->findByConId($contactId);
		$isPrimary			 = 1;
		if (empty($contactEmailModel))
		{
			goto skipAll;
		}

		foreach ($contactEmailModel as $conModel)
		{
			if ($conModel->eml_is_primary && $conModel->eml_active)
			{
				$isPrimary = 0;
				break;
			}
		}
		skipAll:
		Logger::profile("ContactEmail:validatePrimary Ended");
		return $isPrimary;
	}

	/**
	 * 
	 * @param string $emailId
	 * @param int $contactId
	 * @return model
	 */
	public function isExists($emailId, $contactId)
	{
		$params = ['emlAd' => $emailId, 'emlCttId' => $contactId];
		return $this->resetScope()->find("eml_email_address=:emlAd AND eml_contact_id=:emlCttId", $params);
	}

	/**
	 * Finds all the contact related to a particular email id
	 * @param string $emailId
	 */
	public static function getData($emailId, $verified = true)
	{
		$cndVerified = "";
		if ($verified)
		{
			$cndVerified = " AND ( eml_is_verified = 1 OR eml_is_primary = 1)";
		}
		$params	 = ['id' => $emailId];
		$sql	 = "SELECT GROUP_CONCAT(con.ctt_id) AS cttIds
				FROM   `contact` con
					   INNER JOIN contact_email ce ON con.ctt_id = ce.eml_contact_id AND ce.eml_email_address<>'' AND ce.eml_email_address IS NOT NULL
				WHERE  eml_email_address=:id  AND eml_active = 1 $cndVerified
					AND con.ctt_active = 1";
		Logger::info($sql . " \n params: " . json_encode($params));
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
	}

	public static function primaryToggle($email, $contactId)
	{
		$emailModel = self::model()->findByEmailAndContact($email, $contactId);
		if ($emailModel != NULL)
		{
			$emailModel->eml_is_primary	 = 1;
			$emailModel->save();
			$sql						 = "UPDATE contact_email SET eml_is_primary=0 WHERE eml_id<>{$emailModel->eml_id} AND eml_contact_id={$contactId}";
			DBUtil::command($sql)->execute();
		}
	}

	public function editContacts($contactId, $contactValue, $sourceType, $isVerify = 0, $checkvalidity = 0)
	{
		$returnSet = new ReturnSet();

		try
		{
			if (empty($contactId) || empty($sourceType))
			{
				throw new Exception("Invalid paramaters", ReturnSet::ERROR_INVALID_DATA);
			}

			if (!is_numeric($sourceType))
			{
				if ($sourceType == "Facebook")
				{
					$sourceType = SocialAuth::Eml_Facebook;
				}

				if ($sourceType == "Google")
				{
					$sourceType = SocialAuth::Eml_Google;
				}
			}

			//Finds all the contact related to a particular email id
			$response = ContactEmail::checkData($contactValue, 1, 0, $sourceType);
			if (!$response->getStatus())
			{
				/**
				 * validate Primary Email
				 */
				$isPrimary	 = ContactEmail::model()->validatePrimary($contactId);
				$returnSet	 = ContactEmail::addNew($contactId, $contactValue, $sourceType, $isPrimary);
				goto skipToReturn;
			}
			/**
			 * Loops through Ids which are having the email ids
			 */
			$selfIdState = 0;
			foreach ($response->getData() as $emailDetail)
			{
				/**
				 * Case 1: If Current contact don't exists, then update others
				 * Case 2: If Current contact exists, then check for platform
				 */
				if ($emailDetail["eml_contact_id"] == $contactId)
				{
					if ($isVerify)
					{
						goto skipData;
					}
					if (!$checkvalidity)
					{
						$isExists = ContactEmail::checkData($contactValue, 1, $contactId, $sourceType);

						if (!$isExists->getStatus())
						{
							/**
							 * validate Primary Email
							 */
							$isPrimary = ContactEmail::model()->validatePrimary($contactId);

							$returnSet = ContactEmail::addNew($contactId, $contactValue, $sourceType, $isPrimary);
						}
					}
					skipData:
					if ($isVerify)
					{
						$model = ContactEmail::model()->findByConId($contactId);
						foreach ($model as $contactModel)
						{
							if ($contactModel->eml_email_address === $contactValue)
							{
								$contactModel->eml_is_verified	 = $isVerify;
								$contactModel->eml_verified_date = new CDbExpression('NOW()');
								$contactModel->eml_verify_count++;
								$contactModel->eml_is_expired	 = 1;
								if (!$contactModel->save())
								{
									throw new Exception(json_encode($contactModel->getErrors()), ReturnSet::ERROR_FAILED);
								}
								$returnSet->setStatus(true);
							}
						}
					}
					$selfIdState++;
				}
				else
				{
					$params		 = ['otherContactId' => $emailDetail["eml_contact_id"], 'emailId' => $contactValue, 'type' => $sourceType];
					$updateQuery = " UPDATE contact_email SET eml_is_verified = 0, eml_active = 0 
								WHERE eml_contact_id =:otherContactId 
								AND eml_email_address =:emailId AND eml_type =:type";

					DBUtil::command($updateQuery, DBUtil::MDB())->execute($params);
				}
			}



			/**
			 * Make the  entry in case the current contact id is
			 * not a part of the above validation
			 */
			if ($selfIdState == 0)
			{
				/**
				 * validate Primary Email
				 */
				$isPrimary = ContactEmail::model()->validatePrimary($contactId);

				$returnSet = ContactEmail::addNew($contactId, $contactValue, $sourceType, $isPrimary);
			}

			skipToReturn:
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$returnSet->setException($ex);
		}
		return $returnSet;
	}

	public static function markVerified($contactId, $email)
	{
		$params = ['contactId' => $contactId, 'emailId' => trim(str_replace(' ', '', $email))];

		$updateQuery = " UPDATE contact_email 
				SET eml_is_verified = 1,eml_verified_date = NOW() 
				WHERE eml_contact_id =:contactId 
				AND eml_email_address =:emailId 				 
				AND eml_active = 1";
		return DBUtil::execute($updateQuery, $params);
	}

	public static function getEmailByBookingUserId($buiId)
	{
		$model	 = BookingUser::model()->findbypk($buiId);
		$cttId	 = $model->bkg_contact_id;
		if ($cttId != '')
		{
			$email = ContactEmail::getPrimaryEmail($cttId);
			if ($email == '')
			{
				$email = $model->bkg_user_email;
			}
		}
		else
		{
			$email = $model->bkg_user_email;
		}
		return $email;
	}

	public static function getById($cttId)
	{
		$params	 = ['id' => $cttId];
		$sql	 = "select eml_email_address FROM contact_email WHERE eml_active=1 AND eml_is_verified=1 AND eml_contact_id= :id ORDER BY eml_email_address ASC LIMIT 1";
		$email	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return $email;
	}

	public static function getEmailByUserId($userId)
	{
		$params	 = ['id' => $userId];
		$sql	 = "select IF(eml_email_address IS NULL OR eml_email_address = '',usr_email,eml_email_address) FROM users
		    LEFT JOIN contact ON ctt_id = usr_contact_id
		    LEFT JOIN contact_email ON eml_contact_id = ctt_id AND eml_active=1 AND eml_is_primary=1
		    WHERE  user_id= :id ORDER BY eml_is_primary DESC, eml_email_address ASC";
		$email	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return $email;
	}

	/** @return string Description */
	public static function getPrimaryEmail($contactId)
	{
		$params	 = ['contact' => $contactId];
		$sql	 = "SELECT IF((eml_is_primary=1 OR eml_is_verified=1 OR usr_email=''), eml_email_address, usr_email) as contactNumber 
					FROM contact 
					INNER JOIN contact_email ON contact.ctt_id = contact_email.eml_contact_id AND contact.ctt_id = contact.ctt_ref_code
					LEFT JOIN contact_profile cp ON cp.cr_contact_id = ctt_id
					LEFT JOIN users ON user_id = cp.cr_is_consumer 
					WHERE eml_active=1  AND eml_contact_id=:contact
					ORDER BY eml_is_primary DESC, eml_is_verified DESC, eml_id ASC
			";

		$email = DBUtil::command($sql, DBUtil::MDB())->queryScalar($params);
		return (!$email) ? '' : $email;
	}

	public static function getByContactId($cttId)
	{
		$params	 = ['id' => $cttId];
		$sql	 = "select eml_email_address FROM contact_email WHERE eml_active=1 AND eml_contact_id= :id ORDER BY eml_email_address ASC LIMIT 1";
		$email	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return $email;
	}

	public static function findById($email)
	{
		if ($email == null || $email == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$param		 = ['email' => $email];
		$sql		 = "UPDATE contact_email SET eml_active = 1 WHERE eml_email_address =:email AND eml_active = 0";
		DBUtil::command($sql, DBUtil::MDB())->execute($param);
		$i			 = 0;
		$sql		 = "
                    SELECT eml_contact_id
					FROM `contact_email`
					WHERE eml_email_address =:email
					AND eml_active = 1  ORDER BY eml_is_primary DESC, eml_is_verified DESC, eml_id ASC
			        ";
		$emailData	 = DBUtil::command($sql, DBUtil::SDB())->query($param);
		foreach ($emailData as $data)
		{
			$i++;
			if ($i == 1)
			{
				$contactId = $data['eml_contact_id'];
				continue;
			}
			else
			{
				$param	 = ['cttId' => $data['eml_contact_id']];
				$sql	 = "UPDATE contact_email SET eml_active = 0 WHERE eml_contact_id =:cttId";
				DBUtil::command($sql, DBUtil::MDB())->execute($param);
			}
		}
		return $contactId;
	}

	public static function verifyItems($email = NULL, $contactId = NULL)
	{
		if ($email == null || $email == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$param		 = ['email' => $email];
		$sql		 = "
                    SELECT eml_contact_id
					FROM `contact_email`
					WHERE eml_email_address =:email
					AND eml_active = 1  ORDER BY eml_create_date DESC
			        ";
		$emailData	 = DBUtil::command($sql, DBUtil::SDB())->query($param);
		foreach ($emailData as $data)
		{
			if ($data['eml_contact_id'] == $contactId)
			{
				$contactEmail					 = ContactEmail::model()->findByEmailAndContact($email, $contactId);
				$contactEmail->eml_is_verified	 = 1;
				$contactEmail->eml_is_expired	 = 1;
				$contactEmail->eml_verified_date = new CDbExpression('NOW()');
				$contactEmail->eml_verify_count++;
				if (!$contactEmail->save())
				{
					throw new Exception(json_encode($contactModel->getErrors()), ReturnSet::ERROR_FAILED);
				}
			}
			else
			{
				$params	 = ['cttId' => $data['eml_contact_id']];
				$sql	 = "UPDATE contact_email SET eml_active = 0 WHERE eml_contact_id =:cttId";
				DBUtil::command($sql, DBUtil::MDB())->execute($params);
			}
		}
	}

	public static function isVerified($contactId)
	{
		$params	 = ['id' => $contactId];
		$sql	 = "SELECT COUNT(eml.eml_id) AS emlIds
				FROM    contact_email eml
				WHERE  eml_is_verified = 1 AND eml_active = 1 AND eml_contact_id =:id";
		$result	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return ($result > 0);
	}

	public static function countDuplicateItems($email, $contactId)
	{
		$params	 = ["email" => $email, "contactId" => $contactId];
		$sql	 = "SELECT COUNT(eml_email_address) AS email
				FROM    contact_email 
				WHERE  eml_contact_id =:contactId AND eml_email_address =:email AND eml_active = 1";
		$result	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return ($result > 0);
	}

	public static function getEmailFromString($strEmail)
	{
		$email		 = false;
		$arrEmails	 = array();
		if (trim($strEmail) != '')
		{
			$arr = explode(',', $strEmail);
			foreach ($arr as $val)
			{
				$value	 = explode('|', $val);
				$key	 = (($value[1] * 5) + ($value[2] * 10)); // Rank

				if (!isset($arrEmails[$key]))
				{
					$arrEmails[$key] = $value[0];
				}
			}

			krsort($arrEmails);
			$email = array_values($arrEmails)[0];
		}

		return $email;
	}

	/**
	 * 
	 * @param integer $id
	 * @param $string $reason
	 * @param boolean $getQuery
	 * @return
	 */
	public static function unlinkContactsById($id, $reason = null, $getQuery = false)
	{
		$params	 = ['contactId' => $id];
		$sql	 = "SELECT  DISTINCT contact_email.eml_contact_id, contact_email.eml_email_address
				FROM `contact_email`
				WHERE contact_email.eml_email_address IN (
					SELECT DISTINCT contact_email.eml_email_address FROM `contact_email` WHERE contact_email.eml_contact_id=:contactId AND contact_email.eml_active=1
				) AND contact_email.eml_active=1";
		$result	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		if ($getQuery)
		{
			return $result;
		}
		foreach ($result as $val)
		{
			$contactId	 = $val['eml_contact_id'];
			$email		 = $val['eml_email_address'];
			self::unlink("", $contactId);

			$desc = "Contact email remove: {$email} , reason : {$reason}";
			ContactLog::model()->createLog($contactId, $desc, ContactLog::CONTACT_EMAIL_REMOVE);
		}
	}

	/**
	 * 
	 * @param string $identifier
	 * @param int $contactId
	 */
	public static function unlink($identifier = "", $contactId)
	{
		if ($identifier != "")
		{
			$profileEmail = SocialAuth::findProfileEmail($identifier);
			if ($profileEmail)
			{
				$contactEmail = ContactEmail::getPrimaryEmail($contactId);
				if ($profileEmail == $contactEmail)
				{
					$sql = "UPDATE contact_email SET eml_active = 0 WHERE eml_contact_id=:cttId AND eml_email_address =:email ";
					DBUtil::execute($sql, ["cttId" => $contactId, "email" => $contactEmail]);
				}
			}
		}
		else
		{
			$deactivateEmail = "UPDATE contact_email SET eml_active = 0 WHERE eml_contact_id=:cttId";
			//DBUtil::execute($sql, ["cttId" => $contactId]);
			return DBUtil::execute($deactivateEmail, ["cttId" => $contactId]);
		}
	}

	/**
	 * 
	 * @param type $contactId
	 * @throws Exception
	 */
//	public static function unlink($contactId)
//	{
//		if (empty($contactId))
//		{
//			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
//		}
//
//		$deactivateEmail = "UPDATE `contact_email` SET eml_active = 0, eml_is_verified = 0 WHERE eml_contact_id  in ($contactId)";
//		return DBUtil::execute($deactivateEmail);
//	}

	/**
	 * Get All matching contacts by email order by matching ranks
	 *
	 * @return array()
	 * */
	public static function getByEmail($email, $phone = '', $firstName = '', $lastName = '', $limit = '')
	{
		$params = ["email" => $email, 'phone' => $phone, 'firstName' => $firstName, 'lastName' => $lastName];

		$sql	 = "SELECT c1.ctt_id, IFNULL(cp1.cr_is_consumer, cp.cr_is_consumer) as userId,cp1.cr_is_driver as driverId,
						MAX(ce.eml_is_primary) as isPrimary, MAX(ce.eml_is_verified) AS isVerified,IFNULL(cp1.cr_is_vendor, cp.cr_is_vendor) as vendorId,
						IF((c1.ctt_name<>'' AND CONCAT(TRIM(:firstName), ' ', TRIM(:lastName))=c1.ctt_name)
						OR (c.ctt_name<>'' AND CONCAT(TRIM(:firstName), ' ', TRIM(:lastName))=c.ctt_name)
						OR (c.ctt_name<>'' AND SOUNDEX(CONCAT(TRIM(:firstName), ' ', TRIM(:lastName)))=SOUNDEX(c.ctt_name))
						OR (c1.ctt_name<>'' AND CONCAT(TRIM(:firstName), ' ', TRIM(:lastName)) LIKE CONCAT('%',c1.ctt_name,'%'))
						OR (c1.ctt_name<>'' AND c1.ctt_name LIKE CONCAT('%',CONCAT(TRIM(:firstName), ' ', TRIM(:lastName)),'%'))
						OR (c.ctt_name<>'' AND CONCAT(TRIM(:firstName), ' ', TRIM(:lastName)) LIKE CONCAT('%',c.ctt_name,'%'))
						OR (c.ctt_name<>'' AND c.ctt_name LIKE CONCAT('%',CONCAT(TRIM(:firstName), ' ', TRIM(:lastName)),'%')),
						1,0) as nameRank,
						CASE
						WHEN MAX(phn_is_primary)=1 AND MAX(phn_is_verified)=1 THEN 4
						WHEN MAX(phn_is_verified)=1 THEN 3
						WHEN MAX(phn_is_primary)=1 THEN 2
						WHEN phn_id IS NOT NULL THEN 1
						ELSE 0
						END as phoneRank,
						CASE
						WHEN MAX(ce.eml_is_primary)=1 AND MAX(ce.eml_is_verified)=1 THEN 4
						WHEN MAX(ce.eml_is_verified)=1 THEN 3
						WHEN MAX(ce.eml_is_primary)=1 THEN 2
						WHEN eml_id IS NOT NULL THEN 1
						ELSE 0
						END as emailRank,
						IF(cp1.cr_is_consumer IS NULL, 0, 1) as profileRank
					FROM contact_email ce
					INNER JOIN contact c ON ce.eml_contact_id=c.ctt_id AND eml_active=1
					LEFT JOIN contact_profile cp ON cp.cr_contact_id=c.ctt_id
					INNER JOIN contact c1 ON c1.ctt_id=c.ctt_ref_code AND c1.ctt_active = 1
					LEFT JOIN contact_profile cp1 ON cp1.cr_contact_id=c1.ctt_id
					LEFT JOIN contact_phone phn ON phn.phn_contact_id=c1.ctt_id AND phn_active=1 AND phn_full_number=:phone
					WHERE ce.eml_email_address=:email  
					GROUP BY c1.ctt_id HAVING (isPrimary + isVerified)>0
					ORDER BY (ce.eml_is_primary + ce.eml_is_verified) DESC, emailRank DESC,nameRank DESC,phoneRank DESC, ce.eml_is_verified DESC, profileRank DESC $limit";
		$data	 = DBUtil::query($sql, DBUtil:: MDB(), $params);
		//Logger::info("ContactEmail::getByEmail ".$sql);
		return $data;
	}

	/**
	 * 
	 * @param type $email
	 * @return boolean
	 */
	public static function getLinkedContactIds($email)
	{
		$email = trim($email);
		if ($email == '')
		{
			return false;
		}
		$sql	 = "SELECT GROUP_CONCAT(DISTINCT eml_contact_id) as contactIds FROM contact_email WHERE eml_active=1 AND eml_email_address=:email";
		$params	 = ["email" => $email];
		$cttIds	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $cttIds;
	}

	/**
	 * 
	 * @param type $email
	 * @param type $cttId
	 * @return \ReturnSet
	 */
	public static function setPrimaryByEmail($email, $cttId)
	{
		$returnset	 = new ReturnSet();
		$sql		 = "SELECT eml_id FROM contact_email WHERE eml_active=1 AND eml_contact_id={$cttId} AND eml_email_address='$email'";
		$emlId		 = DBUtil::command($sql)->queryScalar();
		$model		 = self::model()->findByPk($emlId);
		try
		{
			if ($emlId != NULL)
			{
				$model->eml_is_primary = 1;
				if ($model->save())
				{
					$returnset->setStatus(true);
					$sql = "UPDATE contact_email SET eml_is_primary=0 WHERE eml_id<>$emlId AND eml_contact_id={$cttId}";
					DBUtil::command($sql)->execute();
				}
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnset->setStatus(false);
			$returnSet	 = $returnSet->setException($ex);
			DBUtil::rollbackTransaction($transaction);
			$message	 = $ex->getMessage();
		}
		return $returnset;
	}

	/**
	 * 
	 * @param type $email
	 * @return boolean
	 */
	public static function getLinkedContactCountByEmail($email)
	{
		$email = trim($email);
		if ($email == '')
		{
			return false;
		}
		$sql	 = "SELECT COUNT(eml_contact_id) as count FROM contact_email WHERE eml_active=1 AND eml_email_address=:email";
		$params	 = ["email" => $email];
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $result;
	}

	/**
	 * 
	 * @param type $cttId
	 * @return boolean
	 */
	public function validateEmailById($attribute, $params)
	{
		$returnSet	 = new ReturnSet();
		$cttId		 = $params['cttid'];
		try
		{
			$sql	 = "SELECT COUNT(eml_id) as emlcount, count(eml_is_verified) as emlVerified FROM contact_email WHERE eml_active=1 AND eml_contact_id=:cttid";
			$params	 = ["cttid" => $cttId];
			$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);

			$contactEmailModel = ContactEmail::model()->find('eml_contact_id=:id && eml_email_address=:email', ['id' => $cttId, 'email' => $attribute]);
			if ($result['emlcount'] == 1)
			{
				throw new Exception("You are not authorized to remove this email", ReturnSet::ERROR_FAILED);
			}

			if ($result['emlVerified'] == 1)
			{
				throw new Exception("You are not authorized to remove verified contact", ReturnSet::ERROR_FAILED);
			}

			if ($contactEmailModel->eml_is_primary == 1)
			{
				throw new Exception("You are not authorized to remove primary contact", ReturnSet::ERROR_FAILED);
			}

			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$this->addError($attribute, $ex->getMessage());
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param type $emailModel
	 * @param type $contactEmail
	 * @param type $cttId
	 * @return type
	 * @throws Exception
	 */
	public function addNewByContact($emailModel, $contactEmail, $cttId)
	{
		$returnSet				 = new ReturnSet();
		$emailModel->attributes	 = $contactEmail;
		try
		{
			if (!$emailModel->validate())
			{
				throw new Exception(json_encode($emailModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$value = $emailModel->eml_email_address;

			$existsModel = self::model()->isExists($value, $cttId);
			if ($existsModel)
			{
				throw new Exception("Already exist in your account.", ReturnSet::ERROR_EMAILEXIST);
			}
			// $returnSet = ContactEmail::checkData(0, $value, 1,'',1);
			$returnSet = ContactEmail::checkData($value, 0, 0, 0, 1);
			//  ($value, $returnType = 0, $contactId = 0, $type = 0, $validate = 0)
			{
				if ($returnSet->isSuccess())
				{
					throw new Exception("This email address is already verified with someone else. Please contact Gozo support team to proceed with this contact. ", ReturnSet::ERROR_EMAILEXIST);
				}
			}

			$isPrimary	 = ContactEmail::model()->validatePrimary($cttId);
			$returnSet	 = ContactEmail::model()->addNew($cttId, $value, SocialAuth::Eml_aaocab, $isPrimary);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public static function checkModifyEmails($contactEmails, $contactId)
	{
		$returnSet = new ReturnSet();
		try
		{
			$email = self::getByVerifiedContactId($contactId);
			if ($email)
			{
				throw new Exception("Email address is already verified. Please contact Gozo support team to proceed with this contact. ", ReturnSet::ERROR_EMAILEXIST);
			}
			if (!$email)
			{
				$cEmail = ContactEmail::model()->findByConId($contactId);

				$contactEmail					 = ContactEmail::model()->findByPk($cEmail[0]->eml_id);
				$contactEmail->eml_email_address = $contactEmails;
				$contactEmail->eml_is_verified	 = 0;

				if (!$contactEmail->save())
				{
					throw new Exception(json_encode($contactEmail->getErrors()), ReturnSet::ERROR_FAILED);
				}
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param integer $contactId
	 * @return
	 */
	public static function getContactsByRefCode($contactId)
	{
		$params	 = ['contactId' => $contactId];
		$sql	 = "SELECT
					contact_email.eml_contact_id
				FROM  `contact_email` 
				INNER JOIN `contact` ON contact.ctt_id=contact_email.eml_contact_id AND contact.ctt_active=1 
				WHERE contact.ctt_ref_code=:contactId AND contact.ctt_id<>:contactId AND contact_email.eml_active = 1 
				GROUP BY contact.ctt_id";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

}
