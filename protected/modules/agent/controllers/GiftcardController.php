<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class GiftcardController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'main';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
//            array(
//                'application.filters.HttpsFilter + create',
//                'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			//    'postOnly + delete', // we only allow deletion via POST request
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('add', 'promoCodeVerify'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index',
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('promoCodeVerify'),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionAdd()
	{
		$this->pageTitle = "Gift Card";
		$model			 = new GiftCardSubscriber('insert');
		$transcode		 = Yii::app()->request->getParam('tinfo', '');
		$gftSubscriberId = Yii::app()->request->getParam('gftId', '');
		try
		{
			if (isset($_POST['GiftCardSubscriber']))
			{
				$prmId					 = Yii::app()->request->getParam('gftPromoId');
				$arr					 = Yii::app()->request->getParam('GiftCardSubscriber');
				$model->gcs_quantity	 = $arr['gcs_quantity'];
				$promoModel				 = Promos::model()->findByPk($prmId);
				$model->gcs_user_id		 = Yii::app()->user->getAgentId();
				$model->gcs_card_code	 = strtoupper(bin2hex(openssl_random_pseudo_bytes(8)));
				$model->gcs_user_type	 = '1';
				$model->gcs_value_amount = $arr['gcs_value_amount'];
                $prmAmt = 0;
				if ($promoModel)
				{
					$prmDetails	 = Promos::model()->getPromoDetailsByCode($promoModel->prm_code, Yii::app()->user->getAgentId(), $arr['gcs_value_amount'] * $arr['gcs_quantity'], 4);
					$maxUse		 = $prmDetails['prm_use_max'];
					$usedCounter = $prmDetails['prm_used_counter'];
					if ($maxUse >= ($usedCounter + 1) || $maxUse == 0)
					{
						if ($prmDetails)
						{
							$prmAmt = $prmDetails['amount'];
						}
					}
				}
                $model->gcs_cost_price = ($arr['gcs_value_amount'] * $arr['gcs_quantity']) - $prmAmt;
				$model->gcs_promo_id = $prmId;
				$model->attributes	 = $arr;
				$paymentOpt			 = Yii::app()->request->getParam('paymentOpt');
				$model->gcs_active	 = '0';
				$returnSet			 = $model->saveData($paymentOpt);
				if (Yii::app()->request->isAjaxRequest)
				{
					echo CJSON::encode(array('success' => $returnSet->getStatus(), 'errors' => $returnSet->getErrors()));
					Yii::app()->end();
				}
				if ($returnSet->getStatus())
				{
					Yii::app()->controller->refresh();
				}
			}
			$message = $this->allGiftCardEntry($transcode, $gftSubscriberId);
		}
		catch (Exception $ex)
		{
			$message = $ex->getMessage();
			$model->addError('gft_name', $message);
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->render('add', array('models' => $model, 'message' => $message), false, $outputJs);
	}

	public function allGiftCardEntry($transcode, $gftSubscriberId)
	{
		$returnSet	 = new ReturnSet();
		$gftModel	 = GiftCardSubscriber::model()->findByPk($gftSubscriberId);
		if ($transcode != '' && $gftModel->gcs_active == 0 && $gftModel->gcs_id == $gftSubscriberId)
		{
			$agentTransModel = PaymentGateway::model()->getByCode($transcode);
			if ($agentTransModel->apg_status == 1)
			{
				$gftCodeArr				 = [];
				$emailObj				 = new emailWrapper();
				$gftQuantity			 = $gftModel->gcs_quantity;
				$gftCode				 = $gftModel->gcs_card_code;
				$gftCodeArr[0]			 = $gftCode;
				$gftModel->gcs_card_code = md5($gftCode);
				$gftModel->gcs_active	 = 1;
				$gftModel->save();
				if ($gftQuantity > 1)
				{
					$qty = ($gftQuantity - 1);
					for ($k = 0; $k < $qty; $k++)
					{
						$numBytes				 = 8;
						$gftCardCode			 = strtoupper(bin2hex(openssl_random_pseudo_bytes($numBytes)));
						$model					 = new GiftCardSubscriber();
						$model->attributes		 = $gftModel->attributes;
						$gftCodeArr[$k + 1]		 = $gftCardCode;
						$model->gcs_promo_id	 = $gftModel->gcs_promo_id;
						$model->gcs_card_code	 = md5($gftCardCode);
						$returnSet				 = $model->saveData();
					}
				}
				if ($returnSet->getStatus() && $gftModel->gcs_promo_id != '')
				{
					$promoModel						 = Promos::model()->findByPk($gftModel->gcs_promo_id);
					$promoModel->prm_used_counter	 = ($promoModel->prm_used_counter + $gftQuantity);
					$promoModel->save();
				}
				$emailObj->sendGiftCard($gftSubscriberId, $gftCodeArr, Yii::app()->user->getAgentId());
				if (Yii::app()->request->isAjaxRequest)
				{
					echo CJSON::encode(array('success' => $returnSet->getStatus(), 'errors' => $returnSet->getErrors()));
				}
				$message = "<span style='color: #32CD32'>Recharge Successful! <br>(Transaction ID: " . $agentTransModel->apg_code . ", Amount: " . abs($agentTransModel->apg_amount) . ")</span>";
			}
			else
			{
				$message = "<span class='text-danger'>Recharge Failed! <br>(Transaction ID: " . $agentTransModel->apg_code . ", Amount: " . abs($agentTransModel->apg_amount) . ")</span>";
			}
		}
		return $message;
	}

	public function actionpromoCodeVerify()
	{
		$prmCode	 = Yii::app()->request->getParam('prmcode');
		$agtId		 = Yii::app()->request->getParam('agtid');
		$totPrice	 = Yii::app()->request->getParam('totprice');
		$quantity	 = Yii::app()->request->getParam('qty');

		$prmDetails	 = Promos::model()->getPromoDetailsByCode($prmCode, $agtId, $totPrice, 4);
		$maxUse		 = $prmDetails['prm_use_max'];
		$usedCounter = $prmDetails['prm_used_counter'];
		if ($maxUse >= ($usedCounter + 1) || $maxUse == 0)
		{
			if ($prmDetails)
			{
				$prmAmt	 = $prmDetails['amount'];
				$data	 = ['success' => true, 'prmId' => $prmDetails['prm_id'], 'prmamt' => $prmAmt, 'prmdesc' => $prmDetails['prm_desc'], 'costPrice' => $totPrice];
			}
			else
			{
				$data = ['success' => false];
			}
		}
		else
		{
			$data = ['success' => false];
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			echo CJSON::encode($data);
			Yii::app()->end();
		}
	}

}
