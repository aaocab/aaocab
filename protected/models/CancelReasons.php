<?php

/**
 * This is the model class for table "cancel_reasons".
 *
 * The followings are the available columns in table 'cancel_reasons':
 * @property integer $cnr_id
 * @property string $cnr_reason
 * @property integer $cnr_show_admin
 * @property string $cnr_admin_text
 * @property integer $cnr_show_user
 * @property string $cnr_user_text
 * @property string $cnr_user_desc_text
 * @property string $cnr_internal_reason
 * @property string $cnr_mark_initiator
 * @property integer $cnr_penalize_customer
 * @property integer $cnr_penalize_vendor
 * @property string $cnr_customer_sms
 * @property string $cnr_vendor_sms
 * @property string $cnr_admin_sms
 * @property string $cnr_created
 * @property integer $cnr_active
 * @property integer $cnr_is_dbo_applicable
 */
class CancelReasons extends CActiveRecord
{

	const USER_TYPE_CUSTOMER	 = 1;
	const USER_TYPE_ADMIN		 = 2;
	const USER_TYPE_VENDOR	 = 3;
	const USER_TYPE_DRIVER	 = 4;

	const CR_BOOKING_RESCHEDULED = 24;

	public $smsTemplate, $whatsappTemplate;
	public $options = [
		'cnr_show_admin'		 => ['0' => 'Dont Show', '1' => 'Show'],
		'cnr_show_user'			 => ['0' => 'Dont Show', '1' => 'Show'],
		'cnr_mark_initiator'	 => ['1' => 'Consumer', '2' => 'Admin', '3' => 'Vendor', '4' => 'Driver'],
		'cnr_penalize_customer'	 => ['0' => 'NA', '1' => 'No', '2' => 'Yes', '3' => 'Give Option'],
		'cnr_penalize_vendor'	 => ['0' => 'NA', '1' => 'No', '2' => 'Yes', '3' => 'Give Option']
	];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cancel_reasons';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cnr_reason, cnr_created', 'required'),
			array('cnr_show_admin, cnr_show_user, cnr_penalize_customer, cnr_penalize_vendor, cnr_active', 'numerical', 'integerOnly' => true),
			array('cnr_reason, cnr_admin_text, cnr_user_text, cnr_internal_reason, cnr_customer_sms, cnr_vendor_sms, cnr_admin_sms', 'length', 'max' => 255),
			array('cnr_user_desc_text', 'length', 'max' => 500),
			array('cnr_mark_initiator', 'length', 'max' => 20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cnr_id, cnr_reason, cnr_show_admin, cnr_admin_text, cnr_show_user, cnr_user_text, cnr_user_desc_text, cnr_internal_reason, cnr_mark_initiator, cnr_penalize_customer, cnr_penalize_vendor, cnr_customer_sms, cnr_vendor_sms, cnr_admin_sms, cnr_created, cnr_active, cnr_is_dbo_applicable', 'safe', 'on' => 'search'),
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
			'cnr_id'				 => 'ID',
			'cnr_reason'			 => 'Reason',
			'cnr_show_admin'		 => 'Show Admin',
			'cnr_admin_text'		 => 'Admin Text',
			'cnr_show_user'			 => 'Show User',
			'cnr_user_text'			 => 'User Text',
			'cnr_user_desc_text'	 => 'User Desc Text',
			'cnr_internal_reason'	 => 'Internal Reason',
			'cnr_mark_initiator'	 => 'Mark Initiator',
			'cnr_penalize_customer'	 => 'Penalize Customer',
			'cnr_penalize_vendor'	 => 'Penalize Vendor',
			'cnr_customer_sms'		 => 'Customer Sms',
			'cnr_vendor_sms'		 => 'Vendor Sms',
			'cnr_admin_sms'			 => 'Admin Sms',
			'cnr_created'			 => 'Created',
			'cnr_active'			 => 'Active',
			'cnr_is_dbo_applicable'	 => 'Is DBO Applicable',
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

		$criteria->compare('cnr_id', $this->cnr_id);
		$criteria->compare('cnr_reason', $this->cnr_reason, true);
		$criteria->compare('cnr_show_admin', $this->cnr_show_admin);
		$criteria->compare('cnr_admin_text', $this->cnr_admin_text, true);
		$criteria->compare('cnr_show_user', $this->cnr_show_user);
		$criteria->compare('cnr_user_text', $this->cnr_user_text, true);
		$criteria->compare('cnr_user_desc_text', $this->cnr_user_desc_text, true);
		$criteria->compare('cnr_internal_reason', $this->cnr_internal_reason, true);
		$criteria->compare('cnr_mark_initiator', $this->cnr_mark_initiator, true);
		$criteria->compare('cnr_penalize_customer', $this->cnr_penalize_customer);
		$criteria->compare('cnr_penalize_vendor', $this->cnr_penalize_vendor);
		$criteria->compare('cnr_customer_sms', $this->cnr_customer_sms, true);
		$criteria->compare('cnr_vendor_sms', $this->cnr_vendor_sms, true);
		$criteria->compare('cnr_admin_sms', $this->cnr_admin_sms, true);
		$criteria->compare('cnr_created', $this->cnr_created, true);
		$criteria->compare('cnr_active', $this->cnr_active);
		$criteria->compare('cnr_is_dbo_applicable', $this->cnr_is_dbo_applicable);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CancelReasons the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByUserType($type = '')
	{
		//1=>customer; 2 =>admin
		$criteria = new CDbCriteria;
		if ($type == 1)
		{
			$criteria->select = array("cnr_id,cnr_user_text,cnr_user_desc_text");
			$criteria->compare('cnr_show_user', 1);
		}
		if ($type == 2)
		{
			$criteria->select = array("cnr_id,cnr_admin_text");
			$criteria->compare('cnr_show_admin', 1);
		}
		$criteria->compare('cnr_active', 1);
		return $this->findAll($criteria);
	}

	public function getActiveList()
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = array("cnr_id, cnr_reason");
		$criteria->compare('cnr_active', 1);
		return $this->findAll($criteria);
	}

	public function getInternalList()
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = array("cnr_id, cnr_internal_reason");
		$criteria->compare('cnr_active', 1);
		return $this->findAll($criteria);
	}

	public function getUserDescTextbyId($id)
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = array("cnr_user_desc_text");
		$criteria->compare('cnr_id', $id);
		$criteria->compare('cnr_active', 1);
		$model				 = $this->find($criteria);
		return $model->cnr_user_desc_text;
	}

	public function getListbyUserType($type = '')
	{
		//1=>customer; 2 =>admin
		$arr		 = [];
		$arrOther	 = [];
		if ($type == 1)
		{
			$arr[0]	 = CHtml::listData($this->getByUserType($type), 'cnr_id', 'cnr_user_text');
			//$arrOther[4] = $arr[0][4];
			unset($arr[0][4]);
			$arr[0]	 = $arr[0] + $arrOther;
			$arr[1]	 = CHtml::listData($this->getByUserType($type), 'cnr_id', 'cnr_user_desc_text');
		}
		if ($type == 2)
		{
			$arr		 = CHtml::listData($this->getByUserType($type), 'cnr_id', 'cnr_admin_text');
			$arrOther[4] = $arr[4];
			unset($arr[4]);
			$arr		 = $arr + $arrOther;
		}
		return $arr;
	}

	public function getSMSTemplate($type, $crid, $bkgid, $excl = true, $penaltyCharge = '')
	{
		//'1' => 'Consumer', '2' => 'Admin', '3' => 'Vendor', '4' => 'Driver'
		$criteria = new CDbCriteria;
		if ($type == self::USER_TYPE_CUSTOMER)
		{
			$criteria->select = "cnr_customer_sms as smsTemplate";
		}
		if ($type == self::USER_TYPE_VENDOR)
		{
			$criteria->select = "cnr_vendor_sms as smsTemplate";
		}
		if ($type == self::USER_TYPE_DRIVER)
		{
			$criteria->select = "cnr_driver_sms as smsTemplate";
		}
		$criteria->compare('cnr_id', $crid);
		$criteria->compare('cnr_active', 1);
		$model		 = $this->find($criteria);
		$template	 = $model->smsTemplate;

		$bkgModel = Booking::model()->getByCode($bkgid);
		if (!$bkgModel)
		{
			$bkgModel = Booking::model()->findByPk($bkgid);
		}
		if ($excl)
		{
			$template = $this->excludePolicy($template);
		}
		else if ($penaltyCharge != '')
		{
			$template = str_replace("__AMOUNT__", 'Rs.' . $penaltyCharge, $template);
		}
		$firstName	 = $bkgModel->bkgUserInfo->bkg_user_fname;
		$lastName	 = $bkgModel->bkgUserInfo->bkg_user_lname;

		$customer = (strlen(trim($firstName)) > 3) ? $firstName : $bkgModel->bkgUserInfo->getUsername();

//		if ($bkgModel->bkg_vendor_id) {
//			$vendor = ($bkgModel->bkgVendor->vnd_owner) ? $bkgModel->bkgVendor->vnd_owner : $bkgModel->bkgVendor->vnd_name;
//		}
//		if ($bkgModel->bkg_driver_id) {
//			$driver = $bkgModel->bkgDriver->drv_name;
//		}
		$vendor	 = '';
		$driver	 = '';

		$bookingCab = $bkgModel->getBookingCabModel();


		if ($bookingCab != '' && $bookingCab->bcbVendor && $type != self::USER_TYPE_CUSTOMER)
		{
			/* @var $bookingCab BookingCab */
			//$vendor	 = ($bookingCab->bcbVendor->vnd_owner) ? $bookingCab->bcbVendor->vnd_owner : $bookingCab->bcbVendor->vnd_owner;
			$vendor = $bookingCab->bcbVendor->vndContact->getName();
			if ($bookingCab->bcbDriver)
			{
				$driver = $bookingCab->bcbDriver->drv_name;
			}
		}

		$bookingCode = $bkgModel->bkg_booking_id;

		$template = str_replace("__CUSTOMER__", $customer, $template);
		if ($vendor != '')
		{
			$template = str_replace("__VENDOR__", $vendor, $template);
		}
		if ($driver != '')
		{
			$template = str_replace("__DRIVER__", $driver, $template);
		}
		$template = str_replace("__ID__", $bookingCode, $template);


		return $template;
	}

	public function excludePolicy($template)
	{
		$fPos	 = strpos($template, '[');
		$lPos	 = strpos($template, ']');
		if ($fPos && $lPos)
		{
			$len		 = $lPos - $fPos + 1;
			$template	 = substr_replace($template, '', $fPos, $len);
		}
		return $template;
	}

	public function excludeCancellationCharge($cancelId)
	{
		$success = false;
		if (in_array($cancelId, [8, 9, 11, 16, 17, 19, 22, 26, 28, 33, 35]))
		{
			$success = true;
		}
		return $success;
	}

	public function getlist($search = true)
	{

		$sql	 = "SELECT cnr_id id ,cnr_reason text  FROM cancel_reasons WHERE cnr_show_admin =1  AND cnr_show_user= 0 AND cnr_active = 1
					UNION
					SELECT cnr_id id,cnr_reason text  FROM cancel_reasons WHERE cnr_show_user =1 AND cnr_show_admin=0 AND cnr_active = 1";
		$rows	 = array();
		$rows	 = DBUtil::command($sql)->queryAll();
		$zArr	 = [];
		foreach ($rows as $row)
		{
			$zArr[$row['id']] = $row['text'];
		}
		return $zArr;
	}
	public static function getById($id)
	{
		$sql = "SELECT cnr_reason FROM cancel_reasons 
				WHERE cnr_active = 1 AND cnr_id = $id";
		$recordset	 = DBUtil::queryRow($sql);
		return $recordset;
	}
	
	/**
	 * @param integer $id
	 * @return integer
	 */
	public static function isDboApplicable($id)
    {
        $sql   = "SELECT cnr_is_dbo_applicable FROM cancel_reasons WHERE cnr_active = 1 AND cnr_id = :id";
        return DBUtil::command($sql, DBUtil::SDB())->bindParam(':id', $id)->queryScalar();        
    }
    
   /**
	 * This function is used for getting cancel reason id
	 * @return queryObject array
	 */
    public static function getNoShowId()
    {
        $sql = "SELECT cnr_reason, cnr_id  FROM `cancel_reasons` WHERE `cnr_id` = 37 AND cnr_active = 1";
        $result	 = DBUtil::queryRow($sql, DBUtil::SDB());
		return $result;
    }

	/**
	 * This function is used for getting rules for penalized customer
	 * @return queryObject array
	 */
	public static function getCustomerPenalizeRuleById($id)
	{
		$sql = "SELECT cnr_penalize_customer FROM `cancel_reasons` WHERE `cnr_id` = :id AND cnr_active = 1";
        return DBUtil::command($sql, DBUtil::SDB())->bindParam(':id', $id)->queryScalar();  
	}

	/**
	 * This function is used for getting rules for penalized vendor
	 * @return queryObject array
	 */
	public static function getVendorPenalizeRuleById($id)
	{
		$sql = "SELECT cnr_penalize_vendor FROM `cancel_reasons` WHERE `cnr_id` = :id AND cnr_active = 1";
        return DBUtil::command($sql, DBUtil::SDB())->bindParam(':id', $id)->queryScalar();  
	}
        
        /**
	 * This function is used for getting cancel reason id
	 * @return queryObject array
	 */
        public static function getDriverNoShowId()
        {
            $sql = "SELECT cnr_reason, cnr_id  FROM `cancel_reasons` WHERE `cnr_id` = 22 AND cnr_active = 1";
            $result	 = DBUtil::queryRow($sql, DBUtil::SDB());
            return $result;
        }

		/**
		* This function is used for getting cancel reason id for mmt flash booking i.e TFR booking
		* @return queryObject array
		*/
		public static function getTFRCancelReason()
		{
			$sql = "SELECT cnr_reason, cnr_id  FROM `cancel_reasons` WHERE `cnr_id` = 42 AND cnr_active = 1";
            $result	 = DBUtil::queryRow($sql, DBUtil::SDB());
            return $result;
		}

		/**
		* This function is used for getting free cancel reason id
		* @return queryObject array
		*/
		public static function getTransferzCancelId()
		{
			$sql = "SELECT cnr_reason, cnr_id  FROM `cancel_reasons` WHERE `cnr_id` = 43 AND cnr_active = 1";
            $result	 = DBUtil::queryRow($sql, DBUtil::SDB());
            return $result;
		}

		/**
		* This function is used for getting cancel with cost reason id
		* @return queryObject array
		*/
		public static function getTransferzCancelWithNoCost()
		{
			$sql = "SELECT cnr_reason, cnr_id  FROM `cancel_reasons` WHERE `cnr_id` = 44 AND cnr_active = 1";
            $result	 = DBUtil::queryRow($sql, DBUtil::SDB());
            return $result;
		}

		public static function getReasonById($id)
		{
			$sql = "SELECT cnr_reason FROM cancel_reasons WHERE cnr_active = 1 AND cnr_id = $id";
			$recordset	 = DBUtil::queryScalar($sql, DBUtil::SDB3());
			return $recordset;
		}
		
		public function getWhatsappTemplate($type, $crid, $bkgid)
		{
			$criteria = new CDbCriteria;
			if ($type == self::USER_TYPE_CUSTOMER)
			{
				$criteria->select = "cnr_customer_whatsapp as whatsappTemplate";
			}
			
			$criteria->compare('cnr_id', $crid);
			$criteria->compare('cnr_active', 1);
			$model		 = $this->find($criteria);
			$template	 = $model->whatsappTemplate;

			$bkgModel = Booking::model()->getByCode($bkgid);
			if (!$bkgModel)
			{
				$bkgModel = Booking::model()->findByPk($bkgid);
			}
			return $template;
		}

	public static function applicableDBOCompensation($cancelId)
	{
		$success = false;
		if (in_array($cancelId, [6, 9, 17, 22, 31, 38, 40, 41]))
		{
			$success = true;
		}
		return $success;
	}

}
