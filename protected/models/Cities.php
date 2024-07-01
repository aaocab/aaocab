<?php

/**
 * This is the model class for table "cities".
 *
 * The followings are the available columns in table 'cities':
 * @property integer $cty_id
 * @property integer $id
 * @property string $cty_name
 * @property string $cty_alias_path
 * @property string $cty_alias_name
 * @property string $cty_keyword_names
 * @property integer $cty_population
 * @property integer $cty_state_id
 * @property string $cty_county
 * @property integer $city_group
 * @property string $cty_city_desc
 * @property string $cty_short_desc
 * @property string $cty_pickup_drop_info
 * @property string $cty_ncr
 * @property float $cty_lat
 * @property float $cty_long
 * @property float $cty_garage_address
 * @property integer $cty_radius
 * @property integer $cty_is_airport
 * @property integer $cty_has_airport
 * @property integer $cty_is_poi
 * @property integer $cty_poi_type
 * @property integer $cty_active
 * @property integer $cty_service_active
 * @property string $cty_code
 * @property integer $cty_is_approved
 * @property string $cty_excluded_cabtypes
 * @property string $cty_log
 * @property string $cty_place_id
 * @property string $cty_bounds
 * @property string $cty_types

 * The followings are the available model relations:
 * @property Booking[] $bookings
 * @property Booking[] $bookings1
 * @property States $ctyState
 * @property Route[] $routes
 * @property Route[] $routes1
 * @property UserPlaces[] $userPlaces
 */
class Cities extends CActiveRecord
{

	public $cty_temp_name, $cty_zones, $cty_state_name;
	public $is_luxury_city, $is_partial, $distance, $form_cty_id, $to_cty_id, $isRedirectedBooking;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cities';
	}

	public function defaultScope()
	{
		$ta	 = $this->getTableAlias(false, false);
		$arr = array(
			'condition' => $ta . ".cty_active=1",
		);
		return $arr;
	}

	public function scopes()
	{
		return array(
			'orderByName' => array(
				'order' => 'cty_name ASC',
			),
		);
	}

	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('cty_state_id,cty_name,cty_lat,cty_long', 'required', 'on' => 'insert'),
			array('id, cty_state_id, city_group, cty_active, cty_has_airport,cty_is_airport,cty_is_poi,cty_service_active', 'numerical', 'integerOnly' => true),
			array('cty_name,cty_alias_name', 'length', 'max' => 100),
			array('cty_place_id', 'validateDuplicate'),
			array('cty_county', 'length', 'max' => 50),
			array('cty_city_desc', 'length', 'max' => 8000),
			array('cty_short_desc, cty_pickup_drop_info', 'length', 'max' => 500),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('cty_id, id,cty_alias_name, cty_alias_path,cty_keyword_names, cty_name,cty_log, cty_state_id, cty_county,cty_has_airport,cty_is_airport,cty_is_poi, city_group, cty_city_desc, cty_short_desc, cty_pickup_drop_info,cty_ncr,cty_lat,cty_long,cty_radius, cty_active, cty_service_active, cty_garage_address,cty_code,cty_excluded_cabtypes,cty_zones,cty_is_approved,cty_place_id,cty_bounds,cty_types,cty_included_cabmodels,cty_poi_type', 'safe'),
		);
	}

	public function validateDuplicate($attribute, $params)
	{
		if ($this->isNewRecord)
		{
			$model = Cities::model()->find("cty_place_id=:placeId", ['placeId' => $this->cty_place_id]);
			if ($model)
			{
				$this->setAttributes($model->getAttributes());
				$this->isNewRecord = false;
			}
		}
		return true;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array(
			'ctyState'	 => array(self::BELONGS_TO, 'States', 'cty_state_id'),
			'zipCodes'	 => array(self::HAS_MANY, 'ZipCodes', 'zip_city_id'),
			'jsdCity'	 => array(self::HAS_MANY, 'JobServiceDetails', 'jsd_city'),
			'zoneCities' => array(self::HAS_MANY, 'ZoneCities', 'zct_cty_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cty_id'				 => 'id',
			'city_group'			 => 'Group',
			'cty_city_desc'			 => 'Desc',
			'cty_short_desc'		 => 'Short Desc',
			'cty_pickup_drop_info'	 => 'Pickup Drop Info',
			'cty_ncr'				 => 'NCR',
			'cty_name'				 => 'Name',
			'cty_alias_path'		 => 'Alias Path',
			'cty_alias_name'		 => 'Alias Name',
			'cty_state_id'			 => 'State',
			'cty_county'			 => 'District',
			'cty_lat'				 => 'Latitude',
			'cty_long'				 => 'Longitude',
			'cty_is_airport'		 => 'Is Airport',
			'cty_is_poi'			 => 'Is Point of Interest',
			'cty_garage_address'	 => 'Garage Address',
			'cty_excluded_cabtypes'	 => 'Excluded CabTypes',
			'cty_active'			 => 'Active',
			'cty_has_airport'		 => 'Has Airport',
			'cty_is_approved'		 => 'Approval State',
			'cty_state_name'		 => 'State',
			'cty_bounds'			 => 'Bounds',
			'cty_radius'			 => 'Radius'
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
		$criteria->compare('cty_id', $this->cty_id);
		$criteria->compare('id', $this->id);
		$criteria->compare('cty_name', $this->cty_name, true);
		$criteria->compare('cty_alias_name', $this->cty_alias_name, true);
		$criteria->compare('cty_alias_path', $this->cty_alias_path, true);
		$criteria->compare('cty_state_id', $this->cty_state_id);
		$criteria->compare('cty_county', $this->cty_county, true);
		$criteria->compare('city_group', $this->city_group);
		$criteria->compare('cty_city_desc', $this->cty_city_desc, true);
		$criteria->compare('cty_short_desc', $this->cty_short_desc, true);
		$criteria->compare('cty_pickup_drop_info', $this->cty_pickup_drop_info, true);
		$criteria->compare('cty_ncr', $this->cty_ncr, true);
		$criteria->compare('cty_is_airport', $this->cty_is_airport);
		$criteria->compare('cty_is_poi', $this->cty_is_poi);
		$criteria->compare('cty_has_airport', $this->cty_has_airport);
		$criteria->compare('cty_active', $this->cty_active);
		$criteria->compare('cty_service_active', $this->cty_service_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function beforeSave()
	{
		if ($this->cty_geo_id == null && $this->isNewRecord)
		{
			$this->setGeoId();
		}
		return parent::beforeSave();
	}

	public function afterSave()
	{
		parent::afterSave();
		if ($this->cty_full_name == null)
		{
			self::updateFullName($this->cty_id);
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cities the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getCityNameByState($stateId)
	{
		$sql	 = "SELECT cty_alias_path FROM cities WHERE cty_state_id = $stateId AND cty_active =1";
		$query	 = DBUtil::query($sql);
		$arr	 = [];
		foreach ($query as $val)
		{
			$arr[] = $val['cty_alias_path'];
		}
		return $arr;
	}

	public function getExcAirportCityNameByState($stateId)
	{
		$sql	 = "SELECT cty_alias_path FROM cities WHERE cty_state_id = $stateId AND cty_active =1 AND cty_is_airport=0";
		$query	 = DBUtil::query($sql);
		$arr	 = [];
		foreach ($query as $val)
		{
			$arr[] = $val['cty_alias_path'];
		}
		return $arr;
	}

	public static function getColumnValue($columnName, $id)
	{
		$sql = "SELECT {$columnName} FROM cities WHERE cty_id=:city";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar(["city" => $id]);
	}

	public static function getName($id)
	{
		return self::getColumnValue("cty_name", $id);
	}

	public static function getFullName($id)
	{
		return self::getColumnValue("cty_full_name", $id);
	}

	public static function getDisplayName($id)
	{
		return self::getColumnValue("cty_display_name", $id);
	}

	public static function getAliasPath($id)
	{
		return self::getColumnValue("cty_alias_path", $id);
	}

	public function getCityNamebyArr($idArr)
	{
		$sql	 = "select cty_display_name,cty_id from cities where cty_id in ($idArr)";
		$query	 = DBUtil::query($sql);
		$arr	 = [];
		foreach ($query as $val)
		{
			$arr[] = array("id" => $val['cty_id'], "text" => $val['cty_display_name']);
		}
		return $arr;
	}

	public function getCityByAliasPath($alias)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('cty_alias_name', $alias);
		return $this->find($criteria);
	}

	public function getCityDetailsById($Id, $cityBox)
	{
		$model	 = Cities::model()->findByPk($Id);
		$arrCity = array("id"		 => $model->cty_id,
			"lat"		 => $model->cty_lat,
			"long"		 => $model->cty_long,
			"radius"	 => $model->cty_radius,
			'citybox'	 => $cityBox);
		return $arrCity;
	}

	public function getIdByCity($city)
	{
		$criteria	 = new CDbCriteria();
		$criteria->compare('cty_name', $city);
		$data		 = $this->find($criteria);
		return $data->cty_id;
	}

	public function getIdByCityAlias($city)
	{
		$criteria	 = new CDbCriteria();
		$criteria->compare('cty_alias_path', $city);
		$data		 = $this->find($criteria);
		return $data->cty_id;
	}

	public function getByCity($city)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('cty_name', $city);
		return $this->find($criteria);
	}

	public function getByCity2($city)
	{
		if (trim($city) == "")
		{
			return false;
		}
		$criteria = new CDbCriteria();
		$criteria->compare('cty_alias_path', $city);
		return $this->find($criteria);
	}

	public function getJSON()
	{
		$ctModels	 = $this->model()->getAllCities();
		$data		 = CJSON::encode($ctModels);
		return $data;
	}

	public function getJSONServiceCity()
	{
		$ctModels	 = $this->model()->getLookup();
		$data		 = CJSON::encode($ctModels);
		return $data;
	}

	public function getJSON1()
	{
		$btModels		 = $this->model()->getCityByBooking();
		$arrBillingType	 = array();
		foreach ($btModels as $btModel)
		{
			$arrBillingType[] = array("id" => $btModel['cty_id'], "text" => $btModel['cty_name']);
		}
		$data = CJSON::encode($arrBillingType);
		return $data;
	}

	public function getCityByFromBooking()
	{
		$rows	 = array();
		$rows	 = DBUtil::queryAll("SELECT cty_id, cty_name FROM  cities WHERE  exists (SELECT bkg_from_city_id FROM   booking  WHERE  bkg_active = 1 and cty_id =bkg_from_city_id AND cty_active = 1) ORDER BY cty_name");
		foreach ($rows as $row)
		{
			$arrBillingType[] = array("id" => $row['cty_id'], "text" => $row['cty_name']);
		}
		$data = CJSON::encode($arrBillingType);
		return $data;
	}

	public function getCityByToBooking()
	{
		$rows	 = array();
		$rows	 = DBUtil::queryAll("SELECT cty_id, cty_name FROM  cities WHERE  exists (SELECT bkg_to_city_id FROM booking  WHERE  bkg_active = 1 and cty_id =bkg_to_city_id AND cty_active = 1) ORDER BY cty_name");
		foreach ($rows as $row)
		{
			$arrBillingType[] = array("id" => $row['cty_id'], "text" => $row['cty_name']);
		}
		$data = CJSON::encode($arrBillingType);
		return $data;
	}

	public function getCityArrByFromBooking()
	{
		$rows	 = array();
		$rows	 = DBUtil::queryAll("SELECT cty_id, cty_name FROM  cities WHERE  exists (SELECT bkg_from_city_id FROM   booking  WHERE  bkg_active = 1 and cty_id =bkg_from_city_id AND cty_active = 1) ORDER BY cty_name");
		$cities	 = [];
		foreach ($rows as $row)
		{
			$cities[$row['cty_id']] = $row['cty_name'];
		}
		return $cities;
	}

	public function getCityArrDistinct()
	{
		$rows	 = array();
		$rows	 = DBUtil::queryAll("SELECT cty_id,cty_name FROM cities WHERE cty_active=1 order by cty_name");
		$cities	 = [];
		foreach ($rows as $row)
		{
			$cities[$row['cty_id']] = $row['cty_name'];
		}
		return $cities;
	}

	public function getCityArrByToBooking()
	{
		$rows	 = array();
		$rows	 = DBUtil::queryAll("SELECT cty_id, cty_name FROM  cities WHERE    exists (SELECT bkg_to_city_id FROM   booking  WHERE  bkg_active = 1 and cty_id =bkg_to_city_id AND cty_active = 1) ORDER BY cty_name");
		$cities	 = [];
		foreach ($rows as $row)
		{
			$cities[$row['cty_id']] = $row['cty_name'];
		}
		return $cities;
	}

	public function getCityByFromBooking1()
	{
		$rows	 = array();
		$rows	 = DBUtil::queryAll("SELECT cty_id, cty_name FROM cities WHERE EXISTS (SELECT  rut_from_city_id  FROM route WHERE  rut_active = 1 AND cty_id = rut_from_city_id AND cty_active = 1) ORDER BY cty_name");
		foreach ($rows as $row)
		{
			$arrBillingType[] = array("id" => $row['cty_id'], "text" => $row['cty_name']);
		}
		$data = CJSON::encode($arrBillingType);
		return $data;
	}

	public function getCityByToBooking1()
	{
		$rows	 = array();
		$rows	 = DBUtil::queryAll("SELECT cty_id, cty_name FROM cities WHERE EXISTS (SELECT  rut_to_city_id  FROM route WHERE  rut_active = 1 AND cty_id = rut_to_city_id AND cty_active = 1) ORDER BY cty_name");
		foreach ($rows as $row)
		{
			$arrBillingType[] = array("id" => $row['cty_id'], "text" => $row['cty_name']);
		}
		$data = CJSON::encode($arrBillingType);
		return $data;
	}

	public function getCityByBooking1()
	{
		$rows	 = array();
		$rows	 = DBUtil::queryAll("SELECT cty_id,cty_name FROM cities WHERE cty_active=1 order by cty_name");
		foreach ($rows as $row)
		{
			$arrBillingType[] = array("id" => $row['cty_id'], "text" => $row['cty_name']);
		}
		$data = CJSON::encode($arrBillingType);
		return $data;
	}

	public function getCityOnlyByBooking1()
	{
		$rows	 = array();
		//$rows	 = DBUtil::queryAll("SELECT cty_id, cty_name FROM  cities WHERE exists (SELECT ctt_city  FROM   vendors INNER JOIN contact ON vendors.vnd_contact_id = contact.ctt_id WHERE  vnd_active = 1 and cty_id=contact.ctt_city ) ORDER BY cty_name");
		$rows	 = DBUtil::queryAll("SELECT cty_id, cty_name FROM cities WHERE EXISTS( SELECT ctt_city FROM vendors as v
						INNER JOIN vendors on vendors.vnd_id =v.vnd_ref_code
						INNER JOIN contact_profile as cp on cp.cr_is_vendor = vendors.vnd_id and cp.cr_status =1
						INNER JOIN contact ON contact.ctt_id= cp.cr_contact_id AND contact.ctt_active =1 and contact.ctt_id =contact.ctt_ref_code WHERE vendors.vnd_active = 1 AND cty_id = contact.ctt_city ) ORDER BY cty_name");
		if (count($rows) > 0)
		{
			$data[''] = 'Home City';
			foreach ($rows as $row)
			{
				$data[$row['cty_id']] = trim($row['cty_name']);
			}
		}
		return $data;
	}

	public function getServiceCity()
	{
		$criteria		 = new CDbCriteria();
		$criteria->order = 'cty_name';
		$criteria->group = "cty_name, cty_id";
		$criteria->with	 = ['ctyState' => ['select' => 'stt_name']];
		$criteria->compare('cty_service_active', 1);
		$criteria->compare('cty_active', 1);
		return Cities::model()->findAll($criteria);
	}

	public function getLookup()
	{
		$qry		 = "SELECT city.`cty_id` as id, city.cty_display_name as text	FROM `cities` city  WHERE city.cty_active=1 AND cty_service_active=1";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getAllCities($select = true)
	{
		if ($select)
		{
			$query = "city.`cty_id` as id, city.cty_display_name as text";
		}
		else
		{
			$query = "city.cty_id, city.cty_name";
		}
		$qry		 = "SELECT  $query  FROM `cities` city	WHERE (city.cty_active=1) ORDER BY cty_name";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getServiceCities()
	{
		$sql		 = "SELECT city.`cty_id`, city.cty_display_name as cty_name FROM `cities` city	WHERE city.cty_active=1 AND cty_service_active=1 ORDER BY cty_name";
		$recordset	 = DBUtil::queryAll($sql);
		return $recordset;
	}

	public function getSourceCities($query = '', $city = '')
	{
		$params1 = [];
		$query	 = ($query == null || $query == "") ? "" : $query;
		$query1	 = str_replace(" ", "%", trim($query));
	//	DBUtil::getLikeStatement($query1, $bindString0, $params1);
		DBUtil::getLikeStatement($query1, $bindString3, $params3, "");
		($query == null || $query == "") ? DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '"') : DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '"');
		$qry	 = '';
		$qry1	 = "";
		if ($city != '')
		{
			$qry1 = " AND c.cty_id in ($city)";
		}
		else
		{
			$qry1 = " AND cl.cty_id IS NOT NULL AND c.cty_service_active=1";
		}
		
		if ($query != '')
		{
			$whereQry	 = [];
			$qrys		 = explode(" ", $query);
			foreach ($qrys as $key => $val)
			{
				$qkey = ":qval" . $key;
				$params1[$qkey] = trim($val);

				$whereQry[] = "c.cty_display_name LIKE CONCAT('%',$qkey,'%')";
			}

			$qry .= " AND (" . implode(" AND ", $whereQry) . ")";
		}
		else
		{
			$params1 = [];
		}

		$order = " startRank DESC, rank ASC, score DESC, cty_full_name ASC ";
		if ($query == '' && $city == '')
		{
			$order = " rank ASC, score DESC, startRank DESC, cty_full_name ASC ";
		}

		$sql = "SELECT c.cty_id , cl.cty_name as cty_name, MATCH(cl.cty_name) AGAINST ($bindString1 IN NATURAL LANGUAGE MODE)  AS score,
				IF(cl.cty_name LIKE $bindString3,1,0) AS startRank, IFNULL(rank, 99) as rank
				FROM cities c
				JOIN city_list cl ON cl.cty_id=c.cty_id
				LEFT JOIN topcitiesstats ON ctyId=c.cty_id
				WHERE 1  $qry $qry1 AND c.cty_active=1
				ORDER BY $order LIMIT 0,6";
		return DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params2, $params3), 4 * 1 * 60 * 60, "cities");
	}

	public static function getByMatchingKeyword($query = '')
	{

		$params = ["key" => $query];

		$sql = "SELECT c.cty_id , c.cty_full_name as cty_name, cty_service_active, c.cty_alias_path, 
					(IF(SOUNDEX(cty_name)=SOUNDEX(:key), 1, 0) + IF(SOUNDEX(cty_display_name)=SOUNDEX(:key), 1, 0) + IF(SOUNDEX(cty_full_name)=SOUNDEX(:key), 1, 0) + IF(SOUNDEX(cty_alias_path)=SOUNDEX(:key), 1, 0) + IF(SOUNDEX(cty_alias_name)=SOUNDEX(:key), 1, 0) + IF(cty_alias_path LIKE CONCAT('%',:key,'%'),1,0) + IF(cty_full_name LIKE CONCAT('%',:key,'%'),1,0) + IF(cty_display_name LIKE CONCAT('%',:key,'%'),1,0) + IF(:key LIKE  CONCAT('%',cty_alias_path,'%'),1,0)) as score,
					MATCH(cty_name, cty_display_name, cty_full_name, cty_alias_path, cty_alias_name) AGAINST (CONCAT(\"'\", :key, \"'\") IN NATURAL LANGUAGE MODE)  AS matchScore, 
					IF(cty_full_name LIKE CONCAT(:key, '%'),1,0) AS startRank
				FROM cities c
				WHERE cty_active=1 AND 
					(SOUNDEX(cty_name)=SOUNDEX(:key) OR SOUNDEX(cty_display_name)=SOUNDEX(:key) OR SOUNDEX(cty_full_name)=SOUNDEX(:key) OR SOUNDEX(cty_alias_path)=SOUNDEX(:key)
						OR MATCH(cty_name, cty_display_name, cty_full_name, cty_alias_path, cty_alias_name) AGAINST (CONCAT(\"'\", :key, \"'\") IN NATURAL LANGUAGE MODE)  OR cty_alias_path LIKE CONCAT('%',:key,'%')
						 OR cty_full_name LIKE CONCAT('%',:key,'%') OR cty_display_name LIKE CONCAT('%',:key,'%') OR :key LIKE  CONCAT('%',cty_alias_path,'%'))
					AND c.cty_active=1 
				ORDER BY `score` DESC, matchScore DESC, startRank DESC LIMIT 0,10;";
		return DBUtil::query($sql, DBUtil::SDB(), $params, 4 * 24 * 60 * 60, "cities");
	}

	/*
	 * The following function is used for getting the list of all cities for ajax requests.
	 */

	public function getSourceCitiesforZone($query = '', $city = '', $lat = 0, $long = 0)
	{
		$query	 = ($query == null || $query == "") ? "" : $query;
		$query1	 = str_replace(" ", "%", trim($query));
		$qry	 = '';
		$qry1	 = "";
		if ($city != '')
		{
			$qry1 = "OR c.cty_id in ($city)";
		}

		if ($query != '')
		{
			$qry .= " AND c.cty_display_name LIKE '$query1%' ";
		}

		$sql		 = "SELECT
                    c.cty_id,
                    c.cty_name,c.cty_display_name,
                    zones.zon_id,
                    zones.zon_name,
                    zones.zon_lat,zones.zon_long,
                    c.cty_lat,c.cty_long
              FROM cities c
              INNER JOIN  zone_cities ON zone_cities.zct_cty_id=c.cty_id AND zone_cities.zct_active =1
              INNER JOIN zones ON zone_cities.zct_zon_id=zones.zon_id
              WHERE  c.cty_active=1
              AND (c.cty_lat BETWEEN (:lat - 1) AND (:lat + 1) AND c.cty_long BETWEEN (:long - 1) AND (:long + 1)
			AND  CalcDistance(c.cty_lat, c.cty_long,:lat, :long)<150 $qry) $qry1";
		$rows		 = DBUtil::query($sql, DBUtil::SDB(), ["lat" => $lat, "long" => $long], 4 * 1 * 60 * 60, "cities");
		$arrCities	 = array();
		$i			 = 0;
		foreach ($rows as $row)
		{
			$i++;
			$arrCities[] = array("id" => $row['cty_id'], "text" => $row['cty_display_name'], "index" => $i);
		}


		$data = CJSON::encode($arrCities);

		return $data;
	}

	public function getSourceListCities($query = '', $city = '')
	{
		$query = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1);
		($query == null || $query == "") ? DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '"') : DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '*"');

		$qry	 = '';
		$qry1	 = "";
		if ($city != '')
		{
			$qry1 = " AND city_list.cty_id='$city'";
		}
		if ($query != '')
		{
			$qry = " AND cities.cty_full_name LIKE $bindString0 ";
		}

		$order = " startRank DESC, rank ASC, score DESC, city_list.cty_name ";
		if ($query == '' && $city == '')
		{
			$order = " rank ASC, score DESC, startRank DESC, city_list.cty_name ";
		}


		$sql = "SELECT city_list.cty_id,cities.cty_full_name as cty_name, cities.cty_bounds,
						MATCH (city_list.cty_name) AGAINST ($bindString1 IN NATURAL LANGUAGE MODE)  AS score,
						IF(city_list.cty_name LIKE $bindString0,1,0) AS startRank
				FROM city_list, cities, topcitiesstats
				WHERE ctyId=cities.cty_id AND cities.cty_id=city_list.cty_id $qry $qry1
				ORDER BY $order LIMIT 0,30";
                
		return DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params2));
	}

	public function getAllCitiesbyQuery($query = '', $city = '', $airportShow = '0')
	{
		$qry	 = '';
		$params3 = [];
		$params4 = [];
		$query	 = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1, "");
		DBUtil::getLikeStatement($query, $bindString1, $params2, '', '');

		if ($city != '')
		{
			DBUtil::getINStatement($city, $bindString4, $params4);
			$qry1 = " AND  cty_id IN ({$bindString4})";
		}
		if ($query != '')
		{
			DBUtil::getLikeStatement($query, $bindString3, $params3);
			$qry .= " AND cty_full_name LIKE $bindString3 ";
		}
		if ($airportShow == '0')
		{
			$qry .= " AND cty.cty_is_airport<>1";
		}
		if ($city != '')
		{
			$sql = "SELECT cty.cty_id, cty_full_name as cty_name, IF(cty_service_active = 0, '(InActive)', '') as status
					FROM cities cty WHERE cty.cty_active=1 $qry1";
			return DBUtil::query($sql, DBUtil::SDB(), $params4);
		}
		else
		{

			$order = " startRank DESC, ctyRank ASC, statusRank DESC, score DESC, cty.cty_display_name ASC ";
			if ($query == '' && $city == '')
			{
				$order = " ctyRank ASC, statusRank DESC, startRank DESC, score DESC, cty.cty_display_name ASC ";
			}


			$sql = "SELECT cty.cty_id, cty_full_name as cty_name,
						MATCH (cty_display_name) AGAINST ($bindString1  IN NATURAL LANGUAGE MODE)  AS score,
						IF(cty_display_name LIKE $bindString0, 1, 0) AS startRank, IFNULL(rank,10) as ctyRank,
						IF(cty_service_active = 0, ' (InActive)', '') as status,
						IF(cty_service_active = 1, 1, 0) as statusRank
					FROM cities cty
					LEFT JOIN topcitiesstats tcs ON cty.cty_id=tcs.ctyId
					WHERE cty.cty_active=1  $qry $qry1
					ORDER BY $order
					LIMIT 0,7";

			return DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params2, $params3, $params4));
		}
	}

	public function updateSourceCities()
	{
		$transaction = Yii::app()->db->beginTransaction();
		try
		{

			echo $sql	 = "TRUNCATE TABLE city_list";
			echo DBUtil::command($sql)->execute();
			echo $sql	 = "INSERT INTO city_list
						SELECT DISTINCT `t`.`cty_id`, concat(cty_name, if((cty_alias_name IS NULL || cty_alias_name=''),'',concat(' (',cty_alias_name,')')),', ',ctyState.stt_name) as cty_name,
							  `ctyState`.`stt_id`, `ctyState`.`stt_name`, `ctyState`.`stt_code`, `ctyState`.`stt_country_id`, `ctyState`.`stt_zone`,
							  `ctyState`.`stt_active`
						FROM `cities` `t`
						INNER JOIN `states` `ctyState` ON (`t`.`cty_state_id`=`ctyState`.`stt_id`)
						WHERE ((t.cty_active=1) AND ((((cty_service_active=1)) AND (cty_active=1)) AND
                            ((cty_id IN (
                                    SELECT rut_from_city_id FROM route WHERE rut_active=1
                                            AND rut_id IN (SELECT rte_route_id FROM rate WHERE rte_status=1)
                                )
                            OR cty_id IN (
                                    SELECT DISTINCT r2.rut_to_city_id FROM route
                                    INNER JOIN route r2 ON route.rut_id<>r2.rut_id AND r2.rut_from_city_id=route.rut_from_city_id AND r2.rut_estm_distance<90  AND r2.rut_active=1
                                    AND route.rut_id IN (SELECT rte_route_id FROM rate WHERE rte_status=1)
                                )
                            OR cty_id IN (
                                    SELECT DISTINCT cty_id FROM vendors
									INNER JOIN contact ON contact.ctt_id = vendors.vnd_contact_id
                                    INNER JOIN route ON contact.ctt_city=route.rut_from_city_id AND rut_active=1 AND vnd_active=1
                                    INNER JOIN cities ON route.rut_to_city_id=cities.cty_id AND route.rut_estm_distance<100
                                ))
                            ))) ORDER BY cty_name";
			echo $rows	 = DBUtil::command($sql)->execute();
			if ($rows > 0)
			{
				$transaction->commit();
			}
			else
			{
				throw new Exception("Failed to update city list");
			}
		}
		catch (Exception $e)
		{
			$transaction->rollback();
		}
	}

	public function getRateSourceCities()
	{

		$criteria			 = new CDbCriteria();
		$criteria->select	 = array("cty_id", "concat(cty_name, if((cty_alias_name IS NULL || cty_alias_name=''),'',concat(' (',cty_alias_name,')')),', ',ctyState.stt_name) as cty_name");
		$criteria->with		 = "ctyState";
		$criteria->order	 = 'cty_name';
		$criteria->compare('cty_service_active', 1);
		$criteria->compare('cty_active', 1);
		$criteria->addCondition("(cty_id IN (SELECT rut_from_city_id FROM route WHERE rut_active=1
            AND rut_id IN (SELECT rte_route_id FROM rate WHERE rte_status=1))
            OR cty_id IN (SELECT cty_id FROM (" . $this->getNearestCitiesSQL(30) . ") a)
                OR cty_id IN (SELECT DISTINCT c2.cty_id FROM cities c1 LEFT JOIN contact ON contact.ctt_city = c1.cty_id
				INNER JOIN vendors ON vendors.vnd_contact_id = contact.ctt_id AND vnd_active = 1
			INNER JOIN cities c2 ON SQRT( POW(69.1 * (c1.cty_lat - c2.cty_lat), 2) + POW(69.1 * (c2.cty_long - c1.cty_long) * COS(c1.cty_lat / 57.3), 2)) < 30 AND c2.cty_service_active=1))
								");
		return Cities::model()->findAll($criteria);
	}

	public function getNearestCitiesSQL($distance = 60)
	{
		$sql = "SELECT DISTINCT cty_id, cty_name as cty_name, MIN(rut_estm_distance) as distance FROM route
                        INNER JOIN cities ON cities.cty_id = route.rut_from_city_id
                        WHERE rut_from_city_id NOT IN
                                (SELECT rut_from_city_id FROM route  INNER JOIN rate ON route.rut_id = rate.rte_route_id AND rate.rte_status=1)
                         AND route.rut_to_city_id IN (
                                SELECT rut_from_city_id FROM route  INNER JOIN rate ON route.rut_id = rate.rte_route_id AND rate.rte_status=1
                                )
                        AND route.rut_estm_distance<$distance and route.rut_active=1 GROUP BY cities.cty_id ORDER BY distance ASC";
		return $sql;
	}

	/**
	 *
	 * @param integer $query
	 * @param string $city
	 * @return type
	 */
	public function getJSONCities($query, $city = '', $status = 0)
	{
		$rows	 = $this->getSourceCities($query, $city);
		$data	 = array();
		$i		 = 0;
		foreach ($rows as $row)
		{
			$i++;
			$data[] = array("id" => (int) $row['cty_id'], "name" => $row['cty_name'], "index" => "$i");
		}
		if ($status == 1)
		{
			$data = CJSON::encode($arrCities);
		}
		return $data;
	}

	public function getJSONSourceCities($query, $city = '', $status = 0)
	{
		if ($city != "")
		{

			$rows = $this->getAllCitiesbyQuery($query, $city);
		}
		else
		{
			$rows = $this->getSourceCities($query, $city);
		}

		$arrCities	 = array();
		$i			 = 0;
		foreach ($rows as $row)
		{
			$i++;
			$arrCities[] = array("id" => $row['cty_id'], "text" => $row['cty_name'], "index" => $i);
		}
		if ($status == 1)
		{
			$data = $arrCities;
		}
		else
		{
			$data = CJSON::encode($arrCities);
		}

		return $data;
	}

	public function getJSONSourceCitiesDR($query, $city = '', $status = 0, $isApp = false)
	{
		$query = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1);
		($query == null || $query == "") ? DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '"') : DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '*"');

		$drCities	 = implode(",", Yii::app()->params['dayRentalCities']);
		$qry		 = '';
		$qry1		 = '';
		if ($city != '' && in_array($city, Yii::app()->params['dayRentalCities']))
		{
			$qry1 .= " AND  cty_id in ($drCities)";
		}
		if ($query != '')
		{
			$qry .= " AND cty_name LIKE  $bindString0 ";
		}

		$sql		 = "SELECT cty_id ,cty_name,MATCH(cty_name) AGAINST ($bindString1 IN BOOLEAN MODE)  AS score
		,IF(cty_name LIKE $bindString0,1,0) AS startRank	FROM city_list  WHERE 1  $qry $qry1 ORDER BY score DESC,startRank DESC,cty_name ASC ";
		$rows		 = DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params2));
		$arrCities	 = array();
		foreach ($rows as $row)
		{
			$arrCities[] = array("id" => $row['cty_id'], "text" => $row['cty_name']);
		}
		if ($status == 1)
		{
			$data = $arrCities;
		}
		else
		{
			$data = CJSON::encode($arrCities);
		}
		return $data;
	}

	public function getSourceCitiesDR($query = '', $city = '', $isApp = false)
	{
		$query = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1);
		($query == null || $query == "") ? DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '"') : DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '*"');

		$qry	 = "";
		$qry1	 = "";
		if ($city != "")
		{
			$qry1 = " AND city_list.cty_id IN ('$city')";
		}
		if ($query != "")
		{
			$qry = " AND city_list.cty_name LIKE $bindString0 ";
		}
		$sql = "SELECT *,MATCH(cty_name) AGAINST ($bindString1 IN BOOLEAN MODE)  AS score,IF(cty_name LIKE $bindString0,1,0) AS startRank FROM city_list  WHERE 1 $qry $qry1 ORDER BY score DESC,startRank DESC,cty_name ASC ";
		return DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params2));
	}

	public function getJSONListCities($query, $city = '')
	{
		$rows = $this->getSourceListCities($query, $city);
                
               

		$arrCities = array();

		foreach ($rows as $row)
		{
			$arrCities[] = array("id" => $row['cty_id'], "text" => $row['cty_name'], 'city_bound' => $row['cty_bounds']);
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	public function getJSONAllCitiesbyQuery($query = '', $city = '', $airportShow = '0')
	{

		$rows		 = $this->getAllCitiesbyQuery($query, $city, $airportShow);
		$arrCities	 = array();
		foreach ($rows as $row)
		{
			$arrCities[] = array("id" => $row['cty_id'], "text" => $row['cty_name'] . ' ' . $row['status']);
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	public function getJSONRateSourceCities()
	{
		$models		 = $this->getRateSourceCities();
		$arrCities	 = array();
		foreach ($models as $model)
		{
			$arrCities[] = array("id" => $model->cty_id, "text" => $model->cty_name);
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	public function getRateCities()
	{
		$criteria			 = new CDbCriteria();
		$criteria->select	 = array("cty_id,cty_name");
		$criteria->order	 = 'cty_name';
		$criteria->compare('cty_service_active', 1);
		$criteria->compare('cty_active', 1);
		$criteria->addCondition("cty_id IN (SELECT rut_from_city_id FROM route WHERE rut_active=1 AND rut_id IN (
                SELECT rte_route_id FROM rate WHERE rte_status=1))");
		return Cities::model()->findAll($criteria);
	}

	public function getRateDestinationCities($fromCity)
	{
		$criteria			 = new CDbCriteria();
		$criteria->select	 = array("cty_id,cty_name");
		$criteria->order	 = 'cty_name';
		$criteria->compare('cty_service_active', 1);
		$criteria->compare('cty_active', 1);
		$criteria->addCondition("cty_id IN (SELECT rut_to_city_id FROM route WHERE rut_active=1 AND rut_id IN (
                SELECT rte_route_id FROM rate WHERE rte_status=1
            ) AND rut_from_city_id='$fromCity')");
		return Cities::model()->findAll($criteria);
	}

	public function getRateDestinationCitiesAll($fromCity)
	{
		$sql = "SELECT
						city.`cty_id` as id,
						city.cty_display_name as text,
						city.`cty_id`,
						city.cty_display_name as cty_name ,
						city.cty_name As city_name ,
						SUBSTRING_INDEX(city.cty_display_name, ',', -1) AS stt_name
						FROM `cities` city
						WHERE city.cty_active=1 AND city.cty_id != $fromCity
						ORDER BY cty_name ASC";
		return DBUtil::queryAll($sql);
	}

	public function getJSONRateDestinationCities($fromCity)
	{
		$models		 = $this->getRateDestinationCities($fromCity);
		$arrCities	 = array();
		foreach ($models as $model)
		{
			$arrCities[] = array("id" => $model->cty_id, "text" => $model->cty_name);
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	public function getJSONRateDestinationCitiesAll($fromCity = 0)
	{
		$models	 = $this->getRateDestinationCitiesAll($fromCity);
		$data	 = CJSON::encode($models);
		return $data;
	}

	public function getJSONRateCities()
	{
		$models		 = $this->getRateCities();
		$arrCities	 = array();
		foreach ($models as $model)
		{
			$arrCities[] = array("id" => $model->cty_id, "text" => $model->cty_name);
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	public function getAllCityList($isRegistor = false)
	{
		if ($isRegistor)
		{
			$cond = " AND cty_is_airport = 0";
		}
		$qry	 = "ORDER BY cty_name";
		$sql	 = "SELECT cty_id, cty_name FROM `cities` city WHERE (city.cty_active=1)$cond $qry";
		$arrCity = CHtml::listData(DBUtil::queryAll($sql), 'cty_id', 'cty_name');
		return $arrCity;
	}

	public function getServiceableCityListforAgents()
	{
		$sql = "SELECT   cty_id AS city_id, cty_name AS city_name , SUBSTRING_INDEX(cty_display_name, ',', -1)  AS state_name, cty_lat AS latitute, cty_long AS longitude, cty_garage_address AS garage_address FROM cities WHERE  cty_active = 1 AND cty_service_active = 1 ORDER BY cty_name";
		return DBUtil::queryAll($sql);
	}

	public function getCityListforAgents()
	{
		$sql = "SELECT cty_id AS id, cty_name AS name, SUBSTRING_INDEX(cty_display_name, ',', -1)  AS  state FROM cities  WHERE cty_active = 1 AND cty_service_active = 1 ORDER BY cty_name";
		return DBUtil::queryAll($sql);
	}

	public function getAllCityListforApp($not_airport = 1)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('cty_active', 1);
		if ($not_airport == 0)
		{
			$criteria->compare('cty_is_airport', 0);
		}
		if ($this->cty_name != '')
		{
			$criteria->addCondition("cty_name LIKE '%" . $this->cty_name . "%'");
		}
		$criteria->with	 = 'ctyState';
		$criteria->order = 'cty_name';
		$arrCity		 = Cities::model()->findAll($criteria);
		$data			 = array();
		foreach ($arrCity as $key => $val)
		{
			$data[] = array("id" => $val->cty_id, "name" => $val->cty_name . ', ' . $val->ctyState->stt_name);
		}
		return $data;
	}

	public function getAllCityListforUserApp()
	{
		$qry = "SELECT cty_id AS id, cty_display_name AS name, cty_lat AS latitute, cty_long AS longitude, cty_garage_address AS garage_address FROM cities	WHERE cty_active = 1 ORDER BY cty_name";
		return DBUtil::queryAll($qry);
	}

	public function getServiceableCityListforUserApp()
	{
		$qry = "SELECT cty_id AS id, cty_display_name AS name, cty_lat AS latitute, cty_long AS longitude, cty_garage_address AS garage_address FROM cities WHERE cty_active = 1 AND cty_service_active = 1 ORDER BY cty_name";
		return DBUtil::queryAll($qry);
	}

	public function getStatesByIds($cities)
	{
		$sql = "SELECT GROUP_CONCAT(DISTINCT cty_state_id) FROM cities WHERE cty_id IN ($cities)";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getbyNamenStateid($city, $state)
	{
		$criteria	 = new CDbCriteria();
		$criteria->compare('cty_name', $city);
		$criteria->compare('cty_state_id', $state);
		$data		 = $this->find($criteria);
		return $data;
	}

	public function fetchList()
	{
		$criteria = new CDbCriteria();
		if ($this->cty_name != "")
		{
			$criteria->addCondition("cty_name LIKE '%" . $this->cty_name . "%' OR cty_alias_name LIKE '%" . $this->cty_name . "%'");
		}
		if ($this->cty_state_id != "")
		{
			$criteria->compare('cty_state_id', $this->cty_state_id);
		}
		if ($this->cty_county != "")
		{
			$criteria->compare('cty_county', $this->cty_county, true);
		}
		if ($this->cty_city_desc != "")
		{
			$criteria->compare('cty_city_desc', $this->cty_city_desc, true);
		}
		if ($this->cty_ncr != "")
		{
			$criteria->compare('cty_ncr', $this->cty_ncr, true);
		}
		$criteria->compare('cty_service_active', 1);
// $criteria->order = 'cty_id DESC';
		$criteria->with	 = ['ctyState' => ['select' => 'stt_name']];
		$dataProvider	 = new CActiveDataProvider(Cities::model()->together(), ['criteria'	 => $criteria, 'sort'		 => array(
				'attributes'	 => ['cty_name', 'ctyState.stt_name', 'cty_county', 'cty_city_desc', 'cty_pickup_drop_info', 'cty_ncr'],
				'defaultOrder'	 => 'cty_name, ctyState.stt_name'
			), 'pagination' => array('pageSize' => 20)]);
		return $dataProvider;
	}

	/**
	 * This function is used for fetching the city details
	 * @param type $type
	 * @return \CSqlDataProvider
	 */
	public function getList($type = '')
	{
		$sql = "
			SELECT
			cities.cty_id,
			cities.cty_alias_name,
			cities.cty_county,
			cities.cty_city_desc,
			cities.cty_ncr,
			cities.cty_name,
			cities.cty_long,
			cities.cty_lat,
			cities.cty_radius,
			states.stt_name,
			GROUP_CONCAT(DISTINCT vct_label SEPARATOR ',')   AS `vht_makes`,
			GROUP_CONCAT(DISTINCT (zones.`zon_name`))   AS `zon_name`
			FROM `cities`
			LEFT JOIN svc_class_vhc_cat svc	ON FIND_IN_SET(svc.scv_id, cities.cty_excluded_cabtypes)
			LEFT JOIN service_class ON scc_id = scv_scc_id
			LEFT JOIN vehicle_category vc ON vct_id = scv_vct_id
			LEFT JOIN `states` ON states.stt_id=cities.cty_state_id
			LEFT JOIN `zone_cities` ON cities.cty_id = zone_cities.zct_cty_id   AND zone_cities.zct_active = 1
			LEFT JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id
			WHERE cities.cty_service_active = 1 AND cities.cty_active = 1	";

		$sqlCount = " SELECT count(cities.cty_id) FROM `cities` WHERE cities.cty_service_active = 1 AND cities.cty_active = 1 ";

		if (isset($this->cty_name) && $this->cty_name != "")
		{
			$sql		 .= " AND (cities.cty_name LIKE '%" . $this->cty_name . "%' OR cities.cty_alias_name LIKE '%" . $this->cty_name . "%')";
			$sqlCount	 .= " AND (cities.cty_name LIKE '%" . $this->cty_name . "%' OR cities.cty_alias_name LIKE '%" . $this->cty_name . "%')";
		}
		if (isset($this->cty_state_id) && $this->cty_state_id != "")
		{
			$sql		 .= " AND cities.cty_state_id=$this->cty_state_id";
			$sqlCount	 .= " AND cities.cty_state_id=$this->cty_state_id";
		}
		if (isset($this->cty_county) && $this->cty_county != "")
		{
			$sql		 .= " AND cities.cty_county LIKE '%$this->cty_county%'";
			$sqlCount	 .= " AND cities.cty_county LIKE '%$this->cty_county%'";
		}
		if (isset($this->cty_city_desc) && $this->cty_city_desc != "")
		{
			$sql		 .= " AND cities.cty_city_desc LIKE '%$this->cty_city_desc%'";
			$sqlCount	 .= " AND cities.cty_city_desc LIKE '%$this->cty_city_desc%'";
		}
		if (isset($this->cty_ncr) && $this->cty_ncr != "")
		{
			$sql		 .= " AND cities.cty_ncr='$this->cty_ncr'";
			$sqlCount	 .= " AND cities.cty_ncr='$this->cty_ncr'";
		}
		if (isset($this->cty_is_airport) && $this->cty_is_airport != "")
		{
			$sql		 .= " AND cities.cty_is_airport='$this->cty_is_airport'";
			$sqlCount	 .= " AND cities.cty_is_airport='$this->cty_is_airport'";
		}
		$sql .= " GROUP BY cities.cty_id";
		if ($type == 'command')
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
		else
		{
			$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['cty_name', 'stt_name', 'zon_name', 'cty_county', 'cty_city_desc', 'cty_pickup_drop_info', 'cty_ncr', 'cty_radius'],
					'defaultOrder'	 => 'cty_name ASC'],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
	}

	public static function checkActive($id)
	{
		if ($id == null || $id == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql = "SELECT COUNT(1) as cnt FROM cities WHERE cty_active=1 AND cty_service_active=1 AND cty_id={$id}";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	public function getDetails($cityid)
	{
		$criteria		 = new CDbCriteria();
		$criteria->compare('cty_id', $cityid);
		$criteria->with	 = ['ctyState'];
		$cmodel			 = $this->find($criteria);
		return $cmodel;
	}

	public function getDetailsArr($cityids)
	{
		$criteria		 = new CDbCriteria();
		$criteria->addInCondition('cty_id', $cityids);
		$criteria->with	 = ['ctyState'];
		$cmodel			 = $this->findAll($criteria);
		return $cmodel;
	}

	public static function list()
	{
		$sql = "SELECT cty_id,cty_id as cityid,cty_name, SUBSTRING_INDEX(cty_display_name, ',', -1) AS stt_name,cty_display_name FROM cities WHERE cty_active=1 and cty_service_active =1 and cty_state_id is not null ORDER BY cty_name ASC";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public function getCityList1($state_id = '', $seach_txt = '')
	{
		$sql = 'Select cty_id, cty_name, cty_state_id from cities where cty_active = 1';
		$sql .= ($state_id != '') ? " AND cty_state_id=$state_id" : "";
		$sql .= ($seach_txt != '') ? " AND cty_name LIKE '%$seach_txt%'" : "";

		$recordSet = DBUtil::queryAll($sql);
		return $recordSet;
	}

	public function getCityLog($ctyid)
	{
		$qry	 = "select cty_log from cities where cty_id = " . $ctyid;
		$logList = DBUtil::queryRow($qry);
		return $logList;
	}

	public function addLog($oldData, $newData)
	{
		if ($oldData)
		{
			$getDifference	 = array_diff_assoc($oldData, $newData);
			$remark			 = $this->cty_log;
			$dt				 = date('Y-m-d H:i:s');
			$user			 = Yii::app()->user->getId();
//if ($remark) {
			if (is_string($remark))
			{
				$newcomm = CJSON::decode($remark);
			}
			else if (is_array($remark))
			{
				$newcomm = $remark;
			}
			if ($newcomm == false)
			{
				$newcomm = array();
			}
			if (count($getDifference) > 0)
			{
				while (count($newcomm) >= 50)
				{
					array_pop($newcomm);
				}
				array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $getDifference));

				$log = CJSON::encode($newcomm);
				return $log;
//}
			}
		}
		if (!$oldData && $newData)
		{
			$dt		 = date('Y-m-d H:i:s');
			$user	 = Yii::app()->user->getId();
			$newcomm = array();
			array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => ''));
			$log	 = CJSON::encode($newcomm);
			return $log;
		}
		return $remark;
	}

	public function getDistTimeByFromCityAndToCity($fromCity, $toCity)
	{

		//$api	 = Yii::app()->params['googleApiKey'];
		$api	 = Config::getGoogleApiKey('apikey');
		$url	 = 'https://maps.google.com/maps/api/distancematrix/json?units=metric';
		$data	 = "&origins=" . urlencode($fromCity) . "&destinations=" . urlencode($toCity) . "&sensor=false&mode=driving&key=$api";
		$map	 = $url . $data;

// echo $map; exit();

		$result = GoogleMapAPI::getInstance()->callAPI($map, 2);
		if ($result['success'])
		{
			$outputFrom	 = $result['data'];
			$cnt		 = count($outputFrom->rows);

			for ($i = 0; $i < $cnt; $i++)
			{
				$status = $outputFrom->rows[$i]->elements[$i]->status;
				if ($status != "OK")
				{
					$result['success']		 = false;
					$result['errorCode']	 = 3;
					$result['errorMessage']	 = $status;
				}
				else
				{
					$time				 = $outputFrom->rows[$i]->elements[$i]->duration->value;
					$dist				 = $outputFrom->rows[$i]->elements[$i]->distance->value;
					$totDist			 = round($dist / 1000);
					$totmins			 = round($time / 60);
					$result['distance']	 = $totDist;
					$result['duration']	 = $totmins;
				}
			}

			return $result;
		}
		return $result;
	}

	/**
	 * @new parameterized version
	 */
	public function getNearestCitiesDistanceListbyId($fromCity, $maxDistance = 1000, $forAirport = false, $queryStr = "", $limit = "", $selectedCity = "")
	{
		$sqlParams = ['maxDistance' => $maxDistance, 'fromCity' => $fromCity];
		if ($forAirport)
		{
			$qry			 = "if((cty1.cty_radius IS NULL || cty1.cty_radius=0),:maxDistance,cty1.cty_radius) as distance1,
								CalcDistance(cty1.cty_lat, cty1.cty_long, cty2.cty_lat, cty2.cty_long) AS estdist, ";
			$havingFilter	 = ' AND distance1 >= estdist ';
		}
		else
		{
			$qry			 = ":maxDistance as distance1, ";
			$havingFilter	 = ' AND distance > 0 ';
		}
		$condQueryStr = "";
		if (trim($queryStr) != "")
		{
			$sqlParams['queryStr']	 = trim($queryStr);
			DBUtil::getLikeStatement(trim($queryStr), $bindString0, $params1, '');
			DBUtil::getLikeStatement(trim($queryStr), $bindString1, $params2);
			DBUtil::getLikeStatement(trim($queryStr), $bindString3, $params3, '"', '"');
			$qry					 .= "IF(cty2.cty_full_name LIKE $bindString0,1,0) AS startRank, MATCH(cty2.cty_display_name) AGAINST ($bindString3 IN NATURAL LANGUAGE MODE) AS score, ";
			$condQueryStr			 = " AND cty2.cty_full_name LIKE  $bindString1";
			$sqlParams				 = array_merge($sqlParams, $params1, $params2, $params3);
		}
		else
		{
			$qry .= "0  AS startRank, 0  AS score, ";
		}
		$toCityCond = "";
		if ($selectedCity !== "")
		{
			$qry						 .= " IF(cty2.cty_id=:selectedCity,1,0) as selectRank, ";
			$toCityCond					 .= " OR cty2.cty_id=:selectedCity";
			$sqlParams['selectedCity']	 = $selectedCity;
		}
		else
		{
			$qry .= "0 as selectRank, ";
		}
		if ($fromCity == "")
		{
			return [];
		}
		$excludeFromCty = " AND cty2.cty_id <> :fromCity ";
		if ($this->isRedirectedBooking == 1)
		{
			$excludeFromCty = "";
		}
		$sql		 = "SELECT DISTINCT cty2.cty_id, $qry
							Floor(CalcDistance(cty1.cty_lat, cty1.cty_long, cty2.cty_lat, cty2.cty_long)/500) as distanceRank,
							cty2.cty_full_name as cty_name, cty2.cty_state_id
						FROM cities  cty1
						INNER JOIN cities cty2 ON cty1.cty_id <> cty2.cty_id AND cty1.cty_id = :fromCity AND cty2.cty_active=1 AND cty2.cty_service_active = 1
						INNER JOIN topcitiesstats ON ctyId=cty2.cty_id
						WHERE  ( 1  $excludeFromCty {$toCityCond})
						$condQueryStr AND	CalcDistance(cty1.cty_lat, cty1.cty_long, cty2.cty_lat, cty2.cty_long)<:maxDistance
						ORDER BY selectRank DESC, startRank DESC, distanceRank ASC, rank ASC,  score DESC, cty2.cty_full_name $limit";
		$recordSet	 = DBUtil::query($sql, DBUtil::SDB2(), $sqlParams, 24 * 60 * 60, "Cities");
		return $recordSet;
	}

	public function getNearestcityList($source, $queryStr, $limit, $status = 0)
	{
		$response = Cities::model()->getJSONNearestAll($source, 2500, false, $queryStr, $limit, $status);
		return $response;
	}

	public static function getListbyState($state, $selectedCity = '', $queryStr = '', $limit = 'LIMIT 0,10')
	{
		if ($state == "")
		{
			return [];
		}
		$sqlParams = ['state' => $state];

		$condQueryStr = "";
		if (trim($queryStr) != "")
		{
//			$sqlParams['queryStr']	 = trim($queryStr);
			DBUtil::getLikeStatement(trim($queryStr), $bindString0, $params1, '');
			DBUtil::getLikeStatement(trim($queryStr), $bindString1, $params2);
			DBUtil::getLikeStatement(trim($queryStr), $bindString3, $params3, '"', '"');
			$qry			 .= "IF(cty.cty_full_name LIKE $bindString0,1,0) AS startRank,  
  									MATCH(cty.cty_display_name) AGAINST ($bindString3 IN NATURAL LANGUAGE MODE) AS score, ";
			$condQueryStr	 = " AND cty.cty_full_name LIKE  $bindString1";
			$sqlParams		 = array_merge($sqlParams, $params1, $params2, $params3);
		}
		else
		{
			$qry .= " 0 AS startRank, 0 AS score, ";
		}
		$cityCond = "";

		if ($selectedCity !== "")
		{
			$qry						 .= " IF(cty.cty_id=:selectedCity,1,0) as selectRank, ";
			$cityCond					 .= " AND cty.cty_id=:selectedCity";
			$sqlParams['selectedCity']	 = $selectedCity;
		}
		else
		{
			$qry .= " 0 as selectRank, ";
		}

		$sql = "SELECT DISTINCT cty.cty_id, $qry cty.cty_full_name as cty_name, cty.cty_state_id
					FROM cities  cty 
					INNER JOIN topcitiesstats ON ctyId=cty.cty_id
					WHERE ( cty.cty_active=1 AND cty.cty_service_active = 1 {$cityCond})
					AND cty.cty_state_id=:state $condQueryStr  
					ORDER BY selectRank DESC, startRank DESC, rank ASC, score DESC, cty.cty_full_name $limit";

		$recordSet = DBUtil::query($sql, DBUtil::SDB2(), $sqlParams, 24 * 60 * 60, "Cities");
		return $recordSet;
	}

	public static function getJSONListbyState($state, $selectedCity, $queryStr = '')
	{
		$recordSet	 = Cities::getListbyState($state, $selectedCity, $queryStr);
		$arrCities	 = array();
		foreach ($recordSet as $record)
		{
			$arrCities[] = array("id" => $record['cty_id'], "text" => $record['cty_name']);
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	public function getJSONNearestAll($fromCity = 0, $maxDistance = 1000, $forAirport = false, $queryStr = "", $limit = "", $status = 0, $selectedVal = "")
	{
		$recordSet	 = $this->getNearestCitiesDistanceListbyId($fromCity, $maxDistance, $forAirport, $queryStr, $limit, $selectedVal);
		$arrCities	 = array();
		foreach ($recordSet as $record)
		{
			$arrCities[] = array("id" => $record['cty_id'], "text" => $record['cty_name']);
		}

		if ($status == 1)
		{
			$data = $arrCities;
		}
		else
		{
			$data = CJSON::encode($arrCities);
		}

		return $data;
	}

	public function getSourceList($last_updated)
	{
		if ($last_updated)
		{
			$where .= "where `cty_modified_on` > '$last_updated'";
		}
		else
		{
			$where .= "";
		}
		$sql		 = "SELECT `cty_id` as cityid,cty_id, CONCAT(`cty_name`, ', ', `stt_name`) as cityname,cty_name,stt_name, `cty_is_airport`, `cty_has_airport`,`cty_is_poi`, `cty_radius`, `cty_bounds`, `cty_alias_name`, `cty_modified_on` as last_update, `cty_service_active` as service_active, `cty_lat` as latitude, `cty_long` as longitude, `cty_garage_address` as garage_address, `cty_active` as  city_active, IF(`cty_id` IN (SELECT `cty_id`  FROM `cities` `t` LEFT OUTER JOIN `states` `ctyState` ON (`t`.`cty_state_id`=`ctyState`.`stt_id`) WHERE ((t.cty_active=1) AND (((cty_service_active=1) AND (cty_active=1)) AND (cty_id IN (SELECT rut_from_city_id FROM route WHERE rut_active=1
                AND rut_id IN (SELECT rte_route_id FROM rate WHERE rte_status=1))
                OR cty_id IN (SELECT cty_id FROM (SELECT cty_id1 as cty_id, cty_name1 as cty_name, MIN(distance) as distance FROM (
                SELECT c.cty_id, c.cty_name, c1.cty_id as cty_id1, c1.cty_name as cty_name1,
                SQRT( POW(69.1 * (c1.cty_lat - c.cty_lat), 2) + POW(69.1 * (c.cty_long - c1.cty_long) * COS(c1.cty_lat / 57.3), 2)) AS distance
                FROM cities c
                INNER JOIN cities c1 ON c.cty_active=c1.cty_active AND c.cty_id<>c1.cty_id
                WHERE c.cty_active=1 AND c.cty_id IN
                (SELECT rut_from_city_id FROM route WHERE rut_active=1 AND rut_id IN (
                SELECT rte_route_id FROM rate WHERE rte_status=1)) AND c1.cty_id NOT IN (SELECT rut_from_city_id FROM route WHERE rut_active=1 AND rut_id IN (
                SELECT rte_route_id FROM rate WHERE rte_status=1)) HAVING distance < 15
                ) a GROUP BY cty_id1 ORDER BY MIN(distance) ASC) a)
                OR cty_id IN (SELECT DISTINCT c2.cty_id FROM cities c1 LEFT JOIN contact ON contact.ctt_city = c1.cty_id
                INNER JOIN vendors ON vendors.vnd_contact_id = contact.ctt_id AND vnd_active=1
                INNER JOIN cities c2 ON SQRT( POW(69.1 * (c1.cty_lat - c2.cty_lat), 2) + POW(69.1 * (c2.cty_long - c1.cty_long) * COS(c1.cty_lat / 57.3), 2)) < 25 AND c2.cty_service_active=1)
                ))) ORDER BY cty_name), 1, 0) as is_from_cty from cities
                left join states on `cty_state_id`= `stt_id` $where
                ";
		$recordSet	 = DBUtil::query($sql, DBUtil::SDB());
		return $recordSet;
	}

	public function getSource()
	{
		$sql		 = "SELECT cty_id , stt_name, cty_name as cty_display_name FROM city_list WHERE `stt_active` = '1'";
		$recordSet	 = DBUtil::query($sql, DBUtil::SDB());
		return $recordSet;
	}

	public function getDestinationCityList($fromCity, $maxDistance = 500)
	{
		if ($fromCity == "")
		{
			return [];
		}
		$sql		 = "select GROUP_CONCAT(cty_id) as city_list from (SELECT cty2.cty_id,
				( 6371 *
						acos(
							cos( radians( cty1.cty_lat ) ) *
							cos( radians( cty2.cty_lat ) ) *
							cos( radians( cty2.cty_long ) - radians( cty1.cty_long )) +
							sin(radians(cty1.cty_lat)) *
							sin(radians(cty2.cty_lat)) )  )
				 AS distance from cities cty1
				 join cities cty2 ON cty1.cty_id <> cty2.cty_id
				 join states stt ON cty2.cty_state_id = stt.stt_id
				 WHERE cty1.cty_id = $fromCity
					 AND cty2.cty_id <> $fromCity
					AND cty2.cty_service_active = 1 AND cty2.cty_active = 1
					having distance < $maxDistance
					order by cty2.cty_name) a";
		$recordSet	 = DBUtil::queryAll($sql);
		return $recordSet;
	}

	public function getCodeByCityId($id)
	{
		return $this->model()->findByPk($id)->cty_code;
	}

	function getLastModified($format = 'YmdHis')
	{
		$sql	 = "SELECT max(cty_modified_on) from cities";
		$val	 = DBUtil::command($sql)->queryScalar();
		$date	 = new DateTime($val);
		return $date->format($format);
	}

	public function getNearestZoneId($maxdistance = 30)
	{
		$zoneId	 = 0;
		$city	 = $this->cty_id;
		$res	 = Zones::model()->getNearestZonebyCity($city, $maxdistance);
		if ($res)
		{
			$zoneId = $res['zon_id'];
		}
		return $zoneId;
	}

	public function getNearestZonePriceRuleId()
	{
		$city = $this->cty_id;

		$sql		 = "SELECT   c1.cty_id
                        , c1.cty_name
                        , zones.zon_id
                        , zones.zon_name
                        , zones.zon_price_rule
                        , min(SQRT(POW(69.1 * (c1.cty_lat - zones.zon_lat), 2) + POW(69.1 * (zones.zon_long - c1.cty_long) * COS(c1.cty_lat / 57.3), 2))) AS zn_distance
               FROM     cities c1
                        INNER JOIN zone_cities ON zone_cities.zct_cty_id = c1.cty_id
                        INNER JOIN zones ON zone_cities.zct_zon_id = zones.zon_id
                        WHERE c1.cty_id = $city AND zon_price_rule>0
               GROUP BY c1.cty_id";
		$zoneData	 = DBUtil::queryRow($sql);
		return $zoneData['zon_price_rule'];
	}

	public function getAirportCities($query = '', $select = true)
	{
		$query = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1);
		($query == null || $query == "") ? DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '"') : DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '*"');
		if ($select)
		{
			$selects = "IF(cty_display_name LIKE $bindString0,1,0) AS startRank,MATCH (cty_display_name) AGAINST ($bindString1 IN BOOLEAN MODE)  AS score,cty_id as id,cty_display_name as text,cty_radius as radius,cty_lat as lat,cty_long as lng";
		}
		else
		{
			$selects = "IF(cty_display_name LIKE $bindString0,1,0) AS startRank, MATCH (cty_display_name) AGAINST ($bindString1 IN BOOLEAN MODE)  AS score,cty_id as cty_id,cty_display_name as cty_name,cty_radius as radius, cty_lat as lat, cty_long as lng";
		}
		$qry = '';
		if ($query != '')
		{
			$qry .= " AND cty_name LIKE $bindString0";
		}
		$sql = "SELECT $selects	FROM cities  WHERE 1 AND cty_active=1 AND cty_is_airport=1 AND cty_service_active=1  $qry  ORDER BY score DESC,startRank DESC,cty_display_name ASC  ";
		return DBUtil::queryAll($sql, DBUtil::SDB(), array_merge($params1, $params2));
	}

	public function getRailwayBusCities($query = '', $select = true)
	{
		$query = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1);
		($query == null || $query == "") ? DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '"') : DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '*"');
		if ($select)
		{
			$selects = "IF(cty_display_name LIKE $bindString0,1,0) AS startRank,MATCH (cty_display_name) AGAINST ($bindString1 IN BOOLEAN MODE)  AS score,cty_id as id,cty_display_name as text,cty_radius as radius,cty_lat as lat,cty_long as lng";
		}
		else
		{
			$selects = "IF(cty_display_name LIKE $bindString0,1,0) AS startRank, MATCH (cty_display_name) AGAINST ($bindString1 IN BOOLEAN MODE)  AS score,cty_id as cty_id,cty_display_name as cty_name,cty_radius as radius, cty_lat as lat, cty_long as lng";
		}
		$qry = '';
		if ($query != '')
		{
			$qry .= " AND cty_name LIKE $bindString0";
		}
		$sql = "SELECT $selects	FROM cities  WHERE 1 AND cty_active=1 AND (cty_poi_type=1 OR cty_poi_type=2) AND cty_service_active=1  $qry  ORDER BY score DESC,startRank DESC,cty_display_name ASC  ";
		return DBUtil::queryAll($sql, DBUtil::SDB(), array_merge($params1, $params2));
	}

	public function getJSONAirportCitiesAll($query = '', $status = 0)
	{
		$recordSet	 = $this->getAirportCities($query);
		$data		 = CJSON::encode($recordSet);
		if ($status == 1)
		{
			$data = $recordSet;
		}
		else
		{
			$data = $data;
		}
		return $data;
	}

	public function getJSONRailwayBusCitiesAll($query = '', $status = 0)
	{
		$recordSet	 = $this->getRailwayBusCities($query);
		$data		 = CJSON::encode($recordSet);
		if ($status == 1)
		{
			$data = $recordSet;
		}
		else
		{
			$data = $data;
		}
		return $data;
	}

	public function getPOI($query = '', $select = true)
	{
		$query = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString, $params);
		if ($query != '')
		{
			$qry = " AND cty_name LIKE $bindString";
		}
		if ($select)
		{
			$selects = " city.`cty_id` as id, city.cty_display_name as text ";
		}
		else
		{
			$selects = " city.`cty_id` as cty_id, city.cty_display_name as cty_name ";
		}
		$sql = "SELECT $selects FROM `cities` city WHERE city.cty_active=1 AND cty_is_poi=1 AND cty_service_active=1 $qry ORDER BY cty_name";
		return DBUtil::queryAll($sql, DBUtil::SDB(), $params);
	}

	public function getJSONPOIAll($query = '')
	{
		$recordSet	 = $this->getAirportCities($query);
		$data		 = CJSON::encode($recordSet);
		return $data;
	}

	public function getExcludedCabTypes($cty_id)
	{
		$model			 = $this->getDetails($cty_id);
		$cabTypeArray	 = [];
		if ($model->cty_excluded_cabtypes != '')
		{
			$cabTypeArray = explode(',', $model->cty_excluded_cabtypes);
		}
		return $cabTypeArray;
	}

	public static function getIdsByRegion($region)
	{
		$key	 = "getIdsByRegion_$region";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$sql	 = "SELECT GROUP_CONCAT(cty_id) as ids FROM cities 	INNER JOIN states ON cty_state_id=stt_id AND cty_active=1 AND stt_active='1' AND stt_zone IN ($region)";
		$data	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		Yii::app()->cache->set($key, $data, 60 * 60 * 24 * 1, new CacheDependency("Routes"));
		result:
		return $data;
	}

	public function getTopCitiesByNorthRegion()
	{
		$key	 = "getTopCitiesByNorthRegion";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}

		$sql	 = "SELECT cty.cty_name as ctyName,cty.cty_alias_path as ctyAliasPath,cty.cty_id as ctyId,COUNT(DISTINCT b1.bkg_id) as total_booking_count

                            FROM cities cty
                            JOIN states stt ON stt.stt_id = cty.cty_state_id
                            JOIN booking b1 on b1.bkg_from_city_id = cty.cty_id AND b1.bkg_status IN (6,7)
                            AND b1.bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 3 MONTH) AND NOW()
                            WHERE cty.cty_active = 1 AND stt.stt_zone = 1 AND cty.cty_is_airport=0
                            GROUP BY cty.cty_id ORDER BY total_booking_count DESC LIMIT 10";
		$data	 = DBUtil::queryAll($sql);
		Yii::app()->cache->set($key, $data, 60 * 60 * 24 * 10, new CacheDependency("Routes"));
		result:
		return $data;
	}

	public function getTopCitiesByWestRegion()
	{
		$key	 = "getTopCitiesByWestRegion";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$sql	 = "SELECT cty.cty_name as ctyName,cty.cty_alias_path as ctyAliasPath,cty.cty_id as ctyId,COUNT(DISTINCT b1.bkg_id) as total_booking_count

                            FROM cities cty
                            JOIN states stt ON stt.stt_id = cty.cty_state_id
                            JOIN booking b1 on b1.bkg_from_city_id = cty.cty_id AND b1.bkg_status IN (6,7)
                            AND b1.bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 3 MONTH) AND NOW()
                            WHERE cty.cty_active = 1 AND stt.stt_zone In (2,3) AND cty.cty_is_airport=0
                            GROUP BY cty.cty_id ORDER BY total_booking_count DESC LIMIT 10";
		$data	 = DBUtil::queryAll($sql);
		Yii::app()->cache->set($key, $data, 60 * 60 * 24 * 10, new CacheDependency("Routes"));
		result:
		return $data;
	}

	public function getTopCitiesBySouthRegion()
	{
		$key	 = "getTopCitiesBySouthRegion";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$sql	 = "SELECT cty.cty_name as ctyName,cty.cty_alias_path as ctyAliasPath,cty.cty_id as ctyId,COUNT(DISTINCT b1.bkg_id) as total_booking_count
					FROM cities cty
					JOIN states stt ON stt.stt_id = cty.cty_state_id
					JOIN booking b1 on b1.bkg_from_city_id = cty.cty_id AND b1.bkg_status IN (6,7)
					AND b1.bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 3 MONTH) AND NOW()
					WHERE cty.cty_active = 1 AND stt.stt_zone IN (4,7,8) AND cty.cty_is_airport=0
					GROUP BY cty.cty_id ORDER BY total_booking_count DESC LIMIT 10";
		$data	 = DBUtil::queryAll($sql);
		Yii::app()->cache->set($key, $data, 60 * 60 * 24 * 10, new CacheDependency("Routes"));
		result:
		return $data;
	}

	public function getTopCitiesByEastRegion()
	{
		$key	 = "getTopCitiesByEastRegion";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$sql	 = "SELECT cty.cty_name as ctyName,cty.cty_alias_path as ctyAliasPath,cty.cty_id as ctyId,COUNT(DISTINCT b1.bkg_id) as total_booking_count
					FROM cities cty
					JOIN states stt ON stt.stt_id = cty.cty_state_id
					JOIN booking b1 on b1.bkg_from_city_id = cty.cty_id AND b1.bkg_status IN (6,7)
					AND b1.bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 3 MONTH) AND NOW()
					WHERE cty.cty_active = 1 AND stt.stt_zone IN (5,6) AND cty.cty_is_airport=0
					GROUP BY cty.cty_id ORDER BY total_booking_count DESC LIMIT 10";
		$data	 = DBUtil::queryAll($sql);
		Yii::app()->cache->set($key, $data, 60 * 60 * 24 * 10, new CacheDependency("Routes"));
		result:
		return $data;
	}

	public function getTopCitiesByRegion($region, $limit = 5)
	{
		$key	 = "getTopCitiesByRegion_" . $region;
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}

		$sql = "SELECT cities.cty_name as city,cities.cty_alias_path,COUNT(DISTINCT booking.bkg_id) as count_booking
                    FROM `cities`
                    INNER JOIN `booking` ON cities.cty_id=booking.bkg_from_city_id AND booking.bkg_active=1 
						AND booking.bkg_create_date > DATE_SUB(NOW(), INTERVAL 365 DAY) AND booking.bkg_status IN (6,7) 
                    INNER JOIN `states` ON states.stt_id=cities.cty_state_id AND states.stt_zone IN ({$region}) 
                    WHERE cities.cty_is_airport=0 AND cty_active = 1 AND cty_service_active = 1 
                    GROUP BY booking.bkg_from_city_id
                    ORDER BY count_booking DESC
                    LIMIT 0, {$limit}";

		$data = DBUtil::queryAll($sql);

		Yii::app()->cache->set($key, $data, 60 * 60 * 24 * 10, new CacheDependency("Routes"));

		result:
		return $data;
	}

	public static function getTopCityByRegion($limit = 5, $isAirport = 0, $regionIds = '1,2,3,4,5,6,7')
	{
		$sql = "SELECT * FROM (
					SELECT cty.cty_id as ctyId, cty.cty_name as city, cty.cty_alias_path, COUNT(DISTINCT bkg.bkg_id) as cnt, stt.stt_id stateid,
					stt.stt_zone, ROW_NUMBER() OVER (PARTITION BY stt.stt_zone ORDER BY cnt DESC) AS rank
					FROM cities cty
					INNER JOIN booking bkg ON cty.cty_id=bkg.bkg_from_city_id AND bkg.bkg_active=1 
						AND bkg.bkg_create_date > DATE_SUB(NOW(), INTERVAL 365 DAY) AND bkg.bkg_status IN (6,7) 
					INNER JOIN states stt ON stt.stt_id=cty.cty_state_id AND stt.stt_zone IN ({$regionIds}) 
					WHERE cty.cty_is_airport = {$isAirport} AND cty.cty_active = 1 AND cty.cty_service_active = 1 
					GROUP BY bkg.bkg_from_city_id
				) a WHERE rank <= {$limit}";

		$data = DBUtil::query($sql, DBUtil::SDB(), [], (60 * 60 * 24 * 30), new CacheDependency("Routes"));

		return $data;
	}

	public function getTopCitiesByKm($city_id, $km = 200, $limit = 25)
	{
		$key	 = "getTopCitiesByKm_" . $city_id . "_" . $km . "_" . $limit;
		$result	 = Yii::app()->cache->get($key);
		if ($result)
		{
			goto result;
		}
		$sql	 = "SELECT cities.cty_name as city,route.rut_estm_distance as distance,cities.cty_alias_path
                            FROM `route`
                            INNER JOIN `cities` ON cities.cty_id=route.rut_to_city_id AND cities.cty_is_airport=0
                            WHERE route.rut_from_city_id=$city_id
                            AND route.rut_estm_distance <= $km
                            AND route.rut_from_city_id<>route.rut_to_city_id
                            AND cities.cty_alias_path IS NOT NULL
							AND route.rut_estm_distance > 0
                            AND cities.cty_active=1
                            ORDER BY route.rut_estm_distance
                            LIMIT 0,$limit";
		$result	 = DBUtil::queryAll($sql);
		Yii::app()->cache->set($key, $result, 60 * 60 * 24 * 30, new CacheDependency("topCities"));
		result:
		return $result;
	}

	public function getTopCitiesByAllRegion()
	{
		// North
		$northCities	 = $this->getTopCitiesByRegion(1);
		// South
		$southCities	 = $this->getTopCitiesByRegion(4, 7);
		// east
		$eastCities		 = $this->getTopCitiesByRegion(5, 6);
		// west
		$westCities		 = $this->getTopCitiesByRegion(2);
		// central
		$centralCities	 = $this->getTopCitiesByRegion(3);

		return array_merge($northCities, $westCities, $centralCities, $eastCities, $southCities);
	}

	public function popularCities($city = '')
	{
		$sql = "SELECT cities.cty_id,cities.cty_name,cities.cty_population,cty_alias_path,countBooking
							FROM `cities` INNER JOIN `city_list` ON city_list.cty_id=cities.cty_id
							INNER JOIN
							(
								SELECT COUNT(booking.bkg_id) as countBooking, booking.bkg_from_city_id
								FROM `booking` WHERE booking.bkg_active=1 AND booking.bkg_status IN (6,7)
								GROUP BY booking.bkg_from_city_id  HAVING (countBooking>10)
							)book ON book.bkg_from_city_id=cities.cty_id
							WHERE cities.cty_active=1
							AND cities.cty_is_airport=0
							ORDER BY cities.cty_population DESC LIMIT 0,100";
		return DBUtil::queryAll($sql);
	}

	public function fetchActiveList()
	{
		$sql = "SELECT * FROM `cities` WHERE cities.cty_active=1 AND cities.cty_is_airport=0 AND cities.cty_alias_path<>''";
		return $sql;
	}

	public function getListWithAlias($start, $limit = 5000)
	{
		if ($start !== null)
		{
			$qry = " LIMIT $start, $limit";
		}
		$sql		 = $this->fetchActiveList() . " ORDER BY cities.cty_id ASC $qry ";
		$cdb		 = Yii::app()->db->createCommand();
		$cdb->text	 = $sql;
		$dataReader	 = $cdb->query();
		return $dataReader;
	}

	/**
	 *
	 * @param integer $cityId
	 * @param integer $status
	 * @return Array
	 */
	public static function getCityBoundByLatLong($cityId, $status = 0)
	{

		if (!$cityId)
		{
			throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql		 = "SELECT cty_id,cty_lat,cty_long,cty_bounds,cty_radius FROM cities WHERE cty_id='" . $cityId . "'";
		$result		 = DBUtil::query($sql, DBUtil::SDB());
		$cityResult	 = [];
		foreach ($result as $res)
		{
			$cityResult		 = [
				'cityId'	 => (int) $res['cty_id'],
				'cityLat'	 => $res['cty_lat'],
				'cityLong'	 => $res['cty_long']
			];
			$cityBoundObj	 = json_decode($res['cty_bounds']);
			$northeastLat	 = ($cityBoundObj->northeast->lat != '') ? $cityBoundObj->northeast->lat : $res['cty_lat'];
			$northeastLng	 = ($cityBoundObj->northeast->lng != '') ? $cityBoundObj->northeast->lng : $res['cty_long'];
			$southwestLat	 = ($cityBoundObj->southwest->lat != '') ? $cityBoundObj->southwest->lat : $res['cty_lat'];
			$southwestLng	 = ($cityBoundObj->southwest->lng != '') ? $cityBoundObj->southwest->lng : $res['cty_long'];

			$cityResult['northeast']['lat']	 = strval($northeastLat + 0.05);
			$cityResult['northeast']['lng']	 = strval($northeastLng + 0.05);
			$cityResult['southwest']['lat']	 = strval($southwestLat - 0.05);
			$cityResult['southwest']['lng']	 = strval($southwestLng - 0.05);
		}
		if ($status == 1)
		{
			return $cityResult;
		}
		else
		{
			return json_encode($cityResult);
		}
	}

	public function getCtyLatLongWithBound($city, $status = 0)
	{
		$cityArr		 = explode(',', $city);
		$sql			 = "SELECT cty_id,cty_lat,cty_long,cty_bounds,cty_radius FROM cities WHERE cty_id IN (" . $city . ")";
		$resultArr		 = DBUtil::queryAll($sql);
		$cityResultArr	 = [];
		$cityResult		 = [];
		foreach ($resultArr as $results)
		{
			$cityBoundArr = json_decode($results['cty_bounds']);
			if (!count($cityBoundArr) > 0)
			{
				$cityBoundArr['northeast']['lat']	 = $results['cty_lat'] + 0.05;
				$cityBoundArr['northeast']['lng']	 = $results['cty_long'] + 0.05;
				$cityBoundArr['southwest']['lat']	 = $results['cty_lat'] - 0.05;
				$cityBoundArr['southwest']['lng']	 = $results['cty_long'] - 0.05;
			}
			$cityResult[$results['cty_id']] = ['success' => true, 'cityId' => $results['cty_id'], 'cityLat' => $results['cty_lat'], 'cityLong' => $results['cty_long'], 'cityBounds' => $cityBoundArr];
		}
		foreach ($cityArr as $cityVal)
		{
			$cityResultArr[] = ($cityResult[$cityVal]) ? $cityResult[$cityVal] : ['success' => false, 'cityId' => $cityVal];
		}
		if ($status == 1)
		{
			return $cityResultArr;
		}
		else
		{
			return json_encode($cityResultArr);
		}
	}

	public function getCtyIdWithBound($cLat, $cLong, $isAirport)
	{
		$resultArr	 = [];
		$distance	 = 1;
		$scLat		 = round($cLat, 5);
		$scLong		 = round($cLong, 4);
		if ($scLat == round(13.201096, 5) && $scLong == round(77.66114189999996, 4))
		{
			$distance = 5;
		}
		if ($isAirport == 0)
		{
			$sql		 = "SELECT
					cty_id,(
					  3959 * acos (
						cos ( radians($cLat) )
						* cos( radians( cty_lat ) )
						* cos( radians( cty_long ) - radians($cLong) )
						+ sin ( radians($cLat) )
						* sin( radians( cty_lat ) )
					  )
					) AS distance,
					cty_bounds,
					cty_lat,
					cty_long
				  FROM cities
				  WHERE
				  cty_service_active =1
				  AND cty_active = 1
				  AND cty_is_airport=1
				  HAVING distance < $distance
				  ORDER BY distance limit 0,5";
			$resultArr	 = DBUtil::queryAll($sql, DBUtil::SDB());
		}
		if (count($resultArr) == 0)
		{
			$sql = "SELECT
							cty_id,(
							  3959 * acos (
								cos ( radians($cLat) )
								* cos( radians( cty_lat ) )
								* cos( radians( cty_long ) - radians($cLong) )
								+ sin ( radians($cLat) )
								* sin( radians( cty_lat ) )
							  )
							) AS distance,
							cty_bounds,
							cty_lat,
							cty_long
						  FROM cities
						  WHERE
						  cty_service_active =1
						  AND cty_active = 1
				  AND cty_is_airport=$isAirport
						  HAVING distance < 25
						  ORDER BY distance limit 0,5";

			$resultArr = DBUtil::queryAll($sql, DBUtil::SDB());
		}
		else
		{
			$isAirport = 1;
		}
		$ctyId = $resultArr[0]['cty_id'];
		foreach ($resultArr as $results)
		{
			$cityBoundArr = CJSON::decode($results['cty_bounds']);
			if (!count($cityBoundArr) > 0)
			{
				$cityBoundArr['northeast']['lat']	 = $results['cty_lat'] + 0.05;
				$cityBoundArr['northeast']['lng']	 = $results['cty_long'] + 0.05;
				$cityBoundArr['southwest']['lat']	 = $results['cty_lat'] - 0.05;
				$cityBoundArr['southwest']['lng']	 = $results['cty_long'] - 0.05;
			}
			$boundStatus = Filter::checkLatLongBound($cLat, $cLong, $cityBoundArr['northeast']['lat'], $cityBoundArr['northeast']['lng'], $cityBoundArr['southwest']['lat'], $cityBoundArr['southwest']['lng']);
			if ($boundStatus)
			{
				$ctyId = $results['cty_id'];
				break;
			}
		}
		return ['ctyId' => $ctyId, 'isAirport' => $isAirport, 'ctyLat' => $cLat, 'ctyLong' => $cLong, 'grageAdd' => ''];
	}

	/** @param \Stub\common\Place $placeObj */
	public static function getByPlace($placeObj)
	{
		if ($placeObj->place_id != '' && ($placeObj->isLocality() || $placeObj->isAirport()))
		{
			$model = self::getByPlaceId($placeObj);
			if ($model)
			{
				goto result;
			}
		}

		$model = self::getByGeoBounds($placeObj, 25);
		if ($model)
		{
			$nearestModel1 = clone $model;
			if ($model && $model->is_partial == 0)
			{
				goto result;
			}
		}

		$model = self::getByNearestBound($placeObj, 25);
		if ($model)
		{
			$nearestModel = clone $model;
			if ($model && $model->is_partial == 0)
			{
				goto result;
			}
		}
		$ltgModel = LatLong::model()->getDetailsByPlace($placeObj);
		if ($ltgModel)
		{
			$model = Cities::model()->findByPk($ltgModel->ltg_city_id);
			goto result;
		}

		$model = self::getByAddress($placeObj);
		if ($model)
		{
			goto result;
		}
		if ($nearestModel)
		{
			$model = $nearestModel;
			goto result;
		}
		if ($nearestModel1)
		{
			$model = $nearestModel1;
			goto result;
		}

		$model = self::addByPlaceObject($placeObj);
		if (!$model)
		{
			throw new Exception("Location not supported. " . json_encode($placeObj), ReturnSet::ERROR_FAILED);
		}
		Logger::profile("Cities::getByPlace Done");
		result:
		return $model;
	}

	/** @param \Stub\common\Place $placeObj
	 * @return Cities
	 */
	public static function getByGeoBounds($placeObj, $distance = 20, $margin = 0.25, $retry = true)
	{
		$model	 = false;
		$bounds	 = $placeObj->bounds;
		if (is_object($placeObj->bounds))
		{
			$bounds = json_encode($placeObj->bounds);
		}
		$params	 = [
			"lat"		 => $placeObj->coordinates->latitude,
			"long"		 => $placeObj->coordinates->longitude,
			"bounds"	 => $bounds,
			"distance"	 => $distance,
			"margin"	 => $margin
		];
		$sql	 = "SELECT c.cty_id, c.cty_geo_id, c.cty_display_name, gdt_name, gc.cty_display_name as gcName,   c.cty_is_airport,
						CalcDistance(c.cty_lat, c.cty_long, :lat, :long) AS distance,
						CalculateArea(c.cty_bounds) as cityArea,
						checkBounds(c.cty_bounds, :lat, :long, 0) as withinBounds,
						IF(CalcDistance(c.cty_lat, c.cty_long, :lat, :long) < :distance,1,0) as withinLimits, c.cty_service_active,
						gdt_area, gc.cty_id as gcCityId,
						IF(gc.cty_id IS NULL, NULL, CalcDistance(gc.cty_lat, gc.cty_long, :lat, :long)) AS gcDistance,
						IF(gc.cty_id IS NULL, NULL, CalculateArea(gc.cty_bounds)) as gcCityArea,
						IF(gc.cty_id IS NULL, NULL, checkBounds(gc.cty_bounds, :lat, :long, 0)) as gcWithinBounds,
						IF(gc.cty_id IS NULL, NULL, IF(CalcDistance(gc.cty_lat, gc.cty_long, :lat, :long) < :distance,1,0)) as gcWithinLimits,
						gc.cty_service_active as gcServiceActive,
						ST_CONTAINS(gdt_polygon, POINT(:long, :lat)) as withinGDTBounds,
						IF(c.cty_is_airport=1 AND checkBounds(c.cty_bounds, :lat, :long, 0), 1, 0)  as isAirport
					FROM geo_data
					INNER JOIN cities c ON c.cty_geo_id=gdt_id AND c.cty_active=1 AND c.cty_service_active=1
					LEFT JOIN cities gc ON gc.cty_id=gdt_city_id

					WHERE c.cty_lat BETWEEN (:lat - :margin) AND (:lat + :margin)
						AND c.cty_long BETWEEN (:long - :margin) AND (:long + :margin)
					HAVING (withinGDTBounds=1 OR withinBounds=1)
					ORDER BY isAirport DESC, (withinLimits + withinBounds + withinGDTBounds) DESC,  withinBounds DESC, withinLimits DESC, distance";

		$results = DBUtil::query($sql, DBUtil::SDB(), $params);

		if ($results->getRowCount() == 0)
		{
			if ($margin >= 0.5 || $retry == false)
			{
				goto model;
			}
			return self::getByGeoBounds($placeObj, $distance, 0.5, false);
		}

		foreach ($results as $row)
		{
			if ($row["cityArea"] < 5 && $row["withinBounds"] != 1 && $row["gcCityId"] != null && $row["withinGDTBounds"] == 1 && $row["cty_is_airport"] != 1 && $row["gcCityId"] != null && ($row["gdt_area"] < 1000 || $row["gcWithinBounds"] == 1 || $row["gcWithinLimits"] == 1))
			{
				$model				 = Cities::model()->findByPk($row["gcCityId"]);
				$model->is_partial	 = 0;
				break;
			}

			$model1				 = Cities::model()->findByPk($row["cty_id"]);
			$model1->is_partial	 = 1;
			if ($model == null)
			{
				$firstRecord = $row;
				$model		 = clone $model1;
			}

			if ($row["withinBounds"])
			{
				$model1->is_partial	 = 0;
				$model				 = clone $model1;
				break;
			}
		}

		if ($model->is_partial == 1 && $firstRecord["gcCityId"] != null && ($firstRecord["withinGDTBounds"] == 1 && ($firstRecord["gdt_area"] < 2000 || $firstRecord["gcWithinLimits"] == 1)))
		{
			$model				 = Cities::model()->findByPk($row["gcCityId"]);
			$model->is_partial	 = 0;
		}

		model:
		return $model;
	}

	/** @param \Stub\common\Place $placeObj
	 * @return Cities
	 */
	public static function getByNearestBound($placeObj, $distance = 25)
	{
		$model	 = false;
		$find	 = false;
		$sql	 = "SELECT
						cty_id, CalcDistance(cty_lat, cty_long, :lat, :long) AS distance,
						IF(cty_types LIKE '%administrative_area_level_2%',2, IF(cty_types LIKE '%sublocality%',1,0)) as typeScore
						FROM cities
						WHERE
						cty_lat BETWEEN (:lat - 0.5) AND (:lat + 0.5) AND
						cty_long BETWEEN (:long - 0.5) AND (:long + 0.5)
							AND cty_active = 1 AND checkBounds(cty_bounds, :lat, :long, 0.03)
						HAVING distance < :distance
						ORDER BY typeScore ASC, cty_service_active DESC, distance ASC limit 0,50";
		$params	 = [
			"lat"		 => $placeObj->coordinates->latitude + 0,
			"long"		 => $placeObj->coordinates->longitude + 0,
			"distance"	 => $distance
		];
		$results = DBUtil::queryAll($sql, DBUtil::MDB(), $params);
		if (count($results) == 0)
		{
			goto model;
		}

		$model				 = Cities::model()->findByPk($results[0]["cty_id"]);
		$model->is_partial	 = 1;
		$model->distance	 = $results[0]["distance"];
		$boundModel			 = [];

		foreach ($results as $row)
		{
			$result			 = Cities::model()->findByPk($row["cty_id"]);
			$cityBoundArr	 = CJSON::decode($result->cty_bounds);
			if (!count($cityBoundArr) > 0)
			{
				$cityBoundArr['northeast']['lat']	 = $result->cty_lat;
				$cityBoundArr['northeast']['lng']	 = $result->cty_long;
				$cityBoundArr['southwest']['lat']	 = $result->cty_lat;
				$cityBoundArr['southwest']['lng']	 = $result->cty_long;
			}
			$boundStatus = Filter::checkLatLongBound($placeObj->coordinates->latitude, $placeObj->coordinates->longitude, $cityBoundArr['northeast']['lat'], $cityBoundArr['northeast']['lng'], $cityBoundArr['southwest']['lat'], $cityBoundArr['southwest']['lng'], 0.03);
			if ($boundStatus)
			{
				$result->distance	 = $row["distance"];
				$boundModel[]		 = $result;
				$model->is_partial	 = 0;
			}
		}
		$last = strrpos($placeObj->address, ",");
		if ($last !== false)
		{
			$last = strrpos($placeObj->address, ",", $last - strlen($placeObj->address) - 1);
		}
		if ($last !== false)
		{
			$stateString = substr($placeObj->address, $last);
		}
		else
		{
			$stateString = $placeObj->address;
		}

		$checkPlaceObj = ($placeObj->isLocality() || $placeObj->isSubLocality1());

		$model				 = array_pop(array_reverse($boundModel));
		$model->is_partial	 = 1;
		if ($model && $model->distance < 15 && strpos($stateString, $model->ctyState->stt_name) !== false && $model->cty_service_active == 1 && strpos($model->cty_types, "administrative_area_level") === false)
		{
			$model->is_partial = 0;
		}
		$placeBounds = $placeObj->bounds;
		if (is_object($placeObj->bounds))
		{
			$placeBounds = json_encode($placeObj->bounds);
		}
		if ($checkPlaceObj && Filter::checkBoundsWithinBounds($model->cty_bounds, $placeBounds))
		{
			$model->is_partial = 0;
		}

		if ($placeObj->address == '')
		{
			goto model;
		}
		foreach ($boundModel as $result)
		{
			$checkBounds		 = Filter::checkBoundsWithinBounds($model->cty_bounds, $result->cty_bounds);
			$checkCity			 = strpos($placeObj->address, $result->cty_name);
			$checkState			 = strpos($stateString, $result->ctyState->stt_name);
			$checkLevel			 = strpos($result->cty_types, "administrative_area_level");
			$checkPlaceBounds	 = ($placeBounds == "") || ($checkPlaceObj && Filter::checkBoundsWithinBounds($result->cty_bounds, $placeBounds));
			if (!$checkLevel && $model->cty_service_active == 1 && $checkPlaceBounds)
			{
				$model				 = $result;
				$model->is_partial	 = 0;
				break;
			}

			if ((($checkCity === false)) || $checkState === false)
			{
				continue;
			}

			if ($checkLevel !== false || $result->cty_service_active == 0)
			{
				$result->is_partial = 1;
			}
			if ($result->is_partial === 1 && ($model->is_partial === 0 || ($checkBounds && !$checkLevel && $model->cty_service_active == 1)))
			{
				$model->is_partial = 0;
				break;
			}
			$model = $result;
			if ($model->is_partial !== 1)
			{
				$model->is_partial = 0;
			}
			break;
		}


		model:
		return $model;
	}

	/** @param \Stub\common\Place $placeObj */
	public static function getByAddress($placeObj)
	{
		$model = false;
		if ($placeObj->place_id != '')
		{
			$googleObj = GoogleMapAPI::getObjectByPlaceId($placeObj->place_id);
			goto addressObj;
		}
		$googleObj = GoogleMapAPI::getObjectByLatLong($placeObj->coordinates->latitude, $placeObj->coordinates->longitude);

		addressObj:
		$address			 = GoogleMapAPI::getAddress($googleObj);
		$googleObj			 = GoogleMapAPI::getObjectByAddress($address);
		/* @var $place \Stub\common\Place */
		$placeObj			 = \Stub\common\Place::initGoogePlace($googleObj->results[0]);
		$countryComponent	 = GoogleMapAPI::getAddressComponents($googleObj->results[0]);
		if ($countryComponent->short_name != 'IN')
		{
			if ($countryComponent->short_name == "")
			{
				$model = self::getByGeoBounds($placeObj, 15);
				if ($model && $model->is_partial == 0)
				{
					goto result;
				}
				$model = self::getByNearestBound($placeObj, 25);
				if ($model)
				{
					goto result;
				}
			}
			else
			{
				throw new Exception("Location not supported.");
			}
		}

		if ($placeObj->isLocality())
		{
			$model = Cities::findByPlaceId($placeObj);
			if ($model)
			{
				goto result;
			}
		}
		$objLocality	 = GoogleMapAPI::getACLocality($googleObj);
		$objAdminLevel1	 = GoogleMapAPI::getACAdminLevel1($googleObj);
		if ($objLocality && $objAdminLevel1)
		{
			$model = Cities::getByName($objLocality->long_name, $objAdminLevel1->long_name, $objAdminLevel1->short_name, $placeObj->coordinates);
		}
		if (!$model && $model == '')
		{
			if ($placeObj->isLocality())
			{
				$model = self::addByPlaceObject($placeObj);
			}
		}
		result:
		if ($model && $model->cty_place_id == '')
		{
			$model->cty_place_id = $googleObj->results[0]->place_id;
			$model->update();
		}
		return $model;
	}

	/** @param \Stub\common\Place $placeObj */
	public static function getByCoordinates($placeObj, $stateId = null)
	{
		$model				 = false;
		$models				 = [];
		$nameCheck			 = false;
		$googleObj			 = GoogleMapAPI::getObjectByLatLong($placeObj->coordinates->latitude, $placeObj->coordinates->longitude);
		$countryComponent	 = GoogleMapAPI::getAddressComponents($googleObj->results[0]);
		if ($countryComponent->short_name != 'IN')
		{
			$googlePlaceObj = GoogleMapAPI::getCountry($googleObj);
			if ($googlePlaceObj->address_components[0]->short_name != 'IN')
			{
				if ($googlePlaceObj->address_components[0]->short_name == "")
				{
					/* @var $place \Stub\common\Place */
					$nearPlaceObj = \Stub\common\Place::initGoogePlace($googleObj->results[0]);

					$model = self::getByGeoBounds($nearPlaceObj, 15);
					if ($model && $model->is_partial === 0)
					{
						goto result;
					}

					$model = self::getByNearestBound($nearPlaceObj, 25);
					if ($model && $model->is_partial === 0)
					{
						goto result;
					}
				}
				else
				{
					throw new Exception("Location not supported.");
				}
			}
		}
		/* @var $place \Stub\common\Place */
		$locplaceObj = \Stub\common\Place::initGoogePlace($googleObj->results[0]);
		if ($locplaceObj->isAirport())
		{
			$model = Cities::getNearestAirport($locplaceObj);
			if (!$model && $model == '')
			{
				$model = self::addByPlaceObject($locplaceObj);
			}
			goto result;
		}

		if ($locplaceObj->isLocality())
		{
			$model = Cities::findByPlaceId($locplaceObj);
			if ($model)
			{
				goto result;
			}
		}
		$objLocality	 = GoogleMapAPI::getACLocality($googleObj);
		$objAdminLevel1	 = GoogleMapAPI::getACAdminLevel1($googleObj);
		if ($objLocality && $objAdminLevel1)
		{
			$model = Cities::getByName($objLocality->long_name, $objAdminLevel1->long_name, $objAdminLevel1->short_name, $locplaceObj->coordinates);
		}
		if ($model)
		{
			goto result;
		}

		skipAddressComp:
		$googleObjs = GoogleMapAPI::getLocalities($googleObj);
		if (count($googleObjs) > 0)
		{
			foreach ($googleObjs as $key => $googlePlaceObj)
			{
				/* @var $place \Stub\common\Place */
				$locplaceObj = \Stub\common\Place::initGoogePlace($googlePlaceObj);
				$model		 = Cities::findByPlaceId($locplaceObj);

				if ($model)
				{
					$models[$key] = $model;
					continue;
				}
				$objLocality	 = GoogleMapAPI::getACLocality($googleObj);
				$objAdminLevel1	 = GoogleMapAPI::getACAdminLevel1($googleObj);
				if ($objLocality && $objAdminLevel1)
				{
					$model = Cities::getByName($objLocality->long_name, $objAdminLevel1->long_name, $objAdminLevel1->short_name, $locplaceObj->coordinates);
				}
				if ($model)
				{
					$models[$key] = $model;
					continue;
				}
				if (!$model && $model == '')
				{
					$model			 = self::addByPlaceObject($locplaceObj, $stateId);
					$models[$key]	 = $model;
				}
			}
			if (count($models) > 1)
			{
				foreach ($models as $k => $mdl)
				{
					$ids .= ($k == 0 ? $mdl->cty_id : ', ' . $mdl->cty_id);
					if ($placeObj->address != '')
					{
						$address = $mdl->cty_name . ', ' . $mdl->ctyState->stt_name;
						if (strpos($placeObj->address, $address) !== false)
						{
							$model		 = $mdl;
							$nameCheck	 = true;
							break;
						}
					}
				}

				if (!$nameCheck)
				{
					$model = self::findByDistance($ids, $placeObj);
				}
			}
		}
		result:
		if ($model && $model->cty_place_id == '')
		{
			$model->cty_place_id = $placeObj->place_id;
			$model->update();
		}
		Logger::profile("Cities::getByCoordinates Done");
		return $model;
	}

	/** @param \Stub\common\Place $placeObj */
	public static function findByDistance($ids, $placeObj)
	{
		$sql = "SELECT *, CalcDistance(cty_lat, cty_long, {$placeObj->coordinates->latitude}, {$placeObj->coordinates->longitude}) AS dis FROM cities WHERE cty_id IN($ids) AND cty_active=1 ORDER BY dis LIMIT 0,1";
		return Cities::model()->findBySql($sql);
	}

	/** @param \Stub\common\Place $placeObj */
	public static function getByPlaceId($placeObj)
	{
		if ($placeObj->isAirport())
		{
			$model = Cities::getNearestAirport($placeObj);
			if (!$model && $model == '')
			{
				$model = self::addByPlaceObject($placeObj);
			}
			goto result;
		}

		$model = Cities::findByPlaceId($placeObj);
		if ($model)
		{
			goto result;
		}

		$googleObj			 = GoogleMapAPI::getObjectByPlaceId($placeObj->place_id);
		/* @var $place \Stub\common\Place */
		$placeObj			 = \Stub\common\Place::initGoogePlace($googleObj->results[0]);
		$countryComponent	 = GoogleMapAPI::getAddressComponents($googleObj->results[0]);
		if ($countryComponent->short_name != 'IN')
		{
			if ($countryComponent->short_name == "")
			{
				$model = self::getByNearestBound($placeObj, 25);
				if ($model)
				{
					goto result;
				}
			}
			else
			{
				throw new Exception("Location not supported.");
			}
		}
		if ($placeObj->isLocality())
		{
			$model = Cities::findByPlaceId($placeObj);
			if ($model)
			{
				goto result;
			}
		}
		$objLocality	 = GoogleMapAPI::getACLocality($googleObj);
		$objAdminLevel1	 = GoogleMapAPI::getACAdminLevel1($googleObj);
		if ($objLocality && $objAdminLevel1)
		{
			$model = Cities::getByName($objLocality->long_name, $objAdminLevel1->long_name, $objAdminLevel1->short_name, $placeObj->coordinates);
		}
		if (!$model && $model == '')
		{
			if ($placeObj->isLocality())
			{
				$model = self::addByPlaceObject($placeObj);
			}
		}
		result:
		if ($model && $model->cty_place_id == '')
		{
			$model->cty_place_id = $googleObj->results[0]->place_id;
			$model->update();
		}
		return $model;
	}

	public static function getByName($cityName, $stateName, $stateCode, $coordinates)
	{
		$stateId = States::model()->getIdByNameAndCode($stateName, $stateCode);
		if (!$stateId)
		{
			return false;
		}

		$sql = "SELECT * FROM cities WHERE (cty_name='{$cityName}' OR cty_alias_name='{$cityName}') AND cty_state_id={$stateId} AND CalcDistance(cty_lat, cty_long, {$coordinates->latitude},{$coordinates->longitude}) < 15 AND cty_types NOT LIKE '%administrative_area%' AND cty_active=1";
		return Cities::model()->findBySql($sql);
	}

	/** @param \Stub\common\Place $placeObj */
	public static function getNearestAirport($placeObj)
	{
		$lat	 = $placeObj->coordinates->latitude;
		$long	 = $placeObj->coordinates->longitude;

		$sql = "SELECT *, IF(cty_place_id = '$placeObj->place_id',1,0) as rank, CalcDistance(cty_lat, cty_long, $lat, $long) AS distance  "
				. "FROM `cities` "
				. "WHERE (cty_place_id = '$placeObj->place_id' "
				. "OR (CalcDistance(cty_lat, cty_long, $lat, $long)<5 AND SOUNDEX(cty_name)=SOUNDEX('$placeObj->name')) "
				. "OR (CalcDistance(cty_lat, cty_long, $lat, $long)<1)) "
				. "AND cty_active = 1 AND cty_is_airport=1 "
				. "ORDER BY rank DESC, distance ASC";
		return Cities::model()->findBySql($sql);
	}

	/**
	 * @deprecated getByCoordiantesOld since 08/01/2020 By chiranjit Hazra
	 */
	public static function getByCoordiantesOld($placeObj)
	{
		$model = false;
		if ($placeObj->place_id != '')
		{
			$model = Cities::findByPlaceId($placeObj);
			if ($model)
			{
				if (in_array('locality', json_decode($model->cty_types)))
				{
					goto result;
				}
				else if (in_array('locality', $placeObj->types))
				{
					goto recheck;
				}
				else
				{
					goto result;
				}
			}
			//	$googleObj	 = GoogleMapAPI::getObjectByPlaceId($placeObj->place_id);
			//	$placeObj	 = \Stub\common\Place::initGoogePlace($googleObj->results[0]);
		}
		recheck:
		$googleObj		 = GoogleMapAPI::getObjectByLatLong($placeObj->coordinates->latitude, $placeObj->coordinates->longitude);
		$countryCompent	 = GoogleMapAPI::getAddressComponents($googleObj->results[0]);
		if ($countryCompent->short_name != 'IN')
		{
			$googlePlaceObj = GoogleMapAPI::getCountry($googleObj);
			if ($googlePlaceObj->address_components[0]->short_name != 'IN')
			{
				throw new Exception("Location not supported.");
			}
		}

		$googleObjs = GoogleMapAPI::getLocalities($googleObj);
		if (count($googleObjs) > 0)
		{
			goto cityPlaceId;
		}

		AdminLevel2:
		$googleObjs = [GoogleMapAPI::getAdminLevel2($googleObj)];
		if (!$googleObjs)
		{
			goto result;
		}

		cityPlaceId:
		$googleObj = '';
		foreach ($googleObjs as $googlePlaceObj)
		{
			/* @var $place \Stub\common\Place */
			$placeObj	 = \Stub\common\Place::initGoogePlace($googlePlaceObj);
			$model		 = Cities::findByPlaceId($placeObj);

			$googleObj = $googlePlaceObj;
			if ($model)
			{
				if (in_array('locality', json_decode($model->cty_types)))
				{
					goto result;
				}
				else if (in_array('locality', $placeObj->types))
				{
					goto AddCity;
				}
				else
				{
					goto result;
				}
			}
		}

		AddCity:
		$model = self::addByPlaceObject($placeObj);
		if (!$model)
		{
			throw new Exception("Location not supported.", ReturnSet::ERROR_FAILED);
		}

		result:
		if ($model->cty_place_id == '')
		{
			$model->cty_place_id = $googleObj->place_id;
			$model->update();
		}

		return $model;
	}

	/** @param \Stub\common\Place $placeObj */
	public static function addByPlaceObject($placeObj, $stateId = null)
	{
		if ($placeObj->isLocality() || $placeObj->isAirport())
		{
			goto Add;
		}
		$googleObj	 = GoogleMapAPI::getObjectByLatLong($placeObj->coordinates->latitude, $placeObj->coordinates->longitude);
		$objs		 = GoogleMapAPI::getLocalities($googleObj);
		foreach ($objs as $obj)
		{
			$placeObj	 = \Stub\common\Place::initGoogePlace($obj);
			$model		 = Cities::addByPlaceObject($placeObj, $stateId);
		}
		goto result;
		Add:

		$model = self::getByGeoBounds($placeObj, 25);
		if ($model && $model->is_partial == 0)
		{
			goto result;
		}
		$model = self::getByNearestBound($placeObj, 25);
		if ($model && $model->is_partial == 0)
		{
			goto result;
		}

		$model = self::create($placeObj, $stateId);

		result:
		return $model;
	}

	public static function create($placeObj, $stateId = null)
	{
		$model = Cities::model()->find("cty_place_id=:place AND :place<>''", ['place' => $placeObj->place_id]);
		if (!$model)
		{
			$model				 = new Cities();
			$model->cty_state_id = $stateId;
			if ($stateId == 0 || $stateId == null)
			{
				$stateModel			 = States::getByCoordiantes($placeObj);
				$model->cty_state_id = $stateModel->stt_id;
			}
		}
		if ($model->cty_name != '' && $model->cty_name <> trim($placeObj->name))
		{
			$model->cty_is_approved = 2;
			$model->save();
			return $model;
		}
		$model->cty_name = trim($placeObj->name);
		$alias			 = trim($placeObj->alias);
		if (strtolower($alias) != strtolower($model->cty_name))
		{
			$model->cty_alias_name = $alias;
		}

		$model->cty_types			 = json_encode($placeObj->types);
		$model->cty_place_id		 = $placeObj->place_id;
		$model->cty_lat				 = $placeObj->coordinates->latitude;
		$model->cty_long			 = $placeObj->coordinates->longitude;
		$model->cty_garage_address	 = $placeObj->address;
		$model->cty_bounds			 = json_encode($placeObj->bounds);

		$model->cty_is_approved = 0;
		if ($placeObj->isAirport())
		{
			$model->cty_is_airport = 1;
		}
		$model->cty_active = 1;
		if (!$model->save())
		{
			throw new Exception(json_encode($model->getErrors()), 1);
		}

		$noCities = self::countNoOfCitiesBySameNameAndState($model->cty_name, $model->cty_state_id);
		if ($noCities && $noCities['cnt'] > 1)
		{
			if (!$googleObj)
			{
				$googleObj = GoogleMapAPI::getObjectByLatLong($placeObj->coordinates->latitude, $placeObj->coordinates->longitude);
			}
			$objAdminlevel2 = GoogleMapAPI::getAdminLevel2($googleObj);
			if ($model->cty_alias_name != '')
			{
				$model->cty_alias_name .= ' - ';
			}
			$model->cty_alias_name .= $objAdminlevel2->address_components[0]->long_name;
		}
		if ($model->cty_alias_path == '')
		{
			$model->cty_alias_path = Cities::model()->generateAliasPath($model->cty_name, $model->cty_id);
		}
		if (!$model->save())
		{
			throw new Exception(json_encode($model->getErrors()), 1);
		}
		ZoneCities::addZoneCities($model->cty_id);
		result:
		return $model;
	}

	public static function countNoOfCitiesBySameNameAndState($name, $stateId)
	{
		$sql	 = "SELECT COUNT(1) AS cnt FROM cities WHERE cty_name='{$name}' AND cty_state_id=$stateId AND cty_active=1";
		$result	 = DBUtil::queryRow($sql, DBUtil::MDB(), [], 60 * 60, CacheDependency::Type_Cities);
		return $result;
	}

	public static function getCtyRadiusByCtyId($ctyId)
	{
		if ($ctyId == null || $ctyId == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params	 = array("ctyId" => $ctyId);
		$sql	 = "SELECT
				cty_radius
				FROM cities
				WHERE
				cty_service_active =1
				AND cty_active = 1
				AND cty_is_airport=1
				AND cty_id=:ctyId";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		if ($result == null)
		{
			$result = 25;
		}
		return $result;
	}

	public static function getCtyLatLongByCtyId($ctyId)
	{
		$sql	 = " SELECT
					cty_id,cty_lat,cty_long,cty_is_airport,cty_poi_type,cty_garage_address
				 FROM cities
				 WHERE
				 cty_active = 1
				 AND (cty_is_airport=1 OR cty_poi_type=1 OR cty_poi_type=2)
				 AND cty_id={$ctyId}
				";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB());
		return ['ctyId' => $result['cty_id'], 'isAirport' => $result['cty_is_airport'], 'isPoiType' => $result['cty_poi_type'], 'ctyLat' => $result['cty_lat'], 'ctyLong' => $result['cty_long'], 'grageAdd' => $result['cty_garage_address']];
		;
	}

	public function getCitiesListByIntCityName($state_id, $city_int_name)
	{
		$recordSet	 = $this->searchCityListByStateId($state_id, $city_int_name);
		$arrCities	 = array();
		foreach ($recordSet as $record)
		{
			$arrCities[] = array("id" => $record['cty_id'], "text" => $record['cty_name']);
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	public function searchCityListByStateId($state_id, $city_int_name)
	{
		if ($city_int_name != '')
		{
			$cityIntName = "AND cities.cty_name LIKE '$city_int_name%' ";
		}
		else
		{
			$cityIntName = " ";
		}
		$sql = "SELECT stt_id,cty_id,cty_name FROM states LEFT JOIN cities ON cities.cty_state_id = states.stt_id AND cities.cty_active=1 WHERE states.stt_id= $state_id  $cityIntName";
		return DBUtil::queryAll($sql);
	}

	public function allCityListByStateId($state_id)
	{
		$sql		 = "SELECT group_concat(cty_id)as citylist FROM cities WHERE cty_state_id=$state_id";
		$cityRecord	 = DBUtil::queryRow($sql);
		$city_list	 = $cityRecord['citylist'];
		return $city_list;
	}

	public static function checkAirport($cityID)
	{
		if (!$cityID)
		{
			return FALSE;
		}
		$sql	 = "SELECT  cty_is_airport FROM cities WHERE  cty_id =$cityID";
		$results = DBUtil::queryRow($sql);
		if ($results['cty_is_airport'] == 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function StateWiseCity($state_id)
	{
		$sql		 = "SELECT cty_id as name FROM cities WHERE cty_state_id=$state_id";
		$CityData	 = DBUtil::queryAll($sql);
		return $CityData;
	}

	/*
	  function city wise structure markup data creatation define by schema.org
	 * param $city_id
	 */

	public function getStructuredMarkupForCity($city_id)
	{
		$key	 = "getStructureMarkupForcity_" . $city_id;
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$arrStructData									 = array();
		$arrStructData['@context']						 = "http://schema.org/";
		$arrStructData['@type']							 = "Service";
		$arrStructData['name']							 = Cities::getName($city_id);
		//rating and comment count
		$arrRouteRating									 = Ratings::getCitySummary($city_id);
		$arrStructData['aggregateRating']['ratingValue'] = $arrRouteRating['ratings'];
		$arrStructData['aggregateRating']['ratingCount'] = $arrRouteRating['cnt'];

		//provider details function
		$arrStructData['provider']	 = array();
		$providerContactdata		 = StructureData::providerDetails();
		$arrStructData['provider']	 = $providerContactdata;

		$sql		 = "SELECT cty_lat,cty_long,cty_excluded_cabtypes FROM cities WHERE cty_id =$city_id"; //area lat long
		$resultArr	 = DBUtil::queryAll($sql);

		$arrStructData['isRelatedTo']									 = array();
		$arrStructData['isRelatedTo']['@type']							 = "TaxiService";
		$arrStructData['isRelatedTo']['areaServed']						 = array();
		$arrStructData['isRelatedTo']['areaServed']['@type']			 = "Place";
		$arrStructData['isRelatedTo']['areaServed']['geo']				 = array();
		$arrStructData['isRelatedTo']['areaServed']['geo']['@type']		 = "GeoCoordinates";
		$arrStructData['isRelatedTo']['areaServed']['geo']['latitude']	 = $resultArr[0]['cty_lat'];
		$arrStructData['isRelatedTo']['areaServed']['geo']['longitude']	 = $resultArr[0]['cty_long'];

		// offer catalog
		$arrStructData['hasOfferCatalog']['@type']	 = "OfferCatalog";
		$arrStructData['hasOfferCatalog']['name']	 = "Car Taxi Services";

		//car wise rate per km
		$arrVehicleTypes	 = VehicleTypes::model()->getCarType();
		// Vehicle details
		$arrVehicleModels	 = VehicleTypes::model()->getMasterCarDetails();
		$arrVehicleId		 = array_keys($arrVehicleTypes);
		$excludedCarId		 = $resultArr[0]['cty_excluded_cabtypes'];
		$excludedCarIdArr	 = explode(",", $excludedCarId);
		$includedCarIdArr	 = array_diff($arrVehicleId, $excludedCarIdArr);
		foreach ($includedCarIdArr as $key => $arrVehicle)
		{
			//$rates			 = AreaPriceRule::model()->getRules($city_id, $arrVehicle);
			//$priceRuleArr	 = PriceRule::model()->findByPk($rates[6]); //rates[6]='roundtrip'
			$priceRuleArr	 = PriceRule::getByCity($city_id, 6, $arrVehicle);
			$ratePerKM		 = $priceRuleArr['prr_rate_per_km_extra'];
			if ($priceRuleArr['prr_rate_per_km_extra'] != "")
			{
				$arrStructVehicleOptions														 = array();
				$vehicleType																	 = $arrVehicleTypes[$arrVehicle];
				$arrStructVehicleOptions['itemOffered']											 = array();
				$arrStructVehicleOptions['itemOffered']['@type']								 = "Car";
				$arrStructVehicleOptions['itemOffered']['category']								 = $vehicleType;
				$arrStructVehicleOptions['itemOffered']['model']								 = $arrVehicleModels[$arrVehicle]['vht_model'];
				$arrStructVehicleOptions['itemOffered']['vehicleSeatingCapacity']				 = $arrVehicleModels[$arrVehicle]['vht_capacity'];
				$arrStructVehicleOptions['@type']												 = "Offer";
				$arrStructVehicleOptions['priceSpecification']									 = array();
				$arrStructVehicleOptions['priceSpecification']['@type']							 = "PriceSpecification";
				$arrStructVehicleOptions['priceSpecification']['price']							 = $ratePerKM;
				$arrStructVehicleOptions['priceSpecification']['priceCurrency']					 = 'INR';
				$arrStructVehicleOptions['priceSpecification']['eligibleQuantity']				 = array();
				$arrStructVehicleOptions['priceSpecification']['eligibleQuantity']['@type']		 = "QuantitativeValue";
				$arrStructVehicleOptions['priceSpecification']['eligibleQuantity']['unitText']	 = "KM";

				$arrOfferCatalog[] = $arrStructVehicleOptions;
			}
		}
		$arrStructData['hasOfferCatalog']					 = array();
		$arrStructData['hasOfferCatalog']['@type']			 = "OfferCatalog";
		$arrStructData['hasOfferCatalog']['name']			 = "Car Taxi Services";
		$arrStructData['hasOfferCatalog']['itemListElement'] = $arrOfferCatalog;
		$arrStructData										 = json_encode($arrStructData);
		Yii::app()->cache->set($key, $arrStructData, 60 * 60 * 24 * 30);
		result:
		return $arrStructData;
	}

	/*
	  function city wise breadcump markup data creatation define by schema.org
	 * param $city_id
	 */

	public function getBreadcumbMarkupForCity($cityId)
	{
		$key	 = "getBreadcumbForcity_" . $cityId;
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$breadcumbData	 = StructureData::breadCumbDetails($cityId, '', 'city_type');
		$data			 = json_encode($breadcumbData, JSON_UNESCAPED_SLASHES);
		Yii::app()->cache->set($key, $data, 60 * 60 * 24 * 30);
		result:
		return $data;
	}

	public function getCityhasairport($city_name)
	{
		$city_name	 = $city_name . ' Airport';
		$sql		 = " SELECT count(cty_id) as val FROM cities  WHERE cty_is_airport = 1 AND  cty_alias_name = '$city_name'";
		$CityData	 = DBUtil::queryAll($sql);
		return $CityData[0]['val'];
	}

	public function getCityListDrop()
	{
		$cityModels	 = Cities::model()->list();
		$arrSkill	 = array();

		foreach ($cityModels as $key => $sklModel)
		{
			$arrSkill[$sklModel['cty_id']] = $sklModel['cty_name'];
		}
		return $arrSkill;
	}

	public function getAirportByCity($cityname)
	{
		if ($cityname == 'bengaluru' || $cityname == 'Bengaluru')
		{
			$cityname = 'bangalore';
		}
		$sql		 = "SELECT cty_id as val FROM cities WHERE cty_is_airport = 1 AND cty_alias_name LIKE '%" . $cityname . "%'";
		$CityData	 = DBUtil::queryRow($sql);
		return $CityData;
	}

	/**
	 * @param \Stub\common\Place $placeObj 
	 * @return CDbDataReader
	 */
	public static function getNearestAirports($placeObj)
	{
		$params	 = ['lat' => $placeObj->coordinates->latitude, "lng" => $placeObj->coordinates->longitude];
		//   print_r($params);
		$sql	 = "SELECT cty_id, CalcDistance(cty_lat, cty_long, :lat, :lng) as distance  FROM cities
						WHERE cty_is_airport = 1 AND cty_active=1 
							AND cty_lat BETWEEN (:lat-0.5) AND (:lat+0.5) 
							AND cty_long BETWEEN (:lng-0.5) AND (:lng +0.5) 
						ORDER BY distance ASC";
		$rows	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $rows;
	}

	public function getAllCityListDrop()
	{
		$cityModels	 = Cities::model()->list();
		$arrSkill	 = array();
		foreach ($cityModels as $key => $sklModel)
		{
			$arrSkill[] = array("id" => $sklModel['cty_id'], "text" => $sklModel['cty_name']);
		}
		//echo json_encode($arrSkill);
		return json_encode($arrSkill);
	}

	public function validateAirportTransfer($airportCityid, $otherCity)
	{
		$maxDistance = Yii::app()->params['airportCityRadius'];

		$sql	 = "
		 SELECT cty2.cty_id,if((cty1.cty_radius IS NULL || cty1.cty_radius=0),$maxDistance,cty1.cty_radius) as distance1,
			rut.rut_estm_distance,
			ceil( ifNull(
			   rut.rut_estm_distance,
			   (6371 * acos(
				  cos(radians(cty1.cty_lat)) *
				  cos(radians(cty2.cty_lat)) *
				  cos(radians(cty2.cty_long) - radians(cty1.cty_long)) +
				  sin(radians(cty1.cty_lat)) * sin(radians(cty2.cty_lat)))) * 2)) AS estdist,
            concat(cty2.cty_name, if((cty2.cty_alias_name IS NULL || cty2.cty_alias_name=''),'',concat(' (',cty2.cty_alias_name,')')),', ',stt.stt_name) as cty_name,
             cty2.cty_state_id,
                (6371 * acos(
                   cos(radians(cty1.cty_lat)) *
                   cos(radians(cty2.cty_lat)) *
                   cos(radians(cty2.cty_long) - radians(cty1.cty_long)) +
                   sin(radians(cty1.cty_lat)) * sin(radians(cty2.cty_lat))))
                  AS distance
               FROM cities  cty1
            JOIN cities cty2 ON cty1.cty_id <> cty2.cty_id
            JOIN states stt ON cty2.cty_state_id = stt.stt_id
            LEFT JOIN route rut ON rut.rut_from_city_id = cty1.cty_id
                 AND rut.rut_to_city_id = cty2.cty_id
            WHERE    cty1.cty_id = $airportCityid
            AND cty2.cty_id = $otherCity
            AND cty2.cty_service_active = 1 AND cty2.cty_active = 1
            having distance < distance1  AND distance1 >= estdist
            order by cty2.cty_name 	";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB());
		return $result;
	}

	public function getCtyNameByCtyId($cty)
	{
		$sql		 = "SELECT cty_id FROM cities WHERE cty_name = '$cty' AND cty_active=1";
		$CityData	 = DBUtil::queryRow($sql);
		return $CityData['cty_id'];
	}

	public function getJSONCitiesPackage($query, $city = '')
	{
		$condQuery	 = "";
		$condCity	 = "";
		$param		 = array();
		if ($query != '')
		{
			DBUtil::getLikeStatement($query, $bindString, $param1);
			$param		 = array_merge($param, $param1);
			$condQuery	 = " AND cty_name LIKE $bindString ";
		}
		if ($city != '')
		{
			$param['cty_id'] = $city;
			$condCity		 = " AND cty_id = :cty_id ";
		}
		$sql		 = "SELECT   cty_id, cty_display_name as  cty_name
					FROM  package
					INNER JOIN package_details ON pcd_pck_id = pck_id AND pcd_active = 1 AND pck_active = 1
					INNER JOIN package_rate ON prt_pck_id = pck_id AND prt_status = 1
					INNER JOIN cities ON (cty_id = pcd_from_city OR cty_id = pcd_to_city) AND cty_active = 1
				    WHERE  1 $condQuery $condCity
					GROUP BY cty_id ORDER BY cty_name";
		$rows		 = DBUtil::query($sql, DBUtil::SDB(), $param);
		$arrCities	 = array();
		foreach ($rows as $row)
		{
			$arrCities[] = array("id" => $row['cty_id'], "text" => $row['cty_name']);
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	public function getCityNameById($cityId)
	{
		$sql	 = "select cty_id, cty_name from cities where cty_id = $cityId";
		$rows	 = DBUtil::queryAll($sql);
		return $rows;
	}

	public static function getZonesAndState($cityId)
	{
		$sql = "SELECT cty_id, cities.cty_name, stt_id, GROUP_CONCAT(zct.zct_zon_id) as zones, states.stt_zone FROM cities
				INNER JOIN states ON states.stt_id = cities.cty_state_id
				LEFT JOIN zone_cities zct ON zct.zct_cty_id=cty_id AND zct.zct_active=1
				WHERE cty_id={$cityId} AND cities.cty_active=1 AND cities.cty_service_active=1
				GROUP BY cty_id";

		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	/**
	 * @var string $param Alias name to be passed
	 * @return int|null return null if not found else city id
	 *    */
	public static function getIdByAlias($param)
	{
		$id		 = null;
		$model	 = self::model()->getByAliasPath($param);
		if ($model)
		{
			$id = (int) $model->cty_id;
		}
		return $id;
	}

	public function getByAliasPath($name, $cityId = "")
	{
		if ($name == "")
		{
			return false;
		}
		$criteria = new CDbCriteria();
		$criteria->compare('cty_alias_path', $name);
		if ($cityId > 0)
		{
			$criteria->addCondition('cty_id != ' . $cityId);
		}
		$criteria->with = ['ctyState' => ['alias' => 'city_statename', 'select' => ['stt_name']]];
		return $this->find($criteria);
	}

	public static function findStateNameById($name, $cityId = "")
	{
		$where = " (cty_name='{$name}' OR cty_alias_name='{$name}')";
		if ($cityId > 0)
		{
			$where = " cty_id=$cityId";
		}
		$sql	 = "SELECT stt_id,stt_name FROM cities INNER JOIN states ON stt_id = cty_state_id WHERE $where AND cty_active=1";
		$result	 = DBUtil::queryRow($sql);
		return $result;
	}

	function clean($string)
	{
		$string	 = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string	 = preg_replace('/[^A-Za-z0-9\_\-]/', '', $string); // Removes special chars.
		return preg_replace('/_+/', '_', $string); // Replaces multiple hyphens with single one.
	}

	public function generateAliasPath($cityName, $cityId = "")
	{
		$cleanCityName	 = $this->clean($cityName);
		$cleanCityName	 = strtolower($cleanCityName);
		//$model			 = $this->getByAliasPath($cleanCityName, $cityId);
		$result			 = self::findStateNameById($cleanCityName, $cityId);
		if ($result)
		{
			$cleanStateName	 = $this->clean($result['stt_name']);
			$stateCityName	 = strtolower($cleanCityName . "-" . $cleanStateName);
			$cleanCityName	 = $stateCityName;
			$i				 = 0;
			while ($this->getByAliasPath($cleanCityName, $cityId))
			{
				$i++;
				$cleanCityName = $stateCityName . "-" . $i;
			}
		}
		return $cleanCityName;
	}

	public static function getDestinationList()
	{

		$sql		 = "SELECT city.cty_id,city.cty_name,SUBSTRING_INDEX(city.cty_display_name, ',', -1)  AS stt_name FROM `cities` city WHERE city.cty_active=1 ORDER BY cty_name ASC";
		$recordset	 = DBUtil::query($sql, DBUtil::SDB());
		return $recordset;
	}

	public function getJSONShuttleSourceCities($query, $city = '', $pdate = '', $showArray = false)
	{
		$qry		 = '';
		$limitNum	 = 30;
		$qry1		 = '';
		if ($city != '')
		{
			$qry1		 = " AND 1 OR cty_id='$city'";
			$limitNum	 = 29;
		}
		if ($pdate != '')
		{
			$qry2 = " AND  date(slt_pickup_datetime)='$pdate'";
		}


		$qry .= " AND cty_id IN (SELECT slt_from_city FROM (SELECT distinct slt_from_city FROM shuttle WHERE slt_status = 1 $qry2
                         ORDER BY slt_from_city DESC LIMIT 0, $limitNum) a)";
		if ($query != '')
		{
			$qry .= " AND cty_name LIKE '%{$query}%'";
		}
		$sql		 = "SELECT cty.cty_id,concat(cty.cty_name, if((cty.cty_alias_name IS NULL || cty.cty_alias_name=''),'',concat(' (',cty.cty_alias_name,')')),', ',ctyState.stt_name) as cty_name
                FROM cities cty LEFT JOIN `states` `ctyState` ON `cty`.`cty_state_id`=`ctyState`.`stt_id`
                WHERE cty.cty_active=1  $qry $qry1 ORDER BY cty.cty_name LIMIT 0,30 ";
		$rows		 = DBUtil::queryAll($sql, DBUtil::SDB());
		$arrCities	 = array();

		foreach ($rows as $row)
		{
			$arrCities[$row['cty_id']] = $row['cty_name'];
		}
		if ($showArray)
		{
			return $arrCities;
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	public function getJSONShuttlePickup($fromCity = 0, $dateVal = '')
	{
		$where = '';
		if ($dateVal != '')
		{
			$where = " AND date(slt_pickup_datetime)='$dateVal'";
		}
		$sql = "SELECT distinct concat(slt_pickup_lat,',',slt_pickup_long) slt_lat_long,
            slt_pickup_location FROM shuttle slt
            WHERE    slt.slt_from_city = $fromCity  $where
			order by slt_pickup_location limit 30";

		$recordSet	 = DBUtil::queryAll($sql, DBUtil::SDB());
		$arrAddress	 = array();
		foreach ($recordSet as $record)
		{
			$arrAddress[$record['slt_lat_long']] = $record['slt_pickup_location'];
		}
		$data = CJSON::encode($arrAddress);
		return $data;
	}

	public function getJSONShuttledest($arr = [], $showArray = false)
	{
		$where		 = '';
		$fromCity	 = $arr['fcityVal'];
		if ($arr['fcityVal'] != '')
		{
			$where .= " AND slt.slt_from_city = $fromCity";
		}
		if ($arr['pdate'] != '')
		{
			$where .= " AND slt.slt_pickup_datetime  between '" . $arr['pdate'] . " 00:00:00' AND  '" . $arr['pdate'] . " 23:59:59'";
		}
		if ($arr['fcityLoc'] != '')
		{
			$latLong = explode(',', $arr['fcityLoc']);
			if (sizeof($latLong) == 2)
			{
				$where	 .= " AND  slt.slt_pickup_lat='" . $latLong[0] . "'";
				$where	 .= " AND  slt.slt_pickup_long='" . $latLong[1] . "'";
			}
		}

		$sql = "SELECT distinct cty.cty_id, concat(cty.cty_name, if((cty.cty_alias_name IS NULL || cty.cty_alias_name=''),'',concat(' (',cty.cty_alias_name,')')),', ',stt.stt_name) as cty_name
			FROM cities  cty
            JOIN states stt ON cty.cty_state_id = stt.stt_id
            INNER JOIN shuttle slt ON  slt.slt_to_city = cty.cty_id AND slt.slt_status = 1
            WHERE 1 $where  AND cty.cty_service_active = 1 AND cty.cty_active = 1
           ORDER BY cty.cty_name limit 30";

		$recordSet = DBUtil::queryAll($sql, DBUtil::SDB());
		foreach ($recordSet as $record)
		{
			$arrCities[$record['cty_id']] = $record['cty_name'];
		}
		if ($showArray)
		{
			return $arrCities;
		}
		$data = CJSON::encode($arrCities);

		return $data;
	}

	public function getJSONShuttleDrop($arr = [])
	{
		$where = '';

		$fromCity	 = $arr['fcityVal'];
		$toCity		 = $arr['tcityVal'];
		$where		 .= " AND slt.slt_from_city = $fromCity";
		$where		 .= " AND slt.slt_to_city = $toCity";
		if ($arr['pdate'] != '')
		{
			$where .= " AND date(slt_pickup_datetime)='" . $arr['pdate'] . "'";
		}

		if ($arr['fcityLoc'] != '')
		{
			$latLong = explode(',', $arr['fcityLoc']);
			if (sizeof($latLong) == 2)
			{
				$where	 .= " AND  slt_pickup_lat='" . $latLong[0] . "'";
				$where	 .= " AND  slt_pickup_long='" . $latLong[1] . "'";
			}
		}

		$sql = "SELECT distinct concat(slt_drop_lat,',',slt_drop_long) slt_lat_long,
            slt_drop_location FROM shuttle slt
            WHERE  1  $where
			order by slt_drop_location limit 30";

		$recordSet	 = DBUtil::queryAll($sql, DBUtil::SDB());
		$arrAddress	 = [];
		foreach ($recordSet as $record)
		{
			$arrAddress[$record['slt_lat_long']] = $record['slt_drop_location'];
		}
		$data = CJSON::encode($arrAddress);
		return $data;
	}

	public function getDetailsByCityId($cityId)
	{
		$sql	 = "SELECT cty_id,cty_lat,cty_long,cty_is_airport,cty_garage_address FROM cities
				WHERE cty_id = $cityId AND cty_active=1";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB());
		return $result;
	}

	public function getCityByLatLong($lat, $long)
	{
		$sql		 = "SELECT
					cty_id,(
					  3959 * acos (
						cos ( radians($lat) )
						* cos( radians( cty_lat ) )
						* cos( radians( cty_long ) - radians($long) )
						+ sin ( radians($lat) )
						* sin( radians( cty_lat ) )
					  )
					) AS distance,
					cty_is_airport,
					IFNULL(cty_radius, 25) AS cty_radius
				  FROM cities
				  WHERE
				  cty_service_active =1
				  AND cty_active = 1
				  HAVING distance < 10
				  ORDER BY distance limit 0,1";
		$resultSet	 = DBUtil::queryRow($sql);
		return $resultSet;
	}

	/** @var Cities $cities */
	public function getCityDetails($model)
	{
		if ($model->bookingRoutes[0]->brt_from_place_lat > 0 && $model->bookingRoutes[0]->brt_from_place_long > 0)
		{
			$sourceCty = Cities::model()->getCtyIdWithBound($model->bookingRoutes[0]->brt_from_place_lat, $model->bookingRoutes[0]->brt_from_place_long, $model->bookingRoutes[0]->brt_from_city_is_airport);
		}
		else
		{
			$sourceCty = Cities::model()->getCtyIdWithBound($model->bookingRoutes[0]->brt_from_latitude, $model->bookingRoutes[0]->brt_from_longitude, $model->bookingRoutes[0]->brt_from_city_is_airport);
		}
		if ($model->bookingRoutes[0]->brt_to_place_lat > 0 && $model->bookingRoutes[0]->brt_to_place_long > 0)
		{
			$destinationCty = Cities::model()->getCtyIdWithBound($model->bookingRoutes[0]->brt_to_place_lat, $model->bookingRoutes[0]->brt_to_place_long, $model->bookingRoutes[0]->brt_to_city_is_airport);
		}
		else
		{
			$destinationCty = Cities::model()->getCtyIdWithBound($model->bookingRoutes[0]->brt_to_latitude, $model->bookingRoutes[0]->brt_to_longitude, $model->bookingRoutes[0]->brt_to_city_is_airport);
		}
		if ($sourceCty['isAirport'] == 1)
		{
			$airportRadius = Cities::getCtyRadiusByCtyId($sourceCty['ctyId']);
		}
		else if ($destinationCty['isAirport'] == 1)
		{
			$airportRadius = Cities::getCtyRadiusByCtyId($sourceCty['ctyId']);
		}
		$distance				 = ROUND(SQRT(POW(69.1 * ($model->bookingRoutes[0]->brt_from_place_lat - $model->bookingRoutes[0]->brt_to_place_lat ), 2) + POW(69.1 * ($model->bookingRoutes[0]->brt_to_place_long - $model->bookingRoutes[0]->brt_from_place_long ) * COS($model->bookingRoutes[0]->brt_from_place_lat / 57.3), 2)), 2);
		$dis					 = $distance * 1.60934;
		$model->bkg_booking_type = 4;
		if ($dis > $airportRadius)
		{
			$model->bkg_booking_type = 1;
		}
//		if ($dis < 2)
//		{
//			// throw new Exception("We cannnot prvoide airport transfer with in 2 km radius", ReturnSet::ERROR_INVALID_DATA);
//			return false;
//		}
		$model->bookingRoutes[0]->brt_from_is_airport	 = $sourceCty['isAirport'];
		$model->bookingRoutes[0]->brt_from_city_id		 = $sourceCty['ctyId'];
		$model->bookingRoutes[0]->brt_to_is_airport		 = $destinationCty['isAirport'];
		$model->bookingRoutes[0]->brt_to_city_id		 = $destinationCty['ctyId'];
		$model->bookingRoutes[0]->brt_from_city_name	 = Cities::getName($sourceCty['ctyId']);
		$model->bookingRoutes[0]->brt_to_city_name		 = Cities::getName($destinationCty['ctyId']);
//        $model->bookingRoutes[0]->brt_from_location   = $model->bookingRoutes[0]->brt_from_formatted_address;
//        $model->bookingRoutes[0]->brt_to_location     = $model->bookingRoutes[0]->brt_to_formatted_address;

		$model->bookingRoutes[0]->brt_from_is_airport	 = 0;
		$model->bookingRoutes[0]->brt_from_location		 = $model->bookingRoutes[0]->brt_from_formatted_address;
		if ($model->bookingRoutes[0]->brt_from_city_is_airport == 1)
		{

			$model->bookingRoutes[0]->brt_from_is_airport	 = 1;
			$fromFormattedAddress							 = $model->bookingRoutes[0]->brt_from_formatted_address;
			$model->bookingRoutes[0]->brt_from_location		 = $fromFormattedAddress;
		}

		$model->bookingRoutes[0]->brt_to_city_is_airport = 0;
		$model->bookingRoutes[0]->brt_to_location		 = $model->bookingRoutes[0]->brt_to_formatted_address;
		if ($model->bookingRoutes[0]->brt_to_city_is_airport == 1)
		{

			$model->bookingRoutes[0]->brt_to_is_airport	 = 1;
			$toFormattedAddress							 = $model->bookingRoutes[0]->brt_to_formatted_address;
			$model->bookingRoutes[0]->brt_to_location	 = $toFormattedAddress;
		}

		/* 	
		  if ($model->bookingRoutes[0]->brt_from_is_airport == 1)
		  {
		  $colpos										 = strrpos($model->bookingRoutes[0]->brt_from_city_name, " ");
		  $result										 = substr($model->bookingRoutes[0]->brt_from_city_name, 0, $colpos);
		  $fromFormattedAddress						 = $result . "  " . $model->bookingRoutes[0]->brt_from_formatted_address;
		  $model->bookingRoutes[0]->brt_from_location	 = $fromFormattedAddress;
		  }
		  else
		  {
		  $model->bookingRoutes[0]->brt_from_location = $model->bookingRoutes[0]->brt_from_formatted_address;
		  }

		  if ($model->bookingRoutes[0]->brt_to_is_airport == 1)
		  {
		  $colpos										 = strrpos($model->bookingRoutes[0]->brt_to_city_name, ",");
		  $result										 = substr($model->bookingRoutes[0]->brt_to_city_name, 0, $colpos);
		  $fromFormattedAddress						 = $result . " , " . $model->bookingRoutes[0]->brt_to_formatted_address;
		  $model->bookingRoutes[0]->brt_to_location	 = $fromFormattedAddress;
		  }
		  else
		  {
		  $model->bookingRoutes[0]->brt_to_location = $model->bookingRoutes[0]->brt_to_formatted_address;
		  }
		 */
		return $model;
	}

	public static function getCityName()
	{
		$qry	 = "select cty_id, cty_name from cities";
		$rows	 = DBUtil::query($qry);
		$cityArr = array();
		foreach ($rows as $key => $value)
		{
			$cityArr[$value['cty_id']] = $value['cty_name'];
		}
		return $cityArr;
	}

	public function getIdByCityCron($city)
	{
		$sql = "select cty_id from cities where cty_name='$city'";
		$row = DBUtil::queryRow($sql, DBUtil::MDB());
		return $row['cty_id'];
	}

	public function getStateCityNameCron()
	{
		$qry	 = "select stt_id,cty_alias_path from states inner Join cities on states.stt_id=cities.cty_state_id where 1 AND cty_active =1 AND cty_is_airport=0 and stt_active ='1'  order by states.stt_id";
		$rows	 = DBUtil::queryAll($qry, DBUtil::MDB());
		return (Filter::groupArray($rows, "stt_id"));
	}

	public function getCityhasairportCron($city_name)
	{
		$city_name	 = $city_name . ' Airport';
		$sql		 = " SELECT count(cty_id) as val FROM cities  WHERE cty_is_airport = 1 AND  cty_alias_name = '$city_name'";
		$CityData	 = DBUtil::queryRow($sql);
		return $CityData['val'];
	}

	public function getDistanceBycoordinates($lat1, $long1, $lat2, $long2)
	{

		$sql		 = "  SELECT ROUND(	( 6371 *
					acos(
							cos( radians($lat1) ) *
							cos( radians($lat2) ) *
							cos( radians($long2) - radians($long1)) +
							sin(radians($lat1)) *
							sin(radians($lat2)) )  ), 2)
				 AS distance from dual";
		$resultSet	 = DBUtil::queryRow($sql);
		return $resultSet;
	}

	public static function findByPlaceId($placeObj)
	{
		$lat	 = $placeObj->coordinates->latitude;
		$long	 = $placeObj->coordinates->longitude;
		$name	 = $placeObj->name;
		$placeId = $placeObj->place_id;
		$sql	 = "SELECT *, IF(cty_place_id = '$placeId', 2, IF(SOUNDEX(cty_name)=SOUNDEX('$name'), 1, 0)) as rank "
				. "FROM `cities` "
				. "WHERE (cty_place_id = '$placeId' "
				. "OR (CalcDistance(cty_lat, cty_long, $lat, $long)<5 AND SOUNDEX(cty_name)=SOUNDEX('$name')) "
				. "OR (CalcDistance(cty_lat, cty_long, $lat, $long)<1)) "
				. "AND cty_types NOT LIKE '%administrative_area_level_2%'"
				. "AND cty_active = 1 "
				. "ORDER BY rank DESC, CalcDistance(cty_lat, cty_long, $lat, $long) ASC";
		$model	 = Cities::model()->findBySql($sql);
		Logger::profile("Cities::findByPlaceId Done");
		return $model;
	}

	public static function add($postData)
	{
		/* @var $place \Stub\common\Place */
		$placeObj		 = \Stub\common\Place::init($postData['cty_lat'], $postData['cty_long'], $postData['cty_place_id']);
		$placeObj->name	 = $postData['cty_name'];

		if ($postData['ctyid'] > 0)
		{
			$model	 = Cities::model()->findByPk($postData['ctyid']);
			$oldData = $model->attributes;
			goto modify;
		}

		//duplicate checking
		$model = Cities::findByPlaceId($placeObj);
		if ($model)
		{
			$model->addError('cty_name', "This city already exists.");
			goto result;
		}

		modify:
		$transaction = Filter::beginTransaction();
		//add new city
		if ($model == '' || $model->cty_place_id == '')
		{
			$model = Cities::getByPlace($placeObj);
			if (!$model || $model == "")
			{
				$model->addError('cty_name', "City not added");
				goto result;
			}
		}

		$model->attributes	 = $postData;
		$result				 = CActiveForm::validate($model);
		if ($result == '[]')
		{
			$model->cty_temp_name				 = $model->cty_name;
			$model->cty_excluded_cabtypes		 = ($postData['cty_excluded_cabtypes'] != '') ? implode(',', $postData['cty_excluded_cabtypes']) : '';
			$model->cty_included_cabtires		 = ($postData['cty_included_cabtires'] != '') ? implode(',', $postData['cty_included_cabtires']) : '';
			$model->cty_included_cabCategories	 = ($postData['cty_included_cabCategories'] != '') ? implode(',', $postData['cty_included_cabCategories']) : '';
			$model->cty_included_cabmodels		 = ($postData['cty_included_cabmodels'] != '') ? implode(',', $postData['cty_included_cabmodels']) : '';
			$model->cty_full_name				 = $postData['cty_name'];
			if ($postData['cty_keyword_names'] != '')
			{
				$keyword_list				 = Keywords::model()->add($postData['cty_keyword_names']);
				$model->cty_keyword_names	 = str_replace(' ', '', implode(',', $keyword_list));
			}

			if ($postData['ctyid'] > 0)
			{
				$model->cty_log = $model->addLog($oldData, $model->attributes);
			}
			$model->cty_poi_type = $postData['cty_poi_type'];
			if ($model->save())
			{
				if ($postData['cty_zones'] != '')
				{
					$zones = $postData['cty_zones'];
					ZoneCities::model()->add($zones, $model->cty_id);
				}
			}
		}
		Filter::commitTransaction($transaction);
		result:
		return $model;
	}

	public static function getStateZoneInfoByCity($city)
	{
		$sql = "SELECT stt_id, GROUP_CONCAT(DISTINCT zct_zon_id) zones, stt_zone AS region
				FROM   cities
				INNER JOIN states ON cty_state_id = stt_id AND stt_active = '1'
				LEFT JOIN zone_cities ON cty_id = zct_cty_id AND zct_active = 1
				LEFT JOIN zones ON zon_id=zct_zon_id AND zon_active=1
				WHERE  cty_id =:city AND cty_active = 1";

		$stateZoneInfo = DBUtil::queryRow($sql, DBUtil::SDB(), ['city' => $city], 5 * 24 * 60 * 60, CacheDependency::Type_Cities);
		return $stateZoneInfo;
	}

	public static function findByCityName($city, $sid)
	{
		if ($sid == null || $sid == "")
		{
			throw new Exception("Required data missing for $city", ReturnSet::ERROR_INVALID_DATA);
		}
		$params		 = ['sid' => $sid];
		DBUtil::getLikeStatement($city, $bindString, $params1);
		$params		 = array_merge($params, $params1);
		$sql		 = "SELECT cty_id FROM cities WHERE cty_name LIKE $bindString OR cty_alias_name LIKE $bindString AND cty_active=1 AND cty_state_id =:sid";
		$CityData	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		if (count($CityData) > 0)
		{
			return $CityData;
		}
		else
		{
			return null;
		}
	}

	public static function getAirportList()
	{
		$selects = " city.`cty_id` as id, city.cty_display_name as text ";
		$sql	 = "SELECT $selects	FROM cities city WHERE 1 AND cty_active=1 AND cty_is_airport=1 AND cty_service_active=1  $qry  ORDER BY cty_display_name ASC  ";
		$res	 = DBUtil::queryAll($sql, DBUtil::SDB());

		$data = CJSON::encode($res);
		return $data;
	}

	public static function getRailwayBusList()
	{
		$selects = " city.`cty_id` as id, city.cty_display_name as text ";
		$sql	 = "SELECT $selects	FROM cities city WHERE 1 AND cty_active=1 AND (cty_poi_type=1 OR cty_poi_type=2) AND cty_service_active=1  $qry  ORDER BY cty_display_name ASC  ";
		$res	 = DBUtil::queryAll($sql, DBUtil::SDB());

		$data = CJSON::encode($res);
		return $data;
	}

	public static function getCityIdByCityNameStateName($cityName, $stateName)
	{
		$cityName	 = trim($cityName);
		$stateName	 = trim($stateName);
		$stateId	 = States::model()->getIdByName($stateName);
		$sql		 = "SELECT cty_id FROM `cities` WHERE cty_name LIKE '$cityName' AND `cty_state_id` = $stateId  LIMIT 1";
		$result		 = DBUtil::queryRow($sql, DBUtil::SDB());
		return $result['cty_id'];
	}

	public function setGeoId()
	{
		$placeObj			 = Stub\common\Place::init($this->cty_lat, $this->cty_long);
		$placeObj->bounds	 = $this->cty_bounds;
		$geoData			 = GeoData::getByPlaceObj($placeObj);
		if ($geoData)
		{
			$this->cty_geo_id = $geoData["gdt_id"];
		}
	}

	public static function updateFullName($city)
	{
		$sql = "UPDATE cities
				INNER JOIN geo_data gd1 ON cities.cty_geo_id=gd1.gdt_id AND cty_id=:city
				LEFT JOIN geo_data gd2 ON gd1.gdt_parent_geo_id=gd2.gdt_geo_id
				LEFT JOIN geo_data gd3 ON gd2.gdt_parent_geo_id=gd3.gdt_geo_id
				SET cities.cty_full_name=CONCAT(cty_name, IF(cty_name<>cities.cty_alias_name AND cty_alias_name<>'', CONCAT(' (', cty_alias_name,')'),''),
											IF(cty_id<>IFNULL(gd1.gdt_city_id,0) AND SOUNDEX(gd1.gdt_local_name)<>SOUNDEX(cities.cty_name)
													AND gd1.gdt_local_name NOT LIKE CONCAT(cty_name,'%') AND cty_name NOT LIKE CONCAT(gd1.gdt_local_name,'%')
													AND cty_name<>gd1.gdt_local_name, CONCAT(', ', gd1.gdt_local_name),''),
											IF(gd2.gdt_id IS NOT NULL AND cty_id<>IFNULL(gd2.gdt_city_id,0) AND SOUNDEX(gd2.gdt_local_name)<>SOUNDEX(cities.cty_name)
													AND gd2.gdt_local_name NOT LIKE CONCAT(cty_name,'%') AND cty_name<>gd2.gdt_local_name
													AND gd1.gdt_local_name<>gd2.gdt_local_name, CONCAT(', ', gd2.gdt_local_name),''),
											IF(gd3.gdt_id IS NOT NULL AND cty_id<>IFNULL(gd3.gdt_city_id,0) AND cty_name<>gd3.gdt_local_name
													AND gd2.gdt_local_name<>gd3.gdt_local_name AND gd1.gdt_local_name<>gd3.gdt_local_name, CONCAT(', ', gd3.gdt_local_name),''))
				";

		$numrows = DBUtil::execute($sql, ["city" => $city]);
		return $numrows;
	}

	public static function getCityShortDetailbyid($ctyid)
	{
		$param	 = ['ctyid' => $ctyid];
		$sql	 = "SELECT cty_id,cty_name, cty_full_name from cities WHERE cty_id=:ctyid";
		$row	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return $row;
	}

	public static function getShortNameByCity($city)
	{
		$cityName = (strlen($city) > 27) ? substr($city, 0, 27) . ".." : $city;
		return $cityName;
	}

	/**
	 * This function is used to get top city based on number of booking serverd last 10 days
	 * @return queryObject array
	 */
	public static function getTopCitiesByBookingCount()
	{
		$sql = "SELECT
                COUNT(booking.bkg_id) bkgcnt,
                booking.bkg_from_city_id,
                cities.cty_lat,
                cities.cty_long
                FROM booking
                INNER JOIN cities ON cities.cty_id= booking.bkg_from_city_id AND cities.cty_active=1 AND cities.cty_is_airport=0
                WHERE booking.bkg_pickup_date BETWEEN DATE_SUB(NOW(),INTERVAL 10 DAY ) AND NOW() AND booking.bkg_status IN (6,7)
                GROUP BY booking.bkg_from_city_id
                ORDER BY bkgcnt DESC
                LIMIT 0,25";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * This function is used to return  city id from  latitude, longitude
	 * @param type $latitude
	 * @param type $longtitude
	 * @return cityId
	 */
	public static function getCityByLatLng($latitude, $longitude)
	{
		$cityId		 = null;
		$sourcePlace = Stub\common\Place::init($latitude, $longitude);
		$ctyModel	 = Cities::getByGeoBounds($sourcePlace, 15);
		if ($ctyModel && $ctyModel->is_partial == 0)
		{
			$cityId = $ctyModel->cty_id;
			goto populateDestionationBound;
		}

		$ctyModel = Cities::getByNearestBound($sourcePlace);
		if ($ctyModel && $ctyModel->is_partial == 0)
		{
			$cityId = $ctyModel->cty_id;
			goto populateDestionationBound;
		}
		populateDestionationBound:
		return $cityId;
	}

	public function isPOI()
	{
		return ($this->cty_is_airport == 1 || $this->cty_is_poi == 1);
	}

	public static function getAllCity()
	{
		$sql = "SELECT cty_id FROM cities WHERE 1 AND cty_active=1";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getCtyNameById($ids)
	{
		$sql = "SELECT GROUP_CONCAT(cty_display_name) FROM `cities` WHERE cty_id IN ($ids) AND  cty_active = '1'";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	public static function getCityListByStateid($stateId = '', $cityId = '', $searchTxt = '')
	{
		$key		 = "getCityListByStateid1_{$stateId}_{$cityId}_{$searchTxt}";
		$citiesList	 = Yii::app()->cache->get($key);
		if ($citiesList !== false)
		{
			goto result;
		}
		$params	 = [];
		$where	 = '';
		if ($stateId > 0)
		{
			$params['sttid'] = $stateId;
			$where			 .= " AND cty_state_id=:sttid";
		}
		if ($cityId > 0)
		{
			$params['ctyid'] = $cityId;
			$where			 .= " AND cty_id=:ctyid";
		}
		$searchTxt = trim($searchTxt);
		if ($searchTxt != '')
		{
			$params['searchTxt'] = $searchTxt;
			$where				 .= " AND cty_name LIKE '%:searchTxt%'";
		}
		$sql	 = "SELECT cty_id, cty_name FROM cities  WHERE cty_active = 1 $where";
		$data	 = DBUtil::query($sql, DBUtil::SDB(), $params);

		$cities = array();
		foreach ($data as $key => $value)
		{
			$cities[$value ['cty_id']] = $value['cty_name'];
		}
		$citiesList = $cities;

		Yii::app()->cache->set($key, $citiesList, 60 * 60 * 24 * 1, new CacheDependency("Cities"));
		result:
		return $citiesList;
	}

	public static function getByCode($code)
	{
		return DBUtil::queryRow("SELECT * FROM cities WHERE cty_code = '{$code}' ORDER BY cty_is_airport DESC");
	}

	/**
	 * 
	 * @param type $id | city Id
	 * @return type |array
	 */
	public function getLatLngByCity($id)
	{
		$model	 = Cities::model()->findByPk($id);
		$arrCity = ["lat" => $model->cty_lat, "lng" => $model->cty_long];
		return $arrCity;
	}

	public function getCityList($stateid)
	{
		$criteria			 = new CDbCriteria();
		$criteria->select	 = "cty_id, cty_name";
		$criteria->compare('cty_state_id', $stateid);
		$criteria->compare('cty_active', 1);
		$criteria->order	 = "cty_name";
		$comments			 = Cities::model()->findAll($criteria);
		return $comments;
	}

	public function getCityList2($stateid)
	{
		$citiesModels	 = Cities::model()->getCityList($stateid);
		$arrSkill		 = Filter::ObjectArrayToArrayList($citiesModels, "cty_id", "cty_name");
		return $arrSkill;
	}

	/**
	 * 
	 * @param type $stateId
	 * @param type $cityName
	 * @return type
	 */
	public static function getCityListByState($stateId, $cityName = '')
	{
		$cityIntName = "";
		if ($cityName != '')
		{
			$cityIntName = " AND cities.cty_name LIKE '$cityName%' ";
		}
		$param	 = ['stateId' => $stateId];
		$sql	 = "SELECT stt_id,cty_id,cty_name 
				FROM states 
				LEFT JOIN cities 
					ON cities.cty_state_id = states.stt_id 
					AND cities.cty_active=1 AND cities.cty_is_airport=0 AND cities.cty_is_poi=0
				WHERE states.stt_id=:stateId $cityIntName";
		return DBUtil::query($sql, DBUtil::SDB(), $param);
	}
}
