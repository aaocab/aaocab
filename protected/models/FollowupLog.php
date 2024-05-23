<?php

/**
 * This is the model class for table "followup_log".
 *
 * The followings are the available columns in table 'followup_log':
 * @property string $fpl_id
 * @property integer $fpl_fwp_id
 * @property string $fpl_remarks
 * @property integer $fpl_event_id
 * @property integer $fpl_user_type
 * @property string $fpl_user_id
 * @property string $fpl_create_date
 * @property integer $fpl_active
 */
class FollowupLog extends CActiveRecord
{

	CONST AUTO_ASSIGNED		 = 1;
	CONST MANUALLY_ASSIGNED	 = 2;
	CONST FOLLOWUP_TRANSFER	 = 3;
	CONST FOLLOWUP_COMPLETE	 = 4;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'followup_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fpl_fwp_id, fpl_event_id, fpl_user_type, fpl_user_id, fpl_create_date, fpl_active', 'required'),
			array('fpl_fwp_id, fpl_event_id, fpl_user_type, fpl_active', 'numerical', 'integerOnly' => true),
			['fpl_event_id', 'validateEvent', 'on' => 'followAdd'],
			array('fpl_remarks', 'length', 'max' => 255),
			array('fpl_user_id', 'length', 'max' => 10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('fpl_id, fpl_fwp_id, fpl_remarks, fpl_event_id, fpl_user_type, fpl_user_id, fpl_create_date, fpl_active', 'safe', 'on' => 'search'),
		);
	}

	public function validateEvent($attribute, $params)
	{
		$event_id = $this->fpl_event_id;
		if (!$event_id || trim($event_id) == ''  )
		{
			$this->addError($attribute, 'Select event');
			return false;
		}
		return true;
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
			'fpl_id'			 => 'Fpl',
			'fpl_fwp_id'		 => 'Fpl Fwp',
			'fpl_remarks'		 => 'Remarks',
			'fpl_event_id'		 => 'Fpl Event',
			'fpl_user_type'		 => 'Fpl User Type',
			'fpl_user_id'		 => 'Fpl User',
			'fpl_create_date'	 => 'Fpl Create Date',
			'fpl_active'		 => 'Fpl Active',
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

		$criteria->compare('fpl_id', $this->fpl_id, true);
		$criteria->compare('fpl_fwp_id', $this->fpl_fwp_id);
		$criteria->compare('fpl_remarks', $this->fpl_remarks, true);
		$criteria->compare('fpl_event_id', $this->fpl_event_id);
		$criteria->compare('fpl_user_type', $this->fpl_user_type);
		$criteria->compare('fpl_user_id', $this->fpl_user_id, true);
		$criteria->compare('fpl_create_date', $this->fpl_create_date, true);
		$criteria->compare('fpl_active', $this->fpl_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FollowupLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used for adding followup Log
	 * @param type $refId
	 * @param type $eventId
	 * @param type $csr
	 * @return \FollowupLog
	 * @throws Exception
	 */
	public static function create($refId, $eventId, $csr, $remarks = null)
	{
		$model					 = new FollowupLog();
		$model->fpl_fwp_id		 = $refId;
		$model->fpl_user_id		 = $csr;
		$model->fpl_user_type	 = UserInfo::getUserType();
		$model->fpl_remarks		 = $remarks;
		$model->fpl_create_date	 = new CDbExpression('now()');
		$model->fpl_event_id	 = $eventId;
		$model->fpl_active		 = '1';
		if (!$model->save())
		{
			Logger::trace("FollowupLog not save : " . json_encode($model->getErrors()));
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		return $model;
	}

	/**
	 * This function is used for getting log details
	 * @param type (int)$id
	 * @return type (array) 
	 */
	public static function getLogDetails($id)
	{
		$params = [
			'id' => $id,
		];

		$sql			 = "SELECT fpl_event_id,fpl_user_id,fpl_remarks,fpl_create_date,adm_fname,adm_lname
        FROM followup_log fpl
        INNER JOIN admins adm ON  fpl.fpl_user_id = adm.adm_id  
        WHERE fpl_fwp_id = $id AND fpl_active = 1";
		$getCount		 = "SELECT count(1)  FROM followup_log  WHERE fpl_fwp_id =:id AND fpl_active=1";
		$count			 = DBUtil::command($getCount, DBUtil::SDB())->queryScalar($params);
		$dataprovider	 = new CSqlDataProvider($sql, array(
			"totalItemCount" => $count,
			'db'			 => DBUtil::SDB(),
			"pagination"	 => array("pageSize" => 10),
			'sort'			 => array('defaultOrder' => 'fpl_id ASC')
		));
		return $dataprovider;
	}

	/*
	 * This function is used for getting ecvent list in array format
	 */

	public static function getEventList()
	{
		$events = [
			1	 => 'Auto Assigned',
			2	 => 'Manually Assigned',
			3	 => 'FollowUp Transfer',
			4	 => 'FollowUp Completed',
			5	 => 'FollowUp Reschedule'
		];
		return $events;
	}

	public static function getEventList_v1()
	{
		$events = [
			1	 => 'Auto Assigned',
			2	 => 'Manually Assigned',
			3	 => 'FollowUp Transfer',
			4	 => 'FollowUp Completed'
		];
		return $events;
	}

}
