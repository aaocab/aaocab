<?php

/**
 * This is the model class for table "transactions".
 *
 * The followings are the available columns in table 'transactions':
 * @property integer $trans_id
 * @property integer $trans_ptp_id
 * @property integer $trans_booking_id
 * @property integer $trans_code
 * @property integer $trans_type
 * @property integer $trans_mode
 * @property string $trans_remarks
 * @property string $trans_ipaddress
 * @property string $trans_device_detail
 * @property integer $trans_user_type
 * @property integer $trans_user_id
 * @property double $trans_amount
 * @property integer $trans_active
 * @property integer $trans_status
 * @property string $trans_start_datetime
 * @property string $trans_response_details
 * @property string $trans_response_code
 * @property string $trans_response_message
 * @property string $trans_txn_id
 *  @property string $trans_merchant_ref_id
 * @property string $trans_ref_id
 * @property string $trans_complete_datetime
 */
class Transactions extends CActiveRecord
{

	public $trans_date1,
			$trans_date2, $trans_mode1, $trans_ptp1,
			$trans_booking, $refcode,
			$trans_user, $trans_ptp, $trans_stat,
			$trans_active_chkbox, $refundOrderCode,
			$trans_ref_code, $trans_desc,
			$bank_chq_no, $bank_chq_dated, $bank_name, $bank_ifsc, $bank_branch,
			$bank_trans_type,
			$bank_trans_id,
			$trans_inactive_chkbox, $trans_booking_code_id, $cash_received_by,
			$trans_status_chkbox;
	public $bankTransType	 = [1 => 'Cash', 2 => 'Cheque', 3 => 'NEFT/RTGS'];
	public $modeList		 = [1 => 'Debit', 2 => 'Credit'];
	public $modeList1		 = [1 => 'Refund (Debit)', 2 => 'Receive (Credit)'];
	public $trans_bkhash, $ebs_description,
			$ebs_name, $ebs_address, $totRefund, $ebsopt,
			$ebs_country, $ebs_state, $ebs_city,
			$ebs_postal_code, $ebs_phone, $ebs_email;

	const MODE_DEBIT			 = 1;
	const MODE_CREDIT			 = 2;
	const TXN_STATUS_OPEN		 = 0;
	const TXN_STATUS_SUCCESS	 = 1;
	const TXN_STATUS_FAILED	 = 2;
	const TXN_STATUS_PENDING	 = 0;

	public function getStatusDesc($value)
	{
		switch ($value)
		{
			case 1:
				$desc	 = "success";
				break;
			case 2:
				$desc	 = "failure";
				break;
			case 0:
			default:
				$desc	 = "pending";
				break;
		}
		return $desc;
	}

	public function getbankTransTypeList($mode = 0)
	{
		$modeList = $this->bankTransType;
		if ($mode > 0)
		{
			return $modeList[$mode];
		}
		return $modeList;
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

	public function getModeList1($mode = 0)
	{
		$modeList = $this->modeList1;
		if ($mode > 0)
		{
			return $modeList[$mode];
		}
		return $modeList;
	}

	public function getTransMode()
	{
		return ['1' => 'refund', '2' => 'payment'];
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transactions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('trans_amount', 'required'),
			['ebs_name,ebs_address,ebs_city,ebs_country,ebs_postal_code,ebs_country,ebs_phone,ebs_email', 'required', 'on' => 'ebs_payment'],
			array('trans_ptp_id, trans_booking_id, trans_type, trans_mode, trans_user_type, trans_user_id, trans_active, trans_status,trans_code', 'numerical', 'integerOnly' => true),
			array('trans_amount,ebs_postal_code', 'numerical'),
			array('ebs_email', 'email'),
			['trans_booking_code_id,trans_ptp1,trans_mode1,	trans_ref_code,trans_desc', 'required', 'on' => 'trans_create'],
			['trans_booking_code_id,trans_ptp1', 'required', 'on' => 'trans_refund,trans_payment'],
			['trans_booking_code_id,trans_ptp,trans_ptp1,bank_trans_type,cash_received_by,trans_amount,
			bank_chq_no,bank_name,bank_ifsc,bank_branch,bank_chq_dated,trans_mode,trans_mode1,bank_trans_id,
			trans_ref_code,trans_desc', 'safe'],
			array('ebs_postal_code', 'length', 'max' => 6, 'min' => 6,),
			array('trans_remarks, trans_ipaddress', 'length', 'max' => 100),
			array('trans_device_detail', 'length', 'max' => 1000),
			array('trans_start_datetime,ebs_name,ebs_address,ebs_city,ebs_state,trans_merchant_ref_id,trans_response_details,trans_code,ebs_country,ebs_postal_code,ebs_country,ebs_phone,ebs_email,trans_ref_id,trans_response_details, trans_response_code, trans_response_message, trans_txn_id,ebsopt', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('trans_id, trans_ptp_id, trans_booking_id,trans_ref_id,trans_merchant_ref_id, trans_type, trans_mode, trans_remarks, trans_ipaddress, trans_device_detail, trans_user_type, trans_user_id, trans_amount, trans_active, trans_status, trans_start_datetime, trans_complete_datetime,trans_response_details,trans_date1,trans_date2', 'safe', 'on' => 'search'),
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
			'tranbooking' => array(self::BELONGS_TO, 'Booking', 'trans_booking_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'trans_id'					 => 'Id',
			'trans_ptp_id'				 => 'Payment Type',
			'trans_ptp'					 => 'Payment Type',
			'trans_booking_id'			 => 'Booking ID',
			'trans_type'				 => 'Type',
			'trans_mode'				 => 'Mode',
			'trans_remarks'				 => 'Remarks',
			'trans_ipaddress'			 => 'IP Address',
			'trans_device_detail'		 => 'Device Detail',
			'trans_user_type'			 => 'User Type',
			'trans_user_id'				 => 'User',
			'trans_amount'				 => 'Amount',
			'trans_active'				 => 'Active',
			'trans_status'				 => 'Status',
			'trans_txn_id'				 => 'Payment Transaction ID',
			'trans_start_datetime'		 => 'Start Datetime',
			'trans_complete_datetime'	 => 'Complete Datetime',
			'trans_date1'				 => 'Transaction From Date',
			'trans_date2'				 => 'Transaction To Date',
			'trans_booking'				 => 'Booking ID',
			'trans_user'				 => 'User Name',
			'trans_status_chkbox'		 => 'Status',
			'trans_active_chkbox'		 => 'Active',
			'trans_inactive_chkbox'		 => 'Show Inactive',
			'trans_code'				 => 'Code',
			'trans_booking_code_id'		 => 'Enter Booking Id',
			'trans_ref_code'			 => 'Reference Code',
			'ebs_name'					 => 'Name',
			'ebs_address'				 => 'Address',
			'ebs_city'					 => 'City',
			'ebs_country'				 => 'Country',
			'ebs_postal_code'			 => 'Postal Code',
			'ebs_country'				 => 'Country',
			'ebs_phone'					 => 'Phone',
			'ebs_email'					 => 'Email',
			'bank_ifsc'					 => 'Bank IFSC Code',
			'bank_branch'				 => 'Branch Name',
			'bank_trans_id'				 => 'Transaction Id',
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

		$criteria->compare('trans_id', $this->trans_id);
		$criteria->compare('trans_ptp_id', $this->trans_ptp_id);
		$criteria->compare('trans_booking_id', $this->trans_booking_id);
		$criteria->compare('trans_type', $this->trans_type);
		$criteria->compare('trans_mode', $this->trans_mode);
		$criteria->compare('trans_remarks', $this->trans_remarks, true);
		$criteria->compare('trans_ipaddress', $this->trans_ipaddress, true);
		$criteria->compare('trans_device_detail', $this->trans_device_detail, true);
		$criteria->compare('trans_user_type', $this->trans_user_type);
		$criteria->compare('trans_user_id', $this->trans_user_id);
		$criteria->compare('trans_amount', $this->trans_amount);
		$criteria->compare('trans_active', $this->trans_active);
		$criteria->compare('trans_status', $this->trans_status);
		$criteria->compare('trans_start_datetime', $this->trans_start_datetime, true);
		$criteria->compare('trans_complete_datetime', $this->trans_complete_datetime, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Transactions the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

//	public function afterFind() {
//		parent::afterFind();
//		if ($this->trans_amount) {
//			$this->trans_amount = round($this->trans_amount);
//		}
//	}

	public function afterSave()
	{
		parent::afterSave();
		//$scenario = $this->scenario;
		if ($this->isNewRecord)
		{
			$this->setIsNewRecord(false);
			$this->trans_code = $this->generateNewTransCode();
			$this->update();
		}
	}

	public function getTodaysCount()
	{
		$cdb		 = Yii::app()->db->createCommand();
		$cdb->select = "COUNT(*) as cnt";
		$cdb->from	 = $this->tableName();
		$cdb->where	 = 'date(trans_start_datetime) = CURDATE()';
		$cnt		 = $cdb->queryScalar();
		return $cnt;
	}

	public function generateTransCode()
	{
		$todayCount	 = $this->getTodaysCount();
		$appendValue = $todayCount + 1;
		$transCode	 = date('ymdHis') . str_pad($appendValue, 3, 0, STR_PAD_LEFT);
		return $transCode;
	}

	public function generateNewTransCode()
	{
		$appendValue = $this->trans_id;
		$transCode	 = date('ymd') . str_pad($appendValue, 9, 0, STR_PAD_LEFT);
		return $transCode;
	}

	public function getByCode($code)
	{
		if ($code)
		{
			$criteria	 = new CDbCriteria();
			$criteria->compare('trans_code', $code);
			$transModel	 = $this->find($criteria);
			return $transModel;
		}
		return false;
	}

	public function getPaymentType()
	{
		$ptpTypeList = PaymentType::model()->getList();
		return $ptpTypeList[$this->trans_ptp_id];
	}

	public function getPayment($type)
	{
		$ptpTypeList = PaymentType::model()->getList();
		return $ptpTypeList[$type];
	}

	public function udpdateCMDResponseByCodeForEBS($response, $success = 0)
	{
		$responseArr					 = json_decode($response, true);
		$this->trans_response_code		 = $responseArr['errorCode'];
		$this->trans_response_message	 = $responseArr['error'] . $responseArr['status'];
		$this->trans_txn_id				 = $responseArr['transactionId'];
		$this->trans_merchant_ref_id	 = $responseArr['referenceNo'];
		$this->trans_response_details	 = $response;
		$diff							 = floor(( time() - strtotime($this->trans_start_datetime)) / 3600);
		if ($responseArr['status'] == 'Captured' || $success == 1 || $responseArr['status'] == 'Refunded' || $responseArr['status'] == 'Authorized')
		{
			$this->trans_status = 1;
		}
		else if ($responseArr['status'] != 'Captured' && $responseArr['status'] != 'Refunded' || $responseArr['status'] != 'Authorized')
		{
			$this->trans_status = 2;

			$params['blg_ref_id'] = $this->trans_id;
			if ($this->trans_ref_id > 0)
			{
				BookingLog::model()->createLog($this->trans_booking_id, "Refund process failed ({$this->getPaymentType()} - {$this->trans_code})", UserInfo::model(), BookingLog::REFUND_PROCESS_FAILED, '', $params);
			}
			else
			{
				BookingLog::model()->createLog($this->trans_booking_id, "Online payment failed ({$this->getPaymentType()} - {$this->trans_code})", UserInfo::model(), BookingLog::PAYMENT_FAILED, '', $params);
			}
		}
		$this->trans_complete_datetime = new CDbExpression('NOW()');

		if ($this->save())
		{
			return true;
		}
		else
		{
			$emailCom = new emailWrapper();
			$emailCom->paymentFailedAlert($this->trans_booking_id, $this->trans_response_message);
			return FALSE;
		}
	}

	public function getOldCodebynewCode($newtranscode)
	{
		$model		 = $this->getByCode($newtranscode);
		$response	 = $model->trans_response_details;
		$resArr		 = json_decode($response, true);

		return $resArr['referenceNo'];
	}

	public function getCodebyRefid($refid)
	{
		$code = $this->getCodebyid($refid);


		return $code;
	}

//    public function paymentCreditsUsed($bkgid, $ptpid, $amount, $platform = 1, $oldApp = false, $editAccount = false)
//    {
//	$sucess		 = true;
//	$bkgmodel	 = Booking::model()->findByPk($bkgid);
//	$preCreditsUsed	 = $bkgmodel->bkg_credits_used;
//	//dsf
//	$bookModel	 = clone $bkgmodel;
//	if ($bookModel->bkg_convenience_charge > 0)
//	{
//	    $bookModel->calculateConvenienceFee(0);
//	    $bookModel->calculateTotal();
//	}
//	$percent30ofAmt = round($bookModel->bkg_total_amount * 0.3);
//	if ($amount > $bookModel->bkg_total_amount)
//	{
//	    $amount = $bookModel->bkg_total_amount;
//	}
//	$amountcredit				 = $amount;
//	//sfsf
//	$credits				 = ($bkgmodel->bkg_credits_used > 0) ? ($bkgmodel->bkg_credits_used + $amount) : $amount;
//	$bkgmodel->bkg_credits_used		 = $credits;
//	$bkgmodel->bkg_due_amount		 = $bkgmodel->bkg_total_amount - $bkgmodel->getTotalPayment();
//	$bkgmodel->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
//	$usePromo				 = ($bkgmodel->bkg_promo_code == '');
//	if ($oldApp)
//	{
//	    $usePromo = true;
//	}
//
//	$totalCredits = UserCredits::getApplicableCredits($bkgmodel->bkg_user_id, $bookModel->bkg_total_amount, $usePromo, $bookModel->bkg_from_city_id, $bookModel->bkg_to_city_id);
//
//	$userCredits = UserCredits::model()->getMaxCreditsByUser($bkgmodel->bkg_user_id, $bookModel->bkg_total_amount, $usePromo);
//	if ($amount <= $totalCredits['credits'] && $amount != 0 && count($userCredits) > 0)
//	{
//
//
//	    $todayCount	 = $this->getTodaysCount();
//	    $appendValue	 = $todayCount + 1;
//	    //$ptpModel = PaymentType::model()->findByPk($ptpid);
//
//	    foreach ($userCredits as $value)
//	    {
//		if ($amount != 0)
//		{
//		    $model			 = new Transactions();
//		    $model->trans_ptp_id	 = $ptpid;
//		    $model->trans_amount	 = $value->CreditVal;
//		    if ($amount <= $value->CreditVal)
//		    {
//			$model->trans_amount = $amount;
//		    }
//		    $model->trans_user_id		 = $bkgmodel->bkg_user_id;
//		    $model->trans_booking_id	 = $bkgid;
//		    $model->trans_code		 = $this->generateTransCode();
//		    $model->trans_start_datetime	 = new CDbExpression('NOW()');
//		    $model->trans_ipaddress		 = \Filter::getUserIP();
//		    $model->trans_device_detail	 = UserLog::model()->getDevice();
//		    $model->trans_mode		 = 2;
//		    if ($desc == "")
//		    {
//			$desc = '{"TRANSACTION_MODE":2,"DESCRIPTION":"Credits used by user successfully","STATUS":"SUCCESS"}';
//		    }
//		    $model->trans_response_details	 = $desc;
//		    $model->trans_desc		 = $desc['DESCRIPTION'];
//		    $model->trans_status		 = 1;
//		    $model->trans_ref_id		 = $value->ucr_id;
//		    if ($model->save())
//		    {
//
//			$userCredits = UserCredits::model()->findByPk($value->ucr_id);
//			if ($amount <= $value->CreditVal)
//			{
//			    $userCredits->ucr_used = $userCredits->ucr_used + $amount;
//			}
//			else
//			{
//			    $userCredits->ucr_used = $userCredits->ucr_used + $value->CreditVal;
//			}
//			$sucess = $userCredits->update();
//			if ($amount <= $value->CreditVal)
//			{
//			    $amount = 0;
//			}
//			else
//			{
//			    $amount = $amount - $value->CreditVal;
//			}
//		    }
//		    else
//		    {
//			$sucess = false;
//		    }
//		}
//	    }
//	    if ($sucess)
//	    {
//
//		$eventid		 = BookingLog::BOOKING_PROMO;
//		$params['blg_ref_id']	 = BookingLog::REF_PROMO_GOZOCOINS_APPLIED;
//		$userId			 = $bkgmodel->bkg_user_id;
//		if ($editAccount)
//		{
//		    $userId = Yii::app()->user->getId();
//		}
//
////                $eventid = BookingLog::BOOKING_PROMO;
////                $params['blg_ref_id'] = BookingLog::REF_PROMO_GOZOCOINS_APPLIED;
//
//		if ($amountcredit >= $percent30ofAmt)
//		{
//		    $bkgmodel->calculateConvenienceFee(0);
//		    $bkgmodel->calculateTotal();
//		    $bkgmodel->calculateVendorAmount();
//		}
//
//		//reduce vendor amount
//		$cabmodel = $bkgmodel->getBookingCabModel();
//		if ($cabmodel != '' && $cabmodel->bcb_trip_type != 1)
//		{
//		    if ($bkgmodel->bkg_vendor_amount != '' && ($bkgmodel->bkg_status == 1 || $bkgmodel->bkg_status == 2) && ($preCreditsUsed == 0 || $preCreditsUsed == '') && ($bkgmodel->bkg_advance_amount == 0 || $bkgmodel->bkg_advance_amount == ''))
//		    {
//			$bkgmodel->bkg_vendor_amount	 = round($bkgmodel->bkg_vendor_amount * 0.97);
//			$bkgmodel->calculateVendorAmount();
//			$cabmodel->bcb_vendor_amount	 = $bkgmodel->bkg_vendor_amount;
//			$cabmodel->save();
//		    }
//		}
//
//		$sucess = $bkgmodel->update();
//	    }
//	}
//	return $sucess;
//    }

	public function getTransactionsbypmtid($ptpid)
	{
		$criteria			 = new CDbCriteria();
		$criteria->condition = " (trans_response_details IS NULL OR trans_response_details = '') AND  (trans_ptp_id = '$ptpid')";
		$criteria->order	 = 'trans_start_datetime DESC';
		$criteria->limit	 = 1;
		$models				 = $this->findAll($criteria);
		return $models;
	}

	public function getByBookingID($bkgId)
	{
		$models = $this->findAll("trans_booking_id=$bkgId");
		return $models;
	}

	public function mapping(array $models)
	{
		$plist	 = PaymentType::model()->getList();
		$arr	 = [];
		foreach ($models as $model)
		{
			/* @var $model Transactions */
			$obj				 = new stdClass();
			$obj->code			 = $model->trans_code;
			$obj->gateway		 = $plist[$model->trans_ptp_id];
			$obj->amount		 = $model->trans_amount;
			$obj->initiateTime	 = $model->trans_start_datetime;
			$obj->completeTime	 = $model->trans_complete_datetime;
			$obj->status		 = $this->getStatusDesc($model->trans_status);
			$arr[]				 = $obj;
		}
		return $arr;
	}

	public function getEmptystatus($bkgid = '')
	{

		$condition = "";
		if ($bkgid != '')
		{
			$condition = " AND trans_booking_id='{$bkgid}'";
		}
		$criteria			 = new CDbCriteria();
		$criteria->select	 = '*,
         IF(trans_mode = 1,
            (SELECT trans_code
             FROM   transactions t2
             WHERE  t2.trans_id = t.trans_ref_id),
            0)
           refundOrderCode';
		$criteria->condition = " (trans_response_details IS NULL OR trans_response_details = '')  "
				. " AND trans_status = 0 " . $condition
				. " AND trans_start_datetime>DATE_SUB(NOW(), INTERVAL 60 DAY) AND trans_start_datetime < DATE_SUB(NOW(), INTERVAL 25 MINUTE)";
		$criteria->addNotInCondition('trans_ptp_id', [1, 2, 5, 7, 8]);
		$criteria->order	 = 'trans_start_datetime DESC';
		// SELECT * from transactions
		// where  (trans_response_details IS NULL OR trans_response_details = '')
		// AND trans_status = 0
		// AND trans_start_datetime < DATE_SUB(NOW(), INTERVAL 30 MINUTE)
		// AND (trans_ptp_id NOT IN (1,2,5,7,8))
		// ORDER BY trans_start_datetime DESC
		$models				 = $this->findAll($criteria);
		return $models;
	}

	public function getUnlinkedTransactionByEventid($evtid)
	{
		return DBUtil::command("SELECT IFNULL(SUM(trans_amount),0) FROM transactions WHERE trans_active=1 AND trans_status=1 AND trans_ptp_id<>5 AND trans_booking_id = " . $bkgId)->queryScalar();
	}

	/** 
	 * 
	 * @param array $responseArr
	 * @param string $transCode
	 * @param integer $ptpid
	 */
	// not in use
	public function updatePaymentStatus($responseArr = [], $transCode, $ptpid)
	{
		if ($ptpid = PaymentType::TYPE_PAYTM)
		{
			$transStatus	 = $responseArr['STATUS'];
			$resCode		 = $responseArr['RESPCODE'];
			$response		 = json_encode($responseArr);
			$transModel		 = Transactions::model()->getByCode($transCode);
			$result['bkid']	 = $transModel->trans_booking_id;
			$result['tinfo'] = $transCode;
			if ($responseArr['STATUS'] == 'TXN_SUCCESS')
			{
				if ($transModel->trans_ref_id > 0 && $transModel->trans_amount < 0 && $transModel->trans_mode == 1)
				{
					Booking::model()->updateRefund($transCode, $response);
				}
				else if ($transModel->trans_amount > 0 && $transModel->trans_mode == 2)
				{
					Booking::model()->updateAdvance($transCode, $response);
				}
			}
			elseif ($responseArr['STATUS'] == 'TXN_FAILURE' || (isset($responseArr['ErrorCode']) && $responseArr['ErrorCode'] > 0))
			{
				$result['success'] = false;
				$transModel->udpdateResponseByCode($response, 2);
				if ($transModel)
				{
					$params['blg_ref_id'] = $transModel->trans_id;
					if ($transModel->trans_ref_id > 0 && $transModel->trans_amount < 0 && $transModel->trans_mode == 1)
					{
						BookingLog::model()->createLog($transModel->trans_booking_id, "Refund process failed ({$transModel->getPaymentType()} - {$transModel->trans_code})", UserInfo::model(), BookingLog::REFUND_PROCESS_FAILED, '', $params);
					}
					else if ($transModel->trans_amount > 0 && $transModel->trans_mode == 2)
					{
						BookingLog::model()->createLog($transModel->trans_booking_id, "Online payment failed ({$transModel->getPaymentType()} - {$transModel->trans_code})", UserInfo::model(), BookingLog::PAYMENT_FAILED, '', $params);
					}
				}
			}
		}
		if ($ptpid = PaymentType::TYPE_MOBIKWIK)
		{
			
		}
	}

}
