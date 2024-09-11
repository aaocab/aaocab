<?php

/**
 * This is the model class for table "partner_area_rule".
 *
 * The followings are the available columns in table 'partner_area_rule':
 * @property integer $par_id
 * @property integer $par_partner_id
 * @property integer $par_area_type
 * @property integer $par_area_id
 * @property integer $par_cab_type
 * @property integer $par_trip_type
 * @property integer $par_min_pickup_time
 * @property integer $par_cancellation_policy_rule
 * @property string $par_min_advance
 * @property string $par_created_date
 * @property string $par_modified_date
 * @property integer $par_active
 */
class PartnerAreaRule extends CActiveRecord
{
	const ADVANCE_AMOUNT_CHECK		 = 1;
	const CANCELLATION_POLICY_CHECK	 = 2;
	const MINIMUM_TIME_CHECK			 = 3;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_area_rule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('par_created_date, par_modified_date', 'required'),
			array('par_partner_id, par_area_type, par_area_id, par_cab_type, par_trip_type, par_min_pickup_time, par_cancellation_policy_rule, par_active', 'numerical', 'integerOnly'=>true),
			array('par_min_advance', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('par_id, par_partner_id, par_area_type, par_area_id, par_cab_type, par_trip_type, par_min_pickup_time, par_cancellation_policy_rule, par_min_advance, par_created_date, par_modified_date, par_active', 'safe', 'on'=>'search'),
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
			'par_id' => 'Par',
			'par_partner_id' => 'Par Partner',
			'par_area_type' => 'Par Area Type',
			'par_area_id' => 'Par Area',
			'par_cab_type' => 'Par Cab Type',
			'par_trip_type' => 'Par Trip Type',
			'par_min_pickup_time' => 'Par Min Pickup Time',
			'par_cancellation_policy_rule' => 'Par Cancellation Policy Rule',
			'par_min_advance' => 'Par Min Advance',
			'par_created_date' => 'Par Created Date',
			'par_modified_date' => 'Par Modified Date',
			'par_active' => 'Par Active',
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

		$criteria->compare('par_id',$this->par_id);
		$criteria->compare('par_partner_id',$this->par_partner_id);
		$criteria->compare('par_area_type',$this->par_area_type);
		$criteria->compare('par_area_id',$this->par_area_id);
		$criteria->compare('par_cab_type',$this->par_cab_type);
		$criteria->compare('par_trip_type',$this->par_trip_type);
		$criteria->compare('par_min_pickup_time',$this->par_min_pickup_time);
		$criteria->compare('par_cancellation_policy_rule',$this->par_cancellation_policy_rule);
		$criteria->compare('par_min_advance',$this->par_min_advance,true);
		$criteria->compare('par_created_date',$this->par_created_date,true);
		$criteria->compare('par_modified_date',$this->par_modified_date,true);
		$criteria->compare('par_active',$this->par_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerAreaRule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param int $fromCity
	 * @param int $tripType
	 * @param int $cabType
	 * @return PriceRule
	 */
	public static function getByCity($fromCity, $tripType, $cabType, $toCity = 0, $partnerId, $type)
	{
		$key = "PartnerAreaRule::getByCity:{$fromCity}_{$toCity}_{$tripType}_{$cabType}_{$partnerId}";
		if (isset($GLOBALS[$key]))
		{
			$partnerAreaRule = $GLOBALS[$key];
			goto result;
		}

		$areaId		 = $fromCity;
		$areaType	 = 3;

//		$rutId = Route::getIdByCities($fromCity, $toCity);
//
//		if ($rutId != '')
//		{
//			$areaId		 = $rutId;
//			$areaType	 = 5;
//		}

		$partnerAreaRule = self::findByAreaCabTripType($areaType, $areaId, $cabType, $tripType, $partnerId, $type);

		if ($partnerAreaRule != '')
		{
			$GLOBALS[$key] = $partnerAreaRule;
		}
		result:
		return $partnerAreaRule;
	}

	/**
	 * 
	 * @param type $areaType
	 * @param type $areaId
	 * @param type $cabType
	 * @param type $tripType
	 * @return PartnerAreaRule
	 */
	public static function findByAreaCabTripType($areaType, $areaId, $cabType, $tripType, $partnerId = null, $type)
	{
		$key = "partnerAreaRule::findByAreaCabTripType:{$areaType}_{$areaId}_{$tripType}_{$cabType}_{$partnerId}";
		if (isset($GLOBALS[$key]))
		{
			$partnerAreaRule = $GLOBALS[$key];
			goto result;
		}

		$areaRule = self::findRulesByAreaType($areaType, $areaId, $cabType, $tripType, $partnerId, $type);
		if ($areaRule != [])
		{
			$id			 = $areaRule['par_id'];
			$partnerAreaRule	 = PartnerAreaRule::model()->findByPk($id);
		}

		if ($partnerAreaRule != '')
		{
			$GLOBALS[$key] = $partnerAreaRule;
		}
		result:
		return $partnerAreaRule;
	}

	/**
	 * 
	 * @param int $fromCity
	 * @param int $tripType
	 * @param int $cabType
	 * @return partnerAreaRule
	 */
	public static function findByAreaParentCabType($areaType, $areaId, $tripType, $cabType, $partnerId)
	{
		$key = "partnerAreaRule::findByAreaParentCabType:{$areaType}_{$areaId}_{$tripType}_{$cabType}_{$partnerId}";
		if (isset($GLOBALS[$key]))
		{
			$partnerAreaRule = $GLOBALS[$key];
			goto result;
		}

		$row = SvcClassVhcCat::model()->findByPk($cabType);
		if (!$row || $row->scv_parent_id <= 0)
		{
			$partnerAreaRule = null;
			goto result;
		}

		$parentCabType	 = $row->scv_parent_id;
		$parentAreaRule = self::findByAreaCabTripType($areaType, $areaId, $parentCabType, $tripType, $partnerId);

		if ($parentAreaRule == null)
		{
			goto result;
		}

		$partnerAreaRule = ServiceClassRule::getAreaPriceRuleWithMarkUp($areaType, $areaId, $cabType, $parentAreaRule);

		result:
		return $partnerAreaRule;
	}

	/**
	 * 
	 * @param type $areaType
	 * @param type $areaId
	 * @param type $cab
	 * @return type
	 */
	public static function findRulesByAreaType($areaType, $areaId, $cab = 0, $tripType, $partnerId, $type)
	{
		$paramsRoute		 = $paramsZone			 = $paramsCity			 = $paramsState		 = $paramsRegion		 = [];
		$bindRouteString	 = $bindZonesString	 = $bindCityString		 = $bindStateString	 = $bindRegionString	 = null;

		if ($areaType == 5)
		{
			$rutModel	 = Route::model()->findByPk($areaId);
			$cityId		 = $rutModel->rut_from_city_id;
			$paramsRoute = DBUtil::getINStatement($areaId, $bindRouteString, $paramsRoute);
		}

		if ($areaType == 3 || $cityId != '')
		{
			if($cityId == '')
			{
				$cityId				 = $areaId;
			}
			$fcityInfo			 = Cities::getStateZoneInfoByCity($cityId);
			$fcityInfo['zones']	 = ($fcityInfo['zones'] == '') ? 0 : $fcityInfo['zones'];
			$fcityInfo['stt_id'] = ($fcityInfo['stt_id'] == '') ? 0 : $fcityInfo['stt_id'];
			$fcityInfo['region'] = ($fcityInfo['region'] == '') ? 0 : $fcityInfo['region'];
			$paramsZone			 = DBUtil::getINStatement($fcityInfo['zones'], $bindZonesString, $paramsZone);
			$paramsCity			 = DBUtil::getINStatement($cityId, $bindCityString, $paramsCity);
			$paramsState		 = DBUtil::getINStatement($fcityInfo['stt_id'], $bindStateString, $paramsState);
			$paramsRegion		 = DBUtil::getINStatement($fcityInfo['region'], $bindRegionString, $paramsRegion);
		}

		if ($areaType == 1)
		{
			$row			 = Zones::getStateRegionInfo($areaId);
			$state			 = $row['stateIds'];
			$region			 = $row['region'];
			$paramsZone		 = DBUtil::getINStatement($areaId, $bindZonesString, $paramsZone);
			$paramsState	 = DBUtil::getINStatement($state, $bindStateString, $paramsState);
			$paramsRegion	 = DBUtil::getINStatement($region, $bindRegionString, $paramsRegion);
		}


		if ($areaType == 2)
		{
			$sttModel		 = States::model()->findByPk($areaId);
			$region			 = $sttModel->stt_zone;
			$paramsState	 = DBUtil::getINStatement($areaId, $bindStateString, $paramsState);
			$paramsRegion	 = DBUtil::getINStatement($region, $bindRegionString, $paramsRegion);
		}


		if ($areaType == 4)
		{
			$paramsRegion = DBUtil::getINStatement($areaId, $bindRegionString, $paramsRegion);
		}


		$where = [];

		if ($bindCityString != null)
		{
			$where[] = "(par_area_type = 3 AND par_area_id IN ({$bindCityString}))";
		}
		if ($bindRouteString != null)
		{
			$where[] = "(par_area_type = 5 AND par_area_id IN ({$bindRouteString}))";
		}
		if ($bindStateString != null)
		{
			$where[] = "(par_area_type = 2 AND par_area_id IN ({$bindStateString}))";
		}
		if ($bindRegionString != null)
		{
			$where[] = "(par_area_type = 4 AND par_area_id IN ({$bindRegionString}))";
		}
		if ($bindZonesString != null)
		{
			$where[] = "(par_area_type = 1 AND par_area_id IN ({$bindZonesString}))";
		}

		$whereCondition = implode(" OR ", $where);
		$partnerId = empty($partnerId) ? NULL : $partnerId;


		//type check advance amount 
		if($type == self::ADVANCE_AMOUNT_CHECK)
		{
			$sql = "SELECT partner_area_rule.*,
					IF(partner_area_rule.par_area_type = 1,10,0) + IF(partner_area_rule.par_area_type = 2,5,0) + IF(partner_area_rule.par_area_type = 3,20,0) +IF(partner_area_rule.par_cab_type IS NOT NULL, 1, 0) + IF(partner_area_rule.par_trip_type IS NOT NULL, 1, 0) AS rank
					FROM partner_area_rule 
					WHERE par_active=1 AND ($whereCondition) AND par_partner_id=:partnerId ORDER BY rank DESC";

			$params = $paramsRoute + $paramsZone + $paramsCity + $paramsState + $paramsRegion + ['partnerId' => $partnerId];
			$rows	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params, true, 60 * 60 * 24, CacheDependency::Type_PartnerRule);

			if (!$rows)
			{
				$rows = null;
			}
			Yii::app()->cache->set($key, $rows, 24 * 60 * 60, new CacheDependency('partnerRule'));
		}
		else
		{
			$rows = null;
		}
		
		return $rows;

	}
}
