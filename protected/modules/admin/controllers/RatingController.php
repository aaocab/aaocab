<?php

class RatingController extends Controller
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
				'actions'	 => array('list', 'changestatus', 'replycustomerpage', 'replyvendorpage', 'ajaxvendorreply', 'listByVendor'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('addcsrreview', 'ajaxverify', 'ajaxcustreply', 'ajaxcustverify',
					'showreview', 'index', 'bookingreview', 'addcustreview', 'nps',
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
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
			$ri	 = array();
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
			// $conins_sync_data = '[{"cin_id":3,"cin_consumer_id":671771,"cin_executive_id":1,"cin_active":2,"cin_created_on":"2015-10-16 14:44:43","cin_upload_id":2569,"cin_synced":0,"cin_longtitude":0,"cin_latitude":0,"cin_answer_count":0,"cin_image_count":0}]';
			$data				 = CJSON::decode($rating_sync_data, true);
			$returninfo			 = Ratings::model()->addCustomerRating($data);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success' => $returninfo
				),
			]);
		});
	}

	public function actionAddcsrreview()
	{
		$bkgid = Yii::app()->request->getParam('bkg_id');

		$model					 = new Ratings('csrRating');
		$model->rtg_booking_id	 = $bkgid;

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addcsrreview', array('model' => $model), false, $outputJs);
	}

	public function actionAjaxverify()
	{
		$model = new Ratings('csrRating');
		//$model->rtg_booking_id = $bkgid;
		if (isset($_POST['Ratings']))
		{
			$arr	 = Yii::app()->request->getParam('Ratings');
			$rmodel	 = Ratings::model()->getRatingbyBookingId($arr['rtg_booking_id']);

			$bkgmodel = Booking::model()->findByPk($arr['rtg_booking_id']);

			if (!$rmodel == false)
			{
				$model = $rmodel;
			}
			$model->attributes	 = Yii::app()->request->getParam('Ratings');
			$model->rtg_csr_date = new CDbExpression('NOW()');
			$model->rtg_csr_id	 = Yii::app()->user->getId();

			if ($model->validate())
			{
				$model->save();
				$bkgid		 = $model->rtg_booking_id;
				$desc		 = "CSR Rating entered";
				$userInfo	 = UserInfo::getInstance();

				$eventid = BookingLog::BOOKING_REVIEWED_BY_CSR;
				BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $bkgmodel);
				echo CJSON::encode(['result' => 'true']);
			}
		}
	}

	public function actionAjaxcustverify()
	{
		$model = new Ratings('custRating');
		if (isset($_REQUEST['Ratings']))
		{
			$arr1		 = Yii::app()->request->getParam('Ratings');
			$rmodel		 = Ratings::model()->getRatingbyBookingId($arr1['rtg_booking_id']);
			$bkgmodel	 = Booking::model()->findByPk($arr1['rtg_booking_id']);
			if (!$rmodel == false)
			{
				$model = $rmodel;
			}
			$model->attributes			 = $arr1;
			$model->rtg_booking_id		 = $arr1['rtg_booking_id'];
			$model->rtg_customer_overall = $arr1['rtg_customer_overall'];
			$model->rtg_customer_driver	 = $arr1['rtg_customer_driver'];
			$model->rtg_customer_csr	 = $arr1['rtg_customer_csr'];
			$model->rtg_customer_car	 = $arr1['rtg_customer_car'];
			$model->rtg_customer_review	 = $arr1['rtg_customer_review'];
			$model->rtg_customer_date	 = new CDbExpression('NOW()');
			$model->rtg_platform		 = Ratings::PLATFORM_BACK_END;
			if ($model->validate())
			{
				if ($model->save())
				{
					$customerProfile					 = new CustomerProfile();
					$customerProfile->csp_user_id		 = $model->rtgBooking->bkgUserInfo->bkg_user_id;
					$customerProfile->csp_booking_id	 = $model->rtg_booking_id;
					$customerProfile->csp_attribute_type = CustomerProfile::TYPE_REVIEW;
					$customerProfile->csp_value_str		 = $model->rtg_customer_review;
					$customerProfile->csp_value_int		 = $model->rtg_customer_overall;
					$customerProfile->save();
				}
				$bkgid		 = $model->rtg_booking_id;
				$desc		 = "Customer Review entered";
				$userInfo	 = UserInfo::getInstance();
				$eventid	 = BookingLog::BOOKING_REVIEWED_BY_USER;
				BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $bkgmodel);
				if (($model->rtg_customer_overall < 4 && $model->rtg_customer_overall != '' && $model->rtg_customer_overall > 0))
				{
					$desc_payment		 = "Vendor payment stopped due to low customer ratings. Escalated to Customer advocacy team";
					$escalation_level	 = 3;
					$assigned_lead		 = 158;
					$assigned_team		 = 14;
					BookingSub::blockVendorPayment($model->rtgBooking->bkg_bcb_id, $model->rtg_booking_id, 1, $desc_payment, $escalation_level, $assigned_lead, $assigned_team);

					/* Auto FUR Poor Rating Start */
					$count = ServiceCallQueue::countQueueByBkgId($model->rtg_booking_id, ServiceCallQueue::TYPE_ADVOCACY, "notRating");
					if ($count == 0)
					{
						ServiceCallQueue::autoFURRating($model->rtg_booking_id);
					}
					$countNotRating	 = ServiceCallQueue::countQueueByBkgId($model->rtg_booking_id, ServiceCallQueue::TYPE_ADVOCACY, "notRating");
					$countIsRated	 = ServiceCallQueue::countQueueByBkgId($model->rtg_booking_id, ServiceCallQueue::TYPE_ADVOCACY, "IsRated");
					if ($countNotRating == 1 && $countIsRated == 1)
					{
						$scqId = ServiceCallQueue::getScqDetailsForRating($model->rtg_booking_id);
						if ($scqId > 0)
						{
							ServiceCallQueue::updateStatus($scqId, 10, 0, "CBR expired. No action taken");
						}
					}
					/* 	Auto FUR Poor Rating  ENDS */

					/* Auto FUR Poor Rating for Vendor Advocacy Start */
//					$count = ServiceCallQueue::countQueueByBkgId($model->rtg_booking_id, ServiceCallQueue::TYPE_VENDOR_ADVOCACY);
//					if ($count == 0)
//					{
//						ServiceCallQueue::autoFURRatingVendorAdvocacy($model->rtg_booking_id);
//					}
					/* 	Auto FUR Poor Rating for Vendor Advocacy ENDS */
				}

				echo CJSON::encode(['result' => 'true', 'bkid' => $model->rtg_booking_id]);
			}
		}
	}

	public function actionShowreview()
	{
		$bkgid		 = Yii::app()->request->getParam('bkg_id');
		$model		 = Ratings::model()->getRatingbyBookingId($bkgid);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('view', array('model' => $model), false, $outputJs);
	}

	public function actionAddcustreview()
	{
		$bkgid = Yii::app()->request->getParam('bkg_id');

		$model					 = new Ratings('custRating');
		$model->rtg_booking_id	 = $bkgid;

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addcustreview', array('model' => $model), false, $outputJs);
	}

	public function actionList()
	{
		$this->pageTitle		 = "Rating List";
		$model					 = new Ratings('search');
		$model->rtg_create_date1 = (!empty($model->rtg_create_date1)) ? $model->rtg_create_date1 : date('Y-m-d', strtotime('-10 DAYS'));
		$model->rtg_create_date2 = (!empty($model->rtg_create_date2)) ? $model->rtg_create_date2 : date('Y-m-d');
		if (isset($_REQUEST['Ratings']))
		{
			$arr					 = Yii::app()->request->getParam('Ratings');
			$model->attributes		 = $arr;
			$model->rtg_create_date1 = $arr['rtg_create_date1'];
			$model->rtg_create_date2 = $arr['rtg_create_date2'];
			$model->category         = $arr['category'];
			$model->strTags		 = implode(',', $arr['strTags']);
		}
		 
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$model->strTags = $arr['strTags'];
			$dataReader	 = $model->fetchList('export');
			$filename	 = "RatingList" . date('YmdHis') . ".csv";
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Pragma: no-cache");
			header("Expires: 0");

			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}

			$rowHead = [
				'BookingId',
				'Vendor',
				'Driver',
				'CustomerOverallRating',
				'CustomerDriverRating',
				'CustomerCsrRating',
				'CustomerCarRating',
				'CustomerReview',
				'ReviewDesc',
				'VendorCustomer',
				'VendorCsr',
				'VendorReview',
				'ReviewDateTime'
			];
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, $rowHead);
			foreach ($dataReader as $data)
			{
				$rowArray							 = array();
				$rowArray['BookingId']				 = $data['bkg_booking_id'];
				$rowArray['Vendor']				 = ($data['rtg_booking_id'] != '') ? $data['vnd_code'] : '';
				$rowArray['Driver']				 = ($data['rtg_booking_id'] != '') ? $data['drv_code'] : '';
				$rowArray['CustomerOverallRating']	 = $data['rtg_customer_overall'];
				$rowArray['CustomerDriverRating']	 = $data['rtg_customer_driver'];
				$rowArray['CustomerCsrRating']		 = $data['rtg_customer_csr'];
				$rowArray['CustomerCarRating']	 = $data['rtg_customer_car'];
				$rowArray['CustomerReview']		 = $data['rtg_customer_review'];
				$rowArray['ReviewDesc']			 = Ratings::parseReviewDescJSON($data['rtg_review_desc']);
				$rowArray['VendorCustomer']		 = $data['rtg_vendor_customer'];
				$rowArray['VendorCsr']			 = $data['rtg_vendor_csr'];
				$rowArray['VendorReview']		 = $data['rtg_vendor_review'];
				$rowArray['ReviewDateTime']			 = DateTimeFormat::DateTimeToLocale($data['rtg_customer_date']);

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		 



		$dataProvider = $model->fetchList();

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionChangestatus()
	{
		$rtg_id		 = Yii::app()->request->getParam('rtg_id');
		$rtg_active	 = Yii::app()->request->getParam('rtg_active');
		$model		 = $success	 = false;
		if ($rtg_id > 0 && $rtg_id != '')
		{
			$model = Ratings::model()->resetScope()->findByPk($rtg_id);
		}
		if ($model)
		{
			$model->rtg_active = ($rtg_active == 1) ? 0 : 1;
			if ($model->save())
			{
				$desc							 = "Rating ";
				$desc							 .= ($rtg_active == 1) ? "Rejected." : "Approved.";
				$event_id						 = ($rtg_active == 1) ? BookingLog::RATING_UNAPPROVE : BookingLog::RATING_APPROVE;
				$userInfo						 = UserInfo::getInstance();
				$params['blg_booking_status']	 = $model->rtgBooking->bkg_status;
				BookingLog::model()->createLog($model->rtgBooking->bkg_id, $desc, $userInfo, $event_id, false, $params);
				$success						 = true;
			}
		}
		echo json_encode(["success" => $success]);
		Yii::app()->end();
	}

	public function actionNps()
	{
		$this->pageTitle	 = "NPS Report";
		$model				 = new Ratings();
		$model->rtg_date1	 = date("Y-m-01", strtotime("-6 month", time()));
		$model->rtg_date2	 = date('Y-m-d');
		$model->groupvar	 = "month";
		$req				 = Yii::app()->request;

		if ($req->getParam('Ratings'))
		{
			$data				 = $req->getParam('Ratings');
			$model->groupvar	 = $data['groupvar'];
			$model->rtg_date1	 = $data['rtg_date1'];
			$model->rtg_date2	 = $data['rtg_date2'];
			$model->bkgtypes	 = $data['bkgtypes'];
		}
		$dataProvider			 = $model->getNpsList();
		$dataNpsByRegionLastYear = $model->getNpsByRegionLastYear();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('nps', array('model' => $model, 'dataProvider' => $dataProvider, 'dataNpsByRegionLastYear' => $dataNpsByRegionLastYear));
	}

	public function actionListByVendor()
	{
		$vendorId		 = Yii::app()->request->getParam('vendor_id');
		$this->pageTitle = "Rating List";
		$model			 = new Ratings();
		$bookingId		 = '';
		$driverName		 = '';
		if (isset($_REQUEST['Ratings']))
		{
			$arr		 = Yii::app()->request->getParam('Ratings');
			$bookingId	 = ($arr['rtg_booking_id'] != '') ? $arr['rtg_booking_id'] : '';
			$driverName	 = ($arr['rtg_driver_name'] != '') ? $arr['rtg_driver_name'] : '';
		}
		$qry			 = ['bookingId' => $bookingId, 'driverName' => $driverName, 'vendorId' => $vendorId];
		$dataProvider	 = $model->getListByVendorId($qry);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$venModel		 = Vendors::model()->findByPk($vendorId);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->renderPartial('listbyvendor', array(
			'model'			 => $model,
			'venModel'		 => $venModel,
			'dataProvider'	 => $dataProvider
				), false, $outputJs);
	}

	public function actionReplycustomerpage()
	{
		$rtgid			 = Yii::app()->request->getParam('id');
		$ratingModel	 = Ratings::model()->findByPk($rtgid);
		$bookingModel	 = Booking::model()->findByPk($ratingModel->rtg_booking_id);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('reply_customer', array('model' => $ratingModel, 'bmodel' => $bookingModel), false, $outputJs);
	}

	public function actionAjaxcustreply()
	{
		if (isset($_REQUEST['Ratings']))
		{
			$arr								 = Yii::app()->request->getParam('Ratings');
			$model								 = Ratings::model()->findByPk($arr['rtg_id']);
			$model->rtg_customer_reply			 = $arr['rtg_customer_reply'];
			$model->rtg_customer_reply_datetime	 = new CDbExpression('NOW()');
			$model->rtg_customer_reply_by		 = Yii::app()->user->getId();
			$model->rtg_customer_reply_status	 = 1;
			$model->scenario					 = 'replycustomer';
			$customer_name						 = $arr['customer_name'];
			$customer_email						 = $arr['customer_email'];
			$customer_reply						 = $arr['rtg_customer_reply'];
			$booking_id							 = $arr['booking_id'];
			$emailsend							 = new emailWrapper();
			$send								 = $emailsend->reply($customer_name, $customer_email, $customer_reply, 1, $booking_id);
			if ($send)
			{
				if ($model->validate())
				{
					$model->save();
					$bkgid		 = $model->rtg_booking_id;
					$bkgmodel	 = Booking::model()->findByPk($bkgid);
					$desc		 = "CSR replied to customer";
					$userInfo	 = UserInfo::getInstance();
					$eventid	 = BookingLog::CSR_REPLIED_TO_CUSTOMER;
					BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $bkgmodel);
					echo CJSON::encode(['result' => true]);
				}
				else
				{
					echo CJSON::encode(['result' => false]);
				}
			}
			else
			{
				echo CJSON::encode(['result' => false]);
			}
		}
	}

	public function actionReplyvendorpage()
	{
		$rtgid			 = Yii::app()->request->getParam('id');
		$ratingModel	 = Ratings::model()->findByPk($rtgid);
		$bookingModel	 = Booking::model()->findByPk($ratingModel->rtg_booking_id);
		$vendorId		 = $bookingModel->getBookingCabModel()->bcb_vendor_id;
		$vendorModel	 = Vendors::model()->findByPk($vendorId);
		if ($vendorModel->vnd_contact_id != NULL)
		{
			$contactEmail = ContactEmail::model()->findByContactID($vendorModel->vnd_contact_id);
			if ($contactEmail != NULL)
			{
				$vendorModel->vnd_email = $contactEmail[0]->eml_email_address;
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('reply_vendor', array('model' => $ratingModel, 'vmodel' => $vendorModel, 'bmodel' => $bookingModel), false, $outputJs);
	}

	public function actionAjaxvendorreply()
	{
		if (isset($_POST['Ratings']))
		{
			$arr								 = Yii::app()->request->getParam('Ratings');
			$model								 = Ratings::model()->findByPk($arr['rtg_id']);
			$model->rtg_vendor_reply			 = $arr['rtg_vendor_reply'];
			$model->rtg_vendor_reply_datetime	 = new CDbExpression('NOW()');
			$model->rtg_vendor_reply_by			 = Yii::app()->user->getId();
			$model->rtg_vendor_reply_status		 = 1;
			$model->scenario					 = 'replyvendor';
			$vendor_name						 = $arr['vendor_name'];
			$vendor_email						 = $arr['vendor_email'];
			$vendor_reply						 = $arr['rtg_vendor_reply'];
			$booking_id							 = $arr['booking_id'];
			$emailsend							 = new emailWrapper();
			$send								 = $emailsend->reply($vendor_name, $vendor_email, $vendor_reply, 2, $booking_id);
			if ($send)
			{
				if ($model->validate())
				{
					$model->save();
					$bkgid		 = $model->rtg_booking_id;
					$bkgmodel	 = Booking::model()->findByPk($bkgid);
					$desc		 = "CSR replied to vendor";
					$userInfo	 = UserInfo::getInstance();
					$eventid	 = BookingLog::CSR_REPLIED_TO_VENDOR;
					BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $bkgmodel);
					echo CJSON::encode(['result' => true]);
				}
				else
				{
					echo CJSON::encode(['result' => false]);
				}
			}
			else
			{
				echo CJSON::encode(['result' => false]);
			}
		}
	}

	public function GetNpsByRegion($dataNpsByRegionLastYear, $search_items)
	{
		$res = $this->search_stt_Zone($dataNpsByRegionLastYear, $search_items);
		return (($res == null || count($res) == 0) ? 0 : $res[0]['nps']);
	}

	public function search_stt_Zone($array, $search_list)
	{
		$result = array();
		foreach ($array as $key => $value)
		{
			foreach ($search_list as $k => $v)
			{
				if (!isset($value[$k]) || $value[$k] != $v)
				{
					continue 2;
				}
			}
			$result[] = $value;
		}
		return $result;
	}

}
