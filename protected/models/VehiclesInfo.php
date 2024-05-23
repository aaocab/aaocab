<?php

/**
 * This is the model class for table "vehicles_info".
 *
 * The followings are the available columns in table 'vehicles_info':
 * @property integer $vhc_id
 * @property integer $dup_id
 * @property integer $vhc_type_id
 * @property string $vhc_number
 * @property integer $vhc_vendor_id
 * @property integer $vhc_year
 * @property string $vhc_color
 * @property string $vhc_insurance_exp_date
 * @property string $vhc_tax_exp_date
 * @property string $vhc_dop
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
 * @property integer $vhc_mark_car_count
 * @property integer $vhc_approved
 * @property string $vhc_insurance_proof
 * @property string $vhc_permits_certificate
 * @property string $vhc_pollution_certificate
 * @property string $vhc_reg_certificate
 * @property string $vhc_rear_plate
 * @property string $vhc_front_plate
 * @property string $vhc_pollution_exp_date
 * @property string $vhc_reg_exp_date
 * @property string $vhc_commercial_exp_date
 * @property string $vhc_fitness_certificate
 * @property string $vhc_fitness_cert_end_date
 * @property integer $vhc_vehicle_id
 * @property integer $vhc_doc_score
 * @property integer $vhc_is_edited
 * @property VehicleDriver $vehicleDrivers
 * @property Vendors $vhcVendor

 */
class VehiclesInfo extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vehicles_info';
	}

	public function defaultScope()
	{

		parent::defaultScope();

		return ['condition' => 'vhc_active=1'];
	}

	public $vendorName, $vhcModel, $vhcCapacity, $cartype, $drv_names, $oldAttributes = [];

	public function beforeSave()
	{
		parent::beforeSave();
		if ($this->vhc_insurance_exp_date !== '' && $this->vhc_insurance_exp_date !== null)
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
		if ($this->vhc_tax_exp_date !== '' && $this->vhc_tax_exp_date !== null)
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
			if ((date('Y-m-d H:i:s', strtotime($this->vhc_dop)) != date($this->vhc_dop)))
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
		if ($this->scenario == 'isEdited')
		{
			$attrsNotifyChanged	 = ['vhc_number', 'vhc_insurance_exp_date', 'vhc_tax_exp_date',
				'vhc_insurance_proof', 'vhc_front_plate', 'vhc_reg_certificate', 'vhc_reg_exp_date', 'vhc_permits_certificate',
				'vhc_commercial_exp_date', 'vhc_fitness_certificate', 'vhc_fitness_cert_end_date', 'vhc_pollution_certificate', 'vhc_pollution_exp_date'];
			$result				 = [];
			for ($i = 0; $i < count($attrsNotifyChanged); $i++)
			{
				$result[$i] = $this->isAttributeChanged($attrsNotifyChanged[$i]);
			}
			if (in_array('true', $result))
			{
				$this->vhc_is_edited = 1;
			}
		}

		return true;
	}

	public function isAttributeChanged($name)
	{

		if (isset($this->attributes[$name], $this->oldAttributes[$name]) || $this->attributes[$name] != '')
		{
			return $this->attributes[$name] !== $this->oldAttributes[$name];
		}
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vhc_type_id, vhc_number,vhc_year', 'required', 'on' => 'addnew'),
			array('dup_id, vhc_type_id, vhc_vendor_id, vhc_year, vhc_owned_or_rented, vhc_active, vhc_is_attached, vhc_overall_rating, vhc_total_kms, vhc_gozo_kms, vhc_total_trips, vhc_last_thirtyday_trips, vhc_home_city, vhc_default_driver, vhc_mark_car_count, vhc_approved', 'numerical', 'integerOnly' => true),
			array('vhc_number, vhc_color', 'length', 'max' => 100),
			array('vhc_description', 'length', 'max' => 1024),
			array('vhc_vin', 'length', 'max' => 50),
			array('vhc_log', 'length', 'max' => 5000),
			array('vhc_vehicle_id', 'unique', 'on' => 'insert'),
			array('vhc_number', 'checkDuplicate', 'on' => 'insert,update,isEdited,addnew'),
			array('vhc_insurance_proof, vhc_permits_certificate, vhc_pollution_certificate, vhc_reg_certificate, vhc_rear_plate, vhc_front_plate, vhc_fitness_certificate', 'length', 'max' => 250),
			array('vhc_insurance_exp_date, vhc_tax_exp_date, vhc_dop, vhc_modified_at, vhc_pollution_exp_date, vhc_reg_exp_date, vhc_commercial_exp_date, vhc_fitness_cert_end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vhc_id,vhc_is_edited,vhc_vehicle_id, dup_id, vhc_type_id, vhc_number, vhc_vendor_id, vhc_year, vhc_color, vhc_insurance_exp_date, vhc_tax_exp_date, vhc_dop, vhc_owned_or_rented, vhc_created_at, vhc_modified_at, vhc_active, vhc_description, vhc_is_attached, vhc_overall_rating, vhc_total_kms, vhc_gozo_kms, vhc_vin, vhc_total_trips, vhc_last_thirtyday_trips, vhc_home_city, vhc_default_driver, vhc_log, vhc_mark_car_count, vhc_approved, vhc_insurance_proof, vhc_permits_certificate, vhc_pollution_certificate, vhc_reg_certificate, vhc_rear_plate, vhc_front_plate, vhc_pollution_exp_date, vhc_reg_exp_date, vhc_commercial_exp_date, vhc_fitness_certificate, vhc_fitness_cert_end_date', 'vhc_doc_score', 'safe'),
		);
	}

	public function checkDuplicate($attribute, $params)
	{
		$check		 = self::model()->find('vhc_number=:number AND vhc_vehicle_id<>:vehicle AND vhc_vendor_id=:vendor', ['number' => $this->$attribute, 'vehicle' => $this->vhc_vehicle_id, 'vendor' => $this->vhc_vendor_id]);
		// $check1 = Vehicles::model()->find('vhc_number=:number AND vhc_id<>:vehicle AND vhc_vendor_id=:vendor', ['number' => $this->$attribute, 'vehicle' => $this->vhc_vehicle_id, 'vendor' => $this->vhc_vendor_id]);
		$sqlCheck	 = "SELECT vhc_id from vehicles INNER JOIN vendor_vehicle ON vvhc_vhc_id = vhc_id WHERE vhc_id <>  " . $this->vhc_vehicle_id . " AND vhc_number ='" . $this->vhc_number . "' AND vvhc_vnd_id = " . $this->vhc_vendor_id;
		$cdb		 = DBUtil::command($sqlCheck);
		$check1		 = $cdb->queryScalar();

		if ($check || $check1)
		{
			$this->addError($attribute, 'Cab number already exists');
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
			'vhcType'		 => array(self::BELONGS_TO, 'VehicleTypes', 'vhc_type_id'),
			'vhcVendor'		 => array(self::BELONGS_TO, 'Vendors', 'vhc_vendor_id'),
			'vehicleDrivers' => array(self::BELONGS_TO, 'VehicleDriver', 'vhc_default_driver'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vhc_id'					 => 'Id',
			'dup_id'					 => 'Dup',
			'vhc_type_id'				 => 'Type',
			'vhc_number'				 => 'Vehicle Number',
			'vhc_vendor_id'				 => 'Vendor',
			'vhc_year'					 => 'Year',
			'vhc_color'					 => 'Color',
			'vhc_insurance_exp_date'	 => 'Insurance Expiry Date',
			'vhc_tax_exp_date'			 => 'Tax Expiry Date',
			'vhc_dop'					 => 'Dop',
			'vhc_owned_or_rented'		 => '1=>Owned, 2=>Rented',
			'vhc_created_at'			 => 'Created At',
			'vhc_modified_at'			 => 'Modified At',
			'vhc_active'				 => 'Active',
			'vhc_description'			 => 'Description',
			'vhc_is_attached'			 => 'Is Attached',
			'vhc_overall_rating'		 => 'Overall Rating',
			'vhc_total_kms'				 => 'Total Kms',
			'vhc_gozo_kms'				 => 'Gozo Kms',
			'vhc_vin'					 => 'Vin',
			'vhc_total_trips'			 => 'Total Trips',
			'vhc_last_thirtyday_trips'	 => 'Last Thirtyday Trips',
			'vhc_home_city'				 => 'Home City',
			'vhc_default_driver'		 => 'Default Driver',
			'vhc_log'					 => 'Log',
			'vhc_mark_car_count'		 => 'Mark Car Count',
			'vhc_approved'				 => 'Approved',
			'vhc_insurance_proof'		 => 'Insurance Proof',
			'vhc_permits_certificate'	 => 'Permits Certificate',
			'vhc_pollution_certificate'	 => 'Pollution Certificate',
			'vhc_reg_certificate'		 => 'Registration Certificate',
			'vhc_rear_plate'			 => 'Rear Plate',
			'vhc_front_plate'			 => 'Front Plate',
			'vhc_pollution_exp_date'	 => 'Pollution Certificate Expiry Date',
			'vhc_reg_exp_date'			 => 'Registration Certificate Expiry Date',
			'vhc_commercial_exp_date'	 => 'Commercial Expiry Date',
			'vhc_fitness_certificate'	 => 'Fitness Certificate',
			'vhc_fitness_cert_end_date'	 => 'Fitness Certificate Expiry Date',
			'vhc_vehicle_id'			 => 'vehicle Id',
			'vhc_is_edited'				 => 'Is Edited'
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

		$criteria->compare('vhc_id', $this->vhc_id);
		$criteria->compare('dup_id', $this->dup_id);
		$criteria->compare('vhc_type_id', $this->vhc_type_id);
		$criteria->compare('vhc_number', $this->vhc_number, true);
		$criteria->compare('vhc_vendor_id', $this->vhc_vendor_id);
		$criteria->compare('vhc_year', $this->vhc_year);
		$criteria->compare('vhc_color', $this->vhc_color, true);
		$criteria->compare('vhc_insurance_exp_date', $this->vhc_insurance_exp_date, true);
		$criteria->compare('vhc_tax_exp_date', $this->vhc_tax_exp_date, true);
		$criteria->compare('vhc_dop', $this->vhc_dop, true);
		$criteria->compare('vhc_owned_or_rented', $this->vhc_owned_or_rented);
		$criteria->compare('vhc_created_at', $this->vhc_created_at, true);
		$criteria->compare('vhc_modified_at', $this->vhc_modified_at, true);
		$criteria->compare('vhc_active', $this->vhc_active);
		$criteria->compare('vhc_description', $this->vhc_description, true);
		$criteria->compare('vhc_is_attached', $this->vhc_is_attached);
		$criteria->compare('vhc_overall_rating', $this->vhc_overall_rating);
		$criteria->compare('vhc_total_kms', $this->vhc_total_kms);
		$criteria->compare('vhc_gozo_kms', $this->vhc_gozo_kms);
		$criteria->compare('vhc_vin', $this->vhc_vin, true);
		$criteria->compare('vhc_total_trips', $this->vhc_total_trips);
		$criteria->compare('vhc_last_thirtyday_trips', $this->vhc_last_thirtyday_trips);
		$criteria->compare('vhc_home_city', $this->vhc_home_city);
		$criteria->compare('vhc_default_driver', $this->vhc_default_driver);
		$criteria->compare('vhc_log', $this->vhc_log, true);
		$criteria->compare('vhc_mark_car_count', $this->vhc_mark_car_count);
		$criteria->compare('vhc_approved', $this->vhc_approved);
		$criteria->compare('vhc_insurance_proof', $this->vhc_insurance_proof, true);
		$criteria->compare('vhc_permits_certificate', $this->vhc_permits_certificate, true);
		$criteria->compare('vhc_pollution_certificate', $this->vhc_pollution_certificate, true);
		$criteria->compare('vhc_reg_certificate', $this->vhc_reg_certificate, true);
		$criteria->compare('vhc_rear_plate', $this->vhc_rear_plate, true);
		$criteria->compare('vhc_front_plate', $this->vhc_front_plate, true);
		$criteria->compare('vhc_pollution_exp_date', $this->vhc_pollution_exp_date, true);
		$criteria->compare('vhc_reg_exp_date', $this->vhc_reg_exp_date, true);
		$criteria->compare('vhc_commercial_exp_date', $this->vhc_commercial_exp_date, true);
		$criteria->compare('vhc_fitness_certificate', $this->vhc_fitness_certificate, true);
		$criteria->compare('vhc_fitness_cert_end_date', $this->vhc_fitness_cert_end_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VehiclesInfo the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @deprecated since version 11-10-2019
	 * @author ramala
	 */
	public function fetchList()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria		 = new CDbCriteria;
		$criteria->compare('vhc_vendor_id', $this->vhc_vendor_id);
		$criteria->with	 = ['vhcType', 'vhcVendor'];
		if ($this->vhc_number != '')
		{
			$criteria->compare('LOWER(REPLACE(vhc_number,\' \',\'\'))', strtolower(str_replace(' ', '', $this->vhc_number)), true);
		}
		$criteria->compare('vhc_year', $this->vhc_year);
		$criteria->compare('vhc_color', $this->vhc_color);
		if ($this->vhc_approved == '' || $this->vhc_approved == 0)
		{
			$criteria->addCondition('vhc_approved<>0');
		}
		else
		{
			$criteria->compare('vhc_approved', $this->vhc_approved);
		}
		$criteria->compare('vhcVendor.vnd_name', $this->vendorName, true);
		$criteria->compare('vhcType.vht_make', $this->vhcModel, true);
		$criteria->compare('vhcType.vht_capacity', $this->vhcCapacity);
		$criteria->compare('vhcVendor.vnd_active', 1);
		$criteria->compare('vhcType.vht_car_type', $this->cartype);
		$criteria->together = true;

		return new CActiveDataProvider($this->together(), array(
			'criteria'	 => $criteria, 'sort'		 => array(
				'attributes'	 => ['vhc_number', 'vhc_dop', 'vhcType.vht_make', 'vhcType.vht_model', 'vhcType.vht_capacity', 'vhcType.vht_car_type'],
				'defaultOrder'	 => ['vhc_number']
			)
		));
	}

	/**
	 * @deprecated since version 11-10-2019
	 * @author ramala
	 */
	public function getCarType()
	{
		$ct		 = VehicleTypes::model()->getCarType();
		$cartype = $ct[$this->vhcType->vht_car_type];
		return $cartype;
	}

	/**
	 * @deprecated since version 11-10-2019
	 * @author ramala
	 */
	public function listToVerify()
	{

		$criteria			 = new CDbCriteria;
		$criteria->select	 = ["vhc_vendor_id", "vhc_number", "vhc_year", "vhc_color", "vhc_dop", "vhc_mark_car_count", "group_concat(vhdDriver.drv_name separator ', ') as drv_names", "REPLACE(vhc_number,' ','') as number"];

		if ($this->vhc_type_id != '')
		{
			$criteria->compare('vhc_type_id', $this->vhc_type_id);
		}
		if ($this->vhc_vendor_id != '')
		{
			$criteria->compare('vhc_vendor_id', $this->vhc_vendor_id);
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
		$criteria->with		 = ['vehicleDrivers' => ['with' => ['vhdDriver' => ['select' => 'drv_name']]], 'vhcType' => ['select' => ['vht_fuel_type', 'vht_car_type', 'vht_make', 'vht_model', 'vht_capacity']], 'vhcVendor' => ['select' => 'vnd_name']];
		$criteria->together	 = true;
		$criteria->group	 = 'vhc_id';
		$dataProvider		 = new CActiveDataProvider($this->together(), ['criteria'	 => $criteria, 'sort'		 => array(
				'attributes'	 => ['vhc_number', 'vhc_dop', 'vhc_mark_car_count', 'vhcType.vht_capacity', 'vhcType.vht_model'],
				'defaultOrder'	 => ['vhc_number']
			),]);
		return $dataProvider;
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

}
