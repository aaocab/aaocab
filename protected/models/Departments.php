<?php

/**
 * This is the model class for table "departments".
 *
 * The followings are the available columns in table 'departments':
 * @property integer $dpt_id
 * @property string $dpt_name
 * @property integer $dpt_status
 * @property integer $dpt_chat_server_id
 * @property integer $dpt_chat_status
 * @property string $dpt_created
 * @property string $dpt_modified
 */
class Departments extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'departments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dpt_name, dpt_created, dpt_modified', 'required'),
			array('dpt_status', 'numerical', 'integerOnly'=>true),
			array('dpt_name', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dpt_id, dpt_name, dpt_status, dpt_created, dpt_modified', 'safe', 'on'=>'search'),
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
			'dpt_id' => 'Dpt',
			'dpt_name' => 'Dpt Name',
			'dpt_status' => 'Dpt Status',
			'dpt_created' => 'Dpt Created',
			'dpt_modified' => 'Dpt Modified',
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

		$criteria->compare('dpt_id', $this->dpt_id);
		$criteria->compare('dpt_name', $this->dpt_name, true);
		$criteria->compare('dpt_status', $this->dpt_status);
		$criteria->compare('dpt_chat_server_id', $this->dpt_chat_server_id);
		$criteria->compare('dpt_chat_status', $this->dpt_chat_status);
		$criteria->compare('dpt_created', $this->dpt_created, true);
		$criteria->compare('dpt_modified', $this->dpt_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Departments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used for getting the list of departments
	 * @param type $chatStatus -	[Optional]	-	Check for Chat server
	 */
	public static function getList($chatStatus = null)
	{
		$sql = "SELECT * FROM departments WHERE dpt_status = 1 ";
		if (!$chatStatus)
		{
			$sql .= " AND dpt_chat_status = $chatStatus";
		}
		$drDetails = DBUtil::query($sql);
		return $drDetails;
	}

	/**
	 * This function is used for creating the departments in live helper chat 
	 * server
	 * 
	 * @param int $dptId -	[Mandatory] - Gozo DB Id 
	 * @param string $dptName - [Mandatory] - Gozo Department Name
	 * @return int
	 */
	public static function processChatServer($dptId, $dptName)
	{
		if (empty($dptName))
		{
			return 0;
		}

		$response = Yii::app()->liveChat->addDepartment($dptName);
		if(!$response["status"])
		{
			return 0;
		}
		return CatDepartTeamMap::updateChatServerStatus($response["message"]->result->id, $dptId);
	}

	/**
	 * This function is used for updating the chat server department Id
	 * @param type $chatDeptId
	 * @param type $dptId
	 * @return int
	 */
	public static function updateChatServerStatus($chatDeptId, $dptId)
	{
		if(empty($chatDeptId) || empty($dptId))
		{
			return 0;
		}
		
		$model = self::model()->findByPk($dptId);
		$model->dpt_chat_server_id = $chatDeptId;
		$model->dpt_chat_status = 1;

		if(!$model->save())
		{
			return 0;
		}
		return 1;
	}

}
