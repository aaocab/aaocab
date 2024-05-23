<?php

/**
 * This is the model class for table "drivers_add_details".
 *
 * The followings are the available columns in table 'drivers_add_details':
 * @property integer $dad_id
 * @property integer $dad_drv_id
 * @property string $dad_bank_name
 * @property string $dad_bank_branch
 * @property string $dad_beneficiary_name
 * @property string $dad_beneficiary_id
 * @property integer $dad_account_type
 * @property string $dad_bank_ifsc
 * @property string $dad_bank_account_no
 * @property integer $dad_doc_score
 * @property double $dad_redeem_amount
 * @property integer $dad_redeem_request
 * @property string $dad_request_date
 * @property integer $dad_active
 *
 * The followings are the available model relations:
 * @property Drivers $dadDrv
 */
class DriversAddDetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'drivers_add_details';
	}

	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dad_bank_name, dad_bank_branch', 'length', 'max'=>100),
			array('dad_beneficiary_name, dad_beneficiary_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dad_id, dad_drv_id, dad_bank_name, dad_bank_branch, dad_beneficiary_name, dad_beneficiary_id, dad_account_type, dad_bank_ifsc, dad_bank_account_no, dad_doc_score, dad_redeem_amount, dad_redeem_request, dad_request_date, dad_active', 'safe', 'on'=>'search'),
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
			'dadDrv' => array(self::BELONGS_TO, 'Drivers', 'dad_drv_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'dad_id' => 'Dad',
			'dad_drv_id' => 'Dad Drv',
			'dad_bank_name' => 'Dad Bank Name',
			'dad_bank_branch' => 'Dad Bank Branch',
			'dad_beneficiary_name' => 'Dad Beneficiary Name',
			'dad_beneficiary_id' => 'Dad Beneficiary',
			'dad_account_type' => 'Dad Account Type',
			'dad_bank_ifsc' => 'Dad Bank Ifsc',
			'dad_bank_account_no' => 'Dad Bank Account No',
		        'dad_doc_score' => 'Dad Doc Score',
		        'dad_redeem_amount' => 'Redeem Amount',
			'dad_redeem_request' => 'Redeem Request',
			'dad_request_date' => 'Redeem Request Date',
			'dad_active' => 'Dad Active',
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

		$criteria->compare('dad_id',$this->dad_id);
		$criteria->compare('dad_drv_id',$this->dad_drv_id);
		$criteria->compare('dad_bank_name',$this->dad_bank_name,true);
		$criteria->compare('dad_bank_branch',$this->dad_bank_branch,true);
		$criteria->compare('dad_beneficiary_name',$this->dad_beneficiary_name,true);
		$criteria->compare('dad_beneficiary_id',$this->dad_beneficiary_id,true);
		$criteria->compare('dad_account_type',$this->dad_account_type);
		$criteria->compare('dad_bank_ifsc',$this->dad_bank_ifsc,true);
		$criteria->compare('dad_bank_account_no',$this->dad_bank_account_no,true);
		$criteria->compare('dad_doc_score',$this->dad_doc_score);
		$criteria->compare('dad_redeem_amount',$this->dad_redeem_amount);
		$criteria->compare('dad_redeem_request',$this->dad_redeem_request);
		$criteria->compare('dad_request_date',$this->dad_request_date);
		$criteria->compare('dad_active',$this->dad_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DriversAddDetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function findByDriverId($drv_id)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('dad_drv_id', $drv_id);
		$model		 = $this->find($criteria);
		if ($model)
		{
			return $model;
		}
		else
		{
			return false;
		}
	}

}
