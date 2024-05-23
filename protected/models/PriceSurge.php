<?php

/**
 * This is the model class for table "price_surge".
 *
 * The followings are the available columns in table 'price_surge':
 * @property integer $prc_id
 * @property string $prc_from_date
 * @property string $prc_to_date
 * @property integer $prc_value_type
 * @property integer $prc_value
 * @property integer $prc_source_city
 * @property integer $prc_destination_city
 * @property string $prc_source_zone
 * @property string $prc_destination_zone
 * @property integer $prc_source_state
 * @property integer $prc_destination_state
 * @property integer $prc_region
 * @property integer $prc_vehicle_type
 * @property integer $prc_trip_type
 * @property string $prc_desc
 * @property integer $prc_priority_score
 * @property integer $prc_is_available
 * @property integer $prc_active
 * @property string $prc_modified
 * @property string $prc_created
 * @property string $prc_log
 * @property integer $prc_override_ds
 * @property integer $prc_override_dz
 * @property integer $prc_override_de
 * @property integer $prc_override_ddv2
 * @property integer $prc_override_profitability
 * @property integer $prc_override_ddsbp
 * @property integer $prc_sold_out
 * @property integer $prc_delete_cache_onrefresh
 * @property integer $prc_is_package
 * @property integer $prc_is_gnow_applicable
 * @property integer $prc_surge_reason
 * The followings are the available model relations:
 * @property Cities $prcSourceCity
 * @property Cities $prcDestinationCity
 * @property Zones $prcSourceZone
 * @property Zones $prcDestinationZone
 * @property SvcClassVhcCat $prcVehicleType
 */
class PriceSurge extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'price_surge';
	}

	public $max_val = 0, $prc_from_date1, $prc_source_city1;
	public $prc_adm_id, $prc_adm_name, $prc_date, $prc_value_changed, $prc_logArr;
	public $srhSourceZone, $srhDestinationZone, $sourceZones, $destinationZones, $overrideType, $isGozoNow;

	public function defaultScope()
	{
		$ta	 = $this->getTableAlias(false, false);
		$arr = array(
			'condition' => "prc_active IN (1)",
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
			//array('prc_modified, prc_created', 'required'),
			array('prc_value_type, prc_value, prc_from_date, prc_to_date', 'required', 'on' => 'insert'),
			//      array('prc_value','numerical', 'min'=>1,'on'=>'insert'),
			array('prc_value_type, prc_value, prc_source_city, prc_destination_city, prc_vehicle_type, prc_trip_type,prc_priority_score, prc_active', 'numerical', 'integerOnly' => true),
			array('prc_desc', 'length', 'max' => 500),
			array('prc_from_date, prc_to_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prc_id, prc_from_date, prc_to_date, prc_from_date1, prc_value_type, prc_value, prc_source_city,
				prc_destination_city, prc_source_zone, prc_destination_zone, prc_vehicle_type, prc_trip_type, 
				prc_desc, prc_priority_score,prc_is_available, prc_active, prc_modified, prc_created, prc_source_state, 
				prc_destination_state,prc_region,prc_override_ds,prc_override_dz,prc_override_de,prc_override_ddv2,prc_override_profitability, prc_override_ddsbp, prc_sold_out, prc_delete_cache_onrefresh, prc_is_package,prc_cab_categories,prc_cab_tiers,prc_cab_models,prc_is_gnow_applicable,prc_surge_reason', 'safe'),
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
			'prcSourceCity'	 => [self::BELONGS_TO, 'Cities', 'prc_source_city'],
			'prcDestCity'	 => [self::BELONGS_TO, 'Cities', 'prc_destination_city'],
			'prcSourceZone'	 => [self::BELONGS_TO, 'Zones', 'prc_source_zone'],
			'prcDestZone'	 => [self::BELONGS_TO, 'Zones', 'prc_destination_zone'],
			'prcSourceState' => [self::BELONGS_TO, 'States', 'prc_source_state'],
			'prcDestState'	 => [self::BELONGS_TO, 'States', 'prc_destination_state'],
			'prcVehicleType' => [self::BELONGS_TO, 'SvcClassVhcCat', 'prc_vehicle_type'],
			'prcTripType'	 => [self::BELONGS_TO, 'Booking', 'prc_trip_type']
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'prc_id'					 => 'id',
			'prc_from_date'				 => 'From Date',
			'prc_to_date'				 => 'To Date',
			'prc_value_type'			 => 'Value Type',
			'prc_value'					 => 'Value',
			'prc_source_city'			 => 'Source City',
			'prc_destination_city'		 => 'Destination City',
			'prc_source_zone'			 => 'Source Zone',
			'prc_destination_zone'		 => 'Destination Zone',
			'prc_vehicle_type'			 => 'Vehicle Type',
			'prc_trip_type'				 => 'Trip Type',
			'prc_desc'					 => 'Description',
			'prc_priority_score'		 => 'Priority Score',
			'prc_active'				 => 'Active',
			'prc_modified'				 => 'Modified',
			'prc_created'				 => 'Created',
			'prc_override_ds'			 => 'Overide DDv1',
			'prc_override_dz'			 => 'Overide DZ',
			'prc_override_de'			 => 'Overide DE',
			'prc_override_ddv2'			 => 'Overide DDv2',
			'prc_override_profitability' => 'Overide Profitability Surge',
			'prc_override_ddsbp'		 => 'Override DDSBP',
			'prc_sold_out'				 => 'Sold Out',
			'prc_log'					 => 'Prc Log',
			'prc_delete_cache_onrefresh' => 'Delete Surge on Cache Refresh',
			'prc_is_package'			 => 'Apply on Packages',
			'prc_is_gnow_applicable'	 => 'Use Gozonow',
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
		//  $criteria->compare('prc_id', $this->prc_id);
		if ($this->prc_from_date != '')
		{
			$criteria->addCondition("DATE(prc_from_date)<='{$this->prc_from_date}' AND DATE(prc_to_date)>='{$this->prc_from_date}'");
		}
		else
		{
			$criteria->addCondition("DATE(prc_to_date)>=CURDATE()");
		}
		if ($this->prc_to_date != '')
		{
			$criteria->compare('DATE(prc_to_date)', $this->prc_to_date, true);
		}
		$criteria->compare('prc_value_type', $this->prc_value_type);
		$criteria->compare('prc_source_city', $this->prc_source_city);
		$criteria->compare('prc_destination_city', $this->prc_destination_city);
		$criteria->compare('prc_source_zone', $this->prc_source_zone);
		$criteria->compare('prc_destination_zone', $this->prc_destination_zone);
		$criteria->compare('prc_vehicle_type', $this->prc_vehicle_type);
		$criteria->compare('prc_trip_type', $this->prc_trip_type);
		$criteria->compare('prc_desc', $this->prc_desc, true);
//        $criteria->compare('prc_priority_score', $this->prc_priority_score);
		$criteria->compare('prc_active', $this->prc_active);
		$criteria->compare('prc_modified', $this->prc_modified, true);
		$criteria->compare('prc_created', $this->prc_created, true);
		$criteria->compare('prc_log', $this->prc_log, true);
		$criteria->with		 = ['prcSourceCity', 'prcDestCity', 'prcSourceZone', 'prcDestZone', 'prcVehicleType',
			'prcSourceCity'				 => ['select' => ['cty_id']], 'prcSourceCity.zoneCities'	 => ['select' => 'zct_zon_id']
		];
		$criteria->together	 = true;
		//  $result= $this->findAll($criteria);
		// $criteria->order="prc_to_date DESC";
		return new CActiveDataProvider($this->together(), array(
			'criteria'	 => $criteria, 'sort'		 =>
			['attributes'	 => ['prc_from_date', 'prc_to_date', 'prc_source_zone', 'prc_destination_zone', 'prc_trip_type', 'prc_source_state', 'prc_destination_state',
					'prc_priority_score', 'prc_value_type', 'prc_source_city', 'prc_destination_city', 'prc_vehicle_type', 'prc_region', 'prc_value'],
				'defaultOrder'	 => 'prc_from_date DESC, prc_to_date DESC']
		));
	}

	public function getList($type = DBUtil::ReturnType_Provider)
	{
		$condition	 = "WHERE prc_active = 1  ";
		$SJoinStmt	 = $DJoinStmt	 = '';
		if ($this->prc_from_date != '')
		{

			$condition .= " AND prc_from_date<='{$this->prc_from_date} 23:59:59' AND DATE(prc_to_date)>='{$this->prc_from_date} 00:00:00'";
		}
		else
		{
			$condition .= " AND prc_to_date>=CONCAT(CURDATE(), ' 00:00:00')";
		}
		if ($this->prc_surge_reason != '')
		{
			$condition .= " AND prc_surge_reason ='{$this->prc_surge_reason}'";
		}
		if ($this->isGozoNow != '')
		{
			$condition .= " AND prc_is_gnow_applicable ='{$this->isGozoNow}'";
		}
		if ($this->prc_to_date != '')
		{
			$condition .= " AND prc_to_date BETWEEN '{$this->prc_to_date} 00:00:00' AND '{$this->prc_to_date} 23:59:59'";
		}
		if ($this->prc_is_available != '')
		{
			$condition .= " AND prc_is_available ='{$this->prc_is_available}'";
		}
		if ($this->prc_source_city != '')
		{
			$condition .= " AND prc_source_city ='{$this->prc_source_city}'";
		}
		if ($this->prc_destination_city != '')
		{
			$condition .= "AND prc_destination_city ='{$this->prc_destination_city}'";
		}
		if ($this->prc_vehicle_type != '')
		{
			$condition .= "AND prc_vehicle_type ='{$this->prc_vehicle_type}'";
		}
		if ($this->prc_trip_type != '')
		{
			$condition .= "AND prc_trip_type ='{$this->prc_trip_type}'";
		}
		if ($this->prc_source_zone != '')
		{
			$condition .= " AND (FIND_IN_SET('{$this->prc_source_zone}',prc.prc_source_zone) 
									OR prc_source_city IN (SELECT zct_cty_id FROM zone_cities WHERE zct_active=1 
											AND zct_zon_id='{$this->prc_destination_zone}'))";
		}
		if ($this->prc_destination_zone != '')
		{
			$condition .= " AND (FIND_IN_SET('{$this->prc_destination_zone}',prc.prc_destination_zone) 
								OR prc_destination_city IN (SELECT zct_cty_id FROM zone_cities WHERE zct_active=1 
											AND zct_zon_id='{$this->prc_destination_zone}'))";
		}
		if ($this->overrideType != '')
		{
			switch ($this->overrideType)
			{
				case 1:
					$condition	 .= " AND prc_override_dz =1 ";
					break;
				case 2:
					$condition	 .= " AND prc_override_de =1 ";
					break;
				case 3:
					$condition	 .= " AND prc_override_ddv2 =1 ";
					break;
				case 4:
					$condition	 .= " AND prc_override_ds =1 ";
					break;
				case 5:
					$condition	 .= " AND prc_override_profitability =1 ";
					break;
				case 6:
					$condition	 .= " AND prc_override_ddsbp =1 ";
					break;
			}
		}
		if ($this->prc_source_state != '')
		{

			if ($this->prc_source_city == '')
			{
				$condition .= " AND (prc_source_state ='{$this->prc_source_state}'	
									OR prc_source_city IN (SELECT cty_id FROM cities 
										WHERE cty_state_id = " . $this->prc_source_state . ")";
			}
			else
			{
				$condition	 .= " OR ( prc_source_state ='{$this->prc_source_state}'";
				$condition	 .= " OR prc_source_city = " . $this->prc_source_city;
			}
			$condition .= ")";
		}

		if ($this->prc_destination_state != '')
		{
			if ($this->prc_destination_city == '')
			{
				$condition	 .= " AND (prc_destination_state ='{$this->prc_destination_state}'";
				$condition	 .= " OR prc_destination_city IN (select cty_id from cities where cty_state_id = " . $this->prc_destination_state . ")";
			}
			else
			{
				$condition	 .= " OR (prc_destination_state ='{$this->prc_destination_state}'";
				$condition	 .= " OR prc_destination_city = " . $this->prc_destination_city;
			}
			$condition .= ")";
		}
		$groupBy = ' GROUP BY prc_id';

		$sql	 = "SELECT prc.*, source_cty.cty_name source_cty_name, dest_cty.cty_name dest_cty_name,
							prcSourceState.stt_name source_stt_name, prcDestState.stt_name dest_stt_name  
						FROM price_surge prc
						LEFT JOIN cities source_cty ON prc.prc_source_city = source_cty.cty_id
						LEFT JOIN cities dest_cty ON prc.prc_destination_city = dest_cty.cty_id
						LEFT JOIN `states` `prcSourceState` ON (`prc`.`prc_source_state` = `prcSourceState`.`stt_id`)
						LEFT JOIN `states` `prcDestState` ON (`prc`.`prc_destination_state` = `prcDestState`.`stt_id`) 
						$condition $groupBy";
		$count	 = DBUtil::command("SELECT COUNT(DISTINCT prc_id) FROM price_surge prc $condition")->queryScalar();

		if ($type == DBUtil::ReturnType_Provider)
		{
			$dataprovider = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 =>
				['attributes'	 => ['prc_from_date', 'prc_to_date', 'prc_source_zone', 'prc_destination_zone', 'prc_trip_type', 'prc_source_state', 'prc_destination_state',
						'prc_priority_score', 'prc_value_type', 'prc_source_city', 'prc_desc', 'prc_destination_city', 'prc_vehicle_type', 'prc_region', 'prc_value'],
					'defaultOrder'	 => 'prc_priority_score DESC, prc_from_date DESC, prc_to_date DESC'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PriceSurge the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getPricing($model, $basePrice)
	{
		/* @var $model Booking */
		//$trip_type= array_keys($model['booking_types']);
		$fcity				 = $model->bkg_from_city_id;
		$tcity				 = $model->bkg_to_city_id;
		$toCities			 = $model->to_cities;
		$strToCities		 = implode(",", $toCities);
		$fstate				 = $model->bkgFromCity->ctyState->stt_id;
		$tstate				 = $model->bkgToCity->ctyState->stt_id;
		$region				 = $model->bkgFromCity->ctyState->stt_zone;
		$amount				 = $basePrice;
		$pdate				 = $model->bkg_pickup_date;
		$sql_vehicle_type	 = ($model->bkg_vehicle_type_id != '') ? " AND (prc_vehicle_type=$model->bkg_vehicle_type_id OR prc_vehicle_type IS NULL)" : "";
		$sql_trip_type		 = ($model->bkg_booking_type != '') ? " AND (prc_trip_type=$model->bkg_booking_type OR prc_trip_type IS NULL)" : "";

		$srcZones	 = DBUtil::command("SELECT GROUP_CONCAT(z2.zct_zon_id) as srcZones FROM zone_cities z2
								WHERE z2.zct_active=1 AND zct_cty_id=$fcity")->queryScalar();
		$srcZones	 = (trim($srcZones) == "") ? 'NULL' : $srcZones;
		$dstZones	 = DBUtil::command("SELECT GROUP_CONCAT(z2.zct_zon_id) as srcZones FROM zone_cities z2
								WHERE z2.zct_active=1 AND zct_cty_id IN ($strToCities)")->queryScalar();
		$dstZones	 = (trim($dstZones) == "") ? 'NULL' : $dstZones;
		$sql		 = "SELECT * FROM (
							SELECT *, FLOOR(IF((prc_value_type=1),(prc_value+$amount),($amount+($amount*(prc_value/100))))) amount,
								((SELECT GROUP_CONCAT(DISTINCT z2.zct_zon_id) as srcZones FROM zone_cities z2 WHERE z2.zct_active=1 AND zct_cty_id=$fcity)) as srcZones,
								(SELECT GROUP_CONCAT(DISTINCT z2.zct_zon_id) as srcZones FROM zone_cities z2 WHERE z2.zct_active=1 AND zct_cty_id IN ($strToCities)) as dstZones,
								IF(prc_value_type=1,prc_value,($amount*(prc_value/100))) value,
								(
									IF(prc_source_city IS NOT NULL,54,0) +
									IF(prc_destination_city IS NOT NULL,54,0) +
									IF(prc_source_zone IS NOT NULL,18,0) +
									IF(prc_destination_zone IS NOT NULL,18,0) +
									IF(prc_source_state IS NOT NULL,6,0) +
									IF(prc_destination_state IS NOT NULL,6,0) +
									IF(prc_region IS NOT NULL,3,0) +
									IF(prc_trip_type IS NOT NULL,1,0) +
									IF(prc_vehicle_type IS NOT NULL,1,0)
								) as rank
							FROM `price_surge`
							WHERE prc_active=1) a
						WHERE
							( (prc_source_city IS NULL OR prc_source_city=$fcity) AND
								(prc_destination_city IS NULL OR prc_destination_city IN ($strToCities)) AND
								(prc_source_zone IS NULL OR  FIND_IN_SET(prc_source_zone, srcZones)) AND
								(prc_destination_zone IS NULL OR FIND_IN_SET(prc_destination_zone, dstZones)) AND
								(prc_source_state IS NULL OR prc_source_state={$fstate}) AND
								(prc_destination_state IS NULL OR prc_destination_state IN (SELECT cty_state_id FROM cities WHERE cty_id IN ($strToCities))) AND
								(prc_region IS NULL OR prc_region={$region})
							)
							AND (prc_from_date < '$pdate' AND  prc_to_date>'$pdate') $sql_vehicle_type $sql_trip_type
							ORDER BY prc_priority_score DESC, rank DESC, amount DESC  LIMIT 1";
		return DBUtil::queryRow($sql);
	}

	/** @param Quote $quoteModel */
	public function calculate(&$quoteModel)
	{
		$surge = self::calculateFromParent($quoteModel);
		return $surge;
	}

	public static function getByCitynPickupDate_OLD($fcity, $toCities, $amount, $pdate, $cabType = "", $tripType = "")
	{
		// Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$strToCities	 = implode(",", $toCities);
		$fstate			 = Cities::model()->resetScope()->findByPk($fcity)->cty_state_id;
		$region			 = States::model()->resetScope()->findByPk($fstate)->stt_zone;
		$sql_trip_type	 = ($tripType != '') ? " AND (prc_trip_type= $tripType OR prc_trip_type IS NULL)" : "";
		if ($tripType == 3 || $tripType == 2)
		{
			$sql_trip_type = ($tripType != '') ? " AND (prc_trip_type IN (2,3) OR prc_trip_type IS NULL)" : "";
		}
		$sql_vehicle_type = ($cabType != '') ? " AND (prc_vehicle_type= $cabType OR prc_vehicle_type IS NULL)" : "";

		$sqlApplyToPackage	 = ($tripType == 5) ? " AND prc_is_package = 1" : "";
		$sql				 = "SELECT * FROM (SELECT *, FLOOR(IF((prc_value_type=1),(prc_value+:amount),(:amount+(:amount*(prc_value/100))))) amount,
                                ((SELECT GROUP_CONCAT(DISTINCT z2.zct_zon_id) as srcZones FROM zone_cities z2 WHERE z2.zct_active=1 AND zct_cty_id=:fcity)) as srcZones,
								(SELECT GROUP_CONCAT(DISTINCT z2.zct_zon_id) as srcZones FROM zone_cities z2 WHERE z2.zct_active=1 AND zct_cty_id IN ($strToCities)) as dstZones,
								IF(prc_value_type=1,prc_value,(:amount*(prc_value/100))) value,
								(IF(prc_is_available = 0,100,0) + IF(prc_source_city IS NOT NULL,54,0) + IF(prc_destination_city IS NOT NULL,54,0) +
								IF(prc_source_zone IS NOT NULL,18,0) + IF(prc_destination_zone IS NOT NULL,18,0) + IF(prc_source_state IS NOT NULL,6,0) +
								IF(prc_destination_state IS NOT NULL,6,0) + IF(prc_region IS NOT NULL,3,0) + IF(prc_trip_type IS NOT NULL,1,0) +
								IF(prc_vehicle_type IS NOT NULL,1,0)) as rank 
								FROM `price_surge` WHERE prc_active=1) a
								WHERE ((prc_source_city IS NULL OR prc_source_city=:fcity) AND
                                prc_active = 1 AND 
								(prc_destination_city IS NULL OR prc_destination_city IN ($strToCities)) AND
								(prc_source_zone IS NULL OR  FIND_IN_SET(prc_source_zone, srcZones)) AND
								(prc_destination_zone IS NULL OR FIND_IN_SET(prc_destination_zone, dstZones)) AND
								(prc_source_state IS NULL OR prc_source_state=:fstate) AND
								(prc_destination_state IS NULL OR prc_destination_state IN (SELECT cty_state_id FROM cities WHERE cty_id IN ($strToCities))) AND
								(prc_region IS NULL OR prc_region=:region))
								AND (:pdate BETWEEN prc_from_date AND prc_to_date) $sql_vehicle_type $sql_trip_type $sqlApplyToPackage
							ORDER BY prc_priority_score DESC,rank DESC, amount DESC  LIMIT 1";
		$result				 = DBUtil::queryRow($sql, DBUtil::SDB(), ['amount' => $amount, 'fcity' => $fcity, 'fstate' => $fstate, 'region' => $region, 'pdate' => $pdate]);

		//  Logger::trace("Query======>".'==AMOUNT===='$amount.'===FCITY===='.$fcity.'===FSTATE===='.$fstate.'===REGION====='.$region.'===PDATE====='.$pdate.'========'.$sql.);
		// Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $result;
	}

	public static function getByCitynPickupDate($fcity, $toCities, $amount, $pdate, $cabType = "", $tripType = "")
	{

		$strToCities = implode(",", $toCities);
		$fstate		 = Cities::model()->resetScope()->findByPk($fcity)->cty_state_id;
		$region		 = States::model()->resetScope()->findByPk($fstate)->stt_zone;

		$params = ['amount' => $amount, 'fcity' => $fcity, 'fstate' => $fstate, 'region' => $region, 'pdate' => $pdate];
		if ($tripType == '')
		{
			goto skipTripSearch;
		}

		$sql_trip_type = " OR prc_trip_type=$tripType";
		if ($tripType == 3 || $tripType == 2)
		{
			$sql_trip_type = " OR prc_trip_type IN (2,3)";
		}

		skipTripSearch:
		if ($cabType == '')
		{
			goto skipTierSearch;
		}

		if ($cabType > 0)
		{
			$params["cabType"]	 = $cabType;
			$sql_vehicle_type	 = " OR prc_vehicle_type=:cabType";
		}

		$svcModel	 = SvcClassVhcCat::model()->findByPk($cabType);
		$sccId		 = $svcModel->scv_scc_id;
		if ($sccId > 0)
		{
			$params["sccId"]	 = $sccId;
			$sql_service_tier	 = " OR FIND_IN_SET(:sccId, prc_cab_tiers)";
		}

		$vctId = $svcModel->scv_vct_id;
		if ($vctId > 0)
		{
			$params["vctId"]		 = $vctId;
			$sql_service_category	 = " OR FIND_IN_SET(:vctId, prc_cab_categories)";
		}

		$modelId = $svcModel->scv_model;
		if ($modelId > 0)
		{
			$params["modelId"]	 = $modelId;
			$sql_service_model	 = " OR FIND_IN_SET(:modelId, prc_cab_models)";
		}

		skipTierSearch:
		$sqlApplyToPackage	 = ($tripType == 5) ? " AND prc_is_package = 1" : "";
		$sql				 = "SELECT * FROM 
									(SELECT *, FLOOR(IF((prc_value_type=1),(prc_value+:amount),(:amount+(:amount*(prc_value/100))))) amount,
										((SELECT GROUP_CONCAT(DISTINCT z2.zct_zon_id) as srcZones FROM zone_cities z2 WHERE z2.zct_active=1 AND zct_cty_id=:fcity)) as srcZones,
										(SELECT GROUP_CONCAT(DISTINCT z2.zct_zon_id) as srcZones FROM zone_cities z2 WHERE z2.zct_active=1 AND zct_cty_id IN ($strToCities)) as dstZones,
										ROUND(IF(prc_value_type=1,prc_value,(:amount*(prc_value/100)))) value,
										(IF(prc_is_available = 0,100,0) + IF(prc_source_city IS NOT NULL,54,0) + IF(prc_destination_city IS NOT NULL,54,0) 
											+ IF(prc_source_zone IS NOT NULL,18,0) + IF(prc_destination_zone IS NOT NULL,18,0) + IF(prc_source_state IS NOT NULL,6,0) 
											+ IF(prc_destination_state IS NOT NULL,6,0) + IF(prc_region IS NOT NULL,3,0) + IF(prc_trip_type IS NOT NULL,2,0) 
											+ IF(prc_vehicle_type IS NOT NULL,2,0) + IF(prc_cab_tiers IS NOT NULL AND prc_cab_tiers<>'',1,0) 
											+ IF(prc_cab_models IS NOT NULL AND prc_cab_models<>'',1,0) 
											+ IF(prc_cab_categories IS NOT NULL AND prc_cab_categories<>'',1,0)
										) as rank 
									FROM `price_surge` WHERE prc_active=1 AND (prc_source_city IS NULL OR prc_source_city=:fcity)  AND prc_active = 1 
										AND (prc_destination_city IS NULL OR prc_destination_city IN ($strToCities)) 
										AND (prc_source_state IS NULL OR prc_source_state=:fstate) 
										AND (prc_destination_state IS NULL OR prc_destination_state IN (SELECT cty_state_id FROM cities WHERE cty_id IN ($strToCities))) 
										AND (prc_region IS NULL OR prc_region=:region) AND (prc_trip_type IS NULL OR prc_trip_type='' $sql_trip_type)
										AND (prc_vehicle_type IS NULL $sql_vehicle_type) AND (prc_cab_tiers IS NULL OR prc_cab_tiers='' $sql_service_tier)
										AND (prc_cab_categories IS NULL OR prc_cab_categories='' $sql_service_category) AND (prc_cab_models IS NULL OR prc_cab_models='' $sql_service_model)
										AND (prc_from_date<:pdate AND prc_to_date>:pdate) $sqlApplyToPackage
								) a
								WHERE ((prc_source_zone IS NULL OR  CONCAT(',', IFNULL(prc_source_zone, '0'), ',') REGEXP CONCAT(',(', REPLACE(srcZones,',', '|'), '),')) 
									AND (prc_destination_zone IS NULL OR CONCAT(',', IFNULL(prc_destination_zone, '0'), ',') REGEXP CONCAT(',(', REPLACE(dstZones,',', '|'), '),')))
								ORDER BY prc_priority_score DESC,rank DESC, amount DESC";
		$result				 = DBUtil::queryRow($sql, DBUtil::SDB(), $params, 30, CacheDependency::Type_PriceRule);
		return $result;
	}

	public static function calculateFromParent($quoteModel)
	{
		$fcity		 = $quoteModel->servingRoute['pickup'];
		$toCities	 = $quoteModel->toCities;
		$amount		 = $quoteModel->routeRates->rockBaseAmount;
		$pdate		 = $quoteModel->routes[0]->brt_pickup_datetime;
		$cabType	 = $quoteModel->cabType;
		$tripType	 = $quoteModel->tripType;
		$surge		 = self::getByCitynPickupDate($fcity, $toCities, $amount, $pdate, $cabType, $tripType);
//		$row		 = SvcClassVhcCat::getParentClass($cabType);
//		if (!$row)
//		{
//			goto end;
//		}
//		$parentCabType = $row['scv_id'];
//		if ($parentCabType == 0)
//		{
//			goto end;
//		}
//
//		$quoteModel1			 = clone $quoteModel;
//		$quoteModel1->routeRates = clone $quoteModel->routeRates;
//		$quoteModel1->cabType	 = $parentCabType;
//		$surgeParent			 = self::calculateFromParent($quoteModel1);
//		if (!$surgeParent)
//		{
//			goto end;
//		}
//		if (!isset($surge) || (isset($surge) && $surgeParent['prc_priority_score'] > $surge['prc_priority_score']) || (isset($surge) && $surgeParent['prc_priority_score'] == $surge['prc_priority_score'] && $surgeParent['rank'] > $surge['rank']))
//		{
//			$surge = $surgeParent;
//		}
		end:
		return $surge;
	}

	public function getLogbyId($id)
	{
		if ($id > 0)
		{
			$admList = Admins::model()->getAdminList();
			$sql	 = 'SELECT prc_log from price_surge WHERE prc_id = ' . $id;
			$log	 = DBUtil::command($sql)->queryScalar();
			$newcomm = CJSON::decode($log);
			$logArr	 = [];
			foreach ($newcomm as $k => $value)
			{
				$logArr[$k]['prc_adm_id']		 = $value[0];
				$logArr[$k]['prc_adm_name']		 = $admList[$value[0]];
				$logArr[$k]['prc_date']			 = $value[1];
				$logArr[$k]['prc_value_changed'] = $value[2];
			}

			return $logArr;
		}
		else
		{
			return false;
		}
	}

	public function getDSOverRideStatus($frmCity, $toCity)
	{
		$sql = "SELECT prc_override_ds,prc_override_dz,prc_override_de,prc_override_ddv2, prc_active FROM `price_surge` WHERE prc_source_city = $frmCity AND prc_destination_city = $toCity AND prc_active = 1 ORDER BY prc_modified DESC";
		return DBUtil::queryRow($sql);
	}

	//+Code Block: Start

	/**
	 * This model is used for Add and Edit Price surge details
	 * @param type $receivedData
	 * @param type $priceSurgeId
	 * @param type $dateTimeArray
	 */
	public static function addUpdatePriceSurge($receivedData, $priceSurgeId = null, $priceSurgeSourceZone, $priceSurgeDestinationZone)
	{
		//Return 0 if the form data is not passed
		if (empty($receivedData))
		{
			return 0;
		}
		$includedCabCategories	 = !empty($receivedData["prc_cab_categories"]) ? implode(",", $receivedData["prc_cab_categories"]) : NULL;
		$includedCabtires		 = !empty($receivedData["prc_cab_tiers"]) ? implode(",", $receivedData["prc_cab_tiers"]) : NULL;
		$includedCabmodels		 = !empty($receivedData["prc_cab_models"]) ? implode(",", $receivedData["prc_cab_models"]) : NULL;

		//Formatting the date and time structure as per database table column
		$fromdate	 = date("Y-m-d", strtotime($receivedData["prc_from_date"]));
		$fromtime	 = date("H:i:s", strtotime("00:00:00"));

		$todate	 = date("Y-m-d", strtotime($receivedData["prc_to_date"]));
		$totime	 = date('H:i:s', strtotime('23:59:59'));

		/**
		 * Case 1: If priceSurgeId passed -> Then update the details
		 * Case 2: If priceSurgedId missing -> Then add a new entry to the price surge table
		 */
		if (!empty($priceSurgeId))
		{
			$priceSurgeModel = PriceSurge::model()->findByPk($priceSurgeId);

			$previousRemarks = $priceSurgeModel["prc_log"];

			$modifiedByUserId	 = Yii::app()->user->getId();
			$newDateTime		 = date("Y-m-d H:i:s");

			$newRemarks = "Price surge has been update by loggedIn UserId : " . $modifiedByUserId;

			if (!empty($previousRemarks))
			{
				$decodedRemark = CJSON::decode($previousRemarks);

				$newLogData = array
					(
					0	 => $modifiedByUserId,
					1	 => $newDateTime,
					2	 => $newRemarks
				);
			}

			array_push($decodedRemark, $newLogData);

			$updateLog = CJSON::encode($decodedRemark); // Updated log data

			$priceSurgeModel->prc_log = $updateLog;
		}
		else
		{
			$priceSurgeModel			 = new PriceSurge();
			$priceSurgeModel->prc_active = 1;
		}
		$priceSurgeModel->prc_from_date				 = $fromdate . " " . $fromtime;
		$priceSurgeModel->prc_to_date				 = $todate . " " . $totime;
		$priceSurgeModel->prc_source_zone			 = $priceSurgeSourceZone;
		$priceSurgeModel->prc_destination_zone		 = $priceSurgeDestinationZone;
		$priceSurgeModel->prc_source_state			 = $receivedData["prc_source_state"];
		$priceSurgeModel->prc_destination_state		 = $receivedData["prc_destination_state"];
		$priceSurgeModel->prc_is_available			 = $receivedData["prc_is_available"];
		$priceSurgeModel->prc_is_package			 = $receivedData["prc_is_package"];
		$priceSurgeModel->prc_override_ds			 = $receivedData["prc_override_ds"];
		$priceSurgeModel->prc_override_dz			 = $receivedData["prc_override_dz"];
		$priceSurgeModel->prc_override_de			 = $receivedData["prc_override_de"];
		$priceSurgeModel->prc_override_ddv2			 = $receivedData["prc_override_ddv2"];
		$priceSurgeModel->prc_override_profitability = $receivedData["prc_override_profitability"];
		$priceSurgeModel->prc_priority_score		 = $receivedData["prc_priority_score"];
		$priceSurgeModel->prc_sold_out				 = $receivedData["prc_sold_out"];
		$priceSurgeModel->prc_delete_cache_onrefresh = $receivedData["prc_delete_cache_onrefresh"];
		$priceSurgeModel->prc_value_type			 = $receivedData["prc_value_type"];
		$priceSurgeModel->prc_value					 = $receivedData["prc_value"];
		$priceSurgeModel->prc_destination_city		 = $receivedData["prc_destination_city"];
		$priceSurgeModel->prc_source_city			 = $receivedData["prc_source_city"];
		$priceSurgeModel->prc_region				 = $receivedData["prc_region"];
		$priceSurgeModel->prc_vehicle_type			 = $receivedData["prc_vehicle_type"]; //This is svc_id
		$priceSurgeModel->prc_trip_type				 = $receivedData["prc_trip_type"];
		$priceSurgeModel->prc_desc					 = $receivedData["prc_desc"];
		$priceSurgeModel->prc_override_ddsbp		 = $receivedData["prc_override_ddsbp"];
		$priceSurgeModel->prc_modified				 = new CDbExpression('NOW()');
		$priceSurgeModel->prc_cab_categories		 = $includedCabCategories;
		$priceSurgeModel->prc_cab_tiers				 = $includedCabtires;
		$priceSurgeModel->prc_cab_models			 = $includedCabmodels;
		$priceSurgeModel->prc_is_gnow_applicable	 = $receivedData["prc_is_gnow_applicable"];
		$priceSurgeModel->prc_surge_reason			 = $receivedData["prc_surge_reason"];

		if ($priceSurgeModel->save())
		{
			return $priceSurgeModel;
		}
		else
		{
			return 0;
		}
	}

	//-Code Block: End

	public static function getSurgeReason($reasonId = 0)
	{
		$arrReason = [
			1	 => 'Not enough supply',
			2	 => 'Sudden surge in demand',
			3	 => 'Social unrest',
			4	 => 'Special event',
			5	 => 'Festival'
		];
		if ($reasonId != 0)
		{
			return $arrReason[$reasonId];
		}
		else
		{
			return $arrReason;
		}
	}
}
