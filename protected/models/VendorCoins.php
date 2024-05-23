<?php

/**
 * This is the model class for table "vendor_coins".
 *
 * The followings are the available columns in table 'vendor_coins':
 * @property integer $vnc_id
 * @property integer $vnc_vnd_id
 * @property integer $vnc_type
 * @property integar $vnc_value
 * @property string $vnc_desc
 * @property integer $vnc_ref_type
 * @property integer $vnc_ref_id
 * @property integer $vnc_type_value
 * @property integer $vnc_active
 * @property integer $vnc_user_id
 * @property integer $vnc_user_type
 * @property datetime $vnc_created_at
 * @property datetime $vnc_modified_at
 */
class VendorCoins extends CActiveRecord
{

	// Type
	const TYPE_RATING			 = 1;
	const TYPE_DRIVER_ON_TIME	 = 2;
	const TYPE_GOZONOW		 = 3;
	const TYPE_PENALTY		 = 4;
	// RefType
	const REF_BOOKING			 = 1;
	const REF_ACCOUNTS		 = 2;

	public $from_date, $to_date, $groupBy;

	const vncType		 = [1 => "Rating", 2 => "Driver On time", 3 => "GozoNow", 4 => "Penalty"];
	const vncRefType	 = [1 => "Booking", 2 => "Trip"];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_coins';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vnc_vnd_id, vnc_type, vnc_value', 'required'),
			array('vnc_id, vnc_vnd_id, vnc_type, vnc_ref_type, vnc_ref_id, vnc_active, vnc_user_id, vnc_user_type', 'numerical', 'integerOnly' => true),
			array('vnc_value', 'length', 'max' => 250),
			array('vnc_desc,  vnc_created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vnc_id, vnc_vnd_id, vnc_type, vnc_value, vnc_desc, vnc_ref_type, vnc_ref_id, vnc_active, vnc_user_id, vnc_user_type, vnc_created_at, vnc_modified_at', 'safe', 'on' => 'search'),
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
			'vnc_id'			 => 'Vnc',
			'vnc_vnd_id'		 => 'Vnc Vnd',
			'vnc_type'			 => '1=>"rating",2=>"penalty"',
			'vnc_value'			 => 'Vnc Value',
			'vnc_desc'			 => 'Vnc Desc',
			'vnc_ref_type'		 => '1=>"booking",2=>"account"',
			'vnc_ref_id'		 => 'Vnc Ref',
			'vnc_active'		 => '0=>"inactive",1=>"active"',
			'vnc_user_id'		 => 'Vnc User Id',
			'vnc_user_type'		 => 'Vnc User Type',
			'vnc_created_at'	 => 'Vnc Created At',
			'vnc_modified_at'	 => 'Vnc Modified At',
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

		$criteria->compare('vnc_id', $this->vnc_id);
		$criteria->compare('vnc_vnd_id', $this->vnc_vnd_id);
		$criteria->compare('vnc_type', $this->vnc_type);
		$criteria->compare('vnc_value', $this->vnc_value, true);
		$criteria->compare('vnc_desc', $this->vnc_desc, true);
		$criteria->compare('vnc_ref_type', $this->vnc_ref_type);
		$criteria->compare('vnc_ref_id', $this->vnc_ref_id);
		$criteria->compare('vnc_active', $this->vnc_active);
		$criteria->compare('vnc_user_id', $this->vnc_user_id);
		$criteria->compare('vnc_user_type', $this->vnc_user_type);
		$criteria->compare('vnc_created_at', $this->vnc_created_at, true);
		$criteria->compare('vnc_modified_at', $this->vnc_modified_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorCoins the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @deprecated now
	 * calculate coin earn 
	 * @param $vndId 
	 * @param $type 
	 * @param $refId
	 */
	public Static function earnCoin($vndId, $type, $refId = null)
	{
		$success = false;

		if ($vndId > 0 && $type > 0)
		{
			if ($type == VendorCoins::TYPE_RATING)
			{
				$success = self::calcRatingCoins($vndId, $refId);
			}
			elseif ($type == VendorCoins::TYPE_GOZONOW)
			{
				$success = self::calcGNowCoins($vndId, $refId);
			}
		}

		return $success;
	}

	/**
	 * calculate rating coin
	 * @param type $vndId
	 * @param type $bkgId
	 * @return type
	 */
	public static function calcRatingCoins($vndId, $bkgId)
	{

		$earnCoins = self::getConfig('rating');
		if ($earnCoins <= 0 || empty($earnCoins))
		{
			return false;
		}

		$vncType = VendorCoins::TYPE_RATING;
		$refType = VendorCoins::REF_BOOKING;
		$vncDesc = 'Vendor coin added for 5 star rating';

		$result = self::add($vndId, $vncType, $earnCoins, $vncDesc, $refType, $bkgId);
		if (!$result)
		{
			return false;
		}
		return true;
	}

	/**
	 * add coins
	 * @param type $vndId
	 * @param type $bkgId
	 * @param type $earnCoins
	 * @param type $vncDesc
	 * @param type $vncType
	 * @param type $refType
	 * @return type
	 */
	public static function addCoins($vndId, $bkgId, $earnCoins, $vncDesc, $vncType, $refType)
	{
		$result = self::add($vndId, $vncType, $earnCoins, $vncDesc, $refType, $bkgId);
		if (!$result)
		{
			return false;
		}
		return true;
	}

	/**
	 * calculate gnow coin
	 * @param type $vndId
	 * @param type $bkgId
	 * @return type
	 */
	public static function calcGNowCoins($vndId, $bkgId)
	{
		$gnowCoins = self::getConfig('gozonow');

		if ($gnowCoins <= 0 || empty($gnowCoins))
		{
			return false;
		}

		$vncType = VendorCoins::TYPE_GOZONOW;
		$refType = VendorCoins::REF_BOOKING;
		$vncDesc = 'Vendor coin added for serving Gozonow booking';

		$bkgModel			 = Booking::model()->findByPk($bkgId);
		$vendorAmount		 = $bkgModel->bkgInvoice->bkg_vendor_amount;
		$vendorPercentile	 = round(($vendorAmount / 100), 0);
		$earnCoins			 = ($vendorPercentile * $gnowCoins);

		$result = self::add($vndId, $vncType, $earnCoins, $vncDesc, $refType, $bkgId);
		if (!$result)
		{
			return false;
		}
		return true;
	}

	/**
	 * function used for entry in vendorCoins table
	 * @param $vndId
	 * @param $vncType
	 * @param $earnCoin
	 * @param $vncDesc
	 * @param $refType
	 * @param $refId
	 * @return boolean
	 * @throws Exception
	 */
	public static function add($vndId, $vncType, $earnCoin, $vncDesc = null, $refType = null, $refId = null, $penaltyType = null)
	{
		$trans = DBUtil::beginTransaction();
		try
		{
			$model					 = new VendorCoins();
			$model->vnc_vnd_id		 = $vndId;
			$model->vnc_type		 = $vncType;
			$model->vnc_value		 = $earnCoin;
			$model->vnc_desc		 = $vncDesc;
			$model->vnc_ref_type	 = $refType;
			$model->vnc_ref_id		 = $refId;
			$model->vnc_type_value	 = $penaltyType;
			$model->vnc_user_id		 = (UserInfo::getUserId() > 0 ? UserInfo::getUserId() : NULL);
			$model->vnc_user_type	 = (UserInfo::getUserType() > 0 ? UserInfo::getUserType() : NULL);
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			DBUtil::commitTransaction($trans);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($trans);
			throw new Exception($e);
		}
		return $model;
	}

	/**
	 * fetch coin constant from database according to type
	 * @param type $key
	 * @return type
	 */
	public static function getConfig($key = null)
	{
		$coinSettings	 = Config::get('vendor.coin.settings');
		$arrSettings	 = json_decode($coinSettings, true);
		if ($key != null)
		{
			return (isset($arrSettings[$key]) ? $arrSettings[$key] : false);
		}
		return $arrSettings;
	}

	/**
	 * function used for total coin balance for vendor
	 * @param type $vndId
	 * @return type
	 */
	public static function totalCoin($vndId)
	{
		$params = ["vndId" => $vndId];

		$sql	 = "SELECT SUM(vnc_value) as totalCoin FROM `vendor_coins` 
			WHERE vnc_vnd_id =:vndId AND vnc_active =1 AND vnc_value <>0";
		$coins	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $coins;
	}

	/** 	 
	 * @param int $vndId
	 * @return type dataprovider
	 */
	public static function getCoinList($vndId)
	{
		$params			 = ["vndId" => $vndId];
		$sql			 = "SELECT vnc_type,vnc_value,vnc_desc,vnc_ref_type,vnc_ref_id,vnc_created_at FROM vendor_coins WHERE vnc_vnd_id = :vndId AND vnc_active=1";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['vnc_id'], 'defaultOrder' => 'vnc_created_at DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public static function RedeemCoin($actid, $vendorId)
	{
		$totalCoin		 = VendorCoins::totalCoin($vendorId);
		$actmodel		 = account_transactions::model()->findByPk($actid);
		$penaltyAmount	 = $actmodel->act_amount;
		if ($totalCoin > $penaltyAmount)
		{

			#$removepenalty = AccountTransactions::remove($act_id);
		}
	}

	public static function updateCoinDetails()
	{
		$sql	 = "SELECT GROUP_CONCAT(DISTINCT vnc_vnd_id) vndIds FROM vendor_coins 
			WHERE vnc_created_at >= DATE_SUB(NOW(), INTERVAL 3 DAY)";
		$vndIds	 = DBUtil::queryScalar($sql, DBUtil::SDB());
		if ($vndIds)
		{
			$sqlUpd	 = "SELECT vnc_vnd_id, SUM(vnc_value) as totalCoin FROM `vendor_coins` 
				WHERE vnc_active = 1 AND vnc_vnd_id IN ({$vndIds}) GROUP BY vnc_vnd_id";
			$results = DBUtil::query($sqlUpd);
			foreach ($results as $row)
			{
				$vndId		 = $row['vnc_vnd_id'];
				$totalCoins	 = $row['totalCoin'];

				VendorStats::updateCoins($totalCoins, $vndId);
			}
		}
	}

	public static function countByBooking($type, $bookingId)
	{
		$params	 = ["bookingId" => $bookingId, 'type' => $type];
		$sql	 = "SELECT COUNT(vnc_vnd_id) as coinCount FROM vendor_coins 
			WHERE vnc_type = :type AND vnc_ref_type=1 AND vnc_ref_id=:bookingId AND vnc_active =1";
		$count	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $count;
	}

	public function getVendorCoinType()
	{
		$type = [
			1	 => 'Rating',
			2	 => 'On time arrived',
			3	 => 'GozoNow',
			4	 => 'Penalty'
		];
		return $type;
	}

	public static function processCoinForBooking($bkgId)
	{
		$model				 = Booking::model()->findByPk($bkgId);
		$pickupDate			 = $model->bkg_pickup_date;
		$isArrivedForPickup	 = $model->bkgTrack->bkg_arrived_for_pickup;
		$tripArrivedTime	 = $model->bkgTrack->bkg_trip_arrive_time;
		$isRideStart		 = $model->bkgTrack->bkg_ride_start;
		$isRideComplete		 = $model->bkgTrack->bkg_ride_complete;
		$isGozoNow			 = $model->bkgPref->bkg_is_gozonow;
		$gozoAmount			 = $model->bkgInvoice->bkg_gozo_amount;
		$vndAmount			 = $model->bkgBcb->bcb_vendor_amount;
		$bkgAmt				 = $model->bkgInvoice->bkg_total_amount;
		$vndId				 = $model->bkgBcb->bcb_vendor_id;
		$drvId				 = $model->bkgBcb->bcb_driver_id;

		// For Matched Bookings
		$bkgTotalAmt = 0;
		$bookings	 = $model->bkgBcb->bookings;
		if (count($bookings) > 1)
		{
			foreach ($bookings as $booking)
			{
				$bkgTotalAmt += $booking->bkgInvoice->bkg_total_amount;
			}

			$vndAmount = round(($bkgAmt * $vndAmount) / $bkgTotalAmt);
		}

		// Vendor
		$arrVendorRatingCoin		 = self::calculateVendorRatingCoins($bkgId, $gozoAmount, $vndAmount);
		$arrOnTimeTripArrivalCoin	 = self::calculateOnTimeTripArrivalCoins($pickupDate, $tripArrivedTime, $isArrivedForPickup, $isRideStart, $isRideComplete);
		$arrDriverAppUsageCoin		 = self::calculateDriverAppUsageCoins($isRideComplete);
		$arrGozoNowCoin				 = self::calculateGNowCoins($isGozoNow, $gozoAmount);

		// Driver
		$arrDriverRatingCoin = DriverCoins::calculateDriverRatingCoins($bkgId, $gozoAmount, $bkgAmt);

		// Vendor On Time Arrived & 100% Driver App Usage
		$vendorCoinForApp	 = 0;
		$remarks			 = 'For ';
		if ($arrOnTimeTripArrivalCoin && isset($arrOnTimeTripArrivalCoin['vendorCoinForOnTimeTripArrived']))
		{
			$vendorCoinForApp	 += $arrOnTimeTripArrivalCoin['vendorCoinForOnTimeTripArrived'];
			$remarks			 .= $arrOnTimeTripArrivalCoin['remarks'];
		}
		if ($arrDriverAppUsageCoin && isset($arrDriverAppUsageCoin['vendorCoinForDriverAppUsage']))
		{
			$vendorCoinForApp	 += $arrDriverAppUsageCoin['vendorCoinForDriverAppUsage'];
			$remarks			 .= ', ' . $arrDriverAppUsageCoin['remarks'];
		}
		if ($vendorCoinForApp > 0)
		{
			$coinCount = VendorCoins::countByBooking(VendorCoins::TYPE_DRIVER_ON_TIME, $bkgId);
			if (!$coinCount || $coinCount == null || $coinCount <= 0)
			{
				VendorCoins::addCoins($vndId, $bkgId, $vendorCoinForApp, $remarks, VendorCoins::TYPE_DRIVER_ON_TIME, VendorCoins::REF_BOOKING);
			}
		}

		// Vendor GozoNow
		if ($arrGozoNowCoin)
		{
			$vendorCoinForApp += $arrGozoNowCoin['gozoNowCoin'];

			$coinCount = VendorCoins::countByBooking(VendorCoins::TYPE_GOZONOW, $bkgId);
			if (!$coinCount || $coinCount == null || $coinCount <= 0)
			{
				VendorCoins::addCoins($vndId, $bkgId, $arrGozoNowCoin['gozoNowCoin'], $arrGozoNowCoin['remarks'], VendorCoins::TYPE_GOZONOW, VendorCoins::REF_BOOKING);
			}
		}

		// Vendor Rating
		$coinCount = VendorCoins::countByBooking(VendorCoins::TYPE_RATING, $bkgId);
		if (!$coinCount || $coinCount == null || $coinCount <= 0)
		{
			if ($arrVendorRatingCoin)
			{
				$earnVendorCoin = ($arrVendorRatingCoin['vndRatingsCoins'] - $vendorCoinForApp);
				if ($earnVendorCoin > 0)
				{
					$remarks = 'For ' . $arrVendorRatingCoin['remarks'];
					VendorCoins::addCoins($vndId, $bkgId, $earnVendorCoin, $remarks, VendorCoins::TYPE_RATING, VendorCoins::REF_BOOKING);
				}
			}
		}

		// Driver On Time Arrived
		$driverCoinForApp = 0;
		if ($arrOnTimeTripArrivalCoin && $arrOnTimeTripArrivalCoin['driverCoinForOnTimeTripArrived'] > 0)
		{
			$driverCoinForApp += $arrOnTimeTripArrivalCoin['driverCoinForOnTimeTripArrived'];

			$coinCount = DriverCoins::countByBooking(DriverCoins::TYPE_DRIVER_ON_TIME, $bkgId);
			if (!$coinCount || $coinCount == null || $coinCount <= 0)
			{
				$remarks = 'For ' . $arrOnTimeTripArrivalCoin['remarks'];
				DriverCoins::addCoins($drvId, $bkgId, $arrOnTimeTripArrivalCoin['driverCoinForOnTimeTripArrived'], $remarks, DriverCoins::TYPE_DRIVER_ON_TIME, DriverCoins::REF_BOOKING);
			}
		}

		// Driver Rating
		$coinCount = DriverCoins::countByBooking(DriverCoins::TYPE_RATING, $bkgId);
		if (!$coinCount || $coinCount == null || $coinCount <= 0)
		{
			if ($arrDriverRatingCoin)
			{
				$earnDriverCoin = ($arrDriverRatingCoin['drvRatingCoins'] - $driverCoinForApp);
				if ($earnDriverCoin > 0)
				{
					$remarks = 'For ' . $arrDriverRatingCoin['remarks'];
					DriverCoins::addCoins($drvId, $bkgId, $earnDriverCoin, $remarks, DriverCoins::TYPE_RATING, DriverCoins::REF_BOOKING);
				}
			}
		}

		$str = "BkgId: {$bkgId}, OnTimeTripArrived: {$arrOnTimeTripArrivalCoin['vendorCoinForOnTimeTripArrived']}, ForAppUsage: {$arrDriverAppUsageCoin['vendorCoinForDriverAppUsage']}";
		$str .= ", gozoNowCoin: {$arrGozoNowCoin['gozoNowCoin']}, Rating: {$arrVendorRatingCoin['vndRatingsCoins']}, RatingFinal: {$earnVendorCoin}";
		Logger::writeToConsole($str);

		$str = "Driver OnTimeTripArrived: {$arrOnTimeTripArrivalCoin['driverCoinForOnTimeTripArrived']}, Rating: {$arrDriverRatingCoin['drvRatingCoins']}, RatingFinal: {$earnDriverCoin}";
		Logger::writeToConsole($str);
	}

	/**
	 * @param type $isGozoNow
	 * @param type $gozoAmount
	 * @return type
	 */
	public static function calculateGNowCoins($isGozoNow, $gozoAmount)
	{
		if ($isGozoNow == 1)
		{
			$gozoNow	 = round(max(min((($gozoAmount * 20) / 100), 20), 5));
			$msg		 = 'Vendor coins for Gozonow booking';
			return $resultArr	 = ['gozoNowCoin' => $gozoNow, 'remarks' => $msg];
		}
		return false;
	}

	public static function calculateOnTimeTripArrivalCoins($pickupDate, $tripArrivedTime, $isArrivedForPickup, $isRideStart, $isRideComplete)
	{
		$onTimeTripArrived = BookingTrack::checkTripArrivedOnTime($pickupDate, $tripArrivedTime, $isArrivedForPickup);
		if ($onTimeTripArrived)
		{
			#$vendorCoinForOnTimeTripArrived	 = 10;
			$vendorCoinForOnTimeTripArrived	 = 5; // according to AK sir on 21-03-2023
			$driverCoinForOnTimeTripArrived	 = (($isRideStart == 1 && $isRideComplete == 1) ? 5 : 0);
			$msg							 = 'on time arrival for trip';

			return $resultArr = ['vendorCoinForOnTimeTripArrived' => $vendorCoinForOnTimeTripArrived, 'driverCoinForOnTimeTripArrived' => $driverCoinForOnTimeTripArrived, 'remarks' => $msg];
		}
		return false;
	}

	public static function calculateDriverAppUsageCoins($isRideComplete)
	{
		if ($isRideComplete == 1)
		{
			//$vendorCoinForDriverAppUsage = 10;
			$vendorCoinForDriverAppUsage = 5; // according to AK sir on on 21-03-2023
			$msg						 = 'driver app usage';

			return $resultArr = ['vendorCoinForDriverAppUsage' => $vendorCoinForDriverAppUsage, 'remarks' => $msg];
		}
		return false;
	}

	/**
	 * @param type $bkgId
	 * @param type $gozoAmount
	 * @param type $vndAmount
	 * @return type
	 */
	public static function calculateVendorRatingCoins($bkgId, $gozoAmount, $vndAmount)
	{
		$ratingRow = Ratings::getCustRatingbyBookingId($bkgId);
		if ($ratingRow && $ratingRow['rtg_customer_overall'] == 5 && $ratingRow['rtg_customer_driver'] == 5 && $ratingRow['rtg_customer_car'] == 5)
		{
			$coins		 = round(min(max((($gozoAmount * 12) / 100), 80), (($vndAmount * 8) / 100), 400));
			$msg		 = '5 star rating';
			return $resultArr	 = ['vndRatingsCoins' => $coins, 'remarks' => $msg];
		}
		return false;
	}

	/**
	 * 
	 * @param type $id
	 * @param type $type
	 * @return type
	 */
	public static function totalCoinByRefId($id, $type)
	{
		$params = ["id" => $id, "type" => $type];

		$sql = "SELECT SUM(vnc_value) as totalCoin FROM `vendor_coins` WHERE vnc_ref_id =:id AND vnc_ref_type =:type AND vnc_active =1";
		$res = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $res['totalCoin'];
	}

	/**
	 * 
	 * @param type $actId
	 * @param type $totalCoin
	 * @return type
	 */
	public static function checkReedemStatus($actId, $totalCoin)
	{
		$actModel	 = AccountTransactions::model()->findByPk($actId);
		$actAmount	 = $actModel['act_amount'];
		if ($totalCoin < 1)
		{
			$msg = "You don't have any vendor coin to waive off your penalty";
			goto skip;
		}
		$actAmount	 = (int) $actAmount;
		$vndId		 = UserInfo::getEntityId();
		####################### new check ############################
		$row		 = AccountTransactions::getPenaltyDetails($actId, $vndId);

		if (!$row)
		{
			throw new Exception(json_encode("Transaction not valid"), ReturnSet::ERROR_VALIDATION);
		}

		$refType		 = $row["adt_type"];
		$refId			 = $row["adt_trans_ref_id"];
		$penaltyAmount	 = $row['adt_amount'] * -1;
		$transParams	 = $row['adt_addt_params'];
		$remarks		 = $row['act_remarks'];
		$actcratedDate	 = $row['act_created'];

		if ($actcratedDate <= '2022-11-01 00:00:00')
		{
			throw new Exception(json_encode("Unable to waiveOff this penalty "), 1);
		}
		$penaltyTypeArr = json_decode($transParams, true);
		if (isset($penaltyTypeArr["remarks"]))
		{
			$remarks = $penaltyTypeArr["remarks"];
		}
		Logger::trace("Trans Details: " . json_encode($row));
		$originalRemarks = $remarks;
		$penaltyType	 = $penaltyTypeArr["penaltyType"];
		$totalVendorCoin = self::totalCoin($vndId);
		if ($totalVendorCoin <= 0)
		{
			throw new Exception(json_encode("Insufficient vendor coins"), ReturnSet::ERROR_VALIDATION);
		}

		$waiveOffData	 = VendorCoins::maxRedeemablePenalty($refType, $refId, $vndId, $penaltyAmount, $penaltyType);
		$maxWaiveOff	 = min($waiveOffData["maxWaiveOff"], $totalVendorCoin);
		$actual			 = $waiveOffData["actual"];
		$waivedOff		 = $waiveOffData["waivedOff"];

		if ($maxWaiveOff <= 0)
		{

			throw new Exception(json_encode("Maximum penalty already waived off"), ReturnSet::ERROR_VALIDATION);
		}
		####################### new check ############################
		$msg = "Penalty of Rs $maxWaiveOff will be waived off against $maxWaiveOff vendor coins";
		skip:
		return $msg;
	}

	/**
	 * calculate redeem penalty
	 * @param type $transDetailId
	 * @param type $vndId
	 */
	public static function redeemPenalty($transactionId, $vndId)
	{
		$returnSet	 = new ReturnSet();
		$transaction = null;
		try
		{
			Logger::trace("Trans ID: {$transactionId}, VendorId: {$vndId}");
			$row = AccountTransactions::getPenaltyDetails($transactionId, $vndId);

			if (!$row)
			{
				throw new Exception(json_encode("Transaction not valid"), ReturnSet::ERROR_VALIDATION);
			}

			$refType		 = $row["adt_type"];
			$refId			 = $row["adt_trans_ref_id"];
			$penaltyAmount	 = $row['adt_amount'] * -1;
			$transParams	 = $row['adt_addt_params'];
			$remarks		 = $row['act_remarks'];
			$actCreateDate	 = $row['act_created'];
			$actDate		 = $row['act_date'];
			if ($actCreateDate <= '2022-11-01 00:00:00')
			{
				throw new Exception(json_encode("Unable to waiveOff this penalty "), 1);
			}
			$penaltyTypeArr = json_decode($transParams, true);
			if (isset($penaltyTypeArr["remarks"]))
			{
				$remarks = $penaltyTypeArr["remarks"];
			}
			Logger::trace("Trans Details: " . json_encode($row));
			$originalRemarks = $remarks;
			$penaltyType	 = $penaltyTypeArr["penaltyType"];
			$totalVendorCoin = self::totalCoin($vndId);
			if ($totalVendorCoin <= 0)
			{
				throw new Exception(json_encode("Insufficient vendor coins"), ReturnSet::ERROR_VALIDATION);
			}

			$waiveOffData	 = VendorCoins::maxRedeemablePenalty($refType, $refId, $vndId, $penaltyAmount, $penaltyType);
			$actualWaivedOff = $penaltyTypeArr['totalWaivedOff'] | 0;
			$waivedOff		 = max($waiveOffData["waivedOff"], $actualWaivedOff);
			$maxWaiveOff	 = min($waiveOffData["maxWaiveOff"] - $waivedOff, $totalVendorCoin);
			$actual			 = $waiveOffData["actual"];

			if ($maxWaiveOff <= 0)
			{
				throw new Exception(json_encode("Maximum penalty already waived off"), ReturnSet::ERROR_VALIDATION);
			}

			$bookingIds = null;
			if ($refType == 5)
			{
				$bcbmodel	 = BookingCab::model()->findByPk($refId);
				$bookingIds	 = $bcbmodel['bcb_bkg_id1'];
			}
			else if ($refType == 1)
			{
				$bookingIds = $refId;
			}


			$remainingPenaltyAmount				 = $penaltyAmount - $waivedOff - $maxWaiveOff;
			$totalWaivedOff						 = $actual - $remainingPenaltyAmount;
			$balanceRemarks						 = " (Actual: ₹{$actual}, Waived off: ₹{$totalWaivedOff})";
			$remarks							 = "Penalty waived off against $maxWaiveOff vendor coins. " . $balanceRemarks;
			$penaltyTypeArr["actual"]			 = $actual;
			$penaltyTypeArr["totalWaivedOff"]	 = $totalWaivedOff;
			$penaltyTypeArr['relatedActID']		 = $transactionId;
			$transaction						 = DBUtil::beginTransaction();
			$amount								 = $maxWaiveOff * -1;

			$vncType	 = VendorCoins::TYPE_PENALTY;
			$vncDesc	 = "Penalty of ₹$maxWaiveOff waived off. " . $balanceRemarks;
			$type		 = ($refType == 5 ? 2 : $refType);
			$vncModel				 = self::add($vndId, $vncType, $amount, $vncDesc, $type, $refId, $penaltyType);
			$penaltyTypeArr['vncId'] = $vncModel->vnc_id;
			$actModel	 = AccountTransactions::penalizeVendor($refType, $refId, $vndId, $amount, $remarks, $penaltyType, $penaltyTypeArr);
			if (!$actModel)
			{
				throw new Exception("Invalid transaction", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			$actModel->act_date = $actDate;

			$actModel->save();

			if ($bookingIds == null)
			{
				goto skipBookingLog;
			}

			/*			 * $remarks		 = "Penalty of ₹$maxWaiveOff waived off against $maxWaiveOff vendor coins " . $balanceRemarks;
			  $bookingIdsArr	 = explode(",", $bookingIds);
			  foreach ($bookingIdsArr as $bookingId)
			  {
			  BookingLog::model()->createLog($bookingId, $remarks, UserInfo::getInstance(), BookingLog::REDEEMED_VENDOR_COIN);
			  }* */
			skipBookingLog:
			DBUtil::commitTransaction($transaction);
			#modify old ACCOUNT ADDITIONAL PARAM
			AccountTransDetails::modifyAdditionalParam($transactionId, $totalWaivedOff);
			$message = "Penalty of ₹{$maxWaiveOff} waived off successfully";
			$returnSet->setStatus(true);
			$returnSet->setMessage($message);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	/**
	 * show already waived off penalty
	 * @param type $vendorId
	 * @param type $refType
	 * @param type $refId
	 * @param type $penaltyType
	 * @return int
	 */
	public static function getWaivedOffPenalty($vendorId, $refType, $refId, $penaltyType = null)
	{
		$refTypeCoin = ($refType == 5 ? 2 : $refType);
		$params		 = ["refType" => $refTypeCoin, "refId" => $refId, "vndId" => $vendorId];
		$sql		 = "SELECT sum(vnc_value * -1) as total FROM vendor_coins
					WHERE  vnc_active =1 AND vnc_ref_id=:refId AND vnc_ref_type=:refType 
						AND vnc_vnd_id=:vndId AND vnc_type=4 AND vnc_value<0";

		if ($penaltyType != null)
		{
			$sql			 .= " AND vnc_type_value=:pType";
			$params["pType"] = $penaltyType;
		}

		$totalPenalty = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);

		if ($totalPenalty == null)
		{
			$totalPenalty = 0;
		}

		return $totalPenalty;
	}

	/**
	 * return actual penalty, maxRedeemble penalty, previous waived off
	 * @param type $refType
	 * @param type $refId
	 * @param type $vendorId
	 * @param type $currentPenalty
	 * @param type $penaltyType
	 * @return type array
	 */
	public static function maxRedeemablePenalty($refType, $refId, $vendorId, $currentPenalty, $penaltyType)
	{
		$penaltyWaivedOff	 = VendorCoins::getWaivedOffPenalty($vendorId, $refType, $refId, $penaltyType);
		$actualPenalty		 = $currentPenalty;
		$maxWaiveOff		 = max($currentPenalty - $penaltyWaivedOff, 0);
		if ($penaltyType == PenaltyRules::PTYPE_CAB_NO_SHOW)
		{
			$maxWaiveOff = max(round($actualPenalty * 0.5 - $penaltyWaivedOff), 0);
		}
		$result = ["maxWaiveOff" => $maxWaiveOff, "waivedOff" => $penaltyWaivedOff, "actual" => $actualPenalty];
		Logger::trace("VendorCoins::maxRedeemablePenalty: " . json_encode($result));
		return $result;
	}

	public function getCoinDetails()
	{
		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";
		$groupBy	 = $this->groupBy;
		$orderBy	 = $this->groupBy;

		if ($this->groupBy == 'vnd_id')
		{
			$orderBy = 'balance';
		}

		$sql = "SELECT DATE_FORMAT(vnc.vnc_created_at, '%Y-%m-%d') AS date, DATE_FORMAT(vnc.vnc_created_at, '%x-%v') AS week, 
					CONCAT(DATE_FORMAT(vnc_created_at, '%x-%v'), '\n',DATE_FORMAT(MIN(vnc_created_at), '%D %b'),' - ',DATE_FORMAT(MAX(vnc_created_at), '%D %b')) as weekLabel,
					DATE_FORMAT(vnc.vnc_created_at, '%Y-%m') AS month, vnc.vnc_vnd_id, v.vnd_code, v.vnd_name, v.vnd_id,
					vrs.vrs_vnd_overall_rating, vrs.vrs_first_approve_date, 
					COUNT(DISTINCT IF(vnc.vnc_ref_type = 1 AND vnc_value > 0, vnc_ref_id, NULL)) AS cntBkg, 
					COUNT(DISTINCT IF(vnc.vnc_ref_type = 2 AND vnc_value > 0, vnc_ref_id, NULL)) AS cntTrips, 
					SUM(vnc.vnc_value) AS balance, SUM(GREATEST(vnc_value, 0)) AS credited, SUM(LEAST(vnc_value, 0)) AS debited 
				FROM vendor_coins vnc 
				INNER JOIN vendors v ON v.vnd_id = vnc.vnc_vnd_id 
				INNER JOIN vendor_stats vrs ON v.vnd_id = vrs.vrs_vnd_id 
				WHERE vnc.vnc_active = 1 AND vnc.vnc_created_at BETWEEN '{$fromDate}' AND '{$toDate}' 
				GROUP BY {$groupBy}";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['balance', 'credited', 'debited', 'cntBkg'], 'defaultOrder' => "{$orderBy} DESC"],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public static function getTransactionList($vndId, $tripId = null, $dateRangeObj = null, $pageRef = null)
	{
		if ($vndId == null || $vndId == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$nowDateTime = Filter::getDBDateTime();

		$limit = " LIMIT 0,20 ";
		if ($pageRef != null)
		{
			/** @var \Beans\common\PageRef $pageRef */
			$pageNumber	 = $pageRef->pageCount;
			$pageSize	 = $pageRef->pageSize;
			$offset		 = $pageNumber * $pageSize;
			$limit		 = " LIMIT {$offset},{$pageSize} ";
		}
		$dateRange = '';

		$tripQry = '';
		if ($tripId > 0)
		{
			$tripQry = " AND ( bkg.bkg_bcb_id=$tripId OR bcb.bcb_id =$tripId )";
		}
		else if ($dateRangeObj == null || (!$dateRangeObj->fromDate || !$dateRangeObj->toDate))
		{
			$today		 = date("Y-m-d", strtotime($nowDateTime));
			$startDate	 = date("Y-m-d", strtotime($nowDateTime . ' -6 MONTH'));
			$dateRange	 = "  AND (vnc.vnc_modified_at)<= '$today 23:59:59' AND (vnc.vnc_modified_at)>= '$startDate 00:00:00' ";
		}
		else
		{
			$fromDate	 = $dateRangeObj->fromDate;
			$toDate		 = $dateRangeObj->toDate;

			$dateRange = " AND (vnc.vnc_modified_at)<= '$toDate 23:59:59' AND (vnc.vnc_modified_at)>= '$fromDate 00:00:00' ";
		}
		$param		 = ['vndId' => $vndId];
		$sql		 = "SELECT vnc.vnc_id,vnc.vnc_type,vnc.vnc_value,vnc.vnc_desc,vnc.vnc_ref_type,vnc.vnc_ref_id,
					bkg.bkg_id, if(vnc.vnc_ref_type=1,bkg.bkg_bcb_id, bcb.bcb_id) tripId,
					vnc.vnc_user_id,vnc.vnc_user_type,vnc.vnc_created_at,vnc.vnc_modified_at,
					if(vnc_user_type=1,'Consumer', if(vnc_user_type=2,'Self',concat(adm.adm_fname,' ',adm.adm_lname))) doneBy
					FROM `vendor_coins` vnc 
					LEFT JOIN booking bkg ON bkg.bkg_id = vnc.vnc_ref_id AND vnc.vnc_ref_type = 1
					LEFT JOIN booking_cab bcb ON  bcb.bcb_id = vnc.vnc_ref_id AND vnc.vnc_ref_type = 2
					LEFT JOIN admins adm ON adm.adm_id = vnc.vnc_user_id AND vnc.vnc_user_type = 4 
				WHERE `vnc_vnd_id` = :vndId AND vnc_active=1 AND vnc_value <>0 $dateRange $tripQry 
				ORDER BY vnc.vnc_created_at ASC	$limit";
		//	echo $sql;exit;
		$resultSet	 = DBUtil::query($sql, DBUtil::SDB(), $param);
		return $resultSet;
	}

	public static function getTransactionSummary($vndId, $tripId = null, $dateRangeObj = null)
	{
		if ($vndId == null || $vndId == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$nowDateTime = Filter::getDBDateTime();

		$dateRange = '';

		$tripQry = '';
		if ($tripId > 0)
		{
			$tripQry = " AND ( bkg.bkg_bcb_id=$tripId OR bcb.bcb_id =$tripId )";
		}
		else if ($dateRangeObj == null || (!$dateRangeObj->fromDate || !$dateRangeObj->toDate))
		{
			$today		 = date("Y-m-d", strtotime($nowDateTime));
			$startDate	 = date("Y-m-d", strtotime($nowDateTime . ' -6 MONTH'));
			$dateRange	 = "  AND (vnc.vnc_modified_at)<= '$today 23:59:59' AND (vnc.vnc_modified_at)>= '$startDate 00:00:00' ";
		}
		else
		{

			$fromDate	 = $dateRangeObj->fromDate;
			$toDate		 = $dateRangeObj->toDate;

			$dateRange = " AND (vnc.vnc_modified_at)<= '$toDate 23:59:59' AND (vnc.vnc_modified_at)>= '$fromDate 00:00:00' ";
		}
		$param	 = ['vndId' => $vndId];
		$sql	 = "SELECT SUM(vnc.vnc_value)  
					FROM `vendor_coins` vnc 
					LEFT JOIN booking bkg ON bkg.bkg_id = vnc.vnc_ref_id AND vnc.vnc_ref_type = 1
					LEFT JOIN booking_cab bcb ON  bcb.bcb_id = vnc.vnc_ref_id AND vnc.vnc_ref_type = 2
					LEFT JOIN admins adm ON adm.adm_id = vnc.vnc_user_id AND vnc.vnc_user_type = 4 
				WHERE `vnc_vnd_id` = :vndId AND vnc_active=1 AND vnc_value <>0 $dateRange $tripQry 
				GROUP BY vnc_vnd_id";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $result;
	}

}
