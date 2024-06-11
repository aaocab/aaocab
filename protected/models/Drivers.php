<?php

use components\Event\Events;
use components\Event\EventSchedule;
use components\Event\EventReceiver;

/**
 * This is the model class for table "drivers".
 *
 * The followings are the available columns in table 'drivers':
 * @property integer $drv_id
 * @property integer $drv_user_id
 * @property integer $drv_contact_id
 * @property string $drv_code
 * @property string $drv_name
 * @property string $drv_paytm_phone
 * @property string $drv_doj
 * @property integer $drv_zip
 * @property integer $drv_bg_checked
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
 * @property string $drv_dob
 * @property integer $drv_marital_status
 * @property integer $drv_total_kms
 * @property integer $drv_year_of_exp
 * @property string $drv_frequent_cities
 * @property integer $drv_is_attached
 * @property integer $drv_overall_rating
 * @property integer $drv_total_trips
 * @property integer $drv_last_thirtyday_trips
 * @property integer $drv_gozo_kms
 * @property string $drv_description
 * @property string $drv_history
 * @property string $drv_log
 * @property integer $drv_mark_driver_count
 * @property string $drv_code_password
 * @property string $drv_adrs_proof1
 * @property string $drv_adrs_proof2
 * @property string $drv_dob_date
 * @property integer $drv_approved
 * @property integer $drv_is_freeze
 * @property integer $drv_ver_adrs_proof
 * @property integer $drv_ver_licence
 * @property integer $drv_ver_police_certificate
 * @property integer $drv_approved_by
 * @property string $drv_issue_date
 * @property double $drv_credit
 * @property string $drv_verification_code
 * @property integer $drv_is_uber_approved
 * @property string $drv_trip_type
 * @property string $drv_temp_licence_approved
 * @property string $drv_log_levels
 *
 * The followings are the available model relations:
 * @property BookingCab[] $bookingCabs
 * @property BookingOld[] $bookingOlds
 * @property VehicleDriver[] $vehicleDrivers
 * @property VendorDriver[] $vendorDrivers
 * @property Cities $driverCity
 * @property States $driverState
 * @property Contact $drvContact
 * @property User $drvUser
 * @property Drivers $drvRefCode
 * @property DriverStats $driverStats
 */
class Drivers extends CActiveRecord
{

	public $contactId;
	public $chk			 = 0;
	public $is_attached	 = 0;
	public $assigned_vhc_id, $drv_vendor_id1, $drv_vendor_id;
	public $vhd_temp_id,
			$vndlist,
			$drv_id_merge, $drv_licence_path2,
			$drv_licence_path_merge, $drv_lic_exp_date, $drv_lic_number,
			$drv_voter_id_img_path_merge, $drv_voter_id_img_path2,
			$drv_pan_img_path_merge, $drv_pan_img_path2,
			$drv_aadhaar_img_path_merge, $drv_aadhaar_img_path2,
			$drv_police_certificate_merge,
			$drv_photo_path_merge,
			$drv_adrs_proof1_merge,
			$drv_adrs_proof2_merge, $drv_city, $drv_phone, $drv_email, $drv_alt_phone,
			$drv_aadhaar_no, $drv_pan_no, $drv_voter_id, $drv_aadhaar_img_path, $drv_contact_name, $drv_type;
	public $drvPhoto, $drv_dob;
	public $drvDoc1, $drvDoc2, $drvDoc3, $vhd_vehicle_id, $cty_name, $agt, $vhc;
	public $drv_mark_driver_count, $drv_mark_driver_status;
	public $drv_bg_checked, $drv_is_attached;
	public $drv_cty_name, $drv_stt_name;
	public $blg_desc, $bkg_booking_id, $from_city_name, $to_city_name, $blg_remark_type,
			$bkg_pickup_date, $blg_created,
			$drv_reset_desc, $drv_password1, $repeat_password, $new_password, $old_password;
	public $total_driver, $total_approved, $total_rejected, $total_pending_approval;
	public $drv_name2, $drv_phone2, $drv_email2, $drv_source;
	public $approve_from_date, $approve_to_date, $drv_temp_licence_approved, $isApp		 = false;
	public $accType		 = [1 => 'Current', 0 => 'Saving'];
	public $contactDetails;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'drivers';
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "drv_active=1",
		);
		return $arr;
	}

	public function scopes()
	{
		return array(
			'orderByName' => array(
				'order' => 'drv_name ASC',
			),
		);
	}

	/**
	 * 
	 * @param int $approveStatus
	 * @return string|Array
	 */
	public static function getApproveStatusList($approveStatus = null)
	{
		$statusList = [
			0	 => 'Not Verified',
			1	 => 'Approved',
			2	 => 'Pending Approval',
			3	 => 'Rejected',
			4	 => 'Ready For Approval'];
		if ($approveStatus != '')
		{
			return $statusList[$approveStatus];
		}
		return $statusList;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//            array('drv_name, drv_phone', 'required'),
			//            array('drv_vendor_id, drv_city, drv_state, drv_zip, drv_bg_checked,drv_country_code, drv_active', 'numerical', 'integerOnly' => true),
			//            array('drv_name, drv_email, drv_photo, drv_lic_number, drv_issue_auth, drv_address, drv_img1, drv_img2, drv_img3, drv_device, drv_ip', 'length', 'max' => 255),
			//            array('drv_phone, drv_lic_exp_date', 'length', 'max' => 100),
			//            array('drv_photo_path, drv_img1_path, drv_img2_path, drv_img3_path', 'length', 'max' => 500),
			//            array('drv_zip', 'length', 'max' => 6, 'min' => 6),
			//            array('drv_photo_path, drv_img1_path, drv_img2_path, drv_img3_path', 'length', 'max' => 500),
			//            array('drv_zip', 'length', 'max' => 10),
			//            array('drv_modified', 'safe'),
			// array('drvPhoto, drvDoc1, drvDoc2, drvDoc3', 'file', 'types' => 'jpg, gif, png'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('drv_contact_id', 'numerical', 'integerOnly' => true),
			array('drv_name', 'required', 'on' => 'insert,insertAdmin', 'except' => 'addverify,addapp'),
			//array('drv_password1', 'required', 'on' => 'insert,insertAdmin', 'except' => 'addverify,addapp'),
			array('drv_name', 'required', 'on' => 'update', 'except' => 'addverify,addapp'),
			array('drv_name', 'required', 'on' => 'updateDriverApp'),
			//array('drv_phone ', 'unique', 'on' => 'insert,insertAdmin'),
			//array('drv_phone', 'validateDriverPhone', 'on' => 'update'),
			array('drv_name', 'required', 'on' => 'addapp'),
			array('drv_zip', 'length', 'max' => 10, 'on' => 'insert,update,insertAdmin'),
			//array('drv_zip', 'length', 'max' => 10, 'on' => 'insert,update'),
			//array('drv_lic_exp_date', 'required', 'on' => 'insertAdmin,updateAdmin'),
			//array('drv_lic_exp_date', 'required', 'on' => 'updateAdmin'),
			//array('drv_id, drv_lic_exp_date', 'required', 'on' => 'updateOnAssignment'),
			array('drv_id', 'required', 'on' => 'updateOnAssignment'),
			//array('drv_licence_path', 'required', 'on' => 'insertAdmin'),
			array('drv_name', 'required', 'on' => 'signup,driverupdate'),
			//array('drv_email', 'checkDuplicate', 'on' => 'signup,driverupdate'),
			array('drv_password1', 'required', 'on' => 'login'),
			array('drv_id, drv_code, drv_name', 'required', 'on' => 'updateCode'),
			//array('drv_id, drv_lic_exp_date', 'required', 'on' => 'updateApproval'),
			//array('drv_id, drv_lic_exp_date', 'required', 'on' => 'updateDoc'),
			array('drv_id', 'required', 'on' => 'updateApproval'),
			array('drv_id', 'required', 'on' => 'updateDoc'),
			array('repeat_password, new_password, old_password', 'required', 'on' => 'changepassword'),
			array('repeat_password', 'compare', 'compareAttribute' => 'new_password', 'on' => 'changepassword', 'message' => "Passwords don't match"),
			array('drv_name, drv_marital_status, drv_total_kms, drv_year_of_exp, drv_frequent_cities', 'required', 'on' => 'infoupdate'),
			//array('drv_phone, drv_lic_number', 'unique', 'on' => 'insert'),
			//array('drv_username', 'unique', 'on' => 'insert,insertAdmin,updateAdmin', 'except' => 'addverify'),
			['drv_name', 'checkDuplicateDriver', 'on' => 'insert,addverify,insertAdmin,updateAdmin'],
			array('drv_contact_id,drv_name', 'required', 'on' => 'updateAdmin,insertAdmin'),
			array('drv_contact_id, drv_code', 'isExisting', 'on' => 'insert'),
			array('drv_password1', 'length', 'min' => 3),
			array('drv_aadhaar_img_path', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true),
			array('drv_name', 'required', 'on' => 'addverify'),
			array('drv_contact_id', 'skipLicesnse', 'on' => 'skipLicesnse'),
			array('drv_doj', 'date', 'message' => 'Please enter valid date.', 'format' => 'yyyy-MM-dd', 'on' => 'insert,update', 'except' => 'addverify'),
			//array('drv_lic_exp_date', 'date', 'message' => 'Please enter valid date.', 'format' => 'yyyy-MM-dd', 'on' => 'insert,update', 'except' => 'addverify'),
			array('drv_id,drv_code,drv_approved_by,drv_ver_adrs_proof,drv_ver_licence,drv_ver_police_certificate,drv_approved,drv_licence_path2,drv_dob_date, drv_name, drv_paytm_phone, drv_vendor_id, drv_doj,
                drv_id_merge, drv_licence_path_merge,drv_voter_id_img_path_merge,drv_pan_img_path_merge,
            drv_aadhaar_img_path_merge, drv_police_certificate_merge, drv_photo_path_merge, drv_adrs_proof1_merge,drv_adrs_proof2_merge,
                drv_photo, drv_photo_path, drv_issue_auth, drv_lic_exp_date,
                drv_zip, drv_bg_checked, drv_aadhaar_img, drv_aadhaar_img_path, drv_aadhaar_img_path2, drv_pan_img, drv_pan_img_path, drv_pan_img_path2, drv_voter_id_img, drv_voter_id_img_path, drv_voter_id_img_path2, drv_device,
                drv_last_login, drv_ip, drv_created, drv_modified, drv_active, drv_is_attached, drv_overall_rating, drv_total_trips,
                drv_last_thirtyday_trips, drv_gozo_kms,drv_log, drv_description, drv_history,drv_mark_driver_count,blg_desc,drv_user_id,
                drv_mark_driver_status,drv_reset_desc,drv_tnc,drv_tnc_id,drv_tnc_datetime,drv_os_version,
                drv_device_uuid,drv_apk_version,drv_mac_address,drv_ip_address,drv_serial,drv_marital_status,drv_total_kms,drv_year_of_exp,drv_frequent_cities,
                drv_pan_no,drv_voter_id,drv_code_password,drv_is_freeze,drv_issue_date,drv_credit, approve_from_date, approve_to_date,drv_is_uber_approved,drv_trip_type, drv_log_levels,drv_temp_licence_approved, drv_type,drs_app_trip_count_started,drs_app_trip_count_completed', 'safe'),
			array('drv_reset_desc', 'required', 'on' => 'reset', 'message' => 'Please enter the reason for resetting bad mark'),
		);
	}

	public function checkDuplicateDriver($attribute, $params)
	{


		//$sql = 'SELECT DISTINCT drv_id FROM ' . $this->tableName() . ' drv INNER JOIN contact ON drv.drv_contact_id = contact.ctt_id LEFT JOIN contact_phone ON contact.ctt_id = contact_phone.phn_contact_id LEFT JOIN vendor_driver vdrv ON drv.drv_id = vdrv.vdrv_drv_id WHERE drv_active = 1 AND contact.ctt_active=1';
		$sql = 'SELECT DISTINCT d.drv_id as drv_id
				FROM ' . $this->tableName() . ' drv 
				INNER JOIN drivers AS d ON d.drv_id = drv.drv_ref_code AND d.drv_active =1
				INNER JOIN contact_profile AS cp ON cp.cr_is_driver = d.drv_id AND cp.cr_status =1
				INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_active = 1 AND contact.ctt_id = contact.ctt_ref_code 
				LEFT JOIN contact_phone ON contact.ctt_id = contact_phone.phn_contact_id 
				LEFT JOIN vendor_driver vdrv ON d.drv_id = vdrv.vdrv_drv_id 
				WHERE d.drv_active = 1 AND contact.ctt_active=1';

		if ($this->isNewRecord)
		{
			if ($this->drv_contact_id > 0)
			{
				$contDetails = Contact::model()->getContactDetails($this->drv_contact_id);
				if ($contDetails['phn_phone_no'] != '')
				{
					//$sql	 .= " AND phn_phone_no LIKE '%" . $contDetails['phn_phone_no'] . "%'";
					$sqlPhone = $sql . "  AND contact_phone.phn_active=1 AND contact_phone.phn_phone_no LIKE '%" . $contDetails['phn_phone_no'] . "%' AND ctt_id<>" . $this->drv_contact_id;

					$result = DBUtil::command($sqlPhone)->queryRow();
					if ($result)
					{

						$this->addError($attribute, "Driver with this phone already exist.");
						return false;
					}
				}
				if ($this->drvContact->ctt_license_no == '')
				{
					$this->addError($attribute, "Driver License no is mandatory.");
					return false;
				}
				if ($this->drvContact->ctt_license_no != '')
				{
					$sqlLicense	 = $sql . " AND ctt_license_no = '" . $this->drvContact->ctt_license_no . "' AND ctt_id<>" . $this->drv_contact_id;
					$result		 = DBUtil::command($sqlLicense)->queryRow();
					if ($result)
					{
						$this->addError($attribute, "Driver with this license already exist. Please verify your contact details to activate your account");
						return false;
					}
				}
			}
		}
		else
		{
			$sqlCheck = 'SELECT drv_id 
							from drivers 
							INNER JOIN contact_profile AS cp ON cp.cr_is_driver = drv_id AND cp.cr_status =1
							INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_active =1 AND contact.ctt_id = contact.ctt_ref_code 
							INNER JOIN vendor_driver ON vdrv_drv_id = drv_id
                      WHERE  contact.ctt_license_no = "' . $this->drvContact->ctt_license_no . '" 
					 AND vdrv_vnd_id =   "' . $this->drv_vendor_id1 . '"
					 AND drv_id=drv_ref_code AND  drv_id <>  "' . $this->drv_id . '"';

			$cdb	 = DBUtil::command($sqlCheck);
			$check	 = $cdb->queryScalar();
			if ($check)
			{
				$this->addError($attribute, 'Driver with this license no already exists');
				return false;
			}
		}

		return true;
	}

	/**
	 * Contact data validate on Insert scenario
	 * @return boolean
	 */
	public function isExisting($attribute, $params)
	{
		$success = true;
		$bool	 = self::isExistDetails($attribute, $this->$attribute, $this->drv_id);

		if ($bool)
		{
			$label	 = $this->getAttributeLabel($attribute);
			$this->addError($attribute, "$label already exists");
			$success = false;
		}

		return $success;
	}

	public static function isExistDetails($field, $value, $id = null)
	{
		$success = false;
		if ($value == '')
		{
			goto end;
		}

		$params = ['value' => $value];

		$sql	 = "SELECT COUNT(1) FROM drivers WHERE drv_active>0 AND $field=:value";
		$count	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		$success = ($count > 0);

		end:
		return $success;
	}

	public function checkDuplicateDocsByDriver($sql)
	{
		//$sql1		 = $sql . ' AND ' . $whereAndQry . $whereOrQry;
		$recordset	 = DBUtil::queryAll($sql);
		$data		 = array_filter($recordset);
		if (count($data) > 0)
		{
			$ctr		 = 1;
			$drvIdStr	 = '';
			foreach ($data as $d)
			{
				if (count($data) == $ctr)
				{
					$drvIdStr .= $d['drv_id'];
				}
				else
				{
					$drvIdStr .= $d['drv_id'] . " ,";
				}
				$ctr++;
			}
			return $ctr;
		}
	}

	public function validateDriverPhone($attribute, $params)
	{
		if (isset($this->drv_phone) && $this->drv_phone > 0)
		{
			$sql = "SELECT
						COUNT(DISTINCT vendor_driver.vdrv_id) AS cnt
					FROM
						`drivers`
					INNER JOIN `vendor_driver` ON vendor_driver.vdrv_drv_id = drivers.drv_id AND vendor_driver.vdrv_active = 1
					WHERE drivers.drv_phone = '$this->drv_phone' 
					AND vendor_driver.vdrv_drv_id = '$this->drv_id' 
					AND vendor_driver.vdrv_vnd_id = '$this->drv_vendor_id1'
					GROUP BY
						drivers.drv_id";
			$cnt = DBUtil::command($sql)->queryScalar();
			if ($cnt > 0)
			{
				$this->addError($attribute, "Phone number already exists.");
				return false;
			}
		}
		return true;
	}

	public function beforeValidate()
	{
		parent::beforeValidate();
		if ($this->drv_doj != null && $this->drv_doj != '')
		{
			if ((date('Y-m-d', strtotime($this->drv_doj)) != date($this->drv_doj)))
			{
				$drvDoj			 = DateTimeFormat::DatePickerToDate($this->drv_doj);
				$this->drv_doj	 = $drvDoj;
			}
		}
		else
		{
			unset($this->drv_doj);
		}
		//if ($this->drv_lic_exp_date != null && $this->drv_lic_exp_date != '')
		if ($this->drvContact->ctt_license_exp_date != null && $this->drvContact->ctt_license_exp_date != '')
		{
			if ((date('Y-m-d', strtotime($this->drvContact->ctt_license_exp_date)) != date($this->drvContact->ctt_license_exp_date)))
			{
				$drvLicExpDate							 = DateTimeFormat::DatePickerToDate($this->drvContact->ctt_license_exp_date);
				$this->drvContact->ctt_license_exp_date	 = $drvLicExpDate;
			}
		}
		else
		{
			unset($this->drvContact->ctt_license_exp_date);
		}
		return true;
	}

	public function checkDuplicate($attribute, $params)
	{
		$scenario	 = $this->scenario;
		$check		 = self::model()->findByUsernamenEmail($this->$attribute);
		if ($scenario == 'signup')
		{
			if ($check)
			{
				$this->addError($attribute, 'Email already exists');
				return false;
			}
		}
		if ($scenario == 'driverupdate')
		{
			if ($check && $check->drv_id != $this->drv_id)
			{
				$this->addError($attribute, 'Email already exists');
				return false;
			}
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
			'bookings'		 => array(self::HAS_MANY, 'Booking', 'bkg_driver_id'),
			'driverDocs'	 => array(self::HAS_MANY, 'DriverDocs', 'drd_drv_id'),
			//'driverCity'	 => array(self::BELONGS_TO, 'Cities', 'drv_city'),
			//'driverState'	 => array(self::BELONGS_TO, 'States', 'drv_state'),
			'drvContact'	 => array(self::BELONGS_TO, 'Contact', 'drv_contact_id'),
			'drvUser'		 => array(self::BELONGS_TO, 'Users', 'drv_user_id'),
			//'drvVendor' => array(self::BELONGS_TO, 'Vendors', 'drv_vendor_id'),
			'vendorDrivers'	 => array(self::HAS_MANY, 'VendorDriver', 'vdrv_drv_id'),
			'driverStats'	 => array(self::HAS_ONE, 'DriverStats', 'drs_drv_id'),
				//'bookingLog' => array(),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'drv_id'					 => 'Drv',
			'drv_id_merge'				 => 'Merge Id',
			'drv_name'					 => 'Driver Name',
			'drv_contact_id'			 => 'Contact',
			'drv_paytm_phone'			 => 'Paytm Phone',
			'drv_vendor_id1'			 => 'Vendor',
			'drv_doj'					 => 'Date of Joining',
			//'drv_issue_auth'			 => 'Driver License is issued by',
			//'drv_lic_exp_date'			 => 'Licence expiry date',
			'drv_zip'					 => 'Zip',
			'drv_bg_checked'			 => 'Background checked',
			'drv_is_attached'			 => 'Is Attached',
			'drv_device'				 => 'Device',
			'drv_last_login'			 => 'Last Login',
			'drv_ip'					 => 'Ip',
			'drv_created'				 => 'Created',
			'drv_modified'				 => 'Modified',
			'drv_active'				 => 'Active',
			'drv_marital_status'		 => 'Marital Status',
			'drv_police_certificate'	 => 'Police Certificate Path',
			'drv_licence_path'			 => 'Licence Path',
			'drv_description'			 => 'Description',
			'drv_history'				 => 'History',
			'drv_ver_adrs_proof'		 => 'Address Proof',
			'drv_ver_licence'			 => 'Licence',
			'drv_ver_police_certificate' => 'Police Certificate',
			'drv_mark_driver_count'		 => 'Remark Bad',
			'drv_mark_driver_status'	 => 'Remark Bad Status',
			'drv_reset_desc'			 => 'Reset Reason',
			'drv_approved'				 => 'Approved',
			'drv_approved_by'			 => 'Approved By',
			'drv_dob_date'				 => 'Date of birth',
			'assigned_vhc_id'			 => 'Assign Vehicle',
			'drv_voter_id'				 => 'Voter Id',
			'drv_verification_code'		 => 'Drv Verification Code',
			'drv_trip_type'				 => 'Trip Type'
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
		$criteria->compare('drv_id', $this->drv_id);
		$criteria->compare('drv_contact_id', $this->drv_contact_id);
		$criteria->compare('drv_name', $this->drv_name, true);
		$criteria->compare('drv_paytm_phone', $this->drv_paytm_phone, true);
		$criteria->compare('drv_vendor_id', $this->drv_vendor_id);
		$criteria->compare('drv_doj', $this->drv_doj, true);
		$criteria->compare('drv_zip', $this->drv_zip);
		$criteria->compare('drv_bg_checked', $this->drv_bg_checked);
		$criteria->compare('drv_device', $this->drv_device, true);
		$criteria->compare('drv_last_login', $this->drv_last_login, true);
		$criteria->compare('drv_ip', $this->drv_ip, true);
		$criteria->compare('drv_created', $this->drv_created, true);
		$criteria->compare('drv_modified', $this->drv_modified, true);
		$criteria->compare('drv_active', $this->drv_active);
		$criteria->compare('drv_verification_code', $this->drv_verification_code, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Drivers the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function afterSave()
	{
		parent::afterSave();
		if ($this->drv_code == null && $this->drv_id > 0)
		{
			$codeArr		 = Filter::getCodeById($this->drv_id, "driver");
			$this->drv_code	 = $codeArr['code'];
		}
		return true;
	}

	public function beforeSave()
	{
		parent::beforeSave();
		$this->drv_ip		 = trim(\Filter::getUserIP());
		$this->drv_device	 = $_SERVER['HTTP_USER_AGENT'];
		$this->drv_modified	 = new CDbExpression('NOW()');
		if ($this->scenario != 'approve')
		{

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
			if ($this->drv_doj != null && $this->drv_doj != '')
			{
				if ((date('Y-m-d', strtotime($this->drv_doj)) != date($this->drv_doj)))
				{
					$drvDoj			 = DateTimeFormat::DatePickerToDate($this->drv_doj);
					$this->drv_doj	 = $drvDoj;
				}
			}
			else
			{
				unset($this->drv_doj);
			}
			if ($this->drvContact->ctt_license_exp_date != null && $this->drvContact->ctt_license_exp_date != '')
			{
				if ((date('Y-m-d', strtotime($this->drvContact->ctt_license_exp_date)) != date($this->drvContact->ctt_license_exp_date)))
				{
					$drvLicExpDate							 = DateTimeFormat::DatePickerToDate($this->drvContact->ctt_license_exp_date);
					$this->drvContact->ctt_license_exp_date	 = $drvLicExpDate;
				}
			}
			else
			{
				unset($this->drvContact->ctt_license_exp_date);
			}
//			if ($this->drv_password1 != "")
//			{
//				//$this->drv_password = md5($this->drv_password1);
//			}
			if ($this->drv_mark_driver_count == '')
			{
				$this->drv_mark_driver_count = 0;
			}
		}
		return true;
	}

	public function findByEmail($email)
	{
		return self::model()->findByAttributes(array('drv_username' => $email));
	}

	public function findByUsernamenEmail($username)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('drv_email', $username, FALSE, 'OR');
		$criteria->compare('drv_username', $username);
		return $this->find($criteria);
	}

	public function findByCode($code)
	{
		$sql = "SELECT COUNT(1) as cnt FROM `drivers` WHERE drivers.drv_code='$code' AND drivers.drv_active>0";
		return DBUtil::command($sql)->queryScalar();
	}

	public function findByUserid($id)
	{
		return self::model()->findByAttributes(array('drv_user_id' => $id));
	}

	public function findById($id)
	{
		return self::model()->findByAttributes(array('drv_id' => $id));
	}

	public function getIdByCode($drvCode)
	{
		return self::model()->findByAttributes(array('drv_code' => $drvCode));
	}

	public function fetchList($qry, $vendorId = '', $active = '')
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = ["t.*,cty.cty_name, GROUP_CONCAT(vnd_name separator ', ') as agt
            "/* ",group_concat(concat(vht_model,'(' ,vhc_number,')' ) separator ', ')  as vhc " */];
		$criteria->compare('drv_active', 1);
		if ($qry['searchname'] != '')
		{
			$criteria->addSearchCondition('drv_name', $qry['searchname'], true);
		}
		if ($qry['searchemail'] != '')
		{
			$criteria->addSearchCondition('drv_email', $qry['searchemail'], true);
		}
		if ($qry['searchphone'] != '')
		{
			$criteria->addSearchCondition('drv_phone', $qry['searchphone'], true);
		}
		if ($qry['vnd_id'] != '')
		{
			$criteria->addCondition('vdrv_vnd_id = ' . $qry['vnd_id']);
		}
		if ($qry['drv_approved'] != '')
		{
			$criteria->addSearchCondition('drv_approved', $qry['drv_approved']);
		}
		if ($qry['searchmarkdriver'] != '')
		{
			$criteria->addCondition("drv_mark_driver_count > '0'");
		}
		if ($vendorId != '')
		{
			$criteria->addCondition('vdrv_vnd_id =' . $vendorId);
		}
		if ($active != '')
		{
			$criteria->compare('drv_active', $active);
		}
		// $criteria->join = " LEFT JOIN vehicle_driver vhd ON drv_id = vhd_driver_id AND vhd_to_date IS NULL";
		$criteria->join		 .= " LEFT JOIN cities cty ON drv_city = cty_id";
		// $criteria->join .= " LEFT JOIN  vehicles vc ON FIND_IN_SET(vhc_id,vhd_vehicle_id)";
		$criteria->join		 .= " LEFT JOIN vendor_driver on vdrv_drv_id = drv_id";
		$criteria->join		 .= " LEFT JOIN vendors ag  ON vnd_id = vdrv_vnd_id";
		// $criteria->join .= " LEFT JOIN vehicle_types vht ON vht_id = vhc_type_id";
		$criteria->group	 = "drv_id";
		$criteria->together	 = true;
		$sort				 = new CSort;
		$sort->attributes	 = array(
			'drv_name', 'drv_email', 'drv_doj', 'drv_created', 'drv_mark_driver_count', 'drv_total_trips'
		);
		//$criteria->string;
		//exit;
		$size				 = 100;
		$dataProvider		 = new CActiveDataProvider($this, array('criteria' => $criteria, 'sort' => $sort, 'pagination' => ['pageSize' => $size]));
		return $dataProvider;
	}

	public function markedBadListByDriverId($drvId)
	{
		$val			 = '"';
		$sql			 = "
				SELECT 
				a.`blg_created`,
				a.`blg_desc`,
				a.`blg_remark_type`,
				b.`bkg_booking_id`,
				b.`bkg_pickup_date`,
				REPLACE(JSON_EXTRACT(`bkg_route_city_names`, '$[0]'), '$val', '') AS from_city_name,
				REPLACE(JSON_EXTRACT(`bkg_route_city_names`, CONCAT('$[', JSON_LENGTH(`bkg_route_city_names`) - 1, ']')), '$val', '') AS to_city_name
				FROM   booking_log a JOIN `booking` b ON a.blg_booking_id = b.bkg_id AND a.blg_mark_driver > 0
				WHERE  a.blg_driver_assigned_id = '" . $drvId . "'";
		$sqlCount		 = "
				SELECT 
				a.`blg_created`
				FROM   booking_log a JOIN `booking` b ON a.blg_booking_id = b.bkg_id AND a.blg_mark_driver > 0
				WHERE  a.blg_driver_assigned_id = '" . $drvId . "'";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) a", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_booking_id', 'blg_remark_type', 'blg_desc'],
				'defaultOrder'	 => 'blg_created DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getdriverDetails($vendorId)
	{

		$qry		 = 'select d2.drv_id, CONCAT(ctt.ctt_first_name," ",ctt.ctt_last_name) AS drv_name,
						phn.phn_phone_no as drv_phone, eml.eml_email_address as drv_email,vdrv_vnd_id as drv_vendor_id,
						d2.drv_mark_driver_count from vendor_driver vd1
						INNER JOIN drivers d1 ON vdrv_drv_id = d1.drv_id
						INNER JOIN drivers d2 ON d2.drv_id = d1.drv_ref_code AND d2.drv_active = 1
						INNER join contact_profile as cp on cp.cr_is_driver = d2.drv_id and cp.cr_status =1
						INNER JOIN contact as ctt on ctt.ctt_id = cp.cr_contact_id
                        INNER JOIN contact as ctt2 on ctt2.ctt_id = ctt.ctt_ref_code and ctt2.ctt_active = 1
						LEFT JOIN contact_phone phn ON phn.phn_contact_id=ctt.ctt_id AND phn.phn_is_primary=1 AND phn.phn_active=1
						LEFT JOIN contact_email eml ON eml.eml_contact_id=ctt.ctt_id AND eml.eml_is_primary=1 AND eml.eml_active=1
						WHERE vdrv_vnd_id = ' . $vendorId . " AND d2.drv_is_freeze <> 1 AND vd1.vdrv_active = 1 GROUP BY d2.drv_ref_code";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getDetails($vendorId)
	{
		$qry = "SELECT d2.drv_id, d2.drv_name, d2.drv_approved, contact.ctt_first_name, contact.ctt_last_name,contact.ctt_license_no ,
				contact_phone.phn_phone_no as drv_phone, contact_email.eml_email_address as drv_email,
				contact_phone.phn_is_verified as isPhVerified, contact_email.eml_is_verified as isEmlVerified,
				d2.drv_mark_driver_count, d2.drv_created, IF(d2.drv_id IS NULL, 0, 1) AS verify_check,
				cities.cty_name AS drv_city, contact.ctt_city, vendor_driver.vdrv_vnd_id AS drv_vendor_id,
				vendor_driver.vdrv_id, vendor_driver.vdrv_active, IF(document.doc_id IS NOT NULL, 0, 1) AS documentUpload
				FROM `vendor_driver`
				INNER JOIN `drivers` d1 ON d1.drv_id = vendor_driver.vdrv_drv_id AND d1.drv_active = 1
				INNER JOIN `drivers` d2 ON d2.drv_id = d1.drv_ref_code
				INNER JOIN contact_profile as cp on cp.cr_is_driver = d2.drv_id and cp.cr_status =1
				INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id and contact.ctt_active=1 and contact.ctt_ref_code=contact.ctt_id
				LEFT JOIN `contact_email` ON contact.ctt_id = contact_email.eml_contact_id AND contact_email.eml_is_primary = 1 AND contact_email.eml_active = 1
				LEFT JOIN `contact_phone` ON contact.ctt_id = contact_phone.phn_contact_id AND contact_phone.phn_is_primary = 1 AND contact_phone.phn_active = 1
				LEFT JOIN `document` ON document.doc_id = contact.ctt_license_doc_id AND document.doc_active = 1 AND document.doc_status IN(0,1) AND document.doc_type = 5 AND document.doc_file_front_path IS NOT NULL
				LEFT JOIN `cities` ON cities.cty_id = contact.ctt_city
				WHERE vendor_driver.vdrv_active = 1 AND vendor_driver.vdrv_vnd_id=:vendorId
				GROUP BY d2.drv_id ORDER BY d2.drv_id DESC";

		$recordset = DBUtil::query($qry, DBUtil::SDB(), ["vendorId" => $vendorId]);
		return $recordset;
	}

	public function getDetailsAdmin($page_no = 1, $total_count = 0, $search_txt = '', $drv_ids = '')
	{
		$offset = $page_no * 20;

		$qry1 = "SELECT d1.drv_id, CONCAT(ctt.ctt_first_name, ' ', ctt.ctt_last_name) drv_name, cttphone.phn_phone_no AS drv_phone, cttemail.eml_email_address AS drv_email,
				ctt.ctt_license_no AS drv_lic_number, ctt.ctt_voter_no, d1.drv_mark_driver_count, IF(d2.drv_id IS NULL, 0, 1) AS verify_check,
				c1.cty_name AS drv_city, d1.drv_created, (vendors.vnd_name) AS drv_vnd_names, vndDrv.vdrv_vnd_id AS vnd_id
				FROM drivers d3
				INNER JOIN drivers d1 ON d1.drv_id = d3.drv_ref_code 
				INNER JOIN contact_profile ON contact_profile.cr_is_driver = d1.drv_id AND cr_status=1  
				INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id 
				INNER JOIN contact ctt ON contact.ctt_ref_code = ctt.ctt_id
				LEFT JOIN contact_email cttemail ON cttemail.eml_contact_id = ctt.ctt_id AND cttemail.eml_is_primary = 1 AND eml_active = 1
				LEFT JOIN contact_phone cttphone ON cttphone.phn_contact_id = ctt.ctt_id AND cttphone.phn_is_primary = 1 AND phn_active = 1
				LEFT JOIN cities c1 ON c1.cty_id = ctt.ctt_city
				LEFT JOIN drivers_info d2 ON d2.drv_driver_id = d1.drv_id";

		$qry2 = "SELECT COUNT(distinct d1.drv_id) cnt 
				FROM drivers d3 
				INNER JOIN drivers d1 ON d1.drv_id = d3.drv_ref_code 
				INNER JOIN contact_profile ON contact_profile.cr_is_driver = d1.drv_id AND cr_status=1  
				INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id 
				INNER JOIN contact ctt ON contact.ctt_ref_code = ctt.ctt_id
				LEFT JOIN contact_email cttemail ON cttemail.eml_contact_id = ctt.ctt_id AND cttemail.eml_is_primary = 1 AND eml_active = 1 
				LEFT JOIN contact_phone cttphone ON cttphone.phn_contact_id = ctt.ctt_id AND cttphone.phn_is_primary = 1 AND phn_active = 1";

		$qry .= " INNER JOIN vendor_driver vndDrv ON d1.drv_id = vndDrv.vdrv_drv_id AND vndDrv.vdrv_active = 1
				  INNER JOIN vendors v1 ON v1.vnd_id = vndDrv.vdrv_vnd_id
				  INNER JOIN vendors ON vendors.vnd_id = v1.vnd_ref_code
				  WHERE d1.drv_is_freeze <> 1 and d1.drv_active = 1";

		if ($search_txt != '')
		{
			$search_txt	 = str_replace(' ', '%', $search_txt);
			$qry		 .= " AND (d1.drv_code LIKE '%$search_txt%' OR cttphone.phn_phone_no LIKE '%$search_txt%'
						OR cttemail.eml_email_address LIKE '%$search_txt%' OR contact.ctt_name LIKE '%$search_txt%')";
		}

		$qry .= ($drv_ids != '') ? " AND d1.drv_id IN ($drv_ids)" : "";
		$qry .= ($total_count == 0) ? " GROUP BY d1.drv_id LIMIT 20 OFFSET $offset" : "";
		$sql = ($total_count == 0) ? $qry1 . $qry : $qry2 . $qry;
		if ($total_count == 0)
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
		else
		{
			return DBUtil::queryScalar($sql, DBUtil::SDB());
		}
	}

	public function getByVendor($vendorId)
	{
		$criteria		 = new CDbCriteria();
		// $criteria->with=['dr']
		$criteria->compare('drv_vendor_id', $vendorId);
		$criteria->addcondition('drv_is_freeze != 1');
		$criteria->order = "drv_name ASC";
		return $this->findAll($criteria);
	}

	public function getJSONbyVendor($vendorId)
	{
		$models		 = VendorDriver::getDriverListbyVendorid($vendorId, true);
		$arrDriver	 = array();

		/* @var $model Drivers */
		foreach ($models as $model)
		{
			$isBlocked	 = ($model['drv_is_freeze'] == 1) ? '(Blocked)' : '';
			$arrDriver[] = array("id"	 => $model['drv_id'],
				"text"	 => $model['drv_name'] . '(' . $model['drv_phone'] . ')' . $isBlocked);
		}
		$data = CJSON::encode($arrDriver);
		return $data;
	}

	public function addDriver($data, $vendorId)
	{
		$success						 = false;
		$errors							 = [];
		$userInfo						 = UserInfo::getInstance();
		$model							 = $this;
		//$model->attributes	 = $data;
		$model->drv_name				 = $data['drv_name'];
		$model->scenario				 = 'addapp';
		$modelContact					 = new Contact();
		$modelContactEmail				 = new ContactEmail();
		$modelContactPhone				 = new ContactPhone();
		$modelContact->ctt_license_no	 = $data['drv_lic_number'];
		$modelContact->ctt_aadhaar_no	 = $data['drv_aadhaar_number'];
		$drvName						 = explode(' ', $data['drv_name']);
		$modelContact->ctt_first_name	 = $drvName[0];
		$modelContact->ctt_last_name	 = $drvName[1];
		try
		{
			$transaction	 = DBUtil::beginTransaction();
			$isPhoneExist	 = $modelContactPhone->findPhoneIdByPhoneNumber($data['drv_phone']);
			if (!empty($isPhoneExist))
			{
				$errors = "Phone Number already Exist.";
				throw new Exception($errors);
			}
			$isEmailExist = $modelContactEmail->findEmailIdByEmail($data['drv_email']);
			if (!empty($isEmailExist))
			{
				$errors = "Email Id already Exist.";
				throw new Exception($errors);
			}

			if ($model->validate())
			{
				if ($model->save())
				{
					//$modelContact->save();
					$modelContact->addType		 = 1;
					$modelContact->contactEmails = $modelContact->convertToContactEmailObjects($data['drv_email']);
					$modelContact->contactPhones = $modelContact->convertToContactPhoneObjects($data['drv_phone']);
					$modelContact->save();
					if ($data['drv_email'] != NULL)
					{
						$modelContact->saveEmails();
						ContactEmail::setPrimaryEmail($modelContact->ctt_id);
					}
					$modelContact->savePhones();
					ContactPhone::setPrimaryPhone($modelContact->ctt_id);

					$model->drv_contact_id	 = $modelContact->ctt_id;
					$model->drv_is_attached	 = 0;
					$model->drv_code		 = '';
					$model->drv_ref_code	 = $model->drv_id;
					$model->save();

					// Logger::create('DRIVER ID : ' . $model->drv_id, CLogger::LEVEL_TRACE);
					$codeArr = Filter::getCodeById($model->drv_id, "driver");
					//$codeArr = Filter::getCodeById($model->drv_id, "driver");
					if ($codeArr['success'] == 1)
					{
						$model->drv_code = $codeArr['code'];
						//$model->drv_is_attached	 = 0;
						if ($model->save())
						{

							$arr = ['driver' => $model->drv_id, 'vendor' => $vendorId];

							$return = VendorDriver::model()->checkAndSave($arr);
							Logger::create('VENDOR DRIVER : ' . $return, CLogger::LEVEL_TRACE);
							if ($return == true)
							{
								$desc = "Driver is Created.";

								/*
								 * New Driver is added to User table.
								 */
								$userData	 = Users::model()->findByPhone($data['drv_phone']);
								$msgBody	 = "";
								if (count($userData) > 1)
								{
									$model->drv_user_id = $userData['user_id'];
									$model->save();

									self::notifyToAlreadyRegistered($model->drv_id);

//									$response			 = WhatsappLog::alreadyRegistered($model->drv_id, $data['drv_name'], $data['drv_phone']);
//									$msgBody			 = "You are already registered with us. Please login with your exiting credentials.";
								}
								else
								{
									self::notifyToDriverCompleteRegistrationReminder($model->drv_id);

//									$response	 = WhatsappLog::driverCompleteRegistrationReminder($model->drv_id, $data['drv_name'], $data['drv_phone']);
//									$msgBody	 = "You are registered with us. Please complete further driver registration steps through app.";
								}

//								if ($response['status'] == 3)
//								{
//									$smsLog = new smsWrapper();
//									$smsLog->sendDriverConfermation($data['drv_country_code'], $data['drv_phone'], $msgBody);
//								}
								////////////////////////
//								$event_id	 = DriversLog::DRIVER_CREATED;
//								DriversLog::model()->createLog($model->drv_id, $desc, $userInfo, $event_id, false, false);
								DBUtil::commitTransaction($transaction);
								$success = true;
								$errors	 = [];
							}
							else
							{
								$errors = "Driver log not created.";
								throw new Exception($errors);
							}
						}
						else
						{
							$errors = "Driver code not created";
							throw new Exception($errors);
						}
					}
					else
					{
						$errors = "Driver code not created";
						throw new Exception($errors);
					}
				}
				else
				{
					$errors = "Driver creation failed.";
					throw new Exception($errors);
				}
			}
			else
			{
				$errors = "Driver validation failed.";
				throw new Exception(json_encode($errors));
			}
		}
		catch (Exception $e)
		{
			$errors = $e->getMessage();
			DBUtil::rollbackTransaction($transaction);
		}
		return ['success' => $success, 'errors' => $errors];
	}

	public function addFromJson($data, $vendorId)
	{
		$success = $this->addDriver($data, $vendorId);
		return $success;
	}

	public function add($data, $vendorId)
	{
		$model					 = $this;
		$model->drv_vendor_id	 = $vendorId;
		$model->attributes		 = $data;
		$success				 = false;
		if ($model->validate())
		{
			try
			{
				$success = $model->save();
			}
			catch (Exception $e)
			{
				$success = false;
				$model->addError('drv_id', $e->getMessage());
			}
		}
		return $success;
	}

	/**
	 * This function is used for finding the driver pk Id
	 * @param type $driverUserId
	 * @return type
	 */
	public function getDriverId($driverUserId)
	{
		if (empty($driverUserId))
		{
			return null;
		}
		$findDriverId	 = "SELECT drv_id FROM `drivers` WHERE `drv_user_id`= $driverUserId LIMIT 0,1";
		$arrDriverId	 = DBUtil::queryRow($findDriverId, DBUtil::SDB());
		//Returns if no data is found
		if (empty($arrDriverId))
		{
			return null;
		}
		return $arrDriverId["drv_id"];
	}

	public function getDriverByVendor($vnd_id)
	{
		$sql = "SELECT drv_id, drv_name FROM   `drivers` 
			 INNER JOIN vendor_driver vdrv ON vdrv_drv_id = drv_id
             LEFT JOIN vendors ag ON vnd_id = vdrv_vnd_id
             WHERE  (drv_active = 1) AND (vdrv_vnd_id = '$vnd_id')";

		return DBUtil::command($sql, DBUtil::SDB())->setFetchMode(PDO::FETCH_OBJ)->queryAll();
	}

	public function getJSONDrivers($vnd_id)
	{

		$models		 = $this->getDriverByVendor($vnd_id);
		$arrDrivers	 = array();
		foreach ($models as $model)
		{
			$arrDrivers[] = array("id" => $model->drv_id, "text" => $model->drv_name);
		}
		$data = CJSON::encode($arrDrivers);
		return $data;
	}

	public static function getStatsByZone()
	{
		$sql = "SELECT 
				zones.zon_id, 
				COUNT(1) as totalCount, 
				SUM(IF(lastLoggedIn > DATE_SUB(NOW(),INTERVAL 30 DAY),1,0)) as totalLoggedIn
				FROM `drivers` 
                INNER JOIN `contact` ON contact.ctt_id=drivers.drv_contact_id AND contact.ctt_active=1  AND drivers.drv_active=1
                INNER JOIN `contact_profile` ON contact_profile.cr_contact_id=contact.ctt_id AND contact_profile.cr_is_driver=drivers.drv_id 
                AND contact_profile.cr_status=1
				INNER JOIN `zone_cities` ON zone_cities.zct_cty_id=contact.ctt_city AND zone_cities.zct_active=1
				INNER JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id AND zones.zon_active=1
				LEFT JOIN
				(
					SELECT MAX(app_tokens.apt_last_login) as lastLoggedIn, 
					app_tokens.apt_user_id 
					FROM `app_tokens` WHERE app_tokens.apt_user_type=5 
					AND app_tokens.apt_status=1 AND app_tokens.apt_user_id > 0
					GROUP BY app_tokens.apt_user_id
				) as token ON token.apt_user_id=drivers.drv_id 
				GROUP BY zones.zon_id";
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
			$areaId		 = $val['zon_id'];
			$type		 = 3;
			$totalCount	 = $val['totalCount'];
			$activeCount = $val['totalLoggedIn'];
			InventoryStats::addInventory($areaType, $areaId, $type, null, $totalCount, $activeCount);
			$ctr++;
		}
	}

	public function updateDriverMarkCount($drvId)
	{
		$sql = "UPDATE drivers
				SET    drv_mark_driver_count = drv_mark_driver_count + 1
				WHERE  drv_id IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				WHERE d1.drv_id='$drvId')";
		/* @var $cdb CDbCommand */
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function updateDriverCredit($drvId, $credit)
	{
		$sql = "UPDATE `drivers` SET `drv_credit`=drv_credit+$credit WHERE drv_id IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				WHERE d1.drv_id='$drvId')";
		/* @var $cdb CDbCommand */
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function updateOntimeCount($drvId)
	{
		$sql = "UPDATE `drivers` SET drivers.drv_ontime_count=(drivers.drv_ontime_count+1) WHERE drivers.drv_id IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				WHERE d1.drv_id='$drvId')";
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function updateSoftspokonCount($drvId)
	{
		$sql = "UPDATE `drivers` SET drivers.drv_softspokon_count=(drivers.drv_softspokon_count+1) WHERE drivers.drv_id IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				WHERE d1.drv_id='$drvId')";
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function updateRespectfullyCount($drvId)
	{
		$sql = "UPDATE `drivers` SET drivers.drv_respectfully_count=(drivers.drv_respectfully_count+1) WHERE drivers.drv_id IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				WHERE d1.drv_id='$drvId')";
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function updateHelpfulCount($drvId)
	{
		$sql = "UPDATE `drivers` SET drivers.drv_helpful_count=(drivers.drv_helpful_count+1) WHERE drivers.drv_id IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				WHERE d1.drv_id='$drvId')";
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function updateSafelyCount($drvId)
	{
		$sql = "UPDATE `drivers` SET drivers.drv_safely_count=(drivers.drv_safely_count+1) WHERE drivers.drv_id IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				WHERE d1.drv_id='$drvId')";
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function checkDriverMarkCount($drvId)
	{
		$sql	 = "SELECT `drv_mark_driver_count` FROM `drivers` WHERE `drv_mark_driver_count`>0  AND `drv_id` IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				WHERE d1.drv_id='$drvId')  AND drivers.drv_id = drivers.drv_ref_code";
		/* @var $cdb CDbCommand */
		$cdb	 = DBUtil::command($sql);
		$Search	 = $cdb->queryRow();
		$count	 = trim($Search['drv_mark_driver_count']);
		return $count;
	}

	public function resetMarkBadByDrvId($drvId)
	{
		if ($drvId != '')
		{
			$model							 = new Drivers();
			$drvModel						 = $model->findByPk($drvId);
			$drvModel->drv_id				 = $drvId;
			$drvModel->drv_mark_driver_count = 0;
			$drvModel->update();
			return true;
		}
		return false;
	}

	public function updateDetails($drv_id = 0)
	{
		$returnset = new ReturnSet();
		try
		{
			// Adding Blank Record in DriverStats (If not exists)
			DriverStats::model()->insertEmplyStats(0, $drv_id);
			DriverStats::model()->insertEmplyStats(1, $drv_id);

			// Rating
			Ratings::model()->getDriverAveragerating($drv_id);
			$whereQry1 = $drv_id > 0 ? " AND ds.drs_drv_id=$drv_id " : '';
//			$selectqry1	 = "SELECT drs_drv_id,drs_drv_overall_rating FROM driver_stats WHERE 1 AND drs_drv_overall_rating > 0 AND drs_modified_date > DATE_SUB(NOW(),INTERVAL 5 DAY) $whereQry1";
//			$resultqry1	 = DBUtil::query($selectqry1, DBUtil::MDB());
//			foreach ($resultqry1 as $val)
//			{
//				try
//				{
//					$updateqry1 = "UPDATE drivers SET drv_overall_rating ='{$val['drs_drv_overall_rating']}' WHERE IFNULL(drv_ref_code, drv_id)={$val['drs_drv_id']}";
//					DBUtil::execute($updateqry1);
//				}
//				catch (Exception $ex)
//				{
//					Logger::writeToConsole($ex->getMessage());
//				}
//			}

			try
			{
				$updateqry1 = "UPDATE drivers drv
								INNER JOIN driver_stats ds ON ds.drs_drv_id = IFNULL(drv.drv_ref_code, drv.drv_id) 
								SET drv.drv_overall_rating = ds.drs_drv_overall_rating
								WHERE 1 AND ds.drs_drv_overall_rating > 0 AND ds.drs_modified_date > DATE_SUB(NOW(),INTERVAL 5 DAY) $whereQry1";
				DBUtil::execute($updateqry1);
			}
			catch (Exception $ex)
			{
				Logger::writeToConsole($ex->getMessage());
			}

			// Total KMs
			$whereQry2	 = $drv_id > 0 ? " AND  drv_id=$drv_id " : '';
			$selectqry2	 = "SELECT * FROM  (SELECT IFNULL(drivers.drv_ref_code, bcb_driver_id) as code,  SUM(booking.bkg_trip_distance)  AS total_km
							FROM booking
							INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
							INNER JOIN drivers ON  drv_id=bcb_driver_id $whereQry2
							WHERE booking.bkg_active=1
							AND booking.bkg_status IN (6,7) 
							AND (booking.bkg_pickup_date)>='2015-10-25 00:00:00'
							AND booking.bkg_booking_type=1
							AND booking_cab.bcb_driver_id>0 
							GROUP BY code) a
							WHERE total_km>0";
			$resultqry2	 = DBUtil::query($selectqry2, DBUtil::SDB());
			foreach ($resultqry2 as $val)
			{
				try
				{
					$updateqry2 = "UPDATE drivers SET drivers.drv_gozo_kms ={$val['total_km']} WHERE drv_id ={$val['code']} $whereQry2";
					DBUtil::execute($updateqry2);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

			// Driver Total Trips
			$whereQry3	 = $drv_id > 0 ? " AND  drv_id=$drv_id " : '';
			$selectqry3	 = "SELECT IFNULL(drivers.drv_ref_code, bcb_driver_id) as code, COUNT(*) AS total
							FROM booking
							INNER JOIN booking_cab ON bcb_id=bkg_bcb_id AND booking_cab.bcb_active=1
							INNER JOIN drivers ON  drv_id=bcb_driver_id $whereQry3
							WHERE bkg_active=1 AND bkg_status IN (6,7) AND (bkg_pickup_date)>='2015-10-25 00:00:00'  AND bcb_driver_id is not null
							GROUP BY code";
			$resultqry3	 = DBUtil::query($selectqry3, DBUtil::SDB());
			foreach ($resultqry3 as $val)
			{
				try
				{
					$updateqry3 = "UPDATE drivers SET drv_total_trips={$val['total']} WHERE drv_id ={$val['code']} $whereQry3";
					DBUtil::execute($updateqry3);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

			// Last 30 days trip count
			$whereQry4	 = $drv_id > 0 ? " AND  drv_id=$drv_id " : '';
			$selectqry4	 = "SELECT IFNULL(drivers.drv_ref_code, bcb_driver_id) as code, COUNT(*) AS total
							FROM booking
							INNER JOIN booking_cab ON bcb_id=bkg_bcb_id AND booking_cab.bcb_active=1
							INNER JOIN drivers ON  drv_id=bcb_driver_id $whereQry4
							WHERE bkg_active=1 AND bkg_status IN (6,7)  AND bkg_pickup_date>DATE_ADD(NOW(), INTERVAL -30 DAY)
							GROUP BY code";
			$resultqry4	 = DBUtil::query($selectqry4, DBUtil::SDB());
			foreach ($resultqry4 as $val)
			{
				try
				{
					$updateqry4 = "UPDATE drivers SET drv_last_thirtyday_trips ={$val['total']} WHERE drv_id={$val['code']} $whereQry4";
					DBUtil::execute($updateqry4);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

			// Driver Stats total trips, last trip date
			$whereQry5	 = $drv_id > 0 ? " AND  drv_id=$drv_id " : '';
			$whereQry51	 = $drv_id > 0 ? " AND  drs_drv_id=$drv_id " : '';
			$selectqry5	 = " SELECT IFNULL(drivers.drv_ref_code, bcb_driver_id) as code,
							COUNT(1) AS total,
							MAX(booking.bkg_pickup_date) AS last_trip,
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
							FROM `booking`
							INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 
							INNER JOIN drivers ON  drv_id=bcb_driver_id $whereQry5
							AND booking.bkg_active = 1 
							AND booking.bkg_status IN (6, 7) 
							AND booking_cab.bcb_driver_id IS NOT NULL
							WHERE bkg_pickup_date>='2015-10-25'
							GROUP BY code";
			$resultqry5	 = DBUtil::query($selectqry5, DBUtil::SDB());
			foreach ($resultqry5 as $val)
			{
				try
				{
					$updateqry5 = "UPDATE `driver_stats` 
                                                        SET 
                                                        driver_stats.drs_total_trips = {$val['total']}
                                                        ,driver_stats.drs_last_trip_date ='{$val['last_trip']}'                                                             
                                                        ,driver_stats.drs_OW_Count ='{$val['OW_Count']}'
                                                        ,driver_stats.drs_RT_Count ='{$val['RT_Count']}'
                                                        ,driver_stats.drs_AT_Count ='{$val['AT_Count']}'
                                                        ,driver_stats.drs_PT_Count ='{$val['PT_Count']}'
                                                        ,driver_stats.drs_FL_Count ='{$val['FL_Count']}'
                                                        ,driver_stats.drs_SH_Count ='{$val['SH_Count']}'
                                                        ,driver_stats.drs_CT_Count ='{$val['CT_Count']}'
                                                        ,driver_stats.drs_DR_4HR_Count ='{$val['DR_4HR_Count']}'
                                                        ,driver_stats.drs_DR_8HR_Count ='{$val['DR_8HR_Count']}'
                                                        ,driver_stats.drs_DR_12HR_Count ='{$val['DR_12HR_Count']}'
                                                        ,driver_stats.drs_AP_Count ='{$val['AP_Count']}' WHERE driver_stats.drs_drv_id= {$val['code']} $whereQry51";
					DBUtil::execute($updateqry5);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

			// Driver Stats last loggedin
			$whereQry6	 = $drv_id > 0 ? " AND  drv_id=$drv_id " : '';
			$whereQry61	 = $drv_id > 0 ? " AND  drs_drv_id=$drv_id " : '';
			$selectqry6	 = "SELECT IFNULL(drv_ref_code, drv_id) as driver_id,
						    MAX(app_tokens.apt_last_login) AS last_login
							FROM`app_tokens` INNER JOIN drivers ON drv_id=app_tokens.apt_entity_id AND app_tokens.apt_user_type = 5 $whereQry6
							WHERE app_tokens.apt_entity_id IS NOT NULL AND app_tokens.apt_status = 1 GROUP BY driver_id";
			$resultqry6	 = DBUtil::query($selectqry6, DBUtil::SDB());
			foreach ($resultqry6 as $val)
			{
				try
				{
					$updateqry6 = "UPDATE  `driver_stats`
				                   SET driver_stats.drs_last_logged_in = '{$val['last_login']}' 
								   WHERE 1 AND driver_stats.drs_drv_id = {$val['driver_id']}  $whereQry61";
					DBUtil::execute($updateqry6);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}


			$whereQry7	 = $drv_id > 0 ? " AND  booking_cab.bcb_driver_id=$drv_id " : '';
			$selectqry7	 = "SELECT 
							temp.driverIds AS driverIds,
							SUM(IF(temp.driverRideStartCount=1,1,0)) AS driverRideStartCount,
							SUM(IF(temp.driverRideCompleteCount=1,1,0)) AS driverRideCompleteCount 
							FROM 
							(
								SELECT            
								booking_cab.bcb_driver_id AS driverIds,
								IF(btk.bkg_ride_start=1,1,0) AS driverRideStartCount,
								IF(btk.bkg_ride_complete=1,1,0) AS driverRideCompleteCount 
								FROM booking 
								INNER JOIN booking_track btk ON bkg_id = btk_bkg_id
								INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
								WHERE 1 
								$whereQry7
								AND booking.bkg_status  IN (6,7)
								AND booking_cab.bcb_driver_id >0
								AND booking.bkg_pickup_date>='2015-10-25' 
							) temp WHERE 1 GROUP BY temp.driverIds;";
			$resultqry7	 = DBUtil::query($selectqry7, DBUtil::SDB());
			foreach ($resultqry7 as $val)
			{
				try
				{
					$updateqry7 = "UPDATE  `driver_stats`
				                   SET driver_stats.drs_app_trip_count_started = '{$val['driverRideStartCount']}',
									driver_stats.drs_app_trip_count_completed = '{$val['driverRideCompleteCount']}'
								   WHERE 1 AND driver_stats.drs_drv_id = {$val['driverIds']}";
					DBUtil::execute($updateqry7);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

			$returnset->setStatus(true);
			$returnset->setMessage("Driver Statistical Data Update Successfully");
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$returnset->setStatus(false);
			$returnset->setMessage("Unable To Update Driver Statistical Data");
		}
		return $returnset;
	}

	public function addLog($oldData, $newData)
	{
		if ($oldData)
		{
			$getDifference	 = array_diff_assoc($oldData, $newData);
			$remark			 = $this->drv_log;
			$dt				 = date('Y-m-d H:i:s');
			$user			 = Yii::app()->user->getId();
			//if ($remark) {
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
				//}
			}
		}
		return $remark;
	}

	public function getCode($vendorId)
	{
		return Yii::app()->shortHash->hash($vendorId);
	}

	public function checkVerifiedByvendor($id)
	{
		$model = DriversInfo::model()->find('drv_driver_id=:id', ['id' => $id]);
		if ($model != '')
		{
			return true;
		}
		return false;
	}

	/**
	 * @deprecated since version 10-10-2019
	 * @author ramala
	 */
	public function listToVerify($vendorId = '', $active = '')
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = ["t.*,cty.cty_name,vnd_id as drv_vendor_id1,vnd_name as agt,group_concat(concat(vht_model,'(' ,vhc_number,')' ) separator ',')  as vhc"];
		$criteria->compare('drv_active', 1);

		if ($vendorId != '')
		{
			$criteria->compare('vdrv_vnd_id', $vendorId);
		}
		if ($active != '')
		{
			$criteria->compare('drv_active', $active);
		}
		if ($this->drv_name != '')
		{
			$criteria->compare('LOWER(drv_name)', strtolower($this->drv_name), true);
		}
		if ($this->drv_email != '')
		{
			$criteria->compare('LOWER(drv_email)', strtolower($this->drv_email), true);
		}
		if ($this->drv_phone != '')
		{
			$criteria->compare('drv_phone', $this->drv_phone, true);
		}
		$criteria->join		 = " LEFT JOIN vehicle_driver vhd ON drv_id = vhd_driver_id AND vhd_to_date IS NULL";
		$criteria->join		 .= " LEFT JOIN cities cty ON drv_city = cty_id";
		$criteria->join		 .= " LEFT JOIN vehicles vc ON FIND_IN_SET(vhc_id,vhd_vehicle_id)";
		$criteria->join		 .= " LEFT JOIN vendor_driver vvhc ON vdrv_drv_id = drv_id";
		$criteria->join		 .= " LEFT JOIN vendors ag ON vnd_id = vdrv_vnd_id";
		$criteria->join		 .= " LEFT JOIN vehicle_types vht ON vht_id = vhc_type_id";
		$criteria->group	 = "drv_id";
		// $criteria->order = 'drv_name DESC';
		$criteria->together	 = true;
		$sort				 = new CSort;
		$sort->attributes	 = array(
			'drv_name', 'drv_email', 'drv_doj', 'drv_created', 'drv_mark_driver_count'
		);

		$dataProvider = new CActiveDataProvider($this, array('criteria' => $criteria, 'sort' => $sort));
		return $dataProvider;
	}

	public function checkExistingDriver($qry = [])
	{
		$returnSet	 = new ReturnSet();
		$returnSet->setStatus(false);
		$sql		 = 'SELECT DISTINCT drv_id FROM `drivers` drv
                INNER JOIN contact ctt on drv.drv_contact_id = ctt.ctt_id
                LEFT JOIN contact_phone on contact_phone.phn_contact_id = ctt.ctt_id
                LEFT JOIN vendor_driver vdrv on drv.drv_id = vdrv.vdrv_drv_id
                WHERE drv_active = 1';

		$whereAnd	 = [];
		$whereOr	 = [];
		if (count($qry) == 0)
		{
			$returnSet->addError("No parameters found", 1);
			goto end;
		}

		$whereAnd[]	 = "LOWER(drv_name) LIKE '%" . strtolower($qry['drv_name']) . "%'";
		$whereAnd[]	 = "phn_phone_no LIKE '%" . $qry['drv_phone'] . "%'";

		if ($qry['drv_lic_number'] != '')
		{
			$whereAnd[] = "ctt_license_no = '" . trim($qry['drv_lic_number']) . "'";
		}
		if ($qry['drv_aadhaar_number'] != '')
		{
			$whereOr[] = "ctt_aadhaar_no = '" . trim($qry['drv_aadhaar_number']) . "'";
		}
		if ($qry['drv_pan_no'] != '')
		{
			$whereOr[] = "ctt_pan_no = '" . trim($qry['drv_pan_no']) . "'";
		}
		if ($qry['drv_voter_id'] != '')
		{
			$whereOr[] = "ctt_voter_no = '" . trim($qry['drv_voter_id']) . "'";
		}

		$whereAndQry = implode(' AND ', array_filter($whereAnd));
		$whereOrQry	 = '';
		if (sizeof($whereOr) > 0)
		{
			$whereOrQry = ' OR (' . implode(' OR ', $whereOr) . ')';
		}

		$sql1		 = $sql . ' AND ' . $whereAndQry . $whereOrQry;
		$recordset	 = DBUtil::query($sql1);
		if ($recordset->getRowCount() == 0)
		{
			$returnSet->setStatus(true);
			goto end;
		}

		$drvIds = [];
		foreach ($recordset as $d)
		{
			$drvIds[] = $d['drv_id'];
		}
		$drvIdStr	 = implode(",", $drvIds);
		$errors		 = ['drv_ids' => $drvIdStr, 'msg' => 'Driver already exists'];
		$returnSet->setErrors($errors, 1);

		end:
		return $returnSet;
	}

	public function checkExisting($qry1 = [])
	{
		$qry = array_filter($qry1);
		if (sizeof($qry) > 0)
		{
			$where	 = 'WHERE ';
			$where1	 = '';
			$where2	 = [];
			$sql1	 = '';
			$sql2	 = '';
			$drvName = '';
			if ($qry['drv_name'] != '')
			{
				$drvName = " LOWER(drv_name) = '" . strtolower($qry['drv_name']) . "'";
			}
			$contDetails = Contact:: model()->getContactDetails($qry['drv_contact_id']);

			$drvPhone = '';
			if ($contDetails['phn_phone_no'] != '')
			{
				$drvPhone = " phn_phone_no = '" . $contDetails['phn_phone_no'] . "'";
			}

			$drvLicNumber = '';
			if ($contDetails['ctt_license_no'] != '')
			{
				$drvLicNumber = "LOWER(ctt_license_no) = '" . strtolower($contDetails['ctt_license_no']) . "'";
			}
			if ($qry['drv_name'] != '')
			{
				$sql1 = ' (' . $drvName . ' ) ';
			}
			if ($qry['drv_name'] != '' && $contDetails['phn_phone_no'] != '')
			{
				$sql1 = ' (' . $drvName . ' AND ' . $drvPhone . ') ';
			}
			if ($contDetails['ctt_license_no'] != '')
			{
				$sql2 = ' (' . $drvLicNumber . ') ';
			}
			$where5	 = ($sql1 != '' && $sql2 != '') ? $sql1 . ' OR ' . $sql2 : $sql1 . $sql2;
			$where4	 = (trim($where5) != '') ? $where5 : '';

			if ($qry['drv_vendor_id1'] != '')
			{
				$where1 .= "vdrv_vnd_id = " . $qry['drv_vendor_id1'] . ' AND ';
			}
			if ($contDetails['ctt_aadhaar_no'] != '')
			{
				$where2[] = "LOWER(ctt_aadhaar_no) = '" . strtolower($contDetails['ctt_aadhaar_no']) . "'";
			}
			if ($contDetails['ctt_pan_no'] != '')
			{
				$where2[] = "ctt_pan_no = '" . $contDetails['ctt_pan_no'] . "'";
			}
			if ($contDetails['ctt_voter_no'] != '')
			{
				$where2[] = "LOWER(ctt_voter_no) = '" . strtolower($contDetails['ctt_voter_no']) . "'";
			}
			$where3 = '';
			if (sizeof($where2) > 0)
			{
				$where3	 = ' OR (' . implode(' OR ', $where2) . ')';
				$where3	 = ($where4 != '') ? $where3 . ' OR ' . $where4 : $where3;
				$where4	 = '';
			}
			$where3		 = trim(trim($where3), 'OR');
			$where		 .= $where1 . $where3 . $where4;
			$sql		 = 'SELECT drv_id,count(vdrv_drv_id) assigned, 
					GROUP_CONCAT(vdrv_vnd_id) vendors  FROM ' . $this->tableName() . ' drv
					LEFT JOIN contact ON contact.ctt_id = drv.drv_contact_id
					LEFT JOIN contact_phone ON contact_phone.phn_contact_id=contact.ctt_id	
					LEFT JOIN vendor_driver vdrv on drv.drv_id = vdrv.vdrv_drv_id ' . $where;
			$recordset	 = DBUtil::queryAll($sql);
			$data		 = array_filter($recordset);
			return $data;
		}
		return false;
	}

	public function getExistingDetails()
	{
		$data = $this->checkExisting($this->attributes);
		return $data[0];
	}

	public function getById($drvId)
	{
		$criteria = new CDbCriteria();
		//$criteria->compare('drv_id', $drvId);
		$criteria->addCondition("drv_active > 0 AND drv_id IN (SELECT d3.drv_id FROM drivers d1
          INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
          INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
          WHERE d1.drv_id=$drvId) AND drv_id = drv_ref_code");
		return Drivers::model()->resetScope()->find($criteria);
	}

	public function getNamefromMap($drvId)
	{
		$sql	 = "SELECT drv_name1 from drivers drv left join driver_map drm ON drv.drv_id = drm.drv_id where drm.drv_id1 = $drvId";
		$name	 = DBUtil::command($sql)->queryScalar();
		echo $name;
		//   return $name;
	}

	public function updateDriverAttachmentPath($drvId, $field, $value)
	{
		// $table = ($tableName == '') ? $this->tableName : $tableName;
		$model			 = Drivers::model()->findByPk($drvId);
		$model->$field	 = $value;
		$success		 = $model->save();
		// $sql = "UPDATE drivers SET $field='$value' WHERE `drv_id`=$drvId";
		// echo $sql;
		//return true;
		return $success;
	}

	public function returnDriverFieldValue($drvId, $field, $tableName = '')
	{
		$table	 = ($tableName == '') ? $this->tableName : $tableName;
		$data	 = [];

		$sql	 = "SELECT drv.$field as $field,din.$field as " . $field . "1
                from $table drv JOIN drivers_info din ON drv.drv_id = din.drv_driver_id
                WHERE drv.drv_id = $drvId";
		echo $sql;
		$data	 = DBUtil::queryRow($sql);
		return $data;
	}

	public function getRelatedDrivers($drvId, $arr, $active)
	{
		$where	 = '';
		$where0	 = '';
		if ($arr['drv_name'])
		{
			$drvname = $arr['drv_name'];
			$where	 .= " AND drv.drv_name LIKE '%$drvname%'";
			$where0	 .= " AND t.drv_name LIKE '%$drvname%'";
		}
		if ($arr['drv_phone'])
		{
			$drvph	 = $arr['drv_phone'];
			$where0	 .= " AND cntp.phn_phone_no LIKE '%$drvph%'";
		}

		if ($arr['drv_email'])
		{
			$drveml	 = $arr['drv_email'];
			$where0	 .= " AND cnte.eml_email_address LIKE '%$drveml%'";
		}

		$sql = "SELECT t.drv_id,t.drv_name,t.drv_approved,t.drv_active,cntp.phn_phone_no as drv_phone,cnte.eml_email_address as drv_email, t.drv_contact_id,
                GROUP_CONCAT(vnd.vnd_name SEPARATOR ', ') as vendor_names,
                rank from
                (SELECT drv0.drv_id,drv0.drv_contact_id,drv0.drv_name,drv0.drv_approved, drv0.drv_active
                , 1 as rank
                from drivers drv0 where drv_id IN
                (SELECT distinct drv.drv_id FROM drivers d1
                INNER JOIN drivers drv
                ON (drv.drv_name=d1.drv_name)
                WHERE d1.drv_id = $drvId $where
                AND d1.drv_active <> 0 AND trim(d1.drv_name) <> '')
                UNION
                SELECT drv1.drv_id,drv1.drv_contact_id,drv1.drv_name,drv1.drv_approved ,drv1.drv_active
                , 2 as rank
                from drivers drv1 where drv_id NOT IN
                (SELECT distinct drv.drv_id FROM drivers d
                INNER JOIN drivers drv
                ON (drv.drv_name=d.drv_name)
                WHERE d.drv_id = $drvId OR d.drv_id=0 )) t
                LEFT JOIN contact_phone as cntp ON t.drv_contact_id=cntp.phn_contact_id 
				LEFT JOIN contact_email as cnte ON t.drv_contact_id = cnte.eml_contact_id
				LEFT JOIN vendor_driver vdrv ON t.drv_id = vdrv.vdrv_drv_id
                LEFT JOIN vendors vnd ON vnd.vnd_id = vdrv.vdrv_vnd_id
                WHERE t.drv_id <> $drvId AND t.drv_active >$active  AND trim(t.drv_name) <> ''  AND cnte.eml_is_primary = 1 AND cntp.phn_is_primary = 1 and cntp.phn_active = 1  and cnte.eml_active = 1 $where0
                GROUP BY t.drv_id";

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) a")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['drv_id', 'drv_name', 'drv_phone', 'drv_email'],
				'defaultOrder'	 => 'rank ASC,drv_name'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getFullDetailsbyId($id)
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = ["t.*,cty.cty_name as drv_cty_name,stt.stt_name as drv_stt_name"];
		$criteria->join		 .= " LEFT JOIN cities cty ON drv_city = cty_id";
		$criteria->join		 .= " LEFT JOIN states stt ON drv_state = stt_id";
		$criteria->group	 = "drv_id";
		$criteria->compare('drv_id', $id);
		return $this->resetScope()->find($criteria);
	}

	public function getExpriedPapersList()
	{

		$sql = "SELECT document.doc_id, d.drv_id, vendor_ids
				FROM `drivers`
				INNER JOIN drivers as d on d.drv_id = drivers.drv_ref_code and d.drv_active =1
				INNER JOIN contact_profile as cp on cp.cr_is_driver = d.drv_id and cp.cr_status = 1
				INNER JOIN contact on contact.ctt_id = cp.cr_contact_id and contact.ctt_active = 1 and contact.ctt_id = contact.ctt_ref_code
				INNER JOIN document ON document.doc_id = contact.ctt_license_doc_id and document.doc_active = 1 AND document.doc_status = 1 and contact.ctt_active=1
				LEFT JOIN
				(
				SELECT
				GROUP_CONCAT(DISTINCT vendor_driver.vdrv_vnd_id SEPARATOR ',') AS vendor_ids,
				vendor_driver.vdrv_drv_id
				FROM `vendor_driver` INNER JOIN `vendors` ON vendors.vnd_id = vendor_driver.vdrv_vnd_id AND vendors.vnd_active = 1 and vendor_driver.vdrv_active=1
				GROUP BY vendor_driver.vdrv_drv_id
				) drv ON drv.vdrv_drv_id = d.drv_id
				WHERE 1 AND (CURDATE() > (contact.ctt_license_exp_date) AND (contact.ctt_license_exp_date) <> '1970-01-01'
				AND document.doc_type = 5) AND d.drv_approved IN (0, 1)
				ORDER BY contact.ctt_license_exp_date DESC";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function getLastTripNextTripByDrvId($drvId)
	{
		$sql = "SELECT last_trip_vendor_id, last_trip_ven_name, last_trip_ven_phone, last_trip,
                next_trip_vendor_id, next_trip_ven_name, next_trip_ven_phone, next_trip
                FROM (
                    SELECT booking_cab.bcb_vendor_id as last_trip_vendor_id, vendors.vnd_name as last_trip_ven_name,
                    vendors.vnd_phone as last_trip_ven_phone, booking_cab.bcb_id as last_trip
                    FROM `booking_cab`
                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id
                    INNER JOIN `vendors` ON vendors.vnd_id=booking_cab.bcb_vendor_id
                    WHERE booking.bkg_pickup_date IN (
                        SELECT MAX(booking.bkg_pickup_date)
                        FROM `booking_cab`
                        INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id
                        AND booking.bkg_active=1
                        AND booking_cab.bcb_active=1
                        WHERE booking.bkg_pickup_date < NOW() AND booking_cab.bcb_driver_id IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
						WHERE d1.drv_id=$drvId)
                        GROUP BY booking_cab.bcb_driver_id
                    )  AND booking_cab.bcb_driver_id IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
						WHERE d1.drv_id=$drvId)
                ) a,
                (
                    SELECT booking_cab.bcb_vendor_id as next_trip_vendor_id, vendors.vnd_name as next_trip_ven_name,
                    vendors.vnd_phone as next_trip_ven_phone,  booking_cab.bcb_id as next_trip
                    FROM `booking_cab`
                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id
                    INNER JOIN `vendors` ON vendors.vnd_id=booking_cab.bcb_vendor_id
                    WHERE booking.bkg_pickup_date IN
                    (
                        SELECT MIN(booking.bkg_pickup_date)
                        FROM `booking_cab`
                        INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id
                        AND booking.bkg_active=1
                        AND booking_cab.bcb_active=1
                        WHERE booking.bkg_pickup_date > NOW() AND booking_cab.bcb_driver_id IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
						WHERE d1.drv_id=$drvId)
                        GROUP BY booking_cab.bcb_driver_id
                    ) AND booking_cab.bcb_driver_id IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
						WHERE d1.drv_id=$drvId)
                )b";
		return DBUtil::queryRow($sql);
	}

	public static function getDetailsById($id = 0)
	{

		if ($id > 0)
		{
			$qry = " AND vendor_driver.vdrv_drv_id =$id";
		}
		$sql = "SELECT drivers.drv_name,
				drivers.drv_trip_type,drivers.drv_code,
				drivers.drv_contact_id, drivers.drv_zip,
				drivers.drv_dob,
				contact_profile.cr_contact_id, 
                contact_phone.phn_phone_no AS drv_phone, 
				ctp.phn_phone_no AS drv_alt_phone,
				contact_email.eml_email_address AS drv_email,
				contact.ctt_address AS drv_address,
				contact.ctt_bank_name,
				contact.ctt_bank_branch,
				contact.ctt_bank_account_no,
				contact.ctt_bank_ifsc,
				contact.ctt_beneficiary_name,
				contact.ctt_beneficiary_id,
				contact.ctt_account_type,
				contact.ctt_license_issue_date,
				contact.ctt_license_exp_date,
				contact.ctt_dl_issue_authority,
				contact.ctt_vaccine_status,
				contact.ctt_profile_path,
                drivers.drv_id,
				drivers.drv_created,
                drivers.drv_approved,
                drivers.drv_approved_by,
                rtg_customer_driver,
                rtg_driver_ontime,
                rtg_driver_softspokon,
                rtg_driver_respectfully,
                rtg_driver_helpful,
                rtg_driver_safely,
                last_trip_pickup_date,
                last_pickup_date,
                countRating,
                CONCAT(admins.adm_fname,' ',admins.adm_lname) as approve_by_name,
                (CASE drivers.drv_approved
                        WHEN 0 THEN 'Not Verified'
                 	WHEN 1 THEN 'Approved'
                 	WHEN 2 THEN 'Pending Approval'
                 	WHEN 3 THEN 'Rejected'
					WHEN 4 THEN 'Ready For Approval'
                END) as approve_status, 
                cities.cty_name as city_name,
				ctt_city,
				vnd_name,
				vnd_code
                FROM `drivers` 
				LEFT JOIN contact_profile ON contact_profile.cr_is_driver=drivers.drv_id AND cr_status=1 
				LEFT JOIN contact ON contact.ctt_id=contact_profile.cr_contact_id 
				LEFT JOIN contact_phone ON contact_phone.phn_contact_id=contact.ctt_id AND phn_is_primary=1 
				LEFT JOIN contact_phone ctp ON ctp.phn_contact_id=contact.ctt_id AND ctp.phn_is_primary!=1 
				LEFT JOIN contact_email ON contact_email.eml_contact_id=contact.ctt_id AND contact_email.eml_is_primary=1
                LEFT JOIN (
                    SELECT ratings.rtg_customer_overall,
                        ratings.rtg_customer_driver,
                        COUNT(1)  as countRating,
                        MAX(booking.bkg_pickup_date) as last_trip_pickup_date,
                        ratings.rtg_driver_ontime,
                        ratings.rtg_driver_softspokon,
                        ratings.rtg_driver_respectfully,
                        ratings.rtg_driver_helpful,
                        ratings.rtg_driver_safely,
                        ratings.rtg_driver_cmt,
                        booking_cab.bcb_driver_id
                        FROM `ratings`
                        INNER JOIN `booking` ON booking.bkg_id=ratings.rtg_booking_id AND booking.bkg_active=1 AND booking.bkg_status IN (6,7)
                        INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
                        WHERE booking.bkg_create_date > '2015-10-01'
                        GROUP BY booking_cab.bcb_driver_id
                ) drv ON drv.bcb_driver_id=drivers.drv_id
                LEFT JOIN (
                    SELECT MAX(booking.bkg_pickup_date) as last_pickup_date,
                    booking_cab.bcb_driver_id
                    FROM `booking`
                    INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1
                    WHERE booking.bkg_create_date > '2015-10-01'
                    GROUP BY booking_cab.bcb_driver_id
                )drv2 ON drv2.bcb_driver_id=drivers.drv_id
                LEFT JOIN `admins` ON admins.adm_id=drivers.drv_approved_by
                LEFT JOIN `cities` ON cities.cty_id=contact.ctt_city 
				
				LEFT JOIN (
                    SELECT GROUP_CONCAT(vendors.vnd_name SEPARATOR ' ,') as vnd_name,GROUP_CONCAT(vendors.vnd_code SEPARATOR ' ,') as vnd_code,
                    vendor_driver.vdrv_drv_id
                    FROM `vendors`
                    INNER JOIN `vendor_driver` ON vendor_driver.vdrv_vnd_id=vendors.vnd_id AND vendor_driver.vdrv_active=1 $qry
                    GROUP BY vendor_driver.vdrv_drv_id
                )ven ON ven.vdrv_drv_id=drivers.drv_id

				WHERE 1";
		$sql .= " AND drivers.drv_id IN (
				SELECT d3.drv_id FROM drivers d1
				  INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
				  INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				  WHERE d1.drv_id=$id) AND drivers.drv_id = drivers.drv_ref_code";
		if ($id == 0)
		{
			return DBUtil::queryAll($sql);
		}
		else
		{
			return DBUtil::queryRow($sql);
		}
	}

	public function getPastTripList($id)
	{
		$Val = '"';
		$sql = "        SELECT
						booking.bkg_id,
						booking.bkg_booking_id,
						(CASE booking.bkg_booking_type 
						WHEN 1 THEN 'One Way' 
						WHEN 2 THEN 'Round/Multi Trip'
						WHEN 3 THEN 'Round/Multi Trip' 
						WHEN 4 THEN 'Airport Transfer'
						WHEN 5 THEN 'Package' 
						WHEN 6 THEN 'Flexxi'
						WHEN 7 THEN 'Shuttle' 
						WHEN 8 THEN 'Custome Trip'
						WHEN 9 THEN 'Day Rental(4hr-40km)' 
						WHEN 10 THEN 'Day Rental(8hr-80km)'
						WHEN 11 THEN 'Day Rental(12hr-120km)' 
						WHEN 12 THEN 'Airport Packages' 
						WHEN 15 THEN 'Local Transfer' 
						END) as booking_type,
						IFNULL(ratings.rtg_customer_driver, 'N/A') as rtg_customer_driver,
						IF(ratings.rtg_driver_cmt = '','N/A',ratings.rtg_driver_cmt) as rtg_driver_cmt,
                booking.bkg_pickup_date,
						REPLACE(JSON_EXTRACT(`bkg_route_city_names`, '$[0]'), '$Val', '')  AS from_city,
						REPLACE(JSON_EXTRACT(`bkg_route_city_names`, CONCAT('$[', JSON_LENGTH(`bkg_route_city_names`) - 1, ']')),'$Val','') AS to_city
                FROM  `booking`
						INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1 and booking_cab.bcb_denied_reason_id=0
						JOIN `drivers` ON drivers.drv_id=booking_cab.bcb_driver_id
                LEFT JOIN `ratings` ON ratings.rtg_booking_id=booking.bkg_id
						WHERE 1 AND (booking.bkg_create_date > '2015-10-01 00:00:00')
                AND drivers.drv_id IN (
					SELECT d3.drv_id FROM drivers d1
					  INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
					  INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
					  WHERE d1.drv_id=$id
				) AND drivers.drv_id = drivers.drv_ref_code AND bkg_status IN (5,6,7) 
                ORDER BY booking.bkg_pickup_date DESC";
		return DBUtil::queryAll($sql);
	}

	public function deactivatebyId($id)
	{
		$sql = "UPDATE drivers SET drv_active=0 WHERE `drv_id` IN (
				SELECT d3.drv_id FROM drivers d1
				  INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
				  INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				  WHERE d1.drv_id=$id) AND drivers.drv_id = drivers.drv_ref_code";
		return DBUtil::command($sql)->execute();
	}

	public function replaceDriverDetailsFromBooking($olddrvid, $newdrvid)
	{
		$sql = "UPDATE booking_cab SET bcb_driver_id=$newdrvid WHERE bcb_driver_id IN (
				SELECT d3.drv_id FROM drivers d1
				  INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
				  INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				  WHERE d1.drv_id=$olddrvid)";
		return DBUtil::command($sql)->execute();
	}

	public function replaceDriverDetailsFromBookingLog($olddrvid, $newdrvid)
	{
		$sql = "UPDATE booking_cab SET bcb_driver_id=$newdrvid WHERE bcb_driver_id IN (
				SELECT d3.drv_id FROM drivers d1
				  INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
				  INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				  WHERE d1.drv_id=$olddrvid)";

		return DBUtil::command($sql)->execute();
	}

	public function getLowRatingList()
	{
		$sql = "SELECT DISTINCT drv_ref_code
				FROM  drivers 
				INNER JOIN `booking_cab` ON booking_cab.bcb_driver_id = drv_id AND booking_cab.bcb_active = 1 and drv_id = drv_ref_code
				WHERE  drv_overall_rating < 2.5 AND drv_is_freeze = 0";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function getList($qry = [], $command = false)
	{
		$dlMismatch			 = "";
		$licenceCondition	 = "";
		if ($qry['searchLicense'] != '')
		{
			$licenseNo			 = $qry['searchLicense'];
			$licenceCondition	 = " AND contact.ctt_license_no LIKE '%$licenseNo%'";
		}
		$select = "SELECT 
					d1.drv_id,
					d1.drv_name,
					d1.drv_contact_id,
					d1.drv_code,
					contact_profile.cr_contact_id,
					contact.ctt_user_type,
					contact.ctt_first_name,
					contact.ctt_last_name,
                    contact.ctt_name,
					contact.ctt_business_name,
					contact.ctt_city,
					d1.drv_approved,
					d1.drv_mark_driver_count,
					d1.drv_doj,
					d1.drv_is_freeze,
					d1.drv_created,
					contact_email.eml_email_address    AS drv_email,
					GROUP_CONCAT(DISTINCT CONCAT(eml_email_address, '|', eml_is_primary, '|', eml_is_verified)) eml_email_address, 
					contact_phone.phn_phone_no    AS drv_phone,
					contact.ctt_license_issue_date     AS drv_issue_date,
					contact.ctt_license_exp_date       AS drv_lic_exp_date,
					contact.ctt_dl_issue_authority     AS drv_issue_auth,
                    contact.ctt_is_name_dl_matched     AS drv_is_name_dl_matched,
                    contact.ctt_is_name_pan_matched     AS drv_is_name_pan_matched,
					driver_stats.drs_total_trips       AS total_trips,
					driver_stats.drs_drv_overall_rating,
					driver_stats.drs_last_trip_date,
					driver_stats.drs_doc_score   AS R4Ascore,
					driver_stats.drs_last_logged_in,
					contact.ctt_license_doc_id         AS drv_licence_path ";

		$sql = " FROM drivers d1 
					INNER JOIN contact_profile ON contact_profile.cr_is_driver = d1.drv_id AND cr_status=1 AND d1.drv_id = d1.drv_ref_code 
					INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1 {$licenceCondition} 
					LEFT JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id  AND contact_phone.phn_active = 1 
					LEFT JOIN contact_email  ON contact_email.eml_contact_id = contact.ctt_id  AND contact_email.eml_active = 1
					LEFT JOIN driver_stats ON driver_stats.drs_drv_id = d1.drv_id ";

		$sqlCount = " FROM drivers d1 
						INNER JOIN contact_profile ON contact_profile.cr_is_driver = d1.drv_id AND cr_status=1 AND d1.drv_id = d1.drv_ref_code 
						INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1 {$licenceCondition} ";

		$sqlCondition = " WHERE 1 AND d1.drv_active = 1 ";
		if ($this->drv_name != '')
		{
			$sqlCondition .= " AND (d1.drv_name LIKE '%$this->drv_name%' OR d1.drv_code='$this->drv_name' OR contact.ctt_first_name LIKE '%$this->drv_name%' OR contact.ctt_last_name LIKE '%$this->drv_name%' OR contact.ctt_business_name LIKE '%$this->drv_name%')";
			#$sql			 .= " AND (d1.drv_name LIKE '%$this->drv_name%' OR d1.drv_code='$this->drv_name' OR contact.ctt_first_name LIKE '%$this->drv_name%' OR contact.ctt_last_name LIKE '%$this->drv_name%' OR contact.ctt_business_name LIKE '%$this->drv_name%')";
		}
		if ($this->drv_code != '')
		{
			$sqlCondition .= " AND ( d1.drv_code='$this->drv_code')";
			#$sql			 .= " AND ( d1.drv_code='$this->drv_code')";
		}
		if ($this->drv_email != '')
		{
			$sqlCount		 .= " LEFT JOIN contact_email  ON contact_email.eml_contact_id = contact.ctt_id  AND contact_email.eml_active = 1 ";
			$sqlCondition	 .= " AND contact_email.eml_email_address LIKE '%$this->drv_email%'";
			#$sql			 .= " AND contact_email.eml_email_address LIKE '%$this->drv_email%'";
		}
		if ($this->drv_phone != '')
		{
			$sqlCount		 .= " LEFT JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id  AND contact_phone.phn_active = 1 ";
			$sqlCondition	 .= " AND (contact_phone.phn_phone_no LIKE '%$this->drv_phone%')";
			#$sql			 .= " AND (contact_phone.phn_phone_no LIKE '%$this->drv_phone%')";
		}
		if ($this->vndlist != 1)
		{
			$sqlCondition .= " AND d1.drv_active=1";
			#$sql			 .= " AND d1.drv_active=1";
		}
		if ($this->drv_approved != '' && $this->drv_approved > 0)
		{
			$sqlCondition .= " AND d1.drv_approved=$this->drv_approved";
			#$sql			 .= " AND d1.drv_approved=$this->drv_approved";
		}
		if ($qry['searchmarkdriver'] != '')
		{
			$sqlCondition .= "  AND d1.drv_mark_driver_count > 0";
			#$sql			 .= "  AND d1.drv_mark_driver_count > 0";
		}
		if ($qry['searchdlmismatch'] != '')
		{
			$dlMismatchedVal = $qry['searchdlmismatch'];
			$sqlCondition	 .= "  AND contact.ctt_is_name_dl_matched  = $dlMismatchedVal";
			#$sql			 .= "  AND contact.ctt_is_name_dl_matched  = $dlMismatchedVal";
		}
		if ($qry['searchpanmismatch'] != '')
		{
			$panMismatchedVal	 = $qry['searchpanmismatch'];
			$sqlCondition		 .= "  AND contact.ctt_is_name_pan_matched  = $panMismatchedVal";
			#$sql				 .= "  AND contact.ctt_is_name_pan_matched  = $panMismatchedVal";
		}

		if ($this->drv_vendor_id != '')
		{
			$sqlCondition .= " AND d1.drv_id IN (SELECT vdrv_drv_id FROM vendor_driver WHERE vdrv_vnd_id={$this->drv_vendor_id} AND vdrv_active=1)";
			#$sql			 .= " AND d1.drv_id IN (SELECT vdrv_drv_id FROM vendor_driver WHERE vdrv_vnd_id={$this->drv_vendor_id} AND vdrv_active=1)";
		}
		if (isset($this->drv_trip_type) && $this->drv_trip_type != '')
		{
			$sqlCondition .= " AND d1.drv_trip_type LIKE '%$this->drv_trip_type%'";
			#$sql			 .= " AND d1.drv_trip_type LIKE '%$this->drv_trip_type%'";
		}
		if ($this->drv_source != '')
		{
			switch ($this->drv_source)
			{
				case 222:
					/* $sql .= " AND (d1.drv_name IS NOT NULL AND d1.drv_name <> '') AND
					  (contact_phone.phn_phone_no IS NOT NULL AND contact_phone.phn_phone_no <> '') AND
					  (contact.ctt_license_doc_id IS NOT NULL AND contact.ctt_license_doc_id <> '') AND
					  d1.drv_approved IN (2)"; */

					$sqlCondition .= " AND (d1.drv_name IS NOT NULL AND d1.drv_name <> '') AND
								(contact_phone.phn_phone_no IS NOT NULL AND contact_phone.phn_phone_no <> '') AND
								(contact.ctt_license_doc_id IS NOT NULL AND contact.ctt_license_doc_id <> '') AND
							   d1.drv_approved IN (2)";
					break;
			}
		}
		$groupby	 = " GROUP BY d1.drv_id ";
		$query		 = $select . $sql . $sqlCondition . $groupby;
		$queryCount	 = "SELECT COUNT(*) FROM (SELECT drv_id " . $sqlCount . $sqlCondition . $groupby . ") a";
		if ($command == false)
		{
			$count			 = DBUtil::command($queryCount)->queryScalar();
			$dataprovider	 = new CSqlDataProvider($query, [
				'totalItemCount' => $count,
				'sort'			 =>
				['attributes'	 =>
					['drv_name', 'drv_email', 'drv_doj', 'drv_created', 'drv_is_name_dl_matched', 'drv_mark_driver_count', 'drs_total_trips', 'R4Ascore', 'total_trips', 'drs_last_logged_in'],
					'defaultOrder'	 => 'R4Ascore DESC'],
				'pagination'	 => ['pageSize' => 30],
			]);
			return $dataprovider;
		}
		else
		{
			return DButil::queryAll($sql);
		}
	}

	public function getModificationMSG($diff, $user = false)
	{
		$msg = '';
		if (count($diff) > 0)
		{
			if ($diff['drv_contact_id'])
			{
				$msg .= ' Driver Contact ID: ' . $diff['drv_contact_id'] . ',';
			}
			if ($diff ['drv_name'])
			{
				$msg .= ' Driver name: ' . $diff['drv_name'] . ',';
			}

			if ($diff ['drv_phone'])
			{
				$msg .= ' Driver Phone: ' . $diff['drv_phone'] . ',';
			}
			if ($diff ['drv_lic_number'])
			{
				$msg .= ' Licence Number: ' . $diff['drv_lic_number'] . ',';
			}
			if ($diff['drv_lic_exp_date'])
			{
				$msg .= ' Licence Exp Date: ' . $diff['drv_lic_exp_date'] . ',';
			}
			if ($diff['drv_voter_id'])
			{
				$msg .= ' VoterId: ' . $diff['drv_voter_id'] . ',';
			}
			if ($diff['drv_aadhaar_no'])
			{
				$msg .= ' Aadhaar No: ' . $diff['drv_aadhaar_no'] . ',';
			}
			if ($diff ['drv_pan_no'])
			{
				$msg .= ' Pan No: ' . $diff['drv_pan_no'] . ',';
			}
			if ($diff['drv_issue_auth'])
			{
				$msg .= 'Driver License is issued by : ' . $diff['drv_issue_auth'] . ',';
			}
			if ($diff['drv_address'])
			{
				$msg .= ' Address: ' . $diff['drv_address'] . ',';
			}
			if ($diff['drv_email'])
			{
				$msg .= ' Email: ' . $diff['drv_email'] . ',';
			}
			if ($diff['drv_state'])
			{
				$smodel	 = States::model()->findByPk($diff['drv_state']);
				$msg	 .= ' State: ' . $smodel->stt_name . ',';
			}
			if ($diff['drv_city'])
			{
				$cmodel	 = Cities::model()->findByPk($diff['drv_city']);
				$msg	 .= ' City: ' . $cmodel->cty_name . ',';
			}
			if ($diff['drv_zip'])
			{
				$msg .= ' Zip: ' . $diff['drv_zip'] . ',';
			}
			if ($diff['drv_approved'] != '')
			{
				switch ($diff['drv_approved'])
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
				//$approveStatus = ($diff['drv_approved']==1) ? 'Yes':'No';
				$msg .= ' Status: ' . $approveStatus . ',';
			}


			if ($diff['photoFile'] != '')
			{
				$msg .= ' Driver Selfie: ' . $diff['photoFile'] . ',';
			}
			if ($diff['voterCardFile'] != '')
			{
				$msg .= ' Voter ID: ' . $diff['voterCardFile'] . ',';
			}
			if ($diff['panCardFile'] != '')
			{
				$msg .= ' PAN: ' . $diff['panCardFile'] . ',';
			}
			if ($diff['aadhaarCardFile'] != '')
			{
				$msg .= ' Aadhaar: ' . $diff['aadhaarCardFile'] . ',';
			}
			if ($diff['licenseFile'] != '')
			{
				$msg .= ' Driver License: ' . $diff['licenseFile'] . ',';
			}
			if ($diff['policeFile'] != '')
			{
				$msg .= ' Police verification: ' . $diff['policeFile'] . ',';
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public function saveDocument($driverId, $path, UserInfo $userInfo = null, $doc_type = null)
	{
		$success = false;
		if ($path != '' && $driverId != '')
		{
			$success	 = true;
			$event_id	 = DriversLog::DRIVER_FILE_UPLOAD;
			$logArray	 = DriversLog::model()->getLogByDocumentType($doc_type);
			$logDesc	 = DriversLog::model()->getEventByEventId($logArray['upload']);
			DriversLog::model()->createLog($driverId, $logDesc, $userInfo, $event_id, false, false);
		}
		return $success;
	}

	public function carDriverApproveList($from_date, $to_date)
	{

		$sql = "SELECT CONCAT(admins.adm_fname,' ',admins.adm_lname) as csr,
                IF(totalCarApprove>0,totalCarApprove,0) as totalCarApprove,
                IF(toatalDrvApprove>0,toatalDrvApprove,0) as toatalDrvApprove
                FROM `admins`
                LEFT JOIN
                (
                   SELECT COUNT(1) as totalCarApprove,vhc_approved_by FROM
                   (
                        SELECT vehicles_log.clg_vhc_id,vehicles.vhc_approved_by,vehicles.vhc_id
                        FROM `vehicles_log`
                        INNER JOIN `vehicles` ON vehicles.vhc_id=vehicles_log.clg_vhc_id AND vehicles.vhc_approved=1
                        WHERE vehicles_log.clg_event_id IN (2,10,18,20)
                        AND vehicles_log.clg_user_type=4
                        AND (vehicles_log.clg_created BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59') and vehicles.vhc_approved_by >0
                        GROUP BY vehicles_log.clg_vhc_id
                    ) a GROUP BY vhc_approved_by
              )vlog ON vlog.vhc_approved_by=admins.adm_id
              LEFT JOIN
              (
                    SELECT COUNT(1) as toatalDrvApprove,drv_approved_by FROM
                    (
                        SELECT drivers_log.dlg_drv_id,drivers.drv_approved_by,drivers.drv_id
                        FROM `drivers_log`
                        INNER JOIN `drivers` ON drivers.drv_id=drivers_log.dlg_drv_id AND drivers.drv_approved=1
                        WHERE drivers_log.dlg_event_id IN (2,14)
                        AND drivers_log.dlg_user_type=4
                        AND (drivers_log.dlg_created BETWEEN  '$from_date 00:00:00' AND '$to_date 23:59:59') and drivers.drv_approved_by>0
                        GROUP BY drivers_log.dlg_drv_id
                    )b GROUP BY drv_approved_by
              )dlog ON dlog.drv_approved_by=admins.adm_id
              where (totalCarApprove>0 OR toatalDrvApprove>0)";
		return DBUtil::queryAll($sql);
	}

	public function getCountByPhoneNo($phoneNo)
	{
		$qry		 = "SELECT d.drv_id,count(phn.phn_phone_no) as count
						FROM `drivers`
						inner join drivers as d on d.drv_id = drivers.drv_ref_code and d.drv_active=1
						inner join contact_profile as cp on cp.cr_is_driver = d.drv_id and cp.cr_status =1
						inner join contact as c on c.ctt_id = cp.cr_contact_id and c.ctt_active =1 and c.ctt_id = c.ctt_ref_code
						LEFT JOIN contact_phone phn ON phn.phn_contact_id=c.ctt_id AND phn.phn_is_primary =1
						AND phn.phn_active=1 WHERE d.drv_active = 1
						AND phn.phn_phone_no ='$phoneNo' Group By phn_phone_no";
		$recordset	 = DBUtil::queryRow($qry);
		return $recordset;
	}

	/*
	 * @deprecated since 27/12/2021
	 * This function is not used anywhere in the system
	 */

	public function getDriverIdByTripId($tripId, $phNo, $otp = '')
	{
		if ($otp != '')
		{
			$otp = " AND drivers.drv_verification_code = '" . $otp . "'";
		}
		$qry		 = "SELECT drivers.drv_id, booking_cab.bcb_id, phn.phn_phon e_no as drv_phone, drivers.drv_verification_code, drivers.drv_name, eml.eml_email_address as drv_email, drivers.drv_country_code  FROM drivers
                INNER JOIN booking_cab ON booking_cab.bcb_driver_id = drivers.drv_id
                INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id
                INNER JOIN contact ctt ON ctt.ctt_id=drivers.drv_contact_id
				LEFT JOIN contact_phone phn ON phn.phn_contact_id=ctt.ctt_id AND phn.phn_is_primary=1 AND phn.phn_active=1
                LEFT JOIN contact_email eml ON eml.eml_contact_id=ctt.ctt_id AND eml.eml_is_primary=1 AND eml.eml_active=1
                WHERE booking_cab.bcb_id = $tripId AND phn.phn_phone_no = $phNo AND booking.bkg_status <> 6 $otp";
		$recordset	 = DBUtil::queryRow($qry);
		return $recordset;
	}

	public function isFreeze($drvId, $userInfo, $desc)
	{
		$success = false;
		try
		{
			$model = Drivers::model()->resetScope()->findByPk($drvId);
			if ($model->drv_is_freeze == 0)
			{
				$model->drv_is_freeze = 1;
				if ($model->validate() && $model->save())
				{
					DriversLog::model()->createLog($model->drv_id, $desc, $userInfo, DriversLog::DRIVER_FREEZE, false, false);
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

	public function approve($drv_id, $userInfo)
	{
		$transaction = DBUtil::beginTransaction();
		$success	 = false;
		try
		{
			$model				 = Drivers::model()->findByPk($drv_id);
			$model->drv_approved = 1;
			if ($model->save())
			{
				$desc		 = "Driver Auto Approved";
				$event_id	 = DriversLog::DRIVER_APPROVED;
				DriversLog::model()->createLog($model->drv_id, $desc, $userInfo, $event_id, false, false);
				$success	 = true;
				DBUtil::commitTransaction($transaction);
				BookingCab::model()->updateVendorPayment(2, $drv_id);
			}
			else
			{
				$errors = "data not yet saved.\n\t\t" . json_encode($model->getErrors());
				Logger::create($errors, CLogger::LEVEL_WARNING);
				throw new Exception($errors);
			}
		}
		catch (Exception $e)
		{
			$this->addError("drv_id", $e->getMessage());
			Logger::create("Not Approve.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	public function disapprove($drv_id, $userInfo)
	{
		$transaction = DBUtil::beginTransaction();
		$success	 = false;
		try
		{
			$model				 = Drivers::model()->findByPk($drv_id);
			$model->drv_approved = 3;
			if ($model->save())
			{
				$desc		 = "Driver modified." . " Driver disapproved(rejected docs).";
				$event_id	 = DriversLog::DRIVER_MODIFIED;
				DriversLog::model()->createLog($model->drv_id, $desc, $userInfo, $event_id, false, false);
				$success	 = true;
				DBUtil::commitTransaction($transaction);
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
			Logger::create("Not Disapprove.\n\t\t" . $ex->getMessage(), CLogger::LEVEL_ERROR);
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	public function getDriverApproveStatus()
	{
		$success = false;
		if ($this->drv_active == 1 && $this->drv_approved == 1 && $this->drv_is_freeze == 0)
		{
			$success = true;
		}
		return $success;
	}

	public function getDriverApproveStatusForUber()
	{
		$success = true;
		if ($this->drv_is_uber_approved == 0)
		{
			$success = false;
		}
		return $success;
	}

	public function isApproved()
	{
		$success = true;
		if ($this->drv_approved == 0)
		{
			$success = false;
		}
		return $success;
	}

	public function findByPhone($phone)
	{
		$model = Drivers::model()->find("drv_phone=:phone", ['phone' => $phone]);
		return $model;
	}

	public function checkSocialLinking($drv_id)
	{
		$result = false;
		if ($drv_id > 0)
		{
			$row	 = Drivers::getUserContact($drv_id);
			$userId	 = $row['userId'];
			if ($userId > 0)
			{
				$result = Users::model()->checkSocialLinking($userId);
			}
		}
		return $result;
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
		if ($params['drv_id'] > 0 && $params['bkg_booking_id'] != '')
		{
			$desc	 = "Driver is Freezed due of Low Rating (" . $params['rtg_customer_car'] . ") in booking id " . $params['bkg_booking_id'];
			$result	 = Drivers::model()->isFreeze($params['drv_id'], $userInfo, $desc);
			$success = $result['success'];
		}
		return $success;
	}

	public function forgotPassword($phone, $code, $newPassword, $arr, $status)
	{
		$user_model = Users::model()->findByPhoneorEmail($phone);
		if (count($user_model) > 1)
		{
			$driver_user_id	 = $user_model['user_id'];
			$email			 = $user_model['usr_email'];
			$phone			 = $user_model['usr_mobile'];

			if (preg_match('/^[0-9]{10}+$/', $phone))
			{

				$dri_vers	 = Drivers::model()->findByUserid($driver_user_id);
				$driver		 = $dri_vers->drv_id;
				$arr		 = ['driver' => $driver];
				if ($dri_vers)
				{
					if ($code != "")
					{
						if ($dri_vers->drv_verification_code == $code)
						{
							if ($newPassword != "")
							{
								$dri_vers->drv_verification_code = '';
								$dri_vers->update();

								$userData1				 = Users::model()->findByPk($driver_user_id);
								$userData1->usr_password = $newPassword;
								$userData1->update();
								$message				 = "Password Changed Successfully";
								$status					 = true;
							}
							else
							{
								$message = "Verified Successfully.";
								$status	 = true;
							}
						}
						else
						{
							$message = "Validation Code didn't match. Resent code and verify";
							$status	 = false;
						}
					}
					else
					{
						$code							 = ($dri_vers->drv_verification_code == "") ? rand(999, 9999) : $dri_vers->drv_verification_code;
						$dri_vers->drv_verification_code = $code;
						if ($dri_vers->update())
						{
							$smsWrapper		 = new smsWrapper();
							//$countrycode = $dri_vers->drv_country_code;
							$contactDetails	 = Contact::model()->getContactDetails($dri_vers->drv_contact_id);
							$sent			 = $smsWrapper->sendForgotPassCodeDriver($contactDetails['phn_phone_country_code'], $phone, $code);
							$message		 = "Validation code sent successfully.";
							$status			 = true;
						}
						else
						{
							$message = "Validation is failed..";
							$status	 = false;
						}
					}
				}
				else
				{
					$message = "Mobile number not Registered";
					$status	 = false;
				}
			}
			else
			{
				$message = "Invalid Mobile number.";
				$status	 = false;
			}
		}
		else
		{
			$message = "Validation Code didn't match. Resent code and verify";
			$status	 = false;
		}

		$re_sult[0]	 = ['success' => $status, 'message' => $message];
		$re_sult[1]	 = $arr;
		return $re_sult;
	}

	// check vendor existance through token and logged in according to that token
	public function authoriseDriver($token)
	{
		$appToken1 = AppTokens::model()->find('apt_token_id = :token and apt_status = 1', array('token' => $token));

		if (empty($appToken1))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	//social login of driver

	public function socialDriverlogin($provider, $processSyncdata, $deviceData1)
	{

		$userData		 = CJSON::decode($processSyncdata, true);
		$deviceData		 = CJSON::decode($deviceData1, true);
		$userAuthentic	 = 0;
		$passWord		 = '';
		$email			 = '';
		$userName		 = '';
		$userPhone		 = '';
		$deviceID		 = $deviceData['device_id'];
		$deviceVersion	 = $deviceData['os_version'];
		$apkVersion		 = $deviceData['apk_version'];
		$ipAddress		 = \Filter::getUserIP();
		$deviceInfo		 = $deviceData['device_info'];
		$appDeviceToken	 = $deviceData['apt_device_token'];

		if ($provider == 'Google' || $provider == 'Facebook')
		{
			$identifier	 = $userData['id'];
			//$email	     = $userData['email'];				
			$tablePrefix = Yii::app()->db->tablePrefix;
			$oauthtable	 = $tablePrefix . 'user_oauth';
			$sql		 = "select * from $oauthtable where  identifier='$identifier' AND provider = '$provider'";
			$val		 = DBUtil::queryRow($sql);
			if (count($val) > 1)
			{
				$userID	 = $val['user_id'];
				$sql	 = "SELECT count(drv_id) as cnt,drv_id  FROM  drivers WHERE  drv_approved < 3 AND drv_user_id=$userID";
				$cdb	 = DBUtil::queryRow($sql);
				if ($cdb['cnt'] == 1)
				{
					$driverID	 = $cdb['drv_id'];
					$sql		 = "SELECT usr_password,usr_name,usr_lname,usr_mobile,usr_email  FROM  users WHERE user_id=$userID  AND usr_active = 1";
					$usrd		 = DBUtil::queryRow($sql);
					if (count($usrd) > 0)
					{
						$passWord		 = $usrd['usr_password'];
						$userName		 = $usrd['usr_name'] . ' ' . $usrd['usr_lname'];
						$email			 = $usrd['usr_email'];
						$userAuthentic	 = 1;
					}
					else
					{
						$userAuthentic = 0;
					}
				}
				else
				{
					$userAuthentic = 0;
				}
			}
			else
			{
				$userAuthentic = 0;
			}


			/* 			
			 * Authenticating user
			 */
			if ($userAuthentic == 1)
			{
				$identity = new UserIdentity($email, $passWord);

				if ($identity->authenticate())
				{
					$userID			 = $identity->getId();
					$userModel		 = Drivers::model()->findByUserid($userID);
					$resultSet		 = Drivers::model()->findByUserid($userID);
					$drvSosStatus	 = DriverStats::model()->getDriverSosStatus($resultSet['drv_id']);
					if ($drvSosStatus < 1)
					{
						if (count($userModel) > 0)
						{
							$identity->setEntityID($userModel->drv_id);
							$driver_id = $identity->entityId;

							/* @var $webUser GWebUser */
							$webUser = Yii::app()->user;
							$webUser->login($identity);
							$webUser->setUserType(UserInfo::TYPE_DRIVER);

							//disabling multiple login
							$multiplelogin = AppTokens::model()->getAppMultiLoginStatus($driver_id);

							$aptModel	 = AppTokens::Add($userID, 5, $driver_id, $deviceData['nameValuePairs']);
							Logger::trace('driver login ' . json_encode($aptModel));
							$success	 = true;
							$userId		 = UserInfo::getUserId();
							$rating		 = DriverStats::fetchRating($driverID);
							$sessionId	 = $aptModel->apt_token_id;
							$userModel	 = Drivers::model()->findByUserid($userId);
							$driver_id	 = UserInfo::getEntityId();

							$userData	 = Users::model()->findByPk($userId);
							$multi		 = false;

							if (is_numeric($data['username']))
							{

								$countPhoneNo	 = Drivers::model()->getCountByPhoneNo($driver_id);
								$count			 = $countPhoneNo['count'];
								if ($count > 1)
								{
									$multi = true;
								}
								if ($data['tripId'] != '')
								{
									$multi = false;
								}
							}
							$phnNo		 = ContactPhone::model()->getContactPhoneById($userModel->drv_contact_id);
							$emlId		 = ContactEmail::model()->getContactEmailById($userModel->drv_contact_id);
							$userName	 = $userModel->drv_name;
							$drvCode	 = $userModel->drv_code;
							$userPhone	 = $phnNo;
							$userEmail	 = $emlId;
							$drvPrefLang = $userModel->drvContact->ctt_preferred_language;
							$msg		 = "Login Successful";
						}
						else
						{
							$success = false;
							$msg	 = "Sorry, unable to login.";
						}
					}
					else
					{
						$success = false;
						$msg	 = "Sorry, SOS is active for this driver.";
					}
				}
				else
				{
					$success = false;
					$msg	 = "Sorry, you are not a valid driver.";
				}
			}
			else
			{
				$success = false;
				$msg	 = "Sorry, your account is not linked with social account. Go to 'Register First'.";
			}
		}
		else
		{
			$success = false;
			$msg	 = "Sorry, unable to login.";
		}

		/////////////////////////////////////////////////

		return CJSON::encode(['success' => $success, 'userPhone' => $userPhone, 'message' => $msg, 'sessionId' => $sessionId, 'userId' => $userId, 'driverId' => $driver_id, 'userEmail' => $userEmail, 'driverPrefLang' => $drvPrefLang, 'userName' => $userName, 'drv_code' => $drvCode, 'multi' => $multi, 'rating' => $rating, 'multiplelogin' => $multiplelogin]);
	}

	public function sendAndVerifyOTP($driver_id, $otp)
	{
		$drvModel = Drivers::model()->findByPk($driver_id);
		if ($otp == $drvModel->drv_verification_code)
		{
			$drvModel->drv_verification_code = '';
			$drvModel->save();
			$success						 = true;
			$message						 = 'OTP matched successfully.';
		}
		else
		{
			$success = false;
			$message = 'OTP mismatched.';
		}

		$data = ['success' => $success, 'message' => $message, 'driver_id' => $driver_id];
		return $data;
	}

	public function findByDriverContactID($drv_contact_id)
	{
		$model = Drivers::model()->find('drv_contact_id=:drv_contact_id AND drv_active>0', ['drv_contact_id' => $drv_contact_id]);
		return $model;
	}

	public function getDriverContact($userID)
	{
		$sql	 = "SELECT cntp.phn_phone_no as phone_no 
				FROM drivers drv
				INNER JOIN contact_phone cntp ON drv.drv_contact_id = cntp.phn_contact_id 
				where drv_user_id = $userID";
		$result	 = DBUtil::queryRow($sql);
		return $result['phone_no'];
	}

	public function getJSONAllDriversbyQuery($query, $drv = '', $showInactive = '0', $vnd = 0)
	{
		$rows	 = $this->getAllDriversbyQuery($query, $drv, $showInactive, $vnd);
		$arrDrv	 = array();
		foreach ($rows as $row)
		{
			$arrDrv[] = $showInactive != '0' ? array("id" => $row['drv_id'], "text" => $row['drv_name'] . ' (' . $row['phn_phone_no'] . ')') : array("id" => $row['drv_id'], "text" => $row['drv_name'] . ' (' . $row['drv_code'] . ')');
		}
		$data = CJSON::encode($arrDrv);
		return $data;
	}

	public function getAllDriversbyQuery($query = '', $drv = '', $onlyActive, $vnd)
	{
//		$relVndIds	 = Vendors::getPrimaryId($vnd);
		$relVndIds	 = Vendors::getRelatedIds($vnd);
		$qry		 = '';
		if ($drv != '')
		{
			$qry1 = " AND 1 OR drivers.drv_id IN (SELECT d3.drv_id FROM drivers d1 
					INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
					INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id WHERE d1.drv_id=$drv) 
					AND drivers.drv_id = drivers.drv_ref_code";
		}
		if ($query == '')
		{
			$qry .= " AND drivers.drv_id IN (SELECT bcb_driver_id FROM (SELECT bcb_driver_id, COUNT(*) as cnt  
											FROM booking_cab  
										WHERE   bcb_active = 1 AND bcb_created > DATE_SUB(NOW(), INTERVAL 4 MONTH) 
									GROUP BY bcb_driver_id ORDER BY cnt DESC LIMIT 0, 30) a)";
		}
		else
		{
			$qry .= " AND drivers.drv_name LIKE '%{$query}%'";
		}

		if ($onlyActive == '0')
		{
			$qry .= " AND drivers.drv_active = 1";
			$sql = "SELECT distinct(drivers.drv_id),drivers.drv_name,drivers.drv_code
				FROM drivers 
				WHERE drivers.drv_name IS NOT NULL  $qry $qry1 ORDER BY drivers.drv_name LIMIT 0,30 ";
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else if ($onlyActive == '2')
		{
			$qry3		 = " inner JOIN vendor_driver vdrv ON vdrv.vdrv_drv_id = d2.drv_id  
						AND vdrv.vdrv_vnd_id IN ({$relVndIds})  AND vdrv_active = 1";
			$qry4		 = " AND (d2.drv_active = 1 AND d2.drv_is_freeze = 0) ";
			$qry2		 = " left JOIN contact_phone cntp ON d2.drv_contact_id = cntp.phn_contact_id 
							and  cntp.phn_is_primary=1 ";
			$qryBooking	 = "((d2.drv_name IS NOT NULL  AND d2.drv_name!='' AND d2.drv_contact_id > 0)			
								AND (d2.drv_name LIKE '%{$query}%' or cntp.phn_phone_no LIKE '%{$query}%'))";
			$sql		 = "SELECT distinct(d2.drv_id), d2.drv_code,  d2.drv_name,cntp.phn_phone_no
					FROM drivers d1 INNER JOIN drivers d2 ON d2.drv_id = d1.drv_ref_code $qry2 $qry3
					WHERE $qryBooking $qry4  ORDER BY d2.drv_name LIMIT 0,30 ";

			return DBUtil::query($sql, DBUtil::SDB());
		}
		else if ($onlyActive == '3')
		{
			$qryBooking = "((d2.drv_name IS NOT NULL  AND d2.drv_name!='' AND d2.drv_contact_id > 0))";

			$qry3	 = " inner JOIN vendor_driver vdrv ON vdrv.vdrv_drv_id = d2.drv_id 
				AND vdrv_active = 1 and vdrv_vnd_id IN ({$relVndIds})";
			$qry4	 = "  AND ( d2.drv_active = 1  AND d2.drv_is_freeze = 0)";
			$qry2	 = " LEFT JOIN contact_phone cntp ON d2.drv_contact_id = cntp.phn_contact_id 
				AND cntp.phn_is_primary=1";
			$sql	 = "SELECT distinct(d2.drv_id), d2.drv_code, d2.drv_name,cntp.phn_phone_no
				FROM drivers d1 
				INNER JOIN drivers d2 ON d2.drv_id = d1.drv_ref_code  $qry2 $qry3
				WHERE $qryBooking $qry4  ORDER BY d2.drv_name LIMIT 0,30 ";

			return DBUtil::query($sql, DBUtil::SDB());
		}
		else if ($onlyActive == '4')
		{
			$qryBooking	 = "((d2.drv_name IS NOT NULL  AND d2.drv_name!=''  AND d2.drv_contact_id > 0 ))";
			$qry3		 = " inner JOIN vendor_driver vdrv ON vdrv.vdrv_drv_id = d2.drv_id  AND vdrv_active = 1 
							and vdrv_vnd_id IN ({$relVndIds})";
			$qry4		 = "  AND ( d2.drv_active = 1  AND d2.drv_is_freeze = 0)";
			$qry2		 = " left JOIN contact_phone cntp ON d2.drv_contact_id = cntp.phn_contact_id 
							and  cntp.phn_is_primary=1";
			$sql		 = "SELECT distinct(d2.drv_id), d2.drv_code, d2.drv_name,cntp.phn_phone_no
				FROM drivers d1 INNER JOIN drivers d2 ON d2.drv_id = d1.drv_ref_code $qry2 $qry3
				WHERE $qryBooking $qry4  ORDER BY d2.drv_name";

			return DBUtil::query($sql, DBUtil::SDB());
		}
	}

	public function checkDuplicateContactByDriver($ctcid)
	{
		$sql = "SELECT COUNT(1) as cnt FROM `drivers`
				INNER JOIN contact_profile cr ON cr.cr_is_driver = drivers.drv_id AND cr.cr_status = 1 
				WHERE drv_approved > 0 AND cr.cr_contact_id='$ctcid'";
		$cnt = DBUtil::command($sql)->queryScalar();
		return $cnt;
	}

	public function checkVendorAndDriverContactExist($vndid, $vndctcid, $drvctcid)
	{
		//$sql			 = "SELECT COUNT(1) as cnt FROM `drivers` INNER JOIN `vendor_driver` ON drv_id = vdrv_drv_id INNER JOIN `vendors` ON vnd_id = vdrv_vnd_id WHERE vdrv_vnd_id = '$vndid' AND drv_contact_id = '$drvctcid' AND vnd_contact_id = '$vndctcid'";
		$sql			 = "SELECT COUNT(1) as cnt FROM `drivers` 
							INNER JOIN `vendor_driver` ON drv_id = vdrv_drv_id INNER JOIN `vendors` ON vnd_id = vdrv_vnd_id 
							INNER JOIN contact_profile vndcp ON vndcp.cr_is_vendor = vendors.vnd_id AND vndcp.cr_status = 1
							INNER JOIN contact_profile drvcp ON drvcp.cr_is_driver = drivers.drv_id AND drvcp.cr_status = 1
							WHERE vdrv_vnd_id = '$vndid' AND drvcp.cr_contact_id = '$drvctcid' AND vndcp.cr_contact_id = '$vndctcid'";
		$isVndDrvExist	 = DBUtil::command($sql)->queryScalar();
		return $isVndDrvExist;
	}

	public function getApiMappingByDriver($dataSet = [])
	{
		$driverContact					 = $this->drvContact;
		$resultContact					 = Contact::model()->getContactDetails($this->drv_contact_id);
		$dataSet['drv_contact_id']		 = $this->contactId;
		$dataSet['drv_country_code']	 = $resultContact['phn_phone_country_code'];
		$dataSet['drv_phone']			 = ContactPhone::model()->getContactPhoneById($this->drv_contact_id);
		$dataSet['drv_alt_phone']		 = ContactPhone::model()->getAlternateContactByDriverId($this->drv_id)->altPhoneNo;
		$dataSet['drv_email']			 = ContactEmail::model()->getContactEmailById($this->drv_contact_id);
		//$dataSet['vnd_email2'] = ContactEmail::model()->getAlternateEmailById($this->vnd_contact_id)->altEmail;
		$dataSet['drv_voter_id']		 = $driverContact->ctt_voter_no;
		$dataSet['drv_aadhaar_no']		 = $driverContact->ctt_aadhaar_no;
		$dataSet['drv_pan_no']			 = $driverContact->ctt_pan_no;
		$dataSet['drv_lic_number']		 = $driverContact->ctt_license_no;
		$dataSet['drv_issue_auth']		 = $driverContact->ctt_dl_issue_authority;
		$dataSet['drv_lic_exp_date']	 = $driverContact->ctt_license_exp_date;
		$dataSet['drv_address']			 = $driverContact->ctt_address;
		$dataSet['drv_photo_path']		 = AttachmentProcessing::ImagePath($driverContact->ctt_profile_path);
		$dataSet['dad_bank_name']		 = $driverContact->ctt_bank_name;
		$dataSet['dad_bank_account_no']	 = $driverContact->ctt_bank_account_no;
		$dataSet['dad_bank_branch']		 = $driverContact->ctt_bank_branch;
		$dataSet['dad_beneficiary_name'] = $driverContact->ctt_beneficiary_name;
		$dataSet['dad_beneficiary_id']	 = $driverContact->ctt_beneficiary_id;
		$dataSet['dad_bank_ifsc']		 = $driverContact->ctt_bank_ifsc;
		$dataSet['dad_account_type']	 = $driverContact->ctt_account_type;
		$dataSet['drv_known_language']	 = $driverContact->ctt_known_language;
		$dataSet['drv_city']			 = $driverContact->ctt_city;
		$dataSet['drv_state']			 = $driverContact->ctt_state;
		$dataSet['drv_address']			 = $driverContact->ctt_address;

		return $dataSet;
	}

	public function getAllDriverIdsByUserId($userId)
	{
		$sql			 = "SELECT drv_id,drv_user_id,drv_contact_id FROM drivers WHERE drv_user_id = '$userId' AND drv_active = 1  ";
		$driverIdsAll	 = DBUtil::queryAll($sql);
		return $driverIdsAll;
	}

	public function getAllDriversByIds($mdrvid)
	{
		$sql		 = "SELECT drivers.*,GROUP_CONCAT(vendors.vnd_name SEPARATOR ',') AS vnd_name,GROUP_CONCAT(vendors.vnd_id SEPARATOR ',') AS vnd_id,contact_phone.phn_phone_country_code,contact.ctt_license_no,contact_phone.phn_phone_no AS drv_phone,contact_email.eml_email_address AS drv_email
		FROM `drivers`   as d
        INNER JOIN drivers ON drivers.drv_id = d.drv_ref_code AND drivers.drv_active =1
        INNER JOIN contact_profile as cp on cp.cr_is_driver = drivers.drv_id and cp.cr_status =1
        LEFT JOIN contact on contact.ctt_id = cp.cr_contact_id and contact.ctt_id=contact.ctt_ref_code and contact.ctt_active =1
		LEFT JOIN contact_phone ON     contact_phone.phn_contact_id = contact.ctt_id AND contact_phone.phn_active = 1  AND contact_phone.phn_is_primary = 1
		LEFT JOIN contact_email ON     contact_email.eml_contact_id = contact.ctt_id AND contact_email.eml_active = 1  AND contact_email.eml_is_primary = 1
		LEFT JOIN `vendor_driver`  ON  vendor_driver.vdrv_drv_id = drivers.drv_id  AND vendor_driver.vdrv_active >0
		LEFT JOIN `vendors` ON         vendors.vnd_id = vendor_driver.vdrv_vnd_id AND vendors.vnd_active > 0
		WHERE drivers.drv_id IN ($mdrvid) and drivers.drv_active>0 and contact.ctt_active = 1  GROUP BY drivers.drv_id";
		$driverAll	 = DBUtil::queryAll($sql);
		return $driverAll;
	}

	public function updateDriverMerge($driverarr, $drvId)
	{
		$sql = "Update `drivers` set drv_active=2 WHERE drv_id  in ($driverarr)";
		$cnt = DBUtil::command($sql)->execute();

		$sql = "Update `drivers` set drv_is_merged=1,drv_merged_to=1 WHERE drv_id  in ($driverarr)";
		$cnt = DBUtil::command($sql)->execute();

		$sql = "Update `drivers` set `drv_ref_code` =$drvId WHERE drv_id  in ($driverarr)";
		$cnt = DBUtil::command($sql)->execute();

		$sql = "update `drivers` set drv_merge_on = now() WHERE drv_id  in ($driverarr)";
		$cnt = DBUtil::command($sql)->execute();
	}

	public function isBankAccountAdded($drvId)
	{
		$sql = "SELECT  
					COUNT(1) as isDriverAdded
				FROM
					`contact` 
                inner join contact_profile as cp on cp.cr_contact_id = contact.ctt_id and cp.cr_status =1 and contact.ctt_id = contact.ctt_ref_code
                inner JOIN drivers on cp.cr_is_driver = drivers.drv_id and drivers.drv_active =1                
				WHERE
					drivers.drv_id IN (
					SELECT d3.drv_id FROM drivers d1
					  INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
					  INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
					 WHERE d1.drv_id=$drvId
					)  AND drivers.drv_id = drivers.drv_ref_code  
					AND contact.ctt_bank_name!='' AND contact.ctt_bank_name IS NOT NULL
					AND contact.ctt_bank_ifsc!='' AND contact.ctt_bank_ifsc IS NOT NULL
					AND contact.ctt_bank_account_no!='' AND contact.ctt_bank_account_no IS NOT NULL
					AND contact.ctt_beneficiary_name!='' AND contact.ctt_beneficiary_name IS NOT NULL";
		return DBUtil::command($sql)->queryScalar();
	}

	public function findByDriverId($drvId)
	{
		/* $sql = "SELECT `contact`.* FROM
		  `contact` INNER JOIN drivers ON  contact.ctt_id = drivers.drv_contact_id
		  WHERE  drivers.drv_id IN (SELECT d3.drv_id FROM drivers d1
		  INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
		  INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
		  WHERE d1.drv_id=$drvId) AND drivers.drv_id = drivers.drv_ref_code"; */
		$sql = "SELECT `contact`.* 
				FROM `contact` 
				INNER JOIN contact_profile as cp on cp.cr_contact_id = contact.ctt_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active =1 AND cp.cr_status = 1
				INNER JOIN drivers on cp.cr_is_driver = drivers.drv_id AND drivers.drv_id = drivers.drv_ref_code AND drivers.drv_active =1
				WHERE  drivers.drv_id =$drvId";
		return DBUtil::queryRow($sql);
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
		$list = Drivers::getTripType();
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
				$tripTypes	 .= Drivers::getSingleTripType($id);
				$tripTypes	 .= (count($ids) != $ctr) ? ', ' : ' ';
				$ctr++;
			}
		}
		return $tripTypes;
	}

	public function checkDriverAvailability_old($vendorId, $startTime, $endTime)
	{
		$sql = "SELECT COUNT(distinct d2.drv_id) FROM vendor_driver 
				INNER JOIN drivers d1 ON d1.drv_id = vendor_driver.vdrv_drv_id AND vdrv_vnd_id = $vendorId
				INNER JOIN drivers d2 ON d2.drv_id = d1.drv_ref_code AND d2.drv_active = 1 AND d2.drv_approved = 1 
				LEFT JOIN booking_cab ON booking_cab.bcb_driver_id = d2.drv_id AND bcb_vendor_id = $vendorId
					  AND ('$startTime' BETWEEN bcb_start_time AND bcb_end_time) 
					  AND ('$endTime' BETWEEN bcb_start_time AND bcb_end_time)
				WHERE (bcb_id IS NULL)";
		$res = DBUtil::command($sql)->queryScalar();
		if ($res > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function checkDriverAvailability($vendorId, $startTime, $endTime)
	{
		$sql = "SELECT COUNT(distinct d2.drv_id) FROM vendor_driver
				INNER JOIN drivers d1 ON d1.drv_id = vendor_driver.vdrv_drv_id AND vdrv_vnd_id = :vnd_id
				INNER JOIN drivers d2 ON d2.drv_id = d1.drv_ref_code AND d2.drv_active = 1 AND d2.drv_approved = 1
				LEFT JOIN booking_cab ON booking_cab.bcb_driver_id = d2.drv_id AND bcb_vendor_id = :vnd_id
				INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id
				AND (booking_cab.bcb_start_time BETWEEN :startTime AND :endTime)
				AND (booking_cab.bcb_end_time BETWEEN :startTime AND :endTime)";
		$res = DBUtil::queryScalar($sql, DBUtil::SDB(), ["vnd_id" => trim($vendorId), "startTime" => $startTime, "endTime" => $endTime]);
		if ($res > 0)
		{
			$driverCount = VendorDriver::getDriverCountbyVendorid($vndId);
			if ($driverCount > $res)
			{
				return true;
			}

			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * @deprecated since 22/12/2021
	 * This function is not used anywhere in the system
	 */
	public function getDriversListByPhoneNo($phoneNo)
	{
		//$qry		 = "SELECT drv_phone, drv_id FROM drivers WHERE drv_phone = $phoneNo AND drv_active=1";
		$qry		 = "SELECT phn.phn_phone_no, drv_id FROM drivers
						INNER JOIN contact ON contact.ctt_id = drivers.drv_contact_id
						LEFT JOIN contact_phone phn ON phn.phn_contact_id=contact.ctt_id AND phn.phn_is_primary=1 AND phn.phn_active = 1
						WHERE phn.phn_phone_no =$phoneNo AND drv_active=1";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getDriverInfo($id)
	{
		$qry = "SELECT drv_name FROM drivers
				WHERE drv_id IN (SELECT d3.drv_id FROM drivers d1
				INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
				INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id WHERE d1.drv_id=$id) AND drivers.drv_id = drivers.drv_ref_code AND drv_active=1 LIMIT 0,1";
		return DBUtil::queryRow($qry);
	}

	public function getUploadDocumentByDriver($drvCntId)
	{
		$uploadDocuments = '';
		$resDriverDocs	 = Document::model()->getAllDocsbyContact($drvCntId, 'driver');
		if ($resDriverDocs[0]['doc_file_front_path3'] != '')
		{
			$uploadDocuments .= ucfirst('aadhaar Card') . " ,";
		}
		if ($resDriverDocs[0]['doc_file_back_path3'] != '')
		{
			$uploadDocuments .= ucfirst('aadhaar Back Card') . " ,";
		}
		if ($resDriverDocs[0]['doc_file_front_path4'] != '')
		{
			$uploadDocuments .= ucfirst('pan Card') . " ,";
		}
		if ($resDriverDocs[0]['doc_file_back_path4'] != '')
		{
			$uploadDocuments .= ucfirst('pan Back Card') . " ,";
		}
		if ($resDriverDocs[0]['doc_file_front_path2'] != '')
		{
			$uploadDocuments .= ucfirst('voter Id Card') . " ,";
		}
		if ($resDriverDocs[0]['doc_file_back_path2'] != '')
		{
			$uploadDocuments .= ucfirst('voter Back Id Card') . " ,";
		}
		if ($resDriverDocs[0]['doc_file_front_path5'] != '')
		{
			$uploadDocuments .= ucfirst('front License') . " ,";
		}
		if ($resDriverDocs[0]['doc_file_back_path5'] != '')
		{
			$uploadDocuments .= ucfirst('back License') . " ,";
		}
		if ($resDriverDocs[0]['doc_file_front_path7'] != '')
		{
			$uploadDocuments .= ucfirst('police Verification') . " ,";
		}
		$uploadDocuments = ($uploadDocuments != '') ? substr_replace($uploadDocuments, '', -1) : '';
		return $uploadDocuments;
	}

	public function saveInfo($drvArray)
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		$contactId	 = ContactProfile::getByEntityId($drvArray["drv_id"], UserInfo::TYPE_DRIVER);
		$contactId	 = ($contactId == '') ? $drvArray["drv_contact_id"] : $contactId;

		//$contactId = ($drvArray["drv_contact_id"] == "") ? 0 : $drvArray["drv_contact_id"];
		if ($contactId > 0)
		{
			$model = self::model()->findByDriverContactID($contactId);
			if ($model)
			{
				$this->drv_id = $model->drv_id;
			}
		}

		if ($this->isApp)
		{
			$result = Drivers::model()->checkExistingDriver($drvArray);
			if (!$result->getStatus())
			{
				$returnSet->setStatus(false);
				$returnSet->addError($result->getError('msg'), 'errkey');
				$returnSet->setData(['drv_ids' => $result->getError('drv_ids')]);
				return $returnSet;
			}
			$contactModel							 = new Contact();
			$drvName								 = explode(' ', trim($drvArray['drv_name']));
			$contactModel->ctt_first_name			 = trim($drvName[0]);
			$contactModel->ctt_last_name			 = trim($drvName[1]);
			$contactModel->ctt_state				 = $drvArray['drv_state'];
			$contactModel->ctt_city					 = $drvArray['drv_city'];
			$contactModel->ctt_address				 = $drvArray['drv_address'];
			$contactModel->ctt_aadhaar_no			 = $drvArray['drv_aadhaar_number'];
			$contactModel->ctt_license_no			 = str_replace(' ', '', $drvArray['drv_lic_number']); // remove space;
			$contactModel->ctt_dl_issue_authority	 = $drvArray['drv_issue_auth'];
			$contactModel->ctt_license_exp_date		 = $drvArray['drv_lic_exp_date'];
			$contactModel->contactEmails			 = $contactModel->convertToContactEmailObjects($drvArray['drv_email']);
			$contactModel->contactPhones			 = $contactModel->convertToContactPhoneObjects($drvArray['drv_phone']);

			$contactModel->commit	 = false;
			$contactModel->addType	 = 1;
			$returnSet				 = $contactModel->add();

			if (!$returnSet->getStatus())
			{
				$i = 0;
				foreach ($returnSet->getErrors() as $key => $error)
				{

					$decodeError = json_decode($error[$i], true);
					$errors		 = array_values($decodeError)[0][0];
					$i++;
				}
				$returnSet->addError($errors, 'errkey');
				DBUtil::rollbackTransaction($transaction);
				return $returnSet;
			}
			ContactEmail::setPrimaryEmail($returnSet->getData()['id']);
			ContactPhone::setPrimaryPhone($returnSet->getData()['id']);
			$this->drv_name			 = $drvArray['drv_name'];
			$this->drv_contact_id	 = $returnSet->getData()['id'];
			$this->drv_zip			 = $drvArray['drv_zip'];
			$this->drv_is_attached	 = 0;
		}
		if ($this->drv_id != '')
		{
			$isNew		 = false;
			$oldModel	 = Drivers::model()->findByPk($this->drv_id);
			goto skipAdd;
		}
		else
		{
			$isNew = true;
		}
		$this->drv_is_uber_approved	 = (int) $drvArray['drv_is_uber_approved'];
		$this->drv_approved			 = 2;
		$this->drv_approved_by		 = ($this->isNewRecord) ? Yii::app()->user->getId() : $this->drv_approved_by;
		$this->drv_dob_date			 = $drvArray['drv_dob_date'];
		$this->drv_active			 = 1;
		if (isset($this->drv_trip_type) && $this->drv_trip_type != '')
		{
			$this->drv_trip_type = implode(',', $this->drv_trip_type);
		}

		try
		{
			if (!$this->save())
			{
				$returnSet->setStatus(false);
				$strErr = "";
				foreach ($this->getErrors() as $key => $value)
				{
					$strErr .= $value . " ";
				}
				$returnSet->addError($strErr, 'errkey');
				DBUtil::rollbackTransaction($transaction);
				return $returnSet;
			}
			$this->refresh();
			if ($this->drv_code == null)
			{
				$codeArr			 = Filter::getCodeById($this->drv_id, "driver");
				$this->drv_code		 = $codeArr['code'];
				$this->drv_ref_code	 = $this->drv_id;
				$this->save();
			}
			Logger::trace("<==***Driver Id***==>" . $this->drv_id . "<==***Contact Id***==>" . $this->drv_contact_id);
			skipAdd:
			if ($drvArray['drv_vendor_id1'] > 0)
			{
				$data		 = ['vendor' => $drvArray['drv_vendor_id1'], 'driver' => $this->drv_id];
				$resLinked	 = VendorDriver::model()->checkAndSave($data);
				if (!$resLinked)
				{
					$returnSet->setStatus(false);
					DBUtil::rollbackTransaction($transaction);
					return $returnSet;
				}
				VendorStats::model()->updateCountDrivers($drvArray['drv_vendor_id1']);
			}
			BookingCab::model()->updateVendorPayment($flag		 = 1, $this->drv_id);
			$verifyCode	 = rand(10000, 99999);
			if ($isNew)
			{
				$this->drvContact->contactPhones[0]->phn_otp = $verifyCode;
				$this->drvContact->contactPhones[0]->save();
				$phoneNumber								 = ContactPhone::model()->getContactPhoneById($this->drv_contact_id);
				$emailAddress								 = ContactEmail::model()->getContactEmailById($this->drv_contact_id);
				$userData									 = Users::model()->findByPhone($phoneNumber);
				if (count($userData) > 1)
				{
					self::notifyToAlreadyRegistered($this->drv_id);
				}
				else
				{
					self::notifyToDriverCompleteRegistrationReminder($this->drv_id);
				}
				/*
				  $msgBody									 = (count($userData) > 1) ? "You are already registered with us. Please login with your exiting credentials." : "You are registered with us. Please complete driver registration through app - Gozocabs";
				  $alreadyRegistered							 = WhatsappLog::alreadyRegistered($this->drv_id, $drvArray['drv_name'], $phoneNumber);
				  $completeRegistraiton						 = WhatsappLog::driverCompleteRegistrationReminder($this->drv_id, $drvArray['drv_name'], $phoneNumber);
				  $response									 = (count($userData) > 1) ? $alreadyRegistered : $completeRegistraiton;
				  $modelPhone									 = ContactPhone::model()->findPhoneIdByPhoneNumber($phoneNumber);

				  if ($response['status'] == 3)
				  {
				  $smsLog = new smsWrapper();
				  $smsLog->sendDriverConfermation($modelPhone->phn_phone_country_code, $modelPhone->phn_phone_no, $msgBody);
				  }
				 * 
				 */
				$isOtpSend	 = Contact::sendVerification($phoneNumber, Contact::TYPE_PHONE, $this->drv_contact_id, Contact::NEW_CON_TEMPLATE, Contact::MODE_OTP, UserInfo::TYPE_DRIVER, 0, $modelPhone->phn_otp, $modelPhone->phn_phone_country_code);
				$isEmailSend = Contact::sendVerification($emailAddress, Contact::TYPE_EMAIL, $this->drv_contact_id, Contact::NEW_CON_TEMPLATE, Contact::MODE_LINK, UserInfo::TYPE_DRIVER);
				//$desc		 = "Driver is Created.";
				//$event		 = DriversLog::DRIVER_CREATED;
			}
			else
			{
				$newData			 = $this->attributes;
				$uploadDocuments	 = $this->getUploadDocumentByDriver($this->drv_contact_id);
				$getOldDifference	 = array_diff_assoc($oldModel->attributes, $newData);
				$changesForLog		 = "<br> Old Values: " . $this->getModificationMSG($getOldDifference, false);
				$desc				 = "Driver modified | ";
				$desc				 .= $changesForLog;
				$desc				 .= ($uploadDocuments != '') ? "Upload Documents : " . $uploadDocuments : '';
				$event				 = DriversLog::DRIVER_MODIFIED;
				$userInfo			 = UserInfo::getInstance();
				DriversLog::model()->createLog($this->drv_id, $desc, $userInfo, $event, false, false);
			}

			$returnSet->setData(['drv_id' => $this->drv_id]);
			$returnSet->setStatus(true);
			DBUtil::commitTransaction($transaction);
			Logger::trace("<==Driver Id==>" . $this->drv_id . "<==Contact Id==>" . $this->drv_contact_id);
			if ($contactId)
			{
				ContactProfile::updateEntity($contactId, $this->drv_id, UserInfo::TYPE_DRIVER);
			}

			//ContactProfile::setProfile($this->drv_contact_id, UserInfo::TYPE_DRIVER);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet->addError($e->getMessage());
		}
		return $returnSet;
	}

	public static function updateTrips($drvId, $trip_type)
	{
		$sql = "UPDATE `drivers` SET drivers.drv_trip_type='$trip_type' 
				WHERE drivers.drv_id IN (SELECT d3.drv_id FROM drivers d1
				INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
				INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				WHERE d1.drv_id='$drvId') AND drivers.drv_id = drivers.drv_ref_code";
		DBUtil::command($sql)->execute();
	}

	public function sendNotificationDriverSosToContact($userId, $driverId, $location)
	{
		$bkgId			 = $location['bkg_id'];
		$bModel			 = Booking::model()->findByPk($location['bkg_id']);
		$UserModel		 = Users::model()->findByPk($userId);
		$DriverModel	 = Drivers::model()->getById($driverId);
		$userName		 = $UserModel->usr_name . '' . $UserModel->usr_lname;
		$driverName		 = $DriverModel->drv_name;
		$sosContactList	 = Users::model()->getSosContactList($userId);
		if ($location['lat'] != 0.0 && $location['lon'] != 0.0)
		{
			foreach ($sosContactList As $value)
			{
				$emergencyUserName	 = $value['name'];
				$phone				 = str_replace('-', '', str_replace(' ', '', $value['phon_no']));
				$phoneNumber		 = substr($phone, -10);
				$emailAddress		 = $value['email'];
				$urlHash			 = Users::model()->createSOSHashUrl($bkgId, $userId);
				$url				 = Yii::app()->params['fullBaseURL'] . "/e?v=" . $urlHash;
				$msg				 = "$driverName has pressed panic button and wants to notify you of the emergency. Track their location at $url urgently contact them. Gozo is also taking action.";
				$type				 = 1;
				if (strlen($phoneNumber) >= 10)
				{
					$msgCom		 = new smsWrapper();
					$sendSmsFlag = $msgCom->sendSmsToEmergencyContact($bkgId, $phoneNumber, $msg, $type);
				}
				if ($emailAddress != '')
				{
					$emailModel		 = new emailWrapper();
					$sendEmailFlag	 = $emailModel->sendEmailToEmergencyContact($bkgId, $userName, $emergencyUserName, $emailAddress, $msg, $type);
				}
			}
			$vModel			 = Vendors::model()->getDetailsbyId($bModel->bkgBcb->bcb_vendor_id);
			$vendorPhone	 = $vModel['vnd_phone'];
			$vendorEmail	 = $vModel['vnd_email'];
			$vendorName		 = $vModel['vnd_owner'];
			$message		 = "$driverName has pressed panic button and wants to notify you of the emergency. Track their location at $url urgently contact them. Gozo is also taking action.";
			$msgCom			 = new smsWrapper();
			$sendSms		 = $msgCom->sendSmsToEmergencyContact($bkgId, $vendorPhone, $message, 1);
			$emailModel		 = new emailWrapper();
			$sendEmail		 = $emailModel->sendEmailToEmergencyContact($bkgId, $driverName, $vendorName, $vendorEmail, $msg, 1);
			$sosSmsTrigger	 = ($sendSmsFlag != Null || $sendEmailFlag != Null || $sendSms != null || $sendEmail != null) ? 2 : 1;
		}
		else
		{
			$sosSmsTrigger = 1;
		}
		$result = array('sosContactList' => $sosContactList,
			'sosSmsTrigger'	 => $sosSmsTrigger);
		return $result;
	}

	public function getTripDetails($driverId)
	{
		$sql = "SELECT bkg.bkg_booking_id AS ongoing_trip FROM booking bkg 
                    INNER  JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bcb.bcb_active = 1 
                    INNER  JOIN booking_track btk ON btk.btk_bkg_id = bkg.bkg_id AND (bkg.bkg_status IN (5) AND btk.bkg_ride_complete = 0) 
                    WHERE  bkg.bkg_active = 1 AND bkg.bkg_pickup_date <= NOW() 
                    AND    bcb.bcb_driver_id = $driverId 
                    ORDER  BY bkg.bkg_pickup_date ASC Limit 0,1";

		return DBUtil::queryRow($sql);
	}

	public function getNextTripDetails($driverId)
	{

		$sql = "SELECT CONCAT(if(FLOOR(HOUR(TIMEDIFF( NOW(), booking.bkg_pickup_date)) / 24)=0, '',concat(FLOOR(HOUR(TIMEDIFF( NOW(), booking.bkg_pickup_date)) / 24), ' days ')),
                    if(MOD(HOUR(TIMEDIFF( NOW(), booking.bkg_pickup_date)), 24)=0, '',concat(MOD(HOUR(TIMEDIFF( NOW(), booking.bkg_pickup_date)), 24), ' hours ')),
                    MINUTE(TIMEDIFF( NOW(), booking.bkg_pickup_date)), ' minutes') AS next_trip_start, COUNT(DISTINCT booking.bkg_bcb_id) AS upcoming_trip
                    FROM  booking_cab 
                    INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id 
                    AND   booking_cab.bcb_active = 1 
                    WHERE booking_cab.bcb_driver_id = $driverId
                    AND   booking.bkg_status IN (5) 
                    AND   booking.bkg_active = 1 
                    AND   booking.bkg_pickup_date >= NOW()
                    ORDER BY  booking.bkg_pickup_date ASC";

		$recordset = DBUtil::queryRow($sql, DBUtil::MDB());

		return $recordset;
	}

	public function getDetailsByBkgId($bookingId)
	{
		$params	 = array("bookingId" => $bookingId);
		$sql	 = "SELECT
					bkg.bkg_id,
					bkg.bkg_booking_id,
					drv.drv_id,
					drv.drv_user_id,
					drv.drv_code,
					cttphn.phn_phone_no AS bkg_driver_number,
					cttphn.phn_phone_country_code AS bkg_driver_code,
					cttphn.phn_is_verified,
					ctt2.ctt_license_no,
					drv.drv_contact_id,
					cttphn.phn_otp
					FROM booking bkg
					JOIN booking_cab AS bcb ON bcb.bcb_id = bkg.bkg_bcb_id
					JOIN booking_track AS btk ON btk.btk_bkg_id = bkg.bkg_id
					JOIN drivers drv ON bcb.bcb_driver_id = drv.drv_id and drv.drv_id = drv.drv_ref_code and drv.drv_active =1
					JOIN contact_profile as cp on cp.cr_is_driver = drv.drv_id and cp.cr_status =1 
					JOIN contact as ctt on ctt.ctt_id = cp.cr_contact_id
					JOIN contact as ctt2 on ctt2.ctt_id = ctt.ctt_ref_code and ctt2.ctt_active = 1
					JOIN contact_phone cttphn ON ctt2.ctt_id = cttphn.phn_contact_id AND cttphn.phn_active = 1
					WHERE bkg.bkg_booking_id = :bookingId AND btk.bkg_ride_complete = 0 AND bkg.bkg_status IN(5) ORDER BY bcb_id DESC,cttphn.phn_is_primary DESC,cttphn.phn_is_verified DESC";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	public function saveDriverImage($image, $imagetmp, $driverId, $cttid, $type)
	{
		try
		{
			$path	 = "";
			$DS		 = DIRECTORY_SEPARATOR;
			if ($image != '')
			{
				$path		 = Yii::app()->basePath;
				$image		 = $cttid . "-" . $type . "-" . date('YmdHis') . "-" . $image;
				$dir		 = $path . $DS . 'contact' . $DS . 'document' . $DS . $cttid . $DS . $type;
				$file_path	 = $dir . $DS . $image;
				$folder_path = $dir . $DS;
				$file_name	 = basename($image);
				if (file_exists($file_path))
				{
					goto skipResize;
				}
				if (!is_dir($dir))
				{
					mkdir($dir);
				}
				$reSize = Vehicles::model()->img_resize($imagetmp, 1200, $folder_path, $file_name);
				if ($reSize)
				{
					if ($type == 'agreement' || $type == 'digital_sign')
					{
						$path = substr($file_path, strlen(PUBLIC_PATH));
					}
					else
					{
						$path = substr($file_path, strlen($path));
					}
					$result = ['path' => $path];
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

	public function getContactByDrvId($drvId)
	{
		// $transaction = DBUtil::beginTransaction();
		try
		{
			$driverDetails = Drivers::model()->findBySql("SELECT * from drivers where drv_id=$drvId and drv_active=1 ");
			if ($driverDetails == null)
			{

				return array("status" => 0, 'message' => "Driver not found");
			}
			else if ($driverDetails->drv_user_id != null && $driverDetails->drv_user_id != "")
			{
				$DriverCount = Drivers::model()->getAllDriverIdsByUserId($driverDetails->drv_user_id);
				if (count($DriverCount) > 1)
				{
					return array("status" => 0, 'message' => "The email is already connected to another driver account.");
				}
				else
				{
					return array("status" => 1, 'UserId' => $driverDetails->drv_user_id, 'message' => "");
				}
			}
			else
			{
				$phoneNo					 = $driverDetails->drvContact->contactPhones != null ? $driverDetails->drvContact->contactPhones[0]->phn_phone_no : "";
				$countryCode				 = $driverDetails->drvContact->contactPhones != null ? $driverDetails->drvContact->contactPhones[0]->phn_phone_country_code : 91;
				$emailId					 = $driverDetails->drvContact->contactEmails != null ? $driverDetails->drvContact->contactEmails[0]->eml_email_address : "";
				$userId						 = $driverDetails->drvContact->getUser();
				$driverDetails->drv_user_id	 = $userId;
				$driverDetails->save();

				return array("status" => 1, 'UserId' => $userId, 'message' => "");
			}
		}
		catch (Exception $ex)
		{
			//DBUtil::rollbackTransaction($transaction);
			return array("status" => 0, 'message' => "Please try again later");
		}
	}

	public function getBkgIdByDriverId($drvId)
	{
		$sql		 = "SELECT booking.bkg_id AS next_bkg_id
                      FROM booking_cab
                      INNER JOIN booking
                       ON booking.bkg_bcb_id = booking_cab.bcb_id AND
                          booking.bkg_active = 1 AND
                          booking_cab.bcb_active = 1 
                     INNER JOIN drivers
                     ON drivers.drv_id = booking_cab.bcb_driver_id AND drivers.drv_active = 1
                     INNER JOIN booking_pref ON booking.bkg_id = booking_pref.bpr_bkg_id
                     INNER JOIN booking_track ON booking.bkg_id = booking_track.btk_bkg_id
                     WHERE booking_cab.bcb_driver_id IN (SELECT d3.drv_id FROM drivers d1
					 INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
					 INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
					 WHERE d1.drv_id='$drvId') AND drivers.drv_id = drivers.drv_ref_code AND booking_track.bkg_is_no_show = 0 AND
                     booking_track.bkg_ride_complete = 0  AND booking.bkg_status IN (5) ORDER BY
                     booking.bkg_pickup_date ASC";
		$recordset	 = DBUtil::queryRow($sql);
		return $recordset;
	}

	public function getContactByDrivers($email, $phone)
	{
		$sql		 = "SELECT d.drv_id,d.drv_contact_id
					FROM drivers
					   INNER JOIN drivers AS d ON d.drv_id = drivers.drv_ref_code and d.drv_active =1 
					   INNER JOIN contact_profile AS cp ON cp.cr_is_driver = d.drv_id AND cp.cr_status = 1 
					   INNER JOIN contact AS c ON c.ctt_id = cp.cr_contact_id AND c.ctt_active =1 AND c.ctt_id = c.ctt_ref_code 
						INNER JOIN contact_email ON c.ctt_id = contact_email.eml_contact_id AND contact_email.eml_active = 1
						INNER JOIN contact_phone ON c.ctt_id = contact_phone.phn_contact_id AND contact_phone.phn_active = 1
						WHERE contact_email.eml_email_address LIKE '%" . $email . "%' AND contact_phone.phn_phone_no = '$phone'
						AND d.drv_id = d.drv_ref_code";
		$recordset	 = DBUtil::queryRow($sql);
		//$recordset = DBUtil::command($sql)->queryScalar();
		return $recordset;
	}

	/**
	 * @deprecated function
	 * New function getLstByVendor
	 */
	public static function getListByVendor($vndid, $search_txt = '', $is_freeze = 0, $approved = 1, $flag = 0)
	{
		$where	 = '';
		$where1	 = '';
		$param	 = ['vndId' => $vndid, 'isFreeze' => $is_freeze, 'approved' => $approved];
		if ($search_txt != '' && strlen($search_txt) >= 4)
		{
			$search_txt = trim($search_txt);

			$where = " AND (d.drv_name  LIKE '%$search_txt%'  OR   
								phn_phone_no LIKE '%$search_txt%'  OR   
								d.drv_code LIKE '%$search_txt%' 
							)";
		}
		else
		{
			$where1 = " AND vdrv_vnd_id = :vndId  ";
		}

		$sql = "SELECT DISTINCT (d.drv_id), d.drv_name, phn_phone_no AS drv_phone,d.drv_code, d.drv_approved, contact_phone.phn_is_verified AS isPhVerified,
					IF(vdrv_vnd_id=:vndId,vdrv_id,0) AS vdrv_id 
					FROM   vendor_driver
					INNER JOIN drivers ON vdrv_drv_id = drv_id 
                    INNER JOIN drivers as d on d.drv_id = drivers.drv_ref_code AND d.drv_active = 1
                    INNER JOIN contact_profile as cp on cp.cr_is_driver = d.drv_id and cp.cr_status = 1
                    INNER JOIN contact as ctt on ctt.ctt_id = cp.cr_contact_id and ctt.ctt_active =1 and ctt.ctt_id = ctt.ctt_ref_code
					INNER JOIN contact_phone ON contact_phone.phn_Contact_id = ctt.ctt_id 
						AND contact_phone.phn_is_primary = 1 AND contact_phone.phn_active = 1
					WHERE  
						vdrv_active=1 $where1
						AND d.drv_is_freeze = :isFreeze AND 
						d.drv_approved = :approved $where  GROUP BY d.drv_id,vdrv_vnd_id   ORDER BY d.drv_id DESC LIMIT 0,30";

		if ($flag == 1)
		{
			$recordSet = DBUtil::query($sql, DBUtil::SDB(), $param);
			return $recordSet;
		}
		else
		{
			$result = DBUtil::queryAll($sql, DBUtil::SDB(), $param);
			return $result;
		}
	}

	/**
	 * This function is used for fetching the driver list of particular vendor
	 * @param [int] $vndid
	 * @param [string] $search_txt
	 * @return [array]
	 */
	public static function getLstByVendor($vndIds, $search_txt = '')
	{
		$where = '';

		if ($search_txt != '')
		{
			$search_txt = trim($search_txt);

			$where = " AND (d.drv_name  LIKE '%$search_txt%'  OR   
								phn_phone_no LIKE '%$search_txt%'  OR   
								d.drv_code LIKE '%$search_txt%' 
							)";
		}

		$sql = "SELECT GROUP_CONCAT(DISTINCT d1.drv_ref_code) as drvIds
				FROM vendor_driver vd
				INNER JOIN drivers d ON vd.vdrv_drv_id=d.drv_id 
					AND vd.vdrv_vnd_id IN ({$vndIds}) 
				INNER JOIN drivers d1 ON d.drv_ref_code=d1.drv_id AND d1.drv_ref_code=d1.drv_id   
				WHERE 1 AND vd.vdrv_active=1 AND d.drv_active=1";

		$driverIds = DBUtil::queryScalar($sql, DBUtil::SDB());

		if ($driverIds != "")
		{
			#echo $driverIds;
			$sql1 = "SELECT d1.drv_id, d1.drv_name,d1.drv_approved,
			d1.drv_is_freeze, c1.*, contact_phone.phn_phone_no AS drv_phone,
			d1.drv_code,contact_phone.phn_is_verified AS isPhVerified,
			vdrv_vnd_id,vnd.vnd_code, vd.vdrv_id   ,
			IF(d1.drv_approved=1,1,0) isApproved,
			IF(vnd.vnd_id=vnd.vnd_ref_code,1,0) isPrimaryVnd
			FROM drivers d 
            INNER JOIN drivers d1 ON d1.drv_id=d.drv_ref_code 
			INNER JOIN contact_profile cp ON cp.cr_is_driver=d1.drv_id 			
			INNER JOIN vendor_driver vd ON vd.vdrv_drv_id=d1.drv_id 
			INNER JOIN contact c ON c.ctt_id=cp.cr_contact_id
			INNER JOIN contact c1 ON c.ctt_ref_code=c1.ctt_id
			INNER JOIN vendors vnd ON vnd.vnd_id = vd.vdrv_vnd_id
			LEFT JOIN contact_phone ON contact_phone.phn_Contact_id = c.ctt_id 
			AND contact_phone.phn_is_primary = 1 AND contact_phone.phn_active = 1
			WHERE 1 AND d.drv_id IN ($driverIds)			
			GROUP BY d1.drv_ref_code 
			ORDER BY isApproved DESC,isPrimaryVnd DESC;";

			$recordSet = DBUtil::query($sql1, DBUtil::SDB());
		}
		return $recordSet;
	}

	public static function getDetailbyid($drvid, $is_freeze = 0, $approved = 1)
	{
		$param	 = ['drvid' => $drvid, 'isFreeze' => $is_freeze, 'approved' => $approved];
		$sql	 = "SELECT 
					d.drv_id as drv_id,
					d.drv_name as drv_name,
					phn_phone_no AS drv_phone,
					d.drv_code as drv_code,
					d.drv_approved as drv_approved
					FROM  drivers
                    INNER JOIN drivers as d on drivers.drv_id = d.drv_ref_code
                    INNER JOIN contact_profile as cp on cp.cr_is_driver = d.drv_id and cp.cr_status = 1
                    INNER JOIN contact as ctt on ctt.ctt_id = cp.cr_contact_id and ctt.ctt_active =1 and ctt.ctt_id = ctt.ctt_ref_code
					INNER JOIN contact_phone ON contact_phone.phn_Contact_id = ctt.ctt_id 	AND contact_phone.phn_is_primary = 1 AND contact_phone.phn_active = 1
					WHERE  d.drv_active = 1 AND 
					d.drv_id = :drvid  	  AND
					d.drv_is_freeze = :isFreeze AND 
					d.drv_approved = :approved
					 ";
		$result	 = DBUtil::queryAll($sql, DBUtil::MDB(), $param);
		return $result;
	}

	public function unAssignFreezeDriver($drvId)
	{
		$sql	 = "SELECT
					bkg_id,
					bcb.bcb_id,
					bkg_pickup_date,
					bkg_status
				FROM
					booking
				INNER JOIN booking_cab bcb ON
					bkg_id = bcb.bcb_bkg_id1 AND bcb.bcb_driver_id = $drvId
				WHERE
					bkg_status IN(5) AND bkg_pickup_date > NOW() + INTERVAL 1 DAY";
		$result	 = DBUtil::queryAll($sql, DBUtil::SDB());
		foreach ($result as $key => $value)
		{
			$reason = 'Driver has been freezed.';
			Booking::model()->unassignCabDriver($value['bkg_id'], $reason);
		}
	}

	public function getDriverAppNotUsed($date1, $date2, $appnotused = '', $type = '')
	{
		$dateCond = " bkg.bkg_pickup_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59'";

		if ($appnotused == 1)
		{
			$usageCond = ' AND (btk.bkg_ride_start = 0 OR btk.bkg_ride_complete = 0)';
		}
		else
		{
			$usageCond = '';
		}

		$sql = "SELECT
			   DISTINCT(bkg.bkg_booking_id),
			    bkg.bkg_id,
			    bkg.bkg_agent_id,
			    bkg.bkg_trip_duration,
				bkg.bkg_pickup_date,
			    drv.drv_id,
			    drv.drv_name,
			    bcb.bcb_vendor_id,
			    vnd.vnd_id,
			    vnd.vnd_name,
			    drv.drv_contact_id,
		        vnd.vnd_contact_id, 
				btk.btk_last_event,
                btk.bkg_ride_start as start_app,btk.bkg_ride_complete as end_app,btk.bkg_trip_arrive_time as trip_arrive_time,
			   	(CONCAT(bkg_usr.bkg_user_fname, ' ', bkg_usr.bkg_user_lname)) AS bkg_user_name
				FROM
			   booking bkg
		    INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bkg.bkg_status IN (5, 6, 7) AND bcb.bcb_active = 1 AND bkg.bkg_active = 1 AND $dateCond
		    LEFT JOIN drivers drv ON drv.drv_id = bcb.bcb_driver_id  
		    LEFT JOIN vendors vnd ON vnd.vnd_id = bcb.bcb_vendor_id  
		    INNER JOIN booking_user bkg_usr ON bkg_usr.bui_bkg_id = bkg.bkg_id
            INNER JOIN booking_track btk ON btk.btk_bkg_id = bkg.bkg_id 
		    WHERE 1 $usageCond  ";

		if ($type == 'command')
		{
			$recordSet = DBUtil::query($sql, DBUtil::SDB());
			return $recordSet;
		}
		else
		{
			$arr	 = array();
			$data	 = DBUtil::queryRow("SELECT COUNT(DISTINCT(bkg.bkg_id)) AS count FROM booking bkg  INNER  JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bkg.bkg_status IN (5, 6, 7) AND bcb.bcb_active = 1 AND bkg.bkg_active = 1 AND $dateCond
				LEFT   JOIN drivers drv ON drv.drv_id = bcb.bcb_driver_id  
				LEFT   JOIN vendors vnd ON vnd.vnd_id = bcb.bcb_vendor_id  
				INNER  JOIN booking_user bkg_usr ON bkg_usr.bui_bkg_id = bkg.bkg_id
				INNER  JOIN booking_track btk ON btk.btk_bkg_id = bkg.bkg_id 
				WHERE 1 $usageCond ", DBUtil::SDB());

			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $data['count'],
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['bkg_booking_id'],
					'defaultOrder'	 => 'bkg_id DESC'], 'pagination'	 => ['pageSize' => 50],
			]);
			$arr[0]			 = $dataprovider;
			$arr[1]			 = $data;
			return $arr;
		}
	}

	public function getDriverAppNotUsedSummary($date1, $date2, $appnotused = '')
	{
		if ($date1 != null && $date2 != null)
		{
			$param = " bkg.bkg_pickup_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59'";
		}

		if ($appnotused == 1)
		{
			$usageCond = ' AND (btk.bkg_ride_complete = 0 OR btk.bkg_ride_start = 0)';
		}
		else
		{
			$usageCond = ' ';
		}

		$param .= $usageCond;

		$sql = "SELECT COUNT(DISTINCT bkg_id) AS total_booking,
                        COUNT(IF(btk.bkg_ride_start=1, 1, NULL)) AS start_count, 
                        COUNT(IF(btk.bkg_ride_complete=1, 1, NULL)) AS end_count, 
                        COUNT(IF(btk.bkg_trip_arrive_time IS NOT NULL,1,NULL)) AS arrived_count,
                        COUNT(IF(btk.bkg_ride_start=1 AND btk.bkg_ride_complete=1, 1, NULL)) AS start_end_count
                        
			FROM
			booking bkg
            INNER JOIN booking_track btk ON btk.btk_bkg_id = bkg.bkg_id 
			WHERE
			bkg.bkg_status IN(5, 6, 7) AND $param";

		$resultSet = DBUtil::queryRow($sql, DBUtil::SDB());
		return $resultSet;
	}

	public function getDriverAppUsage($date1, $date2, $filters, $vndID = '', $zoneID = '', $region = '')
	{
		if ($date1 == '' && $date2 == '')
		{
			$param = ' bkg.bkg_pickup_date BETWEEN(NOW() - INTERVAL 30 DAY) AND NOW()';
		}
		else
		{
			$param = " bkg.bkg_pickup_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59'";
		}
		if ($vndID != '')
		{
			$vndCond = ' bcb.bcb_vendor_id =' . $vndID;
		}
		else
		{
			$vndCond = '1';
		}

		switch ($filters)
		{
			case '1':
				$cond	 = ' AND a.app_used_count = 0';
				break;
			case '2':
				$cond	 = ' AND a.app_used_count > 0';
				break;
			default:
				$cond	 = '';
				break;
		}

		if ($zoneID != '')
		{
			$zonCond = ' AND zon.zon_id IN(' . $zoneID . ')';
		}
		else
		{
			$zonCond = '';
		}
		if ($region != '')
		{
			$regCond = ' AND stt.stt_zone IN(' . $region . ')';
		}
		else
		{
			$regCond = '';
		}

		$sql = "SELECT CASE WHEN
						stt.stt_zone = 1 THEN 'North' WHEN stt.stt_zone = 2 THEN 'West' WHEN stt.stt_zone = 3 THEN 'Central' WHEN stt.stt_zone = 4 THEN 'South' WHEN stt.stt_zone = 5 THEN 'East' WHEN stt.stt_zone = 6 THEN 'North East' WHEN stt.stt_zone = 7 THEN 'South' ELSE '-'
					END AS Region,
					GROUP_CONCAT(zon.zon_name SEPARATOR ', ') AS city_zones,
					a.drv_id,
					a.drv_name,
					a.drv_code,
					a.phn_phone_no,
					a.drs_drv_overall_rating,
					a.drv_created,
					a.drs_last_trip_date,
					a.drs_last_logged_in,
					a.booking_count,
					a.app_used_count
					FROM
						(
						SELECT
							bkg.bkg_id,
							IFNULL(drv.drv_id,d.drv_id) as drv_id,
							IFNULL(drv.drv_name,d.drv_name) as drv_name,
							IFNULL(drv.drv_code,d.drv_code) as drv_code,
							drs.drs_drv_overall_rating,
							drv.drv_created,
							drs.drs_last_trip_date,
							drs.drs_last_logged_in,
							bcb.bcb_vendor_id,
							phn.phn_phone_no,
							ctt.ctt_city,
							COUNT(DISTINCT bkg.bkg_id) AS booking_count, 
							COUNT(DISTINCT btl.btl_bkg_id) AS app_used_count 
						FROM
							booking bkg
						INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bkg.bkg_status IN (6, 7) AND bcb.bcb_active = 1 AND bkg.bkg_active = 1 AND $param
						INNER JOIN drivers d ON d.drv_id = bcb.bcb_driver_id  
						INNER JOIN drivers drv on d.drv_id = drv.drv_ref_code and drv.drv_active =1 
						INNER JOIN driver_stats drs ON drs.drs_drv_id = drv.drv_id
						LEFT JOIN booking_track_log btl ON bkg.bkg_id = btl.btl_bkg_id AND btl.btl_event_platform = 5 
					
						INNER JOIN contact_profile cp ON cp.cr_is_driver = drv.drv_id AND cp.cr_status =1 
						LEFT JOIN contact AS ctt ON ctt.ctt_id =cp.cr_contact_id AND ctt.ctt_id = ctt.ctt_ref_code AND ctt.ctt_active =1
						LEFT JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id AND phn.phn_is_primary = 1 
						WHERE $vndCond
						GROUP BY bcb.bcb_driver_id 
						) AS a
						LEFT JOIN cities cty ON
							a.ctt_city = cty.cty_id AND cty.cty_active = 1
						LEFT JOIN states stt ON
							cty.cty_state_id = stt.stt_id
						LEFT JOIN zone_cities zct ON
							zct.zct_cty_id = cty.cty_id AND zct.zct_active = 1
						LEFT JOIN zones zon ON
							zct.zct_zon_id = zon.zon_id
						WHERE 1 $cond $zonCond $regCond
						GROUP BY
							a.drv_id";

		$sqlCount = "SELECT
					a.drv_id,
					a.booking_count,
					a.app_used_count
					FROM
						(
						    SELECT
							IFNULL(drv.drv_id,d.drv_id) as drv_id,
							ctt.ctt_city,
							COUNT(DISTINCT bkg.bkg_id) AS booking_count, 
						COUNT(DISTINCT btl.btl_bkg_id) AS app_used_count 
						FROM
						booking bkg
						INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bkg.bkg_status IN (6, 7) AND bcb.bcb_active = 1 AND bkg.bkg_active = 1 AND $param
						INNER JOIN drivers d ON d.drv_id = bcb.bcb_driver_id 
						LEFT JOIN booking_track_log btl ON bkg.bkg_id = btl.btl_bkg_id AND btl.btl_event_platform = 5 
						INNER JOIN drivers drv on d.drv_id = drv.drv_ref_code and drv.drv_active =1 
						INNER JOIN contact_profile cp ON cp.cr_is_driver = drv.drv_id AND cp.cr_status =1 
						LEFT JOIN contact AS ctt ON ctt.ctt_id =cp.cr_contact_id AND ctt.ctt_id = ctt.ctt_ref_code AND ctt.ctt_active =1
						LEFT JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id AND phn.phn_is_primary = 1 
						WHERE $vndCond
						GROUP BY bcb.bcb_driver_id 
						) AS a
						LEFT JOIN cities cty ON
							a.ctt_city = cty.cty_id AND cty.cty_active = 1
						LEFT JOIN states stt ON
							cty.cty_state_id = stt.stt_id
						LEFT JOIN zone_cities zct ON
							zct.zct_cty_id = cty.cty_id AND zct.zct_active = 1
						LEFT JOIN zones zon ON
							zct.zct_zon_id = zon.zon_id
						WHERE 1 $cond $zonCond $regCond
						GROUP BY
							a.drv_id";

		$arr			 = array();
		$data			 = DBUtil::queryRow("SELECT  COUNT(a.drv_id) AS count,sum(a.booking_count) AS total_booking_count,sum(a.app_used_count) AS total_app_used_count FROM ($sqlCount) a", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $data['count'],
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['booking_count', 'app_used_count'],
				'defaultOrder'	 => 'app_used_count DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		$arr[0]			 = $dataprovider;
		$arr[1]			 = $data;
		return $arr;
	}

	public function driverAppUsage($date1, $date2, $filter, $vndID = '', $zoneID = '', $region = '')
	{
		if ($date1 != null && $date2 != null)
		{
			$param = " bkg.bkg_pickup_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59'";
		}
		else
		{
			$param = ' bkg.bkg_pickup_date BETWEEN(NOW() - INTERVAL 30 DAY) AND NOW()';
		}
		if ($vndID != '')
		{
			$vndCond = ' bcb.bcb_vendor_id =' . $vndID;
		}
		else
		{
			$vndCond = '1';
		}
		switch ($filter)
		{
			case '1':
				$cond	 = 'AND a.app_used_count = 0';
				break;
			case '2':
				$cond	 = 'AND a.app_used_count > 0';
				break;
			default:
				$cond	 = '';
				break;
		}
		if ($zoneID != '')
		{
			$zonCond = ' AND zon.zon_id IN(' . $zoneID . ')';
		}
		else
		{
			$zonCond = '';
		}
		if ($region != '')
		{
			$regCond = ' AND stt.stt_zone IN(' . $region . ')';
		}
		else
		{
			$regCond = '';
		}
		$sql = "SELECT CASE WHEN
						stt.stt_zone = 1 THEN 'North' WHEN stt.stt_zone = 2 THEN 'West' WHEN stt.stt_zone = 3 THEN 'Central' WHEN stt.stt_zone = 4 THEN 'South' WHEN stt.stt_zone = 5 THEN 'East' WHEN stt.stt_zone = 6 THEN 'North East' WHEN stt.stt_zone = 7 THEN 'South' ELSE '-'
					END AS Region,
					GROUP_CONCAT(zon.zon_name SEPARATOR ', ') AS city_zones,
					a.drv_id,
					a.drv_name,
					a.drv_code,
					a.phn_phone_no,
					a.booking_count,
					a.app_used_count
					FROM
						(
						SELECT
							drv.drv_id,
							drv.drv_name,
							bcb.bcb_vendor_id,
							drv.drv_code,
							phn.phn_phone_no,
							ctt.ctt_city,
						COUNT(DISTINCT bkg.bkg_id) AS booking_count, 
						COUNT(DISTINCT btl.btl_bkg_id) AS app_used_count 
						FROM
							booking bkg
						INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bkg.bkg_status IN (6, 7) AND bcb.bcb_active = 1 AND bkg.bkg_active = 1 AND $param
						INNER JOIN drivers drv ON drv.drv_id = bcb.bcb_driver_id  
						LEFT JOIN booking_track_log btl ON bkg.bkg_id = btl.btl_bkg_id AND btl.btl_event_platform = 5 
						LEFT JOIN contact ctt ON drv.drv_contact_id = ctt.ctt_id AND ctt.ctt_active = 1 
						LEFT JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id AND phn.phn_is_primary = 1 
						WHERE $vndCond
						GROUP BY bcb.bcb_driver_id 
						) AS a
						LEFT JOIN cities cty ON
							a.ctt_city = cty.cty_id AND cty.cty_active = 1
						LEFT JOIN states stt ON
							cty.cty_state_id = stt.stt_id
						LEFT JOIN zone_cities zct ON
							zct.zct_cty_id = cty.cty_id AND zct.zct_active = 1
						LEFT JOIN zones zon ON
							zct.zct_zon_id = zon.zon_id
						WHERE 1 $cond $zonCond $regCond
						GROUP BY
							a.drv_id
						ORDER BY
							a.app_used_count
						DESC";

		$recordSet = DBUtil::queryAll($sql, DBUtil::SDB());
		return $recordSet;
	}

	public function getDriverAppFilter()
	{
		$source = [
			1	 => 'Zero Count',
			2	 => 'Non-zero Count'
		];
		return $source;
	}

	/**
	 * This function is used for adding driver details
	 * @param type $newContactId
	 * @param type $driverName
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function addDriverDetails($newContactId, $driverName, $loggedInId = 0, $source = 0)
	{
		$returnset = new ReturnSet();
		if (empty($entityId))
		{
			$entityId = UserInfo::getEntityId();
		}
		try
		{
			if (empty($newContactId) || empty($driverName))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}
			$driverModel				 = new Drivers();
			$driverModel->drv_user_id	 = "";
			if ($source)
			{
				$driverModel->drv_user_id = empty(UserInfo::getUserId()) ? $loggedInId : UserInfo::getUserId();
			}
			$driverModel->drv_contact_id = $newContactId;
			$driverModel->drv_name		 = $driverName;
			$driverModel->drv_active	 = 1;

			if ($entityId == Config::get('hornok.operator.id'))
			{
				$driverModel->scenario = "skipLicesnse";
			}
			$result = CActiveForm::validate($driverModel, null, false);
			if ($result == '[]')
			{
				if (!$driverModel->save())
				{
					throw new Exception(json_encode($driverModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
			}
			else
			{
				$returnset->setStatus(false);
				$returnset->setMessage("Driver already exists in the system with this contact");
				throw new Exception(json_encode($driverModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$userInfo	 = UserInfo::getInstance();
			$platform	 = Users::Platform_Web;
			DriversLog::model()->createLog($driverModel->drv_id, "Driver added from $platform", $userInfo, DriversLog::DRIVER_CREATED);

			$updateModel = Drivers::model()->findByPk($driverModel->drv_id);

			$codeArray					 = Filter::getCodeById($updateModel->drv_id, 'driver');
			$updateModel->drv_code		 = $codeArray["code"];
			$updateModel->drv_ref_code	 = $updateModel->drv_id;

			if (!$updateModel->save())
			{
				throw new Exception(json_encode($updateModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$returnset->setStatus(true);
			$returnset->setMessage("Driver created");
			$returnset->setData($updateModel->drv_id);
		}
		catch (Exception $e)
		{
			Logger::exception($e);
			$returnset->setException($e);
		}
		return $returnset;
	}

	/**
	 * This function is used for new and update driver details
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function handleDriver($vndId, $contactId, $drvName, $emailId, $phoneNo)
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($vndId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			$model					 = new Drivers();
			//$model->scenario		 = "";
			$modelPhone				 = ContactPhone::model()->findPhoneIdByPhoneNumber($phoneNo);
			$model->drv_name		 = $drvName;
			$model->drv_contact_id	 = $contactId;
			$model->drv_active		 = 1;
			$model->drv_created		 = new \CDbExpression('now()');

			$result = CActiveForm::validate($model, null, false);
			if ($result == "[]")
			{
				if (!$model->save())
				{
					$returnset->setMessage("Failed to save in the system");
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
			}
			else
			{
				$returnset->setStatus(false);
				$returnset->setMessage("Driver already exists in the system with this contact");
				throw new Exception(json_encode($driverModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$platform	 = Users::Platform_App;
			$userInfo	 = UserInfo::getInstance();
			DriversLog::model()->createLog($model->drv_id, "Driver added from $platform", $userInfo, DriversLog::DRIVER_CREATED);

			$updateModel = Drivers::model()->findByPk($model->drv_id);

			$codeArray					 = Filter::getCodeById($updateModel->drv_id, 'driver');
			$updateModel->drv_code		 = $codeArray["code"];
			$updateModel->drv_ref_code	 = $updateModel->drv_id;

			if (!$updateModel->save())
			{
				throw new Exception(json_encode($updateModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

//            //creates vendor driver relationship
//            $vendorDriver["driver"] = $model->drv_id;
//            $vendorDriver["vendor"] = $vndId;
//            $response               = VendorDriver::model()->checkAndSave($vendorDriver);

			ContactProfile::setProfile($model->drv_contact_id, UserInfo::TYPE_DRIVER);
			$isOtpSend	 = Contact::sendVerification($phoneNo, Contact::TYPE_PHONE, $contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_OTP, UserInfo::TYPE_DRIVER, 0, $modelPhone->phn_otp, $modelPhone->phn_phone_country_code, $vndId);
			$isEmailSend = Contact::sendVerification($emailId, Contact::TYPE_EMAIL, $contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_LINK, UserInfo::TYPE_DRIVER, 0, 0, 0, $vndId);

			$returnset->setMessage("Failed to create driver");
			if ($isEmailSend || $isOtpSend)
			{
				$response		 = new stdClass();
				$response->id	 = $model->drv_id;
				$returnset->setStatus(true);
				$returnset->setData($response);
				$returnset->setMessage("Successfully added !. We have sent verification link on the contact details. Please verify the contact details to activate the account");
			}
		}
		catch (Exception $e)
		{
			Logger::exception($e);
			$returnset->setException($e);
		}

		return $returnset;
	}

	public function isIdExists($contactId)
	{
		$sql	 = "SELECT COUNT(1) FROM drivers WHERE drv_contact_id = :id";
		$count	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $contactId]);
		return $count;
	}

	public static function mergeAccountDetails($mgrDrvId, $drvdid)
	{
		$drRefId		 = $mgrDrvId;
		$driverAmount	 = AccountTransDetails::model()->calBonusAmountByDriverId($mgrDrvId, '', '', '');
		$amount			 = $driverAmount['bonus_amount'] != NULL ? $driverAmount['bonus_amount'] : 0;
		$amount1		 = $amount;
		if ($amount != 0)
		{
			$crRefId		 = $drvdid;
			$crRemarks		 = "Adjusting accounts due to merging of driver as $mgrDrvId is merged with $drvdid";
			$drRemarks		 = "Adjusting accounts due to merging of driver as $drvdid is merged with $mgrDrvId";
			$accTransModel	 = new AccountTransactions();
			if ($amount < 0)
			{
				$accTransModel->act_amount = -1 * $amount1;
			}
			if ($amount > 0)
			{
				$accTransModel->act_amount = $amount1;
			}
			$drLedgerId					 = Accounting::LI_DRIVER;
			$drAcctType					 = Accounting::AT_DRIVER;
			$crLedgerID					 = Accounting::LI_DRIVER;
			$crAccType					 = Accounting::AT_DRIVER;
			$accTransModel->act_amount	 = $amount;
			$accTransModel->act_date	 = new CDbExpression('NOW()');
			$accTransModel->act_type	 = $crAccType;
			$accTransModel->act_ref_id	 = $crRefId;
			$accTransModel->act_remarks	 = $crRemarks;
			$accTransModel->mergeAccountBalance($drLedgerId, $crLedgerID, $drRefId, $crRefId, $drAcctType, $crAccType, $drRemarks, $crRemarks, UserInfo::getInstance(), $amount);
		}
	}

	/**
	 * This function is used for merging the duplicate driver accounts
	 * @param type $primaryId
	 * @param type $duplicateId
	 * @throws Exception
	 */
	public static function merge($primaryId, $duplicateId)
	{
		$trans = Yii::app()->db->beginTransaction();
		try
		{
			if (empty($primaryId) || empty($duplicateId))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}

			if ($primaryId != "")
			{
				$model = Drivers::model()->resetScope()->findByPk($primaryId);
			}

			$vendors = VendorDriver::model()->getVendorListbyDriverid($duplicateId);

			//	Drivers::mergeAccountDetails($duplicateId, $primaryId);
			if (sizeof($vendors) > 0)
			{
				foreach ($vendors as $ven)
				{
					$arr = ['driver' => $model->drv_id, 'vendor' => $ven['vdrv_vnd_id']];
					VendorDriver::model()->checkAndSave($arr);
				}
			}

			$newDriver	 = $model->drv_id;
			$oldDriver	 = $duplicateId;
			///		Drivers::model()->replaceDriverDetailsFromBooking($oldDriver, $newDriver);

			if (Drivers::model()->deactivatebyId($duplicateId))
			{
				$remark	 = $model->drv_log;
				$newLog	 = ['drv_id_merge' => 'Driver id ' . $duplicateId . ' merged and deactivated'];
				$dt		 = new CDbExpression('NOW()');
				$user	 = 0; //For system auto
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
				while (count($newcomm) >= 50)
				{
					array_pop($newcomm);
				}
				array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $newLog));
				$log			 = CJSON::encode($newcomm);
				$model->drv_log	 = $log;
			}

			DriverMerged::model()->addMergedData($newDriver, $oldDriver, $user);

			$success = $model->save();
			if (!$success)
			{
				throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			Drivers::model()->updateDriverMerge($duplicateId, $model->drv_id);
			DriversLog::model()->createLog($model->drv_id, "Driver Merge :  $duplicateId  is merged with $model->drv_id", UserInfo::getInstance(), DriversLog::Driver_MERGE, false, false);

			$trans->commit();
		}
		catch (Exception $e)
		{
			Logger::error($e->getTraceAsString());
			if (Yii::app() instanceof CConsoleCommand)
			{
				echo $e->getTraceAsString();
			}
			$trans->rollback();
		}
	}

	/**
	 * 
	 * @param type $primaryContactId
	 * @param type $duplicateContactId
	 * @return boolean
	 * @throws Exception
	 */
	public static function mergeConIds($primaryContactId, $duplicateContactId, $source = null)
	{
		if (empty($primaryContactId) || empty($duplicateContactId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		$sql			 = "SELECT * FROM `drivers` WHERE `drv_contact_id` =:id";
		$arrDupDrvData	 = DBUtil::command($sql, DBUtil::MDB())->query(['id' => $duplicateContactId]);

		if (!empty($arrDupDrvData))
		{
			foreach ($arrDupDrvData as $drvData)
			{
				$drvId = $drvData["drv_id"];

				$updateDuplicate = "UPDATE `drivers` 
							SET    `drv_contact_id` = $primaryContactId
							WHERE  drv_contact_id = $duplicateContactId AND drv_id = $drvId";

				$numrows = DBUtil::command($updateDuplicate)->execute();

				ContactMerged::updateReferenceIds($primaryContactId, $duplicateContactId, ContactMerged::TYPE_DRIVER, $drvId);

				$docType = "";
				if ($source == Document::Document_Licence)
				{
					$docType = "(Driving License matched)";
				}

				$message = "Contacts merged - Old Contact ID: $duplicateContactId, New Contact Id: $primaryContactId. $docType";
				DriversLog::model()->createLog($drvId, $message, null, DriversLog::DRIVER_MODIFIED, false, false);
			}
		}
	}

	public static function getUserContact($driverId)
	{
		$sql = "SELECT d3.drv_ref_code as driverId, cttPrimary.ctt_id as contactId, IFNULL(cpPrimary.cr_is_consumer, cp.cr_is_consumer) as userId
				FROM drivers d1
				INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
				INNER JOIN drivers d3 ON d3.drv_id=d2.drv_ref_code
				INNER JOIN contact_profile cp ON cp.cr_is_driver=d3.drv_ref_code AND cp.cr_status=1
				INNER JOIN contact ctt ON ctt.ctt_id=cp.cr_contact_id
				INNER JOIN contact cttPrimary ON ctt.ctt_ref_code=cttPrimary.ctt_id
				LEFT JOIN contact_profile cpPrimary ON cpPrimary.cr_contact_id=cttPrimary.ctt_id AND cpPrimary.cr_status=1
				WHERE 1 and d3.drv_id=:driverId";
		$row = DBUtil::queryRow($sql, DBUtil::SDB(), ["driverId" => $driverId]);
		return $row;
	}

	public function mergedDriverId($driverId = "")
	{
		if ($driverId != "")
		{
			$sql			 = "WITH RECURSIVE tree (drv_ref_code,drv_id,level) AS 
					(
						SELECT drv_ref_code,drv_id, 1 AS level FROM drivers WHERE drv_id = :driverId
						UNION ALL
						SELECT lpc.drv_ref_code,lpc.drv_id,t.level + 1 FROM drivers lpc
						INNER JOIN tree t ON t.drv_ref_code = lpc.drv_id
					)
					SELECT drv_ref_code,level FROM tree WHERE 1 GROUP BY drv_ref_code ORDER BY level DESC LIMIT 1";
			$driversDetails	 = DBUtil::queryRow($sql, DBUtil::SDB(), ['driverId' => $driverId]);
			return Drivers::model()->findByPk($driversDetails['drv_ref_code']);
		}
		else
		{
			return;
		}
	}

	public static function getCurrentListBookings($driverId, $bookingId = null)
	{
		$maxHour			 = 6;
		$uberAgentId		 = Yii::app()->params['uberAgentId'];
		//$custMaskedPhone = Yii::app()->params['driverToCustomer'];
		//$custMaskedPhone = explode("/", $custMaskedPhone, 2)[0];
		$driverToCustomer	 = CJSON::decode(Config::get('mask.customer.driver.number'), true);
		$custMaskedPhone	 = $driverToCustomer['driverToCustomer'];
		if ($bookingId != null)
		{
			$condition = "AND bkg_id = $bookingId";
		}

		$lastStartTime = BookingCab::showLastStartTime($driverId);

		if ($lastStartTime != null || $lastStartTime != "")
		{
			$con = " AND bkg_pickup_date >= '$lastStartTime'";
		}
		$qry = "SELECT CASE  WHEN(btk_last_event IS NULL) THEN 0
										WHEN btk_last_event IN(107,108,109,110) THEN 203
										ELSE btk_last_event
								END AS btl_event_type_id,
								CASE WHEN(bkg_pickup_date < NOW()) AND btk_last_event IN(101, 102, 103, 104) THEN 2
									WHEN bkg_pickup_date < NOW() AND
											(btk_last_event IN(201,202,203,204,205,206) OR btk_last_event IS NULL) THEN 1
									ELSE 0
								END AS isOverDue, bkg_bcb_id, bkg_id, bkg_booking_id, bkg_vendor_amount, bkg_modified_on,
								bkg_pickup_address, bkg_drop_address, 0 AS is_flexxi, bkg_flexxi_type, brt_from_latitude,
								brt_from_longitude, brt_to_latitude, brt_to_longitude,
								IF(brt_from_latitude IS NULL, 0, brt_from_latitude) AS bkg_pickup_lat,
								IF(brt_from_longitude IS NULL, 0, brt_from_longitude) AS bkg_pickup_long,
								c1.cty_name AS bkg_from_city, c2.cty_name AS bkg_to_city, c2.cty_lat AS dest_cty_lat, c2.cty_long AS dest_cty_long,
								bkg_user_fname AS bkg_user_name, bkg_user_lname,
								IF(btk_last_event =101,concat(bkg_country_code,bkg_contact_no),'$custMaskedPhone') AS bkg_contact_no,
								bkg_alt_contact_no  AS bkg_alternate_contact, bkg_user_email AS bkg_user_email, bkg_country_code AS bkg_country_code,
								a.vnd_name AS bkg_vendor_name, bkg_status, bkg_no_person,
								bkg_pickup_date, booking_cab.bcb_start_time, booking_cab.bcb_end_time,
								booking_cab.bcb_cab_id, bkg_total_amount, v2.vht_model AS bkg_cab_assigned, bkg_booking_type, bkg_instruction_to_driver_vendor,
								bcb_driver_phone AS bkg_driver_number, d.drv_name AS bkg_driver_name, booking_track.bkg_drv_sos_sms_trigger AS drv_sos_trigger,
								v1.vhc_number AS bkg_cab_number, bkg_trip_duration, bcb_vendor_id AS bkg_vendor_id, bcb_driver_id AS bkg_driver_id,
								bkg_due_amount, bkg_create_date, v.vct_desc AS bkg_cab_type, bkg_trip_distance, bkg_additional_charge, bkg_additional_charge_remark,
								bkg_vendor_collected, bkg_service_tax_rate, bkg_start_odometer, bkg_end_odometer, bkg_trip_otp  AS bpr_trip_otp,
								bkg_is_trip_verified AS bpr_is_trip_verified, booking_track.bkg_is_no_show, bpr.bkg_trip_otp_required AS bpr_trip_otp_required,
								bpr.bkg_duty_slip_required AS is_duty_slip_required, IF(btk_last_event= 203,1,0) as bkg_arrived_for_pickup,
								bkg_night_pickup_included, bkg_night_drop_included, vhs.vhs_boost_enabled AS isBoostEnabled, bkg_agent_id,
								((IFNULL(bkg_advance_amount,0) + IFNULL(bkg_credits_used,0) - IFNULL(bkg_refund_amount,0)) + IFNULL(bkg_vendor_collected,0)) AS bkg_advance_paid,
								IF(bkg_vehicle_type_id != " . VehicleCategory::SHARED_SEDAN_ECONOMIC . ", bkg_rate_per_km_extra, 0) AS bkg_rate_per_km_extra,
								booking_invoice.bkg_trip_waiting_charge, booking_invoice.bkg_extra_pickup_charge, booking_invoice.bkg_extra_drop_charge,
								booking_invoice.bkg_is_airport_fee_included, booking_invoice.bkg_airport_entry_fee,booking_invoice.bkg_extra_per_min_charge,
								IF(booking_invoice.bkg_corporate_remunerator=2,'1','0')as credit_booking
							FROM   booking
							INNER JOIN booking_route ON booking_route.brt_bkg_id = booking.bkg_id
							INNER JOIN booking_invoice ON booking.bkg_id = booking_invoice.biv_bkg_id
							INNER JOIN booking_user ON booking.bkg_id = booking_user.bui_bkg_id
							LEFT JOIN contact ON booking_user.bkg_contact_id = contact.ctt_id AND contact.ctt_active = 1
							LEFT JOIN contact_phone  ON bkg_contact_id = contact_phone.phn_contact_id AND contact_phone.phn_is_primary = 1 AND contact_phone.phn_active = 1
							LEFT JOIN contact_email  ON bkg_contact_id = contact_email.eml_contact_id AND contact_email.eml_is_primary = 1 AND contact_email.eml_active = 1
							INNER JOIN booking_add_info ON booking.bkg_id = booking_add_info.bad_bkg_id
							INNER JOIN booking_track ON booking.bkg_id = booking_track.btk_bkg_id AND booking_track.bkg_ride_complete = 0
							INNER JOIN booking_pref bpr On booking.bkg_id = bpr.bpr_bkg_id
							INNER JOIN booking_cab ON bcb_id = bkg_bcb_id
							INNER JOIN cities c1 ON bkg_from_city_id = c1.cty_id
							INNER JOIN cities c2 ON bkg_to_city_id = c2.cty_id
							INNER JOIN svc_class_vhc_cat scv ON bkg_vehicle_type_id = scv.scv_id
							INNER JOIN vehicle_category v ON scv.scv_vct_id = v.vct_id
							LEFT JOIN vehicles v1 ON bcb_cab_id = v1.vhc_id
							LEFT JOIN drivers d ON bcb_driver_id = d.drv_id
							LEFT JOIN vendors a ON bcb_vendor_id = a.vnd_id
							LEFT JOIN vehicle_types v2 ON v1.vhc_type_id = v2.vht_id
							LEFT JOIN vehicle_stats vhs ON vhs.vhs_vhc_id = v1.vhc_id
							WHERE  (booking_track.bkg_is_no_show = 0 OR (booking_track.bkg_is_no_show =1
										AND TIMESTAMPDIFF(MINUTE, booking_route.brt_pickup_datetime, now()) <= 60))
									AND bkg_status IN (5) AND bcb_driver_id = $driverId $condition $con
							GROUP BY bkg_id ORDER BY `bkg_pickup_date` ASC";

		$recordset	 = DBUtil::query($qry, DBUtil::SDB());
		$resultSet	 = [];
		$i			 = 0;

		foreach ($recordset as $val)
		{
			$resultSet[$i] = $val;
			foreach ($val as $k => $v)
			{
				if ($k == 'bkg_id')
				{
					$eventType							 = BookingTrackLog::model()->getEventTypeByBkg($v);
					$resultSet[$i]['ttg_event_type']	 = ($eventType > 0) ? $eventType : '0';
					$resultSet[$i]['bkg_start_odometer'] = BookingTrack::model()->getOdometerReading($v);
					$resultSet[$i]['bkg_route_name']	 = BookingRoute::model()->getRouteName($v);
				}
				if ($k == 'bkg_agent_id')
				{
					if (($v != null || $v != '') && $v == $uberAgentId)
					{
						$resultSet[$i]['bkg_pickup_date'] = BookingCab::model()->getPickupDateTime("Y-m-d H:i:s", $val['bkg_pickup_date'], $v);
					}
				}
			}
			$resultSet[$i]['btl_event_type_id']	 = (int) $val['btl_event_type_id'];
			$resultSet[$i]['cab_verify']		 = Vehicles::model()->checkCarVerifyStatus($val['bcb_cab_id'], $val['bkg_id']);
			$resultSet[$i]['isBoostEnabled']	 = (int) $val['isBoostEnabled'];
			$i++;
		}

		return $resultSet;
	}

	/**
	 * This function is used for new and update driver details
	 * @param type $contactId
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function processData($contactId, $entityType, $entityId = null)
	{
		$returnSet = new ReturnSet();
		if (empty($entityId))
		{
			$entityId = UserInfo::getEntityId();
		}

		/** @var Contact $contactModel */
		$contactModel	 = Contact::model()->findByPk($contactId);
		$emailId		 = $contactModel->contactEmails[0]->eml_email_address;
		$phoneNo		 = $contactModel->contactPhones[0]->phn_phone_no;
		/** @var ContactPhone $modelPhone */
		$modelPhone		 = ContactPhone::model()->findPhoneIdByPhoneNumber($phoneNo);

		$profileId	 = ContactProfile::getEntityById($contactId, $entityType);
		$isExists	 = 0;
		if ($profileId > 0)
		{
			$isExists	 = 1;
			$returnSet	 = ContactTemp::processData($contactModel, UserInfo::TYPE_DRIVER, $entityId);
			if ($returnSet->getStatus())
			{
				$response			 = new stdClass();
				$response->isExists	 = $isExists;
				$response->id		 = (int) $profileId['id'];
				$response->contactId = (int) $contactId;
				$returnSet->setData($response);
			}
			goto skipAll;
		}
		else
		{
			$model					 = new Drivers();
			$model->drv_contact_id	 = $contactId;
			$model->drv_name		 = $contactModel->ctt_first_name . $contactModel->ctt_last_name;
			$model->drv_active		 = 1;
			$model->drv_created		 = new \CDbExpression('now()');
			if ($entityId == Config::get('hornok.operator.id'))
			{
				$model->scenario = "skipLicesnse";
			}
			$result = CActiveForm::validate($model, null, false);

			if ($result != "[]")
			{

				//$returnSet->setMessage(CJSON::decode($result),ReturnSet::ERROR_FAILED);
				$returnSet->setMessage("Driver Already exist", ReturnSet::ERROR_FAILED);
				$returnSet->setStatus(false);
				goto skipAll;
			}
			if (!$model->save())
			{
				$returnSet->setErrors($model->getErrors());
				$returnSet->setStatus(false);
				goto skipAll;
			}

			$platform	 = Users::Platform_App;
			$userInfo	 = UserInfo::getInstance();
			DriversLog::model()->createLog($model->drv_id, "Driver added from $platform", $userInfo, DriversLog::DRIVER_CREATED);

			$updateModel				 = Drivers::model()->findByPk($model->drv_id);
			$codeArray					 = Filter::getCodeById($updateModel->drv_id, 'driver');
			$updateModel->drv_code		 = $codeArray["code"];
			$updateModel->drv_ref_code	 = $updateModel->drv_id;
			if (!$updateModel->save())
			{
				throw new Exception(json_encode($updateModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			ContactProfile::setProfile($model->drv_contact_id, UserInfo::TYPE_DRIVER);

			if (Config::get('hornok.operator.id') == $entityId)
			{
				$isOtpSend	 = true;
				$isEmailSend = false;
			}
			else
			{
				$isOtpSend	 = Contact::sendVerification($phoneNo, Contact::TYPE_PHONE, $contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_OTP, UserInfo::TYPE_DRIVER, 0, $modelPhone->phn_otp, $modelPhone->phn_phone_country_code, $entityId);
				$isEmailSend = Contact::sendVerification($emailId, Contact::TYPE_EMAIL, $contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_LINK, UserInfo::TYPE_DRIVER, 0, 0, 0, $entityId);
			}


			if ($isEmailSend || $isOtpSend)
			{
				$response			 = new stdClass();
				$response->isExists	 = $isExists;
				$response->id		 = $model->drv_id;
				$returnSet->setStatus(true);
				$returnSet->setData($response);
				$returnSet->setMessage("Successfully added !. We have sent verification link on the contact details. Please verify the contact details to activate the account");
			}
		}
		skipAll:
		return $returnSet;
	}

	public static function updateDriverdata($data)
	{
		$contactId	 = $data->ctt_id;
		$model		 = Contact::model()->findByPk($contactId);

		if ($model == null)
		{
			throw new Exception("Contact not found", ReturnSet::ERROR_INVALID_DATA);
		}
		//print_r($data);

		$model->ctt_first_name			 = trim($data->ctt_first_name);
		$model->ctt_last_name			 = trim($data->ctt_last_name);
		$model->ctt_state				 = trim($data->ctt_state);
		$model->ctt_city				 = trim($data->ctt_city);
		$model->ctt_address				 = trim($data->ctt_address);
		$model->ctt_dl_issue_authority	 = trim($data->ctt_dl_issue_authority);
		//$model->ctt_license_no			 = //trim($data->ctt_license_no);
		$model->ctt_license_no			 = str_replace(' ', '', $data->ctt_license_no);
		$model->ctt_license_exp_date	 = trim($data->ctt_license_exp_date);
		$model->ctt_license_issue_date	 = trim($data->ctt_license_issue_date);
		if (!$model->update())
		{
			throw new Exception('Invalid data', ReturnSet::ERROR_VALIDATION);
		}

		$driverId	 = $data->id;
		$drvmodel	 = Drivers::model()->findByPk($driverId);

		$drvmodel->drv_zip	 = $data->zip;
		$drvmodel->drv_dob	 = $data->dob;
		if (!$drvmodel->update())
		{
			throw new Exception('Invalid data', ReturnSet::ERROR_VALIDATION);
		}
		$return = "success";
		return $return;
		//$returnset->setStatus(true);
	}

	public static function addByContact($contactModel, $entityId = null)
	{
		/** @var Contact $contactModel */
		$cttId = Drivers::processContact($contactModel);

		/** @var Drivers $contactModel->drvContact */
		$returnSet = self::processData($cttId, UserInfo::TYPE_DRIVER, $entityId);
		return $returnSet;
	}

	public function findByContactID($cttId)
	{
		$model = self::model()->findAll(array("condition" => "drv_contact_id =$cttId"));
		return $model;
	}

	public function Login($userModel, $deviceData)
	{

		$model = self::getByUserId($userModel->user_id);
		if (empty($model))
		{
			throw new Exception("Driver account not signed up with this user", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
		}
		$identity			 = new UserIdentity($userModel->usr_email, $userModel->usr_password);
		$identity->userId	 = $userModel->user_id;
		$identity->setEntityID($model->drv_id);
		$identity->setUserType(5);
		$drvSosStatus		 = DriverStats::model()->getDriverSosStatus($model->drv_id);
		if ($drvSosStatus > 0)
		{
			throw new Exception("SOS is active", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
		}

		/* @var $webUser GWebUser */
		$webUser = Yii::app()->user;
		$webUser->login($identity);

		$multiplelogin = AppTokens::model()->getAppMultiLoginStatus($model->drv_id);

		$aptModel = AppTokens::Add($webUser->getId(), 5, Yii::app()->user->getEntityID(), $deviceData);
		if (!$aptModel)
		{
			throw new Exception("Failed to create token", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
		}
		$sessionId = $aptModel->apt_token_id;
		Yii::log('Driver login ' . json_encode($aptModel), CLogger::LEVEL_INFO);

		$code	 = $model->drv_code;
		$rating	 = DriverStats::fetchRating($model->drv_id);

		$driver_details	 = Drivers::model()->findByPk($model->drv_id);
		$drvName		 = $driver_details->drv_name;

		$contact_id	 = $driver_details->drv_contact_id;
		$isActive	 = $driver_details->drv_active;

		$result = [
			'session'			 => $sessionId,
			'drvcode'			 => $code,
			'userName'			 => $drvName,
			'version'			 => '',
			'rating'			 => $rating,
			'driver_contact_id'	 => $contact_id,
			'driver_id'			 => $model->drv_id,
			'isActive'			 => $isActive,
			'multiplelogin'		 => $multiplelogin,
		];

		return $result;
	}

	public static function getByUserId($userId)
	{
		$params	 = ["userId" => $userId];
		$sql	 = "SELECT * FROM (
						SELECT d1.drv_id, IF(cp1.cr_is_consumer=:userId, 2, IF(cp.cr_is_consumer=:userId, 1, 0)) as rank
							FROM contact_profile cp
							INNER JOIN contact c ON cp.cr_contact_id=c.ctt_id
							INNER JOIN contact c1 ON c.ctt_ref_code=c1.ctt_id AND c1.ctt_active=1
							INNER JOIN contact_profile cp1 ON cp1.cr_contact_id=c1.ctt_id
							INNER JOIN drivers d ON d.drv_id=IFNULL(cp1.cr_is_driver, cp.cr_is_driver) AND d.drv_active>0
							INNER JOIN drivers d1 ON d1.drv_id=d.drv_ref_code
							WHERE (cp1.cr_is_consumer=:userId)
						UNION
						SELECT d1.drv_id, IF(cp1.cr_is_consumer=:userId, 2, IF(cp.cr_is_consumer=:userId, 1, 0)) as rank
							FROM contact_profile cp
							INNER JOIN contact c ON cp.cr_contact_id=c.ctt_id
							INNER JOIN contact c1 ON c.ctt_ref_code=c1.ctt_id AND c1.ctt_active=1
							INNER JOIN contact_profile cp1 ON cp1.cr_contact_id=c1.ctt_id
							INNER JOIN drivers d ON d.drv_id=IFNULL(cp1.cr_is_driver, cp.cr_is_driver) AND d.drv_active>0
							INNER JOIN drivers d1 ON d1.drv_id=d.drv_ref_code
							WHERE (cp.cr_is_consumer=:userId)
					) a ORDER BY rank DESC";
		$row	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		if ($row)
		{
			$driverId	 = $row['drv_id'];
			$model		 = self::model()->findByPk($driverId);
		}
		else
		{
			/** @todo To be removed after contact and users data merged */
			$model = self::model()->find("drv_user_id=:user AND drv_active>0", ["user" => $userId]);
		}
		return $model;
	}

	public static function verifyDlDates($model, $vndId)
	{
		$returnSet	 = new ReturnSet();
		$drvModel	 = Drivers::getDetailsById($model->drv_id);

		VendorDriver::unlinkDriver($model->drv_id);
		$linked = VendorDriver::model()->checkAndSave(['driver' => $model->drv_id, 'vendor' => $vndId]);
		if (!$linked)
		{
			$returnSet->setStatus(false);
			$returnSet->setErrors("Failed to link driver with vendor.");
		}
		else
		{
			if ($drvModel['ctt_license_issue_date'] == NULL || $model->drvContact->ctt_license_issue_date > $drvModel['ctt_license_issue_date'])
			{
				Contact::updateDlIssueDate($drvModel['drv_contact_id'], $model->drvContact->ctt_license_issue_date);
			}
			if ($drvModel['ctt_license_exp_date'] == NULL || $model->drvContact->ctt_license_exp_date > $drvModel['ctt_license_exp_date'])
			{
				Contact::updateDlExpiryDate($drvModel['drv_contact_id'], $model->drvContact->ctt_license_exp_date);
			}
			$returnSet->setStatus(true);
			$response				 = new stdClass();
			$response->id			 = (int) $drvModel['drv_id'];
			$response->isApproved	 = ($drvModel['drv_approved'] == 1) ? true : false;
			$returnSet->setData($response);
			$returnSet->setMessage("The driver has been successfully linked to your account");
		}

		return $returnSet;
	}

	/**
	 * This function is used for sending login verification OTP
	 * @param integer $phn_phone_no
	 * @param integer $phn_country_code
	 * @return array
	 */
	public static function sendLoginVerificationOtp($phn_phone_no, $phn_country_code)
	{
		$status = false;
		if (!empty($phn_phone_no) && !empty($phn_country_code))
		{
			$verifyCode	 = rand(10000, 99999);
			$isDelay	 = 0;
			$msg		 = "Your OTP for starting verification is " . $verifyCode . " - Gozocabs";
			$sms		 = new Messages();
			$res		 = $sms->sendMessage($phn_country_code, $phn_phone_no, $msg, $isDelay);
			$usertype	 = SmsLog::Driver;
			$slgId		 = smsWrapper::createLog($phn_country_code, $phn_phone_no, "", $msg, $res, $usertype, "", "", "", "", $isDelay);
			$status		 = ($slgId) ? true : false;
		}
		return ["status" => $status, 'verifyCode' => $verifyCode];
	}

	/**
	 * This function is used for getting Driver App Usage Details
	 * @param integer $arr 
	 * @return dataprovider
	 */
	public static function getDriverAppusageDetails($arr = [], $command = DBUtil::ReturnType_Provider)
	{
		$where = '';
		if ($arr['bkg_pickup_date1'] != '' && $arr['bkg_pickup_date2'] != '')
		{
			$fromDate	 = $arr['bkg_pickup_date1'];
			$toDate		 = $arr['bkg_pickup_date2'];
			$where		 .= " AND bkg_pickup_date>= '" . $fromDate . "' AND bkg_pickup_date < '" . $toDate . "'";
		}
		if ($arr['bkg_agent_id'] != '' && $arr['bkg_agent_id'] != '0')
		{
			$where		 .= "  AND bkg_agent_id=" . $arr['bkg_agent_id'];
			$agtwhere	 = "  AND bkg_agent_id=" . $arr['bkg_agent_id'];
		}
		else if ($arr['bkg_agent_id'] == '0')
		{
			$where		 .= "  AND bkg_agent_id IS NULL";
			$agtwhere	 = "  AND bkg_agent_id IS NULL";
		}

		$sql = "SELECT  DATE_FORMAT(bkg_pickup_date,'%d-%m-%Y') date,bkg_pickup_date,
								GROUP_CONCAT(DISTINCT IF(apt.apt_id IS NULL AND btl.btl_bkg_id IS NULL, bkg_id, NULL) SEPARATOR ', ') as not_loggedin,
								GROUP_CONCAT(DISTINCT IF(apt.apt_id IS NOT NULL AND btl.btl_bkg_id IS NULL, bkg_id, NULL) SEPARATOR ', ') as not_left,
								GROUP_CONCAT(DISTINCT IF(btl.btl_bkg_id IS NOT NULL AND bkg_trip_arrive_time IS NULL, bkg_id, NULL) SEPARATOR ', ') as not_arrived,
								GROUP_CONCAT(DISTINCT IF(bkg_trip_arrive_time IS NOT NULL AND bkg_trip_start_time IS NULL , bkg_id, NULL) SEPARATOR ', ') as not_started,
								GROUP_CONCAT(DISTINCT IF(bkg_trip_start_time IS NOT NULL AND bkg_trip_end_time IS NULL, bkg_id, NULL) SEPARATOR ', ') as not_ended,
								GROUP_CONCAT(DISTINCT IF(bkg_trip_arrive_time IS NOT NULL AND a1.aat_id IS NULL, bkg_id, null) SEPARATOR ', ') as ArriveAPIFail,
								GROUP_CONCAT(DISTINCT IF(a1.aat_id IS NOT NULL AND bkg_trip_start_time IS NOT NULL AND a2.aat_id IS NULL, bkg_id, null) SEPARATOR ', ') as StartAPIFail,
								GROUP_CONCAT(DISTINCT IF(bkg_trip_end_time IS NOT NULL AND a2.aat_id IS NOT NULL AND a3.aat_id IS NULL, bkg_id, null) SEPARATOR ', ') as EndAPIFail,
								CONCAT(DATE_FORMAT(MIN(bkg_pickup_date),'%d-%m-%Y'), ' - ', DATE_FORMAT(MAX(bkg_pickup_date),'%d-%m-%Y')) as date_range,
								COUNT(DISTINCT bkg_id) booking_count,
								COUNT(DISTINCT IF(apt.apt_id IS NULL, bkg_id, NULL)) as not_loggedin_count,
								COUNT(DISTINCT IF(btl.btl_bkg_id IS NULL, bkg_id, NULL)) as left_count,
								COUNT(DISTINCT IF(bkg_trip_arrive_time IS NULL, bkg_id, NULL)) as arrived_count,
								COUNT(DISTINCT IF(bkg_trip_start_time IS NULL, bkg_id, NULL)) as start_count,
								COUNT(DISTINCT IF(bkg_trip_end_time IS NULL, bkg_id, NULL)) as end_count,
								ROUND(COUNT(DISTINCT IF(bkg_trip_arrive_time IS NULL, bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg_id),0) as arrived_percent,
								ROUND(COUNT(DISTINCT IF(bkg_trip_start_time IS NULL, bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg_id),0) as start_percent,
								ROUND(COUNT(DISTINCT IF(bkg_trip_end_time IS NULL, bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg_id),0) as end_percent,
								ROUND(COUNT(DISTINCT IF(a1.aat_id IS NULL AND bkg_agent_id IN (18190), bkg_id, NULL)) * 100 / COUNT(DISTINCT IF(bkg_agent_id IN (18190), bkg_id, NULL)),0) as arrived_api_percent,
								ROUND(COUNT(DISTINCT IF(a2.aat_id IS NULL AND bkg_agent_id IN (18190), bkg_id, NULL)) * 100 / COUNT(DISTINCT IF(bkg_agent_id IN (18190), bkg_id, NULL)),0) as start_api_percent,
								ROUND(COUNT(DISTINCT IF(a3.aat_id IS NULL AND bkg_agent_id IN (18190), bkg_id, NULL)) * 100 / COUNT(DISTINCT IF(bkg_agent_id IN (18190), bkg_id, NULL)),0) as end_api_percent
							  FROM booking 
								INNER JOIN booking_track btk ON bkg_id = btk_bkg_id
								INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
								LEFT JOIN app_tokens apt ON apt.apt_entity_id=bcb_driver_id AND apt_last_login>DATE_SUB(bkg_pickup_date, INTERVAL 2 HOUR) AND apt_status=1
								LEFT JOIN booking_track_log btl ON bkg_id = btl.btl_bkg_id AND btl.btl_event_type_id=201
								LEFT JOIN agent_api_tracking a4 ON bkg_id = a4.aat_booking_id AND a4.aat_type = 15 AND a4.aat_status=1
								LEFT JOIN agent_api_tracking a1 ON bkg_id = a1.aat_booking_id AND a1.aat_type = 18 AND a1.aat_status=1
								LEFT JOIN agent_api_tracking a2 ON bkg_id = a2.aat_booking_id AND a2.aat_type = 12 AND a2.aat_status=1
								LEFT JOIN agent_api_tracking a3 ON bkg_id = a3.aat_booking_id AND a3.aat_type = 13 AND a3.aat_status=1
							WHERE bkg_status IN (5,6,7)  AND bkg_vehicle_type_id NOT IN (5,6,75) " . $where . "
							GROUP BY date 
							";
		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes' => ['date', 'date_range'], 'defaultOrder' => 'bkg_pickup_date DESC'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			$orderBy = " ORDER BY bkg_pickup_date DESC ";
			return DBUtil::query($sql . $orderBy, DBUtil::SDB());
		}
	}

	public static function checkDrvAvialability($tripId, $driverId)
	{
		$cabmodel				 = BookingCab::model()->findByPk($tripId);
		$cabmodel->bcb_driver_id = $driverId;

		$overLap = $cabmodel->checkDriverActiveTripTiming();
		return $overLap;
	}

	public static function getExpairedDocuments()
	{
		$afterOneMonth	 = "DATE_ADD(NOW(), INTERVAL 30 DAY)";
		$beforeTenDays	 = "DATE_SUB(NOW(), INTERVAL 11 DAY)";
		$sql			 = "SELECT drv.drv_id,ctt.ctt_first_name, ctt.ctt_last_name, ctt.ctt_license_exp_date 
				FROM drivers drv
				INNER JOIN contact_profile cop ON cop.cr_is_driver=drv.drv_id AND cop.cr_status=1 
				INNER JOIN contact ctt ON ctt.ctt_id=cop.cr_contact_id AND ctt.ctt_active = 1
				INNER JOIN document doc ON ctt.ctt_license_doc_id=doc.doc_id  AND doc.doc_status = 1
				WHERE (ctt.ctt_license_exp_date BETWEEN $beforeTenDays AND $afterOneMonth) AND drv.drv_approved = 1";

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
		$sql		 = "SELECT drv.drv_id,ctt.ctt_first_name, ctt.ctt_last_name, ctt.ctt_license_exp_date 
				FROM drivers drv
				INNER JOIN contact_profile cop ON cop.cr_is_driver=drv.drv_id AND cop.cr_status=1 
				INNER JOIN contact ctt ON ctt.ctt_id=cop.cr_contact_id AND ctt.ctt_active = 1
				INNER JOIN document doc ON ctt.ctt_license_doc_id=doc.doc_id  AND doc.doc_status = 1
				WHERE (ctt.ctt_license_exp_date BETWEEN $today AND $afterTenDay) AND drv.drv_approved = 1";

		$results = DBUtil::query($sql, DBUtil::SDB());
		if ($results)
		{
			self::expiredDocNotification($results, 10);
		}
	}

	public static function expiredDocNotification($driverData, $days = 0)
	{
		foreach ($driverData as $driver)
		{
			$currentDate		 = date("Y-m-d", strtotime(date('Y-m-d')));
			$licenseDate		 = date("Y-m-d", strtotime($driver['ctt_license_exp_date']));
			$licenseDateFromat	 = date("d/M/Y", strtotime($driver['ctt_license_exp_date']));
			$msg				 = ($currentDate > $licenseDate) ? 'expired on' : 'expires on';
			$msg2				 = "License $msg $licenseDateFromat";
			$message			 = $driver['ctt_first_name'] . " " . $driver['ctt_last_name'] . " your $msg2. Please upload latest documents to prevent driver from being frozen.";
			$payLoadData		 = ['EventCode' => Document::Document_Driver_Expired];
			$success			 = AppTokens::model()->notifyDriver($driver['drv_id'], $payLoadData, "", $message, "", "Driver Documents expiring in $days days.");
		}
	}

	/**
	 * 
	 * @param type $name
	 * @return type
	 */
	public static function getByName($name)
	{
		$sql = "SELECT d1.drv_id,d1.drv_name,ctt_first_name,ctt_last_name,ctt_business_name
				FROM `drivers` d1 
				INNER JOIN contact_profile ON contact_profile.cr_is_driver = d1.drv_id AND cr_status=1 AND d1.drv_id = d1.drv_ref_code
				INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1 
				WHERE 1 AND (d1.drv_name LIKE '%$name%' || d1.drv_ref_code LIKE '%$name%' || 
				contact.ctt_first_name LIKE '%$name%' || 
				contact.ctt_last_name LIKE '%$name%')";

		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param type $id
	 * @return type
	 */
	public static function getByDriverId($id)
	{
		$sql = "SELECT d1.drv_id,d1.drv_name,ctt_first_name,ctt_last_name,ctt_business_name
				FROM `drivers` d1 
				INNER JOIN contact_profile ON contact_profile.cr_is_driver = d1.drv_id AND cr_status=1 AND d1.drv_id = d1.drv_ref_code
				INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1 
				WHERE 1 AND d1.drv_id = $id AND d1.drv_active = 1";

		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public static function getHighRatingFreezeDriverList()
	{
		$sql = "SELECT 
				DISTINCT drv_ref_code
				FROM  drivers 
				INNER JOIN `booking_cab` ON booking_cab.bcb_driver_id = drv_id 
				WHERE 1 
				AND booking_cab.bcb_active = 1 
				AND drv_id = drv_ref_code 
				AND  drv_overall_rating >=4
				AND drv_is_freeze =1";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param type $drvId
	 * @param type $cttId
	 * @return type
	 */
	public static function getFullDetails($drvId, $cttId)
	{
		$data			 = [];
		$data['details'] = Drivers::getDetailsById($drvId);
		$data['person']	 = Contact::getByPerson($cttId);
		$data['docs']	 = Document::getDocModels($cttId);
		return $data;
	}

	/**
	 * @param Contact $contactModel 
	 */
	public static function processContact($contactModel)
	{
		$transaction = null;
		try
		{

			$phone	 = null;
			$email	 = '';
			if ($contactModel->contactPhones && count($contactModel->contactPhones) > 0)
			{
				$phone = $contactModel->contactPhones[0]->phn_phone_no;
			}
			if ($contactModel->contactEmails && count($contactModel->contactEmails) > 0)
			{
				$email = $contactModel->contactEmails[0]->eml_email_address;
			}

			$contactId	 = Contact::getByLicenseAndPhone($contactModel->ctt_license_no, $phone);
			$arrCnt		 = [];
			if (!$contactId)
			{
				$licCttId	 = Contact::getContactIdByLicense($contactModel->ctt_license_no);
				$phCttIds	 = null;
				if ($phone)
				{
					$phCttIds = ContactPhone::getData($phone, false);
				}

				$emlCttIds = null;
				if ($email)
				{
					$emlCttIds = ContactEmail::getData($email, false);
				}
				if ($phCttIds && (!$licCttId || !in_array($licCttId, explode(',', $phCttIds))))
				{
					$contactModel->addError("ctt_id", "This phone number is already registered with other account");
				}

				if ($emlCttIds && (!$licCttId || !in_array($licCttId, explode(',', $emlCttIds))))
				{
					$contactModel->addError("ctt_id", "This email is already registered with other account");
				}

				if (strlen($contactModel->ctt_license_no) < 12)
				{
					$contactModel->addError("ctt_id", "Your license was not valid");
				}

				if ($contactModel->hasErrors())
				{
					throw new Exception(json_encode($contactModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}

				$transaction = DBUtil::beginTransaction();
				if (!$licCttId)
				{
					$contModel				 = new Contact();
					$arrCnt					 = $contactModel->attributes;
					$contModel->attributes	 = array_filter((array) $arrCnt);
					if (!$contModel->save())
					{
						throw new Exception(json_encode($contModel->getErrors()), ReturnSet::ERROR_VALIDATION);
					}

					$contactId	 = $contModel->ctt_id;
					$state		 = ContactLog::CONTACT_CREATED;
					$desc		 = "Contact created through driver registration";
					ContactLog::model()->createLog($contactId, $desc, $state, null);
					Contact::model()->updateRefCode($contactId, $contactId);
				}
				else
				{
					$model	 = Contact::model()->findByPk($licCttId);
					$oldName = $model->ctt_first_name . $model->ctt_last_name;
					$newName = $contactModel->ctt_first_name . $contactModel->ctt_last_name;
					if ($oldName != $newName)
					{
						$contactModel->addError("ctt_id", "Name entered does not match with our records. Please enter name properly or contact Gozo support.");
						throw new Exception(json_encode($contactModel->getErrors()), ReturnSet::ERROR_VALIDATION);
					}
					else
					{
						$contactId = $licCttId;
					}
				}
			}
			else
			{
				$contactModel->addError("ctt_id", "Driver Already exist");
				throw new Exception(json_encode($contactModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$rsEmail = ContactEmail::saveEmails($contactModel->contactEmails, $contactId);
			$rsPhone = ContactPhone::savePhones($contactModel->contactPhones, $contactId);
			if (!$rsPhone->getStatus())
			{
				$contactModel->addError("ctt_id", "This phone number is already registered with this account or number is not valid");
				throw new Exception(json_encode($contactModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}


			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			throw $exc;
		}

		return $contactId;
	}

	public static function getTokenGroupListByIds($includeDrivers = '', $hourDuration = 60)
	{
		$params	 = [];
		$where	 = '';
		if ($includeDrivers != '')
		{
			$where		 = " AND drv2.drv_id IN($includeDrivers)";
			$whereAPt	 = " AND apt.apt_entity_id IN($includeDrivers)";
		}
		else
		{
			$where		 = " AND 1=2";
			$whereAPt	 = " AND 1=2";
		}
		$sql = "SELECT	GROUP_CONCAT(DISTINCT drv_id) drvIds, count(DISTINCT drv_id) cntDrv,
					GROUP_CONCAT(DISTINCT apt_device_token) aptTokens, sum(cntToken1) cntToken 
					FROM (
					SELECT DISTINCT drv2.drv_id, 
						GROUP_CONCAT( DISTINCT apt_device_token) apt_device_token ,
							count(DISTINCT apt_device_token) cntToken1 
						FROM drivers drv1
						INNER JOIN drivers drv2 ON drv2.drv_id = drv1.drv_ref_code
						INNER JOIN (
							SELECT apt_device_token,apt_entity_id ,apt_user_type
								FROM  `app_tokens` apt  
								WHERE apt.apt_user_type=5 AND apt.apt_status = 1 
								AND apt.apt_entity_id>0 AND apt.apt_device_token IS NOT NULL  
								AND apt.apt_last_login >= DATE_SUB(NOW(),INTERVAL $hourDuration HOUR)
								$whereAPt
								ORDER BY apt.apt_last_login DESC  
						)apt ON apt.apt_entity_id = drv2.drv_id  	
						INNER JOIN contact_profile cpr ON cpr.cr_is_driver = drv2.drv_id 			 
						WHERE  drv2.drv_active = 1 AND drv2.drv_id = drv2.drv_ref_code  		
							$where   						 
							AND apt.apt_entity_id>0 AND apt.apt_device_token IS NOT NULL
						GROUP BY drv2.drv_id
					  LIMIT 0, 300)a";

		$data = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public static function getListByZoneIds($zoneList, $excludeDriver = '')
	{
		$params	 = ['zoneList' => $zoneList];
		$where	 = '';
		if ($excludeDriver != '')
		{
			$params['excludeDriver'] = $excludeDriver;

			$where = " AND d2.drv_id NOT IN(:excludeDriver)";
		}
		$sql	 = "SELECT GROUP_CONCAT(DISTINCT d2.drv_id) 
					FROM drivers d1
					INNER JOIN drivers d2 ON d2.drv_id = d1.drv_ref_code 			 
					WHERE d2.drv_active = 1 AND d2.drv_id = d2.drv_ref_code  
							AND (FIND_IN_SET(d2.drv_home_zone , :zoneList))
							$where 	 ";
		$data	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public static function getDefaultByContact($cttId)
	{
		//	$cttdata = \ContactProfile::getCodeByCttId($cttId);

		$cttdata = \ContactProfile::getPrimaryEntitiesByContact($cttId);
		$drvId	 = $cttdata['cr_is_driver'];
		$data	 = ($drvId > 0) ? \Drivers::getDetailsById($drvId) : false;
//		$data	 = \Drivers::getDetailsById($drvId);
		return $data;
	}

	/**
	 * 
	 * @param type $drvid
	 * @return type
	 */
	public static function getStatusDetailbyid($drvid)
	{
		$param	 = ['drvid' => $drvid];
		$sql	 = "SELECT 
					d.drv_id as drv_id,
					d.drv_name as drv_name,
					phn_phone_no AS drv_phone,
					eml_email_address AS drv_email,
					d.drv_code as drv_code,
					d.drv_approved as drv_approved, d.drv_is_freeze as drv_is_freeze
					FROM  drivers d1
                    INNER JOIN drivers as d on d.drv_id = d1.drv_ref_code AND d.drv_id = d.drv_ref_code 
                    INNER JOIN contact_profile as cp on cp.cr_is_driver = d.drv_id and cp.cr_status = 1
                    INNER JOIN contact as ctt on ctt.ctt_id = cp.cr_contact_id and ctt.ctt_active =1 and ctt.ctt_id = ctt.ctt_ref_code
					LEFT JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id 	AND phn.phn_is_primary = 1 AND phn.phn_active = 1
					LEFT JOIN contact_email eml ON eml.eml_contact_id = ctt.ctt_id 	AND eml.eml_is_primary = 1 AND eml.eml_active = 1
					WHERE  d.drv_active = 1 AND  d1.drv_id = :drvid";
		$result	 = DBUtil::queryRow($sql, DBUtil::MDB(), $param);
		return $result;
	}

	public static function checkDrvStatus($drvid)
	{
		$param	 = ['drvid' => $drvid];
		$sql	 = "SELECT 
					d.drv_approved as drv_approved, d.drv_is_freeze as drv_is_freeze,ctt.ctt_license_doc_id as licenseDoc
					FROM  drivers d1
                    INNER JOIN drivers as d on d.drv_id = d1.drv_ref_code AND d.drv_id = d.drv_ref_code 
                    INNER JOIN contact_profile as cp on cp.cr_is_driver = d.drv_id and cp.cr_status = 1
                    INNER JOIN contact as ctt on ctt.ctt_id = cp.cr_contact_id and ctt.ctt_active =1 and ctt.ctt_id = ctt.ctt_ref_code
					WHERE  d.drv_active = 1 AND  d1.drv_id = :drvid";
		$result	 = DBUtil::queryRow($sql, DBUtil::MDB(), $param);
		return $result;
	}

	/**
	 * 
	 * @param type $chauffeurData
	 * @param type $operatorId
	 * @return boolean
	 */
	public function addHornok($chauffeurData, $operatorId, $jsonObj)
	{
		//add driver code start
		/* check operator refid exist or not */
		$returnSet			 = new ReturnSet();
		$checkOperatorRefId	 = self::checkOperatorRefId($jsonObj->data->driver->id);
		if ($checkOperatorRefId['drvCount'] > 0)
		{
			$drvData = ['driverId' => $checkOperatorRefId['drv_id']];
		}
		else
		{
			$contactRecord = ContactPhone::getByPhone($jsonObj->data->driver->phone[0]['isdCode'] . $jsonObj->data->driver->phone[0]['number'], '', '', '', 'limit 1');
			if ($contactRecord->getRowCount() > 0)  // && isset($contactRecord['driverId']
			{
				/**
				 * existing phone linked with driver id
				 */
				foreach ($contactRecord as $contactPhone)
				{
					$drvContact = $contactPhone;
				}
				$driverId							 = $drvContact['driverId'];
				$driverModel						 = Drivers::model()->findByPk($driverId);
				$driverModel->drv_operator_ref_id	 = $jsonObj->data->driver->id;
				if ($driverModel->save())
				{
					$vndDrvData	 = ['vendor' => $operatorId, 'driver' => $driverId];
					$resLinked	 = VendorDriver::model()->checkAndSave($vndDrvData);
					if ($resLinked == true)
					{
						$drvData = ['driverId' => $driverId];
					}
				}
			}
			else
			{
				/* add drivers */
				$jsonMapper	 = new JsonMapper();
				$stub		 = new Stub\common\Person();
				$obj		 = $jsonMapper->map($chauffeurData, $stub);

				/** @var Stub\common\Person $obj */
				$contactModel	 = $obj->init();
				$returnSet		 = Drivers::addByContact($contactModel, null);
				$getData		 = $returnSet->getData();
				if ($getData->isExists == 0)
				{
					$driverId							 = $getData->id;
					$driverModel						 = Drivers::model()->findByPk($getData->id);
					$driverModel->drv_operator_ref_id	 = $chauffeurData->operatorDrvId;
					if ($driverModel->save())
					{
						$vndDrvData	 = ['vendor' => $operatorId, 'driver' => $driverId];
						$resLinked	 = VendorDriver::model()->checkAndSave($vndDrvData);
						if ($resLinked == true)
						{
							$drvData = ['driverId' => $driverId];
						}
					}
				}
			}
		}
		return $drvData;
	}

	/**
	 * 
	 * @param type $refId
	 * @return type drivers count Int
	 */
	public static function checkOperatorRefId($refId)
	{
		$params	 = ['drvOperatorId' => $refId];
		$sql	 = "SELECT COUNT(drv_id) drvCount, drv_id FROM drivers WHERE drv_operator_ref_id= :drvOperatorId AND drv_active = 1";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $result;
	}

	/**
	 * 
	 * @param type $jsonObj
	 * @return type
	 * @throws Exception
	 */
	public function addOperator($jsonObj, $vndId)
	{
		$jsonMapper			 = new JsonMapper();
		/** @var OperatorDriver $checkOperatorRefId */
		$checkOperatorRefId	 = OperatorDriver::checkOperatorRefId($jsonObj->driver->id);
		if ($checkOperatorRefId['drvCount'] > 0)
		{
			$drvData = ['driverId' => $checkOperatorRefId['ord_drv_id']];
			goto skipNewDriver;
		}


		$objReg	 = $jsonMapper->map($jsonObj->driver, new \Beans\contact\Register());
		$phoneNo = "+" . $objReg->phone[0]->isdCode . $objReg->phone[0]->number;

		$cttId = Contact::getByEmailPhone($objReg->email[0]->address, $phoneNo);
		if (!$cttId)
		{
			/** @var \Beans\contact\Register $objReg */
			$cttModel	 = $objReg->getContactModel();
			$transaction = DBUtil::beginTransaction();

			$returnSet	 = $cttModel->create(true, UserInfo::TYPE_DRIVER);
			$cttId		 = $cttModel->ctt_id;
			if (!$returnSet->isSuccess())
			{
				throw new Exception("Sorry, unable to create your accounts", ReturnSet::ERROR_FAILED);
			}
		}
		$contactData = \ContactProfile::getCodeByCttId($cttId);
		if ($contactData && $contactData['cr_is_driver'] > 0)
		{
			$drvId	 = $contactData['cr_is_driver'];
			$drvData = ['driverId' => $drvId];
			goto skipNewDriver;
		}
		$userModel = Users::createbyContact($cttId);
		DBUtil::commitTransaction($transaction);

		$driverName	 = $jsonObj->driver->firstName . " " . $jsonObj->driver->lastName;
		$returnSet	 = Drivers::addDriverDetails($cttId, $driverName);
		if ($returnSet->getStatus())
		{

			$drvId = $returnSet->getData();

			$operatorDriverArr	 = ['operatorId' => $vndId, 'driverId' => $drvId, 'driverContactId' => $cttId, 'operatorDriverId' => $jsonObj->driver->refId];
			/** @var OperatorDriver $resLink */
			$resLink			 = OperatorDriver::model()->checkAndSave($operatorDriverArr);

			ContactProfile::updateEntity($cttId, $drvId, UserInfo::TYPE_DRIVER);
			$dataArr	 = ['vendor' => $vndId, 'driver' => $drvId];
			$resLinked	 = VendorDriver::model()->checkAndSave($dataArr);
			$drvData	 = ['driverId' => $drvId];
		}
		else
		{
			if ($returnSet->getMessage() != '')
			{
				throw new Exception($returnSet->getMessage(), ReturnSet::ERROR_VALIDATION);
			}
		}
		skipNewDriver:
		return $drvData;
	}

	/**
	 * 
	 * @return boolean
	 */
	public function skipLicesnse()
	{
		if ($this->drvContact->ctt_license_no == '')
		{
			return true;
		}
	}

	/**
	 * 
	 * @param type $pickuptime
	 * @param type $retuntime
	 */
	public static function helplineDataShow($pickuptime, $retuntime)
	{

		$showNumber	 = 0;
		$startTime	 = strtotime("-30 minutes", strtotime($pickuptime));
		//$startTime = date('Y-m-d h:i:s', $startTime);
		$endTime	 = strtotime("+90 minutes", strtotime($retuntime));
		//$endTime = date('Y-m-d h:i:s', $endTime);
		$currentDate = strtotime(date('Y-m-d H:i:s'));
		//echo $cTime = date('Y-m-d H:i:s', $currentDate);
		if ($currentDate > $startTime && $currentDate < $endTime)
		{
			$showNumber = 1;
		}
		return $showNumber;
	}

	/**
	 * This function is used to send notifications  for the event Cab/Driver Assigned
	 * @param string $bkgId
	 * @return boolean
	 */
	public static function notifyDriverDetailsToCustomer($bkgId, $isSchedule = 0, $schedulePlatform = null, $data = array(), $userType = null)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		Logger::trace("notifyDriverDetailsToCustomer bkgId: $bkgId");
		$bkgModel = Booking::model()->findByPk($bkgId);
		if ($bkgId == '' || !$bkgModel->bkgBcb->bcb_driver_id)
		{
			goto skipAll;
		}
		$drvId			 = $bkgModel->bkgBcb->bcb_driver_id;
		$drvContactId	 = ContactProfile::getByEntityId($drvId, UserInfo::TYPE_DRIVER);
		$drvContact		 = ContactPhone::getContactPhoneById($drvContactId);
		Filter::parsePhoneNumber($drvContact, $drvCode, $drvNumber);
		$datePickupDate	 = new DateTime($bkgModel->bkg_pickup_date);
		$pickupTime		 = $datePickupDate->format('j/M/y h:i A');
		$buttonUrl		 = ltrim(BookingUser::getPaymentLinkByPhone($bkgModel->bkg_id), '/');
		$paymentUrl		 = 'https://www.gozocabs.com/' . $buttonUrl;
		$createTimeDiff	 = Filter::getTimeDiff($bkgModel->bkg_pickup_date, date('Y-m-d H:i:s'));
		$link			 = 'https://www.gozocabs.com' . BookingUser::getPaymentLinkByPhone($bkgModel->bkg_id);
		$phoneNo		 = $bkgModel->bkgUserInfo->bkg_country_code . $bkgModel->bkgUserInfo->bkg_contact_no;
		if ($phoneNo == '' || !$phoneNo)
		{
			goto skipAll;
		}
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		if (!Filter::processPhoneNumber($number, $code) && $userType == null)
		{
			goto skipAll;
		}
		$username = trim($bkgModel->bkgUserInfo->bkg_user_fname) != "" && $bkgModel->bkgUserInfo->bkg_user_fname != null ? $bkgModel->bkgUserInfo->bkg_user_fname : " Customer ";
		if ($userType == UserInfo::TYPE_CONSUMER && !empty($data))
		{
			$username = trim($bkgModel->bkgUserInfo->bkg_user_fname) != "" && $bkgModel->bkgUserInfo->bkg_user_fname != null ? $bkgModel->bkgUserInfo->bkg_user_fname : " Customer ";
		}
		else if ($userType == UserInfo::TYPE_AGENT && !empty($data))
		{
			$username = "Agent";
		}
		else if ($userType == UserInfo::TYPE_ADMIN && !empty($data))
		{
			$username = "Admin";
		}
		$contentParams	 = array(
			'userName'		 => $username,
			'bookingId'		 => Filter::formatBookingId($bkgModel->bkg_booking_id),
			'cabType'		 => $bkgModel->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label,
			'cabNumber'		 => $bkgModel->bkgBcb->bcb_cab_number,
			'driverName'	 => $bkgModel->bkgBcb->bcbDriver->drv_name,
			'driverPhone'	 => $drvCode . $drvNumber,
			'pickupTime'	 => $pickupTime,
			'fromCityName'	 => $bkgModel->bkgFromCity->cty_name,
			'paymentUrl'	 => $paymentUrl,
			'newLink'		 => str_replace("https://", "", Filter::shortUrl($link)),
			'inHour'		 => floor($createTimeDiff / 60) . ' ' . 'hours',
			'primaryId'		 => $bkgId,
			'extraData'		 => $data,
			'eventId'		 => "2",
			'skipPermission' => ($bkgModel->bkg_agent_id == Config::get('transferz.partner.id')) ? true : false
		);
		$senderUserId	 = $bkgModel->bkgUserInfo->bkg_user_id;
		if ($userType == UserInfo::TYPE_CONSUMER && $userType != null)
		{
			$senderUserId = $bkgModel->bkgUserInfo->bkg_user_id;
		}
		else if ($userType != null)
		{
			$senderUserId = null;
		}
		$senderType			 = $userType != null ? $userType : UserInfo::TYPE_CONSUMER;
		$receiverParams		 = EventReceiver::setData($senderType, $senderUserId, WhatsappLog::REF_TYPE_BOOKING, $bkgId, $bkgModel->bkg_booking_id, $code, $number, null, 0, null, SmsLog::SMS_CUSTOMER_BEFORE_PICKUP);
		$eventScheduleParams = EventSchedule::setData($bkgId, ScheduleEvent::BOOKING_REF_TYPE, ScheduleEvent::BOOKING_DRIVER_TO_CUSTOMER, "Driver details to customer notification", $isSchedule, CJSON::encode(array('bkgId' => $bkgId)), 10, $schedulePlatform);
		MessageEventMaster::processPlatformSequences(2, $contentParams, $receiverParams, $eventScheduleParams);
		skipAll:
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	/**
	 * 
	 * @param integer $drvId
	 * @param integer $isSchedule
	 * @param integer $schedulePlatform
	 * @return boolean
	 */
	public static function notifyToAlreadyRegistered($drvId, $isSchedule = 0, $schedulePlatform = null)
	{
		$success	 = false;
		$drvModel	 = Drivers::model()->findById($drvId);
		if (!$drvModel)
		{
			goto skipAll;
		}
		$driverName	 = $drvModel->drv_name;
		$contactId	 = ContactProfile::getByEntityId($drvId, UserInfo::TYPE_DRIVER);
		$row		 = ContactPhone::getNumber($contactId);
		if (!$row || empty($row))
		{
			goto skipAll;
		}
		if ($driverName == "" && $driverName == null)
		{
			$contactModel	 = Contact::model()->findByPk($contactId);
			$driverName		 = $contactModel->getName();
		}
		$contentParams		 = array('name' => $driverName, 'eventId' => "6");
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_DRIVER, $drvId, null, null, null, $row['code'], $row['number'], null, 0, null, SmsLog::Driver);
		$eventScheduleParams = EventSchedule::setData($drvId, ScheduleEvent::DRIVER_REF_TYPE, ScheduleEvent::DRIVER_ALREADY_REGISTRED, "Already registered", $isSchedule, CJSON::encode(array('drvId' => $drvId)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(6, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 2)
			{
				$success = true;
				DriversLog::model()->createLog($drvId, "Driver is Created.", UserInfo::getInstance(), DriversLog::DRIVER_CREATED);
			}
		}
		skipAll:
		return $success;
	}

	/**
	 * @param integer $drvId
	 * @param integer $isSchedule
	 * @param integer $schedulePlatform
	 * @return boolean
	 */
	public static function notifyToDriverCompleteRegistrationReminder($drvId, $isSchedule = 0, $schedulePlatform = null)
	{
		$success	 = false;
		$drvModel	 = Drivers::model()->findById($drvId);
		if (!$drvModel)
		{
			goto skipAll;
		}
		$driverName	 = $drvModel->drv_name;
		$contactId	 = ContactProfile::getByEntityId($drvId, UserInfo::TYPE_DRIVER);
		$row		 = ContactPhone::getNumber($contactId);
		if (!$row || empty($row))
		{
			goto skipAll;
		}
		if ($driverName == "" && $driverName == null)
		{
			$contactModel	 = Contact::model()->findByPk($contactId);
			$driverName		 = $contactModel->getName();
		}
		$contentParams		 = array('name' => $driverName, 'eventId' => "7");
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_DRIVER, $drvId, null, null, null, $row['code'], $row['number'], null, 0, null, SmsLog::Driver);
		$eventScheduleParams = EventSchedule::setData($drvId, ScheduleEvent::DRIVER_REF_TYPE, ScheduleEvent::DRIVER_COMPLETE_REGISTRATION_REMINDER, "complete registration reminder for driver", $isSchedule, CJSON::encode(array('drvId' => $drvId)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(7, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 2)
			{
				$success = true;
			}
		}
		skipAll:
		return $success;
	}

	/**
	 * @param integer $bkgId
	 * @param integer $drvId
	 * @param integer $bonusAmount
	 * @param integer $isSchedule
	 * @param integer $schedulePlatform
	 * @return boolean
	 */
	public static function notifyReviewBonusToDriver($bkgId, $drvId, $bonusAmount = 0, $isSchedule = 0, $schedulePlatform = null)
	{
		$success	 = false;
		$drvModel	 = Drivers::model()->findById($drvId);
		if (!$drvModel)
		{
			goto skipAll;
		}
		$modelBkg = Booking::model()->findByPk($bkgId);
		if (!$modelBkg)
		{
			goto skipAll;
		}
		$driverName	 = $drvModel->drv_name;
		$contactId	 = ContactProfile::getByEntityId($drvId, UserInfo::TYPE_DRIVER);
		$row		 = ContactPhone::getNumber($contactId);
		if (!$row || empty($row))
		{
			goto skipAll;
		}
		if ($driverName == "" && $driverName == null)
		{
			$contactModel	 = Contact::model()->findByPk($contactId);
			$driverName		 = $contactModel->getName();
		}
		$contentParams		 = array('eventId' => "10", 'userName' => $driverName, 'bonus' => Filter::moneyFormatter($bonusAmount));
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_DRIVER, $drvId, SmsLog::REF_DRIVER_ID, $drvId, $modelBkg->bkg_booking_id, $row['code'], $row['number'], null, 0, null, SmsLog::SMS_DRIVER_BONUS);
		$eventScheduleParams = EventSchedule::setData($drvId, ScheduleEvent::DRIVER_REF_TYPE, ScheduleEvent::REVIEW_BONUS_DRIVER, "Review bonus to driver", $isSchedule, CJSON::encode(array('drvId' => $drvId)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(10, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success				 = true;
				$params['blg_ref_id']	 = $response['id'];
				BookingLog::model()->createLog($modelBkg->bkg_id, "Sms sent to driver,Bonus Added Rs. $bonusAmount", null, BookingLog::SMS_SENT, false, $params);
			}
			if ($response['success'] && $response['type'] == 2)
			{
				$success				 = true;
				$params['blg_ref_id']	 = $response['id'];
				BookingLog::model()->createLog($modelBkg->bkg_id, "Sms sent to driver,Bonus Added Rs. $bonusAmount", null, BookingLog::SMS_SENT, false, $params);
			}
		}
		skipAll:
		return $success;
	}

	public static function addDetailsInfo($model)
	{
		$success		 = false;
		$model->scenario = 'updateDriverApp';
		$success		 = false;
		if ($model->validate())
		{
			#$contactModel = $model->drvContact;
			$model->save();
			$model->drvContact->isApp	 = true;
			$model->drvContact->addType	 = -1;
			if ($model->drvContact->save())
			{
				//$model->drvContact->contactEmails->save();
				if ($model->save())
				{
					$contactId	 = $model->drvContact->ctt_id;
					//checkemailexist or not
					$emailModel	 = ContactEmail::model()->findContactEmail($contactId);
					if (empty($emailModel))
					{
						$returnSet = ContactEmail::saveEmails($model->drvContact->contactEmails, $contactId);
					}
					else
					{

						$returnSet = ContactEmail::checkModifyEmails($model->drvContact->contactEmails[0]->eml_email_address, $contactId);
					}
					//$phoneModel=ContactPhone::model()->findContactPhone($contactId);
					$phone = ContactPhone::getByVerifiedContactId($contactId);
					if (!$phone)
					{
						$returnSet = ContactPhone::savePhones($model->drvContact->contactPhones, $contactId);
					}
				}
			}
		}
		return $returnSet;
	}

	public static function modifyDetailsInfo($contactModel, $data)
	{
		$success		 = false;
		$driverId		 = $data->driver->id;
		$model			 = \Drivers::model()->findByPk($driverId);
		$model->scenario = 'updateDriverApp';

		if ($model->validate())
		{
			$model->drvContact			 = contact;
			$model->drvContact->isApp	 = true;
			$model->drvContact->addType	 = -1;
			if ($model->drvContact->update())
			{
				$success = true;
			}
		}
		return $success;
	}

	/**
	 * function used to check force dco flag on off
	 * @param type $driverId
	 * @return type
	 */
	public static function checkForceDCO($driverId)
	{
		$forceDco = 0;
		try
		{
			$param = ['drvId' => $driverId];

			$sql		 = "SELECT count(bkg_Id) as cnt FROM booking bkg
				 INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bcb.bcb_driver_id = :drvId
				 WHERE bkg.bkg_status IN (3,5) AND bkg.bkg_pickup_date < date_add(now(),interval +30 minute) AND DATE_ADD(bkg.bkg_pickup_date,interval IFNULL(bkg_trip_duration,300)+60 minute) > NOW()";
			$dataCount	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
			if ($dataCount < 1)
			{
				$forceDco = 1;
			}
		}
		catch (Exception $exc)
		{
			$forceDco = 0;
		}
		return $forceDco;
	}

	public static function getAllLstByVendor($vndId, $search_txt = '', $is_freeze = 0, $approved = 1, $flag = 0)
	{
		$where = '';

		if ($search_txt != '')
		{
			$search_txt = trim($search_txt);

			$where = " AND (d.drv_name  LIKE '%$search_txt%'  OR   
								phn_phone_no LIKE '%$search_txt%'  OR   
								d.drv_code LIKE '%$search_txt%' 
							)";
		}
		$relVndIds = \Vendors::getRelatedIds($vndId);

		$sql = "SELECT GROUP_CONCAT(DISTINCT d1.drv_ref_code) as drvIds
				FROM vendor_driver vd
				INNER JOIN drivers d ON vd.vdrv_drv_id=d.drv_id 
				INNER JOIN drivers d1 ON d.drv_ref_code=d1.drv_id 
				AND d1.drv_ref_code=d1.drv_id AND d1.drv_active=1
				WHERE  vd.vdrv_active=1 AND vd.vdrv_vnd_id IN ({$relVndIds})";

		$driverIds = DBUtil::queryScalar($sql, DBUtil::SDB());

		if ($driverIds != "")
		{
			#echo $driverIds;
			$sql1 = "SELECT DISTINCT d1.drv_id, d.drv_name,d.drv_approved, c1.*, contact_phone.phn_phone_no AS drv_phone,d.drv_code,contact_phone.phn_is_verified AS isPhVerified,
				    vd.vdrv_id,contact_email.eml_email_address AS drv_email,c.ctt_id 
					FROM contact_profile cp
					INNER JOIN drivers d ON cp.cr_is_driver=d.drv_id 						
					INNER JOIN drivers d1 ON d1.drv_id=d.drv_ref_code AND d1.drv_active=1
					INNER JOIN vendor_driver vd ON vd.vdrv_drv_id=d.drv_id 
						AND vd.vdrv_active=1
					INNER JOIN contact c ON c.ctt_id=cp.cr_contact_id
					INNER JOIN contact c1 ON c.ctt_ref_code=c1.ctt_id
					LEFT JOIN contact_phone ON contact_phone.phn_Contact_id = c.ctt_id 
					AND contact_phone.phn_is_primary = 1 AND contact_phone.phn_active = 1
					LEFT JOIN contact_email ON contact_email.eml_contact_id = c.ctt_id 
					AND contact_email.eml_is_primary = 1 AND contact_email.eml_active = 1
					WHERE 1 AND d.drv_id IN ({$driverIds})
					$where
					GROUP BY d1.drv_ref_code";

			$recordSet = DBUtil::query($sql1, DBUtil::SDB());
		}
		return $recordSet;
	}

	public static function updateDcoDriversdata($data, $cttId)
	{

		$success					 = false;
		$driverId					 = $data->drv_id;
		$contactModel				 = contact::model()->findByPk($cttId);
		$contactModel->attributes	 = $data->drvContact;

		if ($contactModel->save())
		{
			$success = true;
		}


		return $success;
		//$returnset->setStatus(true);
	}

	public static function getRelatedByCttIds($relCttIds)
	{
		$sql2 = "SELECT GROUP_CONCAT(DISTINCT CONCAT_WS(',',drv.drv_id,drv.drv_ref_code))
				FROM contact_profile cp 
				INNER JOIN drivers drv ON drv.drv_id = cp.cr_is_driver  
				WHERE cp.cr_contact_id IN ({$relCttIds}) 
					AND cp.cr_status = 1 
					AND cp.cr_is_driver IS NOT NULL";

		$sql = "SELECT GROUP_CONCAT(CONCAT_WS(',',drv.drv_id,drv.drv_ref_code  )) 
				FROM contact_profile cp 
				INNER JOIN drivers drv1 ON drv1.drv_id = cp.cr_is_driver 
				INNER JOIN drivers drv ON drv.drv_ref_code = drv1.drv_ref_code
				WHERE cp.cr_contact_id IN ({$relCttIds}) 
				AND cp.cr_status = 1 
				AND cp.cr_is_driver IS NOT NULL";

		$relDrvIds		 = DBUtil::queryScalar($sql, DBUtil::SDB());
		$relIdArr		 = explode(',', $relDrvIds);
		$distinctRelIds	 = implode(',', array_unique($relIdArr));
		return $distinctRelIds;
	}

	public static function getPrimaryByIds($drvIds, $onlyPrimary = true)
	{
		$where = '';
		if ($onlyPrimary)
		{
			$where = ' AND drv.drv_active IN (1,2)';
		}
		$sql = "SELECT DISTINCT drv.drv_id,drv.drv_ref_code,ctt.ctt_id,ctt.ctt_ref_code, 
					IF(ctt.ctt_id =ctt.ctt_ref_code,1,0) contactWeight	,
					IF(drv.drv_id =drv.drv_ref_code,1,0) selfWeight	,
					IF(drv.drv_approved = 1,1,0) isApproved,drv.drv_approved,
					IF((ctt.ctt_aadhaar_no <> ''  AND ctt.ctt_aadhaar_no IS NOT NULL 
						AND LENGTH(ctt.ctt_aadhaar_no) >=12 ),2,0) hasAdhaar,
					IF((ctt.ctt_voter_no <> ''  AND ctt.ctt_voter_no IS NOT NULL 
						AND LENGTH(ctt.ctt_voter_no) >=8 ),1,0) hasVoter,
					IF((ctt.ctt_license_no <> ''  AND ctt.ctt_license_no IS NOT NULL 
						AND LENGTH(ctt.ctt_license_no) >=8 ),4,-2) hasLicense,
					IF((ctt.ctt_pan_no <> ''  AND ctt.ctt_pan_no IS NOT NULL 
						AND LENGTH(ctt.ctt_pan_no) =10 ),2,0) hasPan,				

					IF(ctt.ctt_license_exp_date > CURRENT_DATE AND doclicence.doc_status =1 
						AND doclicence.doc_id IS NOT NULL ,3,0) hasValidLicense,
					IF(docvoter.doc_status =1 AND docvoter.doc_id IS NOT NULL ,1,0) hasValidVoter,
					IF(docaadhar.doc_status =1 AND docaadhar.doc_id IS NOT NULL ,1,0) hasValidAdhaar,
					IF(docpan.doc_status =1 AND docpan.doc_id IS NOT NULL ,1,0) hasValidPan,
					IF(docpolicever.doc_status =1 AND docpolicever.doc_id IS NOT NULL ,1,0) hasValidPV,					
						
					IF(TRIM(ctt.ctt_bank_account_no)<> '' AND ctt.ctt_bank_account_no IS NOT NULL,1,0) hasBankRef,  						drv_active,
                    CASE
						WHEN  drv_active =1 THEN 6
						WHEN  drv_active =2 THEN 4						 
						ELSE 0
					END as activeRank,
					CASE
						WHEN  phn.phn_is_primary =1 AND  phn.phn_is_verified =1 
							AND  phn.phn_verified_date= max(phn.phn_verified_date) THEN 6
						WHEN  phn.phn_is_primary =1 AND  phn.phn_is_verified =1 THEN 4
						WHEN  phn.phn_is_verified =1 THEN 3
						WHEN  phn.phn_is_primary =1 THEN 2
						WHEN phn.phn_id IS NOT NULL THEN 1
						ELSE 0
					END as phoneRank,
					CASE
						WHEN  eml.eml_is_primary=1 AND eml.eml_is_verified=1 
							AND  eml.eml_verified_date= max(eml.eml_verified_date) THEN 6
						WHEN  eml.eml_is_primary=1 AND eml.eml_is_verified=1 THEN 4
						WHEN  eml.eml_is_verified=1 THEN 3
						WHEN  eml.eml_is_primary=1 THEN 2
						WHEN eml_id IS NOT NULL THEN 1
						ELSE 0
					END as emailRank 
				FROM drivers drv 
				INNER JOIN contact_profile cpr ON cpr.cr_is_driver = drv.drv_id AND cpr.cr_status = 1
				INNER JOIN contact ctt ON ctt.ctt_id = cpr.cr_contact_id AND ctt.ctt_active =1
				LEFT JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id AND phn.phn_active = 1
				LEFT JOIN contact_email eml ON eml.eml_contact_id = ctt.ctt_id AND eml.eml_active = 1
			 
				LEFT JOIN document as docvoter ON ctt.ctt_voter_doc_id = docvoter.doc_id 
					AND docvoter.doc_type = 2 AND docvoter.doc_active = 1 
					AND docvoter.doc_file_front_path IS NOT NULL
				LEFT JOIN document as docaadhar ON ctt.ctt_aadhar_doc_id = docaadhar.doc_id 
					AND docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 
					AND docaadhar.doc_file_front_path IS NOT NULL
				LEFT JOIN document as docpan ON ctt.ctt_pan_doc_id = docpan.doc_id 
					AND docpan.doc_type = 4 AND docpan.doc_active = 1 
					AND docpan.doc_file_front_path IS NOT NULL
				LEFT JOIN document as doclicence ON ctt.ctt_license_doc_id = doclicence.doc_id 
					AND doclicence.doc_type = 5 AND doclicence.doc_active = 1 
					AND doclicence.doc_file_front_path IS NOT NULL
				LEFT JOIN document as docpolicever ON ctt.ctt_police_doc_id = docpolicever.doc_id 
					AND  docpolicever.doc_type = 7 AND docpolicever.doc_active = 1 				
				
				WHERE drv.drv_ref_code IN ({$drvIds}) $where
				  GROUP BY ctt.ctt_id,drv.drv_ref_code,drv.drv_id
				ORDER BY selfWeight DESC, contactWeight DESC,isApproved DESC,activeRank DESC,hasValidLicense DESC,					 
					(hasLicense+hasAdhaar+hasPan+hasVoter+hasBankRef) DESC, 
					(hasValidLicense +hasValidVoter+hasValidAdhaar+hasValidPan+hasValidPV) DESC";
		if ($onlyPrimary)
		{
			$relData = DBUtil::queryRow($sql, DBUtil::SDB());
		}
		else
		{
			$relData = DBUtil::query($sql, DBUtil::SDB());
		}
		return $relData;
	}

	public static function getCttIdsById($drvIds)
	{
		if (trim($drvIds) == '')
		{
			throw new \Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql = "SELECT GROUP_CONCAT(cr_contact_id) FROM contact_profile 
					WHERE cr_is_driver IN ({$drvIds}) AND cr_status =1";
		return \DBUtil::queryScalar($sql, DBUtil::MDB());
	}

	public static function checkDrvStat($drvId)
	{
		$block		 = 0;
		$drvModel	 = Drivers::model()->findByPk($drvId);
		if (!$drvModel || $drvModel->drv_approved != 1)
		{

			$block = 1;
		}
		return $block;
	}

	public static function getByRefIds($drvIds)
	{
		if ($drvIds == '')
		{
			throw new \Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql = "SELECT GROUP_CONCAT(DISTINCT drv_id)
                FROM drivers
                WHERE drv_active>0 AND drv_ref_code IN (
                    SELECT drv_ref_code FROM drivers
                        WHERE drv_id IN ({$drvIds}) AND drv_active>0)";

		return \DBUtil::queryScalar($sql, DBUtil::MDB());
	}

	public static function getRelatedIds($drvId)
	{
		if (empty($drvId))
		{
			return false;
		}
		$vndIds	 = \Drivers::getByRefIds($drvId);
		$cttIds	 = \Drivers::getCttIdsById($vndIds);

		if (empty($cttIds))
		{
			return 0;
		}
		$relCttIds	 = \Contact::getRelatedIds($cttIds);
		$relDrvList	 = \Drivers::getRelatedByCttIds($relCttIds);
		return $relDrvList;
	}

	public static function getPrimaryId($drvId)
	{
		$relDriverList = \Drivers::getRelatedIds($drvId);
		if ($relDriverList)
		{
			$primaryDrv = \Drivers::getPrimaryByIds($relDriverList);
		}
		return $primaryDrv['drv_id'];
	}

	public static function checkUpcomingOngoigTrip($drvID)
	{
		$param	 = ['drvID' => $drvID];
		// $validity  = date('Y-m-d H:i:s', strtotime('+2 hour'));
		$sql	 = " SELECT bkg.bkg_id,bkg.bkg_pickup_date,bkgtrack.btk_last_event as lastEvent
				FROM booking bkg
				INNER JOIN booking_cab  bcb ON bcb.bcb_id = bkg.bkg_bcb_id 
				INNER JOIN booking_track bkgtrack ON bkgtrack.btk_bkg_id= bkg.bkg_id
				WHERE  bcb_driver_id=:drvID AND  bkg.bkg_status IN (5)  
					 AND bcb.bcb_active=1 AND bkg.bkg_active=1 
                     AND ((bkgtrack.btk_last_event IS NOT NULL OR bkg_pickup_date<DATE_ADD(NOW(), INTERVAL 120 MINUTE))
						 AND (bkgtrack.btk_last_event NOT IN (104)  OR bkgtrack.btk_last_event IS NULL ))";

		$row = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return $row;
	}
}
