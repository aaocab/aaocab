<?php

/**
 * This is the model class for table "price_rule".
 *
 * The followings are the available columns in table 'price_rule':
 * @property integer $prr_id
 * @property integer $prr_cab_type
 * @property string $prr_cab_desc
 * @property integer $prr_trip_type
 * @property double $prr_rate_per_km
 * @property double $prr_rate_per_km_extra
 * @property integer $prr_min_km
 * @property integer $prr_min_base_amount
 * @property integer $prr_min_km_day
 * @property integer $prr_max_km_day
 * @property integer $prr_day_driver_allowance
 * @property integer $prr_night_driver_allowance
 * @property integer $prr_driver_allowance_km_limit
 * @property string $prr_night_start_time
 * @property string $prr_night_end_time
 * @property integer $prr_calculation_type
 * @property integer $prr_min_pickup_duration
 * @property integer $prr_active
 * @property integer $prr_zone_rule_id
 * @property string $prr_created_date
 * @property string $prr_modified_date
 * @property string $prr_log
 * @property SvcClassVhcCat $prrSvc
 * @property integer $prr_rate_per_minute_extra
 * @property integer $prr_min_duration
 * @property double  $prr_rate_per_minute
 */
class PriceRule extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'price_rule';
	}

	public $area_id, $note, $areaType, $areaId, $basePriceRule, $isUpdate, $isType, $aprId;
	public $optimizeFlag = false;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prr_cab_type, prr_trip_type, prr_rate_per_km, prr_trip_type', 'required'),
			array('prr_cab_type, prr_trip_type, prr_min_km, prr_min_base_amount, prr_min_km_day, prr_max_km_day, prr_day_driver_allowance, prr_night_driver_allowance, prr_driver_allowance_km_limit, prr_calculation_type, prr_min_pickup_duration, prr_active, prr_zone_rule_id', 'numerical', 'integerOnly' => true),
			array('prr_rate_per_km, prr_rate_per_km_extra', 'numerical'),
			array('prr_cab_desc', 'length', 'max' => 1000),
			array('prr_log', 'length', 'max' => 4000),
			// array('prr_night_start_time, prr_night_end_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prr_id, prr_cab_type, prr_cab_desc, prr_trip_type, prr_rate_per_km, prr_rate_per_km_extra, prr_min_km, prr_min_base_amount, prr_min_km_day, prr_max_km_day, prr_day_driver_allowance, prr_night_driver_allowance, prr_driver_allowance_km_limit, prr_night_start_time, prr_night_end_time, prr_calculation_type, prr_min_pickup_duration, prr_active, prr_zone_rule_id, prr_created_date, prr_modified_date, prr_log, prr_rate_per_minute_extra, prr_min_duration, prr_rate_per_minute', 'safe', 'on' => 'search'),
			array('prr_id, prr_cab_type, prr_cab_desc, prr_trip_type, prr_rate_per_km, prr_rate_per_km_extra, prr_min_km, prr_min_base_amount, prr_min_km_day, prr_max_km_day, prr_day_driver_allowance, prr_night_driver_allowance, prr_driver_allowance_km_limit, prr_night_start_time, prr_night_end_time, prr_calculation_type, prr_min_pickup_duration, prr_active, prr_zone_rule_id, prr_created_date, prr_modified_date, prr_log, prr_rate_per_minute_extra, prr_min_duration, prr_rate_per_minute', 'safe'),
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
			'prrSvc' => array(self::BELONGS_TO, 'SvcClassVhcCat', 'prr_cab_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'prr_id'						 => 'Prr',
			'prr_cab_type'					 => 'Cab Type',
			'prr_cab_desc'					 => 'Rule Desc',
			'prr_trip_type'					 => 'Trip Type',
			'prr_rate_per_km'				 => 'Rate Per Km',
			'prr_rate_per_km_extra'			 => 'Rate Per Km Extra',
			'prr_min_km'					 => 'Min Per Day Km', // 'minimum chargeable distance on a quotation';
			'prr_min_base_amount'			 => 'Min Base Amount',
			'prr_min_km_day'				 => 'Min Km Day', //  'minimum chargeable distance in a day'; only applies to mmulti-day trips (ignored for <2 day journeys)
			'prr_max_km_day'				 => 'Max Km Per Day',
			'prr_day_driver_allowance'		 => 'Day Driver Allowance',
			'prr_night_driver_allowance'	 => 'Night Driver Allowance',
			'prr_driver_allowance_km_limit'	 => 'Driver Allowance Km Limit',
			'prr_night_start_time'			 => 'Night Start Time',
			'prr_night_end_time'			 => 'Night End Time',
			'prr_calculation_type'			 => 'Calculation Type',
			'prr_min_pickup_duration'		 => 'Min Pickup Duration',
			'prr_active'					 => 'Active',
			'prr_zone_rule_id'				 => 'Zone Rule',
			'prr_created_date'				 => 'Created Date',
			'prr_modified_date'				 => 'Modified Date',
			'prr_log'						 => 'Log',
			'prr_rate_per_minute_extra'		 => 'Rate per hour extra',
			'prr_min_duration'				 => 'Minimum Hour'
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

		$criteria->compare('prr_id', $this->prr_id);
		$criteria->compare('prr_cab_type', $this->prr_cab_type);
		$criteria->compare('prr_cab_desc', $this->prr_cab_desc, true);
		$criteria->compare('prr_trip_type', $this->prr_trip_type);
		$criteria->compare('prr_rate_per_km', $this->prr_rate_per_km);
		$criteria->compare('prr_rate_per_km_extra', $this->prr_rate_per_km_extra);
		$criteria->compare('prr_min_km', $this->prr_min_km);
		$criteria->compare('prr_min_base_amount', $this->prr_min_base_amount);
		$criteria->compare('prr_min_km_day', $this->prr_min_km_day);
		$criteria->compare('prr_max_km_day', $this->prr_max_km_day);
		$criteria->compare('prr_day_driver_allowance', $this->prr_day_driver_allowance);
		$criteria->compare('prr_night_driver_allowance', $this->prr_night_driver_allowance);
		$criteria->compare('prr_driver_allowance_km_limit', $this->prr_driver_allowance_km_limit);
		$criteria->compare('prr_night_start_time', $this->prr_night_start_time, true);
		$criteria->compare('prr_night_end_time', $this->prr_night_end_time, true);
		$criteria->compare('prr_calculation_type', $this->prr_calculation_type);
		$criteria->compare('prr_min_pickup_duration', $this->prr_min_pickup_duration);
		$criteria->compare('prr_active', $this->prr_active);
		$criteria->compare('prr_zone_rule_id', $this->prr_zone_rule_id);
		$criteria->compare('prr_created_date', $this->prr_created_date, true);
		$criteria->compare('prr_modified_date', $this->prr_modified_date, true);
		$criteria->compare('prr_log', $this->prr_log, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PriceRule the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public $calculation_type = [
		1	 => 'Exact route distance (rule: 1)',
		2	 => 'Garage to garage (rule: 2)',
		3	 => 'Garage to Drop location (rule: 3)',
		4	 => 'Pickup location to garage (rule: 4)',
		5	 => 'Running Distance (rule: 5)',
		6	 => 'Exact route distance, min base fare (rule: 6)',
		11	 => 'Running Distance (KM Optimized) (rule: 11)'
	];

	public function getList($type = '')
	{
		$sql			 = "SELECT *
                    FROM `price_rule`
                    WHERE
                    prr_active = 1";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['prr_trip_type'],
				'defaultOrder'	 => 'prr_cab_type ASC'],
			'pagination'	 => ['pageSize' => 50],
		]);

		return $dataprovider;
	}

	public function getDefaultJSON($cab = '', $ttype = '')
	{
		$data	 = [];
		$arr	 = [];
		$arr[-2] = array("id" => "-1", "text" => "Blocked");
		$arr[-1] = array("id" => "0", "text" => "Inherited");
		if ($cab > 0 && $ttype > 0)
		{
			$data = $this->getFilterData($cab, $ttype);
		}
		$arr	 = array_merge($arr, $data);
		$data	 = CJSON::encode($arr);
		return $data;
	}

	public function getFilterData($cab, $ttype)
	{
		$qry	 = "SELECT prr_id as id, prr_cab_desc as text
			FROM `price_rule` `t`
			WHERE prr_cab_type = $cab AND prr_active = 1 AND prr_trip_type = $ttype
			ORDER BY prr_id";
		$data	 = DBUtil::queryAll($qry);
		return $data;
	}

	public function optimiseUnutilised(Quote $quote)
	{
		if (!in_array($this->prr_trip_type, [2, 3]) || !$this->optimizeFlag)
		{
			return;
		}
		$routeDistance		 = $quote->routeDistance;
		$requiredKMs		 = $routeDistance->tripDistance;
		$routeRates			 = $quote->routeRates;
		$chargeableDistance	 = $routeDistance->chargeableDistance;
		$days				 = $quote->routeDuration->calendarDays;
		$unutilisedKms		 = ($chargeableDistance - $requiredKMs);
		$unutilisedPercent	 = ($unutilisedKms / $chargeableDistance);
		$optimizePricePerKM	 = $this->prr_rate_per_km * 0.4;

		$maxOptimizedKms = round(min([$chargeableDistance - $quote->minRequiredKms, $this->prr_min_km_day * 0.20 * $days, $unutilisedKms * 0.50]));
		$optimizeFlag	 = ($unutilisedPercent > 0.20 && $unutilisedKms > 100) || ($quote->minRequiredKms > 0 && ($chargeableDistance - $quote->minRequiredKms) > 0);

		if ($optimizeFlag)
		{
			$quotedDistance					 = $routeDistance->chargeableDistance - $maxOptimizedKms;
			$quotedDistance					 = round($quotedDistance / 10) * 10;
			$maxOptimizedKms				 = $routeDistance->chargeableDistance - $quotedDistance;
			$optimizedFare					 = $optimizePricePerKM * $maxOptimizedKms;
			$routeRates->vendorBaseFare		 = round($routeRates->vendorBaseFare - $optimizedFare);
			$routeDistance->quotedDistance	 = $quotedDistance;
			//		$routeDistance->chargeableDistance	 = $quotedDistance;
		}
	}

	/** @param Quote $quoteModel */
	public function processRule(&$quoteModel)
	{
		$minimumChargeableDistance	 = $this->prr_min_km;
		$prrMinKm					 = $this->prr_min_km;
		$minimumBaseFare			 = $this->prr_min_base_amount;

		$routeDistance	 = $quoteModel->routeDistance;
		$totalRunning	 = $routeDistance->totalRunning;
		$tripDistance	 = $routeDistance->tripDistance;
		$quotedDistance	 = $routeDistance->quotedDistance;
		$totalGarage	 = $routeDistance->totalGarage;
		$endDistance	 = $routeDistance->endDistance;

		if (in_array($this->prr_trip_type, [2, 3]))
		{
			$days						 = $quoteModel->routeDuration->calendarDays;
			$minimumChargeableDistance	 = max($this->prr_min_km, $this->prr_min_km_day * $days);  // for RT & MW its max of the 2
			$minimumBaseFare			 = $this->prr_min_base_amount * $quoteModel->routeDuration->calendarDays;
		}
		else if ($this->prr_trip_type == 4)
		{
			$quoteModel->routeRates->isTollIncluded		 = 1;
			$quoteModel->routeRates->isStateTaxIncluded	 = 1;
			$quoteModel->routeRates->isParkingIncluded	 = 1;
		}

		Logger::trace("updated trip distance => 12 =>". $quotedDistance);
		$chargeableDistance			 = $minimumChargeableDistance	 = max([$quotedDistance, $minimumChargeableDistance]);
		Logger::trace("updated trip distance => 13 =>". $chargeableDistance);

		$extraDistance				 = 0;

		/*
		  Price Rule Types we use -

		  [Recording of our discussion](https://youtu.be/z_v-GBLyCx0)

		  ID 1 = Exact route distance (super tight) - from customer pickup address to customer drop address
		  ID 2 = Garage to garage - from closest high density location to next closest high density location (Suggested for OW, MW in zones and major cities where density is high) (For tight areas.....)
		  ID 3 = Garage to Drop location - closest high density start location to exact customer drop address (Suggested for OW, MW in Regions and State level fall back pricing). (Use this absolutely for Daily rental) (For loose areas)
		  ID 4 - Pickup location to Garaage -- exact customer pickup address to closest highest density drop location (not using....in most place)
		  ID 5 - Running Distance -- from vendor home city to back to his home city (Suggested for Round trips irrespective of loose or tight rates)
		  ID 6 - Exact route distance, min base fare (Suggested for AT, And for DR also)
		  [How to setup DR rates DR >>> how to set it up recording with abhishek](https://youtu.be/LKI-qK4cX24)

		  Also spoke to Abhishek, he's put all the logic in calRule2 thats why you see that in most of the case statements, not a bug -> its by design
		 */

		switch ($this->prr_calculation_type)
		{
			case 1: //ID 1 = Exact route distance (super tight) - from customer pickup address to customer drop address
				$extraRunningDistance				 = 0;
				$tripFareArr						 = $this->calRule2($chargeableDistance, $extraRunningDistance, $minimumChargeableDistance, $quotedDistance);
				$tripFareArr['tot']					 = $tripFareArr['tot'] + $minimumBaseFare;
				break;
			case 2: //ID 2 = Garage to garage - from closest high density location to next closest high density location (Suggested for OW, MW in zones and major cities where density is high) (For tight areas.....)
				$extraRunningDistance				 = max([$totalGarage - $quotedDistance, $routeDistance->startDistance + $endDistance - 400]);
				$totalGarage						 = $quotedDistance + $extraRunningDistance;
				$chargeableDistance					 = max([$minimumChargeableDistance, $totalGarage]);
				$tripFareArr						 = $this->calRule2($chargeableDistance, $extraRunningDistance, $minimumChargeableDistance, $quotedDistance);
				$tripFareArr['tot']					 = $tripFareArr['tot'] + $minimumBaseFare;
				break;
			case 3: //ID 3 = Garage to Drop location - closest high density start location to exact customer drop address (Suggested for OW, MW in Regions and State level fall back pricing). (Use this absolutely for Daily rental) (For loose areas)
				$extraRunningDistance				 = $routeDistance->startDistance;
				$chargeableDistance					 = $extraRunningDistance + $quotedDistance;
				$chargeableDistance					 = max([$minimumChargeableDistance, $chargeableDistance]);
				$tripFareArr						 = $this->calRule2($chargeableDistance, $extraRunningDistance, $minimumChargeableDistance, $quotedDistance);
				$tripFareArr['tot']					 = $tripFareArr['tot'] + $minimumBaseFare;
				$quoteModel->servingRoute['end']	 = $quoteModel->servingRoute['drop'];
				break;
			case 4: //ID 4 - Pickup location to Garaage -- exact customer pickup address to closest highest density drop location (not using....in most place)
				$extraRunningDistance				 = $routeDistance->garageEndDistance;
				$chargeableDistance					 = $quotedDistance + $extraRunningDistance;
				$chargeableDistance					 = max([$minimumChargeableDistance, $chargeableDistance]);
				$tripFareArr						 = $this->calRule2($chargeableDistance, $extraRunningDistance, $minimumChargeableDistance, $quotedDistance);
				$tripFareArr['tot']					 = $tripFareArr['tot'] + $minimumBaseFare;
				$quoteModel->servingRoute['start']	 = $quoteModel->servingRoute['pickup'];
				break;
			case 5: //ID 5 - Running Distance -- from vendor home city to back to his home city (Suggested for Round trips irrespective of loose or tight rates)
				$extraRunningDistance				 = $routeDistance->startDistance + $routeDistance->endDistance;
				$chargeableDistance					 = $totalRunning;
				$chargeableDistance					 = max([$minimumChargeableDistance, $chargeableDistance]);
				$tripFareArr						 = $this->calRule2($chargeableDistance, $extraRunningDistance, $minimumChargeableDistance, $quotedDistance);
				$tripFareArr['tot']					 = $tripFareArr['tot'] + $minimumBaseFare;
				$quoteModel->servingRoute['end']	 = $quoteModel->servingRoute['start'];
				break;
			case 6: //ID 6 - Exact route distance, min base fare (Suggested for AT, And for DR also)
				$tripFareArr						 = $this->calRule6($minimumChargeableDistance, $quotedDistance, $tripDistance, $extraDistance, $prrMinKm);
				break;
			case 7:
				$tripFareArr						 = $this->calRule7($minimumChargeableDistance, $quotedDistance, $tripDistance, $extraDistance);
				break;
			case 8:
				$tripFareArr						 = $this->calRule8($minimumChargeableDistance, $quotedDistance, $tripDistance, $extraDistance);
				break;
			case 9:
				$tripFareArr						 = $this->calRule9($minimumChargeableDistance, $quotedDistance, $tripDistance, $extraDistance);
				break;
			case 10:
				$tripFareArr						 = $this->calRule10($minimumChargeableDistance, $quotedDistance, $tripDistance, $extraDistance);
				break;
			case 11:
				$this->optimizeFlag					 = true;
				$routeDistance						 = $quoteModel->routeDistance;
				$requiredKMs						 = max([$quoteModel->minRequiredKms, $routeDistance->tripDistance]);
				$extraRunningDistance				 = $routeDistance->startDistance + $routeDistance->endDistance;
				$chargeableDistance					 = max([$totalRunning, $requiredKMs + $extraRunningDistance, $minimumChargeableDistance]);
				$tripFareArr						 = $this->calRule11($chargeableDistance, $extraRunningDistance, $minimumChargeableDistance, $quotedDistance);
				$tripFareArr['tot']					 = $tripFareArr['tot'] + $minimumBaseFare;
				$quoteModel->servingRoute['end']	 = $quoteModel->servingRoute['start'];
				break;
			default:
				$extraRunningDistance				 = $routeDistance->startDistance + $routeDistance->endDistance;
				$chargeableDistance					 = $totalRunning;
				$chargeableDistance					 = max([$minimumChargeableDistance, $chargeableDistance]);
				$tripFareArr						 = $this->calRule2($chargeableDistance, $extraRunningDistance, $minimumChargeableDistance, $quotedDistance);
				$tripFareArr['tot']					 = $tripFareArr['tot'] + $minimumBaseFare;
				$quoteModel->servingRoute['end']	 = $quoteModel->servingRoute['start'];
				break;
		}
		Logger::info("PriceRule::processRule chargeableDistance: " . $chargeableDistance . "   extraRunningDistance: " . $extraRunningDistance . "  minimumChargeableDistance: " . $minimumChargeableDistance . " quotedDistance: " . $quotedDistance);
		Logger::info("PriceRule::processRule calcType: " . $this->prr_calculation_type . " routeRates->vendorBaseFare: " . $tripFareArr['tot']);
		$baseFare										 = $tripFareArr['tot'];
		$quoteModel->routeDistance->chargeableDistance	 = $tripFareArr['chargeableDistance'];
		$quoteModel->routeDistance->quotedDistance		 = $tripFareArr['quotedDistance'];

		Logger::trace("updated trip distance => 14 =>". $quoteModel->routeDistance->quotedDistance);

		$quoteModel->routeRates->costPerKM				 = $this->prr_rate_per_km;
		$quoteModel->routeRates->ratePerKM				 = $this->prr_rate_per_km_extra;
		$quoteModel->routeRates->extraPerMinCharge		 = $this->prr_rate_per_minute_extra;
		//$quoteModel->routeRates->extraPerMin             = $this->prr_rate_per_minute;
		$quoteModel->routeRates->vendorBaseFare			 = $baseFare;
		$quoteModel->priceRule							 = $this;

		//this condition is only applicable to kayak agent day rental converted booking
		if($quoteModel->isconvertedToDR == 1 && $quoteModel->routeDuration->totalMinutes > $this->prr_min_duration)
		{
			$extraMinutes = $quoteModel->routeDuration->totalMinutes - $this->prr_min_duration;
			$extraMinutesFare = round($extraMinutes * $this->prr_rate_per_minute);
			//$quoteModel->routeRates->vendorBaseFare = $quoteModel->routeRates->vendorBaseFare + $extraMinutesFare;
		}
		$this->optimiseUnutilised($quoteModel);
	}

	public function calRule1($chargeableDistance, $quotedDistance, $tripDistance, $extraDistance)
	{
		$arr = [];

		$quotedDistance = max([$quotedDistance, $chargeableDistance - $extraDistance]);

		$tripDistanceFare	 = $this->prr_rate_per_km * $quotedDistance;
		$extraDistanceFare	 = round($extraDistance * (($this->prr_rate_per_km / 2) + 1));

		$arr['tot']					 = $tripDistanceFare + $extraDistanceFare;
		$arr['chargeableDistance']	 = $chargeableDistance;
		$arr['quotedDistance']		 = $quotedDistance;
		return $arr;
	}

	public function calRule2($chargeableDistance, $extraRunningDistance, $minChargeableDistance, $quotedDistance)
	{
		$arr					 = [];
		$fullChargeDistance		 = max([$minChargeableDistance, $quotedDistance]);
		$partialChargeDistance	 = max([$chargeableDistance - $fullChargeDistance, 0]);
		$quotedDistance			 = max([$chargeableDistance - $extraRunningDistance, $quotedDistance]);

		$extraRunningRatePerKM		 = (($this->prr_rate_per_km / 2) + 1);
		$tripDistanceFare			 = $this->prr_rate_per_km * $fullChargeDistance;
		$extraDistanceFare			 = round($partialChargeDistance * $extraRunningRatePerKM);
		$arr['tot']					 = $tripDistanceFare + $extraDistanceFare;
		$arr['chargeableDistance']	 = $chargeableDistance;
		$arr['quotedDistance']		 = $quotedDistance;
		return $arr;
	}

	public function calRule11($chargeableDistance, $extraRunningDistance, $minChargeableDistance, $quotedDistance)
	{
		$arr					 = [];
		$fullChargeDistance		 = max([$minChargeableDistance, $quotedDistance]);
		$extraRunningDistance	 = max([$chargeableDistance - $quotedDistance, 0]);
		$partialChargeDistance	 = max([$chargeableDistance - $fullChargeDistance, 0]);
		$quotedDistance			 = max([$chargeableDistance - $extraRunningDistance, $quotedDistance]);

		$extraRunningRatePerKM		 = (($this->prr_rate_per_km / 2) + 1);
		$tripDistanceFare			 = $this->prr_rate_per_km * $fullChargeDistance;
		$extraDistanceFare			 = round($partialChargeDistance * $extraRunningRatePerKM);
		$arr['tot']					 = $tripDistanceFare + $extraDistanceFare;
		$arr['chargeableDistance']	 = $fullChargeDistance;
		$arr['quotedDistance']		 = $quotedDistance;
		return $arr;
	}

	public function calRule3($chargeableDistance, $quotedDistance, $tripDistance, $extraDistance)
	{
		$arr = [];
		if ($chargeableDistance > $quotedDistance - $extraDistance)
		{
			$quotedDistance = max([$tripDistance, $quotedDistance - $extraDistance]);
		}
		$tripDistanceFare			 = $this->prr_rate_per_km * $quotedDistance;
		$extraDistanceFare			 = round(max([($chargeableDistance - $quotedDistance), 0]) * (($this->prr_rate_per_km / 2) + 1));
		$arr['tot']					 = $tripDistanceFare + $extraDistanceFare;
		$arr['chargeableDistance']	 = $chargeableDistance;
		$arr['quotedDistance']		 = $quotedDistance;
		return $arr;
	}

	public function calRule4($chargeableDistance, $quotedDistance, $tripDistance, $extraDistance)
	{
		$arr = [];
		if ($chargeableDistance > $quotedDistance - $extraDistance)
		{
			$quotedDistance = max([$tripDistance, $quotedDistance - $extraDistance]);
		}
		$tripDistanceFare			 = $this->prr_rate_per_km * $quotedDistance;
		$extraDistanceFare			 = round(max([($chargeableDistance - $quotedDistance), 0]) * (($this->prr_rate_per_km / 2) + 1));
		$arr['tot']					 = $tripDistanceFare + $extraDistanceFare;
		$arr['chargeableDistance']	 = $chargeableDistance;
		$arr['quotedDistance']		 = $quotedDistance;
		return $arr;
	}

	public function calRule6($chargeableDistance, $quotedDistance, $tripDistance, $extraDistance, $prrMinKm = 0)
	{
		$arr = [];
		if ($chargeableDistance > $quotedDistance - $extraDistance)
		{
			$quotedDistance = max([$tripDistance, $quotedDistance - $extraDistance]);
		}
		$maxDistance		 = max([$chargeableDistance, $prrMinKm]);
		$tripDistanceFare	 = $this->prr_min_base_amount; //$this->prr_rate_per_km * $quotedDistance;
		if ($maxDistance > 0)
		{
			$tripDistanceFare += $maxDistance * $this->prr_rate_per_km;
		}
		$arr['tot']					 = $tripDistanceFare;
		$arr['chargeableDistance']	 = $chargeableDistance;
		$arr['quotedDistance']		 = $quotedDistance;
		return $arr;
	}

	public function calRule7($chargeableDistance, $quotedDistance, $tripDistance, $extraDistance)
	{
		$arr = [];
		if ($chargeableDistance > $quotedDistance - $extraDistance)
		{
			$quotedDistance = max([$tripDistance, $quotedDistance - $extraDistance]);
		}
		$tripDistanceFare			 = $this->prr_rate_per_minute * $this->prr_min_duration;
		$arr['tot']					 = $tripDistanceFare;
		$arr['chargeableDistance']	 = $chargeableDistance;
		$arr['quotedDistance']		 = $quotedDistance;
		return $arr;
	}

	public function calRule8($chargeableDistance, $quotedDistance, $tripDistance, $extraDistance)
	{
		$arr = [];
		if ($chargeableDistance > $quotedDistance - $extraDistance)
		{
			$quotedDistance = max([$tripDistance, $quotedDistance - $extraDistance]);
		}
		$tripDistanceFare			 = max(($this->prr_rate_per_km * $quotedDistance), ($this->prr_rate_per_minute * $this->prr_min_duration));
		$arr['tot']					 = $tripDistanceFare;
		$arr['chargeableDistance']	 = $chargeableDistance;
		$arr['quotedDistance']		 = $quotedDistance;
		return $arr;
	}

	public function calRule9($chargeableDistance, $quotedDistance, $tripDistance, $extraDistance)
	{
		$arr = [];
		if ($chargeableDistance > $quotedDistance - $extraDistance)
		{
			$quotedDistance = max([$tripDistance, $quotedDistance - $extraDistance]);
		}
		$tripDistanceFare			 = ($this->prr_rate_per_km * $quotedDistance) + ($this->prr_rate_per_minute * $this->prr_min_duration);
		$arr['tot']					 = $tripDistanceFare;
		$arr['chargeableDistance']	 = $chargeableDistance;
		$arr['quotedDistance']		 = $quotedDistance;
		return $arr;
	}

	public function calRule10($chargeableDistance, $quotedDistance, $tripDistance, $extraDistance)
	{
		$arr = [];
		if ($chargeableDistance > $quotedDistance - $extraDistance)
		{
			$quotedDistance = max([$tripDistance, $quotedDistance - $extraDistance]);
		}
		$tripDistanceFare			 = min(($this->prr_rate_per_km * $quotedDistance), ($this->prr_rate_per_minute * $this->prr_min_duration));
		$arr['tot']					 = $tripDistanceFare;
		$arr['chargeableDistance']	 = $chargeableDistance;
		$arr['quotedDistance']		 = $quotedDistance;
		return $arr;
	}

	public function updateDescData($prrId = null)
	{
		$models = [];
		if ($prrId > 0)
		{
			$model		 = PriceRule::model()->findByPk($prrId);
			$models[]	 = $model;
		}
		else
		{
			$models = PriceRule::model()->findAll();
		}
		$calculationType = $this->calculation_type;
		foreach ($models as $key => $model)
		{
			$desc	 = [];
			$desc[]	 = 'Base Amount:' . $model->prr_min_base_amount;
			if (($model->prr_rate_per_km > 0))
			{
				$desc[] = 'Rate:' . $model->prr_rate_per_km . '/KM';
			}
			if (($model->prr_rate_per_km_extra > 0))
			{
				$desc[] = 'Extra Rate:' . $model->prr_rate_per_km_extra . '/KM';
			}
			if (($model->prr_min_km > 0))
			{
				$desc[] = 'Minimum:' . $model->prr_min_km . 'KM/Day';
			}
			if (($model->prr_max_km_day > 0))
			{
				$desc[] = 'Max:' . $model->prr_max_km_day . 'KM/Day';
			}
			if (($model->prr_day_driver_allowance > 0))
			{
				$desc[] = 'Day Driver Allowance:' . $model->prr_day_driver_allowance;
			}
			if (($model->prr_night_driver_allowance > 0))
			{
				$desc[] = 'Night Driver Allowance:' . $model->prr_night_driver_allowance;
			}
			if (($model->prr_driver_allowance_km_limit > 0))
			{
				$desc[] = 'Driver Allowance KM Limit:' . $model->prr_driver_allowance_km_limit;
			}
			if (($model->prr_night_start_time > 0))
			{
				$desc[] = 'Night Start Time:' . date('gA', strtotime($model->prr_night_start_time));
			}
			if (($model->prr_night_end_time > 0))
			{
				$desc[] = 'Night End Time:' . date('gA', strtotime($model->prr_night_end_time));
			}
			if (($model->prr_calculation_type > 0))
			{
				$desc[] = 'Calculation Type:' . $calculationType[$model->prr_calculation_type];
			}
			if (($model->prr_min_pickup_duration > 0))
			{
				$desc[] = 'Min Pickup Duration:' . $model->prr_min_pickup_duration . 'min';
			}
			$fullDesc = implode(' | ', $desc);
			if ($fullDesc == $model->prr_cab_desc)
			{
				echo "Description for Price Rule Id : " . $model->prr_id . " is same as before.";
			}
			else
			{
				$model->prr_cab_desc = $fullDesc;
				if ($model->save())
				{
					echo "Description for Price Rule Id : " . $model->prr_id . " updated successfully.";
				}
				else
				{
					echo "Description for Price Rule Id : " . $model->prr_id . " not updated.";
				}
			}
			echo "<br>";
		}
		echo "Done";
	}

	public function addLog($oldData, $newData)
	{
		if ($oldData)
		{
			$getDifference	 = array_diff_assoc($oldData, $newData);
			$remark			 = $this->prr_log;
			$dt				 = date('Y-m-d H:i:s');
			$user			 = Yii::app()->user->getId();
//if ($remark) {
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
//}
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

	public static function getRulesByAreaAndCab($areaType, $areaId, $cab)
	{
		$aprId	 = '';
		$result	 = AreaPriceRule::getDataByArea($areaType, $areaId, $cab);

		$fieldTypes	 = AreaPriceRule::getFieldNamebyTripType();
		$data		 = [];

		if ($result)
		{
			$data = $result;
		}
		$aprId = $data['apr_id'];
		foreach ($fieldTypes as $tripType => $fieldName)
		{
			if (!in_array($tripType, [5, 7]))
			{
				$isReplicated = false;

				if (!isset($data[$fieldName]) || $data[$fieldName] == 0 || $data[$fieldName] == "")
				{
					$priceRuleModel1 = self::getParentData($areaType, $areaId, $cab, $tripType);
					$isReplicated	 = true;
				}
				if ($data[$fieldName] > 0)
				{
					$priceRuleModel1		 = PriceRule::model()->findByPk($data[$fieldName]);
					$priceRuleModel1->aprId	 = $aprId;
				}

				if (!$priceRuleModel1)
				{
					$priceRuleModel1 = new PriceRule();
				}

				if ($isReplicated)
				{
					$priceRuleModel1->setIsNewRecord(true);
					$priceRuleModel1->prr_id = null;
				}

				$priceRuleModel1->prr_cab_type	 = $cab;
				$priceRuleModel1->areaId		 = $areaId;
				$priceRuleModel1->areaType		 = $areaType;
				$arrRules[$tripType]			 = $priceRuleModel1;
			}
		}

		return $arrRules;
	}

	public function validatePriceRule()
	{
		$success = true;
		if ($this->prr_rate_per_km == 0 || $this->prr_rate_per_km == '')
		{
			$success = false;
		}
		if ($this->prr_rate_per_km_extra == 0 || $this->prr_rate_per_km_extra == '')
		{
			$success = false;
		}
		if ($this->prr_min_km == 0 || $this->prr_min_km == '')
		{
			$success = false;
		}
		if ($this->prr_night_start_time == 0 || $this->prr_night_start_time == '')
		{
			$success = false;
		}
		if ($this->prr_night_end_time == 0 || $this->prr_night_end_time == '')
		{
			$success = false;
		}
		if ($this->prr_calculation_type == 0 || $this->prr_calculation_type == '')
		{
			$success = false;
		}
		return $success;
	}

	public function checkExistingPriceRule($prrId, $aprId)
	{
		$success = 0;

		$sql = "SELECT apr_id FROM area_price_rule  WHERE apr_oneway_id = $prrId AND apr_id<>$aprId";
		$id1 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		if (!$id1)
		{
			$id1 = 0;
		}

		$sql = "SELECT apr_id FROM area_price_rule  WHERE apr_return_id = $prrId AND apr_id<>$aprId";
		$id2 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		if (!$id2)
		{
			$id2 = 0;
		}
		$sql = "SELECT apr_id FROM area_price_rule  WHERE apr_multitrip_id = $prrId AND apr_id<>$aprId";
		$id3 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		if (!$id3)
		{
			$id3 = 0;
		}
		$sql = "SELECT apr_id FROM area_price_rule  WHERE apr_airport_id = $prrId AND apr_id<>$aprId";
		$id4 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		if (!$id4)
		{
			$id4 = 0;
		}
		$sql = "SELECT apr_id FROM area_price_rule  WHERE apr_dr_4_40 = $prrId AND apr_id<>$aprId";
		$id9 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		if (!$id9)
		{
			$id9 = 0;
		}
		$sql	 = "SELECT apr_id FROM area_price_rule  WHERE apr_dr_8_80 = $prrId AND apr_id<>$aprId";
		$id10	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		if (!$id10)
		{
			$id10 = 0;
		}
		$sql	 = "SELECT apr_id FROM area_price_rule  WHERE apr_dr_12_120 = $prrId AND apr_id<>$aprId";

		if (!$id16)
		{
			$id16 = 0;
		}
		$sql	 = "SELECT apr_id FROM area_price_rule  WHERE apr_dr_10_100 = $prrId AND apr_id<>$aprId";
		$id11	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		if (!$id11)
		{
			$id11 = 0;
		}

		if (($id1 + $id2 + $id3 + $id4 + $id9 + $id16 + $id10 + $id11) > 0)
		{
			$success = 1;
		}
		return $success;
	}

	public function checkSimilar()
	{
		$sql	 = "SELECT * FROM price_rule WHERE prr_active = $this->prr_active
					AND prr_cab_type=$this->prr_cab_type 
					AND prr_trip_type=$this->prr_trip_type 
					AND prr_rate_per_km=$this->prr_rate_per_km 
					AND prr_rate_per_minute=$this->prr_rate_per_minute
					AND prr_rate_per_km_extra=$this->prr_rate_per_km_extra
					AND prr_rate_per_minute_extra=$this->prr_rate_per_minute_extra
					AND prr_min_km=$this->prr_min_km
					AND prr_min_duration=$this->prr_min_duration
					AND prr_min_base_amount=$this->prr_min_base_amount
					AND prr_min_km_day=$this->prr_min_km_day
					AND prr_max_km_day=$this->prr_max_km_day
					AND prr_day_driver_allowance=$this->prr_day_driver_allowance 
					AND prr_night_driver_allowance=$this->prr_night_driver_allowance
					AND prr_driver_allowance_km_limit=$this->prr_driver_allowance_km_limit
					AND prr_night_start_time= '$this->prr_night_start_time'
					AND prr_night_end_time='$this->prr_night_end_time'
					AND prr_calculation_type=$this->prr_calculation_type
					AND prr_min_pickup_duration=$this->prr_min_pickup_duration";
		$model	 = PriceRule::model()->findBySql($sql);
		return $model;
	}

	/**
	 * 
	 * @param int $fromCity
	 * @param int $tripType
	 * @param int $cabType
	 * @return PriceRule
	 */
	public static function getByCity($fromCity, $tripType, $cabType, $toCity = 0)
	{
		$key = "PriceRule::getByCity:{$fromCity}_{$toCity}_{$tripType}_{$cabType}";
		if (isset($GLOBALS[$key]))
		{
			$priceRule = $GLOBALS[$key];
			goto result;
		}

		$areaId		 = $fromCity;
		$areaType	 = 3;

		$rutId = Route::getIdByCities($fromCity, $toCity);

		if ($rutId != '')
		{
			$areaId		 = $rutId;
			$areaType	 = 5;
		}

		$priceRule = self::findByAreaCabTripType($areaType, $areaId, $cabType, $tripType);

		if ($priceRule != '')
		{
			$GLOBALS[$key] = $priceRule;
			Logger::trace("PriceRule::getByCity:priceRule=>" . $priceRule->prr_id);
		}
		result:
		return $priceRule;
	}

	/**
	 * 
	 * @param int $fromCity
	 * @param int $tripType
	 * @param int $cabType
	 * @return PriceRule
	 */
	public static function findByAreaParentCabType($areaType, $areaId, $tripType, $cabType)
	{
		$key = "PriceRule::findByAreaParentCabType:{$areaType}_{$areaId}_{$tripType}_{$cabType}";
		if (isset($GLOBALS[$key]))
		{
			$priceRule = $GLOBALS[$key];
			goto result;
		}

		$row = SvcClassVhcCat::model()->findByPk($cabType);
		if (!$row || $row->scv_parent_id <= 0)
		{
			$priceRule = null;
			goto result;
		}

		$parentCabType	 = $row->scv_parent_id;
		$parentPriceRule = self::findByAreaCabTripType($areaType, $areaId, $parentCabType, $tripType);

		if ($parentPriceRule == null)
		{
			goto result;
		}

		$priceRule = ServiceClassRule::getAreaPriceRuleWithMarkUp($areaType, $areaId, $cabType, $parentPriceRule);

		result:
		return $priceRule;
	}

	public static function findByAreaCabTripType($areaType, $areaId, $cabType, $tripType)
	{
		Logger::trace("AreaPriceRule::findRulesByAreaType({$areaType}, {$areaId}, {$cabType}, {$tripType})");
		$key = "PriceRule::findByAreaCabTripType:{$areaType}_{$areaId}_{$tripType}_{$cabType}";
		if (isset($GLOBALS[$key]))
		{
			$priceRule = $GLOBALS[$key];
			goto result;
		}

		$areaPriceRule = AreaPriceRule::findRulesByAreaType($areaType, $areaId, $cabType);
		if ($areaPriceRule != [] && isset($areaPriceRule[$tripType]))
		{
			$id			 = $areaPriceRule[$tripType]['id'];
			$priceRule	 = PriceRule::model()->findByPk($id);
		}

		if ($priceRule == '')
		{
			$priceRule = self::findByAreaParentCabType($areaType, $areaId, $tripType, $cabType, $areaPriceRule);
		}

		if ($priceRule != '')
		{
			$GLOBALS[$key] = $priceRule;
			Logger::trace('AreaPriceRule::findByAreaCabTripType->priceRule' . $priceRule->prr_id);
		}
		result:
		return $priceRule;
	}

	/**
	 * 
	 * @param int $city
	 * @param array $cabType
	 * @param array $tripTypeArr
	 * @return array
	 */
	public static function getByCityCabTripType($city, $cabType = [], $tripTypeArr = [])
	{
		$cabCache	 = implode('-', $cabType);
		$tripCache	 = implode('-', $tripTypeArr);
		$key		 = "PriceRule::getByCityCabTripType:{$city}_{$cabCache}_{$tripCache}";
		$data		 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}

		$cabTypeStr = '';
		if (sizeof($cabType) > 0)
		{
			$cabTypeStr .= ' AND  apr_cab_type IN (' . implode(',', $cabType) . ')';
		}
		$tripTypeStr = '';
		if (sizeof($tripTypeArr) > 0)
		{
			$tripTypeStr .= ' AND  prr_trip_type IN (' . implode(',', $tripTypeArr) . ')';
		}
		$strFields	 = '';
		$resFields	 = AreaPriceRule::getFieldNamebyTripType($tripTypeArr);
		foreach ($tripTypeArr as $tripType)
		{
			$strFields .= " OR pr.prr_id=" . $resFields[$tripType];
		}

		$params = ['ctyId' => $city];

		$sql = "SELECT distinct prr_cab_type,prr_trip_type,prr_rate_per_km ,prr_rate_per_minute 
				FROM cities 
				JOIN states ON states.stt_id=cities.cty_state_id
				LEFT JOIN zone_cities ON zone_cities.zct_cty_id=cities.cty_id
				INNER JOIN area_price_rule ON ((cities.cty_id=area_price_rule.apr_area_id AND area_price_rule.apr_area_type=3) 
					OR (zone_cities.zct_zon_id=area_price_rule.apr_area_id AND area_price_rule.apr_area_type=1)     
					OR (states.stt_id=area_price_rule.apr_area_id AND area_price_rule.apr_area_type=2)
					OR (states.stt_zone=area_price_rule.apr_area_id AND area_price_rule.apr_area_type=4))  
 				INNER JOIN price_rule pr on (pr.prr_id = apr_oneway_id $strFields)  AND pr.prr_active =1
                WHERE apr_active=1 
					AND cty_id=:ctyId 
					$cabTypeStr $tripTypeStr  ";

		$res	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		$data	 = [];
		foreach ($res as $value)
		{
			$data[$value['prr_cab_type']][$value['prr_trip_type']] = ['prr_rate_per_km' => $value['prr_rate_per_km'], 'prr_rate_per_minute' => $value['prr_rate_per_minute']];
		}
		Yii::app()->cache->set($key, $data, 24 * 30 * 60 * 60, new CacheDependency("rate"));

		result:
		return $data;
	}

	public static function getParentData($areaType, $areaId, $cab, $tripType = null)
	{
		$types = AreaPriceRule::model()->convertType;
		foreach ($types as $key => $type)
		{
			$priceRuleModel	 = self::findByAreaCabTripType($areaType, $areaId, $cab, $tripType);
			$aprId			 = AreaPriceRule::getApr($areaId, $areaType, $priceRuleModel->prr_trip_type);
			if ($aprId > 0)
			{
				$priceRuleModel->aprId = $aprId;
			}
			return $priceRuleModel;
		}
	}

	public static function getNextLevelAreaDetails($keyType, $areaId, $rule)
	{
		$types	 = AreaPriceRule::model()->convertType;
		$data	 = [];

		$result = States::getDataById($areaId, $rule);
		if ($keyType == 1)
		{
			$data['id']		 = $result['sttid'];
			$data['type']	 = $types[$keyType];
		}
		if ($keyType == 2)
		{
			$data['id']		 = $result['region'];
			$data['type']	 = $types[$keyType];
		}
		if ($keyType == 3)
		{
			$data['id']		 = $result['zonid'];
			$data['type']	 = $types[$keyType];
		}
		return $data;
	}

}
