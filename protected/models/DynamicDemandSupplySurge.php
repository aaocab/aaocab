<?php

/**
 * This is the model class for table "dynamic_demand_supply_surge".
 *
 * The followings are the available columns in table 'dynamic_demand_supply_surge':
 * @property string $dds_key
 * @property string $dds_key_desc
 * @property string $dds_pickup_date
 * @property integer $dds_area_type
 * @property integer $dds_from_area_id
 * @property integer $dds_to_area_id
 * @property integer $dds_trip_type
 * @property integer $dds_vhc_cat
 * @property integer $dds_bkg_active_count
 * @property integer $dds_bkg_gozo_cancelled_count
 * @property integer $dds_bkg_manual_count
 * @property integer $dds_bkg_critical_count
 * @property integer $dds_bkg_profit_count
 * @property integer $dds_bkg_loss_count
 * @property double $dds_net_margin
 * @property integer $dds_markup
 * @property string $dds_markup_last_updated_at
 * @property integer $dds_apply_markup
 * @property string $dds_stats_last_updated_at
 */
class DynamicDemandSupplySurge extends CActiveRecord
{

	const TYPE_ZONE				 = 1; // Zone
	const TYPE_ZONE_ZONE			 = 2; // Zone-Zone
	#const TYPE_ZONE_STATE			 = 3; // Zone-State
	const TYPE_ZONE_VHC			 = 4; // Zone-VhcCat
	const TYPE_ZONE_VHC_TRIP		 = 5; // Zone-VhcCat-BkgType
	const TYPE_ZONE_ZONE_VHC		 = 6; // Zone-Zone-VhcCat
	const TYPE_ZONE_ZONE_VHC_TRIP	 = 7; // Zone-Zone-VhcCat-BkgType

	#const TYPE_ZONE_STATE_VHC		 = 8; // Zone-State-VhcCat
	#const TYPE_ZONE_STATE_VHC_TRIP = 9; // Zone-State-VhcCat-BkgType

	public $arrZones;
	public $arrStates;
	public $arrVhcCat;
	public $arrBkgTypes;
	public $from_date, $to_date, $fromZone, $toZone, $bkgTypes, $vehicleCategory, $areaType;
	public $refId, $factor, $baseFare, $surgeValue;
	public $activeCountDrop, $activeCount, $profitCountDrop, $profitCount, $lossCountDrop, $lossCount, $netMarginDrop, $netMarginCount, $markupDrop, $markupCount;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dynamic_demand_supply_surge';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dds_key, dds_pickup_date, dds_area_type, dds_from_area_id', 'required'),
			array('dds_area_type, dds_from_area_id, dds_bkg_active_count, dds_bkg_gozo_cancelled_count, dds_bkg_manual_count, dds_bkg_critical_count, dds_bkg_profit_count, dds_bkg_loss_count', 'numerical', 'integerOnly' => true),
			array('dds_net_margin', 'numerical'),
			array('dds_key', 'length', 'max' => 100),
			array('dds_key_desc', 'length', 'max' => 250),
			array('dds_to_area_id, dds_trip_type, dds_vhc_cat, dds_markup, dds_apply_markup, dds_markup_last_updated_at, dds_stats_last_updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dds_key, dds_key_desc, dds_pickup_date, dds_area_type, dds_from_area_id, dds_to_area_id, dds_trip_type, dds_vhc_cat, dds_bkg_active_count, dds_bkg_gozo_cancelled_count, dds_bkg_manual_count, dds_bkg_critical_count, dds_bkg_profit_count, dds_bkg_loss_count, dds_net_margin, dds_markup, dds_markup_last_updated_at, dds_apply_markup, dds_stats_last_updated_at', 'safe', 'on' => 'search'),
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
			'dds_key'						 => 'Dds Key',
			'dds_key_desc'					 => 'Dds Key Details',
			'dds_pickup_date'				 => 'Dds Pickup Date',
			'dds_area_type'					 => '1=>Zone, 2=>Zone-Zone, 3=>Zone-State',
			'dds_from_area_id'				 => 'Dds From Area',
			'dds_to_area_id'				 => 'Dds To Area',
			'dds_trip_type'					 => 'Dds Trip Type',
			'dds_vhc_cat'					 => 'Dds Vhc Cat',
			'dds_bkg_active_count'			 => 'Dds Bkg Active Count',
			'dds_bkg_gozo_cancelled_count'	 => 'Dds Bkg Gozo Cancelled Count',
			'dds_bkg_manual_count'			 => 'Dds Bkg Manual Count',
			'dds_bkg_critical_count'		 => 'Dds Bkg Critical Count',
			'dds_bkg_profit_count'			 => 'Dds Bkg Profit Count',
			'dds_bkg_loss_count'			 => 'Dds Bkg Loss Count',
			'dds_net_margin'				 => 'Dds Net Margin',
			'dds_markup'					 => 'Dds Markup',
			'dds_markup_last_updated_at'	 => 'Dds Markup Last Updated At',
			'dds_apply_markup'				 => 'Dds Apply Markup',
			'dds_stats_last_updated_at'		 => 'Dds Stats Last Updated At',
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

		$criteria->compare('dds_key', $this->dds_key, true);
		$criteria->compare('dds_key_desc', $this->dds_key_desc, true);
		$criteria->compare('dds_pickup_date', $this->dds_pickup_date, true);
		$criteria->compare('dds_area_type', $this->dds_area_type);
		$criteria->compare('dds_from_area_id', $this->dds_from_area_id);
		$criteria->compare('dds_to_area_id', $this->dds_to_area_id);
		$criteria->compare('dds_trip_type', $this->dds_trip_type);
		$criteria->compare('dds_vhc_cat', $this->dds_vhc_cat);
		$criteria->compare('dds_bkg_active_count', $this->dds_bkg_active_count);
		$criteria->compare('dds_bkg_gozo_cancelled_count', $this->dds_bkg_gozo_cancelled_count);
		$criteria->compare('dds_bkg_manual_count', $this->dds_bkg_manual_count);
		$criteria->compare('dds_bkg_critical_count', $this->dds_bkg_critical_count);
		$criteria->compare('dds_bkg_profit_count', $this->dds_bkg_profit_count);
		$criteria->compare('dds_bkg_loss_count', $this->dds_bkg_loss_count);
		$criteria->compare('dds_net_margin', $this->dds_net_margin);
		$criteria->compare('dds_markup', $this->dds_markup);
		$criteria->compare('dds_markup_last_updated_at', $this->dds_markup_last_updated_at, true);
		$criteria->compare('dds_apply_markup', $this->dds_apply_markup);
		$criteria->compare('dds_stats_last_updated_at', $this->dds_stats_last_updated_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DynamicDemandSupplySurge the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getUpdatedRecords($minutes = 30)
	{
		$where		 = "";
		$confirmDate = date("Y-m-d H:i:s", strtotime("-{$minutes} minutes"));

		// Get Zones
		$this->arrZones = Zones::model()->getZoneList1();

		// Get Vehicle Category
		$this->arrVhcCat = VehicleTypes::model()->getCarType();

		// Get Booking Types
		$this->arrBkgTypes = Booking::model()->booking_type;

		// Get Confirm BookingIds
		$confirmBkgIds = BookingTrail::getBookingIdsByConfirmDate($confirmDate);
		if ($confirmBkgIds != null && $confirmBkgIds != '')
		{
			$where .= " OR bkg_id IN ($confirmBkgIds) ";
		}

		// Get Vendor Assigned/Unassigned BookingIds
		$vndUnassignBkgIds = BookingTrail::getBkgIdsByVndAssignUnassignDate($confirmDate);
		if ($vndUnassignBkgIds != null && $vndUnassignBkgIds != '')
		{
			$where .= " OR bkg_id IN ($vndUnassignBkgIds) ";
		}

		// Get Cancelled BookingIds
		$cancelledBkgIds = BookingTrail::getBookingIdsByCancelDate($confirmDate);
		if ($cancelledBkgIds != null && $cancelledBkgIds != '')
		{
			$where .= " OR bkg_id IN ($cancelledBkgIds) ";
		}
		// Getting Bookings
		$sql = "SELECT DATE(bkg.bkg_pickup_date) as date, bkg.bkg_booking_type, svc.scv_vct_id, 
							zc.zct_zon_id fromZoneId, tzc.zct_zon_id toZoneId, c.cty_state_id toStateId,
							COUNT(DISTINCT bkg_id) as cnt
					FROM booking bkg 
					INNER JOIN booking_trail btr ON bkg.bkg_id=btr.btr_bkg_id 
					INNER JOIN zone_cities zc ON zc.zct_cty_id=bkg.bkg_from_city_id AND zc.zct_active=1 
					INNER JOIN zone_cities tzc ON tzc.zct_cty_id=bkg.bkg_to_city_id AND tzc.zct_active=1 
					INNER JOIN cities c ON c.cty_id=bkg.bkg_to_city_id AND c.cty_active=1 
					INNER JOIN svc_class_vhc_cat svc ON svc.scv_id=bkg.bkg_vehicle_type_id AND svc.scv_active=1 
					WHERE bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type IN (1,2,3) AND (bkg_create_date >= '{$confirmDate}' {$where})
					GROUP BY date, bkg_booking_type, scv_vct_id, fromZoneId, toZoneId
					";
		#$sql	 .="WHERE bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type IN (1,2,3) AND bkg_pickup_date >= '2022-12-21 00:00:00'";

		$result = DBUtil::query($sql, DBUtil::SDB());

		return $result;
	}

	public function updateDDSBP($minutes = 30)
	{
		$result = $this->getUpdatedRecords($minutes);
		foreach ($result as $value)
		{
			$pickupDateTime	 = $value['date'];
			$bkgType		 = $value['bkg_booking_type'];
			$vhcCatId		 = $value['scv_vct_id'];
			$fromZoneId		 = $value['fromZoneId'];
			$toZoneId		 = $value['toZoneId'];

			$this->updateBookingCount(self::TYPE_ZONE, $pickupDateTime, $fromZoneId);
			$this->updateBookingCount(self::TYPE_ZONE_ZONE, $pickupDateTime, $fromZoneId, $toZoneId);
			$this->updateBookingCount(self::TYPE_ZONE_VHC, $pickupDateTime, $fromZoneId, "", "", $vhcCatId);
			$this->updateBookingCount(self::TYPE_ZONE_VHC_TRIP, $pickupDateTime, $fromZoneId, "", $bkgType, $vhcCatId);
			$this->updateBookingCount(self::TYPE_ZONE_ZONE_VHC, $pickupDateTime, $fromZoneId, $toZoneId, "", $vhcCatId);
			$this->updateBookingCount(self::TYPE_ZONE_ZONE_VHC_TRIP, $pickupDateTime, $fromZoneId, $toZoneId, $bkgType, $vhcCatId);
		}
	}

	public function create($key, $groupType, $pickupDate, $fromZoneId, $toZoneId = "", $bkgType = "", $vhcCatId = "", $markup = 0, $result = "")
	{
		try
		{
			if ($key == null)
			{
				$arrGroup	 = self::getConditions($groupType, $pickupDate, $fromZoneId, $toZoneId, $bkgType, $vhcCatId);
				$key		 = $arrGroup["key"];
			}

			echo "\r\nKey: " . $key;
			$flgNew	 = false;
			$model	 = DynamicDemandSupplySurge::model()->findByPk($key);
			if (!$model)
			{
				$flgNew	 = true;
				$keyDesc = $this->getKeyDesc($groupType, $fromZoneId, $toZoneId, $bkgType, $vhcCatId);

				$model								 = new DynamicDemandSupplySurge();
				$model->dds_key						 = $key;
				$model->dds_key_desc				 = $keyDesc;
				$model->dds_pickup_date				 = $pickupDate;
				$model->dds_area_type				 = $groupType;
				$model->dds_from_area_id			 = $fromZoneId;
				$model->dds_to_area_id				 = (in_array($groupType, [2, 6, 7]) ? $toZoneId : 0);
				$model->dds_trip_type				 = (in_array($groupType, [5, 7, 9]) ? $bkgType : 0);
				$model->dds_vhc_cat					 = (in_array($groupType, [4, 5, 6, 7, 8, 9]) ? $vhcCatId : 0);
				$model->dds_stats_last_updated_at	 = new CDbExpression('NOW()');
			}

			if ($flgNew || ($result && $result != ''))
			{
				$model->dds_bkg_active_count		 = ((isset($result['bkgActiveCnt']) && $result['bkgActiveCnt'] > 0) ? $result['bkgActiveCnt'] : 0);
				$model->dds_bkg_gozo_cancelled_count = ((isset($result['bkgGozoCancelCnt']) && $result['bkgGozoCancelCnt'] > 0) ? $result['bkgGozoCancelCnt'] : 0);
				$model->dds_bkg_manual_count		 = ((isset($result['bkgManualCnt']) && $result['bkgManualCnt'] > 0) ? $result['bkgManualCnt'] : 0);
				$model->dds_bkg_critical_count		 = ((isset($result['bkgCriticalCnt']) && $result['bkgCriticalCnt'] > 0) ? $result['bkgCriticalCnt'] : 0);
				$model->dds_bkg_profit_count		 = ((isset($result['bkgProfitCnt']) && $result['bkgProfitCnt'] > 0) ? $result['bkgProfitCnt'] : 0);
				$model->dds_bkg_loss_count			 = ((isset($result['bkgLossCnt']) && $result['bkgLossCnt'] > 0) ? $result['bkgLossCnt'] : 0);
				$model->dds_net_margin				 = (!is_null($result['marginAmt']) ? round((($result['marginAmt'] / $result['netBaseAmt']) * 100), 2) : $result['marginAmt']);
				$model->dds_stats_last_updated_at	 = new CDbExpression('NOW()');
			}

			if ($markup != $model->dds_markup && ($model->dds_markup_last_updated_at == '' || strtotime($model->dds_markup_last_updated_at) < (strtotime() - 1800)))
			{
				$model->dds_markup					 = $markup;
				$model->dds_markup_last_updated_at	 = new CDbExpression('NOW()');
				$model->dds_apply_markup			 = 0;
			}

			$model->save();
		}
		catch (Exception $ex)
		{
			echo "\r\nError Create == " . $ex->getMessage();
		}
	}

	public function updateBookingCount($groupType, $pickupDateTime, $fromZoneId, $toZoneId = "", $bkgType = "", $vhcCatId = "")
	{
		$pickupDate = date("Y-m-d", strtotime($pickupDateTime));

		$result = $this->getBookingCounts($groupType, $pickupDateTime, $fromZoneId, $toZoneId, $bkgType, $vhcCatId);
		if ($result)
		{
			$arrGroup	 = self::getConditions($groupType, $pickupDateTime, $fromZoneId, $toZoneId, $bkgType, $vhcCatId);
			$key		 = $arrGroup['key'];

			try
			{
				self::model()->create($key, $groupType, $pickupDate, $fromZoneId, $toZoneId, $bkgType, $vhcCatId, 0, $result);
			}
			catch (Exception $ex)
			{
				echo "\r\nError UpdateBookingCount == " . $ex->getMessage();
			}
		}
	}

	public function getBookingCounts($groupType, $pickupDateTime, $fromZoneId, $toZoneId = "", $bkgType = "", $vhcCatId = "")
	{
		$pickupDate = date("Y-m-d", strtotime($pickupDateTime));

		$arrGroup = self::getConditions($groupType, $pickupDateTime, $fromZoneId, $toZoneId, $bkgType, $vhcCatId);

		$select	 = $arrGroup['select'];
		$where	 = $arrGroup['where'];
		$groupBy = $arrGroup['groupby'];
		$join	 = $arrGroup['join'];

		$sql = "SELECT 
				COUNT(DISTINCT IF(bkg_status IN (2,3,5,6,7), bkg_id, NULL)) bkgActiveCnt, 
				COUNT(DISTINCT IF(bkg_status = 9 AND bkg_cancel_id IN (9,17,22), bkg_id, NULL)) bkgGozoCancelCnt, 
				COUNT(DISTINCT IF(bkg_status = 2 AND bkg_manual_assignment = 1 AND bkg_critical_assignment = 0, bkg_id, NULL)) bkgManualCnt, 
				COUNT(DISTINCT IF(bkg_status = 2 AND bkg_critical_assignment = 1, bkg_id, NULL)) bkgCriticalCnt, 
				COUNT(DISTINCT IF(bkg_status IN (2,3,5,6,7) AND ((bkg_gozo_amount - bkg_credits_used) / bkg_net_base_amount) > 0, bkg_id, NULL)) as bkgProfitCnt, 
				COUNT(DISTINCT IF(bkg_status IN (2,3,5,6,7) AND ((bkg_gozo_amount - bkg_credits_used) / bkg_net_base_amount) <= 0, bkg_id, NULL)) as bkgLossCnt, 
				SUM(IF((bkg_status IN (3,5,6,7) OR (bkg_status=2 AND (bkg_manual_assignment=1 OR bkg_critical_assignment=1))), (bkg_gozo_amount-bkg_credits_used), NULL)) as marginAmt, 
				SUM(IF((bkg_status IN (3,5,6,7) OR (bkg_status=2 AND (bkg_manual_assignment=1 OR bkg_critical_assignment=1))), bkg_net_base_amount, NULL)) as netBaseAmt, 
				{$select} 
				FROM booking bkg 
				INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id=bkg.bkg_id 
				INNER JOIN booking_invoice biv ON biv.biv_bkg_id=bkg.bkg_id 
				INNER JOIN zone_cities zc ON zc.zct_cty_id=bkg.bkg_from_city_id AND zc.zct_active=1 
				{$join} 
				WHERE bkg_status IN (2,3,5,6,7,9) AND bkg_booking_type IN (1,2,3) 
				AND bkg_pickup_date BETWEEN '{$pickupDate} 00:00:00' AND '{$pickupDate} 23:59:59' 
				{$where} 
				GROUP BY {$groupBy} ";

		return $result = DBUtil::queryRow($sql);
	}

	public static function getConditions($groupType, $pickupDateTime, $fromZoneId, $toZoneId = "", $bkgType = "", $vhcCatId = "")
	{
		$pickupDate			 = date("Y-m-d", strtotime($pickupDateTime));
		$pickupDateString	 = date("Ymd", strtotime($pickupDateTime));

		$arrGroup = ["select" => "", "where" => "", "whereDDSBP" => "", "groupby" => "", "join" => "", "key" => ""];

		$arrGroup[self::TYPE_ZONE]				 = [
			"select"	 => " zc.zct_zon_id fromZoneId ",
			"where"		 => " AND zc.zct_zon_id = {$fromZoneId} ",
			"whereDDSBP" => " AND dds_area_type = {$groupType} AND dds_pickup_date = '{$pickupDate}' AND dds_from_area_id = {$fromZoneId} ",
			"groupby"	 => " fromZoneId ",
			"join"		 => "",
			"key"		 => $groupType . "_" . $pickupDateString . "_" . $fromZoneId,
		];
		$arrGroup[self::TYPE_ZONE_ZONE]			 = [
			"select"	 => " zc.zct_zon_id fromZoneId, tzc.zct_zon_id toZoneId ",
			"where"		 => " AND zc.zct_zon_id = {$fromZoneId} AND tzc.zct_zon_id = {$toZoneId} ",
			"whereDDSBP" => " AND dds_area_type = {$groupType} AND dds_pickup_date = '{$pickupDate}' AND dds_from_area_id = {$fromZoneId} AND dds_to_area_id = {$toZoneId} ",
			"groupby"	 => " fromZoneId, toZoneId ",
			"join"		 => " INNER JOIN zone_cities tzc ON tzc.zct_cty_id=bkg.bkg_to_city_id AND tzc.zct_active=1 ",
			"key"		 => $groupType . "_" . $pickupDateString . "_" . $fromZoneId . "_" . $toZoneId,
		];
		$arrGroup[self::TYPE_ZONE_VHC]			 = [
			"select"	 => " zc.zct_zon_id fromZoneId, svc.scv_vct_id vhcCatId ",
			"where"		 => " AND zc.zct_zon_id = {$fromZoneId} AND svc.scv_vct_id = {$vhcCatId} ",
			"whereDDSBP" => " AND dds_area_type = {$groupType} AND dds_pickup_date = '{$pickupDate}' AND dds_from_area_id = {$fromZoneId} AND dds_vhc_cat = {$vhcCatId} ",
			"groupby"	 => " fromZoneId, vhcCatId ",
			"join"		 => " INNER JOIN svc_class_vhc_cat svc ON svc.scv_id=bkg.bkg_vehicle_type_id AND svc.scv_active=1 ",
			"key"		 => $groupType . "_" . $pickupDateString . "_" . $fromZoneId . "_" . $vhcCatId,
		];
		$arrGroup[self::TYPE_ZONE_VHC_TRIP]		 = [
			"select"	 => " zc.zct_zon_id fromZoneId, svc.scv_vct_id vhcCatId, bkg.bkg_booking_type bkgType ",
			"where"		 => " AND zc.zct_zon_id = {$fromZoneId} AND svc.scv_vct_id = {$vhcCatId} AND bkg.bkg_booking_type = {$bkgType} ",
			"whereDDSBP" => " AND dds_area_type = {$groupType} AND dds_pickup_date = '{$pickupDate}' AND dds_from_area_id = {$fromZoneId} AND dds_vhc_cat = {$vhcCatId} AND dds_trip_type = {$bkgType} ",
			"groupby"	 => " fromZoneId, vhcCatId, bkgType ",
			"join"		 => " INNER JOIN svc_class_vhc_cat svc ON svc.scv_id=bkg.bkg_vehicle_type_id AND svc.scv_active=1 ",
			"key"		 => $groupType . "_" . $pickupDateString . "_" . $fromZoneId . "_" . $vhcCatId . "_" . $bkgType,
		];
		$arrGroup[self::TYPE_ZONE_ZONE_VHC]		 = [
			"select"	 => " zc.zct_zon_id fromZoneId, tzc.zct_zon_id toZoneId, svc.scv_vct_id vhcCatId ",
			"where"		 => " AND zc.zct_zon_id = {$fromZoneId} AND tzc.zct_zon_id = {$toZoneId} AND svc.scv_vct_id = {$vhcCatId} ",
			"whereDDSBP" => " AND dds_area_type = {$groupType} AND dds_pickup_date = '{$pickupDate}' AND dds_from_area_id = {$fromZoneId} AND dds_to_area_id = {$toZoneId} AND dds_vhc_cat = {$vhcCatId} ",
			"groupby"	 => " fromZoneId, toZoneId, vhcCatId ",
			"join"		 => " INNER JOIN zone_cities tzc ON tzc.zct_cty_id=bkg.bkg_to_city_id AND tzc.zct_active=1 
								INNER JOIN svc_class_vhc_cat svc ON svc.scv_id=bkg.bkg_vehicle_type_id AND svc.scv_active=1 ",
			"key"		 => $groupType . "_" . $pickupDateString . "_" . $fromZoneId . "_" . $toZoneId . "_" . $vhcCatId,
		];
		$arrGroup[self::TYPE_ZONE_ZONE_VHC_TRIP] = [
			"select"	 => " zc.zct_zon_id fromZoneId, tzc.zct_zon_id toZoneId, svc.scv_vct_id vhcCatId, bkg.bkg_booking_type bkgType ",
			"where"		 => " AND zc.zct_zon_id = {$fromZoneId} AND tzc.zct_zon_id = {$toZoneId} AND svc.scv_vct_id = {$vhcCatId} AND bkg.bkg_booking_type = {$bkgType} ",
			"whereDDSBP" => " AND dds_area_type = {$groupType} AND dds_pickup_date = '{$pickupDate}' AND dds_from_area_id = {$fromZoneId} AND dds_to_area_id = {$toZoneId} AND dds_vhc_cat = {$vhcCatId} AND dds_trip_type = {$bkgType} ",
			"groupby"	 => " fromZoneId, toZoneId, vhcCatId, bkgType ",
			"join"		 => " INNER JOIN zone_cities tzc ON tzc.zct_cty_id=bkg.bkg_to_city_id AND tzc.zct_active=1 
								INNER JOIN svc_class_vhc_cat svc ON svc.scv_id=bkg.bkg_vehicle_type_id AND svc.scv_active=1 ",
			"key"		 => $groupType . "_" . $pickupDateString . "_" . $fromZoneId . "_" . $toZoneId . "_" . $vhcCatId . "_" . $bkgType,
		];

		if (isset($arrGroup[$groupType]))
		{
			$arrGroup = $arrGroup[$groupType];
		}

		return $arrGroup;
	}

	public function getKeyDesc($groupType, $fromZoneId, $toZoneId = "", $bkgType = "", $vhcCatId = "")
	{
		$keyDesc = "";
		switch ($groupType)
		{
			case self::TYPE_ZONE:
				$keyDesc = $this->arrZones[$fromZoneId];
				break;
			case self::TYPE_ZONE_ZONE:
				$keyDesc = $this->arrZones[$fromZoneId] . "_" . $this->arrZones[$toZoneId];
				break;
			case self::TYPE_ZONE_VHC:
				$keyDesc = $this->arrZones[$fromZoneId] . "_" . $this->arrVhcCat[$vhcCatId];
				break;
			case self::TYPE_ZONE_VHC_TRIP:
				$keyDesc = $this->arrZones[$fromZoneId] . "_" . $this->arrVhcCat[$vhcCatId] . "_" . $this->arrBkgTypes[$bkgType];
				break;
			case self::TYPE_ZONE_ZONE_VHC:
				$keyDesc = $this->arrZones[$fromZoneId] . "_" . $this->arrZones[$toZoneId] . "_" . $this->arrVhcCat[$vhcCatId];
				break;
			case self::TYPE_ZONE_ZONE_VHC_TRIP:
				$keyDesc = $this->arrZones[$fromZoneId] . "_" . $this->arrZones[$toZoneId] . "_" . $this->arrVhcCat[$vhcCatId] . "_" . $this->arrBkgTypes[$bkgType];
				break;
			default:
				break;
		}

		return $keyDesc;
	}

	public function processMarkup($minutes = 30)
	{
		$updatedDate = date("Y-m-d H:i:s", strtotime("-{$minutes} minutes"));

		// Get Zones
		$this->arrZones = Zones::model()->getZoneList1();

		// Get Vehicle Category
		$this->arrVhcCat = VehicleTypes::model()->getCarType();

		// Get Booking Types
		$this->arrBkgTypes = Booking::model()->booking_type;

		$sql	 = "SELECT * FROM dynamic_demand_supply_surge WHERE dds_area_type IN (1,2,4,5,6,7) 
					AND dds_stats_last_updated_at >= '{$updatedDate}' AND dds_pickup_date >= CURDATE() AND dds_from_area_id != dds_to_area_id AND dds_net_margin IS NOT NULL";
		//AND `dds_pickup_date` = '2022-12-09' AND `dds_area_type` = 2 AND `dds_from_area_id` IN (394,101) AND `dds_to_area_id` IN (394,101)
		$result	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($result as $row)
		{
			self::processFromRouteMarkup($row);
			self::processReturnRouteMarkup($row, true);
		}
	}

	public static function processFromRouteMarkup($row)
	{
		$key			 = $row['dds_key'];
		$bkgActiveCount	 = $row['dds_bkg_active_count'];
		$netMargin		 = $row['dds_net_margin'];
		$currentMarkup	 = (($row['dds_markup'] == null) ? 0 : $row['dds_markup']);

		if ($bkgActiveCount >= 3 && (($netMargin) < 5 || ($currentMarkup > 0 && ($netMargin - $currentMarkup) > 5)))
		{
			$markup = $currentMarkup;
			if ($netMargin < 5)
			{
				$markup	 = max((5 - floor($netMargin)), 0);
				$markup	 = max($markup, $currentMarkup);
			}

			if ($netMargin > 10 && ($currentMarkup > $netMargin * 0.5 || ($netMargin - $currentMarkup) >= 10))
			{
				$markup	 = max(($currentMarkup - (($netMargin - 10) / 2)), 0);
				$markup	 = max(round(min($markup, $currentMarkup)), -5);
			}

			if ($markup >= 0)
			{
				$model = DynamicDemandSupplySurge::model()->findByPk($key);
				if (!$model)
				{
					throw new Exception("Error key not found: {$key}");
				}
				$model->dds_markup					 = $markup;
				$model->dds_markup_last_updated_at	 = new CDbExpression('NOW()');
				$model->dds_apply_markup			 = 0;
				$model->save();
			}
		}
	}

	public static function processReturnRouteMarkup($row, $checkFrom = false)
	{
		$pickupDate		 = $row['dds_pickup_date'];
		$groupType		 = $row['dds_area_type'];
		$fromZoneId		 = $row['dds_from_area_id'];
		$toZoneId		 = $row['dds_to_area_id'];
		$bkgType		 = $row['dds_trip_type'];
		$vhcCatId		 = $row['dds_vhc_cat'];
		$bkgActiveCount	 = $row['dds_bkg_active_count'];

		if (!in_array($groupType, [self::TYPE_ZONE_ZONE_VHC_TRIP]) || $bkgType != 1)
		{
			return false;
		}

		$arrMarkup	 = self::calculateReturnRatio($groupType, $pickupDate, $toZoneId, $fromZoneId, $bkgType, $vhcCatId, $bkgActiveCount);
		$ratio		 = $arrMarkup['ratio'];
		$returnData	 = $arrMarkup['returnData'];

		if ($ratio >= 4)
		{
			$ddsNetMargin = 25;
			if ($returnData != false && $returnData['dds_bkg_active_count'] > 0)
			{
				$ddsNetMargin = 0;
				if ($returnData['dds_net_margin'] > 10)
				{
					$ddsNetMargin = $returnData['dds_net_margin'];
				}
			}
			$markup = max(round($ratio * -1.5), -1 * $ddsNetMargin);

			self::model()->create(null, $groupType, $pickupDate, $toZoneId, $fromZoneId, $bkgType, $vhcCatId, $markup);
		}

		if ($checkFrom && $returnData != false)
		{
			self::processReturnRouteMarkup($returnData);
		}
	}

	public static function calculateReturnRatio($groupType, $pickupDate, $fromZoneId, $toZoneId = "", $bkgType = "", $vhcCatId = "", $fromBkgActiveCount = 0)
	{
		$arrGroup = self::getConditions($groupType, $pickupDate, $fromZoneId, $toZoneId, $bkgType, $vhcCatId);

		$where	 = $arrGroup['whereDDSBP'];
		$key	 = $arrGroup['key'];

		$returnBkgActiveCount	 = 1;
		$sql					 = "SELECT * FROM dynamic_demand_supply_surge WHERE 1 {$where} ";
		$result					 = DBUtil::queryRow($sql, DBUtil::MDB());
		if ($result)
		{
			$key					 = $result['dds_key'];
			$returnBkgActiveCount	 = $result['dds_bkg_active_count'];
		}

		$ratio = round($fromBkgActiveCount / max(1, $returnBkgActiveCount));

		return ['returnData' => $result, 'ratio' => $ratio];
	}

	public function getList()
	{
		$query = "SELECT dds.*, fZone.zon_name as fromZone, tZone.zon_name as toZone 
				FROM `dynamic_demand_supply_surge` dds 
				INNER JOIN zones fZone ON fZone.zon_id=dds.dds_from_area_id AND fZone.zon_active=1 
				LEFT JOIN zones tZone ON tZone.zon_id=dds.dds_to_area_id AND tZone.zon_active=1 
				WHERE 1 ";

		if ($this->from_date != '' && $this->to_date != '')
		{
			$where .= " AND dds.dds_pickup_date BETWEEN '{$this->from_date}' AND '{$this->to_date}' ";
		}
		if ($this->fromZone != "")
		{
			$where .= " AND dds.dds_from_area_id = {$this->fromZone}";
		}
		if ($this->toZone != "")
		{
			$where .= " AND dds.dds_to_area_id = {$this->toZone}";
		}
		if ($this->bkgTypes != '')
		{
			$strType = implode(',', $this->bkgTypes);
			$where	 .= " AND dds.dds_trip_type IN ($strType) ";
		}
		if ($this->vehicleCategory != "")
		{
			$strCategory = implode(',', $this->vehicleCategory);
			$where		 .= " AND dds.dds_vhc_cat IN ($strCategory)";
		}
		if ($this->areaType != "")
		{
			$where .= " AND dds.dds_area_type = {$this->areaType}";
		}
		if ($this->dds_apply_markup != "")
		{
			$where .= " AND dds.dds_apply_markup = {$this->dds_apply_markup}";
		}
		if ($this->activeCountDrop > 0)
		{
			if ($this->activeCountDrop == 1)
			{
				$where2 .= " AND dds_bkg_active_count > $this->activeCount";
			}
			else
			{
				$where2 .= " AND dds_bkg_active_count < $this->activeCount";
			}
		}
		if ($this->profitCountDrop > 0)
		{
			if ($this->profitCountDrop == 1)
			{
				$where2 .= " AND dds_bkg_profit_count > $this->profitCount";
			}
			else
			{
				$where2 .= " AND dds_bkg_profit_counts < $this->profitCount";
			}
		}
		if ($this->lossCountDrop > 0)
		{
			if ($this->lossCountDrop == 1)
			{
				$where2 .= " AND dds_bkg_loss_count > $this->lossCount";
			}
			else
			{
				$where2 .= " AND dds_bkg_loss_count < $this->lossCount";
			}
		}
		if ($this->netMarginDrop > 0)
		{
			if ($this->netMarginDrop == 1)
			{
				$where2 .= " AND dds_net_margin > $this->netMarginCount";
			}
			else
			{
				$where2 .= " AND dds_net_margin < $this->netMarginCount";
			}
		}
		if ($this->markupDrop > 0)
		{
			if ($this->markupDrop == 1)
			{
				$where2 .= " AND dds_markup > $this->markupCount";
			}
			else
			{
				$where2 .= " AND dds_markup < $this->markupCount";
			}
		}
		if ($this->activeCountDrop > 0 || $this->profitCountDrop > 0 || $this->lossCountDrop > 0 || $this->netMarginDrop > 0 || $this->markupDrop > 0)
		{
			$having = " HAVING (1 " . $where2 . ")";
		}
		$sql			 = $query . $where . $having;
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['dds_pickup_date', 'fromZone', 'toZone', 'dds_trip_type', 'vct_label', 'dds_bkg_active_count', 'dds_bkg_gozo_cancelled_count', 'dds_bkg_manual_count', 'dds_bkg_critical_count', 'dds_bkg_profit_count', 'dds_bkg_loss_count', 'dds_net_margin', 'dds_markup'],
				'defaultOrder'	 => 'dds_markup DESC'], 'pagination'	 => ['pageSize' => 50],
		]);

		return $dataprovider;
	}

	public function getAreaType($typeId = 0)
	{
		$arrType = [
			1	 => 'Zone',
			2	 => 'Zone to Zone',
			//3	 => 'Zone to State',
			4	 => 'Zone, Vehicle category',
			5	 => 'Zone, Vehicle category, Booking type',
			6	 => 'Zone to Zone, Vehicle category',
			7	 => 'Zone to Zone, Vehicle category, Booking type',
				//8	 => 'Zone to State, Vehicle category',
				//9	 => 'Zone to State, Vehicle category, Booking type'
		];
		if ($typeId != 0)
		{
			return $arrType[$typeId];
		}
		else
		{
			return $arrType;
		}
	}

	public static function getMarkup($pickupDateTime, $fromCityId, $toCityId, $vhcCatId, $bkgType)
	{
		if (!in_array($bkgType, [1, 2, 3]))
		{
			return false;
		}

		$pickupDate = date("Y-m-d", strtotime($pickupDateTime));

		$fromZoneIds = ZoneCities::model()->findZoneByCity($fromCityId);
		$toZoneIds	 = ZoneCities::model()->findZoneByCity($toCityId);

		$params = ['pickupDate' => $pickupDate, 'vhcCatId' => $vhcCatId, 'bkgType' => $bkgType];

		$sql = "SELECT dds_key, dds_markup, 
					IF(dds_from_area_id > 0, 20, 0) fromZoneRank, IF(dds_to_area_id > 0, 19, 0) toZoneRank, 
					IF(dds_vhc_cat > 0, 5, 0) vhcCatRank, IF(dds_trip_type > 0, 4, 0) tripTypeRank 
				FROM dynamic_demand_supply_surge 
				WHERE dds_area_type IN (1,2,4,5,6,7) AND dds_apply_markup = 1 AND dds_markup <> 0 AND dds_markup IS NOT NULL 
					AND dds_pickup_date=:pickupDate AND dds_from_area_id IN ({$fromZoneIds}) 
					AND (dds_to_area_id IS NULL OR dds_to_area_id = 0 OR dds_to_area_id IN ({$toZoneIds})) 
					AND (dds_vhc_cat IS NULL OR dds_vhc_cat = 0 OR dds_vhc_cat =:vhcCatId) 
					AND (dds_trip_type IS NULL OR dds_trip_type = 0 OR dds_trip_type =:bkgType) 
				ORDER BY (fromZoneRank + toZoneRank + vhcCatRank + tripTypeRank) DESC LIMIT 0, 1";
		$row = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $row;
	}

	public function calculate($baseFare, $pickupDateTime, $fromCityId, $toCityId, $vhcCatId, $bkgType)
	{
		$row = DynamicDemandSupplySurge::getMarkup($pickupDateTime, $fromCityId, $toCityId, $vhcCatId, $bkgType);
		if ($row)
		{
			$markup	 = $row['dds_markup'];
			$key	 = $row['dds_key'];

			$this->refId		 = $key;
			$this->factor		 = round((100 + $markup) / 100, 2);
			$this->baseFare		 = round($baseFare * $this->factor);
			$this->surgeValue	 = $this->baseFare - $baseFare;

			return $this;
		}

		return false;
	}

}
