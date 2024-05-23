<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PromoDetails
 *
 * @author Roy
 * 
 * @property integer $id
 * @property integer $amount
 * @property string $code
 * @property string $description
 */

namespace Beans\common;

class PromoDetails
{

	/** 
	 * 
	 * @param array $promoData
	 * @return \Beans\common\PromoDetails
	 */
	public static function setDetails($promoData)
	{
		$obj				 = new PromoDetails();
		$obj->id			 = (int) $promoData['prm_id'];
		$obj->code			 = $promoData['prm_code'];
		$obj->description	 = $promoData['prm_desc'];
		$obj->amount		 = (float) $promoData['cashAmount'];
		return $obj;
	}

}
