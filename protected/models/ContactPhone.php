<?php

/**
 * This is the model class for table "contact_phone".
 *
 * The followings are the available columns in table 'contact_phone':
 * @property integer $phn_id
 * @property integer $phn_contact_id
 * @property string $phn_phone_no
 * @property string $phn_phone_country_code
 * @property integer $phn_is_verified
 * @property integer $phn_is_primary
 * @property integer $phn_type 
 * @property integer $phn_active
 * @property integer $phn_is_expired
 * @property string $phn_verified_date
 * @property string $phn_create_date
 * @property string $phn_otp
 * @property string $phn_otp_last_sent_date
 * @property integer $phn_whatsapp_verified 
 * The followings are the available model relations:
 * @property Contact $phnContact
 */
class ContactPhone extends BaseActiveRecord
{

	public $altPhoneNo, $mediumType, $isNew, $fullContactNumber;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact_phone';
	}

	public function defaultScope()
	{
		$ta	 = $this->getTableAlias(false, false);
		$arr = array(
			'condition'	 => "phn_active > 0",
			'order'		 => "phn_is_primary desc"
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
//array('phn_phone_no', "required", 'on' => 'insert,update,insertApp,vendorjoin'),
			array('phn_phone_no', "required", 'on' => 'insert,update,insertApp'),
			array('phn_full_number', "validatePhone"),
			array('phn_contact_id, phn_is_verified, phn_is_primary, phn_active,phn_phone_no', 'numerical', 'integerOnly' => true, 'on' => 'unregVendorJoin,vendorjoin'),
			array('phn_phone_no', 'numerical', 'integerOnly' => true, 'on' => 'insert,update,insertApp,vendorjoin'),
			array('phn_phone_no', 'validatePhoneV1', 'on' => 'validateFullNumber'),
			array('phn_phone_no', 'length', 'max' => 15, 'on' => 'insert,update,insertApp,vendorjoin', 'message' => 'Phone maximum length should be 15 character',),
			array('phn_phone_no', 'length', 'min' => 7, 'on' => 'insert,update,insertApp,vendorjoin', 'message' => 'Phone minimum length should be 7 character',),
			array('phn_phone_country_code', 'length', 'max' => 5),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('phn_id, phn_contact_id, phn_phone_no, phn_phone_country_code, phn_is_verified, phn_is_primary, phn_active,phn_is_expired, phn_verified_date, phn_create_date,phn_whatsapp_verified', 'safe', 'on' => 'search'),
			array('phn_otp, phn_contact_id, phn_phone_country_code, phn_phone_no, phn_is_verified, phn_is_primary, phn_active, phn_verified_date, phn_otp_last_sent_date,phn_is_expired,phn_whatsapp_verified', 'safe'),
			array('phn_full_number', 'unsafe'),
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
			'phnContact' => array(self::BELONGS_TO, 'Contact', 'phn_contact_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'phn_id'				 => 'Phn',
			'phn_contact_id'		 => 'Phn Contact',
			'phn_phone_no'			 => 'Phone',
			'phn_phone_country_code' => 'Phn Phone Country Code',
			'phn_is_verified'		 => 'Phn Is Verified',
			'phn_whatsapp_verified'	 => 'Phn Whatsapp Verified',
			'phn_is_primary'		 => 'Phn Is Primary',
			'phn_type'				 => "Phn Type",
			'phn_active'			 => 'Phn Active',
			'phn_is_expired'		 => 'Phn Is Expired',
			'phn_verified_date'		 => 'Phn Verified Date',
			'phn_create_date'		 => 'Phn Create Date',
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

		$criteria->compare('phn_id', $this->phn_id);
		$criteria->compare('phn_contact_id', $this->phn_contact_id);
		$criteria->compare('phn_phone_no', $this->phn_phone_no, true);
		$criteria->compare('phn_phone_country_code', $this->phn_phone_country_code, true);
		$criteria->compare('phn_is_verified', $this->phn_is_verified);
		$criteria->compare('phn_whatsapp_verified', $this->phn_whatsapp_verified);
		$criteria->compare('phn_is_primary', $this->phn_is_primary);
		$criteria->compare('phn_type', $this->phn_type);
		$criteria->compare('phn_active', $this->phn_active);
		$criteria->compare('phn_is_expired', $this->phn_is_expired);
		$criteria->compare('phn_verified_date', $this->phn_verified_date, true);
		$criteria->compare('phn_create_date', $this->phn_create_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContactPhone the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function checkDuplicatePhone($attribute, $params)
	{
		if($this->phn_phone_no != NULL)
		{
			$check = 0;
			if($this->phn_contact_id != "")
			{
				$check = $this->findByPhone(trim($this->phn_phone_no), $this->phn_contact_id);
			}
			else
			{
				$check = $this->findByPhone(trim($this->phn_phone_no));
			}
			if($check > 0)
			{
				$this->addError($attribute, "This phone number  already exists");
				return false;
			}
		}
		return true;
	}

	public function findByPhone($phone, $phn_contact_id = 0)
	{
		$sql = "";
		if($phn_contact_id != 0)
		{
			$sql = "SELECT COUNT('contact_phone.phn_contact_id') as cnt FROM `contact_phone` WHERE phn_active = 1 AND phn_phone_no='$phone' and phn_contact_id!=$phn_contact_id";
		}
		else
		{
			$sql = "SELECT COUNT('contact_phone.phn_contact_id') as cnt FROM `contact_phone` WHERE phn_active = 1 AND phn_phone_no='$phone'";
		}
		$cnt = DBUtil::command($sql)->queryScalar();
		return $cnt;
	}

	public function checkDuplicatePhoneByDriver($attribute, $params)
	{
		if($this->phn_phone_no != NULL)
		{
			$check = 0;
			if($this->phn_contact_id != "")
			{
				$check = $this->findByPhoneDriver(trim($this->phn_phone_no), $this->phn_contact_id);
			}
			else
			{
				$check = $this->findByPhoneDriver(trim($this->phn_phone_no));
			}
			if($check > 0)
			{
				$this->addError($attribute, "1");
				return false;
			}
		}
		return true;
	}

	public function findByPhoneDriver($phone, $phn_contact_id = 0)
	{
		$sql = "";
		if($phn_contact_id != 0)
		{
			$sql = "SELECT COUNT('contact_phone.phn_contact_id') as cnt FROM `contact_phone` INNER JOIN drivers ON drivers.drv_contact_id = contact_phone.phn_contact_id WHERE phn_active = 1 AND phn_phone_no='$phone' and phn_contact_id!=$phn_contact_id";
		}
		else
		{
			$sql = "SELECT COUNT('contact_phone.phn_contact_id') as cnt FROM `contact_phone` INNER JOIN drivers ON drivers.drv_contact_id = contact_phone.phn_contact_id WHERE phn_active = 1 AND phn_phone_no='$phone'";
		}
		$cnt = DBUtil::command($sql)->queryScalar();
		return $cnt;
	}

	public function validatePhone($attribute, $params)
	{
		if(trim($this->phn_phone_no) != NULL)
		{
			$this->phn_phone_country_code	 = ($this->phn_phone_country_code != '') ? $this->phn_phone_country_code : '91';
			$phone							 = "+" . $this->phn_phone_country_code . trim($this->phn_phone_no);
			if(is_numeric(trim($phone)) == false)
			{
				$this->addError($attribute, 'Phone number must be numeric value');
				return false;
			}
			$phonenumber = new libphonenumber\LibPhone($phone);
			$a			 = $phonenumber->toE164();
			$a			 = $phonenumber->toInternational();
			$a			 = $phonenumber->toNational();
			if(!$phonenumber->validate())
			{
				$this->addError($attribute, 'Please enter valid phone number');
				return false;
			}
		}
		else
		{
			$this->addError($attribute, 'Please enter valid phone number');
			return false;
		}
		return true;
	}

	public function validatePhoneV1($attribute, $params)
	{

		if(trim($this->phn_phone_no) != NULL)
		{
			$this->phn_phone_country_code	 = ($this->phn_phone_country_code != '') ? $this->phn_phone_country_code : '91';
			$phone							 = "+" . $this->phn_phone_country_code . trim($this->phn_phone_no);
			$isValidPhone					 = Filter::validatePhoneNumber($phone);
			if(!$isValidPhone)
			{
				$this->addError($attribute, 'Please enter valid phone number');
				return false;
			}
		}
		else
		{
			$this->addError($attribute, 'Please enter valid phone number');
			return false;
		}
		return true;
	}

	public function validatePhoneEmail($attribute, $params)
	{
		$scenario		 = $this->scenario;
		$checkContact	 = self::model()->findByContact($attribute);
		if($checkContact)
		{
			$this->addError($attribute, 'Phone already exists');
			return false;
		}

		return true;
	}

	/**
	 * @deprecated since  2022
	 * not used function
	 */
	public function checkDuplicatePhoneByVendor($attribute, $params)
	{
		if($this->phn_phone_no != NULL)
		{
			$sql = "";
			if($this->phn_contact_id > 0)
			{
				$sql = "SELECT COUNT('contact_phone.phn_phone_no') as cnt FROM `vendors` INNER JOIN `contact` ON contact.ctt_id=vendors.vnd_contact_id AND contact.ctt_active=1 INNER JOIN `contact_phone` ON contact_phone.phn_contact_id=contact.ctt_id AND contact_phone.phn_active=1 WHERE contact_phone.phn_phone_no='$this->phn_phone_no' and contact_phone.phn_contact_id!=" . $this->phn_contact_id;
			}
			else
			{
				$sql = "SELECT COUNT('contact_phone.phn_phone_no') as cnt FROM `vendors` INNER JOIN `contact` ON contact.ctt_id=vendors.vnd_contact_id AND contact.ctt_active=1 INNER JOIN `contact_phone` ON contact_phone.phn_contact_id=contact.ctt_id AND contact_phone.phn_active=1 WHERE contact_phone.phn_phone_no='$this->phn_phone_no'";
			}
			$cntphone = DBUtil::command($sql)->queryScalar();
			if($cntphone > 0)
			{
				$this->addError($attribute, "This Phone number  already registered as vendor");
				return false;
			}
		}
		return true;
	}

	public function findByContact($phone)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('phn_phone_no', $phone);
		$criteria->compare("phn_active", ">0");
		return $this->find($criteria);
	}

	public function fetchList()
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = ["*"];
		$criteria->together	 = true;
		$sort				 = new CSort;
		$sort->defaultOrder	 = 'phn_create_date DESC';

		$dataProvider = new CActiveDataProvider($this, array('criteria'	 => $criteria,
			'sort'		 => $sort,
			'pagination' => ['pageSize' => 50]));
		return $dataProvider;
	}

	/**
	 * This function gets the model of the all records based on contact Id
	 * @param type $cttId
	 * @return type
	 */
	public function findContactPhone($cttId)
	{
		$sql	 = "SELECT * FROM contact_phone WHERE phn_contact_id = :cttId GROUP By phn_phone_no";
		$model	 = ContactPhone::model()->findAllBySql($sql,
				[
					":cttId" => $cttId,
		]);

		return $model;
	}

	public function findByContactID($cttId)
	{
		$model = ContactPhone::model()->findAll(array("condition" => "phn_contact_id =$cttId AND phn_active = 1", "order" => "phn_is_primary DESC, phn_is_verified DESC"));
		return $model;
	}

	public function findPhoneIdByPhoneNumber($phn_phone_no)
	{
		$model = ContactPhone::model()->find('phn_phone_no=:phn_phone_no_id AND phn_active=1', ['phn_phone_no_id' => $phn_phone_no]);
		return $model;
	}

	public function findPhoneIdByPhoneNumberInActive($phn_phone_no)
	{
		$model = ContactPhone::model()->find('phn_phone_no=:phn_phone_no_id AND phn_active=0', ['phn_phone_no_id' => $phn_phone_no]);
		return $model;
	}

	public function getAlternateContactById($vndId)
	{
		$arrTotal = $this->findBySql("Select phn_phone_no as altPhoneNo
					From vendors
					JOIN contact_profile cp on cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status=1
					JOIN contact AS ctt ON ctt.ctt_id = cp.cr_contact_id and ctt.ctt_id =ctt.ctt_ref_code AND ctt.ctt_active =1
					INNER JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id
					where vendors.vnd_id in (
					select v3.vnd_id
					FROM
					vendors v1
					INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
					INNER JOIN vendors v3 ON v2.vnd_ref_code = v3.vnd_id
					WHERE v1.vnd_id = $vndId) and vnd_id=vnd_ref_code
					AND phn.phn_is_primary != 1
					ORDER BY phn.phn_create_date DESC LIMIT 1");
		return $arrTotal;
	}

	public function getAlternateContactByDriverId($drvId)
	{
		$arrTotal = $this->findBySql("Select phn_phone_no as altPhoneNo
					From drivers
					JOIN contact_profile as cp ON cp.cr_is_driver = drivers.drv_id AND cp.cr_status = 1
					JOIN contact as ctt ON ctt.ctt_id = cp.cr_contact_id AND ctt.ctt_id = ctt.ctt_ref_code AND ctt.ctt_active = 1
					INNER JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id
					where drv_id IN
					(SELECT d3.drv_id
					FROM drivers d1
					INNER JOIN drivers d2 ON d2.drv_id = d1.drv_ref_code
					INNER JOIN drivers d3 ON d3.drv_ref_code = d2.drv_id
					WHERE d1.drv_id = $drvId) and drivers.drv_id=drivers.drv_ref_code AND phn.phn_is_primary != 1
					ORDER BY phn.phn_create_date DESC LIMIT 1");
		return $arrTotal;
	}

	public function getContactNoById($vndId)
	{
		$sql = "Select vnd_id, vnd_name, GROUP_CONCAT(phn_phone_no) as phone_no from vendors
				JOIN contact_profile cp on cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status=1
				JOIN contact AS ctt ON ctt.ctt_id = cp.cr_contact_id and ctt.ctt_id =ctt.ctt_ref_code AND ctt.ctt_active =1
				JOIN contact_phone ON ctt.ctt_id = contact_phone.phn_contact_id
				where vendors.vnd_id in (
				select v3.vnd_id
				FROM
				vendors v1
				INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
				INNER JOIN vendors v3 ON v2.vnd_ref_code = v3.vnd_id
				WHERE v1.vnd_id = $vndId) and vnd_id=vnd_ref_code Order by contact_phone.phn_is_primary ASC";
		return DBUtil::queryRow($sql);
	}

	public function checkPhoneLength($attribute, $params)
	{

		if(strlen(trim($this->phn_phone_no)) > 15)
		{
			$this->addError($attribute, 'Phone number length must be 10 to 15 character');
			return false;
		}
		return true;
	}

	public function checkPhoneNumerical($attribute, $params)
	{
		if($this->phn_phone_no == NULL && $this->phn_phone_no == '')
		{
			$this->addError($attribute, 'Phone number must be numeric value');
			return false;
		}
		if(is_numeric(trim($this->phn_phone_no)) == false)
		{
			$this->addError($attribute, 'Phone number must be numeric value');
			return false;
		}
		return true;
	}

	public function removePhone($arrayphone, $cttid)
	{
		$sql = "Update `contact_phone` set phn_is_primary=0,  phn_active=0 WHERE  phn_phone_no not in ($arrayphone) and phn_contact_id=$cttid";
		$cnt = DBUtil::command($sql)->execute();
		return $cnt;
	}

	public function removePhoneByPhone($phone, $cttid)
	{
		$params	 = ['phone' => $phone, 'contact' => $cttid];
		$sql	 = "Update `contact_phone` set phn_is_primary=0,  phn_active=0 WHERE  phn_phone_no=:phone and phn_contact_id=:contact";
		$cnt	 = DBUtil::execute($sql, $params);
		return $cnt;
	}

	public function updatePhoneByContactId($phone, $cttid)
	{
		$sql = "Update `contact_phone` set phn_phone_no='" . $phone . "' WHERE  phn_is_primary=1 AND phn_active=1 AND phn_contact_id='" . $cttid . "'";
		return DBUtil::command($sql)->execute();
	}

	public function CheckPhoneRequired($attribute, $params)
	{

		if($this->phn_phone_no == NULL || $this->phn_phone_no == '')
		{
			$this->addError($attribute, 'Please provide your phone number');
			return false;
		}
		return true;
	}

	public function getContactPhoneById($Id = 0)
	{
		if($Id > 0)
		{
			$Phone	 = "select phn_phone_no From contact_phone where phn_is_primary = 1 AND phn_active=1 AND phn_contact_id =" . $Id;
			$sql	 = DBUtil::command($Phone)->queryScalar();
		}
		return $sql;
	}

	public function getContactPhoneCodeById($Id = 0)
	{
		if($Id > 0)
		{
			$Phone	 = "select phn_phone_country_code  From contact_phone where phn_is_primary = 1 AND phn_active=1 AND phn_contact_id =" . $Id;
			$sql	 = DBUtil::command($Phone)->queryScalar();
		}
		return $sql;
	}

	public function checkDuplicateUserByVendor($attribute, $params)
	{
		$phone	 = $this->phn_phone_no;
//$email = $this->eml_email_address;
		$usersId = Users::model()->linkUserid($email, $phone);
		$vndId	 = Vendors::getVendorIdByUserId($usersId);
		if($vndId != "")
		{
			$this->addError($attribute, "This Phone Number already taken by another vendor");
			return false;
		}
		return true;
	}

	public function findContact($contactId)
	{
		$number	 = Contact::model()->getContactDetails($contactId);
		$contact = str_replace('-', '', $number['phn_phone_no']);
		if(strlen($contact) > 10)
		{
			return $contact;
		}
		else if($number['phn_phone_country_code'] != '')
		{
			return $number['phn_phone_country_code'] . $contact;
		}
		else
		{
			return '91' . $contact;
		}
	}

	public function findByPhoneAndContact($phone, $contactId)
	{
		Filter::parsePhoneNumber($phone, $code, $number);
		return $this->find("phn_phone_country_code=:code AND phn_phone_no=:phoneId AND phn_contact_id=:contactId AND phn_active=1", ['code' => $code, 'phoneId' => $number, 'contactId' => $contactId]);
	}

	public static function getObject($phone, $contactId, $isPrimary = NULL, $createNew = true, $type = NULL, $isNew = NULL)
	{
		$model = ContactPhone::model()->findByPhoneAndContact($phone, $contactId);
		if(!$model && $createNew)
		{
			Filter::parsePhoneNumber($phone, $code, $number);

			$model							 = new ContactPhone();
			$model->phn_contact_id			 = $contactId;
			$model->phn_phone_country_code	 = $code;
			$model->phn_phone_no			 = $number;
			$model->phn_type				 = $type;

			if($isNew == 0 || $isNew == 1)
			{
				$model->isNew = $isNew;
			}
		}
		$model->phn_is_primary = $isPrimary == NULL ? 0 : $isPrimary;
		if($phone == null)
		{
			return false;
		}
		return $model;
	}

	public function remove($model = null)
	{
		if($model == NULL)
		{
			$model = $this;
		}
		$model->phn_active		 = 0;
		$model->phn_is_primary	 = 0;
		$model->update();
		$desc					 = "Contact phone remove: {$model->phn_phone_country_code}{$model->phn_phone_no}";
		$event					 = ContactLog::CONTACT_PHONE_REMOVE;
		ContactLog::model()->createLog($model->phn_contact_id, $desc, $event, null);
	}

	/**
	 * This function is used for adding new and updating old phone number details
	 * @param type (int) $phoneModel
	 * @param type (int) $cttId 
	 */
	public static function savePhones($phoneModel, $cttId, $verify = false, $userType = UserInfo::TYPE_CONSUMER)
	{
		$returnSet	 = new ReturnSet();
		$oldModels	 = ContactPhone::model()->findByContactID($cttId);
		$phoneArray	 = [];
		$transaction = DBUtil::beginTransaction();
		try
		{
			foreach($oldModels as $model)
			{
				array_push($phoneArray, trim($model->phn_phone_no));
			}
			/** @var contactPhone $newModel */
			// validate and compare the phone number record for new and old model data 
			foreach($phoneModel as $newModel)
			{
				$oldModel = self::model()->isExists($newModel->phn_phone_no, $cttId);
				//Compare the newmodel and oldmodel for phone number and update the matched record as well as check for status zero 
				// if found then update the status for the same to one 
				if(trim($newModel->phn_phone_no) === trim($oldModel->phn_phone_no) && trim($oldModel->phn_phone_no) != null && $oldModel->phn_active == 0)
				{
					$status = ContactPhone::countDuplicateItems($newModel->phn_phone_no, $cttId);
					if(!$status)
					{
						$verifyCode		 = rand(10000, 99999);
						$params			 = array('phn_contact_id' => $cttId, 'phn_otp' => $verifyCode, 'phn_phone_no' => $oldModel->phn_phone_no, 'phn_id' => $oldModel->phn_id);
						//Update only record with that number for the contactId
						$sql			 = "UPDATE contact_phone 
											SET    phn_active = 1,  
												   phn_is_verified = 0,
												   phn_otp = :phn_otp 
											WHERE  phn_contact_id = :phn_contact_id 
												   AND phn_phone_no = :phn_phone_no AND phn_id =:phn_id";
						DBUtil::execute($sql, $params);
						$newPhoneData	 = ['otp' => $verifyCode, 'number' => $oldModel->phn_phone_no, 'ext' => $oldModel->phn_phone_country_code, 'isNew' => 0];
						$returnSet->setData($newPhoneData, false);
						$returnSet->setStatus(true);
					}
					continue;
				}
				// checking for matched part and status one(active record)
				else if(trim($newModel->phn_phone_no) === trim($oldModel->phn_phone_no) && $oldModel->phn_active == 1)
				{
					if($newModel->phn_is_primary != $oldModel->phn_is_primary && $oldModel->phn_active == 1)
					{
						$returnSet = ContactPhone::model()->add($cttId, $newModel->phn_phone_no, 0, $newModel->phn_phone_country_code, SocialAuth::Eml_aaocab, $newModel->phn_is_primary);
					}
					continue;
				}
				//  checking the newmodel phone existence in oldmodel data and if not add the record
				if(!(in_array(trim($newModel->phn_phone_no), $phoneArray)))
				{
					$returnSet = ContactPhone::model()->add($cttId, $newModel->phn_phone_no, 0, $newModel->phn_phone_country_code, SocialAuth::Eml_aaocab, $newModel->phn_is_primary, 0, $newModel->phn_is_verified);
					break;
				}
			}
			DBUtil::commitTransaction($transaction);
		}
		catch(Exception $ex)
		{
			Logger::exception($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function isExists($phoneNo, $contactId)
	{
		$params = ['phnNo' => $phoneNo, 'phnCttId' => $contactId];
		return $this->resetScope()->find("phn_phone_no=:phnNo AND phn_contact_id=:phnCttId", $params);
	}

	public static function setPrimaryPhone($cttId)
	{
		$param	 = ['contactId' => $cttId];
		$sql	 = "SELECT phn_id FROM contact_phone WHERE phn_active=1 AND phn_contact_id=:contactId ORDER BY phn_is_primary DESC, phn_is_verified DESC, phn_create_date ASC LIMIT 1";
		$phn_id	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar($param);
		$params	 = ['contactId' => $cttId, 'phnId' => $phn_id];
		$model	 = self::model()->findByPk($phn_id);
		if($phn_id != NULL)
		{
			$model->phn_is_primary	 = 1;
			$model->save();
			$sql					 = "UPDATE contact_phone SET phn_is_primary=0 WHERE phn_id<>:phnId AND phn_contact_id=:contactId";
			DBUtil::command($sql, DBUtil::MDB())->execute($params);
		}
	}

	public function countContactPhone($phone)
	{
		$params	 = ['phone' => $phone];
		$sql	 = "SELECT COUNT(1) as cnt FROM `contact_phone` WHERE phn_active=1 AND phn_phone_no=:phone";
		$cnt	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar($params);
		return $cnt;
	}

	/**
	 * This function is updating the phone 
	 * @param type $requestData
	 * @return type
	 */
	public function updateContacts($contactId, $contactValue, $userType = NULL, $sourceType = NULL)
	{
		$returnSet = new ReturnSet();
		try
		{
			if(empty($contactId))
			{
				throw new Exception("Invalid paramaters", ReturnSet::ERROR_INVALID_DATA);
			}

			Filter::parsePhoneNumber($contactValue, $code, $number);
//Finds all the contact related to a particular phone id
			$response = ContactPhone::checkData(0, $contactValue, 1, $sourceType);

			/**
			 * Case 1: If Empty Records, Treat as new entry
			 * Case 2: If Not Empty Records, Perform following
			 * 			1 - Checks for phone type and for self id match
			 * 			2 - Inactive all ids which don't match with contact id
			 */
			if(!$response->getStatus())
			{
				$returnSet = $this->add($contactId, $number, $userType, $code, $sourceType, 1);
				goto skipToReturn;
			}

			/**
			 * Loops through Ids which are having the phone ids
			 */
			$selfIdState = 0;
			foreach($response->getData() as $phDetail)
			{
				/**
				 * Case 1: If Current contact don't exists, then update others
				 * Case 2: If Current contact exists, then check for platform
				 */
				if($phDetail["phn_contact_id"] == $contactId)
				{
					$isExists = ContactPhone::checkData($contactId, $contactValue, 1, $sourceType);
					if(!$isExists->getStatus())
					{
						$returnSet = ContactPhone::add($contactId, $number, $userType, $code, $sourceType);
					}
					$selfIdState++;
				}
				else
				{
					$params		 = ['otherContactId' => $phDetail["phn_contact_id"], 'number' => $number];
					$updateQuery = " UPDATE contact_phone SET phn_is_verified = 0, phn_active = 0
								WHERE phn_contact_id =:otherContactId 
								AND phn_phone_no =:number";

					DBUtil::command($updateQuery, DBUtil::MDB())->execute($params);
				}
			}


			/**
			 * Make the social entry in case the current contact id is
			 * not a part of the above validation
			 */
			if($selfIdState == 0)
			{

				ContactPhone::add($contactId, $contactValue, $userType, 0, $sourceType);
			}
			skipToReturn:
		}
		catch(Exception $ex)
		{
			Logger::exception($ex);
			$returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * @deprecated 
	 * @param type $contactId - Contact id
	 * @param type $contactValue - Contact Value
	 * @param type $type - 
	 */
	public function validateData($contactId, $contactValue)
	{
		$returnSet = new ReturnSet();

//Finds all the contact related to a particular Phone number
		$response = $this->checkData($contactValue, 1);

		/**
		 * Case 1: If Empty Records, Treat as new entry
		 * Case 2: If Not Empty Records, Perform following
		 * 			1 - Checks for Phone number type and for self id match
		 * 			2 - Inactive all ids which don't match with contact id
		 */
		if($response->getStatus())
		{
			$response = $this->add($contactId, $ext, $contactValue);

			if($response)
			{
				$response["success"] = true;
				$response["message"] = "Phone added to contact Id";
			}
			else
			{
				$response["success"] = false;
				$response["message"] = "Failed to add phone Number";
			}

			skipToReturn;
		}
	}

	/**
	 * 
	 * @param type $value Phone Number
	 * @param type $returnType - 1: Array , 0: boolean		-	Optional
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function checkData($contactId = 0, $value, $returnType = 0, $sourceType = '', $validate = 0)
	{
		$returnSet = new ReturnSet();
		try
		{
			if(empty($value))
			{
				throw new Exception("Invalid paramaters", ReturnSet::ERROR_INVALID_DATA);
			}
			Filter::parsePhoneNumber(trim(str_replace(' ', '', $value)), $code, $number);
			$param			 = ['phnNumber' => $number];
//Finds all the contact Id related to a particular Contact number
			$findContactIds	 = "SELECT phn_id, phn_contact_id
                              FROM contact_phone
                              WHERE phn_phone_no = :phnNumber AND phn_active = 1";

//phone Type check
			if($type > 0)
			{
				$findContactIds .= " AND phn_type = $type";
			}
			if($code > 0)
			{
				$findContactIds .= " AND phn_phone_country_code = $code";
			}

//Particular contact Id check
			if($contactId > 0)
			{
				$findContactIds .= " AND phn_contact_id = $contactId";
			}
			if($validate > 0)
			{
				$findContactIds .= " AND phn_is_verified = 1";
			}
			$arrContactDetails = DBUtil::queryAll($findContactIds, DBUtil::SDB(), $param);

			/**
			 * Case 1 : If return type 1 and data is there Do Set data, set status = true
			 * Case 2 : If return type null and data is also blank , default status = false
			 */
			if($returnType)
			{
				$returnSet->setData($arrContactDetails);
			}

			if(!empty($arrContactDetails))
			{
				$returnSet->setStatus(true);
			}
		}
		catch(Exception $ex)
		{
			Logger::exception($ex);
			//$returnSet = $returnSet->setException($e);
			$returnSet->setException($ex);
		}

		return $returnSet;
	}

	/**
	 * This function is used for updating the phone numbers
	 * @param type $contactId		-	Unqiue key							-	Mandatory
	 * @param type $value			-	Phone number						-	Mandatory
	 * @param int  $userType		-	UserInfo::TYPE_*					-	Optional
	 * @param type $ext				-	Country code						-	Optional
	 * @param type $type			-	SocialAuth::Eml*					-	Optional
	 * @param int  $updateProfile	-	Flag for updating contactprofile	-	Optional
	 * 
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function add($contactId, $value, $userType = 0, $ext, $type = null, $primaryPhone = 0, $updateProfile = 0, $isVerified = 0)
	{
		Logger::trace("contatId " . $contactId . " number " . $value . " userType " . $userType . " primaryPhone " . $primaryPhone . " updateProfile " . $updateProfile . "isVerified " . $isVerified);
		$returnset = new ReturnSet();
		try
		{
			if(empty($contactId))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}
			if(empty($value))
			{
				goto skipAll;
			}
			$model		 = ContactPhone::model()->findByPhoneAndContact(trim(str_replace(' ', '', $value)), $contactId);
			$isNewRecord = false;
			if(empty($model))
			{
				$isNewRecord					 = true;
				$model							 = new ContactPhone();
				$model->phn_phone_country_code	 = empty($ext) ? '91' : $ext;
				$model->phn_phone_no			 = ltrim(str_replace(' ', '', $value), 0);
				$model->phn_is_primary			 = $primaryPhone;
				$model->phn_type				 = empty($type) ? SocialAuth::Eml_aaocab : $type;
				$model->phn_create_date			 = new CDbExpression('now()');
				$model->phn_contact_id			 = $contactId;
				$model->phn_otp					 = rand(1000, 9999);
				$model->phn_is_verified			 = 0;
			}

			$model->phn_active = 1;
			if($isVerified)
			{
				$model->phn_is_verified		 = $isVerified;
				$model->phn_verified_date	 = new CDbExpression('now()');
			}
			$model->scenario = "validateFullNumber";
			if(!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$newPhoneData = ['otp' => $model->phn_otp, 'number' => $model->phn_phone_no, 'ext' => $model->phn_phone_country_code, 'isNew' => $isNewRecord];
			$returnset->setData($newPhoneData, false);
			$returnset->setStatus(true);
			$returnset->setMessage("Profile created");
			if($updateProfile)
			{
				$returnset->setMessage("Profile updated");
			}
			Logger::profile("ContactPhone:add Ended");
		}
		catch(Exception $e)
		{
			$returnset = ReturnSet::setException($e);
		}
		skipAll:
		return $returnset;
	}

	/**
	 * This function is used for updating the status for phone
	 * @param type (int) $id
	 * @return boolean
	 */
	public function updateVerifyStatus($id, $modifyPhone = null)
	{
		$returnset	 = new ReturnSet();
		$returnset->setStatus(false);
		$phoneNo	 = [];
		$transaction = DBUtil::beginTransaction();
		try
		{

			if($id > 0)
			{
				$cond = ($modifyPhone != null) ? ('AND phn_phone_no = ' . $modifyPhone) : "";

				$updateQuery = " UPDATE contact_phone 
                                        SET    phn_is_primary = 1, phn_is_verified = 1, 
                                               phn_verified_date = Now(), 
                                               phn_verify_count = phn_verify_count + 1 
                                        WHERE  phn_contact_id = $id $cond ";
				$sql		 = DBUtil::command($updateQuery)->execute();
				//Unlink other accounts
				$unlinkQuery = "UPDATE contact_phone SET phn_is_verified = 0, phn_active = 0, phn_is_expired = 0 WHERE phn_contact_id <> $id AND phn_phone_no = '$modifyPhone'";

				$result = DBUtil::command($unlinkQuery)->execute();
				$returnset->setStatus(true);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch(Exception $ex)
		{

			$returnset->setStatus(false);
			$returnSet	 = $returnSet->setException($e);
			DBUtil::rollbackTransaction($transaction);
			$message	 = $ex->getMessage();
		}
		return $returnset;
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
		$model = ContactPhone::model()->findAll(array("condition" => "phn_contact_id =$contactId AND phn_active = 1", "order" => "phn_is_primary desc"));
		return $model;
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
			if(empty($contactId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			$this->phn_contact_id = $contactId;

			if(!$this->save())
			{
				throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_FAILED);
			}

			$returnset->setStatus(true);
			$returnset->setData($this->phn_id);
		}
		catch(Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		return $returnset;
	}

	/**
	 * This function finds contact profile related to a phone number
	 * 
	 * @param string $value		-	Phone number		-	Mandatory
	 * @param int	 $type		-	Type to find for	-	Optional
	 * 
	 * @return \Returnset
	 * @throws Exception
	 */
	public static function findPhone($value, $type = 0, $returnType = 0, $isAdmin = 0)
	{
		$returnset = new ReturnSet();
		try
		{
			if(empty($value))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}

			$sql = " SELECT  
							ctt.ctt_id,
							ctt.ctt_business_name,
							ctt.ctt_first_name,
							ctt.ctt_last_name,
							ctt.ctt_license_no,
							cn.phn_phone_country_code,
							cn.phn_phone_no,
							cn.phn_contact_id,
							cp.cr_is_vendor,
							cp.cr_is_driver,
							cp.cr_is_consumer,
							cp.cr_is_partner
					FROM    contact_phone cn
							INNER JOIN contact ctt ON ctt.ctt_id = cn.phn_contact_id
							INNER JOIN contact_profile cp ON cp.cr_contact_id = cn.phn_contact_id
					WHERE   phn_phone_no = '" . $value . "'
						AND     phn_is_verified = 1
						AND     phn_active = 1
			      ";

			if(!$isAdmin)
			{
				if($type > 0)
				{
					$sql .= " AND phn_type IN (2,3)";
				}
				else
				{
					$sql .= " AND phn_type IN (1)";
				}
			}

			$arrPhoneDetails = DBUtil::queryAll($sql, DBUtil::SDB());

			/**
			 * Case 1 : If return type 1 and data is there Do Set data, set status = true
			 * Case 2 : If return type null and data is also blank , default status = false
			 */
			if($returnType)
			{
				$response	 = [];
				$index		 = 0;
				foreach($arrPhoneDetails as $phone)
				{
					$response[$index]["ctt_id"]					 = $phone["ctt_id"];
					$response[$index]["ctt_business_name"]		 = $phone["ctt_business_name"];
					$response[$index]["ctt_first_name"]			 = $phone["ctt_first_name"];
					$response[$index]["ctt_last_name"]			 = $phone["ctt_last_name"];
					$response[$index]["ctt_license_no"]			 = $phone["ctt_license_no"];
					$response[$index]["phn_contact_id"]			 = $phone["phn_contact_id"];
					$response[$index]["phn_phone_country_code"]	 = $phone["phn_phone_country_code"];
					$response[$index]["phn_phone_no"]			 = $phone["phn_phone_no"];

					if(!empty($phone["cr_is_vendor"]))
					{
						$vendorModel = Vendors::model()->findByPk($phone["cr_is_vendor"]);

						$response[$index]["cr_is_vendor"]	 = $phone["cr_is_vendor"];
						$response[$index]["vnd_code"]		 = $vendorModel->vnd_code;
					}

					if(!empty($phone["cr_is_driver"]))
					{
						$driverModel						 = Drivers::model()->findByPk($phone["cr_is_driver"]);
						$vendorDriverList					 = VendorDriver::model()->getActiveVendorListbyDriverId($phone["cr_is_driver"]);
						$response[$index]["cr_is_driver"]	 = $phone["cr_is_driver"];
						$response[$index]["drv_code"]		 = $driverModel->drv_code;
						$response[$index]["mapVendors"]		 = $vendorDriverList;
					}
					if(!empty($phone["cr_is_consumer"]))
					{
						$userModel							 = Users::model()->findByPk($phone["cr_is_consumer"]);
						$response[$index]["cr_is_consumer"]	 = $phone["cr_is_consumer"];
					}

					$index++;
				}

				$returnset->setData($response);
			}

			if(!empty($arrPhoneDetails))
			{
				$returnset->setStatus(true);
			}
		}
		catch(Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		return $returnset;
	}

	public function validatePrimary($contactId, $phone = null)
	{
		Logger::profile("ContactPhone:validatePrimary Started");
		/** @var ContactPhone $contactPhoneModel */
		$contactPhoneModel	 = ContactPhone::model()->findByConId($contactId);
		$isPrimary			 = 1;

		if(empty($contactPhoneModel))
		{
			goto skipAll;
		}
		foreach($contactPhoneModel as $conModel)
		{
			if($conModel->phn_is_primary && ($phone == null || $phone != $conModel->phn_phone_no))
			{
				$isPrimary = 0;
				break;
			}
		}
		skipAll:
		Logger::profile("ContactPhone:validatePrimary Ended");
		return $isPrimary;
	}

	public function removeZero($phnId, $phone)
	{
		Preg_match("/\d*(\d{10})/", $phone, $match);
		$string	 = substr($match[1], 1);
		$sql	 = "UPDATE contact_phone SET phn_phone_no = $string WHERE phn_id = $phnId ";
		$success = DBUtil::command($sql)->execute();
		return $success;
	}

	public static function expireLink($phone)
	{
		$returnSet = new ReturnSet();
		try
		{
			$contactModel = ContactPhone::model()->findByContact($phone);

			/** @var ContactPhone $contactModel */
			$contactModel->phn_id			 = $contactModel->phn_id;
			$contactModel->phn_is_expired	 = 1;
			if(!$contactModel->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_FAILED);
			}
			$returnSet->setStatus(true);
		}
		catch(Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnSet->setException($ex);
		}


		return $returnSet;
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
		$sql	 = "SELECT DISTINCT contact_phone.phn_contact_id, contact_phone.phn_phone_no  
				FROM `contact_phone`
				WHERE contact_phone.phn_full_number IN (
					SELECT DISTINCT contact_phone.phn_full_number FROM `contact_phone` WHERE  `phn_contact_id` = :contactId AND contact_phone.phn_active=1
				) AND contact_phone.phn_active=1";
		$result	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		if($getQuery)
		{
			return $result;
		}
		foreach($result as $val)
		{
			$contactId	 = $val['phn_contact_id'];
			$number		 = $val['phn_phone_no'];
			self::unlink($contactId);

			$desc = "Contact phone remove: {$number} , reason : {$reason}";
			ContactLog::model()->createLog($contactId, $desc, ContactLog::CONTACT_PHONE_REMOVE);
		}
	}

	/**
	 * 
	 * @param type $contactId
	 * @throws Exception
	 */
	public static function unlink($contactId)
	{
		if(empty($contactId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		$deactivateEmail = "UPDATE `contact_phone` SET phn_active = 0 WHERE phn_contact_id  in ($contactId)";
		return DBUtil::execute($deactivateEmail);
	}

	/**
	 * Finds all the contact related to a particular phone no
	 * @param string $phoneNo
	 */
	public function getData($phoneNo, $verified = true)
	{
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		$cndVerified = "";
		if($verified)
		{
			$cndVerified = " AND ( phn_is_verified = 1 OR phn_is_primary = 1) ";
		}
		$params	 = ['number' => $number, 'code' => $code];
		$sql	 = "SELECT GROUP_CONCAT(con.ctt_id) AS cttIds
					FROM   `contact` con
					INNER JOIN contact_phone cp ON con.ctt_id=cp.phn_contact_id AND cp.phn_phone_no<>'' AND cp.phn_phone_no IS NOT NULL
					WHERE  phn_phone_no=:number AND phn_phone_country_code=:code AND phn_active=1 $cndVerified AND con.ctt_active=1";
		$result	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		Logger::info($sql . " \n params: " . json_encode($params));
		return $result;
	}

	public static function isVerified($contactId)
	{
		$params	 = ['id' => $contactId];
		$sql	 = "SELECT COUNT(cp.phn_id) AS phIds
				FROM    contact_phone cp
				WHERE  phn_is_verified = 1 AND phn_active = 1 AND phn_contact_id =:id";
		$result	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return ($result > 0);
	}

	/**
	 * This function is used for fetching all the active numbers
	 * @param type $contactId
	 * @return \ReturnSet
	 */
	public static function getAllNumbers($contactId)
	{
		$returnSet	 = new ReturnSet();
		$model		 = self::model()->findByConId($contactId);
		if(empty($model))
		{
			goto skipAll;
		}

		$arrResponse = [];
		foreach($model as $key => $value)
		{
			$arrResponse[$key] = $value["phn_phone_no"];
		}
		$returnSet->setStatus(true);
		$returnSet->setData($arrResponse);
		skipAll:
		return $returnSet;
	}

	/**
	 * This function is used for resending verification link to active contact no
	 * With non verified state
	 * @param type $phoneNo
	 * @param type $cttId
	 */
	public static function resendVerificationLink($phoneNo, $cttId, $userType = UserInfo::TYPE_CONSUMER)
	{
		$returnSet = new ReturnSet();
		try
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("Something went wrong");
			$oldModel	 = self::model()->isExists($phoneNo, $cttId);
			$verifyCode	 = rand(10000, 99999);
			if(($oldModel->phn_phone_no == "" || $oldModel->phn_phone_no == null) || ($oldModel->phn_id == "" || $oldModel->phn_id == null) || ($cttId == "" || $cttId == null))
			{
				$returnSet->setMessage("Required data missing");
				return $returnSet;
			}
			$param	 = array("phn_otp" => $verifyCode, 'phn_contact_id' => $cttId, 'phn_phone_no' => $oldModel->phn_phone_no, 'phn_id' => $oldModel->phn_id);
			$sql	 = "UPDATE contact_phone SET phn_otp =:phn_otp , phn_is_expired = 0 WHERE  phn_contact_id =:phn_contact_id  AND phn_phone_no = :phn_phone_no AND phn_id = :phn_id";
			$cntRow	 = DBUtil::execute($sql, $param);
			if($cntRow > 0)
			{
				$phoneData	 = ['otp' => $verifyCode, 'number' => $oldModel->phn_phone_no, 'ext' => $oldModel->phn_phone_country_code, 'isNew' => 0];
				$isOtpSend	 = Contact::sendVerification($phoneData["number"], Contact::TYPE_PHONE, $cttId, Contact::MODIFY_CON_TEMPLATE, Contact::MODE_OTP, $userType, 0, $phoneData["otp"], $phoneData["ext"]);
				$status		 = ($isOtpSend) ? true : false;
				$returnSet->setStatus($status);
				$status ? $returnSet->setMessage("Send link successfully") : $returnSet->setMessage("Unable to send  OTP");
			}
		}
		catch(Exception $ex)
		{
			Logger::exception($ex);
			$returnSet = ReturnSet::setException($ex);
			$returnSet->setMessage("Something went wrong");
		}
		return $returnSet;
	}

	public static function primaryToggle($phone, $contactId)
	{
		$phoneModel	 = self::model()->findByPhoneAndContact($phone, $contactId);
		$phn_id		 = $phoneModel->phn_id;
		$model		 = self::model()->findByPk($phn_id);
		$params		 = ['contact' => $contactId, 'phnId' => $phn_id];
		if($phn_id != NULL)
		{
			$model->phn_is_primary	 = 1;
			$model->save();
			$sql					 = "UPDATE contact_phone SET phn_is_primary=0 WHERE phn_id<>:phnId AND phn_contact_id=:contact";
			DBUtil::command($sql, DBUtil::MDB())->execute($params);
		}
	}

	/**
	 * @deprecated since version 2020
	 * new Function getPrimaryNumber
	 */
	public function findPrimaryPhoneByContactId($cttid)
	{
		$sql	 = "SELECT phn_phone_no,phn_phone_country_code FROM `contact_phone` WHERE phn_active=1 AND phn_is_primary=1 AND phn_contact_id='$cttid'";
		$result	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $result;
	}

	/** @deprecated
	 * @return libphonenumber\PhoneNumber 
	 * use getPrimaryNumber
	 */
	public static function getPrimaryNumberOld($contactId, $isVerified = false)
	{
		$obj = false;
		try
		{
			$verifySQL = "";
			if($isVerified)
			{
				$verifySQL = " AND phn_is_verified=1";
			}

			$params	 = ['contact' => $contactId];
			$sql	 = "SELECT IF((phn_is_primary=1 OR phn_is_verified=1 OR usr_mobile=''), phn_full_number, 
								TRIM(CONCAT(IF(TRIM(IFNULL(`usr_country_code`,''))='' AND TRIM(IFNULL(`usr_mobile`,''))<>'','91',IFNULL(usr_country_code,'')), TRIM(LEADING '0' FROM TRIM(IFNULL(`usr_mobile`,'')))))
							) as contactNumber 
						FROM `contact_phone`
						LEFT JOIN users ON usr_contact_id=phn_contact_id 
						WHERE phn_active=1  AND phn_contact_id=:contact $verifySQL
						ORDER BY phn_is_primary DESC, phn_is_verified DESC, phn_id ASC
				";

			$phone = DBUtil::command($sql, DBUtil::MDB())->queryScalar($params);
			if(!$phone)
			{
				return false;
			}

			$obj = Filter::parsePhoneNumber('+' . $phone, $code, $number);
		}
		catch(Exception $exc)
		{
			Logger::trace(Logger::getExceptionString($exc));
		}

		return $obj;
	}

	/** @return libphonenumber\PhoneNumber */
	public static function getPrimaryNumber($cttIds, $isVerified = false, $refCttId = 0)
	{
		try
		{

			DBUtil::getINStatement($cttIds, $bindString, $params);

			$sql	 = "SELECT GROUP_CONCAT(CONCAT(ctt_id,',',ctt_ref_code)) as ids 
					FROM contact 
					WHERE ctt_id IN ($bindString) 
					AND ctt_active = 1";
			$refIds	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);

			$phoneDataSet = ContactPhone::getRelatedPhoneList($refIds, $isVerified, $refCttId);

			if($phoneDataSet->getRowCount() == 0)
			{
				return false;
			}
			$primaryPhoneData	 = $phoneDataSet->read();
			$primaryPhone		 = $primaryPhoneData['phn_full_number'];
			$obj				 = Filter::parsePhoneNumber('+' . $primaryPhone, $code, $number);
		}
		catch(Exception $exc)
		{
			Logger::trace(Logger::getExceptionString($exc));
		}

		return $obj;
	}

	public static function getRelatedPhoneList($cttIds, $isVerified = false, $refCttId = 0)
	{
		DBUtil::getINStatement($cttIds, $bindString, $params);
		$params ['refCttId'] = $refCttId;

		$verifySQL = "";
		if($isVerified)
		{
			$verifySQL = " AND phn_is_verified=1";
		}

		$sql = " SELECT ctt_id, ctt_ref_code, phn.phn_full_number,
					phn.phn_is_verified, phn_verified_date,
					IF(ctt_id=ctt_ref_code,1,0) as primaryFlag,
					IF(ctt_id=:refCttId,1,0) as refContact
				FROM contact
				INNER JOIN contact_phone phn ON phn.phn_contact_id = ctt_id AND phn.phn_active = 1
				WHERE ctt_id IN ($bindString) AND ctt_active = 1 $verifySQL
				ORDER BY phn_is_verified DESC, phn_is_primary DESC, primaryFlag DESC, refContact DESC, phn_verified_date DESC
				";
		$phoneData = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $phoneData;
	}

	/**
	 * @param type (int) $id
	 * @return boolean  */
	public static function getContactNumber($contactId, $whatsappVerified = 0)
	{
		$params	 = ['contact' => $contactId];
		$orderBy = $whatsappVerified == 1 ? " phn_whatsapp_verified  DESC ," : "";
		$sql	 = "SELECT   if(phn_full_number <> '', phn_full_number, CONCAT(usr_country_code, usr_mobile)) contactNumber
					FROM `contact_phone` 
					LEFT JOIN users ON usr_contact_id = phn_contact_id
					WHERE  phn_contact_id=:contact  AND phn_active=1
					ORDER BY  phn_active DESC,$orderBy (phn_is_primary + phn_is_verified + phn_active) DESC
			";

		$phone = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);

		if(!$phone)
		{
			return false;
		}

		return $phone;
	}

	/**
	 * Find the primary contact related to a particular phone no
	 * @param string $phoneNo
	 */
	public static function getContactid($phoneNo)
	{
		Filter::parsePhoneNumber($phoneNo, $code, $number);

		$params	 = ['number' => $number, 'code' => $code];
		$sql	 = "SELECT  con.ctt_id 
					FROM   `contact` con
					INNER JOIN contact_phone cp ON con.ctt_id=cp.phn_contact_id 
						AND cp.phn_phone_no<>'' AND cp.phn_phone_no IS NOT NULL
					WHERE  phn_phone_no=:number 
						AND phn_phone_country_code=:code 
						AND phn_active=1  
						AND con.ctt_active=1 ORDER BY (phn_is_primary + phn_is_verified) DESC";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $result;
	}

	/**
	 * Sending verification otp to active phone number	
	 * @param integer type $phoneNo
	 * @param integer type $cttId
	 * @param integer type $userType
	 * @param string type $bookingId
	 */
	public static function resendContactVerificationOtp($phoneNo, $cttId, $userType = UserInfo::TYPE_CONSUMER, $bookingId = null, $isResent = 0)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$returnSet = new ReturnSet();
		try
		{
			$returnSet->setStatus(false);
			$oldModel	 = self::model()->isExists($phoneNo, $cttId);
			$verifyCode	 = rand(10000, 99999);
			if($isResent == 1 && $oldModel->phn_otp > 0)
			{
				$from_time	 = strtotime($oldModel->phn_otp_last_sent_date);
				$to_time	 = strtotime("now");
				$minutes	 = round(abs($to_time - $from_time) / 60, 2);
				if($minutes <= 10)
				{
					$verifyCode	 = $oldModel->phn_otp;
					$cntRow		 = 1;
					goto pass;
				}
			}
			$param	 = array("phn_otp" => $verifyCode, 'phn_contact_id' => $cttId, 'phn_phone_no' => $oldModel->phn_phone_no, 'phn_id' => $oldModel->phn_id);
			$sql	 = "UPDATE contact_phone SET phn_otp =:phn_otp , phn_is_expired = 0, phn_otp_last_sent_date = NOW() WHERE  phn_contact_id =:phn_contact_id  AND phn_phone_no = :phn_phone_no AND phn_id = :phn_id";
			$cntRow	 = DBUtil::execute($sql, $param);
			pass:
			if($cntRow > 0)
			{
				$isDelay	 = 0;
				//$msg         = "Your OTP for phone number verification is ".$verifyCode;  
				$msg		 = "Your OTP for starting verification is " . $verifyCode . " - aaocab";
				$sms		 = new Messages();
				$res		 = $sms->sendMessage($oldModel->phn_phone_country_code, $oldModel->phn_phone_no, $msg, $isDelay);
				Logger::trace("Response of SMS =====>" . $res);
				$usertype	 = SmsLog::Driver;
				$slgId		 = smsWrapper::createLog($oldModel->phn_phone_country_code, $oldModel->phn_phone_no, $bookingId, $msg, $res, $usertype, "", '', $oldModel, '', $isDelay);
				$status		 = ($slgId) ? true : false;
				$returnSet->setStatus($status);
				$status ? $returnSet->setMessage("OTP sent successfully") : $returnSet->setMessage("Unable to send  OTP");
			}
		}
		catch(Exception $ex)
		{
			Logger::exception($ex);
			$returnSet = ReturnSet::setException($ex);
			$returnSet->setMessage("Something went wrong");
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $returnSet;
	}

	public static function getPhoneNo($entityId, $entityType)
	{
		$cntId = ContactProfile::getByEntityId($entityId, $entityType);
		if($cntId)
		{
			$getNo	 = ContactPhone::findByContactID($cntId);
			$phoneNo = $getNo[0]->phn_phone_no;
		}
		return $phoneNo;
	}

	public static function countDuplicateItems($number, $contactId)
	{
		$params	 = ["number" => $number, "contactId" => $contactId];
		$sql	 = "SELECT COUNT(phn_phone_no) AS Number
				FROM    contact_phone 
				WHERE  phn_contact_id =:contactId AND phn_phone_no =:number AND phn_active = 1";
		$result	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return ($result > 0);
	}

	public static function getByPhone($phone, $email = '', $firstName = '', $lastName = '', $limit = '')
	{
		$params	 = ["email" => $email, 'phone' => $phone, 'firstName' => $firstName, 'lastName' => $lastName];
		$sql	 = "SELECT c1.ctt_id, IFNULL(cp1.cr_is_consumer, cp.cr_is_consumer) as userId,IFNULL(cp1.cr_is_driver, cp.cr_is_driver) as driverId,
					IFNULL(cp1.cr_is_vendor, cp.cr_is_vendor) as vendorId,
					MAX(ce.phn_is_primary) as isPrimary, MAX(ce.phn_is_verified) AS isVerified,
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
						WHEN MAX(eml.eml_is_primary)=1 AND MAX(eml.eml_is_verified)=1 THEN 4
						WHEN MAX(eml.eml_is_verified)=1 THEN 3
						WHEN MAX(eml.eml_is_primary)=1 THEN 2
						WHEN eml_id IS NOT NULL THEN 1
						ELSE 0
					END as emailRank,
					IF(cp1.cr_is_consumer IS NULL, 0, 1) as profileRank
					FROM contact_phone ce
					INNER JOIN contact c ON ce.phn_contact_id=c.ctt_id AND phn_active=1
					LEFT JOIN contact_profile cp ON cp.cr_contact_id=c.ctt_id
					INNER JOIN contact c1 ON c1.ctt_id=c.ctt_ref_code
					LEFT JOIN contact_profile cp1 ON cp1.cr_contact_id=c1.ctt_id
					LEFT JOIN contact_email eml ON eml.eml_contact_id=c1.ctt_id AND eml_active=1 AND eml_email_address=:email
					WHERE ce.phn_full_number=:phone
					GROUP BY c1.ctt_id HAVING (isPrimary + isVerified)>0
					ORDER BY (ce.phn_is_primary + ce.phn_is_verified) DESC, phoneRank DESC,nameRank DESC,emailRank DESC,ce.phn_is_verified DESC, profileRank DESC $limit";
		$data	 = DBUtil::query($sql, DBUtil:: MDB(), $params);
		//Logger::info("ContactPhone::getByPhone " . $sql);
		Logger::create("ContactPhone::getByPhone query" . $sql, CLogger::LEVEL_INFO);
		Logger::create("ContactPhone::getByPhone params" . json_encode($params), CLogger::LEVEL_INFO);
		return $data;
	}

	public function getVerifiredPhoneByContact($phone, $contactId)
	{
		if($contactId > 0)
		{
			$params	 = ['phone' => $phone, 'contact' => $contactId];
			$sql	 = "select phn_is_expired From contact_phone WHERE phn_is_expired = 0 AND phn_active=1 AND phn_contact_id = :contact AND phn_phone_no = :phone";
			$data	 = DBUtil::queryRow($sql, DBUtil:: SDB(), $params);
		}
		return $data;
	}

	public static function getLinkedContactIds($phone)
	{
		$phone = Filter::processPhoneNumber($phone);
		if(!$phone)
		{
			return false;
		}
		Filter::parsePhoneNumber($phone, $code, $number);
		$phone	 = $code . $number;
		$sql	 = "SELECT GROUP_CONCAT(DISTINCT phn_contact_id) as contactIds FROM contact_phone WHERE phn_active=1 AND phn_full_number=:phone";
		$params	 = ["phone" => $phone];

		$cttIds = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $cttIds;
	}

	/**
	 * 
	 * @param type $code
	 * @param type $phone
	 * @param type $smsType
	 * @param type $sendType (signin/signup)
	 * @return boolean
	 */
	public static function checkTosendSMS($code, $phone, $smsType, $sendType = 0)
	{
		$sendSms			 = true;
		$internationalLimit	 = Config::getSendSMSLimit();
		////Yii::app()->params['internationalNoLimitPerday'];
		$noOfHRvalid		 = Yii::app()->params['sendSmsValidHr'];
		$noOfSMSvalid		 = Yii::app()->params['sendSmsValidNo'];
		if($code != '91')
		{
			$sendType	 = 1;
			$msgCount	 = SmsLog::getCountByType($code, $smsType, $code . $phone, 0, $sendType);
			if($msgCount >= $internationalLimit)
			{
				$sendSms = false;
			}
		}
		else
		{
			$msgCount = SmsLog::getCountByType($code, $smsType, $code . $phone, $noOfHRvalid);
			if($msgCount >= $noOfSMSvalid)
			{
				$sendSms = true;
			}
		}
		return $sendSms;
	}

	/** This function is used for validating Phone number
	 * @throws Exception
	 */
	public function actionVerifyPhone()
	{
		$returnSet = new ReturnSet();
		try
		{
			$hashContactId	 = Yii::app()->request->getParam('id');
			$otpHash		 = Yii::app()->request->getParam('otp');
			$contactId		 = Yii::app()->shortHash->unhash($hashContactId);
			$templateHash	 = Yii::app()->request->getParam('ts');
			$vndHash		 = Yii::app()->request->getParam('vnd');
			$templateStyle	 = Yii::app()->shortHash->unhash($templateHash);

			$model	 = Contact::model()->findByPk($contactId);
			$phoneNo = ContactPhone::model()->getContactPhoneById($contactId);
			$type	 = Contact::TYPE_PHONE;
			$mode	 = Contact::MODE_OTP;
			switch($templateStyle)
			{
				case Contact::NEW_CON_TEMPLATE:
					$vndId			 = Yii::app()->shortHash->unhash($vndHash);
					$templateStyle	 = Contact::NEW_CON_TEMPLATE;
					break;
				case Contact::MODIFY_CON_TEMPLATE:
					$num			 = Yii::app()->request->getParam('num');
					$phoneNo		 = base64_decode($num);
					$templateStyle	 = Contact::MODIFY_CON_TEMPLATE;
					break;
				case Contact::NOTIFY_OLD_CON_TEMPLATE:

					$tempPkHash		 = Yii::app()->request->getParam('tpk');
					$vndHash		 = Yii::app()->request->getParam('v');
					$vndName		 = base64_decode($vndHash);
					$tempPkId		 = Yii::app()->shortHash->unhash($tempPkHash);
					$tempModel		 = ContactTemp::model()->findByPk($tempPkId);
					$phoneNo		 = $tempModel->tmp_ctt_phn_number;
					$templateStyle	 = Contact::NOTIFY_OLD_CON_TEMPLATE;
					break;
				default:
					break;
			}

			if(Yii::app()->request->isAjaxRequest)
			{
				$cid			 = Yii::app()->request->getParam('hash');
				$contactId		 = Yii::app()->request->getParam('cttid');
				$code			 = Yii::app()->request->getParam('code');
				$otp			 = Yii::app()->request->getParam('otp');
				$vndId			 = Yii::app()->request->getParam('vndId');
				$phone			 = Yii::app()->request->getParam('modifyPhone');
				$isExpireLink	 = Yii::app()->request->getParam('expireLink');
				if(Yii::app()->shortHash->unhash($otp) == $code && Yii::app()->shortHash->unhash($cid) == $contactId)
				{
					/**
					 *  expire phone verification link
					 */
					if($isExpireLink > 0)
					{
						$isExpire = ContactPhone::expireLink($phone);
					}
					$returnSet = Contact::verifyItem($contactId, $type, $mode, 0, null, $phone);
					if($returnSet->getStatus() && $vndId > 0)
					{
						$drvModel		 = Drivers::model()->findByDriverContactID($contactId);
						$arr			 = ['driver' => $drvModel->drv_id, 'vendor' => $vndId];
						$isExitVendor	 = VendorDriver::model()->checkAndSave($arr);
						if($isExitVendor)
						{
							VendorStats::model()->updateCountDrivers($vndId);
						}
						BookingCab::model()->updateVendorPayment($flag = 1, $drvModel->drv_id);
					}
				}
				if($returnSet)
				{
					echo json_encode($returnSet);
					Yii::app()->end();
				}
				else
				{
					echo json_encode(['success' => false]);
					Yii::app()->end();
				}
			}
		}
		catch(Exception $exc)
		{
			throw new Exception(json_encode($model->getErrors()));
		}
		$this->render('verifyPhone', array('model' => $model, 'tempModel' => $tempModel, 'conid' => $hashContactId, 'otp' => $otpHash, 'phone' => $phoneNo, 'templateStyle' => $templateStyle, 'vndName' => $vndName, 'vndId' => $vndId, 'contactId' => $contactId));
	}

	/**
	 * This function is used for updating the status for phone
	 * @param type (int) $id
	 * @return boolean
	 */
	public function updateContactStatus($id, $phone = null)
	{
		$returnset	 = new ReturnSet();
		$returnset->setStatus(false);
		$phoneNo	 = [];
		$transaction = DBUtil::beginTransaction();
		try
		{
			Filter::parsePhoneNumber($phone, $code, $number);
			if($id > 0)
			{
				$cond = ($phone != null) ? ('AND phn_phone_no = ' . $number) : "";

				$updateQuery = " UPDATE contact_phone 
                                        SET    phn_is_verified = 1, 
                                               phn_verified_date = Now(), 
                                               phn_verify_count = phn_verify_count + 1 
                                        WHERE  phn_contact_id = $id $cond ";
				$sql		 = DBUtil::command($updateQuery)->execute();
				$returnset->setStatus(true);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch(Exception $ex)
		{
			$returnset->setStatus(false);
			$returnSet	 = $returnSet->setException($e);
			DBUtil::rollbackTransaction($transaction);
			$message	 = $ex->getMessage();
		}
		return $returnset;
	}

	/**
	 * 
	 * @param type $phone
	 * @param type $cttId
	 * @return \ReturnSet
	 */
	public static function setPrimaryByPhone($phone, $cttId)
	{
		$returnset	 = new ReturnSet();
		$param		 = ['contactId' => $cttId, 'phoneno' => $phone];
		$sql		 = "SELECT phn_id FROM contact_phone WHERE phn_active=1 AND phn_contact_id=:contactId AND phn_phone_no=:phoneno";
		$phnId		 = DBUtil::command($sql, DBUtil::MDB())->queryScalar($param);
		$params		 = ['contactId' => $cttId, 'phnId' => $phnId];
		$model		 = self::model()->findByPk($phnId);
		try
		{
			if($phnId != NULL)
			{
				$model->phn_is_primary = 1;
				if($model->save())
				{
					$returnset->setStatus(true);
					$sql = "UPDATE contact_phone SET phn_is_primary=0 WHERE phn_id<>:phnId AND phn_contact_id=:contactId";
					DBUtil::command($sql, DBUtil::MDB())->execute($params);
				}
			}
			DBUtil::commitTransaction($transaction);
		}
		catch(Exception $ex)
		{
			$returnset->setStatus(false);
			$returnSet	 = $returnSet->setException($ex);
			DBUtil::rollbackTransaction($transaction);
			$message	 = $ex->getMessage();
		}
		return $returnset;
	}

	public static function getLinkedContactCountByPhone($phone)
	{
		$phone = Filter::processPhoneNumber($phone);
		if(!$phone)
		{
			return false;
		}
		Filter::parsePhoneNumber($phone, $code, $number);
		$phone	 = $code . $number;
		$sql	 = "SELECT COUNT(phn_contact_id) as count FROM contact_phone WHERE phn_active=1 AND phn_full_number=:phone";
		$params	 = ["phone" => $phone];

		$result = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $result;
	}

	public function validatePhoneById($attribute, $params)
	{
		$returnSet	 = new ReturnSet();
		$cttId		 = $params['cttid'];
		try
		{
			$sql	 = "SELECT COUNT(phn_id) as phncount, count(phn_is_verified) as phnVerified FROM contact_phone WHERE phn_active=1 AND phn_contact_id=:cttid";
			$params	 = ["cttid" => $cttId];
			$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);

			$contactPhoneModel = ContactPhone::model()->find('phn_contact_id=:id && phn_phone_no=:phone', ['id' => $cttId, 'phone' => $attribute]);
			if($result['phncount'] == 1)
			{
				throw new Exception("You are not authorized to remove this phone", ReturnSet::ERROR_FAILED);
			}

			if($result['phnVerified'] == 1)
			{
				throw new Exception("You are not authorized to remove verified contact", ReturnSet::ERROR_FAILED);
			}

			if($contactPhoneModel->phn_is_primary == 1)
			{
				throw new Exception("You are not authorized to remove primary contact", ReturnSet::ERROR_FAILED);
			}

			$returnSet->setStatus(true);
		}
		catch(Exception $ex)
		{
			$this->addError($attribute, $ex->getMessage());
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param type $phoneModel
	 * @param type $contactPhone
	 * @param type $cttId
	 * @throws Exception
	 */
	public function addNewByContact($phoneModel, $contactPhone, $cttId)
	{
		$returnSet				 = new ReturnSet();
		$phoneModel->attributes	 = $contactPhone;
		try
		{
			$value			 = "+" . $phoneModel->phn_phone_country_code . $phoneModel->phn_phone_no;
			//$isPhone = Filter::parsePhoneNumber($value, $code, $number);
			$isValidPhone	 = Filter::validatePhoneNumber('+' . $value);
			if(!$isValidPhone)
			{
				throw new Exception("Please enter valid phone number.", ReturnSet::ERROR_INVALID_DATA);
			}
			$isPhone	 = Filter::parsePhoneNumber($value, $code, $number);
			//need to check phone exist or not
			$existsModel = self::model()->isExists($number, $cttId);
			if($existsModel)
			{
				throw new Exception("Already exist in your account.", ReturnSet::ERROR_PHONEEXIST);
			}
			$returnSet = ContactPhone::checkData(0, $code . $number, 1, '', 1);
			if($returnSet)
			{
				//$returnSet = $this->add($contactId, $contactValue, $userType, 0, $sourceType, 1);
				if($returnSet->isSuccess())
				{
					throw new Exception("This number is already verified with someone else. Please contact Gozo support team to proceed with this contact. ", ReturnSet::ERROR_PHONEEXIST);
				}
			}
			$primaryPhone	 = ContactPhone::validatePrimary($cttId);
			$returnSet		 = ContactPhone::model()->add($cttId, $number, 1, $code, 1, $primaryPhone);
		}
		catch(Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * @param type (int) $id
	 * @return boolean 
	 *  */
	public static function getNumber($contactId)
	{
		$params	 = ['contact' => $contactId];
		$sql	 = "SELECT   if(phn_full_number <> '', phn_full_number, CONCAT(usr_country_code, usr_mobile)) contactNumber,phn_phone_country_code AS code,phn_phone_no AS number
					FROM     `contact_phone` 
					LEFT JOIN users ON usr_contact_id = phn_contact_id
					WHERE  phn_contact_id=:contact  AND phn_active=1
					ORDER BY phn_active DESC,phn_whatsapp_verified DESC,(phn_is_primary + phn_is_verified + phn_active) DESC";

		$phone = DBUtil::queryRow($sql, DBUtil::SDB(), $params);

		if(!$phone)
		{
			return false;
		}
		return $phone;
	}

	public static function checkVerified($phoneNo = null)
	{
		if(!$phoneNo)
		{
			return false;
		}
		$code	 = $number	 = null;
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		$params	 = ['number' => $number, 'code' => $code];
		$sql	 = "SELECT  ctt_id 
					FROM   `contact` con
					INNER JOIN contact_phone cp ON con.ctt_id=cp.phn_contact_id AND cp.phn_phone_no<>'' AND cp.phn_phone_no IS NOT NULL
					WHERE  phn_phone_no=:number AND phn_phone_country_code=:code AND phn_active=1  AND  phn_is_verified = 1 AND con.ctt_active=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public static function getByVerifiedContactId($cttId)
	{
		$params	 = ['id' => $cttId];
		$sql	 = "select phn_phone_no FROM contact_phone WHERE phn_is_verified=1 AND phn_contact_id = :id ORDER BY phn_phone_no ASC LIMIT 1";
		$phone	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return $phone;
	}

	public static function validatePhoneSCQ($phone)
	{
		$phone	 = trim(str_replace(' ', '', $phone));
		$success = true;
		try
		{
			if(!Filter::validatePhoneNumber($phone))
			{
				throw new Exception('Invalid phone number');
			}
		}
		catch(Exception $exc)
		{
			$success = false;
		}
		return $success;
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
				contact_phone.phn_contact_id
				FROM `contact_phone`
				INNER JOIN `contact` ON contact.ctt_id = contact_phone.phn_contact_id AND contact.ctt_active = 1
				WHERE contact.ctt_ref_code = :contactId AND contact.ctt_id <> :contactId AND contact_phone.phn_active = 1
				GROUP BY contact.ctt_id";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}
}
