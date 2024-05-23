<?php

/**
 * This is the model class for table "lead_log_cron".
 *
 * The followings are the available columns in table 'lead_log_cron':
 * @property integer $blg_id
 * @property integer $blg_booking_id
 * @property string $blg_desc
 * @property string $blg_admin_id
 * @property integer $blg_vendor_id
 * @property string $blg_created
 * @property integer $blg_event_id
 * @property string $blg_remarks
 * @property integer $blg_follow_up_status
 */
class LeadLogCron extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lead_log_cron';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('blg_booking_id', 'required'),
			array('blg_booking_id, blg_vendor_id, blg_event_id, blg_follow_up_status', 'numerical', 'integerOnly' => true),
			array('blg_desc', 'length', 'max' => 500),
			array('blg_admin_id', 'length', 'max' => 11),
			array('blg_remarks', 'length', 'max' => 2000),
			array('blg_created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('blg_id, blg_booking_id, blg_desc, blg_admin_id, blg_vendor_id, blg_created, blg_event_id, blg_remarks, blg_follow_up_status', 'safe', 'on' => 'search'),
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
			'blg_id'				 => 'Blg',
			'blg_booking_id'		 => 'Blg Booking',
			'blg_desc'				 => 'Blg Desc',
			'blg_admin_id'			 => 'Blg Admin',
			'blg_vendor_id'			 => 'Blg Vendor',
			'blg_created'			 => 'Blg Created',
			'blg_event_id'			 => 'Blg Event',
			'blg_remarks'			 => 'Blg Remarks',
			'blg_follow_up_status'	 => 'Blg Follow Up Status',
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

		$criteria->compare('blg_id', $this->blg_id);
		$criteria->compare('blg_booking_id', $this->blg_booking_id);
		$criteria->compare('blg_desc', $this->blg_desc, true);
		$criteria->compare('blg_admin_id', $this->blg_admin_id, true);
		$criteria->compare('blg_vendor_id', $this->blg_vendor_id);
		$criteria->compare('blg_created', $this->blg_created, true);
		$criteria->compare('blg_event_id', $this->blg_event_id);
		$criteria->compare('blg_remarks', $this->blg_remarks, true);
		$criteria->compare('blg_follow_up_status', $this->blg_follow_up_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LeadLogCron the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

}
