<?php

/**
 * This is the model class for table "agent_messages".
 *
 * The followings are the available columns in table 'agent_messages':
 * @property integer $agt_msg_id
 * @property integer $agt_agent_id
 * @property integer $agt_event_id
 * @property integer $agt_agent_email
 * @property integer $agt_agent_sms
 * @property integer $agt_agent_app
 * @property integer $agt_trvl_email
 * @property integer $agt_trvl_sms
 * @property integer $agt_trvl_app
 * @property integer $agt_rm_email
 * @property integer $agt_rm_sms
 * @property integer $agt_rm_app
 * @property integer $agt_agent_whatsapp
 * @property integer $agt_trvl_whatsapp
 * @property integer $agt_rm_whatsapp
 * @property integer $agt_active
 * @property string $agt_created
 * @property string $agt_modified
 */
class AgentMessages extends CActiveRecord
{

	public $cabTypes = [1 => 'hatchback', 2 => 'suv', 3 => 'sedan', 5 => 'sedan', 6 => 'suv'];

	//Events for email category
	const BOOKING_CONF_WITH_PAYMENTINFO	 = 1;
	const BOOKING_CONF_WITHOUT_PAYMENTINFO = 2;
	const PAYMENT_CONFIRM					 = 3;
	const PAYMENT_FAILED					 = 4;
	const BOOKING_EDIT					 = 5;
	const CAB_ASSIGNED					 = 6;
	const INVOICE							 = 7;
	const RATING_AND_REVIEWS				 = 8;
	// const BOOK_GOZO_AGAIN = 10;
	const RECONFIRM_BEFORE_PICKUP			 = 9;
	const RESCHEDULE_REQUEST				 = 10;
	const REMINDER_ADVANCE				 = 11;
	//   const PRICE_GUARANTEE = 14;  //priceGuaranteeMail();
	const CAB_DRIVER_DETAIL				 = 12;
	const CANCEL_TRIP						 = 13;
	const SEND_PAYMENT_LINK				 = 14;
	const MSG_RETURN_OR_ONWARD_TRIP		 = 15;

//  const PRE_AUTO_CANCEL_BEFORE_PICKUP = 17;
//  const POST_AUTO_CANCEL_BEFORE_PICKUP = 18;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'agent_messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('agt_agent_id', 'required'),
			array('agt_agent_id, agt_event_id, agt_agent_email, agt_agent_sms, agt_agent_app, agt_trvl_email, agt_trvl_sms, agt_trvl_app, agt_rm_email, agt_rm_sms, agt_rm_app, agt_active', 'numerical', 'integerOnly' => true),
			array('agt_created, agt_modified', 'safe'),
			//  array('agt_agent_id,agt_event_id','checkduplicate', 'on'=>'insert'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('agt_msg_id, agt_agent_id, agt_event_id, 
			agt_agent_email, agt_agent_sms, agt_agent_app, agt_agent_whatsapp,
			agt_trvl_email, agt_trvl_sms, agt_trvl_app,agt_trvl_whatsapp, agt_rm_email, agt_rm_sms, agt_rm_app, agt_rm_whatsapp,
			agt_active, agt_created, agt_modified', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'agt_msg_id'		 => 'Agt Msg',
			'agt_agent_id'		 => 'Agent ID',
			'agt_event_id'		 => 'Event ID',
			'agt_agent_email'	 => 'IS Agent Email',
			'agt_agent_sms'		 => 'Is Agent Sms',
			'agt_agent_app'		 => 'Is Agent App',
			'agt_trvl_email'	 => 'Is Traveller Email',
			'agt_trvl_sms'		 => 'Is Traveller Sms',
			'agt_trvl_app'		 => 'Is Traveller App',
			'agt_rm_email'		 => 'Is Relationship Manager Email',
			'agt_rm_sms'		 => 'Is Relationship Manager Sms',
			'agt_rm_app'		 => 'Is Relationship Manager App',
			'agt_agent_whatsapp' => 'Is Agent WhatsApp',
			'agt_trvl_whatsapp'	 => 'Is Traveller WhatsApp',
			'agt_rm_whatsapp'	 => 'Is Relationship Manager WhatsApp',
			'agt_active'		 => 'Active',
			'agt_created'		 => 'Created',
			'agt_modified'		 => 'Modified',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('agt_msg_id', $this->agt_msg_id);
		$criteria->compare('agt_agent_id', $this->agt_agent_id);
		$criteria->compare('agt_event_id', $this->agt_event_id);
		$criteria->compare('agt_agent_email', $this->agt_agent_email);
		$criteria->compare('agt_agent_sms', $this->agt_agent_sms);
		$criteria->compare('agt_agent_app', $this->agt_agent_app);
		$criteria->compare('agt_trvl_email', $this->agt_trvl_email);
		$criteria->compare('agt_trvl_sms', $this->agt_trvl_sms);
		$criteria->compare('agt_trvl_app', $this->agt_trvl_app);
		$criteria->compare('agt_rm_email', $this->agt_rm_email);
		$criteria->compare('agt_rm_sms', $this->agt_rm_sms);
		$criteria->compare('agt_rm_app', $this->agt_rm_app);
		$criteria->compare('agt_active', $this->agt_active);
		$criteria->compare('agt_created', $this->agt_created, true);
		$criteria->compare('agt_modified', $this->agt_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgentMessages the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function checkduplicate($attributes)
	{
		$agtMsgModel = $this->getByEventAndAgent($this->agt_agent_id, $this->agt_event_id);
		if ($agtMsgModel != '')
		{
			$this->addError('agt_event_id', "Event already exists for this agent.");
			return false;
		}
		return true;
	}

	public static function getEvents($key = '')
	{
		$arr = [
			1	 => "Booking confirmation with price details",
			2	 => "Booking confirmation without price details",
			3	 => "Payment confirmation",
			5	 => "Booking details changed",
			6	 => "Cab assigned",
			7	 => "Invoice",
			8	 => "Rating and Review",
			9	 => "Reconfirm Booking before pickup",
			11	 => "Pickup reminder in advance",
			//12	 => "Cab and driver details",
			13	 => "Booking cancellation",
			14	 => "Payment Link",
			15	 => "Return Trip",
		];
		if ($key != '')
		{
			return $arr[$key];
		}
		return $arr;
	}

	public function getByEventAndAgent($agent, $event)
	{
		return $this->find('agt_agent_id=:agent AND agt_event_id=:event', ['agent' => $agent, 'event' => $event]);
	}

	public function getMessageDefaults($key)
	{
		if ($key == AgentMessages::BOOKING_CONF_WITH_PAYMENTINFO || $key == AgentMessages::PAYMENT_CONFIRM || $key == AgentMessages::PAYMENT_FAILED || $key == AgentMessages::BOOKING_EDIT || $key == AgentMessages::CAB_ASSIGNED || $key == AgentMessages::INVOICE || $key == AgentMessages::RATING_AND_REVIEWS || $key == AgentMessages::RESCHEDULE_REQUEST || $key == AgentMessages::CAB_DRIVER_DETAIL || $key == AgentMessages::CANCEL_TRIP)
		{
			$this->agt_agent_email	 = 1;
			$this->agt_agent_sms	 = 1;
		}
		if ($key == AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO || $key == AgentMessages::CAB_ASSIGNED)
		{
			$this->agt_trvl_email	 = 1;
			$this->agt_trvl_sms		 = 1;
		}
	}

	public function callMegaCabAPI($fName, $params)
	{
		//$fName        = "vendorDriverAllocation";
		$apiServerUrl	 = "https://capi.megacabs.com";
		$functionUrl	 = "/api/v1/outstation/";
		$apiKey			 = "53247267-d42c-41da-92c2-271c8f9d3a1a";

		$apiURL		 = $apiServerUrl . $functionUrl . $fName . "?apiKey=" . $apiKey;
		$postData	 = json_encode($params);

		$ch					 = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($postData))
		);
		$jsonResponse		 = curl_exec($ch);
		$responseParamList	 = json_decode($jsonResponse, true);
		return $responseParamList;
	}

	public function getTelegramRequest($model, $typeAction, $telegramId)
	{
		/* @var $model Agent */
		$partnerRequest			 = new PartnerRequest();
		$partnerRequest->type	 = "telegramAuthentication";
		$partnerRequest->agentId = $model->agt_id;
		$partnerRequest->pid	 = $telegramId;
		return $partnerRequest;
	}

	public function getRequest($model, $typeAction, $pid)
	{
		/* @var $model Booking */
		$modelBcb							 = $model->bkgBcb;
		//$modelBcb->refresh();
		//$bookingPref						 = BookingPref::model()->getByBooking($model->bkg_id);
		$bookingTrack						 = $model->bkgTrack;
		$partnerRequest						 = new PartnerRequest();
		$partnerRequest->bookingId			 = $model->bkg_booking_id;
		$partnerRequest->bookingStatusCode	 = $model->bkg_status;
		$partnerRequest->pid				 = $pid;

		$partnerRequest->cgst				 = ($model->bkgInvoice->bkg_cgst > 0) ? $model->bkgInvoice->bkg_cgst : ($model->bkgInvoice->bkg_service_tax / 2);
		$partnerRequest->sgst				 = ($model->bkgInvoice->bkg_sgst > 0) ? $model->bkgInvoice->bkg_sgst : $model->bkgInvoice->bkg_service_tax - $partnerRequest->cgst;
		$partnerRequest->tollTax			 = ($model->bkgInvoice->bkg_toll_tax | 0) + ($model->bkgInvoice->bkg_state_tax | 0);
		$partnerRequest->totalAmount		 = $model->bkgInvoice->bkg_total_amount;
		$partnerRequest->totalDistanceInKm	 = $model->bkg_trip_distance;
		$partnerRequest->totalTimeInMins	 = $model->bkg_trip_duration;
		$partnerRequest->baseFare			 = $model->bkgInvoice->calculateGrossAmount();
		$partnerRequest->tripEndDateTime	 = $model->getBookingTripEndDateTimeOnAfter1Oct16($model->bkg_id);

		$partnerRequest->cancelReasonId		 = $model->bkg_cancel_id;
		$partnerRequest->cancellationReason	 = $model->bkg_cancel_delete_reason;
		$partnerRequest->cancellationCharge	 = $model->bkgInvoice->bkg_cancel_charge | 0;
		$partnerRequest->refundAmount		 = $model->bkgInvoice->bkg_refund_amount | 0;

		$partnerRequest->driverName		 = ($modelBcb->bcb_driver_name != '') ? $modelBcb->bcb_driver_name : $modelBcb->bcbDriver->drv_name;
		$partnerRequest->driverMobile	 = $modelBcb->bcb_driver_phone;

		if ($partnerRequest->driverMobile == null || $partnerRequest->driverMobile == "")
		{
			$cttId = ContactProfile::getByEntityId($modelBcb->bcb_driver_id, 3);
			if ($cttId != '')
			{
				$partnerRequest->driverMobile = ContactPhone::model()->getContactPhoneById($cttId);
				if (empty($partnerRequest->driverMobile))
				{
					$drvCtn							 = ContactPhone::model()->findByContactID($cttId);
					$partnerRequest->driverMobile	 = $drvCtn[0]->phn_phone_no;
				}
			}
		}

		$partnerRequest->cabNo		 = $modelBcb->bcb_cab_number;
		$partnerRequest->cabModel	 = VehicleCategory::model()->getCabByBkgId($model->bkg_id);
		$partnerRequest->cabName	 = VehicleCategory::model()->getCabNameBkgId($model->bkg_id);
		$partnerRequest->otp		 = $model->bkgTrack->bkg_trip_otp;

		#$vehicleCatId	 = VcvCatVhcType::model()->getVehicleCatId($model->bkg_vehicle_type_id);

		$arrSvcData				 = SvcClassVhcCat::model()->getVctSvcList("detail", 0, 0, $model->bkg_vehicle_type_id);
		$vehicleCatId			 = $arrSvcData['scv_vct_id'];
		$vehicleCatId			 = ($vehicleCatId != '') ? $vehicleCatId : '';
		$partnerRequest->vctId	 = $vehicleCatId;
		$partnerRequest->vctType = $this->cabTypes[$vehicleCatId];

		$partnerRequest->mmtBookingId	 = "{$model->bkg_agent_ref_code}";
		$partnerRequest->bkgId			 = $model->bkg_id;
		$partnerRequest->orderRefId		 = $model->bkg_agent_ref_code;

		$partnerRequest->typeAction = $typeAction;
		switch ($typeAction)
		{
			case PartnerApiTracking::VENDOR_COMPLETE:
				$partnerRequest->fName			 = 'vendorComplete';
				$partnerRequest->bookingStatus	 = 'Completed';
				break;
			case PartnerApiTracking::VENDOR_CANCELLATION:
				$partnerRequest->fName			 = 'vendorCancellation';
				$partnerRequest->bookingStatus	 = "Cancelled";
				break;
			case PartnerApiTracking::VENDOR_DRIVER_ALLOCATION:
				$partnerRequest->fName			 = 'vendorDriverAllocation';
				$partnerRequest->type			 = "driverDetail";
				$partnerRequest->bookingStatus	 = "driverDetail";
				$partnerRequest->transferzStatus = "assign-driver";
				break;
			case AgentApiTracking::TYPE_OTP_UPDATE:
				$partnerRequest->type			 = "tripStart";
				if ($model->bkg_agent_id == 450 || $model->bkg_agent_id == 18190)
				{
					$partnerRequest->type					 = "vendorTripRequest";
					$startReading							 = TripTracking::model()->getOdometerReading($model->bkg_id);
					$partnerRequest->odometer_start_reading	 = $startReading | 0;
				}
//				$tripStartDate					 = $this->tripStartTimebyBkgId($model->bkg_id);
				$tripStartDate					 = TripTracking::model()->tripStartTimebyBkgId($model->bkg_id);
				$partnerRequest->tripStartTime	 = $tripStartDate == '' ? new CDbExpression('NOW()') : $tripStartDate;
				break;
			case AgentApiTracking::TYPE_CAB_DRIVER_UPDATE:
				$partnerRequest->otp			 = $bookingTrack->bkg_trip_otp;
				$partnerRequest->type			 = "driverDetail";
				$partnerRequest->bookingStatus	 = "driverDetail";
				if ($model->bkgUserInfo->bkg_country_code == 91)
				{
					$partnerRequest->driverMobile	 = Filter::processDriverNumber($partnerRequest->driverMobile, $model->bkg_agent_id);
					$partnerRequest->driverMobile	 = BookingPref::getDriverNumber($model, $partnerRequest->driverMobile);

					//			$partnerRequest->driverMobile = Yii::app()->params['customerToDriver'];
					//			if ($model->bkg_agent_id == 450 || $model->bkg_agent_id == 18190)
					//			{
					//				$partnerRequest->driverMobile = Yii::app()->params['customerToDriverforMMT'];
					//			}
				}
				$partnerRequest->drvId		 = $modelBcb->bcb_driver_id;
				$partnerRequest->vhcId		 = $modelBcb->bcb_cab_id;
				$partnerRequest->pickupdate	 = $model->bkg_pickup_date;
				break;

			case AgentApiTracking::TYPE_CAB_DRIVER_REASSIGN:
				Logger::trace("cab driver reassign for booking id" . $model->bkg_id);
				$partnerRequest->otp			 = $bookingTrack->bkg_trip_otp;
				$partnerRequest->type			 = "reAssign";
				$partnerRequest->bookingStatus	 = "reAssign";
				if ($model->bkgUserInfo->bkg_country_code == 91)
				{
					Logger::info("country code is for india");
					$partnerRequest->driverMobile	 = Filter::processDriverNumber($partnerRequest->driverMobile, $model->bkg_agent_id);
					$partnerRequest->driverMobile	 = BookingPref::getDriverNumber($model, $partnerRequest->driverMobile);
					//					$partnerRequest->driverMobile = Yii::app()->params['customerToDriver'];
					//				if ($model->bkg_agent_id == 450 || $model->bkg_agent_id == 18190)
					//			{
					//			$partnerRequest->driverMobile = Yii::app()->params['customerToDriverforMMT'];
					//	}
				}
				$partnerRequest->drvId		 = $modelBcb->bcb_driver_id;
				$partnerRequest->vhcId		 = $modelBcb->bcb_cab_id;
				$partnerRequest->pickupdate	 = $model->bkg_pickup_date;
				break;

			case AgentApiTracking::TYPE_TRIP_START:
				$partnerRequest->type = "tripDetail";
				if ($model->bkg_agent_id == 450 || $model->bkg_agent_id == 18190)
				{
					$partnerRequest->type = "tripStart";
				}
				$partnerRequest->bookingStatus	 = "tripStart";
				$partnerRequest->transferzStatus = "in-progress";
				$triplogDetails					 = BookingTrackLog::model()->getdetailByEvent($model->bkg_id, BookingTrack::TRIP_START);
				$coordinate						 = explode(',', $triplogDetails['btl_coordinates']);

				$coordinatesDistance = Filter::calculateDistance($model->bkg_pickup_lat, $model->bkg_pickup_long, $coordinate[0], $coordinate[1]);
				$address			 = explode(',', $model->bkg_pickup_address);
				$startKmLimit		 = (int) ( count($address) > 3) ? 5 : \Config::get('ride.startkmlimit');
				if ($coordinatesDistance >= $startKmLimit)
				{
					$triplogDetails = BookingTrackLog::model()->getdetailByEvent($model->bkg_id, BookingTrack::DRIVER_ARRIVED);
					if ($triplogDetails)
					{
						$coordinate = explode(',', $triplogDetails['btl_coordinates']);
					}
				}

				$partnerRequest->lattitude			 = $coordinate[0];
				$partnerRequest->longitude			 = $coordinate[1];
				$partnerRequest->tripStartTime		 = $triplogDetails['btl_sync_time'];
				$partnerRequest->tripStatus			 = 1;
				$covidChecking						 = BookingTrack::model()->getCovidDetails($model->bkg_id);
				$partnerRequest->faceMask			 = ($covidChecking['btk_is_selfie'] == 1) ? true : false;
				$partnerRequest->sanitizationKit	 = ($covidChecking['btk_is_sanitization_kit'] == 1) ? true : false;
				$partnerRequest->arogyaSetu			 = ($covidChecking['btk_aarogya_setu'] == 1) ? true : false;
				$partnerRequest->infographicCard	 = ($covidChecking['btk_safetyterm_agree'] != null) ? true : false;
				$fmdocId							 = BookingPayDocs::model()->getDocId($model->bkg_id, BookingTrack::TRIP_SELFIE);
				$skdocId							 = BookingPayDocs::model()->getDocId($model->bkg_id, BookingTrack::TRIP_SANITIZER_KIT);
				$baseURL							 = Yii::app()->params['fullBaseURL'];
				$partnerRequest->faceMaskUrl		 = $baseURL . Yii::app()->createUrl('track/file', ['id' => $fmdocId, 'hash' => Yii::app()->shortHash->hash($fmdocId)]);
				$partnerRequest->sanitizationKitUrl	 = $baseURL . Yii::app()->createUrl('track/file', ['id' => $skdocId, 'hash' => Yii::app()->shortHash->hash($skdocId)]);
				$partnerRequest->infographicUrl		 = $baseURL . Yii::app()->createUrl('track/termsfile');
				break;
			case AgentApiTracking::TYPE_TRIP_END:
				$partnerRequest->type				 = "tripDetail";
				if ($model->bkg_agent_id == 450 || $model->bkg_agent_id == 18190)
				{
					$partnerRequest->type = "tripEnd";
				}
				$partnerRequest->bookingStatus		 = "tripEnd";
				$partnerRequest->transferzStatus	 = "complete";
				$triplogDetails						 = BookingTrackLog::model()->getdetailByEvent($model->bkg_id, BookingTrack::TRIP_STOP);
				$coordinate							 = explode(',', $triplogDetails['btl_coordinates']);
				$partnerRequest->lattitude			 = $coordinate[0];
				$partnerRequest->longitude			 = $coordinate[1];
				$partnerRequest->tripStartTime		 = $triplogDetails['btl_sync_time'];
				$partnerRequest->tripStatus			 = 2;
				$partnerRequest->extrakm			 = $model->bkgInvoice->bkg_extra_km | 0;
				$partnerRequest->extrakmCharge		 = $model->bkgInvoice->bkg_extra_km_charge | 0;
				$partnerRequest->extraMin			 = $model->bkgInvoice->bkg_extra_min | 0;
				$partnerRequest->extraMinCharge		 = $model->bkgInvoice->bkg_extra_per_min_charge | 0;
				$partnerRequest->extraTimeCharge	 = $model->bkgInvoice->bkg_extra_total_min_charge | 0;
				$partnerRequest->extraMinutes		 = ($model->bkgInvoice->bkg_extra_total_min_charge != 0) ? $model->bkgInvoice->bkg_extra_min : 0;
				$partnerRequest->nightCharges		 = 0;
				$partnerRequest->advanceAmount		 = $model->bkgInvoice->bkg_advance_amount | 0;
				$partnerRequest->amountToBeCollected = $model->bkgInvoice->bkg_vendor_collected | 0;
				$partnerRequest->totalAmount		 = $model->bkgInvoice->bkg_total_amount;
				$partnerRequest->total_travelled_km	 = ROUND($model->bkg_trip_distance + $model->bkgInvoice->bkg_extra_km);
				$partnerRequest->waitingCharge		 = 0;
				$baseURL							 = Yii::app()->params['fullBaseURL'];
				$tollReceiptsUrl					 = "";
				$tollChargesReceipts				 = BookingPayDocs::model()->getDocId($model->bkg_id, 2);
				foreach ($tollChargesReceipts as $value)
				{
					$tollChargesReceipts .= $baseURL . Yii::app()->createUrl('track/file', ['id' => $value['bpay_id'], 'hash' => Yii::app()->shortHash->hash($value['bpay_id'])]);
				}
				$partnerRequest->tollChargesReceipts = $tollChargesReceipts;
				$partnerRequest->tollCharges		 = $model->bkgInvoice->bkg_extra_toll_tax | 0;
				$partnerRequest->stateTaxCharges	 = $model->bkgInvoice->bkg_extra_state_tax | 0;
				$partnerRequest->parkingCharges		 = 0;
				$partnerRequest->airportEntryFee	 = 0;
				$partnerRequest->extra_charge		 = ($model->bkgInvoice->bkg_extra_toll_tax | 0) + ($model->bkgInvoice->bkg_extra_state_tax | 0) + ($model->bkgInvoice->bkg_extra_km_charge | 0);

				$partnerRequest->baseFare		 = $model->bkgInvoice->bkg_base_amount | 0;
				$partnerRequest->extrakm		 = $model->bkgInvoice->bkg_extra_km | 0;
				$partnerRequest->extrakmCharge	 = $model->bkgInvoice->bkg_extra_km_charge | 0;
				$partnerRequest->extraMinutes	 = $model->bkgInvoice->bkg_extra_min | 0;
				$partnerRequest->extraMinCharge	 = $model->bkgInvoice->bkg_extra_total_min_charge | 0;
				$partnerRequest->discount		 = $model->bkgInvoice->bkg_discount_amount | 0;
				$partnerRequest->driverAllowance = $model->bkgInvoice->bkg_driver_allowance_amount | 0;
				$partnerRequest->extraTollTax	 = $model->bkgInvoice->bkg_extra_toll_tax | 0;
				$partnerRequest->extraStateTax	 = $model->bkgInvoice->bkg_extra_state_tax | 0;
				$partnerRequest->gst			 = $model->bkgInvoice->bkg_service_tax | 0;
				//$partnerRequest->airportEntryFee = $model->bkgInvoice->bkg_airport_entry_fee | 0;
				break;
			case AgentApiTracking::TYPE_TRIP_CANCELLED:
				$partnerRequest->type			 = "tripCancelled";
				//if reason is "price issue" we map with some "other cancel" reason
				$cancelId						 = $model->bkg_cancel_id;
				if ($cancelId == 38)
				{
					$cancelId = 17;
				}
				$partnerRequest->bookingStatus		 = CancelReasons::getById($cancelId)['cnr_reason'];
				break;
			case AgentApiTracking::TYPE_LEFT_FOR_PICKUP:
				$partnerRequest->type				 = "leftforpickup";
				$partnerRequest->bookingStatus		 = "leftforpickup";
				$partnerRequest->transferzStatus	 = "driver-underway";
				$triplogDetails						 = BookingTrackLog::model()->getdetailByEvent($model->bkg_id, BookingTrack::GOING_FOR_PICKUP);
				$coordinate							 = explode(',', $triplogDetails['btl_coordinates']);
				$partnerRequest->lattitude			 = $coordinate[0];
				$partnerRequest->longitude			 = $coordinate[1];
				$partnerRequest->tripStartTime		 = $triplogDetails['btl_sync_time'];
				break;
			case AgentApiTracking::TYPE_NO_SHOW:
				$partnerRequest->type				 = "noshow";
				$partnerRequest->bookingStatus		 = "noshow";
				$triplogDetails						 = BookingTrackLog::model()->getdetailByEvent($model->bkg_id, BookingTrack::NO_SHOW);
				$coordinate							 = explode(',', $triplogDetails['btl_coordinates']);
				$partnerRequest->lattitude			 = $coordinate[0];
				$partnerRequest->longitude			 = $coordinate[1];
				$partnerRequest->tripStartTime		 = $triplogDetails['btl_sync_time'];
				break;
			case AgentApiTracking::TYPE_ARRIVED:
				$partnerRequest->type				 = "arrived";
				$partnerRequest->bookingStatus		 = "arrived";
				$partnerRequest->transferzStatus	 = "driver-arrived";
				$triplogDetails						 = BookingTrackLog::model()->getdetailByEvent($model->bkg_id, BookingTrack::DRIVER_ARRIVED);
				$coordinate							 = explode(',', $triplogDetails['btl_coordinates']);
				$partnerRequest->lattitude			 = $coordinate[0];
				$partnerRequest->longitude			 = $coordinate[1];
				$partnerRequest->tripStartTime		 = $triplogDetails['btl_sync_time'];
				break;
			case PartnerApiTracking::CANCEL:
				$partnerRequest->type				 = "customerCancel";
				$partnerRequest->bookingStatus		 = "customerCancel";
				$partnerRequest->cancelReasonId		 = $model->bkg_cancel_id;
				$partnerRequest->cancellationReason	 = $model->bkg_cancel_delete_reason;
				$partnerRequest->cancelReasonDesc	 = CancelReasons::model()->findByPk($model->bkg_cancel_id)->cnr_admin_text;
				$partnerRequest->cancellationCharge	 = $model->bkgInvoice->bkg_cancel_charge | 0;
				$partnerRequest->refundAmount		 = $model->bkgInvoice->bkg_refund_amount | 0;

				break;
			case AgentApiTracking::TYPE_GET_PASSENGER_DETAILS:
				$partnerRequest->type						 = "customerDetails";
				break;
			case AgentApiTracking::TYPE_UPDATE_LAST_LOCATION:
				$partnerRequest->type						 = "updateLastLocation";
				$partnerRequest->bookingStatus				 = "updateLastLocation";
				$partnerRequest->transferzStatus			 = "driver-position";
				$partnerRequest->booking_id					 = $model->bkg_id;
				
//				$lastCoordinate								 = explode(",", $model->bkgTrack->btk_last_coordinates);
//				$partnerRequest->lattitude					 = $lastCoordinate[0];
//				$partnerRequest->longitude					 = $lastCoordinate[1];
//				$partnerRequest->timestamp					 = $model->bkgTrack->btk_last_coordinates_time;

				$partnerRequest->lattitude					 = $model->bkgBcb->bcbDriver->driverStats->drv_last_loc_lat;
				$partnerRequest->longitude					 = $model->bkgBcb->bcbDriver->driverStats->drv_last_loc_long;
				$partnerRequest->timestamp					 = $model->bkgBcb->bcbDriver->driverStats->drv_last_loc_date;

				break;
			case AgentApiTracking::TYPE_REVERSE_BOOKING_ACCEPT:
				$partnerRequest->type						 = "bookingAccepted";
				$partnerRequest->partner_reference_number	 = $model->bkg_id;
				$partnerRequest->order_reference_number		 = $model->bkg_agent_ref_code;
				$partnerRequest->communication_type			 = "PRE";
				$partnerRequest->verification_type			 = "OTP";
				$partnerRequest->verification_code			 = $model->bkgTrack->bkg_trip_otp;
				break;
			case AgentApiTracking::TYPE_PAYMENT_DETAILS:
				$partnerRequest->type						 = "paymentDetails";
				break;
			case AgentApiTracking::TYPE_ADD_PAYMENT:
				$partnerRequest->type						 = "addPayment";
				$partnerRequest->amount_paid				 = $model->bkgInvoice->bkg_advance_amount;
				break;

			default:
				break;
		}
		return $partnerRequest;
	}

	public function pushApiCall($model, $typeAction = 0, $pid = null)
	{
		if ($typeAction != AgentApiTracking::TYPE_TELEGRAM_AUTHENTICATION)
		{
			$partnerObj = Filter::getPartnerObject($model->bkg_agent_id);
		}
		if ($partnerObj != null && $typeAction > 0 && $model->bkg_agent_ref_code != null)
		{
			$partnerRequest	 = $this->getRequest($model, $typeAction, $pid);
			$partnerResponse = $partnerObj->initiateRequest($partnerRequest);
			return $partnerResponse;
		}
		if ($typeAction == AgentApiTracking::TYPE_TELEGRAM_AUTHENTICATION)
		{
			$agentId		 = 31381;
			$partnerObj		 = Filter::getPartnerObject($agentId);
			$partnerRequest	 = $this->getTelegramRequest($model, $typeAction, $pid);
			$partnerResponse = $partnerObj->intiateTelegramRequest($partnerRequest);
			return $partnerResponse;
		}
	}

	public function getNotificationDataByBkgId($bkgId)
	{
		$notifydata	 = [];
		$arrEvents	 = AgentMessages::getEvents();
		foreach ($arrEvents as $key => $value)
		{
			$bkgMessagesModel					 = BookingMessages::model()->getByEventAndBookingId($bkgId, $key);
			$notifydata['agt_agent_email'][$key] = $bkgMessagesModel->bkg_agent_email;
			$notifydata['agt_agent_sms'][$key]	 = $bkgMessagesModel->bkg_agent_sms;
			$notifydata['agt_agent_app'][$key]	 = $bkgMessagesModel->bkg_agent_app;
			$notifydata['agt_trvl_email'][$key]	 = $bkgMessagesModel->bkg_trvl_email;
			$notifydata['agt_trvl_sms'][$key]	 = $bkgMessagesModel->bkg_trvl_sms;
			$notifydata['agt_trvl_app'][$key]	 = $bkgMessagesModel->bkg_trvl_app;
			$notifydata['agt_rm_email'][$key]	 = $bkgMessagesModel->bkg_rm_email;
			$notifydata['agt_rm_sms'][$key]		 = $bkgMessagesModel->bkg_rm_sms;
			$notifydata['agt_rm_app'][$key]		 = $bkgMessagesModel->bkg_rm_app;

			$notifydata['agt_agent_whatsapp'][$key]	 = $bkgMessagesModel->bkg_agent_whatsapp;
			$notifydata['agt_trvl_whatsapp'][$key]	 = $bkgMessagesModel->bkg_trvl_whatsapp;
			$notifydata['agt_rm_whatsapp'][$key]	 = $bkgMessagesModel->bkg_rm_whatsapp;
		}

		return $notifydata;
	}

	public function tripStartTimebyBkgId($bkgId)
	{
		$sql = "SELECT trl_date FROM `trip_otplog` WHERE trl_bkg_id = $bkgId";
		$row = DBUtil::queryScalar($sql);
		return $row;
	}

	public static function checkDuplicateEntry($model, $typeAction)
	{
		$params		 = array('bkgId' => $model->bkg_id, 'createDate' => $model->bkg_create_date);
		$qry		 = "SELECT COUNT(`aat_id`) as cnt FROM `agent_api_tracking`
                WHERE `aat_booking_id`= :bkgId AND aat_created_at >= :createDate AND `aat_type` = $typeAction AND `aat_status`= 1";
		$dataCount	 = DBUtil::queryScalar($qry, DBUtil::MDB(), $params);
		return $dataCount;
	}

	public static function getWATemplates($mappedOnly = false)
	{
		if ($mappedOnly)
		{
			$where = " AND wht_message_event_id > 0 ";
		}
		$sql = "SELECT wht_id,wht_template_name,wht_message_event_id  
				FROM `whatsapp_templates` 
				WHERE wht_active = 1 $where";

		$result			 = DBUtil::query($sql);
		$eventList		 = self::getEvents();
		$eventList[12]	 = "Cab and driver details";
		$data			 = [];

		foreach ($result as $row)
		{
			$eventName	 = ($row['wht_message_event_id']) ? $eventList[$row['wht_message_event_id']] : null;
			$data[]		 = ['id' => $row['wht_id'], 'template_name' => $row['wht_template_name'], 'message_event_id' => $row['wht_message_event_id'], 'message_event_name' => $eventName];
		}
		return $data;
	}

	public static function getWATemplatesEvents()
	{
		$sql = "SELECT group_concat(wht_id) wht_id,group_concat(wht_template_name) wht_template_name,wht_message_event_id 
			FROM `whatsapp_templates` 
			WHERE wht_message_event_id > 0 
			group by wht_message_event_id";

		$result		 = DBUtil::query($sql);
		$tempData	 = [];
		foreach ($result as $row)
		{
			$tempData[$row['wht_message_event_id']] = $row;
		}


		$eventList		 = self::getEvents();
		$eventList[12]	 = "Cab and driver details";
		$data			 = [];

		foreach ($eventList as $k => $v)
		{
			$tData	 = $tempData[$k];
			$data[]	 = ['id' => $tData['wht_id'], 'template_name' => $tData['wht_template_name'], 'message_event_id' => $k, 'message_event_name' => $v];
		}
		return $data;
	}

}
