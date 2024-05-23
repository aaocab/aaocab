<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Contact
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $name
 * @property \Beans\contact\Email[] $email
 * @property \Beans\contact\Phone[] $phone
 * @property \Beans\common\Location $address
 * @property \Beans\common\City $city
 * @property string $createDate
 * @property integer $type
 */

namespace Beans\contact;

class Contact
{
	public $id;
	public $name;

	/** @var \Beans\contact\Email $email */
	public $email = [];

	/** @var \Beans\contact\Phone $phone */
	public $phone = [];
	
	/** @var \Beans\common\Location $address */
	public $address;

	/** @var \Beans\common\City $city */
	public $city;
	public $createDate;


}
