<?php

/**
 * This is the model class for table "vehicle_cat_svc_class".
 *
 * The followings are the available columns in table 'vehicle_cat_svc_class':
 * @property integer $vcsc_id
 * @property integer $vcsc_ssc_id
 * @property integer $vcsc_vct_id
 * @property integer $vcsc_large_bag
 * @property integer $vcsc_small_bag
 */
class VehicleCatSvcClass extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vehicle_cat_svc_class';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vcsc_ssc_id, vcsc_vct_id, vcsc_large_bag, vcsc_small_bag', 'required'),
			array('vcsc_ssc_id, vcsc_vct_id, vcsc_large_bag, vcsc_small_bag', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vcsc_id, vcsc_ssc_id, vcsc_vct_id, vcsc_large_bag, vcsc_small_bag', 'safe', 'on'=>'search'),
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
			'vcsc_id' => 'Vcsc',
			'vcsc_ssc_id' => 'Vcsc Ssc',
			'vcsc_vct_id' => 'Vcsc Vct',
			'vcsc_large_bag' => 'Vcsc Large Bag',
			'vcsc_small_bag' => 'Vcsc Small Bag',
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

		$criteria->compare('vcsc_id',$this->vcsc_id);
		$criteria->compare('vcsc_ssc_id',$this->vcsc_ssc_id);
		$criteria->compare('vcsc_vct_id',$this->vcsc_vct_id);
		$criteria->compare('vcsc_large_bag',$this->vcsc_large_bag);
		$criteria->compare('vcsc_small_bag',$this->vcsc_small_bag);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VehicleCatSvcClass the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function smallbagBycategoryClass($vehiclecatid,$serviceclassid)
	{
		$sql="SELECT vcsc.vcsc_small_bag 
			FROM vehicle_cat_svc_class AS vcsc
			 WHERE vcsc.vcsc_ssc_id = $serviceclassid AND vcsc.vcsc_vct_id = $vehiclecatid";
		return DBUtil::queryRow($sql);
	}
}
