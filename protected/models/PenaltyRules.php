<?php

/**
 * This is the model class for table "penalty_rules".
 *
 * The followings are the available columns in table 'penalty_rules':
 * @property integer $plt_id
 * @property integer $plt_code
 * @property string $plt_desc
 * @property integer $plt_entity_type
 * @property integer $plt_event_id
 * @property integer $plt_min_value
 * @property integer $plt_max_value
 * @property double $plt_value
 * @property integer $plt_value_type
 * @property string $plt_rules
 * @property integer $plt_active
 * @property string $plt_create_date
 * @property string $plt_modify_date
 */
class PenaltyRules extends CActiveRecord
{

	const PTYPE_NOT_ALLOCATED_CAB_DRIVER			 = 201; //active
	const PTYPE_OTP_NOT_VERIFIED					 = 202;
	const PTYPE_RIDE_NOT_COMPLETED_BY_DRIVER		 = 203; //active
	const PTYPE_RIDE_START_OVERDUE				 = 204;
	const PTYPE_LATE_OTP_VERIFICATION				 = 205;
	const PTYPE_CAB_VERIFICATION_FAILED			 = 206;
	const PTYPE_ARRIVED_LOCATION_DIFFERENT		 = 207;
	const PTYPE_LATE_COMPLETE_BOOKING				 = 208;
	const PTYPE_CAB_NO_SHOW						 = 209;  //active
	const PTYPE_DRIVER_ARRIVED_LATE				 = 210;
	const PTYPE_DRIVER_ARRIVED_FAR_FROM_LOCATION	 = 211;
	const PTYPE_VENDOR_UNASSIGNED					 = 212; //active
	const PTYPE_UNREGISTERED_VEHICLE				 = 213;
	const PTYPE_UNREGISTERED_DRIVER				 = 214;
	const PTYPE_RIDE_NOT_STARTED_BY_DRIVER		 = 215; //active
	const PTYPE_DRIVER_NOT_LOGGED_IN				 = 216;
	const PTYPE_DRIVER_APP_DISABLE				 = 217;  //active
	const PTYPE_CUSTOMER_REVIEW					 = 218;  //active
	const PTYPE_NOT_USING_DRIVER_APP				 = 219;  //active

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'penalty_rules';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('plt_code, plt_entity_type, plt_event_id, plt_min_value, plt_max_value, plt_value, plt_value_type, plt_create_date, plt_modify_date', 'required'),
			array('plt_code, plt_entity_type, plt_event_id, plt_min_value, plt_max_value, plt_value_type, plt_active', 'numerical', 'integerOnly'=>true),
			array('plt_value', 'numerical'),
			array('plt_desc, plt_rules', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('plt_id, plt_code, plt_desc, plt_entity_type, plt_event_id, plt_min_value, plt_max_value, plt_value, plt_value_type, plt_rules, plt_active, plt_create_date, plt_modify_date', 'safe', 'on'=>'search'),
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
			'plt_id' => 'Plt',
			'plt_code' => 'Plt Code',
			'plt_desc' => 'Plt Desc',
			'plt_entity_type' => 'Plt Entity Type',
			'plt_event_id' => 'Plt Event',
			'plt_min_value' => 'Plt Min Value',
			'plt_max_value' => 'Plt Max Value',
			'plt_value' => 'Plt Value',
			'plt_value_type' => 'percent=1,fixed=2',
			'plt_rules' => 'Plt Rules',
			'plt_active' => 'Plt Active',
			'plt_create_date' => 'Plt Create Date',
			'plt_modify_date' => 'Plt Modify Date',
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

		$criteria->compare('plt_id',$this->plt_id);
		$criteria->compare('plt_code',$this->plt_code);
		$criteria->compare('plt_desc',$this->plt_desc,true);
		$criteria->compare('plt_entity_type',$this->plt_entity_type);
		$criteria->compare('plt_event_id',$this->plt_event_id);
		$criteria->compare('plt_min_value',$this->plt_min_value);
		$criteria->compare('plt_max_value',$this->plt_max_value);
		$criteria->compare('plt_value',$this->plt_value);
		$criteria->compare('plt_value_type',$this->plt_value_type);
		$criteria->compare('plt_rules',$this->plt_rules,true);
		$criteria->compare('plt_active',$this->plt_active);
		$criteria->compare('plt_create_date',$this->plt_create_date,true);
		$criteria->compare('plt_modify_date',$this->plt_modify_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PenaltyRules the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getValueByPenaltyType($ptype)
	{
		$param = ['ptype' => $ptype];
		$sql = "SELECT * FROM `penalty_rules` WHERE plt_event_id =:ptype AND plt_active = 1";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return $result;
	}

	/**
	 * 
	 * @param type $ptype
	 * @return array
	 */
	public static function getRuleByPenaltyType($ptype)
	{
		$param = ['ptype' => $ptype];
		
		$sql = "SELECT plt_rules FROM `penalty_rules` WHERE plt_event_id =:ptype AND plt_active = 1";
		
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return CJSON::decode($result['plt_rules']);
	}
	public static function calculatePenaltyCharge($penaltyType,$arrRules,$vendorAmount = 0,$time = null,$distance = null,$penaltyRow = null,$total_booking_amount = null)
	{
		if ($arrRules != null)
		{
			$minimumRangeCharge	 = ($arrRules['range']['minimumCharge']['type'] == 1) ? round($vendorAmount * $arrRules['range']['minimumCharge']['value']) : $arrRules['range']['minimumCharge']['value'];
			$maximumRangeCharge	 = ($arrRules['range']['maximumCharge']['type'] == 1) ? round($vendorAmount * $arrRules['range']['maximumCharge']['value']) : $arrRules['range']['maximumCharge']['value'];
			$diffrentRangeCharge = ($arrRules['range']['diffrentCharge']['type'] == 1) ? round($vendorAmount * $arrRules['range']['diffrentCharge']['value']) : $arrRules['range']['diffrentCharge']['value'];
			$minimumTimeCharge	 = ($arrRules['time']['minimumCharge']['type'] == 1) ? round($vendorAmount * $arrRules['time']['minimumCharge']['value']) : $arrRules['time']['minimumCharge']['value'];
			$maximumTimeCharge	 = ($arrRules['time']['maximumCharge']['type'] == 1) ? round($vendorAmount * $arrRules['time']['maximumCharge']['value']) : $arrRules['time']['maximumCharge']['value'];
			$diffrentTimeCharge	 = ($arrRules['time']['diffrentCharge']['type'] == 1) ? round($vendorAmount * $arrRules['time']['diffrentCharge']['value']) : $arrRules['time']['diffrentCharge']['value'];
//			if($penaltyType == PenaltyRules::PTYPE_VENDOR_UNASSIGNED)
//			{
//			$minimumTimeCharge	 = ($arrRules['time']['minimumCharge']['type'] == 1) ? round($total_booking_amount * $arrRules['time']['minimumCharge']['pvalue']) : $arrRules['time']['minimumCharge']['value'];
//			$maximumTimeCharge	 = ($arrRules['time']['maximumCharge']['type'] == 1) ? round($total_booking_amount * $arrRules['time']['maximumCharge']['pvalue']) : $arrRules['time']['maximumCharge']['value'];
//			$diffrentTimeCharge1 = ($arrRules['time']['diffrentCharge_1']['type'] == 1) ? round($total_booking_amount * $arrRules['time']['diffrentCharge_1']['pvalue']) : $arrRules['time']['diffrentCharge_1']['value'];
//			$diffrentTimeCharge2 = ($arrRules['time']['diffrentCharge_2']['type'] == 1) ? round($total_booking_amount * $arrRules['time']['diffrentCharge_2']['pvalue']) : $arrRules['time']['diffrentCharge_2']['value'];
//			}
            if($penaltyType == PenaltyRules::PTYPE_VENDOR_UNASSIGNED)
			{
			$minimumTimeCharge	 = min(round($total_booking_amount * $arrRules['time']['minimumCharge']['pvalue']),$arrRules['time']['minimumCharge']['value']);
			$maximumTimeCharge	 = min(round($total_booking_amount * $arrRules['time']['maximumCharge']['pvalue']),$arrRules['time']['maximumCharge']['value']);
			$diffrentTimeCharge1     = min(round($total_booking_amount * $arrRules['time']['diffrentCharge_1']['pvalue']),$arrRules['time']['diffrentCharge_1']['value']);
			$diffrentTimeCharge2     = min(round($total_booking_amount * $arrRules['time']['diffrentCharge_2']['pvalue']),$arrRules['time']['diffrentCharge_2']['value']);
			}
			switch ($penaltyType)
		{

			case PenaltyRules::PTYPE_LATE_OTP_VERIFICATION:
				
				if ($time > $arrRules['time']['diffrentTime'] && $time <= $arrRules['time']['maximumTime'])
				{
					$penalty = $minimumTimeCharge;
				}
				if ($time > $arrRules['time']['maximumTime'])
				{
					$penalty = $diffrentTimeCharge;
				}
				break;
			case PenaltyRules::PTYPE_DRIVER_ARRIVED_LATE:

//				if ($time > $arrRules['time']['minimumTime'] && $time <= $arrRules['time']['diffrentTime'])
//				{
//					$penalty = $minimumTimeCharge;
//				}
//				else if ($time > $arrRules['time']['diffrentTime'] && $time <= $arrRules['time']['maximumTime'])
//				{
//					$penalty = $diffrentTimeCharge;
//				}
//				else if ($time > $arrRules['time']['maximumTime'])
//				{
//					$penalty = $maximumTimeCharge;
//				}
                            
                            
				if($time > $arrRules['time']['minimumTime']) 
				{
					$extraTimeCharge = ($time - $arrRules['time']['minimumTime']) * $diffrentTimeCharge;
					$penalty   = $minimumTimeCharge + $extraTimeCharge;
					if($penalty > $arrRules['time']['maxCharge'])
					{
						$penalty = $arrRules['time']['maxCharge'];
					}
				} 
				else 
				{
					$penalty   = 0;
				}
                break;
			case PenaltyRules::PTYPE_DRIVER_ARRIVED_FAR_FROM_LOCATION:

				if($distance > $arrRules['range']['minimumDistance'] && $distance<=$arrRules['range']['maximumDistance'])
				{
					$penalty = $minimumRangeCharge;
				}
				if($distance > $arrRules['range']['maximumDistance'])
				{
					$penalty = $maximumRangeCharge;
				}
				break;
			case PenaltyRules::PTYPE_VENDOR_UNASSIGNED:

				if ($penaltyRow != null && $total_booking_amount != null)
				{
				//$workingHoursForAssignment	 = $penaltyRow['AssignedWorkingHours'];
				$hoursForAssignment	 = $penaltyRow['AssignedHours'];
				//$workingHoursForPickup		 = $penaltyRow['PickupWorkingHours'];
				$hoursForPickup		 = $penaltyRow['PickupHours'];
				$GivenHours			 = $penaltyRow['GivenHours'];

				// min AssignedWorkingHours =0, max AssignedWorkingHours =4,
				// min PickupWorkingHours =2,max PickupWorkingHours =12,diffrent_1 PickupWorkingHours =4,diffrent_2 PickupWorkingHours=8

				$percentageValue = (100 - ($hoursForPickup * 100 / $GivenHours));

				if ($percentageValue <= 25)
				{
					$penalty = 0;
				}
				else if ($percentageValue > 25 && $percentageValue <= 40)
				{
					//$amount = 500; percent= 0.25;
					$penalty = $minimumTimeCharge;
				}
				else if ($percentageValue > 40 && $percentageValue <= 60)
				{
					//$amount = 1000;percent= 0.5;
					$penalty = $diffrentTimeCharge1;
				}
				else if ($percentageValue > 60 && $percentageValue <= 75)
				{
					//$amount = 1500;percent= 0.75;
					$penalty = $diffrentTimeCharge2;
				}
				else if ($percentageValue > 75)
				{
					//$amount = 2000;
					$penalty = $maximumTimeCharge;
				}
				}
				break;
			case PenaltyRules::PTYPE_RIDE_NOT_COMPLETED_BY_DRIVER:
			   if(($vendorAmount * $arrRules['range']['maximumCharge']['value']) > $arrRules['range']['minimumCharge']['value'])
			   {
					$penalty = $vendorAmount * $arrRules['range']['maximumCharge']['value'];
			   }
			   else
			   {
					$penalty = $arrRules['range']['minimumCharge']['value'];
			   }
				break;
			case PenaltyRules::PTYPE_RIDE_NOT_STARTED_BY_DRIVER:
			 if(($vendorAmount * $arrRules['range']['minimumCharge']['value']) < $arrRules['range']['maximumCharge']['value'])
               {
                    $penalty = $vendorAmount * $arrRules['range']['minimumCharge']['value'];
               }
               else
               {
                    $penalty = $arrRules['range']['maximumCharge']['value'];
               }
				break;
			case PenaltyRules::PTYPE_NOT_USING_DRIVER_APP:
			 if(($vendorAmount * $arrRules['range']['minimumCharge']['value']) < $arrRules['range']['maximumCharge']['value'])
               {
                    $penalty = $vendorAmount * $arrRules['range']['minimumCharge']['value'];
               }
               else
               {
                    $penalty = $arrRules['range']['maximumCharge']['value'];
               }
				break;	
		}
			return $penalty;
		}
	}

	public static function getPenaltyRules($vndId)
	{
		$sql			 = "SELECT plt_desc,plt_min_value,plt_max_value,plt_value,plt_value_type,plt_create_date FROM penalty_rules WHERE plt_active=1";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc");
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['plt_id'], 'defaultOrder' => 'plt_create_date DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getRulesJSON()
	{
		$arrPenalty	 = $this->getRulesList();
		$arrJSON	 = [];

		foreach ($arrPenalty as $key => $val)
		{
			if ($val != '')
			{
				$arrJSON[] = array("id" => $key, "text" => $val);
			}
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getRulesList()
	{
		$sql			 = "SELECT plt_event_id,plt_desc FROM penalty_rules WHERE plt_active > 0 ORDER BY plt_id ASC";
		$penaltyModels	 = DBUtil::command($sql)->queryAll($sql);
		$arrList		 = [];
		foreach ($penaltyModels as $penaltyModel)
		{
			$arrList[$penaltyModel['plt_event_id']] = $penaltyModel['plt_desc'];
		}
		return $arrList;
	}
}
