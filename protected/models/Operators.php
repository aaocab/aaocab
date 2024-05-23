<?php

/**
 * This is the model class for table "operators".
 *
 * The followings are the available columns in table 'operators':
 * @property integer $opt_id
 * @property string $opt_email
 * @property string $opt_phone
 * @property string $opt_company_name
 * @property string $opt_create_date
 * @property integer $opt_active
 */
class Operators extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'operators';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('opt_create_date', 'required'),
			array('opt_active', 'numerical', 'integerOnly' => true),
			array('opt_email, opt_phone', 'length', 'max' => 200),
			array('opt_email,opt_company_name', 'required', 'on' => 'operatorjoin'),
			['opt_email', 'email', 'message' => 'This Email Address is not valid'],
			['opt_phone', 'numerical', 'integerOnly' => true],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('opt_id, opt_email, opt_phone,opt_company_name, opt_create_date, opt_active', 'safe', 'on' => 'search'),
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
			'opt_id'			 => 'Opt',
			'opt_email'			 => 'Email Address',
			'opt_phone'			 => 'Phone Number',
			'opt_company_name'	 => 'Company Name',
			'opt_create_date'	 => 'Opt Create Date',
			'opt_active'		 => 'Opt Active',
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

		$criteria->compare('opt_id', $this->opt_id);
		$criteria->compare('opt_email', $this->opt_email, true);
		$criteria->compare('opt_phone', $this->opt_phone, true);
		$criteria->compare('opt_create_date', $this->opt_create_date, true);
		$criteria->compare('opt_active', $this->opt_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Operators the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}
