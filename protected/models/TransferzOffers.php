<?php

/**
 * This is the model class for table "transferz_offers".
 *
 * The followings are the available columns in table 'transferz_offers':
 * @property integer $trb_id
 * @property integer $trb_trz_id
 * @property integer $trb_trz_journey_id
 * @property string $trb_trz_journey_code
 * @property integer $trb_bkg_id
 * @property string $trb_pickup_date
 * @property integer $trb_vehicle_type
 * @property integer $trb_from_city_id
 * @property integer $trb_to_city_id
 * @property string $trb_expiry_date
 * @property string $trb_quote_data
 * @property string $trb_hash
 * @property integer $trb_status
 * @property string $trb_status_remarks
 * @property integer $trb_request_status
 * @property string $trb_create_date
 * @property string $trb_data_last_pull_date
 */
class TransferzOffers extends CActiveRecord
{

	public $tripType, $replacement, $status, $expiry, $timeZone, $createDate1, $createDate2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transferz_offers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('trb_trz_id, trb_trz_journey_id, trb_bkg_id, trb_vehicle_type, trb_from_city_id, trb_to_city_id, trb_status, trb_request_status', 'numerical', 'integerOnly'=>true),
			array('trb_id, trb_trz_id, trb_trz_journey_id, trb_trz_journey_code, trb_bkg_id, trb_pickup_date, trb_vehicle_type, trb_from_city_id, trb_to_city_id, trb_expiry_date, trb_quote_data, trb_hash, trb_status, trb_status_remarks, trb_request_status, trb_create_date, trb_data_last_pull_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('trb_id, trb_trz_id, trb_trz_journey_id, trb_trz_journey_code, trb_bkg_id, trb_pickup_date, trb_vehicle_type, trb_from_city_id, trb_to_city_id, trb_expiry_date, trb_quote_data, trb_hash, trb_status, trb_status_remarks, trb_request_status, trb_create_date, trb_data_last_pull_date', 'safe', 'on' => 'search'),
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
			'trb_id'				 => 'Trb',
			'trb_trz_id'			 => 'Trb Trz',
			'trb_trz_journey_id'	 => 'Journey Id',
			'trb_trz_journey_code'	 => 'Journey Code',
			'trb_bkg_id'			 => 'Trb Bkg',
			'trb_pickup_date'		 => 'Trb Pickup Date',
			'trb_vehicle_type'		 => 'Trb Vehicle Type',
			'trb_from_city_id'		 => 'Trb From City',
			'trb_to_city_id'		 => 'Trb To City',
			'trb_expiry_date'		 => 'Trb Expiry Date',
			'trb_quote_data'		 => 'Trb Quote Data',
			'trb_hash'				 => 'Trb Hash',
			'trb_status'			 => '0=>Pending, 1=>Booking Created, 2=>Booking failed',
			'trb_status_remarks'	 => 'Trb Status Remarks',
			'trb_request_status'	 => '0=>Pending, 1=>created, 2=>accept, 3=>declined',
			'trb_create_date'		 => 'Trb Create Date',
			'trb_data_last_pull_date' => 'Trb Data Last Pull Date',
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

		$criteria->compare('trb_id', $this->trb_id);
		$criteria->compare('trb_trz_id', $this->trb_trz_id);
		$criteria->compare('trb_trz_journey_id', $this->trb_trz_journey_id);
		$criteria->compare('trb_trz_journey_code', $this->trb_trz_journey_code, true);
		$criteria->compare('trb_bkg_id', $this->trb_bkg_id);
		$criteria->compare('trb_pickup_date', $this->trb_pickup_date, true);
		$criteria->compare('trb_vehicle_type', $this->trb_vehicle_type);
		$criteria->compare('trb_from_city_id', $this->trb_from_city_id);
		$criteria->compare('trb_to_city_id', $this->trb_to_city_id);
		$criteria->compare('trb_expiry_date', $this->trb_expiry_date, true);
		$criteria->compare('trb_quote_data', $this->trb_quote_data, true);
		$criteria->compare('trb_hash', $this->trb_hash, true);
		$criteria->compare('trb_status', $this->trb_status);
		$criteria->compare('trb_status_remarks', $this->trb_status_remarks, true);
		$criteria->compare('trb_request_status', $this->trb_request_status);
		$criteria->compare('trb_create_date', $this->trb_create_date, true);
		$criteria->compare('trb_data_last_pull_date', $this->trb_data_last_pull_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TransferzOffers the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function vhcCategoryMapping($vhcCategory = '')
	{
		$vhcCatId = false;

		$arrVhcCatMap					 = [];
		$arrVhcCatMap['ECONOMY_SEDAN']	 = [73, 3];
		$arrVhcCatMap['SEDAN']			 = [73, 3];
		$arrVhcCatMap['LUXURY_SEDAN']	 = [16];
		$arrVhcCatMap['BUSINESS_SEDAN']	 = [16];
		$arrVhcCatMap['MPV']			 = [74, 2];
		$arrVhcCatMap['SUV']			 = [2];
		$arrVhcCatMap['MINIVAN']		 = [74, 2];
		$arrVhcCatMap['MINIBUS']		 = [15];

		if ($vhcCategory != '' && isset($arrVhcCatMap[$vhcCategory]))
		{
			$vhcCatId = $arrVhcCatMap[$vhcCategory];
		}

		return $vhcCatId;
	}

	/**
	 * 
	 * @param type $journeyCode
	 * @return type
	 */
	public static function findByCode($journeyCode)
	{
		return self::model()->findByAttributes(array('trb_trz_journey_code' => $journeyCode));
	}

	/**
	 * 
	 * @return array
	 */
	public static function getList()
	{
		$sql	 = "SELECT * FROM  transferz_offers WHERE trb_status = 0 AND trb_request_status = 0 ORDER BY trb_id ASC LIMIT 0,20";
		$results = DBUtil::query($sql, DBUtil::MDB());
		return $results;
	}

	/**
	 * 
	 * @param type $model
	 * @param type $row
	 */
	public static function updateStatus($model, $offerId, $response = null, $jsonObj = null)
	{
		$transferzModel = TransferzOffers::model()->findByPk($offerId);
		$bkgModel = Booking::model()->findByPk($model->bkg_id);
		if ($model->bkg_status == 2 && $bkgModel->bkgPref->bkg_is_gozonow == 1)
		{
			$transferzModel->trb_bkg_id			 = $model->bkg_id;
			$transferzModel->trb_status			 = 2;
			$transferzModel->trb_request_status	 = 3;
			$transferzModel->trb_status_remarks	 = ($response == null) ? null : json_encode($response);
		}
		elseif ($model->bkg_status == 2 || $model->bkg_status == 3 || $model->bkg_status == 5)
		{
			$transferzModel->trb_status			 = 1;
			$transferzModel->trb_request_status	 = 2;
			$transferzModel->trb_bkg_id			 = $model->bkg_id;
			$transferzModel->trb_hash			 = $jsonObj->hash;
			$transferzModel->trb_status_remarks	 = ($response == null) ? null : json_encode($response);
		}
		elseif ($model->bkg_status == 9)
		{
			$transferzModel->trb_status			 = 1;
			$transferzModel->trb_request_status	 = 3;
			$transferzModel->trb_bkg_id			 = $model->bkg_id;
			$transferzModel->trb_hash			 = $jsonObj->hash;
			$transferzModel->trb_status_remarks	 = ($response == null) ? null : json_encode($response);
		}
		elseif ($model->bkg_status == 10)
		{
			$transferzModel->trb_status			 = 3;
			$transferzModel->trb_request_status	 = 3;
			$transferzModel->trb_bkg_id			 = null;
			$transferzModel->trb_status_remarks	 = ($response == null) ? null : json_encode($response);
		}
		else
		{
			$transferzModel->trb_bkg_id			 = $model->bkg_id;
			$transferzModel->trb_status			 = 2;
			$transferzModel->trb_request_status	 = 3;
			$transferzModel->trb_status_remarks	 = ($response == null) ? null : json_encode($response);
		}
		$transferzModel->save();
		$transferzModel->getErrors();
	}

	/**
	 * 
	 * @param type $id
	 * @return int | false
	 */
	public static function checkDuplicateReferenceId($id)
	{
		$param	 = ['id' => $id];
		$sql	 = "SELECT count(*) cnt FROM transferz_offers WHERE trb_trz_id = :id";
		$results = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $results;
	}

	/**
	 * 
	 * @param type $model
	 * @return boolean
	 */
	public static function isExistingData($model)
	{
		$returnSet = new ReturnSet();
		if ($model->trb_vehicle_type == false)
		{
			$returnSet->setData('no vehicle availability');
			$returnSet->setStatus(true);
		}
		return $returnSet;
	}

	public function process()
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$returnSet				 = new ReturnSet();
		$arrData				 = array();
		$arrData['apiUrl']		 = '/offers/available/all';
		$arrData['methodType']	 = 'GET';
		Logger::info("Booking request received");

		$objTransferz	 = new Transferz();
		$request		 = $objTransferz->callApi($arrData);
		Logger::trace("request".json_encode($request));

		$partnerId		 = Config::get('transferz.partner.id');
		foreach ($request as $data)
		{
			Logger::info("Booking request received in loop");
			try
			{
				$patModel	 = null;
				$jsonMapper	 = new JsonMapper();
				$jsonObj	 = CJSON::decode(CJSON::encode($data, true), false);

				/** @var \Beans\transferz\PendingRequest $obj */
				$obj = $jsonMapper->map($jsonObj, new Beans\transferz\PendingRequest());

				/** @var TransferzOffers $model */
				$model		 = $obj->setData();
				$typeAction	 = PartnerApiTracking::TYPE_PENDING_BOOKING;
				$patModel	 = PartnerApiTracking::add($typeAction, $jsonObj, $partnerId, $model, $model->trb_pickup_date);

				/**
				 * Check existing and duplicate data
				 */
				$duplicateOfferCount = TransferzOffers::duplicateOfferCount($model);
				if ($duplicateOfferCount > 0)
				{
					goto skip;
				}

				$result	 = TransferzOffers::isExistingData($model);
				$resData = CJSON::decode(CJSON::encode($result, true), false);
				if ($resData->success == true)
				{
					$model->trb_status_remarks	 = json_encode($resData->data);
					$model->trb_status			 = 2;
					$model->trb_request_status	 = 3;
				}
				if ($model->save())
				{
					$emailCom	 = new emailWrapper();
					$emailCom->transferz($model->trb_trz_journey_code);
					$returnSet->setStatus(true);
					$time		 = Filter::getExecutionTime();
					$patModel->updateData($returnSet, 1, null, null, null, $time);

					if ($model->trb_status == 2)
					{
						Booking::newBookingFromChannelPartner($model->trb_trz_journey_code, '', '', $model->trb_pickup_date);
//						self::sendWhatsapp($model->trb_trz_journey_code);
					}
				}
				skip:
			}
			catch (Exception $e)
			{
				$returnSet = ReturnSet::setException($e);
				Logger::Error('Error in proccess booking:' . $e->getMessage());
				if ($patModel)
				{
					$time = Filter::getExecutionTime();
					$patModel->updateData($returnSet, 2, null, $e->getCode(), $e->getMessage(), $time);

					Booking::newBookingFromChannelPartner($model->trb_trz_journey_code, '', '', $model->trb_pickup_date);
//					self::sendWhatsapp($model->trb_trz_journey_code);
				}
			}
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	public function create()
	{
		$returnSet = new ReturnSet();

		/* @var $results TransferzOffers */
		$results = TransferzOffers::getList();
		foreach ($results as $row)
		{
			try
			{
				$data		 = $row['trb_quote_data'];
				$jsonMapper	 = new JsonMapper();
				$jsonObj	 = CJSON::decode($data, false);
				$jsonObj	 = Filter::removeNull($jsonObj);
				$partnerId	 = Config::get('transferz.partner.id');
				/** @var \Beans\transferz\BookingRequest $obj */
				$obj		 = $jsonMapper->map($jsonObj, new \Beans\transferz\BookingRequest());

				/** @var Booking $model */
				$model												 = $obj->getModel();

				/** route block on feb 19th and 21st for Mumbai airport **/
				$isBlockRoute1 = self::blockRoute($model->bkg_pickup_date, $model->bkg_from_city_id, $model->bkg_to_city_id);

				$isBlockRoute2 = self::countConfirmBooking($model->bkg_pickup_date, $model->bkg_from_city_id, $model->bkg_to_city_id);
				if($isBlockRoute2 >= 100 || $isBlockRoute1 == true)
				{
					throw new Exception("Booking temporary block for this route");
				}

				$model->bkg_agent_ref_code							 = $row['trb_id'];
				$model->bkg_agent_id								 = $partnerId;
				$model->bkgTrail->btr_stop_increasing_vendor_amount	 = 1;
				$model->bkgInvoice->bkg_is_parking_included			 = 1;
				$model->bkgPref->bkg_trip_otp_required				 = 0;
				$model->bkgTrail->bkg_platform						 = 4;
				$model->bkgAddInfo->bkg_spl_req_carrier				 = 1;
                $model->bkgPref->bkg_block_autoassignment             = 1;

				/**
				 * generate booking against provided offer
				 */
				self::createBooking($jsonObj, $model, $row['trb_id']);
			}
			catch (Exception $e)
			{
				Logger::writeToConsole('E: ' . $e->getMessage());
				Logger::Error('Error in create booking:' . $e->getMessage());
				$returnSet		 = ReturnSet::setException($e);
				$time			 = Filter::getExecutionTime();
				$statusUpdate	 = TransferzOffers::updateStatus($model, $row['trb_id'], $returnSet, $jsonObj);
			}
		}
	}

	public function updateBooking()
	{
		$patModel	 = null;
		$returnSet	 = new ReturnSet();

		/* @var $results Booking */
		$results = Booking::getTransferzActiveBookingList();
		foreach ($results as $row)
		{
			try
			{
				$journeyDetails = false;
				$model = Booking::model()->findByPk($row['bkg_id']);
				
				Logger::writeToConsole("bkg_id: " . $row['bkg_id'] . " - " . $model->bkg_agent_ref_code);
				
				if (is_numeric($model->bkg_agent_ref_code))
				{
					$journeyDetails = self::getOffer($model->bkg_agent_ref_code);
				}
				if(!$journeyDetails)
				{
					throw new Exception("No transfers offer found: " . $model->bkg_agent_ref_code);
				}
				
				Logger::writeToConsole("trb_trz_journey_id: " . $journeyDetails['trb_trz_journey_id']);

				/* @var $data ClassName */
				$data = Transferz::getJourneyDetailsById($journeyDetails['trb_trz_journey_id'], null);
				if ($data)
				{
					Logger::writeToConsole("data");
					$jsonMapper	 = new JsonMapper();
					$jsonObj	 = CJSON::decode(CJSON::encode($data, true), false);
					$addOn		 = count($jsonObj->addOns);

					if ($jsonObj->hash != $journeyDetails['trb_hash'])
					{
						Logger::writeToConsole("hash");
						
						if (($jsonObj->assignmentStatus == "ASSIGNED" && $jsonObj->status == "CONFIRMED") || ($addOn > 0))
						{
							Logger::writeToConsole("ASSIGNED");
							
							$typeAction		 = AgentApiTracking::TYPE_UPDATE_BOOKING;
							$patModel		 = PartnerApiTracking::add($typeAction, $model->bkg_id, $model->bkg_agent_id, $model, $model->bkg_pickup_date);
							$updateUserInfo	 = BookingUser::updateTransferzData($model, $jsonObj);
							$updateAddInfo	 = BookingAddInfo::updateTransferzData($model, $jsonObj);
							if ($updateUserInfo == true && $updateAddInfo == true)
							{
								$returnSet->setMessage("Data update successfully");
								$returnSet->setStatus(true);
							}

							$time			 = Filter::getExecutionTime();
							$patModel->updateData($jsonObj, 1, $model->bkg_id, null, null, $time);
							$statusUpdate	 = TransferzOffers::updateStatus($model, $journeyDetails['trb_id'], $returnSet, $jsonObj);
						}

						/**
						 * Cancel a journey with cost or free or Reassign the journey to another transfer company
						 */
						if (($jsonObj->assignmentStatus == "ASSIGNED" && $jsonObj->status == "CANCELLED_FREE") || ($jsonObj->assignmentStatus == "ASSIGNED" && $jsonObj->status == "CANCELLED_WITH_COSTS") || ($jsonObj->assignmentStatus == "REASSIGNED"))
						{
							Logger::writeToConsole("CANCELLED_FREE");
							
							$typeAction	 = AgentApiTracking::TYPE_CANCEL_BOOKING;
							$patModel	 = PartnerApiTracking::add($typeAction, $model->bkg_id, $model->bkg_agent_id, $model, $model->bkg_pickup_date);

							$cancelReason	 = CancelReasons::getTransferzCancelId();
							$reasonText		 = $cancelReason['cnr_reason'];
							$reasonId		 = $cancelReason['cnr_id'];
							$success		 = $model->canbooking($model->bkg_id, $reasonText, $reasonId, null, 1);
							if ($success)
							{
								$model->bkg_status				 = 9;
								$model->bkg_cancel_id			 = $reasonId;
								$model->bkg_cancel_delete_reason = $reasonText;
								$model->save();
								$returnSet->setMessage("Booking cancel successfully");
								$returnSet->setStatus(true);
							}
							$time			 = Filter::getExecutionTime();
							$patModel->updateData($jsonObj, 1, $model->bkg_id, null, null, $time);
							$statusUpdate	 = TransferzOffers::updateStatus($model, $journeyDetails['trb_id'], $returnSet, $jsonObj);
						}

						/**
						 * Change the pickup date of a journey and accept replacement offer
						 */
						$objPickupDate	 = new \DateTime($jsonObj->pickupTime->localTime, new \DateTimeZone($jsonObj->pickupTime->timeZone));
						$pickupTime		 = $objPickupDate->format('Y-m-d H:i:s');
						$timeDiff		 = Filter::getTimeDiff($pickupTime, $model->bkg_pickup_date);
						$newCityId		 = Cities::getCityByLatLng($jsonObj->pickup->latitude, $jsonObj->pickup->longitude);

						if (($timeDiff > 0 && $jsonObj->status == "PENDING") || ($newCityId != $model->bkg_from_city_id && $jsonObj->status == "PENDING"))
						{
							Logger::writeToConsole("PENDING");
							
							$typeAction		 = AgentApiTracking::TYPE_CANCEL_BOOKING;
							$patModel		 = PartnerApiTracking::add($typeAction, $model->bkg_id, $model->bkg_agent_id, $model, $model->bkg_pickup_date);
							$cancelReason	 = CancelReasons::getTransferzCancelWithNoCost();
							$reasonText		 = $cancelReason['cnr_reason'];
							$reasonId		 = $cancelReason['cnr_id'];
							$success		 = $model->canbooking($model->bkg_id, $reasonText, $reasonId, null, 1);
							if ($success)
							{
								$model->bkg_status				 = 9;
								$model->bkg_cancel_id			 = $reasonId;
								$model->bkg_cancel_delete_reason = $reasonText;
								$model->save();
								$returnSet->setMessage("Booking cancel successfully");
								$returnSet->setStatus(true);
							}
							$time			 = Filter::getExecutionTime();
							$patModel->updateData($jsonObj, 1, $model->bkg_id, null, null, $time);
							$statusUpdate	 = TransferzOffers::updateStatus($model, $journeyDetails['trb_id'], $returnSet, $jsonObj);
						}
					}
					
					$sql = "UPDATE transferz_offers SET trb_data_last_pull_date = NOW() WHERE trb_id = {$journeyDetails['trb_id']}";
						
					Logger::writeToConsole("SQL: " . $sql);

					DBUtil::execute($sql);
				}
				else
				{
					Logger::writeToConsole("no data found");
					
					$returnSet->setMessage("no data found");
					$returnSet->setStatus(false);
				}
			}
			catch (Exception $e)
			{
				Logger::writeToConsole("Error: " . $e->getMessage());
				
				$returnSet = ReturnSet::setException($e);
				if ($patModel)
				{
					Logger::writeToConsole("UpdateData");
					
					$time = Filter::getExecutionTime();
					$patModel->updateData($returnSet, 2, null, $e->getCode(), $e->getMessage(), $time);
				}
			}
		}
	}

	/**
	 * 
	 * @param type $jsonObj
	 * @param type $model
	 * @param type $row
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function accept($jsonObj, $model, $offerId)
	{
		$returnSet		 = new ReturnSet();
		/** @var \Beans\transferz\AcceptOffer $response */
		$acceptOfferId	 = $jsonObj->id;

		$response	 = new Beans\transferz\AcceptOffer();
		$response->setData($jsonObj);
		$data		 = Filter::removeNull($response);

		$offerData		 = Transferz::acceptOffer($model, $data, $acceptOfferId);
		$acceptedOffer	 = CJSON::decode(CJSON::encode($offerData, true), false);
		if (($acceptedOffer->status == 'CONFIRMED') || ($acceptedOffer->status == 'PENDING'))
		{
			$updateUserInfo = BookingUser::updateTransferzData($model, $acceptedOffer);
			if ($model->bkgInvoice->bkg_advance_amount)
			{
				$actmodel = AccountTransactions::usePartnerWallet($model->bkg_pickup_date, $model->bkgInvoice->bkg_advance_amount, $model->bkg_id, $model->bkg_agent_id, "Partner Wallet used", UserInfo::getInstance());
				if (!$actmodel)
				{
					$returnSet->setStatus(false);
					throw new Exception("Booking failed as partner wallet balance exceeded.");
				}
			}
			if ($model->confirm())
			{
				Logger::error("transferz booking confirm :");
				$returnSet->setStatus(true);
				$returnSet->setMessage("booking accept successfully");
			}
			$model->save();
			$statusUpdate = TransferzOffers::updateStatus($model, $offerId, $returnSet, $acceptedOffer);
		}
		else
		{
			$success	 = Booking::expireQuote($model);
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userType	 = UserInfo::TYPE_SYSTEM;
			if ($success)
			{
				$desc = 'Quote has been exired manually . Reason: ' . $reason;
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog:: QUOTE_EXPIRED, false, false);
			}
			$desc			 = "Booking not confirm by transferz for ID : " . $model->bkg_id;
			$eventId		 = BookingLog::BOOKING_UNVERIFIED;
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId);
		
			$returnSet->setMessage($desc);
			$returnSet->setStatus(false);
			$statusUpdate	 = TransferzOffers::updateStatus($model, $offerId, $returnSet, $acceptedOffer);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param type $model
	 * @param type $row
	 * @return \ReturnSet
	 */
	public static function decline($model, $offerId, $jsonObj)
	{
		$returnSet	 = new ReturnSet();
		/** @var \Beans\transferz\DeclineResponse $response */
		$response	 = new Beans\transferz\DeclineResponse();
		$response->setData();
		$data		 = Filter::removeNull($response);

		/* @var $offerDecline Transferz */
		$offerDecline = Transferz::declineBooking($model, $data, $jsonObj);

		if ($offerDecline != null)
		{
			$returnSet->setStatus(true);
		}

		/** @var TransferzBookings $statusUpdate */
		$statusUpdate = TransferzOffers::updateStatus($model, $offerId, $returnSet, null);
		return $returnSet;
	}

	/**
	 * 
	 * @param type $id
	 * @return int | false
	 */
	public static function checkDuplicateJourneyId($id)
	{
		$param	 = ['id' => $id];
		$sql	 = "SELECT count(*) cnt FROM transferz_offers WHERE trb_trz_journey_id = :id";
		$results = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $results;
	}

	/**
	 * 
	 * @param type $jsonObj
	 * @param type $model
	 * @param type $offerId
	 * @return \ReturnSet
	 */
	public static function createBooking($jsonObj, $model, $offerId)
	{
		$patModel	 = null;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$returnSet			 = new ReturnSet();
			$partnerId			 = Config::get('transferz.partner.id');
			$typeAction			 = PartnerApiTracking::CREATE_BOOKING;
			$patModel			 = PartnerApiTracking::add($typeAction, $jsonObj, $partnerId, $model, $model->bkg_pickup_date);
			$tranferzTotalAmount = $model->bkgInvoice->bkg_total_amount;
			/** @var Booking $model */
			$cabIds				 = $model->bkg_vehicle_type_id;
			$quotData			 = Quote::populateFromModel($model, $cabIds, false, true, $isAllowed			 = true);

			$fare			 = [];
			$quotefare		 = [];
			$minIntraQuote	 = [];

			foreach ($cabIds as $key1 => $scvVehicleIdeach)
			{
				foreach ($quotData as $key => $value)
				{
					if ($value->skuId == $scvVehicleIdeach)
					{
						$arr[$cabIds[$key1]] = $value->routeRates->totalAmount;
						$fare[$key1][$key]	 = $value->routeRates->totalAmount;
					}
				}
			}
			$totalAmount						 = min($arr);
			$model->bkgInvoice->bkg_total_amount = $totalAmount;

			foreach ($fare as $key2 => $value)
			{
				$selectedFare[array_search(min($value), $value)] = min($value);
			}
			foreach ($selectedFare as $key4 => $value)
			{
				if ($value == $totalAmount)
				{
					$skuId = $key4;
				}
			}
			$model->bkg_vehicle_type_id = $skuId;

			$svcModel							 = SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $model->bkg_vehicle_type_id);
			$cancelRuleId						 = \CancellationPolicy::getCancelRuleId($model->bkg_agent_id, $svcModel->scv_id, $model->bkg_from_city_id, $model->bkg_to_city_id, $model->bkg_booking_type);
			$model->bkgPref->bkg_cancel_rule_id	 = $cancelRuleId;

			$totalAmount = $model->bkgInvoice->bkg_total_amount;
			if ($totalAmount <= $tranferzTotalAmount)
			{
				$model->addNew(true);
				if ($model->hasErrors())
				{
					goto handleErrors;
				}
				$extraAmount = round($tranferzTotalAmount - $totalAmount);
				$staxRate	 = BookingInvoice::getGstTaxRate($partnerId, $model->bkg_booking_type);

				if ($extraAmount > 0)
				{
					$extraAmountGST							 = round($extraAmount - $extraAmount / (1 + (0.01 * $staxRate)));
					$extraBaseFare							 = $extraAmount - $extraAmountGST;
					$model->bkgInvoice->bkg_base_amount		 += $extraBaseFare;
					$model->bkgInvoice->bkg_base_amount		 = round($model->bkgInvoice->bkg_base_amount - Config::get('TransferzBooking.parking.charge'));
					$model->bkgInvoice->bkg_parking_charge	 = Config::get('TransferzBooking.parking.charge');

					$model->bkgInvoice->calculateTotal();
					if ($model->bkgInvoice->bkg_due_amount <= 1)
					{
						$model->bkgInvoice->bkg_due_amount	 = 0;
						$model->bkgInvoice->bkg_service_tax	 = ($model->bkgInvoice->bkg_service_tax - $model->bkgInvoice->bkg_due_amount);
					}
					$model->bkgInvoice->bkg_advance_amount = $model->bkgInvoice->bkg_total_amount;
					$model->bkgInvoice->save();
				}

				DBUtil::commitTransaction($transaction);
				$returnSet->setStatus(true);
				$time = Filter::getExecutionTime();
				$patModel->updateData($returnSet, 1, $model->bkg_id, null, null, $time);

				// Accept offer 
				self::accept($jsonObj, $model, $offerId);
				$returnSet->setStatus(true);
				Booking::newBookingFromChannelPartner($jsonObj->journey->code, $model->bkg_booking_id, $model->bkg_id, $model->bkg_pickup_date);
				//self::sendWhatsapp($jsonObj->journey->code, $model->bkg_booking_id, $model->bkg_id);
				goto end;

				handleErrors:
				$errors = $model->getErrors();
				throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
			}
			else
			{
				//convert booking in gozonow
				if(Config::get('transferz.gozonow.enable') == 1)
				{
					$model->bkgInvoice->bkg_total_amount = $tranferzTotalAmount;
					$currentDateTime					 = date_create(Filter::getDBDateTime());
					$pickupDateTime						 = date_create($model->bkg_pickup_date);
					$minutesToPickup					 = Filter::getTimeDiff($model->bkg_pickup_date);
					if ($minutesToPickup < 720)
					{
						$model->isGozonow	 = true;
						/* @var $model Booking */
						$model				 = $model->fbg();
						if ($model)
						{
							$desc = "GozoNOW activated manually for the booking: ";
							BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog:: ACTIVATE_GOZO_NOW, false);

							$desc			 .= BookingCab::gnowNotify($model->bkg_bcb_id);
							$data['message'] = $desc;
							$success		 = true;
						}
					}
					else
					{
						//convert in quote booking only
						$model->isGozonow				 = false;
						$model->bkgPref->bkg_is_gozonow	 = 0;
						/* @var $model Booking */
						$model							 = $model->fbg();
					}
				}


				$returnSet->setMessage("Transferz price is low ( Transferz amount : " . $tranferzTotalAmount . " - " . "Gozo Amount : " . $totalAmount);
				//self::decline($model, $offerId, $jsonObj);
				$statusUpdate	 = TransferzOffers::updateStatus($model, $offerId, $returnSet, $jsonObj);
				$returnSet->setStatus(false);
				$time			 = Filter::getExecutionTime();
				$patModel->updateData($returnSet, 2, NULL, $returnSet->getErrorCode(), $returnSet->getMessage(), $time);
				DBUtil::commitTransaction($transaction);
				Booking::newBookingFromChannelPartner($jsonObj->journey->code, $model->bkg_booking_id, $model->bkg_id, $jsonObj->journey->pickupTime->localTime);
			}
		}
		catch (Exception $e)
		{
			$returnSet		 = $returnSet->setException($e);
			Logger::exception($e);
			$statusUpdate	 = TransferzOffers::updateStatus($model, $offerId, $returnSet, $jsonObj);

			$time = Filter::getExecutionTime();
			if ($patModel)
			{
				$patModel->updateData($returnSet, 2, null, $e->getCode(), $e->getMessage(), $time);
			}
			Booking::newBookingFromChannelPartner($jsonObj->journey->code, '', '', $jsonObj->journey->pickupTime->localTime);
			DBUtil::commitTransaction($transaction);
//			self::sendWhatsapp($jsonObj->journey->code);
		}

		end:
		return $returnSet;
	}

	public static function sendWhatsapp($partnerBkgId, $bookingId = '', $bkgId = '')
	{
		$partnerName	 = 'Transferz';
		$templateName	 = 'new_booking_from_channel_partner';
		$bookingId		 = ($bookingId != '' ? $bookingId : '*No booking created*');

		$arrAdmins = ['311' => '919831100164', '53' => '919903430853', '13' => '919831859111', '544' => '919051153099', '455' => '918017233722'];
		foreach ($arrAdmins as $adminId => $phone)
		{
			$arrWhatsAppData = [$partnerName, $partnerBkgId, $bookingId];
			$arrDBData		 = ['entity_type' => UserInfo::TYPE_ADMIN, 'entity_id' => $adminId];

			if ($bkgId > 0)
			{
				$arrDBData['ref_type']	 = 1;
				$arrDBData['ref_id']	 = $bkgId;
			}
			$arrBody = Whatsapp::buildComponentBody($arrWhatsAppData);

			WhatsappLog::send($phone, $templateName, $arrDBData, $arrBody);
		}
	}

	/**
	 * 
	 * @param type $id
	 * @return type
	 */
	public static function getTransferzOfferIdJourneyId($id)
	{
		$param	 = ['id' => $id];
		$sql	 = "SELECT trb_id FROM transferz_bookings WHERE trb_trz_journey_id = :id";
		$results = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $results;
	}

	/**
	 * 
	 * @param type $id
	 * @return type
	 */
	public static function getQuoteDataByOfferId($id)
	{
		$param	 = ['id' => $id];
		$sql	 = "SELECT * FROM transferz_bookings WHERE trb_trz_id = :id";
		$results = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return $results;
	}

	/**
	 * 
	 * @param type $id
	 * @return type
	 */
	public static function getQueryRequest($id)
	{
		$param	 = ['id' => $id];
		$sql	 = "SELECT trb_quote_data FROM transferz_bookings WHERE trb_trz_journey_id = :id";
		$results = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $results;
	}

	/**
	 * 
	 * @param type $id
	 * @return type
	 */
	public static function getOfferIdss($id)
	{
		$param	 = ['id' => $id];
		$sql	 = "SELECT trb_trz_id FROM transferz_bookings WHERE trb_trz_journey_id = :id";
		$results = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $results;
	}

	/**
	 * 
	 * @param type $id
	 * @return type
	 */
	public static function getOffer($id)
	{
		$param	 = ['id' => $id];
		$sql	 = "SELECT * FROM transferz_offers WHERE trb_id = :id";
		$results = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return $results;
	}

	/**
	 * 
	 * @param type $bmodel
	 * @param type $jsonObj
	 * @param type $offerId
	 */
	public static function reAcceptOffer($bmodel, $jsonObj, $offerId, $oldBkgId)
	{
		$data						 = CJSON::encode($jsonObj);
		$jsonData					 = CJSON::decode($data);
		$model						 = new TransferzOffers();
		$model->trb_trz_id			 = $offerId;
		$model->trb_trz_journey_id	 = $jsonData['id'];
		$model->trb_trz_journey_code = $jsonData['code'];
		$model->trb_bkg_id			 = $bmodel->bkg_id;
		$model->trb_pickup_date		 = $bmodel->bkg_pickup_date;
		$model->trb_vehicle_type	 = $bmodel->bkg_vehicle_type_id;
		$model->trb_from_city_id	 = $bmodel->bkg_from_city_id;
		$model->trb_to_city_id		 = $bmodel->bkg_to_city_id;
		$model->trb_expiry_date		 = $bmodel->bkg_pickup_date;
		$model->trb_quote_data		 = CJSON::encode($jsonData);
		$model->trb_hash			 = $jsonData['hash'];
		$model->trb_status			 = 1;
		$model->trb_status_remarks	 = "Reaccept booking id " . $bmodel->bkg_id . " and previous booking id " . $oldBkgId;
		$model->trb_request_status	 = 2;
		$model->trb_create_date		 = $bmodel->bkg_pickup_date;
		$model->save();
		$model->getErrors();
	}

	/**
	 * 
	 * @param type $id
	 * @return type
	 */
	public static function getJourneyIdByBkgId($id)
	{
		$param	 = ['id' => $id];
		$sql	 = "SELECT trb_trz_journey_id FROM transferz_offers WHERE trb_bkg_id = :id";
		$results = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $results;
	}

	public static function getFailedBooking($model, $type = DBUtil::ReturnType_Provider)
	{
		if ($model->createDate1 != '' && $model->createDate2 != '')
		{
			$fromDate	 = $model->createDate1;
			$toDate		 = $model->createDate2;
			$where		 .= " AND DATE(trb_create_date) BETWEEN '$fromDate' AND '$toDate' ";
		}
		if ($model->trb_trz_journey_code != '')
		{
			$code	 = $model->trb_trz_journey_code;
			$where	 .= " AND trb_trz_journey_code LIKE '%$code%'";
		}
		if ($model->trb_trz_journey_id != '')
		{
			$journeyId	 = $model->trb_trz_journey_id;
			$where		 .= " AND trb_trz_journey_id LIKE '%$journeyId%'";
		}
		if ($model->trb_status != '')
		{
			$status	 = $model->trb_status;
			$where	 .= " AND trb_status = $status";
		}
		else
		{
			$where .= " AND trb_status = 2";
		}

		$sql = "SELECT
					trb_id,
					trb_trz_journey_id,
					trb_trz_journey_code,
					trb_bkg_id,
					trb_vehicle_type,
					scvc.scv_label AS 'vehicletype',
					trb_from_city_id, 
					c1.cty_display_name AS fromCityName,
					c2.cty_display_name AS toCityName,
					trb_to_city_id,
					trb_pickup_date,
					trb_create_date, trb_status
				FROM `transferz_offers`
				INNER JOIN `cities` c1 ON c1.cty_id = trb_from_city_id
				INNER JOIN `cities` c2 ON c2.cty_id = trb_to_city_id
				LEFT JOIN svc_class_vhc_cat scvc ON scvc.scv_id = trb_vehicle_type AND scvc.scv_active = 1 
				WHERE 1" . $where;
		if ($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) temp ", DBUtil::SDB3(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				"params"		 => $params,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes'	 => ['trb_trz_journey_id', 'trb_trz_journey_code'],
					'defaultOrder'	 => 'trb_id DESC'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3(), $params);
		}
	}

	public static function getOfferStatus($id = '')
	{
		$arrStatus = [
			0	 => 'Pending',
			1	 => 'Booking Created',
			2	 => 'Booking Failed'
		];
		if ($id != '')
		{
			return $arrStatus[$id];
		}
		else
		{
			return $arrStatus;
		}
	}

	/**
	 * 
	 * @param type $model
	 * @return type
	 */
	public static function duplicateOfferCount($model)
	{
		$param	 = ['journeyCode' => $model->trb_trz_journey_code, 'pickupDate' => $model->trb_pickup_date , 'journeyId' => $model->trb_trz_journey_id];
		$sql	 = "SELECT count(*) cnt FROM transferz_offers WHERE trb_trz_journey_code = :journeyCode AND trb_pickup_date = :pickupDate AND trb_trz_journey_id = :journeyId";
		$results = DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
		return $results;
	}

	/**
	 * 
	 * @param type $pickupDatetime
	 * @param type $fromCity
	 * @return boolean
	 */
	public static function blockRoute($pickupDatetime, $fromCity, $toCity)
	{
		$result	 = false;
		$pickup	 = date('Y-m-d', strtotime($pickupDatetime));
		$date1	 = "2024-02-19";
		$date2   = "2024-02-21";
		if ((($pickup == $date1 || $pickup == $date2) && $fromCity == 471389))
		{
			$result = true;
		}
		return $result;
	}

	/**
	 * 
	 * @param type $bkgModel
	 * @return json
	 */
	public static function isAccept($bkgModel)
	{
		$getOffer		 = TransferzOffers::getOffer($bkgModel->bkg_agent_ref_code);
		$offerId		 = $getOffer['trb_id'];
		$data			 = $getOffer['trb_quote_data'];
		$jsonMapper		 = new JsonMapper();
		$jsonObj		 = CJSON::decode($data, false);
		$jsonObj		 = Filter::removeNull($jsonObj);
		$isAcceptData	 = TransferzOffers::accept($jsonObj, $bkgModel, $offerId);
		$acceptedOffer	 = CJSON::decode(CJSON::encode($isAcceptData, true), false);
		return $acceptedOffer;
	}

	/**
	 * 
	 * @param type $pickupDateTime
	 * @param type $fromCity
	 * @param type $toCity
	 * @return count
	 */
	public static function countConfirmBooking($pickupDateTime, $fromCity, $toCity)
	{
		//$pickupDate	 = date('Y-m-d', strtotime($pickupDateTime));
		$param	 = ['pickupDate' => $pickupDateTime , 'fromCity' => $fromCity, 'toCity' => $toCity];
		$sql = "SELECT count(*) as cnt  FROM transferz_offers
				WHERE trb_pickup_date = :pickupDate AND trb_from_city_id = :fromCity
				AND trb_to_city_id = :toCity AND trb_status = 1 AND trb_request_status = 2";
		$results = DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
		return $results;
	}

}
