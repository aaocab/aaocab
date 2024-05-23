<?php

/**
 * This is the model class for table "cancellation_policy_rule".
 *
 * The followings are the available columns in table 'cancellation_policy_rule':
 * @property integer $cpr_id
 * @property double $cpr_charge
 * @property integer $cpr_hours
 * @property integer $cpr_is_working_hour
 * @property string $cpr_service_tier
 * @property string $cpr_mark_initiator
 * @property integer $cpr_status
 */
class CancellationPolicyRule extends CActiveRecord
{

	public $local_cpr_hours, $local_cpr_charge;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cancellation_policy_rule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cpr_mark_initiator,cpr_service_tier,cpr_charge,cpr_hours', 'required'),
			array('cpr_hours, cpr_is_working_hour,cpr_status', 'numerical', 'integerOnly' => true),
			array('cpr_charge', 'numerical'),
			array('cpr_service_tier, cpr_mark_initiator', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('local_cpr_hours, local_cpr_charge,cpr_id, cpr_charge, cpr_hours, cpr_is_working_hour, cpr_service_tier, cpr_mark_initiator, cpr_status', 'safe', 'on' => 'search'),
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
			'cpr_id'				 => 'Cpr',
			'cpr_charge'			 => 'Cancellation Charge',
			'cpr_hours'				 => 'Hours',
			'cpr_is_working_hour'	 => 'Is Working Hour',
			'cpr_service_tier'		 => 'Service Tier',
			'cpr_mark_initiator'	 => 'Initiator',
			'cpr_status'			 => 'Status',
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

		$criteria->compare('cpr_id', $this->cpr_id);
		$criteria->compare('cpr_charge', $this->cpr_charge);
		$criteria->compare('cpr_hours', $this->cpr_hours);
		$criteria->compare('cpr_is_working_hour', $this->cpr_is_working_hour);
		$criteria->compare('cpr_service_tier', $this->cpr_service_tier, true);
		$criteria->compare('cpr_mark_initiator', $this->cpr_mark_initiator, true);
		$criteria->compare('cpr_rank', $this->cpr_rank);
		$criteria->compare('cpr_status', $this->cpr_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CancellationPolicyRule the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getList($returnType = "DataProvider")
	{
		$condition = " WHERE 1 ";
		if ($this->local_cpr_charge != null)
		{
			$condition .= " AND cpr_charge=$this->local_cpr_charge";
		}

		if ($this->local_cpr_hours != null)
		{
			$condition .= " AND cpr_hours=$this->local_cpr_hours";
		}

		if ($this->cpr_is_working_hour != null)
		{
			$condition .= " AND cpr_is_working_hour=$this->cpr_is_working_hour";
		}

		if ($this->cpr_service_tier != null)
		{
			$serviceTierArr = explode(',', $this->cpr_service_tier);
			if (count($serviceTierArr) > 1)
			{
				$condition .= " and ( ";
				foreach ($serviceTierArr as $value)
				{
					$condition .= "  FIND_IN_SET(" . $value . ", cpr_service_tier) or ";
				}
				$condition	 = rtrim($condition, ' or');
				$condition	 .= " )  ";
			}
			else
			{
				$condition .= " AND FIND_IN_SET(" . $this->cpr_service_tier . ",cpr_service_tier)";
			}
		}

		if ($this->cpr_mark_initiator != null)
		{
			$initiatorTypeArr = explode(',', $this->cpr_mark_initiator);
			if (count($initiatorTypeArr) > 1)
			{
				$condition .= " and ( ";
				foreach ($initiatorTypeArr as $value)
				{
					$condition .= "  FIND_IN_SET(" . $value . ", cpr_mark_initiator) or ";
				}
				$condition	 = rtrim($condition, ' or');
				$condition	 .= " )  ";
			}
			else
			{
				$condition .= " AND FIND_IN_SET(" . $this->cpr_mark_initiator . ",cpr_mark_initiator)";
			}
		}

		$sql = "SELECT * FROM cancellation_policy_rule $condition";

		if ($returnType == "DataProvider")
		{

			$sqlCount		 = "SELECT count(cpr_id) FROM cancellation_policy_rule $condition ";
			$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['cpr_id'],
					'defaultOrder'	 => 'cpr_id DESC'
				],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		elseif ($returnType == "List")
		{
			$sql .= "  and pcr_status=1 order by cpr_hours desc";
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
	}

	public function add()
	{
		$returnSet	 = new ReturnSet();
		$returnSet->setStatus(false);
		$transaction = DBUtil::beginTransaction();
		try
		{
			$res = $this->save();
			if ($res)
			{
				DBUtil::commitTransaction($transaction);
				$returnSet->setStatus(true);
				$returnSet->setData(["id" => $this->cpr_id]);
			}
		}
		catch (Exception $e)
		{
			if ($returnSet->getErrorCode() == 0)
			{
				$returnSet->setErrorCode($e->getCode());
				$returnSet->addError($e->getMessage());
			}
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public static function getCancelationCharge($pickupdate, $service_tier, $initator)
	{
		$currentdate	 = DBUtil::getCurrentTime();
		$sql			 = "select cpr_hours,cpr_charge,cpr_is_working_hour from cancellation_policy_rule where find_in_set($service_tier,cpr_service_tier) and find_in_set($initator,cpr_mark_initiator) and cpr_status=1 order by  cpr_hours desc";
		$result			 = DBUtil::queryAll($sql, DBUtil::SDB());
		$cancelCharge	 = 0;
		if (count($result) > 1)
		{
			for ($i = 0; $i < (count($result)); $i++)
			{

				if ($i != count($result) - 1)
				{
					$workingHourpre	 = $result[$i]['cpr_is_working_hour'] == 0 ? (DBUtil::getTimeDiff($currentdate, $pickupdate) / 60) : DBUtil::CalcWorkingHour($currentdate, $pickupdate);
					$workingHourpost = $result[$i + 1]['cpr_is_working_hour'] == 0 ? (DBUtil::getTimeDiff($currentdate, $pickupdate) / 60) : DBUtil::CalcWorkingHour($currentdate, $pickupdate);

					if ($workingHourpost >= $result[$i + 1]['cpr_hours'] && $workingHourpre < $result[$i]['cpr_hours'])
					{
						$cancelCharge = $result[$i]['cpr_charge'];
					}
				}

				if ($i == count($result) - 1)
				{
					$workingHourpre = $result[$i]['cpr_is_working_hour'] == 0 ? (DBUtil::getTimeDiff($currentdate, $pickupdate) / 60) : DBUtil::CalcWorkingHour($currentdate, $pickupdate);
					if ($workingHourpre < $result[$i]['cpr_hours'])
					{
						$cancelCharge = $result[$i]['cpr_charge'];
					}
				}
			}
		}
		else
		{
			$workingHourpre = $result[0]['cpr_is_working_hour'] == 0 ? (DBUtil::getTimeDiff($currentdate, $pickupdate) / 60) : DBUtil::CalcWorkingHour($currentdate, $pickupdate);
			if ($workingHourpre < $result[0]['cpr_hours'])
			{
				$cancelCharge = $result[0]['cpr_charge'];
			}
		}

		return $cancelCharge;
	}

	public static function freeCancelEndTime($pickupDate, $hour)
	{
		$sql			 = "SELECT SubWorkingMinutes(:minutes, '$pickupDate') FROM dual";
		$res			 = DBUtil::SDB()->createCommand($sql)->queryScalar(['minutes' => $hour * 60]);
		$freeCancelEnd	 = date('d M Y h:i A', strtotime($res));
		return $freeCancelEnd;
	}

	public static function getCancelationTimeRange($bkg_id, $initator)
	{
		$models			 = Booking::model()->findByPk($bkg_id);
		$createDate		 = $models->bkg_create_date;
		$pickupDate		 = $models->bkg_pickup_date;
		$service_tier	 = $models->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;

		$currentdate	 = DBUtil::getCurrentTime();
		$cancelChargeArr = array();
		$sql			 = "select cpr_hours,cpr_charge,cpr_is_working_hour from cancellation_policy_rule where find_in_set($service_tier,cpr_service_tier) and find_in_set($initator,cpr_mark_initiator) and cpr_status=1 order by  cpr_hours desc";
		$result			 = DBUtil::queryAll($sql, DBUtil::SDB());
		if (count($result) > 1)
		{
			for ($i = 0; $i < (count($result)); $i++)
			{

				if ($i != count($result) - 1)
				{
					$time		 = $result[$i]['cpr_is_working_hour'] == 1 ? CancellationPolicyRule::freeCancelEndTime($pickupDate, $result[$i]['cpr_hours']) : date('d M Y h:i A', strtotime($pickupDate) - $result[$i]['cpr_hours'] * 3600);
					$timePost	 = $result[$i + 1]['cpr_is_working_hour'] == 1 ? CancellationPolicyRule::freeCancelEndTime($pickupDate, $result[$i + 1]['cpr_hours']) : date('d M Y h:i A', strtotime($pickupDate) - $result[$i + 1]['cpr_hours'] * 3600);
					if ($i == 0)
					{
						$cancelChargeArr[$i]	 = array("Fromdate" => date('d M Y h:i A', strtotime($createDate)), "ToDate" => $time, "CancelCharge" => "0");
						$cancelChargeArr[$i + 1] = array("Fromdate" => $time, "ToDate" => $timePost, "CancelCharge" => ($result[$i]['cpr_charge'] * 100));
					}
					else
					{
						$cancelChargeArr[$i + 1] = array("Fromdate" => $time, "ToDate" => $timePost, "CancelCharge" => ($result[$i]['cpr_charge'] * 100));
					}
				}

				if ($i == count($result) - 1)
				{
					$time					 = $result[$i]['cpr_is_working_hour'] == 1 ? CancellationPolicyRule::freeCancelEndTime($pickupDate, $result[$i]['cpr_hours']) : date('d M Y h:i A', strtotime($pickupDate) - $result[$i]['cpr_hours'] * 3600);
					$cancelChargeArr[$i + 1] = array("Fromdate" => $time, "ToDate" => date('d M Y h:i A', strtotime($pickupDate)), "CancelCharge" => ($result[$i]['cpr_charge'] * 100));
				}
			}
		}
		else
		{
			$time				 = $result[0]['cpr_is_working_hour'] == 1 ? CancellationPolicyRule::freeCancelEndTime($pickupDate, $result[0]['cpr_hours']) : date('d M Y h:i A', strtotime($pickupDate) - $result[0]['cpr_hours'] * 3600);
			$cancelChargeArr[0]	 = array("Fromdate" => date('d M Y h:i A', strtotime($createDate)), "ToDate" => $time, "CancelCharge" => "0");
			$cancelChargeArr[1]	 = array("Fromdate" => $time, "ToDate" => date('d M Y h:i A', strtotime($pickupDate)), "CancelCharge" => ($result[0]['cpr_charge'] * 100));
		}

		$finalCancelChargeArr = array();
		foreach ($cancelChargeArr as $key => $value)
		{
			if (strtotime($value['ToDate']) > strtotime($currentdate))
			{
				$finalCancelChargeArr[] = $value;
			}
		}
		return $finalCancelChargeArr;
	}

	public function getinitiatorType($initiatorType = 0)
	{
		$arrInitiatorType = [
			1	 => 'Consumer',
			2	 => 'Admin',
			3	 => 'Vendor',
			4	 => 'Driver',
			5	 => 'System',
			6	 => 'Partner',
		];
		if ($initiatorType != 0)
		{
			return $arrInitiatorType[$initiatorType];
		}
		else
		{
			return $arrInitiatorType;
		}
	}

	public function getJSON($all = '')
	{
		$arrJSON = [];
		foreach ($all as $key => $val)
		{
			if ($val != '')
			{
				$arrJSON[] = array("id" => $key, "text" => $val);
			}
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}
    
    /**
	 * This function is used to get cancel rule Id
	 * @param integer $vehicleTypeId
	 * @param integer $bkgAgentId
	 * @return integer $ruleId
	 */
    public static function getCancellationRuleId($vehicleTypeId, $bkgAgentId)
    {
        $svcModel = SvcClassVhcCat::model()->findByPk($vehicleTypeId);
        if ($bkgAgentId == '')
        {
            $bkgAgentId = Yii::app()->params['gozoChannelPartnerId'];
        }
        $cancelRuleId                 = SkuCancelRule::getData($svcModel->scv_code, $bkgAgentId);
        $ruleId =  $cancelRuleId->scr_rule_id;
        return $ruleId;
    }
    
    /**
     * This function is used to get charges
     * @param type $bkgModel
     * @return integer $cancelCharge
     */
    public static function getCharges($bkmodel)
    {
        if ($bkmodel->bkgPref->bkg_cancel_rule_id != null)
        {
            if ($bkmodel->bkgInvoice->bkg_cancel_charge == null)
            {
                $cancelTimes_new = CancellationPolicy::initiateRequest($bkmodel);
                $cancelCharges   = round($cancelTimes_new->charges);
            }
            else
            {
                $cancelCharges = $bkmodel->bkgInvoice->bkg_cancel_charge;
            }
        }
        else
        {
            $cancelCharges = '';
        }
        return $cancelCharges;
    }

}
