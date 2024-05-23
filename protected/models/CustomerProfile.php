<?php

/**
 * This is the model class for table "customer_profile".
 *
 * The followings are the available columns in table 'customer_profile':
 * @property integer $csp_id
 * @property integer $csp_user_id
 * @property integer $csp_booking_id
 * @property integer $csp_attribute_type
 * @property string $csp_value_str
 * @property integer $csp_value_int
 * @property string $csp_created
 */
class CustomerProfile extends CActiveRecord
{

	const TYPE_BOOKINGS					 = 1; //Booking Created
	const TYPE_COMPLETIONS				 = 2; //Booking Completion
	const TYPE_CANCELLATIONS				 = 3; //Booking Cancellation
	const TYPE_REVIEW						 = 4; // Review Requested
	const TYPE_PAYMENTS					 = 5; //Payment Received
	const TYPE_CANCELLATION_TO_PICKUP		 = 6; //Cancellation time to pickup in (Hours)
	const TYPE_CANCELLATION_FROM_BOOKING	 = 7; //Cancellation time form booking in (Hours)
	const TYPE_PROBLEMS					 = 8; // Problem regarding booking 
	const TYPE_NO_SHOW					 = 9; // No show reason
	const TYPE_CITIES_LIST				 = 10; // Cities List

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customer_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('csp_user_id, csp_booking_id, csp_attribute_type, csp_value_int', 'numerical', 'integerOnly' => true),
			array('csp_value_str', 'length', 'max' => 1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('csp_id, csp_user_id, csp_booking_id, csp_attribute_type, csp_value_str, csp_value_int, csp_created', 'safe', 'on' => 'search'),
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
			'csp_id'			 => 'Csp',
			'csp_user_id'		 => 'Csp User',
			'csp_booking_id'	 => 'Csp Booking',
			'csp_attribute_type' => 'Csp Attribute Type',
			'csp_value_str'		 => 'Csp Value Str',
			'csp_value_int'		 => 'Csp Value Int',
			'csp_created'		 => 'Csp Created',
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

		$criteria->compare('csp_id', $this->csp_id);
		$criteria->compare('csp_user_id', $this->csp_user_id);
		$criteria->compare('csp_booking_id', $this->csp_booking_id);
		$criteria->compare('csp_attribute_type', $this->csp_attribute_type);
		$criteria->compare('csp_value_str', $this->csp_value_str, true);
		$criteria->compare('csp_value_int', $this->csp_value_int);
		$criteria->compare('csp_created', $this->csp_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CustomerProfile the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function setCustomerDetailsOnCancelBooking($bkid)
	{
		try
		{
			$model								 = Booking::model()->findByPk($bkid);
			$customerProfile					 = new CustomerProfile();
			$customerProfile->csp_user_id		 = $model->bkgUserInfo->bkg_user_id;
			$customerProfile->csp_booking_id	 = $model->bkg_id;
			$customerProfile->csp_attribute_type = $customerProfile::TYPE_CANCELLATIONS;
			$customerProfile->csp_value_str		 = $model->bkg_cancel_delete_reason;
			$customerProfile->csp_value_int		 = $model->bkg_cancel_id;
			$customerProfile->save();

			$customerProfile					 = new CustomerProfile();
			$pickupDate							 = $model->bkg_pickup_date;
			$dateDiff							 = round((strtotime($pickupDate) - strtotime(date('Y-m-d H:i:s'))) / (60 * 60));
			$customerProfile->csp_user_id		 = $model->bkgUserInfo->bkg_user_id;
			$customerProfile->csp_booking_id	 = $model->bkg_id;
			$customerProfile->csp_attribute_type = $customerProfile::TYPE_CANCELLATION_TO_PICKUP;
			$customerProfile->csp_value_str		 = 'Cancellation Times To Pickup';
			$customerProfile->csp_value_int		 = $dateDiff;
			$customerProfile->save();

			$customerProfile					 = new CustomerProfile();
			$createDate							 =$model->bkg_create_date;
			$dateTimeDiff						 = round((strtotime(date('Y-m-d H:i:s')) - strtotime($createDate)) / (60 * 60));
			$customerProfile->csp_user_id		 = $model->bkgUserInfo->bkg_user_id;
			$customerProfile->csp_booking_id	 = $model->bkg_id;
			$customerProfile->csp_attribute_type = $customerProfile::TYPE_CANCELLATION_FROM_BOOKING;
			$customerProfile->csp_value_str		 = 'Cancellation From Booking';
			$customerProfile->csp_value_int		 = $dateTimeDiff;
			$customerProfile->save();
			if($model->bkg_cancel_id == 25)
			{
				$customerProfile					 = new CustomerProfile();
				$customerProfile->csp_user_id		 = $model->bkgUserInfo->bkg_user_id;
				$customerProfile->csp_booking_id	 = $model->bkg_id;
				$customerProfile->csp_attribute_type = $customerProfile::TYPE_PROBLEMS;
				$customerProfile->csp_value_str		 = $model->bkg_cancel_delete_reason;
				$customerProfile->csp_value_int		 = $model->bkg_cancel_id;
				$customerProfile->save();

				$vendorId = Ratings::model()->getVendorIdByBookingId($model->bkg_id);
				VendorProfile::model()->setVendorDetailsOnCancelBooking($vendorId['vnd_id'], $bkid, 1);
			}
			if($model->bkg_cancel_id == 21)
			{
				$customerProfile					 = new CustomerProfile();
				$customerProfile->csp_user_id		 = $model->bkgUserInfo->bkg_user_id;
				$customerProfile->csp_booking_id	 = $model->bkg_id;
				$customerProfile->csp_attribute_type = $customerProfile::TYPE_NO_SHOW;
				$customerProfile->csp_value_str		 = $model->bkg_cancel_delete_reason;
				$customerProfile->csp_value_int		 = $model->bkg_cancel_id;
				$customerProfile->save();
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * 
	 * @param type $user_id
	 * @param type $booking_id
	 * @param type $attribute_type
	 * @param type $value_str
	 * @param type $value_int
	 * @return boolean
	 */
	public static function updateAttr($user_id, $booking_id, $attribute_type, $value_str, $value_int)
	{
		$success	 = false;
		$profiling	 = isset($GLOBALS['enableProfiling']) ? $GLOBALS['enableProfiling'] : true;
		if(!$profiling)
		{
			goto end;
		}
		if(!($user_id > 0 && $booking_id > 0))
		{
			goto end;
		}

		$customerProfile					 = new CustomerProfile();
		$customerProfile->csp_user_id		 = $user_id;
		$customerProfile->csp_booking_id	 = $booking_id;
		$customerProfile->csp_attribute_type = $attribute_type;
		$customerProfile->csp_value_str		 = $value_str;
		$customerProfile->csp_value_int		 = $value_int;
		$success							 = $customerProfile->save();

		end:
		return $success;
	}
}
