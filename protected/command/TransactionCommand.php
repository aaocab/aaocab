<?php

class TransactionCommand extends BaseCommand
{

	//private $email_receipient;

	public function actionGetstatus($bkgid = '')
	{
		echo ":: Transaction-Getstatus Started";
		Logger::create("command.transaction.getStatus start", CLogger::LEVEL_PROFILE);
		/* @var $model PaymentGateway */

		$IncompleteRecords = PaymentGateway::model()->getEmptystatus($bkgid); //309024


		foreach ($IncompleteRecords as $model)
		{
			PaymentGateway::model()->updateEmptyPGResponse($model);
		}
		Logger::create("command.transaction.getStatus end", CLogger::LEVEL_PROFILE);
		echo ":: Transaction-Getstatus End";
	}

	public function actionUpdateref()
	{
		$emptyRefRecords = BookingLog::model()->getTransactionLogWithEmptyRef();
		foreach ($emptyRefRecords as $model)
		{
			$desc	 = $model['blg_desc'];
			$refCode = substr($desc, -16, 15);
			if (strstr($refCode, '-'))
			{
				$refCode = substr($refCode, -9);
			}
			$tmodel	 = Transactions::model()->getByCode($refCode);
			$tid	 = $tmodel->trans_id;
			echo $desc . '-----' . $refCode . '-----' . $tid . '-----';
			$res	 = BookingLog::model()->updateRefidbyid($model['blg_id'], $tid);
			echo ($res) ? 'updated' : 'failed to update';
			echo '<br>' . '<br>';
		}
	}

	public function actionUpdatepaymentlog()
	{
		$this->actionUpdateref();

		$ptpList	 = PaymentType::model()->getList();
		$actionList	 = [54, 55, 56];
		foreach ($actionList as $presentAction)
		{
			//$presentAction = ;
			$transPaymentInitiated = Transactions::model()->getUnlinkedTransactionByEventid($presentAction);
			foreach ($transPaymentInitiated as $model)
			{
				$blgModel					 = new BookingLog();
				$blgModel->blg_ref_id		 = $model['trans_id'];
				$blgModel->blg_user_type	 = BookingLog::Consumers;
				$blgModel->blg_user_id		 = $model['trans_user_id'];
				$blgModel->blg_booking_id	 = $model['trans_booking_id'];
				$blgModel->blg_event_id		 = $presentAction;
				$transPaymentType			 = $model['trans_ptp_id'];
				$transcode					 = $model['trans_code'];
				$ptpTypeName				 = $ptpList[$transPaymentType];
				if ($presentAction == 54)
				{
					$blgDesc				 = "Online payment initiated";
					$blgDesc				 .= " ($ptpTypeName - $transcode)";
					$blgModel->blg_created	 = $model['trans_start_datetime'];
				}
				if ($presentAction == 55)
				{
					$blgDesc				 = "Online payment completed";
					$blgDesc				 .= " ($ptpTypeName - $transcode)";
					$blgModel->blg_created	 = ($model['trans_complete_datetime'] == '') ? $model['trans_start_datetime'] : $model['trans_complete_datetime'];
				}
				if ($presentAction == 56)
				{
					$blgDesc				 = "Online payment failed";
					$blgDesc				 .= " ($ptpTypeName - $transcode)";
					$blgModel->blg_created	 = ($model['trans_complete_datetime'] == '') ? $model['trans_start_datetime'] : $model['trans_complete_datetime'];
				}
				$blgModel->blg_desc	 = $blgDesc;
				$res				 = $blgModel->save();

				echo $transcode . ' : ';
				echo ($res) ? "updated :-" . $blgDesc : 'failed to update:-';
				echo "\n\n";
			}
		}
	}

	/////////////////////////////

	public function actionAccountingTransferData()
	{

		//Transactions
		// BANK,CASH,ONLINE,GOZO_COINS,JOURNAL,SETTLE
//	$this->transferTransactions();
		//AgentTransactions
		//CASH,BANK,PAYTM,PAYUMONEY,JOURNAL
//	$this->transferAgentTransactions();
		//CREDITS USED
//		$this->transferPartnerCoinsUsed();
		//VendorTransactions
		//CASH,BANK,PAYTM,PAYUMONEY,JOURNAL,SETTLE
		$this->transferVendorTransactions();
		//TRIP PURCHASED AND VENDOR COLLECTED
		//$this->transferVendorCollected();
	}

	public function transferTransactions()
	{

		$i = 0;
		try
		{
			while (true)
			{
				$sql		 = "SELECT * FROM transactions WHERE trans_ptp_id IN (1,2,3,4,5,6,7,8,9,10,11,12) AND trans_booking_id>0  AND trans_active=1 AND trans_status=1  LIMIT $i, 10000";
				$i			 = $i + 10000;
				$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
				if ($resultset == [])
				{
					break;
				}
				$transaction = Yii::app()->db->beginTransaction();
				foreach ($resultset as $result)
				{
					$bankLedgerId	 = PaymentType::model()->ledgerList($result['trans_ptp_id']);
					$payeeRefType	 = NULL;
					$payeeRefId		 = NULL;

					if (in_array($bankLedgerId, Accounting::getOnlineLedgers(false)))
					{
						$paymentGateway							 = new PaymentGateway();
						$paymentGateway->apg_ptp_id				 = $result['trans_ptp_id'];
						$paymentGateway->apg_booking_id			 = $result['trans_booking_id'];
						$paymentGateway->apg_ledger_id			 = $bankLedgerId;
						$paymentGateway->apg_acc_trans_type		 = Accounting::AT_BOOKING;
						$paymentGateway->apg_trans_ref_id		 = $result['trans_booking_id'];
						$paymentGateway->apg_banktrans_type		 = $result['trans_type'];
						$paymentGateway->apg_code				 = $result['trans_code'];
						$paymentGateway->apg_mode				 = $result['trans_mode'];
						$paymentGateway->apg_remarks			 = $result['trans_remarks'];
						$paymentGateway->apg_ipaddress			 = $result['trans_ipaddress'];
						$paymentGateway->apg_device_detail		 = $result['trans_device_detail'];
						$paymentGateway->apg_user_type			 = $result['trans_user_type'];
						$paymentGateway->apg_user_id			 = $result['trans_user_id'];
						$paymentGateway->apg_amount				 = $result['trans_amount'];
						$paymentGateway->apg_active				 = $result['trans_active'];
						$paymentGateway->apg_status				 = $result['trans_status'];
						$paymentGateway->apg_date				 = $result['trans_start_datetime'];
						$paymentGateway->apg_ref_id				 = $result['trans_ref_id'];
						$paymentGateway->apg_response_details	 = $result['trans_response_details'];
						$paymentGateway->apg_response_code		 = $result['trans_response_code'];
						$paymentGateway->apg_response_message	 = $result['trans_response_message'];
						$paymentGateway->apg_txn_id				 = $result['trans_txn_id'];
						$paymentGateway->apg_merchant_ref_id	 = $result['trans_merchant_ref_id'];
						$paymentGateway->apg_start_datetime		 = $result['trans_start_datetime'];
						$paymentGateway->apg_complete_datetime	 = $result['trans_complete_datetime'];
						if ($paymentGateway->save())
						{
							$payeeRefType	 = Accounting::AT_ONLINEPAYMENT;
							$payeeRefId		 = $paymentGateway->apg_id;
						}
					}
					if ($result['trans_ptp_id'] == PaymentType::TYPE_GOZO_COINS)
					{
						$payeeRefType	 = Accounting::AT_PARTNER;
						$payeeRefId		 = 1249;
					}

					$accTransModel				 = new AccountTransactions();
					$accTransModel->act_amount	 = $result['trans_amount'];
					$accTransModel->act_date	 = $result['trans_start_datetime'];
					$accTransModel->act_type	 = Accounting::AT_BOOKING;
					$accTransModel->act_ref_id	 = $result['trans_booking_id'];
					$accTransModel->act_remarks	 = $result['trans_remarks'];
					$accountTrans				 = $accTransModel->AddReceipt($bankLedgerId, Accounting::LI_BOOKING, $payeeRefId, $result['trans_booking_id'], $result['trans_remarks'], $payeeRefType, $result['trans_user_type'], $result['trans_user_id'], $result['trans_id']);

					echo "(" . $i . ")" . $accountTrans->act_id . "--";
				}

				$transaction->commit();
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			$transaction->rollback();
		}
	}

	public function transferAgentTransactions()
	{
		$i = 0;
		try
		{
			while (true)
			{

				$sql		 = "SELECT * FROM agent_transactions WHERE agt_ptp_id IN(1,2,3,6,7) AND agt_trans_active = 1 AND agt_trans_status = 1 LIMIT $i, 10000";
				$i			 = $i + 10000;
				$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
				if ($resultset == [])
				{
					break;
				}
				$transaction = Yii::app()->db->beginTransaction();
				foreach ($resultset as $result)
				{
					$bankLedgerId	 = PaymentType::model()->ledgerList($result['agt_ptp_id']);
					$userType		 = ($result['agt_admin_id'] != '') ? BookingLog::Admin : BookingLog::Agent;
					$userId			 = ($result['agt_trans_user_id'] != '') ? $result['agt_trans_user_id'] : ['agt_admin_id'];
					$payeeRefType	 = NULL;
					$payeeRefId		 = NULL;

					if (in_array($bankLedgerId, Accounting::getOnlineLedgers(false)))
					{
						$paymentGateway							 = new PaymentGateway();
						$paymentGateway->apg_ptp_id				 = $result['agt_ptp_id'];
						$paymentGateway->apg_ledger_id			 = $bankLedgerId;
						$paymentGateway->apg_acc_trans_type		 = Accounting::AT_PARTNER;
						$paymentGateway->apg_trans_ref_id		 = $result['agt_agent_id'];
						$paymentGateway->apg_banktrans_type		 = $result['agt_trans_type'];
						$paymentGateway->apg_code				 = $result['agt_trans_code'];
						$paymentGateway->apg_mode				 = $result['agt_trans_mode'];
						$paymentGateway->apg_remarks			 = $result['agt_trans_remarks'];
						$paymentGateway->apg_ipaddress			 = $result['agt_trans_ipaddress'];
						$paymentGateway->apg_device_detail		 = $result['agt_trans_device_detail'];
						$paymentGateway->apg_user_type			 = $userType;
						$paymentGateway->apg_user_id			 = $userId;
						$paymentGateway->apg_amount				 = $result['agt_trans_amount'];
						$paymentGateway->apg_active				 = $result['agt_trans_active'];
						$paymentGateway->apg_status				 = $result['agt_trans_status'];
						$paymentGateway->apg_date				 = $result['agt_trans_start_datetime'];
						$paymentGateway->apg_ref_id				 = $result['agt_trans_ref_id'];
						$paymentGateway->apg_response_details	 = $result['agt_trans_response_details'];
						$paymentGateway->apg_response_code		 = $result['agt_trans_response_code'];
						$paymentGateway->apg_response_message	 = $result['agt_trans_response_message'];
						$paymentGateway->apg_txn_id				 = $result['agt_trans_txn_id'];
						$paymentGateway->apg_merchant_ref_id	 = 0;
						$paymentGateway->apg_start_datetime		 = $result['agt_trans_start_datetime'];
						$paymentGateway->apg_complete_datetime	 = $result['agt_trans_complete_datetime'];
						if ($paymentGateway->save())
						{
							$payeeRefType	 = Accounting::AT_ONLINEPAYMENT;
							$payeeRefId		 = $paymentGateway->apg_id;
						}
					}

					$accTransModel				 = new AccountTransactions();
					$accTransModel->act_amount	 = -1 * $result['agt_trans_amount'];
					$accTransModel->act_date	 = $result['agt_trans_start_datetime'];
					$accTransModel->act_type	 = Accounting::AT_PARTNER;
					$accTransModel->act_ref_id	 = $result['agt_agent_id'];
					if ($result['agt_booking_id'] != '')
					{
						$result['agt_trans_remarks'] .= " (Entry Against booking ID :)" . $result['agt_booking_id'];
					}
					$accTransModel->act_remarks	 = $result['agt_trans_remarks'];
					$accountTrans				 = $accTransModel->AddReceipt($bankLedgerId, Accounting::LI_PARTNER, $payeeRefId, $result['agt_agent_id'], $result['agt_trans_remarks'], $payeeRefType, $userInfo, $result['agt_trans_id']);

					echo "(" . $i . ")" . $accountTrans->act_id . "--";
				}

				$transaction->commit();
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			$transaction->rollback();
		}
	}

	public function transferPartnerCoinsUsed()
	{
		$i = 50000;
		try
		{
			while (true && $i < 500000)
			{
				$sql		 = "SELECT * FROM agent_transactions WHERE agt_ptp_id=13 AND agt_trans_active=1 AND agt_trans_status=1 ORDER BY agt_trans_id LIMIT $i, 10000"; //86910
				$i			 = $i + 10000;
				$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
				if ($resultset == [])
				{
					break;
				}
				$transaction = Yii::app()->db->beginTransaction();
				foreach ($resultset as $result)
				{
					$bankLedgerId	 = PaymentType::model()->ledgerList($result['agt_ptp_id']);
					$userType		 = ($result['agt_admin_id'] != '') ? BookingLog::Admin : BookingLog::Agent;
					$userId			 = ($result['agt_trans_user_id'] != '') ? $result['agt_trans_user_id'] : ['agt_admin_id'];
					$payeeRefType	 = Accounting::AT_PARTNER;
					$payeeRefId		 = $result['agt_agent_id'];

					$accTransModel				 = new AccountTransactions();
					$accTransModel->act_amount	 = $result['agt_trans_amount'];
					$accTransModel->act_date	 = $result['agt_trans_start_datetime'];
					$accTransModel->act_type	 = Accounting::AT_BOOKING;
					$accTransModel->act_ref_id	 = $result['agt_booking_id'];
					$accTransModel->act_remarks	 = $result['agt_trans_remarks'];
					$accountTrans				 = $accTransModel->AddReceipt($bankLedgerId, Accounting::LI_BOOKING, $result['agt_booking_id'], $result['agt_booking_id'], $result['agt_trans_remarks'], $payeeRefType, $userInfo, $result['agt_trans_id']);

					$accTransModel				 = new AccountTransactions();
					$accTransModel->act_amount	 = $result['agt_trans_amount'];
					$accTransModel->act_date	 = $result['agt_trans_start_datetime'];
					$accTransModel->act_type	 = Accounting::AT_PARTNER;
					$accTransModel->act_ref_id	 = $result['agt_agent_id'];
					$accTransModel->act_remarks	 = $result['agt_trans_remarks'];
					$accountTrans				 = $accTransModel->AddReceipt(Accounting::LI_PARTNER, $bankLedgerId, $payeeRefId, $result['agt_booking_id'], $result['agt_trans_remarks'], $payeeRefType, $userInfo, $result['agt_trans_id'], Accounting::AT_BOOKING);
					echo "(" . $i . ")" . $accountTrans->act_id . "--";
				}

				$transaction->commit();
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			$transaction->rollback();
		}
	}

	public function transferVendorTransactions()
	{

		$i	 = $j	 = 0;
		try
		{
			while (true)
			{
				$sql = "SELECT * FROM vendor_transactions WHERE ven_trans_active = 1 AND ven_trans_status=1 AND ven_ptp_id IS NOT NULL AND ven_trip_id IS NULL  LIMIT $i, 10000";
				$i	 = $i + 10000;

				$resultset = Yii::app()->db->createCommand($sql)->queryAll();
				if ($resultset == [])
				{
					break;
				}
				$transaction = Yii::app()->db->beginTransaction();
				foreach ($resultset as $result)
				{
					$result['ven_ptp_id']	 = 2;
					$bankLedgerId			 = PaymentType::model()->ledgerList($result['ven_ptp_id']);
					$userType				 = ($result['ven_admin_id'] != '') ? BookingLog::Admin : BookingLog::System;
					$userId					 = ($result['ven_admin_id'] != '') ? 0 : ['ven_admin_id'];
					$payeeRefType			 = NULL;
					$payeeRefId				 = NULL;

					if (in_array($bankLedgerId, Accounting::getOnlineLedgers(false)))
					{
						$paymentGateway							 = new PaymentGateway();
						$paymentGateway->apg_ptp_id				 = $result['ven_ptp_id'];
						$paymentGateway->apg_ledger_id			 = $bankLedgerId;
						$paymentGateway->apg_acc_trans_type		 = Accounting::AT_OPERATOR;
						$paymentGateway->apg_trans_ref_id		 = $result['trans_vendor_id'];
						$paymentGateway->apg_banktrans_type		 = $result['ven_trans_type'];
						$paymentGateway->apg_code				 = $result['ven_trans_code'];
						$paymentGateway->apg_mode				 = $result['ven_trans_mode'];
						$paymentGateway->apg_remarks			 = $result['ven_trans_remarks'];
						$paymentGateway->apg_ipaddress			 = NULL;
						$paymentGateway->apg_device_detail		 = NULL;
						$paymentGateway->apg_user_type			 = $userType;
						$paymentGateway->apg_user_id			 = $userId;
						$paymentGateway->apg_amount				 = $result['ven_trans_amount'];
						$paymentGateway->apg_active				 = $result['ven_trans_active'];
						$paymentGateway->apg_status				 = $result['ven_trans_status'];
						$paymentGateway->apg_date				 = $result['ven_trans_date'];
						$paymentGateway->apg_ref_id				 = 0;
						$paymentGateway->apg_response_details	 = $result['ven_trans_response_details'];
						$paymentGateway->apg_response_code		 = $result['ven_trans_response_code'];
						$paymentGateway->apg_response_message	 = $result['ven_trans_response_message'];
						$paymentGateway->apg_txn_id				 = $result['ven_trans_txn_id'];
						$paymentGateway->apg_merchant_ref_id	 = 0;
						$paymentGateway->apg_start_datetime		 = $result['ven_trans_date'];
						$paymentGateway->apg_complete_datetime	 = $result['ven_trans_complete_date'];
						if ($paymentGateway->save())
						{
							$payeeRefType	 = Accounting::AT_ONLINEPAYMENT;
							$payeeRefId		 = $paymentGateway->apg_id;
						}
					}

					$accTransModel				 = new AccountTransactions();
					$accTransModel->act_amount	 = -1 * $result['ven_trans_amount'];
					$accTransModel->act_date	 = ($result['ven_trans_date'] != '') ? $result['ven_trans_date'] : $result['ven_trans_complete_date'];
					$accTransModel->act_type	 = Accounting::AT_OPERATOR;
					$accTransModel->act_ref_id	 = $result['trans_vendor_id'];
					$accTransModel->act_remarks	 = $result['ven_trans_remarks'];
					$accountTrans				 = $accTransModel->AddReceipt($bankLedgerId, Accounting::LI_OPERATOR, $payeeRefId, $result['trans_vendor_id'], $result['ven_trans_remarks'], $payeeRefType, $userInfo, $result['ven_trans_id']);
					$j++;
					echo "(" . $j . ")" . $accountTrans->act_id . "--";
				}
				$transaction->commit();
			}
		}
		catch (Exception $e)
		{

			echo $e->getMessage();
			$transaction->rollback();
		}
	}

	public function transferVendorCollected()
	{

		$i	 = $j	 = 0;
		$i	 = 50000;
		try
		{
			while (true && $i < 500000)
			{
				$sql		 = "
	SELECT
	ven_trans_id, ven_booking_id, ven_admin_id, trans_vendor_id, ven_trans_date, ven_trip_id, ven_trans_amount, bkg_id, bcb_id, bcb_vendor_amount,
	biv.bkg_vendor_collected, biv.bkg_vendor_amount
	FROM booking_cab
	INNER JOIN booking ON bkg_bcb_id=bcb_id AND bcb_vendor_id IS NOT NULL  AND bkg_status IN (6,7)
	INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
	INNER JOIN vendor_transactions ON ven_trip_id=bcb_id AND vendor_transactions.ven_trans_status=1 AND vendor_transactions.ven_trans_active=1
	AND ven_trip_id IS NOT NULL  LIMIT $i, 10000";
				$i			 = $i + 10000;
				$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
				if ($resultset == [])
				{
					break;
				}
				$transaction = Yii::app()->db->beginTransaction();
				foreach ($resultset as $result)
				{
					$userId		 = 0;
					$userType	 = BookingLog::System;
					if ($result['ven_admin_id'] != '' && $result['ven_admin_id'] != 0)
					{
						$userId		 = $result['ven_admin_id'];
						$userType	 = BookingLog::Admin;
					}
					AccountTransactions::model()->AddVendorCollection($result['bcb_vendor_amount'], $result['bkg_vendor_collected'], $result['ven_trip_id'], $result['bkg_id'], $result['trans_vendor_id'], $result['ven_trans_date'], $userInfo);
					$j++;
					echo $j . "==";
				}

				$transaction->commit();
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			$transaction->rollback();
		}
	}

	public function actionAddPartnerCommissionFromBKGTopartner()
	{
		$sql		 = "SELECT act.act_date, adt.*, adt1.adt_trans_ref_id as partnerId FROM account_trans_details adt
INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND act.act_active = 1 AND act.act_status = 1
INNER JOIN  account_trans_details adt1 ON adt1.adt_trans_id=act.act_id AND adt1.adt_active=1 AND adt1.adt_ledger_id=35
WHERE adt.adt_ledger_id IN (13) AND adt.adt_active=1 AND adt.adt_status =1
    AND adt.adt_trans_ref_id NOT IN
        (SELECT atd1.adt_trans_ref_id FROM account_trans_details atd
            INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND DATE(act_date)<=CURDATE() AND atd.adt_remarks LIKE '%commission%' AND atd.adt_ledger_id IN (15)
INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND act.act_active=1 AND atd1.adt_ledger_id=26 ORDER BY atd1.adt_id DESC
        )";
		$partnerComm = Yii::app()->db->createCommand($sql)->queryAll();
		//$partnerComm = AccountTransactions::model()->updatePartnerCommission();
		foreach ($partnerComm as $val)
		{
			$agtcommpartner = AccountTransactions::model()->AddCommissiontopartnerNewentry(Accounting::LI_COMMISSION, Accounting::LI_PARTNER, $val['adt_trans_ref_id'], $val['partnerId'], 'Commision added partner', Accounting::AT_BOOKING, Accounting::AT_PARTNER, BookingLog::Admin, Yii::app()->user->getId(), $val['adt_amount']);
		}
	}

	public function actionPartnerCommission()
	{
		$sql		 = "SELECT act.act_date, atd1.* FROM account_trans_details atd
				INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND DATE(act_date)<=CURDATE() AND atd.adt_remarks LIKE '%commission%' AND atd.adt_ledger_id IN (15)
				INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND act.act_active=1 AND atd1.adt_ledger_id=26 ORDER BY atd1.adt_id DESC";
		$partnerComm = Yii::app()->db->createCommand($sql)->queryAll();
		//$partnerComm = AccountTransactions::model()->updatePartnerCommissionrevert();
		foreach ($partnerComm as $val)
		{
			$adt				 = AccountTransDetails::model()->findByPk($val['adt_id']);
			$adt['adt_active']	 = 0;
			$adt['adt_status']	 = 0;
			$adt->save();
		}
	}

	public function actionAddPartnerCommissionAll()
	{
		$sql		 = "SELECT bkg.bkg_booking_id,bkg_id,bkg_agent_id,biv.bkg_agent_markup,bkg_pickup_date from booking bkg
                  INNER JOIN agents agt ON bkg.bkg_agent_id=agt.agt_id
                  INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg.bkg_id
                  WHERE agt.agt_type=2 AND agt.agt_active=1 AND bkg.bkg_status IN(9) AND (bkg_advance_amount-bkg_refund_amount)>0 AND bkg_active=1 AND agt_commission>0";
		$partnerComm = Yii::app()->db->createCommand($sql)->queryAll();
		//$partnerComm = AccountTransactions::model()->getIdforPartnerCommission();
		foreach ($partnerComm as $val)
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$model			 = Booking::model()->findByPk($val['bkg_id']);
//				$amt			 = $model->calAgentCommission();
				$cancelCharge	 = ($model->bkgInvoice->bkg_advance_amount - $model->bkgInvoice->bkg_refund_amount);
				$commisionType	 = $model->bkgAgent->agt_commission_value;
				$commision		 = $model->bkgAgent->agt_commission | 0;
				if ($model->bkgAgent->agt_type == 0 || $model->bkgAgent->agt_type == 1)
				{
					$commision = 0;
				}
				$agtComm = ($commisionType == 1) ? round($commision * $cancelCharge * 0.01) : 0;
				$amt	 = $agtComm;
				if (($model->bkgInvoice->bkg_total_amount != $cancelCharge && $model->bkg_agent_id != 450) || $amt == 0)
				{
					$transaction->rollback();
					continue;
				}

				$success = AccountTransactions::model()->AddCommission($model->bkg_pickup_date, $model->bkg_id, $model->bkg_agent_id, $amt);
				if (!$success)
				{
					throw new Exception("Commission failed for booking ID: {$val['bkg_id']}");
				}
				$transaction->commit();
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
				$transaction->rollback();
			}
		}
	}

	public function actionTransferCashTransaction()
	{
		$sql		 = "SELECT *
						FROM   account_trans_details atd INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id
						WHERE  atd.adt_ledger_id = 1 AND act.act_active = 1 AND atd.adt_active = 1 AND act.act_type IN(1,2,3)";
		$cashtrans	 = Yii::app()->db->createCommand($sql)->queryAll();

		foreach ($cashtrans as $result)
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$paymentGateway						 = new PaymentGateway();
				$paymentGateway->apg_ptp_id			 = 1;
				$paymentGateway->apg_ledger_id		 = 1;
				$paymentGateway->apg_acc_trans_type	 = $result['act_type'];
				$paymentGateway->apg_trans_ref_id	 = $result['act_ref_id'];
				$paymentGateway->apg_code			 = date('ymdHis') . str_pad($result['act_id'], 3, 0, STR_PAD_LEFT);
				$paymentGateway->apg_mode			 = ($result['act_amount'] > 0) ? 2 : 1;
				$paymentGateway->apg_remarks		 = $result['act_remarks'];
				$paymentGateway->apg_ipaddress		 = NULL;
				$paymentGateway->apg_device_detail	 = NULL;
				$paymentGateway->apg_user_type		 = $result['act_user_type'];
				$paymentGateway->apg_user_id		 = $result['act_user_id'];
				$paymentGateway->apg_amount			 = $result['act_amount'];
				$paymentGateway->apg_active			 = 1;
				$paymentGateway->apg_status			 = 1;
				$paymentGateway->apg_date			 = $result['act_date'];
				$paymentGateway->apg_ref_id			 = 0;
				if ($paymentGateway->save())
				{
					$paymentId				 = $paymentGateway->apg_id;
					$adt					 = AccountTransDetails::model()->findByPk($result['adt_id']);
					$adt['adt_trans_ref_id'] = $paymentId;
					$adt->save();
				}

				$transaction->commit();
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
				$transaction->rollback();
			}
		}
	}

	public function actionTransferPartnercoinTransaction()
	{
		$sql		 = "SELECT *
						FROM   account_trans_details atd INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id
						WHERE  atd.adt_ledger_id = 26 AND act.act_active = 1 AND atd.adt_active = 1 AND act.act_type IN(1,3) ORDER BY atd.adt_id DESC LIMIT 1,1";
		$cashtrans	 = Yii::app()->db->createCommand($sql)->queryAll();

		foreach ($cashtrans as $result)
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$paymentGateway						 = new PaymentGateway();
				$paymentGateway->apg_ptp_id			 = 13;
				$paymentGateway->apg_ledger_id		 = 26;
				$paymentGateway->apg_acc_trans_type	 = $result['act_type'];
				$paymentGateway->apg_trans_ref_id	 = $result['act_ref_id'];
				$paymentGateway->apg_code			 = date('ymdHis') . str_pad($result['act_id'], 3, 0, STR_PAD_LEFT);
				$paymentGateway->apg_mode			 = ($result['act_amount'] > 0) ? 2 : 1;
				$paymentGateway->apg_remarks		 = $result['act_remarks'];
				$paymentGateway->apg_ipaddress		 = NULL;
				$paymentGateway->apg_device_detail	 = NULL;
				$paymentGateway->apg_user_type		 = $result['act_user_type'];
				$paymentGateway->apg_user_id		 = $result['act_user_id'];
				$paymentGateway->apg_amount			 = $result['act_amount'];
				$paymentGateway->apg_active			 = 1;
				$paymentGateway->apg_status			 = 1;
				$paymentGateway->apg_date			 = $result['act_date'];
				$paymentGateway->apg_ref_id			 = 0;
				if ($paymentGateway->save())
				{
					$paymentId				 = $paymentGateway->apg_id;
					$adt					 = AccountTransDetails::model()->findByPk($result['adt_id']);
					$adt['adt_trans_ref_id'] = $paymentId;
					$adt->save();
				}

				$transaction->commit();
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
				$transaction->rollback();
			}
		}
	}

	public function actionAutoRefundAgainstCancellation()
	{
		BookingInvoice::model()->autoInitiateRefund();
	}

	public function actionAddCancellation()
	{
		$sql	 = "SELECT atd.adt_trans_ref_id as bkgId, GROUP_CONCAT(DISTINCT atd.adt_trans_id) as transId, SUM(atd.adt_amount) as amount,
			(bkg_advance_amount-bkg_refund_amount+bkg_credits_used - SUM(IF(atd1.adt_ledger_id=25, atd1.adt_amount*-1,0))) as excessCancelFee,
			(SUM(IF(atd1.adt_ledger_id IN (25,1,16,17,18,19,20,21,23,26,29,30,32,36,39,41,42,46,47), atd1.adt_amount*-1,0))) as excessCancelFee1
			,bkg_agent_id
		   FROM account_trans_details atd
		   INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id AND atd.adt_ledger_id=13 AND act.act_active=1 AND atd.adt_active=1
		   INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1
		   INNER JOIN booking ON bkg_id=atd.adt_trans_ref_id AND booking.bkg_status=9 AND bkg_agent_id>0
		   INNER JOIN booking_invoice ON biv_bkg_id=bkg_id
		   WHERE 1
		   GROUP BY bkgId 
		   HAVING excessCancelFee < 0 AND excessCancelFee = (excessCancelFee1 * -1) AND bkgId = 1586612";
		$record	 = DBUtil::queryAll($sql, DBUtil::SDB());
		foreach ($record as $data)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				AccountTransactions::model()->advanceReceived($data['pickupdate'], PaymentType::TYPE_AGENT_CORP_CREDIT, $data['bkg_agent_id'], $data['excessCancelFee'], Accounting::AT_BOOKING, $data['bkgId'], 'Cancellation charged');

				echo $data['bkgId'] . "<br>";
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionProcessPendingPayments()
	{
		OnlineBanking::processPendingPayments();
	}
	
	public function actionUpdateOutstandingLedgerBalance($defaultDays = 3)
	{
		$check = Filter::checkProcess("transaction updateOutstandingLedgerBalance");
		if (!$check)
		{
			return;
		}
		
		VendorStats::UpdateLedgerBalance($defaultDays);
		PartnerStats::UpdateLedgerBalance($defaultDays);
		PartnerStats::UpdateWalletBalance($defaultDays);
		UserWallet::UpdateWalletBalance($defaultDays);
	}
	
	public function actionUpdatePartnerBalance($defaultDaysOrHr = 3, $intervalOption = 'HOUR')
	{
		$check = Filter::checkProcess("transaction updatePartnerBalance");
		if (!$check)
		{
			return;
		}
		
		PartnerStats::UpdateLedgerBalance($defaultDaysOrHr, $intervalOption);
		PartnerStats::UpdateWalletBalance($defaultDaysOrHr, $intervalOption);
	}

	public function actionTransferNegativeWalletBalanceToLedgerBalance()
	{
		$sql	 = "SELECT DISTINCT(atd.adt_trans_ref_id) AS partnerID FROM account_trans_details atd
					INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id 
					WHERE atd.adt_active = 1 AND atd.adt_active = 1 AND atd.adt_ledger_id = 49 
					AND act_created BETWEEN  DATE_SUB(NOW(), INTERVAL 10 DAY) AND NOW()";
		
		$rows	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($rows as $data)
		{
			$transaction = null;
			try
			{
				$partnerId = $data['partnerID'];
				$result = AccountTransactions::checkPartnerWalletBalance($partnerId);		
				if($result <0)
				{
					$amount	 = -1 * $result;
					AccountTransactions::issuePartnerWallet($partnerId, $amount, '', $remarks = "Transfer wallet balance to partner ledger due to negative wallet balance", UserInfo::model());
				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
			}
		}
	}

	public function actionProcessLedgerData()
	{
		
	}
}
