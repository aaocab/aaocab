<?php
namespace Stub\consumer;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VoucherDetailsRequest
 *
 * @author Roy
 */
class VoucherDetailsRequest 
{
	//put your code here
	
	public $quantity;
	
	/** @var \Stub\common\VoucherSubscriber $subscriber */
	public $subscriber;
	
	public function setData(VoucherSubscriber $model)
	{
		$this->quantity = '';
		$this->subscriber->firstName = $model->vsb_name;
		$this->subscriber->email     = $model->vsb_email;
	}
	
	public function getModel()
    {		
        $model               = new \VoucherSubscriber();
        $model->vsb_vch_id   = (int)\Yii::app()->shortHash->unHash($this->subscriber->id);
		$model->vsb_name	 = $this->subscriber->fullName;		
		$model->vsb_email	 = $this->subscriber->email;
		$model->vsb_qty		 = $this->quantity;
        return $model;
    }
}
