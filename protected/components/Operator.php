<?php

class Operator extends CComponent
{
	public static $_instance = [];
	public static $_apiData = [];
	public $_operator = null;
	public static $tripTypes = ['Local' => '12'];
	public static $vehicleTypes	 = ['Hatchback' => '1', 'Sedan' => '3', 'SUV' => '2', 'SUV_LUXURY' => '16', 'ANY' => '15'];
	public $objOperator = null;
	public $bkg_booking_type;

	/**
	 * 
	 * @param type $operatorId
	 */
	public function __construct($operatorId)
	{
		$this->_operator=$operatorId;
		$this->initObject();
	}

	/**
	 * 
	 * @param type $operatorId
	 * @return array
	 */
	public static function getInstance($operatorId)
	{
		if(!isset(self::$_instance[$operatorId]))
		{
			self::$_instance[$operatorId] = new static($operatorId);
		}
		return self::$_instance[$operatorId];
	}

	/** @return objOperator */
	public function initObject()
	{
		switch ($this->_operator)
		{
			case Config::get('hornok.operator.id'):
			default:
				$this->objOperator = new Hornok();
				break;
		}
		return $this->objOperator;
	}

	/**
	 * 
	 * @param int $tripType
	 * @return int
	 */
	public static function getOperatorId($tripType = null)
	{
		switch ($tripType)
		{
			case  12 || 4:
			default :
				$operatorId	 = Config::get('hornok.operator.id');
				break;
		}
		return $operatorId;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $operatorId
	 * @return $operatorResponse
	 */	 
	public function holdBooking($bkgId, $operatorId)
	{
		/* @var $objOperator Hornok */
		$operatorResponse = $this->objOperator->holdBooking($bkgId, $operatorId);
		return $operatorResponse;
	}

	/**
     *
     * @param  $model
	 * @param integer $operatorId
     * @return boolean|string
     */
	public function cancelBooking($bkgId, $operatorId)
	{
		$operatorResponse = $this->objOperator->cancelBooking($bkgId, $operatorId);
		return $operatorResponse;
	}


	/**
	 * 
	 * @param type $bkgId
	 * @param type $operatorId
	 * @return boolean
	 */
	public function updateBooking($bkgId, $operatorId)
	{
		$operatorResponse = $this->objOperator->updatebooking($bkgId, $operatorId);
		return $operatorResponse;
	} 

	/**
	 * 
	 * @param type $bkgId
	 * @param type $operatorId
	 * @param type $data
	 * @return boolean
	 */
	public function assignChauffeur($model, $operatorId, $jsonData)
	{
		/* @var $objOperator Hornok */
		$request = $this->objOperator->assignChauffeur($model, $operatorId, $jsonData);
		return $request;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $operatorId
	 * @param type $data
	 * @return boolean
	 */
	public function confirmTrip($bkgId, $operatorId, $data)
	{
		/* @var $objOperator Hornok */
		$request = $this->objOperator->confirmTrip($bkgId, $operatorId, $data);
		return $request;
	}

	
	/**
	 * 
	 * @param type $bkgId
	 * @param type $operatorId
	 * @param type $data
	 * @return boolean
	 */
	public function updateLatLocation($bkgId, $operatorId, $data)
	{
		/* @var $objOperator Hornok */
		$request = $this->objOperator->updateLatLocation($bkgId, $operatorId, $data);
		return $request;
	}


	/**
	 * 
	 * @param type $bkgId
	 * @param type $operatorId
	 * @param type $jsonObj
	 * @return type
	 */
	public function syncRideData($bkgId, $operatorId, $jsonObj)
	{
		/* @var $objOperator Hornok */
		$request = $this->objOperator->syncRideData($bkgId, $operatorId, $jsonObj);
		return $request;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $operatorId
	 * @param type $data
	 * @return boolean
	 */
	public function unAssign($bkgId, $operatorId, $data)
	{
		/* @var $objOperator Hornok */
		$request = $this->objOperator->unAssign($bkgId, $operatorId, $data);
		return $request;
	}

}
