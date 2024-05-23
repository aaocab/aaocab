<?php

/**
 * This is the model class for table "vehicle_stats".
 *
 * The followings are the available columns in table 'vehicle_stats':
 * @property integer $vhs_id
 * @property integer $vhs_vhc_id
 * @property integer $vhs_doc_score
 * @property integer $vhs_total_trips
 * @property string  $vhs_last_trip_date
 * @property string $vhs_modified_date
 * @property integer $vhs_is_partition
 * @property integer $vhs_active
 * @property integer $vhs_last_odometer_reading
 * @property string  $vhs_last_odometer_reading_location
 * @property string  $vhs_last_odometer_reading_date
 * @property integer $vhs_OW_Count
 * @property integer $vhs_RT_Count
 * @property integer $vhs_AT_Count
 * @property integer $vhs_PT_Count
 * @property integer $vhs_FL_Count
 * @property integer $vhs_SH_Count
 * @property integer $vhs_CT_Count
 * @property integer $vhs_DR_4HR_Count
 * @property integer $vhs_DR_8HR_Count
 * @property integer $vhs_DR_12HR_Count
 * @property integer $vhs_AP_Count
 * @property integer $vhs_last_completed_bkg_id
 * @property string  $vhs_last_completed_latlong
 * @property string  $vhs_last_completed_date
 * 
 *  The followings are the available model relations:
 * @property vehicles $vhsVhc
 */
class VehicleStats extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vehicle_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vhs_vhc_id', 'required'),
			array('vhs_vhc_id, vhs_doc_score, vhs_is_partition, vhs_active,vhs_OW_Count,vhs_RT_Count,vhs_AT_Count,vhs_PT_Count,vhs_FL_Count,vhs_SH_Count,vhs_CT_Count,vhs_DR_4HR_Count,vhs_DR_8HR_Count,vhs_DR_12HR_Count,vhs_AP_Count', 'numerical', 'integerOnly' => true),
			array('vhs_vhc_id, vhs_active', 'required', 'on' => 'updateStats'),
			array('vhs_vhc_id, vhs_boost_enabled', 'required', 'on' => 'updateBoost'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vhs_id, vhs_vhc_id, vhs_doc_score, vhs_total_trips, vhs_last_trip_date, vhs_modified_date, vhs_active,vhs_last_odometer_reading,vhs_last_odometer_reading_location,vhs_last_odometer_reading_date,vhs_OW_Count,vhs_RT_Count,vhs_AT_Count,vhs_PT_Count,vhs_FL_Count,vhs_SH_Count,vhs_CT_Count,vhs_DR_4HR_Count,vhs_DR_8HR_Count,vhs_DR_12HR_Count,vhs_AP_Count,vhs_last_completed_bkg_id,vhs_last_completed_latlong,vhs_last_completed_date', 'safe', 'on' => 'search'),
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
			'vhsVhc' => array(self::BELONGS_TO, 'Vehicles', 'vhs_vhc_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vhs_id'			 => 'Vhs',
			'vhs_vhc_id'		 => 'Vhs Vhc',
			'vhs_doc_score'		 => 'Vhs Doc Score',
			'vhs_total_trips'	 => 'Vhs Total Trips',
			'vhs_last_trip_date' => 'Vhs Last Trip Date',
			'vhs_modified_date'	 => 'Vhs Modified Date',
			'vhs_is_partition'	 => 'Vhs Is Partition',
			'vhs_active'		 => 'Vhs Active',
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

		$criteria->compare('vhs_id', $this->vhs_id);
		$criteria->compare('vhs_vhc_id', $this->vhs_vhc_id);
		$criteria->compare('vhs_doc_score', $this->vhs_doc_score);
		$criteria->compare('vhs_total_trips', $this->vhs_total_trips);
		$criteria->compare('vhs_last_trip_date', $this->vhs_last_trip_date);
		$criteria->compare('vhs_modified_date', $this->vhs_modified_date);
		$criteria->compare('vhs_is_partition', $this->vhs_is_partition);
		$criteria->compare('vhs_active', $this->vhs_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VehicleStats the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getbyVehicleID($vehicleId)
	{

		$criteria	 = new CDbCriteria;
		$criteria->compare('vhs_vhc_id', $vehicleId);
		$model		 = $this->find($criteria);
		if ($model)
		{
			return $model;
		}
		else
		{
			return false;
		}
	}

	public function insertEmptyStats($type = 0, $vhc_id = 0)
	{
		// 0 : count total trips and last trip for car 
		switch ($type)
		{
			case 0:
				$where	 = $vhc_id > 0 ? " and  bcb_cab_id=$vhc_id " : "";
				$sql	 = "SELECT
                                            booking_cab.bcb_cab_id
                                    FROM
                                            `booking_cab`
                                    INNER   JOIN `booking` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking.bkg_active = 1 AND booking.bkg_status IN (5, 6, 7)
                                    LEFT    JOIN `vehicle_stats` ON vehicle_stats.vhs_vhc_id=booking_cab.bcb_cab_id
                                    WHERE
                                            booking.bkg_pickup_date>= '2015-10-25 00:00:00'
                                            AND booking_cab.bcb_active = 1 
                                            AND booking_cab.bcb_cab_id IS NOT NULL  $where
                                            AND vehicle_stats.vhs_id IS NULL
                                    GROUP BY  booking_cab.bcb_cab_id";
				break;
		}
		$rows	 = DBUtil::queryAll($sql);
		$count	 = count($rows);
		if ($count > 0)
		{
			foreach ($rows as $row)
			{
				$model				 = new VehicleStats();
				$model->scenario	 = 'updateStats';
				$model->vhs_vhc_id	 = $row['bcb_cab_id'];
				$model->vhs_active	 = 1;
				$model->save();
			}
		}
		return $count;
	}

	public function getUnapprovedDocByVndId($vndId)
	{

		$queryDrv = "SELECT DISTINCT vendor_driver.vdrv_drv_id as drv_ids
				FROM `vendor_driver` 
				LEFT JOIN `drivers` ON drv_id=vendor_driver.vdrv_drv_id
				WHERE	vendor_driver.vdrv_vnd_id = '$vndId' AND vendor_driver.vdrv_active > 0 AND drv_active>0";

		$recordSet1 = DBUtil::command($queryDrv)->queryAll();
		foreach ($recordSet1 as $rec)
		{
			$mapDriverIds[] = $rec['drv_ids'];
		}

		$queryVhc	 = "SELECT DISTINCT vendor_vehicle.vvhc_vhc_id as vhc_ids
				       FROM `vendor_vehicle` 
				    LEFT JOIN `vehicles` ON vehicles.vhc_id=vendor_vehicle.vvhc_vhc_id	
				       WHERE vendor_vehicle.vvhc_vnd_id = '$vndId' AND vendor_vehicle.vvhc_active > 0 AND vehicles.vhc_active > 0";
		$recordSet2	 = DBUtil::command($queryVhc)->queryAll();
		foreach ($recordSet2 as $rec)
		{
			$mapVhcIds[] = $rec['vhc_ids'];
		}

		$sql		 = "SELECT
		     vhc_id,
		    IFNULL(d.drv_id,drivers.drv_id) AS drv_id,
		    IFNULL(d.drv_contact_id,drivers.drv_contact_id) AS drv_contact_id,
		    vehicles.vhc_approved,
		    IFNULL(d.drv_approved,drivers.drv_approved) AS drv_approved,
		    vehicles.vhc_number,
		    vehicle_types.vht_model,
		    vcvt.vcv_vct_id AS vht_car_type,
		    IFNULL(d.drv_name,drivers.drv_name) AS drv_name,
		    contact_phone.phn_phone_no AS drv_phone,
		    vhc_insurance_exp_date,
		    vhc_reg_exp_date,
		    contact.ctt_license_exp_date,
		    vcvt.vcv_vct_id AS car_type
		    
		FROM   `booking_cab`
		INNER JOIN `booking` ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking.bkg_status IN (5, 6, 7) AND booking.bkg_active = 1 AND booking_cab.bcb_active = 1
		LEFT JOIN `vehicles` ON vehicles.vhc_id = booking_cab.bcb_cab_id AND vehicles.vhc_active > 0 AND vehicles.vhc_approved IN (0,2)
		LEFT JOIN `vehicle_types` ON vehicle_types.vht_id = vehicles.vhc_type_id 
		INNER JOIN vcv_cat_vhc_type vcvt ON vcvt.vcv_vht_id = vehicle_types.vht_id	
		LEFT JOIN `drivers`  ON drv_id = booking_cab.bcb_driver_id AND drv_active > 0
		LEFT JOIN drivers AS d ON d.drv_id = drivers.drv_ref_code AND d.drv_active>0
		LEFT JOIN contact_profile AS cp ON cp.cr_is_driver = d.drv_id AND cp.cr_status =1 
		LEFT JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_active =1 
        LEFT JOIN `contact_phone` ON contact.ctt_id = contact_phone.phn_contact_id AND contact_phone.phn_is_primary = 1 AND contact_phone.phn_active = 1
		LEFT JOIN contact_email ON contact_email.eml_contact_id = contact.ctt_id AND contact_email.eml_is_primary=1
        WHERE (vhc_id IS NOT NULL OR d.drv_id IS NOT NULL) AND booking_cab.bcb_vendor_id='$vndId'
		GROUP BY booking_cab.bcb_id 
		ORDER BY booking.bkg_pickup_date DESC 
		LIMIT 0,20";
		$rows		 = DBUtil::queryAll($sql);
		$arr		 = [];
		$result		 = [];
		$driverDocs	 = null;
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				if ($row['drv_id'] != null && !array_key_exists("2_{$row['drv_id']}", $arr))
				{
					$driverDocs	 = Document::model()->getUnapprovedDoc($row['drv_id'], $row['drv_contact_id']);
					$isDriver	 = in_array($row['drv_id'], $mapDriverIds);
					if ($driverDocs['count'] > 0 && $isDriver == true)
					{
						$arr["2_{$row['drv_id']}"] = ['entity_type' => '2', 'entity_id' => $row['drv_id'], 'drv_name' => $row['drv_name'], 'drv_phone' => $row['drv_phone'], 'drv_lic_exp_date' => $row['drv_lic_exp_date'], 'docs' => $driverDocs];
					}
				}
				if ($row['vhc_id'] != null && !array_key_exists("1_{$row['vhc_id']}", $arr))
				{
					$carDocs	 = VehicleDocs::model()->getUnapprovedDoc($row['vhc_id']);
					$isVehicle	 = in_array($row['vhc_id'], $mapVhcIds);
					if ($carDocs['count'] > 0 && $isVehicle == true)
					{
						$arr["1_{$row['vhc_id']}"] = ['entity_type' => '1', 'entity_id' => $row['vhc_id'], 'vhc_number' => $row['vhc_number'], 'vht_model' => $row['vht_model'], 'car_type' => $row['car_type'], 'vhc_insurance_exp_date' => $row['vhc_insurance_exp_date'], 'vhc_reg_exp_date' => $row['vhc_reg_exp_date'], 'docs' => $carDocs];
					}
				}
			}
		}

		foreach ($arr as $a)
		{
			$result[] = ['entity_type' => (int) $a['entity_type'], 'entity_id' => (int) $a['entity_id'], 'drv_name' => $a['drv_name'], 'drv_phone' => $a['drv_phone'], 'vhc_number' => $a['vhc_number'], 'vht_model' => $a['vht_model'], 'car_type' => $a['car_type'], 'exp_date' => $a['car_type'], 'vhc_insurance_exp_date' => $a['vhc_insurance_exp_date'], 'vhc_reg_exp_date' => $a['vhc_reg_exp_date'], 'drv_lic_exp_date' => $a['drv_lic_exp_date'], 'docs' => $a['docs']];
		}
		return $result;
	}

	public function getUnapprovedDocByDrvId($drvId)
	{
		$sql	 = "SELECT
                                    vhc_id,
                                    vehicles.vhc_approved,
                                    vehicles.vhc_number,
                                    vehicle_types.vht_model,
                                    vcv_vct_id AS vht_car_type,
                                    vhc_insurance_exp_date,
                                    vhc_reg_exp_date,
                                    vcvt.vcv_vct_id AS car_type
                            FROM  `booking_cab`
                            INNER  JOIN `booking` ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking.bkg_status IN (2, 3, 5, 6, 7) AND booking.bkg_active = 1 AND booking_cab.bcb_active = 1
                            LEFT   JOIN `vehicles` ON vehicles.vhc_id = booking_cab.bcb_cab_id AND vehicles.vhc_active > 0 AND vehicles.vhc_approved IN(0, 2)
                            LEFT   JOIN `vehicle_types` ON vehicle_types.vht_id = vehicles.vhc_type_id
                            LEFT   JOIN vcv_cat_vhc_type vcvt ON  vehicle_types.vht_id = vcvt.vcv_vht_id
                            WHERE
				booking_cab.bcb_driver_id in (SELECT d3.drv_id FROM drivers d1
          INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
          INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
          WHERE d1.drv_id='$drvId')
                                    AND vhc_id IS NOT NULL
                            GROUP BY
                                    booking_cab.bcb_id";
		$rows	 = DBUtil::queryAll($sql);
		$result	 = $result1 = [];
		foreach ($rows as $row)
		{
			$carDocs = VehicleDocs::model()->getUnapprovedDoc($row['vhc_id']);
			if (isset($carDocs['count']) && $carDocs['count'] > 0)
			{
				$result[] = ['entity_type' => 1, 'entity_id' => (int) $row['vhc_id'], 'vhc_number' => $row['vhc_number'], 'vht_model' => $row['vht_model'], 'car_type' => (int) $row['car_type'], 'vhc_insurance_exp_date' => $row['vhc_insurance_exp_date'], 'vhc_reg_exp_date' => $row['vhc_reg_exp_date'], 'docs' => $carDocs];
			}
		}
		return $result;
	}

	public function getStickyScoreCars($date1, $date2)
	{
		$randomNumber	 = rand();
		$createTable	 = "stickycarcount$randomNumber";
		$sqlDrop		 = "Drop table if EXISTS $createTable";
		DBUtil::command($sqlDrop)->execute();

		$params		 = ['date1' => $date1, 'date2' => $date2];
		$sqlcreated	 = "create TEMPORARY table $createTable
		(INDEX my_index_name (vhc_id))
		select  count(vehicles.vhc_id) as cnt,vehicles.vhc_id as vhc_id,states.stt_zone as  stt_zone
		from booking
		inner join booking_cab on booking_cab.bcb_id=booking.bkg_bcb_id and booking.bkg_status in (6,7) 
		inner join vehicles on vehicles.vhc_id = booking_cab.bcb_cab_id
		inner join cities on booking.bkg_from_city_id=cities.cty_id and cities.cty_active = 1
		inner join states on states.stt_id=cities.cty_state_id and states.stt_active = '1'
		where booking.bkg_pickup_date between :date1 and :date2
		group by vehicles.vhc_id,states.stt_zone";
		DBUtil::command($sqlcreated)->execute($params);

		$stickyCarDataProvider = array();

		$sql0						 = 'select 
			"Car with less than 5 trips" as h0,
			sum(if(stt_zone=1,1,0)) as "North",
			sum(if(stt_zone=2,1,0)) as "West",
			sum(if(stt_zone=3,1,0)) as "Central",
			sum(if(stt_zone=4 or stt_zone=7 ,1,0)) as "South",
			sum(if(stt_zone=5,1,0)) as "East",
			sum(if(stt_zone=6,1,0)) as "NorthEast"
			from  ' . $createTable . '  where  cnt<5';
		$stickyCarDataProvider[0]	 = DBUtil::queryRow($sql0);

		$sql1						 = 'select 
			"Car with between 5  and 10 trips" as h1,
			sum(if(stt_zone=1,1,0)) as "North",
			sum(if(stt_zone=2,1,0)) as "West",
			sum(if(stt_zone=3,1,0)) as "Central",
			sum(if(stt_zone=4 or stt_zone=7 ,1,0)) as "South",
			sum(if(stt_zone=5,1,0)) as "East",
			sum(if(stt_zone=6,1,0)) as "NorthEast"
			from  ' . $createTable . '  where  cnt between 5 and 10';
		$stickyCarDataProvider[1]	 = DBUtil::queryRow($sql1);

		$sql2						 = 'select 
			"Car with between 11  and 15 trips" as h2,
			sum(if(stt_zone=1,1,0)) as "North",
			sum(if(stt_zone=2,1,0)) as "West",
			sum(if(stt_zone=3,1,0)) as "Central",
			sum(if(stt_zone=4 or stt_zone=7 ,1,0)) as "South",
			sum(if(stt_zone=5,1,0)) as "East",
			sum(if(stt_zone=6,1,0)) as "NorthEast"
			from  ' . $createTable . '  where  cnt between 11 and 15';
		$stickyCarDataProvider[2]	 = DBUtil::queryRow($sql2);

		$sql3						 = 'select 
			"Car with greater than 15 trips" as h3,
			sum(if(stt_zone=1,1,0)) as "North",
			sum(if(stt_zone=2,1,0)) as "West",
			sum(if(stt_zone=3,1,0)) as "Central",
			sum(if(stt_zone=4 or stt_zone=7 ,1,0)) as "South",
			sum(if(stt_zone=5,1,0)) as "East",
			sum(if(stt_zone=6,1,0)) as "NorthEast"
			from  ' . $createTable . '  where   cnt>=16';
		$stickyCarDataProvider[3]	 = DBUtil::queryRow($sql3);

		return $stickyCarDataProvider;
	}

	public function getStickyScoreReport($date1, $date2)
	{
		$params	 = ['date1' => $date1, 'date2' => $date2];
		$sql	 = "SELECT 
						temp.date, 
						count(*) AS Totalcars, 
						Sum(IF(temp.cnt >=5 AND temp.cnt <= 15, 1, 0)) AS Countstickycars,
						Sum(IF(temp.cnt < 5, 1, 0)) AS Countnonstickycars,
 						Sum(IF(temp.cnt > 15, 1, 0)) AS Countsuperstickycars
					FROM     (SELECT   DATE_FORMAT(bkg_pickup_date, '%Y-%m') AS date,   count(booking_cab.bcb_cab_id) AS cnt
								FROM     booking
								INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id								
								WHERE    bkg_status IN (6,7) AND  booking.bkg_pickup_date BETWEEN :date1 and :date2 
								GROUP BY  DATE,booking_cab.bcb_cab_id) temp
					GROUP BY temp.date ORDER BY  date desc";
		return DBUtil::queryAll($sql, DBUtil::SDB3(), $params);
	}

	public function checkVerification($vhcId, $docType)
	{
		//echo $vhcId;
		$message = '';
		$model	 = $this->getbyVehicleID($vhcId);
		if (in_array($docType, [8, 9, 10, 11]))
		{
			$checkEnable = $model->vhs_boost_enabled;
			if (in_array($docType, [8, 9, 10, 11]) && $checkEnable == 1)
			{
				$message = 'Boost Verification';
			}
			else
			{
				$message = 'Cab Verification';
			}
		}
		return $message;
	}

	public function getBookingIdByVhcId($vhcId, $docType)
	{
		$message = '';
		$model	 = $this->getbyVehicleID($vhcId);
		if (in_array($docType, [8, 9, 10, 11]))
		{
			$verifyBkgId = $model->vhs_verify_bkgId;
			if ($verifyBkgId != '')
			{
				$bookingId = Booking::model()->getCodeById($verifyBkgId);
			}
			else
			{
				$bookingId = '';
			}
		}
		return $bookingId;
	}

	public static function modifyBoostStatus($boost_enabled_status, $vehicleId)
	{

		$updateSql = "UPDATE vehicle_stats SET vhs_boost_enabled=$boost_enabled_status WHERE vhs_vhc_id=$vehicleId";

		DBUtil::command($updateSql)->execute();
	}

	public static function updateCarVerify($vehicleId)
	{
		$updateSql = "UPDATE vehicle_stats SET vhs_verify_car=1,vhs_verification_date = now() WHERE vhs_vhc_id=$vehicleId";
		//echo $updateSql;exit; 
		DBUtil::command($updateSql)->execute();
	}

	public static function addRelatedBooking($vehicleId, $bookingId)
	{
		if (($vehicleId == null || $vehicleId == "") || ($bookingId == null || $bookingId == ""))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params		 = array('vehicleId' => $vehicleId, 'bookingId' => $bookingId);
		$updateSql	 = "UPDATE vehicle_stats SET vhs_verify_bkgId=:bookingId WHERE vhs_vhc_id=:vehicleId";
		DBUtil::execute($updateSql, $params);
	}

	public static function updateVerifyFlag($vehicleId)
	{
		$docPendingStatus	 = 3;
		$params				 = array('vehicleId' => $vehicleId, 'docPendingStatus' => $docPendingStatus);
		$updateSql			 = "UPDATE vehicle_stats SET vhs_verify_car=:docPendingStatus WHERE vhs_vhc_id=:vehicleId";
		DBUtil::execute($updateSql, $params);
	}

	public static function updateGeneralCar($vehicleId)
	{
		$updateSql = "UPDATE vehicle_stats SET vhs_verify_car=1,vhs_verification_date=now() WHERE vhs_vhc_id=$vehicleId";
		DBUtil::command($updateSql)->execute();
	}

	public function getBoostDocs($bkgId)
	{
		$params	 = array("bkgId" => $bkgId);
		$sql	 = "SELECT 	vhd_id , vhd_file, vhd_type FROM vehicle_stats  vhs
                       LEFT JOIN vehicle_docs vhd ON vhd.vhd_vhc_id = vhs.vhs_vhc_id  
                       WHERE vhs.vhs_verify_bkgId=:bkgId  AND vhs_active = 1 AND vhd_active = 1 AND vhd_type IN(8,9,10,11)";
		$records = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $records;
	}

	public static function carApprovalStatus($vhcId, $status)
	{
		$date		 = date("Y-m-d H:i:s");
		$params		 = [
			'vhcId'	 => $vhcId,
			'status' => $status,
			'date'	 => $date
		];
		$sqlUpdate	 = "UPDATE vehicle_stats vhs 
					 SET vhs.vhs_verify_car=:status, vhs.vhs_verification_date =:date
					 WHERE vhs.vhs_vhc_id=:vhcId";
		return DBUtil::command($sqlUpdate)->execute($params);
	}

	public static function carDisapprovalStatus($vhcId, $status)
	{
		$date		 = date("Y-m-d H:i:s");
		$params		 = [
			'vhcId'			 => $vhcId,
			'status'		 => $status,
			'date'			 => $date,
			'boostVerify'	 => 0,
			'boostEnable'	 => 0
		];
		$sqlUpdate	 = "UPDATE vehicle_stats vhs 
					 SET vhs.vhs_verify_car=:status, vhs.vhs_verification_date =:date, 
					 vhs.vhs_boost_verify=:boostVerify, vhs.vhs_boost_enabled=:boostEnable 
					 WHERE vhs.vhs_vhc_id=:vhcId";
		return DBUtil::command($sqlUpdate)->execute($params);
	}

	public static function boostApprovalStatus($vhcId, $status)
	{
		$boostVerify = ($status == 2) ? 0 : 1;
		$date		 = date("Y-m-d H:i:s");
		$params		 = [
			'vhcId'			 => $vhcId,
			'boostVerfy'	 => $boostVerify,
			'boostEnable'	 => 1,
			'status'		 => $status,
			'date'			 => $date
		];
		$sql		 = "UPDATE vehicle_stats vhs 
					 SET vhs.vhs_boost_verify=:status, 
                                        vhs.vhs_boost_enabled= :boostEnable,
                                        vhs.vhs_verify_car=:status, vhs.vhs_boost_approved_date =:date,vhs.vhs_verification_date =:date 
					 WHERE vhs.vhs_vhc_id=:vhcId";
		return DBUtil::command($sql)->execute($params);
	}

	public static function carVerifyBoostUnverify($vhcId, $status)
	{
		$date	 = date("Y-m-d H:i:s");
		$params	 = [
			'vhcId'	 => $vhcId,
			'date'	 => $date
		];
		$sql	 = "UPDATE vehicle_stats vhs 
		     SET vhs.vhs_boost_verify=0, vhs.vhs_verify_car=1, vhs.vhs_boost_enabled=0, vhs.vhs_boost_approved_date =:date
		     WHERE vhs.vhs_vhc_id=:vhcId";

		return DBUtil::command($sql)->execute($params);
	}

	public static function freezeVendorGivePenalty($bkgId)
	{
		$dateLimit			 = strtotime('2021-01-16 00:00:00');
		$bookingId			 = Booking::model()->getCodeById($bkgId);
		$userInfo			 = UserInfo::getInstance();
		$userInfo->userId	 = UserInfo::getUserId();
		$name				 = Admins::model()->getFullNameById($userInfo->userId);
		$model				 = Booking::model()->findByPk($bkgId);
		$bcb_id				 = $model->bkg_bcb_id;
		$pickupdate			 = strtotime($model->bkg_pickup_date);
		$bcbmodel			 = BookingCab::model()->findByPk($bcb_id);
		$vehicleId			 = $bcbmodel->bcb_cab_id;
		$vehicleModel		 = Vehicles::model()->findByPk($vehicleId);
		$vehicleNumber		 = $vehicleModel->vhc_number;
		$vendorId			 = $bcbmodel->bcb_vendor_id;
		$desc				 = "Cab verification failed by $name(See attachment in Booking ID " . $bookingId . ")";
		BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog::CAB_VERIFIED, false, false);
		if ($pickupdate >= $dateLimit)
		{
			/* $params				 = ['vnd_id' => $vendorId];
			  $sql				 = "UPDATE vendor_pref set vnp_is_freeze=1 WHERE vnp_vnd_id=:vnd_id";
			  DBUtil::command($sql)->execute($params);
			  $remarks			 = "Vendor freeze due to Cab verification failed by System (See attachment in Booking ID " . $bookingId . ")";
			  $penaltyAmount		 = 2000;
			  $accTrans			 = AccountTransactions::model()->addVendorPenalty($bkgId, $vendorId, $penaltyAmount, $remarks);
			  //notification:

			 * 
			 */
			$payLoadData = ['vendorId' => $vendorId, 'EventCode' => BookingLog::CAB_VERIFIED];
			$message	 = "Cab verification failed for " . $vehicleNumber;
			$success	 = AppTokens::model()->notifyVendor($vendorId, $payLoadData, $message, "Cab verification failed.");
		}
	}

	public static function docucumentVerfifyStat($vehicleId)
	{
		$params	 = ['vehicleId' => $vehicleId];
		$sql	 = "SELECT vhs_verify_car FROM vehicle_stats WHERE vhs_vhc_id=:vehicleId";
		$flag	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $flag;
	}

	public function checkAndSave($vhcId)
	{
		$success = false;
		$exist	 = $this->checkExisting($vhcId);
		if (!$exist)
		{
			$vhstatmodel			 = new VehicleStats();
			$vhstatmodel->scenario	 = 'updateStats';
			$vhstatmodel->vhs_vhc_id = $vhcId;
			$vhstatmodel->vhs_active = 1;
			$vhstatmodel->save();
			$success				 = true;
		}
		return true;
	}

	public function checkExisting($vhcId)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('vhs_vhc_id', $vhcId);

		$exist = $this->findAll($criteria);
		if ($exist)
		{
			return true;
		}
		return false;
	}
}
