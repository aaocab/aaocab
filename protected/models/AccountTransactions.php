<?php

/**
 * This is the model class for table "account_transactions".
 *
 * The followings are the available columns in table 'account_transactions':
 * @property integer $act_id
 * @property string $act_type
 * @property integer $act_ref_id
 * @property double $act_amount
 * @property string $act_remarks
 * @property integer $act_active
 * @property integer $act_status
 * @property string $act_date
 * @property string $act_created
 * @property string $act_modified
 * @property integer $act_user_type
 * @property integer $act_user_id
 */
class AccountTransactions extends CActiveRecord
{

	public $bank_chq_no, $bank_chq_dated, $bank_name, $apg_ledger_id_2, $apg_trans_type, $apg_ledger_id_3, $apg_type, $apg_ledger_id_1, $bank_ifsc, $bank_branch, $apg_from_date, $apg_to_date, $apg_date_type, $apg_operator_id;
	public $apg_is_invoice;
	public $bankTransType		 = [1 => 'Cash', 2 => 'Cheque', 3 => 'NEFT/RTGS'];
	public $modeList			 = [1 => 'Debit', 2 => 'Credit'];
	public $operatorList		 = [0 => 'Operator Paid Gozo', 1 => 'Gozo Paid Operator', 2 => 'Gozo Lend To Operator', 3 => 'Operator Lend To Gozo'];
	public $operatorListPartner	 = [0 => 'Agent Paid Gozo', 1 => 'Gozo Paid Agent'], $operator_id;
	public $accGozoPaidList		 = [27 => 'Compensation (Direct Expenses)'];
	public $accGozoReceiverList	 = [28 => 'Penalty (Indirect Expenses)'];
	public $booking_id, $total_penalty, $penalty_amount, $additional_remarks, $penalty_other_reason, $penalty_rule_reason;
	public $from_date, $to_date, $drv_code, $search, $bkg_agent_id;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'account_transactions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('act_type, act_amount', 'required'),
			array('act_ref_id, act_active, act_status', 'numerical', 'integerOnly' => true),
			array('act_amount', 'numerical'),
			array('act_amount', 'validateAmountSum', 'on' => 'insert'),
			array('act_type', 'length', 'max' => 11),
			array('act_remarks', 'length'),
			array('act_date', 'safe'),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('act_id, act_type,act_user_type,act_user_id, act_ref_id,act_created,act_modified, act_amount, act_remarks, act_active, act_status, act_date', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array(
			'accountTransDetails' => array(self::HAS_MANY, 'AccountTransDetails', 'adt_trans_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'act_id'		 => 'Act',
			'act_type'		 => '1=>Booking,2=>Vendor,3=>Agent,4=>OnlinePayment,5=>Trip',
			'act_ref_id'	 => 'Act Ref',
			'act_amount'	 => 'Act Amount',
			'act_remarks'	 => 'Act Remarks',
			'act_active'	 => 'Act Active',
			'act_status'	 => 'Act Status',
			'act_date'		 => 'Act Date',
			'act_created'	 => 'Created Date'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('act_id', $this->act_id);
		$criteria->compare('act_type', $this->act_type, true);
		$criteria->compare('act_ref_id', $this->act_ref_id);
		$criteria->compare('act_amount', $this->act_amount);
		$criteria->compare('act_remarks', $this->act_remarks, true);
		$criteria->compare('act_active', $this->act_active);
		$criteria->compare('act_status', $this->act_status);
		$criteria->compare('act_date', $this->act_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccountTransactions the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param int $type
	 * @param string $date
	 * @param int $amount
	 * @param string $remarks
	 * @param int $refId
	 * @param UserInfo $userInfo
	 * @return \AccountTransactions
	 */
	public static function getInstance($type, $date, $amount, $remarks, $refId = null, $userInfo = null)
	{
		if ($userInfo == null)
		{
			$userInfo = UserInfo::getInstance();
		}
		$accTransModel					 = new AccountTransactions();
		$accTransModel->act_amount		 = abs($amount);
		$accTransModel->act_date		 = ($date == '') ? new CDbExpression('NOW()') : $date;
		$accTransModel->act_type		 = $type;
		$accTransModel->act_ref_id		 = $refId;
		$accTransModel->act_remarks		 = $remarks;
		$accTransModel->act_user_id		 = $userInfo->userId;
		$accTransModel->act_user_type	 = $userInfo->userType;
		$accTransModel->act_active		 = 1;
		$accTransModel->act_status		 = 1;
		return $accTransModel;
	}

	/** @param int/string separated by comma $actIds Account Transaction Id */
	public static function remove($actIds)
	{
		$success = true;
		try
		{
			$actIds	 = is_string($actIds) ? $actIds : strval($actIds);
			DBUtil::getINStatement($actIds, $bindString, $params);
			$sql	 = "UPDATE account_transactions SET act_active=0 WHERE act_id IN ({$bindString}) ";
			$row	 = DBUtil::execute($sql, $params);
			if ($row == 0)
			{
				throw Exception("0 rows updated for trans id: {$actIds}");
			}
		}
		catch (Exception $e)
		{
			$success = false;
			Logger::exception($e);
		}
		return $success;
	}

	public function validateAmountSum($attribute)
	{
		$totAmount	 = 0;
		$totDebit	 = 0;
		$totCredit	 = 0;
		foreach ($this->accountTransDetails as $model)
		{
			$totAmount = $totAmount + $model->adt_amount;
			if ($model->adt_amount > 0)
			{
				$totDebit = $totDebit + $model->adt_amount;
			}
			if ($model->adt_amount < 0)
			{
				$totCredit = $totCredit + $model->adt_amount;
			}
		}
		if ($totAmount == 0 && ($totDebit == $this->act_amount || $totCredit == $this->act_amount))
		{
			return true;
		}
		else
		{
			$this->addError($attribute, "Amount Mismatch");
			return false;
		}
	}

	public function getByBooking($bkgId)
	{
		return $this->find('act_ref_id=:ref AND act_type=' . Accounting::AT_BOOKING, ['ref' => $bkgId]);
	}

	public function getByRefId($refid)
	{
		return $this->find('act_ref_id=:ref', ['ref' => $refid]);
	}

	/**
	 * 
	 * @param AccountTransDetails[] $arrTransDetails
	 * @return boolean
	 * @throws Exception
	 */
	public function create($arrTransDetails)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			$this->accountTransDetails = $arrTransDetails;

			if (!$this->save())
			{
				throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			AccountTransDetails::saveAll($arrTransDetails, $this);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($e);
			if ($e->getCode() != ReturnSet::ERROR_VALIDATION)
			{
				$this->addError("act_id", $e->getMessage());
			}
			else
			{
				$this->addErrors(json_decode($e->getMessage()));
			}
			throw new Exception($e);
		}
		return true;
	}

	/**
	 * @param AccountTransDetails[] $transDetails  
	 * @param DateTime $date
	 * @param int $refId;
	 * @param int $refType Accounting::AT_*;
	 * @return AccountTransactions 
	 * @throws Exception
	 */
	public function add($transDetails, $date = NULL, $amount, $refId, $refType, $remarks = null, $userInfo = null)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		if ($userInfo == null)
		{
			$userInfo = UserInfo::getInstance();
		}
		$modelAccTrans						 = new AccountTransactions();
		$modelAccTrans->act_type			 = $refType;
		$modelAccTrans->act_ref_id			 = $refId;
		$modelAccTrans->act_amount			 = abs($amount);
		$modelAccTrans->act_remarks			 = $remarks;
		$modelAccTrans->act_status			 = 1;
		$modelAccTrans->act_date			 = ($date == NULL) ? new CDbExpression('NOW()') : $date;
		$modelAccTrans->act_user_type		 = $userInfo->userType;
		$modelAccTrans->act_user_id			 = $userInfo->userId;
		$modelAccTrans->accountTransDetails	 = $transDetails;

		$transaction = DBUtil::beginTransaction();
		try
		{
			if (!$modelAccTrans->save())
			{
				Logger::trace("transaction not saved :: ref id: " . $refId . " :: error : " . json_encode($modelAccTrans->getErrors()));
				throw new Exception(json_encode($modelAccTrans->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			Logger::trace("transaction saved ::ref id: " . $refId);
			AccountTransDetails::saveAll($transDetails, $modelAccTrans);
			Logger::trace("transaction details saved ::ref id: " . $refId);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			Logger::exception($e);
			DBUtil::rollbackTransaction($transaction);
			if ($e->getCode() != ReturnSet::ERROR_VALIDATION)
			{
				$this->addError("act_id", $e->getMessage());
			}
			else
			{
				$this->addErrors(json_decode($e->getMessage()));
			}
			Logger::trace("add refund act failed" . $e->getMessage());
			throw new Exception($e);
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $modelAccTrans;
	}

	/**
	 * @param AccountTransDetails $drTransDetails
	 * @param AccountTransDetails $crTransDetails
	 */
	public function processReceipt($drTransDetails, $crTransDetails, $drAcctType)
	{
		$drAcctType	 = ($drTransDetails->adt_type == 0) ? $this->act_type : $drAcctType;
		$drAcctType	 = (in_array($drTransDetails->adt_ledger_id, Accounting::getOfflineLedgers(false))) ? NULL : $drAcctType;
		if ($drAcctType != null && $drTransDetails->adt_type == null)
		{
			$drTransDetails->adt_type = $drAcctType;
		}
		$drTransDetails->adt_amount	 = $this->act_amount;
		$accTransDetArr[]			 = $drTransDetails;
		$accType1					 = $this->act_type;
		if ($this->act_type == Accounting::AT_TRIP && $crTransDetails->adt_trans_ref_id != '')
		{
			$accType1 = 2;
		}
		if ($crTransDetails->adt_type != 0)
		{
			$accType1 = $crTransDetails->adt_type;
		}
		$crTransDetails->adt_amount	 = $this->act_amount * -1;
		$crTransDetails->adt_type	 = $accType1;
		$accTransDetArr[]			 = $crTransDetails;
		return $this->create($accTransDetArr);
	}

	/**
	 * @deprecated
	 * @see  AccountTransactions::processReceipt
	 * @param type $drLedgerId
	 * @param type $crLedgerID
	 * @param type $drRefId
	 * @param type $crRefId
	 * @param type $addtParams
	 * @param type $drAcctType
	 * @param type $userInfo
	 * @param type $oldTransId
	 * @param type $crAccType
	 * @param type $successRemarks
	 * @return AccountTransactions
	 */
	public function AddReceipt($drLedgerId, $crLedgerID, $drRefId = 0, $crRefId = 0, $addtParams = '', $drAcctType = 0, $userInfo = null, $oldTransId = 0, $crAccType = 0, $successRemarks = '', $penaltyData = null)
	{
		$accTransDetArr		 = [];
		$drAcctType			 = ($drAcctType == 0) ? $this->act_type : $drAcctType;
		$drAcctType			 = (in_array($drLedgerId, Accounting::getOfflineLedgers(false))) ? NULL : $drAcctType;
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams($drAcctType, $drRefId, $drLedgerId, $this->act_amount, $addtParams, 0, $oldTransId, $successRemarks, $penaltyData);
		$accType1			 = $this->act_type;
		if ($this->act_type == 5 && $crRefId != '')
		{
			$accType1 = 2;
		}
		if ($crAccType != 0)
		{
			$accType1 = $crAccType;
		}
		$accTransDetArr[] = AccountTransDetails::model()->initializeParams($accType1, $crRefId, $crLedgerID, (-1 * $this->act_amount), $addtParams, 0, $oldTransId, '', $penaltyData);

		return $this->add($accTransDetArr, $this->act_date, $this->act_amount, $this->act_ref_id, $this->act_type, $this->act_remarks, $userInfo);
	}

	public function AddPartnerCoins($bankLedgerID, $payeeLedgerID, $bankRefId = 0, $payeeRefId = 0, $addtParams = '', $bankCharge = 0)
	{
		$accTransDetArr		 = [];
		$amtCredited		 = ($bankCharge > 0) ? $this->act_amount - $bankCharge : $this->act_amount;
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_ONLINEPAYMENT, $bankRefId, $bankLedgerID, $this->act_amount, $addtParams);
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $payeeRefId, $payeeLedgerID, -1 * $amtCredited);
		if ($bankCharge > 0)
		{
			$accTransDetArr[] = AccountTransDetails::model()->initializeParams(Accounting::AT_ONLINEPAYMENT, $bankRefId, Accounting::LI_BANKCHARGE, (-1 * $bankCharge), "Bank charge deducted");
		}

		return $this->add($accTransDetArr, $this->act_date, $this->act_amount, $this->act_ref_id, $this->act_type, $addtParams);
	}

	/**
	 * Accounts the entries after payment confirmation for partner
	 * 
	 * @param integer $partnerId Partner id 
	 * @param integer $bankLedgerID Ledger id of the payment type
	 * @param integer $bankRefId reference id of payment 
	 * @param integer $amount amount used in payment
	 * @param integer $bankCharge amount deducted by bank/payment source
	 * @param string $remarks Remark
	 * @param string $bankChargeRemarks Remark 
	 * @return boolean
	 */
	public function AddPartnerPayment($partnerId, $bankLedgerID, $bankRefId, $amount, $bankCharge = 0, $remarks = '', $bankChargeRemarks = '')
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$success = false;
		$trans	 = DBUtil::beginTransaction();
		try
		{
			if ($amount == 0)
			{
				goto commit;
			}
			// Online A/C to Partner A/C
			$accTransDetArr		 = [];
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_ONLINEPAYMENT, $bankRefId, $bankLedgerID, $amount, $remarks);
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $partnerId, Accounting::LI_PARTNER, (-1 * $amount), $remarks);
			$addedRes			 = $this->add($accTransDetArr, $this->act_date, $this->act_amount, $this->act_ref_id, Accounting::AT_PARTNER, $remarks);
			if ($addedRes && $bankCharge > 0)
			{
				//  Partner A/C to Bank charge
				$accTransDetArr		 = [];
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $partnerId, Accounting::LI_PARTNER, $bankCharge, $bankChargeRemarks);
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams('', '', Accounting::LI_BANKCHARGE, (-1 * $bankCharge), $bankChargeRemarks);
				$addedRes			 = $this->add($accTransDetArr, $this->act_date, $bankCharge, $this->act_ref_id, Accounting::AT_PARTNER, $bankChargeRemarks);
			}
			commit:
			DBUtil::commitTransaction($trans);

			$success = true;
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($trans);
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $success;
	}

	public function AddVendorReceipt($bankLedgerID, $payeeLedgerID, $bankRefId = 0, $payeeRefId = 0, $addtParams = '', $accType = 0, $bankCharge = 0, $userInfo = null)
	{
		$accTransDetArr		 = [];
		//$amtCredited		 = ($bankCharge > 0) ? $this->act_amount - $bankCharge : $this->act_amount;
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_ONLINEPAYMENT, $bankRefId, $bankLedgerID, $this->act_amount, "Payment received by vendor apgId:" . $bankRefId);
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $payeeRefId, $payeeLedgerID, -1 * $this->act_amount, "Payment received by vendor apgId:" . $bankRefId);
		$this->add($accTransDetArr, $this->act_date, $this->act_amount, $this->act_ref_id, $this->act_type, $addtParams, $userInfo);
		if ($bankCharge > 0)
		{
			$accTransDetArr1	 = [];
			$accTransDetArr1[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_ONLINEPAYMENT, $bankRefId, Accounting::LI_BANKCHARGE, (-1 * $bankCharge), "Bank charge deducted for apgId:" . $bankRefId);
			$accTransDetArr1[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $payeeRefId, $payeeLedgerID, $bankCharge, "Bank charge deducted for apgId:" . $bankRefId);
			$this->add($accTransDetArr1, $this->act_date, $bankCharge, $this->act_ref_id, $this->act_type, $addtParams, $userInfo);
		}
		//return $this->add($accTransDetArr, $this->act_date, $this->act_amount, $this->act_ref_id, $this->act_type, $addtParams);
	}

	public function AddGiftCardReceipt($bankLedgerID, $payeeLedgerID, $bankRefId = 0, $payeeRefId = 0, $costAmount, $bankCharge = 0, $addtParams = '', $disAmount = 0)
	{
		$accTransDetArr		 = [];
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_ONLINEPAYMENT, $bankRefId, $bankLedgerID, ($costAmount + $bankCharge), $addtParams);
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $payeeRefId, Accounting::LI_PARTNER, (-1 * ($costAmount + $bankCharge)), $addtParams);
		$this->add($accTransDetArr, $this->act_date, ($costAmount + $bankCharge), $this->act_ref_id, $this->act_type, $addtParams);
		if ($bankCharge > 0)
		{
			$accTransDetArr1	 = [];
			$accTransDetArr1[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $payeeRefId, Accounting::LI_PARTNER, $bankCharge, "Bank charge deducted");
			$accTransDetArr1[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_ONLINEPAYMENT, $bankRefId, Accounting::LI_BANKCHARGE, (-1 * $bankCharge), "Bank charge deducted");
			$this->add($accTransDetArr1, $this->act_date, $bankCharge, $payeeRefId, Accounting::AT_PARTNER, $addtParams);
		}
		if ($disAmount > 0)
		{
			$accTransDetArr2	 = [];
			$accTransDetArr2[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $payeeRefId, Accounting::LI_PARTNER, $costAmount);
			$accTransDetArr2[]	 = AccountTransDetails::model()->initializeParams(null, null, Accounting::LI_DISCOUNT, $disAmount, "Discount Price");
			$accTransDetArr2[]	 = AccountTransDetails::model()->initializeParams($this->act_type, $this->act_ref_id, Accounting::LI_GIFTCARD, -1 * ($costAmount + $disAmount), "Gift Card");
			$this->add($accTransDetArr2, $this->act_date, ($costAmount + $disAmount), $payeeRefId, Accounting::AT_PARTNER, $addtParams);
		}
	}

	public function AddGiftCardEntry($bankLedgerID, $payeeLedgerID, $bankRefId = 0, $payeeRefId = 0, $costAmount, $bankCharge = 0, $addtParams = '')
	{
		if ($disAmount > 0)
		{
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $payeeRefId, $payeeLedgerID, $costAmount);
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(null, null, Accounting::LI_DISCOUNT, $disAmount, "Discount Price");
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(null, null, Accounting::LI_GIFTCARD, (-1 * $this->act_amount), "Gift Card");
		}
		return $this->add($accTransDetArr, $this->act_date, $this->act_amount, $this->act_ref_id, $this->act_type, $addtParams);
	}

	public function AddEntry($bankLedgerID, $payeeLedgerID, $bankRefId = 0, $payeeRefId = 0, $addtParams = '', $accType = 0, $userInfo, $successRemarks = null)
	{
		$accTransDetArr		 = [];
		($accType == 0) ? $this->act_type : $accType;
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams($accType, $bankRefId, $bankLedgerID, $this->act_amount, $addtParams, '', '', $successRemarks);
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams($this->act_type, $payeeRefId, $payeeLedgerID, (-1 * $this->act_amount));

		return $this->add($accTransDetArr, $this->act_date, $this->act_amount, $this->act_ref_id, $this->act_type, $this->act_remarks);
	}

	public function AddVendorPurchaseTrip($purchaseAmount, $vendorCollected, $tripId, $bkgId, $vendorId, $date = '', Userinfo $userInfo = null, $tripstatus)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$success = false;
		$trans	 = DBUtil::beginTransaction();
		try
		{
			$this->removeTripPurchaseAmount($tripId, $vendorId);
			Logger::trace("remove trip and tds Amount" . $bkgId . "tripid" . $tripId);
			$accountTrans = AccountTransactions::getTripPurchase($tripId);
			if (count($accountTrans) == 0)
			{
				$datetime			 = ($date != '') ? $date : new CDbExpression('NOW()');
				$remarks			 = "Trip purchased";
				$accTransDetArr		 = [];
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_TRIP, $tripId, Accounting::LI_TRIP, $purchaseAmount, $remarks);
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, (-1 * $purchaseAmount));
				$this->add($accTransDetArr, $datetime, $purchaseAmount, $tripId, Accounting::AT_TRIP, $remarks, UserInfo::model());
				Logger::trace("add trip Amount" . $bkgId);
				$this->AddVendorTDS($purchaseAmount, $bkgId, $tripId, $vendorId, $userInfo, $datetime);
				Logger::trace("add tds Amount" . $bkgId . "tripid" . $tripId);
				// Booking Log
				$eventid			 = 0;
				$desc				 = "Trip purchased Rs. $purchaseAmount";
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid);
				DBUtil::commitTransaction($trans);
				$success			 = true;
				Logger::trace("add trip and tds Amount" . $bkgId . "tripid" . $tripId);
			}
		}
		catch (Exception $e)
		{
			Logger::create("Failed to add Trip Amount by operator ID: $vendorId ({$e->getMessage()})", CLogger::LEVEL_ERROR);
			DBUtil::rollbackTransaction($trans);
			// Booking Log
			$eventid = BookingLog::ACCOUNT_REMARKS;
			$desc	 = "Failed to add Trip Amount by operator ID: $vendorId ({$e->getMessage()})";
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid);
			Logger::trace($desc);
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $success;
	}

	public function AddVendorTDS($purchaseAmount, $bkgId, $tripId, $vendorId, $userInfo, $datetime)
	{
		$tds				 = round($purchaseAmount * 0.01);
		$remarks			 = "TDS deducted against trip purchased ({$tripId})";
		$accTransDetArr		 = [];
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_TRIP, $tripId, Accounting::LI_TDS, (-1 * $tds), $remarks);
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, $tds);
		$this->add($accTransDetArr, $datetime, $tds, $tripId, Accounting::AT_TRIP, $remarks, UserInfo::model());

		// Booking Log
		$eventid = 0;
		BookingLog::model()->createLog($bkgId, $remarks, $userInfo, $eventid);
	}

	public function AddVendorCollection($purchaseAmount, $vendorCollected, $tripId, $bkgId, $vendorId, $date = '', Userinfo $userInfo = null, $tripstatus)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$success = false;
		$trans	 = DBUtil::beginTransaction();
		try
		{
			$accountTrans = AccountTransDetails::model()->revertVendorCollected($tripId, $bkgId, Accounting::LI_TRIP, Accounting::LI_OPERATOR);
			if ($vendorCollected != 0 && $accountTrans == true)
			{
				$datetime			 = ($date != '') ? $date : new CDbExpression('NOW()');
				$remarks			 = "Amount collected by operator";
				$accTransDetArr		 = [];
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, $vendorCollected, $remarks);
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_BOOKING, (-1 * $vendorCollected));
				$this->add($accTransDetArr, $datetime, $vendorCollected, $bkgId, Accounting::AT_BOOKING, $remarks);
				// Booking Log
				$eventid			 = 0;
				$desc				 = "Amount collected by operator . $vendorCollected";
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid);
				DBUtil::commitTransaction($trans);
				$success			 = true;
				Logger::trace("add Amount collected" . $bkgId);
			}
		}
		catch (Exception $e)
		{
			Logger::trace("Failed to add Amount collected by operator ID: $vendorId ({$e->getMessage()})");
			DBUtil::rollbackTransaction($trans);
			// Booking Log
			$eventid = BookingLog::ACCOUNT_REMARKS;
			$desc	 = "Failed to add Amount collected by operator ID: $vendorId ({$e->getMessage()})";
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid);
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $success;
	}

	public function AddCommission($date, $bkgID, $partnerId, $amount, UserInfo $userInfo = null)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$success = false;
		$trans	 = DBUtil::beginTransaction();
		try
		{
			// Remove Commission if any already added.
			AccountTransactions::model()->removeBookingCommission($bkgID);
			Logger::trace("remove booking commission bookingId" . $bkgID);
			if ($amount == 0)
				goto commit;
			// Booking A/C to Commission A/C
			$remarks			 = "Partner commission credited";
			$accTransDetArr		 = [];
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgID, Accounting::LI_BOOKING, $amount, $remarks);
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $partnerId, Accounting::LI_COMMISSION, (-1 * $amount), $remarks);
			$this->add($accTransDetArr, $date, $amount, $bkgID, Accounting::AT_BOOKING, $remarks, $userInfo);
			Logger::trace("1 Add booking commission bookingId" . $bkgID);
			// Commission A/C to Partner A/C old 
			// commission A/c to Wallet A/C new logic 
			$remarks			 = "Commission credited to partner wallet";
			$accTransDetArr		 = [];
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgID, Accounting::LI_COMMISSION, $amount, $remarks);
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $partnerId, Accounting::LI_PARTNERWALLET, (-1 * $amount), $remarks);
			$this->add($accTransDetArr, $date, $amount, $partnerId, Accounting::AT_PARTNER, $remarks, $userInfo);
			Logger::trace("2 Add booking commission bookingId" . $bkgID);
			// Booking Log
			$eventid			 = BookingLog::AGENT_COMMISSION_APPLIED;
			$desc				 = "Partner commission Rs. $amount credited";
			BookingLog::model()->createLog($bkgID, $desc, $userInfo, $eventid);
			commit:
			DBUtil::commitTransaction($trans);
			$success			 = true;
		}
		catch (Exception $e)
		{
			Logger::create("Failed to add commission for Booking ID: $bkgID ({$e->getMessage()})", CLogger::LEVEL_ERROR);
			DBUtil::rollbackTransaction($trans);
			// Booking Log
			$eventid = BookingLog::ACCOUNT_REMARKS;
			$desc	 = "Failed to add commission. ({$e->getMessage()})";
			BookingLog::model()->createLog($bkgID, $desc, $userInfo, $eventid);
			Logger::trace("Failed to add commission bookingId" . $bkgID . $e->getMessage());
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $success;
	}

	public function refundBooking($date, $amount, $bkgId, $ptpId, $remarks = null, $apgModel = null, UserInfo $userInfo = null)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$transaction = DBUtil::beginTransaction();
		$success	 = false;
		$ptpName	 = PaymentGateway::getPaymentTypeName($ptpId);
		Logger::trace("refund booking: ptpname" . $ptpName);
		try
		{
			/* @var $apgModel PaymentGateway */
			$adtModels	 = [];
			$adtModels[] = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_BOOKING, $amount, $remarks);
			$refId		 = null;
			if ($apgModel != null)
			{
				$params					 = [];
				$params['blg_ref_id']	 = $apgModel->apg_id;
				$refId					 = $apgModel->apg_id;
				if ($remarks == null)
				{
					$remarks = $apgModel->apg_remarks;
				}
			}
			$bkgModel	 = Booking::model()->findByPk($bkgId);
			$ledgerId	 = PaymentType::getLedgerId($ptpId);
			$refType	 = Accounting::AT_ONLINEPAYMENT;
			if ($ledgerId == Accounting::LI_WALLET)
			{
				$refType = Accounting::AT_USER;
				$refId	 = $bkgModel->bkgUserInfo->bkg_user_id;
			}
			if ($ptpId == PaymentType::TYPE_AGENT_CORP_CREDIT && $bkgModel->bkg_agent_id > 0)
			{
				$refId			 = $bkgModel->bkg_agent_id;
				$refType		 = Accounting::AT_PARTNER;
				$updateStatus	 = $this::UpdateInactiveStatus($bkgId);
				//Logger::trace("refund booking: update inactive status" . $updateStatus);
			}
			$adtModels[] = AccountTransDetails::model()->initializeParams($refType, $refId, $ledgerId, -1 * $amount, $remarks);
			$res		 = $this->add($adtModels, $date, $amount, $bkgId, Accounting::AT_BOOKING, $remarks);
			if ($res)
			{
				Logger::trace("refund Booking step 1");
				$params					 = [];
				$params['blg_ref_id']	 = $res->act_id;
				$success				 = true;
				if ($apgModel != null)
				{
					$desc = "Refund added to Booking ({$apgModel->getPaymentType()} - {$apgModel->apg_code}) amount $amount";
				}
				else if ($ptpId == PaymentType::TYPE_GOZO_COINS)
				{
					$desc = "Gozo coins added to Booking amount $amount";
				}
				if ($ptpId == PaymentType::TYPE_WALLET)
				{
					Logger::trace("refund Booking step 2");
					$success = UserWallet::add($refId, $amount);
					Logger::trace("refund Booking staep 3");
					if (!$success)
					{
						$desc = "Failed to refund in wallet";
						Logger::trace("refund Booking step 4: " . $desc);
						throw new Exception($desc);
					}
					$desc = "Refund added to user wallet Rs.$amount";
				}
				else
				{
					$desc = "Refund added to Booking amount $amount";
				}
				Logger::trace("refund Booking step 5: " . $desc);
				$success = $bkgModel->addRefund($amount, $desc, $userInfo, $params);
				Logger::trace("refund Booking step 6" . $success);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			Logger::exception($e);
			DBUtil::rollbackTransaction($transaction);
			if ($apgModel != null)
			{
				$desc = "Failed to add refund ({$apgModel->getPaymentType()} - {$apgModel->apg_code}) amount $amount ({$e->getMessage()})";
			}
			else
			{
				$desc = "Failed to add refund ({$e->getMessage()})";
			}
			Logger::trace("Failed to add refund " . $e->getMessage());
			throw new Exception($desc, $e->getCode());
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $success;
	}

	//obsolute
	public function addCoinsToPartner($date, $partnerId, $bkgId, $amount, $remarks, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			/* @var $apgModel PaymentGateway */
			$adtModels	 = [];
			$adtModels[] = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_PARTNERCOINS, -1 * $amount, $remarks);
			$adtModels[] = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $partnerId, Accounting::LI_PARTNER, $amount, $remarks);
			$success	 = $this->add($adtModels, $date, $amount, $partnerId, Accounting::AT_PARTNER, $remarks);
		}
		catch (Exception $e)
		{
			Logger::create("Failed to credit coins at partner account", CLogger::LEVEL_ERROR);
		}
		return $success;
	}

	//obsolute
	public function addCoinsToBooking($date, $partnerId, $bkgId, $amount, $remarks, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			//To booking
			$adtModels	 = [];
			$adtModels[] = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $partnerId, Accounting::LI_PARTNERCOINS, -1 * $amount, $remarks);
			$adtModels[] = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_BOOKING, $amount, $remarks);
			$success	 = $this->add($adtModels, $date, $amount, $bkgId, Accounting::AT_BOOKING, $remarks);
		}
		catch (Exception $e)
		{
			Logger::create("Failed to credit coins at booking account", CLogger::LEVEL_ERROR);
		}
		return $success;
	}

	/**
	 * 	 
	 * @param string $date
	 * @param int $partnerId
	 * @param int $bkgId
	 * @param int $amount
	 * @param string $remarks
	 * @param UserInfo $userInfo
	 * @return boolean
	 * @throws Exception
	 */
	public function addWalletToPartner($date, $partnerId, $bkgId, $amount, $remarks, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			$adtModels	 = [];
			$adtModels[] = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_PARTNERWALLET, -1 * $amount, $remarks);
			$adtModels[] = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $partnerId, Accounting::LI_PARTNER, $amount, $remarks);
			$success	 = $this->add($adtModels, $date, $amount, $partnerId, Accounting::AT_PARTNER, $remarks);
		}
		catch (Exception $e)
		{
			Logger::create("Failed to credit wallet at partner account", CLogger::LEVEL_ERROR);
		}
		return $success;
	}

	/**
	 * 	 
	 * @param string $date
	 * @param int $partnerId
	 * @param int $bkgId
	 * @param int $amount
	 * @param string $remarks
	 * @param UserInfo $userInfo
	 * @return boolean
	 * @throws Exception
	 */
	public function addWalletToBooking($date, $partnerId, $bkgId, $amount, $remarks, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			//To booking
			$adtModels	 = [];
			$adtModels[] = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $partnerId, Accounting::LI_PARTNERWALLET, $amount, $remarks);
			$adtModels[] = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_BOOKING, -1 * $amount, $remarks);
			$success	 = $this->add($adtModels, $date, $amount, $bkgId, Accounting::AT_BOOKING, $remarks);
		}
		catch (Exception $e)
		{
			Logger::create("Failed to credit wallet at booking account", CLogger::LEVEL_ERROR);
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param string $date
	 * @param integer $cancelCharge
	 * @param UserInfo $userInfo
	 * @return type
	 * @throws Exception
	 */
	public static function AddCancellationCharge($bkgId, $date, $cancelCharge, UserInfo $userInfo = null)
	{
		$success = self::removeCancellationCharge($bkgId);
		if (!$success)
		{
			throw new Exception("Unable to remove old cancellation charge");
		}

		if ($cancelCharge == 0)
		{
			goto end;
		}

		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_CANCELLATION, -1 * $cancelCharge, "Cancellation charged");
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_BOOKING, $cancelCharge, "Cancellation charged");
		$success			 = AccountTransactions::model()->add($accTransDetArr, $date, $cancelCharge, $bkgId, Accounting::AT_BOOKING, "Cancellation charged");
		//Booking log

		$remarks = "Cancelled charged Rs.$cancelCharge added.";
		BookingLog::model()->createLog($bkgId, $remarks, $userInfo, BookingLog::BOOKING_CANCELLED);
		end:
		return $success;
	}

	public function addRefundTransaction($arrRefundedPGModels = [], $cancelCharge = 0, $arrRefundedADTModels = [], $bookingId)
	{
		if ($arrRefundedPGModels != [] || $arrRefundedADTModels != [])
		{
			$totRefunded	 = 0;
			$accTransDetArr	 = [];
			foreach ($arrRefundedPGModels as $apgModel)
			{
				$totRefunded		 = $totRefunded + (-1 * $apgModel->apg_amount);
				$accTransDetail		 = AccountTransDetails::model()->findByAttributes(['adt_trans_ref_id' => $apgModel->apg_ref_id, 'adt_type' => 4]);
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_ONLINEPAYMENT, $apgModel->apg_id, $apgModel->apg_ledger_id, $apgModel->apg_amount, $apgModel->apg_remarks, $accTransDetail->adt_id);
			}

			foreach ($arrRefundedADTModels as $adtModel)
			{
				$totRefunded		 = $totRefunded + (-1 * $adtModel->adt_amount);
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams($adtModel->adt_type, $adtModel->adt_trans_ref_id, $adtModel->adt_ledger_id, $adtModel->adt_amount, $adtModel->adt_remarks, $adtModel->adt_ref_id);
			}

			if ($cancelCharge > 0)
			{
				$accTransDetArr[] = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bookingId, Accounting::LI_CANCELLATION, -1 * $cancelCharge, "Cancellation charged");
			}
			$accTransDetArr[] = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bookingId, Accounting::LI_BOOKING, ($totRefunded + $cancelCharge), "Refund Success");

			return $this->add($accTransDetArr, $apgModel->apg_date, ($totRefunded + $cancelCharge), $bookingId, Accounting::AT_BOOKING, "Refund Success");
		}
		return false;
	}

	public function paymentCreditsUsed($bkgid, $ptpid, $amount, $platform = 1, $oldApp = false, $editAccount = false, $remarks = NULL)
	{

		$sucess			 = true;
		$bkgmodel		 = Booking::model()->findByPk($bkgid);
		$preCreditsUsed	 = $bkgmodel->bkgInvoice->bkg_credits_used;
		//dsf
		$bookModel		 = clone $bkgmodel;
		$percent30ofAmt	 = round($bookModel->bkgInvoice->bkg_total_amount * 0.3);
		if ($amount > $bookModel->bkgInvoice->bkg_total_amount)
		{
			$amount = $bookModel->bkgInvoice->bkg_total_amount;
		}
		$amountcredit	 = $amount;
		//sfsf
		$credits		 = ($bkgmodel->bkgInvoice->bkg_credits_used > 0) ? $bkgmodel->bkgInvoice->bkg_credits_used : $amount;
		//$credits                                         = ($bkgmodel->bkgInvoice->bkg_credits_used > 0) ? ($bkgmodel->bkgInvoice->bkg_credits_used + $amount) : $amount;

		$bkgmodel->bkgInvoice->bkg_credits_used			 = $credits;
		$bkgmodel->bkgInvoice->bkg_due_amount			 = $bkgmodel->bkgInvoice->bkg_total_amount - $bkgmodel->bkgInvoice->getTotalPayment();
		$bkgmodel->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
		$usePromo										 = ($bkgmodel->bkgInvoice->bkg_promo1_id == '' || $bkgmodel->bkgInvoice->bkg_promo1_id == 0);
		if ($oldApp)
		{
			$usePromo = true;
		}
		Logger::create("api/booking/ebs_response 3");
		$totalCredits = UserCredits::getApplicableCredits($bkgmodel->bkgUserInfo->bkg_user_id, $bookModel->bkgInvoice->bkg_base_amount, $usePromo, $bookModel->bkg_from_city_id, $bookModel->bkg_to_city_id);

		$userCredits = UserCredits::model()->getMaxCreditsByUser($bkgmodel->bkgUserInfo->bkg_user_id, $bookModel->bkgInvoice->bkg_base_amount, $usePromo);
		if ($amount <= $totalCredits['credits'] && $amount != 0 && count($userCredits) > 0)
		{
			Logger::create("api/booking/ebs_response 4");
			$totCreditsUsed = 0;

			foreach ($userCredits as $value)
			{
				if ($amount != 0)
				{

					$transAmount = $value->CreditVal;
					if ($amount <= $value->CreditVal)
					{
						$transAmount = $amount;
					}
					$totCreditsUsed = $totCreditsUsed + $transAmount;

					$userCredits = UserCredits::model()->findByPk($value->ucr_id);
					if ($amount <= $value->CreditVal)
					{
						$userCredits->ucr_used = $userCredits->ucr_used + $amount;
					}
					else
					{
						$userCredits->ucr_used = $userCredits->ucr_used + $value->CreditVal;
					}
					$sucess = $userCredits->update();
					if ($amount <= $value->CreditVal)
					{
						$amount = 0;
					}
					else
					{
						$amount = $amount - $value->CreditVal;
					}
				}
			}
			if ($sucess)
			{
				Logger::create("api/booking/ebs_response 5");
				$accTransModel				 = new AccountTransactions();
				$accTransModel->act_amount	 = $totCreditsUsed;
				$accTransModel->act_date	 = new CDbExpression('NOW()');
				$accTransModel->act_type	 = Accounting::AT_BOOKING;
				$accTransModel->act_ref_id	 = $bkgid;
				$accTransModel->act_remarks	 = "Gozocoins redeemd (confirmed)";
				if ($remarks != NULL)
				{
					$accTransModel->act_remarks = "Gozocoins redeemed manually. " . $remarks;
				}
				$accTransModel->AddReceipt(Accounting::LI_GOZOCOINS, Accounting::LI_BOOKING, NULL, $bkgid, '{"TRANSACTION_MODE":2,"DESCRIPTION":"Credits used by user successfully","STATUS":"SUCCESS"}', NULL);

				$userInfo				 = UserInfo::getInstance();
				$eventid				 = BookingLog::BOOKING_PROMO;
				$params['blg_ref_id']	 = BookingLog::REF_PROMO_GOZOCOINS_APPLIED;
				$userId					 = $bkgmodel->bkgUserInfo->bkg_user_id;
				if ($editAccount)
				{
					$userId = Yii::app()->user->getId();
				}
				BookingLog::model()->createLog($bkgmodel->bkg_id, "Gozo coins redeemed for ₹" . $amountcredit . " in booking credit.", $userInfo, $eventid, false, $params);

				if ($amountcredit >= $percent30ofAmt)
				{
					$bkgmodel->bkgInvoice->calculateConvenienceFee(0);
					$bkgmodel->bkgInvoice->calculateTotal();
					$bkgmodel->bkgInvoice->calculateVendorAmount();
				}

//reduce vendor amount
				$cabmodel = $bkgmodel->getBookingCabModel();
				if ($cabmodel != '' && $cabmodel->bcb_trip_type != 1)
				{
					if ($bkgmodel->bkgInvoice->bkg_vendor_amount != '' && ($bkgmodel->bkg_status == 1 || $bkgmodel->bkg_status == 15 || $bkgmodel->bkg_status == 2) && ($preCreditsUsed == 0 || $preCreditsUsed == '') && ($bkgmodel->bkgInvoice->bkg_advance_amount == 0 || $bkgmodel->bkgInvoice->bkg_advance_amount == ''))
					{
						$bkgmodel->bkgInvoice->bkg_vendor_amount = round($bkgmodel->bkgInvoice->bkg_vendor_amount * 0.97);
						$bkgmodel->bkgInvoice->calculateVendorAmount();
						$cabmodel->bcb_vendor_amount			 = $bkgmodel->bkgInvoice->bkg_vendor_amount;
						$cabmodel->bcb_bcb_id1					 = $bkgmodel->bkg_id;
						$cabmodel->save();
					}
				}
				$sucess = $bkgmodel->bkgInvoice->update();
//		$sucess  = $bkgmodel->update();
			}
		}
		return $sucess;
	}

	public static function advanceReceived($date, $ptpId, $ptpRefId, $amount, $refType = Accounting::AT_BOOKING, $refId, $remarks = NULL)
	{
		$accTransModel				 = new AccountTransactions();
		$accTransModel->act_amount	 = $amount;
		$accTransModel->act_date	 = ($date == '') ? new CDbExpression('NOW()') : $date;
		$accTransModel->act_type	 = $refType;
		$accTransModel->act_ref_id	 = $refId;
		$accTransModel->act_remarks	 = $remarks;

		switch ($refType)
		{
			case Accounting::AT_PARTNER:
				$ledgerId	 = Accounting::LI_PARTNER;
				break;
			case Accounting::AT_OPERATOR:
				$ledgerId	 = Accounting::LI_OPERATOR;
				break;
			case Accounting::AT_BOOKING:
				$ledgerId	 = Accounting::LI_BOOKING;
				break;
			case Accounting::AT_VOUCHER:
				$ledgerId	 = Accounting::LI_VOUCHER;
				break;
			default:
				throw new Exception("Advance Payment not accepted for selected type");
		}


		$model = $accTransModel->AddReceipt(PaymentType::getLedgerId($ptpId), $ledgerId, $ptpRefId, $refId, $remarks, $refType);
		return $model;
	}

	/*
	 * @deprecated
	 */

	public function PartnerCoinsUsed($partnerId, $amount, $date, $refId, $refType, $remarks = null, $userInfo)
	{
		$accTransModel				 = new AccountTransactions();
		$accTransModel->act_amount	 = $amount;
		$accTransModel->act_date	 = ($date == '') ? new CDbExpression('NOW()') : $date;
		$accTransModel->act_type	 = Accounting::AT_PARTNER;
		$accTransModel->act_ref_id	 = $partnerId;
		$accTransModel->act_remarks	 = $remarks;
		$actModel					 = $accTransModel->AddReceipt(Accounting::LI_PARTNER, Accounting::LI_PARTNERWALLET, $partnerId, $refId, $remarks, Accounting::AT_PARTNER, $userInfo, 0, $refType);
		return $actModel;
	}

	public function getByCode($code)
	{
		if ($code)
		{
			$criteria	 = new CDbCriteria();
			$criteria->compare('apg_code', $code);
			$transModel	 = $this->find($criteria);
			return $transModel;
		}
		return false;
	}

	public function getModeList($mode = 0)
	{
		$modeList = $this->modeList;
		if ($mode > 0)
		{
			return $modeList[$mode];
		}
		return $modeList;
	}

	public function getOperatorList($mode = 0)
	{
		$modeList = $this->operatorList;
		if ($mode > 0)
		{
			return $modeList[$mode];
		}
		return $modeList;
	}

	public static function removeBookingCommission($bkgId)
	{
		$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM account_trans_details atd 
				INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id AND atd.adt_active=1 AND act.act_active=1 AND atd.adt_ledger_id=35 AND atd.adt_type IN (3) 
				INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id AND atd1.adt_ledger_id IN (13) AND atd1.adt_trans_ref_id=:bkgId
				WHERE atd1.adt_active=1";
		$actIds	 = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
		if ($actIds != null && $actIds != '')
		{
			AccountTransactions::remove($actIds);
		}

		$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM account_trans_details atd 
				INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id AND atd.adt_active=1 AND act.act_active=1 AND atd.adt_ledger_id=35 AND atd.adt_type IN (1)  AND atd.adt_trans_ref_id=:bkgId
				INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id AND atd1.adt_ledger_id IN (49)
				WHERE atd1.adt_active=1";
		$actIds	 = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
		if ($actIds != null && $actIds != '')
		{
			AccountTransactions::remove($actIds);
		}
	}

	public static function removeBookingPartnerCoinUsed($bkgId)
	{
		$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM  account_trans_details atd
					INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id AND atd.adt_ledger_id = 49 AND atd.adt_type IN(1)
					INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id AND atd1.adt_ledger_id IN(13) AND atd1.adt_trans_ref_id = :bkgId
					WHERE atd1.adt_active = 1 AND atd.adt_active = 1 AND act.act_active = 1";
		$actIds	 = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
		if ($actIds != null && $actIds != '')
		{
			$rows = AccountTransactions::remove($actIds);
		}
		return $rows;
	}

	public static function removeCancelationCharge($bkgId)
	{
		$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM  account_trans_details atd
		INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id AND atd.adt_active=1 AND act.act_active=1 AND atd.adt_ledger_id=25 AND atd.adt_type = 1
		INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id AND atd1.adt_active=1 AND atd1.adt_type=1 AND atd1.adt_ledger_id=13
		WHERE atd.adt_trans_ref_id=:bkgId AND atd1.adt_trans_ref_id=:bkgId AND atd.adt_id IS NOT NULL AND atd1.adt_id IS NOT NULL";
		$actIds	 = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
		if ($actIds != null && $actIds != '')
		{
			$rows = AccountTransactions::remove($actIds);
		}
		return $rows;
	}

	public static function removeRefund($bkgId)
	{
		$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM  account_trans_details atd
					INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id  AND atd.adt_active=1 AND act.act_active=1 AND atd.adt_ledger_id=49 AND atd.adt_amount < 0
					INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id  AND atd1.adt_active=1 AND atd1.adt_type=1 AND atd1.adt_ledger_id=13 AND atd1.adt_amount > 0
        INNER JOIN booking bkg ON bkg.bkg_id = atd1.adt_trans_ref_id 
        INNER JOIN booking_invoice bv On bv.biv_bkg_id = atd1.adt_trans_ref_id 
					WHERE atd1.adt_trans_ref_id=:bkgId AND atd.adt_id IS NOT NULL AND atd1.adt_id IS NOT NULL";
		$actIds	 = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
		if ($actIds != null && $actIds != '')
		{
			$rows = AccountTransactions::remove($actIds);
			if ($rows)
			{
				$sql1 = "UPDATE booking_invoice SET bkg_due_amount = (bkg_due_amount - bkg_refund_amount),bkg_refund_amount = 0 WHERE biv_bkg_id=:bkgId";
				DBUtil::execute($sql1, ["bkgId" => $bkgId]);
			}
		}
		return $rows;
	}

	public static function removeRefundB2C($bkgId)
	{
		$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM  account_trans_details atd
					INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id  AND atd.adt_active=1 AND act.act_active=1 AND atd.adt_ledger_id=47 AND atd.adt_amount < 0 AND atd.adt_type=7
					INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id  AND atd1.adt_active=1 AND atd1.adt_type=1 AND atd1.adt_ledger_id=13 AND atd1.adt_amount > 0
        INNER JOIN booking bkg ON bkg.bkg_id = atd1.adt_trans_ref_id 
        INNER JOIN booking_invoice bv On bv.biv_bkg_id = atd1.adt_trans_ref_id 
					WHERE atd1.adt_trans_ref_id=:bkgId AND atd.adt_id IS NOT NULL AND atd1.adt_id IS NOT NULL";
		$actIds	 = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
		if ($actIds != null && $actIds != '')
		{
			$rows = AccountTransactions::remove($actIds);
			if ($rows)
			{
				$sql1 = "UPDATE booking_invoice SET bkg_due_amount = (bkg_due_amount - bkg_refund_amount),bkg_refund_amount = 0 WHERE biv_bkg_id=:bkgId";
				DBUtil::execute($sql1, ["bkgId" => $bkgId]);
			}
		}
		return $rows;
	}

	public static function removeTripPurchaseAmount($tripid, $vendorId)
	{

		$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM account_trans_details atd
				INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id AND atd.adt_active=1 AND act.act_active=1 AND atd.adt_ledger_id IN(22,37,55) AND atd.adt_type IN (5)
				INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id AND atd1.adt_active=1 AND (atd1.adt_ledger_id=14)  AND atd.adt_trans_ref_id=:tripid
			    WHERE 1 ";
		$actIds	 = DBUtil::queryScalar($sql, null, ["tripid" => $tripid]);
		if ($actIds != null && $actIds != '')
		{
			$rows = AccountTransactions::remove($actIds);
		}
		return $rows;
	}

	public function purchaseTrip($date, $tripId, $vendorId, $amount, $userInfo)
	{
		$removevendoramt			 = $this->removeTripPurchaseAmount($tripId, $vendorId);
		$accTransModel				 = new AccountTransactions();
		$accTransModel->act_amount	 = $amount;
		$accTransModel->act_date	 = $date;
		$accTransModel->act_type	 = Accounting::AT_TRIP;
		$accTransModel->act_ref_id	 = $tripId;
		$accTransModel->act_remarks	 = "Trip Purchased";
		$accTransModel->AddReceipt(Accounting::LI_TRIP, Accounting::LI_OPERATOR, $tripId, $vendorId, $accTransModel->act_remarks, NULL);
		$accTransModel->AddVendorTDS($amount, $tripId, $tripId, $vendorId, $userInfo, $date);
	}

	public function AddDriverBonus($bonusAmount, $bkgId, $driverId, UserInfo $userinfo = null, $smsSent = 1, $remarks = '')
	{
		$success = false;
		$trans	 = DBUtil::beginTransaction();
		try
		{
			if ($remarks == '')
			{
				$remarks = "Bonus Added";
			}
			// Driver A/C to Bonus A/C
			$datetime = new CDbExpression('NOW()');

			$accTransDetArr		 = [];
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_BONUS, $bonusAmount, $remarks);
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_DRIVER, $driverId, Accounting::LI_DRIVER, (-1 * $bonusAmount));
			$this->add($accTransDetArr, $datetime, $bonusAmount, $driverId, Accounting::AT_DRIVER, $remarks, UserInfo::model());

			if ($bkgId > 0)
			{
				// Booking A/C to Bonus A/C
				$accTransDetArr		 = [];
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $driverId, Accounting::LI_BONUS, $bonusAmount, $remarks);
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_BOOKING, (-1 * $bonusAmount));
				$this->add($accTransDetArr, $datetime, $bonusAmount, $bkgId, Accounting::AT_BOOKING, $remarks, UserInfo::model());

				// Booking Log
				$eventId = BookingLog::BONUS_DRIVER;
				if ($remarks == '')
				{
					$desc = "Bonus Added Rs. $bonusAmount";
				}
				else
				{
					$desc = $remarks;
				}
				$logStatus = BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId);

				Drivers::notifyReviewBonusToDriver($bkgId, $driverId, $bonusAmount);

//				$response = WhatsappLog::reviewBonusToDriver($driverId, $bonusAmount);
//				if ((!$response || $response['status'] == 3) && $logStatus == true && $smsSent == 1)
//				{
//					$msgCom	 = new smsWrapper();
//					$slgId	 = $msgCom->sentBonusToDriver('91', $driverId, $bonusAmount, $bkgId);
//					if ($slgId > 0)
//					{
//						$eventId				 = BookingLog::SMS_SENT;
//						$desc					 = "Sms sent to driver,Bonus Added Rs. $bonusAmount";
//						$params['blg_ref_id']	 = $slgId;
//						$logbooking				 = BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, false, $params);
//					}
//				}
			}
			$success = DBUtil::commitTransaction($trans);
		}
		catch (Exception $ex)
		{

			Logger::create("Failed to add Bonus Amount,Driver ID: $driverId ({$ex->getMessage()})", CLogger::LEVEL_ERROR);
			DBUtil::rollbackTransaction($trans);
			// Booking Log
			$eventId = BookingLog::ACCOUNT_REMARKS;
			$desc	 = "Failed to add Bonus Amount,Driver ID: $driverId ({$ex->getMessage()})";
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId);
		}
		return $success;
	}

	public function RedeemDriverBonus($bonusAmount, $bkgId, $driverId, USerinfo $userinfo = null, $smsSent = 1)
	{
		$success = false;
		$trans	 = DBUtil::beginTransaction();
		try
		{

			$datetime			 = new CDbExpression('NOW()');
			$remarks			 = "Bonus Refunded";
			$accTransDetArr		 = [];
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_BONUS, (-1 * $bonusAmount), $remarks);
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_DRIVER, $driverId, Accounting::LI_DRIVER, $bonusAmount);
			$this->add($accTransDetArr, $datetime, $bonusAmount, $bkgId, Accounting::AT_BOOKING, $remarks, UserInfo::model());
			// Booking Log
			$eventId			 = BookingLog::BONUS_DRIVER;
			$desc				 = "Bonus Refunded Rs. $bonusAmount";
			$logStatus			 = BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId);
			if ($logStatus == true)
			{
				if ($smsSent == 1)
				{
					$msgCom	 = new smsWrapper();
					$slgId	 = $msgCom->sentBonusToDriver('91', $driverId, $bonusAmount, $bkgId);
					if ($slgId > 0)
					{
						$eventId				 = BookingLog::SMS_SENT;
						$desc					 = "Sms sent to driver,Bonus Refunded Rs. $bonusAmount";
						$params['blg_ref_id']	 = $slgId;
						$logbooking				 = BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, false, $params);
					}
					else
					{
						throw new Exception("There are some errors from the Booking Sms Log.");
					}
				}
				$success = DBUtil::commitTransaction($trans);
			}
			else
			{
				throw new Exception("There are some errors from the Booking Log.");
			}
		}
		catch (Exception $ex)
		{

			Logger::create("Failed to Refund Bonus Amount,Driver ID: $driverId ({$ex->getMessage()})", CLogger::LEVEL_ERROR);
			DBUtil::rollbackTransaction($trans);
			// Booking Log
			$eventId = BookingLog::ACCOUNT_REMARKS;
			$desc	 = "Failed to Refund Bonus Amount,Driver ID: $driverId ({$ex->getMessage()})";
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId);
		}
		return $success;
	}

	public function AddCsvdatatoAccounts($amount, $operatorId, $paymentDate, $remarks, $bankLedger, $paymentTypeId, $accType, $accLedger)
	{
		$success	 = false;
		$userInfo	 = UserInfo::getInstance();
		$userInfo->userId;
		$userInfo->userType;
		//$date2 = '13/12/2018';
		//$csvdate							 = date('Y-m-d', strtotime($date2));
		$csvdate	 = DateTimeFormat::DatePickerToDate($paymentDate);
		$time		 = date('H:i:s');
		$date		 = $csvdate . " " . $time;
		if ($csvdate == '1970-01-01')
		{
			$date = new CDbExpression('NOW()');
		}
		if ($remarks == NULL)
		{
			$remarks = "Payment made(ICICI)";
		}
		$checkDuplicate = PaymentGateway::checkDuplicateVendorPayment($operatorId, $amount);
		if ($checkDuplicate)
		{
			$paymentGateway						 = new PaymentGateway();
			$paymentGateway->apg_acc_trans_type	 = $accType;
			$paymentGateway->apg_trans_ref_id	 = $operatorId;
			$paymentGateway->apg_ptp_id			 = $paymentTypeId;
			$paymentGateway->apg_amount			 = $amount;
			$paymentGateway->apg_remarks		 = $remarks; //"Payment Initiated";
			$paymentGateway->apg_ref_id			 = '';
			$paymentGateway->apg_user_type		 = UserInfo::TYPE_ADMIN;
			$paymentGateway->apg_user_id		 = $userInfo->userId;
			$paymentGateway->apg_status			 = 1;
			$paymentGateway->apg_date			 = new CDbExpression('NOW()');
			$bankLedgerId						 = $bankLedger;

			$paymentGateway = $paymentGateway->payment($bankLedgerId);
			if ($paymentGateway)
			{
				$bankRefId		 = $paymentGateway->apg_id;
				$refType		 = Accounting::AT_ONLINEPAYMENT;
				$actdate		 = ($date == '') ? new CDbExpression('NOW()') : $date;
				$accTransModel	 = self::getInstance(Accounting::AT_OPERATOR, $actdate, $amount, $remarks, $operatorId, $userInfo);
				$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $operatorId, '', $remarks);
				$crTrans		 = AccountTransDetails::getInstance($bankLedger, $refType, $bankRefId, '', $remarks);
				$success		 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_OPERATOR);
			}
		}
		return $success;
	}

	public function addVendorPenaltyByTrip($bcbId, $vendor_id, $penaltyAmount, $remarks, $penaltyType = null, $penaltyModify = null, $penaltyData = null)
	{
		if ($penaltyType != null)
		{
			$dataArray = array('penaltyType' => $penaltyType);
			if ($penaltyData)
			{
				$dataArray = $dataArray + $penaltyData;
			}
			$penaltyData = CJSON::encode($dataArray);
		}
		$refType		 = Accounting::AT_TRIP;
		$act_ref_id		 = $vendor_id;
		$act_type		 = Accounting::AT_OPERATOR;
		$ledger_type	 = Accounting::LI_OPERATOR;
		$accountTrans	 = AccountTransactions::model()->addPenalty($bcbId, $act_ref_id, $refType, $penaltyAmount, $act_type, $ledger_type, $remarks, '', $penaltyData, $penaltyModify);
		return $accountTrans;
	}

	public function addVendorPenalty($bkgRefId, $vendor_id, $penaltyAmount, $remarks, $penaltyIds = null, $penaltyType = null, $penaltyModify = null, $penaltyData = null)
	{
		if ($penaltyType != null)
		{
			$dataArray = array('penaltyType' => $penaltyType);
			if ($penaltyData)
			{
				$dataArray = $dataArray + $penaltyData;
			}
			$penaltyData = CJSON::encode($dataArray);
		}

		$accountTrans	 = "";
		$refType		 = Accounting::AT_BOOKING;
		$act_ref_id		 = $vendor_id;
		$act_type		 = Accounting::AT_OPERATOR;
		$ledger_type	 = Accounting::LI_OPERATOR;
		$bookingID		 = Booking::model()->getCodeById($bkgRefId);
		$model			 = Booking::model()->findByPk($bkgRefId);
		$tripId			 = $model->bkg_bcb_id;
		// check duplicate penalty 
		/* $type =2;
		  $countDuplicatePenalty = self::checkPenaltyStatus($bkgRefId,$penaltyType,$vendor_id,$type);
		  if($countDuplicatePenalty>0)
		  {
		  return $accountTrans;
		  } */
		$accountTrans	 = AccountTransactions::model()->addPenalty($bkgRefId, $act_ref_id, $refType, $penaltyAmount, $act_type, $ledger_type, $remarks, $penaltyIds, $penaltyData, $penaltyModify);
		$payLoadData	 = ['tripId' => $tripId, 'EventCode' => Booking::CODE_VENDOR_BROADCAST];
		#$payLoadData	 = ['EventCode' => Booking::CODE_BROADCAST_IMAGE];
		$message		 = "You have been Penalized for Booking ID-" . $bookingID . ". Reason - " . $remarks;
		$success		 = AppTokens::model()->notifyVendor($vendor_id, $payLoadData, $message, "You have been Penalized");
		//end;
		return $accountTrans;
	}

	/**
	 * calculate and modify vendor penalty amount
	 * @param type $refType
	 * @param type $refId
	 * @param type $vendorId
	 * @param type $penaltyAmount
	 * @param type $remarks
	 * @param type $penaltyType
	 * @param type $penaltyParams
	 * @return type $model
	 */
	public static function penalizeVendor($refType, $refId, $vendorId, $penaltyAmount, $remarks, $penaltyType = null, $penaltyParams = [])
	{
		switch ($refType)
		{
			case 1:
				$refType = Accounting::AT_BOOKING;
				break;
			case 5:
				$refType = Accounting::AT_TRIP;
				break;
			default:
				$refType = Accounting::AT_OTHER;
				break;
		}

		if ($penaltyType != null)
		{
			$penaltyParams['penaltyType']	 = $penaltyType;
			$penaltyData					 = CJSON::encode($penaltyParams);
		}

		$act_ref_id		 = $vendorId;
		$act_type		 = Accounting::AT_OPERATOR;
		$ledger_type	 = Accounting::LI_OPERATOR;
		$accountTrans	 = AccountTransactions::model()->addPenalty($refId, $act_ref_id, $refType, $penaltyAmount, $act_type, $ledger_type, $remarks, '', $penaltyData, 1);
		return $accountTrans;
	}

	/**
	 * this function used for check status that penalty already added or not
	 * @param type $bkgRefId
	 * @param type $penaltyType
	 * @param type $vendor_id
	 * @return type
	 */
	public static function checkPenaltyStatus($bkgRefId, $penaltyType, $vendor_id, $type)
	{
		$sql = "SELECT count(act.act_id) as cnt FROM `account_trans_details` atd
				INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_type =:type AND act.act_ref_id=:vendor_id
				INNER JOIN penalty_rules plt ON plt.plt_event_id = JSON_VALUE(atd.adt_addt_params,'$.penaltyType') AND plt.plt_active=1 AND plt.plt_event_id =:penaltyType
				WHERE atd.adt_trans_ref_id = :booking_id";

		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['vendor_id' => $vendor_id, 'booking_id' => $bkgRefId, 'penaltyType' => $penaltyType, 'type' => $type]);
	}

	public function mapVendorPenalty($reasonId)
	{
		$penaltyArr = [];
		if ($reasonId == 22)
		{
			$penaltyArr[$reasonId] = 5;
		}
		return $penaltyArr;
	}

	public function addPenalty($bkgRefId, $act_ref_id, $refType, $penaltyAmount, $act_type, $ledger_type, $remarks, $penaltyIds = null, $penaltyData = null, $penaltyModify = null)
	{
		//$userInfo					 = UserInfo::model();
		$userInfo					 = UserInfo::getInstance();
		$acctransModel				 = new AccountTransactions();
		$acctransModel->act_date	 = new CDbExpression('NOW()');
		$acctransModel->act_amount	 = -1 * $penaltyAmount;
		$acctransModel->act_ref_id	 = $act_ref_id;
		$acctransModel->act_type	 = $act_type;
		$acctransModel->act_remarks	 = ($penaltyModify != null) ? $remarks : "Penalty Against :" . $remarks;

		$accountTrans = $acctransModel->AddReceipt(Accounting::LI_PENALTY, $ledger_type, $bkgRefId, $act_ref_id, $remarks, $refType, $userInfo, '', '', '', $penaltyData);
		// add in penalty table
		if (!empty($penaltyIds))
		{
			$bookingPenaltyAdd = $this->addBookingPenalty($act_ref_id, $penaltyIds, $penaltyAmount, $bkgRefId);
		}
		// Booking Log
		if ($accountTrans && ($refType == Accounting::AT_BOOKING || $refType == Accounting::AT_TRIP || $penaltyModify != null))
		{
			$eventid			 = BookingLog::VENDOR_PANALIZED;
			$userInfo->userType	 = ($penaltyModify != null) ? UserInfo::TYPE_ADMIN : UserInfo::TYPE_SYSTEM;
			$desc				 = ($penaltyModify != null) ? $remarks : "Penalty Against :" . $remarks . ' Penalty Amount : Rs.' . $penaltyAmount;
			if ($refType == 5)
			{

				$bcbModel	 = BookingCab::model()->findByPk($bkgRefId);
				$bkgIds		 = $bcbModel->bcb_bkg_id1;
				$bkgIdArr	 = explode(",", $bkgIds);
				$bkgRefId	 = $bkgIdArr[0];
			}
			BookingLog::model()->createLog($bkgRefId, $desc, $userInfo, $eventid);
		}
		return $accountTrans;
	}

	public function addBookingPenalty($vendorId, $penaltyIds, $penaltyAmount, $bookingId)
	{
		foreach ($penaltyIds as $id)
		{
			$penaltyModel					 = new BookingPenalties();
			$penaltyModel->vendorId			 = $vendorId;
			$penaltyModel->penaltyReasonId	 = $id;
			$penaltyModel->penaltyAmount	 = (float) $penaltyAmount;
			$penaltyModel->bookingId		 = $bookingId;
			$penaltyModel->date				 = date('Y-m-d h:i:s');
			$penaltyModel->save();
		}
	}

	public function lastRechargeAmount($partnerId, $transaction_id)
	{
		if (!empty($transaction_id))
		{
			$sql		 = "SELECT apg_amount FROM payment_gateway WHERE apg_code=$transaction_id AND apg_active=1 AND apg_status=1 AND apg_trans_ref_id=$partnerId";
			$recordset	 = DBUtil::queryRow($sql);
			return $recordset;
		}
		else
		{
			/* $sql = "SELECT apg_id,apg_code,apg_remarks,apg_user_id,apg_active,apg_status,apg_amount "
			  . "FROM payment_gateway where apg_trans_ref_id=$partnerId AND TIMESTAMPDIFF(SECOND, apg_start_datetime, NOW()) < 5000 "
			  . "ORDER By apg_id DESC LIMIT 1"; */

			$sql = "SELECT apg_id,apg_code,apg_remarks,apg_user_id,apg_active,apg_status,apg_amount "
					. "FROM payment_gateway where apg_trans_ref_id=$partnerId "
					. "ORDER By apg_id DESC LIMIT 1";

			$recordset = DBUtil::queryRow($sql);
			if (!empty($recordset) && $recordset['apg_active'] == 1 && $recordset['apg_status'] == 1)
			{
				$arr = array('transactionid' => $recordset['apg_code'], "lastrecharge" => abs($recordset['apg_amount']), "t_status" => 1, "msg" => "Transaction Successful");
			}
			else
			{
				$arr = array('transactionid' => "", "t_status" => 0, "msg" => "Transaction Failed");
			}
			return $arr;
		}
	}

	public function addAmountGozoPaid($model, $operatorId, $bankRefId, $remarks, $refType, $accType, $ledgerType, $date)
	{
		$accTransModel				 = new AccountTransactions();
		$accTransModel->act_amount	 = -1 * $model->apg_amount;
		$accTransModel->act_date	 = ($date == '') ? new CDbExpression('NOW()') : $model->apg_date;
		$accTransModel->act_type	 = $accType;
		$accTransModel->act_ref_id	 = $operatorId;
		$accTransModel->act_remarks	 = $model->apg_remarks;
		if ($model->apg_ledger_id == Accounting::LI_COMPENSATION)
		{
			$remarks					 = "COMPENSATION - " . $model->apg_remarks;
			$accTransModel->act_amount	 = $model->apg_amount;
		}
		if ($model->apg_ledger_id == Accounting::LI_SECURITY_DEPOSIT)
		{
			$remarks					 = "SECURITY_DEPOSIT - " . $model->apg_remarks;
			$accTransModel->act_amount	 = $model->apg_amount;
		}
		if ($model->apg_ledger_id == Accounting::LI_PROMOTIONS_MARKETING)
		{
			$remarks					 = "PROMOTIONS_MARKETING - " . $model->apg_remarks;
			$accTransModel->act_amount	 = $model->apg_amount;
		}
		if ($model->apg_ledger_id == Accounting::LI_COMMISSION)
		{
			$remarks					 = "COMMISSION - " . $model->apg_remarks;
			$accTransModel->act_amount	 = $model->apg_amount;
		}
		$accTransModel = $accTransModel->AddReceipt($model->apg_ledger_id, $ledgerType, $bankRefId, $operatorId, $remarks, $refType, UserInfo::getInstance());
	}

	public function addAmountGozoReceiver($model, $operatorId, $bankRefId, $remarks, $refType, $accType, $ledgerType, $date, $tripid)
	{
		$accTransModel				 = new AccountTransactions();
		$accTransModel->act_amount	 = $model->apg_amount;
		$accTransModel->act_date	 = ($date == '') ? new CDbExpression('NOW()') : $model->apg_date;
		$accTransModel->act_type	 = $accType;
		$accTransModel->act_remarks	 = $remarks;
		$accTransModel->act_ref_id	 = $operatorId;
		if ($model->apg_ledger_id == Accounting::LI_PENALTY)
		{
			$bankRefId					 = $tripid;
			$remarks					 = "PENALTY - " . $model->apg_remarks;
			$accTransModel->act_amount	 = -1 * $model->apg_amount;
			$accTransModel->act_ref_id	 = $tripid;
			if ($tripid == $operatorId)
			{
				$accTransModel->act_type = $accType;
			}
			else
			{
				$accTransModel->act_type = Accounting::AT_TRIP;
			}
		}
		if ($model->apg_ledger_id == Accounting::LI_SECURITY_DEPOSIT)
		{
			$remarks					 = "SECURITY_DEPOSIT - " . $model->apg_remarks;
			$accTransModel->act_amount	 = -1 * $model->apg_amount;
		}
		$accountTrans = $accTransModel->AddReceipt($model->apg_ledger_id, $ledgerType, $bankRefId, $operatorId, $remarks, $refType, UserInfo::getInstance());
	}

	public function removeOtpPenalty($bkgId, $penaltyAmount)
	{
		if ($bkgId != '' && $penaltyAmount > 0)
		{

			$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM account_trans_details atd
					INNER JOIN account_transactions act
					   ON     atd.adt_trans_id = act.act_id
						  AND atd.adt_active = 1
						  AND act.act_active = 1
						  AND atd.adt_ledger_id = 28 
					INNER JOIN account_trans_details atd1
					   ON     act.act_id = atd1.adt_trans_id
						  AND atd1.adt_active = 1
						  AND atd1.adt_ledger_id = 14      
					WHERE atd.adt_trans_ref_id =:bkgId AND (atd1.adt_amount = -1* :penaltyAmount || atd1.adt_amount = :penaltyAmount) ";
			$actIds	 = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId, 'penaltyAmount' => $penaltyAmount]);
			if ($actIds != null && $actIds != '')
			{
				$rows = AccountTransactions::remove($actIds);
			}
			$userInfo	 = UserInfo::getInstance();
			$eventid	 = BookingLog::VENDOR_PANALIZED;
			$desc		 = 'Previous OTP penalties reverted for this booking.';
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid);
			return $rows;
		}
		return false;
	}

	public function getAmountByPenaltyId($penaltyID, $amount)
	{
		foreach ($penaltyID as $id)
		{
			$result[] = $amount[$id];
			//$priceprod[$g] = $f['price'];			
		}
		return max($result);
	}

	public function AddReceiptForMerge($drLedgerId, $crLedgerID, $drRefId = 0, $crRefId = 0, $addtParams = '', $drAcctType = 0, $userInfo = null, $oldTransId = 0, $crAccType = 0, $successRemarks = '')
	{
		$accTransDetArr		 = [];
		$drAcctType			 = ($drAcctType == 0) ? $this->act_type : $drAcctType;
		$drAcctType			 = (in_array($drLedgerId, Accounting::getOfflineLedgers(false))) ? NULL : $drAcctType;
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams($drAcctType, $drRefId, $drLedgerId, $this->act_amount, $addtParams, 0, $oldTransId, $successRemarks);
		$accType1			 = $type;
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams($accType1, $crRefId, $crLedgerID, (-1 * $this->act_amount), $addtParams, 0, $oldTransId);
		return $this->add($accTransDetArr, $this->act_date, $this->act_amount, $this->act_ref_id, $this->act_type, $this->act_remarks, $userInfo);
	}

	public static function getTotalVendorAmount($vendorId)
	{
		// Getting Merged VendorIds
		$vndIds = Vendors::getVndIdsByRefCode($vendorId);

		if ($vndIds == null || $vndIds == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}

		$sql		 = "SELECT IFNULL(SUM(tripAmt),0) tripAmt 
		FROM (
			SELECT bcb_vendor_amount tripAmt, min(booking.bkg_pickup_date) minpickup,bcb_vendor_id 
			FROM booking_cab 
			INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking.bkg_status IN (5,6,7) 
			WHERE bcb_lock_vendor_payment = 1 AND bcb_trip_status IN(6,7) AND bcb_vendor_id IN ({$vndIds}) 
			GROUP BY booking_cab.bcb_id 
			HAVING minpickup > '2019-04-10 00:00:00' 
		) a ";
		$tripAmount	 = DBUtil::queryScalar($sql);
		return $tripAmount;
	}

	public function getAppliedPenalty($bkgID, $vendorId = 0)
	{
		$cond = "";
		if ($vendorId > 0)
		{
			$cond = " AND atd1.adt_trans_ref_id = {$vendorId} AND atd1.adt_type = 2 ";
		}

		$sql		 = "SELECT SUM(atd1.adt_amount) appliedPenalty from account_trans_details atd 
       INNER JOIN account_transactions act 
          ON     atd.adt_trans_id = act.act_id 
             AND atd.adt_active = 1 
             AND act.act_active = 1 
             AND atd.adt_ledger_id = 28 
       INNER JOIN account_trans_details atd1 
          ON     act.act_id = atd1.adt_trans_id 
             AND atd1.adt_active = 1 
             AND atd1.adt_ledger_id = 14 {$cond} 
             WHERE atd.adt_trans_ref_id = $bkgID";
		$recordset	 = DBUtil::queryScalar($sql);
		return $recordset;
	}

	/**
	 * @param int $bkgID
	 * @param int $pType
	 * @return AccountTransactions|false Will return false if transaction found
	 */
	public static function checkAppliedPenaltyByType($bkgID, $pType)
	{
		$success = true;
		$params	 = array('bkgID' => $bkgID);
		$sql	 = "SELECT 
						atd1.adt_addt_params penaltyData 
					FROM account_trans_details atd
				INNER JOIN account_transactions act
					ON atd.adt_trans_id = act.act_id
						AND atd.adt_active = 1
						AND act.act_active = 1
						AND atd.adt_ledger_id = 28
				INNER JOIN account_trans_details atd1
					ON act.act_id = atd1.adt_trans_id
						AND atd1.adt_active = 1
						AND atd1.adt_ledger_id = 14     
				WHERE 
					atd.adt_trans_ref_id =:bkgID";
		$records = DBUtil::query($sql, DBUtil::MDB(), $params);
		foreach ($records as $row)
		{
			$data		 = CJSON::decode($row['penaltyData']);
			$penaltyType = $data['penaltyType'];
			if ($penaltyType == $pType)
			{
				return $success = false;
			}
		}
		return $success;
	}

	public function removeAppliedPenalty($bkgId)
	{
		if ($bkgId != '')
		{
			$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM account_trans_details atd
       INNER JOIN account_transactions act
          ON     atd.adt_trans_id = act.act_id
             AND atd.adt_active = 1
             AND act.act_active = 1
             AND atd.adt_ledger_id = 28 
       INNER JOIN account_trans_details atd1
          ON     act.act_id = atd1.adt_trans_id
             AND atd1.adt_active = 1
             AND atd1.adt_ledger_id = 14      
						WHERE atd.adt_trans_ref_id =:bkgId ";
			$actIds	 = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
			if ($actIds != null && $actIds != '')
			{
				$rows		 = AccountTransactions::remove($actIds);
				$actIDsArr	 = explode(",", $actIds);
				foreach ($actIDsArr as $actId)
				{
					$accTransModel	 = AccountTransactions::model()->findByPk($actId);
					$amount			 = $accTransModel->act_amount;
					$desc			 = "Penalty Amount : Rs. $amount  removed from vendor account by system";
					BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog::VENDOR_PENALTY_REVERTED);
				}
			}
			return $rows;
		}
		return false;
	}

	public function addOutstandingBalance($drLedgerId, $crLedgerID, $drRefId, $crRefId, $drAcctType, $crAccType, $drRemarks, $crRemarks, $userInfo = null)
	{
		$accTransDetArr		 = [];
		$drAcctType			 = ($drAcctType == 0) ? $this->act_type : $drAcctType;
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams($drAcctType, $drRefId, $drLedgerId, (-1 * $this->act_amount), $drRemarks);
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams($crAccType, $crRefId, $crLedgerID, $this->act_amount, $crRemarks);
		return $this->add($accTransDetArr, $this->act_date, $this->act_amount, $this->act_ref_id, $this->act_type, $drRemarks, $userInfo);
	}

	public function removeDriverBonus($bkgId)
	{
		if ($bkgId != '')
		{
			$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM account_trans_details atd
						INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id AND atd.adt_active = 1  AND act.act_active = 1 AND atd.adt_ledger_id = 41 
						INNER JOIN account_trans_details atd1 ON  act.act_id = atd1.adt_trans_id  AND atd1.adt_active = 1 AND atd1.adt_ledger_id = 40     
	   WHERE atd.adt_trans_ref_id IN($bkgId)";
			$actIds	 = DBUtil::queryScalar($sql);
			if ($actIds != null && $actIds != '')
			{
				$rows1 = AccountTransactions::remove($actIds);
			}

			$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM account_trans_details atd
						INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id AND atd.adt_active = 1  AND act.act_active = 1 AND atd.adt_ledger_id = 41 
						INNER JOIN account_trans_details atd1  ON act.act_id = atd1.adt_trans_id  AND atd1.adt_active = 1 AND atd1.adt_ledger_id = 13     
	   WHERE atd1.adt_trans_ref_id IN($bkgId)";
			$actIds	 = DBUtil::queryScalar($sql);
			if ($actIds != null && $actIds != '')
			{
				$rows2 = AccountTransactions::remove($actIds);
			}

			$userInfo	 = UserInfo::getInstance();
			$eventid	 = BookingLog::BONUS_DRIVER;
			$desc		 = 'Previous Bonus reverted for this booking.';
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid);

			return $rows1;
		}
		return false;
	}

	public function mergeAccountBalance($drLedgerId, $crLedgerID, $drRefId, $crRefId, $drAcctType, $crAccType, $drRemarks, $crRemarks, $userInfo = null, $amount)
	{
		$accTransDetArr		 = [];
		$drAcctType			 = ($drAcctType == 0) ? $this->act_type : $drAcctType;
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams($drAcctType, $drRefId, $drLedgerId, (-1 * $amount), $drRemarks);
		$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams($crAccType, $crRefId, $crLedgerID, $amount, $crRemarks);
		return $this->add($accTransDetArr, $this->act_date, $this->act_amount, $this->act_ref_id, $this->act_type, $drRemarks, $userInfo);
	}

	/**
	 * 	 
	 * @param int $bkgId
	 * @return int
	 */
	public static function getTotalPartnerWallet($bkgId)
	{

		$param	 = ['bkgId' => $bkgId];
		$sql	 = "SELECT  SUM(IFNULL(adt_amount,0)) 
		    FROM `account_trans_details` 
		    INNER JOIN account_transactions ON act_id=adt_trans_id 
		    WHERE act_active=1 AND adt_active=1 AND adt_status=1 AND adt_ledger_id = 49 
		    AND act_type = 1 AND act_ref_id = :bkgId";
		$result	 = DBUtil::queryRow($sql, DBUtil::MDB(), $param);
		return $result;
	}

	/**
	 * 
	 * @param string $pickupDate
	 * @param int $creditUsed
	 * @param int $bkgId
	 * @param int $agentId
	 * @param string $remarks
	 * @param UserInfo $userInfo
	 * @return boolean
	 * @throws Exception
	 */
	public static function usePartnerWallet($pickupDate, $creditUsed, $bkgId, $agentId, $remarks, $userInfo, $overdue = null, $isAdmin = '')
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
			if ($overdue == true)
			{
				goto skipPickupCheck;
			}
			$pickupTimeDiffMinutes = Filter::getTimeDiff($bkgModel->bkg_pickup_date, Filter::getDBDateTime());
			if ($pickupTimeDiffMinutes > 720)
			{
				goto skipIssue;
			}
			skipPickupCheck:
			$balance = self::autoIssuePartnerWallet($agentId, $creditUsed, "Booking ID: {$bkgModel->bkg_booking_id}", $overdue);
			if ($balance === false)
			{
				throw new Exception("Balance not available in Partner Wallet", ReturnSet::ERROR_FAILED);
			}

			skipIssue:
			$accStatus	 = ($isAdmin == 1) ? '' : 1;
			$actModel	 = self::AdvancePartnerWallet($agentId, $bkgId, $creditUsed, $pickupDate, $remarks, UserInfo::model(), $accStatus);
			//$actModel = AccountTransactions::advanceReceived($pickupDate, PaymentType::TYPE_AGENT_CORP_CREDIT, $agentId, $creditUsed, Accounting::AT_BOOKING, $bkgId, $remarks);
			if (!$actModel)
			{
				throw new Exception("Unable to add partner Wallet as advance for Booking ID: {$bkgId}", ReturnSet::ERROR_FAILED);
			}
			if ($actModel->hasErrors())
			{
				throw new Exception(json_encode($actModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			// Booking Log
			$eventid				 = BookingLog::PAYMENT_COMPLETED;
			$params					 = [];
			$params['blg_ref_id']	 = $actModel->act_id;
			$logRemarks				 = "Partner Wallet used - Payment Added - ";
			$ptpString				 = PaymentGateway::model()->getPayment(PaymentType::TYPE_AGENT_CORP_CREDIT);
			$desc					 = " $logRemarks ({$ptpString})";
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid, '', $params);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			throw $e;
		}
		return true;
	}

	/**
	 * 
	 * @param int $partnerId
	 * @param string | CDBExpression $date Description
	 * @return int
	 */
	public static function checkPartnerWalletBalance($partnerId, $date = null)
	{
		if ($date === null)
		{
			$date = DBUtil::getCurrentTime(); //date('Y-m-d H:i:s');
		}
		if (isset($GLOBALS['checkPartnerWalletBalance'][$partnerId][$date]))
		{
			$result = $GLOBALS['checkPartnerWalletBalance'][$partnerId][$date];
			goto result;
		}

		$param	 = ['partnerId' => $partnerId, 'date' => $date];
		$sql	 = "SELECT IFNULL(SUM(atd.adt_amount)* -1,0) as amount 
		    FROM  account_trans_details atd
		    INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id AND atd.adt_ledger_id=49 
			    AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1
		    WHERE atd.adt_trans_ref_id=:partnerId  AND act.act_date<=:date
			GROUP BY atd.adt_trans_ref_id";
//echo $sql; die;
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);

		$GLOBALS['checkPartnerWalletBalance'][$partnerId][$date] = $result;

		result:
		return $result;
	}

	/**
	 * @param int $partnerId
	 * @return int
	 */
	public static function checkPartnerBalance($partnerId)
	{
		if (isset($GLOBALS['checkPartnerBalance'][$partnerId]))
		{
			$balance = $GLOBALS['checkPartnerBalance'][$partnerId];
			goto result;
		}

		$param										 = ['partnerId' => $partnerId];
		$sql										 = "SELECT SUM(adt_amount) totAmount
		    FROM   account_trans_details adt 
		    INNER JOIN agents agt ON adt.adt_trans_ref_id = agt.agt_id
		    INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt_ledger_id=15
		    WHERE adt.adt_trans_ref_id=:partnerId AND adt.adt_status=1 AND act.act_active=1 AND adt.adt_active=1";
		$balance									 = DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
		$GLOBALS['checkPartnerBalance'][$partnerId]	 = $balance;
		result:
		return $balance;
	}

	/**
	 * 
	 * @param int $partnerId
	 * @param int $amount
	 * @param string $date
	 * @param int $refType
	 * @param int $refId
	 * @param string $remarks
	 * @param UserInfo $userInfo
	 * @return AccountTransactions
	 * @throws Exception
	 */
	public static function issuePartnerWallet($partnerId, $amount, $date, $remarks = "", $userInfo = null)
	{
		$accTransModel	 = self::getInstance(Accounting::AT_PARTNER, $date, $amount, $remarks, $partnerId, $userInfo);
		$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_PARTNER, Accounting::AT_PARTNER, $partnerId, '', $remarks);
		$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_PARTNERWALLET, Accounting::AT_PARTNER, $partnerId, '', $remarks);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_PARTNER);

		if (!$status)
		{
			throw new Exception("Unable to add money to partner wallet", ReturnSet::ERROR_FAILED);
		}

		if ($accTransModel->hasErrors())
		{
			throw new Exception(json_encode($accTransModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		return $accTransModel;
	}

	public static function autoIssuePartnerWallet($agentId, $creditUsed, $remarks = '', $overdue = false)
	{
		//$date				 = date('Y-m-d H:i:0', strtotime('+12 hour'));
		$availableBalance	 = Agents::getAvailableLimit($agentId);
		$getBalance			 = PartnerStats::getBalance($agentId);
		$walletBalance		 = $getBalance['pts_wallet_balance'];
		//$walletBalance		 = AccountTransactions::checkPartnerWalletBalance($agentId, $date);
		if ($walletBalance >= $creditUsed)
		{
			goto skipIssue;
		}
		if ($walletBalance < 0 && $agentId == 18190)
		{
			$credits1	 = $creditUsed - $walletBalance;
			$credits	 = max([$credits1, 200000]);
			$remarks	 = " Bookings";
		}
		elseif (($walletBalance < 0 || $walletBalance < $creditUsed) && in_array($agentId, [30228, 35108]))
		{
			$credits1	 = $creditUsed - $walletBalance;
			$credits	 = max([$credits1, 10000]);
			$remarks	 = " Bookings";
		}
		else
		{
			$credits = $creditUsed - $walletBalance;

			if ($credits > $availableBalance && $overdue == false)
			{
				return false;
			}
		}
		if ($remarks != '')
		{
			$remarks = " for {$remarks}";
		}
		$actModel = self::issuePartnerWallet($agentId, $credits, '', "Money issued to wallet" . $remarks, UserInfo::model());
		if (!$actModel)
		{
			return false;
		}
		$walletBalance += $credits;
		skipIssue:
		return $walletBalance;
	}

	/**
	 * @return AccountTransactions|false Will return false if criteria not matched
	 */
	public static function checkPartnerWalletForIssue($bkgId, $agentId, $creditUsed, $overdue = null)
	{
		if ($overdue == true)
		{
			goto skipPickupCheck;
		}
		$pickup = Booking::validatePickupTime($bkgId);
		if ($pickup)
		{
			goto skipIssue;
		}
		skipPickupCheck:
		$date				 = date('Y-m-d H:i:s', strtotime('+12 hour'));
		$availableBalance	 = Agents::getAvailableLimit($agentId);
		$getBalance			 = PartnerStats::getBalance($agentId);
		$walletBalance		 = $getBalance['pts_wallet_balance'];
		//$walletBalance		 = AccountTransactions::checkPartnerWalletBalance($agentId, $date);
		if ($walletBalance >= $creditUsed)
		{
			goto skipIssue;
		}
		$credits = $creditUsed - $walletBalance;
		if ($credits > $availableBalance)
		{
			return false;
		}
		skipIssue:
		return true;
	}

	/**
	 * 
	 * @param integer $amount  | Bonus amount 
	 * @param integer $refId        | Reference ID of $ledgerId
	 * @param integer $userId       | User Id for which bonus is being granted   
	 * @param integer $refUserId    | Referring User Id due which Bonus is being granted to user  
	 * @param type $ledgerId	| Ledger ID in which the joining bonus will be provided
	 * @throws Exception
	 */
	public static function addReferralBonus($date, $amount, $ledgerId, $refId, $userId, $referralUserID, $remarks, $userInfo = null)
	{
		switch ($ledgerId)
		{
			case Accounting::LI_VOUCHER:
				$refType = Accounting::AT_GIFTCARD;
				break;
			case Accounting::LI_PROMOTIONS_MARKETING:
				$refType = Accounting::AT_GIFTCARD;
				break;
			default:
				$refType = Accounting::AT_USER;
				break;
		}

		if ($remarks == "")
		{
			$remarks = "Bonus added for referring user";
		}

		$accTransModel	 = self::getInstance(Accounting::AT_USER, $date, $amount, $remarks, $userId, $userInfo);
		$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_JOINING_BONUS, Accounting::AT_USER, $referralUserID);
		$crTrans		 = AccountTransDetails::getInstance($ledgerId, $refType, $refId);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_USER);

		if (!$status)
		{
			throw new Exception("Unable to process referral transaction", ReturnSet::ERROR_FAILED);
		}

		if ($accTransModel->hasErrors())
		{
			throw new Exception(json_encode($accTransModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		return $accTransModel;
	}

	/**
	 * 
	 * @param string $date
	 * @param integer $amount
	 * @param integer $ledgerId
	 * @param integer $refId
	 * @param integer $userId
	 * @param string $remarks
	 * @param  $userInfo
	 * @return AccountTransactions
	 * @throws Exception
	 */
	public static function addGozoCoins($date, $amount, $ledgerId, $refId, $userId, $remarks, $userInfo = null)
	{
		switch ($ledgerId)
		{
			case Accounting::LI_BOOKING:
				$refType = Accounting::AT_BOOKING;
				break;
			case Accounting::LI_PROMOTIONS_MARKETING:
				$refType = Accounting::AT_GIFTCARD;
				break;
			case Accounting::LI_JOINING_BONUS:
				$refType = Accounting::AT_USER;
				break;
			case Accounting::LI_VOUCHER:
				$refType = Accounting::AT_GIFTCARD;
				break;
			default:
				$refType = Accounting::AT_USER;
				break;
		}

		$accTransModel	 = self::getInstance($refType, $date, $amount, $remarks, $userId, $userInfo);
		$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_GOZOCOINS, Accounting::AT_USER, $userId, '', $remarks);
		$drTrans		 = AccountTransDetails::getInstance($ledgerId, $refType, $refId, '', $remarks);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_USER);

		if (!$status)
		{
			throw new Exception("Unable to add Gozo Coins", ReturnSet::ERROR_FAILED);
		}

		if ($accTransModel->hasErrors())
		{
			throw new Exception(json_encode($accTransModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		return $accTransModel;
	}

	/**
	 * 
	 * @param string $date
	 * @param integer $amount
	 * @param integer $ledgerId
	 * @param integer $refId
	 * @param integer $userId
	 * @param string $remarks
	 * @param  $userInfo
	 * @return AccountTransactions
	 * @throws Exception
	 */
	public static function chargeGozoCoins($date, $amount, $ledgerId, $refId, $userId, $remarks, $userInfo = null, $additionalParams = null)
	{
		switch ($ledgerId)
		{
			case Accounting::LI_VOUCHER:
				$refType = Accounting::AT_GIFTCARD;
				break;
			case Accounting::LI_BOOKING:
			default:
				$refType = Accounting::AT_BOOKING;
				break;
		}

		$accTransModel	 = self::getInstance($refType, $date, $amount, $remarks, $refId, $userInfo);
		$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_GOZOCOINS, Accounting::AT_USER, $userId, $additionalParams);
		$crTrans		 = AccountTransDetails::getInstance($ledgerId, $refType, $refId, $additionalParams);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, $refType);

		if (!$status)
		{
			throw new Exception("Unable to apply Gozo Coins", ReturnSet::ERROR_FAILED);
		}

		if ($accTransModel->hasErrors())
		{
			throw new Exception(json_encode($accTransModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		return $accTransModel;
	}

	/**
	 * 
	 * @param string $date
	 * @param integer $amount
	 * @param integer $refId
	 * @param integer $userId
	 * @param string $remarks
	 * @param Array $userInfo
	 * @return type
	 * @throws Exception
	 */
	public static function redeemWalletVoucher($date, $amount, $refId, $userId, $remarks, $userInfo = null)
	{
		if ($remarks == "")
		{
			$remarks = "Voucher Redeemed";
		}
		return self::processWallet($date, ($amount * -1), $refId, Accounting::AT_VOUCHER, Accounting::LI_VOUCHER, $userId, $remarks, '', $userInfo);
	}

	/**
	 * @return AccountTransactions|false Will return false if transaction failed 
	 */
	public static function userWalletToBooking($date, $amount, $userId, $bkgId, UserInfo $userInfo = null)
	{
		if ($amount >= 0)
		{
			$remarks = "₹" . $amount . " deducted from wallet, used in booking " . $bkgId;
		}
		else
		{
			$remarks = "₹" . abs($amount) . " refunded to wallet from booking " . $bkgId;
		}
		return self::processWallet($date, $amount, $bkgId, Accounting::AT_BOOKING, Accounting::LI_BOOKING, $userId, $remarks, '', $userInfo);
	}

	/**
	 * 
	 * @param string $date
	 * @param integer $amount
	 * @param integer $refId
	 * @param integer $refType
	 * @param integer $refLedgerId
	 * @param integer $userId
	 * @param string $remarks
	 * @param array $refParams
	 * @param UserInfo $userInfo
	 * @return AccountTransactions
	 * @throws Exception
	 */
	public static function processWallet($date, $amount, $refId, $refType, $refLedgerId, $userId, $remarks, $refParams = '', UserInfo $userInfo = null)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			$accTransModel	 = self::getInstance($refType, $date, abs($amount), $remarks, $refId, $userInfo);
			$trans1			 = AccountTransDetails::getInstance(Accounting::LI_WALLET, Accounting::AT_USER, $userId);
			$trans2			 = AccountTransDetails::getInstance($refLedgerId, $refType, $refId, $refParams, $remarks);

			if ($amount >= 0)
			{
				$drTrans = $trans1;
				$crTrans = $trans2;
			}
			else
			{
				$drTrans = $trans2;
				$crTrans = $trans1;
			}

			$status = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_USER);

			if ($accTransModel->hasErrors())
			{
				throw new Exception(json_encode($accTransModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			if (!$status)
			{
				throw new Exception("Unable to process voucher transaction", ReturnSet::ERROR_FAILED);
			}

			$walletModel = UserWallet::add($userId, $amount);
			if (!$walletModel)
			{
				throw new Exception("Unable to process voucher transaction", ReturnSet::ERROR_FAILED);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			throw $e;
		}
		return $accTransModel;
	}

	/**
	 * @return AccountTransactions|false Will return false if transaction failed 
	 */
	public static function paidToOperator($date, $amount, $vendor_id, $bankRefId, $paymentRefId, $bankResponse, $remarks = '', UserInfo $userInfo = null)
	{

		$remarks .= trim(' Amount paid to operator (Ref.' . $bankRefId . ')');

		$accountTrans = self::bankPayment($date, $amount, $paymentRefId, $vendor_id, Accounting::AT_OPERATOR, Accounting::LI_OPERATOR, $bankResponse, $remarks, $userInfo);

		return $accountTrans;
	}

	public static function paidToConstomer($date, $amount, $user_id, $bankRefId, $paymentRefId, $bankResponse, $remarks = '', UserInfo $userInfo = null)
	{

		$remarks .= trim(' Amount transferred  to customer bank (Ref.' . $bankRefId . ')');

		$accountTrans = self::bankPayment($date, $amount, $paymentRefId, $user_id, Accounting::AT_USER, Accounting::LI_WALLET, $bankResponse, $remarks, $userInfo);

		return $accountTrans;
	}

	/**
	 * @return AccountTransactions|false Will return false if transaction failed 
	 */
	public static function bankPayment($date, $amount, $paymentRefId, $refId, $refType, $ledgerId, $bankResponse, $remarks = '', UserInfo $userInfo = null)
	{
		$accTransModel	 = self::getInstance($refType, $date, $amount, $remarks, $refId, $userInfo);
		$drTrans		 = AccountTransDetails::getInstance($ledgerId, $refType, $refId, $remarks);
		$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_ICICI, Accounting::AT_CIB, $paymentRefId, $bankResponse, $remarks);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, $refType);
		if ($status)
		{
			return $accTransModel;
		}
		return $status;
	}

	/**
	 * @return AccountTransactions|false Will return false if transaction failed
	 */
	public static function PaymentGatewayToUserWallet($date, $amount, $userId, $ptpRefId, $ptpId, $response, UserInfo $userInfo = null)
	{
		$remarks	 = "Rs.$amount Added to Wallet.";
		$ptpLedgetId = PaymentType::getLedgerId($ptpId);

		$accTransModel	 = self::getInstance(Accounting::AT_USER, $date, $amount, $remarks, $userId, $userInfo);
		$drTrans		 = AccountTransDetails::getInstance($ptpLedgetId, Accounting::AT_ONLINEPAYMENT, $ptpRefId, $remarks);
		$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_WALLET, Accounting::AT_USER, $userId, $response, $remarks);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);
		if ($status)
		{
			return $accTransModel;
		}
		return false;
	}

	/**
	 * @return AccountTransactions|false Will return false if transaction failed
	 */
	public static function UserWalletToPaymentGateway($date, $amount, $userId, $ptpRefId, $ptpId, $response, UserInfo $userInfo = null)
	{
		$remarks	 = "Rs.$amount Refunded to payment source from Wallet.";
		$ptpLedgetId = PaymentType::getLedgerId($ptpId);

		$accTransModel	 = self::getInstance(Accounting::AT_ONLINEPAYMENT, $date, $amount, $remarks, $ptpRefId, $userInfo);
		$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_WALLET, Accounting::AT_USER, $userId, $response, $remarks);
		$crTrans		 = AccountTransDetails::getInstance($ptpLedgetId, Accounting::AT_ONLINEPAYMENT, $ptpRefId, $remarks);

		$status = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_ONLINEPAYMENT);
		if ($status)
		{
			return $accTransModel;
		}
		return false;
	}

	/**
	 * @return AccountTransactions|false Will return false if transaction failed
	 * 	 
	 * @param type $date
	 * @param type $amount
	 * @param type $bkgId
	 * @param type $ptpRefId
	 * @param type $ptpId
	 * @param type $response
	 * @param UserInfo $userInfo
	 * @return boolean
	 */
	public static function BookingToPaymentGateway($date, $amount, $bkgId, $ptpRefId, $ptpId, $response, UserInfo $userInfo = null)
	{
		$remarks	 = "Rs.$amount Refunded to payment source from Booking.";
		$ptpLedgetId = PaymentType::getLedgerId($ptpId);

		$accTransModel	 = self::getInstance(Accounting::AT_ONLINEPAYMENT, $date, $amount, $remarks, $ptpRefId, $userInfo);
		$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_BOOKING, Accounting::AT_BOOKING, $bkgId, $response, $remarks);
		$crTrans		 = AccountTransDetails::getInstance($ptpLedgetId, Accounting::AT_ONLINEPAYMENT, $ptpRefId, $remarks);

		$status = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_ONLINEPAYMENT);
		if ($status)
		{
			return $accTransModel;
		}
		return false;
	}

	/**
	 * 
	 * @param int $partnerId
	 * @param int $bkgId
	 * @param int $amount
	 * @param string $date
	 * @param int $refType
	 * @param int $refId
	 * @param string $remarks
	 * @param UserInfo $userInfo
	 * @param string $accStatus
	 * @return AccountTransactions
	 * @throws Exception
	 */
	public static function advancePartnerWallet($partnerId, $bkgId, $amount, $date, $remarks = "", $userInfo = null, $accStatus)
	{
		$accTransModel	 = self::getInstance(Accounting::AT_BOOKING, $date, $amount, $remarks, $bkgId, $userInfo);
		$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_BOOKING, Accounting::AT_BOOKING, $bkgId, '', $remarks);
		$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_PARTNERWALLET, Accounting::AT_PARTNER, $partnerId, '', $remarks, $accStatus);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);

		if (!$status)
		{
			throw new Exception("Unable to add money to booking", ReturnSet::ERROR_FAILED);
		}

		if ($accTransModel->hasErrors())
		{
			throw new Exception(json_encode($accTransModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		return $accTransModel;
	}

	public static function processPendingPartnerWallet($hour = 12)
	{

		$numRows = false;
		try
		{
			$param	 = ['hour' => $hour];
			$sql	 = "UPDATE account_trans_details adt
						INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id = 49
						INNER JOIN account_trans_details adt1 ON act.act_id = adt1.adt_trans_id AND adt1.adt_ledger_id = 13
						SET  adt.adt_status = 1
						WHERE act.act_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL :hour HOUR) 
							AND adt.adt_status = 0 AND act_active =1";

			$numRows = DBUtil::execute($sql, $param);
		}
		catch (Exception $e)
		{
			Logger::exception($e);
		}
		return $numRows;
	}

	public static function UpdateInactiveStatus($bkgId)
	{
		$numRows = false;
		try
		{
			$param	 = ['bkgId' => $bkgId];
			$sql	 = "UPDATE
					account_trans_details atd
				INNER JOIN account_transactions act ON
					atd.adt_trans_id = act.act_id AND atd.adt_ledger_id = 49
				INNER JOIN account_trans_details atd1 ON
					act.act_id = atd1.adt_trans_id AND atd1.adt_ledger_id IN(13) AND atd1.adt_trans_ref_id =:bkgId
				SET
					atd.adt_status = 1
				WHERE
					atd1.adt_active = 1 AND atd.adt_active = 1 AND act.act_active = 1 AND atd.adt_status = 0";

			$numRows = DBUtil::execute($sql, $param);
		}
		catch (Exception $e)
		{
			Logger::exception($e);
		}
		return $numRows;
	}

	public static function checkRefund($bkgId, $amt, $ledger = '')
	{
		$param	 = ['bkgId' => $bkgId, 'amt1' => -1 * $amt, 'amt2' => $amt];
		$sql	 = "SELECT
					*
				FROM
					account_trans_details atd
				INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id  
				 AND atd.adt_amount =:amt1
				INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id 
				AND atd1.adt_ledger_id = 13 AND atd1.adt_trans_ref_id =:bkgId  AND atd1.adt_amount =:amt2
				WHERE
					atd1.adt_active = 1 AND atd.adt_active = 1 AND act.act_active = 1";
		$rows	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
		return $rows;
	}

	/**
	 * @return boolean
	 * */
	public static function removeCancellationCharge($bkgId)
	{
		$success = true;
		$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM account_trans_details atd
				INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id  AND atd.adt_ledger_id=25
				INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id 
					AND atd1.adt_ledger_id = 13 AND atd1.adt_trans_ref_id =:bkgId
				WHERE atd1.adt_active = 1 AND atd.adt_active = 1 AND act.act_active = 1";

		$actIds = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
		if ($actIds != null && $actIds != '')
		{
			$success = AccountTransactions::remove($actIds);
		}
		return $success;
	}

	public static function getAppliedPenaltyList($bkgid, $bcbId)
	{
		DBUtil::getINStatement($bcbId, $bindString, $param);
		$sql = "SELECT
				act.act_id,
				act.act_amount,
				vendors.vnd_name,
                vendors.vnd_id,
				vendors.vnd_code,
				act.act_ref_id,
				atd.adt_trans_ref_id,
				act.act_remarks,
				bcb_bkg_id1 bkgid,
				act.act_amount,
				atd1.adt_amount as amount,
				act.act_date,
				act.act_created,
				atd.adt_addt_params,
				atd.adt_type,
				JSON_VALUE(atd.adt_addt_params,'$.vncId') AS vendorCoinId
			FROM
				account_trans_details atd
			INNER JOIN account_transactions act ON
				atd.adt_trans_id = act.act_id AND atd.adt_active = 1 AND act.act_active = 1 AND atd.adt_ledger_id = 28
			INNER JOIN account_trans_details atd1 ON
				act.act_id = atd1.adt_trans_id AND atd1.adt_active = 1 AND atd1.adt_ledger_id = 14
			INNER JOIN vendors ON vendors.vnd_id = atd1.adt_trans_ref_id
			LEFT JOIN booking_cab ON booking_cab.bcb_id = atd.adt_trans_ref_id AND atd.adt_type = 5
			WHERE
				(atd.adt_trans_ref_id = $bkgid AND atd.adt_type = 1) OR (atd.adt_trans_ref_id IN($bindString) AND atd.adt_type = 5)";

		$rows = DBUtil::query($sql, DBUtil::MDB(), $param);
		return $rows;
	}

	public static function getTripPurchase($tipId)
	{
		$params = ['tripId' => $tipId];

		$sql = "SELECT *  FROM account_transactions
                    INNER JOIN account_trans_details adt 
                                ON adt.adt_trans_id = act_id AND adt.adt_ledger_id =22 
                    WHERE 
                    act_type = 5 AND act_ref_id =:tripId AND act_active =1 AND adt.adt_active=1";

		$records = DBUtil::query($sql, DBUtil::MDB(), $params);
		return $records;
	}

	public function getAdvanceMismatchReports()
	{
		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";
		$params		 = ['fromDate' => $fromDate, 'toDate' => $toDate];

		$where .= "bkg.bkg_agent_id IS NOT NULL";
		if ($fromDate != '' && $toDate != '')
		{
			$where .= " AND bkg.bkg_pickup_date BETWEEN :fromDate AND :toDate";
		}
		$sql = "SELECT bkg.bkg_id,
					bkg.bkg_booking_id,
					biv.bkg_net_base_amount, 
					bkg.bkg_agent_id,
					bkg.bkg_status, 
					biv.bkg_net_advance_amount, 
					round(biv.bkg_partner_commission/1.18) as bkg_partner_commission, round(biv.bkg_partner_commission - biv.bkg_partner_commission/1.18) as commissionGst,
					biv.bkg_credits_used, biv.bkg_advance_amount, biv.bkg_refund_amount,
					SUM(atd1.adt_amount) AS totalTrans, 
					SUM(IF(atd1.adt_ledger_id IN (36),atd1.adt_amount,0)) AS adtCreditUsed, 
					SUM(IF(atd1.adt_ledger_id IN (1,23,29,30,16,17,18,19,20,21,39,42,46,47,15,26,49,53,58) AND atd1.adt_status=1 ,round(atd1.adt_amount),0)) AS adtAdvance, 
					SUM(IF(atd1.adt_ledger_id IN (35),atd1.adt_amount,0)) AS adtCommission, 
					SUM(IF(atd1.adt_ledger_id IN (15,26,49),atd1.adt_amount,0)) AS adtPartnerCredits 
                    FROM account_transactions act 
					INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_ledger_id=13 AND atd.adt_active=1         
					INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id AND atd1.adt_ledger_id<>13 AND atd1.adt_active=1                    
                    AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
					INNER JOIN booking bkg ON bkg.bkg_id=atd.adt_trans_ref_id 
					INNER JOIN booking_invoice biv ON bkg.bkg_id=biv.biv_bkg_id 
                    WHERE $where
                    GROUP BY bkg_id 
                    HAVING (adtAdvance<>((biv.bkg_advance_amount + biv.bkg_credits_used - biv.bkg_refund_amount) -bkg_credits_used) AND (adtAdvance <> 0))";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_pickup_date'],
				'defaultOrder'	 => 'bkg_pickup_date  DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getAccountMismatchReports()
	{
		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";
		$params		 = ['fromDate' => $fromDate, 'toDate' => $toDate];

		$where .= "(bkg.bkg_status IN(6,7) OR (bkg.bkg_status IN(9) AND bkg.bkg_agent_id IN(18190,34928,30228,4946,1249)))";
		if ($fromDate != '' && $toDate != '')
		{
			$where1 .= " AND bkg.bkg_pickup_date BETWEEN :fromDate AND :toDate";
		}
		$sql			 = "SELECT bkg.bkg_id, 
			    bkg.bkg_booking_id,
				biv.bkg_net_base_amount, 
				bkg.bkg_agent_id, 
				bkg.bkg_status, 
				biv.bkg_net_advance_amount, 
				(biv.bkg_partner_commission + biv.bkg_partner_extra_commission) bkg_partner_commission, 
			    (biv.bkg_partner_commission + biv.bkg_partner_extra_commission) totalCommission,
				biv.bkg_credits_used,
				SUM(atd1.adt_amount) AS totalTrans,
				SUM(IF(atd1.adt_ledger_id IN (36),atd1.adt_amount,0)) AS adtCreditUsed,
				SUM(IF(atd1.adt_ledger_id IN (1,15,16,17,18,19,20,21,23,26,29,39,42,47,49,30,53,58),round(atd1.adt_amount),0)) AS adtAdvance,
				SUM(IF(atd1.adt_ledger_id IN (35),atd1.adt_amount,0)) AS adtCommission,
				SUM(IF(atd1.adt_ledger_id IN (15,26,49),atd1.adt_amount,0)) AS adtPartnerCredits,
				((bkg_partner_commission + biv.bkg_partner_extra_commission) - SUM(IF(atd1.adt_ledger_id IN(35),atd1.adt_amount,0)) *-1) comDiff
				FROM account_transactions act
				INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_ledger_id=13 AND atd.adt_active=1
				INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id AND atd1.adt_ledger_id<>13 AND atd1.adt_active=1
				AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0))
				INNER JOIN booking bkg ON bkg.bkg_id=atd.adt_trans_ref_id $where1
				INNER JOIN booking_invoice biv ON bkg.bkg_id=biv.biv_bkg_id
				WHERE $where 
				GROUP BY bkg.bkg_id 
				HAVING 
				(adtAdvance <> (bkg_net_advance_amount-bkg_credits_used))
					OR 
				 (adtCommission <> (totalCommission * -1) AND adtCommission <0 AND (comDiff < -5 OR comDiff > 5))";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_pickup_date'],
				'defaultOrder'	 => 'bkg_pickup_date  DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public static function getSecurityAmount($vndIds)
	{
		DBUtil::getINStatement($vndIds, $bindString, $params);
		$sql = "SELECT IFNULL(SUM(adt1.adt_amount),0) FROM account_transactions act
				INNER JOIN account_trans_details adt ON adt.adt_trans_id = act.act_id AND adt.adt_ledger_id = 34 AND adt.adt_active=1 
				INNER JOIN account_trans_details adt1 ON adt1.adt_trans_id = act.act_id AND adt1.adt_ledger_id IN(57,14) 
				AND adt1.adt_active=1 AND adt1.adt_trans_ref_id IN ($bindString) 
					AND act.act_date >= '2021-04-01 00:00:00' 
				WHERE act.act_active = 1";

		$vendorSecurityAmt = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $vendorSecurityAmt;
	}

	public static function refundSecurityAmount($amount, $vndId, $date = null, $remarks = '', $userInfo = null)
	{
		if ($date == null)
		{
			$date = new CDbExpression("NOW()");
		}

		if ($remarks == "")
		{
			$remarks = "Security amount refunded";
		}

		$accTransModel	 = self::getInstance(Accounting::AT_OPERATOR, $date, $amount, $remarks, $vndId, $userInfo);
		$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $vndId, '', $remarks);
		$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_SECURITY_DEPOSIT, Accounting::AT_OPERATOR, $vndId, '', $remarks);

		$status = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_OPERATOR);

		if ($accTransModel->hasErrors())
		{
			throw new Exception(json_encode($accTransModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		if (!$status)
		{
			throw new Exception("Security amount refund failed", ReturnSet::ERROR_FAILED);
		}
		return $accTransModel;
	}

	/**
	 * @return AccountTransactions
	 * @throws Exception 
	 */
	public static function chargeSecurityAmount($amount, $vndId, $date = '', $remarks = '', $userInfo = null)
	{
		if ($date == null)
		{
			$date = new CDbExpression("NOW()");
		}

		if ($remarks == '')
		{
			$remarks = "Security amount charged.";
		}

		$accTransModel	 = self::getInstance(Accounting::AT_OPERATOR, $date, $amount, $remarks, $vndId, $userInfo);
		$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_SECURITY_DEPOSIT, Accounting::AT_OPERATOR, $vndId, '', '');
		$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $vndId, '', $remarks);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_OPERATOR);

		if ($accTransModel->hasErrors())
		{
			throw new Exception(json_encode($accTransModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		if (!$status)
		{
			throw new Exception("Unable to add money to booking", ReturnSet::ERROR_FAILED);
		}

		return $accTransModel;
	}

	public static function getBalance($ledgerId, $refId = null, $date = null)
	{
		$condition	 = "";
		$params		 = ["ledgerId" => $ledgerId];
		if ($refId != null)
		{
			$condition		 = " AND atd.adt_trans_ref_id=:refId";
			$params["refId"] = $refId;
		}
		if ($date != null)
		{
			$condition		 .= " AND act_date<=:date";
			$params["date"]	 = $date;
		}

		$sql	 = "SELECT SUM(adt_amount) as totalAmount FROM account_transactions act
				INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id 
					AND act.act_active=1 AND atd.adt_status=1 AND atd.adt_active=1
				WHERE atd.adt_ledger_id=:ledgerId {$condition}
				";
		$value	 = DBUtil::queryScalar($sql, null, $params);
		return $value;
	}

	public static function getVendorBalance($vendorId, $date = null)
	{
		return self::getBalance(Accounting::LI_OPERATOR, $vendorId, $date);
	}

	public static function transferVendorsBalance($duplicateIds, $vndid)
	{
		if (is_array($duplicateIds))
		{
			$result = [];
			foreach ($duplicateIds as $value)
			{
				$result[$value] = self::transferVendorBalance($value, $vndid);
			}
			return $result;
		}
	}

	public static function transferVendorBalance($fromVendorId, $toVendorId)
	{

		$balance = self::getVendorBalance($fromVendorId);
		if ($balance == 0)
		{
			return true;
		}
		$fromVendorName	 = Vendors::model()->findByPk($fromVendorId)->vnd_name;
		$toVendorName	 = Vendors::model()->findByPk($toVendorId)->vnd_name;
		$remarks		 = "Balance transferred from $fromVendorName to $toVendorName";
		if ($balance < 0)
		{
			$drVendorId	 = $fromVendorId;
			$crVendorId	 = $toVendorId;
		}
		else
		{
			$crVendorId	 = $fromVendorId;
			$drVendorId	 = $toVendorId;
		}

		$accTransModel	 = self::getInstance(Accounting::AT_OPERATOR, null, $balance, $remarks, $toVendorId);
		$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $drVendorId, $remarks);
		$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $crVendorId, '', $remarks);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_OPERATOR);
		if ($status)
		{
			return $accTransModel;
		}
		return $status;
	}

	/**
	 * 
	 * @param int $partnerId
	 * @param int $bkgId
	 * @param int $amount
	 * @param string $date
	 * @param int $refType
	 * @param int $refId
	 * @param string $remarks
	 * @param UserInfo $userInfo
	 * @param string $accStatus
	 * @return AccountTransactions
	 * @throws Exception
	 */
	public static function refundPartnerWallet($partnerId, $bkgId, $amount, $date, $remarks = "", $userInfo = null, $accStatus)
	{
		self::removeRefund($bkgId);
		$accTransModel	 = self::getInstance(Accounting::AT_BOOKING, $date, $amount, $remarks, $bkgId, $userInfo);
		$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_BOOKING, Accounting::AT_BOOKING, $bkgId, '', $remarks);
		$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_PARTNERWALLET, Accounting::AT_PARTNER, $partnerId, '', $remarks, $accStatus);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);

		if (!$status)
		{
			throw new Exception("Unable to add money to booking", ReturnSet::ERROR_FAILED);
		}

		if ($accTransModel->hasErrors())
		{
			throw new Exception(json_encode($accTransModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		return $accTransModel;
	}

	/**
	 * Get last booking ledger date  by particular agent id
	 * @return string
	 */
	public static function getLastBookingLedgerDate($agentId)
	{
		$params	 = array('agtId' => $agentId);
		$sql	 = "SELECT act_created
						FROM account_transactions act
						INNER JOIN account_trans_details adt1 ON     act.act_id = adt1.adt_trans_id AND adt1.adt_active = 1 AND adt1.adt_amount < 0 AND adt1.adt_ledger_id =13 
						INNER JOIN account_trans_details adt2 ON     act.act_id = adt2.adt_trans_id   AND adt2.adt_active = 1 AND adt2.adt_amount > 0 AND adt2.adt_ledger_id =49 and adt2.adt_trans_ref_id =:agtId
						WHERE act_active = 1 GROUP BY act.act_id ORDER BY act.`act_created` DESC LIMIT 0,1";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	/**
	 * 
	 * @param int $partnerId
	 * @param int $amount
	 * @param string $date
	 * @param int $refType
	 * @param int $refId
	 * @param string $remarks
	 * @param UserInfo $userInfo
	 * @return AccountTransactions
	 * @throws Exception
	 */
	public function partnerWalletToPartnerLedger($partnerId, $amount, $date, $remarks = "", $userInfo = null, $adtParam = null)
	{
		$success = false;
		$trans	 = DBUtil::beginTransaction();
		try
		{
			$accTransDetArr		 = [];
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $partnerId, Accounting::LI_PARTNERWALLET, $amount, $remarks, 0, null, null, $adtParam);
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_PARTNER, $partnerId, Accounting::LI_PARTNER, (-1 * $amount), $remarks, 0, null, null, $adtParam);
			$success			 = $this->add($accTransDetArr, $date, $amount, $partnerId, Accounting::AT_PARTNER, $remarks, $userInfo);

			DBUtil::commitTransaction($trans);
			$success = true;
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($trans);
			Logger::create("Failed to add commission for Booking ID: $partnerId ({$e->getMessage()})", CLogger::LEVEL_ERROR);
		}

		return $success;
	}

	public function tripPurchaseMissing()
	{
		$sql = "SELECT GROUP_CONCAT(bkg_id) bkg_id, bcb_id, bkg_agent_id, bkg_pickup_date, act_id,bcb_pending_status 
				FROM booking_cab bcb 
				INNER JOIN booking bkg ON bcb_id = bkg_bcb_id AND bkg_status IN (6,7) AND bkg_pickup_date >= '2023-04-01 00:00:00' 
				LEFT JOIN account_transactions act ON bcb_id = act_ref_id AND act_type = 5 AND act.act_active=1 AND act.act_status=1 AND act.act_date>='2023-04-01 00:00:00' 
				LEFT JOIN account_trans_details atd ON act.act_id = atd.adt_trans_id AND atd.adt_type = 5 AND atd.adt_ledger_id IN (22) AND atd.adt_active=1 AND atd.adt_status=1 
				LEFT JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id AND atd1.adt_ledger_id IN (14) AND atd1.adt_type = 2 AND atd1.adt_active=1 AND atd1.adt_status=1
					AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
				WHERE 1 AND bkg_status IN (6,7)
				GROUP BY bcb_id
				HAVING act_id IS NULL";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['bkg_pickup_date'], 'defaultOrder' => 'bkg_pickup_date  ASC'],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public function tripPurchaseMultipleEntry()
	{
		$sql = "SELECT bkg_id, bcb_id, bkg_status, bcb_vendor_id, bkg_pickup_date, bkg_agent_id, bkg_total_amount, (bkg_total_amount-bkg_net_advance_amount) as vendorCollected,
				bkg_net_advance_amount, bkg_vendor_collected, tripPurchaseAmt, DebitIds, cntActId
				FROM booking
			INNER JOIN booking_invoice ON bkg_id=biv_bkg_id AND bkg_status IN (6,7) AND bkg_pickup_date>='2021-04-01 00:00:00'
			INNER JOIN booking_cab ON bcb_id=bkg_bcb_id 
			INNER JOIN (
				SELECT atd.adt_trans_ref_id, SUM(IF(abs(atd.adt_amount)<=abs(atd1.adt_amount), atd.adt_amount*-1, atd1.adt_amount)) as tripPurchaseAmt,
			GROUP_CONCAT(act_id SEPARATOR ', ') as DebitIds, COUNT(act_id) as cntActId
			FROM account_trans_details atd
			INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 AND atd.adt_ledger_id IN (22) AND atd.adt_status=1 AND act.act_type=5
			INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id IN (14) AND atd1.adt_active=1
			AND (atd.adt_amount>0 AND atd1.adt_amount<0) AND act.act_date>='2021-04-01 00:00:00'
			GROUP BY atd.adt_trans_ref_id HAVING cntActId >1
			) a ON a.adt_trans_ref_id=bcb_id  
			WHERE 1";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['bkg_pickup_date'], 'defaultOrder' => 'bkg_pickup_date ASC'],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public function cashCollectedMissing()
	{
		$sql = "SELECT bkg_id, bcb_vendor_id, bkg_pickup_date, bkg_agent_id, bkg_total_amount, (bkg_total_amount-bkg_net_advance_amount) as vendorCollected,
				bkg_net_advance_amount, bkg_vendor_collected, debited, DebitIds,IF(bcb_trip_type=0,'','Matched') tripType
				FROM booking
				INNER JOIN booking_invoice ON bkg_id=biv_bkg_id AND bkg_status IN (6,7) AND bkg_pickup_date>='2023-04-01 00:00:00'
				INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
				LEFT JOIN (
					SELECT atd.adt_trans_ref_id, SUM(IF(abs(atd.adt_amount)<=abs(atd1.adt_amount), atd.adt_amount*-1, atd1.adt_amount)) as debited, GROUP_CONCAT(act_id) as DebitIds
					FROM account_trans_details atd
					INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 
						AND atd.adt_ledger_id IN (13) AND atd.adt_status=1
					INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id IN (14) AND atd1.adt_active=1
						AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) AND act.act_date>='2023-04-01 00:00:00'
					GROUP BY atd.adt_trans_ref_id
				) a ON a.adt_trans_ref_id=bkg_id
				WHERE 1 AND (bkg_total_amount-bkg_net_advance_amount) > 0 AND (a.adt_trans_ref_id IS NULL)";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['bkg_pickup_date'], 'defaultOrder' => 'bkg_pickup_date ASC'],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public function cashCollectedMultipleEntries()
	{
		$sql = "SELECT bkg_id, bcb_vendor_id, bkg_pickup_date, bkg_agent_id, bkg_total_amount, (bkg_total_amount-bkg_net_advance_amount) as vendorCollected,
				bkg_net_advance_amount, bkg_vendor_collected, driverCollectAccountEntryAmt, DebitIds, cntActId
				FROM booking
				INNER JOIN booking_invoice ON bkg_id=biv_bkg_id AND bkg_status IN (6,7) AND bkg_pickup_date>='2023-04-01 00:00:00'
				INNER JOIN booking_cab ON bcb_id=bkg_bcb_id 
				LEFT JOIN (
					SELECT atd.adt_trans_ref_id, SUM(IF(abs(atd.adt_amount)<=abs(atd1.adt_amount), atd.adt_amount*-1, atd1.adt_amount)) as driverCollectAccountEntryAmt,
					GROUP_CONCAT(act_id SEPARATOR ', ') as DebitIds, COUNT(act_id) as cntActId
					FROM account_trans_details atd
					INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1
						AND atd.adt_ledger_id IN (13) AND atd.adt_status=1
					INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id IN (14) AND atd1.adt_active=1
						AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) AND act.act_date>='2023-04-01 00:00:00'
					GROUP BY atd.adt_trans_ref_id
				) a ON a.adt_trans_ref_id=bkg_id
				WHERE a.cntActId > 1";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['bkg_pickup_date'], 'defaultOrder' => 'bkg_pickup_date ASC'],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public function advanceMultipleEntries()
	{
		// These bkgIds are checked and it is fine
		$arrIgnoreBkgIds = ['2697992', '2604181'];
		$cond			 = " AND bkg_id NOT IN (" . implode(',', $arrIgnoreBkgIds) . ") ";

		$sql = "SELECT bkg_id, bkg_agent_id, bkg_pickup_date, bkg_net_advance_amount, bkg_total_amount, GROUP_CONCAT(act_id SEPARATOR ', ') act_ids, atd.adt_trans_ref_id, 
				SUM(atd.adt_amount) adt_amt, SUM(atd1.adt_amount) adt1_amt, COUNT(DISTINCT act.act_id) cnt, GROUP_CONCAT(atd1.adt_amount) as amts, bkg_advance_amount
				FROM booking bkg
				INNER JOIN booking_invoice biv ON bkg_id = biv_bkg_id
				INNER JOIN account_trans_details atd ON bkg_id = atd.adt_trans_ref_id
				INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 
					AND atd.adt_ledger_id IN (13) AND atd.adt_status=1
				INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id IN (1,16,17,18,19,20,21,23,26,29,30,36,42,46,47,48,49) AND atd1.adt_active=1
					AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) AND act.act_date>='2021-04-01 00:00:00'
				WHERE bkg_status IN (2,3,5,6,7) AND bkg_pickup_date >= '2021-04-01 00:00:00' 
				{$cond} 
				GROUP BY bkg_id, atd1.adt_amount
				HAVING cnt > 1 AND adt_amt <> SUM(amts) AND (adt1_amt > bkg_net_advance_amount OR bkg_net_advance_amount > adt1_amt) AND adt1_amt <> bkg_advance_amount";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['bkg_pickup_date'], 'defaultOrder' => 'bkg_pickup_date ASC'],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public function partnerCommMultipleEntries()
	{
		$sql = "SELECT bkg_id, bkg_agent_id, bkg_pickup_date, bkg_net_advance_amount, bkg_total_amount, act_ids, partnerCommAmt, cntActId
					FROM booking
				INNER JOIN booking_invoice ON bkg_id=biv_bkg_id AND bkg_status IN (6,7) AND bkg_agent_id > 0 
				INNER JOIN (
						   SELECT atd.adt_trans_ref_id adtref, SUM(IF(abs(atd.adt_amount)<=abs(atd1.adt_amount), atd.adt_amount*-1, atd1.adt_amount)) as partnerCommAmt,
					   GROUP_CONCAT(act_id SEPARATOR ', ') act_ids, 
					   COUNT(act_id) as cntActId
							   FROM account_trans_details atd
			   INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1
						   AND atd.adt_ledger_id IN (13) AND atd.adt_status=1
			   INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id IN (35) AND atd1.adt_active=1
						   AND (atd.adt_amount>0 AND atd1.adt_amount<0) AND act.act_date>='2021-04-01 00:00:00'
			   GROUP BY atd.adt_trans_ref_id
			   HAVING cntActId > 1
							   ) a ON a.adtref=bkg_id
			   WHERE 1";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['bkg_pickup_date'], 'defaultOrder' => 'bkg_pickup_date ASC'],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public function partnerCommMissing()
	{
		$sql = "SELECT bkg_id, bkg_pickup_date, bkg_agent_id, bkg_total_amount, bkg_net_advance_amount, commAmt, act_ids
				FROM booking
				INNER JOIN booking_invoice ON bkg_id=biv_bkg_id AND bkg_status IN (6,7) AND bkg_pickup_date>='2023-04-01 00:00:00' AND bkg_agent_id > 0 
				INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
				LEFT JOIN (
					SELECT atd.adt_trans_ref_id, SUM(IF(abs(atd.adt_amount)<=abs(atd1.adt_amount), atd.adt_amount*-1, atd1.adt_amount)) as commAmt, GROUP_CONCAT(act_id SEPARATOR ', ') as act_ids
					FROM account_trans_details atd
					INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 
						AND atd.adt_ledger_id IN (13) AND atd.adt_status=1
					INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id IN (35) AND atd1.adt_active=1
						AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) AND act.act_date>='2023-04-01 00:00:00'
					GROUP BY atd.adt_trans_ref_id
				) a ON a.adt_trans_ref_id=bkg_id
				WHERE a.adt_trans_ref_id IS NULL AND bkg_partner_commission >0";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['bkg_pickup_date'], 'defaultOrder' => 'bkg_pickup_date ASC'],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public function driverBonusList($type = false)
	{
		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";
		$drvcode	 = $this->drv_code;
		//$params		 = ['fromDate' => $fromDate, 'toDate' => $toDate, 'drvcode' => $drvcode];
		$where		 .= "atd.adt_active = 1 AND act.act_active=1 AND atd1.adt_active = 1 ";
		if ($fromDate != '' && $toDate != '')
		{
			$where .= " AND act.act_date BETWEEN '$fromDate' AND '$toDate'";
		}
		if ($drvcode != '')
		{
			$where .= " AND drivers.drv_code LIKE '%$drvcode%'";
		}
		$sql = "SELECT
				bkg.bkg_booking_id,bkg.bkg_id,
				bkg.bkg_bcb_id,
				ratings.rtg_customer_driver,
				drivers.drv_id,
				drivers.drv_name,
				drivers.drv_code,
				IF((SUM(atd.adt_amount)<0),(-1*SUM(atd.adt_amount)),SUM(atd.adt_amount)) bonus_amount,
				dad.dad_redeem_amount,
				act.act_date,
				IF(ctt.ctt_bank_name!='',ctt.ctt_bank_name,'') bank_name,
				IF(ctt.ctt_bank_branch!='',ctt.ctt_bank_branch,'') bank_branch,
				IF(ctt.ctt_beneficiary_id!='',ctt.ctt_beneficiary_id,'') beneficiary_id,
				IF(ctt.ctt_bank_account_no!='',ctt.ctt_bank_account_no,'') bank_account_no,
				IF(ctt.ctt_bank_ifsc!='',ctt.ctt_bank_ifsc,'') bank_ifsc,
				IF(ctt.ctt_beneficiary_name!='',ctt_beneficiary_name,'') beneficiary_name
			FROM
				account_trans_details atd
			INNER JOIN account_transactions act ON
				act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 40 AND atd.adt_type = 6
			INNER JOIN account_trans_details atd1 ON
				act.act_id = atd1.adt_trans_id AND atd1.adt_ledger_id = 41 AND atd1.adt_type = 1
			INNER JOIN booking bkg ON
				bkg.bkg_id = atd1.adt_trans_ref_id
			INNER JOIN ratings ON ratings.rtg_booking_id = bkg.bkg_id
			JOIN drivers ON atd.adt_trans_ref_id = drivers.drv_id
			JOIN drivers_add_details dad ON
				dad.dad_drv_id = drivers.drv_id AND dad.dad_active = 1
			JOIN contact_profile AS ctp
			ON
				ctp.cr_is_driver = drivers.drv_id AND ctp.cr_status = 1
			JOIN contact AS ctt
			ON
				ctt.ctt_id = ctp.cr_contact_id AND ctt.ctt_active = 1 AND ctt.ctt_id = ctt.ctt_ref_code
			LEFT JOIN contact_phone phn ON
				phn.phn_contact_id = ctt.ctt_id AND phn.phn_is_primary = 1 AND phn.phn_is_verified = 1 AND phn.phn_active = 1
			LEFT JOIN contact_email eml ON
				eml.eml_contact_id = ctt.ctt_id AND eml.eml_is_primary = 1 AND eml.eml_is_verified = 1 AND eml.eml_active = 1
			WHERE
				$where
			GROUP BY
				drivers.drv_id";
		if ($type == false)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				//'params'		 => $params,
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes' => ['act.act_date'], 'defaultOrder' => 'act.act_date DESC'],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql);
		}
	}

	public function accountingFlagClosedByAdminList($type = false)
	{
		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";
		if ($fromDate != '' && $toDate != '')
		{
			$where .= " AND blg_created BETWEEN '$fromDate' AND '$toDate'";
		}
		$sql = "SELECT 
					Date(blg_created) flagClosingDate,
					blg_user_id adminId,
					adm_user adminUserName,
					adm_email adminEmail,
					CONCAT(adm_fname,' ',adm_lname) adminName,
					count(*) totalFlagClosed,
					GROUP_CONCAT(DISTINCT(bkg.bkg_booking_id)) bookingIds 
				FROM booking_log
					INNER JOIN admins ON admins.adm_id  = blg_user_id AND blg_user_type = 4
					INNER JOIN booking bkg ON bkg.bkg_id = blg_booking_id
				WHERE blg_event_id = 66  AND blg_active = 1 
					$where
				GROUP by Date(blg_created),blg_user_id";
		if ($type == false)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes' => ['blg_created'], 'defaultOrder' => 'blg_created DESC'],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql);
		}
	}

	public static function getSecurityDepositMismatch()
	{
		$sql = "SELECT
					atd1.adt_trans_ref_id vndId,
					ROUND(SUM(atd.adt_amount)) accDeposit,
					vrs.vrs_security_amount vrsDeposit
				FROM
					account_trans_details atd
				INNER JOIN account_transactions act ON
					act.act_id = atd.adt_trans_id AND act.act_active = 1
				INNER JOIN account_trans_details atd1 ON
					atd1.adt_trans_id = act.act_id AND atd1.adt_ledger_id = 14 AND atd1.adt_active = 1 AND atd1.adt_type = 2
				INNER JOIN vendor_stats vrs ON
					vrs.vrs_vnd_id = atd1.adt_trans_ref_id AND vrs.vrs_vnd_id <> 43
				WHERE
					atd.adt_ledger_id = 34 AND atd.adt_active = 1
				GROUP BY
					atd1.adt_trans_ref_id
				HAVING
					(accDeposit * -1) <> vrsDeposit AND accDeposit < 0";

		return DBUtil::query($sql);
	}

	public function compensationAmount($date, $amount, $bkgId, $ptpId, $remarks = null, $apgModel = null, UserInfo $userInfo = null, $ldgId = null)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$transaction = DBUtil::beginTransaction();
		$returnSet	 = new ReturnSet();
		$ptpName	 = PaymentGateway::getPaymentTypeName($ptpId);
		Logger::trace("compensation amount: ptpname" . $ptpName);
		try
		{
			/* @var $apgModel PaymentGateway */
			$adtModels = [];

			$adtModels[] = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_COMPENSATION, $amount, $remarks);
			$refId		 = null;
			if ($apgModel != null)
			{
				$params					 = [];
				$params['blg_ref_id']	 = $apgModel->apg_id;
				$refId					 = $apgModel->apg_id;
				if ($remarks == null)
				{
					$remarks = $apgModel->apg_remarks;
				}
			}
			$bkgModel	 = Booking::model()->findByPk($bkgId);
			$ledgerId	 = PaymentType::getLedgerId($ptpId);
			$refType	 = Accounting::AT_ONLINEPAYMENT;
			if ($ledgerId == Accounting::LI_WALLET)
			{
				$refType = Accounting::AT_USER;
				$refId	 = $bkgModel->bkgUserInfo->bkg_user_id;
			}
			if ($ledgerId == Accounting::LI_GOZOCOINS)
			{
				$refType = Accounting::AT_USER;
				$refId	 = $bkgModel->bkgUserInfo->bkg_user_id;
			}
			if ($ptpId == PaymentType::TYPE_AGENT_CORP_CREDIT && $bkgModel->bkg_agent_id > 0)
			{
				$refId	 = $bkgModel->bkg_agent_id;
				$refType = Accounting::AT_PARTNER;
				//$updateStatus	 = $this::UpdateInactiveStatus($bkgId);
				//Logger::trace("refund booking: update inactive status" . $updateStatus);
			}
			$adtModels[] = AccountTransDetails::model()->initializeParams($refType, $refId, $ledgerId, -1 * $amount, $remarks);
			$res		 = $this->add($adtModels, $date, $amount, $bkgId, Accounting::AT_BOOKING, $remarks);
			if ($res)
			{
				$params					 = [];
				$params['blg_ref_id']	 = $res->act_id;
				$returnSet->setStatus(true);
				if ($apgModel != null)
				{
					$desc = "Compensation given against booking ({$apgModel->getPaymentType()} - {$apgModel->apg_code}) amount $amount";
				}
				else if ($ptpId == PaymentType::TYPE_GOZO_COINS)
				{
					$desc = "Compensation given Gozo coins against Booking, amount $amount";
				}
				if ($ptpId == PaymentType::TYPE_WALLET)
				{
					$success = UserWallet::add($refId, $amount);
					if (!$success)
					{
						$desc = "Failed to given compensation to user wallet";
						throw new Exception($desc);
					}
					$returnSet->setStatus(true);
					$desc = "Compensation given to user wallet Rs.$amount";
				}
				else
				{
					$desc = "Compensation given against Booking, amount $amount";
				}
//				$success = $bkgModel->addRefund($amount, $desc, $userInfo, $params);
				BookingLog::model()->createLog($bkgModel->bkg_id, $desc, $userInfo, BookingLog::COMPENSATION_PROCESS_COMPLETED, '', $params);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			Logger::exception($e);
			DBUtil::rollbackTransaction($transaction);
			if ($apgModel != null)
			{
				$desc = "Failed to add refund ({$apgModel->getPaymentType()} - {$apgModel->apg_code}) amount $amount ({$e->getMessage()})";
			}
			else
			{
				$desc = "Failed to add refund ({$e->getMessage()})";
			}
			Logger::trace("Failed to add refund " . $e->getMessage());
			throw new Exception($desc, $e->getCode());
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $returnSet;
	}

	public function getPartnerReceivableReports()
	{
		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";
		$partnerId	 = $this->bkg_agent_id;
		$params		 = ['fromDate' => $fromDate, 'toDate' => $toDate, 'partnerId' => $partnerId];

		if ($fromDate != '' && $toDate != '' && $partnerId != '')
		{
			$where .= " bkg.bkg_pickup_date BETWEEN :fromDate AND :toDate AND bkg.bkg_agent_id =:partnerId";
		}
		$sql			 = "SELECT
				DATE(bkg.bkg_pickup_date) pickupDate,bkg.bkg_agent_id,
				COUNT(DISTINCT bkg_id) totalcnt,
				SUM(
					IF(
						adt.adt_ledger_id = 49 AND bkg.bkg_status IN(3,5,6,7),
						adt.adt_amount,
						0
					)
				) partnerWalletUsedCompleted,
				SUM(
					IF(
						adt.adt_ledger_id = 49 AND bkg.bkg_status IN(9),
						adt.adt_amount,
						0
					)
				) partnerWalletUsedCanceled,
				 SUM(
					IF(
						adt.adt_ledger_id = 49 AND bkg.bkg_status IN(2),
						adt.adt_amount,
						0
					)
				) partnerWalletUsedNew,
				SUM(
					IF(
						adt.adt_ledger_id = 35,
						adt.adt_amount,
						0
					)
				) partnerCommission,
				SUM(adt.adt_amount) AS netReceivable
			FROM
				account_transactions act
			INNER JOIN account_trans_details adt ON
				adt.adt_trans_id = act.act_id AND adt.adt_ledger_id IN(49, 35) AND adt.adt_active = 1
			INNER JOIN account_trans_details adt1 ON
				adt1.adt_trans_id = act_id AND adt1.adt_ledger_id = 13 AND adt1.adt_active = 1
			INNER JOIN booking bkg ON
				bkg.bkg_id = adt1.adt_trans_ref_id AND bkg.bkg_status IN(2, 3, 5, 6, 7, 9)
			WHERE
				$where
				GROUP BY pickupDate";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_pickup_date'],
				'defaultOrder'	 => 'bkg_pickup_date  DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getPenaltyTypeReports()
	{
		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";
		$params		 = ['fromDate' => $fromDate, 'toDate' => $toDate];

		if ($fromDate != '' && $toDate != '')
		{
			$where .= "AND act.act_date BETWEEN :fromDate AND :toDate";
		}
		$sql			 = "SELECT temp.penaltyType,temp.pltDesc,SUM(temp.adtAmt) totalPenalty, COUNT(temp.penaltyType) totalCnt, temp.remarks FROM
				(
				SELECT
				JSON_VALUE(adt.adt_addt_params,'$.penaltyType') AS penaltyType,    
				adt.adt_amount adtAmt,
				adt.adt_remarks AS remarks,
				plt.plt_desc pltDesc,act.act_date
				FROM account_trans_details adt
				INNER JOIN account_transactions act ON act.act_id=adt.adt_trans_id AND adt.adt_ledger_id=28
				LEFT JOIN booking bkg ON bkg.bkg_id = adt.adt_trans_ref_id AND adt.adt_type = 1
				LEFT JOIN booking_cab bcb ON bcb.bcb_id = adt.adt_trans_ref_id AND adt.adt_type = 5
				INNER JOIN penalty_rules plt ON plt.plt_event_id = JSON_VALUE(adt.adt_addt_params,'$.penaltyType') AND plt.plt_active=1
				WHERE act.act_active=1 AND adt.adt_active=1 $where
				) temp
				WHERE 1 AND temp.penaltyType IS NOT NULL 
				GROUP BY  temp.penaltyType";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['act_date'],
				'defaultOrder'	 => 'act_date  DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getPenaltySummary($orderby = 'date', $type = '')
	{
		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";

		if ($fromDate != '' && $toDate != '')
		{
			$where .= "AND act.act_date BETWEEN '$fromDate' AND '$toDate'";
		}
		$dataSelect = "SELECT
				DATE_FORMAT(act.act_date, '%Y-%m-%d') AS date,	
				DATE_FORMAT(act.act_date, '%x-%v') AS week,	
				CONCAT(DATE_FORMAT(act.act_date, '%x-%v'), '\n',DATE_FORMAT(MIN(act.act_date), '%D %b'),' - ',DATE_FORMAT(MAX(act.act_date), '%D %b')) as weekLabel,
				DATE_FORMAT(act.act_date, '%b-%Y') AS monthname,	DATE_FORMAT(act.act_date, '%Y-%m') AS month, 
				'$orderby' groupType,
						    SUM(adt.adt_amount*-1) as totalPenalty,
							SUM(IF(JSON_VALUE(adt.adt_addt_params,'$.penaltyType')=203, adt.adt_amount*-1, 0)) AS appNotCompleted,
							SUM(IF(JSON_VALUE(adt.adt_addt_params,'$.penaltyType')=219, adt.adt_amount*-1, 0)) AS appNotUsed,
							SUM(IF(JSON_VALUE(adt.adt_addt_params,'$.penaltyType')=220, adt.adt_amount*-1, 0)) AS appNotArrived,
							SUM(IF(JSON_VALUE(adt.adt_addt_params,'$.penaltyType')=215, adt.adt_amount*-1, 0)) AS appNotStarted,
							SUM(IF(JSON_VALUE(adt.adt_addt_params,'$.penaltyType')=209, adt.adt_amount*-1, 0)) AS noShow,
							SUM(IF(JSON_VALUE(adt.adt_addt_params,'$.penaltyType')=201, adt.adt_amount*-1, 0)) AS notAllocated,
							SUM(IF(JSON_VALUE(adt.adt_addt_params,'$.penaltyType')=210, adt.adt_amount*-1, 0)) AS late,
							SUM(IF(JSON_VALUE(adt.adt_addt_params,'$.penaltyType')=212, adt.adt_amount*-1, 0)) AS unassign,
							SUM(IF(JSON_VALUE(adt.adt_addt_params,'$.penaltyType')=213, adt.adt_amount*-1, 0)) AS unregisteredcab";

		$countSelect = "SELECT  DATE_FORMAT(act.act_date, '%Y-%m-%d') AS date 
							,DATE_FORMAT(act.act_date, '%U-%Y') AS week
							,DATE_FORMAT(act.act_date, '%m-%Y') AS month ";
		$sqlBody	 = "
							FROM account_trans_details adt
			INNER JOIN account_transactions act ON act.act_id=adt.adt_trans_id AND adt.adt_ledger_id=28
			LEFT JOIN booking bkg ON bkg.bkg_id = adt.adt_trans_ref_id AND adt.adt_type = 1
			LEFT JOIN booking_cab bcb ON bcb.bcb_id = adt.adt_trans_ref_id AND adt.adt_type = 5
			INNER JOIN penalty_rules plt ON plt.plt_event_id = JSON_VALUE(adt.adt_addt_params,'$.penaltyType') AND plt.plt_active=1
			WHERE act.act_active=1 AND adt.adt_active=1 $where
			GROUP BY $orderby";
		$sqlData	 = $dataSelect . $sqlBody;
		$sqlCount	 = $countSelect . $sqlBody;

		if ($type == "command")
		{
			return DBUtil::query($sqlData, DBUtil::SDB());
		}
		else
		{

			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sqlData, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['date', 'totalPenalty', 'app', 'noShow', 'notAllocated', 'late', 'unassign'],
					'defaultOrder'	 => 'date  DESC'], 'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
	}

	public static function getPenaltyDetails($actId, $vndIds = '')
	{
		$params	 = ["actId" => $actId];
		$sql	 = "SELECT atd.adt_amount, act.act_id, act.act_date, act_remarks, atd.adt_trans_ref_id, atd.adt_type,atd.adt_trans_ref_id,act.act_created,
						atd.adt_addt_params, atd.adt_remarks
					FROM account_transactions act
					INNER JOIN account_trans_details atd ON atd.adt_trans_id = act.act_id  AND atd.adt_ledger_id=28
					INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id  AND atd1.adt_ledger_id=14
					WHERE act.act_active = 1 AND act.act_id=:actId AND atd.adt_amount<0";
		if ($vndIds != '')
		{
			$sql .= " AND atd1.adt_trans_ref_id IN ({$vndIds})";
		}

		$row = DBUtil::queryRow($sql, DBUtil::SDB(), $params);

		return $row;
	}

	public static function closeLedgerBalance($date, $ledgerId, $userInfo = null)
	{
		$params	 = ["date" => $date, "ledgerId" => $ledgerId];
		$sql	 = "SELECT atd1.adt_trans_ref_id, SUM(atd.adt_amount) as balance 
					FROM account_transactions act 
					INNER JOIN account_trans_details atd ON atd.adt_trans_id = act.act_id AND atd.adt_ledger_id=:ledgerId 
					INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id = 14 AND atd1.adt_type = 2 
					WHERE act_active=1 AND atd.adt_active=1 AND act_date<:date 
					GROUP BY atd1.adt_trans_ref_id 
					HAVING balance <> 0";
		$res	 = DBUtil::query($sql, DBUtil::SDB(), $params);

		foreach ($res as $row)
		{
			$amount	 = $row['balance'];
			$refType = 2; //$row['act_type'];
			$refId	 = $row['adt_trans_ref_id'];

			echo "\r\n RefType: {$refType}, RefId: {$refId}, Amount: {$amount}";

			$returnSet = self::addClosingOpening($date, $ledgerId, $amount, $refId, $refType, $userInfo);
			if (!$returnSet->getStatus() && $amount <> 0)
			{
				Logger::info(json_encode($returnSet));
			}
		}
	}

	public static function addClosingOpening($date, $ledgerId, $amount, $refId, $refType, $userInfo = null)
	{
		$returnSet = new ReturnSet();
		if ($amount == 0)
		{
			$returnSet->addError("0 closing balance. Cannot carry forward");
			goto result;
		}

		$transaction = DBUtil::beginTransaction();
		try
		{
			$data			 = [];
			$closingDate	 = new CDbExpression("DATE_SUB('{$date}', INTERVAL 1 SECOND)");
			$data["closing"] = self::addClosing($closingDate, $ledgerId, $amount, $refId, $refType, $userInfo);
			$data["opening"] = self::addOpening($date, $ledgerId, $amount, $refId, $refType, $userInfo);
			DBUtil::commitTransaction($transaction);

			echo " - DONE";
			$returnSet->setStatus(true);
			$returnSet->setData($data);
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($exc);
			echo " - ERROR";
		}
		result:
		return $returnSet;
	}

	public static function addClosing($date, $ledgerId, $amount, $refId, $refType, $userInfo = null)
	{
		if ($amount == 0)
		{
			return false;
		}

		$drLedgerId	 = $ledgerId;
		$crLedgerId	 = Accounting::LI_CLOSING;
		if ($amount > 0)
		{
			$crLedgerId	 = $ledgerId;
			$drLedgerId	 = Accounting::LI_CLOSING;
		}
		$remarks = "Closing balance";

		$accTransModel	 = self::getInstance($refType, $date, $amount, $remarks, $refId, $userInfo);
		$crTrans		 = AccountTransDetails::getInstance($crLedgerId, $refType, $refId, '', $remarks);
		$drTrans		 = AccountTransDetails::getInstance($drLedgerId, $refType, $refId, '', $remarks);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, $refType);

		if (!$status)
		{
			throw new Exception(json_encode($accTransModel->getErrors()), ReturnSet::ERROR_FAILED);
		}

		return $accTransModel;
	}

	public static function addOpening($date, $ledgerId, $amount, $refId, $refType, $userInfo = null)
	{
		if ($amount == 0)
		{
			return false;
		}

		$drLedgerId	 = $ledgerId;
		$crLedgerId	 = Accounting::LI_OPENING;
		if ($amount < 0)
		{
			$crLedgerId	 = $ledgerId;
			$drLedgerId	 = Accounting::LI_OPENING;
		}
		$remarks = "Opening balance";

		$accTransModel	 = self::getInstance($refType, $date, $amount, $remarks, $refId, $userInfo);
		$crTrans		 = AccountTransDetails::getInstance($crLedgerId, $refType, $refId, '', $remarks);
		$drTrans		 = AccountTransDetails::getInstance($drLedgerId, $refType, $refId, '', $remarks);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, $refType);

		if (!$status)
		{
			throw new Exception(json_encode($accTransModel->getErrors()), ReturnSet::ERROR_FAILED);
		}

		return $accTransModel;
	}

	/**
	 * 
	 * @param int $partnerId
	 * @param int $bkgId
	 * @param int $amount
	 * @param string $date
	 * @param int $refType
	 * @param int $refId
	 * @param string $remarks
	 * @param UserInfo $userInfo
	 * @param string $accStatus
	 * @return AccountTransactions
	 * @throws Exception
	 */
	public static function addClosingOpeningLedgerBalance($crRefId, $drRefId, $crRefType, $drRefType, $crLedgerId, $drLedgerId, $amount, $date, $remarks = "", $userInfo = null)
	{
		$accTransModel	 = self::getInstance($crRefType, $date, $amount, $remarks, $crRefId, $userInfo);
		$crTrans		 = AccountTransDetails::getInstance($crLedgerId, $crRefType, $crRefId, '', $remarks);
		$drTrans		 = AccountTransDetails::getInstance($drLedgerId, $drRefType, $drRefId, '', $remarks);
		$status			 = $accTransModel->processReceipt($drTrans, $crTrans, $crRefType);

		if (!$status)
		{
			throw new Exception("Unable to add balance", ReturnSet::ERROR_FAILED);
		}

		if ($accTransModel->hasErrors())
		{
			throw new Exception(json_encode($accTransModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		return $status;
	}

	public function archiveDataPartnerLedger($archiveDB, $upperLimit = 1, $lowerLimit = 1)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerLimit;
		while ($chk)
		{
			$transaction = "";
			try
			{
				$transaction = DBUtil::beginTransaction();

				// account_transactions

				$sql	 = "SELECT
								GROUP_CONCAT(temp.actid)
							FROM 
							(SELECT act.act_id AS actid
							FROM   account_trans_details adt 
							INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id IN(15) 
								AND adt.adt_type=3 
							WHERE  adt.adt_active = 1 AND act.act_active=1 AND act.act_date <= '2021-03-31 23:59:59' 
								AND adt.adt_trans_ref_id IS NOT NULL AND act.act_active IN(0,1) ORDER BY adt.adt_id ASC LIMIT 0,$limit) AS temp";
				$resQ	 = DBUtil::queryScalar($sql);

				// account_trans_details

				$sql2	 = "SELECT GROUP_CONCAT(adt.adt_id)
							FROM   account_trans_details adt 
							INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id 
							WHERE  act.act_id IN($resQ)";
				$resQ2	 = DBUtil::queryScalar($sql2);

				if (!is_null($resQ) && $resQ != '' && !is_null($resQ2) && $resQ2 != '')
				{
					echo $sql	 = "INSERT INTO " . $archiveDB . ".`account_transactions` (SELECT * FROM `account_transactions` WHERE act_id IN ($resQ))";
					$rows	 = DBUtil::command($sql)->execute();

					echo $sql2	 = "INSERT INTO " . $archiveDB . ".`account_trans_details` (SELECT * FROM `account_trans_details` WHERE adt_id IN ($resQ2))";
					$rows2	 = DBUtil::command($sql2)->execute();

					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `account_transactions` WHERE act_id IN ($resQ)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
					if ($rows2 > 0)
					{
						$sql2		 = "DELETE FROM `account_trans_details` WHERE adt_id IN ($resQ2)";
						$rowsDel2	 = DBUtil::command($sql2)->execute();
					}
				}
				DBUtil::commitTransaction($transaction);

				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
				echo $ex->getMessage() . "\n\n";
			}
		}
	}

	public function archiveDataPartnerWallet($archiveDB, $upperLimit = 100000, $lowerLimit = 1)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerLimit;
		while ($chk)
		{
			$transaction = "";
			try
			{
				$transaction = DBUtil::beginTransaction();

				// account_transactions

				$sql	 = "SELECT
								GROUP_CONCAT(temp.actid)
							FROM 
							(SELECT act.act_id AS actid
							FROM   account_trans_details adt 
							INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id IN(49) 
							WHERE  adt.adt_active = 1 AND act.act_active=1 AND act.act_date <= '2021-03-31 23:59:59' 
								AND adt.adt_trans_ref_id IS NOT NULL AND act.act_active IN(0,1) ORDER BY adt.adt_id ASC LIMIT 0,$limit) AS temp";
				$resQ	 = DBUtil::queryScalar($sql);

				// account_trans_details

				$sql2	 = "SELECT GROUP_CONCAT(adt.adt_id)
							FROM   account_trans_details adt 
							INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id 
							WHERE  act.act_id IN($resQ)";
				$resQ2	 = DBUtil::queryScalar($sql2);

				if (!is_null($resQ) && $resQ != '' && !is_null($resQ2) && $resQ2 != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".`account_transactions` (SELECT * FROM `account_transactions` WHERE act_id IN ($resQ))";
					$rows	 = DBUtil::command($sql)->execute();

					$sql2	 = "INSERT INTO " . $archiveDB . ".`account_trans_details` (SELECT * FROM `account_trans_details` WHERE adt_id IN ($resQ2))";
					$rows2	 = DBUtil::command($sql2)->execute();

					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `account_transactions` WHERE act_id IN ($resQ)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
					if ($rows2 > 0)
					{
						$sql2		 = "DELETE FROM `account_trans_details` WHERE adt_id IN ($resQ2)";
						$rowsDel2	 = DBUtil::command($sql2)->execute();
					}
				}
				DBUtil::commitTransaction($transaction);

				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
				echo $ex->getMessage() . "\n\n";
			}
		}
	}

	public function archiveDataVendorLedger($archiveDB, $upperLimit = 100000, $lowerLimit = 1)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerLimit;
		while ($chk)
		{
			$transaction = "";
			try
			{
				$transaction = DBUtil::beginTransaction();

				// account_transactions

				$sql	 = "SELECT
								GROUP_CONCAT(temp.actid)
							FROM 
							(SELECT act.act_id AS actid
							FROM   account_trans_details adt 
							INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id IN(14) AND adt.adt_type=2 
							WHERE  adt.adt_active = 1 AND act.act_active=1 AND act.act_date <= '2021-03-31 23:59:59' 
								AND adt.adt_trans_ref_id IS NOT NULL AND act.act_active IN(0,1) ORDER BY adt.adt_id ASC LIMIT 0,$limit) AS temp";
				$resQ	 = DBUtil::queryScalar($sql);

				// account_trans_details

				$sql2	 = "SELECT GROUP_CONCAT(adt.adt_id)
							FROM   account_trans_details adt 
							INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id 
							WHERE  act.act_id IN($resQ)";
				$resQ2	 = DBUtil::queryScalar($sql2);

				if (!is_null($resQ) && $resQ != '' && !is_null($resQ2) && $resQ2 != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".`account_transactions` (SELECT * FROM `account_transactions` WHERE act_id IN ($resQ))";
					$rows	 = DBUtil::command($sql)->execute();

					$sql2	 = "INSERT INTO " . $archiveDB . ".`account_trans_details` (SELECT * FROM `account_trans_details` WHERE adt_id IN ($resQ2))";
					$rows2	 = DBUtil::command($sql2)->execute();

					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `account_transactions` WHERE act_id IN ($resQ)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
					if ($rows2 > 0)
					{
						$sql2		 = "DELETE FROM `account_trans_details` WHERE adt_id IN ($resQ2)";
						$rowsDel2	 = DBUtil::command($sql2)->execute();
					}
				}
				DBUtil::commitTransaction($transaction);

				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
				echo $ex->getMessage() . "\n\n";
			}
		}
	}

	public function archiveDataByLedger($archiveDB, $ledger, $upperLimit = 500000, $lowerLimit = 500)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerLimit;
		while ($chk)
		{
			$transaction = "";
			try
			{
				$transaction = DBUtil::beginTransaction();

				// account_transactions
				$sql = "SELECT GROUP_CONCAT(DISTINCT temp.actid) FROM (
								SELECT act.act_id AS actid
								FROM account_trans_details adt 
								INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id = {$ledger}
								WHERE act.act_date < '2021-04-01 00:00:00' AND adt.adt_trans_ref_id > 0 
								ORDER BY act.act_id ASC LIMIT 0, $limit
							) AS temp";

//				$sql	 = "SELECT GROUP_CONCAT(DISTINCT temp.actid) FROM (
//								SELECT act.act_id AS actid 
//								FROM account_transactions act 
//								INNER JOIN account_trans_details atd ON atd.adt_trans_id = act.act_id AND atd.adt_ledger_id = 34 
//								INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id AND atd1.adt_ledger_id = 56 AND atd1.adt_type = 2 
//								WHERE 1 AND act_date<'2021-04-01 00:00:00' AND atd1.adt_trans_ref_id > 0 
//								ORDER BY act.act_id ASC LIMIT 0, $limit
//							) AS temp";
				$resQ = DBUtil::queryScalar($sql);

				if (is_null($resQ) || !$resQ || $resQ == '')
				{
					break;
				}

				// account_trans_details
				$sql2	 = "SELECT GROUP_CONCAT(adt.adt_id)
							FROM account_trans_details adt 
							INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id 
							WHERE act.act_id IN ($resQ)";
				$resQ2	 = DBUtil::queryScalar($sql2);

				if (!is_null($resQ) && $resQ != '' && !is_null($resQ2) && $resQ2 != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".`account_transactions` (SELECT * FROM `account_transactions` WHERE act_id IN ($resQ))";
					echo "\r\nrows = " . $rows	 = DBUtil::command($sql)->execute();

					$sql2	 = "INSERT INTO " . $archiveDB . ".`account_trans_details` (SELECT * FROM `account_trans_details` WHERE adt_id IN ($resQ2))";
					echo "\r\nrows2 = " . $rows2	 = DBUtil::command($sql2)->execute();

					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `account_transactions` WHERE act_id IN ($resQ)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
					if ($rows2 > 0)
					{
						$sql2		 = "DELETE FROM `account_trans_details` WHERE adt_id IN ($resQ2)";
						$rowsDel2	 = DBUtil::command($sql2)->execute();
					}
				}
				DBUtil::commitTransaction($transaction);

				$i += $limit;
				if (is_null($resQ) || !$resQ || ($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
				echo $ex->getMessage() . "\n\n";
			}
		}
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return type int
	 */
	public static function getCancellationCharge($bkgId)
	{
		$sql = "SELECT atd1.adt_amount FROM account_trans_details atd 
				INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id AND atd.adt_ledger_id=25 
				INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id 
				AND atd1.adt_ledger_id = 13 AND atd1.adt_trans_ref_id = :bkgId 
				WHERE atd1.adt_active = 1 AND atd.adt_active = 1 AND act.act_active = 1 
				AND atd1.adt_status = 1 AND atd.adt_status = 1";

		$cancellationCharge = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
		return $cancellationCharge;
	}

	public static function removeCompensationCharge($bkgId)
	{
		$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM account_trans_details atd
                INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id  AND atd.adt_ledger_id=27
                INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id AND atd1.adt_ledger_id = 14
                WHERE atd1.adt_active = 1 AND atd1.adt_status=1
                AND atd.adt_active = 1 AND atd.adt_status=1 
               AND act.act_active = 1 AND act.act_status=1
                AND atd.adt_trans_ref_id = :bkgId";
		$actIds	 = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
		if ($actIds != null && $actIds != '')
		{
			$rows = AccountTransactions::remove($actIds);
		}
		return $rows;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return type
	 */
	public static function removePartnerAdvance($bkgId)
	{
		$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM  account_trans_details atd
					INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id  AND atd.adt_active=1 AND act.act_active=1 AND atd.adt_ledger_id=49 AND atd.adt_amount > 0
					INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id  AND atd1.adt_active=1 AND atd1.adt_type=1 AND atd1.adt_ledger_id=13 AND atd1.adt_amount < 0
        INNER JOIN booking bkg ON bkg.bkg_id = atd1.adt_trans_ref_id 
        INNER JOIN booking_invoice bv On bv.biv_bkg_id = atd1.adt_trans_ref_id 
					WHERE atd1.adt_trans_ref_id=:bkgId AND atd.adt_id IS NOT NULL AND atd1.adt_id IS NOT NULL";
		$actIds	 = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
		if ($actIds != null && $actIds != '')
		{
			$rows = AccountTransactions::remove($actIds);
		}
		return $rows;
	}

	/**
	 * 
	 * @param type $actId
	 * @param type $vndId
	 * @return type
	 */
	public static function getBookingInfoById($actId, $vndId = 0)
	{
		$params	 = ['actId' => $actId];
		$where	 = '';
		if ($vndId > 0)
		{
			$params['vndId'] = $vndId;
			$where			 = " AND atd1.adt_trans_ref_id = :vndId ";
		}
		$sql	 = "SELECT atd.adt_trans_ref_id,atd.adt_type FROM `account_trans_details` atd 
			INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND act.act_status=1 
			INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id 
				AND atd1.adt_active = 1 AND atd1.adt_status=1 
				$where
				AND atd1.adt_ledger_id=14    
			WHERE atd.adt_active = 1 AND atd.adt_status=1  AND atd.adt_trans_id = :actId
				AND atd.adt_type IN (1,5)
				AND atd.adt_ledger_id=28";
		$bkgRow	 = DBUtil::queryRow($sql, null, $params);

		if (!$bkgRow)
		{
			throw new Exception("No booking info found for the transaction id", ReturnSet::ERROR_INVALID_DATA);
		}
		$refId	 = $bkgRow['adt_trans_ref_id'];
		$bkgId	 = null;
		if ($bkgRow['adt_type'] == 5)
		{
			$bkgIds	 = BookingCab::getBkgIdsById($refId);
			$bkgData = explode(',', $bkgIds);
			$bkgId	 = $bkgData[0];
		}
		else
		{
			$bkgId = $refId;
		}
		return $bkgId;
	}

	public static function getLastPaymentReceived($bkgId)
	{
		$sql	 = "SELECT adt1.adt_amount FROM `account_transactions` act 
				INNER JOIN account_trans_details adt ON adt.adt_trans_id = act.act_id AND adt.adt_ledger_id = 13
				INNER JOIN account_trans_details adt1 ON act.act_id = adt1.adt_trans_id AND adt1.adt_ledger_id = 47
				where act_type = 1 AND act_ref_id=:bkgId AND act.act_active = 1 AND act.act_status = 1 AND adt.adt_status = 1 
				AND adt.adt_active = 1 AND adt1.adt_status = 1 AND adt1.adt_active = 1
				ORDER BY `act_id` DESC LIMIT 1";
		$result	 = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
		return $result;
	}

	public static function getEntriesByBooking($bkgId)
	{
		if (empty($bkgId))
		{
			return '';
		}
		$atList		 = AccountTransDetails::getAccountTypeList();
		$atTypeCase	 = ' CASE adt_type';

		foreach ($atList as $key => $value)
		{
			$atTypeCase .= " WHEN $key THEN '$value' ";
		}
		$atTypeCase	 .= " END";
		$sql		 = "SELECT distinct adt_id, adt_trans_id, adt_trans_ref_id,
			concat(adt_ledger_id,' (',al.ledgerName,')') ledger, 
			concat(adt_type,' (',{$atTypeCase},')')  accountType,
			adt_remarks,adt_amount,adt_ref_id,adt_addt_params, adt_modified,
			adt_active
			from account_trans_details adt  
				JOIN account_ledger al ON al.ledgerId = adt.adt_ledger_id
				WHERE adt.adt_trans_id IN ( SELECT * from (
					SELECT adt_trans_id  FROM `account_trans_details` 
					WHERE adt_trans_ref_id = :bkgId 
					#AND adt_ledger_id IN (13,43) 
					AND adt_type = 1 
						UNION 
					SELECT  adt_trans_id  FROM `account_trans_details` 
					INNER JOIN payment_gateway pg ON pg.apg_booking_id = :bkgId 
					WHERE adt_trans_ref_id = pg.apg_id 
						AND adt_ledger_id = pg.apg_ledger_id 
						UNION 
					SELECT  adt_trans_id  FROM booking bkg  
					INNER JOIN `account_trans_details` at 
						ON at.adt_trans_ref_id = bkg.bkg_bcb_id 
					AND adt_ledger_id =28 AND adt_type = 5 
					WHERE  bkg_id=:bkgId  
						UNION 
					SELECT  adt_trans_id  FROM booking_cab bcb  
					INNER JOIN `account_trans_details` at 
						ON at.adt_trans_ref_id = bcb.bcb_id 
					AND adt_ledger_id =28 AND adt_type = 5 
					WHERE bcb.bcb_bkg_id1=:bkgId  
			) ad) 				
			ORDER by adt_modified,adt_id";
		$result		 = DBUtil::query($sql, null, ["bkgId" => $bkgId]);
		return $result;
	}

	public function getBookingAmountMismatchReports()
	{
		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";
		$params		 = ['fromDate' => $fromDate, 'toDate' => $toDate];

		if ($fromDate != '' && $toDate != '')
		{
			$where	 = "AND bkg_pickup_date BETWEEN :fromDate AND :toDate";
			$where1	 = "AND act.act_date >=:fromDate";
		}
		$sql			 = "SELECT bkg_id,bkg_booking_id,bkg_status,bkg_net_base_amount, bcb_vendor_id, bkg_pickup_date, bkg_agent_id, bkg_total_amount, (bkg_total_amount-bkg_net_advance_amount) as vendorCollected,
				bkg_net_advance_amount, bkg_vendor_collected, debited, DebitIds,IF(bcb_trip_type=0,'','Matched') tripType
				FROM booking
				INNER JOIN booking_invoice ON bkg_id=biv_bkg_id AND bkg_status IN (6,7) AND (bkg_total_amount-bkg_net_advance_amount) <> 0 $where
				INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
				LEFT JOIN (
					SELECT atd.adt_trans_ref_id, SUM(IF(abs(atd.adt_amount)<=abs(atd1.adt_amount), atd.adt_amount*-1, atd1.adt_amount)) as debited, 
						GROUP_CONCAT(act_id) as DebitIds
					FROM account_trans_details atd
					INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 
						AND atd.adt_ledger_id IN (13) AND atd.adt_status=1
					INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id IN (14) AND atd1.adt_active=1
						AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) $where1
					GROUP BY atd.adt_trans_ref_id
						  ) a ON a.adt_trans_ref_id=bkg_id
				WHERE (a.adt_trans_ref_id IS NULL)";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_pickup_date'],
				'defaultOrder'	 => 'bkg_pickup_date  DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getdriverCollectionMismatchReports()
	{
		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";
		$params		 = ['fromDate' => $fromDate, 'toDate' => $toDate];

		if ($fromDate != '' && $toDate != '')
		{
			$where = "AND bkg_pickup_date BETWEEN :fromDate AND :toDate";
		}
		$sql			 = "SELECT 
				bkg_id,bkg_status,bkg_pickup_date,bkg_booking_id,bkg_net_base_amount,bkg_agent_id, 
				bkg_total_amount, 
				bkg_net_advance_amount, 
				(bkg_total_amount - bkg_net_advance_amount) vendorCollected, 
				SUM(IF(abs(atd.adt_amount)<=abs(atd1.adt_amount), atd.adt_amount*-1, atd1.adt_amount)) as driverCollectAccountEntryAmt,
				biv.bkg_vendor_collected,
				biv.bkg_vendor_actual_collected,
				((bkg_total_amount - bkg_net_advance_amount) - (SUM(IF(abs(atd.adt_amount)<=abs(atd1.adt_amount), atd.adt_amount*-1, atd1.adt_amount)))) driverCollectionDiff
				FROM `booking` bkg 
				INNER JOIN booking_invoice biv ON biv.biv_bkg_id=bkg.bkg_id AND bkg.bkg_active=1 
				INNER JOIN account_trans_details atd ON atd.adt_ledger_id = 13 AND atd.adt_status=1 AND atd.adt_trans_ref_id = bkg.bkg_id AND atd.adt_active=1 AND atd.adt_status=1
				INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND act.act_status=1 
				INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id = 14 AND atd1.adt_active=1 
					AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
				WHERE 1 AND bkg.bkg_status IN (6,7) $where 
				GROUP BY bkg.bkg_id
				HAVING ((vendorCollected - driverCollectAccountEntryAmt) < 0 OR (vendorCollected - driverCollectAccountEntryAmt) > 0)";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_pickup_date'],
				'defaultOrder'	 => 'bkg_pickup_date  DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public static function getRemainingPenaltybyTransid($actId, $vndId = null, $isModify = false)
	{
		$relVndIds	 = \Vendors::getRelatedIds($vndId);
		$row		 = AccountTransactions::getPenaltyDetails($actId, $relVndIds);

		if (!$row)
		{
			throw new Exception(json_encode("Transaction not valid"), ReturnSet::ERROR_VALIDATION);
		}

		$refType		 = $row["adt_type"];
		$refId			 = $row["adt_trans_ref_id"];
		$penaltyAmount	 = $row['adt_amount'] * -1;
		$transParams	 = $row['adt_addt_params'];
		$remarks		 = $row['act_remarks'];
		$actCreateDate	 = $row['act_created'];
		$actDate		 = $row['act_date'];
		if ($actCreateDate <= '2022-11-01 00:00:00')
		{
			throw new Exception(json_encode("Unable to waiveOff this penalty "), 1);
		}
		$penaltyTypeArr = json_decode($transParams, true);
		if (isset($penaltyTypeArr["remarks"]))
		{
			$remarks = $penaltyTypeArr["remarks"];
		}

		Logger::trace("Trans Details: " . json_encode($row));

		$penaltyType = $penaltyTypeArr["penaltyType"];
		if (!$isModify)
		{
			$totalWaivedOff		 = $penaltyTypeArr['totalWaivedOff'] | 0;
			$remainingPenalty	 = $penaltyAmount - $totalWaivedOff;
			if ($remainingPenalty == 0)
			{
				throw new Exception(json_encode("Maximum penalty already waived off"), ReturnSet::ERROR_VALIDATION);
			}
			if ($remainingPenalty <= 0)
			{
				throw new Exception(json_encode("Penalty is exceeding the previous value."), ReturnSet::ERROR_VALIDATION);
			}
		}
		$row['penaltyType'] = $penaltyType;
		return $row;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $userId
	 * @param type $date
	 * @param type $amount
	 * @param type $remarks
	 * @param UserInfo $userInfo
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function addDboCompensationAmount($bkgId, $userId, $date, $amount, $remarks, $modelBkg, UserInfo $userInfo = null)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$transaction = DBUtil::beginTransaction();
		$returnSet	 = new ReturnSet();
		try
		{
			Logger::trace("add transaction START DBO ::bkg id: " . $bkgId);
			self::removeCompensationDBO($bkgId);
			$accTransModel	 = self::getInstance(Accounting::AT_BOOKING, $date, $amount, $remarks, $bkgId, null);
			$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_COMPENSATION, Accounting::AT_BOOKING, $bkgId, '', $remarks);
			$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_WALLET, Accounting::AT_USER, $userId, '', $remarks, null);
			$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);
			if (!$status)
			{
				throw new Exception("Unable to add compensation given to user account", ReturnSet::ERROR_FAILED);
			}
			Logger::trace("add transaction END DBO ::bkg id: " . $bkgId . "status: " . $status);
			//$sqlInvoice	 = "UPDATE  booking_invoice SET bkg_cust_compensation_amount = $amount WHERE biv_bkg_id = $bkgId";
			//$success	 = DBUtil::execute($sqlInvoice);
			$modelBkg->bkgInvoice->bkg_cust_compensation_amount	 = $amount;
			$success											 = $modelBkg->bkgInvoice->save();
			if (!$success)
			{
				throw new Exception("Unable to add compensation given to user wallet", ReturnSet::ERROR_FAILED);
			}
			Logger::trace("update invoice DBO ::bkg id: " . $bkgId . "success: " . $success);
			$returnSet->setStatus(true);
			BookingLog::model()->createLog($bkgId, $remarks, $userInfo, BookingLog::COMPENSATION_PROCESS_COMPLETED);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($ex);
			$ex->getMessage();
			Logger::trace("Failed to add DBO " . $ex->getMessage());
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $returnSet;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return type
	 */
	public static function removeCompensationDBO($bkgId)
	{
		$sql	 = "SELECT GROUP_CONCAT(act_id) as acts FROM  account_trans_details atd
					INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id  AND atd.adt_active=1 AND act.act_active=1 
						AND atd.adt_ledger_id=47 AND atd.adt_amount < 0 AND atd.adt_type=7
					INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id  AND atd1.adt_active=1 AND atd1.adt_type=1 
						AND atd1.adt_ledger_id=27 AND atd1.adt_amount > 0
					INNER JOIN booking bkg ON bkg.bkg_id = atd1.adt_trans_ref_id 
					INNER JOIN booking_invoice bv On bv.biv_bkg_id = atd1.adt_trans_ref_id 
					WHERE atd1.adt_trans_ref_id=:bkgId AND atd.adt_id IS NOT NULL AND atd1.adt_id IS NOT NULL";
		$actIds	 = DBUtil::queryScalar($sql, null, ["bkgId" => $bkgId]);
		if ($actIds != null && $actIds != '')
		{
			$rows = AccountTransactions::remove($actIds);
			if ($rows)
			{
				$sql1	 = "UPDATE booking_invoice SET bkg_cust_compensation_amount = 0 WHERE biv_bkg_id=:bkgId";
				DBUtil::execute($sql1, ["bkgId" => $bkgId]);
				$remarks = "Compensation removed from user wallet against booking id " . $bkgId;
				BookingLog::model()->createLog($bkgId, $remarks, $userInfo, BookingLog::COMPENSATION_REMOVE);
			}
		}
		return $rows;
	}

	/**
	 * 
	 * @param type $orderby
	 * @return \CSqlDataProvider
	 */
	public function getVendorDueSummary($orderby = 'week')
	{
		switch ($orderby)
		{
			case "date":
				$dateField		 = "DATE_FORMAT(act_date, '%Y-%m-%d')";
				$displayField	 = "dateField";
				break;
			case "month":
				$dateField		 = "DATE_FORMAT(act_date, '%Y-%m')";
				$displayField	 = "dateField";
				break;
			case "week":
			default:
				$dateField		 = "DATE_FORMAT(DATE_ADD(act_date, INTERVAL -3 DAY), '%Y-%v')";
				$displayField	 = "CONCAT(dateField, '\n',DATE_FORMAT(MIN(minDate), '%D %b'),' - ',DATE_FORMAT(MAX(maxDate), '%D %b'))";
				break;
		}

		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";

		if ($fromDate != '' && $toDate != '')
		{
			$where .= "act.act_date BETWEEN '$fromDate' AND '$toDate'";
		}
		$dataSelect = "SELECT
				$dateField AS groupByField,	
				$displayField AS groupByDisplayField,	
				'$orderby' as groupType,
						CONCAT(MIN(minDate), ' - ', MAX(maxDate)) as dateRange, 
						SUM(IF(NetAmount<0, NetAmount,0)) as payable, 
                        SUM(IF(NetAmount>0, NetAmount,0)) as receivable, 
                        SUM(NetAmount) as netDue,
						SUM(NetPaid) as totalPaid";

		$countSelect = "SELECT  $dateField AS groupByField ";
		$sqlBody	 = "
						FROM (
						SELECT atd.adt_trans_ref_id,act.act_date, 
							$dateField as dateField, 
							MIN(date(act_date)) as minDate, 
							max(date(act_date)) as maxDate,
							SUM(IF(atd1.adt_ledger_id NOT IN (23,29,30,1,54), IF(abs(atd.adt_amount)>abs(atd1.adt_amount), atd1.adt_amount *-1, atd.adt_amount),0)) as NetAmount,
							SUM(IF(atd1.adt_ledger_id IN (23,29,30,1,54), IF(abs(atd.adt_amount)>abs(atd1.adt_amount), atd1.adt_amount *-1, atd.adt_amount),0)) as NetPaid						
						FROM account_trans_details atd 
						INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_ledger_id IN (14)
						INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_id<>atd.adt_id AND atd1.adt_active=1
						WHERE $where AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0))
								AND atd1.adt_ledger_id IN (22,27,28,13,34,55,53,37,38,20,29,30,23,54)
						GROUP BY atd.adt_trans_ref_id, dateField) a 
						GROUP BY groupByField";

		$sqlData	 = $dataSelect . $sqlBody;
		$sqlCount	 = $countSelect . $sqlBody;

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sqlData, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['groupByField', 'payable', 'receivable', 'netDue', 'totalPaid'],
				'defaultOrder'	 => 'groupByField  DESC'
			],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	/**
	 *  this will give  the total amount of money sent back to wallet for any particular booking
	 * @param type $bkgId
	 * @return type int
	 */
	public static function getTotalWalletAmountRefunded($bkgId)
	{
		$sql = "SELECT 
					SUM(adt.adt_amount) as amount,
					payment_gateway.apg_mode
				FROM   account_trans_details adt
					INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id       
					INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id
					INNER JOIN account_ledger al ON al.ledgerId = adt.adt_ledger_id
					LEFT JOIN payment_gateway on payment_gateway.apg_booking_id=act.act_ref_id AND payment_gateway.apg_mode=1 AND payment_gateway.apg_active=1 AND payment_gateway.apg_status=1
				WHERE  1 
					AND act.act_active=1 
					AND adt.adt_active = 1
					AND adt.adt_status = 1 
					AND act.act_type = 1
					AND act.act_status = 1 
					AND act.act_ref_id =:bkgId
					AND al.accountGroupId IN (27,28)
					AND adt.adt_ledger_id = 47 
					AND adt.adt_amount<0 
					AND atd1.adt_ledger_id IN(13)
					AND atd1.adt_active = 1 
					AND atd1.adt_status = 1 
					AND atd1.adt_amount>0";
		return DBUtil::queryRow($sql, null, ["bkgId" => $bkgId]);
	}

	/**
	 * 
	 * @param type $agentId
	 * @return type
	 */
	public static function getLastPaymentReceivedDate($agentId)
	{
		$sql	 = "SELECT
				DATE(MAX(act.act_date)) AS lastPaymentReceivedDate
			FROM
				account_trans_details adt
			INNER JOIN account_transactions act ON
				act.act_id = adt.adt_trans_id AND act.act_active = 1 AND act.act_status = 1
			INNER JOIN account_trans_details adt1 ON
				adt1.adt_trans_id = act.act_id AND adt1.adt_active = 1 AND adt1.adt_status = 1 AND adt1.adt_ledger_id IN(23, 29, 30)
			WHERE
				adt.adt_ledger_id = 15 AND adt.adt_trans_ref_id =:agentId AND adt.adt_active = 1 AND adt.adt_status = 1 AND adt.adt_amount < 0 AND adt1.adt_amount > 0 AND adt.adt_id <> adt1.adt_id
			GROUP BY
				adt.adt_trans_ref_id";
		$return	 = DBUtil::queryScalar($sql, null, ["agentId" => $agentId]);
		return $return;
	}
}
