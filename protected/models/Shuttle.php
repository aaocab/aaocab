<?php

/**
 * This is the model class for table "shuttle".
 *
 * The followings are the available columns in table 'shuttle':
 * @property integer $slt_id
 * @property string $slt_pickup_datetime
 * @property integer $slt_from_city
 * @property integer $slt_to_city
 * @property string $slt_pickup_location
 * @property string $slt_drop_location
 * @property string $slt_pickup_lat
 * @property string $slt_pickup_long
 * @property string $slt_drop_lat
 * @property string $slt_drop_long
 * @property integer $slt_time_slot
 * @property integer $slt_seat_availability
 * @property integer $slt_price_per_seat
 * @property integer $slt_gst
 * @property integer $slt_toll_tax
 * @property integer $slt_driver_allowance
 * @property integer $slt_state_tax
 * @property integer $slt_base_fare
 * @property integer $slt_vendor_amount
 * @property integer $slt_vnd_id
 * @property integer $slt_created_by
 * @property string $slt_created_at
 * @property integer $slt_status
 */
class Shuttle extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public $slt_to_city_id1, $slt_from_city_id1, $pickup_start, $pickup_end, $slt_availability;
	public $timeslot_arr	 = ['1' => 'Full hour', '2' => 'Half hour', '3' => '15 min'];

	public function tableName()
	{
		return 'shuttle';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
//			array('slt_pickup_datetime', 'required'),
			['slt_from_city,slt_to_city,slt_availability,
					slt_seat_availability, slt_time_slot,
					slt_pickup_lat, slt_pickup_long, slt_drop_lat, slt_drop_long,
					slt_price_per_seat,  slt_vendor_amount,
					slt_pickup_location, slt_drop_location', 'required', 'on' => 'addnew'],
			['slt_pickup_lat', 'validateLatLong', 'on' => 'addnew'],
			['slt_pickup_datetime', 'validatePickupTime', 'on' => 'addnew'],
			array('slt_from_city, slt_to_city, slt_time_slot, slt_seat_availability, 
					slt_price_per_seat, slt_gst, slt_toll_tax, slt_state_tax,slt_driver_allowance, slt_base_fare,slt_vendor_amount, 
					slt_vnd_id, slt_created_by, slt_status', 'numerical', 'integerOnly' => true),
			array('slt_pickup_location, slt_drop_location', 'length', 'max' => 255),
			array('slt_pickup_lat, slt_pickup_long, slt_drop_lat, slt_drop_long', 'length', 'max' => 100),
			array('slt_created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('slt_id, slt_pickup_datetime, slt_from_city,pickup_start,pickup_end, slt_to_city, slt_pickup_location, slt_drop_location, slt_pickup_lat, slt_pickup_long, slt_drop_lat, slt_drop_long, slt_time_slot, slt_seat_availability, 
					slt_price_per_seat, slt_gst, slt_toll_tax, slt_driver_allowance, slt_state_tax, slt_base_fare,slt_vendor_amount,
					slt_vnd_id, slt_created_by, slt_created_at, slt_status', 'safe', 'on' => 'search'),
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

	public function validatePickupTime($attribute, $params)
	{
		$result = true;
		if (trim($this->slt_pickup_datetime) == '00:00:00')
		{
			$this->addError('slt_pickup_datetime', 'Pickup Date Range is not selected');
			$result = false;
		}

		return $result;
	}

	public function validateLatLong($attribute, $params)
	{
		$result = true;
		if ($this->slt_pickup_lat == '' || Filter::validateLatitude($this->slt_pickup_lat) == 0)
		{
			$this->addError('slt_pickup_lat', 'Correct Pickup latitude is required ');
			$result = false;
		}
		if ($this->slt_pickup_long == '' || Filter::validateLongitude($this->slt_pickup_long) == 0)
		{
			$this->addError('slt_pickup_long', 'Correct Pickup longitude is required ');
			$result = false;
		}

		if ($this->slt_drop_lat == '' || Filter::validateLatitude($this->slt_drop_lat) == 0)
		{
			$this->addError('slt_drop_lat', 'Correct Drop latitude is required ');
			$result = false;
		}
		if ($this->slt_drop_long == '' || Filter::validateLongitude($this->slt_drop_long) == 0)
		{
			$this->addError('slt_drop_long', 'Correct Drop longitude is required ');
			$result = false;
		}
		return $result;
	}

	public static function map_cab_type($noOfSeat = '')
	{
		$dataList = [4=> VehicleCategory::COMPACT_ECONOMIC,6=> VehicleCategory::SUV_ECONOMIC];
		if ($noOfSeat != '')
		{
			return $dataList[$noOfSeat];
		}

		return $dataList;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'slt_id'				 => 'Slt',
			'slt_pickup_datetime'	 => 'Pickup Datetime',
			'slt_from_city'			 => 'From City',
			'slt_to_city'			 => 'To City',
			'slt_pickup_location'	 => 'Pickup Location',
			'slt_drop_location'		 => 'Drop Location',
			'slt_pickup_lat'		 => 'Pickup Lat',
			'slt_pickup_long'		 => 'Pickup Long',
			'slt_drop_lat'			 => 'Drop Lat',
			'slt_drop_long'			 => 'Drop Long',
			'slt_time_slot'			 => 'Time Slot',
			'slt_seat_availability'	 => 'Seat Availability',
			'slt_price_per_seat'	 => 'Price Per Seat',
			'slt_gst'				 => 'GST',
			'slt_toll_tax'			 => 'Toll Tax',
			'slt_driver_allowance'	 => 'Driver Allowance',
			'slt_state_tax'			 => 'State Tax',
			'slt_base_fare'			 => 'Base Fare',
			'slt_vendor_amount'		 => 'Full Vendor Amount',
			'slt_vnd_id'			 => 'Vendor',
			'slt_created_by'		 => 'Created By',
			'slt_created_at'		 => 'Created At',
			'slt_availability'		 => 'No of Shuttle',
			'slt_status'			 => 'Status',
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

		$criteria->compare('slt_id', $this->slt_id);
		$criteria->compare('slt_pickup_datetime', $this->slt_pickup_datetime, true);
		$criteria->compare('slt_from_city', $this->slt_from_city);
		$criteria->compare('slt_to_city', $this->slt_to_city);
		$criteria->compare('slt_pickup_location', $this->slt_pickup_location, true);
		$criteria->compare('slt_drop_location', $this->slt_drop_location, true);
		$criteria->compare('slt_pickup_lat', $this->slt_pickup_lat, true);
		$criteria->compare('slt_pickup_long', $this->slt_pickup_long, true);
		$criteria->compare('slt_drop_lat', $this->slt_drop_lat, true);
		$criteria->compare('slt_drop_long', $this->slt_drop_long, true);
		$criteria->compare('slt_time_slot', $this->slt_time_slot);
		$criteria->compare('slt_seat_availability', $this->slt_seat_availability);
		$criteria->compare('slt_price_per_seat', $this->slt_price_per_seat);
		$criteria->compare('slt_gst', $this->slt_gst);
		$criteria->compare('slt_toll_tax', $this->slt_toll_tax);
		$criteria->compare('slt_driver_allowance', $this->slt_driver_allowance);
		$criteria->compare('slt_state_tax', $this->slt_state_tax);
		$criteria->compare('slt_base_fare', $this->slt_base_fare);
		$criteria->compare('slt_vnd_id', $this->slt_vnd_id);
		$criteria->compare('slt_created_by', $this->slt_created_by);
		$criteria->compare('slt_created_at', $this->slt_created_at, true);
		$criteria->compare('slt_status', $this->slt_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Shuttle the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function splitTime($duration, $startTime = "00:00", $endTime = "23:59")
	{
		$returnArray = array();
		$startTime	 = strtotime($startTime);
		$endTime	 = strtotime($endTime);
		$durationVal = $duration * 60;
		while ($startTime <= $endTime)
		{
			$returnArray[date("H:i", $startTime)]	 = date("h:iA", $startTime);
			$startTime								 += $durationVal;
		}
		return $returnArray;
	}

	public static function populateFromModel($model)
	{
		$errors = BookingRoute::validateRoutes($model->bookingRoutes, $model->bkg_booking_type, null);
		if (!empty($errors))
		{
			throw new Exception(json_encode($errors), ReturnSet::ERROR_VALIDATION);
		}
		$arr			 = [];
		$arr['pickDate'] = date('Y-m-d', strtotime($model->bkg_pickup_date));
		$arr['fromCity'] = $model->bkg_from_city_id;
		$arr['toCity']	 = $model->bkg_to_city_id;
		$data			 = Shuttle::model()->fetchData($arr, false);
		return $data;
	}

	public function fetchData($arr, $showAvailableSeatsOnly = false)
	{
		$where	 = '';
		$having	 = '';
		if ($arr['slt_id'] > 0)
		{
			$where .= ' AND slt.slt_id = ' . $arr['slt_id'];
		}
		if ($arr['fromCity'] > 0)
		{
			$where .= ' AND slt.slt_from_city = ' . $arr['fromCity'];
		}

		if ($arr['toCity'] > 0)
		{
			$where .= ' AND slt.slt_to_city = ' . $arr['toCity'];
		}
		if ($arr['pickDate'] != '')
		{
			$where .= " AND date(slt.slt_pickup_datetime) =  '" . $arr['pickDate'] . "'";
		}
		$selectCabTypeId = "        
		CASE slt.slt_seat_availability ";
        $mapCabs = Shuttle::map_cab_type();
		foreach ($mapCabs as $k1 => $v1)
		{
			$selectCabTypeId .= " WHEN $k1 THEN $v1 ";
		}
		$selectCabTypeId .= "END vht_id";

		$selectCabType = "        
		CASE slt.slt_seat_availability ";
        $mapCabs1 = Shuttle::map_cab_type();
		foreach ($mapCabs1 as $k2 => $v2)
		{
			$selectCabType .= " WHEN $k2 THEN $v2 ";
		}
		$selectCabType .= "END vht_car_type";

		if ($showAvailableSeatsOnly)
		{
			$having = ' HAVING   slt.slt_seat_availability >= seat_booked AND available_seat > 0';
		}

		$sql = "SELECT   slt.slt_id, slt.slt_from_city, slt.slt_to_city, fct.cty_name fromCity, tct.cty_name toCity, 
				slt.slt_seat_availability, 
				$selectCabTypeId , $selectCabType,
				slt.slt_pickup_datetime,
				slt.slt_pickup_location, slt.slt_drop_location,
				slt.slt_pickup_lat, slt.slt_pickup_long, slt.slt_drop_lat, slt.slt_drop_long,
				slt.slt_price_per_seat, slt.slt_gst, slt.slt_toll_tax, slt.slt_state_tax,
				slt.slt_driver_allowance, slt.slt_base_fare,slt.slt_vendor_amount, 
				rut.rut_estm_distance trip_distance,rut.rut_estm_time trip_duration,
				count(bkg.bkg_id) seat_booked, (slt.slt_seat_availability - count(bkg.bkg_id)) available_seat
				FROM shuttle slt
					 LEFT JOIN booking bkg ON bkg.bkg_shuttle_id = slt.slt_id AND bkg_status IN (1,2,3,5,6,7,15) 
					 INNER JOIN cities fct ON slt.slt_from_city = fct.cty_id
					 INNER JOIN cities tct ON slt.slt_to_city = tct.cty_id
					 LEFT JOIN route rut ON rut.rut_from_city_id = fct.cty_id 
						AND rut.rut_to_city_id = tct.cty_id AND rut_active = 1
			WHERE    slt_status = 1 $where    
			GROUP BY slt_id
			$having
			ORDER by slt.slt_pickup_datetime,slt.slt_id
			";

		$result = DBUtil::queryAll($sql);
		return $result;
	}

	public function getJSONList($arr)
	{
		{
			$where = '';

			$fromCity	 = $arr['fcityVal'];
			$toCity		 = $arr['tcityVal'];
			$where		 .= " AND slt.slt_from_city = $fromCity";
			$where		 .= " AND slt.slt_to_city = $toCity";

			$where .= " AND date(slt_pickup_datetime)='" . $arr['pdate'] . "'";


			if ($arr['fcityLoc'] != '')
			{
				$latLong = explode(',', $arr['fcityLoc']);
				if (sizeof($latLong) == 2)
				{
					$where	 .= " AND  slt_pickup_lat='" . $latLong[0] . "'";
					$where	 .= " AND  slt_pickup_long='" . $latLong[1] . "'";
				}
			}
			if ($arr['tcityLoc'] != '')
			{
				$latLong = explode(',', $arr['tcityLoc']);
				if (sizeof($latLong) == 2)
				{
					$where	 .= " AND  slt_drop_lat='" . $latLong[0] . "'";
					$where	 .= " AND  slt_drop_long='" . $latLong[1] . "'";
				}
			}

			$sql = "SELECT  slt.slt_id, slt.slt_from_city, slt.slt_to_city, 
				slt.slt_seat_availability, slt.slt_pickup_datetime,
				slt.slt_price_per_seat, count(bkg.bkg_id) seat_booked, (slt.slt_seat_availability - count(bkg.bkg_id)) available_seat
				FROM     shuttle slt
					 LEFT JOIN booking bkg ON bkg.bkg_shuttle_id = slt.slt_id AND bkg_status IN (1,2,3,5,6,7,15) 
            WHERE  slt_status = 1  $where  		available_seat > 0	
			GROUP BY slt_id HAVING available_seat > 0";

			$recordSet	 = DBUtil::queryAll($sql);
			$arrAddress	 = [];
			if (sizeof($recordSet) > 0)
			{
				foreach ($recordSet as $record)
				{
					$arrAddress[$record['slt_id']] = DateTimeFormat::DateTimeToTimePicker($record['slt_pickup_datetime']) . ' ' . $record['available_seat'] . ' seats available';
				}
				$data = CJSON::encode($arrAddress);
			}
			else
			{
				$data = CJSON::encode(['' => 'All seats are booked']);
			}
			return $data;
		}
	}

	public function getDetailbyId($slt_id)
	{
		$arr			 = ['slt_id' => $slt_id];
		$shuttleDatas	 = Shuttle::model()->fetchData($arr);
		$shuttleData	 = $shuttleDatas[0];
		$cabSeatCapacity = $shuttleData['slt_seat_availability'];
		$vhtid			 = Shuttle::map_cab_type($cabSeatCapacity);
		$cabDetails		 = SvcClassVhcCat::model()->getVctSvcList("detail",0,0,$vhtid);

		$shuttleData['cab_data'] = $cabDetails;
		return $shuttleData;
	}

	public function calculateTotalAmount($no_of_seat, $slt_id)
	{
		$arr			 = ['slt_id' => $slt_id];
		$shuttleDatas	 = Shuttle::model()->fetchData($arr);
		$price_per_seat	 = $shuttleDatas[0]['slt_price_per_seat'];
		return $no_of_seat * $price_per_seat;
	}

	public function getAvailableSeatbyId($slt_id)
	{
		$sql = "SELECT   slt.slt_id,  slt.slt_seat_availability, 				 
				count(bkg.bkg_id) seat_booked, 
				(slt.slt_seat_availability - count(bkg.bkg_id)) available_seat
				FROM shuttle slt
					 LEFT JOIN	booking bkg ON bkg.bkg_shuttle_id = slt.slt_id 
								AND bkg_status IN (1,2,3,5,6,7,15) 
			WHERE  slt_status = 1 AND slt.slt_id = $slt_id
			GROUP BY slt_id ";

		$result = DBUtil::queryRow($sql);
		return $result;
	}

	public static function getCabVendorAmountbyId($slt_id)
	{
		$sql = "SELECT   slt_id  , slt_vendor_amount from shuttle
            WHERE  slt_status = 1  AND slt_id = $slt_id   ";

		$recordSet = DBUtil::queryRow($sql);
		if (isset($recordSet['slt_vendor_amount']))
		{
			return $recordSet['slt_vendor_amount'];
		}
		return false;
	}

	public static function getNextPickupDate($fromCityId, $toCityId)
	{
		$sql = "SELECT slt_pickup_datetime FROM shuttle WHERE slt_from_city = :fromCityId AND slt_to_city = :toCityId AND slt_status = 1 AND DATE(slt_pickup_datetime) > CURRENT_DATE() ORDER BY slt_pickup_datetime LIMIT 0,1";
		$result = DBUtil::command($sql, DBUtil::SDB())->queryScalar([ 'fromCityId'=> $fromCityId,'toCityId'=> $toCityId]);
		return $result;
}


}
