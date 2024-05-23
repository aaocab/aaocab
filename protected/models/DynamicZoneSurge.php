<?php

/**
 * This is the model class for table "dynamic_zone_surge".
 *
 * The followings are the available columns in table 'dynamic_zone_surge':
 * @property integer $dzs_id
 * @property string $dzs_regionname
 * @property integer $dzs_regionid
 * @property integer $dzs_fromzoneid
 * @property string $dzs_fromzonename
 * @property string $dzs_frommasterzone
 * @property integer $dzs_frommasterzoneid
 * @property integer $dzs_tozoneid
 * @property string $dzs_tozonename
 * @property string $dzs_tomasterzone
 * @property integer $dzs_tomasterzoneid
 * @property integer $dzs_countbooking
 * @property integer $dzs_zone_type
 * @property double $dzs_profit
 * @property string $dzs_scv_label
 * @property integer $dzs_scv_id
 * @property integer $dzs_booking_type
 * @property double $dzs_destismaster
 * @property double $dzs_sourceismaster
 * @property double $dzs_targetmargin
 * @property double $dzs_difffromgoal
 * @property double $dzs_dzpp
 * @property string $dzs_createdate
 */
class DynamicZoneSurge extends CActiveRecord
{

	public $dzs_state;

	const POLICY_TYPE_FLEXI			 = "FLEXI";
	const POLICY_TYPE_SUPER_FLEXI		 = "SUPER_FLEXI";
	const POLICY_TYPE_NON_REFUNDABLE	 = "NON_REFUNDABLE";
	const ZONE_TYPE_Z1				 = 1;  // Z1
	const ZONE_TYPE_Z2				 = 2;  // Z2
	const ZONE_TYPE_Z3				 = 3;  // 
//
	const SERVICE_TIER_VALUE			 = 1;  // Value
	const SERVICE_TIER_VALUEPLUS		 = 2;  // Value+
	const SERVICE_TIER_SELECT			 = 4;  // select
	const SERVICE_TIER_SELECTPLUS		 = 5;  // Select+
	const SERVICE_TIER_CNG			 = 6;  // Cng

	public static $cancellationPolicyArr = [
		DynamicZoneSurge::ZONE_TYPE_Z1	 => [DynamicZoneSurge::SERVICE_TIER_CNG => DynamicZoneSurge::POLICY_TYPE_FLEXI, DynamicZoneSurge::SERVICE_TIER_VALUE => DynamicZoneSurge::POLICY_TYPE_FLEXI, DynamicZoneSurge::SERVICE_TIER_VALUEPLUS => DynamicZoneSurge::POLICY_TYPE_SUPER_FLEXI, DynamicZoneSurge::SERVICE_TIER_SELECT => DynamicZoneSurge::POLICY_TYPE_SUPER_FLEXI],
		DynamicZoneSurge::ZONE_TYPE_Z2	 => [DynamicZoneSurge::SERVICE_TIER_CNG => DynamicZoneSurge::POLICY_TYPE_NON_REFUNDABLE, DynamicZoneSurge::SERVICE_TIER_VALUE => DynamicZoneSurge::POLICY_TYPE_NON_REFUNDABLE, DynamicZoneSurge::SERVICE_TIER_VALUEPLUS => DynamicZoneSurge::POLICY_TYPE_FLEXI, DynamicZoneSurge::SERVICE_TIER_SELECT => DynamicZoneSurge::POLICY_TYPE_FLEXI],
		DynamicZoneSurge::ZONE_TYPE_Z3	 => [DynamicZoneSurge::SERVICE_TIER_CNG => DynamicZoneSurge::POLICY_TYPE_NON_REFUNDABLE, DynamicZoneSurge::SERVICE_TIER_VALUE => DynamicZoneSurge::POLICY_TYPE_NON_REFUNDABLE, DynamicZoneSurge::SERVICE_TIER_VALUEPLUS => DynamicZoneSurge::POLICY_TYPE_FLEXI, DynamicZoneSurge::SERVICE_TIER_SELECT => DynamicZoneSurge::POLICY_TYPE_FLEXI]
	];
	public $zone_type					 = [
		'1'	 => 'Z1',
		'2'	 => 'Z2',
		'3'	 => 'Z3'];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dynamic_zone_surge';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array
			(
			array('dzs_regionname, dzs_regionid, dzs_fromzoneid, dzs_fromzonename, dzs_frommasterzone, dzs_frommasterzoneid, dzs_tozoneid, dzs_tozonename, dzs_tomasterzone, dzs_tomasterzoneid, dzs_countbooking, dzs_profit, dzs_scv_label, dzs_scv_id, dzs_booking_type, dzs_destismaster, dzs_sourceismaster, dzs_targetmargin, dzs_difffromgoal, dzs_dzpp, dzs_createdate', 'required'),
			array('dzs_regionid, dzs_fromzoneid, dzs_frommasterzoneid, dzs_tozoneid, dzs_tomasterzoneid, dzs_countbooking, dzs_scv_id, dzs_booking_type', 'numerical', 'integerOnly' => true),
			array('dzs_profit, dzs_destismaster, dzs_sourceismaster, dzs_targetmargin, dzs_difffromgoal, dzs_dzpp', 'numerical'),
			array('dzs_regionname, dzs_fromzonename, dzs_frommasterzone, dzs_tozonename, dzs_tomasterzone, dzs_scv_label', 'length', 'max' => 255),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('dzs_id, dzs_regionname, dzs_regionid, dzs_fromzoneid, dzs_fromzonename, dzs_frommasterzone, dzs_frommasterzoneid, dzs_tozoneid, dzs_tozonename, dzs_tomasterzone, dzs_tomasterzoneid, dzs_countbooking, dzs_profit, dzs_scv_label, dzs_scv_id, dzs_booking_type, dzs_destismaster, dzs_sourceismaster, dzs_targetmargin, dzs_difffromgoal, dzs_dzpp, dzs_createdate', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'dzs_id'				 => 'Dzs',
			'dzs_regionname'		 => 'Dzs Regionname',
			'dzs_regionid'			 => 'Dzs Regionid',
			'dzs_fromzoneid'		 => 'Dzs Fromzoneid',
			'dzs_fromzonename'		 => 'Dzs Fromzonename',
			'dzs_frommasterzone'	 => 'Dzs Frommasterzone',
			'dzs_frommasterzoneid'	 => 'Dzs Frommasterzoneid',
			'dzs_tozoneid'			 => 'Dzs Tozoneid',
			'dzs_tozonename'		 => 'Dzs Tozonename',
			'dzs_tomasterzone'		 => 'Dzs Tomasterzone',
			'dzs_tomasterzoneid'	 => 'Dzs Tomasterzoneid',
			'dzs_countbooking'		 => 'Dzs Countbooking',
			'dzs_profit'			 => 'Dzs Profit',
			'dzs_scv_label'			 => 'Dzs Scv Label',
			'dzs_scv_id'			 => 'Dzs Scv',
			'dzs_booking_type'		 => 'Dzs Booking Type',
			'dzs_destismaster'		 => 'Dzs Destismaster',
			'dzs_sourceismaster'	 => 'Dzs Sourceismaster',
			'dzs_targetmargin'		 => 'Dzs Targetmargin',
			'dzs_difffromgoal'		 => 'Dzs Difffromgoal',
			'dzs_dzpp'				 => 'Dzs Dzpp',
			'dzs_createdate'		 => 'Dzs Createdate',
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

		$criteria->compare('dzs_id', $this->dzs_id);
		$criteria->compare('dzs_regionname', $this->dzs_regionname, true);
		$criteria->compare('dzs_regionid', $this->dzs_regionid);
		$criteria->compare('dzs_fromzoneid', $this->dzs_fromzoneid);
		$criteria->compare('dzs_fromzonename', $this->dzs_fromzonename, true);
		$criteria->compare('dzs_frommasterzone', $this->dzs_frommasterzone, true);
		$criteria->compare('dzs_frommasterzoneid', $this->dzs_frommasterzoneid);
		$criteria->compare('dzs_tozoneid', $this->dzs_tozoneid);
		$criteria->compare('dzs_tozonename', $this->dzs_tozonename, true);
		$criteria->compare('dzs_tomasterzone', $this->dzs_tomasterzone, true);
		$criteria->compare('dzs_tomasterzoneid', $this->dzs_tomasterzoneid);
		$criteria->compare('dzs_countbooking', $this->dzs_countbooking);
		$criteria->compare('dzs_profit', $this->dzs_profit);
		$criteria->compare('dzs_scv_label', $this->dzs_scv_label, true);
		$criteria->compare('dzs_scv_id', $this->dzs_scv_id);
		$criteria->compare('dzs_booking_type', $this->dzs_booking_type);
		$criteria->compare('dzs_destismaster', $this->dzs_destismaster);
		$criteria->compare('dzs_sourceismaster', $this->dzs_sourceismaster);
		$criteria->compare('dzs_targetmargin', $this->dzs_targetmargin);
		$criteria->compare('dzs_difffromgoal', $this->dzs_difffromgoal);
		$criteria->compare('dzs_dzpp', $this->dzs_dzpp);
		$criteria->compare('dzs_createdate', $this->dzs_createdate, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DynamicZoneSurge the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getDZPP(Quote &$quoteModel)
	{
		$scv_id		 = $quoteModel->cabType;
		$tripType	 = $quoteModel->tripType;
		$fromCity	 = $quoteModel->sourceCity;
		$toCity		 = $quoteModel->destinationCity;
		$fromZone	 = Zones::model()->getByCityId($fromCity);
		$toZone		 = Zones::model()->getByCityId($toCity);

		$fromZone	 = $fromZone != null && $fromZone != "" ? $fromZone : "-1";
		$toZone		 = $toZone != null && $toZone != "" ? $toZone : "-1";

		$rockBaseAmount	 = $quoteModel->routeRates->rockBaseAmount;
		$params			 = array();
		DBUtil::getINStatement($fromZone, $bindString, $params1);
		$params			 = array_merge($params1, $params);
		DBUtil::getINStatement($toZone, $bindString1, $params2);
		$params			 = array_merge($params2, $params);

		$sql			 = "SELECT dzs_id,dzs_90_14_final_dzpp AS dzs_dzpp,dzs_scv_id,dzs_booking_type,dzs_rate_update_days FROM dynamic_zone_surge WHERE dzs_fromzoneid IN ({$bindString}) AND dzs_tozoneid IN ({$bindString1}) AND dzs_dzpp IS NOT NULL ORDER BY  dzs_dzpp desc";
		$resDZPPMarkup	 = DBUtil::query($sql, DBUtil::SDB(), $params, 60 * 60 * 24 * 1, CacheDependency::Type_Surge);
		$row			 = DynamicZoneSurge::getRow($resDZPPMarkup, $scv_id, $tripType);
		if (empty($row))
		{
			$sql					 = "SELECT zsg_id AS dzs_id,zsg_dzpp AS dzs_dzpp,zsg_scv_id AS dzs_scv_id,zsg_booking_type AS dzs_booking_type, zsg_rate_update_days AS dzs_rate_update_days FROM zone_surge_global WHERE zsg_fromzoneid IN ({$bindString}) AND zsg_tozoneid IN ({$bindString1}) AND zsg_dzpp IS NOT NULL ORDER BY  zsg_dzpp desc";
			$resDZPPMarkupGlobally	 = DBUtil::query($sql, DBUtil::SDB(), $params, 60 * 60 * 24 * 1, CacheDependency::Type_Surge);
			$row					 = DynamicZoneSurge::getRow($resDZPPMarkupGlobally, $scv_id, $tripType);
			if ($row)
			{
				$row['surgeDesc'] = "DZPP global applied";
			}
		}
		return $row;
	}

	public static function getRow($resDZPPMarkup, $scv_id, $tripType)
	{
		foreach ($resDZPPMarkup as $val)
		{
			if ($val['dzs_scv_id'] == $scv_id && $val['dzs_booking_type'] == $tripType)
			{
				return $val;
			}
		}
		return [];
	}

	public static function getDZPPCancellationPolicy($fromCity, $toCity, $scv_id, $tripType)
	{
		$fromZone		 = Zones::model()->getByCityId($fromCity);
		$toZone			 = Zones::model()->getByCityId($toCity);
		$fromZone		 = $fromZone != null && $fromZone != "" ? $fromZone : "-1";
		$toZone			 = $toZone != null && $toZone != "" ? $toZone : "-1";
		$params			 = array();
		DBUtil :: getINStatement($fromZone, $bindString, $params1);
		$params			 = array_merge($params1, $params);
		DBUtil:: getINStatement($toZone, $bindString1, $params2);
		$params			 = array_merge($params2, $params);
		$sql			 = "SELECT dzs_zone_type,dzs_scv_id,dzs_booking_type FROM dynamic_zone_surge WHERE dzs_fromzoneid IN ({$bindString}) AND dzs_tozoneid IN ({$bindString1})  ORDER BY  dzs_zone_type ASC";
		$resDZPPMarkup	 = DBUtil::query($sql, DBUtil::SDB(), $params, 60 * 60 * 24 * 1, CacheDependency::Type_Surge);
		$row			 = DynamicZoneSurge::getRow($resDZPPMarkup, $scv_id, $tripType);
		return $row;
	}

	public static function getDZPPCancellationPolicyMatrix($cancellationZone, $tierType)
	{
		if ($cancellationZone != null && $tierType != null)
		{
			return self::$cancellationPolicyArr[$cancellationZone][$tierType];
		}
		elseif ($cancellationZone != null)
		{
			return self::$cancellationPolicyArr[$cancellationZone];
		}
		return array();
	}

	public static function getDZPPAddonCancellationPolicy($cancellationType)
	{
		if ($cancellationType == DynamicZoneSurge::POLICY_TYPE_NON_REFUNDABLE)
		{
			return [DynamicZoneSurge::POLICY_TYPE_FLEXI => 3, DynamicZoneSurge::POLICY_TYPE_SUPER_FLEXI => 8];
		}
		else if ($cancellationType == DynamicZoneSurge::POLICY_TYPE_FLEXI)
		{
			return [DynamicZoneSurge::POLICY_TYPE_NON_REFUNDABLE => -3, DynamicZoneSurge::POLICY_TYPE_SUPER_FLEXI => 5];
		}
		return array();
	}

	public static function getDZPPZoneType($fromCity, $toCity, $scv_id, $tripType)
	{
		$fromZone			 = Zones::model()->getByCityId($fromCity);
		$toZone				 = Zones::model()->getByCityId($toCity);
		$fromZone			 = $fromZone != null && $fromZone != "" ? $fromZone : "-1";
		$toZone				 = $toZone != null && $toZone != "" ? $toZone : "-1";
		$params				 = array();
		DBUtil::getINStatement($fromZone, $bindString, $params1);
		$params				 = array_merge($params1, $params);
		DBUtil::getINStatement($toZone, $bindString1, $params2);
		$params				 = array_merge($params2, $params);
		$params['scv_id']	 = $scv_id;
		$params['tripType']	 = $tripType;
		$sql				 = "SELECT dzs_zone_type FROM dynamic_zone_surge WHERE dzs_fromzoneid IN ({$bindString}) AND dzs_tozoneid IN ({$bindString1}) 
                               AND dzs_scv_id=:scv_id AND dzs_booking_type=:tripType ORDER BY  dzs_zone_type ASC";
		$row				 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		if (empty($row))
		{
			$date1	 = date_create("2021-12-03 00:00:00");
			$date2	 = date_create(date("Y-m-d h:i:s"));
			$diff	 = date_diff($date1, $date2);
			$days	 = $diff->format("%a");
			if ($days > 0)
			{
				$sql = "SELECT 
                    CASE
                        WHEN (zsg_countbooking/$days)>=1 THEN 1
                        WHEN (zsg_countbooking/$days)<1 AND (zsg_countbooking/$days)>=0.5 THEN 2
                        ELSE 3
                    END AS  dzs_zone_type 
                    FROM zone_surge_global 
                    WHERE 1
                    AND zsg_fromzoneid IN ({$bindString}) 
                    AND zsg_tozoneid IN ({$bindString1}) 
                    AND zsg_scv_id=:scv_id
                    AND zsg_booking_type=:tripType 
                    ORDER BY  dzs_zone_type ASC";
				$row = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
				if (empty($row))
				{
					$row['dzs_zone_type'] = 3;
				}
			}
			else
			{
				$row['dzs_zone_type'] = 3;
			}
		}
		return $row;
	}

	public static function getDZPPZoneTypeTest($fromCity, $toCity, $scv_id, $tripType)
	{
		echo "<pre>";
		$fromZone			 = Zones::model()->getByCityId($fromCity);
		$toZone				 = Zones::model()->getByCityId($toCity);
		$fromZone			 = $fromZone != null && $fromZone != "" ? $fromZone : "-1";
		$toZone				 = $toZone != null && $toZone != "" ? $toZone : "-1";
		$params				 = array();
		DBUtil::getINStatement($fromZone, $bindString, $params1);
		$params				 = array_merge($params1, $params);
		DBUtil::getINStatement($toZone, $bindString1, $params2);
		$params				 = array_merge($params2, $params);
		$params['scv_id']	 = $scv_id;
		$params['tripType']	 = $tripType;
		echo " fromZone: " . $fromZone . "<br>";
		echo " toZone: " . $toZone . "<br>";
		echo " scv_id: " . $scv_id . "<br>";
		echo " tripType: " . $tripType . "<br>";

		$sql = "SELECT dzs_zone_type FROM dynamic_zone_surge WHERE dzs_fromzoneid IN ({$bindString}) AND dzs_tozoneid IN ({$bindString1}) 
                               AND dzs_scv_id=:scv_id AND dzs_booking_type=:tripType ORDER BY  dzs_zone_type ASC";
		$row = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		if (empty($row))
		{
			$date1	 = date_create("2021-12-03 00:00:00");
			$date2	 = date_create(date("Y-m-d h:i:s"));
			$diff	 = date_diff($date1, $date2);
			$days	 = $diff->format("%a");
			echo " Days: " . $days . "<br>";
			if ($days > 0)
			{
				$sql = "SELECT 
                    CASE
                        WHEN (zsg_countbooking/$days)>=1 THEN 1
                        WHEN (zsg_countbooking/$days)<1 AND (zsg_countbooking/$days)>=0.5 THEN 2
                        ELSE 3
                    END AS  dzs_zone_type 
                    FROM zone_surge_global 
                    WHERE 1
                    AND zsg_fromzoneid IN ({$bindString}) 
                    AND zsg_tozoneid IN ({$bindString1}) 
                    AND zsg_scv_id=:scv_id
                    AND zsg_booking_type=:tripType 
                    ORDER BY  dzs_zone_type ASC";
				$row = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
				print_r($row);
			}
			else
			{
				$row['dzs_zone_type'] = 3;
				print_r($row);
			}
		}
		return $row;
	}

	public static function getRowIdentifier($fromCity, $toCity, $scv_id, $tripType)
	{
		$fromZone	 = Zones::getZoneByCityId($fromCity);
		$toZone		 = Zones::getZoneByCityId($toCity);
		$regionId	 = States::model()->getZoenId($fromCity);
		if ($regionId == null)
		{
			$regionId = 1;
		}
		$param	 = array('regionId' => $regionId, 'fromZone' => $fromZone, 'toZone' => $toZone, 'scv_id' => $scv_id, 'tripType' => $tripType);
		$sql	 = "SELECT CONCAT('7','',LPAD(:regionId,2,'0'),'',LPAD(:fromZone,5,'0'),'',LPAD(:toZone,5,'0'),'',LPAD(:scv_id,3,'0'),'',LPAD(:tripType,2,'0')) AS rowIdentifier FROM DUAL";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
	}

	public static function getZoneIdentifier($fromCity, $toCity)
	{
		$fromZone	 = Zones::getZoneByCityId($fromCity);
		$toZone		 = Zones::getZoneByCityId($toCity);
		$regionId	 = States::model()->getZoenId($fromCity);
		if ($regionId == null)
		{
			$regionId = 1;
		}
		$param	 = array('regionId' => $regionId, 'fromZone' => $fromZone, 'toZone' => $toZone);
		$sql	 = "SELECT CONCAT('7','',LPAD(:regionId,2,'0'),'',LPAD(:fromZone,5,'0'),'',LPAD(:toZone,5,'0')) AS zoneIdentifier FROM DUAL";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
	}

	public static function Yearwise_rowIdentifier($year, $rowIdentifier)
	{
		$sql = "SELECT
                CASE
                    WHEN (count/115)>=1 THEN 1
                    WHEN (count/115)<1 AND (count/115)>=0.5 THEN 2
                    ELSE 3
                END AS  dzs_zone_type
                FROM Yearwise_rowIdentifier WHERE 1 AND Year=:year AND row_identifier=:rowIdentifier";
		$row = DBUtil::queryRow($sql, DBUtil::SDB(), ['year' => $year, 'rowIdentifier' => $rowIdentifier]);
		if (empty($row))
		{
			$row['dzs_zone_type'] = 3;
		}
		return $row;
	}

	public static function Yearwise_zoneIdentifier($year, $zoneIdentifier)
	{
		$sql = "SELECT
                CASE
                    WHEN (count/115)>=1 THEN 1
                    WHEN (count/115)<1 AND (count/115)>=0.5 THEN 2
                    ELSE 3
                END AS  dzs_zone_type
                FROM Yearwise_zoneIdentifier WHERE 1 AND Year=:year AND zone_identifier=:zone_identifier";
		$row = DBUtil::queryRow($sql, DBUtil::SDB(), ['year' => $year, 'zone_identifier' => $zoneIdentifier]);
		if (empty($row))
		{
			$row['dzs_zone_type'] = 3;
		}
		return $row;
	}

	public static function getDZPPReport($model)
	{
		$params = [];
		if (count($model->dzs_fromzoneid) > 0)
		{
			$params['fromZoneid']	 = implode(",", $model->dzs_fromzoneid);
			$where					 .= "  AND (dzs_fromzoneid  IN (:fromZoneid)) ";
		}
		if (count($model->dzs_tozoneid) > 0)
		{
			$params['toZoneid']	 = implode(",", $model->dzs_tozoneid);
			$where				 .= "  AND (dzs_tozoneid  IN (:toZoneid)) ";
		}
		if (count($model->dzs_booking_type) > 0)
		{
			$params['bookingType']	 = implode(",", $model->dzs_booking_type);
			$where					 .= "  AND (dzs_booking_type IN (:bookingType)) ";
		}
		if (count($model->dzs_scv_id) > 0)
		{
			$params['vehicleId'] = implode(",", $model->dzs_scv_id);
			$where				 .= "  AND (dzs_scv_id IN (:vehicleId)) ";
		}
		if (count($model->dzs_regionid) > 0)
		{
			$params['regionId']	 = implode(",", $model->dzs_regionid);
			$where				 .= "  AND (dzs_regionid IN (:regionId)) ";
		}
		if (count($model->dzs_zone_type) > 0)
		{
			$params['ZoneType']	 = implode(",", $model->dzs_zone_type);
			$where				 .= "  AND (dzs_zone_type IN (:ZoneType)) ";
		}
		if (count($model->dzs_state) > 0)
		{
			$stateIds				 = implode(",", $model->dzs_state);
			$params['StateTypeZone'] = Zones::getZoneByStateId($stateIds);
			$where					 .= "  AND (dzs_fromzoneid IN (:StateTypeZone)) ";
		}
		$sqlCount		 = "SELECT dzs_row_identifier FROM dynamic_zone_surge WHERE 1 $where";
		$sql			 = "SELECT 
							dzs_row_identifier AS RowIdentifier,
							dzs_regionname AS  Region,
							dzs_fromzonename AS   FromZoneName,
							dzs_tozonename AS  ToZoneName,
							dzs_countbooking AS CountBooking,
							dzs_zone_type AS ZoneType,
							dzs_profit AS Profit,
							dzs_scv_label AS  scv_label,
							dzs_booking_type AS booking_type,
							dzs_90_14_final_dzpp AS DZPP,
							dzs_cntInquiry,
							dzs_cntCreated,
							dzs_conversionPer,
							dzs_completionPer,
							dzs_va,
							dzs_ca,
							dzs_suggested_va,
							dzs_suggested_ca,
							IFNULL(dzs_rate_update_days,0) AS rateUpdateDays,
							ROUND(IF(dzs_rate_update_days IS NULL OR dzs_rate_update_days=0,dzs_90_14_final_dzpp,IF(dzs_rate_update_days<=30,((dzs_90_14_final_dzpp - 1) * 0.33 + 1),((dzs_90_14_final_dzpp - 1) * 0.66 + 1))),2) AS finalDZPP
							FROM dynamic_zone_surge
							WHERE 1 $where";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			"params"		 => $params,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['DZPP', 'Profit', 'CountBooking', 'rateUpdateDays', 'finalDZPP'],
				'defaultOrder'	 => 'DZPP DESC'],
			'pagination'	 => ['pageSize' => 1000],
		]);

		return $dataprovider;
	}

	public static function getDZPPDetailsReport($row)
	{
		$params = [];
		if ($row['fromZone'] > 0)
		{
			$params['fromZoneid']	 = $row['fromZone'];
			$where					 .= "  AND (z1.zon_id  IN (:fromZoneid)) ";
		}
		if ($row['fromZone'] > 0)
		{
			$params['toZoneid']	 = $row['toZone'];
			$where				 .= "  AND (z2.zon_id  IN (:toZoneid)) ";
		}
		if ($row['bookingType'] > 0)
		{
			$params['bookingType']	 = $row['bookingType'];
			$where					 .= "  AND (bkg.bkg_booking_type IN (:bookingType)) ";
		}
		if ($row['vehicleId'] > 0)
		{
			$params['vehicleId'] = $row['vehicleId'];
			$where				 .= "  AND (bkg.bkg_vehicle_type_id IN (:vehicleId)) ";
		}
		if ($row['regionId'] > 0)
		{
			$params['regionId']	 = $row['regionId'];
			$where				 .= "  AND (stt.stt_zone IN (:regionId)) ";
		}
		$sqlCount		 = "SELECT 
                        bkg.bkg_id
                        FROM booking bkg
                        JOIN booking_cab ON booking_cab.bcb_id = bkg.bkg_bcb_id
                        JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id
                        JOIN booking_price_factor bpf ON bpf.bpf_bkg_id = bkg.bkg_id
                        JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id
                        JOIN svc_class_vhc_cat scvc ON scvc.scv_id = bkg.bkg_vehicle_type_id AND scvc.scv_active=1
                        JOIN vehicle_category vhc ON scvc.scv_vct_id = vhc.vct_id AND vhc.vct_active=1
                        JOIN service_class sc ON scvc.scv_scc_id = sc.scc_id AND sc.scc_active=1
                        JOIN cities c1 ON c1.cty_id = bkg.bkg_from_city_id AND c1.cty_active=1
                        JOIN cities c2 ON c2.cty_id = bkg.bkg_to_city_id   AND c2.cty_active=1
                        JOIN states stt ON stt.stt_id = c1.cty_state_id  AND stt.stt_active='1'
                        JOIN states s2 ON s2.stt_id = c2.cty_state_id  AND s2.stt_active='1'
                        JOIN zone_cities zc1 ON zc1.zct_cty_id = c1.cty_id AND zc1.zct_active=1
                        JOIN zone_cities zc2 ON zc2.zct_cty_id = c2.cty_id  AND zc2.zct_active=1
                        JOIN zones z1 ON z1.zon_id = zc1.zct_zon_id  AND z1.zon_active=1
                        JOIN zones z2 ON z2.zon_id = zc2.zct_zon_id  AND z2.zon_active=1
                        JOIN geo_zones1 gz1 ON z1.zon_id = gz1.zon_id  
                        JOIN geo_zones1 gz2 ON z2.zon_id = gz2.zon_id
                        WHERE 1 AND bkg.bkg_pickup_date BETWEEN (CURDATE() - INTERVAL 90 DAY) AND (CURDATE() - INTERVAL 01 DAY) AND bkg.bkg_status IN (6, 7) 
                        AND booking_cab.bcb_trip_type=0
                        $where
                        GROUP BY booking_cab.bcb_id";
		$sql			 = "SELECT 
                bkg.bkg_id AS bookingId,
                bkg.bkg_create_date AS createDate,
                bkg.bkg_pickup_date AS pickupDate,                        
                c1.cty_display_name AS fromCity,
                c2.cty_display_name AS toCity,
                biv.bkg_total_amount AS totalBookingAmt,               
                (biv.bkg_gozo_amount- IFNULL(biv.bkg_credits_used,0)) AS GozoAmount,
                ROUND((((biv.bkg_gozo_amount- IFNULL(biv.bkg_credits_used,0)) / (biv.bkg_net_base_amount)) * 100),2) AS Profit,  
                scvc.scv_label AS serviceLabel,
                (
                    CASE  bkg_surge_applied 
                    WHEN 0 THEN 'Regular'
                    WHEN 1 THEN 'Manual'
                    WHEN 2 THEN 'DDBP'
                    WHEN 3 THEN 0
                    WHEN 4 THEN 'DTBP'
                    WHEN 5 THEN 'Profitability'
                    WHEN 6 THEN 'DZPP'
                    WHEN 7 THEN 'DZPP'
					WHEN 8 THEN 'DUR'
                    WHEN 9 THEN 'DEBP'
                    WHEN 10 THEN 'DDBP(V2)'
                    END
                ) AS surgeFactor,                  
                (
                    CASE  bkg.bkg_booking_type
                        WHEN 1 THEN 'One Way'
                        WHEN 2 THEN 'Round Trip/Multi City'
                        WHEN 3 THEN 'Round Trip/Multi City'
                        WHEN 4 THEN 'Airport Transfer'
                        WHEN 5 THEN 'Package'
                        WHEN 6 THEN 'Flexxi'
                        WHEN 7 THEN 'Shuttle'	
                        WHEN 8 THEN 'Custom'
                        WHEN 9 THEN 'Day Rental 4hr-40km'
                        WHEN 10 THEN 'Day Rental 8hr-80km'
                        WHEN 11 THEN 'Day Rental 12hr-120km'
                        WHEN 12 THEN 'Airport Packages'
						WHEN 15 THEN 'Local Transfer'
                    END
                ) AS bookingType 
                FROM booking bkg
                JOIN booking_cab ON booking_cab.bcb_id = bkg.bkg_bcb_id
                JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id
                JOIN booking_price_factor bpf ON bpf.bpf_bkg_id = bkg.bkg_id
                JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id
                JOIN svc_class_vhc_cat scvc ON scvc.scv_id = bkg.bkg_vehicle_type_id AND scvc.scv_active=1
                JOIN vehicle_category vhc ON scvc.scv_vct_id = vhc.vct_id AND vhc.vct_active=1
                JOIN service_class sc ON scvc.scv_scc_id = sc.scc_id AND sc.scc_active=1
                JOIN cities c1 ON c1.cty_id = bkg.bkg_from_city_id AND c1.cty_active=1
                JOIN cities c2 ON c2.cty_id = bkg.bkg_to_city_id   AND c2.cty_active=1
                JOIN states stt ON stt.stt_id = c1.cty_state_id  AND stt.stt_active='1'
                JOIN states s2 ON s2.stt_id = c1.cty_state_id  AND s2.stt_active='1'
                JOIN zone_cities zc1 ON zc1.zct_cty_id = c1.cty_id AND zc1.zct_active=1
                JOIN zone_cities zc2 ON zc2.zct_cty_id = c2.cty_id  AND zc2.zct_active=1
                JOIN zones z1 ON z1.zon_id = zc1.zct_zon_id  AND z1.zon_active=1
                JOIN zones z2 ON z2.zon_id = zc2.zct_zon_id  AND z2.zon_active=1
                JOIN geo_zones1 gz1 ON z1.zon_id = gz1.zon_id  
                JOIN geo_zones1 gz2 ON z2.zon_id = gz2.zon_id
                WHERE 1 AND bkg.bkg_pickup_date BETWEEN (CURDATE() - INTERVAL 90 DAY) AND (CURDATE() - INTERVAL 01 DAY) AND bkg.bkg_status IN (6, 7) 
                AND booking_cab.bcb_trip_type=0
                $where
                GROUP BY booking_cab.bcb_id";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			"params"		 => $params,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['bookingId', 'createDate', 'pickupDate', 'surgeFactor', 'GozoAmount', 'Profit', 'totalBookingAmt'],
				'defaultOrder'	 => 'bookingId DESC'],
			'pagination'	 => ['pageSize' => 1000],
		]);
		return $dataprovider;
	}

	public static function getDZPPROWIdentifier($date)
	{
		$sql = "SELECT dzs_id,`dzs_row_identifier` FROM dynamic_zone_surge_1day WHERE 1 AND  dzs_active=1 AND dzs_createdate=:date";
		return DBUtil::query($sql, DBUtil::SDB(), ['date' => $date]);
	}

	public static function getZoneTypeByCityCab($fcity, $tcity, $cab, $tripType)
	{
		$fromZone			 = Zones::model()->getByCityId($fcity);
		$toZone				 = Zones::model()->getByCityId($tcity);
		$fromZone			 = $fromZone != null && $fromZone != "" ? $fromZone : "-1";
		$toZone				 = $toZone != null && $toZone != "" ? $toZone : "-1";
		$params				 = array();
		DBUtil :: getINStatement($fromZone, $bindString, $params1);
		$params				 = array_merge($params1, $params);
		DBUtil:: getINStatement($toZone, $bindString1, $params2);
		$params				 = array_merge($params2, $params);
		$params['cab']		 = $cab;
		$params['tripType']	 = $tripType;
		$sql				 = "SELECT dzs_zone_type FROM dynamic_zone_surge WHERE dzs_fromzoneid IN ({$bindString}) AND dzs_tozoneid IN ({$bindString1}) AND dzs_scv_id=:cab AND dzs_booking_type=:tripType  ORDER BY  dzs_zone_type ASC";
		$zoneType			 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params, 60 * 60 * 24 * 1, CacheDependency::Type_Surge);
		return $zoneType;
	}

	public static function getCityIdentifier($fromCity, $toCity)
	{
		$regionId = States::model()->getZoenId($fromCity);
		if ($regionId == null)
		{
			$regionId = 1;
		}
		$param	 = array('regionId' => $regionId, 'fromCity' => $fromCity, 'toCity' => $toCity);
		$sql	 = "SELECT CONCAT('7','',LPAD(:regionId,2,'0'),'',LPAD(:fromCity,8,'0'),'',LPAD(:toCity,8,'0')) AS cityIdentifier FROM DUAL";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
	}

	public static function getDemandIdentifier($fromCity, $tripType)
	{
		$fromZone	 = Zones::getZoneByCityId($fromCity);
		$regionId	 = States::model()->getZoenId($fromCity);
		if ($regionId == null)
		{
			$regionId = 1;
		}
		$type	 = in_array($tripType, array("4", "9", "10", "11", "12")) ? 1 : 2;
		$param	 = array('regionId' => $regionId, 'fromZone' => $fromZone, 'tripType' => $type);
		$sql	 = "SELECT CONCAT('7','',LPAD(:regionId,2,'0'),'',LPAD(:fromZone,5,'0'),LPAD(:tripType,2,'0')) AS demandIdentifier FROM DUAL";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
	}

	public static function getRowIdentifierByDemandZone($rowIdentifier)
	{
		$sql	 = "SELECT COUNT(tdz_id) AS cnt FROM top_demand_zone WHERE 1 AND tdz_row_identifier=:rowIdentifier AND tdz_active=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['rowIdentifier' => $rowIdentifier]);
	}

}
