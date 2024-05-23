<?php

/**
 * This is the model class for table "contact_merged_details".
 *
 * The followings are the available columns in table 'contact_merged_details':
 * @property integer $cmd_id
 * @property integer $cmd_ctt_id
 * @property integer $cmd_duplicate_ctt_id
 * @property integer $cmd_is_adhaar_matched
 * @property integer $cmd_is_license_matched
 * @property integer $cmd_is_pan_matched
 * @property integer $cmd_is_voter_matched
 * @property integer $cmd_is_phone_matched
 * @property integer $cmd_is_email_matched
 * @property integer $cmd_status
 * @property integer $cmd_active
 */
class ContactMergedDetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact_merged_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cmd_ctt_id, cmd_duplicate_ctt_id', 'required'),
			array('cmd_ctt_id, cmd_duplicate_ctt_id, cmd_is_adhaar_matched, cmd_is_license_matched, cmd_is_pan_matched, cmd_is_voter_matched, cmd_is_phone_matched, cmd_is_email_matched, cmd_status, cmd_active', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cmd_id, cmd_ctt_id, cmd_duplicate_ctt_id, cmd_is_adhaar_matched, cmd_is_license_matched, cmd_is_pan_matched, cmd_is_voter_matched, cmd_is_phone_matched, cmd_is_email_matched, cmd_status, cmd_active', 'safe', 'on'=>'search'),
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
			'cmd_id' => 'Cmd',
			'cmd_ctt_id' => 'Cmd Ctt',
			'cmd_duplicate_ctt_id' => 'Cmd Duplicate Ctt',
			'cmd_is_adhaar_matched' => 'Cmd Is Adhaar Matched',
			'cmd_is_license_matched' => 'Cmd Is License Matched',
			'cmd_is_pan_matched' => 'Cmd Is Pan Matched',
			'cmd_is_voter_matched' => 'Cmd Is Voter Matched',
			'cmd_is_phone_matched' => 'Cmd Is Phone Matched',
			'cmd_is_email_matched' => 'Cmd Is Email Matched',
			'cmd_status' => 'Cmd Status',
			'cmd_active' => 'Cmd Active',
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

		$criteria->compare('cmd_id',$this->cmd_id);
		$criteria->compare('cmd_ctt_id',$this->cmd_ctt_id);
		$criteria->compare('cmd_duplicate_ctt_id',$this->cmd_duplicate_ctt_id);
		$criteria->compare('cmd_is_adhaar_matched',$this->cmd_is_adhaar_matched);
		$criteria->compare('cmd_is_license_matched',$this->cmd_is_license_matched);
		$criteria->compare('cmd_is_pan_matched',$this->cmd_is_pan_matched);
		$criteria->compare('cmd_is_voter_matched',$this->cmd_is_voter_matched);
		$criteria->compare('cmd_is_phone_matched',$this->cmd_is_phone_matched);
		$criteria->compare('cmd_is_email_matched',$this->cmd_is_email_matched);
		$criteria->compare('cmd_status',$this->cmd_status);
		$criteria->compare('cmd_active',$this->cmd_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContactMergedDetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
