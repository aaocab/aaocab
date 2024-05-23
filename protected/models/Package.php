<?php

/**
 * This is the model class for table "package".
 *
 * The followings are the available columns in table 'package':
 * @property integer $pck_id
 * @property string $pck_name
 * @property string $pck_auto_name
 * @property string $pck_desc
 * @property integer $pck_no_of_days
 * @property integer $pck_no_of_nights
 * @property integer $pck_km_included 
 * @property integer $pck_min_included
 * @property integer $pck_created_by
 * @property integer $pck_approved_by
 * @property string $pck_approved_on
 * @property string $pck_created_dt
 * @property string $pck_modified_dt
 * @property integer $pck_active
 * @property string $pck_url
 * @property string $pck_inclusions
 * @property string $pck_exclusions
 * @property string $pck_notes
 * The followings are the available model relations:
 * @property PackageDetails[] $packageDetails
 * @property PackageRate[] $packageRates
 */
class Package extends CActiveRecord
{

	public $packageJsonData, $from_city, $to_city, $zoneId;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'package';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pck_name', 'required'),
			array('pck_name', 'unique', 'on' => 'insert,update'),
			['pck_id', 'validateNight', 'on' => 'insert'],
			array('pck_no_of_days, pck_no_of_nights, pck_km_included,pck_min_included,pck_created_by, pck_approved_by, pck_active', 'numerical', 'integerOnly' => true),
			array('pck_name, pck_auto_name', 'length', 'max' => 255),
			array('pck_desc', 'length', 'max' => 1000),
			array('pck_modified_dt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pck_id, pck_name, pck_auto_name, pck_desc, pck_no_of_days, pck_no_of_nights, pck_km_included, pck_created_by, pck_approved_by, pck_approved_on, pck_created_dt, pck_modified_dt, pck_active,pck_url,pck_min_included,packageJsonData,pck_inclusions,pck_exclusions,pck_notes', 'safe', 'on' => 'search'),
			array('pck_id, pck_name, pck_auto_name, pck_desc, pck_no_of_days, pck_no_of_nights, pck_km_included, pck_created_by, pck_approved_by, pck_approved_on, pck_created_dt, pck_modified_dt, pck_active,pck_url,pck_min_included,packageJsonData,pck_inclusions,pck_exclusions,pck_notes', 'safe'),
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
			'packageDetails' => array(self::HAS_MANY, 'PackageDetails', 'pcd_pck_id'),
			'packageRates'	 => array(self::HAS_MANY, 'PackageRate', 'prt_pck_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pck_id'			 => 'Package ID',
			'pck_name'			 => 'Package Name',
			'pck_auto_name'		 => 'Package Auto Name',
			'pck_desc'			 => 'Package Description',
			'pck_no_of_days'	 => 'No of days',
			'pck_no_of_nights'	 => 'No of nights',
			'pck_km_included'	 => 'Km Included',
			'pck_min_included'	 => 'Min Included',
			'pck_created_by'	 => 'Created By',
			'pck_approved_by'	 => 'Approved By',
			'pck_approved_on'	 => 'Approved On',
			'pck_created_dt'	 => 'Created',
			'pck_modified_dt'	 => 'Modified',
			'pck_active'		 => 'Active',
			'pck_url'			 => 'Url',
			'pck_inclusions'	 => 'Inclusions',
			'pck_exclusions'	 => 'Exclusions',
			'pck_notes'			 => 'Notes & Disclaimers',
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

		$criteria->compare('pck_id', $this->pck_id);
		$criteria->compare('pck_name', $this->pck_name, true);
		$criteria->compare('pck_auto_name', $this->pck_auto_name, true);
		$criteria->compare('pck_desc', $this->pck_desc, true);
		$criteria->compare('pck_no_of_days', $this->pck_no_of_days);
		$criteria->compare('pck_no_of_nights', $this->pck_no_of_nights);
		$criteria->compare('pck_km_included', $this->pck_km_included);
		$criteria->compare('pck_min_included', $this->pck_min_included);
		$criteria->compare('pck_created_by', $this->pck_created_by);
		$criteria->compare('pck_approved_by', $this->pck_approved_by);
		$criteria->compare('pck_approved_on', $this->pck_approved_on, true);
		$criteria->compare('pck_created_dt', $this->pck_created_dt, true);
		$criteria->compare('pck_modified_dt', $this->pck_modified_dt, true);
		$criteria->compare('pck_active', $this->pck_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Package the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function validateNight($attribute, $params)
	{
		$packagedetails = $this->packageDetails;

		$lastNightSerial = $packagedetails[count($packagedetails) - 1]['pcd_night_serial'];
		if ($lastNightSerial != 0 || $lastNightSerial == '')
		{
			$this->addError('pcd_id', "Last night count must be 0");
			return false;
		}
		return true;
	}

	public function getTripType($bktype = 0, $postfix = '')
	{
		$arrBktype = [
			1	 => 'One way drop only' . ' ' . $postfix,
			2	 => 'Package' . ' ' . $postfix
		];
		if ($bktype != 0)
		{
			return $arrBktype[$bktype];
		}
		else
		{
			return $arrBktype;
		}
	}

	public function getList($qry = [])
	{
		$packageName	 = ($qry['packageName'] != '') ? $qry['packageName'] : '';
		$packageautoname = ($qry['packageautoname'] != '') ? $qry['packageautoname'] : '';
		if ($qry['zoneId'] != '')
		{
			$condZone = " INNER JOIN zone_cities ON frct.cty_id = zct_cty_id AND zct_zon_id = " . $qry['zoneId'];
		}

		$sql = "SELECT pck.pck_active, pck.pck_id, pck.pck_name, pck.pck_auto_name, 
		      	pck.pck_desc, pck.pck_no_of_days, pck.pck_no_of_nights,
                concat(frct.cty_name,' - ', group_concat(toct.cty_name SEPARATOR ' - ')) AS route_detail, pck.pck_created_by, concat(adm.adm_fname, ' ',adm.adm_lname) as createdbyname,
                pck.pck_approved_by, IF(pck.pck_approved_by!= 0, concat(adm1.adm_fname, ' ',adm1.adm_lname), ' ') AS pckapprovedby,
				prt.prt_status
                FROM `package` pck
                INNER JOIN package_details pcd ON pck.pck_id = pcd.pcd_pck_id AND pcd.pcd_active = 1
				LEFT JOIN package_rate prt ON prt.prt_pck_id = pck.pck_id
                INNER JOIN cities frct ON frct.cty_id = pcd.pcd_from_city
                INNER JOIN cities toct ON toct.cty_id = pcd.pcd_to_city
                $condZone
                LEFT JOIN admins adm ON adm.adm_id = pck.pck_created_by
                LEFT JOIN admins adm1 ON adm1.adm_id = pck.pck_approved_by
                where pck.pck_active = 1 ";

		if ($packageName != '')
		{
			$sql .= " AND pck.pck_name LIKE '%$packageName%'";
		}

		if ($packageautoname != '')
		{
			$sql .= " AND pck.pck_auto_name LIKE '%$packageautoname%'";
		}
		if ($qry['from_city'] != '')
		{
			$sql .= " AND (pcd.pcd_from_city = " . $qry['from_city'] . " OR pcd.pcd_to_city = " . $qry['from_city'] . ")";
		}
		if ($qry['noofnights'] != '')
		{
			$sql .= " AND pck.pck_no_of_nights = " . $qry['noofnights'];
		}
		if ($qry['noofdays'] != '')
		{
			$sql .= " AND pck.pck_no_of_days = " . $qry['noofdays'];
		}

		$sql .= " group by pck.pck_id";



		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['pck_name'],
				'defaultOrder'	 => 'pck.pck_id DESC'],
			'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function getByPackageId($pck_id)
	{
		$pageSize	 = 25;
		$sql		 = "SELECT pck.pck_id, pck.pck_name, pck.pck_desc, pck.pck_auto_name, pck.pck_no_of_days, pck.pck_no_of_nights, pcd.pcd_day_serial, pcd.pcd_from_location, pcd.pcd_to_location, pcd.pcd_description,frct.cty_name from_city,toct.cty_name to_city
                        FROM `package` pck
                        INNER JOIN package_details pcd ON pck.pck_id = pcd.pcd_pck_id AND pcd.pcd_active = 1
                        INNER JOIN cities frct ON frct.cty_id = pcd.pcd_from_city
                        INNER JOIN cities toct ON toct.cty_id = pcd.pcd_to_city
                        WHERE pck_id='$pck_id' ";


		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['pck_name'],
				'defaultOrder'	 => 'pck_name ASC'],
			'pagination'	 => ['pageSize' => 20],
		]);
		$resultset		 = DBUtil::queryAll($sql);
		return $resultset;
	}

	public function getPckDetails($pck_id)
	{
		$pageSize	 = 25;
		$sql		 = "SELECT pck.pck_id, pck.pck_name, pck.pck_desc, pck.pck_auto_name, pck.pck_no_of_days, pck.pck_no_of_nights
                        FROM `package` pck
                        WHERE pck.pck_id='$pck_id' AND  pck.pck_active = 1 ";


		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['pck_name'],
				'defaultOrder'	 => 'pck_name ASC'],
			'pagination'	 => ['pageSize' => 20],
		]);
		$resultset		 = DBUtil::queryAll($sql);
		return $resultset;
	}

	public function getPackageRates($package, $cabType, $fromDate = '')
	{
		$sql = "SELECT package.*,package_rate.*,
						(SELECT   pcd_from_city FROM package_details WHERE pcd_pck_id = :pck ORDER BY pcd_id ASC LIMIT 1) firstFromCity,
						(SELECT   pcd_to_city FROM package_details WHERE pcd_pck_id = :pck ORDER BY pcd_id DESC LIMIT 1) lastToCity
				FROM   package
				LEFT JOIN package_rate
				  ON prt_pck_id = pck_id AND prt_status = 1 AND prt_package_cab_type = :cabType
				  AND (prt_package_valid_from IS NULL OR :validFrom > prt_package_valid_from)
				  AND (prt_package_valid_to IS NULL OR :validTo < prt_package_valid_to)
				WHERE   pck_id = :pck AND pck_active = 1
				ORDER BY prt_package_rate DESC
				LIMIT    1;";
		$res = DBUtil::queryRow($sql, DBUtil::SDB(), ['cabType' => $cabType, 'validFrom' => $fromDate, 'validTo' => $fromDate, 'pck' => $package], 60 * 60, CacheDependency::Type_Package);
		return $res;
	}

	public function getPackage($pck_id)
	{
		$sql = " SELECT * FROM `package`"
				. " JOIN (SELECT "
				. "(SELECT pcd_from_city FROM package_details WHERE pcd_pck_id=$pck_id ORDER BY pcd_id ASC LIMIT 1) AS firstFromCity,"
				. " (SELECT pcd_to_city FROM package_details WHERE pcd_pck_id=$pck_id ORDER BY pcd_id DESC LIMIT 1) AS lastToCity"
				. " FROM `package_details`"
				. " WHERE pcd_pck_id=$pck_id LIMIT 1)a WHERE pck_id=$pck_id";
		$res = DBUtil::queryRow($sql);
		return $res;
	}

	public function getJSONPackages($query, $pck_id = '')
	{
		$rows = $this->getPackageList($query, $pck_id);

		$arrCities = array();

		foreach ($rows as $row)
		{
			$arrCities[] = array("id" => $row['pck_id'], "text" => $row['pck_name']);
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	public function getPackageList($query = '', $pck_id = '')
	{

		if ($pck_id != '')
		{
			$qry1 = " AND pck_id='$pck_id'";
		}
		else
		{
			if ($query == '')
			{
				$qry = " 1";
			}
			else
			{
				$qry = " AND pck_name LIKE '%{$query}%' OR  pck_desc LIKE '%{$query}%'";
			}
		}

		$sql = "SELECT pck.pck_id,  pck.pck_name 
			FROM package pck 
			JOIN package_rate prt ON prt.prt_pck_id = pck.pck_id AND prt.prt_status = 1
			WHERE pck_active = 1 AND trim(pck_desc) <> '' AND trim(pck_name) <> '' AND  $qry $qry1 GROUP BY pck_id LIMIT 0,30";
		return DBUtil::queryAll($sql);
	}

	public function convertToRoute($pckid)
	{
		$currentDt = date('Y-m-d H:i:s', strtotime('+4 hour'));

		if ($pickupDtTime != '')
		{
			$currentDt = $pickupDtTime;
		}
		$packagemodel	 = Package::model()->findByPk($pckid);
		$pModel			 = $packagemodel->packageDetails;

		foreach ($pModel as $key => $value)
		{
			$dayCount	 = $value->pcd_day_serial;
			$pickDate	 = date('Y-m-d H:i:s', strtotime($currentDt . ' +' . ($dayCount - 1) . ' DAYS'));



			$brtRoute					 = new BookingRoute();
//			$brtRoute->brt_bkg_id		 = $model->bkg_id;
			$brtRoute->brt_from_city_id	 = $value->pcd_from_city;
			$brtRoute->brt_to_city_id	 = $value->pcd_to_city;

			$brtRoute->brt_pickup_datetime	 = $pickDate;
			$brtRoute->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($pickDate);
			$brtRoute->brt_pickup_date_time	 = date('h:i A', strtotime($pickDate));
			$brtRoute->brt_trip_distance	 = $value->pcd_trip_distance;
			$brtRoute->brt_trip_duration	 = $value->pcd_trip_duration;
			if ($value->pcd_from_location != '')
			{
				$brtRoute->brt_from_location = $value->pcd_from_location;
			}
			if ($value->pcd_to_location != '')
			{
				$brtRoute->brt_to_location = $value->pcd_to_location;
			}
//					$brtRoute->attributes	 = $brtRoute;
			$brtRoute->brt_status = 2;

			$dayCount			 = $value->pcd_day_serial;
			$pickDate			 = date('Y-m-d H:i:s', strtotime($currentDt . ' +' . ($dayCount - 1) . ' DAYS'));
			$Arrmulticity[$key]	 = [
				"pickup_city"		 => $value->pcd_from_city,
				"pickup_city_name"	 => $value->pcdFromCity->cty_name,
				"drop_city"			 => $value->pcd_to_city,
				"drop_city_name"	 => $value->pcdToCity->cty_name,
				"pickup_address"	 => $value->pcd_from_location,
				"drop_address"		 => $value->pcd_to_location,
				"date"				 => $pickDate,
				"distance"			 => $value->pcd_trip_distance,
				"duration"			 => $value->pcd_trip_duration,
				"pickup_date"		 => DateTimeFormat::DateTimeToDatePicker($pickDate),
				"pickup_time"		 => date('h:i A', $pickDate),
				"daycount"			 => $value->pcd_day_serial,
				"nightcount"		 => $value->pcd_night_serial,
				"packagedelID"		 => $value->pcd_id,
			];
		}
	}

	public function getRateList($pckid = '')
	{
		if ($pckid != '')
		{
			$sql	 = "SELECT *
                  FROM package pck
                   JOIN  package_rate prt ON prt.prt_pck_id = pck.pck_id AND prt.prt_status = 1 
                   WHERE pck.pck_active = 1 AND pck_id = $pckid group by pck_id";
			$result	 = DBUtil::queryAll($sql, DBUtil::SDB());
		}
		return $result;
	}

	public static function getNamebyId($pck_id)
	{

		$sql	 = "SELECT pck_name FROM package WHERE  pck_id = {$pck_id}";
		$result	 = DBUtil::command($sql)->queryScalar();
		return $result;
	}

	public function getListtoShow($pckid = '', $qry = [], $isMobileApp = false, $pageNumber = 0)
	{
		$limitRange = 10;
		if ($pageNumber > 0)
		{
			$pageNumber = ($pageNumber + $limitRange);
		}
		else
		{
			$pageNumber = 0;
		}

		$params	 = [];
		$sql = "SELECT   pck.pck_id, pck.pck_desc, pck.pck_name, pci_images,pck_url, prt_package_rate, pck_auto_name,prt_package_cab_type
				FROM     package pck
						 LEFT JOIN package_rate prt ON prt.prt_pck_id = pck.pck_id AND prt.prt_status = 1 AND (prt.prt_package_valid_from IS NULL OR prt.prt_package_valid_from < NOW()) AND (prt.prt_package_valid_to IS NULL OR prt.prt_package_valid_to > NOW()+ INTERVAL 7 DAY)
                         INNER JOIN package_details pcd ON pck.pck_id = pcd.pcd_pck_id AND pcd.pcd_active = 1
						 LEFT JOIN (SELECT   pci1.*
									FROM     package_images pci1 LEFT JOIN package_images pci2 ON pci1.pci_pck_id = pci2.pci_pck_id  AND pci1.pci_id < pci2.pci_id                   
									WHERE pci2.pci_pck_id IS NULL ) pci
						   ON pci.pci_pck_id = pck_id AND pci.pci_status = 1 AND pci.pci_image_type = 1 AND pci.pci_images IS NOT NULL AND pci.pci_images <> ''
				WHERE    pck.pck_active = 1";
		if ($qry['city'] != '')
		{
			$params['city'] = $qry['city'];
			$sql .= " AND (pcd.pcd_from_city = :city OR pcd.pcd_to_city = :city)";
		}

		if ($qry['min_nights'] != '')
		{
			$params['minnight'] = $qry['min_nights'];
			$sql .= " AND pck.pck_no_of_nights >=:minnight";
		}
		if ($qry['max_nights'] != '')
		{
			$params['maxnight'] = $qry['max_nights'];
			$sql .= " AND pck.pck_no_of_nights <=:maxnight";
		}

		$sql .= " GROUP BY pck_id";
		if ($isMobileApp)
		{
			$sql .= " LIMIT $pageNumber, $limitRange";
		}
		$result = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $result;
	}

	public static function getMatchingWithRoutes($routeArr = [], $fromDate, $noOfDays = 0)
	{
		$where = "";
		if ($noOfDays > 0)
		{
			$preNum	 = $noOfDays - 1;
			$postNum = $noOfDays + 1;
			$where	 = " AND pck_no_of_days BETWEEN $preNum AND $postNum";
		}

		$result = [];
		if (isset($routeArr) && sizeof($routeArr) > 1)
		{
			$fromCity	 = $routeArr[0];
			unset($routeArr[0]);
			$toCityStr	 = implode(',', $routeArr);
			$sql		 = "SELECT  distinct pcd_pck_id, count(DISTINCT pcd_id) countrt, pck_auto_name,pck_name, package.pck_no_of_days
				FROM     package_details
				INNER JOIN package ON pck_id = pcd_pck_id AND pck_active = 1
				INNER JOIN 	package_rate 
				ON   prt_pck_id = pcd_pck_id
				AND	(prt_package_valid_from IS NULL OR '$fromDate' > prt_package_valid_from) 
						AND (prt_package_valid_to IS NULL OR '$fromDate' < prt_package_valid_to)
						AND package_rate.prt_status = 1 
				WHERE    pcd_active = 1 AND 
					(
						(pcd_sequence = 1 AND pcd_from_city = $fromCity) 
						OR ( pcd_to_city IN ($toCityStr))
					) $where 
				GROUP BY pcd_pck_id			
				ORDER BY countrt DESC
				 ";
			$result		 = DBUtil::queryAll($sql, DBUtil::SDB());
		}
		return $result;
	}

	public static function createUrl()
	{
		$sql = "SELECT pck_id,pck_name FROM package WHERE pck_url IS NULL";

		$result = DBUtil::queryAll($sql);
		foreach ($result as $res)
		{
			$pack_id		 = $res['pck_id'];
			$packName		 = $res['pck_name'];
			$pck_url_name	 = str_replace(' ', '-', $packName);
			$pck_url		 = preg_replace('/[^A-Za-z0-9\-]/', '', $pck_url_name);
			$pck_url		 = strtolower(preg_replace('/-{2,}/', '-', $pck_url));
			$sql			 = "UPDATE package SET pck_url='" . $pck_url . "' WHERE pck_id = $pack_id";
			$rowsUpdated	 = DBUtil::command($sql)->execute();
			$return			 = ($rowsUpdated > 0) ? true : false;
		}
		return $return;
	}

	public function fetchActivePackageList()
	{
		$sql		 = "SELECT pck_id,pck_name,pck_url FROM `package` WHERE pck_active = 1 AND pck_url IS NOT NULL";
		$recordall	 = DBUtil::queryAll($sql);
		return $recordall;
	}
        
        public function getVendorByVehicleId($vehicleId)
	{

		$params	 = ['vehicleId' => $vehicleId];
		
		$sql1	 = "SELECT distinct(vpk_vnd_id) FROM `vendor_packages` WHERE FIND_IN_SET('$vehicleId',vpk_vhc_id) AND vpk_type=1 ORDER bY vpk_id DESC LIMIT 0,1";
                $row1	 = DBUtil::queryRow($sql1, DBUtil::MDB(), $params);
		return $row1['vpk_vnd_id'];
	}

}
