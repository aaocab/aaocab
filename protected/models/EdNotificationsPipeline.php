<?php

/**
 * This is the model class for table "ed_notifications_pipeline".
 *
 * The followings are the available columns in table 'ed_notifications_pipeline':
 * @property string $edp_id
 * @property string $edp_edt_id
 * @property string $edp_variable
 * @property integer $edp_send_to_id
 * @property integer $edp_sending_status
 * @property string $edp_scheduled_datetime
 * @property string $edp_sent_datetime
 * @property integer $edp_priority
 * @property string $edp_created_date
 * @property string $edp_modified_date
 * @property integer $edp_last_modified_by
 */
class EdNotificationsPipeline extends CActiveRecord
{

	const TYPE_HIGH_LEVEL_PRIORITY	 = 1;
	const TYPE_MEDIUM_LEVEL_PRIORITY	 = 2;
	const TYPE_LOW_LEVEL_PRIORITY		 = 3;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ed_notifications_pipeline';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('edp_edt_id, edp_scheduled_datetime', 'required'),
			array('edp_sending_status, edp_priority', 'numerical', 'integerOnly' => true),
			array('edp_edt_id', 'length', 'max' => 10),
			array('edp_variable,edp_send_to_id, edp_sent_datetime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('edp_id,edp_created_date,edp_modified_date,edp_last_modified_by, edp_edt_id, edp_variable, edp_send_to_id, edp_sending_status, edp_scheduled_datetime, edp_sent_datetime, edp_priority', 'safe', 'on' => 'search'),
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
			'edpTemplates' => array(self::BELONGS_TO, 'ed_notifications_templates', 'edp_edt_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'edp_id'				 => 'Edp',
			'edp_edt_id'			 => 'Edp Edt',
			'edp_variable'			 => 'Edp Variable',
			'edp_send_to_id'		 => 'Edp Send To',
			'edp_sending_status'	 => 'Edp Sending Status',
			'edp_scheduled_datetime' => 'Edp Scheduled Datetime',
			'edp_sent_datetime'		 => 'Edp Sent Datetime',
			'edp_priority'			 => 'Edp Priority',
			'edp_created_date'		 => 'Edp Created Date',
			'edp_modified_date'		 => 'Edp Modified_Date',
			'edp_last_modified_by'	 => 'Edp Last Modified By'
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

		$criteria->compare('edp_id', $this->edp_id, true);
		$criteria->compare('edp_edt_id', $this->edp_edt_id, true);
		$criteria->compare('edp_variable', $this->edp_variable, true);
		$criteria->compare('edp_send_to_id', $this->edp_send_to_id);
		$criteria->compare('edp_sending_status', $this->edp_sending_status);
		$criteria->compare('edp_scheduled_datetime', $this->edp_scheduled_datetime, true);
		$criteria->compare('edp_sent_datetime', $this->edp_sent_datetime, true);
		$criteria->compare('edp_priority', $this->edp_priority);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EdNotificationsPipeline the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used to insert notification in a priority wise based on template style
	 * @param type $priority
	 * @param type $templateId
	 * @param type $variables = [[0 => "test1", 1 => "ddd"],[0 => "test2", 1 => "ccc"]];
	 * @return type arrays
	 * @throws Exception 
	 */
	public static function create($priority = 1, $templateId, $variables = [], $dateTime = NULL, $entityIds = [])
	{
		$returnSet	 = new ReturnSet();
		$mapArray	 = array();
		try
		{
			if (empty($templateId) || empty($variables))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			$templateModel		 = EdNotificationsTemplates::model()->findByPk($templateId);
			$templateVariable	 = JSON_DECODE($templateModel->edt_variables);
			$arrayVariable		 = $variables;
			foreach ($arrayVariable as $variable)
			{
				$mapArray[] = ARRAY_COMBINE($templateVariable, $variable);
			}
			$model							 = new EdNotificationsPipeline();
			$model->edp_priority			 = $priority;
			$model->edp_edt_id				 = $templateId;
			$model->edp_send_to_id			 = JSON_ENCODE($entityIds);
			$model->edp_last_modified_by	 = UserInfo::getUserId();
			$model->edp_variable			 = JSON_ENCODE($mapArray);
			$model->edp_sending_status		 = 1;
			$model->edp_scheduled_datetime	 = ($dateTime == NULL) ? self::setDateOnPriority($model) : $dateTime;
			if (!$model->save())
			{
				throw new Exception(JSON_ENCODE($model->getErrors()), ReturnSet::ERROR_INVALID_DATA);
			}
			else
			{
				$returnSet->setData($model->edp_edt_id);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used to send notification in a batch wise manner through cron
	 */
	public static function sendNotification()
	{
		$sql = "SELECT edp_id,edt_title,edt_body,edp_send_to_id,edp_variable,edt_event_code
				FROM   ed_notifications_pipeline
				INNER JOIN ed_notifications_templates ON edt_id = edp_edt_id
				WHERE  edp_scheduled_datetime <= NOW() 
				AND edp_sending_status = 1 AND edp_sent_datetime IS NULL ORDER BY edp_priority, edp_scheduled_datetime";
		$res = DBUtil::query($sql, DBUtil::SDB());
		foreach ($res as $row)
		{
			$notificationId	 = $row['edp_id'];
			$title			 = $row['edt_title'];
			$body			 = $row['edt_body'];
			$entityIds		 = JSON_DECODE($row['edp_send_to_id'], TRUE); // need to discuss on this
			$data			 = ['EventCode' => $row['edt_event_code']]; // NEED to discuss on this
			$array_variable	 = JSON_DECODE($row['edp_variable'], TRUE);
			$combinedArray	 = ARRAY_COMBINE($entityIds, $array_variable);
			foreach ($combinedArray AS $entityId => $mapValue)
			{
				$message = strtr($body, $mapValue);
				$success = AppTokens::model()->notifyEntity($entityId, UserInfo::TYPE_VENDOR, $data, $message, $title);
			}
			if ($success)
			{
				$model						 = EdNotificationsPipeline::model()->findByPk($notificationId);
				$model->edp_sent_datetime	 = DBUtil::getCurrentTime();
				$model->edp_sending_status	 = 2;
				$model->save();
			}
		}
	}

	public static function setDateOnPriority($model)
	{

		switch ($model->edp_priority)
		{
			case self::TYPE_HIGH_LEVEL_PRIORITY:
				$dateTime	 = DBUtil::getCurrentTime();
				break;
			case self::TYPE_MEDIUM_LEVEL_PRIORITY:
				$dateTime	 = new CDbExpression('DATE_ADD(NOW(), INTERVAL ' . 1 . ' HOUR)');
				break;
			case self::TYPE_LOW_LEVEL_PRIORITY:
				$dateTime	 = new CDbExpression('DATE_ADD(NOW(), INTERVAL ' . 2 . ' HOUR)');
				break;
			default:
				$dateTime	 = new CDbExpression('DATE_ADD(NOW(), INTERVAL ' . 3 . ' HOUR)');
				break;
		}
		return $dateTime;
	}

}
