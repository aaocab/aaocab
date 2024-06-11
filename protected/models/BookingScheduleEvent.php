<?php

/**
 * This is the model class for table "booking_schedule_event".
 *
 * The followings are the available columns in table 'booking_schedule_event':
 * @property integer $bse_id
 * @property integer $bse_bkg_id
 * @property integer $bse_event_id
 * @property integer $bse_event_status
 * @property string $bse_schedule_time
 * @property string $bse_addtional_data
 * @property string $bse_remarks
 * @property string $bse_create_date
 * @property string $bse_update_date
 * @property integer $bse_err_count
 * @property string $bse_last_error
 */
class BookingScheduleEvent extends CActiveRecord
{

	//event
	const CONFIRM_MESSAGE				 = 101;
	const REFUND_PROCESS				 = 102;
	const MARK_COMPLETE_PROCESS		 = 103;
	const SEND_DRIVER_DETAILS			 = 104;
	const TRACK_DRIVER_SYNC			 = 105;
	const PARTNER_PENDING_ADVANCE		 = 106;
	const SEND_NOTIFICATION_DATA		 = 107;
	const SEND_BOOKING_INVOICE		 = 108;
	const DRIVER_APP_PENALTY			 = 109;
	const GENERATE_QR_CODE			 = 110;
	const BOOKING_VND_COMPENSATION	 = 111;
	const SEND_TRAVELLER_DETAILS		 = 112;
	const POST_VENDOR_ASSIGNMENT		 = 113;
	//501
	//status
	const STATUS_PENDING				 = 0;
	const STATUS_PROCESSED			 = 1;
	const STATUS_FAILED				 = 2;
	const STATUS_REPROCESSED			 = 3;
	const STATUS_REPROCESSED_FAILED	 = 4;
	//smt score
	const SMT_SCORE_PROCESS			 = 501;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_schedule_event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bse_bkg_id, bse_event_id', 'required'),
			array('bse_bkg_id, bse_event_id, bse_event_status', 'numerical', 'integerOnly' => true),
			//array('bse_schedule_time', 'length', 'max' => 50),
			array('bse_addtional_data', 'length', 'max' => 2000),
			array('bse_remarks', 'length', 'max' => 500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bse_id, bse_bkg_id, bse_event_id, bse_event_status, bse_schedule_time, bse_addtional_data, bse_remarks, bse_create_date, bse_update_date, bse_err_count, bse_last_error', 'safe', 'on' => 'search'),
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
			'bse_id'			 => 'Bse',
			'bse_bkg_id'		 => 'Bse Bkg',
			'bse_event_id'		 => 'bse_event_id',
			'bse_event_status'	 => 'bse_event_status',
			'bse_schedule_time'	 => 'Bse Schedule Time',
			'bse_addtional_data' => 'Bse Addtional Data',
			'bse_remarks'		 => 'Bse Remarks',
			'bse_create_date'	 => 'Bse Create Date',
			'bse_update_date'	 => 'Bse Update Date',
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

		$criteria->compare('bse_id', $this->bse_id);
		$criteria->compare('bse_bkg_id', $this->bse_bkg_id);
		$criteria->compare('bse_event_id', $this->bse_event_id);
		$criteria->compare('bse_event_status', $this->bse_event_status);
		$criteria->compare('bse_schedule_time', $this->bse_schedule_time, true);
		$criteria->compare('bse_addtional_data', $this->bse_addtional_data, true);
		$criteria->compare('bse_remarks', $this->bse_remarks, true);
		$criteria->compare('bse_create_date', $this->bse_create_date, true);
		$criteria->compare('bse_update_date', $this->bse_update_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingScheduleEvent the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function EventList()
	{
		$eventlist = [
			101	 => 'Confirm Process',
			102	 => 'Refund Process',
			103	 => 'Mark Complete Process',
			106	 => 'Partner Pending Advance',
			108	 => 'Send Booking Invoice',
			109	 => 'Driver App Uses Penalty',
			110	 => 'Generate QR Code',
			111	 => 'Booking Vendor Compensation'
		];
		asort($eventlist);
		return $eventlist;
	}

	public function getEventByEventId($eventId)
	{
		$list = $this->EventList();
		return $list[$eventId];
	}

	public static function getScheduleBookingForConfirmMessages()
	{
		$params	 = array('status' => BookingScheduleEvent::STATUS_PENDING, 'event' => BookingScheduleEvent::CONFIRM_MESSAGE);
		$sql	 = "SELECT bse_bkg_id from booking_schedule_event WHERE 1 and bse_event_id=:event and 	bse_event_status=:status";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	public static function getScheduleBooking($bkgId, $event)
	{
		$params				 = array('bkgId' => $bkgId, 'event' => $event);
		$criteria			 = new CDbCriteria();
		$criteria->addCondition("bse_bkg_id=:bkgId and bse_event_id=:event and bse_event_status=0");
		$criteria->params	 = $params;
		$criteria->order	 = 'bse_id DESC';
		return BookingScheduleEvent::model()->find($criteria, $params);
	}

	public static function add($bkgId, $eventId, $remarks, $additionalData = null, $time = null)
	{
		try
		{
			$event				 = BookingScheduleEvent::model()->getEventByEventId($eventId);
			$getScheduleBooking	 = BookingScheduleEvent::checkScheduleEvent($bkgId, $eventId, $additionalData);
			if (!$getScheduleBooking)
			{
				Logger::profile("Start adding into BookingScheduleEvent for bse_bkg_id " . $bkgId . "bse_addtional_data" . $additionalData . "bse_event_id" . $eventId);
				$objBSE						 = new BookingScheduleEvent();
				$objBSE->bse_bkg_id			 = $bkgId;
				$objBSE->bse_event_id		 = $eventId;
				$objBSE->bse_event_status	 = 0;
				$objBSE->bse_addtional_data	 = $additionalData;
				$objBSE->bse_remarks		 = $remarks;
				if ($time != NULL)
				{
					$objBSE->bse_schedule_time = $time;
				}
				$success = $objBSE->save();
				Logger::profile("End === success " . $success . "new bse_id" . $objBSE->bse_id . "bse_bkg_id" . $bkgId . "bse_event_id" . $eventId);
				if (!$success)
				{
					Logger::profile("insertion process failed for " . $bkgId . "errors " . json_encode($objBSE->getErrors()));
					throw new Exception($event . "failed to initiate : " . json_encode($objBSE->getErrors()));
				}
			}
		}
		catch (Exception $e)
		{
			$event_Id = BookingLog::BOOKING_SCHEDULE_EVENT_PROCESS_FAILED;
			BookingLog::model()->createLog($bkgId, $event . "failed to initiate :" . $e->getMessage(), $userInfo, $event_Id);
			Logger::exception($e);
		}
	}

	public static function checkScheduleEvent($bkgId, $eventId, $additionalDt)
	{
		$synData = '';
		if ($additionalDt)
		{
			$driverSyncData = CJSON::decode($additionalDt);
			if ($driverSyncData['SyncEvent'] == BookingTrack::TRIP_START)
			{
				$eventCode	 = $driverSyncData['SyncEvent'];
				$synData	 = " AND JSON_CONTAINS(bse_addtional_data, $eventId, $eventCode)";
			}
		}
		$param	 = ['bkgId' => $bkgId, 'eventId' => $eventId];
		$sql	 = "SELECT * FROM booking_schedule_event WHERE bse_bkg_id  =:bkgId AND bse_event_id =:eventId AND bse_event_status=0 $synData";
		$result	 = DBUtil::queryRow($sql, DBUtil::MDB(), $param);
		return $result;
	}

	/**
	 * @return Array
	 */
	public static function getRefundList($bkgId = null)
	{
		$event	 = BookingScheduleEvent::REFUND_PROCESS;
		$params	 = array('event' => $event);
		$bkg	 = '';
		if ($bkgId > 0)
		{
			$bkg = " AND bse_bkg_id = $bkgId";
		}
		$sql	 = "SELECT * FROM booking_schedule_event WHERE bse_event_id =:event  $bkg
					AND bse_event_status IN(0,2) AND bse_err_count <= 5 ORDER BY bse_id DESC";
		$records = DBUtil::query($sql, DBUtil::MDB(), $params);
		return $records;
	}

	/**
	 * @return Array
	 */
	public static function getMarkCompleteList()
	{
		$event	 = BookingScheduleEvent::MARK_COMPLETE_PROCESS;
		$params	 = array('event' => $event);
		$sql	 = "SELECT * FROM booking_schedule_event WHERE bse_event_id =:event AND bse_event_status IN(0,2) AND bse_err_count <= 5 ORDER BY bse_id DESC";
		$records = DBUtil::query($sql, DBUtil::MDB(), $params);
		return $records;
	}

	public static function markCompleteEvent()
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$records = self::getMarkCompleteList();
		foreach ($records as $row)
		{
			//$transaction = null;
			try
			{
				$userInfo	 = UserInfo::getInstance();
				$bkg		 = $row['bse_bkg_id'];
				$bseid		 = $row['bse_id'];
				$model		 = Booking::model()->findByPk($bkg);
				$modelbse	 = BookingScheduleEvent::model()->findByPk($bseid);
				if ($model->bkgBcb->bcb_trip_status == 6)
				{
					//$transaction = DBUtil::beginTransaction();

					$findmatchBooking = Booking::model()->getMatchBookingIdbyTripId($model->bkgBcb->bcb_id);
					foreach ($findmatchBooking as $valBookingID)
					{
						$date = new DateTime($model->bkg_pickup_date);
						if ($valBookingID['bkg_vendor_collected'] > 0)
						{
							Logger::trace("add collected in" . $valBookingID['bkg_id']);
							$addVendorCollected = AccountTransactions::model()->AddVendorCollection($model->bkgBcb->bcb_vendor_amount, $valBookingID['bkg_vendor_collected'], $model->bkgBcb->bcb_id, $valBookingID['bkg_id'], $model->bkgBcb->bcb_vendor_id, $date->format('Y-m-d H:i:s'), $userInfo, $model->bkgBcb->bcb_trip_status);
							if (!$addVendorCollected)
							{
								Logger::trace("failed to add Add Vendor Collection" . $valBookingID['bkg_id']);
								$remarks = "Add vendor collected process failed";
								throw new Exception($remarks);
							}
						}
						Logger::trace("add collected out" . $valBookingID['bkg_id']);
						$addTripPurchase = AccountTransactions::model()->AddVendorPurchaseTrip($model->bkgBcb->bcb_vendor_amount, $valBookingID['bkg_vendor_collected'], $model->bkgBcb->bcb_id, $valBookingID['bkg_id'], $model->bkgBcb->bcb_vendor_id, $date->format('Y-m-d H:i:s'), $userInfo, $model->bkgBcb->bcb_trip_status);
						Logger::trace("add trip amount done tripid" . $model->bkgBcb->bcb_id);
						if (!$addTripPurchase)
						{
							Logger::trace("failed to add Add Vendor Purchase Trip" . $model->bkgBcb->bcb_id);
							$remarks = "Add Trip Purchase process failed";
							throw new Exception($remarks);
						}
						$bkgModel = booking::model()->findByPk($valBookingID['bkg_id']);
						if ($bkgModel->bkg_agent_id > 0)
						{
							$agentCommission = ($bkgModel->bkgInvoice->bkg_partner_commission + $bkgModel->bkgInvoice->bkg_partner_extra_commission);
							Logger::trace("calculate Commission" . $bkgModel->bkg_id);
							if ($agentCommission > 0)
							{
								Logger::trace("add Commission in" . $bkgModel->bkg_id);
								$addCommission = AccountTransactions::model()->AddCommission($bkgModel->bkg_pickup_date, $bkgModel->bkg_id, $bkgModel->bkg_agent_id, $agentCommission);
								if (!$addCommission)
								{
									Logger::trace("failed to add Add Commission" . $bkgModel->bkg_id);
									$remarks = "Add partner commission process failed";
									throw new Exception($remarks);
								}
							}
							Logger::trace("add Commission out" . $bkgModel->bkg_id);
						}
					}
					$modelbse->bse_event_status = BookingScheduleEvent::STATUS_PROCESSED;
					if (!$modelbse->save())
					{
						Logger::trace("failed to update Booking Schedule Event status" . $model->bkg_id);
						throw new Exception("Complete event process failed");
					}
					Logger::trace("update Booking Schedule Event status" . BookingScheduleEvent::STATUS_PROCESSED . "bookingid" . $model->bkg_id);

					VendorCoins::processCoinForBooking($model->bkg_id);
					if ($model->bkgBcb->bcb_vendor_id != '' && ($model->bkgBcb->getLowestBookingStatus() == 7 || $model->bkgBcb->getLowestBookingStatus() == 6) && $model->bkgBcb->bcb_pending_status == 0)
					{
						$bcount		 = count($model->bkgBcb->bookings);
						$first_city	 = Cities::getName($model->bkgBcb->bookings[0]->bkg_from_city_id);
						$pickup_date = date("d M Y h:i A", strtotime($model->bkgBcb->bookings[0]->bkg_pickup_date));
						$last_city	 = Cities::getName($model->bkgBcb->bookings[$bcount - 1]->bkg_to_city_id);
						$payLoadData = ['tripId' => $model->bkgBcb->bcb_id, 'EventCode' => Booking::CODE_COMPLETED];
						$success	 = AppTokens::model()->notifyVendor($model->bkgBcb->bcb_vendor_id, $payLoadData, "Trip Id: " . $model->bkgBcb->bcb_id . ", " . $first_city . "-" . $last_city . ", " . $pickup_date, $model->bkgBcb->bcb_id . " has been marked as completed.");

						Logger::trace("notify vendor sent" . $model->bkg_id);
					}
					if ($model->bkgUserInfo->bkg_user_id != '')
					{
						if ($model->bkgTrack->bkg_ride_complete == 0)
						{
							notificationWrapper::customerNotifyTripCompleted($model->bkg_id);
						}
//						$notificationId	 = substr(round(microtime(true) * 1000), -5);
//						$payLoadData	 = ['bookingId' => $model->bkg_booking_id, 'EventCode' => Booking::CODE_COMPLETED];
//						$success		 = AppTokens::model()->notifyConsumer($model->bkgUserInfo->bkg_user_id, $payLoadData, $notificationId, "(" . $model->bkg_booking_id . ") " . $model->bkgFromCity->cty_name . " to " . $model->bkgToCity->cty_name . " on " . $model->bkg_pickup_date, $model->bkg_booking_id . " has been marked as completed.");

						Logger::trace("notify consumer sent" . $model->bkg_id);
					}

					if ($model->bkgBcb->bcb_driver_id != "")
					{
						$userInfo		 = UserInfo::getInstance();
						$type			 = Booking::model()->userArr[$userInfo->userType];
						$message		 = "Booking " . $model->bkg_booking_id . " Mark Completed by $type";
						$image			 = null;
						$bkgID			 = $model->bkg_booking_id;
						$notificationId	 = substr(round(microtime(true) * 1000), -5);
						$payLoadData	 = ['EventCode' => Booking::CODE_TRIP_END_NOTIFICATION];
						AppTokens::model()->notifyDriver($model->bkgBcb->bcb_driver_id, $payLoadData, $notificationId, $message, $image, "Booking Mark Completed", $bkgID);

						Logger::trace("notify Driver sent" . $model->bkg_id);
					}
//					Users::processReferralBonous($model->bkg_id);
					Logger::trace("add process Referral Bonous" . $model->bkg_id);
					//DBUtil::commitTransaction($transaction);
					Booking::notifyBookingComplete($model->bkg_id);
				}
			}
			catch (Exception $ex)
			{
				//DBUtil::rollbackTransaction($transaction);

				$remarks					 = $ex->getMessage();
				$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_FAILED;
				$modelbse->bse_err_count	 += 1;
				$modelbse->bse_last_error	 = $remarks;
				$modelbse->save();
				if ($modelbse->bse_err_count > 5)
				{
					$model->setAccountingFlag($remarks);
				}
				Logger::exception($ex);
				Logger::trace("mark Complete Event failed bookingid: $model->bkg_id ({$ex->getMessage()})");
			}
			Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		}
	}

	public static function processRefundEvent($bkgId = null)
	{
		$records = self::getRefundList($bkgId);

		foreach ($records as $row)
		{
			$bkg	 = $row['bse_bkg_id'];
			$bseid	 = $row['bse_id'];

			$model		 = Booking::model()->findByPk($bkg);
			$modelbse	 = BookingScheduleEvent::model()->findByPk($bseid);

			//$transaction = null;
			try
			{

				$data			 = CJSON::decode($row['bse_addtional_data']);
				$refundAmount	 = $data['refundAmount'];

//				$ledger = Accounting::LI_WALLET;
//				if ($model->bkg_agent_id > 0 && ($model->bkg_agent_ref_code != null || $model->bkg_agent_ref_code != ''))
//				{
//					AccountTransactions::UpdateInactiveStatus($bkg);
//					$ledger = Accounting::LI_PARTNERWALLET;
//				}
				$cnrModel = CancelReasons::model()->findByPk($model->bkg_cancel_id);
				if ($model->bkgInvoice->bkg_credits_used > 0 && $cnrModel && $cnrModel->cnr_penalize_customer == 1)
				{
					$model->bkgInvoice->refundGozoCoins($model->bkgInvoice->bkg_credits_used, null, UserInfo::model());
				}

//				if ($model->bkg_agent_id > 0 && ($model->bkg_agent_ref_code == null || $model->bkg_agent_ref_code == ''))
//				{
//					$ledger = Accounting::LI_BOOKING;
//				}

				$checkRefund = AccountTransactions::checkRefund($bkg, $refundAmount, null);
				if (($model->bkgPref->bkg_is_confirm_cash == 1 && $model->bkgInvoice->bkg_advance_amount == 0 ) || $model->bkgInvoice->bkg_advance_amount == 0)
				{
					$modelbse->bse_event_status = BookingScheduleEvent::STATUS_PROCESSED;
					$modelbse->save();
					goto skipAll;
				}
				if ($refundAmount > 0 && ($checkRefund == '' || $checkRefund == null || $checkRefund == false))
				{
					// $transaction = DBUtil::beginTransaction();

					$success = $model->refund($refundAmount, "Refund on booking cancelation", UserInfo::model());
					if ($success)
					{
						$modelbse->bse_event_status = BookingScheduleEvent::STATUS_PROCESSED;
						if ($modelbse->save())
						{
							$cancelReasons = CancelReasons::model()->excludeCancellationCharge($model->bkg_cancel_id);
							if ($cancelReasons == true && $model->bkg_agent_id == NULL)
							{
								Booking::RefundFromWalletToSource($model);
							}
						}
					}
					else
					{
						throw new Exception("Refund on booking cancelation update failed");
					}
				}
				else
				{ //recheck status = 3;
					$modelbse->bse_event_status = 3;
					$modelbse->save();
				}
				// DBUtil::commitTransaction($transaction);
				skipAll:
				$emailObj = new emailWrapper();
				$emailObj->bookingCancellationMail($bkg);
			}
			catch (Exception $ex)
			{
				// DBUtil::rollbackTransaction($transaction);
				$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_FAILED;
				$modelbse->bse_err_count	 += 1;
				$modelbse->bse_last_error	 = CJSON::encode($modelbse->getErrors());
				$modelbse->save();
				if ($modelbse->bse_err_count > 5)
				{
					$model->setAccountingFlag("Refund on booking cancelation update failed Booking Id: {$modelbse->bse_bkg_id})");
				}
				Logger::exception($ex);
			}
		}
	}

	/**
	 * @return Array
	 */
	public static function getFailedMarkCompleteEventList()
	{
		$event	 = BookingScheduleEvent::MARK_COMPLETE_PROCESS;
		$params	 = array('event' => $event);
		$sql	 = "SELECT * FROM booking_schedule_event WHERE bse_event_id =:event AND bse_event_status = 2 AND bse_err_count = 6 ORDER BY bse_id DESC";
		$records = DBUtil::query($sql, DBUtil::MDB(), $params);
		return $records;
	}

	public static function reprocessMarkCompleteEvent()
	{
		$records = self::getFailedMarkCompleteEventList();

		foreach ($records as $row)
		{
			$transaction = null;
			try
			{
				$bkg		 = $row['bse_bkg_id'];
				$bseid		 = $row['bse_id'];
				$model		 = Booking::model()->findByPk($bkg);
				$modelbse	 = BookingScheduleEvent::model()->findByPk($bseid);
				$oldstatus	 = $model->undoActions($bkg);
				$model->refresh();

				if ($oldstatus == 5)
				{
					$success = $model->markComplete($bkg);

					if ($success)
					{
						$modelbse->bse_event_status = BookingScheduleEvent::STATUS_REPROCESSED;
						$modelbse->save();
					}
					else
					{
						throw new Exception("Reprocess mark complete failed success: {$success})");
					}
				}
				else
				{
					throw new Exception("Reprocess mark complete failed old status: {$oldstatus})");
				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				$modelbse->bse_event_status = BookingScheduleEvent::STATUS_REPROCESSED_FAILED;
				$modelbse->save();
				if ($modelbse->bse_err_count > 5)
				{
					$model->setAccountingFlag("Reprocess mark complete event failed Booking Id: {$modelbse->bse_bkg_id})");
				}
				Logger::exception($ex);
			}
		}
	}

	public static function driverDetailsToCustomerEvent($bkgId = null)
	{
		$records = self::getDriverDetailsList($bkgId);
		foreach ($records as $row)
		{
			$transaction = null;
			try
			{
				$bkgId	 = $row['bse_bkg_id'];
				$bseId	 = $row['bse_id'];

				Logger::writeToConsole("bkgId: {$bkgId}, bse_id: " . $row['bse_id']);

				// Booking
				$model = Booking::model()->findByPk($bkgId);

				// Booking Sub
				$bookingSub			 = new BookingSub();
				$countUpdate		 = $bookingSub->checkCabUpdateCount($model->bkg_id);
				$partnerCountUpdate	 = $bookingSub->checkPartnerCabUpdateCount($model->bkg_id);

				Logger::trace("Booking cab update count: " . $countUpdate . "booking id: " . $model->bkg_id . "-----");

				$spiceId	 = Config::get('spicejet.partner.id');
				$sugerboxId	 = Config::get('sugerbox.partner.id');
				if ($model->bkg_agent_id == 12074)
				{
					$typeAction = AgentApiTracking::TYPE_CAB_DRIVER_UPDATE;
				}
				if ($model->bkg_agent_id == 18190 && $model->bkgBcb->bcb_driver_id != '' && $countUpdate < 1)
				{
					$typeAction = AgentApiTracking::TYPE_CAB_DRIVER_UPDATE;
				}
				if ($model->bkg_agent_id == 18190 && ($countUpdate >= 1))
				{
					$typeAction = AgentApiTracking::TYPE_CAB_DRIVER_REASSIGN;
				}
				if ($model->bkg_agent_id != 18190)
				{
					$typeAction = PartnerApiTracking::VENDOR_DRIVER_ALLOCATION;
				}

				if (($model->bkg_agent_id == $spiceId || $model->bkg_agent_id == $sugerboxId) && $partnerCountUpdate < 1)
				{
					$typeAction = PartnerApiTracking::VENDOR_DRIVER_ALLOCATION;
				}

				if (($model->bkg_agent_id == $spiceId || $model->bkg_agent_id == $sugerboxId) && ($partnerCountUpdate >= 1))
				{
					$typeAction = AgentApiTracking::TYPE_CAB_DRIVER_REASSIGN;
				}

				Logger::writeToConsole("bkg_agent_id: {$model->bkg_agent_id}");

				// BookingScheduleEvent
				$modelbse = BookingScheduleEvent::model()->findByPk($bseId);

				$transaction = DBUtil::beginTransaction();

				if ($model->bkg_status == 5)
				{
					if (Agents::isApiKeyAvailable($model->bkg_agent_id))
					{
						Logger::writeToConsole("isApiKeyAvailable");
						$response = AgentMessages::model()->pushApiCall($model, $typeAction);

						Logger::writeToConsole("status: " . $response->status);

						if ($response->status == 1)
						{
							$modelbse->bse_event_status = BookingScheduleEvent::STATUS_PROCESSED;
							$modelbse->save();
						}
						else
						{
							$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_FAILED;
							$modelbse->bse_err_count	 += 1;
							$modelbse->bse_last_error	 = CJSON::encode($response);
							$modelbse->save();
						}
					}
					else
					{
						$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_PROCESSED;
						$modelbse->bse_last_error	 = "Booking partner no API available for push";
						$modelbse->save();
					}
				}
				elseif (in_array($model->bkg_status, [9, 10]))
				{
					$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_PROCESSED;
					$modelbse->bse_err_count	 += 1;
					$modelbse->bse_last_error	 = "Booking is already cancelled";
					$modelbse->save();
				}
				elseif (in_array($model->bkg_status, [2, 3]))
				{
					$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_PROCESSED;
					$modelbse->bse_err_count	 += 1;
					$modelbse->bse_last_error	 = "Booking is in new/assigned status";
					$modelbse->save();
				}
				elseif (in_array($model->bkg_status, [6, 7]))
				{
					$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_PROCESSED;
					$modelbse->bse_err_count	 += 1;
					$modelbse->bse_last_error	 = "Booking is already completed";
					$modelbse->save();
				}
				elseif (in_array($model->bkg_status, [1, 15]))
				{
					$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_PROCESSED;
					$modelbse->bse_err_count	 += 1;
					$modelbse->bse_last_error	 = "Booking is in unverified/quoted status";
					$modelbse->save();
				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_FAILED;
				$modelbse->bse_err_count	 += 1;
				$modelbse->bse_last_error	 = CJSON::encode($modelbse->getErrors());
				$modelbse->save();

				Logger::writeToConsole("Error: " . $ex->getMessage());
				Logger::exception($ex);
			}
		}
	}

	/**
	 * @return Array
	 */
	public static function getDriverDetailsList($bkgID = null)
	{
		$event	 = BookingScheduleEvent::SEND_DRIVER_DETAILS;
		$params	 = array('event' => $event);
		$where	 = " AND 1=1 ";
		if ($bkgID != '')
		{
			$where = " AND bse_bkg_id = {$bkgID}";
		}

		$sql = "SELECT bse_id, bse_bkg_id FROM booking_schedule_event 
				INNER JOIN booking bkg ON bkg.bkg_id = booking_schedule_event.bse_bkg_id 
				WHERE bse_event_id =:event AND bse_event_status IN (0,2) AND bse_err_count <= 1 
				AND (bse_schedule_time <= DATE_ADD(NOW(), INTERVAL 15 MINUTE) OR bse_schedule_time IS NULL) 
				$where 
				ORDER BY bse_schedule_time ASC, bse_id ASC";

		$records = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $records;
	}

	/**
	 * @return Array
	 */
	public static function getPendingPostVendorAssignments()
	{
		$event	 = BookingScheduleEvent::POST_VENDOR_ASSIGNMENT;
		$params	 = array('event' => $event);
		$where	 = " AND 1=1 ";

		$sql = "SELECT bse_id, bse_addtional_data FROM booking_schedule_event 
				WHERE bse_event_id =:event AND bse_event_status IN (0,2) AND bse_err_count <= 1 
				AND (bse_schedule_time <= DATE_ADD(NOW(), INTERVAL 15 MINUTE) OR bse_schedule_time IS NULL) 
				$where 
				ORDER BY bse_schedule_time ASC, bse_id ASC";

		$records = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $records;
	}

	/**
	 * @return Array
	 */
	public static function getTravellerDetailsList($bkgID = null)
	{
		$event	 = BookingScheduleEvent::SEND_TRAVELLER_DETAILS;
		$params	 = array('event' => $event);
		$where	 = " AND 1=1 ";
		if ($bkgID != '')
		{
			$where = " AND bse_bkg_id = {$bkgID}";
		}
		$sql	 = "SELECT * FROM booking_schedule_event 
					INNER JOIN booking bkg ON bkg.bkg_id = booking_schedule_event.bse_bkg_id 
					WHERE bse_event_id =:event AND bse_event_status IN (0,2) AND bse_err_count <= 1 
					AND bkg.bkg_status IN (2,3,5) AND bse_schedule_time <= DATE_ADD(NOW(), INTERVAL 30 MINUTE) 
					$where 
					ORDER BY bse_schedule_time ASC, bse_id ASC";
		$records = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $records;
	}

	public static function addPushDriverDetailsEvent($bkgId, $pickupDate, $agentScheduleTime)
	{
		$remarks = "Push Driver Details";
		if ($agentScheduleTime > 0)
		{
			$scheduleTime	 = floor(strtotime($pickupDate) - $agentScheduleTime * 60);
			$date			 = date('Y-m-d H:i:s', $scheduleTime);
		}
		else
		{
			$date = date('Y-m-d H:i:s');
		}
		BookingScheduleEvent::add($bkgId, BookingScheduleEvent::SEND_DRIVER_DETAILS, $remarks, null, $date);
	}

	public static function addPushTravellerDetailsEvent($bkgModel, $pickupDate, $custScheduleTime)
	{
		$remarks = "Push Traveller Details";

		$pickupTimeDiffMinutes = Filter::getTimeDiff($bkgModel->bkg_pickup_date, Filter::getDBDateTime());
		if ($pickupTimeDiffMinutes > $custScheduleTime)
		{
			$scheduleTime	 = floor(strtotime($pickupDate) - $custScheduleTime * 60);
			$date			 = date('Y-m-d H:i:s', $scheduleTime);
		}
		else
		{
			$date = date('Y-m-d H:i:s');
		}
		BookingScheduleEvent::add($bkgModel->bkg_id, BookingScheduleEvent::SEND_TRAVELLER_DETAILS, $remarks, null, $date);
	}

	/**
	 * This function is used for add a queue to post event schedule service 
	 * @param integer $bkgId
	 * @param integer $event
	 * @return true
	 */
	public static function addPostEvent($bkgId, $event, $date)
	{
		$remarks	 = "";
		$syncData	 = json_encode(['SyncEvent' => $event]);
		BookingScheduleEvent::add($bkgId, BookingScheduleEvent::TRACK_DRIVER_SYNC, $remarks, $syncData, $date);
	}

	public static function getTrackEvents()
	{
		$event	 = BookingScheduleEvent::TRACK_DRIVER_SYNC;
		$params	 = array('event' => $event);
		$sql	 = "SELECT * FROM booking_schedule_event
					WHERE bse_event_id =:event AND bse_event_status = 0
					ORDER BY bse_id DESC";
		$records = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $records;
	}

	/**
	 * This function is used to execute the sync post events 
	 */
	public static function postEvents()
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$records = self::getTrackEvents();
		foreach ($records as $row)
		{
			Logger::Error('booking post event:' . $row['bse_bkg_id']);
			try
			{
				Logger::info("booking schedule event try enter");
				$bkgId			 = $row['bse_bkg_id'];
				$bseId			 = $row['bse_id'];
				$scheduleTime	 = floor(strtotime($row['bse_schedule_time']));
				$trackEventObj	 = json_decode($row['bse_addtional_data']);
				$trackEvent		 = $trackEventObj->SyncEvent;

				$modelbse = BookingScheduleEvent::model()->findByPk($bseId);

				switch ($trackEventObj->SyncEvent)
				{
					case BookingTrack::TRIP_START:
						$success = BookingTrackLog::postEventStart($bkgId);
						break;

					case BookingTrack::TRIP_STOP:
						$success = BookingTrackLog::postEventStop($bkgId);
						break;

					case BookingTrack::DRIVER_ARRIVED:
						$success = BookingTrackLog::postEventArrive($bkgId);
						break;
				}
				if ($success)
				{
					$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_PROCESSED;
					$res						 = $modelbse->save();
					$msg						 = "Check booking schedule event bkg:: " . $bkgId . "bse::" . $bseId . "result::" . $res;
					Logger::info("booking schedule event" . $msg);
				}
			}
			catch (Exception $ex)
			{
				Logger::trace("Update to schedule fail  " . CJSON::encode($ex) . " bseId:: " . $bseId);
				$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_FAILED;
				$modelbse->bse_err_count	 += 1;
				$modelbse->bse_last_error	 = CJSON::encode($ex);
				$modelbse->save();
				Logger::exception($ex);
			}
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	/**
	 * @return Array
	 */
	public static function getPendingList($bkgID = null)
	{
		$event	 = BookingScheduleEvent::PARTNER_PENDING_ADVANCE;
		$where	 = " AND 1=1 ";
		if ($bkgID != '' || $bkgID != NULL)
		{
			$where = " AND bse_bkg_id = {$bkgID}";
		}
		$params	 = array('event' => $event);
		$sql	 = "SELECT * FROM booking_schedule_event WHERE bse_event_id =:event AND bse_event_status IN(0,2) AND bse_err_count <= 5 $where ORDER BY bse_id  DESC limit 100";
		$records = DBUtil::query($sql, DBUtil::MDB(), $params);
		return $records;
	}

	/**
	 * @return Array
	 */
	public static function getSuccessList()
	{
		$event	 = BookingScheduleEvent::PARTNER_PENDING_ADVANCE;
		$params	 = array('event' => $event);
		$sql	 = "SELECT * FROM booking_schedule_event WHERE bse_event_id =:event AND bse_event_status = 1 AND bse_update_date >= DATE_SUB(NOW(),INTERVAL 10 MINUTE)";
		$records = DBUtil::query($sql, DBUtil::MDB(), $params);
		return $records;
	}

	public static function pendingAdvanceProcess($bkgID = null)
	{
		$records = self::getPendingList($bkgID);
		foreach ($records as $row)
		{
			$transaction = null;
			try
			{
				Logger::writeToConsole("bkg_id: {$row['bse_bkg_id']}");
				$advAmountObj	 = json_decode($row['bse_addtional_data']);
				$amount			 = round($advAmountObj->AdvanceAmount);
				$bkg			 = $row['bse_bkg_id'];
				$ppaid			 = $row['bse_id'];
				$modelbse		 = BookingScheduleEvent::model()->findByPk($ppaid);
				$model			 = Booking::model()->findByPk($bkg);
				if (!$model)
				{
					throw new Exception("Error in getting booking details for booking id: {$row['bse_bkg_id']}");
				}
				$transaction = DBUtil::beginTransaction();
				if ($amount > 0)
				{
					$success = $model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet used");
					Logger::writeToConsole("updateAdvance: {$success}");
					if ($success)
					{
						$modelbse->bse_event_status	 = 1;
						$modelbse->bse_update_date	 = new CDbExpression('NOW()');
						$modelbse->save();
					}
					else
					{
						$modelbse->bse_event_status	 = 2;
						$modelbse->bse_err_count	 += 1;
						$modelbse->bse_last_error	 = CJSON::encode($model->getErrors());
						$modelbse->save();
						if ($modelbse->bse_err_count > 5)
						{
							$model->setAccountingFlag("Partner pending advance update failed,Error count : {$modelbse->bse_err_count})");
						}
					}
					$model->refresh();
					if ($model->bkg_status == 9 && $amount > 0 && $success)
					{
						$dataArray	 = array('refundAmount' => $amount);
						$refundData	 = CJSON::encode($dataArray);
						BookingScheduleEvent::add($model->bkg_id, BookingScheduleEvent::REFUND_PROCESS, "Refund on booking cancelation", $refundData);
					}
					DBUtil::commitTransaction($transaction);
					Logger::writeToConsole("commitTransaction Done");
//                    if ($success)
//                    {
//                        if ($model->bkg_status == 9)
//                        {
//                            $cancelCharges = $model->calculateRefund();
//                            $refundAmount  = $cancelCharges->refund;
//                            if ($refundAmount > 0)
//                            {
//                                $dataArray  = array('refundAmount' => $refundAmount);
//                                $refundData = CJSON::encode($dataArray);
//                                BookingScheduleEvent::add($model->bkg_id, BookingScheduleEvent::REFUND_PROCESS, "Refund on booking cancelation", $refundData);
//                            }
//
//                            //	$cancelCharges_new = CancellationPolicy::initiateRequest($model);
//                            if ($cancelCharges->charges > 0)
//                            {
//                                $userInfo            = UserInfo::getInstance();
//                                $chargePartnerCredit = ($cancelCharges->refund < 0) ? abs($cancelCharges->refund) : 0;
//                                if ($chargePartnerCredit > 0)
//                                {
//                                    $model->updateAdvance1($chargePartnerCredit, $userInfo->userType, PaymentType::TYPE_AGENT_CORP_CREDIT);
//                                    $model->chargePartnerWalletOnCancellation($chargePartnerCredit, $model->bkg_pickup_date, UserInfo::model());
//                                }
//                                $model->bkgInvoice->processCancelCharge($cancelCharges->charges);
//                            }
//                        }
//                    }
				}
				if (($amount == 0 || $amount < 0) && $model->bkgPref->bkg_is_fbg_type == 1)
				{
					$modelbse->bse_event_status	 = 2;
					$modelbse->bse_update_date	 = new CDbExpression('NOW()');
					$modelbse->bse_err_count	 = 6;
					$modelbse->save();
					DBUtil::commitTransaction($transaction);
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);

				$modelbse->bse_event_status	 = 2;
				$modelbse->bse_err_count	 += 1;

				if (!$model)
				{
					goto skipErrLog;
				}

				$modelbse->bse_last_error = CJSON::encode($model->getErrors());
				if ($modelbse->bse_err_count > 5)
				{
					$model->setAccountingFlag("Partner pending advance update failed,Error count : {$modelbse->bse_err_count})");
				}

				skipErrLog:
				$modelbse->save();
				Logger::exception($ex);
			}
		}
		return true;
	}

	public static function updateBalancebyBookingid()
	{
		$records = self::getSuccessList();
		foreach ($records as $row)
		{
			$transaction = null;
			try
			{
				$bkg			 = $row['bse_bkg_id'];
				$advAmountObj	 = json_decode($row['bse_addtional_data']);
				$amount			 = $advAmountObj->AdvanceAmount;
				$model			 = Booking::model()->findByPk($bkg);
				$balance		 = AccountTransDetails::getBalancebyBookingid($bkg);
				$bkgAdvance		 = $model->bkgInvoice->bkg_advance_amount;
				$accountAdvance	 = (int) $balance['advance'];
				$accountRefund	 = (int) $balance['refund'];
				$transaction	 = DBUtil::beginTransaction();
				if ($bkgAdvance != $accountAdvance && ($balance['advance'] != '' || $balance['advance'] != null))
				{

					$model->bkgInvoice->bkg_advance_amount	 = $accountAdvance;
					$model->bkgInvoice->bkg_refund_amount	 = $accountRefund;
					$model->bkgInvoice->calculateTotal();
					if (!$model->bkgInvoice->save())
					{
						throw new Exception("Failed to update bkg_advance_amount, advance not matched with account: " . json_encode($model->bkgInvoice->getErrors()));
					}
				}
				if ($bkgAdvance == 0 && ($balance['advance'] == null || $balance['advance'] == ''))
				{
					$success = $model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet used");
					if (!$success)
					{
						throw new Exception("Failed to update advance : " . $success);
					}
				}
				if ($bkgAdvance > 0 && ($balance['advance'] == null || $balance['advance'] == ''))
				{
					$model->bkgInvoice->bkg_advance_amount = 0;
					$model->bkgInvoice->calculateTotal();
					if ($model->bkgInvoice->save())
					{
						$success = $model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet used");
						if (!$success)
						{
							throw new Exception("Failed to update advance : " . $success);
						}
					}
				}
				/*
				 * mmt DoubleBack
				 */
//				if ($model->bkgInvoice->bkg_advance_amount > 0 && $model->bkg_reconfirm_flag == 1)
//				{
//					if ($model->bkg_agent_id == 450 || $model->bkg_agent_id == 18190)
//					{
//						$isMmtC1Route = Route::getMmtC1RouteByCity($model->bkg_from_city_id, $model->bkg_to_city_id);
//						if ($isMmtC1Route)
//						{
//							$model->bkgTrail->updateDBO($model->bkg_pickup_date, $model->bkg_agent_id);
//						}
//					}
//				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				//$model->setAccountingFlag("Booking advance not matched with account: Booking amount <=> Account amount " . $bkgAdvance . " <=> " . $accountAdvance);
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function is used for getiing pending event list by booking id
	 * @param integer $bkgId
	 * @return array
	 */
	public static function getPendingEventListByBkgId($bkgId)
	{
		$params	 = array('bkgId' => $bkgId);
		$sql	 = "SELECT * FROM booking_schedule_event
						WHERE bse_bkg_id =:bkgId AND bse_event_status IN (0,2)  
						 AND bse_err_count <= 5 AND bse_event_id IN(101,106,104)
						ORDER BY bse_id ASC";
		$records = DBUtil::query($sql, DBUtil::MDB(), $params);
		return $records;
	}

	/**
	 * This function will work before cancel booking in every time.
	 * @param integer $bkgId
	 * @return array
	 */
	public static function preCanBooking($bkgId)
	{
		$records = self::getPendingEventListByBkgId($bkgId);
		foreach ($records as $row)
		{
			switch ($row['bse_event_id'])
			{

				case BookingScheduleEvent::CONFIRM_MESSAGE:
					$check_CONFIRM_MESSAGE = Filter::checkProcess("booking getScheduleBookingForConfirmMessages");
					if (!$check_CONFIRM_MESSAGE)
					{
						return;
					}
					$success = Booking::model()->confirmMessages($row['bse_bkg_id']);
					break;

				case BookingScheduleEvent::SEND_DRIVER_DETAILS:
					$check_SEND_DRIVER_DETAILS = Filter::checkProcess("booking ProcessDriverDetailsToCustomerEvent");
					if (!$check_SEND_DRIVER_DETAILS)
					{
						return;
					}
					$success = BookingScheduleEvent::driverDetailsToCustomerEvent($row['bse_bkg_id']);
					break;

				case BookingScheduleEvent::PARTNER_PENDING_ADVANCE:
					$check_PARTNER_PENDING_ADVANCE = Filter::checkProcess("booking ProcessPartnerPendingAdvance");
					if (!$check_PARTNER_PENDING_ADVANCE)
					{
						return;
					}
					$success = BookingScheduleEvent::pendingAdvanceProcess($row['bse_bkg_id']);
					break;
			}
		}
		return $success;
	}

	/**
	 * This function is used to send notification to vendor based on selected Zone
	 * @param type $tripId
	 * @param type $eventId
	 * @param type $zoneId
	 * @param type $bookingSchduledEvent
	 * @param type $time
	 */
	public static function create($tripId, $eventId = Booking::CODE_VENDOR_GOZONOW_BOOKING_REQUEST, $zoneId, $bookingSchduledEvent = true, $time = NULL)
	{
		$data = Location::getVendorDriverByZone($zoneId);
		if (!$bookingSchduledEvent)
		{
			BookingCab::gnowNotify($tripId, explode(",", $data['vendorIds']));
		}
		else
		{
			switch ($eventId)
			{
				case Booking::CODE_VENDOR_GOZONOW_BOOKING_REQUEST;
					$success = self::scheduledMessageForBookingRequest($tripId, $data['vendorIds'], $time);
					break;

				case Booking::CODE_VENDOR_GOZONOW_BOOKING_ALLOCATED;
					break;
			}
		}
	}

	public static function scheduledMessageForBookingRequest($tripId, $entityIds, $time)
	{
		try
		{
			/** @var BookingCab $bcbmodel */
			$bcbmodel					 = BookingCab::model()->findByPk($tripId);
			$bkgId						 = $bcbmodel->bookings[0]->bkg_id;
			$model						 = new BookingScheduleEvent();
			$model->bse_bkg_id			 = $bkgId;
			$model->bse_event_id		 = BookingScheduleEvent::SEND_NOTIFICATION_DATA;
			$model->bse_event_status	 = 0;
			$model->bse_addtional_data	 = json_encode(array("vendorIds" => $entityIds));
			$model->bse_schedule_time	 = DBUtil::getCurrentTime();
			$model->bse_remarks			 = "Cab Required Urgently";
			if ($time != NULL)
			{
				$model->bse_schedule_time = $time;
			}
			if (!$model->save())
			{
				throw new Exception($eventId . "failed to initiate : " . json_encode($model->getErrors()));
			}
		}
		catch (Exception $e)
		{
			Logger::exception($e);
		}
	}

	/**
	 * This function is used for getting  event list by  event id
	 * @param integer eventId
	 * @return query Objects
	 */
	public static function getEventList($eventId)
	{
		$params	 = array('bse_event_id' => $eventId);
		$sql	 = "SELECT bse_id,bse_bkg_id,bse_addtional_data,bse_event_id
                    FROM   booking_schedule_event
                    WHERE  bse_schedule_time <= NOW() 
                    AND bse_event_status IN (0,2) AND bse_err_count<=2 AND bse_event_id =:bse_event_id ORDER BY bse_schedule_time";
		$records = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $records;
	}

	/**
	 * This function is used for update smt Score in booking_vendor_request
	 */
	public static function updateSmtScore($bkgId = null)
	{
		$event	 = BookingScheduleEvent::SMT_SCORE_PROCESS;
		$where	 = " AND 1=1 ";
		if ($bkgId != '' || $bkgId != NULL)
		{
			$where = " AND bse_bkg_id = {$bkgID}";
		}
		$params	 = array('event' => $event);
		$sql	 = "SELECT * FROM booking_schedule_event WHERE bse_event_id =:event AND bse_event_status IN(0,2) AND bse_err_count <= 5 $where ORDER BY bse_id  DESC limit 100";
		$records = DBUtil::query($sql, DBUtil::MDB(), $params);
		#return $records;
		foreach ($records as $record)
		{
			try
			{
				$transaction = DBUtil::beginTransaction();

				$data = json_decode($record['bse_addtional_data'], true);

				$vendorId			 = $data["vendorId"];
				$tripId				 = $data["bcb_id"];
				$tripAmount			 = $data["bcb_vendor_amount"];
				$maxAllowedAmount	 = $data["maxAllowedAmount"];
				$bidAmount			 = ($data["bidAmount"] > 0 ? $data["bidAmount"] : 0);
				#$tripId, $vendorId, $bidAmount, $tripAmount, $maxAllowedAmount
				if (BookingVendorRequest::updateSMTScore($tripId, $vendorId, $bidAmount, $tripAmount, $maxAllowedAmount))
				{
					$bseid = $record['bse_id'];

					$modelbse					 = BookingScheduleEvent::model()->findByPk($bseid);
					$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_PROCESSED;
					if (!$modelbse->save())
					{
						Logger::trace("failed to update Booking Schedule Event status" . $model->bkg_id);
						throw new Exception("Complete event process failed");
					}
					DBUtil::commitTransaction($transaction);
				}
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($e);
			}
		}
	}

	/**
	 *  This function is used for getting  event to process
	 * @return type
	 */
	public static function getEventsToProcess()
	{
		$sql = "SELECT * FROM booking_schedule_event 
					WHERE bse_event_status IN (0,2) AND bse_err_count <= 5 
						AND (bse_schedule_time IS NULL OR bse_schedule_time <= NOW()) 
						AND bse_event_id IN (108,109,110,111)
					ORDER BY bse_schedule_time ASC";

		$results = DBUtil::query($sql, DBUtil::SDB());
		return $results;
	}

	/**
	 * This function is used for process Scheduled Events
	 */
	public static function processScheduledEvents()
	{
		$results = BookingScheduleEvent::getEventsToProcess();

		foreach ($results as $row)
		{
			$eventId = $row['bse_event_id'];
			switch ($eventId)
			{
				case BookingScheduleEvent::SEND_BOOKING_INVOICE:
					BookingScheduleEvent::processSendInvoice($row);
					break;
				case BookingScheduleEvent::DRIVER_APP_PENALTY:
					BookingScheduleEvent::processDriverAppUsagePenalty($row);
					break;
				case BookingScheduleEvent::GENERATE_QR_CODE:
					BookingScheduleEvent::generateUserQrCode($row);
					break;
				case BookingScheduleEvent::BOOKING_VND_COMPENSATION:
					BookingScheduleEvent::vendorCompensationByBooking($row);
					break;
			}
		}
	}

	/**
	 * 
	 * @param type $row
	 * @throws Exception
	 */
	public static function processSendInvoice($row)
	{
		$bseId	 = $row['bse_id'];
		$bkgId	 = $row['bse_bkg_id'];
		$eventId = $row['bse_event_id'];

		try
		{
			// Send Email
			$emailCom1	 = new emailWrapper();
			$return		 = $emailCom1->sendInvoice($bkgId, 0);
			if (!$return)
			{
				throw new Exception("Send invoice process failed");
			}

			// Update Booking Event Stats
			$data					 = [];
			$data['invoice_sent']	 = ($return == true ? 1 : 2);
			BookingEventStats::updateStats($bkgId, $eventId, $data);

			// Update BookingScheduleEvent
			BookingScheduleEvent::updateEventStatus($bseId, $return);
		}
		catch (Exception $ex)
		{
			$remarks = $ex->getMessage();

			// Update BookingScheduleEvent
			BookingScheduleEvent::updateEventStatus($bseId, false, $remarks);

			Logger::exception($ex);
		}
	}

	/**
	 * 
	 * @param $bseId
	 * @param $success
	 * @param $remarks
	 * @return boolean
	 */
	public static function updateEventStatus($bseId, $success, $remarks = '')
	{
		$status = ($success == true ? BookingScheduleEvent::STATUS_PROCESSED : BookingScheduleEvent::STATUS_FAILED);

		$updRemarks = '';
		if (trim($remarks) != '')
		{
			$updRemarks = " , `bse_last_error` = '{$remarks}' ";
		}

		$sql = "UPDATE `booking_schedule_event` SET `bse_event_status`= {$status} {$updRemarks} WHERE `bse_id`= {$bseId}";
		if (!$success)
		{
			$sql = "UPDATE `booking_schedule_event` 
						SET bse_err_count = (bse_err_count + 1), `bse_event_status`= {$status} 
						{$updRemarks} 
						WHERE `bse_id` = {$bseId}";
		}

		return DBUtil::execute($sql);
	}

	/**
	 * @param type $row
	 * @throws Exception
	 */
	public static function processDriverAppUsagePenalty($row)
	{
		$success = true;
		$bseId	 = $row['bse_id'];
		$bkgId	 = $row['bse_bkg_id'];
		$eventId = $row['bse_event_id'];

		Logger::writeToConsole("bse_id == " . $bseId);

		try
		{
			$model = Booking::model()->findByPk($bkgId);

			$isArrivedPickup	 = $model->bkgTrack->bkg_arrived_for_pickup;
			$isRideStart		 = $model->bkgTrack->bkg_ride_start;
			$isRideCompleted	 = $model->bkgTrack->bkg_ride_complete;
			$isDrvAppRequired	 = $model->bkgPref->bkg_driver_app_required;
			$transferzId		 = Config::get('transferz.partner.id');
			if ($transferzId == $model->bkg_agent_id)
			{
				goto skip;
			}

			$cabArrivedByAdmin	 = 0;
			$rideStartedByAdmin	 = 0;
			$adminEvents		 = BookingLog::checkEventsDoneByAdmin(array(215, 93, 216), $bkgId);
			if ($adminEvents)
			{
				$cabArrivedByAdmin	 = $adminEvents['cabArrivedByAdmin'];
				$rideStartedByAdmin	 = $adminEvents['rideStartedByAdmin'];
				$rideEndByAdmin		 = $adminEvents['rideEndByAdmin'];
			}

			Logger::writeToConsole("cabArrivedByAdmin == " . $cabArrivedByAdmin);
			Logger::writeToConsole("rideStartedByAdmin == " . $rideStartedByAdmin);

			if (!$isDrvAppRequired)
			{
				goto applyDisableAppPenalty;
			}
			$penaltyType	 = PenaltyRules::PTYPE_NOT_USING_DRIVER_APP;
			$arrRules		 = PenaltyRules::getRuleByPenaltyType($penaltyType);
			$penaltyAmount	 = PenaltyRules::calculatePenaltyCharge($penaltyType, $arrRules, $model->bkgBcb->bcb_vendor_amount, null, null, null, $model->bkgInvoice->bkg_total_amount);
			$pAmount		 = round($penaltyAmount);

			Logger::writeToConsole("penaltyType == " . $penaltyType);
			Logger::writeToConsole("arrRules == " . $arrRules);
			Logger::writeToConsole("penaltyAmount == " . $penaltyAmount);
			Logger::writeToConsole("pAmount == " . $pAmount);

			$pMessage	 = " ";
			$weightage	 = 0;

			if ($cabArrivedByAdmin == 1)
			{
				$isArrivedPickup = 0;
			}

			if ($rideStartedByAdmin == 1)
			{
				$isRideStart = 0;
			}

			if($rideEndByAdmin == 1)
			{
				$isRideCompleted = 0;
			}

			$msg = [];
			if ($isArrivedPickup == 0)
			{
				$msg[]		 = "arriving";
				$weightage	 = 0.2;
				$penaltyType = PenaltyRules::PTYPE_NOT_ARRIVING_DRIVER_APP;
			}
			if ($isRideStart == 0)
			{
				$msg[]		 = "starting";
				$weightage	 += 0.4;
				$penaltyType = PenaltyRules::PTYPE_RIDE_NOT_STARTED_BY_DRIVER;
			}

			if ($isRideCompleted == 0)
			{
				$msg[]		 = "completing";
				$weightage	 += 0.4;
				$penaltyType = PenaltyRules::PTYPE_RIDE_NOT_COMPLETED_BY_DRIVER;
			}

			if ($weightage == 1)
			{
				$pMessage	 = "for not using the driver app";
				$penaltyType = PenaltyRules::PTYPE_NOT_USING_DRIVER_APP;
			}
			else
			{
				$msg1		 = implode(" and ", $msg);
				$pMessage	 = "for not {$msg1} the trip using driver app";
			}

			$pAmount = round($pAmount * $weightage);

			Logger::writeToConsole("pAmount After == " . $pAmount);

			if ($pAmount > 0)
			{
				$vnd_id			 = $model->bkgBcb->bcb_vendor_id;
				$bkgID			 = $model->bkg_id;
				$bkg_booking_id	 = $model->bkg_booking_id;
				//$penaltyType	 = PenaltyRules::PTYPE_RIDE_NOT_STARTED_BY_DRIVER;
				$remarks		 = "₹$pAmount Penalty applied  $pMessage ($bkg_booking_id)";
				$result			 = AccountTransactions::checkAppliedPenaltyByType($bkgID, $penaltyType);
				if ($result)
				{
					$addVendorPenalty = AccountTransactions::model()->addVendorPenalty($bkgID, $vnd_id, $pAmount, $remarks, '', $penaltyType);

					if ($addVendorPenalty)
					{
						$success = true;
					}
					else
					{
						throw new Exception("Driver App Usage Penalty Process Failed");
					}
				}
				else
				{
					throw new Exception("Driver App Usage Penalty Already Applied!");
				}
			}
			goto skip;

			applyDisableAppPenalty:
			$penaltyType = PenaltyRules::PTYPE_DRIVER_APP_DISABLE;
			$arrRules	 = PenaltyRules::getValueByPenaltyType($penaltyType);
			$pAmount	 = $arrRules['plt_value'];
			$eventId	 = BookingLog::DRIVER_APP_USAGE;
			$checkUser	 = BookingLog::getDataByEventId($eventId, $model->bkg_id);
			if ($checkUser == "")
			{
				goto skip;
			}
			if (($isArrivedPickup == 0 || $cabArrivedByAdmin == 1) && ($isRideStart == 0 || $rideStartedByAdmin == 1) && $isRideCompleted == 0)
			{
				$pMessage	 = "for driver app disable from system";
				$vnd_id		 = $model->bkgBcb->bcb_vendor_id;
				$remarks	 = "₹$pAmount Penalty applied  $pMessage ($model->bkg_booking_id)";
				$result		 = AccountTransactions::checkAppliedPenaltyByType($model->bkg_id, $penaltyType);
				if ($result)
				{
					$addVendorPenalty = AccountTransactions::model()->addVendorPenalty($model->bkg_id, $vnd_id, $pAmount, $remarks, '', $penaltyType);
					if ($addVendorPenalty)
					{
						$success = true;
					}
					else
					{
						throw new Exception("Driver App Usage Penalty Process Failed");
					}
				}
				else
				{
					throw new Exception("Driver App Usage Penalty Already Applied!");
				}
			}

			skip:
			// Update Booking Event Stats
			$data								 = [];
			$data['driver_app_usage_penalty']	 = ($success == true ? 1 : 2);

			Logger::writeToConsole("driver_app_usage_penalty == " . $data['driver_app_usage_penalty']);
			BookingEventStats::updateStats($bkgId, $eventId, $data);

			// Update BookingScheduleEvent
			BookingScheduleEvent::updateEventStatus($bseId, $success);
		}
		catch (Exception $ex)
		{
			$remarks = $ex->getMessage();

			// Update BookingScheduleEvent
			BookingScheduleEvent::updateEventStatus($bseId, false, $remarks);

			Logger::exception($ex);
		}
	}

	public static function generateUserQrCode($row)
	{
		$remarks = '';
		$success = true;

		$bseId	 = $row['bse_id'];
		$bkgId	 = $row['bse_bkg_id'];

		try
		{
			$model = Booking::model()->findByPk($bkgId);

			if (!$model)
			{
				throw new Exception('Invalid booking for generating QR code, booking id: ' . $bkgId);
			}

			$userId = $model->bkgUserInfo->bkg_user_id;
			if (!$userId)
			{
				throw new Exception('Invalid user id for generating QR code, user id: ' . $userId);
			}

			$userModel = Users::model()->findByPk($userId);
			if (!$userModel)
			{
				throw new Exception('Invalid user for generating QR code, user id: ' . $userId);
			}

			if ($userModel->usr_qr_code_path == '' || $userModel->usr_qr_code_path == NULL)
			{
				$returnSet	 = QrCode::processData($userId);
				$success	 = $returnSet->getStatus();
			}
			else
			{
				$success = true;
				$remarks = "QR already exists for the user.";
			}

			BookingScheduleEvent::updateEventStatus($bseId, $success, $remarks);
		}
		catch (Exception $ex)
		{
			$remarks = $ex->getMessage();

			BookingScheduleEvent::updateEventStatus($bseId, false, $remarks);

			Logger::exception($ex);
		}
	}

	public static function vendorCompensationByBooking($row)
	{
		$remarks	 = '';
		$status		 = true;
		$transaction = null;

		$bseId	 = $row['bse_id'];
		$bkgId	 = $row['bse_bkg_id'];
		try
		{
			$model = Booking::model()->findByPk($bkgId);
			if (!$model)
			{
				throw new Exception(serialize(['msg' => 'Invalid booking, booking id: ' . $bkgId, 'status' => true]));
			}

			$getCountCompensation = AccountTransDetails::getCountCompensation($bkgId, $model->bkgBcb->bcb_vendor_id);
			if ($getCountCompensation > 0)
			{
				throw new Exception(serialize(['msg' => 'Vendor compensation already added', 'status' => false]));
			}
			// No Compensation for Mobisign
//			if ($model->bkg_agent_id == Config::get('spicejet.partner.id'))
//			{
//				throw new Exception(serialize(['msg' => 'No vendor compensation for Spicejet, booking id: ' . $bkgId, 'status' => true]), ReturnSet::ERROR_VALIDATION);
//			}
			// No Compensation for local bookings and not marked as customer no-show
			if (in_array($model->bkg_booking_type, [4, 12, 9, 10, 11, 15]) && !in_array($model->bkg_cancel_id, [21, 37]))
			{
				throw new Exception(serialize(['msg' => 'No vendor compensation for local bookings and not marked as customer no-show, booking id: ' . $bkgId, 'status' => true]), ReturnSet::ERROR_VALIDATION);
			}

			if (!in_array($model->bkg_status, [9]))
			{
				throw new Exception(serialize(['msg' => 'Booking not in cancel state, booking id: ' . $bkgId, 'status' => true]), ReturnSet::ERROR_VALIDATION);
			}

			if (in_array($model->bkg_cancel_id, [22]))
			{
				throw new Exception(serialize(['msg' => 'No vendor compensation for marked as car no - show, booking id: ' . $bkgId, 'status' => true]), ReturnSet::ERROR_VALIDATION);
			}
			if (in_array($model->bkg_cancel_id, [9]))
			{
				throw new Exception(serialize(['msg' => 'No vendor compensation for Operator last minute cancel, booking id: ' . $bkgId, 'status' => true]), ReturnSet::ERROR_VALIDATION);
			}
			// Calculate Vendor Compensation
			$arr	 = BookingInvoice::calculateVendorCompensation($model);
			$amount	 = $arr['compensationAmt'];

			$transaction = DBUtil::beginTransaction();
			if ($arr['isVndCompensation'] > 0)
			{
				$model->bkgInvoice->bkg_vnd_compensation		 = $amount;
				$model->bkgInvoice->bkg_vnd_compensation_date	 = new CDbExpression('NOW()');
				if ($model->bkgInvoice->save())
				{
					$accRemarks = "Vendor compensation added for Booking ID : " . $bkgId;

					$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_OPERATOR, "", $amount, $accRemarks, $model->bkgBcb->bcb_vendor_id);
					$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $model->bkgBcb->bcb_vendor_id, '', $accRemarks);
					$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_COMPENSATION, Accounting::AT_BOOKING, $model->bkg_id, '', $accRemarks);
					$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_OPERATOR);

					BookingLog::model()->createLog($bkgId, $accRemarks, null, BookingLog::COMPENSATION_ADDED, false, '', '', $model->bkg_bcb_id);

					$remarks = "Vendor compensation " . $amount . " added successfully for Booking ID : " . $bkgId;

					$payLoadData = ['tripId' => $model->bkg_bcb_id, 'EventCode' => Booking::CODE_VENDOR_BROADCAST];

					$message = "Compensation of ₹" . $amount . " has been credited for Booking ID: " . $model->bkg_booking_id;
					$success = AppTokens::model()->notifyVendor($model->bkgBcb->bcb_vendor_id, $payLoadData, $message, "Compensation received");
				}
			}
			else
			{
				$remarks = "No vendor compensation to be given";
			}
			BookingScheduleEvent::updateEventStatus($bseId, $status, $remarks);

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$data	 = unserialize($ex->getMessage());
			$status	 = ($data['status']) ? true : false;
			DBUtil::rollbackTransaction($transaction);
			$remarks = ($data['msg']) ? $data['msg'] : $ex->getMessage();
			BookingScheduleEvent::updateEventStatus($bseId, $status, $remarks);
			Logger::exception($ex);
		}
	}

	public static function customerDetailsToOperatorEvent($bkgId = null)
	{
		$records = self::getTravellerDetailsList($bkgId);
		foreach ($records as $row)
		{
			$transaction = null;
			try
			{
				$transaction = DBUtil::beginTransaction();
				$bkgId		 = $row['bse_bkg_id'];
				$bseId		 = $row['bse_id'];
				// Booking
				$model		 = Booking::model()->findByPk($bkgId);
				// BookingScheduleEvent
				$modelbse	 = BookingScheduleEvent::model()->findByPk($bseId);

				$operatorId	 = Operator::getOperatorId($model->bkg_booking_type);
				$objOperator = Operator::getInstance($operatorId);

				/* @var $objOperator Operator */
				$response = $objOperator->updateBooking($model->bkg_id, $operatorId);

				if ($response->status == 1)
				{
					$modelbse->bse_event_status = BookingScheduleEvent::STATUS_PROCESSED;
					$modelbse->save();
				}
				else
				{
					$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_FAILED;
					$modelbse->bse_err_count	 += 1;
					$modelbse->bse_last_error	 = CJSON::encode($response);
					$modelbse->save();
				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_FAILED;
				$modelbse->bse_err_count	 += 1;
				$modelbse->bse_last_error	 = CJSON::encode($modelbse->getErrors());
				$modelbse->save();

				Logger::writeToConsole("Error: " . $ex->getMessage());
				Logger::exception($ex);
			}
		}
	}

	public static function processVendorAssignment()
	{
		$res = self::getPendingPostVendorAssignments();
		foreach ($res as $row)
		{
			$params				 = json_decode($row['bse_addtional_data'], true);
			$userParam			 = $params['userInfo'];
			$userInfo			 = new UserInfo();
			$userInfo->userId	 = $userParam["userId"];
			$userInfo->userType	 = $userParam["userType"];
			$returnSet			 = self::postVendorAssignment($params['tripId'], $params['vendorId'], $params['tripAmount'], $userInfo, $params['isDirectAccept']);

			$modelbse = BookingScheduleEvent::model()->findByPk($row['bse_id']);
			if ($returnSet->getStatus() == true)
			{
				$modelbse->bse_event_status = BookingScheduleEvent::STATUS_PROCESSED;
				$modelbse->save();
			}
			else
			{
				$modelbse->bse_event_status	 = BookingScheduleEvent::STATUS_FAILED;
				$modelbse->bse_err_count	 += 1;
				$modelbse->bse_last_error	 = CJSON::encode($returnSet->getErrors());
				$modelbse->save();
			}
		}
	}

	public static function postVendorAssignment($tripId, $vendorId, $tripAmount, $userInfo, $isDirectAccept)
	{
		$returnSet = new ReturnSet();
		try
		{
			$model = BookingCab::model()->findByPk($tripId);
			if (Config::get('hornok.operator.id') != $vendorId)
			{
				Vendors::notifyAssignVendor($tripId, 0);
			}
			BookingVendorRequest::scheduleSMTScore($tripId, $vendorId, $tripAmount);
			BookingInvoice::updateGozoAmount($tripId);
			BookingTrail::updateProfitFlag($tripId);

			/* Notify Lost Vendors */
			BookingVendorRequest::model()->notifyRejectedVendor($tripId, $isDirectAccept);

			BookingLog::model()->createLog($model->bcb_bkg_id1, "Vendors Lost Bid Notifications Sent", null, BookingLog::REMARKS_ADDED);

			AssignmentTracking::createRequest($model->bcb_id, $userInfo);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

}
