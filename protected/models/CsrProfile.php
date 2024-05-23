<?php

/**
 * This is the model class for table "csr_profile".
 *
 * The followings are the available columns in table 'csr_profile':
 * @property integer $cpr_id
 * @property integer $cpr_user_id
 * @property integer $cpr_booking_id
 * @property integer $cpr_attribute_type
 * @property string $cpr_value_str
 * @property integer $cpr_value_int
 * @property string $cpr_created
 */
class CsrProfile extends CActiveRecord
{

	const TYPE_CSR_QUALITY		 = 1;
	const TYPE_POLITE				 = 2;
	const TYPE_CLEAR_COMMUNICATION = 3;
	const TYPE_PROFESSIONAL		 = 4;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'csr_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cpr_user_id, cpr_booking_id, cpr_attribute_type, cpr_value_int', 'numerical', 'integerOnly' => true),
			array('cpr_value_str', 'length', 'max' => 1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cpr_id, cpr_user_id, cpr_booking_id, cpr_attribute_type, cpr_value_str, cpr_value_int, cpr_created', 'safe', 'on' => 'search'),
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
			'cpr_id'			 => 'Cpr',
			'cpr_user_id'		 => 'Cpr User',
			'cpr_booking_id'	 => 'Cpr Booking',
			'cpr_attribute_type' => 'Cpr Attribute Type',
			'cpr_value_str'		 => 'Cpr Value Str',
			'cpr_value_int'		 => 'Cpr Value Int',
			'cpr_created'		 => 'Cpr Created',
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

		$criteria->compare('cpr_id', $this->cpr_id);
		$criteria->compare('cpr_user_id', $this->cpr_user_id);
		$criteria->compare('cpr_booking_id', $this->cpr_booking_id);
		$criteria->compare('cpr_attribute_type', $this->cpr_attribute_type);
		$criteria->compare('cpr_value_str', $this->cpr_value_str, true);
		$criteria->compare('cpr_value_int', $this->cpr_value_int);
		$criteria->compare('cpr_created', $this->cpr_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CsrProfile the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}
