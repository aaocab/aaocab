<?php

/**
 * This is the model class for table "lead_log".
 *
 * The followings are the available columns in table 'lead_log':
 * @property integer $blg_id
 * @property integer $blg_booking_id
 * @property string $blg_desc
 * @property string $blg_admin_id
 * @property integer $blg_vendor_id
 * @property string $blg_created
 *
 * The followings are the available model relations:
 * @property Admins $blgAdmin
 * @property Vendors $blgVendor
 * @property integer $blg_follow_up_status
 * @property string $blg_desc
 */
class LeadLog extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lead_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public $follow_up_status, $blg_created1, $blg_created2, $executive, $total_converted,
			$total_followed, $total_inactive, $total_followed_distinct, $converted_ratio, $inactive_ratio;
	public $fromDate, $toDate, $cntDays;

	//const CALL_ME_PLEASE	 = 20; // AUTOFOLLOUP REPLY RECEIVED
	const AUTO_FOLLOWUP_SENT			 = 21;
	const AUTO_FOLLOWUP_REPLY_RECEIVED = 20;

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('blg_booking_id', 'required'),
			array('blg_booking_id, blg_vendor_id', 'numerical', 'integerOnly' => true),
			array('blg_desc', 'length', 'max' => 500),
			array('blg_admin_id', 'length', 'max' => 11),
			array('blg_created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('blg_desc,blg_follow_up_status,blg_id, blg_booking_id, blg_desc, blg_admin_id, blg_vendor_id, blg_created', 'safe'),
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
			'blgAdmin'	 => array(self::BELONGS_TO, 'Admins', 'blg_admin_id'),
			'blgVendor'	 => array(self::BELONGS_TO, 'Vendors', 'blg_vendor_id'),
			'blgBooking' => [self::BELONGS_TO, 'BookingTemp', 'blg_booking_id'],
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'blg_id'				 => 'Log Id',
			'blg_booking_id'		 => 'Booking Id',
			'blg_desc'				 => 'Log Desc',
			'blg_admin_id'			 => 'Admin',
			'blg_vendor_id'			 => 'Vendor',
			'blg_created'			 => 'Log Created',
			'blg_follow_up_status'	 => 'follow up status',
			'blg_desc'				 => 'Desc/Remark',
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
		$criteria->compare('blg_id', $this->blg_id);
		$criteria->compare('blg_booking_id', $this->blg_booking_id);
		$criteria->compare('blg_desc', $this->blg_desc, true);
		$criteria->compare('blg_admin_id', $this->blg_admin_id, true);
		$criteria->compare('blg_vendor_id', $this->blg_vendor_id);
		$criteria->compare('blg_created', $this->blg_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function getLeadEvents($status = '')
	{
		$statusArr = array(
			0	 => 'Not followed up',
			1	 => 'Call back later',
			2	 => 'Not Responding, Call back later',
			3	 => 'Interested, will book later',
			4	 => 'Already Booked',
			5	 => 'Not interested',
			6	 => 'Booked Somewhere Else',
			7	 => 'Invalid Lead',
			8	 => 'Unsupported city request',
			9	 => 'Lead Expired, Customer did not respond',
			10	 => 'Lead Expired, CSR could not followup in time',
			13	 => 'Lead Converted to Booking',
			20	 => 'Call customer. Auto-follow reply received',
			21	 => 'Auto-followup sent'
		);
		if ($status != '')
		{
			return $statusArr[$status];
		}

		return $statusArr;
	}

	public function getDailyLeadReportData()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria			 = new CDbCriteria;
		$criteria->compare('blg_id', $this->blg_id);
		$criteria->compare('blg_booking_id', $this->blg_booking_id);
		$criteria->compare('blg_desc', $this->blg_desc, true);
		$criteria->compare('blg_admin_id', $this->blg_admin_id);
		$criteria->addBetweenCondition('date(blg_created)', $this->blg_created1, $this->blg_created2, true);
		$criteria->with		 = ['blgAdmin' => ['select' => ['adm_fname', 'adm_lname']], 'blgBooking'];
		$criteria->together	 = true;
		return new CActiveDataProvider($this->together(), array(
			'criteria' => $criteria
		));
	}

	public function getDailyLeadReportCount()
	{
		$randomNumber			 = rand();
		$createTable			 = "csr_booking_created_preformance$randomNumber";
		$confirmTable			 = "csr_booking_confirm_preformance$randomNumber";
		$leadTable				 = "csr_lead_performance$randomNumber";
		$bookingFollowupTable	 = "csr_booking_followup_performance$randomNumber";

		DBUtil::dropTempTable($createTable);
		$sql = " (INDEX my_index_name (bkg_create_user_id))
				             SELECT   date_format(booking.`bkg_create_date`, '%Y-%m-%d') AS date
						   , btr.bkg_create_user_id
						   , count(distinct if(hour(booking.`bkg_create_date`) < 2, NULL, cast(booking.`bkg_create_date` as date))) AS cntDays
						   , sum(if(btr.`bkg_create_type` = 3, 1, 0)) AS cntSelfCreated
						   , sum(if(btr.`bkg_create_type` = 1, 1, 0)) AS cntQuoteCreated
						   , sum(if(btr.`bkg_create_type` = 2, 1, 0)) AS cntLeadCreated
						   , sum(if(btr.`bkg_create_type` IN (1,2), 1, 0)) AS cntTotalQuoted
						   , sum(if(btr.`bkg_confirm_type` in (1, 2, 3, 5) and booking.`bkg_status` in (2, 3, 5, 6, 7, 9) and booking_invoice.`bkg_advance_amount` > 0, 1, 0)) AS cntTotalQuoteConfirmed
						   , sum(if(btr.`bkg_confirm_type` = 4, 1, 0)) AS cntSelfConfirmed
						   , sum(if(btr.`bkg_confirm_type` = 1, 1, 0)) AS cntQuoteConfirmed
						   , sum(if(btr.`bkg_confirm_type` = 2, 1, 0)) AS cntUnverifiedConfirmed
						   , sum(if(btr.`bkg_confirm_type` = 3, 1, 0)) AS cntLeadConfirmed
						   , sum(if(btr.`bkg_confirm_type` = 5, 1, 0)) AS cntUnverifiedQuoteConfirmed
						   , sum(if(booking.`bkg_status` not in (1, 10, 9, 15), booking_invoice.`bkg_total_amount` - booking_invoice.`bkg_service_tax` - booking_invoice.`bkg_credits_used` - if(booking_cab.`bcb_trip_type` = 0, booking_cab.`bcb_vendor_amount`, booking_invoice.`bkg_vendor_amount`), 0)) AS gozoAmount
				  FROM     booking 
				  JOIN booking_trail btr on (booking.`bkg_id` = btr.`btr_bkg_id`) 
				  JOIN booking_invoice on (booking.`bkg_id` = booking_invoice.`biv_bkg_id` and booking.`bkg_agent_id` is null) 
				  JOIN booking_cab on (booking_cab.`bcb_id` = booking.`bkg_bcb_id`) 
				  WHERE    booking.`bkg_status` in (1, 2, 3, 5, 6, 7, 9, 10, 15) AND btr.`bkg_create_user_type` = 4 and btr.`bkg_create_type` in (1, 2)
				   AND bkg_create_date BETWEEN '{$this->fromDate} 00:00:00' AND '{$this->toDate} 23:59:59'
				  GROUP BY  btr.bkg_create_user_id;";
		DBUtil::createTempTable($createTable, $sql);


		DBUtil::dropTempTable($confirmTable);
		$sql = "(INDEX my_index_name (bkg_confirm_user_id))
							SELECT   date_format(bkg_confirm_datetime, '%Y-%m-%d') AS date
						   , btr.bkg_confirm_user_id
						   , count(distinct if(hour(booking.`bkg_create_date`) < 2, NULL, cast(booking.`bkg_create_date` as date))) AS cntDays
						   , sum(if(btr.`bkg_create_type` = 3, 1, 0)) AS cntSelfCreated
						   , sum(if(btr.`bkg_create_type` = 1, 1, 0)) AS cntQuoteCreated
						   , sum(if(btr.`bkg_create_type` = 2, 1, 0)) AS cntLeadCreated
						   , sum(if(btr.`bkg_confirm_type` in (1, 2, 3, 5) and booking.`bkg_status` in (2, 3, 5, 6, 7, 9) and booking_invoice.`bkg_advance_amount` > 0, 1, 0)) AS cntTotalConfirmed
						   , sum(if(btr.`bkg_confirm_type` = 4, 1, 0)) AS cntSelfConfirmed
						   , sum(if(btr.`bkg_confirm_type` = 1, 1, 0)) AS cntQuoteConfirmed
						   , sum(if(btr.`bkg_confirm_type` = 2, 1, 0)) AS cntUnverifiedConfirmed
						   , sum(if(btr.`bkg_confirm_type` = 3, 1, 0)) AS cntLeadConfirmed
						   , sum(if(btr.`bkg_confirm_type` = 5, 1, 0)) AS cntUnverifiedQuoteConfirmed
						   , sum(if(booking.`bkg_status` not in (1, 10, 9, 15), booking_invoice.`bkg_total_amount` - booking_invoice.`bkg_service_tax` - booking_invoice.`bkg_credits_used` - if(booking_cab.`bcb_trip_type` = 0, booking_cab.`bcb_vendor_amount`, booking_invoice.`bkg_vendor_amount`), 0)) AS gozoAmount
							FROM     booking 
							JOIN booking_trail btr on (booking.`bkg_id` = btr.`btr_bkg_id`) 
							JOIN booking_invoice on (booking.`bkg_id` = booking_invoice.`biv_bkg_id` and booking.`bkg_agent_id` is null) 
							JOIN booking_cab on (booking_cab.`bcb_id` = booking.`bkg_bcb_id`) 
							WHERE    booking.`bkg_status` in (2, 3, 5, 6, 7, 9) AND btr.`bkg_confirm_user_type` = 4 and btr.`bkg_confirm_type` in (1, 2, 3,4,5)
						    AND btr.bkg_confirm_datetime BETWEEN '{$this->fromDate} 00:00:00' AND '{$this->toDate} 23:59:59'
							GROUP BY btr.bkg_confirm_user_id;";
		DBUtil::createTempTable($confirmTable, $sql);


		DBUtil::dropTempTable($leadTable);
		$sql = "(INDEX my_index_name (blg_admin_id))
								SELECT
								blg_admin_id,
								COUNT(blg_booking_id) total_followed,
								COUNT(distinct blg_booking_id) total_followed_distinct,
								SUM(IF(btemp.bkg_follow_up_status in(4, 5, 6, 7, 8, 9, 10, 14), 1, 0)) total_inactive,
								SUM(IF(btemp.bkg_follow_up_status in(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 14, 15, 16), 1, 0)) total_active_inactive,
								SUM(IF(btemp.bkg_follow_up_status in(0, 1, 2, 3, 15, 16), 1, 0)) total_active
								FROM lead_log
								INNER JOIN booking_temp btemp on btemp.bkg_id = blg_booking_id
								WHERE  lead_log.blg_created BETWEEN '{$this->fromDate} 00:00:00' AND '{$this->toDate} 23:59:59'
								GROUP BY blg_admin_id";

		DBUtil::createTempTable($leadTable, $sql);

		DBUtil::dropTempTable($bookingFollowupTable);
		$sql = "(INDEX my_index_name (blg_user_id))
				SELECT
					blg_user_id ,
					COUNT(blg_booking_id) cntBookingFollowup ,
					COUNT(DISTINCT blg_booking_id) cntUniqueBookingFollowup
				FROM
					booking_log
				INNER JOIN booking ON bkg_id = blg_booking_id  AND bkg_status IN(1,2,3,5,6,7,9,10,15)
				INNER JOIN booking_trail ON btr_bkg_id = blg_booking_id AND ( blg_created < bkg_confirm_datetime OR bkg_confirm_datetime IS NULL )
				WHERE
					blg_user_type = 4 AND blg_user_id IS NOT NULL AND blg_created BETWEEN '{$this->fromDate} 00:00:00' AND '{$this->toDate} 23:59:59'
					AND blg_event_id IN( 86 ,87 ,88 )
				GROUP BY
					blg_user_id";
		DBUtil::createTempTable($bookingFollowupTable, $sql);

		$sql = "SELECT adm_id as adminid,
					CONCAT(adm_fname, ' ', adm_lname) AS executive,
					cat.cntDays AS cntDays,
					IF(led.total_followed IS NULL,0,led.total_followed) total_lead_followed,
					IF(led.total_followed_distinct IS NULL,0,led.total_followed_distinct) total_lead_followed_distinct,
					IF(led.total_inactive IS NULL,0,led.total_inactive) total_inactive,
					IF(((cat.cntLeadCreated/ led.total_followed_distinct)* 100) IS NULL,0,((cat.cntLeadCreated/ led.total_followed_distinct)* 100)) converted_ratio,
					IF(((led.total_inactive/ led.total_active_inactive)* 100) IS NULL,0,((led.total_inactive/ led.total_active_inactive)* 100)) inactive_ratio,
					IF(((led.total_active/ led.total_active_inactive)* 100) IS NULL,0,((led.total_active/ led.total_active_inactive)* 100)) active_ratio,
					IF(cat.cntLeadCreated IS NULL,0,cat.cntLeadCreated) cntLeadCreated,
					IF(cat.cntTotalQuoted IS NULL,0,cat.cntTotalQuoted) cntTotalQuoted,
					IF(cat.cntTotalQuoteConfirmed IS NULL,0,cat.cntTotalQuoteConfirmed) cntTotalQuoteConfirmed,
					IF(con.cntTotalConfirmed IS NULL,0,con.cntTotalConfirmed) cntTotalConfirmed,
					IF(con.cntLeadConfirmed IS NULL,0,con.cntLeadConfirmed) cntLeadConfirmed,
					IF(bfl.cntBookingFollowup IS NULL,0,bfl.cntBookingFollowup) cntBookingFollowup,
					IF(bfl.cntUniqueBookingFollowup IS NULL,0,bfl.cntUniqueBookingFollowup) cntUniqueBookingFollowup,
					'{$this->fromDate}' as fromDate,
					'{$this->toDate}' as toDate
				FROM admins
				LEFT JOIN $leadTable led ON blg_admin_id=adm_id
				LEFT JOIN $createTable cat ON cat.bkg_create_user_id = adm_id
				LEFT JOIN $confirmTable con ON con.bkg_confirm_user_id = adm_id
				LEFT JOIN $bookingFollowupTable bfl ON bfl.blg_user_id = adm_id
				WHERE cat.date IS NOT NULL OR con.date IS NOT NULL OR led.blg_admin_id IS NOT NULL OR bfl.blg_user_id IS NOT NULL
				GROUP BY adm_id";

		$sqlCount = "SELECT CONCAT(adm_fname, ' ', adm_lname) AS executive
				FROM admins
				LEFT JOIN $leadTable led ON blg_admin_id=adm_id
				LEFT JOIN $createTable cat ON cat.bkg_create_user_id = adm_id
				LEFT JOIN $confirmTable con ON con.bkg_confirm_user_id = adm_id
				LEFT JOIN $bookingFollowupTable bfl ON bfl.blg_user_id = adm_id
				WHERE cat.date IS NOT NULL OR con.date IS NOT NULL OR led.blg_admin_id IS NOT NULL OR bfl.blg_user_id IS NOT NULL
			    GROUP BY adm_id";

		if ($this->executive != '')
		{
			$sql		 .= " having executive LIKE '%" . $this->executive . "%'";
			$sqlCount	 .= " having executive LIKE '%" . $this->executive . "%'";
		}
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['executive',
					'total_converted', 'total_followed', 'total_followed_distinct', 'total_inactive',
					'converted_ratio', 'inactive_ratio', 'cntTotalQuoted', 'cntTotalQuoteConfirmed', 'cntTotalConfirmed',
					'cntBookingFollowup', 'cntUniqueBookingFollowup'
				],
				'defaultOrder'	 => 'cntTotalQuoteConfirmed DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);
		return ['dataprovider' => $dataprovider, 'createTable' => $createTable, 'confirmTable' => $confirmTable, 'leadTable' => $leadTable, 'bookingFollowupTable' => $bookingFollowupTable];
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LeadLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByBookingId($bkgid)
	{
		$criteria		 = new CDbCriteria;
		//$criteria->compare('blg_booking_id', $bkgid);
		$criteria->addCondition("blg_booking_id IN ($bkgid)");
		$criteria->with	 = ['blgAdmin'];
		$criteria->order = 'blg_created';
		return $this->findAll($criteria);
	}

	public function createLog($bkgid, $desc, UserInfo $userInfo = null, $new_remark = '', $followStatus = '', $eventid = '', $ref_id = null)
	{
		// LeadLog::model()->createLog($bkgid, $desc, $userInfo, $eventid);
		$bookingLog = new LeadLog();
		if ($userInfo == null)
		{
			$userInfo = UserInfo::model();
		}
		$user_id					 = $userInfo->userId;
		$bookingLog->blg_booking_id	 = $bkgid;
		$bookingLog->blg_desc		 = $desc;
		if ($userInfo->userType == 2)
		{
			$bookingLog->blg_vendor_id = $user_id;
		}
		elseif ($userInfo->userType == 4)
		{
			$bookingLog->blg_admin_id = $user_id;
		}
		if ($new_remark != '')
		{
			$descRemark				 = $bookingLog->blg_desc;
			$descRemark				 = ($descRemark != '') ? ($descRemark . "\n Remarks: " . $new_remark) : ("Remarks: " . $new_remark);
			$bookingLog->blg_desc	 = $descRemark;
		}
		if ($followStatus != '')
		{
			$bookingLog->blg_follow_up_status = $followStatus;
		}
		if ($eventid != '')
		{
			$bookingLog->blg_event_id = $eventid;
		}
		if ($ref_id != '')
		{
			$bookingLog->blg_ref_id = $ref_id;
		}
		$bookingLog->save();
	}

	public function remarkCron($admin, $dateTime, $bkg_id, $follow_up_status, $comment)
	{
		$model						 = new LeadLogCron();
		$model->blg_booking_id		 = $bkg_id;
		$model->blg_admin_id		 = $admin;
		$model->blg_created			 = $dateTime;
		$model->blg_remarks			 = $comment;
		$model->blg_follow_up_status = $follow_up_status;
		$model->save();
	}

	public function deleteTemporaryTable($createTable, $confirmTable, $leadTable, $bookingFollowupTable)
	{
		$sql			 = "DROP TABLE IF EXISTS $createTable;";
		$creTableDeleted = DBUtil::execute($sql);

		$sql			 = "DROP TABLE IF EXISTS $confirmTable;";
		$conTableDeleted = DBUtil::execute($sql);

		$sql				 = "DROP TABLE IF EXISTS $leadTable;";
		$leadTableDeleted	 = DBUtil::execute($sql);

		$sql						 = "DROP TABLE IF EXISTS $bookingFollowupTable;";
		$bookingFollowupTableDeleted = DBUtil::execute($sql);
	}

	/**
	 * Function for archiving LeadLog 
	 */
	public static function archiveLeadLogData($archiveDB, $resQ)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			DBUtil::getINStatement($resQ, $bindString, $params);
			$sql	 = "INSERT INTO " . $archiveDB . ".lead_log (SELECT * FROM lead_log WHERE blg_booking_id IN ($bindString))";
			$rows	 = DBUtil::execute($sql, $params);
			if ($rows > 0)
			{
				$sql = "DELETE FROM `lead_log` WHERE blg_booking_id IN ($bindString)";
				DBUtil::execute($sql, $params);
				DBUtil::commitTransaction($transaction);
			}
			else
			{
				DBUtil::rollbackTransaction($transaction);
			}
			return true;
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			DBUtil::rollbackTransaction($transaction);
			return false;
		}
	}

}
