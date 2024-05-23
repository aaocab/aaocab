<?php

/**
 * This is the model class for table "channel_partner_markup".
 *
 * The followings are the available columns in table 'channel_partner_markup':
 * @property integer $cpm_id
 * @property integer $cpm_agent_id
 * @property string $cpm_from_date
 * @property string $cpm_to_date
 * @property integer $cpm_value_type
 * @property string $cpm_value
 * @property integer $cpm_apply_surge
 * @property integer $cpm_source_city
 * @property integer $cpm_destination_city
 * @property integer $cpm_source_zone
 * @property integer $cpm_destination_zone
 * @property integer $cpm_vehicle_type
 * @property integer $cpm_trip_type
 * @property string $cpm_desc
 * @property integer $cpm_active
 * @property string $cpm_modified
 * @property string $cpm_created
 * @property string $cpm_log
 *
 * The followings are the available model relations:
 * @property Agents $cpmAgent
 * @property Cities $cpmSourceCity
 * @property Cities $cpmDestCity
 * @property Zones $cpmSourceZone
 * @property Zones $cpmDestZone
 * @property SvcClassVhcCat $cpmVehicleType
 */
class ChannelPartnerMarkup extends CActiveRecord
{

	public $cpm_value_type_arr = [1 => 'Amount', 2 => 'Percentage', 3 => 'Fixed Amount'];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'channel_partner_markup';
	}

	public function defaultScope()
	{
		$ta	 = $this->getTableAlias(false, false);
		$arr = array(
			'condition' => "cpm_active IN (1)",
		);
		return $arr;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('cpm_agent_id', 'required'),
			array('cpm_agent_id, cpm_value_type, cpm_value', 'required', 'on' => 'insert,update'),
			array('cpm_agent_id, cpm_value_type, cpm_apply_surge, cpm_source_city, cpm_destination_city, cpm_source_zone, cpm_destination_zone, cpm_vehicle_type, cpm_trip_type, cpm_active', 'numerical', 'integerOnly' => true),
			array('cpm_value', 'length', 'max' => 10),
			array('cpm_desc', 'length', 'max' => 500),
			array('cpm_log', 'length', 'max' => 5000),
			array('cpm_id, cpm_agent_id, cpm_from_date, cpm_to_date, cpm_value_type, cpm_value, cpm_source_city, cpm_destination_city, cpm_source_zone, cpm_destination_zone, cpm_vehicle_type, cpm_trip_type, cpm_desc, cpm_active, cpm_modified, cpm_created, cpm_log', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cpm_id, cpm_agent_id, cpm_from_date, cpm_to_date, cpm_value_type, cpm_value, cpm_source_city, cpm_destination_city, cpm_source_zone, cpm_destination_zone, cpm_vehicle_type, cpm_trip_type, cpm_desc, cpm_active, cpm_modified, cpm_created, cpm_log', 'safe', 'on' => 'search'),
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
			'cpmAgent'		 => array(self::BELONGS_TO, 'Agents', 'cpm_agent_id'),
			'cpmSourceCity'	 => array(self::BELONGS_TO, 'Cities', 'cpm_source_city'),
			'cpmDestCity'	 => array(self::BELONGS_TO, 'Cities', 'cpm_destination_city'),
			'cpmSourceZone'	 => array(self::BELONGS_TO, 'Zones', 'cpm_source_zone'),
			'cpmDestZone'	 => array(self::BELONGS_TO, 'Zones', 'cpm_destination_zone'),
			'cpmVehicleType' => [self::BELONGS_TO, 'SvcClassVhcCat', 'cpm_vehicle_type'],
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cpm_id'				 => 'CPM Id',
			'cpm_agent_id'			 => 'Agent',
			'cpm_from_date'			 => 'From Date',
			'cpm_to_date'			 => 'To Date',
			'cpm_value_type'		 => 'Value Type',
			'cpm_value'				 => 'Value',
			'cpm_apply_surge'		 => 'Apply Surge',
			'cpm_source_city'		 => 'Source City',
			'cpm_destination_city'	 => 'Destination City',
			'cpm_source_zone'		 => 'Source Zone',
			'cpm_destination_zone'	 => 'Destination Zone',
			'cpm_vehicle_type'		 => 'Vehicle Type',
			'cpm_trip_type'			 => 'Trip Type',
			'cpm_desc'				 => 'Desc',
			'cpm_active'			 => 'Active',
			'cpm_modified'			 => 'Modified',
			'cpm_created'			 => 'Created',
			'cpm_log'				 => 'Log',
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

		if ($this->cpm_from_date != '')
		{
			$criteria->addCondition("DATE(cpm_from_date)<='{$this->cpm_from_date}' AND DATE(cpm_to_date)>='{$this->cpm_from_date}'");
		}
		else
		{
			$criteria->addCondition("DATE(cpm_to_date)>=CURDATE()");
		}
		if ($this->cpm_to_date != '')
		{
			$criteria->compare('DATE(cpm_to_date)', $this->cpm_to_date, true);
		}

		//   $criteria->compare('cpm_id', $this->cpm_id);
		$criteria->compare('cpm_agent_id', $this->cpm_agent_id);
		// $criteria->compare('cpm_from_date', $this->cpm_from_date, true);
		// $criteria->compare('cpm_to_date', $this->cpm_to_date, true);
//        $criteria->compare('cpm_value_type', $this->cpm_value_type);
//        $criteria->compare('cpm_value', $this->cpm_value);
		//  $criteria->compare('cpm_apply_surge',$this->cpm_apply_surge);
		$criteria->compare('cpm_source_city', $this->cpm_source_city);
		$criteria->compare('cpm_destination_city', $this->cpm_destination_city);
		$criteria->compare('cpm_source_zone', $this->cpm_source_zone);
		$criteria->compare('cpm_destination_zone', $this->cpm_destination_zone);
		$criteria->compare('cpm_vehicle_type', $this->cpm_vehicle_type);
		$criteria->compare('cpm_trip_type', $this->cpm_trip_type);
		$criteria->compare('cpm_desc', $this->cpm_desc, true);
		$criteria->compare('cpm_active', $this->cpm_active);
		$criteria->compare('cpm_modified', $this->cpm_modified, true);
		$criteria->compare('cpm_created', $this->cpm_created, true);
		$criteria->compare('cpm_log', $this->cpm_log, true);

		$criteria->with		 = ['cpmSourceCity', 'cpmDestCity', 'cpmAgent', 'cpmSourceZone', 'cpmDestZone', 'cpmVehicleType'];
		$criteria->together	 = true;
		// $criteria->order="cpm_to_date DESC";
		return new CActiveDataProvider($this->together(), array(
			'criteria'	 => $criteria, 'sort'		 => ['attributes'	 => ['cpm_from_date', 'cpm_to_date'],
				'defaultOrder'	 => 'cpm_from_date ASC, cpm_to_date ASC']
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ChannelPartnerMarkup the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getPricing($model, $amount, $partnerId = 0)
	{
		/* @var $model Booking */
		$partner = ($partnerId == 0) ? $model->bkg_agent_id : $partnerId;
		if ($partner > 0)
		{
			if (($model->bkg_from_city_id == null || $model->bkg_from_city_id == "") || ($model->bkg_to_city_id == null || $model->bkg_to_city_id == "") || ($model->bkg_pickup_date == null || $model->bkg_pickup_date == ""))
			{
				throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
			}
			$model->bkg_agent_id = $partner;
			$fcity				 = $model->bkg_from_city_id;
			$tcity				 = $model->bkg_to_city_id;
			$pdate				 = $model->bkg_pickup_date;
			$sql_vehicle_type	 = ($model->bkg_vehicle_type_id != '') ? " AND (cpm_vehicle_type=$model->bkg_vehicle_type_id OR cpm_vehicle_type IS NULL)" : "";
			$sql_trip_type		 = ($model->bkg_booking_type != '') ? " AND (cpm_trip_type=$model->bkg_booking_type OR cpm_trip_type IS NULL)" : "";
			$srcZonesVal		 = DBUtil::command("SELECT GROUP_CONCAT(z2.zct_zon_id) as srcZones FROM zone_cities z2
								WHERE z2.zct_active=1 AND zct_cty_id=$fcity GROUP BY z2.zct_cty_id")->queryScalar();
			$srcZones			 = (trim($srcZonesVal) == "") ? 'NULL' : $srcZonesVal;
			$dstZonesVal		 = DBUtil::command("SELECT GROUP_CONCAT(z2.zct_zon_id) as srcZones FROM zone_cities z2
								WHERE z2.zct_active=1 AND zct_cty_id=$tcity GROUP BY z2.zct_cty_id")->queryScalar();
			$dstZones			 = (trim($dstZonesVal) == "") ? 'NULL' : $dstZonesVal;
			$sql				 = "SELECT * FROM (
                    SELECT *, FLOOR(IF((cpm_value_type=1),(cpm_value+$amount),(IF((cpm_value_type=2),($amount+($amount*(cpm_value/100))),cpm_value))))amount,
                        ((SELECT GROUP_CONCAT(z2.zct_zon_id) as srcZones FROM zone_cities z2
                            WHERE z2.zct_active=1 AND zct_cty_id=$fcity GROUP BY z2.zct_cty_id)) as srcZones,
                        (SELECT GROUP_CONCAT(z2.zct_zon_id) as srcZones FROM zone_cities z2
                            WHERE z2.zct_active=1 AND zct_cty_id=$tcity GROUP BY z2.zct_cty_id) as dstZones,
                        	(
				    IF(cpm_agent_id IS NOT NULL, 1,0) +
				    IF(cpm_source_city IS NOT NULL,54,0) +
				    IF(cpm_destination_city IS NOT NULL,54,0) +
				    IF(cpm_source_zone IS NOT NULL,18,0) +
				    IF(cpm_destination_zone IS NOT NULL,18,0) +
				    IF(cpm_source_state IS NOT NULL,6,0) +
				    IF(cpm_destination_state IS NOT NULL,6,0) +
				    IF(cpm_trip_type IS NOT NULL,1,0) +
				    IF(cpm_vehicle_type IS NOT NULL,1,0)
				   ) as rank
                    FROM `channel_partner_markup`
                    WHERE cpm_active=1) a
                WHERE
                (
                (cpm_source_city IS NULL OR cpm_source_city=$fcity) AND
                (cpm_destination_city IS NULL OR cpm_destination_city=$tcity) AND
                (cpm_source_zone IS NULL OR cpm_source_zone IN (srcZones)) AND
                (cpm_destination_zone IS NULL OR cpm_destination_zone IN (dstZones))
                )
                AND cpm_agent_id = $partner
                AND ('$pdate' BETWEEN cpm_from_date AND cpm_to_date) $sql_vehicle_type $sql_trip_type
            ORDER BY rank DESC, amount DESC  LIMIT 1";
			return DBUtil::queryRow($sql);
		}
		return false;
	}

	/** @param Quote $quoteModel */
	public function calculateMarkup(&$quoteModel)
	{
		$partner = $quoteModel->partnerId;
		if ($partner > 0)
		{
			$fcity				 = $quoteModel->servingRoute['start'];
			$tcity				 = $quoteModel->servingRoute['end'];
			$amount				 = $quoteModel->routeRates->baseAmount;
			$pdate				 = $quoteModel->routes[0]->brt_pickup_datetime;
			$sql_vehicle_type	 = ($quoteModel->cabType != '') ? " AND (cpm_vehicle_type=$quoteModel->cabType OR cpm_vehicle_type IS NULL)" : "";
			$sql_trip_type		 = ($quoteModel->tripType != '') ? " AND (cpm_trip_type=$quoteModel->tripType OR cpm_trip_type IS NULL)" : "";
			if (($fcity == null || $fcity == "") || ($tcity == null || $tcity == "") || ($pdate == null || $pdate == ""))
			{
				throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
			}

			$sql = "SELECT * FROM (
                                SELECT *, FLOOR(IF((cpm_value_type=1),(cpm_value+$amount),(IF((cpm_value_type=2),($amount+($amount*(cpm_value/100))),cpm_value))))amount,
                                    ((SELECT GROUP_CONCAT(z2.zct_zon_id) as srcZones FROM zone_cities z2
                                        WHERE z2.zct_active=1 AND zct_cty_id=$fcity GROUP BY z2.zct_cty_id)) as srcZones,
                                    (SELECT GROUP_CONCAT(z2.zct_zon_id) as srcZones FROM zone_cities z2
                                        WHERE z2.zct_active=1 AND zct_cty_id=$tcity GROUP BY z2.zct_cty_id) as dstZones,
                                    (
                                    IF(cpm_agent_id IS NOT NULL, 1,0) +
                                    IF(cpm_source_city IS NOT NULL,54,0) +
                                    IF(cpm_destination_city IS NOT NULL,54,0) +
                                    IF(cpm_source_zone IS NOT NULL,18,0) +
                                    IF(cpm_destination_zone IS NOT NULL,18,0) +
                                    IF(cpm_source_state IS NOT NULL,6,0) +
                                    IF(cpm_destination_state IS NOT NULL,6,0) +
                                    IF(cpm_trip_type IS NOT NULL,1,0) +
                                    IF(cpm_vehicle_type IS NOT NULL,1,0)
                               ) as rank
                                FROM `channel_partner_markup`
                                WHERE cpm_active=1) a
                            WHERE
                            (
                                (cpm_source_city IS NULL OR cpm_source_city=$fcity) AND
                                (cpm_destination_city IS NULL OR cpm_destination_city=$tcity) AND
                                (cpm_source_zone IS NULL OR cpm_source_zone IN (srcZones)) AND
                                (cpm_destination_zone IS NULL OR cpm_destination_zone IN (dstZones))
                            )
                            AND cpm_agent_id = $partner
                            AND ('$pdate' BETWEEN cpm_from_date AND cpm_to_date) $sql_vehicle_type $sql_trip_type
                        ORDER BY rank DESC, amount DESC  LIMIT 1";

			return DBUtil::queryRow($sql);
		}
		return false;
	}

	public function calcMarkupGMT($quoteModel, $ratePerKm, $partner = 18190)
	{
		$fcity				 = $quoteModel->servingRoute['start'];
		$tcity				 = $quoteModel->servingRoute['end'];
		$amount				 = $ratePerKm;
		$pdate				 = $quoteModel->routes[0]->brt_pickup_datetime;
		$sql_vehicle_type	 = ($quoteModel->cabType != '') ? " AND (cpm_vehicle_type=$quoteModel->cabType OR cpm_vehicle_type IS NULL)" : "";
		$sql_trip_type		 = ($quoteModel->tripType != '') ? " AND (cpm_trip_type=$quoteModel->tripType OR cpm_trip_type IS NULL)" : "";
		if (($fcity == null || $fcity == "") || ($tcity == null || $tcity == "") || ($pdate == null || $pdate == ""))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql = "SELECT * FROM (
                                SELECT *, FLOOR(IF((cpm_value_type=1),(cpm_value+$amount),(IF((cpm_value_type=2),($amount+($amount*(cpm_value/100))),cpm_value))))amount,
                                    ((SELECT GROUP_CONCAT(z2.zct_zon_id) as srcZones FROM zone_cities z2
                                        WHERE z2.zct_active=1 AND zct_cty_id=$fcity GROUP BY z2.zct_cty_id)) as srcZones,
                                    (SELECT GROUP_CONCAT(z2.zct_zon_id) as srcZones FROM zone_cities z2
                                        WHERE z2.zct_active=1 AND zct_cty_id=$tcity GROUP BY z2.zct_cty_id) as dstZones,
                                    (
                                    IF(cpm_agent_id IS NOT NULL, 1,0) +
                                    IF(cpm_source_city IS NOT NULL,54,0) +
                                    IF(cpm_destination_city IS NOT NULL,54,0) +
                                    IF(cpm_source_zone IS NOT NULL,18,0) +
                                    IF(cpm_destination_zone IS NOT NULL,18,0) +
                                    IF(cpm_source_state IS NOT NULL,6,0) +
                                    IF(cpm_destination_state IS NOT NULL,6,0) +
                                    IF(cpm_trip_type IS NOT NULL,1,0) +
                                    IF(cpm_vehicle_type IS NOT NULL,1,0)
                               ) as rank
                                FROM `channel_partner_markup`
                                WHERE cpm_active=1) a
                            WHERE
                            (
                                (cpm_source_city IS NULL OR cpm_source_city=$fcity) AND
                                (cpm_destination_city IS NULL OR cpm_destination_city=$tcity) AND
                                (cpm_source_zone IS NULL OR cpm_source_zone IN (srcZones)) AND
                                (cpm_destination_zone IS NULL OR cpm_destination_zone IN (dstZones))
                            )
                            AND cpm_agent_id = $partner
                            AND ('$pdate' BETWEEN cpm_from_date AND cpm_to_date) $sql_vehicle_type $sql_trip_type
                        ORDER BY rank DESC, amount DESC  LIMIT 1";
		return DBUtil::queryRow($sql);

		return false;
	}

	public function getValueType($var = 0)
	{
		$var	 = ($var > 0) ? $var : $this->cpm_value_type;
		$list	 = $this->cpm_value_type_arr;
		return $list[$var];
	}

	public function getMarkupLog($id)
	{
		$qry	 = "select cpm_log from channel_partner_markup where cpm_id = " . $id;
		$logList = DBUtil::queryRow($qry);
		return $logList;
	}

	public function addLog($oldData, $newData)
	{
		if ($oldData)
		{
			$getDifference	 = array_diff_assoc($oldData, $newData);
			$remark			 = $this->cpm_log;
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
		return $remark;
	}

}
