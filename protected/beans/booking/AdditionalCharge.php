<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdditionalCharge
 *
 * @author Dev
 * 
 * @property integer $type 
 * @property string $description
 * @property integer $amount
 * @property bool $isGSTApplicable
 * @property integer $collectExtra
 * @property string $startTime
 * @property string $endTime
 * @property integer $isIncluded
 */

namespace Beans\booking;

class AdditionalCharge
{

	public $type;  //Toll/State/Parking/DriverAllowance/NightPickup/NightDrop/Convenience Fee/COD Fee/others
	public $description;
	public $amount;
	public $isGSTApplicable;
	public $collectExtra;
	public $startTime;
	public $endTime;
	public $isIncluded;

	public static function setByInvoiceModel(\BookingInvoice $invoiceModel, $type = '', $bookingType='')
	{
		$typeList	 = self::getList($type,$bookingType);
		$addItional	 = [];
		
		foreach ($typeList as $key => $type)
		{
			$obj		 = new AdditionalCharge();
			$obj->type	 = $key;
			switch ($type)
			{
				case "Toll":
					$obj->setTollTax($invoiceModel);
					break;
				case "State":
					$obj->setStateTax($invoiceModel);
					break;
				case "Parking":
					$obj->setParking($invoiceModel);
					break;
				case "DriverAllowance":
					$obj->setDriverAllowance($invoiceModel);
					break;
				case "NightPickup":
					$obj->setNightPickup($invoiceModel);
					break;
				case "NightDrop":
					$obj->setNightDrop($invoiceModel);
					break;
				case "ConvenienceFee":
					$obj->setConvenienceFee($invoiceModel);
					break;
				case "WaitingCharges":
					$obj->setWaitingCharges($invoiceModel);
					break;
				case "AirportEntryCharges":
					$obj->setAirportEntryCharges($invoiceModel);
					break;
//				case "CarrierCharge":
//					$obj->setCarrierCharge($others);
//					break;
				default:
					break;
			}
			$addItional[] = $obj;
		}
		return $addItional;
	}

	public static function getList($type = '',$bookingType='')
	{
		$typeList = [
			1	 => "Toll",
			2	 => "State",
			3	 => "Parking",
			4	 => "DriverAllowance",
			5	 => "NightPickup",
			6	 => "NightDrop",
			7	 => "ConvenienceFee",
			8	 => "WaitingCharges",
			9	 => "AirportEntryCharges",
//			10	 => "CarrierCharge"
		];
		/*afer full update of new version this code will be uncommented 25-07-2023
		 * if($bookingType==12 || $bookingType==4)
			{
				unset($typeList[3]);
			}
			if($bookingType!=12 && $bookingType!=4 )
			{
				unset($typeList[9]);
			}*/ 
		if ($type != '')
		{
			$key = array_search($type, $typeList);
			if ($key != null)
			{
				
				return [$key => $type];
			}
			
		}
		
		return $typeList;
	}

	public function setTollTax($invoiceModel)
	{
		$this->description	 = "Toll Tax";
		$this->amount		 = (int) $invoiceModel->bkg_toll_tax | 0;
		$this->collectExtra	 = (int) $invoiceModel->bkg_extra_toll_tax | 0;
		$this->isIncluded	 = (int) $invoiceModel->bkg_is_toll_tax_included | 0;
	}

	public function setStateTax($invoiceModel)
	{
		$this->description	 = "State Tax";
		$this->amount		 = (int) $invoiceModel->bkg_state_tax | 0;
		$this->collectExtra	 = (int) $invoiceModel->bkg_extra_state_tax | 0;
		$this->isIncluded	 = (int) $invoiceModel->bkg_is_state_tax_included | 0;
	}

	public function setParking($invoiceModel)
	{
		
		$this->description	 = "Parking Charge";
		$this->amount		 = (int) $invoiceModel->bkg_parking_charge | 0;
		$this->isIncluded	 = (int) $invoiceModel->bkg_is_parking_included | 0;
	}

	public function setDriverAllowance($invoiceModel)
	{
		$this->description	 = "Driver Allowance";
		$this->amount		 = (int) $invoiceModel->bkg_driver_allowance_amount | 0;
		#$this->isIncluded	 = (int) ($invoiceModel->bkg_night_pickup_included == 1 || $invoiceModel->bkg_night_drop_included == 1) ? 1 : 0;
		$this->isIncluded	 = (int) 1;
	}

	public function setNightPickup($invoiceModel)
	{
		$this->description	 = "Night Pickup";
		$this->isIncluded	 = (int) $invoiceModel->bkg_night_pickup_included;
	}

	public function setNightDrop($invoiceModel)
	{
		$this->description	 = "Night Drop";
		$this->isIncluded	 = (int) $invoiceModel->bkg_night_drop_included;
	}

	public function setConvenienceFee($invoiceModel)
	{
		$this->description	 = "Convenience Fee";
		$this->amount		 = (int) $invoiceModel->bkg_convenience_charge;
	}

	public function setWaitingCharges($invoiceModel)
	{
		$this->description	 = "Waiting Charges";
		$this->amount		 = (int) $invoiceModel->bkg_trip_waiting_charge;
	}

	public function setAirportEntryCharges($invoiceModel)
	{
		$this->description	 = "Airport Entry Charges";
		$this->amount		 = (int) $invoiceModel->bkg_airport_entry_fee;
		$this->isIncluded	 = (int) $invoiceModel->bkg_is_airport_fee_included;
	}

	public static function setByInput($data, $type = '')
	{
		$typeList = self::getList($type);

		$addItional = [];
		#$data = json_decode($data);


		foreach ($data as $res)
		{
			$obj		 = new AdditionalCharge();
			$type		 = $res->type;
			$obj->type	 = $type;
			switch ($type)
			{
				case 1:
					$obj->setInputTollTax($res);
					break;
				case 2:
					$obj->setInputStateTax($res);
					break;
				case 3:
					$obj->setInputParking($res);
					break;
				case 4:
					$obj->setInputDriverAllowance($res);
					break;
				case 5:
					$obj->setInputNightPickup($res);
					break;
				case 6:
					$obj->setInputNightDrop($res);
					break;
				case 7:
					$obj->setInputConvenienceFee($res);
					break;
				case 8:
					$obj->setInputWaitingCharges($res);
					break;
				case 9:
					$obj->setInputAirportEntryCharges($res);
					break;

				default:
					break;
			}
			$addItional[] = $obj;
		}
		return $addItional;
		/* foreach ($typeList as $key => $type)
		  {

		  $obj		 = new AdditionalCharge();
		  $obj->type	 = $key;
		  //$data = json_decode($data);
		  $type = $data->type;
		  switch ($type)
		  {
		  case "Toll":
		  $obj->setInputTollTax($data[$i]);
		  break;
		  case "State":
		  $obj->setInputStateTax($data[$i]);
		  break;
		  case "Parking":
		  $obj->setInputParking($data[$i]);
		  break;
		  case "DriverAllowance":
		  $obj->setInputDriverAllowance($data[$i]);
		  break;
		  case "NightPickup":
		  $obj->setInputNightPickup($data[$i]);
		  break;
		  case "NightDrop":
		  $obj->setInputNightDrop($data[$i]);
		  break;
		  case "ConvenienceFee":
		  $obj->setInputConvenienceFee($data[$i]);
		  break;
		  case "WaitingCharges":
		  $obj->setInputWaitingCharges($data[$i]);
		  break;
		  case "AirportEntryCharges":
		  $obj->setInputAirportEntryCharges($data[$i]);
		  break;
		  //				case "CarrierCharge":
		  //					$obj->setCarrierCharge($others);
		  //					break;
		  default:
		  break;
		  }
		  $addItional[] = $obj;
		  }
		  return $addItional; */
	}


	public static function setByInputData($data)
	{
		$addItional = [];
		
		foreach ($data as $res)
		{
			$obj		 = new AdditionalCharge();
			$type		 = $res->type;
			$obj->type	 = $type;
			switch ($type)
			{
				case "TOLL_TAX":
					$obj->setInputTollTax($res);
					break;
				case "STATE_TAX":
					$obj->setInputStateTax($res);
					break;
				case "PARKING_CHARGE":
					$obj->setInputParking($res);
					break;
				default:
					break;
			}
			$addItional[] = $obj;
		}
		return $addItional;
	}

	public function setInputTollTax($data)
	{
		$this->description	 = "Toll Tax";
		$this->amount		 = (int) $data->amount | 0;
		$this->collectExtra	 = (int) $data->collectExtra | 0;
		$this->isIncluded	 = (int) $data->isIncluded | 0;
	}

	public function setInputStateTax($data)
	{
		$this->description	 = "State Tax";
		$this->amount		 = (int) $data->amount | 0;
		$this->collectExtra	 = (int) $data->collectExtra | 0;
		$this->isIncluded	 = (int) $data->isIncluded | 0;
	}

	public function setInputParking($data)
	{
		$this->description	 = "Parking Charge";
		$this->amount		 = (int) $data->amount | 0;
		$this->isIncluded	 = (int) $data->isIncluded | 0;
		
		
	}

	public function setInputDriverAllowance($data)
	{
		$this->description	 = "Driver Allowance";
		$this->amount		 = (int) $data->amount | 0;
		$this->isIncluded	 = (int) ($data->isIncluded == 1 || $data->bkg_night_drop_included == 1) ? 1 : 0;
	}

	public function setInputNightPickup($data)
	{
		$this->description	 = "Night Pickup";
		$this->isIncluded	 = (int) $data->isIncluded;
	}

	public function setInputNightDrop($data)
	{
		$this->description	 = "Night Drop";
		$this->isIncluded	 = (int) $data->isIncluded;
	}

	public function setInputConvenienceFee($data)
	{
		$this->description	 = "Convenience Fee";
		$this->amount		 = (int) $data->amount;
	}

	public function setInputWaitingCharges($data)
	{
		$this->description	 = "Waiting Charges";
		$this->amount		 = (int) $data->amount;
	}

	public function setInputAirportEntryCharges($data)
	{
		$this->description	 = "Airport Entry Charges";
		$this->amount		 = (int) $data->amount;
		$this->isIncluded	 = (int) $data->isIncluded;
	}

}
