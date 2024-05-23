<?php

/**
 * This is the model class for table "unregister_operator".
 *
 * The followings are the available columns in table 'unregister_operator':
 * @property integer $uo_id
 * @property string $uo_name
 * @property string $uo_phone
 * @property integer $uo_active
 *
 * The followings are the available model relations:
 * @property BookingUnregVendor[] $bookingUnregVendors
 * @property UnregisterOpsZones[] $unregisterOpsZones
 */
class UnregisterOperator extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'unregister_operator';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uo_active', 'numerical', 'integerOnly'=>true),
			array('uo_name', 'length', 'max'=>255),
			array('uo_phone', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uo_id, uo_name, uo_phone, uo_active', 'safe', 'on'=>'search'),
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
			'bookingUnregVendors' => array(self::HAS_MANY, 'BookingUnregVendor', 'buv_uo_id'),
			'unregisterOpsZones' => array(self::HAS_MANY, 'UnregisterOpsZones', 'uoz_uo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'uo_id' => 'Uo',
			'uo_name' => 'Uo Name',
			'uo_phone' => 'Uo Phone',
			'uo_active' => 'Uo Active',
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

		$criteria->compare('uo_id',$this->uo_id);
		$criteria->compare('uo_name',$this->uo_name,true);
		$criteria->compare('uo_phone',$this->uo_phone,true);
		$criteria->compare('uo_active',$this->uo_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UnregisterOperator the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getPhoneList()
	{
		$sql="SELECT * FROM `unregister_operator` WHERE uo_active=1 LIMIT 10";
		return DBUtil::queryAll($sql);
	}
}
