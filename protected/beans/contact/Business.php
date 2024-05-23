<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Business
 *
 * @author Dev
 * 
 * @property integer $id
 * @property \Beans\common\AccountInfo[] $accounts
 * @property \Beans\common\Document[] $agreements
 * @property \Beans\common\Document $pan
 * @property \Beans\contact\Person $owner
 */

namespace Beans\contact;

class Business extends \Beans\contact\Contact
{
	public $id;

	/** @var \Beans\common\AccountInfo $accounts */
	public $accounts = [];

	/** @var \Beans\common\Document[] $agreements */
	public $agreements = [];	

	/** @var \Beans\common\Document $pan */
	public $pan;

	/** @var \Beans\contact\Person $owner */
	public $owner;	
}
