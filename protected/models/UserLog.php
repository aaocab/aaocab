<?php

use Yii;

/**
 * This is the model class for table "user_log".
 *
 * The followings are the available columns in table 'user_log':
 * @property string $log_id
 * @property integer $log_user
 * @property string $log_ip
 * @property string $log_session
 * @property string $log_in_time
 * @property string $log_out_time
 */
class UserLog extends CActiveRecord
{

	public static $deviceType = 1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('log_user, log_ip, log_session, log_in_time', 'required'),
			array('log_user', 'numerical', 'integerOnly' => true),
			array('log_ip', 'length', 'max' => 100),
			array('log_out_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('log_id, log_user, log_ip, log_session, log_in_time, log_last_active, log_device_info, log_out_time', 'safe', 'on' => 'search'),
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
			'log_id'		 => 'Log',
			'log_user'		 => 'Log User',
			'log_ip'		 => 'Log Ip',
			'log_session'	 => 'Log Session',
			'log_in_time'	 => 'Log In Time',
			'log_out_time'	 => 'Log Out Time',
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

		$criteria->compare('log_id', $this->log_id, true);
		$criteria->compare('log_user', $this->log_user);
		$criteria->compare('log_ip', $this->log_ip, true);
		$criteria->compare('log_session', $this->log_session, true);
		$criteria->compare('log_device_info', $this->log_device_info, true);
		$criteria->compare('log_in_time', $this->log_in_time, true);
		$criteria->compare('log_out_time', $this->log_out_time, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getLogBySession($session)
	{
		return $this->find('log_session =:session', array('session' => $session));
	}

	public function updateLastActive($sess)
	{
		$logModel	 = $this->getLogBySession($sess);
		$ip			 = \Filter::getUserIP();
		$deviceType	 = UserLog::$deviceType;
		if ($logModel)
		{
			$criteria			 = new CDbCriteria();
			$criteria->condition = "log_session = '$logModel->log_session'";
			$attributes			 = array('log_last_active'		 => new CDbExpression('Now()'),
				'log_last_ip'			 => $ip, 'log_last_device_type'	 => $deviceType);

			self::model()->updateAll($attributes, $criteria);
		}
	}

	public function getAverageHourLoginPerDaybyUser1($user)
	{
		$logModel = Yii::app()->db->createCommand()
				->select('*, SUM(IFNULL(TIMESTAMPDIFF(HOUR, log_in_time,IFNULL(log_off_time),Now()),0) AS totHours,  Count(distinct DATE(log_in_time)) as totDays')
				->from('user_log')
				->where('log_user=' . $user)
				->group('log_user')
				->queryAll();
		return $logModel;
	}

	public function getAverageHourLoginPerDaybyUser($user)
	{
		$logModel = Yii::app()->db->createCommand()
				->select('*,COUNT(NULLIF( log_last_active, 0 )) as totalLogin , SUM(IFNULL(TIMESTAMPDIFF(HOUR, log_in_time,log_last_active),0)) AS totHours,SUM(IFNULL(TIMESTAMPDIFF(MINUTE, log_in_time,log_last_active),0)) AS totMinutes,  Count(distinct DATE(log_in_time)) as totDays')
				->from('user_log')
				->where('log_user=' . $user)
				->group('log_user')
				->queryAll();
		return $logModel;
	}

	public function getTotalLogggedinbyDay()
	{
		$logModel = Yii::app()->db->createCommand()
				->select('CAST(log_in_time AS DATE) DATE_ONLY, count(log_id)')
				->from('user_log')
				->group('CAST(log_in_time AS DATE)')
				->queryAll();
		return $logModel;
	}

	public function getActiveUsers($user)
	{
		$logModel = Yii::app()->db->createCommand()
				->select('count(log_id) onlineCount, GROUP_CONCAT(log_user) onlineUsers')
				->from('user_log')
				->where('TIMESTAMPDIFF(MINUTE,log_last_active, NOW()) < 15')
				->where('log_user=' . $user)
				->group('log_user')
				->queryAll();
		return $logModel;
	}

	
	/** @deprecated Use \Filter::getUserIP() */
	public function getIP()
	{
		return Filter::getUserIP();
	}

	public function getCitynCountrycodefromIP($cip)
	{

//		$iptolocation		 = 'http://ipinfo.io/' . $cip;
//		$creatorlocation1	 = file_get_contents($iptolocation);
//		$creatorlocation	 = json_decode($creatorlocation1);
//		$info				 = [];
		if (isset($creatorlocation) && $creatorlocation->city != null)
		{
			$info['city'] =''; //$creatorlocation->city;
		}
		if (isset($creatorlocation) && $creatorlocation->country != null)
		{
			$info['country'] =''; // $creatorlocation->country;
		}
		return $info;
	}

	public function getDevice()
	{
//        if (isset($_SERVER['HTTP_CLIENT_IP']))
//        {
//            $real_ip_adress = $_SERVER['HTTP_CLIENT_IP'];
//        }
//        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
//        {
//            $real_ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
//        }
//        else
//        {
//            $real_ip_adress = $_SERVER['REMOTE_ADDR'];
//        }
		return $_SERVER['HTTP_USER_AGENT'];
	}
	public static function getloggedInCount($userID)
	{
		$params	 = ["userId" => $userID];
		$sql	 = "SELECT COUNT(*) as tot FROM `user_log` WHERE log_user =:userId";
		$count	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return $count;
	}

}
