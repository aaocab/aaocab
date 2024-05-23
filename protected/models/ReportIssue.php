<?php

/**
 * This is the model class for table "report_issue".
 *
 * The followings are the available columns in table 'report_issue':
 * @property integer $rpi_id
 * @property integer $rpi_type
 * @property string $rpi_name
 * @property string $rpi_date
 * @property integer $rpi_active
 */
class ReportIssue extends CActiveRecord
{

	public $reportIssueType = [1 => 'Safety', 2 => 'Driver Behaviour', 3 => 'Car Issues', 4 => 'Ride Related', 5 => 'Payment Issues', 6 => 'Other'];
	public $report_issue_desc;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'report_issue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rpi_type, rpi_date', 'required'),
			array('rpi_type, rpi_active', 'numerical', 'integerOnly' => true),
			array('rpi_name', 'length', 'max' => 1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rpi_id, rpi_type, rpi_name, rpi_date, rpi_active', 'safe', 'on' => 'search'),
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
			'rpi_id'	 => 'Rpi',
			'rpi_type'	 => 'Rpi Type',
			'rpi_name'	 => 'Rpi Name',
			'rpi_date'	 => 'Rpi Date',
			'rpi_active' => 'Rpi Active',
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

		$criteria->compare('rpi_id', $this->rpi_id);
		$criteria->compare('rpi_type', $this->rpi_type);
		$criteria->compare('rpi_name', $this->rpi_name, true);
		$criteria->compare('rpi_date', $this->rpi_date, true);
		$criteria->compare('rpi_active', $this->rpi_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReportIssue the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getType()
	{
		$key	 = "getReportIssue";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}

		$sql	 = "SELECT rpi_id,rpi_name FROM report_issue WHERE rpi_type = 0 AND rpi_active = 1";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $value)
		{
			$arr[$value['rpi_id']] = $value['rpi_name'];
		}

		$data = json_encode($arr);
		Yii::app()->cache->set($key, $data, 60 * 60 * 24 * 30, new CacheDependency("ReportIssue"));
		result:


		return $data;
	}

	public static function getDetails($rpitype)
	{
		$key	 = "getReportIssueDetails" . $rpitype;
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$params	 = ['rpitype' => $rpitype];
		$sql	 = "SELECT * FROM report_issue WHERE FIND_IN_SET(:rpitype,rpi_type) AND rpi_active = 1";
		$result	 = DBUtil::query($sql, DBUtil::SDB(), $params);

		foreach ($result as $value)
		{
			$arr[$value['rpi_id']] = $value['rpi_name'];
		}
		$data = json_encode($arr);

		Yii::app()->cache->set($key, $data, 60 * 60 * 24 * 30, new CacheDependency("ReportIssueDetails"));
		result:
		return $data;
	}

	/** 
	 * 
	 * @param integer $bkgid
	 * @return integer
	 */
	public static function checkStatusToShowIssue($bkgid)
	{
		$params	 = ['bkgid' => $bkgid];
		$sql	 = "SELECT bkg_id 
                    FROM booking INNER JOIN booking_track ON bkg_id = btk_bkg_id
                    WHERE bkg_id =:bkgid AND bkg_status IN (5,6,7) 
                    AND (bkg_pickup_date <= NOW() OR (bkg_ride_start = 1 AND bkg_trip_start_time < NOW() AND bkg_trip_end_time IS NULL))
                    AND (
                        (bkg_return_date IS NOT NULL AND DATE_ADD(bkg_return_date, INTERVAL 30 DAY) >= NOW()) 
                        OR
                        (DATE_ADD(DATE_ADD(bkg_pickup_date, INTERVAL 30 DAY), INTERVAL bkg_trip_duration MINUTE) >= NOW())
                    )";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}


	/** 
	 * 
	 * @param integer $bkgId
	 * @return type
	 */
	public static function checkStatusForSos($bkgId)
	{
		$params	 = ['bkgId' => $bkgId];
		$sql  = "SELECT 
				booking_track.bkg_sos_sms_trigger as isSOS, booking.bkg_id
				FROM `booking`
				INNER JOIN `booking_pref` ON booking.bkg_id = booking_pref.bpr_bkg_id 
				INNER JOIN `booking_track` ON booking.bkg_id = booking_track.btk_bkg_id AND booking_track.bkg_ride_complete = 0 AND booking_track.bkg_is_no_show = 0
				INNER JOIN `booking_user` ON booking.bkg_id = booking_user.bui_bkg_id 
				WHERE bkg_id =:bkgId 
				AND booking.bkg_status IN (5,6,7) 
				AND booking.bkg_active = 1 
				AND(
					(DATE_ADD(booking.bkg_pickup_date, INTERVAL bkg_trip_duration MINUTE) <= NOW()) OR 
					(booking_track.bkg_trip_start_time < NOW() AND booking_track.bkg_ride_start = 1 AND booking_track.bkg_trip_end_time IS NULL)
				)";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	/**
	 * 
	 * @param integer $issueId
	 * @return integer
	 */
	public static function getResponseByIssueId($issueId)
	{
		$params	 = ['issueId' => $issueId];
		$sql	 = "SELECT report_issue.rpi_filename FROM `report_issue` WHERE report_issue.rpi_type <> 0 AND report_issue.rpi_id=:issueId";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}


	/**
	 * 
	 * @param integer $bkgId
	 * @param integer $rpiId
	 * @param integer $rpiType
	 * @param string $rpiDesc
	 * @param integer $userId
	 * @param integer $userType
	 * @return ReturnSet
	 * @throws Exception
	 */
	public static function postAnIssue($bkgId, $rpiId, $rpiType, $rpiDesc, $userId, $userType)
	{
		$isShowIssue = self::checkStatusToShowIssue($bkgId);
		if (!$isShowIssue)
		{
			throw new Exception("Issue reported only for Ongoing Trip for this booking.", ReturnSet::ERROR_INVALID_DATA);
		}
		$rpiModel		 = ReportIssue::model()->findByPk($rpiId);
		if (!$rpiModel)
		{
			throw new Exception("Invalid Issue.", ReturnSet::ERROR_INVALID_DATA);
		}
		$isIssueActive	 = ServiceCallQueue::checkActiveCBRByIssueId($bkgId, $userId, $rpiModel->rpi_id, $rpiModel->rpi_queue_id);
		if ($isIssueActive)
		{
			throw new Exception("This Issue already reported. You will receive a callabck shortly.", ReturnSet::ERROR_DUPLCATE_DATA);
		}

		$model										 = new ServiceCallQueue();
		$model->scq_to_be_followed_up_with_value	 = ContactPhone::getPhoneNo($userId, $userType);
		$model->scq_follow_up_queue_type			 = $rpiModel->rpi_queue_id;
		$issueDetails								 = ($rpiDesc != '') ? ' - ' . $rpiDesc : '';
		$reportIssueType							 = self::getType();
		$reportIssueArray							 = json_decode($reportIssueType, true);
		$issueType									 = $reportIssueArray[$rpiType];
		$model->scq_creation_comments				 = $issueType . ' - ' . $rpiModel->rpi_name . $issueDetails;
		$model->contactRequired						 = 1;
		$model->scq_to_be_followed_up_by_type		 = ($rpiModel->rpi_team_id > 0) ? 1 : 2;
		$model->scq_to_be_followed_up_by_id			 = $rpiModel->rpi_team_id;
		$model->scq_to_be_followed_up_with_entity_id = ContactProfile::getByEntityId($userId, $userType);
		$model->scq_related_bkg_id					 = Booking::model()->getCodeById($bkgId);
		$model->scq_additional_param				 = json_encode(array('issueId' => $rpiModel->rpi_id));
		$model->scq_follow_up_priority				 = $rpiModel->rpi_priority_level;
		$model->scq_priority_score					 = $rpiModel->rpi_priority_score;

		return ServiceCallQueue::model()->create($model, $userType);

	}

}
