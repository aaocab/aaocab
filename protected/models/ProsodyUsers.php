<?php

/**
 * This is the model class for table "prosody_users".
 *
 * The followings are the available columns in table 'prosody_users':
 * @property string $pru_id
 * @property integer $pru_type
 * @property string $pru_pk_id
 * @property int $pru_chat_server_id - Chat server user Id
 * @property string $pru_user_name
 * @property string $pru_password
 * @property integer $pru_status
 * @property string $pru_created
 * @property string $pru_modified
 */
class ProsodyUsers extends CActiveRecord
{
	const TYPE_CONTACT = 1;
	const TYPE_ADMIN = 2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'prosody_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pru_pk_id, pru_user_name, pru_password, pru_created, pru_modified', 'required'),
			array('pru_type, pru_status', 'numerical', 'integerOnly' => true),
			array('pru_pk_id', 'length', 'max' => 20),
			array('pru_user_name', 'length', 'max' => 500),
			array('pru_password', 'length', 'max' => 600),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pru_id, pru_type, pru_pk_id, pru_chat_server_id, pru_user_name, pru_password, pru_status, pru_created, pru_modified', 'safe', 'on' => 'search'),
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
			'pru_id'		 => 'Pru',
			'pru_type'		 => 'Pru Type',
			'pru_pk_id'		 => 'Pru Pk',
			'pru_user_name'	 => 'Pru User Name',
			'pru_password'	 => 'Pru Password',
			'pru_status'	 => 'Pru Status',
			'pru_created'	 => 'Pru Created',
			'pru_modified'	 => 'Pru Modified',
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

		$criteria->compare('pru_id', $this->pru_id, true);
		$criteria->compare('pru_type', $this->pru_type);
		$criteria->compare('pru_pk_id', $this->pru_pk_id, true);
		$criteria->compare('pru_user_name', $this->pru_user_name, true);
		$criteria->compare('pru_password', $this->pru_password, true);
		$criteria->compare('pru_status', $this->pru_status);
		$criteria->compare('pru_created', $this->pru_created, true);
		$criteria->compare('pru_modified', $this->pru_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProsodyUsers the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function add($username, $password, $type, $id, $chatUserId)
	{
		$model					 = new ProsodyUsers();
		$model->pru_type		 = $type;
		$model->pru_pk_id		 = $id;
		$model->pru_chat_server_id = $chatUserId;
		$model->pru_user_name	 = $username;
		$model->pru_password	 = $password;
		$model->pru_created		 = new CDbExpression('now()');
		$model->pru_modified	 = new CDbExpression('now()');
		$model->save();
		return $model->pru_id;
	}

	/**
	 * This function is used for processing the user creation in the chat server
	 * @param type $id
	 * @param type $type
	 * @return int
	 */
	public static function processData($data)
	{
		if (empty($data))
		{
			return 0;
		}
		
		$password = "pass@" . rand(1000, 9999);

		$jsonData = new stdClass();
		$jsonData->username = $data["adm_email"];
		$jsonData->password = $password;
		$jsonData->email = $data["adm_email"];
		$jsonData->name = $data["adm_fname"];
		$jsonData->surname = $data["adm_lname"];
		$jsonData->chat_nickname = $data["adm_user"];
		$jsonData->departments = [$data["cdt_chat_server_dpt_id"]];
		$jsonData->departments_read = [];
		$jsonData->department_groups = [];
		$jsonData->user_groups = [2]; //Operators

		$response = self::handleChatServer($jsonData);
		if(!$response["status"])
		{
			return 0;
		}
		$chatUserId  = $response["message"]->result->id;
		$dbId		 = self::add($data["adm_email"], $password, UserInfo::TYPE_ADMIN, $data["adm_id"], $chatUserId);
		
		if($dbId > 0)
		{
			Admins::updateProsodyId($data["adm_id"], $chatUserId);
		}
		return $chatUserId;
	}


	public static function handleChatServer($jsonData)
	{
		return Yii::app()->liveChat->addUser($jsonData);
	}

	
}
