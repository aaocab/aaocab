<?php

/**
 * This is the model class for table "unreg_vendor_request".
 *
 * The followings are the available columns in table 'unreg_vendor_request':
 * @property integer $uvr_id
 * @property string $uvr_vnd_name
 * @property string $uvr_vnd_lname
 * @property string $uvr_vnd_username
 * @property string $uvr_vnd_password
 * @property string $uvr_vnd_address
 * @property string $uvr_vnd_email
 * @property string $uvr_vnd_phone
 * @property string $uvr_vnd_company
 * @property integer $uvr_vnd_city_id
 * @property string $uvr_vnd_home_zone
 * @property integer $uvr_bid_amount
 * @property integer $uvr_vnd_is_driver
 * @property string $uvr_vnd_voter_id_front_path
 * @property string $uvr_vnd_voter_no
 * @property string $uvr_vnd_aadhaar_front_path
 * @property string $uvr_vnd_aadhaar_no
 * @property string $uvr_vnd_pan_front_path
 * @property string $uvr_vnd_pan_no
 * @property string $uvr_vnd_licence_front_path
 * @property string $uvr_vnd_license_no
 * @property string $uvr_vnd_license_exp_date
 * @property integer $uvr_active
 * @property string $uvr_modified_date
 *
 * The followings are the available model relations:
 * @property BookingUnregVendor[] $bookingUnregVendors
 */
class UnregVendorRequest extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'unreg_vendor_request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uvr_vnd_name, uvr_vnd_city_id', 'required'),
			array('uvr_vnd_city_id, uvr_vnd_is_driver, uvr_active', 'numerical', 'integerOnly' => true),
			array('uvr_vnd_name, uvr_vnd_lname,  uvr_vnd_phone, uvr_bid_amount', 'required', 'on' => 'vendorjoin'),
			array('uvr_vnd_license_no', 'required', 'on' => 'vendorjoin2'),
			array('uvr_vnd_name', 'length', 'max' => 100),
			array('uvr_vnd_username, uvr_vnd_password, uvr_vnd_email, uvr_vnd_phone, uvr_vnd_voter_no, uvr_vnd_aadhaar_no, uvr_vnd_pan_no, uvr_vnd_license_no, uvr_vnd_license_exp_date', 'length', 'max' => 50),
			array('uvr_vnd_address, uvr_vnd_voter_id_front_path, uvr_vnd_aadhaar_front_path, uvr_vnd_pan_front_path, uvr_vnd_licence_front_path', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uvr_id, uvr_vnd_name, uvr_vnd_lname, uvr_vnd_username, uvr_vnd_password, uvr_vnd_address, uvr_vnd_email, uvr_vnd_phone, uvr_vnd_city_id, uvr_vnd_is_driver, uvr_vnd_voter_id_front_path, uvr_vnd_voter_no, uvr_vnd_aadhaar_front_path,uvr_vnd_home_zone,uvr_vnd_company,uvr_vnd_aadhaar_no, uvr_vnd_pan_front_path, uvr_vnd_pan_no, uvr_vnd_licence_front_path, uvr_vnd_license_no, uvr_vnd_license_exp_date, uvr_active,uvr_modified_date', 'safe', 'on' => 'search'),
		);
	}

	public $uvr_buv_id, $uvr_id, $uvr_vnd_license_exp_date1;

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'bookingUnregVendors' => array(self::HAS_MANY, 'BookingUnregVendor', 'buv_vnd_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'uvr_id'						 => 'Uvr',
			'uvr_vnd_name'					 => 'First Name',
			'uvr_vnd_lname'					 => 'Last Name',
			'uvr_vnd_username'				 => 'Uvr Vnd Username',
			'uvr_vnd_password'				 => 'Uvr Vnd Password',
			'uvr_vnd_address'				 => 'Address',
			'uvr_vnd_email'					 => 'Email',
			'uvr_vnd_phone'					 => 'Contact Number',
			'uvr_vnd_city_id'				 => 'City',
			'uvr_vnd_is_driver'				 => 'Uvr Vnd Is Driver',
			'uvr_vnd_voter_id_front_path'	 => 'Uvr Vnd Voter Id Front Path',
			'uvr_vnd_voter_no'				 => 'Uvr Vnd Voter No',
			'uvr_vnd_aadhaar_front_path'	 => 'Vnd Aadhaar Front Path',
			'uvr_vnd_aadhaar_no'			 => 'Uvr Vnd Aadhaar No',
			'uvr_vnd_pan_front_path'		 => 'Vnd Pan Front Path',
			'uvr_vnd_pan_no'				 => 'Uvr Vnd Pan No',
			'uvr_vnd_licence_front_path'	 => 'Uvr Vnd Licence Front Path',
			'uvr_vnd_license_no'			 => 'License No',
			'uvr_vnd_license_exp_date'		 => 'Uvr Vnd License Exp Date',
			'uvr_bid_amount'				 => 'Bid Amount',
			'uvr_active'					 => 'Uvr Active',
		);
	}

	public function findByUsernamenEmail($email, $phone)
	{
		$criteria = new CDbCriteria;
		//$criteria->compare('uvr_active'> 0);
		$criteria->addSearchCondition('uvr_vnd_email', $email, true, 'OR');
		$criteria->addSearchCondition('uvr_vnd_phone', $phone, true, 'OR');
		$criteria->addIncondition('uvr_active', [1, 2]);
		return $this->find($criteria);
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

		$criteria->compare('uvr_id', $this->uvr_id);
		$criteria->compare('uvr_vnd_name', $this->uvr_vnd_name, true);
		$criteria->compare('uvr_vnd_username', $this->uvr_vnd_username, true);
		$criteria->compare('uvr_vnd_password', $this->uvr_vnd_password, true);
		$criteria->compare('uvr_vnd_address', $this->uvr_vnd_address, true);
		$criteria->compare('uvr_vnd_email', $this->uvr_vnd_email, true);
		$criteria->compare('uvr_vnd_phone', $this->uvr_vnd_phone, true);
		$criteria->compare('uvr_vnd_city_id', $this->uvr_vnd_city_id);
		$criteria->compare('uvr_vnd_is_driver', $this->uvr_vnd_is_driver);
		$criteria->compare('uvr_vnd_voter_id_front_path', $this->uvr_vnd_voter_id_front_path, true);
		$criteria->compare('uvr_vnd_voter_no', $this->uvr_vnd_voter_no, true);
		$criteria->compare('uvr_vnd_aadhaar_front_path', $this->uvr_vnd_aadhaar_front_path, true);
		$criteria->compare('uvr_vnd_aadhaar_no', $this->uvr_vnd_aadhaar_no, true);
		$criteria->compare('uvr_vnd_pan_front_path', $this->uvr_vnd_pan_front_path, true);
		$criteria->compare('uvr_vnd_pan_no', $this->uvr_vnd_pan_no, true);
		$criteria->compare('uvr_vnd_licence_front_path', $this->uvr_vnd_licence_front_path, true);
		$criteria->compare('uvr_vnd_license_no', $this->uvr_vnd_license_no, true);
		$criteria->compare('uvr_vnd_license_exp_date', $this->uvr_vnd_license_exp_date, true);
		$criteria->compare('uvr_active', $this->uvr_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UnregVendorRequest the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function validateBidAmount($params)
	{
		//$params = ['buvId' => $buvId, 'fname' => $fname, 'lname' => $lname, 'bidAmount' => $bidAmount, 'phone' => $phone, 'email' => $email, 'city' => $city];
		/* @var  $cityModel Cities */
		$city					 = $params['city'];
		$hashBuvId				 = $params['buvId'];
		$buvId					 = Yii::app()->shortHash->unhash($hashBuvId);
		$bidAmount				 = $params['bidAmount'];
		$cityModel				 = Cities::model()->findByPk($city);
		$model					 = new UnregVendorRequest();
		$model->uvr_vnd_name	 = $params['fname'];
		$model->uvr_vnd_lname	 = $params['lname'];
		$model->uvr_vnd_phone	 = $params['phone'];
		$model->uvr_vnd_address	 = $cityModel->cty_name;
		$model->uvr_bid_amount	 = $params['bidAmount'];
		$model->uvr_vnd_email	 = $params['email'];
		$model->uvr_vnd_username = $params['phone'];
		if ($params['phone'] == '')
		{
			$model->uvr_vnd_username = $params['email'];
		}
		$password				 = md5($params['fname']);
		$model->uvr_vnd_password = $password;
		$model->uvr_vnd_city_id	 = $city;
		if (isset($city) && $city != '')
		{
			$zoneData					 = Zones::model()->getNearestZonebyCity($city);
			$model->uvr_vnd_home_zone	 = $zoneData['zon_id'];
		}
		$model->uvr_active	 = 1;
		$model->scenario	 = 'vendorjoin';
		$transaction = DBUtil::beginTransaction();
		try
		{
			$result		 = CActiveForm::validate($model, null, false);
			if ($result == '[]')
			{
				$buvModel = BookingUnregVendor::model()->findByPk($buvId);
				if ($buvModel->buv_vendor_id > 0)
				{
					$modelUnreg = UnregVendorRequest::model()->findByPk($buvModel->buv_vendor_id);
				}
				if (!$modelUnreg)
				{
					if ($model->save())
					{
						$bmodel					 = BookingUnregVendor::model()->findByPk($buvId);
						$bmodel->buv_vendor_id	 = $model->uvr_id;
						$bmodel->buv_bid_amount	 = 0;
						$bmodel->buv_bid_amount	 = $model->uvr_bid_amount;
						$success				 = $bmodel->save();
						if ($success)
						{
							$return = 'new' . "~" . $model->uvr_id;
							DBUtil::commitTransaction($transaction);
						}
						else
						{
							$getErrors = $bmodel->getErrors();
							throw new Exception($getErrors);
						}
					}
				}
				else
				{
					$bmodel					 = BookingUnregVendor::model()->findByPk($buvId);
					$bmodel->buv_vendor_id	 = $bmodel->buv_vendor_id;
					$bmodel->buv_bid_amount	 = $bidAmount;
					$success				 = $bmodel->save();
					if ($success)
					{
						$array	 = $modelUnreg->attributes;
						$array2	 = ['uvr_vnd_license_exp_date2' => date("d/m/Y", strtotime($modelUnreg->uvr_vnd_license_exp_date))];
						$return	 = 'existing' . "~" . json_encode(array_merge($array, $array2));
						DBUtil::commitTransaction($transaction);
					}
					else
					{
						$getErrors = $bmodel->getErrors();
						throw new Exception($getErrors);
					}
				}
			}
			else
			{
				$getErrors = $result;
				throw new Exception($getErrors);
			}
		}
		catch (Exception $ex)
		{
			$return = 'error' . "~" . $ex->getMessage();
			DBUtil::rollbackTransaction($transaction);
		}
		echo $return;
	}

	public function uploadDocument($model, $uploadVoterID, $uploadedPanId, $uploadedLicId)
	{
		$transaction	 = DBUtil::beginTransaction();
		try
		{
			$success		 = false;
			$errors			 = '';
			$model->scenario = 'vendorjoin2';
			$result			 = CActiveForm::validate($model, null, false);
			if ($result == '[]')
			{
				if ($model->save())
				{
					$folderName = 'unregvendor';
					if ($uploadVoterID != '')
					{
						$type								 = 'voterid';
						$path1								 = $this->uploadAttachments($uploadVoterID, $type, $model->uvr_id, $folderName);
						$model->uvr_vnd_voter_id_front_path	 = $path1;
						$model->save();
					}
					if ($uploadedPanId != '')
					{
						$type							 = 'pan';
						$path1							 = $this->uploadAttachments($uploadedPanId, $type, $model->uvr_id, $folderName);
						$model->uvr_vnd_pan_front_path	 = $path1;
						$model->save();
					}
					if ($uploadedLicId != '')
					{
						$type								 = 'licence';
						$path1								 = $this->uploadAttachments($uploadedLicId, $type, $model->uvr_id, $folderName);
						$model->uvr_vnd_licence_front_path	 = $path1;
						$model->save();
					}
					$success = DBUtil::commitTransaction($transaction);
					Logger::create('RESPONSE DATA =====>: ' . json_encode($model->attributes), CLogger::LEVEL_INFO);
				}
			}
			else
			{
				$getErrors = CJSON::decode($result);
				throw new Exception($getErrors);
			}
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
			DBUtil::rollbackTransaction($transaction);
			Logger::create('ERROR DATA =====>: ' . json_encode($result), CLogger::LEVEL_INFO);
		}

		return ['success' => $success, 'errors' => $errors];
	}

	public function uploadAttachments($uploadedFile, $type, $vendorId, $folderName)
	{
		$fileName	 = $vendorId . "-" . $type . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirFolderName = $dir . DIRECTORY_SEPARATOR . $folderName;
		if (!is_dir($dirFolderName))
		{
			mkdir($dirFolderName);
		}
		$dirByVendorId = $dirFolderName . DIRECTORY_SEPARATOR . $vendorId;
		if (!is_dir($dirByVendorId))
		{
			mkdir($dirByVendorId);
		}
		$foldertoupload	 = $dirByVendorId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByVendorId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $vendorId . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function fetchlist()
	{
		$sql = "SELECT
				uvr_id,
				uvr_vnd_name,
				uvr_vnd_lname,
				uvr_vnd_phone,
				uvr_vnd_email,
				uvr_modified_date,
				uvr_active,
				uvr_vnd_city_id,
				uvr_vnd_is_driver,
				cty_name
				FROM
				`unreg_vendor_request`
				LEFT JOIN cities ON cities.cty_id = uvr_vnd_city_id
				WHERE
				uvr_active = 1";
		if ($this->uvr_vnd_name != '')
		{
			$sql .= " AND unreg_vendor_request.uvr_vnd_name LIKE '%{$this->uvr_vnd_name}%'";
		}
		if ($this->uvr_vnd_lname != '')
		{
			$sql .= " AND unreg_vendor_request.uvr_vnd_lname LIKE '%{$this->uvr_vnd_lname}%'";
		}
		if ($this->uvr_vnd_phone != '')
		{
			$sql .= " AND unreg_vendor_request.uvr_vnd_phone = '{$this->uvr_vnd_phone}'";
		}
		if ($this->uvr_vnd_email != '')
		{
			$sql .= " AND unreg_vendor_request.uvr_vnd_email = '{$this->uvr_vnd_email}'";
		}
		if ($this->uvr_vnd_city_id != '')
		{
			$sql .= " AND cities.cty_name LIKE '%{$this->uvr_vnd_city_id}%'";
		}
		if ($this->uvr_vnd_is_driver != '')
		{
			$sql .= " AND unreg_vendor_request.uvr_vnd_is_driver IN(". implode(',',$this->uvr_vnd_is_driver). ")";
		}
		if ($this->uvr_modified_date != '')
		{
			$sql .= " AND unreg_vendor_request.uvr_modified_date > '{$this->uvr_modified_date} 00:00:00' AND unreg_vendor_request.uvr_modified_date < '{$this->uvr_modified_date} 23:59:59'";
		}

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['uvr_modified_date'],
			'defaultOrder'	 => 'uvr_modified_date DESC'],
			'pagination'	 => ['pageSize' => 20],
		]);

		return $dataprovider;
	}
	public static function findByUnRegID($uvrid, $model)
	{
		$uvrmodel				 = self::model()->findAll(array("condition" => "uvr_id =$uvrid"));
		$model->ctt_first_name	 = $uvrmodel[0]->attributes['uvr_vnd_name'];
		$model->ctt_last_name	 = $uvrmodel->attributes['uvr_vnd_lname'];
		$model->ctt_city		 = $uvrmodel[0]->attributes['uvr_vnd_city_id'];
		$model->ctt_address		 = $uvrmodel[0]->attributes['uvr_vnd_address'];
		$model->ctt_pan_no		 = $uvrmodel[0]->attributes['uvr_vnd_pan_no'];
		$model->ctt_aadhaar_no	 = $uvrmodel[0]->attributes['uvr_vnd_aadhaar_no'];
		$model->ctt_voter_no	 = $uvrmodel[0]->attributes['uvr_vnd_voter_no'];
		$model->ctt_license_no	 = $uvrmodel[0]->attributes['uvr_vnd_license_no'];
		$model->email_address	 = $uvrmodel[0]->attributes['uvr_vnd_email'];
		$model->phone_no		 = $uvrmodel[0]->attributes['uvr_vnd_phone'];
		return $model;
	}

}
