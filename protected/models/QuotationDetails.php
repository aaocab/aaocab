<?php

/**
 * This is the model class for table "quotation_details".
 *
 * The followings are the available columns in table 'quotation_details':
 * @property integer $qot_det_id
 * @property integer $qot_id
 * @property integer $qot_pickup_city
 * @property integer $qot_drop_city
 * @property string $qot_start_date
 * @property string $qot_start_time
 * @property integer $qot_det_car_type
 * @property integer $qot_det_day
 * @property integer $qot_det_km
 * @property double $qot_det_km_rate
 * @property double $qot_det_amount
 * @property double $qot_det_service_tax
 * @property string $qot_det_created
 *
 * The followings are the available model relations:
 * @property Quotation $qot
 */
class QuotationDetails extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotation_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('qot_det_created', 'required'),
			array('qot_id, qot_det_car_type, qot_det_day, qot_det_km', 'numerical', 'integerOnly' => true),
			array('qot_det_km_rate, qot_det_amount, qot_det_service_tax', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('qot_det_id, qot_id, qot_det_car_type, qot_det_day, qot_det_km, qot_det_km_rate, qot_det_amount, qot_det_service_tax, qot_det_created', 'safe', 'on' => 'search'),
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
			'qot' => array(self::BELONGS_TO, 'Quotation', 'qot_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'qot_det_id'			 => 'Qot Det',
			'qot_id'				 => 'Qot',
			'qot_det_car_type'		 => 'Qot Det Car Type',
			'qot_det_day'			 => 'Qot Det Day',
			'qot_det_km'			 => 'Qot Det Km',
			'qot_det_km_rate'		 => 'Qot Det Km Rate',
			'qot_det_amount'		 => 'Qot Det Amount',
			'qot_det_service_tax'	 => 'Qot Det Service Tax',
			'qot_det_created'		 => 'Qot Det Created',
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

		$criteria->compare('qot_det_id', $this->qot_det_id);
		$criteria->compare('qot_id', $this->qot_id);
		$criteria->compare('qot_det_car_type', $this->qot_det_car_type);
		$criteria->compare('qot_det_day', $this->qot_det_day);
		$criteria->compare('qot_det_km', $this->qot_det_km);
		$criteria->compare('qot_det_km_rate', $this->qot_det_km_rate);
		$criteria->compare('qot_det_amount', $this->qot_det_amount);
		$criteria->compare('qot_det_service_tax', $this->qot_det_service_tax);
		$criteria->compare('qot_det_created', $this->qot_det_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuotationDetails the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}
