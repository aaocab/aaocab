<?php

/**
 * This is the model class for table "lead_followup".
 *
 * The followings are the available columns in table 'lead_followup':
 * @property integer $lfu_id
 * @property integer $lfu_ref_type
 * @property integer $lfu_ref_id
 * @property integer $lfu_bkg_tentative_booking
 * @property string $lfu_date
 * @property double $lfu_amount
 * @property string $lfu_comment
 * @property string $lfu_cmt
 * @property string $lfu_tellus
 * @property string $lfu_phone_no
 * @property string $lfu_followup
 * @property string $lfu_pickup_date_time
 * @property string $lfu_return_date_time
 * @property integer $lfu_type
 * @property integer $lfu_status
 * @property string $lfu_create_date
 */
class LeadFollowup extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lead_followup';
	}

	public $bkg_tentative_booking, $lfu_followup, $lfu_from_date, $lfu_to_date, $locale_lfu_date, $locale_lfu_type;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lfu_type', 'validateInfo', "on" => "insertLead"),
			array('locale_lfu_type', 'validateFollowupInfo', "on" => "updateLead"),
			array('lfu_amount', 'numerical'),
			array('lfu_comment, lfu_cmt, lfu_tellus', 'length', 'max' => 4000),
			array('lfu_date, locale_lfu_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lfu_id, lfu_ref_type, lfu_ref_id, lfu_bkg_tentative_booking, lfu_date, lfu_amount, lfu_comment, 
				lfu_cmt, lfu_tellus, lfu_phone_no,lfu_followup, lfu_pickup_date_time, lfu_return_date_time, lfu_type, lfu_status, lfu_create_date, lfu_from_date, 
				lfu_to_date, locale_lfu_date, locale_lfu_type', 'safe', 'on' => 'search'),
		);
	}

	public function validateInfo($attribute, $params)
	{
		$success = true;
		if (isset($this->lfu_type) && $this->lfu_type > 0)
		{
			switch ($this->lfu_type)
			{
				case 1:
					if ($this->lfu_amount == '' || $this->lfu_cmt == '')
					{
						if ($this->lfu_cmt == '')
						{
							$this->addError('lfu_cmt', 'Please enter your input in the space given.');
						}
						if ($this->lfu_amount == '')
						{
							$this->addError('lfu_amount', 'Please enter price in the space given.');
						}
						$success = false;
					}

					break;
				case 2:
					if ($this->lfu_date == '' || $this->lfu_date == '1970-01-01' || $this->lfu_date == NULL)
					{
						$this->addError('locale_lfu_date', 'Please enter your input in the space given.');
						$success = false;
					}
					break;
				case 3:
					if ($this->lfu_tellus == '')
					{
						$this->addError('lfu_tellus', 'Please enter your input in the space given.');
						$success = false;
					}
					if ($this->lfu_phone_no == '')
					{
						$this->addError('lfu_phone_no', 'Please enter your phone number.');
						$success = false;
					}
					if (!Filter::validatePhoneNumber("+91" . $this->lfu_phone_no))
					{
						$this->addError('lfu_phone_no', 'Please enter valid phone number');
						$success = false;
					}
					break;
				case 4:
					if ($this->lfu_followup == '' || $this->lfu_followup == NULL)
					{
						$this->addError('lfu_followup', 'Please enter your input in the space given.');
						$success = false;
					}
					break;
			}
		}
		return $success;
	}

	public function validateFollowupInfo($attribute, $params)
	{
		$success = true;
		if (isset($this->locale_lfu_type) && $this->locale_lfu_type > 0)
		{
			if (($this->lfu_followup == '' || $this->lfu_followup == NULL) && $this->locale_lfu_type == 4)
			{
				$this->addError('lfu_followup', 'Please enter your input in the space given.');
				$success = false;
			}
		}
		return $success;
	}

	public function beforeValidate()
	{
		if ($this->locale_lfu_date != '' && $this->lfu_type == 2)
		{
			$this->lfu_date = DateTimeFormat::DatePickerToDate($this->locale_lfu_date);
		}

		return parent::beforeValidate();
	}

	public function afterFind()
	{
		parent::afterFind();
		if ($this->lfu_date != '' && $this->lfu_type == 2)
		{
			$this->locale_lfu_date = DateTimeFormat::DateTimeToDatePicker($this->lfu_date);
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'lfuBkg' => array(self::BELONGS_TO, 'Booking', 'lfu_ref_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lfu_id'					 => 'Lfu',
			'lfu_ref_type'				 => 'Lfu Ref Type',
			'lfu_ref_id'				 => 'Lfu Ref',
			'lfu_bkg_tentative_booking'	 => 'Lfu Bkg Tentative Booking',
			'lfu_date'					 => 'Lfu Date',
			'lfu_amount'				 => 'Lfu Amount',
			'lfu_comment'				 => 'Lfu Comment',
			'lfu_cmt'					 => 'Lfu Cmt',
			'lfu_tellus'				 => 'Lfu Tellus',
			'lfu_type'					 => 'Lfu Type',
			'lfu_status'				 => 'Lfu Status',
			'lfu_create_date'			 => 'Lfu Create Date',
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

		$criteria->compare('lfu_id', $this->lfu_id);
		$criteria->compare('lfu_ref_type', $this->lfu_ref_type);
		$criteria->compare('lfu_ref_id', $this->lfu_ref_id);
		$criteria->compare('lfu_bkg_tentative_booking', $this->lfu_bkg_tentative_booking);
		$criteria->compare('lfu_date', $this->lfu_date, true);
		$criteria->compare('lfu_amount', $this->lfu_amount);
		$criteria->compare('lfu_comment', $this->lfu_comment, true);
		$criteria->compare('lfu_cmt', $this->lfu_cmt, true);
		$criteria->compare('lfu_tellus', $this->lfu_tellus, true);
		$criteria->compare('lfu_type', $this->lfu_type);
		$criteria->compare('lfu_status', $this->lfu_status);
		$criteria->compare('lfu_create_date', $this->lfu_create_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LeadFollowup the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param integer $type
	 * @param boolean $returnFullUrl
	 * @return string
	 */
	public static function getLeadURL($bkgId, $type = 'e', $returnFullUrl = true)
	{
		$model	 = BookingTemp::model()->findByPk($bkgId);
		$hash	 = 2;
		$url	 = "";
		if ($returnFullUrl)
		{
			$url = Yii::app()->params['fullBaseURL'];
		}

		if ($type == 'e')
		{
			$eHashCode	 = Yii::app()->shortHash->hash($model->bkg_id);
			$url		 .= '/u/' . $bkgId . '/' . $hash . '/e/' . $eHashCode;
		}
		else if ($type == 'p')
		{
			$pHashCode	 = Yii::app()->shortHash->hash($model->bkg_contact_no);
			$url		 .= '/u/' . $bkgId . '/' . $hash . '/p/' . $pHashCode;
		}
		return $url;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param integer $type
	 * @return string
	 */
	public static function getUnvFollowupURL($bkgId, $type = 'e')
	{
		$model	 = Booking::model()->findByPk($bkgId);
		$email	 = $model->bkgUserInfo->bkg_user_email;
		$hash	 = 1;
		if ($type == 'e')
		{
			$eHashCode	 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verifycode_email);
			$url		 = Yii::app()->params['fullBaseURL'] . '/f/' . $bkgId . '/' . $hash . '/e/' . $eHashCode;
		}
		else if ($type == 'p')
		{
			$pHashCode	 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verification_code);
			$url		 = Yii::app()->params['fullBaseURL'] . '/f/' . $bkgId . '/' . $hash . '/p/' . $pHashCode;
		}
		return $url;
	}

	public static function getURL($bkgId, $type = 'e')
	{
		$model	 = Booking::model()->findByPk($bkgId);
		$email	 = $model->bkgUserInfo->bkg_user_email;
		$hash	 = 1;
		if ($type == 'e')
		{
			$eHashCode	 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verifycode_email);
			$url		 = Yii::app()->params['fullBaseURL'] . '/u/' . $bkgId . '/' . $hash . '/e/' . $eHashCode;
		}
		else if ($type == 'p')
		{
			$pHashCode	 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verification_code);
			$url		 = Yii::app()->params['fullBaseURL'] . '/u/' . $bkgId . '/' . $hash . '/p/' . $pHashCode;
		}
		return $url;
	}

	public function getByBkgId($bkgId)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("lfu_ref_id = '" . $bkgId . "'");
		return $this->find($criteria);
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param integer $bkgGroup
	 * @param string $desc
	 * @param array $userInfo
	 * @return boolean
	 */
	public function updateLeadForPriceHigh($bkgId, $bkgGroup = 1, $desc = '', $userInfo)
	{
		$success = false;
		$eventId = BookingLog::UNVERIFIED_FOLLOWUP_PRICE_HIGH;
		if ($bkgGroup == 1)
		{
			$model							 = Booking::model()->findByPk($bkgId);
			$oldModel						 = clone $model;
			$params['blg_booking_status']	 = $model->bkg_status;
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			$success						 = true;
		}
		else if ($bkgGroup == 2)
		{
			$eventId			 = BookingLog::FOLLOWUP_ASSIGN;
			$userInfo			 = new UserInfo();
			$userInfo->userType	 = UserInfo::TYPE_CONSUMER;

			$returnSet	 = BookingTemp::model()->updateFollowup($bkgId, LeadLog::AUTO_FOLLOWUP_REPLY_RECEIVED, $desc, $eventId, $userInfo);
			$success	 = $returnSet['success'];
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param integer $bkgGroup
	 * @param string $dateRequested
	 * @param array $userInfo
	 * @return boolean
	 */
	public function updateLeadForJustLooking($bkgId, $bkgGroup, $dateRequested, $userInfo)
	{
		$success = false;
		$eventId = BookingLog::UNVERIFIED_FOLLOWUP_LOOKING;
		$desc	 = "Customer says was looking. Will travel on " . $dateRequested . ".";
		if ($bkgGroup == 1)
		{
			$model							 = Booking::model()->findByPk($bkgId);
			$oldModel						 = clone $model;
			$params['blg_booking_status']	 = $model->bkg_status;
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			$success						 = true;
		}
		else
		{
			$eventId			 = BookingLog::FOLLOWUP_ASSIGN;
			$userInfo			 = new UserInfo();
			$userInfo->userType	 = UserInfo::TYPE_CONSUMER;

			$returnSet	 = BookingTemp::model()->updateFollowup($bkgId, LeadLog::AUTO_FOLLOWUP_REPLY_RECEIVED, $desc, $eventId, $userInfo);
			$success	 = $returnSet['success'];
		}

		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param integer $bkgGroup
	 * @param string $desc
	 * @param array $userInfo
	 * @return boolean
	 */
	public function updateLeadForOther($bkgId, $bkgGroup, $desc, $userInfo)
	{
		$success = false;
		$eventId = BookingLog::UNVERIFIED_FOLLOWUP_OTHER;
		if ($bkgGroup == 1)
		{
			$userInfo->userId				 = $model->bkgUserInfo->bkg_user_id;
			$model							 = Booking::model()->findByPk($bkgId);
			$oldModel						 = clone $model;
			$params['blg_booking_status']	 = $model->bkg_status;
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			$success						 = true;
		}
		else if ($bkgGroup == 2)
		{
			$eventId			 = BookingLog::FOLLOWUP_ASSIGN;
			$userInfo			 = new UserInfo();
			$userInfo->userType	 = UserInfo::TYPE_CONSUMER;

			$returnSet	 = BookingTemp::model()->updateFollowup($bkgId, LeadLog::AUTO_FOLLOWUP_REPLY_RECEIVED, $desc, $eventId, $userInfo);
			$success	 = $returnSet['success'];
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param integer $bkgGroup
	 * @param string $desc
	 * @param array $userInfo
	 * @return boolean
	 */
	public function updateLeadForCallMe($bkgId, $bkgGroup, $desc, $userInfo)
	{
		$success = false;
		if ($bkgGroup == 1)
		{
			$model			 = Booking::model()->findByPk($bkgId);
			$oldTrailModel	 = clone $model->bkgTrail;
			if (isset($desc) && $desc != '')
			{
				$model->bkgTrail->bkg_followup_active	 = 1;
				$model->bkgTrail->bkg_followup_date		 = new CDbExpression("NOW()");
				$userInfo								 = UserInfo::getInstance();
				$model->bkgTrail->setFollowup($desc, $oldTrailModel, $userInfo);
			}
			$success = true;
		}
		else
		{
			$eventId			 = BookingLog::FOLLOWUP_ASSIGN;
			$userInfo			 = new UserInfo();
			$userInfo->userType	 = UserInfo::TYPE_CONSUMER;

			$returnSet	 = BookingTemp::model()->updateFollowup($bkgId, LeadLog::AUTO_FOLLOWUP_REPLY_RECEIVED, $desc, $eventId, $userInfo);
			$success	 = $returnSet['success'];
		}
		return $success;
	}

	public static function showTravelDate($bkgId)
	{
		$query90Mins = "SELECT addWorkingMinutes(90,NOW())";
		$date90Mins	 = DBUtil::command($query90Mins)->queryScalar();

		$model	 = Booking::model()->findByPk($bkgId);
		$queryCs = "SELECT CalcCriticalityDate(0.75,'$model->bkg_create_date','$model->bkg_pickup_date')";
		$dateCS	 = DBUtil::command($queryCs)->queryScalar();

		$calDate = MAX($date90Mins, $dateCS);
		return Filter::getDateFormatted($calDate);
	}

	public function getList($from_date, $to_date, $command = false)
	{

		$sql = "SELECT
				IF(lead_followup.lfu_ref_type=1,CONCAT(booking_user.bkg_user_fname,' ',booking_user.bkg_user_lname),CONCAT(booking_temp.bkg_user_name,' ',booking_temp.bkg_user_lname)) as user_fullname,
				IF(lead_followup.lfu_ref_type=1,booking_user.bkg_user_email,booking_temp.bkg_user_email) as user_email,
				IF(lead_followup.lfu_ref_type=1,booking_user.bkg_contact_no,booking_temp.bkg_contact_no) as user_phone,
				IF(lead_followup.lfu_ref_type=1,booking.bkg_booking_id,booking_temp.bkg_booking_id) as booking_id,
				IF(lead_followup.lfu_ref_type=1,frmCity.cty_name,leadfrmCity.cty_name) as fromCity,
				IF(lead_followup.lfu_ref_type=1,toCity.cty_name,leadtoCity.cty_name) as toCity,
				IF(lead_followup.lfu_ref_type=1,booking.bkg_pickup_date,booking_temp.bkg_pickup_date) as pickupDate,
				IF(lead_followup.lfu_ref_type=1,booking_invoice.bkg_total_amount,booking_temp.bkg_amount) as bkgAmount,
				IF(lead_followup.lfu_type=1,lead_followup.lfu_cmt,NULL) as priceHighCmt,
				lead_followup.lfu_type,
				lead_followup.lfu_amount,
				lead_followup.lfu_create_date as createDate,
				IF(
					lead_followup.lfu_type = 1,
					'Y',
					'NA'
				) AS price_was_high,
				IF(
					lead_followup.lfu_type = 1,
					lead_followup.lfu_amount,
					'NA'
				) AS price_was_high_comment,
				IF(
					lead_followup.lfu_type = 2,
					'Y',
					'NA'
				) AS will_book_later,
				IF(
					lead_followup.lfu_bkg_tentative_booking = 1,
					lead_followup.lfu_date,
					'N-A'
				) AS will_book_later_tentative,
				IF(
					lead_followup.lfu_type = 3,
					'Y',
					'NA'
				) AS other,
				IF(
					lead_followup.lfu_type = 3,
					lead_followup.lfu_tellus,
					'NA'
				) AS other_comment,
				IF(
					lead_followup.lfu_type=4,
					lead_followup.lfu_followup,
					'NA'
				) as 'call_me_please',
				lead_followup.lfu_create_date
				FROM `lead_followup`
				LEFT JOIN `booking` ON lead_followup.lfu_ref_id = booking.bkg_id AND lead_followup.lfu_ref_type=1 
				LEFT JOIN `booking_temp` ON lead_followup.lfu_ref_id=booking_temp.bkg_id AND lead_followup.lfu_ref_type=2
				LEFT JOIN `booking_user` ON booking.bkg_id = booking_user.bui_bkg_id
				LEFT JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id 
				LEFT JOIN `cities` AS frmCity ON booking.bkg_from_city_id = frmCity.cty_id
				LEFT JOIN `cities` AS toCity ON booking.bkg_to_city_id = toCity.cty_id 
				LEFT JOIN `cities` AS leadfrmCity ON booking_temp.bkg_from_city_id = leadfrmCity.cty_id
				LEFT JOIN `cities` AS leadtoCity ON booking_temp.bkg_to_city_id = leadtoCity.cty_id 
				WHERE lead_followup.lfu_create_date BETWEEN ('$from_date 00:00:00') AND ('$to_date 23:59:59')";
		if ($command == false)
		{
			$sqlCount		 = "SELECT COUNT(*) FROM `lead_followup` WHERE lead_followup.lfu_create_date BETWEEN ('$from_date 00:00:00') AND ('$to_date 23:59:59')";
			$defaultOrder	 = ($order != '') ? $order : 'lfu_create_date DESC';
			$pageSize		 = 100;
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, array(
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => array('attributes'	 => array('user_fullname', 'user_email', 'user_phone', 'createDate', 'pickupDate'),
					'defaultOrder'	 => $defaultOrder,
				),
				'keyField'		 => 'lfu_id',
				'pagination'	 => ['pageSize' => $pageSize],
			));
			return $dataprovider;
		}
		else
		{
			$sql .= " ORDER BY lead_followup.lfu_create_date DESC";
			return DBUtil::query($sql, DBUtil::SDB());
		}
	}

	public static function getLeadsByMail()
	{
		$sql = "SELECT
					IF(lead_followup.lfu_ref_type=1,CONCAT(booking_user.bkg_user_fname,' ',booking_user.bkg_user_lname),CONCAT(booking_temp.bkg_user_name,' ',booking_temp.bkg_user_lname)) as user_fullname,
					IF(lead_followup.lfu_ref_type=1,booking_user.bkg_user_email,booking_temp.bkg_user_email) as user_email,
					IF(lead_followup.lfu_ref_type=1,booking_user.bkg_contact_no,booking_temp.bkg_contact_no) as user_phone,
					IF(lead_followup.lfu_ref_type=1,booking.bkg_booking_id,booking_temp.bkg_booking_id) as bookingId,
					IF(lead_followup.lfu_ref_type=1,frmCity.cty_name,leadfrmCity.cty_name) as fromCity,
					IF(lead_followup.lfu_ref_type=1,toCity.cty_name,leadtoCity.cty_name) as toCity,
					IF(lead_followup.lfu_ref_type=1,booking.bkg_create_date,booking_temp.bkg_create_date) as createDate,
					IF(lead_followup.lfu_ref_type=1,booking.bkg_pickup_date,booking_temp.bkg_pickup_date) as pickupDate,
					IF(lead_followup.lfu_ref_type=1,booking_invoice.bkg_total_amount,booking_temp.bkg_amount) as bkgAmount,
					lead_followup.lfu_type, lead_followup.lfu_amount,
					IF(lead_followup.lfu_type = 1, 'Y', 'NA') AS price_was_high,
					IF(lead_followup.lfu_type = 1,lead_followup.lfu_amount,'NA') AS price_was_high_comment,
					IF(lead_followup.lfu_type = 2,'Y','NA') AS will_book_later,
					IF(lead_followup.lfu_bkg_tentative_booking = 1, lead_followup.lfu_date, 'NA') AS will_book_later_tentative,
					IF(lead_followup.lfu_type = 3, 'Y', 'NA') AS other,
					IF(lead_followup.lfu_type = 3, lead_followup.lfu_tellus, 'NA') AS other_comment,
					IF(lead_followup.lfu_type=4, lead_followup.lfu_followup, 'NA') as 'call_me_please', 
					lead_followup.lfu_create_date
				FROM `lead_followup`
				LEFT JOIN `booking` ON lead_followup.lfu_ref_id = booking.bkg_id AND lead_followup.lfu_ref_type=1 
				LEFT JOIN `booking_temp` ON lead_followup.lfu_ref_id=booking_temp.bkg_id AND lead_followup.lfu_ref_type=2
				LEFT JOIN `booking_user` ON booking.bkg_id = booking_user.bui_bkg_id
				LEFT JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id 
				LEFT JOIN `cities` AS frmCity ON booking.bkg_from_city_id = frmCity.cty_id
				LEFT JOIN `cities` AS toCity ON booking.bkg_to_city_id = toCity.cty_id 
				LEFT JOIN `cities` AS leadfrmCity ON booking_temp.bkg_from_city_id = leadfrmCity.cty_id
				LEFT JOIN `cities` AS leadtoCity ON booking_temp.bkg_to_city_id = leadtoCity.cty_id
				WHERE lead_followup.lfu_create_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY), ' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY), ' 23:59:59')
				ORDER BY lead_followup.lfu_create_date DESC";
		return DBUtil::queryAll($sql);
	}

	/** 
	 * 
	 * @param LeadFollowup $model
	 * @param integer $command
	 * @return \CSqlDataProvider\
	 */
	public static function getAutoLeadFollowup($model, $command = DBUtil::ReturnType_Provider)
	{
		$sql = "SELECT
				lead_followup.lfu_create_date 	AS `lfu_create_date`,
				lead_followup.lfu_followup 	AS `lfu_followup`,
				lead_followup.lfu_id AS `lfu_id`,
				lead_followup.lfu_ref_type AS `lfu_ref_type`,
				lead_followup.lfu_ref_id AS `lfu_ref_id`,
				lead_followup.lfu_comment AS `lfu_comment`,
				lead_followup.lfu_cmt AS `lfu_cmt`,
				lead_followup.lfu_tellus AS `lfu_tellus`,
				lead_followup.lfu_type AS `lfu_type`,
				lead_followup.lfu_status AS `lfu_status`,
				booking_temp.bkg_country_code AS `bkg_country_code`,
				booking_temp.bkg_contact_no AS `bkg_contact_no`,
				booking_temp.bkg_user_email AS `bkg_user_email`,
				booking_temp.bkg_booking_id AS `bkg_booking_id`,
				booking_temp.bkg_pickup_date AS `bkg_pickup_date` 
				FROM `lead_followup` 
				INNER JOIN `booking_temp` ON lead_followup.lfu_ref_id = booking_temp.bkg_id
				WHERE lead_followup.lfu_create_date BETWEEN '$model->lfu_from_date' AND '$model->lfu_to_date'";
		
		$sqlCount = "SELECT COUNT(1) FROM ( 
						SELECT
						lead_followup.lfu_create_date 	AS `lfu_create_date`,
						lead_followup.lfu_followup 	AS `lfu_followup`,
						lead_followup.lfu_id AS `lfu_id`,
						lead_followup.lfu_ref_type AS `lfu_ref_type`,
						lead_followup.lfu_ref_id AS `lfu_ref_id`,
						lead_followup.lfu_comment AS `lfu_comment`,
						lead_followup.lfu_cmt AS `lfu_cmt`,
						lead_followup.lfu_tellus AS `lfu_tellus`,
						lead_followup.lfu_type AS `lfu_type`,
						lead_followup.lfu_status AS `lfu_status`,
						booking_temp.bkg_country_code AS `bkg_country_code`,
						booking_temp.bkg_contact_no AS `bkg_contact_no`,
						booking_temp.bkg_user_email AS `bkg_user_email`,
						booking_temp.bkg_booking_id AS `bkg_booking_id`,
						booking_temp.bkg_pickup_date AS `bkg_pickup_date` 
						FROM `lead_followup` 
						INNER JOIN `booking_temp` ON lead_followup.lfu_ref_id = booking_temp.bkg_id
						WHERE lead_followup.lfu_create_date BETWEEN '$model->lfu_from_date' AND '$model->lfu_to_date') a";
		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => [], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

}
