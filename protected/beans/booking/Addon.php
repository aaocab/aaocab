<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Addon
 *
 * @author Dev
 * 
 * 
 * @property integer $id
 * @property string $description
 * @property string $longText
 * @property string $shortText
 */

namespace Beans\booking;

class Addon
{

	public $id;
	public $description;
	public $longText;
	public $shortText;

	public static function setData($data)
	{
		$obj				 = new Addon();
		$obj->id			 = (int)$data['id'];
		$obj->description	 = $data['label'];
		$obj->longText		 = $data['longDescription'];
		$obj->shortText		 = $data['shortDescription'];
		return $obj;
	}

}
