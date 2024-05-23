<?php

/**
 * This is the model class for table "prebooking_price_factor".
 *
 * The followings are the available columns in table 'prebooking_price_factor':
 * @property integer $ppf_id
 * @property integer $ppf_lead_id
 * @property integer $bkg_ddbp_base_amount
 * @property double $bkg_ddbp_surge_factor
 * @property integer $bkg_manual_base_amount
 * @property integer $bkg_regular_base_amount
 * @property integer $bkg_ddbp_route_flag
 * @property integer $bkg_ddbp_master_flag
 * @property integer $bkg_surge_applied
 * @property integer $bkg_ddbp_factor_type
 * @property integer $bkg_dtbp_base_amount
 * @property integer $bkg_manual_surge_id
 * @property integer $bkg_route_route_factor 
 * @property integer $bkg_zone_zone_factor
 * @property integer $bkg_zone_state_factor
 * @property double  $bkg_zone_factor
 *  */
class PrebookingPriceFactor extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'prebooking_price_factor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ppf_id, ppf_lead_id, bkg_ddbp_base_amount, bkg_ddbp_surge_factor, bkg_manual_base_amount, bkg_regular_base_amount, bkg_ddbp_route_flag, bkg_ddbp_master_flag, bkg_surge_applied, bkg_ddbp_factor_type, bkg_dtbp_base_amount, bkg_manual_surge_id,bkg_zone_zone_factor,bkg_zone_state_factor,bkg_zone_factor ', 'safe', 'on'=>'search'),
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
			'ppf_id' => 'Ppf',
			'ppf_lead_id' => 'Ppf Lead',
			'bkg_ddbp_base_amount' => 'Bkg Ddbp Base Amount',
			'bkg_ddbp_surge_factor' => 'Bkg Ddbp Surge Factor',
			'bkg_manual_base_amount' => 'Bkg Manual Base Amount',
			'bkg_regular_base_amount' => 'Bkg Regular Base Amount',
			'bkg_ddbp_route_flag' => 'Bkg Ddbp Route Flag',
			'bkg_ddbp_master_flag' => 'Bkg Ddbp Master Flag',
			'bkg_surge_applied' => 'Bkg Surge Applied',
			'bkg_ddbp_factor_type' => 'Bkg Ddbp Factor Type',
			'bkg_dtbp_base_amount' => 'Bkg Dtbp Base Amount',
			'bkg_manual_surge_id' => 'Bkg Manual Surge',
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

		$criteria->compare('ppf_id',$this->ppf_id);
		$criteria->compare('ppf_lead_id',$this->ppf_lead_id);
		$criteria->compare('bkg_ddbp_base_amount',$this->bkg_ddbp_base_amount);
		$criteria->compare('bkg_ddbp_surge_factor',$this->bkg_ddbp_surge_factor);
		$criteria->compare('bkg_manual_base_amount',$this->bkg_manual_base_amount);
		$criteria->compare('bkg_regular_base_amount',$this->bkg_regular_base_amount);
		$criteria->compare('bkg_ddbp_route_flag',$this->bkg_ddbp_route_flag);
		$criteria->compare('bkg_ddbp_master_flag',$this->bkg_ddbp_master_flag);
		$criteria->compare('bkg_surge_applied',$this->bkg_surge_applied);
		$criteria->compare('bkg_ddbp_factor_type',$this->bkg_ddbp_factor_type);
		$criteria->compare('bkg_dtbp_base_amount',$this->bkg_dtbp_base_amount);
		$criteria->compare('bkg_manual_surge_id',$this->bkg_manual_surge_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PrebookingPriceFactor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function updateQuote(Quote $qModel)
	{
		$this->populateFromQuote($qModel);
		return $this->save();
	}

	public function populateFromQuote(Quote $qModel)
	{
		$routeRates						 = $qModel->routeRates;
		$this->bkg_ddbp_base_amount		 = $routeRates->srgDDBP->rockBaseAmount;
		$this->bkg_ddbp_master_flag		 = Yii::app()->params['dynamicSurge'];
		$this->bkg_ddbp_route_flag		 = $routeRates->srgDDBP->refModel->routeFlag;
		$this->bkg_ddbp_surge_factor	 = $routeRates->srgDDBP->refModel->dprApplied->factor;
		$this->bkg_manual_base_amount	 = $routeRates->srgManual->rockBaseAmount;
		$this->bkg_manual_surge_id       = $routeRates->srgManual->refId|0;
		$this->bkg_regular_base_amount	 = $routeRates->regularBaseAmount;
		$this->bkg_dtbp_base_amount      = $routeRates->srgDTBP->rockBaseAmount;
		$this->bkg_surge_applied		 = $routeRates->surgeFactorUsed;
		$this->bkg_ddbp_factor_type      = $routeRates->srgDDBP->refModel->dprApplied->type;
        $this->bkg_route_route_factor    = $routeRates->srgDDBP->refModel->dprRoutes->factor; 
        $this->bkg_zone_zone_factor      = $routeRates->srgDDBP->refModel->dprZoneRoutes->factor;
        $this->bkg_zone_state_factor     = $routeRates->srgDDBP->refModel->dprZonesStates->factor;
        $this->bkg_zone_factor           = $routeRates->srgDDBP->refModel->dprZones->factor;
		#return $this->save();
	}

	public function updatePreQuote(Quote $qModel, $leadBookingId)
	{
		$this->populateFromQuote($qModel);
		$this->ppf_lead_id = $leadBookingId;
		$this->save();
	}

}
