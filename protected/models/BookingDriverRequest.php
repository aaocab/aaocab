<?php

/**
 * This is the model class for table "booking_driver_request".
 *
 * The followings are the available columns in table 'booking_driver_request':
 * @property integer $bdr_id
 * @property integer $bdr_booking_id
 * @property integer $bdr_bcb_id
 * @property integer $bdr_driver_id
 * @property integer $bdr_bid_amount
 * @property string $bdr_created_at
 * @property integer $bdr_accepted
 * @property string $bdr_accepted_at
 * @property integer $bdr_assigned
 * @property string $bdr_assigned_at
 * @property string $bdr_last_reminded_at
 * @property integer $bdr_app_notification
 * @property integer $bdr_sms_notification
 
 * @property string $bdr_special_remarks
 * @property integer $bdr_is_gozonow
 * @property integer $bdr_notification_sent
 * @property integer $bdr_deny_reason_id
 * @property string $bdr_snooze_time
 * @property integer $bdr_active
 */
class BookingDriverRequest extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_driver_request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bdr_booking_id, bdr_driver_id', 'required'),
			array('bdr_booking_id, bdr_bcb_id, bdr_driver_id,bdr_bid_amount, bdr_accepted, bdr_assigned, bdr_app_notification, bdr_sms_notification, bdr_is_gozonow, bdr_notification_sent, bdr_deny_reason_id, bdr_active', 'numerical', 'integerOnly'=>true),
			
			array('bdr_special_remarks', 'length', 'max'=>255),
			array('bdr_accepted_at, bdr_assigned_at, bdr_last_reminded_at, bdr_snooze_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bdr_id, bdr_booking_id, bdr_bcb_id, bdr_driver_id,bdr_bid_amount, bdr_created_at, bdr_accepted, bdr_accepted_at, bdr_assigned, bdr_assigned_at, bdr_last_reminded_at, bdr_app_notification, bdr_sms_notification, bdr_special_remarks, bdr_is_gozonow, bdr_notification_sent, bdr_deny_reason_id, bdr_snooze_time, bdr_active', 'safe', 'on'=>'search'),
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
			'bdr_id' => 'Bdr',
			'bdr_booking_id' => 'Bdr Booking',
			'bdr_bcb_id' => 'Bdr Bcb',
			'bdr_driver_id' => 'Bdr Driver',
			'bdr_bid_amount' => 'Bdr Bid Amount',
			'bdr_created_at' => 'Bdr Created At',
			'bdr_accepted' => 'Bdr Accepted',
			'bdr_accepted_at' => 'Bdr Accepted At',
			'bdr_assigned' => 'Bdr Assigned',
			'bdr_assigned_at' => 'Bdr Assigned At',
			'bdr_last_reminded_at' => 'Bdr Last Reminded At',
			'bdr_app_notification' => 'Bdr App Notification',
			'bdr_sms_notification' => 'Bdr Sms Notification',
			'bdr_special_remarks' => 'Bdr Special Remarks',
			'bdr_is_gozonow' => 'Bdr Is Gozonow',
			'bdr_notification_sent' => 'Bdr Notification Sent',
			'bdr_deny_reason_id' => 'Bdr Deny Reason',
			'bdr_snooze_time' => 'Bdr Snooze Time',
			'bdr_active' => 'Bdr Active',
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

		$criteria->compare('bdr_id',$this->bdr_id);
		$criteria->compare('bdr_booking_id',$this->bdr_booking_id);
		$criteria->compare('bdr_bcb_id',$this->bdr_bcb_id);
		$criteria->compare('bdr_driver_id',$this->bdr_driver_id);
		$criteria->compare('bdr_bid_amount',$this->bdr_bid_amount);
		$criteria->compare('bdr_created_at',$this->bdr_created_at,true);
		$criteria->compare('bdr_accepted',$this->bdr_accepted);
		$criteria->compare('bdr_accepted_at',$this->bdr_accepted_at,true);
		$criteria->compare('bdr_assigned',$this->bdr_assigned);
		$criteria->compare('bdr_assigned_at',$this->bdr_assigned_at,true);
		$criteria->compare('bdr_last_reminded_at',$this->bdr_last_reminded_at,true);
		$criteria->compare('bdr_app_notification',$this->bdr_app_notification);
		$criteria->compare('bdr_sms_notification',$this->bdr_sms_notification);
		$criteria->compare('bdr_special_remarks',$this->bdr_special_remarks,true);
		$criteria->compare('bdr_is_gozonow',$this->bdr_is_gozonow);
		$criteria->compare('bdr_notification_sent',$this->bdr_notification_sent);
		$criteria->compare('bdr_deny_reason_id',$this->bdr_deny_reason_id);
		$criteria->compare('bdr_snooze_time',$this->bdr_snooze_time,true);
		$criteria->compare('bdr_active',$this->bdr_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingDriverRequest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/**
	 * add bid amount in booking driver request table
	 * @param array $params
	 * @param int $driverId
	 * @return boolean
	 * @throws Exception
	 */
	public static function storeBidRequest($params, $driverId)
	{
		$success	 = true;
		$bcbId		 = $params['tripId'];
		$bidAmount	 = (isset($params['bidAmount'])) ? $params['bidAmount'] : 0;
		$isAccept	 = $params['isAccept'];
		$bkgId		 = $params['bkgId'];
		$reasonId	 = $params['reasonId'] | 0;
		$isGozoNow   = $params['isGozoNow'];

		$transaction = DBUtil::beginTransaction();
		try
		{
			$model = BookingDriverRequest::model()->find('bdr_bcb_id=:bcbId AND bdr_driver_id=:driverId AND bdr_active=1', ['bcbId' => $bcbId, 'driverId' => $driverId]);
			if (!$model)
			{
				$model = new BookingDriverRequest();
			}
			
			$model->bdr_booking_id	 = (int) $bkgId;
			$model->bdr_bcb_id		 = $bcbId;
			$model->bdr_driver_id	 = $driverId;
			$model->bdr_assigned	 = 0;
			$model->bdr_is_gozonow	 = $isGozoNow;

			if ($isAccept)
			{
				$model->bdr_bid_amount	 = $bidAmount;
				$model->bdr_accepted	 = 1;
			}
			else
			{
				$model->bdr_accepted = 2;
				$model->bdr_assigned = 2;
				$model->bdr_deny_reason_id	 = $reasonId;

				$model->bdr_assigned_at = new CDbExpression('NOW()');
			}
			$model->bdr_accepted_at = new CDbExpression('NOW()');

			if (!$model->save())
			{
				throw new Exception('Bid request not set');
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			throw new Exception($e->getMessage(), $e->getCode());
			$success = false;
		}
		return $success;
	}

}
