<?php

/**
 * This is the model class for table "booking_event_stats".
 *
 * The followings are the available columns in table 'booking_event_stats':
 * @property integer $bes_id
 * @property integer $bes_bkg_id
 * @property integer $bes_invoice_sent
 * @property string $bes_invoice_sent_date
 * @property integer $bes_driver_app_penalty_applied
 * @property string $bes_driver_app_penalty_applied_date
 */
class BookingEventStats extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_event_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bes_bkg_id', 'required'),
			array('bes_bkg_id, bes_invoice_sent, bes_driver_app_penalty_applied', 'numerical', 'integerOnly' => true),
			array('bes_invoice_sent_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bes_id, bes_bkg_id, bes_invoice_sent, bes_invoice_sent_date, bes_driver_app_penalty_applied, bes_driver_app_penalty_applied_date', 'safe', 'on' => 'search'),
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
			'bes_id'				 => 'Bes',
			'bes_bkg_id'			 => 'Bes Bkg',
			'bes_invoice_sent'		 => 'Bes Invoice Sent',
			'bes_invoice_sent_date'	 => 'Bes Invoice Sent Date',
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

		$criteria->compare('bes_id', $this->bes_id);
		$criteria->compare('bes_bkg_id', $this->bes_bkg_id);
		$criteria->compare('bes_invoice_sent', $this->bes_invoice_sent);
		$criteria->compare('bes_invoice_sent_date', $this->bes_invoice_sent_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingEventStats the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $eventId
	 * @param type $data
	 * @throws Exception
	 */
	public static function updateStats($bkgId, $eventId, $data = array())
	{
		try
		{
			$model = self::model()->findByAttributes(array('bes_bkg_id' => $bkgId));
			if (!$model)
			{
				$model				 = new BookingEventStats();
				$model->bes_bkg_id	 = $bkgId;
			}

			switch ($eventId)
			{
				case BookingScheduleEvent::SEND_BOOKING_INVOICE:
					$model->bes_invoice_sent					 = $data['invoice_sent'];
					$model->bes_invoice_sent_date				 = new CDbExpression('NOW()');
					$errorMsg									 = "Send Invoice process failed";
					break;
				case BookingScheduleEvent::DRIVER_APP_PENALTY:
					$model->bes_driver_app_penalty_applied		 = $data['driver_app_usage_penalty'];
					$model->bes_driver_app_penalty_applied_date	 = new CDbExpression('NOW()');
					$errorMsg									 = "Driver app penalty process failed";
					break;
			}

			if (!$model->save())
			{
				throw new Exception($errorMsg);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
	}

}
