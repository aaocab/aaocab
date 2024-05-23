<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\consumer;

class ReviewAttributeResponse extends \Stub\booking\GetDetailsResponse
{

	public $attributes;

	/** 
	 * 
	 * @param \Booking $model
	 * @param array $attrData
	 * @return boolean|$this
	 */
	public function setAttrDataV1(\Booking $model, $attrData)
	{
		if (!$model)
		{
			return false;
		}
		$this->setData($model);
		$this->attributes = $attrData;
		return $this;
	}

	/** 
	 * 
	 * @param array $attrData
	 * @return $this
	 */
	public function setAttrData($attrData)
	{
		$this->attributes = $attrData;
		return $this;
	}

}
