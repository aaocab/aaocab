<?php

/**
 * This is the model class for table "callback".
 *
 * The followings are the available columns in table 'callback':
 * @property integer $cbk_id
 * @property integer $cbk_contact_id
 * @property string $cbk_desc
 * @property integer $cbk_ref_type
 * @property integer $cbk_ref_id
 * @property integer $cbk_assigned_csr
 * @property string $cbk_created_on
 * @property integer $cbk_status
 * @property string $cbk_csr_remarks
 * The followings are the available model relations:
 * @property Contact $cbkContact
 */
class Callback extends CActiveRecord
{

	const NEW_BOOKING_CALLBACK		 = 1;
	const EXISTING_BOOKING_CALLBACK	 = 2;

	public $cbk_fname, $cbk_lname, $cbk_phone, $cbk_email;
	public $cbk_country_code;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'callback';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cbk_desc,cbk_contact_id', 'required'),
			array('cbk_contact_id, cbk_ref_type, cbk_ref_id, cbk_assigned_csr, cbk_status', 'numerical', 'integerOnly' => true),
			array('cbk_desc, cbk_csr_remarks', 'length', 'max' => 255),
			array('cbk_created_on', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cbk_id, cbk_contact_id, cbk_desc, cbk_ref_type, cbk_ref_id, cbk_assigned_csr, cbk_created_on, cbk_status, cbk_csr_remarks', 'safe', 'on' => 'search'),
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
			'cbk_id'			 => 'Cbk',
			'cbk_contact_id'	 => 'Cbk Contact',
			'cbk_desc'			 => 'Cbk Desc',
			'cbk_ref_type'		 => 'Cbk Ref Type',
			'cbk_ref_id'		 => 'Cbk Ref',
			'cbk_assigned_csr'	 => 'Cbk Assigned Csr',
			'cbk_created_on'	 => 'Cbk Created On',
			'cbk_status'		 => 'Cbk Status',
			'cbk_csr_remarks'	 => 'Cbk Csr Remarks',
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

		$criteria->compare('cbk_id', $this->cbk_id);
		$criteria->compare('cbk_contact_id', $this->cbk_contact_id);
		$criteria->compare('cbk_desc', $this->cbk_desc, true);
		$criteria->compare('cbk_ref_type', $this->cbk_ref_type);
		$criteria->compare('cbk_ref_id', $this->cbk_ref_id);
		$criteria->compare('cbk_assigned_csr', $this->cbk_assigned_csr);
		$criteria->compare('cbk_created_on', $this->cbk_created_on, true);
		$criteria->compare('cbk_status', $this->cbk_status);
		$criteria->compare('cbk_csr_remarks', $this->cbk_csr_remarks, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Callback the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function returns the reason list
	 * @return string
	 */
	public static function getReasonList()
	{
		$reasons = [
					1	 => 'New Booking',
					2	 => 'Existing Booking',
					3	 => 'New Vendor Attachment',
					4	 => 'Existing Vendor',
		];
		return $reasons;
	}

	public static function add($contactId, $jsonObject)
	{
		$model					 = new Callback();
		$model->cbk_contact_id	 = $contactId;
		$model->cbk_ref_type	 = $jsonObject->refTypeId;
		$model->cbk_desc		 = $jsonObject->refDesc;
		$model->cbk_status		 = 1;
		$model->cbk_created_on	 = new CDbExpression('NOW()');

		if (!$model->save())
		{
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_INVALID_DATA);
		}

		return $model->cbk_id;
	}

	public static function processData($jsonObject)
	{
		$returnSet = new ReturnSet();
		$userId		 = UserInfo::getUserId();
		$userModel	 = Users::model()->findByPk($userId);
		$contactId	 = $userModel->usr_contact_id;
		if (empty($contactId))
		{
			$contactId = Users::createByUser($userModel);
		}
		$isVerified = ContactPhone::isVerified($contactId);
		if(!$isVerified)
		{
			$returnSet->setMessage("Oops!!. I can't register your request as it seems like your contact details are not verified with us");
			goto skipAll;
		}
		
		$id = self::add($contactId, $jsonObject);
		$returnSet->setMessage("Your requested has been send to the respective department. You will receive a call back on your verified registered number");
		$returnSet->setData($id);
		skipAll:
		return $returnSet;
	}

}
