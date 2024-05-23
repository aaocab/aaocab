<?php

/**
 * This is the model class for table "trip_tracking".
 *
 * The followings are the available columns in table 'trip_tracking':
 * @property integer $ttg_id
 * @property integer $ttg_bcb_id
 * @property integer $ttg_bkg_id
 * @property string $ttg_trip_start_time
 * @property string $ttg_trip_end_time
 * @property integer $ttg_start_odometer
 * @property string $ttg_start_odometer_path
 * @property integer $ttg_end_odometer
 * @property string $ttg_end_odometer_path
 * @property integer $ttg_driver_id
 * @property string $ttg_latitude
 * @property string $ttg_longitude
 * @property integer $ttg_event_type
 * @property integer $ttg_trip_late
 * @property integer $ttg_active
 * @property string $ttg_time_stamp
 * @property string $ttg_created
 *
 * The followings are the available model relations:
 * @property BookingCab $ttgBcb
 * @property Booking $ttgBkg
 * @property Drivers $ttgDriver
 */
class TripTracking extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'trip_tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ttg_event_type', 'required'),
			['ttg_event_type', 'required', 'on' => 'startTrip,insert'],
			array('ttg_bcb_id, ttg_bkg_id, ttg_start_odometer, ttg_end_odometer, ttg_trip_late, ttg_driver_id, ttg_event_type, ttg_active', 'numerical', 'integerOnly' => true),
			array('ttg_start_odometer_path, ttg_end_odometer_path', 'length', 'max' => 500),
			array('ttg_latitude, ttg_longitude', 'length', 'max' => 50),
			array('ttg_trip_start_time, ttg_trip_end_time, ttg_time_stamp, ttg_created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ttg_id, ttg_bcb_id, ttg_bkg_id, ttg_trip_start_time, ttg_trip_end_time, ttg_start_odometer, ttg_start_odometer_path, ttg_end_odometer, ttg_end_odometer_path, ttg_driver_id, ttg_latitude, ttg_longitude, ttg_event_type, ttg_trip_late, ttg_active, ttg_time_stamp, ttg_created', 'safe', 'on' => 'search'),
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
			'ttgBcb' => array(self::BELONGS_TO, 'BookingCab', 'ttg_bcb_Id'),
			'ttgBkg' => array(self::BELONGS_TO, 'Booking', 'ttg_bkg_Id'),
			'ttgDriver' => array(self::BELONGS_TO, 'Drivers', 'ttg_driver_Id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ttg_id' => 'Ttg',
			'ttg_bcb_id' => 'Ttg Bcb',
			'ttg_bkg_id' => 'Ttg Bkg',
			'ttg_trip_start_time' => 'Ttg Trip Start Time',
			'ttg_trip_end_time' => 'Ttg Trip End Time',
			'ttg_start_odometer' => 'Ttg Start Odometer',
			'ttg_start_odometer_path' => 'Ttg Start Odometer Path',
			'ttg_end_odometer' => 'Ttg End Odometer',
			'ttg_end_odometer_path' => 'Ttg End Odometer Path',
			'ttg_driver_id' => 'Ttg Driver',
			'ttg_latitude' => 'Ttg Latitude',
			'ttg_longitude' => 'Ttg Longitude',
			'ttg_event_type' => '1=>Arrived,2=>No show,3=>Late, 4=>Start, 5=>Stop,6=>Paused,7=>Resume',
			'ttg_trip_late' => 'Ttg Trip Late',
			'ttg_active' => 'Ttg Active',
			'ttg_time_stamp' => 'Ttg Time Stamp',
			'ttg_created' => 'Ttg Created',
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

		$criteria->compare('ttg_id', $this->ttg_id);
		$criteria->compare('ttg_bcb_id', $this->ttg_bcb_id);
		$criteria->compare('ttg_bkg_id', $this->ttg_bkg_id);
		$criteria->compare('ttg_trip_start_time', $this->ttg_trip_start_time, true);
		$criteria->compare('ttg_trip_end_time', $this->ttg_trip_end_time, true);
		$criteria->compare('ttg_start_odometer', $this->ttg_start_odometer);
		$criteria->compare('ttg_start_odometer_path', $this->ttg_start_odometer_path, true);
		$criteria->compare('ttg_end_odometer', $this->ttg_end_odometer);
		$criteria->compare('ttg_end_odometer_path', $this->ttg_end_odometer_path, true);
		$criteria->compare('ttg_driver_id', $this->ttg_driver_id);
		$criteria->compare('ttg_latitude', $this->ttg_latitude, true);
		$criteria->compare('ttg_longitude', $this->ttg_longitude, true);
		$criteria->compare('ttg_event_type', $this->ttg_event_type);
		$criteria->compare('ttg_trip_late', $this->ttg_trip_late);
		$criteria->compare('ttg_active', $this->ttg_active);
		$criteria->compare('ttg_time_stamp', $this->ttg_time_stamp, true);
		$criteria->compare('ttg_created', $this->ttg_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TripTracking the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getOdometerReading($bkg_id)
	{
		$criteria	 = new CDbCriteria();
		$criteria->compare('ttg_bkg_id', $bkg_id);
		$criteria->compare('ttg_event_type', 215);
		$data		 = $this->find($criteria);
		return $data->ttg_start_odometer;
	}

	public function checkData($id){
      $sql		 = "SELECT
                        booking.bkg_id AS next_bkg_id,
                        booking.bkg_pickup_date,
                        booking.bkg_trip_duration,
                        DATE_ADD(NOW(), INTERVAL 3 HOUR) as now_after_3hrs,
                        DATE_ADD(
                            bkg_pickup_date,
                            INTERVAL bkg_trip_duration MINUTE
                        ) AS tripCompletionTime,
                        IF(
                            DATE_ADD(
                                bkg_pickup_date,
                                INTERVAL(bkg_trip_duration +120) MINUTE
                            ) < NOW(), 1, 0) AS iscompleted
                        FROM
                        booking_cab                    
                        INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id 
                        AND booking.bkg_active = 1
                        AND booking_cab.bcb_active = 1 
                        AND booking.bkg_status IN(5)              
                        INNER JOIN drivers ON drivers.drv_id = booking_cab.bcb_driver_id AND drivers.drv_active = 1
						INNER JOIN booking_pref ON booking.bkg_id = booking_pref.bpr_bkg_id
                        INNER JOIN booking_track ON booking.bkg_id = booking_track.btk_bkg_id
                        WHERE
                            1=1
                            AND booking_cab.bcb_driver_id in (SELECT d3.drv_id FROM drivers d1
          INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
          INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
          WHERE d1.drv_id='$id')
                            AND booking_track.bkg_is_no_show = 0
                            AND  booking_track.bkg_ride_complete=0
                        GROUP BY
                            booking.bkg_id
                        ORDER BY
                           iscompleted ASC,
                            booking.bkg_pickup_date ASC
                            LIMIT 0, 1";
		$recordset1	 = DBUtil::queryRow($sql);
		return ['bkg_id' => $recordset1['next_bkg_id'], 'flag' => 1];
	}

	public function checkDuplicateIds($bkg_id, $drv_id, $time_stamp, $eventId)
	{
		$qry		 = "SELECT ttg_bkg_id FROM trip_tracking WHERE ttg_bkg_id = $bkg_id AND ttg_driver_id in (SELECT d3.drv_id FROM drivers d1
          INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
          INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
          WHERE d1.drv_id='$drv_id')  AND ttg_time_stamp = '$time_stamp' AND ttg_event_type = $eventId";
		$recordset	 = DBUtil::queryRow($qry);
		return $recordset['ttg_bkg_id'];
	}

	public function getTripTarkingLog($bkgId)
	{
		$qry			 = "SELECT ttg.ttg_bkg_id, ttg.ttg_bcb_id, ttg.ttg_driver_id, ttg.ttg_latitude, ttg.ttg_longitude, ttg.ttg_event_type, ttg.ttg_created FROM booking bkg
                INNER JOIN trip_tracking ttg ON ttg.ttg_bkg_id = bkg.bkg_id
                WHERE ttg.ttg_bkg_id = $bkgId AND ttg.ttg_active = 1";
		$dataprovider	 = new CSqlDataProvider($qry, [
			'sort' => ['attributes' => ['vlg_desc', 'vlg_event_id', 'vlg_created', 'name', 'type'],
				'defaultOrder' => 'ttg_created  DESC'],
		]);
		return $dataprovider;
	}

	public function getDirverAppInfo($bkgId)
	{
        $params	 = ['bkgId' => $bkgId];
		$sql = "SELECT * FROM  booking_track_log ttg NNER JOIN booking bkg ON bkg.bkg_id = ttg.btl_bkg_id AND bkg_id=:bkgId";
		$data	 = DBUtil::queryAll($sql, DBUtil:: SDB(), $params);
		return $data;
	}

	public function getEventTypeByBkg($bkgId)
	{
		/* $sql = "SELECT
		  IF(
		  ttg_event_type IS NULL,
		  0,
		  ttg_event_type
		  ) AS ttg_event_type
		  FROM
		  (
		  SELECT
		  trip_tracking.ttg_bkg_Id,
		  trip_tracking.ttg_event_type
		  FROM
		  `trip_tracking`
		  WHERE
		  trip_tracking.ttg_bkg_id = '$bkgId' AND trip_tracking.ttg_event_type NOT IN (251,252)
		  ORDER BY
		  trip_tracking.ttg_time_stamp
		  DESC
		  LIMIT 0,
		  1
		  ) a"; */

//		$sql = "SELECT
//				IF(
//					ttg_event_type IS NULL,
//					0,
//					ttg_event_type
//				) AS ttg_event_type
//			FROM
//				(
//				SELECT
//					trip_tracking.ttg_bkg_Id,
//				IF(
//					trip_tracking.ttg_event_type IS NULL,
//					0,
//					trip_tracking.ttg_event_type
//				) AS ttg_event_type
//				FROM
//					`trip_tracking`
//				WHERE
//					trip_tracking.ttg_bkg_id = '$bkgId' 
//				ORDER BY
//					trip_tracking.ttg_time_stamp
//				DESC
//			LIMIT 0,
//			1
//			) a";
$sql ="SELECT `btl_event_type_id` AS ttg_event_type FROM `booking_track_log` WHERE `btl_bkg_id` = '$bkgId'  ORDER BY
					booking_track_log.`btl_created` DESC LIMIT 0,1";
		return DBUtil::command($sql)->queryScalar();
	}

	public function unsetNoShowByBookingId($bkgId)
	{
		$success = false;
		$model	 = TripTracking::model()->find('ttg_bkg_id=:id', ['id' => $bkgId]);
		if ($model != '')
		{
			if ($model->ttg_event_type = BookingLog::NO_SHOW)
			{
				$model->ttg_active = 0;
				if ($model->save())
				{
					$this->ttg_bcb_id		 = $model->ttg_bcb_id;
					$this->ttg_bkg_id		 = $model->ttg_bkg_id;
					$this->ttg_driver_id	 = $model->ttg_driver_id;
					$this->ttg_latitude		 = $model->ttg_latitude;
					$this->ttg_longitude	 = $model->ttg_longitude;
					$this->ttg_event_type	 = BookingLog::NO_SHOW_RESET;
					$this->ttg_active		 = 1;
					if ($this->validate())
					{
						$this->save();
						$success = true;
					}
				}
			}
		}

		return $success;
	}

	public function add($bkg_id, $event, $odoStartReading = '')
	{
		$bModel							 = Booking::model()->findByPk($bkg_id);
		$ttgModel						 = new TripTracking();
		$ttgModel->scenario				 = "startTrip";
		$ttgModel->ttg_start_odometer	 = $odoStartReading;
		$ttgModel->ttg_driver_id		 = $bModel->bkgBcb->bcb_driver_id;
		$ttgModel->ttg_bkg_id			 = $bkg_id;
		$ttgModel->ttg_bcb_id			 = $bModel->bkg_bcb_id;
		$ttgModel->ttg_event_type		 = $event;
		$ttgModel->save();
		return $ttgModel;
	}

	public function getByBkg($bkg_id, $event)
	{
		$sql	 = "SELECT * FROM  trip_tracking ttg where  ttg.ttg_bkg_id   =$bkg_id AND ttg.ttg_active =1 AND ttg_event_type = {$event}";
		$data	 = DBUtil::queryAll($sql);
		return $data;
	}
	
	public function tripStartTimebyBkgId($bkg_id){
		$event=215;
		$sql	 = "SELECT ttg_created FROM  trip_tracking ttg where  ttg.ttg_bkg_id   =$bkg_id AND ttg.ttg_active =1 AND ttg_event_type = {$event}";
		$data	 = DBUtil::command($sql)->queryScalar();
		return $data;
	}

}
