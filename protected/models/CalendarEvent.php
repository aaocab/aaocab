<?php

/**
 * This is the model class for table "calendar_event".
 *
 * The followings are the available columns in table 'calendar_event':
 * @property string $cle_id
 * @property string $cle_dt
 * @property integer $cle_y
 * @property integer $cle_q
 * @property integer $cle_m
 * @property integer $cle_d
 * @property integer $cle_dw
 * @property string $cle_month_name
 * @property string $cle_day_name
 * @property integer $cle_w
 * @property integer $cle_dow_type
 * @property integer $cle_day_type
 * @property integer $cle_is_long_weekend
 * @property integer $cle_is_event
 * @property string $cle_event_id
 *  @property string $cle_halo_event_id
 * @property string $cle_created_at
 * @property string $cle_modified_at
 * @property integer $cle_active
 * @property integer $cle_is_phantom_weekend
 * @property double $cle_factor
 * @property double $cle_weighted_factor
 */
class CalendarEvent extends CActiveRecord
{

	public $year, $fromDate, $toDate, $eventId, $pastDays, $nextDays;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'calendar_event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cle_dt, cle_created_at, cle_modified_at', 'required'),
			array('cle_y, cle_q, cle_m, cle_d, cle_dw, cle_w, cle_dow_type, cle_day_type, cle_is_long_weekend, cle_is_event, cle_active, cle_is_phantom_weekend', 'numerical', 'integerOnly' => true),
			array('cle_factor, cle_weighted_factor', 'numerical'),
			array('cle_month_name, cle_day_name', 'length', 'max' => 100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cle_id, cle_dt, cle_y, cle_q, cle_m, cle_d, cle_dw, cle_month_name, cle_day_name, cle_w, cle_dow_type, cle_day_type, cle_is_long_weekend, cle_is_event, cle_event_id, cle_created_at, cle_modified_at, cle_active, cle_is_phantom_weekend, cle_factor, cle_weighted_factor,cle_halo_event_id', 'safe', 'on' => 'search'),
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
			'cle_id'				 => 'Cle',
			'cle_dt'				 => 'Cle Dt',
			'cle_y'					 => 'Cle Y',
			'cle_q'					 => 'Cle Q',
			'cle_m'					 => 'Cle M',
			'cle_d'					 => 'Cle D',
			'cle_dw'				 => 'Cle Dw',
			'cle_month_name'		 => 'Cle Month Name',
			'cle_day_name'			 => 'Cle Day Name',
			'cle_w'					 => 'Cle W',
			'cle_dow_type'			 => '0=>weekday ,1=>weekend',
			'cle_day_type'			 => 'regular = 0; 
                                        weekend = 1, 
                                        event = 2,
                                        event + wkend = 3
                                        =====
                                        if its event then 2, 
                                        if its weekend then 1, 
                                        if its both then 2+1
                                        if its none then 0',
			'cle_is_long_weekend'	 => 'if event is on dw=6(Fri), then 6,7,1 are long, 
                                        if event is on 2(Mon), then 7,1,2 are long. 
                                        Sunday = 1 in our dow method',
			'cle_is_event'			 => '1=>event,0=>No',
			'cle_event_id'			 => 'id of the holiday_events table',
			'cle_created_at'		 => 'Cle Created At',
			'cle_modified_at'		 => 'Cle Modified At',
			'cle_active'			 => '1=>active 0=>inactive',
			'cle_is_phantom_weekend' => 'if is_event 1= AND  dw=(7,1), then 6,7,1,2 are phantom',
			'cle_factor'			 => '1+ 0.05*IF(is_phantom_weekend=1 OR is_long_weekend=1 OR dow_type=1,1,0)+0.08*is_event',
			'cle_weighted_factor'	 => '0.6*(P2)+0.2*P1+0.2*P3',
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

		$criteria->compare('cle_id', $this->cle_id, true);
		$criteria->compare('cle_dt', $this->cle_dt, true);
		$criteria->compare('cle_y', $this->cle_y);
		$criteria->compare('cle_q', $this->cle_q);
		$criteria->compare('cle_m', $this->cle_m);
		$criteria->compare('cle_d', $this->cle_d);
		$criteria->compare('cle_dw', $this->cle_dw);
		$criteria->compare('cle_month_name', $this->cle_month_name, true);
		$criteria->compare('cle_day_name', $this->cle_day_name, true);
		$criteria->compare('cle_w', $this->cle_w);
		$criteria->compare('cle_dow_type', $this->cle_dow_type);
		$criteria->compare('cle_day_type', $this->cle_day_type);
		$criteria->compare('cle_is_long_weekend', $this->cle_is_long_weekend);
		$criteria->compare('cle_is_event', $this->cle_is_event);
		$criteria->compare('cle_event_id', $this->cle_event_id);
		$criteria->compare('cle_created_at', $this->cle_created_at, true);
		$criteria->compare('cle_modified_at', $this->cle_modified_at, true);
		$criteria->compare('cle_active', $this->cle_active);
		$criteria->compare('cle_is_phantom_weekend', $this->cle_is_phantom_weekend);
		$criteria->compare('cle_factor', $this->cle_factor);
		$criteria->compare('cle_weighted_factor', $this->cle_weighted_factor);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CalendarEvent the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getDEBP(Quote &$quoteModel)
	{
		$fromCity		 = $quoteModel->sourceCity;
		$toCity			 = $quoteModel->destinationCity;
		$date			 = date("Y-m-d", strtotime($quoteModel->routeDuration->fromDate));
		$rockBaseAmount	 = $quoteModel->routeRates->rockBaseAmount;
		$resDEBPMarkup	 = CalendarEvent::getWeightFactor($date);
		Logger::trace("date:" . $date);
		Logger::trace("resDEBPMarkup" . json_encode($resDEBPMarkup));
		if ($resDEBPMarkup['event_id'] != null || $resDEBPMarkup['halo_event_id'] != null)
		{
			return self::getRow($resDEBPMarkup, $fromCity, $toCity);
		}
		else if ($resDEBPMarkup['isPhantomLong'] == 1 || $resDEBPMarkup['isDowType'] == 1)
		{
			$eventFactor			 = Config::get('EventFactor');
			$resDEBPMarkup['factor'] = 1.0;
			if ($resDEBPMarkup['isPhantomLong'] == 1)
			{
				if (!empty($eventFactor))
				{
					$result					 = CJSON::decode($eventFactor);
					$resDEBPMarkup['factor'] += $result['isPhantomLong'];
				}
				else
				{
					$resDEBPMarkup['factor'] += 0.02; // long weeekend ka  2%
				}
			}
			if ($resDEBPMarkup['isDayOfWeek'] == 1)
			{
				if (!empty($eventFactor))
				{
					$result					 = CJSON::decode($eventFactor);
					$resDEBPMarkup['factor'] += $result['isDowType'];
				}
				else
				{
					$resDEBPMarkup['factor'] += 0.01; // weekend ka 1%
				}
			}
			return $resDEBPMarkup;
		}
		else if ($resDEBPMarkup['cleFactor'] > 1)
		{
			return $resDEBPMarkup;  // only apply cle_weight factor if clefactor >1 
		}
		return [];
	}

	public static function getRow($resDEBPMarkup, $fromCity, $toCity)
	{
		try
		{
			$modelFrom	 = CityMaster::model()->getByCity($fromCity);
			$modelTo	 = CityMaster::model()->getByCity($toCity);
			$eventId	 = "";
			if ($resDEBPMarkup['event_id'] != null)
			{
				$eventId .= $resDEBPMarkup['event_id'];
			}
			if ($resDEBPMarkup['halo_event_id'] != null)
			{
				$eventId .= $eventId != null ? "," . $resDEBPMarkup['halo_event_id'] : $resDEBPMarkup['halo_event_id'];
			}
			Logger::trace("eventId:" . $eventId);
			$result = EventGeo::getByEventId($eventId, $modelFrom, $modelTo);
			if ($result)
			{
				$resDEBPMarkup['factor'] = $resDEBPMarkup['factor'] < $result['margin'] ? $result['margin'] : $resDEBPMarkup['factor'];
				Logger::trace("getRow" . json_encode($resDEBPMarkup));
				return $resDEBPMarkup; /// use the value from the table
			}
			else if ($resDEBPMarkup['isPhantomLong'] == 1 || $resDEBPMarkup['isDowType'] == 1)
			{
				$eventFactor			 = Config::get('EventFactor');
				$resDEBPMarkup['factor'] = 1.0; /// reset DEBP markup to 1 and now start to calculate
				if ($resDEBPMarkup['isPhantomLong'] == 1) // it is a long weekend or a phantom weekend (PhantomLong = Phantom or Long)
				{
					if (!empty($eventFactor))
					{
						$result					 = CJSON::decode($eventFactor);
						$resDEBPMarkup['factor'] += $result['isPhantomLong'];
					}
					else
					{
						$resDEBPMarkup['factor'] += 0.02; // phantom ka 2% if the config value is missing. for now we're making config value to 0 for phantomLong
					}
				}
				if ($resDEBPMarkup['isDayOfWeek'] == 1) // add this to also include Friday
				{
					if (!empty($eventFactor))
					{
						$result					 = CJSON::decode($eventFactor);
						$resDEBPMarkup['factor'] += $result['isDowType'];
					}
					else
					{
						$resDEBPMarkup['factor'] += 0.01;  // weekend ka 1%
					}
				}
				return $resDEBPMarkup;
			}
			return [];
		}
		catch (Exception $ex)
		{
			return [];
		}
	}

	public function getYearEventDate($date)
	{
		$params	 = array('fromDate' => ($date - 1) . "-01-01", 'toDate' => ($date + 1) . "-12-31");
		$sql	 = "SELECT 
                    GROUP_CONCAT(DISTINCT calendar_event.cle_dt) AS Date,
                    holiday_events.hde_id,
                    holiday_events.hde_name,
					holiday_events.hde_slug,
                    holiday_events.hde_description,
                    holiday_events.hde_calendar_event_type,
                    holiday_events.hde_std_or_not,
                    holiday_events.hde_active,
					holiday_events.hde_added_by_uid,
					holiday_events.hde_approved_by_uid
                    FROM `holiday_events` 
                    LEFT JOIN calendar_event ON FIND_IN_SET(holiday_events.hde_id, calendar_event.cle_event_id) AND `cle_dt` BETWEEN :fromDate AND :toDate AND `cle_is_event` = 1 AND calendar_event.cle_active=1
                    WHERE 1
                    GROUP BY holiday_events.hde_id";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	public static function getStatus($type = -1)
	{
		$arrStatusType = [
			0	 => "Rejected",
			1	 => "Approved",
			2	 => 'Pending approval'
		];
		if ($type != -1)
		{
			return $arrStatusType[$type];
		}
		else
		{
			return $arrStatusType;
		}
	}

	public static function getEventType($type = -1)
	{
		$arrEventType = [
			0	 => "National Holiday",
			1	 => "Regional Holiday",
			2	 => 'Elections',
			3	 => 'Social Unrest',
			99	 => 'other'
		];
		if ($type != -1)
		{
			return $arrEventType[$type];
		}
		else
		{
			return $arrEventType;
		}
	}

	public static function getWeightFactor($date)
	{
		$params	 = array('date' => $date);
		$sql	 = "SELECT 
                    cle_id AS id,
                    cle_weighted_factor as factor,
                    cle_event_id AS event_id ,
                    cle_halo_event_id AS halo_event_id,
                    cle_factor AS cleFactor,
                    IF(cle_is_phantom_weekend=1 OR cle_is_long_weekend=1,1,0) AS isPhantomLong,
					IF(cle_dw IN (6,7,1),1,0) AS isDayOfWeek,
                    IF(cle_dow_type=1,1,0) AS isDowType
                    FROM calendar_event WHERE cle_dt=:date AND cle_active=1";
		Logger::trace("getWeightFactor:" . $sql);
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	public function updateWeightedFactor()
	{
		$result					 = CalendarEvent::getAllCalenderDate();
		$weightedFactor			 = Config::get('WeightedFactor');
		$previousWeightFactor	 = 0.2;
		$todayWeightFactor		 = 0.6;
		$tomorrowWeightFactor	 = 0.2;
		if (!empty($weightedFactor))
		{
			$results				 = CJSON::decode($weightedFactor);
			$previousWeightFactor	 = $results['previousWeightFactor'];
			$todayWeightFactor		 = $results['todayWeightFactor'];
			$tomorrowWeightFactor	 = $results['tomorrowWeightFactor'];
		}
		foreach ($result as $row)
		{
			$ids				 = $row['id'];
			$prevFactor			 = CalendarEvent::getPrevIds($ids);
			$nextFactor			 = CalendarEvent::getNextIds($ids);
			$weightedFactorSurge = $todayWeightFactor * ($row['factor']) + $previousWeightFactor * $prevFactor + $tomorrowWeightFactor * $nextFactor;
			$sqlUpdate			 = "UPDATE calendar_event SET cle_weighted_factor=:weightedFactorSurge WHERE 1 AND cle_id=:id";
			DBUtil::execute($sqlUpdate, ['id' => $ids, 'weightedFactorSurge' => $weightedFactorSurge]);
		}
	}

	public static function getAllCalenderDate()
	{
		$sql = "SELECT cle_id AS id,cle_factor AS factor FROM `calendar_event` WHERE 1";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getNextIds($ids)
	{
		$sqlNext	 = "SELECT `cle_factor` FROM  calendar_event WHERE 1 AND cle_id>:id ORDER BY cle_id ASC LIMIT 0,1";
		$nextFactor	 = DBUtil::queryScalar($sqlNext, DBUtil::SDB(), ['id' => $ids]);
		$nextFactor	 = $nextFactor ? $nextFactor : 0;
		return $nextFactor;
	}

	public static function getPrevIds($ids)
	{
		$sqlPrev	 = "SELECT `cle_factor` FROM calendar_event WHERE 1 AND cle_id<:id ORDER BY  cle_id DESC LIMIT 0,1";
		$prevFactor	 = DBUtil::queryScalar($sqlPrev, DBUtil::SDB(), ['id' => $ids]);
		$prevFactor	 = $prevFactor ? $prevFactor : 0;
		return $prevFactor;
	}

	public static function isEventExistForYear($holidayEventId, $year)
	{
		$sql = "SELECT COUNT(1) as cnt,GROUP_CONCAT(cle_dt) AS cle_dt  FROM `calendar_event` WHERE 1 AND cle_y=:year and FIND_IN_SET(:holidayEventId, calendar_event.cle_event_id)";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['holidayEventId' => $holidayEventId, 'year' => $year]);
	}

	public function IsEventByDate($date)
	{
		$sql = "SELECT COUNT(1) AS cnt FROM calendar_event WHERE 1 AND cle_dt=:date AND cle_is_event=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['date' => $date]);
	}

	public static function updateEvent($eventId, $date, $flag = 1)
	{
		if ($flag == 1)
		{
			$sql = "UPDATE calendar_event SET cle_event_id=TRIM(BOTH ',' FROM CONCAT(cle_event_id,',',:eventId)),cle_is_event=1 WHERE 1 AND cle_dt=:date";
		}
		else
		{
			$sql = "UPDATE calendar_event SET cle_event_id=:eventId,cle_is_event=1 WHERE 1 AND cle_dt=:date";
		}
		return DBUtil::execute($sql, ['eventId' => $eventId, 'date' => $date]);
	}

	public function isHaloEventDate($date)
	{
		$sql = "SELECT COUNT(1) AS cnt FROM calendar_event WHERE 1 AND cle_dt=:date AND cle_halo_event_id IS NOT NULL";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['date' => $date]);
	}

	public static function updateHaloEvent($eventId, $date, $flag = 1)
	{
		if ($flag == 1)
		{
			$sql = "UPDATE calendar_event SET cle_halo_event_id=TRIM(BOTH ',' FROM CONCAT(cle_halo_event_id,',',:eventId)) WHERE 1 AND cle_dt=:date";
		}
		else
		{
			$sql = "UPDATE calendar_event SET cle_halo_event_id=:eventId WHERE 1 AND cle_dt=:date";
		}
		return DBUtil::execute($sql, ['eventId' => $eventId, 'date' => $date]);
	}

	public static function updateDayType()
	{
		$sql = "UPDATE calendar_event 
                SET cle_day_type=CASE
                WHEN cle_dow_type=1 AND cle_is_event=1 THEN 3
                WHEN cle_dow_type=0 AND cle_is_event=1 THEN 2
                WHEN cle_dow_type=1 AND cle_is_event=0 THEN 1
                ELSE 0
                END
                WHERE 1
                ORDER BY calendar_event.cle_id ASC";
		return DBUtil::execute($sql);
	}

	public static function updateLongWeekends()
	{
		//marking for friday and monday is long weekend

		$sql = "UPDATE calendar_event SET cle_is_long_weekend=IF((cle_dw=6 OR cle_dw=2) AND cle_is_event=1,1,0) WHERE 1 ORDER BY calendar_event.cle_id  ASC";
		return DBUtil::execute($sql);
	}

	public static function updateNextLongWeekends()
	{
		$sql = "UPDATE calendar_event
                SET cle_is_long_weekend=1
                WHERE 1 AND FIND_IN_SET (cle_id,(
                SELECT
                GROUP_CONCAT(TRIM(BOTH ',' FROM TRIM(BOTH '0,0' FROM CONCAT(nextID1,',',nextID2,',',PrevID1,',',PrevID2)))) AS Ids
                FROM
                (
                    SELECT
                    cle_id AS nextID,
                    (SELECT cle_id FROM calendar_event WHERE 1 AND cle_id>nextID ORDER BY cle_id ASC LIMIT 0,1 ) AS nextID1,
                    (SELECT cle_id FROM calendar_event WHERE 1 AND cle_id>nextID ORDER BY cle_id ASC LIMIT 1,1 ) AS nextID2,
                    0 AS PrevID,
                    0 AS PrevID1,
                    0 AS PrevID2
                    FROM calendar_event WHERE 1 AND cle_dw = 6 AND cle_is_long_weekend = 1

                    UNION

                    SELECT
                    0 AS nextID,
                    0 AS nextID1,
                    0 AS nextID2,
                    cle_id AS PrevID,
                    (SELECT cle_id FROM calendar_event WHERE 1 AND cle_id<PrevID order BY cle_id DESC LIMIT 0,1 ) AS PrevID1,
                    (SELECT cle_id FROM calendar_event WHERE 1 AND cle_id<PrevID order BY cle_id DESC LIMIT 1,1 ) AS PrevID2
                    FROM calendar_event WHERE 1 AND cle_dw = 2 AND cle_is_long_weekend = 1                
                ) AS temp))";
		return DBUtil::execute($sql);
	}

	public static function updateNextLgWeekends()
	{
		// for Thursday and Tuesday  marking for is long weekneed
		$sql = "UPDATE calendar_event
                SET cle_is_long_weekend=1
                WHERE 1 AND FIND_IN_SET (cle_id,(
                SELECT
                GROUP_CONCAT(TRIM(BOTH ',' FROM TRIM(BOTH '0,0,0,0' FROM CONCAT(nextID,',',nextID1,',',nextID2,',',nextID3,',',PrevID,',',PrevID1,',',PrevID2,',',PrevID3)))) AS Ids
                FROM
                (
                    SELECT
                    cle_id AS nextID,
                    (SELECT cle_id FROM calendar_event WHERE 1 AND cle_id>nextID ORDER BY cle_id ASC LIMIT 0,1 ) AS nextID1,
                    (SELECT cle_id FROM calendar_event WHERE 1 AND cle_id>nextID ORDER BY cle_id ASC LIMIT 1,1 ) AS nextID2,
                    (SELECT cle_id FROM calendar_event WHERE 1 AND cle_id>nextID ORDER BY cle_id ASC LIMIT 2,1 ) AS nextID3,
                    0 AS PrevID,
                    0 AS PrevID1,
                    0 AS PrevID2,
                    0 AS PrevID3
                    FROM calendar_event WHERE 1 AND cle_is_event=1 AND cle_dw=5

                    UNION

                    SELECT
                    0 AS nextID,
                    0 AS nextID1,
                    0 AS nextID2,
                    0 AS nextID3,
                    cle_id AS PrevID,
                    (SELECT cle_id FROM calendar_event WHERE 1 AND cle_id<PrevID order BY cle_id DESC LIMIT 0,1 ) AS PrevID1,
                    (SELECT cle_id FROM calendar_event WHERE 1 AND cle_id<PrevID order BY cle_id DESC LIMIT 1,1 ) AS PrevID2,
                    (SELECT cle_id FROM calendar_event WHERE 1 AND cle_id<PrevID order BY cle_id DESC LIMIT 2,1 ) AS PrevID3
                    FROM calendar_event WHERE 1 AND cle_is_event=1 AND cle_dw=3
                )
                AS temp))";
		return DBUtil::execute($sql);
	}

	public static function updatePhantomWeekends()
	{
		$sql = "UPDATE calendar_event
                SET cle_is_phantom_weekend=1
                WHERE 1 AND FIND_IN_SET (cle_id,
                (
                    SELECT
                    GROUP_CONCAT(TRIM(BOTH ',0' FROM TRIM(BOTH '0,' FROM CONCAT(nextID,',',PrevID)))) as id
                    FROM
                    (
                        SELECT
                        (SELECT cle_id FROM calendar_event WHERE 1 AND cle_id>ce.cle_id ORDER BY cle_id ASC LIMIT 0,1 ) AS nextID,
                        0 AS PrevID
                        FROM calendar_event AS ce WHERE 1 AND cle_dow_type=1 and cle_is_event=1 AND cle_dw=1

                        UNION

                        SELECT
                        0 AS nextID,
                        (SELECT cle_id FROM calendar_event WHERE 1 AND cle_id<ce1.cle_id order BY cle_id DESC LIMIT 0,1 ) AS PrevID
                        FROM calendar_event ce1 WHERE 1 AND cle_dow_type=1 and cle_is_event=1  AND cle_dw=7
                    )
                temp))";
		return DBUtil::execute($sql);
	}

	public static function updateEventFactor()
	{
		$eventFactor					 = Config::get('EventFactor');
		$param							 = array();
		$param['isEvent']				 = 0.1;
		$param['haloEvent']				 = 0.05;
		$param['isPhantomLongDowType']	 = 0.07;
		if (!empty($eventFactor))
		{
			$result							 = CJSON::decode($eventFactor);
			$param['isEvent']				 = $result['isevent'];
			$param['haloEvent']				 = $result['halo_event'];
			$param['isPhantomLongDowType']	 = $result['phantom_long_dow_type'];
		}
		$sql = "UPDATE calendar_event
                SET cle_factor=(1+ :isPhantomLongDowType*IF(cle_is_phantom_weekend=1 OR cle_is_long_weekend=1 OR cle_dow_type=1,1,0)+:isEvent*cle_is_event+:haloEvent*IF(cle_halo_event_id IS NOT NULL,1,0))
                WHERE 1";
		return DBUtil::execute($sql, $param);
	}

	public static function getEventRow($date)
	{
		$sql = "SELECT cle_event_id,cle_halo_event_id FROM calendar_event WHERE 1 AND cle_dt=:date AND cle_active=1";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['date' => $date]);
	}

	public static function updateEventByDate($date, $eventIds, $type)
	{
		//1=> Normal Event,2=>Halo event
		if ($type == 1 && $eventIds != null)
		{
			$sql = "UPDATE calendar_event SET cle_event_id=:eventIds WHERE 1 AND cle_dt=:date";
			return DBUtil::execute($sql, array('eventIds' => $eventIds, 'date' => $date));
		}
		else if ($type == 2 && $eventIds != null)
		{
			$sql = "UPDATE calendar_event SET cle_halo_event_id=:eventIds WHERE 1 AND cle_dt=:date";
			return DBUtil::execute($sql, array('eventIds' => $eventIds, 'date' => $date));
		}
	}

	public static function get90DayCalendar($pastDays, $nextDays)
	{
		$params	 = ['pastDays' => $pastDays, 'nextDays' => $nextDays];
		$sql	 = "SELECT 
				cle_dt,
				cle_day_name,
				cle_month_name,
				cle_dow_type,
				cle_event_id,
				cle_halo_event_id,
				cle_weighted_factor,
				hde_name,
				hde_slug,
				hde_description,
				event_geo.etg_id,
				event_geo.etg_event_lookup_id,
				event_geo.etg_std_or_not,
				event_geo.etg_affects_region_type,
				event_geo.etg_region_id,
				event_geo.etg_source_mzone_id,
				event_geo.etg_destination_mzone_id,
				event_geo.etg_source_zone_id,
				event_geo.etg_destination_zone_id,
				event_geo.etg_source_state_id,
				event_geo.etg_destination_state_id,
				event_geo.etg_source_city_id,
				event_geo.etg_destination_city_id,
				etg_margin
				FROM calendar_event
				INNER JOIN holiday_events ON 
				( 
					(FIND_IN_SET(holiday_events.hde_id, cle_event_id) AND cle_event_id IS NOT NULL) 
					OR
					(FIND_IN_SET(holiday_events.hde_id, cle_halo_event_id) AND cle_halo_event_id IS NOT NULL) 
				) AND holiday_events.hde_active=1
				INNER JOIN event_geo ON event_geo.etg_event_lookup_id=holiday_events.hde_id  AND etg_active=1
				WHERE 1
				AND cle_active=1
				AND cle_dt BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL :pastDays DAY), ' 00:00:00') AND CONCAT(DATE_ADD(CURDATE(),INTERVAL :nextDays DAY), ' 23:59:59')";

		$sqlCount = "SELECT 
					cle_id
					FROM calendar_event
					INNER JOIN holiday_events ON 
					( 
						(FIND_IN_SET(holiday_events.hde_id, cle_event_id) AND cle_event_id IS NOT NULL) 
						OR
						(FIND_IN_SET(holiday_events.hde_id, cle_halo_event_id) AND cle_halo_event_id IS NOT NULL) 
					) AND holiday_events.hde_active=1
					INNER JOIN event_geo ON event_geo.etg_event_lookup_id=holiday_events.hde_id  AND etg_active=1
					WHERE 1
					AND cle_active=1
					AND cle_dt BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL :pastDays DAY), ' 00:00:00') AND CONCAT(DATE_ADD(CURDATE(),INTERVAL :nextDays DAY), ' 23:59:59')";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['cle_dt'],
				'defaultOrder'	 => 'cle_id ASC'
			],
			'pagination'	 => ['pageSize' => 120],
		]);
		return $dataprovider;
	}

	/**
	 * This function is used to read  recurring rule from holiday table and map  with calendar event
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $holidayEventId.
	 * @return None
	 */
	public static function MapEventWithRule($holidayEventId)
	{
		if ($holidayEventId > 0)
		{
			$holidayEventsModel	 = HolidayEvents::model()->findbypk($holidayEventId);
			$recurrs_rule		 = json_decode($holidayEventsModel->hde_recurrs_rule);
			switch ($recurrs_rule->repeat)
			{
				case 'Daily':
					$eventId	 = $holidayEventsModel->hde_id;
					$begin		 = new DateTime(date_format(date_create($recurrs_rule->start), "Y-m-d"));
					$interval	 = $recurrs_rule->interval;
					if ($recurrs_rule->count > 0)
					{
						$recurrsCount	 = $recurrs_rule->count * $recurrs_rule->interval;
						$end			 = new DateTime(date('Y-m-d', strtotime(date_format(date_create($recurrs_rule->start), "Y-m-d") . " + $recurrsCount days"))); // add 40 days if 
					}
					else if ($recurrs_rule->until != null)
					{
						$end = new DateTime(date_format(date_create($recurrs_rule->until), "Y-m-d"));
					}
					else
					{
						$end = new DateTime(date('Y-m-d', strtotime(date_format(date_create($recurrs_rule->start), "Y-m-d") . " + 120 days"))); // add 120 days if 
					}

					for ($j = $begin; $j <= $end; $j->modify("+$interval  day"))
					{
						try
						{
							$date	 = $j->format("Y-m-d");
							$flag	 = CalendarEvent::IsEventByDate($date) > 0 ? 1 : 0;
							CalendarEvent::updateEvent($eventId, $date, $flag);
							if ($flag == 1)
							{
								$eventRowData	 = CalendarEvent::getEventRow($date);
								$eventIds		 = implode(',', array_unique(explode(',', $eventRowData['cle_event_id'])));
								CalendarEvent::updateEventByDate($date, $eventIds, 1);
							}
						}
						catch (Exception $ex)
						{
							$success = false;
						}
					}
					break;
				case 'Weekly':
					$eventId	 = $holidayEventsModel->hde_id;
					$begin		 = new DateTime(date_format(date_create($recurrs_rule->start), "Y-m-d"));
					$interval	 = $recurrs_rule->interval;
					if ($recurrs_rule->count > 0)
					{
						$recurrsCount	 = $recurrs_rule->count * $recurrs_rule->interval * 7;
						$end			 = new DateTime(date('Y-m-d', strtotime(date_format(date_create($recurrs_rule->start), "Y-m-d") . " + $recurrsCount days")));
					}
					else if ($recurrs_rule->until != null)
					{
						$end = new DateTime(date_format(date_create($recurrs_rule->until), "Y-m-d"));
					}
					else
					{
						$end = new DateTime(date('Y-m-d', strtotime(date_format(date_create($recurrs_rule->start), "Y-m-d") . " + 120 days"))); // add 120 days if 
					}

					for ($j = $begin; $j < $end; $j->modify("+$interval  week"))
					{
						$datesArray	 = Filter::getStartAndEndDate($j->format("Y-m-d"));
						$startWeek	 = new DateTime(date('Y-m-d', strtotime($datesArray['start_date'])));
						$endWeek	 = new DateTime(date('Y-m-d', strtotime($datesArray['end_date'])));
						for ($i = $startWeek; ($i <= $endWeek && $i <= $end); $i->modify("+1 day"))
						{
							$date = $i->format("Y-m-d");
							if (in_array(strtoupper(date('D', strtotime($date))), explode(",", $recurrs_rule->weekDays)))
							{
								try
								{
									$flag = CalendarEvent::IsEventByDate($date) > 0 ? 1 : 0;
									CalendarEvent::updateEvent($eventId, $date, $flag);
									if ($flag == 1)
									{
										$eventRowData	 = CalendarEvent::getEventRow($date);
										$eventIds		 = implode(',', array_unique(explode(',', $eventRowData['cle_event_id'])));
										CalendarEvent::updateEventByDate($date, $eventIds, 1);
									}
								}
								catch (Exception $ex)
								{
									$success = false;
								}
							}
						}
					}
					break;
				default:
					break;
			}
		}
	}

}
