<?php

/**
 * This is the model class for table "states".
 *
 * The followings are the available columns in table 'states':
 * @property integer $stt_id
 * @property string $stt_name
 * @property string $stt_code
 * @property integer $stt_country_id
 * @property integer $stt_zone
 * @property string $stt_active
 * @property string $stt_place_id
 *
 * The followings are the available model relations:
 * @property Cities[] $cities
 */
class States extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'states';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stt_name', 'required'),
			array('stt_country_id', 'numerical', 'integerOnly' => true),
			array('stt_name', 'length', 'max' => 50),
			array('stt_code', 'length', 'max' => 20),
			array('stt_active', 'length', 'max' => 1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('stt_id, stt_name, stt_code, stt_country_id,stt_zone, stt_active, stt_place_id', 'safe', 'on' => 'search'),
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
			'cities' => array(self::HAS_MANY, 'Cities', 'cty_state_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'stt_id'		 => 'Stt',
			'stt_name'		 => 'Name',
			'stt_code'		 => 'Code',
			'stt_country_id' => 'Country',
			'stt_place_id'	 => 'Place ID',
			'stt_active'	 => 'Active',
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

		$criteria->compare('stt_id', $this->stt_id);
		$criteria->compare('stt_name', $this->stt_name, true);
		$criteria->compare('stt_code', $this->stt_code, true);
		$criteria->compare('stt_country_id', $this->stt_country_id);
		$criteria->compare('stt_active', $this->stt_active, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function findRegionName($zone_id = "")
	{
		$arrVal = [
			1	 => 'North',
			2	 => 'West',
			3	 => 'Central',
			4	 => 'South',
			5	 => 'East',
			6	 => 'North East',
			7	 => 'South Kerala'
		];
		if ($zone_id > 0)
		{
			return $arrVal[$zone_id];
		}
		return $arrVal;
	}

	public static function findUniqueZone($zone_id = "")
	{
		$arrVal = [
			1	 => 'North',
			2	 => 'West',
			3	 => 'Central',
			4	 => 'South',
			5	 => 'East',
			6	 => 'North East',
			7	 => 'South'
		];
		if ($zone_id > 0)
		{
			return $arrVal[$zone_id];
		}
		return $arrVal;
	}

	public static function findRegionNumber($regionName = "")
	{
		$regionName	 = ucwords(strtolower(trim($regionName)));
		$arrVal		 = [
			'North'			 => 1,
			'West'			 => 2,
			'Central'		 => 3,
			'South'			 => 4,
			'East'			 => 5,
			'North east'	 => 6,
			'South kerala'	 => 7
		];
		if ($regionName != '')
		{
			if (array_key_exists($regionName, $arrVal))
			{
				foreach ($arrVal as $x => $x_value)
				{
					if ($x == $regionName)
					{
						$regionVal = $x_value;
					}
				}
			}
			else
			{
				$regionVal = $regionName;
			}
		}
		return $regionVal;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return States the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getNameById($id)
	{
		$name = "";
		if ($id > 0)
		{
			$name = $this->findByPk($id)->stt_name;
		}
		return $name;
	}

	public function getStateList()
	{
		$criteria			 = new CDbCriteria();
		$criteria->select	 = "stt_id, stt_name";
		$criteria->compare('stt_active', 1);
		$criteria->order	 = "stt_name";
		$comments			 = States::model()->findAll($criteria);
		return $comments;
	}

	public function getStateList1($all = null)
	{
		$stateModels = States::model()->getStateList();
		$arrSkill	 = Filter::ObjectArrayToArrayList($stateModels, "stt_id", "stt_name");
		return $arrSkill;
	}

	public function getIndiaStateList1()
	{
		$stateModels = States::model()->getIndiaStateList();
		$arrSkill	 = array();

		foreach ($stateModels as $sklModel)
		{
			$arrSkill[$sklModel->stt_id] = $sklModel->stt_name;
		}
		return $arrSkill;
	}

	public function getIndiaStateList()
	{
		$criteria			 = new CDbCriteria();
		$criteria->select	 = "stt_id, stt_name";
		$criteria->compare('stt_active', 1);
		$criteria->compare('stt_country_id', 99);
		$criteria->order	 = "stt_name";
		$comments			 = States::model()->findAll($criteria);
		return $comments;
	}

	public function getJSON($all = '')
	{
		$arrState	 = $this->getStateList1();
		$arrJSON	 = [];
		if ($all != '')
		{
			$arrJSON[] = array_merge(array("id" => '0', "text" => "All"), $arrJSON);
		}
		foreach ($arrState as $key => $val)
		{
			if ($val != '')
			{
				$arrJSON[] = array("id" => $key, "text" => $val);
			}
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getList($type = '')
	{
		$sql			 = "SELECT stt_id, stt_name
                    FROM `states`
                    WHERE
                    stt_active = '1'";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['stt_name'],
				'defaultOrder'	 => 'stt_name ASC'],
			'pagination'	 => ['pageSize' => 50],
		]);

		return $dataprovider;
	}

	public function getStateById($id)
	{
		return $this->model()->findByPk($id)->stt_name;
	}

	public function getZoenId($ctyId)
	{
		$sql	 = "SELECT stt.stt_zone FROM cities cty INNER JOIN states stt ON stt.stt_id = cty.cty_state_id WHERE cty.cty_id = $ctyId";
		$results = DBUtil::queryRow($sql, DBUtil::SDB());
		return $results['stt_zone'];
	}

	public function getIdByName($sttName)
	{
		$sql	 = "SELECT stt_id FROM states WHERE stt_name= '$sttName'";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB());
		return $data['stt_id'];
	}

	public function getIdByNameAndCode($sttName, $stateCode)
	{
		$sql	 = "SELECT stt_id FROM states WHERE (stt_name= '$sttName' OR (stt_code='$stateCode' AND stt_code<>'' AND stt_code IS NOT NULL))";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB());
		return $data['stt_id'];
	}

	public function getByCityId($cityId)
	{
		if ((trim($cityId) == null || trim($cityId) == ""))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params	 = array('cityId' => $cityId);
		$sql	 = "SELECT stt.stt_id FROM states stt
					INNER JOIN cities cty ON cty.cty_state_id = stt.stt_id
					WHERE cty.cty_id = :cityId";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public function getStateListByCountryId($countryId)
	{
		$sql	 = "SELECT stt.stt_id, stt.stt_name FROM states stt
                    INNER JOIN countries cnty ON cnty.id = stt.stt_country_id
					WHERE cnty.id = $countryId";
		$data	 = DBUtil::query($sql, DBUtil::SDB());
		return $data;
	}

	public function getStateListCron()
	{
		$query	 = " SELECT stt_id,stt_name   FROM states WHERE stt_active ='1'";
		$result	 = DBUtil::queryAll($query, DBUtil::SDB());
		return $result;
	}

	public function getRegionByZoneId()
	{
		$sql	 = "SELECT  distinct stt_zone,z.zon_id,z.zon_name
					FROM zones z
					LEFT JOIN zone_cities zc ON zc.zct_zon_id = z.zon_id
					LEFT JOIN cities cty ON cty.cty_id = zc.zct_cty_id
					LEFT JOIN states s ON cty.cty_state_id = s.stt_id
					WHERE  1";
		$result	 = DBUtil::queryAll($sql, DBUtil::SDB(), [], true, 60 * 60 * 24, CacheDependency::Type_Cities);
		return $result;
	}
	
	/**
	 *  @param array $regions 
	 * @return string
	 */
	public static function getIdsByRegion($regions = [])
	{
		$ids = "";
		if(count($regions)>0)
		{
			DBUtil::getINStatement($regions, $bindString, $params);
			$sql = "SELECT GROUP_CONCAT(stt_id) as ids FROM states WHERE stt_active='1' AND stt_zone IN ($bindString)";
			$ids = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		}
		return $ids;
	}

	/** @param \Stub\common\Place $placeObj */
	public function getByCoordiantes($placeObj)
	{
		$googleObj	 = GoogleMapAPI::getObjectByLatLong($placeObj->coordinates->latitude, $placeObj->coordinates->longitude);
		$placeObj	 = GoogleMapAPI::getAdminLevel1($googleObj);
		/* @var $place \Stub\common\Place */
		$placeObj	 = \Stub\common\Place::initGoogePlace($placeObj);
		$code		 = $placeObj->alias;
		$name		 = $placeObj->name;
		$placeId	 = trim($placeObj->place_id);
		$stateModel	 = States::getByPlaceId($placeId);
		if ($stateModel != '')
		{
			goto result;
		}
		$stateModel = States::findByPlaceObj($placeObj);
		if ($stateModel)
		{
			goto update;
		}
		$stateModel					 = States::addState($placeObj);
		goto result;
		update:
		$stateModel->stt_place_id	 = $placeId;
		$stateModel->update();
		result:
		return $stateModel;
	}

	public static function findByPlaceObj($placeObj)
	{
		if ($placeObj->name == "")
		{
			return false;
		}
		$cond		 = [];
		$criteria	 = new CDbCriteria();
		$criteria->compare('stt_name', $placeObj->name);
		$criteria1	 = new CDbCriteria();
		$criteria1->compare('stt_code', $placeObj->alias);

		$criteria->mergeWith($criteria1, 'OR');

		$criteria2 = new CDbCriteria();
		$criteria2->addCondition("stt_active='1'");

		$criteria2->mergeWith($criteria);

		$model = States::model()->find($criteria2);
		return $model;
	}

	public static function getByPlaceId($placeId)
	{
		$sql = "SELECT * FROM states WHERE stt_place_id = '$placeId'";
		return States::model()->findBySql($sql);
	}

	public static function getByCode($code)
	{
		$sql = "SELECT * FROM states WHERE stt_code = '$code'";
		return States::model()->findBySql($sql);
	}

	public static function getByName($name)
	{
		$sql = "SELECT * FROM states WHERE stt_name = '$name'";
		return States::model()->findBySql($sql);
	}

	/** @param \Stub\common\Place $placeObj */
	public static function addState($placeObj)
	{
		$model					 = new States();
		$model->stt_code		 = $placeObj->alias;
		$model->stt_name		 = $placeObj->name;
		$model->stt_place_id	 = trim($placeObj->place_id);
		$model->stt_country_id	 = 99;
		$model->stt_active		 = 1;
		if (!$model->save())
		{
			throw new Exception("Error adding state.");
		}
		return $model;
	}

	public function getStateListForNote($query = '')
	{

		if ($query != '')
		{
			$qry .= " AND stt_name LIKE '%{$query}%'";
		}
		$query = " SELECT stt_id,stt_name, IF(stt_active = 0, '(InActive)', '') as status   FROM states WHERE stt_active ='1' " . $qry . " ";

		$result = DBUtil::queryAll($query, DBUtil::MDB());
		return $result;
	}

	public function getJSONAllStatebyQuery($query, $city = '', $airportShow = '0')
	{

		$rows		 = $this->getStateListForNote($query);
		$arrState	 = array();
		foreach ($rows as $row)
		{
			$arrState[] = array("id" => $row['stt_id'], "text" => $row['stt_name'] . ' ' . $row['status']);
		}
		$data = CJSON::encode($arrState);
		return $data;
	}

	/** @return States */
	public static function getByGeoCoordinates($lat, $long)
	{
		$model	 = false;
		$params	 = ["lat" => $lat, "long" => $long];
		$sql	 = "SELECT stt_id FROM geo_data 
				INNER JOIN states ON stt_geo_id=gdt_id AND gdt_admin_level=4
				WHERE gdt_lat BETWEEN :lat-4 AND :lat+4  AND 
					gdt_lng BETWEEN :long-4 AND :long+4
					AND ST_CONTAINS(gdt_polygon, POINT(:long, :lat))
			";

		$sttId = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		if ($sttId)
		{
			$model = States::model()->findByPk($sttId);
		}

		return $model;
	}

	public static function getDopdownList()
	{
		$sql	 = "SELECT stt_id, stt_name FROM `states` WHERE  stt_country_id = 99 AND stt_active = '1'";
		$result	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $result;
	}

	/**
	 * 
	 * @param type $id
	 * @param type $rule
	 * @return type
	 * @throws Exception
	 */
	public static function getDataById($id, $rule)
	{
		if ((trim($id) == null || trim($id) == ""))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		if ($rule == 3)
		{
			$where = " AND cty.cty_id = :id";
		}
		if ($rule == 2)
		{
			$where = " AND cty.cty_state_id = :id";
		}
		if ($rule == 1)
		{
			$where = " AND zct.zct_zon_id = :id";
		}
		$params	 = array('id' => $id);
		$sql	 = "SELECT DISTINCT stt.stt_id as sttid, stt.stt_zone as region, zct.zct_zon_id as zonid, GROUP_CONCAT(cty.cty_id) as ctyid  FROM zone_cities zct
					INNER JOIN cities cty ON cty.cty_id = zct.zct_cty_id AND cty.cty_active = 1 AND cty.cty_service_active = 1
					INNER JOIN states stt ON stt.stt_id = cty.cty_state_id AND stt.stt_active = '1'
					WHERE zct.zct_active = 1 $where";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	public static function getSatetNameById($ids)
	{
		$sql = "SELECT GROUP_CONCAT(stt_name) FROM `states` WHERE stt_id IN ($ids) AND  stt_active = '1'";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}
	public function getJSONSourceState($query, $state = '', $status = 0)
	{
		if ($state != "")
		{

			$rows = $this->getAllStatesbyQuery($query, $state);
		}
		else
		{
			$rows = $this->getSourceState($query, $state);
		}

		$arrStates	 = array();
		$i			 = 0;
		foreach ($rows as $row)
		{
			$i++;
			$arrStates[] = array("id" => $row['stt_id'], "text" => $row['stt_name'], "index" => $i);
		}
		if ($status == 1)
		{
			$data = $arrStates;
		}
		else
		{
			$data = CJSON::encode($arrStates);
		}

		return $data;
	}
	public function getAllStatesbyQuery($query = '', $state = '', $airportShow = '0')
	{
		$qry	 = '';
		$params3 = [];
		$params4 = [];
		$query	 = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1, "");
		DBUtil::getLikeStatement($query, $bindString1, $params2, '', '');

		if ($state != '')
		{
			DBUtil::getINStatement($state, $bindString4, $params4);
			$qry1 = " AND  stt_id IN ({$bindString4})";
		}
		if ($query != '')
		{
			DBUtil::getLikeStatement($query, $bindString3, $params3);
			$qry .= " AND stt_name LIKE $bindString3 ";
		}
		
		if ($state != '')
		{
			$sql = "SELECT stt_id, stt_name
					FROM states  WHERE stt_active=1 $qry1";
			return DBUtil::query($sql, DBUtil::SDB(), $params4);
		}
		else
		{

			$order = "stt_name ASC ";
			/*if ($query == '' && $state == '')
			{
				$order = " ctyRank ASC, statusRank DESC, startRank DESC, score DESC, cty.cty_display_name ASC ";
			}*/


			$sql = "SELECT stt_id, stt_name as stt_name,
					FROM states
					WHERE stt_active='1'  $qry $qry1
					ORDER BY $order
					LIMIT 0,50";

			return DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params2, $params3, $params4));
		}
	}
	
	public function getSourceState($query = '', $state = '')
	{
		$query	 = ($query == null || $query == "") ? "" : $query;
		$query1	 = str_replace(" ", "%", trim($query));
		
		
		$qry	 = '';
		$qry1	 = "";
		if ($state != '')
		{
			$qry1 = " AND stt_id in ($state)";
		}
		
		if ($query != '')
		{
			$qry .= " AND stt_name LIKE  '%$query%' ";
		}
		else
		{
			$params1 = [];
		}

		$order = "stt_name ASC ";
		$sql = "SELECT stt_id,stt_name
				FROM states 
				WHERE 1  $qry $qry1 AND stt_active='1'
				ORDER BY $order LIMIT 0,6";
	
		return DBUtil::query($sql, DBUtil::SDB());
	}
	
	
	
}
