<?php

/**
 * This is the model class for table "agent_requested_users".
 *
 * The followings are the available columns in table 'agent_requested_users':
 * @property integer $aru_id
 * @property integer $aru_agent_id
 * @property string $aru_name
 * @property string $aru_email
 * @property string $aru_phone
 */
class AgentRequestedUsers extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'agent_requested_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('aru_agent_id', 'required'),
			array('aru_agent_id', 'numerical', 'integerOnly' => true),
			array('aru_name, aru_email', 'length', 'max' => 200),
			array('aru_phone', 'length', 'max' => 100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('aru_id, aru_agent_id, aru_name, aru_email, aru_phone', 'safe', 'on' => 'search'),
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
			'aru_id'		 => 'Agu',
			'aru_agent_id'	 => 'Agu Agent',
			'aru_name'		 => 'Agu Name',
			'aru_email'		 => 'Agu Email',
			'aru_phone'		 => 'Agu Phone',
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

		$criteria->compare('aru_id', $this->aru_id);
		$criteria->compare('aru_agent_id', $this->aru_agent_id);
		$criteria->compare('aru_name', $this->aru_name, true);
		$criteria->compare('aru_email', $this->aru_email, true);
		$criteria->compare('aru_phone', $this->aru_phone, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgentRequestedUsers the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}
