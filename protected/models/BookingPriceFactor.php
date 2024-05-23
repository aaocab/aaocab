<?php

/**
 * This is the model class for table "booking_price_factor".
 *
 * The followings are the available columns in table 'booking_price_factor':
 * @property integer $bpf_id
 * @property integer $bkg_ddbp_base_amount
 * @property double $bkg_ddbp_surge_factor
 * @property integer $bkg_manual_base_amount
 * property double  $bkg_manual_surge_factor
 * @property integer $bkg_regular_base_amount
 * @property integer $bkg_ddbp_route_flag
 * @property integer $bkg_ddbp_master_flag
 * @property integer $bpf_bkg_id
 * @property integer $bkg_surge_applied
 * @property integer $bkg_dtbp_base_amount
 * @property double  $bkg_dtbp_factor
 * @property integer $bkg_ddbp_factor_type
 * @property integer $bkg_manual_surge_id
 * @property integer $bkg_route_route_factor
 * @property integer $bkg_zone_zone_factor
 * @property integer $bkg_zone_state_factor
 * @property double  $bkg_zone_factor
 * @property integer $bkg_profitability_base_amount
 * @property double  $bkg_profitability_surge_factor
 * @property integer $bkg_dzpp_base_amount
 * @property double  $bkg_dzpp_surge_factor
 * @property string  $bkg_surge_description
 * @property string  $bkg_additional_param
 * @property integer $bkg_debp_base_amount
 * @property double  $bkg_debp_surge_factor
 * @property integer $bkg_durp_base_amount
 * @property double  $bkg_durp_surge_factor
 * @property integer $bkg_rte_id 
 * 
 * The followings are the available model relations:
 * @property Booking $pfBkg
 */
class BookingPriceFactor extends CActiveRecord
{

	public $bpf_pickup_date1, $bpf_pickup_date2, $fromCity, $toCity, $from_zone, $to_zone;

//    CONST surgeFactorList      = [0 => 'Regular', 1 => 'Manual', 2 => 'DDBP', 3 => 'DDBP and Manual', 4 => 'DTBP', 5 => 'Profitabilty', 6 => 'Profitabilty and Manual', 7 => 'DZPP'];
//    CONST surgeFactorListShort = [0 => 'R', 1 => 'M', 2 => 'DD', 3 => 'DD AND M', 4 => 'DT', 5 => 'PB', 6 => 'PB AND M', 7 => 'DZ'];

	CONST surgeFactorList		 = [
		0	 => 'Regular',
		1	 => 'Manual',
		2	 => 'DDBP',
		4	 => 'DTBP',
		7	 => 'DZPP',
		8	 => 'DURP',
		9	 => 'DEBP',
		10	 => 'DDBPV2',
		11	 => 'DEBP+DDBPV2',
		12	 => 'DDSBP',
	];
	CONST surgeFactorListShort = [
		0	 => 'R',
		1	 => 'M',
		2	 => 'DD',
		4	 => 'DT',
		7	 => 'DZ',
		8	 => 'DUR',
		9	 => 'DE',
		10	 => 'DD(V2)',
		11	 => 'DE+DD(V2)',
		12	 => 'DDSBP',
	];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_price_factor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bpf_id, bkg_ddbp_base_amount, bkg_ddbp_surge_factor, bkg_manual_base_amount, bkg_regular_base_amount, bkg_ddbp_route_flag, bkg_ddbp_master_flag, bpf_bkg_id, bkg_surge_applied,
 bkg_dtbp_base_amount, bkg_ddbp_factor_type, bkg_manual_surge_id, bkg_route_route_factor, bkg_zone_zone_factor, bkg_zone_state_factor, bkg_zone_factor,bkg_additional_param,
bkg_profitability_base_amount,bkg_profitability_surge_factor,bkg_manual_surge_factor,bkg_dtbp_factor,bkg_dzpp_base_amount,bkg_dzpp_surge_factor,bkg_surge_description,bkg_debp_base_amount,bkg_debp_surge_factor,bkg_durp_base_amount,bkg_durp_surge_factor,bkg_ddbpv2_base_amount,bkg_ddbpv2_surge_factor,bkg_partner_soldout,bkg_rte_id', 'safe', 'on' => 'search'),
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
			'pfBkg' => array(self::BELONGS_TO, 'Booking', 'bpf_bkg_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bpf_id'					 => 'Bpf',
			'bkg_ddbp_base_amount'		 => 'Bkg Ddbp Base Amount',
			'bkg_ddbp_surge_factor'		 => 'Bkg Ddbp Surge Factor',
			'bkg_manual_base_amount'	 => 'Bkg Manual Base Amount',
			'bkg_regular_base_amount'	 => 'Bkg Regular Base Amount',
			'bkg_ddbp_route_flag'		 => 'Bkg Surge Flag',
			'bkg_ddbp_master_flag'		 => 'Bkg Master Flag',
			'bpf_bkg_id'				 => 'Bpf Bkg',
			'bkg_surge_applied'			 => '0=>regular, 1=>manual, 2=> DDBP, 3=>Both',
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

		$criteria->compare('bpf_id', $this->bpf_id);
		$criteria->compare('bkg_ddbp_base_amount', $this->bkg_ddbp_base_amount);
		$criteria->compare('bkg_ddbp_surge_factor', $this->bkg_ddbp_surge_factor);
		$criteria->compare('bkg_manual_base_amount', $this->bkg_manual_base_amount);
		$criteria->compare('bkg_regular_base_amount', $this->bkg_regular_base_amount);
		$criteria->compare('bkg_ddbp_route_flag', $this->bkg_ddbp_route_flag);
		$criteria->compare('bkg_ddbp_master_flag', $this->bkg_ddbp_master_flag);
		$criteria->compare('bpf_bkg_id', $this->bpf_bkg_id);
		$criteria->compare('bkg_surge_applied', $this->bkg_surge_applied);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingPriceFactor the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByBookingID($bkgId)
	{
		$model = $this->find("bpf_bkg_id=:bkgId", ['bkgId' => $bkgId]);
		return $model;
	}

	public function updateDynamicCounter()
	{
		$dynamicSurge = new DynamicSurge();
		$dynamicSurge->updateBookingCount($this->bpf_bkg_id);
	}

	public function updateFromQuote(Quote $qModel)
	{
		$this->populateFromQuote($qModel);
		return $this->save();
	}

	public function populateFromQuote(Quote $qModel)
	{
		$routeRates								 = $qModel->routeRates;
		$this->bkg_ddbp_base_amount				 = $routeRates->srgDDBP->rockBaseAmount;
		$this->bkg_ddbp_master_flag				 = Yii::app()->params['dynamicSurge'];
		$this->bkg_ddbp_route_flag				 = $routeRates->srgDDBP->refModel->routeFlag;
		$this->bkg_ddbp_surge_factor			 = $routeRates->srgDDBP->refModel->dprApplied->factor;
		$this->bkg_manual_base_amount			 = $routeRates->srgManual->rockBaseAmount;
		$this->bkg_manual_surge_factor			 = $routeRates->srgManual->factor;
		$this->bkg_manual_surge_id				 = $routeRates->srgManual->refId | 0;
		$this->bkg_regular_base_amount			 = $routeRates->regularBaseAmount;
		$this->bkg_dtbp_base_amount				 = $routeRates->srgDTBP->rockBaseAmount;
		$this->bkg_dtbp_factor					 = $routeRates->srgDTBP->factor;
		$this->bkg_surge_applied				 = $routeRates->surgeFactorUsed;
		$this->bkg_factors_used					 = implode(",", $routeRates->appliedFactors);
		$this->bkg_ddbp_factor_type				 = $routeRates->srgDDBP->refModel->dprApplied->type;
		$this->bkg_route_route_factor			 = $routeRates->srgDDBP->refModel->dprRoutes->factor;
		$this->bkg_zone_zone_factor				 = $routeRates->srgDDBP->refModel->dprZoneRoutes->factor;
		$this->bkg_zone_state_factor			 = $routeRates->srgDDBP->refModel->dprZonesStates->factor;
		$this->bkg_zone_factor					 = $routeRates->srgDDBP->refModel->dprZones->factor;
		$this->bkg_profitability_base_amount	 = $routeRates->srgProfitability->rockBaseAmount;
		$this->bkg_profitability_surge_factor	 = $routeRates->srgProfitability->factor;
		$this->bkg_dzpp_base_amount				 = $routeRates->srgDZPP->rockBaseAmount;
		$this->bkg_dzpp_surge_factor			 = $routeRates->srgDZPP->factor;
		$this->bkg_surge_description			 = $routeRates->srgDZPP->surgeDesc;
		$this->bkg_debp_base_amount				 = $routeRates->srgDEBP->rockBaseAmount;
		$this->bkg_debp_surge_factor			 = $routeRates->srgDEBP->factor;
		$this->bkg_durp_base_amount				 = $routeRates->srgDURP->rockBaseAmount;
		$this->bkg_durp_surge_factor			 = $routeRates->srgDURP->factor;
		$this->bkg_ddbpv2_base_amount			 = $routeRates->srgDDBPV2->rockBaseAmount;
		$this->bkg_ddbpv2_surge_factor			 = $routeRates->srgDDBPV2->factor;
		$this->bkg_ddsbp_base_amount			 = $routeRates->srgDDSBP->rockBaseAmount;
		$this->bkg_ddsbp_surge_factor			 = $routeRates->srgDDSBP->factor;
		$this->bkg_additional_param				 = $routeRates->additional_param;
		$this->bkg_partner_soldout				 = $routeRates->partner_soldout;
		$preData								 = $this->bkg_additional_param;
		if ($preData != '')
		{
			$preDataArr			 = json_decode($preData, true);
			$this->bkg_rte_id	 = $preDataArr['rateId'];
		}
		if ($routeRates->minVendorAmount > 0 && $routeRates->maxVendorAmount > 0)
		{
			$preDataArr	 = [];
			$preData	 = $this->bkg_additional_param;
			if ($preData != '')
			{
				$preDataArr = json_decode($preData, true);
			}
			$additionalArr				 = ['minVendorAmount' => $routeRates->minVendorAmount, 'maxVendorAmount' => $routeRates->maxVendorAmount];
			$this->bkg_additional_param	 = json_encode(['vndGnowOfferSuggestion' => $additionalArr] + $preDataArr);
		}

		//// TODO Manual Surge Id, DTBP Base Amount, $DDBP Factor Type
//		return $this->save();
	}

	public function getList($date1 = '', $date2 = '', $sourcezone = '', $destinationzone = '', $command = DBUtil::ReturnType_Provider)
	{
		$sourcezone		 = ($sourcezone != '') ? " AND z1.zon_id IN ($sourcezone)" : '';
		$destinationzone = ($destinationzone != '') ? " AND z2.zon_id IN ($destinationzone)" : '';
		$sql			 = "SELECT   Date(bkg.bkg_pickup_date) AS pickupDate, bkg.bkg_id, bpf.bpf_bkg_id, bkg.bkg_status,
								fromCty.cty_name AS fromCity, z1.zon_name AS from_zone,z2.zon_name AS to_zone,
								toCty.cty_name AS toCity,
								COUNT(bkg.bkg_id) AS totalBooking,
								SUM(CASE WHEN bpf.bkg_surge_applied = 0 THEN 1 ELSE 0 END) AS regular,
								SUM(CASE WHEN bpf.bkg_surge_applied = 1 THEN 1 ELSE 0 END) AS manual,
								SUM(CASE WHEN bpf.bkg_surge_applied = 4 THEN 1 ELSE 0 END) AS dtbp,
								SUM(CASE WHEN bpf.bkg_surge_applied = 3 THEN 1 ELSE 0 END) AS manualddbp,
								SUM(CASE WHEN bpf.bkg_ddbp_factor_type IN(0,1) AND bpf.bkg_surge_applied = 2 THEN 1 ELSE 0 END) AS countOfroute_route,
								SUM(CASE WHEN bpf.bkg_ddbp_factor_type = 2 AND bpf.bkg_surge_applied = 2 THEN 1 ELSE 0 END) AS countOfzone_zone,
								SUM(CASE WHEN bpf.bkg_ddbp_factor_type = 3 AND bpf.bkg_surge_applied = 2 THEN 1 ELSE 0 END) AS countOfzone_state,
								SUM(CASE WHEN bpf.bkg_ddbp_factor_type = 4 AND bpf.bkg_surge_applied = 2 THEN 1 ELSE 0 END) AS countOfzone
							FROM     booking bkg INNER JOIN booking_price_factor bpf ON bpf.bpf_bkg_id = bkg.bkg_id 
							INNER JOIN zone_cities zc1 ON zc1.zct_cty_id=bkg.bkg_from_city_id 
							INNER JOIN zone_cities zc2 ON zc2.zct_cty_id=bkg.bkg_to_city_id 
							INNER JOIN zones z1 ON z1.zon_id=zc1.zct_zon_id  $sourcezone
							INNER JOIN zones z2 ON z2.zon_id=zc2.zct_zon_id  $destinationzone
							JOIN cities as fromCty ON fromCty.cty_id=bkg.bkg_from_city_id
							JOIN cities as toCty ON toCty.cty_id=bkg.bkg_to_city_id  WHERE bkg.bkg_active=1
							AND bkg.bkg_status IN (2,3,4,5)    ";
		if (($date1 != '' && $date1 != '') && ($date2 != '' && $date2 != ''))
		{
			$sql .= " AND ( bkg.bkg_pickup_date BETWEEN '$date1 00:00:00' AND '$date2 23:59:59') ";
		}
		if (isset($from) && $from != '')
		{
			$sql .= " AND fromCty.cty_id='" . $from . "'";
		}
		if (isset($to) && $to != '')
		{
			$sql .= " AND toCty.cty_id ='" . $to . "'";
		}
		$sql .= " GROUP BY pickupDate,bkg.bkg_from_city_id,bkg.bkg_to_city_id ";

		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 =>
				['attributes'	 => ['pickupDate', 'from_zone', 'to_zone', 'fromCity', 'toCity', 'totalBooking', 'regular', 'manual', 'manualddbp', 'dtbp', 'countOfroute_route', 'countOfzone_zone', 'countOfzone_state'],
					'defaultOrder'	 => ''
				],
				'pagination'	 => ['pageSize' => 500],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
	}

	/**
	 * 
	 * @param type $bkgId
	 */
	public function getQuotedFactor($bkgId)
	{
		try
		{
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userType	 = UserInfo::TYPE_SYSTEM;
			$surgeArr			 = BookingPriceFactor::surgeFactorListShort;
			$caseStr			 = '(CASE bkg_surge_applied ';
			foreach ($surgeArr as $key => $value)
			{
				$caseStr .= " WHEN $key THEN '$value' ";
			}
			$caseStr .= " ELSE '' END)";
			$sql	 = "SELECT bkg_regular_base_amount AS regular, 
				bkg_manual_base_amount AS manual,bkg_manual_surge_factor,
				bkg_ddbp_base_amount AS ddbp,
				bkg_dtbp_base_amount AS dtbp,bkg_dtbp_factor,								
                bkg_ddbp_surge_factor,
				bkg_profitability_base_amount  AS pb,
				bkg_profitability_surge_factor,
				bkg_dzpp_surge_factor,
				bkg_dzpp_base_amount,
				bkg_debp_surge_factor,
				bkg_debp_base_amount,
				bkg_durp_surge_factor,
				bkg_durp_base_amount,
				bkg_ddbpv2_base_amount,
				bkg_ddbpv2_surge_factor,
				bkg_ddsbp_base_amount,
				bkg_ddsbp_surge_factor,
				bkg_additional_param AS additionalParam,
				JSON_VALUE(booking_price_factor.bkg_additional_param,'$.srgManual.factor') AS srgManualFactor,
				JSON_VALUE(booking_price_factor.bkg_additional_param,'$.srgDZPP.factor') AS srgDZPPFactor,
				JSON_VALUE(booking_price_factor.bkg_additional_param,'$.srgDDBP.factor') AS srgDDBPFactor,
				JSON_VALUE(booking_price_factor.bkg_additional_param,'$.srgDEBP.factor') AS srgDEBPFactor,
				JSON_VALUE(booking_price_factor.bkg_additional_param,'$.srgDURP.factor') AS srgDURPFactor,
				JSON_VALUE(booking_price_factor.bkg_additional_param,'$.srgDDBPV2.factor') AS srgDDBPV2Factor,
				JSON_VALUE(booking_price_factor.bkg_additional_param,'$.srgDTBP.factor') AS srgDTBPFactor,
				JSON_VALUE(booking_price_factor.bkg_additional_param,'$.srgDTBP.surgeAmount') AS dtbpSurgeAmount,
				JSON_VALUE(booking_price_factor.bkg_additional_param,'$.srgDDSBP.factor') AS srgDDSBPFactor,
				$caseStr AS QuoteFactor 
				FROM booking_price_factor WHERE  bpf_bkg_id =:bkgId";
			$data	 = DBUtil::queryRow($sql, DBUtil::MDB(), ['bkgId' => $bkgId]);

			$surgeFactorDescription = "";
			if ($data['additionalParam'] != null)
			{
				$result					 = CJSON::decode($data['additionalParam']);
				$surgeFactorDescription	 = " | PPE path --> " . $result['surgeFactorDescription'];
			}

			$regular = '*R@₹' . $data['regular'] . '*  ';
			$desc	 = "PPE: " . $regular . "; $surgeFactorDescription";
			$success = BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog:: Dynamic_Price, false, false);
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
	}

}
