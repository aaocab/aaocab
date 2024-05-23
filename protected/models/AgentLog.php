<?php

/**
 * This is the model class for table "agent_log".
 *
 * The followings are the available columns in table 'agent_log':
 * @property integer $agl_id
 * @property integer $agl_ref_id
 * @property integer $agl_usr_ref_id
 * @property integer $agl_usr_type
 * @property integer $agl_agent_id
 * @property string $agl_desc
 * @property integer $agl_event_id
 * @property integer $agl_active
 * @property string $agl_created
 * @property string $agl_ip
 * @property string $agl_session
 * @property string $agl_device_info
 * @property string $agl_logout_time
 */
class AgentLog extends CActiveRecord
{

	const Agent						 = 1;
	const Admin						 = 2;
	const System						 = 10;
	const Consumer					 = 4;
	//Events
	const AGENT_LOGGEDIN				 = 1;
	const AGENT_APPROVED				 = 2;
	const AGENT_TYPE_CHANGED			 = 3;
	const AGENT_DELETED				 = 4;
	const AGENT_REJECTED				 = 5;
	const AGENT_CREATED				 = 6;
	const AGENT_UPDATED				 = 7;
	const EFFECTIVE_CREDIT_LIMIT_UNSET = 8;
	const CREDIT_LIMIT_UNSET			 = 9;
	const AGENT_PARTNER_COMMISSION	 = 10;
//
//Payment events
	const PAYMENT_INITIATED			 = 51;
	const PAYMENT_COMPLETED			 = 52;
	const PAYMENT_FAILED				 = 53;
	const REFUND_PROCESS_INITIATED	 = 61;
	const REFUND_PROCESS_COMPLETED	 = 62;
	const REFUND_PROCESS_PENDING		 = 63;
	const REFUND_PROCESS_FAILED		 = 64;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'agent_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('agl_agent_id', 'required'),
			array('agl_ref_id, agl_usr_ref_id, agl_usr_type, agl_agent_id, agl_event_id, agl_active', 'numerical', 'integerOnly' => true),
			array('agl_desc', 'length', 'max' => 4000),
			array('agl_ip', 'length', 'max' => 50),
			array('agl_device_info', 'length', 'max' => 250),
			array('agl_session, agl_logout_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('agl_id, agl_ref_id, agl_usr_ref_id, agl_usr_type, agl_agent_id, agl_desc, agl_event_id, agl_active, agl_created, agl_ip, agl_session, agl_device_info, agl_logout_time', 'safe'),
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
			'aglAgents' => array(self::BELONGS_TO, 'Agents', 'agl_agent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'agl_id'			 => 'Id',
			'agl_ref_id'		 => 'Event Reference Id',
			'agl_usr_ref_id'	 => 'Event By',
			'agl_usr_type'		 => 'User Type',
			'agl_agent_id'		 => 'Agent',
			'agl_desc'			 => 'Description',
			'agl_event_id'		 => 'Event',
			'agl_active'		 => 'Status',
			'agl_created'		 => 'Created',
			'agl_ip'			 => 'IP Address',
			'agl_session'		 => 'Session',
			'agl_device_info'	 => 'Device Info',
			'agl_logout_time'	 => 'Logout Time',
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

		$criteria->compare('agl_id', $this->agl_id);
		$criteria->compare('agl_ref_id', $this->agl_ref_id);
		$criteria->compare('agl_usr_ref_id', $this->agl_usr_ref_id);
		$criteria->compare('agl_usr_type', $this->agl_usr_type);
		$criteria->compare('agl_agent_id', $this->agl_agent_id);
		$criteria->compare('agl_desc', $this->agl_desc, true);
		$criteria->compare('agl_event_id', $this->agl_event_id);
		$criteria->compare('agl_active', $this->agl_active);
		$criteria->compare('agl_created', $this->agl_created, true);
		$criteria->compare('agl_ip', $this->agl_ip, true);
		$criteria->compare('agl_session', $this->agl_session, true);
		$criteria->compare('agl_device_info', $this->agl_device_info, true);
		$criteria->compare('agl_logout_time', $this->agl_logout_time, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgentLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function eventList()
	{
		$eventlist = [
			51	 => 'Payment Initiated',
			52	 => 'Payment Completed',
			53	 => 'Payment Failed',
			61	 => 'Refund Process Initiated',
			62	 => 'Refund Process Completed',
			63	 => 'Refund Process Pending',
			64	 => 'Refund Process Failed',
		];
	}

	public function getLogBySession($session)
	{
		return $this->find('agl_session =:session', array('session' => $session));
	}

	public static function getIP()
	{
		if (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$real_ip_adress = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$real_ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$real_ip_adress = $_SERVER['REMOTE_ADDR'];
		}
		if ($real_ip_adress == '::1')
		{
			$real_ip_adress = '122.163.41.5';
		}
		return $real_ip_adress;
	}

	/**
	 * 
	 * @param integer $agt_id
	 * @param string $desc
	 * @param UserInfo $userInfo
	 * @param integer $event_id
	 * @param model $oldModel
	 * @param array $params
	 * @return array
	 * @throws Exception
	 */
	public function createLog($agt_id, $desc, UserInfo $userInfo = null, $event_id, $oldModel = false, $params = false)
	{
		$success = false;
		try
		{
			/* @var $model AgentLog  */
			$model = new AgentLog();
			if ($userInfo == null)
			{
				$userInfo = UserInfo::model();
			}
			$model->agl_agent_id	 = $agt_id;
			$model->agl_usr_type	 = $userInfo->userType;
			$model->agl_usr_ref_id	 = $userInfo->userId;
			$model->agl_desc		 = $desc;
			$model->agl_event_id	 = $event_id;
			if ($oldModel)
			{
				$model->agl_active = $oldModel->agl_active;
			}
			if ($params['agl_ip'] != ''):
				$model->agl_ip = $params['agl_ip'];
			endif;
			if ($params['agl_session'] != ''):
				$model->agl_session = $params['agl_session'];
			endif;
			if ($params['agl_device_info'] != ''):
				$model->agl_device_info = $params['agl_device_info'];
			endif;
			if ($params['agl_logout_time'] != ''):
				$model->agl_logout_time = $params['agl_logout_time'];
			endif;
			if ($model->validate() && $model->save())
			{
				$success	 = true;
				$resultSet	 = ['success' => $success, 'message' => ''];
			}
			else
			{
				$errors = json_encode($model->getErrors());
				throw new Exception("Errors : " . $errors);
			}
		}
		catch (Exception $ex)
		{
			$errors		 = $ex->getMessage();
			$errorCode	 = $ex->getCode();
			$resultSet	 = ['success' => $success, 'errors' => $errors, 'errorCode' => $errorCode];
		}
		return $resultSet;
	}

	/**
	 * 
	 * @param integer $partnerId
	 * @param string $desc	 
	 * @param integer $event_id 
	 * @param UserInfo $userInfo
	 * @return array
	 * @throws Exception
	 */
	public static function add($partnerId, $desc, $event_id, $userInfo = null)
	{
		$success = false;
		try
		{
			if ($userInfo == null)
			{
				$userInfo = UserInfo::getInstance();
			}
			$ip								 = AgentLog::getIP();
			$sessionid						 = session_id();
			$agtlogModel					 = new AgentLog();
			$agtlogModel->agl_usr_ref_id	 = $userInfo->getUserId();
			$agtlogModel->agl_usr_type		 = ($userInfo->userType == UserInfo::TYPE_SYSTEM) ? UserInfo::TYPE_SYSTEM : AgentLog::Agent;
			$agtlogModel->agl_agent_id		 = $partnerId;
			$agtlogModel->agl_desc			 = $desc;
			$agtlogModel->agl_event_id		 = $event_id;
			$agtlogModel->agl_ip			 = $ip;
			$agtlogModel->agl_session		 = $sessionid;
			$agtlogModel->agl_device_info	 = $_SERVER['HTTP_USER_AGENT'];
			if ($agtlogModel->validate() && $agtlogModel->save())
			{
				$success	 = true;
				$resultSet	 = ['success' => $success, 'message' => ''];
			}
			else
			{
				$errors = json_encode($agtlogModel->getErrors());
				throw new Exception("Errors : " . $errors);
			}
		}
		catch (Exception $ex)
		{
			$errors		 = $ex->getMessage();
			$errorCode	 = $ex->getCode();
			$resultSet	 = ['success' => $success, 'errors' => $errors, 'errorCode' => $errorCode];
		}
		return $resultSet;
	}

}
