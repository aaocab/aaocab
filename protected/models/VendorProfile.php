<?php

/**
 * This is the model class for table "vendor_profile".
 *
 * The followings are the available columns in table 'vendor_profile':
 * @property integer $vnp_id
 * @property integer $vnp_user_id
 * @property integer $vnp_booking_id
 * @property integer $vnp_attribute_type
 * @property string $vnp_value_str
 * @property integer $vnp_value_int
 * @property string $vnp_created
 */
class VendorProfile extends CActiveRecord
{

	const TYPE_NO_SHOW				 = 1;
	const TYPE_LATE					 = 2;
	const TYPE_PRIVATE_CAR			 = 3;
	const TYPE_NOT_MATCH				 = 4;
	const TYPE_BAD_CAR				 = 5;
	const TYPE_BAD_DRIVER				 = 6;
	const TYPE_RATING					 = 7;
	const TYPE_OTHER_PROBLEMS			 = 8;
	const TYPE_APP_USE				 = 9; //later
	const TYPE_DENIALS				 = 10;
	const TYPE_DENIALS_TO_PICKUP		 = 11;
	const TYPE_DENIALS_FROM_ASSIGNMENT = 12;
	const TYPE_CITIES_LIST			 = 13;

	public $csp_user_id, $csp_booking_id, $csp_attribute_type, $csp_value_int, $csp_value_str;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vnp_user_id, vnp_booking_id, vnp_attribute_type, vnp_value_int', 'numerical', 'integerOnly' => true),
			array('vnp_value_str', 'length', 'max' => 1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vnp_id, vnp_user_id, vnp_booking_id, vnp_attribute_type, vnp_value_str, vnp_value_int, vnp_created', 'safe', 'on' => 'search'),
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
			'vnp_id'			 => 'Vnp',
			'vnp_user_id'		 => 'Vnp User',
			'vnp_booking_id'	 => 'Vnp Booking',
			'vnp_attribute_type' => 'Vnp Attribute Type',
			'vnp_value_str'		 => 'Vnp Value Str',
			'vnp_value_int'		 => 'Vnp Value Int',
			'vnp_created'		 => 'Vnp Created',
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

		$criteria->compare('vnp_id', $this->vnp_id);
		$criteria->compare('vnp_user_id', $this->vnp_user_id);
		$criteria->compare('vnp_booking_id', $this->vnp_booking_id);
		$criteria->compare('vnp_attribute_type', $this->vnp_attribute_type);
		$criteria->compare('vnp_value_str', $this->vnp_value_str, true);
		$criteria->compare('vnp_value_int', $this->vnp_value_int);
		$criteria->compare('vnp_created', $this->vnp_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorProfile the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function setVendorDetailsOnCancelBooking($vendorId, $bkid, $status = 0)
	{
		try
		{
			$model			 = Booking::model()->findByPk($bkid);
			$vendorProfile	 = new VendorProfile();
			if ($status == 1)
			{
				$vendorProfile->vnp_user_id			 = $vendorId;
				$vendorProfile->vnp_booking_id		 = $model->bkg_id;
				$vendorProfile->vnp_attribute_type	 = $vendorProfile::TYPE_OTHER_PROBLEMS;
				$vendorProfile->vnp_value_str		 = $model->bkg_cancel_delete_reason;
				$vendorProfile->vnp_value_int		 = $model->bkg_cancel_id;
				$vendorProfile->save();
			}
			else
			{
				if ($model->bkg_cancel_id == 6)
				{
					$vendorProfile->vnp_user_id			 = $vendorId;
					$vendorProfile->vnp_booking_id		 = $model->bkg_id;
					$vendorProfile->vnp_attribute_type	 = $vendorProfile::TYPE_LATE;
					$vendorProfile->vnp_value_str		 = $model->bkg_cancel_delete_reason;
					$vendorProfile->vnp_value_int		 = $model->bkg_cancel_id;
					$vendorProfile->save();
				}

				if ($model->bkg_cancel_id == 22)
				{
					$vendorProfile->vnp_user_id			 = $vendorId;
					$vendorProfile->vnp_booking_id		 = $model->bkg_id;
					$vendorProfile->vnp_attribute_type	 = $vendorProfile::TYPE_NO_SHOW;
					$vendorProfile->vnp_value_str		 = 'No Show';
					$vendorProfile->vnp_value_int		 = $model->bkg_cancel_id;
					$vendorProfile->save();
				}
			}
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * 
	 * @param integer $rtgBookingId
	 * @param integer $vendor_id
	 * @param integer $attributeType
	 * @param integer $valueInt
	 * @param string $valueStr
	 * @return boolean
	 */
	
	public static function updateAttr($vendorId, $rtgBookingId, $attributeType, $valueInt = 0, $valueStr = '')
	{
		$success = false;
		if (isset($vendorId) && $vendorId > 0)
		{
			$vendorProfile						 = new VendorProfile();
			$vendorProfile->vnp_user_id			 = $vendorId;
			$vendorProfile->vnp_booking_id		 = $rtgBookingId;
			$vendorProfile->vnp_attribute_type	 = $attributeType;
			if ($valueInt > 0)
			{
				$vendorProfile->vnp_value_int = $valueInt;
			}
			if ($valueStr != '')
			{
				$vendorProfile->vnp_value_str = $valueStr;
			}
			if ($vendorProfile->save())
			{
				$success = true;
			}
		}
		return $success;
	}

	public function getBkgByVndId($vendorId)
	{
		$sql	 = "SELECT `vnp_booking_id` FROM `vendor_profile` WHERE `vnp_user_id` = $vendorId";
		$bkgId	 = DBUtil::queryAll($sql);
		return $bkgId;
	}
        
        public function getVendorNameInitials($name)
    {
        preg_match_all('#([A-Z]+)#', $name, $capitals);
        if (count($capitals[1]) >= 2) {
            return substr(implode('', $capitals[1]), 0, 2);
        }
        return strtoupper(substr($name, 0, 2));
    }
    
    /** 
     * @var ClassName $model
     * @param int $typeAction
     * @param int $pid
     * @return array
     *  */
    public function pushApiCall($model, $typeAction = 0, $pid = null)
    {
        if($typeAction == AgentApiTracking::TYPE_TELEGRAM_VENDOR_AUTHENTICATION)
        {
            $partnerRequest	 = self::getTelegramVendorRequest($model, $typeAction, $pid);
			$partnerResponse = self::intiateTelegramRequest($partnerRequest);
            return $partnerResponse;
        }
	}
    
    /** 
     * @param array $request
     * @return array
     *  */
    public static function intiateTelegramRequest($request)
    {
        $type     = "telegramAuthentication";
        $dataList = new Stub\telegram\AuthenticationResponse();
        $dataList->setData($request);
        $getdataList = Filter::removeNull($dataList);
        $arrResponse         = [];
        $arrResponse['type'] = $type;
        $arrResponse['data'] = $getdataList;
        $responseParamList   = self::callAPI($arrResponse);
        return $response;
    }
    
    /** 
     * @var ClassName $model
     * @param int $typeAction
     * @param int $telegramId
     * @return array
     *  */
    public static function getTelegramVendorRequest($model, $typeAction, $telegramId)
    {
      
        $partnerRequest          = new PartnerRequest();
        $partnerRequest->type    = "telegramAuthentication";
        $partnerRequest->vendorId = $model->vnd_id;
        $partnerRequest->pid     = $telegramId;
        return $partnerRequest;
    }
    
    /** 
     * @param array $request
     * @return true | false
     *  */
    public static function callAPI($param)
	{
        $apiURL		 = "https://pragati.gozo.cab/api/journey-updates";
		$postData	 = json_encode($param);

		$ch					 = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
            'Authorization: ' . $authorization,
			'Content-Length: ' . strlen($postData))
		);
		$jsonResponse		 = curl_exec($ch);
		$responseParamList	 = json_decode($jsonResponse, true);
		return $responseParamList;
    }
	
	public static function addCancelAttr($bkgModel, $bcbModel, $reason)
	{
		$bkgId	 = $bkgModel->bkg_id;
		$bcbId	 = $bcbModel->bcb_id;
		$vndId	 = $bcbModel->bcb_vendor_id;

		// Denied
		$vendorProfile						 = new VendorProfile();
		$vendorProfile->vnp_user_id			 = $vndId;
		$vendorProfile->vnp_booking_id		 = $bkgId;
		$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_DENIALS;
		$vendorProfile->vnp_value_str		 = $reason;
		$vendorProfile->vnp_value_int		 = $bcbId;
		$vendorProfile->save();

		// Pickup
		$dateDiff							 = round((strtotime($bkgModel->bkg_pickup_date) - strtotime(date('Y-m-d H:i:s'))) / (60 * 60));
		$vendorProfile						 = new VendorProfile();
		$vendorProfile->vnp_user_id			 = $vndId;
		$vendorProfile->vnp_booking_id		 = $bkgId;
		$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_DENIALS_TO_PICKUP;
		$vendorProfile->vnp_value_str		 = 'Denials to Pickup';
		$vendorProfile->vnp_value_int		 = $dateDiff;
		$vendorProfile->save();

		// Assignment
		$assignmentTime						 = VendorsLog::model()->getVendorAssignmentTime($bkgId);
		$assignmentDateTime					 = round((strtotime(date('Y-m-d H:i:s')) - strtotime($assignmentTime['vlg_created'])) / (60 * 60));
		$vendorProfile						 = new VendorProfile();
		$vendorProfile->vnp_user_id			 = $vndId;
		$vendorProfile->vnp_booking_id		 = $bkgId;
		$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_DENIALS_FROM_ASSIGNMENT;
		$vendorProfile->vnp_value_str		 = 'Denials to Assignment';
		$vendorProfile->vnp_value_int		 = $assignmentDateTime;
		$vendorProfile->save();
	}

}
