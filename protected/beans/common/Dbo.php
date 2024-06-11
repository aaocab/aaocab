<?php

namespace Beans\common;

/**
 * Description of Dbo
 *
 * @author Roy
 * @property boolean isDbo
 * @property string applicableTime
 * @property string message 
 */
class Dbo
{

	public $status;
	public $validTill;
	public $message;
	public $tnc;

	/**
	 * 
	 * @param string $pickupDate
	 * @param integer $bkgStatus
	 * @return \Beans\common\Dbo
	 */
	public static function getData($pickupDate, $bkgStatus = null, $bkgModel = null)
	{
		$dboObj			 = new Dbo();
		$dboObj->status	 = false;
		if ($pickupDate != '')
		{
			$getDboConfirmEndTime = \Filter::getDboConfirmEndTime($pickupDate, $bkgModel);
			if ($getDboConfirmEndTime != '')
			{
				$dboObj->status = true;
				if ($bkgStatus == null)
				{
					goto skipChecking;
				}
				if (in_array($bkgStatus, [2, 3, 5, 6, 7]))
				{
					$message		 = "This trip is applicable for double back offer";
					$dboObj->message = $message;
				}
				else
				{
					skipChecking:
					$dboObj->validTill	 = $getDboConfirmEndTime;
					$message			 = "This trip is applicable for double back offer if confirmed before " . date('D, jS M, h:i A', strtotime($getDboConfirmEndTime));
					$dboObj->message	 = $message;
				}
				$dboObj->tnc		 = "https://app.aaocab.com/terms/doubleBackAppView";
				
			}
		}
		return $dboObj;
	}

}
