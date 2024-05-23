<?php

/**
 * This is the model class for table "partner_api_details".
 *
 * The followings are the available columns in table 'partner_api_details':
 * @property integer $pad_id
 * @property integer $pad_pat_id
 * @property string $pad_request
 * @property string $pad_response
 * @property string $pad_created_at
 */
class PartnerApiDetails extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_api_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pad_pat_id', 'numerical', 'integerOnly' => true),
			array('pad_request, pad_response', 'length', 'max' => 10000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pad_id, pad_pat_id, pad_request, pad_response, pad_created_at', 'safe', 'on' => 'search'),
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
			'pad_id'		 => 'Pad',
			'pad_pat_id'	 => 'Pad Pat',
			'pad_request'	 => 'Pad Request',
			'pad_response'	 => 'Pad Response',
			'pad_created_at' => 'Pad Created At',
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

		$criteria->compare('pad_id', $this->pad_id);
		$criteria->compare('pad_pat_id', $this->pad_pat_id);
		$criteria->compare('pad_request', $this->pad_request, true);
		$criteria->compare('pad_response', $this->pad_response, true);
		$criteria->compare('pad_created_at', $this->pad_created_at, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerApiDetails the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function resendApiPushData()
	{
		$sql = "SELECT pat.pat_id,pat.pat_type,pat.pat_booking_id FROM `partner_api_tracking` pat 
				WHERE pat.pat_status=2";

		$rows = DBUtil::queryAll($sql);
		return $rows;
	}

}
