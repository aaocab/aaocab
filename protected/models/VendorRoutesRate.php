<?php

/**
 * This is the model class for table "vendor_routes_rate".
 *
 * The followings are the available columns in table 'vendor_routes_rate':
 * @property string $vnrr_id
 * @property integer $vnrr_vendor_id
 * @property integer $vnrr_route_id
 * @property string $vnrr_name
 * @property integer $vnrr_rate
 * @property integer $vnrr_status
 * @property string $vnrr_created
 * @property string $vnrr_modified

 * The followings are the available model relations:
 * @property Vendors $vendors
 */
class VendorRoutesRate extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_routes_rate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vnrr_vendor_id, vnrr_rate, vnrr_status', 'numerical', 'integerOnly' => true),
			array('vnrr_name', 'length', 'max' => 255),
			array('vnrr_created, vnrr_modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vnrr_id, vnrr_vendor_id,vnrr_route_id, vnrr_name, vnrr_rate, vnrr_status, vnrr_created, vnrr_modified', 'safe', 'on' => 'search'),
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
			'vendors' => array(self::BELONGS_TO, 'Vendors', 'vnd_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vnrr_id'		 => 'Agt Rut',
			'vnrr_vendor_id' => 'Agt Rut Vendor',
			'vnrr_route_id'	 => 'Agt Rut Route',
			'vnrr_name'		 => 'Agt Rut Rut Name',
			'vnrr_rate'		 => 'Agt Rut Rut Rate',
			'vnrr_status'	 => 'Agt Rut Status',
			'vnrr_created'	 => 'Agt Rut Created',
			'vnrr_modified'	 => 'Agt Rut Modified',
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

		$criteria->compare('vnrr_id', $this->vnrr_id, true);
		$criteria->compare('vnrr_vendor_id', $this->vnrr_vendor_id);
		$criteria->compare('vnrr_route_id', $this->vnrr_route_id);
		$criteria->compare('vnrr_name', $this->vnrr_name, true);
		$criteria->compare('vnrr_rate', $this->vnrr_rate);
		$criteria->compare('vnrr_status', $this->vnrr_status);
		$criteria->compare('vnrr_created', $this->vnrr_created, true);
		$criteria->compare('vnrr_modified', $this->vnrr_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorRoutesRate the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}
