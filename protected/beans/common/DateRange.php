<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Beans\common;

/**
 * Description of DateRange
 *
 * @property string $fromDate
 * @property string $toDate 
 * @author Deepak
 */
class DateRange
{

	public $fromDate;
	public $toDate;

	public function fillData($row)
	{
		$this->fromDate	 = $row->fromDate;
		$this->toDate	 = $row->toDate;
	}

	public function getHourDiff()
	{
		$date1	 = new \DateTime($this->fromDate); // first date
		$date2	 = new \DateTime($this->toDate); // second date
		$d1		 = $date1;
		$d2		 = $date2;
		if($date1 > $date2)
		{
			$d1	 = $date2;
			$d2	 = $date1;
		}
		$interval = $d1->diff($d2); // get difference between two dates
		return ($interval->days * 24) + $interval->h;
	}
}
