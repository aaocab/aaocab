<?php

/**
 * This is the model class for table "addon_service_class_rule".
 *
 * The followings are the available columns in table 'addon_service_class_rule':
 * @property integer $ascr_id
 * @property integer $ascr_cty_category
 * @property integer $ascr_scc_id
 * @property integer $ascr_adn_id
 * @property integer $ascr_active
 * @property string $ascr_created
 * @property string $ascr_modified
 */
class AddonServiceClassRule extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'addon_service_class_rule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ascr_adn_id, ascr_created', 'required'),
			array('ascr_cty_category, ascr_scc_id,  ascr_adn_id, ascr_active', 'numerical', 'integerOnly' => true),
			array('ascr_modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ascr_id, ascr_cty_category, ascr_scc_id,  ascr_adn_id, ascr_active, ascr_created, ascr_modified', 'safe', 'on' => 'search'),
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
			'ascr_id'			 => 'Ascr',
			'ascr_cty_category'	 => 'Ascr Cty Category',
			'ascr_scc_id'		 => 'Ascr Class',
			'ascr_adn_id'		 => 'Ascr Addon',
			'ascr_active'		 => 'Ascr Active',
			'ascr_created'		 => 'Ascr Created',
			'ascr_modified'		 => 'Ascr Modified',
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

		$criteria->compare('ascr_id', $this->ascr_id);
		$criteria->compare('ascr_cty_category', $this->ascr_cty_category);
		$criteria->compare('ascr_scc_id', $this->ascr_scc_id);
		$criteria->compare('ascr_adn_id', $this->ascr_adn_id);
		$criteria->compare('ascr_active', $this->ascr_active);
		$criteria->compare('ascr_created', $this->ascr_created, true);
		$criteria->compare('ascr_modified', $this->ascr_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AddonServiceClassRule the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * This function is used to return comma separated addon ids by city category and service class id
	 * @param type $category
	 * @param type $sccId
	 * @return type
	 */
	public static function getIdsByCityCategoryClassId($category, $sccId)
	{
		$sql		 = "SELECT GROUP_CONCAT(adn_id) as ids FROM addon_service_class_rule INNER JOIN addons ON ascr_adn_id = adn_id AND adn_active=1 AND ascr_active = 1 WHERE ascr_cty_category=:cat AND ascr_scc_id=:class";
		$params		 = ['cat' => $category, 'class' => $sccId];
		$ids		 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $ids;
	}
    /**
	 * This function is used to return comma separated addon ids city and service class 
	 * @param type $city
	 * @param type $sccId
	 * @return type
	 */
	public static function getIdsByCityClassId($city, $sccId)
	{
		$category = CitiesStats::getCategory($city) | 0;
		return self::getIdsByCityCategoryClassId($category, $sccId);
	}

   /**
    * This function returns addon details by city category and service class
    * @param type $category
    * @param type $sccId
    * @return boolean or sql rows
    */
	public static function getByCityCategoryClassId($category, $sccId)
	{
		$sql		 = "SELECT adn_id,adn_desc,adn_cancel_rule_id, ascr_cty_category cat FROM addon_service_class_rule INNER JOIN addons ON ascr_adn_id = adn_id AND adn_active=1 AND ascr_active = 1 WHERE ascr_cty_category=:cat AND ascr_scc_id=:class";
		$params		 = ['cat' => $category, 'class' => $sccId];
		$rows		 = DBUtil::query($sql, DBUtil::SDB(), $params);
		if (count($rows) > 0)
		{
			return $rows;
		}
		return false;
	}

    /**
	 * This function is used to return addons by city and service class
	 * @param type $city from city Id
	 * @param type $sccId service class Id
	 * @return array addons
	 */
	public static function getByCityClassId($city, $sccId)
	{
		$category = CitiesStats::getCategory($city) | 0;
		return self::getByCityCategoryClassId($category, $sccId);
	}

	/**
	 * This function returns addon charges,addon labels,addon ids applicable acc. to city and service class
	 * @param type $cityId from city Id 
	 * @param type $sccId  service class Id
	 * @param type $baseAmount baseAmounnt from quotation
	 * @return array
	 */
	public static function getApplicableAddons($cityId, $sccId, $baseAmount)
	{
		$arrAddOns	 = [];
		$rows		 = self::getByCityClassId($cityId, $sccId);
		foreach ($rows as $row)
		{
			$addOnCharge	 = Addons::getApplicableCharge($row['adn_id'], $baseAmount);
			array_push($arrAddOns, ['id' => $row['adn_id'], 'addOnCharge' => $addOnCharge,'label' => $row['adn_desc']]);
		}
		if (count($arrAddOns) > 0)
		{
			$arrAddOnCharge = array_column($arrAddOns, 'addOnCharge');
			array_multisort($arrAddOnCharge, SORT_ASC, $arrAddOns);
		}
		return $arrAddOns;
	}

}
