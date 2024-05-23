<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TrackEvent
 *
 * @author Dev
 * 
 * @property \Beans\common\ValueObject[] $type
 * @property \Beans\Booking $booking  
 * @property string $createDate 
 * @property \Beans\common\ValueObject[] $documents
 * @property \Beans\common\Coordinates $coordinates
 * @property \Beans\booking\SyncInfo $syncInfo
 * @property string $transaction
 */

namespace Beans\booking;

class TrackEvent
{

	/** @var \Beans\common\ValueObject $type */
	public $type;

	/** @var \Beans\Booking $booking */
	public $booking;
	public $createDate;

	/** @var \Beans\common\ValueObject[] $documents */
	public $documents;

	/** @var \Beans\common\Coordinates $coordinates */
	public $coordinates;

	/** @var \Beans\booking\SyncInfo $syncInfo */
	public $syncInfo;
	public $transaction;
	public $deviceTrack;
	public $remarks;

	/** @var \Beans\booking\Fare $fare */
	public $fare;

	/**
	 * 
	 * @param type $data
	 * @return type
	 */
	public static function setTrackModel($data, $isDCO = true)
	{
		if ($isDCO == true)
		{
			$eventType	 = $data->eventType;
			$model		 = self::getDefaultModel($data);
		}
		else
		{
			$eventType	 = \BookingTrackLog::model()->getEventIdByEventType($data->eventType);
			$model		 = self::setModelData($data, $eventType);
		}
		$trackObj = null;
		switch ($eventType)
		{
			case \BookingTrack::DRIVER_ARRIVED:
				$model		 = self::setArrived($model);
				break;
			case \BookingTrack::TRIP_START:
				$model		 = self::setStart($model, $data, $isDCO);
				break;
			case \BookingTrack::TRIP_SELFIE:
				$model		 = self::takeSelfie($model, $data);
				break;
			case \BookingTrack::NO_SHOW:
				$model		 = self::setNoShow($model);
				break;
			case \BookingTrack::TRIP_TERMS_AGREE:
				$model		 = self::setTermsAgree($model, $data);
				break;
			case \BookingTrack::TRIP_SANITIZER_KIT:
				$model		 = self::takeSanitizer($model, $data);
				break;
			case \BookingTrack::VOUCHER_UPLOAD:
				$model		 = self::takeVoucher($model, $data);
				break;
			case \BookingTrack::TRIP_STOP:
				$model		 = self::setStop($model, $data, $isDCO);
				$trackObj	 = self::setStopTripTransactions($model);
				break;
			case \BookingTrack::FILE_UPLOAD:
				$model		 = self::takeFile($model, $data);
				break;
			
			
		}
		return [$model, $trackObj];
	}

	/**
	 * 
	 * @param type $data
	 * @return \BookingTrackLog
	 */
	public static function getDefaultModel($data)
	{

		$deviceTrack				 = json_decode($data->deviceTrack);
		$coordinate					 = $deviceTrack->coordinates;
		$model						 = new \BookingTrackLog();
		$model->btl_event_type_id	 = $data->eventType;
		$model->btl_bkg_id			 = $data->refId;
		$model->btl_appsync_id		 = $data->id;
		$model->btl_remarks			 = $data->remarks;
		$model->btl_sync_time		 = date("Y-m-d H:i:s", strtotime($data->createDate));
		$coordinates				 = new \Beans\common\Coordinates();
		$model->btl_coordinates		 = $coordinates->getCoordinateData($model, $coordinate);
		//$model->btl_coordinates		 = $data->deviceTrack->coordinates->lat . "," . $data->deviceTrack->coordinates->lng;
		$userInfo					 = \UserInfo::getInstance();
		$model->btl_event_platform	 = \UserInfo::$platform;
		$model->btl_user_type_id	 = \UserInfo::TYPE_DRIVER;
		$model->btl_user_id			 = $userInfo->userId;
		//$model->btl_created			 = new \CDbExpression('NOW()');	
		$model->btl_created			 = date("Y-m-d H:i:s");

		$bookingModel			 = \Booking::model()->findByPk($model->btl_bkg_id);
		$model->btl_bcb_id		 = $bookingModel->bkg_bcb_id;
		$deviceTrackObj			 = new \Beans\common\DeviceTrack();
		$model->btl_device_info	 = $deviceTrackObj->getDeviceData($model, $deviceTrack);
		if ($bookingModel->bkg_agent_id == 450)
		{
			$model->reff_id = $bookingModel->bkg_agent_ref_code;
		}

		// Discrepancies
		if (!in_array($data->type->id, [\BookingTrack::TRIP_START, \BookingTrack::DRIVER_ARRIVED]))
		{
			goto skipDiscrepancies;
		}
		$distanceDiscrepancies	 = \Filter::calculateDistance($data->deviceTrack->coordinates->lat, $data->deviceTrack->coordinates->lng, $bookingModel->bookingRoutes[0]->brt_from_latitude, $bookingModel->bookingRoutes[0]->brt_from_longitude);
		$address1				 = explode(',', $bookingModel->bkg_pickup_address);
		$startKmLimit			 = (int) ( count($address1) > 3) ? 5 : \Config::get('ride.startkmlimit');
		if ($distanceDiscrepancies < $startKmLimit)
		{
			goto skipDiscrepancies;
		}
		$discrepanciesRemark = [];
		if ((!$data->discrepancies) && $distanceDiscrepancies > $startKmLimit)
		{
			$var				 = '[{"code": 1,"remarks": "Arrived location discrepancy"}]';
			$data->discrepancies = json_decode($var);
		}
		$model->btl_discrepancy_remarks = json_encode(array_unique(json_decode($model->btl_discrepancy_remarks, true), SORT_REGULAR));
		skipDiscrepancies:

		return $model;
	}

	/**
	 * 
	 * @param Booking $model
	 * @return type
	 */
	public static function setArrived($model)
	{
		$model->btlBkg->bkgTrack->bkg_trip_arrive_time = $model->btl_sync_time;
		return $model;
	}

	/**
	 * 
	 * @param Booking $model
	 * @param type $data
	 * @param type $isDCO
	 * @return type
	 */
	public static function setStart($model, $data, $isDCO)
	{

		#$model->btl_doc_checksum								= $data->odometer->checksum;
		$model->btlBkg->bkgTrack->bkg_trip_start_time		 = $model->btl_sync_time;
		$model->btlBkg->bkgTrack->bkg_trip_start_coordinates = $model->btl_coordinates;
		$model->btlBkg->bkgTrack->bkg_is_trip_verified		 = 1;

		$setData = self::setDataVal($model, $data, $isDCO);
		return $setData;
	}
	
	

	public static function setDriverPosition($model)
	{
		$model->btk_last_coordinates_time = $model->btl_sync_time;
	}

	public static function setDataVal($model, $data, $isDCO = true)
	{
		if ($isDCO == true)
		{
			$bookingId	 = $data->refId;
			$data		 = json_decode($data->data);
		}
		else
		{
			$bookingId	 = $model->btl_bkg_id;
			$data		 = $data->data;
		}
		foreach ($data as $eventData)
		{
			if ($eventData->refType == 'START_TRIP_ODOMETER')
			{
				$model->btlBkg->bkgTrack->bkg_start_odometer = $eventData->refValue;
			}
			if ($eventData->refType == 'OTPVerified')
			{
				$model->btlBkg->bkgTrack->bkg_is_trip_verified = $eventData->refValue;
			}
			if ($eventData->refType == 'STOP_TRIP_ODOMETER')
			{
				$model->btlBkg->bkgTrack->bkg_end_odometer = $eventData->refValue;
			}
			if ($eventData->refType == 'Fare')
			{
				$fare = new \Beans\booking\Fare();
				if ($isDCO == true)
				{
					$model->btlBkg->fare = $fare->setStopData($eventData->refValue, $bookingId);
				}
				else
				{
					$model->btlBkg->fare = $fare->setStop($eventData->refValue, $bookingId);
				}
			}
			
		}

		return $model;
	}

	/**
	 * 
	 * @param type $model
	 * @param type $data
	 * @return type
	 */
	public static function takeSelfie($model, $data)
	{
		$model->btl_doc_checksum				 = $data->selfie->checksum;
		$model->btlBkg->bkgTrack->btk_is_selfie	 = $data->selfie->refValue;
		return \BookingTrackLog::getDocs($model, $data);
	}

	/**
	 * 
	 * @param type $model
	 * @return type
	 */
	public static function setNoShow($model)
	{
		$model->btlBkg->bkgTrack->bkg_no_show_time = $model->btl_sync_time;
		return $model;
	}

	/**
	 * 
	 * @param type $model
	 * @param type $data
	 * @return type
	 */
	public static function setTermsAgree($model, $data)
	{
		$model->btlBkg->bkgTrack->btk_safetyterm_agree = $data->remarks;
		return $model;
	}

	/**
	 * 
	 * @param type $model
	 * @param type $data
	 * @return type
	 */
	public static function takeSanitizer($model, $data)
	{
		$model->btl_doc_checksum							 = $data->covidSafety->checksum;
		$model->btlBkg->bkgTrack->btk_is_sanitization_kit	 = $data->covidSafety->refValue;
		return \BookingTrackLog::getDocs($model, $data);
	}

	/**
	 * 
	 * @param type $model
	 * @param type $data
	 * @return type
	 */
	public static function takeVoucher($model, $data)
	{
		$model->btl_doc_checksum = $data->odometer->checksum;
		return \BookingTrackLog::getDocs($model, $data);
	}

	public static function takeFile($model, $data)
	{
	    #$data	 = json_decode($data);
		
		foreach ($data->data as $fileArr)
		{
			$fileType = $fileArr->refType;
			switch ($fileType)
			{
				case SELFI_FILE:
					$model->btl_event_type_id	 = \BookingTrack::TRIP_SELFIE;
					break;
				case ODOMETER_START_FILE:
					$model->btl_event_type_id	 = \BookingTrack::ODOMETER_START_FILE;
					break;
				case ODOMETER_STOP_FILE:
					$model->btl_event_type_id	 = \BookingTrack::ODOMETER_STOP_FILE;
					break;
				case STATE_TAX_FILE:
					$model->btl_event_type_id	 = \BookingTrack::STATE_TAX_FILE;
					break;
				case TOLL_TAX_FILE:
					$model->btl_event_type_id	 = \BookingTrack::TOLL_TAX_FILE;
					break;
				case PARKING_CHARGES_FILE:
					$model->btl_event_type_id	 = \BookingTrack::PARKING_CHARGES_FILE;
					break;
				case DUTY_SLIP_FILE:
					$model->btl_event_type_id	 = \BookingTrack::DUTY_SLIP_FILE;
					break;
				case OTHERS_FILE:
					$model->btl_event_type_id	 = \BookingTrack::OTHERS_FILE;
					break;
				case CAB_FRONT_FILE:
					$model->btl_event_type_id	 = \BookingTrack::CAB_FRONT_FILE;
					break;
				case CAB_BACK_FILE:
					$model->btl_event_type_id	 = \BookingTrack::CAB_BACK_FILE;
					break;
			}
			return \BookingTrackLog::getFileDocs($model, $data, $fileArr);
		}
	}

	/**
	 * 
	 * @param type $model
	 * @param type $data
	 */
	public static function setStopOld($model, $data)
	{
		$model->btl_doc_checksum								 = $data->odometer->checksum;
		$model->btlBkg->bkgTrack->bkg_trip_end_time				 = $model->btl_sync_time;
		$model->btlBkg->bkgTrack->bkg_trip_end_coordinates		 = $model->btl_coordinates;
		$model->btlBkg->bkgTrack->bkg_end_odometer				 = $data->odometer->refValue;
		$model->btlBkg->bkgBcb->bcbCab->vhc_end_odometer		 = $data->odometer->refValue;
		$model->btlBkg->bkgBcb->bcbCab->vhc_odometer_modified_on = new \CDbExpression("NOW()");
		return \BookingTrackLog::getDocs($model, $data);
	}

	/**
	 * 
	 * @param type $model
	 * @param type $data
	 */
	public static function setStop($model, $data, $isDCO)
	{
		#$dataArr =  json_decode($data); 
		$model->btlBkg->bkgTrack->bkg_trip_end_time			 = $model->btl_sync_time;
		$model->btlBkg->bkgTrack->bkg_trip_end_coordinates	 = $model->btl_coordinates;
		$setData											 = self::setDataVal($model, $data, $isDCO);

		return $setData;
	}
	
	/**
	 * 
	 * @param type $model
	 * @param type $data
	 */
	public static function setTripStop($model, $data)
	{
		#$dataArr =  json_decode($data); 
		$model->btlBkg->bkgTrack->bkg_trip_end_time			 = $model->btl_sync_time;
		$model->btlBkg->bkgTrack->bkg_trip_end_coordinates	 = $model->btl_coordinates;
		$setData											 = self::setDataValue($model, $data);

		return $setData;
	}

	/**
	 * 	 
	 * @param type $model
	 */
	public static function setStopTripTransactions($model)
	{

		$trackObj				 = new \Beans\booking\TrackEvent();
		$transObj				 = new \Beans\common\PartnerTransactionDetails();
		$transObj->setData($model->btlBkg->fare);
		$trackObj->transaction	 = $transObj;
		return $trackObj;
	}

	/**
	 * 
	 * @param type $response
	 * @param type $eventModel
	 * @param type $refId
	 * @return type
	 */
	public static function setResponse($response, $eventModel, $refId = null)
	{
		if ($eventModel->btlBkg->bkgBcb->bcb_vendor_id == \Config::get("hornok.operator.id"))
		{
			$eventType = \BookingTrackLog::model()->getEventTypeById($eventModel->btl_event_type_id);
		}
		else
		{
			$eventType = $eventModel->btl_event_type_id;
		}

		$obj				 = new TrackEvent();
		$obj->id			 = $eventModel->btl_appsync_id;
		$obj->eventType		 = $eventType;
		$obj->booking		 = new \Beans\Booking();
		$obj->refId			 = $eventModel->btl_bkg_id;
		$status				 = $response->getStatus();
		$obj->syncStatus	 = (int) $status;
		$obj->syncError[]	 = implode(', ', $response->getErrors());
		$obj->message		 = ($status) ? $response->getMessage() : implode(', ', array_column($response->getErrors(), 0));
		#$obj->btl_created		 = new \CDbExpression('NOW()');
		return $obj;
	}

	/**
	 * 
	 * @param type $data
	 * @return \Beans\booking\TrackEvent
	 */
	public static function setFileResponse($data,$id=0)
	{
		$model = $data->getData();
		
		$message		 = $data->getMessage();
		$obj			 = new TrackEvent();
		$obj->booking	 = new \Beans\Booking();
		$obj->syncInfo	 = new \Beans\booking\SyncInfo();

		/*$row = \BookingTrackLog::model()->getdetailByEvent($model->bpay_bkg_id, $model->bpay_type);
		if ($row)
		{
			$obj->syncInfo->id = $row["btl_appsync_id"];
		}*/
		$obj->syncInfo->id       = $id;
		$obj->booking->id		 = (int) $model->bpay_bkg_id;
		$obj->type->id			 = (int) $model->bpay_type;
		$obj->syncInfo->status	 = (bool) $model->bpay_status;
		$obj->syncInfo->remarks	 = $message;
		return $obj;
	}

	/**
	 * 
	 * @param type $data
	 * @return type
	 */
	public static function setTrackModelData($data, $bkgModel)
	{
		$eventType	 = $data->eventType;
		$model		 = self::getModel($data, $bkgModel);
		switch ($eventType)
		{
//			case "leftForPickup":
//				$model		 = self::setLeftForPickup($model);
//				break;
			case "arrived":
				$model	 = self::setArrived($model);
				break;
			case "tripStart":
				$model	 = self::setTripStart($model, $data);
				break;
			case "tripEnd":
				$model	 = self::setTripStop($model, $data);
				//$trackObj	 = self::setStopTripTransactions($model);
				break;
			case "driverPosition":
				$model	 = self::setDriverPosition($model);
				break;
		}
		//return [$model, $trackObj];
		return $model;
	}

	/**
	 * 
	 * @param Booking $model
	 * @return type
	 */
	public static function setLeftForPickup($model)
	{
		//$model->btlBkg->bkgTrack->bkg_trip_arrive_time = $model->btl_sync_time;
		return $model;
	}

	/**
	 * 
	 * @param type $data
	 * @return \BookingTrackLog
	 */
	public static function getModel($data, $bkgModel)
	{
		$deviceTrack				 = $data->deviceTrack;
		$coordinate					 = $deviceTrack->coordinates;
		$model						 = new \BookingTrackLog();
		$model->btl_event_type_id	 = $model->getEventIdByEventType($data->eventType);
		$model->btl_bkg_id			 = $bkgModel->bkg_id;
		$model->btl_sync_time		 = date("Y-m-d H:i:s", strtotime($data->lastUpdate));
		$coordinates				 = new \Beans\common\Coordinates();
		$model->btl_coordinates		 = $coordinates->getCoordinateData($model, $coordinate);
		$userInfo					 = \UserInfo::getInstance();
		$model->btl_event_platform	 = \UserInfo::$platform;
		$model->btl_user_type_id	 = \UserInfo::TYPE_DRIVER;
		$model->btl_user_id			 = $userInfo->userId;
		$model->btl_created			 = date("Y-m-d H:i:s");
		$model->btl_bcb_id			 = $bkgModel->bkg_bcb_id;
//exit;
		return $model;
	}

	/**
	 * 
	 * @param type $data
	 * @param type $eventType
	 * @return \BookingTrackLog
	 */
	public static function setModelData($data, $eventType)
	{
		$deviceTrack				 = $data->deviceTrack;
		$coordinate					 = $deviceTrack->coordinates;
		$model						 = new \BookingTrackLog();
		$model->btl_event_type_id	 = $eventType;

		$bkgModel					 = \Booking::model()->findByBookingid($data->orderReferenceNumber);
		if($bkgModel->bkg_id == NULL || $bkgModel->bkg_id == '')
		{
			$bkgModel = \Booking::model()->findByPk($data->refId);
		}
		
		$model->btl_bkg_id			 = $bkgModel->bkg_id;
		$model->btl_sync_time		 = date("Y-m-d H:i:s", strtotime($data->lastUpdate));
		$coordinates				 = new \Beans\common\Coordinates();
		$model->btl_coordinates		 = $coordinates->getCoordinateData($model, $coordinate);
		//$model->btl_coordinates		 = $data->deviceTrack->coordinates->lat . "," . $data->deviceTrack->coordinates->lng;
		$userInfo					 = \UserInfo::getInstance();
		$model->btl_event_platform	 = \UserInfo::$platform;
		$model->btl_user_type_id	 = ($data->userType == 4) ? \UserInfo::TYPE_ADMIN : \UserInfo:: TYPE_DRIVER;
		$model->btl_user_id			 = $userInfo->userId;
		//$model->btl_created			 = new \CDbExpression('NOW()');	
		$model->btl_created			 = date("Y-m-d H:i:s");
		$model->btl_bcb_id			 = $bkgModel->bkg_bcb_id;

		return $model;
	}

	/**
	 * 
	 * @param Booking $model
	 * @param type $data
	 * @return type
	 */
	public static function setTripStart($model, $data)
	{
		$model->btlBkg->bkgTrack->bkg_trip_start_time		 = $model->btl_sync_time;
		$model->btlBkg->bkgTrack->bkg_trip_start_coordinates = $model->btl_coordinates;
		$model->btlBkg->bkgTrack->bkg_is_trip_verified		 = 1;

		$setData = self::setDataValue($model, $data);
		return $setData;
	}

	/**
	 * 
	 * @param type $model
	 * @param type $data
	 * @return type
	 */
	public static function setDataValue($model, $data)
	{
		if ($data->refType == 'START_TRIP_ODOMETER')
		{
			$model->btlBkg->bkgTrack->bkg_start_odometer = $data->refValue;
		}
		if ($data->refType == 'STOP_TRIP_ODOMETER')
		{
			$model->btlBkg->bkgTrack->bkg_end_odometer = $data->refValue;
		}
		return $model;
	}

}
