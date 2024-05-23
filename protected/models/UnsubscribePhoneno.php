<?php

/**
 * This is the model class for table "unsubscribe_phoneno".
 *
 * The followings are the available columns in table 'unsubscribe_phoneno':
 * @property integer $upn_id
 * @property string $upn_mobile
 * @property integer $upn_event_id
 * @property string $upn_sent_date
 * @property integer $upn_source_type
 * @property integer $upn_is_whatsapp_verified
 * @property integer $upn_status
 * @property integer $upn_active
 * @property string $upn_created_at
 * @property string $upn_updated_at
 */
class UnsubscribePhoneno extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'unsubscribe_phoneno';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('upn_mobile', 'required'),
			array('upn_event_id, upn_source_type, upn_is_whatsapp_verified, upn_status, upn_active', 'numerical', 'integerOnly' => true),
			array('upn_mobile', 'length', 'max' => 20),
			array('upn_sent_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('upn_id, upn_mobile, upn_event_id, upn_sent_date, upn_source_type, upn_is_whatsapp_verified, upn_status, upn_active, upn_created_at, upn_updated_at', 'safe', 'on' => 'search'),
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
			'upn_id'					 => 'Id',
			'upn_mobile'				 => 'Mobile',
			'upn_event_id'				 => 'Event Id',
			'upn_sent_date'				 => 'Sent Date',
			'upn_source_type'			 => '1=> SMS, 2=>Whatsapp',
			'upn_is_whatsapp_verified'	 => '0=>Not Verified ,1 => Verified',
			'upn_status'				 => '1=>we cannot send msg ,0=>we can send msg',
			'upn_active'				 => '1=>active ,0=>Inactive',
			'upn_created_at'			 => 'Create Date',
			'upn_updated_at'			 => 'Update at',
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

		$criteria->compare('upn_id', $this->upn_id);
		$criteria->compare('upn_mobile', $this->upn_mobile, true);
		$criteria->compare('upn_event_id', $this->upn_event_id);
		$criteria->compare('upn_sent_date', $this->upn_sent_date, true);
		$criteria->compare('upn_source_type', $this->upn_source_type);
		$criteria->compare('upn_is_whatsapp_verified', $this->upn_is_whatsapp_verified);
		$criteria->compare('upn_status', $this->upn_status);
		$criteria->compare('upn_active', $this->upn_active);
		$criteria->compare('upn_created_at', $this->upn_created_at, true);
		$criteria->compare('upn_updated_at', $this->upn_updated_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UnsubscribePhoneno the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used to check whether mobile is blocked or not by source type
	 * @param $mobile.
	 * @return int
	 */
	public static function checkBlockedNumber($mobile, $sourceType = 1, $eventId = 0)
	{
		$params	 = ['mobile' => $mobile, 'sourceType' => $sourceType];
		$where	 = "";
		if ($eventId > 0)
		{
			$params['eventId']	 = $eventId;
			$where				 .= " AND upn_event_id =:eventId ";
		}
		$sql = "SELECT COUNT(1) cnt FROM unsubscribe_phoneno WHERE upn_mobile =:mobile AND upn_source_type=:sourceType AND upn_status=1 and upn_active=1 $where";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	/**
	 * This function is used to block the phone number for sending SMS/WHATSAPP
	 * @param $mobile.
	 * @return int
	 */
	public

	static function markUnSubscribe($phone, $sourceType, $eventId)
	{
		$model = self::model()->find('upn_mobile=:phone AND upn_source_type =:sourceType  AND upn_event_id=:eventId AND upn_active=1', ['phone' => $phone, 'sourceType' => $sourceType, 'eventId' => $eventId]);
		if ($model)
		{
			$model->upn_is_whatsapp_verified = $sourceType == 1 ? 0 : 1;
			$model->upn_status				 = 1;
			$model->upn_updated_at			 = DBUtil::getCurrentTime();
		}
		else
		{
			$model							 = new UnsubscribePhoneno();
			$model->upn_mobile				 = $phone;
			$model->upn_source_type			 = $sourceType;
			$model->upn_event_id			 = $eventId;
			$model->upn_is_whatsapp_verified = $sourceType == 1 ? 0 : 1;
			$model->upn_status				 = 1;
			$model->upn_sent_date			 = DBUtil::getCurrentTime();
			$model->upn_created_at			 = DBUtil::getCurrentTime();
			$model->upn_updated_at			 = DBUtil::getCurrentTime();
			$model->upn_active				 = 1;
		}
		$model->save();
	}

	/**
	 * This function is used to unblock the phone number for sending sms/whatsapp
	 * @param $mobile.
	 * @return int
	 */
	public static function markSubscribe($phone, $sourceType, $eventId)
	{
		$model = self::model()->find('upn_mobile=:phone AND upn_source_type =:sourceType AND upn_source_type =:sourceType AND upn_event_id=:eventId AND upn_active=1', ['phone' => $phone, 'sourceType' => $sourceType, 'eventId' => $eventId]);
		if ($model)
		{
			$model->upn_is_whatsapp_verified = $sourceType == 1 ? 0 : 1;
			$model->upn_status				 = 0;
			$model->upn_updated_at			 = DBUtil::getCurrentTime();
		}
		$model->save();
	}

}
