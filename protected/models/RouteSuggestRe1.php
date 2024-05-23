<?php

/**
 * This is the model class for table "route_suggest_re1".
 *
 * The followings are the available columns in table 'route_suggest_re1':
 * @property integer $rsu_id
 * @property integer $rsu_from_city_id
 * @property integer $rsu_to_city_id
 * @property integer $rsu_rut_id
 * @property integer $rsu_user_id
 * @property string $rsu_create_date
 *
 * The followings are the available model relations:
 * @property Cities $rsuFromCity
 * @property Cities $rsuToCity
 */
class RouteSuggestRe1 extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'route_suggest_re1';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rsu_user_id, rsu_from_city_id, rsu_to_city_id', 'required'),
			array('rsu_id, rsu_from_city_id, rsu_to_city_id, rsu_rut_id, rsu_user_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rsu_id, rsu_from_city_id, rsu_to_city_id, rsu_rut_id, rsu_user_id, rsu_create_date', 'safe', 'on'=>'search'),
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
			'rsuFromCity' => array(self::BELONGS_TO, 'Cities', 'rsu_from_city_id'),
			'rsuToCity' => array(self::BELONGS_TO, 'Cities', 'rsu_to_city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rsu_id' => 'Rsu',
			'rsu_from_city_id' => 'From City',
			'rsu_to_city_id' => 'To City',
			'rsu_rut_id' => 'Rsu Rut',
			'rsu_user_id' => 'Rsu User',
			'rsu_create_date' => 'Rsu Create Date',
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

		$criteria->compare('rsu_id',$this->rsu_id);
		$criteria->compare('rsu_from_city_id',$this->rsu_from_city_id);
		$criteria->compare('rsu_to_city_id',$this->rsu_to_city_id);
		$criteria->compare('rsu_rut_id',$this->rsu_rut_id);
		$criteria->compare('rsu_user_id',$this->rsu_user_id);
		$criteria->compare('rsu_create_date',$this->rsu_create_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RouteSuggestRe1 the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
