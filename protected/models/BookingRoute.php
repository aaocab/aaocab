<?php

/**
 * This is the model class for table "booking_route".
 *
 * The followings are the available columns in table 'booking_route':
 * @property integer $brt_id
 * @property integer $brt_bkg_id
 * @property integer $brt_bcb_id
 * @property integer $brt_from_city_id
 * @property integer $brt_to_city_id
 * @property string $brt_from_location
 * @property string $brt_to_location
 * @property string $brt_pickup_datetime
 * @property string $brt_from_pincode
 * @property string $brt_to_pincode
 * @property double $brt_from_latitude
 * @property double $brt_from_longitude
 * @property double $brt_to_latitude
 * @property double $brt_to_longitude
 * @property string $brt_trip_distance
 * @property string $brt_trip_duration
 * @property integer $brt_status
 * @property integer $brt_active
 * @property string $brt_created
 * @property string $brt_vendor_request_cnt
 *
 * The followings are the available model relations:
 * @property Booking $brtBkg
 * @property BookingCab $brtBcb
 * @property Cities $brtFromCity
 * @property Cities $brtToCity
 */
class BookingRoute extends CActiveRecord
{

	public $from_place, $to_place, $airport, $place, $arrival_time, $railway;
	public $brt_from_city_name, $brt_to_city_name, $brt_from_city_is_airport, $brt_to_city_is_airport,
			$brt_from_city_is_poi, $brt_to_city_is_poi, $brt_pcd_id,
			$brt_return_date_date, $brt_return_date_time, $brt_return_datetime,
			$brt_pickup_date_date, $brt_pickup_date_time, $brt_min_date, $brt_from_city_is_poitype, $brt_to_city_is_poitype;
	public $brt_additional_from_address, $brt_additional_to_address, $returnDateTime;
	public $brt_place_id, $brt_from_place_id, $brt_to_place_id, $estArrTime, $brt_from_place_name, $brt_to_place_name, $brt_from_place_lat, $brt_from_place_long, $brt_to_place_lat, $brt_to_place_long,
			$brt_formatted_address, $brt_from_formatted_address, $brt_to_formatted_address, $brt_from_place_type, $brt_to_place_type;
	public $tripType, $transferType, $totalRoutes, $brtAirport, $brtTransferLoc, $trip_distance		 = 0, $trip_duration		 = 0;
	public $est_date			 = "";
	public $brt_from_is_airport	 = 0, $brt_to_is_airport	 = 0;
	public $brt_from_is_railway  = 0, $brt_to_is_railway = 0;
	public $bkg_ext_route_data	 = [];
	public $brt_from_location_cpy, $brt_to_location_cpy, $brt_is_copy_booking	 = 0;
	public $agentId;
	public $useHyperLocation = true;
	/**
	 * @var BookingRoute $firstRoute
	 */
	public $firstRoute;

	/**
	 * @var BookingRoute $lastRoute
	 */
	public $lastRoute;

	/**
	 * @var BookingRoute $previousRoute
	 */
	public $previousRoute;

	/**
	 * @var BookingRoute $nextRoute
	 */
	public $nextRoute;

	public function defaultScope()
	{
		$ta = $this->getTableAlias(false, false);

		$arr = array(
			'condition' => $ta . ".brt_active=1",
		);
		return $arr;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_route';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			['brt_from_city_id, brt_to_city_id, brt_pickup_datetime', 'required'], // rule use for validation
			['brt_pickup_date_time, brt_pickup_date_date', 'required', 'on' => 'validateDayRental, insert'], // rule use for validation DayRental
			array('brt_bkg_id', 'required', 'on' => 'rtupdate'),
			array('brt_bkg_id', 'validateRouteData', 'on' => 'ALLRoutes'),
//			array('brt_pickup_date_date', 'validateTime', 'on' => 'ALLRoutes'),
			array('brt_return_date_date', 'validateReturnTime', 'on' => 'validate'),
			array('brt_from_latitude, brt_from_longitude, brt_to_latitude, brt_to_longitude', 'numerical'),
			array('brt_from_location, brt_to_location', 'length', 'max' => 500),
			array('brt_bkg_id, brt_bcb_id, brt_from_city_id, brt_to_city_id, brt_status, brt_active', 'numerical', 'integerOnly' => true),
			array('brt_trip_distance, brt_trip_duration', 'length', 'max' => 100),
            ['airport', 'airportValidate', 'on' => 'airportValidate'],
            
			array('brt_pickup_datetime,brt_from_location, brt_to_location, brt_from_pincode,brt_to_pincode,brt_pickup_date_date,brt_pickup_date_time
                ,brt_from_latitude,brt_from_longitude,brt_to_latitude,brt_to_longitude,brt_return_date_date,brt_return_date_time,brt_pcd_id,
				brt_placeid,brt_formatted_address,brt_from_placeid,brt_from_formatted_address,brt_to_placeid,brt_to_formatted_address, brt_id, brt_bkg_id, brt_bcb_id, brt_from_city_id, brt_to_city_id, brt_pickup_datetime,
					brt_from_city_name, brt_to_city_name, brt_pickup_date_date, brt_pickup_date_time, brt_trip_distance,
					brt_trip_duration, brt_status, brt_active,brt_vendor_request_cnt, brt_created, from_place,
					to_place, airport, place', 'safe'),
		);
	}

	public function beforeValidate()
	{
		$this->encodeAttributes();
		return parent::beforeValidate();
	}

	/**
	 * This function helps to determine the time difference of create and pickup booking
	 * @param BookingRoute $model
	 * @return int
	 */
	public static function checkTime($model)
	{
		$minTime = Config::getMinPickupTime($model->tripType);

		$response = new stdClass();

		$response->timeDifference	 = $minTime;
		$response->isAllowed		 = false;

		if ($model->tripType == 7)
		{
			$response->timeDifference = 12 * 60;
		}

		return $response;
	}

	public function encodeAttributes()
	{
		if (Booking::isDayRental($this->tripType))
		{
			$this->brt_to_city_id = $this->brt_from_city_id;
		}

		if ($this->previousRoute != null && $this->from_place == "")
		{
			$this->from_place = $this->previousRoute->to_place;
		}

		if ($this->from_place != "")
		{
			$this->applyPlace($this->from_place, 1);
		}
		if ($this->to_place != "")
		{
			$this->applyPlace($this->to_place, 2);
		}

		if ($this->tripType == 4 && $this->place != "" && $this->airport != "")
		{
			$this->populateAirport($this->transferType);
		}

		if (DateTimeFormat::concatDateTime($this->brt_pickup_date_date, $this->brt_pickup_date_time, $pickupTime))
		{
			$this->brt_pickup_datetime = $pickupTime;
		}
		if (DateTimeFormat::concatDateTime($this->brt_return_date_date, $this->brt_return_date_time, $returnTime))
		{
			$this->brt_return_datetime = $returnTime;
		}
	}

	public function decodeAttributes()
	{
		$this->parsePickupDateTime($this->brt_pickup_datetime);
		$this->parseReturnDateTime($this->brt_return_datetime);
	}

	public function afterFind()
	{
		parent::afterFind();
		$this->decodeAttributes();
	}

	public function parsePickupDateTime($dateTime)
	{
		if (DateTimeFormat::parseDateTime($dateTime, $date, $time))
		{
			$this->brt_pickup_date_date	 = $date;
			$this->brt_pickup_date_time	 = $time;
		}
	}

	public function parseReturnDateTime($dateTime)
	{
		if (DateTimeFormat::parseDateTime($dateTime, $date, $time))
		{
			$this->brt_return_date_date	 = $date;
			$this->brt_return_date_time	 = $time;
		}
	}

	/** @param BookingRoute[] $routes */
	public static function validateRoutes(&$routes, $tripType = 1, $transferType = 0, $agentId = 0, $fbgType = 0)
	{
		$key = json_encode($routes) . "_" . $tripType . "_" . $transferType;
		if (isset($GLOBALS[$key]))
		{
			goto returnValidate;
		}

		$errors	 = [];
		/** @var BookingRoute $route */
		$route	 = null;
		Logger::beginProfile("validateRoutes");
		if ($tripType == 2)
		{
			self::setRouteForRoundTrip($routes);
		}

		$count = count($routes);
		for ($i = 0; $i < $count; $i++)
		{
			$route->nextRoute			 = $routes[$i];
			$routes[$i]->previousRoute	 = $route;
			$route						 = $routes[$i];
			if ($i < $count - 1)
			{
				$route->nextRoute = $routes[$i + 1];
			}

			$route->firstRoute	 = $routes[0];
			$route->lastRoute	 = $routes[$count - 1];
			$route->tripType	 = $tripType;
			$route->transferType = $transferType;
			$route->setScenario('ALLRoutes');
			$route->tripType	 = $tripType;
			if ($route->validate())
			{
				if ($route->previousRoute != null)
				{
					$route->brt_min_date = $route->previousRoute->arrival_time;
				}
				$route->agentId = $agentId;
				$route->validateRouteTime('brt_pickup_datetime', $i, $route->agentId, $fbgType);
			}
			if ($route->hasErrors())
			{
				$errors[$i] = $route->getErrors();
			}
		}
		Logger::endProfile("validateRoutes");

		$GLOBALS[$key] = array_values(Filter::removeNull($errors));

		returnValidate:
		return $GLOBALS[$key];
	}

	public function validateRouteTime($attribute, $i, $agentId = 0, $fbgType)
	{
		$this->calculateDistance(false);

		if ($this->brt_pickup_datetime == '' || $this->brt_min_date == '')
		{
			goto result;
		}
		$est_date1	 = $this->brt_min_date;
		$est_date2	 = new DateTime($this->brt_pickup_datetime);
		$est_date3	 = new DateTime($this->brt_min_date);
		
		if($this->tripType == 2)
		{
			$est_date4 = date('Y-m-d H:i:s', strtotime($est_date1 . ' +' . $this->brt_trip_duration . ' minute'));
		}

		if ($est_date2 >= $est_date3 || $est_date2 == '')
		{
			goto result;
		}
		$fromCityName	 = $this->brtFromCity->cty_name;
		$toCityName		 = $this->brtToCity->cty_name;
		if (($this->tripType == 2 && $fbgType != 1) || ($this->tripType == 2 && $fbgType != 2))
		{
			$message = "You are expected to arrive in $fromCityName at " . date('d/m/Y h:i A', strtotime($est_date1)) . '.';
			$message .= ' Journey end time to ' . $toCityName . ' should be after ' . date('d/m/Y h:i A', strtotime($est_date4)) . '.';
			$this->addError($attribute, $message);
			return false;
		}
		if ($this->tripType == 3)
		{
			$this->addError($attribute, 'Pickup Time for ' . $fromCityName . ' should be after ' . date('d/m/Y h:i A', strtotime($est_date1)));
			return false;
		}


		result:
		return true;
	}

	public function validateTime($attribute, $params)
	{
		$this->encodeAttributes();
		if ($this->brt_pickup_datetime != '')
		{
			$diff = floor((strtotime($this->brt_pickup_datetime) - time()) / 60);

			if ($this->brt_is_copy_booking == 1)
			{
				goto skipCheck;
			}

			$response = self::checkTime($this);
			if ($response->isAllowed && $diff >= 2)
			{
				goto skipCheck;
			}

			if ($diff < $response->timeDifference)
			{
				$this->addError($attribute, 'Departure time should be at least ' . $response->timeDifference . ' minutes from now.');
				return false;
			}

			skipCheck:
			$maxTime		 = Config::getMaxPickupTime($this->tripType);
			$maxTimestamp	 = strtotime("+{$maxTime} minute");
			$maxPickDate	 = date('Y-m-d H:00:00', $maxTimestamp);
			$d1				 = new DateTime($this->brt_pickup_datetime);
			$d2				 = new DateTime($maxPickDate);
			if ($d1 > $d2)
			{
				$maxPickDate = date('d-M-Y H:00:00', $maxTimestamp);

				$this->addError($attribute, 'Departure time should be before ' . $maxPickDate);
				return false;
			}
		}
		return true;
	}

	public function validateReturnTime($attribute, $params)
	{
		if ($this->brt_return_datetime != null)
		{
			$returnDateTime	 = $this->brt_return_datetime;
			$pickupDateTime	 = $this->brt_pickup_datetime;
			if ($pickupDateTime >= $returnDateTime)
			{
				$this->addError($attribute, 'Return date cannot be earlier than Pickup date');
				return FALSE;
			}
		}
		return true;
	}

	public function validateTripType()
	{
		switch ($this->tripType)
		{
			case 2:
				$this->validateRoundTrip();
				break;
			case 3:
				$this->validateMultiCity();
				break;
			case 4:
				$this->validateAirportTransfer();
				break;
			case 14:
				$this->validateOneWay();
				break;
			case 1:
				$this->validateOneWay();
			default:
				break;
		}
		return !$this->hasErrors();
	}

	public function validateAirportTransfer()
	{
		if ($this->nextRoute != null)
		{
			$this->addError('brt_id', "Only one route is allowed in Airport Transfer");
		}
		$field = "brt_from_city_id";
		/** @var Cities $city */
		if ($this->transferType == 1)
		{
			$city	 = $this->brtFromCity;
			$field	 = "brt_from_city_id";
		}
		if ($this->transferType == 2)
		{
			$city	 = $this->brtToCity;
			$field	 = "brt_to_city_id";
		}

		$maxDistance = Yii::app()->params['airportCityRadius'];
		$radius		 = ($city->cty_radius > 0) ? $city->cty_radius : $maxDistance;
		/* if ($this->brt_trip_distance > $radius)
		  {
		  $this->addError($field, 'Airport transfer is not available for the selected route.');
		  return FALSE;
		  } */
	}

	public function validateOneWay()
	{
		if ($this->nextRoute != null)
		{
			$this->addError('brt_id', "Only 1 route is allowed in one way trip");
			return FALSE;
		}
	}

	public function validateRoundTrip()
	{
		if ($this->nextRoute == null && $this->previousRoute == null)
		{
			$this->addError('brt_id', "Minimum 2 route is allowed in round trip");
			return FALSE;
		}
		if ($this->nextRoute == null && $this->lastRoute->brt_to_city_id != $this->firstRoute->brt_from_city_id)
		{
			$this->addError('brt_to_city_id', "Source and destination cities must be same for return trip");
			return FALSE;
		}
	}

	public function validateMultiCity()
	{
		if ($this->nextRoute == null && $this->previousRoute == null)
		{
			$this->addError('brt_id', "For Round Trip / Multi city you need to add pickups from at least 2 cities.<br /> For example: Your trip plan needs to be going from A to B then B to A OR Going from A to B then B to C then C to D and so on.");
			return FALSE;
		}
	}

	public function validateRouteData($attribute, $params)
	{
		try
		{
			$success = true;
			if ($this->brt_to_city_id == null && !in_array($this->tripType, [9, 10, 11]))
			{
				if (!$this->hasErrors("brt_to_city_id"))
				{
					$this->addError("brt_to_city_id", 'Please select destination city');
				}
				$success = false;
			}

			if (($this->brt_to_city_id == null || $this->brt_from_city_id == null ) && $this->tripType == 14)
			{
				if (!$this->hasErrors("brt_to_city_id"))
				{
					$this->addError("brt_to_city_id", 'Please select source and destination both');
				}
				$success = false;
			}

			if ($success == false)
			{
				goto skipNextRoute;
			}

			$fromCity	 = Cities::checkActive($this->brt_from_city_id);
			$toCity		 = Cities::checkActive($this->brt_to_city_id);
			if ($fromCity == 0)
			{
				if (!$this->hasErrors("brt_from_city_id"))
				{
					$this->addError("brt_from_city_id", 'Please select valid source city');
				}
				$success = false;
			}

			if ($toCity == 0)
			{
				if (!$this->hasErrors("brt_to_city_id"))
				{
					$this->addError("brt_to_city_id", 'Please select valid destination city');
				}
				$success = false;
			}

			if ($this->nextRoute == null)
			{
				goto skipNextRoute;
			}
			$this->brt_to_city_id = $this->nextRoute->brt_from_city_id;
			if ($this->nextRoute->getFromCoordinates())
			{
				$this->brt_to_location	 = $this->nextRoute->brt_from_location;
				$this->brt_to_latitude	 = $this->nextRoute->brt_from_latitude;
				$this->brt_to_longitude	 = $this->nextRoute->brt_from_longitude;
			}

			skipNextRoute:
			if (!$this->hasErrors())
			{

//$this->fillDetails();
				$this->calculateDistance($this->useHyperLocation);
				$success = $this->validateTripType();
			}
		}
		catch (Exception $exc)
		{
			$success = false;
			$this->addError("brt_id", $exc->getMessage());
		}
		return $success;
	}

	public function setRouteForRoundTrip(&$routes)
	{
		if (sizeof($routes) == 1 && $routes[0]->brt_return_date_date != '')
		{
			$firstRoute						 = $routes[0];
			$pickupDateTime					 = BookingRoute::model()->getReturnPickupDateTime($firstRoute->brt_from_city_id, $firstRoute->brt_to_city_id, $firstRoute->brt_return_date_date, $firstRoute->brt_return_date_time);
			$nextRoute						 = new BookingRoute();
			$nextRoute->brt_from_city_id	 = $firstRoute->brt_to_city_id;
			$nextRoute->brt_to_city_id		 = $firstRoute->brt_from_city_id;
			$nextRoute->brt_pickup_date_date = DateTimeFormat::DateTimeToDatePicker($pickupDateTime);
			$nextRoute->brt_pickup_date_time = date('h:i A', strtotime($pickupDateTime));
			$nextRoute->brt_return_date_date = $firstRoute->brt_return_date_date;
			$nextRoute->brt_return_date_time = $firstRoute->brt_return_date_time;
			$routes[]						 = $nextRoute;
		}
	}

	public function getFromCoordinates($useCity = false)
	{
		$data = Filter::getCoordinates($this->brt_from_latitude, $this->brt_from_longitude);
		if ($data !== false || $useCity == false)
		{
			goto end;
		}
		$data = Filter::getCoordinates($this->brtFromCity->cty_lat, $this->brtFromCity->cty_long);
		end:
		return $data;
	}

	public function getToCoordinates($useCity = false)
	{
		$data = Filter::getCoordinates($this->brt_to_latitude, $this->brt_to_longitude);
		if ($data !== false || $useCity == false)
		{
			goto end;
		}
		$data = Filter::getCoordinates($this->brtToCity->cty_lat, $this->brtToCity->cty_long);
		end:
		return $data;
	}

	public function fillDetails()
	{
		$fromCoordinates = $this->getFromCoordinates(true);
		$toCoordinates	 = $this->getToCoordinates(true);
		if (!$fromCoordinates && !$toCoordinates)
		{
			return;
		}
		$sourcePlace = Stub\common\Place::init($this->brt_from_latitude, $this->brt_from_longitude);
		$destPlace	 = Stub\common\Place::init($this->brt_to_latitude, $this->brt_to_longitude);
		$dmxModel	 = DistanceMatrix::getByCoordinates($sourcePlace, $destPlace);
		if ($dmxModel == null)
		{
			throw new Exception("Unable to calculate distance for given route", 1);
		}

		$this->brt_trip_distance = $dmxModel->dmx_distance;
		$this->brt_trip_duration = $dmxModel->dmx_duration;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'brtBkg'		 => array(self::BELONGS_TO, 'Booking', 'brt_bkg_id'),
			'brtBcb'		 => array(self::BELONGS_TO, 'BookingCab', 'brt_bcb_id'),
			'brtFromCity'	 => array(self::BELONGS_TO, 'Cities', 'brt_from_city_id'),
			'brtToCity'		 => array(self::BELONGS_TO, 'Cities', 'brt_to_city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'brt_id'				 => 'ID',
			'brt_bkg_id'			 => 'Booking ID',
			'brt_bcb_id'			 => 'Cab ID',
			'brt_from_city_id'		 => 'Source City',
			'brt_to_city_id'		 => 'Destination City',
			'brt_pickup_datetime'	 => 'Pickup Datetime',
			'brt_pickup_date_date'	 => 'Pickup Date',
			'brt_pickup_date_time'	 => 'Pickup Time',
			'brt_trip_distance'		 => 'Trip Distance',
			'brt_trip_duration'		 => 'Trip Duration',
			'brt_status'			 => 'Status',
			'brt_active'			 => 'Active',
			'brt_created'			 => 'Created At'
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

		$criteria->compare('brt_id', $this->brt_id);
		$criteria->compare('brt_bkg_id', $this->brt_bkg_id);
		$criteria->compare('brt_bcb_id', $this->brt_bcb_id);
		$criteria->compare('brt_from_city_id', $this->brt_from_city_id);
		$criteria->compare('brt_to_city_id', $this->brt_to_city_id);
		$criteria->compare('brt_from_location', $this->brt_from_location, true);
		$criteria->compare('brt_from_pincode', $this->brt_from_pincode, true);
		$criteria->compare('brt_to_pincode', $this->brt_to_pincode, true);
		$criteria->compare('brt_to_location', $this->brt_to_location, true);
		$criteria->compare('brt_from_latitude', $this->brt_from_latitude);
		$criteria->compare('brt_from_longitude', $this->brt_from_longitude);
		$criteria->compare('brt_to_latitude', $this->brt_to_latitude);
		$criteria->compare('brt_to_longitude', $this->brt_to_longitude);
		$criteria->compare('brt_pickup_datetime', $this->brt_pickup_datetime, true);
		$criteria->compare('brt_trip_distance', $this->brt_trip_distance, true);
		$criteria->compare('brt_trip_duration', $this->brt_trip_duration, true);
		$criteria->compare('brt_status', $this->brt_status);
		$criteria->compare('brt_vendor_request_cnt', $this->brt_vendor_request_cnt);
		$criteria->compare('brt_active', $this->brt_active);
		$criteria->compare('brt_created', $this->brt_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingRoute the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{
		parent::beforeSave();
		if (in_array($this->tripType, [9, 10, 11]) && $this->trip_distance > 0 && $this->trip_duration > 0)
		{
			$this->brt_trip_distance = $this->trip_distance;
			$this->brt_trip_duration = $this->trip_duration;
		}
		return true;
	}

	public function getByBkgid($bkgid)
	{
		$rModel = '';
		if ($bkgid)
		{
			$criteria	 = new CDbCriteria;
			$criteria->compare('brt_bkg_id', $bkgid);
			$rModel		 = $this->find($criteria);
		}
		return $rModel;
	}

	public function getAllByBkgid($bkgid)
	{
		if ($bkgid > 0)
		{
			$criteria = new CDbCriteria;

			$criteria->compare('brt_bkg_id', $bkgid);
			$rModel = $this->findAll($criteria);
			return $rModel;
		}
		return false;
	}

	public function calculateDistance($useMAPAPI = true, $airportTransfer = false, $isAdrsUpdate = false)
	{
		Logger::beginProfile("BookingRoute::calculateDistance");
		$json		 = json_encode(Filter::removeNull($this->getAttributes()));
		Logger::trace($json);
		$success	 = false;
		$distance	 = 0;
		$time		 = 0;
		if ($this->brt_trip_distance > 0 && $this->brt_trip_duration > 0)
		{
			goto end;
		}
		$dmxModel = null;
		if ($this->brt_from_longitude > 0 && $this->brt_to_longitude > 0 && $this->brt_from_latitude > 0 && $this->brt_to_latitude > 0)
		{
			$lat1	 = $this->brt_from_latitude;
			$lat2	 = $this->brt_to_latitude;
			$lon1	 = $this->brt_from_longitude;
			$lon2	 = $this->brt_to_longitude;

			$dmxModel = DistanceMatrix::getByCoordinates(Stub\common\Place::init($lat1, $lon1), Stub\common\Place::init($lat2, $lon2), $useMAPAPI);
			if ($dmxModel)
			{
				Logger::info("Distance fetched  via DistanceMatrix::getByCoordinates");
				$success	 = true;
				$distance	 = $dmxModel->dmx_distance;
				$time		 = $dmxModel->dmx_duration;
				goto skipAPI;
			}
		}
		skipDistanceMatrix:
		if ($this->brt_from_city_id != $this->brt_to_city_id || !$dmxModel)
		{

			$result = Route::model()->populate($this->brt_from_city_id, $this->brt_to_city_id);
			if ($result['success'])
			{
				$rutModel	 = $result['model'];
				$distance	 = $rutModel->rut_estm_distance;
				$time		 = $rutModel->rut_estm_time;
			}
			else
			{
				return false;
			}
		}

		skipAPI:
		if ($this->brt_from_city_id == $this->brt_to_city_id)
		{
			$time		 = ($time > 0) ? $time : 30;
			$distance	 = ($distance > 0) ? $distance : 10;
		}

		$this->brt_trip_distance = $distance;
		$this->brt_trip_duration = $time;
		end:
		$this->arrival_time		 = date('Y-m-d H:i:s', strtotime($this->brt_pickup_datetime . ' +' . $this->brt_trip_duration . ' minute'));
		Logger::endProfile("BookingRoute::calculateDistance");
	}

	public function getRouteName($bkgid)
	{
		$qry	 = "SELECT brt_from_city_id, brt_to_city_id from booking_route where brt_bkg_id = $bkgid AND brt_active = 1";
		$routes	 = DBUtil::queryAll($qry);
		$ctr	 = 0;
		foreach ($routes as $route)
		{
			$ctr = ($ctr + 1);
			$rut .= Cities::getName($route['brt_from_city_id']) . ' - ';
			if (count($routes) == $ctr)
			{
				$rut .= Cities::getName($route['brt_to_city_id']);
			}
		}
		return $rut;
	}

	public function getPickupLatLngByBkgId($bkgId)
	{
		$params	 = array('bkgId' => $bkgId);
		$qry	 = "SELECT  IF(brt.brt_from_latitude > 0, brt.brt_from_latitude, cty.cty_lat) AS pickup_lat,
							    IF(brt.brt_from_longitude> 0,brt.brt_from_longitude, cty.cty_long) AS pickup_long
							FROM booking_route brt INNER JOIN cities cty ON cty.cty_id = brt.brt_from_city_id WHERE brt.brt_bkg_id =:bkgId AND brt_active = 1 ORDER BY brt_id  ASC LIMIT 1";
		$records = DBUtil::queryRow($qry, DBUtil::SDB(), $params);
		return $records;
	}

	public function getRouteNameByBcb($id)
	{
		$qry			 = "SELECT booking_route.brt_from_city_id, booking_route.brt_to_city_id
                FROM `booking_route`
                WHERE booking_route.brt_active=1 AND brt_bcb_id = $id";
		$routes			 = DBUtil::queryAll($qry);
		$ctr			 = 0;
		$previousCity	 = '';
		foreach ($routes as $route)
		{
			$ctr = ($ctr + 1);
			if ($route['brt_from_city_id'] != $previousCity)
			{
				$rut .= Cities::getName($route['brt_from_city_id']) . ' - ';
			}
			if (count($routes) == $ctr)
			{
				$rut .= Cities::getName($route['brt_to_city_id']);
			}
			$previousCity = $route['brt_from_city_id'];
		}
		return $rut;
	}

	public function linkBookingwithVendor($bkgid, $bcbid)
	{
		$model				 = $this->getByBkgid($bkgid);
//	foreach ($models as $model) {
		$model->brt_bcb_id	 = $bcbid;
		return $model->save();
//}
	}

	public function linkAllBookingwithVendor($bkgid, $bcbid)
	{
		$success = true;
		$models	 = $this->getAllByBkgid($bkgid);
		foreach ($models as $model)
		{
			$model->brt_bcb_id	 = $bcbid;
			$success			 = $model->save();
		}
		return $success;
	}

	public function populateMinDate($pscity, $pdate, $ptime)
	{
		$scity = $this->brt_from_city_id;
		if ($this->brt_pickup_datetime != '')
		{
			$this->parsePickupDateTime($this->brt_pickup_datetime);
			$this->parseReturnDateTime($this->brt_return_datetime);
		}
		if ($pscity == '')
		{
			if ($this->brt_pickup_date_date == '')
			{
				$this->brt_min_date = date('Y-m-d');
			}
			return;
		}
		$rutModel = Route::model()->getbyCities($pscity, $scity);
		if (!$rutModel)
		{
			$result1 = Route::model()->populate($pscity, $scity);
			if ($result1['success'])
			{
				$rutModel = $result1['model'];
			}
		}

		$date				 = DateTimeFormat::DatePickerToDate($pdate);
		$time				 = DateTime::createFromFormat('h:i A', $ptime)->format('H:i:00');
		$dateTime			 = $date . ' ' . $time;
		$dateTime			 = new DateTime($dateTime);
		$dateTime->add(new DateInterval('PT' . $rutModel->rut_estm_time . 'M'));
		$seconds			 = $dateTime->getTimestamp();
		$rounded_seconds	 = ceil($seconds / (15 * 60)) * (15 * 60);
		$dateTime->setTimestamp($rounded_seconds);
		$minTime			 = $dateTime->format('Y-m-d H:i:s');
		$date				 = DateTimeFormat::DateTimeToDatePicker($minTime);
		$time				 = $dateTime->format("h:i A");
		$this->brt_min_date	 = $dateTime->format('Y-m-d');
	}

	public function linkBooking($model)
	{
		$routeModel = BookingRoute::model()->findByPk($model->bkg_id);
		if (!$routeModel)
		{
			$routeModel = new BookingRoute();
		}
		$routeModel->brt_bkg_id			 = $model->bkg_id;
		$routeModel->brt_from_city_id	 = $model->bkg_from_city_id;
		$routeModel->brt_to_city_id		 = $model->bkg_to_city_id;
		$routeModel->brt_from_location	 = $model->bkg_pickup_address;
		$routeModel->brt_to_location	 = $model->bkg_drop_address;

		$routeModel->brt_from_pincode	 = $model->bkg_pickup_pincode;
		$routeModel->brt_to_pincode		 = $model->bkg_drop_pincode;

		$routeModel->brt_pickup_datetime	 = $model->bkg_pickup_date;
		$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($model->bkg_pickup_date);
		$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime($model->bkg_pickup_date));
		$routeModel->brt_trip_distance		 = $model->bkg_trip_distance;
		$routeModel->brt_trip_duration		 = $model->bkg_trip_duration;
		$routeModel->brt_status				 = $model->bkg_status;
		if ($routeModel->save())
		{
			return $routeModel->brt_id;
		}
	}

	public function getRouteNameByBookingId($bkgid)
	{
		$qry	 = "SELECT fct.cty_name fctName, tct.cty_name tctName
                    FROM booking_route
                         JOIN cities fct ON brt_from_city_id = fct.cty_id
                         JOIN cities tct ON brt_to_city_id = tct.cty_id
                    WHERE brt_bkg_id = :bkgid AND brt_active = 1";
		$routes	 = DBUtil::command($qry, DBUtil::SDB())->bindParam(':bkgid', $bkgid)->queryAll();
		$ctr	 = 0;
		foreach ($routes as $route)
		{
			$ctr = ($ctr + 1);
			$rut .= $route['fctName'] . ' - ';
			if (count($routes) == $ctr)
			{
				$rut .= $route['tctName'];
			}
		}
		return $rut;
	}

	public function saveRouteDataForEditBooking($bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if ($model->bkg_booking_type == 1)
		{
			try
			{
				$bookingRouteModel						 = $this->find("brt_bkg_id='$model->bkg_id'");
				$bookingRouteModel->scenario			 = 'admCreate';
				$bookingRouteModel->brt_from_city_id	 = $model->bkg_from_city_id;
				$bookingRouteModel->brt_to_location		 = $model->bkg_drop_address;
				$bookingRouteModel->brt_from_location	 = $model->bkg_pickup_address;
				$bookingRouteModel->brt_to_city_id		 = $model->bkg_to_city_id;
				$bookingRouteModel->brt_pickup_datetime	 = $model->bkg_pickup_date;
				$bookingRouteModel->save();
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		if ($model->multicityjsondata != '""' && ($model->bkg_booking_type == 2 || $model->bkg_booking_type == 3))
		{
			$bookingRouteModel	 = $this->findAll("brt_bkg_id='$model->bkg_id'");
			$ctr				 = 0;
			foreach ($bookingRouteModel as $value)
			{
				$value->scenario = 'admCreate';
				if ($ctr == 0)
				{
					$bcb_id = $value->brt_bcb_id;
				}
				$ctr++;
			}

			$routesArr	 = json_decode($model->multicityjsondata);
			$i			 = 0;
			do
			{
				try
				{
					$this->scenario				 = 'admCreate';
					$this->brt_bkg_id			 = $model->bkg_id;
					$this->brt_status			 = $model->bkg_status;
					$this->brt_bcb_id			 = $bcb_id;
					$this->brt_from_city_id		 = $routesArr[$i]->pickup_city;
					$this->brt_to_city_id		 = $routesArr[$i]->drop_city;
					$this->brt_from_location	 = $routesArr[$i]->pickup_address;
					$this->brt_to_location		 = $routesArr[$i]->drop_address;
					$this->brt_pickup_datetime	 = $routesArr[$i]->date;
					$this->brt_trip_distance	 = $routesArr[$i]->distance;
					$this->brt_trip_duration	 = $routesArr[$i]->duration;
					$this->brt_from_pincode		 = $routesArr[$i]->pickup_pin;
					$this->brt_to_pincode		 = $routesArr[$i]->drop_pin;
					if ($this->validate())
					{
						$this->save();
					}
				}
				catch (Exception $e)
				{
					throw $e;
				}

				$i++;
			}
			while ($i < count($routesArr));
		}
		return $bookingRouteModel;
	}

	public function copybookingroute($bkid)
	{
		$arrMultiCity	 = [];
		$routeModel		 = $this->findAll('brt_bkg_id=:id', ['id' => $bkid]);

		if ($newmodel->bkg_booking_type != 1)
		{
			foreach ($routeModel as $key => $value)
			{
				$arrMultiCity[$key]	 = [
					"pickup_city"			 => $value->brt_from_city_id,
					"drop_city"				 => $value->brt_to_city_id,
					"pickup_city_name"		 => Cities::getName($value->brt_from_city_id),
					"drop_city_name"		 => Cities::getName($value->brt_to_city_id),
					"pickup_date"			 => DateTimeFormat::DateTimeToDatePicker($value->brt_pickup_datetime),
					"pickup_time"			 => DateTimeFormat::DateTimeToTimePicker($value->brt_pickup_datetime),
					"date"					 => $value->brt_pickup_datetime,
					"duration"				 => $value->brt_trip_duration,
					"distance"				 => $value->brt_trip_distance,
//					"pickup_pin"			 => $value->brt_from_pincode,
//					"drop_pin"				 => $value->brt_to_pincode,
					"pickup_address"		 => $value->brt_from_location,
					"drop_address"			 => $value->brt_to_location,
					"pickup_loc_lat"		 => $value->brt_from_latitude,
					"pickup_loc_long"		 => $value->brt_from_longitude,
					"pickup_loc_place"		 => $value->brt_from_place_id,
					"pickup_loc_FAdd"		 => $value->brt_from_formatted_address,
					"drop_loc_lat"			 => $value->brt_to_latitude,
					"drop_loc_long"			 => $value->brt_to_longitude,
					"drop_loc_place"		 => $value->brt_to_place_id,
					"drop_loc_FAdd"			 => $value->brt_to_formatted_address,
					'pickup_cty_lat'		 => $value->brtFromCity->cty_lat,
					'pickup_cty_long'		 => $value->brtFromCity->cty_long,
					'drop_cty_lat'			 => $value->brtToCity->cty_lat,
					'drop_cty_long'			 => $value->brtToCity->cty_long,
					'pickup_cty_bounds'		 => $value->brtFromCity->cty_bounds,
					'drop_cty_bounds'		 => $value->brtToCity->cty_bounds,
					'pickup_cty_radius'		 => $value->brtFromCity->cty_radius,
					'drop_cty_radius'		 => $value->brtToCity->cty_radius,
					'pickup_cty_is_airport'	 => $value->brtFromCity->cty_is_airport,
					'drop_cty_is_airport'	 => $value->brtToCity->cty_is_airport
				];
				$fbounds			 = $value->brtFromCity->cty_bounds;
				$fboundArr			 = CJSON::decode($fbounds);
				$tbounds			 = $value->brtToCity->cty_bounds;
				$tboundArr			 = CJSON::decode($tbounds);

				$arrMultiCity[$key]['pickup_cty_ne_lat']	 = $fboundArr['northeast']['lat'];
				$arrMultiCity[$key]['pickup_cty_ne_long']	 = $fboundArr['northeast']['lng'];
				$arrMultiCity[$key]['pickup_cty_sw_lat']	 = $fboundArr['southwest']['lat'];
				$arrMultiCity[$key]['pickup_cty_sw_long']	 = $fboundArr['southwest']['lng'];

				$arrMultiCity[$key]['drop_cty_ne_lat']	 = $tboundArr['northeast']['lat'];
				$arrMultiCity[$key]['drop_cty_ne_long']	 = $tboundArr['northeast']['lng'];
				$arrMultiCity[$key]['drop_cty_sw_lat']	 = $tboundArr['southwest']['lat'];
				$arrMultiCity[$key]['drop_cty_sw_long']	 = $tboundArr['southwest']['lng'];
			}
			$multijsondata	 = json_encode($arrMultiCity);
			$arrjsondata	 = json_decode($multijsondata);
		}
		return $arrjsondata;
	}

	public function setTripRouteInfo($routeModel, $routeType, $pickupDtTime = '')
	{
		$Arrmulticity	 = [];
		$currentDt		 = date('Y-m-d H:i:s', strtotime('+4 hour'));
		if ($routeType == 5)
		{
			$defaultPackagePickupTime	 = Yii::app()->params['defaultPackagePickupTime'];
			$currentDt					 = date("Y-m-d $defaultPackagePickupTime", strtotime('+4 DAYS'));
		}
		if ($pickupDtTime != '')
		{
			$currentDt = $pickupDtTime;
		}
		$prevDuration	 = 0;
		$noFactor		 = 0;

		foreach ($routeModel as $key => $value)
		{
			if ($routeType == 5)
			{

				$nightCount	 = $value->pcd_night_serial;
				$dayCount	 = ($key == 0 && $nightCount > 0) ? 0 : $value->pcd_day_serial - 1;
				$pickDate	 = date('Y-m-d H:i:s', strtotime($currentDt . ' + ' . $prevDuration . ' MINUTES'));

//$dayDiff			 = date('d', strtotime($pickDate)) - date('d', strtotime($currentDt)) +$dayDiff1 ;

				$Arrmulticity[$key] = [
					"pickup_city"			 => $value->pcd_from_city,
					"pickup_city_name"		 => $value->pcdFromCity->cty_name,
					"drop_city"				 => $value->pcd_to_city,
					"drop_city_name"		 => $value->pcdToCity->cty_name,
					"pickup_address"		 => $value->pcd_from_location,
					"drop_address"			 => $value->pcd_to_location,
					"date"					 => $pickDate,
					"distance"				 => $value->pcd_trip_distance,
					"duration"				 => $value->pcd_trip_duration,
					"pickup_date"			 => DateTimeFormat::DateTimeToDatePicker($pickDate),
					"pickup_time"			 => date('h:i A', strtotime($pickDate)),
					"daycount"				 => $value->pcd_day_serial,
					"nightcount"			 => $nightCount,
					"packagedelID"			 => $value->pcd_id,
					"pickup_cty_is_airport"	 => $value->pcdFromCity->cty_is_airport,
					"drop_cty_is_airport"	 => $value->pcdToCity->cty_is_airport,
					"pickup_cty_is_poi"		 => $value->pcdFromCity->cty_is_poi,
					"drop_cty_is_poi"		 => $value->pcdToCity->cty_is_poi,
					"pickup_cty_lat"		 => $value->pcdFromCity->cty_lat,
					"pickup_cty_long"		 => $value->pcdFromCity->cty_long,
					"drop_cty_lat"			 => $value->pcdToCity->cty_lat,
					"drop_cty_long"			 => $value->pcdToCity->cty_long,
					"pickup_cty_bounds"		 => $value->pcdFromCity->cty_bounds,
					"drop_cty_bounds"		 => $value->pcdToCity->cty_bounds,
					"pickup_cty_radius"		 => $value->pcdFromCity->cty_radius,
					"drop_cty_radius"		 => $value->pcdToCity->cty_radius,
				];
//				$dayDiff 			 = date('d', strtotime($pickDate)) - date('d', strtotime($currentDt))  ;
//				$nightCount1		 = $value->pcd_day_serial - $dayDiff - 1 + $value->pcd_night_serial;

				$fbounds = $value->pcdFromCity->cty_bounds;
				if ($fbounds != '')
				{
					$fboundArr									 = CJSON::decode($fbounds);
					$Arrmulticity[$key]['pickup_cty_ne_lat']	 = $fboundArr['northeast']['lat'];
					$Arrmulticity[$key]['pickup_cty_ne_long']	 = $fboundArr['northeast']['lng'];
					$Arrmulticity[$key]['pickup_cty_sw_lat']	 = $fboundArr['southwest']['lat'];
					$Arrmulticity[$key]['pickup_cty_sw_long']	 = $fboundArr['southwest']['lng'];
				}
				$tbounds = $value->pcdToCity->cty_bounds;
				if ($tbounds != '')
				{
					$tboundArr								 = CJSON::decode($tbounds);
					$Arrmulticity[$key]['drop_cty_ne_lat']	 = $tboundArr['northeast']['lat'];
					$Arrmulticity[$key]['drop_cty_ne_long']	 = $tboundArr['northeast']['lng'];
					$Arrmulticity[$key]['drop_cty_sw_lat']	 = $tboundArr['southwest']['lat'];
					$Arrmulticity[$key]['drop_cty_sw_long']	 = $tboundArr['southwest']['lng'];
				}

//				$currentDt=$pickDate;
				$noFactor = $nightCount;

				$durationVal	 = ($noFactor == 0) ? $value->pcd_trip_duration + $durationVal : max([$durationVal, (($noFactor + $dayCount) * 24 * 60)]);
				$prevDuration	 = max($value->pcd_trip_duration, $durationVal);
			}
			else
			{

				$Arrmulticity[$key] = ["pickup_city"		 => $value->brt_from_city_id,
					"pickup_city_name"	 => $value->brtFromCity->cty_name,
					"drop_city"			 => $value->brt_to_city_id,
					"drop_city_name"	 => $value->brtToCity->cty_name,
					"pickup_address"	 => $value->brt_from_location,
					"drop_address"		 => $value->brt_to_location,
					"date"				 => $value->brt_pickup_datetime,
					"distance"			 => $value->brt_trip_distance,
					"duration"			 => $value->brt_trip_duration,
					"pickup_date"		 => DateTimeFormat::DateTimeToDatePicker($value->brt_pickup_datetime),
					"pickup_time"		 => date('h:i A', $value->brt_pickup_datetime),
				];
			}
		}
		$multijsondata	 = CJSON::encode($Arrmulticity);
		$multijsondata	 = CJSON::decode($multijsondata, false);

		return $multijsondata;
	}

	public function setBookingRouteInfo($model, $multicityjson = 0)
	{
		if ($model->bookingRoutes)
		{
			if (in_array($model->bkg_booking_type,[1,2,3,4,5,8,9,10,11,15]))
			{
				$routesArr = json_decode($multicityjson);
				foreach ($model->bookingRoutes as $i => $brtRoute)
				{
//					$rModel				 = new BookingRoute();
					$brtRoute->brt_bkg_id		 = $model->bkg_id;
					$brtRoute->brt_from_city_id	 = $routesArr[$i]->pickup_city;
					$brtRoute->brt_to_city_id	 = $routesArr[$i]->drop_city;

					$brtRoute->brt_pickup_datetime	 = $routesArr[$i]->date;
					$brtRoute->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($routesArr[$i]->date);
					$brtRoute->brt_pickup_date_time	 = date('h:i A', strtotime($routesArr[$i]->date));
					$brtRoute->brt_trip_distance	 = $routesArr[$i]->distance;
					$brtRoute->brt_trip_duration	 = $routesArr[$i]->duration;
					if ($routesArr[$i]->pickup_address != '')
					{
						$brtRoute->brt_from_location = $routesArr[$i]->pickup_address;
					}
					if ($routesArr[$i]->drop_address != '')
					{
						$brtRoute->brt_to_location = $routesArr[$i]->drop_address;
					}
//					$brtRoute->attributes	 = $brtRoute;
					$brtRoute->brt_status = 2;

					if ($brtRoute->validate())
					{
						if (!$brtRoute->save())
						{
							throw new Exception("Failed to Save Route Data");
						}
					}
				}
			}
		}
		else
		{


			if ($model->bkg_booking_type == 2 || $model->bkg_booking_type == 3 || $model->bkg_booking_type == 8)
			{

				$routesArr = json_decode($multicityjson);
			}
			$i		 = 0;
			$brtCnt	 = sizeof($routesArr);
			if ($brtCnt > 1)
			{
				if ($routesArr[0]->pickup_address == '' && $model->bkg_pickup_address != '')
				{
					$routesArr[0]->pickup_address = $model->bkg_pickup_address;
				}
				if ($routesArr[$brtCnt - 1]->drop_address == '' && $model->bkg_drop_address != '')
				{
					$routesArr[$brtCnt - 1]->drop_address = $model->bkg_drop_address;
				}
			}
			do
			{
				try
				{
					$rModel							 = new BookingRoute();
					$rModel->brt_bkg_id				 = $model->bkg_id;
					$rModel->brt_from_city_id		 = $model->bkg_from_city_id;
					$rModel->brt_to_city_id			 = $model->bkg_to_city_id;
					$rModel->brt_from_location		 = $model->bkg_pickup_address;
					$rModel->brt_to_location		 = $model->bkg_drop_address;
					$rModel->brt_pickup_datetime	 = $model->bkg_pickup_date;
					$rModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($model->bkg_pickup_date);
					$rModel->brt_pickup_date_time	 = date('h:i A', strtotime($model->bkg_pickup_date));
					$rModel->brt_trip_distance		 = $model->bkg_trip_distance;
					$rModel->brt_trip_duration		 = $model->bkg_trip_duration;
					$rModel->brt_from_latitude		 = $model->bkg_pickup_lat;
					$rModel->brt_from_longitude		 = $model->bkg_pickup_long;
					$rModel->brt_to_latitude		 = $model->bkg_dropup_lat;
					$rModel->brt_to_longitude		 = $model->bkg_dropup_long;
//					$rModel->brt_from_pincode		 = $model->bkg_pickup_pincode;
//					$rModel->brt_to_pincode			 = $model->bkg_drop_pincode;
					$rModel->brt_status				 = 2;
					if ($model->bkg_booking_type == 3 || $model->bkg_booking_type == 2 || $model->bkg_booking_type == 5 || $model->bkg_booking_type == 8)
					{
						$rModel->brt_from_city_id		 = $routesArr[$i]->pickup_city;
						$rModel->brt_to_city_id			 = $routesArr[$i]->drop_city;
						$rModel->brt_from_location		 = $routesArr[$i]->pickup_address;
						$rModel->brt_to_location		 = $routesArr[$i]->drop_address;

						if(Cities::checkAirport($rModel->brt_from_city_id))
						{
							$rModel->brt_from_location		 = Cities::getDisplayName($rModel->brt_from_city_id);
						}
						if(Cities::checkAirport($rModel->brt_to_city_id))
						{
							$rModel->brt_to_location		 = Cities::getDisplayName($rModel->brt_to_city_id);
						}

						$rModel->brt_pickup_datetime	 = $routesArr[$i]->date;
						$rModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($routesArr[$i]->date);
						$rModel->brt_pickup_date_time	 = date('h:i A', strtotime($routesArr[$i]->date));
						$rModel->brt_trip_distance		 = $routesArr[$i]->distance;
						$rModel->brt_trip_duration		 = $routesArr[$i]->duration;
						$rModel->brt_from_pincode		 = $routesArr[$i]->pickup_pin;
						$rModel->brt_to_pincode			 = $routesArr[$i]->drop_pin;
					}
					if ($rModel->validate())
					{
						if (!$rModel->save())
						{
							throw new Exception("Failed to Save Route Data");
						}
					}
				}
				catch (Exception $e)
				{
					throw $e;
				}
				$i++;
			}
			while ($i < count($routesArr));
		}
	}

	public function setRouteInfoForLeadToBooking($route_data)
	{
		foreach ($route_data as $k => $v)
		{
			$bookingRoute						 = new BookingRoute();
			$bookingRoute->attributes			 = $v;
			$bookingRoute->brt_from_location	 = '';
			$bookingRoute->brt_to_location		 = '';
			$bookingRoute->brt_from_latitude	 = $bookingRoute->brtFromCity->cty_lat;
			$bookingRoute->brt_from_longitude	 = $bookingRoute->brtFromCity->cty_long;
			$bookingRoute->brt_to_latitude		 = $bookingRoute->brtToCity->cty_lat;
			$bookingRoute->brt_to_longitude		 = $bookingRoute->brtToCity->cty_long;
			$bookingRoutes[]					 = $bookingRoute;
		}
		return $bookingRoutes;
	}

	public function getReturnPickupDateTime($pickupCity, $dropCity, $returnTPDate, $returnTPTime = '')
	{
		$defaultReturnTime	 = Yii::app()->params['defaultReturnEndTime'];
		$returnTime			 = ( $returnTPTime == '') ? $defaultReturnTime : DateTime::createFromFormat('h:i A', $returnTPTime)->format('H:i:00');
		$returnDate			 = DateTimeFormat::DatePickerToDate($returnTPDate);
		$returnDateTime		 = $returnDate . ' ' . $returnTime;
		return self::populateReturnPickupTime($pickupCity, $dropCity, $returnDateTime);
	}

	public static function populateReturnPickupTime($pickupCity, $dropCity, $returnTime)
	{
		$routeDuration	 = Route::model()->getRouteDurationbyCities($dropCity, $pickupCity);
		$pickupDateTime	 = date('Y-m-d H:i:s', strtotime($returnTime . ' -' . $routeDuration . ' minute'));
		return $pickupDateTime;
	}

	public function setBookingCabStartEndTime($bcbId, $bkgId)
	{
		if ($bcbId != '' && $bkgId != '')
		{
			$sql	 = "SELECT
					MAX(date_add(brt_pickup_datetime,INTERVAL brt_trip_duration MINUTE)) drop_time,
					MIN(brt_pickup_datetime) pickup_time
					FROM booking_route
					WHERE brt_bkg_id IN($bkgId) AND brt_active=1
					ORDER BY brt_pickup_datetime desc";
			$result	 = DBUtil::queryRow($sql);
			if ($result != "")
			{
				$bookingCab					 = BookingCab::model()->findByPk($bcbId);
				$bookingCab->bcb_start_time	 = $result['pickup_time'];
				$bookingCab->bcb_end_time	 = $result['drop_time'];
				$bookingCab->save();
			}
		}
	}

	public function getRouteNameByBooking($bkgId)
	{
		$sql = "select concat(ct1.cty_name,' - ',group_concat(ct2.cty_name separator ' - ')) rt from booking_route
				join cities ct1 ON ct1.cty_id = booking_route.brt_from_city_id
				join cities ct2 ON ct2.cty_id = booking_route.brt_to_city_id
				where brt_bkg_id = $bkgId AND brt_active=1 ORDER BY brt_id ";
		return DBUtil::command($sql)->queryScalar();
	}

	public function setQuoteSession()
	{
		/** @var CHttpSession $session */
		$session = Yii::app()->session;
		$quote	 = $session->get("QuoteRoute");
		if ($quote == false)
		{
			$quote = [];
		}
		$quote[$this->getKey()] = 1;
		$session->add("QuoteRoute", $quote);
	}

	public function checkQuoteSession()
	{
		$success = false;
		/** @var CHttpSession $session */
		$session = Yii::app()->session;
		$quote	 = $session->get("QuoteRoute");
		if ($quote == false)
		{
			goto end;
		}
		$success = isset($quote[$this->getKey()]);

		end:
		return $success;
	}

	public function clearQuoteSession()
	{
		/** @var CHttpSession $session */
		$session = Yii::app()->session;
		$data	 = $session->remove('QuoteRoute');
	}

	public function getKey()
	{
		$key = $this->brt_from_city_id . "_" . $this->brt_to_city_id . "_" . $this->brt_pickup_datetime;
		return $key;
	}

	function setBcbIdForNewBooking($bkgId, $bcbId)
	{
		$sql	 = "UPDATE booking_route SET brt_bcb_id={$bcbId} WHERE brt_bkg_id={$bkgId}";
		$result	 = DBUtil::command($sql)->execute();
		return $result;
	}

	function updateBrtPickupDateTime($brtId, $brtPickupDateTime)
	{
		try
		{
			if ($brtId && $brtPickupDateTime)
			{
				$sql	 = "UPDATE booking_route SET brt_pickup_datetime='$brtPickupDateTime' WHERE brt_id= $brtId";
				$result	 = DBUtil::command($sql)->execute(); //DBUtil::command($sql)->execute();
//                if ($result)
//                {
//                    return $result;
//                }
			}
			else
			{
				throw new Exception("Unknown exception");
			}
		}
		catch (Exception $e)
		{
			throw new Exception("Unknown exception");
			Logger::create('Exception:--------  ' . $e->getMessage(), CLogger::LEVEL_TRACE);
		}
		return $result;
	}

	public function populateRouteByShuttleId($sltid)
	{
		$sltDetail							 = Shuttle::model()->getDetailbyId($sltid);
		$bookingRoute						 = new BookingRoute();
		$bookingRoute->with('brtFromCity,brtToCity');
		$bookingRoute->brt_from_city_id		 = $sltDetail['slt_from_city'];
		$bookingRoute->brt_to_city_id		 = $sltDetail['slt_to_city'];
		$bookingRoute->brt_from_location	 = $sltDetail['slt_pickup_location'];
		$bookingRoute->brt_from_latitude	 = $sltDetail['slt_pickup_lat'];
		$bookingRoute->brt_from_longitude	 = $sltDetail['slt_pickup_long'];
		$bookingRoute->brt_to_location		 = $sltDetail['slt_drop_location'];
		$bookingRoute->brt_to_latitude		 = $sltDetail['slt_drop_lat'];
		$bookingRoute->brt_to_longitude		 = $sltDetail['slt_drop_long'];
		$bookingRoute->brt_trip_distance	 = $sltDetail['trip_distance'];
		$bookingRoute->brt_trip_duration	 = $sltDetail['trip_duration'];
		$bookingRoute->brt_pickup_datetime	 = $sltDetail['slt_pickup_datetime'];
		$bookingRoutes[]					 = $bookingRoute;
		return $bookingRoutes;
	}

	public static function populateTripduration($routedata)
	{
		$cnt	 = count($routedata);
		$diff	 = [];
		$d1;
		$d2;
		foreach ($routedata as $key => $b)
		{
			if ($key == 0)
			{
				$d1 = new DateTime($b->brt_pickup_datetime);
			}
			if ($key == $cnt - 1)
			{
				if (trim($b->est_date) != "" && date('Y', strtotime($b->est_date)) != '1970')
				{
					$d2 = new DateTime($b->est_date);
				}
				else
				{
					$est_date	 = date('Y-m-d H:i:s', strtotime($b->brt_pickup_datetime . '+ ' . $b->brt_trip_duration . ' minute'));
					$d2			 = new DateTime($est_date);
				}
			}
		}
		$diff = $d2->diff($d1);
		return (($diff->d > 0) ? $diff->d . ' day(s) ' : '') . $diff->h . ' hr(s) ' . (($diff->i > 0) ? $diff->i . ' min(s)' : '') . " (+/- 30 mins for traffic)";
	}

	public static function formatTripduration($time, $type = 0)
	{
		$hours	 = floor($time / 60);
		$mins	 = $time % 60;
		$space	 = ($type > 0) ? "" : "<br/>";
		return (($hours > 0) ? $hours . " hr " : "") . (($mins > 0) ? " $space " . $mins . " mins " : "");
	}

	public function populateRouteByPackageId($pckid, $formatPickUpDt = '', $alldetails = false)
	{
		$packagemodel	 = Package::model()->findByPk($pckid);
		$routeModel		 = $packagemodel->packageDetails;

		$defaultPackagePickupTime	 = Yii::app()->params['defaultPackagePickupTime'];
		$currentDt					 = date("Y-m-d $defaultPackagePickupTime", strtotime('+4 DAYS'));

		if ($formatPickUpDt != '')
		{
			$currentDt = $formatPickUpDt;
		}
		$prevDuration	 = 0;
		$noFactor		 = 0;

		foreach ($routeModel as $key => $value)
		{
			$nightCount	 = $value->pcd_night_serial;
			$dayCount	 = ($key == 0 && $nightCount > 0) ? 0 : $value->pcd_day_serial - 1;
			$pickDate	 = date('Y-m-d H:i:s', strtotime($currentDt . ' + ' . $prevDuration . ' MINUTES'));

			$bookingRoute					 = new BookingRoute();
			$bookingRoute->with('brtFromCity,brtToCity');
			$bookingRoute->brt_pcd_id		 = $value->pcd_id;
			$bookingRoute->brt_from_city_id	 = $value->pcd_from_city;
			$bookingRoute->brt_to_city_id	 = $value->pcd_to_city;
			$bookingRoute->brt_trip_distance = $value->pcd_trip_distance;
			$bookingRoute->brt_trip_duration = $value->pcd_trip_duration;
			if ($alldetails)
			{
				$bookingRoute->brt_from_city_name	 = $value->pcdFromCity->cty_name;
				$bookingRoute->brt_to_city_name		 = $value->pcdToCity->cty_name;
				$bookingRoute->brt_from_location	 = $value->pcd_from_location;
				$bookingRoute->brt_from_latitude	 = $value->pcdFromCity->cty_lat;
				$bookingRoute->brt_from_longitude	 = $value->pcdFromCity->cty_long;
				$bookingRoute->brt_to_location		 = $value->pcd_to_location;
				$bookingRoute->brt_to_latitude		 = $value->pcdToCity->cty_lat;
				$bookingRoute->brt_to_longitude		 = $value->pcdToCity->cty_long;
			}
			$bookingRoute->brt_pickup_datetime	 = $pickDate;
			$bookingRoute->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($pickDate);
			$bookingRoute->brt_pickup_date_time	 = date('h:i A', strtotime($pickDate));

			$noFactor = $nightCount;

			$durationVal	 = ($noFactor == 0) ? $value->pcd_trip_duration + $durationVal : max([$durationVal, (($noFactor + $dayCount) * 24 * 60)]);
			$prevDuration	 = max($value->pcd_trip_duration, $durationVal);
			$bookingRoutes[] = $bookingRoute;
		}
		return $bookingRoutes;
	}

	public function setRouteForAirportTransfer($brtModel)
	{
		#print_r($routes[0]);
		$result = [];
		if ($brtModel->brt_from_location == "")
		{
			$result[0] = ["Source Address can't be blank "];
		}
		if ($brtModel->brt_to_location == "")
		{
			$result[1] = ["Destination Address can't be blank "];
		}

		if ($brtModel->brt_from_is_airport == "1")
		{
			$airportRadius = Cities::getCtyRadiusByCtyId($brtModel->brt_from_city_id);
			goto end;
		}
		if ($brtModel->brt_to_is_airport == "1")
		{
			$airportRadius = Cities::getCtyRadiusByCtyId($brtModel->brt_to_city_id);
			goto end;
		}
		$result[3] = ['Within source or destination one should be airport'];
		end:

		#check distance
		$distance	 = ROUND(SQRT(POW(69.1 * ($brtModel->brt_from_latitude - $brtModel->brt_to_latitude), 2) + POW(69.1 * ($brtModel->brt_to_longitude - $brtModel->brt_from_longitude) * COS($brtModel->brt_from_latitude / 57.3), 2)), 2);
		$dis		 = $distance * 1.60934;
		if ($dis > $airportRadius && empty($result))
		{
			$result['bkType'] = ['1'];
		}
		else if ($dis < 2)
		{
			$result[4] = ['Cab not available within that distance'];
		}
		return json_encode($result);
	}

	public function populateByCavData($cavData,$estimatedPickupTime='')
	{

		$bookingRoute						 = new BookingRoute();
		$bookingRoute->with('brtFromCity,brtToCity');
		$bookingRoute->brt_from_city_id		 = $cavData['cav_from_city'];
		$bookingRoute->brt_to_city_id		 = $cavData['cav_to_cities'];
		$bookingRoute->brt_pickup_datetime	 = ($estimatedPickupTime!='')?$estimatedPickupTime:$cavData['cav_date_time'];
		if ($bookingRoute->brtFromCity->cty_is_airport == 1)
		{
			$bookingRoute->brt_from_location		 = $cavData['cav_from_city_name'];
			$bookingRoute->brt_from_city_is_airport	 = 1;
			$bookingRoute->brt_from_city_is_poi		 = 1;
			$bookingRoute->brt_from_latitude		 = $bookingRoute->brtFromCity->cty_lat;
			$bookingRoute->brt_from_longitude		 = $bookingRoute->brtFromCity->cty_long;
		}
		if ($bookingRoute->brtToCity->cty_is_airport == 1)
		{
			$bookingRoute->brt_to_location			 = $cavData['cav_to_city_name'];
			$bookingRoute->brt_to_city_is_airport	 = 1;
			$bookingRoute->brt_to_city_is_poi		 = 1;
			$bookingRoute->brt_to_latitude			 = $bookingRoute->brtToCity->cty_lat;
			$bookingRoute->brt_to_longitude			 = $bookingRoute->brtToCity->cty_long;
		}
		$bookingRoutes[] = $bookingRoute;
		return $bookingRoutes;
	}

	public function populateCities()
	{
		if (($this->brt_from_latitude == null || $this->brt_from_latitude == '0') || ($this->brt_from_longitude == null || $this->brt_from_longitude == '0'))
		{
//			if($this->brt_from_city_id == null)
//			{
//				return false;
//			}

			$cityModel = \Cities::model()->findByPk($this->brt_from_city_id);
			if (!empty($cityModel))
			{
				$this->brt_from_latitude	 = $cityModel->cty_lat;
				$this->brt_from_longitude	 = $cityModel->cty_long;
			}
		}

		if (($this->brt_to_latitude == null || $this->brt_to_latitude == '0') || ($this->brt_to_longitude == null || $this->brt_to_longitude == '0'))
		{
//			if($this->brt_to_city_id == null)
//			{
//				return false;
//			}

			$cityModel = \Cities::model()->findByPk($this->brt_to_city_id);
			if (!empty($cityModel))
			{
				$this->brt_to_latitude	 = $cityModel->cty_lat;
				$this->brt_to_longitude	 = $cityModel->cty_long;
			}
		}

		$sourceCity				 = $destCity				 = null;
		$sourcePlace			 = Stub\common\Place::init($this->brt_from_latitude, $this->brt_from_longitude);
		$destPlace				 = Stub\common\Place::init($this->brt_to_latitude, $this->brt_to_longitude);
		$sourcePlace->address	 = $this->brt_from_location;
		$destPlace->address		 = $this->brt_to_location;
		$ctyModel				 = Cities::getByGeoBounds($sourcePlace, 15);
		if ($ctyModel && $ctyModel->is_partial == 0)
		{
			$sourceCity = $ctyModel->cty_id;
			goto populateDestionationBound;
		}

		$ctyModel = Cities::getByNearestBound($sourcePlace);
		if ($ctyModel && $ctyModel->is_partial == 0)
		{
			$sourceCity = $ctyModel->cty_id;
			goto populateDestionationBound;
		}

		populateDestionationBound:
		$ctyModel = Cities::getByGeoBounds($destPlace, 15);
		if ($ctyModel && $ctyModel->is_partial == 0)
		{
			$destCity = $ctyModel->cty_id;
			goto skipDestionationNearestBound;
		}
		$ctyModel = Cities::getByNearestBound($destPlace);
		if ($ctyModel && $ctyModel->is_partial == 0)
		{
			$destCity = $ctyModel->cty_id;
		}

		skipDestionationNearestBound:
		if (empty($sourceCity))
		{
			$srcLtLngModel	 = LatLong::model()->getDetailsByPlace($sourcePlace);
			$sourceCity		 = $srcLtLngModel->ltg_city_id;
		}

		if (empty($destCity))
		{
			$dstLtLngModel	 = LatLong::model()->getDetailsByPlace($destPlace);
			$destCity		 = $dstLtLngModel->ltg_city_id;
		}

		$this->brt_from_city_id	 = $sourceCity;
		$this->brt_to_city_id	 = $destCity;

		$result = Route::model()->populate($this->brt_from_city_id, $this->brt_to_city_id);
		if ($result["success"])
		{
			return $result["model"];
		}
		return false;
	}

	public function isAirportTransfer()
	{
		switch (true)
		{
			case $this->brtFromCity->cty_is_airport:
				$airport = $this->brt_from_city_id;
				$radius	 = $this->brtFromCity->cty_radius;
				$type	 = 1;
				break;
			case $this->brtToCity->cty_is_airport:
				$airport = $this->brt_to_city_id;
				$radius	 = $this->brtToCity->cty_radius;
				$type	 = 2;
				break;
			default:
				$airport = 0;
				$radius	 = 0;
				$type	 = 0;
				break;
		}
		$success = false;
		if ($this->brt_trip_distance > 0)
		{
			$distance = $this->brt_trip_distance;
			goto nextLine;
		}
		$distance = Filter::calculateDistance($this->brtFromCity->cty_lat, $this->brtFromCity->cty_long, $this->brtToCity->cty_lat, $this->brtToCity->cty_long);

		nextLine:
		if ($airport > 0 && $radius >= $distance)
		{
			$this->transferType	 = $type;
			$this->tripType		 = 4;
			$success			 = true;
		}
		return $success;
	}

	public function isAirportPickDrop()
	{
		$isAirportPickupDrop = ($this->brtFromCity->cty_is_airport == 1 || $this->brtToCity->cty_is_airport == 1);
		return $isAirportPickupDrop;
	}

	/** @param BookingRoute[] $routes */
	public function getReturnRoute($routes, $returnDateTime)
	{
		$startCity	 = $routes[0]->brt_from_city_id;
		$lastCity	 = $routes[count($routes) - 1]->brt_to_city_id;

		$model						 = new BookingRoute();
		$model->brt_from_city_id	 = $lastCity;
		$model->brt_to_city_id		 = $startCity;
		$model->brt_pickup_datetime	 = self::populateReturnPickupTime($lastCity, $startCity, $returnDateTime);
		$model->brt_to_latitude		 = $routes[0]->brt_to_latitude;
		$model->brt_to_longitude	 = $routes[0]->brt_to_longitude;
		$model->brt_to_location		 = $routes[0]->brt_to_location;
		$result						 = Route::model()->populate($lastCity, $startCity);
		if ($result['success'])
		{
			$routeModel					 = $result['model'];
			$model->brt_trip_distance	 = $routeModel->rut_actual_distance > 0 ? $routeModel->rut_actual_distance : $routeModel->rut_estm_distance;
		}
		return $model;
	}

	public static function addReturnRoute($routes)
	{
		$startCity	 = $routes[0]->brt_from_city_id;
		/** @var BookingRoute $lastRoute */
		$lastRoute	 = $routes[count($routes) - 1];
		$lastCity	 = $lastRoute->brt_to_city_id;

		if ($lastCity == $startCity)
		{
			goto end;
		}
		$lastRoute->calculateDistance();

		$model						 = new BookingRoute();
		$model->brt_from_city_id	 = $lastCity;
		$model->brt_to_city_id		 = $startCity;
		$model->brt_pickup_datetime	 = date('Y-m-d H:i:s', strtotime($lastRoute->brt_pickup_datetime . ' +' . $lastRoute->brt_trip_duration . ' minute'));
		$model->calculateDistance();

		$routes[] = $model;

		end:
		return $routes;
	}

	public function copyRoutes(Booking $bkgModel)
	{
		$bookingRouteArr = [];
		foreach ($bkgModel->bookingRoutes as $k => $brtModel)
		{
			$brtModelNew						 = new BookingRoute();
			$brtModelNew->attributes			 = $brtModel->attributes;
			unset($brtModelNew->brt_id);
			unset($brtModelNew->brt_bkg_id);
			unset($brtModelNew->brt_bcb_id);
			$brtModelNew->brt_pickup_date_date	 = date('d/m/Y', strtotime($brtModelNew->brt_pickup_datetime));
			$brtModelNew->brt_pickup_date_time	 = date('h:i A', strtotime($brtModelNew->brt_pickup_datetime));
			$brtModelNew->brt_is_copy_booking	 = 1;
			$bookingRouteArr[$k]				 = $brtModelNew;
		}
		return $bookingRouteArr;
	}

	public static function updateAddresses($bookingRoutes, $bkgModel)
	{
		$cntRoutes = count($bookingRoutes);
		foreach ($bookingRoutes as $key => $bookingRoute)
		{
			$bkgRoute						 = BookingRoute::model()->findByPk($bookingRoute['brt_id']);
			$bkgRoute->brt_from_latitude	 = ($key > 0) ? $bookingRoutes[($key - 1)]['brt_to_latitude'] : $bookingRoute['brt_from_latitude'];
			$bkgRoute->brt_from_location	 = ($key > 0) ? $bookingRoutes[($key - 1)]['brt_to_location'] : $bookingRoute['brt_from_location'];
			$bkgRoute->brt_from_longitude	 = ($key > 0) ? $bookingRoutes[($key - 1)]['brt_to_longitude'] : $bookingRoute['brt_from_longitude'];

			$bkgRoute->brt_to_latitude	 = $bookingRoute['brt_to_latitude'];
			$bkgRoute->brt_to_location	 = $bookingRoute['brt_to_location'];
			$bkgRoute->brt_to_longitude	 = $bookingRoute['brt_to_longitude'];

			if ($key == 0)
			{
				$bkgModel->bkg_pickup_address	 = $bookingRoute['brt_from_location'];
				$bkgModel->bkg_pickup_lat		 = $bkgRoute->brt_from_latitude;
				$bkgModel->bkg_pickup_long		 = $bkgRoute->brt_from_longitude;
				if (!$bkgModel->save())
				{
					throw new Exception("Error occurred while saving address");
				}
			}
			if ($key == ($cntRoutes - 1))
			{
				$bkgModel->bkg_drop_address	 = $bookingRoute['brt_to_location'];
				$bkgModel->bkg_dropup_lat	 = $bkgRoute->brt_to_latitude;
				$bkgModel->bkg_dropup_long	 = $bkgRoute->brt_to_longitude;
				if (!$bkgModel->save())
				{
					throw new Exception("Error occurred while saving address");
				}
			}
			if (!$bkgRoute->save())
			{
				throw new Exception("Error occurred while saving address");
			}
		}
	}

	/** @param Booking $bkgModel */
	public static function completeUpdateAddresses($bookingRoutes, $bkgModel, $isSave = true)
	{
		$cntRoutes	 = count($bookingRoutes);
		$bkgRoutes	 = $bkgModel->bookingRoutes;
		$lastRoute	 = null;
		$i			 = 0;
		foreach ($bkgRoutes as $bkgRoute)
		{
			if (isset($bookingRoutes[$bkgRoute->brt_id]))
			{
				$bookingRoute = $bookingRoutes[$bkgRoute->brt_id];

				if ($i == 0)
				{
					$bkgRoute->applyPlace($bookingRoute["from_place"], 1);

					$bkgModel->bkg_pickup_address	 = substr($bkgRoute->brt_from_location,0, 254);
					$bkgModel->bkg_pickup_lat		 = $bkgRoute->brt_from_latitude;
					$bkgModel->bkg_pickup_long		 = $bkgRoute->brt_from_longitude;
				}
				if ($lastRoute != null)
				{
					$bkgRoute->brt_from_latitude	 = $lastRoute->brt_to_latitude;
					$bkgRoute->brt_from_location	 = $lastRoute->brt_to_location;
					$bkgRoute->brt_from_longitude	 = $lastRoute->brt_to_longitude;
				}
				$bkgRoute->applyPlace($bookingRoute["to_place"], 2);
				if ($isSave)
				{
					$bkgRoute->saveAddress($bkgModel, $bkgRoute);
				}
			}
			$lastRoute = clone $bkgRoute;
			$i++;
		}

		if ($lastRoute != null)
		{
			$bkgModel->bkg_drop_address	 = substr($lastRoute->brt_to_location, 0, 254);
			$bkgModel->bkg_dropup_lat	 = $lastRoute->brt_to_latitude;
			$bkgModel->bkg_dropup_long	 = $lastRoute->brt_to_longitude;
			unset($bkgModel->bkg_route_city_names);
			if ($isSave)
			{
				$bkgRoute->saveAddress($bkgModel, null);
			}
		}
	}

	public static function updateDistance($bkgModel, $brtRoutes = [], $isSave = true)
	{
		$brtRoutes;
		$airportTransfer = ($bkgModel->bkg_booking_type == 4) ? true : false;
		$distance		 = 0;
		$duration		 = 0;
		$arrResult		 = [];
		if ($brtRoutes)
		{
			$bkgModel->bookingRoutes = $brtRoutes;
			$obj					 = new \Stub\common\Fare();
			$fare					 = $obj->setData($bkgModel->bkgInvoice);
			$arrResult				 = ['fare' => $fare];
		}

		foreach ($bkgModel->bookingRoutes as $key => $brtRoute)
		{

			$brtRoute->brt_trip_distance = 0;
			$brtRoute->brt_trip_duration = 0;
			$isAdrsUpdate				 = true;
			$brtRoute->calculateDistance(false, $airportTransfer, $isAdrsUpdate);

			$distance	 += $brtRoute->brt_trip_distance;
			$duration	 += $brtRoute->brt_trip_duration;
//			if (!$isSave)
//			{
//				goto returnBlock;
//			}
			if ($isSave)
			{
				if (!$brtRoute->save())
				{
					throw new Exception("Error occurred while saving address");
				}
			}
		}
		//returnBlock:
		if ($distance > $bkgModel->bkg_trip_distance)
		{

			if (!$isSave)
			{
				$additional_km		 = $distance - $bkgModel->bkg_trip_distance;
				$additional_charge	 = ROUND($additional_km * $bkgModel->bkgInvoice->bkg_rate_per_km_extra) | 0;
				$arrResult			 = BookingInvoice::populateOnAdditionalKm($distance, $duration, $bkgModel);
				$data				 = ['success'	 => true,
					'charge'	 => $additional_charge,
					'km'		 => $additional_km,
					'msg'		 => 'Distance increase by ' . $additional_km . 'km with' . $additional_charge . '/-',
					'rateChart'	 => $arrResult];
				return $data;
			}
			$arrResult = BookingInvoice::updateOnAdditionalKm($distance, $duration, $bkgModel);
		}

		return $arrResult;
	}

	public function addAddresses($bkgModel, $model)
	{
		$rteArray						 = [];
		$count							 = count($model->bookingRoutes);
		$bkgModel->bkg_pickup_address	 = $model->bookingRoutes[0]->brt_from_location;
		$bkgModel->bkg_pickup_lat		 = $model->bookingRoutes[0]->brt_from_latitude;
		$bkgModel->bkg_pickup_long		 = $model->bookingRoutes[0]->brt_from_longitude;
		$bkgModel->bkg_drop_address		 = $model->bookingRoutes[$count - 1]->brt_to_location;
		$bkgModel->bkg_dropup_lat		 = $model->bookingRoutes[$count - 1]->brt_to_latitude;
		$bkgModel->bkg_dropup_long		 = $model->bookingRoutes[$count - 1]->brt_to_longitude;
		if (!$bkgModel->save())
		{
			throw new Exception("Error occurred while saving address");
		}
		foreach ($bkgModel->bookingRoutes as $key => $bookingRoute)
		{

			$routeModel						 = BookingRoute::model()->findByPk($bookingRoute->brt_id);
			$routeModel->brt_from_location	 = $bkgModel->bkg_pickup_address;
			$routeModel->brt_from_latitude	 = $bkgModel->bkg_pickup_lat;
			$routeModel->brt_from_longitude	 = $bkgModel->bkg_pickup_long;

			$routeModel->brt_to_location	 = $bkgModel->bkg_drop_address;
			$routeModel->brt_to_latitude	 = $bkgModel->bkg_dropup_lat;
			$routeModel->brt_to_longitude	 = $bkgModel->bkg_dropup_long;
			if (!$routeModel->save())
			{
				throw new Exception("Error occurred while saving address");
			}
			array_push($rteArray, $routeModel);
		}
		return $rteArray;
	}

	public static function copyRouteData($bkgModel, $newBkgModel)
	{
		if (COUNT($newBkgModel->bookingRoutes) != COUNT($bkgModel->bookingRoutes))
		{
			return false;
		}
		if ($newBkgModel->bkg_from_city_id != $bkgModel->bkg_from_city_id || $newBkgModel->bkg_to_city_id != $bkgModel->bkg_to_city_id)
		{
			return false;
		}
		$newBkgModel->bkg_pickup_address = $bkgModel->bkg_pickup_address;
		$newBkgModel->bkg_drop_address	 = $bkgModel->bkg_drop_address;
		$newBkgModel->bkg_pickup_lat	 = $bkgModel->bkg_pickup_lat;
		$newBkgModel->bkg_pickup_long	 = $bkgModel->bkg_pickup_long;
		$newBkgModel->bkg_dropup_lat	 = $bkgModel->bkg_dropup_lat;
		$newBkgModel->bkg_dropup_long	 = $bkgModel->bkg_dropup_long;
		$newBkgModel->bkg_trip_distance	 = $bkgModel->bkg_trip_distance;
		$newBkgModel->bkg_trip_duration	 = $bkgModel->bkg_trip_duration;
		foreach ($newBkgModel->bookingRoutes as $key => $brtRoute)
		{
			$newBkgRoute = BookingRoute::model()->findByPk($brtRoute->brt_id);
			if ($newBkgRoute->brt_from_city_id != $bkgModel->bookingRoutes[$key]->brt_from_city_id || $newBkgRoute->brt_to_city_id != $bkgModel->bookingRoutes[$key]->brt_to_city_id)
			{
				return false;
			}
			$newBkgRoute->brt_from_location	 = $bkgModel->bookingRoutes[$key]->brt_from_location;
			$newBkgRoute->brt_to_location	 = $bkgModel->bookingRoutes[$key]->brt_to_location;
			$newBkgRoute->brt_from_latitude	 = $bkgModel->bookingRoutes[$key]->brt_from_latitude;
			$newBkgModel->brt_from_longitude = $bkgModel->bookingRoutes[$key]->brt_from_longitude;
			$newBkgRoute->brt_to_latitude	 = $bkgModel->bookingRoutes[$key]->brt_to_latitude;
			$newBkgRoute->brt_to_longitude	 = $bkgModel->bookingRoutes[$key]->brt_to_longitude;
			$newBkgRoute->brt_trip_distance	 = $bkgModel->bookingRoutes[$key]->brt_trip_distance;
			$newBkgRoute->brt_trip_duration	 = $bkgModel->bookingRoutes[$key]->brt_trip_duration;
			$bookingRoutes[]				 = $newBkgRoute;
		}
		foreach ($bookingRoutes as $key => $value)
		{
			$value->save();
		}
		return $newBkgModel->save();
	}

	public static function getUserAddressesByCity($city, $userId, $platform = null)
	{
		$joinSql = "FROM booking_route
				INNER JOIN booking ON bkg_id=brt_bkg_id AND bkg_status IN (1,2,3,5,6,7,9,15)
				INNER JOIN booking_user ON bkg_id=bui_bkg_id AND bkg_user_id='$userId' AND bkg_user_id>0
				";

		$fromSelect	 = "SELECT MAX(brt_id) as id, brt_from_location as address, brt_from_latitude as latitude, brt_from_longitude as longitude";
		$fromSql	 = "
			INNER JOIN cities ON brt_from_city_id=cty_id AND trim(cities.cty_garage_address)<>TRIM(brt_from_location)
			WHERE `brt_from_city_id` = $city
					AND brt_from_location != '' AND brt_from_latitude != ''
					AND brt_from_longitude != '' GROUP BY round(brt_from_latitude,6), round(brt_from_longitude,6)";

		$toSelect	 = "SELECT MAX(brt_id) as id,   brt_to_location as address, brt_to_latitude as latitude, brt_to_longitude as longitude";
		$toSql		 = "
			INNER JOIN cities ON brt_to_city_id=cty_id AND trim(cities.cty_garage_address)<>TRIM(brt_to_location)
			WHERE `brt_to_city_id` = $city
					AND brt_to_location != '' AND brt_to_location != '' AND brt_to_latitude != ''
					AND brt_to_longitude != '' GROUP BY round(brt_to_latitude,6), round(brt_to_longitude,6)";

		$fullSql = "SELECT DISTINCT latitude, longitude, address FROM
					(($fromSelect $joinSql $fromSql) UNION ($toSelect $joinSql $toSql)) a
					GROUP BY round(latitude,4), round(longitude,4), address ORDER BY id DESC LIMIT 0,15";

		$route	 = [];
		$routes	 = DBUtil::query($fullSql);
		foreach ($routes as $val)
		{
			$place			 = Stub\common\Place::init($val['latitude'], $val['longitude']);
			$place->address	 = $val['address'];
			if ($platform == Booking::Platform_App)
			{
				$id = Filter::removeNull($place);
			}
			else
			{
				$id = Filter::removeNull(json_encode($place));
			}

			$route[] = ['id' => $id, 'text' => $val['address']];
		}
		return $route;
	}

	public static function getToLocationByCityId($formCity, $toCity)
	{
		$sql	 = "Select brt_id as id, brt_to_location as text
				from booking_route
				where `brt_from_city_id` = $formCity AND `brt_to_city_id` = $toCity
				AND brt_from_location != '' AND brt_to_location != '' AND brt_to_latitude != ''
				AND brt_to_longitude != '' Group BY brt_to_latitude,brt_to_longitude";
		$routes	 = DBUtil::queryAll($sql);
		$data	 = CJSON::encode($routes);
		return $data;
	}

	/** @param Booking|BookingTemp $model */
	public static function validateAddress($model)
	{
		$success			 = true;
		$brtRoutes			 = $model->bookingRoutes;
		/** @var BookingRoute $pickupRoute */
		$pickupRoute		 = $brtRoutes[0];
		$tripType			 = $model->bkg_booking_type;
		$havePickupAddress	 = false;
		if ($model->pickup_later_chk == 1)
		{
			goto skipPickupAdrs;
		}
		if (!$pickupRoute->isPreciseAddrress(1))
		{

			$pickupRoute->addError("from_place", "Enter proper pickup location");
			$model->addError("bkg_pickup_address", "Pickup location is required");
			$success			 = false;
			$havePickupAddress	 = true;
		}
		skipPickupAdrs:
		if ($model instanceof Booking)
		{
			$isGozoNow = $model->bkgPref->bkg_is_gozonow;
		}
		else
		{
			$isGozoNow = $model->bkg_is_gozonow;
		}
		if ($model->drop_later_chk == 1)
		{
			goto skipDropAdrs;
		}
		/** @var BookingRoute $dropRoute */
		$dropRoute = end($brtRoutes);
		if (!$dropRoute->isPreciseAddrress(2) && in_array($tripType, [1, 2, 3, 4, 12]) && $isGozoNow == '0')
		{
			$dropRoute->addError("to_place", "Enter proper drop location");
			$model->addError("bkg_drop_address", "Drop location is required");
			$success = false;
		}
		skipDropAdrs:
		return $success;
	}

	/** @param int $type 1=>fromAddress, 2=>toAddress */
	public function isPreciseAddrress($type = 1)
	{
		/** @var Cities $city */
		$city	 = ($type == 1) ? $this->brtFromCity : $this->brtToCity;
		$field	 = ($type == 1) ? "from" : "to";
		if ($city->isPOI())
		{
			return true;
		}

		if (($this->{"brt_{$field}_latitude"} == '' || $this->{"brt_{$field}_longitude"} == '' || trim($this->{"brt_{$field}_location"}) == ''))
		{
			return false;
		}

		if (($this->{"brt_{$field}_latitude"} == $city->cty_lat && $this->{"brt_{$field}_longitude"} == $city->cty_long))
		{
			return false;
		}

		if (($this->{"brt_{$field}_location"} == $city->cty_name || $this->{"brt_{$field}_location"} == $city->cty_garage_address || $this->{"brt_{$field}_location"} == $city->cty_display_name || $this->{"brt_{$field}_location"} == $city->cty_full_name))
		{
			return false;
		}


		return true;
	}

	/**
	 * @param Booking $bkgModel
	 * @param BookingRoute[]| array() $routes
	 */
	public static function updateRouteAddresses($bkgModel, $routes)
	{
//		if (in_array($bkgModel->bkg_booking_type, [2,3]))
//		{
//			BookingRoute::validateRoutes($bkgModel->bookingRoutes,$bkgModel->bkg_booking_type);
//		}
//		else
//		{
//			BookingRoute::validateRoutes($bkgModel->bookingRoutes);
//		}

		$cntRoutes	 = count($bkgModel->bookingRoutes);
		$routes		 = array_values($routes);
		for ($i = 0; $i < $cntRoutes; $i++)
		{
			$route					 = $routes[$i];
			$brtRoute				 = $bkgModel->bookingRoutes[$i];
			$brtRoute->attributes	 = $route;
			$brtRoute->from_place	 = ($i > 0) ? $routes[($i - 1)]['to_place'] : $route['from_place'];
			if ($brtRoute->from_place != "")
			{
				$brtRoute->applyPlace($brtRoute->from_place, 1);
			}
			if ($brtRoute->to_place != "")
			{
				$brtRoute->applyPlace($brtRoute->to_place, 2);
			}
			if (!$brtRoute->save())
			{
				Logger::trace(json_encode($brtRoute->getErrors()));
				throw new Exception("Error occurred while saving address");
			}

			if ($i == 0)
			{
				$bkgModel->bkg_pickup_address	 = $brtRoute->brt_from_location;
				$bkgModel->bkg_pickup_lat		 = $brtRoute->brt_from_latitude;
				$bkgModel->bkg_pickup_long		 = $brtRoute->brt_from_longitude;
				if (!$bkgModel->save())
				{
					Logger::trace(json_encode($bkgModel->getErrors()));
					throw new Exception("Error occurred while saving address");
				}
			}
			if ($i == ($cntRoutes - 1))
			{
				$bkgModel->bkg_drop_address	 = $brtRoute->brt_to_location;
				$bkgModel->bkg_dropup_lat	 = $brtRoute->brt_to_latitude;
				$bkgModel->bkg_dropup_long	 = $brtRoute->brt_to_longitude;
				if (!$bkgModel->save())
				{
					Logger::trace(json_encode($bkgModel->getErrors()));
					throw new Exception("Error occurred while saving address");
				}
			}
		}
	}

	public function populateAirport($transferType)
	{
		$airportModel = Cities::model()->findByPk($this->airport);
		if ($transferType == 1)
		{
			$returnSet = $this->applyPlace($this->place, 2);
			if (!$returnSet->isSuccess())
			{
				goto end;
			}
			$this->brt_from_city_id		 = $this->airport;
			$this->brt_from_location	 = $airportModel->cty_garage_address;
			$this->brt_from_latitude	 = $airportModel->cty_lat;
			$this->brt_from_longitude	 = $airportModel->cty_long;
			$this->brt_from_is_airport	 = 1;
		}
		else
		{
			$returnSet = $this->applyPlace($this->place, 1);
			if (!$returnSet->isSuccess())
			{
				goto end;
			}
			$this->brt_to_city_id	 = $this->airport;
			$this->brt_to_location	 = $airportModel->cty_garage_address;
			$this->brt_to_latitude	 = $airportModel->cty_lat;
			$this->brt_to_longitude	 = $airportModel->cty_long;
			$this->brt_to_is_airport = 1;
		}
		end:
		return $returnSet;
	}
	
	public function populateRailwayBus($transferType)
	{
		$railwayBusModel = Cities::model()->findByPk($this->railway);
		if ($transferType == 1)
		{
			$returnSet = $this->applyPlace($this->place, 2);
			if (!$returnSet->isSuccess())
			{
				goto end;
			}
			$this->brt_from_city_id		 = $this->railway;
			$this->brt_from_location	 = $railwayBusModel->cty_garage_address;
			$this->brt_from_latitude	 = $railwayBusModel->cty_lat;
			$this->brt_from_longitude	 = $railwayBusModel->cty_long;
			$this->brt_from_is_railway	 = 1;
		}
		else
		{
			$returnSet = $this->applyPlace($this->place, 1);
			if (!$returnSet->isSuccess())
			{
				goto end;
			}
			$this->brt_to_city_id	 = $this->railway;
			$this->brt_to_location	 = $railwayBusModel->cty_garage_address;
			$this->brt_to_latitude	 = $railwayBusModel->cty_lat;
			$this->brt_to_longitude	 = $railwayBusModel->cty_long;
			$this->brt_to_is_railway = 1;
		}
		end:
		return $returnSet;
	}

	/**
	 * @param Stub\common\Place | string $placeObj
	 * @param int $type 1: from_location, 2: to_location
	 * @return ReturnSet
	 */
	public function applyPlace($placeObj, $type)
	{
		$returnSet = new ReturnSet();
		try
		{
			if ($placeObj == null)
			{
				throw new Exception("No place value passed", ReturnSet::ERROR_VALIDATION);
			}
			if (is_string($placeObj))
			{
				$placeObj = @json_decode($placeObj);

				if (json_last_error() !== JSON_ERROR_NONE)
				{
					throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
				}
				$jsonMapper						 = new JsonMapper();
				$jsonMapper->bStrictNullTypes	 = false;
				/** @var Stub\common\Place $obj */
				$obj							 = $jsonMapper->map($placeObj, new Stub\common\Place());
			}
			else
			{
				$obj = $placeObj;
			}
			if (!($obj instanceof Stub\common\Place))
			{
				throw new Exception("Invalid paramater", ReturnSet::ERROR_INVALID_DATA);
			}
			$ctyModel = Cities::getByPlace($obj);
			if ($type == 1)
			{
				$this->brt_from_city_id		 = $ctyModel->cty_id;
				$this->brt_from_location	 = substr(trim($obj->address), 0, 500);
				$this->brt_from_latitude	 = $obj->coordinates->latitude;
				$this->brt_from_longitude	 = $obj->coordinates->longitude;
			}
			else
			{
				$this->brt_to_city_id	 = $ctyModel->cty_id;
				$this->brt_to_location	 =  substr(trim($obj->address), 0, 500);
				$this->brt_to_latitude	 = $obj->coordinates->latitude;
				$this->brt_to_longitude	 = $obj->coordinates->longitude;
			}
			$returnSet->setStatus(true);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	/**
	 * This function is used for calculating distance between start location(cab) and pickup location
	 * @param type $bkgId
	 */
	public static function calcDistance($bkgId = NULL)
	{
		$model				 = Booking::model()->findByPk($bkgId);
		$distance			 = 0;
		$startCoordinates	 = BookingTrackLog::model()->getCoordinatesByEvent($bkgId, BookingTrack::TRIP_START);
		$startLocation		 = explode(',', $startCoordinates['btl_coordinates']);
		$pickupLocation		 = explode(",", $model->bookingRoutes[0]->brt_from_latitude . ',' . $model->bookingRoutes[0]->brt_from_longitude);
		$pickUpLat			 = (float) $pickupLocation[0];
		$pickUpLong			 = (float) $pickupLocation[1];
		$startLat			 = (float) $startLocation[0];
		$startLong			 = (float) $startLocation[1];
		if ($pickUpLat && $pickUpLong && $startLat && $startLong)
		{
			$qry		 = "SELECT CalcDistance($startLat,$startLong,$pickUpLat,$pickUpLong) AS `CalcDistance`";
			$distance	 = ((DBUtil::command($qry)->queryScalar()));
		}
		return $distance;
	}

	/**
	 * This function is used to get the route full city name
	 * @param type (int) $bkgid
	 * @return type (string) $rut
	 */
	public static function getRouteCityFullName($bkgid)
	{
		$param	 = ['bkgId' => $bkgid];
		$qry	 = "SELECT brt_from_city_id, brt_to_city_id from booking_route where brt_bkg_id =:bkgId AND brt_active = 1 ORDER BY brt_pickup_datetime, brt_id";
		$routes	 = DBUtil::query($qry, DBUtil::SDB(), $param);
		$ctr	 = 0;
		foreach ($routes as $route)
		{
			$ctr = ($ctr + 1);
			$rut .= Cities::getFullName($route['brt_from_city_id']) . ' - ';
			if (count($routes) == $ctr)
			{
				$rut .= Cities::getFullName($route['brt_to_city_id']);
			}
		}
		return $rut;
	}

	/**
	 * This function  is used to get the route full city name on bases of cab Id
	 * @param type (int) $id
	 * @return type (string) $rut
	 */
	public static function getRouteFullNameByBcb($id)
	{
		$param			 = ['bcbId' => $id];
		$qry			 = "SELECT booking_route.brt_from_city_id, booking_route.brt_to_city_id
                FROM `booking_route`
                WHERE booking_route.brt_active=1 AND brt_bcb_id =:bcbId";
		$routes			 = DBUtil::query($qry, DBUtil::SDB(), $param);
		$ctr			 = 0;
		$previousCity	 = '';
		foreach ($routes as $route)
		{
			$ctr = ($ctr + 1);
			if ($route['brt_from_city_id'] != $previousCity)
			{
				$rut .= Cities::getFullName($route['brt_from_city_id']) . ' - ';
			}
			if (count($routes) == $ctr)
			{
				$rut .= Cities::getFullName($route['brt_to_city_id']);
			}
			$previousCity = $route['brt_from_city_id'];
		}
		return $rut;
	}

	public function getSourcePlace()
	{
		$obj = null;
		if ($this->brt_from_latitude != '' && $this->brt_from_longitude != '' && $this->brt_from_location != '')
		{
			$obj				 = \Stub\common\Place::init($this->brt_from_latitude, $this->brt_from_longitude, null, $this->brt_from_location);
			$this->from_place	 = json_encode(Filter::removeNull($obj));
		}

		return $obj;
	}

	public function getDestinationPlace()
	{
		$obj = null;
		if ($this->brt_to_latitude != '' && $this->brt_to_longitude != '' && $this->brt_to_location != '')
		{
			$obj			 = \Stub\common\Place::init($this->brt_to_latitude, $this->brt_to_longitude, null, $this->brt_to_location);
			$this->to_place	 = json_encode(Filter::removeNull($obj));
		}
		return $obj;
	}

	/**
	 * This function  is used to get multi cities pickup for each route 
	 * @param type (int) $pickupCity
	 * @param type (int) $dropCity
	 * @param type (string)$pickupDateTime
	 * @return type (string) $dropoffDateTime
	 */
	public static function populatePickupTime($pickupCity, $dropCity, $pickupDateTime, $duration)
	{
		$routeDuration	 = Route::model()->getRouteDurationbyCities($pickupCity, $dropCity);
		$totalDuration	 = $routeDuration + $duration;
		$dropoffDateTime = date('Y-m-d H:i:s', strtotime($pickupDateTime . ' + ' . $routeDuration . ' minute'));

		$days = Filter::getTravelDays($pickupDateTime, $dropoffDateTime);
		if ($days['calendarDays'] > 1 && $totalDuration > 720)
		{
			$dropoffDateTime = $pickupDateTime;
			$dropoffDateTime = date('Y-m-d H:i:s', strtotime($dropoffDateTime . '+10 hours'));
			$totalDuration	 = 0;
		}
		else
		{
			$dropoffDateTime = $pickupDateTime;
		}
		return $dropoffDateTime;
	}

	public static function saveAddress($bkgModel, $bkgRoute)
	{
		if (!$bkgModel->save())
		{
			Logger::trace(json_encode($bkgModel->getErrors()));
			throw new Exception("Error occurred while saving address");
		}
		if ($bkgRoute != null)
		{
			if (!$bkgRoute->save())
			{
				Logger::trace(json_encode($bkgRoute->getErrors()));
				throw new Exception("Error occurred while saving address");
			}
		}
	}

	public function populateIntraCity($transferType)
	{


		$returnSet = $this->applyPlace($this->from_place, 1);
		if (!$returnSet->isSuccess())
		{
			goto end;
		}
		$intraModel					 = Cities::model()->findByPk($this->brt_from_city_id);
		$this->brt_from_city_id		 = $this->brt_from_city_id;
		$this->brt_from_location	 = $intraModel->cty_garage_address;
		$this->brt_from_latitude	 = $intraModel->cty_lat;
		$this->brt_from_longitude	 = $intraModel->cty_long;
		{
			$returnSet = $this->applyPlace($this->to_place, 2);
			if (!$returnSet->isSuccess())
			{
				goto end;
			}
			$this->brt_to_city_id	 = $this->brt_from_city_id;
			$this->brt_to_location	 = $intraModel->cty_garage_address;
			$this->brt_to_latitude	 = $intraModel->cty_lat;
			$this->brt_to_longitude	 = $intraModel->cty_long;
		}
		end:
		return $returnSet;
	}

	public static function updateDropAddress($bkgModel, $routes)
	{
		$returnSet	 = new ReturnSet();
		$bkg_id		 = $bkgModel->bkg_id;
		$transaction = DBUtil::beginTransaction();
		try
		{
			Logger::info("Update Route Address Start");
			BookingRoute::updateRouteAddresses($bkgModel, $routes);
			Logger::trace("BookingId: " . $bkg_id . " route address updated");
			$bkgModel->refresh();
			$arrResult	 = BookingRoute::updateDistance($bkgModel);
			Logger::trace("BookingId: " . $bkg_id . " route rates  updated =>" . json_encode($arrResult));
			$success	 = true;
			if (!empty($arrResult))
			{
				$desc = "Address Updated . Additional Kms:" . $arrResult['additional_km'] . ". Old Base Fare:₹" .
						$arrResult['oldBaseFare'] . ". New Base fare:₹" . $arrResult['fare']['baseFare'];
			}
			else
			{
				$desc = "Address Updated . No Additional Kms";
			}
			BookingLog::model()->createLog($bkg_id, $desc, UserInfo::getInstance(), BookingLog::BOOKING_MODIFIED, false);
			Logger::trace("BookingId: " . $bkg_id . " route address updated Log added");
			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus($success);
			$returnSet->setMessage($desc);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public static function getByBkgids($bkgIds)
	{
		$bkgIdList	 = explode(',', $bkgIds);
		DBUtil::getINStatement($bkgIdList, $bindString, $params);
		$sql		 = "SELECT brt.brt_id,brt.brt_bkg_id,
				brt.brt_pickup_datetime,
				brt.brt_trip_distance,brt.brt_trip_duration,
				fct.cty_id fCityId,fct.cty_name fCityName,
				tct.cty_id tCityId,tct.cty_name tCityName
			FROM booking_route brt 
			INNER JOIN cities fct ON brt.brt_from_city_id = fct.cty_id
			INNER JOIN cities tct ON brt.brt_to_city_id = tct.cty_id
			WHERE brt.brt_bkg_id IN ($bindString) AND brt.brt_active=1 
			ORDER BY brt.brt_pickup_datetime";
		$recordSet	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $recordSet;
	}

	public static function getByTripid($tripId)
	{ 
		$params = ['tripId' => $tripId];
		$sql		 = "SELECT brt.brt_id,brt.brt_bkg_id,
				brt.brt_pickup_datetime,
				brt.brt_trip_distance,brt.brt_trip_duration,
				fct.cty_id fCityId,fct.cty_name fCityName,
				tct.cty_id tCityId,tct.cty_name tCityName
			FROM booking_route brt 
			INNER JOIN cities fct ON brt.brt_from_city_id = fct.cty_id
			INNER JOIN cities tct ON brt.brt_to_city_id = tct.cty_id
			INNER JOIN booking bkg ON bkg.bkg_id = brt.brt_bkg_id
			WHERE bkg.bkg_bcb_id = :tripId AND brt.brt_active=1 
			ORDER BY brt.brt_pickup_datetime";
		$recordSet	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $recordSet;
	}

	public static function getDataByBkgid($bkgId)
	{
		$params = ['bkgId' => $bkgId];

		$sql = "SELECT brt.brt_id,brt.brt_bkg_id,brt.brt_pickup_datetime,
					brt_trip_distance, brt_trip_duration,
				brt.brt_from_city_id,fct.cty_name brt_from_city_name, brt.brt_from_location ,
					brt.brt_from_latitude,brt.brt_from_longitude,  
				brt.brt_to_city_id,  tct.cty_name brt_to_city_name, brt.brt_to_location,   
					brt.brt_to_latitude,brt.brt_to_longitude 
			FROM booking_route brt 
			INNER JOIN cities fct ON brt.brt_from_city_id = fct.cty_id
			INNER JOIN cities tct ON brt.brt_to_city_id = tct.cty_id
			WHERE brt.brt_bkg_id = :bkgId AND brt.brt_active=1 
			ORDER BY brt.brt_pickup_datetime";

		$recordSet	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		$dataSet	 = [];
		$dataArr	 = ['city_id', 'city_name', 'latitude', 'longitude', 'location'];
		foreach ($recordSet as $key => $data)
		{
			$dataSet[$key] = $data;
			foreach ($dataArr as $val)
			{
				$sourceVal						 = 'brt_from_' . $val;
				$destVal						 = 'brt_to_' . $val;
				$dataSet[$key]['source'][$val]	 = $data[$sourceVal];
				$dataSet[$key]['dest'][$val]	 = $data[$destVal];
			}
		}
		return $dataSet;
	}
	/**
	 * used to show drop to stop distance
	 * @param type $bookingId
	 * @param type $stopLat
	 * @param type $stopLong
	 * @return type
	 */
	public static function calcEndDistance($bookingId,$stopLat,$stopLong)
	{
		$model				 = Booking::model()->findByPk($bookingId);
		$distance			 = 0;
		$dropLocation		 = explode(",", $model->bookingRoutes[0]->brt_to_latitude . ',' . $model->bookingRoutes[0]->brt_to_longitude);
		$stopLat			 = (float) $stopLat;
		$stopLong			 = (float) $stopLong;
		$dropLat			 = (float) $dropLocation[0];
		$dropLong			 = (float) $dropLocation[1];
		if ($dropLat && $dropLong && $stopLat && $stopLong)
		{
			$qry		 = "SELECT CalcDistance($stopLat,$stopLong,$dropLat,$dropLong) AS `CalcDistance`";
			$distance	 = ((DBUtil::command($qry)->queryScalar()));
		}
		return $distance;
	}
	
	/**
	 * 
	 * @param type $bookingId
	 */
	public static function checkExtraKmApplied($bookingId,$stopLat,$stopLong)
	{
		//pickup position  to  start distance
		$calculateStartDistance	 = BookingRoute::calcDistance($bookingId);
		//end positon to stop distance
		$calculateEndDistance	 = BookingRoute::calcEndDistance($bookingId,$stopLat,$stopLong);
		if ($calculateStartDistance <= 1 && $calculateEndDistance<=1)
		{
			$isExtraKm = 0;
		}
		else
		{
			$isExtraKm = 1;
			
		}
		return $isExtraKm;
		
	}

	/**
	 * 
	 * @param \BookingRoute $model
	 * @param type $journey
	 * @param type $pickupDate
	 * @return \BookingRoute
	 */
	public static function getRouteModel(\BookingRoute $model = null, $journey, $pickupDate)
	{
		if ($model == null)
		{
			$model = new \BookingRoute();
		}
		$model->brt_from_location	 = $journey->pickup->bookerEnteredAddress;
		$model->brt_from_latitude	 = (float) $journey->pickup->latitude;
		$model->brt_from_longitude	 = (float) $journey->pickup->longitude;

		$model->brt_to_location		 = $journey->dropoff->bookerEnteredAddress;
		$model->brt_to_latitude		 = (float) $journey->dropoff->latitude;
		$model->brt_to_longitude	 = (float) $journey->dropoff->longitude;
		$model->brt_pickup_datetime	 = $pickupDate;
		$routeModel =  $model->populateCities();
		$distance   =  (int) $journey->distance;

		if ($routeModel)
		{
			$model->brt_trip_duration = $routeModel->rut_estm_time;
			$distance					 = max([$distance, $routeModel->rut_estm_distance]);
			$model->brt_trip_distance	 = $distance;
		}
		$isAirportTransfer			 = $model->isAirportTransfer();
		if ($isAirportTransfer)
		{
			$model->tripType = 12;
		}
		$model->brt_pickup_date_time = $model->tripType;
		return $model;
	}
    public function airportValidate()
    {

        if ($this->airport == '')
        {
            $this->addError("airport", "Airport required");
            return false;
        }
        else
		{
			return true;
		}
    }

	public static function reschedulePickupTime($newPickupTime,$prevRoutes)
	{
		
		$prevTimeStr = strtotime($prevRoutes[0]->brt_pickup_datetime); 
		$newTimeStr = strtotime($newPickupTime); 
		if($newTimeStr > $prevTimeStr)
		{
			$minutes = round(abs($prevTimeStr - $newTimeStr) / 60,2);
			$strMinutes = '+ ' . $minutes . ' minute';
		}
		else
		{
			$minutes = round(abs($prevTimeStr - $newTimeStr) / 60,2);
			$strMinutes = '- ' . $minutes . ' minute';
		}
		
		$routes = [];
		foreach($prevRoutes as $key=>$brtRoutes)
		{
			$newBrtRoute = new BookingRoute();
			$newBrtRoute->attributes = $brtRoutes->attributes;
			$newBrtRoute->brt_id = null;
			$newBrtRoute->brt_bkg_id = null;
			$newBrtRoute->brt_bcb_id = null;
			$newBrtRoute->brt_created =  new CDbExpression('NOW()');
			$newBrtRoute->brt_pickup_datetime = ($key==0)?$newPickupTime:date('Y-m-d H:i:s', strtotime($brtRoutes->brt_pickup_datetime .$strMinutes ));
			$routes[$key] = $newBrtRoute;
		}
		return $routes;
	}
    public function getRouteName_V1($bkgid)
    {
        $qry    = "SELECT brt_from_city_id, brt_to_city_id from booking_route where brt_bkg_id = $bkgid AND brt_active = 1";
        $routes = DBUtil::query($qry);
        $ctr    = 0;
        foreach ($routes as $route)
        {
            $ctr = ($ctr + 1);
            $rut .= Cities::getName($route['brt_from_city_id']) . ' - ';
            if (count($routes) == $ctr)
            {
                $rut .= Cities::getName($route['brt_to_city_id']);
            }
        }
        return $rut;
    }

}
