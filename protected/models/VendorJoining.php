
<?php

/**
 * This is the model class for table "vendors_joining".
 *
 * The followings are the available columns in table 'vendors_joining':
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $city
 * @property string $company
 * @property string $email
 * @property integer $tnc
 * @property integer $tnc_id
 * @property string $tnc_datetime
 * @property string $ip_address
 * @property string $user_agent
 * @property integer $active
 * @property string $created
 * @property string $photo
 * @property string $photo_path
 * @property string $license
 * @property string $license_path
 * @property string $id_proof
 * @property string $id_proof_path
 * @property string $device_gcm_token
 */
class VendorJoining extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendors_joining';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, phone, city', 'required'),
			array('tnc, tnc_id, active', 'numerical', 'integerOnly' => true),
			array('name, city, company, ip_address', 'length', 'max' => 100),
			array('phone, email', 'length', 'max' => 50),
			array('user_agent', 'length', 'max' => 255),
			array('tnc_datetime, created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, phone, city, company, email, tnc, tnc_id, tnc_datetime, ip_address, user_agent, active, created, photo, photo_path, license, license_path, id_proof, id_proof_path, device_gcm_token', 'safe'),
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'			 => 'ID',
			'name'			 => 'Name',
			'phone'			 => 'Phone',
			'city'			 => 'City',
			'company'		 => 'Company',
			'email'			 => 'Email',
			'tnc'			 => 'Tnc',
			'tnc_id'		 => 'Tnc',
			'tnc_datetime'	 => 'Tnc Datetime',
			'ip_address'	 => 'Ip Address',
			'user_agent'	 => 'User Vendor',
			'active'		 => 'Active',
			'created'		 => 'Created',
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

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('phone', $this->phone, true);
		$criteria->compare('city', $this->city, true);
		$criteria->compare('company', $this->company, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('active', 1);
		$criteria->compare('created', $this->created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorsJoining the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getAll()
	{
		$criteria = new CDbCriteria;
		return $this->findAll($criteria);
	}

	public function fetchList()
	{
		$criteria		 = new CDbCriteria();
		$criteria->compare('active', 1);
		$dataProvider	 = new CActiveDataProvider(VendorsJoining::model()->together(), ['criteria' => $criteria, 'pagination' => array('pageSize' => 20)]);
		return $dataProvider;
	}

}
