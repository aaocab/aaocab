<?php

/**
 * This is the model class for table "driver_profile".
 *
 * The followings are the available columns in table 'driver_profile':
 * @property integer $drp_id
 * @property integer $drp_user_id
 * @property integer $drp_booking_id
 * @property integer $drp_attribute_type
 * @property string $drp_value_str
 * @property integer $drp_value_int
 * @property string $drp_created
 */
class DriverProfile extends CActiveRecord
{

	const TYPE_DRIVER_QUALITY	 = 1;
	const TYPE_ON_TIME		 = 2;
	const TYPE_SOFT_SPOKEN	 = 3;
	const TYPE_RESPECTFULL	 = 4;
	const TYPE_HELPFULL		 = 5;
	const TYPE_DROVE_SAFELY	 = 6;
	const TYPE_DRIVER_MISMATCH = 7;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'driver_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('drp_user_id, drp_booking_id, drp_attribute_type, drp_value_int', 'numerical', 'integerOnly' => true),
			array('drp_value_str', 'length', 'max' => 1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('drp_id, drp_user_id, drp_booking_id, drp_attribute_type, drp_value_str, drp_value_int, drp_created', 'safe', 'on' => 'search'),
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
			'drp_id'			 => 'Drp',
			'drp_user_id'		 => 'Drp User',
			'drp_booking_id'	 => 'Drp Booking',
			'drp_attribute_type' => 'Drp Attribute Type',
			'drp_value_str'		 => 'Drp Value Str',
			'drp_value_int'		 => 'Drp Value Int',
			'drp_created'		 => 'Drp Created',
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

		$criteria->compare('drp_id', $this->drp_id);
		$criteria->compare('drp_user_id', $this->drp_user_id);
		$criteria->compare('drp_booking_id', $this->drp_booking_id);
		$criteria->compare('drp_attribute_type', $this->drp_attribute_type);
		$criteria->compare('drp_value_str', $this->drp_value_str, true);
		$criteria->compare('drp_value_int', $this->drp_value_int);
		$criteria->compare('drp_created', $this->drp_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DriverProfile the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}
