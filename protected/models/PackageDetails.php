<?php

/**
 * This is the model class for table "package_details".
 *
 * The followings are the available columns in table 'package_details':
 * @property integer $pcd_id
 * @property integer $pcd_pck_id
 * @property integer $pcd_sequence
 * @property integer $pcd_from_city
 * @property integer $pcd_to_city
 * @property string $pcd_from_location
 * @property string $pcd_to_location
 * @property string $pcd_description
 * @property integer $pcd_trip_distance
 * @property integer $pcd_trip_duration
 * @property integer $pcd_day_serial
 * @property integer $pcd_night_serial
 * @property string $pcd_created_dt
 * @property string $pcd_modified_dt
 * @property integer $pcd_active
 * @property double $pcd_to_latitude
 * @property double $pcd_to_longitude
 * @property double $pcd_from_latitude
 * @property double $pcd_from_longitude

 * The followings are the available model relations:
 * @property Package $pcdPck
 * @property Cities $pcdFromCity
 * @property Cities $pcdToCity
 */
class PackageDetails extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'package_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pcd_day_serial', 'required'),
			array('pcd_pck_id, pcd_sequence, pcd_from_city, pcd_to_city, pcd_trip_distance, pcd_trip_duration, pcd_day_serial, pcd_night_serial, pcd_active', 'numerical', 'integerOnly' => true),
			array('pcd_from_location, pcd_to_location', 'length', 'max' => 255),
			array('pcd_description', 'length', 'max' => 2000),
			array('pcd_created_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pcd_id, pcd_pck_id, pcd_sequence, pcd_from_city, pcd_to_city, pcd_from_location, pcd_to_location, pcd_description, pcd_trip_distance, pcd_trip_duration, pcd_day_serial, pcd_night_serial, pcd_created_dt, pcd_modified_dt, pcd_active,pcd_to_latitude,pcd_to_longitude,pcd_from_latitude,pcd_from_longitude', 'safe', 'on' => 'search'),
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
			'pcdPck'		 => array(self::BELONGS_TO, 'Package', 'pcd_pck_id'),
			'pcdFromCity'	 => array(self::BELONGS_TO, 'Cities', 'pcd_from_city'),
			'pcdToCity'		 => array(self::BELONGS_TO, 'Cities', 'pcd_to_city'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pcd_id'			 => 'Id',
			'pcd_pck_id'		 => 'Pck',
			'pcd_sequence'		 => 'Sequence',
			'pcd_from_city'		 => 'From City',
			'pcd_to_city'		 => 'To City',
			'pcd_from_location'	 => 'From Location',
			'pcd_to_location'	 => 'To Location',
			'pcd_description'	 => 'Description',
			'pcd_trip_distance'	 => 'Trip Distance',
			'pcd_trip_duration'	 => 'Trip Duration',
			'pcd_day_serial'	 => 'Day Serial',
			'pcd_night_serial'	 => 'Night Serial',
			'pcd_created_dt'	 => 'Created Dt',
			'pcd_modified_dt'	 => 'Modified Dt',
			'pcd_active'		 => 'Active',
			'pcd_to_latitude'	 => 'To Latitude ',
			'pcd_to_longitude'	 => 'To Longitude',
			'pcd_from_latitude'	 => 'From Latitude',
			'pcd_from_longitude' => 'From Longitude',
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

		$criteria->compare('pcd_id', $this->pcd_id);
		$criteria->compare('pcd_pck_id', $this->pcd_pck_id);
		$criteria->compare('pcd_sequence', $this->pcd_sequence);
		$criteria->compare('pcd_from_city', $this->pcd_from_city);
		$criteria->compare('pcd_to_city', $this->pcd_to_city);
		$criteria->compare('pcd_from_location', $this->pcd_from_location, true);
		$criteria->compare('pcd_to_location', $this->pcd_to_location, true);
		$criteria->compare('pcd_description', $this->pcd_description, true);
		$criteria->compare('pcd_trip_distance', $this->pcd_trip_distance);
		$criteria->compare('pcd_trip_duration', $this->pcd_trip_duration);
		$criteria->compare('pcd_day_serial', $this->pcd_day_serial);
		$criteria->compare('pcd_night_serial', $this->pcd_night_serial);
		$criteria->compare('pcd_created_dt', $this->pcd_created_dt, true);
		$criteria->compare('pcd_modified_dt', $this->pcd_modified_dt, true);
		$criteria->compare('pcd_active', $this->pcd_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PackageDetails the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getDetailsById($pck_id, $isCmd = false)
	{
		if (trim($pck_id) == null || trim($pck_id) == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params	 = array('pck_id' => $pck_id);
		$sql	 = "SELECT
			pcd.*,pck.*,pci.pci_images,
			frct.cty_name as fcity,
			toct.cty_name as tcity,
			pr.prt_package_rate AS package_rate
			FROM package pck
			JOIN package_details pcd ON pck.pck_id = pcd.pcd_pck_id AND pcd.pcd_active = 1
			LEFT JOIN (SELECT pci1.*
			FROM package_images pci1 LEFT JOIN package_images pci2 ON pci1.pci_pck_id = pci2.pci_pck_id AND pci1.pci_id < pci2.pci_id
			WHERE pci2.pci_pck_id IS NULL ) pci ON pci.pci_pck_id = pck_id AND pci.pci_status = 1 AND pci.pci_image_type = 1 AND pci.pci_images IS NOT NULL AND pci.pci_images <> ''
			LEFT JOIN cities frct ON frct.cty_id = pcd.pcd_from_city
			LEFT JOIN cities toct ON toct.cty_id = pcd.pcd_to_city
			LEFT JOIN (SELECT * FROM package_rate prt WHERE prt.prt_pck_id=:pck_id
				AND (prt.prt_package_valid_from IS NULL
				OR prt.prt_package_valid_from < NOW())
				AND (prt.prt_package_valid_to IS NULL
				OR prt.prt_package_valid_to > NOW()+ INTERVAL 7 DAY)
				AND prt.prt_status = 1 
				GROUP BY prt.prt_package_rate 
				ORDER BY prt.prt_package_rate LIMIT 0,1) pr ON pr.prt_pck_id=pck.pck_id
			WHERE pck.pck_id=:pck_id AND pcd_active = 1
			";

		if ($isCmd)
		{
			$resultset = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
			return $resultset;
		}
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['pcd_day_serial'],
				'defaultOrder'	 => 'pcd_day_serial ASC'],
			'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function updatePackageDetails($pck_id)
	{
		$sql = "UPDATE `package_details` SET `pcd_active`=0 WHERE `pcd_pck_id`=$pck_id";
		/* @var $cdb CDbCommand */
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function getDetails($pck_id)
	{
		if (trim($pck_id) == null || trim($pck_id) == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params		 = array('pck_id' => $pck_id);
		$sql		 = "SELECT
						pcd.*,
						a.cty_name as pickup_city_name,
						b.cty_name as drop_city_name, 
						pcd_from_location as pickup_address,
						pcd_to_location as drop_address,
						pcd_trip_distance as distance,
						pcd_trip_duration as duration
						FROM package_details pcd 
						LEFT JOIN cities a ON a.cty_id = pcd_from_city 
						LEFT JOIN cities b ON b.cty_id = pcd_to_city 
						WHERE pcd_pck_id = :pck_id";
		$resultset	 = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $resultset;
	}

	public function getTotalDuration($pck_id)
	{
		$sql		 = "SELECT (((pcd.pcd_day_serial-1) * 24 * 60) + sum(pcd.pcd_trip_duration)) totDuration, pcd.pcd_day_serial, sum(pcd.pcd_trip_duration) 
FROM   package_details pcd
WHERE  pcd.pcd_pck_id = $pck_id AND
pcd.pcd_day_serial IN (SELECT max(pcd1.pcd_day_serial)
FROM   package_details pcd1
WHERE  pcd1.pcd_pck_id = $pck_id)";
		$resultset	 = DBUtil::queryRow($sql);
		return $resultset;
	}

	public static function getIdByUrlName($name)
	{
		$param	 = ['name' => $name];
		$sql	 = "SELECT p.pck_id FROM package p  LEFT JOIN package_rate pr ON pr.prt_pck_id=p.pck_id  WHERE p.pck_url= :name AND p.pck_active=1";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $result;
	}

	public static function getCItyByPackid($packId)
	{
		$sql	 = "SELECT pcd_from_city,pcd_to_city from package_details where pcd_pck_id=" . $packId . "";
		#echo $sql;
		$result	 = DBUtil::queryAll($sql);
		#print_r($result);
		foreach ($result as $res)
		{
			$city_arr[]	 = $res['pcd_from_city'];
			$city_arr[]	 = $res['pcd_to_city'];
		}
		$unique_city_id	 = implode(",", array_unique($city_arr));
		$sql			 = "SELECT cty_name,cty_city_desc from cities where cty_id IN (" . $unique_city_id . ") AND cty_city_desc!=''";
		$result			 = DBUtil::queryAll($sql);
		return $result;
	}

}
