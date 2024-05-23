<?php

/**
 * This is the model class for table "addon_cab_models".
 *
 * The followings are the available columns in table 'addon_cab_models':
 * @property integer $acm_id
 * @property integer $acm_area_type
 * @property integer $acm_area_id
 * @property integer $acm_scv_id_from
 * @property integer $acm_svc_id_to
 * @property string $acm_margin_data
 * @property integer $acm_is_allowed
 * @property integer $acm_active
 * @property string $acm_created_at
 * @property string $acm_modified_at
 */
class AddonCabModels extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'addon_cab_models';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('acm_scv_id_from, acm_svc_id_to, acm_margin_data, acm_created_at', 'required'),
			array('acm_area_type, acm_area_id, acm_scv_id_from, acm_svc_id_to, acm_is_allowed, acm_active', 'numerical', 'integerOnly' => true),
			array('acm_margin_data', 'length', 'max' => 300),
			array('acm_modified_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('acm_id, acm_area_type, acm_area_id, acm_scv_id_from, acm_svc_id_to, acm_margin_data, acm_is_allowed, acm_active, acm_created_at, acm_modified_at', 'safe', 'on' => 'search'),
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
			'acm_id'			 => 'Acm',
			'acm_area_type'		 => 'Acm Area Type',
			'acm_area_id'		 => 'Acm Area',
			'acm_scv_id_from'	 => 'Acm Scv Id From',
			'acm_svc_id_to'		 => 'Acm Svc Id To',
			'acm_margin_data'	 => 'Acm Margin Data',
			'acm_is_allowed'	 => 'Acm Is Allowed',
			'acm_active'		 => 'Acm Active',
			'acm_created_at'	 => 'Acm Created At',
			'acm_modified_at'	 => 'Acm Modified At',
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

		$criteria->compare('acm_id', $this->acm_id);
		$criteria->compare('acm_area_type', $this->acm_area_type);
		$criteria->compare('acm_area_id', $this->acm_area_id);
		$criteria->compare('acm_scv_id_from', $this->acm_scv_id_from);
		$criteria->compare('acm_svc_id_to', $this->acm_svc_id_to);
		$criteria->compare('acm_margin_data', $this->acm_margin_data, true);
		$criteria->compare('acm_is_allowed', $this->acm_is_allowed);
		$criteria->compare('acm_active', $this->acm_active);
		$criteria->compare('acm_created_at', $this->acm_created_at, true);
		$criteria->compare('acm_modified_at', $this->acm_modified_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AddonCabModels the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * this returns cab models visible for particular city cab type and trip type
	 * @param type $fcity
	 * @param type $tcity
	 * @param type $scvId
	 * @param type $tripType
	 * @param type $scvId
	 * @return array
	 */
	public static function getByCtyVehicleType($fcity, $tcity, $scvId, $tripType)
	{

		$key	 = md5("addon_cab_models_getByCtyVehicleType" . "_" . $fcity . "_" . $tcity . "_" . $scvId . "_" . $tripType);
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			$res = $data;
			goto result;
		}
		$fcityInfo			 = Cities::getStateZoneInfoByCity($fcity);
		$zoneType			 = DynamicZoneSurge::getZoneTypeByCityCab($fcity, $tcity, $scvId, $tripType);
		$zoneType			 = (!$zoneType) ? -1 : $zoneType;
		$fcityInfo['zones']	 = ($fcityInfo['zones'] == '') ? 0 : $fcityInfo['zones'];
		$fcityInfo['stt_id'] = ($fcityInfo['stt_id'] == '') ? 0 : $fcityInfo['stt_id'];
		$fcityInfo['region'] = ($fcityInfo['region'] == '') ? 0 : $fcityInfo['region'];

		$allZones = array_filter(explode(',', $fcityInfo['zones']));
		if (count($allZones) == 0)
		{
			$allZones = [-1];
		}
		$paramsZone		 = DBUtil::getINStatement($allZones, $bindZonesString, $paramsZone);
		$paramsCity		 = DBUtil::getINStatement([$fcity], $bindCityString, $paramsCity);
		$paramsState	 = DBUtil::getINStatement([$fcityInfo['stt_id']], $bindStateString, $paramsState);
		$paramsRegion	 = DBUtil::getINStatement([$fcityInfo['region']], $bindRegionString, $paramsRegion);

		$sql = "SELECT addon_cab_models.*,
								CASE
											WHEN acm_area_type=1 AND acm_area_id IN ({$fcityInfo['zones']}) THEN 5
											WHEN acm_area_type=2 AND acm_area_id IN ({$fcityInfo['stt_id']}) THEN 15
											WHEN acm_area_type=3 AND acm_area_id IN ({$fcity}) THEN 20
											WHEN acm_area_type=4 AND acm_area_id IN ({$fcityInfo['region']}) THEN 1
											WHEN acm_area_type=5 AND acm_area_id IN ({$zoneType}) THEN 10
											ELSE 0
											END AS rank FROM addon_cab_models
						WHERE (
								(acm_area_type = 1 AND acm_area_id IN ({$bindZonesString}))
								OR (acm_area_type = 2 AND acm_area_id IN ($bindStateString))
								OR (acm_area_type = 3 AND acm_area_id IN ($bindCityString))
								OR (acm_area_type = 4 AND acm_area_id IN ($bindRegionString))
								OR (acm_area_type = 5 AND acm_area_id IN ({$zoneType}))
								OR acm_area_type IS NULL)
							AND acm_scv_id_from = :svcId AND acm_active=1
						ORDER BY rank DESC";

		$params		 = $paramsZone + $paramsCity + $paramsState + $paramsRegion + ['svcId' => $scvId];
		$rows		 = DBUtil::query($sql, DBUtil::SDB(), $params, 60 * 60 * 6, "addons");
		$arrIncluded = [];
		$arrExcluded = [];
		foreach ($rows as $row)
		{
			if ($row['acm_is_allowed'] == 1 && !in_array($row['acm_id'], $arrExcluded))
			{
				$arrIncluded[] = $row["acm_id"];
			}

			if ($row['acm_is_allowed'] == 0 && !in_array($row['acm_id'], $arrIncluded))
			{
				$arrExcluded[] = $row["acm_id"];
			}
		}
		$res = array_unique($arrIncluded);
		Yii::app()->cache->set($key, $res, 12 * 60 * 60, new CacheDependency("addons"));
		result:
		return $res;
	}

	/**
	 * this return cab addon details with calculated addon charge
	 * @param type $id
	 * @param type $baseAmount
	 * @return array
	 */
	public static function getById($id, $baseAmount = 0)
	{
		$acmData	 = DBUtil::queryRow("SELECT acm_svc_id_to,acm_margin_data FROM `addon_cab_models` WHERE `acm_id` = :id", DBUtil::MDB(), ['id' => $id]);
		$charge	 = self::calculateCharge($acmData, $baseAmount);
		$label		 = SvcClassVhcCat::model()->findByPk($acmData['acm_svc_id_to'])->scv_label;
        $labelArr = explode("(",$label);
		$label = $labelArr[0];
		$obj		 = ['id' => $id, 'cost' => $charge, 'label' => $label, 'default' => 0];
		return $obj;
	}

	/**
	 * this function is used to calculate the total base fare after addon applied
	 * @param type $acmData
	 * @param type $baseAmount
	 * @return int amount
	 */
	public static function calculateCharge($acmData, $baseAmount)
	{
		$marginData = json_decode($acmData['acm_margin_data'], true);
		if ($marginData['value'] == 0)
		{
			return;
		}
		if ($marginData['type'] == 1 && $baseAmount > 0)
		{
			$amount	 = round($baseAmount * ($marginData['value'] / 100));
			$amount	 = ($marginData['max'] > 0 && $amount > $marginData['max']) ? $marginData['max'] : $amount;
	     	$amount	 = ($marginData['min'] > 0 && $amount < $marginData['min']) ? $marginData['min'] : $amount;
		}
		else
		{
			$amount = $marginData['value'];
		}
		
		return $amount;
	}

}
