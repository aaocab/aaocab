<?php

/**
 * This is the model class for table "auto_cancel_rule".
 *
 * The followings are the available columns in table 'auto_cancel_rule':
 * @property integer $acr_id
 * @property integer $acr_demsupmisfire
 * @property double $acr_flag_cs
 * @property double $acr_cancel_cs
 * @property integer $acr_rule_rank
 * @property integer $acr_is_assigned
 * @property integer $acr_is_allocated
 * @property string $acr_bkg_type
 * @property integer $acr_addresses_given
 * @property string $acr_service_tier
 * @property integer $acr_time_create
 * @property integer $acr_time_to_pickup
 * @property integer $acr_flag_minpickuptime
 * @property integer $acr_cancel_minpickuptime
 * @property integer $acr_time_confirm
 * @property integer $acr_time_bidstarted
 * @property integer $acr_auto_cancel_value
 * @property integer $acr_auto_cancel_code
 * @property integer $acr_status
 */
class AutoCancelRule extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'auto_cancel_rule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('acr_rule_rank,acr_auto_cancel_value,acr_auto_cancel_code', 'required'),
			array('acr_demsupmisfire,acr_auto_cancel_code ,acr_rule_rank, acr_is_assigned, acr_is_allocated, acr_addresses_given, acr_time_create, acr_time_to_pickup,acr_flag_minpickuptime,acr_cancel_minpickuptime, acr_time_confirm, acr_time_bidstarted, acr_auto_cancel_value, acr_status', 'numerical', 'integerOnly' => true),
			array('acr_flag_cs,acr_cancel_cs', 'numerical'),
			array('acr_bkg_type, acr_service_tier', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('acr_auto_cancel_code,acr_id, acr_demsupmisfire, acr_flag_cs,acr_cancel_cs,acr_rule_rank, acr_is_assigned, acr_is_allocated, acr_bkg_type, acr_addresses_given, acr_service_tier, acr_time_create, acr_time_to_pickup,acr_flag_minpickuptime,acr_cancel_minpickuptime, acr_time_confirm, acr_time_bidstarted, acr_auto_cancel_value, acr_status', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'acr_id'					 => 'Acr',
			'acr_demsupmisfire'			 => 'Demsupmisfire',
			'acr_flag_cs'				 => 'Flag Critical Score',
			'acr_cancel_cs'				 => 'Cancel Critical Score',
			'acr_rule_rank'				 => 'Rule Rank',
			'acr_is_assigned'			 => 'Is Assigned',
			'acr_is_allocated'			 => 'Is Allocated',
			'acr_bkg_type'				 => 'Booking Type',
			'acr_addresses_given'		 => 'Is Address Given',
			'acr_service_tier'			 => 'Service Tier',
			'acr_time_create'			 => 'Create Time',
			'acr_time_to_pickup'		 => 'Pickup Time',
			'acr_flag_minpickuptime'	 => 'Flag Mark Time',
			'acr_cancel_minpickuptime'	 => 'Cancel Time',
			'acr_time_confirm'			 => 'Confirm Time',
			'acr_time_bidstarted'		 => 'Bidstarted Time',
			'acr_auto_cancel_value'		 => 'Auto Cancel Value',
			'acr_auto_cancel_code'		 => 'Auto Cancel Code',
			'acr_status'				 => 'Status',
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

		$criteria->compare('acr_id', $this->acr_id);
		$criteria->compare('acr_demsupmisfire', $this->acr_demsupmisfire);
		$criteria->compare('acr_flag_cs', $this->acr_flag_cs);
		$criteria->compare('acr_cancel_cs', $this->acr_cancel_cs);
		$criteria->compare('acr_rule_rank', $this->acr_rule_rank);
		$criteria->compare('acr_is_assigned', $this->acr_is_assigned);
		$criteria->compare('acr_is_allocated', $this->acr_is_allocated);
		$criteria->compare('acr_bkg_type', $this->acr_bkg_type, true);
		$criteria->compare('acr_addresses_given', $this->acr_addresses_given);
		$criteria->compare('acr_service_tier', $this->acr_service_tier, true);
		$criteria->compare('acr_time_create', $this->acr_time_create, true);
		$criteria->compare('acr_time_to_pickup', $this->acr_time_to_pickup, true);
		$criteria->compare('acr_flag_minpickuptime', $this->acr_flag_minpickuptime, true);
		$criteria->compare('acr_cancel_minpickuptime', $this->acr_cancel_minpickuptime, true);
		$criteria->compare('acr_time_confirm', $this->acr_time_confirm, true);
		$criteria->compare('acr_time_bidstarted', $this->acr_time_bidstarted, true);
		$criteria->compare('acr_auto_cancel_value', $this->acr_auto_cancel_value);
		$criteria->compare('acr_auto_cancel_code', $this->acr_auto_cancel_code);
		$criteria->compare('acr_status', $this->acr_status);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AutoCancelRule the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getList($returnType = "DataProvider")
	{
		$condition = " WHERE 1 ";
		if ($this->acr_auto_cancel_value != null)
		{
			$condition .= " AND acr_auto_cancel_value=$this->acr_auto_cancel_value";
		}

		if ($this->acr_auto_cancel_code != null)
		{
			$condition .= " AND acr_auto_cancel_code=$this->acr_auto_cancel_code";
		}

		if ($this->acr_time_create != null)
		{
			$condition .= " AND acr_time_create=$this->acr_time_create";
		}
		if ($this->acr_time_to_pickup != null)
		{
			$condition .= " AND acr_time_to_pickup=$this->acr_time_to_pickup";
		}
		if ($this->acr_time_confirm != '')
		{
			$condition .= " AND acr_time_confirm=$this->acr_time_confirm";
		}
		if ($this->acr_time_bidstarted != null)
		{
			$condition .= " AND acr_time_bidstarted=$this->acr_time_bidstarted";
		}
		if ($this->acr_service_tier != null)
		{
			$serviceTierArr = explode(',', $this->acr_service_tier);
			if (count($serviceTierArr) > 1)
			{
				$condition .= " AND ( ";
				foreach ($serviceTierArr as $value)
				{
					$condition .= "  FIND_IN_SET(" . $value . ", acr_service_tier) OR ";
				}
				$condition	 = rtrim($condition, ' OR');
				$condition	 .= " )  ";
			}
			else
			{
				$condition .= " AND FIND_IN_SET(" . $this->acr_service_tier . ",acr_service_tier)";
			}
		}

		if ($this->acr_bkg_type != null)
		{
			$bookingTypeArr = explode(',', $this->acr_bkg_type);
			if (count($bookingTypeArr) > 1)
			{
				$condition .= " AND ( ";
				foreach ($bookingTypeArr as $value)
				{
					$condition .= "  FIND_IN_SET(" . $value . ", acr_bkg_type) OR ";
				}
				$condition	 = rtrim($condition, ' OR');
				$condition	 .= " )  ";
			}
			else
			{
				$condition .= " AND FIND_IN_SET(" . $this->acr_bkg_type . ",acr_bkg_type)";
			}
		}

		$sql = "SELECT * FROM auto_cancel_rule $condition";

		if ($returnType == "DataProvider")
		{

			$sqlCount		 = "SELECT count(acr_id) FROM auto_cancel_rule $condition ";
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['acr_id'],
					'defaultOrder'	 => 'acr_id DESC'
				],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		elseif ($returnType == "List")
		{
			$sql .= "  AND acr_status=1 ORDER BY acr_rule_rank DESC";
			return DBUtil::query($sql, DBUtil::SDB());
		}
	}

	public function add()
	{
		$returnSet = new ReturnSet();
		$returnSet->setStatus(false);
		try
		{
			$res = $this->save();
			if ($res)
			{
				$returnSet->setStatus(true);
				$returnSet->setData(["id" => $this->acr_id]);
			}
		}
		catch (Exception $e)
		{
			if ($returnSet->getErrorCode() == 0)
			{
				$returnSet->setErrorCode($e->getCode());
				$returnSet->addError($e->getMessage());
			}
		}
		return $returnSet;
	}

	public static function updateAutoCancelBooking($autoCancelRule = [])
	{
		foreach ($autoCancelRule as $value)
		{
			try
			{
				$autoCancelRuleId		 = $value['acr_id'];
				$demsupmisfire			 = $value['acr_demsupmisfire'];
				$criticalScore			 = $value['acr_flag_cs'];
				$criticalMinPickupTime	 = $value['acr_flag_minpickuptime'];
				$vendorid				 = $value['acr_is_assigned'];
				$driverid				 = $value['acr_is_allocated'];
				$bookingType			 = $value['acr_bkg_type'];
				$pickup_address			 = $value['acr_addresses_given'];
				$scc_id					 = $value['acr_service_tier'];
				$createTime				 = $value['acr_time_create'];
				$pickupTime				 = $value['acr_time_to_pickup'];
				$confirmTime			 = $value['acr_time_confirm'];
				$bidStartTime			 = $value['acr_time_bidstarted'];
				$autoCancelFlag			 = $value['acr_auto_cancel_value'];
				$autoCancelCode			 = $value['acr_auto_cancel_code'];
				$where					 = $criticalScore != null ? " AND booking_pref.bkg_critical_score>=$criticalScore  AND TIMESTAMPDIFF(MINUTE,booking.bkg_pickup_date,NOW()) <=$criticalMinPickupTime " : " ";
				$where					 .= $demsupmisfire != null ? " AND booking_trail.btr_is_dem_sup_misfire=$demsupmisfire " : " ";
				if ($vendorid != null && $vendorid != "")
				{
					$where .= $vendorid == 1 ? " AND booking_cab.bcb_vendor_id>0 " : " AND  booking_cab.bcb_vendor_id IS NULL  ";
				}
				if ($driverid != null && $driverid != "")
				{
					$where .= $driverid == 1 ? " AND booking_cab.bcb_driver_id>0 " : " AND booking_cab.bcb_driver_id  IS  NULL  ";
				}
				$where .= ($bookingType != null and $bookingType != "") ? " AND booking.bkg_booking_type IN ($bookingType) " : " ";
				if ($pickup_address != null && $pickup_address != "")
				{
					$where .= $pickup_address == 1 ? " AND booking.bkg_pickup_address  IS NOT NULL  " : " AND  booking.bkg_pickup_address IS NULL  ";
				}
				$where		 .= ($scc_id != null and $scc_id != "") ? " AND service_class.scc_id IN ($scc_id) " : " ";
				$where		 .= $createTime > 0 ? "  AND TIMESTAMPDIFF(MINUTE,booking.bkg_create_date,NOW()) > $createTime " : " ";
				$where		 .= $pickupTime > 0 ? "  AND TIMESTAMPDIFF(MINUTE,booking.bkg_pickup_date,NOW()) >$pickupTime" : " ";
				$where		 .= $confirmTime > 0 ? " AND TIMESTAMPDIFF(MINUTE,booking_trail.bkg_confirm_datetime,NOW()) >$confirmTime " : " ";
				$where		 .= $bidStartTime > 0 ? " AND TIMESTAMPDIFF(MINUTE,booking_trail.btr_bid_start_time,NOW()) > $bidStartTime " : " ";
				$sql		 = "SELECT
								GROUP_CONCAT(bpr_bkg_id)
								FROM booking
								JOIN booking_pref ON booking.bkg_id = booking_pref.bpr_bkg_id
								JOIN booking_cab ON booking.bkg_bcb_id = booking_cab.bcb_id
								JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id AND btr_auto_cancel_value IS NULL AND btr_auto_cancel_reason_id IS NULL
								JOIN svc_class_vhc_cat ON svc_class_vhc_cat.scv_id = booking.bkg_vehicle_type_id
								JOIN service_class  ON service_class.scc_id = svc_class_vhc_cat.scv_scc_id
								WHERE  1 AND booking.bkg_status IN (15,2,3,5) AND booking_pref.bkg_autocancel=0 $where ";
				
				$bkgIdStr	 = DBUtil::queryScalar($sql, DBUtil::SDB());
				if ($bkgIdStr != null)
				{
					$bkgArr		 = explode(",", $bkgIdStr);
					$userInfo	 = UserInfo::getInstance();
					$eventid	 = BookingLog::BOOKING_AUTOCANCEL;
					foreach ($bkgArr as $value)
					{
						$model		 = Booking::model()->findByPk($value);
						$cf			 = $model->bkgPref->bkg_critical_score;
						$currentdate = DBUtil::getCurrentTime();
						$desc		 = "Auto cancel rule was applied to this booking: " . $value . " .CF : " . $cf . " .Auto cancel rule: " . $autoCancelRuleId;
						$sqlUpdate	 = "Update booking_trail SET booking_trail.btr_auto_cancel_value=$autoCancelFlag,booking_trail.btr_auto_cancel_create_date='$currentdate', booking_trail.btr_auto_cancel_reason_id=$autoCancelCode,btr_auto_cancel_rule_id =$autoCancelRuleId WHERE  btr_auto_cancel_value IS NULL  AND btr_auto_cancel_reason_id IS NULL AND btr_bkg_id IN ($value) ";
						$cntRow		 = DBUtil::execute($sqlUpdate);
						if ($cntRow > 0)
						{
							// setting Delegated to Operation manager start
							$model->bkgPref->bpr_assignment_level	 = 2;
							$model->bkgPref->bpr_assignment_id		 = 0;
							if ($model->bkgPref->bpr_assignment_fdate == NULL || $model->bkgPref->bpr_assignment_fdate == "")
							{
								$model->bkgPref->bpr_assignment_fdate = new CDbExpression('NOW()');
							}
							$model->bkgPref->bpr_assignment_ldate = new CDbExpression('NOW()');
							if ($model->bkgPref->save())
							{
								$descDelegate	 = " Booking Id - " . $model->bkg_booking_id . " is delegated to Operation Manager";
								$eventidDelegate = BookingLog::ESCALATE_OM;
								$success=BookingLog::model()->createLog($value, $descDelegate, $userInfo, $eventidDelegate, false, '', '', '');
								if($success && $model->bkgPref->bkg_is_fbg_type==0)
								{
									notificationWrapper::notifyDTMBooking($value);
								}
							}
							BookingLog::model()->createLog($value, $desc, $userInfo, $eventid);
							$bookingName	 = $model->bkg_booking_id;
							$notificationId	 = substr(round(microtime(true) * 1000), -5);
							$omIds			 = Admins::model()->getCsrNotificationList();
							$payLoadData	 = ['bookingId' => $value, 'EventCode' => Booking::CODE_AUTOCANCEL_NOTIFICATION];
							$title			 = "Auto Cancel Flag Set - " . $bookingName;
							$message		 = "Auto cancel rule was applied to this Booking.";
							foreach ($omIds as $omId)
							{
								$omId	 = $omId['adm_id'];
								$result	 = AppTokens::model()->notifyAdmin($omId, $payLoadData, $notificationId, $message, $title);
							}
						}
					}
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function getCancelType($bktype = 0)
	{
		$arrBktype = [
			1	 => 'Cancel',
			2	 => 'Review Cancel',
		];
		if ($bktype != 0)
		{
			return $arrBktype[$bktype];
		}
		else
		{
			return $arrBktype;
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

	public static function revertAutoCancelBooking()
	{
		try
		{
			$sql		 = "SELECT
							GROUP_CONCAT(bkg_id) AS bkg_id
							FROM booking								
							JOIN booking_cab ON booking.bkg_bcb_id = booking_cab.bcb_id
							JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id 
							WHERE  1 AND booking.bkg_status IN (3) AND  booking_trail.btr_auto_cancel_value IS NOT NULL AND booking_trail.btr_auto_cancel_reason_id  NOT IN (33)
							AND  booking_cab.bcb_vendor_id>0 ";
			$bkgIdStr	 = DBUtil::queryScalar($sql, DBUtil::SDB());
			if ($bkgIdStr != null)
			{
				$bkgArr		 = explode(",", $bkgIdStr);
				$userInfo	 = UserInfo::getInstance();
				$eventid	 = BookingLog::BOOKING_AUTOCANCEL;
				foreach ($bkgArr as $value)
				{
					$currentdate = DBUtil::getCurrentTime();
					$model		 = Booking::model()->findByPk($value);
					$cf			 = $model->bkgPref->bkg_critical_score;
					$desc		 = "Auto cancel rule was  removed from this Booking:" . $value . "CF : " . $cf;
					DBUtil::getINStatement($value, $bindString, $params);
					$sqlUpdate	 = "Update booking_trail SET  booking_trail.btr_auto_cancel_value=NULL,booking_trail.btr_auto_cancel_create_date=NULL, booking_trail.btr_auto_cancel_reason_id=NULL, booking_trail.btr_auto_cancel_rule_id=NULL WHERE  btr_auto_cancel_value IS NOT NULL  AND btr_auto_cancel_reason_id IS NOT NULL AND btr_bkg_id IN ($bindString)";
					$cntRow		 = DBUtil::execute($sqlUpdate, $params);
					if ($cntRow > 0)
					{
						// Removing Delegated to Operation manager start
						$model->bkgPref->bpr_assignment_level	 = 0;
						$model->bkgPref->bpr_assignment_id		 = 0;
						$model->bkgPref->bpr_assignment_fdate	 = NULL;
						$model->bkgPref->bpr_assignment_ldate	 = NULL;
						if ($model->bkgPref->save())
						{
							$descDelegate	 = " Booking Id - " . $model->bkg_booking_id . " is delegated to Operation Manager Removing";
							$eventidDelegate = BookingLog::ESCALATE_OM;
							BookingLog::model()->createLog($value, $descDelegate, $userInfo, $eventidDelegate, false, '', '', '');
						}
						BookingLog::model()->createLog($value, $desc, $userInfo, $eventid);
					}
				}
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
	}

}
