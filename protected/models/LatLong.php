<?php

/**
 * This is the model class for table "lat_long".
 *
 * The followings are the available columns in table 'lat_long':
 * @property integer $ltg_id
 * @property string $ltg_lat
 * @property string $ltg_long
 * @property integer $ltg_city_id
 * @property string $ltg_place_id
 * @property string $ltg_locality_address
 * @property integer $ltg_active
 * @property string $ltg_types
 * @property string $ltg_created_on
 * @property string $ltg_name
 * @property string $ltg_alias 
 * @property integer $ltg_review
 */
class LatLong extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lat_long';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ltg_lat, ltg_long, ltg_city_id, ltg_place_id, ltg_locality_address', 'required'),
			array('ltg_city_id, ltg_active', 'numerical', 'integerOnly' => true),
//			array('ltg_lat, ltg_long', 'length', 'max' => 4),
			array('ltg_name', 'length', 'max' => 100),
			array('ltg_place_id', 'length', 'max' => 250),
			array('ltg_place_id', 'validateDuplicate'),
			array('ltg_locality_address', 'length', 'max' => 250),
			array('ltg_bounds', 'length', 'max' => 1000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ltg_id, ltg_lat, ltg_long, ltg_city_id, ltg_place_id, ltg_locality_address, ltg_active, ltg_created_on, ltg_types, ltg_name, ltg_alias,ltg_review', 'safe', 'on' => 'search'),
		);
	}

	public function validateDuplicate($attribute, $params)
	{
		if ($this->isNewRecord)
		{
			$model = LatLong::model()->find("ltg_place_id=:placeId", ['placeId' => $this->ltg_place_id]);
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ltg_id'				 => 'Ltg',
			'ltg_lat'				 => 'Ltg Lat',
			'ltg_long'				 => 'Ltg Long',
			'ltg_city_id'			 => 'Ltg City',
			'ltg_place_id'			 => 'Ltg Place',
			'ltg_locality_address'	 => 'Ltg Locality Address',
			'ltg_active'			 => 'Ltg Active',
			'ltg_created_on'		 => 'Ltg Created On',
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

		$criteria->compare('ltg_id', $this->ltg_id);
		$criteria->compare('ltg_lat', $this->ltg_lat, true);
		$criteria->compare('ltg_long', $this->ltg_long, true);
		$criteria->compare('ltg_city_id', $this->ltg_city_id);
		$criteria->compare('ltg_place_id', $this->ltg_place_id, true);
		$criteria->compare('ltg_locality_address', $this->ltg_locality_address, true);
		$criteria->compare('ltg_active', $this->ltg_active);
		$criteria->compare('ltg_created_on', $this->ltg_created_on, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function beforeSave()
	{
		return parent::beforeSave();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LatLong the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getPlaceDistance($address1, $address2, $fromCityId = null, $toCityId = null)
	{
		$sourceRecord	 = $this->findLocationId($address1, $fromCityId);
		$destinationArr	 = $this->findLocationId($address2, $toCityId);
	}

	public function getPlaceLatLongDistance($route)
	{
		$address1			 = trim($route->brt_from_latitude) . ',' . trim($route->brt_from_longitude);
		$address2			 = trim($route->brt_to_latitude) . ',' . trim($route->brt_to_longitude);
		$sourcePlaceId		 = trim($route->brt_from_place_id);
		$sourceFormattedAdd	 = trim($route->brt_from_formatted_address);
		$destPlaceId		 = trim($route->brt_to_place_id);
		$destFormattedAdd	 = trim($route->brt_to_formatted_address);
		$fromCityId			 = $route->brt_from_city_id;
		$toCityId			 = $route->brt_to_city_id;

		$sourceArr = [
			'address'		 => $address1,
			'placeId'		 => $sourcePlaceId,
			'formattedAdd'	 => $sourceFormattedAdd,
			'cityId'		 => $fromCityId
		];

		$destArr = [
			'address'		 => $address2,
			'placeId'		 => $destPlaceId,
			'formattedAdd'	 => $destFormattedAdd,
			'cityId'		 => $toCityId
		];

		$sourceId = $this->findLocationId($address1, $fromCityId);
		if (!$sourceId)
		{
			$sourceId = $this->addData($sourceArr);
		}
		$destId = $this->findLocationId($address2, $destPlaceId);
		if (!$destId)
		{
			$destId = $this->addData($destArr);
		}


		return [$sourceId, $destId];
	}

	public function findLocationId($location, $cityId = null)
	{
		$locLatArr	 = explode(',', $location);
		$lat		 = number_format((float) $locLatArr[0], 2);
		$long		 = number_format((float) $locLatArr[1], 2);
		$sql		 = "SELECT ltg_id FROM lat_long WHERE ltg_lat = '$lat' AND ltg_long = '$long' AND ltg_active = 1";
		$record		 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();

		return $record;
	}

	public function addData($routeData)
	{
		$locLatArr					 = explode(',', $routeData['address']);
		$lat						 = round($locLatArr[0], 2);
		$long						 = round($locLatArr[1], 2);
		$model						 = new LatLong();
		$model->ltg_lat				 = $lat;
		$model->ltg_long			 = $long;
		$model->ltg_city_id			 = $routeData['cityId'];
		$model->ltg_place_id		 = $routeData['placeId'];
		$model->ltg_locality_address = $routeData['formattedAdd'];
		if ($model->save())
		{
			return $model->ltg_id;
		}
		else
		{
			return false;
		}
	}

	/*
	 * This function will return LatLong model if exists else add new record then return model
	 * @return LatLong model
	 * @param $lat latitude of a place
	 * @param $lat longitude of a place
	 */

	/** @param \Stub\common\Place $placeObj */
	public static function findPOI($placeObj)
	{
		$cond		 = [];
		$criteria	 = new CDbCriteria();
		$criteria->compare('ltg_lat', round($placeObj->coordinates->latitude, 4));
		$criteria->compare('ltg_long', round($placeObj->coordinates->longitude, 4));
		$criteria1	 = new CDbCriteria();
		$criteria1->compare('ltg_place_id', $placeObj->place_id);

		$criteria->mergeWith($criteria1, 'OR');

		$criteria2 = new CDbCriteria();
		$criteria2->addCondition("ltg_active=1 AND ltg_types LIKE '%point_of_interest%'");

		$criteria2->mergeWith($criteria);

		$model = LatLong::model()->find($criteria2);
		return $model;
	}

	/**
	 * @param \Stub\common\Place $placeObj 
	 * @return LatLong
	 */
	public function getDetailsByPlace($placeObj)
	{
		$model = self::findNearest($placeObj);
		if ($model)
		{
			goto model;
		}


		$models = self::addByPlace($placeObj);
		if ($models && count($models) > 0)
		{
			$model = $models[0];
		}

		model:
		return $model;
	}

	public static function addPOI($placeObj)
	{
		if ($placeObj->place_id == '')
		{
			goto searchByCoordinates;
		}

		$googleObj = GoogleMapAPI::getObjectByPlaceId($placeObj->place_id);
		if (!$googleObj)
		{
			goto searchByCoordinates;
		}
		$googleplaceObj = $googleObj->results[0];
		goto AddPlace;

		searchByCoordinates:
		$googleplaceObj	 = GoogleMapAPI::getObjectByLatLong($placeObj->coordinates->latitude, $placeObj->coordinates->longitude);
		self::addByGoogleObjects($googleplaceObj);
		$googleplaceObj	 = GoogleMapAPI::getPOI($googleplaceObj);
		$model			 = false;
		AddPlace:
		if ($googleplaceObj)
		{
			/* @var $place \Stub\common\Place */
			$placeObj	 = \Stub\common\Place::initGoogePlace($googleplaceObj);
			$model		 = LatLong::addPlace($placeObj, 4);
		}
		return $model;
	}

	/** @param \Stub\common\Place $placeObj */
	public static function addPlace($placeObj, $decimalPoint = null, $city = 0)
	{
		Logger::trace(json_encode($placeObj));
		if ($decimalPoint === null)
		{
			$decimalPoint = 4;
		}
		if ($placeObj->place_id != '')
		{
			$model = LatLong::model()->find("ltg_place_id=:place AND ltg_active=1", ["place" => $placeObj->place_id]);
		}
//		if ($model)
//		{
//			goto end;
//		}
		if (!$model)
		{
			$model				 = new LatLong();
			$model->ltg_city_id	 = $city;
			$model->ltg_review	 = 0;
		}

		if ($model->ltg_city_id > 0)
		{
			goto skipCityModel;
		}

		$ctyModel = Cities::getByPlace($placeObj);
		if (!$ctyModel)
		{
			throw new Exception("Unable to find city.");
		}

		$model->ltg_city_id = $ctyModel->cty_id;
		if (isset($ctyModel->is_partial) && $ctyModel->is_partial == 1)
		{
			$model->ltg_review = 1;
		}
		skipCityModel:
		$model->ltg_lat				 = round($placeObj->coordinates->latitude, $decimalPoint);
		$model->ltg_long			 = round($placeObj->coordinates->longitude, $decimalPoint);
		$model->ltg_name			 = $placeObj->name;
		$model->ltg_alias			 = $placeObj->alias;
		$model->ltg_place_id		 = $placeObj->place_id;
		$model->ltg_locality_address = $placeObj->address;
		$model->ltg_types			 = json_encode($placeObj->types);
		$model->ltg_bounds			 = json_encode($placeObj->bounds);
		if (!$model->save())
		{
			//Logger::error("LatLOng Model Error :" . json_encode($model->getErrors()));
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		//	end:
		return $model;
	}

	/** @param Stub\common\Place $placeObj 
	 * 
	 * @param Stub\common\Place $placeObj
	 * @param type $precision
	 * @return boolean
	 */
	public static function findNearest($placeObj, $precision = 0.005)
	{
		if ($placeObj->coordinates->latitude == 0 && $placeObj->place_id == '')
		{
			return false;
		}
		$select		 = "0 as diff";
		$cond		 = [];
		$criteria	 = new CDbCriteria();
		if ($placeObj->coordinates->latitude > 0)
		{
			$latMin	 = $placeObj->coordinates->latitude - $precision;
			$latMax	 = $placeObj->coordinates->latitude + $precision;
			$longMin = $placeObj->coordinates->longitude - $precision;
			$longMax = $placeObj->coordinates->longitude + $precision;

			$select						 = "CalcDistance(`ltg_lat`, `ltg_long`,:lat, :lng) as diff";
			$criteria->addCondition("`ltg_lat` BETWEEN :latMin AND :latMax AND `ltg_long` BETWEEN :longMin AND :longMax AND CalcDistance(`ltg_lat`, `ltg_long`,:lat, :lng)<0.15");
			$criteria->params['lat']	 = $placeObj->coordinates->latitude;
			$criteria->params['lng']	 = $placeObj->coordinates->longitude;
			$criteria->params['latMin']	 = $latMin;
			$criteria->params['latMax']	 = $latMax;
			$criteria->params['longMin'] = $longMin;
			$criteria->params['longMax'] = $longMax;
		}

		$criteria1 = new CDbCriteria();
		$criteria1->compare('ltg_place_id', $placeObj->place_id);

		$criteria->mergeWith($criteria1, 'OR');

		$criteria2			 = new CDbCriteria();
		$criteria2->addCondition("ltg_active=1");
		$criteria2->select	 = "*, $select";

		$criteria2->mergeWith($criteria);
		$criteria2->order	 = "diff ASC";
		$model				 = LatLong::model()->find($criteria2);
		Logger::profile("LatLong::findNearest Done");

		return $model;
	}

	public static function getDataByLatLongBound($placeObj)
	{
		$latLongModel = LatLong::getDetailsByPlace($placeObj);
		return $latLongModel;
	}

	/**
	 * @param \Stub\common\Place $placeObj
	 *  */
	public static function addByPlace($placeObj)
	{
		$objects = GoogleMapAPI::getObjectByLatLong($placeObj->coordinates->latitude, $placeObj->coordinates->longitude);
		$models	 = self::addByGoogleObjects($objects, $placeObj);
		return $models;
	}

	public static function addByGoogleObjects($objects, $placeObj = null)
	{
		$statePlaceObject	 = \Stub\common\Place::initGoogePlace(GoogleMapAPI::getAdminLevel1($objects));
		$stateModel			 = States::findByPlaceObj($statePlaceObject);
		if ($stateModel)
		{
			goto populateLocalities;
		}

		if ($placeObj == null)
		{
			$placeObj = Stub\common\Place::initGoogePlace($objects->results[0]);
		}

		if ($placeObj != null)
		{
			$stateModel = States::getByGeoCoordinates($placeObj->coordinates->latitude, $placeObj->coordinates->longitude);
		}

		if (!$stateModel)
		{
			return false;
		}

		populateLocalities:
		$objs = GoogleMapAPI::getLocalities($objects);
		if (count($objs) == 0)
		{
			$googleObjects = $objects->results;
			foreach ($googleObjects as $obj)
			{
				$placeObj1 = \Stub\common\Place::initGoogePlace($obj);
				if (!$placeObj1->isUnderLocality())
				{
					continue;
				}
				$cityModel = Cities::getByGeoBounds($placeObj1, 15);
				if ($cityModel && $cityModel->is_partial == 0)
				{
					break;
				}
				$cityModel = Cities::getByNearestBound($placeObj1);
				if ($cityModel)
				{
					break;
				}
			}
		}
		foreach ($objs as $obj)
		{
			$placeObj	 = \Stub\common\Place::initGoogePlace($obj);
			$cityModel	 = Cities::addByPlaceObject($placeObj, $stateModel->stt_id);
		}
		if (!$cityModel)
		{
			return false;
		}

		$googleObjects	 = $objects->results;
		$models			 = [];
		$model			 = null;
		foreach ($googleObjects as $obj)
		{
			$placeObj1	 = \Stub\common\Place::initGoogePlace($obj);
			$obj		 = LatLong::addPlace($placeObj1, null, $cityModel->cty_id);
			if ($obj && !$model && $placeObj1->isUnderLocality())
			{
				$model = $obj;
				array_unshift($models, $obj);
			}
			else
			{
				$models[] = $obj;
			}
		}
		return $models;
	}

	/**
	 * @param Stub\common\Place $placeObj 
	 */
	public static function addByPlaceId($placeObj, $city = null)
	{
		if ($placeObj->place_id == '')
		{
			return false;
		}
		$condition	 = "";
		$params		 = ["place" => $placeObj->place_id];
		if ($city != null)
		{
			$condition		 = " AND ltg_city_id=:city";
			$params ["city"] = $city;
		}

		$model = LatLong::model()->find("ltg_place_id=:place AND ltg_active=1 {$condition}", $params);

		if ($model && $model->ltg_bounds != '')
		{
			$geoplaceModel = GeoPlace::model()->checkPlace($placeObj, $model->ltg_id);
			goto result;
		}
		$transaction = DBUtil::beginTransaction();
		try
		{
			$precision		 = 0.00005;
			$googleObjects	 = GoogleMapAPI::getObjectByPlaceId($placeObj->place_id);
			$obj			 = $googleObjects->results[0];
			$placeObj		 = Stub\common\Place::initGoogePlace($obj);
			$model			 = LatLong::findNearest($placeObj, $precision);
			if (!$model)
			{

				$model = LatLong::addPlace($placeObj);
			}

			$placeModel = GeoPlace::model()->add($obj);

			$placeModel->gpl_ltg_id = $model->ltg_id;
			$placeModel->save();
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			ReturnSet::setException($ex);
		}

		result:
		return $model;
	}

	/** @param \Stub\common\Place $placeObj */
	public static function getPlace($placeObj)
	{
		Logger::trace(json_encode($placeObj));
		if ($placeObj->place_id != '')
		{
			$model = LatLong::model()->find("ltg_place_id=:place AND ltg_active=1", ["place" => $placeObj->place_id]);
		}
		return $model;
	}

	/**
	 * OLD SERVICE : addByPlaceId
	 * use getObjectByPlaceId_V1 new function for api changes
	 * @param Stub\common\Place $placeObj 
	 * @param type $city
	 * @param type $sessionId
	 * @return boolean
	 */
	public static function addByPlaceId_V1($placeObj, $city = null, $sessionId)
	{
//Logger::setModelCategory(__CLASS__, __FUNCTION__);
 //Logger::trace("rrrrrrrrrrrrPlace with session :" . $placeObj->place_id.'==========='.$sessionId);
		if ($placeObj->place_id == '')
		{
			return false;
		}
		$condition	 = "";
		$params		 = ["place" => $placeObj->place_id];
		if ($city != null)
		{
			$condition		 = " AND ltg_city_id=:city";
			$params ["city"] = $city;
		}

		$model = LatLong::model()->find("ltg_place_id=:place AND ltg_active=1 {$condition}", $params);

		if ($model)
		{
			goto result;
		}

		$geoPlaceModel = GeoPlace::model()->getByPlace($placeObj, false);

		if ($geoPlaceModel)
		{
			$model = self::addFromGeoPlace($geoPlaceModel);
		}

		if ($model)
		{
			goto result;
		}
		$transaction = null;
		try
		{


          //  Logger::trace("Place with session :" . $placeObj->place_id.'==========='.$sessionId);
			$googleObjects = GoogleMapAPI::getObjectBySessionPlaceId($placeObj->place_id, $sessionId);
			if (!$googleObjects)
			{
				throw new Exception("Failed to fetch Google place id {$placeObj->place_id}", ReturnSet::ERROR_FAILED);
			}

			//$obj			 = $googleObjects->results[0];
			$googleObjects->place_id = $placeObj->place_id;
			$placeObj				 = Stub\common\Place::initGoogePlace($googleObjects);
			$model					 = LatLong::getPlace($placeObj);
			$transaction			 = DBUtil::beginTransaction();
			if (!$model)
			{
				$model = LatLong::addPlace($placeObj);
			}

			$placeModel				 = GeoPlace::model()->add($googleObjects);
			$placeModel->gpl_ltg_id	 = $model->ltg_id;
			$placeModel->save();
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			ReturnSet::setException($ex);
		}
//Logger::unsetModelCategory(__CLASS__, __FUNCTION__);


		result:
		return $model;
	}

	/** @param GeoPlace $geoPlaceModel */
	public static function addFromGeoPlace($geoPlaceModel)
	{
		$model						 = new LatLong();
		$model->ltg_name			 = $geoPlaceModel->gpl_name;
		$model->ltg_place_id		 = $geoPlaceModel->gpl_place_id;
		$model->ltg_bounds			 = $geoPlaceModel->gpl_bounds;
		$model->ltg_lat				 = $geoPlaceModel->gpl_lat;
		$model->ltg_long			 = $geoPlaceModel->gpl_lng;
		$model->ltg_types			 = $geoPlaceModel->gpl_types;
		$model->ltg_alias			 = $geoPlaceModel->gpl_alias;
		$model->ltg_locality_address = $geoPlaceModel->gpl_address;
		$model->ltg_active			 = 1;

		$ltgModel = null;
		if ($geoPlaceModel->gpl_ltg_id > 0)
		{
			$ltgModel = LatLong::model()->findByPk($geoPlaceModel->gpl_ltg_id);
		}

		if (!$ltgModel)
		{
			$placeObj							 = new Stub\common\Place();
			$placeObj->coordinates->latitude	 = $geoPlaceModel->gpl_lat;
			$placeObj->coordinates->longitude	 = $geoPlaceModel->gpl_lng;
			$placeObj->place_id					 = $geoPlaceModel->gpl_place_id;
			$placeObj->types					 = json_decode($geoPlaceModel->gpl_types);
			$cityModel							 = Cities::getByPlace($placeObj);
			$model->ltg_city_id					 = $cityModel->cty_id;
		}
		else
		{
			$model->ltg_city_id = $ltgModel->ltg_city_id;
		}

		$model->save();
		return $model;
	}

}
