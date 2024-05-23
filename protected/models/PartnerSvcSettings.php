<?php

/**
 * This is the model class for table "partner_svc_setting".
 *
 * The followings are the available columns in table 'partner_svc_setting':
 * @property integer $pss_id
 * @property integer $pss_svc_id
 * @property integer $pss_partner_id
 * @property integer $pss_cancel_rule_id
 * @property integer $pss_active
 */
class PartnerSvcSettings extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_svc_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pss_svc_id, pss_partner_id, pss_cancel_rule_id, pss_active', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pss_id, pss_svc_id, pss_partner_id, pss_cancel_rule_id, pss_active', 'safe', 'on'=>'search'),
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
			'pss_id' => 'Pss',
			'pss_svc_id' => 'Pss Svc',
			'pss_partner_id' => 'Pss Partner',
			'pss_cancel_rule_id' => 'Pss Cancel Rule',
			'pss_active' => 'Pss Active',
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

		$criteria->compare('pss_id',$this->pss_id);
		$criteria->compare('pss_svc_id',$this->pss_svc_id);
		$criteria->compare('pss_partner_id',$this->pss_partner_id);
		$criteria->compare('pss_cancel_rule_id',$this->pss_cancel_rule_id);
		$criteria->compare('pss_active',$this->pss_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerSvcSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /** 
     * @param int $svcId
     * @param int $partnerId
     * @return int | false
     *  */
    public static function getCancelRuleId($svcId, $partnerId)
    {
            $sql = "SELECT pss_cancel_rule_id FROM  partner_svc_settings WHERE pss_svc_id=:svcId AND pss_partner_id=:partnerId AND pss_active=1";
        $params = ["svcId" => $svcId,  'partnerId' => $partnerId];
        $ruleId = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
        return $ruleId;
    }

	/** 
     * @param int $airportId
     * @param int $partnerId
	 * @param int $atTransferType
	 * @param int $vhcType
     * @return int | false
     *  */
	public static function eligibleCabType($airportId,$partnerId,$atTransferType,$vhcType)
	{
		$arrRelationDetails = SvcClassVhcCat::getCategoryServiceClass(0, $vhcType, 0);
		$arrReturn = [];
		foreach ($arrRelationDetails as $relationDetail)
		{
			$arrReturn[] = $relationDetail["scv_id"];
		}
		$vhcType = $arrReturn;

		
		$params = ["patCityId" => $airportId,  'patPartnerId' => $partnerId, 'patTransType' => $atTransferType];
		DBUtil::getINStatement($vhcType, $bindString, $params1);
		$params   = array_merge($params, $params1);
		$sql = "SELECT pat_vehicle_type FROM partner_airport_transfer WHERE pat_city_id = :patCityId AND pat_partner_id = :patPartnerId AND pat_transfer_type = :patTransType AND pat_vehicle_type  IN ({$bindString})";
        $ruleId = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
        return $ruleId;
	}
}
