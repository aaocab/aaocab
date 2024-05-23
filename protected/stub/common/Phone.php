<?php

namespace Stub\common;

class Phone
{

	public $code;
	public $number;
	public $isVerified;

	public function getFullNumber()
	{
		return trim($this->code . $this->number);
	}

	/**
	 *  @param Phone $objPhone 
	 *  @return \ContactPhone
	 */
	public static function getPhoneModel($objPhone)
	{
		$phnModel = null;
		if ($objPhone != null && $objPhone->number != '')
		{
			\Filter::parsePhoneNumber("+".$objPhone->getFullNumber(), $code, $number);
			$phnModel							 = new \ContactPhone();
			$phnModel->phn_phone_country_code	 = $code;
			$phnModel->phn_phone_no				 = $number;
			if ($objPhone->isVerified == 1)
			{
				$phnModel->phn_is_verified = 1;
			}
		}
		return $phnModel;
	}

}
