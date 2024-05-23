<?php

/**
 * This is the model class for table "vendor_quote".
 *
 * The followings are the available columns in table 'vendor_quote':
 * @property integer $vqt_id
 * @property integer $vqt_cqt_id
 * @property integer $vqt_vendor_id
 * @property integer $vqt_amount
 * @property string $vqt_description
 * @property string $vqt_created
 * @property integer $vqt_status
 * @property integer $vqt_active
 * 
 * The followings are the available model relations:
 * @property CustomQuote $vqtCqt
 * @property Vendors $vqtVendor
 */
class VendorQuote extends CActiveRecord
{

	public $isInterested;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_quote';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vqt_cqt_id, vqt_vendor_id', 'required'),
			['vqt_cqt_id', 'validateCustomBid', 'on' => 'insert,update'],
			array('vqt_cqt_id, vqt_vendor_id, vqt_amount, vqt_status, vqt_active', 'numerical', 'integerOnly' => true),
			array('vqt_description', 'length', 'max' => 5000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vqt_id, vqt_cqt_id, vqt_vendor_id, vqt_amount, vqt_description, vqt_created, vqt_status, vqt_active,isInterested', 'safe', 'on' => 'search'),
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
			'vqtCqt'	 => array(self::BELONGS_TO, 'CustomQuote', 'vqt_cqt_id'),
			'vqtVendor'	 => array(self::BELONGS_TO, 'Vendors', 'vqt_vendor_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vqt_id'			 => 'Vqt',
			'vqt_cqt_id'		 => 'Cqt',
			'vqt_vendor_id'		 => 'Vendor',
			'vqt_amount'		 => 'Amount',
			'vqt_description'	 => 'Description',
			'vqt_created'		 => 'Created',
			'vqt_status'		 => 'Status',
			'vqt_active'		 => 'Active',
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

		$criteria->compare('vqt_id', $this->vqt_id);
		$criteria->compare('vqt_cqt_id', $this->vqt_cqt_id);
		$criteria->compare('vqt_vendor_id', $this->vqt_vendor_id);
		$criteria->compare('vqt_amount', $this->vqt_amount);
		$criteria->compare('vqt_description', $this->vqt_description, true);
		$criteria->compare('vqt_created', $this->vqt_created, true);
		$criteria->compare('vqt_status', $this->vqt_status);
		$criteria->compare('vqt_active', $this->vqt_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorQuote the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getQuotesByRequest($cqt)
	{
		
		$sql	 = "SELECT vqt.*,cty.cty_name,vct.vct_label cabtype,vqt.vqt_cqt_id,vnd_name,
					if(cqt_pickup_date < NOW(),0,1) futureDate,cqt_no_of_days,cqt_booking_type,cqt_pickup_date
					FROM   custom_quote cqt 
					JOIN cities cty ON cty.cty_id = cqt.cqt_from_city
					LEFT JOIN svc_class_vhc_cat scvhc ON scvhc.scv_id = cqt.cqt_cab_type
					LEFT JOIN vehicle_category vct ON vct.vct_id = scvhc.scv_vct_id
					LEFT JOIN service_class ON scc_id = scvhc.scv_scc_id
					JOIN vendor_quote vqt ON cqt.cqt_id = vqt.vqt_cqt_id 
					JOIN vendors vnd ON vnd.vnd_id = vqt.vqt_vendor_id 
                    LEFT JOIN vendor_stats vstats ON vstats.vrs_vnd_id = vqt.vqt_vendor_id 
					WHERE cqt.cqt_active=1 and cqt_id =  :cqt  ";
		$result	 = DBUtil::queryAll($sql, DBUtil::SDB(), ['cqt' => $cqt]);
		return $result;
	}

	public static function getExistingBid($vnd, $cqt)
	{
		$sql	 = "SELECT vqt.* 
				FROM    vendor_quote vqt  
				JOIN vendors vnd ON vnd.vnd_id = vqt.vqt_vendor_id 
				WHERE vqt.vqt_active=1 and vqt.vqt_cqt_id =  :cqt  AND vqt.vqt_vendor_id =  :vndid";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), ['cqt' => $cqt, 'vndid' => $vnd]);
		return $result;
	}

	public function validateCustomBid($attribute, $params)
	{
		$error = 0;
		if ($this->vqt_cqt_id == '')
		{
			$this->addError('id', 'Reference Id is missing');
			$error++;
		}
		if (!$this->vqtCqt)
		{
			$this->addError('id', 'Invalid reference quote id');
			$error++;
		}
		if ($this->vqt_vendor_id == '')
		{
			$this->addError('vqt_vendor_id', 'Vendor id is not found');
			$error++;
		}
		if ($this->isInterested)
		{
			if ($this->vqt_amount == '' || is_nan($this->vqt_amount))
			{
				$this->addError('amount', 'Please check the amount');
				$error++;
			}
		}
		if ($error > 0)
		{
			return false;
		}
		return TRUE;
	}

	public function addNew()
	{
		$vnd	 = $this->vqt_vendor_id;
		$cqt	 = $this->vqt_cqt_id;
		$isExist = VendorQuote::getExistingBid($vnd, $cqt);
		$model	 = new VendorQuote();
		if ($isExist['vqt_id'] > 0)
		{
			$model			 = VendorQuote::model()->findByPk($isExist['vqt_id']);
			$this->vqt_id	 = $isExist['vqt_id'];
			$this->setScenario('update');
		}
		else
		{
			$model->vqtCqt = CustomQuote::model()->findByPk($cqt);
		}
		$model->attributes	 = $this->attributes;
		$model->isInterested = $this->isInterested;
		$model->vqt_created	 = new CDbExpression('NOW()');		 
		if (!$model->save())
		{
			$errors = $model->getErrors();
			throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
		}
		$logModel = BidQuoteLog::create($model);
		if ($logModel->hasErrors())
		{
			$errors = $logModel->getErrors();
			throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
		}
		return $model;
	}

}
