<?php

/**
 * Description of CabAvailabilities
 *
 * @author Deepak
 * 
 * @property integer id
 * @property \Beans\common\DateRange $pickupDateRange
 * @property \Beans\Vendor $vendor
 * @property \Beans\Driver $driver
 * @property \Beans\common\Cab $cab 
 * @property \Beans\common\Fare $fare
 * @property \Beans\common\City $pickupCity
 * @property \Beans\common\City $dropCity
 * @property integer $isOneWay 
 * @property integer $isShared
 * @property integer $isLocalTrip
 * @property string $createDate
 */

namespace Beans\common;

class CabAvailabilities
{

	public $id;

	/** @var \Beans\common\DateRange $pickupDateRange */
	public $pickupDateRange;

	/** @var \Beans\Vendor $vendor */
	public $vendor;

	/** @var \Beans\Driver $driver */
	public $driver;

	/** @var \Beans\common\Cab $cab */
	public $cab;

	/** @var \Beans\booking\Fare $fare */
	public $fare;

	/** @var \Beans\common\City $pickupCity */
	public $pickupCity;

	/** @var \Beans\common\City $dropCity */
	public $dropCity;
	public $isOneWay, $isShared, $isLocalTrip;
	public $createDate;

	public static function setModelData($model)
	{ /** @var \CabAvailabilities $model */
		$obj = new CabAvailabilities();

		$obj->vendor	 = new \Beans\Vendor();
		$obj->vendor->id = $model->cav_vendor_id;

		$obj->driver	 = new \Beans\Driver();
		$obj->driver->id = $model->cav_driver_id;

		$obj->cab		 = new \Beans\common\Cab();
		$obj->cab->id	 = $model->cav_cab_id;

		$obj->pickupCity	 = new \Beans\common\City();
		$obj->pickupCity->id = $model->cav_from_city;

		$obj->dropCity		 = new \Beans\common\City();
		$obj->dropCity->id	 = $model->cav_to_cities;

		$obj->pickupDateRange			 = new \Beans\common\DateRange();
		$obj->pickupDateRange->fromDate	 = $model->cav_date_time;
		$obj->pickupDateRange->toDate	 = date('Y-m-d H:i:s', strtotime($model->cav_date_time . '+' . $model->cav_duration . ' hour'));
		$obj->fare						 = new \Beans\booking\Fare();
		$obj->fare->vendorAmount		 = $model->cav_amount;
		if($model->cav_total_amount > 0)
		{
			$obj->fare->totalAmount = $model->cav_total_amount;
		}
		$obj->createDate = $model->cav_created_at;
		return $obj;
	}

	public function getRegisterModel($model = null)
	{

		$duration = $this->pickupDateRange->getHourDiff();
		if($model == null)
		{
			$model = new \CabAvailabilities('insert');
		}
		$model->cav_status		 = 1;
		$model->cav_date_time	 = $this->pickupDateRange->fromDate;
		$model->cav_duration	 = $duration;
		$model->cav_from_city	 = $this->pickupCity->id;
		$model->cav_to_cities	 = $this->dropCity->id;
		$model->cav_amount		 = $this->fare->vendorAmount;
		if($this->fare->totalAmount > 0)
		{
			$model->cav_total_amount = $this->fare->totalAmount;
		}
		$model->cav_vendor_id	 = $this->vendor->id;
		$model->cav_cab_id		 = $this->cab->id;
		$model->cav_driver_id	 = $this->driver->id;
		$model->cav_is_oneway	 = $this->isOneWay | 0;
		$model->cav_is_shared	 = $this->isShared | 0;

		$model->cav_is_local_trip = $this->isLocalTrip | 0;
		return $model;
	}

	public function setData($data)
	{

		$dataObj		 = (is_array($data)) ? \Filter::convertToObject($data) : $data;
		$this->id		 = (int) $dataObj->cav_id;
		/** @var \CabAvailabilities $dataObj */
		$this->vendor	 = \Beans\Vendor::setDataByModel($dataObj);

		$this->driver = new \Beans\Driver();
		$this->driver->fillData($dataObj);

		$this->cab = new \Beans\common\Cab();
		$this->cab->setBasicInfo($dataObj);

		$this->pickupCity	 = new \Beans\common\City();
		$this->pickupCity->setIdName($dataObj->cav_from_city, $dataObj->cav_from_city_name);
		$this->dropCity		 = new \Beans\common\City();
		$this->dropCity->setIdName($dataObj->cav_to_cities, $dataObj->cav_to_city_name);

		$this->pickupDateRange			 = new \Beans\common\DateRange();
		$this->pickupDateRange->fromDate = $dataObj->cav_date_time;
		$this->pickupDateRange->toDate	 = date('Y-m-d H:i:s', strtotime($dataObj->cav_date_time . '+' . $dataObj->cav_duration . ' hour'));
		$this->fare						 = new \Beans\booking\Fare();
		$this->fare->vendorAmount		 = (int) $dataObj->cav_amount;
		$this->fare->totalAmount		 = (int) $dataObj->cav_total_amount;
		$this->isOneWay					 = (int) $dataObj->cav_is_oneway;
		$this->isLocalTrip				 = (int) $dataObj->cav_is_local_trip;
		$this->isShared					 = (int) $dataObj->cav_is_shared;
		$this->createDate				 = $dataObj->cav_created_at;
	}

	public static function getList($dataList)
	{
		$rowList = [];
		foreach($dataList as $data)
		{
			$object		 = new CabAvailabilities();
			$object->setData($data);
			$rowList[]	 = $object;
		}
		return $rowList;
	}

	function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = \DateTime::createFromFormat($format, $date);
		return ($d && $d->format($format) == $date) | 0;
	}

	public function validateInputs()
	{
		$currDate = \Filter::getDBDateTime();
		if(!$this->pickupDateRange || !$this->pickupDateRange->fromDate || !$this->pickupDateRange->toDate)
		{
			throw new \Exception("Please provide proper date range", \ReturnSet::ERROR_INVALID_DATA);
		}
		if(!$this->validateDate($this->pickupDateRange->fromDate) || !$this->validateDate($this->pickupDateRange->toDate))
		{
			throw new \Exception("Please provide proper date time range", \ReturnSet::ERROR_INVALID_DATA);
		}
		if($this->pickupDateRange->fromDate <= $currDate || $this->pickupDateRange->toDate <= $currDate)
		{
			throw new \Exception("Please provide proper date time range", \ReturnSet::ERROR_INVALID_DATA);
		}
		if($this->pickupDateRange->fromDate >= $this->pickupDateRange->toDate)
		{
			throw new \Exception("Please provide proper date time range", \ReturnSet::ERROR_INVALID_DATA);
		}
		if(!$this->fare || !$this->fare->vendorAmount)
		{
			throw new \Exception("Please set proper vendor amount", \ReturnSet::ERROR_INVALID_DATA);
		}
		if(!$this->pickupCity || !$this->pickupCity->id)
		{
			throw new \Exception("Please select pickup city", \ReturnSet::ERROR_INVALID_DATA);
		}
		if(!$this->dropCity || !$this->dropCity->id)
		{
			throw new \Exception("Please select drop city", \ReturnSet::ERROR_INVALID_DATA);
		}
		if(!$this->cab || !$this->cab->id)
		{
			throw new \Exception("Please select a cab from the list", \ReturnSet::ERROR_INVALID_DATA);
		}
		if(!$this->driver || !$this->driver->id)
		{
			throw new \Exception("Please select a driver from the list", \ReturnSet::ERROR_INVALID_DATA);
		}
		$duration = $this->pickupDateRange->getHourDiff();
        if($duration > 24)
        {
            throw new \Exception("Availablity time range cannot be more than 24 hours", \ReturnSet::ERROR_INVALID_DATA);
        }
	}
}
