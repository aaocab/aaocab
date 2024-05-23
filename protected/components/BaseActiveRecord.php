<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseActiveRecord
 *
 * @author Admin
 */
class BaseActiveRecord extends CActiveRecord
{

	public function save($runValidation = true, $attributes =null)
	{
		if($attributes === null)
		{
			$attributes = $this->getSafeAttributeNames();
		}
		
		return parent::save($runValidation, $attributes);
	}

	public function update($attributes = null)
	{
		if ($attributes == null)
		{
			$attributes = $this->getSafeAttributeNames();
		}
		return parent::update($attributes);
	}

}
