<?php

/**
 * This is the model class for table "corporate".
 *
 * The followings are the available columns in table 'corporate':
 * @property integer $crp_id
 * @property string $crp_fname
 * @property string $crp_lname
 * @property string $crp_company
 * @property string $crp_owner
 * @property string $crp_code
 * @property string $crp_country_code
 * @property string $crp_contact
 * @property string $crp_email
 * @property string $crp_address
 * @property integer $crp_discount_type
 * @property integer $crp_discount_amount
 * @property integer $crp_credit_limit
 * @property string $crp_bank_name
 * @property string $crp_bank_branch
 * @property string $crp_bank_ifsc
 * @property string $crp_bank_account_no
 * @property integer $crp_agreement
 * @property string $crp_agreement_date
 * @property string $crp_agreement_file
 * @property string $crp_id_proof
 * @property integer $crp_active
 * @property string $crp_created
 * @property string $crp_modified
 */
class Corporate extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'corporate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('crp_company,crp_country_code,crp_contact,crp_email,crp_code', 'required'),
			array('crp_email', 'email'),
			array('crp_discount_type, crp_discount_amount, crp_credit_limit, crp_agreement, crp_active', 'numerical', 'integerOnly' => true),
			array('crp_fname, crp_lname, crp_company, crp_owner, crp_code, crp_email, crp_bank_name, crp_bank_branch, crp_bank_ifsc, crp_bank_account_no', 'length', 'max' => 150),
			array('crp_country_code', 'length', 'max' => 10),
			array('crp_contact', 'length', 'max' => 20),
			array('crp_address', 'length', 'max' => 300),
			array('crp_agreement_file, crp_id_proof', 'length', 'max' => 250),
			array('crp_agreement_date', 'safe'),
			array('crp_email', 'checkduplicate', 'on' => 'insert'),
			array('crp_code', 'checkduplicatecode', 'on' => 'insert,update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('crp_id, crp_fname, crp_lname, crp_company, crp_owner, crp_code, crp_country_code, crp_contact, crp_email, crp_address, crp_discount_type, crp_discount_amount, crp_credit_limit, crp_bank_name, crp_bank_branch, crp_bank_ifsc, crp_bank_account_no, crp_agreement, crp_agreement_date, crp_agreement_file, crp_id_proof, crp_active, crp_created, crp_modified', 'safe', 'on' => 'search'),
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
		);
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "crp_active=1",
		);
		return $arr;
	}

	public function checkduplicate($attribute)
	{
		$succ	 = true;
		$model	 = $this->find("crp_email=:email", ['email' => $this->crp_email]);
		if ($model != '')
		{
			$this->addError($attribute, 'Email address already added');
			$succ = false;
		}
		$model1 = $this->find("crp_contact=:contact", ['contact' => $this->crp_contact]);
		if ($model1 != '')
		{
			$this->addError('crp_contact', 'Contact already added');
			$succ = false;
		}
		return $succ;
	}

	public function checkduplicatecode($attribute)
	{
		$succ	 = true;
		$model	 = $this->find("crp_code=:code", ['code' => $this->crp_code]);
		if ($this->crp_id != '')
		{
			$model = $this->find("crp_code=:code AND crp_id<>:id", ['code' => $this->crp_code, 'id' => $this->crp_id]);
		}
		if ($model != '')
		{
			$this->addError($attribute, 'Corporate Code Already Exist.');
			$succ = false;
		}
		return $succ;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'crp_id'				 => 'id',
			'crp_fname'				 => 'Fname',
			'crp_lname'				 => 'Lname',
			'crp_company'			 => 'Company',
			'crp_owner'				 => 'Owner',
			'crp_code'				 => 'Corporate Code',
			'crp_country_code'		 => 'Country Code',
			'crp_contact'			 => 'Contact',
			'crp_email'				 => 'Email',
			'crp_address'			 => 'Address',
			'crp_discount_type'		 => 'Discount Type',
			'crp_discount_amount'	 => 'Discount Amount',
			'crp_credit_limit'		 => 'Credit Limit',
			'crp_bank_name'			 => 'Bank Name',
			'crp_bank_branch'		 => 'Bank Branch',
			'crp_bank_ifsc'			 => 'Bank Ifsc',
			'crp_bank_account_no'	 => 'Bank Account No',
			'crp_agreement'			 => 'Agreement',
			'crp_agreement_date'	 => 'Agreement Date',
			'crp_agreement_file'	 => 'Agreement File',
			'crp_id_proof'			 => 'Id Proof',
			'crp_active'			 => 'Active',
			'crp_created'			 => 'Created',
			'crp_modified'			 => 'Modified',
		);
	}

	protected function beforeSave()
	{
		parent::beforeSave();

		if ($this->crp_agreement_date !== '' && $this->crp_agreement_date !== null)
		{
			if ((date('Y-m-d', strtotime($this->crp_agreement_date)) != date($this->crp_agreement_date)))
			{
				$insuranceExpDate			 = DateTimeFormat::DatePickerToDate($this->crp_agreement_date);
				$this->crp_agreement_date	 = $insuranceExpDate;
			}
		}
		else
		{
			$this->crp_agreement_date = null;
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

		$criteria->compare('crp_id', $this->crp_id);
//        $criteria->compare('crp_fname', $this->crp_fname, true);
//        $criteria->compare('crp_lname', $this->crp_lname, true);
		$criteria->compare('crp_company', $this->crp_company, true);
		$criteria->compare('crp_owner', $this->crp_owner, true);
		$criteria->compare('crp_code', $this->crp_code, true);
//        $criteria->compare('crp_code', $this->crp_code, true);
//        $criteria->compare('crp_country_code', $this->crp_country_code, true);
		$criteria->compare('crp_contact', $this->crp_contact, true);
		$criteria->compare('crp_email', $this->crp_email, true);
//        $criteria->compare('crp_address', $this->crp_address, true);
//        $criteria->compare('crp_discount_type', $this->crp_discount_type);
//        $criteria->compare('crp_discount_amount', $this->crp_discount_amount);
//        $criteria->compare('crp_credit_limit', $this->crp_credit_limit);
//        $criteria->compare('crp_bank_name', $this->crp_bank_name, true);
//        $criteria->compare('crp_bank_branch', $this->crp_bank_branch, true);
//        $criteria->compare('crp_bank_ifsc', $this->crp_bank_ifsc, true);
//        $criteria->compare('crp_bank_account_no', $this->crp_bank_account_no, true);
//        $criteria->compare('crp_agreement', $this->crp_agreement);
//        $criteria->compare('crp_agreement_date', $this->crp_agreement_date, true);
//        $criteria->compare('crp_agreement_file', $this->crp_agreement_file, true);
//        $criteria->compare('crp_id_proof', $this->crp_id_proof, true);
		$criteria->compare('crp_active', $this->crp_active);
//        $criteria->compare('crp_created', $this->crp_created, true);
//        $criteria->compare('crp_modified', $this->crp_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Corporate the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function uploadAttachments($uploadedFile, $type, $vehicleId, $folderName)
	{
		$fileName	 = $vehicleId . "-" . $type . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
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

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $vehicleId . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function getCorporateCodes($tag = '')
	{
		$arr = CHtml::listData($this->findAll(), 'crp_id', 'crp_code');
		if ($tag != '')
		{
			return $arr[$tag];
		}
		return $arr;
	}

}
