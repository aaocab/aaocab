<?php

/**
 * This is the model class for table "admin_log".
 *
 * The followings are the available columns in table 'admin_log':
 * @property string $adm_log_id
 * @property integer $adm_log_user
 * @property string $adm_log_ip
 * @property string $adm_log_session
 * @property string $adm_log_device_info
 * @property integer $adm_log_last_device_type
 * @property string $adm_log_in_time
 * @property string $adm_log_last_active
 * @property string $adm_log_last_ip
 * @property string $adm_log_out_time
 */
class AdminLog extends CActiveRecord
{

	public $adm_fname, $adm_lname;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'admin_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('adm_log_user, adm_log_ip, adm_log_session, adm_log_device_info, adm_log_in_time', 'required'),
			array('adm_log_user, adm_log_last_device_type', 'numerical', 'integerOnly' => true),
			array('adm_log_ip', 'length', 'max' => 25),
			array('adm_log_device_info', 'length', 'max' => 250),
			array('adm_log_last_ip', 'length', 'max' => 50),
			array('adm_log_last_active, adm_log_out_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('adm_log_id, adm_log_user, adm_log_ip, adm_log_session, adm_log_device_info, adm_log_last_device_type, adm_log_in_time, adm_log_last_active, adm_log_last_ip, adm_log_out_time', 'safe', 'on' => 'search'),
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
			'admUser' => array(self::BELONGS_TO, 'Admins', 'adm_log_user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'adm_log_id'				 => 'Adm Log',
			'adm_log_user'				 => 'Adm Log User',
			'adm_log_ip'				 => 'Adm Log Ip',
			'adm_log_session'			 => 'Adm Log Session',
			'adm_log_device_info'		 => 'Adm Log Device Info',
			'adm_log_last_device_type'	 => 'Adm Log Last Device Type',
			'adm_log_in_time'			 => 'Adm Log In Time',
			'adm_log_last_active'		 => 'Adm Log Last Active',
			'adm_log_last_ip'			 => 'Adm Log Last Ip',
			'adm_log_out_time'			 => 'Adm Log Out Time',
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

		$criteria->compare('adm_log_id', $this->adm_log_id, true);
		$criteria->compare('adm_log_user', $this->adm_log_user);
		$criteria->compare('adm_log_ip', $this->adm_log_ip, true);
		$criteria->compare('adm_log_session', $this->adm_log_session, true);
		$criteria->compare('adm_log_device_info', $this->adm_log_device_info, true);
		$criteria->compare('adm_log_last_device_type', $this->adm_log_last_device_type);
		$criteria->compare('adm_log_in_time', $this->adm_log_in_time, true);
		$criteria->compare('adm_log_last_active', $this->adm_log_last_active, true);
		$criteria->compare('adm_log_last_ip', $this->adm_log_last_ip, true);
		$criteria->compare('adm_log_out_time', $this->adm_log_out_time, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AdminLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getLogBySession($session)
	{
		return $this->find('adm_log_session =:session', array('session' => $session));

//       $qry = "select `adm_log_session` from `admin_log` where adm_log_session = '".$session."'";
//        $logList = DBUtil::queryRow($qry);
//        $admlogList = new CArrayDataProvider($logList, array('pagination' => array('pageSize' => 2),));
//        return $admlogList;
	}

	public function updateLastActive($sess)
	{
		$admlogModel = $this->getLogBySession($sess);
		$ip			 = \Filter::getUserIP();
		$deviceType	 = AdminLog::$deviceType;

		if ($admlogModel)
		{
			$criteria			 = new CDbCriteria();
			$criteria->condition = "adm_log_session = '$admlogModel->log_session'";
			$attributes			 = array('adm_log_last_active'		 => new CDbExpression('Now()'),
				'adm_log_last_ip'			 => $ip, 'adm_log_last_device_type'	 => $deviceType);

			self::model()->updateAll($attributes, $criteria);
		}
	}

	/** @deprecated Use \Filter::getUserIP() */
	public function getIP()
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

	public function fetchList()
	{
		$criteria = new CDbCriteria;
		if ($this->adm_log_user != '')
		{
			$criteria->compare("adm_id", $this->adm_log_user);
		}
		$criteria->with		 = ['admUser' => ['select' => ['adm_fname', 'adm_lname', 'adm_id']]];
		$criteria->together	 = true;
		$criteria->order	 = 'adm_log_id DESC';
		$dataProvider		 = new CActiveDataProvider($this, array('criteria' => $criteria, 'sort' => ['attributes' => ['admUser.adm_fname', 'adm_log_ip', 'adm_log_in_time', 'adm_log_out_time', 'adm_log_session']], 'pagination' => ['pageSize' => 20]));
		return $dataProvider;
	}

}
