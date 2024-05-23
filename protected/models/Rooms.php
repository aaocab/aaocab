<?php

/**
 * This is the model class for table "rooms".
 *
 * The followings are the available columns in table 'rooms':
 * @property string $rom_id
 * @property string $rom_creator_ctt_id
 * @property integer $rom_creator_type_id
 * @property string $rom_resource
 * @property integer $rom_admin_join_count
 * @property integer $rom_last_event_id
 * @property integer $rom_chat_server_id
 * @property integer $rom_status
 * @property string $rom_created
 * @property string $rom_modified
 */
class Rooms extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rooms';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rom_creator_ctt_id', 'required'),
			array('rom_creator_type_id, rom_admin_join_count, rom_last_event_id, rom_status', 'numerical', 'integerOnly'=>true),
			array('rom_creator_ctt_id', 'length', 'max'=>10),
			array('rom_resource', 'length', 'max'=>200),
			array('rom_created, rom_modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rom_id, rom_creator_ctt_id, rom_creator_type_id, rom_resource, rom_admin_join_count, rom_last_event_id, rom_chat_server_id, rom_status, rom_created, rom_modified', 'safe', 'on'=>'search'),
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
			'rom_id' => 'Rom',
			'rom_creator_ctt_id' => 'Rom Creator Ctt',
			'rom_creator_type_id' => 'Rom Creator Type',
			'rom_resource' => 'Rom Resource',
			'rom_admin_join_count' => 'Rom Admin Join Count',
			'rom_last_event_id' => 'Rom Last Event',
			'rom_chat_server_id' => 'Rom Chat Server Id',
			'rom_status' => 'Rom Status',
			'rom_created' => 'Rom Created',
			'rom_modified' => 'Rom Modified',
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

		$criteria->compare('rom_id',$this->rom_id,true);
		$criteria->compare('rom_creator_ctt_id',$this->rom_creator_ctt_id,true);
		$criteria->compare('rom_creator_type_id',$this->rom_creator_type_id);
		$criteria->compare('rom_resource',$this->rom_resource,true);
		$criteria->compare('rom_admin_join_count',$this->rom_admin_join_count);
		$criteria->compare('rom_last_event_id',$this->rom_last_event_id);
		$criteria->compare('rom_status',$this->rom_status);
		$criteria->compare('rom_created',$this->rom_created,true);
		$criteria->compare('rom_modified',$this->rom_modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rooms the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function add($contactId, $type, $serverChatId)
	{
		$model = new Rooms();
		$model->rom_creator_ctt_id = $contactId;
		$model->rom_creator_type_id = $type;
		$model->rom_chat_server_id = $serverChatId;
		$model->rom_url = Yii::app()->baseUrl . "/bot/liveChat?" . base64_encode("chatId=$serverChatId");
		if(!$model->save())
		{
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_INVALID_DATA);
		}
		return $model->rom_url;
	}

	/**
	 * This function is used for processing the chat details
	 * @param type $contactId
	 * @param type $type
	 * @return int
	 */
	public static function processData($contactId, $type)
	{
		if(empty($contactId) || empty($type))
		{
			return 0;
		}

		$date = new DateTime();

		$cttModel	 = Contact::model()->findByPk($contactId);
		$userId		 = UserInfo::getUserId();
		$userModel	 = Users::model()->findByPk($userId);
		$name		 = $userModel->usr_name . " " . $userModel->usr_lname;

		$msgTemp = new stdClass();
		$msgTemp->msg = "Hello $name, Please type your queries. so that when our executive joins they can serve you better";
		$msgTemp->user_id = -1;
		$msgTemp->time = $date->getTimestamp();

		$arrMsg = [];
		array_push($arrMsg, $msgTemp);

		$field = new stdClass();
		$field->Username = $name;

		$additionalDataTemp = new stdClass();
		$additionalDataTemp->userContact = $contactId; // send hyperlink of mycall

		$arrAdditionalData = [];
		array_push($arrAdditionalData, $additionalDataTemp);
		
		$teamId = 0;
		switch ((int) $type)
		{
			case UserInfo::TYPE_CONSUMER:
				$teamId = Teams::getByRefType(ServiceCallQueue::TYPE_EXISTING_BOOKING);
				break;
			case UserInfo::TYPE_DRIVER:
			case UserInfo::TYPE_VENDOR:
				$teamId = Teams::getByRefType(ServiceCallQueue::TYPE_EXISTING_VENDOR);
				break;
		}
		
		if(!$teamId)
		{
			return 0;
		}
		$departmentIds = Teams::getCdtIdById($teamId, 2);
		$jsonData = new stdClass();
		$jsonData->ignore_required = true;
		$jsonData->ignore_required = true;
		$jsonData->ignore_bot = false;
		$jsonData->department = $departmentIds;
		$jsonData->fields = $field;
		$jsonData->additional_data = $arrAdditionalData;
		$jsonData->messages = $arrMsg;

		$response = Yii::app()->liveChat->createChat($jsonData);
		if(!$response["status"])
		{
			return 0;
		}

		$chatId = $response["message"]->result->id;
		$link = "";
		if($chatId > 0)
		{
			$link = self::add($contactId, $type, $chatId);
		}
		return $link;
	}
}
