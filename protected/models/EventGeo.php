<?php

/**
 * This is the model class for table "event_geo".
 *
 * The followings are the available columns in table 'event_geo':
 * @property string  $etg_id
 * @property integer $etg_std_or_not
 * @property integer $etg_affects_region_type
 * @property integer $etg_event_lookup_id
 * @property integer $etg_region_id
 * @property integer $etg_source_mzone_id
 * @property integer $etg_destination_mzone_id
 * @property integer $etg_source_zone_id
 * @property integer $etg_destination_zone_id
 * @property integer $etg_source_state_id
 * @property integer $etg_destination_state_id
 * @property integer $etg_source_city_id
 * @property integer $etg_destination_city_id
 * @property float  $etg_margin 
 * @property string  $etg_created_at
 * @property string  $etg_modified_at
 * @property integer $etg_active
 */
class EventGeo extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event_geo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('etg_created_at, etg_modified_at', 'required'),
			array('etg_std_or_not, etg_event_lookup_id,etg_affects_region_type,etg_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('etg_id, etg_std_or_not, etg_event_lookup_id, etg_affects_region_type,etg_region_id, etg_source_mzone_id,etg_destination_mzone_id,etg_source_zone_id,etg_destination_zone_id,etg_source_state_id,etg_destination_state_id,etg_source_city_id,etg_destination_city_id etg_created_at, etg_modified_at, etg_active,etg_margin', 'safe', 'on' => 'search'),
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
			'etg_id'					 => 'Event Id',
			'etg_std_or_not'			 => '1=>standard 0=>other',
			'etg_affects_region_type'	 => '-1=>No Region Affected,0=>All Region Affected,1=>Specified Region Affected',
			'etg_event_lookup_id'		 => 'this will contain holiday_events table id',
			'etg_source_mzone_id'		 => 'Source MzoneId',
			'etg_destination_mzone_id'	 => 'Destination MzoneId',
			'etg_source_zone_id'		 => 'Source zoneId',
			'etg_destination_zone_id'	 => 'Destination zoneId',
			'etg_source_state_id'		 => 'Source State Id',
			'etg_destination_State_id'	 => 'Destination State Id',
			'etg_source_city_id'		 => 'Source CityId',
			'etg_destination_city_id'	 => 'Destination CityId',
			'etg_margin'				 => 'Event Geo Margin',
			'etg_created_at'			 => 'Created datetime',
			'etg_modified_at'			 => 'Modified datetime',
			'etg_active'				 => '1=>active 0=>inactive',
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

		$criteria->compare('etg_id', $this->etg_id, true);
		$criteria->compare('etg_std_or_not', $this->etg_std_or_not);
		$criteria->compare('etg_affects_region_type', $this->etg_affects_region_type);
		$criteria->compare('etg_event_lookup_id', $this->etg_event_lookup_id);
		$criteria->compare('etg_region_id', $this->etg_region_id);
		$criteria->compare('etg_source_mzone_id', $this->etg_source_mzone_id);
		$criteria->compare('etg_destination_mzone_id', $this->etg_destination_mzone_id);
		$criteria->compare('etg_source_zone_id', $this->etg_source_zone_id);
		$criteria->compare('etg_destination_mzone_id', $this->etg_destination_mzone_id);
		$criteria->compare('etg_source_state_id', $this->etg_source_state_id);
		$criteria->compare('etg_destination_state_id', $this->etg_destination_state_id);
		$criteria->compare('etg_source_city_id', $this->etg_source_city_id);
		$criteria->compare('etg_destination_city_id', $this->etg_destination_city_id);
		$criteria->compare('etg_margin', $this->etg_margin);
		$criteria->compare('etg_created_at', $this->etg_created_at, true);
		$criteria->compare('etg_modified_at', $this->etg_modified_at, true);
		$criteria->compare('etg_active', $this->etg_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EventGeo the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getEventGeoDetails($eventId)
	{
		$sql = " SELECT * FROM event_geo WHERE etg_event_lookup_id=:eventId AND etg_active=1";
		return DBUtil::queryAll($sql, DBUtil::SDB(), ['eventId' => $eventId]);
	}

	public static function deleteByEventId($eventId)
	{
		$sql = "DELETE FROM  event_geo WHERE 1 AND etg_event_lookup_id=:eventId";
		return DBUtil::execute($sql, ['eventId' => $eventId]);
	}

	public static function InactiveByEventId($eventId)
	{
		$sql = "UPDATE event_geo SET etg_active=0 WHERE 1 AND etg_event_lookup_id=:eventId";
		return DBUtil::execute($sql, ['eventId' => $eventId]);
	}

	public static function add($data)
	{
		$eventGeoModel							 = new EventGeo();
		$eventGeoModel->etg_std_or_not			 = $data['isStandard'];
		$eventGeoModel->etg_affects_region_type	 = $data['affects_region_type'];
		$eventGeoModel->etg_event_lookup_id		 = $data['eventId'];
		$eventGeoModel->etg_region_id			 = $data['regions'];
		$eventGeoModel->etg_source_mzone_id		 = $data['source_mzone'];
		$eventGeoModel->etg_destination_mzone_id = $data['destination_mzone'];
		$eventGeoModel->etg_source_zone_id		 = $data['source_zone'];
		$eventGeoModel->etg_destination_zone_id	 = $data['destination_zone'];
		$eventGeoModel->etg_source_state_id		 = $data['source_state'];
		$eventGeoModel->etg_destination_state_id = $data['destination_state'];
		$eventGeoModel->etg_source_city_id		 = $data['source_city'];
		$eventGeoModel->etg_destination_city_id	 = $data['destination_city'];
		$eventGeoModel->etg_margin				 = $data['margin'] != null ? $data['margin'] : 1;
		$eventGeoModel->etg_created_at			 = DBUtil::getCurrentTime();
		$eventGeoModel->etg_modified_at			 = DBUtil::getCurrentTime();
		$eventGeoModel->etg_active				 = 1;
		if ($eventGeoModel->save())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function getByEventId($eventIds, $modelFrom, $modelTo)
	{
		$eventIds					 = is_string($eventIds) ? $eventIds : strval($eventIds);
		DBUtil::getINStatement($eventIds, $bindString, $param);
		$params						 = array();
		$eventIds					 = $eventIds;
		$modelFrom->ctm_region_id	 = "'" . $modelFrom->ctm_region_id . "'";

		$modelFrom->ctm_mzone_id = "'" . $modelFrom->ctm_mzone_id . "'";
		$modelTo->ctm_mzone_id	 = "'" . $modelTo->ctm_mzone_id . "'";

		$modelFrom->ctm_zone_id	 = "'" . $modelFrom->ctm_zone_id . "'";
		$modelTo->ctm_zone_id	 = "'" . $modelTo->ctm_zone_id . "'";

		$modelFrom->ctm_state_id = "'" . $modelFrom->ctm_state_id . "'";
		$modelTo->ctm_state_id	 = "'" . $modelTo->ctm_state_id . "'";

		$modelFrom->ctm_city_id	 = "'" . $modelFrom->ctm_city_id . "'";
		$modelTo->ctm_city_id	 = "'" . $modelTo->ctm_city_id . "'";

		$sql = "SELECT TEMP.etg_id AS cnt,TEMP.margin AS margin,TEMP.priority AS priority FROM 
                    (
                            SELECT etg_id,etg_margin AS margin,6 AS priority
                            FROM event_geo
                            WHERE 1
                            AND etg_event_lookup_id IN ($eventIds)
                            AND etg_active = 1
                            AND etg_affects_region_type  = 0
                            
                            UNION  
                            
                            SELECT etg_id,etg_margin,5 AS priority
                            FROM event_geo
                            WHERE     1
                            AND etg_event_lookup_id IN ($eventIds)
                            AND etg_active = 1
                            AND etg_affects_region_type  = 1
                            AND  CONCAT(',', IFNULL(etg_region_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelFrom->ctm_region_id, ',', '|'), '),')
                          
                            UNION      
            
                         
                            SELECT etg_id,etg_margin AS margin,4 AS priority
                            FROM event_geo
                            WHERE     1
                            AND etg_event_lookup_id IN ($eventIds)
                            AND etg_active = 1
                            AND etg_affects_region_type  = 1
                            AND   
                            (
                                (
                                 etg_source_mzone_id IS NOT NULL 
                                 AND CONCAT(',', IFNULL(etg_source_mzone_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelFrom->ctm_mzone_id, ',', '|'), '),')
                                 AND etg_destination_mzone_id IS NOT NULL
                                 AND CONCAT(',', IFNULL(etg_destination_mzone_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelTo->ctm_mzone_id, ',', '|'), '),')
                                )

                                OR 
                                (
                                 etg_source_mzone_id IS  NULL 
                                 AND etg_destination_mzone_id IS NOT NULL
                                 AND CONCAT(',', IFNULL(etg_destination_mzone_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelTo->ctm_mzone_id, ',', '|'), '),')

                                )
                                OR 
                                (
								 etg_destination_mzone_id IS  NULL
                                 AND etg_source_mzone_id IS NOT  NULL 
                                 AND CONCAT(',', IFNULL(etg_source_mzone_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelFrom->ctm_mzone_id, ',', '|'), '),')
                                )
                            )
      
      
                            UNION 
                            
                           
                            SELECT etg_id,etg_margin AS margin,3 AS priority
                            FROM event_geo
                            WHERE     1
                            AND etg_event_lookup_id IN ($eventIds)
                            AND etg_active = 1
                            AND etg_affects_region_type  = 1
                            AND   
                            (
                                (
                                 etg_source_zone_id IS NOT NULL 
                                 AND CONCAT(',', IFNULL(etg_source_zone_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelFrom->ctm_zone_id, ',', '|'), '),')
                                 AND etg_destination_zone_id IS NOT NULL
                                 AND CONCAT(',', IFNULL(etg_destination_zone_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelTo->ctm_zone_id, ',', '|'), '),')
                                )

                                OR 
                                (
                                 etg_source_zone_id IS  NULL 
                                 AND etg_destination_zone_id IS NOT NULL
                                 AND CONCAT(',', IFNULL(etg_destination_zone_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelTo->ctm_zone_id, ',', '|'), '),')

                                )
                                OR 
                                (
                                 etg_source_zone_id IS NOT  NULL 
                                 AND CONCAT(',', IFNULL(etg_source_zone_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelFrom->ctm_zone_id, ',', '|'), '),')
                                 AND etg_destination_zone_id IS NULL
                                )
                            )

                            UNION 
     
                            
                            SELECT etg_id,etg_margin AS margin,2 AS priority
                            FROM event_geo
                            WHERE  1
                            AND etg_event_lookup_id IN ($eventIds)
                            AND etg_active = 1
                            AND etg_affects_region_type  = 1
                            AND   
                            (
                                (
                                 etg_source_state_id IS NOT NULL 
                                 AND CONCAT(',', IFNULL(etg_source_state_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelFrom->ctm_state_id, ',', '|'), '),')
                                 AND etg_destination_state_id IS NOT NULL
                                 AND CONCAT(',', IFNULL(etg_destination_state_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelTo->ctm_state_id, ',', '|'), '),')
                                )
                                OR 
                                (
                                 etg_source_state_id IS  NULL 
                                 AND etg_destination_state_id IS NOT NULL
                                 AND CONCAT(',', IFNULL(etg_destination_state_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelTo->ctm_state_id, ',', '|'), '),')

                                )
                                OR 
                                (
                                 etg_source_state_id IS NOT  NULL 
                                 AND CONCAT(',', IFNULL(etg_source_state_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelFrom->ctm_state_id, ',', '|'), '),')
                                 AND etg_destination_state_id IS NULL
                                )
                            )

                            UNION 
                            
                            SELECT etg_id,etg_margin AS margin,1 AS priority
                            FROM event_geo
                            WHERE     1
                            AND etg_event_lookup_id IN ($eventIds)
                            AND etg_active = 1
                            AND etg_affects_region_type  = 1
                            AND   
                              (
                                  (
                                   etg_source_city_id IS NOT NULL 
                                   AND CONCAT(',', IFNULL(etg_source_city_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelFrom->ctm_city_id, ',', '|'), '),')
                                   AND etg_destination_city_id IS NOT NULL
                                   AND CONCAT(',', IFNULL(etg_destination_city_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelTo->ctm_city_id, ',', '|'), '),')
                                  )
                                  OR 
                                  (
                                   etg_source_city_id IS  NULL 
                                   AND etg_destination_city_id IS NOT NULL
                                   AND CONCAT(',', IFNULL(etg_destination_city_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelTo->ctm_city_id, ',', '|'), '),')

                                  )
                                  OR 
                                  (
                                   etg_source_city_id IS NOT  NULL 
                                   AND CONCAT(',', IFNULL(etg_source_city_id, '0'), ',') REGEXP CONCAT(',(', REPLACE($modelFrom->ctm_city_id, ',', '|'), '),')
                                   AND etg_destination_city_id IS NULL
                                  )
                              )
                        ) TEMP  WHERE 1 ORDER BY  TEMP.margin DESC,priority ASC ";

		Logger::info("sqlQuery:" . $sql);
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

}
