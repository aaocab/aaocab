<?php

/**
 * This is the model class for table "promos".
 *
 * The followings are the available columns in table 'promos':
 * @property integer $prm_id
 * @property string $prm_code
 * @property string $prm_desc
 * @property string $prm_valid_from
 * @property string $prm_valid_upto
 * @property string $prm_pickupdate_from
 * @property string $prm_pickupdate_to
 * @property string $prm_createdate_from
 * @property string $prm_createdate_to
 * @property integer $prm_use_max
 * @property integer $prm_applicable_type
 * @property integer $prm_activate_on
 * @property integer $prm_used_counter
 * @property string $prm_applicable_platform
 * @property integer $prm_applicable_user
 * @property integer $prm_applicable_nexttrip
 * @property integer $prm_active
 * @property integer $prm_createtime_before
 * @property integer $prm_createtime_after
 * @property integer $prm_pickuptime_before
 * @property integer $prm_pickuptime_after
 * @property integer $prm_min_base_amount
 * @property integer $prm_logged_in
 * @property integer $prm_booked_min
 * @property integer $prm_booked_max
 * @property integer $prm_complete_min
 * @property integer $prm_complete_max
 * @property integer $prm_not_travelled
 * @property integer $prm_user_type
 * @property string $prm_created
 * @property string $prm_modified
 * @property string $prm_log
 * @property integer $prm_usr_cat_type
 * 
 * @property PromoCalculation $prmCal
 */
class Promos extends CActiveRecord
{

	public $prm_valid_from_date, $prm_valid_from_time, $prm_valid_upto_date, $prm_valid_upto_time;
	public $prm_createdate_from_date, $prm_createdate_from_time, $prm_createdate_to_date, $prm_createdate_to_time;
	public $prm_pickupdate_from_date, $prm_pickupdate_from_time, $prm_pickupdate_to_date, $prm_pickupdate_to_time;
	public $prm_validity, $prm_use_max_other;
	public $promoCode, $createDate, $pickupDate, $fromCityId, $toCityId, $email, $phone, $carType, $bookingType, $totalAmount, $userId, $platform, $imEfect, $autoApply	 = 0, $view		 = false, $noOfSeat;
	public $refId, $bkgId, $refType, $cabType, $pcnType;
	public $from_date_create, $to_date_create, $from_date_pickup, $to_date_pickup, $status;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'promos';
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "prm_active=1",
		);
		return $arr;
	}

	public static $nextTrip				 = [1 => "Next booking", 0 => "Current booking"];
	public static $applicableType		 = [0 => "Manual Apply", 1 => "Auto Apply"];
	public static $applicableUserType	 = [1 => "Particular User", 0 => "All User"];
	public static $applicableTripType	 = [1 => "Particular Trip", 0 => "All Trip"];
	public static $valueType			 = [1 => "Percentage", 2 => "Amount"];
	public static $promoType			 = [1 => "Cash", 2 => "GozoCoins", 3 => "Both", 4 => "Fixed Price"];
	public static $promoUseType			 = [1 => "Booking", 2 => "GiftCard"];
	public static $userType				 = [1 => "Partner", 2 => "User"];
	public static $giftCardFor			 = [1 => "Selected", 2 => "All"];
	public static $source_type			 = ['1' => 'User', '2' => 'Admin', '3' => 'App', '4' => 'Agent', '6' => 'Bot'];
	public static $activateOn			 = [0 => 'Immediate', 1 => 'Advance payment'];
	public static $areaType				 = [1 => 'Zone', 2 => 'State', 3 => 'City', 4 => 'Region'];
	public static $useMax				 = [0 => 'Infinite', 1 => 'Other'];
	public static $region				 = [1 => 'North', 2 => 'West', 3 => 'Central', 4 => 'South', 5 => 'East', 6 => 'North East', 7 => 'South Kerala'];

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prm_code, prm_desc', 'required'),
			array('prm_code,prm_desc,prm_valid_from_date,prm_valid_upto_date,prm_valid_from_time,prm_valid_upto_time', 'required', 'on' => 'add,edit'),
			array('prm_code', 'unique', 'on' => 'add'),
			array('prm_id', 'validatePromoOnAddEdit', 'on' => 'add,edit'),
			array('prm_id,prm_active,prm_min_base_amount', 'numerical', 'integerOnly' => true),
			array('prm_code', 'length', 'max' => 70),
			array('prm_desc', 'length', 'max' => 150),
			array('prm_applicable_platform', 'length', 'max' => 50),
			array('prm_valid_from, prm_valid_upto, prm_pickupdate_from, prm_pickupdate_to, prm_createdate_from, prm_createdate_to', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prm_id, prm_code, prm_desc,prm_valid_from,prm_createtime_after,prm_createtime_before,prm_pickuptime_after,'
				. 'prm_pickuptime_before, prm_valid_upto, prm_pickupdate_from, prm_pickupdate_to, prm_createdate_from, '
				. 'prm_createdate_to,prm_use_max,prm_applicable_type,prm_activate_on,prm_used_counter,prm_applicable_platform,'
				. 'prm_applicable_user,prm_applicable_nexttrip, prm_active, prm_created, prm_modified, prm_log,prm_min_base_amount,prm_logged_in,prm_complete_max,'
				. 'prm_complete_min,prm_booked_min,prm_booked_max,prm_not_travelled,prm_user_type', 'safe'),
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
			'prmCal' => array(self::HAS_ONE, 'PromoCalculation', 'pcn_promo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'prm_id'					 => 'Prm',
			'prm_code'					 => 'Promo Code',
			'prm_desc'					 => 'Promo Description',
			'prm_valid_from'			 => 'Valid From',
			'prm_valid_upto'			 => 'Valid Upto',
			'prm_pickupdate_from'		 => 'Pickupdate From',
			'prm_pickupdate_to'			 => 'Pickupdate To',
			'prm_createdate_from'		 => 'Createdate From',
			'prm_createdate_to'			 => 'Createdate To',
			'prm_active'				 => 'Active',
			'prm_created'				 => 'Created',
			'prm_modified'				 => 'Modified',
			'prm_log'					 => 'log',
			'prm_use_max'				 => 'use max',
			'prm_applicable_type'		 => 'applicable type',
			'prm_activate_on'			 => 'activate on',
			'prm_used_counter'			 => 'used counter',
			'prm_applicable_platform'	 => 'platform',
			'prm_applicable_user'		 => 'all or selected user',
			'prm_applicable_nexttrip'	 => 'usnext trip apply',
			'prm_pickuptime_after'		 => 'Maximum time in hour',
			'prm_pickuptime_before'		 => 'Minimum time in hour',
			'prm_createtime_after'		 => 'Maximum time in hour',
			'prm_createtime_before'		 => 'Minimum time in hour',
			'prm_min_base_amount'		 => 'Minimum Base Amount',
			'prm_logged_in'				 => 'User Logged In',
		);
	}

	public function afterFind()
	{
		parent::afterFind();
		//    $date = strtotime($this->prm_valid_from);
		//    $this->prm_valid_from = DateTimeFormat::DateToLocale($this->prm_valid_from);
	}

	public function beforeSave()
	{
		parent::beforeSave();
		return true;
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

		$criteria->compare('prm_id', $this->prm_id);
		$criteria->compare('prm_code', $this->prm_code, true);
		$criteria->compare('prm_desc', $this->prm_desc, true);
		$criteria->compare('prm_valid_from', $this->prm_valid_from, true);
		$criteria->compare('prm_valid_upto', $this->prm_valid_upto, true);
		$criteria->compare('prm_pickupdate_from', $this->prm_pickupdate_from, true);
		$criteria->compare('prm_pickupdate_to', $this->prm_pickupdate_to, true);
		$criteria->compare('prm_createdate_from', $this->prm_createdate_from, true);
		$criteria->compare('prm_createdate_to', $this->prm_createdate_to, true);
		$criteria->compare('prm_active', $this->prm_active);
		$criteria->compare('prm_created', $this->prm_created, true);
		$criteria->compare('prm_modified', $this->prm_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function validatePromoOnAddEdit($attribute, $params)
	{
		if ($this->pcnType == 4)
		{
//			if ($this->prm_use_max == 0)
//			{
//				$this->addError('prm_use_max', 'Please provide maximum uses');
//				return FALSE;
//			}
			if ($this->prm_logged_in == 0)
			{
				$this->addError('prm_logged_in', 'Please checked logged in');
				return FALSE;
			}
			if ($this->cabType == '')
			{
				$this->addError('pef_cab_type', 'Please select cab type');
				return FALSE;
			}
			if ($this->cabType != 11)
			{
				$this->addError('pef_cab_type', 'Please select shared sedan');
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Promos the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByCode($code)
	{
		return $this->find("prm_code='$code'");
	}

	public function getTncByPromoCode($code)
	{
		$terms = [
			'GET10OFF'		 => 'showTcGozoCoins1()',
			'GET75PEROFF'	 => 'showTcGozoCoins1()',
			'GET5PDISC'		 => 'showTcGozoCoins1()',
			'GET2P5PDISC'	 => 'showTcGozoCoins2p5()',
			'GET50PCB'		 => 'showTcGozoCoins2()',
			'GET25PCB'		 => 'showTcGozoCoins25()'
		];
		return $terms[$code];
	}

	public function applyPromoCode()
	{
		if (!BookingSub::model()->getApplicable($this->fromCityId, $this->toCityId, 1))
		{
			return false;
		}

		if (!$this->checkPromo())
		{
			return false;
		}

		$calculationModel = PromoCalculation::model()->getByPromoId($this->prm_id);
		if (!$calculationModel)
		{
			return false;
		}

		if ($calculationModel->pcn_active == 0)
		{
			return false;
		}
		if ($calculationModel->pcn_type == 4)
		{
			if ($this->noOfSeat == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		$amount = $calculationModel->calculate($this->totalAmount);
		if ($this->prm_applicable_nexttrip == 1)
		{
			if ($this->userId != '')
			{
				$res = PromoUsers::getUserAvailability($this->userId, $this->prm_id, 0);
				if ($res == 0)
				{
					$amount['nextTripApply'] = 1;
				}
				else if ($res > 0)
				{
					$amount['nextTripApply'] = 0;
				}
			}
			else
			{
				return false;
			}
		}
		$amount['id']				 = $this->prm_id;
		$amount['pcn_type']			 = $calculationModel->pcn_type;
		$amount['prm_activate_on']	 = $this->prm_activate_on;
		return $amount;
	}

	/**
	 *
	 * @param BookingInvoice $invoiceModel
	 * @param Promos $model
	 * @return message
	 */
	public static function getDiscountMessage(BookingInvoice $invoiceModel, Promos $model)
	{
//		if (isset($model->prm_applicable_nexttrip) && $model->prm_applicable_nexttrip == 1)
//		{
//			$discountArr['cash']	 = 0;
//			$discountArr['coins']	 = 0;
//		}
		//$message = ($model->prm_activate_on == 1) ? 'Promo ' . $model->prm_code . ' applied successfully .(not confirmed)' : 'Promo ' . $model->prm_code . ' used successfully.(Confirmed)';
		$message = 'Promo ' . $model->prm_code . ' applied successfully. ';
		if ($model->prmCal->pcn_type == 1 && $model->prm_activate_on == 1)
		{
			$message .= " You will get discount worth ₹" . $invoiceModel->bkg_discount_amount . " when you make payment.";
		}
		if ($model->prmCal->pcn_type == 2)
		{
			$message .= "You got Gozo Coins worth ₹" . $invoiceModel->bkg_promo1_coins . ". You may redeem these Gozo Coins against your future bookings with us.";
		}
		if ($model->prmCal->pcn_type == 3)
		{
			$message .= " You will get discount worth ₹" . $invoiceModel->bkg_discount_amount . " and Gozo Coins worth ₹" . $invoiceModel->bkg_promo1_coins . ".* You may redeem these Gozo Coins against your future bookings with us.";
		}
		if ($invoiceModel->bivBkg->bkgTrail->bkg_platform != 3 && (in_array($model->prmCal->pcn_type, [2, 3])))
		{
			$message .= "*<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
		}
		if (isset($model->prm_applicable_nexttrip) && $model->prm_applicable_nexttrip == 1)
		{
			$message .= " You will be benefited on your next trip.";
		}
		return $message;
	}

	/**
	 *
	 * @param BookingInvoice $model
	 * @param integer $promoCode
	 * @return type
	 * @throws Exception
	 */
	public static function getDiscount($model, $promoCode)
	{
		/* @var $promoModel Promos */
		$promoModel = Promos::model()->getByCode($promoCode);
		if (!$promoModel)
		{
			throw new Exception("Invalid promo code.", ReturnSet::ERROR_INVALID_DATA);
		}
		$promoModel->promoCode	 = $promoCode;
		$promoModel->totalAmount = $model->bkg_base_amount;
		$promoModel->createDate	 = $model->bivBkg->bkg_create_date;
		$promoModel->pickupDate	 = $model->bivBkg->bkg_pickup_date;
		$promoModel->fromCityId	 = $model->bivBkg->bkg_from_city_id;
		$promoModel->toCityId	 = $model->bivBkg->bkg_to_city_id;
		$promoModel->userId		 = $model->bivBkg->bkgUserInfo->bkg_user_id;
		$promoModel->platform	 = $model->bivBkg->bkgTrail->bkg_platform;
		$promoModel->carType	 = $model->bivBkg->bkg_vehicle_type_id;
		$promoModel->bookingType = $model->bivBkg->bkg_booking_type;
		$promoModel->noOfSeat	 = $model->bivBkg->bkgAddInfo->bkg_no_person;
		$promoModel->bkgId		 = $model->biv_bkg_id;
		$promoModel->email		 = '';
		$promoModel->phone		 = '';
		$promoModel->imEfect	 = 1;

		$returnData = $promoModel->getDiscountMessage($model);
		if ($returnData->isSuccess())
		{
			return [
				'id'		 => $returnData->getData()['id'],
				'amount'	 => $returnData->getData()['amount'],
				'coins'		 => $returnData->getData()['coins'],
				'message'	 => $returnData->getMessage(),
				'promoModel' => $promoModel];
		}
		else
		{
			return false;
		}
	}

	public function validatePromoCode()
	{
		if ($this->prm_logged_in == 1 && $this->platform != 2)
		{
			$this->userId = Yii::app()->user->getId();
		}
		if ($this->prm_logged_in == 1 && $this->userId == '' && $this->view == false)
		{
			return false;
		}
		else
		{
			if ($this->prm_logged_in == 1 && $this->userId != '' && $this->view == false && ($this->prm_use_max > 0 || $this->prmCal->pcn_type == 4) && $this->prm_applicable_nexttrip == 0)
			{
				$res = PromoUsers::getUserAvailability($this->userId, $this->prm_id, 0);
				if ($res == 0)
				{
					PromoUsers::addUser($this->prm_id, $this->userId, 0, 0, date('Y-m-d') . " 00:00:00", date('Y-m-d', strtotime('+1 year')) . " 00:00:00", 1);
				}
			}
		}
		if ($this->prm_booked_min != '' || $this->prm_booked_max != '' || $this->prm_complete_min != '' || $this->prm_complete_max != '' || $this->prm_applicable_user == 1 || $this->prm_logged_in == 1 || ($this->prm_logged_in == 1 && $this->prm_use_max > 0) || $this->prm_not_travelled != '' || $this->prmCal->pcn_type == 4 || $this->prm_applicable_nexttrip == 1)
		{
			if ($this->userId == '')
			{
				$this->userId = Users::model()->linkUserid($this->email, $this->phone);
				if (!$this->userId)
				{
					return false;
				}
			}
		}
		if (!$this->getRefId())
		{
			return false;
		}

		if (!$this->checkPromo())
		{
			return false;
		}

		if (!PromoEntityFilter::model()->getApplicable($this->prm_id, $this->fromCityId, $this->toCityId, $this->bookingType, $this->carType))
		{
			return false;
		}

		$dateFilter = PromoDateFilter::model()->getByPromoId($this->prm_id);
		if ($dateFilter)
		{
			if (!$dateFilter->getCreateDateFilterApplicable($this->createDate))
			{
				return false;
			}
			if (!$dateFilter->getPickupDateFilterApplicable($this->pickupDate))
			{
				return false;
			}
		}


		return true;
	}

	public function checkPromo()
	{
		$where = "prm_valid_from <= CURRENT_TIMESTAMP AND prm_valid_upto >= CURRENT_TIMESTAMP";
		if ($this->imEfect == 1)
		{
			$where .= " AND (prm_use_max > prm_used_counter OR prm_use_max=0)";
		}
		if ($this->totalAmount > 0)
		{
			$where .= " AND (prm_min_base_amount <=" . $this->totalAmount . " OR prm_min_base_amount IS NULL)";
		}
		if ($this->bookingType != '')
		{
			$where .= " AND (find_in_set(" . $this->bookingType . ",pef.pef_booking_type) OR pef.pef_booking_type IS NULL)";
		}
		if ($this->carType != '')
		{
			$where .= " AND (find_in_set(" . $this->carType . ",pef.pef_cab_type) OR pef.pef_cab_type IS NULL)";
		}

		if (($this->prm_booked_min != '' || $this->prm_booked_max != '') && $this->userId > 0)
		{
			$bSql	 = "SELECT COUNT(1) FROM booking LEFT JOIN booking_user ON bui_bkg_id=bkg_id WHERE bkg_user_id=" . $this->userId . " AND bkg_active=1 AND bkg_status IN(2,3,4,5,6)";
			$bRes	 = DBUtil::command($bSql)->queryScalar();
			$where	 .= " AND (prm_booked_min < $bRes OR prm_booked_min IS NULL) AND (prm_booked_max > $bRes OR prm_booked_max IS NULL)";
		}
		if (($this->prm_complete_min != '' || $this->prm_complete_max != '') && $this->userId > 0)
		{
			$bCSql	 = "SELECT COUNT(1) FROM booking
					LEFT JOIN booking_user ON bui_bkg_id=bkg_id
					WHERE
					bkg_status=6 AND
					bkg_user_id=" . $this->userId . " AND bkg_active=1";
			$bCRes	 = DBUtil::command($bCSql)->queryScalar();
			$where	 .= " AND (prm_complete_min < $bCRes OR prm_complete_min IS NULL) AND (prm_complete_max > $bCRes OR prm_complete_max IS NULL)";
		}
		if ($this->prm_not_travelled != '' && $this->userId > 0)
		{
			$nTSql	 = "SELECT DATEDIFF(NOW(),bkg_create_date) FROM booking
					LEFT JOIN booking_user ON bui_bkg_id=bkg_id
					WHERE
					bkg_user_id =" . $this->userId . "
					AND bkg_status IN(2,3,4,5,6)
					AND bkg_active=1
					ORDER BY bkg_create_date DESC
					LIMIT 0,1";
			$nTRes	 = DBUtil::command($nTSql)->queryScalar();
			$where	 .= " AND (prm_not_travelled < $nTRes OR prm_not_travelled IS NULL)";
		}
		$currDateTime	 = Filter::getDBDateTime();
		$workingHrsDiff	 = Filter::CalcWorkingHour($currDateTime, $this->pickupDate);
		$sql			 = "SELECT COUNT(1)
				FROM promos
				INNER JOIN promo_entity_filter pef ON pef.pef_promo_id = promos.prm_id
				WHERE
				$where
				AND ( (NOW() > DATE_ADD('" . $this->createDate . "', INTERVAL prm_createtime_before HOUR)) OR prm_createtime_before IS NULL OR prm_createtime_before='')
				AND ( (NOW() < DATE_ADD('" . $this->createDate . "', INTERVAL prm_createtime_after HOUR)) OR prm_createtime_after IS NULL OR prm_createtime_after='')
				AND (prm_pickuptime_before<=$workingHrsDiff OR prm_pickuptime_before IS NULL OR prm_pickuptime_before='')
                                AND (prm_pickuptime_after>=$workingHrsDiff OR prm_pickuptime_after IS NULL OR prm_pickuptime_after='')
				AND (prm_pickupdate_from <='" . $this->pickupDate . "' OR prm_pickupdate_from IS NULL) AND (prm_pickupdate_to >= '" . $this->pickupDate . "' OR prm_pickupdate_to IS NULL)
				AND (prm_createdate_from <= '" . $this->createDate . "' OR prm_createdate_from IS NULL) AND (prm_createdate_to >= '" . $this->createDate . "' OR prm_createdate_to IS NULL)
				AND find_in_set(" . $this->platform . ",prm_applicable_platform)
				AND prm_active=1 AND LOWER(prm_code)='" . strtolower($this->prm_code) . "'";
		#echo $sql;
		return DBUtil::command($sql)->queryScalar();
	}

	/**
	 *
	 * @return boolean
	 */
	public function validatePromo()
	{
		$success	 = false;
		$baseAmount	 = $this->totalAmount;
		$createDate	 = $this->createDate;
		$pickupDate	 = $this->pickupDate;
		$cabType	 = $this->carType;
		$platform	 = $this->platform;
		$bookingType = $this->bookingType;
		$fromCity	 = $this->fromCityId;
		$toCity		 = $this->toCityId;
		$promoCode	 = $this->promoCode;

		$row		 = Cities::getZonesAndState($fromCity);
		$fromState	 = $row['stt_id'];
		$fromZones	 = "," . str_replace(",", "|", $row['zones']) . ",";
		$fromRegion	 = $row['stt_zone'];

		$row		 = Cities::getZonesAndState($toCity);
		$toState	 = $row['stt_id'];
		$toZones	 = "," . str_replace(",", "|", $row['zones']) . ",";
		$toRegion	 = $row['stt_zone'];
		if ($cabType != '')
		{
			$sqlCab	 = "SELECT vct_id FROM svc_class_vhc_cat,vehicle_category WHERE  scv_vct_id = vct_id AND scv_id = $cabType";
			$cabType = DBUtil::command($sqlCab)->queryScalar();
		}
		$sqlParams = [':baseAmount'	 => $baseAmount, ':createDate'	 => $createDate, ':pickupDate'	 => $pickupDate, ':cabType'		 => $cabType,
			':platform'		 => $platform, ':bookingType'	 => $bookingType, ':fcity'		 => $fromCity, ':tcity'		 => $toCity,
			':fstate'		 => $fromState, ':fzones'		 => $fromZones, ':fregion'		 => $fromRegion, ':tstate'		 => $toState,
			':tzones'		 => $toZones, ':tregion'		 => $toRegion];

		$promoData = self::applicableCodesQuery($sqlParams, $promoCode);
		if ($promoData->getRowCount() > 0)
		{
			$success = true;
		}
		return $success;
	}

	public function getList()
	{
		$condition = "1";
		if ($this->prm_code != '')
		{
			$condition .= " AND prm_code like'%" . $this->prm_code . "%'";
		}
		if ($this->prm_desc != '')
		{
			$condition .= " AND prm_desc like '%" . $this->prm_desc . "%'";
		}
		if ($this->prm_applicable_type != '')
		{
			$condition .= " AND prm_applicable_type IN(" . implode(',', $this->prm_applicable_type) . ")";
		}
		if ($this->prm_applicable_platform != 0)
		{
			$condition .= " AND find_in_set(" . $this->prm_applicable_platform . ",prm_applicable_platform)";
		}
		if ($this->prm_applicable_user != "")
		{
			$condition .= " AND prm_applicable_user IN(" . implode(',', $this->prm_applicable_user) . ")";
		}
		if ($this->prm_active != "")
		{
			if (is_array($this->prm_active))
			{
				$condition .= " AND prm_active IN(" . implode(',', $this->prm_active) . ")";
			}
			else
			{
				$condition .= " AND prm_active IN(" . $this->prm_active . ")";
			}
		}
		if (count($this->prm_validity) == 1)
		{
			if ($this->prm_validity[0] == "1")
			{
				$condition .= " AND prm_valid_upto < NOW() ";
//				$condition .= " AND ((prm_pickupdate_from IS NOT NULL AND prm_pickupdate_from > NOW()) OR (prm_pickupdate_to IS NOT NULL AND prm_pickupdate_to < NOW())) ";
//				$condition .= " AND ((prm_createdate_from IS NOT NULL AND prm_createdate_from > NOW()) OR (prm_createdate_to IS NOT NULL AND prm_createdate_to < NOW())) ";
			}
			if ($this->prm_validity[0] == "0")
			{
				$condition .= " AND prm_valid_upto >= NOW()";
				$condition .= " AND (prm_pickupdate_to IS NULL OR prm_pickupdate_to >= NOW()) ";
				$condition .= " AND (prm_createdate_to IS NULL OR prm_createdate_to >= NOW()) ";
			}
		}

		$sql			 = "SELECT * FROM promos
					INNER JOIN promo_calculation ON promo_calculation.pcn_promo_id=promos.prm_id
					WHERE $condition
					ORDER BY prm_id DESC";
		$count			 = DBUtil::command("SELECT COUNT(1) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public function getApplicableSources()
	{
		$src	 = str_replace(',', ', ', $this->prm_applicable_platform);
		$srctype = strtr($src, Promos::$source_type);
		return $srctype;
	}

	public function getApplicableSources1($platform)
	{
		$src	 = str_replace(',', ', ', $platform);
		$srctype = strtr($src, Promos::$source_type);
		return $srctype;
	}

	public function getActivePromoCodeJson()
	{
		$result	 = $this->getActivePromoCode();
		$arrJSON = array();
		foreach ($result as $val)
		{
			$arrJSON[] = array("id" => $val['prm_code'], "text" => $val['prm_code'] . ' (' . $val['prm_desc'] . ')');
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getActivePromoCode()
	{
		$result = DBUtil::command("SELECT  `prm_id`, `prm_code`, `prm_desc`, IF(prm_valid_upto IS NULL, 1, 0) as valid
										FROM     `promos`
										WHERE    (`prm_valid_upto` >= CURRENT_TIMESTAMP OR `prm_valid_upto` IS NULL) AND prm_active = 1
										ORDER BY `valid`, `prm_valid_upto`")->queryAll();
		return $result;
	}

	public function getExpTimeAdvPromo($createDate, $pickTime, $app = false)
	{
		$now				 = new DateTime(date('Y-m-d H:i:s'));
		$add8hrCreateDate	 = new DateTime(date('Y-m-d H:i:s', strtotime($createDate . '+8 hour')));
		$sub24pickDate		 = new DateTime(date('Y-m-d H:i:s', strtotime($pickTime . '-24 hour')));
		$sub12pickDate		 = new DateTime(date('Y-m-d H:i:s', strtotime($pickTime . '-12 hour')));
		$sub8pickdate		 = new DateTime(date('Y-m-d H:i:s', strtotime($pickTime . '-8 hour')));
		$sub18pickdate		 = new DateTime(date('Y-m-d H:i:s', strtotime($pickTime . '-18 hour')));

		if ($app)
		{
			if ($now < $sub24pickDate)
			{
				return 2;
			}
			if ($now < $sub12pickDate)
			{
				return 3;
			}
			else
			{
				return 4;
			}
			return 0;
		}

		if ($now < $add8hrCreateDate && $now < $sub8pickdate)
		{
			return 1;
		}
		if ($now < $sub18pickdate)
		{
			return 1;
		}
		else if ($now > $sub18pickdate && $now < $sub8pickdate)
		{
			return 3;
		}
		else if ($now > $sub8pickdate)
		{
			return 0;
		}

		return 0;
	}

	public function getApplicableCodes($view = false)
	{
		if ($this->userId == '')
		{
			$this->userId = Users::model()->linkUserid($this->email, $this->phone);
			if (!$this->userId)
			{
				return false;
			}
		}
		if ($view == false)
		{
			$promoArr = $this->getAutoApplyPromoCodes();
		}
		else
		{
			$promoArr = $this->getAutoApplyPromoCodesWithoutCashBask();
		}

		foreach ($promoArr as $key => $value)
		{
			$promoModel				 = $this->findByPk($value['prm_id']);
			$promoModel->promoCode	 = $value['prm_code'];
			$promoModel->totalAmount = $this->totalAmount;
			$promoModel->createDate	 = $this->createDate;
			$promoModel->pickupDate	 = $this->pickupDate;
			$promoModel->fromCityId	 = $this->fromCityId;
			$promoModel->toCityId	 = $this->toCityId;
			$promoModel->userId		 = $this->userId;
			$promoModel->platform	 = $this->platform;
			$promoModel->carType	 = $this->carType;
			$promoModel->bookingType = $this->bookingType;
			$promoModel->refId		 = $this->refId;
			$promoModel->refType	 = $this->refType;
			$promoModel->bkgId		 = $this->bkgId;
			$promoModel->autoApply	 = 1;
			$promoModel->imEfect	 = 1;
			$promoModel->view		 = true;
			if ($promoModel->validatePromoCode())
			{
				if ($view == false)
				{
					if ($this->platform == 1)
					{
						$promoArr[$key]['prm_desc'] = $value['prm_desc'] . " *<a href='#' onclick='" . $this->getTncByPromoCode($value['prm_code']) . "'>T&C</a> Apply";
					}
					if ($this->platform == 3)
					{
						unset($promoArr[$key]['valid']);
					}
				}
			}
			else
			{
				unset($promoArr[$key]);
			}
		}
		return array_unique($promoArr, SORT_REGULAR);
	}

	public function getAutoApplyPromoCodes()
	{
		if ($this->bkgId == '')
		{
			$this->bkgId = -1;
		}
		$sql = "SELECT prm_id,
				prm_code,
				prm_desc,
				IF(prm_valid_upto IS NULL, 1, 0) AS valid,
				  IF(pcn_type = 1, IF(pcn_value_type_cash = 1,
								IF((pcn_value_cash * $this->totalAmount / 100) > pcn_max_cash,IF(pcn_max_cash > 0,pcn_max_cash,(pcn_value_cash * $this->totalAmount / 100)),
							(pcn_value_cash * $this->totalAmount / 100)
						),
						pcn_value_cash
					),
					IF(
						pcn_type = 2,
						IF(
							pcn_value_type_coins = 1,
							IF(
								(pcn_value_coins * $this->totalAmount / 100) > pcn_max_coins,
								IF(pcn_max_coins > 0,pcn_max_coins,(pcn_value_coins * $this->totalAmount / 100)),
								(pcn_value_coins * $this->totalAmount / 100)
							),
							pcn_value_coins
						),
						IF(
							pcn_value_type_cash = 1 AND pcn_value_type_coins = 1,
							IF(
								(pcn_value_cash * $this->totalAmount / 100) > pcn_max_cash AND(pcn_value_coins * $this->totalAmount / 100) > pcn_max_coins,
								IF(
									pcn_max_cash > 0 AND pcn_max_coins > 0,
									pcn_max_cash + pcn_max_coins,
									IF(
										pcn_max_cash <= 0 AND pcn_max_coins > 0,
										(pcn_value_cash * $this->totalAmount / 100) + pcn_max_coins,
										IF(
											pcn_max_cash > 0 AND pcn_max_coins <= 0,
											pcn_max_cash +(pcn_value_coins * $this->totalAmount / 100),
											(pcn_value_cash * $this->totalAmount / 100) +(pcn_value_coins * $this->totalAmount / 100)
										)
									)
								),
								IF(
									(pcn_value_cash * $this->totalAmount / 100) < pcn_max_cash AND (pcn_value_coins * $this->totalAmount / 100) > pcn_max_coins,
									IF(
										pcn_max_coins > 0,
										(pcn_value_cash * $this->totalAmount / 100) + pcn_max_coins,
										(pcn_value_cash * $this->totalAmount / 100) +(pcn_value_coins * $this->totalAmount / 100)
									),
									IF(
										(pcn_value_cash * $this->totalAmount / 100) > pcn_max_cash AND (pcn_value_coins * $this->totalAmount / 100) < pcn_max_coins,
										IF(
											pcn_max_cash > 0,
											(pcn_value_coins * $this->totalAmount / 100) + pcn_max_cash,
											(pcn_value_coins * $this->totalAmount / 100) +(pcn_value_cash * $this->totalAmount / 100)
										),
										(pcn_value_coins * $this->totalAmount / 100) +(pcn_value_cash * $this->totalAmount / 100)
									)
								)
							),
							IF(
								pcn_value_type_cash = 1 AND pcn_value_type_coins = 2,
								IF(
									(pcn_value_cash * $this->totalAmount / 100) > pcn_max_cash,
									pcn_max_cash + pcn_value_coins,
									(pcn_value_cash * $this->totalAmount / 100) + pcn_value_coins
								),
								IF(
									pcn_value_type_cash = 2 AND pcn_value_type_coins = 1,
									pcn_max_coins + pcn_value_cash,
									pcn_value_cash + pcn_value_coins
								)
							)
						)
					)
				) AS amount
				FROM promos
				INNER JOIN promo_calculation ON promos.prm_id = promo_calculation.pcn_promo_id
				LEFT JOIN (
					SELECT pru_id,pru_promo_id,pru_auto_apply FROM promo_users
					WHERE
					(promo_users.pru_ref_id = $this->userId AND promo_users.pru_ref_type=0) OR (promo_users.pru_ref_id = $this->bkgId AND promo_users.pru_ref_type=1)
					AND pru_valid_from <= CURRENT_TIMESTAMP
					AND pru_valid_upto >= CURRENT_TIMESTAMP
					AND pru_active=1
				) abc ON promos.prm_id = abc.pru_promo_id
				WHERE
				prm_valid_from <= CURRENT_TIMESTAMP
				AND prm_valid_upto >= CURRENT_TIMESTAMP
				AND (prm_pickupdate_from <='$this->pickupDate' OR prm_pickupdate_from IS NULL)
				AND (prm_pickupdate_to >= '$this->pickupDate' OR prm_pickupdate_to IS NULL)
				AND (prm_createdate_from <= '$this->createDate' OR prm_createdate_from IS NULL)
				AND (prm_createdate_to >= '$this->createDate' OR prm_createdate_to IS NULL)
				AND (prm_use_max > prm_used_counter OR prm_use_max=0)
				AND find_in_set('$this->platform',prm_applicable_platform)
				AND ( (NOW() > DATE_ADD('$this->createDate', INTERVAL prm_createtime_before HOUR)) OR prm_createtime_before IS NULL OR prm_createtime_before='')
				AND ( (NOW() < DATE_ADD('$this->createDate', INTERVAL prm_createtime_after HOUR)) OR prm_createtime_after IS NULL OR prm_createtime_after='')
				AND ( (NOW() < DATE_SUB('$this->pickupDate', INTERVAL prm_pickuptime_before HOUR)) OR prm_pickuptime_before IS NULL OR prm_pickuptime_before='')
				AND ( (NOW() > DATE_SUB('$this->pickupDate', INTERVAL prm_pickuptime_after HOUR)) OR prm_pickuptime_after IS NULL OR prm_pickuptime_after='')
				AND prm_active=1 AND ((prm_applicable_type = 1 AND pru_id IS NULL) OR (prm_applicable_type = 1 AND pru_auto_apply = 1) OR (prm_applicable_type = 0 AND pru_auto_apply = 1))
				ORDER BY amount DESC
				 ";
		return DBUtil::queryAll($sql);
	}

	public function getAutoApplyPromoCodesWithoutCashBask()
	{
		if ($this->bkgId == '')
		{
			$this->bkgId = -1;
		}
		$sql = "SELECT prm_id,
				prm_code,
				pcn_value_cash,
				pcn_max_cash,
				pcn_min_cash,
				  IF(
					pcn_type = 1,
					IF(
						pcn_value_type_cash = 1,
						IF(
							(pcn_value_cash * $this->totalAmount / 100) > pcn_max_cash,
							IF(pcn_max_cash > 0,pcn_max_cash,(pcn_value_cash * $this->totalAmount / 100)),
							(pcn_value_cash * $this->totalAmount / 100)
						),
						pcn_value_cash
					),
					0
				) AS amount
				FROM promos
				INNER JOIN promo_calculation ON promos.prm_id = promo_calculation.pcn_promo_id
				LEFT JOIN (
					SELECT pru_id,pru_promo_id,pru_auto_apply FROM promo_users
					WHERE
					(promo_users.pru_ref_id = $this->userId AND promo_users.pru_ref_type=0) OR (promo_users.pru_ref_id = $this->bkgId AND promo_users.pru_ref_type=1)
					AND pru_valid_from <= CURRENT_TIMESTAMP
					AND pru_valid_upto >= CURRENT_TIMESTAMP
					AND pru_active=1
				) abc ON promos.prm_id = abc.pru_promo_id
				WHERE
				prm_valid_from <= CURRENT_TIMESTAMP
				AND prm_valid_upto >= CURRENT_TIMESTAMP
				AND ( (NOW() > DATE_ADD('$this->createDate', INTERVAL prm_createtime_before HOUR)) OR prm_createtime_before IS NULL OR prm_createtime_before='')
				AND ( (NOW() < DATE_ADD('$this->createDate', INTERVAL prm_createtime_after HOUR)) OR prm_createtime_after IS NULL OR prm_createtime_after='')
				AND ( (NOW() < DATE_SUB('$this->pickupDate', INTERVAL prm_pickuptime_before HOUR)) OR prm_pickuptime_before IS NULL OR prm_pickuptime_before='')
				AND ( (NOW() > DATE_SUB('$this->pickupDate', INTERVAL prm_pickuptime_after HOUR)) OR prm_pickuptime_after IS NULL OR prm_pickuptime_after='')
				AND (prm_pickupdate_from <='$this->pickupDate' OR prm_pickupdate_from IS NULL)
				AND (prm_pickupdate_to >= '$this->pickupDate' OR prm_pickupdate_to IS NULL)
				AND (prm_createdate_from <= '$this->createDate' OR prm_createdate_from IS NULL)
				AND (prm_createdate_to >= '$this->createDate' OR prm_createdate_to IS NULL)
				AND (prm_use_max > prm_used_counter OR prm_use_max=0)
				AND find_in_set($this->platform,prm_applicable_platform)
				AND prm_active=1 AND ((prm_applicable_type = 1 AND pru_id IS NULL) OR (prm_applicable_type = 1 AND pru_auto_apply = 1) OR (prm_applicable_type = 0 AND pru_auto_apply = 1))
				AND pcn_value_type_cash = 1 AND pcn_value_type_coins IS NULL
				ORDER BY amount DESC
				";
		return DBUtil::queryAll($sql);
	}

	/* @deprecated */

	public static function allApplicableCodesOld($baseAmount, $createDate, $pickupDate, $cabType = '', $platform = 1, $bookingType, $fromCity, $toCity)
	{
		$row		 = Cities::getZonesAndState($fromCity);
		$fromState	 = $row['stt_id'];
		$fromZones	 = "," . str_replace(",", "|", $row['zones']) . ",";
		$fromRegion	 = $row['stt_zone'];

		$row		 = Cities::getZonesAndState($toCity);
		$toState	 = $row['stt_id'];
		$toZones	 = "," . str_replace(",", "|", $row['zones']) . ",";
		$toRegion	 = $row['stt_zone'];
		if ($cabType != '')
		{
			$sqlCab	 = "SELECT vct_id FROM svc_class_vhc_cat,vehicle_category WHERE  scv_vct_id = vct_id AND scv_id = $cabType";
			$cabType = DBUtil::command($sqlCab)->queryScalar();
		}
		$sqlParams = [':baseAmount'	 => $baseAmount, ':createDate'	 => $createDate, ':pickupDate'	 => $pickupDate, ':cabType'		 => $cabType,
			':platform'		 => $platform, ':bookingType'	 => $bookingType, ':fcity'		 => $fromCity, ':tcity'		 => $toCity,
			':fstate'		 => $fromState, ':fzones'		 => $fromZones, ':fregion'		 => $fromRegion, ':tstate'		 => $toState,
			':tzones'		 => $toZones, ':tregion'		 => $toRegion];

		$sql = "SELECT prm_id, prm_code, prm_desc, IF(pcn_type IN (1,3),1,0) as rank,
						IF(pcn_type IN (1,3), calculateValue( :baseAmount, `pcn_value_type_cash`, `pcn_value_cash`, `pcn_max_cash`, `pcn_min_cash`),0) as cashAmount,
						IF(pcn_type IN (2,3), calculateValue(  :baseAmount, `pcn_value_type_coins`, `pcn_value_coins`, `pcn_max_coins`, `pcn_min_coins`),0) as coinsAmount
				FROM promos p
				INNER JOIN promo_calculation ON p.prm_id = promo_calculation.pcn_promo_id
				LEFT JOIN promo_entity_filter pef ON p.prm_id = pef.pef_promo_id AND p.prm_active=1 AND pef.pef_active=1
				LEFT JOIN promo_date_filter pdf ON p.prm_id = pdf.pcd_promo_id AND pdf.pcd_active=1
				WHERE prm_valid_from <= CURRENT_TIMESTAMP AND prm_valid_upto >= CURRENT_TIMESTAMP
						AND (prm_pickupdate_from <= DATE(:pickupDate) OR prm_pickupdate_from IS NULL)
						AND (prm_pickupdate_to >= DATE(:pickupDate) OR prm_pickupdate_to IS NULL)
						AND (prm_createdate_from <= DATE(:createDate) OR prm_createdate_from IS NULL)
						AND (prm_createdate_to >= DATE(:createDate) OR prm_createdate_to IS NULL)
						AND ( (NOW() > DATE_ADD(:createDate, INTERVAL prm_createtime_before HOUR)) OR prm_createtime_before IS NULL OR prm_createtime_before='')
						AND ( (NOW() < DATE_ADD(:createDate, INTERVAL prm_createtime_after HOUR)) OR prm_createtime_after IS NULL OR prm_createtime_after='')
						AND ( (NOW() < DATE_SUB(:pickupDate, INTERVAL prm_pickuptime_before HOUR)) OR prm_pickuptime_before IS NULL OR prm_pickuptime_before='')
						AND ( (NOW() > DATE_SUB(:pickupDate, INTERVAL prm_pickuptime_after HOUR)) OR prm_pickuptime_after IS NULL OR prm_pickuptime_after='')
						AND (prm_use_max > prm_used_counter OR prm_use_max=0)
						AND FIND_IN_SET(:platform, prm_applicable_platform)
						AND prm_active=1 AND prm_applicable_type = 1 AND (prm_min_base_amount< :baseAmount OR prm_min_base_amount IS NULL)
						AND (pef.pef_area_type_from IS NULL
								OR (pef.pef_area_type_from=1 AND CONCAT(',', pef.pef_area_from_id, ',') REGEXP :fzones)
								OR (pef.pef_area_type_from=2 AND FIND_IN_SET(:fstate, pef.pef_area_from_id))
								OR (pef.pef_area_type_from=3 AND FIND_IN_SET(:fcity, pef.pef_area_from_id))
								OR (pef.pef_area_type_from=4 AND FIND_IN_SET(:fregion, pef.pef_area_from_id))
						) AND (pef.pef_area_type_to IS NULL
								OR (pef.pef_area_type_to=1 AND  CONCAT(',', pef.pef_area_to_id, ',') REGEXP :tzones)
								OR (pef.pef_area_type_to=2 AND FIND_IN_SET(:tstate, pef.pef_area_to_id))
								OR (pef.pef_area_type_to=3 AND FIND_IN_SET(:tcity, pef.pef_area_to_id))
								OR (pef.pef_area_type_to=4 AND FIND_IN_SET(:tregion, pef.pef_area_to_id))
						) AND (pef.pef_area_type IS NULL
								OR (pef.pef_area_type=1 AND (CONCAT(',', pef.pef_area_id, ',') REGEXP :fzones) OR CONCAT(',', pef.pef_area_id, ',') REGEXP :tzones)
								OR (pef.pef_area_type=2 AND (FIND_IN_SET(:fstate, pef.pef_area_id) OR FIND_IN_SET(:tstate, pef.pef_area_id)))
								OR (pef.pef_area_type=3 AND (FIND_IN_SET(:fcity, pef.pef_area_id) OR FIND_IN_SET(:tcity, pef.pef_area_id)))
								OR (pef.pef_area_type=4 AND (FIND_IN_SET(:fregion, pef.pef_area_id) OR FIND_IN_SET(:tregion, pef.pef_area_id)))
						) AND (pef.pef_booking_type IS NULL OR FIND_IN_SET(:bookingType, pef.pef_booking_type))
						AND (pef.pef_cab_type IS NULL OR FIND_IN_SET(:cabType, pef.pef_cab_type))
						AND (pdf.pcd_weekdays_create IS NULL OR FIND_IN_SET((WEEKDAY(:createDate)+1),pdf.pcd_weekdays_create))
						AND (pdf.pcd_weekdays_pickup IS NULL OR FIND_IN_SET((WEEKDAY(:pickupDate)+1),pdf.pcd_weekdays_pickup))
						AND (pdf.pcd_weeks_create IS NULL OR FIND_IN_SET((FLOOR((DAYOFMONTH(:createDate) - 1) / 7) + 1), pdf.pcd_weeks_create))
						AND (pdf.pcd_weeks_pickup IS NULL OR FIND_IN_SET((FLOOR((DAYOFMONTH(:pickupDate) - 1) / 7) + 1), pdf.pcd_weeks_pickup))
						AND (pdf.pcd_months_create IS NULL OR FIND_IN_SET(MONTH(:createDate),pdf.pcd_months_create))
						AND (pdf.pcd_months_pickup IS NULL OR FIND_IN_SET(MONTH(:pickupDate),pdf.pcd_months_pickup))
						AND (pdf.pcd_monthdays_create IS NULL OR FIND_IN_SET(DAY(:createDate),pdf.pcd_monthdays_create))
						AND (pdf.pcd_monthdays_pickup IS NULL OR FIND_IN_SET(DAY(:pickupDate),pdf.pcd_monthdays_pickup))
				ORDER BY rank DESC, cashAmount DESC, coinsAmount DESC
				";

		return DBUtil::query($sql, DBUtil::SDB(), $sqlParams);
	}

	/**
	 * @param Booking $model
	 * @param string $promoCode
	 * @return type
	 */
	public static function allApplicableCodes(Booking $model, $promoCode = null)
	{
		$condUserCategory = "";
		$row		 = Cities::getZonesAndState($model->bkg_from_city_id);
		$fromState	 = $row['stt_id'];
		$fromZones	 = "," . str_replace(",", "|", $row['zones']) . ",";
		$fromRegion	 = $row['stt_zone'];

		$row		 = Cities::getZonesAndState($model->bkg_to_city_id);
		$toState	 = $row['stt_id'];
		$toZones	 = "," . str_replace(",", "|", $row['zones']) . ",";
		$toRegion	 = $row['stt_zone'];
		if ($model->bkg_vehicle_type_id != '')
		{
			$sqlCab	 = "SELECT vct_id FROM svc_class_vhc_cat,vehicle_category WHERE  scv_vct_id = vct_id AND scv_id = $model->bkg_vehicle_type_id";
			$cabType = DBUtil::command($sqlCab)->queryScalar();
		}
		$createDate = $model->bkg_create_date;
		if ($model->bkg_id == null)
		{
			$createDate = null;
		}

		if ($createDate == null)
		{
			$createDate = Filter::getDBDateTime();
		}

		$currDateTime	 = Filter::getDBDateTime();
		$workingHrsDiff	 = Filter::CalcWorkingHour($currDateTime, $model->bkg_pickup_date);

		$sqlParams = [':baseAmount'	 => $model->bkgInvoice->bkg_base_amount,
			':createDate'	 => $model->bkg_create_date,
			':pickupDate'	 => $model->bkg_pickup_date,
			':cabType'		 => $cabType,
			':platform'		 => $model->bkgTrail->bkg_platform,
			':bookingType'	 => $model->bkg_booking_type,
			':fcity'		 => $model->bkg_from_city_id,
			':tcity'		 => $model->bkg_to_city_id,
			':fstate'		 => $fromState,
			':fzones'		 => $fromZones,
			':fregion'		 => $fromRegion,
			':tstate'		 => $toState,
			':tzones'		 => $toZones,
			':tregion'		 => $toRegion,
			':userId'		 => $model->bkgUserInfo->bkg_user_id
		];

		$condMinBooking = "";
		if ($model->bkgUserInfo->bkg_user_id > 0)
		{
			$bCSql			 = "SELECT COUNT(1) FROM booking
					INNER JOIN booking_user ON bui_bkg_id=bkg_id
					WHERE
					bkg_status=6 AND
					bkg_user_id='{$model->bkgUserInfo->bkg_user_id}' AND bkg_active=1";
			$bCRes			 = DBUtil::command($bCSql)->queryScalar();
			$condMinBooking	 = " AND (prm_complete_min < $bCRes OR prm_complete_min IS NULL) ";
			
			$contactId = Users::getContactByUserId($model->bkgUserInfo->bkg_user_id);
			if($contactId > 0)
			{
				$category = ContactPref::model()->find('cpr_ctt_id=:id',['id'=>$contactId])->cpr_category;
				if($category == '')
				{
				  $category = 1;
				}
				$condUserCategory = " AND (FIND_IN_SET($category,prm_usr_cat_type) OR prm_usr_cat_type IS NULL) ";
			}
		}
		else
		{
			$condMinBooking = " AND p.prm_logged_in=0 ";
		}

		$autoOrManual	 = ($promoCode != '') ? "" : " AND prm_applicable_type = 1 ";
		$sql			 = "SELECT prm_id, prm_code, prm_desc, IF(pcn_type IN (1,3),1,0) as rank,
						IF(pcn_type IN (1,3), calculateValue( :baseAmount, `pcn_value_type_cash`, `pcn_value_cash`, `pcn_max_cash`, `pcn_min_cash`),0) as cashAmount,
						IF(pcn_type IN (2,3), calculateValue(  :baseAmount, `pcn_value_type_coins`, `pcn_value_coins`, `pcn_max_coins`, `pcn_min_coins`),0) as coinsAmount
				FROM promos p
				INNER JOIN promo_calculation ON p.prm_id = promo_calculation.pcn_promo_id
				LEFT JOIN promo_entity_filter pef ON p.prm_id = pef.pef_promo_id AND p.prm_active=1 AND pef.pef_active=1
				LEFT JOIN promo_date_filter pdf ON p.prm_id = pdf.pcd_promo_id AND pdf.pcd_active=1
						LEFT JOIN promo_users ON pru_promo_id=prm_id 
						AND pru_ref_type IN (0,1) AND pru_ref_id=:userId AND pru_auto_apply=1
                                        AND (pru_valid_from IS NULL OR pru_valid_from<=NOW())
                                        AND (pru_valid_upto IS NULL OR pru_valid_upto>=NOW())
                                        AND (pru_use_max=0 OR pru_use_max > pru_used_counter)
						WHERE 1 $condMinBooking $condUserCategory AND prm_valid_from <= CURRENT_TIMESTAMP AND prm_valid_upto >= CURRENT_TIMESTAMP
					AND ( prm_pickuptime_before<=$workingHrsDiff OR prm_pickuptime_before IS NULL OR prm_pickuptime_before='')
                                        AND (prm_pickuptime_after>=$workingHrsDiff OR prm_pickuptime_after IS NULL OR prm_pickuptime_after='')
                                        AND (prm_pickupdate_from <= DATE('$model->bkg_pickup_date') OR prm_pickupdate_from IS NULL)
                                        AND (prm_pickupdate_to >= DATE('$model->bkg_pickup_date') OR prm_pickupdate_to IS NULL)
                                        AND (prm_createdate_from <= DATE('{$createDate}') OR prm_createdate_from IS NULL)
                                        AND (prm_createdate_to >= DATE('{$createDate}') OR prm_createdate_to IS NULL)
                                        AND ( (NOW() > DATE_ADD('$createDate', INTERVAL prm_createtime_before HOUR)) OR prm_createtime_before IS NULL OR prm_createtime_before='')
                                        AND ( (NOW() < DATE_ADD('$createDate', INTERVAL prm_createtime_after HOUR)) OR prm_createtime_after IS NULL OR prm_createtime_after='')
						AND ((prm_applicable_user=0 AND (prm_use_max > prm_used_counter OR prm_use_max=0)) OR (prm_applicable_user=1 AND pru_id IS NOT NULL))
                                        AND FIND_IN_SET(:platform, prm_applicable_platform)
                                        AND prm_active=1 $autoOrManual AND (prm_min_base_amount< :baseAmount OR prm_min_base_amount IS NULL)
						AND (pef.pef_area_type_from IS NULL OR (pef.pef_area_type_from=1 AND CONCAT(',', pef.pef_area_from_id, ',') REGEXP :fzones)
                                                        OR (pef.pef_area_type_from=2 AND FIND_IN_SET(:fstate, pef.pef_area_from_id))
                                                        OR (pef.pef_area_type_from=3 AND FIND_IN_SET(:fcity, pef.pef_area_from_id))
						OR (pef.pef_area_type_from=4 AND FIND_IN_SET(:fregion, pef.pef_area_from_id))) AND (pef.pef_area_type_to IS NULL
                                                        OR (pef.pef_area_type_to=1 AND  CONCAT(',', pef.pef_area_to_id, ',') REGEXP :tzones)
                                                        OR (pef.pef_area_type_to=2 AND FIND_IN_SET(:tstate, pef.pef_area_to_id))
                                                        OR (pef.pef_area_type_to=3 AND FIND_IN_SET(:tcity, pef.pef_area_to_id))
						OR (pef.pef_area_type_to=4 AND FIND_IN_SET(:tregion, pef.pef_area_to_id))) AND (pef.pef_area_type IS NULL
                                                        OR (pef.pef_area_type=1 AND (CONCAT(',', pef.pef_area_id, ',') REGEXP :fzones) OR CONCAT(',', pef.pef_area_id, ',') REGEXP :tzones)
                                                        OR (pef.pef_area_type=2 AND (FIND_IN_SET(:fstate, pef.pef_area_id) OR FIND_IN_SET(:tstate, pef.pef_area_id)))
                                                        OR (pef.pef_area_type=3 AND (FIND_IN_SET(:fcity, pef.pef_area_id) OR FIND_IN_SET(:tcity, pef.pef_area_id)))
						OR (pef.pef_area_type=4 AND (FIND_IN_SET(:fregion, pef.pef_area_id) OR FIND_IN_SET(:tregion, pef.pef_area_id)))) 
						AND (pef.pef_booking_type IS NULL OR FIND_IN_SET(:bookingType, pef.pef_booking_type))
                                        AND (pef.pef_cab_type IS NULL OR FIND_IN_SET(:cabType, pef.pef_cab_type))
                                        AND (pdf.pcd_weekdays_create IS NULL OR FIND_IN_SET((WEEKDAY('$createDate')+1),pdf.pcd_weekdays_create))
                                        AND (pdf.pcd_weekdays_pickup IS NULL OR FIND_IN_SET((WEEKDAY('$model->bkg_pickup_date')+1),pdf.pcd_weekdays_pickup))
                                        AND (pdf.pcd_weeks_create IS NULL OR FIND_IN_SET((FLOOR((DAYOFMONTH('$model->bkg_pickup_date') - 1) / 7) + 1), pdf.pcd_weeks_create))
                                        AND (pdf.pcd_weeks_pickup IS NULL OR FIND_IN_SET((FLOOR((DAYOFMONTH('$model->bkg_pickup_date') - 1) / 7) + 1), pdf.pcd_weeks_pickup))
                                        AND (pdf.pcd_months_create IS NULL OR FIND_IN_SET(MONTH('$createDate'),pdf.pcd_months_create))
                                        AND (pdf.pcd_months_pickup IS NULL OR FIND_IN_SET(MONTH('$model->bkg_pickup_date'),pdf.pcd_months_pickup))
                                        AND (pdf.pcd_monthdays_create IS NULL OR FIND_IN_SET(DAY('$createDate'),pdf.pcd_monthdays_create))
                                        AND (pdf.pcd_monthdays_pickup IS NULL OR FIND_IN_SET(DAY('$model->bkg_pickup_date'),pdf.pcd_monthdays_pickup))";

		$sql .= ($promoCode != '') ? " AND p.prm_code='$promoCode'" : "";
		$sql .= " GROUP BY prm_id  ORDER BY rank DESC, cashAmount DESC, coinsAmount DESC";

		return DBUtil::query($sql, DBUtil::SDB(), $sqlParams
//, 20 * 60, CacheDependency::Type_Promo
		);
	}

	public function apply($prm_id, $userId = '')
	{
		$sql = "SET @pickupDate='2019-06-10',@createDate='2019-06-05',@platform='1',@baseAmount=2000,@bookingType=1;
				SELECT prm_id, prm_code, prm_desc, IF(pcn_type IN (1,3),1,0) as rank,
						IF(pcn_type IN (1,3), calculateValue( @baseAmount, `pcn_value_type_cash`, `pcn_value_cash`, `pcn_max_cash`, `pcn_min_cash`),0) as cashAmount,
						IF(pcn_type IN (2,3), calculateValue(  @baseAmount, `pcn_value_type_coins`, `pcn_value_coins`, `pcn_max_coins`, `pcn_min_coins`),0) as coinsAmount
				FROM promos p
				INNER JOIN promo_calculation ON p.prm_id = promo_calculation.pcn_promo_id AND prm_id=$prm_id
				LEFT JOIN promo_users ON pu.pru_promo_id=prm_id AND pu.prm_applicable_user=1
				WHERE prm_applicable_user=0 OR pu.pru_ref_id='$userId'
				ORDER BY rank DESC, cashAmount DESC, coinsAmount DESC";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	/**
	 *
	 * @param integer $promoId
	 * @param integer $userId
	 * @param integer $bkgId
	 * @param string $email
	 * @param integer $phone
	 */
	public static function incrementUsedCounter($promoCode, $userId, $bkgId = null, $email = null, $phone = null)
	{
		$bkgModel = Booking::model()->findByPk($bkgId);
		if (!$bkgModel)
		{
			throw new Exception("Invalid Booking", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$drCodes = self::allApplicableCodes($bkgModel, $promoCode);
		if (isset($drCodes) && $drCodes->getRowCount() > 0)
		{
			$promoData = $drCodes->read();
		}
		else
		{
			throw new Exception("Invalid Promo Code", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}

		if ($userId == null && $email != null && $phone != null)
		{
			$userId = Users::model()->linkUserid($email, $phone);
			if (!$userId)
			{
				throw new Exception('Not any find user id', ReturnSet::ERROR_INVALID_DATA);
			}
		}
	}

	public function incrementCounter($promoId, $userId = '', $bkgId = '', $email = '', $phone = '')
	{
		$promoModel = Promos::model()->findByPk($promoId);
		if (!$promoModel)
		{
			return false;
		}
		if ($userId == '')
		{
			if ($email != '' && $phone != '')
			{
				$userId = Users::model()->linkUserid($email, $phone);
				if (!$userId)
				{
					throw new Exception('Not any find user id');
				}
			}
		}
		if ($userId == '' || $bkgId == '')
		{
			throw new Exception('Please provide user id or booking id');
		}
		if ($promoModel->getRefId($promoModel->prm_id, $userId, $bkgId))
		{
			$promoModel->refresh();
		}
		else
		{
			return false;
		}
		if ($promoModel->prm_use_max != 0)
		{
			if ($promoModel->prm_applicable_nexttrip == 1)
			{
				$res = PromoUsers::getUserAvailability($promoModel->refId, $promoModel->prm_id, $promoModel->refType);
			}
			if ($promoModel->prm_applicable_nexttrip == 0 || $res == 1)
			{
				$promoModel->prm_used_counter = $promoModel->prm_used_counter + 1;
				$promoModel->save();
			}
		}
		if (($promoModel->refId > 0 || $promoModel->refId != '') && ($promoModel->refType > 0 || $promoModel->refType != ''))
		{
			PromoUsers::model()->incrementCounter($promoModel->prm_id, $promoModel->refId);
		}
	}

	public function decrementCounter($promocode, $userid = '', $email = '', $phone = '')
	{
		$promoModel = Promos::model()->getByCode($promocode);
		if ($promoModel->prm_use_max != 0)
		{
			$promoModel->prm_used_counter = $promoModel->prm_used_counter - 1;
			$promoModel->save();
		}
		if ($promoModel->prm_applicable_user == 1)
		{
			if ($userid == '')
			{
				if ($email != '' && $phone != '')
				{
					$userid = Users::model()->linkUserid($email, $phone);
					if (!$userid)
					{
						throw new Exception('Not any find user id');
					}
				}
				else
				{
					throw new Exception('Please provide email address and phone number');
				}
			}
			if ($userid > 0)
			{
				PromoUsers::model()->decrementCounter($promoModel->prm_id, $userid);
			}
		}
	}

	public function saveInfo($calModel, $entityModel, $dateModel)
	{
		$error		 = '';
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($calModel->pcn_value_type_cash != '' || $calModel->pcn_value_type_coins != '')
			{
				if ($calModel->pcn_value_type_cash != '')
				{
					if ($calModel->pcn_value_type_cash == 2 && $calModel->pcn_value_cash <= 0)
					{
						$error = "Cash value field can not be blank or zero";
					}
					if ($calModel->pcn_value_type_cash == 1 && $calModel->pcn_value_cash <= 0)
					{
						$error = "Cash value,max cash,min cash field can not be blank or zero";
					}
				}
				else
				{
					if ($calModel->pcn_type == 3)
					{
						$error = "Please select value type";
					}
					else if ($calModel->pcn_value_cash > 0 || $calModel->pcn_max_cash > 0 || $calModel->pcn_min_cash > 0)
					{
						$error = "Please select value type";
					}
				}
				if ($calModel->pcn_value_type_coins != '')
				{
					if ($calModel->pcn_value_type_coins == 2 && $calModel->pcn_value_coins <= 0)
					{
						$error = "Coins value field can not be blank or zero";
					}
					if ($calModel->pcn_value_type_coins == 1 && $calModel->pcn_value_coins <= 0)
					{
						$error = "Coins value,max coins,min coins field can not be blank or zero";
					}
				}
				else
				{
					if ($calModel->pcn_type == 3)
					{
						$error = "Please select value type";
					}
					else if ($calModel->pcn_value_coins > 0 || $calModel->pcn_max_coins > 0 || $calModel->pcn_min_coins > 0)
					{
						$error = "Please select value type";
					}
				}
			}
			else if ($calModel->pcn_type == 4)
			{
				if ($calModel->pcn_fixed_price <= 0)
				{
					$error = "Fixed price must be greater than 0";
				}
			}
			else
			{
				$error = "Please select value type";
			}

			/* Promo Calculation End */
			if ($error == '')
			{
				$result	 = CActiveForm::validate($this, null, false);
				$result1 = CActiveForm::validate($entityModel, null, false);
				$result2 = CActiveForm::validate($dateModel, null, false);
				$result3 = CActiveForm::validate($calModel, null, false);
				if ($result == '[]' && $result1 == '[]' && $result2 == '[]' && $result3 == '[]')
				{
					$this->prm_created			 = new CDbExpression('NOW()');
					$calModel->pcn_created		 = new CDbExpression('NOW()');
					$entityModel->pef_created	 = new CDbExpression('NOW()');
					$dateModel->pcd_created		 = new CDbExpression('NOW()');

					if ($this->save())
					{
						$calModel->pcn_promo_id		 = $this->prm_id;
						$entityModel->pef_promo_id	 = $this->prm_id;
						$dateModel->pcd_promo_id	 = $this->prm_id;

						if ($calModel->save() && $entityModel->save() && $dateModel->save())
						{
							$success = true;
						}
						else
						{
							throw new Exception('Failed to create Promotion');
						}
					}
					else
					{
						throw new Exception('Failed to create Promotion');
					}
				}
				else
				{
					$error = $result;
				}
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			$error = "Failed to create Promotion";
			DBUtil::rollbackTransaction($transaction);
		}
		return ['success' => $success, 'error' => $error];
	}

	function getRegionJSON()
	{
		$areaArr = Promos::$region;
		$arrJSON = [];
		foreach ($areaArr as $key => $val)
		{
			if ($val != '')
			{
				$arrJSON[] = array("id" => $key, "text" => $val);
			}
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function addLog($oldData, $newData, $remark)
	{
		if ($oldData)
		{
			$getDifference	 = array_diff_assoc($oldData, $newData);
			$dt				 = date('Y-m-d H:i:s');
			$user			 = Yii::app()->user->getId();
			if (is_string($remark))
			{
				$newcomm = CJSON::decode($remark);
			}
			else if (is_array($remark))
			{
				$newcomm = $remark;
			}
			if ($newcomm == false)
			{
				$newcomm = array();
			}
			if (count($getDifference) > 0)
			{
				while (count($newcomm) >= 50)
				{
					array_pop($newcomm);
				}
				array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $getDifference));

				$log = CJSON::encode($newcomm);
				return $log;
			}
		}
		if (!$oldData && $newData)
		{
			$dt		 = date('Y-m-d H:i:s');
			$user	 = Yii::app()->user->getId();
			$newcomm = array();
			array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => ''));
			$log	 = CJSON::encode($newcomm);
			return $log;
		}
		return $remark;
	}

	public function getRefId($promoId = '', $userId = '', $bkgId = '')
	{
		if ($promoId == '')
		{
			$promoId = $this->prm_id;
		}
		if ($userId == '')
		{
			$userId = $this->userId;
		}
		if ($bkgId == '')
		{
			$bkgId = $this->bkgId;
		}
		$result = PromoUsers::getRefType($promoId, $userId, $bkgId);
		if (count($result) > 0)
		{
			$failed = 0;
			foreach ($result as $key => $value)
			{
				$this->refId	 = $value['pru_ref_id'];
				$this->refType	 = $value['pru_ref_type'];
				if (PromoUsers::getUserApplicable($this->refId, $promoId, $this->imEfect, $this->autoApply, $this->prm_applicable_nexttrip, $this->refType))
				{
					break;
				}
				else
				{
					$failed			 += 1;
					$this->refId	 = "";
					$this->refType	 = "";
				}
			}
			if ($failed == count($result))
			{
				return false;
			}
		}
		else
		{
			if ($this->prm_applicable_nexttrip == 0)
			{
				if ($this->prm_applicable_user == 1 || ($this->prm_logged_in == 1 && $this->prm_use_max > 0))
				{
					return false;
				}
			}
			else
			{
				$this->refId	 = $userId;
				$this->refType	 = 0;
			}
		}
		return true;
	}

	public function getPromoList()
	{
		$sql		 = "SELECT prm_id,prm_code FROM promos WHERE prm_active = 1 AND prm_id IS NOT NULL AND prm_code IS NOT NULL";
		$allPromo	 = DBUtil::queryAll($sql);
		$arrJSON	 = [];
		foreach ($allPromo as $key => $val)
		{
			$arrJSON[] = array("id" => $val['prm_id'], "text" => $val['prm_code']);
		}
		$data = json_encode($arrJSON);
		return $data;
	}

	public function getPromoReportData($type = '')
	{
		$where = " AND bkg_promo1_id > 0";
		if ($this->prm_id != '')
		{
			$where .= " AND bkg_promo1_id IN(" . $this->prm_id . ")";
		}
		if ($this->from_date_create != '' && $this->to_date_create != '')
		{
			$where .= " AND bkg_create_date BETWEEN '{$this->from_date_create} 00:00:00' AND '{$this->to_date_create} 23:59:59'";
		}
		if ($this->from_date_pickup != '' && $this->to_date_pickup != '')
		{
			$where .= " AND bkg_pickup_date BETWEEN '{$this->from_date_pickup} 00:00:00' AND '{$this->to_date_pickup} 23:59:59'";
		}

		if ($this->status != '')
		{
			$where .= " AND (CASE
				WHEN bkg_status = 1 THEN 'Unverified'
				WHEN bkg_status = 2 THEN 'New'
				WHEN bkg_status = 3 THEN 'Assigned'
				WHEN bkg_status = 4 THEN 'Confirmed'
				WHEN bkg_status = 5 THEN 'Allocated'
				WHEN bkg_status = 6 THEN 'Completed'
				WHEN bkg_status = 7 THEN 'Settled'
				WHEN bkg_status = 9 THEN 'Cancelled'
				WHEN bkg_status = 10 THEN 'Unverified Cancelled'
				ELSE 'Quoted' END) LIKE '%" . $this->status . "%' ";
		}

		$sql = "SELECT
				prm_code AS promoCode,
				bkg_id,
				bkg_booking_id AS bookingId,
				CASE WHEN bkg_status = 1 THEN 'Unverified' WHEN bkg_status = 2 THEN 'New' WHEN bkg_status = 3 THEN 'Assigned' WHEN bkg_status = 4 THEN 'Confirmed' WHEN bkg_status = 5 THEN 'Allocated' WHEN bkg_status = 6 THEN 'Completed' WHEN bkg_status = 7 THEN 'Settled' WHEN bkg_status = 9 THEN 'Cancelled' WHEN bkg_status = 10 THEN 'Unverified Cancelled' ELSE 'Quoted'
				END AS
				status,
				DATE_FORMAT(bkg_pickup_date, '%d/%m/%Y %h:%i %p') AS pickupDate,
				DATE_FORMAT(bkg_create_date, '%d/%m/%Y %h:%i %p') AS createDate,
				IF(temp.blg_user_type=1, CONCAT(users.usr_name,' ',usr_lname),admins.gozen) AS UserName,
				IF(temp.blg_user_type=1,'Users','Admin') AS UserType
				FROM
				booking
				INNER JOIN booking_invoice ON booking.bkg_id = booking_invoice.biv_bkg_id AND bkg_status IN(1,2,3,5,6,7,9,10,15)
				INNER JOIN
				(
				 SELECT blg_user_type,blg_user_id,blg_booking_id  FROM `booking_log` WHERE blg_ref_id = 211 AND blg_event_id= 100  AND blg_user_id IS NOT NULL AND blg_active=1
				) temp ON temp.blg_booking_id=booking.bkg_id
				LEFT JOIN users ON users.user_id  = temp.blg_user_id AND temp.blg_user_type=1
				LEFT JOIN admins ON admins.adm_id = temp.blg_user_id AND temp.blg_user_type=4
				INNER JOIN promos ON promos.prm_id = booking_invoice.bkg_promo1_id
				WHERE
				bkg_active = 1 $where";
		if ($type == 'Command')
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['pickupDate', 'createDate', 'promoCode', 'bookingId'],
					'defaultOrder'	 => 'bkg_id DESC'],
				'pagination'	 => ['pageSize' => 250],
			]);

			return $dataprovider;
		}
		else
		{
			$recordset = DBUtil::query($sql, DBUtil::SDB());
			return $recordset;
		}
	}

	public function getPromoWiseBookingCount()
	{
		$where = " AND bkg_promo1_id > 0";
		if ($this->prm_id != '')
		{
			$where .= " AND bkg_promo1_id IN(" . $this->prm_id . ")";
		}
		if ($this->from_date_create != '' && $this->to_date_create != '')
		{
			$where .= " AND bkg_create_date BETWEEN '{$this->from_date_create} 00:00:00' AND '{$this->to_date_create} 23:59:59'";
		}
		if ($this->from_date_pickup != '' && $this->to_date_pickup != '')
		{
			$where .= " AND bkg_pickup_date BETWEEN '{$this->from_date_pickup} 00:00:00' AND '{$this->to_date_pickup} 23:59:59'";
		}
		$sql = "SELECT
					prm_code AS promoCode,
					SUM(IF(bkg_agent_id IS NULL,1,0)) AS BtoC,
					SUM(IF(bkg_agent_id IS NOT NULL,1,0)) AS BtoB,
					SUM(IF(bkg_status IN(2,3,5,6,7,9) AND bkg_reconfirm_flag = 1,1,0)) AS ConfirmTot,
					count(1) as CountTot
				FROM
					booking
				INNER JOIN booking_invoice ON booking.bkg_id = booking_invoice.biv_bkg_id AND bkg_status IN(1,2,3,5,6,7,9,10,15)
				INNER JOIN promos ON promos.prm_id = booking_invoice.bkg_promo1_id
				WHERE
					bkg_active = 1
					$where
				GROUP BY
					bkg_promo1_id ORDER BY CountTot DESC";
		return DBUtil::queryAll($sql);
	}

	public function getApplicableCodesFromQoute(Quote $quote, $platform)
	{
		$this->pickupDate	 = $quote->pickupDate;
		$this->createDate	 = Filter::getDBDateTime();
		$this->autoApply	 = 1;
		$this->cabType		 = $quote->cabType;
		$this->fromCityId	 = $quote->routes[0]->brt_from_city_id;
		$this->platform		 = $platform;
		$this->totalAmount	 = $quote->routeRates->baseAmount;
		$result				 = $this->getAutoApplyPromoCodesWithoutCashBask();
	}

	public function getPromoDetailsByCode($promoCode, $agtId, $totPrice, $platform)
	{
		$sql = "select prm_id, pcn_value_cash, prm_use_max, prm_used_counter, prm_desc,
				IF(
					pcn_type = 1,
					IF(
						pcn_value_type_cash = 1,
						IF(
							(pcn_value_cash * $totPrice / 100) > pcn_max_cash,
							IF(pcn_max_cash > 0,pcn_max_cash,(pcn_value_cash * $totPrice / 100)),
							(pcn_value_cash * $totPrice / 100)
						),
						pcn_value_cash
					),
					0
				) AS amount
				from promos
				INNER JOIN promo_calculation ON promos.prm_id = promo_calculation.pcn_promo_id
				LEFT JOIN gift_card_partner prp ON promos.prm_id=prp.prp_promo_id AND prp.prp_partner_id='$agtId' AND prm_applicable_user = 1
               where prm_code = '$promoCode' AND prm_active =1 AND prm_user_type = 2
			   AND (prm_min_base_amount< $totPrice OR prm_min_base_amount IS NULL)
			   AND (prm_use_max > prm_used_counter OR prm_use_max=0)
			   AND find_in_set(" . $platform . ",prm_applicable_platform)
			   AND prm_valid_from <= CURRENT_TIMESTAMP
			   AND prm_valid_upto >= CURRENT_TIMESTAMP AND (prp.prp_id IS NOT NULL OR prm_applicable_user = 0)";
		return DBUtil::queryRow($sql);
	}

	public static function usePromo($bkgpcode, $bkgId)
	{
		$model		 = Booking::model()->findByPk($bkgId);
		$returnSet	 = new ReturnSet();
		$promoModel	 = Promos::model()->getByCode($bkgpcode);

		if (!$promoModel)
		{
			$returnSet->setStatus(false);
			$returnSet->addError("Invalid promo code.");
			return $returnSet;
		}
		$promoModel->promoCode	 = $model->bkgInvoice->bkg_promo1_code;
		$promoModel->totalAmount = $model->bkgInvoice->bkg_base_amount;
		$promoModel->createDate	 = $model->bkg_create_date;
		$promoModel->pickupDate	 = $model->bkg_pickup_date;
		$promoModel->fromCityId	 = $model->bkg_from_city_id;
		$promoModel->toCityId	 = $model->bkg_to_city_id;
		$promoModel->userId		 = $model->bkgUserInfo->bkg_user_id;
		$promoModel->platform	 = $model->bkgTrail->bkg_platform;
		$promoModel->carType	 = $model->bkg_vehicle_type_id;
		$promoModel->bookingType = $model->bkg_booking_type;
		$promoModel->noOfSeat	 = $model->bkgAddInfo->bkg_no_person;
		$promoModel->bkgId		 = $model->bkg_id;
		$promoModel->email		 = '';
		$promoModel->phone		 = '';
		$promoModel->imEfect	 = 1;
		$discountArr			 = $promoModel->applyPromoCode();
		if ($discountArr == false)
		{
			$returnSet->setStatus(false);
			$returnSet->addError("Invalid promo code.");
			return $returnSet;
		}
		if ($discountArr['cash'] > 0 && $discountArr['cash'] >= $model->bkgInvoice->bkg_due_amount)
		{
			$returnSet->setStatus(false);
			$returnSet->addError("Discount can not exceed due amount.");
			return $returnSet;
		}
		$app		 = ($model->bkgTrail->bkg_platform == 3) ? true : false;
		$promoRule	 = Promos::model()->getExpTimeAdvPromo($model->bkg_create_date, $model->bookingRoutes[0]->brt_pickup_datetime, $app);
		if ($discountArr['prm_activate_on'] == 1 && $promoRule == 0)
		{
			$returnSet->setStatus(false);
			$returnSet->addError("Invalid promo code.");
			return $returnSet;
		}
		if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
		{
			$discountArr['cash']	 = 0;
			$discountArr['coins']	 = 0;
		}
		$message = 'Promo ' . $model->bkgInvoice->bkg_promo1_code . ' applied successfully.';
		if ($discountArr['pcn_type'] == 1 && $discountArr['prm_activate_on'] == 1)
		{
			$message .= " You will get discount worth ₹" . $discountArr["cash"] . " when you make payment.";
		}
		if ($discountArr['pcn_type'] == 2)
		{
			$message .= "You got Gozo Coins worth ₹" . $discountArr['coins'] . ". You may redeem these Gozo Coins against your future bookings with us.";
		}
		if ($discountArr['pcn_type'] == 3)
		{
			$message .= " You will get discount worth ₹" . $discountArr['cash'] . " and Gozo Coins worth ₹" . $discountArr['coins'] . ".* You may redeem these Gozo Coins against your future bookings with us.";
		}
		if ($model->bkgTrail->bkg_platform != 3 && ($discountArr['pcn_type'] == 2 || $discountArr['pcn_type'] == 3))
		{
			$message .= "*<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
		}
		if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
		{
			$message .= " You will be benefited on your next trip.";
		}

		$model->bkgInvoice->bkg_discount_amount			 = $discountArr['cash'];
		$model->bkgInvoice->bkg_promo1_code				 = $bkgpcode;
		$model->bkgInvoice->bkg_promo1_id				 = $discountArr['id'];
		$model->bkgInvoice->populateAmount(true, false, true, false, $model->bkg_agent_id);
		$model->bkgUserInfo->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
		$model->bkgInvoice->bkg_promo2_amt				 = $discountArr['cash'];
		if ($model->save() && $model->bkgUserInfo->save() && $model->bkgInvoice->save())
		{
			$msgLog				 = ($discountArr['prm_activate_on'] == 1) ? 'Promo ' . $bkgpcode . ' applied successfully .(not confirmed)' : 'Promo ' . $bkgpcode . ' used successfully.(confirmed)';
			$userInfo			 = UserInfo::model();
			$userInfo->userType	 = UserInfo::TYPE_CONSUMER;
			$userInfo->userId	 = $model->bkgUserInfo->bkg_user_id;
			BookingLog::model()->createLog($model->bkg_id, $msgLog, $userInfo, BookingLog::BOOKING_PROMO, false, ['blg_ref_id' => BookingLog::REF_PROMO_APPLIED]);

			$returnSet->setStatus(true);
			$returnSet->setData([
				'promoCode'			 => $bkgpcode,
				'promoId'			 => $discountArr['id'],
				'promoType'			 => $discountArr['pcn_type'],
				'promoDiscount'		 => $discountArr['cash'],
				'message'			 => $message,
				'gst'				 => $model->bkgInvoice->bkg_service_tax,
				'da'				 => $model->bkgInvoice->bkg_driver_allowance_amount,
				'dueAmt'			 => $model->bkgInvoice->bkg_due_amount,
				'totAmt'			 => $model->bkgInvoice->bkg_total_amount,
				'baseAmt'			 => $model->bkgInvoice->bkg_base_amount,
				'minPay'			 => $model->bkgInvoice->calculateMinPayment(),
				'isDiscAfterPayment' => ($discountArr['prm_activate_on'] == 1) ? true : false,
				'promoDesc'			 => $promoModel->prm_desc,
			]);
			return $returnSet;
		}
		$returnSet->setStatus(false);
		$returnSet->addError("Invalid promo code.");
		return $returnSet;
	}

	public function getRegion($id = 0)
	{
		return self::$region;
	}

	/**
	 *
	 * @param Booking $model
	 * @return Array
	 */
	public static function getPromoDetails($model)
	{
		//$promoData			 = Promos::allApplicableCodes($model->bkgInvoice->bkg_base_amount, $model->bkg_pickup_date, $model->bkg_pickup_date, $model->bkg_vehicle_type_id, 3, $model->bkg_booking_type, $model->bkg_from_city_id, $model->bkg_to_city_id);
		//$isPromoApplicable	 = BookingSub::model()->getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 1);
		$promoData			 = Promos::allApplicableCodes($model);
		$isPromoApplicable	 = Route::getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 1);
		if ($promoData->getRowCount() > 0 && $isPromoApplicable)
		{
			$promoRes	 = array();
			$ctr		 = 0;
			while ($val		 = $promoData->read())
			{
				$promoRes[$ctr]['prm_id']	 = $val['prm_id'];
				$promoRes[$ctr]['prm_code']	 = $val['prm_code'];
				$promoRes[$ctr]['prm_desc']	 = $val['prm_desc'];
				$ctr++;
			}
		}
		return $promoRes;
	}

	/* @deprecated */

	public static function getActivePromoCredits($model)
	{
		$sql					 = "SELECT booking_user.bkg_user_id,booking_trail.bkg_platform FROM booking JOIN booking_user ON booking_user.bui_bkg_id=booking.bkg_id JOIN booking_trail ON booking_trail.btr_bkg_id=booking.bkg_id WHERE booking.bkg_id =" . $model->bkg_id;
		$result					 = DBUtil::queryRow($sql, DBUtil::SDB());
		$promoModel				 = new Promos();
		$promoModel->promoCode	 = '';
		$promoModel->totalAmount = $model->bkgInvoice->bkg_base_amount;
		$promoModel->createDate	 = $model->bkg_create_date;
		$promoModel->pickupDate	 = $model->bkg_pickup_date;
		$promoModel->fromCityId	 = $model->bkg_from_city_id;
		$promoModel->toCityId	 = $model->bkg_to_city_id;
		$promoModel->userId		 = $result['bkg_user_id'];
		$promoModel->platform	 = $result['bkg_platform'] | 3;
		$promoModel->carType	 = $model->bkg_vehicle_type_id;
		$promoModel->bookingType = $model->bkg_booking_type;
		$promoModel->bkgId		 = $model->bkg_id;
		$promoCodes				 = $promoModel->getApplicableCodes();

		return ["prpmoCodes" => $promoCodes, "userDetails" => $result];
	}

	/**
	 *
	 * @param integer $bkgId
	 * @param string $promoCode
	 * @param array $userInfo
	 * @return ReturnSet
	 * @throws Exception
	 */
	public static function applyPromotions($bkgId, $promoCode, $userInfo)
	{
		$returnSet = new ReturnSet();
		try
		{
			$model		 = Booking::model()->findByPk($bkgId);
			$promoModel	 = Promos::model()->getByCode($promoCode);
			if (!$promoModel)
			{
				throw new Exception("Invalid promo code.", ReturnSet::ERROR_INVALID_DATA);
			}


			$promoModel->promoCode	 = $model->bkgInvoice->bkg_promo1_code;
			$promoModel->totalAmount = $model->bkgInvoice->bkg_base_amount;
			$promoModel->createDate	 = $model->bkg_create_date;
			$promoModel->pickupDate	 = $model->bkg_pickup_date;
			$promoModel->fromCityId	 = $model->bkg_from_city_id;
			$promoModel->toCityId	 = $model->bkg_to_city_id;
			$promoModel->userId		 = $model->bkgUserInfo->bkg_user_id;
			$promoModel->platform	 = $model->bkgTrail->bkg_platform;
			$promoModel->carType	 = $model->bkg_vehicle_type_id;
			$promoModel->bookingType = $model->bkg_booking_type;
			$promoModel->noOfSeat	 = $model->bkgAddInfo->bkg_no_person;
			$promoModel->bkgId		 = $model->bkg_id;
			$promoModel->email		 = '';
			$promoModel->phone		 = '';
			$promoModel->imEfect	 = 1;
			$discountArr			 = $promoModel->applyPromoCode();
			if ($discountArr == false)
			{
				throw new Exception("Invalid promo code.", ReturnSet::ERROR_INVALID_DATA);
			}
			if ($discountArr['cash'] > 0 && $discountArr['cash'] >= $model->bkgInvoice->bkg_due_amount)
			{
				throw new Exception("Discount can not exceed due amount.", ReturnSet::ERROR_INVALID_DATA);
			}
			$app		 = ($model->bkgTrail->bkg_platform == 3) ? true : false;
			$promoRule	 = Promos::model()->getExpTimeAdvPromo($model->bkg_create_date, $model->bookingRoutes[0]->brt_pickup_datetime, $app);
			if ($discountArr['prm_activate_on'] == 1 && $promoRule == 0)
			{
				throw new Exception("Invalid promo code.", ReturnSet::ERROR_INVALID_DATA);
			}
			if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
			{
				$discountArr['cash']	 = 0;
				$discountArr['coins']	 = 0;
			}

			$message = ($discountArr['prm_activate_on'] == 1) ? 'Promo ' . $promoCode . ' applied successfully .(not confirmed)' : 'Promo ' . $promoCode . ' used successfully.(confirmed)';

			//$message = 'Promo ' . $model->bkgInvoice->bkg_promo1_code . ' applied successfully.';

			if ($discountArr['pcn_type'] == 1 && $discountArr['prm_activate_on'] == 1)
			{
				$message .= " You will get discount worth ₹" . $discountArr["cash"] . " when you make payment.";
			}
			if ($discountArr['pcn_type'] == 2)
			{
				$message .= "You got Gozo Coins worth ₹" . $discountArr['coins'] . ". You may redeem these Gozo Coins against your future bookings with us.";
			}
			if ($discountArr['pcn_type'] == 3)
			{
				$message .= " You will get discount worth ₹" . $discountArr['cash'] . " and Gozo Coins worth ₹" . $discountArr['coins'] . ".* You may redeem these Gozo Coins against your future bookings with us.";
			}
			if ($model->bkgTrail->bkg_platform != 3 && ($discountArr['pcn_type'] == 2 || $discountArr['pcn_type'] == 3))
			{
				$message .= "*<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
			}
			if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
			{
				$message .= " You will be benefited on your next trip.";
			}

			$model->bkgInvoice->bkg_promo1_code		 = $promoCode;
			$model->bkgInvoice->bkg_discount_amount	 = $discountArr['cash'];
			$model->bkgInvoice->bkg_promo1_id		 = $discountArr['id'];
			$model->bkgInvoice->populateAmount(true, false, true, false, $model->bkg_agent_id);
			$model->bkgInvoice->bkg_promo2_amt		 = $discountArr['cash'];
			$model->bkgInvoice->calculateConvenienceFee(0);
			$model->bkgInvoice->calculateTotal();

			$model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');

			if ($model->bkgUserInfo->save() && $model->bkgInvoice->save())
			{
				$msgLog	 = ($discountArr['prm_activate_on'] == 1) ? 'Promo ' . $promoCode . ' applied successfully .(not confirmed)' : 'Promo ' . $promoCode . ' used successfully.(confirmed)';
				BookingLog::model()->createLog($model->bkg_id, $msgLog, $userInfo, BookingLog::BOOKING_PROMO, false, ['blg_ref_id' => BookingLog::REF_PROMO_APPLIED]);
				$returnSet->setStatus(true);
				$data	 = ['bookingModel'	 => $model,
					'promoModel'	 => $promoModel,
					'discountArr'	 => $discountArr
				];
				$returnSet->setData($data);
				$returnSet->setMessage($message);
			}
			else
			{
				throw new Exception("Invalid promo code.", ReturnSet::ERROR_INVALID_DATA);
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 *
	 * @param integer $bkgId
	 * @param string $promoCode
	 * @param array $userInfo
	 * @return ReturnSet
	 * @throws Exception
	 */
	public static function removePromotions($bkgId, $promoCode, $userInfo)
	{
		$returnSet = new ReturnSet();
		try
		{
			$model		 = Booking::model()->findByPk($bkgId);
			$promoModel	 = Promos::model()->getByCode($promoCode);
			if (!$promoModel)
			{
				throw new Exception("Invalid promo code.", ReturnSet::ERROR_INVALID_DATA);
			}

			$promoModel->totalAmount = $model->bkgInvoice->bkg_base_amount;
			$promoModel->createDate	 = $model->bkg_create_date;
			$promoModel->pickupDate	 = $model->bkg_pickup_date;
			$promoModel->fromCityId	 = $model->bkg_from_city_id;
			$promoModel->toCityId	 = $model->bkg_to_city_id;
			$promoModel->userId		 = $model->bkgUserInfo->bkg_user_id;
			$promoModel->platform	 = $model->bkgTrail->bkg_platform;
			$promoModel->carType	 = $model->bkg_vehicle_type_id;
			$promoModel->bookingType = $model->bkg_booking_type;
			$promoModel->noOfSeat	 = $model->bkgAddInfo->bkg_no_person;
			$promoModel->bkgId		 = $model->bkg_id;
			$promoModel->email		 = '';
			$promoModel->phone		 = '';
			$promoModel->imEfect	 = '';

			$discountArr = $promoModel->applyPromoCode();

			if ($discountArr != false)
			{
				if ($discountArr['pcn_type'] == 2)
				{
					$discountArr['cash'] = 0;
				}
				if ($discountArr['prm_activate_on'] == 1)
				{
					$discountArr['cash'] = 0;
				}
				if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
				{
					$discountArr['cash']	 = 0;
					$discountArr['coins']	 = 0;
				}

				$model->bkgInvoice->bkg_promo1_code		 = NULL;
				$model->bkgInvoice->bkg_discount_amount	 = 0;
				$model->bkgInvoice->bkg_promo1_id		 = 0;
				$model->bkgInvoice->populateAmount(true, false, true, false, $model->bkg_agent_id);
				$model->bkgInvoice->bkg_promo1_amt		 = '0';
				$model->bkgInvoice->calculateConvenienceFee(0);
				$model->bkgInvoice->calculateTotal();

				$model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');

				if ($model->bkgInvoice->save() && $model->bkgUserInfo->save())
				{
					$params['blg_ref_id'] = BookingLog::REF_PROMO_REMOVED;
					BookingLog::model()->createLog($model->bkg_id, "Promo '$promoCode' removed successfully.", $userInfo, BookingLog::BOOKING_PROMO, false, $params);
				}
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}

		return $returnSet;
	}

	/**
	 *
	 * @param Booking $bkgModel
	 * @param string $code
	 * @throws Exception
	 */
	public static function validateCode($bkgModel, $promoCode)
	{
		try
		{
			if ($promoCode == null)
			{
				return false;
			}
			$drCodes = self::allApplicableCodes($bkgModel, $promoCode);
			if (isset($drCodes) && $drCodes->getRowCount() > 0)
			{
				$promoData = $drCodes->read();
			}
			else
			{
				throw new Exception("Invalid Promo Code", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$model = self::model()->findByPk($promoData['prm_id']);
			#$bkgModel->bkgInvoice->bkg_promo1_id = $promoData['prm_id'];
			$model->validateUser($bkgModel);
		}
		catch (Exception $e)
		{
			ReturnSet::setException($e);
			$model = false;
		}

		return $model;
	}

	/**
	 *
	 * @param Booking $bkgModel
	 * @return boolean|int
	 */
	public function validateUser(Booking $bkgModel)
	{
		if ($this->prm_logged_in == 1 && ((UserInfo::getUserType() != UserInfo::TYPE_CONSUMER && UserInfo::getUserType() != UserInfo::TYPE_ADMIN) || UserInfo::getUserId() == 0  ))
		{
			$this->addError("prm_logged_in", "User not logged in");
		}
		if($this->prm_usr_cat_type!='')
		{
			$arrAllowedUsrCat = explode(",", $this->prm_usr_cat_type);
			$contactId = Users::getContactByUserId($bkgModel->bkgUserInfo->bkg_user_id);
			if($contactId>0)
			{
				$contactUsrCategory = ContactPref::model()->find('cpr_ctt_id=:ctt_id',['ctt_id'=>$contactId])->cpr_category;
				$label = "";
				if($contactUsrCategory=='')
				{
					$contactUsrCategory = 1;
				}
				if(!in_array($contactUsrCategory, $arrAllowedUsrCat))
				{
					foreach ($arrAllowedUsrCat as $key=>$value)
					{
						if($key > 0)
						{
							$label.="/";
						}
						$label .= UserCategoryMaster::model()->findByPk(trim($value))->ucm_label;
					}
					$this->addError("prm_usr_cat_type", "Promo applicable to {$label}  users only");
				}
			}
		}

		if ($this->prm_applicable_user == 0)
		{
			goto end;
		}

		if (!$bkgModel->bkgUserInfo->bkg_user_id)
		{
			$this->addError("prm_applicable_user", "User not linked to booking");
		}

		#$res = PromoUsers::getUserAvailability($bkgModel->bkgUserInfo->bkg_user_id, $bkgModel->bkgInvoice->bkg_promo1_id, 0);
		$res = PromoUsers::getUserAvailability($bkgModel->bkgUserInfo->bkg_user_id, $this->prm_id, 0);

		if ($res == 0)
		{
			//PromoUsers::addUser($this->prm_id, $this->userId, 0, 0, date('Y-m-d') . " 00:00:00", date('Y-m-d', strtotime('+1 year')) . " 00:00:00", 1);
			$this->addError("prm_applicable_user", "Promo applicable to selected user only");
		}

		end:
		return $this;
	}

	/**
	 *
	 * @param Booking $model
	 * @return Booking
	 * @throws Exception
	 */
	public static function removeCode($model)
	{
		$model->bkgInvoice->removePromo();
		return $model;
	}

	/**
	 *
	 * @param integer $bookingId
	 * @param string $promoCode
	 * @param integer $eventType
	 * @return Booking
	 * @throws Exception
	 */
	public static function applyRemove($bookingId, $promoCode, $gozoCoins)
	{

		$model = Booking::model()->findByPk($bookingId);
		if (!$model)
		{
			throw new Exception("Invalid Booking ", ReturnSet::ERROR_INVALID_DATA);
		}
		if ($promoCode == '')
		{
			$model = Promos::removeCode($model);
			goto skipPromo;
		}
		$model = BookingInvoice::validatePromo($model, $promoCode);
		skipPromo:

		if (UserInfo::getUser()->isGuest || $gozoCoins == 0 || $gozoCoins == '')
		{
			goto skipCoins;
		}
		$invoiceModel		 = UserCredits::validateCoins($model, $gozoCoins);
		$model->bkgInvoice	 = $invoiceModel;
		skipCoins:
		return $model;
	}

	/**
	 *
	 * @param integer $eventType
	 * @param Booking $model
	 * @param integer $gozoCoins
	 * @return string
	 */
	public static function getMessage($eventType, $model = null, $gozoCoins = 0)
	{
		switch ($eventType)
		{
			case 1:
				$message = Promos::getDiscountMessage($model->bkgInvoice, $model->bkgInvoice->bivPromos);
				break;
			case 2:
				$message = "Promo removed successfully.";
				break;
			case 3:
				$message = "$gozoCoins Gozo coins applied successfully.";
				break;
			case 4:
				$message = "Gozo coins removed successfully.";
				break;
		}
		return $message;
	}	
}
