<?php

namespace Stub\vendor;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RejectQuote
{

	public $id;
	public $isInterested;

	public function getModel($model = null)
	{
		if ($model == null)
		{
			$model = new \VendorQuote();
		}
		$model->vqt_cqt_id		 = $this->id;
		$model->vqt_vendor_id	 = \UserInfo::getEntityId();
		$model->vqt_status = 0;
		$model->isInterested = $this->isInterested;
		return $model;
	}

}
