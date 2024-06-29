<?php

class SystemCommand extends BaseCommand
{

	public function actionRouteDistanceTime($start = 0)
	{

		$sql	 = "SELECT `rut_id`, rut_from_city_id, rut_to_city_id, a.cty_lat as scty_lat, a.cty_long as scty_long, b.cty_lat as dcty_lat, b.cty_long as dcty_long, 
							CalcDistance(a.cty_lat, a.cty_long, b.cty_lat, b.cty_long) as distance
					FROM route 
					INNER JOIN cities a ON a.cty_id = `rut_from_city_id` AND a.cty_active=1  AND a.cty_service_active=1
					INNER JOIN cities b ON b.cty_id = `rut_to_city_id` AND b.cty_active=1 AND b.cty_service_active=1  AND b.cty_id IN (SELECT topCities.aat_from_city FROM topCities WHERE topCities.cnt>300) 
					WHERE rut_active =2 AND rut_id>1  AND route.rut_actual_distance IS NULL AND a.cty_id<>b.cty_id
                        HAVING distance < 30 AND distance > 0 ORDER BY distance ASC LIMIT $start, 2000
";
		$result	 = DBUtil::query($sql);
		$mem1	 = 21;
		exit;
		foreach ($result as $row)
		{
			try
			{
				$result	 = Route::model()->populate($row['rut_from_city_id'], $row['rut_to_city_id']);
				$rModel	 = $result['model'];
				Logger::writeToConsole("\n{$row["rut_id"]} : {$rModel->rut_id} [{$rModel->rut_name}] - {$row["distance"]} : {$rModel->rut_actual_distance}\n");
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionCitieslatlong()
	{
		$sql = "SELECT cty_lat, cty_long, cty_id, cty_place_id, cty_display_name  FROM cities 
				WHERE `cty_bounds` IS NULL AND `cty_active` = 1 AND `cty_service_active` = 1
			";
		$res = DBUtil::query($sql);
		Logger::writeToConsole("Total: " . $res->getRowCount());
		foreach ($res as $row)
		{
			$placeObj			 = Stub\common\Place::init($row["cty_lat"], $row["cty_long"]);
			$placeObj->place_id	 = $row["cty_place_id"];
			$objects			 = GoogleMapAPI::getObjectByPlaceId($row["cty_place_id"]);
			$googleObjects		 = $objects->results;
			$models				 = [];
			$model				 = null;
			foreach ($googleObjects as $obj)
			{
				$placeObj1	 = \Stub\common\Place::initGoogePlace($obj);
				$obj		 = LatLong::addPlace($placeObj1, null, $row["cty_id"]);
			}
		}

		echo json_encode($obj);
	}

	public function validateCityName($obj, $model)
	{
		$shortName	 = strtolower(trim($obj->address_components[0]->short_name));
		$longName	 = strtolower(trim($obj->address_components[0]->long_name));
		$name		 = strtolower(trim($model->cty_name));
		$aliasName	 = strtolower(trim($model->cty_alias_name));
		if ($longName == "Unnamed Road")
		{
			$shortName	 = strtolower(trim($obj->address_components[1]->short_name));
			$longName	 = strtolower(trim($obj->address_components[1]->long_name));
		}

		if ($shortName == $name || $longName == $name || (($longName == $aliasName || $shortName == $aliasName) && $aliasName != ''))
		{
			return true;
		}
		return false;
	}

	public function actionAddGeoCities()
	{
		return;
		$GLOBALS["citiesMismatched"] = [];
		$sql						 = "SELECT DISTINCT CONCAT(gd1.gdt_local_name,', ', gd2.gdt_local_name, ', ', gd3.gdt_local_name, ', India') as gdtName, gd1.gdt_area, gd2.gdt_area FROM cities
				INNER JOIN geo_data gd1 ON cities.cty_geo_id=gd1.gdt_id
				INNER JOIN geo_data gd2 ON gd2.gdt_geo_id=gd1.gdt_parent_geo_id AND gd2.gdt_area>400
				INNER JOIN geo_data gd3 ON gd3.gdt_geo_id=gd2.gdt_parent_geo_id
				WHERE gd1.gdt_city_id IS NULL AND gd2.gdt_city_id IS NULL ";
		$res						 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($res as $row)
		{
			echo "\nName: {$row["gdtName"]} - ";
			$data = GoogleMapAPI::getObjectByAddress($row["gdtName"]);

			$placeObj	 = Stub\common\Place::initGoogePlace($data->results[0]);
			$objs		 = GoogleMapAPI::getLocalities($data);
			if (count($objs) == 0)
			{
				$data	 = GoogleMapAPI::getObjectByLatLong($placeObj->coordinates->latitude, $placeObj->coordinates->longitude);
				$objs	 = GoogleMapAPI::getLocalities($data);
			}

			if (count($objs) == 0)
			{
				continue;
			}

			$stateModel = States::getByGeoCoordinates($placeObj->coordinates->latitude, $placeObj->coordinates->longitude);
			foreach ($objs as $obj)
			{
				$placeObj	 = \Stub\common\Place::initGoogePlace($obj);
				$model		 = Cities::create($placeObj, $stateModel->stt_id);
				if ($model)
				{
					echo "\n\t" . json_encode($model->getAttributes());
				}
			}
			echo "\n=============================";
		}
	}

	public function actionUpdateLatLong()
	{
		return;
		$sql = " SELECT ll.* FROM lat_long ll
					INNER JOIN cities c ON ll.ltg_city_id=c.cty_id
					INNER JOIN geo_data gdt ON c.cty_geo_id=gdt_id AND NOT ST_CONTAINS(gdt_polygon, POINT(ll.ltg_long, ll.ltg_lat))
			";
		$res = DBUtil::query($sql, DBUtil::SDB());
		echo "Records Retrieved...\n";

		$i = 0;
		foreach ($res as $row)
		{
			$i++;
			$ltgModel	 = LatLong::model()->findByPk($row['ltg_id']);
			$placeObj	 = Stub\common\Place::init($ltgModel->ltg_lat, $ltgModel->ltg_long);
			$ctyModel	 = Cities::getByGeoBounds($placeObj);
			if ($ctyModel)
			{
				if ($ctyModel->cty_id != $ltgModel->ltg_city_id)
				{
					echo "{$ltgModel->ltg_id}::{$ltgModel->ltg_city_id}={$ctyModel->cty_id}\t";
				}
				$ltgModel->ltg_city_id	 = $ctyModel->cty_id;
				$ltgModel->ltg_review	 = $ctyModel->is_partial;
				$ltgModel->save();
			}
		}
	}

	public function actionUpdateDistance()
	{
		$sql = "SELECT DISTINCT fc.cty_lat as fcLat, fc.cty_long as fcLong, tc.cty_lat as tcLat, tc.cty_long as tcLong, rut_id, rut_name FROM `route` 
				INNER JOIN cities fc ON fc.cty_id=route.rut_from_city_id AND fc.cty_active=1 AND fc.cty_service_active=1
				INNER JOIN cities tc ON tc.cty_id=route.rut_to_city_id AND tc.cty_active=1 AND tc.cty_service_active=1
				WHERE route.rut_active=1 AND rut_estm_distance=0 AND fc.cty_id<>tc.cty_id AND CalcDistance(fc.cty_lat, fc.cty_long, tc.cty_lat, tc.cty_long)>1";

		$res = DBUtil::query($sql, DBUtil::SDB());
		foreach ($res as $row)
		{
			$row1 = DistanceMatrix::findNearest($row["fcLat"], $row["fcLong"], $row["tcLat"], $row["tcLong"]);

			if ($row1)
			{
				$dModel			 = DistanceMatrix::model()->findByPk($row1['dmx_id']);
				$srcLtLngModel	 = LatLong::model()->findByPk($dModel->dmx_source_ltg_id);
				$dstLtLngModel	 = LatLong::model()->findByPk($dModel->dmx_destination_ltg_id);
			}
			else
			{
				$dModel			 = new DistanceMatrix();
				$srcLtLngModel	 = LatLong::model()->getDetailsByPlace(\Stub\common\Place::init($row["fcLat"], $row["fcLong"]));
				$dstLtLngModel	 = LatLong::model()->getDetailsByPlace(\Stub\common\Place::init($row["tcLat"], $row["tcLong"]));
			}

			try
			{
				$result = GoogleMapAPI::getInstance()->getDrivingDistancebyLatLong($srcLtLngModel->ltg_lat, $srcLtLngModel->ltg_long, $dstLtLngModel->ltg_lat, $dstLtLngModel->ltg_long);
				if ($result["success"])
				{
					Logger::info("Distance matrix fetched via Google API");

					$dModel->dmx_source_ltg_id		 = $srcLtLngModel->ltg_id;
					$dModel->dmx_destination_ltg_id	 = $dstLtLngModel->ltg_id;
					$dModel->dmx_distance			 = $result['distance'][0]['dist'];
					$dModel->dmx_duration			 = $result['distance'][0]['time'];
					if (!$dModel->save())
					{
						echo "\nFailed: {$model->dmx_id}::{$row["rut_name"]} == " . json_encode($model->getErrors());

						throw new Exception("Error adding distance and time.");
					}
				}
			}
			catch (Exception $e)
			{
				echo "\nFailed: {$dModel->dmx_id}::{$row["rut_name"]} == {$e->getMessage()}";
				$dModel->dmx_active = 2;
				$dModel->save();
			}
		}
	}

	public function actionCitiesLatLongAddress()
	{
		$sql = "SELECT ll.*, CalcDistance(ll.ltg_lat, ll.ltg_long, c.cty_lat, c.cty_long) as distance FROM lat_long ll
				INNER JOIN cities c ON ll.ltg_city_id=c.cty_id AND c.cty_service_active=0 ";
		$res = DBUtil::query($sql);
		foreach ($res as $row)
		{
			$placeObj			 = \Stub\common\Place::init($row["ltg_lat"], $row["ltg_long"]);
			$placeObj->address	 = $row["ltg_locality_address"];
			$ctyModel			 = Cities::getByNearestBound($placeObj);
			if ($ctyModel && $ctyModel->cty_id != $row["ltg_city_id"] && $ctyModel->is_partial === 0)
			{
				$ltgModel				 = LatLong::model()->findByPk($row["ltg_id"]);
				$ltgModel->ltg_city_id	 = $ctyModel->cty_id;
				$ltgModel->save();
				Logger::info("\n********************************************************************************\n");
				Logger::info(json_encode($row));
				Logger::info("\n\nctyId: {$ctyModel->cty_id}, type: {$ctyModel->cty_types}, Name: {$ctyModel->cty_display_name}, Distance {$ctyModel->distance} | {$row["distance"]}");
				Logger::info("\n*********************************************************************************\n");
			}
			else if ($ctyModel->cty_id != $row["ltg_city_id"])
			{
//				Logger::info("\n--------------------------------------------------------------------------------\n");
//				Logger::info(json_encode($row));
//				if($ctyModel)
//				{
//					Logger::info("\n\nctyId: {$ctyModel->cty_id}, type: {$ctyModel->cty_types}, Name: {$ctyModel->cty_display_name}");
//				}
//				Logger::info("\n--------------------------------------------------------------------------------\n");
			}
		}
	}

	public function actionBookingDistanceTime()
	{
		$sql	 = "SELECT `bkg_id`,`bkg_from_city_id`,a.cty_name as fromcity,a.cty_state_id as fromstateid,"
				. "c.stt_name as fromstate, `bkg_to_city_id`,b.cty_name as tocity,b.cty_state_id as tostateid,"
				. "d.stt_name as tostate FROM `booking`"
				. "  JOIN cities a ON a.cty_id = `bkg_from_city_id` AND a.cty_active=1 "
				. " JOIN cities b ON b.cty_id = `bkg_to_city_id` AND b.cty_active=1 "
				. " JOIN states c ON a.cty_state_id= c.stt_id "
				. " JOIN states d ON b.cty_state_id= d.stt_id "
				. "WHERE `bkg_booking_type`=1 AND (`bkg_trip_distance` LIKE '%km%' OR `bkg_trip_distance`='' OR  `bkg_trip_distance` IS NULL)";
		$result	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($result as $value)
		{

			$fromcity		 = $value['fromcity'];
			$fromstate		 = $value['fromstate'];
			$fromAddress	 = $fromcity . ' ' . $fromstate;
			$formattedfrom	 = str_replace(' ', '+', $fromAddress);
			$fromgeocode	 = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyAPAIERlYgkZXp-6ANUI_b5nrhnit0fX_U&address=' . $formattedfrom . '&sensor=false');
			$arrfrom		 = json_decode($fromgeocode);
			$from_lat_long	 = $arrfrom->results[0]->geometry->location->lat . "," . $arrfrom->results[0]->geometry->location->lng;
			$tocity			 = $value['tocity'];
			$tostate		 = $value['tostate'];
			$toAddress		 = $tocity . ' ' . $tostate;
			$formattedto	 = str_replace(' ', '+', $toAddress);
			$togeocode		 = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyAPAIERlYgkZXp-6ANUI_b5nrhnit0fX_U&address=' . $formattedto . '&sensor=false');
			$arrto			 = json_decode($togeocode);
			$to_lat_long	 = $arrto->results[0]->geometry->location->lat . "," . $arrto->results[0]->geometry->location->lng;

			$geocodeFrom	 = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?key=AIzaSyAPAIERlYgkZXp-6ANUI_b5nrhnit0fX_U&origins=' . $from_lat_long . '&destinations=' . $to_lat_long . '&sensor=false&units=metric&mode=driving');
			$arr_distance	 = json_decode($geocodeFrom);

			$distance		 = $arr_distance->rows[0]->elements[0]->distance->value;
			$maindistance	 = round($distance / 1000);
			$duration		 = $arr_distance->rows[0]->elements[0]->duration->value;
			$mainduration	 = round($duration / 60);

			$rutupdate						 = Booking::model()->findByPk($value['bkg_id']);
			$rutupdate->bkg_trip_distance	 = $maindistance;
			$rutupdate->bkg_trip_duration	 = $mainduration;
			$rutupdate->update();
		}
	}

	public function actionUpdatevendor($interval = 30)
	{
		Logger::create("command.system.updateVendor start", CLogger::LEVEL_PROFILE);
		$vndId	 = 0;
		$params	 = ["interval" => $interval];
		$sql	 = "SELECT DISTINCT(vnd_id) vnd_id FROM (
						SELECT vnd.vnd_id as vnd_id 
						FROM `booking` bkg 
						INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id 
						INNER JOIN vendors vnd ON vnd.vnd_id = bcb.bcb_vendor_id AND vnd.vnd_active = 1 
						WHERE 1 AND bkg.bkg_status IN (6,7,9) AND bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL :interval DAY) 
						UNION 
						SELECT vnd.vnd_id as vnd_id 
						FROM `booking` bkg 
						INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id 
						INNER JOIN vendors vnd ON vnd.vnd_id = bcb.bcb_vendor_id AND vnd.vnd_active = 1 
						INNER JOIN ratings rtg ON rtg.rtg_booking_id = bkg.bkg_id 
						WHERE rtg.rtg_customer_overall > 0 AND rtg.rtg_customer_date >= DATE_SUB(NOW(), INTERVAL :interval DAY) 
						AND bkg.bkg_status IN (6,7,9) 
						UNION 
						SELECT apt_entity_id as vnd_id 
						FROM app_tokens 
						WHERE apt_user_type = 2 AND (apt_date >= DATE_SUB(NOW(), INTERVAL :interval DAY) OR apt_last_login >= DATE_SUB(NOW(), INTERVAL :interval DAY) OR apt_logout >= DATE_SUB(NOW(), INTERVAL :interval DAY))
					) a";
		$result	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		foreach ($result as $res)
		{
			$vndId = $res['vnd_id'];
			Vendors::model()->updateDetails($vndId);
			Logger::writeToConsole("VendorID: " . $vndId);
		}
	}

	public function actionUpdateInventoryStats()
	{

		Vendors::updateInventoryStats();
		Drivers::updateInventoryStats();

		//Vehicles::updateInventoryStats();
	}

	public function actionUpdatedriver($interval = 30)
	{
		Logger::create("command.system.updateDriver start", CLogger::LEVEL_PROFILE);
		$drvId	 = 0;
		$params	 = ["interval" => $interval];
		$sql	 = "SELECT DISTINCT(drv_id) drv_id FROM (
						SELECT drv.drv_id as drv_id 
						FROM `booking` bkg 
						INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id 
						INNER JOIN drivers drv ON drv.drv_id = bcb.bcb_driver_id AND drv.drv_active = 1 
						WHERE 1 AND bkg.bkg_status IN (6,7,9) AND bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL :interval DAY) 
						UNION 
						SELECT drv.drv_id as drv_id 
						FROM `booking` bkg 
						INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id 
						INNER JOIN drivers drv ON drv.drv_id = bcb.bcb_driver_id AND drv.drv_active = 1 
						INNER JOIN ratings rtg ON rtg.rtg_booking_id = bkg.bkg_id 
						WHERE 1 AND bkg.bkg_status IN (6,7,9) AND rtg.rtg_customer_driver > 0 AND rtg.rtg_customer_date >= DATE_SUB(NOW(), INTERVAL :interval DAY) 
						UNION 
						SELECT apt_entity_id as drv_id 
						FROM app_tokens 
						WHERE apt_user_type = 5 AND (apt_date >= DATE_SUB(NOW(), INTERVAL :interval DAY) OR apt_last_login >= DATE_SUB(NOW(), INTERVAL :interval DAY) OR apt_logout >= DATE_SUB(NOW(), INTERVAL :interval DAY)) 
					) a";
		$result	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		foreach ($result as $res)
		{
			$drvId = $res['drv_id'];
			Drivers::model()->updateDetails($drvId);
			Logger::writeToConsole("DriverID: " . $drvId);
		}
	}

	public function actionUpdatevehicle()
	{
		Logger::create("command.system.updateVehicle start", CLogger::LEVEL_PROFILE);
		$vhc_id = 0;
		Vehicles::model()->updateDetails($vhc_id);
		Logger::create("command.system.updateVehicle end", CLogger::LEVEL_PROFILE);
	}

	public function actionUpdateCustomer()
	{
		Logger::create("command.system.updateCustomer start", CLogger::LEVEL_PROFILE);
		Users::model()->updateDetails();
		//UserStats::updateStats();
		Logger::create("command.system.updateCustomer end", CLogger::LEVEL_PROFILE);
	}

	public function actionUpdatecities()
	{
		Cities::model()->updateSourceCities();
	}

	public function actionRefcron()
	{

		$model	 = Users::model()->findAll(['condition' => "usr_email NOT LIKE '%aaocab.in%' AND usr_refer_code IS NULL"]);
		$sum	 = 0;
		foreach ($model as $modelUser)
		{
			$modelUser->scenario = 'refcode';
			if ($modelUser->usr_refer_code != '')
			{
				$refCode = $modelUser->usr_refer_code;
			}
			else
			{
				if ($modelUser->usr_name != null)
				{
					$fname	 = preg_replace('/[^a-zA-Z0-9]/', '', $modelUser->usr_name);
					$refCode = strtoupper($fname . rand(100, 999)); //Yii::app()->shortHash->hash($userId, 6);        
				}
				else
				{
					$refCode = strtoupper(Yii::app()->shortHash->hash($modelUser->user_id, 6));
				}
				$modelUser->usr_refer_code = $refCode;
				if ($modelUser->validate())
				{
					if (!$modelUser->update())
					{
						$errors = $modelUser->getErrors();
						echo 'error_update';
					}
					else
					{
						echo 'success<br>';
						echo $sum = $sum + 1;
					}
				}
				else
				{
					print_r($modelUser->getErrors());
				}
			}
		}
	}

	public function actionSendUserNotification()
	{
		$query			 = "SELECT ntf_id, ntf_type from notification WHERE ntf_status = 0";
		$notifications	 = Yii::app()->db->createCommand($query)->queryAll();
		foreach ($notifications as $notification)
		{
			$users = Users::model()->getUsers($notification['ntf_type']);
			foreach ($users as $user)
			{
				$unfModel				 = new UserNotification();
				$unfModel->unf_user_id	 = $user['user_id'];
				$unfModel->unf_ntf_id	 = $notification['ntf_id'];
				$unfModel->save();
			}
			$qry = "UPDATE notification set ntf_status = 1 WHERE ntf_id = " . $notification['ntf_id'];
			Yii::app()->db->createCommand($qry)->execute();
		}
		$sql	 = "SELECT ntf_title as title, ntf_message as message, unf_user_id as user_id, unf_ntf_id as nft_id, ntf_message_type, unf_id from 
			        user_notification  LEFT JOIN notification ON ntf_id = unf_ntf_id
                    WHERE unf_status = 0";
		$rows	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($rows as $row)
		{
			$notificationId	 = substr(round(microtime(true) * 1000), -5);
			$payLoadData	 = ['UserId' => $row['user_id'], 'NtfId' => $row['nft_id'], 'MsgType' => $row['ntf_message_type'], 'EventCode' => Booking::CODE_CONSUMER_NOTIFICATION];
			$success		 = AppTokens::model()->notifyConsumer($row['user_id'], $payLoadData, $notificationId, $row['message'], $row['title']);
			if ($success)
			{
				$model				 = UserNotification::model()->findByPk($row['unf_id']);
				$model->unf_status	 = 1;
				$model->save();
			}
		}
	}

	public function actionProcessMails()
	{
		$check = Filter::checkProcess("system processMails");
		if (!$check)
		{
			return;
		}
		echo ":: System-ProcessMails Started";

		Logger::create("command.system.processMails start", CLogger::LEVEL_PROFILE);
		$emailLog = new EmailLog();
		$emailLog->sentInactiveMails(EmailLog::SEND_SERVICE_EMAIL, 100);
		$emailLog->sentInactiveMails(EmailLog::SEND_ACCOUNT_EMAIL, 100);
		$emailLog->sentInactiveMails(EmailLog::SEND_AGENT_EMAIL, 50);
		$emailLog->sentInactiveMails(EmailLog::SEND_CONSUMER_BATCH_EMAIL, 200);
		$emailLog->sentInactiveMails(EmailLog::SEND_VENDOR_BATCH_EMAIL, 100);
		$emailLog->sentInactiveMails(EmailLog::SEND_METERDOWN_EMAIL, 50);
		Logger::create("command.system.processMails end", CLogger::LEVEL_PROFILE);
		echo ":: System-ProcessMails End";
	}

	public function actionProcessSms()
	{
		$check = Filter::checkProcess("system processSms");
		if (!$check)
		{
			return;
		}

		Logger::create("command.system.processSms start", CLogger::LEVEL_PROFILE);
		$smsLog = new SmsLog();
		$smsLog->sentInactiveSms();
		Logger::create("command.system.processSms end", CLogger::LEVEL_PROFILE);
	}

	//function to execute all sitemapxml
	public function actionAllSitemapXml()
	{
		echo Filter::getExecutionTime() . " actionPopulatePopularSitemapRute Start<br> ";
		$this->actionPopulatePopularSitemapRute();
		echo Filter::getExecutionTime() . " actionPopulatePopularSitemapRute Ends <br> ";

		echo Filter::getExecutionTime() . " actionSiteXml Start<br> ";
		$this->actionSiteXml(); //show toproute sitexml
		echo Filter::getExecutionTime() . " actionSiteXml Ends<br> ";

		echo Filter::getExecutionTime() . " actionPopulateOtherSitemapState Start<br> ";
		$this->actionPopulateOtherSitemapState(); //create all route sitemap
		echo Filter::getExecutionTime() . " actionPopulateOtherSitemapState Ends<br> ";

		echo Filter::getExecutionTime() . " actionPackageSitemapXml Start<br> ";
		#$this->actionPackageSitemapXml(); //create all package sitemap
		echo Filter::getExecutionTime() . " actionPackageSitemapXml Ends<br> ";

		echo Filter::getExecutionTime() . " actionCarRentalSiteMap Start<br> ";
		$this->actionCarRentalSiteMapXml();
		echo Filter::getExecutionTime() . " actionCarRentalSiteMap End<br> ";
		echo "Stooped";
	}

	public function actionSiteXml()
	{
		$list	 = [];
		$this->populateSitemap($list);
		$count	 = count($list);
		//die();
		$length	 = 45000; // real data
		for ($i = 1; $i <= ceil($count / $length); $i++)
		{
			$offset	 = ($i - 1) * $length;
			$list1	 = array_slice($list, $offset, $length);
			$str	 = '<?xml version="1.0" encoding="UTF-8"?>';
			$str	 .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'  xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'  xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9  http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'>";
			for ($k = 0; $k < count($list1); $k++)
			{
				$str .= "<url><loc>" . $list1[$k]['loc'] . "</loc><changefreq>" . $list1[$k]['frequency'] . "</changefreq> <priority>" . $list1[$k]['priority'] . "</priority></url>";
			}
			$str	 .= "</urlset>";
			$j		 = ($i == 1) ? "" : $i - 1;
			$handle	 = fopen(PUBLIC_PATH . DIRECTORY_SEPARATOR . 'sitemaps' . DIRECTORY_SEPARATOR . "sitemap{$j}.xml", 'w');
			fwrite($handle, $str);
			fclose($handle);
		}
		Logger::create("command.system.siteXml end", CLogger::LEVEL_PROFILE);
	}

	public function actionPackageSitemapXml()
	{
		Logger::create("command.system.packageXml start", CLogger::LEVEL_PROFILE);
		$sitemap_folder	 = "sitemaps";
		$site_url		 = "http://www.aaocab.com/";
		$myfile			 = fopen("robots.txt", "a") or die("Unable to open file!");
		$txt			 .= "SITEMAP: " . $site_url . $sitemap_folder . "/sitemap_package.xml" . PHP_EOL;
		fwrite($myfile, $txt);
		fclose($myfile);
		$list			 = [];
		$packages		 = Package::model()->fetchActivePackageList();
		foreach ($packages as $row)
		{
			$list[] = $this->createSitemapEntry('packages/' . $row['pck_url'], '', 'daily', '0.9');
		}
		$data	 = $this->renderFile(Yii::getPathOfAlias("application.views.sitemap.xml") . ".php", ['list' => $list], true);
		$handle	 = fopen(PUBLIC_PATH . DIRECTORY_SEPARATOR . 'sitemaps' . DIRECTORY_SEPARATOR . "sitemap_package.xml", 'w');
		fwrite($handle, $data);
		fclose($handle);
		Logger::create("command.system.packageXml end", CLogger::LEVEL_PROFILE);
	}

	public function populateSitemap(&$list)
	{
		$routes			 = Route::model()->getSiteRoutesCron();
		$list[]			 = $this->createSitemapEntry('', [], 'daily', '1');
		$list[]			 = $this->createSitemapEntry('users/signin', [], 'monthly', '0.7');
		$list[]			 = $this->createSitemapEntry('users/signup', [], 'monthly', '0.7');
		$list[]			 = $this->createSitemapEntry('index/aboutus', [], 'monthly', '0.5');
		$list[]			 = $this->createSitemapEntry('index/faqs', [], 'monthly', '0.5');
		$list[]			 = $this->createSitemapEntry('index/contactus', [], 'monthly', '0.8');
		$list[]			 = $this->createSitemapEntry('index/openings', [], 'monthly', '0.5');
		$list[]			 = $this->createSitemapEntry('index/terms', [], 'monthly', '0.5');
		$list[]			 = $this->createSitemapEntry('index/disclaimer', [], 'monthly', '0.5');
		$list[]			 = $this->createSitemapEntry('index/testimonial', [], 'daily', '0.7');
		$list[]			 = $this->createSitemapEntry('cheapest-oneway-rides', [], 'daily', '0.9');
		$list[]			 = $this->createSitemapEntry('blog', [], 'daily', '0.7');
		$new_text		 = array();
		$new_text_sec	 = array();
		for ($i = 0; $i < count($routes); $i++)
		{
			$text		 = strtolower(str_replace(' ', '_', $routes[$i]['text']));
			$text_split	 = explode("-", $text);

			$list[] = $this->createSitemapEntry('book-taxi/' . $text, '', 'weekly', '0.9');
			//remove duplicate link and add tempo traveller and outstation link
			if (in_array($text_split[0], $new_text))
			{
				goto end;
			}
			$check_city = Cities::model()->getIdByCityCron($text_split[0]);
			if ($check_city == '')
			{
				$modify_city		 = strtolower(str_replace('_', '-', $text_split[0]));
				$check_modified_city = Cities::model()->getIdByCityCron($modify_city);
				if ($check_modified_city != "")
				{
					$list[]	 = $this->createSitemapEntry('outstation-cabs/' . $modify_city, '', 'weekly', '0.9');
					$list[]	 = $this->createSitemapEntry('tempo-traveller-rental/' . $modify_city, '', 'weekly', '0.9');
				}
			}
			else
			{
				$new_text[]	 = $text_split[0];
				$list[]		 = $this->createSitemapEntry('outstation-cabs/' . $text_split[0], '', 'weekly', '0.9');
				$list[]		 = $this->createSitemapEntry('tempo-traveller-rental/' . $text_split[0], '', 'weekly', '0.9');
			}


			if (in_array($text_split[1], $new_text))
			{
				goto end;
			}
			$check_city = Cities::model()->getIdByCityCron($text_split[1]);
			if ($check_city == '')
			{
				$modify_city		 = strtolower(str_replace('_', '-', $text_split[1]));
				$check_modified_city = Cities::model()->getIdByCityCron($modify_city);
				if ($check_modified_city != "")
				{
					$list[]	 = $this->createSitemapEntry('outstation-cabs/' . $modify_city, '', 'weekly', '0.9');
					$list[]	 = $this->createSitemapEntry('tempo-traveller-rental/' . $modify_city, '', 'weekly', '0.9');
				}
			}
			else
			{
				$new_text[]	 = $text_split[1];
				//print_r($new_text);
				$list[]		 = $this->createSitemapEntry('outstation-cabs/' . $text_split[1], '', 'weekly', '0.9');
				$list[]		 = $this->createSitemapEntry('tempo-traveller-rental/' . $text_split[1], '', 'weekly', '0.9');
			}
			end:
		}

		//print_r($new_text);
	}

	public function createSitemapEntry($route, $params = [], $frequency, $priority)
	{
		return ['loc'		 => "http://www.aaocab.com/" . $route,
			// return ['loc'		 => "http://localhost:89/" . $route,
			'frequency'	 => $frequency,
			'priority'	 => $priority];
	}

	public function actionPopulateOtherSitemapState()
	{
		$this->writeToRobotFile('sitemap.xml', true);
		$state_list	 = States::model()->getStateListCron();
		$cityList	 = Cities::model()->getStateCityNameCron();
		$routeCount	 = Route::model()->cityRouteCountCron();

		if (count($state_list) > 0)
		{
			for ($j = 0; $j < count($state_list); $j++)
			{
				$strXML		 = '';
				$stateId	 = $state_list[$j] ['stt_id'];
				$urlCount	 = 0;
				$countCity	 = count($cityList[$state_list[$j] ['stt_id']]);
				if ($countCity > 0)
				{
					for ($i = 0; $i < $countCity; $i++)
					{
						$city = $cityList[$state_list[$j] ['stt_id']][$i];
						if ($city != "")
						{
							//$strXML	 .= $this->getSitemapXMLString($urlCount, array('loc' => 'car-rental/' . $city, 'frequency' => 'monthly', 'priority' => '0.7'));
							$strXML	 .= $this->getSitemapXMLString($urlCount, array('loc' => 'tempo-traveller-rental/' . $city, 'frequency' => 'monthly', 'priority' => '0.7'));
							$strXML	 .= $this->getSitemapXMLString($urlCount, array('loc' => 'outstation-cabs/' . $city, 'frequency' => 'monthly', 'priority' => '0.7'));
							$cmodel	 = Cities::model()->getCityhasairportCron($city);
							if ($cmodel == 1)
							{
								$strXML .= $this->getSitemapXMLString($urlCount, array('loc' => 'airport-transfer/' . $city, 'frequency' => 'monthly', 'priority' => '0.7'));
							}
							$luxary_cities_arr = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur');
							if (in_array($city, $luxary_cities_arr))
							{
								$strXML .= $this->getSitemapXMLString($urlCount, array('loc' => 'Luxury-car-rental/' . $city, 'frequency' => 'monthly', 'priority' => '0.7'));
							}
						}
					}
				}
				$totUrl		 = 0;
				$zz			 = 0;
				$yy			 = 0;
				$dataLimit	 = 1000;
				$xmlLimit	 = 45000;

				if ($routeCount[$j]['cnt'] > 0)
				{
					while ($zz < $routeCount[$j]['cnt'])
					{
						$routeData = Route::model()->allCityRouteListCron($stateId, $zz, $dataLimit);
						for ($xx = 0; $xx < count($routeData); $xx++)
						{
							$totUrl++;
							$route	 = $routeData[$xx]['rut_name'];
							$strXML	 .= $this->getSitemapXMLString($urlCount, array('loc' => 'book-taxi/' . $route, 'frequency' => 'monthly', 'priority' => '0.7'));
							if ($urlCount >= $xmlLimit || $totUrl == $routeCount[$j]['cnt'])
							{
								$sitemapXmlData		 = $this->getSitemapXMLString($urlCount, array(), true, false);
								$sitemapXmlData		 .= $strXML;
								$sitemapXmlData		 .= $this->getSitemapXMLString($urlCount, array(), false, true);
								$suffix				 = ($yy == 0) ? "" : "_" . $yy;
								$sitemapXMLFilename	 = 'sitemap' . $stateId . $suffix . '.xml';
								$this->writeSitemapXMLFile($sitemapXMLFilename, $sitemapXmlData);
								$this->writeToRobotFile($sitemapXMLFilename, false);
								$urlCount			 = 0;
								$strXML				 = '';
								$yy++;
							}
						}
						$zz += $dataLimit;
					}
				}
			}
		}
	}

	public function writeSitemapXMLFile($fileName, $xmlData)
	{
		$sitemap_folder	 = "sitemaps";
		$handle			 = fopen(PUBLIC_PATH . DIRECTORY_SEPARATOR . $sitemap_folder . DIRECTORY_SEPARATOR . $fileName, 'w');
		fwrite($handle, $xmlData);
		fclose($handle);
	}

	public function writeToRobotFile($fileName, $newFile = false)
	{
		#echo "\n writeToRobotFile == " . $fileName . " , NewFile == " . $newFile;
		$sitemap_folder	 = "sitemaps";
		$site_url		 = "http://www.aaocab.com/";
		$txt			 = '';

		// Create Robot.txt
		if ($newFile)
		{
			$myfile = fopen('robots.txt', 'w') or die("Unable to open file!");

			$txt = "User-agent: *" . PHP_EOL;
			$txt .= "allow: /" . PHP_EOL;
			$txt .= "crawl-delay: 7" . PHP_EOL;
			$txt .= PHP_EOL;
			$txt .= PHP_EOL;
			fwrite($myfile, $txt);
			fclose($myfile);
		}

		// Write Robot.txt Content
		if (trim($fileName) != '')
		{
			$myfile	 = fopen("robots.txt", "a") or die("Unable to open file!");
			$txt	 = "SITEMAP: " . $site_url . $sitemap_folder . "/" . $fileName . PHP_EOL;
			fwrite($myfile, $txt);
			fclose($myfile);
		}
	}

	public function getSitemapXMLString(&$urlCount, $data = array(), $fileBegin = false, $fileEnd = false)
	{
		$strXML	 = '';
		$siteUrl = 'http://www.aaocab.com/';

		if ($fileBegin)
		{
			$strXML	 = '<?xml version="1.0" encoding="UTF-8"?>';
			$strXML	 .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
		}

		if (count($data) > 0)
		{
			$strXML	 .= '<url>';
			$strXML	 .= '<loc>' . CHtml::encode($siteUrl . $data['loc']) . '</loc>';
			$strXML	 .= '<changefreq>' . $data['frequency'] . '</changefreq>';
			$strXML	 .= '<priority>' . $data['priority'] . '</priority>';
			$strXML	 .= '</url>';
			$urlCount++;
		}
		if ($fileEnd)
		{
			$strXML .= '</urlset>';
		}

		return $strXML;
	}

	public function actionCarRentalSiteMapXml()
	{
		$arrCtyCat = [1, 2, 0];
		foreach ($arrCtyCat as $cat)
		{

			$sql	 = "SELECT cty_id,cty_alias_path FROM `cities_stats` INNER JOIN cities ON cty_id = cts_cty_id WHERE cts_category = {$cat} AND cty_service_active = 1 AND cty_active=1;";
			$rows	 = DBUtil::queryAll($sql);
			$list	 = [];
			if ($cat == 1)
			{
				$frequency	 = 'weekly';
				$priority	 = '0.8';
			}
			if ($cat == 2)
			{
				$frequency	 = 'weekly';
				$priority	 = '0.9';
			}
			if ($cat == 0)
			{
				$frequency	 = 'monthly';
				$priority	 = '0.7';
			}
			foreach ($rows as $row)
			{
				$list[] = $this->createSitemapEntry("car-rental/" . strtolower($row['cty_alias_path']), [], $frequency, $priority);
			}

			$count	 = count($list);
			$length	 = 45000; // real data
			for ($i = 1; $i <= ceil($count / $length); $i++)
			{
				$offset	 = ($i - 1) * $length;
				$list1	 = array_slice($list, $offset, $length);
				$str	 = '<?xml version="1.0" encoding="UTF-8"?>';
				$str	 .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'  xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'  xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9  http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'>";
				for ($k = 0; $k < count($list1); $k++)
				{
					$str .= "<url><loc>" . $list1[$k]['loc'] . "</loc><changefreq>" . $list1[$k]['frequency'] . "</changefreq> <priority>" . $list1[$k]['priority'] . "</priority></url>";
				}
				$str				 .= "</urlset>";
				//$j					 = ($i == 1) ? "" : $i - 1;
				$sitemapXMLFilename	 = "sitemap_cr_{$cat}_{$i}.xml";
				$this->writeSitemapXMLFile($sitemapXMLFilename, $str);
				$this->writeToRobotFile($sitemapXMLFilename, false);
			}
		}
	}

	// create other sitemap xml page
	public function actionPopulateOtherSitemapState_OLD()
	{
		#echo "\nactionPopulateOtherSitemapState";
		$sitemap_folder	 = "sitemaps";
		$site_url		 = "http://www.aaocab.com/";
		Logger::create("command.system.PopulateOtherSitemapState start", CLogger::LEVEL_PROFILE);
		$state_list		 = States::model()->getStateList();
		$path			 = Yii::getPathOfAlias("application.views.sitemap.xml");
		$webrootPath	 = Yii::getPathOfAlias("webroot");
		//clean robot.txt
		$fh				 = fopen('robots.txt', 'w');
		fclose($fh);
		//create static content in robot.txt
		$myfile			 = fopen("robots.txt", "a") or die("Unable to open file!");
		$txt			 .= "User-agent: *" . PHP_EOL;
		$txt			 .= "allow: /" . PHP_EOL;
		$txt			 .= PHP_EOL;
		$txt			 .= PHP_EOL;
		$txt			 .= "SITEMAP: " . $site_url . $sitemap_folder . "/sitemap.xml" . PHP_EOL;
		echo $txt;
		fwrite($myfile, $txt);
		fclose($myfile);

		#echo "\n state_list == " . count($state_list);

		foreach ($state_list as $state)
		{
			$list_map	 = array();
			//city related map
			$cityList	 = Cities::model()->getExcAirportCityNameByState($state->stt_id);

			$countCity = count($cityList);

			#echo "\n countCity == " . $countCity;

			if ($countCity > 0)
			{
				for ($i = 0; $i < count($cityList); $i++)
				{
					$city = $cityList[$i];
					if ($city != "")
					{
						$list_map[]	 = $this->createSitemapEntry('car-rental/' . $city, '', 'monthly', '0.7');
						$list_map[]	 = $this->createSitemapEntry('tempo-traveller-rental/' . $city, '', 'monthly', '0.7');
						$list_map[]	 = $this->createSitemapEntry('outstation-cabs/' . $city, '', 'monthly', '0.7');
						//show airport page 
						$cmodel		 = Cities::model()->getCityhasairport($city);
						if ($cmodel == 1)
						{
							$list_map[] = $this->createSitemapEntry('airport-transfer/' . $city, '', 'monthly', '0.7');
						}
						//show luxary page
						$luxary_cities_arr = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur');

						if (in_array($city, $luxary_cities_arr))
						{

							$list_map[] = $this->createSitemapEntry('Luxury-car-rental/' . $city, '', 'monthly', '0.7');
						}
					}
				}
			}

			#echo "\n =================== ";

			$xx				 = 0;
			$j				 = 0;
			$lowerlimit		 = 0;
			$number_of_data	 = 45000;
			while (1)
			{
				$rute_list_arr = array();
				$j++;
				if ($j == 1)
				{
					$number_of_data = $number_of_data - count($list_map);
				}
				else
				{
					$number_of_data	 = $number_of_data + 45000;
					$list_map		 = array();
				}

				#echo "\n stt_id == " . $state->stt_id . " , lowerlimit ==  " . $lowerlimit . " , number_of_data == " . $number_of_data;


				$internalLowerLimit = $lowerlimit;

				$arrData = array();
				for ($zz = $internalLowerLimit; $zz < $number_of_data; $zz += 1000)
				{
					$internalRouteData = Route::model()->allCityRouteList($state->stt_id, $zz, 1000);

					if (count($internalRouteData) == 0)
					{
						break;
					}
					$arrData = array_merge((array) $arrData, (array) $internalRouteData);

					/* if($xx == 2)
					  {
					  print_r($internalRouteData);
					  print_r($arrData);
					  die();
					  }
					  $xx++; */
				}
				$rute_list_arr = $arrData;

				#$rute_list_arr = Route::model()->allCityRouteList($state->stt_id, $lowerlimit, $number_of_data);
				#print_r($rute_list_arr);
				#die();
				#echo "\n COUNT rute_list_arr == " . count($rute_list_arr);

				if (count($rute_list_arr) == 0)
				{
					break;
				}

				// add xml
				for ($i = 0; $i < count($rute_list_arr); $i++)
				{
					$route		 = $rute_list_arr[$i]['rut_name'];
					$list_map[]	 = $this->createSitemapEntry('book-taxi/' . $route, '', 'monthly', '0.7');
				}
				$suffix		 = ($j == 1) ? "" : "_" . ($j - 1);
				$data		 = $this->renderFile($path . ".php", ['list' => $list_map], true);
				$handle		 = fopen(PUBLIC_PATH . DIRECTORY_SEPARATOR . $sitemap_folder . DIRECTORY_SEPARATOR . "sitemap{$state->stt_id}{$suffix}.xml", 'w');
				fwrite($handle, $data);
				fclose($handle);
				//write dynamic content in robot.txt start here
				$myfile		 = fopen("robots.txt", "a") or die("Unable to open file!");
				$txt		 .= "\n";
				$txt		 = "SITEMAP: " . $site_url . $sitemap_folder . "/sitemap{$state->stt_id}{$suffix}.xml" . PHP_EOL;
				fwrite($myfile, $txt);
				fclose($myfile);
				//write dynamic content in robot.txt end here
				$lowerlimit	 += $number_of_data;

				#echo "\n number_of_data == " . $number_of_data;
			}
		}
		Logger::create("command.system.siteXml end", CLogger::LEVEL_PROFILE);
	}

	// action to add data in site map route table
	public function actionPopulatePopularSitemapRute()
	{
		$routes = Route::model()->addSiteRoutesCron();
	}

	public function createList($url, $title, $frequency = 'daily', $priority = '0.8')
	{
		return ['url' => $url, 'title' => $title, 'frequency' => $frequency, 'priority' => $priority];
	}

	public function actionUpdateApiTracking()
	{
		$sql		 = 'select aat_id, aat_request, aat_response from agent_api_tracking where aat_type = 2 and aat_from_mmt_code IS NULL order by aat_id DESC limit 0, 10000';
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($resultset as $result)
		{
			$array			 = json_decode($result['aat_request'], true);
			$response		 = json_decode($result['aat_response'], true);
			$fromCityCode	 = MmtCity::model()->getCityId($array['fromCityCode']);
			$toCityCode		 = MmtCity::model()->getCityId($array['toCityCode']);
			if ($fromCityCode != '' && $toCityCode != '')
			{
				$fromToCityQuery = ", aat_from_city = " . $fromCityCode . ", aat_to_city = " . $toCityCode;
			}
			else
			{
				$fromToCityQuery = "";
			}
			$mmtFromCityCode = $array['fromCityCode'];
			$mmtToCityCode	 = $array['toCityCode'];
			$bookingType	 = $array['tripType'];
			if ($bookingType == 'OW')
			{
				$tripType = 1;
			}
			else
			{
				$tripType = 2;
			}
			$error = $response['errors'];
			if ($error != '')
			{
				$errorType = 4;
				foreach ($error as $key => $value)
				{
					$errorMsg = $value[0];
				}
				if ($errorMsg == 'Route not supported')
				{
					$errorType = 2;
				}
				if ($errorMsg == 'City not found')
				{
					$errorType = 1;
				}
				$errorQuery = "', aat_error_type = " . $errorType . ", aat_error_msg = '" . $errorMsg . "'";
			}
			else
			{
				$errorQuery = "'";
			}
			$pickupDate = DateTimeFormat::DatePickerToDate($array['departureDate']);
			if ($array['pickupTime'] != '')
			{
				$pickupTime = $array['pickupTime'] . ':00';
			}
			else
			{
				$pickupTime = '06:00:00';
			}
			$pickupDateTime	 = $pickupDate . ' ' . $pickupTime;
			echo $qry			 = "UPDATE agent_api_tracking SET aat_booking_type = " . $tripType . $fromToCityQuery . ", aat_pickup_date = '" . $pickupDateTime . "', aat_from_mmt_code = '" . $mmtFromCityCode . "', aat_to_mmt_code = '" . $mmtToCityCode . $errorQuery . " WHERE aat_id = " . $result['aat_id'];
			echo Yii::app()->db->createCommand($qry)->execute();
			echo "\n";
		}
	}

	public function actionUpdateGst()
	{
		$sql		 = 'select bkg_id from booking 
						INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
						where biv.bkg_igst = 0 
						and biv.bkg_cgst = 0 and biv.bkg_sgst = 0 and biv.bkg_service_tax_rate = 5 
						order by bkg_id DESC limit 0, 25000';
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($resultset as $result)
		{
			$model = Booking::model()->findByPk($result['bkg_id']);
			if ($model->bkg_agent_id != '')
			{
				$agtModel = Agents::model()->findByPk($model->bkg_agent_id);
				if ($agtModel->agt_city == 30706)
				{
					$model->bkgInvoice->bkg_cgst = Yii::app()->params['cgst'];
					$model->bkgInvoice->bkg_sgst = Yii::app()->params['sgst'];
					$model->bkgInvoice->bkg_igst = 0;
				}
				else
				{
					$model->bkgInvoice->bkg_igst = Yii::app()->params['igst'];
					$model->bkgInvoice->bkg_cgst = 0;
					$model->bkgInvoice->bkg_sgst = 0;
				}
			}
			else
			{
				if ($model->bkg_from_city_id == 30706)
				{
					$model->bkgInvoice->bkg_cgst = Yii::app()->params['cgst'];
					$model->bkgInvoice->bkg_sgst = Yii::app()->params['sgst'];
					$model->bkgInvoice->bkg_igst = 0;
				}
				else
				{
					$model->bkgInvoice->bkg_igst = Yii::app()->params['igst'];
					$model->bkgInvoice->bkg_cgst = 0;
					$model->bkgInvoice->bkg_sgst = 0;
				}
			}
			$model->update();
		}
	}

	public function actionUpdateTopRoutes()
	{
		$sql		 = 'select id, from_city_id, to_city_id from top_routes where suv_inclusive IS NULL';
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($resultset as $result)
		{
			$route							 = [];
			$routeModel						 = new BookingRoute();
			$routeModel->brt_from_city_id	 = $result['from_city_id'];
			$routeModel->brt_to_city_id		 = $result['to_city_id'];
			$routeModel->brt_pickup_datetime = '2018-01-03 06:00:00';
			if (DateTimeFormat::parseDateTime($routeModel->brt_pickup_datetime, $date, $time))
			{
				$routeModel->brt_pickup_date_date	 = $date;
				$routeModel->brt_pickup_date_time	 = $time;
			}
			$route[]				 = $routeModel;
			$quote					 = Quotation::model()->getQuote($route, 1);
			print_r($quote);
			$estm_distance			 = $quote['routeData']['quoted_km'];
			$estm_time				 = $quote[2]['total_min'];
			$suv_inclusive			 = $quote[2]['total_amt'];
			$suv_exclusive			 = $quote[2]['total_amt'] - $quote[2]['toll_tax'] - $quote[2]['state_tax'];
			$suv_toll_tax			 = $quote[2]['toll_tax'];
			$suv_state_tax			 = $quote[2]['state_tax'];
			$suv_toll_state_flag	 = $quote[2]['tolltax'];
			$compact_inclusive		 = $quote[1]['total_amt'];
			$compact_exclusive		 = $quote[1]['total_amt'] - $quote[1]['toll_tax'] - $quote[1]['state_tax'];
			$compact_toll_tax		 = $quote[1]['toll_tax'];
			$compact_state_tax		 = $quote[1]['state_tax'];
			$compact_toll_state_flag = $quote[1]['tolltax'];
			$sedan_inclusive		 = $quote[3]['total_amt'];
			$sedan_exclusive		 = $quote[3]['total_amt'] - $quote[3]['toll_tax'] - $quote[3]['state_tax'];
			$sedan_toll_tax			 = $quote[3]['toll_tax'];
			$sedan_state_tax		 = $quote[3]['state_tax'];
			$sedan_toll_state_flag	 = $quote[3]['tolltax'];
			$qry					 = "UPDATE top_routes SET suv_inclusive = " . $suv_inclusive . ", suv_exclusive = " . $suv_exclusive . ", suv_toll_tax = " . $suv_toll_tax . ", suv_state_tax = " . $suv_state_tax . ", sedan_inclusive = " . $sedan_inclusive . ", sedan_exclusive = " . $sedan_exclusive . ", sedan_toll_tax = " . $sedan_toll_tax . ", sedan_state_tax = " . $sedan_state_tax . ", compact_inclusive = " . $compact_inclusive . ", compact_exclusive = " . $compact_exclusive . ", compact_toll_tax = " . $compact_toll_tax . ", compact_state_tax = " . $compact_state_tax . ", suv_toll_state_flag = " . $suv_toll_state_flag . ", sedan_toll_state_flag = " . $sedan_toll_state_flag . ", compact_toll_state_flag = " . $compact_toll_state_flag . ", estm_distance = " . $estm_distance . ", estm_time = " . $estm_time . " where id = " . $result['id'];
			Yii::app()->db->createCommand($qry)->execute();
		}
	}

	public function actionUpdateTopRoutesSavaari()
	{
		$sql		 = 'select sr.rut_id, r.rut_from_city_id, r.rut_to_city_id from savaari_routes sr
                left join route r on r.rut_id = sr.rut_id where sr.sedan_total_amount is null';
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($resultset as $result)
		{
			$route								 = [];
			$routeModel							 = new BookingRoute();
			$routeModel->brt_from_city_id		 = $result['rut_from_city_id'];
			$routeModel->brt_to_city_id			 = $result['rut_to_city_id'];
			$routeModel->brt_pickup_datetime	 = '2017-11-14 06:00:00';
			$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker('2017-11-14 06:00:00');
			$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime('2017-11-14 06:00:00'));
			$route[]							 = $routeModel;
			$quote								 = Quotation::model()->getQuote($route, 1);
			$estm_distance						 = $quote['routeData']['quoted_km'];
			$estm_time							 = $quote[2]['total_min'];
			$suv_total_amount					 = $quote[2]['total_amt'];
			$suv_gst							 = $quote[2]['service_tax'];
			$suv_toll_tax						 = $quote[2]['toll_tax'];
			$suv_state_tax						 = $quote[2]['state_tax'];
			$suv_vendor_amount					 = $quote[2]['vendor_amount'];
			$suv_base_amount					 = $quote[2]['base_amt'];
			$suv_extra_charge					 = $quote[2]['km_rate'];
			$compact_total_amount				 = $quote[1]['total_amt'];
			$compact_gst						 = $quote[1]['service_tax'];
			$compact_toll_tax					 = $quote[1]['toll_tax'];
			$compact_state_tax					 = $quote[1]['state_tax'];
			$compact_vendor_amount				 = $quote[1]['vendor_amount'];
			$compact_base_amount				 = $quote[1]['base_amt'];
			$compact_extra_charge				 = $quote[1]['km_rate'];
			$sedan_total_amount					 = $quote[3]['total_amt'];
			$sedan_gst							 = $quote[3]['service_tax'];
			$sedan_toll_tax						 = $quote[3]['toll_tax'];
			$sedan_state_tax					 = $quote[3]['state_tax'];
			$sedan_vendor_amount				 = $quote[3]['vendor_amount'];
			$sedan_base_amount					 = $quote[3]['base_amt'];
			$sedan_extra_charge					 = $quote[3]['km_rate'];
			$qry								 = "UPDATE savaari_routes SET 
		     suv_total_amount = " . $suv_total_amount . ",
		     suv_gst = " . $suv_gst . ","
					. " suv_toll = " . $suv_toll_tax . ","
					. " suv_state = " . $suv_state_tax . ","
					. " sedan_total_amount = " . $sedan_total_amount . ","
					. " sedan_gst = " . $sedan_gst . ","
					. " sedan_toll = " . $sedan_toll_tax . ","
					. " sedan_state = " . $sedan_state_tax . ","
					. " compact_total_amount = " . $compact_total_amount . ","
					. " compact_gst = " . $compact_gst . ","
					. " compact_toll = " . $compact_toll_tax . ","
					. " compact_state = " . $compact_state_tax . ","
					. " suv_extra_charge = " . $suv_extra_charge . ","
					. " sedan_extra_charge = " . $sedan_extra_charge . ","
					. " compact_extra_charge = " . $compact_extra_charge . ", "
					. " suv_base_amount	 = " . $suv_base_amount . ","
					. " sedan_base_amount	 = " . $sedan_base_amount . ","
					. " compact_base_amount	 = " . $compact_base_amount . " "
					. " where rut_id = " . $result['rut_id'];

			Yii::app()->db->createCommand($qry)->execute();
		}
	}

	public function actionUpdateTopRoutesReturn()
	{
		$sql		 = 'select id, from_city_id, to_city_id from top_routes_return where suv_inclusive_1 IS NULL';
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($resultset as $result)
		{
			$pickupCity		 = $result['from_city_id'];
			$dropCity		 = $result['to_city_id'];
			$pickupDate		 = '2017-10-12';
			$pickupTime		 = '06:00:00';
			$pickupDateTime	 = $pickupDate . ' ' . $pickupTime;
			$routeDuration	 = Route::model()->getRouteDurationbyCities($pickupCity, $dropCity);
			$routeDuration1	 = $routeDuration + 60;
			$returnTime		 = date('H:i:s', strtotime($pickupDateTime . '+ ' . $routeDuration1 . ' minute'));
			$returnDate		 = date('Y-m-d', strtotime($pickupDateTime . '+ ' . $routeDuration1 . ' minute'));
			$triptype		 = 2;
			$routes			 = [0 => ['pickupDate' => $pickupDate, 'pickupTime' => $pickupTime, 'dropCity' => $dropCity, 'dropPincode' => '', 'dropAddress' => '', 'pickupCity' => $pickupCity, 'pickupPincode' => '', 'pickupAddress' => ''], 1 => ['pickupDate' => $returnDate, 'pickupTime' => $returnTime, 'dropCity' => $pickupCity, 'dropPincode' => '', 'dropAddress' => '', 'pickupCity' => $dropCity, 'pickupPincode' => '', 'pickupAddress' => '']];
			$routes			 = json_encode($routes);
			$routes			 = json_decode($routes);
			$route			 = [];
			foreach ($routes as $key => $value)
			{
				$routeModel							 = new BookingRoute();
				$routeModel->brt_from_city_id		 = $value->pickupCity;
				$routeModel->brt_to_city_id			 = $value->dropCity;
				$routeModel->brt_pickup_datetime	 = $value->pickupDate . " " . $value->pickupTime;
				$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($value->pickupDate . " " . $value->pickupTime);
				$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime($value->pickupDate . " " . $value->pickupTime));
				$routeModel->brt_to_location		 = $value->dropAddress;
				$routeModel->brt_from_location		 = $value->pickupAddress;
				$routeModel->brt_to_pincode			 = $value->dropPincode;
				$routeModel->brt_from_pincode		 = $value->pickupPincode;
				$route[]							 = $routeModel;
			}
			$quote						 = Quotation::model()->getQuote($route, $triptype);
			$estm_distance				 = $quote['routeData']['quoted_km'];
			$estm_time					 = $quote[2]['total_min'];
			$suv_inclusive				 = $quote[2]['total_amt'];
			$suv_exclusive				 = $quote[2]['total_amt'] - $quote[2]['toll_tax'] - $quote[2]['state_tax'];
			$suv_toll_tax				 = $quote[2]['toll_tax'];
			$suv_state_tax				 = $quote[2]['state_tax'];
			$suv_toll_state_flag		 = $quote[2]['tolltax'];
			$suv_driver_allowance		 = $quote[2]['driverAllowance'];
			$compact_inclusive			 = $quote[1]['total_amt'];
			$compact_exclusive			 = $quote[1]['total_amt'] - $quote[1]['toll_tax'] - $quote[1]['state_tax'];
			$compact_toll_tax			 = $quote[1]['toll_tax'];
			$compact_state_tax			 = $quote[1]['state_tax'];
			$compact_toll_state_flag	 = $quote[1]['tolltax'];
			$compact_driver_allowance	 = $quote[1]['driverAllowance'];
			$sedan_inclusive			 = $quote[3]['total_amt'];
			$sedan_exclusive			 = $quote[3]['total_amt'] - $quote[3]['toll_tax'] - $quote[3]['state_tax'];
			$sedan_toll_tax				 = $quote[3]['toll_tax'];
			$sedan_state_tax			 = $quote[3]['state_tax'];
			$sedan_toll_state_flag		 = $quote[3]['tolltax'];
			$sedan_driver_allowance		 = $quote[3]['driverAllowance'];
			$qry						 = "UPDATE top_routes_return SET suv_inclusive_1 = " . $suv_inclusive . ", suv_exclusive_1 = " . $suv_exclusive . ", suv_toll_tax_1 = " . $suv_toll_tax . ", suv_state_tax_1 = " . $suv_state_tax . ", sedan_inclusive_1 = " . $sedan_inclusive . ", sedan_exclusive_1 = " . $sedan_exclusive . ", sedan_toll_tax_1 = " . $sedan_toll_tax . ", sedan_state_tax_1 = " . $sedan_state_tax . ", compact_inclusive_1 = " . $compact_inclusive . ", compact_exclusive_1 = " . $compact_exclusive . ", compact_toll_tax_1 = " . $compact_toll_tax . ", compact_state_tax_1 = " . $compact_state_tax . ", suv_toll_state_flag_1 = " . $suv_toll_state_flag . ", sedan_toll_state_flag_1 = " . $sedan_toll_state_flag . ", compact_toll_state_flag_1 = " . $compact_toll_state_flag . ", estm_distance_1 = " . $estm_distance . ", estm_time_1 = " . $estm_time . ", suv_driver_allowance_1 = " . $suv_driver_allowance . ", sedan_driver_allowance_1 = " . $suv_driver_allowance . ", compact_driver_allowance_1 = " . $compact_driver_allowance . " where id = " . $result['id'];
			Yii::app()->db->createCommand($qry)->execute();
		}
	}

	public function actionUpdateAirportRoutes()
	{
		$sql		 = 'select cty_id, cty_name from cities where cty_is_airport = 1 and cty_active = 1';
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($resultset as $result)
		{
			$sql1		 = "SELECT rut_to_city_id, cty_name from route join cities on cty_id = rut_to_city_id where rut_from_city_id = " . $result['cty_id'] . " AND rut_estm_distance < 40 AND rut_to_city_id <> " . $result['cty_id'];
			$resultset1	 = Yii::app()->db->createCommand($sql1)->queryAll();
			foreach ($resultset1 as $result1)
			{
				$route1								 = [];
				$route2								 = [];
				$routeModel1						 = new BookingRoute();
				$routeModel1->brt_from_city_id		 = $result['cty_id'];
				$routeModel1->brt_to_city_id		 = $result1['rut_to_city_id'];
				$routeModel1->brt_pickup_datetime	 = '2017-09-10 06:00:00';
				$routeModel1->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker('2017-09-10 06:00:00');
				$routeModel1->brt_pickup_date_time	 = date('h:i A', strtotime('2017-09-10 06:00:00'));
				$route1[]							 = $routeModel1;
				$routeModel2						 = new BookingRoute();
				$routeModel2->brt_from_city_id		 = $result1['rut_to_city_id'];
				$routeModel2->brt_to_city_id		 = $result['cty_id'];
				$routeModel2->brt_pickup_datetime	 = '2017-09-10 06:00:00';
				$routeModel2->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker('2017-09-10 06:00:00');
				$routeModel2->brt_pickup_date_time	 = date('h:i A', strtotime('2017-09-10 06:00:00'));
				$route2[]							 = $routeModel2;
				$quote1								 = Quotation::model()->getQuote($route1, 4);
				$quote2								 = Quotation::model()->getQuote($route2, 4);
				$estm_distance						 = $quote1[2]['chargeableDistance'];
				$estm_time							 = $quote1[2]['total_min'];
				$suv_inclusive_pickup				 = $quote1[2]['total_amt'];
				$suv_exclusive_pickup				 = $quote1[2]['total_amt'] - $quote1[2]['toll_tax'] - $quote1[2]['state_tax'];
				$suv_toll_tax_pickup				 = $quote1[2]['toll_tax'];
				$suv_state_tax_pickup				 = $quote1[2]['state_tax'];
				$suv_toll_state_flag_pickup			 = $quote1[2]['tolltax'];
				$compact_inclusive_pickup			 = $quote1[1]['total_amt'];
				$compact_exclusive_pickup			 = $quote1[1]['total_amt'] - $quote1[1]['toll_tax'] - $quote1[1]['state_tax'];
				$compact_toll_tax_pickup			 = $quote1[1]['toll_tax'];
				$compact_state_tax_pickup			 = $quote1[1]['state_tax'];
				$compact_toll_state_flag_pickup		 = $quote1[1]['tolltax'];
				$sedan_inclusive_pickup				 = $quote1[3]['total_amt'];
				$sedan_exclusive_pickup				 = $quote1[3]['total_amt'] - $quote1[3]['toll_tax'] - $quote1[3]['state_tax'];
				$sedan_toll_tax_pickup				 = $quote1[3]['toll_tax'];
				$sedan_state_tax_pickup				 = $quote1[3]['state_tax'];
				$sedan_toll_state_flag_pickup		 = $quote1[3]['tolltax'];
				$suv_inclusive_drop					 = $quote2[2]['total_amt'];
				$suv_exclusive_drop					 = $quote2[2]['total_amt'] - $quote2[2]['toll_tax'] - $quote2[2]['state_tax'];
				$suv_toll_tax_drop					 = $quote2[2]['toll_tax'];
				$suv_state_tax_drop					 = $quote2[2]['state_tax'];
				$suv_toll_state_flag_drop			 = $quote2[2]['tolltax'];
				$compact_inclusive_drop				 = $quote2[1]['total_amt'];
				$compact_exclusive_drop				 = $quote2[1]['total_amt'] - $quote2[1]['toll_tax'] - $quote2[1]['state_tax'];
				$compact_toll_tax_drop				 = $quote2[1]['toll_tax'];
				$compact_state_tax_drop				 = $quote2[1]['state_tax'];
				$compact_toll_state_flag_drop		 = $quote2[1]['tolltax'];
				$sedan_inclusive_drop				 = $quote2[3]['total_amt'];
				$sedan_exclusive_drop				 = $quote2[3]['total_amt'] - $quote2[3]['toll_tax'] - $quote2[3]['state_tax'];
				$sedan_toll_tax_drop				 = $quote2[3]['toll_tax'];
				$sedan_state_tax_drop				 = $quote2[3]['state_tax'];
				$sedan_toll_state_flag_drop			 = $quote2[3]['tolltax'];
				$qry								 = "INSERT INTO airport_transfer(from_city, to_city, from_city_id, to_city_id, estm_distance, estm_time, suv_inclusive_pickup, suv_inclusive_drop, suv_exclusive_pickup, suv_exclusive_drop, suv_toll_tax_pickup, suv_toll_tax_drop, suv_state_tax_pickup, suv_state_tax_drop, suv_toll_state_flag_pickup, suv_toll_state_flag_drop, sedan_inclusive_pickup, sedan_inclusive_drop, sedan_exclusive_pickup, sedan_exclusive_drop, sedan_toll_tax_pickup, sedan_toll_tax_drop, sedan_state_tax_pickup, sedan_state_tax_drop, sedan_toll_state_flag_pickup, sedan_toll_state_flag_drop, compact_inclusive_pickup, compact_inclusive_drop, compact_exclusive_pickup, compact_exclusive_drop, compact_toll_tax_pickup, compact_toll_tax_drop, compact_state_tax_pickup, compact_state_tax_drop, compact_toll_state_flag_pickup, compact_toll_state_flag_drop) 
                            VALUES ('" . $result['cty_name'] . "','" . $result1['cty_name'] . "'," . $result['cty_id'] . "," . $result1['rut_to_city_id'] . "," . $estm_distance . "," . $estm_time . "," . $suv_inclusive_pickup . "," . $suv_inclusive_drop . "," . $suv_exclusive_pickup . "," . $suv_exclusive_drop . "," . $suv_toll_tax_pickup . "," . $suv_toll_tax_drop . "," . $suv_state_tax_pickup . "," . $suv_state_tax_drop . "," . $suv_toll_state_flag_pickup . "," . $suv_toll_state_flag_drop . "," . $sedan_inclusive_pickup . "," . $sedan_inclusive_drop . "," . $sedan_exclusive_pickup . "," . $sedan_exclusive_drop . "," . $sedan_toll_tax_pickup . "," . $sedan_toll_tax_drop . "," . $sedan_state_tax_pickup . "," . $sedan_state_tax_drop . "," . $sedan_toll_state_flag_pickup . "," . $sedan_toll_state_flag_drop . "," . $compact_inclusive_pickup . "," . $compact_inclusive_drop . "," . $compact_exclusive_pickup . "," . $compact_exclusive_drop . "," . $compact_toll_tax_pickup . "," . $compact_toll_tax_drop . "," . $compact_state_tax_pickup . "," . $compact_state_tax_drop . "," . $compact_toll_state_flag_pickup . "," . $compact_toll_state_flag_drop . ")";
				Yii::app()->db->createCommand($qry)->execute();
			}
		}
	}

	public function actionAgentMessage()
	{
		$arrAgents = Yii::app()->db->createCommand("SELECT agt_id,agt_copybooking_ismail,agt_copybooking_issms,agt_trvl_isemail,agt_trvl_issms,agt_trvl_isapp,agt_copybooking_admin_ismail,agt_copybooking_admin_issms,agt_copybooking_admin_isapp FROM `agents` WHERE agt_active=1")->queryAll();
		foreach ($arrAgents as $agents)
		{
			$arrEvents = AgentMessages::getEvents();
			foreach ($arrEvents as $key => $value)
			{
				$agentMessages = AgentMessages::model()->getByEventAndAgent($agents['agt_id'], $key);
				if ($agentMessages == '')
				{
					$agentMessages					 = new AgentMessages();
					$agentMessages->agt_agent_id	 = $agents['agt_id'];
					$agentMessages->agt_event_id	 = $key;
					$agentMessages->agt_agent_email	 = $agents['agt_copybooking_ismail'];
					$agentMessages->agt_agent_sms	 = $agents['agt_copybooking_issms'];
					$agentMessages->agt_agent_app	 = 0;
					$agentMessages->agt_trvl_email	 = $agents['agt_trvl_isemail'];
					$agentMessages->agt_trvl_sms	 = $agents['agt_trvl_issms'];
					$agentMessages->agt_trvl_app	 = $agents['agt_trvl_isapp'];
					$agentMessages->agt_rm_email	 = $agents['agt_copybooking_admin_ismail'];
					$agentMessages->agt_rm_sms		 = $agents['agt_copybooking_admin_issms'];
					$agentMessages->agt_rm_app		 = $agents['agt_copybooking_admin_isapp'];
					$agentMessages->agt_active		 = 1;
					$agentMessages->save();
					echo "success" . $agentMessages->agt_msg_id . "\n";
				}
				else
				{
					echo "already exists" . "\n";
				}
			}
		}
	}

	public function actionBookingMessage()
	{
		$arrBooking = Yii::app()->db->createCommand("SELECT bkg_id,bkg_agent_id,bpr.bpr_id,bpr.bkg_crp_send_email,bpr.bkg_crp_send_sms,bpr.bkg_crp_send_app,bpr.bkg_trv_send_email,bpr.bkg_trv_send_sms,bpr.bkg_trv_send_app FROM `booking` LEFT JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg_id WHERE bkg_agent_id>0 AND bkg_status IN(2,3,5,6,7,8,9) AND bkg_active=1 order by bkg_pickup_date DESC")->queryAll();
		foreach ($arrBooking as $booking)
		{
			$arrEvents = AgentMessages::getEvents();
			foreach ($arrEvents as $key => $value)
			{

				$agentMessages = BookingMessages::model()->getByEventAndBookingId($booking['bkg_id'], $key);
				if ($agentMessages == '')
				{
					$bookingMessages				 = new BookingMessages();
					$bookingMessages->bkg_booking_id = $booking['bkg_id'];
					$bookingMessages->bkg_event_id	 = $key;
					$bookingMessages->bkg_active	 = 1;
					if ($booking['bpr_id'] == '')
					{
						$bookingMessages->getMessageDefaults($booking['bkg_agent_id'], $key);
					}
					else
					{
						$bookingMessages->bkg_agent_email	 = $booking['bkg_crp_send_email'];
						$bookingMessages->bkg_agent_sms		 = $booking['bkg_crp_send_sms'];
						$bookingMessages->bkg_agent_app		 = $booking['bkg_crp_send_app'];
						$bookingMessages->bkg_trvl_email	 = $booking['bkg_trv_send_email'];
						$bookingMessages->bkg_trvl_sms		 = $booking['bkg_trv_send_sms'];
						$bookingMessages->bkg_trvl_app		 = $booking['bkg_trv_send_app'];
						$bookingMessages->bkg_rm_email		 = 0;
						$bookingMessages->bkg_rm_sms		 = 0;
						$bookingMessages->bkg_rm_app		 = 0;
					}
					$bookingMessages->save();
					echo "success" . $bookingMessages->bkg_msg_id . "\n";
				}
			}
		}
	}

	/**
	 * Function for Archiving Data From Tables
	 */
	public function actionArchive()
	{
		Logger::create("command.system.archive end Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		$archiveDB = 'gozo_archive';

		// Archive Agent Api Tracking Data
		Logger::create("AgentApiTracking Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		AgentApiTracking::archiveData($archiveDB);
		Logger::create("AgentApiTracking Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Archive Booking Vendor Request Data
		Logger::create("BookingVendorRequest Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		BookingVendorRequest::model()->archiveData($archiveDB);
		Logger::create("BookingVendorRequest Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Archive Booking Log Data
		Logger::create("BookingLog Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		BookingLog::model()->archiveData($archiveDB);
		Logger::create("BookingLog Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		//Email Log Content Data
		Logger::create("EmailLog Content Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		EmailLog::model()->archiveEmailContentData($archiveDB);
		Logger::create("EmailLog Content Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		//Email Log Delete  Data
		Logger::create("EmailLog Delete Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		EmailLog::model()->deleteEmail();
		Logger::create("EmailLog Delete Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		//SMS Log Content Data
		Logger::create("SMSLog Content Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		SmsLog::model()->archiveSMSContentData($archiveDB);
		Logger::create("SMSLog Content Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		//SMS Log Delete  Data
		Logger::create("SMSLog Delete Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		SmsLog::model()->deleteSMS();
		Logger::create("SMSLog Delete Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// QuotesDataCreated
		Logger::create("QuotesDataCreated  Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		QuotesDataCreated::model()->archiveQuotesData($archiveDB);
		Logger::create("QuotesDataCreated  Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Quotes Data Pickup
		Logger::create("QuotesDataPickup  Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		QuotesDataPickup::model()->archiveQuotesPickup($archiveDB);
		Logger::create("QuotesDataPickup  Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Booking Temp
		Logger::create("BookingTempData  Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		BookingTemp::model()->archiveBookingTempData($archiveDB);
		Logger::create("BookingTempData  Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Booking Data
		Logger::create("BookingData  Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		BookingSub::archiveBookingData($archiveDB);
		Logger::create("BookingData  Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Visitor Track
		Logger::create("VisitorTrack  Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		VisitorTrack::model()->archiveData($archiveDB);
		Logger::create("VisitorTrack  Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Notification Log
		Logger::create("NotificationLog Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		NotificationLog::model()->archiveData($archiveDB);
		Logger::create("NotificationLog Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Location
		Logger::create("Location Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		Location::model()->archiveData($archiveDB);
		Logger::create("Location Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Archive API Tracking Data
		Logger::create("API Tracking Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		ApiTracking::model()->archiveData($archiveDB);
		Logger::create("API Tracking Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Archive Service Call Queue
		Logger::create("Service Call Queue Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		ServiceCallQueue::model()->archiveData($archiveDB);
		Logger::create("Service Call Queue Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Archive App Token
		Logger::create("App Token Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		AppTokens::model()->archiveData($archiveDB);
		Logger::create("App Token Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Archive Call Status
		Logger::create("Call Status Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		CallStatus::model()->archiveData($archiveDB);
		Logger::create("Call Status Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Archive WhatsappLog
		Logger::create("WhatsappLog Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		WhatsappLog::model()->archiveData($archiveDB);
		Logger::create("WhatsappLog Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Optimize Tables
		Logger::create("OptimizeSelectedTables Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		$this->actionOptimizeSelectedTables();
		Logger::create("OptimizeSelectedTables Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		Logger::create("command.system.archive end Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
	}

	/**
	 * Function for Archiving Data From Tables
	 */
	public function actionArchiveSingle()
	{
		$check = Filter::checkProcess("system archiveSingle");
		if (!$check)
		{
			return;
		}

		Logger::create("command.system.archivesingle end Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		$archiveDB = 'gozo_archive';

		Logger::create("App Token Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		AppTokens::model()->archiveData($archiveDB, 1000000, 1000);
		Logger::create("App Token Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		// Archive WhatsappLog
		#Logger::create("WhatsappLog Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		#WhatsappLog::model()->archiveData($archiveDB, 1000000, 1000);
		#Logger::create("WhatsappLog Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		Logger::create("command.system.archivesingle end Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
	}

	public function actionUpdateCabDriverMmt()
	{
		$sql		 = 'SELECT bkg_id, bcb_cab_number, bcb_driver_name, bcb_driver_phone, bkg_agent_ref_code, bkg_from_city_id, bkg_to_city_id, bkg_pickup_date, bkg_booking_type, bcb_driver_id, bcb_cab_id FROM `booking`
		JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id
		WHERE bkg_status = 5 and bkg_agent_id = 450 
		and date(bkg_pickup_date) = curdate()';
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($resultset as $result)
		{
			$model = Booking::model()->findByPk($result['bkg_id']);
			if ($model->bkg_agent_id == 450 || $model->bkg_agent_id == 18190)
			{
				$typeAction = AgentApiTracking::TYPE_CAB_DRIVER_UPDATE;
			}
			$partnerResponse = AgentMessages::model()->pushApiCall($model, $typeAction);
			if ($partnerResponse->status == 1)
			{
				echo $description = 'Updated Successfully';
			}
			else
			{
				echo $description = 'Failed to Update';
			}
			$params['blg_driver_id']	 = $result['bcb_driver_id'];
			$params['blg_vehicle_id']	 = $result['bcb_cab_id'];
			BookingLog::model()->createLog($result['bkg_id'], $description, UserInfo::model(), BookingLog::MMT_CAB_DRIVER_UPDATE, $oldModel, $params);
		}
	}

	public function actionagentApiDetailsMove()
	{
		echo "==========Start===========";
		while (true)
		{

			$range_sql	 = "SELECT aad_id, aad_aat_id, aad_request, aad_response, aat_agent_id, aat_type, aat_booking_type, aat_created_at FROM `agent_api_details` LEFT JOIN agent_api_tracking ON aad_aat_id = aat_id where aad_status>=0 ORDER BY aad_id DESC LIMIT 0, 50";
			$rows		 = Yii::app()->db->createCommand($range_sql)->queryAll();
			if (count($rows) == 0)
			{
				print_r($rows);
				break;
			}
			foreach ($rows as $row)
			{
				try
				{
					$request	 = $row['aad_request'];
					$response	 = $row['aad_response'];
					$body		 = $request . "\r\n\r\n============\r\n\r\n" . $response;
					$uniqueId	 = $row['aad_aat_id'];
					$path		 = Yii::app()->basePath;
					$agentId	 = $row['aat_agent_id'];
					$aatType	 = $row['aat_type'];
					$bookingType = $row['aat_booking_type'];
					$createAt	 = $row['aat_created_at'];

					$fileName = $aatType . '_' . $uniqueId . '_' . $bookingType . '.apl';

					$mainfoldername	 = $path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'partner' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . $agentId;
					$subFolderDay	 = $mainfoldername . DIRECTORY_SEPARATOR . Filter::createFolderPrefix(strtotime($createAt), true);

					Filter::WriteFile($subFolderDay, $fileName, $body, false);

					$sql = "UPDATE `agent_api_details` SET aad_status=-1 WHERE aad_id={$row['aad_id']}";
					Yii::app()->db->createCommand($sql)->execute();
				}
				catch (Exception $e)
				{
					echo "Error: {$e->getMessage()}";
				}
			}
//			break;
		}
		echo "<br>";
		echo "===========End==============";
	}

	public function actionpartnerApiDetailsMove()
	{
		echo "==========Start===========";
		while (true)
		{

			$range_sql	 = "SELECT pad_id, pad_pat_id, pad_request, pad_response, pat_agent_id, pat_type, pat_booking_type, pat_created_at FROM `partner_api_details` LEFT JOIN partner_api_tracking ON pad_pat_id = pat_id where pad_status >=0 ORDER BY pad_id DESC LIMIT 0, 50";
			$rows		 = Yii::app()->db->createCommand($range_sql)->queryAll();
			if (count($rows) == 0)
			{
				print_r($rows);
				break;
			}
			echo $row['pad_pat_id'] . "start==============";
			foreach ($rows as $row)
			{
				try
				{
					echo $row['pad_pat_id'] . '=========';
					$request	 = $row['pad_request'];
					$response	 = $row['pad_response'];
					$body		 = $request . "\r\n\r\n====================\r\n\r\n" . $response;
					$uniqueId	 = $row['pad_pat_id'];
					$path		 = Yii::app()->basePath;
					$agentId	 = $row['pat_agent_id'];
					$patType	 = $row['pat_type'];
					$bookingType = $row['pat_booking_type'];
					$createAt	 = $row['pat_created_at'];

					$fileName = $patType . '_' . $uniqueId . '_' . $bookingType . '.apl';

					$mainfoldername	 = $path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'partner' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . $agentId;
					$subFolderDay	 = $mainfoldername . DIRECTORY_SEPARATOR . Filter::createFolderPrefix(strtotime($createAt), true);

					$logPath = Filter::WriteFile($subFolderDay, $fileName, $body, false);
					$dbPath	 = explode("doc", $logPath);
					$sql	 = "UPDATE `partner_api_details` SET pad_status=-1 WHERE pad_id={$row['pad_id']}";
					//$sql = "Delete FROM `agent_api_details` WHERE aad_id = {$row['aad_id']}";
					Yii::app()->db->createCommand($sql)->execute();
				}
				catch (Exception $e)
				{
					echo "Error: {$e->getMessage()}";
				}
				echo "=========end";
			}
//			break;
		}
		echo "<br>";
		echo "===========End==============";
	}

	public function actionagentApiTrackingMove()
	{
		echo "==========Start===========";
		while (true)
		{
			$range_sql	 = "SELECT aat_id, aat_request, aat_response, aat_agent_id, aat_type, aat_booking_type, aat_created_at FROM `agent_api_tracking1` WHERE aat_request != '' AND aat_response != '' ORDER BY aat_id DESC LIMIT 0, 50";
			$rows		 = Yii::app()->db->createCommand($range_sql)->queryAll();
			if (count($rows) == 0)
			{
				print_r($rows);
				break;
			}
			foreach ($rows as $row)
			{
				try
				{
					$request	 = $row['aat_request'];
					$response	 = $row['aat_response'];
					$body		 = $request . "\r\n\r\n============\r\n\r\n" . $response;
					$uniqueId	 = $row['aat_id'];
					$path		 = Yii::app()->basePath;
					$agentId	 = $row['aat_agent_id'];
					$aatType	 = $row['aat_type'];
					$bookingType = $row['aat_booking_type'];
					$createAt	 = $row['aat_created_at'];

					$fileName = $aatType . '_' . $uniqueId . '_' . $bookingType . '.apl';

					$mainfoldername	 = $path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'partner' . DIRECTORY_SEPARATOR . 'apitracking1' . DIRECTORY_SEPARATOR . $agentId;
					$subFolderDay	 = $mainfoldername . DIRECTORY_SEPARATOR . Filter::createFolderPrefix(strtotime($createAt), true);

					Filter::WriteFile($subFolderDay, $fileName, $body, false);

					$sql = "UPDATE `agent_api_tracking1` SET aat_request='', aat_response='' WHERE aat_id={$row['aat_id']}";
					Yii::app()->db->createCommand($sql)->execute();
				}
				catch (Exception $e)
				{
					echo "Error: {$e->getMessage()}";
				}
			}
//			break;
		}
		echo "<br>";
		echo "===========End==============";
	}

	public function actionApprovedVehicleForUBER()
	{
		$path	 = PUBLIC_PATH;
		$sql	 = "SELECT vhc.vhc_id,vhc.vhc_number,vhd.vhd_type,vhd.vhd_file FROM vehicles vhc
				INNER JOIN vehicle_docs vhd ON vhd.vhd_vhc_id = vhc.vhc_id AND vhd.vhd_active=1  AND vhd.vhd_status=1
				WHERE vhc.vhc_active=1 AND vhd.vhd_type IS NOT NULL AND vhd.vhd_file IS NOT NULL AND vhc.vhc_approved=1 AND vhc.vhc_id 
				IN (SELECT cabid FROM table162 WHERE cabid IS NOT NULL)";
		$rows	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($rows as $row)
		{
			$vhcNo		 = $row['vhc_number'];
			$existPath	 = $row['vhd_file'];
			$exitPathUrl = PUBLIC_PATH . $existPath;
			$file		 = explode('/', $existPath);
			$filename	 = $file[4];

			$oldFolderPath	 = $path . $row['vhd_file'];
			$newfolderpath	 = $path . DIRECTORY_SEPARATOR . $vhcNo;

			$mainfoldername = $path . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'vehicles' . DIRECTORY_SEPARATOR . $vhcNo;
			if (!file_exists($mainfoldername))
			{
				mkdir($mainfoldername, 777, true);
			}
			$newFileName = $mainfoldername . DIRECTORY_SEPARATOR . $filename;

			$newFileName = str_replace(DIRECTORY_SEPARATOR, '/', $newFileName);
			$exitPathUrl = str_replace(DIRECTORY_SEPARATOR, '/', $exitPathUrl);

			if (file_exists($exitPathUrl))
			{
				if (copy($exitPathUrl, $newFileName))
				{
					echo "<br>copied $exitPathUrl into $newFileName\n";
				}
				else
				{
					echo "<br>failed to copy $exitPathUrl...\n";
				}
			}
			else
			{
				echo "<br>File Not Found\n";
			}
		}
	}

	public function actionApprovedDriverForUBER()
	{
		$path	 = PUBLIC_PATH;
		$sql	 = "SELECT drv.drv_id, drv.drv_name, drv.drv_phone, drd.drd_file FROM drivers drv
				INNER JOIN driver_docs drd ON drd.drd_drv_id = drv.drv_id AND drd.drd_active=1  AND drd.drd_status=1
				WHERE drv.drv_active=1 AND drd.drd_file IS NOT NULL AND drv.drv_approved=1 AND drv.drv_id 
				IN (SELECT driverid FROM table162 WHERE driverid IS NOT NULL)";
		$rows	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($rows as $row)
		{
			$drv_name	 = $row['drv_name'];
			$drv_phone	 = $row['drv_phone'];
			$existPath	 = $row['drd_file'];
			$exitPathUrl = PUBLIC_PATH . $existPath;
			$file		 = explode('/', $existPath);
			$filename	 = $file[4];

			$oldFolderPath	 = $path . $row['drd_file'];
			$newfolderpath	 = $path . DIRECTORY_SEPARATOR . $drv_name . "_" . $drv_phone;

			$mainfoldername = $path . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'drivers' . DIRECTORY_SEPARATOR . $drv_name . "_" . $drv_phone;
			if (!file_exists($mainfoldername))
			{
				mkdir($mainfoldername, 777, true);
			}
			$newFileName = $mainfoldername . DIRECTORY_SEPARATOR . $filename;

			$newFileName = str_replace(DIRECTORY_SEPARATOR, '/', $newFileName);
			$exitPathUrl = str_replace(DIRECTORY_SEPARATOR, '/', $exitPathUrl);

			if (file_exists($exitPathUrl))
			{
				if (copy($exitPathUrl, $newFileName))
				{
					echo "<br>copied $exitPathUrl into $newFileName\n";
				}
				else
				{
					echo "<br>failed to copy $exitPathUrl...\n";
				}
			}
			else
			{
				echo "<br>File Not Found\n";
			}
		}
	}

	public function actionPendingApprovedVehicleForUBER()
	{
		$path	 = PUBLIC_PATH;
		$sql	 = "SELECT vhc.vhc_id,vhc.vhc_number,vhd.vhd_type,vhd.vhd_file FROM vehicles vhc
				INNER JOIN vehicle_docs vhd ON vhd.vhd_vhc_id = vhc.vhc_id AND vhd.vhd_active=1  AND vhd.vhd_status=1
				WHERE vhc.vhc_active=1 AND vhd.vhd_type IS NOT NULL AND vhd.vhd_file IS NOT NULL AND vhc.vhc_approved=1 AND vhc.vhc_id 
				IN (SELECT vhc_id FROM vwVehicleDriverList WHERE vhc_id IS NOT NULL)";
		$rows	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($rows as $row)
		{
			$vhcNo		 = $row['vhc_number'];
			$existPath	 = $row['vhd_file'];
			$exitPathUrl = PUBLIC_PATH . $existPath;
			$file		 = explode('/', $existPath);
			$filename	 = $file[4];

			$oldFolderPath	 = $path . $row['vhd_file'];
			$newfolderpath	 = $path . DIRECTORY_SEPARATOR . $vhcNo;

			$mainfoldername = $path . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'vehiclesSouth' . DIRECTORY_SEPARATOR . $vhcNo;
			if (!file_exists($mainfoldername))
			{
				mkdir($mainfoldername, 777, true);
			}
			$newFileName = $mainfoldername . DIRECTORY_SEPARATOR . $filename;

			$newFileName = str_replace(DIRECTORY_SEPARATOR, '/', $newFileName);
			$exitPathUrl = str_replace(DIRECTORY_SEPARATOR, '/', $exitPathUrl);

			if (file_exists($exitPathUrl))
			{
				if (copy($exitPathUrl, $newFileName))
				{
					echo "<br>copied $exitPathUrl into $newFileName\n";
				}
				else
				{
					echo "<br>failed to copy $exitPathUrl...\n";
				}
			}
			else
			{
				echo "<br>File Not Found\n";
			}
		}
	}

	public function actionPendingApprovedDriverForUBER()
	{
		$path	 = PUBLIC_PATH;
		$sql	 = "SELECT drv.drv_id, drv.drv_name, drv.drv_phone, drd.drd_file FROM drivers drv
				INNER JOIN driver_docs drd ON drd.drd_drv_id = drv.drv_id AND drd.drd_active=1  AND drd.drd_status=1
				WHERE drv.drv_active=1 AND drd.drd_file IS NOT NULL AND drv.drv_approved=1 AND drv.drv_id 
				IN (SELECT drv_id FROM vwVehicleDriverList WHERE drv_id IS NOT NULL)";
		$rows	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($rows as $row)
		{
			$drv_name	 = $row['drv_name'];
			$drv_phone	 = $row['drv_phone'];
			$existPath	 = $row['drd_file'];
			$exitPathUrl = PUBLIC_PATH . $existPath;
			$file		 = explode('/', $existPath);
			$filename	 = $file[4];

			$oldFolderPath	 = $path . $row['drd_file'];
			$newfolderpath	 = $path . DIRECTORY_SEPARATOR . $drv_name . "_" . $drv_phone;

			$mainfoldername = $path . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'driversSouth' . DIRECTORY_SEPARATOR . $drv_name . "_" . $drv_phone;
			if (!file_exists($mainfoldername))
			{
				mkdir($mainfoldername, 777, true);
			}
			$newFileName = $mainfoldername . DIRECTORY_SEPARATOR . $filename;

			$newFileName = str_replace(DIRECTORY_SEPARATOR, '/', $newFileName);
			$exitPathUrl = str_replace(DIRECTORY_SEPARATOR, '/', $exitPathUrl);

			if (file_exists($exitPathUrl))
			{
				if (copy($exitPathUrl, $newFileName))
				{
					echo "<br>copied $exitPathUrl into $newFileName\n";
				}
				else
				{
					echo "<br>failed to copy $exitPathUrl...\n";
				}
			}
			else
			{
				echo "<br>File Not Found\n";
			}
		}
	}

	/**
	 * @deprecated since 15/12/2020
	 * @author rakesh
	 * New Function:OptimizeSelectedTables
	 */
	public function actionOptimizeTables()
	{
		$arrIgnoreTable = array('vendor_agreement1', 'vendor_agreement2', 'email_log', 'sms_log', 'agent_api_tracking', 'booking_vendor_request', 'booking_log');

		// Optimize Main DB
		$database = DBUtil::queryScalar("SELECT DATABASE()");
		$this->optimizeTables($database, $arrIgnoreTable);

		// Optimize Archive DB
		#$archiveDB = 'gozo_archive';
		#$this->optimizeTables($archiveDB, $arrIgnoreTable);
	}

	/**
	 * @deprecated since 15/12/2020
	 * @author rakesh
	 */
	protected function optimizeTables($database, $arrIgnoreTable)
	{
		try
		{
			if (trim($database) != '')
			{
				$sql	 = "SELECT table_name FROM information_schema.tables WHERE table_schema='" . $database . "'";
				$rows	 = DBUtil::queryAll($sql);
				if ($rows)
				{
					foreach ($rows as $row)
					{
						if (!in_array($row['table_name'], $arrIgnoreTable))
						{
							$tableName	 = $row['table_name'];
							$sql		 = "OPTIMIZE TABLE " . $database . ".`" . $tableName . "`";
							$rows		 = DBUtil::command($sql)->execute();
						}
					}
				}
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			echo "\r\n";
		}
	}

	/**
	 * Function for Optimizing Selected Tables
	 */
	public function actionOptimizeSelectedTables()
	{
		Logger::info("OptimizeSelectedTables action start");
		//date('w') 0->sunday,1->monday,2->Tuesday,3->Wednesday,4->Thursday,5->Friday,6->Saturday
		$database = DBUtil::queryScalar("SELECT DATABASE()");

		//Ignore table list  monthy
		$arrIgnoreTable = array(
			'quotes_data_created', 'booking_invoice_2', 'quotes_data_pickup', 'booking_price_factor',
			'bookings_data_created', 'bookings_data_pickup', 'dynamic_price_surge_pri_bkp', 'transactions',
			'temp_user_table', 'temp_user_table_final', 'vendors1', 'contact_190417', 'driver_docs1',
			'vendor_agreement1', 'nearestcity3', 'nearestcity2', 'vendor_freeze_july', 'chandigarh-delhi', 'route_suggest_re1',
			'TABLE 250', 'route_suggest_re1', 'vendor_docs1', 'Commission_Backup', 'account_trans_details_1',
			'vrs_lock_backup_1', 'vrs_lock_backup_1', 'VendorCollection1718', 'states', 'VendorUnAuthorized', 'vwZoneDemand', 'vendor_agreement2', 'nearestcity1'
		);

		//Optimize table list
		$arrTable = array(
			"0"	 => array(),
			"1"	 => array(),
			"2"	 => array('booking_log', 'call_status', 'dynamic_price_surge'),
			"3"	 => array(
				'email_log', 'sms_log', 'lead_log', 'booking_log', 'vendors_log', 'vehicles_log',
				'drivers_log', 'chat_log', 'customer_profile', 'contact_log', 'notification_log', 'whatsapp_log'
			),
			"4"	 => array(
				'account_trans_details', 'agent_api_tracking', 'booking_trail', 'account_transactions',
				'route', 'app_tokens', 'contact', 'contact_phone', 'contact_email', 'users',
				'partner_api_tracking', 'distance_matrix', 'lat_long', 'contact_profile', 'api_tracking'
			),
			"5"	 => array(
				'vendor_stats', 'vendor_driver', 'booking_temp', 'booking', 'booking_vendor_request', 'booking_cab',
				'booking_route', 'booking_invoice', 'booking_pay_docs', 'booking_user', 'booking_track', 'booking_pref',
				'booking_track_log', 'booking_add_info', 'payment_gateway', 'vehicle_docs', 'document', 'drivers', 'vehicles',
				'vendors', 'vendor_pref', 'vendor_agreement', 'location', 'api_tracking'
			),
			"6"	 => array()
		);

		if (count($arrTable[date('w')]) > 0)
		{
			$arrSelectedTable = $arrTable[date('w')];
			foreach ($arrSelectedTable as $tableName)
			{
				try
				{
					if (date('H') >= 6)
					{
						break;
					}

					Logger::info("OPTIMIZE TABLE " . $database . ".`" . $tableName . '`');
					DBUtil::execute("OPTIMIZE TABLE " . $database . ".`" . $tableName . '`');
				}
				catch (Exception $ex)
				{
					Logger::exception($ex);
				}
			}
		}

		// Every Tuesday will be optimize the rest of the table
		if (date('w') == 2)
		{
			$mergedTable	 = array_merge($arrTable['2'], $arrTable['3'], $arrTable['4'], $arrTable['5'], $arrIgnoreTable);
			$sql			 = "SELECT table_name FROM information_schema.tables WHERE table_schema  ='$database'";
			$restTableList	 = DBUtil::query($sql, DBUtil::SDB());
			foreach ($restTableList as $tableName)
			{
				if (!in_array($tableName['table_name'], $mergedTable))
				{
					try
					{
						if (date('H') >= 6)
						{
							break;
						}

						Logger::info("OPTIMIZE TABLE " . $database . ".`" . $tableName['table_name'] . '`');
						DBUtil::execute("OPTIMIZE TABLE " . $database . ".`" . $tableName['table_name'] . '`');
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
				}
			}
		}
		// Every 1 day of the Month
		if (date('Y-m-01') == date('Y-m-d'))
		{
			foreach ($arrIgnoreTable as $tableName)
			{
				try
				{
					if (date('H') >= 6)
					{
						break;
					}

					Logger::info("OPTIMIZE TABLE " . $database . ".`" . $tableName . '`');
					DBUtil::execute("OPTIMIZE TABLE " . $database . ".`" . $tableName . '`');
				}
				catch (Exception $ex)
				{
					Logger::exception($ex);
				}
			}
		}
		Logger::info("OptimizeSelectedTables action ends");
	}

	/**
	 * Function for Populating Top Demand Routes
	 */
	public function actionPopulateTopDemandRoutes()
	{
		AgentApiTracking::populateTopDemandRoutes();
	}

	public function actionUpdateDDBPStatus()
	{
		$status = DynamicPriceSurge::model()->updateDDBPStatus();
		if ($status)
		{
			echo "Successfully updated";
		}
	}

	public function actionUpdatePartnerStateData()
	{
		$record = PartnerApiTracking::model()->countPartnerAPIQuoteBooking();
		if ($record)
		{
			echo "Record added successfully";
		}
	}

	public function actionCheckCurl()
	{
		$apiURL = 'http://cabs-internal.makemytrip.com/updateVendorTripDetails';

		$ch				 = curl_init($apiURL);
		$jsonData		 = '{"type":"vendorTripRequest","booking_id":"NC751911844640846","vendor_booking_id":"900255","odometer_start_reading":"0","trip_start_timestamp":"2019-03-06 16:07:07"}';
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'auth-id: GOZO',
			'auth-token: f2c8adff-5f4b-4e54-98fe-678129329ad9')
		);
		echo $jsonResponse	 = curl_exec($ch);
		print_r(curl_getinfo($ch));

		//	print_r($jsonResponse);
//		return $jsonResponse;
	}

	public function actionNotificationForVendorUsingDriverApp()
	{
		$message	 = "You will Improve your chances of winning bids by 50% if you simply get your Drivers to use Gozo Driver app when serving bookings. Driver app usage will become mandatory very soon!";
		$title		 = "Please get your drivers to use Driver app!";
		$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
		$vendorModel = Vendors::model()->getNotificationForVendorUsingDriverApp();
		foreach ($vendorModel as $model)
		{
			AppTokens::model()->notifyVendor($model['vnd_id'], $payLoadData, $message, $title);
		}
	}

	public function actionBroadcastMsgToUser()
	{
		$message = "RENT a car for AIRPORT TRANSFER or Book an OUTSTATION CAB in any city at aaocab.com. Amazing rates & 20 % additional CASH OFF. Call NOW 9051877000.";
		$title	 = "Important Notification";

		$limit		 = 3000;
		$maxLimit	 = 15000;
		for ($x = 12000; $x < $maxLimit; $x += $limit)
		{
			echo $sql	 = "SELECT DISTINCT users.user_id 
					FROM `app_tokens` 
					INNER JOIN `users` ON users.user_id=app_tokens.apt_user_id  
					AND app_tokens.apt_user_type=1 
					AND app_tokens.apt_status=1 
					AND app_tokens.apt_logout IS NULL 
					AND app_tokens.apt_last_login > DATE_SUB(NOW(), INTERVAL 90 DAY) 
					where usr_active = 1 
					GROUP BY app_tokens.apt_user_id 
					ORDER BY apt_last_login DESC 
					LIMIT $x, $limit";
//AND app_tokens.apt_user_id = 129215 
			$rows	 = Yii::app()->db->createCommand($sql)->queryAll();

			if (count($rows) <= 0)
			{
				break;
			}

			echo "\nTotal Count == " . $rowCount = count($rows);

			$i = 0;
			if (count($rows) > 0)
			{
				foreach ($rows as $row)
				{
					$i++;
					$notificationId	 = substr(round(microtime(true) * 1000), -5);
					$payLoadData	 = ['EventCode' => 530, 'bookingId' => ''];
					$success		 = AppTokens::model()->notifyConsumer($row['user_id'], $payLoadData, $notificationId, $message, $title, '');
					echo "\nSent to User id:" . $row['user_id'] . "\n";
				}
			}

			echo "\nTotal Sent Count == " . $i;
		}

		echo "\nCOMPLETED actionBroadcastMsgToUser == $i";
	}

	public function actionBroadcastMsgToNonLoggedUser()
	{
		$message = "RENT a car for AIRPORT TRANSFER or Book an OUTSTATION CAB in any city at aaocab.com. Amazing rates & 20 % additional CASH OFF. Call NOW 9051877000.";
		$title	 = "Important Notification";

		$limit		 = 3000;
		$maxLimit	 = 12000;
		for ($x = 9000; $x < $maxLimit; $x += $limit)
		{
			echo $sql	 = "SELECT apt_id FROM `app_tokens` where 1 AND apt_id IN (
							SELECT MAX(apt_id) apt_id FROM `app_tokens` where 1 
							AND apt_user_type=1 
							AND apt_status=1 
							AND apt_logout IS NULL 
							AND (apt_user_id IS NULL OR apt_user_id = 0)
							AND apt_device_uuid IS NOT NULL 
							AND apt_device_token IS NOT NULL 
							AND apt_date > DATE_SUB(NOW(), INTERVAL 90 DAY) 
							GROUP BY apt_device_uuid 
						) ORDER BY apt_date DESC 
						LIMIT $x, $limit";
//AND apt_device_uuid = 'a8b4051c5bc81f1c' 
			$rows	 = Yii::app()->db->createCommand($sql)->queryAll();

			if (count($rows) <= 0)
			{
				break;
			}

			echo "\nTotal Count == " . $rowCount = count($rows);

			$i = 0;
			if (count($rows) > 0)
			{
				foreach ($rows as $row)
				{
					$i++;
					$notificationId	 = substr(round(microtime(true) * 1000), -5);
					$payLoadData	 = ['EventCode' => 530, 'bookingId' => ''];
					$success		 = AppTokens::model()->notifyNonLoggedConsumer($row['apt_id'], $payLoadData, $notificationId, $message, $title, '');
					echo "\nSent to apt_id:" . $row['apt_id'] . "\n";
				}
			}

			echo "\nTotal Sent Count == " . $i;
		}

		echo "\nCOMPLETED actionBroadcastMsgToNonLoggedUser == $i";
	}

	public function actionBroadcastMsgToVendors()
	{
		#$message	 = "As per the Government mandate from Income Tax Department, GOZO is liable to deduct a certain percentage of tax before making payment in full to the receiver. Hence we have started charging Tax Deducted at Source (TDS) to all our vendors and to avoid bulk payment we will continue charging part by part TDS for hassle free clearance from our Vendor.";
		#$title		 = "TDS is a system exist in Income Tax Department";

		$message = "TDS लेना बाध्यतामूलक है सरकार से । इसीलिए रिसीवर को पेमेन्ट देने के समय GOZO मजबूर है TDS चार्ज करने क लिए हर वेंडर से। जो आप को एकसाथ चार्ज नहीं किया जायेगा । ताकि आप आसानी से चार्ज पे कर सको ।";
		$title	 = "TDS एक सिस्टम है Income Tax Department का ।";

		$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];

		$limit		 = 3000;
		$maxLimit	 = 3000;
		for ($x = 0; $x < $maxLimit; $x += $limit)
		{
			$sql	 = "SELECT DISTINCT vnd_id FROM vendors vnd 
					INNER JOIN app_tokens apt ON vnd.vnd_id = apt.apt_entity_id AND apt.apt_user_type=2 AND apt_status=1 
					AND apt_last_login>=DATE_SUB(NOW(), INTERVAL 15 DAY) AND apt_device_token IS NOT NULL AND vnd.vnd_id = 43 
					ORDER BY apt_last_login DESC 
					LIMIT $x, $limit";
//AND vnd.vnd_id = 43
			$rows	 = Yii::app()->db->createCommand($sql)->queryAll();

			if (count($rows) <= 0)
			{
				break;
			}

			echo "\nTotal Count == " . $rowCount = count($rows);

			$i = 0;
			if (count($rows) > 0)
			{
				foreach ($rows as $row)
				{
					$i++;
					AppTokens::model()->notifyVendor($row['vnd_id'], $payLoadData, $message, $title);
					echo "\nSent to Vnd id:" . $row['vnd_id'] . "\n";
				}
			}
			echo "\nTotal Sent Count == " . $i;
		}

		echo "\nCOMPLETED actionBroadcastMsgToVendors == $i";
	}

	public function actionRemoveEmailFile()
	{
		$currentMonth			 = date("m", strtotime("-2 months"));
		$currentYear			 = date("Y");
		$sourceDirectory		 = Yii::getPathOfAlias('application') . DIRECTORY_SEPARATOR . "doc" . DIRECTORY_SEPARATOR . "mails" . DIRECTORY_SEPARATOR;
		$removesourceDirectory	 = Yii::getPathOfAlias('application') . DIRECTORY_SEPARATOR . "doc" . DIRECTORY_SEPARATOR . "mails" . DIRECTORY_SEPARATOR;
		$destinationDirectory	 = Yii::getPathOfAlias('application') . DIRECTORY_SEPARATOR . "doc2" . DIRECTORY_SEPARATOR . "mails" . DIRECTORY_SEPARATOR;
		for ($i = $currentYear; $i >= 2017; $i--)
		{
			if ($i != $currentYear)
			{
				$currentMonth = 12;
			}
			for ($j = $currentMonth; $j >= 1; $j--)
			{
				$source			 = $sourceDirectory . $i . DIRECTORY_SEPARATOR . str_pad($j, 2, '0', STR_PAD_LEFT);
				$removesource	 = $removesourceDirectory . $i;
				if (is_dir($source))
				{
					$destination = $destinationDirectory . $i;
					@mkdir($destination);
					$destination .= DIRECTORY_SEPARATOR . str_pad($j, 2, '0', STR_PAD_LEFT);
					@mkdir($destination);
					$this->copyDirectory($source, $destination);
				}
			}
			if (count(glob($removesource . DIRECTORY_SEPARATOR . "*", GLOB_BRACE)) == 0)
			{
				$this->removeDirectory($removesource);
			}
		}
	}

	public function copyDirectory($src, $dst)
	{
		$dir	 = opendir($src);
		@mkdir($dst);
		while ($file	 = readdir($dir))
		{
			if (( $file != '.' ) && ( $file != '..' ))
			{
				if (is_dir($src . DIRECTORY_SEPARATOR . $file))
				{
					$this->copyDirectory($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
				}
				else
				{
					copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
					$this->removeDirectory($src . DIRECTORY_SEPARATOR . $file);
				}
			}
		}
		$this->removeDirectory($src);
		closedir($dir);
	}

	function removeDirectory($path)
	{
		$files = glob($path . '/*');
		foreach ($files as $file)
		{
			is_dir($file) ? $this->removeDirectory($file) : unlink($file);
		}
		rmdir($path);
		return;
	}

	public function actionRefreshProfitablitySurge()
	{
		ProfitabilitySurge::setData();
	}

	public function actionPopulateVendorTDS()
	{
		Logger::create("command.system.PopulateVendorTDS Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		Logger::create("PopulateVendorTDS Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		VendorBalance::getVendorTDS();
		Logger::create("PopulateVendorTDS Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		Logger::create("command.system.PopulateVendorTDS end Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
	}

	public function actionProcessVendorTDS()
	{
		Logger::create("command.system.ProcessVendorTDS Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		Logger::create("ProcessVendorTDS Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		VendorBalance::addVendorTDS();
		Logger::create("ProcessVendorTDS Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		Logger::create("command.system.ProcessVendorTDS end Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
	}

	public function actionNotificationVendorTDS()
	{
		$sql	 = "SELECT 
					atd1.adt_trans_ref_id AS vnd_id,
					sum(if(atd.adt_ledger_id = 37, (-1 * atd.adt_amount), 0)) AS tdspaid
					FROM     account_trans_details atd
					INNER JOIN account_transactions act
					ON atd.adt_trans_id = act.act_id AND atd.adt_active = 1 AND act.act_active = 1 AND atd.adt_type IN (2) AND atd.adt_ledger_id IN  (37)
					INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id AND atd1.adt_active = 1 AND atd1.adt_ledger_id = 14
					WHERE    act.act_date BETWEEN '2019-04-01 00:00:00' AND '2019-07-01 00:00:00'
					GROUP BY atd1.adt_trans_ref_id
					ORDER BY vnd_id ASC";
		$result	 = DBUtil::queryAll($sql, DBUtil::SDB());

		#$message1		 = "TDS of ₹10 (Trip Purchased: ₹100) has been deducted for the month Apr'19 - Jun'19'";
		#$payLoadData1	 = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
		#$success		 = AppTokens::model()->notifyVendor(43, $payLoadData1, $message1, "TDS Notification");

		$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];

		foreach ($result as $value)
		{
			$message = "TDS of ₹" . $value['tdspaid'] . " (Trip Purchased: ₹" . ($value['tdspaid'] * 100) . ") has been deducted for the month Apr'19 - Jun'19'";
			$success = AppTokens::model()->notifyVendor($value['vnd_id'], $payLoadData, $message, "TDS Notification");
			echo "\nSent to Vnd id:" . $value['vnd_id'] . "\n";
		}
	}

	public function actionUpdateDDBP()
	{
		$updateData = DynamicPriceSurge::model()->updateNewDDBPRouteData();
	}

	public function actionTripPurchase()
	{
		$sql = "SELECT bcb_id, bcb_vendor_id, bcb_vendor_amount, createDate
				FROM (
					SELECT bcb_id, count(*) as cnt, GROUP_CONCAT(bkg_id SEPARATOR ', ') as bkgs, SUM(bkg_total_amount - bkg_service_tax) as GMV, 
						SUM(bkg_gozo_amount) as gozoAmount, (SUM(bkg_total_amount - bkg_service_tax - bkg_vendor_amount - IF(agents.agt_type=2,IF(agents.agt_commission_value=1,ROUND(bkg_base_amount*agents.agt_commission*0.01), agents.agt_commission),0))) as gozoUnmatchedAmount, 
						MAX(bkg_pickup_date) as createDate, SUM(bkg_total_amount) as totalAmount, bcb_vendor_amount, bcb_vendor_id, SUM(bkg_total_amount *0.80) as BkgVendorAmount, bcb_pending_status

					FROM `booking_cab` INNER JOIN booking ON booking_cab.bcb_id=booking.bkg_bcb_id AND bkg_status IN (2,3,5,6,7)  AND bkg_pickup_date BETWEEN '2019-04-01' AND '2020-03-31'
					INNER JOIN booking_invoice ON booking.bkg_id=booking_invoice.biv_bkg_id
					LEFT JOIN agents ON bkg_agent_id=agents.agt_id 
					GROUP BY bcb_id HAVING DATE(createDate)<=DATE_ADD(NOW(), INTERVAL 96 HOUR)) a
					LEFT JOIN (
				   SELECT DISTINCT atd.adt_trans_ref_id FROM account_transactions act
				INNER JOIN  account_trans_details atd ON act.act_id=atd.adt_trans_id AND atd.adt_ledger_id=22 AND act.act_active=1 AND atd.adt_active=1
				INNER JOIN  account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1 AND atd1.adt_ledger_id<>atd.adt_ledger_id
				INNER JOIN account_ledger al ON al.ledgerId=atd1.adt_ledger_id AND act_date BETWEEN '2019-01-01' AND '2020-05-31'
					) b ON a.bcb_id=adt_trans_ref_id
					WHERE adt_trans_ref_id IS NULL AND bcb_vendor_id=33653 AND bcb_pending_status<>1

					";

		$res = DBUtil::query($sql);

		foreach ($res as $row)
		{
			$tripId			 = $row["bcb_id"];
			$vendorId		 = $row["bcb_vendor_id"];
			$date			 = $row["createDate"];
			$purchaseAmount	 = $row["bcb_vendor_amount"];
			$success		 = false;
			$trans			 = DBUtil::beginTransaction();
			try
			{
				AccountTransactions::model()->removeTripPurchaseAmount($tripId, $vendorId);
				$accountTrans = AccountTransactions::model()->find('act_type=5 AND act_ref_id=:trip AND act_status=1 AND act_active=1', ['trip' => $tripId]);
				if ($accountTrans == '')
				{
					$datetime			 = ($date != '') ? $date : new CDbExpression('NOW()');
					$remarks			 = "Trip purchased";
					$accTransDetArr		 = [];
					$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_TRIP, $tripId, Accounting::LI_TRIP, $purchaseAmount, $remarks);
					$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, (-1 * $purchaseAmount));
					AccountTransactions::model()->add($accTransDetArr, $datetime, $purchaseAmount, $tripId, Accounting::AT_TRIP, $remarks, UserInfo::model());

					DBUtil::commitTransaction($trans);
					$success = true;
					Logger::info("{$tripId} : {$purchaseAmount} : {$date}");
				}
			}
			catch (Exception $e)
			{
				Logger::create("Failed to add Trip Amount by operator ID: $vendorId ({$e->getMessage()})", CLogger::LEVEL_ERROR);
				DBUtil::rollbackTransaction($trans);
				$desc = "Failed to add Trip Amount by operator ID: $vendorId ({$e->getMessage()})";
				Logger::info($desc);
			}
		}
	}

	public function actionUpdateCityStats()
	{
		$check = Filter::checkProcess("system updateCityStats");
		if (!$check)
		{
			return;
		}
		CitiesStats::updateStats();
	}

	public function actionCacheGC()
	{
		echo "Clearing expired cache \n";
		Yii::app()->cache->gc();
		echo "Expired cache cleared";
	}

	public function actionSetCallingDurationMedian()
	{
		ServiceCallQueue::updateCallingDurationMedian();
	}

	public function actionUpdateShiftTimeOver()
	{
		AdminOnoff::model()->updateOpsUserShiftTimeOver();
	}

	public function actionSCqBackupScript()
	{
		ServiceCallQueue::SCqBackupScript();
	}

	public function actionScqPriorityScore()
	{
		$check = Filter::checkProcess("system scqPriorityScore");
		if (!$check)
		{
			return;
		}
		$result = ServiceCallQueue::ScqPriorityScore();
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::updatePriorityScore($row);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionCloseAllPaymentFollowup()
	{
		$check = Filter::checkProcess("system CloseAllPaymentFollowup");
		if (!$check)
		{
			return;
		}
		$result = ServiceCallQueue::getAllPaymentFollowup();
		foreach ($result as $row)
		{
			try
			{
				if ($row['scq_related_bkg_id'] == "" || $row['bkg_reconfirm_flag'] == 1 || $row['quoteExpired'] == 1 || $row['statusExpired'] == 1 || $row['paymentExpired'] == 1)
				{
					ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "Closed Payment followup because booking has been confirmed");
				}
				if (($row['scq_related_bkg_id'] != "" && $row['bkg_agent_id'] == null) && ($row['bkg_reconfirm_flag'] == 1 || $row['quoteExpired'] == 1 || $row['statusExpired'] == 1 || $row['paymentExpired'] == 1))
				{
					ServiceCallQueue::autoCloseRelatedLeadQuote($row['scq_related_bkg_id'], "7");
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionAutoVendorApproval()
	{
		$check = Filter::checkProcess("system autoVendorApproval");
		if (!$check)
		{
			return;
		}

		$result = Vendors::getAllVendorApproval();
		foreach ($result as $row)
		{
			try
			{
				$count = ServiceCallQueue::checkDuplicateAutoApprovalForVendor($row['vnd_id'], ServiceCallQueue::TYPE_VENDOR_APPROVAl);
				if ($count == 0)
				{
					ServiceCallQueue::autoVendorApproval($row);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all the Dispatch  queue that is older than 1 days from now
	 */
	public function actionAutoCloseDispatchQueue()
	{
		$check = Filter::checkProcess("system autoCloseDispatchQueue");
		if (!$check)
		{
			return;
		}
		$result = ServiceCallQueue::getAutoCloseDispatch(ServiceCallQueue::TYPE_DISPATCH, 10);
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "CBR expired. No action taken");
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all the upsell  queue that is older than 1 days from now
	 */
	public function actionAutoCloseUpSellQueue()
	{
		$check = Filter::checkProcess("system autoCloseUpSellQueue");
		if (!$check)
		{
			return;
		}
		$queueId = ServiceCallQueue::TYPE_UPSELL . "," . ServiceCallQueue::TYPE_UPSELL_UPPERTIER;
		$result	 = ServiceCallQueue::getAllDataByQueueId($queueId, 10);
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "CBR expired. No action taken");
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all the B2B Post Pickup  queue that is older than 1 days from now
	 */
	public function actionAutoCloseB2BPostPickupQueue()
	{
		$check = Filter::checkProcess("system autoCloseB2BPostPickupQueue");
		if (!$check)
		{
			return;
		}
		$result = ServiceCallQueue::getAllDataByQueueId(ServiceCallQueue::TYPE_B2B_POST_PICKUP, 10);
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "CBR expired. No action taken");
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionSendMsgToVendorNotLoggedInLastDays()
	{
		SmsLog::sendMsgToVendorNotLoggedInLastDays();
	}

	/**
	 * This function will return all the Booking at risk(BAR)  queue that is older than 1 days from now
	 */
	public function actionAutoCloseBARQueue()
	{
		$check = Filter::checkProcess("system autoCloseBARQueue");
		if (!$check)
		{
			return;
		}
		$queueId = ServiceCallQueue::TYPE_BAR . "," . ServiceCallQueue::TYPE_AIRPORT_DAILYRENTAL;
		$result	 = ServiceCallQueue::getAllDataByQueueId($queueId, 4);
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "CBR expired. No action taken");
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionAutoFURFbg()
	{
		$check = Filter::checkProcess("system autoFURFbg");
		if (!$check)
		{
			return;
		}
		$result = BookingSub::getFbgBookings();
		foreach ($result as $row)
		{
			try
			{
				$count = ServiceCallQueue::countQueueByBkgId($row['bkg_id'], ServiceCallQueue::TYPE_FBG);
				if ($count == 0)
				{
					ServiceCallQueue::autoFURFBG($row['bkg_id']);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all the Image for IRead
	 */
	public function actiongetAllDocsImage()
	{
		$result = Ireaddocs::getAllDocsImage();
		foreach ($result as $row)
		{
			try
			{
				if ($row['type'] == 1)
				{
					Ireaddocs::add($docId	 = $row['docId'], $docType = 1, $type	 = 1);
				}
				else if ($row['type'] == 2 && $row['docType'] == 107)
				{
					Ireaddocs::add($docId	 = $row['docId'], $docType = 2, $type	 = 3);
				}
				else if ($row['type'] == 2 && in_array($row['docType'], array(8, 9)))
				{
					Ireaddocs::add($docId	 = $row['docId'], $docType = 2, $type	 = 2);
				}
				else if ($row['type'] == 3)
				{
					Ireaddocs::add($docId	 = $row['docId'], $docType = 3, $type	 = 2);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all the Image for IRead
	 */
	public function actiongetAllImageForIread()
	{
		$check = Filter::checkProcess("system getAllImageForIread;");
		if (!$check)
		{
			return;
		}
		$result	 = Ireaddocs::getAllImage($status	 = 1);
		foreach ($result as $row)
		{
			try
			{
				$irdId					 = $row['ird_id'];
				$irdType				 = $row['ird_type'];
				$irdDocType				 = $row['ird_doc_type'];
				$imageUrl				 = $row['ird_gimage_url'];
				$modelIread				 = Ireaddocs::model()->findByPk($irdId);
				$modelIread->ird_status	 = 2;
				if ($modelIread->save())
				{
					switch ($irdType)
					{
						case 1:
							$response	 = IRead::readOCR($irdId, $imageUrl, 'doc');
							Ireaddocs::updateIread($irdId, $response);
							break;
						case 2:
							$response	 = IRead::readOCR($irdId, $imageUrl, 'number');
							Ireaddocs::updateIread($irdId, $response);
							break;
						case 3:
							$response	 = IRead::detectFaceMask($irdId, $imageUrl);
							Ireaddocs::updateIread($irdId, $response);
							break;
					}
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all the booking which FOR rDemMisFire
	 */
	public function actiongetAllBookingForDemMisFire()
	{
		$check = Filter::checkProcess("system getAllBookingForDemMisFire");
		if (!$check)
		{
			return;
		}
		$result = Booking::getAllBookingForDemMisFire();
		foreach ($result as $row)
		{
			try
			{
				$bkgId			 = $row['bkg_id'];
				$zoneId			 = $row['stt_zone'];
				$serviceResult	 = ServiceCallQueue::countDemMisFireByBkgId($bkgId, 1);
				if ($serviceResult == 0)
				{
					switch ($zoneId)
					{
						case 1:
							//assignNorth  30
							ServiceCallQueue::autoFURForDemMisFire($bkgId, 30);
							break;
						case 2:
							//assignWest  29
							ServiceCallQueue::autoFURForDemMisFire($bkgId, 29);
							break;
						case 3:
							//assignCentral  36
							ServiceCallQueue::autoFURForDemMisFire($bkgId, 36);
							break;
						case 4:
						case 7:
							//assignSouth  27
							ServiceCallQueue::autoFURForDemMisFire($bkgId, 27);
							break;
						case 5:
							//assignEast  28
							ServiceCallQueue::autoFURForDemMisFire($bkgId, 28);
							break;
						case 6:
							//assignNorthEast  28
							ServiceCallQueue::autoFURForDemMisFire($bkgId, 28);
							break;
					}
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all DemMisFire  that is older than 1 days from now
	 */
	public function actionAutoCloseDemMisFire()
	{
		$check = Filter::checkProcess("system autoCloseDemMisFire");
		if (!$check)
		{
			return;
		}
		$result = ServiceCallQueue::getAllDataByDemMisFire();
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "CBR expired. No action taken");
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all Auto Fur for Rating  that is older than 1 days from now
	 */
	public function actionAutoFurForRating()
	{
		$check = Filter::checkProcess("system autoFurForRating");
		if (!$check)
		{
			return;
		}
		$date		 = date('Y-m-d', strtotime(date('Y-m-d') . '-1 day'));
		$fromdate	 = $date . " 00:00:00";
		$todate		 = $date . " 23:59:59";
		$result		 = Ratings::getAutoFurRating($fromdate, $todate);
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::autoFURRatingForBooking($row['bkg_id'], $row['bkg_user_id'], $row['bkg_country_code'], $row['bkg_contact_no']);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all cancelling auto FUR For Rating
	 */
	public function actionAutoCloseAutoFurForRating()
	{
		$check = Filter::checkProcess("system autoCloseAutoFurForRating");
		if (!$check)
		{
			return;
		}
		$result = ServiceCallQueue::getAllDataFORAutoFurForRating();
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "CBR expired. No action taken");
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all attendance
	 */
	public function actionAdminAttendance()
	{
		$result = Admins::model()->findAllActive();
		foreach ($result as $row)
		{
			$data		 = AdminOnoff::model()->getByAdmId($row['adm_id'], $condition	 = true);
			if ($data['ado_status'] == 1)
			{
				AdminOnoff::model()->addInOutEntry($row['adm_id'], $condition = true);
			}
		}
		foreach ($result as $row)
		{
			try
			{
				AttendanceStats::getAdminAttendance($row['adm_id']);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionShiftTimeOver()
	{
		AdminOnoff::model()->ShiftTimeOver();
	}

	public function actionUpdateDriverZoneMaster()
	{
		$driverId = 0;
		DriverMasterZones::model()->updateDetails($driverId);
	}

	/**
	 * This function is used for inserting lead  Into service call queue
	 */
	public function actionAddLead()
	{
		$check = Filter::checkProcess("system AddLead");
		if (!$check)
		{
			return;
		}
		try
		{
			$eligibleScore		 = 105;
			$configCount		 = (int) Config::get('SCQ.maxLeadAllowed');
			$serviceCallCount	 = ServiceCallQueue::getLeadCount(true, $eligibleScore);
			Logger::info("\n*********************************** ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
			if (ServiceCallQueue::getLeadCount(true, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				Logger::info("\n*********************************** Inside ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
				ServiceCallQueue::updatePendingLeadsCron(true, $eligibleScore);
			}

			$serviceCallCount = ServiceCallQueue::getLeadCount(false, $eligibleScore);
			Logger::info("\n*********************************** ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore FALSE PART *********************************************\n");
			if (ServiceCallQueue::getLeadCount(false, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				Logger::info("\n*********************************** Inside ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
				ServiceCallQueue::updatePendingLeadsCron(false, $eligibleScore);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			Logger::info("\n*********************************** zone_surge_global Error Start *********************************************\n");
			Logger::info($ex->getMessage());
			Logger::info("\n*********************************** zone_surge_global Error Ends *********************************************\n");
		}

		try
		{
			$eligibleScore		 = 100;
			$serviceCallCount	 = ServiceCallQueue::getLeadCount(true, $eligibleScore);
			Logger::info("\n*********************************** ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
			if (ServiceCallQueue::getLeadCount(true, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				Logger::info("\n*********************************** Inside ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
				ServiceCallQueue::updatePendingLeadsCron(true, $eligibleScore);
			}

			$serviceCallCount = ServiceCallQueue::getLeadCount(false, $eligibleScore);
			Logger::info("\n*********************************** ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore FALSE PART *********************************************\n");
			if (ServiceCallQueue::getLeadCount(false, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				Logger::info("\n*********************************** Inside ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
				ServiceCallQueue::updatePendingLeadsCron(false, $eligibleScore);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			Logger::info("\n*********************************** zone_surge_global Error Start *********************************************\n");
			Logger::info($ex->getMessage());
			Logger::info("\n*********************************** zone_surge_global Error Ends *********************************************\n");
		}
		try
		{
			$eligibleScore		 = 80;
			$serviceCallCount	 = ServiceCallQueue::getLeadCount(true, $eligibleScore);
			Logger::info("\n*********************************** ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
			if (ServiceCallQueue::getLeadCount(true, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				Logger::info("\n*********************************** Inside ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
				ServiceCallQueue::updatePendingLeadsCron(true, $eligibleScore);
			}

			$serviceCallCount = ServiceCallQueue::getLeadCount(false, $eligibleScore);
			Logger::info("\n*********************************** ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore FALSE PART *********************************************\n");
			if (ServiceCallQueue::getLeadCount(false, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				Logger::info("\n*********************************** Inside ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
				ServiceCallQueue::updatePendingLeadsCron(false, $eligibleScore);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			Logger::info("\n*********************************** zone_surge_global Error Start *********************************************\n");
			Logger::info($ex->getMessage());
			Logger::info("\n*********************************** zone_surge_global Error Ends *********************************************\n");
		}
	}

	/**
	 * This function used to take details of document expired of vehicle,vendor and driver
	 */
	public function actionDocumentsExpNotification()
	{
		$vehicleData = Vehicles::model()->getExpairedDocuments();
		$driverData	 = Drivers::model()->getExpairedDocuments();
		$vendorData	 = Vendors::model()->getExpairedDocuments();
	}

	/**
	 * This function used to take details of document expired of vehicle,vendor and driver after 10 days
	 */
	public function actionDocExpNotification10days()
	{
		$vehicleData = Vehicles::model()->getExpairedDocumentsWithTenDays();
		$driverData	 = Drivers::model()->getExpairedDocumentsWithTenDays();
		$vendorData	 = Vendors::model()->getExpairedDocumentsWithTenDays();
	}

	/**
	 * This function is used for mark lead close if its create hour is more than 24 hours other wise it will decrease  the priority_score by  half for every 8 hrs
	 */
	public function actionMarkLeadClose()
	{
		$result = ServiceCallQueue::getAllDataByLead();
		foreach ($result as $row)
		{
			try
			{
				if ($row['scqCreateHours'] < 2 && $row['scqCreateHours'] >= 1)
				{
					$score = $row['scq_priority_score'] * 0.75;
					ServiceCallQueue::updateScqPriorityScore($row['scq_id'], $score);
				}
				else if ($row['scqCreateHours'] < 4 && $row['scqCreateHours'] >= 2)
				{
					$score = $row['scq_priority_score'] * 0.50;
					ServiceCallQueue::updateScqPriorityScore($row['scq_id'], $score);
				}
				else if ($row['scqCreateHours'] >= 6)
				{
					ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "CBR expired. No action taken");
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function is used update the vendor ids base on zone id
	 */
	public function actionLocationStatsHourly()
	{
		// 1=>Location Zone,2=>Location Home Zone,3=>Zone With Vendor logged into app ,4=> Zone With Vendor approved but not logged into app
		$locationStatsDetails = LocationStats::getVendorList(1);
		foreach ($locationStatsDetails as $value)
		{
			try
			{
				LocationStats::add($value);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}

		// By Location vendor home zone
		$locationStatsDetails = LocationStats::getVendorList(2);
		foreach ($locationStatsDetails as $value)
		{
			try
			{
				LocationStats::add($value);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function is used update the vendor ids base on zone id
	 */
	public function actionLocationStatsDaily()
	{
		//3=>Zone With Vendor logged into app,4=> Zone With Vendor approved
		$locationStatsDetails = Zones::getVendorListByApp();
		foreach ($locationStatsDetails as $value)
		{
			try
			{
				LocationStats::add($value);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}

		// By Location vendor home zone
		$locationStatsDetails = Zones::getVendorApprovedList();
		foreach ($locationStatsDetails as $value)
		{
			try
			{
				LocationStats::add($value);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function is used auto approval of the document 
	 */
	public function actionAutoFURDocumentApproval()
	{
		$result = Vendors::getAllVendorForDocumentApproval();
		foreach ($result as $row)
		{
			try
			{
				//modify vendor status:
				Vendors::modifyReadytoApprove($row['vnd_id']);
				$count = ServiceCallQueue::checkDuplicateDocumetApprovalForVendor($row['vnd_id'], ServiceCallQueue::TYPE_DOCUMENT_APPROVAL);
				if ($count == 0)
				{
					ServiceCallQueue::autoFURDocumentApproval($row);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function is used auto approval of the vendor from inventory shortage report
	 */
	public function actionAutoVendorApprovalOnInventoryShortage()
	{
		$check = Filter::checkProcess("system AutoVendorApprovalOnInventoryShortage");
		if (!$check)
		{
			return;
		}
		$result = Zones::getInventoryShortageZone();
		foreach ($result as $row)
		{
			/*			 * ********* On the based of From Zone ID ************* */
			$results = Vendors::getAllVendorApprovalOnInventoryShortage($row['fromZoneId']);
			foreach ($results as $rows)
			{
				try
				{
					$count = ServiceCallQueue::checkDuplicateAutoApprovalForVendor($rows['vnd_id'], ServiceCallQueue::TYPE_VENDOR_APPROVAl);
					if ($count == 0)
					{
						$returnSet = ServiceCallQueue::autoVendorApproval($rows);
						if ($returnSet->isSuccess())
						{
							$desc = "Service Request has been generated for " . $rows['vnd_id'];
							VendorsLog::model()->createLog($rows['vnd_id'], $desc, UserInfo::getInstance(), VendorsLog::VENDOR_SR, false, false);
						}
					}
				}
				catch (Exception $ex)
				{
					Logger::exception($ex);
				}
			}

			/*			 * ***************** On the based of To Zone ID ************* */
			$results = Vendors::getAllVendorApprovalOnInventoryShortage($row['toZoneId']);
			foreach ($results as $rows)
			{
				try
				{
					$count = ServiceCallQueue::checkDuplicateAutoApprovalForVendor($rows['vnd_id'], ServiceCallQueue::TYPE_VENDOR_APPROVAL_ZONE_BASED_INVENTORY);
					if ($count == 0)
					{
						$returnSet = ServiceCallQueue::autoVendorApproval($rows);
						if ($returnSet->isSuccess())
						{
							$desc = "Service Request has been generated for " . $rows['vnd_id'];
							VendorsLog::model()->createLog($rows['vnd_id'], $desc, UserInfo::getInstance(), VendorsLog::VENDOR_SR, false, false);
						}
					}
				}
				catch (Exception $ex)
				{
					Logger::exception($ex);
				}
			}
		}
	}

	/**
	 * This function is used to send notification to multiple entities
	 */
	public function actionSendNotification()
	{
		$res = BookingScheduleEvent::getEventList(107);
		foreach ($res as $row)
		{
			try
			{
				switch ($row['bse_event_id'])
				{
					case BookingScheduleEvent::SEND_NOTIFICATION_DATA;
						$bseModel	 = BookingScheduleEvent::model()->findByPk($row['bse_id']);
						$entityIds	 = explode(",", json_decode($row['bse_addtional_data'])->vendorIds);
						$model		 = Booking::model()->findByPk($row['bse_bkg_id']);
						$tripId		 = $model->bkg_bcb_id;
						$success	 = BookingCab::gnowNotify($tripId, $entityIds);
						if ($success)
						{
							$bseModel->bse_event_status = 1;
							$bseModel->save();
						}
						break;
					case Booking::CODE_VENDOR_GOZONOW_BOOKING_ALLOCATED;
						break;
				}
			}
			catch (Exception $ex)
			{
				$bseModel->bse_event_status	 = 2;
				$bseModel->bse_last_error	 = $ex->getMessage();
				$bseModel->bse_err_count	 = $bseModel->bse_err_count == 0 ? 1 : $bseModel->bse_err_count + 1;
				$bseModel->save();
				Logger::exception($ex);
			}
		}
	}

	public function actionMarkZoneType()
	{
		$check = Filter::checkProcess("system MarkZoneType");
		if (!$check)
		{
			return;
		}

		$result = BookingPref::getBookingZoneType();
		foreach ($result as $row)
		{
			try
			{
				$fromCity					 = $row['bkg_from_city_id'];
				$toCity						 = $row['bkg_to_city_id'];
				$scv_id						 = $row['bkg_vehicle_type_id'];
				$tripType					 = $row['bkg_booking_type'];
				$res						 = DynamicZoneSurge::getDZPPZoneType($fromCity, $toCity, $scv_id, $tripType);
				$model						 = BookingPref::model()->getByBooking($row['bkg_id']);
				$rowIdentifier				 = DynamicZoneSurge::getRowIdentifier($fromCity, $toCity, $scv_id, $tripType);
				$rowIdentifierByDemandZone	 = DynamicZoneSurge::getRowIdentifierByDemandZone($rowIdentifier);
				$model->bpr_zone_type		 = $rowIdentifierByDemandZone > 0 ? 0 : ($res['dzs_zone_type'] != null ? $res['dzs_zone_type'] : 3);
				$model->bpr_row_identifier	 = DynamicZoneSurge::getRowIdentifier($fromCity, $toCity, $scv_id, $tripType);
				$model->bpr_zone_identifier	 = DynamicZoneSurge::getZoneIdentifier($fromCity, $toCity);
				if (!$model->save())
				{
					Logger::writeToConsole(json_encode($model->errors));
				}
				if ($row['ddbpv2SurgeFactor'] > 1 && ($row['goingRegularRatio'] > 1.2 || $row['askingGoingRatio'] > 1.30))
				{
					IRead::setRowIdentifierRequest(array('rowIdentifier' => $row['rowIdentifier']));
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionALLMarkZoneType()
	{
		$result = BookingPref::allgetBookingZoneType();
		foreach ($result as $row)
		{
			try
			{
				$fromCity				 = $row['bkg_from_city_id'];
				$toCity					 = $row['bkg_to_city_id'];
				$scv_id					 = $row['bkg_vehicle_type_id'];
				$tripType				 = $row['bkg_booking_type'];
				$res					 = DynamicZoneSurge::getDZPPZoneType($fromCity, $toCity, $scv_id, $tripType);
				$model					 = BookingPref::model()->getByBooking($row['bkg_id']);
				$model->bpr_zone_type	 = ($res['dzs_zone_type'] != null) ? $res['dzs_zone_type'] : 3;
				if (!$model->save())
				{
					Logger::writeToConsole(json_encode($model->errors));
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionSendCabDriverDetailsToCustomer()
	{
		$result = Booking::model()->getDetailsOfCabDriverForCustomer();
		if (count($result) > 0)
		{
			foreach ($result as $row)
			{
				if ($row['bkg_agent_id'] > 0 && $row['bkg_agent_id'] != 1249)
				{
					$messageDetails	 = BookingMessages::messageCommunicationAgentSettings($row['bkg_id'], AgentMessages::CAB_DRIVER_DETAIL);
					$consumerArr	 = $messageDetails[UserInfo::TYPE_CONSUMER];
					$adminArr		 = $messageDetails[UserInfo::TYPE_ADMIN];
					$agentArr		 = $messageDetails[UserInfo::TYPE_AGENT];
					if (!empty($consumerArr))
					{
						Drivers::notifyDriverDetailsToCustomer($row['bkg_id'], 0, null, $consumerArr, UserInfo::TYPE_CONSUMER);
					}
					if (!empty($adminArr))
					{
						Drivers::notifyDriverDetailsToCustomer($row['bkg_id'], 0, null, $adminArr, UserInfo::TYPE_ADMIN);
					}
					if (!empty($agentArr))
					{
						Drivers::notifyDriverDetailsToCustomer($row['bkg_id'], 0, null, $agentArr, UserInfo::TYPE_AGENT);
					}
				}
				else
				{
					Drivers::notifyDriverDetailsToCustomer($row['bkg_id'], 0);
				}
			}
		}
	}

	public function actionGetAllRowIdentifier()
	{
		$begin	 = new DateTime("2019-01-01");
		$end	 = new DateTime("2022-04-25");
		for ($j = $begin; $j <= $end; $j->modify('+1 day'))
		{
			$date		 = $j->format("Y-m-d");
			$fromDate	 = $date . " 00:00:00";
			$result		 = BookingPref::getBookingRowIdentifierDateWise($date);
			foreach ($result as $row)
			{
				try
				{
					$fromCity					 = $row['bkg_from_city_id'];
					$toCity						 = $row['bkg_to_city_id'];
					$scv_id						 = $row['bkg_vehicle_type_id'];
					$tripType					 = $row['bkg_booking_type'];
					$rowIdentifier				 = DynamicZoneSurge::getRowIdentifier($fromCity, $toCity, $scv_id, $tripType);
					$model						 = BookingPref::model()->getByBooking($row['bkg_id']);
					$model->bpr_row_identifier	 = $rowIdentifier;
					if (!$model->save())
					{
						Logger::writeToConsole(json_encode($model->errors));
					}
				}
				catch (Exception $ex)
				{
					Logger::exception($ex);
				}
			}
		}
	}

	/**
	 * This function is used auto fur for trip start trip (1 hour early and 1 hour after trip start)
	 */
	public function actionAutoTripStartUpperTier()
	{
		$result = Booking::getAllTripStartBooking();
		foreach ($result as $row)
		{
			try
			{
				if ($row['bkg_agent_id'] != null && $row['bkg_agent_id'] > 0)
				{
					ServiceCallQueue::autoFURTripStartedForB2BHour($row['bkg_id']);
				}
				else
				{
					ServiceCallQueue::autoFURTripStartedHour($row['bkg_id']);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function is used for updating (is_dco) flag as vendor for more than 1 car count and dco for 1 car count
	 * type = 1(dco) and type = 2(vendor)
	 */
	public function actionUpdateVendorCatType()
	{
		$data = Vendors::getAllVendorCarCount();

		if ($data->getRowCount() > 0)
		{
			foreach ($data as $row)
			{
				$vndId		 = $row['vnd_id'];
				$carCount	 = $row['totalNoOfCars'];
				$type		 = 0;
				if ($carCount > 0)
				{
					$type = ($carCount > 1) ? 2 : 1;
				}
				echo "echo =====type: {$type} vendor(2)/dco(1)=========";
				Vendors::updateVendorCategoryType($vndId, $type);
				echo "\r\nID: {$vndId}, CarCount:" . $carCount;
				echo "=========End===========";
			}
		}
	}

	public function actionRatePerKilomter()
	{
		$details = BookingInvoice::getQuoteRateKm();
		foreach ($details as $row)
		{
			try
			{
				$model							 = BookingInvoice::model()->getByBookingID($row['bkg_id']);
				$model->biv_quote_base_rate_km	 = $row['RatePerKilometer'];
				if (!$model->save())
				{
					Filter::writeToConsole(json_encode($model->errors));
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all Auto Fur for CSA having critical score greater than 0.83
	 */
	public function actionAutoFurCSA()
	{
		$check = Filter::checkProcess("system autoFurCSA");
		if (!$check)
		{
			return;
		}
		$fromdate	 = date('Y-m-d') . " 00:00:00";
		$todate		 = date('Y-m-d', strtotime(date('Y-m-d') . '+5 day')) . " 23:59:59";
		$result		 = ServiceCallQueue::getDataForCSAQueue($fromdate, $todate);
		foreach ($result as $row)
		{
			try
			{
				$count = ServiceCallQueue::countQueueByBkgId($row['bkg_id'], ServiceCallQueue::TYPE_CSA);
				if ($count == 0)
				{
					$scqId = ServiceCallQueue::getScqIdForCSA($row['bkg_id']);
					if ($scqId)
					{
						ServiceCallQueue::updateStatus($scqId, 10, 0, "CBR expired. No action taken. As because  Critcal assisgnemnt are assigned to some other team");
					}

					switch ($row['stt_zone'])
					{
						case 1:
							//assignNorth  30
//							ServiceCallQueue::autoFURForDemMisFire($bkgId, 30);
							ServiceCallQueue::addCSAQueue($row, 30);
							break;
						case 2:
							//assignWest  29
//							ServiceCallQueue::autoFURForDemMisFire($bkgId, 29);
							ServiceCallQueue::addCSAQueue($row, 29);
							break;
						case 3:
							//assignCentral  36
//							ServiceCallQueue::autoFURForDemMisFire($bkgId, 36);
							ServiceCallQueue::addCSAQueue($row, 36);
							break;
						case 4:
						case 7:
							//assignSouth  27
//							ServiceCallQueue::autoFURForDemMisFire($bkgId, 27);
							ServiceCallQueue::addCSAQueue($row, 27);
							break;
						case 5:
							//assignEast  28
//							ServiceCallQueue::autoFURForDemMisFire($bkgId, 28);
							ServiceCallQueue::addCSAQueue($row, 28);
							break;
						case 6:
							//assignNorthEast  28
//							ServiceCallQueue::autoFURForDemMisFire($bkgId, 28);
							ServiceCallQueue::addCSAQueue($row, 28);
							break;
					}
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all cancelling auto FUR For Rating
	 */
	public function actionAutoCloseCSA()
	{
		$check = Filter::checkProcess("system autoCloseCSA");
		if (!$check)
		{
			return;
		}
		$queueIds	 = ServiceCallQueue::TYPE_CSA . "," . ServiceCallQueue::TYPE_AIRPORT_DAILYRENTAL;
		$result		 = ServiceCallQueue::getAllDataByQueueId($queueIds, 4);
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "CBR expired. No action taken");
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionUpdateDynamicZoneSurge()
	{
//        Logger::info("\n*********************************** UpdateDynamicZoneSurge Start *********************************************\n");
		$begin	 = new DateTime("2021-12-31 00:00:00");
		$end	 = new DateTime("2021-12-03 00:00:00");
		for ($j = $begin; $j >= $end; $j->modify('-1 day'))
		{
			$date		 = $j->format("Y-m-d");
//            Logger::info("\n*********************************** Date For = $date loop start *********************************************\n");
			$fromDate	 = $date . " 00:00:00";
			$toDate		 = $date . " 23:59:59";
			$resultDzpp	 = DynamicZoneSurge::getDZPPROWIdentifier($date);
			foreach ($resultDzpp as $row)
			{
//                Logger::info("\n****************** dzs_id= " . $row['dzs_id'] . " *****************dzs_row_identifier= " . $row['dzs_row_identifier'] . " loop start *********************************************\n");
				try
				{
					$result				 = Booking::getBookingCountByRowIdentifierDump($row['dzs_row_identifier'], $fromDate, $toDate);
					$resultLead			 = BookingTemp::getBookingCountByRowIdentifierDump($row['dzs_row_identifier'], $fromDate, $toDate);
					$param['cntLead']	 = $resultLead > 0 ? $resultLead : 0;
					$param['cntInquiry'] = $result['cntInquiry'] > 0 ? $result['cntInquiry'] : 0;
					$param['cntCreated'] = $result['cntCreated'] > 0 ? $result['cntCreated'] : 0;
					$param['dzs_id']	 = $row['dzs_id'];
					$sql				 = "UPDATE `dynamic_zone_surge_1day` SET `dzs_cntLead` =:cntLead, `dzs_cntInquiry` =:cntInquiry, `dzs_cntCreated` = :cntCreated WHERE `dynamic_zone_surge_1day`.`dzs_id` =:dzs_id";
					DBUtil::execute($sql, $param);
				}
				catch (Exception $ex)
				{
					Logger::exception($ex);
				}
			}
		}
	}

	public function actionGenerateLedgerCsv()
	{
		$check = Filter::checkProcess("system generateLedgerCsv");
		if (!$check)
		{
			return;
		}

		$success	 = true;
		$columnNames = ["Month/Date", "From Ledger Name", "To Ledger Name", "Opening", "Debit", "Credit", "Balance"];

		$ledgerData = LedgerDataRequests::getPendingLedger();
		if (!$ledgerData)
		{
			$success = false;
			goto skip;
		}

		$ldrId				 = $ledgerData['ldr_id'];
		$model				 = LedgerDataRequests::model()->findByPk($ldrId);
		$model->ldr_status	 = 2;
		if (!$model->save())
		{
			$success = false;
			goto skip;
		}

		$arrFilters	 = json_decode($model->ldr_form_input, true);
		$grpType	 = trim($arrFilters['groupby_type']);
		$grpPeriod	 = trim($arrFilters['groupby_period']);

		#$periodFieldName = (($grpPeriod == '' || $grpPeriod == 'month') ? 'month' : $grpPeriod);
		$periodFieldName = (($grpPeriod == '' || $grpPeriod == 'month') ? 'month' : ($grpPeriod == 'all' ? 'date' : $grpPeriod));

		$columnNames = ["Month/Date", "From Ledger Name", "To Ledger Name", "Period Amount"];
		if ($grpType != '')
		{
			$columnNames = ["Id", "Name", "Month/Date", "From Ledger Name", "To Ledger Name", "Opening", "Debit", "Credit", "Balance"];
		}

		$arrData[]	 = $columnNames;
		$rows		 = AccountTransDetails::processLedgerData($arrFilters);
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$rowArray = array();
				if ($grpType != '')
				{
					$rowArray['entityId']		 = $row['adt_trans_ref_id'];
					$rowArray['entityName']		 = $row['entityName'];
					$rowArray['month']			 = $row["{$periodFieldName}"];
					$rowArray['fromLedgerName']	 = $row['fromLedgerName'];
					$rowArray['toLedgerName']	 = $row['toLedgerName'];
					$rowArray['opening']		 = (isset($row['opening']) ? $row['opening'] : '');
					$rowArray['debit']			 = (isset($row['debit']) ? $row['debit'] : '');
					$rowArray['credit']			 = (isset($row['credit']) ? $row['credit'] : '');
					$rowArray['balance']		 = (isset($row['balance']) ? $row['balance'] : '');
				}
				else
				{
					$rowArray['month']			 = ($grpPeriod != '' ? $row["{$periodFieldName}"] : '');
					$rowArray['fromLedgerName']	 = $row['fromLedgerName'];
					$rowArray['toLedgerName']	 = $row['toLedgerName'];
					$rowArray['currentTotal']	 = $row['currentTotal'];
				}

				$arrData[] = array_values($rowArray);
			}
		}

		$serverID	 = Config::getServerID();
		$path		 = DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . $serverID . DIRECTORY_SEPARATOR . 'ledger' . DIRECTORY_SEPARATOR;
		#$path		 = DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR;
		$publicDir	 = PUBLIC_PATH . $path;

		if (!is_dir($publicDir))
		{
			mkdir($publicDir);
		}

		$fileName	 = date('YmdHi') . "_" . $ldrId . "_Ledger.csv";
		$filePath	 = $publicDir . $fileName;
		$path		 = $path . $fileName;

		if (!file_exists($filePath))
		{
			$handle = fopen($filePath, 'w');
			if (!$handle)
			{
				$success = false;
				goto skip;
			}

			foreach ($arrData as $line)
			{
				fputcsv($handle, $line);
			}
			fclose($handle);

			$model->refresh();
			$model->ldr_data_filepath	 = $path;
			$model->ldr_status			 = 3;
			if (!$model->save())
			{
				$success = false;
				goto skip;
			}
		}

		skip:
		if (!$success)
		{
			echo "Failed";
		}
		else
		{
			echo "Success";
		}
	}

	public function actionUpdateEventSurge()
	{
		CalendarEvent::updateDayType();
		CalendarEvent::updateLongWeekends();
		CalendarEvent::updateNextLongWeekends();
		CalendarEvent::updateNextLgWeekends();
		CalendarEvent::updatePhantomWeekends();
		CalendarEvent::updateEventFactor();
		CalendarEvent::updateWeightedFactor();
	}

	/**
	 * This function is used for inserting lead  Into service call queue
	 */
	public function actionLastMinBooking()
	{
		$check = Filter::checkProcess("system LastMinBooking");
		if (!$check)
		{
			return;
		}
		try
		{
			if (ServiceCallQueue::getLastMinLeadCount() < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				ServiceCallQueue::updateLastMinPendingLeadsCron();
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
	}

	/**
	 * This function will return all booking cancellation yesterday due to price was high
	 */
	public function actionAutoFURForBookingCancellation()
	{
		$check = Filter::checkProcess("system autoFURForBookingCancellation");
		if (!$check)
		{
			return;
		}
		$result = Booking::getAutoFurBookingCancellationByHighPrice();
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::autoFURForBookingCancellation($row);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all cancelling auto FUR For Manaual Assignment  ASK
	 */
	public function actionAutoCloseManaualAssignment()
	{
		$result = ServiceCallQueue::autoCloseManaualAssignment();
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "CBR expired. No action taken");
				$modelBooking = Booking::model()->findByPk($row['bkg_id']);
				if ($modelBooking->bkgPref->bpr_askmanual_assignment == 1)
				{
					$modelBooking->bkgPref->bpr_askmanual_assignment = 0;
					$modelBooking->bkgPref->save();
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all scq required to be closed as its days are over
	 */
	public function actionAutoCloseDispatchFollowUp()
	{
		$result = ServiceCallQueue::autoCloseDispatchFollowUp();
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "CBR expired. No action taken");
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionUpdateDDSBP($minutes = 60)
	{
		$check = Filter::checkProcess("onetime updateDDSBP");
		if (!$check)
		{
			return;
		}

		DynamicDemandSupplySurge::model()->updateDDSBP($minutes);
		DynamicDemandSupplySurge::model()->processMarkup(($minutes + 30));
	}

	public function actionAutoFURDriverLate()
	{
		$check = Filter::checkProcess("system autoFURDriverLate");
		if (!$check)
		{
			return;
		}
		if (ServiceCallQueue::getFollowupDispatchCount() < (int) Config::get('SCQ.maxDisPatchAllowed'))
		{
			$result = ServiceCallQueue::getFollowupDispatch((int) Config::get('SCQ.maxDisPatchAllowed'));
			foreach ($result as $row)
			{
				try
				{
					ServiceCallQueue::autoFURDriverLate($row['bkg_id']);
				}
				catch (Exception $ex)
				{
					Logger::exception($ex);
				}
			}
		}
	}

	public function actionAutoVendorAssignedFollowup()
	{
		$check = Filter::checkProcess("system autoVendorAssignedFollowup");
		if (!$check)
		{
			return;
		}

		$result = Booking::getListToUnassignVendor(true);
		foreach ($result as $row)
		{
			try
			{
				$count = ServiceCallQueue::countQueueByBkgId($row['bkg_id'], ServiceCallQueue::TYPE_VENDOR_ASSIGN);
				if ($count == 0)
				{
					ServiceCallQueue::autoFURVendorAssign($row['bkg_id']);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function will return all scq required to be closed as its days are over
	 */
	public function actionAutoCloseVendorAssignedFollowup()
	{
		$result = ServiceCallQueue::autoCloseVendorAssignedFollowup();
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "CBR expired. No action taken");
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionAutoCloseGozoNow()
	{
		$result = ServiceCallQueue::getAutoCloseGozoNow();
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "CBR expired. No action taken");
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionReportExport()
	{
		$result = ReportExport::getExportData();
		foreach ($result as $row)
		{
			try
			{
				ReportExport::createCsv($row);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionReportExpiryExport()
	{
		$result = ReportExport::getExpiryExportData();
		foreach ($result as $row)
		{
			try
			{
				$model				 = ReportExport::model()->findByPk($row['id']);
				$model->rpe_status	 = 0;
				if ($model->save())
				{
					$filename = $row['rpe_file_name'];
					if ($filename != "" && $filename != null)
					{
						$path = PUBLIC_PATH . DIRECTORY_SEPARATOR . "ReportExported" . DIRECTORY_SEPARATOR . $filename;
						unlink($path);
					}
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function is used for inserting lead  Into service call queue
	 */
	public function actionAddSpiceLead()
	{
		$check = Filter::checkProcess("system AddSpiceLead");
		if (!$check)
		{
			return;
		}
		$agentId			 = 34928;
		$eligibleScoreArr	 = [105, 100, 80];
		foreach ($eligibleScoreArr as $eligibleScore)
		{
			try
			{
				if (ServiceCallQueue::getLeadCount(true, $eligibleScore, $agentId) < (int) Config::get('SCQ.maxLeadAllowed'))
				{
					ServiceCallQueue::updatePendingLeadsCron(true, $eligibleScore, $agentId);
				}
				if (ServiceCallQueue::getLeadCount(false, $eligibleScore, $agentId) < (int) Config::get('SCQ.maxLeadAllowed'))
				{
					ServiceCallQueue::updatePendingLeadsCron(false, $eligibleScore, $agentId);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * This function is used for updating the WhatsApp every day  
	 */
	public function actionUpdateWhatsappNumber()
	{
		WhatsappLog::updateIsWhatsappVerified();
	}

	/**
	 * This function is used for sending WhatsApp refer link every day 
	 */
	public function actionReferAFriend()
	{
		$details = WhatsappLog::sendReferLink();
		foreach ($details as $val)
		{
			try
			{
				$url		 = "https://aao.cab/c/" . $val['qrc_code'];
				$userId		 = $val['user_id'];
				$contactId	 = $val['ctt_id'];
				Users::CustomerReferrals($userId, $contactId, $url, "10%");
			}
			catch (Exception $ex)
			{
				ReturnSet::setException($ex);
			}
		}
	}

	/**
	 * This function is used to send notification to multiple entities
	 */
	public function actionEventNotification()
	{
		$check = Filter::checkProcess("system EventNotification");
        if (!$check)
        {
            return;
        }
		
		$res = ScheduleEvent::getEventList([ScheduleEvent::USER_COIN_RECHARGE,ScheduleEvent::USER_COIN_EXPIRE, ScheduleEvent::VENDOR_PAYMENT_RELEASE, ScheduleEvent::BOOKING_DRIVER_TO_CUSTOMER, ScheduleEvent::BOOKING_CAB_DRIVER_ASSIGNMNET, ScheduleEvent::BOOKING_REVIEW], [ScheduleEvent::CUSTOMER_REF_TYPE,ScheduleEvent::BOOKING_REF_TYPE, ScheduleEvent::TRIP_REF_TYPE, ScheduleEvent::VENDOR_REF_TYPE]);
		foreach ($res as $row)
		{
			Logger::writeToConsole("Sde_id: ". $row['sde_id'] . " - " . $row['sde_event_id']);
			$model = ScheduleEvent::model()->findByPk($row['sde_id']);
			try
			{
				switch ($row['sde_event_id'])
				{
					case ScheduleEvent::BOOKING_CAB_DRIVER_ASSIGNMNET;
						$success = Vendors::notifyAssignVendor($row['sde_ref_id'], 0, $row['sde_event_sequence']);
						if ($success)
						{
							$model->sde_event_status = 1;
							$model->save();
						}
                        else
						{
							$model->sde_event_status = 2;
							$model->sde_last_error	 = "Fail";
							$model->sde_err_count	 = $model->sde_err_count == 0 ? 1 : $model->sde_err_count + 1;
							$model->save();
						}
						break;
                   case ScheduleEvent::USER_COIN_RECHARGE;
						$jsonData	 = json_decode($row['sde_addtional_data']);
						$coin		 = $jsonData->coin;
						$contactID	 = $jsonData->contactID;
						
						Logger::writeToConsole($row['sde_ref_id'] ." - ". $contactID  ." - ".  $coin  ." - ". $row['sde_event_sequence']);
						
						$success	 = Users::notifyCoinRecharge($row['sde_ref_id'], $contactID, $coin, 0, $row['sde_event_sequence']);
						if ($success)
						{
							Logger::writeToConsole("UPDATED");
							$model->sde_event_status = 1;
							$model->save();
						}
                        else
						{
							$model->sde_event_status = 2;
							$model->sde_last_error	 = "Fail";
							$model->sde_err_count	 = $model->sde_err_count == 0 ? 1 : $model->sde_err_count + 1;
							$model->save();
						}
						break;
					case ScheduleEvent::USER_COIN_EXPIRE;
						Logger::writeToConsole("USER_COIN_EXPIRE - ". $row['sde_addtional_data']);
						$jsonData	 = json_decode($row['sde_addtional_data']);
						$expiryDate	 = $jsonData->expiryDate;
						$coin		 = $jsonData->coin;
						$contactID	 = $jsonData->contactID;
						$success	 = Users::notifyCoinExpiry($row['sde_ref_id'], $expiryDate, $coin, $contactID, 0, $row['sde_event_sequence']);
						if ($success)
						{
							Logger::writeToConsole("UPDATED");
							$model->sde_event_status = 1;
							$model->save();
						}
                         else
						{
							$model->sde_event_status = 2;
							$model->sde_last_error	 = "Fail";
							$model->sde_err_count	 = $model->sde_err_count == 0 ? 1 : $model->sde_err_count + 1;
							$model->save();
						}
						break;
					case ScheduleEvent::BOOKING_DRIVER_TO_CUSTOMER;
						$success = Drivers::notifyDriverDetailsToCustomer($row['sde_ref_id'], 0, $row['sde_event_sequence']);
						if ($success)
						{
							$model->sde_event_status = 1;
							$model->save();
						}
						break;
					case ScheduleEvent::BOOKING_REVIEW;
						$success = Booking::bookingReview($row['sde_ref_id'], 0, $row['sde_event_sequence']);
						if ($success)
						{
							$model->sde_event_status = 1;
							$model->save();
						}
						break;
					case ScheduleEvent::VENDOR_PAYMENT_RELEASE;
						$jsonData	 = json_decode($row['sde_addtional_data']);
						$amount		 = $jsonData->amount;
						$success	 = Vendors::notifyVendorPaymentRelease($row['sde_ref_id'], $amount, 0, $row['sde_event_sequence']);
						if ($success)
						{
							$model->sde_event_status = 1;
							$model->save();
						}
						else
						{
							$model->sde_event_status = 2;
							$model->sde_last_error	 = "Fail";
							$model->sde_err_count	 = $model->sde_err_count == 0 ? 1 : $model->sde_err_count + 1;
							$model->save();
						}
						break;
				}
			}
			catch (Exception $ex)
			{
				$model->sde_event_status = 2;
				$model->sde_last_error	 = $ex->getMessage();
				$model->sde_err_count	 = $model->sde_err_count == 0 ? 1 : $model->sde_err_count + 1;
				$model->save();
				ReturnSet::exception($ex);
			}
		}
	}

	public function actionProcessQrCode()
	{
		$check = Filter::checkProcess("system ProcessQrCode");
		if (!$check)
		{
			return;
		}
		$sql	 = "SELECT 
						booking_user.bkg_user_id AS userId
					FROM booking
						JOIN booking_user ON booking_user.bui_bkg_id=booking.bkg_id AND bkg_status IN (1,2,3,4,5,6,7,9,15)
						JOIN users ON users.user_id=booking_user.bkg_user_id 
						LEFT JOIN qr_code ON qr_code.qrc_ent_id=booking_user.bkg_user_id AND qr_code.qrc_ent_type=1
					WHERE 1 
						AND (bkg_agent_id IS NULL OR bkg_agent_id=1249)
						AND booking.bkg_create_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 2 DAY),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59')
						AND qr_code.qrc_id IS NULL
					GROUP BY booking_user.bkg_user_id LIMIT 0,100";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{
			try
			{
				QrCode::processData($row['userId']);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionUpdateDistanceMatrix()
	{
		$sql = "SELECT dmx_id
				FROM distance_matrix dmx 
				INNER JOIN lat_long sl ON sl.ltg_id=dmx.dmx_source_ltg_id
				INNER JOIN lat_long dl ON dl.ltg_id=dmx.dmx_destination_ltg_id
				WHERE dmx.dmx_active=1 AND sl.ltg_active=1 AND dl.ltg_active=1
				AND dmx.dmx_distance>1 AND CalcDistance(sl.ltg_lat, sl.ltg_long, dl.ltg_lat, dl.ltg_long)>dmx_distance";

		$res = DBUtil::query($sql, DBUtil::SDB());
		foreach ($res as $row)
		{
			DistanceMatrix::refreshGoogleAPI($row['dmx_id']);
		}
	}

	public function actionUpdateCustomerStats()
	{
		$check = Filter::checkProcess("system updateCustomerStats");
		if (!$check)
		{
			return;
		}

		Logger::create("command.system.UpdateCustomerStats start", CLogger::LEVEL_PROFILE);
		UserStats::updateStats();
		Logger::create("command.system.UpdateCustomerStats end", CLogger::LEVEL_PROFILE);
	}

	public function actionGetDuplicateContacts($start = 0)
	{
		$check = Filter::checkProcess("system getDuplicateContacts");
		if (!$check)
		{
			return;
		}
		Contact::getDuplicateContacts($start);
	}

	public function actionVendorExpiryDocs()
	{
		$check = Filter::checkProcess("system vendorExpiryDocs");
		if (!$check)
		{
			return;
		}
		$getAllExpiryDocs = Vendors::getAllExpiryDocs();
		foreach ($getAllExpiryDocs as $value)
		{
			try
			{
				if ($value['license_status'] == 0)
				{
					Vendors::notifyExpiryDocs($value['vendorIds'], "License Certifcate");
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionVendorRejectedDocs()
	{
		$check = Filter::checkProcess("system vendorRejectedDocs");
		if (!$check)
		{
			return;
		}
		$getAllRejectedDocs = Vendors::getAllRejectedDocs();
		foreach ($getAllRejectedDocs as $value)
		{
			$fileType	 = "";
			$flag		 = 0;
			try
			{
				if ($value['voterId'] == 0)
				{
					$flag		 = 1;
					$fileType	 .= "Voter Card, ";
				}
				if ($value['aadharId'] == 0)
				{
					$flag		 = 1;
					$fileType	 .= "Aadhar Card, ";
				}
				if ($value['panId'] == 0)
				{
					$flag		 = 1;
					$fileType	 .= "Pan Card, ";
				}

				if ($value['licenceId'] == 0)
				{
					$flag		 = 1;
					$fileType	 .= "Licence Card, ";
				}
				if ($value['policeverId'] == 0)
				{
					$flag		 = 1;
					$fileType	 .= "Police Verification Certifcate,";
				}
				if ($flag == 1)
				{
					Vendors::notifyRejectedDocs($value['vendorIds'], trim(trim($fileType), ","));
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionNotWorkingVendor()
	{
		$check = Filter::checkProcess("system notWorkingVendor");
		if (!$check)
		{
			return;
		}
		$notWorkingVendor = Vendors::getNotWorkingVendor();
		foreach ($notWorkingVendor as $value)
		{
			try
			{
				Vendors::notifyNotWorkingVendor($value['vnd_id']);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

}
