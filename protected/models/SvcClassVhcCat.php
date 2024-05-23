<?php

/**
 * This is the model class for table "svc_class_vhc_cat".
 *
 * The followings are the available columns in table 'svc_class_vhc_cat':
 * @property integer $scv_id
 * @property integer $scv_scc_id
 * @property integer $scv_vct_id
 * @property integer $scv_active
 * @property VehicleCategory $scc_VehicleCategory
 * @property ServiceClass $scc_ServiceClass
 */
class SvcClassVhcCat extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'svc_class_vhc_cat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('scv_scc_id, scv_vct_id', 'required'),
			array('scv_scc_id, scv_vct_id, scv_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('scv_id, scv_scc_id, scv_vct_id, scv_active', 'safe', 'on' => 'search'),
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
			'scc_ServiceClass'		 => array(self::BELONGS_TO, 'ServiceClass', 'scv_scc_id'),
			'scc_VehicleCategory'	 => array(self::BELONGS_TO, 'VehicleCategory', 'scv_vct_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'scv_id'	 => 'Scv',
			'scv_scc_id' => 'Scv Scc',
			'scv_vct_id' => 'Scv Vct',
			'scv_active' => 'Scv Active',
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

		$criteria->compare('scv_id', $this->scv_id);
		$criteria->compare('scv_scc_id', $this->scv_scc_id);
		$criteria->compare('scv_vct_id', $this->scv_vct_id);
		$criteria->compare('scv_active', $this->scv_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SvcClassVhcCat the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getCarType()
	{
		$result = array(
			VehicleCategory::COMPACT_ECONOMIC			 => '28', //compact
			VehicleCategory::SUV_ECONOMIC				 => '29', //suv
			VehicleCategory::SEDAN_ECONOMIC				 => '30', //sedan
			VehicleCategory::TEMPO_TRAVELLER_ECONOMIC	 => '31', //Tempo Traveller (14 seater)
			VehicleCategory::ASSURED_DZIRE_ECONOMIC		 => '81', //Assured Dzire
			VehicleCategory::ASSURED_INNOVA_ECONOMIC	 => '82', //Assured Innova
			VehicleCategory::TEMPO_TRAVELLER_9_ECONOMIC	 => '92', //Tempo Traveller (9 seater)
			VehicleCategory::TEMPO_TRAVELLER_12_ECONOMIC => '93', //Tempo Traveller (12 seater)
			VehicleCategory::TEMPO_TRAVELLER_15_ECONOMIC => '94', //Tempo Traveller (15 seater)
			VehicleCategory::TEMPO_TRAVELLER_19_ECONOMIC => '134', //Tempo Traveller (19 seater)
			VehicleCategory::SHARED_SEDAN_ECONOMIC		 => '114', //Shared Sedan
			VehicleCategory::TEMPO_TRAVELLER_26_ECONOMIC => '135', //Tempo Traveller (26 seater)
			VehicleCategory::SUV_7_PLUS_1_ECONOMIC		 => '10', //suv 7+1
		);
		return $result;
	}

	//+ Code Block: Start

	/**
	 * 
	 * This function is used for fetching the service class and vehicle category relationship data
	 * 
	 * @param type $returnType - This defines the type of data need as in return for the operation
	 * @param type $svccId - This is service class table pk id 
	 * @param type $vctId - This is the vehicle category table pk id
	 * @param type $svcId - This is service class and vehicle category relationship table pk id
	 * @return - Depends on the return type ( Default = Array )
	 */
	public static function getVctSvcList($returnType = "", $svccId = 0, $vctId = 0, $svcId = 0, $excludeModels = false)
	{
		$arrReturn = [];

		$arrRelationDetails = self::getCategoryServiceClass($svccId, $vctId, $svcId, $excludeModels);

		//Returns if no data is found
		if (empty($arrRelationDetails))
		{
			return $arrReturn;
		}

		if ($returnType == "object")
		{
			foreach ($arrRelationDetails as $i => $det)
			{
				$data = $det;
			}
			return (object) $data;
		}

		if ($returnType == "allDetail")
		{
			foreach ($arrRelationDetails as $relationDetail)
			{
				$arrReturn[$relationDetail["scv_id"]] = $relationDetail;
			}
			return $arrReturn;
		}
		if ($returnType == "detail" || ($svcId > 0 && $vctId > 0))
		{
			foreach ($arrRelationDetails as $i => $det)
			{
				$data = $det;
			}
			return $data;
		}

		if ($returnType == "list")
		{
			return CHtml::listData($arrRelationDetails, "scv_id", "label");
		}

		if ($returnType == "listCategory")
		{
			return CHtml::listData($arrRelationDetails, "scv_id", "scv_label");
		}

		if ($returnType == "listClass")
		{
			return CHtml::listData($arrRelationDetails, "scc_id", "scc_label");
		}

		if ($returnType == "category")
		{
			return CHtml::listData($arrRelationDetails, "vct_id", "vct_label");
		}

		if ($returnType == "string")
		{
			foreach ($arrRelationDetails as $i => $det)
			{
				$label = $det["label"];
			}
			return $label;
		}

		if ($returnType == "carname")
		{
			return CHtml::listData($arrRelationDetails, "scv_id", "vhcName");
		}

		if ($returnType == "selectize")
		{
			foreach ($arrRelationDetails as $i => $det)
			{
				$data = $det;
			}
			return $data;
		}

		foreach ($arrRelationDetails as $relationDetail)
		{
			if (strpos($relationDetail["scv_label"], trim($relationDetail["vct_label"])) !== false)
			{
				$arrReturn[$relationDetail["scv_id"]] = $relationDetail["scv_label"];
			}
			else
			{
				$arrReturn[$relationDetail["scv_id"]] = $relationDetail["vct_label"] . ' ' . $relationDetail["scv_label"];
			}
		}

		return $arrReturn;
	}

	public static function getCategoryServiceClass($svccId = 0, $vctId = 0, $svcId = 0, $excludeModels = false)
	{

		$noImage = "images/cabs/no-image.png";
		//Fetches the relationship details between vehicle_category and service_class
		$query	 = " SELECT scv_id,scc_id, vct_id, scc_cancel_rule, scc_label,scc_rank, scv_label, scv_code, scc_model_year, 
								CONCAT(vct_label, ' (', scc_label , ')') label,
									vct_label,vct_rank, vct_desc, CONCAT(vct_label, ' ', vct_desc) vhcName, 
									vct_capacity, IF(vct_image IS NULL, '$noImage', vct_image) as vct_image, vct_big_bag_capacity, vct_small_bag_capacity, vcsc_small_bag, FLOOR(vcsc_small_bag/2) AS big_bag,
									scc_is_cng, scc_is_petrol_diesel, scc_id, scc_desc, IF( vct_id IN(5, 6), '1', '0' ) AS is_assured, scv_vct_id,scv_parent_id,scv_vct_id,scv_model,scv_scc_id
						FROM svc_class_vhc_cat
						INNER JOIN service_class ON scc_id = scv_scc_id
						INNER JOIN vehicle_category ON vct_id = scv_vct_id
						INNER JOIN vehicle_cat_svc_class on vcsc_ssc_id = scv_scc_id AND vcsc_vct_id = scv_vct_id
						WHERE scv_active = 1 
							AND scc_active = 1 
						AND vct_active = 1
		";
		if ($svccId > 0)
		{
			$query .= " AND scv_scc_id = $svccId";
		}
		if ($vctId > 0)
		{
			$query .= " AND scv_vct_id = $vctId";
		}

		if ($svcId > 0)
		{
			$query .= " AND scv_id = $svcId";
		}
		if ($excludeModels)
		{
			$query .= " AND scv_model = 0";
		}
		$arrRelationDetails = DBUtil::query($query, DBUtil::SDB(), [], 2 * 24 * 60 * 60, CacheDependency::Type_CabTypes);
		return $arrRelationDetails;
	}

	public function getVehicleCategoryNameById($scvId = '')
	{
		$sql		 = "select CONCAT(vct_label, ' - ' ,vct_desc) vhcName
						FROM svc_class_vhc_cat
						INNER JOIN vehicle_category ON vct_id = scv_vct_id
						WHERE scv_active = 1 AND scv_id = $scvId AND vct_active = 1";
		$cabdetail	 = DBUtil::command($sql)->queryScalar();
		return $cabdetail;
	}

	public function vehicleCategoryMapping($carInfo)
	{
		$result	 = $this->getCarType();
		$array	 = $result[$carInfo];
		return $array;
	}

	public function vehicleCategoryReverseMapping($vhcTypeId)
	{
		$result = $this->getCarType();
		foreach ($result as $key => $value)
		{
			if ($vhcTypeId == $value)
			{
				return $key;
			}
		}
	}

	/**
	 * This function is used for fetching the service class
	 * and vehicle category mapping
	 */
	public function ScVcMapping($returnType)
	{
		//default response
		$arrResponse = array
			(
			"success"	 => false,
			"statusCode" => 500,
			"message"	 => "Failed",
			"data"		 => null
		);

		//Fetching all the active service class
		$fetchServiceClass = "
			SELECT scc_id, scc_label
			FROM service_class
			WHERE scc_active = 1
		";

		$arrServiceClassDetails = DBUtil::queryAll($fetchServiceClass, DBUtil::SDB());

		if (empty($arrServiceClassDetails))
		{
			$arrResponse["Message"] = "No active service class found";
			//goto skipAll;
		}

		$index = 0;
		foreach ($arrServiceClassDetails as $value)
		{
			$servicClass[$index]["headerKeyId"]	 = $value["scc_id"];
			$servicClass[$index]["headerLabel"]	 = $value["scc_label"];

			$index++;
		}

		//Fetching all vehicle categories
		$fetchAllVehicleCategories = "
			SELECT vct_id, vct_label
			FROM vehicle_category
			WHERE vct_active = 1
		";

		$arrVehicleCategoryDetails = DBUtil::queryAll($fetchAllVehicleCategories, DBUtil::SDB());

		if (empty($arrVehicleCategoryDetails))
		{
			$arrResponse["Message"] = "No vehicle category found";
			//goto skipAll;
		}

		/**
		 * finding the relationship
		 * Example : VC - Vehicle Category, SC = Service Class
		 * VC1 x SC1 = SVC_ID
		 * VC1 x SC2 = SVC_ID
		 */
		$scIndex	 = 0;
		$vehicleData = [];
		foreach ($arrVehicleCategoryDetails as $vehicleCategory)
		{
			$vctId = $vehicleCategory["vct_id"];

			$vehicleData[$scIndex]["keyId"]		 = $vctId;
			$vehicleData[$scIndex]["keyDesc"]	 = $vehicleCategory["vct_label"];

			$vctIndex	 = 0;
			$vctData	 = [];
			foreach ($arrServiceClassDetails as $serviceClass)
			{
				$sccId			 = $serviceClass["scc_id"];
				$validateMapping = Lookup::findScVcMapping($vctId, $sccId);

				$isMap		 = 0; //Default, If no mapping found
				$svcId		 = 0; //Default, If no mapping found
				$isEdit		 = 1;
				$isActive	 = 0;

				if ($validateMapping->scvId > 0)
				{
					$isMap		 = 1;
					$svcId		 = $validateMapping->scvId;
					$isActive	 = $validateMapping->isActive;
				}

				$vctData[$vctIndex]["mapKeyId"]		 = $svcId;
				$vctData[$vctIndex]["keyId"]		 = $sccId;
				$vctData[$vctIndex]["relationKeyId"] = $vctId;
				$vctData[$vctIndex]["keyName"]		 = $serviceClass["scc_label"];
				$vctData[$vctIndex]["isMap"]		 = $isMap;
				$vctData[$vctIndex]["isActive"]		 = $isActive;
				$vctData[$vctIndex]["isEdit"]		 = $isEdit; //Default TODO:Stub

				$vctIndex++;
			}

			$vehicleData[$scIndex]["subCat"] = $vctData;

			$scIndex++;
		}

		$createButtons = array();

		$temp = new stdClass();

		$temp->name		 = "Add Category";
		$temp->target	 = "_blank";
		$temp->class	 = "btn btn-primary mb10";
		$temp->url		 = "admin/vehicle/addcategory";
		$temp->style	 = "text-decoration: none;margin-right: 15px;float: right;";

		array_push($createButtons, $temp);

		$temp = new stdClass();

		$temp->name		 = "Add Service Class";
		$temp->target	 = "_blank";
		$temp->class	 = "btn btn-primary mb10";
		$temp->url		 = "admin/vehicle/serviceclasstype";
		$temp->style	 = "text-decoration: none;margin-right: 15px;float: right;";

		array_push($createButtons, $temp);

		$arrResponse["success"]					 = true;
		$arrResponse["statusCode"]				 = 200;
		$arrResponse["message"]					 = "Successfully fetched";
		$arrResponse["data"]["headerType"]		 = "Vehicle Category \ Service Class";
		$arrResponse["data"]["createButtons"]	 = $createButtons;
		$arrResponse["data"]["headerRow"]		 = $servicClass;
		$arrResponse["data"]["rowData"]			 = $vehicleData;

		if (!empty($returnType) && $returnType == "array")
		{
			return $arrResponse;
		}

		skipAll:

		echo json_encode($arrResponse);
		exit;
	}

	/**
	 * This function is used for updating the vehicle category and
	 * vehicle type mapping and insertion
	 * @param type $receivedData
	 */
	public static function updateScVcMapping($receivedData)
	{
		$arrResponse = array
			(
			"success"	 => false,
			"message"	 => "",
		);

		$success		 = 0;
		$newMapping		 = $receivedData->newMapping;
		$updateMapping	 = $receivedData->updateMapping;

		if (empty($newMapping))
		{
			goto skipNew;
		}

		//For new mappings
		$success = 1;
		foreach ($newMapping as $value)
		{
			$newSvcClassVhcCatModel = new SvcClassVhcCat();

			$newSvcClassVhcCatModel->scv_scc_id	 = $value->keyId;
			$newSvcClassVhcCatModel->scv_vct_id	 = $value->relationKeyId;
			$newSvcClassVhcCatModel->scv_active	 = 1;

			$newSvcClassVhcCatModel->save();
		}

		skipNew:

		if (empty($updateMapping))
		{
			goto skipAll;
		}


		//For updating the existing records
		$success = 1;
		foreach ($updateMapping as $value)
		{
			$scvId		 = $value->mapKeyId;
			$isActive	 = (int) $value->isActive;

			//Update existing record if previous vcvId exists
			$updateSvcClassVhcCat = "
				UPDATE svc_class_vhc_cat
				SET scv_active = $isActive
				WHERE scv_id = $scvId
			";

			DBUtil::command($updateSvcClassVhcCat, DBUtil::MDB())->execute();
		}

		skipAll:

		if ($success)
		{
			$arrResponse["success"]	 = true;
			$arrResponse["message"]	 = "Successfully update the details";
		}

		return $arrResponse;
	}

	public function getParentCabWithClass($scvId)
	{
		$catId = self::getCatIdBySvcid($scvId);
		if ($catId == VehicleCategory::ASSURED_DZIRE_ECONOMIC)
		{
			$catId = VehicleCategory::SEDAN_ECONOMIC;
		}
		if ($catId == VehicleCategory::ASSURED_INNOVA_ECONOMIC)
		{
			$catId = VehicleCategory::SUV_ECONOMIC;
		}
		if (in_array($catId, [VehicleCategory::TEMPO_TRAVELLER_ECONOMIC, VehicleCategory::TEMPO_TRAVELLER_9_ECONOMIC, VehicleCategory::TEMPO_TRAVELLER_12_ECONOMIC, VehicleCategory::TEMPO_TRAVELLER_15_ECONOMIC, VehicleCategory::TEMPO_TRAVELLER_19_ECONOMIC, VehicleCategory::TEMPO_TRAVELLER_26_ECONOMIC]))
		{
			$catId = VehicleCategory::TEMPO_TRAVELLER_ECONOMIC;
		}
		$arr = self::getScvIdByCatId($catId, 0);
		return $arr;
	}

	public static function getSvcClassIdByVehicleCat($vctId, $sccId, $scv_model = 0)
	{
		$modelWhere = "";
		if ($scv_model > 0)
		{
			$modelWhere = " AND scv_model = {$scv_model}";
		}
		$sql = "select scv_id
                FROM svc_class_vhc_cat
				INNER JOIN service_class ON scc_id = scv_scc_id
				INNER JOIN vehicle_category ON vct_id = scv_vct_id
				WHERE scv_active = 1 AND vct_id = $vctId AND scc_id = $sccId 
				AND vct_active = 1 AND scc_active = 1 " . $modelWhere;
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar();
	}

	public static function getCatIdBySvcid($pk)
	{
		try
		{
			$sql = "select scv_vct_id FROM svc_class_vhc_cat where scv_id = $pk";
			return DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		}
		catch (Exception $ex)
		{
			Logger::setCategory("trace.models.SvcClassVhcCat");
			Logger::info("===========getCatIdBySvcid======MSG====" . $ex->getMessage() . "=====MSG======getCatIdBySvcid===========");
			Logger::trace("===========getCatIdBySvcid======TRACE=====" . $ex->getTraceAsString() . "=====TRACE====getCatIdBySvcid=========");
			Logger::setCategory("trace.models.SvcClassVhcCat");
		}
	}

	/*
	 * @return array() of relation ids of category and class of a vehicle
	 * @author ramala 2019-12-18
	 */

	public static function getBasicCabList($fromCity, $toCity)
	{
		$sql = "SELECT scv_id FROM svc_class_vhc_cat 
				INNER JOIN service_class ON scc_id=scv_scc_id
			WHERE scv_active = 1 AND scv_id IN (1,2,3,72,73,74,6,7,19) ORDER BY scc_rank ASC";

		$result = DBUtil::query($sql, DBUtil::SDB(), [], true, 2 * 24 * 60 * 60, CacheDependency::Type_CabTypes);
		foreach ($result as $value)
		{
			$arr[] = $value['scv_id'];
		}

		$excludedCabTypes	 = SvcClassVhcCat::getExcludedCabTypes($fromCity, $toCity);
		$availableList		 = array_diff($arr, $excludedCabTypes);
		$cabList			 = implode(",", $availableList);
		return $availableList;
	}

	public static function getCabListQuote($platform = NULL)
	{
//		$key = md5("getCabListQuote-$platform-allscv_id");
//		$arr = Yii::app()->cache->get($key);
//		if ($arr)
//		{
//			return $arr;
//		}
		$arr = [];
		//$sql = "SELECT scv_id FROM svc_class_vhc_cat WHERE scv_active = 1 ORDER BY scv_vct_id ASC,scv_scc_id ASC";

		if ($platform == 3)
		{
			$sql = "SELECT scv_id FROM svc_class_vhc_cat WHERE scv_active = 1 AND scv_scc_id NOT IN(4) ORDER BY scv_vct_id ASC,scv_scc_id ASC";
		}
		elseif ($platform == 1 || $platform == '' || is_null($platform)) // For B2C
		{
			$sql = "SELECT scv_id FROM svc_class_vhc_cat WHERE scv_active = 1 AND scv_scc_id IN (1,2,4,6) ORDER BY scv_vct_id ASC,scv_scc_id ASC";
		}
		else
		{
			$sql = "SELECT scv_id FROM svc_class_vhc_cat WHERE scv_active = 1 ORDER BY scv_vct_id ASC,scv_scc_id ASC";
		}
		if ($platform == "App")
		{
			$sql = "SELECT scv_id FROM svc_class_vhc_cat WHERE scv_active = 1 AND scv_scc_id IN(1,2,6) AND (scv_model=0 OR scv_vct_id IN(5,6)) ORDER BY scv_vct_id ASC,scv_scc_id ASC";
		}


		$result = DBUtil::queryAll($sql, DBUtil::SDB(), [], true, 2 * 24 * 60 * 60, CacheDependency::Type_CabTypes);
		foreach ($result as $value)
		{
			$arr[] = $value['scv_id'];
		}
		return $arr;
	}

	/**
	 * 
	 * @return array
	 */
	public static function getCabListGNowQuote($preferredVhtType = '')
	{
		$arr	 = [];
		$params	 = [];
		$where	 = '';
		if ($preferredVhtType != '' && $preferredVhtType > 0)
		{
			$params	 = ['scv_vct_id' => $preferredVhtType];
			$where	 = ' AND scv_vct_id=:scv_vct_id';
		}
		$sql = "SELECT scv_id FROM svc_class_vhc_cat WHERE scv_scc_id IN (1,2,6) AND  (scv_model=0 OR scv_id IN(5,6)) AND scv_vct_id IN (1,2,3,5,6) AND scv_active = 1 $where";

		$result = DBUtil::query($sql, DBUtil::SDB(), $params, true, 2 * 24 * 60 * 60, CacheDependency::Type_CabTypes);
		foreach ($result as $value)
		{
			$arr[] = $value['scv_id'];
		}
		return $arr;
	}

	/*
	 * @param integer - primary key of this table
	 * @return array() of relation ids of category and class of a vehicle
	 * @author ramala 2019-12-18
	 */

	public static function getClassById($pk)
	{
		$sql = "select scv_scc_id FROM svc_class_vhc_cat where scv_id = $pk AND scv_active = 1";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar();
	}

	public static function getLowerClassId($higherId)
	{
		$params = ['higherId' => $higherId];

		$sql = "SELECT lowerSvc.scv_id FROM svc_class_vhc_cat higherSvc INNER JOIN svc_class_vhc_cat lowerSvc ON lowerSvc.scv_vct_id = higherSvc.scv_vct_id INNER JOIN service_class ON scc_id = lowerSvc.scv_scc_id AND scc_active = 1 WHERE higherSvc.scv_id=:higherId GROUP BY lowerSvc.scv_vct_id ORDER BY service_class.scc_rank ASC";

		return DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
	}

	/**
	 * 
	 * @return CDbDataReader
	 */
	public static function getAllCategoryServiceClass($filterList = null)
	{
		$where = "";
		if ($filterList != null && count($filterList) > 0)
		{
			$ids	 = implode(",", $filterList);
			$where	 = " AND scv_id IN ({$ids})";
		}

		$sql	 = "SELECT scv.*, scc.scc_id, scc.scc_label, scc.scc_desc, scc.scc_title, scc.scc_tag, scc.scc_rank, scc.scc_image, vct.* 
					FROM svc_class_vhc_cat scv
					INNER JOIN service_class scc ON scc.scc_id = scv.scv_scc_id AND scv.scv_active = 1 AND scc.scc_active = 1
					INNER JOIN vehicle_category vct ON vct.vct_id = scv.scv_vct_id AND scv.scv_active = 1 AND vct.vct_active = 1 
					WHERE (scv_model=0 OR scv_parent_id=0 OR scv_vct_id=scv_id OR scv_vct_id IN(5,6))  $where
					ORDER BY vct.vct_rank ASC, scc.scc_rank ASC, scc.scc_id ASC";
		$result	 = DBUtil::query($sql, DBUtil::SDB(), [], 3 * 24 * 60 * 60, CacheDependency::Type_CabTypes);
		return $result;
	}

	/**
	 * 
	 * @param array $categoryServiceClasses (Parameter will be populated with $categoryServiceClass[$category]=[$class=>$categoryClass])
	 * @param array $class (Parameter will be populated with available classes in ranking order)
	 * @param array $category  (Parameter will be populated with available categories order by rank)
	 * @return CDbDataReader
	 */
	public static function mapAllCategoryServiceClass(&$categoryServiceClasses, &$class, &$category, $filterList = null)
	{
		$result = self::getAllCategoryServiceClass($filterList);
		foreach ($result as $row)
		{
			$categoryServiceClasses[$row['vct_id']][$row['scc_id']]	 = $row['scv_id'];
			$class[$row['scc_id']]									 = $row['scc_rank'];
			$category[$row['vct_id']]								 = $row['vct_rank'];
		}
		asort($class);
		asort($category);
		return $result;
	}

	public static function getAllServiceClass($quotes)
	{
		$result = self::getAllCategoryServiceClass();

		$data		 = [];
		$class		 = [];
		$category	 = [];
		foreach ($result as $row)
		{
			$data[$row['vct_id']][$row['scc_id']]	 = $row['scv_id'];
			$class[$row['scc_id']]					 = $row['scc_rank'];
			$category[$row['vct_id']]				 = 1;
		}
		asort($class);
		ksort($category);
		$res = [
			'data'		 => $data,
			'class'		 => $class,
			'category'	 => $category
		];

		return $res;
		$includeValueClass		 = 0;
		$includeValuePlusClass	 = 0;
		$includePlusClass		 = 0;
		$includeSelectClass		 = 0;
		foreach ($result as $value)
		{
			$sccId	 = $value['scc_id'];
			$sccRank = $value['scc_rank'];

			$scvId = $value['scv_id'];

			$scvSccId	 = $value['scv_scc_id'];
			$scvVctId	 = $value['scv_vct_id'];
			if ($quotes[$scvId]->packageID != '' && $sccId != ServiceClass::CLASS_ECONOMIC)
			{
				continue;
			}
			if ($quotes[$scvId]->success)
			{
				if ($sccId == ServiceClass::CLASS_ECONOMIC)
				{
					$includeValueClass = 1;
				}
				if ($sccId == ServiceClass::CLASS_VLAUE_PLUS)
				{
					$includeValuePlusClass = 1;
				}
				if ($sccId == ServiceClass::CLASS_PLUS)
				{
					$includePlusClass = 1;
				}
				if ($sccId == ServiceClass::CLASS_SELECT)
				{
					$includeSelectClass = 1;
				}
			}


			$arrVehicleCat[$value['vct_id']]['vct_id']					 = $value['vct_id'];
			$arrVehicleCat[$value['vct_id']]['vct_label']				 = $value['vct_label'];
			$arrVehicleCat[$value['vct_id']]['vct_desc']				 = $value['vct_desc'];
			$arrVehicleCat[$value['vct_id']]['vct_image']				 = $value['vct_image'];
			$arrVehicleCat[$value['vct_id']]['vct_capacity']			 = $value['vct_capacity'];
			$arrVehicleCat[$value['vct_id']]['vct_big_bag_capacity']	 = $value['vct_big_bag_capacity'];
			$arrVehicleCat[$value['vct_id']]['vct_small_bag_capacity']	 = $value['vct_small_bag_capacity'];

			$arrServiceClass[$value['scc_id']]['scc_id']	 = $value['scc_id'];
			$arrServiceClass[$value['scc_id']]['scc_label']	 = $value['scc_label'];
			$arrServiceClass[$value['scc_id']]['scc_desc']	 = $value['scc_desc'];
			$arrServiceClass[$value['scc_id']]['scc_tag']	 = $value['scc_tag'];
			$arrServiceClass[$value['scc_id']]['scc_rank']	 = $value['scc_rank'];

			if (in_array($sccId, $arrServiceClassIds))
			{
				$arrServiceClassIds[] = $sccId;
			}

			if (array_key_exists($scvId, $quotes) && $quotes[$scvId]->success)
			{
				$arrVehicleCat[$value['vct_id']]['quote'][] = $quotes[$scvId];
			}

			$relKey					 = $scvVctId . '|' . $scvSccId;
			$arrSerVehRel[$relKey]	 = $scvId;
		}

		$arr['arrServiceClassIds']						 = $arrServiceClassIds;
		$arr['arrServiceClass']							 = $arrServiceClass;
		$arr['arrVehicleCat']							 = $arrVehicleCat;
		$arr['arrSerVehRel']							 = $arrSerVehRel;
		$arr['include'][ServiceClass::CLASS_ECONOMIC]	 = $includeValueClass;
		$arr['include'][ServiceClass::CLASS_VLAUE_PLUS]	 = $includeValuePlusClass;
		$arr['include'][ServiceClass::CLASS_PLUS]		 = $includePlusClass;
		$arr['include'][ServiceClass::CLASS_SELECT]		 = $includeSelectClass;
		//echo Filter::getExecutionTime() . " getAllServiceClass End<br> ";
		return $arr;
	}

	public static function getScvIdByCatId($scvVctId, $value = 0)
	{
		$sql = "select scv_id FROM svc_class_vhc_cat
				 where scv_vct_id = $scvVctId AND scv_active = 1";
		if ($value == 0)
		{
			$result = DBUtil::queryAll($sql, DBUtil::SDB());
			foreach ($result as $value)
			{
				$arr[] = $value['scv_id'];
			}
			return $arr;
		}
		else
		{
			$result = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
			return $result;
		}
	}

	public static function getParentClass($cabType)
	{
		$sql	 = "SELECT svc1.* FROM `svc_class_vhc_cat` svc
                    INNER JOIN service_class sc1 ON svc.scv_scc_id=sc1.scc_id AND svc.scv_active = 1 AND sc1.scc_active = 1 
                    INNER JOIN service_class sc2 ON sc2.scc_id = sc1.scc_parent_id AND sc2.scc_active = 1 
                    INNER JOIN svc_class_vhc_cat svc1 ON svc1.scv_vct_id=svc.scv_vct_id AND sc2.scc_id=svc1.scv_scc_id AND svc1.scv_active = 1
                    WHERE 1 AND svc.scv_id = :scvId";
		$params	 = ["scvId" => $cabType];

		$row = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $row;
	}

	public static function getModelServiceClass($scvId)
	{
		$sql	 = "SELECT svc1.* FROM `svc_class_vhc_cat` svc
                INNER JOIN service_class sc1 ON svc.scv_scc_id=sc1.scc_id AND svc.scv_active = 1 AND sc1.scc_active = 1 
                INNER JOIN svc_class_vhc_cat svc1 ON svc1.scv_vct_id=svc.scv_vct_id AND sc1.scc_id=svc1.scv_scc_id AND svc1.scv_active = 1 AND svc1.scv_model=0
                WHERE 1 AND svc.scv_id = :scvId";
		$params	 = ["scvId" => $scvId];

		$row = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $row;
	}

	public static function getParentClass_OLD($cabType)
	{
		$sql = "SELECT
				svc2.*
			    FROM
				svc_class_vhc_cat svc1
				INNER JOIN svc_class_vhc_cat svc2
				ON svc1.scv_vct_id = svc2.scv_vct_id AND svc1.scv_id = :cabType AND svc1.scv_scc_id <> svc2.scv_scc_id AND
				svc2.scv_scc_id =IF(svc1.scv_scc_id = " . ServiceClass::CLASS_SELECT . "," . ServiceClass::CLASS_PLUS . ",IF(svc1.scv_scc_id = " . ServiceClass::CLASS_PLUS . "," . ServiceClass::CLASS_VLAUE_PLUS . ",IF(svc1.scv_scc_id = " . ServiceClass::CLASS_VLAUE_PLUS . "," . ServiceClass::CLASS_ECONOMIC . ",0))) AND svc1.scv_active = 1";
		$row = DBUtil::queryRow($sql, DBUtil::SDB(), ['cabType' => $cabType], 60 * 60 * 24, CacheDependency::Type_CabTypes);
		return $row;
	}

	public static function getAllByLowerClass($svcIds)
	{
		if ($svcIds != '')
		{
			$svcIds	 = implode(',', $svcIds);
			$sql	 = "SELECT GROUP_CONCAT(scv_id) FROM  svc_class_vhc_cat where scv_vct_id IN (SELECT scv_vct_id FROM svc_class_vhc_cat WHERE scv_id IN($svcIds))";
			$data	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
			if ($data)
			{
				return explode(',', $data);
			}
		}
		return [];
	}

	public static function getCabTypeList()
	{
		$sql	 = "SELECT scv.scv_id, vct.vct_label ,scv_label, scv_scc_id FROM svc_class_vhc_cat scv JOIN vehicle_category vct ON scv.scv_vct_id = vct.vct_id JOIN service_class ON scc_id = scv_scc_id";
		$result	 = DBUtil::queryAll($sql);
		$arr	 = array();
		foreach ($result as $row)
		{
			$arr[$row['scv_id']] = array("text" => ($row['scv_scc_id'] == 4 || $row['scv_scc_id'] == 5) ? $row['vct_label'] . " (" . $row['scv_label'] . ")" : $row['scv_label']);
		}
		return $arr;
	}

	public static function getAllCabTypeList()
	{
		$sql	 = "SELECT scv.scv_id, vct.vct_label, scc.scc_label FROM svc_class_vhc_cat scv
					JOIN vehicle_category vct ON scv.scv_vct_id = vct.vct_id 
					JOIN service_class scc ON scc.scc_id = scv.scv_scc_id";
		$data	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $data;
	}

	public static function getExcludedCabTypes($fromCtyId, $toCtyId)
	{
		$key	 = "getexcludedCabTypes_new1_{$fromCtyId}_{$toCtyId}";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			return $data;
		}
		$ctyExcludedCabTypes = Cities::model()->getExcludedCabTypes($fromCtyId);
		$rutExcludedCabTypes = Route::model()->getExcludedCabTypes($fromCtyId, $toCtyId);
		$zonExcludedCabTypes = ZoneCities::model()->getExcludedCabTypes($fromCtyId);
		$data				 = array_unique(array_merge($ctyExcludedCabTypes, $rutExcludedCabTypes, $zonExcludedCabTypes));
		Yii::app()->cache->set($key, $data, 60 * 60 * 24, new CacheDependency('getexcludedCabTypes_availableCabs'));
		return $data;
	}

	/** @return static */
	public static function getBySKU($sku)
	{
		return self::model()->find("scv_code=:code", ["code" => $sku]);
	}

	/** @return static */
	public static function getModel($sku)
	{
		$params	 = ['sku' => $sku];
		$sql	 = "SELECT vht_model,scv_code FROM svc_class_vhc_cat scv
                INNER JOIN vehicle_types vht ON vht.vht_id = scv.scv_model
                WHERE scv.scv_id = :sku";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public static function getData($cabType, $modelTypeId, $modelYear, $combustionTypeIsCNG, $combustionTypeIsPetrolDiesel)
	{
		$typeIdCondition = '';
		if ($modelTypeId > 0)
		{
			$typeIdCondition = " AND scv.scv_model like '%$modelTypeId%'";
		}
		if ($modelYear > 0 && $modelYear <= 5)
		{
			$modelYear = 5;
		}
		else
		{
			$modelYear = 11;
			if ($modelTypeId > 0)
			{
				$modelYear = 5;
			}
		}
		$params	 = ['modelYear' => $modelYear, 'cabType' => $cabType, 'cng' => $combustionTypeIsCNG, 'petrolDiesel' => $combustionTypeIsPetrolDiesel];
		$sql	 = "SELECT scv_id FROM svc_class_vhc_cat scv
                INNER JOIN service_class scc ON scc.scc_id=scv.scv_scc_id
                WHERE scv.scv_vct_id = :cabType AND scc.scc_model_year >= :modelYear  AND scc.scc_id IN (1,2,4,5,6) AND scc.scc_active=1 AND scc.scc_is_cng = :cng AND scc.scc_is_petrol_diesel = :petrolDiesel $typeIdCondition ORDER BY scc.scc_rank ASC LIMIT 0,1";

		$data = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public static function getScvListByCategory($vctId)
	{
		$params	 = ['vctId' => $vctId];
		$sql	 = "SELECT GROUP_CONCAT(scv_id ORDER BY scv_id ASC) FROM `svc_class_vhc_cat` INNER JOIN vehicle_category ON vct_id = scv_vct_id INNER JOIN service_class ON scc_id = scv_scc_id WHERE scv_vct_id =:vctId AND scv_active =1 AND scc_active = 1";
		$result	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return explode(',', $result);
	}

	public static function getClassesByIds($scvIds)
	{
		$params	 = ['scvIds' => $scvIds];
		$sql	 = "SELECT GROUP_CONCAT(DISTINCT svc1.scv_scc_id) sccIds,svc1.scv_vct_id vctId FROM svc_class_vhc_cat svc INNER JOIN svc_class_vhc_cat svc1 ON svc1.scv_vct_id = svc.scv_vct_id WHERE svc.scv_id IN(:scvIds) GROUP BY svc1.scv_vct_id";
		$data	 = DBUtil::query($sql, null, $params, 60 * 60 * 24 * 5, CacheDependency::Type_CabTypes);
		if (count($data) > 0)
		{
			return $data;
		}
		return false;
	}

	public static function getByVhtAndTier($vhtId, $sccId)
	{
		$params	 = ['vhtId' => $vhtId, 'sccId' => $sccId];
		$sql	 = "SELECT scv_id,scv_parent_id FROM svc_class_vhc_cat WHERE scv_scc_id =:sccId AND scv_model=:vhtId AND scv_active = 1";
		$data	 = DBUtil::queryRow($sql, null, $params, 60 * 60 * 24 * 5, CacheDependency::Type_CabTypes);
		if (count($data) > 0)
		{
			return $data;
		}
		return false;
	}

	public static function getSvcsWithVhcModel()
	{
		return DBUtil::queryScalar("SELECT GROUP_CONCAT(scv_id) FROM svc_class_vhc_cat WHERE scv_active = 1 AND scv_model >0 ");
	}

	public static function getListwithModel($sccId, $vctId)
	{
		$sql = "SELECT scv_id,scv_model, vht_name FROM svc_class_vhc_cat
				INNER JOIN vehicle_types vht ON vht.vht_id=scv_model
				WHERE scv_scc_id=:sccId AND scv_vct_id=:vctId AND scv_model>0 AND scv_active=1";

		$params	 = ["sccId" => $sccId, "vctId" => $vctId];
		$rows	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $rows;
	}

	public static function getBaseSVCId($sccId, $vctId)
	{
		$sql = "SELECT scv_id FROM svc_class_vhc_cat
				WHERE scv_scc_id=:sccId AND scv_vct_id=:vctId AND scv_model=0 AND scv_active=1";

		$params	 = ["sccId" => $sccId, "vctId" => $vctId];
		$id		 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $id;
	}

	public static function getCabModelById($scvId)
	{
		$sql	 = "SELECT scv_model FROM svc_class_vhc_cat WHERE scv_id=:scvId AND scv_active=1";
		$params	 = ["scvId" => $scvId];
		$id		 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $id;
	}

	public function getNameById($id)
	{
		return $this->model()->findByPk($id)->scv_label;
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

	public static function getVctIdSccIdByScvId($id)
	{
		$sql	 = "SELECT scv_scc_id,scv_vct_id,scv_model FROM `svc_class_vhc_cat` 
                    WHERE 1 AND scv_id = :scvId";
		$params	 = ["scvId" => $id];
		$row	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);

		return $row;
	}

	/**
	 * @param int $zoneIds
	 * @return array
	 *  */
	public static function getCabIds($zoneIds)
	{
		$arr	 = [];
		//$zoneIds = implode(",", $zoneIds);
		DBUtil::getINStatement($zoneIds, $bindString, $params);
		$sql	 = "SELECT * FROM partner_corporate_zones WHERE pcz_zon_id IN ($bindString) AND pcz_active = 1";
		$result	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		if ($result)
		{
			foreach ($result as $value)
			{
				$arr[] = $value['pcz_scv_id'];
			}
		}

		return $arr;
	}

	public static function getIdByModelId($modelId, $sccId = 4)
	{
		$params	 = ["modelId" => $modelId, "sccId" => $sccId];
		$sql	 = "SELECT scv_id FROM `svc_class_vhc_cat` WHERE `scv_model` = :modelId AND scv_scc_id=:sccId";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public static function getAllVctIdByScvId()
	{
		$arr	 = [];
		$sql	 = "SELECT scv_vct_id,scv_id FROM `svc_class_vhc_cat` WHERE 1";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		if ($result)
		{
			foreach ($result as $value)
			{
				$arr[$value['scv_id']] = $value['scv_vct_id'];
			}
		}
		return $arr;
	}

	public static function getIds($excludeModels = true)
	{
		$result	 = null;
		$params	 = [];
		$where	 = '';

		if ($excludeModels)
		{
			$params	 = ['model' => 0];
			$where	 = ' AND ((scv_model=:model AND scv_scc_id IN (1,2,6)) OR scv_vct_id IN (5,6)) ';
		}

		$sql = "SELECT GROUP_CONCAT(scv_id) ids FROM svc_class_vhc_cat WHERE scv_active = 1 {$where}";
		$ids = DBUtil::queryScalar($sql, DBUtil::SDB(), $params, true, 2 * 24 * 60 * 60, CacheDependency::Type_CabTypes);

		if ($ids)
		{
			$result = explode(',', $ids);
		}
		return $result;
	}

	/**
	 * 
	 * @param type $vehicleTypeId
	 * @return boolean
	 */
	public static function applicableVehicleType($vehicleTypeId)
	{
		$success = true;
		if (in_array($vehicleTypeId, [4, 7, 8, 9, 10, 12, 40]))
		{
			$success = false;
		}
		return $success;
	}

	public static function getCatrgoryLabel($id,$labelWithClass = false)
	{
		$sql = "SELECT scv_label FROM svc_class_vhc_cat WHERE scv_id =:id ";
		$fullLabel = DBUtil::queryScalar($sql, DBUtil::SDB(), ['id'=>$id], true, 2 * 24 * 60 * 60, CacheDependency::Type_CabTypes);
		if($labelWithClass)
		{
			return $fullLabel;
		}
		$label = explode("(", $fullLabel);
		return trim($label[0]);
	}
}
