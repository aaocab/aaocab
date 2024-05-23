<?php

/**
 * This is the model class for table "invoices".
 *
 * The followings are the available columns in table 'invoices':
 * @property integer $inv_id
 * @property string $inv_no
 * @property integer $vnd_id
 * @property string $inv_from_date
 * @property string $inv_to_date
 * @property double $inv_booking_amount
 * @property double $inv_base_amount
 * @property double $inv_vendor_amount
 * @property double $inv_gozo_amount
 * @property double $inv_service_tax_amount
 * @property double $inv_tds_amount
 * @property double $inv_total_amount
 * @property string $inv_create_date
 *
 * The followings are the available model relations:
 * @property Vendors $inv
 */
class Invoices extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invoices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('inv_no, vnd_id, inv_booking_amount, inv_base_amount, inv_vendor_amount, inv_gozo_amount, inv_service_tax_amount, inv_tds_amount, inv_total_amount, inv_create_date', 'required'),
			array('vnd_id', 'numerical', 'integerOnly' => true),
			array('inv_booking_amount, inv_base_amount, inv_vendor_amount, inv_gozo_amount, inv_service_tax_amount, inv_tds_amount, inv_total_amount', 'numerical'),
			array('inv_no', 'length', 'max' => 200),
			array('inv_from_date, inv_to_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('inv_id, inv_no, vnd_id, inv_from_date, inv_to_date, inv_booking_amount, inv_base_amount, inv_vendor_amount, inv_gozo_amount, inv_service_tax_amount, inv_tds_amount, inv_total_amount, inv_create_date', 'safe', 'on' => 'search'),
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
			'inv' => array(self::BELONGS_TO, 'Vendors', 'inv_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'inv_id'				 => 'Inv',
			'inv_no'				 => 'Inv No',
			'vnd_id'				 => 'Vnd',
			'inv_from_date'			 => 'Inv From Date',
			'inv_to_date'			 => 'Inv To Date',
			'inv_booking_amount'	 => 'Inv Booking Amount',
			'inv_base_amount'		 => 'Inv Base Amount',
			'inv_vendor_amount'		 => 'Inv Vendor Amount',
			'inv_gozo_amount'		 => 'Inv Gozo Amount',
			'inv_service_tax_amount' => 'Inv Service Tax Amount',
			'inv_tds_amount'		 => 'Inv Tds Amount',
			'inv_total_amount'		 => 'Inv Total Amount',
			'inv_create_date'		 => 'Inv Create Date',
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

		$criteria->compare('inv_id', $this->inv_id);
		$criteria->compare('inv_no', $this->inv_no, true);
		$criteria->compare('vnd_id', $this->vnd_id);
		$criteria->compare('inv_from_date', $this->inv_from_date, true);
		$criteria->compare('inv_to_date', $this->inv_to_date, true);
		$criteria->compare('inv_booking_amount', $this->inv_booking_amount);
		$criteria->compare('inv_base_amount', $this->inv_base_amount);
		$criteria->compare('inv_vendor_amount', $this->inv_vendor_amount);
		$criteria->compare('inv_gozo_amount', $this->inv_gozo_amount);
		$criteria->compare('inv_service_tax_amount', $this->inv_service_tax_amount);
		$criteria->compare('inv_tds_amount', $this->inv_tds_amount);
		$criteria->compare('inv_total_amount', $this->inv_total_amount);
		$criteria->compare('inv_create_date', $this->inv_create_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Invoices the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}
