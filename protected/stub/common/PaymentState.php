<?php

namespace Stub\common;

class PaymentState
{

	public $refId;
	public $refType;
	public $code, $gateway, $amount, $initiateTime, $initiateDate;
	public $completeTime, $completeDate, $statusCode, $statusDesc, $responseMessage,$status;
	

//    public function __construct($code, $gateway)
//    {
//        $this->code    = $code;
//        $this->gateway = $gateway;
//    }

	public function getCode()
	{
		return $this->code;
	}

	public function getGateway()
	{
		return $this->gateway;
	}

	public function setModelData($model)
	{
		$this->code		 = (int) $model->code;
		$this->gateway	 = $model->gateway;
		$this->amount	 = (int) $model->amount;
		if ($model->initiateTime != "")
		{
			$this->initiateDate	 = date('Y-m-d', strtotime($model->initiateTime));
			$this->initiateTime	 = date('H:i:s', strtotime($model->initiateTime));
		}
		if ($model->completeTime != "")
		{
			$this->completeDate	 = date('Y-m-d', strtotime($model->completeTime));
			$this->completeTime	 = date('H:i:s', strtotime($model->completeTime));
		}
		$this->status = $model->status;
	}

	/**
	 * 
	 * @param array $data
	 */
	public function setData($data)
	{
		$plist				 = \PaymentType::model()->ptpList($data['adt_ledger_id']);
		$this->code			 = $data['apg_code'];
		$this->gateway		 = $plist;
		$this->amount		 = (float) $data['adt_amount'];
		$this->initiateTime	 = $data['act_date'];
		if ($data['act_date'] != '')
		{
			$this->initiateDate	 = date('Y-m-d', strtotime($data['act_date']));
			$this->initiateTime	 = date('H:i:s', strtotime($data['act_date']));
		}
		if ($data['act_date'] != "")
		{
			$this->completeDate	 = date('Y-m-d', strtotime($data['act_date']));
			$this->completeTime	 = date('H:i:s', strtotime($data['act_date']));
		}
		$this->statusCode		 = (int) $data['adt_status'];
		$this->statusDesc		 = \AccountTransDetails::model()->getStatusDesc($data['adt_status']);
		$this->responseMessage	 = $data['apg_response_message'];
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return $this
	 */
	public function setModels($bkgId)
	{
		$trans	 = [];
		$res	 = \AccountTransDetails::getByBkgID($bkgId);
		foreach ($res as $row)
		{
			$tran	 = new Transactions();
			$tran->setData($row);
			$trans[] = $tran;
		}
		return $trans;
	}

	/* @var $data Array */
	public function setStatusData($data)
	{
		$this->bookingId	 = (int) $data['apg_booking_id'];
		$this->paymentStatus = ($data['apg_status'] == 1) ? true : false;
		$this->statusDesc	 = $data['apg_remarks'];
		$this->statusCode	 = (int) $data['apg_status'];
		return $this;
	}
	
	public function setUpdatedStatusData($data)
	{
		$this->paymentId	 = $data['apg_txn_id'];
		$this->orderId       = $data['apg_pre_txn_id'];
		$this->paymentStatus = ($data['apg_status'] == 1) ? true : false;
		$this->statusDesc	 = $data['apg_response_message'];
		$this->statusCode	 = $data['apg_response_code'];
		$this->status        =  ($data['apg_status'] == 1) ? "Payment Success" : "Payment Failed";
		return $this;
	}
}
