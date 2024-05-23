<?php

/**
 * This is the model class for table "mmt_extra_km_dump".
 *
 * The followings are the available columns in table 'mmt_extra_km_dump':
 * @property integer $ekm_id
 * @property integer $ekm_city_id
 * @property string $ekm_city_name
 * @property integer $ekm_trip_type
 * @property integer $ekm_cab_type
 * @property double $ekm_extra_per_km
 */
class MmtExtraKmDump extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mmt_extra_km_dump';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			
			array('ekm_city_id, ekm_trip_type, ekm_cab_type', 'numerical', 'integerOnly'=>true),
			array('ekm_extra_per_km', 'numerical'),
			array('ekm_city_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ekm_id, ekm_city_id, ekm_city_name, ekm_trip_type, ekm_cab_type, ekm_extra_per_km', 'safe', 'on'=>'search'),
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
			'ekm_id' => 'Ekm',
			'ekm_city_id' => 'Ekm City',
			'ekm_city_name' => 'Ekm City Name',
			'ekm_trip_type' => 'Ekm Trip Type',
			'ekm_cab_type' => 'Ekm Cab Type',
			'ekm_extra_per_km' => 'Ekm Extra Per Km',
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

		$criteria->compare('ekm_id',$this->ekm_id);
		$criteria->compare('ekm_city_id',$this->ekm_city_id);
		$criteria->compare('ekm_city_name',$this->ekm_city_name,true);
		$criteria->compare('ekm_trip_type',$this->ekm_trip_type);
		$criteria->compare('ekm_cab_type',$this->ekm_cab_type);
		$criteria->compare('ekm_extra_per_km',$this->ekm_extra_per_km);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MmtExtraKmDump the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
