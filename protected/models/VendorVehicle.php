<?php

/**
 * This is the model class for table "vendor_vehicle".
 *
 * The followings are the available columns in table 'vendor_vehicle':
 * @property integer $vvhc_id
 * @property integer $vvhc_vnd_id
 * @property integer $vvhc_vhc_id
 * @property string $vvhc_reg_no
 * @property string $vvhc_vhc_owner
 * @property string $vvhc_vhc_owner_auth_valid_date
 * @property string $vvhc_digital_ver

 * @property string $vvhc_digital_sign
 * @property string $vvhc_digital_ip
 * @property string $vvhc_digital_undertaking
 * @property string $vvhc_draft_undertaking
 * @property string $vvhc_digital_uuid
 * @property string $vvhc_digital_lat
 * @property string $vvhc_digital_long
 * @property string $vvhc_digital_device_id
 * @property string $vvhc_digital_os
 * @property integer $vvhc_digital_flag
 * @property string $vvhc_digital_date
 * 
 * @property string $vhc_owner_phone
 * @property string $vhc_owner_email
 * @property string $vhc_owner_proof
 * 
 * @property integer $vvhc_digital_is_agree
 * @property integer $vvhc_digital_is_email  
 * @property integer $vvhc_active
 * @property string $vvhc_created
 * @property Vendors $vvhcVnd
 * @property Vehicles $vvhcVhc
 * @property integer $vvhc_lou_approved
 * @property integer $vvhc_lou_approveby
 * @property integer $vvhc_is_lou_required 
 * @property string $vvhc_lou_approve_date
 * @property string $vvhc_lou_expire_date
 * @property string $vvhc_lou_created_date
 * 
 */
class VendorVehicle extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_vehicle';
	}

	public $vhc_number;
	public $vhc_owner_phone;
	public $vhc_owner_email;
	public $vhc_owner_proof;
	public $search;
	public $vvhc_lou_approve_date1;
	public $vvhc_lou_approve_date2;
	public $louStatusType;
	public $lou_status_types = ['0' => 'Default', '1' => 'Approved', '2' => 'Rejected', '3' => 'Pending'];
	public $searchVehicleNumber;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vvhc_vnd_id, vvhc_vhc_id ', 'required'),
			array('vvhc_vnd_id, vvhc_vhc_id ', 'numerical', 'integerOnly' => true),
			array('vvhc_vhc_owner, vvhc_vhc_owner_auth_valid_date, vvhc_digital_ip', 'length', 'max' => 50),
			array('vvhc_digital_sign, vvhc_digital_undertaking, vvhc_draft_undertaking, vvhc_digital_uuid', 'length', 'max' => 200),
			array('vvhc_digital_lat, vvhc_digital_long, vvhc_digital_os', 'length', 'max' => 100),
			array('vvhc_vhc_owner_auth_valid_date', 'required', 'on' => 'update_undertaking,updateUnderTaking'),
			array('vvhc_digital_date,vhc_owner_phone,vhc_owner_email,vhc_owner_proof,vvhc_owner_or_not', 'safe'),
			['vhc_owner_phone, vhc_owner_email', 'validateUpdateUndertaking', 'on' => 'update_undertaking'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vvhc_id, vvhc_vnd_id, vvhc_vhc_id, vvhc_vhc_owner, vvhc_vhc_owner_auth_valid_date, vvhc_digital_sign, vvhc_digital_ip, vvhc_digital_undertaking, vvhc_draft_undertaking, vvhc_digital_uuid, vvhc_digital_lat, vvhc_digital_long, vvhc_digital_device_id, vvhc_digital_os, vvhc_digital_flag, vvhc_digital_date, vvhc_active, vvhc_digital_is_agree, vvhc_digital_is_email, vvhc_created, vhc_number, vhc_owner_email, vhc_owner_phone, vhc_owner_proof, vvhc_lou_approved, vvhc_lou_approveby, vvhc_is_lou_required, vvhc_lou_approve_date, vvhc_lou_expire_date, vvhc_lou_created_date', 'safe', 'on' => 'search'),
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
			'vvhcVnd'	 => array(self::BELONGS_TO, 'Vendors', 'vvhc_vnd_id'),
			'vvhcVhc'	 => array(self::BELONGS_TO, 'Vehicles', 'vvhc_vhc_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vvhc_id'						 => 'Vvhc',
			'vvhc_vnd_id'					 => 'Vnd',
			'vvhc_vhc_id'					 => 'Vhc',
			'vvhc_vhc_owner'				 => 'Vhc Owner',
			'vvhc_vhc_owner_auth_valid_date' => 'Owner Auth Valid Date',
			'vvhc_digital_ver'				 => 'Digital Ver',
			'vvhc_digital_sign'				 => 'Digital Sign',
			'vvhc_digital_ip'				 => 'Digital Ip',
			'vvhc_digital_undertaking'		 => 'Digital Undertaking',
			'vvhc_draft_undertaking'		 => 'Draft Undertaking',
			'vvhc_digital_uuid'				 => 'Digital Uuid',
			'vvhc_digital_lat'				 => 'Digital Lat',
			'vvhc_digital_long'				 => 'Digital Long',
			'vvhc_digital_device_id'		 => 'Digital Device',
			'vvhc_digital_os'				 => 'Digital Os',
			'vvhc_digital_flag'				 => 'Digital Flag',
			'vvhc_digital_date'				 => 'Digital Date',
			'vvhc_digital_is_agree'			 => 'Digital Is Agree',
			'vvhc_digital_is_email'			 => 'Digital Is Email',
			'vvhc_active'					 => 'Active',
			'vvhc_created'					 => 'Created',
			'vhc_owner_phone'				 => 'Phone',
			'vhc_owner_email'				 => 'Email',
			'vhc_owner_proof'				 => 'Proof',
			'vvhc_lou_approved'				 => 'Lou Approved',
			'vvhc_lou_approveby'			 => 'Lou Approved By',
			'vvhc_lou_approve_date'			 => 'Lou Approved Date',
			'vvhc_lou_expire_date'			 => 'Lou Expire Date',
			'vvhc_lou_created_date'			 => 'Lou Created Date',
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

		$criteria->compare('vvhc_id', $this->vvhc_id);
		$criteria->compare('vvhc_vnd_id', $this->vvhc_vnd_id);
		$criteria->compare('vvhc_vhc_id', $this->vvhc_vhc_id);
		$criteria->compare('vvhc_vhc_owner', $this->vvhc_vhc_owner, true);
		$criteria->compare('vvhc_vhc_owner_auth_valid_date', $this->vvhc_vhc_owner_auth_valid_date, true);
		$criteria->compare('vvhc_digital_sign', $this->vvhc_digital_sign, true);
		$criteria->compare('vvhc_digital_ip', $this->vvhc_digital_ip, true);
		$criteria->compare('vvhc_digital_undertaking', $this->vvhc_digital_undertaking, true);
		$criteria->compare('vvhc_draft_undertaking', $this->vvhc_draft_undertaking, true);
		$criteria->compare('vvhc_digital_uuid', $this->vvhc_digital_uuid, true);
		$criteria->compare('vvhc_digital_lat', $this->vvhc_digital_lat, true);
		$criteria->compare('vvhc_digital_long', $this->vvhc_digital_long, true);
		$criteria->compare('vvhc_digital_os', $this->vvhc_digital_os, true);
		$criteria->compare('vvhc_digital_device_id', $this->vvhc_digital_device_id, true);
		$criteria->compare('vvhc_digital_flag', $this->vvhc_digital_flag);
		$criteria->compare('vvhc_digital_date', $this->vvhc_digital_date, true);
		$criteria->compare('vvhc_digital_is_agree', $this->vvhc_digital_is_agree);
		$criteria->compare('vvhc_digital_is_email', $this->vvhc_digital_is_email);
		$criteria->compare('vvhc_active', $this->vvhc_active);
		$criteria->compare('vvhc_created', $this->vvhc_created, true);
		$criteria->compare('vvhc_lou_approved', $this->vvhc_lou_approved, true);
		$criteria->compare('vvhc_lou_approveby', $this->vvhc_lou_approveby, true);
		$criteria->compare('vvhc_lou_approve_date', $this->vvhc_lou_approve_date, true);
		$criteria->compare('vvhc_lou_expire_date', $this->vvhc_lou_expire_date, true);
		$criteria->compare('vvhc_lou_created_date', $this->vvhc_lou_created_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorVehicle the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function checkExisting($data = [])
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('vvhc_vnd_id', $data['vendor']);
		$criteria->compare('vvhc_vhc_id', $data['vehicle']);
		$exist		 = $this->findAll($criteria);
		if ($exist)
		{
			return true;
		}
		return false;
	}

	public function validateUpdateUndertaking($attribute, $params)
	{
		$success = false;
		$error	 = 0;

		if (($this->vhc_owner_email == "") && ($this->vhc_owner_phone == ""))
		{
			$this->addError($attribute, 'Please provide owner mobile numbers or email.');
			$error++;
		}
		if ($error > 0)
		{
			$success = false;
		}
		$success = true;
		return $success;
	}

	public function checkAndSave($data = [])
	{
		$success = false;
		$exist	 = $this->checkExisting($data);
		if (!$exist)
		{
			$model				 = new VendorVehicle();
			$model->vvhc_vhc_id	 = $data['vehicle'];
			$model->vvhc_vnd_id	 = $data['vendor'];
			if ($data['vhcOwner'] != '')
			{
				$model->vvhc_vhc_owner = $data['vhcOwner'];
			}
			if ($model->validate())
			{
				if ($model->save())
				{
					$success = true;
				}
			}
			else
			{
				$model->getErrors();
			}
		}
		else
		{
			if ($data['vendor'] != '' && $data['vehicle'] != '' && $data['vhcOwner'] != '')
			{
				$vendorVehicle = VendorVehicle::model()->findByVndVhcId($data['vendor'], $data['vehicle']);

				$vendorVehicle->vvhc_vhc_owner = $data['vhcOwner'];
				if ($vendorVehicle->validate())
				{
					if ($vendorVehicle->save())
					{
						$success = true;
					}
				}
				else
				{
					$model->getErrors();
				}
			}
		}
		return $success;
	}

	public function getVendorListbyVehicleid($vhcid)
	{
		$sql		 = "select  v2.vnd_name from vendor_vehicle
				JOIN vendors v1 on vvhc_vnd_id=v1.vnd_id 
				JOIN vendors v2 on v1.vnd_id = v2.vnd_ref_code  
				where vendor_vehicle.vvhc_active=1 AND v2.vnd_id = v2.vnd_ref_code AND vvhc_vhc_id = $vhcid
				GROUP BY v2.vnd_id";
		$recordset	 = DBUtil::queryAll($sql);
		return $recordset;
	}

	public function findByVndVhcId($vndId, $vhcId)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('vvhc_vnd_id', $vndId);
		$criteria->compare('vvhc_vhc_id', $vhcId);
		return $this->find($criteria);
	}

	public function updateSignature($vndId, $vhcId, $digSign = '')
	{
		if ($digSign != '')
		{
			$digDate = DATE('Y-m-d H:i:s');
			$sql	 = "UPDATE `vendor_vehicle` SET vendor_vehicle.vvhc_digital_sign='$digSign',vendor_vehicle.vvhc_digital_date='$digDate'
                    WHERE vendor_vehicle.vvhc_vnd_id=$vndId AND vendor_vehicle.vvhc_vhc_id=$vhcId";
			DBUtil::command($sql);
			return 1;
		}
	}

	public function findUndertakingByVndVhcId($vhcId = 0, $vndId = 0, $vvhcId = 0)
	{
		$params	 = [];
		$sql	 = "SELECT vvhc_vhc_owner_auth_valid_date,vehicles.vhc_owner_contact_id,
				vehicles.vhc_reg_owner,vehicles.vhc_reg_owner_lname,vendors.vnd_name, 
				IF(contact.ctt_user_type=1,IF(contact.ctt_first_name IS NOT NULL AND contact.ctt_last_name IS NOT NULL,CONCAT(contact.ctt_first_name,' ',contact.ctt_last_name),contact.ctt_first_name),contact.ctt_business_name) AS vnd_owner, 
				contact.ctt_user_type as vnd_firm_type, vendors.vnd_firm_pan, 
				vendors.vnd_firm_ccin,vvhc_lou_approve_date,
				contact.ctt_business_name AS vnd_company, 
					vehicles.vhc_number, vehicles.vhc_reg_exp_date,vendor_vehicle.vvhc_lou_s3_data,
				IF((vendor_vehicle.vvhc_vhc_owner IS NOT NULL AND vendor_vehicle.vvhc_vhc_owner <> '') ,vendor_vehicle.vvhc_vhc_owner,CONCAT(vehicles.vhc_reg_owner,' ',vehicles.vhc_reg_owner_lname)) as vhc_owner,
				vendor_vehicle.vvhc_id,
				vendor_vehicle.vvhc_digital_sign,vendor_vehicle.vvhc_lou_s3_data,
					vendor_vehicle.vvhc_digital_flag
				FROM `vendor_vehicle`
				INNER JOIN `vendors` ON vendors.vnd_id=vendor_vehicle.vvhc_vnd_id 
					AND vendors.vnd_id = vendors.vnd_ref_code
				INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id 
					AND cp.cr_status =1
				INNER JOIN contact ON ctt_id =cp.cr_contact_id AND ctt_active =1 
					AND ctt_id = ctt_ref_code
				INNER JOIN `vehicles` ON vehicles.vhc_id=vendor_vehicle.vvhc_vhc_id
				WHERE 1";

		if ($vvhcId > 0)
		{
			$sql				 .= " AND vendor_vehicle.vvhc_id=:vvhcId";
			$params['vvhcId']	 = $vvhcId;
			if ($vhcId > 0)
			{
				$sql			 .= " AND vendor_vehicle.vvhc_vhc_id=:vhcId";
				$params['vhcId'] = $vhcId;
			}
			if ($vndId > 0)
			{
				$sql			 .= " AND vendor_vehicle.vvhc_vnd_id=:vndId";
				$params['vndId'] = $vndId;
			}
		}
		else if ($vhcId == 0 && $vndId == 0)
		{
			$sql .= " AND vehicles.vhc_reg_owner IS NOT NULL LIMIT 0,1";
		}
		else if ($vhcId > 0 && $vndId > 0)
		{
			$sql			 .= " AND vendor_vehicle.vvhc_vnd_id=:vndId AND vendor_vehicle.vvhc_vhc_id=:vhcId ";
			$params['vndId'] = $vndId;
			$params['vhcId'] = $vhcId;
		}
 
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	public function findAllDigitalAgreementCopy()
	{
		$sql = "SELECT vvhc_id, vvhc_vhc_id, vvhc_vnd_id
                FROM `vendor_vehicle`
                    INNER JOIN `vendors` v2 ON v2.vnd_id=vendor_vehicle.vvhc_vnd_id 
                INNER JOIN `vehicles` ON vehicles.vhc_id=vendor_vehicle.vvhc_vhc_id
                WHERE  v2.vnd_id = v2.vnd_ref_code AND vendor_vehicle.vvhc_digital_flag=1
                AND vendor_vehicle.vvhc_digital_is_email=0
                AND (vendor_vehicle.vvhc_digital_undertaking IS NULL OR vendor_vehicle.vvhc_draft_undertaking IS NULL)";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function getVehiclebyVehicleNumber($vhcNumber)
	{
		$sql		 = "select vhc_id from vehicles where vhc_number ='$vhcNumber' AND vhc_active =1 Order By vhc_id DESC limit 1";
		$recordset	 = DBUtil::command($sql)->queryScalar();
		return $recordset;
	}

	/**
	 * 
	 * @param integer $vndId
	 * @return integer
	 */
	public static function getApprovedCarByVndId($vndId)
	{
		$sql = "SELECT IF((cntApprovedCar=cntCar AND cntCar=1),1,2) as is_vendor_type
                FROM 
                (
                    SELECT SUM(IF((vehicles.vhc_id>0 AND vehicles.vhc_approved=1),1,0)) as cntApprovedCar, 
                    COUNT(DISTINCT vehicles.vhc_id) as cntCar
                    FROM `vendors` 
                    INNER JOIN `vendor_vehicle` ON vendor_vehicle.vvhc_vnd_id=vendors.vnd_id 
                    INNER JOIN `vehicles` ON vehicles.vhc_id=vendor_vehicle.vvhc_vhc_id
                    WHERE vendors.vnd_id='$vndId' 
                    AND vehicles.vhc_active=1 
                )a";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar();
	}

	/**
	 * 
	 * @param integer $vndId vendor ID
	 * @return Array List of all vehicles under the vendor
	 */
	public static function getVehicleListByVndId($vndId)
	{
		$params	 = ['vndId' => $vndId];
		$sql	 = "SELECT vehicles.vhc_id,
						vehicles.vhc_number,
						vehicles.vhc_code,
						vehicles.vhc_approved,
						vht_make as vhc_make,
						vht_model as vhc_model,
						vhc_created_at as vhc_created_date,
                        vct.vct_label,IF(vehicle_types.vht_active = 1,'Active','InActive') as vht_active 
					FROM
						`vendor_vehicle`
					INNER JOIN vehicles ON vendor_vehicle.vvhc_vhc_id = vehicles.vhc_id AND vehicles.vhc_active = 1
					INNER JOIN vehicle_types ON vehicles.vhc_type_id = vehicle_types.vht_id AND vehicle_types.vht_active = 1
					INNER JOIN vcv_cat_vhc_type vcv ON vcv.vcv_vht_id = vehicle_types.vht_id
                    INNER JOIN vehicle_category vct ON vct.vct_id = vcv.vcv_vct_id
					WHERE
						vendor_vehicle.vvhc_vnd_id =:vndId AND vendor_vehicle.vvhc_active = 1 GROUP BY vehicles.vhc_id";

		$result = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $result;
	}

	/**
	 * This function is used for adding vehicle vendor mapping
	 * @param type $vendorId
	 * @param type $vehicleIds
	 * 
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function addVehicleVendor($vendorId, $vehicleIds)
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($vendorId) || empty($vehicleIds))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			foreach ($vehicleIds as $id)
			{
				$vendorVehicleModel				 = new VendorVehicle();
				$vendorVehicleModel->vvhc_active = 1;
				$vendorVehicleModel->vvhc_vnd_id = $vendorId;
				$vendorVehicleModel->vvhc_vhc_id = $id;

				if (!$vendorVehicleModel->save())
				{
					throw new Exception(json_encode($vendorVehicleModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
			}

			$returnset->setStatus(true);
		}
		catch (Exception $e)
		{
			Logger::error($e->getMessage());
			$returnset->setException($e);
		}

		return $returnset;
	}

	public function updateUnderTaking($vmodel, $vendorVehicleId, $isImage = 0)
	{
		$success = false;
		/** @var $model VendorVehicle  */
		$model	 = VendorVehicle::model()->findByPk($vendorVehicleId);

		$model->vvhc_vhc_owner					 = $vmodel->vvhcVhc->owner;
		$model->vvhc_vhc_owner_auth_valid_date	 = $vmodel->vvhc_vhc_owner_auth_valid_date;
		$model->vhc_owner_phone					 = $vmodel->vhc_owner_phone;
		$model->vhc_owner_email					 = $vmodel->vhc_owner_email;
		$proof_img								 = $_FILES['vhc_owner_proof']['name'];
		$proof_img_temp							 = $_FILES['vhc_owner_proof']['tmp_name'];
		//$model->scenario						 = 'update_undertaking';

		if ($model->validate())
		{
			if ($isImage == 1)
			{
				$path					 = $this->saveImage($proof_img, $proof_img_temp, $model->vvhc_vnd_id);
				$model->vhc_owner_proof	 = $path[path];
			}
			$model->vvhc_digital_is_agree = 0;
			$model->save();
			if (!$model->save())
			{
				throw new Exception("Not Saved the Information.", ReturnSet::ERROR_FAILED);
			}
			else
			{
				$success = true;
				$msg	 = $model->vvhc_vhc_owner . " - Information Updated";
			}
		}
		else
		{
			throw new Exception("Not Validate the Information", ReturnSet::ERROR_FAILED);
		}
		return $model;
	}

	public function saveImage($image, $imagetmp, $vndID)
	{
		try
		{
			$path = "";
			if ($image != '')
			{
				$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
				if (!is_dir($dir))
				{
					mkdir($dir);
				}
				$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'vendor';

				if (!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}
				$dirByVehicleId = $dirFolderName . DIRECTORY_SEPARATOR . $vndID;
				if (!is_dir($dirByVehicleId))
				{
					mkdir($dirByVehicleId);
				}
				$dirByVehicleIdLOU = $dirByVehicleId . DIRECTORY_SEPARATOR . 'lou';
				if (!is_dir($dirByVehicleIdLOU))
				{
					mkdir($dirByVehicleIdLOU);
				}
				//$file_path = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'vehicles' . DIRECTORY_SEPARATOR . $vehicleId;
				$file_path	 = $dirByVehicleIdLOU;
				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;

				if (Vehicles::model()->img_resize($imagetmp, 1200, $f, $file_name))
				{
					$path	 = substr($file_path, strlen(PUBLIC_PATH));
					$result	 = ['path' => $path];
				}
			}
		}
		catch (Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		return $result;
	}

	public function updateVendorVehicleStatusByVndIdVhcId($vendorId, $vehicleId)
	{
		$params		 = [
			'vndId'	 => $vendorId,
			'vhcId'	 => $vehicleId
		];
		$sqlUpdate	 = "UPDATE `vendor_vehicle` 
                SET    vvhc_active   = 0, vvhc_is_lou_required = 0  
				WHERE  vvhc_vnd_id   =:vndId
				AND    vvhc_vhc_id   =:vhcId ";

		return DBUtil::execute($sqlUpdate, $params);
	}

	public function unlinkByVendorVehicleId($vendorId, $vehicleId)
	{
		$success = false;
		$model	 = VendorVehicle::model()->updateVendorVehicleStatusByVndIdVhcId($vendorId, $vehicleId);
		if ($model)
		{
			$userInfo	 = UserInfo::getInstance();
			$eventId	 = VehiclesLog::VEHICLE_VENDOR_DELETE;
			$desc		 = VehiclesLog::model()->getEventByEventId($eventId);
			VehiclesLog::model()->createLog($vehicleId, $desc, $userInfo, $eventId, false, false);
			$success	 = true;
		}
		return $success;
	}

	public function getLouList($search = false, $date1 = false, $date2 = false, $statusDt = false, $searchVhcNo = false)
	{
		if ($search != '')
		{
			$searchCond = "AND ((cnte.eml_email_address LIKE '%" . trim($search) . "%') OR (cntp.phn_phone_no LIKE '%" . trim($search) . "%') OR (vnd.vnd_code LIKE '%" . trim($search) . "%' ) OR (vnd.vnd_name LIKE '%" . trim($search) . "%'))";
		}
		if ($date1 != '' && $date2 != '')
		{
			$searchDateCond = " AND (vvhc_lou_approve_date BETWEEN '$date1 00:00:00' AND '$date2 23:59:59')";
		}
		if ($statusDt != '')
		{
			$statusCond = " vvhc_lou_approved IN($statusDt)";
		}
		else
		{
			$statusCond = " vvhc_lou_approved IN(3)";
		}
		if ($searchVhcNo != '')
		{
			$search_txt		 = trim($searchVhcNo);
			$tsearch_txt	 = strtolower(str_replace(' ', '', $search_txt));
			$searchVhcNoCond = " AND  (REPLACE(LOWER(vehicles.vhc_number),' ', '')  LIKE '%$tsearch_txt%') ";
		}

		$sql = "SELECT 
				vnd.vnd_name
				, vnd.vnd_code
				, vvhc_id
				, vvhc_vnd_id
				, vvhc_vhc_id
				, vvhc_lou_approved
				, vvhc_lou_approveby
				, vvhc_vhc_owner
				, vvhc_lou_approve_date
				, vvhc_lou_expire_date
				, vvhc_lou_created_date
				, IF(CONCAT(vehicles.vhc_reg_owner, ' ', vehicles.vhc_reg_owner_lname) IS NOT NULL AND CONCAT(vehicles.vhc_reg_owner, ' ', vehicles.vhc_reg_owner_lname) <> '', CONCAT(vehicles.vhc_reg_owner, ' ', vehicles.vhc_reg_owner_lname), CONCAT(cnt.ctt_first_name, ' ', cnt.ctt_last_name)) AS vhc_owner
				, (CONCAT(ad.adm_fname, ' ', ad.adm_lname)) AS vvhc_lou_approve_name
				, vehicle_types.vht_make
				, vehicles.vhc_number
				, vehicle_types.vht_model
				, (CASE WHEN (vvhc_lou_approved = 1) THEN 'Approved' WHEN (vvhc_lou_approved = 2) THEN 'Rejected' WHEN (vvhc_lou_approved = 0) THEN 'Default' WHEN (vvhc_lou_approved = 3) THEN 'Pending' END) AS lou_status
				, CONCAT(vht_make, ' ', vht_model) AS vehicle_name,
				cntp.phn_phone_no,
				cnte.eml_email_address
				FROM     vendor_vehicle
				INNER JOIN vendors vnd ON vvhc_vnd_id = vnd.vnd_id AND vnd.vnd_active > 0
				INNER JOIN `vehicles` ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id AND vehicles.vhc_active = 1
				INNER JOIN `vehicle_types` ON vehicle_types.vht_id = vehicles.vhc_type_id
				LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = vendor_vehicle.vvhc_owner_contact_id AND cnte.eml_active = 1
				LEFT JOIN contact AS cnt ON cnt.ctt_id = vendor_vehicle.vvhc_owner_contact_id AND cnt.ctt_active = 1
				LEFT JOIN admins AS ad ON ad.adm_id = vendor_vehicle.vvhc_lou_approveby         
				LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = vendor_vehicle.vvhc_owner_contact_id AND cntp.phn_active = 1
				WHERE  vvhc_active = 1 AND  $statusCond  $searchCond  $searchDateCond  $searchVhcNoCond GROUP BY vvhc_vnd_id";

		$sqlCount		 = "SELECT 
					COUNT(DISTINCT vvhc_vnd_id)
					FROM  vendor_vehicle
					INNER JOIN vendors vnd ON vvhc_vnd_id = vnd.vnd_id AND vnd.vnd_active > 0
					LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = vendor_vehicle.vvhc_owner_contact_id AND cnte.eml_active = 1
					LEFT JOIN contact AS cnt ON cnt.ctt_id = vendor_vehicle.vvhc_owner_contact_id AND cnt.ctt_active = 1
					LEFT JOIN admins AS ad ON ad.adm_id = vendor_vehicle.vvhc_lou_approveby
					INNER JOIN `vehicles` ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id AND vehicles.vhc_active = 1
					INNER JOIN `vehicle_types` ON vehicle_types.vht_id = vehicles.vhc_type_id
					LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = vendor_vehicle.vvhc_owner_contact_id AND cntp.phn_active = 1
					WHERE    vvhc_active = 1 AND  $statusCond  $searchCond  $searchDateCond  $searchVhcNoCond";
		$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['vvhc_vnd_id'],
				'defaultOrder'	 => 'vvhc_vnd_id DESC'
			],
			'pagination'	 => ['pageSize' => 200],
		]);
		return $dataprovider;
	}

	public function getLouInfoById($vendorId, $vvhcVhcId)
	{
		$sql = "SELECT vnd.vnd_name, vnd.vnd_code, 
                                            vvhc_id, vvhc_vnd_id, vvhc_vhc_id, vvhc_lou_approved, vvhc_lou_approveby, vvhc_vhc_owner,
                                            vvhc_lou_approve_date, vvhc_lou_expire_date,
					    cnt.ctt_license_no,
					    cnt.ctt_pan_no,
                                            IF(CONCAT(vehicles.vhc_reg_owner, ' ', vehicles.vhc_reg_owner_lname)  IS NOT NULL AND CONCAT(vehicles.vhc_reg_owner, ' ', vehicles.vhc_reg_owner_lname)  <> '', (CONCAT(vehicles.vhc_reg_owner, ' ', vehicles.vhc_reg_owner_lname)),(CONCAT(cnt.ctt_first_name , ' ',cnt.ctt_last_name))) AS vhc_owner,
					    (CONCAT(ad.adm_fname, ' ',ad.adm_lname)) AS vvhc_lou_approve_name,
                                            vht_make, vhc_number, 
                                            vht_model,
                                            (CASE WHEN (vvhc_lou_approved =1)  THEN 'Approved'
                                                  WHEN (vvhc_lou_approved = 2) THEN 'Rejected'
                                                  WHEN (vvhc_lou_approved = 0) THEN 'Default'
						  WHEN (vvhc_lou_approved =3) THEN 'Pending'         
                                            END) AS lou_status,
                                            CONCAT(vht_make , ' ' , vht_model) AS vehicle_name,
                                            cntp.phn_phone_no,cnte.eml_email_address 
                                            FROM vendor_vehicle 
                                            INNER JOIN vendors vnd ON vvhc_vnd_id =vnd.vnd_id AND vnd.vnd_active > 0
                                            LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = vendor_vehicle.vvhc_owner_contact_id AND cnte.eml_active = 1
				            LEFT JOIN contact AS cnt ON cnt.ctt_id = vendor_vehicle.vvhc_owner_contact_id AND cnt.ctt_active = 1
					    LEFT JOIN admins AS ad ON ad.adm_id  = vendor_vehicle.vvhc_lou_approveby
				            INNER JOIN `vehicles` ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id  AND vehicles.vhc_active = 1 
                                            INNER JOIN `vehicle_types` ON vehicle_types.vht_id= vehicles.vhc_type_id
                                            LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = vendor_vehicle.vvhc_owner_contact_id AND cntp.phn_active = 1 
			                    WHERE   vvhc_active = 1 AND vendor_vehicle.vvhc_vnd_id = $vendorId AND vendor_vehicle.vvhc_vhc_id = $vvhcVhcId";

		$recordset = DBUtil::queryRow($sql, DBUtil::SDB());
		return $recordset;
	}

	public function updateLouStatusByVndVhcId($vvhc_id, $status, $userId, $vnd_id, $vvhc_vhc_id)
	{
		$check = VendorVehicle::checkVhcForLou($vvhc_vhc_id, $vnd_id);
		if ($check == true)
		{
			$louApprovedDate	 = date("Y-m-d H:i:s");
			$louExpireDate		 = date("Y-m-d H:i:s", strtotime("+3 months"));
			$louRejectedExpiry	 = date("Y-m-d H:i:s");
			$params				 = [
				'vvhc_id'			 => $vvhc_id,
				'status'			 => $status,
				'userId'			 => $userId,
				'louApprovedDate'	 => $louApprovedDate,
				'louExpireDate'		 => $louExpireDate,
				'louRejectedExpiry'	 => $louRejectedExpiry
			];
			$sqlUpdate			 = "UPDATE `vendor_vehicle` 
                SET    vvhc_lou_approved   =:status,
				vvhc_lou_approveby  =:userId,
				vvhc_lou_approve_date = CASE
				WHEN :status =1 THEN :louApprovedDate
				ELSE :louApprovedDate
				END,
				vvhc_lou_expire_date = CASE
				WHEN :status =1 THEN :louExpireDate
				ELSE :louRejectedExpiry
				END
				WHERE  vvhc_id    =:vvhc_id";
			return DBUtil::command($sqlUpdate)->execute($params);
		}
	}

	public static function getNewInstance()
	{
		$model			 = new VendorVehicle('new');
		$model->vvhcVhc	 = new Vehicles();
		return $model;
	}

	public function getcabForLou($vendorId)
	{
		$qry		 = "SELECT vehicles.vhc_owned_or_rented as isOwned, vehicles.vhc_id,
					vehicles.vhc_type_id,
					vehicles.vhc_number,
		 CONCAT(vndContact.ctt_first_name, ' ', vndContact.ctt_last_name) as vndName,
		 CONCAT(vehicles.vhc_reg_owner, ' ', vehicles.vhc_reg_owner_lname) as vhcOwnerName,
		 vendor_vehicle.vvhc_id as  vvhc_id
		 FROM
	`vendor_vehicle`
	INNER JOIN `vehicles` ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id AND vehicles.vhc_active = 1 AND vehicles.vhc_is_freeze <> 1 AND vhc_approved <> 3
	INNER JOIN vendors ON vendors.vnd_id =  vendor_vehicle.vvhc_vnd_id 
	INNER JOIN contact  as vndContact ON vndContact.ctt_id = vendors.vnd_contact_id 
		 
		 WHERE
	vendor_vehicle.vvhc_vnd_id = $vendorId  AND vendor_vehicle.vvhc_active = 1
		 AND vendor_vehicle.vvhc_lou_approved IN(0,2)
HAVING isOwned = 2 OR (isOwned =1 AND vndName!= vhcOwnerName)";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getcabForLouV1($vendorId)
	{
		$qry = "SELECT vendor_vehicle.vvhc_owner_or_not as isOwned, vehicles.vhc_id,
                                            vehicles.vhc_type_id,
                                            vehicles.vhc_number,
                     CONCAT(vndContact.ctt_first_name, ' ', vndContact.ctt_last_name) as vndName,
                     CONCAT(vehicles.vhc_reg_owner, ' ', vehicles.vhc_reg_owner_lname) as vhcOwnerName,
                     vendor_vehicle.vvhc_id as  vvhc_id
                     FROM
            `vendor_vehicle`
            INNER JOIN `vehicles` ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id AND vehicles.vhc_active = 1 AND vehicles.vhc_is_freeze <> 1 AND vhc_approved <> 3
            INNER JOIN vendors ON vendors.vnd_id =  vendor_vehicle.vvhc_vnd_id AND vendors.vnd_id = vendors.vnd_ref_code
			INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status = 1
            INNER JOIN contact  as vndContact ON vndContact.ctt_id = cp.cr_contact_id AND vndContact.ctt_active = 1 AND vndContact.ctt_id = vndContact.ctt_ref_code

                     WHERE
            vendor_vehicle.vvhc_vnd_id = $vendorId  AND vendor_vehicle.vvhc_active = 1
                     AND vendor_vehicle.vvhc_lou_approved IN(0,2)
     HAVING isOwned = 2 OR (isOwned =1 AND vndName!= vhcOwnerName)";

		$recordset = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function checkVhcForLou($vvhc_vhc_id, $vnd_id)
	{
		$params		 = [
			'vvhc_vhc_id'	 => $vvhc_vhc_id,
			'vvhc_vnd_id'	 => $vnd_id,
			'vvhc_active'	 => 1,
		];
		$userInfo	 = UserInfo::getInstance();
		#$sql		 = "SELECT vvhc_id FROM vendor_vehicle WHERE vvhc_vhc_id =:vvhc_vhc_id AND vvhc_vnd_id!=:vvhc_vnd_id AND vvhc_active=:vvhc_active";
		#$recordset	 = DBUtil::command($sql)->execute($params);
		$sql		 = "SELECT vvhc_id FROM vendor_vehicle WHERE vvhc_vhc_id =$vvhc_vhc_id AND vvhc_vnd_id!=$vnd_id AND vvhc_active=1";
		$recordset	 = DBUtil::command($sql, DBUtil::SDB())->queryAll();
		if (count($recordset) > 0)
		{
			foreach ($recordset as $val)
			{
				$vvhcModel				 = VendorVehicle::model()->findByPk($val['vvhc_id']);
				$vvhcModel->vvhc_active	 = 0;
				if ($vvhcModel->save())
				{
					$desc		 = "Vehicle is Unlinked For LOU.";
					$event_id	 = VehiclesLog::VEHICLE_REJECTED;
					VehiclesLog::model()->createLog($vvhcModel->vvhc_vhc_id, $desc, $userInfo, $event_id, false, false);
				}
			}
		}
		return true;
	}

	public function getVvhcForLou($vendorId)
	{
		$qry		 = "SELECT   vehicles.vhc_owned_or_rented as isOwned, vehicles.vhc_id,
					vehicles.vhc_type_id,
					vehicles.vhc_number,
		 CONCAT(vndContact.ctt_first_name, ' ', vndContact.ctt_last_name) as vndName,
		 CONCAT(vehicles.vhc_reg_owner, ' ', vehicles.vhc_reg_owner_lname) as vhcOwnerName,
		 vendor_vehicle.vvhc_id as  vvhc_id
		 FROM
	`vendor_vehicle`
	INNER JOIN `vehicles` ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id AND vehicles.vhc_active = 1 AND vehicles.vhc_is_freeze <> 1 AND vhc_approved <> 3
	INNER JOIN vendors ON vendors.vnd_id =  vendor_vehicle.vvhc_vnd_id 
	INNER JOIN contact  as vndContact ON vndContact.ctt_id = vendors.vnd_contact_id 
		 
		 WHERE
	vendor_vehicle.vvhc_vnd_id = $vendorId  AND vendor_vehicle.vvhc_active = 1 
		 AND vendor_vehicle.vvhc_is_lou_required = 1 AND vendor_vehicle.vvhc_lou_approved NOT IN(1,3)";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getLouRequiredData($search = false, $command = DBUtil::ReturnType_Provider)
	{
		if ($search != '')
		{
			$search = " AND vhc.vhc_code = '$search'";
		}

		$qry = "SELECT vhc.vhc_code, vhc.vhc_number, vvhc.vvhc_id, GROUP_CONCAT(vnd.vnd_code) vnd_code,vnd.vnd_id, vhc.vhc_id, GROUP_CONCAT(ctt.ctt_first_name, ' ', ctt.ctt_last_name) as vendorNames FROM vendor_vehicle vvhc 
                INNER JOIN vendors vnd ON vnd.vnd_id = vvhc.vvhc_vnd_id AND vnd.vnd_active = 1 AND vnd.vnd_id = vnd.vnd_ref_code
                INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status = 1
                LEFT JOIN contact ctt ON ctt.ctt_id = cp.cr_contact_id AND ctt.ctt_active = 1 AND ctt.ctt_id = ctt.ctt_ref_code 
                INNER JOIN vehicles vhc ON vhc.vhc_id = vvhc.vvhc_vhc_id AND vhc.vhc_active = 1 
                WHERE vvhc.vvhc_is_lou_required = 1 AND vvhc.vvhc_lou_approved=0 $search GROUP BY vvhc.vvhc_vhc_id";
		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($qry) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($qry, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['vhc_code'],
					'defaultOrder'	 => 'vhc_code DESC'
				],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			$orderby = " ORDER BY vhc_code DESC ";
			return DBUtil::query($qry . $orderby, DBUtil::SDB());
		}
	}

	public function unlinkOther($vvhc_vhc_id, $vnd_id)
	{
		$params		 = [
			'vvhc_vhc_id'	 => $vvhc_vhc_id,
			'vvhc_vnd_id'	 => $vnd_id,
			'vvhc_active'	 => 1,
		];
		$userInfo	 = null;
		#$sql		 = "SELECT vvhc_id FROM vendor_vehicle WHERE vvhc_vhc_id =:vvhc_vhc_id AND vvhc_vnd_id!=:vvhc_vnd_id AND vvhc_active=:vvhc_active";
		#$recordset	 = DBUtil::command($sql)->execute($params);
		$sql		 = "SELECT vvhc_id FROM vendor_vehicle WHERE vvhc_vhc_id =$vvhc_vhc_id AND vvhc_vnd_id!=$vnd_id AND vvhc_active=1";
		//$recordset	 = DBUtil::command($sql, DBUtil::SDB())->queryAll();
		$recordset	 = DBUtil::query($sql, DBUtil::SDB());
		if ($recordset->getRowCount() > 0)
		{
			foreach ($recordset as $val)
			{
				$vvhcModel	 = VendorVehicle::model()->findByPk($val['vvhc_id']);
				$vendorId	 = $vvhcModel->vvhc_vnd_id;

				$vvhcModel->vvhc_active = 0;
				if ($vvhcModel->save())
				{
					$desc		 = "Vehicle is Unlinked From old vendor.";
					$event_id	 = VehiclesLog::VEHICLE_VENDOR_DELETE;
					VehiclesLog::model()->createLog($vvhcModel->vvhc_vhc_id, $desc, $userInfo, $event_id, false, false);
				}
			}
		}
		return true;
	}

	public static function checkCarAvlibility($bkg_id, $carValArr, $vendorId)
	{

		$params	 = ['bkgId'		 => $vvhc_vhc_id,
			'vendorId'	 => $vendorId
		];
		$sql	 = "SELECT booking.bkg_vehicle_type_id FROM BOOKING
                INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
                INNER JOIN vehicle_category ON vehicle_category.vct_id = scv.scv_vct_id
		 WHERE booking.bkg_id = $bkg_id";
	}

	public static function getLouListByVehicle($vhcId)
	{
		$params	 = ['vhcId' => $vhcId];
		$sql	 = "SELECT 
				vnd.vnd_name
				, vnd.vnd_code
				, vvhc_id
				, vvhc_vnd_id
				, vvhc_vhc_id
				, vvhc_is_lou_required
				, vvhc_lou_approved
				, vvhc_lou_approveby
				, vvhc_vhc_owner
				, vvhc_lou_approve_date
				, vvhc_lou_expire_date
				, vvhc_lou_created_date
				, IF(CONCAT(COALESCE(vehicles.vhc_reg_owner,''), ' ', COALESCE(vehicles.vhc_reg_owner_lname,'')) IS NOT NULL AND CONCAT(COALESCE(vehicles.vhc_reg_owner,''), ' ', COALESCE(vehicles.vhc_reg_owner_lname,'')) <> '', CONCAT(COALESCE(vehicles.vhc_reg_owner,''), ' ', COALESCE(vehicles.vhc_reg_owner_lname,'')), CONCAT(COALESCE(cnt.ctt_first_name,''), ' ', COALESCE(cnt.ctt_last_name,''))) AS vhc_owner
				, (CONCAT(ad.adm_fname, ' ', ad.adm_lname)) AS vvhc_lou_approve_name
				, vehicle_types.vht_make
				, vehicles.vhc_number
				, vehicle_types.vht_model
				, (CASE WHEN (vvhc_lou_approved = 1) THEN 'Approved' WHEN (vvhc_lou_approved = 2) THEN 'Rejected' WHEN (vvhc_lou_approved = 0) THEN 'Default' WHEN (vvhc_lou_approved = 3) THEN 'Pending' END) AS lou_status
				, CONCAT(vht_make, ' ', vht_model) AS vehicle_name,
				cntp.phn_phone_no,
				cnte.eml_email_address,
                doc1.doc_file_front_path AS license_path,
				doc1.doc_id AS license_id,
                doc2.doc_file_front_path AS pan_path,
				doc2.doc_id AS pan_id,
                doc3.doc_file_front_path AS proof_path,
				doc3.doc_id AS proof_id
				FROM     vendor_vehicle
				INNER JOIN vendors vnd ON vvhc_vnd_id = vnd.vnd_id AND vnd.vnd_active > 0
				INNER JOIN `vehicles` ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id AND vehicles.vhc_active = 1
				INNER JOIN `vehicle_types` ON vehicle_types.vht_id = vehicles.vhc_type_id
                LEFT JOIN document doc1 ON vendor_vehicle.vvhc_owner_license_id = doc1.doc_id AND doc1.doc_active = 1
                LEFT JOIN document doc2 ON vendor_vehicle.vvhc_owner_pan_id = doc2.doc_id AND doc2.doc_active = 1
                LEFT JOIN document doc3 ON vendor_vehicle.vvhc_owner_proof = doc3.doc_id AND doc3.doc_active = 1
				LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = vendor_vehicle.vvhc_owner_contact_id AND cnte.eml_active = 1
				LEFT JOIN contact AS cnt ON cnt.ctt_id = vendor_vehicle.vvhc_owner_contact_id AND cnt.ctt_active = 1
				LEFT JOIN admins AS ad ON ad.adm_id = vendor_vehicle.vvhc_lou_approveby         
				LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = vendor_vehicle.vvhc_owner_contact_id AND cntp.phn_active = 1
				WHERE  vvhc_active = 1 AND vvhc_vhc_id =:vhcId
				 GROUP BY vvhc_vhc_id";
		return DBUtil::queryAll($sql, DBUtil::SDB(), $params);
	}

	public static function getCurrentlyActiveVehicleByVendor($vndId)
	{
		$params	 = ['vndId' => $vndId];
		$sql	 = "SELECT  
        booking_cab.bcb_cab_id,
        v1.vhc_number,
		v1.vhc_code,
		v1.vhc_approved,
		vht_make as vhc_make,
		vht_model as vhc_model,
        vht_created_date as vhc_created_date,
		vct.vct_label,v2.vht_active
		FROM   booking
			JOIN booking_cab ON bcb_id = bkg_bcb_id
			JOIN svc_class_vhc_cat scv ON bkg_vehicle_type_id = scv.scv_id 
       JOIN vehicle_category v ON scv.scv_vct_id = v.vct_id
       LEFT JOIN vehicles v1 ON bcb_cab_id = v1.vhc_id
	   LEFT JOIN vendors a ON bcb_vendor_id = a.vnd_id
       LEFT JOIN vehicle_types v2 ON v1.vhc_type_id = v2.vht_id
	   INNER JOIN vcv_cat_vhc_type vcv ON vcv.vcv_vht_id = v2.vht_id
       INNER JOIN vehicle_category vct ON vct.vct_id = vcv.vcv_vct_id
	   WHERE bkg_status IN (5) AND bcb_vendor_id =:vndId  
	   GROUP BY bkg_id
	   ORDER BY `bkg_pickup_date` DESC LIMIT 2";

		$result = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $result;
	}

	public static function getCurrentlyActiveDriverByVendor($vndId)
	{
		$params		 = ['vndId' => $vndId];
		$sql		 = "SELECT  
						booking_cab.bcb_cab_id,
						d.drv_id,
						d.drv_name,
						d.drv_code,
						d.drv_is_freeze,
						phn_phone_no AS drv_phone
						FROM   booking
							JOIN booking_cab ON bcb_id = bkg_bcb_id

					   LEFT JOIN drivers d1 ON bcb_driver_id = d1.drv_id AND d1.drv_is_freeze = 0 AND d1.drv_active = 1
					   LEFT JOIN drivers d ON d.drv_id = d1.drv_ref_code
					   INNER JOIN contact_profile AS cp ON cp.cr_is_driver = d.drv_id AND cp.cr_status =1
					   INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_active =1 AND contact.ctt_id = contact.ctt_ref_code 
					   LEFT JOIN contact_phone ON contact_phone.phn_Contact_id = contact.ctt_id AND contact_phone.phn_is_primary=1 AND contact_phone.phn_active=1
					   WHERE bkg_status IN (5) AND bcb_vendor_id =:vndId
					   GROUP BY bcb_driver_id
					   ORDER BY `bkg_pickup_date`DESC LIMIT 2";
		$recordset	 = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $recordset;
	}

	////////////////////////////////////////

	public function getLocalDigitalPath()
	{
		$filePath = $this->vvhc_digital_sign;

		$filePath = implode("/", explode(DIRECTORY_SEPARATOR, $filePath));

		$filePath = ltrim($filePath, '/attachments');

		$filePath = implode(DIRECTORY_SEPARATOR, explode("/", $filePath));

		$filePath = $this->getLouLocalPath() . $filePath;

		if (!file_exists($filePath))
		{
			$filePath = APPLICATION_PATH . $this->vvhc_digital_sign;
		}

		return $filePath;
	}

	public function getLouLocalPath()
	{
		return PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR;
	}

	public function getLouSpacePath($localPath)
	{
		$fileName	 = basename($localPath);
		$id			 = $this->vvhc_digital_sign;
		$date		 = $this->vvhc_lou_created_date;
		$vhcId		 = $this->vvhc_vhc_id;
		if ($vhcId == '')
		{
			$vhcId = 0;
		}
		$fileName		 = $this->vvhc_id . '_' . $fileName;
		$folderExtender	 = Filter::s3FolderPath($vhcId);
		$path			 = "/vendor/lou/{$folderExtender}/{$vhcId}/{$fileName}";
		return $path;
	}

	public function getDigitalSpacePath()
	{
		return $this->getLouSpacePath($this->vvhc_digital_sign);
	}

	/**
	 * @return Stub\common\SpaceFile
	 */
	public function uploadToSpace($localFile, $spaceFile, $removeLocal = true)
	{
		$objSpaceFile = Storage::uploadFile(Storage::getDocumentSpace(), $spaceFile, $localFile, $removeLocal);
		return $objSpaceFile;
	}

	/** @return Stub\common\SpaceFile */
	public function uploadDigitalFileToSpace($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$vhccModel = $this;
			if (!file_exists($vhccModel->getLocalDigitalPath()) || $vhccModel->vvhc_digital_sign == '')
			{
				if ($vhccModel->vvhc_digital_sign == '')
				{
					$vhccModel->vvhc_lou_s3_data = "{}";
					$vhccModel->save();
					return null;
				}
			}
			$spaceFile = $vhccModel->uploadToSpace($vhccModel->getLocalDigitalPath(), $vhccModel->getDigitalSpacePath(), $removeLocal);

			$vhccModel->vvhc_lou_s3_data = $spaceFile->toJSON();
			$vhccModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
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
			$cond = " AND vvhc_digital_sign LIKE '%/{$serverId}/vehicles/%' ";

			$sql = "SELECT vvhc_id,vvhc_digital_sign FROM vendor_vehicle 
					WHERE vvhc_digital_sign!='' AND vvhc_lou_s3_data IS NULL {$cond} 
					ORDER BY vvhc_id DESC LIMIT 0, $limit1";
			$res = DBUtil::query($sql);
			if ($res->getRowCount() == 0)
			{
				break;
			}
			foreach ($res as $row)
			{
				/** @var Document $docModel */
				$vvhcModel = VendorVehicle::model()->findByPk($row["vvhc_id"]);
				$vvhcModel->uploadDigitalFileToSpace();
			}

			$limit -= $limit1;
			Logger::flush();
		}
	}

	/** @return SpacesAPI\File */
	public function getSpaceFile($spaceJSON)
	{
		if ($spaceJSON == '' || $spaceJSON == '{}')
		{
			return null;
		}
		return Stub\common\SpaceFile::populate($spaceJSON)->getFile();
	}

	/** @return SpacesAPI\File */
	public function getLouSpaceFile()
	{
		return $this->getSpaceFile($this->vvhc_lou_s3_data);
	}

	/**
	 * 
	 * @param type $vvhcId
	 * @return Doc path link
	 */
	public static function getLOUPathS3($vvhcId)
	{
		$path		 = '/images/no-image.png';
		/** @var VendorVehicle $vvhcModel */
		$vvhcModel	 = VendorVehicle::model()->findByPk($vvhcId);
		if (!$vvhcModel)
		{
			goto end;
		}
		$fieldName	 = "vvhc_lou_s3_data";
		$s3Data		 = $vvhcModel->$fieldName;
		$imgPath	 = $vvhcModel->getLocalDigitalPath();
		if (file_exists($imgPath) && $imgPath != $vvhcModel->getLouLocalPath())
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
				$vvhcModel->$fieldName = $spaceFile->toJSON();
				$vvhcModel->save();
			}
		}

		end:
		return $path;
	}

	public static function getLinking($vndId, $vhcId)
	{
		$params	 = ['vndId' => $vndId, 'vhcId' => $vhcId];
		$sql	 = "
				SELECT vhc.vhc_id,vhc_reg_owner,UPPER(vhc_number) vhc_number,vhc_reg_owner_lname,vhc_owned_or_rented,vhc_is_attached,vhc_has_cng,
				IF(vhc_approved=1,1,0) isApproved,vht.vht_model,vht.vht_make,vht.vht_id,
				vhc_has_cng,vhc_is_freeze,vhc_approved,
				vhc_owner_contact_id,ctt.ctt_first_name,ctt.ctt_last_name, 
				vhc_active,ctt.ctt_active,cpr.cr_is_vendor,vvhc_owner_or_not,
				vvhc.vvhc_id,vvhc_vnd_id,vvhc.vvhc_active,vvhc.vvhc_is_lou_required,vnd.vnd_ref_code,vnd.vnd_name,vnd2.vnd_name
				FROM `vehicles` vhc 
				INNER JOIN `vehicle_types` vht ON vht.vht_id=vhc.vhc_type_id AND vht.vht_active=1
				LEFT JOIN contact ctt ON ctt.ctt_id= vhc_owner_contact_id 
				LEFT JOIN contact_profile cpr ON cpr.cr_contact_id=ctt.ctt_id
				LEFT JOIN vendors vnd ON vnd.vnd_id = cpr.cr_is_vendor
				LEFT JOIN vendor_vehicle vvhc ON vvhc.vvhc_vhc_id=vhc_id AND vvhc.vvhc_vnd_id=:vndId
				LEFT JOIN vendors vnd2 ON vnd2.vnd_id = vvhc.vvhc_vnd_id
				WHERE vhc.vhc_id =:vhcId 
				";
		$res	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $res;
	}

	public static function checkLinking($vndId, $vhcId)
	{
		$data		 = VendorVehicle::getLinking($vndId, $vhcId);
		$defaultSet	 = ['isOwned' => 0, 'isLinked' => 0, 'isLOURequired' => 1, 'isApproved' => (int) $data['isApproved']];
		$resultSet	 = array_merge($defaultSet, $data);

		if (!$data)
		{
			return $resultSet;
		}
		if ($vndId == $data['vnd_ref_code'] || $data['vvhc_owner_or_not'] == 1)
		{
			$resultSet['isOwned'] = 1;
		}
		if ($data['vhc_reg_owner'] != null && ($data['vhc_reg_owner'] . ' ' . $data['vhc_reg_owner_lname'] == $data['ctt_first_name'] . ' ' . $data['ctt_last_name']))
		{
			$resultSet['isLOURequired'] = 0;
		}
		if ($data['vvhc_active'] == 1)
		{
			$resultSet['isLOURequired']	 = 0;
			$resultSet['isLinked']		 = 1;
		}
		return $resultSet;
	}

	/**
	 * @param integer $vndId
	 * @param integer $vhcId
	 * @return bool 
	 */
	public static function activateLinkingUnlinkOthers($vndId, $vhcId)
	{
		$success		 = false;
		$vendorVehicle	 = VendorVehicle::model()->findByVndVhcId($vndId, $vhcId);
		$transaction	 = DBUtil::beginTransaction();
		try
		{
			if (!empty($vendorVehicle) && $vendorVehicle->vvhc_active == 0)
			{
				$vendorVehicle->vvhc_active = 1;
				if ($vendorVehicle->save())
				{
					$success = VendorVehicle::unlinkOther($vhcId, $vndId);
				}
				DBUtil::commitTransaction($transaction);
			}
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			throw new Exception($e->getMessage(), ReturnSet::ERROR_FAILED);
		}

		return $success;
	}

	public static function getLinkedVendors($vhcId)
	{
		$params	 = ['vhcId' => $vhcId];
		$sql	 = "SELECT DISTINCT v2.vnd_id  AS vndId FROM vendor_vehicle 
					JOIN vendors v1 on vvhc_vnd_id=v1.vnd_id 
					JOIN vendors v2 on v1.vnd_id = v2.vnd_ref_code  
					WHERE vendor_vehicle.vvhc_vhc_id =:vhcId AND v2.vnd_id = v2.vnd_ref_code AND vendor_vehicle.vvhc_active=1
					GROUP BY v2.vnd_id";

		$res = DBUtil::query($sql, DBUtil::SDB(), $params);

		return $res;
	}

	/**
	 * 
	 * @param type $vndId
	 * @return type
	 */
	public static function getLinkedVehicles($vndId)
	{
		$params	 = ['vndId' => $vndId];
		$sql	 = "SELECT DISTINCT vvhc_vhc_id FROM vendor_vehicle 					  
					WHERE vendor_vehicle.vvhc_vnd_id =:vndId 
						AND vendor_vehicle.vvhc_active=1
					GROUP BY vvhc_vnd_id";
		$res	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);

		return $res;
	}

	/**
	 * 
	 * @param type $vndId
	 * @return boolean
	 */
	public static function checkAnyAddedVehicleDocsByVnd($vndId)
	{
		$vhcId = VendorVehicle::getLinkedVehicles($vndId);
		if ($vhcId > 0)
		{
			$dataDocs = VehicleDocs::model()->getDocsByVhcId($vhcId);
			return $dataDocs;
		}
		return false;
	}

	/**
	 * total number of vehicles of particular vendor
	 * @param type $vndIds
	 * @return type
	 */
	public static function totalVehicle($vndIds)
	{
		$params	 = ['vndId' => $vndIds];
		$sql	 = "SELECT count(distinct(vendor_vehicle.vvhc_vhc_id)) FROM vendor_vehicle 
					INNER JOIN vehicles ON vehicles.vhc_id=vendor_vehicle.vvhc_vhc_id AND vehicles.vhc_active =1
					INNER JOIN vendors ON vendors.vnd_id = vendor_vehicle.vvhc_vnd_id 
					WHERE vendor_vehicle.vvhc_vnd_id IN (:vndId)
					AND vendor_vehicle.vvhc_active=1
					GROUP BY vendors.vnd_ref_code";
		$res	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);

		return $res;
	}

	/**
	 * number of rejected vehicle of particular vendor
	 * @param type $vndIds
	 * @return type
	 */
	public static function rejectedVehicle($vndIds)
	{
		$params	 = ['vndId' => $vndIds];
		$sql	 = "SELECT COUNT(DISTINCT vendor_vehicle.vvhc_id) AS is_car
                FROM
                    `vendor_vehicle`
                INNER JOIN `vendors` ON vendors.vnd_id = vendor_vehicle.vvhc_vnd_id 
                INNER JOIN `vehicles` ON vehicles.vhc_id= vendor_vehicle.vvhc_vhc_id AND vehicles.vhc_approved =3 AND vehicles.vhc_active =1
                WHERE vendor_vehicle.vvhc_active = 1 AND vendors.vnd_id IN (:vndId) group by vendors.vnd_ref_code";

		$res = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);

		return $res;
	}
}
