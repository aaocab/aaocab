<?php

/**
 * This is the model class for table "ed_notifications_templates".
 *
 * The followings are the available columns in table 'ed_notifications_templates':
 * @property string $edt_id
 * @property string $edt_title
 * @property string $edt_body
 * @property string $edt_variables
 * @property string $edt_created_date
 * @property string $edt_modified_date
 * @property integer $edt_last_modified_by
 * @property integer $edt_event_code
 * @property integer $edt_active
 */
class EdNotificationsTemplates extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ed_notifications_templates';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('edt_title, edt_active', 'required'),
			array('edt_active', 'numerical', 'integerOnly' => true),
			array('edt_title', 'length', 'max' => 255),
			array('edt_body, edt_variables', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('edt_id,edt_created_date,edt_event_code,edt_modified_date,edt_last_modified_by, edt_title, edt_body, edt_variables, edt_active', 'safe', 'on' => 'search'),
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
			'edt_id'				 => 'Edt',
			'edt_title'				 => 'Edt Title',
			'edt_body'				 => 'Edt Body',
			'edt_variables'			 => 'Edt Variables',
			'edt_created_date'		 => 'Edt Created Date',
			'edt_modified_date'		 => 'Edt Modified_Date',
			'edt_last_modified_by'	 => 'Edt Last Modified By',
			'edt_active'			 => 'Edt Active',
			'edt_event_code'         => 'Edt Event Code'
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

		$criteria->compare('edt_id', $this->edt_id, true);
		$criteria->compare('edt_title', $this->edt_title, true);
		$criteria->compare('edt_body', $this->edt_body, true);
		$criteria->compare('edt_variables', $this->edt_variables, true);
		$criteria->compare('edt_active', $this->edt_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EdNotificationsTemplates the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used to create notification template Data
	 * @param type $subject
	 * @param type $body
	 * @param type $templateVariable = [0 => "drvName", 1 => "drvCode"];
	 * @return type
	 * @throws Exception
	 */
	public static function create($subject = NULL, $body = NULL, $templateVariables = [], $eventCode = 1)
	{
		$returnSet = new ReturnSet();
		try
		{
			//$templateVariable = [0 => "vndNmae", 1 => "vndCode"];
			if (empty($subject) || empty($body) || empty($templateVariables))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			$model						 = new EdNotificationsTemplates();
			$model->edt_title			 = $subject;
			$model->edt_body			 = $body;
			$model->edt_variables		 = json_encode($templateVariables);
			$model->edt_last_modified_by = UserInfo::getUserId();
			$model->edt_event_code		 = $eventCode;	
			$model->edt_active			 = 1;
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_INVALID_DATA);
			}
			else
			{
				$returnSet->setData($model->edt_id);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

}
