<?php

/**
 * This is the model class for table "agent_transactions".
 *
 * The followings are the available columns in table 'agent_transactions':
 * @property integer $agt_trans_id
 * @property integer $agt_ptp_id
 * @property integer $agt_agent_id
 * @property integer $agt_booking_id
 * @property integer $agt_trip_id
 * @property string $agt_trans_code
 * @property integer $agt_trans_type
 * @property integer $agt_trans_mode
 * @property string $agt_trans_remarks
 * @property double $agt_trans_amount
 * @property integer $agt_admin_id
 * @property integer $agt_trans_active
 * @property string $agt_trans_created
 * @property string $agt_trans_response_details
 * @property string $agt_trans_date
 * @property integer $agt_trans_status
 * @property string $agt_trans_start_datetime
 * @property string $agt_trans_txn_id
 * @property string $agt_trans_response_code
 * @property string $agt_trans_ipaddress
 * @property string $agt_trans_device_detail
 * @property integer $agt_trans_user_id
 * @property string $agt_trans_response_message
 * @property string $agt_trans_complete_datetime
 * @property integer $agt_trans_ref_id
 */
class AgentTransactions extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	const GOZO_PAID_AGENT	 = 1;
	const AGENT_PAID_GOZO	 = -1;

	public $bank_chq_no, $bank_chq_dated, $bank_name, $bank_ifsc, $bank_branch,
			$bank_trans_type, $trans_ptp, $trans_amount, $trans_desc, $operator_id,
			$bank_trans_id, $trans_create_date1, $trans_create_date2;
	public $modeList	 = [1 => 'Receive from Agent (Debit)', 2 => 'Payment to Agent (Credit)'];
	public $operatorList = [0 => 'Agent Paid Gozo', 1 => 'Gozo Paid Agent'];

	public function tableName()
	{
		return 'agent_transactions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('agt_trans_created', 'required'),
			array('agt_ptp_id,agt_trans_mode,agt_trans_amount', 'required', 'on' => 'insert'),
			array('agt_ptp_id,agt_trans_mode,agt_trans_remarks,agt_trans_amount,agt_trans_date,operator_id', 'required', 'on' => 'ledgerbooking'),
			array('trans_ptp,agt_trans_mode,trans_amount', 'required', 'on' => 'create'),
			array('agt_ptp_id, agt_agent_id, agt_booking_id, agt_trip_id, agt_trans_type, agt_trans_mode, agt_admin_id, agt_trans_active', 'numerical', 'integerOnly' => true),
			array('agt_trans_amount', 'numerical'),
			array('agt_trans_code', 'length', 'max' => 50),
			array('agt_trans_remarks', 'length', 'max' => 200),
			array('agt_trans_response_details', 'length', 'max' => 1500),
			array('agt_trans_date', 'safe'),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('agt_trans_id,agt_trans_ref_id, agt_ptp_id,agt_trans_ipaddress,agt_trans_user_id,agt_trans_response_message,agt_trans_complete_datetime,agt_trans_device_detail,agt_trans_response_code,agt_trans_txn_id,agt_trans_status,agt_trans_start_datetime, agt_agent_id, agt_booking_id, agt_trip_id, agt_trans_code, agt_trans_type, agt_trans_mode, agt_trans_remarks, agt_trans_amount, agt_admin_id, agt_trans_active, agt_trans_created, agt_trans_response_details, agt_trans_date,operator_id,bank_name,bank_branch,bank_chq_no,bank_chq_dated,bank_ifsc,bank_trans_type,trans_ptp,trans_amount,trans_desc', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'agt_trans_id'				 => 'Trans Id',
			'agt_ptp_id'				 => 'Payment Type',
			'trans_ptp'					 => 'Payment Type',
			'agt_agent_id'				 => 'Agent Id',
			'agt_booking_id'			 => 'Booking Id',
			'agt_trip_id'				 => 'Trip Id',
			'agt_trans_code'			 => 'Trans Code',
			'agt_trans_type'			 => 'Trans Type',
			'agt_trans_mode'			 => 'Trans Mode',
			'agt_trans_remarks'			 => 'Trans Remarks',
			'agt_trans_amount'			 => 'Trans Amount',
			'trans_amount'				 => 'Transaction Amount',
			'agt_admin_id'				 => 'Admin Id',
			'agt_trans_active'			 => 'Trans Active',
			'agt_trans_created'			 => 'Trans Created',
			'agt_trans_response_details' => 'Trans Response Details',
			'agt_trans_date'			 => 'Trans Date',
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

		$criteria->compare('agt_trans_id', $this->agt_trans_id);
		$criteria->compare('agt_ptp_id', $this->agt_ptp_id);
		$criteria->compare('agt_agent_id', $this->agt_agent_id);
		$criteria->compare('agt_booking_id', $this->agt_booking_id);
		$criteria->compare('agt_trip_id', $this->agt_trip_id);
		$criteria->compare('agt_trans_code', $this->agt_trans_code, true);
		$criteria->compare('agt_trans_type', $this->agt_trans_type);
		$criteria->compare('agt_trans_mode', $this->agt_trans_mode);
		$criteria->compare('agt_trans_remarks', $this->agt_trans_remarks, true);
		$criteria->compare('agt_trans_amount', $this->agt_trans_amount);
		$criteria->compare('agt_admin_id', $this->agt_admin_id);
		$criteria->compare('agt_trans_active', $this->agt_trans_active);
		$criteria->compare('agt_trans_created', $this->agt_trans_created, true);
		$criteria->compare('agt_trans_response_details', $this->agt_trans_response_details, true);
		$criteria->compare('agt_trans_date', $this->agt_trans_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgentTransactions the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getPaymentModeValue($val)
	{
		switch ($val)
		{
			case '0':
				return AgentTransactions::GOZO_PAID_AGENT;
				break;
			case '1':
				return AgentTransactions::AGENT_PAID_GOZO;
				break;
		}
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

	public function generateTranscode()
	{
		$todayCount	 = $this->getTodaysCount();
		$appendValue = $todayCount + 1;
		$transcode	 = date('ymdHis') . str_pad($appendValue, 3, 0, STR_PAD_LEFT);
		return $transcode;
	}

	public function getTodaysCount()
	{
		$cdb		 = Yii::app()->db->createCommand();
		$cdb->select = "COUNT(*) as cnt";
		$cdb->from	 = $this->tableName();
		$cdb->where	 = 'date(agt_trans_created) = CURDATE()';
		$cnt		 = $cdb->queryScalar();
		return $cnt;
	}

	public function getLastEntrybyBkgid($bkgid)
	{
		$criteria		 = new CDbCriteria;
		$criteria->compare('agt_booking_id', $bkgid);
		$criteria->order = "agt_trans_date DESC, agt_trans_id DESC";
		$criteria->limit = 1;
		return $this->find($criteria);
	}

	public function addBookingTransactionData($bkid, $agentid, $amount, $remarks = '', $ignoreActiveBooking = false, $userInfo, $bkgTransaction = true, $verfiyAdvance = true)
	{

		$bkmodel = Booking::model()->findByPk($bkid);
		Logger::create("21");

		$success = false;
		if ($amount != 0)
		{
			if ($bkmodel && (($bkmodel->bkg_status >= 1 && $bkmodel->bkg_status <= 7) || $ignoreActiveBooking))
			{
				$accTransType	 = Accounting::AT_BOOKING;
				$ptpid			 = PaymentType::TYPE_AGENT_CORP_CREDIT;
				$status			 = (!$bkgTransaction) ? 1 : 0;

				$bankLedgerId						 = PaymentType::model()->ledgerList($ptpid);
				$paymentGateway						 = new PaymentGateway();
				$paymentGateway->apg_booking_id		 = $bkid;
				$paymentGateway->apg_acc_trans_type	 = Accounting::AT_BOOKING;
				$paymentGateway->apg_trans_ref_id	 = $bkmodel->bkg_id;
				$paymentGateway->apg_ptp_id			 = $ptpid;
				$paymentGateway->apg_amount			 = $amount;
				$paymentGateway->apg_remarks		 = "Payment Initiated";
				$paymentGateway->apg_ref_id			 = '';
				$paymentGateway->apg_user_type		 = UserInfo::TYPE_SYSTEM;
				$paymentGateway->apg_user_id		 = 0;
				$paymentGateway->apg_status			 = 0;
				$paymentGateway->apg_date			 = $bkmodel->bkg_pickup_date;

				$transModel = PaymentGateway::model()->payment($bankLedgerId);

				Logger::create("22");
				if ($transModel && $bkgTransaction)
				{
					$success = false;
					$ptpid	 = PaymentType::TYPE_AGENT_CORP_CREDIT;

					Logger::create("23");
					$arr['remarks']						 = "$remarks";
					$transModel->apg_response_details	 = CJSON::encode($arr);

					if ($amount > 0)
					{
						Booking::model()->updateAcctAdvance($transModel->apg_code, $transModel->apg_response_details, $userInfo, 0, $verfiyAdvance);
						Logger::create("24");
					}
					else
					{
						Booking::model()->updateAccRefund($transModel->apg_code, $transModel->apg_response_details, $userInfo);
						Logger::create("25");
					}

					$success = true;
				}
			}
			return $success;
		}
		return true;
	}

	public function agentTransactionList($paramArray = [])
	{
		$agentId = $paramArray['agentId'] | 0;
		$where	 = '';
		if ($paramArray['trans_mode'] != "")
		{
			$where .= " AND agt_trans_mode = " . $paramArray['trans_mode'];
		}
		if ($paramArray['bkg_create_date1'] != "" && $paramArray['bkg_create_date2'] != "")
		{
			$where .= " AND (DATE(bkg_create_date) BETWEEN '{$paramArray['bkg_create_date1']}' AND '{$paramArray['bkg_create_date2']}' )";
		}
		if ($paramArray['bkg_pickup_date1'] != "" && $paramArray['bkg_pickup_date2'] != "")
		{
			$where .= " AND (DATE(bkg_pickup_date) BETWEEN '{$paramArray['bkg_pickup_date1']}' AND '{$paramArray['bkg_pickup_date2']}' )";
		}
		if ($paramArray['agt_trans_created1'] != "" && $paramArray['agt_trans_created2'] != "")
		{
			$where .= " AND (DATE(agt_trans_created) BETWEEN '{$paramArray['agt_trans_created1']}' AND '{$paramArray['agt_trans_created2']}' )";
		}
		$search = $paramArray['search'];
		if ($search != "")
		{
			$fields		 = ['bkg_booking_id', 'bkg_user_fname', 'bkg_user_lname', 'bkg_status',
				'bkg_contact_no', 'bkg_alt_contact_no', 'bkg_user_email',
				'bkg_pickup_address', 'bkg_drop_address', 'bkg_bcb_id',
				'bkg_instruction_to_driver_vendor'];
			$arrSearch	 = array_filter(explode(" ", $search));
			$search1	 = [];
			foreach ($arrSearch as $val)
			{
				$arr = [];
				$key = array_search($val, $this->getTags());
				if ($key > 0)
				{
					$arr[] = "FIND_IN_SET($key,REPLACE(bkg_tags,' ',''))";
				}
				foreach ($fields as $field)
				{
					$arr[] = "$field LIKE '%{$val}%'";
				}
				$search1[] = "(" . implode(' OR ', $arr) . ")";
			}

			$where .= " AND " . implode(" AND ", $search1);
		}

		$sql = "SELECT agtrans.agt_trans_amount,
            agtrans.agt_trans_mode,
            agtrans.agt_trans_created,
            adm.adm_fname,
                 concat(booking_user.bkg_user_fname,' ',booking_user.bkg_user_lname)bkg_user_name,
                booking.bkg_id,
                booking.bkg_booking_id,
                booking.bkg_pickup_date,
                booking.bkg_create_date,
                booking_invoice.bkg_total_amount,
                booking_invoice.bkg_advance_amount,
                booking_invoice.bkg_agent_markup,
                booking.bkg_status,
                IFNULL(booking_invoice.bkg_corporate_credit,0) bkg_credits_used,
               (SELECT   sum(agt2.agt_trans_amount)
                          FROM     agent_transactions agt2
                          WHERE    agt2.agt_trans_id <= agtrans.agt_trans_id
                          and agt2.agt_agent_id = $agentId
                          ORDER BY agt2.agt_trans_id asc) tot_trans_amount,
                DATE_FORMAT(agtrans.agt_trans_date,'%d-%m-%Y') agt_trans_date,
                agents.agt_fname
                FROM `agent_transactions` agtrans
                LEFT JOIN `booking` ON booking.bkg_id=agtrans.agt_booking_id
                INNER JOIN `booking_user` ON booking.bkg_id=booking_user.bui_bkg_id
                INNER JOIN `booking_invoice` ON booking.bkg_id=booking_invoice.biv_bkg_id
                LEFT JOIN `agents` ON agents.agt_id=booking.bkg_agent_id
                LEFT JOIN `admins` adm ON agtrans.agt_admin_id=adm.adm_id
                WHERE agtrans.agt_trans_active = 1 AND agtrans.agt_trans_status = 1
                ";

		if ($agentId != '0')
		{
			$sql .= " AND agtrans.agt_agent_id=" . $agentId;
		}
		$sql	 = "$sql $where";

		$count	 = DBUtil::queryScalar("SELECT COUNT(1) FROM ($sql) abc");

		$dataprovider = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 =>
			['attributes'	 =>
				[
					'bkg_booking_id', 'bkg_agent_markup', 'agt_trans_mode', 'bkg_id'
//                    'bkg_booking_id', 'from_city_name', 'bkg_status',
//                    'to_city_name', 'bkg_total_amount', 'bkg_create_date', 'bkg_pickup_date',
//                    'bkg_return_date'
				],
				'defaultOrder'	 => ' agtrans.agt_trans_date ' . $orderBy . ', agtrans.agt_trans_created'],
			'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function getTags($tag)
	{
		$arr = [1 => 'MFF', 2 => 'VIP'];
		if ($tag != '')
		{
			return $arr[$tag];
		}
		return $arr;
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

	public function transactionList($agtId, $transDate1 = '', $transDate2 = '')
	{
		$cond	 = "";
		$sql	 = "CALL GetAgentTransactions($agtId, '$transDate1', '$transDate2')";
		if ($transDate1 == '' && $transDate2 == '')
		{
			$sql = "CALL GetAgentTransactions($agtId, null, null)";
		}
		return DBUtil::queryAll($sql);
	}

	public function getPaymentType()
	{
		$ptpTypeList = PaymentType::model()->getList();
		return $ptpTypeList[$this->agt_ptp_id];
	}

	public function payment($agentId, $ptpid, $amount, $userId = 0)
	{
		$todayCount	 = $this->getTodaysCount();
		$appendValue = $todayCount + 1;

		$agentTransaction							 = new AgentTransactions();
		$agentTransaction->agt_ptp_id				 = $ptpid;
		$agentTransaction->agt_trans_code			 = date('ymdHis') . str_pad($appendValue, 3, 0, STR_PAD_LEFT);
		$agentTransaction->agt_trans_mode			 = ( $amount < 0) ? 1 : 2;
		$agentTransaction->agt_trans_amount			 = -1 * $amount;
		$agentTransaction->agt_trans_date			 = new CDbExpression("NOW()");
		$agentTransaction->agt_trans_created		 = new CDbExpression("NOW()");
		$agentTransaction->agt_agent_id				 = $agentId;
		$agentTransaction->agt_trans_start_datetime	 = new CDbExpression("NOW()");
		$agentTransaction->agt_trans_status			 = 0;
		$agentTransaction->agt_trans_user_id		 = $userId;
		$agentTransaction->agt_trans_ipaddress		 = \Filter::getUserIP();
		$agentTransaction->agt_trans_device_detail	 = UserLog::model()->getDevice();
		$agentTransaction->agt_trans_remarks		 = "Account Recharged";
		$agentTransaction->save();
		return $agentTransaction;
	}

	public function getByCode($code)
	{
		if ($code)
		{
			$criteria	 = new CDbCriteria();
			$criteria->compare('agt_trans_code', $code);
			$transModel	 = $this->find($criteria);
			return $transModel;
		}
		return false;
	}

	public function udpdateResponseByCode($response, $success = 0)
	{
		$responseArr = [];
		$responseArr = json_decode($response, true);

		if ($this->agt_ptp_id == PaymentType::TYPE_PAYTM)
		{
			$this->agt_trans_response_code		 = $responseArr['RESPCODE'];
			$this->agt_trans_response_message	 = $responseArr['RESPMSG'];
			$this->agt_trans_txn_id				 = $responseArr['TXNID'];
		}
		if ($this->agt_ptp_id == PaymentType::TYPE_EBS)
		{
			$this->agt_trans_response_code		 = $responseArr['ResponseCode'];
			$this->agt_trans_response_message	 = $responseArr['ResponseMessage'];
			if ($responseArr['TransactionID'] != '')
			{
				$this->agt_trans_txn_id = $responseArr['TransactionID'];
			}
			else
			{
				$this->agt_trans_txn_id = $responseArr['TransactionId'];
			}
		}
		if ($this->agt_ptp_id == PaymentType::TYPE_PAYUMONEY)
		{
			$this->agt_trans_response_code	 = $responseArr['error'];
			$errorMsg1						 = ($responseArr['error_Message'] == '') ? '' : ', ' . $responseArr['error_Message'];

			$errorMsg = ($responseArr['result'][0]['status']) ? $responseArr['result'][0]['status'] : $responseArr['message'];

			$this->agt_trans_response_message	 = $responseArr['field9'] . $errorMsg . $errorMsg1 . $responseArr['DESCRIPTION'];
			$this->agt_trans_txn_id				 = $responseArr['payuMoneyId'] . $responseArr['result'][0]['paymentId'];
		}
		if ($this->agt_ptp_id == PaymentType::TYPE_INTERNATIONAL_CARD)
		{
			$this->agt_trans_response_code	 = $responseArr['processorResponseCode'];
			$errorMsg1						 = $responseArr['processorResponseText'];

			$errorMsg							 = $responseArr['_attributes']['message'];
			//$transactionResponse->message
			//$responseMessage = ($error != '') ? $error : $transactionResponse->_attributes['status'].$message;
			$this->agt_trans_response_message	 = trim($responseArr['status'] . ' ' . $errorMsg . ' ' . $errorMsg1 . ' ' . $responseArr['message']);
			$this->agt_trans_txn_id				 = $responseArr['id'];
		}
		if ($this->agt_ptp_id == PaymentType::TYPE_MOBIKWIK)
		{
			$this->agt_trans_response_code		 = $responseArr['statuscode'];
			$response_message					 = $responseArr['statusmessage'];
			$this->agt_trans_response_message	 = trim($responseArr['status'] . ' ' . $response_message);
			$this->agt_trans_txn_id				 = $responseArr['orderid'];
		}
		if ($this->agt_ptp_id == PaymentType::TYPE_ZAAKPAY)
		{
			$this->agt_trans_response_code		 = $responseArr['responseCode'] . $responseArr['responsecode'];
			$response_message					 = $responseArr['responseDescription'];
			$this->agt_trans_response_message	 = trim($responseArr['description'] . ' ' . $response_message);
			$this->agt_trans_txn_id				 = $responseArr['orderId'] . $responseArr['orderid'];
		}
		if ($this->agt_ptp_id == PaymentType::TYPE_FREECHARGE)
		{
			if ($responseArr['errorCode'])
			{
				$this->agt_trans_response_code = $responseArr['errorCode'];
			}
			$response_message = '';
			if ($responseArr['errorMessage'])
			{
				$response_message = $responseArr['errorMessage'];
			}
			$this->agt_trans_response_message	 = trim($responseArr['status'] . ' ' . $response_message);
			$this->agt_trans_txn_id				 = $responseArr['merchantTxnId'];
		}
		if ($this->agt_ptp_id == PaymentType::TYPE_AGENT_CORP_CREDIT)
		{

			$this->agt_trans_response_message = trim($responseArr['remarks']);
		}
		//echo $response;exit;
		$this->agt_trans_response_details	 = $response;
		$this->agt_trans_status				 = $success;
		$this->agt_trans_complete_datetime	 = new CDbExpression('NOW()');
		if ($this->save())
		{
			return true;
		}
		else
		{
			//  $emailCom = new emailWrapper();
			// $emailCom->paymentFailedAlert($this->trans_booking_id, $this->agt_trans_response_message);
			return FALSE;
		}
	}

	public function addProcessingCharge($agentId, $transAmount, $transId)
	{
		$processingCharge				 = ((abs($transAmount) * 0.02) >= 1) ? (abs($transAmount) * 0.02) : 1;
		$vTransModel					 = new AgentTransactions();
		$vTransModel->agt_ptp_id		 = PaymentType::TYPE_AGENT_CORP_CREDIT;
		$vTransModel->agt_trans_code	 = $vTransModel->generateTranscode();
		$vTransModel->agt_trans_mode	 = 2;
		$vTransModel->agt_trans_amount	 = round($processingCharge);
		$vTransModel->agt_trans_date	 = new CDbExpression("NOW()");
		$vTransModel->agt_trans_created	 = new CDbExpression("NOW()");
		$vTransModel->agt_agent_id		 = $agentId;
		$vTransModel->agt_trans_status	 = 1;
		$vTransModel->agt_trans_ref_id	 = $transId;
		$vTransModel->agt_trans_remarks	 = "Processing fee charged for online payment";
		$vTransModel->save();
	}

	public function checkCreditLimit($agentId, $routes, $bookingType, $corpCredit, $requestData, $bookingStep, $isTrack = true)
	{
		$isRechargeAccount	 = false;
		$tot				 = AccountTransDetails::accountTotalSummary($agentId);
		$agtModel			 = Agents::model()->findByPk($agentId);
		$totAgentCredit		 = $tot['totAmount'];
		$totAgentCredit		 = $totAgentCredit + $corpCredit;
		if ($totAgentCredit > $agtModel->agt_effective_credit_limit)
		{
			$isRechargeAccount = true;
			if ($isTrack)
			{
				$bookingArr						 = [];
				$bookingArr['bkg_agent_id']		 = $agentId;
				$bookingArr['bkg_booking_type']	 = $bookingType;
				foreach ($routes as $k => $v)
				{
					if ($k == 0)
					{
						$bookingArr['bkg_from_city_id']	 = $v->pickup_city;
						$bookingArr['bkg_pickup_date']	 = $v->date;
					}
					$bookingArr['bkg_to_city_id'] = $v->drop_city;
					if ($bookingType == 2 && $k == 0)
					{
						$bookingArr['bkg_to_city'] = $v->drop_city;
					}
				}
				$result = json_encode(['success' => false, 'errors' => ['bkg_id' => [0 => "Booking failed as your credit limit exceeded, please recharge."]]]);
				//save log in agent api tracking
				//Agent transaction type =12 (credit limit exceeded)
				$time		 = Filter::getExecutionTime();
				//$aatModel    = AgentApiTracking::model()->add($bookingStep, $requestData,  $bookingArr['bkg_pickup_date'], $bookingArr['bkg_booking_type'], \Filter::getUserIP(), $bookingArr['bkg_from_city_id'], $bookingArr['bkg_to_city_id']);
				$aatModel    = AgentApiTracking::model()->add(12, $requestData,  $bookingArr,\Filter::getUserIP());
				$aatModel->updateResponse($result, null, '', $error_type, $error_msg, $time);
			}
			return $isRechargeAccount;
		}
		return false;
	}

}
