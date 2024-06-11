<?php

/**
 * This is the model class for table "sms_log".
 *
 * The followings are the available columns in table 'sms_log':
 * @property integer $id
 * @property string $number
 * @property string $message
 * @property string $message2
 * @property integer $status
 * @property string $date_sent
 * @property string $booking_id
 * @property integer $recipient
 * @property string $delivery_response
 * @property string $slg_type
 * @property varchar $slg_ref_id
 * @property integer $slg_ref_type
 * @property integer $slg_provider_type
 */
class SmsLog extends CActiveRecord
{

	const Consumers								  = 1;
	const Vendor									  = 2;
	const Driver									  = 3;
	const Admin									  = 4;
	const MeterDown								  = 5;
	const Agent									  = 6;
	const Corporate								  = 7;
	const UnregVendor								  = 8;
	const SMS_BOOKING_CREATED						  = 1;   // gotBooking()
	const SMS_VENDOR_ASSIGNED						  = 2;   // assignVendor()
	const SMS_USER_CAB_DETAILS_UPDATED			  = 3;   // msgToUserBookingConfirmed()
	const SMS_VENDOR_CAB_DETAILS_UPDATED_NO_CONTACT = 41; // pickupDetailsToDriver()
	const SMS_DRIVER_CAB_DETAILS_UPDATED_NO_CONTACT = 51; // pickupDetailsToDriver()
	const SMS_VENDOR_CAB_DETAILS_UPDATED			  = 4; // pickupDetailsToDriver()
	const SMS_DRIVER_CAB_DETAILS_UPDATED			  = 5; // pickupDetailsToDriver()
	const SMS_BOOKING_VERIFICATION_CODE			  = 6;  // sendVerification()
	const SMS_BOOKING_CONFIRM						  = 7;   // gotBooking()
	const SMS_USER_ACCOUNT_CONFIRM				  = 8; //
	const SMS_VENDOR_ONLINE_PAYMENT				  = 9;
	const SMS_DRIVER_ONLINE_PAYMENT				  = 10;
	const SMS_MISSING_DRIVER_CAR					  = 11;
	const SMS_CUSTOMER_BEFORE_PICKUP				  = 12;
	const COMPENSATE_DRIVER_ON_RATING				  = 13;
	const SMS_UNVERIFIED_FOLLOWUP					  = 14;
	const DRIVER_ADHERE_TO_QUALITY				  = 15;
	const PRE_AUTO_CANCEL_BEFORE_PICKUP			  = 16;
	const POST_AUTO_CANCEL_BEFORE_PICKUP			  = 17;
	const VENDOR_FROZEN							  = 18;
	const RECONFIRM_BEFORE_PICKUP					  = 19;
	const VENDOR_BLOCKED							  = 20;
	const VENDOR_ADMINISTRATIVE_FREEZED			  = 21;
	const VENDOR_ADMINISTRATIVE_UNFREEZED			  = 22;
	const SMS_PROMOTIONAL_CASHBCK					  = 23;
	const SMS_PAYMENT_SUCCESS						  = 24;
	const SMS_VENDOR_DRIVER_PAPER_REJECTED		  = 25;
	const SMS_VENDOR_CAR_PAPER_REJECTED			  = 26;
	const SMS_VENDOR_LAST_ACTIVE					  = 27;
	const SMS_DRIVER_BONUS						  = 28;
	const SMS_FOR_UNREGISTERED_VENDORS			  = 29;
	const SMS_LEAD_FOLLOWUP						  = 30;
	const SMS_UNVERIFIED_FINAL_FOLLOWUP			  = 31;
	const SMS_LOGIN_REGISTER						  = 32;
	const SMS_TUTORIAL_LINK						  = 33;
	const SMS_APPROVE_VENDOR						  = 34;
	const SMS_FORGET_PASSWORD						  = 35;
	const SMS_UPDATE_ADDRESS_REMINDER				  = 36;
	const REF_BOOKING_ID							  = 1;
	const REF_USER_ID								  = 2;
	const REF_VENDOR_ID							  = 3;
	const REF_DRIVER_ID							  = 4;
	const SMS_CONTENT_BLANK						  = 3;
	const SMS_NATIONAL_PROVIDER_TYPE				  = 0;
	const SMS_INTERNATIONAL_PROVIDER_TYPE			  = 1;
	const SMS_MAX_ALLOWED							  = 6;

	public $sendDate1, $sendDate2;
	public $recipient_arr = ['1' => 'Consumer', '2' => 'Vendor', '3' => 'Driver', '4' => 'Admin', '5' => 'MeterDown', '6' => 'Agent', '7' => 'Corporate'];
	public $smsTypeArr	  = ['1'	 => 'Booking Created',
		'2'	 => 'Vendor Assigned',
		'3'	 => 'User - Cab Details Updation',
		'4'	 => 'Vendor - Cab Details Updation',
		'5'	 => 'Driver - Cab Details Updation',
		'6'	 => 'Sent Verification Code',
		'7'	 => 'Booking Confirmation',
		'8'	 => 'User Account Confirmation',
		'9'	 => 'Vendor for advance payment',
		'10' => 'Driver for advance payment',
		'11' => 'Missing Driver/Car Information',
		'12' => 'Customer Before Pickup',
		'14' => 'Unverified Followup',
		'15' => 'Quality sms',
		'18' => 'Vendor is Frozen',
		'19' => 'Customer Before Pickup',
		'20' => 'SMS Vendor on Blocked',
		'21' => 'Admininstrative Freezed',
		'22' => 'Admininstrative Unfreezed',
		'23' => 'Promotional Cashback',
		'24' => 'Advance Payment',
		'25' => 'SMS Vendor on Driver paper Rejected',
		'26' => 'SMS Vendor on Car paper Rejected',
		'27' => 'SMS Vendor for last active',
		'28' => 'SMS Driver for bonus',
		'29' => 'SMS for unregistered vendors'
	];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sms_log';
	}

	public function defaultScope()
	{
		$arr = array();
		return $arr + array('order' => 'date_sent DESC');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('number', 'required', 'on' => 'insert'),
			//  array('number', 'length', 'min' => 12),
			//array('number,recipient', 'numerical', 'integerOnly' => true),
			array('message, delivery_response', 'length', 'max' => 4000),
			array('booking_id', 'length', 'max' => 225),
			array('date_sent,recipient', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, number, message, status, date_sent,recipient, booking_id, delivery_response,slg_type,slg_ref_type,slg_ref_id,message2,sendDate1,sendDate2,slg_provider_type', 'safe', 'on' => 'search'),
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
			'id'				=> 'ID',
			'number'			=> 'Number',
			'message'			=> 'Message',
			'date_sent'			=> 'Date Sent',
			'booking_id'		=> 'Booking Id',
			'recipient'			=> 'Recipient',
			'delivery_response' => 'Delivery Response',
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

		$criteria->compare('id', $this->id);
		$criteria->compare('number', $this->number, true);
		$criteria->compare('message', $this->message, true);
		$criteria->compare('date_sent', $this->date_sent, true);
		$criteria->compare('booking_id', $this->booking_id, true);
		$criteria->compare('recipient', $this->recipient);
		$criteria->compare('delivery_response', $this->delivery_response, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SmsLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function fetchList()
	{
		$sql	  = "SELECT number,message , booking_id ,recipient,delivery_response,date_sent,LOCATE('otp',message) otpIndex FROM sms_log WHERE 1 ";
		$sqlCount = "SELECT id FROM sms_log WHERE 1 ";
		$params	  = array();
		if ($this->number != '')
		{
			$phrase_mod = trim(preg_replace('/[><()~*:"&|@+-]/', '', trim($this->number)));
			$length		= strlen($phrase_mod);
			if ($length > 10)
			{
				$countryCode = substr($phrase_mod, -$length, 2);
			}
			if ($countryCode == '91')
			{
				$params['number'] = $phrase_mod . "*";
				$sql			  .= " AND  MATCH(number) AGAINST(:number IN BOOLEAN MODE)";
				$sqlCount		  .= " AND  MATCH(number) AGAINST(:number IN BOOLEAN MODE)";
			}
			elseif ($countryCode != '91' && $length > 10)
			{
				$params['number'] = $phrase_mod . "*";
				$sql			  .= " AND  MATCH(number) AGAINST(:number IN BOOLEAN MODE)";
				$sqlCount		  .= " AND  MATCH(number) AGAINST(:number IN BOOLEAN MODE)";
			}
			else
			{
				$params['number'] = "91" . $phrase_mod . "*";
				$sql			  .= " AND  MATCH(number) AGAINST(:number IN BOOLEAN MODE)";
				$sqlCount		  .= " AND  MATCH(number) AGAINST(:number IN BOOLEAN MODE)";
			}
		}
		if ($this->message != '')
		{
			$phrase_mod		   = trim(preg_replace('/[><()~*:"&|@+-]/', '', trim($this->message)));
			$params['message'] = $phrase_mod . "*";
			$sql			   .= " AND MATCH(message)  AGAINST(:message IN BOOLEAN MODE)";
			$sqlCount		   .= " AND MATCH(message)  AGAINST(:message IN BOOLEAN MODE)";
		}

		if ($this->booking_id != '')
		{
			$phrase_mod			  = trim(preg_replace('/[><()~*:"&|@+-]/', '', trim($this->booking_id)));
			$params['booking_id'] = $phrase_mod . "*";
			$sql				  .= " AND MATCH(booking_id) AGAINST(:booking_id IN BOOLEAN MODE )";
			$sqlCount			  .= " AND MATCH(booking_id) AGAINST(:booking_id  IN BOOLEAN MODE )";
		}
		if ($this->sendDate1 != '' && $this->sendDate2 != '')
		{
			$sql	  .= " AND date_sent BETWEEN '" . $this->sendDate1 . " 00:00:00' AND '" . $this->sendDate2 . " 23:59:59' ";
			$sqlCount .= " AND date_sent BETWEEN '" . $this->sendDate1 . " 00:00:00' AND '" . $this->sendDate2 . " 23:59:59' ";
		}
		$count		  = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB(), $params);
		$dataprovider = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'params'		 => $params,
			'sort'			 => ['attributes'   => [],
				'defaultOrder' => 'id DESC'
			],
			'pagination'	 => ['pageSize' => 200],
		]);
		return $dataprovider;
	}

	public function getRecipientName()
	{
		$userArr = $this->recipient_arr;
		return $userArr[$this->recipient];
	}

	public function getRecipient($recipient)
	{
		$userArr = $this->recipient_arr;
		return $userArr[$recipient];
	}

	public function getSmsType($slgId)
	{
		$list = $this->smsTypeArr;
		return $list[$slgId];
	}

	public function statusUpdateById($slgId, $response, $provider = 0)
	{
		$model					  = SmsLog::model()->resetScope()->findByPk($slgId);
		$model->status			  = 1;
		$model->delivery_response = $response;
		$model->slg_provider_type = $provider > 0 ? $provider : 0;
		if ($model->save())
		{
			return true;
		}
		else
		{
			Logger::info("error updating sms log: ($response) " . json_encode($model->getErrors()));
			$e = ReturnSet::getModelValidationException($model);
			Logger::exception($e);
			return false;
		}
		//$sql = "UPDATE `sms_log` SET sms_log.status=1,sms_log.delivery_response='".mysql_escape_string($response)."' WHERE sms_log.id=$slgId";
		//$statusRecord = DBUtil::command($sql)->execute();
		//return $statusRecord;
	}

	public function sentInactiveSms()
	{
		$sql  = "SELECT id,slg_phn_code,slg_phn_number,message FROM `sms_log` WHERE sms_log.status=2  AND sms_log.date_sent <= NOW() ORDER BY `sms_log`.`id` DESC";
		$data = DBUtil::query($sql, DBUtil::SDB());
		foreach ($data as $d)
		{
			$sms	= new Messages();
			$ext	= $d['slg_phn_code'];
			$number = $d['slg_phn_number'];
			$msg	= $d['message'];
			$res	= $sms->sendMessage($ext, $number, $msg, 0);
			if ($res != '')
			{
				$this->statusUpdateById($d['id'], $res["smsProviderResponse"], $res["smsProvider"]);
			}
			Logger::info($number . " - " . $res . "\n");
		}
	}

	public function showRefTypes($bkgId, $refId)
	{
		$arrResults	   = DBUtil::command("SELECT DISTINCT sms_log.recipient FROM `sms_log` WHERE slg_ref_id=$bkgId AND id=$refId")->queryAll();
		$arrRecipients = $this->recipient_arr;
		$strRecipients = "";
		foreach ($arrResults as $value)
		{
			$strRecipients = $strRecipients . $arrRecipients[$value['recipient']] . ", ";
		}
		return "(" . rtrim($strRecipients, ", ") . ")";
	}

	public function getCountVerifySms($bkgId)
	{
		return DBUtil::command("SELECT count(*) from sms_log WHERE slg_ref_id=$bkgId AND `slg_type`=6 AND status=1")->queryScalar();
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return integer
	 */
	public static function checkBookingConfirmed($bkgId)
	{
		$sql = "SELECT COUNT(1) AS chkConfirmSms
				FROM `booking`
				INNER JOIN `sms_log` ON sms_log.slg_ref_id = booking.bkg_id AND sms_log.slg_type = 1
				WHERE booking.bkg_id = '$bkgId' 
				AND booking.bkg_active = 1 
				AND sms_log.message LIKE '%Cab request received%'";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar();
	}

	/**
	 * Function for archiving SMS log data
	 */
	public function archiveSMSContentData($archiveDB)
	{
		$status		= SmsLog::SMS_CONTENT_BLANK;
		$i			= 0;
		$chk		= true;
		$totRecords = 100000;
		$limit		= 1000;
		while ($chk)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$sql  = "SELECT GROUP_CONCAT(id) AS id FROM (SELECT id FROM sms_log WHERE status NOT IN ($status) AND date_sent < CONCAT(DATE_SUB(CURDATE(), INTERVAL 6 MONTH), ' 23:59:59') ORDER BY id LIMIT 0, $limit) as temp";
				$resQ = DBUtil::command($sql, DBUtil::MDB())->queryScalar();
				if (!is_null($resQ) && $resQ != '')
				{
					$sql  = "INSERT INTO " . $archiveDB . ".sms_log (SELECT * FROM sms_log WHERE id IN ($resQ))";
					$rows = DBUtil::command($sql)->execute();
					if ($rows > 0)
					{
						$sql		= "Update sms_log set message='',status=$status WHERE id IN ($resQ)  ";
						$rowsUpdate = DBUtil::command($sql)->execute();
					}
				}
				DBUtil::commitTransaction($transaction);
				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $e)
			{
				echo $e->getMessage() . "\n\n";
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function deleteSMS()
	{
		$status		= SmsLog::SMS_CONTENT_BLANK;
		$i			= 0;
		$chk		= true;
		$totRecords = 100000;
		$limit		= 1000;
		while ($chk)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$sql  = "SELECT GROUP_CONCAT(id) AS id FROM (SELECT id FROM sms_log WHERE status IN ($status) AND date_sent < CONCAT(DATE_SUB(CURDATE(), INTERVAL 12 MONTH), ' 23:59:59') ORDER BY id LIMIT 0, $limit) as temp";
				$resQ = DBUtil::command($sql, DBUtil::MDB())->queryScalar();
				if (!is_null($resQ) && $resQ != '')
				{
					$sql		= "DELETE from sms_log WHERE id IN ($resQ)";
					$rowsUpdate = DBUtil::command($sql)->execute();
				}
				DBUtil::commitTransaction($transaction);
				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $e)
			{
				echo $e->getMessage() . "\n\n";
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public static function sendMsgToVendorNotLoggedInLastDays()
	{
		$sql	 = "SELECT DISTINCT vnd_id, vrs_last_logged_in, phn_phone_no, phn_is_primary 
					FROM vendors 
					INNER JOIN vendor_pref ON vnd_id = vnp_vnd_id 
					INNER JOIN `vendor_stats` ON vnd_id = vrs_vnd_id 
					INNER JOIN contact_profile ON vnd_id = cr_is_vendor AND cr_status = 1 
					INNER JOIN contact_phone ON cr_contact_id = phn_contact_id AND phn_active = 1 AND phn_is_verified = 1 
					WHERE vnd_active = 1 AND `vrs_last_logged_in` <= DATE_SUB(NOW(), INTERVAL 100 DAY) AND vnp_is_freeze = 0 
					AND vrs_last_logged_in > '2020-03-01 00:00:00' 
					ORDER BY `vnd_id` DESC LIMIT 0, 2500";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$ext	  = '91';
			$number	  = $row['phn_phone_no'];
			$msg	  = 'Dear Gozo Vendor, you have not logged into the app and, many bookings are pending to be assigned, so please login to GozoCabs Vendor App and resume business.';
			$usertype = SmsLog::Vendor;

			$sms = new Messages();
			$res = $sms->sendMessage($ext, $number, $msg, 0);
			smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype);
		}
	}

	public static function getCountVendorAssignedSms($bkgId, $number, $type = '')
	{
		$sql   = "SELECT count(*) from sms_log WHERE booking_id=:id AND number=:number AND slg_type=:type AND status=1";
		$count = DBUtil::command($sql, DBUtil::SDB())->queryScalar(['id' => $bkgId, 'number' => $number, 'type' => $type]);
		return $count;
	}

	/**
	 * This function is used for to count the send message by type.
	 * @param type $type
	 * @return boolean|int
	 */
	public static function getCountByType($phncode, $type, $phnNumber = '', $noOfHRvalid = 0, $sendType = 0)
	{
		$condition = ' AND 1=1';
		if ($type == '')
		{
			return false;
		}

		if ($phnNumber != "" && $sendType != 1)
		{
			$condition .= " AND number=$phnNumber";
		}
		if ($phncode != 91 && $sendType == 1)
		{
			$condition .= " AND slg_phn_code<>91 ";
		}
		if ($noOfHRvalid == 0 || $noOfHRvalid == null)
		{
			$noOfHRvalid = 1;
		}

		$condition .= " AND date_sent>=DATE_SUB(NOW(), INTERVAL $noOfHRvalid HOUR)";

		$sql   = "SELECT count(1) from sms_log WHERE slg_type=:slg_type AND status=1 $condition";
		$count = DBUtil::queryScalar($sql, DBUtil::SDB(), ['slg_type' => $type]);

		return $count;
	}

	public static function checkBlockedNumber($mobile)
	{
		$sql = "SELECT COUNT(1) cnt FROM unsubscribe_phoneno WHERE upn_mobile = '{$mobile}'";
		return DBUtil::queryScalar($sql);
	}

	/**
	 * This function is used for to count the send message by type.
	 * @param type $type
	 * @return boolean|int
	 */
	public static function getProviderDetails($type = SmsLog::SMS_NATIONAL_PROVIDER_TYPE)
	{
		$where = $type > 0 ? "  AND slg_phn_code != 91 " : "  AND slg_phn_code = 91 ";
		$sql   = "SELECT 
				SUM(if(provider_type=1,1,0) ) AS `slg_provider_type_1`,
				SUM(if(provider_type=2,1,0) ) AS `slg_provider_type_2`,
				SUM(if(provider_type=3,1,0) ) AS `slg_provider_type_3`,
				SUM(if(provider_type=4,1,0) ) AS `slg_provider_type_4`
				FROM
				(
					SELECT 
							slg_provider_type AS provider_type
					FROM sms_log
					WHERE 1 
						AND slg_provider_type >0 
						AND status=1  
						$where
					ORDER BY id DESC 	
					LIMIT 0,10
				) AS temp   WHERE 1 ";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}
}
