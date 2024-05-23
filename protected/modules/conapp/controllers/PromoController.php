<?php

use Booking;

include_once(dirname(__FILE__) . '/BaseController.php');

class PromoController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'main';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';
	public $title		 = '';

//public $layout = '//layouts/column2';pre

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
		return array(
			'REST.' => 'RestfullYii.actions.ERestActionProvider',
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(
					'REST.GET', 'REST.PUT', 'REST.REQUEST', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS', 'generateInvoice'),
				'users'		 => array('*'),
			),
			['allow',
				'actions'	 => ['new', 'list'],
				'users'		 => ['@']
			],
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
			$ri	 = array('/applyRemovePromos', '/creditApplyRemove', '/getActivePromoCredits', '/applyPromo');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		// Promotions Apply
		$this->onRest('req.post.applyPromo.render', function () {
			return $this->renderJSON($this->applyPromo());
		});

		// Wallet Apply
		$this->onRest('req.post.applyWallet.render', function () {
			return $this->renderJSON($this->applyWallet());
		});

		// Promotions Remove
		$this->onRest('req.post.removePromo.render', function () {
			return $this->renderJSON($this->removePromo());
		});

		//promo Apply and Remove
		$this->onRest('req.post.applyRemovePromos.render', function () {
			return $this->renderJSON($this->applyRemovePromos());
		});

		//Credit Apply and Remove
		$this->onRest('req.post.creditApplyRemove.render', function () {
			return $this->renderJSON($this->creditApplyRemove());
		});

		// Get Active Promo & Credits
		$this->onRest('req.post.getActivePromoCredits.render', function () {
			return $this->renderJSON($this->getActivePromoCredits());
		});
	}

	public function creditApplyRemove()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			/* @var $model AppTokens */
			$model		 = AppTokens::validateToken($token);
			$data		 = Yii::app()->request->rawBody; // '{"bookingId":1196012,"credits":"50","event":1}'
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			Logger::create("Request : " . json_encode($data), CLogger::LEVEL_INFO);
			if (!$data)
			{
				throw new Exception("Invalid Request", ReturnSet::ERROR_INVALID_DATA);
			}
			/** @var $obj Stub\booking\CreditRequest */
			$obj = $jsonMapper->map($jsonObj, new Stub\booking\CreditRequest());
			if (!$obj->credits)
			{
				throw new Exception("Give Some Credit Data: ", ReturnSet::ERROR_INVALID_DATA);
			}
			/** @var Booking $model */
			$model = $obj->getModel();
			if ($model->bkg_id > 0)
			{
				$model = Booking::model()->findByPk($model->bkg_id);
			}
			if (!$model)
			{
				throw new Exception("Invalid Data: ", ReturnSet::ERROR_INVALID_DATA);
			}
			if (!in_array($obj->event, ['1', '2']))
			{
				throw new Exception("Invalid event request: ", ReturnSet::ERROR_VALIDATION);
			}
			$returnSet = $this->applyRemoveCredits($model->bkg_id, $obj->credits, $obj->event);

			$model->bkgInvoice->bkg_base_amount				 = (int) $returnSet->getData()['base_amount'];
			$model->bkgInvoice->bkg_service_tax				 = (int) $returnSet->getData()['service_tax'];
			$model->bkgInvoice->bkg_driver_allowance_amount	 = (int) $returnSet->getData()['driver_allowance'];
			$model->bkgInvoice->bkg_due_amount				 = (int) $returnSet->getData()['due_amount'];
			$model->bkgInvoice->bkg_total_amount			 = (int) $returnSet->getData()['total_amount'];
			$model->bkgInvoice->bkg_discount_amount			 = (int) $returnSet->getData()['discount'];
			$model->bkgInvoice->bkg_state_tax				 = (int) $returnSet->getData()['state_tax'];
			$model->bkgInvoice->bkg_temp_credits			 = (int) $returnSet->getData()['credits_used'];
			if ($returnSet->isSuccess())
			{
				$returnSet->setStatus(true);
				$result		 = Filter::convertToObject($returnSet->getData());
				/* @var $response Stub\booking\CreditResponse */
				$response	 = new Stub\booking\CreditResponse();
				$response->setData($model, $result, $model->bkgInvoice);
				$response	 = Filter::removeNull($response);
				$returnSet->setData($response);
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("You have limited credit.");
			}

			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function applyRemovePromos()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody; //'{"bookingId":1196012,"code":"AUG9","event":1}';  // 1 => applied, 2 => remove
			if (!$data)
			{
				throw new Exception("Invalid Request", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . ($data), CLogger::LEVEL_INFO);
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			/* @var $obj Stub\booking\PromoRequest */
			$obj		 = $jsonMapper->map($jsonObj, new Stub\booking\PromoRequest());
			/* @var Booking $model */
			$model		 = $obj->getModel();
			if ($model->bkg_id > 0)
			{
				$model = Booking::model()->findByPk($model->bkg_id);
			}
			if (!$model)
			{
				throw new Exception("Invalid Data: ", ReturnSet::ERROR_INVALID_DATA);
			}
			if (!in_array($obj->event, ['1', '2']))
			{
				throw new Exception(CJSON::encode("Invalid event request: "), ReturnSet::ERROR_VALIDATION);
			}
			if ($obj->event == 1)
			{
				$flag			 = ($model->bkgInvoice->bkg_promo1_id > 0) ? 1 : 0;
				$credits		 = ($model->bkgInvoice->bkg_credits_used > 0 ? $model->bkgInvoice->bkg_credits_used : $model->bkgInvoice->bkg_temp_credits);
				$coinPromoStatus = ($credits > 0) ? 1 : 0;
				$result			 = $this->promoService($flag, $obj->code, $model->bkg_id, $credits, $coinPromoStatus);
			}
			else if ($obj->event == 2)
			{
				$isRemoveOnly	 = true;
				$service		 = 0;
				$result			 = $this->promoRemoveService($isRemoveOnly, $service, $model->bkg_id);
			}
			/** @var Promos $promoModel */
			$promoModel	 = Promos::model()->findByPk($result['promo_id']);
			$message	 = $result['message'];
			//Convert array to std object
			$result		 = Filter::convertToObject($result);
			/* @var $response Stub\booking\PromoResponse */
			if ($result->result == true)
			{
				$model->bkgInvoice->bkg_base_amount				 = (int) $result->base_amount;
				$model->bkgInvoice->bkg_service_tax				 = (int) $result->service_tax;
				$model->bkgInvoice->bkg_driver_allowance_amount	 = (int) $result->driver_allowance;
				$model->bkgInvoice->bkg_due_amount				 = (int) $result->due_amount;
				$model->bkgInvoice->bkg_total_amount			 = (int) $result->total_amount;
				$model->bkgInvoice->bkg_discount_amount			 = (int) $result->discount;
				$response										 = new \Stub\booking\PromoResponse();
				$response->setData($model, $promoModel, $message, $model->bkgInvoice);
				$response										 = Filter::removeNull($response);
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage($result->message);
			}
			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param string  $credits
	 * @param integer $eventType
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function applyRemoveCredits($bkgId, $credits, $eventType = 0)
	{
		$success	 = false;
		$returnSet	 = new ReturnSet();
		$userInfo	 = UserInfo::model();
		try
		{
			if ($eventType == 1) // apply credits
			{
				$model			 = Booking::model()->findbyPk($bkgId);
				$isPromoApply	 = ($model->bkgInvoice->bkg_promo1_id > 0) ? 1 : 0;
				$applyCredits	 = ($credits > 0) ? $credits : 0;

				//Check Promo Appplied or not
				if ($isPromoApply)
				{
					$model->bkgInvoice = self::promoRemoveServiceData($model->bkg_id, 0, $applyCredits, 2);
				}

				//Check Balance
				$actualCredit = UserCredits::model()->getTotalActiveCredits($model->bkgUserInfo->bkg_user_id);
				if (($actualCredit < $applyCredits) && $applyCredits > 0)
				{
					$message = 'You have limited amount credit balance.';
					$returnSet->setMessage($message);
					$success = false;
					return $returnSet;
				}

				//Apply Gozocoins                 
				if ($applyCredits > 0)
				{
					//data without COD
					$model2 = clone $model;

					$userCreditStatus = UserCredits::model()->getGozocoinsUsesStatus($model2->bkgUserInfo->bkg_user_id);

					$model2->bkgInvoice->calculateConvenienceFee(0);
					$model2->bkgInvoice->calculateTotal();
					$usepromo								 = ($model->bkgInvoice->bkg_promo1_id == 0);
					$success								 = true;
					$MaxCredits								 = UserCredits::getApplicableCredits($model2->bkgUserInfo->bkg_user_id, $model2->bkgInvoice->bkg_base_amount, $usepromo, $model->bkg_from_city_id, $model->bkg_to_city_id);
					$model2->bkgInvoice->bkg_temp_credits	 = min([$applyCredits, $MaxCredits["credits"], $model2->bkgInvoice->bkg_total_amount]);
//                    if ($isWalletUsed == 1 && UserInfo::getUserId() > 0 && $amtWalletUsed > 0)
//                    {
//                        $totWalletBalance                       = UserWallet::getBalance(UserInfo::getUserId());
//                        $totWalletBalance                       = ($model2->bkgInvoice->bkg_due_amount < $totWalletBalance) ? $model2->bkgInvoice->bkg_due_amount : $totWalletBalance;
//                        $amtWalletUsed                          = ($amtWalletUsed > $totWalletBalance) ? $totWalletBalance : $amtWalletUsed;
//                        $model2->bkgInvoice->bkg_advance_amount += $amtWalletUsed;
//                    }
					$model2->bkgInvoice->calculateTotal();
					$fareAmtWithoutConvFee					 = $model2->bkgInvoice->bkg_total_amount;
					$fareDueWithoutConvFee					 = $model2->bkgInvoice->bkg_due_amount;
					$fareTaxWithoutConvFee					 = round($model2->bkgInvoice->bkg_service_tax);
					//data without COD
					//data with COD
					$model1									 = clone $model2;
					$model1->bkgInvoice->calculateConvenienceFee(0);
					$model1->bkgInvoice->calculateTotal();
					$fareTaxWithConvFee						 = round($model1->bkgInvoice->bkg_service_tax);
					//data with COD

					$refundAmount			 = $MaxCredits['refundCredits'];
					$fareBaseAmount			 = $model2->bkgInvoice->bkg_base_amount;
					$fareDriverAllowance	 = $model2->bkgInvoice->bkg_driver_allowance_amount;
					$creditUsed				 = $model2->bkgInvoice->bkg_temp_credits;
					$fareDiscount			 = $model2->bkgInvoice->bkg_discount_amount;
					$minpay					 = $model2->bkgInvoice->calculateMinPayment();
					$fareConvFee			 = $model1->bkgInvoice->bkg_total_amount - $model2->bkgInvoice->bkg_total_amount; //+ Filter::getServiceTax($model->bkg_convenience_charge);           
					$fareTollTax			 = $model2->bkgInvoice->bkg_toll_tax;
					$fareStateTax			 = $model2->bkgInvoice->bkg_state_tax;
					$fareAmountWithConvFee	 = round($model1->bkgInvoice->bkg_due_amount);
					if ($isAdvDiscount)
					{
						$model->bkgInvoice->bkg_temp_credits = $creditUsed;
						$model->bkgInvoice->calculateTotal();
						$fareAmountWithConvFee				 = round($model->bkgInvoice->bkg_due_amount);
					}

					$percent30ofAmt = round(($fareAmtWithoutConvFee * 0.3));

					if ($creditUsed >= $percent30ofAmt)
					{
						$fareConvFee						 = 0;
						$model->bkgInvoice->bkg_temp_credits = $creditUsed;
						$model->bkgInvoice->calculateConvenienceFee(0);
						$model->bkgInvoice->calculateTotal();
						$fareAmountWithConvFee				 = $model->bkgInvoice->bkg_due_amount;
					}
					if ($creditUsed > 0)
					{
						$model->bkgInvoice->save();
						$credit = true;
					}
					$isPromoCodeUsed	 = ($model->bkgInvoice->bkg_promo1_id != 0) ? 1 : 0;
					$userInfo->userType	 = UserInfo::TYPE_CONSUMER;
					$userInfo->userId	 = $model->bkgUserInfo->bkg_user_id;
					$msgLog				 = ($applyCredits > 1) ? 'Credit ' . $applyCredits . ' applied successfully .(not confirmed)' : 'Credit ' . $applyCredits . ' used successfully.(confirmed)';
					BookingLog::model()->createLog($model->bkg_id, $msgLog, $userInfo, BookingLog::BOOKING_PROMO, false, ['blg_ref_id' => BookingLog::REF_PROMO_GOZOCOINS_APPLIED]);
					$msg				 = "Credit ' . $applyCredits . ' Applied successfully.";
				}

//				if ($model->bkgInvoice->bkg_promo1_id > 0)
//				{
//					$isRemoveOnly	 = true;
//					$service		 = 0;
//					$result			 = $this->promoRemoveService($isRemoveOnly, $service, $model->bkg_id);
//				}
			}
			else if ($eventType == 2)  // remove credits
			{
				if (isset($bkgId))
				{
					$isAdvDiscount	 = false;
					$removedCredits	 = ($credits > 0) ? $credits : 0;
					$model			 = Booking::model()->findbyPk($bkgId);

					if (!$model)
					{
						throw new Exception('Invalid data.', ReturnSet::ERROR_INVALID_DATA);
					}


					$model1				 = clone $model;
					$model1->bkgInvoice->calculateConvenienceFee(0);
					$userCreditStatus	 = UserCredits::model()->getGozocoinsUsesStatus($model1->bkgUserInfo->bkg_user_id);

					$model1->bkgInvoice->bkgFlexxiMinPay = 1;
					$usepromo							 = ($model1->bkgInvoice->bkg_promo1_id == 0);
					$MaxCredits							 = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model1->bkgInvoice->bkg_base_amount, $usepromo, $model->bkg_from_city_id, $model->bkg_to_city_id);
					$creditVal							 = $MaxCredits['credits'];
					$refundAmount						 = $MaxCredits['refundCredits']; //added for show proper coin
					$success							 = true;
					$minpay								 = $model1->bkgInvoice->calculateMinPayment();
					$fareDueWithoutConvFee				 = $model1->bkgInvoice->bkg_due_amount;
					$fareTaxWithoutConvFee				 = $model1->bkgInvoice->bkg_service_tax;
					$fareDriverAllowance				 = $model1->bkgInvoice->bkg_driver_allowance_amount;
					$fareBaseAmount						 = $model->bkgInvoice->bkg_base_amount;
					$model1->bkgInvoice->bkgFlexxiMinPay = 0;
					//data without cod
					$model2								 = clone $model;
					$model2->bkgInvoice->calculateConvenienceFee(0);
					$model2->bkgInvoice->calculateTotal();
					//data without cod
					$fareAmtWithoutConvFee				 = $model2->bkgInvoice->bkg_total_amount;
					$fareConvFee						 = $model->bkgInvoice->bkg_total_amount - $model2->bkgInvoice->bkg_total_amount;
					$amountWithConvFee					 = round($model->bkgInvoice->bkg_due_amount);
					$discAdvDue							 = $model1->bkgInvoice->bkg_due_amount;
					$fareTollTax						 = $model1->bkgInvoice->bkg_toll_tax;
					$fareStateTax						 = $model1->bkgInvoice->bkg_state_tax;
					$fareDiscount						 = 0;
					$model->bkgInvoice->bkg_temp_credits = 0;
					//Log Data
					if (!$model->bkgInvoice->save())
					{
						throw new Exception(CJSON::encode($model->bkgInvoice->getErrors()), ReturnSet::ERROR_VALIDATION);
					}
					if (!$model->bkgUserInfo->save())
					{
						throw new Exception(CJSON::encode($model->bkgUserInfo->getErrors()), ReturnSet::ERROR_VALIDATION);
					}
					$success				 = true;
					$credit					 = false;
					$creditRemove			 = true;
					$msg					 = "Credit ' . $removedCredits . ' removed successfully.";
					$userInfo->userType		 = UserInfo::TYPE_CONSUMER;
					$userInfo->userId		 = $model->bkgUserInfo->bkg_user_id;
					$params['blg_ref_id']	 = BookingLog::REF_PROMO_GOZOCOINS_REMOVED;
					$msgLog					 = ($removedCredits > 1) ? 'Credit ' . $removedCredits . ' removed successfully .(not confirmed)' : 'Credit ' . $removedCredits . ' removed successfully.(confirmed)';
					BookingLog::model()->createLog($model->bkg_id, $msgLog, $userInfo, BookingLog::BOOKING_PROMO, false, $params);
				}
			}

			//app
			$data = [
				'result'					 => $success,
				'apply'						 => $success,
				'newtotal'					 => $fareAmtWithoutConvFee,
				'isPromoCodeUsed'			 => $isPromoCodeUsed,
				'refundCredits'				 => $refundAmount,
				'credits_used'				 => $creditUsed,
				'totCredits'				 => $creditVal,
				'due_amount'				 => $fareDueWithoutConvFee,
				'discount'					 => $fareDiscount,
				'service_tax'				 => $fareTaxWithoutConvFee,
				'service_tax_with_conv_fee'	 => $fareTaxWithConvFee,
				'driver_allowance'			 => $fareDriverAllowance,
				'total_amount'				 => $fareAmtWithoutConvFee,
				'toll_tax'					 => $fareTollTax,
				'state_tax'					 => $fareStateTax,
				'base_amount'				 => $fareBaseAmount,
				'convFee'					 => $fareConvFee,
				'amountWithConvFee'			 => $fareAmountWithConvFee,
				'minPayable'				 => $minpay,
				'isPromo'					 => $isPromo,
				'promo_desc'				 => $promoDescription,
				'promo_code'				 => $promoCode,
				'promo_type'				 => $prmType,
				'message'					 => $msg,
				'isCredit'					 => false,
				'isAdvDiscount'				 => $isAdvDiscount,
				'credit'					 => $credit,
				'creditStatus'				 => $userCreditStatus,
				'discAdv'					 => $discAdvDue,
				'amtWalletUsed'				 => $amtWalletUsed,
				'isPromoApplied'			 => $isPromoApplied | false,
				'isGozoCoinsApplied'		 => $credit,
				'isWalletApplied'			 => ($amtWalletUsed > 0) ? true : false,
				'isRefundCredits'			 => ($refundAmount > 0) ? true : false,
				'promo_id'					 => $promo_id,
				'creditRemove'				 => $creditRemove,
			];
			//print_r($data);exit;
			$returnSet->setStatus(true);
			$returnSet->setData($data);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param boolean $flag
	 * @param string $val
	 * @param integer $bookingId
	 * @param integer $creditApplied
	 * @param integer $coinPromoStatus
	 * @return boolean
	 * @throws CHttpException
	 */
	public function promoService($flag = 0, $val = '', $bookingId = 0, $creditApplied = 0, $coinPromoStatus = 0)
	{
		//new promoservice
		$result		 = false;
		$bkgId		 = $bookingId;
		$bkgpcode	 = $val;
		$model		 = Booking::model()->findbyPk($bkgId);
		if (!$model && $model->bkg_status != 1)
		{
			throw new Exception("Invalid data : ", ReturnSet::ERROR_INVALID_DATA);
		}
		if ($model->bkgTrail->bkg_platform != 3)
		{
			throw new Exception("Invalid data : ", ReturnSet::ERROR_INVALID_DATA);
		}
		//remove promo if already applied
		if ($flag == 1)
		{
			$creditApplied	 = ($creditApplied > 0) ? $creditApplied : 0;
			$result			 = $this->promoRemoveServiceData($bkgId, $hash			 = 0, $creditApplied);
		}
		//remove coin if already applied
		if ($coinPromoStatus == 1)
		{
			$model->bkgInvoice	 = $this->gozoCoinsRemoveData($bkgId, $hash				 = 0, $web				 = 0, 2, $creditApplied);
		}
		if ($prm_id > 0)
		{
			$promoModel1 = Promos::model()->findByPk($prm_id);
			$bkgpcode	 = $promoModel1->prm_code;
		}
		//Apply Promo
		if ($model->bkgInvoice->bkg_promo1_code != '' || $bkgpcode != '')
		{
			$credits = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model->bkgInvoice->bkg_base_amount, false, $model->bkg_from_city_id, $model->bkg_to_city_id);

			$creditVal		 = $credits['credits'];
			$refundCredits	 = $credits['refundCredits'];
			if ($refundCredits == 0 && $creditApplied > 0 && $model->bkgInvoice->bkg_promo1_code != '' && $bkgpcode == '')
			{
				goto next;
			}
			$bkgpcode	 = ($bkgpcode == '') ? $model->bkgInvoice->bkg_promo1_code : $bkgpcode;
			$returnSet	 = Promos::usePromo($bkgpcode, $model->bkg_id);
			if ($returnSet->isSuccess())
			{
				$isPromoApplied	 = true;
				$msg			 = $returnSet->getData()['message'];
				$prmType		 = $returnSet->getData()['promoType'];
				$dueAmount		 = $returnSet->getData()['dueAmt'];
				$prmCode		 = $returnSet->getData()['promoCode'];
				$promoDiscount	 = $returnSet->getData()['promoDiscount'];
				$promoDesc		 = $returnSet->getData()['promoDesc'];
				$serviceTax		 = $returnSet->getData()['gst'];
				$DA				 = $returnSet->getData()['da'];
				$totAmt			 = $returnSet->getData()['totAmt'];
				$baseAmt		 = $returnSet->getData()['baseAmt'];
				$minPay			 = $returnSet->getData()['minPay'];
				$promoId		 = $returnSet->getData()['promoId'];
				$usePromoCredit	 = ($isPromoApplied) ? false : true;
				$credits		 = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $baseAmt, $usePromoCredit, $model->bkg_from_city_id, $model->bkg_to_city_id);
				$creditVal		 = $credits['credits'];
				if ($creditVal > 0)
				{
					$isCredit		 = true;
					$isRefundCredits = true;
					$refundCredits	 = $creditVal;
				}
				$isPromo			 = false;
				$isDiscAfterPayment	 = $returnSet->getData()['isDiscAfterPayment'];
				$result				 = true;
			}
			else
			{
				$result	 = false;
				$msg	 = "Invalid Promo Code";
			}
		}
		else
		{
			$result	 = false;
			$msg	 = "Promo is already removed.";
		}
		next:
		//Apply Gozocoins
//        if ($creditapplied > 0)
//        {
//            $usePromoCredit                         = ($isPromoApplied) ? false : true;
//            $credits                                = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model->bkgInvoice->bkg_base_amount, $usePromoCredit, $model->bkg_from_city_id, $model->bkg_to_city_id);
//            $creditVal                              = $credits['credits'];
//            $refundCredits                          = $credits['refundCredits'];
//            $dueAmount                              = ($dueAmount == '') ? $model->bkgInvoice->bkg_due_amount : $dueAmount;
//            $model->refresh();
//            $model->bkgInvoice->bkg_discount_amount = ($isPromoApplied) ? $promoDiscount : 0;
//            $model->bkgInvoice->bkg_credits_used    = min([$creditapplied, $creditVal, $dueAmount]);
//            $model->bkgInvoice->populateAmount();
//            $dueAmount                              = $model->bkgInvoice->bkg_due_amount;
//            $serviceTax                             = $model->bkgInvoice->bkg_service_tax;
//            $DA                                     = $model->bkgInvoice->bkg_driver_allowance_amount;
//            $totAmt                                 = $model->bkgInvoice->bkg_total_amount;
//            $baseAmt                                = $model->bkgInvoice->bkg_base_amount;
//            $minPay                                 = $model->bkgInvoice->calculateMinPayment();
//            $creditapplied                          = $model->bkgInvoice->bkg_credits_used;
//            $isGozoCoinsApplied                     = true;
//            $isCredit                               = false;
//            $credit                                 = true;
//            $result                                 = true;
//            $isPromo                                = ($isPromoApplied) ? false : ($refundCredits > 0) ? true : false;
//            $userCreditStatus                       = UserCredits::model()->getGozocoinsUsesStatus($model->bkgUserInfo->bkg_user_id);
//            if ($creditVal == 0)
//            {
//                $isGozoCoinsApplied = false;
//            }
//        }
		//app
		$resultData = [
			'result'					 => $result, //a
			'due_amount'				 => $dueAmount, //a
			'service_tax'				 => $serviceTax, //a
			'driver_allowance'			 => $DA, //a
			'total_amount'				 => $totAmt, //a
			'base_amount'				 => $baseAmt, //a
			'convFee'					 => 0, //a
			'amountWithConvFee'			 => $dueAmount, //a
			'minPayable'				 => $minPay, //a
			'apply'						 => $result, //c
			'newtotal'					 => $totAmt, //c
			'creditStatus'				 => $userCreditStatus, //c
			'refundCredits'				 => $refundCredits, //c
			'credit'					 => $credit, //c
			'credits_used'				 => $creditApplied, //c
			'totCredits'				 => $creditVal, //c
			'creditused'				 => $creditApplied, //c
			'coinPromoStatus'			 => $coinPromoStatus, //c
			'isCredit'					 => $isCredit, //c
			'isGozoCoinsApplied'		 => $isGozoCoinsApplied, //c
			'isRefundCredits'			 => $isRefundCredits,
			'isPromoCodeUsed'			 => $isPromoApplied, //p
			'service_tax_with_conv_fee'	 => $serviceTax, //p
			'isPromo'					 => $isPromo, //p
			'promo'						 => $isPromoApplied, //p
			'isPromoApplied'			 => $isPromoApplied, //p
			'message'					 => $msg, //p
			'promo_code'				 => $prmCode, //p
			'promo_type'				 => $prmType, //p 
			'discount'					 => $promoDiscount, //p
			'isAdvDiscount'				 => $isDiscAfterPayment, //p
			'promo_desc'				 => $promoDesc, //p
			'promo_id'					 => $promoId, //p
		];
		return $resultData;
	}

	public function promoRemoveService($isRemoveOnly = false, $service = 0, $bkgid = 0)
	{
		//$bkgid	   = Yii::app()->request->getParam('bkg_id');
		$bkgid			 = ($bkgid == '') ? (Yii::app()->request->getParam('bkg_id')) : $bkgid;
		$hash			 = Yii::app()->request->getParam('bkghash');
		$creditapplied	 = Yii::app()->request->getParam('credit_amount', 0);
		$isRemoveOnly	 = ($isRemoveOnly) ? $isRemoveOnly : Yii::app()->request->getParam('isRemoveOnly');
		$flag2			 = 1;

		if ($service == 0)
		{
			return $this->promoRemoveServiceData($bkgid, $hash, $creditapplied, $flag2, $isRemoveOnly);
		}
		else
		{
			return $this->promoRemoveServiceData($bkgid, $hash, $creditapplied, $flag2, $isRemoveOnly, $service);
		}
	}

	public function gozoCoinsRemoveData($bkgid, $hash = 0, $web, $flag = 0, $creditRemove = 0)
	{
		$isWalletUsed	 = Yii::app()->request->getParam('isWalletUsed', 0);
		$amtWalletUsed	 = Yii::app()->request->getParam('amtWalletUsed', 0);

		if (isset($bkgid))
		{
			$isAdvDiscount	 = false;
			$model			 = Booking::model()->findbyPk($bkgid);
			if (!$model)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$model->bkgInvoice->bkg_temp_credits = 0;
			$model1								 = clone $model;

			$model1->bkgInvoice->bkgFlexxiMinPay = 1;
			$usepromo							 = ($model1->bkgInvoice->bkg_promo1_id == 0);
			$MaxCredits							 = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model1->bkgInvoice->bkg_base_amount, $usepromo, $model->bkg_from_city_id, $model->bkg_to_city_id);
			$creditVal							 = $MaxCredits['credits'];
			$refundCredits						 = $MaxCredits['refundCredits']; //added for show proper coin
			$result								 = true;
			$minpay								 = $model1->bkgInvoice->calculateMinPayment();
			$dueWithoutConvFee					 = $model1->bkgInvoice->bkg_due_amount;
			$taxWithoutConvFee					 = $model1->bkgInvoice->bkg_service_tax;
			$driver_allowance					 = $model1->bkgInvoice->bkg_driver_allowance_amount;
			$model1->bkgInvoice->bkgFlexxiMinPay = 0;
			//data without cod
			$model2								 = clone $model;
			$model2->bkgInvoice->calculateConvenienceFee(0);
			$model2->bkgInvoice->calculateTotal();
			//data without cod
			$totAmount							 = $model2->bkgInvoice->bkg_total_amount;
			$convFee							 = $model->bkgInvoice->bkg_total_amount - $model2->bkgInvoice->bkg_total_amount;
			$amountWithConvFee					 = round($model->bkgInvoice->bkg_due_amount);
			$discAdvDue							 = $model1->bkgInvoice->bkg_due_amount;

			//Log Data
			if ($model->bkgInvoice->save() && $model->bkgUserInfo->save())
			{
				$result					 = true;
				$isGozoCoinsApplied		 = true;
				$userInfo				 = UserInfo::model();
				$userInfo->userType		 = UserInfo::TYPE_CONSUMER;
				$userInfo->userId		 = $model->bkgUserInfo->bkg_user_id;
				$eventid				 = BookingLog::BOOKING_PROMO;
				$params['blg_ref_id']	 = BookingLog::REF_PROMO_GOZOCOINS_REMOVED;
				BookingLog::model()->createLog($model->bkg_id, "Credit '$creditRemove' removed successfully.", $userInfo, $eventid, false, $params);
			}

			if ($flag == 1)
			{
				$status = ['message'			 => $msg,
					'base_amount'		 => $model->bkgInvoice->bkg_base_amount,
					'promo_type'		 => $prmType,
					'isPromoUsed'		 => $isPromoUsed,
					'promo_id'			 => $promo_id,
					'isCreditUsed'		 => false,
					'totCredits'		 => $creditVal,
					'refundCredits'		 => $refundCredits,
					'creditused'		 => 0,
					'result'			 => $result,
					'due_amount'		 => $dueWithoutConvFee,
					'promo_code'		 => $promoCode,
					'discount'			 => $model1->bkgInvoice->bkg_discount_amount,
					'promo_desc'		 => $promoDescription,
					'service_tax'		 => $taxWithoutConvFee,
					'driver_allowance'	 => $driver_allowance,
					'total_amount'		 => $totAmount,
					'convFee'			 => $convFee,
					'amountWithConvFee'	 => $amountWithConvFee,
					'minPayable'		 => $minpay,
					'isCredit'			 => true,
					'isPromo'			 => $isPromo,
					'discAdv'			 => $discAdvDue,
					'isAdvDiscount'		 => $isAdvDiscount,
					'creditRemove'		 => $result,
					'amtWalletUsed'		 => $amtWalletUsed,
					'isPromoApplied'	 => $isPromoApplied,
					'isGozoCoinsApplied' => false,
					'isWalletApplied'	 => ($amtWalletUsed > 0) ? true : false,
					'isRefundCredits'	 => ($refundCredits > 0) ? true : false
				];
				return $status;
			}
			else if ($flag == 2)
			{
				return $model->bkgInvoice;
			}
		}
	}

	public function getActivePromoCredits()
	{
		$returnSet = new ReturnSet();
		try
		{
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$data	 = Yii::app()->request->rawBody;  // {"bookingId":1196526}
			if (!$data)
			{
				throw new Exception('Invalid Request: ', ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonObj = json_decode($data, false);

			$appModel	 = AppTokens::model()->getByToken($token);
			/* @var $model Booking */
			$model		 = \Booking::model()->findByPk($jsonObj->bookingId);
			$result		 = Promos::getPromoDetails($model);

			if (UserInfo::getUserId() == null || UserInfo::getUserId() == '' || ($appModel->apt_user_id != $model->bkgUserInfo->bkg_user_id))
			{
				goto skipCreditsWallet;
			}

			$credits		 = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model->bkgInvoice->bkg_base_amount, true, $model->bkg_from_city_id, $model->bkg_to_city_id);
			$credits		 = $credits['credits'];
			$walletBalance	 = UserWallet::getBalance($model->bkgUserInfo->bkg_user_id);

			skipCreditsWallet:

			$response	 = new \Stub\common\PromoDetails();
			$response->setData($credits, $result, $walletBalance);
			$response	 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

	public function promoRemoveServiceData($bkgid, $hash = 0, $creditapplied, $flag2 = 0, $isRemoveOnly = false, $service = 0)
	{
		$isWalletUsed	 = Yii::app()->request->getParam('isWalletUsed', 0);
		$amtWalletUsed	 = Yii::app()->request->getParam('amtWalletUsed', 0);

		if (isset($bkgid) && $bkgid > 0)
		{
			$model = Booking::model()->findbyPk($bkgid);
			if (!$model)
			{
				throw new CHttpException(400, 'Invalid data');
			}
		}
		if ($model->bkgTrail->bkg_platform != 3)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if (isset($bkgid))
		{
			Logger::create("PROMOCODE OLD:---: " . $model->bkgInvoice->bkg_promo1_code, CLogger::LEVEL_INFO);
			if ($model->bkgInvoice->bkg_promo1_id > 0)
			{
				$oldPromo	 = $model->bkgInvoice->bkg_promo1_code;
				//data with COD
				//$promoModel	 = Promos::model()->getByCode($model->bkgInvoice->bkg_promo1_code);
				$promoModel	 = Promos::model()->findByPk($model->bkgInvoice->bkg_promo1_id);
				if (!$promoModel)
				{
					throw new Exception('Invalid Promo code');
				}
				$promoModel->promoCode	 = $model->bkgInvoice->bkg_promo1_code;
				$promoModel->totalAmount = $model->bkgInvoice->bkg_base_amount;
				$promoModel->createDate	 = $model->bkg_create_date;
				$promoModel->pickupDate	 = $model->bkg_pickup_date;
				$promoModel->fromCityId	 = $model->bkg_from_city_id;
				$promoModel->toCityId	 = $model->bkg_to_city_id;
				$promoModel->userId		 = $model->bkgUserInfo->bkg_user_id;
				$promoModel->platform	 = $model->bkgTrail->bkg_platform;
				$promoModel->carType	 = $model->bkg_vehicle_type_id;
				$promoModel->bookingType = $model->bkg_booking_type;
				$promoModel->noOfSeat	 = $model->bkgAddInfo->bkg_no_person;
				$promoModel->bkgId		 = $model->bkg_id;
				$promoModel->email		 = '';
				$promoModel->phone		 = '';
				$promoModel->imEfect	 = '';

				$discountArr = $promoModel->applyPromoCode();
				//Logger::create("Discount ARRAY:---: " . CJSON::encode($discountArr), CLogger::LEVEL_INFO);
				if ($discountArr != false)
				{
					if ($discountArr['pcn_type'] == 2)
					{
						$discountArr['cash'] = 0;
					}
					if ($discountArr['prm_activate_on'] == 1)
					{
						$discountArr['cash'] = 0;
					}
					if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
					{
						$discountArr['cash']	 = 0;
						$discountArr['coins']	 = 0;
					}

					$model->bkgInvoice->bkg_promo1_code				 = '';
					$model->bkgInvoice->bkg_promo1_id				 = '0';
					$remainigDiscount								 = $model->bkgInvoice->bkg_discount_amount - $discountArr['cash'];
					//$remainigPromo1amount							 = $model->bkgInvoice->bkg_promo1_amt - $prmdiscount;
					$discount										 = ($remainigDiscount > 0) ? $remainigDiscount : 0;
					$model->bkgInvoice->bkg_discount_amount			 = 0;
					$model->bkgInvoice->bkg_promo1_amt				 = '0';
					$model->bkgUserInfo->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
					$model->bkgInvoice->calculateConvenienceFee(0);
					$model->bkgInvoice->calculateTotal();
					$model->bkgInvoice->bkg_total_amount			 = $model->bkgInvoice->bkg_total_amount + $discountArr['cash'];
					if ($model->bkgInvoice->save() && $model->bkgUserInfo->save())
					{
						$result					 = true;
						$promoRemove			 = true;
						$userInfo				 = UserInfo::model();
						$userInfo->userType		 = UserInfo::TYPE_CONSUMER;
						$userInfo->userId		 = $model->bkgUserInfo->bkg_user_id;
						$eventid				 = BookingLog::BOOKING_PROMO;
						$params['blg_ref_id']	 = BookingLog::REF_PROMO_REMOVED;
						BookingLog::model()->createLog($model->bkg_id, "Promo '$oldPromo' removed successfully.", $userInfo, $eventid, false, $params);
					}

					//$model->bkgInvoice->bkg_credits_used = $creditapplied;
					$model->bkgInvoice->calculateTotal();
					if ($isWalletUsed == 1 && UserInfo::getUserId() > 0 && $amtWalletUsed > 0)
					{
						$totWalletBalance						 = UserWallet::getBalance(UserInfo::getUserId());
						$totWalletBalance						 = ($model->bkgInvoice->bkg_due_amount < $totWalletBalance) ? $model->bkgInvoice->bkg_due_amount : $totWalletBalance;
						$amtWalletUsed							 = ($amtWalletUsed > $totWalletBalance) ? $totWalletBalance : $amtWalletUsed;
						$model->bkgInvoice->bkg_advance_amount	 += $amtWalletUsed;
						$model->bkgInvoice->calculateTotal();
					}
					//data with COD
					//data without COD
					$model1				 = clone $model;
					$model1->bkgInvoice->calculateConvenienceFee(0);
					$model1->bkgInvoice->calculateTotal();
					$amtWithoutConvFee	 = round($model1->bkgInvoice->bkg_total_amount);
					$dueWithoutConvFee	 = round($model1->bkgInvoice->bkg_due_amount);
					$taxWithoutConvFee	 = round($model1->bkgInvoice->bkg_service_tax);
					//data without COD

					$discount			 = $model1->bkgInvoice->bkg_discount_amount;
					//$promocode			 = $model1->bkgInvoice->bkg_promo1_code;
					$promocode			 = $oldPromo;
					$baseAmt			 = round($model1->bkgInvoice->bkg_base_amount);
					$driver_allowance	 = round($model1->bkgInvoice->bkg_driver_allowance_amount);
					$minpay				 = $model1->bkgInvoice->calculateMinPayment();
					$convFee			 = $model->bkgInvoice->bkg_due_amount - $model1->bkgInvoice->bkg_due_amount; // + Filter::getServiceTax($model->bkg_convenience_charge);
					$creditused			 = round($model1->bkgInvoice->bkg_credits_used);
					$amountWithConvFee	 = round($model->bkgInvoice->bkg_due_amount);

					$percent30ofAmt = round(($amtWithoutConvFee * 0.3));
					if ($creditused >= $percent30ofAmt)
					{
						$convFee			 = 0;
						$amountWithConvFee	 = $dueWithoutConvFee;
					}
					$MaxCredits		 = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model1->bkgInvoice->bkg_base_amount, true, $model->bkg_from_city_id, $model->bkg_to_city_id);
					$creditVal		 = $MaxCredits['credits'];
					$isCredit		 = ($creditVal > 0 && $creditapplied == 0 && !Yii::app()->user->isGuest);
					$isCreditUsed	 = ($creditused > 0);
					$discAdvDue		 = $model1->bkgInvoice->bkg_due_amount;
					$msg			 = "Promo Remove sucessfully.";
				}
				else
				{
					$result	 = false;
					$msg	 = "Promo Remove failed.";
				}
			}
			else
			{
				$result	 = false;
				$msg	 = "Promo is already Remove.";
			}

			if ($flag2 == '1')
			{

				$status = [
					'result'			 => $result,
					'message'			 => $msg,
					'isPromoUsed'		 => false,
					'isCreditUsed'		 => $isCreditUsed,
					'promo_type'		 => 0,
					'totCredits'		 => $creditVal,
					'creditused'		 => $creditused,
					'result'			 => $result,
					'due_amount'		 => $dueWithoutConvFee,
					'promo_code'		 => $promocode,
					'discount'			 => 0,
					'service_tax'		 => $taxWithoutConvFee,
					'driver_allowance'	 => $driver_allowance,
					'total_amount'		 => $amtWithoutConvFee,
					'base_amount'		 => $baseAmt,
					'convFee'			 => $convFee,
					'amountWithConvFee'	 => $amountWithConvFee,
					'minPayable'		 => $minpay,
					'promoRemove'		 => $promoRemove,
					'isCredit'			 => $isCredit,
					'discAdv'			 => $discAdvDue,
					'isAdvDiscount'		 => false,
					'isPromo'			 => true,
					'success'			 => ($result) ? 'true' : 'false',
					'amtWalletUsed'		 => $amtWalletUsed,
					'isWalletApplied'	 => ($amtWalletUsed > 0) ? true : false,
				];
				if ($service == 1)
				{
					return $status;
				}
				if ($isRemoveOnly)
				{
					//echo CJSON::encode($status);
					return $status;
				}
			}
			else if ($flag2 == 2)
			{
				return $model1->bkgInvoice;
			}
		}
	}

	public function applyPromo()
	{
		$returnSet = new ReturnSet();
		try
		{
			/* @var $obj Stub\common\Promotions */
			$obj = Yii::app()->request->getJSONObject(new Stub\common\Promotions());
			Logger::create("Request : " . CJSON::encode($obj), CLogger::LEVEL_INFO);

			$bookingId	 = $obj->bookingId;
			$gozoCoins	 = $obj->gozoCoins;
			$promoCode	 = $obj->promo->code;
			$eventType	 = $obj->eventType;
			$bkgModel	 = Booking::model()->findByPk($bookingId);
			BookingInvoice::evaluatePromoCoins($bkgModel, $eventType, $gozoCoins, $promoCode, true);
			$message	 = $obj->getMessage($bkgModel->bkgInvoice, $eventType);
			$response	 = new Stub\common\Promotions();
			$response->setData($bkgModel->bkgInvoice, $eventType);

			$returnSet->setStatus(true);
			$returnSet->setData($response);
			$returnSet->setMessage($message);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function applyWallet()
	{
		$return_Set	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$obj				 = Yii::app()->request->getJSONObject(new Beans\common\WalletPayment());
			Logger::create("Request : " . CJSON::encode($obj), CLogger::LEVEL_INFO);
			AppTokens::validateToken($token);
			$bkgId				 = $obj->refId;
			$paymentType		 = $obj->paymentType;
			$walletBal			 = $obj->wallet;
			$amount				 = $obj->amount;
			$selectPercentage	 = $obj->appliedAdvanceSlab->percentage;
			$selectValue		 = $obj->appliedAdvanceSlab->value;
			$isSelected			 = $obj->appliedAdvanceSlab->isSelected;

			
			/* var @model Booking */
			$model			 = Booking::model()->findByPk($bkgId);
			$amountToApply	 = ($selectValue>0) ? $selectValue : $amount;
			$returnSet = $model->bkgInvoice->processUserWallet($walletBal, $amountToApply);

			if (!$returnSet->getStatus())
			{
				throw new Exception(json_encode($returnSet->getErrors()), ReturnSet::ERROR_INVALID_DATA);
			}

			$walletUsed	 = $returnSet->getData()['walletUsed'];
			$amountToPay = $returnSet->getData()['amountToPay'];
			$return_Set->setStatus(true);
			if ($walletUsed > 0 && $amountToPay == 0)
			{
				$message	 = $walletUsed . " Wallet ballance used";
				$responseObj = new \Beans\common\WalletPayment();
				$response	 = $responseObj->setModelData($model->bkgInvoice, $isSelected, $selectPercentage, $walletUsed);
				goto end;
			}
			if ($amountToPay > 0)
			{
				$referenceId = $model->bkg_id;
				$method		 = 21;
				$responseObj = new \Beans\common\WalletPayment();
				$response	 = $responseObj->setData($referenceId, $paymentType, $method, $amountToPay, $model->bkgUserInfo, $model->bkgInvoice);
				$message	 = "Payment process successfully initiated.";
				goto end;
			}
		}
		catch (Exception $ex)
		{
			$return_Set = ReturnSet::setException($ex);
			Logger::error($ex);
		}
		end:
		$return_Set->setData($response);
		$return_Set->setMessage($message);
		return $return_Set;
	}

}
