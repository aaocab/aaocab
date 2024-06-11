<?php

/**
 * Description of BookFormRequest
 *
 * @author Admin
 */

/**
 * @property ContactVerification[] $contactVerifications
 * @property \Stub\common\Booking $booking
 * @property \Stub\consumer\SignUpRequest $signupRequest
 */
class BookFormRequest
{

	/** @var \Stub\common\ContactVerification[] $contactVerifications */
	public $contactVerifications = [];
	public $isNewUser			 = 0;
	public $tripCategory		 = 0;
	public $tripType			 = 0;

	/** @var \Stub\common\Booking $booking */
	public $booking				 = null;
	public $step				 = 0;
	public $steps				 = [];
	public $afterLoginStep		 = 0;
	public $isRedirectedBooking	 = 0;

	/** @var \Stub\booking\QuoteResponse $quote */
	public $quote = null;

	/** @var \Stub\consumer\SignUpRequest $signupRequest */
	public $signupRequest = null;

	/** @var \Stub\common\Cab $cab */
	public $cab = null;

	const URL_HOME				 = 1000; //index/index
	const URL_CHECKACCOUNT		 = 1001; //booking/CheckAccount
	const URL_CABSEGMENTATION		 = 1002; //booking/tripType
	const URL_SERVICETYPE			 = 1003; //booking/bkgType
	const URL_ITINERARY			 = 1004; //booking/Itinerary
	const URL_SIGNINBYPASSWORD	 = 1005; //users/VerifyPass
	const URL_SIGNINBYOTP			 = 1006; //users/SendOTP
	const URL_SIGNUP				 = 1007; //users/SignupOTP
	const URL_QUOTE				 = 1008; //booking/CatQuotes
	const URL_TIRE				 = 1009; //booking/TierQuotes
	const URL_MORETIRE			 = 1010; //booking/moreTierQuotes
	const URL_ADDRESS				 = 1011; //booking/address
	const URL_REVIEW				 = 1012; //booking/review
	const URL_FINALPAY			 = 1013; //booking/finalPay
	const URL_PAY					 = 1014; //booking/paymentreview
	const URL_PAYINTERNAL			 = 1015; //booking/paymentv3
	const URL_BKPN				 = 1016; //booking/Paynow
	const URL_PARTNER_BOOKING		 = 1017; // booking/airport
	const URL_REDIRECTED_BOOKING	 = 1018; // booking/cab
	const URL_PROFILE				 = 1020; //users/View
	const URL_BOOKINGLIST			 = 1021; //booking/list
	const URL_BOOKINGVIEW			 = 1022; //booking/view
	const URL_BOOKINGMODIFY		 = 1023; //booking/editnew
	const URL_BOOKINGCANCEL		 = 1024; //booking/CheckTripStatus
	const URL_BOOKINGINVOICE		 = 1025; //booking/receipt
	const URL_BOOKINGADDREVIEW	 = 1026; //rating/Addreview
	const URL_BOOKINGREVIEW		 = 1027; //rating/Bookingreview
	const URL_BOOKINGSHOWREVIEW	 = 1028; //rating/Showreview
	const URL_PLACE				 = 1029; //place/View
	const URL_ADDPLACE			 = 1030; //place/create
	const URL_UPDATEPLACE			 = 1031; //place/update
	const URL_DELETEPLACE			 = 1032; //place/Deleteme
	const URL_REFER				 = 1033; //users/refer
	const URL_WALLETHISTORY		 = 1034; //users/creditlist
	const URL_VOUCHERS			 = 1035; //voucher/Orders
	const URL_REDEEMVOUCHER		 = 1036; //voucher/Redeem
	const URL_ADDONS				 = 1037; //booking/Addons
	const URL_CAPTCHA_VERIFY		 = 1038; //users/CaptchaVerify
    const URL_FLASHBOOKING          = 1039; //booking/InfoNew

//===========================================

	/** @return static */
	public static function createInstance($encryptedData = null)
	{
		$obj = new static();
		if ($encryptedData == null)
		{
			goto end;
		}

		$jsonObj = self::decryptData($encryptedData);

		if($jsonObj == null)
		{
			Logger::warning("Encrypted data is NULL", true);
			goto end;
		}
		
		$jsonMapper	 = new JsonMapper();
		/** @var BookFormRequest $obj */
		$obj		 = $jsonMapper->map($jsonObj, $obj);

		end:
		return $obj;
	}

	public function getTripTypeDesc()
	{
		$desc = "Trip Type";
		if ($this->tripCategory == 1)
		{
			$desc = "Local";
		}

		if ($this->tripCategory == 2)
		{
			$desc = "Outstation";
		}
		return $desc;
	}

	public function getBkgTypeDesc1()
	{
		$desc = "Booking Type";
		if ($this->booking != null)
		{
			if ($this->booking->tripType == 1)
			{
				$desc = "One Way";
				if ($this->booking->transferType == 1)
				{
					$desc .= " (Airport Pickup)";
				}
				if ($this->booking->transferType == 2)
				{
					$desc .= " (Airport Drop)";
				}
			}
			if ($this->booking->tripType == 2)
			{
				$desc = "Round Trip";
			}
			if ($this->booking->tripType == 3)
			{
				$desc = "Multi City";
			}
			if ($this->booking->tripType == 4)
			{
				$desc = "Airport Transfer";
				if ($this->booking->transferType == 1)
				{
					$desc = "Airport Pickup";
				}
				if ($this->booking->transferType == 2)
				{
					$desc = "Airport Drop";
				}
			}
			if ($this->booking->tripType == 5)
			{
				$desc = "Outstation Package";
			}
			if ($this->booking->tripType == 6)
			{
				$desc = "Flexi";
			}
			if ($this->booking->tripType == 7)
			{
				$desc = "Daily Rental";
			}
			if ($this->booking->tripType == 8)
			{
				$desc = "Daily Rental";
			}
			if ($this->booking->tripType == 9)
			{
				$desc = "Daily Rental";
			}
			if ($this->booking->tripType == 12)
			{
				$desc = "Airport Package";
				if ($this->booking->transferType == 1)
				{
					$desc = "Airport Pickup";
				}
				if ($this->booking->transferType == 2)
				{
					$desc = "Airport Drop";
				}
			}
			if ($this->booking->tripType == 14)
			{
				$desc = "Intra City";
			}
			if ($this->booking->tripType == 15)
			{
				$desc = "Local Transfer";
				if ($this->booking->transferType == 1)
				{
					$desc = "Local Pickup";
				}
				if ($this->booking->transferType == 2)
				{
					$desc = "Local Drop";
				}
			}
		}
		return $desc;
	}

	public function getBkgTypeDesc()
	{
		$desc			 = "Booking Type";
		$tripCategory	 = $this->tripCategory;
		if ($this->booking != null)
		{
			if ($this->booking->tripType == 1 && $tripCategory == 2)
			{
				$desc = "Outstation (One Way)";
				if ($this->booking->transferType == 1)
				{
					$desc = " Outstation (One Way -Airport Pickup)";
				}
				if ($this->booking->transferType == 2)
				{
					$desc = " Outstation (One Way -Airport Drop)";
				}
			}
			if ($this->booking->tripType == 2 && $tripCategory == 2)
			{
				$desc = "Outstation (Round Trip)";
			}
			if ($this->booking->tripType == 3 && $tripCategory == 2)
			{
				$desc = "Outstation (Multi City)";
			}
			if ($this->booking->tripType == 4)
			{
				$desc = "Airport Transfer";
				if ($this->booking->transferType == 1)
				{
					$desc = "Local (Airport Pickup)";
				}
				if ($this->booking->transferType == 2)
				{
					$desc = "Local (Airport Drop)";
				}
			}
			if ($this->booking->tripType == 5)
			{
				$desc = "Outstation Package";
			}
			if ($this->booking->tripType == 6)
			{
				$desc = "Flexi";
			}
			if ($this->booking->tripType == 7 && $tripCategory == 1)
			{
				$desc = "Local(Daily Rental)";
			}
			if ($this->booking->tripType == 8 && $tripCategory == 1)
			{
				$desc = "Local(Daily Rental)";
			}
			if ($this->booking->tripType == 9)
			{
				$desc = "Local (Daily Rental)";
			}
			if ($this->booking->tripType == 10)
			{
				$desc = "Local (Daily Rental)";
			}
			if ($this->booking->tripType == 11)
			{
				$desc = "Local (Daily Rental)";
			}
			if ($this->booking->tripType == 12)
			{
				$desc = "Airport Package";
				if ($this->booking->transferType == 1)
				{
					$desc = "Airport Pickup";
				}
				if ($this->booking->transferType == 2)
				{
					$desc = "Airport Drop";
				}
			}
			if ($this->booking->tripType == 14)
			{
				$desc = "Local (Point to Point)";
			}
			if ($this->booking->tripType == 15)
			{
				$desc = "Local Transfer";
				if ($this->booking->transferType == 1)
				{
					$desc = "Local(Pickup)";
				}
				if ($this->booking->transferType == 2)
				{
					$desc = "Local(Drop)";
				}
			}
		}
		return $desc;
	}

	public function getQuoteURL()
	{
		if ($this->booking->id > 0)
		{
			$hash	 = Yii::app()->shortHash->hash($this->booking->id);
			$params	 = ["booking/catQuotes", "lid" => $this->booking->id, "hash" => $hash];
		}
		else if(Yii::app()->user->isGuest && ($this->booking->bkg_agent_id == '' || $this->booking->bkg_agent_id == 0) && Yii::app()->session['_gz_rdata_skiplogin']!='')
		{
			$rdata = Yii::app()->session['_gz_rdata_skiplogin'];
			$params	 = ["booking/catQuotes", "rid" => md5($rdata)];
		}
		else
		{
			$params = $this->getCatQuotesURLParams();
		}

		return $params;
	}

	public function getTierURL()
	{
		if ($this->booking->id > 0)
		{
			$hash	 = Yii::app()->shortHash->hash($this->booking->id);
			$params	 = ["booking/tierQuotes", "cabcategory" => $this->booking->cab->categoryId, "lid" => $this->booking->id, "hash" => $hash];
		}
		else
		{
			$params = $this->getTierQuotesURLParams();
		}

		return $params;
	}

	public function getAddonsURL()
	{
		if ($this->booking->id > 0)
		{
			$hash	 = Yii::app()->shortHash->hash($this->booking->id);
			$params	 = ["booking/addons", "cabclass" => $this->booking->cab->cabCategory->id, "lid" => $this->booking->id, "hash" => $hash];
		}
		return $params;
	}

	public function getItineraryDesc()
	{
		$desc = "Itinerary";
		if ($this->booking != null && count($this->booking->routes) > 0)
		{
			$desc = date('D, jS M, h:i A', strtotime($this->booking->routes[0]->startDate . "" . $this->booking->routes[0]->startTime));
		}
		return $desc;
	}

	public function getCabServiceCategoryDesc()
	{
		$desc = "Cab Type";
		if ($this->booking->cab->categoryId != '')
		{
			$desc = VehicleCategory::model()->getNameById($this->booking->cab->categoryId);
		}	
		if($this->booking->cab->cabCategory->id>0)
		{
			$desc = SvcClassVhcCat::getCatrgoryLabel($this->booking->cab->cabCategory->id);
		}
		return $desc;
	}

	public function getCabServiceClassDesc()
	{
		$desc = "Cab Type";
		if ($this->booking->cab->id != '')
		{
			$this->booking->cab->setCabType($this->booking->cab->id);
		}
		if ($this->booking->cab->cabCategory->catClass != '')
		{
			$desc = $this->booking->cab->cabCategory->catClass;
		}

		return $desc;
	}

	public function getCabServiceAddonsDesc()
	{
		$desc = "Cab Type";
		if ($this->booking->cab->id != '')
		{
			$this->booking->cab->setCabType($this->booking->cab->id);
		}
		if ($this->booking->cab->cabCategory->catClass != '')
		{
			$desc = "Addons";
		}

		return $desc;
	}

	public function getServiceClassDesc()
	{
		$desc = "Service Tier";
		if ($this->booking->cabType > 0)
		{
			$cabCategory = new \Stub\common\CabCategory();
			$cabCategory->setData($this->booking->cabType);
			$desc		 = $cabCategory->catClass;
		}
		return $desc;
	}

	/** @param \Booking $model Description */
	public function setBookingModel($model)
	{
		$this->booking	 = \Stub\common\Booking::setModel($model);
		$bkgType		 = $model->bkg_booking_type;
		if (in_array($bkgType, [1, 2, 3, 5, 6]))
		{
			$this->tripCategory = 2;
		}
		else if ($this->tripCategory == '')
		{
			$this->tripCategory = 2;
		}
	}

	/** @return \Stub\common\ContactVerification */
	public function getContactObject()
	{
		return $this->contactVerifications[count($this->contactVerifications) - 1];
	}

	/** @return \Stub\common\ContactVerification */
	public function getContact($type, $value)
	{
		$objCttVerify = null;
		foreach ($this->contactVerifications as $contactVerification)
		{
			if ($contactVerification->type == $type && $contactVerification->value == $value)
			{
				$objCttVerify = $contactVerification;
				break;
			}
		}

		if ($objCttVerify == null)
		{
			$objCttVerify					 = \Stub\common\ContactVerification::create($type, $value);
			$this->contactVerifications[]	 = $objCttVerify;
		}
		return $objCttVerify;
	}

	public function clearContact($type = null, $value = null)
	{
		foreach ($this->contactVerifications as $i => $contactVerification)
		{
			if ($value == null || ($contactVerification->type == $type && $contactVerification->value == $value))
			{
				unset($this->contactVerifications[$i]);
			}
		}
	}

	public function getEncrptedData()
	{
		$data = json_encode(\Filter::removeNull($this));
		return \Filter::encrypt($data);
	}

	public static function decryptData($data)
	{
		$jsonData = \Filter::decrypt($data);
		Logger::trace($jsonData);
		$jsonMapper	 = new \JsonMapper();
		$jsonObj	 = \CJSON::decode($jsonData, false);
		$jsonObj	 = \Filter::removeNull($jsonObj);

		return $jsonObj;
	}

	public function updatePostData()
	{
		$_POST['rdata'] = $this->getEncrptedData();
	}

	public function getSelectedCategory()
	{
		
	}

	public function isSelectAvailable()
	{
		$success = false;
		if ($this->booking->cabType > 0 && $this->booking->cab->cabCategory->scvVehicleModel > 0)
		{
			$success = true;
		}
		return $success;
	}

	public function getItineraryURLParams()
	{
		$params = ["booking/itinerary", "bkgType" => $this->booking->tripType];
		if ($this->booking->transferType > 0)
		{
			$params["type"] = $this->booking->transferType;
		}
		$routes = $this->booking->routes;
		if (count($routes) > 0 && $routes[0]->source->code > 0)
		{
			$params["fcity"] = Cities::getAliasPath($routes[0]->source->code);
		}
		if (count($routes) > 0 && ($this->booking->transferType > 0 || isset($params["fcity"])) && $routes[count($routes) - 1]->destination->code > 0)
		{
			$params["tcity"] = Cities::getAliasPath($routes[count($routes) - 1]->destination->code);
		}
		return $params;
	}

	public function getRoutes()
	{
		$routes	 = $this->booking->routes;
		$ctr	 = 0;
		foreach ($routes as $key => $route)
		{
			$ctr = ($ctr + 1);
			$rut .= Cities::getName($route->source->code) . ' - ';
			if (count($routes) == $ctr)
			{
				$rut .= Cities::getName($route->destination->code);
			}
		}

		return $rut;
	}

	public function getCatQuotesURLParams()
	{
		$params = ["booking/catQuotes", "bkgType" => $this->booking->tripType];
		if ($this->booking->transferType > 0)
		{
			$params["type"] = $this->booking->transferType;
		}
		$routes = $this->booking->routes;
		if (count($routes) > 0 && $routes[0]->source->code > 0 && $this->booking->transferType != 2)
		{
			$params["fcity"] = Cities::getAliasPath($routes[0]->source->code);
		}


		if (count($routes) > 0 && (in_array($this->booking->tripType, [1, 2, 3]) || $this->booking->transferType == 2) && ($this->booking->transferType > 0 || isset($params["fcity"])) && $routes[count($routes) - 1]->destination->code > 0)
		{
			$params["tcity"] = Cities::getAliasPath($routes[count($routes) - 1]->destination->code);
		}
		return $params;
	}

	public function getTierQuotesURLParams()
	{
		$params = ["booking/tierQuotes", "cabcategory" => $this->booking->cab->categoryId, "bkgType" => $this->booking->tripType];

		if ($this->booking->transferType > 0)
		{
			$params["type"] = $this->booking->transferType;
		}
		$routes = $this->booking->routes;
		if (count($routes) > 0 && $routes[0]->source->code > 0 && $this->booking->transferType != 2)
		{
			$params["fcity"] = Cities::getAliasPath($routes[0]->source->code);
		}


		if (count($routes) > 0 && (in_array($this->booking->tripType, [1, 2, 3]) || $this->booking->transferType == 2) && ($this->booking->transferType > 0 || isset($params["fcity"])) && $routes[count($routes) - 1]->destination->code > 0)
		{
			$params["tcity"] = Cities::getAliasPath($routes[count($routes) - 1]->destination->code);
		}
		return $params;
	}

	public function getInfoURL()
	{
		$params = ["booking/address", "lid" => $this->booking->id, "hash" => Yii::app()->shortHash->hash($this->booking->id)];
		return $params;
	}

	public function getTravellerURL()
	{
		$params = ["booking/travellercontact", "lid" => $this->booking->id, "hash" => Yii::app()->shortHash->hash($this->booking->id)];
		return $params;
	}

	public function getTravellerURLKayak()
	{
		if ($this->booking->defLeadId == '' || $this->booking->defLeadId == 0)
		{
			$this->booking->defLeadId = $this->booking->id;
		}
		$params = ["booking/travellercontact", "lid" => $this->booking->id, "hash" => Yii::app()->shortHash->hash($this->booking->id), "dlid" => $this->booking->defLeadId, "dhash" => Yii::app()->shortHash->hash($this->booking->defLeadId)];
		return $params;
	}

	public function populateQuote($model = null)
	{
		if ($model == null)
		{
			$model = $this->booking->getLeadModel();
		}

		$applyPromo = true;

		$svcIds = SvcClassVhcCat::getIds();

		$quotData	 = Quote::populateFromModel($model, $svcIds, false, true, false, 0, $applyPromo);
		$response	 = new \Stub\booking\QuoteResponse();
		$response->setData($quotData);

		$this->quote = $response;
	}

	public function saveLead($userChk = true)
	{

		$model					 = $this->booking->getLeadModel();
		$userId					 = UserInfo::getUserId();
		$model->bkg_user_id		 = $userId;
		$model->bkg_user_device	 = UserLog::model()->getDevice();
		$model->bkg_platform	 = Booking::Platform_User;

		$cttModel = Contact::model()->getByUserId($userId);
		if ($this->booking->agentId == Config::get('Mobisign.partner.id') || $this->booking->agentId == Config::get('Kayak.partner.id'))
		{
			$model->bkg_user_id = '';
			goto skipusercontact;
		}
		if (($userId == '' || !$cttModel) && $model->bkg_contact_no == '' && $userChk == true)
		{
			return false;
		}
		if ($model->bkg_contact_no == '')
		{

			$objPhoneNumber = ContactPhone::getPrimaryNumber($cttModel->ctt_id);
			if ($objPhoneNumber != '')
			{
				$model->bkg_country_code = $objPhoneNumber->getCountryCode();
				$model->bkg_contact_no	 = $objPhoneNumber->getNationalNumber();
			}
		}
		if ($model->bkg_user_email == '')
		{
			$email					 = ContactEmail::getPrimaryEmail($cttModel->ctt_id);
			$model->bkg_user_email	 = $email;
		}
		skipusercontact:
		$routeDt				 = $model->setRoutes($model->bookingRoutes);
		$model->bkg_route_data	 = CJSON::encode($routeDt);

		if ($this->booking->addons != null)
		{
			$model->bkgAddonDetails[] = ['adn_type' => $this->booking->addons->type, 'adn_id' => $this->booking->addons->id, 'adn_value' => $this->booking->addons->charge];
		}

		if (($model->bkg_agent_id == \Config::get('Kayak.partner.id') || $this->agentId == \Config::get('Kayak.partner.id')) && $this->booking->returnDate == '' && $this->quote->estimatedDuration > 0)
		{
			$model->bkg_return_date = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[0]->brt_pickup_datetime . ' + ' . $this->quote->estimatedDuration . ' MINUTE'));
		}
        $model->bkg_user_name = $cttModel->ctt_first_name;
        $model->bkg_user_lname = $cttModel->ctt_last_name;
		$model->createLead();
		//unset(Yii::app()->request->cookies['gozo_agent_id']);
		Yii::app()->session->remove("_gz_rdata_skiplogin");
		BookingTemp::markDuplicateLead($model->bkg_id);
		BookingTemp::stopAutoAssignDuplicateQuote($model->bkg_id);
		$this->setBookingModel($model);
		return $model;
	}

	/**
	 * @param Stub\common\CabRate $rate1
	 * @param Stub\common\CabRate $rate2
	 */
	public function compareRates($rate1, $rate2)
	{
		$rate = $rate1;

		if ($rate1->fare->baseFare > $rate2->fare->baseFare)
		{
			$rate = $rate2;
		}
		if ($rate1->fare->baseFare <> $rate2->fare->baseFare)
		{
			goto end;
		}
		if ($rate1->cab->cabCategory->catRank > $rate2->cab->cabCategory->catRank)
		{
			$rate = $rate2;
		}

		end:
		return $rate;
	}
 

	public function categorizeQuote($tiersOnly = false)
	{
		$arrQuoteCat	 = [];
		$arrQuoteTier	 = [];
		foreach ($this->quote->cabRate as $key => $rate)
		{
			/** @var Stub\common\CabRate $rate */
			$cabId	 = $rate->cab->id;
			$catId	 = $rate->cab->cabCategory->scvVehicleId;
			$classId = $rate->cab->cabCategory->scvVehicleServiceClass;
			if($rate->cab->cabCategory->scvVehicleModel > 0 
				&& in_array($classId, [ServiceClass::CLASS_ECONOMIC, ServiceClass::CLASS_VLAUE_PLUS, ServiceClass::CLASS_VALUE_CNG])
				&& !in_array($catId, [5,6])){
				continue;
			}
			if (!isset($arrQuoteCat[$catId]))
			{
				$arrQuoteCat[$catId] = [];
			}

			if (!isset($arrQuoteCat[$catId][$classId]))
			{
				$arrQuoteCat[$catId][$classId] = [];
			}
			$arrQuoteCat[$catId][$classId][$cabId] = $rate; 
			if($tiersOnly == true && $rate->cab->cabCategory->scvVehicleServiceClass > 0){
				$arrQuoteTier[$classId] = $rate->cab->cabCategory;
			}
		}
		if($tiersOnly == true){
			return $arrQuoteTier;
		}
//		echo '<Pre>';
//		echo json_encode($arrQuoteCat);
//		exit;
		return $arrQuoteCat;
	}

	public function sortCategory()
	{
		/** @var CabRate $rate */ 
		$arrQuote	 = $this->categorizeQuote();
		$sortArray	 = [];
		foreach ($arrQuote as $catId => $catQuotes)
		{
			foreach ($catQuotes as $classId => $cabQuotes)
			{
				foreach ($cabQuotes as $cabId => $rate)
				{
					if (!isset($sortArray[$catId]) || $rate->fare->baseFare < $sortArray[$catId]->fare->baseFare)
					{
						$sortArray[$catId] = $rate;
					}
				}
			}
		}
		$arr = [];
		foreach ($sortArray as $key => $rate)
		{
			/** @var Stub\common\CabRate $rate */
			$key1 = str_pad($rate->fare->baseFare, 10, "0", STR_PAD_LEFT) . "_" . str_pad($rate->cab->cabCategory->catRank, 3, "0", STR_PAD_LEFT) . "_" . $key;

			$arr[$key1] = $rate;
		}

		ksort($arr);
		return $arr;
	}

	public function sortServiceTier($catId)
	{
		/** @var CabRate $rate */
		$arrQuote	 = $this->categorizeQuote();
		$catQuotes	 = $arrQuote[$catId];
		$sortArray	 = [];
		foreach ($catQuotes as $classId => $cabQuotes)
		{
			foreach ($cabQuotes as $cabId => $rate)
			{
				if (!isset($sortArray[$classId]) || $rate->fare->baseFare < $sortArray[$classId]->fare->baseFare)
				{
					$sortArray[$classId] = $rate;
				}
			}
		}
		$arr = [];
		foreach ($sortArray as $key => $rate)
		{
			/** @var Stub\common\CabRate $rate */
			$arr[str_pad($rate->fare->baseFare, 10, "0", STR_PAD_LEFT) . "_" . str_pad($rate->cab->cabCategory->catClassRank, "0", STR_PAD_LEFT) . "_" . $key] = $rate;
		}

		ksort($arr);

		return $arr;
	}

	public function sortModels($catId, $classId)
	{
		/** @var CabRate $rate */
		$arrQuote	 = $this->categorizeQuote();
		$cabQuotes	 = $arrQuote[$catId][$classId];
		$arr		 = [];
		foreach ($cabQuotes as $key => $rate)
		{
			/** @var Stub\common\CabRate $rate */
			$arr[str_pad($rate->fare->baseFare, 10, "0", STR_PAD_LEFT) . "_" . $key] = $rate;
		}

		ksort($arr);

		return $arr;
	}

	public function getOTPMessage()
	{
		$msg = [];
		foreach ($this->contactVerifications as $contactVerifications)
		{
			if ($contactVerifications->otpValidTill > time())
			{
				$type	 = ($contactVerifications->type == Stub\common\ContactVerification::TYPE_EMAIL) ? "Email:" : "Phone:";
				$msg[]	 = "{$type} {$contactVerifications->value}";
			}
		}

		return implode(" and ", $msg);
	}
}
