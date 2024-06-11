<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class PaymentController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
//	public $layout		 = 'column1';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';

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
				'actions'	 => array(' '),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('updateAdvance', 'initiate', 'response', 'initiate1',
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

//        $this->onRest('post.filter.req.auth.user', function($validation) {
//            $pos = false;
//            $arr = $this->getURIAndHTTPVerb();
//            $ri = array('');
//            foreach ($ri as $value)
//            {
//                if (strpos($arr[0], $value))
//                {
//                    $pos = true;
//                }
//            }
//            return $validation ? $validation : ($pos != false);
//        });
	}

	public function requestAction($param)
	{
		
	}

	public function actionUpdateAdvance()
	{
		$sql	 = " SELECT bkg.bkg_id,(bkg.bkg_advance_amount + bkg.bkg_credits_used-bkg.bkg_refund_amount) as adv,
                        SUM(IF(adt.adt_amount<0,IF(adt.adt_ref_id>0,adt.adt_amount,0),adt.adt_amount)) netTrans,
                        SUM(IF(adt_amount > 0 ,IF(adt_type=3 AND adt.adt_trans_ref_id=1249,0,adt_amount), 0)) as advPaid,
                        SUM(IF(adt_amount < 0, IF(adt.adt_ref_id>0,adt.adt_amount,0), 0)) as refunded
                    FROM
                        account_trans_details adt
                     JOIN account_transactions act ON
                        act.act_id = adt.adt_trans_id
                     JOIN account_ledger al ON
                        al.ledgerId = adt.adt_ledger_id
                         INNER JOIN booking bkg ON bkg.bkg_id = act.act_ref_id
                    WHERE
                        al.accountGroupId IN(27, 28) AND act.act_type = 1 AND adt.adt_status = 1 AND adt.adt_active = 1  GROUP BY act.act_ref_id HAVING adv<>netTrans";
		return;
		$rows	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($rows as $row)
		{
			$model = Booking::model()->findByPk($row['bkg_id']);
			$model->updateAdvance1($row['advPaid'], UserInfo::TYPE_SYSTEM);
			$model->updateRefund1(0 - $row['refunded']);
		}
	}

	public function actionInitiate()
	{
		$apgid		 = Yii::app()->request->getParam('apgid', 0);
		$payRequest	 = PaymentGateway::model()->getPGRequest($apgid);
		$pgObject	 = Filter::GetPGObject($payRequest->payment_type);

		$view		 = $pgObject->view;
		$param_list	 = $pgObject->initiateRequest($payRequest);

		$this->render($view, array('param_list' => $param_list));
	}

	 

	public function actionResponse()
	{
		$responseArr = $_REQUEST;
		$ptpId		 = Yii::app()->request->getParam('ptpid', 0);
		if (isset($responseArr['response']))
		{
			$responseArr = $responseArr['response'];
		}
		$result	 = PaymentGateway::model()->updatePGResponse($responseArr, $ptpId);
		Logger::trace("PaymentGateway::updatePGResponse result:" . json_encode($result));
		$app	 = Yii::app()->request->getParam('app', 0);
		$bolt	 = Yii::app()->request->getParam('bolt', 0);
		if ($app == 1)
		{
			return $result;
		}
		if ($bolt == 1 && $result['bkid'] > 0)
		{
			if (Yii::app()->request->isAjaxRequest)
			{
				if (!$result['success'])
				{
					$result['message'] = 'The transaction was cancelled';
				}
				//if ($ptpId == PaymentType::TYPE_RAZORPAY)
				{
					$hash			 = Yii::app()->shortHash->hash($result['bkid']);
					$result['url']	 = Yii::app()->createAbsoluteUrl('booking/paynow/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']);

					if ($result['success'])
					{
						$result['url'] = Yii::app()->createAbsoluteUrl('booking/summary/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']);
					}
				}
				echo CJSON::encode($result);
				Yii::app()->end();
			}
		}

		if ($result['bkid'] > 0)
		{
			$hash = Yii::app()->shortHash->hash($result['bkid']);
			if ($result['success'])
			{
				$this->redirect(array('booking/summary/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
			}
			$this->redirect(array('booking/paynow/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
		}
		if ($result['vorId'] > 0)
		{
			$hash = Yii::app()->shortHash->hash($result['vorId']);
			if ($result['success'])
			{
				$this->redirect(array('voucher/summary/action/done/id/' . $result['vorId'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
			}
			$this->redirect(array('voucher/paynow/action/done/id/' . $result['vorId'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
		}
		if ($result['agtId'] > 0)
		{
			if ($bolt == 1)
			{
				$result['url'] = Yii::app()->createAbsoluteUrl('agent/recharge/add/tinfo/' . $result['tinfo']);
				echo CJSON::encode($result);
				Yii::app()->end();
			}
			$this->redirect(array('agent/recharge/add/tinfo/' . $result['tinfo']));
		}
	}
}
