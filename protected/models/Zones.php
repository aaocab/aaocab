<?php

/**
 * This is the model class for table "zones".
 *
 * The followings are the available columns in table 'zones':
 * @property integer $zon_id
 * @property string $zon_name
 * @property double $zon_lat
 * @property double $zon_long
 * @property integer $zon_price_rule
 * @property integer $zon_home_median_sedan
 * @property integer $zon_home_median_compact
 * @property integer $zon_home_median_suv
 * @property integer $zon_home_sedan_own_rate
 * @property integer $zon_home_compact_own_rate
 * @property integer $zon_home_suv_own_rate
 * @property integer $zon_is_promo_code_apply
 * @property integer $zon_is_promo_gozo_coins_apply
 * @property integer $zon_is_cod_apply
 * @property integer $zon_excluded_cabtypes
 * @property integer $zon_active
 * @property string $zon_created_at
 * @property String $zone_log
 * @property integer $zon_hilly_factor
 * The followings are the available model relations:
 * @property ZoneCities[] $zoneCities
 */
class Zones extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public $vnd_city, $search_text, $zon_info_source, $zone_log, $zon_bkg_create_date1, $zon_bkg_create_date2, $mdcDate1, $mdcDate2, $dateType, $bookingType, $sourcezone, $destinaitonzone, $fromcity, $tocity;

	public function tableName()
	{
		return 'zones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('zon_name', 'required'),
			array('zon_active,zon_is_promo_code_apply, zon_is_promo_gozo_coins_apply, zon_is_cod_apply', 'numerical', 'integerOnly' => true),
			array('zon_lat, zon_long', 'numerical'),
			array('vnd_city', 'validateZoneCity', 'on' => 'zoneCityAdd'),
			array('zon_name', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('zon_id, zon_name, zon_active,zon_price_rule,zon_lat, zon_long, zon_created_at,vnd_city,search_text,zon_home_median_sedan,zon_home_median_compact,zon_home_median_suv,zon_home_sedan_own_rate,zon_home_compact_own_rate,zon_home_suv_own_rate,zon_info_source,zon_is_promo_code_apply, zon_is_promo_gozo_coins_apply, zon_is_cod_apply, zon_excluded_cabtypes, zone_log,zon_included_cabtires,zon_included_cabCategories,zon_included_cabmodels', 'safe'),
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
			'zoneCity' => array(self::HAS_MANY, 'ZoneCities', 'zct_zon_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'zon_id'						 => 'Zon',
			'zon_name'						 => 'Zone Name',
			'zon_home_median_sedan'			 => 'Proposed Sedan Rate',
			'zon_home_median_compact'		 => 'Proposed Sedan Compact',
			'zon_home_median_suv'			 => 'Proposed Sedan Suv',
			'zon_home_sedan_own_rate'		 => 'Own Sedan Rate',
			'zon_home_compact_own_rate'		 => 'Own Compact Rate',
			'zon_home_suv_own_rate'			 => 'Own suv Rate',
			'zon_is_promo_code_apply'		 => 'Apply Promo',
			'zon_is_promo_gozo_coins_apply'	 => 'Apply Promo Gozo Coins',
			'zon_is_cod_apply'				 => 'Apply COD',
			'zon_excluded_cabtypes'			 => 'Excluded CabTypes',
			'zon_active'					 => '1 => Active, 0 => Inactive',
			'zon_created_at'				 => 'Zon Created At',
			'zon_info_source'				 => 'Select Source',
			'zon_price_rule'				 => 'Zone Price Rule',
			'zon_lat'						 => 'Latitude',
			'zon_long'						 => 'Longitude',
			'zon_city'						 => 'Zone Wise City'
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

		$criteria->compare('zon_id', $this->zon_id);
		$criteria->compare('zon_name', $this->zon_name, true);
		$criteria->compare('zon_active', $this->zon_active);
		$criteria->compare('zon_created_at', $this->zon_created_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function validateZoneCity($attribute, $params)
	{
		if ($this->vnd_city == '' || $this->vnd_city == null)
		{
			$this->addError($attribute, 'You Should Select Atleast One Zone City.');
			return false;
		}

		if (($this->zon_name != '') && ($this->zon_name != null))
		{
			$condition = "";
			if ($this->zon_id != '')
			{
				$condition = " and zon_id !={$this->zon_id}";
			}
			$sql	 = "SELECT zon_id FROM zones WHERE zon_name = '$this->zon_name'" . $condition;
			$record	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar();

			if ($record > 0)
			{
				$this->addError('zon_name', 'You Have Entered Duplicate Zone.');
				return false;
			}
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Zones the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getZoneCitiesList($searchTxt)
	{
		$sql = "SELECT a.`zon_id`,a.`zon_name`,GROUP_CONCAT(c.`cty_name` separator ', ') as `cty_names`"
				. " FROM zones a "
				. " LEFT JOIN zone_cities b ON b.zct_zon_id=a.zon_id AND b.zct_active = 1 "
				. " LEFT JOIN cities c ON c.cty_id=b.zct_cty_id WHERE 1=1 AND a.zon_active='1'";
		if ($searchTxt != '')
		{
			$sql .= " AND  (a.zon_name LIKE '%" . $searchTxt . "%' || c.cty_name LIKE '%" . $searchTxt . "%' )";
		}
		$sql			 .= " GROUP BY a.zon_id";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['zon_name', 'cty_names'],
				'defaultOrder'	 => 'zon_name DESC'], 'pagination'	 => ['pageSize' => 25],
		]);
		return $dataprovider;
	}

	public function getVolumeTrendByZone($zonId, $source = 2, $type = '')
	{
		$condition = "";
		if ($this->zon_bkg_create_date1 != "" && $this->zon_bkg_create_date2 != "")
		{
			$condition = " AND booking.bkg_create_date BETWEEN '$this->zon_bkg_create_date1' AND '$this->zon_bkg_create_date2' ";
		}
		$sql		 = "SELECT zones.zon_name, SUM(IF(booking.bkg_status IN (6,7),1,0))  as count_completed,
                    SUM(IF(booking.bkg_status=9,1,0)) as count_cancelled,
                    SUM(IF(booking.bkg_status IN (6,7),booking_invoice.bkg_total_amount,0)) as gmv_amount,
                    CONCAT(DATE_FORMAT(MIN(booking.bkg_create_date),'%d %M %Y'),'-',DATE_FORMAT(MAX(booking.bkg_create_date),'%d %M %Y')) as month_range,
                    DATE_FORMAT(MIN(booking.bkg_create_date),'%M %Y') as show_date
                    FROM `booking` INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id=booking.bkg_id";
		$sqlCount	 = "    SELECT zones.zon_name
                        FROM `booking` INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id=booking.bkg_id";
		if ($source == 2)
		{
			$sql		 .= "  JOIN `zone_cities` ON zone_cities.zct_cty_id=booking.bkg_from_city_id";
			$sqlCount	 .= "  JOIN `zone_cities` ON zone_cities.zct_cty_id=booking.bkg_from_city_id";
		}
		else if ($source == 1)
		{
			$sql		 .= "  JOIN `zone_cities` ON zone_cities.zct_cty_id=booking.bkg_to_city_id";
			$sqlCount	 .= "  JOIN `zone_cities` ON zone_cities.zct_cty_id=booking.bkg_to_city_id";
		}
		$sql		 .= "  JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id WHERE booking.bkg_status IN (6,7,9)  AND booking.bkg_active=1 $condition and zones.zon_name is not null";
		$sqlCount	 .= "  JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id WHERE booking.bkg_status IN (6,7,9)  AND booking.bkg_active=1 $condition and zones.zon_name is not null";
		if ($zonId > 0)
		{
			$sql		 .= " AND zones.zon_id=$zonId";
			$sqlCount	 .= " AND zones.zon_id=$zonId";
		}
		$sql		 .= " GROUP BY MONTH(booking.bkg_create_date),YEAR(booking.bkg_create_date),zones.zon_name";
		$sqlCount	 .= " GROUP BY MONTH(booking.bkg_create_date),YEAR(booking.bkg_create_date),zones.zon_name";

		if ($type == 'command')
		{
			$sql .= " Order by zones.zon_name ASC,month_range ASC";
			return DBUtil::queryAll($sql);
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['zon_id', 'zon_name', 'count_completed', 'count_cancelled', 'gmv_amount', 'show_date'],
					'defaultOrder'	 => 'zones.zon_name ASC,month_range ASC'], 'pagination'	 => ['pageSize' => 20],
			]);
			return $dataprovider;
		}
	}

	public function getVendorRateByZone($zonId)
	{
		$sql				 = "SELECT a.zon_id,a.zon_name,b.vnd_sedan_rate "
				. " FROM zones a "
				. " LEFT JOIN vendors b ON b.vnd_home_zone=a.zon_id WHERE 1=1 AND a.zon_id='" . $zonId . "' AND b.vnd_sedan_rate IS NOT NULL ORDER BY b.vnd_sedan_rate ASC";
		$recordHomeSeadan	 = DBUtil::queryAll($sql);
		foreach ($recordHomeSeadan as $val)
		{
			$sedanVal[] = $val['vnd_sedan_rate'];
		}

		$sql				 = "SELECT a.zon_id,a.zon_name,b.vnd_compact_rate "
				. " FROM zones a "
				. " LEFT JOIN vendors b ON b.vnd_home_zone=a.zon_id WHERE 1=1 AND a.zon_id='" . $zonId . "' AND b.vnd_compact_rate IS NOT NULL ORDER BY b.vnd_compact_rate ASC";
		$recordHomeCompact	 = DBUtil::queryAll($sql);
		foreach ($recordHomeCompact as $val)
		{
			$compactVal[] = $val['vnd_compact_rate'];
		}

		$sql			 = "SELECT a.zon_id,a.zon_name,b.vnd_suv_rate "
				. " FROM zones a "
				. " LEFT JOIN vendors b ON b.vnd_home_zone=a.zon_id WHERE 1=1 AND a.zon_id='" . $zonId . "' AND b.vnd_suv_rate IS NOT NULL ORDER BY b.vnd_suv_rate ASC";
		$recordHomeSuv	 = DBUtil::queryAll($sql);
		foreach ($recordHomeSuv as $val)
		{
			$suvVal[] = $val['vnd_suv_rate'];
		}
		$data['home_median_compact'] = $this->calculateMedian($compactVal);
		$data['home_median_sedan']	 = $this->calculateMedian($sedanVal);
		$data['home_median_suv']	 = $this->calculateMedian($suvVal);
		return $data;
	}

	public function getSource()
	{
		$source = [
			'1'	 => 'Destination Zone',
			'2'	 => 'Source Zone'
		];
		return $source;
	}

	public function calculateMedian($arr)
	{
		$count = count($arr); //total numbers in array
		if ($count > 0)
		{
			sort($arr);
		}
		$middleval = floor(($count - 1) / 2); // find the middle value, or the lowest middle value
		if ($count % 2)
		{
			$median = $arr[$middleval];
		}
		else
		{
			$low	 = $arr[$middleval];
			$high	 = $arr[$middleval + 1];
			$median	 = (($low + $high) / 2);
		}
		return $median;
	}

	public function getZoneList($query = null)
	{
		$criteria			 = new CDbCriteria();
		$criteria->select	 = "zon_id, zon_name";
		$criteria->compare('zon_active', 1);
		if ($query != null)
		{
			$criteria->compare('zon_name', $query);
		}
		$criteria->order = "zon_name";
		$comments		 = Zones::model()->findAll($criteria);
		return $comments;
	}

	public function getAll()
	{
		$criteria		 = new CDbCriteria;
		$criteria->order = "zon_name";
		return $this->findAll($criteria);
	}

	public function getcheckCities()
	{
		$criteria		 = new CDbCriteria;
		$criteria->order = "zon_name";
		return $this->findAll($criteria);
	}

	public function getZoneList1($all = null)
	{
		$zoneModels	 = Zones::model()->getZoneList();
		$arrSkill	 = array();

		foreach ($zoneModels as $sklModel)
		{
			$arrSkill[$sklModel->zon_id] = $sklModel->zon_name;
		}
		return $arrSkill;
	}

	/*
	 * param $all - for all records
	 * this function populates all zonelist with Statename related to that zone.
	 */

	public function getZoneListforPriceSurge($all = null)
	{
		if ($all != null)
		{
			$cond = " AND z1.zon_name like '%" . $all . "%'";
		}
		$sql		 = "SELECT z1.zon_id as zon_id,z1.zon_name as zon_name,GROUP_CONCAT(DISTINCT(s.stt_name)) as state_name
						FROM `zones` AS z1
						INNER JOIN zone_cities AS zc1 ON zc1.zct_zon_id = z1.zon_id AND zc1.zct_active = 1 
						INNER JOIN cities AS c1 ON zc1.zct_cty_id = c1.cty_id AND c1.cty_active = 1 AND c1.cty_service_active = 1
						INNER JOIN states AS s ON c1.cty_state_id = s.stt_id
						WHERE z1.zon_active = 1 $cond
						GROUP BY z1.zon_id";
		$zoneData	 = DBUtil::query($sql);
		return $zoneData;
	}

	public function getJSON($all = '')
	{
		$arrZone = $this->getZoneList1();
		$arrJSON = [];
		if ($all != '')
		{
			$arrJSON[] = array_merge(array("id" => '0', "text" => "All"), $arrJSON);
		}
		foreach ($arrZone as $key => $val)
		{
			if ($val != '')
			{
				$arrJSON[] = array("id" => $key, "text" => $val);
			}
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getCabRateByCityId($cityId)
	{
		$sql			 = "SELECT a.* FROM `zones` a
                LEFT JOIN `zone_cities` b ON a.zon_id=b.`zct_zon_id`
                WHERE b.`zct_cty_id`=" . $cityId . "";
		$zoneRateData	 = DBUtil::queryRow($sql);
		$compactRate	 = $zoneRateData['zon_home_compact_own_rate'] > 0 ? $zoneRateData['zon_home_compact_own_rate'] : $zoneRateData['zon_home_median_compact'];
		$suvRate		 = $zoneRateData['zon_home_suv_own_rate'] > 0 ? $zoneRateData['zon_home_suv_own_rate'] : $zoneRateData['zon_home_median_suv'];
		$sedanRate		 = $zoneRateData['zon_home_sedan_own_rate'] > 0 ? $zoneRateData['zon_home_sedan_own_rate'] : $zoneRateData['zon_home_median_sedan'];
		$rateList		 = array('1' => $compactRate, '2' => $suvRate, '3' => $sedanRate);
		return $rateList;
	}

	public function getNearestZonebyCity($cityId, $maxdistance = 30)
	{
		$having = "";
		if ($maxdistance != null)
		{
			$having = " having zn_distance <= $maxdistance";
		}

		$sql		 = "SELECT c1.cty_id, c1.cty_name, zones.zon_id, zones.zon_name, zones.zon_price_rule,
							ROUND(SQRT( POW(69.1 * (c1.cty_lat - zones.zon_lat), 2) + POW(69.1 * (zones.zon_long - c1.cty_long) * COS(c1.cty_lat / 57.3), 2)),2) as zn_distance
						FROM cities c1
						INNER JOIN  zone_cities ON zone_cities.zct_cty_id=c1.cty_id AND zct_active=1
						INNER JOIN zones ON zone_cities.zct_zon_id=zones.zon_id AND zon_active=1
						WHERE c1.cty_id = $cityId
						$having
						ORDER BY zn_distance ASC
						LIMIT 1";
		$zoneData	 = DBUtil::queryRow($sql);
		return $zoneData;
	}

	public function getNearestZonesbyCitySummary($cityId, $maxdistance = 200)
	{
		$sql		 = "SELECT c1.cty_id,c1.cty_name,
                count(DISTINCT zone_cities.zct_zon_id) cnt_zid,
                count(DISTINCT zones.zon_name) cnt_znm,
                GROUP_CONCAT(DISTINCT zone_cities.zct_zon_id) zoneIds,
                GROUP_CONCAT(DISTINCT zones.zon_name) as zoneNames FROM cities c1
                INNER JOIN zones
                ON SQRT( POW(69.1 * (c1.cty_lat - zones.zon_lat), 2) + POW(69.1 * (zones.zon_long - c1.cty_long) * COS(c1.cty_lat / 57.3), 2)) <= $maxdistance
                INNER JOIN zone_cities ON zone_cities.zct_zon_id=zones.zon_id
                where c1.cty_id = $cityId";
		$zoneData	 = DBUtil::queryRow($sql);
		return $zoneData;
	}

	public function getNearestZonesbyCity($cityId, $maxdistance = 200)
	{
		$sql		 = "SELECT
                DISTINCT zone_cities.zct_zon_id zoneIds,
                zones.zon_name as zoneNames FROM cities c1
                INNER JOIN zones
                ON SQRT( POW(69.1 * (c1.cty_lat - zones.zon_lat), 2) + POW(69.1 * (zones.zon_long - c1.cty_long) * COS(c1.cty_lat / 57.3), 2)) <= $maxdistance
                INNER JOIN zone_cities ON zone_cities.zct_zon_id=zones.zon_id
                where c1.cty_id = $cityId";
		$zoneData	 = DBUtil::queryAll($sql);
		return $zoneData;
	}

	public function getNearestZonesbyCityLatLong($ctylat, $ctylong, $maxdistance = 30)
	{
		$sql		 = "SELECT
                DISTINCT zone_cities.zct_zon_id zoneIds,
                zones.zon_name as zoneNames
                FROM zones
                 INNER JOIN zone_cities ON zone_cities.zct_zon_id=zones.zon_id
                WHERE
                 SQRT( POW(69.1 * (zones.zon_lat - $ctylat), 2) + POW(69.1 * ($ctylong-zones.zon_long ) * COS(zones.zon_lat / 57.3), 2)) <= $maxdistance                     ";
		$zoneData	 = DBUtil::queryAll($sql);
		return $zoneData;
	}

	public function getJSONCorptozone()
	{
		$sql		 = "SELECT zon_id,zon_name FROM zones WHERE zon_id IN(SELECT DISTINCT `zct_zon_id` from zone_cities where `zct_cty_id` IN (SELECT DISTINCT bkg_to_city_id from booking bkg
                WHERE bkg_active=1 AND date(bkg_pickup_date)>='2016-12-01' AND  bkg_status IN (3,5,6,7) AND  (FIND_IN_SET(1,bkg_tags) OR bkg_from_city_id IN(SELECT zct_cty_id FROM zone_cities WHERE zct_zon_id=420) OR bkg_to_city_id IN(SELECT zct_cty_id FROM zone_cities WHERE zct_zon_id=420))))";
		$zoneModels	 = $this->findAllBySql($sql);
		$arrSkill	 = CHtml::listData($zoneModels, 'zon_id', 'zon_name');
		$arrJSON	 = [];
		foreach ($arrSkill as $key => $val)
		{
			if ($val != '')
			{
				$arrJSON[] = array("id" => $key, "text" => $val);
			}
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getZoneByFromBooking()
	{
		$rows = DBUtil::queryAll("SELECT zon_id,zon_name FROM zones WHERE zon_id IN(SELECT DISTINCT `zct_zon_id` from zone_cities where `zct_cty_id` IN (SELECT DISTINCT cty_id from cities WHERE cty_service_active=1 AND cty_active=1))");
		foreach ($rows as $row)
		{
			$arrBillingType[] = array("id" => $row['zon_id'], "text" => $row['zon_name']);
		}
		$data = CJSON::encode($arrBillingType);
		return $data;
	}

	public function getZoneArrByFromBooking()
	{
		$rows	 = DBUtil::queryAll("SELECT zon_id,zon_name FROM zones WHERE zon_id IN(SELECT DISTINCT `zct_zon_id` from zone_cities where `zct_cty_id` IN (SELECT DISTINCT cty_id from cities WHERE cty_service_active=1 AND cty_active=1))", DBUtil::SDB(), [], true, 60 * 60 * 24, CacheDependency::Type_Cities);
		$zArr	 = [];
		foreach ($rows as $row)
		{
			$zArr[$row['zon_id']] = $row['zon_name'];
		}
		return $zArr;
	}

	public function getAllZoneListforApp($zonId)
	{
		if ($zonId != '')
		{
			$sql1		 = 'SELECT z2.zon_id as zoneId, z2.zon_name as zoneName, SQRT( POW(69.1 * (z1.zon_lat - z2.zon_lat), 2) + POW(69.1 * (z2.zon_long - z1.zon_long) * COS(z1.zon_lat / 57.3), 2)) as distance
                    FROM zones z1
                    INNER JOIN zones z2 ON z2.zon_active = 1
                    WHERE z1.zon_active = 1 AND z1.zon_id = ' . $zonId . ' HAVING distance < 400';
			$zoneData1	 = DBUtil::command($sql1)->queryAll();
			$data1		 = array();
			foreach ($zoneData1 as $key1 => $val1)
			{
				$data1[] = array("id" => $val1['zoneId'], "name" => $val1['zoneName']);
			}
			$sql2		 = 'SELECT z2.zon_id as zoneId, z2.zon_name as zoneName, SQRT( POW(69.1 * (z1.zon_lat - z2.zon_lat), 2) + POW(69.1 * (z2.zon_long - z1.zon_long) * COS(z1.zon_lat / 57.3), 2)) as distance
                    FROM zones z1
                    INNER JOIN zones z2 ON z2.zon_active = 1
                    WHERE z1.zon_active = 1 AND z1.zon_id = ' . $zonId . ' HAVING distance >= 400';
			$zoneData2	 = DBUtil::command($sql2)->queryAll();
			foreach ($zoneData2 as $key2 => $val2)
			{
				$data1[] = array("id" => $val2['zoneId'], "name" => $val2['zoneName']);
			}
			return $data1;
		}
		else
		{
			$criteria			 = new CDbCriteria();
			$criteria->select	 = "zon_id, zon_name";
			$criteria->compare('zon_active', 1);
			$criteria->order	 = 'zon_name';
			$arrZone			 = Zones::model()->findAll($criteria);
			$data				 = array();
			foreach ($arrZone as $key => $val)
			{
				$data[] = array("id" => $val->zon_id, "name" => $val->zon_name);
			}
			return $data;
		}
	}

	/**
	 * @deprecated since version 07-01-2020
	 */
	public function getIncludedExcludedZoneListforAppOld($vndId)
	{
		$qry = 'SELECT DISTINCT zones.zon_id id,zones.zon_name name 
                    FROM zones
                    INNER JOIN `vendor_pref` ON find_in_set(zones.zon_id,vendor_pref.vnp_accepted_zone)
                    WHERE vendor_pref.vnp_vnd_id = ' . $vndId;

		$dataIncluded = DBUtil::queryAll($qry, DBUtil::SDB());

		$sql = 'SELECT DISTINCT vzone.zon_id id,vzone.zon_name name 
                    FROM zones vzone    
                    INNER JOIN `vendor_pref` ON NOT find_in_set(vzone.zon_id,vendor_pref.vnp_accepted_zone)
                    INNER JOIN `vendors` ON vnd_id = vnp_vnd_id AND vendors.vnd_id = vendors.vnd_ref_code
                    INNER JOIN `contact` ON ctt_id = vnd_contact_id
                    INNER JOIN cities vcity ON contact.ctt_city = vcity.cty_id AND round(6371 
					* acos(cos(radians(vcity.cty_lat)) 
					* cos(radians(vzone.zon_lat)) 
					* cos(radians(vzone.zon_long) - radians(vcity.cty_long)) 
					+ sin(radians(vcity.cty_lat)) 
					* sin(radians(vzone.zon_lat))),2) <=300
                    WHERE vendor_pref.vnp_vnd_id = ' . $vndId;

		$data	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $dataArr = ['included_zones' => $dataIncluded, 'excluded_zones' => $data];
	}

	public function getIncludedExcludedZoneListforApp($vndId)
	{
		if (($vndId == "" || $vndId == null))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$qry = 'SELECT DISTINCT zones.zon_id id,zones.zon_name name 
                    FROM zones
                    INNER JOIN `vendor_pref` ON find_in_set(zones.zon_id,vendor_pref.vnp_accepted_zone)
                    WHERE vendor_pref.vnp_vnd_id = ' . $vndId;

		$dataIncluded	 = DBUtil::queryAll($qry, DBUtil::SDB());
		$data			 = self::getValidZoneList($vndId);
		return $dataArr		 = ['included_zones' => $dataIncluded, 'excluded_zones' => $data];
	}

	public static function getIncExcZoneListForVendor($vndId)
	{
		if (($vndId == "" || $vndId == null))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$qry = 'SELECT DISTINCT zones.zon_id id,zones.zon_name name 
                    FROM zones
                    INNER JOIN `vendor_pref` ON find_in_set(zones.zon_id,vendor_pref.vnp_accepted_zone)
                    WHERE vendor_pref.vnp_vnd_id = ' . $vndId;

		$dataIncluded	 = DBUtil::query($qry, DBUtil::SDB());
		$data			 = self::getVendorZoneList($vndId);
		return $dataArr		 = ['included_zones' => $dataIncluded, 'excluded_zones' => $data];
	}

	public function getNearestZonePriceRuleIdbyCity($cityId)
	{
		$sql		 = "SELECT   c1.cty_id
                        , c1.cty_name
                        , zones.zon_id
                        , zones.zon_name
                        , zones.zon_price_rule
                        , min(ROUND(SQRT(POW(69.1 * (c1.cty_lat - zones.zon_lat), 2) + POW(69.1 * (zones.zon_long - c1.cty_long) * COS(c1.cty_lat / 57.3), 2)), 2)) AS zn_distance
               FROM     cities c1
                        INNER JOIN zone_cities ON zone_cities.zct_cty_id = c1.cty_id
                        INNER JOIN zones ON zone_cities.zct_zon_id = zones.zon_id
                        WHERE c1.cty_id = $cityId
               GROUP BY c1.cty_id";
		$zoneData	 = DBUtil::queryRow($sql);
		return $zoneData['zon_price_rule'];
	}

	public function getZoneById($id)
	{
		return $this->model()->findByPk($id)->zon_name;
	}

	public function getZoneLog($zoneid)
	{
		$qry	 = "select zon_log from zones where zon_id = " . $zoneid;
		$logList = DBUtil::queryRow($qry);
		return $logList;
	}

	public function getZoneListByCityId($lat, $long, $maxDistance = 0)
	{
		$sql		 = "SELECT zon_id,zon_name,zon_lat, zon_long, SQRT(
				POW(69.1 * (zon_lat - $lat), 2) +
				POW(69.1 * ($long - zon_long) * COS(zon_lat / 57.3), 2)) AS distance
			FROM zones HAVING distance < 500 ORDER BY distance";
		$zoneData	 = DBUtil::queryAll($sql);
		$zArr		 = [];
		foreach ($zoneData as $row)
		{
			$zArr[$row['zon_id']] = $row['zon_id'];
		}
		return $zArr;
	}

	public function getByCityId($cityId)
	{
		$key	 = "zoneCities_{$cityId}";
		$data	 = Yii::app()->cache->get($key);
		if ($data == false)
		{
			$sql = "SELECT GROUP_CONCAT(zones.zon_id) as zones FROM cities c1
					INNER JOIN zone_cities ON zone_cities.zct_cty_id = c1.cty_id AND zct_active=1
					INNER JOIN zones ON zone_cities.zct_zon_id = zones.zon_id AND zon_active=1
					WHERE c1.cty_id = $cityId";

			$data = DBUtil::queryScalar($sql);
			Yii::app()->cache->set($key, $data, 86400, new CacheDependency('zones'));
		}
		return $data;
	}

	public function getIdByName($zoneName)
	{
		$sql	 = "SELECT zon_id FROM zones WHERE zon_name= '$zoneName'";
		$data	 = DBUtil::queryRow($sql);
		return $data['zon_id'];
	}

	/**
	 * This is used for calculating zone list from home zone less then set distance
	 * @param int $zonId
	 * @return (array) $acceptedZoneData
	 */
	public static function getValidZoneListForAdmin($zonId = NULL)
	{
		$acceptedZoneData	 = array();
		$distance			 = (int) Config::get('vendor.maxDistanceZoneAllowed');
		if ($zonId != '')
		{
			$params				 = ['zonId' => $zonId, 'distance' => $distance];
			$acceptedZoneSql	 = 'SELECT z2.zon_id as zoneId, z2.zon_name as zoneName, SQRT( POW(69.1 * (z1.zon_lat - z2.zon_lat), 2) + POW(69.1 * (z2.zon_long - z1.zon_long) * COS(z1.zon_lat / 57.3), 2)) as distance
                    FROM zones z1
                    INNER JOIN zones z2 ON z2.zon_active = 1
                    WHERE z1.zon_active = 1 AND z1.zon_id = :zonId HAVING distance < :distance';
			$acceptedzoneData	 = DBUtil::query($acceptedZoneSql, DBUtil::SDB(), $params);
			foreach ($acceptedzoneData AS $data)
			{
				$acceptedZoneData[] = $data['zoneId'];
			}
		}
		return $acceptedZoneData;
	}

	/**
	 * This function is used for calculating zone list less than set distance from home zone 
	 * @param (int) $vndId
	 * @return array $data
	 * @throws Exception
	 */
	public static function getValidZoneList($vndId = NULL)
	{
		$qry1 = 'SELECT DISTINCT
					(SELECT vzone.zon_lat from zones vzone WHERE  vzone.zon_id = vendor_pref.vnp_home_zone) as home_zon_lat

				FROM zones vzone
				INNER JOIN vendor_pref ON NOT find_in_set(vzone.zon_id,vendor_pref.vnp_accepted_zone)
				WHERE vendor_pref.vnp_vnd_id =' . $vndId;

		$result			 = DBUtil::queryAll($qry1, DBUtil::SDB());
		$home_zon_lat	 = $result[0][home_zon_lat];

		$qry2 = 'SELECT DISTINCT
			(SELECT vzone.zon_long from zones vzone WHERE  vzone.zon_id = vendor_pref.vnp_home_zone) as home_zon_long

		FROM zones vzone
		INNER JOIN vendor_pref ON NOT find_in_set(vzone.zon_id,vendor_pref.vnp_accepted_zone)
		WHERE vendor_pref.vnp_vnd_id = ' . $vndId;

		$result			 = DBUtil::queryAll($qry2, DBUtil::SDB());
		$home_zon_long	 = $result[0][home_zon_long];
		if (($home_zon_long == "" || $home_zon_long == null) || ($home_zon_lat == "" || $home_zon_lat == null))
		{
			throw new Exception("Home zone not set", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$distance	 = (int) Config::get('vendor.maxDistanceZoneAllowed');
		$params		 = ['vndId' => $vndId, 'distance' => $distance, 'homeZonLat' => $home_zon_lat, 'homeZonLong' => $home_zon_long];
		$sql		 = 'SELECT
				vzone.zon_id id,  vzone.zon_name name
			FROM zones vzone
			INNER JOIN vendor_pref ON NOT find_in_set(vzone.zon_id,vendor_pref.vnp_accepted_zone)
			AND round(6371
				  * acos( cos( radians(:homeZonLat) )
						  * cos(  radians( vzone.zon_lat )   )
						  * cos(  radians( vzone.zon_long ) - radians(:homeZonLong) )
						+ sin( radians(:homeZonLat))
						  * sin( radians( vzone.zon_lat ) )
						)) <= :distance
			WHERE vendor_pref.vnp_vnd_id = :vndId';

		$data = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * This function is used for calculating zone list less than set distance from home zone 
	 * @param (int) $vndId
	 * @return array $data
	 * @throws Exception
	 */
	public static function getVendorZoneList($vndId = NULL)
	{
		$qry1 = 'SELECT vzone.zon_lat,vzone.zon_long from zones vzone 
				INNER JOIN vendor_pref ON vzone.zon_id = vnp_home_zone
				WHERE vendor_pref.vnp_vnd_id = ' . $vndId;

		$result = DBUtil::queryRow($qry1, DBUtil::SDB());
		if (!$result)
		{
			throw new Exception("Home zone not set", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$distance	 = (int) Config::get('vendor.maxDistanceZoneAllowed');
		$params		 = ['vndId' => $vndId, 'distance' => $distance, 'homeZonLat' => $result['zon_lat'], 'homeZonLong' => $result['zon_long']];
		$sql		 = 'SELECT
				vzone.zon_id id,  vzone.zon_name name
			FROM zones vzone
			INNER JOIN vendor_pref ON NOT find_in_set(vzone.zon_id,vendor_pref.vnp_accepted_zone)
			AND round(6371
				  * acos( cos( radians(:homeZonLat) )
						  * cos(  radians( vzone.zon_lat )   )
						  * cos(  radians( vzone.zon_long ) - radians(:homeZonLong) )
						+ sin( radians(:homeZonLat))
						  * sin( radians( vzone.zon_lat ) )
						)) <= :distance
			WHERE vendor_pref.vnp_vnd_id = :vndId';

		$data = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public function getZoneSupplyDensity($model, $date1, $date2, $zon_id, $command = DBUtil::ReturnType_Provider)
	{
		$batchSize = 20;

		$where = '';
		if ($zon_id > 0)
		{
			$where = ' AND zones.zon_id = ' . $zon_id;
		}
		$zoneInfo	 = "SELECT zones.zon_name, zones.zon_id
                            FROM `zones` 
                            JOIN `zone_cities` ON zone_cities.zct_zon_id=zones.zon_id 
                            WHERE zones.zon_name is not null $where GROUP BY zones.zon_id LIMIT $batchSize";
		$sqlQuery	 = DBUtil::queryAll($zoneInfo);

		if ($sqlQuery != '')
		{
			foreach ($sqlQuery as $key => $val)
			{
				$sql = "SELECT COUNT(DISTINCT(vnd.vnd_id)) as active_vendors
                          FROM  vendors vnd
                          INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id
                          LEFT JOIN app_tokens apt ON apt.apt_entity_id = vnd.vnd_id AND apt.apt_user_type = 2
                          WHERE vnp.vnp_accepted_zone IN (" . $val['zon_id'] . ") AND vnd.vnd_active = 1 
                            AND apt.apt_last_login BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'";

				$sql2 = "SELECT COUNT(DISTINCT(vnd.vnd_id)) as home_zone_vendors
                          FROM vendors vnd 
                          INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id 
                          LEFT JOIN app_tokens apt ON apt.apt_entity_id = vnd.vnd_id AND apt.apt_user_type = 2 
                          WHERE vnp.vnp_home_zone IN (" . $val['zon_id'] . ") AND vnd.vnd_active = 1 
                            AND apt.apt_last_login BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'";

				$sqlQuery[$key]['active_vendors']	 = DBUtil::queryAll($sql, DBUtil::SDB())[0]['active_vendors']; //$modelPref[0]['active_vendors'];
				$sqlQuery[$key]['home_zone_vendors'] = DBUtil::queryAll($sql2, DBUtil::SDB())[0]['home_zone_vendors']; //$modelPref[0]['home_zone_vendors'];
			}
		}

		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($zoneInfo) abc", DBUtil::SDB());
			$dataprovider	 = new CArrayDataProvider($sqlQuery, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['zon_name', 'active_vendors', 'home_zone_vendors'],
					'defaultOrder'	 => 'zones.zon_name ASC'], 'pagination'	 => ['pageSize' => 20],
			]);
			return $dataprovider;
		}
		else
		{

			return $sqlQuery;
		}
	}

	public function getZoneSupplyDensityVendorsList($model, $zoneid, $type)
	{
		$date1	 = date('Y-m-d', strtotime("-90 days"));
		$date2	 = date('Y-m-d');

		if ($type == 1)
		{
			$where = " AND vnp.vnp_accepted_zone IN ($zoneid)";
		}
		else
		{
			$where = " AND vnp.vnp_home_zone IN ($zoneid)";
		}
		$sql = "SELECT vnd.vnd_id, vnd.vnd_name, zones.zon_name
                      FROM  vendors vnd
                      INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id
                      LEFT JOIN app_tokens apt ON apt.apt_entity_id = vnd.vnd_id AND apt.apt_user_type = 2
                      LEFT JOIN `zones` ON zones.zon_id=$zoneid
                      WHERE vnd.vnd_active = 1 $where
                        AND apt.apt_last_login BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'
                      GROUP BY vnd.vnd_id";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['vnd_id', 'vnd_name', 'zon_name'], 'defaultOrder' => ''], 'pagination'	 => ['pageSize' => 25],
		]);
		return $dataprovider;
	}

	/**
	 * This function is used for getting nearest zone list from home zone of a vendor 
	 * @param (int) $vndId
	 * @return array $data
	 * @throws Exception
	 */
	public static function getZoneByHomeZone($vndId = NULL)
	{
		$qry1 = 'SELECT DISTINCT
					(SELECT vzone.zon_lat from zones vzone WHERE  vzone.zon_id = vendor_pref.vnp_home_zone) as home_zon_lat
				FROM zones vzone
				INNER JOIN vendor_pref ON vzone.zon_id = vendor_pref.vnp_home_zone
				WHERE vendor_pref.vnp_vnd_id =' . $vndId;

		$result			 = DBUtil::queryAll($qry1, DBUtil::SDB());
		$home_zon_lat	 = $result[0][home_zon_lat];
		$qry2			 = 'SELECT DISTINCT
			(SELECT vzone.zon_long from zones vzone WHERE  vzone.zon_id = vendor_pref.vnp_home_zone) as home_zon_long
		FROM zones vzone
		INNER JOIN vendor_pref ON vzone.zon_id = vendor_pref.vnp_home_zone
		WHERE vendor_pref.vnp_vnd_id = ' . $vndId;

		$result			 = DBUtil::queryAll($qry2, DBUtil::SDB());
		$home_zon_long	 = $result[0][home_zon_long];

		$distance	 = (int) Config::get('vendor.maxDistanceZoneAllowed');
		$params		 = ['vndId' => $vndId, 'distance' => $distance, 'homeZonLat' => $home_zon_lat, 'homeZonLong' => $home_zon_long];
		$sql		 = 'SELECT
				vzone.zon_id id,  vzone.zon_name name
			FROM zones vzone
		
			WHERE round(6371
				  * acos( cos( radians(:homeZonLat) )
						  * cos(  radians( vzone.zon_lat )   )
						  * cos(  radians( vzone.zon_long ) - radians(:homeZonLong) )
						+ sin( radians(:homeZonLat))
						  * sin( radians( vzone.zon_lat ) )
						)) <= :distance
			';

		$data = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public function getZeroinventory_OLD($model, $date1, $date2)
	{
		//get all zones into single array
		$homeZones		 = "SELECT group_concat(distinct(vnp_home_zone)) as home_zone_array FROM `vendor_pref` where vnp_home_zone !=''";
		$homeZonesQuery	 = DBUtil::queryAll($homeZones, DBUtil::SDB());
		$acceptedZones	 = "SELECT vnp_accepted_zone as acce_zone_array FROM `vendor_pref` where vnp_accepted_zone !=''";

		$acceptedZonesQuery	 = DBUtil::queryAll($acceptedZones, DBUtil::SDB());
		$acceptedZoneArray	 = implode(',', array_column($acceptedZonesQuery, 'acce_zone_array'));
		$allZones			 = array_merge(explode(',', $homeZonesQuery['home_zone_array']), array_unique(explode(',', $acceptedZoneArray)));

		$zoneString	 = implode(',', array_filter($allZones));
		$sql		 = "SELECT zon_id, zon_name FROM zones WHERE zon_id NOT IN ($zoneString)";
		$result		 = DBUtil::queryAll($sql, DBUtil::SDB());

		if ($result != '')
		{
			foreach ($result as $key => $val)
			{
				$bookingCount				 = "SELECT group_concat(DISTINCT(bkg_id)) as bookings, COUNT(DISTINCT(bkg_id)) as bkg_count
                    FROM `booking`
                    INNER JOIN zone_cities ON zone_cities.zct_cty_id = booking.bkg_from_city_id
                    INNER JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id
                    WHERE zones.zon_id= " . $val['zon_id'] . " AND bkg_status IN(1,2,9,15) AND bkg_create_date BETWEEN DATE(DATE_SUB(NOW(), INTERVAL 180 DAY)) AND NOW()";
				$result[$key]['bkg_count']	 = DBUtil::queryAll($bookingCount, DBUtil::SDB())[0]['bkg_count'];
				$result[$key]['bookings']	 = DBUtil::queryAll($bookingCount, DBUtil::SDB())[0]['bookings'];
			}
		}
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CArrayDataProvider($result, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['zon_name'],
				'defaultOrder'	 => 'zon_name ASC'], 'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function getZeroinventory($model, $date1, $date2)
	{
		$final_result	 = array();
		//get all zones into single array
		$homeZones		 = "SELECT group_concat(distinct(vnp_home_zone)) as home_zone_array FROM `vendor_pref` where vnp_home_zone !=''";
		$homeZonesQuery	 = DBUtil::queryAll($homeZones, DBUtil::SDB());
		$zoneString		 = $homeZonesQuery[0]['home_zone_array'];

		$acceptedZones		 = "SELECT vnp_accepted_zone as acce_zone_array FROM `vendor_pref` where vnp_accepted_zone !=''";
		$acceptedZonesQuery	 = DBUtil::queryAll($acceptedZones, DBUtil::SDB());

		$arr_col	 = array_unique(array_column($acceptedZonesQuery, 'acce_zone_array'));
		$singleVal	 = [];
		foreach ($arr_col as $val)
		{
			$uniq_zn = explode(',', $val);
			foreach ($uniq_zn as $val1)
			{
				if (!in_array($val1, $singleVal))
				{
					array_push($singleVal, $val1);
				}
			}
		}

		$home_array	 = array_filter(explode(',', $zoneString), 'is_numeric');
		$allZones	 = array_unique(array_filter(array_merge($home_array, $singleVal), 'is_numeric'));

		$implodeArray	 = implode(',', $allZones);
		$sql			 = "SELECT zon_id FROM zones WHERE zon_id NOT IN ($implodeArray)";
		$result			 = DBUtil::queryAll($sql, DBUtil::SDB()); //got zones those who doesnt have home zone

		if ($result != '')
		{
			foreach ($result as $key => $val)
			{
				$bookingCount					 = "SELECT zones.zon_name, group_concat(DISTINCT(bkg_id)) as bookings, COUNT(DISTINCT(bkg_id)) as bkg_count
                    FROM `booking`
                    INNER JOIN zone_cities ON zone_cities.zct_cty_id = booking.bkg_from_city_id
                    INNER JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id
                    WHERE zones.zon_id= " . $val['zon_id'] . " AND bkg_status IN(1,2,9,15) AND bkg_create_date BETWEEN DATE(DATE_SUB(NOW(), INTERVAL 180 DAY)) AND NOW()";
				$final_result[$key]['zon_name']	 = DBUtil::queryAll($bookingCount, DBUtil::SDB())[0]['zon_name'];
				$final_result[$key]['bkg_count'] = DBUtil::queryAll($bookingCount, DBUtil::SDB())[0]['bkg_count'];
				$final_result[$key]['bookings']	 = DBUtil::queryAll($bookingCount, DBUtil::SDB())[0]['bookings'];
			}
		}
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CArrayDataProvider($final_result, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['zon_name'],
				'defaultOrder'	 => 'zon_name ASC'], 'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public static function countZeroInventory()
	{
		$returnSet = Yii::app()->cache->get('countZeroInventory');
		if ($returnSet === false)
		{
			$homeZones		 = "SELECT GROUP_CONCAT(DISTINCT vnp_home_zone) as home_zone_array FROM `vendor_pref` where vnp_home_zone <> ''";
			$homeZonesQuery	 = DBUtil::queryAll($homeZones, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			$zoneString		 = $homeZonesQuery[0]['home_zone_array'];

			$acceptedZones		 = "SELECT vnp_accepted_zone as acce_zone_array FROM `vendor_pref` where vnp_accepted_zone !=''";
			$acceptedZonesQuery	 = DBUtil::queryAll($acceptedZones, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);

			$arr_col	 = array_unique(array_column($acceptedZonesQuery, 'acce_zone_array'));
			$singleVal	 = [];
			foreach ($arr_col as $val)
			{
				$uniq_zn = explode(',', $val);
				foreach ($uniq_zn as $val1)
				{
					if (!in_array($val1, $singleVal))
					{
						array_push($singleVal, $val1);
					}
				}
			}

			$home_array	 = array_filter(explode(',', $zoneString), 'is_numeric');
			$allZones	 = array_unique(array_filter(array_merge($home_array, $singleVal), 'is_numeric'));
			$zoneString	 = implode(',', array_filter($allZones, 'is_numeric'));

			$sql		 = "SELECT COUNT(zon_id) FROM zones WHERE zon_id NOT IN ($zoneString)";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('countZeroInventory', $returnSet, 600);
		}
		return $returnSet;
	}

	/**
	 * This function is used to get the zoneName of particular city
	 * @param type string $columnName
	 * @param type (int) $cityId
	 * @return type (string) Zone_name
	 */
	public static function getColumnValue($columnName, $cityId)
	{
		$ids		 = self::getByCityId($cityId);
		$arrayIds	 = explode(',', $ids);
		$sql		 = "SELECT {$columnName} FROM zones WHERE zon_id=:id AND zon_active = 1";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar(['id' => $arrayIds[0]]);
	}

	/**
	 * This function is used to get the zoneName
	 * @param type (int) cityId $id
	 * @return type string) Zone_name
	 */
	public static function getName($id)
	{
		return self::getColumnValue("zon_name", $id);
	}

	/**
	 * this function is used to get all zones based on searching context
	 * @param string $query  
	 * @param (integer) $zoneId
	 * @return type
	 */
	public function getAllZoneList($query, $zoneId = '')
	{
		$rows	 = Zones::getZones($query, $zoneId);
		$data	 = array();
		$i		 = 0;
		foreach ($rows as $row)
		{
			$i++;
			$data[] = array("id" => (int) $row['zon_id'], "name" => $row['zon_name']);
		}
		return $data;
	}

	public static function getZones($query = '', $zone = '')
	{
		$query	 = ($query == null || $query == "") ? "" : $query;
		$query1	 = str_replace(" ", "%", trim($query));
		DBUtil::getLikeStatement($query1, $bindString0, $params1);
		DBUtil::getLikeStatement($query1, $bindString3, $params3, "");
		$qry	 = '';
		$qry1	 = "";
		if ($zone != '')
		{
			$qry1 = " AND z.zon_id IN ($zone)";
		}
		else
		{
			$qry1 = " AND z.zon_id IS NOT NULL AND z.zon_active = 1";
		}
		if ($query != '')
		{
			$qry .= " AND z.zon_name LIKE $bindString0 ";
		}
		else
		{
			$params1 = [];
		}

		$sql = "SELECT z.zon_id , z.zon_name,
				IF(z.zon_name LIKE $bindString3,1,0) AS startRank	
				FROM zones z 
				WHERE 1  $qry $qry1 AND z.zon_active=1 
				ORDER BY startRank DESC, zon_name ASC LIMIT 0,10";
		return DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params3), 4 * 1 * 60 * 60, CacheDependency::Type_Zones);
	}

	public static function getDopdownList()
	{
		$sql	 = "SELECT zon_id, zon_name FROM `zones` WHERE zon_active  = 1";
		$result	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $result;
	}

	public function getNearByZone($zoneId)
	{


		/*  $sql = "SELECT 
		  DISTINCT z2.zon_id, z2.zon_name
		  FROM `zones` z1
		  INNER JOIN zone_cities zc1 ON zc1.zct_zon_id = z1.zon_id AND zc1.zct_active = 1
		  INNER JOIN cities c1 ON zc1.zct_cty_id = c1.cty_id AND c1.cty_active = 1 AND c1.cty_service_active = 1
		  INNER JOIN zone_cities zc2 ON zc2.zct_cty_id = c1.cty_id AND zc2.zct_active = 1
		  INNER JOIN `zones` z2 ON z2.zon_id = zc2.zct_zon_id AND z2.zon_active = 1
		  WHERE z1.zon_active = 1 AND z1.`zon_id` = $zoneId "; */
		$sql		 = "SELECT 
				DISTINCT z2.zon_id, z2.zon_name ,GROUP_CONCAT(DISTINCT(s.stt_name)) as state_names
				FROM `zones` z1
				INNER JOIN zone_cities zc1 ON zc1.zct_zon_id = z1.zon_id AND zc1.zct_active = 1 
				INNER JOIN cities c1 ON zc1.zct_cty_id = c1.cty_id AND c1.cty_active = 1 AND c1.cty_service_active = 1
                inner JOIN states s on c1.cty_state_id = s.stt_id
				INNER JOIN zone_cities zc2 ON zc2.zct_cty_id = c1.cty_id AND zc2.zct_active = 1 
				INNER JOIN `zones` z2 ON z2.zon_id = zc2.zct_zon_id AND z2.zon_active = 1
				WHERE z1.zon_active = 1 AND z1.`zon_id` =$zoneId
                GROUP BY z2.zon_id";
		$zoneData	 = DBUtil::query($sql);
		// $zArr = [];
		foreach ($zoneData as $row)
		{
			// $zArr[$row['zon_id']] = $row['zon_name'];

			$zArr[] = array("id" => $row['zon_id'], "text" => ($row['state_names'] != "" ? "[" . $row['state_names'] . "]-" : "") . $row['zon_name']);
		}
		return $zArr;
	}

	public function getZonesEdit($ids)
	{
		$sql		 = "SELECT z1.zon_id as zon_id,z1.zon_name as zon_name,GROUP_CONCAT(DISTINCT(s.stt_name)) as state_name
						FROM `zones` AS z1
						INNER JOIN zone_cities AS zc1 ON zc1.zct_zon_id = z1.zon_id AND zc1.zct_active = 1 
						INNER JOIN cities AS c1 ON zc1.zct_cty_id = c1.cty_id AND c1.cty_active = 1 AND c1.cty_service_active = 1
						INNER JOIN states AS s ON c1.cty_state_id = s.stt_id
						WHERE z1.zon_active = 1
						AND z1.`zon_id` IN ($ids) group by z1.zon_id";
		$zoneData	 = DBUtil::query($sql);
		foreach ($zoneData as $row)
		{

			$zArr[] = array("id" => $row['zon_id'], "text" => '[' . $row['state_name'] . '] - ' . $row['zon_name']);
		}
		return $zArr;
	}

	public static function getNameByCityId($ids)
	{
		$sql	 = "SELECT GROUP_CONCAT(zon_name) FROM `zones` WHERE zon_active  = 1 AND zon_id IN ($ids)";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB());
		return $result;
	}

	/*
	 * This function is used to get multiple selection for the Zone list in manage home service zone page
	 * it populates all the zone except currently allocated service zones
	 * @param int $zoneid
	 * 
	 * return string 
	 */

	public static function getServiceZoneList($zoneid, $distance = 200)
	{
		$key	 = "ServiceZone_{$zoneid}_$distance";
		$data	 = Yii::app()->cache->get($key);
		if ($data == false)
		{
			$params	 = ['zoneid' => $zoneid, 'distance' => $distance];
			$sql	 = "SELECT group_concat(distinct szn.zon_id) 
				FROM   home_service_zones hsz
				INNER JOIN zones hzn ON hzn.zon_id = hsz.hsz_home_id
				INNER JOIN zones szn ON szn.zon_id = hsz.hsz_service_id
				WHERE  FIND_IN_SET(hsz.hsz_home_id, :zoneid) AND hsz.hsz_active = 1 
				AND CalcDistance(hzn.zon_lat,hzn.zon_long,szn.zon_lat,szn.zon_long)<=:distance
			  ";
			$data	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
			Yii::app()->cache->set($key, $data, 86400, new CacheDependency('zones'));
		}
		return $data;
	}

	/*
	 * This function is used to retrun  a list of vendor seperated by comma for individual zone that is active  in vendor apps 
	 * return query object 
	 */

	public static function getVendorListByApp()
	{
		$sql = "SELECT 
                zones.zon_id AS ZoneId,
                3 as type,
                CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 Day), ' 00:00:00') AS date,
                HOUR(DATE_SUB(NOW(),INTERVAL 1 HOUR)) AS hour,
                GROUP_CONCAT(DISTINCT vendor_pref.vnp_vnd_id) AS vendorIds
                FROM `app_tokens` apt
                INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id= apt.apt_entity_id  
                INNER JOIN zones ON zones.zon_id = vendor_pref.vnp_home_zone
                WHERE 1
                AND apt.apt_user_type = 2
                AND apt.apt_status = 1
                AND apt.apt_last_login BETWEEN  CONCAT(DATE_SUB(CURDATE(),INTERVAL 30 DAY),' 00:00:00') AND NOW()
                AND zones.zon_id >0
                GROUP BY zones.zon_id";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/*
	 * This function is used to retrun  a list of vendor seperated by comma for individual zone that is approved
	 * return query object 
	 */

	public static function getVendorApprovedList()
	{
		$sql = "SELECT zones.zon_id AS ZoneId,
                4 as type,
                CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 Day), ' 00:00:00') AS date,
                HOUR(DATE_SUB(NOW(),INTERVAL 1 HOUR)) AS hour,
                GROUP_CONCAT(DISTINCT vnd_id) AS vendorIds
                FROM vendors
                INNER JOIN vendor_pref ON vnd_id=vnp_vnd_id AND vnd_active=1 
                INNER JOIN zones ON zones.zon_id = vendor_pref.vnp_home_zone
                WHERE vendor_pref.vnp_is_freeze=0 AND zones.zon_id>0
                GROUP BY zones.zon_id";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/*
	 * This function is used to retrun  a list of zone based on inventory shortage
	 * return query object 
	 */

	public function getInventoryShortageZone()
	{
		$sql = "SELECT
                fzone.zon_id AS  fromZoneId,
                tzone.zon_id AS toZoneId,
                SUM(IF(btr_is_dem_sup_misfire=1,1,0)) as cntdemsup,
                (SUM(IF(btr_is_dem_sup_misfire=1,1,0))+SUM(IF(btr_nmi_flag=1,1,0))+SUM(IF(bkg.bkg_cancel_id IN(9,17) AND bkg.bkg_status IN(9,10),1,0))) as tot,
                ROUND(((SUM(IF(bkg.bkg_cancel_id IN(9,17) AND bkg.bkg_status IN(9,10),1,0)))/SUM(IF(bkg.bkg_status IN(6,7),1,0)))*100 ,1)as percentage
                FROM booking bkg
                LEFT JOIN cancel_reasons ON cnr_id= bkg.bkg_cancel_id
                INNER JOIN booking_trail ON booking_trail.btr_bkg_id = bkg.bkg_id 
                INNER JOIN zone_cities fzonecity ON fzonecity.zct_cty_id = bkg.bkg_from_city_id 
                INNER JOIN zone_cities tzonecity ON tzonecity.zct_cty_id = bkg.bkg_to_city_id 
                INNER JOIN zones fzone ON fzone.zon_id = fzonecity.zct_zon_id 
                INNER JOIN zones tzone ON tzone.zon_id = tzonecity.zct_zon_id 
                WHERE 1
                AND bkg.bkg_pickup_date BETWEEN  CURDATE() AND NOW()   
                GROUP BY fzone.zon_id,tzone.zon_id 
                HAVING tot>0 AND cntdemsup>=10   AND percentage > 0
                ORDER BY  percentage DESC
                LIMIT 0,20";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getZoneByCityId($cityId)
	{
		$sql = "SELECT zones.zon_id
                    FROM cities c1
                    INNER JOIN zone_cities ON zone_cities.zct_cty_id = c1.cty_id AND zct_active=1
                    INNER JOIN zones ON zone_cities.zct_zon_id = zones.zon_id AND zon_active=1
                    WHERE c1.cty_id = $cityId ORDER BY zones.zon_id DESC LIMIT 0,1";
		return DBUtil::queryScalar($sql);
	}

	public static function getIdsByRegionId($regionId)
	{
		$sql = "SELECT GROUP_CONCAT(DISTINCT zon_id) as zon_ids 
				FROM zones 
				INNER JOIN zone_cities ON zon_id = zct_zon_id AND zon_active = 1 AND zct_active = 1 
				INNER JOIN cities ON cty_id = zct_cty_id AND cty_active = 1 
				INNER JOIN states ON stt_id = cty_state_id AND stt_active = '1' 
				WHERE 1 AND stt_zone IN ({$regionId})";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	public function getMZoneArr()
	{
		$rows	 = DBUtil::query("SELECT `zon_master_zone_id`,gdt_name  FROM `geo_data` INNER JOIN  zones ON zones.zon_master_zone_id=geo_data.gdt_id WHERE 1 GROUP by geo_data.gdt_id;", DBUtil::SDB(), [], true, 60 * 60 * 24, CacheDependency::Type_Zones);
		$zArr	 = [];
		foreach ($rows as $row)
		{
			$zArr[$row['zon_master_zone_id']] = $row['gdt_name'];
		}
		return $zArr;
	}

	/** 	
	 * Block Auto Assignment will be active within Delhi Zone for all bookings.
	 * @param object $model (Booking Model)	 
	 */
	public static function checkForBlockedZones($model)
	{
		$blockedZones	 = CJSON::decode(Config::get('Zone.AutoAssignment.Blocked'));
		$zones			 = self::getByCityId($model->bkg_from_city_id);
		$allZones		 = explode(",", $zones);
		$data			 = array_intersect($allZones, $blockedZones);
		if (!empty($data))
		{
			BookingPref::setBlockAutoAssignment($model);
		}
	}

	public static function getZoneByStateId($stateIds)
	{
		$stateIds	 = is_string($stateIds) ? $stateIds : strval($stateIds);
		DBUtil::getINStatement($stateIds, $bindString, $params);
		$sql		 = "SELECT GROUP_CONCAT(DISTINCT zon_id) as zon_ids 
						FROM zones 
						INNER JOIN zone_cities ON zon_id = zct_zon_id AND zon_active = 1 AND zct_active = 1 
						INNER JOIN cities ON cty_id = zct_cty_id AND cty_active = 1 
						INNER JOIN states ON stt_id = cty_state_id AND stt_active = '1' 
						WHERE 1 AND stt_id  IN ({$bindString})";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public static function getStateRegionInfo($zoneId)
	{
		$sql = "SELECT GROUP_CONCAT(DISTINCT stt_id) as stateIds, GROUP_CONCAT(DISTINCT zct_zon_id) zones, 
					GROUP_CONCAT(DISTINCT stt_zone) AS region
				FROM   cities
				INNER JOIN states ON cty_state_id = stt_id AND stt_active = '1'
				INNER JOIN zone_cities ON cty_id = zct_cty_id AND zct_active = 1
				INNER JOIN zones ON zon_id=zct_zon_id AND zon_active=1
				WHERE  zon_id =:zone AND cty_active = 1";

		$stateRegionInfo = DBUtil::queryRow($sql, DBUtil::SDB(), ['zone' => $zoneId], 5 * 24 * 60 * 60, CacheDependency::Type_Zones);
		return $stateRegionInfo;
	}

	public static function getRegionByZoneId($zoneId)
	{
		$zoneIds = is_string($zoneId) ? $zoneId : strval($zoneId);
		DBUtil::getINStatement($zoneIds, $bindString, $params);
		$sql	 = "SELECT states.stt_zone AS RegionId
						FROM zones 
						INNER JOIN zone_cities ON zon_id = zct_zon_id AND zon_active = 1 AND zct_active = 1 
						INNER JOIN cities ON cty_id = zct_cty_id AND cty_active = 1 
						INNER JOIN states ON stt_id = cty_state_id AND stt_active = '1' 
						WHERE 1 AND zon_id  IN ({$bindString}) AND states.stt_zone IS NOT NULL GROUP BY states.stt_zone";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public static function getMasterZoneNameById($ids)
	{
		$sql	 = "SELECT GROUP_CONCAT(msz_zone_name) FROM `master_zones` WHERE `msz_master_zone_id` IN ($ids)";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB());
		return $result;
	}

	public static function getNamesByIds($arrZones, $zoneIds = '')
	{
		$strZoneNames = '';
		if ($zoneIds != '')
		{
			$arrZoneIds = explode(',', $zoneIds);
			foreach ($arrZoneIds as $zoneId)
			{
				$strZoneNames .= $arrZones[$zoneId] . ', ';
			}

			$strZoneNames = trim($strZoneNames, ', ');
		}

		return $strZoneNames;
	}

	public function getMmtEnquiry()
	{
		$fromDate		 = $this->mdcDate1;
		$toDate			 = $this->mdcDate2;
		$type			 = $this->dateType;
		$bookingType	 = $this->bookingType;
		$sourcezone		 = $this->sourcezone;
		$destinationZone = $this->destinaitonzone;
		$fromCity		 = $this->fromcity;
		$toCity			 = $this->tocity;
		$where			 = '';
		$cond			 = '';

		if ($type == 1)
		{
			if ($fromDate != '' && $toDate != '')
			{
				$cond .= " AND mdc_date BETWEEN '$fromDate' AND '$toDate'";
			}
			if ($bookingType != '')
			{
				$cond .= " AND mdc_booking_type IN ($bookingType)";
			}

			$select		 = "SELECT fz.zon_name as fzonename, tz.zon_name as tzonename, mdc_date AS date,
						GROUP_CONCAT(DISTINCT CONCAT(fromCityName, ' - ', toCityName, ' (', a.searchCnt,')') ORDER BY a.searchCnt DESC SEPARATOR ', ' ) as routes,
						mdc_booking_type AS bookingType, SUM(searchCnt) as searchCnt, SUM(confirmCnt) as confirmCnt, 
						SUM(holdCnt) as holdCnt, SUM(blockedCnt) as blockedCnt, SUM(errorCnt) as errorCnt ";
			$selectCount = "SELECT mdc_date AS date, mdc_booking_type AS bookingType ";
			$sql		 = "FROM(
								SELECT fc.cty_id AS fromCityId, tc.cty_id AS toCityId, fc.cty_name AS fromCityName, tc.cty_name AS toCityName,
								mdc_booking_type, mdc_date, SUM(mdc.mdc_search_count) as searchCnt, SUM(mdc.mdc_confirm_count) as confirmCnt, 
								SUM(mdc.mdc_hold_count) as holdCnt, SUM(mdc.mdc_search_blocked_count) as blockedCnt, SUM(mdc.mdc_search_error_count) as errorCnt  
								FROM `mmt_data_created` mdc
								INNER JOIN cities fc On fc.cty_id=mdc.mdc_from_city_id
								INNER JOIN cities tc On tc.cty_id=mdc.mdc_to_city_id
								WHERE 1 $cond
								GROUP BY fc.cty_id, tc.cty_id, mdc_booking_type
							) a ";
		}
		elseif ($type == 2)
		{
			if ($fromDate != '' && $toDate != '')
			{
				$cond .= " AND mdp_date BETWEEN '$fromDate' AND '$toDate'";
			}
			if ($bookingType != '')
			{
				$cond .= " AND mdp_booking_type IN ($bookingType)";
			}

			$select		 = "SELECT fz.zon_name as fzonename, tz.zon_name as tzonename, mdp_date AS date, 
						GROUP_CONCAT(DISTINCT CONCAT(fromCityName, ' - ', toCityName, ' (', a.searchCnt,')') ORDER BY a.searchCnt DESC SEPARATOR ', ' ) as routes,
						mdp_booking_type AS bookingType, SUM(searchCnt) as searchCnt, SUM(confirmCnt) as confirmCnt, 
						SUM(holdCnt) as holdCnt, SUM(blockedCnt) as blockedCnt, SUM(errorCnt) as errorCnt ";
			$selectCount = "SELECT mdp_date AS date, mdp_booking_type AS bookingType ";
			$sql		 = "FROM(
								SELECT fc.cty_id AS fromCityId, tc.cty_id AS toCityId, fc.cty_name AS fromCityName, tc.cty_name AS toCityName,
								mdp_booking_type, mdp_date, SUM(mdp.mdp_search_count) as searchCnt, SUM(mdp.mdp_confirm_count) as confirmCnt, 
								SUM(mdp.mdp_hold_count) as holdCnt, SUM(mdp.mdp_search_blocked_count) as blockedCnt, SUM(mdp.mdp_search_error_count) as errorCnt 
								FROM `mmt_data_pickup` mdp
								INNER JOIN cities fc On fc.cty_id=mdp.mdp_from_city_id
								INNER JOIN cities tc On tc.cty_id=mdp.mdp_to_city_id
								WHERE 1 $cond
								GROUP BY fc.cty_id, tc.cty_id, mdp_booking_type
							) a ";
		}

		if ($sourcezone != '')
		{
			$where .= " AND fz.zon_id IN ($sourcezone) ";
		}
		if ($destinationZone != '')
		{
			$where .= " AND tz.zon_id IN ($destinationZone) ";
		}
		if ($fromCity != '')
		{
			$where .= " AND a.fromCityId = $fromCity ";
		}
		if ($toCity != '')
		{
			$where .= " AND a.toCityId = $toCity ";
		}

		$dataSelect = "INNER JOIN zone_cities fzc ON fzc.zct_cty_id=a.fromCityId AND fzc.zct_active=1 
					INNER JOIN zones fz ON fz.zon_id=fzc.zct_zon_id AND fz.zon_active=1 
					INNER JOIN zone_cities tzc ON tzc.zct_cty_id=a.toCityId AND tzc.zct_active=1 
					INNER JOIN zones tz ON tz.zon_id=tzc.zct_zon_id AND tz.zon_active=1 
					where 1 $where 
					GROUP BY fz.zon_id, tz.zon_id, bookingType ";

		$sqlData	 = $select . $sql . $dataSelect;
		$sqlCount	 = $selectCount . $sql . $dataSelect;

		$dataCount		 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sqlData, [
			'db'			 => DBUtil::SDB3(),
			'totalItemCount' => $dataCount,
			'sort'			 => ['attributes' => ['searchCnt', 'confirmCnt', 'holdCnt', 'blockedCnt', 'errorCnt'], 'defaultOrder' => 'searchCnt DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	/**
	 * @param Booking $model
	 * @param integer $type
	 * @return \CSqlDataProvider
	 */
	public static function getBookingByZone($model, $type = DBUtil::ReturnType_Provider)
	{
		$sql = "SELECT
				COUNT(distinct bkg_id) AS cnt,
				DATE(bkg_pickup_date) AS PickupDate,
				CASE
				WHEN stt_zone = 1 THEN CONCAT(zon_name,\"(\",'North',\")\")
				WHEN stt_zone = 2 THEN CONCAT(zon_name,\"(\",'West',\")\")
				WHEN stt_zone = 3 THEN CONCAT(zon_name,\"(\",'Central',\")\")
				WHEN stt_zone = 4 THEN CONCAT(zon_name,\"(\",'South',\")\")
				WHEN stt_zone = 5 THEN CONCAT(zon_name,\"(\",'East',\")\")
				WHEN stt_zone = 6 THEN CONCAT(zon_name,\"(\",'North East',\")\")
				WHEN stt_zone = 7 THEN CONCAT(zon_name,\"(\",'South',\")\")
				ELSE '-'
				END AS zon_name
				FROM `booking`
				JOIN `cities` ON cities.cty_id = booking.bkg_from_city_id and cities.cty_active=1
				JOIN `states` ON states.stt_id = cities.cty_state_id AND stt_active='1'
				JOIN `zone_cities` ON zone_cities.zct_cty_id = booking.bkg_from_city_id and zone_cities.zct_active=1
				JOIN `zones` ON zones.zon_id = zone_cities.zct_zon_id AND zones.zon_active=1
				WHERE booking.bkg_pickup_date BETWEEN :pickupDate1 AND :pickupDate2 
				AND booking.bkg_reconfirm_flag=1
				GROUP BY PickupDate,zon_id,stt_zone
				HAVING cnt>=5";

		$sqlCount = "SELECT COUNT(1) FROM 
					(
						SELECT
						COUNT(distinct bkg_id) AS cnt,
						DATE(bkg_pickup_date) AS PickupDate,
						CASE
						WHEN stt_zone = 1 THEN CONCAT(zon_name,\"(\",'North',\")\")
						WHEN stt_zone = 2 THEN CONCAT(zon_name,\"(\",'West',\")\")
						WHEN stt_zone = 3 THEN CONCAT(zon_name,\"(\",'Central',\")\")
						WHEN stt_zone = 4 THEN CONCAT(zon_name,\"(\",'South',\")\")
						WHEN stt_zone = 5 THEN CONCAT(zon_name,\"(\",'East',\")\")
						WHEN stt_zone = 6 THEN CONCAT(zon_name,\"(\",'North East',\")\")
						WHEN stt_zone = 7 THEN CONCAT(zon_name,\"(\",'South',\")\")
						ELSE '-'
						END AS zon_name
						FROM `booking`
						JOIN `cities` ON cities.cty_id = booking.bkg_from_city_id and cities.cty_active=1
						JOIN `states` ON states.stt_id = cities.cty_state_id AND stt_active='1'
						JOIN `zone_cities` ON zone_cities.zct_cty_id = booking.bkg_from_city_id and zone_cities.zct_active=1
						JOIN `zones` ON zones.zon_id = zone_cities.zct_zon_id AND zones.zon_active=1
						WHERE booking.bkg_pickup_date BETWEEN :pickupDate1 AND :pickupDate2 
						AND booking.bkg_reconfirm_flag=1
						GROUP BY PickupDate,zon_id,stt_zone
						HAVING cnt>=5) a";

		$params['pickupDate1']	 = $model->bkg_pickup_date1;
		$params['pickupDate2']	 = $model->bkg_pickup_date2;

		if ($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'params'		 => $params,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['cnt', 'PickupDate', 'zon_name'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3(), $params);
		}
	}

	public static function getHillyFactor()
	{
		$arrHillyFactor = [
			0	 => 'Normal',
			1	 => 'Less',
			2	 => 'Medium',
			3	 => 'High'
		];

		return $arrHillyFactor;
	}

	/**
	 * 
	 * @param int $cityId
	 * @return CDbDataReader
	 */
	public static function getListByCityId($cityId)
	{
		$param	 = ['city' => $cityId];
		$sql	 = "SELECT zones.zon_id,zones.zon_name FROM cities c1
					INNER JOIN zone_cities ON zone_cities.zct_cty_id = c1.cty_id AND zct_active=1
					INNER JOIN zones ON zone_cities.zct_zon_id = zones.zon_id AND zon_active=1
					WHERE c1.cty_id = :city";

		$data = DBUtil::query($sql, DBUtil::SDB(), $param);
		return $data;
	}

	public static function getTopZones()
	{


		$sql = "SELECT z.zon_id , z.zon_name,
				IF(z.zon_name LIKE $bindString3,1,0) AS startRank	
				FROM zones z 
				WHERE 1  $qry $qry1 AND z.zon_active=1 
				ORDER BY startRank DESC, zon_name ASC LIMIT 0,10";
		return DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params3), 4 * 1 * 60 * 60, CacheDependency::Type_Zones);
	}
}
