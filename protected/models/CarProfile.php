<?php

/**
 * This is the model class for table "car_profile".
 *
 * The followings are the available columns in table 'car_profile':
 * @property integer $crp_id
 * @property integer $crp_user_id
 * @property integer $crp_booking_id
 * @property integer $crp_attribute_type
 * @property string $crp_value_str
 * @property integer $crp_value_int
 * @property string $crp_created
 */
class CarProfile extends CActiveRecord
{

	const TYPE_CAR_QUALITY	 = 1;
	const TYPE_CLEAN			 = 2;
	const TYPE_GOOD_CONDITION	 = 3;
	const TYPE_COMMERCIAL		 = 4;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'car_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('crp_user_id, crp_booking_id, crp_attribute_type, crp_value_int', 'numerical', 'integerOnly' => true),
			array('crp_value_str', 'length', 'max' => 1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('crp_id, crp_user_id, crp_booking_id, crp_attribute_type, crp_value_str, crp_value_int, crp_created', 'safe', 'on' => 'search'),
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
			'crp_id'			 => 'Crp',
			'crp_user_id'		 => 'Crp User',
			'crp_booking_id'	 => 'Crp Booking',
			'crp_attribute_type' => 'Crp Attribute Type',
			'crp_value_str'		 => 'Crp Value Str',
			'crp_value_int'		 => 'Crp Value Int',
			'crp_created'		 => 'Crp Created',
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

		$criteria->compare('crp_id', $this->crp_id);
		$criteria->compare('crp_user_id', $this->crp_user_id);
		$criteria->compare('crp_booking_id', $this->crp_booking_id);
		$criteria->compare('crp_attribute_type', $this->crp_attribute_type);
		$criteria->compare('crp_value_str', $this->crp_value_str, true);
		$criteria->compare('crp_value_int', $this->crp_value_int);
		$criteria->compare('crp_created', $this->crp_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CarProfile the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}
