<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class RatingController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';

	//public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter + create',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
		);
	}

	public function actions()
	{
		$pass = uniqid(rand(), TRUE);
		return array(
			'oauth'		 => array(
				// the list of additional properties of this action is below
				'class'				 => 'ext.hoauth.HOAuthAction',
				// Yii alias for your user's model, or simply class name, when it already on yii's import path
				// default value of this property is: User
				'model'				 => 'Users',
				'alwaysCheckPass'	 => false,
				// map model attributes to attributes of user's social profile
				// model attribute => profile attribute
				// the list of avaible attributes is below
				'attributes'		 => array(
					'usr_email'			 => 'email',
					'username'			 => 'displayName',
					// you can also specify additional values,
					// that will be applied to your model (eg. account activation status)
					'usr_email_verify'	 => 1,
					'user_type'			 => 1,
					'new_password'		 => $pass,
					'repeat_password'	 => $pass,
					'tnc'				 => 1,
				),
			),
			// this is an admin action that will help you to configure HybridAuth
			// (you must delete this action, when you'll be ready with configuration, or
			// specify rules for admin role. User shouldn't have access to this action!)
			'oauthadmin' => array(
				'class' => 'ext.hoauth.HOAuthAdminAction',
			),
			'REST.'		 => 'RestfullYii.actions.ERestActionProvider',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array(''),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('addreview', 'ajaxverify', 'showreview', 'index', 'bookingreview', 'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS', 'averagecab', 'averagedriver', 'averagevendor', 'reviewAttribute', 'emailNotification', 'checkMail', 'downloadQrCode'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('admin'),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array('/reviewAttribute', '/customer', '/customerRating');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.customer.render', function () {
			$rating_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($rating_sync_data, true);
			$returninfo			 = Ratings::model()->addCustomerRating($data);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success' => $returninfo
				),
			]);
		});

		$this->onRest('req.post.customerRating.render', function () {
			$rating_sync_data	 = '{"car":{"rtg_car_cmt":"","rtg_customer_car":2,"rtg_attr":[{"id":46,"value":1},{"id":50,"value":0},{"id":78,"value":0},{"id":36,"value":0}]},"csr":{"rtg_csr_cmt":"Berry bad","rtg_customer_csr":2,"rtg_attr":[{"id":32,"value":0},{"id":75,"value":1},{"id":62,"value":0},{"id":35,"value":0}]},"customer":{"rtg_customer_review":"Hello Roy","rtg_customer_overall":3,"rtg_customer_recommend":5,"rtg_booking_id":"750095","rtg_platform":""},"driver":{"rtg_driver_cmt":"","rtg_customer_driver":2,"rtg_attr":[{"id":22,"value":0},{"id":23,"value":0},{"id":25,"value":0},{"id":77,"value":0},{"id":17,"value":1},{"id":12,"value":0},{"id":13,"value":0}]}}';
			$success			 = false;
			$message			 = '';
			try
			{
				Logger::create("data =>" . json_encode($rating_sync_data), CLogger::LEVEL_INFO);
				$postData	 = CJSON::decode($rating_sync_data, true);
//				$rmodel		 = Ratings::model()->getRatingbyBookingId($postData['customer']['rtg_booking_id']);
//				if ($rmodel != '')  { }	
				$isRated	 = Ratings::isRatingPosted($postData['customer']['rtg_booking_id']);
				if ($isRated == true)
				{
					throw new Exception("Rating already posted.", 401);
				}
				$driverGoodAttr	 = $driverBadAttr	 = $carGoodAttr	 = $carBadAttr		 = $csrGoodAttr	 = $csrBadAttr		 = [];
				if (isset($postData['driver']) && count($postData['driver']) > 0)
				{

					$data['rtg_customer_driver'] = $postData['driver']['rtg_customer_driver'];
					$data['rtg_driver_cmt']		 = $postData['driver']['rtg_driver_cmt'];
					foreach ($postData['driver']['rtg_attr'] as $attr)
					{
						if ($attr['value'] == 1)
						{
							$driverGoodAttr[] = $attr['id'];
						}
						else if ($attr['value'] == 0)
						{
							$driverBadAttr[] = $attr['id'];
						}
					}
					$data['rtg_driver_good_attr']	 = implode(',', $driverGoodAttr);
					$data['rtg_driver_bad_attr']	 = implode(',', $driverBadAttr);
				}

				if (isset($postData['car']) && count($postData['car']) > 0)
				{
					$data['rtg_customer_car']	 = $postData['car']['rtg_customer_car'];
					$data['rtg_car_cmt']		 = $postData['car']['rtg_car_cmt'];
					foreach ($postData['car']['rtg_attr'] as $attr)
					{
						if ($attr['value'] == 1)
						{
							$carGoodAttr[] = $attr['id'];
						}
						else if ($attr['value'] == 0)
						{
							$carBadAttr[] = $attr['id'];
						}
					}
					$data['rtg_car_good_attr']	 = implode(',', $carGoodAttr);
					$data['rtg_car_bad_attr']	 = implode(',', $carBadAttr);
				}


				if (isset($postData['csr']) && count($postData['csr']) > 0)
				{
					$data['rtg_customer_csr']	 = $postData['csr']['rtg_customer_csr'];
					$data['rtg_csr_cmt']		 = $postData['csr']['rtg_csr_cmt'];
					foreach ($postData['csr']['rtg_attr'] as $attr)
					{
						if ($attr['value'] == 1)
						{
							$csrGoodAttr[] = $attr['id'];
						}
						else if ($attr['value'] == 0)
						{
							$csrBadAttr[] = $attr['id'];
						}
					}
					$data['rtg_csr_good_attr']	 = implode(',', $csrGoodAttr);
					$data['rtg_csr_bad_attr']	 = implode(',', $csrBadAttr);
				}

				if (isset($postData['customer']) && count($postData['customer']) > 0)
				{
					$data['rtg_booking_id']			 = $postData['customer']['rtg_booking_id'];
					$data['rtg_customer_recommend']	 = $postData['customer']['rtg_customer_recommend'];
					$data['rtg_customer_overall']	 = $postData['customer']['rtg_customer_overall'];
					$data['rtg_customer_review']	 = $postData['customer']['rtg_customer_review'];
					$data['rtg_platform']			 = $postData['customer']['rtg_platform'];
				}

				if (isset($data['rtg_booking_id']) && $data['rtg_booking_id'] > 0)
				{
					if ($postData['customer']['rtg_booking_id'] > 0)
					{
						$data['uniqueId'] = Booking::model()->generateLinkUniqueid($postData['customer']['rtg_booking_id']);
					}

					if ($data['rtg_platform'] == Ratings::PLATFORM_IOS_APP)
					{
						$data['rtg_platform'] = Ratings::PLATFORM_IOS_APP;
					}
					else
					{
						$data['rtg_platform'] = Ratings::PLATFORM_ANDROID_APP;
					}
					Logger::create("Post : \t" . json_encode($data), CLogger::LEVEL_INFO);
					$result	 = Ratings::model()->addRatingForCustomer($data);
					Logger::create("Result : \t" . json_encode($result), CLogger::LEVEL_INFO);
					$success = $result['success'];
				}
			}
			catch (Exception $ex)
			{
				$message = $ex->getMessage();
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'message'	 => $message
				),
			]);
		});

		// show all attribute for review
		$this->onRest('req.get.reviewAttribute.render', function () {
			$bkgId	 = Yii::app()->request->getParam('bkgId');
			//$bkgId = "1024";
			/* @var $model Booking */
			$data	 = RatingAttributes::model()->ratingAttributes();
			if (isset($bkgId) && $bkgId > 0)
			{

				/* @var $model Booking */
				$model = Booking::model()->findByPk($bkgId);

				$vehicleModel = $model->bkgBcb->bcbCab->vhcType->vht_model;
				if ($model->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
				{
					$vehicleModel = OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
				}

				$data['consumer']['pickupDate']	 = DateTimeFormat::DateTimeToLocale($model->bkg_pickup_date);
				$data['consumer']['bookingDate'] = DateTimeFormat::DateTimeToLocale($model->bkg_create_date);
				$data['consumer']['route']		 = $model->bkgFromCity->cty_name . ' ' . $model->bkgToCity->cty_name;
				$data['consumer']['driverName']	 = $model->bkgBcb->bcbDriver->drv_name;
				$data['consumer']['cabName']	 = $vehicleModel . ' ' . $model->bkgBcb->bcbCab->vhc_number;
				$data['consumer']['tripType']	 = Booking::model()->getBookingType($model->bkg_booking_type);
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => true,
					'data'		 => $data
				),
			]);
		});
	}

	public function actionAddreview()
	{
		$bkgid = Yii::app()->request->getParam('bkg_id');

		$model					 = new Ratings('custRating');
		$model->rtg_booking_id	 = $bkgid;

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addreview', array('model' => $model), false, $outputJs);
	}

	public function actionAjaxverify()
	{
		$model	 = new Ratings('custRating');
		$request = Yii::app()->request;
		$data	 = $request->getParam('Ratings');
		if (isset($data))
		{
			$data					 = Ratings::setData($request);
			$data['uniqueId']		 = trim($_REQUEST['uniqueId']);
			$data['rtg_platform']	 = Ratings::PLATFORM_FRONT_END;
			//Logger::create("Post Data ================> " . json_encode($data), CLogger::LEVEL_INFO);
			$result					 = Ratings::model()->addRatingForCustomer($data);

			if ($result['success'] == true && $result['overallRating'] > 4)
			{
				$promoId	 = Config::get('complete.review.booking.user.promo.id');
				$bkgModel	 = Booking::model()->findByPk($data['rtg_booking_id']);
				$refId		 = $bkgModel->bkgUserInfo->bkg_user_id;
				$validFrom	 = Filter::getDBDateTime();
				$validUpto	 = date("Y-m-d H:i:s", strtotime("+3 months"));
				PromoUsers::addUser($promoId, $refId, 0, 1, $validFrom, $validUpto, 1, 0);
//				$response			 = WhatsappLog::bookingReviewCustomerOtherLinks($result['booking_id'], $promoId);
				Booking::bookingReviewOtherLinks($result['booking_id'], $promoId, $isSchedule	 = 0);
			}
			Logger::create("Result Data : \t" . json_encode($result), CLogger::LEVEL_INFO);

			echo CJSON::encode(['result'		 => $result['success'],
				'bkid'			 => $result['booking_id'],
				'uniqueId'		 => $result['uniqueId'],
				'tripadviser'	 => $result['tripAdviser']]);
		}
	}

	public function actionShowreview()
	{
		$this->checkV2Theme();
		$bkgid	 = Yii::app()->request->getParam('bkg_id');
		$model	 = Ratings::model()->getRatingbyBookingId($bkgid);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('view', array('model' => $model), false, $outputJs);
	}

	public function actionBookingreview()
	{
		$uniqueid		 = Yii::app()->request->getParam('uniqueid');
		$status			 = Yii::app()->request->getParam('status');
		$rateVal		 = Yii::app()->request->getParam('val');
		$totLength		 = strlen($uniqueid);
		$bkgdateLength	 = 14;
		$bkgidLength	 = $totLength - $bkgdateLength;
		$bkgid			 = (int) substr($uniqueid, (0 - $bkgidLength));
		$bookingdate	 = substr($uniqueid, (0 - ($bkgidLength + $bkgdateLength)), $bkgdateLength);
		$this->layout	 = 'column1';
		$bkmodel		 = Booking::model()->getByCodenDatenId($bookingdate, $bkgid);
		$this->pageTitle = "";
		$dataArray		 = RatingAttributes::model()->getRatingAttributes(1);
		try
		{
			$linkExpire = Ratings::isLinkExpired($bkmodel->bkg_id);
			if ($linkExpire == 1)
			{
				throw new Exception("Sorry this link has been expired");
			}
			if ($bkmodel)
			{
				$userId		 = $bkmodel->bkgUserInfo->bkg_user_id;
				$modelUser	 = Users::model()->findByPk($userId);

				if ($modelUser->usr_refer_code != '')
				{
					$refCode = $modelUser->usr_refer_code;
				}
				else
				{
					$refCode					 = substr($modelUser->usr_name, 0, 3) . rand(100, 999); //Yii::app()->shortHash->hash($userId, 6);
					$modelUser->usr_refer_code	 = $refCode;
					$modelUser->scenario		 = 'refcode';

					if ($modelUser->validate())
					{
						if (!$modelUser->update())
						{
							$errors = $modelUser->getErrors();
						}
					}
				}



				$ifReviewExist	 = FALSE;
				$link			 = [];
				$model			 = Ratings::model()->getRatingbyBookingId($bkgid);

				if ($model)
				{

					$ifReviewExist	 = TRUE;
					$ratingLinkList	 = Ratings::reviewButtonLink($uniqueid, $refCode, $model->rtg_customer_review);
					if (!$model->rtg_customer_overall)
					{
						$ifReviewExist = FALSE;
					}
				}
				else
				{
					$model = new Ratings('custRating');
				}
				$model->rtg_booking_id = $bkmodel->bkg_id;
				QrCode::processData($userId);
				if ($rateVal != '')
				{
					$model->rtg_customer_recommend = $rateVal;
				}

				$this->render('emailreview', array('model'			 => $model,
					'refCode'		 => $refCode,
					'bkmodel'		 => $bkmodel,
					'ifReviewExist'	 => $ifReviewExist,
					'bookingcode'	 => $bookingcode,
					'uniqueid'		 => $uniqueid,
					'status'		 => $status,
					'data_array'	 => $dataArray,
					'reviewLinkList' => $ratingLinkList,
					'diffDays'		 => $diffDays,
					'linkExpire'	 => $linkExpire));
			}
		}
		catch (Exception $ex)
		{
			echo '<h2>' . $ex->getMessage() . '</h2>';
		}
	}

	public function actionEmailNotification()
	{
		$rtgId								 = Yii::app()->request->getParam('rtg_id');
		$model								 = Ratings::model()->findByPk($rtgId);
		$ratingData							 = Ratings::model()->getDriverVendorDetailsById($model->rtg_id);
		$ratingData['rtg_driver_good_attr']	 = $model->rtg_driver_good_attr;
		$ratingData['rtg_driver_bad_attr']	 = $model->rtg_driver_bad_attr;
		$ratingData['rtg_csr_good_attr']	 = $model->rtg_csr_good_attr;
		$ratingData['rtg_csr_bad_attr']		 = $model->rtg_csr_bad_attr;
		$ratingData['rtg_car_good_attr']	 = $model->rtg_car_good_attr;
		$ratingData['rtg_car_bad_attr']		 = $model->rtg_car_bad_attr;
		$ratingData['bkg_contact_gozo']		 = $model->rtgBooking->bkgPref->bkg_contact_gozo;
		$ratingData['total_trip_by_car']	 = Vehicles::totalTrips($ratingData['vhc_id']);
		$info								 = Users::info($ratingData['bkg_user_id']);
		$ratingData['first_trip_date']		 = $info['firstTripDate'];
		$ratingData['last_trip_date']		 = $info['lastTripDate'];
		$ratingData['user_rating']			 = $info['rating'];
		$ratingData['total_trip']			 = $info['totalTrips'];

		$emailCom = new emailWrapper();
		$emailCom->reviewNotification($model->rtg_id, $ratingData);
	}

	public function actionAveragecab()
	{
		$vhc_id	 = 0;
		$model	 = Ratings::model()->getCarAveragerating($vhc_id);
	}

	public function actionAveragedriver()
	{
		$drv_id	 = 0;
		$model	 = Ratings::model()->getDriverAveragerating($drv_id);
	}

	public function actionAveragevendor()
	{
		$vnd_id	 = 0;
		$model	 = Ratings::model()->getVendorAveragerating($vnd_id);
	}

	public function actionDownloadQrCode()
	{
		$userId		 = Yii::app()->request->getParam('userId');
		$userModel	 = Users::model()->findByPk($userId);
		if ($userModel->usr_s3_data == '{}' || $userModel->usr_s3_data == '' || $userModel->usr_s3_data == Null)
		{
			$fullpath = Yii::app()->basePath . $userModel->usr_qr_code_path;
		}
		else
		{
			$fullpath = Users::getUserPathById($userId);
		}
		$qrCodeModel = QrCode::model()->find('qrc_ent_id=:userid', ['userid' => $userId]);

		$serverId = Config::getServerID();

		$templateImgPath = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'images/qrcodetemplate.jpg';
		$newTempImg		 = Yii::app()->basePath . DIRECTORY_SEPARATOR . "doc/{$serverId}/qrcode/" . $qrCodeModel->qrc_code . '.jpg';
		copy($templateImgPath, $newTempImg);

		$qrImg	 = imagecreatefrompng($fullpath);
		$destImg = imagecreatefromjpeg($templateImgPath);

		imagealphablending($destImg, false);
		imagesavealpha($destImg, true);

		imagecopymerge($destImg, imagescale($qrImg, 230, 230), 75, 150, 0, 0, 230, 230, 100); //have to play with these numbers for it to work for you, etc.

		header('Content-Type: image/png');
		imagepng($destImg, $newTempImg);

		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . basename($newTempImg) . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
//		header('Content-Length: ' . filesize($newTempImg));
		flush(); // Flush system output buffer
		readfile($newTempImg);
		unlink($newTempImg);
	}

}
