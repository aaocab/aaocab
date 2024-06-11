<?php

use Netflie\WhatsAppCloudApi\WebHook;

include_once(dirname(__FILE__) . '/BaseController.php');

class TrackController extends BaseController
{

	public function filters()
	{
		return array
			(
			array
				(
				"application.filters.HttpsFilter + create",
				"bypass" => false
			),
			"accessControl", // perform access control for CRUD operations
			"postOnly + delete", // we only allow deletion via POST request
			array
				(
				"RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS"
			),
		);
	}

	public function actions()
	{
		return array
			(
			"REST." => "RestfullYii.actions.ERestActionProvider",
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array
			(
			array
				("allow", // allow all users to perform "index" and "view" actions
				"actions"	 => array(),
				"users"		 => array("@"),
			),
			array
				("allow", // allow authenticated user to perform "create" and "update" actions
				"actions"	 => array
					(
					"REST.GET", "REST.PUT", "REST.POST", "REST.DELETE", "REST.OPTIONS",
					"uploads", 'file', 'termsfile', 'whatsappNotificationHook',
					'dcoInterested', 'dcoInterestedUrl', 'dcoDownload', 'VendorWriteOff', 'VendorDownloadDco', 'VendorLoginReminder', 'TrackActionButton'
				),
				"users"		 => array("*"),
			),
			array
				("allow", // allow admin user to perform "admin" and "delete" actions
				"actions"	 => array(),
				"users"		 => array("admin"),
			),
			array
				("deny", // deny all users
				"users" => array("*"),
			),
		);
	}

	/**
	 * This holds the REST API Events that's needs to be used
	 */
	public function restEvents()
	{
		$this->onRest("req.cors.access.control.allow.methods", function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest("req.post.syncBookingTrackDetails.render", function () {
			return $this->renderJSON($this->syncTrackDetails());
		});
	}

	/**
	 * This function handles the booking track sync
	 * @return array
	 */
	public function syncTrackDetails()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);

		if (empty($jsonObj))
		{
			exit;
		}

		return BookingTrack::syncTrackDetails($jsonObj);
	}

	public function actionFile()
	{
		$id		 = Yii::app()->request->getParam('id');
		$hash	 = Yii::app()->request->getParam('hash');
		if ($id != Yii::app()->shortHash->unHash($hash))
		{
			throw new CHttpException(400, 'Invalid data');
		}
		else
		{
			$bpayModel	 = BookingPayDocs::model()->findByPk($id);
			$s3data		 = $bpayModel->bpay_s3_data;
			$imgPath	 = $bpayModel->bpay_image;

			$filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . $imgPath;
			if (file_exists($filePath))
			{
				Yii::app()->request->downloadFile($filePath);
			}
			else if ($s3data != '')
			{
				$spaceFile	 = Stub\common\SpaceFile::populate($s3data);
				$url		 = $spaceFile->getURL();
				Yii::app()->request->redirect($url);
			}
		}
	}

	public function actionTermsfile()
	{
		$DS			 = DIRECTORY_SEPARATOR;
		$imgUrl		 = $DS . "doc" . $DS . "bookings" . $DS . "mmtTerms" . $DS . "terms.png";
		$Url		 = AttachmentProcessing::ImagePath($imgUrl);
		$spiltPath	 = explode("/assets", $Url);
		$ImagePath	 = "/assets" . $spiltPath[1];
		echo "<img src='$ImagePath' >";
	}

	public function actionWhatsappNotificationHook1()
	{
		$accessToken = "EastOrWestGozoIsBestTravelPlatform";

		$webhook = new WebHook();

		echo $webhook->verify($_GET, $accessToken);
	}

	public function actionWhatsappNotificationHook()
	{
//		Whatsapp::writeFile('START WRITING', 'a+');

		$payload	 = file_get_contents('php://input');
		$jsonData	 = json_decode($payload, true);
		Filter::unsetInnermostKey($jsonData, 'conversation');

//		Whatsapp::writeFile(json_encode($jsonData), 'a+');

		$webhook		 = new WebHook();
		$notification	 = $webhook->read($jsonData);
		Whatsapp::processNotification($notification);

//		Whatsapp::writeFile("ENDED", 'a+');
	}

	public function actionDcoInterestedUrl()
	{
		$vndId	 = Yii::app()->request->getParam('vndId');
		$hash	 = Yii::app()->shortHash->hash($vndId);
		$url	 = Yii::app()->params['fullBaseURL'] . '/dct/' . $hash;
		echo $url	 = \Filter::shortUrl($url);
		exit;
	}

	public function actionDcoInterested()
	{
		$hash	 = Yii::app()->request->getParam('hash');
		$id		 = Yii::app()->shortHash->unHash($hash);
		try
		{
			if ($id == '' || $id == null)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$vModel = Vendors::model()->findByPk($id);
			if ($vModel)
			{
				$ditModel = DcoInterestedTracking::add($vModel->vnd_id);
			}
			else
			{
				throw new CHttpException(400, 'Invalid id');
			}
			$contactId		 = ContactProfile::getByEntityId($vModel->vnd_id, UserInfo::TYPE_VENDOR);
			$contactModel	 = Contact::model()->getContactDetails($contactId);
			$vndName		 = "<b>" . $contactModel['ctt_first_name'] . ' ' . $contactModel['ctt_last_name'] . " </b>(" . $vModel->vnd_code . ")";
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
			\Sentry\captureMessage(json_encode($ex), null);
		}
		$baseUrl = Yii::app()->params['fullBaseURL'];
		$url	 = $baseUrl . '/dctd/' . $hash;
		$url	 = \Filter::shortUrl($url);
		$this->renderAuto('dcoDownload', ['ditModel' => $ditModel, 'vndName' => $vndName, 'url' => $url]);
	}

	public function actionDcoDownload()
	{
		$hash	 = Yii::app()->request->getParam('hash');
		$id		 = Yii::app()->shortHash->unHash($hash);
		try
		{
			if ($id == '' || $id == null)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$vModel = Vendors::model()->findByPk($id);
			if ($vModel)
			{
				$success = DcoInterestedTracking::updateDownload($vModel->vnd_id);
				$url	 = "https://c.gozo.cab/rMnyi";
				header("Location: $url");
				Yii::app()->end();
			}
			else
			{
				throw new CHttpException(400, 'Invalid id');
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
			\Sentry\captureMessage(json_encode($ex), null);
		}
	}

	public function actionVendorWriteOff()
	{
		$hash		 = Yii::app()->request->getParam('hash');
		$vendorId	 = Yii::app()->shortHash->unHash($hash);
		try
		{
			if ($vendorId == '' || $vendorId == null)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$sqlUpdate = "UPDATE test.vendor_writeoff_06032024 SET is_download=1,downloaded_at=NOW() WHERE vnd_id = $vendorId";
			if (DBUtil::execute($sqlUpdate) > 0)
			{
				$url = "https://play.google.com/store/apps/details?id=com.gozocab.dco";
				header("Location: $url");
				Yii::app()->end();
			}
			else
			{
				$url = "https://play.google.com/store/apps/details?id=com.gozocab.dco";
				header("Location: $url");
				Yii::app()->end();
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
		}
	}

	public function actionVendorDownloadDco()
	{
		$hash		 = Yii::app()->request->getParam('hash');
		$vendorId	 = Yii::app()->shortHash->unHash($hash);
		try
		{
			if ($vendorId == '' || $vendorId == null)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$sqlUpdate = "UPDATE test.vendor_dco_download_20032024 SET is_download=1,downloaded_at=NOW() WHERE 1 AND is_processed=1 AND status=1 AND vnd_id = $vendorId";
			if (DBUtil::execute($sqlUpdate) > 0)
			{
				$url = "https://play.google.com/store/apps/details?id=com.gozocab.dco";
				header("Location: $url");
				Yii::app()->end();
			}
			else
			{
				$url = "https://play.google.com/store/apps/details?id=com.gozocab.dco";
				header("Location: $url");
				Yii::app()->end();
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
		}
	}

	public function actionVendorLoginReminder()
	{
		$hash		 = Yii::app()->request->getParam('hash');
		$vendorId	 = Yii::app()->shortHash->unHash($hash);
		try
		{
			if ($vendorId == '' || $vendorId == null)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$sqlUpdate = "UPDATE test.vendor_login_reminder_21032024 SET is_downloaded=1,downloaded_at=NOW() WHERE vnd_id = $vendorId";
			if (DBUtil::execute($sqlUpdate) > 0)
			{
				$url = "https://play.google.com/store/apps/details?id=com.gozocab.dco";
				header("Location: $url");
				Yii::app()->end();
			}
			else
			{
				$url = "https://play.google.com/store/apps/details?id=com.gozocab.dco";
				header("Location: $url");
				Yii::app()->end();
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
		}
	}

	public function actionTrackActionButton()
	{
		$refId		 = Yii::app()->shortHash->unHash(Yii::app()->request->getParam('refId'));
		$refType	 = Yii::app()->shortHash->unHash(Yii::app()->request->getParam('refType'));
		$eventId	 = Yii::app()->shortHash->unHash(Yii::app()->request->getParam('eventId'));
		$platform	 = Yii::app()->shortHash->unHash(Yii::app()->request->getParam('platform'));
		$linkId		 = Yii::app()->shortHash->unHash(Yii::app()->request->getParam('linkId'));
		try
		{
			switch ($eventId)
			{
				case 50:
					MarketingMessageTracker::updateStatus($refType, $refId, $eventId,$platform, $linkId);
					if ($linkId == 0)
					{
						$url = "http://www.aaocab.com/";
						header("Location: $url");
						Yii::app()->end();
					}
					else if ($linkId == 1)
					{
						$hash	 = Yii::app()->shortHash->hash($refId);
						$url	 = "http://www.aaocab.com/refer-by-friend/$hash";
						header("Location: $url");
						Yii::app()->end();
					}
					break;

				default:
					break;
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
		}
	}

}
