<?php

/**
 * This is the model class for table "vehicle_driver".
 *
 * The followings are the available columns in table 'vehicle_driver':
 * @property integer $vhd_id
 * @property integer $vhd_vehicle_id
 * @property integer $vhd_driver_id
 * @property string $vhd_from_date
 * @property string $vhd_to_date
 * @property integer $vhd_assigned
 *
 * The followings are the available model relations:
 * @property Drivers $vhdDriver
 * @property Vehicles $vhdVehicle
 */
class VehicleDriver extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vehicle_driver';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vhd_vehicle_id, vhd_driver_id, vhd_from_date', 'required'),
			array('vhd_driver_id, vhd_assigned', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vhd_id, vhd_vehicle_id, vhd_driver_id, vhd_from_date, vhd_to_date, vhd_assigned', 'safe', 'on' => 'search'),
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
			'vhdDriver'	 => array(self::BELONGS_TO, 'Drivers', 'vhd_driver_id'),
			'vhdVehicle' => array(self::BELONGS_TO, 'Vehicles', 'vhd_vehicle_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vhd_id'		 => 'Vhd',
			'vhd_vehicle_id' => 'Vehicle',
			'vhd_driver_id'	 => 'Driver',
			'vhd_from_date'	 => 'From Date',
			'vhd_to_date'	 => 'To Date',
			'vhd_assigned'	 => 'Assigned',
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

		$criteria->compare('vhd_id', $this->vhd_id);
		$criteria->compare('vhd_vehicle_id', $this->vhd_vehicle_id);
		$criteria->compare('vhd_driver_id', $this->vhd_driver_id);
		$criteria->compare('vhd_from_date', $this->vhd_from_date, true);
		$criteria->compare('vhd_to_date', $this->vhd_to_date, true);
		$criteria->compare('vhd_assigned', $this->vhd_assigned);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VehicleDriver the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getVehiclebyDriver($drv_id)
	{
		$vhd = Yii::app()->db->createCommand()
				->select('*')
				->from('vehicle_driver')
				->where("vhd_driver_id  in (SELECT d3.drv_id FROM drivers d1
          INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
          INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
          WHERE d1.drv_id='$drv_id')  AND vhd_from_date <= Now() AND vhd_to_date IS NULL")
				->queryRow();
		return $vhd;
	}

	public function getVehiclestatus2($vhcid)
	{
		$vhd = Yii::app()->db->createCommand()
				->select('*')
				->from('vehicle_driver')
				->where("vhd_vehicle_id =  $vhcid AND vhd_from_date <= Now() AND vhd_to_date IS NULL")
				->queryRow();
		return $vhd;
	}

	public function getVehiclestatus($vhcid)
	{
		$vhd = $this->find('vhd_vehicle_id =  :vhcid AND vhd_from_date <= Now() AND vhd_to_date IS NULL', array(':vhcid' => $vhcid));
		return $vhd;
	}

	public function driverlist($vch_id)
	{
		$driver = Yii::app()->db->createCommand()
				->select("vhd_driver_id,drv_name")
				->from('vehicle_driver')
				->leftJoin('drivers', 'vhd_driver_id=drv_id  AND drv_active = 1 ')
				->where("FIND_IN_SET($vch_id,vhd_vehicle_id)")
				->queryAll();
		return $driver;
	}

}
