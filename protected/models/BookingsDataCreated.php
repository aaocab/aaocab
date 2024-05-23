<?php

/**
 * This is the model class for table "bookings_data_created".
 *
 * The followings are the available columns in table 'bookings_data_created':
 * @property integer $bdc_id
 * @property integer $bdc_source
 * @property integer $bdc_from_city
 * @property integer $bdc_to_city
 * @property integer $bdc_from_zone
 * @property integer $bdc_to_zone
 * @property integer $bdc_vehicle_type_id
 * @property string $bdc_create_date
 * @property integer $bdc_create_week
 * @property integer $bdc_count
 * @property integer $bdc_status
 */
class BookingsDataCreated extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bookings_data_created';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bdc_source', 'required'),
			array('bdc_source, bdc_from_city, bdc_to_city, bdc_vehicle_type_id, bdc_create_week, bdc_count, bdc_status', 'numerical', 'integerOnly'=>true),
			array('bdc_create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bdc_id, bdc_source, bdc_from_city, bdc_to_city, bdc_vehicle_type_id, bdc_create_date, bdc_create_week, bdc_count, bdc_status, bdc_from_zone, bdc_to_zone', 'safe', 'on'=>'search'),
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
			'bdc_id' => 'Bdc',
			'bdc_source' => '1 => User, 2 => Admin, 3 => App, 4=>Agent, 5=>Partner Spot',
			'bdc_from_city' => 'Bdc From City',
			'bdc_to_city' => 'Bdc To City',
			'bdc_from_zone' => 'Bdc From Zone',
			'bdc_to_zone' => 'Bdc To Zone',
			'bdc_vehicle_type_id' => 'Bdc Vehicle Type',
			'bdc_create_date' => 'Bdc Create Date',
			'bdc_create_week' => 'Bdc Create Week',
			'bdc_count' => 'Bdc Count',
			'bdc_status' => 'Bdc Status',
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

		$criteria->compare('bdc_id',$this->bdc_id);
		$criteria->compare('bdc_source',$this->bdc_source);
		$criteria->compare('bdc_from_city',$this->bdc_from_city);
		$criteria->compare('bdc_to_city',$this->bdc_to_city);
		$criteria->compare('bdc_from_zone',$this->bdc_from_zone);
		$criteria->compare('bdc_to_zone',$this->bdc_to_zone);
		$criteria->compare('bdc_vehicle_type_id',$this->bdc_vehicle_type_id);
		$criteria->compare('bdc_create_date',$this->bdc_create_date,true);
		$criteria->compare('bdc_create_week',$this->bdc_create_week);
		$criteria->compare('bdc_count',$this->bdc_count);
		$criteria->compare('bdc_status',$this->bdc_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingsDataCreated the static model class
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
			$createdDate = ($bkgModel->bkg_create_date == null) ? date('Y-m-d') : date('Y-m-d', strtotime($bkgModel->bkg_create_date));
			$source		 = $bkgModel->bkgTrail->bkg_platform;
			$cabType	 = $bkgModel->bkg_vehicle_type_id;
			$model       = $this::model()->find('bdc_from_city=:bdc_from_city AND bdc_to_city=:bdc_to_city AND bdc_create_date=:bdc_create_date AND bdc_source=:bdc_source AND bdc_vehicle_type_id=:bdc_vehicle_type_id ', ['bdc_from_city' => $fromCity ,'bdc_to_city' =>$toCity,'bdc_create_date'=>$createdDate,'bdc_source'=>$source,'bdc_vehicle_type_id'=>$cabType]);
			$count		 = ($model->bdc_count == null) ? 0 : $model->bdc_count;
			if (!$model)
			{
				$model						 = new BookingsDataCreated();
				$model->bdc_count			 = $count;
				$model->bdc_create_date		 = ($bkgModel->bkg_create_date == null) ? date('Y-m-d') : date('Y-m-d', strtotime($bkgModel->bkg_create_date));
				$model->bdc_create_week		 = date("W", strtotime($model->bdc_create_date));
				$model->bdc_from_city		 = $fromCity;
				$model->bdc_to_city			 = $toCity;
				$model->bdc_source			 = $source;
				$model->bdc_vehicle_type_id	 = $cabType;
			}
			else
			{
				$model->bdc_count			 = $count + 1;
				$model->bdc_vehicle_type_id	 = $cabType;
			}
			$model->save();
			BookingsDataPickup::model()->setData($bkgId);
		}
	}
}
