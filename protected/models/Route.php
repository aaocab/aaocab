<?php

use \Spatie\SchemaOrg\Schema;

/**
 * This is the model class for table "route".
 *
 * The followings are the available columns in table 'route':
 * @property integer $rut_id
 * @property integer $rut_from_city_id
 * @property string $rut_from_address
 * @property integer $rut_to_city_id
 * @property string $rut_to_address
 * @property integer $rut_actual_distance
 * @property string $rut_actual_time
 * @property string $rut_estm_distance
 * @property string $rut_estm_time
 * @property string $rut_name
 * @property string $rut_info
 * @property string $rut_special_remarks
 * @property string rut_keyword_names
 * @property string rut_title
 * @property integer rut_is_promo_code_apply
 * @property integer rut_is_promo_gozo_coins_apply
 * @property integer rut_is_cod_apply
 * @property integer rut_excluded_cabtypes
 * @property string $rut_create_date
 * @property integer $rut_active
 * @property string $rut_log
 * @property integer $rut_override_dr
 * The followings are the available model relations:
 * @property Rate[] $rates
 * @property Cities $rutFromCity
 * @property Cities $rutToCity
 */
class Route extends CActiveRecord
{

	public $fromcity,
			$rut_estm_time_min,
			$rut_return_name,
			$rut_estm_distance_exp,
			$rut_vehicle_type_id,
			$rut_from_city_id1,
			$rut_to_city_id1,
			$rut_route_city_id,
			$tocity,
			$rut_source_zone,
			$rut_destination_zone;

	public function tableName()
	{
		return 'route';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rut_from_city_id, rut_to_city_id,  rut_estm_distance, rut_estm_time', 'required', 'on' => 'insert'),
			array('rut_from_city_id, rut_to_city_id, rut_active, rut_is_promo_code_apply, rut_is_promo_gozo_coins_apply, rut_is_cod_apply', 'numerical', 'integerOnly' => true),
			array('rut_from_address, rut_to_address', 'length', 'max' => 255),
			array('rut_estm_distance, rut_estm_time', 'length', 'max' => 100),
			array('rut_name', 'length', 'max' => 150),
			array('rut_to_city_id', 'duplicateCheck'),
			array('rut_name', 'duplicateNameCheck'),
			array('rut_info, rut_title', 'length', 'max' => 1024),
			array('rut_id,rut_source_zone,rut_destination_zone, rut_from_city_id,  rut_from_city_id1, rut_to_city_id1,  rut_from_address,rut_actual_distance,rut_actual_time, rut_log,rut_to_city_id, rut_to_address, rut_estm_distance, rut_estm_time, rut_name, rut_info, rut_special_remarks,rut_keyword_names,rut_title, rut_is_promo_code_apply, rut_is_promo_gozo_coins_apply, rut_is_cod_apply, rut_create_date, rut_active,rut_estm_distance_exp,rut_excluded_cabtypes,rut_override_dr,rut_included_cabCategories,rut_excluded_cabtypes,rut_included_cabmodels', 'safe',),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rut_id, rut_from_city_id, rut_from_address, rut_log,rut_to_city_id, rut_to_address, rut_estm_distance, rut_estm_time, rut_name, rut_info, rut_special_remarks,rut_keyword_names,rut_title, rut_is_promo_code_apply, rut_is_promo_gozo_coins_apply, rut_is_cod_apply, rut_create_date, rut_active,rut_estm_distance_exp,rut_excluded_cabtypes,rut_override_dr', 'safe', 'on' => 'search'),
		);
	}

	public function defaultScope()
	{
		$ta	 = $this->getTableAlias(false, false);
		$arr = array(
			'condition' => $ta . ".rut_active=1",
		);
		return $arr;
	}

	public function duplicateCheck($attribute, $params)
	{
		$criteria = new CDbCriteria();
		$criteria->compare("rut_from_city_id", $this->rut_from_city_id);
		$criteria->compare("rut_to_city_id", $this->rut_to_city_id);
		if (!$this->isNewRecord)
		{
			$criteria->addCondition("rut_id <> " . $this->rut_id);
		}

		$duplicatecheck = Route::model()->find($criteria);
		if ($duplicatecheck)
		{
			$this->addError($attribute, "Route Already Exists");
			return false;
		}
		return true;
	}

	public function duplicateNameCheck($attribute, $params)
	{
		if ($this->rut_name == '')
		{
			return true;
		}

		$criteria = new CDbCriteria();
		$criteria->compare("rut_name", $this->rut_name);
		if (!$this->isNewRecord)
		{
			$criteria->addCondition("rut_id <> " . $this->rut_id);
		}
		$duplicatecheck = Route::model()->find($criteria);
		if ($duplicatecheck)
		{
			$this->addError($attribute, "Route Path Already Exists");
			return false;
		}
		if (preg_match("/[^A-Za-z0-9\_\-]/", $this->rut_name))
		{
			$this->addError($attribute, "Route Path can consist only the lowercase alphabet, numeric, underscore, and hyphen as characters");
			return false;
		}
		return true;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// class name for the relations automatically generated below.
		// NOTE: you may need to adjust the relation name and the related
		return array(
			'bookings'		 => array(self::HAS_MANY, 'Booking', 'bkg_route_id'),
			'rates'			 => array(self::HAS_MANY, 'Rate', 'rte_route_id'),
			'rutFromCity'	 => array(self::BELONGS_TO, 'Cities', 'rut_from_city_id'),
			'rutToCity'		 => array(self::BELONGS_TO, 'Cities', 'rut_to_city_id'),
			'routeReturns'	 => array(self::HAS_MANY, 'RouteReturn', 'rtn_route_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rut_id'						 => 'id',
			'rut_from_city_id'				 => 'Source',
			'rut_from_address'				 => 'Source Address',
			'rut_to_city_id'				 => 'Destination',
			'rut_to_address'				 => 'Destination Address',
			'rut_estm_distance'				 => 'Route Estimate Distance',
			'rut_estm_time'					 => 'Route Estimate Time',
			'rut_estm_time_min'				 => 'Route Estimate Time',
			'rut_title'						 => 'Route Title',
			'rut_is_promo_code_apply'		 => 'Apply Promo',
			'rut_is_promo_gozo_coins_apply'	 => 'Apply Promo Gozo Coins',
			'rut_is_cod_apply'				 => 'Apply COD',
			'rut_excluded_cabtypes'			 => 'Excluded CabTypes',
			'rut_create_date'				 => 'Route Create Date',
			'rut_active'					 => 'Status',
			'rut_estm_distance_exp'			 => 'Route Estimate Distance (Expected)',
			'rut_actual_distance'			 => 'Route Actual Distance',
			'rut_actual_time'				 => 'Route Actual Time',
			'rut_name'						 => 'Route Path',
			'rut_info'						 => 'Route Description',
			'rut_override_dr'				 => 'Overide Dynamic Routes',
			'rut_special_remarks'			 => 'Route Special Remarks',
			'rut_included_cabmodels'		 => 'Route Included Cab Models'
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

		$criteria->compare('rut_id', $this->rut_id);
		$criteria->compare('rut_from_city_id', $this->rut_from_city_id);
		$criteria->compare('rut_from_address', $this->rut_from_address, true);
		$criteria->compare('rut_to_city_id', $this->rut_to_city_id);
		$criteria->compare('rut_to_address', $this->rut_to_address, true);
		$criteria->compare('rut_estm_distance', $this->rut_estm_distance, true);
		$criteria->compare('rut_estm_time', $this->rut_estm_time, true);
		$criteria->compare('rut_create_date', $this->rut_create_date, true);
		$criteria->compare('rut_active', $this->rut_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Route the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function estmTime()
	{
		$timeArr = [];
		$last	 = 12 * 60;
		for ($i = 60; $i <= $last; $i += 30)
		{
			$hr			 = date('G', mktime(0, $i)) . " Hr ";
			$min		 = (date('i', mktime(0, $i)) != '00') ? '  ' . date('i', mktime(0, $i)) . " min" : '';
			$timeArr[$i] = $hr . '  ' . $min;
		}
		return $timeArr;
	}

	public function getRoute($cityID)
	{
		$criteria				 = new CDbCriteria();
		$criteria->addCondition("rut_from_location LIKE :t OR rut_to_location LIKE :d");
		$criteria->params[':t']	 = $cityID;
		$criteria->params[':d']	 = $cityID;
		return Route::model()->findAll($criteria);
	}

	public function getRouteList()
	{
		$qry		 = "SELECT `t`.`rut_id`, CONCAT(fcity.cty_name,' - ',tcity.cty_name) as rut_name
                FROM   `route` `t`
                 JOIN `cities` `tcity` ON (`t`.`rut_to_city_id` = `tcity`.`cty_id` AND tcity.cty_active = 1)
                 JOIN `cities` `fcity` ON (`t`.`rut_from_city_id` = `fcity`.`cty_id` AND fcity.cty_active = 1)
                WHERE  rut_active = 1 LIMIT 0,1000";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getJSON()
	{
		$qry		 = "SELECT   `rut_id` as id, CONCAT(`fcity`.`cty_name`, ' to ', `tcity`.`cty_name`) as text
                    FROM     `route`
                     JOIN `cities` `tcity` ON (`rut_to_city_id` = `tcity`.`cty_id`) AND (tcity.cty_active = 1)
                     JOIN `cities` `fcity` ON (`rut_from_city_id` = `fcity`.`cty_id`) AND (fcity.cty_active = 1)
                WHERE    rut_active = 1
                ORDER BY rut_name";
		$recordall	 = DBUtil::queryAll($qry);
		$data		 = CJSON::encode($recordall);
		return $data;
	}

	public function getRecommendedRoutes()
	{
		$key	 = "getRecommendedRoutes";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$sql = "SELECT rut_id, CONCAT(c1.cty_name,'-', c2.cty_name) as rutName, IF(count>30,count,0) as popular FROM route
				LEFT JOIN
					(SELECT booking.bkg_from_city_id, booking.bkg_to_city_id, count(*) as count FROM booking
						WHERE booking.bkg_status IN (2,3,4,5,6,7) AND booking.bkg_from_city_id<>booking.bkg_to_city_id
						AND booking.bkg_create_date > DATE_SUB(NOW(), INTERVAL 90 DAY)
						GROUP BY booking.bkg_from_city_id, booking.bkg_to_city_id HAVING count > 5
						ORDER BY count DESC, booking.bkg_create_date DESC limit 0, 400
					) bkg ON rut_active=1 AND route.rut_from_city_id=bkg.bkg_from_city_id AND route.rut_to_city_id=bkg.bkg_to_city_id
				LEFT JOIN
					(SELECT DISTINCT rte_route_id FROM rate WHERE rate.rte_status=1) rate1 ON rate1.rte_route_id=route.rut_id
				INNER JOIN cities c1 ON route.rut_from_city_id=c1.cty_id AND c1.cty_active=1
				INNER JOIN cities c2 ON route.rut_to_city_id=c2.cty_id AND c2.cty_active=1
				WHERE rut_active=1 AND (rate1.rte_route_id IS NOT NULL OR  bkg.count IS NOT NULL) ORDER BY popular DESC, rut_name";

		$rows	 = DBUtil::queryAll($sql);
		$arrRut	 = array();
		foreach ($rows as $row)
		{
			$arrRut[] = array("id" => $row['rut_id'], "text" => $row['rutName']);
		}
		$data = CJSON::encode($arrRut);
		Yii::app()->cache->set($key, $data, 60 * 60 * 24);

		result:
		return $data;
	}

	function getSiteRoutes()
	{

		$sql = "SELECT rut_id, rut_name FROM sitemap_routes ORDER BY popular DESC, rut_name";

		$rows	 = DBUtil::queryAll($sql);
		$arrRut	 = array();

		foreach ($rows as $row)
		{
			$arrRut[] = array("id" => $row['rut_id'], "text" => $row['rut_name']);
		}
		$data = CJSON::encode($arrRut);

		return $data;
	}

	function getSiteRoutes_id()
	{

		$sql = "SELECT rut_name FROM sitemap_routes ORDER BY popular DESC,rut_name";

		$rows	 = DBUtil::queryAll($sql);
		$arrRut	 = array();

		foreach ($rows as $row)
		{
			$arrRut[] = $row['rut_name'];
		}
		return $arrRut;
	}

	/*
	 * check route available or not in site route table and add data if not available
	 * if available then update
	 */

	public function addSiteRoutes()
	{
		//show all rute from databse
		$sql		 = "SELECT GROUP_CONCAT(rut_id)as rut_id FROM sitemap_routes";
		$RouteData	 = DBUtil::queryAll($sql);
		$arrRut		 = array();

		if (count($RouteData) > 0)
		{
			$route_string	 = $RouteData[0]['rut_id'];
			$arrRut			 = explode(",", $route_string);
		}

		// calculate popularity in current route and fetch according to popularity of last 12 month

		$sql	 = "SELECT rut_id, rut_name, IF(cntbkg IS NOT NULL, cntbkg, 0) as popular FROM route
					LEFT JOIN (SELECT booking.bkg_from_city_id, booking.bkg_to_city_id, count(bkg_id) as cntbkg FROM booking 
						WHERE booking.bkg_from_city_id<>booking.bkg_to_city_id 
						AND booking.bkg_status IN (2,3,4,5,6,7)
                        AND booking.bkg_pickup_date > DATE_SUB(now(), INTERVAL 6 MONTH)    
						GROUP BY booking.bkg_from_city_id, booking.bkg_to_city_id 
						) bkg ON route.rut_from_city_id=bkg.bkg_from_city_id AND route.rut_to_city_id=bkg.bkg_to_city_id 
					WHERE rut_active=1 AND rut_name<>'' AND rut_estm_distance<=1000 AND cntbkg <>0 ORDER BY popular DESC, rut_name";
		$rows	 = DBUtil::queryAll($sql);
		if (count($rows > 0))
		{
			foreach ($rows as $row)
			{
				$sql = "INSERT INTO sitemap_routes(rut_id, rut_name, popular) 
						 VALUES ('" . $row['rut_id'] . "', '" . $row['rut_name'] . "', '0.9')
						ON DUPLICATE KEY UPDATE rut_id = '" . $row['rut_id'] . "',popular = '0.9', last_updated_date=now()";
				DBUtil::command($sql)->execute();
			}
		}
	}

	public function allCityRouteList($state_id, $lowerlimit, $number_of_data)
	{
		$sql = "SELECT REPLACE(rut_name, ' ', '_' ) as rut_name FROM route
				INNER JOIN cities ON route.rut_from_city_id = cty_id 
				AND rut_active=1 AND rut_estm_distance < 1000 AND `rut_from_city_id` <> `rut_to_city_id` 
				AND rut_id NOT IN (SELECT sitemap_routes.rut_id FROM sitemap_routes)
				AND cty_state_id = " . $state_id . " LIMIT $lowerlimit, $number_of_data";

		$rows = DBUtil::queryAll($sql);

		return $rows;
	}

	public function cityRouteCount($state_id)
	{
		$sql = "SELECT COUNT(1) as cnt FROM route
				INNER JOIN cities ON route.rut_from_city_id = cty_id 
				AND rut_active=1 AND rut_estm_distance < 1000 AND `rut_from_city_id` <> `rut_to_city_id` 
				AND rut_id NOT IN (SELECT sitemap_routes.rut_id FROM sitemap_routes)
				AND cty_state_id = " . $state_id;

		$rows = DBUtil::command($sql)->queryScalar();

		return $rows;
	}

	// show all rute for city
	public function getRuteListForcity($city_id)
	{
		$sql	 = "SELECT group_concat(rut_name)as routelist FROM route WHERE rut_active=1 AND `rut_from_city_id`= ' " . $city_id . " ' AND `rut_from_city_id`<> `rut_to_city_id` "
				. "AND rut_estm_distance<=1000 AND rut_active=1 ORDER BY  rut_name";
		$rows	 = DBUtil::queryAll($sql);
		if ($rows[0]['routelist'] != "")
		{
			return $rows[0]['routelist'];
		}
	}

	public function getAllRuteListForcity($city_list)
	{
		$sql	 = "SELECT group_concat(rut_name)as routelist FROM route WHERE rut_active=1 AND `rut_from_city_id` IN ( " . $city_list . " ) AND `rut_from_city_id`<> `rut_to_city_id` "
				. "AND rut_estm_distance<=1000 AND rut_active=1 ORDER BY  rut_name";
		$rows	 = DBUtil::queryAll($sql);

		if ($rows[0]['routelist'] != "")
		{
			return $rows[0]['routelist'];
		}
	}

	//SELECT  rut_from_city_id ,GROUP_concat(distinct rut_to_city_id),GROUP_concat(distinct rut_id) as rut_to_city_id FROM `route`
//JOIN rate ON `rut_id`=rte_route_id AND rte_status = 1 WHERE `rut_active`=1 GROUP BY rut_from_city_id



	public function getRutidbyCities($fcity, $tcity, $isModel = '')
	{
		$criteria	 = new CDbCriteria();
		$criteria->compare('rut_from_city_id', $fcity);
		$criteria->compare('rut_to_city_id', $tcity);
		$criteria->compare('rut_active', 1);
		$rtt		 = $this->find($criteria);
		if ($isModel != '')
		{
			return $rtt;
		}
		else
		{
			return $rtt->rut_id;
		}
	}

	public function getbyCities($fcity, $tcity, $active = 1)
	{
		$criteria	 = new CDbCriteria();
		$criteria->compare('rut_from_city_id', $fcity);
		$criteria->compare('rut_to_city_id', $tcity);
		$criteria->compare('rut_active', $active);
		$rtt		 = $this->resetScope()->find($criteria);

		return $rtt;
	}

	function clean($string)
	{
		$string	 = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string	 = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		return preg_replace('/-+/', '_', $string); // Replaces multiple hyphens with single one.
	}

	public function generateAlias($fcity, $tcity)
	{
		$fcityModel	 = Cities::model()->findByPk($fcity);
		$dcityModel	 = Cities::model()->findByPk($tcity);
		$fname		 = $this->clean($fcityModel->cty_name);
		$dname		 = $this->clean($dcityModel->cty_name);
		$rutName	 = strtolower($fname . '-' . $dname);
		$rmodel		 = $this->getByName($rutName);
		if ($rmodel)
		{
			$fstatename	 = $this->clean($fcityModel->ctyState->stt_name);
			$dstatename	 = $this->clean($dcityModel->ctyState->stt_name);
			$rtname		 = strtolower($fname . "_" . $fstatename . "-" . $dname . "_" . $dstatename);
			$rutName	 = $rtname;
			$i			 = 0;
			while ($this->getByName($rutName))
			{
				$i++;
				$rutName = $rtname . "-" . $i;
			}
		}
		return $rutName;
	}

	public function populate($fcity, $tcity)
	{
		$result['success'] = true;
		if ($fcity == "" || $tcity == "")
		{
			$result['success'] = false;
			goto end;
		}

		$rutModel = $this->getbyCities($fcity, $tcity);
		if (!$rutModel)
		{
			$rutModel = $this->getbyCities($fcity, $tcity, 2);
		}
		if (!$rutModel)
		{
			$rutModel = $this->getbyCities($fcity, $tcity, 0);
		}
		$result['model'] = $rutModel;

		if ($rutModel && $rutModel->rut_active == 1)
		{
			return $result;
		}

		$fcityModel	 = Cities::model()->findByPk($fcity);
		$dcityModel	 = Cities::model()->findByPk($tcity);
		$isIP		 = false;
		$result		 = ["success" => false];
		if ($fcityModel->cty_lat != '' && $dcityModel->cty_lat != '')
		{
			$isIP		 = true;
			$sourcePlace = \Stub\common\Place::init($fcityModel->cty_lat, $fcityModel->cty_long);
			$destPlace	 = \Stub\common\Place::init($dcityModel->cty_lat, $dcityModel->cty_long);
			$dmxModel	 = DistanceMatrix::getByCoordinates($sourcePlace, $destPlace);
			if ($dmxModel)
			{
				$distance		 = $actualDistance	 = $dmxModel->dmx_distance;
				$time			 = $actualTime		 = $dmxModel->dmx_duration;
				$result			 = ["success" => true];
			}
		}

		if ($result['success'])
		{
			if (!$rutModel)
			{
				$rutModel = new Route();
			}
			$rutModel->rut_from_city_id		 = $fcity;
			$rutModel->rut_to_city_id		 = $tcity;
			$rutModel->rut_estm_distance	 = $distance;
			$rutModel->rut_estm_time		 = $time;
			$rutModel->rut_actual_distance	 = $actualDistance;
			$rutModel->rut_actual_time		 = $actualTime;
			$rutModel->rut_active			 = 1;
			if ($rutModel->rut_name == "")
			{
				$rutModel->rut_name = $this->generateAlias($fcity, $tcity);
			}
			$rutModel->save();
			$result['model'] = $rutModel;
		}
		end:
		return $result;
	}

	public function calculateAPIDistance($address1, $address2, $isIP = false, $fromCityId = null, $toCityId = null)
	{
		$fromAdderss	 = explode(',', $address1);
		$toAddress		 = explode(',', $address2);
		/* @var $place \Stub\common\Place */
		$place			 = new \Stub\common\Place();
		$fromPlaceObj	 = $place->initGoogleRoute($fromAdderss[0], $fromAdderss[1]);
		$toPlaceObj		 = $place->initGoogleRoute($toAddress[0], $toAddress[1]);
		$result			 = self::calculate($fromPlaceObj, $toPlaceObj);
		return $result;
	}

	public function calculateAPIDistanceByAddress($address1, $address2)
	{
		/* @var $place \Stub\common\Place */
		$place			 = new \Stub\common\Place();
		$fromPlaceObj	 = $place->initGoogleRoute('', '', '', $address1);
		$toPlaceObj		 = $place->initGoogleRoute('', '', '', $address2);
		$result			 = self::calculate($fromPlaceObj, $toPlaceObj);
		return $result;
	}

	public static function calculate($fromPlaceObj, $toPlaceObj)
	{
		$srcLtLngModel	 = LatLong::getDetailsByPlace($fromPlaceObj);
		$dstLtLngModel	 = LatLong::getDetailsByPlace($toPlaceObj);
		$distModel		 = DistanceMatrix::calculate($srcLtLngModel, $dstLtLngModel);
		if ($distModel)
		{
			$distance1				 = $distModel->dmx_distance;
			$time1					 = $distModel->dmx_duration;
			$result['gdistance'][0]	 = ['time' => $time1, 'dist' => $distance1];

			//$grace				 = (round($distance1 / 10) > 10) ? 10 : ceil($distance1 / 10);
			//$result['distance']	 = (round($distance1 / 10) * 10) + $grace;
			$result['distance']	 = round($distance1);
			//$result['time']		 = (ceil($time1 / 15) * (15)) + 30;
			$result['time']		 = ceil($time1);
			$result['fromCtyId'] = $srcLtLngModel->ltg_city_id;
			$result['toCtyId']	 = $dstLtLngModel->ltg_city_id;
			$result['success']	 = true;
		}
		return $result;
	}

	public function calculateAPIPlaceDistance($route)
	{
		$json			 = json_encode(Filter::removeNull($route->getAttributes()));
		Logger::profile("Route::calculateAPIPlaceDistance $json Started");
		$result			 = [];
		/* @var $place \Stub\common\Place */
		$place			 = new \Stub\common\Place();
		$fromPlaceObj	 = $place->initGoogleRoute($route->brt_from_latitude, $route->brt_from_longitude, $route->brt_from_place_id, $route->brt_from_formatted_address);
		$toPlaceObj		 = $place->initGoogleRoute($route->brt_to_latitude, $route->brt_to_longitude, $route->brt_to_place_id, $route->brt_to_formatted_address);
		$result			 = self::calculate($fromPlaceObj, $toPlaceObj);
		Logger::profile("Route::calculateAPIPlaceDistance $json Ended");
		return $result;
	}

	public function getReturnRoute1($routeID)
	{
		$data		 = Route::model()->find('rut_id=:route', array('route' => $routeID));
		$tocity		 = $data->rut_to_city_id;
		$fromcity	 = $data->rut_from_city_id;
		$returnRoute = Route::model()->find('rut_to_city_id=:tocity AND rut_from_city_id=:fcity', array('tocity' => $fromcity, 'fcity' => $tocity));
		return $returnRoute;
	}

	/**
	 * @deprecated since version 02-10-2019
	 * 
	 * @param type $routeID
	 * @param type $amount
	 * @param type $type_id
	 * @param type $vnd_amount
	 * @param type $type
	 * @param type $tollTax
	 * @param type $stateTax
	 */
	public function getreturnroute($routeID, $amount, $type_id, $vnd_amount = '', $type = '', $tollTax = '', $stateTax = '')
	{
		exit;

		$data		 = Route::model()->find('rut_id=:route', array('route' => $routeID));
		$tocity		 = $data->rut_to_city_id;
		$fromcity	 = $data->rut_from_city_id;

		$returndata		 = Route::model()->find('rut_to_city_id=:tocity AND rut_from_city_id=:fcity', array('tocity' => $fromcity, 'fcity' => $tocity));
		$returnrouteID	 = $returndata->rut_id;

		if ($returnrouteID != '')
		{
			$returnrate = Rate::model()->find('rte_route_id=:id AND rte_vehicletype_id=:type', array('id' => $returnrouteID, 'type' => $type_id));
			$returnrate->rte_id;

			if ($returnrate->rte_id != '')
			{
				$old_rate						 = $returnrate->rte_amount;
				$returnrate->rte_amount			 = $amount;
				$returnrate->rte_vendor_amount	 = $vnd_amount;
				$returnrate->rte_vehicletype_id	 = $type_id;
				$returnrate->rte_toll_tax		 = $tollTax;
				$returnrate->rte_state_tax		 = $stateTax;
				$returnrate->rte_status			 = 1;

				if ($amount != $old_rate)
				{
					$remark		 = $returnrate->rte_log;
					$dt			 = date('Y-m-d H:i:s');
					$user		 = Yii::app()->user->getId();
					$new_remark	 = "rate changed";
					if ($new_remark != '')
					{
						if (is_string($remark))
						{
							$newcomm = CJSON::decode($remark);
							if ($remark != '' && CJSON::decode($remark) == '')
							{
								$newcomm = array(array(0 => $user, 1 => $returnrate->rte_create_date, 2 => $remark, 3 => $old_rate, 4 => $amount));
							}
						}
						else if (is_array($remark))
						{
							$newcomm = $remark;
						}
						if ($newcomm == false)
						{
							$newcomm = array();
						}
						while (count($newcomm) >= 50)
						{
							array_pop($newcomm);
						}
						array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $new_remark, 3 => $old_rate, 4 => $amount));
						$returnrate->rte_log = CJSON::encode($newcomm);
					}
				}
				$returnrate->save();
			}
			else
			{
				if ($type_id == 0)
				{
					$ratemodel				 = Rate::model()->checkExisting($returnrouteID, $type);
					$ratemodel->rte_status	 = 0;
					$ratemodel->update();
				}
				else
				{
					if ($amount > 0)
					{
						$ratemodel						 = new Rate();
//                        $ratemodel->rte_amount = $amount;
						$ratemodel->rte_vendor_amount	 = $vnd_amount;
						$ratemodel->rte_route_id		 = $returnrouteID;
						$ratemodel->rte_vehicletype_id	 = $type_id;
						$ratemodel->rte_toll_tax		 = $tollTax;
						$ratemodel->rte_state_tax		 = $stateTax;
						$ratemodel->rte_status			 = 1;
						$ratemodel->insert();
					}
				}
			}
		}
	}

	/**
	 * @deprecated 24/06/2021 by ramala
	 * new Rate::addRates()
	 * This function is used for updating the details of the vehicle rates
	 * in the rates table if data is different from the old data
	 * 
	 * @param type $models [Consists of the entire data set which will be used for validation]
	 * @param type $receivedData [Consists of single object which is passed from the loop]
	 */
	public static function updateRatesModel($models, $receivedData)
	{
		$old_vendor_amount	 = "";
		$old_toll_tax		 = "";
		$old_state_tax		 = "";
		$old_min_markup		 = "";

		//echo print_r($models, true);
		//Loops through the data to find the record the current svcId
		foreach ($models as $oldRate)
		{
			if (($oldRate["scv_id"] == $receivedData["scv_id"]) && (!empty($oldRate["rte_id"]))) //Checks for the current object data
			{
				$rateId = $oldRate["rte_id"];

				$old_vendor_amount	 = $oldRate["rte_vendor_amount"];
				$old_toll_tax		 = $oldRate["rte_toll_tax"];
				$old_state_tax		 = $oldRate["rte_state_tax"];
				$old_min_markup		 = $oldRate["rte_minimum_markup"];
				$oldLogData			 = $oldRate["rte_log"];
			}
		}

		//Return if you don't find the corresponding route id is not assigned or blank
		if (empty($rateId))
		{
			return 0;
		}

		$receivedVendorAmount	 = $receivedData["rte_vendor_amount"];
		$receivedTollTax		 = $receivedData["rte_toll_tax"];
		$receivedStateTax		 = $receivedData["rte_state_tax"];
		$receivedMinBreakUp		 = $receivedData["rte_minimum_markup"];

		/**
		 * Checks whether the received data differs or not and it will update the record
		 * If it differs then update the records
		 */
		if ($receivedVendorAmount != $old_vendor_amount || $receivedTollTax != $old_toll_tax || $receivedStateTax != $old_state_tax || $receivedMinBreakUp != $old_min_markup)
		{
			$newDateTime		 = date("Y-m-d H:i:s");
			$modifiedByUserId	 = Yii::app()->user->getId();
			$newRemarks			 = "Rate has been update by loggedIn UserId : " . $modifiedByUserId;

			$prevRemarks = $oldLogData;

			if (!empty($prevRemarks))
			{
				$decodedRemark = CJSON::decode($prevRemarks);

				$newLogData = array
					(
					0	 => $modifiedByUserId,
					1	 => $newDateTime,
					2	 => $newRemarks,
					3	 => 'VendorAmount:' . $old_vendor_amount . ' -> ' . $receivedVendorAmount,
					4	 => 'TollTax:' . $old_toll_tax . ' -> ' . $receivedTollTax,
					5	 => 'Statetax:' . $old_state_tax . ' -> ' . $receivedStateTax,
					6	 => 'Min Markup:' . $old_min_markup . ' -> ' . $receivedMinBreakUp
				);
			}

			array_unshift($decodedRemark, $newLogData);

			$updateLog = CJSON::encode($decodedRemark);

			$updateModel = Rate::model()->findByPk($rateId);

			$updateModel->rte_vendor_amount	 = $receivedVendorAmount;
			$updateModel->rte_toll_tax		 = $receivedTollTax;
			$updateModel->rte_state_tax		 = $receivedStateTax;
			$updateModel->rte_minimum_markup = $receivedMinBreakUp;
			$updateModel->rte_vehicletype_id = $receivedData["scv_id"];
			$updateModel->rte_log			 = $updateLog;
			$updateModel->rte_status		 = 1;

			$updateModel->save();
		}
	}

	/**
	 * @deprecated 24/06/2021 by ramala
	 * new Rate::addRates();
	 * 
	 * This model function is being used for updating the reverse route details if the reverse route update
	 * flag is set as 1 during form submission.
	 * 
	 * @param type $arr
	 */
	public static function updateReturnRoute($arr = [])
	{
		$routeId		 = $arr["rut_id"]; //Route Id
		$newRateDetails	 = $arr["rateData"]; //Received on form submit

		$data = Route::model()->find("rut_id=:route", array("route" => $routeId));

		$tocity		 = $data->rut_to_city_id;
		$fromcity	 = $data->rut_from_city_id;

		//Fetches the return route Id
		$returnRouteDataQuery = "
            SELECT r.rut_id
            FROM route r
            WHERE rut_from_city_id = $tocity 
                AND rut_to_city_id = $fromcity
        ";

		$returnRouteData = DBUtil::queryAll($returnRouteDataQuery, DBUtil::SDB());

		$returnRouteId = $returnRouteData[0]["rut_id"];

		if (empty($returnRouteId))
		{
			return 0;
		}
		$returnRateDetails = Rate::model()->getVehicleDetailsByRoute($returnRouteId); //Return route details

		foreach ($newRateDetails as $rate)
		{
			//Procced if svc_id is greater than zero. Ids with Zero will not be considered in the transaction
			if ($rate["scv_id"] <= 0)
			{
				continue;
			}

			/**
			 * Finding the corresponding return rateId based on return Rate model
			 */
			foreach ($returnRateDetails as $returnRate)
			{
				if ($rate["scv_id"] == $returnRate["scv_id"])
				{
					$returnRateId = $returnRate["rte_id"];
				}
			}

			/**
			 * Case 1: If rateId and corresponding return rateId exists, update the details.
			 * Case 2: If rateId and corresponding return rateId exists and vendor amount is zero for the one way then update the return route entry as inactive
			 * Case 3: If return rateId doesn't exists, Make a new entry
			 */
			if ($rate["rte_id"] > 0 && $returnRateId > 0)
			{
				Route::updateRatesModel($returnRateDetails, $rate);
			}
			else if ($rate["rte_id"] > 0 && ($rate["rte_vendor_amount"] == 0) && $returnRateId > 0)
			{
				$updateModel = Rate::model()->findByPk($returnRouteId);

				$updateModel->rte_status = 0;

				$updateModel->save();
			}
			else
			{
				$ratemodel = new Rate();

				$ratemodel->rte_vendor_amount	 = $rate["rte_vendor_amount"];
				$ratemodel->rte_route_id		 = $returnRouteId;
				$ratemodel->rte_toll_tax		 = $rate["rte_toll_tax"];
				$ratemodel->rte_state_tax		 = $rate["rte_state_tax"];
				$ratemodel->rte_minimum_markup	 = $rate["rte_minimum_markup"];
				$ratemodel->rte_vehicletype_id	 = $rate["scv_id"];
				$ratemodel->rte_status			 = 1;

				$ratemodel->save();
			}
		}
	}

	/**
	 * This model function checks whether this route already exists or not
	 * @param [Array] $cityDetails
	 */
	public static function checkRouteExists($toCityId, $fromCityId, $flag, $returnType)
	{
		$condition = "";
		if ($flag == 1)
		{
			$condition .= "	rut_from_city_id = $fromCityId 
                AND rut_to_city_id = $toCityId
			";
		}

		if ($flag == 2)
		{
			$condition .= "	rut_from_city_id = $toCityId 
                AND rut_to_city_id = $fromCityId
			";
		}


		//Fetches the route Id
		$returnRouteDataQuery = "
            SELECT r.rut_id
            FROM route r
            WHERE $condition
        ";

		$returnRouteData = DBUtil::queryAll($returnRouteDataQuery, DBUtil::SDB());

		$returnRouteId = $returnRouteData[0]["rut_id"];

		if ($returnType == "boolean")
		{
			if ($returnRouteId > 0)
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}

		if ($returnType == "value")
		{
			return $returnRouteId;
		}
	}

	/**
	 * This model function returns city name based on city id
	 * @param type $cityId
	 * @return string
	 */
	public static function getRouteName($cityId)
	{
		if (empty($cityId))
		{
			return "";
		}
		//Fetches the city name
		$returnCityNameQuery = "
			SELECT c.cty_name
			FROM cities c
			WHERE c.cty_id = $cityId
				AND c.cty_active = 1
		";

		$returnCityData = DBUtil::queryAll($returnCityNameQuery, DBUtil::SDB());

		$returnCityName = $returnCityData[0]["cty_name"];

		return $returnCityName;
	}

	public function getByName($name)
	{
		$criteria		 = new CDbCriteria();
		$criteria->compare('rut_name', $name);
		$criteria->with	 = ['rutFromCity' => ['select' => ['cty_active', 'cty_name', 'cty_city_desc', 'cty_full_name', 'cty_short_desc', 'cty_pickup_drop_info', 'cty_ncr', 'cty_lat', 'cty_long', 'cty_has_airport'], 'with' => ['ctyState' => ['alias' => 'fstate', 'select' => ['stt_name']]]], 'rutToCity' => ['select' => ['cty_active', 'cty_name', 'cty_city_desc', 'cty_short_desc', 'cty_pickup_drop_info', 'cty_lat', 'cty_long', 'cty_has_airport'], 'with' => ['ctyState' => ['alias' => 'tstate', 'select' => ['stt_name']]]]];
		return $this->find($criteria);
	}

	public function fetchList()
	{
		$where = "";
		if ($this->rut_active != '')
		{
			$where .= " AND rut_active =$this->rut_active";
		}
		if ($this->rut_from_city_id != '')
		{
			$where .= " AND rut_from_city_id =$this->rut_from_city_id";
		}
		if ($this->rut_to_city_id != '')
		{
			$where .= " AND rut_to_city_id =$this->rut_to_city_id";
		}
		if ($this->rut_route_city_id != '')
		{
			$where .= " AND  (rut_from_city_id = $this->rut_route_city_id OR rut_to_city_id = $this->rut_route_city_id)";
		}
		if ($this->rut_source_zone != '')
		{
			$where .= ' AND rut_from_city_id IN (SELECT
                        DISTINCT (c1.cty_id)
                        FROM `zones` z1
                        INNER JOIN zone_cities zc1 ON zc1.zct_zon_id = z1.zon_id AND zc1.zct_active = 1
                        INNER JOIN cities c1 ON zc1.zct_cty_id = c1.cty_id AND c1.cty_active = 1 AND c1.cty_service_active = 1
                        INNER JOIN zone_cities zc2 ON zc2.zct_cty_id = c1.cty_id AND zc2.zct_active = 1
                        INNER JOIN `zones` z2 ON z2.zon_id = zc2.zct_zon_id AND z2.zon_active = 1
                        WHERE z1.zon_active = 1 AND z1.`zon_id` = ' . $this->rut_source_zone . ')';
		}
		if ($this->rut_destination_zone != '')
		{
			$where .= ' AND rut_to_city_id IN (SELECT
                        DISTINCT (c1.cty_id)
                        FROM `zones` z1
                        INNER JOIN zone_cities zc1 ON zc1.zct_zon_id = z1.zon_id AND zc1.zct_active = 1
                        INNER JOIN cities c1 ON zc1.zct_cty_id = c1.cty_id AND c1.cty_active = 1 AND c1.cty_service_active = 1
                        INNER JOIN zone_cities zc2 ON zc2.zct_cty_id = c1.cty_id AND zc2.zct_active = 1
                        INNER JOIN `zones` z2 ON z2.zon_id = zc2.zct_zon_id AND z2.zon_active = 1
                        WHERE z1.zon_active = 1 AND z1.`zon_id` = ' . $this->rut_destination_zone . ')';
		}
		$sql		 = "SELECT 
				rut_id,
				rutFromCity.cty_name as FromCity,
				rutToCity.cty_name as ToCity, 
				rut_estm_distance, 
				rut_estm_time,
				rut_create_date, 
				rut_active,
				rut_override_dr
				FROM  route 
				INNER JOIN cities rutFromCity ON   route.rut_from_city_id = rutFromCity.cty_id AND rutFromCity.cty_active = 1
				INNER JOIN cities rutToCity   ON   route.rut_to_city_id = rutToCity.cty_id AND rutToCity.cty_active = 1
				WHERE 1 $where ";
		$sqlCount	 = "SELECT 
					count(*)
					FROM  route 
					JOIN cities rutFromCity ON   route.rut_from_city_id = rutFromCity.cty_id AND rutFromCity.cty_active = 1
					JOIN cities rutToCity   ON   route.rut_to_city_id = rutToCity.cty_id AND rutToCity.cty_active = 1
					WHERE 1  $where ";

		$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['FromCity', 'ToCity', 'rut_estm_distance', 'rut_estm_time', 'rut_create_date', 'rut_active'],
				'defaultOrder'	 => ''
			],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}
	
	public static function getByMatchingKeyword($route)
	{
		$keywords = explode("-", $route);
		$fromCities = array_splice($keywords, 0, -1);
		$keyFCity = implode(" ", $fromCities);
		$res = Cities::getByMatchingKeyword($keyFCity);
		$fcity	 = "";
		foreach ($res as $row)
		{
			if ($fcity == "")
			{
				$fcity = $row["cty_id"];
			}

			if ($row["cty_service_active"] == 1)
			{
				$fcity = $row["cty_id"];
				break;
			}
		}
		
		if($fcity == "")
		{
			return null;
		}
		
		$keywords = explode("-", $route);
		$toCities = array_splice($keywords, 1);
		$keyTCity = implode(" ", $toCities);
		$res = Cities::getByMatchingKeyword($keyTCity);
		
		$tcity	 = "";
		foreach ($res as $row)
		{
			if($fcity == $row["cty_id"])
			{
				continue;
			}
			if ($tcity == "")
			{
				$tcity = $row["cty_id"];
			}

			if ($row["cty_service_active"] == 1)
			{
				$tcity = $row["cty_id"];
				break;
			}
		}
		
		if($tcity == "")
		{
			return null;
		}
		
		$result = Route::model()->populate($fcity, $tcity);
		
		if($result["success"])
		{
			return $result["model"];
		}
		
		return null;
	}

	public function fetchActiveList($checkAlias = false)
	{
		if ($checkAlias)
		{
			$sql = "rut_name <> '' AND ";
		}

		$qry		 = "SELECT   `rut_name`  FROM  `route`
            LEFT  JOIN `cities` `rutFromCity` ON (`rut_from_city_id` = `rutFromCity`.`cty_id`) AND (rutFromCity.cty_active = 1)
            LEFT  JOIN `cities` `rutToCity` ON (`rut_to_city_id` = `rutToCity`.`cty_id`) AND (rutToCity.cty_active = 1)
            WHERE " . $sql . "rut_id IN (SELECT DISTINCT rte_route_id
            FROM   rate  WHERE  rte_status = 1) AND rutFromCity.cty_active = 1 AND rutToCity.cty_active = 1
            ORDER BY rutFromCity.cty_name, rutToCity.cty_name";
		$recordall	 = DBUtil::queryAll($qry);
		return $recordall;
	}

	/**
	 * @return string */
	public function SQLListWithAlias()
	{
		$sql = "SELECT rut_id, rut_name, rutFromCity.cty_name as from_city_name, rutToCity.cty_name as to_city_name FROM route
				INNER JOIN cities as rutFromCity ON route.rut_from_city_id=rutFromCity.cty_id AND rutFromCity.cty_service_active=1
				INNER JOIN cities as rutToCity ON route.rut_to_city_id=rutToCity.cty_id AND rutToCity.cty_service_active=1
				WHERE rut_active=1 AND rut_name<>'' AND rut_from_city_id <> rut_to_city_id
                                AND (rut_id IN (SELECT DISTINCT rte_route_id FROM rate WHERE rte_status=1) OR (
rut_from_city_id IN (SELECT cty_id FROM city_list) AND rut_estm_distance BETWEEN 5 AND 750))
			";
		return $sql;
	}

	/**
	 * @return CDbDataReader */
	public function getListWithAlias($start, $limit = 49500)
	{
		if ($start !== null)
		{
			$qry = " LIMIT $start, $limit";
		}
		$sql = $this->SQLListWithAlias() .
				"
			ORDER BY rut_id ASC $qry
			";

		$cdb		 = Yii::app()->db->createCommand();
		$cdb->text	 = $sql;

		$dataReader = $cdb->query();
		return $dataReader;
	}

	/**
	 * @return CSqlDataProvider */
	public function ListWithAliasProvider($limit = 50)
	{
		$sql			 = $this->SQLListWithAlias();
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) a")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['rut_id', 'rut_name'],
				'defaultOrder'	 => 'rut_name ASC'],
			'pagination'	 => ['pageSize' => $limit],
		]);
		return $dataprovider;
	}

	public function addLog($oldData, $newData)
	{
		if ($oldData)
		{
			$getDifference	 = array_diff_assoc($oldData, $newData);
			$excludeFields	 = ['rut_id', 'rut_from_address', 'rut_to_address', 'rut_active',
				'rut_modified_on', 'rut_create_date', 'rut_log'];
			foreach ($excludeFields as $fields)
			{
				if (isset($getDifference[$fields]))
				{
					unset($getDifference[$fields]);
				}
			}
			$remark	 = $this->rut_log;
			$dt		 = date('Y-m-d H:i:s');
			$user	 = Yii::app()->user->getId();
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
		return $remark;
	}

	/**
	 * @deprecated since version 02-10-2019
	 * @author ramala
	 */
	public function rateByRoutes()
	{
		exit();
		$sql = "
	    SELECT 
		route.rut_id,
		route.rut_name,
		agent_routes_rate.agtr_route_id,
		agent_routes_rate.agtr_name,
		agent_routes_rate.agtr_rate,
		rate.rte_excl_amount,
		rate.rte_amount,
		rate.rte_vehicletype_id,
		agent_routes_rate.agtr_agent_id,
		scvc.scv_scc_id
		FROM route 
        LEFT JOIN agent_routes_rate ON FIND_IN_SET(route.rut_id,agent_routes_rate.agtr_route_id) 
        LEFT JOIN agents ON agents.agt_id=agent_routes_rate.agtr_agent_id 
        LEFT JOIN rate ON rate.rte_route_id=route.rut_id 
        LEFT JOIN svc_class_vhc_cat AS scvc  ON rate.rte_vehicletype_id  = scvc.scv_id  
        LEFT JOIN vehicle_category AS vc  ON vc.vct_id = scvc.scv_vct_id  
        LEFT JOIN service_class AS sc   ON sc.scc_id = scvc.scv_scc_id
        WHERE route.rut_id=155 
        GROUP BY agents.agt_id 
        ORDER BY rate.rte_amount";

		$record = DBUtil::queryAll($sql);
		foreach ($record as $val)
		{
			$recSedan[] = $val['agtr_rate'];
		}
		$data['rut_name'] = $this->calculateMedian($recSedan);
	}

	public function calculateMedian($arr)
	{
		$count		 = count($arr); //total numbers in array
		$middleval	 = floor(($count - 1) / 2); // find the middle value, or the lowest middle value
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

	public function getTripDistancebyCityArr($cityArr = [])
	{
		$cnt	 = sizeof($cityArr);
		$dist	 = 0;
		for ($i = 0; $i < $cnt - 1; $i++)
		{
			$dist += $this->getRouteDistancebyCities($cityArr[$i], $cityArr[$i + 1]);
		}
		return $dist;
	}

	public function getTripDurationbyCityArr($cityArr = [])
	{
		$cnt	 = sizeof($cityArr);
		$dist	 = 0;
		for ($i = 0; $i < $cnt - 1; $i++)
		{
			$dist += $this->getRouteDurationbyCities($cityArr[$i], $cityArr[$i + 1]);
		}
		return $dist;
	}

	public function getRouteDistancebyCities($scity, $dcity)
	{
		$dist	 = '';
		$rmodel	 = $this->getbyCities($scity, $dcity);
		if ($rmodel->rut_estm_distance && $rmodel->rut_estm_distance > 0)
		{
			$dist = $rmodel->rut_estm_distance;
		}
		else
		{
			$scityModel		 = $rmodel->rutFromCity;
			$dcityModel		 = $rmodel->rutToCity;
			/* @var $place \Stub\common\Place */
			$fromPlaceObj	 = \Stub\common\Place::initGoogleRoute($scityModel->cty_lat, $scityModel->cty_long);
			$toPlaceObj		 = \Stub\common\Place::initGoogleRoute($dcityModel->cty_lat, $dcityModel->cty_long);
			$ar				 = Booking::model()->getDistance($fromPlaceObj, $toPlaceObj);
			$dist			 = $ar['totdist'];
			//$dist		 = Quotation::model()->getDistance([$scityname, $dcityname]);
		}
		return $dist;
	}

	public function getRouteDurationbyCities($scity, $dcity)
	{
		$time	 = 0;
		$result	 = $this->populate($scity, $dcity);
		$rmodel	 = $result['model'];
		if ($rmodel->rut_estm_time && $rmodel->rut_estm_time > 0)
		{
			$time1	 = $rmodel->rut_estm_time;
			$time	 = $time1;
		}
		else
		{
			$sCityModel	 = $rmodel->rutFromCity;
			$dCityModel	 = $rmodel->rutToCity;
			if ($sCityModel == '' && $dCityModel == '')
			{
				$sCityModel	 = Cities::model()->findByPk($scity);
				$dCityModel	 = Cities::model()->findByPk($dcity);
			}
			/* @var $place \Stub\common\Place */
			$place			 = new \Stub\common\Place();
			$fromPlaceObj	 = $place->initGoogleRoute($sCityModel->cty_lat, $sCityModel->cty_long, $sCityModel->cty_place_id, $sCityModel->cty_garage_address);
			$toPlaceObj		 = $place->initGoogleRoute($dCityModel->cty_lat, $dCityModel->cty_long, $dCityModel->cty_place_id, $dCityModel->cty_garage_address);
			$ar				 = Booking::model()->getDistance($fromPlaceObj, $toPlaceObj);
			$time2			 = $ar['totmin'];
			$time			 = $time2;
		}
		if ($time1 != '' && $time2 != '')
		{
			if ($time1 > $time2)
			{
				$time = $time1;
			}
			else
			{
				$time = $time2;
			}
		}
		return $time;
	}

	public function getNearestMinRates($routes, $cabType)
	{
		$condition	 = [];
		$fromCities	 = [];
		$toCities	 = [];
		foreach ($routes as $route)
		{
			$condition[]	 = "(rut_from_city_id={$route['fromCity']} AND rut_to_city_id={$route['toCity']})";
			$fromCities[]	 = $route['fromCity'];
			$toCities[]		 = $route['toCity'];
		}
		$fcities = implode(",", $fromCities);
		$tcities = implode(",", $toCities);
		$route	 = $routes[0];
		$sql	 = "

            SELECT route.rut_name, r1.rut_name, r2.rut_name, r3.rut_name,
	    route.rut_estm_distance, r3.rut_estm_distance as rDistance,
	    IF(route.rut_from_city_id IN ('$fcities'),1,0) as fRank,
	    IF(route.rut_to_city_id IN ('$tcities'),1,0) as tRank,
            IF(route.rut_from_city_id=r1.rut_to_city_id,0,r1.rut_estm_distance) as fromDistance,
            IF(route.rut_to_city_id=r2.rut_from_city_id,0,r2.rut_estm_distance) as toDistance,
	    rate.rte_vendor_amount as rutvndamount, rate.rte_amount as amount,
	    rate.rte_toll_tax, rate.rte_state_tax,rte_minimum_markup
            FROM route
            INNER JOIN rate ON rate.rte_route_id = route.rut_id AND rate.rte_status=1 AND route.rut_active=1 AND
              (rut_from_city_id IN (
                  SELECT rut_from_city_id FROM route WHERE rut_active=1 AND ((rut_to_city_id={$route['fromCity']} AND rut_estm_distance<50))
               ) OR rut_from_city_id={$route['fromCity']} )
               AND (rut_to_city_id IN (
                  SELECT rut_to_city_id FROM route WHERE rut_active=1 AND ((rut_from_city_id={$route['toCity']} AND rut_estm_distance<50))
               ) OR rut_to_city_id = {$route['toCity']})
            LEFT JOIN route r1 ON r1.rut_from_city_id=route.rut_from_city_id AND r1.rut_to_city_id={$route['fromCity']} AND r1.rut_active=1
            LEFT JOIN route r2 ON r2.rut_to_city_id=route.rut_to_city_id AND r2.rut_from_city_id={$route['toCity']} AND r2.rut_active=1
            LEFT JOIN route r3 ON r3.rut_from_city_id=r1.rut_to_city_id AND r3.rut_to_city_id=r2.rut_from_city_id  AND r3.rut_active=1 
			 where rte_vehicletype_id= $cabType

            ORDER BY (r3.rut_estm_distance + fromDistance + toDistance) ASC, fRank DESC, tRank DESC, (rte_vendor_amount-rte_toll_tax-rte_state_tax) ASC
     ";

		$record = DBUtil::queryRow($sql);
		return $record;
	}

	public function getRouteRates($fromCity, $toCity, $cabType)
	{
		$sql = "SELECT route.rut_id, route.rut_from_city_id start,route.rut_to_city_id end, NULL AS fromAlias, NULL AS toAlias, rut_name,route.rut_estm_distance as quotedDistance, route.rut_estm_distance as rateDistance,
                                0 AS extraDistance, 
								0 AS extraStartDistance,
								1 as rank,
                                0 as startTime, 0 as endTime, route.rut_estm_time,rate.*
                        FROM   route
						INNER JOIN rate ON rut_id = rate.rte_route_id AND rate.rte_status=1 AND rate.rte_vendor_amount>0 AND rate.rte_vehicletype_id=:cabType
                        WHERE  rut_active = 1 AND route.rut_from_city_id =:fromCity AND route.rut_to_city_id =:toCity";

		$res = DBUtil::queryRow($sql, DBUtil::SDB(), ['cabType' => $cabType, 'fromCity' => $fromCity, 'toCity' => $toCity], 3600 * 24, CacheDependency::Type_Rates);

		return $res;
	}

	public static function getNearestRates($fromCity, $toCity, $cabType)
	{
		$key	 = md5("Route::getNearestRates:" . "_" . $fromCity . "_" . $toCity . "_" . $cabType);
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			$res = $data;
			goto result;
		}

		$priceRule = PriceRule::getByCity($fromCity, 1, $cabType);

		$costPerKm = $priceRule->prr_rate_per_km;
		if (empty($costPerKm))
		{
			$costPerKm = 7;
		}
		$tableName	 = "nearest_route_" . $fromCity . "_" . $toCity;
		//	DBUtil::dropTempTable($tableName);
		Route::createNearestRouteTemp($fromCity, $toCity, $tableName, DBUtil::SDB2());
		$sql		 = "SELECT *, (rte_vendor_amount + extraDistance * $costPerKm) as totalCost FROM (SELECT * FROM $tableName) a
					INNER JOIN rate ON a.rut_id = rate.rte_route_id AND rte_status=1 AND rate.rte_vehicletype_id=:cabType AND rate.rte_vendor_amount <>0
					WHERE 1 ORDER BY rank DESC, totalCost ASC LIMIT 0,1";
		$res		 = DBUtil::queryRow($sql, DBUtil::SDB2(), ['cabType' => $cabType], 86400 * 1, CacheDependency::Type_Rates);
		//		DBUtil::dropTempTable($tableName);
		if (!$res)
		{
			$res = Route::model()->getNearestRouteRate($fromCity, $toCity, $cabType, $costPerKm);
		}
		if (!$res)
		{
			$res = null;
		}

		Yii::app()->cache->set($key, $res, 1 * 24 * 60 * 60, new CacheDependency(CacheDependency::Type_Rates));
		result:
		return $res;
	}

	public function getbyRouteAmount($rutId, $cabType)
	{
		$sql = "SELECT rate.rte_amount as amount, route.rut_estm_distance as distance"
				. " FROM rate "
				. " LEFT JOIN route ON route.rut_id = rate.rte_route_id "
				. " LEFT JOIN svc_class_vhc_cat AS scvc  ON rte_vehicletype_id  = scvc.scv_id "
				. " WHERE route.rut_id= $rutId AND scvc.scv_id = $cabType AND rate.rte_status=1";

		//echo $sql; exit();
		$record = DBUtil::queryRow($sql);
		return $record;
	}

	function getLastModified($format = 'YmdHis')
	{
		$sql	 = "SELECT max(rut_modified_on) from route";
		$val	 = DBUtil::command($sql)->queryScalar();
		$date	 = new DateTime($val);
		//$date->sub(new DateInterval('PT5H30M'));
		return $date->format($format);
	}

	public function getAllRouteUrls()
	{
		$sql		 = "SELECT distinct(rut_name),rut_estm_distance FROM route WHERE rut_estm_distance <= 500 AND rut_active = 1";
		$recordset	 = DBUtil::queryAll($sql);
		return $recordset;
	}

	public function getUrls()
	{
		$sql		 = "SELECT distinct CONCAT(c1.cty_name, '-', c2.cty_name) as routeName, c1.cty_lat as lat1, c1.cty_long as long1, c2.cty_lat as lat2, c2.cty_long as long2, c1.cty_id as fromCityId, c1.cty_name as fromCityName, c2.cty_id as toCityId, c2.cty_name as toCityName, c1.cty_state_id as fromState,
        c2.cty_state_id as tostate_id, rut_estm_distance as distance
        FROM cities c1
        INNER JOIN cities c2 ON c1.cty_id <> c2.cty_id AND c2.cty_active = 1 AND c2.cty_service_active = 1
        INNER JOIN route ON route.rut_from_city_id = c1.cty_id AND route.rut_to_city_id = c2.cty_id AND rut_active=1
        LEFT JOIN ignore_cty ON  ignore_cty.from_cty_id = c1.cty_id AND ignore_cty.to_cty_id = c2.cty_id
        WHERE c1.cty_active = 1 AND c1.cty_service_active =1 AND route.rut_id IS NULL AND ignore_cty.from_cty_id IS NULL AND distance < 200";
		$recordset	 = DBUtil::queryAll($sql);
		return $recordset;
	}

	function getRoundtripDurationArrbyCities($fromCityId, $toCityId)
	{
		$sql	 = "SELECT r1.rut_from_city_id, r1.rut_to_city_id, r1.rut_estm_time as rut1, r2.rut_estm_time as rut2
                FROM route r1
                JOIN route r2 ON r1.rut_from_city_id = r2.rut_to_city_id AND r1.rut_to_city_id = r2.rut_from_city_id
                WHERE  r1.rut_from_city_id = $fromCityId AND r1.rut_to_city_id = $toCityId";
		$result	 = DBUtil::queryRow($sql);
		if (!$result)
		{
			$sql1	 = "SELECT cty_lat,cty_long from cities where cty_id IN ($fromCityId, $toCityId)";
			$result1 = DBUtil::command($sql1)->queryAll();
			if (sizeof($result1) == 2)
			{
				/* @var $fromPlaceObj \Stub\common\Place */
				$fromPlaceObj	 = \Stub\common\Place::initGoogleRoute($result1[0]['cty_lat'], $result1[0]['cty_long']);
				/* @var $toPlaceObj \Stub\common\Place */
				$toPlaceObj		 = \Stub\common\Place::initGoogleRoute($result1[1]['cty_lat'], $result1[1]['cty_long']);
				$ar				 = Booking::model()->getDistance($fromPlaceObj, $toPlaceObj);
				/* @var $fromPlaceObj \Stub\common\Place */
				$fromPlaceObj	 = \Stub\common\Place::initGoogleRoute($result1[1]['cty_lat'], $result1[1]['cty_long']);
				/* @var $toPlaceObj \Stub\common\Place */
				$toPlaceObj		 = \Stub\common\Place::initGoogleRoute($result1[0]['cty_lat'], $result1[0]['cty_long']);
				$ar1			 = Booking::model()->getDistance($fromPlaceObj, $toPlaceObj);
				$res			 = [
					'rut_from_city_id'	 => $fromCityId,
					'rut_to_city_id'	 => $toCityId,
					'rut1'				 => $ar['totmin'],
					'rut2'				 => $ar1['totmin']
				];
				return $res;
			}
		}
		return $result;
	}

	function getRoundtripEstimatedMinimumDurationbyCities($fromCityId, $toCityId, $gap = 30)
	{
		$result	 = $this->getRoundtripDurationArrbyCities($fromCityId, $toCityId);
		$totTime = $result['rut1'] + $result['rut2'] + $gap;
		$estTime = $totTime + (10 - ($totTime % 10));
		return $estTime;
	}

	function getRoundtripEstimatedPickupbyReturnDateTime($fromCityId, $toCityId, $returnDateTime)
	{
		if ($fromCityId != '' && $toCityId != '' && $returnDateTime != '')
		{
			$result = $this->getRoundtripDurationArrbyCities($fromCityId, $toCityId);

			if ($result)
			{
				$returnDuration			 = $result['rut2'];
				$estimatedPickupDateTime = date('Y-m-d H:i:s', strtotime("-$returnDuration minutes", strtotime($returnDateTime)));
				return $estimatedPickupDateTime;
			}
		}
		return '';
	}

	function populateRoutes()
	{
		$arrApi = ["AIzaSyABe538hu95CiyXSn53nCKrAmJxaya0xPk",
			"AIzaSyCWSa0tPA_06ww-J0lsjqDqsABct_pnEH8",
			"AIzaSyCyNYbd-3vlKal97sk8bDK9PHZeLcst7R0",
			"AIzaSyBZ3bieYV6kWLah1dii4y8-iuAHRrXXlIM",
			"AIzaSyAmMlwEXWyU-9ukm43Vq9wNcQxdbQs5dek",
			"AIzaSyDj3a6CwprvOQZv2cdyKHVLYSSelXb534o",
			"AIzaSyCnOETh-r60drz_bVknrFHx0jhMOpHf0gs",
			"AIzaSyA4AIw58ic0kgHPuMaHpiuPYalBzfAXmek",
			"AIzaSyAZ08x3wNMZvRerr0BbiO0fc_QtS_62b7U",
			"AIzaSyDS65ZvdBbxPbDbNb-5XQUxTcB9yYsiMfE",
			//"AIzaSyCZ9nnzZTIdapekYTaF6wpcHVsf_VyaQao",
			"AIzaSyCGVVr8PS0FHbYkbbwUbbShmqpPMJ7IMWE",
			"AIzaSyBSZYBDdO1UiQF6Z0rktRDVv92-AwCVZq0"];

		$rutUrls = Route::model()->getUrls();
		$i		 = 0;
		foreach ($rutUrls as $key => $val)
		{
			$j									 = $i % 11;
			Yii::app()->params['googleApiKey']	 = 'AIzaSyAmMlwEXWyU-9ukm43Vq9wNcQxdbQs5dek'; //$arrApi[$j]; //'AIzaSyDS65ZvdBbxPbDbNb-5XQUxTcB9yYsiMfE';
			$lat1								 = $val['lat1'];
			$long1								 = $val['long1'];
			$lat2								 = $val['lat2'];
			$long2								 = $val['long2'];
			$fromCty							 = $val['fromCityId'];
			$toCty								 = $val['toCityId'];
			$rutName							 = Route::model()->generateAlias($val['fromCityId'], $val['toCityId']);
			$GLOBALS['ctr']						 = 0;
			$distance							 = Quotation::model()->calculateLatLongDistance($lat1, $long1, $lat2, $long2);

			if ($distance['distance'] != '' && $distance['time'] != 0)
			{
				echo $sql = "INSERT INTO route (rut_from_city_id, rut_to_city_id, rut_estm_distance, rut_estm_time, rut_name)
                    VALUES ('" . $val['fromCityId'] . "', '" . $val['toCityId'] . "', '" . $distance['distance'] . "', '" . $distance['time'] . "', '" . $rutName . "')";
				$row = DBUtil::command($sql)->execute();
				echo "\r\n ID::$i::" . Yii::app()->db->lastInsertID . "\r\n";
				echo "\r\n APIKEYID" . Config::getGoogleApiKey('apikey') . "\r\n";
				print_r($distance);
				echo "----------------------------\r\n\r\n";
			}
			else
			{
				print_r($distance);
				echo "\r\n APIKEYID" . Config::getGoogleApiKey('apikey') . "\r\n";
				echo "failed overlimit";
				echo "----------------------------\r\n\r\n";
			}
			if ($distance['errorMessage'] == 'ZERO_RESULTS')
			{
				echo $sql = "INSERT INTO ignore_cty (from_cty_id, to_cty_id) VALUES ('" . $val['fromCityId'] . "', '" . $val['toCityId'] . "')";
				$row = DBUtil::command($sql)->execute();
			}
			$i++;
		}
	}

	public function getExistingUrls()
	{
		$sql		 = "SELECT rut.rut_id,rut.rut_from_city_id as fromCityId, rut.rut_to_city_id toCityId,c1.cty_lat as c1_lat, c1.cty_long as c1_long, c2.cty_lat as c2_lat, c2.cty_long as c2_long FROM route rut
                INNER JOIN cities c1 ON c1.cty_id=rut.rut_from_city_id
                INNER JOIN cities c2 ON c2.cty_id=rut.rut_to_city_id
                WHERE rut.rut_create_date < '2017-04-15 00:00:00'
                ORDER BY `rut_id` ASC ";
		$recordset	 = DBUtil::queryAll($sql);
		return $recordset;
	}

	public function populateExisting()
	{
		$rutUrls = Route::model()->getExistingUrls();
		foreach ($rutUrls as $key => $val)
		{
			$lat1								 = $val['c1_lat'];
			$long1								 = $val['c1_long'];
			$lat2								 = $val['c2_lat'];
			$long2								 = $val['c2_long'];
			Yii::app()->params['googleApiKey']	 = 'AIzaSyBSZYBDdO1UiQF6Z0rktRDVv92-AwCVZq0'; //$arrApi[$j];
			$rutName							 = Route::model()->generateAlias($val['rut_from_city_id'], $val['rut_to_city_id']);
			$distance							 = Quotation::model()->calculateLatLongDistance($lat1, $long1, $lat2, $long2);

			if ($distance['distance'] != '' && $distance['time'] != 0)
			{
				$sql = "UPDATE route SET rut_estm_distance = '" . $distance['distance'] . "', rut_estm_time = '" . $distance['time'] . "' WHERE rut_id = '" . $val['rut_id'] . "' ";
				$row = DBUtil::command($sql)->execute();
			}
			else
			{
				print_r($distance);
				echo "\r\n APIKEYID" . Config::getGoogleApiKey('apikey') . "\r\n";
				echo "failed overlimit";
				echo "----------------------------\r\n\r\n";
			}
			if ($distance['errorMessage'] == 'ZERO_RESULTS')
			{
				echo $sql = "INSERT INTO ignore_cty (from_cty_id, to_cty_id) VALUES ('" . $val['fromCityId'] . "', '" . $val['toCityId'] . "')";
				$row = DBUtil::command($sql)->execute();
			}
		}
	}

	public function popularRoute($rutName)
	{
		if ($rutName != "")
		{
			$route = self::model()->getByName($rutName);
			if ($route)
			{
				return $this->topRoute($route->rut_from_city_id, $route->rut_to_city_id);
			}
			$city = Cities::model()->getByCity($rutName);
			if ($city)
			{
				return $this->topRoute($city->cty_id, 0);
			}
		}
		$sqlQueryNorth	 = "SELECT
                                IF(c1.cty_alias_name <> '',c1.cty_alias_name,c1.cty_name) as from_city, c1.cty_id as frmCtyId,
                                IF(c2.cty_alias_name <> '',c2.cty_alias_name,c2.cty_name) as to_city, c2.cty_id as toCtyId,
                                CONCAT(c1.cty_name,'-', c2.cty_name) as rut, route.rut_name as rutname,
                                total_booking_count,
                                s1.stt_zone
                                FROM
                                (
                                   SELECT DISTINCT bkg_from_city_id, bkg_to_city_id, COUNT(1) as total_booking_count
                                   FROM `booking` WHERE bkg_status IN (2,3,5,6,7)
                                   AND bkg_active=1
                                   AND DATE(bkg_create_date) > '2015-10-01' AND bkg_pickup_date BETWEEN DATE_SUB(NOW(),INTERVAL 6 MONTH) AND NOW()
                                   GROUP BY bkg_from_city_id,bkg_to_city_id
                                ) b1
                                INNER JOIN `route` on b1.bkg_from_city_id = route.rut_from_city_id AND b1.bkg_to_city_id = route.rut_to_city_id
                                INNER JOIN `cities` c1 on `rut_from_city_id`= c1.cty_id AND c1.cty_active = 1 AND c1.cty_is_airport=0
                                INNER JOIN `cities` c2 on `rut_to_city_id`= c2.cty_id AND c2.cty_active = 1 AND c2.cty_is_airport=0
                                INNER JOIN `states` s1 ON s1.stt_id=c1.cty_state_id
                                WHERE rut_active = 1 AND c1.cty_id <> c2.cty_id AND s1.stt_zone=1
                                GROUP BY rut_id
                                ORDER BY total_booking_count DESC
                                LIMIT 0,5";
		$recordNorthSet	 = DBUtil::command($sqlQueryNorth)->queryAll();

		$sqlQueryWest	 = "SELECT
                                IF(c1.cty_alias_name <> '',c1.cty_alias_name,c1.cty_name) as from_city, c1.cty_id as frmCtyId,
                                IF(c2.cty_alias_name <> '',c2.cty_alias_name,c2.cty_name) as to_city, c2.cty_id as toCtyId,
                                CONCAT(c1.cty_name,'-', c2.cty_name) as rut, route.rut_name as rutname,
                                total_booking_count,
                                s1.stt_zone
                                FROM
                                (
                                   SELECT DISTINCT bkg_from_city_id, bkg_to_city_id, COUNT(1) as total_booking_count
                                   FROM `booking` WHERE bkg_status IN (2,3,5,6,7)
                                   AND bkg_active=1
                                   AND DATE(bkg_create_date) > '2015-10-01' AND bkg_pickup_date BETWEEN DATE_SUB(NOW(),INTERVAL 6 MONTH) AND NOW()
                                   GROUP BY bkg_from_city_id,bkg_to_city_id
                                ) b1
                                INNER JOIN `route` on b1.bkg_from_city_id = route.rut_from_city_id AND b1.bkg_to_city_id = route.rut_to_city_id
                                INNER JOIN `cities` c1 on `rut_from_city_id`= c1.cty_id AND c1.cty_active = 1 AND c1.cty_is_airport=0
                                INNER JOIN `cities` c2 on `rut_to_city_id`= c2.cty_id AND c2.cty_active = 1 AND c2.cty_is_airport=0
                                INNER JOIN `states` s1 ON s1.stt_id=c1.cty_state_id
                                WHERE rut_active = 1 AND c1.cty_id <> c2.cty_id AND s1.stt_zone=2
                                GROUP BY rut_id
                                ORDER BY total_booking_count DESC
                                LIMIT 0,5";
		$recordWestSet	 = DBUtil::command($sqlQueryWest)->queryAll();

		$sqlQueryCentral	 = "SELECT
                                IF(c1.cty_alias_name <> '',c1.cty_alias_name,c1.cty_name) as from_city, c1.cty_id as frmCtyId,
                                IF(c2.cty_alias_name <> '',c2.cty_alias_name,c2.cty_name) as to_city, c2.cty_id as toCtyId,
                                CONCAT(c1.cty_name,'-', c2.cty_name) as rut, route.rut_name as rutname,
                                total_booking_count,
                                s1.stt_zone
                                FROM
                                (
                                   SELECT DISTINCT bkg_from_city_id, bkg_to_city_id, COUNT(1) as total_booking_count
                                   FROM `booking` WHERE bkg_status IN (2,3,5,6,7)
                                   AND bkg_active=1
                                   AND DATE(bkg_create_date) > '2015-10-01' AND bkg_pickup_date BETWEEN DATE_SUB(NOW(),INTERVAL 6 MONTH) AND NOW()
                                   GROUP BY bkg_from_city_id,bkg_to_city_id
                                ) b1
                                INNER JOIN `route` on b1.bkg_from_city_id = route.rut_from_city_id AND b1.bkg_to_city_id = route.rut_to_city_id
                                INNER JOIN `cities` c1 on `rut_from_city_id`= c1.cty_id AND c1.cty_active = 1 AND c1.cty_is_airport=0
                                INNER JOIN `cities` c2 on `rut_to_city_id`= c2.cty_id AND c2.cty_active = 1 AND c2.cty_is_airport=0
                                INNER JOIN `states` s1 ON s1.stt_id=c1.cty_state_id
                                WHERE rut_active = 1 AND c1.cty_id <> c2.cty_id AND s1.stt_zone=3
                                GROUP BY rut_id
                                ORDER BY total_booking_count DESC
                                LIMIT 0,5";
		$recordCentralSet	 = DBUtil::command($sqlQueryCentral)->queryAll();

		$sqlQueryEast	 = "SELECT
                                IF(c1.cty_alias_name <> '',c1.cty_alias_name,c1.cty_name) as from_city, c1.cty_id as frmCtyId,
                                IF(c2.cty_alias_name <> '',c2.cty_alias_name,c2.cty_name) as to_city, c2.cty_id as toCtyId,
                                CONCAT(c1.cty_name,'-', c2.cty_name) as rut, route.rut_name as rutname,
                                total_booking_count,
                                s1.stt_zone
                                FROM
                                (
                                   SELECT DISTINCT bkg_from_city_id, bkg_to_city_id, COUNT(1) as total_booking_count
                                   FROM `booking` WHERE bkg_status IN (2,3,5,6,7)
                                   AND bkg_active=1
                                   AND DATE(bkg_create_date) > '2015-10-01' AND bkg_pickup_date BETWEEN DATE_SUB(NOW(),INTERVAL 6 MONTH) AND NOW()
                                   GROUP BY bkg_from_city_id,bkg_to_city_id
                                ) b1
                                INNER JOIN `route` on b1.bkg_from_city_id = route.rut_from_city_id AND b1.bkg_to_city_id = route.rut_to_city_id
                                INNER JOIN `cities` c1 on `rut_from_city_id`= c1.cty_id AND c1.cty_active = 1 AND c1.cty_is_airport=0
                                INNER JOIN `cities` c2 on `rut_to_city_id`= c2.cty_id AND c2.cty_active = 1 AND c2.cty_is_airport=0
                                INNER JOIN `states` s1 ON s1.stt_id=c1.cty_state_id
                                WHERE rut_active = 1 AND c1.cty_id <> c2.cty_id AND s1.stt_zone IN (5,6)
                                GROUP BY rut_id
                                ORDER BY total_booking_count DESC
                                LIMIT 0,5";
		$recordEastSet	 = DBUtil::command($sqlQueryEast)->queryAll();

		$sqlQuerySouth	 = "SELECT
                                IF(c1.cty_alias_name <> '',c1.cty_alias_name,c1.cty_name) as from_city, c1.cty_id as frmCtyId,
                                IF(c2.cty_alias_name <> '',c2.cty_alias_name,c2.cty_name) as to_city, c2.cty_id as toCtyId,
                                CONCAT(c1.cty_name,'-', c2.cty_name) as rut, route.rut_name as rutname,
                                total_booking_count,
                                s1.stt_zone
                                FROM
                                (
                                   SELECT DISTINCT bkg_from_city_id, bkg_to_city_id, COUNT(1) as total_booking_count
                                   FROM `booking` WHERE bkg_status IN (2,3,5,6,7)
                                   AND bkg_active=1
                                   AND DATE(bkg_create_date) > '2015-10-01' AND bkg_pickup_date BETWEEN DATE_SUB(NOW(),INTERVAL 6 MONTH) AND NOW()
                                   GROUP BY bkg_from_city_id,bkg_to_city_id
                                ) b1
                                INNER JOIN `route` on b1.bkg_from_city_id = route.rut_from_city_id AND b1.bkg_to_city_id = route.rut_to_city_id
                                INNER JOIN `cities` c1 on `rut_from_city_id`= c1.cty_id AND c1.cty_active = 1 AND c1.cty_is_airport=0
                                INNER JOIN `cities` c2 on `rut_to_city_id`= c2.cty_id AND c2.cty_active = 1 AND c2.cty_is_airport=0
                                INNER JOIN `states` s1 ON s1.stt_id=c1.cty_state_id
                                WHERE rut_active = 1 AND c1.cty_id <> c2.cty_id AND s1.stt_zone IN (4,7)
                                GROUP BY rut_id
                                ORDER BY total_booking_count DESC
                                LIMIT 0,5";
		$recordSouthSet	 = DBUtil::command($sqlQuerySouth)->queryAll();
		return array_merge($recordNorthSet, $recordWestSet, $recordCentralSet, $recordEastSet, $recordSouthSet);
	}

	public function topRoute($fromCty, $toCty = 0)
	{

		if ($toCty == 0)
		{
			$sql = "(
                        SELECT route.rut_id,route.rut_name as rutname,IF(c1.cty_alias_name <> '',c1.cty_alias_name,c1.cty_name) as from_city,
                        IF(c2.cty_alias_name <> '',c2.cty_alias_name,c2.cty_name) as to_city, c1.cty_id as frmCtyId,
                        c2.cty_id as toCtyId, CONCAT(c1.cty_name,'-', c2.cty_name) as rut,route.rut_name,route.rut_estm_distance, route.rut_estm_time
                        FROM `route`
                        INNER JOIN `cities` c1 ON c1.cty_id=route.rut_from_city_id AND c1.cty_is_airport=0
                        LEFT JOIN `cities` c2 ON c2.cty_id=route.rut_to_city_id AND c2.cty_is_airport=0
                        WHERE route.rut_from_city_id=$fromCty AND (route.rut_estm_distance BETWEEN 40 AND 300)
                        ORDER BY `route`.`rut_estm_distance`  DESC LIMIT 0,10
                    )
                    UNION
                    (
                        SELECT route.rut_id,route.rut_name as rutname,IF(c1.cty_alias_name <> '',c1.cty_alias_name,c1.cty_name) as from_city,
                        IF(c2.cty_alias_name <> '',c2.cty_alias_name,c2.cty_name) as to_city, c1.cty_id as frmCtyId,
                        c2.cty_id as toCtyId,CONCAT(c1.cty_name,'-', c2.cty_name) as rut,route.rut_name,route.rut_estm_distance, route.rut_estm_time
                        FROM `route`
                        INNER JOIN `cities` c1 ON c1.cty_id=route.rut_from_city_id AND c1.cty_is_airport=0
                        LEFT JOIN `cities` c2 ON c2.cty_id=route.rut_to_city_id AND c2.cty_is_airport=0
                        WHERE route.rut_from_city_id=$fromCty AND (route.rut_estm_distance BETWEEN 40 AND 300)
                        ORDER BY `route`.`rut_estm_distance`  ASC LIMIT 0,10
                    )";
		}
		else
		{
			$sql = "SELECT * FROM (
                        SELECT IF(c1.cty_alias_name <> '',c1.cty_alias_name,c1.cty_name) as from_city, c1.cty_id as frmCtyId, IF(c2.cty_alias_name <> '',c2.cty_alias_name,c2.cty_name) as to_city, c2.cty_id as toCtyId, CONCAT(c1.cty_name,'-', c2.cty_name) as rut, route.rut_name as rutname, COUNT(DISTINCT b1.bkg_id) as total_booking_count,
                        SUM(IF(b1.bkg_status IN (2,3,5,6,7),1,0)) as active_bookings, SUM(IF(b1.bkg_status IN (9),1,0)) as cancelled_bookings
                        FROM route
                        join cities c1 on `rut_from_city_id`= c1.cty_id
                        join cities c2 on `rut_to_city_id`= c2.cty_id
                        join booking b1 on b1.bkg_from_city_id = rut_from_city_id AND b1.bkg_to_city_id = rut_to_city_id AND bkg_status IN (2,3,5,6,7,9)
                        AND bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 3 MONTH) AND NOW()
                        where rut_active = 1 AND c1.cty_id <> c2.cty_id AND route.rut_from_city_id = $fromCty AND route.rut_to_city_id <> '$toCty'
                        GROUP BY rut_id ORDER BY total_booking_count DESC LIMIT 0,10
                    ) A
                    UNION (
                        SELECT * FROM (
                            SELECT IF(c1.cty_alias_name <> '',c1.cty_alias_name,c1.cty_name) as from_city,c1.cty_id as frmCtyId, IF(c2.cty_alias_name <> '',c2.cty_alias_name,c2.cty_name) as to_city, c2.cty_id as toCtyId, CONCAT(c1.cty_name,'-', c2.cty_name) as rut,route.rut_name as rutname, COUNT(DISTINCT b1.bkg_id) as total_booking_count,
                            SUM(IF(b1.bkg_status IN (2,3,5,6,7),1,0)) as active_bookings, SUM(IF(b1.bkg_status IN (9),1,0)) as cancelled_bookings
                            FROM route
                            join cities c1 on `rut_from_city_id`= c1.cty_id
                            join cities c2 on `rut_to_city_id`= c2.cty_id
                            join booking b1 on b1.bkg_from_city_id = rut_from_city_id AND b1.bkg_to_city_id = rut_to_city_id AND bkg_status IN (2,3,5,6,7,9)
                            AND bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 3 MONTH) AND NOW()
                            where rut_active = 1 AND c1.cty_id <> c2.cty_id AND route.rut_from_city_id = $toCty
                            GROUP BY rut_id ORDER BY total_booking_count DESC LIMIT 0,10
                        )B
                    ) ORDER BY total_booking_count DESC";
		}
		$recordset = DBUtil::queryAll($sql);
		return $recordset;
	}

	public static function getBasicDailyRentalQuote($fromCity, $tripType, $cabList = null)
	{
		$dailyRentalBkgArr	 = array(9, 10, 11);
		$cabIds				 = "";
		if ($cabList != null)
		{
			$cabIds = implode(',', $cabList);
		}
		$key = "getBasicDailyRentalQuote::{$fromCity}-{$toCity}-{$tripType}-{$cabIds}";

		$resQuote = Yii::app()->cache->get($key);
		if ($resQuote !== false)
		{
			goto result;
		}

		$routeModel						 = new BookingRoute();
		$routeModel->brt_from_city_id	 = $fromCity;
		$routeModel->brt_to_city_id		 = $fromCity;
		$dateTime						 = new DateTime();
		$dateTime->add(new DateInterval('P1D'));
		$dateTime->setTime('7', '0', '0');
		$pickupTime						 = $dateTime->format('Y-m-d H:i:s');
		$routeModel->brt_pickup_datetime = $pickupTime;
		$routesArr[]					 = $routeModel;
		$partnerId						 = Yii::app()->params['gozoChannelPartnerId'];
		$cabTypes						 = SvcClassVhcCat::getBasicCabList($fromCity, $fromCity);
		$quote							 = new Quote();
		$quote->routes					 = $routesArr;
		$quote->tripType				 = $tripType;
		$quote->suggestedPrice			 = 1;
		$quote->partnerId				 = $partnerId;
		$quote->quoteDate				 = date("Y-m-d H:i:s", strtotime("+1 day"));
		$quote->pickupDate				 = $pickupTime;
		$resQuote						 = $quote->getQuote($cabTypes, false, false, false, false);
		Yii::app()->cache->set($key, $resQuote, 7 * 24 * 60 * 60, new CacheDependency('BasicDRQuote'));

		result:
		return $resQuote;
	}

	public static function getBasicOnewayQuote($fromCity, $toCity, $cabList = null)
	{
		$cabIds = "";
		if ($cabList != null)
		{
			$cabIds = implode(',', $cabList);
		}
		$key		 = "getBasicOnewayQuote::{$fromCity}-{$toCity}-{$cabIds}";
		$resQuote	 = Yii::app()->cache->get($key);

		if ($resQuote !== false)
		{
			goto result;
		}

		$routeModel						 = new BookingRoute();
		$routeModel->brt_from_city_id	 = $fromCity;
		$routeModel->brt_to_city_id		 = $toCity;
		$dateTime						 = new DateTime();
		$dateTime->add(new DateInterval('P1D'));
		$dateTime->setTime('7', '0', '0');
		$pickupTime						 = $dateTime->format('Y-m-d H:i:s');
		$routeModel->brt_pickup_datetime = $pickupTime;
		$routesArr[]					 = $routeModel;
		$partnerId						 = Yii::app()->params['gozoChannelPartnerId'];
		$cabTypes						 = SvcClassVhcCat::getBasicCabList($fromCity, $toCity);
		$quote							 = new Quote();
		$quote->routes					 = $routesArr;
		$quote->tripType				 = 1;
		$quote->suggestedPrice			 = 1;
		$quote->partnerId				 = $partnerId;
		$quote->quoteDate				 = date("Y-m-d H:i:s", strtotime("+1 day"));
		$quote->pickupDate				 = $pickupTime;
		$resQuote						 = $quote->getQuote($cabTypes, false, false, false, false);
		Yii::app()->cache->set($key, $resQuote, 30 * 24 * 60 * 60, new CacheDependency('BasicRouteQuote'));

		result:
		return $resQuote;
	}

	public function getRoutePrices($pickupTime = null, $priceSurge = false)
	{

		$resQuote	 = Route::getBasicOnewayQuote($this->rut_from_city_id, $this->rut_to_city_id);
		$arr		 = [];
		$cabList	 = VehicleTypes::model()->getCarType();
		foreach ($resQuote as $key => $value)
		{
			$arr[$key] = ['cab' => $cabList[$key], 'base_amt' => $value->routeRates->baseAmount];
		}

		return $arr;
	}

	public function getExcludedCabTypes($fromCtyId, $toCtyId)
	{
		$model			 = $this->getRutidbyCities($fromCtyId, $toCtyId, 1);
		$cabTypeArray	 = [];
		if ($model->rut_excluded_cabtypes != '')
		{
			$cabTypeArray = explode(',', $model->rut_excluded_cabtypes);
		}
		return $cabTypeArray;
	}

	public function getTopRouteByCity($cty_id)
	{
		$compactSvcId = VehicleCategory::COMPACT_ECONOMIC;

		$sql	 = "SELECT c1.cty_name as from_city,route.rut_id,c2.cty_name as to_city, COUNT(DISTINCT b1.bkg_id) as booking_count, rut_name, r1.rte_amount as 'COMPACT'
                    FROM route
                        INNER join rate r1 on r1.`rte_route_id`= rut_id AND r1.rte_vehicletype_id= $compactSvcId AND r1.rte_status = 1
                        join cities c1 on `rut_from_city_id`= c1.cty_id
                        join cities c2 on `rut_to_city_id`= c2.cty_id
                        join booking b1 on b1.bkg_from_city_id = rut_from_city_id AND b1.bkg_to_city_id = rut_to_city_id AND bkg_status IN (2,3,5,6,7,9) AND bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 6 MONTH) AND NOW()
                    where rut_active = 1 and rut_from_city_id = $cty_id
                    GROUP BY rut_id ORDER BY booking_count DESC LIMIT 0,5";
		$data	 = DBUtil::queryAll($sql);
		return $data;
	}

	public function getPriceByRoute($rut_id)
	{
		$compactSvcId	 = VehicleCategory::COMPACT_ECONOMIC;
		$gst			 = Yii::app()->params['gst'];
		$sql			 = "SELECT route.rut_id,route.rut_name,rate.rte_excl_amount,round(rate.rte_amount/(1+($gst/100))) as rteAmount
                    FROM route
                        join cities c1 on `rut_from_city_id`= c1.cty_id
                        join cities c2 on `rut_to_city_id`= c2.cty_id
                        LEFT JOIN rate ON rate.rte_route_id=route.rut_id AND rate.rte_vehicletype_id= $compactSvcId
                    WHERE route.rut_id = $rut_id";
		$data			 = DBUtil::queryRow($sql);
		return $data;
	}

	public static function getTopRoutesByCity($cityId)
	{
		$sql = "SELECT GROUP_CONCAT(rut_id) FROM (SELECT rut_id, COUNT(DISTINCT bkg_id) AS booking_count
				FROM `route`
				INNER JOIN `cities` c2 ON c2.cty_id=route.rut_to_city_id
				INNER JOIN booking b1 on b1.bkg_from_city_id = rut_from_city_id AND b1.bkg_to_city_id = rut_to_city_id AND bkg_status IN (6,7) 
						AND b1.bkg_create_date > DATE_SUB(CURDATE(), INTERVAL 730 DAY)
				WHERE route.rut_active=1
					AND route.rut_from_city_id=:city AND route.rut_from_city_id<>route.rut_to_city_id
					AND c2.cty_is_airport=0 AND rut_id IN (SELECT DISTINCT rte_route_id FROM rate WHERE rate.rte_vehicletype_id IN (3,73) AND rte_status=1)
				GROUP BY route.rut_id ORDER BY booking_count DESC
				LIMIT 0,10) a;";

		$result = DBUtil::queryScalar($sql, DBUtil::SDB(), ["city" => $cityId], 60 * 60 * 24 * 60, new CacheDependency("topRoutes"));
	}
	
	public static function getTopRoutesByRegion($limit = 5, $regionIds = '1,2,3,4,5,6,7')
	{
		#Logger::beginProfile('getTopRoutesByRegion');
		$sql = "SELECT * FROM (
					SELECT fromCity.cty_id fromCityId, toCity.cty_id toCityId, fromCity.cty_name fromCityName, 
						toCity.cty_name toCityName, COUNT(DISTINCT bkg.bkg_id) as cnt, fromCity.cty_alias_path aliaspath, 
						stt.stt_zone, ROW_NUMBER() OVER (PARTITION BY stt.stt_zone ORDER BY cnt DESC) AS rank, fromCity.cty_state_id stateid,
						stt.stt_zone zoneid
					FROM booking bkg 
					INNER JOIN cities fromCity ON bkg.bkg_from_city_id=fromCity.cty_id AND fromCity.cty_is_airport=0 
						AND fromCity.cty_active = 1 AND fromCity.cty_service_active = 1 
					INNER JOIN cities toCity ON bkg.bkg_to_city_id=toCity.cty_id AND toCity.cty_is_airport=0 
						AND toCity.cty_active = 1 AND toCity.cty_service_active = 1 
					INNER JOIN states stt ON stt.stt_active = '1' AND stt.stt_id = fromCity.cty_state_id AND stt.stt_zone IN ($regionIds) 
					WHERE bkg.bkg_status IN (6,7) AND bkg.bkg_active=1 AND bkg.bkg_booking_type = 1 
						AND bkg.bkg_create_date > DATE_SUB(NOW(), INTERVAL 365 DAY) 
						AND bkg.bkg_from_city_id <> bkg.bkg_to_city_id
					GROUP BY bkg.bkg_from_city_id, bkg.bkg_to_city_id 
				) a WHERE rank <= {$limit}";

		#$data = DBUtil::query($sql, DBUtil::SDB());
		$data = DBUtil::query($sql, DBUtil::SDB(), [], (60 * 60 * 24 * 30), new CacheDependency("Routes"));

		#Logger::endProfile('getTopRoutesByRegion');
		#echo "edTime = ".Logger::getProfileExecutionTime('getTopRoutesByRegion');
		
		return $data;
	}

	public function getRoutesByCityId($city_id)
	{
		$sql	 = "SELECT * FROM (
                    (
                            SELECT route.rut_id,rut_from_city_id,rut_to_city_id,route.rut_name , 
	                            COUNT(DISTINCT b1.bkg_id) as booking_count, c1.cty_name as from_city, c2.cty_name as to_city,
								c1.cty_alias_path as from_city_alias_path, c2.cty_alias_path as to_city_alias_path, 
								c1.cty_has_airport as airport_flag, route.rut_estm_distance, route.rut_estm_time
                            FROM `route`
							INNER JOIN `cities` c1 ON c1.cty_id=route.rut_from_city_id
                            INNER JOIN `cities` c2 ON c2.cty_id=route.rut_to_city_id
                            INNER JOIN booking b1 on b1.bkg_from_city_id = rut_from_city_id AND b1.bkg_to_city_id = rut_to_city_id AND bkg_status IN (6,7) 
									AND b1.bkg_create_date > DATE_SUB(CURDATE(), INTERVAL 730 DAY)
                            WHERE route.rut_active=1 
								AND route.rut_from_city_id = $city_id AND route.rut_from_city_id<>route.rut_to_city_id
								AND rut_id IN (SELECT DISTINCT rte_route_id FROM rate WHERE rate.rte_vehicletype_id IN (3,73) AND rte_status=1)
								AND c2.cty_is_airport=0
                            GROUP BY route.rut_id
                            ORDER BY booking_count DESC LIMIT 0,10
                        ) 
                         UNION
                         (
								SELECT route.rut_id,rut_from_city_id,rut_to_city_id,route.rut_name , 
									'0' as booking_count, c1.cty_name as from_city, c2.cty_name as to_city, 
									c1.cty_alias_path as from_city_alias_path, c2.cty_alias_path as to_city_alias_path, 
									c1.cty_has_airport as airport_flag , route.rut_estm_distance, route.rut_estm_time 
								FROM `route`
								INNER JOIN `rate` r1 on r1.rte_route_id=route.rut_id AND r1.rte_status=1 AND r1.rte_vehicletype_id IN (1,3,72,73)
								INNER JOIN `cities` c1 ON c1.cty_id=route.rut_from_city_id AND c1.cty_is_airport=0
								INNER JOIN `cities` c2 ON c2.cty_id=route.rut_to_city_id AND c2.cty_is_airport=0
								WHERE route.rut_active=1
								AND route.rut_from_city_id<>route.rut_to_city_id
								AND route.rut_id IN
								(
								   SELECT route.rut_id FROM `route`
								   INNER JOIN `cities` c2 ON c2.cty_id=route.rut_to_city_id AND c2.cty_is_airport=0
								   WHERE route.rut_active=1 AND route.rut_from_city_id<>route.rut_to_city_id
								   AND route.rut_from_city_id=$city_id AND route.rut_estm_distance BETWEEN 50 AND 400
								   GROUP BY route.rut_id
								)
								GROUP BY route.rut_id
								ORDER BY route.rut_estm_distance
                         )) a
                         LIMIT 0,10";
		$result	 = DBUtil::queryAll($sql, DBUtil::SDB(), [], true, 60 * 60 * 24 * 60, 'topRouteQuote');
		result:
		return $result;
	}

	public static function getRoutesByAirportid($city_id)
	{
		$key	 = "routesByAirport_" . $city_id;
		$result	 = Yii::app()->cache->get($key);
		if ($result)
		{
			goto result;
		}
		$sql	 = "SELECT * FROM
                    (
                            SELECT route.rut_id,rut_from_city_id,rut_to_city_id,route.rut_name , r1.rte_amount as compact_amount,
                            r2.rte_amount as suv_amount, r3.rte_amount as seadan_amount, 
                            COUNT(DISTINCT b1.bkg_id) as booking_count, c1.cty_name as from_city, c2.cty_name as to_city,
							c1.cty_alias_path as from_city_alias_path, c2.cty_alias_path as to_city_alias_path, 
                            c1.cty_has_airport as airport_flag, route.rut_estm_distance
                            FROM `route`
                            LEFT JOIN `rate` r1 on r1.rte_route_id=route.rut_id AND r1.rte_status=1 AND r1.rte_vehicletype_id=" . VehicleCategory::COMPACT_ECONOMIC . "
                            LEFT JOIN `rate` r2 on r2.rte_route_id=route.rut_id AND r2.rte_status=1 AND r2.rte_vehicletype_id=" . VehicleCategory::SUV_ECONOMIC . "
                            LEFT JOIN `rate` r3 on r3.rte_route_id=route.rut_id AND r3.rte_status=1 AND r3.rte_vehicletype_id=" . VehicleCategory::SEDAN_ECONOMIC . "
                            LEFT JOIN `cities` c1 ON c1.cty_id=route.rut_from_city_id
                            LEFT JOIN `cities` c2 ON c2.cty_id=route.rut_to_city_id
                            INNER JOIN booking b1 on b1.bkg_from_city_id = rut_from_city_id AND b1.bkg_to_city_id = rut_to_city_id AND bkg_status IN (6,7) AND DATE(b1.bkg_create_date) > '2015-10-01'
                            WHERE route.rut_active=1
                            AND route.rut_from_city_id = $city_id AND route.rut_from_city_id<>route.rut_to_city_id
                            AND c1.cty_is_airport=0 AND c2.cty_is_airport=0
                            GROUP BY route.rut_id
                            ORDER BY booking_count DESC LIMIT 0,10
                        ) A
                         UNION
                         (
                            SELECT * FROM
                             (
                                    SELECT route.rut_id,rut_from_city_id,rut_to_city_id,route.rut_name , r1.rte_amount as compact_amount,
                                    r2.rte_amount as suv_amount, r3.rte_amount as seadan_amount,

									'0' as booking_count, c1.cty_name as from_city, c2.cty_name as to_city, 
									c1.cty_alias_path as from_city_alias_path, c2.cty_alias_path as to_city_alias_path, 
									c1.cty_has_airport as airport_flag , route.rut_estm_distance 
                                    FROM `route`
                                    LEFT JOIN `rate` r1 on r1.rte_route_id=route.rut_id AND r1.rte_status=1 AND r1.rte_vehicletype_id=" . VehicleCategory::COMPACT_ECONOMIC . "
                                    LEFT JOIN `rate` r2 on r2.rte_route_id=route.rut_id AND r2.rte_status=1 AND r2.rte_vehicletype_id=" . VehicleCategory::SUV_ECONOMIC . "
                                    LEFT JOIN `rate` r3 on r3.rte_route_id=route.rut_id AND r3.rte_status=1 AND r3.rte_vehicletype_id=" . VehicleCategory::SEDAN_ECONOMIC . "
                                    LEFT JOIN `cities` c1 ON c1.cty_id=route.rut_from_city_id AND c1.cty_is_airport=0
                                    LEFT JOIN `cities` c2 ON c2.cty_id=route.rut_to_city_id AND c2.cty_is_airport=0
                                    WHERE route.rut_active=1
                                    AND route.rut_from_city_id<>route.rut_to_city_id
                                    AND route.rut_id IN
                                    (
                                       SELECT route.rut_id FROM `route`
                                       INNER JOIN `cities` c1 ON c1.cty_id=route.rut_from_city_id AND c1.cty_is_airport=0
                                       LEFT JOIN `cities` c2 ON c2.cty_id=route.rut_to_city_id AND c2.cty_is_airport=0
                                       WHERE route.rut_active=1 AND route.rut_from_city_id<>route.rut_to_city_id
                                       AND route.rut_from_city_id=$city_id AND route.rut_estm_distance BETWEEN 50 AND 400
                                       GROUP BY route.rut_id ORDER BY route.rut_estm_distance ASC
                                    )
                                    GROUP BY route.rut_id
                                    ORDER BY route.rut_estm_distance
                             )C

                         )
                         LIMIT 0,10";
		$result	 = DBUtil::queryAll($sql, DBUtil::SDB());
		Yii::app()->cache->set($key, $result, 60 * 60 * 24 * 15, new CacheDependency('topRouteQuote'));
		result:
		return $result;
	}

	public function getTempoRoutesByCityId($city_id)
	{

		$sql = "SELECT * FROM
                    (
                            SELECT route.rut_id,rut_from_city_id,rut_to_city_id,route.rut_name ,
                            r5.rte_amount as tempo_9_seater_amount, r6.rte_amount as tempo_12_seater_amount, r7.rte_amount as tempo_15_seater_amount,
                            COUNT(DISTINCT b1.bkg_id) as booking_count, c1.cty_name as from_city, c2.cty_name as to_city,
                            c1.cty_has_airport as airport_flag, route.rut_estm_distance
                            FROM `route`
                            LEFT JOIN `rate` r5 ON r5.rte_route_id=route.rut_id AND r5.rte_status=1 AND r5.rte_vehicletype_id=" . VehicleCategory::TEMPO_TRAVELLER_9_ECONOMIC . "
                            LEFT JOIN `rate` r6 ON r6.rte_route_id=route.rut_id AND r6.rte_status=1 AND r6.rte_vehicletype_id=" . VehicleCategory::TEMPO_TRAVELLER_12_ECONOMIC . "
                            LEFT JOIN `rate` r7 ON r7.rte_route_id=route.rut_id AND r7.rte_status=1 AND r7.rte_vehicletype_id=" . VehicleCategory::TEMPO_TRAVELLER_15_ECONOMIC . "
                            LEFT JOIN `cities` c1 ON c1.cty_id=route.rut_from_city_id
                            LEFT JOIN `cities` c2 ON c2.cty_id=route.rut_to_city_id
                            INNER JOIN booking b1 on b1.bkg_from_city_id = rut_from_city_id AND b1.bkg_to_city_id = rut_to_city_id AND bkg_status IN (6,7) AND DATE(b1.bkg_create_date) > '2015-10-01'
                            WHERE route.rut_active=1
                            AND route.rut_from_city_id = $city_id AND route.rut_from_city_id<>route.rut_to_city_id
                            AND c1.cty_is_airport=0 AND c2.cty_is_airport=0
                            GROUP BY route.rut_id
                            ORDER BY booking_count DESC LIMIT 0,10
                        ) A
                         UNION
                         (
                            SELECT * FROM
                             (
                                    SELECT route.rut_id,rut_from_city_id,rut_to_city_id,route.rut_name , '0' as booking_count,
                                    r5.rte_amount as tempo_9_seater_amount, r6.rte_amount as tempo_12_seater_amount, r7.rte_amount as tempo_15_seater_amount,
                                    c1.cty_name as from_city, c2.cty_name as to_city, c1.cty_has_airport as airport_flag , route.rut_estm_distance
                                    FROM `route`
                                    LEFT JOIN `rate` r5 ON r5.rte_route_id=route.rut_id AND r5.rte_status=1 AND r5.rte_vehicletype_id=" . VehicleCategory::TEMPO_TRAVELLER_9_ECONOMIC . "
                                    LEFT JOIN `rate` r6 ON r6.rte_route_id=route.rut_id AND r6.rte_status=1 AND r6.rte_vehicletype_id=" . VehicleCategory::TEMPO_TRAVELLER_12_ECONOMIC . "
                                    LEFT JOIN `rate` r7 ON r7.rte_route_id=route.rut_id AND r7.rte_status=1 AND r7.rte_vehicletype_id=" . VehicleCategory::TEMPO_TRAVELLER_15_ECONOMIC . "
                                    LEFT JOIN `cities` c1 ON c1.cty_id=route.rut_from_city_id AND c1.cty_is_airport=0
                                    LEFT JOIN `cities` c2 ON c2.cty_id=route.rut_to_city_id AND c2.cty_is_airport=0
                                    WHERE route.rut_active=1
                                    AND route.rut_from_city_id<>route.rut_to_city_id
                                    AND route.rut_id IN
                                    (
                                       SELECT route.rut_id FROM `route`
                                       INNER JOIN `cities` c1 ON c1.cty_id=route.rut_from_city_id AND c1.cty_is_airport=0
                                       LEFT JOIN `cities` c2 ON c2.cty_id=route.rut_to_city_id AND c2.cty_is_airport=0
                                       WHERE route.rut_active=1 AND route.rut_from_city_id<>route.rut_to_city_id
                                       AND route.rut_from_city_id=$city_id AND route.rut_estm_distance BETWEEN 50 AND 400
                                       GROUP BY route.rut_id ORDER BY route.rut_estm_distance ASC
                                    )
                                    GROUP BY route.rut_id
                                    ORDER BY route.rut_estm_distance
                             )C

                         )
                         LIMIT 0,10";
		return DBUtil::queryAll($sql);
	}

	public function countRouteCities()
	{
		$sql = "SELECT * FROM (
                        SELECT COUNT(1) as countRoutes FROM `route` WHERE route.rut_active=1
                    )a,
                    (
                        SELECT COUNT(1) as countCities FROM `cities` WHERE cities.cty_active=1
                    )b";
		return DBUtil::queryRow($sql, DBUtil::SDB(), [], 60 * 60 * 24 * 30, 'countRouteCities');
	}

	public function demandReport($date = '', $region = '', $sourcezone = '', $destinationzone = '', $bkg_vehicle_type_id = '', $type = 'data')
	{
		$region				 = ($region != '') ? " AND stt.stt_zone IN ($region)" : '';
		$sourcezone			 = ($sourcezone != '') ? " AND z1.zon_id IN ($sourcezone)" : '';
		$destinationzone	 = ($destinationzone != '') ? " AND z2.zon_id IN ($destinationzone)" : '';
		$bkg_vehicle_type_id = ($bkg_vehicle_type_id != '') ? " AND booking.bkg_vehicle_type_id IN ($bkg_vehicle_type_id)" : '';

		$sql = "SELECT a1.date as bkg_pickup_date, a1.zonn1 as 'from_zone', a1.zonn2 as 'to_zone', a1.count as 'up_count', a.count as 'down_count', a1.confirmed as 'up_confirmed',
            a.confirmed as 'down_confirmed', a1.vht_mk as 'bkg_vehicle_type_id'
                    FROM (
                        SELECT DATE(bkg_pickup_date) as date,z1.zon_id zon1, z1.zon_name zonn1, z2.zon_id zon2, z2.zon_name zonn2,
                        booking.bkg_vehicle_type_id bkg_vehicle_type_id, vht.vct_label AS vht_mk, COUNT(DISTINCT booking.bkg_id) as count, COUNT(DISTINCT IF(booking.bkg_reconfirm_flag=1, bkg_id, null)) as confirmed FROM booking
                            INNER JOIN zone_cities zc1 ON zc1.zct_cty_id=booking.bkg_from_city_id AND zc1.zct_active=1
                            INNER JOIN cities cty ON cty.cty_id = zc1.zct_cty_id AND cty.cty_active=1
                            INNER JOIN states stt ON stt.stt_id = cty.cty_state_id AND stt.stt_active='1' $region
                            INNER JOIN zones z1 ON z1.zon_id=zc1.zct_zon_id AND z1.zon_active=1 $sourcezone
                            INNER JOIN zone_cities zc2 ON zc2.zct_cty_id=booking.bkg_to_city_id AND zc2.zct_active=1
                            INNER JOIN zones z2 ON z2.zon_id=zc2.zct_zon_id AND z2.zon_active=1 $destinationzone
							INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id	
							INNER JOIN vehicle_category vht ON vht.vct_id = scv.scv_vct_id AND vht.vct_active = '1' $bkg_vehicle_type_id
							INNER JOIN service_class ON scc_id = scv_scc_id

                            WHERE bkg_status IN (2,3,5) AND DATE(bkg_pickup_date) ='" . $date . "' AND booking.bkg_booking_type=1 AND bkg_pickup_date>=DATE_ADD(NOW(), INTERVAL 4 HOUR)
                            GROUP BY zc1.zct_zon_id, zc2.zct_zon_id, date HAVING count>=1
                    ) a1
                    LEFT JOIN (
                        SELECT DATE(bkg_pickup_date) as date, z1.zon_id zon1, z1.zon_name zonn1, z2.zon_id zon2, z2.zon_name zonn2,
                        booking.bkg_vehicle_type_id bkg_vehicle_type_id, vht.vct_label vht_mk, COUNT(DISTINCT booking.bkg_id) as count, COUNT(DISTINCT IF(booking.bkg_reconfirm_flag=1, bkg_id, null)) as confirmed FROM booking
                        INNER JOIN zone_cities zc1 ON zc1.zct_cty_id=booking.bkg_from_city_id AND zc1.zct_active=1
                        INNER JOIN cities cty ON cty.cty_id = zc1.zct_cty_id AND cty.cty_active=1
                        INNER JOIN states stt ON stt.stt_id = cty.cty_state_id AND stt.stt_active='1'
                        INNER JOIN zones z1 ON z1.zon_id=zc1.zct_zon_id AND z1.zon_active=1
                        INNER JOIN zone_cities zc2 ON zc2.zct_cty_id=booking.bkg_to_city_id AND zc2.zct_active=1
                        INNER JOIN zones z2 ON z2.zon_id=zc2.zct_zon_id AND z2.zon_active=1
					INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id	
                    INNER JOIN vehicle_category vht ON vht.vct_id = scv.scv_vct_id AND vht.vct_active = '1'

                    WHERE bkg_status IN (2,3,5) AND DATE(bkg_pickup_date) ='" . $date . "' AND booking.bkg_booking_type=1 AND bkg_pickup_date>=DATE_ADD(NOW(), INTERVAL 4 HOUR)
                    GROUP BY zc1.zct_zon_id, zc2.zct_zon_id, date
                    ) a ON a.zon1=a1.zon2 AND a.zon2=a1.zon1 AND a.date=a1.date WHERE 1
                    ";

		if ($type == 'data')
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['bkg_pickup_date', 'from_zone', 'to_zone', 'bkg_vehicle_type_id', 'up_count', 'down_count', 'up_confirmed', 'down_confirmed'],
					'defaultOrder'	 => ''],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else if ($type == 'command')
		{
			//$sql .= " ORDER BY a1.date ASC, a1.count DESC";
			$sql .= " ORDER BY a1.date DESC";
			return DBUtil::queryAll($sql);
		}
	}

	public function getRouteDetailsbyNameList($rutNameList = [], $isApp = false, $sort = "")
	{

		$rutName = "'" . implode("','", $rutNameList) . "'";

		//var_dump($rutNameList);
		$sql = "SELECT fcty.cty_name fcity_name,tcty.cty_name tcity_name,
			    rut.rut_from_city_id,rut.rut_to_city_id,rut_id,rut.rut_name,
			    rut_estm_distance from route rut
			    JOIN cities fcty ON rut.rut_from_city_id = fcty.cty_id
			    JOIN cities tcty ON rut.rut_to_city_id = tcty.cty_id
			    where rut.rut_name IN ($rutName)";
		if ($sort == 'bk')
		{
			$sql .= ' ORDER BY rut.rut_name DESC';
		}
		$resultset	 = DBUtil::queryAll($sql, DBUtil::SDB());
		$quoteRes	 = [];
		foreach ($resultset as $result)
		{
			Logger::beginProfile("Quoted for Route: " . $result['rut_name']);
			$route							 = [];
			$routeModel						 = new BookingRoute();
			$routeModel->brt_from_city_id	 = $result['rut_from_city_id'];
			$routeModel->brt_to_city_id		 = $result['rut_to_city_id'];
			$routeModel->brt_pickup_datetime = date("Y-m-d H:i:s", strtotime("+1 day"));
			if ($isApp == true)
			{
				$routeModel->brt_from_location	 = $result['fcity_name'];
				$routeModel->brt_to_location	 = $result['tcity_name'];
			}
			$route[] = $routeModel;

//            $resQuote = Quotation::model()->getQuote($route, 1);
			$partnerId			 = Yii::app()->params['gozoChannelPartnerId'];
			$quote				 = new Quote();
			$quote->routes		 = $route;
			$quote->tripType	 = 1;
			$quote->partnerId	 = $partnerId;
			$quote->quoteDate	 = date("Y-m-d H:i:s");
			$quote->pickupDate	 = date("Y-m-d H:i:s", strtotime("+1 day"));
			if ($isApp == true)
			{

				/* @var $svcObj SvcClassVhcCat */
				$svcObj		 = SvcClassVhcCat::getVctSvcList('object', 0, 0, 0);
				$resQuote	 = $quote->getQuote($svcObj->scv_id, false, false);
				$quoteRes[]	 = [
					'routeModel'	 => $routeModel,
					'baseAmount'	 => $resQuote[$svcObj->scv_id]->routeRates->baseAmount,
					'quotedDistance' => $resQuote[$svcObj->scv_id]->routeDistance->quotedDistance,
					'routeName'		 => $result['rut_name']
				];
			}
			else
			{
				$quote->setCabTypeArr();
				$resQuote	 = $quote->getQuote();
				$baseAmt	 = [];
				foreach ($resQuote as $k => $qt)
				{
					$baseAmount = $qt->routeRates->baseAmount;
					if ($k > 0 && $baseAmount > 0)
					{
						$baseAmt[] = $baseAmount;
					}
				}
				$minBase						 = min($baseAmt);
				$quoteRes[$result['rut_name']]	 = $result + ['baseAmount' => $minBase];
			}
			Logger::endProfile("Quoted for Route: " . $result['rut_name']);
		}
		return $quoteRes;
	}

	public function getRutLog($rutid)
	{
		$qry	 = "select rut_log from route where rut_id = " . $rutid;
		$logList = DBUtil::queryRow($qry);
		return $logList;
	}

	/*	 * @deprecated
	 * New service : StructureData::getSchemaForRoute($driverId)
	 * Function for getting the Structured Markup Data as defined by schema.org
	 * @param $rModel
	 * @param $routeQuot
	 */

	public function getStructuredMarkupForRoute($rModel, $routeQuot)
	{
		$key	 = "Route::getStructuredMarkupForRoute{$rModel->rut_id}_{$routeQuot}";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			$arrStructData = $data;
			goto result;
		}
		// Route Rating
		$arrRouteRating = Ratings::getRouteSummary($rModel->rut_from_city_id, $rModel->rut_to_city_id);

		// Main Node
		$arrStructData								 = array();
		$arrStructData['@context']					 = "http://schema.org/";
		$arrStructData['@type']						 = "Service";
		$arrStructData['name']						 = $rModel->rutFromCity->cty_name . " To " . $rModel->rutToCity->cty_name . " Taxi";
		$arrStructData['aggregateRating']			 = array();
		$arrStructData['aggregateRating']['@type']	 = "AggregateRating";

		// Route Rating
		if ($arrRouteRating && count($arrRouteRating) > 0)
		{
			$arrStructData['aggregateRating']['ratingValue'] = $arrRouteRating['ratings'];
			$arrStructData['aggregateRating']['ratingCount'] = $arrRouteRating['cnt'];
		}

		//provider details function
		$arrStructData['provider']	 = array();
		$providerContactdata		 = StructureData::providerDetails();
		$arrStructData['provider']	 = $providerContactdata;

		// Offers
		$offerRateAmt	 = 0;
		$unitRate		 = '';
		if (isset($routeQuot[11]) && isset($routeQuot[11]->flexxiRates) && isset($routeQuot[11]->flexxiRates[1]) && isset($routeQuot[11]->flexxiRates[1]['subsBaseAmount']) && $routeQuot[11]->flexxiRates[1]['subsBaseAmount'] > 0)
		{
			$offerRateAmt	 = $routeQuot[11]->flexxiRates[1]['subsBaseAmount'];
			$unitRate		 = 'Per Seat';
		} /* else if(isset($routeQuot[1]) && isset($routeQuot[1]->routeRates) && isset($routeQuot[1]->routeRates->baseAmount) && $routeQuot[1]->routeRates->baseAmount > 0) {
		  $offerRateAmt = $routeQuot[1]->routeRates->baseAmount;
		  $unitRate = 'Fixed';
		  } */

		if ($offerRateAmt > 0)
		{
			$arrStructData['offers']														 = array();
			$arrStructData['offers']['@type']												 = "Offer";
			$arrStructData['offers']['priceSpecification']									 = array();
			$arrStructData['offers']['priceSpecification']['@type']							 = "PriceSpecification";
			$arrStructData['offers']['priceSpecification']['price']							 = $offerRateAmt;
			$arrStructData['offers']['priceSpecification']['priceCurrency']					 = 'INR';
			$arrStructData['offers']['priceSpecification']['eligibleQuantity']				 = array();
			$arrStructData['offers']['priceSpecification']['eligibleQuantity']['@type']		 = "QuantitativeValue";
			$arrStructData['offers']['priceSpecification']['eligibleQuantity']['unitText']	 = $unitRate;
		}

		// Provider
		/* $arrStructData['provider']					 = array();
		  $arrStructData['provider']['@type'] = "Organization";
		  $arrStructData['provider']['name'] = 'aaocab';
		  $arrStructData['provider']['url'] = Yii::app()->getBaseUrl(true);
		  $arrStructData['provider']['logo'] = Yii::app()->getBaseUrl(true) . "/images/logo2_outstation.png";

		  // Provider Contact Point
		  $arrStructData['provider']['contactPoint'] = array();

		  // Contact Point
		  $arrContactPoint = array();
		  $arrContactPoint['@type'] = "ContactPoint";
		  $arrContactPoint['telephone'] = "+91-90518-77-000";
		  $arrContactPoint['contactType'] = "Customer service";
		  $arrStructData['provider']['contactPoint'][] = $arrContactPoint;
		  $arrContactPoint['telephone'] = "+1-650-741-4696";
		  $arrStructData['provider']['contactPoint'][] = $arrContactPoint; */


		/* 	$arrStructData['provider']	 = array();
		  $providerContactdata		 = StructureData::providerDetails();
		  $arrStructData['provider']	 = $providerContactdata;

		  // Provider Rating
		  $arrCompanyInfo												 = Yii::app()->params['companyInfo'];
		  $arrStructData['provider']['aggregateRating']				 = array();
		  $arrStructData['provider']['aggregateRating']['@type']		 = "AggregateRating";
		  $arrStructData['provider']['aggregateRating']['ratingValue'] = $arrCompanyInfo['rating'];
		  $arrStructData['provider']['aggregateRating']['ratingCount'] = $arrCompanyInfo['ratingCount']; */

		Logger::profile("VehicleTypes::model()->getCarType");

		// Related To
		$arrStructData['isRelatedTo']									 = array();
		$arrStructData['isRelatedTo']['@type']							 = "TaxiService";
		$arrStructData['isRelatedTo']['areaServed']						 = array();
		$arrStructData['isRelatedTo']['areaServed']['@type']			 = "Place";
		$arrStructData['isRelatedTo']['areaServed']['geo']				 = array();
		$arrStructData['isRelatedTo']['areaServed']['geo']['@type']		 = "GeoCoordinates";
		$arrStructData['isRelatedTo']['areaServed']['geo']['latitude']	 = $rModel->rutFromCity->cty_lat;
		$arrStructData['isRelatedTo']['areaServed']['geo']['longitude']	 = $rModel->rutFromCity->cty_long;

		// Similar To
		$arrStructData['isSimilarTo']									 = array();
		$arrStructData['isSimilarTo']['@type']							 = "Flight";
		$arrStructData['isSimilarTo']['itinerary']						 = array();
		$arrStructData['isSimilarTo']['itinerary']['@type']				 = "ItemList";
		$arrStructData['isSimilarTo']['itinerary']['numberOfItems']		 = 2;
		$arrStructData['isSimilarTo']['itinerary']['itemListElement']	 = array();

		// Itinerary Items
		$arrItineraryItems												 = array();
		$arrItineraryItems['@type']										 = "ListItem";
		$arrItineraryItems['position']									 = 1;
		$arrItineraryItems['item']										 = array();
		$arrItineraryItems['item']['@type']								 = "City";
		$arrItineraryItems['item']['name']								 = $rModel->rutFromCity->cty_name;
		$arrItineraryItems['item']['geo']								 = array();
		$arrItineraryItems['item']['geo']['@type']						 = "GeoCoordinates";
		$arrItineraryItems['item']['geo']['latitude']					 = $rModel->rutFromCity->cty_lat;
		$arrItineraryItems['item']['geo']['longitude']					 = $rModel->rutFromCity->cty_long;
		$arrStructData['isSimilarTo']['itinerary']['itemListElement'][]	 = $arrItineraryItems;

		$arrItineraryItems['position']									 = 2;
		$arrItineraryItems['item']['name']								 = $rModel->rutToCity->cty_name;
		$arrItineraryItems['item']['geo']								 = array();
		$arrItineraryItems['item']['geo']['@type']						 = "GeoCoordinates";
		$arrItineraryItems['item']['geo']['latitude']					 = $rModel->rutToCity->cty_lat;
		$arrItineraryItems['item']['geo']['longitude']					 = $rModel->rutToCity->cty_long;
		$arrStructData['isSimilarTo']['itinerary']['itemListElement'][]	 = $arrItineraryItems;

		// Offer Catalog
		$arrStructData['hasOfferCatalog']			 = array();
		$arrStructData['hasOfferCatalog']['@type']	 = "OfferCatalog";
		$arrStructData['hasOfferCatalog']['name']	 = "Car Taxi Services";

		$arrOfferCatalog					 = array();
		$arrOfferCatalog['@type']			 = "OfferCatalog";
		$arrOfferCatalog['itemListElement']	 = array();

		// Vehicle Types/ Category
		$arrVehicleTypes = VehicleTypes::model()->getCarType();

		// Vehicle Model Types
		$arrVehicleModels = VehicleTypes::model()->getMasterCarDetails();

		// Vehicle Model Rates
		if ($routeQuot && is_array($routeQuot) && count($routeQuot) > 0)
		{
			foreach ($routeQuot as $cabKey => $baseQuot)
			{
				// One Way Rates
				$ratePerKM = $baseQuot->routeRates->ratePerKM;

				// Round Trip Rates
				$roundTripType	 = 2;
				//	$rates			 = AreaPriceRule::model()->getRules($rModel->rutFromCity->cty_id, $cabKey);
//				if ($rates && isset($rates[$roundTripType]))
//				{
				//	$priceRule = PriceRule::model()->findByPk($rates[$roundTripType]);
				$priceRule		 = PriceRule::getByCity($rModel->rutFromCity->cty_id, $roundTripType, $cabKey);
				if ($priceRule)
				{
					$ratePerKM = $priceRule->prr_rate_per_km_extra;
				}
				//	}

				$vehicleType		 = $arrVehicleTypes[$cabKey];
				$vehicleModelData	 = $arrVehicleModels[$cabKey];

				$arrStructVehicleOptions			 = array();
				$arrStructVehicleOptions['@type']	 = "Offer";

				$arrStructVehicleOptions['priceSpecification']									 = array();
				$arrStructVehicleOptions['priceSpecification']['@type']							 = "PriceSpecification";
				$arrStructVehicleOptions['priceSpecification']['price']							 = $ratePerKM;
				$arrStructVehicleOptions['priceSpecification']['priceCurrency']					 = 'INR';
				$arrStructVehicleOptions['priceSpecification']['eligibleQuantity']				 = array();
				$arrStructVehicleOptions['priceSpecification']['eligibleQuantity']['@type']		 = "QuantitativeValue";
				$arrStructVehicleOptions['priceSpecification']['eligibleQuantity']['unitText']	 = "KM";

				$arrStructVehicleOptions['itemOffered']								 = array();
				$arrStructVehicleOptions['itemOffered']['@type']					 = "Car";
				$arrStructVehicleOptions['itemOffered']['vehicleSeatingCapacity']	 = $vehicleModelData['vht_capacity'];
				$arrStructVehicleOptions['itemOffered']['category']					 = $vehicleType;
				$arrStructVehicleOptions['itemOffered']['model']					 = $vehicleModelData['vht_model'];

				// For Type Flexxi Resetting Values
				if ($cabKey == VehicleCategory::SHARED_SEDAN_ECONOMIC)
				{
					$arrStructVehicleOptions['priceSpecification']['price']							 = $offerRateAmt;
					$arrStructVehicleOptions['priceSpecification']['eligibleQuantity']['unitText']	 = $unitRate;
					unset($arrStructVehicleOptions['itemOffered']['vehicleSeatingCapacity']);
				}

				$arrOfferCatalog['itemListElement'][] = $arrStructVehicleOptions;
			}
		}

		$arrStructData['hasOfferCatalog']['itemListElement']	 = array();
		$arrStructData['hasOfferCatalog']['itemListElement'][]	 = $arrOfferCatalog;
		$data													 = $arrStructData;

		Yii::app()->cache->set($key, $data, 24 * 7 * 60 * 60, new CacheDependency("RouteRates"));

		result:
		return $arrStructData;
	}

	public function getBreadcumbMarkupForRoute($fromcityId, $tocityid)
	{
		$key				 = "getBreadcumbMarkupForroute_{$fromcityId}_{$tocityid}";
		$breadcumbDataJson	 = Yii::app()->cache->get($key);

		if ($breadcumbDataJson != false)
		{
			goto result;
		}


		$breadcumbData		 = StructureData::breadCumbDetails($fromcityId, $tocityid, 'route_type');
		$breadcumbDataJson	 = json_encode($breadcumbData, JSON_UNESCAPED_SLASHES);
		Yii::app()->cache->set($key, $breadcumbDataJson, 60 * 60 * 24 * 30);
		result:
		return $breadcumbDataJson;
	}

	public function setTop8Routes()
	{
		//, 176, 6, 37, 43, 3652, 3455, 3408, 103, 4707, 12270, 4495
		$routeList = [3307, 3320, 1, 3295, 175, 348, 97, 349];
		return $routeList;
	}

	public function getRoutebyQuery($query = '', $rut = '')
	{
		$qry		 = '';
		$limitNum	 = 30;

		if ($rut != '')
		{
			$qry1		 = " AND (1 OR rut.rut_id='$rut')";
			$con		 = " ,if(rut.rut_id =$rut,2,0) rtrank1";
			$limitNum	 = 29;
		}
		if ($query == '')
		{
			$qry .= "  JOIN (SELECT   rut_id, COUNT(bkg_id) AS cnt
             FROM     route JOIN booking ON bkg_from_city_id = rut_from_city_id AND bkg_to_city_id = rut_to_city_id
             WHERE    bkg_status IN (2, 3, 5, 6, 7, 9) AND bkg_create_date > DATE_SUB(NOW(), INTERVAL 4 MONTH)
             GROUP BY rut_id
             ORDER BY cnt DESC LIMIT  0, $limitNum) a
         ON rut.rut_id = a.rut_id";
			$con = " ,0 rtrank1,0 rtrank2";
		}
		else
		{
//			$qry1 .= " AND (fct.cty_name LIKE '%{$query}%' OR tct.cty_name LIKE '%{$query}%') ";
			$qry1	 .= " AND  (rut_name LIKE '%{$query}%') ";
			$con	 = " ,if(fct.cty_name LIKE '%{$query}%',2,0) rtrank1,if(tct.cty_name LIKE '%{$query}%',1,0) rtrank2";
		}

		$sql = "SELECT rut.rut_id,concat(fct.cty_name,' - ',tct.cty_name) as rut_name $con
                FROM   route rut
       JOIN cities fct ON rut.rut_from_city_id = fct.cty_id
       JOIN cities tct ON rut.rut_to_city_id = tct.cty_id
           $qry     WHERE rut.rut_active=1   $qry1 ORDER BY rtrank1 DESC,rtrank2 DESC,rut.rut_name LIMIT 0,30 ";

		return DBUtil::queryAll($sql);
	}

	public function getJSONRoutebyQuery($query = '', $rut = '')
	{
		$rows	 = $this->getRoutebyQuery($query, $rut);
		$arrRut	 = [];
		foreach ($rows as $row)
		{
			$arrRut[] = array("id" => $row['rut_id'], "text" => $row['rut_name']);
		}
		$data = CJSON::encode($arrRut);
		return $data;
	}

	public function isDDBPEnable($fromCity, $toCity)
	{
		$routeData = $this;
		if ($this->rut_id == null)
		{
			$routedata = Route::model()->getbyCities($fromCity, $toCity);
			if (!$routeData)
				return false;
		}
		return ($routedata->rut_override_dr == 1);
	}

	public function addSiteRoutesCron()
	{
		$sql	 = "SELECT rut_id, rut_name, IF(cntbkg IS NOT NULL, cntbkg, 0) as popular FROM route
					LEFT JOIN (SELECT booking.bkg_from_city_id, booking.bkg_to_city_id, count(bkg_id) as cntbkg FROM booking 
						WHERE booking.bkg_from_city_id<>booking.bkg_to_city_id 
						AND booking.bkg_status IN (6,7)
                        AND booking.bkg_pickup_date > DATE_SUB(now(), INTERVAL 3 MONTH)    
						GROUP BY booking.bkg_from_city_id, booking.bkg_to_city_id 
						) bkg ON route.rut_from_city_id=bkg.bkg_from_city_id AND route.rut_to_city_id=bkg.bkg_to_city_id 
					WHERE rut_active=1 AND rut_name<>'' AND rut_estm_distance<=1000 AND cntbkg <>0 ORDER BY popular DESC, rut_name";
		$rows	 = DBUtil::query($sql);
		if (count($rows > 0))
		{
			$sql1 = "";
			foreach ($rows as $row)
			{
				$sql1 .= " INSERT INTO sitemap_routes(rut_id, rut_name, popular) 
						 VALUES ('" . $row['rut_id'] . "', '" . $row['rut_name'] . "', '0.9')
						ON DUPLICATE KEY UPDATE rut_id = '" . $row['rut_id'] . "',popular = '0.9', last_updated_date=now(); ";
			}
			DBUtil::command($sql1)->execute();
		}
	}

	public function getSiteRoutesCron()
	{
		$sql	 = "SELECT rut_id, rut_name FROM sitemap_routes ORDER BY popular DESC, rut_name";
		$rows	 = DBUtil::query($sql);
		$arrRut	 = array();
		foreach ($rows as $row)
		{
			$arrRut[] = array("id" => $row['rut_id'], "text" => $row['rut_name']);
		}

		return $arrRut;
	}

	public function cityRouteCountCron()
	{
		$query	 = "SELECT COUNT(1) AS cnt
					FROM route
						 INNER JOIN cities
							ON     route.rut_from_city_id = cty_id
							   AND rut_active = 1
							   AND rut_estm_distance < 1000
							   AND `rut_from_city_id` <> `rut_to_city_id`
							   AND rut_id NOT IN
									  (SELECT sitemap_routes.rut_id FROM sitemap_routes)
						 INNER JOIN states ON states.stt_id = cty_state_id
					GROUP BY states.stt_id
					HAVING count(states.stt_id) >= 0
					ORDER BY states.stt_id";
		$result	 = DBUtil::queryAll($query, DBUtil::MDB());
		return $result;
	}

	public function allCityRouteListCron($state_id, $lowerlimit, $number_of_data)
	{
		$sql	 = "SELECT REPLACE(rut_name, ' ', '_' ) as rut_name FROM route
				INNER JOIN cities ON route.rut_from_city_id = cty_id 
				AND rut_active=1 AND rut_estm_distance < 1000 AND `rut_from_city_id` <> `rut_to_city_id` 
				AND rut_id NOT IN (SELECT sitemap_routes.rut_id FROM sitemap_routes)
				AND cty_state_id = " . $state_id . " LIMIT $lowerlimit, $number_of_data";
		$rows	 = DBUtil::queryAll($sql);
		return $rows;
	}

	public static function createNearestRouteTemp($fromCity, $toCity, $tableName, $db = null)
	{
		if ($db == null)
		{
			$db = DBUtil::SDB();
		}
		$key			 = "RutIds_{$tableName}";
		$filterRutIds	 = "";
		$rutIds			 = Yii::app()->cache->get($key);
		if ($rutIds !== false && $rutIds != '')
		{
			$filterRutIds = " AND routeAlias.rut_id IN ($rutIds)";
		}

		$sql = "(INDEX indx_nearest_rt_rutid (rut_id)) 
			SELECT routeAlias.rut_id, routeAlias.rut_from_city_id start, routeAlias.rut_to_city_id end, routeFrom.rut_name as fromAlias, 
				NULL AS toAlias, routeAlias.rut_name, route.rut_estm_distance as quotedDistance, routeAlias.rut_estm_distance as rateDistance, 
					(IFNULL(route.rut_actual_distance, route.rut_estm_distance) + IFNULL(routeFrom.rut_actual_distance, routeFrom.rut_estm_distance) 
						- IFNULL(routeAlias.rut_actual_distance, routeAlias.rut_estm_distance)) AS extraDistance, 
				IFNULL(routeFrom.rut_actual_distance, routeFrom.rut_estm_distance) AS extraStartDistance, 
				0 as rank, routeFrom.rut_estm_time as startTime, 0 as endTime, route.rut_estm_time, 
				(IFNULL(routeFrom.rut_actual_distance, routeFrom.rut_estm_distance) + IFNULL(routeAlias.rut_actual_distance, routeAlias.rut_estm_distance)) as extraTravelDistance
			FROM route
			INNER JOIN route routeFrom ON routeFrom.rut_to_city_id = route.rut_from_city_id AND route.rut_from_city_id=$fromCity 
				AND route.rut_to_city_id=$toCity AND routeFrom.rut_to_city_id=$fromCity AND routeFrom.rut_estm_distance < 50 
				AND routeFrom.rut_from_city_id<>routeFrom.rut_to_city_id AND route.rut_active=1 AND routeFrom.rut_active=1
			INNER JOIN route routeAlias ON (routeAlias.rut_from_city_id = routeFrom.rut_from_city_id) 
						AND (routeAlias.rut_to_city_id=route.rut_to_city_id) AND routeAlias.rut_active = 1 $filterRutIds
			UNION
			SELECT routeAlias.rut_id, routeAlias.rut_from_city_id start, routeAlias.rut_to_city_id end, NULL, routeTo.rut_name, routeAlias.rut_name, 
				route.rut_estm_distance as quotedDistance, routeAlias.rut_estm_distance as rateDistance, 
					(IFNULL(route.rut_actual_distance, route.rut_estm_distance) + IFNULL(routeTo.rut_actual_distance, routeTo.rut_estm_distance) 
							- IFNULL(routeAlias.rut_actual_distance, routeAlias.rut_estm_distance)) AS extraDistance, 
				0 AS extraStartDistance, 0, 0 as startTime, routeTo.rut_estm_time as endTime, route.rut_estm_time, 
				(IFNULL(routeTo.rut_actual_distance, routeTo.rut_estm_distance) + IFNULL(routeAlias.rut_actual_distance, routeAlias.rut_estm_distance)) as extraTravelDistance
			FROM   route
			INNER JOIN route routeTo ON routeTo.rut_from_city_id = route.rut_to_city_id AND route.rut_from_city_id = $fromCity AND route.rut_to_city_id=$toCity 
				AND routeTo.rut_from_city_id=$toCity
				AND route.rut_active = 1 AND routeTo.rut_active = 1 AND routeTo.rut_estm_distance < 50 AND routeTo.rut_from_city_id<>routeTo.rut_to_city_id
			INNER JOIN route routeAlias ON (routeAlias.rut_from_city_id = route.rut_from_city_id) 
			   AND (routeAlias.rut_to_city_id = routeTo.rut_to_city_id) AND routeAlias.rut_active = 1 $filterRutIds
			UNION
			SELECT routeAlias.rut_id, routeAlias.rut_from_city_id start, routeAlias.rut_to_city_id end, 
				routeFrom.rut_name, routeTo.rut_name, routeAlias.rut_name, 
				route.rut_estm_distance as quotedDistance, routeAlias.rut_estm_distance as rateDistance, 
					(IFNULL(route.rut_actual_distance, route.rut_estm_distance) + IFNULL(routeFrom.rut_actual_distance, routeFrom.rut_estm_distance) 
					+ IFNULL(routeTo.rut_actual_distance, routeTo.rut_estm_distance) - IFNULL(routeAlias.rut_actual_distance, routeAlias.rut_estm_distance)) 
				AS extraDistance, IFNULL(routeFrom.rut_actual_distance, routeFrom.rut_estm_distance) AS extraStartDistance, 0, 
				routeFrom.rut_estm_time as startTime, routeTo.rut_estm_time as endTime, route.rut_estm_time, 
				(IFNULL(routeFrom.rut_actual_distance, routeFrom.rut_estm_distance) + IFNULL(routeTo.rut_actual_distance, routeTo.rut_estm_distance) + IFNULL(routeAlias.rut_actual_distance, routeAlias.rut_estm_distance)) as extraTravelDistance
			FROM   route
			INNER JOIN route routeFrom ON routeFrom.rut_to_city_id = route.rut_from_city_id AND route.rut_from_city_id=$fromCity 
					AND route.rut_to_city_id=$toCity AND routeFrom.rut_to_city_id=$fromCity AND routeFrom.rut_estm_distance < 50 AND routeFrom.rut_from_city_id <> routeFrom.rut_to_city_id
					AND route.rut_active=1 AND routeFrom.rut_active=1
			INNER JOIN route routeTo ON routeTo.rut_from_city_id = route.rut_to_city_id AND routeTo.rut_estm_distance < 50 
					AND routeTo.rut_from_city_id=$toCity AND routeTo.rut_from_city_id<>routeTo.rut_to_city_id
			INNER JOIN route routeAlias ON (routeAlias.rut_from_city_id = routeFrom.rut_from_city_id) 
					AND (routeAlias.rut_to_city_id=routeTo.rut_to_city_id) AND routeAlias.rut_active = 1 AND routeAlias.rut_from_city_id=$fromCity $filterRutIds";

		DBUtil::createTempTable($tableName, $sql, $db);

		if ($rutIds === false)
		{
			$sqlRutIds	 = "SELECT GROUP_CONCAT(rut_id) as rutIds FROM $tableName";
			$rutIds		 = DBUtil::queryScalar($sqlRutIds, $db);
			Yii::app()->cache->set($key, $rutIds, 86400 * 5, new CacheDependency(CacheDependency::Type_Routes));
		}
	}

	/**
	 * 
	 * @param integer $fromCityId
	 * @param integer $toCityId
	 * @param integer $promoFlag
	 * @return boolean
	 */
	public static function getApplicable($fromCityId, $toCityId, $promoFlag)
	{
		$isApplicable = false;
		switch ($promoFlag)
		{
			case 1:
				$isApplicableRoute	 = Route::model()->getRutidbyCities($fromCityId, $toCityId, 1)->rut_is_promo_code_apply;
				break;
			case 2:
				$isApplicableRoute	 = Route::model()->getRutidbyCities($fromCityId, $toCityId, 1)->rut_is_promo_gozo_coins_apply;
				break;
			case 3:
				$isApplicableRoute	 = Route::model()->getRutidbyCities($fromCityId, $toCityId, 1)->rut_is_cod_apply;
				break;
		}
		$isApplicableZone = ZoneCities::model()->getApplyByZone($fromCityId, $promoFlag);
		if (($isApplicableRoute == 1 || $isApplicableRoute == '') && ($isApplicableZone || $isApplicableZone == ''))
		{
			$isApplicable = true;
		}
		return $isApplicable;
	}

	/**
	 * @param BookingRoute[] $routes 
	 */
	public static function addNeareastReturnRoute($routes)
	{
		$pickupRoute = $routes[0];
		$dropRoute	 = $routes[count($routes) - 1];
		$startCity	 = $endCity	 = $pickupCity	 = $pickupRoute->brt_from_city_id;
		$dropCity	 = $dropRoute->brt_to_city_id;
		$nearestRow	 = Route::getNearestCityForRoundTrip($pickupCity, $dropCity);
		if ($nearestRow)
		{
			$startCity					 = $nearestRow["startCity"];
			$endCity					 = $nearestRow["endCity"];
			$route1						 = new BookingRoute();
			$route1->brt_from_city_id	 = $startCity;
			$route1->brt_to_city_id		 = $pickupCity;
			$pickupTime					 = date("Y-m-d H:i:s", strtotime($pickupRoute->brt_pickup_datetime) - ($nearestRow["startTime"] * 60));
			$route1->brt_pickup_datetime = $pickupTime;
			$route1->calculateDistance();
			array_unshift($routes, $route1);
		}
		$route						 = new BookingRoute();
		$route->brt_from_city_id	 = $dropCity;
		$route->brt_to_city_id		 = $endCity;
		$route->brt_pickup_datetime	 = date('Y-m-d H:i:s', strtotime($dropRoute->brt_pickup_datetime . ' +' . $dropRoute->brt_trip_duration . ' minute'));
		$route->calculateDistance();
		array_push($routes, $route);

		return $routes;
	}

	public static function getNearestCityForRoundTrip($pickupCity, $dropCity)
	{
		$key = "getNearestCityForRoundTrip::$pickupCity-$dropCity";
		$arr = Yii::app()->cache->get($key);
		if ($arr)
		{
			$data = $arr;
			goto end;
		}
		if (!$arr)
		{
			$arr = [];
		}
		$randomNumber	 = rand();
		$tempTable		 = "GarageRoute$randomNumber";
		DBUtil::dropTempTable($tempTable);

		$params = ["dropCity" => $dropCity];

		$sqlCreate = " (INDEX my_index_name (rut_id), INDEX my_index_name1 (rut_from_city_id))
							SELECT route.rut_id, route.rut_from_city_id, route.rut_to_city_id, route.rut_name, rut_estm_distance, rut_estm_time
							FROM route 
							INNER JOIN cities_stats   ON cities_stats.cts_cty_id = route.rut_from_city_id
							AND rut_estm_distance < 300
							AND rut_active = 1
							AND route.rut_to_city_id=$pickupCity
							AND cities_stats.cts_vnd_cnt >= 3
							ORDER BY rut_estm_distance LIMIT 10";

		DBUtil::createTempTable($tempTable, $sqlCreate);

		$sql = "SELECT a.*, fc.cty_id as c2id, fc.cty_name as c2name, fc.cty_garage_address as c2address,
								tc.cty_id as c4id, tc.cty_name as c4name, tc.cty_garage_address as c4address 
						FROM (
							(
							SELECT xyz.rut_from_city_id as startCity, r2.rut_to_city_id as endCity, 
									xyz.rut_estm_distance as startDistance,
									IFNULL(r2.rut_actual_distance, r2.rut_estm_distance) as endDistance,
									xyz.rut_estm_time as startTime,
									IFNULL(r2.rut_actual_time, r2.rut_estm_time) as endTime,
									xyz.rut_name as startRoute, r1.rut_name as servingRoute, r2.rut_name as endRoute,
								   (xyz.rut_estm_distance + r2.rut_estm_distance) as ExtraDistance
							FROM $tempTable as xyz
							INNER JOIN route r1 ON r1.rut_from_city_id=xyz.rut_to_city_id AND r1.rut_to_city_id=:dropCity
							INNER JOIN route r2 ON r2.rut_to_city_id=xyz.rut_from_city_id AND r2.rut_from_city_id=r1.rut_to_city_id
							ORDER BY ExtraDistance ASC LIMIT 1
							)) a
						INNER JOIN cities fc ON startCity=fc.cty_id
						INNER JOIN cities tc ON endCity=tc.cty_id 
						ORDER BY ExtraDistance";

		$data = DBUtil::queryRow($sql, DBUtil::SDB(), $params, 2 * 24 * 60 * 60, CacheDependency::Type_Routes);
		Yii::app()->cache->set($key, $data, 4 * 60 * 60, new CacheDependency('routes'));
		DBUtil::dropTempTable($tempTable);
		end:
		return $data;
	}

	public static function getMmtC1RouteByCity($fromCity, $toCity)
	{
		$sql = "SELECT mcr_route_id FROM `mmt_c1_routes` WHERE mcr_from_city_id = {$fromCity} AND mcr_to_city_id = {$toCity} AND mcr_is_active = 1";
		return DBUtil::queryScalar($sql);
	}

	/**
	 * This function is used to find nearest city Rates
	 * @param type (int) $fromCity
	 * @param type (int) $toCity
	 * @param type (int) $cabType
	 * @return type (array) $res
	 */
	public function getNearestRouteRate($fromCity, $toCity, $cabType, $costPerKm = 7)
	{
		$nearestRouteTable = Route::getNearestRoute($fromCity, $toCity);

		$params	 = ['cabType' => $cabType];
		$sql	 = "SELECT *, (rte_vendor_amount + extradistance * $costPerKm ) AS totalCost, 
						ABS(quotedDistance-rateDistance) AS extraChargeableDistance
					FROM $nearestRouteTable a
					INNER JOIN rate ON a.rut_id=rate.rte_route_id AND rte_status=1 
						AND rate.rte_vehicletype_id=:cabType AND rate.rte_vendor_amount <> 0
					WHERE  1
					ORDER  BY rank DESC, extraStartDistance, extraChargeableDistance ASC, totalcost ASC
					LIMIT  0, 1";
		$res	 = DBUtil::queryRow($sql, DBUtil::SDB2(), $params, 86400, CacheDependency::Type_Rates);
		return $res;
	}

	public static function getNearestRoute($fromCity, $toCity, $distance = 70)
	{
		$tempTable	 = "getNearestRoute_{$fromCity}_{$toCity}_{$distance}";
		$sqlCreate	 = "(INDEX {$tempTable}_index_rut_id (rut_id))
						SELECT routeAlias.rut_id, routeAlias.rut_from_city_id AS start, routeAlias.rut_to_city_id AS end,
								routeFrom.rut_name AS fromAlias, NULL AS toAlias, routeAlias.rut_name, route.rut_estm_distance AS quotedDistance,
								routeAlias.rut_estm_distance AS rateDistance, 
								(IFNULL(route.rut_actual_distance, route.rut_estm_distance) + IFNULL(routeFrom.rut_actual_distance, routeFrom.rut_estm_distance)
									 - IFNULL(routeAlias.rut_actual_distance, routeAlias.rut_estm_distance)) AS extraDistance,
								IFNULL(routeFrom.rut_actual_distance, routeFrom.rut_estm_distance) AS extraStartDistance,
								0 AS rank, routeFrom.rut_estm_time AS startTime, 0 AS endTime, route.rut_estm_time, 
								(IFNULL(routeFrom.rut_actual_distance, routeFrom.rut_estm_distance) + IFNULL(routeAlias.rut_actual_distance, routeAlias.rut_estm_distance)) AS extraTravelDistance
						FROM   route
						INNER JOIN route routeTo ON routeTo.rut_to_city_id=route.rut_from_city_id
								AND route.rut_from_city_id=$fromCity AND routeTo.rut_active=1
								AND routeTo.rut_estm_distance<70 AND route.rut_active=1
						INNER JOIN route routeFrom ON routeFrom.rut_from_city_id=route.rut_to_city_id
								AND route.rut_to_city_id=$toCity AND routeFrom.rut_active = 1
								AND routeFrom.rut_estm_distance<70 AND route.rut_active = 1
						INNER JOIN route routeAlias ON routeAlias.rut_to_city_id=routeFrom.rut_to_city_id
							AND routeAlias.rut_from_city_id=routeTo.rut_from_city_id AND routeAlias.rut_active = 1";

		DBUtil::createTempTable($tempTable, $sqlCreate, DBUtil::SDB2());

		return $tempTable;
	}

	public static function getIdByCities($from, $to)
	{
		return DBUtil::queryScalar("SELECT rut_id FROM `route` WHERE rut_from_city_id =:fromCty AND rut_to_city_id=:toCty AND rut_active = 1", DBUtil::SDB(), ['fromCty' => $from, 'toCty' => $to]);
	}

	/**
	 * 
	 * @param Booking $model
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function getMarginByRoutes($model, $command = DBUtil::ReturnType_Provider)
	{
		$sql = "SELECT
				SUM(tempfinal.totalInquiry) AS totalInquiry,
				SUM(tempfinal.B2C) AS B2CInquiry,
				SUM(tempfinal.B2B) AS B2BInquiry,
				SUM(tempfinal.totalConfirm) AS totalConfirm,
				SUM(tempfinal.B2CConfirm) AS B2CConfirm,
				SUM(tempfinal.B2BConfirm) AS B2BConfirm,
				tempfinal.fromCity,
				tempfinal.toCity,
				c1.cty_name as fromCityName,
			    c2.cty_name as toCityName,
				tempfinal.rut_name,
				tempfinal.distance,
				tempfinal.createDate
				FROM
				(
					SELECT
					SUM(TEMP.B2C + TEMP.B2B) AS totalInquiry,
					SUM(TEMP.B2C) AS B2C,
					SUM(TEMP.B2B) AS B2B,
					0 AS totalConfirm,
					0 AS B2CConfirm,
					0 AS B2BConfirm,
					TEMP.fromCity,
					TEMP.toCity,
					rut_name,
					rut_estm_distance AS distance,
					TEMP.createDate
					FROM
					(
						SELECT
						COUNT(1) AS B2C,
						0 AS B2B,
						bkg_from_city_id AS fromCity,
						bkg_to_city_id AS toCity,
						DATE(bkg_create_date) AS createDate
						FROM `booking_temp`
						WHERE 1
						AND booking_temp.bkg_create_date BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2' 
						AND bkg_from_city_id > 0 AND bkg_to_city_id > 0
						GROUP By bkg_from_city_id ,bkg_to_city_id

					UNION

						SELECT
						0 AS B2C,
						COUNT(1) AS B2B,
						aat_from_city AS fromCity,
						aat_to_city AS toCity,
						DATE(aat_created_at) AS createDate
						FROM `agent_api_tracking`
						WHERE 1
						AND agent_api_tracking.aat_created_at BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2'
						AND aat_type=2
						AND aat_from_city >0
						AND aat_to_city>0
						GROUP BY aat_from_city,aat_to_city

					) AS TEMP
					INNER JOIN route ON route.rut_from_city_id=TEMP.fromCity AND route.rut_to_city_id=TEMP.toCity
					WHERE 1 AND (TEMP.fromCity>0 AND TEMP.toCity>0) AND route.rut_active=1
					GROUP BY TEMP.fromCity,TEMP.toCity

				UNION


						SELECT
						0 AS totalInquiry,
						0 AS B2C,
						0 AS B2B,
						SUM(IF(bkg_advance_amount > 0, 1, 0)) AS totalConfirm,
						SUM(IF(bkg_advance_amount > 0 AND bkg_agent_id IS NULL,1,0)) AS B2CConfirm,
						SUM(IF(bkg_advance_amount > 0 AND bkg_agent_id IS NOT NULL,1,0)) AS B2BConfirm,
						bkg_from_city_id AS fromCity,
						bkg_to_city_id AS toCity,
						rut_name,
						rut_estm_distance AS distance,
						DATE(bkg_create_date) AS createDate
						FROM `booking`
						INNER JOIN `route` ON route.rut_from_city_id=bkg_from_city_id AND route.rut_to_city_id=bkg_to_city_id
						INNER JOIN `booking_invoice` ON bkg_id=biv_bkg_id 
						WHERE (bkg_agent_id IN (450,18190) OR bkg_agent_id IS NULL)
						AND booking.bkg_create_date BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2'
						AND bkg_active = 1
						AND bkg_status IN (2, 3, 5, 6, 7)
						AND route.rut_active=1
						AND (bkg_from_city_id>0 OR bkg_to_city_id>0)
						GROUP By bkg_from_city_id,bkg_to_city_id

				) tempfinal 
				LEFT JOIN `cities` as c1 ON c1.cty_id=tempfinal.fromCity 
				LEFT JOIN `cities` as c2 ON c2.cty_id=tempfinal.toCity
				WHERE 1 AND (tempfinal.fromCity>0 AND tempfinal.toCity>0)
				GROUP BY tempfinal.fromCity,tempfinal.toCity";

		$sqlCount = "SELECT
						COUNT(1) AS cnt
					FROM
						(
						SELECT
							SUM(tempfinal.totalInquiry) AS totalInquiry,
							SUM(tempfinal.B2C) AS B2CInquiry,
							SUM(tempfinal.B2B) AS B2BInquiry,
							SUM(tempfinal.totalConfirm) AS totalConfirm,
							SUM(tempfinal.B2CConfirm) AS B2CConfirm,
							SUM(tempfinal.B2BConfirm) AS B2BConfirm,
							tempfinal.fromCity,
							tempfinal.toCity,
							c1.cty_name AS fromCityName,
							c2.cty_name AS toCityName,
							tempfinal.rut_name,
							tempfinal.distance,
							tempfinal.createDate
						FROM
							(
							SELECT
								SUM(TEMP.B2C + TEMP.B2B) AS totalInquiry,
								SUM(TEMP.B2C) AS B2C,
								SUM(TEMP.B2B) AS B2B,
								0 AS totalConfirm,
								0 AS B2CConfirm,
								0 AS B2BConfirm,
								TEMP.fromCity,
								TEMP.toCity,
								rut_name,
								rut_estm_distance AS distance,
								TEMP.createDate
							FROM
								(
								SELECT
									COUNT(1) AS B2C,
									0 AS B2B,
									bkg_from_city_id AS fromCity,
									bkg_to_city_id AS toCity,
									DATE(bkg_create_date) AS createDate
								FROM
									`booking_temp`
								WHERE
									1 AND booking_temp.bkg_create_date BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2' AND bkg_from_city_id > 0 AND bkg_to_city_id > 0
								GROUP BY
									bkg_from_city_id,
									bkg_to_city_id
								UNION
							SELECT
								0 AS B2C,
								COUNT(1) AS B2B,
								aat_from_city AS fromCity,
								aat_to_city AS toCity,
								DATE(aat_created_at) AS createDate
							FROM
								`agent_api_tracking`
							WHERE
								1 AND agent_api_tracking.aat_created_at BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2' AND aat_type = 2 AND aat_from_city > 0 AND aat_to_city > 0
							GROUP BY
								aat_from_city,
								aat_to_city
							) AS TEMP
						INNER JOIN route ON route.rut_from_city_id = TEMP.fromCity AND route.rut_to_city_id = TEMP.toCity
						WHERE
							1 AND(
								TEMP.fromCity > 0 AND TEMP.toCity > 0
							) AND route.rut_active = 1
						GROUP BY
							TEMP.fromCity,
							TEMP.toCity
						UNION
					SELECT
						0 AS totalInquiry,
						0 AS B2C,
						0 AS B2B,
						SUM(
							IF(bkg_advance_amount > 0, 1, 0)
						) AS totalConfirm,
						SUM(
							IF(
								bkg_advance_amount > 0 AND bkg_agent_id IS NULL,
								1,
								0
							)
						) AS B2CConfirm,
						SUM(
							IF(
								bkg_advance_amount > 0 AND bkg_agent_id IS NOT NULL,
								1,
								0
							)
						) AS B2BConfirm,
						bkg_from_city_id AS fromCity,
						bkg_to_city_id AS toCity,
						rut_name,
						rut_estm_distance AS distance,
						DATE(bkg_create_date) AS createDate
					FROM
						`booking`
					INNER JOIN `route` ON route.rut_from_city_id = bkg_from_city_id AND route.rut_to_city_id = bkg_to_city_id
					INNER JOIN `booking_invoice` ON bkg_id = biv_bkg_id
					WHERE
						(
							bkg_agent_id IN(450, 18190) OR bkg_agent_id IS NULL
						) AND booking.bkg_create_date BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2' AND bkg_active = 1 AND bkg_status IN(2, 3, 5, 6, 7) AND route.rut_active = 1 AND(
							bkg_from_city_id > 0 OR bkg_to_city_id > 0
						)
					GROUP BY
						bkg_from_city_id,
						bkg_to_city_id
						) tempfinal
					LEFT JOIN `cities` AS c1
					ON
						c1.cty_id = tempfinal.fromCity
					LEFT JOIN `cities` AS c2
					ON
						c2.cty_id = tempfinal.toCity
					WHERE
						1 AND(
							tempfinal.fromCity > 0 AND tempfinal.toCity > 0
						)
					GROUP BY
						tempfinal.fromCity,
						tempfinal.toCity
					) a";
		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['totalInquiry', 'B2CInquiry', 'B2BInquiry', 'totalConfirm', 'B2CConfirm', 'B2BConfirm', 'distance'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	public static function getTopRouteByRegions($type, $arrRegionLimit = [])
	{
		$key	 = "topRouteByRegions_" . $type;
		if (count($arrRegionLimit) > 0)
		{
			$strRegionKey = md5(serialize($arrRegionLimit));
			$key	 = "topRouteByRegions_" . $type . "_" . $strRegionKey;
		}
		
		$arrData	 = Yii::app()->cache->get($key);
		if ($arrData)
		{
			goto result;
		}
		
		if (count($arrRegionLimit) <= 0)
		{
			$arrRegionLimit = [1 => 5, 2 => 4, 3 => 2, 4 => 4, 5 => 2, 6 => 2, 7 => 2];
		}
	
		$arrData = [];
		foreach ($arrRegionLimit as $region => $limit)
		{
			$sql = "SELECT trc.*, fromCity.cty_name fromCityName, toCity.cty_name toCityName, fromCity.cty_full_name fromCityFullName, 
					toCity.cty_full_name toCityFullName, rut.rut_name, fromCity.cty_alias_path fromCityAliasPath   
					FROM top_route_cities trc 
					INNER JOIN cities fromCity ON trc.trc_from_city_id=fromCity.cty_id AND fromCity.cty_active = 1 AND fromCity.cty_service_active = 1 
					LEFT JOIN cities toCity ON trc.trc_to_city_id=toCity.cty_id AND toCity.cty_active = 1 AND toCity.cty_service_active = 1 
					LEFT JOIN route rut ON rut.rut_from_city_id = fromCity.cty_id AND rut.rut_to_city_id = toCity.cty_id AND rut.rut_active = 1 
					WHERE trc_type =:type AND trc_region =:region 
					ORDER BY trc_total_served DESC 
					LIMIT 0, {$limit}";
			$result = DBUtil::queryAll($sql, DBUtil::SDB(), ['type'=> $type, 'region' => $region]);
			if($result && count($result) > 0)
			{
				$arrData = array_merge($arrData, $result);
			}
		}
		
		Yii::app()->cache->set($key, $arrData, (60 * 60 * 24 * 15), new CacheDependency(CacheDependency::Type_Routes));

		result:
		return $arrData;
	}
	
	public static function getTopRoutesForCity($type, $arrCities = [])
	{
		$arrData = [];
		foreach ($arrCities as $cityId => $limit)
		{
			$key	 = "topRoutesForCity_{$cityId}_{$limit}";
			$result	 = Yii::app()->cache->get($key);
			if ($result)
			{
				$arrData = array_merge($arrData, $result);
				continue;
			}

			$sql = "SELECT trc.*, fromCity.cty_name as fromCityName, fromCity.cty_full_name fromCityFullName, toCity.cty_name as toCityName, 
					toCity.cty_full_name toCityFullName, rut.rut_name, fromCity.cty_alias_path fromCityAliasPath 
					FROM top_route_cities trc 
					INNER JOIN cities fromCity ON trc.trc_from_city_id=fromCity.cty_id AND fromCity.cty_active = 1 AND fromCity.cty_service_active = 1 
					LEFT JOIN cities toCity ON trc.trc_to_city_id=toCity.cty_id AND toCity.cty_active = 1 AND toCity.cty_service_active = 1 
					LEFT JOIN route rut ON rut.rut_from_city_id = fromCity.cty_id AND rut.rut_to_city_id = toCity.cty_id AND rut.rut_active = 1 
					WHERE trc_type =:type AND trc_from_city_id =:cityId 
					ORDER BY trc_total_served DESC 
					LIMIT 0, {$limit}";

			$result = DBUtil::queryAll($sql, DBUtil::SDB(), ['type' => $type, 'cityId' => $cityId]);
			if ($result && count($result) > 0)
			{
				$arrData = array_merge($arrData, $result);

				Yii::app()->cache->set($key, $result, (60 * 60 * 24 * 15), new CacheDependency(CacheDependency::Type_Routes));
			}
		}

		return $arrData;
	}

	public static function getTopRouteByType($type, $arrCities = [], $arrRegionLimit = [])
	{
		$arrData = [];
		if($arrCities && count($arrCities) > 0)
		{
			$arrData = self::getTopRoutesForCity($type, $arrCities);
		}
		else
		{
			$arrData = self::getTopRouteByRegions($type, $arrRegionLimit);
		}
		
		return $arrData;
	}
	
	public static function getCitiesForUrl()
	{
		$arrFCityData = [];
		$fRouteName = trim(Yii::app()->request->getParam('route'));
		$fCityName = trim(Yii::app()->request->getParam('city'));
		if($fRouteName != '')
		{
			$objRouteM = Route::model()->getByName($fRouteName);
			$arrFCityData[$objRouteM->rut_from_city_id] = 10;
			$arrFCityData[$objRouteM->rut_to_city_id] = 10;
		}
		elseif($fCityName != '')
		{
			$objCityM = Cities::model()->getByCity2($fCityName);
			$arrFCityData[$objCityM->cty_id] = 20;
		}
		
		return $arrFCityData;
	}
    
    
    
 
}
