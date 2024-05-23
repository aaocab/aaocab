<?php

/**
 * This is the model class for table "vehicle_types".
 *
 * The followings are the available columns in table 'vehicle_types':
 * @property integer $vht_id
 * @property string $vht_image
 * @property string $vht_make
 * @property string $vht_model
 * @property integer $vht_capacity
 * @property integer $vht_average_mileage
 * @property integer $vht_estimated_cost
 * @property integer $vht_bag_capacity
 * @property integer $vht_big_bag_capacity
 * @property integer $vht_fuel_type
 * @property string $vht_created_date
 * @property integer $vht_active
 * @property string $vht_log
 *
 * The followings are the available model relations:
 * @property Vehicles[] $vehicles
 * @property VcvCatVhcType $vht_VcvCatVhcType
 */
class VehicleTypes extends CActiveRecord
{
	public $rfuelType;
	public $vht_make_model;
	public $id;
	public $lowerToHigherType = [1, 3, 2, 4, 5, 6, 7, 8, 9];
	public $bkgSvcClassVhcCat;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vehicle_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vht_make, vht_model,vht_capacity, vht_average_mileage,vht_estimated_cost, vht_fuel_type', 'required', 'on' => 'insert,update'),
			array('vht_capacity, vht_average_mileage, vht_fuel_type, vht_estimated_cost,vht_big_bag_capacity, vht_bag_capacity, vht_active', 'numerical', 'integerOnly' => true),
			array('vht_image, vht_make, vht_model', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vht_id, vht_make, vht_model,vht_bag_capacity,vht_log, vht_capacity, vht_average_mileage,vht_big_bag_capacity, vht_fuel_type, vht_created_date, vht_active', 'safe', 'on' => 'search'),
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
			//'bookings'			 => array(self::HAS_MANY, 'Booking', 'bkg_vehicle_type_id'),
			//'rates'				 => array(self::HAS_MANY, 'SvcClassVhcCat', 'rte_vehicletype_id'),
			//'vhcSvcClassVhcCat'	 => array(self::HAS_MANY, 'SvcClassVhcCat', 'scv_id'),
			//'rtSvcClassVhcCat'	 => array(self::HAS_MANY, 'SvcClassVhcCat', 'scv_id'),
			'vehicles'			 => array(self::HAS_MANY, 'Vehicles', 'vhc_type_id'),
			'vht_VcvCatVhcType'	 => array(self::HAS_ONE, 'VcvCatVhcType', 'vcv_vht_id'),
		);
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "vht_active=1",
		);
		return $arr;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vht_id'				 => 'Vht',
			'vht_make'				 => 'Vehicle make',
			'vht_image'				 => 'Image',
			'vht_model'				 => 'Vehicle model',
			'vht_capacity'			 => 'Seat capacity',
			'vht_average_mileage'	 => 'Average Mileage',
			'vht_estimated_cost'	 => 'Estimated cost',
			'vht_fuel_type'			 => 'Fuel Type',
			'vht_created_date'		 => 'Created Date',
			'vht_active'			 => 'Active',
			'vht_bag_capacity'		 => 'Bag Capacity',
			'vht_big_bag_capacity'	 => 'Big Bag Capacity',
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
		$criteria->compare('vht_id', $this->vht_id);
		$criteria->compare('vht_image', $this->vht_image, true);
		$criteria->compare('vht_make', $this->vht_make, true);
		$criteria->compare('vht_model', $this->vht_model, true);
		$criteria->compare('vht_estimated_cost', $this->vht_estimated_cost);
		$criteria->compare('vht_capacity', $this->vht_capacity);
		$criteria->compare('vht_average_mileage', $this->vht_average_mileage);
		$criteria->compare('vht_fuel_type', $this->vht_fuel_type);
		$criteria->compare('vht_created_date', $this->vht_created_date, true);
		$criteria->compare('vht_active', $this->vht_active);
		return new CActiveDataProvider($this->with("vht_VcvCatVhcType.vcv_VehicleCategory"), array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VehicleTypes the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getFuelType()
	{
		return array(1 => 'Diesel', 2 => 'Petrol');
	}

	public function getCarType($type = '')
	{
		$sql = "select vct_id, vct_label  from vehicle_category where vct_active=1";
		if ($type != '')
		{
			$sql		 .= " and vct_id='$type'";
			$recordset	 = DBUtil::queryAll($sql, DBUtil::MDB());
			return $recordset != null ? $recordset[0]['vct_label'] : "";
		}
		else
		{
			$recordset	 = DBUtil::queryAll($sql, DBUtil::MDB());
			$result		 = [];
			foreach ($recordset as $val)
			{
				$result[$val['vct_id']] = $val['vct_label'];
			}
			return $result;
		}
	}

	/**
	 * @deprecated since version 15-10-2019
	 * @author ramala
	 */
	public function getCabByBkgId($bkgId)
	{
		$sql = "SELECT vehicle_types.vht_make as cab FROM `vehicle_types` WHERE vehicle_types.vht_id=(SELECT booking.bkg_vehicle_type_id FROM `booking` WHERE booking.bkg_id='$bkgId')";
		$cab = DBUtil::command($sql)->queryScalar();
		$cab = ($cab != '') ? $cab : '';
		return $cab;
	}

	/**
	 * @deprecated since version 10-09-2019
	 * This model has deprecated as the table structure has been modified
	 * @return list
	 */
	public function getMasterCarType()
	{
		$cdb = Yii::app()->db->createCommand()
				->select("vct_id, vct_label")
				->from('vehicle_category')
				->where('vct_active =1')
				->order('vct_id ASC');
		$query = $cdb->queryAll();
		return CHtml::listData($query, 'vct_id', 'vct_label');
	}

	public function getMasterCarDetails()
	{
		$cdb	 = Yii::app()->db->createCommand()
				->from('vehicle_category')
				->where('vct_active = 1')
				->order('vct_id ASC');
		$query	 = $cdb->queryAll();
		$arr	 = [];

		foreach ($query as $val)
		{
			$arr[$val['vct_id']] = $val;
		}
		return $arr;
	}

	public static function getMasterDetails($selected = [])
	{

		$strArr	 = implode('_', $selected);
		$key	 = "getMasterDetails_$strArr";
		$arr	 = Yii::app()->cache->get($key);
		if ($arr !== false)
		{
			goto result;
		}

		$qry = '';
		if (sizeof($selected) > 0)
		{
			$qry = " AND vct_id IN (" . implode(',', $selected) . ")";
		}
		$sql	 = "SELECT `vct_id`, `vct_label`, `vct_desc`, `vct_capacity`,vct_image
				FROM `vehicle_category`
				WHERE vct_active = 1 $qry
				ORDER BY `vct_id` ASC";
		$query	 = DBUtil::query($sql, DBUtil::SDB());
		$arr	 = [];
		foreach ($query as $val)
		{
			$arr[$val['vct_id']] = $val;
		}

		Yii::app()->cache->set($key, $arr, 24 * 30 * 60 * 60, new CacheDependency("vehicle"));

		result:
		return $arr;
	}

	public function getCarByCarType($carType)
	{
		$list = $this->getCarType();
		return $list[$carType];
	}

	/**
	 * @deprecated since version 10-10-2019
	 * @author ramala
	 */
	public function getCabType($id = null)
	{
		$list = $this->getCarType();
		return $list[$this->vht_car_type];
	}

	public function checkVehicleType($cab_type, $post_cab_type)
	{
		switch ($cab_type)
		{
			case 1:
				$return	 = (in_array($post_cab_type, [1, 2, 3])) ? true : false;
				break;
			case 2:
				$return	 = (in_array($post_cab_type, [2])) ? true : false;
				break;
			case 3:
				$return	 = (in_array($post_cab_type, [2, 3])) ? true : false;
				break;
			case 4:
				$return	 = (in_array($post_cab_type, [4])) ? true : false;
				break;
		}
		return $return;
	}

	public function getVehicleRate()
	{
		$rate = $this->vht_estimated_cost;
		return $rate;
	}

	public function getVehicleModel($vhtypeid = '')
	{
		if ($vhtypeid != '')
		{
			$vhtid = $vhtypeid;
		}
		else
		{
			$vhtid = $this->vht_id;
		}
		$vhtmodel	 = VehicleTypes::model()->resetScope()->findbyPk($vhtid);
		$vmodel		 = $vhtmodel->vht_make . ' - ' . $vhtmodel->vht_model;
		return $vmodel;
	}

	public function getVehicleTypeById($vhtid)
	{
		$vhtmodel	 = VehicleTypes::model()->findbyPk($vhtid);
		$vtype		 = $vhtmodel->vht_VcvCatVhcType->vcv_vct_id;
		return $vtype;
	}

	public function getVehicleTypeList1()
	{
		$cdb = Yii::app()->db->createCommand()
				->select("vht_id, vht_model, concat( vht_make,  ' - '  ,vht_model) as vht_make_model")
				->from('vehicle_types')
				->where('vht_active = 1')
				->order('vht_make_model', 'ASC');

		$query = $cdb->queryAll();
		return CHtml::listData($query, 'vht_id', 'vht_make_model');
	}

	public function fetchMakeList()
	{
		$cdb	 = Yii::app()->db->createCommand()
				->selectDistinct("vht_make")
				->from('vehicle_types');
		$query	 = $cdb->queryColumn();
		return $query;
	}

	public function fetchModelList()
	{
		$cdb	 = Yii::app()->db->createCommand()
				->selectDistinct("vht_model")
				->from('vehicle_types');
		$query	 = $cdb->queryColumn();
		return $query;
	}

	public function getJSON1()
	{
		$carList = $this->getParentVehicleTypes();
		$arrCar	 = array();
		foreach ($carList as $key => $val)
		{
			$arrCar[] = array("id" => $key, "text" => $val);
		}

		$data = CJSON::encode($arrCar);
		return $data;
	}

	public function getJSON($arr = [])
	{
		$arrJSON = array();
		foreach ($arr as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getCarModel($cartype, $adm = 0)
	{
		$cond = " AND vct_id NOT IN(4,12)";
		if ($adm == 1)
		{
			$cond = '';
		}
		if ($cartype > 0)
		{
			$cond .= " AND vct_id =$cartype";
		}
		$sql = "Select vct_label, vct_desc from vehicle_category where vct_active IN(1,2)" . $cond;
		return $this->resetScope()->cache(24 * 60 * 60)->findBySql($sql);
	}

	public function getcabTypeDetails()
	{
		$sql		 = "select vht_id, vht_make, CONCAT(vht_make, ' - ', vht_model) as vht_model from vehicle_types where vht_active = 1";
		$recordset	 = DBUtil::queryAll($sql, DBUtil::MDB());
		return $recordset;
	}

	public function addLog($oldData, $newData)
	{
		if ($oldData)
		{
			$getDifference	 = array_diff_assoc($oldData, $newData);
			$remark			 = $this->vht_log;
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
			}
		}
		return $remark;
	}

	public function getParentVehicleTypes($adm = 0)
	{
		$cond = " AND vct_id NOT IN(4,12)";
		if ($adm == 1)
		{
			$cond = '';
		}

		$sql = "SELECT vct_id, concat(vct_label, ' ', vct_desc) AS vht_make
			FROM `vehicle_category` 
		   WHERE vct_active IN(1)" . $cond;

		if ($adm == 2)
		{
			$sql = "Select vht_id vct_id, concat(vht_make,  ' '  ,vht_model) as vht_make From vehicle_types where vht_active=1";
		}
		return CHtml::listData(DBUtil::queryAll($sql), 'vct_id', 'vht_make');
	}

	public function getModelDetailsbyId($vhtid)
	{
		$cdb = Yii::app()->db->createCommand()
				->select("vht_id, vht_make, vht_model,vht_bag_capacity,
						vht_capacity, vht_big_bag_capacity")
				->from('vehicle_types')
				->where('vht_id = ' . $vhtid);

		$query = $cdb->queryRow();

		return $query;
	}

	public function getParentTypeConfig()
	{
		$arr = [
			VehicleCategory::COMPACT_ECONOMIC			 => VehicleCategory::COMPACT_ECONOMIC,
			VehicleCategory::SEDAN_ECONOMIC				 => VehicleCategory::SEDAN_ECONOMIC,
			VehicleCategory::SUV_ECONOMIC				 => VehicleCategory::SUV_ECONOMIC,
			VehicleCategory::TEMPO_TRAVELLER_ECONOMIC	 => VehicleCategory::TEMPO_TRAVELLER_ECONOMIC,
			VehicleCategory::ASSURED_DZIRE_ECONOMIC		 => VehicleCategory::SEDAN_ECONOMIC,
			VehicleCategory::ASSURED_INNOVA_ECONOMIC	 => VehicleCategory::SUV_ECONOMIC,
			VehicleCategory::TEMPO_TRAVELLER_9_ECONOMIC	 => VehicleCategory::TEMPO_TRAVELLER_ECONOMIC,
			VehicleCategory::TEMPO_TRAVELLER_12_ECONOMIC => VehicleCategory::TEMPO_TRAVELLER_ECONOMIC,
			VehicleCategory::TEMPO_TRAVELLER_15_ECONOMIC => VehicleCategory::TEMPO_TRAVELLER_ECONOMIC,
			VehicleCategory::TEMPO_TRAVELLER_19_ECONOMIC => VehicleCategory::TEMPO_TRAVELLER_ECONOMIC,
			VehicleCategory::TEMPO_TRAVELLER_26_ECONOMIC => VehicleCategory::TEMPO_TRAVELLER_ECONOMIC,
		];
		return $arr;
	}

	public static function mapVehicleTypenId($vType = 0)
	{
		$arr = [
			VehicleCategory::COMPACT_ECONOMIC			 => '28',
			VehicleCategory::SUV_ECONOMIC				 => '29',
			VehicleCategory::SEDAN_ECONOMIC				 => '30',
			VehicleCategory::ASSURED_DZIRE_ECONOMIC		 => '81',
			VehicleCategory::ASSURED_INNOVA_ECONOMIC	 => '82',
			VehicleCategory::TEMPO_TRAVELLER_9_ECONOMIC	 => '92',
			VehicleCategory::TEMPO_TRAVELLER_12_ECONOMIC => '93',
			VehicleCategory::TEMPO_TRAVELLER_15_ECONOMIC => '94',
			VehicleCategory::TEMPO_TRAVELLER_19_ECONOMIC => '132',
			VehicleCategory::TEMPO_TRAVELLER_26_ECONOMIC => '135',
		];
		if ($vType > 0)
		{
			return $arr[$vType];
		}
		else
		{
			return $arr;
		}
	}

	public function getByVehicleCategoryId($vct_id)
	{
		$pageSize	 = 25;
		$cond		 = "";
		$sql		 = "
						    SELECT vehicle_types.*
							FROM `vehicle_category`
							LEFT JOIN `vcv_cat_vhc_type` ON vehicle_category.vct_id=vcv_cat_vhc_type.vcv_vct_id
							LEFT JOIN `vehicle_types` ON vehicle_types.vht_id=vcv_cat_vhc_type.vcv_vht_id
							WHERE vehicle_category.vct_id = $vct_id";
		if ($this->vht_make != '')
		{
			$cond .= " AND vht_make LIKE '%" . $this->vht_make . "%'";
		} if ($this->vht_model != '')
		{
			$cond .= " AND vht_model LIKE '%" . $this->vht_model . "%'";
		}

		$sql .= $cond;

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['vehicle_types.vht_make', 'vehicle_types.vht_model', 'vehicle_types.vht_active'],
				'defaultOrder'	 => 'vehicle_types.vht_created_date  DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	/**
	 * @deprecated since version 10-10-2019
	 * @author ramala
	 */
	public function getcabPriceDetails($arr)
	{
		$data		 = join("','", $arr);
		$qry		 = "select vht_make,vht_model,vht_car_type,vht_estimated_cost,vht_average_mileage FROM vehicle_types where vht_make IN ('$data')";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getVehicleTypeList($adm = 0)
	{
		$ftypearr	 = $this->getFuelType();
		$ftype		 = 'CASE vht_fuel_type';
		foreach ($ftypearr as $key => $val)
		{
			$ftype .= " WHEN $key THEN '$val' ";
		}
		if ($adm == 1)
		{
			$parent = '(0,-1)';
		}
		else
		{
			$parent = '(0)';
		}
		$ftype	 .= 'END';
		$cdb	 = Yii::app()->db->createCommand()
				->select("vht_id, vht_make,vht_model, concat( vht_make,  ' - '  ,vht_model) as vht_make_model")
				->from('vehicle_types')
				->where('vht_active IN (1,2) ')
				->order('vht_model');

		$query = $cdb->queryAll();
		return CHtml::listData($query, 'vht_id', 'vht_make_model');
	}

	public function create($oldData, $newData, $carType)
	{
		$success		 = false;
		$this->vht_log	 = $this->addLog($oldData, $newData);

		if ($this->save())
		{
			$vhcTypeId	 = $this->vht_id;
			$success	 = VcvCatVhcType::addCategoryMapping($vhcTypeId, $carType);
			return $success;
		}
		return $success;
	}

	public function getCabByVehicleTypeId($asmModel, $baseAmount = '', $vctId)
	{
		foreach ($asmModel as $model)
		{
			$asmModelId[] = $model['asm_id'];
		}
		$cabModelId = implode(",", $asmModelId);
		if ($cabModelId != "")
		{
			$sql = "SELECT concat(vehicle_types.vht_make,' ', vehicle_types.vht_model) as cab, asm_markup_type, asm_markup,
				vehicle_types.vht_id
				FROM `vehicle_types`
				INNER JOIN area_select_model asm ON asm.asm_model_id = vehicle_types.vht_id
				LEFT JOIN vcv_cat_vhc_type vcvt ON vcvt.vcv_vht_id =  vehicle_types.vht_id
				WHERE asm.asm_id IN($cabModelId) AND vcv_vct_id = $vctId";
			$cab = DBUtil::queryAll($sql);

			foreach ($cab as $val)
			{
				if ($val['asm_markup_type'] == 1)
				{
					$calAmount = ($baseAmount * $val['asm_markup']) / 100;
				}
				else
				{
					$calAmount = $val['asm_markup'];
				}
				$calAmountStr	 = ($calAmount > 0) ? $val['cab'] . ' + (Rs:' . ($calAmount) . ')' : $val['cab'];
				$arrJSON[]		 = array("id" => $val['vht_id'], "text" => $calAmountStr);
			}
		}
		else
		{
			$arrJSON[] = array("id" => 0, "text" => "--Select Model--");
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public static function vendorCabType($vendorId)
	{
		$sql = "SELECT group_concat(DISTINCT(vct_id)) as vhcLabelId 
                    FROM   vehicles vhc
                    INNER JOIN `vendor_vehicle` vvhc ON vvhc.vvhc_vhc_id = vhc.vhc_id
                    INNER JOIN `vehicle_types` vht ON vht.vht_id=vhc.vhc_type_id
                    INNER JOIN `vcv_cat_vhc_type` vcv ON vcv.vcv_vht_id=vht.vht_id
                    INNER JOIN vehicle_category vht1 ON vht1.vct_id = vcv.vcv_vct_id
                    WHERE  vhc_active = 1 AND  vht.vht_active = 1 AND
                        vvhc_vnd_id = " . $vendorId . " AND
                        vhc_is_freeze = 0 AND
                        vhc_approved = 1
                     ORDER BY vvhc.vvhc_id";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	/** @return static */
	public static function getModelTypeId($modelType)
	{
		return self::model()->find("vht_model=:modelType", ["modelType" => $modelType]);
	}

	/**
	 * 
	 * @param int $cabType
	 * @return int
	 */
	public static function getUpperCategory($cabType)
	{
		switch ($cabType)
		{
			case 1:
				$return	 = 3;
				break;
			case 3:
				$return	 = 2;
				break;
			default :
				$return	 = 2;
				break;
		}
		return $return;
	}

	/**
	 * 
	 * @param type $make
	 * @return int
	 */
	public static function getModelTypeByMake($model)
	{
		$sql = "SELECT vht_id FROM vehicle_types WHERE vht_model LIKE '%$model%' AND vht_active = 1";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public static function getTypeListJson($query = '', $id = '')
	{
		$qry	 = '';
		$qry1	 = '';
		$params3 = [];
		$query	 = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1, "");
		DBUtil::getLikeStatement($query, $bindString1, $params2, '', '');

		if ($id != '')
		{
			$qry1 = " AND  vht_id in ($id)";
		}
		if ($query != '')
		{
			DBUtil::getLikeStatement($query, $bindString3, $params3);
			$qry .= " MATCH (vht_make) AGAINST ($bindString1  IN NATURAL LANGUAGE MODE)  AS vht_make_score,
						IF(vht_make LIKE $bindString0, 1, 0) AS startRank, IFNULL(rank,10) as vht_make_Rank, 
  					MATCH (vht_model) AGAINST ($bindString1  IN NATURAL LANGUAGE MODE)  AS vht_model_score,
						IF(vht_model LIKE $bindString0, 1, 0) AS startRank, IFNULL(rank,10) as vht_model_Rank,";
		}
		$sql = "SELECT vht_id  , CONCAT(vht_make,  ' '  ,vht_model) vht_make_model
				FROM vehicle_types 
				WHERE vht_active= 1 $qry1 $qry1";

		$rows	 = DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params2, $params3));
		$arr	 = [];
		foreach ($rows as $row)
		{
			$arr[] = array("id" => $row['vht_id'], "text" => $row['vht_make_model']);
		}
		$data = CJSON::encode($arr);
		return $data;
	}

	public function getCabMakeModel($bkgVehicleType)
	{
		$sql = "SELECT vehicle_types.vht_make,vht_model  FROM `vehicle_types` WHERE vehicle_types.vht_id=$bkgVehicleType";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public static function getModelList()
	{
		$sql = "SELECT vht_id, vht_make, vht_model 
				FROM vehicle_types 
				WHERE vht_active= 1 ";
		return DBUtil::query($sql, DBUtil::SDB());
	}
}
