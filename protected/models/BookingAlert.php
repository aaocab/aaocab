<?php

/**
 * This is the model class for table "booking_alert".
 *
 * The followings are the available columns in table 'booking_alert':
 * @property integer $alr_id
 * @property integer $alr_bkg_id
 * @property string $alr_email
 * @property string $alr_name
 * @property string $alr_from_date
 * @property string $alr_to_date
 * @property integer $alr_from_city
 * @property integer $alr_to_city
 * @property string $alr_created
 * @property integer $alr_status
 */
class BookingAlert extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_alert';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('alr_from_city, alr_to_city, alr_email, alr_name, alr_from_date, alr_to_date', 'required'),
			array('alr_from_city, alr_to_city, alr_status', 'numerical', 'integerOnly' => true),
			array('alr_email, alr_name', 'length', 'max' => 150),
			array('alr_email', 'email', 'except' => 'stepMobile3,cancel_delete_new', 'message' => 'Please enter valid email address', 'checkMX' => true),
			array('alr_from_date, alr_to_date,alr_bkg_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('alr_id, alr_email, alr_name, alr_from_date, alr_to_date, alr_from_city, alr_to_city, alr_created, alr_status', 'safe', 'on' => 'search'),
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
			'alr_id'		 => 'Alr',
			'alr_email'		 => 'Email',
			'alr_name'		 => 'Name',
			'alr_from_date'	 => 'From Date',
			'alr_to_date'	 => 'To Date',
			'alr_from_city'	 => 'From City',
			'alr_to_city'	 => 'To City',
			'alr_created'	 => 'Alr Created',
			'alr_status'	 => 'Alr Status',
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

		$criteria->compare('alr_id', $this->alr_id);
		$criteria->compare('alr_email', $this->alr_email, true);
		$criteria->compare('alr_name', $this->alr_name, true);
		$criteria->compare('alr_from_date', $this->alr_from_date, true);
		$criteria->compare('alr_to_date', $this->alr_to_date, true);
		$criteria->compare('alr_from_city', $this->alr_from_city);
		$criteria->compare('alr_to_city', $this->alr_to_city);
		$criteria->compare('alr_created', $this->alr_created, true);
		$criteria->compare('alr_status', $this->alr_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingAlert the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function saveData()
	{
		$success = false;
		$result = CActiveForm::validate($this, null, false);
		if ($result == '[]')
		{
			if($this->save())
			{
				$result = '';
				$success = true;
			}
			else
			{
				$result = 'Error to save';
				$success = false;				
			}
		}
		$ret = ['success' => $success, 'result' => $result];
		return $ret;
	}
	
	public function getNotifyData()
	{
		$sql = "SELECT alr_id,alr_bkg_id,alr_name,alr_email,alr_from_date,alr_to_date,alr_from_city,alr_to_city FROM booking_alert WHERE alr_to_date > NOW() AND alr_status=1";
		$dataSet = DBUtil::queryAll($sql);
		return $dataSet;
	}
	
	public function updateStatus($bkgId)
	{
		$sql = "SELECT COUNT(1) FROM booking_alert WHERE alr_bkg_id=$bkgId AND alr_status=1";
		$res = DBUtil::command($sql)->queryScalar();
		if($res > 0)
		{
			$updSql= "UPDATE booking_alert SET alr_status=0 WHERE alr_bkg_id=$bkgId";
			$result = DBUtil::command($updSql)->execute();
			if(!$result)
			{
				return false;
			}
		}
		return true;
		
	}

}
