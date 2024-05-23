<?php

/**
 * This is the model class for table "whatsapp_initiate_track".
 *
 * The followings are the available columns in table 'whatsapp_initiate_track':
 * @property integer $wit_id
 * @property integer $wit_template_id
 * @property integer $wit_initiate_by
 * @property integer $wit_initiate_type
 * @property string $wit_finitiate_date
 * @property string $wit_linitiate_date
 * @property string $wit_phone_number
 * @property integer $wit_status
 * @property string $wit_create_date
 * @property string $wit_modified_on
 * @property integer $wit_active
 */
class WhatsappInitiateTrack extends CActiveRecord
{

	const INITIATE_BY_GOZO			 = 1;
	const INITIATE_BY_CUSTOMER		 = 2;
	const INITIATE_TYPE_UTILITY		 = 1;
	const INITIATE_TYPE_AUTHENTICATION = 2;
	const INITIATE_TYPE_MARKETING		 = 3;
	const INITIATE_TYPE_USER			 = 4;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'whatsapp_initiate_track';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('wit_phone_number', 'required'),
			array('wit_template_id, wit_initiate_by, wit_initiate_type, wit_status, wit_active', 'numerical', 'integerOnly' => true),
			array('wit_phone_number', 'length', 'max' => 255),
			array('wit_finitiate_date,wit_linitiate_date, wit_modified_on', 'safe'),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('wit_id, wit_template_id, wit_initiate_by, wit_initiate_type, wit_finitiate_date,wit_linitiate_date, wit_phone_number, wit_status, wit_create_date, wit_modified_on, wit_active', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'wit_id'			 => 'Wit',
			'wit_template_id'	 => 'Template Id',
			'wit_initiate_by'	 => 'Initiate By',
			'wit_initiate_type'	 => '1=>Utility, 2=>Authentication, 3=>Marketing,4=>User',
			'wit_finitiate_date' => 'First Initiate Date',
			'wit_linitiate_date' => 'Last Initiate Date',
			'wit_phone_number'	 => 'Phone Number',
			'wit_status'		 => '0=>Whatsapp initiate expiry , 1=> Whatsapp initiate active',
			'wit_create_date'	 => 'Create Date',
			'wit_modified_on'	 => 'Modified On',
			'wit_active'		 => 'Active',
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

		$criteria->compare('wit_id', $this->wit_id);
		$criteria->compare('wit_template_id', $this->wit_template_id);
		$criteria->compare('wit_initiate_by', $this->wit_initiate_by);
		$criteria->compare('wit_initiate_type', $this->wit_initiate_type);
		$criteria->compare('wit_finitiate_date', $this->wit_finitiate_date, true);
		$criteria->compare('wit_linitiate_date', $this->wit_linitiate_date, true);
		$criteria->compare('wit_phone_number', $this->wit_phone_number, true);
		$criteria->compare('wit_status', $this->wit_status);
		$criteria->compare('wit_create_date', $this->wit_create_date, true);
		$criteria->compare('wit_modified_on', $this->wit_modified_on, true);
		$criteria->compare('wit_active', $this->wit_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WhatsappInitiateTrack the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used for data setup for booking referral track
	 * @param type array
	 * @return retuenSet
	 * @throws Exception
	 */
	public static function add($row)
	{
		$returnSet = new ReturnSet();
		try
		{
			$model = WhatsappInitiateTrack::setWhatsappInititateData($row);
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			if ($model->wit_id > 0)
			{
				$returnSet->setData(['whatsappInititateId' => (int) $model->wit_id]);
				$returnSet->setStatus("Data added successfully.");
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for updating the followup status
	 * @param type $initiateBy
	 * @param type $initiateType
	 * @param type $phoneNumber
	 * @return type int
	 */
	public static function updateStatus($initiateBy, $initiateType, $phoneNumber)
	{
		$params	 = ['initiateBy' => $initiateBy, 'initiateType' => $initiateType, 'phoneNumber' => $phoneNumber];
		$sql	 = "UPDATE whatsapp_initiate_track 
							SET wit_status=0,wit_modified_on=NOW()
						WHERE  1 
							AND wit_active=1
							AND wit_status=1 
							AND wit_initiate_by=:initiateBy
							AND wit_initiate_type =:initiateType 
							AND wit_phone_number=:phoneNumber
							AND wit_finitiate_date IS NOT NULL 
							AND wit_finitiate_date<DATE_SUB(NOW(),INTERVAL 1 DAY)";
		return DBUtil::execute($sql, $params);
	}

	/**
	 * This function is used for checking that initiate is active or not 
	 * @param type $initiateBy
	 * @param type $initiateType
	 * @param type $phoneNumber
	 * @return type int
	 */
	public static function isInitiateAlive($initiateBy, $initiateType, $phoneNumber)
	{
		$firstInitiateDate = WhatsappInitiateTrack::getFirstInitiateDate($initiateBy, $initiateType, $phoneNumber);
		return WhatsappInitiateTrack::isFirstInitiateDateValid($firstInitiateDate) ? true : false;
	}

	/**
	 * This function is used for getting first initiate date
	 * @param type $initiateBy
	 * @param type $initiateType
	 * @param type $phoneNumber
	 * @return type string
	 */
	public static function getFirstInitiateDate($initiateBy, $initiateType, $phoneNumber)
	{
		$params	 = ['initiateBy' => $initiateBy, 'initiateType' => $initiateType, 'phoneNumber' => $phoneNumber];
		$sql	 = "SELECT 
							wit_finitiate_date 
						FROM whatsapp_initiate_track
						WHERE  1 
							AND wit_active=1
							AND wit_status=1
							AND wit_initiate_by=:initiateBy
							AND wit_initiate_type =:initiateType 
							AND wit_phone_number=:phoneNumber
							AND wit_finitiate_date IS NOT NULL 
							ORDER BY wit_id  DESC LIMIT 0,1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	/**
	 * This function is used for getting first initiate date is valid or not
	 * @param type $finitiateDate
	 * @return type boolean
	 */
	public static function isFirstInitiateDateValid($finitiateDate)
	{
		if (empty($finitiateDate) || $finitiateDate == null)
		{
			return false;
		}
		else if (strtotime(date('Y-m-d H:i:s', strtotime($finitiateDate . ' +1 day'))) >= strtotime(date('Y-m-d H:i:s')))
		{
			return true;
		}
		else if (strtotime(date('Y-m-d H:i:s', strtotime($finitiateDate . ' +1 day'))) < strtotime(date('Y-m-d H:i:s')))
		{
			return false;
		}
	}

	/**
	 * This function is used for setting the data for whatSapp initiate track
	 * @param type $row array
	 * @return type model
	 */
	public static function setWhatsappInititateData($row)
	{
		$model				 = new WhatsappInitiateTrack();
		$firstInitiateDate	 = WhatsappInitiateTrack::getFirstInitiateDate($row['initiateBy'], $row['initiateType'], $row['phoneNumber']);
		if (WhatsappInitiateTrack::isFirstInitiateDateValid($firstInitiateDate))
		{
			$model->wit_finitiate_date	 = $firstInitiateDate;
			$model->wit_linitiate_date	 = new CDbExpression("NOW()");
		}
		else
		{
			$model->wit_finitiate_date	 = new CDbExpression("NOW()");
			$model->wit_linitiate_date	 = new CDbExpression("NOW()");
		}

		$model->wit_template_id		 = $row['templateId'];
		$model->wit_initiate_by		 = $row['initiateBy'];
		$model->wit_initiate_type	 = $row['initiateType'];
		$model->wit_phone_number	 = $row['phoneNumber'];
		$model->wit_status			 = 1;
		$model->wit_create_date		 = new CDbExpression("NOW()");
		$model->wit_modified_on		 = new CDbExpression("NOW()");
		$model->wit_active			 = 1;
		return $model;
	}

}
