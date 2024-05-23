<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\mmt;

/**
 * Description of Confirm Response
 *
 * @author Admin
 */
class ConfirmResponse
{
		public $success;
		public $error;
		public $code;
		public $link;
		public $tnc;
		/** @var DestinationNote $destinationNote */
	   // public $destinationNote;

	public function setData($model)
	{
		$this->success	= true;
		$this->tnc = "https://www.gozocabs.com/terms";
		//destination notes by Rituparana
		//$noteArrList = \DestinationNote::model()->showNoteApi($model->bkg_id, $showNoteTo= 5);
		//if ($noteArrList != false || $noteArrList != NULL)
		//{
		   //$res		 = new \Stub\common\DestinationNote();
		   //$responseDt = $res->getData($noteArrList);
		    //foreach ($responseDt as $res)
			//{
				//$this->destinationNote = $res;
			//}
		//}
		
		
		
//		$hash = Yii::app()->shortHash->hash($model->bkg_id);
//		$epassUploadLink = Yii::app()->createUrl('index/epass', array('bkgid' => $model->bkg_id,'hash' => $hash));
//		$this->link = $epassUploadLink;
		
		//$this->error = "null";
		//$this->code = "null";

//		$result				 = $model->getBookingCodeStatus();
//		$this->bookingId	 = $model->bkg_booking_id;
//		$this->referenceId	 = $model->bkg_agent_ref_code;
//		$this->statusCode	 = (int) $result['code'];
//		$this->statusDesc	 = $result['desc'];
//
//		if ($model->bkg_agent_id != null)
//		{
//			$this->partnerTransactionDetails = new \Stub\common\PartnerTransactionDetails();
//			$this->partnerTransactionDetails->setModelData($model->bkgInvoice);
//		}
//		
//		$transactions = new \Stub\common\PaymentState();
//		$this->transactions[]=$transactions->setModels($model->bkg_id);
	}

}
