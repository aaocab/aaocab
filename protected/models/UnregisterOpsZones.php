<?php

/**
 * This is the model class for table "unregister_ops_zones".
 *
 * The followings are the available columns in table 'unregister_ops_zones':
 * @property integer $uoz_id
 * @property integer $uoz_uo_id
 * @property integer $uoz_area_id
 * @property integer $uoz_area_type
 * @property integer $uoz_active
 *
 * The followings are the available model relations:
 * @property UnregisterOperator $uozUo
 */
class UnregisterOpsZones extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'unregister_ops_zones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uoz_uo_id', 'required'),
			array('uoz_uo_id, uoz_area_id, uoz_area_type, uoz_active', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uoz_id, uoz_uo_id, uoz_area_id, uoz_area_type, uoz_active', 'safe', 'on'=>'search'),
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
			'uozUo' => array(self::BELONGS_TO, 'UnregisterOperator', 'uoz_uo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'uoz_id' => 'Uoz',
			'uoz_uo_id' => 'Uoz Uo',
			'uoz_area_id' => 'Uoz Area',
			'uoz_area_type' => 'Uoz Area Type',
			'uoz_active' => 'Uoz Active',
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

		$criteria->compare('uoz_id',$this->uoz_id);
		$criteria->compare('uoz_uo_id',$this->uoz_uo_id);
		$criteria->compare('uoz_area_id',$this->uoz_area_id);
		$criteria->compare('uoz_area_type',$this->uoz_area_type);
		$criteria->compare('uoz_active',$this->uoz_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UnregisterOpsZones the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
