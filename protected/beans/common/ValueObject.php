<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Beans\common;

/**
 * Description of ValueObject
 *
 * @property integer $id
 * @property string $code
 * @property string $label
 * @property string $value
 * @property string $desc
 * @author Admin
 */
class ValueObject
{

	//put your code here

	public $id, $code, $label, $value, $desc, $airportType;

	public function fillData($row)
	{
		$this->id	 = (int) $row->id;
		$this->label = $row->label;
	}

	public static function setIdlabel($id, $label, $value = '')
	{
		$obj		 = new ValueObject();
		$obj->id	 = (int) $id;
		$obj->label	 = $label;
		if ($value != '')
		{
			$obj->value = $value;
		}
		return $obj;
	}

	public function setList($data)
	{
		foreach ($data as $row)
		{
			$object		 = new \Beans\common\ValueObject();
			$object->fillData($row);
			$rowList[]	 = $object;
		}
		return $rowList;
	}

	public static function setBookingTypeData($tripType)
	{
		$bkgModel	 = new \Booking();
		$labelArr	 = $bkgModel->booking_types;
		$label		 = $labelArr[$tripType];
		$obj		 = new ValueObject();
		$obj->id	 = (int) $tripType;
		$obj->label	 = $label;
		$obj->desc	 = ($tripType>0)?$bkgModel->getBookingType($tripType):"";
		return $obj;
	}

	public static function setBookingType($bkgType, $tripType = 0)
	{
		if ($tripType == 1)
		{
			$label = "MATCHED";
		}
		else
		{
			$bkgModel	 = new \Booking();
			$label		 = $bkgModel->getBookingType($bkgType);
		}
		$obj		 = new ValueObject();
		$obj->id	 = (int) $bkgType;
		$obj->label	 = strtoupper($label);

		return $obj;
	}

	
	public static function getTypeData($bkgType)
	{
		$bkgModel	 = new \Booking();
		$obj		 = new ValueObject();
		$obj->airportType = $bkgModel->getBookingType($bkgType);
		$obj->code	 = $bkgModel->getBookingTypeCode($bkgType);
		$obj->airportType = $bkgModel->getAirportType($bkgModel);
		return $obj;
	}

}
