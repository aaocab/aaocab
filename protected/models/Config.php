<?php

/**
 * This is the model class for table "config".
 *
 * The followings are the available columns in table 'config':
 * @property integer $cfg_id
 * @property string $cfg_name
 * @property string $cfg_value
 * @property string $cfg_description
 * @property string $cfg_env
 * @property string $cfg_modified_date
 * @property integer $cfg_modified_by
 */
class Config extends CActiveRecord
{

	CONST Corporate_address	 = 1;
	CONST Operation_address	 = 2;

	protected static $_data = [];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cfg_value', 'length', 'max' => 5000),
			array('cfg_description', 'length', 'max' => 100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cfg_id, cfg_name, cfg_value, cfg_description, cfg_env, cfg_modified_date, cfg_modified_by', 'safe', 'on' => 'search'),
			array('cfg_id, cfg_name, cfg_value, cfg_description, cfg_env, cfg_modified_date, cfg_modified_by', 'safe'),
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
			'cfg_id'			 => 'Cfg',
			'cfg_name'			 => 'Name',
			'cfg_value'			 => 'Value',
			'cfg_description'	 => 'Description',
			'cfg_env'			 => 'Environment',
			'cfg_modified_date'	 => 'Modified Date',
			'cfg_modified_by'	 => 'Modified By'
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

		$criteria->compare('cfg_id', $this->cfg_id);
		$criteria->compare('cfg_name', $this->cfg_name, true);
		$criteria->compare('cfg_value', $this->cfg_value, true);
		$criteria->compare('cfg_description', $this->cfg_description, true);
		$criteria->compare('cfg_env', $this->cfg_env, true);
		$criteria->compare('cfg_modified_date', $this->cfg_modified_date, true);
		$criteria->compare('cfg_modified_by', $this->cfg_modified_by, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Config the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getAccess($type)
	{
		return $isNumberShow = $this->find('cfg_name=:name', ['name' => $type])->cfg_value;
	}

	public static function getList()
	{
		$env	 = APPLICATION_ENV;
		$params	 = ['env' => $env];
		$sql	 = "SELECT * FROM config WHERE cfg_active=1 AND cfg_env IN ('',:env) ORDER BY cfg_env ASC, cfg_name ASC";
		$res	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $res;
	}

	public static function getArrayList($refresh = false)
	{
		$key	 = "config.data." . APPLICATION_ENV;
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false && !$refresh)
		{
			goto result;
		}

		$rows	 = self::getList();
		$data	 = [];

		foreach ($rows as $row)
		{
			$value	 = self::parseKeyValue($row['cfg_name'], $row['cfg_value']);
			$data	 = array_replace_recursive($data, $value);
		}

		Yii::app()->cache->set($key, $data, 1 * 24 * 60 * 60, new CacheDependency("config"));

		result:
		return $data;
	}

	public static function parseKeyValue($key, $value)
	{
		$data	 = [];
		if (($pos	 = stripos($key, ".")) !== false)
		{
			$data[substr($key, 0, $pos)] = self::parseKeyValue(substr($key, $pos + 1), $value);
			goto result;
		}
		if (is_numeric($key))
		{
			$key = intval($key);
		}
		$data[$key] = $value;

		result:
		return $data;
	}

	public static function getData()
	{
		if (self::$_data == null)
		{
			self::$_data = self::getArrayList();
		}
		return self::$_data;
	}

	public static function get($name)
	{
		$keys	 = explode(".", $name);
		$value	 = self::getData();
		foreach ($keys as $key)
		{
			if (!is_array($value) || !isset($value[$key]))
			{
				$value = null;
				break;
			}

			$value = $value[$key];
		}

		return $value;
	}

	public static function getAllowedHost($module)
	{
		$baseKey		 = "module.$module.host.allow";
		$value	 = self::get($baseKey);
		return json_decode($value);
	}
	
	public static function getInternalIPs()
	{
		$baseKey		 = "internal.ip";
		$value	 = self::get($baseKey);
		return json_decode($value);
	}

	/**
	 *
	 * Get a minimum pickup time for particular city and trip type
	 *
	 * @param int $tripType Booking Type: One Way/Round/Airport etc..
	 * @param int $cityId
	 * @return int Minimum Pickup Time in minutes
	 */
	public static function getMinPickupTime($tripType, $cityId = null)
	{
		$baseKey		 = "booking.pickup.mintime";
		$pickupList		 = Config::get($baseKey);
		$cityPickupList	 = Config::get("cities.{$cityId}." . $baseKey);

		switch (true)
		{
			case ($cityPickupList != null && isset($cityPickupList[$tripType])):
				$minTime = $cityPickupList[$tripType];
				break;

			case ($cityPickupList != null && isset($cityPickupList[0])):
				$minTime = $cityPickupList[0];
				break;

			case ($pickupList != null && isset($pickupList[$tripType])):
				$minTime = $pickupList[$tripType];
				break;

			case ($pickupList != null && isset($pickupList[0])):
			default:
				$minTime = $pickupList[0];
				break;
		}
		result:
		return $minTime;
	}

	/**
	 *
	 * Get a max pickup time for particular city and trip type
	 *
	 * @param int $tripType Booking Type: One Way/Round/Airport etc..
	 * @param int $cityId
	 * @return int Maximum Pickup Time in minutes
	 */
	public static function getMaxPickupTime($tripType, $cityId = null)
	{
		$baseKey		 = "booking.pickup.maxtime";
		$pickupList		 = Config::get($baseKey);
		$cityPickupList	 = Config::get("cities.{$cityId}." . $baseKey);

		switch (true)
		{
			case ($cityPickupList != null && isset($cityPickupList[$tripType])):
				$maxTime = $cityPickupList[$tripType];
				break;

			case ($cityPickupList != null && isset($cityPickupList[0])):
				$maxTime = $cityPickupList[0];
				break;

			case ($pickupList != null && isset($pickupList[$tripType])):
				$maxTime = $pickupList[$tripType];
				break;

			case ($pickupList != null && isset($pickupList[0])):
			default:
				$maxTime = $pickupList[0];
				break;
		}
		result:
		return $maxTime;
	}

	/**
	 * Config description : get min pickup time for any booking based on partner id, trip type, tier type
	 * Example $data[A][B][C] :: A=>partnerId, B=>tripType, C=> tierType
	 * IF value not found for any category then value for the 0 index will considered as default
	 *
	 * @param int $partnerId
	 * @param integer $tripType
	 * @param integer $tier
	 * @return integer
	 */
	public static function getMinPickupDuration($partnerId, $tripType, $tier, $transferType = null)
	{
		$minDuration = null;
		if ($transferType != null)
		{
			$minDuration = self::getMinPickupDuration($partnerId, $tripType, $tier);

			$tripType = $tripType . $transferType;
		}


		//$object = json_decode(Config::get("booking.pickup.min.duration"));
		$data = json_decode(Config::get("booking.pickup.min.duration"), true);

		if ($partnerId == null)
		{
			$partnerId = 1249;
		}



		$defaultPartner	 = $data[0];
		$partnerData	 = isset($data[$partnerId]) ? $data[$partnerId] : $defaultPartner;

		$tripData	 = isset($partnerData[$tripType]) ? $partnerData[$tripType] : $partnerData[0];
		$tripData	 = isset($tripData) ? $tripData : $defaultPartner[$tripType];
		$tripData	 = isset($tripData) ? $tripData : $defaultPartner[0];

		$duration	 = isset($tripData[$tier]) ? $tripData[$tier] : $tripData[0];
		$duration	 = isset($duration) ? $duration : $defaultPartner[$tripType][$tier];
		$duration	 = isset($duration) ? $duration : $defaultPartner[$tripType][0];
		$duration	 = isset($duration) ? $duration : $defaultPartner[0][0];

		// if($duration == null && $minDuration !=null)
		// {
		// $duration = $minDuration;
		// }

		return $duration;
	}

	/**
	 * @param integer $agent
	 * @param integer $bkgType
	 * @param integer $serviceTier
	 * @return integer
	 */
	public static function getMinAdvancePercent($agent, $bkgType, $serviceTier, $isGozoNow = 0)
	{
		$data		 = Yii::app()->params['minPayPrecentage'];
		$agentData	 = (isset($data[$agent])) ? $data[$agent] : $data[0];
		$btData		 = (isset($agentData[$bkgType])) ? $agentData[$bkgType] : $agentData[0];
		$minPayPer	 = (isset($btData[$serviceTier])) ? $btData[$serviceTier] : $btData[0];
		//$minPayPer	 = ($isGozoNow == 1) ? 30 : $minPayPer;
		return $minPayPer;
	}

	public static function getGoogleApiKey($key)
	{
		$apiKey		 = Config::getFileParam('google')[$key];
		$cnt		 = count($apiKey);
		$day		 = date("d");
		$index		 = floor($day / ((date('t') + 1) / $cnt));
		$keyValue	 = $apiKey[$index];
		return $keyValue;
	}

	public static function getMapMyIndiaApiKey($key)
	{
		$apiKey		 = Config::getFileParam('mapMyIndia')[$key];
		$cnt		 = count($apiKey);
		$day		 = date("d");
		$index		 = floor($day / ((date('t') + 1) / $cnt));
		$keyValue	 = $apiKey[$index];
		return $keyValue;
	}

	public static function getFileParam($key)
	{
		return Yii::app()->params[$key];
	}

	/**
	 * This function is used to calculate margin tolerance factor based on service tier
	 * @param type $serviceTier
	 * @return type Array $toleranceFactor
	 */
	public static function getMarginToleranceFactor($serviceTier = 0)
	{
		$data			 = Config::getFileParam('marginTolerance');
		$marginTolerance = $data[$serviceTier] ? $data[$serviceTier] : $data[0];
		return $marginTolerance;
	}

	public static function getServerID()
	{
		return SERVER_ID;
	}

	/**
	 * 
	 * @param int $tripType
	 * @param int $tier
	 * @return int type
	 */
	public static function getMinGozoNowPickupDuration($tripType, $tier = null, $userType = null)
	{
		$data				 = json_decode(Config::get("booking.gozonow.pickup.min.duration"), true);
//		$data				 = json_decode('{"0":{"0":60,"1":60,"4":60,"9":60,"10":60,"11":60}}', true);
		$defaultData		 = $data[0];
		$tripData			 = $defaultData[$tripType];
		$tripdurationData	 = isset($tripData) ? $tripData : $defaultData[0];
		//$tripdurationData	 = 60;
        return ($userType == UserInfo::TYPE_ADMIN)? 0: $tripdurationData;
	}

	/**
	 * This function checks whether GozoNow is enabled with the given view
	 * @return bool
	 */
	public static function checkGozoNowEnabled()
	{
		$isGozoNowEnabled		 = Config::get("booking.gozoNow.isEnabled");
		$isMobileViewEnabled	 = Config::get("booking.gozoNow.isMobileViewEnabled");
		$isDesktopViewEnabled	 = Config::get("booking.gozoNow.isDesktopViewEnabled");
		$isMobileDetect			 = Yii::app()->mobileDetect->isMobile();
		$checkGozoNowEnabled	 = ($isGozoNowEnabled && (($isMobileDetect && $isMobileViewEnabled) || (!$isMobileDetect && $isDesktopViewEnabled ) ) ) ? true : false;
		return $checkGozoNowEnabled;
	}

	public static function getSecurityKey()
	{
		$key = Yii::app()->params['securityKey'];

		return $key;
	}

	public static function updateValueByName($name, $value)
	{
		$config = Config::model()->find('cfg_name=:name', ['name' => $name]);
		if ($config)
		{
			$config->cfg_value = $value;
			$config->update();

			return true;
		}
		return false;
	}

	public static function getGozoAddress($type = Config::Corporate_address, $includebr = false)
	{
		$gozoAddress = Config::get('gozo.address');
		$result		 = CJSON::decode($gozoAddress);
		$address	 = $result[$type];
		$separator	 = ($includebr) ? '<br>' : '';

		$address = $address['address'] . ", $separator" . $address['city']['name'] . ', ' . $address['state']['name'] . '-' . $address['pin'];
		return $address;
	}

	public static function getGozoAddressCity($type = Config::Corporate_address)
	{
		$gozoAddress = Config::get('gozo.address');
		$result		 = CJSON::decode($gozoAddress);
		$address	 = $result[$type];
		return $address['city']['name'];
	}

	/*
	 * function for limit sms *** incomplete/need to work and improve more
	 */

	public static function getSendSMSLimit()
	{
		$limit = Config::model()->getAccess("sms.limit.international");
		return $limit;
	}

	/**
	 * function for getting  json value by key against cfg_name
	 * @param type $cfg_name
	 * @param type $cfg_value_json_key
	 * @return int|array
	 */
	public static function getValue($cfg_name, $cfg_value_json_key = "")
	{
		if ($cfg_name == '')
		{
			return false;
		}
		$val		 = Config::model()->getAccess($cfg_name);
		$arrSettings = json_decode($val, true);
		if ($cfg_value_json_key != null)
		{
			return (isset($arrSettings[$cfg_value_json_key]) ? $arrSettings[$cfg_value_json_key] : false);
		}
		return $arrSettings;
	}

	public function getConfigList()
	{
		if ($this->cfg_active)
		{
			$where .= " AND cfg_active = 1 ";
		}
		if ($this->cfg_name != '')
		{
			$where .= " AND cfg_name LIKE '%{$this->cfg_name}%' ";
		}
		if ($this->cfg_value != '')
		{
			$where .= " AND cfg_value = '{$this->cfg_value}' ";
		}
		if ($this->cfg_description != '')
		{
			$where .= " AND cfg_description LIKE '%{$this->cfg_description}%' ";
		}
		$sql = "SELECT * FROM config WHERE 1 $where ";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['cfg_name', 'cfg_value', 'cfg_modified_date'],
				'defaultOrder'	 => ''], 'pagination'	 => ['pageSize' => 50],
		]);

		return $dataprovider;
	}
	public static function getMMIApiKey($key)
	{
		$apiKey		 = Config::getFileParam('google')[$key];
		$cnt		 = count($apiKey);
		$day		 = date("d");
		$index		 = floor($day / ((date('t') + 1) / $cnt));
		$keyValue	 = $apiKey[$index];
		return $keyValue;
	}

	public static function getValueByName($name)
	{
		$sql = "SELECT cfg_value FROM config WHERE cfg_active=1 AND cfg_env IN ('',:env) AND cfg_name=:name ORDER BY cfg_env ASC, cfg_name ASC";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['env' => APPLICATION_ENV, 'name' => $name], 60 * 60, $name);
	}

}
