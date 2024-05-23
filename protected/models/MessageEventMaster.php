<?php

use components\Event\Events;

/**
 * This is the model class for table "message_event_master".
 *
 * The followings are the available columns in table 'message_event_master':
 * @property string $mem_id
 * @property string $mem_name
 * @property string $mem_desc
 * @property string $mem_sequence
 * @property string $mem_schedule_sequence
 * @property string $mem_module
 * @property string $mem_created_at
 * @property string $mem_modified_at
 * @property integer $mem_status
 * @property integer $mem_schedule_mintue

 */
class MessageEventMaster extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'message_event_master';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mem_name, mem_module, mem_created_at, mem_modified_at', 'required'),
			array('mem_status', 'numerical', 'integerOnly' => true),
			array('mem_name', 'length', 'max' => 500),
			array('mem_desc', 'length', 'max' => 5000),
			array('mem_module', 'length', 'max' => 100),
			array('mem_sequence', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('mem_id, mem_name, mem_desc, mem_sequence,mem_schedule_sequence, mem_module, mem_created_at, mem_modified_at, mem_status,mem_schedule_mintue', 'safe', 'on' => 'search'),
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
			'mem_id'				 => 'Evm',
			'mem_name'				 => 'Event Name',
			'mem_desc'				 => 'Event Description',
			'mem_sequence'			 => 'Sequence:whatsapp,App Notification,Email,SMS',
			'mem_schedule_sequence'	 => 'Sequence Schedule',
			'mem_module'			 => '0=>General,1=> Booking,2=>User ,3 =>Vendor,4=>Driver,5=>agent ',
			'mem_created_at'		 => 'when this row was created',
			'mem_modified_at'		 => 'Evm Modified At',
			'mem_status'			 => '1=> active,0=>inactive ',
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

		$criteria->compare('mem_id', $this->mem_id, true);
		$criteria->compare('mem_name', $this->mem_name, true);
		$criteria->compare('mem_desc', $this->mem_desc, true);
		$criteria->compare('mem_sequence', $this->mem_sequence, true);
		$criteria->compare('mem_schedule_sequence', $this->mem_schedule_sequence, true);
		$criteria->compare('mem_module', $this->mem_module, true);
		$criteria->compare('mem_created_at', $this->mem_created_at, true);
		$criteria->compare('mem_modified_at', $this->mem_modified_at, true);
		$criteria->compare('mem_status', $this->mem_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EventMaster the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used to get all the details regarding event master by event id
	 * @param string $eventId
	 * @return queryRow
	 */
	public static function getDetails($eventId)
	{
		$sql = "SELECT 
			mem_id,
			mem_name,
			mem_desc,
			mem_module,
			mem_sequence,
			mem_status,
			mem_schedule_sequence,
			mem_schedule_mintue
			FROM `message_event_master`
			WHERE 1
			AND message_event_master.mem_id=:eventId
			AND message_event_master.mem_status=1";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['eventId' => $eventId]);
	}

	/**
	 * This function is used to process the sequence Object ex {"0":"WhatSapp", "1":"SMS", "2":"Email"} 
	 * @param string $sequence
	 * @return boolean
	 */
	public static function processPlatformSequence($seqObject, $data, $contentParams = [], $receiverParams = null, $eventScheduleParams = null)
	{
		$response	 = ['success' => false, 'type' => 0];
		$returnSet	 = new ReturnSet();
		foreach ($seqObject as $seq)
		{
			if ($eventScheduleParams == null || $eventScheduleParams->event_sequence == null || $seq == $eventScheduleParams->event_sequence)
			{
				switch ($seq)
				{
					case TemplateMaster::SEQ_WHATSAPP_CODE:
						$templateObj = $data->template[TemplateMaster::SEQ_WHATSAPP_CODE];
						foreach ($templateObj as $obj)
						{

							if (!empty($contentParams['extraData']) && is_array($contentParams['extraData']))
							{
								$extraData				 = $contentParams['extraData'];
								$receiverParams->ext	 = $contentParams['extraData'][TemplateMaster::SEQ_WHATSAPP_CODE]['country_code'];
								$receiverParams->number	 = $contentParams['extraData'][TemplateMaster::SEQ_WHATSAPP_CODE]['phone'];
								if (!Filter::processPhoneNumber($receiverParams->number, $receiverParams->ext))
								{
									$returnSet->setStatus(false);
									$returnSet->setData(['type' => TemplateMaster::SEQ_WHATSAPP_CODE]);
								}
								else
								{
									$returnSet = WhatsappLog::process($obj, $contentParams, $receiverParams, $eventScheduleParams);
								}
							}
							else
							{
								$returnSet = WhatsappLog::process($obj, $contentParams, $receiverParams, $eventScheduleParams);
							}
						}
						break;
					case TemplateMaster::SEQ_SMS_CODE:
						$templateObj = $data->template[TemplateMaster::SEQ_SMS_CODE];
						foreach ($templateObj as $obj)
						{
							if (!empty($contentParams['extraData']) && is_array($contentParams['extraData']))
							{
								$extraData				 = $contentParams['extraData'];
								$receiverParams->ext	 = $contentParams['extraData'][TemplateMaster::SEQ_SMS_CODE]['country_code'];
								$receiverParams->number	 = $contentParams['extraData'][TemplateMaster::SEQ_SMS_CODE]['phone'];
								if (!Filter::processPhoneNumber($receiverParams->number, $receiverParams->ext))
								{
									$returnSet->setStatus(false);
									$returnSet->setData(['type' => TemplateMaster::SEQ_SMS_CODE]);
								}
								else
								{
									$message	 = TemplateMaster::prepareTemplate($obj, $contentParams);
									$returnSet	 = smsWrapper::process($obj, $message, $contentParams, $receiverParams, $eventScheduleParams);
								}
							}
							else
							{
								$message	 = TemplateMaster::prepareTemplate($obj, $contentParams);
								$returnSet	 = smsWrapper::process($obj, $message, $contentParams, $receiverParams, $eventScheduleParams);
							}
						}
						break;
					case TemplateMaster::SEQ_EMAIL_CODE:
						$templateObj = $data->template[TemplateMaster::SEQ_EMAIL_CODE];
						foreach ($templateObj as $obj)
						{

							if (!empty($contentParams['extraData']) && is_array($contentParams['extraData']))
							{
								$extraData				 = $contentParams['extraData'];
								$receiverParams->email	 = $contentParams['extraData'][TemplateMaster::SEQ_EMAIL_CODE]['email'];
								if ($receiverParams->email == null || trim($receiverParams->email) == "")
								{
									$returnSet->setStatus(false);
									$returnSet->setData(['type' => TemplateMaster::SEQ_EMAIL_CODE]);
								}
								else
								{
									$message	 = TemplateMaster::prepareTemplate($obj, $contentParams);
									$returnSet	 = emailWrapper::process($obj, $message, $contentParams, $receiverParams, $eventScheduleParams);
								}
							}
							else
							{
								$message	 = TemplateMaster::prepareTemplate($obj, $contentParams);
								$returnSet	 = emailWrapper::process($obj, $message, $contentParams, $receiverParams, $eventScheduleParams);
							}
						}
						break;
					case TemplateMaster::SEQ_APP_CODE:
						$templateObj = $data->template[TemplateMaster::SEQ_APP_CODE];
						foreach ($templateObj as $obj)
						{
							$message	 = TemplateMaster::prepareTemplate($obj, $contentParams);
							$returnSet	 = notificationWrapper::process($obj, $message, $receiverParams, $eventScheduleParams);
						}
						break;
					default:
						$returnSet->setStatus(true);
						$returnSet->setData(['type' => 0]);
						break;
				}

				if ($returnSet->getStatus() && $returnSet->getData()['type'] > 0)
				{
					$response = ['success' => $returnSet->getStatus(), 'type' => $returnSet->getData()['type'], 'id' => $returnSet->getData()['id']];
					break;
				}
			}
		}
		return $response;
	}

	/** @param Beans\event\Event $data */
	public static function processPlatformSequences($eventId, $contentParams = [], $receiverParams = null, $eventScheduleParams = null)
	{
		$data		 = Events::getEventDetails($eventId, $eventScheduleParams->event_sequence);
		$response	 = [];
		$sequence	 = json_decode($data->event_sequence, $associative = true);
		foreach ($sequence as $seqObject)
		{
			$response[] = MessageEventMaster::processPlatformSequence($seqObject, $data, $contentParams, $receiverParams, $eventScheduleParams);
		}

		return $response;
	}

	/**
	 * This function is used to get all the details regarding event/Template master by event id
	 * @param string $eventId
	 * @return queryRow
	 */
	public static function eventMasterDetailsById($eventId)
	{
		$sql = "SELECT 
			mem_id,
			tpm_id,
			tpm_entity_type,
			tpm_content,
			tpm_name,
			tpm_language,
			mem_name,
			mem_sequence,
			mem_schedule_sequence,
			tpm_platform
			FROM `template_master`
			INNER JOIN message_event_master ON message_event_master.mem_id=template_master.tpm_mem_id
			WHERE 1
			AND message_event_master.mem_id=:eventId
			AND message_event_master.mem_status=1 
			AND template_master.tpm_status=1 
			GROUP BY mem_id,tpm_platform,tpm_language ";
		return DBUtil::query($sql, DBUtil::SDB(), ['eventId' => $eventId]);
	}

	/**
	 * This function is used to get all the sender code by event id
	 * @param string $eventId
	 * @return queryRow
	 */
	public static function getPlatformCode($eventId, $platform = null)
	{
		$params	 = [];
		$where	 = "";
		if ($platform != null)
		{
			$params['platform']	 = $platform;
			$where				 = " AND tpm_platform=:platform";
		}
		$params['eventId']	 = $eventId;
		$sql				 = "SELECT 
				DISTINCT tpm_platform
				FROM `template_master`
				  INNER JOIN message_event_master ON message_event_master.mem_id=template_master.tpm_mem_id
				WHERE 1
					AND message_event_master.mem_id=:eventId 
					AND message_event_master.mem_status=1 
					AND template_master.tpm_status=1 $where";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

}
