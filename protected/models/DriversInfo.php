<?php

/**
 * This is the model class for table "drivers_info".
 *
 * The followings are the available columns in table 'drivers_info':
 * @property integer $drv_id
 * @property string $drv_name
 * @property string $drv_username
 * @property string $drv_password
 * @property integer $drv_country_code
 * @property string $drv_phone
 * @property string $drv_alt_phone
 * @property string $drv_email
 * @property integer $drv_vendor_id
 * @property string $drv_doj
 * @property string $drv_photo
 * @property string $drv_photo_path
 * @property string $drv_lic_number
 * @property string $drv_issue_auth
 * @property string $drv_lic_exp_date
 * @property string $drv_address
 * @property integer $drv_city
 * @property integer $drv_state
 * @property integer $drv_zip
 * @property integer $drv_bg_checked
 * @property string $drv_aadhaar_img
 * @property string $drv_aadhaar_img_path
 * @property string $drv_pan_img
 * @property string $drv_pan_img_path
 * @property string $drv_voter_id_img
 * @property string $drv_voter_id_img_path
 * @property string $drv_device
 * @property string $drv_last_login
 * @property string $drv_ip
 * @property string $drv_created
 * @property string $drv_modified
 * @property integer $drv_active
 * @property integer $drv_tnc_id
 * @property integer $drv_tnc
 * @property string $drv_tnc_datetime
 * @property string $drv_os_version
 * @property string $drv_device_uuid
 * @property string $drv_apk_version
 * @property string $drv_mac_address
 * @property string $drv_ip_address
 * @property string $drv_serial
 * @property integer $drv_marital_status
 * @property integer $drv_total_kms
 * @property integer $drv_year_of_exp
 * @property string $drv_frequent_cities
 * @property string $drv_pan_no
 * @property string $drv_voter_id
 * @property integer $drv_is_attached
 * @property integer $drv_overall_rating
 * @property integer $drv_total_trips
 * @property integer $drv_last_thirtyday_trips
 * @property integer $drv_gozo_kms
 * @property string $drv_aadhaar_no
 * @property string $drv_description
 * @property string $drv_history
 * @property string $drv_log
 * @property integer $drv_mark_driver_count
 * @property string $drv_code_password
 * @property string $drv_licence_path
 * @property string $drv_adrs_proof1
 * @property string $drv_adrs_proof2
 * @property string $drv_police_certificate
 * @property string $drv_dob_date
 * @property integer $drv_driver_id
 * @property integer $drv_approved
 * @property integer $drv_is_edited
 */
class DriversInfo extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'drivers_info';
	}

	public $oldAttributes;

	public function beforeSave()
	{
		parent::beforeSave();
		if ($this->drv_lic_exp_date !== null && $this->drv_lic_exp_date != '')
		{
			if ((date('Y-m-d', strtotime($this->drv_lic_exp_date)) != date($this->drv_lic_exp_date)))
			{
				$vhcTaxEexpDate			 = DateTimeFormat::DatePickerToDate($this->drv_lic_exp_date);
				$this->drv_lic_exp_date	 = $vhcTaxEexpDate;
			}
		}
		else
		{
			$this->drv_lic_exp_date = null;
		}
		if ($this->drv_dob_date !== null && $this->drv_dob_date != '')
		{
			if ((date('Y-m-d', strtotime($this->drv_dob_date)) != date($this->drv_dob_date)))
			{
				$vhcTaxEexpDate		 = DateTimeFormat::DatePickerToDate($this->drv_dob_date);
				$this->drv_dob_date	 = $vhcTaxEexpDate;
			}
		}
		else
		{
			$this->drv_dob_date = null;
		}
		if (!$this->isNewRecord)
		{
			$attrsNotifyChanged	 = ['drv_name', 'drv_photo_path', 'drv_email',
				'drv_dob_date', 'drv_phone', 'drv_address', 'drv_city',
				'drv_state', 'drv_zip', 'drv_bg_checked', 'drv_lic_number', 'drv_issue_auth', 'drv_lic_exp_date', 'drv_aadhaar_img_path', 'drv_pan_img_path', 'drv_voter_id_img_path', 'drv_licence_path', 'drv_police_certificate'];
			$result				 = [];
			for ($i = 0; $i < count($attrsNotifyChanged); $i++)
			{
				$result[$i] = $this->isAttributeChanged($attrsNotifyChanged[$i]);
			}
			if (in_array('true', $result))
			{
				$this->drv_is_edited = 1;
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
			array('drv_country_code, drv_vendor_id, drv_city, drv_state, drv_zip, drv_bg_checked, drv_active, drv_tnc_id, drv_tnc, drv_marital_status, drv_total_kms, drv_year_of_exp, drv_is_attached, drv_overall_rating, drv_total_trips, drv_last_thirtyday_trips, drv_gozo_kms, drv_mark_driver_count', 'numerical', 'integerOnly' => true),
			array('drv_name, drv_username, drv_email, drv_photo, drv_lic_number, drv_issue_auth, drv_address, drv_aadhaar_img, drv_pan_img, drv_voter_id_img, drv_device, drv_ip, drv_device_uuid', 'length', 'max' => 255),
			array('drv_password, drv_phone, drv_alt_phone, drv_lic_exp_date, drv_os_version, drv_apk_version, drv_ip_address, drv_code_password', 'length', 'max' => 100),
			array('drv_photo_path, drv_aadhaar_img_path, drv_pan_img_path, drv_voter_id_img_path', 'length', 'max' => 500),
			array('drv_mac_address, drv_serial', 'length', 'max' => 150),
			array('drv_frequent_cities', 'length', 'max' => 1024),
			array('drv_pan_no, drv_voter_id, drv_aadhaar_no', 'length', 'max' => 20),
			array('drv_description, drv_history', 'length', 'max' => 2048),
			array('drv_log', 'length', 'max' => 5000),
			['drv_name', 'checkDuplicate', 'on' => 'editDriver,verifyDriver,update,insert'],
			array('drv_driver_id', 'unique', 'on' => 'insert'),
			array('drv_name,drv_phone,drv_country_code', 'required', 'on' => 'verifyDriver,editDriver'),
			array('drv_licence_path, drv_adrs_proof1, drv_adrs_proof2, drv_police_certificate', 'length', 'max' => 250),
			array('drv_doj, drv_last_login, drv_created, drv_modified, drv_tnc_datetime, drv_dob_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('drv_id,drv_approved,drv_driver_id, drv_name, drv_username, drv_password, drv_country_code, drv_phone, drv_alt_phone, drv_email, drv_vendor_id, drv_doj, drv_photo, drv_photo_path, drv_lic_number, drv_issue_auth, drv_lic_exp_date, drv_address, drv_city, drv_state, drv_zip, drv_bg_checked, drv_aadhaar_img, drv_aadhaar_img_path, drv_pan_img, drv_pan_img_path, drv_voter_id_img, drv_voter_id_img_path, drv_device, drv_last_login, drv_ip, drv_created, drv_modified, drv_active, drv_tnc_id, drv_tnc, drv_tnc_datetime, drv_os_version, drv_device_uuid, drv_apk_version, drv_mac_address, drv_ip_address, drv_serial, drv_marital_status, drv_total_kms, drv_year_of_exp, drv_frequent_cities, drv_pan_no, drv_voter_id, drv_is_attached, drv_overall_rating, drv_total_trips, drv_last_thirtyday_trips, drv_gozo_kms, drv_aadhaar_no, drv_description, drv_history, drv_log, drv_mark_driver_count, drv_code_password, drv_licence_path, drv_adrs_proof1, drv_adrs_proof2, drv_police_certificate, drv_dob_date,drv_is_edited', 'safe'),
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
			'drvVendor'	 => array(self::BELONGS_TO, 'Vendors', 'drv_vendor_id'),
			'drvCity'	 => array(self::BELONGS_TO, 'Cities', 'drv_city'),
			'drvState'	 => array(self::BELONGS_TO, 'States', 'drv_state'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'drv_id'					 => 'Id',
			'drv_name'					 => 'Name',
			'drv_username'				 => 'Username',
			'drv_password'				 => 'Password',
			'drv_country_code'			 => 'Country Code',
			'drv_phone'					 => 'Phone',
			'drv_alt_phone'				 => 'Alternate Phone',
			'drv_email'					 => 'Email',
			'drv_vendor_id'				 => 'Agent',
			'drv_doj'					 => 'Date of joining',
			'drv_photo'					 => 'Photo',
			'drv_photo_path'			 => 'Photo Path',
			'drv_lic_number'			 => 'License Number',
			'drv_issue_auth'			 => 'Issue Authorization',
			'drv_lic_exp_date'			 => 'License Expiry Date',
			'drv_address'				 => 'Address',
			'drv_city'					 => 'City',
			'drv_state'					 => 'State',
			'drv_zip'					 => 'Zip',
			'drv_bg_checked'			 => '1=>Yes, 2=>No',
			'drv_aadhaar_img'			 => 'Aadhaar',
			'drv_aadhaar_img_path'		 => 'Aadhaar Card',
			'drv_pan_img'				 => 'PAN',
			'drv_pan_img_path'			 => 'PAN Card',
			'drv_voter_id_img'			 => 'Voter Id Card',
			'drv_voter_id_img_path'		 => 'Voter Id Card',
			'drv_device'				 => 'Device',
			'drv_last_login'			 => 'Last Login',
			'drv_ip'					 => 'IP address',
			'drv_created'				 => 'Created',
			'drv_modified'				 => 'Modified',
			'drv_active'				 => 'Active',
			'drv_tnc_id'				 => 'Tnc',
			'drv_tnc'					 => 'Tnc',
			'drv_tnc_datetime'			 => 'Tnc Datetime',
			'drv_os_version'			 => 'Os Version',
			'drv_device_uuid'			 => 'Device Uuid',
			'drv_apk_version'			 => 'Apk Version',
			'drv_mac_address'			 => 'Mac Address',
			'drv_ip_address'			 => 'Ip Address',
			'drv_serial'				 => 'Serial',
			'drv_marital_status'		 => 'Marital Status',
			'drv_total_kms'				 => 'Total Kms',
			'drv_year_of_exp'			 => 'Year Of Exp',
			'drv_frequent_cities'		 => 'Frequent Cities',
			'drv_pan_no'				 => 'Pan No',
			'drv_voter_id'				 => 'Voter',
			'drv_is_attached'			 => 'Is Attached',
			'drv_overall_rating'		 => 'Overall Rating',
			'drv_total_trips'			 => 'Total Trips',
			'drv_last_thirtyday_trips'	 => 'Last Thirtyday Trips',
			'drv_gozo_kms'				 => 'Gozo Kms',
			'drv_aadhaar_no'			 => 'Aadhaar No',
			'drv_description'			 => 'Description',
			'drv_history'				 => 'History',
			'drv_log'					 => 'Log',
			'drv_mark_driver_count'		 => 'Mark Driver Count',
			'drv_code_password'			 => 'Code Password',
			'drv_licence_path'			 => 'Licence Path',
			'drv_adrs_proof1'			 => 'Address Proof1',
			'drv_adrs_proof2'			 => 'Address Proof2',
			'drv_police_certificate'	 => 'Police Certificate',
			'drv_dob_date'				 => 'Date of birth',
			'drv_driver_id'				 => 'Driver Id',
			'drv_approved'				 => 'Approved',
			'drv_is_edited'				 => 'Is Edited'
		);
	}

	public function checkDuplicate($attribute, $params)
	{
		if ($this->isNewRecord)
		{

			$sql = "SELECT drv_id from drivers 
				                    INNER JOIN vendor_driver ON vdrv_drv_id = drv_id 
									WHERE drv_name = :drvName" .
					" AND vdrv_vnd_id = " . $this->drv_vendor_id;

			$param[':drvName']	 = $this->drv_name;
			$cdb				 = DBUtil::command($sql);
			$check1				 = $cdb->queryScalar($param);
            $check = self::model()->find('drv_name=:name  AND drv_phone=:phone  AND drv_vendor_id=:vendor', ['name' => $this->$attribute, 'phone' => $this->drv_phone, 'vendor' => $this->drv_vendor_id]);
			if ($this->drv_driver_id != '')
			{
				$sqlCheck			 = "SELECT drv_id from drivers INNER JOIN vendor_driver ON vdrv_drv_id = drv_id 
					                   
                                       WHERE drv_name = :drvName" .
						"AND vdrv_vnd_id = " . $this->drv_vendor_id .
						"AND drv_id <> " . $this->drv_driver_id;
				$param[':drvName']	 = $this->drv_name;
				$cdb				 = DBUtil::command($sqlCheck);
				$check1				 = $cdb->queryScalar($param);
			}
		}
		else
		{
			$check				 = self::model()->find('drv_name=:name AND drv_phone=:phone AND drv_driver_id<>:driver AND drv_vendor_id=:vendor', ['name' => $this->$attribute, 'phone' => $this->drv_phone, 'driver' => $this->drv_driver_id, 'vendor' => $this->drv_vendor_id]);
			$sqlCheck			 = "SELECT drv_id from drivers 
				                    INNER JOIN vendor_driver ON vdrv_drv_id = drivers.drv_id 
				                    WHERE drv_name = :drvName" .
									" AND vdrv_vnd_id = " . $this->drv_vendor_id .
									" AND drv_id <> " . $this->drv_driver_id;
			$param[':drvName']	 = $this->drv_name;
			$cdb				 = DBUtil::command($sqlCheck);
			$check1				 = $cdb->queryScalar($param);
		}
		if ($check || $check1)
		{
			$this->addError($attribute, 'Driver already exists');
			return false;
		}
		return true;
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

		$criteria->compare('drv_id', $this->drv_id);
		$criteria->compare('drv_name', $this->drv_name, true);
		$criteria->compare('drv_username', $this->drv_username, true);
		$criteria->compare('drv_password', $this->drv_password, true);
		$criteria->compare('drv_country_code', $this->drv_country_code);
		$criteria->compare('drv_phone', $this->drv_phone, true);
		$criteria->compare('drv_alt_phone', $this->drv_alt_phone, true);
		$criteria->compare('drv_email', $this->drv_email, true);
		$criteria->compare('drv_vendor_id', $this->drv_vendor_id);
		$criteria->compare('drv_doj', $this->drv_doj, true);
		$criteria->compare('drv_photo', $this->drv_photo, true);
		$criteria->compare('drv_photo_path', $this->drv_photo_path, true);
		$criteria->compare('drv_lic_number', $this->drv_lic_number, true);
		$criteria->compare('drv_issue_auth', $this->drv_issue_auth, true);
		$criteria->compare('drv_lic_exp_date', $this->drv_lic_exp_date, true);
		$criteria->compare('drv_address', $this->drv_address, true);
		$criteria->compare('drv_city', $this->drv_city);
		$criteria->compare('drv_state', $this->drv_state);
		$criteria->compare('drv_zip', $this->drv_zip);
		$criteria->compare('drv_bg_checked', $this->drv_bg_checked);
		$criteria->compare('drv_aadhaar_img', $this->drv_aadhaar_img, true);
		$criteria->compare('drv_aadhaar_img_path', $this->drv_aadhaar_img_path, true);
		$criteria->compare('drv_pan_img', $this->drv_pan_img, true);
		$criteria->compare('drv_pan_img_path', $this->drv_pan_img_path, true);
		$criteria->compare('drv_voter_id_img', $this->drv_voter_id_img, true);
		$criteria->compare('drv_voter_id_img_path', $this->drv_voter_id_img_path, true);
		$criteria->compare('drv_device', $this->drv_device, true);
		$criteria->compare('drv_last_login', $this->drv_last_login, true);
		$criteria->compare('drv_ip', $this->drv_ip, true);
		$criteria->compare('drv_created', $this->drv_created, true);
		$criteria->compare('drv_modified', $this->drv_modified, true);
		$criteria->compare('drv_active', $this->drv_active);
		$criteria->compare('drv_tnc_id', $this->drv_tnc_id);
		$criteria->compare('drv_tnc', $this->drv_tnc);
		$criteria->compare('drv_tnc_datetime', $this->drv_tnc_datetime, true);
		$criteria->compare('drv_os_version', $this->drv_os_version, true);
		$criteria->compare('drv_device_uuid', $this->drv_device_uuid, true);
		$criteria->compare('drv_apk_version', $this->drv_apk_version, true);
		$criteria->compare('drv_mac_address', $this->drv_mac_address, true);
		$criteria->compare('drv_ip_address', $this->drv_ip_address, true);
		$criteria->compare('drv_serial', $this->drv_serial, true);
		$criteria->compare('drv_marital_status', $this->drv_marital_status);
		$criteria->compare('drv_total_kms', $this->drv_total_kms);
		$criteria->compare('drv_year_of_exp', $this->drv_year_of_exp);
		$criteria->compare('drv_frequent_cities', $this->drv_frequent_cities, true);
		$criteria->compare('drv_pan_no', $this->drv_pan_no, true);
		$criteria->compare('drv_voter_id', $this->drv_voter_id, true);
		$criteria->compare('drv_is_attached', $this->drv_is_attached);
		$criteria->compare('drv_overall_rating', $this->drv_overall_rating);
		$criteria->compare('drv_total_trips', $this->drv_total_trips);
		$criteria->compare('drv_last_thirtyday_trips', $this->drv_last_thirtyday_trips);
		$criteria->compare('drv_gozo_kms', $this->drv_gozo_kms);
		$criteria->compare('drv_aadhaar_no', $this->drv_aadhaar_no, true);
		$criteria->compare('drv_description', $this->drv_description, true);
		$criteria->compare('drv_history', $this->drv_history, true);
		$criteria->compare('drv_log', $this->drv_log, true);
		$criteria->compare('drv_mark_driver_count', $this->drv_mark_driver_count);
		$criteria->compare('drv_code_password', $this->drv_code_password, true);
		$criteria->compare('drv_licence_path', $this->drv_licence_path, true);
		$criteria->compare('drv_adrs_proof1', $this->drv_adrs_proof1, true);
		$criteria->compare('drv_adrs_proof2', $this->drv_adrs_proof2, true);
		$criteria->compare('drv_police_certificate', $this->drv_police_certificate, true);
		$criteria->compare('drv_dob_date', $this->drv_dob_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DriversInfo the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function fetchList()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;
		$criteria->compare('drv_vendor_id', $this->drv_vendor_id);
		$criteria->compare('drv_phone', $this->drv_phone, true);
		$criteria->compare('drv_name', $this->drv_name, true);
		$criteria->compare('drv_email', $this->drv_email, true);
		if ($this->drv_approved == '' || $this->drv_approved == 0)
		{
			$criteria->addCondition('drv_approved<>0');
		}
		else
		{
			$criteria->compare('drv_approved', $this->drv_approved);
		}
		$criteria->compare('drvVendor.vnd_active', 1);
		$criteria->with		 = ['drvVendor'];
		$criteria->together	 = true;

		return new CActiveDataProvider($this->together(), array(
			'criteria'	 => $criteria, 'sort'		 => array(
				'attributes'	 => ['drv_created', 'drv_doj'],
				'defaultOrder'	 => ['drv_created'],
			),
			'pagination' => ['pageSize' => 50]
		));
	}

}
