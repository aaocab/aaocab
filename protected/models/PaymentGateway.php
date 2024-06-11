<?php

/**
 * This is the model class for table "payment_gateway".
 *
 * The followings are the available columns in table 'payment_gateway':
 * @property integer $apg_id
 * @property integer $apg_ptp_id
 * @property integer $apg_trans_id
 * @property integer $apg_model_id
 * @property integer $apg_model_type
 * @property integer $apg_booking_id
 * @property string $apg_ledger_id
 * @property string $apg_acc_trans_type
 * @property integer $apg_trans_ref_id
 * @property string $apg_code
 * @property integer $apg_mode
 * @property string $apg_remarks
 * @property string $apg_ipaddress
 * @property string $apg_device_detail
 * @property integer $apg_user_type
 * @property integer $apg_user_id
 * @property double $apg_amount
 * @property integer $apg_active
 * @property integer $apg_status
 * @property integer $apg_banktrans_type
 * @property string $apg_start_datetime
 * @property string $apg_response_details
 * @property string $apg_response_code
 * @property string $apg_response_message
 * @property string $apg_txn_id
 * @property string $apg_merchant_ref_id
 * @property string $apg_ref_id
 * @property string $apg_complete_datetime
 * @property string $apg_date
 * @property integer $apg_first_api_status_type
 * @property integer $apg_first_api_status
 * @property integer $apg_last_api_status_type
 * @property string $apg_first_response_details
 * @property string $apg_first_response_time
 * @property string $apg_pre_txn_id
 */
class PaymentGateway extends CActiveRecord
{

	const GOZO_PAID_OPERATOR	 = 1;
	const OPERATOR_PAID_GOZO	 = -1;

	public $paymentUrl;
	public $bank_chq_no, $bank_chq_dated, $bank_name, $apg_ledger_id_2, $apg_trans_type, $apg_ledger_id_3, $apg_type, $apg_ledger_id_1, $bank_ifsc, $bank_branch, $apg_from_date, $apg_to_date, $apg_date_type, $apg_operator_id;
	public $apg_is_invoice;
	public $bankTransType		 = [1 => 'Cash', 2 => 'Cheque', 3 => 'NEFT/RTGS'];
	public $modeList			 = [1 => 'Debit', 2 => 'Credit'];
	public $transModeList		 = [2 => 'Debit', 1 => 'Credit'];
	public $operatorList		 = [0 => 'Operator Paid Gozo', 1 => 'Gozo Paid Operator', 2 => 'Gozo Lend To Operator', 3 => 'Operator Lend To Gozo'];
	public $operatorListPartner	 = [0 => 'Agent -> Gozo (Debit to Gozo / Gozo pays)', 1 => 'Gozo -> Agent (Credit to Gozo / Gozo receives)'], $operator_id;
	public $accGozoPaidList		 = [27 => 'Compensation (Direct Expenses)'];
	public $accGozoReceiverList	 = [28 => 'Penalty (Indirect Expenses)'];
	public $paymentModeArr		 = [1 => 'Refund', 2 => 'Payment'];
	public $paymentStatusArr	 = [1 => 'Success', 2 => 'Failed'];
	public $paymentStatus		 = [0 => 'Pending', 1 => 'Success', 2 => 'Failed'];
	public $booking_id;
	public $trans_create_date1;
	public $trans_create_date2;
	public $ven_date_type;
	public $ven_from_date;
	public $ven_to_date;
	public $refundOrderCode;
	public $trans_vendor_id;
	public $trans_booking;
	public $trans_date1;
	public $trans_date2;
	public $trans_user;
	public $trans_ptp;
	public $trans_stat;
	public $trans_inactive_chkbox;
	public $trans_code;
	public $apg_ledger_type_id;
	public $apg_ledger_type_ids;
	public $ebs_name, $ebs_address, $totRefund, $ebsopt,
			$ebs_country, $ebs_state, $ebs_city,
			$ebs_postal_code, $ebs_phone, $ebs_email;
	public $tranasctionFor;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'payment_gateway';
	}

	public $trip_id;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('apg_ptp_id, apg_trans_id, apg_booking_id, apg_trans_ref_id, apg_mode, apg_user_type, apg_user_id, apg_active, apg_status', 'numerical', 'integerOnly' => true),
			['ebs_name,ebs_address,ebs_city,ebs_country,ebs_postal_code,ebs_country,ebs_phone,ebs_email', 'required', 'on' => 'ebs_payment'],
			array('apg_amount', 'numerical'),
			array('booking_id', 'required', 'on' => 'createTrans'),
			array('apg_ledger_id', 'length', 'max' => 20),
			array('apg_acc_trans_type', 'length', 'max' => 11),
			array('apg_code, apg_ipaddress', 'length', 'max' => 50),
			array('apg_remarks, apg_response_message, apg_txn_id, apg_merchant_ref_id, apg_ref_id', 'length', 'max' => 255),
			array('apg_device_detail', 'length', 'max' => 500),
			array('apg_response_details', 'length', 'max' => 5000),
			array('apg_response_code', 'length', 'max' => 100),
			array('apg_start_datetime, apg_complete_datetime, apg_date,booking_id', 'safe'),
			array('apg_id, apg_ptp_id,apg_banktrans_type, apg_trans_id, apg_model_id, apg_model_id,operator_id, apg_booking_id, apg_ledger_id, apg_acc_trans_type, apg_trans_ref_id, apg_code, apg_mode, apg_remarks, apg_ipaddress, apg_device_detail, apg_user_type, apg_user_id, apg_amount, apg_active, apg_status, apg_start_datetime, apg_response_details, apg_response_code, apg_response_message, apg_txn_id, apg_merchant_ref_id, apg_ref_id, apg_complete_datetime, apg_date, apg_pre_txn_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('apg_id, apg_ptp_id,apg_banktrans_type, apg_trans_id, apg_model_id, apg_model_id,operator_id, apg_booking_id, apg_ledger_id, apg_acc_trans_type, apg_trans_ref_id, apg_code, apg_mode, apg_remarks, apg_ipaddress, apg_device_detail, apg_user_type, apg_user_id, apg_amount, apg_active, apg_status, apg_start_datetime, apg_response_details, apg_response_code, apg_response_message, apg_txn_id, apg_merchant_ref_id, apg_ref_id, apg_complete_datetime, apg_date, apg_pre_txn_id', 'safe', 'on' => 'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'apg_id'				 => 'Apg',
			'apg_ptp_id'			 => 'Apg Ptp',
			'apg_trans_id'			 => 'Apg Trans',
			'apg_booking_id'		 => 'Apg Booking',
			'apg_ledger_id'			 => 'Apg Ledger',
			'apg_acc_trans_type'	 => 'Apg Acc Trans Type',
			'apg_trans_ref_id'		 => 'Apg Trans Ref',
			'apg_code'				 => 'Apg Code',
			'apg_mode'				 => 'Apg Mode',
			'apg_remarks'			 => 'Apg Remarks',
			'apg_ipaddress'			 => 'Apg Ipaddress',
			'apg_device_detail'		 => 'Apg Device Detail',
			'apg_user_type'			 => 'Apg User Type',
			'apg_user_id'			 => 'Apg User',
			'apg_amount'			 => 'Apg Amount',
			'apg_active'			 => 'Apg Active',
			'apg_status'			 => 'Apg Status',
			'apg_start_datetime'	 => 'Apg Start Datetime',
			'apg_response_details'	 => 'Apg Response Details',
			'apg_response_code'		 => 'Apg Response Code',
			'apg_response_message'	 => 'Apg Response Message',
			'apg_txn_id'			 => 'Apg Txn',
			'apg_merchant_ref_id'	 => 'Apg Merchant Ref',
			'apg_ref_id'			 => 'Apg Ref',
			'apg_complete_datetime'	 => 'Apg Complete Datetime',
			'apg_date'				 => 'Apg Date',
			'apg_banktrans_type'	 => 'Bank Trans Type',
			'booking_id'			 => 'Booking Id'
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

		$criteria->compare('apg_id', $this->apg_id);
		$criteria->compare('apg_ptp_id', $this->apg_ptp_id);
		$criteria->compare('apg_trans_id', $this->apg_trans_id);
		$criteria->compare('apg_booking_id', $this->apg_booking_id);
		$criteria->compare('apg_ledger_id', $this->apg_ledger_id, true);
		$criteria->compare('apg_acc_trans_type', $this->apg_acc_trans_type, true);
		$criteria->compare('apg_trans_ref_id', $this->apg_trans_ref_id);
		$criteria->compare('apg_code', $this->apg_code, true);
		$criteria->compare('apg_mode', $this->apg_mode);
		$criteria->compare('apg_remarks', $this->apg_remarks, true);
		$criteria->compare('apg_ipaddress', $this->apg_ipaddress, true);
		$criteria->compare('apg_device_detail', $this->apg_device_detail, true);
		$criteria->compare('apg_user_type', $this->apg_user_type);
		$criteria->compare('apg_user_id', $this->apg_user_id);
		$criteria->compare('apg_amount', $this->apg_amount);
		$criteria->compare('apg_active', $this->apg_active);
		$criteria->compare('apg_status', $this->apg_status);
		$criteria->compare('apg_start_datetime', $this->apg_start_datetime, true);
		$criteria->compare('apg_response_details', $this->apg_response_details, true);
		$criteria->compare('apg_response_code', $this->apg_response_code, true);
		$criteria->compare('apg_response_message', $this->apg_response_message, true);
		$criteria->compare('apg_txn_id', $this->apg_txn_id, true);
		$criteria->compare('apg_merchant_ref_id', $this->apg_merchant_ref_id, true);
		$criteria->compare('apg_ref_id', $this->apg_ref_id, true);
		$criteria->compare('apg_complete_datetime', $this->apg_complete_datetime, true);
		$criteria->compare('apg_date', $this->apg_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PaymentGateway the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getTXNIDbyid($tid)
	{
		$model		 = PaymentGateway::model()->findByPk($tid);
		$response	 = $model->apg_response_details;
		$resArr		 = json_decode($response, true);
		$txnid		 = $resArr['TXNID'] . $resArr['transactionId'];
		$txnid		 = ($txnid == '') ? $model->apg_txn_id : $txnid;
		return $txnid;
	}

	public function getByTxnId($txnid, $mode = null)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('apg_txn_id', $txnid);
		if ($mode > 0)
		{
			$criteria->compare('apg_mode', $mode);
		}
		$apgModel = $this->findAll($criteria);
		return $apgModel;
	}

	public static function getTXNIDbyBkgId($bkg_id, $mode = 0)
	{
		$sql	 = "SELECT `apg_code` FROM `payment_gateway` WHERE `apg_mode`= $mode AND `apg_booking_id`=$bkg_id AND apg_active = 1 AND apg_status IN(1,2)";
		$result	 = DBUtil::queryAll($sql);
		foreach ($result as $data)
		{
			$txnId[] = $data['apg_code'];
		}
		foreach ($txnId as $value)
		{
			if ($value == NULL)
			{
				$var .= "";
			}
			else
			{
				$var .= $value . ",";
			}
		}
		$var = substr($var, 0, -1);
		return $var;
	}

	public static function getTransCodebyBkgId($bkg_id, $mode = 0)
	{
		$sql	 = "SELECT group_concat(`apg_code` SEPARATOR ', ' ) FROM `payment_gateway` WHERE `apg_mode`= $mode AND `apg_booking_id`=$bkg_id AND apg_active = 1 AND apg_status IN(1)";
		$result	 = DBUtil::queryScalar($sql);
		return $result;
	}

	public function getTodaysCount()
	{
		$cdb		 = Yii::app()->db->createCommand();
		$cdb->select = "COUNT(*) as cnt";
		$cdb->from	 = $this->tableName();
		$cdb->where	 = 'date(apg_start_datetime) = CURDATE()';
		$cnt		 = $cdb->queryScalar();
		return $cnt;
	}

	public function generateNewTransCode()
	{
		$appendValue = $this->apg_id;
		$transCode	 = date('ymd') . str_pad($appendValue, 9, 0, STR_PAD_LEFT);
		return $transCode;
	}

	public function getPaymentType()
	{
		$ptpTypeList = PaymentType::model()->getList();
		return $ptpTypeList[$this->apg_ptp_id];
	}

	public static function getPaymentTypeName($ptp_id)
	{
		$ptpTypeList = PaymentType::model()->getList();
		return $ptpTypeList[$ptp_id];
	}

	public function getByCode($code)
	{
		if ($code)
		{
			$criteria	 = new CDbCriteria();
			$criteria->compare('apg_code', $code);
			$model		 = $this->find($criteria);
			Logger::create(' Transaction Details:  ' . json_encode($model->attributes), CLogger::LEVEL_TRACE);
			return $model;
		}
		return false;
	}

	public function getCodebyRefid($refid)
	{
		$code = $this->getCodebyid($refid);

		return $code;
	}

	public function getCodebyid($tid)
	{
		$model	 = PaymentGateway::model()->findByPk($tid);
		$code	 = $model->apg_code;

		return $code;
	}

	public static function getTotalAdvance($bkgId)
	{
		$sql = "SELECT SUM(adt.adt_amount)
				FROM   account_trans_details adt
				 INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id       
				 INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id 
				 INNER JOIN account_ledger al ON al.ledgerId = adt.adt_ledger_id
				WHERE  act.act_active=1 AND adt.adt_active = 1 AND act.act_status = 1 
                AND adt.adt_status = 1 AND act.act_type = 1 
				AND act.act_ref_id = $bkgId AND al.accountGroupId IN (27,28) 
				AND atd1.adt_ledger_id IN(13) AND atd1.adt_active = 1 AND atd1.adt_status = 1";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getTotalOnlinePayment($bkgId)
	{
		return DBUtil::command("SELECT SUM(adt_amount) advance
                                                FROM   account_trans_details adt
                                                       LEFT JOIN account_transactions act ON act.act_id = adt.adt_trans_id
                                                       LEFT JOIN account_ledger al ON al.ledgerId = adt.adt_ledger_id
                                                WHERE act.act_user_type=1 AND act.act_active=1 AND adt.adt_status = 1 AND adt.adt_active = 1 AND act.act_type = 1 AND act.act_ref_id = $bkgId AND al.accountGroupId IN (28
                                                     )")->queryScalar();
	}

	public static function getTotalVendorCollected($bkgId)
	{
		return DBUtil::command("SELECT SUM(adt_amount)
                                                FROM   account_trans_details adt
                                                       LEFT JOIN account_transactions act ON act.act_id = adt.adt_trans_id
                                                       LEFT JOIN account_ledger al ON al.ledgerId = adt.adt_ledger_id
                                                WHERE  adt.adt_status = 1 AND adt.adt_active = 1 AND act.act_type = 1 AND act.act_active=1 AND act.act_ref_id = $bkgId AND adt.adt_ledger_id=14
                                                       ")->queryScalar();
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

	public function getOperatorListPartner($mode = 0)
	{
		$modeList = $this->operatorListPartner;
		if ($mode > 0)
		{
			return $modeList[$mode];
		}
		return $modeList;
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

	public function getGozoPaidList()
	{
		$modeList = $this->accGozoPaidList;
		if ($mode > 0)
		{
			return $modeList[$mode];
		}
		return $modeList;
	}

	public function getGozoReceiverList()
	{
		$modeList = $this->accGozoReceiverList;
		if ($mode > 0)
		{
			return $modeList[$mode];
		}
		return $modeList;
	}

	public function udpdateResponseByCode($response, $success = 0)
	{
		$responseArr = [];
		$responseArr = json_decode($response, true);
		Logger::create("this->apg_ptp_id: " . $this->apg_ptp_id);
		if ($this->apg_ptp_id == PaymentType::TYPE_PAYTM)
		{
			$this->apg_response_code	 = $responseArr['RESPCODE'];
			$this->apg_response_message	 = $responseArr['RESPMSG'];
			$this->apg_txn_id			 = $responseArr['TXNID'];
		}
		if ($this->apg_ptp_id == PaymentType::TYPE_EBS)
		{
			$this->apg_response_code	 = $responseArr['errorCode'] . $responseArr['ResponseCode'];
			$this->apg_response_message	 = $responseArr['error'] . $responseArr['status'] . $responseArr['ResponseMessage'];
			if ($responseArr['TransactionID'] != '')
			{
				$this->apg_txn_id = $responseArr['TransactionID'];
			}
			else
			{
				$this->apg_txn_id = $responseArr['PaymentID'];
			}
		}
		if ($this->apg_ptp_id == PaymentType::TYPE_PAYUMONEY)
		{
			$this->apg_response_code = $responseArr['error'];
			$errorMsg1				 = ($responseArr['error_Message'] == '') ? '' : ', ' . $responseArr['error_Message'];

			$errorMsg = ($responseArr['result'][0]['status']) ? $responseArr['result'][0]['status'] : $responseArr['message'];

			$this->apg_response_message	 = $responseArr['field9'] . $errorMsg . $errorMsg1 . $responseArr['DESCRIPTION'];
			$this->apg_txn_id			 = $responseArr['payuMoneyId'] . $responseArr['result'][0]['paymentId'];
		}
		if ($this->apg_ptp_id == PaymentType::TYPE_INTERNATIONAL_CARD)
		{
			$this->apg_response_code = $responseArr['processorResponseCode'];
			$errorMsg1				 = $responseArr['processorResponseText'];

			$errorMsg					 = $responseArr['_attributes']['message'];
			//$transactionResponse->message
			//$responseMessage = ($error != '') ? $error : $transactionResponse->_attributes['status'].$message;
			$this->apg_response_message	 = trim($responseArr['status'] . ' ' . $errorMsg . ' ' . $errorMsg1 . ' ' . $responseArr['message']);
			$this->apg_txn_id			 = $responseArr['id'];
		}
		if ($this->apg_ptp_id == PaymentType::TYPE_MOBIKWIK)
		{
			$this->apg_response_code	 = $responseArr['statuscode'];
			$response_message			 = $responseArr['statusmessage'];
			$this->apg_response_message	 = trim($responseArr['status'] . ' ' . $response_message);
			$this->apg_txn_id			 = $responseArr['orderid'];
		}
		if ($this->apg_ptp_id == PaymentType::TYPE_ZAAKPAY)
		{
			$this->apg_response_code	 = $responseArr['responseCode'] . $responseArr['responsecode'];
			$response_message			 = $responseArr['responseDescription'];
			$this->apg_response_message	 = trim($responseArr['description'] . ' ' . $response_message);
			$this->apg_txn_id			 = $responseArr['orderId'] . $responseArr['orderid'];
		}
		if ($this->apg_ptp_id == PaymentType::TYPE_FREECHARGE)
		{
			if ($responseArr['errorCode'])
			{
				$this->apg_response_code = $responseArr['errorCode'];
			}
			$response_message = '';
			if ($responseArr['errorMessage'])
			{
				$response_message = $responseArr['errorMessage'];
			}
			$this->apg_response_message	 = trim($responseArr['status'] . ' ' . $response_message);
			$this->apg_txn_id			 = $responseArr['merchantTxnId'];
		}
		if ($this->apg_ptp_id == PaymentType::TYPE_LAZYPAY)
		{

			$this->apg_response_code = $responseArr['pgRespCode'];

			$this->apg_response_message	 = $responseArr['TxMsg'];
			$this->apg_txn_id			 = $responseArr['pgTxnNo'];
		}
		if ($this->apg_ptp_id == PaymentType::TYPE_EPAYLATER)
		{
			$this->apg_response_code	 = $responseArr['statusCode'];
			$this->apg_response_message	 = $responseArr['statusDesc'];
			$this->apg_txn_id			 = $responseArr['eplOrderId'];
		}
		if ($this->apg_ptp_id == PaymentType::TYPE_PAYNIMO)
		{
			$tranStatusArr				 = explode('=', $responseArr[0]);
			$tranStatus					 = trim($tranStatusArr[1]);
			$tranMsgArr					 = explode('=', $responseArr[1]);
			$tranMsg					 = trim($tranMsgArr[1]);
			$txnIdArr					 = explode('=', $responseArr[5]);
			$txnId						 = trim($txnIdArr[1]);
			$this->apg_response_code	 = $tranStatus;
			$this->apg_response_message	 = $tranMsg;
			$this->apg_txn_id			 = $txnId;
		}
		if ($this->apg_ptp_id == PaymentType::TYPE_AGENT_CORP_CREDIT)
		{

			$this->apg_response_message = trim($responseArr['remarks']);
		}
		//echo $response;exit;
		$this->apg_response_details	 = $response;
		$this->apg_status			 = $success;
		if ($this->apg_status == 1)
		{
			$this->apg_remarks = "Payment Success";
		}
		else
		{
			$this->apg_remarks = "Payment Failed";
		}
		$this->apg_complete_datetime = new CDbExpression('NOW()');
		Logger::create("udpdateResponseByCode Saving..");
		if ($this->save())
		{
			Logger::create("udpdateResponseByCode Saved");
			return true;
		}
		else
		{
			Logger::create("udpdateResponseByCode Error: " . json_encode($this->getErrors()));
			$emailCom = new emailWrapper();
			$emailCom->paymentFailedAlert($this->apg_booking_id, $this->apg_response_message);
			return FALSE;
		}
	}

	public function getAllowedRefundAmt($apgId)
	{
		$sql			 = "SELECT SUM(apg_amount) tot FROM `payment_gateway` WHERE ((apg_ref_id=$apgId AND apg_status<>2) OR (apg_id=$apgId AND apg_status=1)) AND apg_active=1";
		$remainingRefund = DBUtil::queryScalar($sql);
		return $remainingRefund;
	}

	public static function getPendingRefundAmt($apgId)
	{
		$sql			 = "SELECT SUM(apg_amount *-1) tot FROM `payment_gateway` WHERE ((apg_ref_id=$apgId AND apg_status=0)) AND apg_active=1";
		$remainingRefund = DBUtil::queryScalar($sql);
		return ($remainingRefund | 0);
	}

	public function getAllowedOtherRefundAmt($apgId, $refapgId)
	{
		$sql			 = "SELECT SUM(apg_amount) tot FROM `payment_gateway` WHERE (((apg_ref_id=$apgId AND apg_status<>2) OR (apg_id=$apgId AND apg_status=1)) AND  apg_id<>$refapgId  ) AND apg_active=1";
		$remainingRefund = DBUtil::command($sql)->queryScalar();
		return $remainingRefund;
	}

	public function refundByRefId($amount, $refId, $refType = Accounting::AT_BOOKING, UserInfo $userInfo = null, $isDBORefund = false, $refundGozoCoin = false, $onlyWalletRefund = false)
	{
		$result		 = ["success" => true, "refunded" => 0];
		$transaction = DBUtil::beginTransaction();
		try
		{
			$refund					 = 0;
			$typeWalletRefund		 = true;
			$partnerWalletRefund	 = false;
			$balanceForBooking		 = AccountTransDetails::getBalancebyBookingid($refId, true);
			$getCancellationCharge	 = AccountTransactions::getCancellationCharge($refId);
			$balanceForBooking		 = ($balanceForBooking - $getCancellationCharge);
			if ($refType == Accounting::AT_BOOKING)
			{
				$bmodel = Booking::model()->findByPk($refId);

				if ($bmodel->bkg_agent_id > 0)
				{
					$typeWalletRefund	 = false;
					$refundGozoCoin		 = false;
					$partnerWalletRefund = ($bmodel->bkg_agent_ref_code != '' || $bmodel->bkg_agent_ref_code != null);
					$amount				 = min([$amount, $balanceForBooking]);
				}
			}


			if ($refundGozoCoin)
			{
				$gozoCoinEntry = AccountTransDetails::getGozoCoinEntryUsedForbooking($refId);
				foreach ($gozoCoinEntry as $grow)
				{
					$maxGcoinRefund	 = $amount - $balanceForBooking;
					$refundAmount	 = min([$amount, $grow['adt_amount'], $maxGcoinRefund]);
					if ($refundAmount < 0)
					{
						$refundAmount = 0;
						continue;
					}
					Logger::create("Refund Amount : " . $refund, CLogger::LEVEL_TRACE);
					$actTransDetailsModel				 = AccountTransDetails::model()->findByPk($grow['adt_id']);
//				$success							 = $actTransDetailsModel->walletRefund($refundAmount);
					$actId								 = $actTransDetailsModel->adt_trans_id;
					$actTransDetailsModel->adt_amount	 = $refundAmount;
					$actModel							 = AccountTransactions::model()->findByPk($actId);
					$bkgmodel							 = Booking::model()->findByPk($actModel->act_ref_id);
					$success							 = AccountTransDetails::model()->refundTransaction($actTransDetailsModel, $bkgmodel);

					if ($success)
					{
						$refund	 += $refundAmount;
						Logger::create("Refund Amount : " . $refund, CLogger::LEVEL_TRACE);
						$amount	 -= $refundAmount;
						Logger::create("Refund Amount : " . $amount, CLogger::LEVEL_TRACE);
					}

					if ($amount <= 0)
					{
						break;
					}
				}
			}


			if ($partnerWalletRefund)
			{
				$partnerWalletBalance	 = AccountTransDetails::getPartnerBalanceForbooking($refId);
				$partnerWalletData		 = AccountTransDetails::getPartnerWalletEntryForbooking($refId);
				foreach ($partnerWalletData as $row)
				{
					$refundAmount = min([$amount, $row['adt_amount'], $balanceForBooking, $partnerWalletBalance]);

					if ($refundAmount <= 0)
					{
						$refundAmount = 0;
						continue;
					}
					Logger::create("Partner Refund Amount : " . $refund, CLogger::LEVEL_TRACE);
					$actTransDetailsModel	 = AccountTransDetails::model()->findByPk($row['adt_id']);
					/** @var AccountTransDetails $actTransDetailsModel */
					$success				 = $actTransDetailsModel->walletRefund($refundAmount);

					if ($success)
					{
						$refund	 += $refundAmount;
						Logger::create("Refund Amount : " . $refund, CLogger::LEVEL_TRACE);
						$amount	 -= $refundAmount;
						Logger::create("Refund Amount : " . $amount, CLogger::LEVEL_TRACE);
					}

					if ($amount <= 0)
					{
						break;
					}
				}
			}

			if (!$typeWalletRefund)
			{
				goto skipCustWallet;
			}
			$walletData = AccountTransDetails::getWalletEntryUsedForbooking($refId);
			foreach ($walletData as $row)
			{
				$refundAmount = min([$amount, $row['adt_amount'], $balanceForBooking]);

				if ($refundAmount <= 0)
				{
					$refundAmount = 0;
					continue;
				}
				Logger::create("Refund Amount : " . $refund, CLogger::LEVEL_TRACE);
				$actTransDetailsModel	 = AccountTransDetails::model()->findByPk($row['adt_id']);
				/** @var AccountTransDetails $actTransDetailsModel */
				$success				 = $actTransDetailsModel->walletRefund($refundAmount);

				if ($success)
				{
					$refund	 += $refundAmount;
					Logger::create("Refund Amount : " . $refund, CLogger::LEVEL_TRACE);
					$amount	 -= $refundAmount;
					Logger::create("Refund Amount : " . $amount, CLogger::LEVEL_TRACE);
				}

				if ($amount <= 0)
				{
					break;
				}
			}
			skipCustWallet:

			if ($isDBORefund && $amount > 0)
			{
				$txndate = new CDbExpression('NOW()');
				$ptpId	 = PaymentType::TYPE_WALLET;
				$remarks = "Refund of amount Rs.$amount for DBO";
				$success = AccountTransactions::model()->refundBooking($txndate, $amount, $refId, $ptpId, $remarks, null, $userInfo);
				if ($success)
				{
					$refund	 += $amount;
					$amount	 -= $amount;
					Logger::create("Refund Amount : " . $amount, CLogger::LEVEL_TRACE);
				}
			}


			sourceRefund:
			if ($amount <= 0)
			{
				goto tocommit;
			}
			if ($onlyWalletRefund)
			{
				goto tocommit;
			}
			$sql	 = "SELECT * FROM payment_gateway WHERE apg_active=1 AND apg_status=1 AND apg_mode=2 AND apg_trans_ref_id={$refId} AND apg_acc_trans_type=$refType ORDER BY apg_date DESC";
//				$rows	 = Yii::app()->db->createCommand($sql)->query();
			$rows	 = DBUtil::query($sql);

			foreach ($rows as $row)
			{

				$allowedRefund	 = PaymentGateway::model()->getAllowedRefundAmt($row['apg_id']);
				$refundInPending = PaymentGateway::getPendingRefundAmt($row['apg_id']);
				$refundable		 = min($allowedRefund, max($balanceForBooking - $refundInPending, 0));
				$refundAmount	 = min([$amount, $row['apg_amount'], $refundable, $balanceForBooking]);

				if ($refundAmount <= 0)
				{
					continue;
				}
				//exit;
				Logger::create("Refund Amount : " . $refund, CLogger::LEVEL_TRACE);
				$apgModel	 = $this->findByPk($row['apg_id']);
				$success	 = $apgModel->refund($refundAmount, $userInfo, $typeWalletRefund);
				if ($success)
				{
					$refund	 += $refundAmount;
					Logger::create("Refund Amount : " . $refund, CLogger::LEVEL_TRACE);
					$amount	 -= $refundAmount;
					Logger::create("Refund Amount : " . $amount, CLogger::LEVEL_TRACE);
				}

				if ($amount <= 0)
				{
					break;
				}
			}
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$result["success"]	 = false;
			$result["errors"]	 = ["code" => $e->getCode(), "message" => $e->getMessage()];
			goto skipcommit;
		}
		tocommit:
		DBUtil::commitTransaction($transaction);
		skipcommit:
		if (!$success)
		{
			$result["success"] = false;
		}
		$result["refunded"] = $refund;
		Logger::create("Total Refunded Amount : " . $result["refunded"] . " against Booking ID :" . $refId, CLogger::LEVEL_TRACE);
		return $result;
	}

	public function refund($amount, UserInfo $userInfo = null, $typeWallet = true)
	{
		$pgModel = $this;
		$success = false;

		if ($pgModel->apg_user_type != UserInfo::TYPE_CONSUMER)
		{
//			$typeWallet = false;
		}
//		$useUserWallet = Yii::app()->params['useUserWallet'];
		$useUserWallet = Config::get('user.useWallet');
		if (!$typeWallet)
		{
			$useUserWallet = 0;
		}
		if ($useUserWallet == 0)
		{
			$typeWallet = false;
		}
		$transaction = DBUtil::beginTransaction();
		try
		{
			$appendValue						 = rand(000, 999);
			$paymentGateway						 = new PaymentGateway();
			$paymentGateway->apg_booking_id		 = $pgModel->apg_booking_id;
			$paymentGateway->apg_acc_trans_type	 = $pgModel->apg_acc_trans_type;
			$paymentGateway->apg_trans_ref_id	 = $pgModel->apg_trans_ref_id;
			$paymentGateway->apg_ptp_id			 = $pgModel->apg_ptp_id;
			$paymentGateway->apg_ledger_id		 = ($typeWallet) ? Accounting::LI_WALLET : $pgModel->apg_ledger_id;
			$paymentGateway->apg_amount			 = ($amount > 0) ? -1 * $amount : $amount;
			$paymentGateway->apg_mode			 = 1;
			$paymentGateway->apg_code			 = date('ymd') . str_pad($appendValue, 9, 0, STR_PAD_LEFT);
			$paymentGateway->apg_remarks		 = "Refund Initiated";
			$paymentGateway->apg_user_type		 = $userInfo->userType;
			$paymentGateway->apg_user_id		 = $userInfo->userId;
			$paymentGateway->apg_status			 = 0;
			$paymentGateway->apg_date			 = new CDbExpression('NOW()');
			$paymentGateway->apg_ref_id			 = $pgModel->apg_id;
			$paymentGateway->apg_merchant_ref_id = $pgModel->apg_txn_id;
			$paymentGateway->scenario			 = 'create';
			if ($paymentGateway->validate())
			{
				if ($paymentGateway->save())
				{
					$paymentGateway->apg_code	 = $paymentGateway->generateNewTransCode();
					$success					 = $paymentGateway->save();
					// Booking Log
					$params['blg_ref_id']		 = $paymentGateway->apg_id;
					$eventid					 = BookingLog::REFUND_PROCESS_INITIATED;
					$desc						 = "Online Refund initiated ({$paymentGateway->getPaymentType()} - {$paymentGateway->apg_code})";
					BookingLog::model()->createLog($paymentGateway->apg_booking_id, $desc, $userInfo, $eventid, '', $params);
					DBUtil::commitTransaction($transaction);
				}
				else
				{
					DBUtil::rollbackTransaction($transaction);
				}
			}

			if (!$success)
			{
				goto result;
			}
			if ($typeWallet)
			{
				$user = $pgModel->apg_user_id;

				$responseArr					 = ['success' => true, 'message' => 'Transferred to wallet'];
				$payResponse					 = new PaymentResponse();
				$payResponse->payment_type		 = PaymentType::TYPE_WALLET;
				$payResponse->transaction_code	 = $paymentGateway->apg_code;
				$payResponse->response_code		 = 200;
				$payResponse->payment_code		 = $pgModel->apg_id;
				$payResponse->response			 = json_encode($responseArr);
				$payResponse->message			 = trim($responseArr['message']);
				$payResponse->payment_status	 = 1;
			}
			else
			{
				$pgObject	 = Filter::GetPGObject($paymentGateway->apg_ptp_id);
				/* @var $payResponse PaymentResponse */
				$payResponse = $pgObject->refund($paymentGateway);
			}
			$paymentGateway->updateRefundStatus($payResponse);
			$paymentGateway->refresh();
			if ($paymentGateway->apg_status == 1)
			{
				$success = true;
			}
			else
			{
				$success = false;
			}
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
		}
		result:
		return $success;
	}

	public function updateRefundStatus($payResponse)
	{
		Logger::create("PaymentGateway->updateRefundStatus  ");
		$transStatus = false;
		$transaction = DBUtil::beginTransaction();
		try
		{

			$success = $this->updateTransResponse($payResponse);
			Logger::create("PaymentGateway->updateRefundStatus  :: updateTransResponse : " . $success);
			DBUtil::commitTransaction($transaction);
			if ($this->apg_acc_trans_type == Accounting::AT_BOOKING && $success && $payResponse->payment_status == 1)
			{
				$amount		 = -1 * $this->apg_amount;
				$transStatus = AccountTransactions::model()->refundBooking($this->apg_date, $amount, $this->apg_trans_ref_id, $this->apg_ptp_id, $this->apg_remarks, $this, UserInfo::getInstance());
				Logger::create("PaymentGateway->updateRefundStatus  :: after refundBooking");
			}
		}
		catch (Exception $e)
		{
			Logger::create("PaymentGateway->updateRefundStatus  :: exception:" . $e->getMessage());
			DBUtil::rollbackTransaction($transaction);
		}
		return $transStatus;
	}

	/* @var $transaction PaymentGateway */

	public function udpdateCMDResponseByCodeForEBS($response, $success = 0)
	{
		$responseArr				 = json_decode($response, true);
		$this->apg_response_code	 = $responseArr['errorCode'] . $responseArr['ResponseCode'];
		$this->apg_response_message	 = $responseArr['error'] . $responseArr['status'];
		$this->apg_txn_id			 = $responseArr['transactionId'];
		$this->apg_merchant_ref_id	 = $responseArr['referenceNo'];
		$this->apg_response_details	 = $response;
		$diff						 = floor(( time() - strtotime($this->apg_start_datetime)) / 3600);
		if ($responseArr['status'] == 'Captured' || $success == 1 || $responseArr['status'] == 'Refunded')
		{
			$this->apg_status = 1;
		}
		else if ($responseArr['status'] != 'Captured' && $responseArr['status'] != 'Refunded')
		{
			$this->apg_status = 2;

			$params['blg_ref_id'] = $this->apg_id;
			if ($this->apg_ref_id > 0)
			{
				BookingLog::model()->createLog($this->apg_booking_id, "Refund process failed ({$this->getPaymentType()} - {$this->apg_code})", UserInfo::model(), BookingLog::REFUND_PROCESS_FAILED, '', $params);
			}
			else
			{
				BookingLog::model()->createLog($this->apg_booking_id, "Online payment failed ({$this->getPaymentType()} - {$this->apg_code})", UserInfo::model(), BookingLog::PAYMENT_FAILED, '', $params);
			}
		}
		$this->apg_complete_datetime = new CDbExpression('NOW()');
		if ($this->apg_status == 1 && $this->apg_ref_id > 0)
		{
			$this->apg_remarks = "Refund Success";
		}

		if ($this->save())
		{
			return true;
		}
		else
		{
			$emailCom = new emailWrapper();
			$emailCom->paymentFailedAlert($this->apg_booking_id, $this->apg_response_message);
			return FALSE;
		}
	}

	public function accountTotalSummary($agtId, $transDate1 = '', $transDate2 = '')
	{
		$cond = "";
		if ($transDate1 != '' && $transDate2 != '')
		{
			$fromTime		 = '00:00:00';
			$toTime			 = '23:59:59';
			$date1			 = DateTimeFormat::DatePickerToDate($transDate1);
			$date2			 = DateTimeFormat::DatePickerToDate($transDate2);
			$fromDateTime	 = $date1 . ' ' . $fromTime;
			$toDateTime		 = $date2 . ' ' . $toTime;
			$cond			 = " AND (apg_complete_datetime <= '{$date2}') ";
			//$cond = " AND (DATE(apg_complete_datetime) <= '{$transDate2}') ";
		}
		$sql = "SELECT ROUND(SUM(IF(apg_amount IS NOT NULL AND apg_amount <> '',apg_amount,0))) AS totAmount FROM paymentGateway WHERE apg_active = 1 AND apg_status = 1 $cond";
		return DBUtil::queryRow($sql);
	}

	public function getPayment($type)
	{
		$ptpTypeList = PaymentType::model()->getList();
		return $ptpTypeList[$type];
	}

	public function getPMTIDbyid($tid)
	{
		$model		 = PaymentGateway::model()->findByPk($tid);
		$response	 = $model->apg_response_details;
		$resArr		 = json_decode($response, true);
		$pmtid		 = 0;
		if (isset($resArr['PaymentID']))
		{
			$pmtid = $resArr['PaymentID'];
		}
		else if (isset($resArr['paymentId']))
		{
			$pmtid = $resArr['paymentId'];
		}
		else if (isset($resArr['PaymentId']))
		{
			$pmtid = $resArr['PaymentId'];
		}
		return $pmtid;
	}

	public function getRemainingRefundbyId($refid)
	{
		$result			 = DBUtil::command("SELECT SUM(apg_amount) totRefund FROM payment_gateway WHERE apg_status=1 AND apg_active=1 AND apg_ref_id=$refid  ")->queryRow();
		$refundAmt		 = $result['totRefund'] | 0;
		$model			 = PaymentGateway::model()->findByPk($refid);
		$remainingAmt	 = $model->apg_amount + $refundAmt;
		return $remainingAmt;
	}

	public function addAmountForOnlineLedger($model, $operatorId, $paymentTypeId, $bankLedgerId, $accType, $userInfo = null)
	{
		$paymentGateway							 = new PaymentGateway();
		$paymentGateway->apg_acc_trans_type		 = $accType;
		$paymentGateway->apg_trans_ref_id		 = $operatorId;
		$paymentGateway->apg_ptp_id				 = $paymentTypeId;
		$paymentGateway->apg_amount				 = $model->apg_amount;
		$paymentGateway->apg_remarks			 = $model->apg_remarks; //"Payment Initiated";
		$paymentGateway->apg_ref_id				 = '';
		$paymentGateway->apg_user_type			 = UserInfo::TYPE_ADMIN;
		$paymentGateway->apg_user_id			 = $userInfo->userId;
		$paymentGateway->apg_status				 = 1;
		$paymentGateway->apg_date				 = new CDbExpression('NOW()');
		$paymentGateway->apg_response_message	 = "Payment added by admin";
		$bankLedgerId							 = $model->apg_ledger_id;
		$paymentGateway							 = $paymentGateway->payment($bankLedgerId);
		return $paymentGateway;
	}

	public function payment($bankLedgerId)
	{
		try
		{
			$todayCount					 = $this->getTodaysCount();
			$appendValue				 = $todayCount + 1;
			$this->apg_code				 = date('ymdHis') . str_pad($appendValue, 3, 0, STR_PAD_LEFT);
			$this->apg_mode				 = ( $this->apg_amount < 0) ? 1 : 2;
			$this->apg_ipaddress		 = \Filter::getUserIP();
			$this->apg_device_detail	 = UserLog::model()->getDevice();
			$this->apg_ledger_id		 = $bankLedgerId;
			$this->apg_start_datetime	 = new CDbExpression('NOW()');
			if ($this->apg_ref_id > 0)
			{
				$oldtxnid					 = PaymentGateway::model()->getTXNIDbyid($this->apg_ref_id);
				$this->apg_merchant_ref_id	 = $oldtxnid;
			}
			if ($this->apg_status == 1)
			{
				$this->apg_complete_datetime = new CDbExpression('NOW()');
			}
			$this->scenario = 'create';
			if ($this->validate())
			{
				if ($this->save())
				{
					$this->apg_code = $this->generateNewTransCode();
					$this->save();
					return $this;
				}
			}
		}
		catch (Exception $e)
		{
			$this->addErrors($e->getMessage());
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

	public function getTransModeList($mode = 0)
	{
		$modeList = $this->transModeList;
		if ($mode > 0)
		{
			return $modeList[$mode];
		}
		return $modeList;
	}

	public function getOperatorValue($val)
	{
		switch ($val)
		{
			case '0':
				return VendorTransactions::OPERATOR_PAID_GOZO;
				break;
			case '1':
				return VendorTransactions::GOZO_PAID_OPERATOR;
				break;
			case '2':
				return VendorTransactions::GOZO_PAID_OPERATOR;
				break;
			case '3':
				return VendorTransactions::OPERATOR_PAID_GOZO;
				break;
		}
	}

	public function successTransaction($response, $success = 0)
	{
		Logger::create("transModel->response: " . $response);
		Logger::create("PaymentGateway->udpdateResponseByCode trans_status: " . $this->apg_status);

		$success = $this->udpdateResponseByCode($response, $success);
		Logger::create("PaymentGateway->udpdateResponseByCode: " . $success);

		$accType = $this->getAccountingType();
		$this->refresh();

		if ($this->apg_status == 1 && $accType == Accounting::AT_BOOKING)
		{
			$bankLedgerID	 = PaymentType::model()->ledgerList($this->apg_ptp_id);
			$accTransDetails = AccountTransDetails::model()->find('adt_active=1 AND adt_status=1 AND adt_ledger_id=' . $bankLedgerID . ' AND adt_trans_ref_id=:apg_id AND adt_type IN(1,4)', ['apg_id' => $this->apg_id]);
			if ($accTransDetails == NULL)
			{
				$model			 = Booking::model()->findByPk($this->apg_trans_ref_id);
				$isUpdateAdvance = $model->updateAdvance($this->apg_amount, $this->apg_date, $this->apg_ptp_id, UserInfo::model($this->apg_user_type, $this->apg_user_id), $this, $this->apg_response_message);
				if ($isUpdateAdvance)
				{
					Booking::model()->confirm(true, true, $model->bkg_id);
				}
			}
		}

		if ($this->apg_status == 1 && $accType == Accounting::AT_PARTNER)
		{
			$bankLedgerID = PaymentType::model()->ledgerList($this->apg_ptp_id);
			Agents::model()->updateCoins($this->apg_amount, $this->apg_trans_ref_id, $this->apg_date, $bankLedgerID, $this->apg_id, $this->apg_response_details);
		}


		if ($this->apg_status == 1 && $accType == Accounting::AT_OPERATOR)
		{
			$accTransModel				 = new AccountTransactions();
			$accTransModel->act_amount	 = $this->apg_amount;
			$accTransModel->act_date	 = ($this->apg_date == '') ? new CDbExpression('NOW()') : $this->apg_date;
			$accTransModel->act_type	 = Accounting::AT_OPERATOR;
			$accTransModel->act_ref_id	 = $this->apg_trans_ref_id;
			$accTransModel->act_remarks	 = $this->apg_remarks;
			$bankLedgerID				 = PaymentType::model()->ledgerList($this->apg_ptp_id);
			$bankCharge					 = Agents::model()->calculateProcessingFee($this->apg_amount);
			$accTransDetails			 = AccountTransDetails::model()->find('adt_active=1 AND adt_status=1 AND adt_ledger_id=' . $bankLedgerID . ' AND adt_trans_ref_id=:apg_id AND adt_type IN(1,4)', ['apg_id' => $this->apg_id]);
			if ($this->apg_user_type == 2)
			{
				$userInfo = UserInfo::model(UserInfo::TYPE_VENDOR, UserInfo::getEntityId());
			}
			if ($accTransDetails == NULL)
			{
				$accTransModel->AddVendorReceipt($bankLedgerID, Accounting::LI_OPERATOR, $this->apg_id, $this->apg_trans_ref_id, $accTransModel->act_remarks, Accounting::AT_ONLINEPAYMENT, $bankCharge, $userInfo);
			}
		}
	}

	public function giftCardSuccessTransaction($response, $success = 0, $accountingLedger, $giftId)
	{
		Logger::create("transModel->response: " . $response);
		Logger::create("PaymentGateway->udpdateResponseByCode trans_status: " . $this->apg_status);

		$success = $this->udpdateResponseByCode($response, $success);
		Logger::create("PaymentGateway->udpdateResponseByCode: " . $success);

		$this->refresh();

		if ($this->apg_status == 1)
		{
			$accTransModel		 = new AccountTransactions();
			$gftSubscriberModel	 = GiftCardSubscriber::model()->findByPk($giftId);
			$quantity			 = $gftSubscriberModel->gcs_quantity;
			$promoAmount		 = $gftSubscriberModel->gcs_value_amount;
			if ($quantity > 1)
			{
				$promoAmount = $gftSubscriberModel->gcs_value_amount * $quantity;
			}
			$costAmount					 = $gftSubscriberModel->gcs_cost_price;
			$discount					 = $promoAmount - $costAmount;
			$bankCharge					 = Agents::model()->calculateProcessingFee($costAmount);
			$accTransModel->act_type	 = Accounting::AT_GIFTCARD;
			$accTransModel->act_ref_id	 = $giftId;
			$accTransModel->act_amount	 = ($costAmount + $bankCharge);
			$accTransModel->act_remarks	 = "Gift Card Purchased";
			$accTransModel->act_date	 = ($this->apg_date == '') ? new CDbExpression('NOW()') : $this->apg_date;
			$bankLedgerID				 = PaymentType::model()->ledgerList($this->apg_ptp_id);

			$accTransDetails = AccountTransDetails::model()->find('adt_active=1 AND adt_status=1 AND adt_ledger_id=' . $bankLedgerID . ' AND adt_trans_ref_id=:apg_id AND adt_type IN(8)', ['apg_id' => $this->apg_id]);
			if ($accTransDetails == NULL)
			{
				$accTransModel->AddGiftCardReceipt($bankLedgerID, Accounting::LI_GIFTCARD, $this->apg_id, $this->apg_user_id, $costAmount, $bankCharge, $accTransModel->act_remarks, $discount);
				//	$accTransModel->AddGiftCardEntry($bankLedgerID, Accounting::LI_GIFTCARD, $this->apg_id, $this->apg_user_id, $costAmount, $bankCharge, $accTransModel->act_remarks);
			}
		}
	}

	public function updateTransResponse($payResponse, $usertype = '')
	{
		Logger::create("PaymentGateway->updateTransResponse :: payResponse->message " . $payResponse->message);
		$this->apg_response_message	 = $payResponse->message;
		$this->apg_response_code	 = $payResponse->response_code;
		$this->apg_txn_id			 = $payResponse->payment_code;
		$this->apg_complete_datetime = new CDbExpression('NOW()');
		$this->apg_status			 = $payResponse->payment_status | 0;
		$this->apg_ptp_id			 = $payResponse->payment_type;

		if ($payResponse->payment_type != PaymentType::TYPE_PAYUMONEY)
		{
			$payResponse->payment_status_type = 2;
		}
		if ($this->apg_first_api_status_type == 0)
		{
			$this->apg_first_api_status_type = $payResponse->payment_status_type | 0;
		}
		if ($this->apg_first_api_status == 0)
		{
			$this->apg_first_api_status = $payResponse->payment_status | 0;
		}
		$this->apg_last_api_status_type = $payResponse->payment_status_type | 0;
		if ($this->apg_first_response_details == NULL)
		{
			$this->apg_first_response_details = $payResponse->response;
		}
		if ($this->apg_first_response_time == NULL)
		{
			$this->apg_first_response_time = new CDbExpression('NOW()');
		}

		if ($this->apg_status != 0)
		{
			$this->apg_response_details = $payResponse->response;
		}

		$paymentMode = $this->getPaymentMode();
		if ($paymentMode == 'Refund' && $payResponse->payment_type == PaymentType::TYPE_WALLET)
		{
			if (BookingTrail::isDBORefundApplicable($this->apg_booking_id))
			{
				$paymentMode = 'Amount for DoubleBack refunded to wallet for booking: ' . $this->apg_booking_id;
			}
			else
			{
				$paymentMode = 'Amount refunded to wallet for booking: ' . $this->apg_booking_id;
			}
		}
		$remarks = ( $this->apg_remarks) ? $this->apg_remarks : '';
		if ($this->apg_status == 1)
		{
			$remarks = "$paymentMode (Success)";
		}
		if ($this->apg_status == 2)
		{
			$remarks = "$paymentMode (Failed)";
		}
		if ($this->apg_ptp_id == PaymentType::TYPE_RAZORPAY && $this->apg_response_code == "refunded" && $this->apg_response_details != null)
		{
			$arrResp = json_decode($this->apg_response_details, true);
			if ($arrResp['error_description'] != '')
			{
				$remarks = $arrResp['error_description'];
			}
		}
		$this->apg_remarks = "$remarks";
		if ($this->save())
		{
			Logger::create("updateTransResponse Saved");

			if ($this->apg_status == 2)
			{
				$remarks				 = "$paymentMode Process Failed";
				$params['blg_ref_id']	 = $this->apg_id;
				if ($this->apg_mode == 1)
				{

					$eventId = BookingLog::REFUND_PROCESS_FAILED;
					if ($payResponse->payment_type == PaymentType::TYPE_WALLET)
					{
						$eventId = BookingLog::ADD_TO_CUSTOMER_WALLET;
					}
				}
				if ($this->apg_mode == 2)
				{
					$eventId = BookingLog::PAYMENT_FAILED;
					if ($payResponse->payment_type == PaymentType::TYPE_WALLET)
					{
						$eventId = BookingLog::DEDUCTED_CUSTOMER_WALLET;
					}
				}
			}
			if ($this->apg_acc_trans_type != 2 && $this->apg_status != 0)
			{
				$params['blg_ref_id']	 = $this->apg_id;
				$isExist				 = false;
				if ($this->apg_acc_trans_type == 1 && $this->apg_status == 2)
				{
					$bkgId			 = $this->apg_booking_id;
					$eventId		 = BookingLog::PAYMENT_FAILED;
					$stopDuration	 = Yii::app()->params['skipBookingFailedPaymentLogDuration'];
					$createdAt		 = date('Y-m-d H:i:s', strtotime(DBUtil::getCurrentTime() . "- $stopDuration SECOND"));
					$refId			 = $this->apg_id;
					$desc			 = $this->apg_code;
					$isExist		 = BookingLog::checkExistingLog($bkgId, $eventId, $createdAt, $refId, $desc);
				}
				if ($this->apg_acc_trans_type == 1 && $this->apg_status == 1)
				{
					$bkgId					 = $this->apg_booking_id;
					$eventId				 = BookingLog::PAYMENT_COMPLETED;
					$refId					 = $this->apg_id;
					$params['blg_ref_id']	 = $this->apg_id;
				}
				if (!$isExist)
				{
					BookingLog::model()->createLog($this->apg_booking_id, $remarks, UserInfo::getInstance(), $eventId, '', $params);
				}
			}
			$success = true;
		}
		else
		{
			Logger::create("udpdateResponseByCode Error: " . json_encode($this->getErrors()));
			if ($this->apg_acc_trans_type != 2)
			{
				$emailCom = new emailWrapper();
				$emailCom->paymentFailedAlert($this->apg_booking_id, $this->apg_response_message);
			}
			$success = FALSE;
		}
		return $success;
	}

	public function processTransaction($payResponse, $usertype = '')
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$transaction = DBUtil::beginTransaction();
		try
		{
			Logger::trace("PaymentGateway->processTransaction trans_status: " . $this->apg_status);
			$success = $this->updateTransResponse($payResponse);
			if ($usertype != '' || $usertype != NULL)
			{
				$this->apg_user_type = $usertype;
			}

			$transStatus = true;
			$accType	 = $this->getAccountingType();
			$this->refresh();
			if ($this->apg_status == 1 && $success)
			{
				Logger::trace("Initiating Payment Update for Type : " . $accType);
				if ($accType == Accounting::AT_BOOKING)
				{
					$this->processBookingPostPayment();
				}

				if ($accType == Accounting::AT_PARTNER)
				{
					$bankLedgerID	 = PaymentType::model()->ledgerList($this->apg_ptp_id);
					$amount			 = $this->apg_amount;
					/** @var PaymentResponse $payResponse */
					$paymentId		 = $this->apg_txn_id;
					$paymentCode	 = $this->apg_code;
					$mode			 = $payResponse->mode;
					Agents::model()->processPayment($amount, $this->apg_trans_ref_id, $this->apg_date, $bankLedgerID, $this->apg_id, $this->apg_response_details, $mode, $paymentId, $paymentCode);
					$desc			 = "Account recharge for ₹$amount processed";
					$event			 = AgentLog::PAYMENT_COMPLETED;
					$partnerId		 = $this->apg_trans_ref_id;
					AgentLog::add($partnerId, $desc, $event);
				}


				if ($accType == Accounting::AT_OPERATOR)
				{
					$accTransModel				 = new AccountTransactions();
					$accTransModel->act_amount	 = $this->apg_amount;
					$accTransModel->act_date	 = ($this->apg_date == '') ? new CDbExpression('NOW()') : $this->apg_date;
					$accTransModel->act_type	 = Accounting::AT_OPERATOR;
					$accTransModel->act_ref_id	 = $this->apg_trans_ref_id;
					$accTransModel->act_remarks	 = $this->apg_remarks;
					$bankLedgerID				 = PaymentType::model()->ledgerList($this->apg_ptp_id);
					$bankCharge					 = Agents::model()->calculateProcessingFee($this->apg_amount);
					$accTransDetails			 = AccountTransDetails::model()->find('adt_active=1 AND adt_status=1 AND adt_ledger_id=' . $bankLedgerID . ' AND adt_trans_ref_id=:apg_id AND adt_type IN(1,4)', ['apg_id' => $this->apg_id]);
					if ($this->apg_user_type == 2)
					{
						$userInfo = UserInfo::model(UserInfo::TYPE_VENDOR, UserInfo::getEntityId());
					}
					if ($accTransDetails == NULL)
					{
						$accTransModel->AddVendorReceipt($bankLedgerID, Accounting::LI_OPERATOR, $this->apg_id, $this->apg_trans_ref_id, $accTransModel->act_remarks, Accounting::AT_ONLINEPAYMENT, $bankCharge, $userInfo);
					}
				}

				if ($accType == Accounting::AT_VOUCHER)
				{
					$returnSet = VoucherOrder::confirm($this->apg_trans_ref_id, $this->apg_id);
					if ($returnSet->isSuccess())
					{
						$date = new CDbExpression('NOW()');
						AccountTransactions::advanceReceived($date, $this->apg_ptp_id, $this->apg_id, $this->apg_amount, Accounting::AT_VOUCHER, $this->apg_trans_ref_id, 'Voucher Purchased');
					}
				}
			}
			if ($this->apg_status == 2 && $accType == Accounting::AT_BOOKING)
			{
				Logger::trace("PaymentGateway->processTransaction :: apg_status = 2");
				$transStatus			 = false;
				$params['blg_ref_id']	 = $this->apg_id;

				$bkgId			 = $this->apg_booking_id;
				$eventId		 = BookingLog::PAYMENT_FAILED;
				$stopDuration	 = Yii::app()->params['skipBookingFailedPaymentLogDuration'];
				$createdAt		 = date('Y-m-d H:i:s', strtotime(DBUtil::getCurrentTime() . "- $stopDuration SECOND "));
				$refId			 = $this->apg_id;
				$desc			 = $this->apg_code;
				$isExist		 = BookingLog::checkExistingLog($bkgId, $eventId, $createdAt, $refId, $desc);
				if (!$isExist)
				{
					BookingLog::model()->createLog($bkgId, "Online payment failed ({$this->getPaymentType()} - {$this->apg_code})", UserInfo::getInstance(), $eventId, '', $params);
				}
			}
			if ($this->apg_status == 2 && $accType == Accounting::AT_PARTNER)
			{
				Logger::trace("PaymentGateway->processTransaction :: apg_status = 2");
				$transStatus = false;
				$amount		 = $this->apg_amount;
				$desc		 = "Account recharge for ₹$amount failed";
				$event		 = AgentLog::PAYMENT_FAILED;
				$partnerId	 = $this->apg_trans_ref_id;
				AgentLog::add($partnerId, $desc, $event);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($exc);
		}

		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $transStatus;
	}

	public function getEmptystatus($bkgid = '')
	{

		$condition = "";
		if ($bkgid != '')
		{
			$condition = " AND (apg_booking_id='{$bkgid}' OR apg_trans_ref_id='{$bkgid}')";
		}

		$sql = "SELECT *,
			IF(apg_mode = 1,
            (SELECT apg_code
             FROM payment_gateway apg2
             WHERE apg2.apg_id = apg.apg_ref_id),
            0)
            refundOrderCode FROM payment_gateway apg
            WHERE ( (apg_response_details IS NULL OR apg_response_details = '') AND apg_status = 0 $condition
            AND apg_start_datetime>DATE_SUB(NOW(), INTERVAL 30 DAY) 
			AND apg_start_datetime < DATE_SUB(NOW(), INTERVAL 10 MINUTE)) AND apg_acc_trans_type IN(1,2,3)
            AND apg_ptp_id NOT IN (1 ,2, 5, 7, 8) 
			AND apg_amount <> 0 
			HAVING (apg_mode = 1 AND refundOrderCode> 0) OR apg_mode = 2
			ORDER BY apg_start_datetime DESC";

		$models = $this->findAllBySql($sql);
		return $models;
	}

	public function getFailedEntryList($bkgid = '')
	{
		$condition = "";
		if ($bkgid != '')
		{
			$condition = " AND (apg_booking_id='{$bkgid}' OR apg_trans_ref_id='{$bkgid}')";
		}
		$sql = "SELECT * FROM `payment_gateway` apg
                 WHERE apg_ptp_id IN (6,4,3,21,23) AND apg_mode IN (1,2) AND apg_status = 2 
				AND (apg_last_api_status_type <> 2 
					OR (apg_start_datetime <= DATE_SUB(NOW(), INTERVAL 5 MINUTE)  ))
                AND apg_start_datetime >= DATE_SUB(NOW(), INTERVAL 60 MINUTE) 
				AND apg_start_datetime < DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND apg_acc_trans_type=1 $condition 
				ORDER BY apg_start_datetime DESC";

		$models = $this->findAllBySql($sql);
		return $models;
	}

	public function getImmediateEmptystatus($bkgid = '')
	{

		$condition = "";
		if ($bkgid != '')
		{
			$condition = " AND (apg_booking_id='{$bkgid}' OR apg_trans_ref_id='{$bkgid}')";
		}

		$sql = "SELECT *,
			IF(apg_mode = 1,
            (SELECT apg_code
             FROM payment_gateway apg2
             WHERE apg2.apg_id = apg.apg_ref_id),
            0)
            refundOrderCode FROM payment_gateway apg
            WHERE ( (apg_response_details IS NULL OR apg_response_details = '') AND apg_status = 0 $condition
            AND apg_start_datetime>DATE_SUB(NOW(), INTERVAL 30 DAY) AND apg_start_datetime < DATE_SUB(NOW(), INTERVAL 5 MINUTE)) AND apg_acc_trans_type IN(1,2)
            AND apg_ptp_id NOT IN (1 ,2, 5, 7, 8) 
			AND apg_amount <> 0 
			HAVING (apg_mode = 1 AND refundOrderCode> 0) OR apg_mode = 2
			ORDER BY apg_start_datetime DESC";

		$models = $this->findAllBySql($sql);
		return $models;
	}

	/**
	 * 
	 * @param integer $pType
	 * @param integer $amount
	 * @param integer $bkgId
	 * @param integer $trans_ref_id
	 * @param UserInfo $userInfo
	 * @param integer $accTransType
	 * @return object | PaymentGateway
	 */
	public function add($pType, $amount, $bkgId = null, $trans_ref_id, UserInfo $userInfo = null, $accTransType = Accounting::AT_BOOKING)
	{
		if ($userInfo == null)
		{
			$userInfo = UserInfo::getInstance();
		}
		if ($accTransType == 1)
		{
			$bookingUserInfo = BookingUser::model()->getByBookingId($bkgId);
			$userInfo		 = UserInfo::model(UserInfo::TYPE_CONSUMER, $bookingUserInfo->bkg_user_id);
		}
		if ($accTransType == 2)
		{
			$userInfo = UserInfo::model(UserInfo::TYPE_VENDOR, UserInfo::getEntityId());
		}
		if ($accTransType == Accounting::AT_PARTNER)
		{
			$userInfo = UserInfo::model(UserInfo::TYPE_AGENT, UserInfo::getUserId());
		}
		$bankLedgerId						 = PaymentType::model()->ledgerList($pType);
		$paymentGateway						 = new PaymentGateway();
		$paymentGateway->apg_booking_id		 = $bkgId;
		$paymentGateway->apg_acc_trans_type	 = $accTransType;
		$paymentGateway->apg_trans_ref_id	 = $trans_ref_id;
		$paymentGateway->apg_ptp_id			 = $pType;
		$paymentGateway->apg_amount			 = $amount;
		$paymentGateway->apg_remarks		 = "Payment Initiated";
		$paymentGateway->apg_ref_id			 = '';
		$paymentGateway->apg_user_type		 = $userInfo->userType;
		$paymentGateway->apg_user_id		 = $userInfo->userId;
		$paymentGateway->apg_status			 = 0;
		$paymentGateway->apg_date			 = new CDbExpression('NOW()');

		$paymentGateway = $paymentGateway->payment($bankLedgerId);
		if ($accTransType == Accounting::AT_BOOKING && $paymentGateway)
		{
			$paymentGateway->paymentUrl = Yii::app()->createUrl('payment/initiate', ['apgid' => $paymentGateway->apg_id]);
		}
		if ($accTransType == Accounting::AT_VOUCHER && $paymentGateway)
		{
			$paymentGateway->paymentUrl = Yii::app()->createUrl('payment/initiate', ['apgid' => $paymentGateway->apg_id]);
		}
		if ($accTransType == Accounting::AT_OPERATOR && $paymentGateway)
		{
			$paymentGateway->paymentUrl = Yii::app()->createUrl('payment/initiate', ['apgid' => $paymentGateway->apg_id]);
		}
		if ($accTransType == Accounting::AT_PARTNER && $paymentGateway)
		{
			$paymentGateway->paymentUrl = Yii::app()->createUrl('payment/initiate', ['apgid' => $paymentGateway->apg_id]);
		}
		return $paymentGateway;
	}

	public function getTransdetailByTranscode($transCode)
	{
		$transId	 = '';
		$succ		 = '';
		$transArr	 = [];
		$reconfirmed = false;
		if ($transCode != '')
		{
			Logger::create('Payement Trans Code:  ' . $transCode, CLogger::LEVEL_TRACE);
			/* @var $transModel PaymentGateway */
			$transModel = PaymentGateway::model()->getByCode($transCode);
			if ($transModel)
			{
				Logger::create('Payement Trans Array:  ' . json_encode($transModel->attributes), CLogger::LEVEL_TRACE);
				$transDetails	 = $transModel->apg_response_details;
				$transId		 = $transModel->apg_txn_id;
				if ($transDetails != '')
				{

					Logger::create(' Transaction Details:  ' . $transDetails, CLogger::LEVEL_TRACE);
					$transArr = json_decode($transDetails, true);
					Logger::create(' APG_PTP_ID:  ' . $transModel->apg_ptp_id, CLogger::LEVEL_TRACE);
					if ($transModel->apg_ptp_id == PaymentType::TYPE_PAYTM)
					{
						$tranStatus	 = $transArr['STATUS'];
						$succ		 = ($tranStatus == 'TXN_SUCCESS') ? 'success' : 'fail';
						$transId	 = $transArr['TXNID'];
					}
					if ($transModel->apg_ptp_id == PaymentType::TYPE_EBS)
					{
						$tranStatus	 = $transArr['ResponseCode'];
						$succ		 = ($tranStatus == 0) ? 'success' : 'fail';
						$transId	 = $transArr['TransactionID'];
					}
					if ($transModel->apg_ptp_id == PaymentType::TYPE_PAYUMONEY)
					{
						$tranStatus	 = $transArr['status'];
						$succ		 = ($tranStatus == 'success' ) ? 'success' : 'failure';
						$tranStatus	 = $succ;
						$transId	 = $transModel->apg_txn_id;
					}
					if ($transModel->apg_ptp_id == PaymentType::TYPE_INTERNATIONAL_CARD)
					{
						$tranStatus	 = trim($transArr['processorResponseText']);
						$succ		 = ($tranStatus == 'Approved') ? 'success' : 'fail';
						$transId	 = $transModel->apg_txn_id;
					}
					if ($transModel->apg_ptp_id == PaymentType::TYPE_MOBIKWIK)
					{
						$tranCode	 = $transModel->apg_response_code;
						$succ		 = ($tranCode == '0') ? 'success' : 'fail';
						$transId	 = $transModel->apg_txn_id;
					}
					if ($transModel->apg_ptp_id == PaymentType::TYPE_ZAAKPAY)
					{
						$tranCode	 = $transModel->apg_response_code;
						$succ		 = ($tranCode == '100') ? 'success' : 'fail';
						$transId	 = $transModel->apg_txn_id;
					}
					if ($transModel->apg_ptp_id == PaymentType::TYPE_FREECHARGE)
					{
						$tranStatus	 = $transModel->apg_response_message;
						$succ		 = (strtolower($tranStatus) == 'completed') ? 'success' : 'fail';
						$transId	 = $transModel->apg_txn_id;
					}
					if ($transModel->apg_ptp_id == PaymentType::TYPE_LAZYPAY)
					{
						$tranStatus	 = $transArr['TxStatus'];
						$succ		 = (strtolower($tranStatus) == 'success') ? 'success' : 'fail';
						$transId	 = $transModel->apg_txn_id;
					}
					if ($transModel->apg_ptp_id == PaymentType::TYPE_EPAYLATER)
					{
						$tranStatus	 = $transArr['status'];
						$succ		 = (strtolower($tranStatus) == 'success') ? 'success' : 'fail';
						$transId	 = $transModel->apg_txn_id;
					}
					if ($transModel->apg_ptp_id == PaymentType::TYPE_PAYNIMO)
					{
						$tranStatusArr	 = explode('=', $transArr[1]);
						$tranStatus		 = trim($tranStatusArr[1]);
						$succ			 = (strtolower($tranStatus) == 'success') ? 'success' : (strtolower($tranStatus) == 'awaited') ? 'waiting' : 'fail';
						$transId		 = $transModel->apg_txn_id;
					}
					if ($transModel->apg_ptp_id == PaymentType::TYPE_RAZORPAY)
					{
						$tranStatus	 = "success";
						$succ		 = ($transArr['razorpay_payment_id'] != "" && $transArr['razorpay_order_id'] != "" && $transArr['razorpay_signature'] != "") ? "success" : "fail";
						$transId	 = $transModel->apg_txn_id;
					}
//                    if ($reconfirmed)
//                    {
					$paymentStatus = ($succ == 'success') ? 1 : ($succ == 'waiting') ? 2 : 0;
//                    }
				}

				$paymentStatus	 = $transModel->apg_status;
				$transResult	 = ['transId'		 => $transId,
					'succ'			 => $succ, 'paymentStatus'	 => $paymentStatus, 'tranStatus'	 => $tranStatus, 'code'			 => $transModel->apg_code];
				Logger::create('TransDetails:  ' . json_encode($transResult), CLogger::LEVEL_TRACE);

				return $transResult;
			}
		}
		return false;
	}

	public function getPaymentStatus($transCode)
	{
		$pgModel	 = PaymentGateway::model()->getByCode($transCode);
		$pgObject	 = Filter::GetPGObject($pgModel->apg_ptp_id);
		$payResponse = $pgObject->getPaymentStatus($pgModel);
		var_dump($payResponse);
	}

	public function getPGRequest($apg_id)
	{
		/* @var $pModel PaymentGateway  */
		$pModel							 = PaymentGateway::model()->findByPk($apg_id);
		$paymentData					 = self::getMapData($pModel);
		$payRequest						 = new PaymentRequest();
		$payRequest->trans_amount		 = $paymentData['transaction_amount'];
		$payRequest->payment_type		 = $paymentData['ptp_id'];
		$payRequest->custInfo			 = $paymentData['info'];
		$payRequest->description		 = $paymentData['description'];
		$payRequest->mobile				 = $paymentData['mobile'];
		$payRequest->email				 = $paymentData['email'];
		$payRequest->name				 = $paymentData['name'];
		$payRequest->billingAddress		 = $paymentData['billing_address'];
		$payRequest->city				 = $paymentData['city'];
		$payRequest->state				 = $paymentData['state'];
		$payRequest->postal				 = $paymentData['postal_code'];
		$payRequest->country			 = $paymentData['country'];
		$payRequest->bankcode			 = $paymentData['bank_code'];
		$payRequest->transaction_code	 = $paymentData['transaction_code'];

		/*
		  $bkgid			 = $pModel->apg_booking_id;
		  $bkmodel		 = Booking::model()->findByPk($bkgid);
		  $bkgUserModel	 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $bkgid]);
		  if (!$bkgid)
		  {
		  throw new CHttpException(400, "Invalid Payment Request", 400);
		  }

		  $payRequest->trans_amount		 = $pModel->apg_amount;
		  $payRequest->payment_type		 = $pModel->apg_ptp_id;
		  $payRequest->custInfo			 = ($bkgUserModel->bkg_user_id == '') ? $bkmodel->bkg_booking_id : $bkgUserModel->bkg_user_id;
		  $payRequest->description		 = $bkmodel->bkgFromCity->cty_name . '/' . $bkmodel->bkgToCity->cty_name . '/' . $bkmodel->bkg_booking_id;
		  $payRequest->mobile				 = $bkgUserModel->bkg_bill_contact;
		  $payRequest->email				 = $bkgUserModel->bkg_bill_email;
		  $payRequest->name				 = $bkgUserModel->bkg_bill_fullname;
		  $payRequest->billingAddress		 = $bkgUserModel->bkg_bill_address;
		  $payRequest->city				 = $bkgUserModel->bkg_bill_city;
		  $payRequest->state				 = $bkgUserModel->bkg_bill_state;
		  $payRequest->postal				 = $bkgUserModel->bkg_bill_postalcode;
		  $payRequest->country			 = $bkgUserModel->bkg_bill_country;
		  $payRequest->bankcode			 = $bkgUserModel->bkg_bill_bankcode;
		  $payRequest->transaction_code	 = $pModel->apg_code;
		 */
		return $payRequest;
	}

	/**
	 * 
	 * @param PaymentGateway $model
	 * @return array
	 */
	public static function getMapData($model)
	{
		$paymentData = [];
		switch ($model->apg_acc_trans_type)
		{
			case 1:
				$bkgId			 = $model->apg_booking_id;
				$bkmodel		 = Booking::model()->findByPk($bkgId);
				$bkgUserModel	 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $bkgId]);
				$paymentData	 = ['transaction_amount' => $model->apg_amount,
					'ptp_id'			 => $model->apg_ptp_id,
					'info'				 => ($bkgUserModel->bkg_user_id == '') ? $bkmodel->bkg_booking_id : $bkgUserModel->bkg_user_id,
					'description'		 => $bkmodel->bkgFromCity->cty_name . '/' . $bkmodel->bkgToCity->cty_name . '/' . $bkmodel->bkg_booking_id,
					'mobile'			 => $bkgUserModel->bkg_bill_contact,
					'email'				 => $bkgUserModel->bkg_bill_email,
					'name'				 => $bkgUserModel->bkg_bill_fullname,
					'billing_address'	 => $bkgUserModel->bkg_bill_address,
					'city'				 => $bkgUserModel->bkg_bill_city,
					'state'				 => $bkgUserModel->bkg_bill_state,
					'postal_code'		 => $bkgUserModel->bkg_bill_postalcode,
					'country'			 => $bkgUserModel->bkg_bill_country,
					'bank_code'			 => $bkgUserModel->bkg_bill_bankcode,
					'transaction_code'	 => $model->apg_code];
				break;
			case 3:
				$partnerId		 = $model->apg_trans_ref_id;
				/** @var Agents $partnerModel */
				$partnerModel	 = Agents::model()->findByPk($partnerId);
				$cityName		 = '';
				if ($partnerModel->agt_city > 0)
				{
					$cityName = Cities::getName($partnerModel->agt_city);
				}
				$address = $partnerModel->agt_address;
				if (!$partnerModel->agt_address && $cityName != '')
				{
					$address = $cityName;
				}
				$company = $partnerModel->agt_company;
				if ($company == '')
				{
					$company = $partnerModel->agt_owner_name;
				}
				if ($company == '')
				{
					$company = $partnerModel->agt_fname . " " . $partnerModel->agt_lname;
				}
				$infoDesc	 = $company . " - " . $partnerModel->agt_agent_id;
				$paymentData = [
					'transaction_amount' => $model->apg_amount,
					'ptp_id'			 => $model->apg_ptp_id,
					'info'				 => $infoDesc,
					'description'		 => $infoDesc,
					'mobile'			 => $partnerModel->agt_phone,
					'email'				 => $partnerModel->agt_email,
					'name'				 => $partnerModel->agt_fname . " " . $partnerModel->agt_lname,
					'billing_address'	 => $address,
					'city'				 => $cityName,
					'state'				 => '',
					'postal_code'		 => '',
					'country'			 => 'IN',
					'bank_code'			 => '',
					'transaction_code'	 => $model->apg_code];
				break;
			case 9:
				/* @var $vorModel VoucherOrder */
				$vorModel	 = VoucherOrder::model()->findByPk($model->apg_trans_ref_id);
				$description = '';
				$ctr		 = 1;
				foreach ($vorModel->voucherOrderDetails as $orderDetails)
				{
					if (count($orderDetails) == $ctr)
					{
						$description .= $orderDetails->vodVch->vch_title;
					}
					else
					{
						$description .= $orderDetails->vodVch->vch_title . "/";
					}
					$ctr++;
				}
				$paymentData	 = [
					'transaction_amount' => $vorModel->vor_total_price,
					'ptp_id'			 => $model->apg_ptp_id,
					'info'				 => $vorModel->vor_id,
					'description'		 => $description,
					'mobile'			 => $vorModel->vor_bill_contact,
					'email'				 => $vorModel->vor_bill_email,
					'name'				 => $vorModel->vor_bill_fullname,
					'billing_address'	 => $vorModel->vor_bill_address,
					'city'				 => $vorModel->vor_bill_city,
					'state'				 => $vorModel->vor_bill_state,
					'postal_code'		 => $vorModel->vor_bill_postalcode,
					'country'			 => $vorModel->vor_bill_country,
					'bank_code'			 => $vorModel->vor_bill_bankcode,
					'transaction_code'	 => $model->apg_code];
				break;
			case 2:
				$partnerId		 = $model->apg_trans_ref_id;
				/** @var Vendors $partnerModel */
				$partnerModel	 = Vendors::model()->findByPk($partnerId);
				$infoDesc		 = $partnerModel->vnd_name . " (" . $partnerModel->vnd_code . ")";
				$contactNumber	 = ContactPhone::getContactNumber($partnerModel->vnd_contact_id);
				$contactEmail	 = ContactEmail::getByContactId($partnerModel->vnd_contact_id);
				$paymentData	 = [
					'transaction_amount' => $model->apg_amount,
					'ptp_id'			 => $model->apg_ptp_id,
					'info'				 => $infoDesc,
					'description'		 => $infoDesc,
					'mobile'			 => $contactNumber,
					'email'				 => $contactEmail,
					'name'				 => $partnerModel->vnd_name,
					'city'				 => '',
					'state'				 => '',
					'postal_code'		 => '',
					'country'			 => 'IN',
					'bank_code'			 => '',
					'transaction_code'	 => $model->apg_code];
				break;
		}
		return $paymentData;
	}

	public function updatePGResponse($responseArr, $ptpId)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$transaction = DBUtil::beginTransaction();
		$result		 = ['success' => true];
		try
		{
			$pgObject	 = Filter::GetPGObject($ptpId);
			/* @var $payResponse PaymentResponse */
			$payResponse = $pgObject->parseResponse($responseArr);
			/* @var $apgModel PaymentGateway */
			$apgModel	 = PaymentGateway::model()->getByCode($payResponse->transaction_code);
			if ($apgModel->apg_acc_trans_type > 0)
			{
				switch ($apgModel->apg_acc_trans_type)
				{
					case 1:
						$result['bkid']	 = $apgModel->apg_trans_ref_id;
						break;
					case 3:
						$result['agtId'] = $apgModel->apg_trans_ref_id;
						break;
					case 9:
						$result['vorId'] = $apgModel->apg_trans_ref_id;
						break;
				}
			}
			else
			{
				$result['bkid'] = $apgModel->apg_booking_id;
			}

			$result['tinfo'] = $payResponse->transaction_code;

			$transStatus		 = $apgModel->processTransaction($payResponse, UserInfo::TYPE_CONSUMER);
			$result['success']	 = $transStatus;

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			Logger::create("Payment Update Failed: ({$e->getMessage()})", CLogger::LEVEL_ERROR);
			DBUtil::rollbackTransaction($transaction);
			throw $e;
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $result;
	}

	public function getAccountingType()
	{
		switch ($this->apg_acc_trans_type)
		{
			case 2:
				$accType	 = Accounting::AT_OPERATOR;
				break;
			case 3:
				$accType	 = Accounting::AT_PARTNER;
				break;
			case Accounting::AT_TRIP:
				$accTypeName = 'Trip';
				break;
			case Accounting::AT_DRIVER:
				$accTypeName = 'Driver';
				break;
			case Accounting::AT_USER:
				$accTypeName = 'User';
				break;
			case Accounting::AT_VOUCHER:
				$accType	 = Accounting::AT_VOUCHER;
				break;
			default:
				$accType	 = Accounting::AT_BOOKING;
				break;
		}
		return $accType;
	}

	/**
	 * @param PaymentGateway $pgModel 
	 */
	public function updateEmptyPGResponse($pgModel, $app = 0)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);

		/* @var $payResponse PaymentResponse */

		Logger::trace("PGModel: (" . json_encode($pgModel->getAttributes()) . ")");
		$transStatus = false;
		try
		{
			$pgObject	 = Filter::GetPGObject($pgModel->apg_ptp_id);
			$count		 = 1;
			end:
			$payResponse = $pgObject->getPaymentStatus($pgModel);
			if ($payResponse)
			{

				Logger::trace("payResponse: (" . json_encode($payResponse) . ")");
				if ($pgModel->apg_ptp_id == PaymentType::TYPE_PAYNIMO && ($payResponse->response_code == '0396' || $payResponse->response_code == '9999') && $count <= 3 && $app == 1)
				{
					$count				 += 1;
					$pgModel->apg_status = 0;
					goto end;
				}
				if ($payResponse && $pgModel->apg_status == 0)
				{
					if ($pgModel->apg_mode == 1)
					{
						$bkgId	 = $pgModel->apg_booking_id;
						Logger::writeToConsole("BookingId: " . $bkgId);
						$bModel	 = Booking::model()->findByPk($bkgId);
//						if ($bModel->bkg_agent_id == 34928 )
						if ($bModel->bkg_agent_id > 0 && $bModel->bkg_agent_id != 1249)
						{
							$transStatus = $pgModel->updateRefundStatus($payResponse);
						}
						else
						{
							$transStatus = $pgModel->processRefundStatus($payResponse);
						}
					}
					else
					{
						$transStatus = $pgModel->processTransaction($payResponse);
					}
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error($e);
			Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
			throw $e;
		}
		$result = ['success' => $transStatus];
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $result;
	}

	public function updateFailedPGResponse($pgModel, $app = 0)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$transaction = DBUtil::beginTransaction();
		/* @var $payResponse PaymentResponse */
		/* @var $pgModel PaymentGateway */
		Logger::trace("Payment update for transcode: ({$pgModel->apg_code})");
		$transStatus = false;
		try
		{
			Logger::trace("PaymentGateway->updateFailedPGResponse   ::  apg_ptp_id:" . $pgModel->apg_ptp_id);
			$pgObject = Filter::GetPGObject($pgModel->apg_ptp_id);
			Logger::trace("PaymentGateway->updateFailedPGResponse   ::  pgObject: " . $pgObject->view);

			$payResponse = $pgObject->getPaymentStatus($pgModel);

			if ($payResponse && $pgModel->apg_status == 2)
			{

				if ($pgModel->apg_mode == 1)
				{
					$transStatus = $pgModel->processRefundStatus($payResponse);
				}
				else
				{
					$transStatus = $pgModel->processTransaction($payResponse);
				}
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			Logger::exception($e);
			DBUtil::rollbackTransaction($transaction);
			Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
			throw $e;
		}
		$result = ['success' => $transStatus];
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $result;
	}

	public function searchTransactions()
	{
		if ($this->trans_stat != '')
		{
			$trans_status = "{$this->trans_stat}";
		}
		else
		{
			$trans_status = '1';
		}
		$sql1 = "SELECT
				pg.apg_code trans_code,
				pg.apg_id,
				pg.apg_amount,
				pg.apg_acc_trans_type,
				vendors.vnd_name,
				account_ledger.ledgerName,
				apgRef.apg_code refundOrderCode,
				pg.apg_ptp_id trans_ptp,
				IF(pg.apg_amount > 0,'DEBIT','CREDIT') trans_mode,
				IF(pg.apg_acc_trans_type = 1,book.bkg_booking_id,vendors.vnd_name) bkg_booking_id,
				book.bkg_id bkg_id,
				pg.apg_ledger_id,
				pg.apg_status trans_status,
				pg.apg_remarks trans_response_message,
				pg.apg_start_datetime,
				pg.apg_start_datetime trans_start_datetime,
				pg.apg_complete_datetime trans_complete_datetime,
				usr.usr_name,
				usr.usr_lname,
				usr.usr_email, 0 is_wallet
				FROM payment_gateway pg
				LEFT JOIN payment_gateway apgRef ON		pg.apg_ref_id = apgRef.apg_id
				JOIN account_ledger ON account_ledger.ledgerId = pg.apg_ledger_id
				LEFT JOIN vendors ON pg.apg_trans_ref_id = vendors.vnd_id
				INNER JOIN booking book ON book.bkg_id = pg.apg_trans_ref_id AND book.bkg_status IN(1,2, 3, 5, 6, 7, 9, 10,15) 
				AND pg.apg_mode IN(1, 2) AND pg.apg_status IN($trans_status) AND pg.apg_active = 1
				INNER JOIN booking_user bookuser ON bookuser.bui_bkg_id=book.bkg_id
				LEFT JOIN users usr ON	user_id = bookuser.bkg_user_id
				WHERE	account_ledger.accountGroupId IN(28) AND pg.apg_id IS NOT NULL AND pg.apg_ledger_id IN(16, 21, 20, 32, 17, 19, 18, 39, 42,46,53,58)";

		$SqlCount1 = "SELECT
			pg.apg_code as trans_code,
            pg.apg_id
			FROM	payment_gateway pg
			LEFT JOIN payment_gateway apgRef ON	pg.apg_ref_id = apgRef.apg_id
			JOIN account_ledger ON account_ledger.ledgerId = pg.apg_ledger_id
			LEFT JOIN vendors ON pg.apg_trans_ref_id = vendors.vnd_id
			INNER JOIN booking book ON	book.bkg_id = pg.apg_trans_ref_id AND book.bkg_status IN(1,2, 3, 5, 6, 7, 9, 10,15) 
			AND pg.apg_mode IN(1, 2) AND pg.apg_status IN($trans_status) AND pg.apg_active = 1
			INNER JOIN booking_user bookuser ON bookuser.bui_bkg_id=book.bkg_id
			LEFT JOIN users usr ON	user_id = bookuser.bkg_user_id
			WHERE	account_ledger.accountGroupId IN(28) AND pg.apg_id IS NOT NULL AND pg.apg_ledger_id IN(16, 21, 20, 32, 17, 19, 18, 39, 42,46,53,58) ";

		$sql2 = "
				UNION 
				SELECT 
				'' trans_code,
				adt.adt_id apg_id,
				adt.adt_amount apg_amount,
				act.act_type apg_acc_trans_type,
				'' vnd_name,
				'wallet' ledgerName,
				'' refundOrderCode,
				'' trans_ptp,
				IF(adt.adt_amount > 0,'DEBIT','CREDIT') trans_mode,
				IF(adt.adt_type = 1,book.bkg_booking_id,book.bkg_booking_id) bkg_booking_id,
				book.bkg_id bkg_id,
				adt.adt_ledger_id apg_ledger_id,
				act.act_status trans_status,
				act.act_remarks trans_response_message,
				act.act_date apg_start_datetime,
				act.act_date trans_start_datetime,
				act.act_date trans_complete_datetime,
				usr.usr_name,
				usr.usr_lname,
				usr.usr_email, 1 is_wallet
				FROM account_trans_details adt
				INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND  adt.adt_ledger_id=47 
				INNER JOIN booking book ON book.bkg_id = act.act_ref_id AND book.bkg_status IN(1,2, 3, 5, 6, 7, 9, 10,15) AND act.act_active = 1
				LEFT JOIN users usr ON user_id = act.act_user_id
				where 1	";

		$SqlCount2 = " 
                    UNION 
					SELECT 
                    '' trans_code,
	                adt.adt_id apg_id
					FROM account_trans_details adt
					INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND  adt.adt_ledger_id=47 
					INNER JOIN booking book ON book.bkg_id = act.act_ref_id AND book.bkg_status IN(1,2, 3, 5, 6, 7, 9, 10,15) AND act.act_active = 1
					LEFT JOIN users usr ON user_id = act.act_user_id
					where 1	
		 ";
		if ($this->apg_ledger_id != '')
		{
			$sql1		 .= " AND pg.apg_ledger_id = {$this->apg_ledger_id}";
			$sql2		 .= " AND adt_ledger_id = {$this->apg_ledger_id}";
			$SqlCount1	 .= " AND pg.apg_ledger_id = {$this->apg_ledger_id}";
			$SqlCount2	 .= " AND adt_ledger_id = {$this->apg_ledger_id}";
		}
		if ($this->apg_txn_id != '')
		{
			$sql1		 .= " AND pg.apg_txn_id = '{$this->apg_txn_id}'";
			$SqlCount1	 .= " AND pg.apg_txn_id = '{$this->apg_txn_id}'";
		}
		if ($this->trans_booking != '')
		{
			$sql1		 .= " AND bkg_booking_id LIKE '%{$this->trans_booking}%'";
			$sql2		 .= " AND bkg_booking_id LIKE '%{$this->trans_booking}%'";
			$SqlCount1	 .= " AND bkg_booking_id LIKE '%{$this->trans_booking}%'";
			$SqlCount2	 .= " AND bkg_booking_id LIKE '%{$this->trans_booking}%'";
		}
		if ($this->trans_user != '')
		{
			$sql1		 .= " AND usr.usr_name LIKE '%{$this->trans_user}%'";
			$sql2		 .= " AND usr.usr_name LIKE '%{$this->trans_user}%'";
			$SqlCount1	 .= " AND usr.usr_name LIKE '%{$this->trans_user}%'";
			$SqlCount2	 .= " AND usr.usr_name LIKE '%{$this->trans_user}%'";
		}
		if ($this->trans_date1 != '' && $this->trans_date2 != '')
		{
			$fromTime		 = '00:00:00';
			$toTime			 = '23:59:59';
			$date1			 = DateTimeFormat::DatePickerToDate($this->trans_date1);
			$date2			 = DateTimeFormat::DatePickerToDate($this->trans_date2);
			$fromDateTime	 = $date1 . ' ' . $fromTime;
			$toDateTime		 = $date2 . ' ' . $toTime;
			$sql1			 .= " AND pg.apg_start_datetime BETWEEN '$fromDateTime' AND '$toDateTime'";
			$sql2			 .= " AND act_date BETWEEN '$fromDateTime' AND '$toDateTime'";
			$SqlCount1		 .= " AND pg.apg_start_datetime BETWEEN '$fromDateTime' AND '$toDateTime'";
			$SqlCount2		 .= " AND act_date BETWEEN '$fromDateTime' AND '$toDateTime'";
		}
		if ($this->trans_code != '')
		{
			$sql1		 .= " AND  pg.apg_code = '{$this->trans_code}'";
			$SqlCount1	 .= " AND  pg.apg_code = '{$this->trans_code}'";
			$sql2		 .= " HAVING trans_code = '{$this->trans_code}'";
			$SqlCount2	 .= " HAVING trans_code={$this->trans_code}";
		}
		if ($this->trans_stat != '')
		{
			$sql1		 .= " AND pg.apg_status = {$this->trans_stat}";
			$SqlCount1	 .= " AND pg.apg_status = {$this->trans_stat}";
		}
		if ($this->apg_mode != '')
		{
			$sql1		 .= " AND pg.apg_mode = {$this->apg_mode}";
			$SqlCount1	 .= " AND pg.apg_mode = {$this->apg_mode}";
		}
		$sql		 = $sql1 . $sql2;
		$SqlCount	 = $SqlCount1 . $SqlCount2;

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($SqlCount) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => [
				'attributes'	 => [],
				'defaultOrder'	 => 'apg_start_datetime DESC'
			],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function verifyUserBySuccesTransaction($transCode)
	{
		$apgModel = PaymentGateway::model()->getByCode($transCode);

		$bmodel		 = Booking::model()->findByPk($apgModel->apg_booking_id);
		$bverified	 = false;
		if ($bmodel->bkgUserInfo->bkg_country_code == '91' && $bmodel->bkgUserInfo->bkg_contact_no == $bmodel->bkgUserInfo->bkg_bill_contact && $bmodel->bkgUserInfo->bkg_phone_verified != 1)
		{
			$bmodel->bkgUserInfo->bkg_verification_code	 = '';
			$bmodel->bkgUserInfo->bkg_phone_verified	 = 1;
			$bverified									 = true;
		}
		if ($bmodel->bkgUserInfo->bkg_user_email == $bmodel->bkgUserInfo->bkg_bill_email && $bmodel->bkgUserInfo->bkg_email_verified != 1)
		{
			$bmodel->bkgUserInfo->bkg_email_verified	 = 1;
			$bmodel->bkgUserInfo->bkg_verifycode_email	 = '';
			$bverified									 = true;
		}
		if ($bverified)
		{
			$bmodel->save();
			$user		 = Users::model()->findByPk($bmodel->bkgUserInfo->bkg_user_id);
			$uverified	 = false;
			if ($user && $bmodel->bkgUserInfo->bkg_bill_contact == $user->usr_mobile && $user->usr_mobile_verify != 1)
			{
				$user->usr_mobile_verify = 1;
				$uverified				 = true;
			}
			if ($user && $bmodel->bkgUserInfo->bkg_bill_email == $user->usr_email && $user->usr_email_verify != 1)
			{
				$user->usr_email_verify	 = 1;
				$uverified				 = true;
			}
			if ($uverified)
			{
				$user->save();
			}
		}
	}

	public function getPaymentMode()
	{
		$paymentModeArr = $this->paymentModeArr;
		return $paymentModeArr[$this->apg_mode];
	}

	/**
	 * 
	 * @param integer $referenceId
	 * @param integer $paymentType
	 * @param PaymentGateway $pmodel
	 * @param BookingUser | VoucherOrder $bkgUsrModel
	 * @param array $userInfo
	 * @return Object
	 */
	public static function getCheckSum($referenceId, $paymentType, $pmodel, $refModel, $userInfo)
	{
		$bookingId	 = $checkSum	 = null;
		switch ($paymentType)
		{
			case 1:
				$usrModel		 = BookingUser::saveBillingInfo($refModel, $userInfo, $referenceId);
				$bookingId		 = $referenceId;
				$accountType	 = Accounting::AT_BOOKING;
				$billFirstName	 = $usrModel->bkg_bill_fullname;
				$billEmail		 = $usrModel->bkg_bill_email;
				$billAddress	 = $usrModel->bkg_bill_address;
				$billCity		 = $usrModel->bkg_bill_city;
				$billState		 = $usrModel->bkg_bill_state;
				$billCountry	 = $usrModel->bkg_bill_country;
				$billContact	 = $usrModel->bkg_bill_contact;
				break;
			case 2:
				$accountType	 = Accounting::AT_OPERATOR;
				$billFirstName	 = $refModel['firstname'];
				$billEmail		 = $refModel['email'];
				$billAddress	 = $refModel['address'];
				$billCity		 = $refModel['city'];
				$billState		 = $refModel['state'];
				$billCountry	 = $refModel['country'];
				$billContact	 = $refModel['phone'];
				break;
			case 9:
				/* @var $refModel VoucherOrder */
				$accountType	 = Accounting::AT_VOUCHER;
				$billFirstName	 = $refModel->vor_bill_fullname;
				$billEmail		 = $refModel->vor_bill_email;
				$billAddress	 = $refModel->vor_bill_address;
				$billCity		 = $refModel->vor_bill_city;
				$billState		 = $refModel->vor_bill_state;
				$billCountry	 = $refModel->vor_bill_country;
				$billContact	 = $refModel->vor_bill_contact;
				break;
		}

		$transReferenceId	 = $referenceId;
		$paymentGateway		 = PaymentGateway::model()->add($pmodel->apg_ptp_id, $pmodel->apg_amount, $bookingId, $transReferenceId, $userInfo, $accountType);
		Logger::trace("\r\n PaymentGateway Obj : " . CJSON::encode($paymentGateway->getAttributes()));
		if ($paymentGateway->apg_id > 0)
		{
			$params['blg_ref_id'] = $paymentGateway->apg_id;
			BookingLog::model()->createLog($referenceId, "Online payment initiated ({$paymentGateway->getPaymentType()} - {$paymentGateway->apg_code})", $userInfo, BookingLog::PAYMENT_INITIATED, '', $params);
			if ($pmodel->apg_ptp_id == PaymentType::TYPE_PAYUMONEY && $pmodel->apg_amount > 0)
			{
				$suurl = "http://www.aaocab.com/payment/response/app/1";
				if ($paymentGateway)
				{
					$orderId	 = $paymentGateway->apg_code;
					$paramsList	 = array(
						'key'		 => Yii::app()->payu->merchant_key,
						'txnid'		 => $orderId,
						'amount'	 => number_format($paymentGateway->apg_amount, 1, ".", ""),
						'firstname'	 => $billFirstName,
						'email'		 => $billEmail,
						'address1'	 => $billAddress,
						'city'		 => $billCity,
						'state'		 => $billState,
						'country'	 => $billCountry,
						'phone'		 => $billContact
					);
					switch ($paymentType)
					{
						case 1:
							$paramsList['productinfo']	 = $refModel->buiBkg->bkgFromCity->cty_name . '/' . $refModel->buiBkg->bkgToCity->cty_name . '/' . $model->bkg_booking_id;
							break;
						case 2:
							$paramsList['productinfo']	 = $billFirstName . '/' . $referenceId;
							break;
						case 9:
							$paramsList['productinfo']	 = $billFirstName . '/' . $billAddress;
							break;
					}
					$paramsList['surl']				 = YII::app()->createAbsoluteUrl('payment/response/ptpid/6/app/1');
					$paramsList['furl']				 = YII::app()->createAbsoluteUrl('payment/response/ptpid/6/app/1');
					$paramsList['service_provider']	 = 'payu_paisa';
					$checkSum						 = Yii::app()->payu->getChecksumFromArray($paramsList);
					$paramsList['CHECKSUMHASH']		 = $checkSum;
					$checkSum['merchant_id']		 = Yii::app()->payu->merchant_id;
					$checkSum['method']				 = $pmodel->apg_ptp_id;

					Logger::info("\r\n checkSum : " . CJSON::encode($checkSum));
				}
			}
		}
		$checksumObj = Filter::convertToObject($checkSum);
		return $checksumObj;
	}

	public static function updateStatus($bkgid = '')
	{
		$IncompleteRecords	 = PaymentGateway::model()->getEmptystatus($bkgid);
		$c					 = count($IncompleteRecords);
		Logger::writeToConsole("Total $c records to update");
		foreach ($IncompleteRecords as $model)
		{

			try
			{
				Logger::trace(json_encode($model->getAttributes()));
				$success = PaymentGateway::model()->updateEmptyPGResponse($model);
				Logger::info("{$model->apg_id} - " . json_encode($success));
			}
			catch (Exception $e)
			{
				Logger::error($e);
			}
		}
	}

	public static function updateFailedStatus($bkgid = '')
	{
		$failedRecords	 = PaymentGateway::model()->getFailedEntryList($bkgid);
		$i				 = 0;
		//print'<pre>';print_r($failedRecords);exit;
		foreach ($failedRecords as $model)
		{
			echo "<br>";
			echo " Booking Id : " . $model->apg_booking_id;
			echo ":: Payment Type : " . $model->apg_ptp_id;
			echo ":: Payment Code : " . $model->apg_code;
			echo "<br>";
			$success = PaymentGateway::model()->updateFailedPGResponse($model);
			echo "success: ";
			$i++;
//			echo "Total $i records to Failed";
			echo "<br>";
		}
	}

	public static function fetchDetailsbyBkg($bkgid)
	{
		$sql = "    SELECT *
    FROM payment_gateway apg
    WHERE apg.apg_booking_id = $bkgid";
		return DBUtil::query($sql);
	}

	public static function getStatusById($apgid)
	{
		$pgModel	 = PaymentGateway::model()->findByPk($apgid);
		$pgObject	 = Filter::GetPGObject($pgModel->apg_ptp_id);
		$payResponse = $pgObject->getPaymentStatus($pgModel);
		return $payResponse;
	}

	/**
	 * This function is used for getting the booking status
	 * @param type $bkgCode
	 * @return \ReturnSet
	 */
	public static function getStatusByBkgCode($bkgCode)
	{
		$bkgModel	 = Booking::model()->getByCode($bkgCode);
		$params		 = ["bkgId" => $bkgModel->bkg_id];
		$sql		 = " Select  apg_status from payment_gateway Where apg_booking_id =:bkgId And apg_mode = 1";
		$status		 = DBUtil::command($sql)->queryScalar($params);
		$message	 = ($status) ? "Your money has been refunded successfully " : "Refund process is still in progress";
		$returnSet	 = new ReturnSet();
		$returnSet->setStatus(true);
		$returnSet->setMessage($message);
		return $returnSet;
	}

	public function addToWallet()
	{
		$transaction = DBUtil::beginTransaction();
		$pgModel	 = $this;
		$success	 = false;
		$amount		 = $this->apg_amount;
		try
		{
			$user		 = ($pgModel->apg_acc_trans_type == Accounting::AT_ONLINEPAYMENT) ? $pgModel->apg_trans_ref_id : $pgModel->apg_user_id;
			$walletAdded = UserWallet::add($user, $amount);
			if (!$walletAdded)
			{
				throw new Exception('Amount could not be tranfered to wallet');
			}
			$date		 = $pgModel->apg_complete_datetime;
			$userInfo	 = UserInfo::getInstance();
			$actModel	 = AccountTransactions::PaymentGatewayToUserWallet($date, $pgModel->apg_amount, $user, $pgModel->apg_id, $pgModel->apg_ptp_id, $pgModel->apg_response_message, $userInfo);
			if ($actModel && $pgModel->apg_booking_id > 0)
			{
				$params['blg_ref_id']	 = $pgModel->apg_id;
				$eventid				 = BookingLog::ADD_TO_CUSTOMER_WALLET;
				$desc					 = "Rs.$amount added to Wallet (Ref : {$pgModel->getPaymentType()} - {$pgModel->apg_code})";
				BookingLog::model()->createLog($pgModel->apg_booking_id, $desc, $userInfo, $eventid, '', $params);
			}
			else
			{
				throw new Exception('Exception in adding PaymentGatewayToUserWallet ');
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($e);
		}
		result:
		return $actModel;
	}

	/**
	 * This function is used for getting the total online payments for the booking
	 * @param type $bkgid
	 * @return dataset
	 */
	public static function getTotalOnlinePaymentByBooking($bkgid, $status = 9 )
	{
		$params	 = ['bkgid' => $bkgid, 'status' => $status];
		$where	 = '';	 

		$ledgerList	 = implode(',', Accounting::getBookingPaymentSource(false));
		$sql		 = " SELECT   apg_booking_id, sum(apg_amount) balance
		FROM     payment_gateway apg JOIN booking ON bkg_id = apg_booking_id AND bkg_status = :status
		WHERE    apg_booking_id IS NOT NULL AND apg_ledger_id IN ($ledgerList) 
		AND apg_ledger_id NOT IN (47,1,49) AND (apg_status = 1 OR (apg_status=0 AND apg_mode=1))
		AND apg_booking_id = :bkgid AND bkg_status IN (6,9) 
		GROUP BY apg_booking_id ";
		$res		 = DBUtil::queryRow($sql, null, $params);
		return $res;
	}

	public static function getTotalOnlineExpiredBalanceByBooking($bkgid, $lastPaymentMonthDuration = 4)
	{
		$params	 = ['bkgid' => $bkgid ];
		$where	 = '';
		if ($lastPaymentMonthDuration > 0)
		{
			$where			 = ' AND apg_date < DATE_SUB(NOW(),INTERVAL :month MONTH) ';
			$params['month'] = $lastPaymentMonthDuration;
		}
		$ledgerList	 = implode(',', Accounting::getBookingPaymentSource(false));
		$sql		 = " SELECT  COALESCE(SUM(apg_amount), 0)   balance 
		FROM     payment_gateway apg 
		JOIN booking ON bkg_id = apg_booking_id AND bkg_status IN (6,9)
		WHERE    apg_booking_id IS NOT NULL AND apg_ledger_id IN ($ledgerList) 
		AND apg_ledger_id NOT IN (47,1,49) AND apg_status = 1 AND apg_mode=2 
		AND apg_booking_id = :bkgid AND apg_booking_id IS NOT NULL $where ";
		$res		 = DBUtil::queryScalar($sql, null, $params);
		return $res;
	}

	public static function getTotalPendingRefundAmt($bkgid)
	{
		$ledgerList		 = implode(',', Accounting::getBookingPaymentSource(false));
		$params			 = ['bkgid' => $bkgid];
		$sql			 = "SELECT SUM(apg_amount *-1) tot FROM `payment_gateway`  
		WHERE apg_booking_id IS NOT NULL AND apg_ledger_id IN ($ledgerList) 
		AND apg_ledger_id NOT IN (47,1,49) AND apg_status=0
		AND apg_booking_id = :bkgid AND apg_active=1 GROUP BY apg_booking_id";
		$remainingRefund = DBUtil::queryScalar($sql, null, $params);
		return ($remainingRefund | 0);
	}

	public function processBookingPostPayment()
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		Logger::trace("Process Booking Post Payment for BkgID : " . $this->apg_trans_ref_id);
		$success		 = false;
		$bankLedgerID	 = PaymentType::model()->ledgerList($this->apg_ptp_id);
		$accTransDetails = AccountTransDetails::model()->find('adt_active=1 AND adt_status=1 AND adt_ledger_id=' . $bankLedgerID . ' AND adt_trans_ref_id=:apg_id AND adt_type IN(1,4)', ['apg_id' => $this->apg_id]);
		if ($accTransDetails == NULL)
		{
			Logger::trace("Payment Update for Ledger ID : " . $bankLedgerID);

			$model = Booking::model()->findByPk($this->apg_trans_ref_id);
			if ($model->bkg_agent_id == '')
			{
				$wUserId = ($this->apg_user_id == null || $this->apg_user_id == 0 || $this->apg_user_id == '') ? $model->bkgUserInfo->bkg_user_id : $this->apg_user_id;
				UserWallet::createIfNotExist($wUserId);
			}
			$transaction = DBUtil::beginTransaction();
			try
			{
				if ($this->apg_user_id == null || $this->apg_user_id == 0 || $this->apg_user_id == '')
				{
					$this->apg_user_type = UserInfo::TYPE_CONSUMER;
					$this->apg_user_id	 = $model->bkgUserInfo->bkg_user_id;
					$this->save();
					Logger::trace("Payment updated with user id");
				}

				$useUserWallet = Config::get('user.useWallet');

				$ptpId	 = $this->apg_ptp_id;
				$amount	 = $this->apg_amount;
				if ($model->bkg_agent_id > 0)
				{
					goto skipthis;
				}
				if ($useUserWallet == 1 && $model->bkg_agent_id == '')
				{
					$this->addToWallet();
					$ptpId										 = PaymentType::TYPE_WALLET;
					$model->bkgInvoice->bkg_is_wallet_selected	 = 1;
					$model->bkgInvoice->bkg_wallet_used			 += $this->apg_amount;
					$amount										 = 0;
					Logger::trace("added to wallet");
				}
				skipthis:
				Logger::trace("Before update advance amount " . $model->bkg_id . " : advance amount " . $model->bkgInvoice->bkg_wallet_used);
				$userInfo		 = UserInfo::model($this->apg_user_type, $this->apg_user_id);
				$isUpdateAdvance = $model->updateAdvance($amount, $this->apg_date, $ptpId, $userInfo, $this, $this->apg_response_message);
				Logger::trace("UPDATE ADVANCE success : " . $isUpdateAdvance);
				Logger::trace("PaymentGateway->processTransaction :: before confirm -- Bkgid=" . $model->bkg_id . "  :: Status:" . $model->bkg_status . " advance amount ==" . $model->bkgInvoice->bkg_wallet_used);

				if ($isUpdateAdvance)
				{
					$model->refresh();
					$model->bkgInvoice->refresh();
					Logger::trace("Before booking confirm " . $model->bkg_id . " : advance amount " . $model->bkgInvoice->bkg_advance_amount);
					if ($model->bkg_status == 15 && $model->bkgInvoice->bkg_promo1_code != '')
					{
						//		$model->bkgInvoice->applyPromoCoins();
						$model->bkgInvoice->save();
					}
					Logger::trace("booking confirm " . $model->bkg_id . " :advance amount " . $model->bkgInvoice->bkg_advance_amount);

					$sendConfirmMessages = true;
					//reschedulebooking new
					if ($model->bkgPref->bpr_rescheduled_from > 0)
					{
						$oldModel = Booking::model()->findByPk($model->bkgPref->bpr_rescheduled_from);
						if (!in_array($oldModel->bkg_status, [9, 10]))
						{
							$sendConfirmMessages = false;
						}
					}

					$model->confirm(true, $sendConfirmMessages, $model->bkg_id, $userInfo, $isAllowed = true);
					Logger::trace("after booking confirm " . $model->bkg_id . " :advance amount " . $model->bkgInvoice->bkg_advance_amount);
//					$model->refresh();
//					if ($model->bkgPref->bkg_is_gozonow == 1 && $model->bkg_status == 2)
//					{
//						BookingCab::assignPreferredVendorDriverCab($model->bkg_bcb_id);
//					}
					if ($model->bkg_status == Booking::STATUS_VERIFY_CANCELLED && $model->bkgInvoice->bkg_advance_amount > 0)
					{
						$model->bkg_status = Booking::STATUS_CANCELLED;
						$model->save();
					}

					Logger::trace("Booking Confirmed Advanced = " . "bookingId " . $model->bkg_id . " :advance amount " . $model->bkgInvoice->bkg_advance_amount);
				}
				DBUtil::commitTransaction($transaction);
				$success = true;
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($e);
			}
			Logger::trace("PaymentGateway->processTransaction :: after confirm -- Bkgid=" . $model->bkg_id . "  :: Status:" . $model->bkg_status . " Advanced = " . $model->bkgInvoice->bkg_advance_amount);

//			if ($success && PaymentType::isOnline($this->apg_ptp_id) && $model->bkgUserInfo->bkg_user_id > 0)
//			{
//				$smsWrapper = new smsWrapper();
//				$smsWrapper->paymentSuccessMsgCustomer($model->bkg_id, $this->apg_amount);
//			}
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	public static function getTransactionsByBooking($bkgId)
	{
		$param	 = ['bkgid' => $bkgId];
		$sql	 = "SELECT * FROM payment_gateway 
		WHERE apg_active=1 AND 
			apg_status=1 AND 
			apg_mode=2 AND 
			apg_trans_ref_id=:bkgid AND 
			apg_acc_trans_type = 1 
			ORDER BY apg_date DESC";
		$data	 = DBUtil::query($sql, null, $param);
		return $data;
	}

	public static function refundToPGByBookingid($bkgId, $amount, $userInfo)
	{

		$data = PaymentGateway::getTransactionsByBooking($bkgId);

		foreach ($data as $row)
		{
			$allowedRefund	 = PaymentGateway::model()->getAllowedRefundAmt($row['apg_id']);
			$refundAmount	 = min([$amount, $row['apg_amount'], $allowedRefund]);
			if ($refundAmount <= 0)
			{
				continue;
			}
			Logger::create("PaymentGateway::refundToPGByBookingid  refundAmount: {$refundAmount}", CLogger::LEVEL_INFO);
			$paymentGateway	 = PaymentGateway::refundEntry($row['apg_id'], $refundAmount, $userInfo);
			$success		 = PaymentGateway::processPGrefund($paymentGateway);
			if ($success)
			{
				$refund	 += $refundAmount;
				Logger::create("Refund Amount : " . $refund, CLogger::LEVEL_TRACE);
				$amount	 -= $refundAmount;
				Logger::create("Refund Amount : " . $amount, CLogger::LEVEL_TRACE);
			}

			if ($amount <= 0)
			{
				break;
			}
		}
	}

	public static function refundEntry($apgid, $amount, $userInfo)
	{
		$refAmount							 = $amount;
		$pgModel							 = PaymentGateway::model()->findByPk($apgid);
		$success							 = false;
		$transaction						 = DBUtil::beginTransaction();
		$appendValue						 = rand(000, 999);
		$paymentGateway						 = new PaymentGateway();
		$paymentGateway->apg_booking_id		 = $pgModel->apg_booking_id;
		$paymentGateway->apg_acc_trans_type	 = $pgModel->apg_acc_trans_type;
		$paymentGateway->apg_trans_ref_id	 = $pgModel->apg_trans_ref_id;
		$paymentGateway->apg_ptp_id			 = $pgModel->apg_ptp_id;
		$paymentGateway->apg_ledger_id		 = $pgModel->apg_ledger_id;
		$paymentGateway->apg_amount			 = ($amount > 0) ? -1 * $amount : $amount;
		$paymentGateway->apg_mode			 = 1;
		$paymentGateway->apg_code			 = date('ymd') . str_pad($appendValue, 9, 0, STR_PAD_LEFT);
		$paymentGateway->apg_remarks		 = "Refund Initiated";
		$paymentGateway->apg_user_type		 = $pgModel->apg_user_type;
		$paymentGateway->apg_user_id		 = $pgModel->apg_user_id;
		$paymentGateway->apg_status			 = 0;
		$paymentGateway->apg_date			 = new CDbExpression('NOW()');
		$paymentGateway->apg_ref_id			 = $pgModel->apg_id;
		$paymentGateway->apg_merchant_ref_id = $pgModel->apg_txn_id;
		$paymentGateway->scenario			 = 'create';
		if ($paymentGateway->validate())
		{
			if ($paymentGateway->save())
			{
				UserWallet::lockBalance($pgModel->apg_user_id, $refAmount);
				$paymentGateway->apg_code	 = $paymentGateway->generateNewTransCode();
				$success					 = $paymentGateway->save();
				// Booking Log
				$params['blg_ref_id']		 = $paymentGateway->apg_id;
				$eventid					 = BookingLog::REFUND_PROCESS_INITIATED;
				$desc						 = "Online Refund initiated ({$paymentGateway->getPaymentType()} - {$paymentGateway->apg_code})";
				BookingLog::model()->createLog($paymentGateway->apg_booking_id, $desc, $userInfo, $eventid, '', $params);
				Logger::create("PaymentGateway::refundEntry apg_id:{$paymentGateway->apg_id}", CLogger::LEVEL_INFO);
			}
			else
			{
				$errors = json_encode($paymentGateway->getErrors());
				Logger::create("PaymentGateway::refundEntry errors:{$errors}", CLogger::LEVEL_INFO);
				DBUtil::rollbackTransaction($transaction);
				return false;
			}
		}
		DBUtil::commitTransaction($transaction);
		return $paymentGateway;
	}

	public static function processPGrefund($paymentGateway)
	{
		$pgObject	 = Filter::GetPGObject($paymentGateway->apg_ptp_id);
		/* @var $payResponse PaymentResponse */
		$payResponse = $pgObject->refund($paymentGateway);
//$payResponse->payment_status=0;

		$success = PaymentGateway::updatePGrefund($paymentGateway, $payResponse);
		return $success;
	}

	public static function updatePGrefund($paymentGateway, $payResponse)
	{
		$transaction = DBUtil::beginTransaction();
		$success	 = $paymentGateway->updateTransResponse($payResponse);
		Logger::create("PaymentGateway::updateRefundStatus  :: updateTransResponse : " . $success);
		$userInfo	 = UserInfo::getInstance();

		if ($success && $payResponse->payment_status == 1)
		{
			$amount = -1 * $paymentGateway->apg_amount;

			$date = $paymentGateway->apg_complete_datetime;

			$actModel = AccountTransactions::UserWalletToPaymentGateway($date, $amount, $paymentGateway->apg_user_id, $paymentGateway->apg_id, $paymentGateway->apg_ptp_id, $paymentGateway->apg_response_message, $userInfo);
			UserWallet::releaseLockedBalance($paymentGateway->apg_user_id, $paymentGateway->apg_amount);
			DBUtil::commitTransaction($transaction);
			if ($actModel)
			{
				$params['blg_ref_id']	 = $paymentGateway->apg_id;
				$eventid				 = BookingLog::REFUND_PROCESS_COMPLETED;
				$desc					 = "Rs.$amount refunded from Wallet (Ref : {$paymentGateway->getPaymentType()} - {$paymentGateway->apg_code})";
				BookingLog::model()->createLog($paymentGateway->apg_booking_id, $desc, $userInfo, $eventid, '', $params);

				$paymentType = PaymentGateway::getPaymentTypeName($paymentGateway->apg_ledger_id);
				$emailObj	 = new emailWrapper();
				$emailObj->refundFromWalletToSource($paymentGateway->apg_booking_id, $amount, $paymentType);
			}
			Logger::create("PaymentGateway::updatePGrefund  if success and payment_status=1");
			return $success;
		}
		if ($success && $payResponse->payment_status == 2)
		{
			Logger::create("PaymentGateway::updatePGrefund  if success and payment_status=2 ");
			UserWallet::releaseLockedBalance($paymentGateway->apg_user_id, $paymentGateway->apg_amount);
		}
		DBUtil::rollbackTransaction($transaction);
	}

	public function processRefundStatus($payResponse)
	{
		$success = PaymentGateway::updatePGrefund($this, $payResponse);
		return $success;
	}

	public function getUserPaymentDetails($userId, $userType)
	{
		$param			 = ['userid' => $userId, 'usertype' => $userType];
		$sql			 = "SELECT * FROM payment_gateway 
		join booking on booking.bkg_id = payment_gateway.apg_booking_id
		WHERE apg_user_type = :usertype AND apg_user_id = :userid
			ORDER BY apg_date DESC";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $param);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['defaultOrder' => 'apg_date DESC'],
			'pagination'	 => ['pageSize' => 20],
			'params'		 => $param,
		]);
		return $dataprovider;
	}

	public static function getByRzpOrderId($orderId)
	{
		$params	 = ['orderId' => $orderId];
		$sql	 = "SELECT apg_code FROM `payment_gateway` WHERE `apg_pre_txn_id` = :orderId AND apg_ptp_id = 21 ORDER BY `apg_id` DESC";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	public static function getByRzpPaymentId($paymentId)
	{
		$params	 = ['paymentId' => $paymentId];
		$sql	 = "SELECT apg_code FROM `payment_gateway` WHERE `apg_txn_id` = :paymentId ORDER BY `apg_id` DESC";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	public static function initiatePayment($pmodel, $billingObj = null, $promoObj = null)
	{
		$userInfo = UserInfo::getInstance();

		switch ($pmodel->apg_acc_trans_type)
		{
			case 1:
				if ($billingObj != null)
				{
					$userModel	 = $billingObj->getData();
					$refModel	 = $userModel;
					$usrModel	 = BookingUser::saveBillingInfo($refModel, $userInfo, $pmodel->apg_trans_ref_id);
				}
				$bookingId	 = $pmodel->apg_trans_ref_id;
				$accountType = Accounting::AT_BOOKING;
				break;
			case 2:
				$accountType = Accounting::AT_OPERATOR;
				$bookingId	 = null;
				break;
			case 9:
				/* @var $refModel VoucherOrder */
				if ($billingObj != null)
				{
					$vmodel		 = $billingObj->getVoucherData();
					$refModel	 = $vmodel;
				}
				$accountType = Accounting::AT_VOUCHER;
				break;
		}

		$transReferenceId	 = $pmodel->apg_trans_ref_id;
		$paymentGateway		 = PaymentGateway::model()->add($pmodel->apg_ptp_id, $pmodel->apg_amount, $bookingId, $transReferenceId, $userInfo, $accountType);
		if (!$paymentGateway->apg_id)
		{
			throw new Exception("Unknown Exception");
		}
		$payRequest	 = PaymentGateway::model()->getPGRequest($paymentGateway->apg_id);
		$pgObject	 = Filter::GetPGObject($payRequest->payment_type);
		$response	 = $pgObject->initiateRequest($payRequest);
		if (!$response['success'])
		{
			throw new Exception("Error occurred in payment initiation");
		}
		if ($pmodel->apg_acc_trans_type == 1)
		{
			$bkgModel = Booking::model()->findByPk($pmodel->apg_trans_ref_id);
			$bkgModel->bkgInvoice->savePromoCoins($promoObj->promo->code, $promoObj->gozoCoins);
		}
		$response['secret'] = $pgObject->secret;
		return $response;
	}

	public function paymentSummaryReport($data = [], $groupBy = 'date', $ptpId = '', $retrunType = DBUtil::ReturnType_Provider)
	{
		$fromDate	 = $data['from_date'] . ' 00:00:00';
		$toDate		 = $data['to_date'] . ' 23:59:59';
		$dateFormat	 = ['date' => '%Y-%m-%d', 'week' => '%x-%v', 'month' => '%Y-%m'];

		$cond = '';
		if ($ptpId > 0)
		{
			$cond = " AND apg_ptp_id = {$ptpId} ";
		}

		$date				 = $dateFormat[$groupBy];
		$params				 = [];
		$params['format']	 = $date;
		$params['fromDate']	 = $fromDate;
		$params['toDate']	 = $toDate;

		$sql			 = "SELECT apg_ptp_id, 
			DATE_FORMAT(apg_date, :format) as date, 
			SUM(IF(apg_mode = 2, apg_amount, 0)) receive, 
			SUM(IF(apg_mode = 1, apg_amount, 0)) refund, 
			SUM(apg_amount) net 
			FROM payment_gateway 
			WHERE apg_date BETWEEN :fromDate AND :toDate 
			AND apg_status = 1 AND apg_active = 1 AND apg_txn_id IS NOT NULL AND apg_txn_id<>'' {$cond} 
			GROUP BY date, apg_ptp_id";
		$command		 = DBUtil::command($sql, DBUtil::SDB());
		$command->params = $params;
		if ($retrunType == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(1) FROM ({$command->getText()} ) temp", DBUtil::SDB(), $command->params);
			$dataProvider	 = new CSqlDataProvider($command, [
				"params"		 => $params,
				"totalItemCount" => $count,
				"params"		 => $command->params,
				'db'			 => DBUtil ::SDB(),
				'pagination'	 => array('pageSize' => 50),
				'sort'			 => [
					'attributes'	 => ['date'],
					'defaultOrder'	 => 'date DESC'
				],
			]);
			return $dataProvider;
		}
		else
		{
			return DBUtil::query($command->getText(), DBUtil::SDB(), $command->params);
		}
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return type
	 */
	public static function extendLastPaymentInitiatedDate($bkgId, $apgDuration = '30')
	{
		$now	 = Filter::getDBDateTime();
		$apgDate = self::getLastPaymentInitiatedDateByBkg($bkgId);
		if ($apgDate)
		{
			$apgExtendTime = date('Y-m-d H:i:s', strtotime('+' . $apgDuration . ' minutes', strtotime($apgDate)));
			return $apgExtendTime;
		}
		return false;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return type
	 */
	public static function getLastPaymentInitiatedDateByBkg($bkgId)
	{
		$params	 = ['bkgid' => $bkgId];
		$sql	 = "SELECT max(apg_date) FROM payment_gateway 
				WHERE apg_booking_id = :bkgid AND apg_active = 1
				ORDER BY apg_date DESC";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	public function payList($command = false)
	{
		$where					 = 1;
		$whereCount				 = 1;
		$this->tranasctionFor	 = ($this->tranasctionFor == '') ? 1 : $this->tranasctionFor;
		$join					 = "LEFT JOIN booking book ON book.bkg_id = pg.apg_trans_ref_id AND book.bkg_status IN(1,2, 3, 5, 6, 7, 9, 10,15) ";
		$select					 = " ,book.bkg_booking_id,book.bkg_id";
		if ($this->tranasctionFor == 2)
		{
			$join	 = "LEFT JOIN vendors ON pg.apg_trans_ref_id = vendors.vnd_id ";
			$select	 = " ,vendors.vnd_name,vendors.vnd_id";
		}
		$this->apg_ledger_id = ($this->apg_ledger_id == '') ? 53 : $this->apg_ledger_id;
		if ($this->apg_ledger_id != '')
		{
			$where		 .= " AND pg.apg_ledger_id = {$this->apg_ledger_id}";
			//$sql2		 .= " AND adt_ledger_id = {$this->apg_ledger_id}";
			$whereCount	 .= " AND pg.apg_ledger_id = {$this->apg_ledger_id}";
			//$SqlCount2	 .= " AND adt_ledger_id = {$this->apg_ledger_id}";
		}
		if ($this->apg_mode != '')
		{
			$where		 .= " AND pg.apg_mode = {$this->apg_mode}";
			$whereCount	 .= " AND pg.apg_mode = {$this->apg_mode}";
		}
		if ($this->trans_code != '')
		{
			$where		 .= " AND  pg.apg_code = '{$this->trans_code}'";
			$whereCount	 .= " AND  pg.apg_code = '{$this->trans_code}'";
		}

		if (count($this->trans_stat) > 0 && $this->trans_stat != NULL)
		{
			if (is_array($this->trans_stat))
			{
				$trans_stat = implode(',', $this->trans_stat);
			}
			else
			{
				$trans_stat = $this->trans_stat;
			}
			$where		 .= " AND pg.apg_status IN ({$trans_stat})";
			$whereCount	 .= " AND pg.apg_status IN ({$trans_stat})";
		}
		else
		{
			$this->trans_stat	 = 1;
			$where				 .= " AND pg.apg_status IN (1)";
			$whereCount			 .= " AND pg.apg_status IN (1)";
		}
		if ($this->trans_booking != '')
		{
			$where		 .= " AND bkg_booking_id LIKE '%{$this->trans_booking}%'";
			$whereCount	 .= " AND bkg_booking_id LIKE '%{$this->trans_booking}%'";
		}
		if ($this->trans_date1 == '' && $this->trans_date2 == '')
		{
			$this->trans_date1	 = date('d/m/Y', strtotime("-1 days"));
			$this->trans_date2	 = date('d/m/Y', strtotime("-1 days"));
		}

		if ($this->trans_date1 != '' && $this->trans_date2 != '')
		{
			$fromTime		 = '00:00:00';
			$toTime			 = '23:59:59';
			$date1			 = DateTimeFormat::DatePickerToDate($this->trans_date1);
			$date2			 = DateTimeFormat::DatePickerToDate($this->trans_date2);
			$fromDateTime	 = $date1 . ' ' . $fromTime;
			$toDateTime		 = $date2 . ' ' . $toTime;
			$where			 .= " AND pg.apg_start_datetime BETWEEN '$fromDateTime' AND '$toDateTime'";
			$whereCount		 .= " AND pg.apg_start_datetime BETWEEN '$fromDateTime' AND '$toDateTime'";
		}
		$sql = "SELECT  pg.apg_code trans_code,
					pg.apg_id,
					pg.apg_amount,
					pg.apg_acc_trans_type $select,
					pg.apg_code refundOrderCode,
					pg.apg_ptp_id trans_ptp,
    CASE 
        WHEN pg.apg_mode = '2' THEN 'Credit'
        WHEN pg.apg_mode = '1' THEN 'Debit'
        ELSE 1
           END AS apg_mode,    
				CASE 
	WHEN pg.apg_ptp_id = '2' THEN 'Bank'
    WHEN pg.apg_ptp_id = '3' THEN 'PayTM'
    WHEN pg.apg_ptp_id = '4' THEN 'EBS'
	WHEN pg.apg_ptp_id = '5' THEN 'GozoCoins'
	WHEN pg.apg_ptp_id = '6' THEN 'PayUMoney'
	WHEN pg.apg_ptp_id = '13' THEN 'Partner Wallet'
	WHEN pg.apg_ptp_id = '17' THEN 'Wallet'
	WHEN pg.apg_ptp_id = '21' THEN 'RazorPay'
	WHEN pg.apg_ptp_id = '23' THEN 'EaseBuzz'
				ELSE 1
				END AS trans_ptp_text,
					IF(pg.apg_amount > 0,'DEBIT','CREDIT') trans_mode,
					
									pg.apg_status trans_status,
					pg.apg_remarks trans_response_message,
					pg.apg_start_datetime,
					pg.apg_start_datetime trans_start_datetime,
					pg.apg_complete_datetime trans_complete_datetime,pg.apg_txn_id, pg.apg_response_details
	FROM `payment_gateway`pg
	$join
	WHERE $where AND pg.apg_acc_trans_type = $this->tranasctionFor AND  pg.apg_mode IN(1, 2)  AND pg.apg_active = 1";

		$SqlCount = "SELECT pg.apg_code as trans_code,
				pg.apg_id
	FROM `payment_gateway`pg
	$join
	WHERE $whereCount AND  pg.apg_acc_trans_type = $this->tranasctionFor AND pg.apg_mode IN(1, 2)  AND pg.apg_active = 1  ";

		if ($command == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($SqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => [
					'attributes'	 => [],
					'defaultOrder'	 => 'apg_start_datetime DESC'
				],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
	}

	public static function fetchTransactionsByBooking($bkgId)
	{
		$param	 = ['bkgid' => $bkgId];
		$sql	 = "SELECT 
			apg.apg_id, apg.apg_ptp_id,apg.apg_code, apg.apg_ledger_id, acl.ledgerName, apg.apg_booking_id, 
			apg.apg_mode, apg.apg_user_type, apg.apg_user_id , apg.apg_amount, apg.apg_txn_id,apg.apg_start_datetime,apg.apg_complete_datetime,
			apg.apg_status, apg.apg_remarks
		FROM payment_gateway apg 
		JOIN account_ledger acl ON acl.ledgerId = apg.apg_ledger_id		
		WHERE apg_active=1 AND apg_trans_ref_id=:bkgid AND apg_acc_trans_type = 1 
		ORDER BY apg_date DESC";
		$data	 = DBUtil::query($sql, null, $param);
		return $data;
	}

	public function registerPayment()
	{
		$userInfo = UserInfo::getInstance();

		$accountType = $this->apg_acc_trans_type;
		$transRefId	 = $this->apg_trans_ref_id;
		$pgModel	 = PaymentGateway::model()->add($this->apg_ptp_id, $this->apg_amount, null, $transRefId, $userInfo, $accountType);

		return $pgModel;
	}

	public static function checkDuplicateVendorPayment($vendorId, $amount)
	{
		$result	 = true;
		//$d       = new CDbExpression('NOW()');		
		$date	 = date('Y-m-d', strtotime(DBUtil::getCurrentTime()));
		$params	 = ['vendorId' => $vendorId, 'amount' => $amount, 'date' => $date];
		$sql	 = "SELECT COUNT(apg_id) cnt	
					FROM payment_gateway
					WHERE apg_active=1 AND apg_trans_ref_id =:vendorId AND apg_acc_trans_type = 2 AND apg_ledger_id = 30 
					AND apg_amount =:amount AND apg_status = 1 AND DATE(apg_date) =:date AND apg_mode = 2 AND apg_user_type = 4";
		$data	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		if ($data > 0)
		{
			$result = false;
		}
		return $result;
	}

	public static function getLastPaymentStatus($bkgId)
	{
		$params	 = ['bkgid' => $bkgId];
		$sql	 = "SELECT cntSuccess,cntFailed,apg.apg_status lastStatus FROM payment_gateway apg 
			INNER JOIN ( 
				SELECT SUM(if(apg_status=1,1,0)) cntSuccess, SUM(if(apg_status=2,1,0)) cntFailed,
				max(apg_id) lastApgid
				FROM payment_gateway 
				WHERE apg_booking_id = :bkgid AND apg_active = 1 ) lastapg
			ON apg.apg_id = lastapg.lastApgid";
		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}
}
