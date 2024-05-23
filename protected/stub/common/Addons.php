<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Addons
 *
 * @author Ramala
 * 
 */
class Addons
{

	public $id;
	public $charge; 
	public $label;
	public $desc;
	public $bookingId;
	public $addonId;
	public $content;
	public $default;
	public $type;
	
	/**
	 * this function is used to return list of addons with it's cost  
	 * @param type $data
	 * @param type $defId
	 * @param type $baseAmt
	 * @param type $type
	 * @return type array of objects
	 */
	public function getList($data,$defId,$baseAmt=0,$type=1,$pDateTime='')
	{
		$arr = [];
		
		foreach ($data as $row)
		{
			if($type == 1)
			{
				$row = \AddonCancellationPolicy::getById($row, $baseAmt);
				$hourdiff = round((strtotime($pDateTime)- strtotime(date("Y-m-d H:i:s")))/3600, 1);
				if($hourdiff > 0 && $hourdiff < 24 && $row['cost'] < 0){
					continue;
				}
				if($row['minutesBeforePickup'] > 0 && $pDateTime!='')
				{
					//$pDateTime = '2022-08-08 22:56:00';
					$cpDateTime = date('Y-m-d H:i:s', strtotime($pDateTime. ' - ' .$row['minutesBeforePickup'].' minute'));

					$cpDateTimeObj	 = new \DateTime($cpDateTime);
					$pDateTimeObj	 = new \DateTime($pDateTime);
					$nowObj = new \DateTime("now");
					if($pDateTimeObj > $cpDateTimeObj && $cpDateTimeObj > $nowObj)
					{
						// if condition satisfied
					}
					else
					{
						continue;
					}
				}
			}
			if($type == 2)
			{
				$row = \AddonCabModels::getById($row, $baseAmt);
			}
			
			$obj						 = new $this;
			$obj->id					 = (int) $row['id'];
			$obj->charge				 = (int) $row['cost'];
			$obj->label				     = $row['label'];
			$obj->desc                   = $row['desc'];
			$obj->default                = (int) $row['default'];
			$arr[]						 = $obj;
		}
		if($type == 1)
		{
		  $arr[] = $this->setCPDefault($defId);
		}
		if($type == 2)
		{
		  $arr[] = $this->setCMDefault();
		}
		usort($arr, function($a, $b) 
		{
			return $a->charge < $b->charge ? -1 : 1;
		});
		return $arr;
	}

	/**
	 *
	 * @param \BookingInvoice $model
	 * @param integer $eventType [1 => Add Promo, 2=> Remove Promo, 3=> Add Gozocoins, 4=> Remove Gozocoins ] 
	 * @return boolean|$this
	 */
	public function setData(\BookingInvoice $model, $eventType = 1)
	{
		if ($model == null)
		{
			return false;
		}
		$this->bookingId = (int) $model->biv_bkg_id;
		$this->eventType = $eventType;
		$this->fare		 = new Fare();
		$this->fare->setPromotionData($model);
		if ($model->bivPromos)
		{
			$this->promo = new PromoDetails();
			$this->promo->setModelData($model->bivPromos);
		}
		$this->wallet	 = $model->bkg_wallet_used;
		$this->gozoCoins = $model->bkg_credits_used;
		return $this;
	}

	public function getMessage(\BookingInvoice $model, $addonsId)
	{
		if($addonsId > 0){
			$message = "Addons apply successfully";
		}else{
			$message = "Addons remove  successfully";
		}
		return $message;
	}

	/**
	 * this function returns default cp applied as dummy addon
	 * @param type $id
	 * @return \Stub\common\this
	 */
	public function setCPDefault($id)
	{
		$model = \CancellationPolicyDetails::model()->findByPk($id);
		$obj						 = new $this;
		$obj->id					 = 0;
		$obj->charge				 = 0;
		$obj->label				     = $model->cnp_label;
		$obj->desc                   = $model->cnp_desc;
		$obj->default                = 1;
		return $obj;
	}
	/**
	 * this function returns default cm applied as dummy addon
	 * @param type $id
	 * @return \Stub\common\this
	 */
	public function setCMDefault()
	{
		$obj						 = new $this;
		$obj->id					 = 0;
		$obj->charge				 = 0;
		$obj->label				     = "Any model";
		$obj->desc                   = "";
		$obj->default                = 1;
		return $obj;
	}
}
