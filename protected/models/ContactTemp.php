<?php

/**
 * This is the model class for table "contact_temp".
 *
 * The followings are the available columns in table 'contact_temp':
 * @property string $tmp_ctt_id
 * @property int $tmp_ctt_request_by
 * @property string $tmp_ctt_contact_id
 * @property integer $tmp_ctt_profile
 * @property string $tmp_ctt_name
 * @property string $tmp_ctt_email
 * @property integer $tmp_ctt_phn_code
 * @property string $tmp_ctt_phn_number
 * @property string $tmp_ctt_phn_otp
 * @property string $tmp_ctt_license
 * @property integer $tmp_ctt_status
 * @property string $tmp_ctt_expiry_time
 * @property string $tmp_ctt_created
 * @property string $tmp_ctt_modified
 */
class ContactTemp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact_temp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tmp_ctt_contact_id, tmp_ctt_profile, tmp_ctt_status, tmp_ctt_expiry_time, tmp_ctt_created, tmp_ctt_modified', 'required'),
			array('tmp_ctt_profile, tmp_ctt_phn_code, tmp_ctt_status', 'numerical', 'integerOnly'=>true),
			array('tmp_ctt_contact_id', 'length', 'max'=>20),
			array('tmp_ctt_expiry_time', 'length', 'max'=>11),
			array('tmp_ctt_name', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tmp_ctt_id, tmp_ctt_contact_id, tmp_ctt_profile, tmp_ctt_name, tmp_ctt_email, tmp_ctt_phn_code, tmp_ctt_phn_number, tmp_ctt_license, tmp_ctt_status, tmp_ctt_expiry_time, tmp_ctt_created, tmp_ctt_modified', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tmp_ctt_id' => 'Tmp Ctt',
			'tmp_ctt_request_by' => 'Tmp Ctt Request By',
			'tmp_ctt_contact_id' => 'Tmp Ctt Contact',
			'tmp_ctt_profile' => '1:admin;2:vendor;3:driver',
			'tmp_ctt_name' => 'Tmp Ctt Name',
			'tmp_ctt_email' => 'Tmp Ctt Email',
			'tmp_ctt_phn_code' => 'Tmp Ctt Phn Code',
			'tmp_ctt_phn_number' => 'Tmp Ctt Phn Number',
			'tmp_ctt_phn_otp'	=> 'Tmp Ctt Phn Otp',
			'tmp_ctt_license' => 'Tmp Ctt License',
			'tmp_ctt_status' => '1=>unverified, 2=>verified',
			'tmp_ctt_expiry_time' => 'Tmp Ctt Expiry Time',
			'tmp_ctt_created' => 'Tmp Ctt Created',
			'tmp_ctt_modified' => 'Tmp Ctt Modified',
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

		$criteria=new CDbCriteria;

		$criteria->compare('tmp_ctt_id',$this->tmp_ctt_id,true);
		$criteria->compare('tmp_ctt_contact_id',$this->tmp_ctt_contact_id,true);
		$criteria->compare('tmp_ctt_profile',$this->tmp_ctt_profile);
		$criteria->compare('tmp_ctt_name',$this->tmp_ctt_name,true);
		$criteria->compare('tmp_ctt_email',$this->tmp_ctt_email,true);
		$criteria->compare('tmp_ctt_phn_code',$this->tmp_ctt_phn_code);
		$criteria->compare('tmp_ctt_phn_number',$this->tmp_ctt_phn_number,true);
		$criteria->compare('tmp_ctt_license',$this->tmp_ctt_license,true);
		$criteria->compare('tmp_ctt_status',$this->tmp_ctt_status);
		$criteria->compare('tmp_ctt_expiry_time',$this->tmp_ctt_expiry_time,true);
		$criteria->compare('tmp_ctt_created',$this->tmp_ctt_created,true);
		$criteria->compare('tmp_ctt_modified',$this->tmp_ctt_modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContactTemp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}



	/**
	 * This function is used for storing the temp details
	 * @param type $emailId
	 * @param type $name
	 * @param type $ext
	 * @param type $phNo
	 * @param type $licenseNo
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function add($contactId, $emailId, $name, $ext, $phNo, $licenseNo, $userId, $otp)
	{
		$returnset = new ReturnSet();
		
		try
		{
			if(empty($contactId) || empty($licenseNo))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			$model = new \ContactTemp();
			
			$model->tmp_ctt_contact_id  = $contactId;
			$model->tmp_ctt_request_by	= $userId;
			$model->tmp_ctt_email       = $emailId;
			$model->tmp_ctt_name        = $name;
			$model->tmp_ctt_phn_code    = $ext;
			$model->tmp_ctt_phn_number  = $phNo;
			$model->tmp_ctt_phn_otp		= $otp;
			$model->tmp_ctt_profile     = UserInfo::TYPE_DRIVER;
			$model->tmp_ctt_license     = $licenseNo;
			$model->tmp_ctt_status      = 1; //Unverified
			$model->tmp_ctt_expiry_time = time() + 4 * 60 * 60; // now +4 hour
			$model->tmp_ctt_created     = new CDbExpression('now()');
			$model->tmp_ctt_modified    = new CDbExpression('now()');

			if(!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_FAILED);
			}

			$returnset->setStatus(true);
            $returnset->setData($model->tmp_ctt_id);
		} 
		catch (Exception $ex) 
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		return $returnset;
	}
    
    public function findEmailByTemPkId($tempPkId)
    {
        $sql    = "SELECT tmp_ctt_email  FROM `contact_temp` WHERE `tmp_ctt_id` = '$tempPkId'";
        $result = DBUtil::command($sql)->queryScalar();
        return $result;
    }
    
    public function updateContactStatus($tempPkId,$isVerify)
    {
        $sql = "UPDATE contact_temp SET tmp_ctt_status = $isVerify  WHERE tmp_ctt_id = $tempPkId";
        $result = DBUtil::command($sql)->execute();
        return $result;
    }
    
	/**
	 * 
	 * @param type $contactId
	 * @param type $emailId
	 * @param type $modelPhone 
	 * @param type $vndId
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function addNew($contactModel,$emailId,$phoneModel,$vndId)
	{
		$returnset = new ReturnSet();
		
		try
		{
			if(empty($contactModel))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			$model = new \ContactTemp();
			
			$model->tmp_ctt_contact_id  = $contactModel->ctt_id;
			$model->tmp_ctt_request_by  = ($vndId)? $vndId : UserInfo::getEntityId();
			$model->tmp_ctt_email       = $emailId;
			$model->tmp_ctt_name        = $contactModel->ctt_first_name ."".$contactModel->ctt_last_name;
			$model->tmp_ctt_phn_code    = $phoneModel->phn_phone_country_code;
			$model->tmp_ctt_phn_number  = $phoneModel->phn_phone_no;
			$model->tmp_ctt_phn_otp	    = $phoneModel->phn_otp;
			$model->tmp_ctt_profile     = UserInfo::TYPE_DRIVER;
			$model->tmp_ctt_license     = $contactModel->ctt_license_no;
			$model->tmp_ctt_status      = 1; //Unverified
			$model->tmp_ctt_expiry_time = time() + 4 * 60 * 60; // now +4 hour
			$model->tmp_ctt_created     = new CDbExpression('now()');
			$model->tmp_ctt_modified    = new CDbExpression('now()');

			if(!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_FAILED);
			}

			$returnset->setStatus(true);
            $returnset->setData($model->tmp_ctt_id);
		} 
		catch (Exception $ex) 
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		return $returnset;
	}


	public function processData($contactModel, $userType,$vndId = null)
    {
	    $returnSet   = new ReturnSet();
	    
	    /** @var Contact $contactModel */
	    /** @var ContactPhone   $modelPhone */
	    $emailId      = $contactModel->contactEmails[0]->eml_email_address;
	    $phoneNo      = $contactModel->contactPhones[0]->phn_phone_no;
	     /** @var ContactPhone $modelPhone */
	    $phoneModel				 = ContactPhone::model()->findPhoneIdByPhoneNumber($phoneNo);
	    
	    $response = self::addNew($contactModel,$emailId,$phoneModel,$vndId);
	    
	
	    $isEmailSend = Contact::sendVerification($emailId, Contact::TYPE_EMAIL, $contactModel->ctt_id, Contact::NOTIFY_OLD_CON_TEMPLATE, Contact::MODE_LINK, $userType, $response->getData());
		if (!$isEmailSend)
		{
			$isOtpSend = Contact::sendVerification($phoneNo, Contact::TYPE_PHONE, $contactModel->ctt_id, Contact::NOTIFY_OLD_CON_TEMPLATE, Contact::MODE_OTP, $userType, $response->getData(), $phoneModel->phn_otp, $phoneModel->phn_phone_country_code);
		}

		if ($isEmailSend || $isOtpSend)
		{
			$returnSet->setStatus(true);
			$returnSet->setMessage("Your request has been noted. We have send verification to the contact details. Please verify it ");
		}

		return $returnSet;
    }

}
