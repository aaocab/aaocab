<?php

namespace Stub\common;

class Email
{
	public $value;
	public $isVerified;
	public $isPrimary;
	
	
	/**
	 *  @param Email $objEmail 
	 *  @return \ContactEmail
	 */
	public static function getEmailModel($objEmail)
	{
		$emlModel = null;
		if($objEmail != null && $objEmail->value!='')
		{
			$emlModel = new \ContactEmail();
			$emlModel->eml_email_address = $objEmail->value;
			$emlModel->eml_is_primary=1;
			if($objEmail->isVerified == 1)
			{
				$emlModel->eml_is_verified=1;
			}
		}
		return $emlModel;
	}
}
