<?php

/**
 * This is the model class for table "booking_penalties".
 *
 * The followings are the available columns in table 'booking_penalties':
 * @property integer $penaltyId
 * @property integer $vendorId
 * @property integer $penaltyReasonId
 * @property double $penaltyAmount
 * @property integer $bookingId
 * @property string $date
 */
class BookingPenalties extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_penalties';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vendorId, penaltyReasonId, penaltyAmount, bookingId, date', 'required'),
			array('vendorId, penaltyReasonId, bookingId', 'numerical', 'integerOnly'=>true),
			array('penaltyAmount', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('penaltyId, vendorId, penaltyReasonId, penaltyAmount, bookingId, date', 'safe', 'on'=>'search'),
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
			'penaltyId' => 'Penalty',
			'vendorId' => 'Vendor',
			'penaltyReasonId' => 'Penalty Reason',
			'penaltyAmount' => 'Penalty Amount',
			'bookingId' => 'Booking',
			'date' => 'Date',
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

		$criteria->compare('penaltyId',$this->penaltyId);
		$criteria->compare('vendorId',$this->vendorId);
		$criteria->compare('penaltyReasonId',$this->penaltyReasonId);
		$criteria->compare('penaltyAmount',$this->penaltyAmount);
		$criteria->compare('bookingId',$this->bookingId);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingPenalties the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
