<?php

/**
 * This is the model class for table "served_bookings".
 *
 * The followings are the available columns in table 'served_bookings':
 * @property integer $seb_id
 * @property integer $seb_from_city_id
 * @property integer $seb_to_city_id
 * @property integer $seb_bkg_served
 * @property string $seb_created
 */

class ServedBookings extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'served_bookings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('seb_from_city_id, seb_to_city_id, seb_created', 'required'),
			array('seb_from_city_id, seb_to_city_id, seb_bkg_served', 'numerical', 'integerOnly'=>true),
			array('seb_modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('seb_id, seb_from_city_id, seb_to_city_id, seb_bkg_served, seb_created', 'safe', 'on'=>'search'),
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
			'seb_id' => 'Seb',
			'seb_from_city_id' => 'Seb From City',
			'seb_to_city_id' => 'Seb To City',
			'seb_bkg_served' => 'Seb Bkg Served',
			'seb_created' => 'Seb Created',
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

		$criteria=new CDbCriteria;

		$criteria->compare('seb_id',$this->seb_id);
		$criteria->compare('seb_from_city_id',$this->seb_from_city_id);
		$criteria->compare('seb_to_city_id',$this->seb_to_city_id);
		$criteria->compare('seb_bkg_served',$this->seb_bkg_served);
		$criteria->compare('seb_created',$this->seb_created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ServedBookings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
