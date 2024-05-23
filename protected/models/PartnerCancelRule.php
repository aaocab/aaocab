<?php

/**
 * This is the model class for table "partner_cancel_rule".
 *
 * The followings are the available columns in table 'partner_cancel_rule':
 * @property string $prc_id
 * @property integer $prc_zone_category
 * @property integer $prc_scc_id
 * @property integer $prc_partner_id
 * @property integer $prc_cancel_rule_id
 * @property integer $prc_bkg_type
 * @property integer $prc_is_gozonow
 * @property integer $prc_active
 */
class PartnerCancelRule extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_cancel_rule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prc_zone_category, prc_scc_id, prc_cancel_rule_id', 'required'),
			array('prc_zone_category, prc_scc_id, prc_partner_id, prc_cancel_rule_id, prc_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prc_id, prc_zone_category, prc_scc_id, prc_partner_id, prc_cancel_rule_id, prc_active', 'safe', 'on' => 'search'),
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
			'prc_id'			 => 'Prc',
			'prc_zone_category'	 => 'Prc Zone Type',
			'prc_scc_id'		 => 'Prc Scc',
			'prc_partner_id'	 => 'Prc Partner',
			'prc_cancel_rule_id' => 'Prc Cancel Rule',
			'prc_active'		 => 'Prc Active',
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

		$criteria->compare('prc_id', $this->prc_id, true);
		$criteria->compare('prc_zone_category', $this->prc_zone_category);
		$criteria->compare('prc_scc_id', $this->prc_scc_id);
		$criteria->compare('prc_partner_id', $this->prc_partner_id);
		$criteria->compare('prc_cancel_rule_id', $this->prc_cancel_rule_id);
		$criteria->compare('prc_active', $this->prc_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerCancelRule the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getCancelRuleId($fcity, $tcity, $svcId, $tripType, $partnerId = null, $isGozoNow = null, $fromTopZoneCat = false)
	{
		if($fromTopZoneCat)
		{
			$zoneCat	 = TopZoneRoutes::getCategory($fcity, $tcity);
			$zoneType = ($zoneCat === 0 ? 1 : ($zoneCat == null ? 3 : $zoneCat));
		}
		else
		{
			$zoneType	 = DynamicZoneSurge::getZoneTypeByCityCab($fcity, $tcity, $svcId, $tripType) | 1;
		}

		$sccId		 = SvcClassVhcCat::model()->getClassById($svcId);
		$where		 = '';
		$orderBy	 = '';
		$params		 = ['zoneType'	 => $zoneType,
			'sccId'		 => $sccId,
			'partnerId'	 => $partnerId,
			'tripType'	 => $tripType
		];
		$where				 = ' AND (prc_is_gozonow IS NULL) ';
		if ($isGozoNow)
		{
			$where				 = ' AND (prc_is_gozonow = :isGozoNow OR prc_is_gozonow IS NULL) ';
			$params['isGozoNow'] = $isGozoNow;
			$orderBy			 = ',prc_is_gozonow DESC';
		}

		$sql			 = "SELECT prc_cancel_rule_id FROM `partner_cancel_rule` 
			WHERE (prc_zone_category = :zoneType OR prc_zone_category IS NULL)
		AND (prc_scc_id = :sccId OR prc_scc_id IS NULL)
		AND (prc_partner_id = :partnerId OR prc_partner_id IS NULL) 
		AND (prc_bkg_type = :tripType OR prc_bkg_type IS NULL) 
		$where
		AND prc_active=1  
		ORDER BY prc_partner_id DESC, prc_bkg_type DESC $orderBy";
		$cancelRuleId	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $cancelRuleId;
	}

	public static function getCancelRule()
	{
		$sql			 = "SELECT prc.*,scc.scc_label,cnp.cnp_label,cnp.cnp_desc,trim(if((agt.agt_company=''||agt.agt_company IS NULL),concat(agt.agt_fname, ' ',agt.agt_lname),concat(agt.agt_company,' (',agt.agt_fname, ' ',agt.agt_lname,')'))) as agt_name
            FROM
               `partner_cancel_rule` as prc
            INNER JOIN service_class scc ON scc.scc_id = prc.prc_scc_id
			INNER JOIN cancellation_policy_details cnp ON cnp.cnp_id = prc.prc_cancel_rule_id
            LEFT JOIN agents agt ON agt.agt_id = prc.prc_partner_id
            WHERE prc.prc_active = 1";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc");
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['prc_id'], 'defaultOrder' => 'prc_partner_id DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

}
