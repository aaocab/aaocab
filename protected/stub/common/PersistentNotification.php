<?php

namespace Stub\common;

class PersistentNotification
{
	public $total, $red, $orange, $yellow, $blue, $mmt, $b2b, $b2c;

	public function getData($data='', $dData = '')
	{
	   $this->escalation->total = (int)$data['activeEscalationCnt'];
	   $this->escalation->red = (int)$data['activeEscalationRed'];
	   $this->escalation->orange = (int)$data['activeEscalationOrange'];
	   $this->escalation->yellow = (int)$data['activeEscalationYellow'];
	   $this->escalation->blue = (int)$data['activeEscalationBlue'];
	   $this->delegate->total = (int)$dData['cnt'];
	   $this->delegate->mmt = (int)$dData['countMMT'];
	   $this->delegate->b2b = (int)$dData['countB2B'];
	   $this->delegate->b2c = (int)$dData['countB2C'];
	   return $this;
	}
}
