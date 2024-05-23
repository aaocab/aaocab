<?php

/**
 * This is the model class for table "service_class_rule".
 *
 * The followings are the available columns in table 'service_class_rule':
 * @property integer $scr_id
 * @property integer $scr_area_type
 * @property integer $scr_area_id
 * @property integer $scr_trip_type
 * @property integer $scr_markup_type
 * @property integer $scr_markup
 * @property integer $scr_is_allowed
 * @property string $scr_create_date
 * @property string $scr_update_date
 * @property string $scr_log
 */
class ServiceClassRule extends CActiveRecord
{

	public $scr_zone_id, $scr_state_id, $scr_city_id, $scr_region_id;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'service_class_rule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('scr_area_type, scr_area_id', 'required'),
			array('scr_area_type, scr_area_id, scr_trip_type, scr_markup_type, scr_markup, scr_is_allowed', 'numerical', 'integerOnly' => true),
			array('scr_update_date', 'safe'),
			array('scr_log', 'length', 'max' => 4000),
			//array('scr_scv_id,scr_area_type,scr_area_id', 'unique', 'message' => 'Area and cab already exists.', 'on' => 'create'),
			['scr_area_type', 'validateRules', 'on' => 'create'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('scr_id, scr_area_type, scr_area_id, scr_scv_id, scr_vht_id, scr_trip_type, scr_markup_type, scr_markup, scr_is_allowed, scr_create_date, scr_update_date, scr_log', 'safe'),
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
			'scr_id'			 => 'Scr',
			'scr_area_type'		 => 'Scr Area Type',
			'scr_area_id'		 => 'Scr Area',
			'scr_trip_type'		 => 'Scr Trip Type',
			'scr_markup_type'	 => 'Scr Markup Type',
			'scr_markup'		 => 'Scr Markup',
			'scr_is_allowed'	 => 'Scr Is Allowed',
			'scr_create_date'	 => 'Scr Create Date',
			'scr_update_date'	 => 'Scr Update Date',
			'scr_log'			 => 'Log',
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

		$criteria->compare('scr_id', $this->scr_id);
		$criteria->compare('scr_area_type', $this->scr_area_type);
		$criteria->compare('scr_area_id', $this->scr_area_id);
		$criteria->compare('scr_trip_type', $this->scr_trip_type);
		$criteria->compare('scr_markup_type', $this->scr_markup_type);
		$criteria->compare('scr_markup', $this->scr_markup);
		$criteria->compare('scr_is_allowed', $this->scr_is_allowed);
		$criteria->compare('scr_create_date', $this->scr_create_date, true);
		$criteria->compare('scr_update_date', $this->scr_update_date, true);
		$criteria->compare('scr_log', $this->scr_log, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ServiceClassRule the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function filterCabsByRule($fromcity, $tocity, $tripType = NULL, $svcIds = NULL)
	{
		$key	 = md5("filterCabsByRule_{$fromcity}_{$tocity}_{$tripType}_" . json_encode($svcIds) . "");
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			return $data;
		}
		
		$svcIds = array_filter($svcIds);
		$arrSvcIds	 = (count($svcIds) > 0) ? implode(",", $svcIds) : -1;
		$fcityInfo	 = Cities::getStateZoneInfoByCity($fromcity);
		$tcityInfo	 = Cities::getStateZoneInfoByCity($tocity);
		$routeId	 = Route::getIdByCities($fromcity, $tocity);

		$fcityInfo['zones']	 = ($fcityInfo['zones'] == '') ? 0 : $fcityInfo['zones'];
		$fcityInfo['stt_id'] = ($fcityInfo['stt_id'] == '') ? 0 : $fcityInfo['stt_id'];
		$fcityInfo['region'] = ($fcityInfo['region'] == '') ? 0 : $fcityInfo['region'];

		$tcityInfo['zones']	 = 0; //($tcityInfo['zones']=='')?0:$tcityInfo['zones'];
		$tcityInfo['stt_id'] = ($tcityInfo['stt_id'] == '') ? 0 : $tcityInfo['stt_id'];
		$tcityInfo['region'] = ($tcityInfo['region'] == '') ? 0 : $tcityInfo['region'];
		if (!$fcityInfo)
		{
			$data = $arrSvcIds;
			goto skipServiceClassRule;
		}

		$allZones = array_filter(explode(',', $fcityInfo['zones'] . ',' . $tcityInfo['zones']));
		if (count($allZones) == 0)
		{
			$allZones = [-1];
		}
		$paramsZone		 = DBUtil::getINStatement($allZones, $bindZonesString, $paramsZone);
		$paramsCity		 = DBUtil::getINStatement([$fromcity, $tocity], $bindCityString, $paramsCity);
		$paramsState	 = DBUtil::getINStatement([$fcityInfo['stt_id'], $tcityInfo['stt_id']], $bindStateString, $paramsState);
		$paramsRegion	 = DBUtil::getINStatement([$fcityInfo['region'], $tcityInfo['region']], $bindRegionString, $paramsRegion);
		$zoneCondition	 = "(scr_area_type = 1 AND scr_area_id IN ({$bindZonesString})";
		$routeCondition	 = "";
		if ($routeId > 0)
		{
			$routeCondition = "OR (scr_area_type = 5 AND scr_area_id IN ({$routeId}))";
		}
		$sqlServiceRule = "SELECT scv_id, CASE
				WHEN scr_area_type=1 AND scr_area_id IN ({$fcityInfo['zones']}) THEN 10
				WHEN scr_area_type=1 AND scr_area_id IN ({$tcityInfo['zones']}) THEN 9
				WHEN scr_area_type=2 AND scr_area_id IN ({$fcityInfo['stt_id']}) THEN 5
				WHEN scr_area_type=2 AND scr_area_id IN ({$tcityInfo['stt_id']}) THEN 4
				WHEN scr_area_type=3 AND scr_area_id IN ({$fromcity}) THEN 20
				WHEN scr_area_type=3 AND scr_area_id IN ({$tocity}) THEN 19
				WHEN scr_area_type=4 AND scr_area_id IN ({$fcityInfo['region']}) THEN 3
				WHEN scr_area_type=4 AND scr_area_id IN ({$tcityInfo['region']}) THEN 2
				WHEN scr_area_type=5 THEN 25
				ELSE 1
				END AS rank, 
			scc_id, scr_is_allowed 
		FROM service_class_rule 
		INNER JOIN svc_class_vhc_cat ON scv_id = scr_scv_id AND scv_active = 1
		INNER JOIN service_class ON scv_scc_id = scc_id
		WHERE   ($zoneCondition) 
			OR (scr_area_type = 2 AND scr_area_id IN ($bindStateString)) 
			OR (scr_area_type = 3 AND scr_area_id IN ($bindCityString)) 
			OR (scr_area_type = 4 AND scr_area_id IN ($bindRegionString))
			$routeCondition
			OR scr_area_type IS NULL)
			AND (scr_trip_type = :tripType OR scr_trip_type IS NULL) 
			AND scr_active = 1 AND scv_id IN ({$arrSvcIds})
		ORDER BY rank DESC
		";

		$params = $paramsZone + $paramsCity + $paramsState + $paramsRegion + ['tripType' => $tripType];

		$rows = DBUtil::query($sqlServiceRule, DBUtil::SDB(), $params);

		$arrIncluded = [];
		$arrExcluded = [];
		foreach ($rows as $row)
		{
			if ($row['scr_is_allowed'] == 1 && !in_array($row['scv_id'], $arrExcluded))
			{
				$arrIncluded[] = $row["scv_id"];
			}
			if ($row['scr_is_allowed'] == 0 && !in_array($row['scv_id'], $arrIncluded))
			{
				$arrExcluded[] = $row["scv_id"];
			}
		}
		$data = array_unique($arrIncluded);

		skipServiceClassRule:
		if (count($data) > 0)
		{

			Yii::app()->cache->set($key, $data, 60 * 60 * 24, new CacheDependency('cabTypes'));
		}
		return $data;
	}

	public function getCabByClassId($sccId, $baseAmount, $vctId)
	{
		$sql = "SELECT scr.scr_id, CONCAT(vht.vht_make,' ', vht.vht_model) as cab, scr_markup_type,
					scr_markup, vht.vht_id
                FROM `service_class_rule` scr
                INNER JOIN svc_class_vhc_cat svc ON svc.scv_id=scr.scr_scv_id AND scv_active = 1 AND scv_model > 0 
                LEFT JOIN vcv_cat_vhc_type vcvt ON vcvt.vcv_vht_id =  svc.scv_model
                INNER JOIN vehicle_types vht ON vht.vht_id = vcvt.vcv_vht_id
                WHERE svc.scv_scc_id = $sccId AND svc.scv_vct_id = $vctId AND scr_active =1 GROUP BY vht_id";
		$cab = DBUtil::query($sql);
		if (count($cab) == 0)
		{
			return false;
		}

		foreach ($cab as $val)
		{
			$calAmountStr	 = $val['cab'];
			$arrJSON[]		 = array("id" => $val['vht_id'], "text" => $calAmountStr);
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public static function getByCityVhcModel($fromcity, $tocity, $scvId = 0)
	{
		$routeId	 = Route::getIdByCities($fromcity, $tocity);
		$areaType	 = 3;
		$areaId		 = $fromcity;
		if ($routeId > 0)
		{
			$areaType	 = 5;
			$areaId		 = $routeId;
		}

		$data = self::getByAreaModel($areaType, $areaId, $scvId);
		return $data;
	}

	public static function getByAreaModel($areaType, $areaId, $svcId = 0)
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
			$cityId				 = ($areaType == 3) ? $areaId : $cityId;
			$fcityInfo			 = Cities::getStateZoneInfoByCity($cityId);
			$fcityInfo['zones']	 = ($fcityInfo['zones'] == '') ? 0 : $fcityInfo['zones'];
			$fcityInfo['stt_id'] = ($fcityInfo['stt_id'] == '') ? 0 : $fcityInfo['stt_id'];
			$fcityInfo['region'] = ($fcityInfo['region'] == '') ? 0 : $fcityInfo['region'];
			$paramsZone			 = DBUtil::getINStatement($fcityInfo['zones'], $bindZonesString, $paramsZone);
			$paramsCity			 = DBUtil::getINStatement($areaId, $bindCityString, $paramsCity);
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
			$where[] = "(scr_area_type = 3 AND scr_area_id IN ({$bindCityString}))";
		}
		if ($bindRouteString != null)
		{
			$where[] = "(scr_area_type = 5 AND scr_area_id IN ({$bindRouteString}))";
		}
		if ($bindStateString != null)
		{
			$where[] = "(scr_area_type = 2 AND scr_area_id IN ({$bindStateString}))";
		}
		if ($bindRegionString != null)
		{
			$where[] = "(scr_area_type = 4 AND scr_area_id IN ({$bindRegionString}))";
		}
		if ($bindZonesString != null)
		{
			$where[] = "(scr_area_type = 1 AND scr_area_id IN ({$bindZonesString}))";
		}

		$whereCondition = implode(" OR ", $where);

		$sql = "SELECT service_class_rule.*,
					CASE 
						WHEN scr_area_type=1 THEN 10
						WHEN scr_area_type=2 THEN 5
						WHEN scr_area_type=3 THEN 20
						WHEN scr_area_type=4 THEN 3
						WHEN scr_area_type=5 THEN 25
						ELSE 1
					END AS rank FROM service_class_rule
				INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = scr_scv_id
				WHERE ($whereCondition OR scr_area_type IS NULL) 
					AND scv.scv_id=:scv_id AND scr_active=1
				ORDER BY rank DESC
						";

		$params	 = $paramsRoute + $paramsZone + $paramsCity + $paramsState + $paramsRegion + ['scv_id' => $svcId];
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params, 60 * 60 * 6, "serviceClassRule");
		return $data;
	}

	/**
	 * This function is used add markup on base fare according to service_class_rule table
	 * @param integer $fromCity from city
	 * @param integer $scvId service class and category relation id
	 * @param double $baseAmount Base Amount of the booking
	 */
	public static function getRateWithMarkUp($fromCity, $toCity, $scvId, $baseAmount)
	{
		$result	 = self::getByCityVhcModel($fromCity, $toCity, $scvId);
		$amt	 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $baseAmount);
		return $amt;
	}

	public static function addMarkup($type, $value, $amount, $roundoff = 0)
	{
		if ($type == 1)
		{
			$amount = $amount * (100 + $value) / 100;
		}
		else
		{
			$amount = $amount + $value;
		}

		if ($roundoff > 0)
		{
			$amount = (round($amount / $roundoff) * $roundoff);
		}
		else
		{
			$amount = round($amount);
		}

		return $amount;
	}

	/**
	 * This function is used to add markup according to service_class_rule on routes which has no rate table entry
	 * @param integer $cityId from city id 
	 * @param integer $svcId service class and category relation id
	 * @param PriceRule $priceRule
	 * @return PriceRule
	 */
	public static function getPriceRuleWithMarkUp($fromCity, $toCity, $svcId, $priceRule)
	{
		$result							 = self::getByCityVhcModel($fromCity, $toCity, $svcId);
		$priceRule->basePriceRule		 = clone $priceRule;
		$priceRule->prr_min_base_amount	 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $priceRule->prr_min_base_amount);
		if ($result['scr_markup_type'] == 1)
		{
			$priceRule->prr_rate_per_km			 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $priceRule->prr_rate_per_km, 0.25);
			$priceRule->prr_rate_per_km_extra	 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $priceRule->prr_rate_per_km_extra, 0.25);
			if ($result['scr_markup'] > 0)
			{
				$priceRule->prr_day_driver_allowance	 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $priceRule->prr_day_driver_allowance);
				$priceRule->prr_night_driver_allowance	 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $priceRule->prr_night_driver_allowance);
			}
			$priceRule->prr_rate_per_minute			 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $priceRule->prr_rate_per_minute, 0.25);
			$priceRule->prr_rate_per_minute_extra	 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $priceRule->prr_rate_per_minute_extra, 0.25);
		}
		return $priceRule;
	}

	/**
	 * This function is used to add markup according to service_class_rule on routes which has no rate table entry
	 * @param integer $cityId from city id 
	 * @param integer $svcId service class and category relation id
	 * @param PriceRule $newPriceRule
	 * @return PriceRule
	 */
	public static function getAreaPriceRuleWithMarkUp($areaType, $areaId, $svcId, $priceRule)
	{
		$newPriceRule						 = clone $priceRule;
		$result								 = self::getByAreaModel($areaType, $areaId, $svcId);
		$newPriceRule->basePriceRule		 = $priceRule;
		$newPriceRule->prr_min_base_amount	 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $newPriceRule->prr_min_base_amount);
		if ($result['scr_markup_type'] == 1)
		{
			$newPriceRule->prr_rate_per_km		 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $newPriceRule->prr_rate_per_km, 0.25);
			$newPriceRule->prr_rate_per_km_extra = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $newPriceRule->prr_rate_per_km_extra, 0.25);
			if ($result['scr_markup'] > 0)
			{
				$newPriceRule->prr_day_driver_allowance		 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $newPriceRule->prr_day_driver_allowance);
				$newPriceRule->prr_night_driver_allowance	 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $newPriceRule->prr_night_driver_allowance);
			}
			$newPriceRule->prr_rate_per_minute		 = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $newPriceRule->prr_rate_per_minute, 0.25);
			$newPriceRule->prr_rate_per_minute_extra = self::addMarkup($result['scr_markup_type'], $result['scr_markup'], $newPriceRule->prr_rate_per_minute_extra, 0.25);
		}
		return $newPriceRule;
	}

	public function getList()
	{
		$sql2 = '';
		if ($this->scr_zone_id != '')
		{
			$sql2 .= " (scr_area_id = " . $this->scr_zone_id . " AND scr_area_type = " . 1 . ")";
		}
		if ($this->scr_state_id != '')
		{
			if ($sql2 != '')
			{
				$sql2 .= " OR ";
			}
			$sql2 .= "(scr_area_id = " . $this->scr_state_id . " AND scr_area_type = " . 2 . ")";
		}
		if ($this->scr_city_id != '')
		{
			if ($sql2 != '')
			{
				$sql2 .= " OR ";
			}
			$sql2 .= " (scr_area_id = " . $this->scr_city_id . " AND scr_area_type = " . 3 . ")";
		}
		if ($this->scr_region_id != '')
		{
			if ($sql2 != '')
			{
				$sql2 .= " OR ";
			}
			$sql2 .= " (scr_area_id = " . $this->scr_region_id . " AND scr_area_type = " . 4 . ")";
		}
		if ($sql2 != '')
		{
			$sql2 = " AND ($sql2)";
		}
		$sql = "SELECT service_class_rule.*,CASE service_class_rule.scr_area_type
					WHEN 1 THEN 'Zones'
					WHEN 2 THEN 'State'
					WHEN 3 THEN 'City'  
					WHEN 4 THEN 'Region'   
					ELSE 1
					END AS areaType
			FROM     service_class_rule
				WHERE 1 $sql2";

		if ($this->scr_scv_id != '')
		{
			$sql .= " AND scr_scv_id = " . $this->scr_scv_id;
		}
		if ($this->scr_trip_type != '')
		{
			$sql .= " AND scr_trip_type = " . $this->scr_trip_type;
		}
		if ($this->scr_is_allowed != '')
		{
			$sql .= " AND scr_is_allowed = " . $this->scr_is_allowed;
		}

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['scr_area_type', 'scr_area_id', 'scr_trip_type', 'scr_scv_id', 'scr_is_allowed', 'scr_create_date'],
				'defaultOrder'	 => 'scr_id DESC'
			],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getJSON($arr = [])
	{
		$arrJSON = array();
		foreach ($arr as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function validateRules()
	{
		$params	 = ['areaType' => $this->scr_area_type, 'areaId' => $this->scr_area_id, 'scvId' => $this->scr_scv_id, 'tripType' => $this->scr_trip_type];
		$sql	 = "SELECT COUNT(*) as cnt FROM service_class_rule WHERE scr_area_type=:areaType AND scr_area_id=:areaId AND scr_scv_id=:scvId AND scr_trip_type=:tripType";
		$cnt	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		if ($cnt > 0)
		{
			$this->addError('scr_area_type', "area,cab,trip type must be unique together.");
			return false;
		}
		return true;
	}

	public static function getLogJson($oldLogData = "", $oldData, $newData)
	{
		if (!empty($oldData))
		{
			$newLogData = array
				(
				0	 => Yii::app()->user->getId(),
				1	 => date("Y-m-d H:i:s"),
				2	 => 'Markup Type:' . $oldData['scr_markup_type'] . ' -> ' . $newData['scr_markup_type'],
				3	 => 'Markup Amount:' . $oldData['scr_markup'] . ' -> ' . $newData['scr_markup'],
				4	 => 'Supported:' . $oldData['scr_is_allowed'] . ' -> ' . $newData['scr_is_allowed']
			);
			if ($oldLogData != "" && $oldLogData != "null")
			{
				$decodedRemark = json_decode($oldLogData);
				array_push($decodedRemark, $newLogData);
			}
			else
			{
				$decodedRemark = [0 => $newLogData];
			}
			if (count($decodedRemark) > 5)
			{
				$arrMax = array_splice($decodedRemark, -5, 5);
				return json_encode($arrMax);
			}
			return json_encode($decodedRemark);
		}

		return false;
	}

}
