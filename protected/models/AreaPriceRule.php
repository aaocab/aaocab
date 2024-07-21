<?php

/**
 * This is the model class for table "area_price_rule".
 *
 * The followings are the available columns in table 'area_price_rule':
 * @property integer $apr_id
 * @property integer $apr_area_type
 * @property integer $apr_area_id
 * @property integer $apr_cab_type
 * @property integer $apr_oneway_id
 * @property integer $apr_return_id
 * @property integer $apr_multitrip_id
 * @property integer $apr_airport_id
 * @property string $apr_created_date
 * @property string $apr_modified_date
 * @property integer $apr_active
 * @property integer $apr_dr_4_40
 * @property integer $apr_dr_8_80
 * @property integer $apr_dr_12_120
 */
class AreaPriceRule extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'area_price_rule';
	}
	public $prr_trip_type, $cty_state_id, $sourcezone, $city_id, $areaType;

	public $areatype = array(
		1	 => 'Zone',
		2	 => 'State',
		3	 => 'City',
		4	 => 'Region'
	);
	public $convertType = array(
			3 => '1',
			1 => '2',
			2 => '4'
			);
	public $apr_area_name, $apr_area_id1;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('apr_area_type, apr_cab_type,apr_area_id', 'required'),
			array('apr_area_type, apr_area_id, apr_area_id ,apr_cab_type, apr_oneway_id, apr_return_id, apr_multitrip_id, apr_airport_id, apr_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('apr_id, apr_area_type, apr_area_id , apr_area_id, apr_cab_type, apr_oneway_id, apr_return_id, apr_multitrip_id, apr_airport_id, apr_created_date, apr_modified_date, apr_active,apr_dr_4_40,apr_dr_8_80,apr_dr_12_120', 'safe', 'on' => 'search'),
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
			'apr_id'			 => 'Apr',
			'apr_area_type'		 => 'Area Type',
			'apr_area_id'		 => 'Area',
			'apr_cab_type'		 => 'Cab Type',
			'apr_oneway_id'		 => 'Oneway',
			'apr_return_id'		 => 'Return',
			'apr_multitrip_id'	 => 'Multitrip',
			'apr_airport_id'	 => 'Airport',
			'apr_dr_4_40'		 => 'Day Rental(4hr-40km)',
			'apr_dr_8_80'		 => 'Day Rental(8hr-80km)',
			'apr_dr_10_100'		 => 'Day Rental(10hr-100km)',
			'apr_dr_12_120'		 => 'Day Rental(12hr-120km)',
			'apr_created_date'	 => 'Created Date',
			'apr_modified_date'	 => 'Modified Date',
			'apr_active'		 => 'Active',
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

		$criteria->compare('apr_id', $this->apr_id);
		$criteria->compare('apr_area_type', $this->apr_area_type);
		$criteria->compare('apr_area_id', $this->apr_area_id);
		$criteria->compare('apr_cab_type', $this->apr_cab_type);
		$criteria->compare('apr_oneway_id', $this->apr_oneway_id);
		$criteria->compare('apr_return_id', $this->apr_return_id);
		$criteria->compare('apr_multitrip_id', $this->apr_multitrip_id);
		$criteria->compare('apr_airport_id', $this->apr_airport_id);
		$criteria->compare('apr_created_date', $this->apr_created_date, true);
		$criteria->compare('apr_modified_date', $this->apr_modified_date, true);
		$criteria->compare('apr_active', $this->apr_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AreaPriceRule the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	// need to be look at usage of this funtion
	public function getList($type = '')
	{
		$sql			 = "SELECT apr.*, concat(ifnull(zon_name, ''), ifnull(stt_name, ''), ifnull(cty_name, '')) apr_area_name
		FROM   `area_price_rule` apr
			   LEFT JOIN zones zon ON apr.apr_area_type = 1 AND apr.apr_area_id = zon.zon_id
			   LEFT JOIN states stt ON apr.apr_area_type = 2 AND apr.apr_area_id = stt.stt_id
			   LEFT JOIN cities cty ON apr.apr_area_type = 3 AND apr.apr_area_id = cty.cty_id
		WHERE  apr_active = 1";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc");
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count, 'sort'			 => ['attributes' => [], 'defaultOrder' => 'apr_created_date ASC'],
			'pagination'	 => ['pageSize' => 50],
		]);

		return $dataprovider;
	}

	public function getValues($ctyId, $cabType = 1, $tripType = 1)
	{
		$tripField = "apr.apr_return_id";

		if ($tripType == 1)
		{
			$tripField = "apr.apr_oneway_id";
		}
		if ($tripType == 2)
		{
			$tripField = "apr.apr_return_id";
		}
		if ($tripType == 3 || $tripType == 5 || $tripType == 7)
		{
			$tripField = "apr.apr_multitrip_id";
		}
		if ($tripType == 4)
		{
			$tripField = "apr.apr_airport_id";
		}
		if ($tripType == 9)
		{
			$tripField = "apr.apr_dr_4_40";
		}
		if ($tripType == 10)
		{
			$tripField = "apr.apr_dr_8_80";
		}
		if ($tripType == 11)
		{
			$tripField = "apr.apr_dr_12_120";
		}
		if ($tripType == 15)
		{
			$tripField = "apr.apr_local_transfer_id";
		}

		$limit	 = "LIMIT 1";
		$group	 = 'group by apr.apr_cab_type,tripType';
		$order	 = "ORDER BY areaRank ASC, apr.apr_modified_date DESC $limit";

		$sql = "SELECT distinct apr.apr_cab_type, $tripType tripType, $tripField priceRuleId,
                prr.*,
                apr.apr_area_type,
			CASE
			WHEN apr.apr_area_type = 1 AND apr.apr_area_id = zon_id THEN 4
			WHEN apr.apr_area_type = 2 AND apr.apr_area_id = stt_id THEN 2
			WHEN apr.apr_area_type = 3 AND apr.apr_area_id = cty_id THEN 1
			END AS areaRank
			FROM   area_price_rule apr
				JOIN (SELECT zon.zon_id, cty.cty_id, cty.cty_name, stt.stt_id, zon.zon_name, stt.stt_name
					FROM   cities cty
						JOIN zone_cities zct ON cty.cty_id = zct.zct_cty_id
						JOIN zones zon ON zon.zon_id = zct.zct_zon_id
						JOIN states stt ON stt.stt_id = cty.cty_state_id) a
				ON (apr.apr_area_type = 1 AND apr.apr_area_id = a.zon_id) OR
					(apr.apr_area_type = 2 AND apr.apr_area_id = a.stt_id) OR
					(apr.apr_area_type = 3 AND apr.apr_area_id = a.cty_id)
				JOIN price_rule prr ON $tripField = prr.prr_id
			WHERE   apr_active = 1 AND (prr_trip_type=4 OR prr_zone_rule_id > 0) 
			AND prr_trip_type=$tripType AND
				a.cty_id = $ctyId AND
				$tripField  > 0 AND
				apr.apr_cab_type = $cabType
			$order ";

		$data = DBUtil::queryAll($sql);
		if (empty($data))
		{
			$sql = "SELECT distinct apr.apr_cab_type, $tripType tripType, $tripField priceRuleId,
                prr.*,
                apr.apr_area_type
			FROM   area_price_rule apr
				JOIN price_rule prr ON $tripField = prr.prr_id
				JOIN (SELECT cty.cty_id, cty.cty_name, stt.stt_id, stt.stt_name, stt.stt_zone
					FROM   cities cty
						JOIN states stt ON stt.stt_id = cty.cty_state_id) a
				ON prr_zone_rule_id = a.stt_zone
				WHERE   apr_active = 1 AND
			a.cty_id = $ctyId AND
			$tripField  > 0       AND
			apr.apr_cab_type = $cabType
			";

			$data = DBUtil::queryAll($sql);
		}
//        echo "<pre>";
//        print_r($data);
//        echo "</pre>";
//        exit;
		return $data[0];
	}

	public function getData($ctyId, $cabtype = 0)
	{
		$key		 = "apr_{$ctyId}_{$cabtype}";
		$data		 = Yii::app()->cache->get($key);
		$tripTypes	 = Booking::model()->booking_type;
		if ($data == false)
		{
			$data			 = [];
			$isCityinZone	 = ZoneCities::model()->getZoneByCities([$ctyId]);
			if ($isCityinZone)
			{
				foreach ($tripTypes as $trip => $v)
				{
					$d			 = $this->getValues($ctyId, $cabtype, $trip);
					$data[$trip] = $d;
				}
			}
			else
			{
				foreach ($tripTypes as $trip => $v)
				{
					$d			 = $this->getValues($ctyId, $cabtype, $trip);
					$data[$trip] = $d;
				}
			}
			Yii::app()->cache->set($key, $data, 86400, new CacheDependency("price_city_rules"));
		}
		return $data;
	}

	public static function findRules($ctyId, $cab = 0)
	{
//echo "====".$cab."===="; echo "<br />";
        $cab = empty($cab) ? 0 : $cab;
        $params = ['cityId' => $ctyId, 'cab' => $cab];
		$sql = "SELECT area_price_rule.*,
					CASE area_price_rule.apr_area_type
					WHEN 1 THEN 10
					WHEN 2 THEN 5
					WHEN 3 THEN 20
                    WHEN 4 THEN 30   
					ELSE 1
					END AS rank
				FROM cities 
				JOIN states ON states.stt_id=cities.cty_state_id
				LEFT JOIN zone_cities ON zone_cities.zct_cty_id=cities.cty_id
				INNER JOIN area_price_rule ON ((cities.cty_id=area_price_rule.apr_area_id AND area_price_rule.apr_area_type=3) 
										   OR (zone_cities.zct_zon_id=area_price_rule.apr_area_id AND area_price_rule.apr_area_type=1)                           
										   OR (states.stt_id=area_price_rule.apr_area_id AND area_price_rule.apr_area_type=2)
										   OR (states.stt_zone=area_price_rule.apr_area_id AND area_price_rule.apr_area_type=4)) 
				WHERE apr_active=1 AND cty_id=:cityId AND apr_cab_type=:cab ORDER BY rank DESC";

		$rows	 = DBUtil::query($sql, DBUtil::SDB(), $params , true, 60 * 60 * 24, CacheDependency::Type_PriceRule);
		$result	 = [];
		foreach ($rows as $row)
		{
			if ($row['apr_oneway_id'] != null && $row['apr_oneway_id'] > 0 && !isset($result[1]))
			{
				$result[1]['id']	 = $row['apr_oneway_id'];
				$result[1]['rank']	 = $row['rank'];
			}

			if ($row['apr_return_id'] != null && $row['apr_return_id'] > 0 && !isset($result[2]))
			{
				$result[2]['id']	 = $row['apr_return_id'];
				$result[2]['rank']	 = $row['rank'];
			}

			if ($row['apr_multitrip_id'] != null && $row['apr_multitrip_id'] > 0 && !isset($result[3]))
			{
				$result[3]['id']	 = $row['apr_multitrip_id'];
				$result[3]['rank']	 = $row['rank'];
			}

			if ($row['apr_airport_id'] != null && $row['apr_airport_id'] > 0 && !isset($result[4]))
			{
				$result[4]['id']	 = $row['apr_airport_id'];
				$result[4]['rank']	 = $row['rank'];
			}
			if ($row['apr_multitrip_id'] != null && $row['apr_multitrip_id'] > 0 && !isset($result[5]))
			{
				$result[5]['id']	 = $row['apr_multitrip_id'];
				$result[5]['rank']	 = $row['rank'];
			}
			if ($row['apr_oneway_id'] != null && $row['apr_oneway_id'] > 0 && !isset($result[6]))
			{
				$result[6]['id']	 = $row['apr_oneway_id'];
				$result[6]['rank']	 = $row['rank'];
			}
			if ($row['apr_dr_4_40'] != null && $row['apr_dr_4_40'] > 0 && !isset($result[9]))
			{
				$result[9]['id']	 = $row['apr_dr_4_40'];
				$result[9]['rank']	 = $row['rank'];
			}
			if ($row['apr_dr_8_80'] != null && $row['apr_dr_8_80'] > 0 && !isset($result[10]))
			{
				$result[10]['id']	 = $row['apr_dr_8_80'];
				$result[10]['rank']	 = $row['rank'];
			}
			if ($row['apr_dr_12_120'] != null && $row['apr_dr_12_120'] > 0 && !isset($result[11]))
			{
				$result[11]['id']	 = $row['apr_dr_12_120'];
				$result[11]['rank']	 = $row['rank'];
			}
			if ($row['apr_intra_id'] != null && $row['apr_intra_id'] > 0 && !isset($result[14]))
			{
				$result[14]['id']	 = $row['apr_intra_id'];
				$result[14]['rank']	 = $row['rank'];
			}
		}

		return $result;
	}

	public static function findRulesByAreaType($areaType, $areaId, $cab = 0)
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
			$where[] = "(apr_area_type = 3 AND apr_area_id IN ({$bindCityString}))";
		}
		if ($bindRouteString != null)
		{
			$where[] = "(apr_area_type = 5 AND apr_area_id IN ({$bindRouteString}))";
		}
		if ($bindStateString != null)
		{
			$where[] = "(apr_area_type = 2 AND apr_area_id IN ({$bindStateString}))";
		}
		if ($bindRegionString != null)
		{
			$where[] = "(apr_area_type = 4 AND apr_area_id IN ({$bindRegionString}))";
		}
		if ($bindZonesString != null)
		{
			$where[] = "(apr_area_type = 1 AND apr_area_id IN ({$bindZonesString}))";
		}

		$whereCondition = implode(" OR ", $where);

		$cab = empty($cab) ? 0 : $cab;
		$sql = "SELECT area_price_rule.*,
					CASE area_price_rule.apr_area_type
					WHEN 1 THEN 10
					WHEN 2 THEN 5
					WHEN 3 THEN 20   
					ELSE 1
					END AS rank
				FROM area_price_rule 
				WHERE apr_active=1 AND ($whereCondition) AND apr_cab_type=:cab ORDER BY rank DESC";

		$params = $paramsRoute + $paramsZone + $paramsCity + $paramsState + $paramsRegion + ['cab' => $cab];
		
		#Logger::info('params == ' . json_encode($params));
		#Logger::info('SQL == ' . $sql);
//CacheDependency::Type_PriceRule
		$rows	 = DBUtil::query($sql, DBUtil::SDB(), $params, true, 60 * 60 * 24,null );
		
		#Logger::info('SQL == ' . json_encode($rows));
		$result	 = [];
		foreach ($rows as $row)
		{
			if ($row['apr_oneway_id'] != null && $row['apr_oneway_id'] > 0 && !isset($result[1]))
			{
				$result[1]['id']	 = $row['apr_oneway_id'];
				$result[1]['rank']	 = $row['rank'];
			}

			if ($row['apr_return_id'] != null && $row['apr_return_id'] > 0 && !isset($result[2]))
			{
				$result[2]['id']	 = $row['apr_return_id'];
				$result[2]['rank']	 = $row['rank'];
			}

			if ($row['apr_multitrip_id'] != null && $row['apr_multitrip_id'] > 0 && !isset($result[3]))
			{
				$result[3]['id']	 = $row['apr_multitrip_id'];
				$result[3]['rank']	 = $row['rank'];
			}

			if ($row['apr_airport_id'] != null && $row['apr_airport_id'] > 0 && !isset($result[4]))
			{
				$result[4]['id']	 = $row['apr_airport_id'];
				$result[4]['rank']	 = $row['rank'];
			}
			if ($row['apr_multitrip_id'] != null && $row['apr_multitrip_id'] > 0 && !isset($result[5]))
			{
				$result[5]['id']	 = $row['apr_multitrip_id'];
				$result[5]['rank']	 = $row['rank'];
			}
			if ($row['apr_oneway_id'] != null && $row['apr_oneway_id'] > 0 && !isset($result[6]))
			{
				$result[6]['id']	 = $row['apr_oneway_id'];
				$result[6]['rank']	 = $row['rank'];
			}
			if ($row['apr_dr_4_40'] != null && $row['apr_dr_4_40'] > 0 && !isset($result[9]))
			{
				$result[9]['id']	 = $row['apr_dr_4_40'];
				$result[9]['rank']	 = $row['rank'];
			}
			if ($row['apr_dr_8_80'] != null && $row['apr_dr_8_80'] > 0 && !isset($result[10]))
			{
				$result[10]['id']	 = $row['apr_dr_8_80'];
				$result[10]['rank']	 = $row['rank'];
			}
			if ($row['apr_dr_10_100'] != null && $row['apr_dr_10_100'] > 0 && !isset($result[16]))
			{
				$result[16]['id']	 = $row['apr_dr_10_100'];
				$result[16]['rank']	 = $row['rank'];
			}
			if ($row['apr_dr_12_120'] != null && $row['apr_dr_12_120'] > 0 && !isset($result[11]))
			{
				$result[11]['id']	 = $row['apr_dr_12_120'];
				$result[11]['rank']	 = $row['rank'];
			}
	        if ($row['apr_intra_id'] != null && $row['apr_intra_id'] > 0 && !isset($result[14]))
			{
				$result[14]['id']	 = $row['apr_intra_id'];
				$result[14]['rank']	 = $row['rank'];
			}
			if ($row['apr_local_transfer_id'] != null && $row['apr_local_transfer_id'] > 0 && !isset($result[15]))
			{
				$result[15]['id']	 = $row['apr_local_transfer_id'];
				$result[15]['rank']	 = $row['rank'];
			}
		}
		return $result;
	}
	

	public function addAreaPriceRule($tripType, $areaId, $prrId, $areaType = 0, $apr_area_id = 0, $cabType = 0)
	{


		$areaPriceRule = AreaPriceRule::model()->findByPk($areaId);

		if ($areaId == 0 || $areaId == NULL)
		{
			$apr_id			 = self::getApr($apr_area_id, $areaType, $cabType);
			$areaPriceRule	 = AreaPriceRule::model()->findByPk($apr_id);
		}

		if ($areaPriceRule == '')
		{
			$areaPriceRule					 = new AreaPriceRule();
			$areaPriceRule->apr_cab_type	 = $cabType;
			$areaPriceRule->apr_area_type	 = $areaType;
			$areaPriceRule->apr_area_id		 = $apr_area_id;
		}
		if ($tripType == 1)
		{
			$areaPriceRule->apr_oneway_id = $prrId;
		}
		if ($tripType == 2)
		{
			$areaPriceRule->apr_return_id = $prrId;
		}
		if ($tripType == 3)
		{
			$areaPriceRule->apr_multitrip_id = $prrId;
		}
		if ($tripType == 4)
		{
			$areaPriceRule->apr_airport_id = $prrId;
		}
		if ($tripType == 9)
		{
			$areaPriceRule->apr_dr_4_40 = $prrId;
		}
		if ($tripType == 10)
		{
			$areaPriceRule->apr_dr_8_80 = $prrId;
		}
		if ($tripType == 16)
		{
			$areaPriceRule->apr_dr_10_100 = $prrId;
		}
		if ($tripType == 11)
		{
			$areaPriceRule->apr_dr_12_120 = $prrId;
		}
		if ($tripType == 15)
		{
			$areaPriceRule->apr_local_transfer_id = $prrId;
		}
$areaPriceRule->errors;
		return $areaPriceRule->save();
	}

	public function checkDuplicate($areaId, $areaType, $cabType)
	{
		$sql = "SELECT COUNT(1)
                FROM   area_price_rule
                WHERE  apr_cab_type   = $cabType
                AND    apr_area_type  = $areaType
                AND    apr_area_id    = $areaId
                AND    apr_active     = 1 ";

		$record = DBUtil::queryScalar($sql);

		return $record;
	}

	public static function getFieldNamebyTripType($tripType = [])
	{
		$tripFieldArr = [
			1	 => "apr_oneway_id",
			2	 => "apr_return_id",
			3	 => "apr_multitrip_id",
			5	 => "apr_multitrip_id",
			7	 => "apr_multitrip_id",
			4	 => "apr_airport_id",
			9	 => "apr_dr_4_40",
			10	 => "apr_dr_8_80",
			16   => "apr_dr_10_100",
			11	 => "apr_dr_12_120",
			15   => "apr_local_transfer_id"];
		if (sizeof($tripType) == 0)
		{
			return $tripFieldArr;
		}
		$resArr = [];
		foreach ($tripType as $k)
		{
			$resArr[$k] = $tripFieldArr[$k];
		}
		return $resArr;
	}

	public static function getApr($areaId, $areaType, $cabType)
	{
		$params	 = ['apr_area_id' => $areaId, 'apr_area_type' => $areaType, 'apr_cab_type' => $cabType];
		$sql	 = "SELECT apr_id
                FROM   area_price_rule
                WHERE  apr_cab_type   =:apr_cab_type
                AND    apr_area_type  =:apr_area_type
                AND    apr_area_id   =:apr_area_id
                AND    apr_active     = 1 ";
		$apr_id	 = DBUtil::queryScalar($sql, null, $params);
		return $apr_id;
	}

	/**
	 * 
	 * @param type $areaType
	 * @param type $areaId
	 * @param type $cab
	 * @return type
	 */
	public static function getDataByArea($areaType, $areaId, $cab, $tripType = null)
	{
		$condition = "";
		if ($tripType != null)
		{
			$conditions	 = [];
			$fields		 = AreaPriceRule::getFieldNamebyTripType([$tripType]);
			foreach ($fields as $key => $value)
			{
				$conditions[] = "$value>0";
			}

			$condition = " AND " . implode(" AND ", $conditions);
		}
		$sql	 = "SELECT apr_id, apr_oneway_id, apr_return_id, apr_multitrip_id, 
						apr_airport_id, apr_dr_4_40, apr_dr_8_80, apr_dr_10_100, apr_dr_12_120 
					FROM `area_price_rule` 
					WHERE apr_area_type=$areaType AND apr_area_id IN($areaId) AND apr_cab_type=$cab $condition";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB());
		return $data;
	}

	public function getDayRentalPrice($isExport = '')
	{
		$params	 = [];
		$prrTripType = $areaType = $cabType = $ctyStateId = $ctyStateId = $cityId ='';
		if ($this->prr_trip_type != '')
		{
			$prrTripType = " AND prr_trip_type = " .$this->prr_trip_type;
		}
		if($this->apr_cab_type != '')
		{
			$cabType = " AND apr_cab_type = " . $this->apr_cab_type;
		}
		if($this->areaType != '')
		{
			$areaType = " AND apr_area_type = " .$this->areaType;
		}
		if($this->cty_state_id != '')
		{
			$ctyStateId = " AND apr_area_type = 2 AND apr_area_id = " . $this->cty_state_id;
		}
		if($this->sourcezone != '')
		{
			$zoneId = " AND apr_area_type = 1 AND apr_area_id = " . $this->sourcezone;
		}
		if($this->city_id != '')
		{
			$cityId = " AND apr_area_type = 3 AND apr_area_id = " . $this->city_id;
		}

		$cond = $prrTripType . $cabType . $areaType . $ctyStateId . $zoneId . $cityId;

		$sql1	 = "SELECT apr_id, apr_area_type, apr_area_id, apr_cab_type, prr_id, prr_cab_type, prr_trip_type, prr_rate_per_km, prr_rate_per_minute, prr_rate_per_km_extra, prr_rate_per_minute_extra, prr_min_km, prr_min_duration,
					prr_min_base_amount, prr_min_km_day, prr_max_km_day, prr_day_driver_allowance, prr_night_driver_allowance,
					prr_driver_allowance_km_limit, prr_min_pickup_duration, prr_night_start_time, prr_night_end_time, apr_created_date
                FROM   area_price_rule apr
				LEFT JOIN price_rule prr ON prr.prr_id = apr.apr_dr_4_40
                WHERE apr_active = 1 AND apr_dr_4_40 != 0 $cond";

		$sql2	 = "SELECT apr_id, apr_area_type, apr_area_id, apr_cab_type, prr_id, prr_cab_type, prr_trip_type, prr_rate_per_km, prr_rate_per_minute, prr_rate_per_km_extra, prr_rate_per_minute_extra, prr_min_km, prr_min_duration,
					prr_min_base_amount, prr_min_km_day, prr_max_km_day, prr_day_driver_allowance, prr_night_driver_allowance,
					prr_driver_allowance_km_limit, prr_min_pickup_duration, prr_night_start_time, prr_night_end_time, apr_created_date
                FROM   area_price_rule apr
				LEFT JOIN price_rule prr ON prr.prr_id = apr.apr_dr_8_80
                WHERE apr_active = 1 AND apr_dr_8_80 != 0 $cond";
		
		$sql3	 = "SELECT apr_id, apr_area_type, apr_area_id, apr_cab_type, prr_id, prr_cab_type, prr_trip_type, prr_rate_per_km, prr_rate_per_minute, prr_rate_per_km_extra, prr_rate_per_minute_extra, prr_min_km, prr_min_duration,
					prr_min_base_amount, prr_min_km_day, prr_max_km_day, prr_day_driver_allowance, prr_night_driver_allowance,
					prr_driver_allowance_km_limit, prr_min_pickup_duration, prr_night_start_time, prr_night_end_time, apr_created_date
                FROM   area_price_rule apr
				LEFT JOIN price_rule prr ON prr.prr_id = apr.apr_dr_12_120
                WHERE apr_active = 1 AND apr_dr_12_120 != 0 $cond";

		$sql = $sql1 ." UNION ". $sql2 . " UNION " . $sql3;

		if($isExport)
		{
			$order = ' ORDER BY apr_id DESC';
			$sql = $sql. $order;
			$result	 = DBUtil::query($sql, DBUtil::SDB());
		}else{
			$count	 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
			$result	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['apr_id', 'prr_cab_type', 'prr_trip_type'],
					'defaultOrder'	 => 'apr_id  DESC'], 'pagination'	 => ['pageSize' => 100],
			]);
		}
		return $result;
	}

	/**
	 * 
	 * @param type $id
	 * @param type $type
	 * @return type
	 */
	public static function getNameByData($id, $type)
	{
		switch ($type)
		{
			case '1':
				$value = Zones::getNameByCityId($id);
				break;
			case '2':
				$value = States::getSatetNameById($id);
				break;
			case '3':
				$value = Cities::getCtyNameById($id);
				break;
			case '4':
				$value = States::findRegionName($id);
			default:
				break;
		}
		return $value;
	}
}
