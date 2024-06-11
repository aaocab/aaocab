<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class RechargeController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'main';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';

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
				'actions'	 => array('add', 'process'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'signin', 'cpagreement',
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array(),
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
			$ri	 = array('');

			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});
	}

	public function actionAdd()
	{
		$this->pageTitle = "Recharge Account";
		$transcode		 = Yii::app()->request->getParam('tinfo', '');
		$userInfo		 = UserInfo::getInstance();
		$partnerId		 = $userInfo->getUser()->getAgentId();
		if ($transcode != '')
		{
			$agentTransModel = PaymentGateway::model()->getByCode($transcode);
			if ($agentTransModel->apg_status == 1)
			{
				$transinfo = "<div class='text-success  '>Recharge Successful! <br>(Transaction ID: " . $agentTransModel->apg_code . ", Amount: " . abs($agentTransModel->apg_amount) . ")</div>";
			}
			else
			{
				$transinfo = "<span class='text-danger'>Recharge Failed! <br>(Transaction ID: " . $agentTransModel->apg_code . ", Amount: " . abs($agentTransModel->apg_amount) . ")</span>";
			}
		}

		$partnerModel	 = Agents::model()->findByPk($partnerId);
		$company		 = $partnerModel->agt_company;
		if ($company == '')
		{
			$company = $partnerModel->agt_owner_name;
		}
		if ($company == '')
		{
			$company = $partnerModel->agt_fname . " " . $partnerModel->agt_lname;
		}
		$infoDesc	 = $company . " (" . $partnerModel->agt_agent_id . ")";
		$paymentData = [
			'mobile'			 => $partnerModel->agt_phone,
			'email'				 => $partnerModel->agt_email,
			'name'				 => $partnerModel->agt_fname . " " . $partnerModel->agt_lname,
			'billing_address'	 => $partnerModel->agt_address
		];
		$getBalance	 = PartnerStats::getBalance($partnerId);
		$this->render("recharge", ['transinfo' => $transinfo, 'paymentData' => $paymentData, 'infoDesc' => $infoDesc, 'getBalance' => $getBalance]);
	}

	public function actionProcess()
	{
		$userInfo	 = UserInfo::getInstance();
		$partnerId	 = $userInfo->getUser()->getAgentId();

//		$paymentType = PaymentType::TYPE_PAYUMONEY;
//		$paymentType = PaymentType::TYPE_EASEBUZZ;
//		$paymentType = PaymentType::TYPE_RAZORPAY;
		$paymentType = Yii::app()->request->getParam('paymentType');

		$accTransType	 = Accounting::AT_PARTNER;
		$amount			 = Yii::app()->request->getParam('amount');

		$transaction	 = DBUtil::beginTransaction();
		$paymentGateway	 = PaymentGateway::model()->add($paymentType, $amount, '', $partnerId, $userInfo, $accTransType);
		if ($paymentGateway)
		{
			$desc			 = "Account recharge for ₹$amount initiated";
			$event			 = AgentLog::PAYMENT_INITIATED;
			AgentLog::add($partnerId, $desc, $event, $userInfo);
			$url			 = $paymentGateway->paymentUrl;
			$payubolt		 = Yii::app()->request->getParam('payubolt');
			$return['url']	 = $url;

			if (Yii::app()->request->isAjaxRequest)
			{
				$apg_id		 = $paymentGateway->apg_id;
				$payRequest	 = PaymentGateway::model()->getPGRequest($apg_id);
				$pgObject	 = Filter::GetPGObject($payRequest->payment_type);

				$return		 = $pgObject->initiateRequest($payRequest);

//				$return['success'] = true;
				echo CJSON::encode($return);
				DBUtil::commitTransaction($transaction);
				Yii::app()->end();
			}
		}
		DBUtil::commitTransaction($transaction);
		$return['success'] = true;

		echo CJSON::encode($return);

		Yii::app()->end();
	}
}
