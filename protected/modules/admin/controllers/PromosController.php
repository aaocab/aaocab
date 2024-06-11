<?php

class PromosController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
			['allow', 'actions' => ['add'], 'roles' => ['promoAdd', 'promoEdit']],
			['allow', 'actions' => ['list'], 'roles' => ['promoList']],
			['allow', 'actions' => ['delpromo'], 'roles' => ['promoDelete']],
			['allow', 'actions' => ['getpromodiscount', 'view', 'getDescription', 'validatecode', 'linkPromoUsers', 'deletePromoUsers', 'getRegionList', 'giftcarduser', 'addgiftcardpartner', 'updategftpartnerstatus'], 'users' => ['@']],
			['deny', 'users' => ['*']],
		);
	}

	public function actionAdd()
	{
		$promoId			 = Yii::app()->request->getParam('promoid');
		$oldData			 = false;
		$oldDataEntity		 = false;
		$oldDataDate		 = false;
		$oldDataCalculation	 = false;
		if ($promoId == '' || $promoId == null)
		{
			$this->pageTitle	= "Add Promo";
			$model				= new Promos();
			$calModel			= new PromoCalculation();
			$dateModel			= new PromoDateFilter();
			$entityModel		= new PromoEntityFilter();
			$model->scenario = "add";
		}
		else
		{
			$this->pageTitle = "Edit Promo";
			$model			 = Promos::model()->findByPk($promoId);
			$calModel		 = PromoCalculation::model()->getByPromoId($promoId);
			$dateModel		 = PromoDateFilter::model()->getByPromoId($promoId);
			$entityModel	 = PromoEntityFilter::model()->getByPromoId($promoId);
			$oldData		 = $model->attributes;
			$model->scenario = "edit";
		}
		if (isset($_REQUEST['Promos']) && isset($_REQUEST['PromoCalculation']))
		{
			$arr1	 = Yii::app()->request->getParam('Promos');
			$arr	 = Yii::app()->request->getParam('PromoDateFilter');
			$arr2	 = Yii::app()->request->getParam('PromoEntityFilter');
			$arr3	 = Yii::app()->request->getParam('PromoCalculation');

			$model->attributes				= $arr1;
			$dateModel->attributes			= $arr;
			$entityModel->attributes		= $arr2;
			$calModel->attributes			= $arr3;
			if($arr1['prm_allow_negative_addon'][0]==1)
			{
			   $model->prm_allow_negative_addon = 1;
			}
			else
			{
				$model->prm_allow_negative_addon = 0;
			}
			if($arr2['pef_area_from_id'] == '')
			{
				$entityModel->pef_area_type_from = null;
			}
			if($arr2['pef_area_to_id'] == '')
			{
				$entityModel->pef_area_type_to = null;
			}
			if($arr2['pef_area_id'] == '')
			{
				$entityModel->pef_area_type = null;
			}
			if ($arr1['prm_valid_from_date'] != '' && $arr1['prm_valid_from_time'] != '')
			{
				$validFromDate			 = DateTimeFormat::DatePickerToDate($arr1['prm_valid_from_date']) . " " . date('H:i:00', strtotime($arr1['prm_valid_from_time']));
				$model->prm_valid_from	 = $validFromDate;
			}
			if ($arr1['prm_valid_upto_date'] != '' && $arr1['prm_valid_upto_time'] != '')
			{
				$validUptoDate			 = DateTimeFormat::DatePickerToDate($arr1['prm_valid_upto_date']) . " " . date('H:i:00', strtotime($arr1['prm_valid_upto_time']));
				$model->prm_valid_upto	 = $validUptoDate;
			}
			if ($arr1['prm_createdate_from_date'] != '' && $arr1['prm_createdate_from_time'] != '')
			{
				$createFromDate				 = DateTimeFormat::DatePickerToDate($arr1['prm_createdate_from_date']) . " " . date('H:i:00', strtotime($arr1['prm_createdate_from_time']));
				$model->prm_createdate_from	 = $createFromDate;
			}
			else
			{
				$model->prm_createdate_from = NULL;
			}
			if ($arr1['prm_createdate_to_date'] != '' && $arr1['prm_createdate_to_time'] != '')
			{
				$createToDate				 = DateTimeFormat::DatePickerToDate($arr1['prm_createdate_to_date']) . " " . date('H:i:00', strtotime($arr1['prm_createdate_to_time']));
				$model->prm_createdate_to	 = $createToDate;
			}
			else
			{
				$model->prm_createdate_to = NULL;
			}
			if ($arr1['prm_pickupdate_from_date'] != '' && $arr1['prm_pickupdate_from_time'] != '')
			{
				$pickupFromDate				 = DateTimeFormat::DatePickerToDate($arr1['prm_pickupdate_from_date']) . " " . date('H:i:00', strtotime($arr1['prm_pickupdate_from_time']));
				$model->prm_pickupdate_from	 = $pickupFromDate;
			}
			else
			{
				$model->prm_pickupdate_from = NULL;
			}
			if ($arr1['prm_pickupdate_to_date'] != '' && $arr1['prm_pickupdate_to_time'] != '')
			{
				$pickupToDate				 = DateTimeFormat::DatePickerToDate($arr1['prm_pickupdate_to_date']) . " " . date('H:i:00', strtotime($arr1['prm_pickupdate_to_time']));
				$model->prm_pickupdate_to	 = $pickupToDate;
			}
			else
			{
				$model->prm_pickupdate_to = NULL;
			}
			if ($arr1['prm_applicable_user'] == '')
			{
				$model->prm_applicable_user = 1;
			}
			else
			{
				$model->prm_applicable_user = 0;
			}
			if ($arr1['prm_activate_on'] == '')
			{
				$model->prm_activate_on = 1;
			}
			else
			{
				$model->prm_activate_on = 0;
			}
			if ($arr1['prm_applicable_type'] == '')
			{
				$model->prm_applicable_type = 1;
			}
			else
			{
				$model->prm_applicable_type = 0;
			}
			if ($arr1['prm_applicable_nexttrip'] == '')
			{
				$model->prm_applicable_nexttrip = 0;
			}
			else
			{
				$model->prm_applicable_nexttrip = 1;
			}
			if ($arr1['prm_logged_in'] == '')
			{
				$model->prm_logged_in = 0;
			}
			else
			{
				$model->prm_logged_in = 1;
			}

			
			$model->prm_applicable_platform = implode(',', $arr1['prm_applicable_platform']);
			$model->prm_usr_cat_type = implode(',', $arr1['prm_usr_cat_type']);

			/* Promo Date filter starts */

			$dateModel->pcd_weeks_create	 = implode(',', $arr['pcd_weeks_create']);
			$dateModel->pcd_weekdays_create	 = implode(',', $arr['pcd_weekdays_create']);
			$dateModel->pcd_monthdays_create = implode(',', $arr['pcd_monthdays_create']);
			$dateModel->pcd_months_create	 = implode(',', $arr['pcd_months_create']);
			$dateModel->pcd_weeks_pickup	 = implode(',', $arr['pcd_weeks_pickup']);
			$dateModel->pcd_weekdays_pickup	 = implode(',', $arr['pcd_weekdays_pickup']);
			$dateModel->pcd_monthdays_pickup = implode(',', $arr['pcd_monthdays_pickup']);
			$dateModel->pcd_months_pickup	 = implode(',', $arr['pcd_months_pickup']);

			/* Promo Date Filter End */

			/* Promo Entity Filter Starts */
			if(in_array(3, $arr2['pef_booking_type']))
			{
				array_push($arr2['pef_booking_type'],'2');
			}
			$entityModel->pef_booking_type	 = implode(',', $arr2['pef_booking_type']);
			$entityModel->pef_cab_type		 = implode(',', $arr2['pef_cab_type']);
			$model->cabType						 = $entityModel->pef_cab_type;
			$model->pcnType						 = $calModel->pcn_type;
			/* Promo Entity Filter End */
			if ($oldData != false)
			{
				$model->prm_log = $model->addLog($oldData, $model->attributes, $model->prm_log);
			}
			/* Promo Calculation Start */
			$result = $model->saveInfo($calModel, $entityModel, $dateModel);

			if (Yii::app()->request->isAjaxRequest)
			{
				$data = $result;
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		$model->prm_applicable_platform = explode(',', $model->prm_applicable_platform);
		$model->prm_usr_cat_type = explode(',', $model->prm_usr_cat_type);
		if ($promoId > 0)
		{
			if ($dateModel->pcd_monthdays_create != null || $dateModel->pcd_months_create != null || $dateModel->pcd_weekdays_create != null || $dateModel->pcd_weeks_create != null)
			{
				$dateModel->pcd_monthdays_create = explode(',', $dateModel->pcd_monthdays_create);
				$dateModel->pcd_months_create	 = explode(',', $dateModel->pcd_months_create);
				$dateModel->pcd_weekdays_create	 = explode(',', $dateModel->pcd_weekdays_create);
				$dateModel->pcd_weeks_create	 = explode(',', $dateModel->pcd_weeks_create);
			}
			if ($dateModel->pcd_monthdays_pickup != null || $dateModel->pcd_months_pickup != null || $dateModel->pcd_weekdays_pickup != null || $dateModel->pcd_weeks_pickup != null)
			{
				$dateModel->pcd_monthdays_pickup = explode(',', $dateModel->pcd_monthdays_pickup);
				$dateModel->pcd_months_pickup	 = explode(',', $dateModel->pcd_months_pickup);
				$dateModel->pcd_weekdays_pickup	 = explode(',', $dateModel->pcd_weekdays_pickup);
				$dateModel->pcd_weeks_pickup	 = explode(',', $dateModel->pcd_weeks_pickup);
			}
			$entityModel->pef_cab_type		 = explode(',', $entityModel->pef_cab_type);
			$entityModel->pef_booking_type	 = explode(',', $entityModel->pef_booking_type);
		}

		$this->render('add', array('model' => $model, 'calModel' => $calModel, 'dateModel' => $dateModel, 'entityModel' => $entityModel), false, true);
	}

	public function actionView()
	{
		$this->pageTitle = "Promo View";
		$id				 = Yii::app()->request->getParam('promoid');
		$promoModel		 = Promos::model()->findByPk($id);

		$entityFilterModel = PromoEntityFilter::model()->getByPromoId($id);
		if (!$entityFilterModel)
		{
			$entityFilterModel = new PromoEntityFilter();
		}

		$createdateFilterModel = PromoDateFilter::model()->getByPromoId($id);
		if (!$createdateFilterModel)
		{
			$createdateFilterModel = new PromoDateFilter();
		}

		$pickupdateFilterModel = PromoDateFilter::model()->getByPromoId($id);
		if (!$pickupdateFilterModel)
		{
			$pickupdateFilterModel = new PromoDateFilter();
		}
		if ($createdateFilterModel == '' && $pickupdateFilterModel)
		{
			$dateModel = $pickupdateFilterModel;
		}
		else
		{
			$dateModel = $createdateFilterModel;
		}
		$this->render('view', array('promoModel' => $promoModel, 'entityModel' => $entityFilterModel, 'dateModel' => $dateModel));
	}

	public function actionList()
	{
		$this->pageTitle				 = "Promo List";
		$model							 = new Promos();
		$model->prm_applicable_platform	 = '0';
		$model->prm_applicable_user		 = ['0', '1'];
		$model->prm_applicable_type		 = ['0', '1'];
		$model->prm_validity			 = ['0'];
		if (isset($_REQUEST['Promos']))
		{
			$model->attributes	 = Yii::app()->request->getParam('Promos');
			$model->prm_validity = $_REQUEST['Promos']['prm_validity'];
		}
		$model->resetScope();
		//$dataProvider = $model->promoListing();
		$dataProvider = $model->getList();
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionDelpromo()
	{

		$id = Yii::app()->request->getParam('pid');
		if ($id != '')
		{
			$model = Promos::model()->findByPk($id);
			if (count($model) == 1)
			{

				$model->prm_active = 0;
				$model->update();
			}
		}
		$this->redirect(array('list'));
	}

	public function actionGetpromodiscount()
	{
		$success		 = false;
		$params			 = $_GET;
		$promoCredits	 = 0;
		$request		 = Yii::app()->request;
		$code			 = $request->getParam('code');
		//$userid = Yii::app()->request->getParam('userId');
		$amount			 = $request->getParam('amount');
		$pdate			 = $request->getParam('pickupDate');
		$ptime			 = $request->getParam('pickupTime');
		$bkgId			 = $request->getParam('bkgId');
		$fromCityId		 = $request->getParam('fromCityId');
		$toCityId		 = $request->getParam('toCityId');
		$email			 = $request->getParam('email');
		$phone			 = $request->getParam('phone');
		$oldCode		 = $request->getParam('oldCode');
		$carType		 = $request->getParam('carType');
		$bookingType	 = $request->getParam('bookingType');
		$contactId       = $request->getParam('contactId');
		$cpAddon        = $request->getParam('cpAddon');
		$date			 = DateTimeFormat::DatePickerToDate($pdate);
		$time			 = date('H:i:00', strtotime($ptime));
		$returnSet = new ReturnSet();
		try{
			if ($code != '')
			{
				$bookingModel				 = new Booking();
				$bookingModel->bkgInvoice	 = new BookingInvoice();
				$bookingModel->bkgUserInfo	 = new BookingUser();
				$bookingModel->bkgPref		 = new BookingPref();
				$bookingModel->bkgTrail		 = new BookingTrail();

				$jsonObj				 = new stdClass();
				$jsonObj->promo->code	 = $code;
				$jsonObj->eventType		 = 1;
				$jsonObj->wallet		 = 0;
//				$jsonObj->gozoCoins		 = $bookingModel->bkgInvoice->getAppliedGozoCoins();
				$jsonMapper				 = new JsonMapper();
				$obj					 = $jsonMapper->map($jsonObj, new Stub\common\Promotions());

				$bookingModel->bkg_create_date				 = date('Y-m-d H:i:s', time());
				$bookingModel->bkg_pickup_date				 = $date . ' ' . $time;
				$bookingModel->bkg_from_city_id				 = $fromCityId;
				$bookingModel->bkg_to_city_id				 = $toCityId;
				$bookingModel->bkg_booking_type				 = $bookingType;
				$bookingModel->bkg_vehicle_type_id			 = $carType;

				$bookingModel->bkgInvoice->bkg_base_amount	 = $amount;
				$bookingModel->bkgInvoice->bivBkg			 = $bookingModel;
				$bookingModel->bkgTrail->bkg_platform		 = 2;
				//$bookingModel->bkgUserInfo->bkg_user_id		 = UserInfo::getUserId();
				if($contactId=='' || $contactId == 0)
				{
				  $contactId = Contact::getByEmailPhone($email, $phone);
				}
				if($contactId>0)
				{
					$userId = ContactProfile::model()->findByContactId($contactId)->cr_is_consumer;
					if($userId>0)
					{
						$bookingModel->bkgUserInfo->bkg_user_id = $userId;
					}
				}
				if($cpAddon!='')
				{
					$bookingModel->bkgInvoice->bkg_addon_details = json_encode([0=>$cpAddon]);
				}
				BookingInvoice::evaluatePromoCoins($bookingModel, $obj->eventType, '', $obj->promo->code, false);

				$response	 = new Stub\common\Promotions();
				$response->setData($bookingModel->bkgInvoice, $obj->eventType);
				$message	 = $obj->getMessage($bookingModel->bkgInvoice, $obj->eventType);

				$returnSet->setStatus(true);
				$returnSet->setData($response);
				$returnSet->setMessage($message);
			}
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			echo CJSON::encode(['success'=>false,'errors'=>$returnSet->getErrors()]);
			Yii::app()->end();
		}
		echo CJSON::encode($returnSet);
	}

	public function actionGetDescription()
	{
		$calId		 = Yii::app()->request->getParam('calId');
		$entityId	 = Yii::app()->request->getParam('entityId ');
		$cDateId	 = Yii::app()->request->getParam('cDateId');
		$pDateId	 = Yii::app()->request->getParam('pDateId');
		$success	 = false;
		$desc		 = '';
		if ($calId != '' || $calId != null)
		{
			$calModel	 = PromoCalculation::model()->findByPk($calId);
			$success	 = true;
			$desc		 = $calModel->pcn_desc;
		}
		if ($entityId != '' || $entityId != null)
		{
			$entityModel = PromoEntityFilter::model()->findByPk($entityId);
			$success	 = true;
			$desc		 = $entityModel->pef_desc;
		}
		if ($cDateId != '' || $cDateId != null)
		{
			$cDataModel	 = PromoDateFilter::model()->findByPk($cDateId);
			$success	 = true;
			$desc		 = $cDataModel->pcd_desc;
		}
		if ($pDateId != '' || $pDateId != null)
		{
			$pDateModel	 = PromoDateFilter::model()->findByPk($pDateId);
			$success	 = true;
			$desc		 = $pDateModel->pcd_desc;
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'desc' => $desc];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionValidatecode()
	{
		$code					 = Yii::app()->request->getParam('code');
		$userid					 = Yii::app()->request->getParam('userid');
		$email					 = Yii::app()->request->getParam('email');
		$bkgid					 = Yii::app()->request->getParam('bkgid');
		$promomodel				 = Promos::model()->getByCode($code);
		$bkgmodel				 = Booking::model()->findByPk($bkgid);
		$promomodel->promoCode	 = $code;
		$promomodel->totalAmount = $bkgmodel->bkgInvoice->bkg_base_amount;
		$promomodel->createDate	 = $bkgmodel->bkg_create_date;
		$promomodel->pickupDate	 = $bkgmodel->bkg_pickup_date;
		$promomodel->fromCityId	 = $bkgmodel->bkg_from_city_id;
		$promomodel->toCityId	 = $bkgmodel->bkg_to_city_id;
		$promomodel->userId		 = $bkgmodel->bkgUserInfo->bkg_user_id;
		$promomodel->platform	 = $bkgmodel->bkgTrail->bkg_platform;
		$promomodel->carType	 = $bkgmodel->bkg_vehicle_type_id;
		$promomodel->bookingType = $bkgmodel->bkg_booking_type;
		$promomodel->email		 = '';
		$promomodel->phone		 = '';
		$promomodel->imEfect	 = '';
		//$result					 = $promomodel->validatePromoCode($bkgmodel->bkg_pickup_date, $bkgmodel->bkg_create_date, $bkgmodel->bkg_from_city_id, $bkgmodel->bkg_to_city_id, $bkgmodel->bkgUserInfo->bkg_user_id, $bkgmodel->bkgInvoice->bkg_base_amount, $bkgmodel->bkgTrail->bkg_platform);
		$result					 = $promomodel->validatePromoCode();
		if ($result)
		{
			$expdate	 = date('d/m/Y', strtotime($promomodel->prm_valid_upto));
			$calModel	 = $promomodel->prmCal;
			if ($calModel->pcn_value_type_cash != '')
			{
				$damount = ($calModel->pcn_value_type_cash == 1) ? $calModel->pcn_value_cash . '% Cash' : 'Rs. ' . $calModel->pcn_value_cash;
			}
			if ($calModel->pcn_value_type_cash != '' && $calModel->pcn_value_type_coins != '')
			{
				$damount .= ' AND ';
			}
			if ($calModel->pcn_value_type_coins != '')
			{
				$damount .= ($calModel->pcn_value_type_coins == 1) ? $calModel->pcn_value_coins . '% Gozocoins' : 'Gozocoins. ' . $calModel->pcn_value_coins;
			}
			$email1		 = new emailWrapper();
			$email1->sendPromocode($email, $code, $bkgid, $expdate, $damount);
			//Create Log
			//$bkgModel	 = Booking::model()->findByPk($bkgid);
			$eventid	 = BookingLog::DISCOUNT_CODE_SENT;
			$desc		 = 'Discount code sent to user';
			$userInfo	 = UserInfo::getInstance();
			BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $bkgmodel);
			$status		 = 'true';
		}
		else
		{
			$status = 'false';
		}

		echo CJSON::encode(array('status' => $status));
	}

//	public function actionPromoApply()
//	{
//		$first	 = microtime(true);
//		$amount	 = Promos::model()->applyPromoCode('Test90', 710627);
//		echo json_encode($amount);
//		$last	 = microtime(true);
//		echo ($last - $first);
//	}

	public function actionLinkPromoUsers()
	{
		$promoId = Yii::app()->request->getParam('promoId');
		$userId	 = Yii::app()->request->getParam('userId');
		$pruId	 = Yii::app()->request->getParam('pruId');

		$useMax		 = Yii::app()->request->getParam('maxUse');
		$autoApply	 = Yii::app()->request->getParam('autoApply');
		$validFrom	 = Yii::app()->request->getParam('validFrom');
		$validUpto	 = Yii::app()->request->getParam('validUpto');
		if ($pruId == '')
		{
			$pruId = PromoUsers::model()->checkPromoAndUser($promoId, $userId);
		}
		if ($pruId > 0)
		{
			$promoUserModel = PromoUsers::model()->findByPk($pruId);
		}
		else
		{
			$promoUserModel = new PromoUsers();
		}
		$this->pageTitle = "Link User";
		//if (isset($_REQUEST['PromoUsers']))
		//{
		$success		 = false;
		$error			 = '';
		$arr			 = Yii::app()->request->getParam('PromoUsers');
		$transaction	 = DBUtil::beginTransaction();
		try
		{
			//$promoUserModel->attributes		 = $arr;
			$promoUserModel->pru_use_max	 = $useMax;
			$promoUserModel->pru_auto_apply	 = $autoApply;
			$promoUserModel->pru_promo_id	 = $promoId;
			$promoUserModel->pru_ref_id		 = $userId;
			$promoUserModel->pru_active		 = 1;
			$promoUserModel->pru_ref_type	 = 0;
			if ($validFrom != '')
			{
				$validFromDate					 = DateTimeFormat::DatePickerToDate($validFrom) . " 00:00:00";
				$promoUserModel->pru_valid_from	 = $validFromDate;
			}
			if ($validUpto != '')
			{
				$validUptoDate					 = DateTimeFormat::DatePickerToDate($validUpto) . " 00:00:00";
				$promoUserModel->pru_valid_upto	 = $validUptoDate;
			}

			$result = CActiveForm::validate($promoUserModel, null, false);

			if ($result == '[]')
			{
				$promoUserModel->pru_created		 = new CDbExpression('NOW()');
				$promoUserModel->pru_next_trip_apply = 0;
				if (!$promoUserModel->save())
				{
					throw new Exception('Failed to save');
				}
				$success = true;
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			$error = "Failed to save";
			DBUtil::rollbackTransaction($transaction);
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'error' => $error];
			echo json_encode($data);
			Yii::app()->end();
		}
		//}
		$this->renderPartial('linkpromousers', array('promoUserModel' => $promoUserModel, 'promoId' => $promoId, 'refId' => $userId, 'pruId' => $pruId), false, true);
	}

	public function actionDeletePromoUsers()
	{
		$id		 = Yii::app()->request->getParam('pruId');
		$promoId = Yii::app()->request->getParam('promoId');
		if ($id != '')
		{
			$model = PromoUsers::model()->findByPk($id);
			if (count($model) == 1)
			{
				if ($model->pru_active == 0)
				{
					$model->pru_active = 1;
				}
				else
				{
					$model->pru_active = 0;
				}
				if ($model->update())
				{
					$success = true;
				}
				else
				{
					$success = false;
				}
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	function actionGetRegionList()
	{
		$data = Promos::getRegionJSON();
		echo $data;
		Yii::app()->end();
	}

	public function actionGiftCardUser()
	{
		$this->pageTitle	 = "Gift Card User";
		$request			 = Yii::app()->request;
		$model				 = new Agents('search');
		$promoId		 = $request->getParam('promoId');
		//$dataProvider	 = $model->getDuplicateContact($arr, $cttid, $type, $vnd_id);
		if (isset($_REQUEST['Agents']))
		{
			$model->search		 = trim($_REQUEST['Agents']['search']);
			$model->attributes	 = $request->getParam('GiftCardUser');
		}
		$dataProvider	 = $model->getAgentDetails($promoId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('giftcarduser', array('model' => $model, 'promoId' => $promoId, 'dataProvider' => $dataProvider), null, $outputJs);
	}
	
	public function actionaddgiftcardpartner()
	{
		$promoId = Yii::app()->request->getParam('promoId');
		$agentId = Yii::app()->request->getParam('agt_id');
		$status = Yii::app()->request->getParam('status');
		$gftPartnerId = GiftCardPartner::model()->getGiftCardPartnerId($promoId, $agentId);
		if ($gftPartnerId == '' || $gftPartnerId == false)
		{
			$model = new GiftCardPartner();
		}
		else
		{
			$model = GiftCardPartner::model()->findByPk($gftPartnerId);
		}
			$model->prp_promo_id   = $promoId;
			$model->prp_partner_id = $agentId;
			$model->prp_active = $status;
		
			$result = $model->saveGiftCardInfo();
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = $result;
				echo json_encode($data);
				Yii::app()->end();
			}
	}

	public function actionUpdateGftPartnerStatus()
	{
		$agentId		 = Yii::app()->request->getParam('agt_id');
		$promoId = Yii::app()->request->getParam('promoId');
		if ($agentId != '')
		{
			$gftPartnerId = GiftCardPartner::model()->getGiftCardPartnerId($promoId, $agentId);
			if ($gftPartnerId == '' || $gftPartnerId == false)
			{
				$model = new GiftCardPartner();
				$model->prp_promo_id   = $promoId;
				$model->prp_partner_id = $agentId;
			}
			else
			{
				$model = GiftCardPartner::model()->findByPk($gftPartnerId);
				if ($model->prp_active == 0)
				{
					$model->prp_active = 1;
                    $msg =   "";//"User added successfully to this promo gift card";
                    $imgpath = "\images\icon\active.png";
				}
				else
				{
					$model->prp_active = 0;
                    $msg =   "User removed successfully from this promo gift card";
                    $imgpath = "\images\icon\inactive.png";
					$maxUser = -1;
				}
			}
			if ($model->save())
			{
				$success = true;
			}
			else
			{
				$success = false;
			}
				
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success,'msg' => $msg,'imgpath'=>$imgpath];
			echo json_encode($data);
		}
	}
}


