<?php

/**
 * This is the model class for table "bookings_data_pickup".
 *
 * The followings are the available columns in table 'bookings_data_pickup':
 * @property integer $bdp_id
 * @property integer $bdp_source
 * @property integer $bdp_from_city
 * @property integer $bdp_from_zone
 * @property integer $bdp_to_zone
 * @property integer $bdp_to_city
 * @property integer $bdp_vehicle_type_id
 * @property string $bdp_pickup_date
 * @property integer $bdp_pickup_week
 * @property integer $bdp_count
 * @property integer $bdp_status
 */
class BookingsDataPickup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bookings_data_pickup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bdp_source, bdp_from_city, bdp_to_city, bdp_vehicle_type_id, bdp_pickup_week, bdp_count, bdp_status', 'numerical', 'integerOnly'=>true),
			array('bdp_pickup_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bdp_id, bdp_source, bdp_from_city, bdp_to_city, bdp_vehicle_type_id, bdp_pickup_date, bdp_pickup_week, bdp_count, bdp_status, bdp_from_zone, bdp_to_zone', 'safe', 'on'=>'search'),
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
			'bdp_id' => 'Bdp',
			'bdp_source' => '1 => User, 2 => Admin, 3 => App, 4=>Agent, 5=>Partner Spot',
			'bdp_from_city' => 'Bdp From City',
			'bdp_to_city' => 'Bdp To City',
			'bdp_from_zone' => 'Bdp From Zone',
			'bdp_to_zone' => 'Bdp To Zone',
			'bdp_vehicle_type_id' => 'Bdp Vehicle Type',
			'bdp_pickup_date' => 'Bdp Pickup Date',
			'bdp_pickup_week' => 'Bdp Pickup Week',
			'bdp_count' => 'Bdp Count',
			'bdp_status' => 'Bdp Status',
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

		$criteria->compare('bdp_id',$this->bdp_id);
		$criteria->compare('bdp_source',$this->bdp_source);
		$criteria->compare('bdp_from_city',$this->bdp_from_city);
		$criteria->compare('bdp_to_city',$this->bdp_to_city);
		$criteria->compare('bdp_from_zone',$this->bdp_from_zone);
		$criteria->compare('bdp_to_zone',$this->bdp_to_zone);
		$criteria->compare('bdp_vehicle_type_id',$this->bdp_vehicle_type_id);
		$criteria->compare('bdp_pickup_date',$this->bdp_pickup_date,true);
		$criteria->compare('bdp_pickup_week',$this->bdp_pickup_week);
		$criteria->compare('bdp_count',$this->bdp_count);
		$criteria->compare('bdp_status',$this->bdp_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingsDataPickup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function setData($bkgId)
	{
		$bkgModel = Booking::model()->findByPk($bkgId);
		if ($bkgModel)
		{
			$fromCity	 = $bkgModel->bkg_from_city_id;
			$toCity		 = $bkgModel->bkg_to_city_id;
			$pickupDate	 = date('Y-m-d', strtotime($bkgModel->bkg_pickup_date));
			$source		 = $bkgModel->bkgTrail->bkg_platform;
			$cabType	 = $bkgModel->bkg_vehicle_type_id;
			$model       = $this::model()->find('bdp_from_city=:bdp_from_city AND bdp_to_city=:bdp_to_city AND bdp_pickup_date=:bdp_pickup_date AND bdp_source=:bdp_source AND bdp_vehicle_type_id=:bdp_vehicle_type_id ', ['bdp_from_city' => $fromCity ,'bdp_to_city' =>$toCity,'bdp_pickup_date'=>$pickupDate,'bdp_source'=>$source,'bdp_vehicle_type_id'=>$cabType]);
			$count		 = ($model->bdp_count == null) ? 0 : $model->bdp_count;
			if (!$model)
			{
				$model						 = new BookingsDataPickup();
				$model->bdp_count			 = $count;
				$model->bdp_pickup_date		 = $bkgModel->bkg_pickup_date;
				$model->bdp_pickup_week		 = date("W", strtotime($bkgModel->bkg_pickup_date));
				$model->bdp_from_city		 = $fromCity;
				$model->bdp_to_city			 = $toCity;
				$model->bdp_source			 = $source;
				$model->bdp_vehicle_type_id	 = $cabType;
			}
			else
			{
				$model->bdp_count			 = $count + 1;
				$model->bdp_vehicle_type_id	 = $cabType;
			}
			$model->save();
		}
	}
}
