<?php

/**
 * This is the model class for table "agent_users".
 *
 * The followings are the available columns in table 'agent_users':
 * @property integer $agu_id
 * @property integer $agu_user_id
 * @property integer $agu_agent_id
 * @property integer $agu_role
 * @property string $agu_created
 */
class AgentUsers extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'agent_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('agu_user_id, agu_agent_id', 'required'),
			array('agu_user_id, agu_agent_id, agu_role', 'numerical', 'integerOnly' => true),
			array('agu_created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('agu_id, agu_user_id, agu_agent_id, agu_role, agu_created', 'safe', 'on' => 'search'),
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
			'agentAgentUsers' => array(self::BELONGS_TO, 'Agents', 'agu_agent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'agu_id'		 => 'Agu',
			'agu_user_id'	 => 'Agu User',
			'agu_agent_id'	 => 'Agu Agent',
			'agu_role'		 => 'Agu Role',
			'agu_created'	 => 'agu Created',
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

		$criteria->compare('agu_id', $this->agu_id);
		$criteria->compare('agu_user_id', $this->agu_user_id);
		$criteria->compare('agu_agent_id', $this->agu_agent_id);
		$criteria->compare('agu_role', $this->agu_role);
		$criteria->compare('agu_created', $this->agu_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgentUsers the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function checkAccess($agentId, $userId)
	{
		if ($agentId == $userId)
		{
			return true;
		}
		else
		{
			$sql	 = "SELECT COUNT(1) as cnt FROM `agent_users` WHERE `agu_user_id`=$userId AND `agu_agent_id`=$agentId";
			$count	 = DBUtil::queryScalar($sql);
			return ($count > 0);
		}
	}
	
	public function getAgentByUserId($user_id)
	{
		$qry	     = "SELECT agu_agent_id FROM agent_users where agent_users.agu_user_id= $user_id";
		
		$record	 = DBUtil::queryAll($qry);
		return $record[0]['agu_agent_id'];
	}
	public function getChannelPartners($agt_usr_id)
	{
		$qry	     = "SELECT agu_agent_id as agent_id,CONCAT(agents.agt_fname, ' ', agents.agt_lname) AS  name,agents.agt_effective_credit_limit as agent_effective_credit,agents.agt_credit_limit as agent_credit FROM agent_users INNER JOIN agents ON agents.agt_id = agent_users.agu_agent_id where agent_users.agu_user_id= $agt_usr_id";
		
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}
	
	public function getVendorContactUser($userId)
	{
		$qry = "SELECT ctt.ctt_first_name, ctt.ctt_last_name, ctt.ctt_business_name, eml.eml_email_address, phn.phn_phone_no FROM vendors vnd
INNER JOIN contact ctt ON ctt.ctt_id = vnd.vnd_contact_id 
INNER JOIN contact_email eml ON eml.eml_contact_id = ctt.ctt_id AND eml.eml_is_primary = 1 AND eml.eml_active = 1 
INNER JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id AND phn.phn_is_primary = 1 AND phn.phn_active = 1 
WHERE vnd_active = 1 AND vnd_user_id = $userId";
	$recordset	 = DBUtil::queryAll($qry);
	return $recordset;
	}
}
