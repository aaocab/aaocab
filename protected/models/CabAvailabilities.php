<?php

/**
 * This is the model class for table "cab_availabilities".
 *
 * The followings are the available columns in table 'cab_availabilities':
 * @property integer $cav_id
 * @property integer $cav_vendor_id
 * @property integer $cav_cab_id
 * @property integer $cav_from_city
 * @property string $cav_to_cities
 * @property integer $cav_driver_id
 * @property integer $cav_amount
 * @property integer $cav_total_amount
 * @property integer $cav_duration
 * @property integer $cav_status
 * @property string $cav_date_time
 * @property string $cav_created_at
 * @property string $cav_modified_at
 * @property integer $cav_is_oneway
 * @property integer $cav_is_shared
 * @property integer $cav_is_local_trip
 * 
 * @property Vendors $cavVendor
 * @property Vehicles $cavCab
 * @property Drivers $cavDriver
 * 
 */
class CabAvailabilities extends CActiveRecord
{

	public $vnd_id;
	public $from_date, $to_date, $to_city, $from_city, $time;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cab_availabilities';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('cav_cab_id, cav_from_city, cav_to_cities, cav_driver_id, cav_date_time', 'required'),
			array('cav_cab_id, cav_from_city, cav_driver_id, cav_amount,cav_total_amount, cav_duration, cav_status', 'numerical', 'integerOnly' => true),
			array('cav_to_cities', 'length', 'max' => 255),
			array('cav_status', 'required', 'on' => 'updatestatus'),
			array('from_date, to_date', 'date', 'format' => 'dd/MM/yyyy'),
			array('cav_cab_id', 'checkOverlappingTimeSlot', 'on' => 'insert'),
			array('cav_cab_id', 'checkCabStatus', 'on' => 'insert'),
			array('cav_date_time', 'checkExpiry', 'on' => 'insert'),
			array('cav_driver_id', 'checkDriverStatus', 'on' => 'insert'),
			array('cav_vendor_id', 'checkVendor', 'on' => 'insert'),
			array('cav_id', 'checkOverrate', 'on' => 'insert'),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('cav_id, cav_vendor_id, cav_cab_id, cav_from_city, cav_to_cities, cav_driver_id, cav_amount, cav_duration, cav_status, cav_date_time, cav_created_at, cav_modified_at,vnd_id,to_city,from_city,to_date,from_date, cav_is_oneway, cav_is_shared', 'safe', 'on' => 'search'),
			array('cav_id, cav_vendor_id, cav_cab_id, cav_from_city, cav_to_cities, cav_driver_id, cav_amount,cav_total_amount, cav_duration, cav_status, cav_date_time, cav_created_at, cav_modified_at,vnd_id,to_city,from_city,to_date,from_date, cav_is_oneway, cav_is_shared,cav_is_local_trip', 'safe'),
		);
	}

	public function checkDuplicate($attribute, $params)
	{
		$scenario	 = $this->scenario;
		$check		 = self::model()->findByCabId($this->$attribute);
		if($scenario == 'insert')
		{
			if($check)
			{
				$this->addError($attribute, 'Availability already added for the cab');
				return false;
			}
			if($this->cav_date_time < FILTER::getDBDateTime())
			{
				$this->addError($attribute, 'Availability Time is already started for the cab');
				return false;
			}
		}
		return true;
	}

	public function checkOverlappingTimeSlot($attribute, $params)
	{
		$scenario	 = $this->scenario;
		$cab_id		 = $this->cav_cab_id;
		$startDate	 = $this->cav_date_time;
		$duration	 = $this->cav_duration;

		$check = self::model()->findOverLappingTimeSlot($cab_id, $startDate, $duration);
		if($scenario == 'insert')
		{
			if(sizeof($check) > 0)
			{
				$this->addError($attribute, 'The cab is already added for the time slot');
				return false;
			}
		}
		return true;
	}

	public function checkCabStatus($attribute, $params)
	{
		$scenario	 = $this->scenario;
		$checkStatus = ($this->cavCab->getVehicleApproveStatus() && $this->cavCab->vhc_approved == 1);
		if($scenario == 'insert' && !$checkStatus)
		{
			$this->addError($attribute, 'The cab is not approved or ready to serve');
			return false;
		}
		return true;
	}

	public function checkVendor($attribute, $params)
	{
		$scenario = $this->scenario;
		if($scenario == 'insert' && !$this->cavVendor)
		{
			$this->addError($attribute, 'The vendor is not validated');
			return false;
		}

		return true;
	}

	public function checkDriverStatus($attribute, $params)
	{
		$scenario = $this->scenario;
		if($scenario == 'insert' && !$this->cavDriver)
		{
			$this->addError($attribute, 'The driver is not active');
			return false;
		}
		$checkStatus = $this->cavDriver->getDriverApproveStatus();

		if($scenario == 'insert' && !$checkStatus)
		{
			$this->addError($attribute, 'The driver is not approved or ready to serve');
			return false;
		}
		return true;
	}

	public function checkExpiry($attribute, $params)
	{
		$scenario	 = $this->scenario;
		$dbTime		 = FILTER::getDBDateTime();
		if($scenario == 'insert')
		{
			if($this->cav_date_time < $dbTime)
			{
				$this->addError($attribute, 'Availability Time is already started/passed for the cab');
				return false;
			}
			if(strtotime($this->cav_date_time) - strtotime($dbTime) > (10 * 24 * 60 * 60))
			{
				$this->addError($attribute, 'Availability Time is cannot be greater than 10 days');
				return false;
			}
			if($this->cav_duration <= 0)
			{
				$this->addError($attribute, 'Availability duration is not supplied');
				return false;
			}
		}
		return true;
	}

	public function checkOverrate($attribute, $params)
	{
		$scenario = $this->scenario;

		if($this->cav_is_local_trip == 0 && $scenario == 'insert')
		{
			$isOverRated = CabAvailabilities::compareRates($this->attributes);
			if($isOverRated)
			{
				$this->addError('cav_id', 'Proposed amount is greater than normal');
				return false;
			}
		}
		if($this->cav_is_local_trip == 1 && $this->cav_from_city != $this->cav_to_cities)
		{
			$this->addError('cav_id', 'Local trip must have same source and destination');
			return false;
		}
		if($this->cav_amount == 0 && $this->cav_is_local_trip == 0)
		{
			$this->addError('cav_amount', 'Amount cannot be set 0');
			return false;
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
			'cavVendor'		 => array(self::BELONGS_TO, 'Vendors', 'cav_vendor_id'),
			'cavCab'		 => array(self::BELONGS_TO, 'Vehicles', 'cav_cab_id'),
			'cavFromCity'	 => array(self::BELONGS_TO, 'Cities', 'cav_from_city'),
			'cavDriver'		 => array(self::BELONGS_TO, 'Drivers', 'cav_driver_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cav_id'			 => 'Cav',
			'cav_vendor_id'		 => 'Vendor',
			'cav_cab_id'		 => 'Cab',
			'cav_from_city'		 => 'From City',
			'cav_to_cities'		 => 'To City',
			'cav_driver_id'		 => 'Driver',
			'cav_amount'		 => 'Vendor Quoted Amount',
			'cav_total_amount'	 => 'Total Amount',
			'cav_duration'		 => 'Duration',
			'cav_status'		 => 'Availability Status',
			'cav_date_time'		 => 'Date Time',
			'cav_created_at'	 => 'Created At',
			'cav_modified_at'	 => 'Modified At',
			'cav_is_oneway'		 => 'Is Oneway',
			'cav_is_shared'		 => 'Is Shared',
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

		$criteria->compare('cav_id', $this->cav_id);
		$criteria->compare('cav_vendor_id', $this->cav_vendor_id);
		$criteria->compare('cav_cab_id', $this->cav_cab_id);
		$criteria->compare('cav_from_city', $this->cav_from_city);
		$criteria->compare('cav_to_cities', $this->cav_to_cities, true);
		$criteria->compare('cav_driver_id', $this->cav_driver_id);
		$criteria->compare('cav_amount', $this->cav_amount);
		$criteria->compare('cav_total_amount', $this->cav_total_amount);
		$criteria->compare('cav_duration', $this->cav_duration);
		$criteria->compare('cav_status', $this->cav_status);
		$criteria->compare('cav_date_time', $this->cav_date_time, true);
		$criteria->compare('cav_created_at', $this->cav_created_at, true);
		$criteria->compare('cav_modified_at', $this->cav_modified_at, true);
		$criteria->compare('cav_is_oneway', $this->cav_is_oneway);
		$criteria->compare('cav_is_shared', $this->cav_is_shared);
		$criteria->compare('cav_is_local_trip', $this->cav_is_local_trip);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CabAvailabilities the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @deprecated  
	 * new function : addNew()
	 */
	public function add($data)
	{
		exit;
		$model = $this;
		foreach($data as $attr => $val)
		{
			if($val == null || $val == '' || $val == 'null')
			{
				unset($data[$attr]);
			}
		}
		$model->attributes	 = $data;
		$model->cav_status	 = 1;
		$success			 = false;
		if($model->validate())
		{
			try
			{
				$success = $model->save();
			}
			catch(Exception $e)
			{
				$success = false;
				$model->addError('cav_id', $e->getMessage());
			}
		}
		return $success;
	}

	public function addNew()
	{
		$model				 = new CabAvailabilities();
		$model->attributes	 = $this->attributes;
		$errorCode			 = ReturnSet::ERROR_VALIDATION;

		if($model->save())
		{
			$cavModel = $model->attributes;
			if($this->cav_is_local_trip == 0)
			{
				$routeRates				 = CabAvailabilities::calculateQuoteRate($cavModel);
				$totamount				 = $routeRates->totalAmount;
				$model->cav_total_amount = $totamount;
			}
			if(($this->cav_is_local_trip == 1 || $totamount > 0) && $model->save())
			{
//				TaxiOTaxi::postSupply($model->cav_id);
				/* $fbPost = new FBPost();
				  $fbPost->flashBookingPost($model->cav_id); */
				return $model;
			}
		}

		$errors = $model->getErrors();
		throw new Exception(CJSON::encode($errors), $errorCode);
	}

	public function remove($data)
	{
		$model				 = CabAvailabilities::model()->findbyPk($data['cav_id']);
		$model->cav_status	 = 0;
		$model->scenario	 = 'updatestatus';
		$success			 = false;
		try
		{
			if($model->validate())
			{
				$success = $model->save();
			}
		}
		catch(Exception $e)
		{
			$success = false;
			$model->addError('cav_id', $e->getMessage());
		}

		return $model;
	}

	public function findByCabId($cav_cab_id)
	{
		$sql = "select * from cab_availabilities where cav_cab_id = $cav_cab_id AND cav_status = 1 AND cav_date_time > NOW() LIMIT 0,1";
		return DBUtil::queryRow($sql);
	}

	public function fetchList($from_city, $to_city, $vnd_id, $create_date1, $create_date2, $type = 'data')
	{
		$sql = "SELECT
				vnd.vnd_name,
				phn.phn_phone_no AS vnd_phone,
				cavCab.vhc_number,
				cavFromCity.cty_name AS from_city,
				cavToCity.cty_name AS to_city,
				drv_name,
				cav.cav_date_time,
				cav.cav_duration,
				concat(vht.vht_make, ' ', vht.vht_model) AS vht_make_model,
				if(cav.cav_date_time > CURDATE(), 1, 0) rank,
				vct_label cab_type
				FROM cab_availabilities cav
				JOIN vehicles cavCab ON (cav.cav_cab_id = cavCab.vhc_id) AND (vhc_active IN (1, 2, 3))
				JOIN vehicle_types vht ON (vht.vht_id = cavCab.vhc_type_id)
				JOIN vcv_cat_vhc_type vcvt ON vcvt.vcv_vht_id = cavCab.vhc_type_id
				JOIN vehicle_category vct ON vct.vct_id = vcvt.vcv_vct_id
				INNER JOIN vendors vnd1 ON (cav.cav_vendor_id = vnd1.vnd_id)
				INNER JOIN vendors vnd ON vnd.vnd_id = vnd1.vnd_ref_code AND (vnd.vnd_active > 0)
				INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id and cp.cr_status =1
				INNER JOIN contact ctt ON ctt.ctt_id = cp.cr_contact_id and ctt.ctt_active =1 and ctt.ctt_id = ctt.ctt_ref_code
				LEFT JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id AND phn.phn_is_primary = 1
				INNER JOIN drivers ON (cav.cav_driver_id = drv_id) AND (drv_active = 1)
				JOIN cities cavFromCity ON (cav.cav_from_city = cavFromCity.cty_id) AND (cavFromCity.cty_active = 1)
				JOIN cities cavToCity ON (cav.cav_to_cities = cavToCity.cty_id) AND (cavToCity.cty_active = 1)
				WHERE 1 AND cav.cav_status = 1 AND vnd.vnd_id = vnd.vnd_ref_code ";

		$sqlCount = "SELECT
					COUNT(DISTINCT cav.cav_id)
					FROM cab_availabilities cav
					JOIN vehicles cavCab ON (cav.cav_cab_id = cavCab.vhc_id) AND (vhc_active IN (1, 2, 3))
					JOIN vehicle_types vht ON (vht.vht_id = cavCab.vhc_type_id)
					JOIN vcv_cat_vhc_type vcvt ON vcvt.vcv_vht_id = cavCab.vhc_type_id
					JOIN vehicle_category vct ON vct.vct_id = vcvt.vcv_vct_id
					INNER JOIN vendors vnd1 ON (cav.cav_vendor_id = vnd1.vnd_id)
					INNER JOIN vendors vnd ON vnd.vnd_id = vnd1.vnd_ref_code AND (vnd.vnd_active > 0)
					INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id and cp.cr_status =1
					INNER JOIN contact ctt ON ctt.ctt_id = cp.cr_contact_id and ctt.ctt_active =1 and ctt.ctt_id = ctt.ctt_ref_code
					LEFT JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id AND phn.phn_is_primary = 1
					INNER JOIN drivers ON (cav.cav_driver_id = drv_id) AND (drv_active = 1)
					JOIN cities cavFromCity ON (cav.cav_from_city = cavFromCity.cty_id) AND (cavFromCity.cty_active = 1)
					JOIN cities cavToCity ON (cav.cav_to_cities = cavToCity.cty_id) AND (cavToCity.cty_active = 1)
					WHERE 1 AND cav.cav_status = 1 AND vnd.vnd_id = vnd.vnd_ref_code ";

		if($create_date1 != '' && $create_date1 != '1970-01-01' && $create_date2 != '' && $create_date2 != '1970-01-01')
		{
			$sql		 .= " AND (cav.cav_date_time BETWEEN '$create_date1 00:00:00' AND '$create_date2 23:59:59') ";
			$sqlCount	 .= " AND (cav.cav_date_time BETWEEN '$create_date1 00:00:00' AND '$create_date2 23:59:59') ";
		}
		else
		{
			$sql		 .= " AND cav.cav_date_time BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)";
			$sqlCount	 .= " AND cav.cav_date_time BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)";
		}
		if($from_city != '')
		{
			$sql		 .= " AND cavFromCity.cty_id=$from_city";
			$sqlCount	 .= " AND cavFromCity.cty_id=$from_city";
		}
		if($to_city != '')
		{
			$sql		 .= " AND cavToCity.cty_id=$to_city";
			$sqlCount	 .= " AND cavToCity.cty_id=$to_city";
		}
		if($vnd_id != '')
		{
			$sql		 .= " AND vnd.vnd_id=$vnd_id";
			$sqlCount	 .= " AND vnd.vnd_id=$vnd_id";
		}

		$sql .= " GROUP BY cav_id";

		if($type == 'data')
		{
			$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['vnd_name', 'vht_make_model', 'cab_type', 'vhc_number', 'from_city', 'to_city', 'drv_name', 'cav_date_time', 'cav_duration'],
					'defaultOrder'	 => 'rank DESC,
                    CASE rank WHEN 1 THEN cav.cav_date_time END ASC,
                    CASE rank WHEN 0 THEN cav.cav_date_time END DESC'],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else if($type == 'command')
		{
			$sql .= " ORDER BY cavFromCity.cty_id DESC";
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
	}

	public function getList($entityId, $entityType, $qryVal = '', $getCount = false, $pageSize = 1, $pageCount = 1)
	{
		$sqlEntityQuery	 = $entityType == 'driver' ? " AND cav_driver_id=$entityId " : " AND cav_vendor_id=$entityId";
		$limit1			 = (($pageCount - 1) * $pageSize);
		$where			 = '';
		$param			 = [];
		if($qryVal != '')
		{
			$search_txt		 = trim($qryVal);
			$tsearch_txt	 = strtolower(str_replace(' ', '', $search_txt));
			$param['qryVal'] = "%$qryVal%";
			$where			 = " AND 
					(REPLACE(LOWER(vhc.vhc_number),' ', '')  LIKE '%$tsearch_txt%'  OR  
					fct.cty_name LIKE :qryVal OR
					tct.cty_name LIKE :qryVal OR
					DATE(cav.cav_date_time)='$tsearch_txt'					 
					)";
		}
		$sql		 = "SELECT cav.*,
					fct.cty_name cav_from_city_name,tct.cty_name cav_to_city_name,
					DATE_ADD(cav_date_time, INTERVAL (if(cav_duration > 0, cav_duration, 3)) HOUR) as  expire,
					vhc_id,vhc_number,vht_make,vht_model,vhc_number
					FROM   cab_availabilities cav
					INNER JOIN vehicles vhc ON vhc.vhc_id = cav.cav_cab_id 	AND vhc_active = 1 AND vhc_is_freeze = 0 AND vhc_approved = 1
					INNER JOIN vehicle_types vht ON vht.vht_id = vhc.vhc_type_id
					INNER JOIN cities fct ON  cav.cav_from_city = fct.cty_id 
					INNER JOIN cities tct ON  cav.cav_to_cities = tct.cty_id 					
					WHERE  cav_status = 1 AND cav_duration > 0 $sqlEntityQuery
					AND NOW() <  cav_date_time  $where
					LIMIT $limit1,$pageSize ";
		$countSql	 = "SELECT count(distinct cav.cav_id) 
					FROM cab_availabilities cav				
					INNER JOIN vehicles vhc ON vhc.vhc_id = cav.cav_cab_id AND vhc_is_freeze = 0 AND vhc_approved = 1
					INNER JOIN cities fct ON  cav.cav_from_city = fct.cty_id 
					INNER JOIN cities tct ON  cav.cav_to_cities = tct.cty_id 
					WHERE  cav_status = 1 AND cav_duration > 0 $sqlEntityQuery  AND NOW() <  cav_date_time $where
			  ";
		if($getCount)
		{
			$result = DBUtil::command($countSql, DBUtil::SDB())->queryScalar($param);
		}
		else
		{
			$result = DBUtil::queryAll($sql, DBUtil::SDB(), $param);
		}

		return $result;
	}

	public static function deactivateByIdnVnd($cav_id, $vndId)
	{
		$qry		 = "select cav.cav_status,cav.cav_vendor_id  from cab_availabilities cav where cav_id = $cav_id LIMIT 0,1";
		$recordset	 = DBUtil::queryRow($qry);
		if(!$recordset)
		{
			throw new Exception("No such availability exists.", ReturnSet::ERROR_INVALID_DATA);
		}
		if($recordset['cav_status'] == 0)
		{
			throw new Exception(json_encode(["Availability is already deleted."]), ReturnSet::ERROR_VALIDATION);
		}

		if($recordset['cav_vendor_id'] != $vndId)
		{
			throw new Exception(json_encode(["Only owner/operator of the availability is allowed to delete the record."]), ReturnSet::ERROR_VALIDATION);
		}
		$param	 = ['cav_id' => $cav_id, 'vndId' => $vndId];
		$sql	 = "UPDATE cab_availabilities
					SET    cav_status = 0
					WHERE  cav_status = 1 AND cav_vendor_id = :vndId AND cav_id = :cav_id ";
		$result	 = DBUtil::command($sql)->execute($param);
		//TaxiOTaxi::deativateAvailablity($cav_id);
		return $result;
	}

	public static function deactivateByIdnDrv($cav_id, $drvId)
	{
		$qry		 = "select cav.cav_status,cav.cav_driver_id  from cab_availabilities cav where cav_id = $cav_id limit 0,1  ";
		$recordset	 = DBUtil::queryRow($qry);
		if(!isset($recordset))
		{
			throw new Exception("No such availability exists.", ReturnSet::ERROR_INVALID_DATA);
		}
		if($recordset['cav_status'] == 0)
		{
			throw new Exception("Availability is already deleted.", ReturnSet::ERROR_VALIDATION);
		}
		if($recordset['cav_driver_id'] != $drvId)
		{
			throw new Exception("Only owner/operator of the availability is allowed to delete the record.", ReturnSet::ERROR_VALIDATION);
		}

		$param	 = ['cav_id' => $cav_id, 'drvId' => $drvId];
		$sql	 = "UPDATE cab_availabilities
					SET    cav_status = 0
					WHERE  cav_status = 1 AND cav_driver_id = :drvId AND cav_id = :cav_id ";
		$result	 = DBUtil::command($sql)->execute($param);
		TaxiOTaxi::deativateAvailablity($cav_id);
		return $result;
	}

	public static function deactivateById($cav_id)
	{
		$param	 = ['cav_id' => $cav_id];
		$sql	 = "UPDATE cab_availabilities
					SET    cav_status = 0
					WHERE  cav_status = 1 AND  cav_id = :cav_id ";
		$result	 = DBUtil::command($sql)->execute($param);
		TaxiOTaxi::deativateAvailablity($cav_id);
		return $result;
	}

	public static function checkPostingOwner($cav_id, $vndId)
	{
		$param	 = ['cav_id' => $cav_id, 'vndId' => $vndId];
		$sql	 = "SELECT cav_id from cab_availabilities					 
					WHERE   cav_vendor_id = :vndId AND cav_id = :cav_id  LIMIT 0,1";
		$result	 = DBUtil::command($sql)->queryScalar($param);
	}

	public static function findOverLappingTimeSlot($cab_id, $startDate, $duration = 0)
	{
		$param	 = ['cab_id' => $cab_id];
		$sql	 = " 
	SELECT cav.cav_id, cav.cav_cab_id, cav.cav_duration,
	cav.cav_date_time startDate,
	DATE_ADD(cav_date_time, INTERVAL (if(cav_duration > 0, cav_duration, 3)) HOUR) expireTime, 
	'$startDate' proposedStartDate, 
	DATE_ADD('$startDate', INTERVAL (if('$duration' > 0, '$duration', 3)) HOUR) proposedExpireTime
	FROM   cab_availabilities cav
	WHERE  cav_date_time > now() AND cav_status = 1 AND cav_cab_id = :cab_id 
	HAVING ((proposedStartDate <= startDate AND startDate < proposedExpireTime)
        OR (proposedStartDate < expireTime AND expireTime <= proposedExpireTime)
        OR (proposedStartDate <= startDate AND expireTime <= proposedExpireTime)
        OR (startDate <= proposedStartDate AND proposedExpireTime <= expireTime))";
		$result	 = DBUtil::queryAll($sql, DBUtil::SDB(), $param);
		return $result;
	}

	public static function getDetails($cav_id)
	{
		$param	 = ['cavid' => $cav_id];
		$sql	 = "SELECT  
						cav_id,cav_from_city,cav_to_cities,cav_date_time,cav_amount,cav_total_amount,
						DATE_ADD(cav_date_time,INTERVAL(IF(cav_duration > 0,cav_duration,3)) HOUR) cav_expire,
                        vhc.vhc_number,vht_make,vht_model,vct_id cabType,vnd.vnd_name,
						ctp.phn_phone_no vnd_phone,
						fct.cty_name cav_from_city_name,
						tct.cty_name cav_to_city_name,cav_cab_id,
						cav_is_oneway, cav_is_shared,cav_is_local_trip
					FROM cab_availabilities cav
					INNER JOIN vehicles vhc ON	vhc.vhc_id = cav.cav_cab_id AND vhc_is_freeze = 0 AND vhc_approved = 1
					INNER JOIN vehicle_types vht ON	vht.vht_id = vhc.vhc_type_id
					INNER JOIN vcv_cat_vhc_type ON vcv_cat_vhc_type.vcv_vht_id = vht.vht_id
					INNER JOIN vehicle_category ON vct_id = vcv_vct_id
					INNER JOIN vendors vnd ON cav.cav_vendor_id = vnd.vnd_id AND vnd.vnd_active > 0
                    INNER JOIN vendors vnd1 ON vnd.vnd_id = vnd1.vnd_ref_code
                    INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = vnd1.vnd_id
					LEFT JOIN contact_phone ctp ON	ctp.phn_contact_id = cpr.cr_contact_id AND ctp.phn_is_primary = 1
					JOIN cities fct ON cav.cav_from_city = fct.cty_id
					JOIN cities tct ON cav.cav_to_cities = tct.cty_id
					WHERE cav_date_time > NOW() AND	cav_status = 1 AND cav_id = :cavid LIMIT 0,1";
		$result	 = DBUtil::queryRow($sql, null, $param);
		return $result;
	}

	public static function getData($cav_id)
	{
		$param	 = ['cavid' => $cav_id];
		$sql	 = "SELECT cav_id,cav_from_city,cav_to_cities,cav_date_time,cav_amount,cav_total_amount,
				DATE_ADD(cav_date_time,INTERVAL(IF(cav_duration > 0,cav_duration,3)) HOUR) cav_expire,
				cav_vendor_id, cav_driver_id,cav_cab_id, phn.phn_full_number
			FROM cab_availabilities cav	
			INNER JOIN drivers d1 ON d1.drv_id = cav.cav_driver_id
			INNER JOIN drivers d2 ON d2.drv_id = d1.drv_ref_code AND d2.drv_active = 1
			INNER join contact_profile as cp on cp.cr_is_driver = d2.drv_id and cp.cr_status =1
			INNER JOIN contact as ctt on ctt.ctt_id = cp.cr_contact_id
			INNER JOIN contact as ctt2 on ctt2.ctt_id = ctt.ctt_ref_code and ctt2.ctt_active = 1
			LEFT JOIN contact_phone phn ON phn.phn_contact_id=ctt.ctt_id 
				AND phn.phn_is_primary=1 
				AND phn.phn_active=1
			WHERE cav_id = :cavid LIMIT 0,1 ";
		$result	 = DBUtil::queryRow($sql, null, $param);
		return $result;
	}

	public static function getQuoteRate($cav_id, $partner = '')
	{
		$cavModel	 = CabAvailabilities::getDetails($cav_id);
		$routeRates	 = CabAvailabilities::calculateQuoteRate($cavModel, $partner);
		return $routeRates;
	}

	public static function calculateQuoteRate($cavModel, $partner = '', $actualQuote = false)
	{
		$cabArr			 = Vehicles::getDetailbyid($cavModel['cav_cab_id']);
		$fromCity		 = $cavModel['cav_from_city'];
		$toCity			 = $cavModel['cav_to_cities'];
		$vendorAmount	 = $cavModel['cav_amount'];
		$cabType		 = $cabArr['vht_car_type'];
		if($actualQuote)
		{
			$vendorAmount = CabAvailabilities::calculateRate($cavModel);
		}
		$pickDate						 = $cavModel['cav_date_time'];
		$brtModel						 = new BookingRoute();
		$brtModel->brt_from_city_id		 = $fromCity;
		$brtModel->brt_to_city_id		 = $toCity;
		$brtModel->brt_pickup_datetime	 = $pickDate;
		$routes[]						 = $brtModel;

		$partnerId							 = ($partner > 0) ? $partner : Yii::app()->params['gozoChannelPartnerId'];
		$qt									 = new Quote();
		$qt->routes							 = $routes;
		$qt->cabType						 = $cabType;
		$qt->pickupDate						 = $pickDate;
		$qt->partnerId						 = $partnerId;
		$qt->routeRates						 = new RouteRates();
		$qt->routeDuration					 = new RouteDuration();
		$qt->routeRates->vendorAmount		 = $vendorAmount;
		$qt->routeRates->tollTaxAmount		 = 0;
		$qt->routeRates->stateTax			 = 0;
		$qt->routeRates->isTollIncluded		 = 1;
		$qt->routeRates->isStateTaxIncluded	 = 1;
		$qt->servingRoute['start']			 = $fromCity;
		$qt->servingRoute['end']			 = $toCity;
		$qt->toCities						 = [$toCity];

		$qt->routeRates->ignoreDayDriverAllowance = true;
		if(!$actualQuote)
		{
			$qt->suggestedPrice = 1;
		}
		$qt->routeRates->calculate($qt);
		if(!$actualQuote)
		{
			$qt->routeRates->baseAmount = round($qt->routeRates->rockBottomAmount * 1.01);
		}
		$qt->routeRates->discount = 0;
		$qt->routeRates->calculateTotal();

		return $qt->routeRates;
	}

	public static function calculateRate($cavModel, $partner = '')
	{
		$cabArr		 = Vehicles::getDetailbyid($cavModel['cav_cab_id']);
		$fromCity	 = $cavModel['cav_from_city'];
		$toCity		 = $cavModel['cav_to_cities'];
		$cabType	 = $cabArr['vht_car_type'];
		$pickDate	 = $cavModel['cav_date_time'];
		$res		 = Route::model()->getRouteRates($fromCity, $toCity, $cabType, true);
		if($res)
		{
			$routeVendorAmount = $res['rte_vendor_amount'];
		}
		else
		{
			$brtModel						 = new BookingRoute();
			$brtModel->brt_from_city_id		 = $fromCity;
			$brtModel->brt_to_city_id		 = $toCity;
			$brtModel->brt_pickup_datetime	 = $pickDate;
			$routes[]						 = $brtModel;

			$partnerId			 = ($partner > 0) ? $partner : Yii::app()->params['gozoChannelPartnerId'];
			$qt					 = new Quote();
			$qt->routes			 = $routes;
			$qt->partnerId		 = $partnerId;
			$qt->sourceCity		 = $fromCity;
			$qt->destinationCity = $toCity;
			$qt->routeRates		 = new RouteRates();
			$qt->routeDuration	 = new RouteDuration();
			$qt->routeDistance	 = new RouteDistance();
			$qt->suggestedPrice	 = 1;
			$qt->tripType		 = 1;
			$qt->cabType		 = $cabType;
			$qt->calculateRules();
			$routeVendorAmount	 = $qt->routeRates->vendorAmount;
		}
		return $routeVendorAmount;
	}

	public static function compareRates($cavModel)
	{
		$routeVendorAmount = CabAvailabilities::calculateRate($cavModel);

		$expectedVndAmount	 = $routeVendorAmount * 0.9;
		$isOverRated		 = ($cavModel['cav_amount'] > $expectedVndAmount) ? true : false;
		return $isOverRated;
	}

	public static function updateTotalAmount()
	{
		$sql = "SELECT cav_id,cav_total_amount from cab_availabilities cav WHERE  cav_date_time > now() AND cav_status = 1 AND cav_total_amount IS NULL";

		$result = DBUtil::queryAll($sql);
		foreach($result as $value)
		{
			$cav_id						 = $value['cav_id'];
			$cavModel					 = CabAvailabilities::model()->findByPk($cav_id);
			$cavModelAttr				 = $cavModel->attributes;
			$routeRates					 = CabAvailabilities::calculateQuoteRate($cavModelAttr);
			$cavModel->cav_total_amount	 = $routeRates->totalAmount;
			$cavModel->save();
		}
	}

	public function fetchFlashSale($qry = [])
	{
		$sourceCity		 = (isset($qry['cav_from_city'])) ? $qry['cav_from_city'] : "";
		$destinationCity = (isset($qry['cav_to_cities'])) ? $qry['cav_to_cities'] : "";
		$journeyDate	 = (isset($qry['cav_date_time'])) ? $qry['cav_date_time'] : "";
		$fuelList		 = VehicleTypes::model()->getFuelType();
		$fuelCase		 = "(CASE vht.vht_fuel_type";
		foreach($fuelList as $key => $value)
		{
			$fuelCase .= " WHEN $key THEN '$value'";
		}
		$fuelCase	 .= " END )";
		$cngCase	 = "(CASE v.vhc_has_cng WHEN 1  THEN ' +CNG' ELSE ''  END)";
		$sql		 = "SELECT cav.cav_total_amount Amount,cav.cav_id cavid,cav_cab_id,
			cav_from_city,cav_to_cities,cav_date_time,
			s.cty_name sourceCity,d.cty_name destinationCity,
			concat(vht.vht_make,' ',vht.vht_model,' (',vct_label,')') cabModel,cav_date_time start,
			DATE_ADD(cav_date_time, INTERVAL (if(cav_duration > 0, cav_duration, 3)) HOUR) expiry,
			cav.cav_duration duration,if(vht.vht_fuel_type IS NOT NULL,concat($fuelCase,$cngCase ),'') fuelType, if(v.vhc_has_cng = 1,'+ CNG','') is_cng  
			from cab_availabilities cav
			INNER JOIN vendors vnd ON cav.cav_vendor_id = vnd.vnd_id AND vnd.vnd_active > 0
			INNER join cities s on cav.cav_from_city = s.cty_id 
			INNER join cities d on cav.cav_to_cities = d.cty_id 
			INNER join vehicles v on  cav.cav_cab_id = v.vhc_id	AND vhc_is_freeze = 0 AND vhc_approved = 1
			INNER JOIN vehicle_types vht ON vht.vht_id = v.vhc_type_id
			INNER JOIN vcv_cat_vhc_type ON vcv_cat_vhc_type.vcv_vht_id = vht.vht_id
			INNER JOIN vehicle_category ON vct_id = vcv_vct_id
			LEFT JOIN route rut ON rut.rut_from_city_id = cav.cav_from_city AND rut.rut_to_city_id = cav.cav_to_cities AND rut.rut_active = 1
			LEFT JOIN rate ON rut.rut_id = rate.rte_route_id AND rte_status=1 AND rate.rte_vehicletype_id= vct_id
			WHERE cav_status = 1 AND cav_duration > 0 AND cav_is_local_trip = 0
			AND  cav_date_time > NOW() GROUP BY cav.cav_id
         ";
		if($sourceCity != "")
		{
			$sql .= " AND cav.cav_from_city = '$sourceCity'";
		}
		if($destinationCity != "")
		{
			$sql .= " AND cav.cav_to_cities = '$destinationCity'";
		}
		if($journeyDate != "")
		{
			$sql .= " AND cav.cav_date_time between  '$journeyDate 00:00:00' AND  '$journeyDate 23:59:59'";
		}

		$result = DBUtil::queryAll($sql);
		return $result;
	}

	public static function getRouteRates($cavId)
	{
		$param	 = ['cavid' => $cavId];
		$sql	 = "SELECT DISTINCT route.rut_id, route.rut_from_city_id start, route.rut_to_city_id end, NULL AS fromAlias,
                NULL AS toAlias, rut_name, route.rut_estm_distance AS quotedDistance, route.rut_estm_distance AS rateDistance,
                0 AS extraDistance, 0 AS extraStartDistance, 1 AS rank, 0 AS startTime,
                0 AS endTime, route.rut_estm_time, vct_id rte_vehicletype_id, 0 rte_toll_tax,
                0 rte_state_tax, cav_amount rte_vendor_amount
			FROM   route
				   INNER JOIN cab_availabilities cav ON route.rut_from_city_id = cav.cav_from_city AND route.rut_to_city_id = cav.cav_to_cities
				   INNER JOIN vehicles vhc ON vhc.vhc_id = cav.cav_cab_id AND vhc_is_freeze = 0 AND vhc_approved = 1
				   INNER JOIN vehicle_types vht ON vht.vht_id = vhc.vhc_type_id
				   INNER JOIN vcv_cat_vhc_type ON vcv_cat_vhc_type.vcv_vht_id = vht.vht_id
				   INNER JOIN vehicle_category ON vct_id = vcv_vct_id
			WHERE  rut_active = 1 AND cav_status = 1 AND cav_id = :cavid LIMIT 0,1";
		$result	 = DBUtil::queryRow($sql, null, $param);
		return $result;
	}

	public static function getUnconfirmedCavBookings()
	{
		$sql		 = "SELECT   bkg.bkg_id, cav.cav_id, bkg_status, cav.cav_date_time, bkg.bkg_create_date,     
			pgt.apg_status ,if(pgt.apg_status IS NULL,if(DATE_ADD(bkg_create_date, INTERVAL 30 MINUTE) < NOW(), 0, 1),if(pgt.apg_status IN (0,2) AND  DATE_ADD(bkg_create_date, INTERVAL 1 HOUR) < NOW(), 0, 1)) paymentStatus
			FROM     cab_availabilities cav
				INNER JOIN booking bkg ON bkg.bkg_cav_id = cav.cav_id AND bkg_status IN (1,15) AND bkg.bkg_cav_id > 0   
				INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id AND bpr.bpr_is_flash = 1
				LEFT JOIN payment_gateway pgt ON pgt.apg_booking_id = bkg.bkg_id
			WHERE    cav.cav_status = 0 AND cav.cav_date_time > NOW() AND (pgt.apg_status IS NULL OR pgt.apg_status <> 1)  
			ORDER BY bkg.bkg_create_date DESC";
		$result		 = DBUtil::queryAll($sql, DBUtil::SDB());
		$reasonText	 = "Booking cancelled as payment was not recieved from the customer in required time.";
		foreach($result as $row)
		{
			$bkgid	 = $row['bkg_id'];
			$cav_id	 = $row['cav_id'];
			$success = Booking::model()->canBooking($bkgid, $reasonText, 18);
			if($success)
			{
				CabAvailabilities::reactivateRecord($cav_id);
			}
		}
	}

	public static function reactivateRecord($cav_id)
	{
		$param	 = ['cav_id' => $cav_id];
		$sql	 = "UPDATE cab_availabilities
					SET    cav_status = 1
					WHERE  cav_status = 0 AND  cav_id = :cav_id ";
		$result	 = DBUtil::command($sql)->execute($param);
		return $result;
	}

	public static function getIdfromBkgId($bkgId)
	{
		$params	 = ['bkg_id' => $bkgId];
		$sql	 = "SELECT  
						bkg_cav_id 
					FROM booking bkg					 
					WHERE bkg_id = :bkg_id LIMIT 0,1 ";
		$result	 = DBUtil::command($sql)->queryScalar($params);
		return $result;
	}

	public static function processBooking($bkgId)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$cavId	 = CabAvailabilities::getIdfromBkgId($bkgId);
		$return	 = false;
		if($cavId > 0)
		{
			$cavModel = CabAvailabilities::model()->findByPk($cavId);
			if($cavModel->cav_status == 0)
			{
				$return = false;
			}
			$bkgModel = Booking::model()->findByPk($bkgId);
			if($bkgModel->bkg_status == 2 && ($bkgModel->bkgInvoice->bkg_advance_amount > 0 || $bkgModel->bkgPref->bkg_is_confirm_cash == 1) && $bkgModel->bkgPref->bpr_is_flash == 1)
			{
				$cavId			 = $bkgModel->bkg_cav_id;
				$cavData		 = CabAvailabilities::getData($cavId);
				$vendorId		 = $cavData['cav_vendor_id'];
				$vendorAmount	 = $cavData['cav_amount'];
				$remark			 = "Vendor assigned from Cab Availabilty";
				$autoAssignment	 = 1;
				$res			 = $bkgModel->bkgBcb->assignVendor($bkgModel->bkg_bcb_id, $vendorId, $vendorAmount, $remark, UserInfo::getInstance(), $autoAssignment);
				if($res->isSuccess())
				{
					$bkgModel->refresh();
					$cabId		 = $cavData['cav_cab_id'];
					$driverId	 = $cavData['cav_driver_id'];

					$bcabModel					 = $bkgModel->getBkgCabModel();
					$bcabModel->bcb_driver_id	 = $driverId;
					$bcabModel->bcb_cab_id		 = $cabId;
					$bcabModel->bcb_driver_phone = $cavData['phn_full_number'];
					$bcabModel->bcb_id;

					$cab_type	 = $bkgModel->bkgSvcClassVhcCat->scv_vct_id;
					$assigned	 = $bcabModel->assigncabdriver($bcabModel->bcb_cab_id, $bcabModel->bcb_driver_id, $cab_type, UserInfo::getInstance());
					$return		 = $assigned;
					CabAvailabilities::deactivateById($bkgModel->bkg_cav_id);
				}

				################# Notification to vendor regardiing trip confirmed Start for Flash sale booking #####################################
				//BookingCab::gnowWinBidNotify($bkgModel->bkg_bcb_id);
				###################Notification to vendor regardiing trip confirmed END Flash sale booking #######################################
			}
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $return;
	}

	/**
	 * 
	 * @param type $model
	 * @return result
	 */
	public function fetchFlashSaleBooking($model)
	{
		$fromCity	 = $model->bkg_from_city_id;
		$toCity		 = $model->bkg_to_city_id;
		$pickupDate	 = $model->bkg_pickup_date;

		$fuelList	 = VehicleTypes::model()->getFuelType();
		$fuelCase	 = "(CASE vht.vht_fuel_type";

		foreach($fuelList as $key => $value)
		{
			$fuelCase .= " WHEN $key THEN '$value'";
		}

		$fuelCase	 .= " END )";
		$cngCase	 = " (CASE v.vhc_has_cng WHEN 1  THEN ' +CNG' ELSE '' END) ";

		$sql = "SELECT cav.cav_total_amount Amount,cav.cav_id cavid,cav_cab_id,
                cav_from_city, cav_to_cities, cav_date_time, s.cty_name sourceCity, d.cty_name destinationCity,
                concat(vht.vht_make,' ',vht.vht_model,' (',vct_label,')') cabModel,cav_date_time start,
                DATE_ADD(cav_date_time, INTERVAL (if(cav_duration > 0, cav_duration, 3)) HOUR) expiry,
                cav.cav_duration duration,if(vht.vht_fuel_type IS NOT NULL,concat($fuelCase,$cngCase ),'') fuelType, if(v.vhc_has_cng = 1,'+ CNG','') is_cng  
                FROM cab_availabilities cav
                INNER join cities s on cav.cav_from_city = s.cty_id 
                INNER join cities d on cav.cav_to_cities = d.cty_id 
                INNER join vehicles v on  cav.cav_cab_id = v.vhc_id	AND vhc_is_freeze = 0 AND vhc_approved = 1
                INNER JOIN vehicle_types vht ON vht.vht_id = v.vhc_type_id
                INNER JOIN vcv_cat_vhc_type ON vcv_cat_vhc_type.vcv_vht_id = vht.vht_id
                INNER JOIN vehicle_category ON vct_id = vcv_vct_id
                LEFT JOIN route rut ON rut.rut_from_city_id = cav.cav_from_city AND rut.rut_to_city_id = cav.cav_to_cities AND rut.rut_active = 1
                LEFT JOIN rate ON rut.rut_id = rate.rte_route_id AND rte_status=1 AND rate.rte_vehicletype_id= vct_id
                WHERE cav_status = 1 AND cav_duration > 0 AND cav_is_local_trip = 0 AND cav_is_oneway = 1
                AND cav.cav_from_city = '{$fromCity}' AND cav.cav_to_cities = '{$toCity}' AND cav_date_time >= DATE_ADD(NOW(), INTERVAL 45 MINUTE) 
				AND ('{$pickupDate}' BETWEEN cav_date_time AND DATE_ADD(cav_date_time, INTERVAL (if(cav_duration > 0, cav_duration, 3)) HOUR)) 
                GROUP BY cav.cav_id";
                // AND (cav_date_time >= DATE_SUB('{$pickupDate}', INTERVAL 4 HOUR) AND cav_date_time <= DATE_ADD('{$pickupDate}', INTERVAL 4 HOUR)) ";
 // echo $sql;exit;
		$result = DBUtil::query($sql);
		return $result;
	}

	public static function getDetailById($cavId)
	{
		$sql	 = "SELECT
		cav.*,vnd.vnd_id,drv.drv_id,vhc.vhc_id, vnd.vnd_name,
		drv.drv_name,drv.drv_approved,vhc.vhc_number,vhc.vhc_year,
		fct.cty_name cav_from_city_name,
		tct.cty_name cav_to_city_name,
		vht_make,vht_model,vct_id,vct_label
		FROM cab_availabilities cav
			JOIN vendors vnd1 ON vnd1.vnd_id = cav.cav_vendor_id
			INNER JOIN vendors vnd ON vnd.vnd_id = vnd1.vnd_ref_code	
			JOIN drivers drv ON drv.drv_ref_code=cav.cav_driver_id AND drv.drv_id = drv.drv_ref_code
			JOIN vehicles vhc ON vhc.vhc_id = cav.cav_cab_id
			JOIN cities fct ON fct.cty_id = cav.cav_from_city
			JOIN cities tct ON tct.cty_id = cav.cav_to_cities
			INNER JOIN vehicle_types vht ON vht.vht_id = vhc.vhc_type_id
			INNER JOIN vcv_cat_vhc_type ON vcv_cat_vhc_type.vcv_vht_id = vht.vht_id
			INNER JOIN vehicle_category ON vct_id = vcv_vct_id
			WHERE cav.cav_id= :cavid
			GROUP BY cav.cav_id";
		$param	 = ['cavid' => $cavId];
		$result	 = DBUtil::queryRow($sql, null, $param);
		return $result;
	}

	public static function getListByEntity($entId, $entType, $filter = [])
	{
		$where	 = " AND 1=0";
		$param	 = [];
		if($entType == UserInfo::TYPE_VENDOR)
		{
			$where			 = " AND cav_vendor_id=:vndId";
			$param['vndId']	 = $entId;
		}
		if($entType == UserInfo::TYPE_DRIVER)
		{
			$where			 = " AND cav_driver_id=:drvId";
			$param['drvId']	 = $entId;
		}
		if(sizeof($filter) > 0)
		{
			if(isset($filter['pickupDateRange']))
			{
				$fromDate	 = date('Y-m-d 00:00:00', strtotime($filter['pickupDateRange']->fromDate));
				$toDate		 = date('Y-m-d 23:59:59', strtotime($filter['pickupDateRange']->toDate));
				$where		 .= " AND (cav.cav_date_time between '$fromDate' AND '$toDate')";
			}
			if(isset($filter['city']) && $filter['city']->pickupCity > 0)
			{
				$fromDate			 = $filter['city']->pickupCity;
				$where				 .= " AND cav.cav_from_city = :fromCity";
				$param['fromCity']	 = $filter['city']->pickupCity;
			}
			if(isset($filter['city']) && $filter['city']->dropCity > 0)
			{
				$toDate			 = $filter['city']->dropCity;
				$where			 .= " AND cav.cav_to_cities = :toCity";
				$param['toCity'] = $filter['city']->dropCity;
			}
		}
		$sql = "SELECT cav.*,
		vnd.vnd_id, vnd.vnd_name,
		drv.drv_id, drv.drv_name,drv.drv_approved,
		vhc.vhc_id, vhc.vhc_number, vhc.vhc_year,
		fct.cty_name cav_from_city_name, tct.cty_name cav_to_city_name,
		vht_make,vht_model,
		vct_id,vct_label
		FROM cab_availabilities cav
			INNER JOIN vendors vnd1 
				ON vnd1.vnd_id = cav.cav_vendor_id
			INNER JOIN vendors vnd 
				ON vnd.vnd_id = vnd1.vnd_ref_code	
			INNER JOIN drivers drv 
				ON drv.drv_ref_code=cav.cav_driver_id 
				AND drv.drv_id = drv.drv_ref_code
			INNER JOIN vehicles vhc 
				ON vhc.vhc_id = cav.cav_cab_id
			INNER JOIN cities fct 
				ON fct.cty_id = cav.cav_from_city
			INNER JOIN cities tct 
				ON tct.cty_id = cav.cav_to_cities
			INNER JOIN vehicle_types vht 
				ON vht.vht_id = vhc.vhc_type_id
			INNER JOIN vcv_cat_vhc_type 
				ON vcv_cat_vhc_type.vcv_vht_id = vht.vht_id
			INNER JOIN vehicle_category 
				ON vct_id = vcv_vct_id
			WHERE cav.cav_date_time > NOW() 
				AND cav_status = 1 $where  
			GROUP BY cav.cav_id";

		$result = DBUtil::query($sql, DBUtil::SDB(), $param);
		return $result;
	}
}
