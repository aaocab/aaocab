<?php

/**
 * This is the model class for table "mmt_data_pickup".
 *
 * The followings are the available columns in table 'mmt_data_pickup':
 * @property integer $mdp_id
 * @property string $mdp_date
 * @property integer $mdp_from_city_id
 * @property integer $mdp_to_city_id
 * @property integer $mdp_booking_type
 * @property integer $mdp_search_count
 * @property integer $mdp_hold_count
 * @property integer $mdp_confirm_count
 * @property string $mdp_create_date
 * @property string $mdp_modify_date
 */
class MmtDataPickup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mmt_data_pickup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mdp_date, mdp_from_city_id, mdp_to_city_id, mdp_booking_type, mdp_create_date, mdp_modify_date', 'required'),
			array('mdp_from_city_id, mdp_to_city_id, mdp_booking_type, mdp_search_count, mdp_hold_count, mdp_confirm_count', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('mdp_id, mdp_date, mdp_from_city_id, mdp_to_city_id, mdp_booking_type, mdp_search_count, mdp_hold_count, mdp_confirm_count, mdp_create_date, mdp_modify_date', 'safe', 'on'=>'search'),
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
			'mdp_id' => 'Mdp',
			'mdp_date' => 'Mdp Date',
			'mdp_from_city_id' => 'Mdp From City',
			'mdp_to_city_id' => 'Mdp To City',
			'mdp_booking_type' => 'Mdp Booking Type',
			'mdp_search_count' => 'Mdp Search Count',
			'mdp_hold_count' => 'Mdp Hold Count',
			'mdp_confirm_count' => 'Mdp Confirm Count',
			'mdp_create_date' => 'Mdp Create Date',
			'mdp_modify_date' => 'Mdp Modify Date',
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

		$criteria=new CDbCriteria;

		$criteria->compare('mdp_id',$this->mdp_id);
		$criteria->compare('mdp_date',$this->mdp_date,true);
		$criteria->compare('mdp_from_city_id',$this->mdp_from_city_id);
		$criteria->compare('mdp_to_city_id',$this->mdp_to_city_id);
		$criteria->compare('mdp_booking_type',$this->mdp_booking_type);
		$criteria->compare('mdp_search_count',$this->mdp_search_count);
		$criteria->compare('mdp_hold_count',$this->mdp_hold_count);
		$criteria->compare('mdp_confirm_count',$this->mdp_confirm_count);
		$criteria->compare('mdp_create_date',$this->mdp_create_date,true);
		$criteria->compare('mdp_modify_date',$this->mdp_modify_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MmtDataPickup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param type $fromZoneCities
	 * @param type $toZoneCities
	 * @param type $pickupDate
	 * @param type $tripType
	 * @return int | false
	 */
	public static function getCountByPickupDate($fromZoneCities, $toZoneCities, $pickupDate, $tripType)
	{
		$pickupDate	 = date('Y-m-d', strtotime($pickupDate));
		$param		 = ["pickupDate" => $pickupDate, "tripType" => $tripType];

//		DBUtil::getINStatement($fromZoneCities, $bindString, $params);
//		DBUtil::getINStatement($toZoneCities, $bindString1, $params1);

		//$mergedParams = array_merge($params, $param, $params1);

		$sql	 = "SELECT SUM(mdp_search_count) as searchCount, SUM(mdp_confirm_count) as confirmCount 
				FROM mmt_data_pickup 
				WHERE mdp_from_city_id IN ({$fromZoneCities}) AND mdp_to_city_id IN ({$toZoneCities}) 
				AND mdp_date = '{$pickupDate}' AND mdp_booking_type = {$tripType}";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 60 * 60, CacheDependency::Type_Routes);

		return $result;
	}

	/**
	 * 
	 * @param type $fromZoneCities
	 * @param type $toZoneCities
	 * @param type $tripType
	 * @return int | false
	 */
	public static function getCountByCreateDate($fromZoneCities, $toZoneCities, $tripType)
	{
		$param = ["tripType" => $tripType];

		$sql	 = "SELECT SUM(mdc_search_count) as searchCount, SUM(mdc_confirm_count) as confirmCount 
				FROM mmt_data_created 
				WHERE mdc_from_city_id IN ({$fromZoneCities}) AND mdc_to_city_id IN ({$toZoneCities}) 
				AND mdc_booking_type = {$tripType} AND mdc_date >=DATE_SUB(CURDATE(), INTERVAL 48 HOUR)";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 60 * 60, CacheDependency::Type_Routes);

		return $result;
	}

	/**
	 * 
	 * @param type $fromCity
	 * @param type $toCity
	 * @param type $pickupDate
	 * @param type $tripType
	 * @return boolean
	 */
	public static function isAllowed($fromCity, $toCity, $pickupDate, $tripType)
	{
		$success = 1;
		$factor1 = 1500;

		$date = date("Ymd", strtotime($pickupDate));

		// Cache
		$key	 = "mmtpickup_{$tripType}_{$fromCity}_{$toCity}_{$date}";
		$result	 = Yii::app()->cache->get($key);
		if ($result !== false)
		{
			$success = $result;
			goto result;
		}

		// Zones
		$fromZone	 = Zones::model()->getByCityId($fromCity);
		$toZone		 = Zones::model()->getByCityId($toCity);
		if (!$fromZone || !$toZone)
		{
			goto result;
		}

		// Zone Cities
		$fromZoneCities	 = ZoneCities::getCitiesByZones($fromZone);
		$toZoneCities	 = ZoneCities::getCitiesByZones($toZone);
		if (!$fromZoneCities || !$toZoneCities)
		{
			goto result;
		}

		// Count By Pickup Date
		$countByPickupDate = self::getCountByPickupDate($fromZoneCities, $toZoneCities, $pickupDate, $tripType);
		if (!$countByPickupDate)
		{
			goto result;
		}
		$searchCountByPickupDate	 = $countByPickupDate['searchCount'];
		$confirmCountByPickupDate	 = $countByPickupDate['confirmCount'];

		// Count By Create Date
		$countByCreateDate = self::getCountByCreateDate($fromZoneCities, $toZoneCities, $tripType);
		if (!$countByCreateDate)
		{
			goto result;
		}
		$searchCountByCreateDate	 = $countByCreateDate['searchCount'];
		$confirmCountByCreateDate	 = $countByCreateDate['confirmCount'];

		$factor2 = $factor1 * 2;
		$factor4 = $factor1 * 3;

		// Check Factors
		if ($searchCountByPickupDate > $factor2 || $searchCountByCreateDate > $factor1)
		{
			$pickupDateRatio = (($confirmCountByPickupDate / $searchCountByPickupDate) * $factor1);
			$createDateRatio = (($confirmCountByCreateDate / $searchCountByCreateDate) * $factor1);

			$pickupDateRatio2	 = (($confirmCountByPickupDate / $searchCountByPickupDate) * $factor2);
			$createDateRatio2	 = (($confirmCountByCreateDate / $searchCountByCreateDate) * $factor4);

			if ($pickupDateRatio >= 1 || $createDateRatio >= 1)
			{
				goto skipValidation;
			}

			if (($searchCountByPickupDate > $factor1 && $searchCountByCreateDate > $factor1) || ($searchCountByPickupDate > $factor2) || ($searchCountByCreateDate > $factor4))
			{
				$success = 0;
			}
		}
		skipValidation:
		Yii::app()->cache->set($key, $success, 60 * 60, new CacheDependency("Routes"));

		result:
		return $success;
	}
}