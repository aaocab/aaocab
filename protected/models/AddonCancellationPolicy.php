<?php

/**
 * This is the model class for table "addon_cancellation_policy".
 *
 * The followings are the available columns in table 'addon_cancellation_policy':
 * @property integer $acp_id
 * @property integer $acp_area_type
 * @property integer $acp_area_id
 * @property integer $acp_cr_from
 * @property integer $acp_cr_to
 * @property string $acp_margin_data
 * @property integer $acp_is_allowed
 * @property integer $acp_active
 * @property string $acp_created_at
 * @property string $acp_modified_at
 */
class AddonCancellationPolicy extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'addon_cancellation_policy';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('acp_cr_from, acp_cr_to, acp_margin_data, acp_created_at', 'required'),
			array('acp_area_type, acp_area_id, acp_cr_from, acp_cr_to, acp_is_allowed, acp_active', 'numerical', 'integerOnly' => true),
			array('acp_margin_data', 'length', 'max' => 300),
			array('acp_modified_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('acp_id, acp_area_type, acp_area_id, acp_cr_from, acp_cr_to, acp_margin_data, acp_is_allowed, acp_active, acp_created_at, acp_modified_at', 'safe', 'on' => 'search'),
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
			'acp_id'			 => 'Acp',
			'acp_area_type'		 => 'Acp Area Type',
			'acp_area_id'		 => 'Acp Area',
			'acp_cr_from'		 => 'Acp Cr From',
			'acp_cr_to'			 => 'Acp Cr To',
			'acp_margin_data'	 => 'Acp Margin Data',
			'acp_is_allowed'	 => 'Acp Is Allowed',
			'acp_active'		 => 'Acp Active',
			'acp_created_at'	 => 'Acp Created At',
			'acp_modified_at'	 => 'Acp Modified At',
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

		$criteria->compare('acp_id', $this->acp_id);
		$criteria->compare('acp_area_type', $this->acp_area_type);
		$criteria->compare('acp_area_id', $this->acp_area_id);
		$criteria->compare('acp_cr_from', $this->acp_cr_from);
		$criteria->compare('acp_cr_to', $this->acp_cr_to);
		$criteria->compare('acp_margin_data', $this->acp_margin_data, true);
		$criteria->compare('acp_is_allowed', $this->acp_is_allowed);
		$criteria->compare('acp_active', $this->acp_active);
		$criteria->compare('acp_created_at', $this->acp_created_at, true);
		$criteria->compare('acp_modified_at', $this->acp_modified_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AddonCancellationPolicy the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * use to return ids of cancellation policy addons acc. to city trip type and default cancel rule id
	 * @param type $fcity
	 * @param type $tcity
	 * @param type $scvId
	 * @param type $tripType
	 * @param type $defCanRuleId
	 * @return array
	 */
	public static function getByCtyVehicleType($fcity, $tcity, $scvId, $tripType, $defCanRuleId)
	{
		$key	 = md5("AddonCancellationPolicy_getByCtyVehicleType" . "_" . $fcity . "_" . $tcity . "_" . $scvId . "_" . $tripType . "_" . $defCanRuleId);
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

		$sql = "SELECT addon_cancellation_policy.*,
								CASE
											WHEN acp_area_type=1 AND acp_area_id IN ({$fcityInfo['zones']}) THEN 5
											WHEN acp_area_type=2 AND acp_area_id IN ({$fcityInfo['stt_id']}) THEN 15
											WHEN acp_area_type=3 AND acp_area_id IN ({$fcity}) THEN 20
											WHEN acp_area_type=4 AND acp_area_id IN ({$fcityInfo['region']}) THEN 1
											WHEN acp_area_type=5 AND acp_area_id IN ({$zoneType}) THEN 10
											ELSE 0
											END AS rank FROM addon_cancellation_policy
						WHERE (
								(acp_area_type = 1 AND acp_area_id IN ({$bindZonesString}))
								OR (acp_area_type = 2 AND acp_area_id IN ($bindStateString))
								OR (acp_area_type = 3 AND acp_area_id IN ($bindCityString))
								OR (acp_area_type = 4 AND acp_area_id IN ($bindRegionString))
								OR (acp_area_type = 5 AND acp_area_id IN ({$zoneType}))
								OR acp_area_type IS NULL)
							AND acp_cr_from = :defCanRuleId AND acp_active=1 AND acp_is_allowed = 1
						ORDER BY rank DESC";

		$params		 = $paramsZone + $paramsCity + $paramsState + $paramsRegion + ['defCanRuleId' => $defCanRuleId];
		$rows		 = DBUtil::query($sql, DBUtil::SDB(), $params, 60 * 60 * 12, "addons");
		$arrIncluded = [];
		$arrExcluded = [];
		foreach ($rows as $row)
		{
			if ($row['acp_is_allowed'] == 1 && !in_array($row['acp_id'], $arrExcluded))
			{
				$arrIncluded[] = $row["acp_id"];
			}

			if ($row['acp_is_allowed'] == 0 && !in_array($row['acp_id'], $arrIncluded))
			{
				$arrExcluded[] = $row["acp_id"];
			}
		}
		$res = array_unique($arrIncluded);
		Yii::app()->cache->set($key, $res, 12 * 60 * 60, new CacheDependency("addons"));
		result:
		return $res;
	}

	/**
	 * this return cp addon details with calculated addon charge
	 * @param type $id
	 * @param type $baseAmount
	 * @return array
	 */
	public static function getById($id, $baseAmount = 0)
	{
		$acpData	 = DBUtil::queryRow("SELECT acp_cr_to,acp_margin_data FROM `addon_cancellation_policy` WHERE `acp_id` = :id", DBUtil::SDB(), ['id' => $id]);
		$addonCost	 = self::calculateCharge($acpData, $baseAmount);
		$CPdetails	 = CancellationPolicyDetails::model()->findByPk($acpData['acp_cr_to']);
		$ruleArr     = json_decode($CPdetails->cnp_rule_data,true); 
		$obj		 = ['id' => $id, 'cost' => $addonCost, 'label' => $CPdetails->cnp_label, 'desc' => $CPdetails->cnp_desc, 'default' => 0,'minutesBeforePickup'=>$ruleArr['timeRules']['minutesBeforePickup']];
		return $obj;
	}

	/**
	 * this function is used to calculate the total base fare after addon applied
	 * @param type $acpData
	 * @param type $baseAmount
	 * @return int amount
	 */
	public static function calculateCharge($acpData, $baseAmount)
	{
		$marginData = json_decode($acpData['acp_margin_data'], true);
		if ($marginData['value'] == 0)
		{
			return 0;
		}
		if ($marginData['type'] == 1 && $baseAmount > 0)
		{
			$amount	 = round($baseAmount * ($marginData['value'] / 100));
			$amount	 = ($marginData['max'] > 0 && $amount > $marginData['max'] && $amount>0) ? $marginData['max'] : $amount;
			$amount	 = ($marginData['min'] > 0 && $amount < $marginData['min'] && $amount>0) ? $marginData['min'] : $amount;

			$amount	 = ($marginData['max'] < 0 && $amount < $marginData['max'] && $amount<0) ? $marginData['max'] : $amount;
			$amount	 = ($marginData['min'] < 0 && $amount > $marginData['min'] && $amount<0) ? $marginData['min'] : $amount;
		}
		else
		{
			$amount	 = $marginData['value'];
		}
		return $amount;
	}

	/**
	 * this function used to return cancel rule id applied by primary key
	 * @param type $id
	 * @return type
	 */
	public static function getCancelRuleById($id)
	{
		$cpRule = DBUtil::queryScalar("SELECT acp_cr_to FROM `addon_cancellation_policy` WHERE `acp_id` = :id", DBUtil::SDB(), ['id' => $id]);
		return $cpRule;
	}

	public static function getMinPayble($addonId,$totalFare)
	{
		$amount = 0;
		$advanceRuleId = self::model()->findByPk($addonId)->acp_advance_rule_id;
		if ($advanceRuleId > 0)
		{
			$rule = Filter::getAdvanceRuleArr($advanceRuleId);
			if ($rule != '')
			{
				if ($rule['type'] == 1 && $totalFare > 0)
				{
					$amount	 = round($totalFare * ($rule['value'] / 100));
					$amount	 = ($rule['max'] > 0 && $amount > $rule['max']) ? $rule['max'] : $amount;
					$amount	 = ($rule['min'] > 0 && $amount < $rule['min']) ? $rule['min'] : $amount;
				}
				else
				{
					$amount = $rule['value'];
				}
			}
		}
		return $amount;
	}

}
