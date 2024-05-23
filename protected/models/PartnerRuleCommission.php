<?php

/**
 * This is the model class for table "partner_rule_commission".
 *
 * The followings are the available columns in table 'partner_rule_commission':
 * @property string $prc_id
 * @property string $prc_agent_id
 * @property integer $prc_booking_type
 * @property integer $prc_booking_count
 * @property integer $prc_commission_type
 * @property double $prc_commission_value
 * @property string $prc_additional_param
 * @property string $prc_created_at
 * @property string $prc_update_at
 * @property integer $prc_active
 */
class PartnerRuleCommission extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_rule_commission';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prc_created_at', 'required'),
			array('prc_booking_type, prc_booking_count, prc_commission_type, prc_active', 'numerical', 'integerOnly' => true),
			array('prc_commission_value', 'numerical'),
			array('prc_agent_id', 'length', 'max' => 11),
			array('prc_additional_param, prc_update_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prc_id, prc_agent_id, prc_booking_type, prc_booking_count, prc_commission_type, prc_commission_value, prc_additional_param, prc_created_at, prc_update_at, prc_active', 'safe', 'on' => 'search'),
		);
	}
	
	public $bookingType  = ['1' => 'Outstation', '2' => 'Local'];
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
			'prc_id'				 => 'Prc',
			'prc_agent_id'			 => 'Partner Id',
			'prc_booking_type'		 => '1=> OutStation,2=>Local',
			'prc_booking_count'		 => 'Partner Booking Count',
			'prc_commission_type'	 => '1=>Percentage,2=>Fixed',
			'prc_commission_value'	 => 'Partner Commission Value',
			'prc_additional_param'	 => 'Additional parameters if required',
			'prc_created_at'		 => 'when this row was created',
			'prc_update_at'			 => 'Last modified date',
			'prc_active'			 => '1=> active,0=>inactive ',
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
		$criteria->compare('prc_agent_id', $this->prc_agent_id, true);
		$criteria->compare('prc_booking_type', $this->prc_booking_type);
		$criteria->compare('prc_booking_count', $this->prc_booking_count);
		$criteria->compare('prc_commission_type', $this->prc_commission_type);
		$criteria->compare('prc_commission_value', $this->prc_commission_value);
		$criteria->compare('prc_additional_param', $this->prc_additional_param, true);
		$criteria->compare('prc_created_at', $this->prc_created_at, true);
		$criteria->compare('prc_update_at', $this->prc_update_at, true);
		$criteria->compare('prc_active', $this->prc_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerRuleCommission the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used for getting partner rule commission based on booking type 
	 * @return query Object
	 */
	public static function getPartnerRuleCommission($bookingCnt, $agentId = 0, $type = 1)
	{
		$where	 = "";
		$params	 = array('prc_booking_count' => $bookingCnt, 'prc_booking_type' => $type);
		if ($agentId > 0)
		{
			$where					 .= " AND prc_agent_id=:prc_agent_id";
			$params['prc_agent_id']	 = $agentId;
		}
		$sql	 = "SELECT * FROM partner_rule_commission WHERE prc_booking_type=:prc_booking_type AND prc_booking_count<=:prc_booking_count $where AND prc_active=1 Order by prc_booking_count DESC LIMIT 0,1";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		if (!$result)
		{
			unset($params["prc_agent_id"]);
			$sql	 = "SELECT * FROM partner_rule_commission WHERE prc_booking_type=:prc_booking_type AND prc_booking_count<=:prc_booking_count  AND prc_active=1 Order by prc_booking_count DESC LIMIT 0,1";
			$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		}
		return $result;
	}

	public function getbyPartnerId($agtid, $bookingType = 0)
	{
		$criteria = new CDbCriteria;
		if ($bookingType != 0)
		{
			$criteria->compare('prc_booking_type ', $bookingType);
		}
		$criteria->compare('prc_agent_id', $agtid);
		$model = $this->find($criteria);
		if ($model)
		{
			return $model;
		}
		else
		{
			return false;
		}
	}

	public static function getMinAmountByType($type,$agentId)
	{
		DBUtil::getINStatement($type, $bindString, $params);
		$params['agentId']=$agentId;
		$sql = "SELECT 
                partner_rule_commission.prc_id,
				partner_rule_commission.prc_commission_type,
                partner_rule_commission.prc_commission_value  
                FROM  partner_rule_commission
                WHERE 1 AND  (partner_rule_commission.prc_agent_id IS NULL OR partner_rule_commission.prc_agent_id=:agentId) AND partner_rule_commission.prc_booking_type IN ({$bindString}) ORDER BY partner_rule_commission.prc_agent_id DESC,partner_rule_commission.prc_booking_count ASC LIMIT 0,1";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	public function getByAgtId($id = "")
	{
		$where = '';

		if ($id != '')
		{

			$where = "AND prc_agent_id = $id";
		}
		$sql = "select * FROM partner_rule_commission WHERE 1 $where AND prc_active = 1 ORDER BY prc_booking_count";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param type $agtId
	 * @throws Exception
	 */
	public static function saveData($agtId)
	{
		$returnSet	 = new ReturnSet();
		$typeValues = PartnerRuleCommission::model()->bookingType;
		foreach ($typeValues as $key => $value)
		{
			$partnerModel = PartnerRuleCommission::model()->getbyPartnerId($agtId, $key);
			try
			{
				if (!$partnerModel)
				{
					$model						 = new PartnerRuleCommission();
					$model->prc_agent_id		 = $agtId;
					$model->prc_booking_type	 = $key;
					$model->prc_booking_count	 = 0;
					$model->prc_commission_type	 = 2;
					$model->prc_commission_value = 100;
					$model->prc_created_at		 = DBUtil::getCurrentTime();
					$model->prc_update_at		 = DBUtil::getCurrentTime();
					$model->prc_active			 = 1;

					if (!$model->save())
					{
						throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
					}
					DBUtil::commitTransaction($transaction);
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				echo "\r\n" . $ex->getMessage();
				$returnSet->setStatus(false);
			}
		}
	}

}
