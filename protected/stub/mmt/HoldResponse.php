<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\mmt;

/**
 * Description of HoldResponse
 *
 * @author Admin
 */
class HoldResponse
{

	public $response;
	public $gozoId;
	public $refId;
	public $reference_number;
	public $verification_code;
	public $success;
	public $code;
	public $error;
	public $tnc;


	public $increasedPrice;

	/** @var Fare $fare */
	public $fare;
	/** @var DestinationNote $destinationNote */
	//public $destinationNote;

	/** @param \Booking $model */
	public function setData($model)
	{
		$this->response->success				 = true;
		$this->response->reference_number	 = $model->bkg_id;
		$this->response->verification_code	 = $model->bkgTrack->bkg_trip_otp;
		$this->response->tnc = "http://www.aaocab.com/terms";
        $this->error = NULL;
		$this->code = NULL;
		//destination notes by Rituparana
		//$noteArrList = \DestinationNote::model()->showNoteApi($model->bkg_id, $showNoteTo= 1);
		//if ($noteArrList != false || $noteArrList != NULL)
		//{
		   //$res		 = new \Stub\common\DestinationNote();
		   //$responseDt = $res->getData($noteArrList);
		   // foreach ($responseDt as $res)
			//{
				//$this->response->destinationNote = $res;
			//}
		//}
	}

	public function setIncreasePriceMsg($price=0)

	{
		$this->code			 = (int) 105;
		$this->error			  = "BLOCK Failed: Prices have increased";
		$this->response->newPrice = $price;

	}


}
