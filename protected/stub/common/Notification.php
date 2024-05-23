<?php

namespace Stub\common;

class Notification
{

	public $id;
	public $eventCode;
	public $payload;
	public $title;
	public $message;
	public $body;
	public $createDate;
	public $isRead;
	public $readAt;
	public $actionValue;
	public $actionAt;
	public $totalCount;
	public $pageSize;
	public $currentPage;

	/** @var \Stub\common\Payload $payload */
	public function fillData($row)
	{
		$this->id			 = (int) $row['ntl_id'];
		$this->eventCode	 = (int) $row['ntl_event_code'];
		$payloadData		 = json_decode($row['ntl_payload']);
		$this->payload		 = (isset($payloadData->notifications) ) ? $payloadData->notifications : $payloadData;
		$this->title		 = $row['ntl_title'];
		$this->message		 = $row['ntl_message'];
		$this->createDate	 = $row['ntl_created_on'];
		$this->isRead		 = (int) $row['ntl_is_read'];
		$this->readAt		 = $row['ntl_read_at'];
		if ($row['ntl_action_value'] != NULL)
		{
			$this->actionValue	 = $row['ntl_action_value'];
			$this->actionAt		 = $row['ntl_action_at'];
		}
	}

	public function getList($dataArr, $totalCount, $pageSize, $pageCount)
	{
		$list				 = [];
		$this->totalCount	 = (int) $totalCount;
		$this->pageSize		 = (int) $pageSize;
		$this->currentPage	 = (int) $pageCount;
		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\common\Notification();
			$obj->fillData($row);
			$this->dataList[]	 = $obj;
		}
	}

	public function getLogList($dataArr)
	{
		$list = [];
		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\common\Notification();
			$obj->fillData($row);
			$this->dataList[]	 = $obj;
		}
	}

	public function setGNowNotify(\Booking $model, $titleMessage = '')
	{
		/** @var \Booking $model */
		$pickupAddress	 = $model->bkg_pickup_address;
//		$dropAddress	 = $model->bkg_drop_address;
		$pickupDate		 = \DateTimeFormat::SQLDateTimeToLocaleDateTime($model->bkg_pickup_date);
		$cabType		 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$tripType		 = trim($model->getBookingType($model->bkg_booking_type));

		$message = ($titleMessage == '' ) ? "$cabType required at $pickupAddress | on $pickupDate, $tripType" : $titleMessage;

		$eventCode	 = \Booking::CODE_VENDOR_GOZONOW_BOOKING_REQUEST;
		$payLoadData = new Payload();
		$payLoadData->setGNowNotifyData($model, $eventCode);

		$payload = \Filter::removeNull($payLoadData);

		$title			 = "$cabType required urgent";
		$this->eventCode = $eventCode;
		$this->title	 = $title;
		$this->payload	 = $payload;
		$this->message	 = $message;
	}

	public function setGNowWinBidNotify(\Booking $model)
	{
		/** @var \Booking $model */
		$pickupCity	 = $model->bkgFromCity->cty_name;
		$dropCity	 = $model->bkgToCity->cty_name;
		$tripId		 = $model->bkg_bcb_id;
//		$pickupDateLocal = \DateTimeFormat::SQLDateTimeToLocaleDateTime($model->bkg_pickup_date);
//		$cabType		 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
//		$tripType		 = trim($model->getBookingType($model->bkg_booking_type));
//		$pickupDate	 = $model->bkg_pickup_date;
		$vendorId	 = $model->bkgBcb->bcb_vendor_id;
      
       // if($model->bkgPref->bkg_is_gozonow == 1)
       // {
            $dataRow	 = \BookingVendorRequest::getPreferredVendorbyBooking($tripId);
            if (isset($dataRow['bvr_vendor_id']) && $dataRow['bvr_vendor_id'] != $vendorId)
            {
                throw new \Exception("Booking already assigned to other vendor", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
            }
            $dataBidString	 = $dataRow['bvr_special_remarks'];
            $dataBidArr		 = json_decode($dataBidString);
            $reachTime		 = $dataBidArr->reachingAtTime;
       // }
        if (!$reachTime)
        {
            $reachTime = $model->bkg_pickup_date;
        }

        $timeDiffMinutes = \Filter::getTimeDiff($reachTime);

        $message				 = "Pickup is scheduled for the trip from $pickupCity to $dropCity starting in $timeDiffMinutes minutes. Dispatch the cab now! ";
        $model->reachInMinutes	 = $timeDiffMinutes;
        $eventCode				 = \Booking::CODE_VENDOR_GOZONOW_BOOKING_ALLOCATED;
		$payLoadData			 = new Payload();
		$payLoadData->setGNowNotifyData($model, $eventCode);

		$payload		 = \Filter::removeNull($payLoadData);
		$this->title	 = "Pickup Scheduled!";
		$this->payload	 = $payload;
		$this->message	 = $message;
		$this->body		 = $message;
		$this->eventCode = $eventCode;
	}

	public function setCabDrvAssignNotify(\Booking $model)
	{
		/** @var \Booking $model */
		$tripId					 = $model->bkg_bcb_id;
		$pickupDate				 = $model->bkg_pickup_date;
		$vendorId				 = $model->bkgBcb->bcb_vendor_id;
		$reachTime				 = $model->bkg_pickup_date;
		$assignTime				 = $model->bkgTrail->bkg_assigned_at;
		$timeDiffMinutes		 = \Filter::getTimeDiff($reachTime);
		$endTime				 = date('d/M/Y-h:iA', strtotime($assignTime . ' + 30 MINUTE'));
		$model->reachInMinutes	 = $timeDiffMinutes;
		$message				 = " Assign Approved Car & Driver before $endTime ";
		$title					 = "Assign Cab Driver!";
		$eventCode				 = \NotificationLog::CODE_VENDOR_ASSIGN_CAB_DRIVER;
		$payLoadData			 = new Payload();
		$payLoadData->setVendorNotifyData($model, $eventCode);

		$payload		 = \Filter::removeNull($payLoadData);
		$this->eventCode = $eventCode;
		$this->title	 = $title;
		$this->payload	 = $payload;
		$this->message	 = $message;
	}

	public function setDriverReadyToGoNotify(\Booking $model)
	{
		/** @var \Booking $model */
		$pickupCity		 = $model->bkgFromCity->cty_name;
		$dropCity		 = $model->bkgToCity->cty_name;
		$reachTime		 = $model->bkg_pickup_date;
		$timeDiffMinutes = \Filter::getTimeDiff($reachTime);

		$message				 = "You have been allocated the trip from $pickupCity to $dropCity starting in $timeDiffMinutes minutes. Dispatch the cab now! ";
		$model->reachInMinutes	 = $timeDiffMinutes;
		$eventCode				 = \Booking::CODE_VENDOR_GOZONOW_BOOKING_ALLOCATED;
		$payLoadData			 = new Payload();
		$payLoadData->setGNowNotifyData($model, $eventCode);

		$payload		 = \Filter::removeNull($payLoadData);
		$this->title	 = "Ready to go for pickup now!";
		$this->payload	 = $payload;
		$this->message	 = $message;
		$this->body		 = $message;
		$this->eventCode = $eventCode;
	}

	public function setGNowCustomerRejectNotify(\Booking $model)
	{
		/** @var \Booking $model */
		$message	 = "Customer declined your offer. Try with lower amount";
		$eventCode	 = \Booking::CODE_VENDOR_GOZONOW_NOTIFIED_REJECTED_OFFER;
		$payLoadData = new Payload();
		$payLoadData->setGNowRejectedNotifyData($model, $eventCode);

		$payload = \Filter::removeNull($payLoadData);

		$this->payload	 = $payload;
		$this->message	 = $message;
	}

	public function setNotificationBody()
	{

		$notification = [
			'title'		 => $this->title,
			'EventCode'	 => $this->eventCode,
			'body'		 => $this->message,
		];
		return $notification;
	}

	/**
	 * 
	 * @param \Booking $model
	 * @param integer $eventCode
	 */
	public function setNotifyCustomer(\Booking $model, $eventCode = '')
	{
		/** @var \Booking $model */
		$payLoadData = new Payload();
		$payLoadData->setCustomerNotifyData($model, $eventCode);
		$payload		 = \Filter::removeNull($payLoadData);

		$this->payload	 = $payload;
		$this->eventCode = $eventCode;
	}

}
