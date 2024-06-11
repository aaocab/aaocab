<?php

/**
 * This is the model class for table "vehicle_docs".
 *
 * The followings are the available columns in table 'vehicle_docs':
 * @property integer $vhd_id
 * @property integer $vhd_vhc_id
 * @property integer $vhd_type
 * @property string $vhd_file
 * @property string $vhd_remarks
 * @property integer $vhd_status
 * @property integer $vhd_active
 * @property string $vhd_created_at
 * @property string $vhd_appoved_at
 * @property integer $vhd_approve_by
 * @property integer $vhd_temp_approved
 * @property string $vhd_temp_approved_at
 *
 * The followings are the available model relations:
 * @property Vehicles $vhdVhc
 */
class VehicleDocs extends CActiveRecord
{

	const TYPE_INSURANCE			 = 1;
	const TYPE_LICENSE_FRONT		 = 2;
	const TYPE_LICENSE_BACK		 = 3;
	const TYPE_PUC				 = 4;
	const TYPE_RC_FRONT			 = 5;
	const TYPE_COMERCIAL_PERMIT	 = 6;
	const TYPE_FITNESS_CERTIFICATE = 7;
	const TYPE_CAR_FRONT			 = 8;
	const TYPE_CAR_BACK			 = 9;
	const TYPE_RC_REAR			 = 13;

	public $insuranceFile, $registrationCertificateFile, $newestVhc;
	public $vhd_approve, $vhcnumber;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vehicle_docs';
	}

	public $doctype		 = [
		1	 => 'insurance',
		2	 => 'frontLicensePlate',
		3	 => 'rearLicensePlate',
		4	 => 'pollutionUnderControl',
		5	 => 'registrationCertificate',
		6	 => 'commercialPermits',
		7	 => 'fitnessCertificate',
		8	 => 'Car(Front Image)',
		9	 => 'Car(Back Image)',
		10	 => 'Car(Left Image)',
		11	 => 'Car(Right Image)',
		13	 => 'Registration Certificate(Rear)'
	];
	public $doctypeTxt	 = [
		1	 => 'Insurance',
		2	 => 'Front License Plate',
		3	 => 'Rear License Plate',
		4	 => 'Pollution Under Control',
		5	 => 'Registration Certificate',
		6	 => 'Commercial Permits',
		7	 => 'Fitness Certificate',
		8	 => 'Car(Front Image)',
		9	 => 'Car(Back Image)',
		10	 => 'Car(Left Image)',
		11	 => 'Car(Right Image)',
		13	 => 'Registration Certificate(Rear)'
	];

	public function vehicleDocumentType()
	{
		$arr[1]	 = 'Insurance';
		$arr[2]	 = 'FrontLicense';
		$arr[3]	 = 'RearLicense';
		$arr[4]	 = 'PollutionUnderControl';
		$arr[5]	 = 'RegistrationCertificate';
		$arr[6]	 = 'CommercialPermits';
		$arr[7]	 = 'FitnessCertificate';
		$arr[8]	 = 'Car(Front Image)';
		$arr[9]	 = 'Car(Back Image)';
		$arr[10] = 'Car(Left Image)';
		$arr[11] = 'Car(Right Image)';
		$arr[13] = 'RegistrationCertificateRear';
		return $arr;
	}

	public function vehicleDocumentDbField()
	{

		$arr[1]	 = 'vhc_insurance_exp_date';
		$arr[2]	 = '';
		$arr[3]	 = '';
		$arr[4]	 = 'vhc_pollution_exp_date';
		$arr[5]	 = 'vhc_reg_exp_date';
		$arr[6]	 = 'vhc_commercial_exp_date';
		$arr[7]	 = 'vhc_fitness_cert_end_date';
		$arr[13] = 'vhc_reg_exp_date';
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
			array('vhd_vhc_id, vhd_type, vhd_status, vhd_approve_by,vhd_temp_approved', 'numerical', 'integerOnly' => true),
			array('vhd_file', 'length', 'max' => 250),
			array('vhd_appoved_at', 'safe'),
			array('vhd_vhc_id, vhd_file', 'required'),
			array('vhd_remarks', 'required', 'on' => 'reject'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vhd_id, vhd_vhc_id, vhd_type, vhd_file, vhcnumber,vhd_remarks, vhd_status, vhd_active, vhd_created_at, vhd_appoved_at, vhd_approve_by,vhd_temp_approved,vhd_temp_approved_at,vhd_machine_output', 'safe'),
			array('vhd_id, vhd_vhc_id, vhd_type, vhd_file,vhcnumber, vhd_remarks, vhd_status, vhd_active, vhd_created_at, vhd_appoved_at, vhd_approve_by,vhd_machine_output', 'safe', 'on' => 'search'),
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
			'vhdVhc' => array(self::BELONGS_TO, 'Vehicles', 'vhd_vhc_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vhd_id'				 => 'Vhd',
			'vhd_vhc_id'			 => 'Vhd Vhc',
			'vhcnumber'				 => 'Vehicle Number',
			'vhd_type'				 => '1=>Insurance , 2=>Front License, 3=>Rear License, 4=>Pollution Under Control, 5=>Registration Certificate, 6=>Commercial Permits, 7=>Fitness Certificate',
			'vhd_file'				 => 'Vhd File',
			'vhd_remarks'			 => 'Remarks',
			'vhd_status'			 => 'Vhd Status',
			'vhd_active'			 => 'Vhd Active',
			'vhd_appoved_at'		 => 'Vhd Appoved At',
			'vhd_approve_by'		 => 'Vhd Approve By',
			'vhd_temp_approved'		 => 'Vhd Temporary approved',
			'vhd_temp_approved_at'	 => 'Vhd Temporary approved At'
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
		$criteria->compare('vhd_id', $this->vhd_id);
		$criteria->compare('vhd_vhc_id', $this->vhd_vhc_id);
		$criteria->compare('vhd_type', $this->vhd_type);
		$criteria->compare('vhd_file', $this->vhd_file, true);
		$criteria->compare('vhd_status', $this->vhd_status);
		$criteria->compare('vhd_active', $this->vhd_active);
		$criteria->compare('vhd_appoved_at', $this->vhd_appoved_at, true);
		$criteria->compare('vhd_approve_by', $this->vhd_approve_by);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VehicleDocs the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getDocType($var)
	{
		$list = $this->doctype;
		return $list[$var];
	}

	public function getDocTypeText($var = 0)
	{
		$var	 = ($var > 0) ? $var : $this->vhd_type;
		$list	 = $this->doctypeTxt;
		return $list[$var];
	}

	public function getDocTypeList()
	{
		$list = $this->doctypeTxt;
		return $list;
	}

	public function findByVhcId($id, $type = '')
	{

		$type = ($type != '') ? '(' . implode(',', $type) . ')' : "";

		$sql = "SELECT * FROM `vehicle_docs` WHERE vehicle_docs.vhd_vhc_id = '$id' AND vehicle_docs.vhd_active=1";
		$sql .= ($type != '') ? ' AND vehicle_docs.vhd_type IN ' . $type . '' : '';

		return DBUtil::queryAll($sql);
	}

	public function findByDocType($id, $type)
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = 'vhd_doc_file';
		$criteria->compare('vhd_vhc_id', $id);
		$criteria->compare('vhd_doc_type', $type);
		return $this->find($criteria);
	}

	public function fetchByDoctype($id, $type)
	{
		$sql = "SELECT vehicle_docs.vhd_file
                    FROM `vehicle_docs`
                    WHERE vehicle_docs.vhd_vhc_id=$id AND vehicle_docs.vhd_type=$type";
		return DBUtil::command($sql)->queryScalar();
	}

	public static function getByVehicleId($vhcId)
	{
		$models		 = VehicleDocs::model()->findAll('vhd_active=1 AND vhd_vhc_id=:vhcId', ['vhcId' => $vhcId]);
		$vhdModels	 = [];
		foreach ($models as $vhdModel)
		{
			$vhdModels[$vhdModel->vhd_type] = $vhdModel;
		}

		return $vhdModels;
	}

	public function findAllByVhcId($vhcId)
	{
		$param	 = ['vhcId' => $vhcId];
		$sql	 = "SELECT * FROM
                    (
                        SELECT DISTINCT vehicle_docs.vhd_id,vehicle_docs.vhd_vhc_id,vehicle_docs.vhd_type,
                        vehicle_docs.vhd_file,vehicle_docs.vhd_s3_data,vehicle_docs.vhd_remarks,vehicle_docs.vhd_status,
                        vehicle_docs.vhd_created_at,vehicle_docs.vhd_appoved_at,vehicle_docs.vhd_approve_by,vehicle_docs.vhd_active
                        FROM `vehicle_docs`
                        WHERE vehicle_docs.vhd_active = 1 AND vehicle_docs.vhd_vhc_id=:vhcId
                        ORDER BY vehicle_docs.vhd_created_at DESC
                    ) a GROUP BY vhd_type";

		return DBUtil::queryAll($sql, DBUtil::MDB(), $param);
	}

	public function findAllActiveDocByVhcId($vhcId)
	{
		$param	 = ['vhcId' => $vhcId];
		$sql	 = "SELECT * FROM
                    ( SELECT DISTINCT vehicle_docs.vhd_id,vehicle_docs.vhd_vhc_id,vehicle_docs.vhd_type,
                        vehicle_docs.vhd_file,vehicle_docs.vhd_remarks,vehicle_docs.vhd_status,
                        vehicle_docs.vhd_created_at,vehicle_docs.vhd_appoved_at,vehicle_docs.vhd_approve_by
                        FROM `vehicle_docs`
                        WHERE vehicle_docs.vhd_vhc_id=:vhcId
                        AND vehicle_docs.vhd_active=1
                        ORDER BY vehicle_docs.vhd_created_at DESC
                    ) a GROUP BY vhd_type";

		return DBUtil::query($sql, DBUtil::MDB(), $param);
	}

	public function getDocsByVhcId($vhcId)
	{
		$rows										 = $this->findAllByVhcId($vhcId);
		$data['vhc_insurance_proof']				 = $data['vhc_front_plate']					 = $data['vhc_rear_plate']						 = '';
		$data['vhc_pollution_certificate']			 = $data['vhc_reg_certificate']				 = $data['vhc_permits_certificate']			 = $data['vhc_fitness_certificate']			 = '';
		$data['vhc_insurance_proof_status']			 = $data['vhc_front_plate_status']				 = $data['vhc_rear_plate_status']				 = $data['vhc_pollution_certificate_status']	 = 0;
		$data['vhc_reg_certificate_status']			 = $data['vhc_permits_certificate_status']		 = $data['vhc_fitness_certificate_status']		 = 0;
		foreach ($rows as $row)
		{
			switch ($row['vhd_type'])
			{
				case 1:
					$data['vhc_insurance_proof']				 = $row['vhd_file'];
					$data['vhc_insurance_proof_status']			 = $row['vhd_status'];
					break;
				case 2:
					$data['vhc_front_plate']					 = $row['vhd_file'];
					$data['vhc_front_plate_status']				 = $row['vhd_status'];
					break;
				case 3:
					$data['vhc_rear_plate']						 = $row['vhd_file'];
					$data['vhc_rear_plate_status']				 = $row['vhd_status'];
					break;
				case 4:
					$data['vhc_pollution_certificate']			 = $row['vhd_file'];
					$data['vhc_pollution_certificate_status']	 = $row['vhd_status'];
					break;
				case 5:
					$data['vhc_reg_certificate']				 = $row['vhd_file'];
					$data['vhc_reg_certificate_status']			 = $row['vhd_status'];
					break;
				case 6:
					$data['vhc_permits_certificate']			 = $row['vhd_file'];
					$data['vhc_permits_certificate_status']		 = $row['vhd_status'];
					break;
				case 7:
					$data['vhc_fitness_certificate']			 = $row['vhd_file'];
					$data['vhc_fitness_certificate_status']		 = $row['vhd_status'];
					break;
			}
		}
		return $data;
	}

	/**
	 * @return CDbDataReader 
	 */
	public static function findApproveList()
	{
		$sql = "SELECT  vhd1.vhd_vhc_id, vhc_number,CONCAT(vhd1.vhd_id,',',vhd5.vhd_id,',',vhd7.vhd_id) as vhdIds
				FROM `vehicles`
				INNER JOIN `vehicle_docs` vhd1 ON vhd1.vhd_vhc_id=vhc_id AND vhd1.vhd_type IN (1) AND vhd1.vhd_active=1 AND vhd1.vhd_status=1
				INNER JOIN `vehicle_docs` vhd5 ON vhd5.vhd_vhc_id=vhc_id AND vhd5.vhd_type IN (5) AND vhd5.vhd_active=1 AND vhd5.vhd_status=1
				INNER JOIN `vehicle_docs` vhd7 ON vhd7.vhd_vhc_id=vhc_id AND vhd7.vhd_type IN (7) AND vhd7.vhd_active=1 AND vhd7.vhd_status=1
			
				WHERE vehicles.vhc_approved IN (0,2,3)
							AND (CURDATE() < (vehicles.vhc_insurance_exp_date) AND (vehicles.vhc_insurance_exp_date) IS NOT NULL AND (vehicles.vhc_insurance_exp_date) <> '1970-01-01')
							AND (CURDATE() < (vehicles.vhc_reg_exp_date) AND (vehicles.vhc_reg_exp_date) IS NOT NULL AND (vehicles.vhc_reg_exp_date) <> '1970-01-01')
							AND (CURDATE() < (vehicles.vhc_fitness_cert_end_date) AND (vehicles.vhc_fitness_cert_end_date) IS NOT NULL AND (vehicles.vhc_fitness_cert_end_date) <> '1970-01-01')
							AND vehicles.vhc_is_commercial = 1
							AND vehicles.vhc_active=1
				GROUP BY vhc_id";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * @return CDbDataReader 
	 */
	public function findDisapproveList()
	{
		$sql = "SELECT   DISTINCT(vhd_vhc_id),  vhc_number
				FROM     `vehicles`
				INNER JOIN `vehicle_docs` vhd1 ON vhd1.vhd_vhc_id=vhc_id AND vhd1.vhd_type IN (1,5,7) AND vhd1.vhd_active=1 AND vhd1.vhd_status=2
			  WHERE    vehicles.vhc_approved IN (0, 1, 2) AND vehicles.vhc_active = 1	";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * @return CDbDataReader 
	 */
	public static function pendingApprovalList()
	{
		$sql = "SELECT vhc_id, vhc_number, COUNT(DISTINCT vhd1.vhd_type) as cntDocs, GROUP_CONCAT(DISTINCT vhd1.vhd_type) as docs
				FROM     `vehicles`
				LEFT JOIN `vehicle_docs` vhd1 ON vhd1.vhd_vhc_id=vhc_id AND vhd1.vhd_type IN (1,5,7) AND vhd1.vhd_active=1 AND vhd1.vhd_status IN (0,1)
				WHERE  vehicles.vhc_approved IN (1) AND vehicles.vhc_active = 1
				GROUP BY vhc_id HAVING cntDocs<3  ";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public function missingDocsByVhdIds($vhdIds)
	{
		$sql = "SELECT GROUP_CONCAT(missing SEPARATOR ',') as missing_docs FROM (
                        SELECT CONCAT(IF(vehicle_docs.vhd_type=5,'Registration',''),
                              IF(vehicle_docs.vhd_type=1,'Insurance',''),
                              IF(vehicle_docs.vhd_type=6,'Permit',''),
                              IF(vehicle_docs.vhd_type=7,'Fitness Certificate','')
                             ) as missing FROM `vehicle_docs` WHERE vehicle_docs.vhd_id IN ($vhdIds)
                    )a";

		return DBUtil::command($sql)->queryScalar();
	}

	public function getUnapproved($arr = [], $command = false)
	{
		$where = '';
		if (trim($arr['vhcnumber']) != '')
		{
			$where .= "  AND LOWER(REPLACE(vhc1.vhc_number,' ','')) LIKE '%" . strtolower(str_replace(' ', '', $arr['vhcnumber'])) . "%'";
		}
		if (trim($arr['vhd_type']) != '')
		{
			$where .= "  AND vhd1.vhd_type = " . $arr['vhd_type'];
		}
		if (trim($arr['vhc_id']) > 0)
		{
			$where .= "  AND vhc1.vhc_id = " . $arr['vhc_id'];
		}

		$where .= " AND vhd1.vhd_type NOT IN (8,9,10,11,13)";

		$sqlVehicle					 = "SELECT GROUP_CONCAT(DISTINCT vhd_vhc_id) as vhd_vhc_id FROM vehicle_docs WHERE vhd_status = 0 AND vhd_active = 1 AND vhd_file IS NOT NULL AND vhd_file <> ''";
		$rowVehicle					 = DBUtil::queryRow($sqlVehicle);
		$rowVehicle['vhd_vhc_id']	 = $rowVehicle['vhd_vhc_id'] != null ? rtrim($rowVehicle['vhd_vhc_id'], ',') : -1;
		$randomNumber				 = rand();
		$createTable				 = "VechicleDocs$randomNumber";
		$sql						 = "DROP TABLE IF EXISTS $createTable;";
		$creTableDeleted			 = DBUtil::command($sql)->execute();

		$sqlTemp = "CREATE TEMPORARY TABLE $createTable
					(INDEX my_vhc_id (vhc_id)) 
					SELECT bcb_cab_id as vhc_id , COUNT(DISTINCT bkg.bkg_id) AS hasBooking, if(bkg_pickup_date > NOW(),1,0) futureBooking,  MIN(if(bkg_pickup_date > NOW(),bkg_pickup_date,NULL)) as futureDate, MIN(bkg_pickup_date) as minDate FROM
					booking bkg INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bkg_status IN (5,6,7)   AND bcb_cab_id IN (" . $rowVehicle['vhd_vhc_id'] . ") GROUP BY bcb_cab_id";

		$creTableCreated = DBUtil::command($sqlTemp)->execute();

		$sql = "SELECT  vhd1.vhd_file, vhd1.vhd_id,vhd1.vhd_type,vhc1.vhc_number,if(vhc1.vhc_approved !=1,1,0) unapproved
				FROM vehicle_docs vhd1
				JOIN vehicles vhc1 ON vhc1.vhc_id = vhd1.vhd_vhc_id AND vhc1.vhc_active > 0
				LEFT JOIN $createTable temp ON  temp.vhc_id=vhc1.vhc_id
				WHERE vhd1.vhd_status = 0 AND vhd1.vhd_active = 1 AND
				vhd1.vhd_file IS NOT NULL AND
				vhd1.vhd_file <> ''    $where  GROUP BY vhd1.vhd_id ";

		$sqlCount = "SELECT  vhd1.vhd_id
					FROM vehicle_docs vhd1
					JOIN vehicles vhc1 ON vhc1.vhc_id = vhd1.vhd_vhc_id AND vhc1.vhc_active > 0
					WHERE vhd1.vhd_status = 0 AND vhd1.vhd_active = 1 AND
					vhd1.vhd_file IS NOT NULL AND
					vhd1.vhd_file <> ''    $where  GROUP BY vhd1.vhd_id ";

		$defaultOrder = 'futureBooking DESC,unapproved DESC,hasBooking DESC, futureDate ASC, vhd_created_at asc';
		if ($arr['newestVhc'])
		{
			$defaultOrder = 'vhc_year DESC, vhc_dop DESC';
		}

		if ($command == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 =>
					['vhc_number'],
					'defaultOrder'	 => $defaultOrder],
				'pagination'	 => ['pageSize' => 20],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::queryAll($sql);
		}
	}

	public function getCarVerifyImage($arr)
	{
		$where	 = '';
		$where_1 = '';
		if (trim($arr['vhcnumber']) != '')
		{
			$where .= "  AND LOWER(REPLACE(vehicles.vhc_number,' ','')) LIKE '%" . strtolower(str_replace(' ', '', $arr['vhcnumber'])) . "%'";
		}
		$sql = "(SELECT booking_pay_docs.bpay_id,
		booking_pay_docs.bpay_bkg_id,
		booking_pay_docs.bpay_type,
		bpay_image,
		vehicles.vhc_number,
		vehicles.vhc_id,
		vehicle_stats.vhs_boost_enabled,'' as vhd_id,'' as vhd_type, '' as vhd_file
		FROM `booking_pay_docs`
		INNER JOIN booking ON booking.bkg_id = booking_pay_docs.bpay_bkg_id
		INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id
		INNER JOIN vehicles ON booking_cab.bcb_cab_id = vehicles.vhc_id AND vehicles.vhc_active > 0
		INNER JOIN vehicle_stats ON  vehicle_stats.vhs_vhc_id = vehicles.vhc_id 
		WHERE booking_pay_docs.bpay_type IN (8,9, 10,11)
		AND booking_pay_docs.bpay_approved = 0
		AND booking_pay_docs.bpay_status = 1 " . $where . "
		GROUP BY booking_pay_docs.bpay_bkg_id
		ORDER BY booking_pay_docs.bpay_id DESC) ";

		$paydoc_count	 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
		$count			 = 50;
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $paydoc_count,
			'sort'			 => ['attributes' =>
				['vhc_number']],
			'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function getCarVerifyDoc($arr)
	{
		$where	 = '';
		$where_1 = '';
		if (trim($arr['vhcnumber']) != '')
		{
			$where	 .= "  AND LOWER(REPLACE(vehicles.vhc_number,' ','')) LIKE '%" . strtolower(str_replace(' ', '', $arr['vhcnumber'])) . "%'";
			$where_1 .= "  AND LOWER(REPLACE(vhc1.vhc_number,' ','')) LIKE '%" . strtolower(str_replace(' ', '', $arr['vhcnumber'])) . "%'";
		}
		$type = $arr['vhd_type'] | 0;
		if ($type == 1)
		{
			$where .= " AND vehicle_stats.vhs_boost_enabled !=1";
		}
		if ($type == 2)
		{
			$where .= " AND vehicle_stats.vhs_boost_enabled=1";
		}

		/* if (trim($arr['vhd_type']) = 0)
		  {
		  $where = " AND vehicle_stats.vhs_boost_enabled=0";
		  }
		  if (trim($arr['vhc_id']) > 0)
		  {
		  $where .= " AND vehicle_stats.vhs_boost_enabled=1";
		  } */
		/* if (trim($arr['vhd_type']) = 0)
		  {
		  $where .= " AND vehicle_stats.vhs_boost_enabled=1";
		  }
		  if (trim($arr['vhc_id']) = 1)
		  {
		  $where .= " AND vehicle_stats.vhs_boost_enabled=0";
		  } */

		$union = "";
		if ($type != 1)
		{
			$union = ' UNION 
					(SELECT  "" as bpay_id,"" as bpay_bkg_id,"" as bpay_type,"" as bpay_image,vhc1.vhc_number,vhc1.vhc_id,0 as vhs_boost_enabled,  vhd1.vhd_id,vhd1.vhd_type,vhd1.vhd_file
					FROM vehicle_docs vhd1
					JOIN vehicles vhc1 ON vhc1.vhc_id = vhd1.vhd_vhc_id
					WHERE vhd1.vhd_status = 0 AND vhd1.vhd_active = 1 AND
					vhd1.vhd_file IS NOT NULL AND
					vhd1.vhd_file <> "" AND vhd1.vhd_type IN (8, 9, 10, 11) ' . $where_1 . ' GROUP BY vhd1.vhd_id )';
		}
		$sql = "(SELECT booking_pay_docs.bpay_id,
		booking_pay_docs.bpay_bkg_id,
		booking_pay_docs.bpay_type,
		bpay_image,
		vehicles.vhc_number,
		vehicles.vhc_id,
		vehicle_stats.vhs_boost_enabled,'' as vhd_id,'' as vhd_type, '' as vhd_file
		FROM `booking_pay_docs`
		INNER JOIN booking ON booking.bkg_id = booking_pay_docs.bpay_bkg_id
		INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id
		INNER JOIN vehicles ON booking_cab.bcb_cab_id = vehicles.vhc_id AND vehicles.vhc_active > 0
		INNER JOIN vehicle_stats ON  vehicle_stats.vhs_vhc_id = vehicles.vhc_id 
		WHERE booking_pay_docs.bpay_type IN (8,9, 10,11)
		AND booking_pay_docs.bpay_approved = 0
		AND booking_pay_docs.bpay_status = 1 " . $where . "
		GROUP BY booking_pay_docs.bpay_bkg_id
		ORDER BY booking_pay_docs.bpay_id DESC) " . $union;

		$sqlCount = "SELECT booking_pay_docs.bpay_id
		FROM `booking_pay_docs`
		INNER JOIN booking ON booking.bkg_id = booking_pay_docs.bpay_bkg_id
		INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id
		INNER JOIN vehicles ON booking_cab.bcb_cab_id = vehicles.vhc_id AND vehicles.vhc_active > 0
		INNER JOIN vehicle_stats ON  vehicle_stats.vhs_vhc_id = vehicles.vhc_id 
		WHERE booking_pay_docs.bpay_type IN (8,9, 10,11)
		AND booking_pay_docs.bpay_approved = 0
		AND booking_pay_docs.bpay_status = 1 " . $where . "
		GROUP BY booking_pay_docs.bpay_bkg_id    
		ORDER BY booking_pay_docs.bpay_id DESC";

		$paydoc_count	 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
		$union_count	 = 0;
		if ($type != 1)
		{
			$qry_1		 = "SELECT  vhd1.vhd_id
					FROM vehicle_docs vhd1
					JOIN vehicles vhc1 ON vhc1.vhc_id = vhd1.vhd_vhc_id
					WHERE vhd1.vhd_status = 0 AND vhd1.vhd_active = 1 AND
					vhd1.vhd_file IS NOT NULL AND
					vhd1.vhd_file <> '' AND vhd1.vhd_type IN (8, 9, 10, 11)  GROUP BY vhd1.vhd_id";
			$union_count = DBUtil::command("SELECT COUNT(*) FROM ($qry_1) abc", DBUtil::SDB())->queryScalar();
		}
		$count			 = 50;
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $paydoc_count + $union_count,
			'sort'			 => ['attributes'	 =>
				['vhc_number'],
				'defaultOrder'	 => $defaultOrder],
			'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function updateExistingByIdType($vhcId, $vhcType)
	{
		$sql = "UPDATE `vehicle_docs` SET `vhd_active`=0 WHERE vehicle_docs.vhd_vhc_id=$vhcId AND vehicle_docs.vhd_type=$vhcType AND vehicle_docs.vhd_active=1";

		$cdb		 = DBUtil::command($sql);
		$rowsUpdated = $cdb->execute();
		return $rowsUpdated;
	}

	public function setTypeByDocumentType($doc_type)
	{
		switch ($doc_type)
		{
			case 'insurance':
				$this->vhd_type	 = 1;
				break;
			case 'FrontLicensePlate':
				$this->vhd_type	 = 1;
				break;
			case 'RearLicensePlate':
				$this->vhd_type	 = 2;
				break;
			case 'PUC':
				$this->vhd_type	 = 2;
				break;
			case 'RC':
				$this->vhd_type	 = 3;
				break;
			case 'Permit':
				$this->vhd_type	 = 3;
				break;
			case 'Fitness':
				$this->vhd_type	 = 4;
				break;
			case 'Packages':
				$this->vhd_type	 = 8;
				break;
		}
	}

	public function savePackages($vhcId, $path, UserInfo $userInfo = null, $doc_type, $tempApprove = 0)
	{
		$success = false;
		if ($path != '' && $vhcId != '')
		{
			$this->vhd_type	 = $doc_type;
			$returnSet		 = $this->updateExistingByIdType($vhcId, $this->vhd_type);

			$this->vhd_vhc_id		 = $vhcId;
			$this->vhd_file			 = $path;
			$this->vhd_status		 = 0;
			$this->vhd_active		 = 1;
			$this->vhd_appoved_at	 = NULL;
			$this->vhd_approve_by	 = NULL;

			if ($tempApprove == 1 && in_array($doc_type, [1, 5]))
			{
				$this->vhd_temp_approved	 = $tempApprove;
				$this->vhd_temp_approved_at	 = date('Y-m-d H:i:s');
			}

			if ($this->validate())
			{
				if ($this->save())
				{
					$success	 = true;
					$logArray	 = VehiclesLog::model()->getLogByDocumentType($doc_type);
					$logDesc	 = VehiclesLog::model()->getEventByEventId($logArray['upload']);
					VehiclesLog::model()->createLog($vhcId, $logDesc, $userInfo, VehiclesLog::VEHICLE_FILE_UPLOAD, false, false);
				}
				else
				{
					$success = false;
				}
			}
			else
			{
				$success = false;
				$errors	 = $this->getErrors();
			}
			return $success;
		}
	}

	public function saveDocument($vhcId, $path, UserInfo $userInfo = null, $doc_type, $tempApprove = 0)
	{

		$success = false;
		if ($path != '' && $vhcId != '')
		{
			Logger::trace(" Upload Packeage Images .... Upload File Path*********" . $path);
			$this->vhd_type	 = $doc_type;
			$returnSet		 = $this->updateExistingByIdType($vhcId, $this->vhd_type);

			$this->vhd_vhc_id		 = $vhcId;
			$this->vhd_file			 = $path;
			$this->vhd_status		 = 0;
			$this->vhd_active		 = 1;
			$this->vhd_appoved_at	 = NULL;
			$this->vhd_approve_by	 = NULL;

			if ($tempApprove == 1 && in_array($doc_type, [1, 5]))
			{
				$this->vhd_temp_approved	 = $tempApprove;
				$this->vhd_temp_approved_at	 = date('Y-m-d H:i:s');
			}

			if ($this->validate())
			{
				if ($this->save())
				{
					$success	 = true;
					$logArray	 = VehiclesLog::model()->getLogByDocumentType($doc_type);

					$logDesc = VehiclesLog::model()->getEventByEventId($logArray['upload']);

					VehiclesLog::model()->createLog($vhcId, $logDesc, $userInfo, VehiclesLog::VEHICLE_FILE_UPLOAD, false, false);
				}
				else
				{
					$success = false;
				}
			}
			else
			{
				$success = false;
				$errors	 = $this->getErrors();
			}
			return $success;
		}
	}

	public function saveDocumentNew($vhcId, $path, UserInfo $userInfo = null, $doc_type, $tempApprove = 0, $checksum = null, $inApp = 0)
	{
		$success = false;
		if ($path != '' && $vhcId != '')
		{
			Logger::trace(" Upload Packeage Images .... Upload File Path*********" . $path);
			$this->vhd_type = $doc_type;

			$checkExistDoc = $this->checkExistDoc($vhcId, $this->vhd_type);
			if ($checkExistDoc['vhd_id'] != "")
			{
				$model					 = VehicleDocs::model()->findByPk($checkExistDoc['vhd_id']);
				$model->vhd_file		 = $path;
				$model->vhd_status		 = 0;
				$model->vhd_appoved_at	 = NULL;
				$model->vhd_approve_by	 = NULL;
				$model->vhd_checksum	 = $checksum;
				$model->vhd_created_at	 = date('Y-m-d H:i:s');
				$model->vhd_vhc_id		 = ((!empty($model->vhd_vhc_id)) ? $model->vhd_vhc_id : $vhcId);
				if ($model->vhd_s3_data != '' && $model->vhd_file != '')
				{
					$model->vhd_s3_data = NULL;
				}
				if (empty($model->vhd_vhc_id))
				{
					throw new Exception("Sorry unable to find any specific vehicle", ReturnSet::ERROR_INVALID_DATA);
				}
				$model->save();
				if ($tempApprove == 1 && in_array($doc_type, [1, 5]))
				{
					$model->vhd_temp_approved	 = $tempApprove;
					$model->vhd_temp_approved_at = date('Y-m-d H:i:s');
				}

				$success = true;
			}
			else
			{
				#$returnSet = $this->updateExistingByIdType($vhcId, $this->vhd_type);
				$this->vhd_vhc_id		 = $vhcId;
				$this->vhd_file			 = $path;
				$this->vhd_status		 = 0;
				$this->vhd_active		 = 1;
				$this->vhd_appoved_at	 = NULL;
				$this->vhd_approve_by	 = NULL;
				$this->vhd_checksum		 = $checksum;
				if ($tempApprove == 1 && in_array($doc_type, [1, 5]))
				{
					$this->vhd_temp_approved	 = $tempApprove;
					$this->vhd_temp_approved_at	 = date('Y-m-d H:i:s');
				}
				if (empty($this->vhd_vhc_id))
				{
					throw new Exception("Sorry unable to find any specific vehicle", ReturnSet::ERROR_INVALID_DATA);
				}
				if ($this->validate())
				{
					if ($this->save())
					{
						$success = true;
					}
					else
					{
						$success = false;
					}
				}
				else
				{
					$success = false;
					$errors	 = $this->getErrors();
				}
			}
			if ($success == true)
			{
				$logArray	 = VehiclesLog::model()->getLogByDocumentType($doc_type);
				$logDesc	 = VehiclesLog::model()->getEventByEventId($logArray['upload']);
				VehiclesLog::model()->createLog($vhcId, $logDesc, $userInfo, VehiclesLog::VEHICLE_FILE_UPLOAD, false, false);
			}
			return $success;
		}
	}

	public function getMsgByType($type, $sub_type = 0)
	{
		$returnData = [];
		switch ($type)
		{
			case 1:
				$returnData	 = ['remarks' => 'Insurance Paper expired. Upload latest papers with new expiration date', 'event_id' => VehiclesLog::VEHICLE_INSURANCE_REJECT, 'doc' => 'Insurance'];
				break;
			case 4:
				$returnData	 = ['remarks' => 'PUC Paper expired. Upload latest papers with new expiration date', 'event_id' => VehiclesLog::VEHICLE_PUC_REJECT, 'doc' => 'PUC'];
				break;
			case 5:
				$returnData	 = ['remarks' => 'Registration Paper expired. Upload latest papers with new expiration date', 'event_id' => VehiclesLog::VEHICLE_REGISTRATION_REJECT, 'doc' => 'Registration'];
				break;
			case 6:
				$returnData	 = ['remarks' => 'Commercial permit Paper expired. Upload latest papers with new expiration date', 'event_id' => VehiclesLog::VEHICLE_PERMITS_REJECT, 'doc' => 'Commercial Permit'];
				break;
			case 7:
				$returnData	 = ['remarks' => 'Fitness Paper expired. Upload latest papers with new expiration date', 'event_id' => VehiclesLog::VEHICLE_FITNESS_REJECT, 'doc' => 'Fitness'];
				break;
		}

		return $returnData;
	}

	public function rejectDocument($vhd_id, $vendors = '', $userInfo)
	{
		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			$success			 = false;
			$modeld				 = VehicleDocs::model()->findByPk($vhd_id);
			$msgData			 = $this->getMsgByType($modeld->vhd_type);
			$modeld->vhd_status	 = 2;
			$modeld->vhd_remarks = $msgData['remarks'];
			if ($modeld->save())
			{
				VehiclesLog::model()->createLog($modeld->vhd_vhc_id, $modeld->vhd_remarks, $userInfo, $msgData['event_id'], false, false);
				if ($vendors != '')
				{
					$vendors = Vendors::getVendorsByIds($vendors);
					//$vendors = explode(',', $vendors);
					if (count($vendors) > 0)
					{
						$vhcModel = Vehicles::model()->findByPk($modeld->vhd_vhc_id);

						foreach ($vendors as $val)
						{
							if (isset($val['vnd_id']) && $val['vnd_id'] > 0)
							{
								//$isLastLogin = AppTokens::model()->checkVendorLastLogin($val['vnd_id']);
								$message	 = " Document (" . $msgData['doc'] . ") for Vehicle " . $vhcModel->vhc_number . " has been rejected (" . $msgData['remarks'] . "). Please verify and re-upload document properly";
								//if ($isLastLogin == 1)
								//{
								$vendorName	 = ($val['vnd_owner'] != '') ? $val['vnd_owner'] . ',' : $val['vnd_name'] . ',';
								$smsMessage	 = "Dear " . $vendorName . $message . ' - aaocab';
								$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
								$success	 = AppTokens::model()->notifyVendor($val['vnd_id'], $payLoadData, $smsMessage, "LICENSE PAPER REJECTED");
								Logger::create("Notification->" . $smsMessage, CLogger::LEVEL_INFO);
								//}
								//$venModel	 = Vendors::model()->findByPk($vndId);								
								//$sms		 = new smsWrapper();
								//$sms->sendAlertMessageVendor(91, $val['vnd_id'], $smsMessage, SmsLog::SMS_VENDOR_DRIVER_PAPER_REJECTED);
								//Logger::create("Sms->" . $smsMessage, CLogger::LEVEL_INFO);
							}
						}
					}
				}



				$success = true;
				if ($success == true)
				{
					$desc = $modeld->vhd_vhc_id . " ### " . $msgData['doc'] . " Rejected\n";
					Logger::create($desc, CLogger::LEVEL_INFO);
					$transaction->commit();
				}
			}
			else
			{
				throw new Exception("Reject document not yet saved.\n\t\t" . json_encode($modeld->getErrors()));
			}
		}
		catch (Exception $e)
		{
			Logger::create("Not Reject.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
			$transaction->rollback();
		}
	}

	/**
	 * update document status of particular cab
	 * @param type $vehicleId
	 * @param type $type
	 * @param type $status
	 * @return int number of row updated
	 */
	public static function modifyDocStatus($vehicleId, $type, $status)
	{
		$userInfo	 = UserInfo::getInstance();
		$userId		 = $userInfo->userId;
		$params		 = array("vhcId" => $vehicleId, "vhdtype" => $type, "userId" => $userId, "status" => $status);

		$sql	 = "UPDATE vehicle_docs SET vhd_status =:status, vhd_appoved_at =now(), vhd_approve_by=:userId WHERE vhd_vhc_id = :vhcId AND vhd_type = :vhdtype AND vhd_active=1";
		$result	 = DBUtil::execute($sql, $params);
		return $result;
	}

	public function checkApproveDocByVhcId($vhc_id, $vhc_type)
	{
		$sql		 = "SELECT
                IF(vehicle_docs.vhd_id > 0, 1, 0) AS check_approve
                FROM
                `vehicle_docs`
                WHERE
                vehicle_docs.vhd_vhc_id = $vhc_id AND vehicle_docs.vhd_type = $vhc_type AND vehicle_docs.vhd_active = 1 AND vehicle_docs.vhd_status = 1";
		$valApprove	 = DBUtil::command($sql)->queryScalar();
		$valApprove	 = ($valApprove > 0) ? $valApprove : 0;
		return $valApprove;
	}

	public function getUnapprovedDoc($vhcId, $count = 2)
	{
		$listDocs		 = $this->findAllByVhcId($vhcId);
		$insuranceStatus = $rcStatus		 = 0;
		$count			 = 2;
		$arr			 = [];
		if (count($listDocs) > 0)
		{
			foreach ($listDocs as $doc)
			{
				switch ($doc['vhd_type'])
				{
					case 1:
						if (($doc['vhd_status'] == 0 || $doc['vhd_status'] == 1))
						{
							$insuranceStatus = 1;
							$count			 = ($count - 1);
						}
						break;
					case 5:
						if (($doc['vhd_status'] == 0 || $doc['vhd_status'] == 1))
						{
							$rcStatus	 = 1;
							$count		 = ($count - 1);
						}
						break;
				}
			}
		}
		$arr			 = $insuranceArr	 = $rcArr			 = [];

		if ($insuranceStatus == 0)
		{
			$insuranceArr = ['insurance' => $insuranceStatus];
		}
		if ($rcStatus == 0)
		{
			$rcArr = ['rc' => $rcStatus];
		}
		//$arr = array_merge($insuranceArr,$rcArr);
		return ['count' => $count, 'doc' => array_merge($insuranceArr, $rcArr)];
	}

	public static function getListReadyApproval()
	{
		$sql = "SELECT
					vehicles.vhc_id,
					updateDocNumber
				FROM  `vehicles`
				INNER JOIN 
				(
                        SELECT	vehicle_docs.vhd_vhc_id,
						SUM(IF((vehicle_docs.vhd_file <> '' AND vehicle_docs.vhd_type IN (1, 5, 6) AND vehicle_docs.vhd_status = 0), 1,0)) AS updateDocNumber
                        FROM	`vehicle_docs`	WHERE	 vehicle_docs.vhd_active = 1 GROUP BY		vehicle_docs.vhd_vhc_id
                    ) AS doc	ON	vehicles.vhc_id = vhd_vhc_id
                    WHERE vehicles.vhc_active > 0 ORDER BY updateDocNumber DESC";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function instantReadyForApproval($vhcId, $docScore)
	{
		$userInfo = UserInfo::getInstance();
		if ($vhcId > 0)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$vmodel = VehicleStats::model()->getbyVehicleID($vhcId);
				if (!$vmodel)
				{
					$vmodel				 = new VehicleStats();
					$vmodel->vhs_vhc_id	 = $vhcId;
					$vmodel->vhs_active	 = 1;
				}
				$vmodel->vhs_doc_score = $docScore;
				if ($vmodel->validate())
				{
					if ($vmodel->save())
					{
						$success = DBUtil::commitTransaction($transaction);
						if ($success)
						{
							$updateData = "Ready for approval Vehicle ID :: " . $vmodel->vhs_vhc_id;
							Logger::create('CODE DATA ===========>: ' . $updateData, CLogger::LEVEL_INFO);
						}
					}
					else
					{
						$errors = $vmodel->getErrors();
						throw new Exception("Validation Errors [ Vehicle Stats  ] :: " . $errors);
					}
				}
				else
				{
					$errors = $vmodel->getErrors();
					throw new Exception("Validation Errors [ Vehicle Stats ] :: " . $errors);
				}
			}
			catch (Exception $ex)
			{

				DBUtil::rollbackTransaction($transaction);
				Logger::create('ERRORS =====> : ' . "Exception :" . json_encode($ex->getMessage()) . " Errors :" . json_encode($errors), CLogger::LEVEL_ERROR);
			}
		}
	}

	public function resetTempApprovedVehicles()
	{
		$sql	 = "UPDATE vehicle_docs SET vhd_temp_approved = NULL,vhd_temp_approved_at = NULL "
				. "WHERE vhd_temp_approved = 1 AND (TIMESTAMPDIFF(HOUR,vhd_temp_approved_at,NOW())>23 OR vhd_temp_approved_at IS NULL)";
		$result	 = DBUtil::command($sql)->execute();
	}

	public function addVehiclesPendingDocument($modelVehicles, $arrVehicles, $modelVehicleDocs)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($arrVehicles['vhc_reg_exp_date'] != '' || $arrVehicles['vhc_insurance_exp_date'] != '')
			{
				$modelVehicles->vhc_reg_exp_date		 = $arrVehicles['vhc_reg_exp_date'] != NULL ? $arrVehicles['vhc_reg_exp_date'] : $modelVehicles->vhc_reg_exp_date;
				$modelVehicles->vhc_insurance_exp_date	 = $arrVehicles['vhc_insurance_exp_date'] != NULL ? $arrVehicles['vhc_insurance_exp_date'] : $modelVehicles->vhc_insurance_exp_date;
				if (!$modelVehicles->update())
				{
					DBUtil::rollbackTransaction($transaction);
					return false;
				}
			}
			$uploadedFile1	 = CUploadedFile::getInstance($modelVehicleDocs, "insuranceFile");
			$uploadedFile2	 = CUploadedFile::getInstance($modelVehicleDocs, "registrationCertificateFile");
			if ($uploadedFile1 != '')
			{
				$type					 = VehicleDocs::model()->getDocType(1);
				$path1					 = $this->uploadAttachments($uploadedFile1, $type, $modelVehicles->vhc_id, "vehicles");
				$modeld					 = new VehicleDocs();
				$tempInsuranceApprove	 = $arrVehicles['vhc_temp_insurance_approved'][0] == 1 ? 1 : 0;
				$success				 = $modeld->saveDocument($modelVehicles->vhc_id, $path1, UserInfo::getInstance(), 1, $tempInsuranceApprove);
				if (!$success)
				{
					DBUtil::rollbackTransaction($transaction);
					return false;
				}
			}
			if ($uploadedFile2 != '')
			{
				$type						 = VehicleDocs::model()->getDocType(5);
				$path1						 = $this->uploadAttachments($uploadedFile2, $type, $modelVehicles->vhc_id, "vehicles");
				$modeld						 = new VehicleDocs();
				$tempRegCertificateApprove	 = $arrVehicles['vhc_temp_reg_certificate_approved'][0] == 1 ? 1 : 0;
				$success					 = $modeld->saveDocument($modelVehicles->vhc_id, $path1, UserInfo::getInstance(), 5, $tempRegCertificateApprove);
				if (!$success)
				{
					DBUtil::rollbackTransaction($transaction);
					return false;
				}
			}
			DBUtil::commitTransaction($transaction);
			return true;
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			return false;
		}
	}

	public function uploadAttachments($uploadedFile, $type, $vehicleId, $folderName)
	{
		$fileName	 = $vehicleId . "-" . $type . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}

		$dirFolderName = $dir . DIRECTORY_SEPARATOR . Config::getServerID();
		if (!is_dir($dirFolderName))
		{
			mkdir($dirFolderName);
		}

		$dirFolderName .= DIRECTORY_SEPARATOR . $folderName;
		if (!is_dir($dirFolderName))
		{
			mkdir($dirFolderName);
		}

		$dirByVehicleId = $dirFolderName . DIRECTORY_SEPARATOR . $vehicleId;
		if (!is_dir($dirByVehicleId))
		{
			mkdir($dirByVehicleId);
		}
		$foldertoupload	 = $dirByVehicleId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByVehicleId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}
		file_put_contents(file_get_contents($uploadedFile->tempName), $foldertoupload);
		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . Config::getServerID() . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $vehicleId . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function documentType()
	{
		$arr[1]	 = 'FitnessCertificate';
		$arr[2]	 = 'InsuranceInformation';
		$arr[3]	 = 'Licence';
		$arr[4]	 = 'PUCInformation';
		$arr[5]	 = 'PermitCertificate';
		$arr[6]	 = 'RcBook';
		return $arr;
	}

	public function checkdoc($vhcId, $type)
	{
		$sql	 = "SELECT vhd_id FROM `vehicle_docs` WHERE `vhd_vhc_id` = $vhcId AND `vhd_type` = $type";
		$vhdId	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		return $vhdId;
	}

	public function uploadFiles($uploadedFile)
	{
		$result			 = [];
		$success		 = false;
		$fileChecksum	 = md5_file($uploadedFile->getTempName());
		$docModel		 = $this->getRowAll($fileChecksum);
		foreach ($docModel as $value)
		{
			$docID					 = $value['vhd_id'];
			$vhcDocModel			 = VehicleDocs::model()->findByPk($docID);
			$result					 = $this->saveImage($uploadedFile, $value['vhd_vhc_id'], $value['vhd_type'], $vhcDocModel->vhd_s3_data);
			$vhcDocModel->vhd_file	 = $result['path'];
			$vhcDocModel->vhd_status = 0;
			if ($vhcDocModel->save())
			{
				$success = true;
				$message = "Saved Successfully.";
			}
			else
			{
				$message = "Not Saved.";
			}
		}
		//$message = "Invalid Checksum.";
		return ['success' => $success, 'message' => $message, 'model' => $uploadModel];
	}

	public function uploadPackages($uploadedFile, $package_type, $vehicleId)
	{
		$returnSet	 = new ReturnSet();
		$success	 = false;
		$type		 = 'packages';
		$path		 = VehicleDocs::savePackageImage($uploadedFile, $type, $vehicleId);
		$userInfo	 = UserInfo::getInstance();
		$model		 = new VehicleDocs();
		$success	 = $model->saveDocument($vehicleId, $path, UserInfo::getInstance(), $package_type, $tempRegCertificateApprove);

		if ($success == true)
		{
			$returnSet->setData($path);
			$returnSet->setMessage("Successfully saved");
			$returnSet->setStatus($success);
		}
		else
		{
			throw new Exception(json_encode($this->getErrors()), 1);
		}
		return $returnSet;
	}

	public static function savePackageImage($uploadedFile, $type, $vehicleId)
	{
		$mainRoot	 = Yii::app()->basePath;
		$dir		 = $mainRoot . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . Config::getServerID();

		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'vehicles';

		if (!is_dir($dirFolderName))
		{
			mkdir($dirFolderName);
		}
		$dirByVehicleId = $dirFolderName . DIRECTORY_SEPARATOR . $vehicleId;

		if (!is_dir($dirByVehicleId))
		{
			mkdir($dirByVehicleId);
		}
		$path		 = 'doc' . DIRECTORY_SEPARATOR . Config::getServerID() . DIRECTORY_SEPARATOR . 'vehicles' . DIRECTORY_SEPARATOR . $vehicleId;
		$file_path	 = $mainRoot . DIRECTORY_SEPARATOR . $path;

		$tempFile	 = $uploadedFile->getTempName();
		$file		 = $uploadedFile->getName();

		$file_name = $vehicleId . "-" . $type . "-" . date('YmdHis') . "-" . $uploadedFile->getName();

		$extention = strtolower($uploadedFile->getExtensionName());
		if (in_array($extention, ["jpg", "png", "jpeg", "gif"]))
		{
			Vehicles::model()->img_resize($tempFile, 1200, $file_path, $file_name);
		}
		else
		{
			$uploadedFile->saveAs($file_path . $file_name);
		}

		return DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $file_name;
		//return '/' . $path . '/' . $file_name;
	}

	public function getRowAll($fileChecksum)
	{
		$sql = "SELECT vhd_id,vhd_vhc_id,vhd_type FROM vehicle_docs WHERE vhd_checksum = '$fileChecksum' AND vhd_active = 1 ";
		return DBUtil::command($sql, DBUtil::SDB())->queryAll();
	}

	public function saveImage($uploadedFile, $vehicleId, $type, $s3dt)
	{
		$DS = DIRECTORY_SEPARATOR;
		try
		{

			$image		 = $uploadedFile->getName();
			$extention	 = strtolower($uploadedFile->getExtensionName());

			$path = "";
			if ($uploadedFile != '')
			{
				$image		 = $vehicleId . "-" . $type . "-" . date('YmdHis') . "." . $image;
				$maindir	 = $DS . 'doc' . $DS . Config::getServerID() . $DS . 'vehicles' . $DS . $vehicleId;
				$file_path	 = Yii::app()->basePath . $maindir;

				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . $DS . $file_name;
				if (file_exists($file_path) || ($s3dt != NULL && $s3dt != "{}"))
				{
					goto skipResize;
				}
				if (!is_dir($f))
				{
					mkdir($f, 0755, true);
				}
				if (in_array($extention, ["jpg", "png", "jpeg", "gif"]))
				{
					Vehicles::model()->img_resize($uploadedFile->getTempName(), 1200, $f, $file_name);
				}
				else
				{
					$uploadedFile->saveAs($file_path);
				}
				$result = ['path' => $maindir . $DS . $file_name];
//				if (Vehicles::model()->img_resize($uploadedFile->getTempName(), 1200, $f, $file_name))
//				{
//					//	$path	 = substr($file_path, strlen(PUBLIC_PATH));
//					$result = ['path' => $maindir . $DS . $file_name];
//				}
			}
		}
		catch (Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		skipResize:
		return $result;
	}

	public function createFolderPath($vhcId, $image)
	{
		try
		{
			$DS			 = DIRECTORY_SEPARATOR;
			$basePath	 = Yii::app()->basePath;
			$docPath	 = $DS . 'doc' . $DS . Config::getServerID() . $DS . 'vehicles' . $DS . $vhcId . $DS;
			if (!is_dir($docPath))
			{
				mkdir($basePath . $docPath, 0755, true);
			}
			$file_path = $docPath . $image;
		}
		catch (Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		return $file_path;
	}

//	public function updatePath($docID, $path)
//	{
//		$model				 = VehicleDocs::model()->findByPk($docID);
//		$model->vhd_file	 = $path;
//		$model->vhd_status	 = 0;
//		$model->save();
//		return $model;
//	}

	public function getBoostDocsByVhcId($vhcId)
	{
		$params	 = array("vhcId" => $vhcId);
		$sql	 = "SELECT 	vhd.vhd_id , vhd.vhd_vhc_id , vhd.vhd_file, vhd.vhd_type FROM vehicle_docs  vhd
				       
                       WHERE  vhd.vhd_vhc_id =:vhcId AND vhd.vhd_active = 1 AND vhd.vhd_type IN(8,9,10,11) AND vhd.vhd_status NOT IN(1,2)";
		$records = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $records;
	}

	public function updateAllBoostCarImages($docId, $status, $fileType, $vhcId, $remarks, $filePath)
	{
		$success	 = false;
		$userInfo	 = UserInfo::getInstance();
		$docType	 = VehicleDocs::model()->doctypeTxt;
		$fileName	 = $docType[$fileType];
		$vmodel		 = new VehicleDocs();
		if ($status == 3)
		{
			$vmodel->vhd_status = 1;
		}
		else
		{
			$vmodel->vhd_status = $status;
		}
		$vmodel->vhd_vhc_id		 = $vhcId;
		$vmodel->vhd_appoved_at	 = new CDbExpression('NOW()');
		$vmodel->vhd_approve_by	 = $userInfo->userId;
		$vmodel->vhd_type		 = $fileType;
		$vmodel->vhd_file		 = $filePath;
		$vmodel->vhd_remarks	 = ($remarks != '') ? $remarks : "";
		if ($vmodel->save())
		{
			if ($status == 1)
			{
				$descLog = "#$fileName for cab is approved";
			}
			elseif ($status == 2)
			{
				$descLog = "#$fileName for cab is rejected";
			}
			else
			{
				$descLog = "#$fileName for cab is approved but boost rejected";
			}

			if ($status == 3)
			{
				$eventId = VehiclesLog::VEHICLE_CAR_APPROVE_BOOST_REJECT;
			}
			else
			{
				$eventId = $this->checkEventId($fileType, $status);
			}

			VehiclesLog::model()->createLog($vhcId, $descLog, $userInfo, $eventId, false, false);
			$success = true;
		}
		return $success;
	}

	public function updateDocStatus($docId, $status, $fileType, $vhcId, $remarks)
	{
		$success	 = false;
		$userInfo	 = UserInfo::getInstance();
		$docType	 = VehicleDocs::model()->doctypeTxt;
		$fileName	 = $docType[$fileType];

		$vmodel					 = VehicleDocs::model()->findByPk($docId);
		$vmodel->vhd_status		 = $status;
		$vmodel->vhd_appoved_at	 = new CDbExpression('NOW()');
		$vmodel->vhd_approve_by	 = $userInfo->userId;
		$vmodel->vhd_remarks	 = ($remarks != '') ? $remarks : "";
		if ($vmodel->save())
		{
			if ($status == 1)
			{
				$descLog = "#$fileName for cab is approved";
			}
			else
			{
				$descLog = "#$fileName for cab is rejected";
			}
			$eventId	 = $this->checkEventId($fileType, $status);
			$vhcModel	 = Vehicles::model()->findByPk($vhcId);
			if ($vhcModel->save())
			{
				$success = true;
				VehiclesLog::model()->createLog($vhcId, $descLog, $userInfo, $eventId, false, false);
			}
		}
		return $success;
	}

	public function checkEventId($fileType, $status)
	{
		switch ($fileType)
		{
			case 8:
				$eventId = ($status == '1') ? VehiclesLog::VEHICLE_CAR_FRONT_APPROVE : VehiclesLog::VEHICLE_CAR_FRONT_REJECT;
				break;
			case 9:
				$eventId = ($status == '1') ? VehiclesLog::VEHICLE_CAR_BACK_APPROVE : VehiclesLog::VEHICLE_CAR_BACK_REJECT;
				break;
			case 10:
				$eventId = ($status == '1') ? VehiclesLog::VEHICLE_CAR_LEFT_APPROVE : VehiclesLog::VEHICLE_CAR_LEFT_REJECT;
				break;
			case 11:
				$eventId = ($status == '1') ? VehiclesLog::VEHICLE_CAR_RIGHT_APPROVE : VehiclesLog::VEHICLE_CAR_RIGHT_REJECT;
				break;
		}
		return $eventId;
	}

	public static function countCarVerifyDoc()
	{
		$returnSet = Yii::app()->cache->get('countCarVerifyDoc');
		if ($returnSet === false)
		{
			$sqlCount	 = "(SELECT booking_pay_docs.bpay_id
			FROM `booking_pay_docs`
			INNER JOIN booking ON booking.bkg_id = booking_pay_docs.bpay_bkg_id
			INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id
			INNER JOIN vehicles ON booking_cab.bcb_cab_id = vehicles.vhc_id
			INNER JOIN vehicle_stats ON  vehicle_stats.vhs_vhc_id = vehicles.vhc_id 
			WHERE booking_pay_docs.bpay_type IN (8,9, 10,11)
			AND booking_pay_docs.bpay_approved = 0
			AND booking_pay_docs.bpay_status = 1 AND vehicle_stats.vhs_boost_enabled=1
			GROUP BY booking_pay_docs.bpay_bkg_id) 
			UNION
			(SELECT  vhd1.vhd_id
			FROM vehicle_docs vhd1
			JOIN vehicles vhc1 ON vhc1.vhc_id = vhd1.vhd_vhc_id
			WHERE vhd1.vhd_status = 0 AND vhd1.vhd_active = 1 AND
			vhd1.vhd_file IS NOT NULL AND
			vhd1.vhd_file <> '' AND vhd1.vhd_type IN (8, 9, 10, 11)  GROUP BY vhd1.vhd_id)";
			$returnSet	 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('countCarVerifyDoc', $returnSet, 600);
		}
		return $returnSet;
	}

	public function checkExistDoc($vehicleId, $vehicleType)
	{
		$params	 = ['vehicleId' => $vehicleId, 'vehicleType' => $vehicleType];
		$sql	 = "SELECT vhd_id,vhd_vhc_id,vhd_type FROM vehicle_docs WHERE vhd_vhc_id = :vehicleId  AND vhd_type = :vehicleType AND vhd_active =1";
		$row	 = DBUtil::queryRow($sql, DBUtil::MDB(), $params);
		return $row;
	}

	public static function findExpiredDoc($id, $type = '')
	{
		$param		 = ['vhc_id' => $id];
		$type		 = ($type != '') ? '(' . implode(',', $type) . ')' : "";
		$condition	 = "AND
							(
								(CURDATE() > DATE_ADD(vehicles.vhc_insurance_exp_date, INTERVAL 15 DAY) OR vehicles.vhc_insurance_exp_date IS NULL OR vehicles.vhc_insurance_exp_date = '1970-01-01')
								 OR
								(CURDATE() > DATE_ADD(vehicles.vhc_reg_exp_date, INTERVAL 15 DAY) OR vehicles.vhc_reg_exp_date IS NULL OR vehicles.vhc_reg_exp_date = '1970-01-01')
								 OR
								(CURDATE() > DATE_ADD(vehicles.vhc_fitness_cert_end_date, INTERVAL 15 DAY) OR vehicles.vhc_fitness_cert_end_date IS NULL OR vehicles.vhc_fitness_cert_end_date = '1970-01-01')
							)";
		$sql		 = "SELECT DISTINCT vehicle_docs.vhd_id FROM `vehicle_docs` JOIN vehicles ON vhc_id = vehicle_docs.vhd_vhc_id WHERE vehicle_docs.vhd_vhc_id =:vhc_id AND vehicle_docs.vhd_active=1 $condition";
		$sql		 .= ($type != '') ? ' AND vehicle_docs.vhd_type IN ' . $type . '' : '';
		$sql		 .= "GROUP BY vehicles.vhc_id";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
	}

	/**
	 *
	 * @param type $docId
	 * @return Doc path link
	 */
	public static function getDocPathById($vhdId)
	{
		$path = '/images/no-image.png';

		$vhdModel = VehicleDocs::model()->findByPk($vhdId);
		if (!$vhdModel)
		{
			goto end;
		}
		$fieldName = "vhd_s3_data";

		$s3Data	 = $vhdModel->$fieldName;
		$imgPath = $vhdModel->getLocalPath();

		if (file_exists($imgPath) && $imgPath != $vhdModel->getBaseDocPath())
		{
			if (substr_count($imgPath, PUBLIC_PATH) > 0)
			{
				$path = substr($imgPath, strlen(PUBLIC_PATH));
			}
			else
			{
				$path = AttachmentProcessing::publish($imgPath);
			}
		}
		else if ($s3Data != '{}' && $s3Data != '')
		{
			$spaceFile	 = \Stub\common\SpaceFile::populate($s3Data);
			$path		 = $spaceFile->getURL();
			if ($spaceFile->isURLCreated())
			{
				$vhdModel->$fieldName = $spaceFile->toJSON();
				$vhdModel->save();
			}
		}
		end:
		return $path;
	}

	public static function uploadAllToS3($limit = 1000)
	{
		while ($limit > 0)
		{
			$limit1 = min([1000, $limit]);

			// Server Id
			$serverId = Config::getServerID();
			if ($serverId == '' || $serverId <= 0)
			{
				Logger::writeToConsole('Server ID not found!!!');
				break;
			}
			$condFilePath = " AND (vhd_s3_data IS NULL AND vhd_file LIKE '%/{$serverId}/vehicles/%') ";

			$sql = "SELECT vhd_id FROM vehicle_docs WHERE vhd_vhc_id > 0 {$condFilePath} ORDER BY vhd_id DESC LIMIT 0, $limit1";
			$res = DBUtil::query($sql);
			if ($res->getRowCount() == 0)
			{
				break;
			}
			foreach ($res as $row)
			{
				$vhdModel = VehicleDocs::model()->findByPk($row['vhd_id']);

				$vhdModel->uploadToS3();

				//BookingPayDocs::updateS3DataFromVhcDoc($vhdModel);

				Logger::writeToConsole($vhdModel->vhd_s3_data);
			}

			$limit -= $limit1;
			Logger::flush();
		}
	}

	/** @return Stub\common\SpaceFile */
	public function uploadToS3($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$vhdModel	 = $this;
			$path		 = $this->getLocalPath();

			if (!file_exists($path) || $this->vhd_file == '')
			{
				if ($vhdModel->vhd_s3_data == '')
				{
					$vhdModel->vhd_s3_data = "{}";
					$vhdModel->save();
				}
				return null;
			}
			$spaceFile = $vhdModel->uploadToSpace($path, $this->getSpacePath($path), $removeLocal);

			$vhdModel->vhd_s3_data = $spaceFile->toJSON();
			$vhdModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

	/**
	 * @return Stub\common\SpaceFile
	 */
	public function uploadToSpace($localFile, $spaceFile, $removeLocal = true)
	{
		$objSpaceFile = Storage::uploadFile(Storage::getVehicleDocSpace(), $spaceFile, $localFile, $removeLocal);
		return $objSpaceFile;
	}

	public function getBaseDocPath()
	{
		return PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR;
	}

	public function getLocalPath()
	{
		$filePath = $this->vhd_file;

		$filePath = implode("/", explode(DIRECTORY_SEPARATOR, $filePath));

		$filePath = ltrim($filePath, '/attachments');

		$filePath = implode(DIRECTORY_SEPARATOR, explode("/", $filePath));

		$filePath = $this->getBaseDocPath() . $filePath;

		if (!file_exists($filePath))
		{
			$filePath = APPLICATION_PATH . $this->vhd_file;
		}

		return $filePath;
	}

	public function getSpacePath($localPath)
	{
		$fileName	 = basename($localPath);
		$id			 = $this->vhd_id;
		$docType	 = $this->vhd_type;
		if ($docType == '')
		{
			$docType = 0;
		}
		$date		 = $this->vhd_created_at;
		$dateString	 = DateTimeFormat::SQLDateTimeToDateTime($date)->format("Y/m/d");
		$path		 = "/{$docType}/{$dateString}/{$id}_{$fileName}";
		return $path;
	}

	public function saveVehicleImage($uploadedFile, $vehicleId, $type, $docId)
	{
		try
		{
			$path		 = "";
			$DS			 = DIRECTORY_SEPARATOR;
			$image		 = $uploadedFile->getName();
			$imagetmp	 = $uploadedFile->getTempName();

			$expectedStr	 = str_pad($vehicleId, 8, "0", STR_PAD_LEFT);
			$arr			 = str_split($expectedStr, 2);
			$extendedPath	 = implode($DS, $arr);

			if ($image != '')
			{
				$image		 = $vehicleId . "-" . $type . "-" . $docId . "." . $image;
				$dirFinal	 = 'doc' . $DS . Config::getServerID() . $DS . 'vehicles' . $DS . $extendedPath . $DS . $vehicleId;
				$file_path	 = Yii::app()->basePath . $DS . $dirFinal;

				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . $DS . $file_name;
				if (file_exists($file_path))
				{
					goto skipResize;
				}
				if (!is_dir($f))
				{
					mkdir($f, 0755, true);
				}
				if (Vehicles::model()->img_resize($imagetmp, 1200, $f, $file_name))
				{
					$result = $DS . $dirFinal . $DS . $file_name;
				}
			}
		}
		catch (Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		skipResize:
		return $result;
	}

	public static function saveDoc($uploadedFile, $vhcId, $doctype, $userInfo)
	{
		$type	 = VehicleDocs::model()->getDocType($doctype);
		$result	 = VehicleDocs::model()->saveImage($uploadedFile, $vhcId, $type, NULL);
		$modeld	 = new VehicleDocs();
		$success = $modeld->saveDocumentNew($vhcId, $result['path'], $userInfo, $doctype);
		return $success;
	}
}
