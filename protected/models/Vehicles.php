<?php

/**
 * This is the model class for table "vehicles".
 *
 * The followings are the available columns in table 'vehicles':
 * @property integer $vhc_id
 * @property integer $vhc_type_id
 * @property string $vhc_number
 * @property string $vhc_code
 * @property integer $vhc_year
 * @property string $vhc_color
 * @property string $vhc_insurance_exp_date
 * @property string $vhc_tax_exp_date
 * @property string $vhc_dop
 * @property integer $vhc_reg_owner
 * @property string  $vhc_reg_owner_lname
 * @property integer $vhc_owned_or_rented
 * @property string $vhc_created_at
 * @property string $vhc_modified_at
 * @property integer $vhc_active
 * @property string $vhc_description
 * @property integer $vhc_is_attached
 * @property integer $vhc_overall_rating
 * @property integer $vhc_total_kms
 * @property integer $vhc_gozo_kms
 * @property string $vhc_vin
 * @property integer $vhc_total_trips
 * @property integer $vhc_last_thirtyday_trips
 * @property integer $vhc_home_city
 * @property integer $vhc_default_driver
 * @property string $vhc_log
 * The followings are the available model relations:
 * @property VehicleDriver[] $vehicleDrivers
 * @property Booking[] $bookings
 * @property VehicleTypes $vhcType
 * @property VehicleDocs[] $vehicleDocs
 * @property integer $vhc_approved_by
 * @property integer $dup_id
 * @property integer $vhc_approved
 * @property string $vhc_insurance_proof
 * @property string $vhc_temp_insurance_approved
 * @property string $vhc_permits_certificate
 * @property string $vhc_pollution_certificate
 * @property string $vhc_reg_certificate
 * @property string $vhc_temp_reg_certificate_approved
 * @property string $vhc_rear_plate
 * @property string $vhc_front_plate
 * @property string $vhc_pollution_exp_date
 * @property string $vhc_reg_exp_date
 * @property string $vhc_commercial_exp_date
 * @property string $vhc_fitness_certificate
 * @property string $vhc_fitness_cert_end_date
 * @property integer $vhc_ver_number
 * @property integer $vhc_ver_model_year_color
 * @property integer $vhc_ver_rc
 * @property integer $vhc_ver_front_license
 * @property integer $vhc_ver_rear_license
 * @property integer $vhc_ver_license_commercial
 * @property integer $vhc_ver_insurance
 * @property integer $vhc_ver_permit
 * @property integer $vhc_ver_fitness
 * @property integer $vhc_is_commercial
 * @property integer $vhc_is_freeze
 * @property integer $vhc_clean_count
 * @property integer $vhc_good_cond_count
 * @property integer $vhc_commercial_count
 * @property integer $vhc_is_uber_approved
 * @property string $vhc_trip_type
 * @property integer $vhc_end_odometer 
 * @property string $vhc_odometer_modified_on
 * @property integer $vhc_has_cng
 * @property integer $vhc_has_electric
 * @property integer $vhc_has_rooftop_carrier
 * @property BookingCab[] $bookingCabs
 * @property BookingOld[] $bookingOlds
 * @property VendorVehicle[] $vendorVehicles
 * @property VehicleContact[] $vehicleContact
 * @property VehicleStats $vhcStat

 */
class Vehicles extends CActiveRecord
{

	public $ownership_type, $drv_names, $oldAttributes	 = [];
	public $vhc_insurance_exp_date_date, $vndlist, $vht_capacity, $vhcnumber,
			$vhc_tax_exp_date_date, $vhc_vendor_id1, $vhc_vendor_id,
			$vhc_dop_date, $vhc_mark_car_count, $vhc_mark_car_status;
	public $is_attached		 = 0, $vnd_name, $vnd_names;
	public $blg_desc, $bkg_booking_id, $from_city_name, $to_city_name, $blg_remark_type, $bkg_pickup_date, $blg_created, $vhc_reset_desc;
	public $total_vehicle, $total_approved, $total_rejected, $total_pending_approval, $vhc_type, $vhc_source;
	public $vhc_photo, $vhc_vnd_owner, $vhc_temp_insurance_approved, $vhc_temp_reg_certificate_approved;
	public $vhc_back_reg_certificate, $vhc_back_temp_reg_certificate_approved;
	public $isPartitioned	 = 0, $isBoostVerify	 = 0;

	const approveStatusList = ['0' => 'Not Verified', '1' => 'Approved', '2' => 'Pending Approval (Verified)', '3' => 'Rejected', '4' => 'Approved but Papers expired', '5' => 'Boost enabled'];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vehicles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		$ydate		 = date('Y');
		$minydate	 = $ydate - 10;
		return array(
			//array('vhc_type_id, vhc_number,vhc_year,vhc_front_plate,vhc_rear_plate', 'required', 'on' => 'approve'),
			array('vhc_type_id, vhc_active', 'numerical', 'integerOnly' => true),
			array('vhc_number, vhc_color', 'length', 'max' => 100),
			array('vhc_insurance_exp_date_date, vhc_tax_exp_date,vhc_dop,vhc_dop_date,vhc_log,blg_desc,vhc_vendor_id1', 'safe'),
			// array('vhc_year', 'compare', 'operator' => '<=', 'compareValue' => $ydate, 'message' => "You can put maximum as $ydate"),
			// array('vhc_year', 'compare', 'operator' => '>=', 'compareValue' => $minydate, 'message' => "Models before $minydate are out dated"),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vhc_year,vhc_id, vhc_type_id, vhc_number,vhc_log,vhc_tax_exp_date, vhc_dop, vhc_owned_or_rented, vhc_active, vhc_description, vhc_is_attached, vhc_overall_rating, vhc_total_kms, vhc_gozo_kms, vhc_vin, vhc_total_trips, vhc_last_thirtyday_trips, vhv_log,vhc_home_city, vhc_default_driver, vhc_log,vhc_mark_car_count,vhc_mark_car_status,vhc_reset_desc', 'safe'),
			array('vhc_type_id,vhc_number', 'required', 'on' => 'insert,update'),
			array('vhc_id,vhc_approved', 'required', 'on' => 'isApprove'),
			/*  array('vhc_type_id,vhc_number,vhc_dop', 'required', 'on' => 'insert,update'),
			  array('vhc_id,vhc_approved,vhc_dop', 'required', 'on' => 'isApprove'),
			 */
			//array('vhc_has_cng,vhc_has_rooftop_carrier ', 'required', 'on' => 'insertadminapp,approveinsert,appvendoradd'),
			array('vhc_type_id,vhc_number,vhc_trip_type', 'required', 'on' => 'insertAdmin,updateAdmin'),
			//array('vhc_type_id,vhc_number,vhc_trip_type,vhc_dop', 'required', 'on' => 'insertAdmin,updateAdmin'),
			array('vhc_number, vhc_color', 'length', 'max' => 100, 'on' => 'updateAdmin'),
			array('vhc_number', 'checkDuplicate2', 'on' => 'insertAdmin,vendorAttach'),
			// array('vhc_insurance_exp_date_date', 'date', 'message' => 'Please enter valid date.', 'format' => 'dd/MM/yyyy', 'on' => 'insert,update', 'except' => 'approveinsert'),
			//// array('vhc_insurance_exp_date', 'date', 'message' => 'Please enter valid date.', 'format' => 'yyyy-MM-dd', 'on' => 'insert,update', 'except' => 'approveinsert'),
			//   array('vhc_tax_exp_date_date', 'date', 'message' => 'Please enter valid date.', 'format' => 'dd/MM/yyyy', 'on' => 'insert,update', 'except' => 'approveinsert'),
			////  array('vhc_tax_exp_date', 'date', 'message' => 'Please enter valid date.', 'format' => 'yyyy-MM-dd', 'on' => 'insert,update', 'except' => 'approveinsert'),
			//   array('vhc_dop_date', 'date', 'message' => 'Please enter valid date.', 'format' => 'dd/MM/yyyy', 'on' => 'insert,update', 'except' => 'approveinsert'),
			////  array('vhc_dop', 'date', 'message' => 'Please enter valid date.', 'format' => 'yyyy-MM-dd HH:mm:ss', 'on' => 'insert,update', 'except' => 'approveinsert'),
			array('vhc_id, vhc_dop, vhc_reg_exp_date', 'required', 'on' => 'updateApprovalRC'),
			array('vhc_id, vhc_insurance_exp_date', 'required', 'on' => 'updateApprovalIns'),
			array('vhc_number, vhc_color', 'length', 'max' => 100, 'on' => 'insert,update'),
			//array('vhc_year, vhc_dop', 'on' => 'update'),
			array('vhc_number', 'checkDuplicate', 'on' => 'approveinsert'),
			array('vhc_number', 'checkDuplicate2', 'on' => 'insert,insertadminapp'),
			array('vhc_id, vhc_code, vhc_number', 'required', 'on' => 'updateCode'),
			array('vhc_year', 'compare', 'operator' => '<=', 'compareValue' => $ydate, 'message' => "You can put maximum as $ydate", 'on' => 'update,isApprove,vendorAttach'),
			array('vhc_year', 'compare', 'operator' => '>=', 'compareValue' => $minydate, 'message' => "Models before $minydate are out dated", 'on' => 'isApprove,vendorAttach'),
			array('vhc_reset_desc', 'required', 'on' => 'reset', 'message' => 'Please enter the reason for resetting bad mark'),
			//       array('blg_desc','on'=>'reset_bad_count'),
			array('vhc_insurance_exp_date, vhc_tax_exp_date, vhc_dop,vhc_pollution_exp_date, vhc_type, vhc_reg_exp_date, vhcnumber,vhc_vendor_id,
                vhc_commercial_exp_date, vhc_fitness_cert_end_date', 'safe'),
			array('vhc_id,vhc_code,vhc_is_commercial,vhc_is_freeze, dup_id, vhc_type_id, vhc_number, vhc_year, vhc_color, vhc_reg_owner,
					vhc_tax_exp_date, vhc_dop, vhc_reg_owner_lname, vhc_owned_or_rented, vhc_active, vhc_description, vhc_is_attached, vhc_overall_rating,
					 vhc_total_kms, vhc_gozo_kms, vhc_vin, vhc_total_trips, vhc_last_thirtyday_trips, vhc_home_city, vhc_default_driver, vhc_log, vhc_mark_car_count,
					 vhc_approved, vhc_insurance_proof, vhc_permits_certificate, vhc_pollution_certificate, vhc_reg_certificate, vhc_rear_plate, vhc_front_plate,
					 vhc_pollution_exp_date, vhc_reg_exp_date, vhc_commercial_exp_date, vhc_fitness_certificate, vhc_fitness_cert_end_date,vhc_approved_by,
					vhc_ver_number,vhc_ver_model_year_color,vhc_ver_rc,vhc_ver_front_license,vhc_ver_rear_license,vhc_ver_license_commercial,vhc_ver_insurance,
					vhc_ver_permit,vhc_ver_fitness,vhc_vendor_id1,vhc_clean_count,vhc_good_cond_count,vhc_commercial_count,vhc_is_uber_approved,vhc_trip_type,
					vhc_has_cng,vhc_has_electric,vhc_has_rooftop_carrier,vhc_temp_insurance_approved,vhc_temp_reg_certificate_approved,vhc_end_odometer,vhc_odometer_modified_on', 'safe'),
			array('vhc_owned_or_rented', 'required', 'on' => 'lou'),
		);
	}

	public function checkDuplicate($attribute, $params)
	{
		$vndid = '';
		if ($this->vhc_vendor_id1 > 0)
		{
			$vndid = " AND vvhc_vnd_id = " . $this->vhc_vendor_id1;
		}
		$sql	 = "SELECT vhc_id from vehicles INNER JOIN vendor_vehicle ON vvhc_vhc_id = vhc_id WHERE vhc_number ='" . $this->$attribute . "'" . $vndid;
		$cdb	 = DBUtil::command($sql);
		$vhc_id	 = $cdb->queryScalar();
		if ($vhc_id != '')
		{
			$sqlCheck	 = "SELECT vhc_id from vehicles INNER JOIN vendor_vehicle ON vvhc_vhc_id = vhc_id WHERE vhc_id <> " . $vhc_id . " AND vhc_number = '" . $this->vhc_number . "'" . $vndid;
			$cdb		 = DBUtil::command($sqlCheck);
			$check		 = $cdb->queryScalar();
		}
		if ($check)
		{
			$this->addError($attribute, 'Cab number already exists');
			return false;
		}
		return true;
	}

	public function checkDuplicate2($attribute, $params)
	{
		$vhcNumber = '';
		if ($this->vhc_number != '')
		{
			$vhcNumber = str_replace(' ', '', trim($this->vhc_number));
		}
		if (!$vhcNumber)
		{
			$this->addError($attribute, 'Cab number not provided');
			return false;
		}
		$where = '';
		if ($this->vhc_id > 0)
		{
			$where = " AND vehicles.vhc_id<>{$this->vhc_id}";
		}
		$param		 = ['vnumber' => $vhcNumber];
		$sql		 = "SELECT REPLACE(vhc_number,' ','') vhcnumber , COUNT(1) as cnt
					FROM `vehicles` WHERE vehicles.vhc_active > 0 and  REPLACE(vhc_number,' ','')=:vnumber $where
					GROUP BY vhcnumber HAVING  cnt>0";
		$recordset	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		$data		 = array_filter($recordset);
		if ($data['cnt'] > 0)
		{
			$this->addError($attribute, 'Cab number already exists. Please check');
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array(
			'vehicleDrivers' => array(self::HAS_MANY, 'VehicleDriver', 'vhd_vehicle_id'),
			'vhcType'		 => array(self::BELONGS_TO, 'VehicleTypes', 'vhc_type_id'),
			'bookings'		 => array(self::HAS_MANY, 'Booking', 'bkg_vehicle_id'),
			//   'vhcType' => array(self::BELONGS_TO, 'VehicleTypes', 'vhc_type_id'),
			'vehicleDocs'	 => array(self::HAS_MANY, 'VehicleDocs', 'vhd_vhc_id'),
			'vendorVehicles' => array(self::HAS_MANY, 'VendorVehicle', 'vvhc_vhc_id'),
			'vehicleContact' => array(self::BELONGS_TO, 'Contact', 'vhc_owner_contact_id'),
			'vhcStat'		 => array(self::HAS_ONE, 'VehicleStats', 'vhs_vhc_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vhc_id'							 => 'Id',
			'vhc_code'							 => 'Code',
			'vhc_type_id'						 => 'Vehicle type',
			'vhc_year'							 => 'Year',
			'vhc_number'						 => 'Vehicle Number',
			'vhc_color'							 => 'Color',
			// 'vhc_vendor_id' => 'Vendor',
			'vhc_insurance_exp_date_date'		 => 'Insurance expiry date',
			'vhc_insurance_exp_date'			 => 'Insurance expiry date',
			// 'vhc_tax_exp_date_date' => 'Tax expiry date',
			'vhc_tax_exp_date'					 => 'Tax expiry date',
			'vhc_dop_date'						 => 'Date of purchase',
			'vhc_dop'							 => 'Date of purchase',
			'vhc_owned_or_rented'				 => 'Owned or rented',
			'vhc_created_at'					 => 'Created At',
			'vhc_modified_at'					 => 'Modified At',
			'vhc_vin'							 => 'VIN',
			'vhc_home_city'						 => 'City',
			'vhc_default_driver'				 => 'Default Driver',
			'vhc_active'						 => 'Active',
			'blg_desc'							 => '',
			'vhc_reset_desc'					 => 'Reset Reason',
			'vhc_front_plate'					 => 'Front Plate',
			'vhc_rear_plate'					 => 'Rear plate',
			'vhc_insurance_proof'				 => 'Insurance proof ',
			'vhc_temp_insurance_approved'		 => 'Insurance proof Temp Approved',
			'vhc_pollution_certificate'			 => 'Pollution Certificate',
			'vhc_reg_certificate'				 => 'Registration Certificate ',
			'vhc_temp_reg_certificate_approved'	 => 'Reg Certificate Temp Approved',
			'vhc_pollution_exp_date'			 => 'Pollution certificate Expiry date',
			'vhc_reg_exp_date'					 => 'Registration  Expiry date',
			'vhc_commercial_exp_date'			 => 'Commercial certificate Expiry date',
			'vhc_fitness_certificate'			 => 'Fitness certificate',
			'vhc_fitness_cert_end_date'			 => 'Fitness certificate Expiry date',
			'vhc_approved_by'					 => 'Approved By',
			'vhc_reg_owner'						 => 'Registered Owner of Vehicle',
			'vhc_reg_owner_lname'				 => 'Registered Owner Last Name of Vehicle',
			'vhc_trip_type'						 => 'Trip type',
			'vhc_end_odometer'					 => 'End Odometer',
			'vhc_odometer_modified_on'			 => 'Odometer Modified On',
			'vhcnumber'							 => 'Cab Number',
		);
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "vhc_active IN (1,2,3)",
		);
		return $arr;
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

		$criteria->compare('vhc_id', $this->vhc_id);
		$criteria->compare('vhc_type_id', $this->vhc_type_id);
		$criteria->compare('vhc_number', $this->vhc_number, true);
		$criteria->compare('vhc_color', $this->vhc_color, true);
		$criteria->compare('vhc_insurance_exp_date', $this->vhc_insurance_exp_date, true);
		$criteria->compare('vhc_tax_exp_date', $this->vhc_tax_exp_date, true);
		$criteria->compare('vhc_dop', $this->vhc_dop, true);
		$criteria->compare('vhc_owned_or_rented', $this->vhc_owned_or_rented);
		$criteria->compare('vhc_created_at', $this->vhc_created_at, true);
		$criteria->compare('vhc_modified_at', $this->vhc_modified_at, true);
		$criteria->compare('vhc_active', $this->vhc_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Vehicles the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{
		parent::beforeSave();

		if ($this->scenario != 'approve')
		{
			if ($this->vhc_insurance_exp_date !== null)
			{
				if ((date('Y-m-d', strtotime($this->vhc_insurance_exp_date)) != date($this->vhc_insurance_exp_date)))
				{
					$insuranceExpDate				 = DateTimeFormat::DatePickerToDate($this->vhc_insurance_exp_date);
					$this->vhc_insurance_exp_date	 = $insuranceExpDate;
				}
			}
			else
			{
				unset($this->vhc_insurance_exp_date);
			}
			if ($this->vhc_tax_exp_date !== null)
			{
				if ((date('Y-m-d', strtotime($this->vhc_tax_exp_date)) != date($this->vhc_tax_exp_date)))
				{
					$vhcTaxEexpDate			 = DateTimeFormat::DatePickerToDate($this->vhc_tax_exp_date);
					$this->vhc_tax_exp_date	 = $vhcTaxEexpDate;
				}
			}
			else
			{
				unset($this->vhc_tax_exp_date);
			}

			if ($this->vhc_dop !== '' && $this->vhc_dop !== null)
			{
				if ((date('Y-m-d H:i:s', strtotime($this->vhc_dop)) != date($this->vhc_dop)) && DateTimeFormat::DatePickerToDate($this->vhc_dop) !== '1970-01-01')
				{
					$vhcDop			 = DateTimeFormat::DatePickerToDate($this->vhc_dop);
					$vhcDopTime		 = date('H:i:s', strtotime($this->vhc_dop));
					$this->vhc_dop	 = $vhcDop . " " . $vhcDopTime;
				}
			}
			else
			{
				unset($this->vhc_dop);
			}
			if ($this->vhc_pollution_exp_date !== '' && $this->vhc_pollution_exp_date !== null)
			{
				if ((date('Y-m-d', strtotime($this->vhc_pollution_exp_date)) != date($this->vhc_pollution_exp_date)))
				{
					$vhcTaxEexpDate					 = DateTimeFormat::DatePickerToDate($this->vhc_pollution_exp_date);
					$this->vhc_pollution_exp_date	 = $vhcTaxEexpDate;
				}
			}
			else
			{
				unset($this->vhc_pollution_exp_date);
			}
			if ($this->vhc_reg_exp_date !== '' && $this->vhc_reg_exp_date !== null)
			{
				if ((date('Y-m-d', strtotime($this->vhc_reg_exp_date)) != date($this->vhc_reg_exp_date)))
				{
					$vhcTaxEexpDate			 = DateTimeFormat::DatePickerToDate($this->vhc_reg_exp_date);
					$this->vhc_reg_exp_date	 = $vhcTaxEexpDate;
				}
			}
			else
			{
				unset($this->vhc_reg_exp_date);
			}

			if ($this->vhc_commercial_exp_date !== '' && $this->vhc_commercial_exp_date !== null)
			{
				if ((date('Y-m-d', strtotime($this->vhc_commercial_exp_date)) != date($this->vhc_commercial_exp_date)))
				{
					$vhcTaxEexpDate					 = DateTimeFormat::DatePickerToDate($this->vhc_commercial_exp_date);
					$this->vhc_commercial_exp_date	 = $vhcTaxEexpDate;
				}
			}
			else
			{
				unset($this->vhc_commercial_exp_date);
			}

			if ($this->vhc_fitness_cert_end_date !== '' && $this->vhc_fitness_cert_end_date !== null)
			{
				if ((date('Y-m-d', strtotime($this->vhc_fitness_cert_end_date)) != date($this->vhc_fitness_cert_end_date)))
				{
					$vhcTaxEexpDate					 = DateTimeFormat::DatePickerToDate($this->vhc_fitness_cert_end_date);
					$this->vhc_fitness_cert_end_date = $vhcTaxEexpDate;
				}
			}
			else
			{
				unset($this->vhc_fitness_cert_end_date);
			}
			if ($this->vhc_insurance_proof == '')
			{
				unset($this->vhc_insurance_proof);
			}
			if ($this->vhc_front_plate == '')
			{
				unset($this->vhc_front_plate);
			}
			if ($this->vhc_rear_plate == '')
			{
				unset($this->vhc_rear_plate);
			}
			if ($this->vhc_pollution_certificate == '')
			{
				unset($this->vhc_pollution_certificate);
			}
			if ($this->vhc_reg_certificate == '')
			{
				unset($this->vhc_reg_certificate);
			}
			if ($this->vhc_permits_certificate == '')
			{
				unset($this->vhc_permits_certificate);
			}
			if ($this->vhc_fitness_certificate == '')
			{
				unset($this->vhc_fitness_certificate);
			}
			if ($this->vhc_mark_car_count == '')
			{
				$this->vhc_mark_car_count = 0;
			}
		}
		$userInfo	 = UserInfo::getInstance();
		$adminId	 = $userInfo->getUserId();

		$checkaccess = false;
		if ($adminId != "")
		{
			$checkaccess = Yii::app()->user->checkAccess('updateVehicleCNGYear');
		}

		if (!$checkaccess)
		{
			if ($this->vhc_id != '')
			{
				$model = self::findByPk($this->vhc_id);
				if ($model)
				{
					if ($model->vhc_year != '')
					{
						$nowYear		 = $this->vhc_year;
						$previousYear	 = $model->vhc_year;
						if ($previousYear < $nowYear)
						{
							$this->addError("vhc_year", "You can not change the year");
							return false;
						}
					}
					if ($model->vhc_dop != '')
					{
						$date			 = str_replace("/", "-", $this->vhc_dop);
						$nowDate		 = date("d-m-Y", strtotime($date));
						$previousDate	 = date("d-m-Y", strtotime($model->vhc_dop));
						if ($previousDate < $nowDate && $previousDate != '01-01-1970')
						{
							$this->addError("vhc_dop", "You can not change the purchase date");
							return false;
						}
					}
					if ($model->vhc_has_cng == 1)
					{
						if ($this->vhc_has_cng == 0 || $this->vhc_has_cng == '')
						{
							$this->addError("vhc_has_cng", "You can not uncheck CNG");
							return false;
						}
					}
				}
			}
		}

		return true;
	}

	/**
	 * @deprecated since version 10-10-2019
	 * @author ramala
	 */
	public function fetchList($qry = false)
	{

		$criteria			 = new CDbCriteria;
		$criteria->select	 = ["vhc_number", "vhc_year", "vhc_color", "vhc_dop",
			"vhc_mark_car_count", "group_concat(vvhcVnd.vnd_name separator ', ') as vnd_names",
			//    "group_concat(vhdDriver.drv_name separator ', ') as drv_names",
			"REPLACE(vhc_number,' ','') as number", "vhc_insurance_exp_date", "vhc_tax_exp_date", "vhc_modified_at",
			"vhc_insurance_proof", "vhc_reg_certificate", "vhc_front_plate", "vhc_rear_plate",
			"vhc_reg_exp_date", "vhc_pollution_certificate", "vhc_pollution_exp_date",
			"vhc_permits_certificate", "vhc_commercial_exp_date", "vhc_fitness_certificate",
			"vhc_fitness_cert_end_date", "vhc_approved", "vhc_is_freeze"];

		if ($this->vhc_type_id != '')
		{
			$criteria->compare('vhc_type_id', $this->vhc_type_id);
		}
		if ($this->vhc_vendor_id1 != '')
		{
			$criteria->compare('vendorVehicles.vvhc_vnd_id', $this->vhc_vendor_id1);
		}
		if ($this->vhc_year != '')
		{
			$criteria->compare('vhc_year', $this->vhc_year);
		}
		if ($this->vhc_type_id != '')
		{
			$criteria->compare('vhc_type_id', $this->vhc_type_id);
		}
		if ($this->vhc_active != '')
		{
			$criteria->compare('vhc_active', $this->vhc_active);
		}
		if ($this->vhc_approved != '')
		{
			$criteria->compare('vhc_approved', $this->vhc_approved);
		}
		if ($this->vhc_number != '')
		{
// $criteria->having=" having number LIKE '%".$this->vhc_number."%'";

			$criteria->compare('LOWER(REPLACE(vhc_number,\' \',\'\'))', strtolower(str_replace(' ', '', $this->vhc_number)), true);
		}
		if ($qry['sCtype'] != '')
		{
			$criteria->compare('vhcType.vht_fuel_type', $qry['sCtype']);
		}
		if ($qry['searchmarkvehicle'] != '')
		{
			$criteria->addCondition("vhc_mark_car_count1 > '0'");
		}
		//$criteria->compare('vhc_active', $this->vhc_active);
		$criteria->with = [//'vehicleDrivers' => ['with' => ['vhdDriver' => ['select' => 'drv_name']]],
			'vendorVehicles' => ['select' => 'vvhc_vnd_id,vvhc_vhc_id', 'with' => ['vvhcVnd' => ['select' => 'vnd_id,vnd_name']]],
			'vhcType'		 => ['select' => ['vht_fuel_type', 'vht_car_type', 'vht_make', 'vht_model', 'vht_capacity']]];

		$criteria->group = 'vhc_id';
//$criteria->order = 'vhc_created_at DESC';
		$dataProvider	 = new CActiveDataProvider(Vehicles::model()->together(), ['criteria'	 => $criteria, 'sort'		 => array(
				'attributes'	 => ['vhc_number', 'vhc_total_trips', 'vhc_dop', 'vhc_modified_at', 'vhc_mark_car_count', 'vhcType.vht_capacity', 'vhcType.vht_model'],
				'defaultOrder'	 => ['vhc_number']
			),]);
		return $dataProvider;
	}

	public function getList($qry = [], $command = false)
	{
		$select = "SELECT  
					vehicles.vhc_id,
					vehicles.vhc_color,
					vehicles.vhc_tax_exp_date,
					vehicles.vhc_mark_car_count,
					vehicles.vhc_insurance_exp_date,
					vehicles.vhc_reg_exp_date,
					vehicles.vhc_year,
					vehicles.vhc_is_freeze,
					vehicles.vhc_number,
					vehicles.vhc_approved,
					vehicles.vhc_tax_exp_date,
					vehicles.vhc_code,
				GROUP_CONCAT(DISTINCT(vendors.vnd_name) SEPARATOR ' , ') as vnd_name,vehicle_types.vht_make,
                vehicle_types.vht_model,vehicle_types.vht_capacity, vct.vct_label, 
                IF(vehicle_stats.vhs_doc_score>0,vehicle_stats.vhs_doc_score,0) AS docScore,
				IFNULL(vehicle_stats.vhs_total_trips,0) AS totaltrips,
				vehicle_stats.vhs_last_trip_date,
				vehicle_stats.vhs_boost_enabled,
				vehicle_stats.vhs_is_partition
				";

		$sql = " FROM `vehicles`
                LEFT JOIN `vehicle_types` ON vehicle_types.vht_id=vehicles.vhc_type_id
				INNER JOIN vcv_cat_vhc_type vcv ON vcv.vcv_vht_id = vehicle_types.vht_id
				INNER JOIN vehicle_category vct ON vct.vct_id = vcv.vcv_vct_id
				LEFT JOIN `vehicle_stats` ON vehicle_stats.vhs_vhc_id = vehicles.vhc_id 
				LEFT JOIN `vendor_vehicle` ON vendor_vehicle.vvhc_vhc_id=vehicles.vhc_id  AND vvhc_active=1
				LEFT JOIN `vendors` ON vendors.vnd_id = vendor_vehicle.vvhc_vnd_id AND vnd_active>0
                WHERE 1 ";

		$sqlCount = " FROM `vehicles`
                INNER JOIN `vehicle_types` ON vehicle_types.vht_id=vehicles.vhc_type_id
				INNER JOIN vcv_cat_vhc_type vcv ON vcv.vcv_vht_id = vehicle_types.vht_id
				INNER JOIN vehicle_category vct ON vct.vct_id = vcv.vcv_vct_id
				LEFT JOIN `vehicle_stats` ON vehicle_stats.vhs_vhc_id = vehicles.vhc_id 
				WHERE 1 ";

		if ($this->vhc_type_id != '')
		{
			$sql		 .= " AND vehicles.vhc_type_id=$this->vhc_type_id";
			$sqlCount	 .= " AND vehicles.vhc_type_id=$this->vhc_type_id";
		}
		if ($this->vhc_year != '')
		{
			$sql		 .= " AND vehicles.vhc_year=$this->vhc_year";
			$sqlCount	 .= " AND vehicles.vhc_year=$this->vhc_year";
		}
		if ($this->vhc_number != '')
		{
			$vhcNumber	 = str_replace(' ', '', $this->vhc_number);
			$sql		 .= " AND (REPLACE(vehicles.vhc_number, ' ', '') LIKE '%$vhcNumber%' OR REPLACE(vehicles.vhc_code, ' ', '') LIKE '%$vhcNumber%')";
			$sqlCount	 .= " AND (REPLACE(vehicles.vhc_number, ' ', '') LIKE '%$vhcNumber%' OR REPLACE(vehicles.vhc_code, ' ', '') LIKE '%$vhcNumber%')";
		}
		if (strlen($this->vhc_approved) > 0)
		{
			if ($this->vhc_approved == 5)
			{
				$sql		 .= " AND vehicle_stats.vhs_boost_enabled = 1";
				$sqlCount	 .= " AND vehicle_stats.vhs_boost_enabled = 1";
			}
			else
			{
				$sql		 .= " AND vehicles.vhc_approved = $this->vhc_approved";
				$sqlCount	 .= " AND vehicles.vhc_approved = $this->vhc_approved";
			}
		}
		if ($this->vndlist != 1)
		{
			$sql		 .= " AND vehicles.vhc_active>0";
			$sqlCount	 .= " AND vehicles.vhc_active>0";
		}

		if ($qry['searchmarkvehicle'] != '')
		{
			$sql		 .= "  AND vehicles.vhc_mark_car_count >0";
			$sqlCount	 .= "  AND vehicles.vhc_mark_car_count >0";
		}
		if ($qry['searchcngvehicle'] != '')
		{
			$sql		 .= "  AND vehicles.vhc_has_cng > 0";
			$sqlCount	 .= "  AND vehicles.vhc_has_cng > 0";
		}
		if ($qry['searchIsPartitioned'] != '')
		{
			$sql		 .= "  AND vehicle_stats.vhs_is_partition >0 ";
			$sqlCount	 .= "  AND vehicle_stats.vhs_is_partition >0 ";
		}
		if ($this->vhc_vendor_id1 != '')
		{
			$sql		 .= " AND vehicles.vhc_id IN (SELECT vvhc_vhc_id FROM vendor_vehicle WHERE vvhc_vnd_id={$this->vhc_vendor_id1} AND vvhc_active=1)";
			$sqlCount	 .= " AND vehicles.vhc_id IN (SELECT vvhc_vhc_id FROM vendor_vehicle WHERE vvhc_vnd_id={$this->vhc_vendor_id1} AND vvhc_active=1)";
		}
		if (isset($this->vhc_trip_type) && $this->vhc_trip_type != '')
		{
			$sql		 .= " AND vehicles.vhc_trip_type LIKE '%$this->vhc_trip_type%'";
			$sqlCount	 .= " AND vehicles.vhc_trip_type LIKE '%$this->vhc_trip_type%'";
		}
		if ($this->vhc_source != '')
		{
			switch ($this->vhc_source)
			{
				case 223:
					$sql		 .= " AND vehicles.vhc_id IN (
                                SELECT DISTINCT vehicles.vhc_id
                                FROM `vehicles`
                                JOIN `vehicle_types` ON vehicle_types.vht_id=vehicles.vhc_type_id AND vehicle_types.vht_active=1
                                WHERE 1
                                AND (vehicle_types.vht_model!='' OR vehicle_types.vht_model <> NULL)
                                AND (vehicles.vhc_year!='' OR vehicles.vhc_year <> NULL)
                                AND (vehicles.vhc_insurance_proof <> NULL OR vehicles.vhc_insurance_proof <>'')
                                AND (vehicles.vhc_pollution_certificate <> NULL OR vehicles.vhc_pollution_certificate <>'')
                                AND (vehicles.vhc_reg_certificate <> NULL OR vehicles.vhc_reg_certificate <>'')
                                AND (vehicles.vhc_fitness_certificate <> NULL OR vehicles.vhc_fitness_certificate <>'')
                                AND (vehicles.vhc_permits_certificate <> NULL OR vehicles.vhc_permits_certificate <>'')
                                AND vehicles.vhc_approved IN (2)
                            )";
					$sqlCount	 .= " AND vehicles.vhc_id IN (
                                SELECT DISTINCT vehicles.vhc_id
                                FROM `vehicles`
                                JOIN `vehicle_types` ON vehicle_types.vht_id=vehicles.vhc_type_id AND vehicle_types.vht_active=1
                                WHERE 1
                                AND (vehicle_types.vht_model!='' OR vehicle_types.vht_model <> NULL)
                                AND (vehicles.vhc_year!='' OR vehicles.vhc_year <> NULL)
                                AND (vehicles.vhc_insurance_proof <> NULL OR vehicles.vhc_insurance_proof <>'')
                                AND (vehicles.vhc_pollution_certificate <> NULL OR vehicles.vhc_pollution_certificate <>'')
                                AND (vehicles.vhc_reg_certificate <> NULL OR vehicles.vhc_reg_certificate <>'')
                                AND (vehicles.vhc_fitness_certificate <> NULL OR vehicles.vhc_fitness_certificate <>'')
                                AND (vehicles.vhc_permits_certificate <> NULL OR vehicles.vhc_permits_certificate <>'')
                                AND vehicles.vhc_approved IN (2)
                            )";
					break;
			}
		}
		$groupBy .= " GROUP BY vehicles.vhc_id";
		$orderBy .= " ORDER BY docScore DESC";
		$query	 = $select . $sql . $groupBy . $orderBy;

		if ($command == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(DISTINCT vehicles.vhc_id) " . $sqlCount, DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($query, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 =>
				['attributes'	 =>
					['vht_fuel_type', 'vht_make', 'vht_model', 'vht_capacity', 'vnd_name', 'total_trips'],
					'defaultOrder'	 => $defaultOrder],
				'pagination'	 => [],
			]);
			return $dataprovider;
		}
	}

	public function getCount($vhcId)
	{
		
	}

	/**
	 * @deprecated since version 10-10-2019
	 * @author ramala
	 */
	public function vehicleList()
	{
		$allVehicle	 = $this->fetchList();
		$arr		 = [];
		foreach ($allVehicle as $key => $val)
		{
			$arr[$val['vhc_id']] = $val->vhcType->vht_make . ' ' . $val->vhcType->vht_model . ' (' . $val['vhc_number'] . ')';
		}
		return $arr;
	}

	public function findById($vhcid)
	{
		return self::model()->findByAttributes(array('vhc_id' => $vhcid));
	}

	public function getIdByCode($vhcCode)
	{
		return self::model()->findByAttributes(array('vhc_code' => $vhcCode));
	}

	public function findByCode($code)
	{
		$sql = "SELECT COUNT(1) as cnt FROM `vehicles` WHERE vehicles.vhc_code='$code' AND vehicles.vhc_code>0";
		return DBUtil::command($sql)->queryScalar();
	}

	public function findByNumber($number)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('vhc_number', $number);
		$criteria->compare('vhc_id', $this->vhc_id);
		//  $criteria->compare('vhc_vendor_id', $this->vhc_vendor_id);
		return $this->find($criteria);
	}

	/**
	 * @deprecated since version 10-10-2019
	 * @author ramala
	 */
	public function getJSON()
	{
		$carList = $this->vehicleList();
		$arrCar	 = array();
		foreach ($carList as $key => $val)
		{
			$arrCar[] = array("id" => $key, "text" => $val);
		}
		$data = CJSON::encode($arrCar);
		return $data;
	}

	public function getFuelType()
	{
		$ft			 = VehicleTypes::model()->getFuelType();
		$fueltype	 = $ft[$this->vhcType->vht_fuel_type];
		return $fueltype;
	}

	public function getCarType()
	{
		$ct		 = VehicleTypes::model()->getCarType();
		$cartype = $ct[$this->vhcType->vht_VcvCatVhcType->vcv_vct_id];
		return $cartype;
	}

	public function getJSONbyVendor($vendorId)
	{
		/* @var $model Vehicles */
		$data = $this->getJSONbyTypeNVendor(0, $vendorId);
		return $data;
	}

	public function getcabDetails($vendorId)
	{
		$qry		 = "SELECT
					vehicles.vhc_id,
					vehicles.vhc_type_id,
					vehicles.vhc_number,
					vehicles.vhc_mark_car_count,
					vehicles.vhc_is_freeze,
					vehicles.vhc_color,
					vehicles.vhc_year,
					vehicles.vhc_has_cng,
                    vehicles.vhc_has_rooftop_carrier,
                    vehicle_types.vht_make,
                    vehicle_types.vht_model,
					vehicles.vhc_approved,
					IF(
						vehicles.vhc_dop = NULL,
						'',
						vehicles.vhc_dop
					) AS vhc_dop,
					vendor_vehicle.vvhc_id,
					vendor_vehicle.vvhc_digital_is_agree,
					vendor_vehicle.vvhc_vnd_id AS vhc_vendor_id,
					IF(
						vehicles_info.vhc_id IS NULL,
						0,
						1
					) AS verify_check,
					(
						vct.vct_label
					) AS vht_car_type,
						vct.vct_id,
					IF(
						(
							vehicles.vhc_owned_or_rented = 1 OR vendor_vehicle.vvhc_digital_flag = 1
						),
						1,
						0
					) AS vvhc_digital_flag,
					vendor_vehicle.vvhc_active,
					IF(vehicleDoc = 3, 0, 1) AS documentUpload
					FROM
						`vendor_vehicle`
					INNER JOIN `vehicles` ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id 
						AND vehicles.vhc_active = 1 AND vehicles.vhc_is_freeze <> 1
					LEFT JOIN
					(
						SELECT
							vehicle_docs.vhd_vhc_id,
							SUM(vehicle_docs.vhd_active) AS vehicleDoc
						FROM
							`vehicle_docs`
						WHERE
							vehicle_docs.vhd_active = 1 
							AND vehicle_docs.vhd_status IN (0,1) 
							AND vehicle_docs.vhd_type IN (1, 5, 6)
						GROUP BY
							vehicle_docs.vhd_vhc_id
					) vehicledoc
					ON
						vehicledoc.vhd_vhc_id = vehicles.vhc_id
					LEFT JOIN `vehicles_info` ON vehicles_info.vhc_vehicle_id = vehicles.vhc_id
					LEFT JOIN `vehicle_types` ON vehicle_types.vht_id = vehicles.vhc_type_id
					INNER JOIN vcv_cat_vhc_type vcvt ON vcvt.vcv_vht_id = vehicle_types.vht_id
					INNER JOIN vehicle_category vct ON vct.vct_id = vcvt.vcv_vct_id
					WHERE
						vendor_vehicle.vvhc_vnd_id = '$vendorId' AND vendor_vehicle.vvhc_active = 1 
						GROUP BY vehicles.vhc_id
					ORDER BY vehicles.vhc_id DESC";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getCabDetailsAdmin($page_no = 0, $total_count = 0, $search_txt = '', $vhc_ids = '')
	{
		$search_txt1 = str_replace(' ', '', $search_txt);
		$offset		 = $page_no * 20;
		$qry1		 = "select v1.vhc_id, v1.vhc_type_id, v1.vhc_number,
                v1.vhc_mark_car_count,REPLACE(v1.vhc_number,' ','') vhcnumber,        
                v1.vhc_color, v1.vhc_year, v1.vhc_approved,
                IF(v1.vhc_dop = NULL,'',v1.vhc_dop) as vhc_dop,
                vct_label as vht_car_type, GROUP_CONCAT(v4.vnd_name) as vhc_vnd_names
                from vehicles v1 INNER join vehicle_types v3 on v3.vht_id = v1.vhc_type_id
                INNER JOIN vcv_cat_vhc_type ON vcv_cat_vhc_type.vcv_vht_id = v3.vht_id
                INNER JOIN vehicle_category ON vcv_cat_vhc_type.vcv_vct_id = vct_id
                JOIN vendor_vehicle on vvhc_vhc_id = v1.vhc_id
                LEFT JOIN vendors v4 ON v4.vnd_id = vvhc_vnd_id
                where v1.vhc_is_freeze <> 1 and v1.vhc_active = 1";
		$qry2		 = "Select count(distinct v1.vhc_id)cnt ,REPLACE(v1.vhc_number,' ','') vhcnumber,
			    GROUP_CONCAT(v4.vnd_name) as vhc_vnd_names FROM  vehicles v1 
	            JOIN vendor_vehicle on vvhc_vhc_id = v1.vhc_id
                LEFT JOIN vendors v4 ON v4.vnd_id = vvhc_vnd_id
                where v1.vhc_is_freeze <> 1 and v1.vhc_active = 1";
		$qry		 .= ($search_txt != '') ? " AND (
				REPLACE(v1.vhc_number,' ','') LIKE '%$search_txt1%'
				OR v4.vnd_name LIKE '%$search_txt%')" : "";
		$qry		 .= ($vhc_ids != '') ? " AND v1.vhc_id IN ($vhc_ids)" : "";
		$qry		 .= ($total_count == 0) ? " GROUP BY v1.vhc_id" : "";
		$qry		 .= ($total_count == 0) ? " LIMIT 20 OFFSET $offset" : "";
		$sql		 = ($total_count == 0) ? $qry1 . $qry : $qry2 . $qry;
		$recordset	 = DBUtil::queryAll($sql);
		return $recordset;
	}

	public function getAvailabilities($vendorId)
	{
		$qry		 = "select vhc_id, vhc_type_id, vhc_number, vvhc.vvhc_vnd_id vhc_vendor_id, vehicle_types.vht_model, "
				. "vehicle_types.vht_make, cav_id, cav_cab_id, cav_from_city, cav_to_cities, "
				. "cav_driver_id, cav_status, cav_date_time, c1.cty_name as from_city, "
				. "c2.cty_name as to_city, d2.drv_name as driver_name "
				. "from vehicles "
				. " left join cab_availabilities on vhc_id = cav_cab_id AND cav_status = 1 AND NOW() < DATE_ADD(cav_date_time, INTERVAL 180 MINUTE) "
				. " left join cities c1 on c1.cty_id = cav_from_city"
				. " left join cities c2 on c2.cty_id = cav_to_cities"
				. " left join drivers d2 on d2.drv_id = cav_driver_id"
				. " join vehicle_types on vht_id = vhc_type_id"
				. " inner join vendor_vehicle vvhc on vvhc.vvhc_vhc_id = vhc_id
						where vvhc.vvhc_vnd_id  ='$vendorId'  and vhc_is_freeze <> 1 and vhc_active = 1 "
				. "order by cav_status desc";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getAvailabilitiesPerPage($vendorId, $page_no = 0)
	{
		$offset		 = $page_no * 20;
		$qry		 = "select vhc_id, vhc_type_id, vhc_number, vvhc.vvhc_vnd_id vhc_vendor_id, vehicle_types.vht_model, vehicle_types.vht_make, cav_id, cav_cab_id, "
				. "cav_from_city, cav_to_cities, cav_driver_id, cav_status, cav_amount, cav_duration, cav_date_time, "
				. "c1.cty_name as from_city, c2.cty_name as to_city, d2.drv_name as driver_name, vhc_mark_car_count "
				. "from vehicles "
				. " left join cab_availabilities on vhc_id = cav_cab_id AND cav_status = 1 AND NOW() < DATE_ADD(cav_date_time, INTERVAL 180 MINUTE) "
				. " left join cities c1 on c1.cty_id = cav_from_city"
				. " left join cities c2 on c2.cty_id = cav_to_cities"
				. " left join drivers d2 on d2.drv_id = cav_driver_id"
				. " join vehicle_types on vht_id = vhc_type_id"
				. " inner join vendor_vehicle vvhc on vvhc.vvhc_vhc_id = vhc_id
                where vvhc.vvhc_vnd_id ='$vendorId' and vhc_active = 1 order by cav_status desc LIMIT 20 OFFSET $offset";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getAvailabilitiesCount($vendorId)
	{
		$qry		 = " select COUNT(*) as cnt from vehicles "
				. " left join cab_availabilities on vhc_id = cav_cab_id "
				. " AND cav_status = 1 AND NOW() < DATE_ADD(cav_date_time, INTERVAL 180 MINUTE) "
				. " inner join vendor_vehicle vvhc on vvhc.vvhc_vhc_id = vhc_id
                where vvhc.vvhc_vnd_id  ='$vendorId' and vhc_active = 1";
		$recordset	 = DBUtil::command($qry)->queryScalar();
		return $recordset;
	}

	public function updateVehicleMarkCount($vhcId)
	{
		$sql = "UPDATE `vehicles` SET `vhc_mark_car_count`=vhc_mark_car_count+1 WHERE `vhc_id`=$vhcId";
		/* @var $cdb CDbCommand */
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function resetMarkBadByVhcId($vhcId)
	{
		if ($vhcId != '')
		{
			$model						 = new Vehicles();
			$vModel						 = $model->findByPk($vhcId);
			$vModel->vhc_id				 = $vhcId;
			$vModel->vhc_mark_car_count	 = 0;
			$vModel->update();
			return true;
		}
		return false;
	}

	public function checkVehicleMarkCount($vhcId)
	{
		$sql	 = "SELECT `vhc_mark_car_count` FROM `vehicles` WHERE `vhc_mark_car_count`>0  AND `vhc_id`=$vhcId";
		/* @var $cdb CDbCommand */
		$cdb	 = DBUtil::command($sql);
		$Search	 = $cdb->queryRow();
		$count	 = ($Search['vhc_mark_car_count']);
		return $count;
	}

	public function addVehicle($data, $vendorId, $modelData = '')
	{
		$success	 = false;
		$userInfo	 = UserInfo::getInstance();
		$model		 = $this;
		if ($modelData)
		{
			$data = (array) $modelData;
		}
		$model->attributes	 = $data;
		$model->scenario	 = 'insertadminapp';
		$transaction		 = DBUtil::beginTransaction();
		try
		{
			if ($model->validate())
			{
				if ($model->save())
				{
					$codeArr = Filter::getCodeById($model->vhc_id, "car");
					if ($codeArr['success'] == 1)
					{
						$model->vhc_code = $codeArr['code'];
						$model->save();
					}
					else
					{
						$errors = "Vehicle code not created";
						throw new Exception($errors);
					}
					$arr	 = ['vehicle' => $model->vhc_id, 'vendor' => $vendorId];
					$return	 = VendorVehicle::model()->checkAndSave($arr);
					if ($return == true)
					{
//						$vendorVehicleModel = VendorVehicle::model()->findByVndVhcId($vndID, $model->vhc_id);
//						if ($vendorVehicleModel)
//						{
//
//							$vendorVehicleModel->vvhc_active = 0;
//							$vendorVehicleModel->save();
//						}
						//check and insert in vehicle stat table
						$linked		 = VehicleStats::model()->checkAndSave($model->vhc_id);
						$desc		 = "Vehicle is Created.";
						$event_id	 = VehiclesLog::VEHICLE_CREATED;
						VehiclesLog::model()->createLog($model->vhc_id, $desc, $userInfo, $event_id, false, false);
						DBUtil::commitTransaction($transaction);
						$success	 = true;
					}
					else
					{
						$errors = "Vehicle log not created.";
						throw new Exception($errors);
					}
				}
			}
			else
			{
				$errors = $model->getErrors();
				throw new Exception(json_encode($errors));
			}
		}
		catch (Exception $e)
		{
			$errors = ($errors);
			DBUtil::rollbackTransaction($transaction);
		}
		return ['success' => $success, 'errors' => $errors];
	}

	public function addFromJson($data, $vendorId)
	{
		$success = $this->addVehicle($data, $vendorId);
		return $success;
	}

	public function getCabByVendor($vnd_id)
	{

		$criteria			 = new CDbCriteria();
		$criteria->select	 = array("vhc_id,vhc_number");
		$criteria->join		 = 'INNER JOIN  vendor_vehicle  ON vvhc_vhc_id = vhc_id';
		if ($vnd_id != '')
		{
			$criteria->condition = " vendor_vehicle.vvhc_vnd_id = $vnd_id ";
		}
		return Vehicles::model()->findAll($criteria);
	}

	public function getJSONcab($vnd_id)
	{
		$models	 = $this->getCabByVendor($vnd_id);
		$arrCabs = array();
		foreach ($models as $model)
		{
			$arrCabs[] = array("id" => $model->vhc_id, "text" => $model->vhc_number);
		}
		$data = CJSON::encode($arrCabs);
		return $data;
	}

	public function updateDetails($vhc_id = 0)
	{
		$returnset = new ReturnSet();
		try
		{
			Ratings::model()->getCarAveragerating($vhc_id);
			$whereQry1	 = $vhc_id > 0 ? " AND  cbs_vhc_id=$vhc_id " : '';
			$selectqry1	 = "SELECT  cbs_vhc_id, cbs_vhc_overall_rating FROM cab_stats WHERE 1 $whereQry1";
			$resultqry1	 = DBUtil::query($selectqry1, DBUtil::SDB());
			foreach ($resultqry1 as $val)
			{
				try
				{
					$updateqry1 = "UPDATE vehicles SET vhc_overall_rating ={$val['cbs_vhc_overall_rating']} WHERE vhc_id={$val['cbs_vhc_id']} AND vhc_active=1";
					DBUtil::execute($updateqry1);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

			$whereQry2	 = $vhc_id > 0 ? " AND  bcb_cab_id=$vhc_id " : '';
			$whereQry21	 = $vhc_id > 0 ? " AND  vhc_id=$vhc_id " : '';
			$selectqry2	 = "SELECT 
							bcb_cab_id,
							COUNT(*) AS total FROM booking
							INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
							WHERE bkg_active=1 AND  bkg_status in (5, 6, 7) AND (bkg_pickup_date)>='2015-10-25' $whereQry2
							GROUP BY bcb_cab_id";
			$resultqry2	 = DBUtil::query($selectqry2, DBUtil::SDB());
			foreach ($resultqry2 as $val)
			{
				try
				{
					$updateqry2 = "UPDATE vehicles SET vhc_total_trips = {$val['total']} WHERE vhc_id={$val['bcb_cab_id']} AND vhc_active=1 $whereQry21 ";
					DBUtil::execute($updateqry2);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

			$whereQry3	 = $vhc_id > 0 ? " and  bcb_cab_id=$vhc_id " : '';
			$whereQry31	 = $vhc_id > 0 ? " and  vhc_id=$vhc_id " : '';
			$selectqry3	 = "SELECT bcb_cab_id, COUNT(*) AS total FROM booking
							INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
							WHERE bkg_active=1 AND  bkg_status in (5, 6, 7) AND bkg_pickup_date>DATE_ADD(NOW(), INTERVAL -30 DAY) $whereQry3
							GROUP BY bcb_cab_id";
			$resultqry3	 = DBUtil::query($selectqry3, DBUtil::SDB());
			foreach ($resultqry3 as $val)
			{
				try
				{
					$updateqry3 = "UPDATE vehicles SET vhc_last_thirtyday_trips = {$val['total']} WHERE vhc_id={$val['bcb_cab_id']} AND vhc_active=1 $whereQry31";
					DBUtil::execute($updateqry3);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

			VehicleStats::model()->insertEmptyStats(0, $vhc_id);

			$whereQry4	 = $vhc_id > 0 ? " and  bcb_cab_id=$vhc_id " : '';
			$whereQry41	 = $vhc_id > 0 ? " and  vhs_vhc_id=$vhc_id " : '';
			$selectqry4	 = "SELECT
							booking_cab.bcb_cab_id,
							COUNT(DISTINCT booking.bkg_id) AS totalTrips,
                            MAX(booking.bkg_pickup_date) AS lastTripDate,
                            
                            SUM(IF(bkg_booking_type=1,1,0)) AS OW_Count,
                            SUM(IF(bkg_booking_type IN (2,3),1,0)) AS RT_Count,
                            SUM(IF(bkg_booking_type=4,1,0)) AS AT_Count,
                            SUM(IF(bkg_booking_type=5,1,0)) AS PT_Count,
                            SUM(IF(bkg_booking_type=6,1,0)) AS FL_Count,
                            SUM(IF(bkg_booking_type=7,1,0)) AS SH_Count,
                            SUM(IF(bkg_booking_type=8,1,0)) AS CT_Count,
                            SUM(IF(bkg_booking_type=9,1,0)) AS DR_4HR_Count,
                            SUM(IF(bkg_booking_type=10,1,0)) AS DR_8HR_Count,
                            SUM(IF(bkg_booking_type=11,1,0)) AS DR_12HR_Count,
                            SUM(IF(bkg_booking_type=12,1,0)) AS AP_Count
							FROM  `booking_cab`
							INNER JOIN `booking` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking.bkg_active = 1 AND booking.bkg_status IN (5, 6, 7)
							WHERE (booking.bkg_pickup_date) >= '2015-10-25' AND booking_cab.bcb_active = 1  AND booking_cab.bcb_cab_id IS NOT NULL  $whereQry4
							GROUP BY  booking_cab.bcb_cab_id";
			$resultqry4	 = DBUtil::query($selectqry4, DBUtil::SDB());
			foreach ($resultqry4 as $val)
			{
				try
				{
					$updateqry4 = "UPDATE `vehicle_stats` 
                                SET 
                                vhs_total_trips ={$val['totalTrips']}
                                ,vhs_last_trip_date = '{$val['lastTripDate']}'
                                ,vehicle_stats.vhs_OW_Count ='{$val['OW_Count']}'
                                ,vehicle_stats.vhs_RT_Count ='{$val['RT_Count']}'
                                ,vehicle_stats.vhs_AT_Count ='{$val['AT_Count']}'
                                ,vehicle_stats.vhs_PT_Count ='{$val['PT_Count']}'
                                ,vehicle_stats.vhs_FL_Count ='{$val['FL_Count']}'
                                ,vehicle_stats.vhs_SH_Count ='{$val['SH_Count']}'
                                ,vehicle_stats.vhs_CT_Count ='{$val['CT_Count']}'
                                ,vehicle_stats.vhs_DR_4HR_Count ='{$val['DR_4HR_Count']}'
                                ,vehicle_stats.vhs_DR_8HR_Count ='{$val['DR_8HR_Count']}'
                                ,vehicle_stats.vhs_DR_12HR_Count ='{$val['DR_12HR_Count']}'
                                ,vehicle_stats.vhs_AP_Count ='{$val['AP_Count']}' WHERE  vhs_vhc_id=  {$val['bcb_cab_id']} AND  1 $whereQry41 ";
					DBUtil::execute($updateqry4);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

			$returnset->setStatus(true);
			$returnset->setMessage("Vehicle Statistical Data Update Successfully");
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$returnset->setStatus(false);
			$returnset->setMessage("Unable To Update Vehicle Statistical Data");
		}
		return $returnset;
	}

	public function addLog($oldData, $newData)
	{
		if ($oldData)
		{
			$getDifference	 = array_diff_assoc($oldData, $newData);
			$remark			 = $this->vhc_log;
			$dt				 = date('Y-m-d H:i:s');
			$user			 = Yii::app()->user->getId();

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

	public function markedBadListByVehicleId($vhcId)
	{

		$Val			 = '"';
		$sql			 = "SELECT 
							a.`blg_created`, 
							a.`blg_desc`,
							a.`blg_remark_type`,
							b.`bkg_booking_id`,
							b.`bkg_pickup_date`
							,REPLACE(JSON_EXTRACT(b.`bkg_route_city_names`, '$[0]'), '$Val', '')  AS from_city_name
							,REPLACE(JSON_EXTRACT(b.`bkg_route_city_names`, CONCAT('$[', JSON_LENGTH(`bkg_route_city_names`) - 1, ']')),'$Val','') AS to_city_name
							FROM `booking` b   
							JOIN booking_log a  ON a.blg_booking_id = b.bkg_id AND a.blg_mark_car > 0
							JOIN `vehicles` c ON c.vhc_id = a.blg_vehicle_assigned_id 
							WHERE  1  and   a.blg_vehicle_assigned_id=$vhcId";
		$sqlCount		 = "SELECT b.`bkg_booking_id`
							FROM `booking` b   
							JOIN booking_log a  ON a.blg_booking_id = b.bkg_id AND a.blg_mark_car > 0
							JOIN `vehicles` c ON c.vhc_id = a.blg_vehicle_assigned_id
							WHERE  1  and   a.blg_vehicle_assigned_id=$vhcId";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) a")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_booking_id', 'blg_remark_type', 'blg_desc'],
				'defaultOrder'	 => 'blg_created DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getCode($vendorId)
	{
		return Yii::app()->shortHash->hash($vendorId);
	}

	public function checkVerifiedByvendor($id)
	{
		$model = VehiclesInfo::model()->find('vhc_vehicle_id=:id', ['id' => $id]);
		if ($model != '')
		{
			return true;
		}
		return false;
	}

	/**
	 * @deprecated since version 11-10-2019
	 * @author ramala
	 */
	public function listToVerify()
	{

		$criteria			 = new CDbCriteria;
		$criteria->select	 = ["vhc_number", "vhc_year",
			"group_concat(vvhcVnd.vnd_name separator ', ') as vnd_names",
			"vvhcVnd.vnd_id as vhc_vendor_id1",
			"vhc_color", "vhc_dop", "vhc_mark_car_count",
			"group_concat(vhdDriver.drv_name separator ', ') as drv_names",
			"REPLACE(vhc_number,' ','') as number", "vhc_approved"];

		if ($this->vhc_type_id != '')
		{
			$criteria->compare('vhc_type_id', $this->vhc_type_id);
		}
		if ($this->vendorVehicles->vvhc_vnd_id != '')
		{
			$criteria->compare('vendorVehicles.vvhc_vnd_id', $this->vendorVehicles->vvhc_vnd_id);
		}
		if ($this->vhc_vendor_id1 != '')
		{
			$criteria->compare('vendorVehicles.vvhc_vnd_id', $this->vhc_vendor_id1);
		}
		if ($this->vhc_year != '')
		{
			$criteria->compare('vhc_year', $this->vhc_year);
		}
		if ($this->vhc_type_id != '')
		{
			$criteria->compare('vhc_type_id', $this->vhc_type_id);
		}
		if ($this->vhc_active != '')
		{
			$criteria->compare('vhc_active', $this->vhc_active);
		}
		if ($this->vhc_number != '')
		{
// $criteria->having=" having number LIKE '%".$this->vhc_number."%'";

			$criteria->compare('LOWER(REPLACE(vhc_number,\' \',\'\'))', strtolower(str_replace(' ', '', $this->vhc_number)), true);
		}
		$criteria->compare('vhc_active', $this->vhc_active);
		$criteria->with = ['vehicleDrivers' => ['with' => ['vhdDriver' => ['select' => 'drv_name']]], 'vendorVehicles' => ['with' => ['vvhcVnd' => ['select' => 'vnd_name']], 'select' => ['vvhc_vnd_id']], 'vhcType' => ['select' => ['vht_fuel_type', 'vht_car_type', 'vht_make', 'vht_model', 'vht_capacity']]];

		$criteria->together	 = true;
		$criteria->group	 = 'vhc_id';
		$dataProvider		 = new CActiveDataProvider(Vehicles::model()->together(), ['criteria'	 => $criteria, 'sort'		 => array(
				'attributes'	 => ['vhc_number', 'vhc_dop', 'vhc_mark_car_count', 'vhcType.vht_capacity', 'vhcType.vht_model'],
				'defaultOrder'	 => ['vhc_number']
			),]);
		return $dataProvider;
	}

	function img_resize($tmpname, $size, $save_dir, $save_name, $maxisheight = 0)
	{
		$arr		 = array();
		$save_dir	 .= ( substr($save_dir, -1) != DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : "";
		$arr[1]		 = $save_dir;
		$gis		 = getimagesize($tmpname);
		$arr[21]	 = $tmpname;
		$type		 = $gis[2];
		$arr[2]		 = $gis;
		switch ($type)
		{
			case "1": $imorig	 = imagecreatefromgif($tmpname);
				break;
			case "2": $imorig	 = imagecreatefromjpeg($tmpname);
				break;
			case "3": $imorig	 = imagecreatefrompng($tmpname);
				break;
			default: $imorig	 = imagecreatefromjpeg($tmpname);
		}
		$x	 = imagesx($imorig);
		$y	 = imagesy($imorig);
		$woh = (!$maxisheight) ? $gis[0] : $gis[1];
		if ($woh <= $size)
		{
			$aw	 = $x;
			$ah	 = $y;
		}
		else
		{
			if (!$maxisheight)
			{
				$aw	 = $size;
				$ah	 = $size * $y / $x;
			}
			else
			{
				$aw	 = $size * $x / $y;
				$ah	 = $size;
			}
		}
		$im = imagecreatetruecolor($aw, $ah);
		if (imagecopyresampled($im, $imorig, 0, 0, 0, 0, $aw, $ah, $x, $y))
		{
			if (imagejpeg($im, $save_dir . $save_name))
			{
				Yii::log("Image Resampled: " . $save_dir . $save_name, CLogger::LEVEL_INFO, 'system.api.images');

				return true;
			}
			else
			{
				return false;
			}
		}
	}

	/**
	 * @deprecated since version 09-10-2019
	 * @author ramala
	 */
	public function getVehicleModel($vhcTypeId)
	{
		$qry		 = "SELECT v1.vht_id,v1.vht_model FROM `vehicle_types` v1 INNER JOIN `vehicle_types` v2 ON v2.vht_car_type = v1.vht_parent_id and v2.vht_parent_id=0 WHERE v2.vht_id=$vhcTypeId";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function checkExisting($qry1 = [])
	{
		$qry = array_filter($qry1);
		if (sizeof($qry) > 0)
		{
			$where		 = '';
			$vnumber	 = trim(str_replace(' ', '', $qry['vhc_number']));
			$param		 = ['vnumber' => $vnumber];
			$sql		 = "SELECT 
							MIN(vhc_id) vhc_id,
							REPLACE(vhc_number,' ','') vhcnumber,
							COUNT(vvhc_vhc_id) assigned, 
							GROUP_CONCAT(vvhc_vnd_id) vendorids
							FROM `vehicles`  vhc 
							LEFT JOIN vendor_vehicle vvhc on vhc.vhc_id = vvhc.vvhc_vhc_id 
							WHERE REPLACE(vhc_number,' ','')=:vnumber
                            AND vhc.vhc_active = 1
                            AND vvhc.vvhc_active = 1
							GROUP BY vhcnumber";
			$recordset	 = DBUtil::queryAll($sql, DBUtil::SDB(), $param);
			$data		 = array_filter($recordset);
			return $data;
		}
		return false;
	}

	public function checkExistingVehicle($qry = [])
	{

		$returnData	 = ['success' => 1, 'vnd_ids' => NULL, 'msg' => ''];
		$qry		 = array_filter($qry);
		if (sizeof($qry) > 0)
		{
			$vendorId	 = $qry['vhc_vendor_id'];
			$vnumber	 = trim(str_replace(' ', '', $qry['vhc_number']));
			$vid		 = $qry['vhc_vendor_id'];
			$sql		 = "select * from (SELECT  REPLACE(vhc_number,' ','') vhcnumber,
                    count(vvhc_vhc_id) assigned, GROUP_CONCAT(vvhc_vnd_id SEPARATOR ',') vendorids
                    FROM vehicles vhc
                    JOIN vendor_vehicle vvhc on vhc.vhc_id = vvhc.vvhc_vhc_id
                    WHERE vvhc.vvhc_vnd_id  ='$vendorId' and REPLACE(vhc_number,' ','')='$vnumber'
                    GROUP BY vhcnumber) temp where  temp.assigned>0";
			$recordset	 = DBUtil::queryRow($sql);
			$data		 = array_filter($recordset);
			if ($data['assigned'] > 0)
			{
				$returnData = ['success' => 0, 'vnd_ids' => $data['vendorids'], 'msg' => 'Car already exists'];
			}
		}
		return $returnData;
	}

	public function getJSONbyTypeNVendor($typeID = 0, $vendor = '')
	{
		$sql	 = "SELECT vvhc_vhc_id, vhc_id, vht1.vht_make, vht1.vht_model, upper(vhc_number) vhc_number,vhc_is_freeze,vhc_total_trips,vhc_approved
                FROM vendor_vehicle
						JOIN vehicles vhc on vvhc_vhc_id = vhc_id AND vhc.vhc_active = 1
						JOIN vehicle_types vht1 on vht1.vht_id = vhc_type_id AND vht1.vht_active = 1
                WHERE vvhc_vnd_id  ='$vendor'";
		$cars	 = DBUtil::queryAll($sql);
		$arr	 = [];
		foreach ($cars as $val)
		{
			$isBlocked			 = ($val['vhc_is_freeze'] == 0) ? '' : '(Blocked)';
			$arr[$val['vhc_id']] = $val['vht_make'] . ' ' . $val['vht_model'] . ' (' . $val['vhc_number'] . ')' . $isBlocked;
		}
		return $arr;
	}

	public function getVehiclebyType($typeID = 0, $vendor = '')
	{
		$cond = "";
		if ($typeID != 0)
		{
			$cond = " AND vhc_type_id='" . $typeID . "' ";
		}
		if ($vendor != '')
		{
			$cond .= " AND vendorVehicles.vvhc_vnd_id ='$vendor' ";
		}
//		
		$sql = "SELECT  `t`.`vhc_id` as id, CONCAT(`vhcType`.`vht_make`,' ', `vhcType`.`vht_model`,' (',`t`.`vhc_number`,')') as text
                FROM     `vehicles` `t`
                INNER JOIN `vehicle_types` `vhcType` ON (`t`.`vhc_type_id` = `vhcType`.`vht_id`) AND (vht_active = 1)
                INNER JOIN `vendor_vehicle` `vendorVehicles` ON (`vendorVehicles`.`vvhc_vhc_id` = `t`.`vhc_id`)
                WHERE   (vhc_active = 1) AND (vhc_is_freeze != 1) $cond
                ORDER BY vhcType.vht_make, vhcType.vht_model";

		return DBUtil::queryAll($sql);
	}

	public function getExistingDetails()
	{
		$data = $this->checkExisting($this->attributes);
		return $data[0];
	}

	public function getDetailListbyId($vhcid)
	{
		$cabData = [];
		if ($vhcid > 0)
		{
			$sql	 = 'SELECT * from ' . $this->tableName() . ' WHERE vhc_id = ' . $vhcid;
			$cabData = DBUtil::queryRow($sql);
		}
		return $cabData;
	}

	public function updateAttachmentPath($vhcId, $field, $value, $tableName = '')
	{
		$table	 = ($tableName == '') ? $this->tableName : $tableName;
		$sql	 = "UPDATE $table SET $field='$value' WHERE `vhc_id`=$vhcId";
		return DBUtil::command($sql)->execute();
	}

	public function getApproveStatus()
	{
		$arrJSON = array();
		$arr	 = ['0' => 'Not Verified', '1' => 'Approved', '2' => 'Pending Approval (Verified)', '3' => 'Rejected', '4' => 'Approved but Papers expired', '5' => 'Boost enabled'];
		foreach ($arr as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		return CJSON::encode($arrJSON);
	}

	public function updatCleanCount($vhcId)
	{
		$sql = "UPDATE `vehicles` SET vehicles.vhc_clean_count=(vehicles.vhc_clean_count+1) WHERE vehicles.vhc_id=$vhcId";
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function updatGoodConditionCount($vhcId)
	{
		$sql = "UPDATE `vehicles` SET vehicles.vhc_good_cond_count=(vehicles.vhc_good_cond_count+1) WHERE vehicles.vhc_id=$vhcId";
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function updatCommercialCount($vhcId)
	{
		$sql = "UPDATE `vehicles` SET vehicles.vhc_commercial_count=(vehicles.vhc_commercial_count+1) WHERE vehicles.vhc_id=$vhcId";
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function getLowRatingList()
	{
		$sql = "SELECT DISTINCT booking_cab.bcb_cab_id
                FROM `vehicles`
                INNER JOIN `booking_cab` ON booking_cab.bcb_cab_id=vehicles.vhc_id AND booking_cab.bcb_active=1
                WHERE vehicles.vhc_overall_rating < 2.5
                AND vehicles.vhc_is_freeze=0";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function saveForUndertakingCopy($vvhcId)
	{
		try
		{
			$success	 = false;
			$host		 = Yii::app()->params['host'];
			$baseURL	 = Yii::app()->params['fullBaseURL'];
			$filterObj	 = new Filter();

			/* @var $model VendorVehicle */
			$model	 = VendorVehicle::model()->findByPk($vvhcId);
			$vhcId	 = $model->vvhc_vhc_id;
			$version = $model->vvhc_vnd_id . $model->vvhc_vhc_id;
			$url	 = $baseURL . '/admpnl/vehicle/generateAgreementForVehicle?vvhcId=' . $vvhcId . '&ds=1';

			$url				 = str_replace('./', '', $url);
			$agreement			 = $this->file_get_contents_curl($url);
			$myfile				 = fopen(PUBLIC_PATH . "/attachments/vehicles/$vhcId/digitalAgreement_" . $version . ".pdf", "w");
			fwrite($myfile, $agreement);
			fclose($myfile);
			$digitalAgreementUrl = "/attachments/vehicles/$vhcId/digitalAgreement_{$version}.pdf";
			$fileArray			 = [0 => ['PATH' => PUBLIC_PATH . "/attachments/vehicles/$vhcId/digitalAgreement_" . $version . ".pdf"]];
			$attachments		 = json_encode($fileArray);
			Logger::create($attachments, CLogger::LEVEL_TRACE);
			$url				 = $baseURL . '/admpnl/vehicle/generateAgreementForVehicle?vvhcId=' . $vvhcId . '&ds=0';
			$url				 = str_replace('./', '', $url);
			$agreement			 = $this->file_get_contents_curl($url);
			$myfile				 = fopen(PUBLIC_PATH . "/attachments/vehicles/$vhcId/draftAgreement_" . $version . ".pdf", "w");
			fwrite($myfile, $agreement);
			fclose($myfile);
			$draftAgreement		 = "/attachments/vehicles/$vhcId/draftAgreement_{$version}.pdf";
			$fileArray2			 = [0 => ['PATH' => PUBLIC_PATH . "/attachments/vehicles/$vhcId/draftAgreement_" . $version . ".pdf"]];
			$attachments2		 = json_encode($fileArray2);
			Logger::create($attachments2, CLogger::LEVEL_TRACE);

			if ($digitalAgreementUrl != '' && $draftAgreement != '')
			{
				$desc							 = 'Undertaking Copy Digital -> ' . $digitalAgreementUrl . 'Undertaking Copy Draft -> ' . $draftAgreement;
				Logger::create($desc, CLogger::LEVEL_TRACE);
				$model->vvhc_digital_undertaking = $digitalAgreementUrl;
				$model->vvhc_draft_undertaking	 = $draftAgreement;
				if ($model->save())
				{
					Logger::create("Undertaking Copy has been created", CLogger::LEVEL_INFO);
					$success = true;
				}
				else
				{
					throw new Exception("Undertaking creation failed.\n\t\t" . json_encode($model->getErrors()));
				}
			}
			else
			{
				throw new Exception("Undertaking creation failed. Digital or draft agreement not created. \n");
			}
		}
		catch (Exception $e)
		{
			Logger::create("Undertaking not sent.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
		}
		return $success;
	}

	public function getExpriedPapersList()
	{
		$sql = "SELECT 	DISTINCT vehicle_docs.vhd_id, vendor_ids
                FROM `vehicle_docs`
                INNER JOIN `vehicles` ON vehicles.vhc_id=vehicle_docs.vhd_vhc_id
                AND
                (
                    ((CURDATE() > vehicles.vhc_insurance_exp_date OR vehicles.vhc_insurance_exp_date = '1970-01-01' OR vehicles.vhc_insurance_exp_date IS NULL) AND (vehicle_docs.vhd_type IN (1)))
                     OR
                    (CURDATE() > vehicles.vhc_pollution_exp_date AND vehicles.vhc_pollution_exp_date <> '1970-01-01' AND vehicle_docs.vhd_type IN (4))
                     OR
                    ((CURDATE() > vehicles.vhc_reg_exp_date OR vehicles.vhc_reg_exp_date = '1970-01-01' OR vehicles.vhc_reg_exp_date IS NULL) AND (vehicle_docs.vhd_type IN (5)))
                     OR
                    (CURDATE() > vehicles.vhc_commercial_exp_date AND vehicles.vhc_commercial_exp_date <> '1970-01-01' AND vehicle_docs.vhd_type IN (6))
                     OR
                    ((CURDATE() > vehicles.vhc_fitness_cert_end_date OR vehicles.vhc_fitness_cert_end_date = '1970-01-01' OR vehicles.vhc_fitness_cert_end_date IS NULL) AND (vehicle_docs.vhd_type IN (7)))
                )
                JOIN
                (
                    SELECT vendor_vehicle.vvhc_vhc_id,
                    GROUP_CONCAT(DISTINCT vendor_vehicle.vvhc_vnd_id SEPARATOR ',') as vendor_ids
                    FROM `vendor_vehicle`
                    INNER JOIN `vendors` ON vendors.vnd_id=vendor_vehicle.vvhc_vnd_id AND vendors.vnd_active=1 AND vvhc_active = 1 
                    GROUP BY vendor_vehicle.vvhc_vhc_id
                ) vhc ON vhc.vvhc_vhc_id=vehicles.vhc_id
                WHERE vehicle_docs.vhd_active=1
                AND vehicle_docs.vhd_status=1
                AND vehicles.vhc_approved IN (0,1)";
		return DBUtil::queryAll($sql);
	}

	public function getLastTripNextTripByVhcId($vehicle_id)
	{

		$sql = "SELECT last_trip_vendor_id, last_trip_ven_name, last_trip_ven_phone, last_trip,
                next_trip_vendor_id, next_trip_ven_name, next_trip_ven_phone, next_trip
                FROM (
                    SELECT booking_cab.bcb_vendor_id as last_trip_vendor_id, v1.vnd_name as last_trip_ven_name, v1.vnd_phone as last_trip_ven_phone, booking_cab.bcb_id as last_trip
                    FROM `booking_cab`
                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id
                    INNER JOIN `vendors` ON vendors.vnd_id=booking_cab.bcb_vendor_id
					
                    INNER JOIN `vendors` v1  ON v1.vnd_id=vendors.vnd_ref_code
                    WHERE booking.bkg_pickup_date IN (
                        SELECT MAX(booking.bkg_pickup_date)
                        FROM `booking_cab`
                        INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id
                        AND booking.bkg_active=1
                        AND booking_cab.bcb_active=1
                        WHERE booking.bkg_pickup_date < NOW() AND booking_cab.bcb_cab_id IN ($vehicle_id)
                        GROUP BY booking_cab.bcb_cab_id
                    ) AND booking_cab.bcb_cab_id IN ($vehicle_id)
                ) a,
                (

                    SELECT booking_cab.bcb_vendor_id as next_trip_vendor_id, v1.vnd_name as next_trip_ven_name, v1.vnd_phone as next_trip_ven_phone,  booking_cab.bcb_id as next_trip
                    FROM `booking_cab`
                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id
                    INNER JOIN `vendors` ON vendors.vnd_id=booking_cab.bcb_vendor_id
					INNER JOIN `vendors` v1  ON v1.vnd_id=vendors.vnd_ref_code
                    WHERE booking.bkg_pickup_date IN
                    (
                        SELECT MIN(booking.bkg_pickup_date)
                        FROM `booking_cab`
                        INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id
                        AND booking.bkg_active=1
                        AND booking_cab.bcb_active=1
                        WHERE booking.bkg_pickup_date > NOW() AND booking_cab.bcb_cab_id IN ($vehicle_id)
                        GROUP BY booking_cab.bcb_cab_id
                    ) AND booking_cab.bcb_cab_id IN ($vehicle_id)
                )b";
		return DBUtil::queryRow($sql);
	}

	public function getAutoRemovelList()
	{
		$sql = "SELECT LEFT(missing,length(missing)-1) as missing_docs , LEFT(missing_ids,length(missing_ids)-1) as missing_ids ,
                interval_date, reg_exp_date, vhc_id ,pollution_exp_date, insurance_exp_date, fitness_cert_end_date, commercial_exp_date, vendorIds, vhc_number
                FROM
                (
                    SELECT CONCAT(
                                IF(reg_exp_date!='' AND interval_date > reg_exp_date,'Registration ,',''),
                                IF(insurance_exp_date!='' AND interval_date > insurance_exp_date,'Insurance ,',''),
                                IF(fitness_cert_end_date!='' AND interval_date > fitness_cert_end_date,'Fitness ,',''),
                                IF(commercial_exp_date!='' AND interval_date > commercial_exp_date,'Permit ','')
                            ) as missing,
                            CONCAT(
                                IF(insurance_exp_date!='' AND interval_date > insurance_exp_date,'1,',''),
                                IF(reg_exp_date!='' AND interval_date > reg_exp_date,'5,',''),
                                IF(commercial_exp_date!='' AND interval_date > commercial_exp_date,'6,',''),
                                IF(fitness_cert_end_date!='' AND interval_date > fitness_cert_end_date,'7,','')
                            ) as missing_ids,
                            interval_date ,
                            reg_exp_date,vhc_id ,
                            pollution_exp_date,
                            insurance_exp_date,
                            fitness_cert_end_date,
                            commercial_exp_date,
                            vendorIds,
                            vhc_number
                            FROM
                            (
                                SELECT
                                vehicles.vhc_id,
                                DATE(DATE_ADD(NOW(), INTERVAL 10 DAY)) AS interval_date,
                                vehicles.vhc_number,
                                IFNULL(vehicles.vhc_reg_exp_date,'') as reg_exp_date,
                                IFNULL(vehicles.vhc_pollution_exp_date,'') as pollution_exp_date,
                                IFNULL(vehicles.vhc_insurance_exp_date,'') as insurance_exp_date,
                                IFNULL(vehicles.vhc_fitness_cert_end_date,'') as fitness_cert_end_date,
                                IFNULL(vehicles.vhc_commercial_exp_date,'') as commercial_exp_date,
                                vendorIds
                                FROM
                                    `vehicles`
                                LEFT JOIN(
                                    SELECT vendor_vehicle.vvhc_vhc_id,
                                    GROUP_CONCAT(vendor_vehicle.vvhc_vnd_id SEPARATOR ',') as vendorIds
                                    FROM  `vendor_vehicle`
                                    INNER JOIN `vendors` ON vendors.vnd_id=vendor_vehicle.vvhc_vnd_id
                                    WHERE vendor_vehicle.vvhc_active=1  GROUP BY vendor_vehicle.vvhc_vhc_id
                                )vhc ON vhc.vvhc_vhc_id=vehicles.vhc_id
                                WHERE 1 AND(
                                        DATE_ADD(NOW(), INTERVAL 10 DAY) > vehicles.vhc_reg_exp_date AND vehicles.vhc_reg_exp_date IS NOT NULL AND vehicles.vhc_reg_exp_date != '1970-01-01'
                                        OR DATE_ADD(NOW(), INTERVAL 10 DAY) > vehicles.vhc_insurance_exp_date AND vehicles.vhc_insurance_exp_date IS NOT NULL AND vehicles.vhc_insurance_exp_date != '1970-01-01'
                                        OR DATE_ADD(NOW(), INTERVAL 10 DAY) > vehicles.vhc_fitness_cert_end_date AND vehicles.vhc_fitness_cert_end_date IS NOT NULL AND vehicles.vhc_fitness_cert_end_date != '1970-01-01'
                                        OR DATE_ADD(NOW(), INTERVAL 10 DAY) > vehicles.vhc_commercial_exp_date AND vehicles.vhc_commercial_exp_date IS NOT NULL AND vehicles.vhc_commercial_exp_date != '1970-01-01'
                                    ) AND vehicles.vhc_approved = 1
                            ) a
                    )b HAVING missing_docs!=''";
		return DBUtil::queryAll($sql);
	}

	public function getDetailsById($id = 0)
	{
		if ($id > 0)
		{
			$qry	 = " AND vendor_vehicle.vvhc_vhc_id =$id";
			$qry1	 = " AND booking_cab.bcb_cab_id=$id";
		}
		$sql = "SELECT
                vehicles.vhc_id,
				vehicles.vhc_number,
                vehicles.vhc_approved_by,
				vehicles.vhc_trip_type,
				vehicles.vhc_is_freeze,
                vhs.vhs_is_partition,
                vhs.vhs_boost_verify,
				vhs.vhs_boost_enabled,
				vhs.vhs_boost_approved_date,
				vhs.vhs_boost_expiry_date,
                rtg_car_clean,
                rtg_car_good_cond,
                rtg_car_commercial,
                rtg_customer_car,
                last_trip_pickup_date,
                last_pickup_date,
				vct.vct_label as label,
				service_class.scc_label,
                countRating,
                CONCAT(admins.adm_fname,' ',admins.adm_lname) as approve_by_name,
                (CASE vehicles.vhc_approved
                 WHEN 0 THEN 'Not Verified'
                 WHEN 1 THEN 'Approved'
                 WHEN 2 THEN 'Pending Approval'
                 WHEN 3 THEN 'Rejected'
				 WHEN 4 THEN 'Approved but Paper Expired'
				 WHEN 5 THEN 'Ready for Approval'
                 END) as approve_status,
                cities.cty_name as city_name,
                vnd_name,
				vnd_code
                FROM `vehicles`
				LEFT JOIN vehicle_types vht ON vht.vht_id = vehicles.vhc_type_id
                LEFT JOIN vehicle_stats vhs ON vhs.vhs_vhc_id = vehicles.vhc_id
                INNER JOIN vcv_cat_vhc_type vcvt ON vcvt.vcv_vht_id = vht.vht_id
                INNER JOIN vehicle_category vct ON vct.vct_id = vcvt.vcv_vct_id
				INNER JOIN svc_class_vhc_cat scvc ON scvc.scv_vct_id = vct.vct_id
                INNER JOIN service_class ON service_class.scc_id = scvc.scv_scc_id
                LEFT JOIN (
                    SELECT ratings.rtg_customer_overall,
                    ratings.rtg_customer_car,
                    COUNT(1)  as countRating,
                    MAX(booking.bkg_pickup_date) as last_trip_pickup_date,
                    ratings.rtg_car_clean,
                    ratings.rtg_car_good_cond,
                    ratings.rtg_car_commercial,
                    ratings.rtg_car_cmt,
                    booking_cab.bcb_cab_id
                    FROM `ratings`
                    INNER JOIN `booking` ON booking.bkg_id=ratings.rtg_booking_id AND booking.bkg_active=1 AND booking.bkg_status IN (6,7)
                    INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 $qry1
                    WHERE booking.bkg_create_date > '2015-10-01'
                    GROUP BY booking_cab.bcb_cab_id
                ) vhc ON vhc.bcb_cab_id=vehicles.vhc_id
                LEFT JOIN (
                    SELECT MAX(booking.bkg_pickup_date) as last_pickup_date,
                    booking_cab.bcb_cab_id
                    FROM `booking`
                    INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1 AND booking.bkg_status IN (6,7) $qry1
                    WHERE booking.bkg_create_date > '2015-10-01'
                    GROUP BY booking_cab.bcb_cab_id
                )vhc2 ON vhc2.bcb_cab_id=vehicles.vhc_id
                LEFT JOIN `admins` ON admins.adm_id=vehicles.vhc_approved_by
                LEFT JOIN `cities` ON cities.cty_id=vehicles.vhc_home_city
                LEFT JOIN (
                    SELECT GROUP_CONCAT(vendors.vnd_name SEPARATOR ' ,') as vnd_name,GROUP_CONCAT(vendors.vnd_code SEPARATOR ' ,') as vnd_code,
                    vendor_vehicle.vvhc_vhc_id
                    FROM `vendors`
                    INNER JOIN `vendor_vehicle` ON vendor_vehicle.vvhc_vnd_id=vendors.vnd_id AND vendor_vehicle.vvhc_active=1 $qry
                    GROUP BY vendor_vehicle.vvhc_vhc_id
                )ven ON ven.vvhc_vhc_id=vehicles.vhc_id
                WHERE 1";
		if ($id == 0)
		{
			return DBUtil::queryAll($sql);
		}
		else
		{
			$sql .= " AND vehicles.vhc_id=$id  limit 0,1";
			return DBUtil::queryRow($sql);
		}
	}

	public function getPastTripList($id)
	{
		$removeVal	 = '"';
		$sql		 = "
			    SELECT booking.bkg_id,
				booking.bkg_booking_id,
                booking.bkg_booking_type,
                (CASE booking.bkg_booking_type WHEN 1 THEN 'One Way' WHEN 2 THEN 'Return' END) as booking_type,
                IFNULL(ratings.rtg_customer_car, 'N/A') as rtg_customer_car,
				IFNULL(ratings.rtg_car_cmt = '','N/A') as rtg_car_cmt,
                booking.bkg_pickup_date,
                btk.bkg_start_odometer,
                btk.bkg_end_odometer,
				REPLACE(JSON_EXTRACT(`bkg_route_city_names`, '$[0]'), '$removeVal', '')  AS from_city,
				REPLACE(JSON_EXTRACT(`bkg_route_city_names`, CONCAT('$[', JSON_LENGTH(`bkg_route_city_names`) - 1, ']')),'$removeVal','') AS to_city
                FROM `booking`
                JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1
				JOIN `vehicles` ON vehicles.vhc_id=booking_cab.bcb_cab_id
                LEFT JOIN `ratings` ON ratings.rtg_booking_id=booking.bkg_id
                INNER JOIN booking_track btk ON booking.bkg_id = btk.btk_bkg_id
                WHERE 1 AND booking.bkg_create_date > '2015-10-01 00:00:00'
                AND vehicles.vhc_id=$id
                GROUP BY booking.bkg_id
                ORDER BY booking.bkg_pickup_date DESC LIMIT 0,50";
		return DBUtil::queryAll($sql);
	}

	public function getModificationMSG($diff, $user = false)
	{
		$msg = '';
		if (count($diff) > 0)
		{
			if ($diff ['vhc_number'])
			{
				$msg .= ' Vehicle Number: ' . $diff['vhc_number'] . ',';
			}
			if ($diff ['vhc_year'])
			{
				$msg .= ' Vehicle Year: ' . $diff['vhc_year'] . ',';
			}
			if ($diff['vhc_color'])
			{
				$msg .= ' Vehicle Color: ' . $diff['vhc_color'] . ',';
			}
			if ($diff['vhc_type_id'])
			{
				$vhtModel	 = VehicleTypes::model()->findByPk($diff['vhc_type_id']);
				$vehicle	 = ($vhtModel->vht_make . " " . $vhtModel->vht_model);
				$msg		 .= ' Vehicle Type: ' . $vehicle . ',';
			}
			if ($diff['vhc_is_attached'])
			{
				$exclusiveStatus = ($diff['vhc_is_attached'] == 1) ? 'Yes' : 'No';
				$msg			 .= ' Is exclusive to Gozo: ' . $exclusiveStatus . ',';
			}
			if ($diff['vhc_is_commercial'])
			{
				$commercialStatus	 = ($diff['vhc_is_commercial'] == 1) ? 'Yes' : 'No';
				$msg				 .= ' Is Commercial: ' . $commercialStatus . ',';
			}
			if ($diff['vhc_approved'] <> '')
			{
				switch ($diff['vhc_approved'])
				{
					case 0;
						$approveStatus	 = 'Not Verified';
						break;
					case 1;
						$approveStatus	 = 'Approved';
						break;
					case 2;
						$approveStatus	 = 'Pending approval';
						break;
					case 3;
						$approveStatus	 = 'Rejected';
						break;
				}
				//$approveStatus = ($diff['vhc_approved']==1) ? 'Yes':'No';
				$msg .= ' Is Approved: ' . $approveStatus . ',';
			}
			if ($diff['vhc_owned_or_rented'])
			{
				$ownedrentedStatus	 = ($diff['vhc_owned_or_rented'] == 1) ? ' Yes' : 'No';
				$msg				 .= ' Vehicle owned or rented: ' . $ownedrentedStatus . ',';
			}
			if ($diff['insuranceFile'])
			{
				$msg .= ' Insurance : ' . $diff['insuranceFile'] . ',';
			}
			if ($diff['frontLicenseFile'])
			{
				$msg .= ' Front License : ' . $diff['frontLicenseFile'] . ',';
			}
			if ($diff['rearLicenseFile'])
			{
				$msg .= ' Rear License : ' . $diff['rearLicenseFile'] . ',';
			}
			if ($diff['pollutionFile'])
			{
				$msg .= ' Pollution under control : ' . $diff['pollutionFile'] . ',';
			}
			if ($diff['registrationFile'])
			{
				$msg .= ' Registration certificate : ' . $diff['registrationFile'] . ',';
			}
			if ($diff['permitFile'])
			{
				$msg .= ' Commercial permits : ' . $diff['permitFile'] . ',';
			}
			if ($diff['fitnessFile'])
			{
				$msg .= ' Fitness certificate : ' . $diff['fitnessFile'] . ',';
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public function getAllWoDocumentFiles()
	{
		$sql = "SELECT vehicles.vhc_id, vehicles.vhc_number, cntdocs , cntcab
                FROM `vehicles`
                INNER JOIN
                (
                    SELECT bcb_vendor_id, bcb_cab_id, COUNT(1) as cntcab
                    FROM `booking_cab`
                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_status IN (2,3,5,6,7) AND booking.bkg_active=1
                    INNER JOIN `vendors` ON vendors.vnd_id=booking_cab.bcb_vendor_id AND booking_cab.bcb_active=1
					INNER JOIN `vendors` v1 ON v1.vnd_id=vendors.vnd_ref_code
                    INNER JOIN `vehicles` ON vehicles.vhc_id=booking_cab.bcb_cab_id
                    GROUP BY booking_cab.bcb_vendor_id,booking_cab.bcb_cab_id
                    HAVING (cntcab > 2)
                )cab ON cab.bcb_cab_id=vehicles.vhc_id
                INNER JOIN
                (
                    SELECT vehicle_docs.vhd_vhc_id,COUNT(DISTINCT vehicle_docs.vhd_id) as cntdocs
                    FROM `vehicle_docs`
                    WHERE vehicle_docs.vhd_type IN (1,5,6) AND vehicle_docs.vhd_status=1
                    GROUP BY vehicle_docs.vhd_vhc_id
                    HAVING (cntdocs<=2)
                )docs ON docs.vhd_vhc_id=vehicles.vhc_id
                WHERE vehicles.vhc_is_freeze IN (0) AND vehicles.vhc_active=1";
		return DBUtil::queryAll($sql);
	}

	public function getVendorDetails($vhc_id)
	{
		$sql = 'SELECT ctp.phn_phone_no AS vnd_phone,vhc.vhc_number
                FROM vehicles vhc
                LEFT OUTER JOIN vendor_vehicle vvhc ON vvhc.vvhc_vhc_id = vhc.vhc_id
                LEFT OUTER JOIN vendors vnd ON vvhc.vvhc_vnd_id = vnd.vnd_id AND vnd.vnd_active > 0
				INNER JOIN contact_phone ctp ON ctp.phn_contact_id = ctt.ctt_id AND ctp.phn_is_primary=1
                WHERE vhc.vhc_active IN (1,2,3) AND vhc.vhc_id = ' . $vhc_id;
		return DBUtil::queryRow($sql);
	}

	public function getPendingApprovalList($arr = [], $command = false)
	{
		$where = '';
		if (trim($arr['vhcnumber']) != '')
		{
			$where .= "  AND LOWER(REPLACE(vhc.vhc_number,' ','')) LIKE '%" . strtolower(str_replace(' ', '', trim($arr['vhcnumber']))) . "%'";
		}
		if (trim($arr['vht_capacity']) != '')
		{
			$where .= "  AND  vht1.vht_capacity = " . trim($arr['vht_capacity']);
		}
		if (trim($arr['vhc_vendor_id']) > 0)
		{
			$where .= " AND vvhc_vhc_id IN (SELECT vvhc_vhc_id FROM   vendor_vehicle  WHERE  vvhc_vnd_id = " . $arr['vhc_vendor_id'] . ')';
		}
		if (trim($arr['vhc_color']) != '')
		{
			$where .= "  AND LOWER(vhc_color) LIKE '%" . strtolower(trim($arr['vhc_color'])) . "%'";
		}
		if (trim($arr['vhc_year']) != '')
		{
			$where .= "  AND vhc_year = " . trim($arr['vhc_year']);
		}


		$sql = "SELECT  
							group_concat(DISTINCT vnd.vnd_name SEPARATOR ', ') vnd_name,
							vht1.vht_capacity,
							vht1.vht_model,
							vhc.vhc_id,
							vhc.vhc_dop,
							vhc.vhc_color,
							vhc.vhc_number,
							vhc.vhc_year,
							if(bkg_id > 0, 1, 0) hasBooking,
							if(vhc.vhc_approved != 1, 1, 0) unapproved, 
				vct.vct_label,
				vhc.vhc_approved
                FROM vehicles vhc
                JOIN vehicle_types vht1 ON vht1.vht_id = vhc.vhc_type_id
				INNER JOIN vcv_cat_vhc_type vcvt ON vcvt.vcv_vht_id = vht1.vht_id
                INNER JOIN vehicle_category vct ON vct.vct_id = vcvt.vcv_vct_id
							JOIN vendor_vehicle vvhc ON vvhc.vvhc_vhc_id = vhc.vhc_id
                JOIN vendors vnd ON vnd.vnd_id = vvhc.vvhc_vnd_id and  vnd.vnd_id = vnd.vnd_ref_code
                JOIN vehicle_docs vhd  ON vhc.vhc_id = vhd.vhd_vhc_id
							LEFT JOIN booking_cab bcb ON bcb.bcb_cab_id = vhc.vhc_id AND bcb.bcb_id IS NOT NULL
							LEFT JOIN booking bkg ON bcb.bcb_id = bkg.bkg_bcb_id
							WHERE vhd_status = 0 AND vhd_active = 1 AND vhc_active > 0 AND vhd_file IS NOT NULL AND vhd_file <> ''	$where   GROUP BY vhc.vhc_id";

		$sqlCount		 = "SELECT  
							vhc.vhc_id
							FROM     vehicles vhc
							JOIN vehicle_types vht1 ON vht1.vht_id = vhc.vhc_type_id
							INNER JOIN vcv_cat_vhc_type vcvt ON vcvt.vcv_vht_id = vht1.vht_id
							INNER JOIN vehicle_category vct ON vct.vct_id = vcvt.vcv_vct_id
							JOIN vendor_vehicle vvhc ON vvhc.vvhc_vhc_id = vhc.vhc_id
							JOIN vendors vnd ON vnd.vnd_id = vvhc.vvhc_vnd_id and  vnd.vnd_id = vnd.vnd_ref_code
							JOIN vehicle_docs vhd ON vhc.vhc_id = vhd.vhd_vhc_id
							WHERE  vhd_status = 0 AND vhd_active = 1 AND vhc_active > 0 AND vhd_file IS NOT NULL AND vhd_file <> '' $where  GROUP BY vhc.vhc_id ";
		$defaultOrder	 = 'unapproved DESC,hasBooking DESC';
		if ($command == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 =>
					['vhc_number'],
					'defaultOrder'	 => $defaultOrder],
				'pagination'	 => ['pageSize' => 20],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
	}

	public function file_get_contents_curl($url)
	{
		$ch		 = curl_init();
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$data	 = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	public function approve($vhc_id, $userInfo)
	{
		$transaction = null;
		$success	 = false;
		try
		{
			/* @var $model Vehicles */
			$model				 = Vehicles::model()->findById($vhc_id);
			$model->vhc_approved = 1;
			$model->scenario	 = "isApprove";
			$transaction		 = DBUtil::beginTransaction();
			if ($model->save())
			{
				$desc		 = "Vehicle Approved.";
				Logger::writeToConsole($desc);
				$event_id	 = VehiclesLog::VEHICLE_APPROVED;
				VehiclesLog::model()->createLog($model->vhc_id, $desc, $userInfo, $event_id, false, false);
				$success	 = true;
				DBUtil::commitTransaction($transaction);
				BookingCab::model()->updateVendorPayment(1, $vhc_id);
			}
			else
			{

				$errors = "data not yet saved.\n\t" . json_encode($model->getErrors());
				Logger::writeToConsole("Error == " . $errors);
				throw new Exception($errors, ReturnSet::ERROR_VALIDATION);
			}
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			ReturnSet::setException($e);
		}
		return $success;
	}

	public function disapprove($vhc_id, $userInfo)
	{

		$success = false;
		try
		{
			$type		 = [1, 5, 7];
			$approved	 = 3;
			$docId		 = VehicleDocs::findExpiredDoc($vhc_id, $type);
			$model		 = Vehicles::model()->findByPk($vhc_id);
			$transaction = DBUtil::beginTransaction();
			$oldData	 = $model->attributes;
			if ($docId)
			{
				$approved = 4;
			}
			$model->vhc_approved = $approved;
			$newData			 = $model->attributes;

			if ($model->save())
			{
				$getOldDifference	 = array_diff_assoc($oldData, $newData);
				$changesForLog		 = "<br> Old Values: " . Vehicles::model()->getModificationMSG($getOldDifference, false);
				$desc				 = "Cab modified | " . $changesForLog;
				$event_id			 = VehiclesLog::VEHICLE_MODIFIED;
				VehiclesLog::model()->createLog($model->vhc_id, $desc, $userInfo, $event_id, false, false);
				$success			 = true;
				if ($success == true)
				{
					DBUtil::commitTransaction($transaction);
				}
			}
			else
			{
				$errors = "data not yet saved.\n\t\t" . json_encode($model->getErrors());
				Logger::create($errors, CLogger::LEVEL_WARNING);
				throw new Exception($errors);
			}
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::create("Not Disapprove.\n\t\t" . $ex->getMessage(), CLogger::LEVEL_ERROR);
		}
		return $success;
	}

	public function pendingApproval($vhc_id, $userInfo)
	{
		$transaction = null;
		$success	 = false;
		try
		{
			/* @var $model Vehicles */
			$model				 = Vehicles::model()->findById($vhc_id);
			$transaction		 = DBUtil::beginTransaction();
			$model->vhc_approved = 2;

			if ($model->save())
			{
				$desc		 = "Vehicle in Pending Approval State.";
				Logger::writeToConsole($desc);
				$event_id	 = VehiclesLog::VEHICLE_PENDING_APPROVAL;
				VehiclesLog::model()->createLog($model->vhc_id, $desc, $userInfo, $event_id, false, false);
				$success	 = true;
				DBUtil::commitTransaction($transaction);
			}
			else
			{

				$errors = "data not yet saved.\n\t" . json_encode($model->getErrors());
				Logger::writeToConsole("Error == " . $errors);
				throw new Exception($errors, ReturnSet::ERROR_VALIDATION);
			}
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			ReturnSet::setException($e);
		}
		return $success;
	}

	public function isFreeze($vhcId, $userInfo, $desc)
	{
		$success = false;
		try
		{
			$model = Vehicles::model()->resetScope()->findByPk($vhcId);
			if ($model->vhc_is_freeze == 0)
			{
				$model->vhc_is_freeze = 1;
				if ($model->validate() && $model->save())
				{
					VehiclesLog::model()->createLog($model->vhc_id, $desc, $userInfo, VehiclesLog::VEHICLE_FREEZE, false, false);
					$success = true;
				}
				else
				{
					$getErrors = $model->getErrors();
					throw new Exception("Not validate : " . $getErrors);
				}
			}
		}
		catch (Exception $ex)
		{
			$messsage = $ex->getMessage();
		}
		return ['success' => $success, 'messsage' => $messsage];
	}

	public function getVehicleApproveStatus()
	{
		$success = false;

		$checkVhcApp = [1, 2];
		if (UserInfo::getUserType() == UserInfo::TYPE_ADMIN) /** @TODO REMOVE THIS ONCE VEHICLE APPROVAL SYSTEM IS FIXED */
		{
			$checkVhcApp = [0, 1, 2, 4];
		}

		if ($this->vhc_active == 1 && in_array($this->vhc_approved, $checkVhcApp) && $this->vhc_is_freeze == 0)
		{
			$success = true;
		}
		return $success;
	}

	public function getVehicleApproveStatusForUber()
	{
		$success = true;
		if ($this->vhc_is_uber_approved == 0)
		{
			$success = false;
		}
		return $success;
	}

	/**
	 * This function is for updating all vehicle cities based on vendor city & number of bookings
	 */
	public function updateVehicleCity()
	{
		$sql = "SELECT bcb_cab_id, vnd_city, MAX(cnt) as cnt 
				FROM (
				SELECT bcb_cab_id, bcb_vendor_id,contact.ctt_city as vnd_city, COUNT(1) as cnt FROM booking bkg 
				INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bcb.bcb_active = 1 AND bcb_cab_id IS NOT NULL AND bcb_cab_id NOT IN (SELECT vhc_id FROM vehicles WHERE vhc_home_city > 0) 
				INNER JOIN vendors vnd1 ON vnd1.vnd_id = bcb.bcb_vendor_id AND vnd1.vnd_active = 1  and  vnd1.vnd_id = vnd1.vnd_ref_code
				INNER JOIN vendors vnd ON vnd.vnd_id = vnd1.vnd_ref_code
				INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vnd1.vnd_id AND vendor_pref.vnp_is_freeze = 0 
				INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd1.vnd_id AND cp.cr_status = 1 
				INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND ctt_id = ctt_ref_code AND ctt_active=1 AND ctt_city > 0
				WHERE bkg_active=1 AND bkg_status IN (5,6,7)  
				GROUP BY bcb_cab_id, bcb_vendor_id
				HAVING cnt >= 5 
				) tmp 
				GROUP BY bcb_cab_id";
		$res = DBUtil::queryAll($sql);
		if ($res)
		{
			foreach ($res as $eachRow)
			{
				$bcbCabId	 = $eachRow['bcb_cab_id'];
				$vndCity	 = $eachRow['vnd_city'];

				$sql = "UPDATE vehicles SET vhc_home_city = $vndCity WHERE vhc_id = $bcbCabId";
				$row = DBUtil::command($sql)->execute();
			}
		}

		$sql = "select city_id, bcb_cab_id, MAX(cnt) as cnt
				FROM (
				select city_id, bcb_cab_id, SUM(cnt) as cnt
				FROM (
				SELECT bkg.bkg_from_city_id as city_id, bcb_cab_id, COUNT(1) as cnt FROM booking bkg 
				INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bcb.bcb_active = 1 AND bcb_cab_id IS NOT NULL 
				WHERE bkg_active=1 AND bkg_status IN (5,6,7) 
				AND bcb_cab_id NOT IN (SELECT vhc_id FROM vehicles WHERE vhc_home_city > 0)
				GROUP BY bcb_cab_id, bkg_from_city_id 

				UNION ALL

				SELECT bkg1.bkg_to_city_id as city_id, bcb_cab_id, COUNT(1) as cnt FROM booking bkg1 
				INNER JOIN booking_cab bcb1 ON bcb1.bcb_id = bkg1.bkg_bcb_id AND bcb1.bcb_active = 1 AND bcb_cab_id IS NOT NULL 
				WHERE bkg_active=1 AND bkg_status IN (5,6,7) 
				AND bcb_cab_id NOT IN (SELECT vhc_id FROM vehicles WHERE vhc_home_city > 0)
				GROUP BY bcb_cab_id, bkg_to_city_id 
				) tmp 
				GROUP BY bcb_cab_id, city_id
				ORDER BY cnt DESC
				) tmp 
				GROUP BY bcb_cab_id
				HAVING cnt >= 10";
		$res = DBUtil::queryAll($sql);
		if ($res)
		{
			foreach ($res as $eachRow)
			{
				$bcbCabId	 = $eachRow['bcb_cab_id'];
				$cityId		 = $eachRow['city_id'];

				$sql = "UPDATE vehicles SET vhc_home_city = $cityId WHERE vhc_id = $bcbCabId";
				$row = DBUtil::command($sql)->execute();
			}
		}
	}

	/**
	 * updateVehicleState
	 */
	public function updateVehicleState()
	{
		$arr = array('AN' => '110', 'AP' => '80', 'AR' => '81', 'AS' => '82', 'BR' => '83', 'CH' => '114', 'CG' => '84', 'DN' => '109', 'DD' => '111', 'DL' => '108', 'GA' => '85', 'GJ' => '86', 'HR' => '87', 'HP' => '88', 'JK' => '89', 'JH' => '90', 'KA' => '91', 'KL' => '92', 'LD' => '113', 'MP' => '93', 'MH' => '94', 'MN' => '95', 'ML' => '96', 'MZ' => '97', 'NL' => '98', 'OD' => '99', 'PY' => '112', 'PB' => '100', 'RJ' => '101', 'SK' => '102', 'TN' => '103', 'TS' => '115', 'TR' => '104', 'UP' => '105', 'UK' => '106', 'WB' => '107');

		$sql = "SELECT vhc_id, vhc_number, UPPER(SUBSTRING(vhc_number, 1, 2)) as vhc_code FROM vehicles WHERE vhc_active=1";
		$res = DBUtil::queryAll($sql);
		if ($res)
		{
			foreach ($res as $eachRow)
			{
				$vhcId	 = trim($eachRow['vhc_id']);
				$vhcCode = trim($eachRow['vhc_code']);

				if (array_key_exists($vhcCode, $arr))
				{
					$stateId = $arr[$vhcCode];

					$sql = "INSERT INTO vehicle_state (`vst_vhc_id`, `vst_stt_id`) VALUES ($vhcId, $stateId)";
					$row = DBUtil::command($sql)->execute();
				}
			}
		}
	}

	/**
	 * 
	 * @param integer $rtgId
	 * @param array $userInfo
	 * @param array $params
	 * @return boolean
	 */
	public static function freezeLowRatingByRtgId($rtgId, $userInfo, $params)
	{
		$success = false;
		if ($params['vhc_id'] > 0 && $params['bkg_booking_id'] != '')
		{
			$desc	 = "Cab is Freezed due of Low Rating (" . $params['rtg_customer_car'] . ") in booking id " . $params['bkg_booking_id'];
			$result	 = Vehicles::model()->isFreeze($params['vhc_id'], $userInfo, $desc);
			$success = $result['success'];
		}
		return $success;
	}

	public static function totalTrips($vhcId)
	{
		$totalTrips = 0;
		if (isset($totalTrips) && $totalTrips > 0)
		{
			$sql		 = "SELECT
					COUNT(DISTINCT booking.bkg_id) AS total_trip_by_car
					FROM
						`booking_cab` 
					INNER JOIN `booking`  ON
					booking.bkg_bcb_id = booking_cab.bcb_id 
					AND booking.bkg_status IN (6, 7) 
					AND booking.bkg_active = 1 
					AND booking_cab.bcb_active = 1 
					AND booking_cab.bcb_cab_id IS NOT NULL 
					AND booking_cab.bcb_cab_id='$vhcId'";
			$totalTrips	 = DBUtil::command($sql)->queryScalar();
		}
		return $totalTrips;
	}

	public function cab_list($data, $triptype)
	{
		$route = [];
		foreach ($data as $key => $val)
		{
			$routeModel							 = new BookingRoute();
			$routeModel->brt_from_city_id		 = $val->pickup_city;
			$routeModel->brt_to_city_id			 = $val->drop_city;
			$routeModel->brt_pickup_datetime	 = $val->date;
			$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($val->date);
			$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime($val->date));
			$routeModel->brt_to_location		 = $val->drop_address;
			$routeModel->brt_from_location		 = $val->pickup_address;
			$routeModel->brt_to_pincode			 = $val->drop_pincode;
			$routeModel->brt_from_pincode		 = $val->pickup_pincode;
			$route[]							 = $routeModel;
		}
		$partnerId = Yii::app()->user->getId();

		$quoteM					 = new Quote();
		$quoteM->routes			 = $route;
		$quoteM->tripType		 = $triptype;
		$quoteM->partnerId		 = $partnerId;
		$quoteM->quoteDate		 = date("Y-m-d H:i:s");
		$quoteM->pickupDate		 = $data[0]->date;
		$quoteM->isB2Cbooking	 = false;
		$quoteM->setCabTypeArr();
		Logger::create("Quote Initialized: " . json_encode($quoteM), CLogger::LEVEL_INFO);
		$quoteData				 = $quoteM->getQuote('', true, false);
		$cabArr					 = [3, 2, 1, 5, 6];
		$cabArrAirport			 = [1, 2, 3];
		if ($triptype == 4)
		{
			$cabArr = $cabArrAirport;
		}

		$result = [];
		if (count($quoteData) == 0)
		{
			return false;
		}

		foreach ($cabArr as $k => $cab)
		{
			$additionalTime	 = 0;
			$fullPayment	 = false;
			if (!isset($quoteData[$cab]) || !$quoteData[$cab]->success)
			{
				continue;
			}
			if (in_array($cab, [5, 6]) && $quoteData[$cab]->routeRates->isTollIncluded != 1)
			{
				continue;
			}
			else if (in_array($cab, [5, 6]))
			{
				$fullPayment = true;
			}
			$vhtmodel		 = VehicleTypes::model()->getCarModel($cab, 1);
			$routeDistance	 = $quoteData[$cab]->routeDistance;
			$routeDuration	 = $quoteData[$cab]->routeDuration;
			$routeRates		 = $quoteData[$cab]->routeRates;
			$priceRule		 = $quoteData[$cab]->priceRule;
			$graceTime		 = ($routeDuration->garageTimeStart > 30) ? $routeDuration->garageTimeStart - 30 : 0;
			//$CabList[$ctr]['advanceRequired'] = ($startDistance > 60) ? 1 : 0;
			// $CabList[$ctr]['fullPayment'] = ($startDistance > 120) ? 1 : 0;
			$resData		 = [
				'graceTime'			 => $graceTime + $additionalTime,
				'fullPayment'		 => ($routeDuration->garageTimeStart > 120 || $fullPayment) ? 1 : 0,
				'nightAllowance'	 => $routeRates->driverNightAllowance,
				'nightCharges'		 => $priceRule->prr_night_driver_allowance,
				'state_tax'			 => $routeRates->stateTax | 0,
				'toll_tax'			 => $routeRates->tollTaxAmount | 0,
				'min_chargeable'	 => $routeDistance->quotedDistance, // $quote['routeData']['quoted_km'],
				'km_per_day'		 => $priceRule->prr_min_km, //$quote['routeData']['rateConfig']['perDayMinimumKM'],
				'days'				 => $routeDuration->calendarDays, //$quote['routeData']['days']['calendarDays'],
				'total_min'			 => $routeDuration->totalMinutes, //$quote[$cab]['total_min'],
				'cab'				 => VehicleTypes::model()->getCarByCarType($cab),
				'cab_type_id'		 => $cab,
				'actual_amt'		 => $routeRates->totalAmount - $routeRates->gst,
				'base_amt'			 => $routeRates->baseAmount,
				'gozo_base_amt'		 => $routeRates->baseAmount,
				'service_tax'		 => $routeRates->gst,
				'total_amt'			 => $routeRates->totalAmount,
				'quote_km'			 => $routeDistance->quotedDistance,
				'total_day'			 => $routeDuration->durationInWords,
				'km_rate'			 => $routeRates->ratePerKM, //   $quote[$cab]['km_rate'],
				'addional_km'		 => 0,
				'total_km'			 => $routeDistance->quotedDistance,
				'route'				 => $quoteM->routeDistance->routeDesc,
				'error'				 => 0,
				'image'				 => $vhtmodel->vht_image,
				'capacity'			 => $vhtmodel->vht_capacity,
				'bag_capacity'		 => $vhtmodel->vht_bag_capacity,
				'big_bag_capacity'	 => $vhtmodel->vht_big_bag_capacity,
				'cab_model'			 => $vhtmodel->vht_model,
				'startTripDate'		 => $routeDuration->fromDate,
				'endTripDate'		 => $routeDuration->toDate, //$quote['routeData']['endTripDate'],
				'driverAllowance'	 => $routeRates->driverAllowance,
				'tolltax'			 => $routeRates->isTollIncluded | 0,
				'statetax'			 => $routeRates->isStateTaxIncluded | 0,
				'parkingInc'		 => $routeRates->isParkingIncluded | 0,
				'servicetax'		 => $routeRates->gst, // $quote[$i]['servicetax'],
				'startTripCity'		 => $quoteM->routes[0]->brt_from_city_id,
				'endTripCity'		 => $quoteM->routes[count($quoteM->routes) - 1]->brt_to_city_id,
				'cab_id'			 => $vhtmodel->vht_id, //$quote[$i]['cab_id']
			];
			//echo '<pre>';
			//print_r($result);
			//echo '<pre>';
			//exit();
			$result[$k]		 = $resData;
		}
		return $result;
	}

	public function getCabType($typeId = 0)
	{
		$arrType = [
			1	 => 'hatchback',
			2	 => 'suv',
			3	 => 'sedan',
			4	 => 'traveller_15',
			5	 => 'MMT_ASSURED_DZIRE',
			6	 => 'MMT_ASSURED_INNOVA',
		];
		if ($typeId != 0)
		{
			return $arrType[$typeId];
		}
		else
		{
			return $arrType;
		}
	}

	public function getCabModel($typeId = 0)
	{
		$arrModel = [
			1	 => 'Indica, Swift or similar',
			2	 => 'Innova, Tavera or similar',
			3	 => 'Dzire, Etios or similar',
			4	 => 'Tempo Traveller 15 seater',
			5	 => 'Maruti Suzuki Dzire',
			6	 => 'Toyota Innova',
		];
		if ($typeId != 0)
		{
			return $arrModel[$typeId];
		}
		else
		{
			return $arrModel;
		}
	}

	public function getCabId($type = '')
	{
		$arrType = [
			'hatchback'			 => 1,
			'suv'				 => 2,
			'sedan'				 => 3,
			'traveller_15'		 => 4,
			'mmt_assured_dzire'	 => 5,
			'mmt_assured_innova' => 6,
		];
		if ($type != '')
		{
			return $arrType[$type];
		}
		else
		{
			return $arrType;
		}
	}

	public static function getTripType()
	{
		return [
			'1'	 => 'Outstation',
			'2'	 => 'Local',
			'3'	 => 'Airport Transfer'
		];
	}

	public static function getSingleTripType($tripType)
	{
		$list = Vehicles::getTripType();
		return $list[$tripType];
	}

	public static function getType($ids)
	{
		$tripTypes	 = '';
		$ids		 = explode(',', $ids);
		$ctr		 = 1;
		if (count($ids) > 0)
		{
			foreach ($ids as $id)
			{
				$tripTypes	 .= Vehicles::getSingleTripType($id);
				$tripTypes	 .= (count($ids) != $ctr) ? ', ' : ' ';
				$ctr++;
			}
		}
		return $tripTypes;
	}

	public function checkVehicleAvailability($vendorId, $startTime, $endTime, $fromCityId, $toCityId, $carType)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);

		if ($carType == VehicleCategory::ASSURED_INNOVA_ECONOMIC)
		{
			$carType = VehicleCategory::SUV_ECONOMIC . "," . VehicleCategory::ASSURED_INNOVA_ECONOMIC;
			//$carType =  VehicleCategory::ASSURED_INNOVA_ECONOMIC;
		}
		else if ($carType == VehicleCategory::ASSURED_DZIRE_ECONOMIC)
		{
			$carType = VehicleCategory::SEDAN_ECONOMIC . "," . VehicleCategory::ASSURED_DZIRE_ECONOMIC;
			//$carType =  VehicleCategory::ASSURED_DZIRE_ECONOMIC;
		}
		else if ($carType == VehicleCategory::COMPACT_ECONOMIC)
		{
			$carType = VehicleCategory::COMPACT_ECONOMIC . "," . VehicleCategory::SUV_ECONOMIC . "," . VehicleCategory::SEDAN_ECONOMIC . "," . VehicleCategory::ASSURED_DZIRE_ECONOMIC . "," . VehicleCategory::ASSURED_INNOVA_ECONOMIC;
		}
		else if ($carType == VehicleCategory::SEDAN_ECONOMIC)
		{
			$carType = VehicleCategory::SUV_ECONOMIC . "," . VehicleCategory::SEDAN_ECONOMIC . "," . VehicleCategory::ASSURED_DZIRE_ECONOMIC . "," . VehicleCategory::ASSURED_INNOVA_ECONOMIC;
		}
		else if ($carType == VehicleCategory::SUV_7_PLUS_1_ECONOMIC)
		{
			$carType = VehicleCategory::SUV_ECONOMIC;
		}
		$err	 = "";
		$sql1	 = "SELECT COUNT(1) FROM vendor_vehicle 
				INNER JOIN vehicles ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id AND vhc_active = 1 AND 	vhc_approved IN (1,2)  AND vvhc_vnd_id = $vendorId 
				INNER JOIN vehicle_types ON vhc_type_id = vht_id 
				INNER JOIN vcv_cat_vhc_type ON vht_id = vcv_vht_id AND vcv_vct_id IN($carType) ";
		$res1	 = DBUtil::command($sql1)->queryScalar();

		if ($res1 == 0)
		{
			$err = "Check your approved cars. None in this service class";
			goto result;
		}

		$sql = "SELECT COUNT(1) FROM vendor_vehicle 
				INNER JOIN vehicles ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id AND vhc_active = 1 AND 	vhc_approved IN (1,2)  AND vvhc_vnd_id = $vendorId 
				INNER JOIN vehicle_types ON vhc_type_id = vht_id 
				LEFT JOIN booking_cab ON booking_cab.bcb_cab_id = vehicles.vhc_id AND bcb_vendor_id = $vendorId 
				 AND ('$startTime' BETWEEN bcb_start_time AND bcb_end_time) 
				AND ('$endTime' BETWEEN bcb_start_time AND bcb_end_time)
				LEFT JOIN booking ON bkg_bcb_id = bcb_id AND bkg_status IN (5,6,7) 
				LEFT JOIN cab_availabilities ON cav_cab_id = vehicles.vhc_id AND cab_availabilities.cav_from_city = $fromCityId AND cab_availabilities.cav_to_cities = $toCityId
					AND cav_status = 1 AND '$startTime' BETWEEN cav_date_time AND DATE_ADD(cav_date_time, INTERVAL IFNULL(cav_duration, 10) HOUR)
					AND '$endTime' BETWEEN cav_date_time AND DATE_ADD(cav_date_time, INTERVAL IFNULL(cav_duration, 10) HOUR)
				WHERE (bkg_id IS NULL OR cav_id IS NOT NULL) AND vvhc_active=1";
		$res = DBUtil::command($sql)->queryScalar();

		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		if ($res == 0)
		{
			$err = "Your car(s) qualify in this service class and category but your cars are already busy at this time";
			goto result;
		}
		if ($res > 0)
		{
			return $err;
		}

		result:

		return $err;
	}

	public function checkVehicleClass($vendorId, $bookingClass)
	{
		if ($bookingClass == ServiceClass::CLASS_ECONOMIC)
		{
			$bookingClass = ServiceClass::CLASS_ECONOMIC . "," . ServiceClass::CLASS_VLAUE_PLUS . "," . ServiceClass::CLASS_SELECT . "," . ServiceClass::CLASS_SELECT_PLUS;
		}
		if ($bookingClass == ServiceClass::CLASS_VLAUE_PLUS)
		{
			$bookingClass = ServiceClass::CLASS_VLAUE_PLUS . "," . ServiceClass::CLASS_SELECT . "," . ServiceClass::CLASS_SELECT_PLUS;
		}
		if ($bookingClass == ServiceClass::CLASS_SELECT)
		{
			$bookingClass = ServiceClass::CLASS_SELECT . "," . ServiceClass::CLASS_SELECT_PLUS;
		}
		if ($bookingClass == ServiceClass::CLASS_SELECT_PLUS)
		{
			$bookingClass = ServiceClass::CLASS_SELECT_PLUS;
		}
		//$vehicle_type 
		$sql		 = "SELECT group_concat(DISTINCT (vht1.vct_id) )as vehicle_type
                    FROM   vehicles vhc
                    INNER JOIN `vendor_vehicle` vvhc ON vvhc.vvhc_vhc_id = vhc.vhc_id
                    INNER JOIN `vehicle_types` vht ON vht.vht_id=vhc.vhc_type_id
                    INNER JOIN `vcv_cat_vhc_type` vcv ON vcv.vcv_vht_id=vht.vht_id
                    INNER JOIN vehicle_category vht1 ON vht1.vct_id = vcv.vcv_vct_id
                    WHERE  vhc_active = 1 AND  vht.vht_active = 1 AND
                    vvhc_vnd_id = $vendorId AND
                        vhc_is_freeze = 0 AND
                        vhc_approved = 1
                     ORDER BY vvhc.vvhc_id";
		$result		 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		$vehicleType = $result['vehicle_type'];
		$res		 = 0;
		if (!empty($vehicleType) && !empty($bookingClass))
		{
			$sql1	 = "SELECT count(vcsc_id)  FROM `vehicle_cat_svc_class` WHERE `vcsc_vct_id` IN(" . $vehicleType . ") AND vcsc_ssc_id IN(" . $bookingClass . ")";
			$res	 = DBUtil::command($sql1)->queryScalar();
		}

		if ($res > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function checkDuplicateVehicleNo($vehicle, $vhcId = 0)
	{

		$param				 = ['vhc_id' => $vhcId];
		$strVehicleIdCond	 = '';
		if ($vhcId > 0)
		{
			$strVehicleIdCond = " AND vhc_id <> :vhc_id ";
		}
		$vNumber = str_replace(' ', '', trim($vehicle));
		$sql	 = "SELECT vhc_id, vhc_insurance_exp_date,vhc_reg_exp_date,vhc_year,vhc_description,vhc_type_id,vhc_number FROM vehicles
				where  vhc_active > 0  AND REPLACE(vhc_number,' ','') = '$vNumber' $strVehicleIdCond 
				order by vhc_approved,vhc_created_at desc";

		$result = DBUtil::queryAll($sql, DBUtil::MDB(), $param);
		return $result;
	}

	public function isApproved()
	{
		$success = true;
		if ($this->vhc_approved == 0)
		{
			$success = false;
		}
		return $success;
	}

	public function getJSONAllCabsbyQuery($query = '', $cabs = '', $onlyActive = '0', $vnd = 0)
	{
		$qry		 = '';
		$qry2		 = '';
		$limitNum	 = "LIMIT 0,30 ";
		if ($cabs != 0)
		{
			$qry1 = " AND 1 OR vhc.vhc_id='$cabs'";
		}
		if ($query != '')
		{
			DBUtil::getLikeStatement($query, $bindString, $params);
			$qry .= " AND (vhc.vhc_number LIKE $bindString or vht1.vht_make LIKE $bindString or vhc.vhc_number LIKE $bindString ) ";
		}
		if ($onlyActive == '1' && $vnd != 0)
		{
			$qry	 .= " AND vhc.vhc_active = 1  AND  vhc.vhc_is_freeze =0 AND vhc.vhc_approved !=3";
			$qry2	 .= " AND  vendor_vehicle.vvhc_vnd_id=$vnd AND vendor_vehicle.vvhc_active = 1";
		}
		if ($onlyActive == '2' && $vnd != 0)
		{
			$qry		 .= " AND vhc.vhc_active = 1  AND  vhc.vhc_is_freeze =0 AND vhc.vhc_approved !=3";
			$qry2		 .= " AND  vendor_vehicle.vvhc_vnd_id=$vnd";
			$limitNum	 = "";
		}
		$sql	 = "SELECT vvhc_vhc_id, vhc.vhc_id, vht1.vht_make, vht1.vht_model, upper(vhc.vhc_number) vhc_number
			    FROM vendor_vehicle
				INNER JOIN vehicles vhc on vvhc_vhc_id = vhc.vhc_id AND vhc.vhc_active = 1  $qry2 
                LEFT JOIN vehicle_types vht1 on vht1.vht_id = vhc_type_id AND vht1.vht_active = 1
                WHERE  vhc.vhc_number <> '' $qry $qry1 ORDER BY vhc.vhc_number  $limitNum";
		$rows	 = DBUtil::query($sql, DBUtil::MDB(), $params);
		$arr	 = array();
		foreach ($rows as $val)
		{
			$arr[] = array("id" => $val['vhc_id'], "text" => $val['vht_make'] . ' ' . $val['vht_model'] . ' (' . $val['vhc_number'] . ')');
		}
		$data = CJSON::encode($arr);
		return $data;
	}

	public static function updateCng($cabId, $isCng = '')
	{
		if ($isCng != '' && ($isCng == 0 || $isCng == 1 ) && $cabId > 0)
		{
			$sql = "UPDATE vehicles SET vhc_has_cng = $isCng WHERE vhc_id = $cabId";
			$row = DBUtil::command($sql)->execute();
			return $row;
		}
	}

	public static function getListByVendor($vndid, $search_txt = '', $is_freeze = 0, $approved = 1, $flag = 0)
	{

		$param = ['vndId' => $vndid, 'isFreeze' => $is_freeze, 'approved' => $approved];

		$where = '';
		if ($search_txt != '')
		{
			$search_txt = trim($search_txt);

			$tsearch_txt = strtolower(str_replace(' ', '', $search_txt));
			DBUtil::getLikeStatement($tsearch_txt, $bindString, $param1);
			$where		 = " AND (REPLACE(LOWER(vhc_number),' ', '')  LIKE $bindString  OR   
								vht.vht_make LIKE $bindString  OR   
								vht.vht_model LIKE $bindString  OR
								vht1.vct_label LIKE $bindString ) ";

			$param = array_merge($param, $param1);
		}

		$sql = "SELECT DISTINCT (vhc_id),vht1.vct_label cab_type, vhc_number,vhc_has_cng,
					vht.vht_model,vht.vht_make,vht1.vct_id vht_car_type   
					FROM   vehicles vhc
					INNER JOIN `vendor_vehicle` vvhc ON vvhc.vvhc_vhc_id = vhc.vhc_id
					INNER JOIN `vehicle_types` vht ON vht.vht_id=vhc.vhc_type_id
					INNER JOIN `vcv_cat_vhc_type` vcv ON vcv.vcv_vht_id=vht.vht_id
                    INNER JOIN vehicle_category vht1 ON vht1.vct_id = vcv.vcv_vct_id
					WHERE  vhc_active = 1 AND  vht.vht_active = 1 AND 
						vvhc_vnd_id = :vndId AND 
						vhc_is_freeze = :isFreeze AND 
						vhc_approved = :approved  $where   
					 ORDER BY vvhc.vvhc_id DESC LIMIT 0,30 ";

		if ($flag == 1)
		{
			return DBUtil::query($sql, DBUtil::SDB(), $param);
		}
		else
		{
			return DBUtil::queryAll($sql, DBUtil::MDB(), $param);
		}
	}

	public static function getListForTripByVendor($vndid, $bkgVehicleTypeId, $search_txt)
	{

		$catId		 = SvcClassVhcCat::getCatIdBySvcid($bkgVehicleTypeId);
		$where1		 = '';
		$joinType	 = 'INNER ';
		$param		 = ['vndId' => $vndid];
		if ($search_txt != '' && count(array_filter(str_split($search_txt), 'is_numeric')) >= 4)
		{
			$tsearch_txt = strtolower(str_replace(' ', '', trim($search_txt)));
			$where1		 = " AND (REPLACE(LOWER(vhc_number),' ', ''))  LIKE '%$tsearch_txt%'  ";
			$joinType	 = 'LEFT ';
		}

		$sql = "SELECT DISTINCT (vhc_id),vht1.vct_label cab_type, vhc_number,vhc_has_cng,
				vht.vht_model,vht.vht_make,vht1.vct_id vht_car_type,vht1.vct_id ,vhc_approved,vhc_is_freeze ,
				vhc_is_attached,vhc_owned_or_rented,
				if(vhc_approved=1,50,10) approvedRank,
				if(vhc_is_freeze=0,50,0) freezeRank,
				if(vht1.vct_id IN ($catId),50,0) catRank,
				if(vvhc.vvhc_vhc_id IS NULL,0,1) isLinked
				FROM   vehicles vhc				
				INNER JOIN `vehicle_types` vht ON vht.vht_id=vhc.vhc_type_id AND vht.vht_active=1
				INNER JOIN `vcv_cat_vhc_type` vcv ON vcv.vcv_vht_id=vht.vht_id AND vcv.vcv_active=1
				INNER JOIN vehicle_category vht1 ON vht1.vct_id = vcv.vcv_vct_id 
				$joinType JOIN `vendor_vehicle` vvhc ON vvhc.vvhc_vhc_id = vhc.vhc_id 
								AND vvhc.vvhc_active=1 AND vvhc_vnd_id = :vndId
				WHERE  vhc.vhc_active = 1 AND vht1.vct_id NOT IN (5,6) AND 
					vhc_approved IN (1,2) $where1
				ORDER BY isLinked DESC, approvedRank+freezeRank+catRank DESC,vct_label,vhc_number LIMIT 0,30 ";
		$res = DBUtil::query($sql, DBUtil::SDB(), $param);
		return $res;
	}

	public static function getDetailbyid($vhcid)
	{
		$param	 = ['vhcid' => $vhcid];
		$sql	 = "SELECT DISTINCT (vhc_id),vht1.vct_label cab_type, vhc_number,vhc_has_cng,
					vht.vht_model,   vht.vht_make,vht1.vct_id  vht_car_type,vhs.vhs_total_trips ,
					if(vht1.vct_id IN (5,6),0,1) vctRank, vhc.vhc_overall_rating 
					FROM   vehicles vhc				 
					INNER JOIN `vehicle_types` vht ON vht.vht_id=vhc.vhc_type_id
					INNER JOIN `vcv_cat_vhc_type` vcv ON vcv.vcv_vht_id=vht.vht_id
                    INNER JOIN vehicle_category vht1 ON vht1.vct_id = vcv.vcv_vct_id
					LEFT JOIN vehicle_stats vhs ON vhs.vhs_vhc_id=vhc_id
					WHERE  vhc_active = 1 AND  vht.vht_active = 1 AND 
						vhc_id = :vhcid     
					ORDER BY vctRank DESC
					 ";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return $result;
	}

	/**
	 * This function is used for validating the car number
	 * @param type $carNumber
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function checkCarNumber($carNumber)
	{
		$returnset = new ReturnSet();

		try
		{
			if (empty($carNumber))
			{
				throw new Exception("Data not passed", ReturnSet::ERROR_INVALID_DATA);
			}

			$sql	 = "SELECT COUNT(1) FROM vehicles WHERE vhc_number = :id";
			$count	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar(['id' => $carNumber]);

			$returnset->setStatus($count > 0);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		return $returnset;
	}

	/**
	 * This function is used for add vehicle details
	 * @param type $carDetails
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function addVehicleDetails($carDetails)
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($carDetails))
			{
				throw new Exception("Invalid input", ReturnSet::ERROR_INVALID_DATA);
			}

			$Ids = array();
			foreach ($carDetails as $cDetail)
			{
				$vehicleModel				 = new Vehicles();
				$vehicleModel->vhc_type_id	 = $cDetail->carModel;
				$vehicleModel->vhc_number	 = $cDetail->carNumber;
				$vehicleModel->vhc_year		 = $cDetail->carYear;

				if (!$vehicleModel->save())
				{
					throw new Exception(json_encode($vehicleModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}

				array_push($Ids, $vehicleModel->vhc_id);
			}

			$returnset->setStatus(true);
			$returnset->setData($Ids);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		return $returnset;
	}

	/**
	 * This function is used for fetching the vehicle types list and search
	 * @param type $requestDetails
	 * @return \CSqlDataProvider
	 */
	public static function fetchVehicleTypeDetalis($requestDetails = null)
	{
		$vehicleMake		 = $requestDetails["vehicleMake"];
		$vehicleModel		 = $requestDetails["vehicleModel"];
		$vehicleMileage		 = $requestDetails["vehicleMileage"];
		$vehicleSeatCapacity = $requestDetails["vehicleSeatCapacity"];

		$fetchVehicleDetails = "
			SELECT vt.vht_id,
				   vt.vht_make,
				   vt.vht_model,
				   vt.vht_average_mileage,
				   vt.vht_capacity,
				   vc.vct_label,
				   vt.vht_fuel_type
			FROM vehicle_types vt
				 INNER JOIN vcv_cat_vhc_type vcv ON vcv.vcv_vht_id = vt.vht_id
				 INNER JOIN vehicle_category vc ON vcv.vcv_vct_id = vc.vct_id
			WHERE vcv.vcv_active = 1 AND vc.vct_active = 1 AND vt.vht_active = 1
		";

		if (!empty($vehicleMake))
		{
			$fetchVehicleDetails .= " AND vht_make LIKE '%$vehicleMake%'";
		}





		if (!empty($vehicleModel))
		{
			$fetchVehicleDetails .= " AND vht_model LIKE '%$vehicleModel%'";
		}

		if (!empty($vehicleMileage))
		{
			$fetchVehicleDetails .= " AND vht_average_mileage = $vehicleMileage";
		}

		if (!empty($vehicleSeatCapacity))
		{
			$fetchVehicleDetails .= " AND vht_capacity = $vehicleSeatCapacity";
		}

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($fetchVehicleDetails) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($fetchVehicleDetails,
				[
			"totalItemCount" => $count,
			"pagination"	 =>
			[
				"pageSize" => 50
			],
			'sort'			 => array('defaultOrder' => 'vht_id DESC')
		]);

		return $dataprovider;
	}

	public function saveEndOdometer()
	{
		$success = true;
		if (!$this->save())
		{
			throw new Exception("Failed to add end odometer", ReturnSet::ERROR_FAILED);
			$success = false;
		}
		return $success;
	}

	public static function getEstimatedOdometer($odometer = 0, $lastdate = "")
	{
		if ($odometer > 0 && $lastdate != "")
		{
			$diff		 = strtotime(date("Y-m-d")) - strtotime($lastdate);
			$days		 = abs(round($diff / 86400));
			$estOdometer = $odometer + ( 250 * $days);
			return $estOdometer;
		}
		return 0;
	}

	public static function updateTier($odometerValue, $sccId, $maxYear = 15)
	{
		$where	 = "";
		$params	 = [];

		if ($sccId > 1)
		{
			$where .= " AND vhc_has_cng != 1 ";
		}

		$where	 .= " AND (YEAR(CURDATE()) - vhc_year) <= :maxYear 
					 AND (vhc_end_odometer < :odometerValue OR vhc_end_odometer IS NULL) AND vhc_year IS NOT NULL";
		$params	 = ["odometerValue" => $odometerValue, "maxYear" => $maxYear];

		$sql = "UPDATE vehicles vhc
				 SET    vhc.vhc_is_allowed_tier = CONCAT(IF(vhc.vhc_is_allowed_tier IS NULL,'',CONCAT(vhc.vhc_is_allowed_tier,',')),:sccId)
				 WHERE  vhc_approved = 1 AND vhc_active = 1 AND (NOT FIND_IN_SET(:sccId, vhc_is_allowed_tier) OR vhc_is_allowed_tier IS NULL) $where";

		$result = DBUtil::command($sql)->execute(["sccId" => $sccId] + $params);
		return $result;
	}

	public function updateIsAllowedTier()
	{
		$query	 = "update vehicles vhc SET vhc.vhc_is_allowed_tier = NULL where vhc.vhc_is_allowed_tier IS NOT NULL";
		$result	 = DBUtil::command($query)->execute();
	}

	public static function getDetailsByZoneID($zoneId = 0, $tierId = 0)
	{
		$sql			 = "Select vhc.vhc_number, vhc.vhc_code, vhc.vhc_end_odometer, (YEAR(CURDATE()) -  vhc_year) vhcYear, vnp_home_zone, zon_name, GROUP_CONCAT(vnd.vnd_name), concat(vht.vht_make, ' ' ,vht.vht_model) as vhcModel, vhc.vhc_id 
				 from vehicles vhc
				INNER JOIN vendor_vehicle ON vvhc_vhc_id = vhc.vhc_id AND vvhc_active = 1 
				INNER JOIN vendors vnd ON vnd.vnd_id = vvhc_vnd_id 
				INNER JOIN vendor_pref ON vnp_vnd_id = vnd.vnd_id AND vnd_active=1
				INNER JOIN zones ON zones.zon_id = vnp_home_zone
				INNER JOIN vehicle_types vht ON vht.vht_id = vhc.vhc_type_id
				where vnp_home_zone =$zoneId AND vhc.vhc_is_allowed_tier LIKE '%$tierId%' AND vhc_active = 1 AND vhc_approved = 1 AND vhc_year IS NOT NULL Group BY vhc.vhc_id";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider("$sql", [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'pagination'	 => ["pageSize" => 20],
		]);
		return $dataprovider;
	}

	public function getVModel($vhcTypeId)
	{
		$sql = "SELECT vht_model FROM vehicle_types WHERE vht_id= :id";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar(['id' => $vhcTypeId]);
	}

	public function getCodeById($id)
	{
		$sql	 = "SELECT vhc.vhc_code FROM vehicles vhc WHERE  vhc_active = 1 AND vhc_id = $id";
		$result	 = DBUtil::queryRow($sql);
		return $result['vhc_code'];
	}

	public static function getIdByNumber($number)
	{
		$params	 = ['number' => strtoupper(str_replace(' ', '', trim($number)))];
		$sql	 = "SELECT vhc.vhc_id FROM vehicles vhc 
				WHERE UPPER(REPLACE(vhc_number,' ','')) = :number 
				AND vhc.vhc_active > 0";
		$result	 = DBUtil::queryRow($sql, null, $params);
		return $result['vhc_id'];
	}

	/*
	  @deprecated
	 * 
	 */

	public function addBoostV1()
	{
		$date		 = date('Y-m-d 23:59:59');
		$today		 = date('d-m-Y');
		$prev_date	 = date('Y-m-d 00:00:00', strtotime($today . ' -1 days'));
		$sql		 = "SELECT distinct(`vhd_vhc_id`) FROM `vehicle_docs` WHERE `vhd_type` IN (8,9,10,11) and `vhd_status`=1 AND `vhd_appoved_at`  between '" . $prev_date . "' and  '" . $date . "'";
		//$prev_date = date('2020-11-21');
		$rows		 = DBUtil::query($sql, DBUtil::MDB(), $params);

		foreach ($rows as $val)
		{
			$vehicleId = $val['vhd_vhc_id'];

			$params	 = ['vehicleId' => $vehicleId];
			$sql	 = "SELECT count(vhd_id)as approveImage  FROM `vehicle_docs` WHERE vhd_vhc_id =:vehicleId AND `vhd_type` IN (8,9,10,11) and `vhd_status`=1 AND vhd_active=1";

			$result		 = DBUtil::queryRow($sql, null, $params);
			$numImage	 = $result['approveImage'];
			if ($numImage >= 4)
			{
				// check boost verify or car verify

				$sql1		 = "SELECT  vhs_boost_enabled  FROM `vehicle_stats` WHERE vhs_vhc_id =:vehicleId AND vhs_active=1";
				$result1	 = DBUtil::queryRow($sql1, null, $params);
				$boostStatus = $result1['vhs_boost_enabled'];
				if ($boostStatus == 2)
				{
					$today		 = date('d-m-Y');
					$next_date	 = date('Y-m-d', strtotime($today . ' + 30 days'));

					$today		 = date('d-m-Y');
					$next_date	 = date('Y-m-d', strtotime($today . ' + 30 days'));

					$vhkStatsModel = VehicleStats::model()->getbyVehicleId($vehicleId);
					if (!empty($vhkStatsModel))
					{
						$vhkStatsModel->vhs_boost_enabled		 = 1;
						$vhkStatsModel->vhs_boost_approved_date	 = $date;
						$vhkStatsModel->vhs_boost_expiry_date	 = $next_date;
						$success								 = $vhkStatsModel->save();

						$vendorId		 = Vehicles::model()->getVendorByVehicleId($vehicleId);
						$updateVendor	 = VendorPref::model()->updateBoostCount($vendorId);
						$boostPercentage = Vehicles::calculateVendorBoost($vendorId);
						$updateVendor	 = VendorStats::model()->updateBoostPercentage($vendorId, $boostPercentage);
					}
				}

				//general car verify

				$updateVerifyStat = VehicleStats::model()->updateGeneralCar($vehicleId);
			}
		}
	}

	public function addBoost()
	{
		$date		 = date('Y-m-d 23:59:59');
		$today		 = date('d-m-Y');
		$prev_date	 = date('Y-m-d 00:00:00', strtotime($today . ' -1 days'));
		$sql		 = "SELECT distinct(`vhd_vhc_id`) FROM `vehicle_docs` WHERE `vhd_type` IN (8,9,10,11) and `vhd_status`=1 AND `vhd_appoved_at`  between '" . $prev_date . "' and  '" . $date . "'";
		//$prev_date = date('2020-11-21');
		$rows		 = DBUtil::query($sql, DBUtil::MDB(), $params);

		foreach ($rows as $val)
		{
			$vehicleId = $val['vhd_vhc_id'];

			$params	 = ['vehicleId' => $vehicleId];
			$sql	 = "SELECT count(vhd_id)as approveImage  FROM `vehicle_docs` WHERE vhd_vhc_id =:vehicleId AND `vhd_type` IN (8,9,10,11) and `vhd_status`=1 AND vhd_active=1";

			$result		 = DBUtil::queryRow($sql, null, $params);
			$numImage	 = $result['approveImage'];
			if ($numImage >= 4)
			{
				// check boost verify
				$updateBoostStat = Vehicles::model()->checkBoostVerify($vehicleId);
			}
			$params	 = ['vehicleId' => $vehicleId];
			$sql	 = "SELECT count(vhd_id)as approveImage  FROM `vehicle_docs` WHERE vhd_vhc_id =:vehicleId AND `vhd_type` IN (8,9) and `vhd_status`=1 AND vhd_active=1";

			$result		 = DBUtil::queryRow($sql, null, $params);
			$numImage	 = $result['approveImage'];
			if ($numImage >= 2)
			{
				$updateVerifyStat = VehicleStats::model()->updateGeneralCar($vehicleId);
			}
		}
	}

	public static function checkBoostVerify($vehicleId)
	{
		$params		 = ['vehicleId' => $vehicleId];
		$sql1		 = "SELECT  vhs_boost_enabled  FROM `vehicle_stats` WHERE vhs_vhc_id =:vehicleId AND vhs_active=1";
		$result1	 = DBUtil::queryRow($sql1, null, $params);
		$boostStatus = $result1['vhs_boost_enabled'];
		if ($boostStatus == 1 || $boostStatus == 2)
		{

			$today		 = date('d-m-Y');
			$next_date	 = date('Y-m-d', strtotime($today . ' + 30 days'));

			$vhkStatsModel = VehicleStats::model()->getbyVehicleId($vehicleId);
			if (!empty($vhkStatsModel))
			{
				if ($boostStatus == 2)
				{
					$vhkStatsModel->vhs_boost_enabled		 = 1;
					$vhkStatsModel->vhs_boost_verify		 = 1;
					$vhkStatsModel->vhs_boost_approved_date	 = $date;
					$vhkStatsModel->vhs_boost_expiry_date	 = $next_date;
					$success								 = $vhkStatsModel->save();
				}

				$vendorId		 = Vehicles::model()->getVendorByVehicleId($vehicleId);
				$updateVendor	 = VendorPref::model()->updateBoostCount($vendorId);
				$boostPercentage = Vehicles::calculateVendorBoost($vendorId);
				$updateVendor	 = VendorStats::model()->updateBoostPercentage($vendorId, $boostPercentage);
			}
		}
	}

	public function getVendorByVehicleId($vehicleId)
	{

		$params	 = ['vehicleId' => $vehicleId];
		$sql1	 = "SELECT `vvhc_vnd_id` FROM `vendor_vehicle` WHERE vvhc_vhc_id =:vehicleId AND vvhc_active =1 ORDER BY `vvhc_id` DESC LIMIT 0,1";
		$row1	 = DBUtil::queryRow($sql1, DBUtil::MDB(), $params);
		return $row1['vvhc_vnd_id'];
	}

	public static function calculateVendorBoost($vendorId)
	{
		$calculatedBoost = 0;
		$params			 = ['vendorId' => $vendorId];

		$sql = "SELECT count(vvhc_vhc_id)as totalVehicle FROM vendor_vehicle WHERE vvhc_vnd_id=:vendorId";

		$rows			 = DBUtil::queryRow($sql, DBUtil::MDB(), $params);
		$totalVehicle	 = $rows['totalVehicle'];
		$sql1			 = "SELECT vnp_vhc_boost_count FROM vendor_pref WHERE vnp_vnd_id =:vendorId";
		$row1			 = DBUtil::queryRow($sql1, DBUtil::MDB(), $params);
		$boostedVehicle	 = $row1['vnp_vhc_boost_count'];
		if ($boostedVehicle > 0)
		{
			$calculatedBoost = round((($boostedVehicle / $totalVehicle) * 100), 2);
		}
		return $calculatedBoost;
	}

	public function removeBoost()
	{
		// $date =date('Y-m-d');
		$today	 = date('d-m-Y');
		$date	 = date('Y-m-d', strtotime($today . ' -1 days'));
		$params	 = ['date' => $date];
		$sql	 = "SELECT vhs_vhc_id FROM vehicle_stats WHERE vhs_boost_enabled=1 AND vhs_boost_expiry_date>=:date";
		$rows	 = DBUtil::query($sql, DBUtil::MDB(), $params);

		foreach ($rows as $val)
		{
			$vehicleId								 = $val['vhs_vhc_id'];
			$vhkStatsModel							 = VehicleStats::model()->getbyVehicleId($vehicleId);
			$vhkStatsModel->vhs_boost_enabled		 = 0;
			$vhkStatsModel->vhs_boost_approved_date	 = null;
			$vhkStatsModel->vhs_boost_expiry_date	 = null;
			$success								 = $vhkStatsModel->save();
			$vendorId								 = Vehicles::model()->getVendorByVehicleId($vehicleId);
			$updateVendor							 = VendorPref::model()->modifyBoostCount($vendorId);
			$boostPercentage						 = Vehicles::calculateVendorBoost($vendorId);
			$updateVendor							 = VendorStats::model()->updateBoostPercentage($vendorId, $boostPercentage);
		}
	}

	public static function rejectBoost()
	{

		$transaction = DBUtil::beginTransaction();
		try
		{
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userType	 = UserInfo::TYPE_SYSTEM;
			$date				 = date('Y-m-d 23:59:59');
			$today				 = date('d-m-Y');
			$prev_date			 = date('Y-m-d 00:00:00', strtotime($today . ' -1 days'));
			$sql				 = "SELECT distinct(`vhd_vhc_id`), vhd_approve_by FROM `vehicle_docs` WHERE `vhd_type` IN (8,9,10,11) and `vhd_status`=2 AND `vhd_appoved_at`  between '" . $prev_date . "' and  '" . $date . "'";
			$rows				 = DBUtil::query($sql, DBUtil::MDB(), $params);

			foreach ($rows as $val)
			{
				$vehicleId = $val['vhd_vhc_id'];

				$vhkStatsModel = VehicleStats::model()->getbyVehicleId($vehicleId);
				if (!empty($vhkStatsModel))
				{
					$sql1		 = "SELECT  vhs_verify_car,vhs_boost_enabled,vhs_verify_bkgId  FROM `vehicle_stats` WHERE vhs_vhc_id =:vehicleId  AND vhs_active=1";
					$result1	 = DBUtil::queryRow($sql1, DBUtil::SDB(), ['vehicleId' => $vehicleId]);
					$boostStatus = $result1['vhs_boost_enabled'];
					$bookingId	 = $result1['vhs_verify_bkgId'];
					//$vendorId	 = Vehicles::model()->getVendorByVehicleId($vehicleId);
					if ($bookingId != 0)
					{
						$model = Booking::model()->findByPk($bookingId);

						$bcb_id		 = $model->bkg_bcb_id;
						$bcbmodel	 = BookingCab::model()->findByPk($bcb_id);
						$vendorId	 = $bcbmodel->bcb_vendor_id;
						#$vendorId	 = BookingCab::model()->getVendorByBookingId($bookingId);
					}
					else
					{
						$vendorId = Vehicles::model()->getVendorByVehicleId($vehicleId);
					}
					//echo "Vendor".$vendorId.'<br>';
					if ($boostStatus == 2)
					{
						$vhkStatsModel->vhs_boost_enabled		 = 0; //0 for disapprove
						$vhkStatsModel->vhs_boost_approved_date	 = null;
						$vhkStatsModel->vhs_boost_expiry_date	 = null;
						$success								 = $vhkStatsModel->save();

						$updateVendor	 = VendorPref::model()->modifyBoostCount($vendorId);
						$boostPercentage = Vehicles::calculateVendorBoost($vendorId);
						$updateVendor	 = VendorStats::model()->updateBoostPercentage($vendorId, $boostPercentage);
						$event_id		 = VehiclesLog::VEHICLE_FILE_REJECTED;
						$desc			 = "Cab boost is  disabled due to inappropiate documentation";
						VehiclesLog::model()->createLog($vehicleId, $desc, Userinfo::getInstance(), $event_id, false, false);
					}
					else
					{
						$status = $result1['vhs_verify_car'];
						// echo $status.'---';
						if ($status != 2)
						{
							if ($bookingId != "")
							{
								$vhkStatsModel->vhs_verify_car			 = 2; // 2 for reject
								$vhkStatsModel->vhs_verification_date	 = date('Y-m-d');
								$success								 = $vhkStatsModel->save();

								// $sql			 = "Update `vendors` set vnd_active=2 WHERE vnd_id  =:vnd_id";
								$model		 = Booking::model()->findByPk($bookingId);
								$pickupdate	 = strtotime($model->bkg_pickup_date);
								$limitDate	 = strtotime('2021-01-16 00:00:00');
								/* if($pickupdate>=$limitDate)
								  {
								  $sql ="UPDATE vendor_pref set vnp_is_freeze=1 WHERE vnp_vnd_id=:vnd_id";
								  $cnt			 = DBUtil::execute($sql, ['vnd_id' => $vendorId]);
								  $penaltyAmount	 = 2000;
								  $remarks		 = "Vendor freeze due to Cab verification failed (See attachment in BKG ID ".$bookingId.")";
								  //AccountTransactions::model()->addVendorPenalty($bookingId, $vendorId, $penaltyAmount, $remarks);
								  } */
								$desc		 = "Cab verification failed by System(See attachment in BKG ID " . $bookingId . ")";
								BookingLog::model()->createLog($bookingId, $desc, $userInfo, BookingLog::CAB_VERIFIED, false, false);
							}
						}
					}
				}
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			$errors = ($errors);
			DBUtil::rollbackTransaction($transaction);
		}
	}

	public static function checkCarVerifyStatusOld($vehicleId, $bookingId)
	{

		$verifyFlag	 = 0;
		$params		 = ['vehicleId' => $vehicleId];
		$sql		 = "SELECT vhs_boost_verify,vhs_boost_approved_date,vhs_boost_enabled,vhs_verify_car,vhs_verification_date FROM vehicle_stats WHERE vhs_vhc_id =:vehicleId";
		$row		 = DBUtil::queryRow($sql, DBUtil::MDB(), $params);
		$now		 = time(); // or your date as well
		if ($row['vhs_boost_enabled'] == 1)
		{
			$verifyStat = $row['vhs_boost_verify'];
			if ($verifyStat == 1)
			{


				$your_date	 = strtotime($row['vhs_boost_approved_date']);
				$datediff	 = $now - $your_date;

				$difference = round($datediff / (60 * 60 * 24));
				if ($difference > 15)
				{
					$verifyFlag = 1;
				}
			}
			else
			{
				$verifyFlag = 1;
			}
		}
		//this portion is used for car verification for non busted car
		else
		{
			$verifyCar = $row['vhs_verify_car'];
			if ($verifyCar == 0 || $verifyCar == 2)
			{
				$verifyFlag = 1;
			}
			else
			{
				$your_date	 = strtotime($row['vhs_verification_date']);
				$datediff	 = $now - $your_date;

				$difference = round($datediff / (60 * 60 * 24));
				if ($difference > 15)
				{
					$verifyFlag = 1;
				}
			}
		}
		//check force car verification status
		$bkg_force_verification = BookingTrack::model()->checkCarVerifyByBkg($bookingId);

		if ($bkg_force_verification == 1)
		{
			$verifyFlag = 1;
		}
		//check document already in pending list or not if pending then no verification
		$carVerifyStatus = VehicleStats::docucumentVerfifyStat($vehicleId);
		if ($carVerifyStatus == 3)
		{
			$verifyFlag = 0;
		}


		return $verifyFlag;
	}

	/* @deprecated new function getVerifyStatus
	 * 
	 */

	public static function checkCarVerifyStatus($vehicleId, $bookingId)
	{

		$verifyFlag	 = 0;
		$params		 = ['vehicleId' => $vehicleId];
		$sql		 = "SELECT vhs_boost_verify,vhs_boost_approved_date,vhs_boost_enabled,vhs_verify_car,vhs_verification_date FROM vehicle_stats WHERE vhs_vhc_id =:vehicleId";
		$row		 = DBUtil::queryRow($sql, DBUtil::MDB(), $params);
		$now		 = time(); // or your date as well
		$verifyCar	 = $row['vhs_verify_car'];
		if ($row['vhs_boost_enabled'] == 1)
		{
			$verifyStat = $row['vhs_boost_verify'];

			if ($verifyStat == 1)
			{


				$your_date	 = strtotime($row['vhs_boost_approved_date']);
				$datediff	 = $now - $your_date;

				$difference = round($datediff / (60 * 60 * 24));
				if ($difference > 15)
				{
					$verifyFlag = 1;
				}
			}
			else
			{
				$verifyFlag = 1;
			}
			if ($verifyCar == 3)
			{
				$verifyFlag = 0;
			}
		}
		//this portion is used for car verification for non busted car
		else
		{


			switch ((int) $verifyCar)
			{
				case 1:
					$your_date	 = strtotime($row['vhs_verification_date']);
					$datediff	 = $now - $your_date;

					$difference = round($datediff / (60 * 60 * 24));
					if ($difference > 15)
					{
						$verifyFlag = 1;
					}
					break;
				case 2:
					$verifyFlag	 = 1;
					break;
				case 3:
					$verifyFlag	 = 0;
					break;
				default :
					$verifyFlag	 = 1;
					break;
			}
		}
		//check force car verification status
		$bkg_force_verification = BookingTrack::model()->checkCarVerifyByBkg($bookingId);
		if ($bkg_force_verification == 1)
		{
			$verifyFlag = 1;
		}
		// for sp instruction verification flag become 0
		//$verifyFlag = 0;

		return $verifyFlag;
	}

	/**
	 * function used to verify images of cab
	 * @param type $vehicleId
	 * @param type $pickupDate
	 */
	public static function getVerifyStatus($vehicleId, $pickupDate)
	{
		$params			 = ['vehicleId' => $vehicleId];
		$sql			 = "SELECT vhs_verify_car,vhs_verification_date FROM vehicle_stats WHERE vhs_vhc_id =:vehicleId";
		$row			 = DBUtil::queryRow($sql, DBUtil::MDB(), $params);
		$verifyDate		 = strtotime($row['vhs_verification_date']);
		$verifyStatus	 = $row['vhs_verify_car'];
		$verifiFlag		 = 0;
		if ($verifyStatus == 1)
		{
			$vDate		 = strtotime($row['vhs_verification_date']);
			$pDate		 = strtotime($pickupDate);
			$datediff	 = $pDate - $vDate;
			$difference	 = round($datediff / (60 * 60 * 24));

			if ($difference > 15)
			{
				$verifyStatus = 0;
			}
		}
		if ($verifyStatus == 0 || $verifyStatus == 2)
		{
			$verifiFlag = 1;
		}
		return $verifiFlag;
	}

	public function addVehicle_V2($data, $vendorId, $modelData = '', $linkWithVnd = true)
	{
		$success	 = false;
		$userInfo	 = UserInfo::getInstance();
		$vendorModel = Vendors::model()->findByPk($vendorId);
		if ($modelData->vhc_id > 0)
		{
			$model = Vehicles::model()->findByPk($modelData->vhc_id);
		}
		else
		{
			$model			 = $this;
			$model->scenario = 'insertadminapp';
		}
		if ($modelData && $model && $vendorId != Config::get('hornok.operator.id'))
		{
			if ($modelData->vhc_insurance_exp_date != $model->vhc_insurance_exp_date && $modelData->vhc_insurance_exp_date < date('Y-m-d'))
			{
				return ['success' => false, 'errors' => 'Insurance expiry date does not match!'];
			}
			if ($modelData->vhc_tax_exp_date != $model->vhc_tax_exp_date && $modelData->vhc_tax_exp_date < date('Y-m-d'))
			{
				return ['success' => false, 'errors' => 'Tax expiry date does not match!'];
			}
			if ($modelData->vhc_reg_exp_date != $model->vhc_reg_exp_date && $modelData->vhc_reg_exp_date < date('Y-m-d'))
			{
				return ['success' => false, 'errors' => 'Registration Certificate expiry date does not match!'];
			}
			if ($modelData->vhc_commercial_exp_date != $model->vhc_commercial_exp_date && $modelData->vhc_commercial_exp_date < date('Y-m-d'))
			{
				return ['success' => false, 'errors' => 'Commercial Certificate date does not match!'];
			}
			$model				 = $modelData;
			$model->vhc_approved = 2;
		}
		else
		{
			$model				 = $modelData;
			$model->vhc_approved = 2;
		}

		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($model->validate())
			{
				if ($model->save())
				{
					$codeArr = Filter::getCodeById($model->vhc_id, "car");
					if ($codeArr['success'] == 1)
					{
						$model->vhc_code = $codeArr['code'];
						$model->save();
					}
					else
					{
						$errors = "Vehicle code not created";
						throw new Exception($errors);
					}
					if ($linkWithVnd)
					{
						$vendorName			 = $vendorModel->vndContact->ctt_first_name . ' ' . $vendorModel->vndContact->ctt_last_name;
						$vehicleOwnerName	 = $model->vhc_reg_owner . ' ' . $model->vhc_reg_owner_lname;
						VendorVehicle::model()->unlinkOther($model->vhc_id, $vendorId);
						$arr				 = ['vehicle' => $model->vhc_id, 'vendor' => $vendorId, 'vhcOwner' => $vehicleOwnerName];
						$return				 = VendorVehicle::model()->checkAndSave($arr);
						if ($return == true)
						{
							$vendorVehicleModel = VendorVehicle::model()->findByVndVhcId($vendorId, $model->vhc_id);
							if ($vendorVehicleModel)
							{
								$vendorVehicleModel->vvhc_owner_or_not	 = ($model->vhc_owned_or_rented != null)? $model->vhc_owned_or_rented : 1;
								$isOwned								 = $model->vhc_owned_or_rented;
								if (($isOwned == 2) || ($isOwned == 1 && (strtoupper($vendorName) != strtoupper($vehicleOwnerName))))
								{
									$vendorVehicleModel->vvhc_is_lou_required	 = 1;
									$vendorVehicleModel->vvhc_active			 = 0;
								}
								else
								{
									$vendorVehicleModel->vvhc_is_lou_required	 = 0;
									$vendorVehicleModel->vvhc_active			 = 1;
								}
								$vendorVehicleModel->save();
							}
						}
					}
					//check and insert in vehicle stat table
					$linked = VehicleStats::model()->checkAndSave($model->vhc_id);
					if ($modelData->vhc_id > 0)
					{
						$desc		 = "Vehicle Modified.";
						$event_id	 = VehiclesLog::VEHICLE_MODIFIED;
						VehiclesLog::model()->createLog($model->vhc_id, $desc, $userInfo, $event_id, false, false);
					}
					else
					{
						$desc		 = "Vehicle is Created.";
						$event_id	 = VehiclesLog::VEHICLE_CREATED;
						VehiclesLog::model()->createLog($model->vhc_id, $desc, $userInfo, $event_id, false, false);
					}
					$success = true;
					if ($model->vehicleDocs)
					{
						foreach ($model->vehicleDocs as $vhdModel)
						{
							$vhdModel->saveDocumentNew($model->vhc_id, $vhdModel->vhd_file, $userInfo, $vhdModel->vhd_type, $vhdModel->vhd_temp_approved, $vhdModel->vhd_checksum, 1);
							$vhdModel->save();
						}
					}
					DBUtil::commitTransaction($transaction);
				}
				else
				{
					$errors = $model->getErrors();
					throw new Exception(json_encode($errors));
				}
			}
			else
			{
				$errors = $model->getErrors();
				throw new Exception(json_encode($errors));
			}
		}
		catch (Exception $e)
		{
			$errors = $e;
			DBUtil::rollbackTransaction($transaction);
		}
		return ['success' => $success, 'errors' => $errors, 'vehicleId' => $model->vhc_id];
	}

	public static function getStatsByZone()
	{
		$sql = "SELECT 
				zone_cities.zct_zon_id , 
				svc_class_vhc_cat.scv_id,
				COUNT(1) as cnt
				FROM `vehicles` 
				INNER JOIN `svc_class_vhc_cat` ON svc_class_vhc_cat.scv_vct_id=vehicles.vhc_type_id AND svc_class_vhc_cat.scv_active=1
				INNER JOIN `zone_cities` ON zone_cities.zct_cty_id=vehicles.vhc_home_city 
				INNER JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id AND zones.zon_active=1
				WHERE vehicles.vhc_active=1
				GROUP BY zone_cities.zct_zon_id, svc_class_vhc_cat.scv_id";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function updateInventoryStats()
	{
		// zone wise update
		$results = self::getStatsByZone();
		$ctr	 = 0;
		foreach ($results as $val)
		{
			$areaType	 = 1;
			$areaId		 = $val['zct_zon_id'];
			$svcTypeId	 = $val['scv_id'];
			$type		 = 2;
			$totalCount	 = $val['totalCount'];
			$activeCount = $val['activeCount'];
			InventoryStats::addInventory($areaType, $areaId, $type, $svcTypeId, $totalCount, $activeCount);
			$ctr++;
		}
	}

	public static function getOdometerHistory($id)
	{
		$params	 = array('id' => $id);
		$sql	 = "
			    SELECT 
				booking.bkg_id,
                booking.bkg_booking_id,
                ratings.rtg_booking_id,
                btk.bkg_start_odometer,
                btk.bkg_end_odometer,
                btk.bkg_ride_start,
                booking.bkg_pickup_date,
                btk.bkg_ride_complete,
                booking_pay_docs.bpay_type,
                booking_pay_docs.bpay_image
                FROM `booking`
                JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1
                LEFT JOIN `ratings` ON ratings.rtg_booking_id=booking.bkg_id
                INNER JOIN booking_track btk ON booking.bkg_id = btk.btk_bkg_id
                LEFT JOIN booking_pay_docs ON booking.bkg_id = booking_pay_docs.bpay_bkg_id  AND booking_pay_docs.bpay_type IN (101, 104)
                WHERE 1 AND booking.bkg_create_date > '2015-10-01 00:00:00'
                AND booking_cab.bcb_cab_id=:id  AND (btk.bkg_start_odometer IS NOT NULL OR btk.bkg_end_odometer IS NOT NULL)
                GROUP BY booking.bkg_id
                ORDER BY booking.bkg_pickup_date DESC LIMIT 0,50";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	public function getReviewHistory($id)
	{
		$params	 = array('id' => $id);
		$sql	 = "SELECT   
                    booking.bkg_booking_id,
                    ratings.rtg_booking_id,
                    ratings.rtg_customer_car,
                    ratings.rtg_customer_date,
                    ratings.rtg_car_cmt,
                    booking_cab.bcb_cab_rating
                    FROM `ratings`
                    JOIN `booking` ON booking.bkg_id = ratings.rtg_booking_id AND booking.bkg_active = 1
                    INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1
                    WHERE 1 AND booking.bkg_create_date > '2015-10-01 00:00:00'
                    AND booking_cab.bcb_cab_id=:id AND ratings.rtg_customer_car IS NOT NULL
                    GROUP BY booking.bkg_id
                    ORDER BY booking.bkg_pickup_date DESC LIMIT 0,50";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	public function getOdometerImage($bkg_id, $type)
	{
		$params	 = array('bkg_id' => $bkg_id, 'type' => $type);
		$sql	 = "SELECT 
				booking_pay_docs.bpay_image
                FROM booking_pay_docs 
                WHERE bpay_bkg_id=:bkg_id AND bpay_type =:type";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	/**
	 * 
	 * @param int $vehicleTypeId
	 * @return string
	 */
	public static function getVhcTypeFromScv($vehicleTypeId)
	{
		$params	 = array('vehicleTypeId' => $vehicleTypeId);
		$sql	 = "SELECT GROUP_CONCAT(vcv.vcv_vht_id) cabTypeList  from  vcv_cat_vhc_type vcv  
					INNER JOIN svc_class_vhc_cat svc ON svc.scv_vct_id = vcv.vcv_vct_id AND vcv.vcv_active=1 AND svc.scv_active=1
                    WHERE   svc.scv_id =:vehicleTypeId";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	/**
	 * Vehicle will be in pending_approval status if its Insurance Certificate & Registration Certificate is not disapproved.
	 * @param int $vehicleId	 
	 */
	public static function approveVehicleStatus($vehicleId)
	{
		if (!empty($vehicleId))
		{
			$params	 = array('vehicleId' => $vehicleId);
			$sql	 = "SELECT vhc_approved  from  vehicles	 WHERE  vhc_id =:vehicleId";
			$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
			if (in_array($result['vhc_approved'], [0, 4]))
			{
				$typeCnt = 0;
				//Insurance Certificate Check
				$sql	 = "SELECT count(vhd_id) as cnt FROM `vehicle_docs` where vhd_type=1 AND vhd_status IN(0,1) AND vhd_active=1 AND vhd_vhc_id=:vehicleId";
				$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
				if ($data['cnt'] >= 1)
				{
					$typeCnt++;
				}
				//Registration Certificate Check
				$sql1	 = "SELECT count(vhd_id) as cnt FROM `vehicle_docs` where vhd_type=5 AND vhd_status IN(0,1) AND vhd_active=1 AND vhd_vhc_id=:vehicleId";
				$data1	 = DBUtil::queryRow($sql1, DBUtil::SDB(), $params);
				if ($data1['cnt'] >= 1)
				{
					$typeCnt++;
				}
				if ($typeCnt >= 2)
				{
					$updateQuery = "UPDATE vehicles SET vhc_approved = 2 WHERE vhc_id =:vehicleId";
					DBUtil::execute($updateQuery, $params);
				}
			}
		}
	}

	public static function checkAvialability($tripId, $vehicleId)
	{
		$cabmodel = BookingCab::model()->findByPk($tripId);

		$overLap	 = $cabmodel->checkVehicleActiveTripTiming($vehicleId);
		//$overLap = $cabmodel->checkCabActiveTripTiming($vehicleId);
		$available	 = $overLap > 0 ? 0 : 1;
		return $available;
	}

	public static function checkApplicable($tripId, $vehicleId)
	{
		$cabmodel				 = BookingCab::model()->findByPk($tripId);
		$bookingId				 = $cabmodel['bcb_bkg_id1'];
		$vehicleModel			 = Vehicles::model()->findByPk($vehicleId);
		$cabmodel->bcb_cab_id	 = $vehicleId;
		$hasCngAllowed			 = $cabmodel->isCngAllowed($bookingId, $vehicleModel);
		$applicable				 = ($hasCngAllowed['success'] == 1 ? 1 : 0);
		return $applicable;
	}

	public static function getExpairedDocuments()
	{
		$afterOneMonth	 = "DATE_ADD(NOW(), INTERVAL 30 DAY)";
		$beforeTenDays	 = "DATE_SUB(NOW(), INTERVAL 11 DAY)";
		$sql			 = "SELECT vhc.vhc_id, vhc.vhc_number, vhc.vhc_insurance_exp_date, vvhc.vvhc_vnd_id,ctt.ctt_first_name, ctt.ctt_last_name,
					vhc.vhc_tax_exp_date, vhc.vhc_pollution_exp_date, vhc.vhc_commercial_exp_date,vhc.vhc_reg_exp_date,
				if(vhc.vhc_insurance_exp_date<= $afterOneMonth,1,0) as insurence_status,
				if(vhc.vhc_tax_exp_date<= $afterOneMonth,1,0) as tax_status,
				if(vhc.vhc_pollution_exp_date<= $afterOneMonth,1,0) as pollution_status,
				if(vhc.vhc_commercial_exp_date<= $afterOneMonth,1,0) as commercial_status,
				if(vhc.vhc_reg_exp_date<= $afterOneMonth,1,0) as reg_status
				FROM vehicles vhc 
				INNER JOIN vendor_vehicle vvhc on vhc.vhc_id = vvhc.vvhc_vhc_id AND vvhc.vvhc_active = 1
				INNER JOIN vendors vnd on vnd.vnd_id = vvhc.vvhc_vnd_id
				INNER JOIN vehicle_docs vhd on vhc.vhc_id = vhd.vhd_vhc_id
				INNER JOIN vehicle_docs vhd2 on vhd.vhd_id = vhd2.vhd_id AND vhd2.vhd_status != 0 AND vhd2.vhd_active = 1
				INNER JOIN contact_profile ON contact_profile.cr_is_vendor=vvhc.vvhc_vnd_id AND contact_profile.cr_status=1
                INNER JOIN contact ctt ON ctt.ctt_id=contact_profile.cr_contact_id AND ctt.ctt_active = 1 WHERE
				(vhc.vhc_insurance_exp_date BETWEEN $beforeTenDays AND $afterOneMonth) OR
				(vhc.vhc_tax_exp_date BETWEEN $beforeTenDays AND $afterOneMonth) OR
				(vhc.vhc_pollution_exp_date BETWEEN $beforeTenDays AND $afterOneMonth) OR
				(vhc.vhc_commercial_exp_date BETWEEN $beforeTenDays AND $afterOneMonth)OR
				(vhc.vhc_commercial_exp_date BETWEEN $beforeTenDays AND $afterOneMonth)OR
				(vhc.vhc_reg_exp_date BETWEEN $beforeTenDays AND $afterOneMonth)
				AND vhc.vhc_approved = 1 GROUP BY vhc.vhc_number";

		$results = DBUtil::query($sql, DBUtil::SDB());
		if ($results)
		{
			self::expiredDocNotification($results, 30);
		}
	}

	public static function getExpairedDocumentsWithTenDays()
	{
		$afterTenDay = "DATE_ADD(NOW(), INTERVAL 10 DAY)";
		$today		 = "CURRENT_DATE";
		$sql		 = "SELECT vhc.vhc_id, vhc.vhc_number, vhc.vhc_insurance_exp_date, vvhc.vvhc_vnd_id,ctt.ctt_first_name, ctt.ctt_last_name,
					vhc.vhc_tax_exp_date, vhc.vhc_pollution_exp_date, vhc.vhc_commercial_exp_date,vhc.vhc_reg_exp_date,
				if(vhc.vhc_insurance_exp_date<= $afterTenDay,1,0) as insurence_status,
				if(vhc.vhc_tax_exp_date<= $afterTenDay,1,0) as tax_status,
				if(vhc.vhc_pollution_exp_date<= $afterTenDay,1,0) as pollution_status,
				if(vhc.vhc_commercial_exp_date<= $afterTenDay,1,0) as commercial_status,
				if(vhc.vhc_reg_exp_date<= $afterTenDay,1,0) as reg_status
				FROM vehicles vhc 
				INNER JOIN vendor_vehicle vvhc on vhc.vhc_id = vvhc.vvhc_vhc_id AND vvhc.vvhc_active = 1
				INNER JOIN vendors vnd on vnd.vnd_id = vvhc.vvhc_vnd_id
				INNER JOIN vehicle_docs vhd on vhc.vhc_id = vhd.vhd_vhc_id
				INNER JOIN vehicle_docs vhd2 on vhd.vhd_id = vhd2.vhd_id AND vhd2.vhd_status != 0 AND vhd2.vhd_active = 1
				INNER JOIN contact_profile ON contact_profile.cr_is_vendor=vvhc.vvhc_vnd_id AND contact_profile.cr_status=1
                INNER JOIN contact ctt ON ctt.ctt_id=contact_profile.cr_contact_id AND ctt.ctt_active = 1 WHERE
				(vhc.vhc_insurance_exp_date BETWEEN $today AND $afterTenDay) OR
				(vhc.vhc_tax_exp_date BETWEEN $today AND $afterTenDay) OR
				(vhc.vhc_pollution_exp_date BETWEEN $today AND $afterTenDay) OR
				(vhc.vhc_commercial_exp_date BETWEEN $today AND $afterTenDay)OR
				(vhc.vhc_commercial_exp_date BETWEEN $today AND $afterTenDay)OR
				(vhc.vhc_reg_exp_date BETWEEN $today AND $afterTenDay)
				AND vhc.vhc_approved = 1 GROUP BY vhc.vhc_number";

		$results = DBUtil::query($sql, DBUtil::SDB());
		if ($results)
		{
			self::expiredDocNotification($results, 10);
		}
	}

	public static function expiredDocNotification($vehicleData, $days = 0)
	{
		foreach ($vehicleData as $vehicle)
		{
			$currentDate			 = date("Y-m-d", strtotime(date('Y-m-d')));
			$insurenceDate			 = date("Y-m-d", strtotime($vehicle['vhc_insurance_exp_date']));
			$insurenceDateFromat	 = date("d/M/Y", strtotime($vehicle['vhc_insurance_exp_date']));
			$taxDate				 = date("Y-m-d", strtotime($vehicle['vhc_tax_exp_date']));
			$taxDateFromat			 = date("d/M/Y", strtotime($vehicle['vhc_tax_exp_date']));
			$pollutionDate			 = date("Y-m-d", strtotime($vehicle['vhc_pollution_exp_date']));
			$pollutionDateFromat	 = date("d/M/Y", strtotime($vehicle['vhc_pollution_exp_date']));
			$commercialDate			 = date("Y-m-d", strtotime($vehicle['vhc_commercial_exp_date']));
			$commercialDateFormat	 = date("d/M/Y", strtotime($vehicle['vhc_commercial_exp_date']));
			$regitrationDate		 = date("Y-m-d", strtotime($vehicle['vhc_reg_exp_date']));
			$regitrationDateFormat	 = date("d/M/Y", strtotime($vehicle['vhc_reg_exp_date']));
			$arr					 = array();
			if ($vehicle['insurence_status'] == 1)
			{
				$msg	 = ($currentDate > $insurenceDate) ? 'expired on' : ' expires on';
				$arr[]	 = "Insurance $msg $insurenceDateFromat";
			}

			if ($vehicle['tax_status'] == 1)
			{
				$msg	 = ($currentDate > $taxDate) ? 'expired on' : 'expires on';
				$arr[]	 = "Tax $msg $taxDateFromat";
			}

			if ($vehicle['pollution_status'] == 1)
			{
				$msg	 = ($currentDate > $pollutionDate) ? 'expired on' : 'expires on';
				$arr[]	 = "Pollution $msg $pollutionDateFromat";
			}
			if ($vehicles['commercial_status'] == 1)
			{
				$msg	 = ($currentDate > $commercialDate) ? 'expired on' : 'expires on';
				$arr[]	 = "Commercial $msg $commercialDateFormat";
			}
			if ($vehicles['reg_status'] == 1)
			{
				$msg	 = ($currentDate > $regitrationDate) ? 'expired on' : 'expires on';
				$arr[]	 = "Registration Certificate $msg $regitrationDateFormat";
			}

			$status	 = implode(",", $arr);
			$message = "Hello " . $vehicle['ctt_first_name'] . " " . $vehicle['ctt_last_name'] . " your vehicle no - " . $vehicle['vhc_number'] . ". Vehicle $status.Please upload latest documents to prevent car from being frozen.";

			$payLoadData = ['vendorId' => $vehicle['vvhc_vnd_id'], 'EventCode' => Document::Document_Car_Expired];
			$success	 = AppTokens::model()->notifyVendor($vehicle['vvhc_vnd_id'], $payLoadData, $message, "Car documents expiring in $days days.");
		}
	}

	public static function autoApprove()
	{

		$check = Filter::checkProcess("vehicle autoApprove");
		if (!$check)
		{
			return;
		}
		Logger::profile("command.vehicle.autoApprove start", CLogger::LEVEL_PROFILE);
		$sumApprove	 = 0;
		$res		 = VehicleDocs::findApproveList();
		if ($res->getRowCount() > 0)
		{
			foreach ($res as $row)
			{
				$key		 = "Approval Started for " . json_decode($row);
				#$vendors	 = explode(',', $row['vendorIds']);
				$vhcId		 = $row['vhd_vhc_id'];
				$vhcNumber	 = $row['vhc_number'];
				$vmodel		 = new Vehicles();
				$success	 = $vmodel->approve($vhcId, UserInfo::getInstance());

				if (!$success)
				{
					continue;
				}
				$message	 = "Cab [ " . $vhcNumber . " ] is approved.";
				Logger::info($message);
				$sumApprove	 = ($sumApprove + 1);
				$title		 = "Cab approved";
				self::sendApprovalNotification($vhcId, $message, $title);
			}
		}
		Logger::info("command.vehicle.autoApprove Total Approve ->" . $sumApprove);
	}

	public static function sendApprovalNotification($vhcId, $message, $title)
	{
		$res = VendorVehicle::getLinkedVendors($vhcId);
		foreach ($res as $row)
		{
			$vndId = $row['vndId'];

			$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
			AppTokens::model()->notifyVendor($vndId, $payLoadData, $message, $title);
			Vendors::model()->updateDetails($vndId);
		}
		return true;
	}

	public static function autoDisapprove()
	{
		$model			 = new VehicleDocs();
		$res			 = $model->findDisapproveList();
		$sumNotApprove	 = 0;
		if (!$res || $res->getRowCount() == 0)
		{
			return;
		}
		foreach ($res as $row)
		{
			$vhcId		 = $row['vhd_vhc_id'];
			#$vendors	 = explode(',', $row['vendorIds']);
			$vhcNumber	 = $row['vhc_number'];
			$vmodel		 = new Vehicles();
			$success	 = $vmodel->disapprove($vhcId, UserInfo::getInstance());
			if (!$success)
			{
				continue;
			}
			$message		 = "Car [" . $vhcNumber . "] is rejected .";
			$title			 = "Cab rejected";
			Logger::info($message);
			$sumNotApprove	 = ($sumNotApprove + 1);
			self::sendApprovalNotification($vhcId, $message, $title);
		}
	}

	public static function autoPendingApproval()
	{
		$res			 = VehicleDocs::pendingApprovalList();
		$sumNotApprove	 = 0;
		if (!$res || $res->getRowCount() == 0)
		{
			return;
		}
		foreach ($res as $row)
		{
			$vhcId		 = $row['vhc_id'];
			#$vendors	 = explode(',', $row['vendorIds']);
			$vhcNumber	 = $row['vhc_number'];
			$vmodel		 = new Vehicles();
			$success	 = $vmodel->pendingApproval($vhcId, UserInfo::getInstance());
			if (!$success)
			{
				continue;
			}
			$title			 = "Cab pending approval";
			$message		 = "Car [" . $vhcNumber . "] is in pending approval state. Need to upload proper documents for approval.";
			Logger::info($message);
			$sumNotApprove	 = ($sumNotApprove + 1);
			self::sendApprovalNotification($vhcId, $message, $title);
		}
	}

	/**
	 * 
	 * @param int $cttid
	 * @param int $vehicleTypeId
	 * @return int
	 */
	public static function getPrefferedByContact($cttid, $vehicleTypeId)
	{
		$vnd	 = ContactProfile::getEntityById($cttid, UserInfo::TYPE_VENDOR);
		$vndId	 = $vnd['id'];
		$prefCab = Vehicles::getPrefferedByLastUsedVendor($vndId, $vehicleTypeId);
		if (!$prefCab)
		{
			$prefCab = Vehicles::getPrefferedByCabListVendor($vndId, $vehicleTypeId);
		}
		return $prefCab;
	}

	/**
	 * 
	 * @param int $vndId
	 * @param int $vehicleTypeId
	 * @return int
	 */
	public static function getPrefferedByLastUsedVendor($vndId, $vehicleTypeId)
	{
		$params	 = ['vndId' => $vndId, 'vehicleTypeId' => $vehicleTypeId];
		$sql	 = "SELECT   bcb.bcb_cab_id, max(bkg_pickup_date) max_pickup_date,
				if(bkg.bkg_vehicle_type_id = :vehicleTypeId,1,0) vhcRank 
			from  booking_cab bcb 
			INNER JOIN booking bkg ON bkg.bkg_bcb_id = bcb.bcb_id AND bkg.bkg_status IN (5,6,7)
			WHERE bcb.bcb_vendor_id =:vndId AND bcb.bcb_cab_id > 0
			GROUP BY bcb.bcb_cab_id
			ORDER BY vhcRank DESC, max_pickup_date DESC";
		$prefCab = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $prefCab;
	}

	/**
	 * 
	 * @param int $vndId
	 * @param int $vehicleTypeId
	 * @return int
	 */
	public static function getPrefferedByCabListVendor($vndId, $vehicleTypeId)
	{
		$cabTypeList = Vehicles::getVhcTypeFromScv($vehicleTypeId);

		$params = ['cabTypeList' => $cabTypeList, 'vndId' => $vndId];

		$sql	 = "SELECT DISTINCT vhc.vhc_id 
				FROM vendor_vehicle vvhc 
				INNER JOIN vehicles vhc ON vhc.vhc_id = vvhc.vvhc_vhc_id AND vhc.vhc_active = 1  
				WHERE    FIND_IN_SET(vhc.vhc_type_id,:cabTypeList) AND vvhc.vvhc_vnd_id = :vndId 
				AND vvhc.vvhc_active= 1 
				GROUP BY vhc_id";
		$prefCab = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $prefCab;
	}

	/**
	 * 
	 * @param type $docType
	 * @return type
	 */
	public static function getFieldListByDoc($docType = 0)
	{
		$docTypeArr = [
			'vhc_insurance_proof'		 => VehicleDocs::TYPE_INSURANCE,
			'vhc_front_plate'			 => VehicleDocs::TYPE_LICENSE_FRONT,
			'vhc_rear_plate'			 => VehicleDocs::TYPE_LICENSE_BACK,
			'vhc_pollution_certificate'	 => VehicleDocs::TYPE_PUC,
			'vhc_reg_certificate'		 => VehicleDocs::TYPE_RC_FRONT,
			'vhc_permits_certificate'	 => VehicleDocs::TYPE_COMERCIAL_PERMIT,
			'vhc_fitness_certificate'	 => VehicleDocs::TYPE_FITNESS_CERTIFICATE,
			'vhc_car_front'				 => VehicleDocs::TYPE_CAR_FRONT,
			'vhc_car_back'				 => VehicleDocs::TYPE_CAR_BACK,
			'vhc_back_reg_certificate'	 => VehicleDocs::TYPE_RC_REAR
		];
		if ($docType > 0)
		{
			return array_search($docType, $docTypeArr);
		}
		return $docTypeArr;
	}

	/**
	 * 
	 * @param type $refId
	 * @return type drivers count Int
	 */
	public static function checkOperatorRefId($refId)
	{
		$params	 = ['vhcOperatorId' => $refId];
		$sql	 = "SELECT COUNT(vhc_id) vhcCount, vhc_id FROM vehicles WHERE vhc_operator_ref_id= :vhcOperatorId AND vhc_active = 1";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $result;
	}

	/**
	 * 
	 * @param type $jsonObj
	 * @param type $operatorId
	 * @return array
	 */
	public function addHornOk($jsonObj, $operatorId)
	{
		$jsonMapper			 = new JsonMapper();
		/** @var OperatorVehicle $checkOperatorRefId */
		$checkOperatorRefId	 = OperatorVehicle::checkOperatorRefId($jsonObj->cab->id);
		/* vehcile details add or update */

		if ($checkOperatorRefId['vhcCount'] > 0)
		{
			$vhcData = ['vehicleId' => $checkOperatorRefId['orv_vhc_id']];
			goto skipNewVehicle;
		}
		else
		{
			$data1 = Vehicles::model()->checkDuplicateVehicleNo($jsonObj->cab->number);
			if ($data1[0]['vhc_id'] > 0)
			{
				$vhcModel = Vehicles::model()->findByPk($data1[0]['vhc_id']);
				if ($vhcModel)
				{
//					$OperatorVehicleArr				 = ['vehicleTypeId' => $vhcModel->vhc_type_id, 'vehicleId' => $vhcModel->vhc_id, 'operatorId' => $operatorId, 'vehicleModel' => $jsonObj->cab->category->allowedModels[0]->model, 'vehicleMake' => $jsonObj->cab->category->allowedModels[0]->make, 'operatorVehicleId' => $jsonObj->cab->id];
//					$returnVal				 = OperatorVehicle::model()->checkAndSave($OperatorVehicleArr);

					$arr	 = ['vehicle' => $vhcModel->vhc_id, 'vendor' => $operatorId, 'vhcOwner' => ''];
					$return	 = VendorVehicle::model()->checkAndSave($arr);
					$vhcData = ['vehicleId' => $vhcModel->vhc_id];
				}
			}
			else
			{
				/** @var \Beans\common\Cab $obj */
				$obj	 = $jsonMapper->map($jsonObj->cab, new \Beans\common\Cab());
				/** @var Vehicles $model */
				$model	 = $obj->setModel();
				$result	 = Vehicles::model()->addVehicle_V2('', $operatorId, $model);
				if ($result['success'] == true)
				{
					$vhcModel				 = Vehicles::model()->findByPk($result['vehicleId']);
					$vhcModel->vhc_approved	 = 1;
					if ($vhcModel->save())
					{

						$OperatorVehicleArr	 = ['vehicleTypeId' => $vhcModel->vhc_type_id, 'vehicleId' => $vhcModel->vhc_id, 'operatorId' => $operatorId, 'vehicleModel' => $jsonObj->cab->category->allowedModels[0]->model, 'vehicleMake' => $jsonObj->cab->category->allowedModels[0]->make, 'operatorVehicleId' => $jsonObj->cab->refId];
						/** @var OperatorVehicle $returnVal */
						$returnVal			 = OperatorVehicle::model()->checkAndSave($OperatorVehicleArr);

						$arr	 = ['vehicle' => $vhcModel->vhc_id, 'vendor' => $operatorId, 'vhcOwner' => ''];
						$return	 = VendorVehicle::model()->checkAndSave($arr);
						$vhcData = ['vehicleId' => $vhcModel->vhc_id];
					}
				}
			}
		}
		skipNewVehicle :
		return $vhcData;
	}

	/**
	 * 
	 * @param int $vhcId
	 * @return bool|Array
	 */
	public static function getDocumentExpiryDateById($vhcId)
	{
		if (empty($vhcId))
		{
			return false;
		}
		$sql = "SELECT vhc.vhc_id, vhc.vhc_insurance_exp_date, vhc.vhc_pollution_exp_date,vhc.vhc_reg_exp_date,
				vhc.vhc_fitness_cert_end_date, vhc.vhc_tax_exp_date, vhc.vhc_commercial_exp_date 
				FROM vehicles vhc
				WHERE vhc_id=:vhcId";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['vhcId' => $vhcId]);
	}

	public static function getCabListByVendor($vendorId, $searchTxt = '')
	{
		$param	 = ['vendorId' => $vendorId];
		$where	 = '';
		if (trim($searchTxt != ''))
		{
			$searchTxt	 = trim($searchTxt);
			$tsearchTxt	 = strtolower(str_replace(' ', '', $searchTxt));
			$bindString	 = "'%$tsearchTxt%'";

			$where = " AND (REPLACE(LOWER(vhc.vhc_number),' ', '')  LIKE $bindString OR 
						vht.vht_make LIKE $bindString OR 
						vht.vht_model LIKE $bindString OR 
						vct.vct_label LIKE $bindString ) ";
		}

		$sql		 = "SELECT
					vhc.vhc_id,
					vhc.vhc_type_id,
					vhc.vhc_number,
					vhc.vhc_mark_car_count,
					vhc.vhc_is_freeze,
					vhc.vhc_color,
					vhc.vhc_year,
					vhc.vhc_has_cng,
                    vhc.vhc_has_rooftop_carrier,
                    vht.vht_make,
                    vht.vht_model,
					vhc.vhc_approved,
					IF(vhc.vhc_dop = NULL,'',vhc.vhc_dop) AS vhc_dop,
					vvhc.vvhc_id,
					vvhc.vvhc_digital_is_agree,
					vvhc.vvhc_vnd_id AS vhc_vendor_id,					 
					vct.vct_label AS vht_car_type,
					vct.vct_id,vct.vct_label,
					IF((vhc.vhc_owned_or_rented = 1 OR vvhc.vvhc_digital_flag = 1),1,0) AS vvhc_digital_flag,					 
					IF(vehicleDoc = 3, 0, 1) AS documentUpload
					FROM
						`vehicles` vhc
					INNER JOIN `vendor_vehicle` vvhc ON vhc.vhc_id = vvhc.vvhc_vhc_id 
						AND vhc.vhc_active = 1 AND vhc.vhc_is_freeze <> 1					
					INNER JOIN `vehicle_types` vht ON vht.vht_id = vhc.vhc_type_id
					INNER JOIN vcv_cat_vhc_type vcvt ON vcvt.vcv_vht_id = vht.vht_id
					INNER JOIN vehicle_category vct ON vct.vct_id = vcvt.vcv_vct_id
					LEFT JOIN
					(
						SELECT
							vhd.vhd_vhc_id,
							SUM(vhd.vhd_active) AS vehicleDoc
						FROM
							`vehicle_docs` vhd
						WHERE
							vhd.vhd_active = 1 
							AND vhd.vhd_status IN (0,1) 
							AND vhd.vhd_type IN (1, 5, 6)
						GROUP BY
							vhd.vhd_vhc_id
					) vehicledoc
					ON
						vehicledoc.vhd_vhc_id = vhc.vhc_id
					WHERE
						vvhc.vvhc_vnd_id = :vendorId 
						$where
						AND vvhc.vvhc_active = 1
					GROUP BY vhc.vhc_id
					ORDER BY vhc.vhc_id DESC";
		$recordset	 = DBUtil::query($sql, DBUtil::SDB(), $param);
		return $recordset;
	}
}
