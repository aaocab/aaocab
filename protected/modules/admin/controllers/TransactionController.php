<?php

class TransactionController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;
	public $bank_trans_type;
	public $cash_received_by;
	public $bank_chq_no;
	public $bank_name;
	public $bank_branch;
	public $bank_chq_dated;
	public $bank_trans_id;

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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('list', 'lists', 'delterms', 'refund', 'create', 'refundtran', 'paymenttran', 'viewPenalty', 'deletePenalty', 'modifyPenalty', 'refundWalletToCustomer', 'redeemPenalty', 'adjustPenalty', 'addBalance', 'payList'),
				'users'		 => array('@'),
			),
			['allow', 'actions' => ['addCompensation'], 'roles' => ['addCompensation']],
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index',),
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

	public function actionList()
	{
		$this->pageTitle	 = "Transaction List";
		$qry				 = [];
		$date1				 = '';
		$date2				 = '';
		$bookingId			 = '';
		$userName			 = '';
		$model				 = new PaymentGateway('search');
		$isPaymentSuccess	 = Yii::app()->request->getParam('isPaymentSuccess');
		if (isset($_REQUEST['PaymentGateway']))
		{
			$arr = Yii::app()->request->getParam('PaymentGateway');
			$qry = [];
			foreach ($arr as $k => $v)
			{

				$model->$k = $v;
			}
		}
		$model->resetScope();
		$params1								 = array_filter($_GET + $_POST);
		$dataProvider							 = $model->searchTransactions();
		$dataProvider->getPagination()->params	 = array_filter($params1);
		$dataProvider->getSort()->params		 = $params1;
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model, 'isPaymentSuccess' => $isPaymentSuccess));
	}

	public function actionRefund()
	{
		$apgid		 = Yii::app()->request->getParam('apgid');
		$iswallet	 = Yii::app()->request->getParam('iswallet');

		$paymentGateway = PaymentGateway::model()->findByPk($apgid);

		if (isset($_POST['PaymentGateway']))
		{
			$arr					 = Yii::app()->request->getParam('PaymentGateway');
			$amount					 = $arr['apg_amount'];
			$params['blg_ref_id']	 = $arr['apg_id'];
			try
			{
				if ($iswallet != 1)
				{
					$paymentGateway	 = PaymentGateway::model()->findByPk($apgid);
					$success		 = $paymentGateway->refund($amount, UserInfo::getInstance());
					$successArr		 = [];
					$url			 = Yii::app()->createUrl('admin/transaction/list', $successArr);
					$return['url']	 = ' ';
					if ($success === 0)
					{
						$return['message']	 = "Refund Failed";
						$return['success']	 = false;
					}
					else if ($success === 2)
					{
						$return['message']	 = "Already Refunded";
						$return['success']	 = false;
					}
					else
					{
						$return['message']	 = "Refund initiated";
						$return['success']	 = true;
						$return['url']		 = $url;
					}
				}
				else
				{
					$actTransDetailsModel	 = AccountTransDetails::model()->findByPk($apgid);
					$refundTrans			 = $actTransDetailsModel->walletRefund($amount);
					$url					 = Yii::app()->createUrl('admin/transaction/list', $successArr);
					$return['success']		 = true;
					$return['message']		 = "Refund success";
					$return['url']			 = $url;
				}
			}
			catch (Exception $e)
			{
				$return['message']	 = $e->getMessage();
				$return['success']	 = false;
				$return['url']		 = "";

				$transaction->rollback();
			}
			echo CJSON::encode($return);
			Yii::app()->end();
		}
		if ($iswallet != 1)
		{
			$allowedRefund = PaymentGateway::model()->getAllowedRefundAmt($apgid);
		}
		else
		{
			$allowedRefund				 = AccountTransDetails::model()->getAllowedRefundAmt($apgid);
			$paymentGateway				 = new PaymentGateway();
			$paymentGateway->apg_amount	 = $maxrefund;
		}
		$maxrefund = max([$allowedRefund, 0]);
		$this->renderPartial('refund', array('model' => $paymentGateway, 'maxrefund' => round($maxrefund), 'ptp_type' => $paymentGateway->apg_ptp_id), false, true);
	}

	public function actionRefundtran()
	{
		$bkgid	 = Yii::app()->request->getParam('bkg_id');
		$model	 = new AccountTransDetails('refund');
		if ($bkgid > 0)
		{
			$bkgmodel				 = Booking::model()->findByPk($bkgid);
			$model->adt_trans_ref_id = $bkgmodel->bkg_id;
			$model->bkg_booking_id	 = $bkgmodel->bkg_booking_id;
		}
		if (isset($_REQUEST['AccountTransDetails']))
		{
			$model->attributes = Yii::app()->request->getParam('AccountTransDetails');

			$result	 = CActiveForm::validate($model);
			$return	 = ['success' => false, 'message' => 'Error occurred'];
			if ($result == '[]')
			{
				$refundTrans	 = AccountTransDetails::model()->refundTransaction($model, $bkgmodel);
				//if($bkgmodel->bkg_agent_id > 0 && $bkgmodel->bkg_agent_ref_code !='')
				//{
				$getCancelCharge = AccountTransactions::getCancellationCharge($bkgid);
				if ($getCancelCharge)
				{
					$cancelCharge = (round($getCancelCharge - $model->adt_amount) > 0) ? round($getCancelCharge - $model->adt_amount) : 0;
					AccountTransactions::AddCancellationCharge($bkgid, $bkgmodel->bkg_pickup_date, $cancelCharge);
					if ($cancelCharge == 0)
					{
						$remarks = "Cancelled charged Rs.$getCancelCharge reverted.";
						BookingLog::model()->createLog($bkgmodel->bkg_id, $remarks, $userInfo, BookingLog::BOOKING_CANCELLED);
					}
				}
				//}
				$return['success']	 = true;
				$return['message']	 = "Refund success";
			}
			else
			{
				$return['success']	 = false;
				$return['error']	 = CJSON::decode($result);
			}
			echo CJSON::encode($return);
			Yii::app()->end();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('refundtrans', array('model' => $model, 'bkgmodel' => $bkgmodel), false, $outputJs);
	}

	public function actionPaymenttran()
	{
		$log			 = [];
		$bkgid			 = Yii::app()->request->getParam('bkg_id');
		$agentBooking	 = false;
		$model			 = new PaymentGateway('trans_payment');
		$creditVal		 = 0;
		$walletBalance	 = 0;
		if ($bkgid > 0)
		{
			$bkgmodel = Booking::model()->findByPk($bkgid);

			if ($bkgmodel->bkg_agent_id > 0)
			{
				$agentBooking = true;
			}
			$userId					 = $bkgmodel->bkgUserInfo->bkg_user_id;
			$walletBalance			 = UserWallet::model()->getBalance($userId);
			$model->apg_booking_id	 = $bkgmodel->bkg_id;
			$model->apg_trans_ref_id = $bkgmodel->bkg_id;
			$model->booking_id		 = $bkgmodel->bkg_booking_id;
			$bkgmodel->bkgInvoice->calculateTotal();
			Logger::trace(json_encode($bkgmodel->bkgInvoice->getAttributes()));
			if ($bkgmodel->bkgUserInfo->bkg_user_id > 0 && $bkgmodel->bkgInvoice->bkg_due_amount > 0 && in_array($bkgmodel->bkg_status, [2, 3, 5]))
			{
				$usepromo = ($bkgmodel->bkgInvoice->bkg_promo1_id == 0);

				$cloneBookModel = clone $bkgmodel;
				$cloneBookModel->bkgInvoice->calculateTotal();

				$MaxCredits	 = UserCredits::getApplicableCredits($cloneBookModel->bkgUserInfo->bkg_user_id, $cloneBookModel->bkgInvoice->bkg_base_amount, $usepromo, $bkgmodel->bkg_from_city_id, $bkgmodel->bkg_to_city_id);
				Logger::trace(json_encode($MaxCredits));
				$cloneBookModel->bkgInvoice->calculateTotal();
				Logger::trace(json_encode($cloneBookModel->bkgInvoice->getAttributes()));
				$maxCredits	 = max($MaxCredits['credits'] - $cloneBookModel->bkgInvoice->bkg_credits_used, $MaxCredits['refundCredits'], 0);
				$creditVal	 = round(min([$maxCredits, $cloneBookModel->bkgInvoice->bkg_due_amount]));
			}
		}
		if (isset($_REQUEST['PaymentGateway']))
		{
			$model->attributes	 = Yii::app()->request->getParam('PaymentGateway');
			$model->apg_mode	 = 2;
			$valArr				 = [
				// 'TRANSACTION_TYPE'	 => $model->bank_trans_type,
				//  'CASH_RECEIVED_BY'	 => $model->cash_received_by,
				//   'CHEQUE_NUMBER'		 => $model->bank_chq_no,
				//   'BANK_NAME'		 => $model->bank_name,
				//   'BANK_IFSC_CODE'	 => $model->bank_ifsc,
				//   'BANK_BRANCH_NAME'	 => $model->bank_branch,
				//   'CHEQUE_DATE'		 => ($model->bank_chq_dated == '') ? '' : DateTimeFormat::DatePickerToDate($model->bank_chq_dated),
				'TRANSACTION_MODE'	 => $model->apg_mode,
				//    'TXN_ID'		 => $model->bank_trans_id . $model->apg_txn_id,
				'REF_ID'			 => $model->apg_code,
				'DESCRIPTION'		 => $model->apg_remarks];

			$model->apg_response_details = json_encode($valArr, true);
			$model->apg_date			 = new CDbExpression('NOW()');
			$result						 = CActiveForm::validate($model);
			$return						 = ['success' => false];
			if ($result == '[]')
			{
				$transaction = DBUtil::beginTransaction();
				try
				{


					$ptpId			 = PaymentType::model()->ptpList($model->apg_ledger_id);
					$bkgId			 = $model->apg_trans_ref_id;
					$bankLedgerId	 = $model->apg_ledger_id;
					$accType		 = Accounting::AT_BOOKING;
					if (in_array($model->apg_ledger_id, Accounting::getOnlineLedgers()))
					{
						$pgModel				 = PaymentGateway::model()->addAmountForOnlineLedger($model, $bkgId, $ptpId, $bankLedgerId, $accType, UserInfo::getInstance());
						$params['blg_ref_id']	 = $pgModel->apg_id;
						BookingLog::model()->createLog($bkgId, "Online payment initiated ({$pgModel->getPaymentType()} - {$pgModel->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_INITIATED, '', $params);
						$bkgmodel				 = Booking::model()->findByPk($bkgId);
						$isUpdateAdvance		 = $bkgmodel->updateAdvance($pgModel->apg_amount, $pgModel->apg_date, $pgModel->apg_ptp_id, UserInfo::model($pgModel->apg_user_type, $pgModel->apg_user_id), $pgModel, $pgModel->apg_response_message);
						if ($isUpdateAdvance)
						{
							$resultSet = Booking::model()->confirm(true);
						}
						$return['success']	 = true;
						$return['message']	 = "Transaction Added";
					}
					else
					{
						$bankRefId	 = '';
						$accType	 = NULL;

						if ($model->apg_ledger_id == Accounting::LI_GOZOCOINS && $model->apg_amount <= $creditVal && $creditVal > 0)
						{
							$coinsApplied		 = $bkgmodel->bkgInvoice->redeemGozoCoins($model->apg_amount);
							$model->apg_amount	 = $coinsApplied;
							$return['success']	 = true;
							$return['message']	 = "Transaction Added";
						}
						else if ($model->apg_ledger_id == Accounting::LI_GOZOCOINS)
						{
							$return['success']	 = false;
							$return['msg']		 = "Can not apply more than $creditVal credits";
						}

						if ($model->apg_ledger_id == Accounting::LI_PARTNERWALLET)
						{
							$acctransaction = AccountTransactions::checkPartnerWalletForIssue($bkgId, $bkgmodel->bkg_agent_id, $model->apg_amount);
							if ($acctransaction)
							{
								$bankRefId		 = ($bkgmodel->bkg_agent_id == '') ? 1249 : $bkgmodel->bkg_agent_id;
								$accType		 = Accounting::AT_PARTNER;
								$bkgmodel		 = Booking::model()->findByPk($bkgId);
								$isUpdateAdvance = $bkgmodel->updateAdvance($model->apg_amount, $model->apg_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), ' ', $model->apg_remarks, '', 1);
								if ($isUpdateAdvance)
								{
									$resultSet			 = Booking::model()->confirm(true);
									$return['success']	 = true;
									$return['message']	 = "Transaction Added";
								}
								else
								{
									$return['success']	 = false;
									$return['msg']		 = "Can not apply more credits";
								}
							}
							else
							{
								$return['success']	 = false;
								$return['msg']		 = "Can not apply more credits";
							}
						}
						if ($model->apg_ledger_id == Accounting::LI_CASH)
						{
							$bkgmodel		 = Booking::model()->findByPk($bkgId);
							$isUpdateAdvance = $bkgmodel->updateAdvance($model->apg_amount, $model->apg_date, PaymentType::TYPE_CASH, UserInfo::getInstance(), ' ', $model->apg_remarks);
							if ($isUpdateAdvance)
							{
								$resultSet			 = Booking::model()->confirm(true);
								$return['success']	 = true;
								$return['message']	 = "Transaction Added";
							}
						}
						if ($model->apg_ledger_id == Accounting::LI_WALLET)
						{
							$bkgmodel			 = Booking::model()->findByPk($bkgId);
							$amount				 = $model->apg_amount;
							$return['success']	 = true;
							if ($amount > $bkgmodel->bkgInvoice->bkg_due_amount)
							{
								$return['success']	 = false;
								$return['msg']		 = "Amount exceeding due amount";
							}
							elseif ($amount > $walletBalance)
							{
								$return['success']	 = false;
								$return['msg']		 = "Amount exceeding wallet balance";
							}
							else
							{
								$date	 = '';
								$userId	 = $bkgmodel->bkgUserInfo->bkg_user_id;

								$ptpId											 = PaymentType::TYPE_WALLET;
								$bkgmodel->bkgInvoice->bkg_is_wallet_selected	 = 1;
								$bkgmodel->bkgInvoice->bkg_wallet_used			 = $model->apg_amount;
								$amount											 = 0;
								Logger::create("added to wallet");

								$userInfo		 = UserInfo::getInstance();
								//$userInfo		 = UserInfo::model($model->apg_user_type, $model->apg_user_id);
								//$isUpdateAdvance = UserWallet::model()->useWallet($userId, $bkgmodel->bkg_id, true, true, $bkgmodel->bkgInvoice->bkg_wallet_used);
								$isUpdateAdvance = $bkgmodel->updateAdvance(0, $model->apg_date, $ptpId, $userInfo, $model, $model->apg_response_message);

								$return['msg'] = "Transaction Added";
							}
						}
					}

					DBUtil::commitTransaction($transaction);
				}
				catch (Exception $e)
				{
					$model->addError('bkg_id', $e->getMessage());
					$return['error'] = $model->getErrors();
					DBUtil::rollbackTransaction($transaction);
				}
			}
			else
			{
				$return['success']	 = false;
				$return['error']	 = CJSON::decode($result);
			}
			echo CJSON::encode($return);
			Yii::app()->end();
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('paymenttrans', array('model' => $model, 'creditVal' => $creditVal, 'walletBalance' => $walletBalance, 'agentBooking' => $agentBooking), false, $outputJs);
	}

	public function actionViewPenalty()
	{
		$bkg_id		 = Yii::app()->request->getParam('bkg_id');
		$bcbId		 = BookingCab::getTripIdByBkgId($bkg_id);
		$penaltylist = AccountTransactions::getAppliedPenaltyList($bkg_id, $bcbId);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('viewPenalty', array('penaltylist' => $penaltylist, 'bkg_id' => $bkg_id), false, $outputJs);
	}

	public function actionDeletePenalty()
	{
		$success			 = false;
		$userInfo			 = UserInfo::getInstance();
		$actid				 = Yii::app()->request->getParam('act_id');
		$adt_trans_ref_id	 = Yii::app()->request->getParam('adt_trans_ref_id');
		$penaltyType		 = Yii::app()->request->getParam('adt_addt_params');
		$old_amount			 = Yii::app()->request->getParam('act_amount');
		$adt_type			 = Yii::app()->request->getParam('adt_type');
		$bkg_id				 = Yii::app()->request->getParam('bkg_id');
		$vndid				 = Yii::app()->request->getParam('vnd_id');
		$bookingModel		 = Booking::model()->findByPk($bkg_id);
		$isRestricted		 = BookingInvoice::validateDateRestriction($bookingModel->bkg_pickup_date);
		if (!$isRestricted)
		{
			echo "Sorry, you cannot perform this action now.";
			Yii::app()->end();
		}
		$model		 = new AccountTransDetails('penalty');
		$transaction = null;

		if (isset($_REQUEST['AccountTransDetails']))
		{
			try
			{
				$transaction = DBUtil::beginTransaction();
				$row		 = AccountTransactions::getRemainingPenaltybyTransid($actid, $vndid);

				$model->attributes	 = Yii::app()->request->getParam('AccountTransDetails');
				$penaltyType		 = $row['penaltyType'];
				$pModel				 = PenaltyRules::getValueByPenaltyType($penaltyType);
				$eventid			 = BookingLog::VENDOR_PANALIZED;
				$userInfo->userType	 = UserInfo::TYPE_ADMIN;
				$remarks			 = $model->adt_remarks;

				$actual								 = $old_amount;
				$totalWaivedOff						 = $old_amount;
				$balanceRemarks						 = " (Actual: ₹{$actual}, Waived off: ₹{$totalWaivedOff})";
				$remarks							 = "$remarks. Penalty waived off." . $balanceRemarks;
				$penaltyTypeArr["actual"]			 = $actual;
				$penaltyTypeArr["totalWaivedOff"]	 = $totalWaivedOff;
				$penaltyTypeArr['relatedActID']		 = $actid;

				if ($adt_type == 5)
				{
					$addpenalty = AccountTransactions::model()->addVendorPenaltyByTrip($adt_trans_ref_id, $vndid, (-1 * $old_amount), $model->adt_remarks, $penaltyType, $penaltyTypeArr);
				}
				else
				{

					$addpenalty = AccountTransactions::model()->addVendorPenalty($adt_trans_ref_id, $vndid, (-1 * $old_amount), $remarks, '', $penaltyType, null, $penaltyTypeArr);
				}
				if (!$addpenalty)
				{
					throw new Exception("Failed to process remove penalty booking/trip id: {$adt_trans_ref_id} adt type: {$adt_type}");
				}
//		$desc						 = "Penalty removed by admin|" . $pModel['plt_desc'] . ' ₹' . $old_amount . '| Notes:' . $model->adt_remarks;
//		$arr						 = array('act_id' => $actid, 'adt_trans_ref_id' => $adt_trans_ref_id, 'penaltyType' => $penaltyType);
//		$params['additionlalParams'] = $arr;
//		BookingLog::model()->createLog($adt_trans_ref_id, $desc, $userInfo, $eventid, '', $params);
				$success = true;
				$msg	 = "Penalty removed successfully.";
				$data	 = ['success' => $success, 'msg' => $msg];
				AccountTransDetails::modifyAdditionalParam($actid, $old_amount);
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
				DBUtil::rollbackTransaction($transaction);
				$success = false;
				$error	 = trim($ex->getMessage(), '"');
				$data	 = ['success' => $success, 'error' => $error];
			}
			echo json_encode($data);
			Yii::app()->end();
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('deletePenalty', array('model' => $model), false, $outputJs);
	}

	public function actionModifyPenalty()
	{
		$userInfo			 = UserInfo::getInstance();
		$act_id				 = Yii::app()->request->getParam('act_id');
		$adt_trans_ref_id	 = Yii::app()->request->getParam('adt_trans_ref_id');
		$penaltyType		 = Yii::app()->request->getParam('adt_addt_params');
		$vndid				 = Yii::app()->request->getParam('vnd_id');
		$adt_type			 = Yii::app()->request->getParam('adt_type');
		$old_amount			 = Yii::app()->request->getParam('act_amount');
		$bkg_id				 = Yii::app()->request->getParam('bkg_id');
		$penaltyModify		 = 1;
		$bookingModel		 = Booking::model()->findByPk($bkg_id);
		$isRestricted		 = BookingInvoice::validateDateRestriction($bookingModel->bkg_pickup_date);
		if (!$isRestricted)
		{
			echo "Sorry, you cannot perform this action now.";
			Yii::app()->end();
		}
		$model		 = new AccountTransDetails('penalty');
		$transaction = null;

		if (yii::app()->request->getPost('AccountTransDetails'))
		{
			try
			{
				$transaction = DBUtil::beginTransaction();
				$row		 = AccountTransactions::getRemainingPenaltybyTransid($act_id, $vndid, true);

				$model->attributes = Yii::app()->request->getParam('AccountTransDetails');
				if ($model->adt_amount < 0)
				{
					throw new Exception(json_encode("Check the amount."), ReturnSet::ERROR_VALIDATION);
				}
				$pModel		 = PenaltyRules::getValueByPenaltyType($penaltyType);
				$bookingID	 = Booking::model()->getCodeById($bkg_id);
				$remarks	 = 'Modified : ' . $pModel['plt_desc'] . ' booking id-' . $bookingID . ', Amount: Rs.' . $old_amount . ' to Rs.' . $model->adt_amount . '. Reason: ' . $model->adt_remarks;
				$result		 = CActiveForm::validate($model);
				$return		 = ['success' => false, 'message' => 'Error occurred'];
				if ($result == '[]')
				{

					$actual			 = $old_amount;
					$totalWaivedOff	 = $old_amount - $model->adt_amount;
					$balanceRemarks	 = " (Actual: ₹{$actual}, Waived off: ₹{$totalWaivedOff})";
					if ($totalWaivedOff <= 0)
					{
						$increasedBY	 = -1 * $totalWaivedOff;
						$balanceRemarks	 = " (Actual: ₹{$actual}, Raised by: ₹{$increasedBY})";
					}
					AccountTransDetails::modifyAdditionalParam($act_id, $totalWaivedOff);
					$removepenalty = AccountTransactions::remove($act_id);
					if (!$removepenalty)
					{
						throw new Exception(json_encode("Failed to modify act_id: {$act_id}"), ReturnSet::ERROR_VALIDATION);
					}
					$remarks							 = $remarks . $balanceRemarks;
					$penaltyTypeArr["actual"]			 = $actual;
					$penaltyTypeArr["totalWaivedOff"]	 = $totalWaivedOff;
					$penaltyTypeArr['relatedActID']		 = $act_id;
					if ($adt_type == 5)
					{
						$eventid			 = BookingLog::VENDOR_PANALIZED;
						$userInfo->userType	 = UserInfo::TYPE_ADMIN;
						$addpenalty			 = AccountTransactions::model()->addVendorPenaltyByTrip($adt_trans_ref_id, $vndid, $model->adt_amount, $remarks, $penaltyType, $penaltyModify, $penaltyTypeArr);
						//BookingLog::model()->createLog($bkg_id, $remarks, $userInfo, $eventid);
					}
					else
					{

						$addpenalty = AccountTransactions::model()->addVendorPenalty($adt_trans_ref_id, $vndid, $model->adt_amount, $remarks, '', $penaltyType, $penaltyModify, $penaltyTypeArr);
					}
					if (!$addpenalty)
					{
						throw new Exception("Failed to process modify penalty booking/trip id: {$adt_trans_ref_id} adt type: {$adt_type}");
					}

					$return['success']	 = true;
					$return['message']	 = "Penalty Modification success";
				}
				else
				{
					$return['success']	 = false;
					$return['error']	 = CJSON::decode($result);
				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
				DBUtil::rollbackTransaction($transaction);
				$success = false;
				$error	 = trim($ex->getMessage(), '"');
				$return	 = ['success' => $success, 'error' => $error];
			}
			echo CJSON::encode($return);
			Yii::app()->end();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('modifyPenalty', array('model' => $model), false, $outputJs);
	}

	public function actionRedeemPenalty()
	{
		try
		{
			$userInfo			 = UserInfo::getInstance();
			$act_id				 = Yii::app()->request->getParam('act_id');
			$adt_trans_ref_id	 = Yii::app()->request->getParam('adt_trans_ref_id');
			$penaltyType		 = Yii::app()->request->getParam('adt_addt_params');
			$vndid				 = Yii::app()->request->getParam('vnd_id');
			$adt_type			 = Yii::app()->request->getParam('adt_type');

			$old_amount		 = Yii::app()->request->getParam('act_amount');
			$bkg_id			 = Yii::app()->request->getParam('bkg_id');
			$penaltyModify	 = 1;
			$bookingModel	 = Booking::model()->findByPk($bkg_id);
			$isRestricted	 = BookingInvoice::validateDateRestriction($bookingModel->bkg_pickup_date);
			$coin			 = VendorCoins::totalCoin($vndid);

			/* if (!$isRestricted)
			  {
			  echo "Sorry, you cannot perform this action now.";
			  Yii::app()->end();
			  } */
			$model = new AccountTransDetails('penalty');
			if (yii::app()->request->getPost('AccountTransDetails'))
			{
				$model->attributes	 = Yii::app()->request->getParam('AccountTransDetails');
				$pModel				 = PenaltyRules::getValueByPenaltyType($penaltyType);

				$bookingID	 = Booking::model()->getCodeById($bkg_id);
				$remarks	 = 'Redeemm with coin: ' . $pModel['plt_desc'] . ' booking id-' . $bookingID . ', Amount: Rs.' . $model->adt_amount . ' ' . $model->adt_remarks;
				$result		 = CActiveForm::validate($model);
				$return		 = ['success' => false, 'message' => 'Error occurred'];
				if ($result == '[]')
				{
					/* $transaction   = DBUtil::beginTransaction();

					  $penaltyRedemAmount  = $model->adt_amount;
					  if($penaltyRedemAmount>$coin*10)
					  {
					  $error = "Redeem amount greater then total coin";
					  throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
					  $return['message'] = $error;
					  }
					  $accTransDetArr		 = [];
					  $accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkg_id, Accounting::LI_PARTNER, (-1 * $penaltyRedemAmount), $remarks);
					  $accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_VENDOR, $vndid, Accounting::LI_PARTNER, $penaltyRedemAmount);
					  AccountTransactions::model()->add($accTransDetArr, $datetime, $penaltyRedemAmount, $bkg_id, Accounting::AT_BOOKING, $remarks, UserInfo::model());
					  // Booking Log
					  $eventId			 = BookingLog::REDEEMED_VENDOR_COIN;
					  $desc				 = $remarks;
					  $logStatus			 = BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId);
					 */
					/*  $removepenalty = AccountTransactions::remove($act_id);
					  if (!$removepenalty)
					  {
					  throw new Exception("Failed to process remove act_id: {$act_id}");
					  } */



					if ($model->adt_amount > $old_amount)
					{
						throw new Exception("Penaly amount is less than redeemed amout");
						echo "Penaly amount is less than redeemed amount.";
						Yii::app()->end();
					}
					if ($model->adt_amount > ($coin * 10))
					{
						throw new Exception("Redeemed amount is greater then coin amount");
					}
					/* if (!$removepenalty)
					  {
					  throw new Exception("Failed to process remove act_id: {$act_id}");
					  } */
					$transaction		 = DBUtil::beginTransaction();
					$removepenalty		 = AccountTransactions::remove($act_id);
					$newPenaltyAmount	 = $old_amount - $model->adt_amount;
					if ($newPenaltyAmount > 0)
					{
						if ($adt_type == 5)
						{
							$eventid			 = BookingLog::VENDOR_PANALIZED;
							$userInfo->userType	 = UserInfo::TYPE_ADMIN;
							$addpenalty			 = AccountTransactions::model()->addVendorPenaltyByTrip($adt_trans_ref_id, $vndid, $newPenaltyAmount, $remarks, $penaltyType, $penaltyModify);
							BookingLog::model()->createLog($bkg_id, $remarks, $userInfo, $eventid);
						}
						else
						{
							$addpenalty = AccountTransactions::model()->addVendorPenalty($adt_trans_ref_id, $vndid, $newPenaltyAmount, $remarks, '', $penaltyType, $penaltyModify);
						}
						if (!$addpenalty)
						{
							throw new Exception("Failed to process modify penalty booking/trip id: {$adt_trans_ref_id} adt type: {$adt_type}");
						}
					}
					//adjust vendor coin with penality
					if ($coin > 0)
					{
						$coinUsed	 = $model->adt_amount / 10;
						$coin		 = ($coinUsed <= 0 ? $coinUsed : -$coinUsed);
						$vncType	 = VendorCoins::PENALTY;
						$vncDesc	 = "Coin redeemed with penality amount";
						$reffType	 = 2;
						$adjust		 = VendorCoins::entry($bkg_id, $coin, $vncType, $vncDesc, $reffType);
					}
					$return['success']	 = true;
					$return['message']	 = "Penalty Redeem successfully";
					DBUtil::commitTransaction($transaction);
				}
				else
				{
					$return['success']	 = false;
					$return['error']	 = CJSON::decode($result);
				}
				echo CJSON::encode($return);
				Yii::app()->end();
			}
			$outputJs	 = Yii::app()->request->isAjaxRequest;
			$method		 = "render" . ($outputJs ? "Partial" : "");
			$this->$method('redeemPenalty', array('model' => $model, 'coin' => $coin, 'oldAmount' => $old_amount), false, $outputJs);
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			DBUtil::rollbackTransaction($transaction);
		}
	}

	public function actionRefundWalletToCustomer()
	{
		$userid	 = Yii::app()->request->getParam('user_id');
		$model	 = new AccountTransDetails('userrefund');

		if (isset($_REQUEST['AccountTransDetails']))
		{
			$model->attributes	 = Yii::app()->request->getParam('AccountTransDetails');
			$getbalance			 = UserWallet::getBalance($userid);
			if ($model->adt_amount > $getbalance || $model->adt_amount <= 0)
			{
				return false;
			}
			$refundTrans = $model::refundUserTransaction($model, $userid);
			if ($refundTrans)
			{
				$return['success']	 = true;
				$return['message']	 = "Refund success";
			}
			else
			{
				$return['success']	 = false;
				$return['error']	 = "Error occurred";
			}
			echo CJSON::encode($return);
			Yii::app()->end();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('refundWalletToCustomer', array('model' => $model), false, $outputJs);
	}

	public function actionAddCompensation()
	{
		$bkgId	 = Yii::app()->request->getParam('bkgId');
		$model	 = new AccountTransDetails('compensation');
		if ($bkgId > 0)
		{
			$bkgmodel				 = Booking::model()->findByPk($bkgId);
			$model->adt_trans_ref_id = $bkgmodel->bkg_id;
			$model->bkg_booking_id	 = $bkgmodel->bkg_booking_id;
		}
		if (isset($_REQUEST['AccountTransDetails']))
		{
			$request				 = Yii::app()->request->getParam('AccountTransDetails');
			$model->adt_remarks		 = $request['adt_remarks'];
			$model->attributes		 = $request;
			$model->ucrMaxuseType	 = $request['ucrMaxuseType'];
			$model->ucrCreditType	 = $request['ucrCreditType'];
			$result					 = CActiveForm::validate($model);
			$return					 = ['success' => false, 'message' => 'Error occurred'];
			if ($result == '[]')
			{
				$resultSet = AccountTransDetails::addCompensation($model, $bkgmodel);
				if ($resultSet->getStatus())
				{
					$return['success']	 = $resultSet->getStatus();
					$return['message']	 = $resultSet->getMessage();
				}
				else
				{
					$return['success']	 = false;
					$return['error']	 = CJSON::encode($resultSet->getErrors());
				}
			}
			else
			{
				$return['success']	 = false;
				$return['error']	 = CJSON::decode($result);
			}
			echo CJSON::encode($return);
			Yii::app()->end();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addcompensation', array('model' => $model, 'bkgmodel' => $bkgmodel), false, $outputJs);
	}

	public function actionAdjustPenalty()
	{
		$success = false;
		$actid	 = Yii::app()->request->getParam('act_id');
		$vndId	 = Yii::app()->request->getParam('vnd_id');
		try
		{
			if ($actid > 0 && $vndId > 0)
			{
				$resultSet = VendorCoins::redeemPenalty($actid, $vndId);
				if ($resultSet->getStatus())
				{
					$success = $resultSet->getStatus();
					$message = $resultSet->getMessage();
				}
				else
				{
					$success = false;
					$message = $resultSet->getErrors()[0];
				}
			}
			else
			{
				$message = "Problem Occured";
			}
		}
		catch (Exception $ex)
		{
			$success = false;
			$message = trim($e->getMessage(), '"');
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'message' => $message];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
	}

	public function actionAddBalance()
	{
		$userid	 = Yii::app()->request->getParam('user_id');
		$model	 = new AccountTransDetails('userrefund');
		$request = Yii::app()->request;
		if ($request->isPostRequest)
		{
			$model->attributes	 = Yii::app()->request->getParam('AccountTransDetails');
			$refundTrans		 = $model::addManualBalance($model, $userid);
			if ($refundTrans)
			{
				$return['success']	 = true;
				$return['message']	 = "Successfully add balance";
			}
			else
			{
				$return['success']	 = false;
				$return['error']	 = "Error occurred";
			}
			echo CJSON::encode($return);
			Yii::app()->end();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addBalance', array('model' => $model), false, $outputJs);
	}

	public function actionPayList()
	{
		$this->pageTitle	 = "Transaction List";
		$qry				 = [];
		$date1				 = '';
		$date2				 = '';
		$bookingId			 = '';
		$userName			 = '';
		$model				 = new PaymentGateway('search');
		$isPaymentSuccess	 = Yii::app()->request->getParam('isPaymentSuccess');
		if (isset($_REQUEST['PaymentGateway']))
		{
			$arr = Yii::app()->request->getParam('PaymentGateway');
			$qry = [];
			foreach ($arr as $k => $v)
			{

				$model->$k = $v;
			}
		}
		$model->resetScope();
		$params1								 = array_filter($_GET + $_POST);
		$dataProvider							 = $model->payList();
		$dataProvider->getPagination()->params	 = array_filter($params1);
		$dataProvider->getSort()->params		 = $params1;

		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{

			$model				 = new PaymentGateway('search');
			$picupDate1			 = Yii::app()->request->getParam('export_trans_date1');
			$picupDate2			 = Yii::app()->request->getParam('export_trans_date2');
			$model->trans_date1	 = $picupDate1;
			$model->trans_date2	 = $picupDate2;

			$model->trans_code		 = Yii::app()->request->getParam('export_trans_code');
			$model->trans_booking	 = Yii::app()->request->getParam('export_trans_booking');
			$model->apg_ledger_id	 = Yii::app()->request->getParam('export_apg_ledger_id');
			$model->trans_stat		 = Yii::app()->request->getParam('export_trans_stat');
			$model->apg_mode		 = Yii::app()->request->getParam('export_apg_mode');
			$model->tranasctionFor	 = Yii::app()->request->getParam('export_tranasctionFor');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"TransactionPayList" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "TransactionPayList" . date('YmdHi') . ".csv";
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
			$command = true;
			$rows	 = $model->payList($command);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, [
				'Booking ID/Vendor',
				'Payment Type',
				'Mode',
				'Trans Code',
				'Payment trans id',
				'Trans Status',
				'Trans Response Message',
				'Trans Amount',
				'Start Date/Time',
				'Complete Date/Time',
				'Response Details'
			]);
			foreach ($rows as $row)
			{


				$rowArray						 = array();
				$rowArray['apg_acc_trans_type']	 = ($row['apg_acc_trans_type'] == 1) ? $row['bkg_booking_id'] : $row['vnd_name'];
				$rowArray['trans_ptp_text']		 = $row['trans_ptp_text'];
				$rowArray['apg_mode']			 = $row['apg_mode'];
				$rowArray['trans_code']			 = $row['trans_code'] . ($row['apg_amount'] < 0) ? $row['refundOrderCode'] : "N/A";
				$rowArray['apg_txn_id']			 = $row['apg_txn_id'];

				$rowArray['trans_status']			 = $row['trans_status'];
				$rowArray['trans_response_message']	 = ($row['apg_ledger_id'] == 1 || $row['apg_ledger_id'] == 33) ? json_decode($row['trans_response_details'], true) : $row['trans_response_message'];
				$rowArray['apg_amount']				 = $row['apg_amount'];
				$rowArray['trans_start_datetime']	 = date("d/m/Y H:i:s", strtotime($row['trans_start_datetime']));
				$rowArray['trans_complete_datetime'] = ($row['trans_complete_datetime'] != "") ? date("d/m/Y H:i:s", strtotime($row['trans_complete_datetime'])) : "";
				$responseDetails					 = json_decode($row['apg_response_details'], true);
				$rowArray['apg_response_details']	 = ($row['apg_response_details'] != '') ? 'Payment Id: ' . $responseDetails['razorpay_payment_id'] . ', Order Id: ' . $responseDetails['razorpay_order_id'] . ', Signature: ' . $responseDetails['razorpay_signature'] : '';
				$row1								 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}




		$this->render('payList', array('dataProvider' => $dataProvider, 'model' => $model, 'isPaymentSuccess' => $isPaymentSuccess));
	}

}
