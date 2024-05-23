<?php

namespace Stub\mmt;

class ExtraTimeFare
{
	public $rate, $applicable_time;

	public function setExtraTimeFare($rate,$applicableTime)
	{
		$this->applicable_time	 = (int) $applicableTime;
		$this->rate				 = (float) $rate * $applicableTime;
	}
}
