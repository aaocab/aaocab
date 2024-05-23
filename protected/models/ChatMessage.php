<?php

/**
 * This is the model class for table "chat_message".
 *
 * The followings are the available columns in table 'chat_message':
 * @property integer $message_id
 * @property integer $msg_entity_id
 * @property integer $msg_entity_type
 * @property string $message_start_date
 * @property string $message_last_date
 * @property integer $message_owner_id
 * @property integer $message_unread_count_for_admin
 * @property integer $message_active
 */
class ChatMessage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'chat_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('msg_entity_id, msg_entity_type, message_start_date, message_last_date', 'required'),
			array('msg_entity_id, msg_entity_type, message_owner_id, message_unread_count_for_admin, message_active', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('message_id, msg_entity_id, msg_entity_type, message_start_date, message_last_date, message_owner_id, message_unread_count_for_admin, message_active', 'safe', 'on'=>'search'),
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
			'message_id' => 'Message',
			'msg_entity_id' => 'Msg Entity',
			'msg_entity_type' => '0:Booking; 1:Consumer ; 2:Vendor ; 3:Driver ; 4:Admin ; 5:Agent',
			'message_start_date' => 'Message Start Date',
			'message_last_date' => 'Message Last Date',
			'message_owner_id' => 'Message Owner',
			'message_unread_count_for_admin' => 'Message Unread Count For Admin',
			'message_active' => 'Message Active',
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

		$criteria->compare('message_id',$this->message_id);
		$criteria->compare('msg_entity_id',$this->msg_entity_id);
		$criteria->compare('msg_entity_type',$this->msg_entity_type);
		$criteria->compare('message_start_date',$this->message_start_date,true);
		$criteria->compare('message_last_date',$this->message_last_date,true);
		$criteria->compare('message_owner_id',$this->message_owner_id);
		$criteria->compare('message_unread_count_for_admin',$this->message_unread_count_for_admin);
		$criteria->compare('message_active',$this->message_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ChatMessage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
