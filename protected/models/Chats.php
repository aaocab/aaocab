<?php

/**
 * This is the model class for table "chats".
 *
 * The followings are the available columns in table 'chats':
 * @property integer $cht_id
 * @property integer $cht_ref_id
 * @property integer $cht_ref_type
 * @property integer $cht_entity_id
 * @property integer $cht_entity_type
 * @property integer $cht_vendor_id
 * @property integer $cht_driver_id
 * @property integer $cht_admin_id
 * @property integer $cht_consumer_id
 * @property string $cht_start_date
 * @property string $cht_last_date
 * @property integer $cht_owner_id
 * @property integer $cht_unread_count_for_admin
 * @property integer $cht_status
 * @property integer $cht_active
 * 
 * The followings are the available model relations:
 * @property ChatLog[] $chatLogs
 */
class Chats extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'chats';
	}

	const REF_TYPE_BOOKING	 = 0;
	const REF_TYPE_ACCOUNTS	 = 1;

	public $refTypeList = [
		0	 => 'Booking',
		1	 => 'Accounts'
	];

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cht_ref_id, cht_ref_type, cht_start_date, cht_last_date', 'required'),
			array('cht_ref_id, cht_ref_type,cht_entity_id, cht_entity_type, cht_owner_id, cht_unread_count_for_admin, cht_status, cht_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cht_id, cht_ref_id, cht_ref_type,cht_entity_id, cht_entity_type, cht_start_date, cht_last_date, cht_owner_id, cht_unread_count_for_admin, cht_status, cht_active', 'safe', 'on' => 'search'),
			array('cht_id, cht_ref_id, cht_ref_type,cht_entity_id, cht_entity_type, cht_start_date, cht_last_date, cht_owner_id, cht_unread_count_for_admin, cht_status, cht_active', 'safe'),
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
			'chatLogs' => array(self::HAS_MANY, 'ChatLog', 'chl_cht_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cht_id'					 => 'Chat ID',
			'cht_entity_id'				 => 'Chat Entity',
			'cht_entity_type'			 => '0:Booking; 1:Consumer ; 2:Vendor ; 3:Driver ; 4:Admin ; 5:Agent',
			'cht_start_date'			 => 'Chat Start Date',
			'cht_last_date'				 => 'Chat Last Date',
			'cht_owner_id'				 => 'Chat Owner',
			'cht_unread_count_for_admin' => 'Chat Unread Count For Admin',
			'cht_status'				 => 'Chat Status',
			'cht_active'				 => 'Chat Active',
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

		$criteria->compare('cht_id', $this->cht_id);
		$criteria->compare('cht_ref_id', $this->cht_ref_id);
		$criteria->compare('cht_ref_type', $this->cht_ref_type);
		$criteria->compare('cht_entity_id', $this->cht_entity_id);
		$criteria->compare('cht_entity_type', $this->cht_entity_type);
		$criteria->compare('cht_start_date', $this->cht_start_date, true);
		$criteria->compare('cht_last_date', $this->cht_last_date, true);
		$criteria->compare('cht_owner_id', $this->cht_owner_id);
		$criteria->compare('cht_unread_count_for_admin', $this->cht_unread_count_for_admin);
		$criteria->compare('cht_status', $this->cht_status);
		$criteria->compare('cht_active', $this->cht_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Chats the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * updateOwner
	 */
	public function updateOwner_OLD($entityId, $entityType, $userInfo)
	{
		$user_id = $userInfo->userId;

		$sql = "SELECT count(1) as val FROM `chats` WHERE cht_entity_id = $entityId AND cht_entity_type = $entityType AND cht_active = 1";
		$val = DBUtil::command($sql)->queryScalar();

		if($val > 0)
		{
			$sql1 = "UPDATE chats SET cht_owner_id = $user_id WHERE cht_entity_id = $entityId AND cht_entity_type = $entityType AND cht_active = 1";
			return DBUtil::command($sql1)->execute();
		}
		else
		{
			$objChat							 = new Chats();
			$objChat->cht_ref_id				 = $entityId;
			$objChat->cht_ref_type				 = $entityType;
			$objChat->cht_start_date			 = date("Y-m-d H:i:s");
			$objChat->cht_last_date				 = date("Y-m-d H:i:s");
			$objChat->cht_owner_id				 = $user_id;
			$objChat->cht_unread_count_for_admin = 0;
			$objChat->cht_active				 = 1;
			$objChat->save();

			return true;
		}
	}

	/**
	 * updateOwner
	 */
	public function updateOwner($entityId, $entityType, $arrChatData)
	{
		$currDate = date("Y-m-d H:i:s");

		$chtId			 = $arrChatData['chtId'];
		$userId			 = $arrChatData['userId'];
		$ownerShipAct	 = $arrChatData['ownerShipAct'];

		$objChat = Chats::model()->findByPk($chtId);
		if($objChat)
		{
			$objChat->cht_owner_id = $userId;

			if($ownerShipAct == 3)
			{
				$objChat->cht_status = 0;
			}
			else
			{
				$objChat->cht_status = 1;
			}
			$objChat->save();

			return true;
		}
		else
		{
			$objChat							 = new Chats();
			$objChat->cht_ref_id				 = $entityId;
			$objChat->cht_ref_type				 = $entityType;
			$objChat->cht_start_date			 = $currDate;
			$objChat->cht_last_date				 = $currDate;
			$objChat->cht_owner_id				 = $userId;
			$objChat->cht_unread_count_for_admin = 0;
			$objChat->cht_status				 = 1;
			$objChat->cht_active				 = 1;
			$objChat->save();

			return true;
		}
	}

	/**
	 * chatDetails
	 */
	public function chatDetails($entityId, $entityType)
	{
		if($entityId > 0)
		{
			$sql = "SELECT booking.bkg_booking_id, chats.* FROM `chats` 
					LEFT JOIN `booking` ON booking.bkg_id = chats.cht_ref_id AND booking.bkg_active = 1 
					WHERE cht_ref_id = $entityId AND cht_ref_type = $entityType AND cht_active = 1";
			return DBUtil::queryRow($sql);
			#$sql = "SELECT booking.bkg_booking_id, chats.* FROM `booking` LEFT JOIN `chats` ON chats.cht_ref_id = booking.bkg_id AND chats.cht_active = 1 AND chats.cht_ref_id = $entityId AND chats.cht_ref_type = $entityType WHERE booking.bkg_id = $entityId";
			#return DBUtil::queryRow($sql);
		}
		else
		{
			return false;
		}
	}

	/**
	 * updateRoomMember
	 */
	public static function updateRoomMember($chtId, $entityId, $entityType)
	{


		$chatModel = Chats::model()->findByPk($chtId);

		switch($entityType)
		{
			case UserInfo::TYPE_ADMIN:
				$chatModel->cht_admin_id	 = $entityId;
				break;
			case UserInfo::TYPE_VENDOR:
				$chatModel->cht_vendor_id	 = $entityId;
				break;
			case UserInfo::TYPE_DRIVER:
				$chatModel->cht_driver_id	 = $entityId;
				break;
			case UserInfo::TYPE_CONSUMER:
				$chatModel->cht_consumer_id	 = $entityId;
				break;
		}
		return $chatModel->save();
	}
}
