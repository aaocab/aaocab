<?php

/**
 * This is the model class for table "vendors".
 *
 * The followings are the available columns in table 'vendors':
 * @property integer $vnd_id
 * @property string $vnd_name
 * @property string $vnd_phone
 * @property string $vnd_email
 * @property string $vnd_address
 * @property integer $vnd_active
 * @property string $vnd_create_date
 *
 * The followings are the available model relations:
 * @property Booking[] $bookings
 */
class backVendors extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendors';
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "vnd_active=1",
		);
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
			array('vnd_name, vnd_phone, vnd_address', 'required'),
			array('vnd_active', 'numerical', 'integerOnly' => true),
			array('vnd_name', 'length', 'max' => 200),
			array('vnd_phone, vnd_email', 'length', 'max' => 100),
			array('vnd_address', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vnd_id, vnd_name, vnd_phone, vnd_email, vnd_address, vnd_active, vnd_create_date', 'safe', 'on' => 'search'),
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
			'bookings' => array(self::HAS_MANY, 'Booking', 'bkg_vendor_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vnd_id'			 => 'Agt',
			'vnd_name'			 => 'Vendor Name',
			'vnd_phone'			 => 'Phone',
			'vnd_email'			 => 'Email',
			'vnd_address'		 => 'Address',
			'vnd_active'		 => 'Agt Active',
			'vnd_create_date'	 => 'Agt Create Date',
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

		$criteria->compare('vnd_id', $this->vnd_id);
		$criteria->compare('vnd_name', $this->vnd_name, true);
		$criteria->compare('vnd_phone', $this->vnd_phone, true);
		$criteria->compare('vnd_email', $this->vnd_email, true);
		$criteria->compare('vnd_address', $this->vnd_address, true);
		$criteria->compare('vnd_active', $this->vnd_active);
		$criteria->compare('vnd_create_date', $this->vnd_create_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Vendors the static model class
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

	public function getVendorList()
	{
		$vendorModels	 = Vendors::model()->getAll();
		$arrSkill		 = array();
		foreach ($vendorModels as $sklModel)
		{
			$arrSkill[$sklModel->vnd_id] = $sklModel->vnd_name;
		}
		return $arrSkill;
	}

	public function getJSON()
	{
		$arrVendor	 = $this->getVendorList();
		$arrJSON	 = [];
		foreach ($arrVendor as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}

		$data = CJSON::encode($arrJSON);
		return $data;
	}

}
