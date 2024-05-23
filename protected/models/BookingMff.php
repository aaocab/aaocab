<?php

/**
 * This is the model class for table "booking_mff".
 *
 * The followings are the available columns in table 'booking_mff':
 * @property integer $bmf_id
 * @property string $bmf_booking_id
 * @property string $bmf_pickup_cordinator
 * @property string $bmf_created
 * @property string $bmf_log
 * @property integer $bmf_status
 */
class BookingMff extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_mff';
	}

	public $vendor_id, $to_zone_id;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bmf_booking_id,bmf_pickup_cordinator', 'required', 'on' => 'change_cordinator'),
			array('bmf_booking_id', 'length', 'max' => 250),
			array('bmf_pickup_cordinator', 'length', 'max' => 500),
			array('bmf_log', 'length', 'max' => 800),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bmf_id, bmf_booking_id, bmf_pickup_cordinator, bmf_created, bmf_log,bmf_status', 'safe'),
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
			'bmf_id'				 => 'Bmf',
			'bmf_booking_id'		 => 'Booking Id',
			'bmf_pickup_cordinator'	 => 'Pickup Coordinator',
			'bmf_created'			 => 'Created',
			'bmf_status'			 => 'Status',
			'bmf_log'				 => 'Log',
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

		$criteria->compare('bmf_id', $this->bmf_id);
		$criteria->compare('bmf_booking_id', $this->bmf_booking_id, true);
		$criteria->compare('bmf_pickup_cordinator', $this->bmf_pickup_cordinator, true);
		$criteria->compare('bmf_created', $this->bmf_created, true);
		$criteria->compare('bmf_log', $this->bmf_log, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingMff the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getStatus($status = '')
	{
		$arrStatus = [1 => 'Pickup Pending', 2 => 'Car Ready', 3 => 'Picked Up', 4 => 'Reached', 5 => 'Customer Not Ready'];
		if ($status != '')
		{
			return $arrStatus[$status];
		}
		return $arrStatus;
	}

}
