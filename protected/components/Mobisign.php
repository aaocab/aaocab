<?php

class Mobisign extends CComponent
{

	function initiate($request)
	{
		try
		{
			$url					 = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . Yii::app()->createUrl('site/unauthorized');
			$apiKey					 = $request->getParam('apikey');
			$agentModel				 = Agents::model()->findByApiKey($apiKey);
			//$_SERVER['HTTP_REFERER'] = "http://localhost:100/"; //remove this before commit
			//if ($_SERVER['HTTP_REFERER'] == '' || $agentModel == null)
			if($agentModel == null)
			{
				header("Location: " . $url);
				Yii::app()->end();
			}
			$partnerSettings = PartnerSettings::getValueById($agentModel['agt_id']);
			$referrals		 = $partnerSettings['pts_referral_urls'];
			if ($referrals == null)
			{
				goto mapReq;
			}
			$arrReferrals = json_decode($referrals);
			if (!in_array($_SERVER['HTTP_REFERER'], $arrReferrals))
			{
				header("Location: " . $url);
				Yii::app()->end();
			}
			mapReq:
			$agtId	 = $agentModel['agt_id'];
			$model	 = self::map($request, $agtId);
			return $model;
		}
		catch (Exception $ex)
		{
			$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . Yii::app()->createUrl('site/badrequest');
			header("Location: " . $url);
			Yii::app()->end();
		}
	}
		function map($request, $agtId = '')
		{
			$model						 = new BookingTemp();
			$model->bkg_booking_type	 = 12; //DEPARTURE = Drop to airport , ARRIVAL = Pickup from airport

			if($request->getParam('category') == 'Outstation')
			{
				$model->bkg_booking_type	 = 1; 
				goto skipairport;
			}

			if($request->getParam('category') == 'Rental')
			{
				$model->bkg_booking_type	 = 9; 
				goto skipnew;
			}

			if($request->getParam('triptype') == 'ARRIVAL')
			{
                $model->bkg_transfer_type = 1;
				$pickupDateTime = $request->getParam('pickuptime');
				
				if($pickupDateTime=="")
				{
					$arrivalDateTimeStr = trim($request->getParam('STA'),'=');
					$arrivalDateTime = explode("T", $arrivalDateTimeStr);
					$arrivalDate = $arrivalDateTime[0];
					$arrivalTime = $arrivalDateTime[1];
					$PickupTime = date("h:i A", strtotime($arrivalTime));
					$PickupTime = DateTimeFormat::TimeToSQLTime($PickupTime);
					$pickupDateTime = DateTimeFormat::DatePickerToDate($arrivalDate)." ".$PickupTime;
					$model->bkg_pickup_date = date('Y-m-d H:i:s', strtotime($pickupDateTime . '+ 15 minute'));
				}
				else
				{
					$model->bkg_pickup_date = date('Y-m-d H:i:s', strtotime($pickupDateTime));
				}
			}

			if($request->getParam('triptype') == 'DEPARTURE')
			{
                $model->bkg_transfer_type = 2;
				$pickupDateTime = $request->getParam('pickuptime');
				if($pickupDateTime=="")
				{
					$arrivalDateTimeStr = trim($request->getParam('STD'),'=');
					$arrivalDateTime = explode("T", $arrivalDateTimeStr);
					$arrivalDate = $arrivalDateTime[0];
					$arrivalTime = $arrivalDateTime[1];
					$PickupTime = date("h:i A", strtotime($arrivalTime));
					$PickupTime = DateTimeFormat::TimeToSQLTime($PickupTime);
					$pickupDateTime = DateTimeFormat::DatePickerToDate($arrivalDate)." ".$PickupTime;
					$model->bkg_pickup_date = date('Y-m-d H:i:s', strtotime($pickupDateTime . '- 180 minute'));
				}
				else
				{
					$model->bkg_pickup_date = date('Y-m-d H:i:s', strtotime($pickupDateTime));
				}
				
			}

			skipairport:
			if($request->getParam('category') == 'Outstation')
			{
				$pickupDateTime = $request->getParam('pickuptime');
				$model->bkg_pickup_date = date('Y-m-d H:i:s', strtotime($pickupDateTime));
			}

			skipnew:
			if($request->getParam('category') == 'Rental')
			{
				$pickupDateTime = $request->getParam('pickuptime');
				$model->bkg_pickup_date = date('Y-m-d H:i:s', strtotime($pickupDateTime));
			}
			
			$model->bkg_agent_id		 = $agtId; //34928;
			$model->bkg_contact_no = $request->getParam('ph');
			$model->bkg_user_email = $request->getParam('email');
			$name = $request->getParam('name');
			$nameArr = explode("/", $name);
			$model->bkg_user_name = $nameArr[0];
			$model->bkg_user_lname = $nameArr[1];
			$pickuplatlong  = $request->getParam('pickuplatlong');
			$pickuplatlongArr = explode(",", $pickuplatlong);
			$fcityLat = $pickuplatlongArr[0];
			$fcityLong = $pickuplatlongArr[1];
			$pickupLoc = $request->getParam('pickloc');

			$droplatlong  = $request->getParam('droplatlong');
			if($droplatlong==""){
				$droplatlong  = $request->getParam('Droplatlong');
			}
			$droplatlongArr = explode(",", $droplatlong);
			$tcityLat = $droplatlongArr[0];
			$tcityLong = $droplatlongArr[1];
			$dropLoc = $request->getParam('droploc');
			if($dropLoc==""){
			$dropLoc = $request->getParam('Droploc');
			}

			if($request->getParam('category') == 'Rental')
			{
				$tcityLat = $fcityLat;
				$tcityLong = $fcityLong; 
				$dropLoc = $pickupLoc;
			}
			self::mapRoutes($model,$fcityLat,$fcityLong,$tcityLat,$tcityLong,$pickupLoc,$dropLoc);
			return $model;
		}

		function mapRoutes(&$model,$flat,$flong,$tlat,$tlong,$pickupLoc = '',$dropLoc = '')
		{
			/* @var $model BookingTemp*/
			$fcityId = Cities::getCityByLatLng($flat,$flong);
			$tcityId = Cities::getCityByLatLng($tlat,$tlong);
//			if($fcityId==null && $pickupLoc!='')
//			{
//				$placeObj = Stub\common\Place::init($flat, $flong,'', $pickupLoc);
//				$city = Cities::getByAddress($placeObj);
//				$fcityId = $city->cty_id;
//			}
			if($fcityId == null)
			{
				$pickupLoc = $flat = $flong = null;
			}
//			if($tcityId==null && $dropLoc!='')
//			{
//				$placeObj = Stub\common\Place::init($tlat, $tlong,'', $dropLoc);
//				$city = Cities::getByAddress($placeObj);
//				$tcityId = $city->cty_id;
//			}
			if($tcityId==null)
			{
				$dropLoc = $tlat = $tlong = null;
			}
			$model->bkg_from_city_id			 = $fcityId;
			$model->bkg_to_city_id				 = $tcityId;
			$model->bkg_pickup_lat = $flat;
			$model->bkg_pickup_long = $flong;
			if($model->bkg_transfer_type == 1)
			{
				if($fcityId!=null)
				{
				  $pickupLoc = Cities::getColumnValue("cty_garage_address", $fcityId);
				}
				$model->bkg_pickup_address  = $pickupLoc;
				$model->bkg_drop_address  = $dropLoc;
			}
			else
			{
				if($tcityId!='' && $dropLoc=='')
				{
					$dropLoc = Cities::getColumnValue("cty_garage_address", $tcityId);
				}
				$model->bkg_pickup_address  = $pickupLoc;
				$model->bkg_drop_address  = $dropLoc;
			}
			$params[0]['brt_from_city_id']		 = $model->bkg_from_city_id;
			$params[0]['brt_to_city_id']		 = $model->bkg_to_city_id;
			$params[0]['brt_from_latitude']		 = $model->bkg_pickup_lat;
			$params[0]['brt_from_longitude']	 = $model->bkg_pickup_long;
			$params[0]['brt_to_latitude']		 = $tlat;
			$params[0]['brt_to_longitude']		 = $tlong;
			$params[0]['brt_pickup_datetime']	 = $model->bkg_pickup_date;
			$params[0]['brt_from_location']		 = $model->bkg_pickup_address;
			$params[0]['brt_to_location']		 = $model->bkg_drop_address;
			$model->bookingRoutes = $model->setRoutes($params);
		}

}
