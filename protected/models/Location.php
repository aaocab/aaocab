<?php

/**
 * This is the model class for table "location".
 *
 * The followings are the available columns in table 'location':
 * @property integer $loc_id
 * @property integer $loc_entity_id
 * @property integer $loc_entity_type
 * @property integer $loc_ref_id
 * @property integer $loc_ref_type
 * @property string $loc_time
 * @property double $loc_lat
 * @property double $loc_lng
 * @property double $loc_city_id
 * @property double $loc_zone_id
 * @property double $loc_hzone_id
 * @property integer $loc_event_id
 * @property string $loc_desc
 * @property string $loc_device_uuid
 * @property datetime $loc_create_date
 * @property integer $loc_status
 */
class Location extends CActiveRecord
{
	const REF_TYPE_BOOKING = 1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'location';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loc_entity_id, loc_entity_type, loc_time, loc_lat, loc_lng', 'required'),
			array('loc_entity_id, loc_entity_type, loc_status', 'numerical', 'integerOnly' => true),
			array('loc_lat, loc_lng', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('loc_id, loc_entity_id, loc_entity_type, loc_time, loc_lat, loc_lng, loc_status,loc_create_date,loc_city_id,loc_zone_id,loc_zone_id, loc_ref_id, loc_ref_type, loc_event_id, loc_desc, loc_device_uuid', 'safe', 'on' => 'search'),
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
			'loc_id'			 => 'Location Id',
			'loc_entity_id'		 => 'Location Entity Id',
			'loc_entity_type'	 => 'Location Entity Type',
			'loc_ref_id'		 => 'Location Ref Id',
			'loc_ref_type'		 => 'Location Ref Type',
			'loc_time'			 => 'Location Time',
			'loc_lat'			 => 'Location Latitude',
			'loc_lng'			 => 'Location Longitude',
			'loc_city_id'		 => 'Location City ',
			'loc_zone_id'		 => 'Location Zone',
			'loc_event_id'		 => 'Location Event Id',
			'loc_desc'			 => 'Location Desc ',
			'loc_device_uuid'	 => 'Location Device UUID',
			'loc_create_date'	 => 'Location Create At',
			'loc_status'		 => 'Location Status',
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

		$criteria->compare('loc_id', $this->loc_id);
		$criteria->compare('loc_entity_id', $this->loc_entity_id);
		$criteria->compare('loc_entity_type', $this->loc_entity_type);
		$criteria->compare('loc_ref_id', $this->loc_ref_id);
		$criteria->compare('loc_ref_type', $this->loc_ref_type);
		$criteria->compare('loc_time', $this->loc_time, true);
		$criteria->compare('loc_lat', $this->loc_lat);
		$criteria->compare('loc_lng', $this->loc_lng);
		$criteria->compare('loc_city_id', $this->loc_city_id);
		$criteria->compare('loc_zone_id', $this->loc_zone_id);
		$criteria->compare('loc_hzone_id', $this->loc_hzone_id);
		$criteria->compare('loc_event_id', $this->loc_event_id);
		$criteria->compare('loc_desc', $this->loc_desc);
		$criteria->compare('loc_device_uuid', $this->loc_device_uuid);
		$criteria->compare('loc_create_date', $this->loc_create_date);
		$criteria->compare('loc_status', $this->loc_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Location the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used for saving all entity type Latitude and Longitude
	 * @param type $data
	 * @param type $userInfo
	 * @return type boolean 
	 */
	public static function addLocation($data, UserInfo $userInfo = null)
	{
		$success = false;
		if ($userInfo == null)
		{
			$userInfo = UserInfo::model();
		}

		$model					 = new Location();
		$model->loc_entity_type	 = $userInfo->getUserType();
		$model->loc_entity_id	 = $userInfo->getUserType() == 4 ? $userInfo->getUserId() : $userInfo->getEntityId();
		$model->loc_time		 = $data['timeStamp'] != null ? date('Y-m-d H:i:s', $data['timeStamp'] / 1000) : DBUtil::getCurrentTime();
		$model->loc_create_date	 = DBUtil::getCurrentTime();
		$model->loc_lat			 = $data['lat'];
		$model->loc_lng			 = $data['lon'];
		$cityId					 = Cities::getCityByLatLng($data['lat'], $data['lon']);
		$model->loc_city_id		 = ($cityId == null || $cityId == 0) ? 0 : $cityId;
		$model->loc_zone_id		 = ($cityId == null || $cityId == 0) ? 0 : Zones::model()->getNearestZonebyCity($cityId)['zon_id'];
		$vnd_home_zone			 = $userInfo->getUserType() == 2 ? VendorPref::model()->getByVendorId($userInfo->getEntityId())->vnp_home_zone : 0;
		$model->loc_hzone_id	 = trim($vnd_home_zone) != "" && is_int((int) $vnd_home_zone) ? $vnd_home_zone : 0;

		self::mapTripEvents($data, $model);

		if ($model->validate())
		{
			if ($model->save())
			{
				$latitude			 = $data['lat'];
				$longtitude			 = $data['lon'];
				$entityId			 = $userInfo->getUserType == 4 ? $userInfo->getUserId() : $userInfo->getEntityId();
				$entityType			 = $userInfo->getUserType();
				$contactId			 = $userInfo->getUserType() == 4 ? 0 : ContactProfile::getByEntityId($userInfo->getEntityId(), $userInfo->getUserType());
				$lastActiveStat		 = new Stub\common\LastActiveStats();
				$lastActiveData		 = $lastActiveStat->setData($latitude, $longtitude, $entityId, $entityType, $contactId);
				$lastActiveresponse	 = Filter::removeNull($lastActiveData);
				if ((int) $latitude != 0 && (int) $longtitude != 0)
				{
					IRead::setLocationRequest($lastActiveresponse);
				}
				$success = true;
			}
			else
			{
				$getErrors = json_encode($model->getErrors());
			}
		}
		else
		{
			$getErrors = json_encode($model->getErrors());
		}
		return $success;
	}

	public static function mapTripEvents($data, &$model)
	{
		$refId	 = null;
		$refType = null;
		if (isset($data['bkg_id']) && $data['bkg_id'] > 0)
		{
			$refId	 = $data['bkg_id'];
			$refType = 1;
		}
		elseif (isset($data->bkg_id) && $data->bkg_id > 0)
		{
			$refId	 = $data->bkg_id;
			$refType = 1;
		}
		elseif (isset($data['loc_ref_id']) && $data['loc_ref_id'] > 0)
		{
			$refId	 = $data['loc_ref_id'];
			$refType = $data['loc_ref_type'];
		}
		if (isset($data['loc_device_uuid']))
		{
			$model->loc_device_uuid = $data['loc_device_uuid'];
		}
		if (!self::checkValidBookingForTaggingWithLocation($refId))
		{
			return false;
		}

		$model->loc_ref_id	 = ($refId > 0 ? $refId : NULL);
		$model->loc_ref_type = ($refType > 0 ? $refType : NULL);

		if (isset($data['loc_event_id']))
		{
			$model->loc_event_id = $data['loc_event_id'];
		}
		if (isset($data['loc_desc']))
		{
			$model->loc_desc = $data['loc_desc'];
		}
	}

	public static function checkValidBookingForTaggingWithLocation($bkgId)
	{
		if (!$bkgId || $bkgId <= 0 || $bkgId == null)
		{
			return false;
		}

		/** @var Booking $bkgModel */
		$bkgModel = Booking::model()->findByPk($bkgId);
		if ($bkgModel->bkg_status != 5)
		{
			return false;
		}

		$tripLeftForPickup = BookingTrackLog::model()->getdetailByEvent($bkgId, 201); //Going for pickup
		if (!$tripLeftForPickup)
		{
			return false;
		}

		$tripEnd = BookingTrackLog::model()->getdetailByEvent($bkgId, 104); //End Trip
		if ($tripEnd && (time() > (strtotime($tripEnd['btl_sync_time']) + (60 * 15))))
		{
			return false;
		}

		if ($bkgModel->bkg_return_date != null && (time() > (strtotime($bkgModel->bkg_return_date) + (60 * 60 * 2))))
		{
			return false;
		}
		if (time() > (strtotime($bkgModel->bkg_pickup_date) + ($bkgModel->bkg_trip_duration * 60) + (60 * 60 * 2)))
		{
			return false;
		}

		return true;
	}

	/**
	 * This function is used return all the vendor/driver that are present within 28km diameter from given city lat and long and 1 hour time range
	 * @param type $latitude
	 * @param type $longtitude
	 * @return type row array 
	 */
	public static function getVendorDriverByLatLong($city, $lat, $lng)
	{
		$key	 = "city:{$city}";
//        $result = Yii::app()->cache->get($key);
//        if ($result !== false)
//        {
//            goto result;
//        }
		$sql	 = "SELECT 
                COUNT(DISTINCT IF(location.loc_entity_type = 2 AND loc_entity_id IS NOT NULL, loc_entity_id,NULL)) AS cntVendor,
                GROUP_CONCAT(DISTINCT IF(location.loc_entity_type = 2, loc_entity_id, NULL)) AS vendorIds,
                COUNT( DISTINCT IF(location.loc_entity_type = 3 AND loc_entity_id IS NOT NULL, loc_entity_id,NULL)) AS cntDriver,
                GROUP_CONCAT(DISTINCT IF(location.loc_entity_type = 3, loc_entity_id, NULL)) AS driverIds
                FROM   location
                WHERE  1 
                AND loc_time BETWEEN DATE_SUB(NOW(),INTERVAL 1 HOUR) AND NOW()
                AND (location.loc_lat BETWEEN (:lat - 0.125) AND (:lat  + 0.125)) 
                AND (location.loc_lng BETWEEN (:lng - 0.125) AND(:lng + 0.125))";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), ['lat' => $lat, 'lng' => $lng], 60 * 30, CacheDependency::Type_Cities);
		Yii::app()->cache->set($key, $result, 60 * 30, new CacheDependency("cities"));
		result:
		return $result;
	}

	/**
	 * This function is used return all the vendor/driver that are present within  given zone id and 1 hour time range
	 * @param type $zoneId
	 * @return type row array 
	 */
	public static function getVendorDriverByZone($zoneId)
	{
		$key	 = "zone:{$zoneId}";
		$sql	 = "SELECT 
                COUNT(DISTINCT IF(location.loc_entity_type = 2 AND loc_entity_id IS NOT NULL, loc_entity_id,NULL)) AS cntVendor,
                GROUP_CONCAT(DISTINCT IF(location.loc_entity_type = 2, loc_entity_id, NULL)) AS vendorIds,
                COUNT( DISTINCT IF(location.loc_entity_type = 3 AND loc_entity_id IS NOT NULL, loc_entity_id,NULL)) AS cntDriver,
                GROUP_CONCAT(DISTINCT IF(location.loc_entity_type = 3, loc_entity_id, NULL)) AS driverIds
                FROM  location
                WHERE  1 
                AND loc_time BETWEEN DATE_SUB(NOW(),INTERVAL 1 HOUR) AND NOW()
                AND ((loc_zone_id=:zoneId AND loc_zone_id>0) OR  (loc_hzone_id=:zoneId AND loc_hzone_id>0))";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), ['zoneId' => $zoneId], 60 * 30, CacheDependency::Type_Zones);
		Yii::app()->cache->set($key, $result, 60 * 30, new CacheDependency("zones"));
		result:
		return $result;
	}

	/**
	 * This function is used return all the vendor/driver that are present within  given city id and 1 hour time range
	 * @param type $cityId
	 * @return type row array 
	 */
	public static function getVendorDriverByCity($cityId, $durationHour = 1)
	{
		$key	 = "city:{$cityId}";
		$sql	 = "SELECT 
                COUNT(DISTINCT IF(location.loc_entity_type = 2 AND loc_entity_id IS NOT NULL, loc_entity_id,NULL)) AS cntVendor,
                GROUP_CONCAT(DISTINCT IF(location.loc_entity_type = 2, loc_entity_id, NULL)) AS vendorIds,
                COUNT( DISTINCT IF(location.loc_entity_type = 3 AND loc_entity_id IS NOT NULL, loc_entity_id,NULL)) AS cntDriver,
                GROUP_CONCAT(DISTINCT IF(location.loc_entity_type = 3, loc_entity_id, NULL)) AS driverIds
                FROM  location
                WHERE  1 
                AND loc_time BETWEEN DATE_SUB(NOW(),INTERVAL $durationHour HOUR) AND NOW()
                AND loc_city_id=:cityId";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), ['cityId' => $cityId], 60 * 30, CacheDependency::Type_Cities);
		Yii::app()->cache->set($key, $result, 60 * 30, new CacheDependency("cities"));
		result:
		return $result;
	}

	/**
	 * This function is used return all the vendor/driver that are present within  given zone id and 1 hour time range
	 * @param type $zoneId
	 * @return type row array 
	 */
	public static function getVendorDriverByZoneIds($zoneIds)
	{
		$key	 = "zone:{$zoneIds}";
		$sql	 = "SELECT 
                COUNT(DISTINCT IF(location.loc_entity_type = 2 AND loc_entity_id IS NOT NULL, loc_entity_id,NULL)) AS cntVendor,
                GROUP_CONCAT(DISTINCT IF(location.loc_entity_type = 2, loc_entity_id, NULL)) AS vendorIds,
                COUNT( DISTINCT IF(location.loc_entity_type = 3 AND loc_entity_id IS NOT NULL, loc_entity_id,NULL)) AS cntDriver,
                GROUP_CONCAT(DISTINCT IF(location.loc_entity_type = 3, loc_entity_id, NULL)) AS driverIds
                FROM  location
                WHERE  1 
                AND loc_time BETWEEN DATE_SUB(NOW(),INTERVAL 5 HOUR) AND NOW()
                AND ((loc_zone_id IN ($zoneIds) AND loc_zone_id>0) OR  (loc_hzone_id  IN ($zoneIds) AND loc_hzone_id>0))";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB());
		Yii::app()->cache->set($key, $result, 60 * 30, new CacheDependency("zones"));
		result:
		return $result;
	}

	/**
	 * This function is used return all the vendor/driver that are present within  given zone id and 5 hour time range based on priority
	 * @param type $zoneId
	 * @return type query object 
	 */
	public static function getVendorDriverByZoneIds_V1($zoneIds)
	{
		$key	 = "zone:{$zoneIds}";
		$sql	 = "SELECT 
                   temp.vendorIds,temp.locType
                   FROM 
                    (
                        SELECT 
                        DISTINCT vdrv_vnd_id AS vendorIds,
                        1 AS locType
                        FROM  location
                        INNER JOIN vendor_driver ON vendor_driver.vdrv_drv_id=location.loc_entity_id
                        WHERE  1 
                        AND loc_time BETWEEN DATE_SUB(NOW(),INTERVAL 5 HOUR) AND NOW()
                        AND loc_entity_type=3 
                        AND vendor_driver.vdrv_active=1
                        AND loc_zone_id IN ($zoneIds) AND loc_zone_id>0

                        UNION

                        SELECT 
                        DISTINCT loc_entity_id AS vendorIds,
                        2 AS locType
                        FROM  location
                        WHERE  1 
                        AND loc_time BETWEEN DATE_SUB(NOW(),INTERVAL 5 HOUR) AND NOW()
                        AND loc_entity_type=2 
                        AND loc_hzone_id IN ($zoneIds) AND loc_hzone_id>0
                        
                        UNION 
                        
                        SELECT 
                        DISTINCT loc_entity_id AS vendorIds,
                        3 AS locType
                        FROM  location
                        WHERE  1 
                        AND loc_time BETWEEN DATE_SUB(NOW(),INTERVAL 5 HOUR) AND NOW()
                        AND loc_entity_type=2 
                        AND loc_zone_id IN ($zoneIds) AND loc_zone_id>0
	            ) temp  WHERE 1 GROUP BY temp.vendorIds ORDER BY temp.locType ASC";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		Yii::app()->cache->set($key, $result, 60 * 30, new CacheDependency("zones"));
		result:
		return $result;
	}

	/**
	 * Function for archiving location
	 */
	public function archiveData($archiveDB, $upperLimit = 1000000, $lowerLimit = 1000)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerLimit;
		while ($chk)
		{
			$transaction = "";
			try
			{
				$sql	 = "SELECT GROUP_CONCAT(loc_id) AS loc_id FROM (SELECT loc_id FROM location WHERE 1 AND loc_create_date < CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), ' 00:00:00') ORDER BY loc_id  LIMIT 0, $limit) as temp";
				$resQ	 = DBUtil::queryScalar($sql);
				if (!is_null($resQ) && $resQ != '')
				{
					$transaction = DBUtil::beginTransaction();
					DBUtil::getINStatement($resQ, $bindString, $params);
					$sql		 = "INSERT INTO " . $archiveDB . ".location (SELECT * FROM location WHERE loc_id IN ($bindString))";
					$rows		 = DBUtil::execute($sql, $params);
					if ($rows > 0)
					{
						$sql = "DELETE FROM `location` WHERE loc_id IN ($bindString)";
						DBUtil::execute($sql, $params);
						DBUtil::commitTransaction($transaction);
					}
					else
					{
						DBUtil::rollbackTransaction($transaction);
					}
				}

				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
				echo $ex->getMessage() . "\n\n";
			}
		}
	}

}
