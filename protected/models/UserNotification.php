<?php

/**
 * This is the model class for table "user_notification".
 *
 * The followings are the available columns in table 'user_notification':
 * @property integer $unf_id
 * @property integer $unf_user_id
 * @property integer $unf_ntf_id
 * @property integer $unf_credit_status
 * @property integer $unf_status
 * @property string $unf_created
 */
class UserNotification extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('unf_user_id, unf_ntf_id', 'required', 'on' => 'insert'),
			array('unf_user_id, unf_ntf_id, unf_credit_status, unf_status', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('unf_id, unf_user_id, unf_ntf_id, unf_credit_status, unf_status, unf_created', 'safe', 'on' => 'search'),
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
			'unfUser'	 => array(self::BELONGS_TO, 'Users', 'unf_user_id'),
			'unfNtf'	 => array(self::BELONGS_TO, 'Notification', 'unf_ntf_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'unf_id'			 => 'Unf',
			'unf_user_id'		 => 'Unf User',
			'unf_ntf_id'		 => 'Unf Ntf',
			'unf_credit_status'	 => 'Unf Credit Status',
			'unf_status'		 => 'Unf Status',
			'unf_created'		 => 'Unf Created',
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

		$criteria->compare('unf_id', $this->unf_id);
		$criteria->compare('unf_user_id', $this->unf_user_id);
		$criteria->compare('unf_ntf_id', $this->unf_ntf_id);
		$criteria->compare('unf_credit_status', $this->unf_credit_status);
		$criteria->compare('unf_status', $this->unf_status);
		$criteria->compare('unf_created', $this->unf_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserNotification the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function findByUserAndNtf($userId, $ntfId)
	{
		$criteria		 = new CDbCriteria();
		$criteria->compare('unf_user_id', $userId);
		$criteria->compare('unf_ntf_id', $ntfId);
		$criteria->compare('unf_status', 1);
		$criteria->with	 = ['unfNtf'];
		$model			 = $this->find($criteria);
		return $model;
	}

}
