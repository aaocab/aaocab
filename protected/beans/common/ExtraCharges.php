<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\common;

/**
 * Description of ExtraCharges
 *
 * @author Admin
 * 
 * @property integer $km
 * @property integer $kmCharges
 * @property integer $tollTax
 * @property integer $stateTax
 * @property integer $parking 
 */
class ExtraCharges
{
	public $km, $kmCharges, $tollTax, $stateTax, $parking = 0;

}
