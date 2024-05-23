<?php

/**
 * This is the model class for table "app_tokens".
 *
 * The followings are the available columns in table 'app_tokens':
 * @property integer $apt_id
 * @property integer $apt_user_id
 * @property string $apt_token_id
 * @property string $apt_device
 * @property string $apt_date
 * @property string $apt_last_login
 * @property integer $apt_status
 *
 * The followings are the available model relations:
 * @property Users $aptUser
 */
class AppTokens1 extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'app_tokens';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('apt_user_id, apt_token_id, apt_device', 'required'),
			array('apt_user_id, apt_status', 'numerical', 'integerOnly' => true),
			array('apt_token_id', 'length', 'max' => 200),
			array('apt_device', 'length', 'max' => 255),
			array('apt_last_login', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('apt_id, apt_user_id, apt_token_id, apt_device, apt_date, apt_last_login, apt_status', 'safe', 'on' => 'search'),
		);
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "apt_status=1",
		);
		return $arr;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'aptUser' => array(self::BELONGS_TO, 'Users', 'apt_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'apt_id'		 => 'Apt',
			'apt_user_id'	 => 'Apt User',
			'apt_token_id'	 => 'Apt Token',
			'apt_device'	 => 'Apt Device',
			'apt_date'		 => 'Apt Date',
			'apt_last_login' => 'Apt Last Login',
			'apt_status'	 => '1:active;2:inactive',
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

		$criteria->compare('apt_id', $this->apt_id);
		$criteria->compare('apt_user_id', $this->apt_user_id);
		$criteria->compare('apt_token_id', $this->apt_token_id, true);
		$criteria->compare('apt_device', $this->apt_device, true);
		$criteria->compare('apt_date', $this->apt_date, true);
		$criteria->compare('apt_last_login', $this->apt_last_login, true);
		$criteria->compare('apt_status', $this->apt_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppTokens the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/** @return AppTokens  */
	public function getByToken($token)
	{
		return self::model()->find('apt_token_id=:token', array('token' => $token));
	}

	/** @return AppTokens[]  */

	/** @return AppTokens[]  */
	public function getByUser($user, $type)
	{
		return $this->findAll('apt_user_id=:user AND apt_user_type=:type', ['user' => $user, 'type' => $type]);
	}

	public function getArrayByOS($aptModels)
	{
		$arrApn = array();
		foreach ($aptModels as $aptModel)
		{
			$arrApn[$aptModel->apt_os_type][] = $aptModel->apt_apn_id;
		}
		return $arrApn;
	}

	public function sendNotifications($aptModels, $message, $param)
	{
		$arrApn			 = $this->getArrayByOS($aptModels);
		$apnGcm			 = Yii::app()->apnsGcm;
		$result			 = [];
		/* @var $apnGcm YiiApnsGcm */
		if (count($arrApn[1]) > 0)
			$result['gcm']	 = $apnGcm->sendMulti(YiiApnsGcm::TYPE_GCM, $arrApn[1], $message, $param);

		if (count($arrApn[2]) > 0)
			$result['apn'] = $apnGcm->sendMulti(YiiApnsGcm::TYPE_APNS, $arrApn[2], $message, $param);

		return $result;
	}

}
