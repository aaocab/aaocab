<?php

/**
 * This is the model class for table "quotation".
 *
 * The followings are the available columns in table 'quotation':
 * @property integer $qot_id
 * @property string $qot_code
 * @property integer $qot_user_id
 * @property integer $qot_user_type
 * @property integer $qot_trip_type
 * @property integer $qot_car_type
 * @property string $qot_special_needs
 * @property string $qot_start_date
 * @property string $qot_start_time
 * @property string $qot_end_date
 * @property string $qot_name
 * @property string $qot_email
 * @property string $qot_phone
 * @property integer $qot_passenger
 * @property integer $qot_luggage
 * @property string $qot_pickup_point
 * @property string $qot_drop_point
 * @property integer $qot_pickup_city
 * @property integer $qot_drop_city
 * @property string $qot_pickup_pincode
 * @property float $qot_pickup_lat
 * @property float $qot_pickup_long
 * @property float $qot_drop_lat
 * @property float $qot_drop_long
 * @property float $qot_amount
 * @property float $qot_service_tax
 * @property string $qot_create_date
 * @property integer $qot_status
 * The followings are the available model relations:
 * @property Users $qotUser
 */
class Quotation extends CActiveRecord
{

	public $specialNeeds = [
		'1'	 => 'Need carrier',
		'2'	 => 'Female only passengers',
		'3'	 => 'Elderly passengers',
		'4'	 => 'Other requests'
	];
	public $tripList	 = [
		'1'	 => 'One Way',
		'2'	 => 'Round Trip',
		'3'	 => 'Multi-City Trip',
		'4'	 => 'Transfer Trip'
	];
	public $catypeArr	 = [1, 3, 2, 5, 6, 7, 8, 9];
	public $qot_email_txt, $qot_start_time;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('qot_car_type,qot_name, qot_email, qot_phone, qot_pickup_city, qot_drop_city', 'required', 'on' => 'insert'),
			array('qot_pickup_point, qot_drop_point', 'required', 'on' => 'insert2'),
			array('qot_pickup_city,qot_drop_city,qot_start_date', 'required', 'on' => 'insertRoute'),
			array('qot_email_txt', 'required', 'on' => 'insert3'),
			array('qot_phone', 'numerical', 'integerOnly' => true, 'on' => 'insert'),
			array('qot_email', 'email', 'message' => 'Please enter valid email address', 'on' => 'insert'),
			array('qot_email_txt', 'email', 'message' => 'Please enter valid email address', 'on' => 'insert2'),
			array('qot_user_id, qot_trip_type, qot_passenger, qot_luggage, qot_pickup_city, qot_drop_city, qot_status', 'numerical', 'integerOnly' => true),
			array('qot_code, qot_pickup_pincode, qot_drop_pincode', 'length', 'max' => 50),
			array('qot_name, qot_email, qot_pickup_point, qot_drop_point', 'length', 'max' => 100),
			array('qot_phone', 'length', 'max' => 20),
			array('qot_start_date, qot_end_date, qot_create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('qot_id, qot_code, qot_user_id, qot_user_type, qot_trip_type, qot_start_date,qot_start_time, qot_end_date, qot_name, qot_email, qot_phone, qot_passenger, qot_luggage, qot_pickup_point, qot_drop_point, qot_pickup_city, qot_drop_city, qot_pickup_pincode, qot_drop_pincode, qot_pickup_lat, qot_pickup_long, qot_drop_lat, qot_drop_long, qot_amount, qot_service_tax, qot_create_date, qot_status', 'safe', 'on' => 'search'),
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
			'qotUser' => array(self::BELONGS_TO, 'Users', 'qot_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'qot_id'			 => 'Qot',
			'qot_code'			 => 'Code',
			'qot_user_id'		 => 'Qot User',
			'qot_car_type'		 => 'Cab Type',
			'qot_trip_type'		 => 'Trip Type',
			'qot_start_date'	 => 'Start Date',
			'qot_end_date'		 => 'End Date',
			'qot_name'			 => 'Customer Name',
			'qot_email'			 => 'Customer Email',
			'qot_phone'			 => 'Customer Phone',
			'qot_passenger'		 => 'Passenger',
			'qot_luggage'		 => 'Luggage',
			'qot_pickup_point'	 => 'Pickup Point',
			'qot_drop_point'	 => 'Drop Point',
			'qot_pickup_city'	 => 'Pickup City',
			'qot_drop_city'		 => 'Drop City',
			'qot_pickup_pincode' => 'Pickup Pincode',
			'qot_drop_pincode'	 => 'Drop Pincode',
			'qot_create_date'	 => 'Create Date',
			'qot_status'		 => 'Status',
			'qot_email_txt'		 => 'Email Address',
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

		$criteria->compare('qot_id', $this->qot_id);
		$criteria->compare('qot_code', $this->qot_code, true);
		$criteria->compare('qot_user_id', $this->qot_user_id);
		$criteria->compare('qot_trip_type', $this->qot_trip_type);
		$criteria->compare('qot_start_date', $this->qot_start_date, true);
		$criteria->compare('qot_end_date', $this->qot_end_date, true);
		$criteria->compare('qot_name', $this->qot_name, true);
		$criteria->compare('qot_email', $this->qot_email, true);
		$criteria->compare('qot_phone', $this->qot_phone, true);
		$criteria->compare('qot_passenger', $this->qot_passenger);
		$criteria->compare('qot_luggage', $this->qot_luggage);
		$criteria->compare('qot_pickup_point', $this->qot_pickup_point, true);
		$criteria->compare('qot_drop_point', $this->qot_drop_point, true);
		$criteria->compare('qot_pickup_city', $this->qot_pickup_city);
		$criteria->compare('qot_drop_city', $this->qot_drop_city);
		$criteria->compare('qot_pickup_pincode', $this->qot_pickup_pincode, true);
		$criteria->compare('qot_drop_pincode', $this->qot_drop_pincode, true);
		$criteria->compare('qot_create_date', $this->qot_create_date, true);
		$criteria->compare('qot_status', $this->qot_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Quotation the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tripList()
	{
		$tripList = [
			1	 => strtoupper('One Way'),
			2	 => strtoupper('Round Trip'),
			3	 => strtoupper('Multi-City Trip'),
			4	 => strtoupper('Transfer Trip'),
		];
		asort($tripList);
		return $tripList;
	}

	//google Api implemented to get distace matrix
	public function getDbDistance($arrPickRoutes, $addtnlPickup = false, $addtnlDrop = false)
	{
		$route		 = [];
		$rutModels	 = [];
		$result		 = [];
		for ($i = 0; $i < count($arrPickRoutes) - 1; $i++)
		{
			$pcity		 = $arrPickRoutes[$i];
			$dcity		 = $arrPickRoutes[$i + 1];
			$rutModel	 = Route::model()->getbyCities($pcity, $dcity);
			if (!$rutModel)
			{
				$result1 = Route::model()->populate($pcity, $dcity);
				if ($result1['success'])
				{
					$rutModel = $result1['model'];
				}
				else
				{
					$result		 = $result1;
					$route[$i]	 = $result1;
					continue;
				}
			}
			$distance			 = $distance + $rutModel->rut_estm_distance;
			$duration			 = $duration + $rutModel->rut_estm_time;
			$route[$i]['dist']	 = $rutModel->rut_estm_distance;
			$route[$i]['time']	 = $rutModel->rut_estm_time;
			$rutModels[]		 = $rutModel;
		}
		$tripRoute	 = $route;
		$addtnlKms	 = 0;
		$addtnlTime	 = 0;
		if ($addtnlPickup)
		{
			array_shift($tripRoute);
			$addtnlKms	 += $rutModels[0]->rut_estm_distance;
			$addtnlTime	 += $rutModels[0]->rut_estm_time;
		}
		if ($addtnlDrop)
		{
			array_pop($tripRoute);
			$addtnlKms	 += $rutModels[count($rutModels) - 1]->rut_estm_distance;
			$addtnlTime	 += $rutModels[count($rutModels) - 1]->rut_estm_time;
		}
		$totalDistTime	 = ['time' => $duration, 'dist' => $distance, 'tripRoute' => $tripRoute, 'routeData' => $route, 'addtnl' => ['kms' => $addtnlKms, 'time' => $addtnlTime]];
		$result			 = $result + $totalDistTime;
		return $result;
	}

	public function getDistance($arrPickRoutes, $addtnlPickup = false, $addtnlDrop = false)
	{
		$result = GoogleMapAPI::getInstance()->getDistance($arrPickRoutes);
		if ($result['success'])
		{
			$p		 = $result['distance'];
			$route	 = [];
			for ($i = 0; $i < count($p); $i++)
			{
				$distance			 = $distance + $p[$i]['dist'];
				$duration			 = $duration + $p[$i]['time'];
				$route[$i]['dist']	 = $p[$i]['dist'];
				$route[$i]['time']	 = $p[$i]['time'];
			}
			$tripRoute	 = $route;
			$addtnlKms	 = 0;
			$addtnlTime	 = 0;
			if ($addtnlPickup)
			{
				array_shift($tripRoute);
				$addtnlKms	 += $p[0]['dist'];
				$addtnlTime	 += $p[0]['time'];
			}
			if ($addtnlDrop)
			{
				array_pop($tripRoute);
				$addtnlKms	 += $p[count($p) - 1]['dist'];
				$addtnlTime	 += $p[count($p) - 1]['time'];
			}
			$totalDistTime	 = ['time' => $duration, 'dist' => $distance, 'tripRoute' => $tripRoute, 'routeData' => $route, 'addtnl' => ['kms' => $addtnlKms, 'time' => $addtnlTime]];
			$result			 = $result + $totalDistTime;
		}
		return $result;
	}

	//get lat long search using last drop location
	public function getGarageCity($pickupCity, $dropCity, $cabType = 0, $tripType = 2)
	{
		$result = $this->getNearestRoute($pickupCity, $dropCity, $cabType);
		if ($result)
		{
			return [
				0	 => ['id' => $result["c2id"], "address" => $result["c2address"]],
				1	 => ['id' => $result["c4id"], "address" => $result["c4address"]],
			];
		}
		if ($pickupCity != '' || $pickupCity != null)
		{
			$arrPickup				 = $this->nearestRouteCity($pickupCity);
			$arrPickup['address']	 = $result['address'];
			$pickupcityid			 = $arrPickup['id'];
			if ($dropCity != '' || $dropCity != null)
			{
				$arrDrop			 = $this->nearestRouteCity($dropCity, $pickupcityid, $cabType, $tripType);
				$arrDrop['address']	 = $result['address'];
			}
		}

		return [0 => $arrPickup, 1 => $arrDrop];
	}

	public function getNearestRoute($pickupCity, $dropCity, $cabType = '')
	{

		$arr = Yii::app()->cache->get("$pickupCity-$dropCity");
		if (!$arr || !$arr[$cabType])
		{
			if (!$arr)
			{
				$arr = [];
			}
			if ($pickupCity == $dropCity)
			{
				$sql	 = "
                SELECT cities.cty_id as c2id, cities.cty_name as c2name, cities.cty_garage_address as c2address,
                    cities.cty_id as c4id, cities.cty_name as c4name, cities.cty_garage_address as c4address,
                        (distance * 2) as totalExtraDistance
                 FROM (SELECT route.rut_to_city_id as city, route.rut_estm_distance as distance FROM cities
                     INNER JOIN route ON cities.cty_id=route.rut_from_city_id AND rut_active=1 AND cities.cty_id=$pickupCity AND route.rut_estm_distance<40
                     INNER JOIN route r1 ON route.rut_to_city_id=r1.rut_from_city_id AND r1.rut_active=1
                     INNER JOIN rate ON r1.rut_id=rate.rte_route_id AND rate.rte_status=1 $condition GROUP BY route.rut_id HAVING count(*) >30
                   UNION
                     SELECT route.rut_to_city_id as city, route.rut_estm_distance as distance FROM cities
                     INNER JOIN route ON cities.cty_id=route.rut_from_city_id AND rut_active=1 AND cities.cty_id=$pickupCity AND route.rut_estm_distance<600
					 INNER JOIN contact ON contact.ctt_city=route.rut_to_city_id 
                     INNER JOIN vendors ON vendors.vnd_contact_id=contact.ctt_id AND vendors.vnd_active=1 GROUP BY route.rut_to_city_id HAVING count(*)>=2
                   UNION
                     SELECT ctt_city as city, 0 as distance FROM vendors 
					 INNER JOIN contact ON vendors.vnd_contact_id = contact.ctt_id
					 WHERE vnd_active=1 and ctt_city=$dropCity GROUP BY ctt_city HAVING COUNT(*) > 1) a
                     INNER JOIN cities ON cities.cty_id=a.city
                     ORDER BY totalExtraDistance LIMIT 1
                ";
				$data	 = DBUtil::queryRow($sql);
			}
			else
			{
				$sql	 = "SELECT fc.cty_id as c2id, fc.cty_name as c2name, fc.cty_garage_address as c2address,
                           tc.cty_id as c4id, tc.cty_name as c4name, tc.cty_garage_address as c4address, 0 as totalExtraDistance FROM route
                    INNER JOIN cities fc ON rut_from_city_id=fc.cty_id
                    INNER JOIN cities tc ON rut_from_city_id=tc.cty_id
                    WHERE route.rut_from_city_id=$pickupCity AND route.rut_to_city_id=$dropCity AND route.rut_active=1 AND route.rut_id IN
                        (SELECT rate.rte_route_id FROM rate WHERE rate.rte_status=1 $condition)";
				$data	 = DBUtil::queryRow($sql);

				if (!$data)
				{
					$sql	 = "
                SELECT fc.cty_id as c2id, fc.cty_name as c2name, fc.cty_garage_address as c2address,
                              tc.cty_id as c4id, tc.cty_name as c4name, tc.cty_garage_address as c4address, totalExtraDistance
                FROM (
                        SELECT r3.rut_id as new_route_id, r3.rut_name as new_route_name, r3.rut_from_city_id,  r3.rut_to_city_id, route.rut_name , route.rut_id,
                                    (route.rut_estm_distance + r2.rut_estm_distance - r3.rut_estm_distance) as totalExtraDistance, r3.rut_estm_distance
                          FROM route
                          INNER JOIN route r2 ON route.rut_to_city_id = r2.rut_from_city_id AND route.rut_from_city_id=$pickupCity
                              AND route.rut_to_city_id=$dropCity AND r2.rut_active=1 AND r2.rut_estm_distance<=60
                          INNER JOIN route r3 ON ((r3.rut_from_city_id=route.rut_from_city_id AND r2.rut_to_city_id=r3.rut_to_city_id))
                              AND r3.rut_active=1 AND r3.rut_id IN (SELECT rate.rte_route_id FROM rate WHERE rate.rte_status=1 $condition)
                        UNION
                        SELECT r3.rut_id, r3.rut_name, r3.rut_from_city_id, r3.rut_to_city_id, route.rut_name , route.rut_id,
                                    (route.rut_estm_distance + r1.rut_estm_distance - r3.rut_estm_distance) as totalExtraDistance, r3.rut_estm_distance
                          FROM route
                          INNER JOIN route r1 ON route.rut_from_city_id = r1.rut_to_city_id AND route.rut_from_city_id=$pickupCity AND route.rut_to_city_id=$dropCity AND r1.rut_active=1 AND route.rut_active=1 AND r1.rut_estm_distance<=60
                          INNER JOIN route r3 ON ((r3.rut_from_city_id=r1.rut_from_city_id AND route.rut_to_city_id=r3.rut_to_city_id))
                              AND r3.rut_active=1 AND r3.rut_id IN (SELECT rate.rte_route_id FROM rate WHERE rate.rte_status=1 $condition)
                    ) a
                INNER JOIN cities fc ON a.rut_from_city_id=fc.cty_id
                INNER JOIN cities tc ON a.rut_from_city_id=tc.cty_id ORDER BY totalExtraDistance ASC LIMIT 1
                ";
					$data	 = DBUtil::queryRow($sql);
				}

				if (!$data)
				{
					$sql	 = "SELECT fc.cty_id as c2id, fc.cty_name as c2name, fc.cty_garage_address as c2address,
                              tc.cty_id as c4id, tc.cty_name as c4name, tc.cty_garage_address as c4address, totalExtraDistance
                        FROM (SELECT r3.rut_id as new_route_id, r3.rut_name as new_route_name, r3.rut_from_city_id, r3.rut_to_city_id, route.rut_name , route.rut_id,
                                    (route.rut_estm_distance + r1.rut_estm_distance + r2.rut_estm_distance - r3.rut_estm_distance) as totalExtraDistance, r3.rut_estm_distance
                                FROM route
                                    INNER JOIN route r1 ON route.rut_from_city_id = r1.rut_to_city_id AND route.rut_from_city_id=$pickupCity AND r1.rut_active=1 AND route.rut_active=1 AND r1.rut_estm_distance<=60
                                    INNER JOIN route r2 ON route.rut_to_city_id = r2.rut_from_city_id AND route.rut_to_city_id=$dropCity AND r2.rut_active=1 AND r2.rut_estm_distance<=60
                                    INNER JOIN route r3 ON ((r3.rut_from_city_id=r1.rut_from_city_id AND r2.rut_to_city_id=r3.rut_to_city_id)) AND r3.rut_active=1 AND r3.rut_id IN
                                    (SELECT rate.rte_route_id FROM rate WHERE rate.rte_status=1 $condition) LIMIT 1
                                )a
                        INNER JOIN cities fc ON a.rut_from_city_id=fc.cty_id
                        INNER JOIN cities tc ON a.rut_from_city_id=tc.cty_id ORDER BY totalExtraDistance ASC LIMIT 1
                        ";
					$data	 = DBUtil::queryRow($sql);
				}
				if (!$data)
				{
					$sql	 = "SELECT * FROM ((SELECT cty_id as c2id, cty_name as c2name, cty_garage_address as c2address,
                              cty_id as c4id, cty_name as c4name, cty_garage_address as c4address, ((route.rut_estm_distance+r1.rut_estm_distance) * 2 - r2.rut_estm_distance) as totalExtraDistance
                        FROM cities
                        INNER JOIN route ON cities.cty_id=route.rut_from_city_id AND rut_active=1 AND route.rut_to_city_id=$pickupCity
                		INNER JOIN route r1 ON r1.rut_to_city_id=route.rut_from_city_id AND r1.rut_from_city_id=$dropCity AND r1.rut_active=1
                		INNER JOIN route r2 ON r2.rut_from_city_id=route.rut_to_city_id AND r2.rut_to_city_id=r1.rut_from_city_id AND r2.rut_active=1
                        INNER JOIN contact ON contact.ctt_city=route.rut_from_city_id
                        INNER JOIN vendors ON vendors.vnd_contact_id=contact.ctt_id AND vendors.vnd_active=1
                        GROUP BY contact.ctt_city HAVING count(*)>=2 ORDER BY totalExtraDistance LIMIT 1)
                        UNION
                        (SELECT c2id, c2name, c2address,
                            cty_id as c4id, cty_name as c4name, cty_garage_address as c4address,
                            (ExtraDistance + r2.rut_estm_distance) as totalExtraDistance
                            FROM (SELECT cty_id as c2id, cty_name as c2name, cty_garage_address as c2address,
                                    rut_estm_distance as ExtraDistance
                                    FROM cities
                                    INNER JOIN route ON cities.cty_id=route.rut_from_city_id AND rut_active=1 AND route.rut_to_city_id=$pickupCity
                                    INNER JOIN contact ctt ON ctt.ctt_city = route.rut_from_city_id
                                    INNER JOIN vendors ON vendors.vnd_contact_id=ctt.ctt_id AND vendors.vnd_active=1
                                    GROUP BY route.rut_from_city_id HAVING count(*)>=2 ORDER BY ExtraDistance LIMIT 3) a
                        INNER JOIN route r1 ON r1.rut_from_city_id=a.c2id AND r1.rut_active=1 AND rut_id IN (SELECT rte_route_id FROM rate WHERE rte_status=1 $condition) AND r1.rut_estm_distance<250
                        INNER JOIN route r2 ON r2.rut_to_city_id=r1.rut_to_city_id AND r2.rut_from_city_id=$dropCity AND r2.rut_active=1
                        INNER JOIN cities ON r2.rut_to_city_id=cities.cty_id  ORDER BY totalExtraDistance LIMIT 1)) a ORDER BY totalExtraDistance LIMIT 1";
					$data	 = DBUtil::queryRow($sql);
				}
			}

			$arr[$cabType] = $data;
			Yii::app()->cache->set("$pickupCity-$dropCity", $arr, 2 * 24 * 60 * 60);
		}
		else
		{
			$data = $arr[$cabType];
		}
		return $data;
	}

	public function nearestRouteCity($cityId, $sourceCity = null, $cabType = 0, $tripType = null)
	{
		$cityDetails = Cities::model()->findByPk($cityId);
		$latitude	 = $cityDetails->cty_lat;
		$longitude	 = $cityDetails->cty_long;

		$condition = "";
		if ($tripType == 1)
		{
			$condition3 = " OR cty.cty_id=rut.rut_to_city_id";
		}
		if ($sourceCity != null)
		{
			$condition = "(rut.rut_to_city_id=$sourceCity OR rut.rut_from_city_id=$sourceCity)
			AND ";
		}
		if ($cabType > 0)
		{
			$svcIds		 = SvcClassVhcCat::model()->getParentCabWithClass($cabType);
			$strSvcIds	 = implode(',', $svcIds);
			$condition1	 .= " AND rte_vehicletype_id IN ($strSvcIds)";
		}

		$qry = "SELECT   cty.cty_id AS id, cty_garage_address, cty.cty_name AS name, CalcDistance(cty.cty_lat, cty.cty_long, $latitude,$longitude) AS distance
		FROM     route rut INNER JOIN cities cty ON (cty.cty_id = rut.rut_from_city_id $condition3) AND cty_active = 1
		WHERE  ( $condition  EXISTS ( SELECT rte_route_id FROM   rate  WHERE  rut.rut_id = rate.rte_route_id AND rte_status = 1 $condition1 ))
		AND CalcDistance(cty.cty_lat, cty.cty_long, $latitude,$longitude) IS NOT NULL
		ORDER BY distance LIMIT 0,1";

		$data = DBUtil::queryRow($qry);
		return $data;
	}

	public function calculateTax($amt)
	{
		$staxrate	 = Filter::getServiceTaxRate();
		$tax		 = round((($amt * $staxrate) / 100), 2);
		return $tax;
	}

	public function routeDistance(&$brtRoutes, $tripType, $cabType = null)
	{
		$distance		 = 0;
		$time			 = 0;
		$routeDesc		 = [];
		$arrToCity		 = [];
		$api			 = false;
		$airportTransfer = false;
		if ($tripType == 4)
		{
			$airportTransfer = true;
		}

		/* @var $brtRoutes[] BookingRoute */
		foreach ($brtRoutes as $brtRoute)
		{
			/* @var $brtRoute BookingRoute */
			$brtRoute->calculateDistance($api, $airportTransfer);
			$arrToCity[] = $brtRoute->brt_to_city_id;
			$distance	 += $brtRoute->brt_trip_distance;
			$time		 += $brtRoute->brt_trip_duration;
			$routeDesc[] = $brtRoute->brtFromCity->cty_name;
		}


		$tripDistance	 = $distance;
		$startRoute		 = $brtRoutes[0];
		$lastRoute		 = $brtRoutes[count($brtRoutes) - 1];
		$pickupCity		 = $startRoute->brt_from_city_id;
		$pickupAddress	 = $startRoute->brt_from_location;
		$startTripDate	 = $startRoute->brt_pickup_datetime;
		$endDate		 = new DateTime($lastRoute->brt_pickup_datetime);
		$endDate->add(new DateInterval('PT' . $lastRoute->brt_trip_duration . 'M'));
		$endTripDate	 = $endDate->format('Y-m-d H:i:s');
		$routeDesc[]	 = $lastRoute->brtToCity->cty_name;
		$returnDate		 = $lastRoute->brt_pickup_datetime;
		$zoneRows		 = ZoneCities::model()->getZoneByCities($arrToCity);
		if ($zoneRows == '')
		{
			$zoneRows = ZoneCities::model()->findZoneByCitiesState($arrToCity);
		}
		$arrToZones		 = explode(',', $zoneRows['zones']);
		$dropCity		 = $lastRoute->brt_to_city_id;
		$dropAddress	 = $lastRoute->brt_to_location;
		$result			 = $this->getGarageCity($pickupCity, $dropCity, $cabType, $tripType);
		$sourceCity		 = $result[0]['id'];
		$destinationCity = $result[1]['id'];
		$startDistance	 = 0;
		$servingRoute	 = ['start' => $pickupCity, 'end' => $dropCity];
		if ($sourceCity != $pickupCity || $pickupAddress != '')
		{
			$brtModel					 = new BookingRoute();
			$brtModel->brt_from_city_id	 = $sourceCity;
			$brtModel->brt_from_location = Cities::model()->findByPk($sourceCity)->cty_garage_address;
			$brtModel->brt_to_city_id	 = $pickupCity;
			$brtModel->brt_to_location	 = $pickupAddress;
			$brtModel->calculateDistance();
			$startDistance				 = $brtModel->brt_trip_distance;
			if ($startDistance < 100)
			{
				$servingRoute['start'] = $sourceCity;
			}
		}
		$endDistance	 = 0;
		$garageDistance	 = 0;
		if ($sourceCity != $dropCity || $dropAddress != '')
		{
			$brtModel					 = new BookingRoute();
			$brtModel->brt_from_city_id	 = $dropCity;
			$brtModel->brt_from_location = $dropAddress;
			$brtModel->brt_to_city_id	 = $sourceCity;
			$brtModel->brt_to_location	 = Cities::model()->findByPk($sourceCity)->cty_garage_address;
			$brtModel->calculateDistance();
			$endDistance				 = $brtModel->brt_trip_distance;
			$garageDistance				 = 0;

			if ($sourceCity == $dropCity)
			{
				$garageDistance = 0;
			}
			else if ($destinationCity == $sourceCity)
			{
				$garageDistance = $endDistance;
			}
			else
			{
				$brtModel					 = new BookingRoute();
				$brtModel->brt_from_city_id	 = $dropCity;
				$brtModel->brt_from_location = $dropAddress;
				$brtModel->brt_to_city_id	 = $destinationCity;
				$brtModel->brt_to_location	 = Cities::model()->findByPk($destinationCity)->cty_garage_address;
				$brtModel->calculateDistance();
				$garageDistance				 = $brtModel->brt_trip_distance;
				$garageDistance				 = min([$garageDistance, $endDistance]);
				if ($garageDistance < 100)
				{
					$servingRoute['end'] = $destinationCity;
				}
			}
		}

		//$zone = $startRoute->brtFromCity->ctyState->stt_zone;

		$zone = $startRoute->brtFromCity->getNearestZonePriceRuleId();
		if ($zone == '')
		{
			$zone = $startRoute->brtFromCity->ctyState->stt_zone;
		}
		if ($tripType == 4)
		{
			$cityId1		 = $startRoute->brt_from_city_id;
			$cityId2		 = $startRoute->brt_to_city_id;
			$rates			 = $this->airportRateConfig($cityId1, $cityId2);
			$quotedKM		 = $tripDistance;
			$startDistance	 = $endDistance	 = 0;
			$garageDistance	 = 0;
		}
		else
		{
			$rates = $this->rateConfig($zone);
//            $rates =  AreaPriceRule::model()->getData($startRoute->brt_from_city_id,$tripType,$cabType);
		}
		$totalRunning	 = $startDistance + $tripDistance + $endDistance;
		$totalGarage	 = $startDistance + $tripDistance + $garageDistance;
		$totalExtra		 = $startDistance + $garageDistance;
		$days			 = $this->getTravelDays($startTripDate, $endTripDate);
		$minimumKM		 = $rates['minimumKM'][$tripType];
		$markups		 = $rates['markup'];
		if ($tripType == 4)
		{
			$minimumTransferKM			 = $rates['minimumTransferKM'];
			$minimumChargeableDistance	 = ($minimumTransferKM > $tripDistance) ? $minimumTransferKM : $tripDistance;
		}
		else if ($tripType == 1)
		{
			$minimumChargeableDistance	 = ($minimumKM > $tripDistance) ? $minimumKM : $tripDistance;
			$quotedKM					 = $tripDistance;
		}
		else
		{
			$oneWayMethod				 = $rates['oneWayMethod'];
			$routeCount					 = (count($brtRoutes) - 1) * 0;
			$dayCount					 = $days['calendarDays'] * 0;
			$distanceExtraCount			 = ($dayCount > $routeCount) ? $dayCount : $routeCount;
			$minimumChargeableDistance	 = $tripDistance + $distanceExtraCount;
			$tripDays					 = $days['calendarDays'];
			$minKM						 = $rates['perDayMinimumKM'];
			//$totalExtra = ($oneWayMethod == 1) ? $totalExtra : 0;
			$dist						 = ($minKM * $tripDays);
			$quotedKM					 = max([$minimumChargeableDistance, max([$dist, $totalGarage]) - $totalExtra]);
			$minimumChargeableDistance	 = ($dist > $minimumChargeableDistance) ? $dist : $minimumChargeableDistance;
		}
		$distanceParam		 = [
			'toCities'					 => $arrToCity,
			'addtionalDistance'			 => ['start' => $startDistance, 'end' => $endDistance],
			'servingRoute'				 => $servingRoute,
			'tripDistance'				 => $tripDistance,
			'time'						 => $time,
			'routeDesc'					 => $routeDesc,
			'totalExtra'				 => $totalExtra,
			'totalRunning'				 => $totalRunning,
			'totalGarage'				 => $totalGarage,
			'minimumChargeableDistance'	 => $minimumChargeableDistance,
			'days'						 => $days,
			'rateConfig'				 => $rates,
			'quoted_km'					 => $quotedKM,
			'pickupCity'				 => $pickupCity,
			'dropCity'					 => $dropCity,
			'sourceCity'				 => $sourceCity,
			'destinationCity'			 => $destinationCity,
			'startTripDate'				 => $startTripDate,
			'endTripDate'				 => $endTripDate,
			'returnDate'				 => $returnDate
		];
		$cabTypes			 = Quote::getCabTypeArr();
		$excludedCabTypes	 = BookingSub::getexcludedCabTypes($pickupCity, $dropCity);
		$cabTypes			 = array_diff($cabTypes, $excludedCabTypes);
		if ($cabType != '')
		{
			$cabTypes = [$cabType];
		}
		$cabResult = [];
		foreach ($cabTypes as $cabType)
		{
			$cabResult[$cabType] = $this->getBestMethod($distanceParam, $cabType, $rates, $tripType);
		}
		return $distanceParam + ['cabResult' => $cabResult];
	}

	public function getBestMethod(&$distanceParam, $cabType, $rates, $tripType)
	{
		$days						 = $distanceParam["days"]['gozoDuration'];
		$baseFare					 = $rates['baseFare'][$cabType];
		$totalBaseFare				 = $baseFare * $days;
		$cabRate					 = $rates['carType'][$cabType];
		$startDistance				 = $distanceParam['addtionalDistance']['start'];
		$endDistance				 = $distanceParam['addtionalDistance']['end'];
		$totalRunning				 = $distanceParam['totalRunning'];
		$totalGarage				 = $distanceParam['totalGarage'];
		$minimumChargeableDistance	 = $distanceParam['minimumChargeableDistance'];

		$oneWayMethod	 = $rates['oneWayMethod'];
		$totalRunning	 = ($minimumChargeableDistance > $totalRunning) ? $minimumChargeableDistance : $totalRunning;
		$roundRate		 = $rate			 = $totalBaseFare + ($totalRunning * $cabRate);
		if ($tripType == 4)
		{
			$cabRate					 = $rates['carType'][$cabType];
			$minimumChargeableDistance	 = $rates['minimumTransferKM'];
			$minWaitinMin				 = $rates['waiting']['includedMinutes'];
			$waitingCharge				 = $rates['waiting']['chargesPer30Mins'];
			if ($baseFare == 0)
			{
				$totalBaseFare = ($minimumChargeableDistance * 2 * $cabRate) + (ceil($minWaitinMin / 30) * $waitingCharge);
			}
			else
			{
				$totalBaseFare = $baseFare;
			}
			$extradistance	 = $totalRunning - $minimumChargeableDistance;
			$rate			 = $totalBaseFare + ($extradistance * $cabRate);
			$distance		 = $totalRunning;
		}
		else if (in_array($tripType, [1, 3]))
		{
			$tripTypeRates	 = ($tripType == 1) ? $rates['oneWay'] : $rates['multiCity'];
			// $roundRate += (($distanceParam['totalRunning'] - $distanceParam['tripDistance']) * $cabRate);
			$totalGarage	 = ($minimumChargeableDistance < $totalGarage) ? $totalGarage : $minimumChargeableDistance;
			$end			 = max([($endDistance - 300), 0]);
			$distance		 = ($oneWayMethod == 1) ? ($minimumChargeableDistance + $end + $startDistance) : $totalGarage;
			$rate			 = $totalBaseFare + ( $distance * $tripTypeRates[$cabType]);
			if ($roundRate < $rate)
			{
				$rate = $roundRate;
			}
			else
			{
				//		$distance = $distance;
				$cabRate = $tripTypeRates[$cabType];
			}
		}
		else
		{
			$distance = $totalRunning;
		}

		return ['amount' => $rate, 'runningDistance' => $distance, 'cabRate' => $cabRate];
	}

	public function calculateDistance($tripType, $qotData, $cabType = 0)
	{
		// tripType [ 1: oneway,2:round,3:multiway ]$calDistance = 0;
		$fromDate		 = $toDate			 = $pickupCity		 = $dropCity		 = '';
		$error			 = 0;
		$calculateTime	 = '';
		$startTripCity	 = '';
		$pickupPincode	 = '';
		$dropPincode	 = '';
		$endTripCity	 = '';
		$endTripDate	 = '';
		$padress		 = [];
		$calDistance	 = 0;
		$pCity			 = [];

		if (count($qotData) > 0)
		{
			$ctr	 = 0;
			$cityArr = [];
			foreach ($qotData as $val)
			{
				$cityArr[] = $val->pickup_city;
				if (count($qotData) == ($ctr + 1))
				{
					$cityArr[] = $val->drop_city;
				}
				$ctr = ($ctr + 1);
			}
		}
		if (sizeof($cityArr))
		{
			$pcityDetail = Cities::model()->getDetailsArr($cityArr);
			foreach ($pcityDetail as $j => $ctyDetails)
			{
				$pGarage[$ctyDetails->cty_id]	 = $ctyDetails->cty_garage_address;
				$padress[$ctyDetails->cty_id]	 = $ctyDetails->cty_name . ', ' . $ctyDetails->ctyState->stt_name . ', India';
				$pCity[$ctyDetails->cty_id]		 = $ctyDetails->cty_name;
			}
		}
		$ctr			 = 0;
		$arrPickRoutes	 = [];
		$arrCities		 = [];
		foreach ($qotData as $k => $cityDetails)
		{
			$arrCities[]	 = $cityDetails->pickup_city;
//            if ($cityDetails->pickup_address == "") {
			$arrPickRoutes[] = $pGarage[$cityDetails->pickup_city];
//            } else {
//                $arrPickRoutes[] = $cityDetails->pickup_address . ', ' . $padress[$cityDetails->pickup_city];
//            }

			if ($ctr == 0)
			{
				$prefix			 = ($cityDetails->pickup_address != '') ? $cityDetails->pickup_address . ', ' : "";
				$pickupCity		 = $pGarage[$cityDetails->pickup_city];
				$pickupCityId	 = $cityDetails->pickup_city;
			}

			$ctr++;
		}
		$dropCityId = $cityDetails->drop_city;

		$prefix			 = ($cityDetails->drop_address != '') ? $cityDetails->drop_address . ', ' : "";
		$dropCity		 = $pGarage[$dropCityId];
		$arrPickRoutes[] = $dropCity;
		$arrCities[]	 = $dropCityId;
		$distanceLatLong = $this->getGarageCity($pickupCityId, $dropCityId, $cabType, $tripType);
		$pickArr		 = $distanceLatLong[0];
		if ($pickArr == false)
		{
			$error = 3;
		}
		else
		{
			//		$arrPickRoutes[0]=$pickArr['address'];
		}
		$pickAddress = $pickArr['cty_garage_address'];
		$dropArr	 = $distanceLatLong[1];
		if ($dropArr == false)
		{
			$error = 3;
		}
		else
		{
//		        $arrPickRoutes[count($arrPickRoutes)-1]=$dropArr['address'];
		}
		$serveRoute = [];
		if ($error <> 3)
		{
			$serveRoute = ['pickup' => $pickArr['id'], 'drop' => $dropArr['id']];
		}
		$dropAddress	 = $dropArr['cty_garage_address'];
		$addtnlPickup	 = false;
		$addtnlDrop		 = false;
		if ($dropAddress != $arrPickRoutes[count($arrPickRoutes) - 1])
		{
			$addtnlDrop = true;
			array_push($arrCities, $dropArr['id']);
			array_push($arrPickRoutes, $dropAddress);
		}
		if ($pickAddress != $arrPickRoutes[0])
		{
			$addtnlPickup = true;
			array_unshift($arrCities, $pickArr['id']);
			array_unshift($arrPickRoutes, $pickAddress);
		}
		$distance = $this->getDbDistance($arrCities, $addtnlPickup, $addtnlDrop);
		//	$distance = $this->getDistance($arrPickRoutes, $addtnlPickup, $addtnlDrop);
		if (!$distance['success'])
		{
			$error = $distance['errorCode'];
		}

		$ratesList = Zones::model()->getCabRateByCityId($pickArr['id']);

		if (count($qotData) > 0)
		{
			$qot		 = $qotData[0];
			$qotEnd		 = $qotData[count($qotData) - 1];
			$routes[]	 = Cities::getName($qot->pickup_city);
			foreach ($qotData as $q)
			{
				$routes[]		 = Cities::getName($q->drop_city);
				$lastTripDate	 = $q->date;
			}
			$rateModels = [];
			if ($tripType == 1)
			{
				$requestDetails = array
					(
					"fromCityId" => $qot->pickup_city,
					"toCityId"	 => $qot->drop_city
				);

				$rateModels = Rate::model()->fetchCabDetailsByCites($requestDetails);
			}

			$routeStatus = 0;
			$lastRoute	 = $distance['tripRoute'][count($distance['tripRoute']) - 1];
			$totalTime	 = $distance["time"];
			if (count($rateModels) > 0)
			{
				$routeModel		 = $rateModels;
				$estmDistance	 = (float) $routeModel["rut_estm_distance"];
				if ($estmDistance > $distance['dist'])
				{
					$calculateTime = $calculateTime + $routeModel["rut_estm_time"];
				}
				else
				{
					$extraDistance	 = round(($distance['dist'] - $estmDistance));
					$estmDistance	 = round($distance['dist']);
					$calculateTime	 = $calculateTime + $distance['time'] - $distance['addtnl']['time'];
				}
				$totalTime	 = $calculateTime;
				$calDistance = round($calDistance + $estmDistance);
				$lastRoute	 = ["dist" => $calDistance, "time" => $calculateTime];
				$routeStatus = 1;
			}
			else
			{
				$calDistance	 = round($distance['dist']);
				$calculateTime	 = $calculateTime + $distance['time'] - $distance['addtnl']['time'];
			}
			$fromDate		 = $qot->date;
			$toDate			 = $qotEnd->date;
			$pickupCity		 = $qot->pickup_city;
			$dropCity		 = $qotEnd->drop_city;
			$pickupAddress	 = $qot->pickup_address;
			$dropAddress	 = $qotEnd->drop_address;
			$pickupPincode	 = $qot->pickup_pincode;
			$dropPincode	 = $qotEnd->drop_pincode;
			$startTripCity	 = $qot->pickup_city;
			$endTripCity	 = $qotEnd->drop_city;
			$startTripDate	 = $qot->date;

			$endTripDate	 = date('Y-m-d H:i:s', strtotime($lastTripDate . '+' . $lastRoute['time'] . ' minute'));
			$minEndTripDate	 = date('Y-m-d H:i:s', strtotime($startTripDate . '+' . $totalTime . ' minute'));
			$garageTime		 = ((new DateTime($endTripDate)) > (new DateTime($minEndTripDate))) ? $endTripDate : $minEndTripDate;
		}
		if ($startTripDate != '' && $endTripDate != '')
		{
			$days = Quotation::model()->getTravelDays($startTripDate, $endTripDate);

			$routeCount			 = (count($routes) - 1) * 20;
			$dayCount			 = $days['calendarDays'] * 20;
			$distanceExtraCount	 = ($dayCount > $routeCount) ? $dayCount : $routeCount;
			if ($tripType == 1)
			{
				$distanceExtraCount	 = 0;
				$chargeableDistance	 = $calDistance;
			}
			else
			{
				$distanceWithAdditional	 = $calDistance + $distanceExtraCount;
				$distanceByTime			 = Yii::app()->params['perDayMinDistance'] * $days['calendarDays'];
				$chargeableDistance		 = ($distanceByTime > $distanceWithAdditional) ? $distanceByTime : $calDistance;
			}
		}
		$calDistanceData = ['fromDate'			 => $fromDate,
			'travelDays'		 => $days,
			'totalExtraDist'	 => $distance['addtnl']['kms'],
			'totalExtraTime'	 => $distance['addtnl']['time'],
			'toDate'			 => $toDate,
			'serveRoute'		 => $serveRoute,
			'distanceExtraCount' => $distanceExtraCount,
			'pickupCityId'		 => $pickupCityId,
			'pickupCity'		 => $pickupCity,
			'dropCity'			 => $dropCity,
			'addtional'			 => $distance['addtnl'],
			'pickup_address'	 => $pickupAddress,
			'drop_address'		 => $dropAddress,
			'calDistance'		 => $calDistance,
			'calculateTime'		 => $calculateTime,
			'chargeableDistance' => $chargeableDistance,
			'garageTime'		 => $garageTime,
			'ratesList'			 => $ratesList,
			'startTripCity'		 => $startTripCity,
			'endTripCity'		 => $endTripCity,
			'startTripDate'		 => $startTripDate,
			'routes'			 => $routes,
			'routeData'			 => $distance['routeData'],
			'tripData'			 => $distance['tripData'],
			'error'				 => $error,
			'routeStatus'		 => $routeStatus,
			'pickupPincode'		 => $pickupPincode,
			'dropPincode'		 => $dropPincode,
			'endTripDate'		 => $endTripDate];
		return $calDistanceData;
	}

	public function getNightAllowDays($fromDate, $toDate)
	{
		$fromDate	 = DateTimeFormat::DatePickerToDate($fromDate);
		$toDate		 = DateTimeFormat::DatePickerToDate($toDate);
		$toDate		 = $toDate . ' 20:30:00';
		$toTime		 = date('H:i:s', $toDate);
		$interval	 = $datetime1->diff($datetime2);
		$interval	 = ($interval->format('%a') - 1);
		echo $fromDate . "-" . $toDate;
	}

	public function getTravelDays($fromDate, $toDate)
	{
		$night			 = 0;
		$fromData2		 = strtotime($fromDate);
		$toDate2		 = strtotime($toDate);
		$startTime		 = date('H', $fromData2);
		$endTime		 = date('H', $toDate2);
		$startDate		 = new DateTime(date('Y-m-d', $fromData2));
		$endDate		 = new DateTime(date('Y-m-d', $toDate2));
		$interval		 = $startDate->diff($endDate);
		$calendarDays	 = $interval->format('%a');
		$calendarDays++;
		$night			 += $calendarDays;
		if ($endTime <= 22)
		{
			$night--;
		}
		if ($startTime <= 5)
		{
			$night++;
		}

		$seconds = $toDate2 - $fromData2;
		$minutes = date(round($seconds / 60), strtotime(30));
		$minutes = $minutes - ($minutes % 15);
		$sec	 = $minutes * 60;
		$days	 = floor($sec / 86400);
		$hours	 = floor(($sec - ($days * 86400)) / 3600);
		$thours	 = floor($sec / 3600);
		$min	 = $minutes - ($thours * 60);
		$dur	 = '';
		if ($days > 0)
		{
			$dur .= $days . " days ";
		}
		if ($hours > 0)
		{
			$dur .= $hours . " hrs ";
		}
		if ($min > 0)
		{
			$dur .= $min . " mins";
		}

		return ['gozoDuration' => $calendarDays, 'calendarDays' => $calendarDays, 'totalNight' => $night, 'totalMin' => $minutes, 'actualDur' => $dur];
	}

	function roundToQuarterHour($timestring)
	{
		$minutes = date('i', strtotime($timestring));
		return $minutes - ($minutes % 15);
	}

	public function fetchRateByRouteCabtype($rutId, $carType)
	{
		$sql	 = "SELECT a.`rte_amount`,a.`rte_car_type`
                FROM `rate` a
                LEFT JOIN `route` b ON a.rte_route_id=b.rut_id
                WHERE 1=1 AND a.rte_car_type IN (" . $carType . ") AND a.rte_route_id=" . $rutId . "";
		$cabRate = DBUtil::queryAll($sql);
		return $cabRate;
	}

	public function fetchRateByCitiesCabType($fromCity, $toCity)
	{
		$sql	 = "SELECT a.`rte_amount`,a.`rte_car_type`
                FROM `rate` a LEFT JOIN `route` b ON a.rte_route_id=b.rut_id
                WHERE 1=1 AND b.rut_from_city_id=" . $fromCity . " AND b.rut_to_city_id=" . $toCity . "";
		$cabRate = DBUtil::queryAll($sql);
		return $cabRate;
	}

	public function getPriceByZone($cityZoneId)
	{
		switch ($cityZoneId)
		{
			case '1':
				$hike	 = 1.1;
				break;
			case '2':
				$hike	 = 1.5;
				break;
			case '3':
				$hike	 = 1.5;
				break;
			case '4':
				$hike	 = 1.5;
				break;
			case '5':
				$hike	 = 1.5;
				break;
			case '6':
				$hike	 = 1.5;
				break;
			default :
				$hike	 = 1.1;
				break;
		}
		return $hike;
	}

	public function airportRateConfig($airportId1, $airportId2)
	{
		$defMarkupCab		 = Yii::app()->params['defMarkupCab'];
		$defMarkupAssured	 = Yii::app()->params['defMarkupAssured'];
		$defaultRate		 = [
			'baseFare'			 => ['1' => 800, '2' => 1299, '3' => 999, '4' => 2200, '5' => 1150, '6' => 1400, '7' => 1999, '8' => 2200, '9' => 2400], //including 300 1hours waiting charge
			'carType'			 => ['1' => 14, '2' => 22, '3' => 16, '4' => 30, '5' => 17.5, '6' => 25, '7' => 26, '8' => 28, '9' => 30],
			'defPartnerMarkup'	 => [
				'1'	 => $defMarkupCab,
				'2'	 => $defMarkupCab,
				'3'	 => $defMarkupCab,
				'4'	 => $defMarkupCab,
				'5'	 => $defMarkupAssured,
				'6'	 => $defMarkupAssured
			],
			'waiting'			 => ['includedMinutes' => 60, 'chargesPer30Mins' => 150],
			'minimumTransferKM'	 => 25,
			'driverAllowance'	 => ['type' => '2', 'charges' => 250, 'kmLimit' => 0, 'NightTime' => ['start' => '22', 'end' => '5']],
		];
		$airportRates		 = [
			'32010'	 => [
				'baseFare'			 => ['1' => 1599, '2' => 2249, '3' => 1749, '4' => 3500, '5' => 2025, '6' => 2585, '7' => 3200, '8' => 3500, '9' => 3800], //including 300 1hours waiting charge
				'carType'			 => ['1' => 14, '2' => 22, '3' => 16, '4' => 30, '5' => 17.5, '6' => 25],
				'waiting'			 => ['includedMinutes' => 60, 'chargesPer30Mins' => 150],
				'minimumTransferKM'	 => 46,
			],
			'0'		 => [],
		];
		if (array_key_exists($airportId1, $airportRates))
		{
			$ratesConfig = array_replace_recursive($defaultRate, $airportRates[$airportId1]);
		}
		elseif (array_key_exists($airportId2, $airportRates))
		{
			$ratesConfig = array_replace_recursive($defaultRate, $airportRates[$airportId2]);
		}
		else
		{
			$ratesConfig = array_replace_recursive($defaultRate, []);
		}


		return $ratesConfig;
	}

	public function rateConfig($zone)
	{
		/**
		 * driverAllowance - type: 1=>'Per Trip', '2'=>'Per Night'
		 * driverAllowance - kmLimit: 0 => Any Limit, >0 => Charges / kmLimit
		 * driverAllowance - NightTime: start=>start time(in Night), end=>End Time(early Morning);
		 * oneWayMethod = 1=>Rates Calculated Based on Acutal KM, 2=> Rates Calculated based on Garage to Garage, 3=> Rates Calculated based on Source to Source
		 *  */
		$defaultRate = [
			'baseFare'			 => ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0],
			'carType'			 => ['1' => 8, '2' => 11.5, '3' => 8.85, '4' => 17.7, '5' => 10.62, '6' => 14.15, '7' => 14.5, '8' => 15.9, '9' => 18.5],
			'oneWay'			 => ['1' => 12.4, '2' => 17.7, '3' => 13.27, '4' => 26.55, '5' => 15.93, '6' => 21.24, '7' => 28.25, '8' => 31, '9' => 36.5],
			'multiCity'			 => ['1' => 12.4, '2' => 17.7, '3' => 13.27, '4' => 26.55, '5' => 15.93, '6' => 21.24, '7' => 28.25, '8' => 31, '9' => 36.5],
			'minimumKM'			 => ['1' => 150, '2' => 250, '3' => 250],
			'perDayMinimumKM'	 => 250,
			'perDayMaximumRide'	 => 600,
			'driverAllowance'	 => ['type' => '1', 'charges' => ['1' => 250, '2' => 250, '3' => 250, '4' => 250, '5' => 250, '6' => 250, '7' => 350, '8' => 350, '9' => 350], 'kmLimit' => 0],
			'oneWayMethod'		 => 1
		];

		$zonesRates	 = ['1'	 => [
				'carType'			 => ['1' => 7.52, '2' => 10.62, '3' => 7.96, '4' => 17.7, '5' => 9.3, '6' => 12.4, '7' => 14.25, '8' => 16, '9' => 18],
				'oneWay'			 => ['1' => 8.4, '2' => 12.4, '3' => 9.3, '4' => 26.55, '5' => 11.5, '6' => 15.5, '7' => 27.25, '8' => 31, '9' => 35],
				'multiCity'			 => ['1' => 8.4, '2' => 12.4, '3' => 9.3, '4' => 26.55, '5' => 11.5, '6' => 15.5, '7' => 27.25, '8' => 31, '9' => 35],
				'minimumKM'			 => ['1' => 200, '2' => 250, '3' => 250],
				'perDayMinimumKM'	 => 250,
				'perDayMaximumRide'	 => 600,
				'driverAllowance'	 => ['type' => '2', 'charges' => ['1' => 250, '2' => 250, '3' => 250, '4' => 250, '5' => 250, '6' => 250, '7' => 300, '8' => 300, '9' => 300], 'kmLimit' => 0, 'NightTime' => ['start' => '22', 'end' => '5']],
				'oneWayMethod'		 => 2
			],
			'4'	 => [],
			'2'	 => [],
			'3'	 => [],
			'5'	 => [
				'baseFare'			 => ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0],
				'carType'			 => ['1' => 12.4, '2' => 14.6, '3' => 12.8, '4' => 19.5, '5' => 14.6, '6' => 16.8, '7' => 18.98, '8' => 21.9, '9' => 26.28],
				'oneWay'			 => ['1' => 16.8, '2' => 20.35, '3' => 17.7, '4' => 28.3, '5' => 20.35, '6' => 24.75, '7' => 37.96, '8' => 43.8, '9' => 52.56],
				'multiCity'			 => ['1' => 16.8, '2' => 20.35, '3' => 17.7, '4' => 28.3, '5' => 20.35, '6' => 24.75, '7' => 37.96, '8' => 43.8, '9' => 52.56],
				'minimumKM'			 => ['1' => 200, '2' => 250, '3' => 250],
				'perDayMinimumKM'	 => 250,
				'perDayMaximumRide'	 => 600,
				'driverAllowance'	 => ['type' => '2', 'charges' => ['1' => 300, '2' => 300, '3' => 300, '4' => 300, '5' => 300, '6' => 300, '7' => 400, '8' => 400, '9' => 400], 'kmLimit' => 400],
				'oneWayMethod'		 => 2
			],
			'10' => [
				'baseFare'			 => ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0],
				'carType'			 => ['1' => 7.95, '2' => 11.5, '3' => 8.85, '4' => 19.5, '5' => 10.6, '6' => 14.15, '7' => 14.95, '8' => 17.25, '9' => 20.7],
				'oneWay'			 => ['1' => 15.5, '2' => 22.12, '3' => 17.25, '4' => 35.4, '5' => 19.5, '6' => 25.65, '7' => 29.9, '8' => 34.5, '9' => 41.4],
				'multiCity'			 => ['1' => 15.5, '2' => 22.12, '3' => 17.25, '4' => 35.4, '5' => 19.5, '6' => 25.65, '7' => 29.9, '8' => 34.5, '9' => 41.4],
				'minimumKM'			 => ['1' => 200, '2' => 250, '3' => 250],
				'perDayMinimumKM'	 => 250,
				'perDayMaximumRide'	 => 600,
				'driverAllowance'	 => ['type' => '2', 'charges' => ['1' => 300, '2' => 300, '3' => 300, '4' => 300, '5' => 300, '6' => 300, '7' => 400, '8' => 400, '9' => 400], 'kmLimit' => 400],
				'oneWayMethod'		 => 1
			],
			'8'	 => [
				'baseFare'			 => ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0],
				'carType'			 => ['1' => 7.95, '2' => 11.5, '3' => 8.85, '4' => 15.9, '5' => 10.20, '6' => 13.25, '7' => 14.95, '8' => 17.25, '9' => 20.7],
				'oneWay'			 => ['1' => 11.5, '2' => 16.8, '3' => 13.25, '4' => 24.75, '5' => 15, '6' => 19.5, '7' => 29.9, '8' => 34.5, '9' => 41.4],
				'multiCity'			 => ['1' => 11.5, '2' => 16.8, '3' => 13.25, '4' => 24.75, '5' => 15, '6' => 19.5, '7' => 29.9, '8' => 34.5, '9' => 41.4],
				'minimumKM'			 => ['1' => 200, '2' => 250, '3' => 250],
				'perDayMinimumKM'	 => 250,
				'perDayMaximumRide'	 => 600,
				'driverAllowance'	 => ['type' => '2', 'charges' => ['1' => 300, '2' => 300, '3' => 300, '4' => 300, '5' => 300, '6' => 300, '7' => 400, '8' => 400, '9' => 400], 'kmLimit' => 400],
				'oneWayMethod'		 => 2
			],
			'9'	 => [
				'minimumKM'			 => ['1' => 100, '2' => 300, '3' => 300],
				'perDayMinimumKM'	 => 300,
			],
			'14' => [
				'minimumKM'			 => ['1' => 100, '2' => 400, '3' => 400],
				'perDayMinimumKM'	 => 400,
			],
			'6'	 => [
				'baseFare'			 => ['1' => 975, '2' => 1600, '3' => 1250, '4' => 2125, '5' => 1500, '6' => 1900, '7' => 2080, '8' => 2400, '9' => 2880],
				'carType'			 => ['1' => 3.54, '2' => 7.1, '3' => 5.3, '4' => 7.95, '5' => 6.2, '6' => 7.95, '7' => 9.23, '8' => 10.65, '9' => 12.78],
				'oneWay'			 => ['1' => 6.2, '2' => 10.6, '3' => 7.95, '4' => 12.4, '5' => 9.3, '6' => 12.4, '7' => 18.46, '8' => 21.3, '9' => 25.56],
				'multiCity'			 => ['1' => 6.2, '2' => 10.6, '3' => 7.95, '4' => 12.4, '5' => 9.3, '6' => 12.4, '7' => 18.46, '8' => 21.3, '9' => 25.56],
				'minimumKM'			 => ['1' => 50, '2' => 50, '3' => 50],
				'perDayMinimumKM'	 => 100,
				'perDayMaximumRide'	 => 500,
				'driverAllowance'	 => ['type' => '1', 'charges' => ['1' => 500, '2' => 500, '3' => 500, '4' => 500, '5' => 500, '6' => 500, '7' => 500, '8' => 500, '9' => 500], 'kmLimit' => 0],
				'oneWayMethod'		 => 2
			],
			'7'	 => [
				'baseFare'			 => ['1' => 530, '2' => 485, '3' => 485, '4' => 885, '5' => 530, '6' => 530, '7' => 630.5, '8' => 727.5, '9' => 873],
				'carType'			 => ['1' => 9.75, '2' => 14.15, '3' => 11.5, '4' => 19.5, '5' => 14.15, '6' => 16.8, '7' => 18.39, '8' => 21.22, '9' => 25.47],
				'oneWay'			 => ['1' => 15.9, '2' => 20.35, '3' => 16.8, '4' => 29.2, '5' => 20.35, '6' => 24.75, '7' => 36.79, '8' => 42.45, '9' => 50.94],
				'multiCity'			 => ['1' => 15.9, '2' => 20.35, '3' => 16.8, '4' => 29.2, '5' => 20.35, '6' => 24.75, '7' => 36.79, '8' => 42.45, '9' => 50.94],
				'minimumKM'			 => ['1' => 100, '2' => 100, '3' => 100],
				'perDayMinimumKM'	 => 100,
				'perDayMaximumRide'	 => 500,
				'driverAllowance'	 => ['type' => '1', 'charges' => ['1' => 250, '2' => 250, '3' => 250, '4' => 250, '5' => 250, '6' => 250, '7' => 400, '8' => 400, '9' => 400], 'kmLimit' => 0],
				'oneWayMethod'		 => 1
			],
			'11' => [
				'carType'			 => ['1' => 11.9, '2' => 15.9, '3' => 13.7, '4' => 26.5, '5' => 15.9, '6' => 18.10, '7' => 20.67, '8' => 23.85, '9' => 28.62],
				'oneWay'			 => ['1' => 23, '2' => 30.9, '3' => 26.5, '4' => 48.5, '5' => 30.5, '6' => 35.25, '7' => 41.34, '8' => 47.7, '9' => 57.24],
				'multiCity'			 => ['1' => 23, '2' => 30.9, '3' => 26.5, '4' => 48.5, '5' => 30.5, '6' => 35.25, '7' => 41.34, '8' => 47.7, '9' => 57.24],
				'minimumKM'			 => ['1' => 80, '2' => 250, '3' => 250],
				'perDayMinimumKM'	 => 250,
				'perDayMaximumRide'	 => 500,
				'driverAllowance'	 => ['type' => '2', 'charges' => ['1' => 250, '2' => 250, '3' => 250, '4' => 250, '5' => 250, '6' => 250, '7' => 400, '8' => 400, '9' => 400], 'kmLimit' => 0, 'NightTime' => ['start' => '22', 'end' => '5']],
				'oneWayMethod'		 => 1
			],
			'12' => [
				'carType'	 => ['1' => 7.95, '2' => 11.2, '3' => 8.2, '4' => 17.7, '5' => 10.17, '6' => 13.70, '7' => 14.95, '8' => 17.25, '9' => 20.7],
				'oneWay'	 => ['1' => 11.95, '2' => 17.7, '3' => 12, '4' => 26.50, '5' => 14.6, '6' => 20.8, '7' => 29.9, '8' => 34.5, '9' => 41.4],
			],
			'13' => [
				'baseFare'			 => ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0],
				'carType'			 => ['1' => 11, '2' => 15.9, '3' => 11, '4' => 22.10, '5' => 12.80, '6' => 18.15, '7' => 20.67, '8' => 23.85, '9' => 28.62],
				'oneWay'			 => ['1' => 14.15, '2' => 22.10, '3' => 14.15, '4' => 35.35, '5' => 16.35, '6' => 25.6, '7' => 41.34, '8' => 47.7, '9' => 57.24],
				'multiCity'			 => ['1' => 14.15, '2' => 22.10, '3' => 14.15, '4' => 35.35, '5' => 16.35, '6' => 25.6, '7' => 41.34, '8' => 47.7, '9' => 57.24],
				'minimumKM'			 => ['1' => 200, '2' => 200, '3' => 200, '4' => 200, '5' => 200, '6' => 200],
				'perDayMinimumKM'	 => 200,
				'perDayMaximumRide'	 => 400,
				'driverAllowance'	 => ['type' => '1', 'charges' => ['1' => 250, '2' => 250, '3' => 250, '4' => 250, '5' => 250, '6' => 250, '7' => 400, '8' => 400, '9' => 400], 'kmLimit' => 600],
				'oneWayMethod'		 => 2
			],
		];
		$ratesConfig = array_replace_recursive($defaultRate, $zonesRates[$zone]);
		return $ratesConfig;
	}

	public function findZoneByCity($city_id)
	{
		$sql		 = "SELECT stt.stt_zone FROM cities cty JOIN states stt ON cty.cty_state_id=stt.stt_id"
				. " WHERE cty.cty_id= $city_id";
		$cityZone	 = DBUtil::queryRow($sql);
		return $cityZone;
	}

	/** @param BookingRoute[] $brtRoutes */
	public function getQuote(&$brtRoutes, $tripType, $partnerId = 1249, $cabType = '', $priceSurge = true, $includeNightAllowance = true)
	{
		$brtKey = json_encode($brtRoutes) . "-$priceSurge-$includeNightAllowance-$tripType";
		//     $data = Yii::app()->cache->get($brtKey);
		if (is_array($data) && isset($data['type']) && $cabType == '' && $data['type'] == 1)
		{
			return $data;
		}
		if (is_array($data) && $cabType != '' && isset($data[$cabType]))
		{
			return $data;
		}

		$result		 = $this->routeDistance($brtRoutes, $tripType, $cabType);
		$toCities	 = $result['toCities'];
		$rateConfig	 = $result['rateConfig'];
		$travelDays	 = $result['days'];

		$cabTypes			 = Quote::getCabTypeArr();
		$excludedCabTypes	 = BookingSub::getexcludedCabTypes($result['pickupCity'], $result['dropCity']);
		$cabTypes			 = array_diff($cabTypes, $excludedCabTypes);
		if ($cabType != '')
		{
			$cabTypes = [$cabType];
		}
		$servingRoute	 = $result['servingRoute'];
		$routes			 = [];
		$routes[]		 = ["fromCity" => $result["pickupCity"], "toCity" => $result["dropCity"]];
		$routes[]		 = ["fromCity" => $servingRoute['start'], "toCity" => $servingRoute['end']];
		$routeModel		 = Route::model()->getbyCities($servingRoute['start'], $servingRoute['end']);
		$driverCharge	 = 0;
		$nightAllowance	 = 0;
		$stax			 = Filter::getServiceTaxRate();
		$staxType		 = Filter::getServiceTaxType();
		$surgeAmount	 = 0;
		foreach ($cabTypes as $cab)
		{
			$ctr		 = $cab;
			$model		 = VehicleTypes::model()->getCarModel($cab, 1);
			$cabResult	 = $result['cabResult'][$cab];
			//  $markup = $rateConfig['markup'][$cab];
			$distance	 = $cabResult['runningDistance'];

			$nightAllow		 = $travelDays['totalNight'];
			$quotedDistance	 = $result['minimumChargeableDistance'];
			$calendarDays	 = $travelDays['calendarDays'];
			if ($rateConfig['driverAllowance']['type'] == 1)
			//if ($rateConfig['driverAllowance']['type'][$cab] == 1)
			{
				$kmlimit		 = $rateConfig['driverAllowance']['kmLimit'];
				$multiply1		 = ($kmlimit > 0) ? ceil($distance / $rateConfig['driverAllowance']['kmLimit']) : 1;
				$multiply		 = max([$calendarDays, $multiply1]);
				$nightAllowance	 = $rateConfig['driverAllowance']['charges'][$cab];
				$driverCharge	 = $nightAllowance * $multiply;
			}
			else
			{

				$nightAllowance = $rateConfig['driverAllowance']['charges'][$cab];
				if ($tripType == 2 && !$includeNightAllowance && $calendarDays > 0)
				{
					$nightAllow = $calendarDays - 1;
				}
				if (!$includeNightAllowance && $tripType == 1 && $nightAllow >= 1)
				{
					$nightAllow--;
				}
				$driverCharge = (int) ($nightAllow * $nightAllowance);
			}

			$baseAmount	 = round($cabResult['amount']);
			$amount		 = round($baseAmount);
			$estTax		 = round(Quotation::model()->calculateTax($amount));
			$ratevndamt	 = Yii::app()->params['rateVendorAmount'];
			if ($tripType == 2 && Yii::app()->params['rateRoundVendorAmount'])
			{
				$ratevndamt = Yii::app()->params['rateRoundVendorAmount'];
			}
			if ($tripType == 3 && Yii::app()->params['rateMultiVendorAmount'])
			{
				$ratevndamt = Yii::app()->params['rateMultiVendorAmount'];
			}
			if ($tripType == 4 && Yii::app()->params['airportRateVendorAmount'])
			{
				$ratevndamt = Yii::app()->params['airportRateVendorAmount'];
			}
			$rateVendorAmount				 = $amount;
			$totalAmount					 = $amount + $estTax + $driverCharge;
			$isTolltax						 = 0;
			$isStatetax						 = 0;
			$servicetax						 = 1;
			$driverAllowance				 = 1;
			$tollTax						 = 0;
			$stateTax						 = 0;
			$incr							 = 1;
			$extraDistance					 = 0;
			$extraCharge					 = 0;
			$garage							 = 0;
			//    $defPartnerMarkup		 = $rateConfig['defPartnerMarkup'][$cab];
			$actualAmount					 = $amount;
			$bkgModel						 = Booking::model();
			$bkgModel->to_cities			 = $toCities;
			$bkgModel->bkg_agent_id			 = $partnerId;
			$bkgModel->bkg_from_city_id		 = $result['pickupCity'];
			$bkgModel->bkg_to_city_id		 = $result['dropCity'];
			$bkgModel->bkg_vehicle_type_id	 = $cab;
			$bkgModel->bkg_pickup_date		 = $brtRoutes[0]->brt_pickup_datetime;
			$bkgModel->bkg_booking_type		 = $tripType;
			$defaultMarkup					 = $this->getCabDefaultMarkup($cab);
			if ($tripType == 1)
			{
				$rModel = Route::model()->getNearestMinRates($routes, $cab);
				if ($rModel)
				{
					$isTolltax		 = 1;
					$isStatetax		 = 1;
					$servicetax		 = 1;
					$driverAllowance = 1;

					$tollTax			 = $rModel['rte_toll_tax'];
					$stateTax			 = $rModel['rte_state_tax'];
					$cabRouteMinMarkup	 = $rModel['rte_minimum_markup'];
					$defaultMarkup		 = max([$cabRouteMinMarkup, $defaultMarkup]);
					if ($rateConfig['driverAllowance']['type'] == 1)
					//if ($rateConfig['driverAllowance']['type'][$cab] == 1)
					{
						$driverCharge = 0;
					}

					$fromDistance	 = max([$rModel['fromDistance'] - 15, 0]);
					$toDistance		 = max([$rModel['toDistance'] - 15, 0]);

					$garage = $rModel['rDistance'] + $fromDistance + $toDistance;

					$calculatedDistance		 = ($rateConfig['oneWayMethod'] == 1) ? $fromDistance + $result['tripDistance'] : $garage;
					$routeDistance			 = $rModel['rut_estm_distance'];
					$extraDistance1			 = $calculatedDistance - $routeDistance;
					$extraDistance			 = max([$extraDistance1, 0]);
					$cabResult['cabRate']	 = $rateConfig['oneWay'][$cab];
					$quotedDistance			 = $result['tripDistance'];
					$extraCharge			 = ($rateConfig['carType'][$cab] * ($extraDistance)); // + $nightCharge;

					$rutVndAmount = $rModel['rutvndamount'];

					$rateVendorAmountXtraCharge = round((100 - $ratevndamt) * $extraCharge / 100);
					if ($rutVndAmount > 0)
					{
						$rateVendorAmount = round($rutVndAmount + $extraCharge);
					}
					else
					{
						$rateVendorAmount = $amount + $tollTax + $stateTax;
					}
				}
				else
				{
					$extraDistance = $result['addtionalDistance']['start'] + $result['addtionalDistance']['end'];
					if ($result['addtionalDistance']['start'] > 50 || $result['addtionalDistance']['end'] > 50)
					{
						$incr = "1.05";
					}
				}
			}
			else
			{
//                if ($result["pickupCity"] != $servingRoute['sourceCity']) {
//                    $incr += 0.025;
//                }
//                if ($result["dropCity"] != $servingRoute['destinationCity']) {
//                    $incr += 0.025;
//                }
			}


			if ($incr > 1)
			{
				$baseAmount	 = round($baseAmount * $incr);
				$amount		 = $baseAmount + $driverCharge;
				$estTax		 = round(Quotation::model()->calculateTax($amount));
				$totalAmount = $amount + $estTax;
			}


			$partnerModel				 = Agents::model()->resetScope()->findByPk($bkgModel->bkg_agent_id);
			$partnerDiscount			 = $partnerModel->agt_commission | 0;
//
			$taxes						 = $tollTax + $stateTax;
			$vndBaseAmount				 = $rateVendorAmount - $taxes;
			$totExtraCharge				 = $extraCharge;
			$valArr['rateVendorAmount']	 = $rateVendorAmount;
			$valArr['vndBaseAmount']	 = $vndBaseAmount;
			$valArr['defPartnerMarkup']	 = $defPartnerMarkup;
			$cabrate					 = $cabResult['cabRate'];
			$rockMargin					 = Yii::app()->params['rockBottomMargin'];
			$rockBottomBasePrice		 = round($vndBaseAmount * (1 + $rockMargin / 100));
			$surgeArray					 = $this->addSurge($bkgModel, $rockBottomBasePrice, $vndBaseAmount);
			$surgeRockBottomPrice		 = $surgeArray['rockBaseAmount'];
			$surgeVendorAmount			 = $surgeArray['baseVendorAmount'];

			$sellBasePrice	 = round($surgeRockBottomPrice + ($defaultMarkup * $surgeRockBottomPrice / 100));
			$cpMarkup		 = ChannelPartnerMarkup::model()->getPricing($bkgModel, $sellBasePrice);
			if (!$cpMarkup)
			{
				$surgeSellBasePrice = $sellBasePrice;
			}
			else
			{
				$surgeSellBasePrice = $cpMarkup['amount'];
			}

			$partnerCost = round($partnerModel->calculateCost($surgeSellBasePrice));
			if ($partnerCost < $surgeRockBottomPrice)
			{
				$partnerCost		 = $surgeRockBottomPrice;
				$surgeSellBasePrice	 = $partnerModel->calculateSellPrice($partnerCost);
			}


			$baseAmount = $surgeSellBasePrice;

			$estTax		 = round(Quotation::model()->calculateTax($baseAmount));
			$totalAmount = $baseAmount + $estTax + $taxes + $driverCharge;

			$rateVendorAmount = $surgeVendorAmount + $taxes + round($driverCharge / 2);

			$CabList[$ctr]['gozo_base_amount']	 = $baseAmount;
			$CabList[$ctr]['actual_amt']		 = $totalAmount;
			$CabList[$ctr]['base_amt']			 = $baseAmount;
			$CabList[$ctr]['service_tax']		 = $estTax;
			$CabList[$ctr]['total_amt']			 = $totalAmount;
			$CabList[$ctr]['vendor_amount']		 = $rateVendorAmount;
			$CabList[$ctr]['toll_tax']			 = $tollTax;
			$CabList[$ctr]['state_tax']			 = $stateTax;

			$CabList[$ctr]['cab']			 = VehicleTypes::model()->getCarByCarType($cab);
			$CabList[$ctr]['cab_type_id']	 = $cab;
			$CabList[$ctr]['taxType']		 = $staxType;

			$CabList[$ctr]['nightAllowance'] = $nightAllowance;
			$extraKmRatePercentage			 = round($cabResult['cabRate'] * ((100 + $rockMargin) * (100 + $defaultMarkup) / 10000) * 2) / 2;

			//$CabList[$ctr]['gozo_amount'] = $totalAmount - $rateVendorAmount;
			$CabList[$ctr]['total_day']			 = $travelDays['actualDur'];
			$CabList[$ctr]['total_min']			 = $travelDays['totalMin'];
			$CabList[$ctr]['quoted_km']			 = $quotedDistance;
			$CabList[$ctr]['km_rate']			 = $extraKmRatePercentage;
			$CabList[$ctr]['error']				 = $result['error'];
			$CabList[$ctr]['image']				 = $model->vht_image;
			$CabList[$ctr]['capacity']			 = $model->vht_capacity;
			$CabList[$ctr]['bag_capacity']		 = $model->vht_bag_capacity;
			$CabList[$ctr]['big_bag_capacity']	 = $model->vht_big_bag_capacity;
			$CabList[$ctr]['cab_model']			 = $model->vht_model;
			$CabList[$ctr]['driverAllowance']	 = round($driverCharge);
			$CabList[$ctr]['tolltax']			 = $isTolltax;
			$CabList[$ctr]['statetax']			 = $isStatetax;
			$CabList[$ctr]['servicetax']		 = $servicetax;
			$CabList[$ctr]['cab_id']			 = $model->vht_id;

			$CabList[$ctr]['chargeableDistance'] = $distance;
		}

		$CabList['km']			 = $result['tripDistance'];
		$CabList['routeData']	 = $result;
		if (is_array($data))
		{
			$arr = $data + $CabList;

			$data			 = $arr;
			$data['type']	 = ($cabType == "") ? 1 : 0;
		}
		else
		{
			$data = $CabList;
		}

//        Yii::app()->cache->set($brtKey, $data, 1*60*60);
		return $CabList;
	}

	public function getCabDefaultMarkup($cabType)
	{
		$defaultMarkup = Yii::app()->params['defMarkupCab'];
		if (in_array($cabType, [VehicleCategory::ASSURED_DZIRE_ECONOMIC, VehicleCategory::ASSURED_INNOVA_ECONOMIC]))
		{
			$defaultMarkup += Yii::app()->params['assuredPremiumMarkup'];
		}
		return $defaultMarkup;
	}

	public function calculateMarkupSurge($bkgModel, $valArr, $priceSurge = true, $partnerId = 0)
	{
		$rateVendorAmount	 = $valArr['rateVendorAmount'];
		$vndBaseAmount		 = $valArr['vndBaseAmount'];
		$defPartnerMarkup	 = $valArr['defPartnerMarkup'];

		$bkgModel->bkg_agent_id	 = ($partnerId == 0) ? $bkgModel->bkg_agent_id : $partnerId;
		$allowSurge				 = true;
		$cpMarkup				 = ChannelPartnerMarkup::model()->getPricing($bkgModel, $vndBaseAmount);
		if ($cpMarkup)
		{
			$allowSurge = ($cpMarkup['cpm_apply_surge'] == 0) ? false : true;
		}


		$baseAmountWithMarkup = ($cpMarkup) ? round($cpMarkup['amount']) : round($vndBaseAmount * (1 + ($defPartnerMarkup / 100)));


		$estTax						 = round(Quotation::model()->calculateTax($baseAmountWithMarkup));
		$totalAmount				 = $baseAmountWithMarkup + $estTax;
		$bkgModel->bkg_total_amount	 = $totalAmount;
		$bkgModel->bkg_service_tax	 = $estTax;
		$surgeArr					 = [];
		$surge						 = 0;
		$baseAmount					 = $baseAmountWithMarkup;
		if ($priceSurge && $allowSurge)
		{
			$surgeArr			 = $this->addSurge($bkgModel, $rateVendorAmount);
			$rateVendorAmount	 = $surgeArr['rateVendorAmount'];
			$surge				 = $surgeArr['surge'];
			$baseAmountWithSurge = $baseAmountWithMarkup + $surge;

			$baseAmountWithSurge		 = ($baseAmountWithSurge > $baseAmountWithMarkup) ? $baseAmountWithSurge : $baseAmountWithMarkup;
			$baseAmount					 = $baseAmountWithSurge;
			$surgeArr['baseWithSurge']	 = $baseAmountWithSurge;
		}

		return ['baseWithMarkup'	 => $baseAmountWithMarkup,
			'rateVendorAmount'	 => $rateVendorAmount,
			'apply_surge'		 => $allowSurge,
			'surge'				 => $surge,
			'baseAmount'		 => $baseAmount
				] + $surgeArr;
	}

	public function addSurge($bkgModel, $rockBaseAmount, $baseVendorAmount)
	{
		$amount	 = $rockBaseAmount;
		$surge	 = PriceSurge::model()->getPricing($bkgModel, $rockBaseAmount);
		$diff	 = 0;
		if ($surge != null)
		{
			$amount	 = round($surge['amount']);
			$diff	 = $amount - $rockBaseAmount;
			if ($diff > 0)
			{
				$baseVendorAmount = $baseVendorAmount + round($diff / 3);
			}
			else
			{
				$baseVendorAmount = $baseVendorAmount + $diff;
			}
		}

		$data = ['surge' => $diff, 'rockBaseAmount' => $amount, 'baseVendorAmount' => $baseVendorAmount];
		return $data;
	}

	public function getQuotationList($params, $qotData)
	{
		$tripType	 = (int) $params['tripType'];
		$hike		 = 1.1;
		$cabTypes	 = explode(',', $params['carType']);
		$cabType	 = 0;
		if (count($cabTypes) == 1)
		{
			$cabType = $cabTypes[0];
		}
		$distanceData	 = $this->calculateDistance($tripType, $qotData, $cabType);
		$travelDays		 = $distanceData['travelDays'];
		$CabList		 = array();
		if (count($cabTypes) > 0)
		{
			$ctr				 = 0;
			$distanceEstimated	 = $distanceData['chargeableDistance'];

			$cityZoneId = Quotation::model()->findZoneByCity($distanceData['pickupCityId']);
			if ($tripType != 2)
			{
				$hike = Quotation::model()->getPriceByZone($cityZoneId['stt_zone']);
			}
			if ($cityZoneId['stt_zone'] == 1)
			{
				$defaultRates = [29 => 12, 30 => 9, 31 => 20];
			}
			else
			{
				$defaultRates = [29 => 13, 30 => 10, 31 => 20];
			}
			$distanceold = $distanceEstimated;
			$distance	 = (ceil($distanceold / 10)) * 10;
			$qoutedKM	 = $distance - $distanceData['totalExtraDist'];
			if ($cityZoneId['stt_zone'] != 1)
			{
				$minDistance = ($tripType == 1) ? 150 : 300;
				$distance	 = $qoutedKM;
				if ($distance < $minDistance)
				{
					$distance	 = $minDistance;
					$qoutedKM	 = $distance;
				}
			}
			else
			{
				$minDistance = ($tripType == 1) ? 100 : 200;
				if ($distance < $minDistance)
				{
					$distance = $minDistance;
				}
			}
			$pickup	 = $qotData[0]->pickup_city;
			$drop	 = $qotData[0]->drop_city;
			if (!empty($distanceData['serveRoute']))
			{
				$pickup	 = $distanceData['serveRoute']['pickup'];
				$drop	 = $distanceData['serveRoute']['drop'];
			}
			$routeModel		 = Route::model()->getbyCities($pickup, $drop);
			$extraDistance1	 = 0;
			foreach ($cabTypes as $cab)
			{
				$model = VehicleTypes::model()->getCarModel($cab, 1);

				$defaultRate = $defaultRates[$model->vht_id];
				$addlKmRate	 = (float) $distanceData['ratesList'][$cab] != null ? $distanceData['ratesList'][$cab] : $defaultRate;

				$hikeKMRate = ($tripType != 2) ? round($addlKmRate * $hike) : $addlKmRate;

				$nightAllow		 = ($travelDays['gozoDuration'] - 1);
				$nightCharge	 = Yii::app()->params['nightCharge'];
				$driverCharge	 = (int) ($nightAllow * $nightCharge[$cab] );
				if ($cityZoneId['stt_zone'] != 1)
				{
					$driverCharge = 300 * ceil($distance / 400);
				}

				$amount = $distance * (($tripType != 2) ? $hikeKMRate : $addlKmRate);

				$amount			 = round($amount + $driverCharge);
				$estTax			 = round(Quotation::model()->calculateTax($amount));
				$totalAmount	 = $amount + $estTax;
				$tolltax		 = 0;
				$statetax		 = 0;
				$servicetax		 = 1;
				$driverAllowance = 1;
				if ($tripType == 1)
				{

					if ($routeModel)
					{
						$rModel = Route::model()->getbyRouteAmount($routeModel->rut_id, $cab);
						if ($rModel)
						{
							$distanceData['routeStatus'] = 1;
							if ($distanceData['routeStatus'] == 1)
							{
								$tolltax		 = 1;
								$statetax		 = 1;
								$servicetax		 = 1;
								$driverAllowance = 1;
							}

							$qoutedKM	 = $rDistance	 = $rModel['distance'];
							if ($extraDistance1 > 0)
							{
								$qoutedKM = $distanceData['calDistance'] - $distanceData['totalExtraDist'];
							}
							$extraDistance	 = $distanceData['calDistance'] - $rDistance;
							$extraDistance	 = ($extraDistance <= 0 ) ? 0 : $extraDistance;
							$driverCharge	 = 0;
							$extraCharge	 = ($hikeKMRate * ($extraDistance)); // + $nightCharge;
							$extraTax		 = round(Quotation::model()->calculateTax($extraCharge));
							$totalAmount	 = $rModel['amount'] + $extraCharge + $extraTax;
							$stax			 = Filter::getServiceTaxRate();
							$amount			 = round($totalAmount / (1 + ($stax / 100)));
							$estTax			 = $totalAmount - $amount;
							$distanceold	 = $rModel['distance'] + $distanceData['totalExtraDist'];
							$distance		 = (ceil($distanceold / 10)) * 10;
							if ($cityZoneId['stt_zone'] != 1)
							{
								if ($distance < 150)
								{
									$distance = 150;
								}
							}
							else
							{
								if ($distance < 100)
								{
									$distance = 100;
								}
							}
						}
					}
				}
				$actualAmount					 = $amount;
				$bkgModel						 = Booking::model();
				$bkgModel->bkg_agent_id			 = $distanceData['agentid'];
				$bkgModel->bkg_from_city_id		 = $distanceData['startTripCity'];
				$bkgModel->bkg_to_city_id		 = $distanceData['endTripCity'];
				$bkgModel->bkg_vehicle_type_id	 = $cab;
				$bkgModel->bkg_pickup_date		 = $distanceData['startTripDate'];
				$bkgModel->bkg_total_amount		 = $totalAmount;
				$bkgModel->bkg_service_tax		 = $estTax;
				$bkgModel->bkg_booking_type		 = $tripType;
				if ($priceSurge != TRUE)
				{
					$surge = PriceSurge::model()->getPricing($bkgModel);
					if ($surge != null)
					{
						$gross		 = round($surge['amount']);
						$amount		 = $gross - round($driverCharge);
						$estTax		 = round((($stax * 0.01) * $gross));
						$totalAmount = $gross + $estTax;
					}
				}

				$CabList[$ctr]['cab']				 = VehicleTypes::model()->getCarByCarType($cab);
				$CabList[$ctr]['cab_type_id']		 = $cab;
				$CabList[$ctr]['actual_amt']		 = $actualAmount;
				$CabList[$ctr]['base_amt']			 = $amount;
				$CabList[$ctr]['service_tax']		 = $estTax;
				$CabList[$ctr]['total_amt']			 = $totalAmount;
				$CabList[$ctr]['km']				 = $distanceEstimated;
				$CabList[$ctr]['addional_km']		 = $distanceData['totalExtraDist'];
				$CabList[$ctr]['addional_time']		 = $distanceData['totalExtraTime'];
				$CabList[$ctr]['extra_km']			 = $distanceData['distanceExtraCount'];
				$CabList[$ctr]['total_km']			 = $distance;
				$CabList[$ctr]['quote_km']			 = $qoutedKM;
				//$CabList[$ctr]['old_km'] = $distanceEstimated;
				$CabList[$ctr]['total_day']			 = $travelDays['actualDur'];
				$CabList[$ctr]['total_min']			 = $travelDays['totalMin'];
				$CabList[$ctr]['km_rate']			 = $hikeKMRate;
				//$CabList[$ctr]['km_rate'] = $addlKmRate;
				$CabList[$ctr]['route']				 = $distanceData['routes'];
				$CabList[$ctr]['error']				 = $distanceData['error'];
				$CabList[$ctr]['routeData']			 = $distanceData['routeData'];
				$CabList[$ctr]['tripData']			 = $distanceData['tripData'];
				$CabList[$ctr]['image']				 = $model->vht_image;
				$CabList[$ctr]['capacity']			 = $model->vht_capacity;
				$CabList[$ctr]['bag_capacity']		 = $model->vht_bag_capacity;
				$CabList[$ctr]['big_bag_capacity']	 = $model->vht_big_bag_capacity;
				$CabList[$ctr]['cab_model']			 = $model->vht_model;
				$CabList[$ctr]['startTripDate']		 = $distanceData['startTripDate'];
				$CabList[$ctr]['pickup_address']	 = $distanceData['pickup_address'];
				$CabList[$ctr]['drop_address']		 = $distanceData['drop_address'];
				$CabList[$ctr]['endTripDate']		 = $distanceData['endTripDate'];
				$CabList[$ctr]['driverAllowance']	 = round($driverCharge);
				$CabList[$ctr]['tolltax']			 = $tolltax;
				$CabList[$ctr]['statetax']			 = $statetax;
				$CabList[$ctr]['servicetax']		 = $servicetax;
				$CabList[$ctr]['startTripCity']		 = $distanceData['startTripCity'];
				$CabList[$ctr]['endTripCity']		 = $distanceData['endTripCity'];
				$CabList[$ctr]['cab_id']			 = $model->vht_id;
				$CabList[$ctr]['pickup_pincode']	 = $distanceData['pickupPincode'];
				$CabList[$ctr]['drop_pincode']		 = $distanceData['dropPincode'];
				$CabList[$ctr]['distanceLatLong']	 = $distanceData['distanceLatLong'];
				$CabList[$ctr]['chargeableDistance'] = $distanceData['chargeableDistance'];
				$CabList[$ctr]['garageTime']		 = $distanceData['garageTime'];


				$ctr = ($ctr + 1);
			}
		}
		return $CabList;
	}

	public function objectToArray($d)
	{
		if (is_object($d))
		{
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
		if (is_array($d))
		{
			/*
			 * Return array converted to object
			 * Using __FUNCTION__ (Magic constant)
			 * for recursive call
			 */
			return array_map(__FUNCTION__, $d);
		}
		else
		{
			// Return array
			return $d;
		}
	}

	public function tripValidation($routes)
	{
		$success	 = true;
		$errors		 = [];
		$triptype	 = 1;
		$rCount		 = count($routes);
		if ($rCount <= 2)
		{
			$pickup_date = $routes[0]->date;
			$diff		 = floor((strtotime($pickup_date) - time()) / 3600);
			if ($diff < 4)
			{
				$success = false;
				$errors	 = 'Departure time should be after ' . date('d-M-y h:i A', strtotime('+4 hours'));
			}
			else
			{
				$success = true;
				$errors	 = [];
			}
		}
		if ($rCount >= 2 && $success)
		{
			$arr						 = [];
			$arr[0]['date']				 = $routes[$rCount - 2]->date;
			$arr[0]['drop_address']		 = $routes[$rCount - 2]->drop_address;
			$arr[0]['drop_city']		 = $routes[$rCount - 2]->drop_city;
			$arr[0]['drop_point']		 = $routes[$rCount - 2]->drop_point;
			$arr[0]['pickup_address']	 = $routes[$rCount - 2]->pickup_address;
			$arr[0]['pickup_city']		 = $routes[$rCount - 2]->pickup_city;
			$arr[0]['pickup_point']		 = $routes[$rCount - 2]->pickup_point;
			$data						 = json_encode($arr);
			$data						 = json_decode($data);
			$result						 = Quotation::model()->calculateDistance($triptype, $data);
			$est_date					 = date('Y-m-d H:i:s', strtotime($routes[$rCount - 2]->date . '+ ' . $result['calculateTime'] . ' minute'));
			if ($est_date != "")
			{
				$d1	 = new DateTime($routes[$rCount - 1]->date);
				$d2	 = new DateTime($est_date);
				if ($d1 < $d2)
				{
					$success = false;
					$errors	 = 'Pickup Time for ' . Cities::getName($routes[$rCount - 1]->pickup_city) . ' should be after ' . $est_date;
				}
				else
				{
					if ($rCount > 2)
					{
						$arr1						 = [];
						$arr1[0]['date']			 = $routes[$rCount - 3]->date;
						$arr1[0]['drop_address']	 = $routes[$rCount - 3]->drop_address;
						$arr1[0]['drop_city']		 = $routes[$rCount - 3]->drop_city;
						$arr1[0]['drop_point']		 = $routes[$rCount - 3]->drop_point;
						$arr1[0]['pickup_address']	 = $routes[$rCount - 3]->pickup_address;
						$arr1[0]['pickup_city']		 = $routes[$rCount - 3]->pickup_city;
						$arr1[0]['pickup_point']	 = $routes[$rCount - 3]->pickup_point;
						$data1						 = json_encode($arr1);
						$data1						 = json_decode($data1);
						$result1					 = Quotation::model()->calculateDistance($triptype, $data1);
						$est_date1					 = date('Y-m-d H:i:s', strtotime($routes[$rCount - 3]->date . '+ ' . $result1['calculateTime'] . ' minute'));
						if ($est_date1 != "")
						{
							$d11 = new DateTime($routes[$rCount - 2]->date);
							$d21 = new DateTime($est_date1);
							if ($d11 < $d21)
							{
								$success = false;
								$errors	 = 'Pickup Time for ' . Cities::getName($routes[$rCount - 2]->pickup_city) . ' should be after ' . $est_date1;
							}
							else
							{
								$success = true;
								$errors	 = [];
							}
						}
					}
					else
					{
						$success = true;
						$errors	 = [];
					}
				}
			}
		}
		return ['success' => $success, 'errors' => $errors];
	}

	public function getVendorAmountListByBookingIds($bkgIds, $carType = '')
	{
		$triptype	 = 2;
		$models		 = Booking::model()->getBookingModelsbyIdsList($bkgIds);
		$route		 = [];
		foreach ($models as $model)
		{
			/* @var $model Booking */
			//$brtRoutes = $model->bookingRoutes;
			$brtRoutes = BookingRoute::model()->getAllByBkgid($model->bkg_id);
			foreach ($brtRoutes as $val)
			{
				/* @var $val BookingRoute */
				if ($oldToCity != $val['brt_from_city_id'] && $oldToCity != null)
				{
					$routeModel							 = new BookingRoute();
					$routeModel->brt_from_city_id		 = $oldToCity;
					$routeModel->brt_to_city_id			 = $val['brt_from_city_id'];
					$routeModel->brt_pickup_date_date	 = $dt->format('Y-m-d H:i:s');
					$routeModel->brt_pickup_date_time	 = $dt->format('h:i A');
					$route[]							 = $routeModel;
				}

				$routeModel						 = new BookingRoute();
				$routeModel->brt_from_city_id	 = $val['brt_from_city_id'];
				$oldToCity						 = $routeModel->brt_to_city_id		 = $val['brt_to_city_id'];
				$tripDuration					 = $val['brt_trip_duration'];
				$hour							 = floor($tripDuration / 60);
				$minute							 = $tripDuration % 60;
				$dt								 = new DateTime($val['brt_pickup_datetime']);
				$dt->add(new DateInterval("PT{$tripDuration}M"));

				//echo "testTrip==============>" . $dt->format('Y-m-d H:i:s');
				$routeModel->brt_pickup_datetime	 = $val['brt_pickup_datetime'];
				$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($val['brt_pickup_datetime']);
				$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime($val['brt_pickup_datetime']));
				$route[]							 = $routeModel;
			}
		}
		//print_r($route);
		//if(count($route)>2) exit();
		$partnerId = Yii::app()->params['gozoChannelPartnerId'];
//		$quote		 = Quotation::model()->getQuote($route, $triptype, $partnerId);



		$quote				 = new Quote();
		$quote->routes		 = $route;
		$quote->tripType	 = $triptype; // package
		$quote->partnerId	 = $partnerId;
		$quote->quoteDate	 = $models[0]->bkg_create_date;
		$quote->pickupDate	 = $route[0]->brt_pickup_datetime;

		$quote->sourceQuotation	 = Quote::Platform_Admin;
		$quote->setCabTypeArr();
		$tempCab				 = [];
		if ($carType > 0)
		{
			$qt					 = $quote->getQuote($carType);
			$tempCab[$carType]	 = $qt[$carType]->routeRates->vendorAmount;
		}
		else
		{
			$qt = $quote->getQuote();
			foreach ($qt as $k => $v)
			{
				$tempCab[$k] = $v->routeRates->vendorAmount;
			}
		}

		return $tempCab;
	}

	public function calculateLatLongDistance($lat1, $long1, $lat2, $long2)
	{
		$result = GoogleMapAPI::getInstance()->getDrivingDistancebyLatLong($lat1, $long1, $lat2, $long2);
		if ($result['success'])
		{
			$distanceData		 = $result['distance'];
			$distance1			 = $distanceData[0]['dist'];
			$grace				 = (round($distance1 / 10) > 10) ? 10 : ceil($distance1 / 10);
			$grace1				 = 2;
			$result['distance']	 = $distance1;
			$time1				 = $distanceData[0]['time'];
			$result['time']		 = (ceil(($time1 * 1.1) / 10) * (10));
		}
		return $result;
	}

}
