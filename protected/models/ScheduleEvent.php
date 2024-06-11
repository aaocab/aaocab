<?php

/**
 * This is the model class for table "schedule_event".
 *
 * The followings are the available columns in table 'schedule_event':
 * @property integer $sde_id
 * @property integer $sde_ref_type
 * @property integer $sde_ref_id
 * @property integer $sde_event_id
 * @property integer $sde_event_status
 * @property string $sde_schedule_time
 * @property string $sde_addtional_data
 * @property string $sde_remarks
 * @property string $sde_event_sequence
 * @property string $sde_create_date
 * @property string $sde_update_date
 * @property integer $sde_err_count
 * @property string $sde_last_error
 */
class ScheduleEvent extends CActiveRecord
{

	//event
	const CONFIRM_MESSAGE								 = 101;
	const REFUND_PROCESS								 = 102;
	const MARK_COMPLETE_PROCESS						 = 103;
	const SEND_DRIVER_DETAILS							 = 104;
	const TRACK_DRIVER_SYNC							 = 105;
	const PARTNER_PENDING_ADVANCE						 = 106;
	const SEND_NOTIFICATION_DATA						 = 107;
	const SEND_BOOKING_INVOICE						 = 108;
	const DRIVER_APP_PENALTY							 = 109;
	const GENERATE_QR_CODE							 = 110;
	const BOOKING_VND_COMPENSATION					 = 111;
	// Event Notification
	const BOOKING_CAB_DRIVER_ASSIGNMNET				 = 200;
	const BOOKING_DRIVER_TO_CUSTOMER					 = 201;
	const BOOKING_PAYMENT_RECEIVED_CUSTOMER			 = 202;
	const BOOKING_REVIEW								 = 203;
	const BOOKING_REVIEW_OTHER						 = 204;
	const BOOKING_CANCEL_TO_DRIVER					 = 205;
	const BOOKING_DETAILS_TO_DRIVER					 = 206;
	const BOOKING_DETAILS_TO_VENDOR					 = 207;
	const DEPENDENCY_BOOST_TO_VENDOR					 = 208;
	const BOOKING_QUOTE_EXPIRY_REMINDER_TO_CUSTOMER	 = 209;
	const DRIVER_ALREADY_REGISTRED					 = 210;
	const DRIVER_COMPLETE_REGISTRATION_REMINDER		 = 211;
	const VENDOR_APPROVED								 = 212;
	const REVIEW_BONUS_DRIVER							 = 213;
	const CUSTOMER_REFERRALS							 = 214;
	const CUSTOMER_GOZONOW							 = 215;
	const CUSTOMER_ONGOING_TRIP						 = 216;
	const UNASSIGNED_TRIP_DETAILS_TO_VENDOR			 = 217;
	const VENDOR_ACCOUNT_BLOCKED						 = 218;
	const VENDOR_ACCOUNT_UNBLOCKED					 = 219;
	const VENDOR_DUES_WAIVED_OFF						 = 220;
	const REMINDER_FOR_INCOMPLETE_LEADS				 = 221;
	const VENDOR_ATTACHEMENT_SOCIAL_LINK				 = 222;
	const VENDOR_FREEZE								 = 223;
	const VENDOR_UNFREEZE								 = 224;
	const NEW_BOOKING_CHANNEL_PARTNER					 = 225;
	const DTM_BOOKING									 = 226;
	const VENDOR_PAYMENT_RELEASE						 = 227;
	const BOOKING_LEAD_FOLLOWUP						 = 228;
	const LOGIN_FORGOT_WEB_OTP						 = 229;
	const CUSTOMER_DOUBLE_BACK_OFFER					 = 230;
	const VIP_BOOKING									 = 231;
	const BOOKING_CONFIRM								 = 232;
	const BOOKING_PAYMENT_RECEIVED					 = 233;
	const PAYMENT_REQUEST_SENT						 = 234;
	const BOOKING_QUOTE_CREATED						 = 235;
	const BOOKING_CONFIRM_WITHOUT_PAY					 = 236;
	const BOOKING_CONFIRM_WITH_PAY					 = 237;
	const TRIP_AMOUNT_RESET							 = 238;
	const VENDOR_EXPIRY_DOCS							 = 239;
	const VENDOR_REJECTED_DOCS						 = 240;
	const VENDOR_NOT_LOGIN							 = 241;
	const USER_CHECK_RATE								 = 242;
	const USER_ADDRESS_UPDATE							 = 243;
	const USER_COIN_EXPIRE							 = 244;
	const USER_COIN_RECHARGE							 = 245;
	const UDPATE_BOOKING_ADDRESS						 = 246;
	const PRICE_LOCK_EXPIRING							 = 247;
	const PRICE_LOCK_EXPIRED							 = 248;
	const LAST_INQUIRY_LAST_TRAVELED					 = 249;
	//status
	const STATUS_PENDING								 = 0;
	const STATUS_PROCESSED							 = 1;
	const STATUS_FAILED								 = 2;
	const STATUS_REPROCESSED							 = 3;
	const STATUS_REPROCESSED_FAILED					 = 4;
	//smt score
	const SMT_SCORE_PROCESS							 = 501;
	// Reference Type
	const BOOKING_REF_TYPE							 = 1;
	const TRIP_REF_TYPE								 = 2;
	const DRIVER_REF_TYPE								 = 3;
	const VENDOR_REF_TYPE								 = 4;
	const CUSTOMER_REF_TYPE							 = 5;
	const LEAD_REF_TYPE								 = 6;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'schedule_event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sde_ref_id,sde_event_id,sde_ref_type', 'required'),
			array('sde_ref_id,sde_event_id, sde_event_status,sde_ref_type', 'numerical', 'integerOnly' => true),
			//array('sde_schedule_time', 'length', 'max' => 50),
			array('sde_addtional_data', 'length', 'max' => 2000),
			array('sde_remarks', 'length', 'max' => 500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sde_id, sde_ref_id, sde_ref_type,sde_event_id, sde_event_status, sde_schedule_time, sde_addtional_data, sde_remarks, sde_create_date, sde_update_date, sde_err_count, sde_last_error,sde_event_sequence', 'safe', 'on' => 'search'),
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
			'sde_id'			 => 'Schedule Id',
			'sde_ref_id'		 => 'Schedule Ref Id',
			'sde_ref_type'		 => 'Schedule Ref Type',
			'sde_event_id'		 => 'Schedule event Id',
			'sde_event_status'	 => 'Schedule Status',
			'sde_schedule_time'	 => 'Schedule Time',
			'sde_addtional_data' => 'Schedule Addtional Data',
			'sde_remarks'		 => 'Schedule  Remarks',
			'sde_create_date'	 => 'Create Date',
			'sde_update_date'	 => 'Update Date',
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

		$criteria->compare('sde_id', $this->sde_id);
		$criteria->compare('sde_ref_id', $this->sde_ref_id);
		$criteria->compare('sde_ref_type', $this->sde_ref_type);
		$criteria->compare('sde_event_id', $this->sde_event_id);
		$criteria->compare('sde_event_status', $this->sde_event_status);
		$criteria->compare('sde_schedule_time', $this->sde_schedule_time, true);
		$criteria->compare('sde_addtional_data', $this->sde_addtional_data, true);
		$criteria->compare('sde_remarks', $this->sde_remarks, true);
		$criteria->compare('sde_create_date', $this->sde_create_date, true);
		$criteria->compare('sde_update_date', $this->sde_update_date, true);
		$criteria->compare('sde_event_sequence', $this->sde_event_sequence, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ScheduleEvent the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function EventList()
	{
		$eventlist = [
			101	 => 'Confirm Process',
			102	 => 'Refund Process',
			103	 => 'Mark Complete Process',
			106	 => 'Partner Pending Advance',
			108	 => 'Send Booking Invoice',
			109	 => 'Driver App Uses Penalty',
			110	 => 'Generate QR Code',
			111	 => 'Booking Vendor Compensation',
			200	 => 'Booking Cab Driver Assignment',
			244	 => 'Coin Expire',
			245	 => 'Coin Recharge'
		];
		asort($eventlist);
		return $eventlist;
	}

	public function getEventByEventId($eventId)
	{
		$list = $this->EventList();
		return $list[$eventId];
	}

	/**
	 * This function is used add event to schedule event
	 * @param integer $refId
	 * @param integer $refType 1=>booking,2=>trip
	 * @param integer $eventId
	 * @param string $remarks
	 * @param string $additionalData
	 * @param string $time

	 * @return query Objects
	 */
	public static function add($refId, $refType, $eventId, $remarks, $additionalData = null, $time = null, $sequence = null)
	{
		try
		{
			$event				 = ScheduleEvent::model()->getEventByEventId($eventId);
			$getScheduleBooking	 = ScheduleEvent::checkScheduleEvent($refId, $eventId, $refType, $sequence);
			if (!$getScheduleBooking)
			{
				$model						 = new ScheduleEvent();
				$model->sde_ref_id			 = $refId;
				$model->sde_ref_type		 = $refType;
				$model->sde_event_id		 = $eventId;
				$model->sde_remarks			 = $remarks;
				$model->sde_addtional_data	 = $additionalData;
				$model->sde_event_sequence	 = $sequence;
				$model->sde_schedule_time	 = $time != null ? date('Y-m-d H:i:s', strtotime('+' . $time . ' minutes')) : date('Y-m-d H:i:s', strtotime('+' . 10 . ' minutes'));
				$model->sde_event_status	 = 0;
				if (!$model->save())
				{
					throw new Exception($event . "failed to initiate : " . json_encode($model->getErrors()));
				}
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
		}
	}

	/**
	 * This function is used for getting  event list by  event id and with reference Type
	 * @param integer $refId
	 * @param integer $eventId
	 * @param integer $refType 1=>booking,2=>trip
	 * @param integer $sequence 1=>Whatapp,2=>sms ,3=>Email ,4=>App
	 * @return query Objects
	 */
	public static function checkScheduleEvent($refId, $eventId, $refType = null, $sequence = null)
	{
		$param	 = ['refId' => $refId, 'eventId' => $eventId, 'refType' => $refType, 'sequence' => $sequence];
		$sql	 = "SELECT * FROM schedule_event WHERE sde_ref_id=:refId AND sde_event_id =:eventId AND sde_event_status=0 AND sde_ref_type=:refType AND sde_event_sequence=:sequence  ";
		$result	 = DBUtil::queryRow($sql, DBUtil::MDB(), $param);
		return $result;
	}

	/**
	 * This function is used for getting  event list by  event id and with reference Type
	 * @param integer eventId
	 * @param integer $refType 1=>booking,2=>trip
	 * @return query Objects
	 */
	public static function getEventList($eventIds, $refTypes = ScheduleEvent::BOOKING_REF_TYPE)
	{
		DBUtil::getINStatement($eventIds, $bindStringEvent, $paramsEvent);
		DBUtil::getINStatement($refTypes, $bindStringRefType, $paramsRefType);

		$sql = "SELECT 
						sde_id,
						sde_ref_id,
						sde_addtional_data,
						sde_event_id,
						sde_event_sequence
                    FROM   schedule_event
                    WHERE  1
						AND sde_schedule_time <= NOW() 
						AND sde_event_status IN (0,2) 
						AND sde_err_count<=2 
						AND sde_event_id IN ({$bindStringEvent})
						AND sde_ref_type IN ({$bindStringRefType})
					ORDER BY sde_schedule_time";

		Logger::writeToConsole($sql);

		$records = DBUtil::query($sql, DBUtil::SDB(), array_merge($paramsEvent, $paramsRefType));
		return $records;
	}

}
