<?php

/**
 * This is the model class for table "area_select_model".
 *
 * The followings are the available columns in table 'area_select_model':
 * @property integer $asm_id
 * @property integer $asm_area_type
 * @property integer $asm_area_id
 * @property integer $asm_markup_type
 * @property integer $asm_markup
 * @property integer $asm_model_id
 * @property string $asm_create_date
 * @property string $asm_modified_date
 */
class AreaSelectModel extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'area_select_model';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('asm_area_type', 'required'),
			array('asm_area_type, asm_area_id, asm_markup_type, asm_markup, asm_model_id', 'numerical', 'integerOnly' => true),
			array('asm_modified_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('asm_id, asm_area_type, asm_area_id, asm_markup_type, asm_markup, asm_model_id', 'safe', 'on' => 'search'),
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
			'asm_id'			 => 'Asm',
			'asm_area_type'		 => 'Asm Area Type',
			'asm_area_id'		 => 'Asm Area',
			'asm_markup_type'	 => 'Asm Markup Type',
			'asm_markup'		 => 'Asm Markup',
			'asm_model_id'		 => 'Asm Model',
			'asm_create_date'	 => 'Asm Create Date',
			'asm_modified_date'	 => 'Asm Modified Date',
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

		$criteria->compare('asm_id', $this->asm_id);
		$criteria->compare('asm_area_type', $this->asm_area_type);
		$criteria->compare('asm_area_id', $this->asm_area_id);
		$criteria->compare('asm_markup_type', $this->asm_markup_type);
		$criteria->compare('asm_markup', $this->asm_markup);
		$criteria->compare('asm_model_id', $this->asm_model_id);
		$criteria->compare('asm_create_date', $this->asm_create_date, true);
		$criteria->compare('asm_modified_date', $this->asm_modified_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AreaSelectModel the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getRateWithMarkUp($city, $vhcModelSelected, $rte_vendor_amount)
	{
		$result	 = self::getByCityVhcModel($city, $vhcModelSelected);
		$amt	 = self::addMarkup($result['asm_markup_type'], $result['asm_markup'], $rte_vendor_amount);
		return $amt;
	}

	public static function getPriceRuleWithMarkUp($city, $vhcModelSelected, $priceRule)
	{
		$result							 = self::getByCityVhcModel($city, $vhcModelSelected);
		$priceRule->prr_min_base_amount	 = self::addMarkup($result['asm_markup_type'], $result['asm_markup'], $priceRule->prr_min_base_amount);
		if ($result['asm_markup_type'] == 1)
		{
			$priceRule->prr_rate_per_km				 = self::addMarkup($result['asm_markup_type'], $result['asm_markup'], $priceRule->prr_rate_per_km);
			$priceRule->prr_rate_per_km_extra		 = self::addMarkup($result['asm_markup_type'], $result['asm_markup'], $priceRule->prr_rate_per_km_extra);
			$priceRule->prr_day_driver_allowance	 = self::addMarkup($result['asm_markup_type'], $result['asm_markup'], $priceRule->prr_day_driver_allowance);
			$priceRule->prr_night_driver_allowance	 = self::addMarkup($result['asm_markup_type'], $result['asm_markup'], $priceRule->prr_night_driver_allowance);
			$priceRule->prr_rate_per_minute			 = self::addMarkup($result['asm_markup_type'], $result['asm_markup'], $priceRule->prr_rate_per_minute);
			$priceRule->prr_rate_per_minute_extra	 = self::addMarkup($result['asm_markup_type'], $result['asm_markup'], $priceRule->prr_rate_per_minute_extra);
		}
		return $priceRule;
	}

	/**
	 * @deprecated since version number 2021-09-02
	 */
	public static function getByCityVhcModel($city, $vhcModelSelected = 0)
	{
		$cityInfo	 = Cities::getStateZoneInfoByCity($city);
		$sql		 = "SELECT area_select_model.*,CASE asm_area_type
						WHEN 1 THEN 10
						WHEN 2 THEN 5
						WHEN 3 THEN 20   
						ELSE 1
						END AS rank FROM area_select_model
						WHERE ((asm_area_type = 1 AND asm_area_id IN (:zones))  OR (asm_area_type = 2 AND asm_area_id IN (:state)) OR
						(asm_area_type = 3 AND asm_area_id IN (:city)) OR (asm_area_type = 4 AND asm_area_id IN (:region)))
						AND asm_model_id=:asm_model_id  ORDER BY rank";
		$params		 = ['zones' => $cityInfo['zones'], 'state' => $cityInfo['stt_id'], 'city' => $city, 'region' => $cityInfo['region'], 'asm_model_id' => $vhcModelSelected];
		$data		 = DBUtil::queryRow($sql, DBUtil::SDB(), $params, 60 * 60, "PriceRule");
		return $data;
	}

	/**
	 * @deprecated since version number 2021-09-02
	 */
	public static function getByCityVhcModelList($city)
	{
		$cityInfo	 = Cities::getStateZoneInfoByCity($city);
		$sql		 = "SELECT area_select_model.*,CASE asm_area_type
					WHEN 1 THEN 10
					WHEN 2 THEN 5
					WHEN 3 THEN 20   
					ELSE 1
					END AS rank FROM area_select_model
					WHERE ((asm_area_type = 1 AND asm_area_id IN (:zones))  OR (asm_area_type = 2 AND asm_area_id IN (:state)) OR
					 (asm_area_type = 3 AND asm_area_id IN (:city)) OR (asm_area_type = 4 AND asm_area_id IN (:region))) ORDER BY rank";
		$params		 = ['zones' => $cityInfo['zones'], 'state' => $cityInfo['stt_id'], 'city' => $city, 'region' => $cityInfo['region']];
		$data		 = DBUtil::queryAll($sql, DBUtil::SDB(), $params, true, 60 * 60, "PriceRule");
		return $data;
	}

	public static function addMarkup($type, $value, $amount)
	{
		if ($type == 1)
		{
			$amount = $amount * (100 + $value) / 100;
		}
		else
		{
			$amount = $amount + $value;
		}
		return round($amount);
	}
    /**
	 * @deprecated since version 02/09/2021
	 */
	public static function getMarkupValueByModelId($srvClassModel)
	{
		$sql	 = "SELECT asm.asm_markup_type, asm.asm_markup
				FROM `area_select_model` asm
				WHERE asm.asm_model_id = $srvClassModel";
		$value	 = DBUtil::queryRow($sql, DBUtil::SDB(), 60 * 60, "PriceRule");
		return $value;
	}

	public static function getCalculatedMarkupByBaseFare($modelMarkupVal, $baseamount, $discAmount)
	{
		if ($modelMarkupVal[asm_markup_type] == 1)
		{
			$amount['calAmount'] = round($baseamount + ($baseamount * $modelMarkupVal['asm_markup']) / 100);
			$amount['discount']	 = $amount['calAmount'] - $discAmount;
		}
		else
		{
			$amount['calAmount'] = round($baseamount + $modelMarkupVal['asm_markup']);
			$amount['discount']	 = $amount['calAmount'] - $discAmount;
		}
		return $amount;
	}

}
