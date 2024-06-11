<?php

/**
 * This is the model class for table "user_stats".
 *
 * The followings are the available columns in table 'user_stats':
 * @property integer $urs_id
 * @property integer $urs_user_id
 * @property string $urs_first_date
 * @property string $urs_last_date
 * @property integer $urs_total_trips
 * @property integer $urs_rating
 * @property integer $urs_OW_Count
 * @property integer $urs_RT_Count
 * @property integer $urs_AT_Count
 * @property integer $urs_PT_Count
 * @property integer $urs_FL_Count
 * @property integer $urs_SH_Count
 * @property integer $urs_CT_Count
 * @property integer $urs_DR_4HR_Count
 * @property integer $urs_DR_8HR_Count
 * @property integer $urs_DR_12HR_Count
 * @property integer $urs_AP_Count
 * @property integer $urs_active
 * @property integer $urs_total_cancelled
 * @property integer $urs_total_amount
 * @property integer $urs_total_gozo_amount
 * @property integer $urs_trips_3months
 * @property integer $urs_trips_6months
 * @property integer $urs_trips_12months
 * @property integer $urs_last_trip_created
 * 
 * 
 * The followings are the available model relations:
 * @property Users $ursUser
 */
class UserStats extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
	return 'user_stats';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
	// NOTE: you should only define rules for those attributes that
	// will receive user inputs.
	return array(
	    array('urs_user_id', 'required'),
            array('urs_user_id, urs_total_trips, urs_rating, urs_active,urs_OW_Count,urs_RT_Count,urs_AT_Count,urs_PT_Count,urs_FL_Count,urs_SH_Count,urs_CT_Count,urs_DR_4HR_Count,urs_DR_8HR_Count,urs_DR_12HR_Count,urs_AP_Count', 'numerical', 'integerOnly' => true),
	    array('urs_first_date, urs_last_date', 'length', 'max' => 50),
	    array('urs_user_id, urs_active', 'required', 'on' => 'updateStats'),
	    // The following rule is used by search().
	    // @todo Please remove those attributes that should not be searched.
            array('urs_id, urs_user_id, urs_first_date, urs_last_date, urs_total_trips, urs_rating, urs_active,urs_total_spend_complete_base_fare,urs_total_kms_driven,urs_total_days_count,urs_OW_Count,urs_RT_Count,urs_AT_Count,urs_PT_Count,urs_FL_Count,urs_SH_Count,urs_CT_Count,urs_DR_4HR_Count,urs_DR_8HR_Count,urs_DR_12HR_Count,urs_AP_Count', 'safe', 'on' => 'search'),
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
	    'ursUser' => array(self::BELONGS_TO, 'Users', 'urs_user_id'),
	);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
	return array(
	    'urs_id'		 => 'Urs',
	    'urs_user_id'		 => 'Urs User',
	    'urs_first_date'	 => 'Urs First Date',
	    'urs_last_date'		 => 'Urs Last Date',
	    'urs_total_trips'	 => 'Urs Total Trips',
	    'urs_rating'		 => 'Urs Rating',
	    'urs_active'		 => 'Urs Active',
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

	$criteria->compare('urs_id', $this->urs_id);
	$criteria->compare('urs_user_id', $this->urs_user_id);
	$criteria->compare('urs_first_date', $this->urs_first_date, true);
	$criteria->compare('urs_last_date', $this->urs_last_date, true);
	$criteria->compare('urs_total_trips', $this->urs_total_trips);
	$criteria->compare('urs_rating', $this->urs_rating);
	$criteria->compare('urs_active', $this->urs_active);

	return new CActiveDataProvider($this, array(
	    'criteria' => $criteria,
	));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserStats the static model class
     */
    public static function model($className = __CLASS__)
    {
	return parent::model($className);
    }

    public function getbyUserId($userId)
    {
	$criteria	 = new CDbCriteria;
	$criteria->compare('urs_user_id', $userId);
	$criteria->compare('urs_active', 1);
	$model		 = $this->find($criteria);
	if ($model)
	{
	    return $model;
	}
	else
	{
	    return false;
	}
    }

    public static function getList($userId)
    {
	$having		 = "";
	$queryById	 = ($userId > 0) ? " AND users.user_id='$userId'" : "";
	if ($userId == 0 || $userId == '')
	{
	    $having = " HAVING last_trip_date>DATE_SUB(NOW(), INTERVAL 48 HOUR) OR last_review_date>DATE_SUB(NOW(), INTERVAL 48 HOUR)";
	}
	$sql = "SELECT
					IFNULL(ROUND(totalReview / countTotalReview),0) AS updateReview,
                    countTotalReview,
					user_id,
					total_trips,
					reviews,
					first_trip_date,
                last_trip_date,
                bkg_base_amount,
                bkg_trip_distance,                
                OW_Count,
                RT_Count,
                AT_Count,
                PT_Count,
                FL_Count,
                SH_Count,
                CT_Count,
                DR_4HR_Count,
                DR_8HR_Count,
                DR_12HR_Count,
                AP_Count
					FROM
					(
						SELECT
						SUM(ROUND(totalReview / countReview)) AS totalReview,
						COUNT(1) as total_trips,
                        SUM(countReview) as countTotalReview,
						user_id,
						bkg_id,
						GROUP_CONCAT(
							ROUND(totalReview / countReview) SEPARATOR ','
						) AS reviews,
						MIN(bkg_pickup_date) AS first_trip_date,
						MAX(bkg_pickup_date) AS last_trip_date,
                    MAX(rtg_customer_date) as last_review_date,
                    SUM(a.bkg_base_amount) AS bkg_base_amount,
                    SUM(a.bkg_trip_distance) AS bkg_trip_distance,
                    SUM(a.OW_Count) AS OW_Count,
                    SUM(a.RT_Count) AS RT_Count,
                    SUM(a.AT_Count) AS AT_Count,
                    SUM(a.PT_Count) AS PT_Count,
                    SUM(a.FL_Count) AS FL_Count,
                    SUM(a.SH_Count) AS SH_Count,
                    SUM(a.CT_Count) AS CT_Count,
                    SUM(a.DR_4HR_Count) AS DR_4HR_Count,
                    SUM(a.DR_8HR_Count) AS DR_8HR_Count,
                    SUM(a.DR_12HR_Count) AS DR_12HR_Count,
                    SUM(a.AP_Count) AS AP_Count
						FROM
						(
							SELECT
								(
									CASE WHEN(
										ratings.rtg_csr_customer > 0 AND ratings.rtg_vendor_customer > 0
									) THEN 2 WHEN(
										ratings.rtg_csr_customer > 0 AND ratings.rtg_vendor_customer IS NULL
									) THEN 1 WHEN(
										ratings.rtg_csr_customer IS NULL AND ratings.rtg_vendor_customer > 0
									) THEN 1 WHEN(
										ratings.rtg_csr_customer IS NULL AND ratings.rtg_vendor_customer IS NULL
									) THEN 0
								END
								) AS countReview,
								(
									CASE WHEN(
										ratings.rtg_csr_customer > 0 AND ratings.rtg_vendor_customer > 0
									) THEN(
										ratings.rtg_csr_customer + ratings.rtg_vendor_customer
									) WHEN(
										ratings.rtg_csr_customer > 0 AND ratings.rtg_vendor_customer IS NULL
									) THEN ratings.rtg_csr_customer WHEN(
										ratings.rtg_csr_customer IS NULL AND ratings.rtg_vendor_customer > 0
									) THEN ratings.rtg_vendor_customer WHEN(
										ratings.rtg_csr_customer IS NULL AND ratings.rtg_vendor_customer IS NULL
									) THEN 0
								END
								) AS totalReview,
								users.user_id,
								booking.bkg_id,
								booking.bkg_pickup_date,
                        rtg_customer_date,
                        bkg_base_amount,
                        bkg_trip_distance,
                        IF(bkg_booking_type=1,1,0) AS OW_Count,
                        IF(bkg_booking_type IN (2,3),1,0) AS RT_Count,
                        IF(bkg_booking_type=4,1,0) AS AT_Count,
                        IF(bkg_booking_type=5,1,0) AS PT_Count,
                        IF(bkg_booking_type=6,1,0) AS FL_Count,
                        IF(bkg_booking_type=7,1,0) AS SH_Count,
                        IF(bkg_booking_type=8,1,0) AS CT_Count,
                        IF(bkg_booking_type=9,1,0) AS DR_4HR_Count,
                        IF(bkg_booking_type=10,1,0) AS DR_8HR_Count,
                        IF(bkg_booking_type=11,1,0) AS DR_12HR_Count,
                        IF(bkg_booking_type=12,1,0) AS AP_Count
                        FROM `booking`
                        INNER JOIN `booking_user` ON booking_user.bui_bkg_id = booking.bkg_id
                        INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id = booking.bkg_id
                        INNER JOIN `users` ON users.user_id = booking_user.bkg_user_id
                        LEFT JOIN `ratings` ON  ratings.rtg_booking_id = booking.bkg_id 
                        WHERE booking.bkg_active=1  AND booking.bkg_status IN(6,7)
                        AND booking.bkg_create_date > '2015-10-01 23:59:59' $queryById
              ) a
              GROUP BY user_id $having 
				) b
				GROUP BY user_id"; 
		return DBUtil::queryAll($sql, DBUtil::SDB());
    }
	
	public static function updateStats()
	{
		$sql = "SELECT DISTINCT bkg_user_id 
				FROM `booking` 
				INNER JOIN booking_user ON bui_bkg_id = bkg_id 
				INNER JOIN users ON users.user_id = bkg_user_id AND usr_active = 1 
				INNER JOIN contact_profile ON cr_is_consumer = user_id 
				INNER JOIN contact ON ctt_id = cr_contact_id AND ctt_active = 1 AND ctt_id = ctt_ref_code 
				WHERE 1 AND bkg_active =1 AND bkg_create_date > '2015-10-01 23:59:59' AND bkg_status IN (2,3,5,6,7,9) 
				AND bkg_agent_id IS NULL  ";

		$result = DBUtil::query($sql);
		foreach ($result as $value)
		{
			$sql = "SELECT 
					SUM(IF(bkg_status = 9,1,0)) totCancelled, 
					SUM(IF(bkg_status = 9 AND bkg_cancel_id = 21 AND bkg_reconfirm_flag = 1 AND bkg_net_advance_amount <= 0,1,0)) totZeroCashCancelled, 
					SUM(IF(bkg_status = 9 AND bkg_cancel_id = 21 AND bkg_reconfirm_flag = 1 AND bkg_net_advance_amount <= 0 AND bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH),1,0)) totZeroCashCancelledMonth12, 
					ROUND(SUM(IF(bkg_status IN (6,7), bkg_total_amount, 0))) totAmt,
					ROUND(SUM(IF(bkg_status IN (6,7), bkg_gozo_amount, 0))) totGozoAmt,
					SUM(IF((bkg_status IN (6,7) AND bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL 3 MONTH)), 1, 0)) as trips_month3,
					SUM(IF((bkg_status IN (6,7) AND bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)), 1, 0)) as trips_month6,
					SUM(IF((bkg_status IN (6,7) AND bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)), 1, 0)) as trips_month12,
					SUM(IF(bkg_status IN (6,7), 1, 0)) as total_trips, 
					MAX(bkg_create_date) lastCreateDate 
					FROM `booking` 
					INNER JOIN booking_user ON bui_bkg_id = bkg_id 
					INNER JOIN booking_invoice ON biv_bkg_id = bkg_id 
					INNER JOIN users ON user_id = bkg_user_id AND usr_active = 1 
					INNER JOIN contact_profile ON cr_is_consumer = user_id 
					INNER JOIN contact ON ctt_id = cr_contact_id AND ctt_active = 1 AND ctt_id = ctt_ref_code 
					
					WHERE 1 AND bkg_active =1 AND bkg_create_date > '2015-10-01 23:59:59' AND bkg_status IN (2,3,5,6,7,9) 
					AND bkg_agent_id IS NULL AND bkg_user_id=:id 
					GROUP BY bkg_user_id";

			$params = ['id' => $value['bkg_user_id']];

			Logger::writeToConsole("UserId: " . $value['bkg_user_id']);
			try
			{
				$data	 = DBUtil::queryRow($sql, NULL, $params);
				$model	 = UserStats::model()->getbyUserId($value['bkg_user_id']);
				if (!$model)
				{
					$model				 = new UserStats();
					$model->urs_user_id	 = $value['bkg_user_id'];
					$model->urs_active	 = 1;
				}
				
				$totZeroCashCancelled = $data['totZeroCashCancelled'];
				$totZeroCashCancelledMonth12 = $data['totZeroCashCancelledMonth12'];
				
				$model->urs_total_cancelled		 = $data['totCancelled'];
				$model->urs_total_amount		 = $data['totAmt'];
				$model->urs_total_gozo_amount	 = $data['totGozoAmt'];
				$model->urs_trips_3months		 = $data['trips_month3'];
				$model->urs_trips_6months		 = $data['trips_month6'];
				$model->urs_trips_12months		 = $data['trips_month12'];
				$model->urs_total_trips			 = $data['total_trips'];
				$model->urs_last_trip_created	 = $data['lastCreateDate'];
				if ($model->save())
				{
					ContactPref::updateCategory($value['bkg_user_id'], $totZeroCashCancelled, $totZeroCashCancelledMonth12);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
				Logger::writeToConsole($ex->getMessage());
			}
		}
	}

}
