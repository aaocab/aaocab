<?php

/**
 * This is the model class for table "account_trans_details".
 *
 * The followings are the available columns in table 'account_trans_details':
 * @property integer $adt_id
 * @property integer $adt_trans_id
 * @property integer $adt_trans_ref_id
 * @property integer $adt_old_trans_id
 * @property string $adt_ledger_id
 * @property integer $adt_type
 * @property string $adt_remarks
 * @property string $adt_addt_params
 * @property double $adt_amount
 * @property integer $adt_active
 * @property integer $adt_status
 * @property string $adt_ref_id
 * @property AccountTransactions $accTransaction
 */
class AccountTransDetails extends CActiveRecord
{

	public $bkg_booking_id, $bkg_id;
	public $trans_code;
	public $refundOrderCode;
	public $trans_mode;
	public $trans_status;
	public $trans_ptp_id;
	public $trans_response_message;
	public $trans_response_details;
	public $trans_amount;
	public $trans_start_datetime;
	public $trans_complete_datetime;
	public $refcode;
	public $trans_booking;
	public $trans_date1;
	public $trans_date2;
	public $trans_user;
	public $trans_ptp;
	public $trans_stat;
	public $trans_inactive_chkbox;
	public $trans_create_date1, $trans_remove_date1;
	public $trans_create_date2, $trans_remove_date2;
	public $act_date;
	public $apg_code;
	public $apg_id;
	public $apg_amount;
	public $debit;
	public $credit;
	public $openingbalance;
	public $closingbalance;
	public $bankTransType = [1 => 'Cash', 2 => 'Cheque', 3 => 'NEFT/RTGS'];
	public $ven_is_invoice, $ven_from_date, $ven_to_date, $vendor_id, $refrence_id, $ucrMaxuseType, $ucrCreditType, $penalty_status;

	const MODE_DEBIT			 = 1;
	const MODE_CREDIT			 = 2;
	const TXN_STATUS_OPEN		 = 0;
	const TXN_STATUS_SUCCESS	 = 1;
	const TXN_STATUS_FAILED	 = 2;
	const TXN_STATUS_PENDING	 = 0;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'account_trans_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('adt_amount', 'required', 'on' => 'insert'),
			['adt_amount,adt_trans_ref_id,adt_ledger_id,adt_remarks', 'required', 'on' => 'refund'],
			['adt_amount', 'validateRefund', 'on' => 'refund'],
			['adt_amount', 'validateCompensation', 'on' => 'compensation'],
			['adt_amount,adt_remarks', 'required', 'on' => 'penalty'],
			['adt_amount,adt_trans_ref_id,adt_ledger_id,adt_remarks,refrence_id', 'required', 'on' => 'userrefund'],
			array('adt_trans_id, adt_trans_ref_id, adt_type, adt_active, adt_status', 'numerical', 'integerOnly' => true),
			array('adt_amount', 'numerical'),
			array('adt_ledger_id', 'length', 'max' => 20),
			array('adt_ref_id', 'length', 'max' => 50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ucrMaxuseType,ucrCreditType', 'safe'),
			array('adt_id, adt_trans_id, adt_trans_ref_id, adt_old_trans_id, adt_ledger_id, adt_type, adt_remarks, adt_addt_params, adt_amount, adt_active, adt_status, adt_ref_id,trans_create_date1,trans_create_date2', 'safe', 'on' => 'search'),
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
			'accTransaction' => array(self::BELONGS_TO, 'AccountTransactions', 'adt_trans_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'adt_id'			 => 'ID',
			'adt_trans_id'		 => 'Account Trans ID',
			'adt_trans_ref_id'	 => 'REF ID',
			'adt_ledger_id'		 => 'Ledger ID',
			'adt_type'			 => 'Type',
			'adt_remarks'		 => 'Remarks',
			'adt_addt_params'	 => 'Extra Remarks',
			'adt_amount'		 => 'Amount',
			'adt_active'		 => 'Active',
			'adt_status'		 => 'Status',
			'adt_ref_id'		 => 'Refund Ref ID',
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

		$criteria->compare('adt_id', $this->adt_id);
		$criteria->compare('adt_trans_id', $this->adt_trans_id);
		$criteria->compare('adt_trans_ref_id', $this->adt_trans_ref_id);
		$criteria->compare('adt_ledger_id', $this->adt_ledger_id, true);
		$criteria->compare('adt_type', $this->adt_type);
		$criteria->compare('adt_amount', $this->adt_amount);
		$criteria->compare('adt_active', $this->adt_active);
		$criteria->compare('adt_status', $this->adt_status);
		$criteria->compare('adt_ref_id', $this->adt_ref_id, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccountTransDetails the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param int $ledgerId
	 * @param int $refType
	 * @param int $refId
	 * @param string|object|array $additionalParams
	 * @param string $remarks
	 * @return AccountTransDetails
	 */
	public static function getInstance($ledgerId, $refType = null, $refId = null, $additionalParams = '', $remarks = '', $accStatus = '')
	{
		if (is_array($additionalParams) || is_object($additionalParams))
		{
			$additionalParams = json_encode($additionalParams);
		}

		$model					 = new AccountTransDetails();
		$model->adt_ledger_id	 = $ledgerId;
		$model->adt_type		 = $refType;
		$model->adt_trans_ref_id = $refId;
		$model->adt_addt_params	 = $additionalParams;
		$model->adt_remarks		 = $remarks;
		$model->adt_active		 = 1;
		$model->adt_status		 = ($accStatus == '') ? 1 : 0;
		return $model;
	}

	public function validateRefund($param)
	{
		$getTotalAdvance		 = PaymentGateway::getTotalAdvance($this->adt_trans_ref_id);
		$totalVendorCollected	 = PaymentGateway::getTotalVendorCollected($this->adt_trans_ref_id);
		$amtRefundable			 = ($getTotalAdvance + $totalVendorCollected);
		$amtToRefund			 = ($amtRefundable < 0) ? 0 : $amtRefundable;
		if (abs($this->adt_amount) > $amtToRefund)
		{
			$this->addError('adt_amount', "refund amount must be less than equal to " . $amtToRefund);
			return false;
		}
		return true;
	}

	public function validateCompensation($attribute, $params)
	{
		$totAmount	 = '';
		$data		 = $this->getAttributes();
		$refId		 = $data['adt_trans_ref_id'];
		$maxUseType	 = $this->ucrMaxuseType;
		$creditType	 = $this->ucrCreditType;
		if ($refId != '')
		{
			$bkgModel = Booking::model()->findByPk($refId);
		}
		$totAmount = $bkgModel->bkgInvoice->bkg_total_amount;
		if ($data['adt_ledger_id'] == '')
		{
			$this->addError('adt_ledger_id', "Please choose payment type");
			return false;
		}
		if ($data['adt_ledger_id'] == Accounting::LI_GOZOCOINS)
		{
			if ($maxUseType == '')
			{
				$this->addError('ucrMaxuseType', "Please choose credit max use");
				return false;
			}
			if ($creditType == '')
			{
				$this->addError('ucrCreditType', "Please choose credit type");
				return false;
			}
		}
		if ($data['adt_amount'] <= 0 || abs($data['adt_amount']) > $totAmount)
		{
			$this->addError('adt_amount', "compensation amount must be greater than 0 or less than equal to " . $totAmount);
			return false;
		}
		return true;
	}

	public function initializeParams($refType, $refId, $ledgerId, $amount, $extra_params = NULL, $refundRefId = 0, $oldTransId = null, $successRemarks = null, $penaltyData = null)
	{
		$accountTransDEtails					 = new AccountTransDetails();
		$accountTransDEtails->adt_type			 = $refType;
		$accountTransDEtails->adt_trans_ref_id	 = $refId;
		$accountTransDEtails->adt_ledger_id		 = $ledgerId;
		$accountTransDEtails->adt_remarks		 = $extra_params;
		$accountTransDEtails->adt_amount		 = $amount;
		$accountTransDEtails->adt_status		 = 1;
		$accountTransDEtails->adt_addt_params	 = ($penaltyData != null || $penaltyData != '') ? $penaltyData : $extra_params;
		$accountTransDEtails->adt_ref_id		 = $refundRefId;
		$accountTransDEtails->adt_old_trans_id	 = $oldTransId;
		return $accountTransDEtails;
	}

	public function generateNewTransCode()
	{
		$appendValue = $this->adt_id;
		$transCode	 = date('ymd') . str_pad($appendValue, 9, 0, STR_PAD_LEFT);
		return $transCode;
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
				$result	 = json_encode(['success' => false, 'errors' => ['bkg_id' => [0 => "Booking failed as your credit limit exceeded, please recharge."]]]);
				//save log in agent api tracking
				//Agent transaction type =12 (credit limit exceeded)
				$time	 = Filter::getExecutionTime();
				//$aatModel	 = AgentApiTracking::model()->add($bookingStep, $requestData, $bookingArr['bkg_pickup_date'], $bookingArr['bkg_booking_type'], \Filter::getUserIP(), $bookingArr['bkg_from_city_id'], $bookingArr['bkg_to_city_id']);
				if ($agentId == 450)
				{
					$aatModel = AgentApiTracking::model()->add(12, $requestData, $bookingArr, \Filter::getUserIP());
					$aatModel->updateResponse($result, null, '', $error_type, $error_msg, $time);
				}
			}
			return $isRechargeAccount;
		}
		return false;
	}

	public function calAmountByVendorId($vendorId, $fromDate = '', $toDate = '', $openingDate = null)
	{
		//$vndIds = Vendors::getVndIdsByRefCode($vendorId);
		
		
		$vndIds = Vendors::getRelatedIds($vendorId);

		$primeVndData	 = Vendors::getPrimaryByIds($vndIds);
		$vndId			 = $primeVndData['vnd_id'];
		if ($vndIds == null || $vndIds == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}

		$sql = "SELECT sum(adt_amount) vendor_amount 
                FROM account_trans_details adt 
				INNER JOIN vendors ON adt.adt_trans_ref_id = vnd_id 
                INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id 
                WHERE adt.adt_active = 1 AND act.act_active=1 AND adt.adt_status = 1 
                AND adt.adt_ledger_id = 14 AND adt.adt_type = 2 
					AND act.act_date >= '2021-04-01 00:00:00' 
                AND vnd_id IN ({$vndIds})";

		if ($fromDate != '')
		{
			$fromDate		 = DateTimeFormat::DatePickerToDate($fromDate);
			$fromTime		 = '00:00:00';
			$fromDateTime	 = $fromDate . ' ' . $fromTime;
			$sql			 .= " AND act_date>='$fromDateTime'";
		}
		if ($toDate != '')
		{
			
			$toDate		 = DateTimeFormat::DatePickerToDate($toDate);
			
			$toTime		 = '23:59:59';
			$toDateTime	 = $toDate . ' ' . $toTime;
			$sql		 .= " AND act_date<='$toDateTime'";
		}
		if ($openingDate != '')
		{
			$openingDate	 = DateTimeFormat::DatePickerToDate($openingDate);
			$fromTime		 = '00:00:00';
			$fromDateTime	 = $openingDate . ' ' . $fromTime;
			$sql			 .= " AND act_date < '$fromDateTime'";
		}

		//	$sql		 .= " GROUP BY vnd_ref_code ";
		
		$recordSet = DBUtil::queryRow($sql, DBUtil::SDB());

		$objVnd = Vendors::model()->findByPk($vndId);

		$calculateAmt						 = array();
		$calculateAmt['vendor_amount']		 = $recordSet['vendor_amount'];
		$calculateAmt['vnd_security_amount'] = AccountTransactions::getSecurityAmount($vndIds);
		$calculateAmt['locked_amount']		 = $objVnd->vendorStats->vrs_locked_amount;
		$calculateAmt['vnp_is_freeze']		 = $objVnd->vendorPrefs->vnp_is_freeze;
		$calculateAmt['vnd_active']			 = $objVnd->vnd_active;
		$calculateAmt['vnd_name']			 = $objVnd->vnd_name;
		$calculateAmt['vnd_code']			 = $objVnd->vnd_code;

		$vendorBalance = -1 * $recordSet['vendor_amount'];
		if ($vendorBalance < 0)
		{
			$calculateAmt['withdrawable_balance']	 = 0;
			$calculateAmt['vendor_amount_type']		 = 'Payable';
		}
		else
		{
			$Withdrawable_Balance					 = ($objVnd->vendorPrefs->vnp_is_freeze != 0 || $objVnd->vnd_active != 1) ? 0 : max([$vendorBalance - $objVnd->vendorStats->vrs_locked_amount, 0]);
			$calculateAmt['withdrawable_balance']	 = $Withdrawable_Balance;
			$calculateAmt['vendor_amount_type']		 = 'Receivable';
		}
		return $calculateAmt;
	}

	public function calAmountByVendorId_OLD($vendorId, $fromDate = '', $toDate = '', $openingDate = null)
	{
		$sql = "SELECT sum(adt_amount) vendor_amount,vrs_security_amount as vnd_security_amount,
			    vendor_stats.vrs_locked_amount as locked_amount,vnp_is_freeze,vnd_active,vnd_name,vnd_code,vnd_id
                FROM   account_trans_details adt 
				INNER JOIN vendors ON adt.adt_trans_ref_id = vnd_id
				INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id
                INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
                INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id
                WHERE  adt.adt_active = 1 AND act.act_active=1
                AND adt.adt_status = 1
                AND adt.adt_ledger_id = 14
                AND adt.adt_type = 2 AND act.act_date >= '2021-04-01 00:00:00' 
                AND vendors.vnd_id=:vendorId
                ";

		if ($fromDate != '')
		{
			$fromDate		 = DateTimeFormat::DatePickerToDate($fromDate);
			$fromTime		 = '00:00:00';
			$fromDateTime	 = $fromDate . ' ' . $fromTime;
			$sql			 .= " AND act_date>='$fromDateTime'";
		}
		if ($toDate != '')
		{
			$toDate		 = DateTimeFormat::DatePickerToDate($toDate);
			$toTime		 = '23:59:59';
			$toDateTime	 = $toDate . ' ' . $toTime;
			$sql		 .= " AND act_date<='$toDateTime'";
		}
		if ($openingDate != '')
		{
			$openingDate	 = DateTimeFormat::DatePickerToDate($openingDate);
			$fromTime		 = '00:00:00';
			$fromDateTime	 = $openingDate . ' ' . $fromTime;
			$sql			 .= " AND act_date < '$fromDateTime'";
		}

		$sql .= " GROUP BY vnd_ref_code";

		$params								 = ['vendorId' => $vendorId];
		$recordSet							 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		$calculateAmt						 = array();
		$vendorBalance						 = -1 * $recordSet['vendor_amount'];
		$calculateAmt['vnd_security_amount'] = $recordSet['vnd_security_amount'];
		$calculateAmt['vendor_amount']		 = $recordSet['vendor_amount'];
		$calculateAmt['locked_amount']		 = $recordSet['locked_amount'];
		$calculateAmt['vnp_is_freeze']		 = $recordSet['vnp_is_freeze'];
		$calculateAmt['vnd_active']			 = $recordSet['vnd_active'];
		$calculateAmt['vnd_name']			 = $recordSet['vnd_name'];
		$calculateAmt['vnd_code']			 = $recordSet['vnd_code'];

		if ($vendorBalance < 0)
		{
			$calculateAmt['withdrawable_balance']	 = 0;
			$calculateAmt['vendor_amount_type']		 = 'Payable';
		}
		else
		{
			$Withdrawable_Balance					 = ($recordSet['vnp_is_freeze'] != 0 || $recordSet['vnd_active'] != 1) ? 0 : max([$vendorBalance - $recordSet['locked_amount'], 0]);
			$calculateAmt['withdrawable_balance']	 = $Withdrawable_Balance;
			$calculateAmt['vendor_amount_type']		 = 'Receivable';
		}
		return $calculateAmt;
	}

	/**
	 * This function is used to calculate total vendor and vendirReff both
	 * @param type $vendorId
	 * @param type $fromDate
	 * @param type $toDate
	 * @param type $openingDate
	 * @return type
	 */
	public static function calAmntByVendorReffBoth($vendorId)
	{
		$vendorModel = Vendors::model()->findByPk($vendorId);
		$vndReffId	 = $vendorModel->vnd_ref_code;
		$value		 = [$vendorId, $vndReffId];
		DBUtil::getINStatement($value, $bindString, $params);
		$sql		 = "SELECT sum(vrs_security_amount) as vnd_security_amount
				FROM vendor_stats 
				WHERE vendor_stats.vrs_vnd_id IN ({$bindString})";
		$recordSet	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		$securityAmt = $recordSet['vnd_security_amount'];
		return $securityAmt;
	}

	public function calTdsByVendorId($vendorId)
	{
		$vndIds		 = Vendors::getRelatedIds($vendorId);
		$PreYear	 = $NextYear	 = "";
		if (date('m') >= 4)
		{
			$PreYear	 = date('Y') . "-04-01 00:00:00";
			$NextYear	 = (date('Y') + 1) . "-03-31 23:59:59";
		}
		else
		{
			$PreYear	 = (date('Y') - 1) . "-04-01 00:00:00";
			$NextYear	 = (date('Y')) . "-03-31 23:59:59";
		}
		$sql = "SELECT
			atd1.adt_trans_ref_id as vbl_vnd_id,
			sum(atd1.adt_amount) AS vendor_amount,
            sum(if(atd.adt_ledger_id = 22, round(-1 * atd1.adt_amount)* 0.01, 0)) AS totalTDS,
			sum(if(atd.adt_ledger_id IN(37,55), (atd1.adt_amount), 0)) AS alreadypaid
			FROM account_trans_details atd 
			INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id 
				AND atd.adt_active = 1 AND act.act_active = 1 
				AND atd.adt_ledger_id IN(22,37,55)
			INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id 
				AND atd1.adt_active = 1 
				AND atd1.adt_trans_ref_id IN ({$vndIds})   AND   atd1.adt_ledger_id = 14
			WHERE  act.act_date between '$PreYear' and '$NextYear' ";

		$recordSet				 = DBUtil::queryRow($sql);
		$tdsAmt					 = array();
		$tdsAmt['vnd_tot_tds']	 = $recordSet['totalTDS'];
		$tdsAmt['vnd_paid_tds']	 = $recordSet['alreadypaid'];
		return $tdsAmt;
	}

	public static function calBonusAmountByDriverId($driverId, $fromDate = '', $toDate = '', $openingDate = null)
	{
		$sql = "SELECT sum(adt_amount) bonus_amount,drivers.drv_name,drivers.drv_id 
                FROM   account_trans_details 
		        JOIN drivers ON account_trans_details.adt_trans_ref_id = drivers.drv_id
                INNER JOIN account_transactions ON account_transactions.act_id = account_trans_details.adt_trans_id
                WHERE  account_trans_details.adt_active = 1 AND account_transactions.act_active=1
                AND account_trans_details.adt_status = 1
                AND account_trans_details.adt_ledger_id = 40
                AND account_trans_details.adt_type = 6
                AND account_trans_details.adt_trans_ref_id  =$driverId";
		if ($fromDate != '')
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate($fromDate);
			$sql		 .= " AND DATE(act_date)>='$fromDate'";
		}
		if ($toDate != '')
		{
			$toDate	 = DateTimeFormat::DatePickerToDate($toDate);
			$sql	 .= " AND DATE(act_date)<='$toDate'";
		}
		if ($openingDate != '')
		{
			$openingDate = DateTimeFormat::DatePickerToDate($openingDate);
			$sql		 .= " AND DATE(act_date) < '$openingDate'";
		}

		$sql .= " GROUP BY drivers.drv_id";
		return DBUtil::queryRow($sql);
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

	public function getLedgerData($vendorId, $fromDate, $toDate)
	{
		
		$record			 = Vendors::model()->getDrillDownInfo($vendorId);
		$vendorAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId, '', $toDate);
		$openingAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId, '', '', $fromDate);
		$transactionList = AccountTransDetails::vendorTransactionList($vendorId, $fromDate, $toDate, '0', 'ASC');
		
		//        /* @var $html2pdf \Spipu\Html2Pdf\Html2Pdf */
		$html2pdf	 = Yii::app()->ePdf->mPdf();
		$data		 = ['record'		 => $record,
			'vendorAmount'	 => $vendorAmount,
			'openingAmount'	 => $openingAmount,
			'vendorList'	 => $transactionList,
			'pdf'			 => $html2pdf,
			'fromDate'		 => $fromDate,
			'toDate'		 => $toDate];
		return $data;
	}
	public function getLedgerPdf($vendorId, $fromDate, $toDate)
	{
		//$fromNewDate = date("d/m/Y", strtotime($fromDate));
		//$toNewDate = date("d/m/Y", strtotime($toDate));
		//$record			 = Vendors::model()->getDrillDownInfo($vendorId);
		//$vendorAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId, '', $toNewDate);
		//$openingAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId, '', '', $fromNewDate);
		$transactionList = AccountTransDetails::DcoTransactionList($vendorId, $fromDate, $toDate, '0', 'ASC');
		
		//        /* @var $html2pdf \Spipu\Html2Pdf\Html2Pdf */
		$html2pdf	 = Yii::app()->ePdf->mPdf();
		$data		 = ['record'		 => $record,
			'vendorAmount'	 => $vendorAmount,
			'openingAmount'	 => $openingAmount,
			'vendorList'	 => $transactionList,
			'pdf'			 => $html2pdf,
			'fromDate'		 => $fromDate,
			'toDate'		 => $toDate];
		return $data;
	}
	public function getLedgerxls($vendorId, $fromDate, $toDate)
	{
		
		$transactionList = AccountTransDetails::DcoTransactionList($vendorId, $fromDate, $toDate, '0', 'ASC');
		
		//        /* @var $html2pdf \Spipu\Html2Pdf\Html2Pdf */
		$data		 = ['vendorList'	 => $transactionList];
		return $data;
	}
	//obsolute
	public static function getTotalPartnerCredit($bkgId)
	{
		return DBUtil::queryScalar("SELECT  SUM(IFNULL(adt_amount,0))  FROM `account_trans_details` INNER JOIN account_transactions ON act_id=adt_trans_id WHERE act_active=1 AND adt_active=1 AND adt_status=1 AND adt_ledger_id IN(26,49) AND act_type=" . Accounting::AT_BOOKING . " AND act_ref_id = $bkgId");
	}

	public static function getAdjustableAmount($paramArr = [])
	{
		$agentId = $paramArr['agentId'];
		$where	 = '';
		if ($paramArr['fromDate'] != '' && $paramArr['toDate'] != '')
		{
			$where = " AND date(act.act_date) BETWEEN '" . $paramArr['fromDate'] . "' AND '" . $paramArr['toDate'] . "'";
		}
		$sql = "SELECT IFNULL(SUM(IF((adt_amount<0 AND adt_ledger_id=15),adt_amount,0)),0) as adjust_amount,
                IFNULL(SUM(adt_amount),0) as transaction_amount
                FROM account_trans_details adt
                JOIN account_transactions act ON act.act_id = adt.adt_trans_id
                WHERE act.act_active=1 AND adt.adt_ledger_id IN(26,15) 
					AND adt.adt_type=3 AND adt.adt_trans_ref_id = $agentId AND adt.adt_status=1 
					AND adt.adt_active=1 $where";
		return DBUtil::queryRow($sql);
	}

	public static function getAdjustableAmountVendor($vendorId, $fromDate, $toDate)
	{
		$vndIds	 = Vendors::getRelatedIds($vendorId);
		$where	 = "";
		if ($fromDate != '' && $toDate != '')
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate($fromDate);
			$toDate		 = DateTimeFormat::DatePickerToDate($toDate);
			$where		 .= " AND date(act.act_date) BETWEEN '$fromDate' AND '$toDate'";
		}
		$sql = "SELECT  SUM(IF((adt_amount<0),adt_amount,'0')) as adjust_amount,
                        SUM(adt_amount) as transaction_amount
                        FROM account_trans_details adt
                        JOIN account_transactions act ON act.act_id = adt.adt_trans_id
                        WHERE act.act_active=1 AND adt.adt_ledger_id IN(14) 
							AND adt.adt_trans_ref_id IN ({$vndIds}) AND adt.adt_type=2 AND adt.adt_status=1 
							AND adt.adt_active=1 $where ";

		return DBUtil::queryRow($sql);
	}

	public function revertVenTransOnEditAcc($tripId, $bkgId, $bankLedgerId, $payeeLedgerId)
	{
		$sql	 = "SELECT DISTINCT
                        adt.adt_trans_id
                    FROM
                        account_transactions act
                    INNER JOIN account_trans_details adt ON act.act_id = adt.adt_trans_id AND adt.adt_status = 1 AND adt.adt_active = 1 AND adt.adt_ledger_id = 14 
                    INNER JOIN account_trans_details adt1 ON act.act_id = adt1.adt_trans_id AND adt1.adt_status = 1 AND adt1.adt_active = 1 AND adt1.adt_ledger_id NOT IN(28)
                    WHERE 
                           act.act_active = 1 
                        AND (
                            (adt1.adt_trans_ref_id = $tripId AND adt1.adt_type = 5 ) 
                            OR 
                            (adt1.adt_trans_ref_id = $bkgId AND adt1.adt_type = 1 )
                            )";
		$data	 = DBUtil::queryAll($sql);

		foreach ($data as $adtModel)
		{
			$modelAccTrans			 = AccountTransactions::model()->findByPk($adtModel['adt_trans_id']);
			$modelAccTransDetails	 = AccountTransDetails::model()->findAll('adt_trans_id=:act_id AND adt_active=1 AND adt_status=1', ['act_id' => $modelAccTrans->act_id]);
			foreach ($modelAccTransDetails as $accTransDet)
			{
				$accTransDet->adt_status = 0;
				$accTransDet->adt_active = 0;
				$accTransDet->save();
				//VendorStats::model()->updateOutstanding($accTransDet->adt_trans_ref_id);
			}
			$modelAccTrans->act_status	 = 0;
			$modelAccTrans->act_active	 = 0;
			$modelAccTrans->save();
		}
		return true;
	}

	public function revertVendorCollected($tripId, $bkgId, $bankLedgerId, $payeeLedgerId)
	{
		$sql	 = "SELECT DISTINCT adt_trans_id FROM `account_trans_details`
			INNER JOIN account_transactions ON act_id=adt_trans_id
			WHERE adt_status=1 AND adt_active=1 AND account_transactions.act_active=1 AND
			((account_transactions.act_ref_id=$bkgId AND account_transactions.act_type=1)) AND adt_ledger_id=14";
		$data	 = DBUtil::queryAll($sql);

		foreach ($data as $adtModel)
		{
			$modelAccTrans			 = AccountTransactions::model()->findByPk($adtModel['adt_trans_id']);
			$modelAccTransDetails	 = AccountTransDetails::model()->findAll('adt_trans_id=:act_id AND adt_active=1 AND adt_status=1', ['act_id' => $modelAccTrans->act_id]);
			foreach ($modelAccTransDetails as $accTransDet)
			{
				$accTransDet->adt_status = 0;
				$accTransDet->adt_active = 0;
				$accTransDet->save();
				//VendorStats::model()->updateOutstanding($accTransDet->adt_trans_ref_id);
			}
			$modelAccTrans->act_status	 = 0;
			$modelAccTrans->act_active	 = 0;
			$modelAccTrans->save();
		}
		return true;
	}

	public function getPayment($type)
	{
		$ptpTypeList = PaymentType::model()->getList();
		return $ptpTypeList[$type];
	}

	public function showTransactions()
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = ["t.*", "t.adt_trans_ref_id bkg_booking_id", "t.adt_ledger_id", "t.adt_status=1 trans_status", "t.adt_remarks trans_response_message", "t.adt_amount trans_amount", "act.act_date", "act.act_date trans_complete_datetime"];
		$criteria->join		 = "  JOIN account_transactions act ON t.adt_trans_id=act.act_id AND act.act_status=1 AND act.act_active=1 ";
		$criteria->join		 .= "  JOIN account_ledger ON account_ledger.ledgerId=t.adt_ledger_id";
		$criteria->condition .= " t.adt_status=1 AND t.adt_active=1";
		if ($this->adt_ledger_id != '')
		{
			$criteria->compare('t.adt_ledger_id', $this->adt_ledger_id);
		}

		$criteria->limit	 = '50';
		$criteria->together	 = TRUE;
		$dataProvider		 = new CActiveDataProvider($this->together(), ['criteria'	 => $criteria, 'pagination' => ['pageSize' => 50], 'sort'		 => array(
				'defaultOrder' => 'act.act_date DESC'
			),]);

		return $dataProvider;
	}

	public static function getAccountTransactionsList($date1 = '', $date2 = '', $ledgerId = null, $refId = null)
	{
		$name		 = "(ledgerName) as name, ";
		$where		 = "";
		$subWhere	 = "";
		if ($ledgerId != null)
		{
			//	$name1 = "r.name";
			$where		 = " AND ledgerId=$ledgerId";
			$subWhere	 = " AND account_trans_details.adt_ledger_id=$ledgerId";
			$groupBy	 = ", adt_trans_ref_id";
			$joinOn		 = " AND a.adt_trans_ref_id=r.adt_trans_ref_id";
			switch ($ledgerId)
			{
				case 15:
					$name	 = "(CONCAT(agt_company)) as name,";
					$join	 = " INNER JOIN agents ON agt_id=IFNULL(a.adt_trans_ref_id,r.adt_trans_ref_id)";
					break;
				case 14:
					$name	 = "(vnd_name) as name,";
					$join	 = " INNER JOIN vendors ON vnd_id=IFNULL(a.adt_trans_ref_id,r.adt_trans_ref_id)";
					break;
				case 40:
					$name	 = "(drv_name) as name, drv_id,";
					$join	 = " INNER JOIN drivers ON drv_id=IFNULL(a.adt_trans_ref_id,r.adt_trans_ref_id)";
					break;
				default:
					break;
			}
		}
		if ($date1 != '' && $date2 != '')
		{
			$currentdate = "'$date1 00:00:00'";
			$condition	 = " AND act_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59' ";
		}
		else
		{
			$currentdate = "CONCAT(DATE(DATE_SUB(NOW(), INTERVAL 30 DAY)),' 00:00:00')";
			$condition	 = " AND (act_date BETWEEN $currentdate AND NOW()) ";
		}
		$sql = "
			SELECT ledgerId, $name IFNULL(opening,0) as opening, credit, debit, (IFNULL(opening,0) + currentTotal) as closing
			FROM account_ledger
			LEFT JOIN
			 (SELECT adt_ledger_id, adt_trans_ref_id, SUM(adt_amount) as currentTotal ,
				SUM(IF(adt_amount<0, adt_amount,0)) as credit, SUM(IF(adt_amount>0, adt_amount,0)) as debit
                FROM account_trans_details
                INNER JOIN account_transactions ON act_id=account_trans_details.adt_trans_id AND act_active=1 $subWhere
				INNER JOIN account_ledger ON ledgerId=adt_ledger_id
                WHERE account_trans_details.adt_active=1 AND account_trans_details.adt_status=1 $condition 
                GROUP BY adt_ledger_id $groupBy
            ) a on ledgerId=a.adt_ledger_id
            LEFT JOIN (SELECT adt_ledger_id, adt_trans_ref_id, SUM(adt_amount) as opening
				FROM account_trans_details
				INNER JOIN account_transactions ON act_id=account_trans_details.adt_trans_id AND act_active=1 $subWhere
				WHERE account_trans_details.adt_active=1 AND account_trans_details.adt_status=1 AND act_date< $currentdate  
				GROUP BY adt_ledger_id $groupBy
			) r ON a.adt_ledger_id=r.adt_ledger_id $joinOn
			$join
				WHERE (opening<>0 OR credit<>0 OR debit<>0)$where";

		$count			 = DBUtil::queryScalar("SELECT COUNT(1) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'pagination'	 => false,
			'sort'			 => ['attributes'	 => ['name', 'opening', 'closing', 'credit', 'debit'],
				'defaultOrder'	 => 'name ASC'],
		]);
		return $dataprovider;
	}

	public static function getAccountTransactionsList1($ledgerId = null, $date1 = null, $date2 = null)
	{
		$where = "";
		if ($ledgerId > 0)
		{
			$where = " AND ledgerId=$ledgerId";
		}

		if ($date1 != '' && $date2 != '')
		{
			$currentdate = "'$date1 00:00:00'";
			$condition	 = " AND act_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59' ";
		}
		else
		{
			$currentdate = "CONCAT(DATE(DATE_SUB(NOW(), INTERVAL 30 DAY)),' 00:00:00')";
			$condition	 = " AND (act_date BETWEEN $currentdate AND NOW()) ";
		}

		$tempTable	 = "AccountLedgerBalance_" . rand();
		$sqlCreate	 = " (INDEX my_index_name (adt_ledger_id))
						SELECT adt_ledger_id, SUM(adt_amount) AS currentTotal, SUM(IF(adt_amount < 0, adt_amount, 0)) AS credit, SUM(IF(adt_amount > 0, adt_amount, 0)) AS debit
						FROM account_trans_details
						INNER JOIN account_transactions ON act_id = account_trans_details.adt_trans_id AND act_active = 1
						WHERE account_trans_details.adt_active = 1 AND account_trans_details.adt_status = 1 AND (act_date BETWEEN CONCAT(DATE(DATE_SUB(NOW(), INTERVAL 30 DAY)),' 00:00:00') AND NOW())
						GROUP BY adt_ledger_id";
		DBUtil::createTempTable($tempTable, $sqlCreate);

		$tempTable1	 = "AccountLedgerBalance_" . rand();
		$sqlCreate1	 = " (INDEX my_index_name (adt_ledger_id))
						SELECT adt_ledger_id, SUM(adt_amount) AS opening 
						FROM account_transactions 
						INNER JOIN account_trans_details ON act_id = adt_trans_id AND act_active = 1 
						WHERE 1 AND adt_active = 1 AND adt_status = 1 AND act_date < $currentdate 
						GROUP BY adt_ledger_id";
		DBUtil::createTempTable($tempTable1, $sqlCreate1);

		$sql = "SELECT ledgerId, (ledgerName) AS name, IFNULL(opening, 0) AS opening, credit, debit, (IFNULL(opening, 0) + currentTotal) AS closing
			FROM account_ledger
			LEFT JOIN $tempTable a ON ledgerId = a.adt_ledger_id
			LEFT JOIN $tempTable1 r ON a.adt_ledger_id = r.adt_ledger_id
			WHERE (opening <> 0 OR credit <> 0 OR debit <> 0) $where 
			GROUP BY ledgerId";

		//$count			 = DBUtil::queryScalar("SELECT COUNT(1) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider = new CSqlDataProvider($sql, [
			//'totalItemCount' => $count,
			'db'		 => DBUtil::SDB(),
			'pagination' => false,
			'sort'		 => ['attributes'	 => ['name', 'opening', 'closing', 'credit', 'debit'],
				'defaultOrder'	 => 'name ASC'],
		]);
		return $dataprovider;
	}

	public static function getAccountTransactionsList1_OLD($ledgerId = null, $date1 = null, $date2 = null)
	{
		if ($ledgerId != null)
		{
			$where = " AND ledgerId=$ledgerId group BY ledgerId,a.abc";
		}
		else
		{
			$where = " group BY ledgerId";
		}
		if ($date1 != '' && $date2 != '')
		{
			$currentdate = "'$date1 00:00:00'";
			$condition	 = " AND act_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59' ";
		}
		else
		{
			$currentdate = "CONCAT(DATE(DATE_SUB(NOW(), INTERVAL 30 DAY)),' 00:00:00')";
			$condition	 = " AND (act_date BETWEEN $currentdate AND NOW()) ";
		}
		$tempTable	 = "AccountLedgerBalance_1";
		$sqlCreate	 = " (INDEX my_index_name (adt_ledger_id), INDEX my_index_name1 (adt_trans_ref_id), INDEX my_index_name2 (act_date))
					SELECT		
					adt_ledger_id,act_date, DATE_FORMAT(act_date ,'%Y-%m') abc,
					adt_trans_ref_id,
					SUM(adt_amount) AS currentTotal,
					SUM(IF(adt_amount < 0, adt_amount, 0)) AS credit,
					SUM(IF(adt_amount > 0, adt_amount, 0)) AS debit
				FROM
					account_trans_details
				INNER JOIN account_transactions ON act_id = account_trans_details.adt_trans_id AND act_active = 1
				WHERE account_trans_details.adt_active = 1 AND account_trans_details.adt_status = 1 $condition
				GROUP BY adt_ledger_id,abc";

		DBUtil::createTempTable($tempTable, $sqlCreate);

		$tempTable1	 = "AccountLedgerBalance_2";
		$sqlCreate1	 = " (INDEX my_index_name (adt_ledger_id), INDEX my_index_name1 (adt_trans_ref_id) , INDEX my_index_name2 (act_date))
					SELECT
                adt_ledger_id, act_date, DATE_FORMAT(act_date ,'%Y-%m') abc,
                adt_trans_ref_id,
                SUM(adt_amount) AS opening
            FROM
                account_trans_details
            INNER JOIN account_transactions ON act_id = account_trans_details.adt_trans_id AND act_active = 1
            WHERE
                account_trans_details.adt_active = 1 AND account_trans_details.adt_status = 1 AND act_date< $currentdate
                GROUP BY adt_ledger_id,abc";

		DBUtil::createTempTable($tempTable1, $sqlCreate1);

		$sql = "SELECT
					ledgerId,a.abc,
					(ledgerName) AS name,
					IFNULL(opening, 0) AS opening,
					credit,
					debit,
					(IFNULL(opening, 0) + currentTotal) AS closing
				FROM
					account_ledger
				LEFT JOIN $tempTable a ON ledgerId = a.adt_ledger_id
				LEFT JOIN $tempTable1 r ON a.adt_ledger_id = r.adt_ledger_id
					WHERE (opening <> 0 OR credit <> 0 OR debit <> 0) $where";

		$count			 = DBUtil::queryScalar("SELECT COUNT(1) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'pagination'	 => false,
			'sort'			 => ['attributes'	 => ['name', 'opening', 'closing', 'credit', 'debit'],
				'defaultOrder'	 => 'name ASC'],
		]);
		return $dataprovider;
	}

	public static function getdriverAccountTransactionsList($date1 = '', $date2 = '', $ledgerId = null, $refId = null, $type = '')
	{
		$name	 = "(ledgerName) as name, ";
		$where	 = "";
		if ($ledgerId != null)
		{
			//	$name1 = "r.name";
			$where = " AND ledgerId=$ledgerId";
			switch ($ledgerId)
			{
				case 15:
					$name	 = "(CONCAT(agt_company)) as name,";
					$join	 = " INNER JOIN agents ON agt_id=IFNULL(a.adt_trans_ref_id,r.adt_trans_ref_id)";
					$groupBy = ", adt_trans_ref_id";
					$joinOn	 = " AND a.adt_trans_ref_id=r.adt_trans_ref_id";
					break;
				case 14:
					$name	 = "(vnd_name) as name,";
					$join	 = " INNER JOIN vendors ON vnd_id=IFNULL(a.adt_trans_ref_id,r.adt_trans_ref_id)";
					$groupBy = ", adt_trans_ref_id";
					$joinOn	 = " AND a.adt_trans_ref_id=r.adt_trans_ref_id";
					break;
				case 40:
					$name	 = "(drv_name) as name, contact_phone.phn_phone_no as phone, drv_id,";
					$join	 = " INNER JOIN drivers ON drv_id = IFNULL(a.adt_trans_ref_id, r.adt_trans_ref_id) AND drv_id = drv_ref_code  
								JOIN contact_profile AS cp ON cp.cr_is_driver = drivers.drv_id AND cp.cr_status =1
								JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_active =1 AND contact.ctt_id = contact.ctt_ref_code 
								LEFT JOIN contact_phone ON contact_phone.phn_contact_id=contact.ctt_id AND phn_is_primary = 1 AND phn_active =1";
					$groupBy = ", adt_trans_ref_id";
					$joinOn	 = " AND a.adt_trans_ref_id=r.adt_trans_ref_id";
					break;
				default:
					break;
			}
		}
		if ($date1 != '' && $date2 != '')
		{
			$dt1		 = date("Y-m-d H:i:s", strtotime($date1));
			$time		 = '23:59:59';
			$dt2		 = date('Y-m-d ' . $time, strtotime($date2));
			$currentdate = "'$dt1'";
			$condition	 = " AND act_date BETWEEN '" . $dt1 . "' AND '" . $dt2 . "' ";
		}
		else
		{
			$currentdate = "'NOW()'";
			$condition	 = " AND (act_date < NOW()) ";
		}
		$sql = "
			SELECT ledgerId, $name IFNULL(opening,0) as opening, credit, debit, (IFNULL(opening,0) + currentTotal) as closing
			FROM account_ledger
			 JOIN
			 (SELECT adt_ledger_id, adt_trans_ref_id, SUM(adt_amount) as currentTotal ,
				SUM(IF(adt_amount<0, adt_amount,0)) as credit, SUM(IF(adt_amount>0, adt_amount,0)) as debit
                FROM account_trans_details
                INNER JOIN account_transactions ON act_id=account_trans_details.adt_trans_id AND act_active=1
				INNER JOIN account_ledger ON ledgerId=adt_ledger_id
                WHERE account_trans_details.adt_active=1 $condition
                GROUP BY adt_ledger_id $groupBy
            ) a on ledgerId=a.adt_ledger_id
            LEFT JOIN (SELECT adt_ledger_id, adt_trans_ref_id, SUM(adt_amount) as opening
				FROM account_trans_details
				INNER JOIN account_transactions ON act_id=account_trans_details.adt_trans_id AND act_active=1
				WHERE account_trans_details.adt_active=1 AND act_date< $currentdate
				GROUP BY adt_ledger_id $groupBy
			) r ON a.adt_ledger_id=r.adt_ledger_id $joinOn
			$join
				WHERE (opening<>0 OR credit<>0 OR debit<>0)$where";

		if ($type == 'command')
		{
			$recordSet = DBUtil::query($sql, DBUtil::SDB());
			return $recordSet;
		}
		else
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(1) FROM ($sql) abc");
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'pagination'	 => false,
				'sort'			 => ['attributes'	 => ['name', 'opening', 'closing', 'credit', 'debit'],
					'defaultOrder'	 => 'name ASC'],
			]);
			return $dataprovider;
		}
	}

	public function getReferenceList($date1 = '', $date2 = '', $ledgerId = null, $refId = null)
	{
		$name	 = "(ledgerName) as name, ";
		$where	 = "";
		if ($ledgerId != null)
		{
			//	$name1 = "r.name";
			$where = " AND ledgerId=$ledgerId";
			switch ($ledgerId)
			{
				case 15:
					$name	 = "(CONCAT(agt_company)) as name,";
					$join	 = " INNER JOIN agents ON agt_id=IFNULL(a.adt_trans_ref_id,r.adt_trans_ref_id)";
					$groupBy = ", adt_trans_ref_id";
					$joinOn	 = " AND a.adt_trans_ref_id=r.adt_trans_ref_id";
					break;
				case 14:
					$name	 = "(vnd_name) as name,";
					$join	 = " INNER JOIN vendors ON vnd_id=IFNULL(a.adt_trans_ref_id,r.adt_trans_ref_id)";
					$groupBy = ", adt_trans_ref_id";
					$joinOn	 = " AND a.adt_trans_ref_id=r.adt_trans_ref_id";
					break;
				default:
					break;
			}
		}
		if ($date1 != '' && $date2 != '')
		{
			$currentdate = "'$date1'";
			$condition	 = " AND(date(act_date) BETWEEN '" . $date1 . "' AND '" . $date2 . "') ";
		}
		else
		{
			$currentdate = "DATE(DATE_SUB(NOW(), INTERVAL 30 DAY))";
			$condition	 = " AND(date(act_date) BETWEEN DATE(DATE_SUB(NOW(), INTERVAL 30 DAY)) AND NOW()) ";
		}
		$sql = "
			SELECT ledgerId, $name IFNULL(opening,0) as opening, credit, debit, (IFNULL(opening,0) + currentTotal) as closing
			FROM account_ledger
			LEFT JOIN
			 (SELECT adt_ledger_id, adt_trans_ref_id, SUM(adt_amount) as currentTotal ,
				SUM(IF(adt_amount<0, adt_amount,0)) as credit, SUM(IF(adt_amount>0, adt_amount,0)) as debit
                FROM account_trans_details
                INNER JOIN account_transactions ON act_id=account_trans_details.adt_trans_id AND act_active=1
				INNER JOIN account_ledger ON ledgerId=adt_ledger_id
                WHERE account_trans_details.adt_active=1 $condition
                GROUP BY adt_ledger_id $groupBy
            ) a on ledgerId=a.adt_ledger_id
            LEFT JOIN (SELECT adt_ledger_id, adt_trans_ref_id, SUM(adt_amount) as opening
				FROM account_trans_details
				INNER JOIN account_transactions ON act_id=account_trans_details.adt_trans_id AND act_active=1
				WHERE account_trans_details.adt_active=1 AND DATE(act_date)< $currentdate
				GROUP BY adt_ledger_id $groupBy
			) r ON a.adt_ledger_id=r.adt_ledger_id $joinOn
			$join
				WHERE (opening<>0 OR credit<>0 OR debit<>0)$where";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc");
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'pagination'	 => false,
		]);
		return $dataprovider;
	}

	public function getDetailLink($data)
	{
		$ledgerId = Yii::app()->request->getParam("ledgerId");

		if (in_array($data["ledgerId"], [14, 15]) && $ledgerId == null)
		{
			echo CHtml::link($data["name"], Yii::app()->createUrl("admin/account/list", $_REQUEST + ["ledgerId" => $data["ledgerId"]]));
		}
		else
		{
			echo $data["name"];
		}
	}

	public function getRemainingRefundbyId($refid)
	{
		$result			 = DBUtil::command("SELECT SUM(account_trans_details.adt_amount) totRefund FROM account_trans_details WHERE account_trans_details.adt_status=1 AND account_trans_details.adt_active=1 AND account_trans_details.adt_ref_id=$refid GROUP BY account_trans_details.adt_ref_id")->queryRow();
		$refundAmt		 = $result['totRefund'];
		$model			 = AccountTransDetails::model()->findByPk($refid);
		$remainingAmt	 = $model->adt_amount + $refundAmt;
		return $remainingAmt;
	}

	public function getTransactionsToRefund($bkgId)
	{
		return $this->findAllBySql("SELECT adt.*,
				(CASE
					WHEN adt.adt_ledger_id = 26 THEN 1
					WHEN adt.adt_ledger_id = 21 THEN 2
					WHEN adt.adt_ledger_id = 16 THEN 3
					WHEN adt.adt_ledger_id = 20 THEN 4
					WHEN adt.adt_ledger_id = 17 THEN 5
					WHEN adt.adt_ledger_id = 18 THEN 6
					WHEN adt.adt_ledger_id = 19 THEN 7
					WHEN adt.adt_ledger_id = 1  THEN 8
					WHEN adt.adt_ledger_id = 23 THEN 9
					ELSE 99
						END)
						 orderbyptp
					 FROM
					   account_trans_details adt
						JOIN account_transactions act ON act.act_id = adt.adt_trans_id
						JOIN account_ledger al ON al.ledgerId = adt.adt_ledger_id
						LEFT JOIN payment_gateway pg ON pg.apg_id = adt.adt_trans_ref_id AND adt.adt_type = 4 AND adt.adt_ref_id IS NULL
						JOIN account_trans_details adt1 ON adt.adt_id = adt1.adt_ref_id
					 WHERE
					   act.act_active=1 AND act.act_type = 1 AND act.act_ref_id=$bkgId AND al.accountGroupId IN (27,28) AND adt.adt_amount > 0 AND adt.adt_active = 1 AND adt.adt_status = 1 AND adt1.adt_id IS NULL ORDER BY orderbyptp ASC");
	}

	public static function vendorTransactionList($vendorId, $fromDate = '', $toDate = '', $vendorAmt = '0', $orderBy = 'DESC', $ledgers = '', $type = 'command')
	{
		
		if ($vendorId == null || $vendorId == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}

		// Getting Merged VendorIds
//		$vndIds = Vendors::getVndIdsByRefCode($vendorId);
		$vndIds = Vendors::getRelatedIds($vendorId);

		if ($vndIds == null || $vndIds == "")
		{
			throw new Exception("Vendor not found", ReturnSet::ERROR_INVALID_DATA);
		}

		$dateRange = '';
		
		if (($fromDate != '' && $fromDate != '1970-01-01') && ($toDate != '' && $toDate != '1970-01-01'))
		{
		
			if ($vendorAmt == '0')
			{
				$fromDate	 = DateTimeFormat::DatePickerToDate($fromDate);
				$toDate		 = DateTimeFormat::DatePickerToDate($toDate);
			}
			
			$dateRange	 .= " AND (act_date) >='$fromDate 00:00:00' AND (act_date) <= '$toDate 23:59:59' ";
			$actOpenDate = " AND (act_date) < '$fromDate 00:00:00'";
			if ($ledgers != '')
			{
				$ledgersID	 = "AND atd1.adt_ledger_id IN ($ledgers)";
				$and		 = '';
			}
			else
			{
				$and = ' and atd1.adt_ledger_id=22 ';
			}
		}
		$vndAmt = '';
		if ($vendorAmt == '0')
		{
			$vndAmt .= "  AND (adt.adt_amount<>0)";
		}
		
		$sql = "SELECT *, Round(@runningBal1 := @runningBal1 + ven_trans_amount,2) as runningBalance,  
				Round(@runningBal1 * -1,2) as vendorRunningBalance
			FROM (
				SELECT atd2.adt_amount bank_charge, act_date,act_created, act_id, 
				atd.adt_id, atd.adt_ledger_id,booking.bkg_id, act_type,act_ref_id,
					IF(atd1.adt_ledger_id=13,  atd.adt_remarks , atd1.adt_remarks ) ven_trans_remarks, bkg_net_advance_amount as bkg_advance_amount,
					act.act_date as ven_trans_date,booking.bkg_booking_id,
					GROUP_CONCAT(atd1.adt_ledger_id) as ledgerIds,
					GROUP_CONCAT(CASE atd1.adt_ledger_id	
							WHEN 13 THEN CONCAT(account_ledger.ledgerName, ': ', booking.bkg_booking_id, ' (', booking.bkg_bcb_id ,')')
							WHEN 22 THEN CONCAT(account_ledger.ledgerName, ': ', booking_cab.bcb_id)
							ELSE account_ledger.ledgerName
						END SEPARATOR ', ') as ledgerNames,
					account_ledger.ledgerName entityType, atd.adt_amount ven_trans_amount,atd.adt_addt_params,
					IF(atd1.adt_ledger_id=22, MIN(bcbbkg.bkg_pickup_date), booking.bkg_pickup_date) bkg_pickup_date,
					CONCAT(admins.adm_fname, ' ', admins.adm_lname) AS adm_name,
					GROUP_CONCAT(concat(c1.cty_name, ' - ', c2.cty_name) SEPARATOR ' - ') from_city,
					account_ledger.ledgerId As lid
				FROM account_transactions act
				INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id 
					AND atd.adt_ledger_id=14 AND atd.adt_trans_ref_id IN ({$vndIds}) AND  adt_status=1 
					AND adt_active=1 AND act_status=1 
					AND act_active=1 $dateRange 
				INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id 
					AND ((abs(atd.adt_amount)<>atd.adt_amount AND atd1.adt_amount>0) OR (abs(atd.adt_amount)=atd.adt_amount 
						AND atd1.adt_amount<0))
				LEFT JOIN account_trans_details atd2 ON act.act_id = atd2.adt_trans_id 
					AND atd2.adt_ledger_id=31  
				INNER JOIN account_ledger ON atd1.adt_ledger_id=account_ledger.ledgerId $ledgersID
				LEFT JOIN booking ON (atd1.adt_ledger_id=13) AND atd1.adt_trans_ref_id=booking.bkg_id
				LEFT JOIN booking_invoice ON booking_invoice.biv_bkg_id=booking.bkg_id
				LEFT JOIN booking_cab ON  atd1.adt_trans_ref_id=booking_cab.bcb_id $and 
				LEFT JOIN booking bcbbkg ON bcbbkg.bkg_bcb_id = booking_cab.bcb_id AND booking_cab.bcb_active = 1
				LEFT JOIN `admins` ON admins.adm_id = act.act_user_id AND act.act_user_type = 4
				LEFT JOIN `cities` AS c1 ON c1.cty_id = if(atd1.adt_ledger_id=22, bcbbkg.bkg_from_city_id, booking.bkg_from_city_id)
				LEFT JOIN `cities` AS c2 ON c2.cty_id = if(atd1.adt_ledger_id=22, bcbbkg.bkg_to_city_id, booking.bkg_to_city_id)
				WHERE act_active = 1 AND act_date >= '2021-04-01 00:00:00' AND act_status=1 
				GROUP BY atd.adt_id ORDER BY act.act_date ASC) a
				JOIN 
				(SELECT @runningBal1 := ifNULL(SUM(adt_amount),0) openBalance FROM account_trans_details
					INNER JOIN account_transactions ON act_id=adt_trans_id
					WHERE 1 $actOpenDate 
						AND adt_ledger_id=14  AND adt_status=1 AND adt_active=1 AND act_status=1 AND act_active=1 
						AND act_date >= '2021-04-01 00:00:00' 
						AND adt_trans_ref_id IN ({$vndIds})  
					ORDER BY act_date,act_created ASC) r";
				
		$sqlCount = "SELECT  COUNT(DISTINCT atd1.adt_id) AS cnt
					FROM account_transactions act
					INNER JOIN account_trans_details atd ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 14 
							AND atd.adt_trans_ref_id IN ({$vndIds}) AND adt_active = 1 
					INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id 
							AND ((abs(atd.adt_amount) <> atd.adt_amount AND atd1.adt_amount > 0) OR (abs(atd.adt_amount) = atd.adt_amount AND atd1.adt_amount < 0)) AND atd1.adt_active=1
					WHERE act_active = 1 AND act_date >= '2021-04-01 00:00:00' $ledgersID $dateRange";

		if ($type == 'data')
		{
			$count			 = DBUtil::queryScalar("$sqlCount", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'pagination'	 => ['pageSize' => 400],
			]);
			return $dataprovider;
		}
		else
		{
			$recordset	 = DBUtil::query($sql, DBUtil::SDB());
			$resultSet	 = [];
			$i			 = 0;
			foreach ($recordset as $val)
			{
				$resultSet[$i]						 = $val;
				$resultSet[$i]['penaltyRedeemFlag']	 = self::model()->checkFlagStatus($val['act_date'], $val['lid'], $val['ven_trans_amount'], $val['adt_addt_params']); // for checking penalty will be reeedem or not
				$i++;
			}

			return $resultSet;
		}
	}

	public static function checkFlagStatus($act_date, $lid, $amount, $params = null)
	{
		$penalityRedeemValidity	 = Config::get('vendor.ReedemPenalty');
		$validDateArr			 = json_decode($penalityRedeemValidity);
		$startDate				 = $validDateArr[0]->startDate;
		$pntValidDate			 = $startDate; //according to KG */
		#$pntValidDate = '2022-11-01 00:00:00'; //according to AK Sir


		if ($params != null && $params != "")
		{
			$paramArr		 = json_decode($params);
			$waivedOffAmount = $paramArr->totalWaivedOff;
			if ($waivedOffAmount == null || $waivedOffAmount == "")
			{
				$waivedOffAmount = 0;
			}
		}

		$date1 = date_create(Filter::getDBDateTime());

		$date2	 = date_create($act_date);
		$diff	 = date_diff($date1, $date2);
		$days	 = $diff->format("%a");

		#$flag =($days<60 && $act_date>$pntValidDate && $lid==28 && $amount>0 && $waivedOffAmount<1) ?1:0;
		$flag = ($days < 60 && $act_date > $pntValidDate && $lid == 28 && $amount > 0) ? 1 : 0;
		#$flag = 0; //for tempurary perpose
		return $flag;
	}

	/**
	 * 
	 * @param type $vendorId
	 * @param type $fromDate
	 * @param type $toDate 
	 * @return CDbDataReader
	 */
	public static function vendorTransactionList1($vndIds, $fromDate = '', $toDate = '', $tripId = null, $pageRef = null)
	{
		$criteria = '';
		if ($tripId > 0)
		{
			$criteria .= " AND bcb_id=$tripId";
		}
		else if (($fromDate != '' && $fromDate != '1970-01-01') && ($toDate != '' && $toDate != '1970-01-01'))
		{
			$criteria .= " AND DATE(bkg_pickup_date)>='$fromDate 00:00:00' AND DATE(bkg_pickup_date)<='$toDate 23:59:59'";
		}
		$limit = '';
		if ($pageRef != null)
		{
			/** @var \Beans\common\PageRef $pageRef */
			$pageNumber	 = $pageRef->pageCount;
			$pageSize	 = $pageRef->pageSize;
			$offset		 = $pageNumber * $pageSize;
			$limit		 = " LIMIT {$offset},{$pageSize} ";
		}


		// Getting Merged VendorIds
		//$vndIds = Vendors::getVndIdsByRefCode($vendorId);
		if ($vndIds == null || $vndIds == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}

		$criteria .= " AND bcb_vendor_id IN ({$vndIds}) ";

		$sql		 = "SELECT MAX(bkg_pickup_date) as ven_trans_date, bcb_trip_type,
				booking_cab.bcb_id bcb_id,bkg_booking_id,
				bcb_vendor_amount AS trip_amount,
				sum(bkginv.bkg_vendor_collected) trip_vendor_collected,
				(bcb_vendor_amount -  sum(bkginv.bkg_vendor_collected)) as ven_trans_amount,
				CONCAT('[',GROUP_CONCAT(JSON_OBJECT('id',bkg_booking_id,'collected',bkginv.bkg_vendor_collected, 'routes', concat(c1.cty_name, ' - ', c2.cty_name))),']') as tripDetails,
				GROUP_CONCAT(bkg_status) as bstatus,
				group_concat(bkginv.bkg_advance_amount) bkg_advance_amount,
				group_concat(
					concat(c1.cty_name, ' - ', c2.cty_name) SEPARATOR ', ')
					from_city
			FROM booking_cab
				INNER JOIN booking bcbbkg
					ON     bcbbkg.bkg_bcb_id = booking_cab.bcb_id
						AND booking_cab.bcb_active = 1 AND bcbbkg.bkg_status IN (5,6,7)
				INNER JOIN booking_invoice bkginv ON bcbbkg.bkg_id = bkginv.biv_bkg_id      
				INNER JOIN cities AS c1
					ON c1.cty_id = bcbbkg.bkg_from_city_id
				INNER JOIN cities AS c2
					ON c2.cty_id = bcbbkg.bkg_to_city_id
			WHERE 1 $criteria
        GROUP BY booking_cab.bcb_id HAVING NOT FIND_IN_SET(5, bstatus)
        ORDER BY ven_trans_date DESC $limit";
		$recordSet	 = DBUtil::query($sql);

		return $recordSet;
	}
	/**
	 * @deprecated new function drvTransactionList
	 * @param type $driverId
	 * @param type $fromDate
	 * @param type $toDate
	 * @param type $orderBy
	 * @return type
	 */
	public static function driverTransactionList($driverId, $fromDate = '', $toDate = '', $orderBy = 'DESC')
	{

		$dateRange = '';
		if (($fromDate != '' && $fromDate != '1970-01-01') && ($toDate != '' && $toDate != '1970-01-01'))
		{
			if ($vendorAmt == '0')
			{
				$fromDate = DateTimeFormat::DatePickerToDate($fromDate);

				$toDate = DateTimeFormat::DatePickerToDate($toDate);
			}
			$dateRange	 .= " AND DATE(act_date)>='$fromDate' AND DATE(act_date)<='$toDate'";
			$actOpenDate = "DATE(act_date)<'$fromDate'";
		}



		$sql		 = "SELECT *, @runningBal1 := @runningBal1 + drv_bonus_amount as runningBalance
FROM (
			SELECT booking.bkg_booking_id AS booking_id,
			account_transactions.act_date AS drv_trans_date,
			account_transactions.act_created AS drv_createdate,
			account_trans_details.adt_amount AS drv_bonus_amount,
			account_transactions.act_remarks AS drv_remarks,
			concat(admins.adm_fname, ' ', admins.adm_lname) AS adm_name,
			 IF(atd1.adt_ledger_id=41, booking.bkg_booking_id , account_ledger.ledgerName ) ledgerNames
		 FROM `account_transactions`
		      INNER JOIN `account_trans_details`
			 ON     account_transactions.act_id = account_trans_details.adt_trans_id
			    AND account_trans_details.adt_ledger_id = 40
			    AND account_trans_details.adt_status = 1
			    AND account_trans_details.adt_active = 1
			    AND account_transactions.act_status = 1
			    AND account_transactions.act_active = 1
			    AND account_trans_details.adt_trans_ref_id = '$driverId'
		       INNER JOIN account_trans_details atd1
			       ON account_transactions.act_id = atd1.adt_trans_id
				  AND ((abs(account_trans_details.adt_amount) <> account_trans_details.adt_amount
					   AND atd1.adt_amount > 0)
				       OR (abs(account_trans_details.adt_amount) = account_trans_details.adt_amount
					   AND atd1.adt_amount < 0)) 
			LEFT JOIN booking
			       ON     atd1.adt_ledger_id = 41
				  AND atd1.adt_trans_ref_id = booking.bkg_id     
			LEFT JOIN `admins` ON admins.adm_id = account_transactions.act_user_id AND account_transactions.act_user_type = 4
		      INNER JOIN `account_ledger`
			 ON atd1.adt_ledger_id = account_ledger.ledgerId
		      INNER JOIN `drivers`
			 ON drivers.drv_id = account_trans_details.adt_trans_ref_id
		 WHERE act_active = 1 AND act_status = 1 AND account_transactions.act_type = 6
		 GROUP BY account_trans_details.adt_id ORDER BY account_transactions.act_date ASC
     ) a
JOIN (SELECT @runningBal1 := ifNULL(SUM(adt_amount),0) openBalance FROM account_trans_details
INNER JOIN account_transactions ON act_id=adt_trans_id
WHERE act_date<'2016-06-01 00:00:00'
AND adt_ledger_id=40 AND adt_status=1 AND adt_active=1 AND act_status=1 AND act_active=1 AND
adt_trans_ref_id='$driverId') r";
		$recordSet	 = DBUtil::queryAll($sql);
		return $recordSet;
	}
	/**
	 * function use for drvTransction old one drvTransactionList
	 * @param type $driverId
	 * @param type $fromDate
	 * @param type $toDate
	 * @param type $orderBy
	 * @return type
	 */

	public static function drvTransactionList($driverId, $fromDate = '', $toDate = '', $orderBy = 'DESC')
	{

		//$dateRange = 'AND act_date < "2016-06-01 00:00:00"';
		$dateRange = "AND act_date > '2016-06-01 00:00:00'";
		if (($fromDate != '' && $fromDate != '1970-01-01') && ($toDate != '' && $toDate != '1970-01-01'))
		{
			if ($vendorAmt == '0')
			{
				$fromDate = DateTimeFormat::DatePickerToDate($fromDate);

				$toDate = DateTimeFormat::DatePickerToDate($toDate);
			}
			//$dateRange	 = " AND DATE(act_date)>='$fromDate' AND DATE(act_date)<='$toDate'";
			$dateRange	 = " AND DATE(`act_date`) BETWEEN '$fromDate' AND '$toDate' " ;
			$actOpenDate = "DATE(act_date)>'$fromDate'";
			
		}



		$sql		 = "SELECT *, @runningBal1 := @runningBal1 + drv_bonus_amount as runningBalance
			FROM (
			SELECT booking.bkg_booking_id AS booking_id,
			account_transactions.act_date AS drv_trans_date,
			account_transactions.act_created AS drv_createdate,
			account_trans_details.adt_amount AS drv_bonus_amount,
			account_transactions.act_remarks AS drv_remarks,
			concat(admins.adm_fname, ' ', admins.adm_lname) AS adm_name,
			 IF(atd1.adt_ledger_id=41, booking.bkg_booking_id , account_ledger.ledgerName ) ledgerNames
		 FROM `account_transactions`
		      INNER JOIN `account_trans_details`
			 ON     account_transactions.act_id = account_trans_details.adt_trans_id
			    AND account_trans_details.adt_ledger_id = 40
			    AND account_trans_details.adt_status = 1
			    AND account_trans_details.adt_active = 1
			    AND account_transactions.act_status = 1
			    AND account_transactions.act_active = 1
			    AND account_trans_details.adt_trans_ref_id = '$driverId'
		       INNER JOIN account_trans_details atd1
			       ON account_transactions.act_id = atd1.adt_trans_id
				  AND ((abs(account_trans_details.adt_amount) <> account_trans_details.adt_amount
					   AND atd1.adt_amount > 0)
				       OR (abs(account_trans_details.adt_amount) = account_trans_details.adt_amount
					   AND atd1.adt_amount < 0)) 
			LEFT JOIN booking
			       ON     atd1.adt_ledger_id = 41
				  AND atd1.adt_trans_ref_id = booking.bkg_id     
			LEFT JOIN `admins` ON admins.adm_id = account_transactions.act_user_id AND account_transactions.act_user_type = 4
		      INNER JOIN `account_ledger`
			 ON atd1.adt_ledger_id = account_ledger.ledgerId
		      INNER JOIN `drivers`
			 ON drivers.drv_id = account_trans_details.adt_trans_ref_id
		 WHERE act_active = 1 AND act_status = 1 AND account_transactions.act_type = 6 $dateRange
		 GROUP BY account_trans_details.adt_id ORDER BY account_transactions.act_date ASC
     ) a
JOIN (SELECT @runningBal1 := ifNULL(SUM(adt_amount),0) openBalance FROM account_trans_details
INNER JOIN account_transactions ON act_id=adt_trans_id
WHERE 1  
AND adt_ledger_id=40 AND adt_status=1 AND adt_active=1 AND act_status=1 AND act_active=1 AND
adt_trans_ref_id='$driverId') r";
		//echo $sql;
		$recordSet	 = DBUtil::query($sql);
		return $recordSet;
	}
	public static function vendorCollectionReport($pagination = '', $type = '')
	{
		$size			 = ($pagination != '') ? $pagination : 10;
		$status			 = [3, 5, 6, 7];
		$status			 = implode(',', $status);
		$vendor_status	 = [1, 2];
		$vendor_status	 = implode(',', $vendor_status);
		$sql			 = "SELECT    
								vendors.vnd_id, 
								vendors.vnd_name AS vendor_name,
								SUM(account_trans_details.adt_amount) AS vendor_amount, 
								countFlag
                           FROM vendors
                                JOIN account_trans_details  ON vendors.vnd_id = account_trans_details.adt_trans_ref_id AND account_trans_details.adt_ledger_id = 14 AND account_trans_details.adt_type=2
							LEFT JOIN 
							(
								SELECT   booking_cab.bcb_vendor_id,COUNT(1) AS countFlag
								FROM     booking  JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 AND booking.bkg_active = 1
								JOIN booking_pref ON booking_pref.bpr_bkg_id = booking.bkg_id AND booking_pref.bkg_account_flag = 1
								WHERE    booking.bkg_status IN ($status)
								GROUP BY booking_cab.bcb_vendor_id

							) a 
				            ON a.bcb_vendor_id = vendors.vnd_id
                            WHERE account_trans_details.adt_id IS NOT NULL AND vendors.vnd_id IS NOT NULL AND vendors.vnd_active IN ($vendor_status)
                            GROUP BY vendors.vnd_id";

		$sqlCount = "   SELECT  DISTINCT vendors.vnd_id
						FROM vendors JOIN account_trans_details  ON vendors.vnd_id = account_trans_details.adt_trans_ref_id AND account_trans_details.adt_ledger_id = 14 AND	 account_trans_details.adt_type =2
						WHERE account_trans_details.adt_id IS NOT NULL AND vendors.vnd_id IS NOT NULL AND vendors.vnd_active IN ($vendor_status)	";

		if ($type == 'command')
		{
			$recordSet = DBUtil::queryAll($sql, DBUtil::SDB());
			return $recordSet;
		}
		else
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['vendor_name', 'vendor_amount', 'countFlag', 'vendorTotalTrips', 'vnd_credit_limit', 'vendorPhone', 'vendorEmail', 'vendorRating'],
					'defaultOrder'	 => 'vnd_id ASC'], 'pagination'	 => ['pageSize' => $size],
			]);
			return $dataprovider;
		}
	}

	public static function bulkReport($pagination = '', $type = '')
	{
		$size	 = ($pagination != '') ? $pagination : 10;
		$status	 = [3, 5, 6, 7];
		$sql	 = "SELECT
					vendors.vnd_id,
					vendors.vnd_name AS vendor_name,
					contact_email.eml_email_address AS vendorEmail,
					vendor_stats.vrs_vnd_overall_rating AS vendorRating,
					vendor_stats.vrs_total_trips AS vendorTotalTrips,
					vendor_amount,
					vendor_stats.vrs_credit_limit AS vnd_credit_limit
					FROM `vendors` as v
					JOIN vendors ON vendors.vnd_id = v.vnd_ref_code
					JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status = 1
					JOIN contact ON contact.ctt_id = cp.cr_contact_id and contact.ctt_active =1 and contact.ctt_id = contact.ctt_ref_code
					JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
					JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id
					LEFT JOIN contact_email ON contact_email.eml_contact_id = contact.ctt_id AND contact_email.eml_is_primary=1
					LEFT JOIN
					(
					SELECT SUM(account_trans_details.adt_amount) AS vendor_amount, adt_trans_ref_id
					FROM `account_trans_details`
					INNER JOIN `account_transactions` ON account_transactions.act_id = account_trans_details.adt_trans_id AND account_trans_details.adt_ledger_id IN(14) AND account_trans_details.adt_type = 2 AND account_transactions.act_status = 1 AND account_trans_details.adt_active = 1
					GROUP BY account_trans_details.adt_trans_ref_id
					) as totAmt ON totAmt.adt_trans_ref_id=vendors.vnd_id
					WHERE vendors.vnd_id IS NOT NULL AND(totAmt.vendor_amount IS NOT NULL AND totAmt.vendor_amount<>0) AND vendors.vnd_active=1 GROUP BY vendors.vnd_id  ";

		$sqlCount = "SELECT
						vendors.vnd_id,
						vendor_amount
						FROM `vendors` as v
						JOIN vendors ON vendors.vnd_id = v.vnd_ref_code
						JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status = 1
						JOIN contact ON contact.ctt_id = cp.cr_contact_id and contact.ctt_active =1 and contact.ctt_id = contact.ctt_ref_code

						JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
						JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id
						LEFT JOIN
						(
						SELECT SUM(account_trans_details.adt_amount) AS vendor_amount, adt_trans_ref_id
						FROM `account_trans_details`
						INNER JOIN `account_transactions` ON account_transactions.act_id = account_trans_details.adt_trans_id AND account_trans_details.adt_ledger_id IN(14) AND account_trans_details.adt_type = 2 AND account_transactions.act_status = 1 AND account_trans_details.adt_active = 1
						GROUP BY account_trans_details.adt_trans_ref_id
						) as totAmt ON totAmt.adt_trans_ref_id=vendors.vnd_id
						WHERE vendors.vnd_id IS NOT NULL AND(totAmt.vendor_amount IS NOT NULL AND totAmt.vendor_amount<>0) AND vendors.vnd_active=1 GROUP BY vendors.vnd_id  ";
		if ($type == 'command')
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
		else
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['vendor_name', 'vendor_amount', 'vendorTotalTrips', 'vnd_credit_limit', 'vendorEmail', 'vendorRating'],
					'defaultOrder'	 => 'vnd_id ASC'], 'pagination'	 => ['pageSize' => $size],
			]);
			return $dataprovider;
		}
	}

	public function vendorAccount($date1 = '', $date2 = '', $vendorId = '', $type = 'data')
	{

		if ($date1 != '' && $date2 != '')
		{
			if ($type == 'data')
			{
				$fromDate	 = DateTimeFormat::DatePickerToDate($date1) . ' ' . '00:00:00';
				$toDate		 = DateTimeFormat::DatePickerToDate($date2) . ' ' . '23:59:59';
			}
			else
			{
				$date1String = explode('/', $date1);
				$date2String = explode('/', $date2);
				$fromDate	 = $date1String[2] . "-" . $date1String[1] . "-" . $date1String[0] . ' ' . '00:00:00';
				$toDate		 = $date2String[2] . "-" . $date2String[1] . "-" . $date2String[0] . ' ' . '23:59:59';
			}
		}
		$status				 = [3, 5, 6, 7];
		$status				 = implode(',', $status);
		$condRequiredData	 = '';
		if ($type != 'invoice')
		{
			if (($date1 != '' && $date1 != '1970-01-01') && ($date2 != '' && $date2 != '1970-01-01'))
			{
				$condRequiredData .= " AND act.act_date BETWEEN  '$fromDate' AND '$toDate' ";
			}
			$condPastData = "";
			if ($fromDate != '')
			{
				$condPastData = " AND act1.act_date < '$fromDate' ";
			}
		}
		$sql = "SELECT   vnd_name, vnd_id, vrs.vrs_total_trips AS vendorTotalTrips, tTotTrans.current_payable, tTotTrans.vendorAmount, IF(tPastTrans.pastDues IS NULL, 0, tPastTrans.pastDues) pastDues
				FROM     vendors
				JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vendors.vnd_id
				JOIN
				(SELECT   adt.adt_id, sum(adt.adt_amount) current_payable, adt.adt_trans_ref_id, act.act_type,  ABS(SUM(IF(act.act_type = 5, adt.adt_amount, 0))) vendorAmount, adt.adt_active, adt.adt_status
				FROM     account_trans_details adt JOIN account_transactions act ON act.act_id = adt.adt_trans_id
				WHERE    act.act_active = 1 AND act.act_id IS NOT NULL AND adt.adt_ledger_id = 14 AND adt.adt_type = 2  $condRequiredData
				GROUP BY adt.adt_trans_ref_id) tTotTrans
				ON tTotTrans.adt_trans_ref_id = vendors.vnd_id AND tTotTrans.adt_active = 1 AND tTotTrans.adt_status = 1
				LEFT JOIN
				(SELECT   sum(adt1.adt_amount) pastDues, adt1.adt_trans_ref_id, act1.act_type, adt1.adt_active, adt1.adt_status
				FROM     account_trans_details adt1 JOIN account_transactions act1 ON act1.act_id = adt1.adt_trans_id
				WHERE    act1.act_active = 1 AND act1.act_id IS NOT NULL AND adt1.adt_ledger_id = 14 AND adt1.adt_type = 2 $condPastData
				GROUP BY adt1.adt_trans_ref_id) tPastTrans
				ON tPastTrans.adt_trans_ref_id = vendors.vnd_id AND tPastTrans.adt_active = 1 AND tPastTrans.adt_status = 1
				WHERE    vendors.vnd_name IS NOT NULL AND tTotTrans.adt_id IS NOT NULL AND vendors.vnd_active = 1 ";

		$sqlCount = "SELECT   vnd_name
        FROM     vendors
                 JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vendors.vnd_id
                 JOIN account_trans_details adt ON adt.adt_trans_ref_id = vendors.vnd_id
                 JOIN account_transactions act  ON act.act_id = adt.adt_trans_id $condRequiredData
        WHERE    vendors.vnd_name IS NOT NULL $condRequiredData AND act.act_active =1 AND act.act_id IS NOT NULL AND adt.adt_ledger_id = 14 AND adt.adt_type = 2 AND adt.adt_active = 1 AND adt.
                 adt_status =1 AND vendors.vnd_active = 1 ";

		if ($vendorId != '' && $vendorId != '0')
		{
			$sql		 .= " AND vnd_id  = '$vendorId'";
			$sqlCount	 .= " AND vnd_id  = '$vendorId' ";
		}
		$sqlCount .= "  GROUP BY adt.adt_trans_ref_id";
		if ($type == 'data')
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc");
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['vnd_name', 'vendorAmount', 'ven_tds_amount', 'vendorTotalTrips', 'current_payable', 'pastDues', 'credit_limit'],
					'defaultOrder'	 => 'current_payable DESC'], 'pagination'	 => ['pageSize' => 25],
			]);
			return $dataprovider;
		}
		else if ($type == 'command' || $type == 'invoice')
		{
			$recordSet = DBUtil::queryAll($sql);
			return $recordSet;
		}
	}

	public static function transactionList($agtId, $transDate1 = '', $transDate2 = '', $ledgers = '')
	{
		$actOpenDate = "";
		$condition	 = "0";
		$condition1	 = "";
		$actOpenDate = "";
		//$limit       = "LIMIT 0,100";
		if ($transDate1 != '' && $transDate2 != '')
		{
			$fromTime		 = '00:00:00';
			$toTime			 = '23:59:59';
			$fromDateTime	 = $transDate1 . ' ' . $fromTime;
			$toDateTime		 = $transDate2 . ' ' . $toTime;
//			$condition		 = " (SELECT SUM(adt_amount)-adt_amount
//                                              FROM account_trans_details
//                                               JOIN account_transactions ON act_id = adt_trans_id
//                                             WHERE     act_active=1 AND adt_trans_ref_id = $agtId AND adt_type=3 AND adt_ledger_id IN (26, 15)
//                                                   AND adt_active = 1 AND adt_status=1 AND act_date < '$fromDateTime'
//                                                   )";
//			$condition1		 = "  AND act.act_date BETWEEN '$fromDateTime' AND '$toDateTime'";
		}
		$dateRange = '';
		if (($transDate1 != '' && $transDate1 != '1970-01-01') && ($transDate2 != '' && $transDate2 != '1970-01-01'))
		{

//            $fromDate = DateTimeFormat::DatePickerToDate($fromDate);
//
//            $toDate = DateTimeFormat::DatePickerToDate($toDate);
			//$sql .= " AND  date(trans.ven_trans_date) BETWEEN '$fromDate' AND '$toDate'";
			// $dateRange   .= " AND date(act_date) BETWEEN '$fromDate' AND '$toDate'";
			$dateRange	 .= " AND act_date>='$fromDateTime' AND act_date<='$toDateTime'";
			$actOpenDate = " act_date<'$fromDateTime' AND ";
			if ($ledgers != '')
			{
				$ledgersID = "AND account_ledger.ledgerId IN($ledgers)";
			}
		}


		$sql		 = "SELECT *, @runningBal1 := adt_amount +@runningBal1   as runningBalance
		FROM (SELECT act_date,act_created,act_id, atd.adt_id, atd.adt_ledger_id,atd1.adt_ledger_id atd1LedgerId, act.act_remarks, agents.agt_company, agents.agt_email, agents.agt_phone, 
		booking.bkg_booking_id bookingId,booking.bkg_agent_ref_code,
		atd1.adt_remarks agt_trans_remarks,
		bcb_cab_number,bcb_driver_id,booking_user.bkg_user_fname fname,booking_user.bkg_user_lname lname,
		concat(c1.cty_name,' - ',c2.cty_name) bookingInfo,
		GROUP_CONCAT(atd1.adt_ledger_id) as ledgerIds,
		GROUP_CONCAT(
		CASE atd1.adt_ledger_id
		WHEN 26 THEN CONCAT(account_ledger.ledgerName, ':(',booking.bkg_booking_id ,')', IFNULL(booking.bkg_agent_ref_code,''))
        WHEN 35 THEN CONCAT(account_ledger.ledgerName, ':(',booking.bkg_booking_id ,')', IFNULL(booking.bkg_agent_ref_code,''))
		ELSE account_ledger.ledgerName
		END
		SEPARATOR ', ') as ledgerNames, atd.adt_amount,account_ledger.ledgerName entityType,
		booking.bkg_pickup_date,
		booking_invoice.bkg_advance_amount,
		CONCAT(admins.adm_fname,' ',admins.adm_lname) adminName
		FROM account_transactions act
		INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id
		AND atd.adt_ledger_id=15 AND atd.adt_trans_ref_id=$agtId AND atd.adt_active = 1 AND atd.adt_status = 1
		$dateRange
		INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id 
		AND ((abs(atd.adt_amount)<>atd.adt_amount AND atd1.adt_amount>0)
		OR (abs(atd.adt_amount)=atd.adt_amount AND atd1.adt_amount<0))
		INNER JOIN account_ledger ON atd1.adt_ledger_id=account_ledger.ledgerId $ledgersID
		INNER JOIN agents ON atd.adt_trans_ref_id=agt_id
		LEFT JOIN booking ON atd1.adt_ledger_id IN(35,26) AND atd1.adt_trans_ref_id=booking.bkg_id
		LEFT JOIN booking_invoice ON booking.bkg_id=booking_invoice.biv_bkg_id
		LEFT JOIN admins ON admins.adm_id = act.act_user_id AND act.act_user_type = 4
		LEFT JOIN cities c1 ON booking.bkg_from_city_id = c1.cty_id
		LEFT JOIN cities c2 ON booking.bkg_to_city_id = c2.cty_id
		LEFT JOIN booking_cab ON booking.bkg_bcb_id=booking_cab.bcb_id
		LEFT JOIN booking_user ON booking.bkg_id=booking_user.bui_id
		WHERE act_active = 1 AND atd1.adt_active = 1 AND atd1.adt_status = 1 AND act_date >= '2021-04-01 00:00:00' 
		GROUP BY atd.adt_id ORDER BY act.act_date ASC ) a
		JOIN (SELECT @runningBal1 := ifNULL(SUM(adt_amount),0) openBalance FROM account_trans_details
		INNER JOIN account_transactions ON act_id=adt_trans_id
		WHERE $actOpenDate 
		adt_ledger_id=15 AND act_active = 1 AND adt_active=1 AND adt_status=1 AND act_date >= '2021-04-01 00:00:00' AND adt_trans_ref_id=$agtId) r";
		$recordSet	 = DBUtil::queryAll($sql);
		return $recordSet;
	}

	public static function accountTotalSummary($agtId, $transDate1 = '', $transDate2 = '', $openingDate = null, $tillDate = null)
	{

		$sql = "SELECT SUM(adt_amount) totAmount, agt.agt_opening_deposit securitydepo, 
				agt.agt_effective_credit_limit effectivecreditLimit, agt.agt_credit_limit creditLimit,agt.agt_company,agt_overdue_days
                FROM   account_trans_details adt 
				 JOIN agents agt ON adt.adt_trans_ref_id = agt.agt_id AND adt.adt_type = 3
                INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt_ledger_id=15
				AND adt.adt_trans_ref_id = $agtId
				AND adt.adt_status=1 AND act.act_active=1 
				AND adt.adt_active=1";
		if ($transDate1 != '')
		{
			$fromTime		 = '00:00:00';
			$fromDateTime	 = $transDate1 . ' ' . $fromTime;
			$sql			 .= " AND act.act_date>='$fromDateTime'";
		}
		if ($transDate2 != '')
		{
			$toTime		 = '23:59:59';
			$toDateTime	 = $transDate2 . ' ' . $toTime;
			$sql		 .= " AND act.act_date<='$toDateTime'";
		}
		if ($openingDate != '')
		{
			$fromTime		 = '00:00:00';
			$fromDateTime	 = $openingDate . ' ' . $fromTime;
			$sql			 .= " AND act.act_date < '$fromDateTime'";
		}
		if ($tillDate != '')
		{
			$sql .= " AND act.act_date<='$tillDate'";
		}
		$sql .= " AND act.act_date >= '2021-04-01 00:00:00'";

		//$sql			 .= " GROUP BY agt_id";		
		$agentAmount = DBUtil::queryRow($sql);
		return $agentAmount;
	}

	/** @deprecated Check AccountTransDetails::getByBkgID */
	public static function getByBookingID($bkgId)
	{
		$models = AccountTransDetails::model()->findAllBySql("SELECT * FROM account_trans_details
          JOIN account_transactions ON act_id = adt_trans_id
          JOIN account_ledger ON account_ledger.ledgerId = adt_ledger_id AND account_transactions.act_type=1
         LEFT JOIN payment_gateway ON payment_gateway.apg_id = account_trans_details.adt_trans_id
         WHERE act_active=1 AND act_type = 1 AND act_ref_id = '$bkgId' AND account_ledger.accountGroupId IN (27, 28)");

		return $models;
	}

	public static function getByBkgID($bkgId)
	{
		$rows = DBUtil::query("SELECT * FROM account_trans_details
          JOIN account_transactions ON act_id = adt_trans_id
          JOIN account_ledger ON account_ledger.ledgerId = adt_ledger_id AND account_transactions.act_type=1
         LEFT JOIN payment_gateway ON payment_gateway.apg_id = account_trans_details.adt_trans_ref_id    
         WHERE act_active=1 AND act_type = 1 AND act_ref_id = '$bkgId' AND account_ledger.accountGroupId IN (27, 28)", DBUtil::SDB3());

		return $rows;
	}

	public function getPaymentModeByBkgId($bkgID)
	{
		$val		 = AccountTransDetails::getByBkgID($bkgID);
		$paymodes	 = '';
		foreach ($val as $row)
		{
			$paymodes .= $row[ledgerName] . ',';
		}
		if ($paymodes != '')
		{
			return rtrim($paymodes, ',');
		}
		else
		{
			return 'NA';
		}
	}

	public function mapping(array $models)
	{
		//$plist	 = PaymentType::model()->getList();
		$arr = [];
		foreach ($models as $model)
		{
			$plist				 = PaymentType::model()->ptpList($model->adt_ledger_id);
			$obj				 = new stdClass();
			$obj->code			 = $model->apg_code;
			$obj->gateway		 = $plist;
			$obj->amount		 = $model->adt_amount;
			$obj->initiateTime	 = $model->act_date;
			$obj->completeTime	 = $model->act_date;
			$obj->status		 = $this->getStatusDesc($model->adt_status);
			$arr[]				 = $obj;
		}
		return $arr;
	}

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

	public function getTotCommissionByBooking($bkgId)
	{
		$sql = "SELECT SUM(adt_amount)
                          FROM
                              account_trans_details
                          WHERE
                              adt_trans_id IN(
                              SELECT
                                  adt_trans_id
                              FROM
                                  `account_trans_details`
                               JOIN account_transactions ON account_transactions.act_id = account_trans_details.adt_trans_id
                              WHERE
                                  adt_ledger_id = " . Accounting::LI_BOOKING . " AND adt_status = 1 AND account_transactions.act_active = 1 AND adt_active = 1 AND adt_trans_ref_id = $bkgId AND account_transactions.act_type=" . Accounting::AT_PARTNER . "
                          ) AND adt_ledger_id = " . Accounting::LI_COMMISSION;
		return DBUtil::queryScalar($sql);
	}

	public static function getTotTransByVndId($vndId)
	{
		$sql = "SELECT
                    v1.totTrans,
                    v1.trans_vendor_id,
                    vendor_pref.vnp_is_freeze AS vnd_is_freeze,
                    vendor_pref.vnp_cod_freeze AS vnd_cod_freeze,
					vendor_pref.vnp_gozonow_enabled,

					vendor_pref.vnp_low_rating_freeze AS vnp_low_rating_freeze,
					vendor_pref.vnp_doc_pending_freeze AS vnp_doc_pending_freeze,
					vendor_pref.vnp_manual_freeze AS vnp_manual_freeze,

                    vendors.vnd_id
                    FROM `vendors`
						INNER JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id=vendors.vnd_id
                    LEFT JOIN
                    (
                        SELECT SUM( account_trans_details.adt_amount ) AS totTrans,
                            account_trans_details.adt_trans_ref_id trans_vendor_id
                        FROM `account_trans_details`
                         JOIN `account_transactions` ON account_transactions.act_id = account_trans_details.adt_trans_id 
							AND account_transactions.act_active = 1
                        WHERE
                            account_trans_details.adt_active = 1 AND account_trans_details.adt_status = 1 AND account_trans_details.adt_type = 2 
							AND account_trans_details.adt_ledger_id = 14 AND account_trans_details.adt_trans_ref_id=$vndId
                        GROUP BY account_trans_details.adt_trans_ref_id
                    ) AS v1
                    ON v1.trans_vendor_id = vendors.vnd_id
						WHERE vnd_id =$vndId
                    GROUP BY vendors.vnd_id";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public function getTransactionsList()
	{
		$sql			 = "SELECT act_date,act_type,act_id,
			adt1.adt_id adt_id1,adt1.adt_type adt_type1,adt1.adt_ledger_id adt_ledger_id1,adt1.adt_amount adt_amount1,adt1.adt_remarks adt_remarks1,
			adt2.adt_id adt_id2,adt2.adt_type adt_type2,adt2.adt_ledger_id adt_ledger_id2,adt2.adt_amount adt_amount2,adt2.adt_remarks adt_remarks2
			FROM account_transactions act
			INNER JOIN account_trans_details adt1 ON act.act_id=adt1.adt_trans_id AND adt1.adt_active=1 AND adt1.adt_amount > 0
			INNER JOIN account_trans_details adt2 ON act.act_id=adt2.adt_trans_id AND adt2.adt_active=1 AND adt2.adt_amount < 0
			WHERE act_active=1 
			GROUP BY act.act_id";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc");
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => [],
				'defaultOrder'	 => 'act.act_id DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getBookingType($typeID)
	{
		switch ($typeID)
		{
			case Accounting::AT_BOOKING:
				$accTypeName = 'Booking';
				break;
			case Accounting::AT_PARTNER:
				$accTypeName = 'Partner';
				break;
			case Accounting::AT_OPERATOR:
				$accTypeName = 'Operator';
				break;
			case Accounting::AT_ONLINEPAYMENT:
				$accTypeName = 'Onlinepayment';
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
		}
		return $accTypeName;
	}

	public static function getAccountTypeList()
	{
		$atList = [
			Accounting::AT_BOOKING		 => 'Booking',
			Accounting::AT_PARTNER		 => 'Partner',
			Accounting::AT_OPERATOR		 => 'Operator',
			Accounting::AT_ONLINEPAYMENT => 'Onlinepayment',
			Accounting::AT_TRIP			 => 'Trip',
			Accounting::AT_DRIVER		 => 'Driver',
			Accounting::AT_USER			 => 'User'
		];
		return $atList;
	}

	public static function getAccountType($typeID)
	{
		switch ($typeID)
		{
			case Accounting::AT_BOOKING:
				$accTypeName = 'Booking';
				break;
			case Accounting::AT_PARTNER:
				$accTypeName = 'Partner';
				break;
			case Accounting::AT_OPERATOR:
				$accTypeName = 'Operator';
				break;
			case Accounting::AT_ONLINEPAYMENT:
				$accTypeName = 'Onlinepayment';
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
		}
		return $accTypeName;
	}

	public static function getLedgerAccountList($bkg_id, $bcb_id)
	{
		$sql1 = "SELECT IF(atd.adt_ledger_id=22, atd.adt_trans_ref_id, 0) as bcb_id,IF(atd.adt_ledger_id=13, atd.adt_trans_ref_id, 0) as bkg_id, bkg_status bkgStatus, bkg_pickup_date as pickupDate, bkg_agent_id as AgentId,
				(IF(abs(atd.adt_amount)<=abs(atd1.adt_amount), atd.adt_amount*-1, atd1.adt_amount)) as Amount,
				alfrom.ledgerName as fromLedgerName, alto.ledgerName as toLedgerName,
				atd.adt_ledger_id as fromLedgerId, atd1.adt_ledger_id as toLedgerId,
				act_remarks, atd.adt_remarks,atd1.adt_amount toamount, atd.adt_amount fromamount
				FROM account_trans_details atd
				INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 
					AND atd.adt_ledger_id IN (13,22) AND atd.adt_status=1
				INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id <> atd.adt_ledger_id AND atd1.adt_active=1
					AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0))
				INNER JOIN account_ledger alfrom ON alfrom.ledgerId=atd.adt_ledger_id
				INNER JOIN account_ledger alto ON alto.ledgerId=atd1.adt_ledger_id
				INNER JOIN booking ON bkg_id=atd.adt_trans_ref_id AND bkg_agent_id IS NOT NULL
				INNER JOIN account_ledger al ON al.ledgerId=atd.adt_ledger_id AND atd1.adt_status=1
				INNER JOIN account_ledger al1 ON al1.ledgerId=atd1.adt_ledger_id
				WHERE atd.adt_ledger_id=13 AND atd.adt_trans_ref_id=$bkg_id";

		$sql2 = "SELECT IF(atd.adt_ledger_id IN (22,37,55), atd.adt_trans_ref_id, 0) as bcb_id,IF(atd.adt_ledger_id=13, atd.adt_trans_ref_id, 0) as bkg_id, bkg_status bkgStatus, bkg_pickup_date as pickupDate, bkg_agent_id as AgentId,
				(IF(abs(atd.adt_amount)<=abs(atd1.adt_amount), atd.adt_amount*-1, atd1.adt_amount)) as Amount,
				alfrom.ledgerName as fromLedgerName, alto.ledgerName as toLedgerName,
				atd.adt_ledger_id as fromLedgerId, atd1.adt_ledger_id as toLedgerId,
				act_remarks, atd.adt_remarks,atd1.adt_amount toamount, atd.adt_amount fromamount
				FROM account_trans_details atd
				INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 
					AND atd.adt_ledger_id IN (37,22,55) AND atd.adt_status=1
				INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id <> atd.adt_ledger_id AND atd1.adt_active=1
					AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0))
				INNER JOIN account_ledger alfrom ON alfrom.ledgerId=atd.adt_ledger_id
				INNER JOIN account_ledger alto ON alto.ledgerId=atd1.adt_ledger_id
				INNER JOIN booking ON bkg_bcb_id=atd.adt_trans_ref_id AND bkg_agent_id IS NOT NULL
				INNER JOIN account_ledger al ON al.ledgerId=atd.adt_ledger_id AND atd1.adt_status=1
				INNER JOIN account_ledger al1 ON al1.ledgerId=atd1.adt_ledger_id
				WHERE atd.adt_trans_ref_id=$bcb_id";

		$sql = $sql1;
		$sql .= " UNION ";
		$sql .= $sql2;

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public static function getTransactionData($agtId, $transDate1 = '', $transDate2 = '')
	{
		$dateRange	 = '';
		$limit		 = ' LIMIT 20 ';
		if (($transDate1 != '' && $transDate1 != '1970-01-01') && ($transDate2 != '' && $transDate2 != '1970-01-01'))
		{
			$fromTime		 = '00:00:00';
			$toTime			 = '23:59:59';
			$fromDateTime	 = $transDate1 . ' ' . $fromTime;
			$toDateTime		 = $transDate2 . ' ' . $toTime;
			$dateRange		 .= " AND act_date>='$fromDateTime' AND act_date<='$toDateTime'";
			$limit			 = '';
		}
		$sql		 = "SELECT act_date as transactionDate,booking.bkg_booking_id as bookingId,atd.adt_amount as transactionAmount,
		IF(atd.adt_amount < 0, 'credit', 'debit') AS status      	
		FROM account_transactions act
		INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id
		AND atd.adt_ledger_id=15 AND atd.adt_trans_ref_id=$agtId AND atd.adt_active = 1
		$dateRange
		INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id
		AND ((abs(atd.adt_amount)<>atd.adt_amount AND atd1.adt_amount>0)
		OR (abs(atd.adt_amount)=atd.adt_amount AND atd1.adt_amount<0))
		INNER JOIN account_ledger ON atd1.adt_ledger_id=account_ledger.ledgerId
		INNER JOIN agents ON atd.adt_trans_ref_id=agt_id
		LEFT JOIN booking ON atd1.adt_ledger_id IN(35,26) AND atd1.adt_trans_ref_id=booking.bkg_id AND bkg_status IN(1,2,3,4,5,6,7,9)
		LEFT JOIN booking_invoice ON booking.bkg_id=booking_invoice.biv_bkg_id		
		WHERE act_active = 1
		GROUP BY atd.adt_id ORDER BY act.act_date DESC         
		" . $limit;
		$recordSet	 = DBUtil::queryAll($sql);
		return $recordSet;
	}

	public function refundTransaction($model, $bkgmodel)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			$ptpStr					 = PaymentType::model()->getList()[$model->adt_ledger_id];
			$ptpId					 = PaymentType::model()->ptpList($model->adt_ledger_id);
			$bkgId					 = $model->adt_trans_ref_id;
			$arrRefundedModels		 = [];
			$arrRefundedADTModels	 = [];
			//$model->adt_amount		 = -1 * $model->adt_amount;

			if ($model->adt_ledger_id == Accounting::LI_CASH || $model->adt_ledger_id == Accounting::LI_WALLET || $model->adt_ledger_id == Accounting::LI_PARTNERWALLET)
			{
				if ($model->adt_ledger_id == Accounting::LI_CASH)
				{
					$accTransModel1				 = new AccountTransactions();
					$accTransModel1->act_amount	 = $model->adt_amount;
					$accTransModel1->act_date	 = new CDbExpression('NOW()');
					$accTransModel1->act_type	 = Accounting::AT_BOOKING;
					$accTransModel1->act_ref_id	 = $bkgmodel->bkg_id;
					$accTransModel1->act_remarks = "Manual refund added";

					$success = AccountTransactions::model()->refundBooking($accTransModel1->act_date, $accTransModel1->act_amount, $bkgmodel->bkg_id, PaymentType::TYPE_WALLET, $accTransModel1->act_remarks, '', UserInfo::getInstance());
				}
//				if ($model->adt_ledger_id == Accounting::LI_PARTNERCOINS)
//				{
//					$accDetailsTrans->adt_trans_ref_id	 = ($bkgmodel->bkg_agent_id == '') ? 1249 : $bkgmodel->bkg_agent_id;
//					$accDetailsTrans->adt_type			 = Accounting::AT_PARTNER;
//					$accTransModel1						 = new AccountTransactions();
//					$accTransModel1->act_amount			 = $model->adt_amount;
//					$accTransModel1->act_date			 = $bkgmodel->bkg_pickup_date;
//					$accTransModel1->act_type			 = Accounting::AT_PARTNER;
//					$accTransModel1->act_ref_id			 = $bkgmodel->bkg_agent_id;
//					$accTransModel1->act_remarks		 = "Manual partner coins refunded";
//					AccountTransactions::model()->refundBooking($accTransModel1->act_date, $accTransModel1->act_amount, $bkgmodel->bkg_id, PaymentType::TYPE_AGENT_CORP_CREDIT, $accTransModel1->act_remarks, '', UserInfo::getInstance());
//				}
				if ($model->adt_ledger_id == Accounting::LI_PARTNERWALLET)
				{
					$accDetailsTrans->adt_trans_ref_id	 = ($bkgmodel->bkg_agent_id == '') ? 1249 : $bkgmodel->bkg_agent_id;
					$accDetailsTrans->adt_type			 = Accounting::AT_PARTNER;
					$accTransModel1						 = new AccountTransactions();
					$accTransModel1->act_amount			 = $model->adt_amount;
					$accTransModel1->act_date			 = $bkgmodel->bkg_pickup_date;
					$accTransModel1->act_type			 = Accounting::AT_PARTNER;
					$accTransModel1->act_ref_id			 = $bkgmodel->bkg_agent_id;
					$accTransModel1->act_remarks		 = "Manual partner wallet refunded";
					$success							 = AccountTransactions::model()->refundBooking($accTransModel1->act_date, $accTransModel1->act_amount, $bkgmodel->bkg_id, PaymentType::TYPE_AGENT_CORP_CREDIT, $accTransModel1->act_remarks, '', UserInfo::getInstance());
				}
				if ($model->adt_ledger_id == Accounting::LI_WALLET)
				{
					$accTransModel1				 = new AccountTransactions();
					$accTransModel1->act_amount	 = $model->adt_amount;
					$accTransModel1->act_date	 = new CDbExpression('NOW()');
					$accTransModel1->act_type	 = Accounting::AT_BOOKING;
					$accTransModel1->act_ref_id	 = $bkgmodel->bkg_id;
					$accTransModel1->act_remarks = "Balance refunded from booking id :" . $bkgmodel->bkg_id;

					$success = AccountTransactions::model()->refundBooking($accTransModel1->act_date, $accTransModel1->act_amount, $bkgmodel->bkg_id, PaymentType::TYPE_WALLET, $accTransModel1->act_remarks, '', UserInfo::getInstance());
				}
			}
			else if ($model->adt_ledger_id == Accounting::LI_GOZOCOINS)
			{
				$accDetailsTrans				 = new AccountTransDetails();
				$accDetailsTrans->adt_amount	 = ($model->adt_amount > 0) ? -1 * $model->adt_amount : $model->adt_amount;
				$accDetailsTrans->adt_ledger_id	 = $model->adt_ledger_id;
				$accDetailsTrans->adt_remarks	 = $model->adt_remarks;
				$arrRefundedADTModels[]			 = $accDetailsTrans;
				$code							 = '';
				$gozocoinrefunded				 = true;
				$accTransModel1					 = new AccountTransactions();
				$accTransModel1->act_amount		 = $model->adt_amount;
				$accTransModel1->act_date		 = new CDbExpression('NOW()');
				$accTransModel1->act_type		 = Accounting::AT_BOOKING;
				$accTransModel1->act_ref_id		 = $bkgmodel->bkg_id;
				$accTransModel1->act_remarks	 = "Manual gozo coins refunded";

				$success = AccountTransactions::model()->refundBooking($accTransModel1->act_date, $accTransModel1->act_amount, $bkgmodel->bkg_id, PaymentType::TYPE_GOZO_COINS, $accTransModel1->act_remarks, '', UserInfo::getInstance());
			}
			else
			{
				$paymentGateway						 = new PaymentGateway();
				$paymentGateway->apg_booking_id		 = $bkgId;
				$paymentGateway->apg_acc_trans_type	 = Accounting::AT_BOOKING;
				$paymentGateway->apg_trans_ref_id	 = $bkgId;
				$paymentGateway->apg_ledger_id		 = $model->adt_ledger_id;
				$paymentGateway->apg_ptp_id			 = $ptpId;
				$paymentGateway->apg_amount			 = -1 * $model->adt_amount;
				$paymentGateway->apg_mode			 = 1;
				$paymentGateway->apg_remarks		 = "Refund Initiated " . $model->adt_remarks;
				$paymentGateway->apg_ref_id			 = '';
				$paymentGateway->apg_user_type		 = UserInfo::TYPE_ADMIN;
				$paymentGateway->apg_user_id		 = Yii::app()->user->getId();
				$paymentGateway->apg_status			 = 1;
				$paymentGateway->apg_date			 = new CDbExpression('NOW()');
				$paymentGateway->apg_ref_id			 = 0;
				$pgModel							 = $paymentGateway->payment($model->adt_ledger_id);
				$pgModel->refresh();
				$arrRefundedModels[]				 = $pgModel;
				$code								 = $pgModel->apg_code;

				//$amount = -1 * $paymentGateway->apg_amount;
				$success = AccountTransactions::model()->refundBooking($paymentGateway->apg_date, $model->adt_amount, $paymentGateway->apg_trans_ref_id, $paymentGateway->apg_ptp_id, $paymentGateway->apg_remarks, $paymentGateway, UserInfo::getInstance());
			}
			if ($gozocoinrefunded)
			{
				$userCredits = new UserCredits();
				$userCredits->addGozocoins($bkgmodel->bkgUserInfo->bkg_user_id, $bkgId, $accDetailsTrans->adt_amount);
			}
			if ($errors == '')
			{
				$bkgmodel->applyCancelCommission();
				//$params['blg_ref_id'] = $accTransModel->act_id;
				//BookingLog::model()->createLog($model->adt_trans_ref_id, "Refund Process Completed ({$ptpStr} - {$code})", UserInfo::getInstance(), BookingLog::REFUND_PROCESS_COMPLETED, '', $params);
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			$model->addError('bkg_id', $e->getMessage());
			$return['error'] = $model->getErrors();
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	public function getAgentLedgerData($agentId, $fromDate, $toDate)
	{
		$agentAmount		 = AccountTransDetails::model()->accountTotalSummary($agentId, '', $toDate);
		$openingAmount		 = AccountTransDetails::model()->accountTotalSummary($agentId, '', '', $fromDate);
		$transactionList	 = AccountTransDetails::transactionList($agentId, $fromDate, $toDate);
		$lastPaymentReceived = AccountTransDetails::getLastPaymentReceived($agentId, 3);
		//        /* @var $html2pdf \Spipu\Html2Pdf\Html2Pdf */

		$data = [
			'agentAmount'		 => $agentAmount,
			'openingAmount'		 => $openingAmount,
			'agentList'			 => $transactionList,
			'fromDate'			 => $fromDate,
			'toDate'			 => $toDate,
			'lastTransaction'	 => $lastPaymentReceived
		];

		return $data;
	}

	public static function getLastPaymentReceived($agentIds, $actType)
	{
		$sql		 = "SELECT adt.adt_amount paymentReceived,act.act_date ReceivedDate 
				FROM account_transactions act
				INNER JOIN account_trans_details adt 
				ON act.act_id = adt.adt_trans_id 
					AND adt.adt_ledger_id IN(23,29,30)
				WHERE `adt_amount` > 0 
				AND act_type='$actType' AND act_ref_id IN({$agentIds}) 
				ORDER BY `act_date` DESC";
		$recordSet	 = DBUtil::queryRow($sql);
		return $recordSet;
	}

	public static function getLastPaymentSent($refIds, $refType)
	{
		$sql		 = "SELECT
					adt.adt_amount paymentSent, act.act_date sentDate
				FROM account_transactions act
				INNER JOIN account_trans_details adt ON
					act.act_id = adt.adt_trans_id AND adt.adt_ledger_id IN (23, 29, 30)
				WHERE `adt_amount` < 0 
				AND act_type = '$refType' AND act_ref_id IN({$refIds}) 
				ORDER BY `act_date` DESC";
		$recordSet	 = DBUtil::queryRow($sql);
		return $recordSet;
	}

	public static function getVendorAmountforLastNDays($vendorId, $days, $percentage)
	{
		// Getting Merged VendorIds
		$vndIds = Vendors::getVndIdsByRefCode($vendorId);
		if ($vndIds == null || $vndIds == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}

		$sql = "SELECT IFNULL(round(sum(a.vendor_amount) * $percentage),0) vndAmount FROM 
			    (SELECT  booking_cab.bcb_vendor_amount  vendor_amount,booking_cab.bcb_vendor_id
     FROM booking_cab
     INNER JOIN booking
        ON     booking.bkg_bcb_id = booking_cab.bcb_id
           AND booking.bkg_status IN (6,7)
     INNER JOIN booking_invoice
        ON     booking_invoice.biv_bkg_id = booking.bkg_id
		   WHERE   booking.bkg_pickup_date BETWEEN DATE_SUB(NOW(),INTERVAL $days DAY )  AND NOW()
      AND booking_cab.bcb_vendor_id IS NOT NULL
      AND booking_cab.bcb_vendor_id IN ({$vndIds}) 
		   GROUP BY booking_cab.bcb_id) a";

		$recordSet = DBUtil::queryScalar($sql);
		return $recordSet;
	}

	public static function getNextNdaysGozoAmount($vendorId, $days)
	{
		// Getting Merged VendorIds
		$vndIds = Vendors::getVndIdsByRefCode($vendorId);
		if ($vndIds == null || $vndIds == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}

		$hours	 = $days * 24;
		$sql	 = "SELECT IFNULL(SUM(vendorDue),0) as totalDue
			FROM (
			SELECT bcb_id, count(*) as cnt, 
			(SUM(bkg_total_amount) - SUM(bkg_advance_amount-bkg_refund_amount+bkg_credits_used) - bcb_vendor_amount) as vendorDue,
			MIN(bkg_pickup_date) as pickupDate    
		 FROM booking_cab
			INNER JOIN booking ON booking_cab.bcb_id=booking.bkg_bcb_id AND bkg_status IN (3,5)  
				AND bkg_pickup_date>=NOW() AND bkg_reconfirm_flag=1
			INNER JOIN booking_invoice ON booking.bkg_id=booking_invoice.biv_bkg_id AND bcb_vendor_id IN ({$vndIds}) 
			LEFT JOIN agents ON bkg_agent_id=agents.agt_id 
		GROUP BY bcb_id HAVING DATE(pickupDate)<=DATE(DATE_ADD(NOW(), INTERVAL $hours HOUR))) a";

		$recordSet = DBUtil::queryScalar($sql);
		return $recordSet;
	}

	public function getAllowedRefundAmt($apgId)
	{
		$remainingRefund = DBUtil::queryScalar("SELECT SUM(adt_amount) tot
			FROM   account_transactions LEFT JOIN account_trans_details ON act_id = adt_trans_id
			WHERE  act_ref_id = (SELECT adt_trans_ref_id
            FROM   account_trans_details
            WHERE  adt_trans_id = (SELECT adt_trans_id
            FROM   account_trans_details
            WHERE  adt_id = $apgId) AND adt_ledger_id = 13) AND adt_ledger_id = 47");
		return $remainingRefund;
	}

	public function checkCreditStat($agentId, $corpCredit)
	{
		$isRechargeAccount	 = false;
		$tot				 = AccountTransDetails::accountTotalSummary($agentId);
		$agtModel			 = Agents::model()->findByPk($agentId);
		$totAgentCredit		 = $tot['totAmount'];
		$totAgentCredit		 = $totAgentCredit + $corpCredit;
		if ($totAgentCredit > $agtModel->agt_effective_credit_limit)
		{
			$isRechargeAccount = true;
		}
		return $isRechargeAccount;
	}

	public function getRunningBalByVndId($vndid, $tdsVndBal, $vendorBal)
	{
		$sqlDate = "SELECT DATE_ADD(IFNULL(MAX(date),'2019-03-31'), INTERVAL 1 DAY) as startDate FROM (
					SELECT *, @runningTds := @runningTds + vbl_tds_paid as tdsPaid,
					@runningTrip := @runningTrip + vbl_tds_amt as tdsApplicable
					FROM (
					SELECT DATE(act_date) as date, 
					sum(if(atd1.adt_ledger_id = 22, atd1.adt_amount, 0)) AS vbl_trip_amt,
					sum(if(atd1.adt_ledger_id = 22, atd1.adt_amount* 0.01, 0)) AS vbl_tds_amt,
					sum(if(atd1.adt_ledger_id IN(37,55), atd1.adt_amount, 0)) AS vbl_tds_paid
					FROM  account_transactions act
					INNER JOIN account_trans_details atd ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 14 AND atd.adt_trans_ref_id =$vndid
					AND adt_status = 1 AND adt_active = 1 AND act_status = 1 AND act_active = 1 AND (act_date) >= '2019-04-01 00:00:00' AND act_date <= NOW()
					INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id AND atd1.adt_ledger_id IN(22, 37, 55)
					GROUP BY date ORDER BY date) a
					JOIN (SELECT @runningTds:=0, @runningTrip:=0 from dual) b
					WHERE 1 ) c WHERE (ROUND(tdsApplicable)+tdsPaid)<=0";
		$rowDate = DBUtil::queryRow($sqlDate);

		$sqlBalance	 = "SELECT * FROM (
			SELECT *, @runningTds := @runningTds + vbl_tds_paid as tdsPaid,
			@runningTrip := @runningTrip + vbl_tds_amt as tdsApplicable
			FROM (
			SELECT DATE(act_date) as date, 
			sum(if(atd1.adt_ledger_id = 22, atd1.adt_amount, 0)) AS vbl_trip_amt,
			sum(if(atd1.adt_ledger_id = 22, atd1.adt_amount* 0.01, 0)) AS vbl_tds_amt,
			sum(if(atd1.adt_ledger_id IN(37,55), -1 * atd1.adt_amount, 0)) AS vbl_tds_paid
			FROM  account_transactions act
			INNER JOIN account_trans_details atd ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 14 AND atd.adt_trans_ref_id =$vndid
			AND adt_status = 1 AND adt_active = 1 AND act_status = 1 AND act_active = 1 AND (act_date) >= '2019-04-01 00:00:00' AND act_date <= NOW()
			INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id AND atd1.adt_ledger_id IN(22, 37, 55)
			GROUP BY date ORDER BY date) a
			JOIN (SELECT @runningTds:=0, @runningTrip:=0 from dual) b 
			WHERE 1 ) c WHERE (tdsApplicable-tdsPaid)<$vendorBal ORDER BY date DESC LIMIT 0,1";
		$rowBalance	 = DBUtil::queryRow($sqlBalance);

		$data				 = array();
		$data['tdsDate']	 = $rowDate;
		$data['tdsDetails']	 = $rowBalance;
		return $data;
	}

	public static function saveAll($transDetails, $actModel)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		foreach ($transDetails as $transDetail)
		{
			$transDetail->adt_trans_id = $actModel->act_id;
			if (!$transDetail->save())
			{
				throw new Exception(json_encode($transDetail->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			continue;
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	public static function walletTransactionList($agtId, $transDate1 = '', $transDate2 = '', $ledgers = '')
	{
		$actOpenDate = "";
		$condition	 = "0";
		$condition1	 = "";
		$actOpenDate = "";

		if ($transDate1 != '' && $transDate2 != '')
		{
			$fromTime		 = '00:00:00';
			$toTime			 = '23:59:59';
			$fromDateTime	 = $transDate1 . ' ' . $fromTime;
			$toDateTime		 = $transDate2 . ' ' . $toTime;
		}
		$dateRange = '';
		if (($transDate1 != '' && $transDate1 != '1970-01-01') && ($transDate2 != '' && $transDate2 != '1970-01-01'))
		{

			$dateRange	 .= " AND act_date>='$fromDateTime' AND act_date<='$toDateTime'";
			$actOpenDate = " act_date<'$fromDateTime' AND ";
			if ($ledgers != '')
			{
				$ledgersID = "AND account_ledger.ledgerId IN($ledgers)";
			}
		}


		$sql		 = "SELECT *, @runningBal1 := adt_amount +@runningBal1   as runningBalance
		FROM 
		    (
		SELECT act_date,act_created,act_id, atd.adt_id, atd.adt_ledger_id, act.act_remarks, 
		agents.agt_company, agents.agt_email, agents.agt_phone, 
		booking.bkg_booking_id,booking.bkg_agent_ref_code,
		atd.adt_remarks agt_trans_remarks,
		bcb_cab_number,bcb_driver_id,booking_user.bkg_user_fname fname,booking_user.bkg_user_lname lname,
		concat(c1.cty_name,' - ',c2.cty_name) bookingInfo,
		GROUP_CONCAT(atd1.adt_ledger_id) as ledgerIds,
		GROUP_CONCAT(
		    CASE atd1.adt_ledger_id
		    WHEN 13 THEN CONCAT(account_ledger.ledgerName, ':(',booking.bkg_booking_id ,')', IFNULL(booking.bkg_agent_ref_code,''))
		    WHEN 35 THEN CONCAT(account_ledger.ledgerName, ':(',booking.bkg_booking_id ,')', IFNULL(booking.bkg_agent_ref_code,''))
		    ELSE account_ledger.ledgerName
		    END
		SEPARATOR ', ') as ledgerNames, (-1*atd.adt_amount) adt_amount,account_ledger.ledgerName entityType,
		booking.bkg_pickup_date,
		booking_invoice.bkg_advance_amount,
		CONCAT(admins.adm_fname,' ',admins.adm_lname) adminName
		FROM 
		    account_transactions act
		INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id
		AND atd.adt_ledger_id=49 AND atd.adt_trans_ref_id=$agtId AND atd.adt_active = 1 $dateRange AND atd.adt_status = 1
		INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id
		AND ((abs(atd.adt_amount)<>atd.adt_amount AND atd1.adt_amount>0) OR (abs(atd.adt_amount)=atd.adt_amount AND atd1.adt_amount<0))
		INNER JOIN account_ledger ON atd1.adt_ledger_id=account_ledger.ledgerId $ledgersID
		INNER JOIN agents ON atd.adt_trans_ref_id=agt_id
		LEFT JOIN booking ON atd1.adt_ledger_id IN(35,13) AND atd1.adt_trans_ref_id=booking.bkg_id
		LEFT JOIN booking_invoice ON booking.bkg_id=booking_invoice.biv_bkg_id
		LEFT JOIN admins ON admins.adm_id = act.act_user_id AND act.act_user_type = 4
		LEFT JOIN cities c1 ON booking.bkg_from_city_id = c1.cty_id
		LEFT JOIN cities c2 ON booking.bkg_to_city_id = c2.cty_id
		LEFT JOIN booking_cab ON booking.bkg_bcb_id=booking_cab.bcb_id
		LEFT JOIN booking_user ON booking.bkg_id=booking_user.bui_id
		WHERE act_active = 1 AND atd1.adt_active=1 AND atd1.adt_status=1 
		GROUP BY atd.adt_id ORDER BY act.act_date ASC 
		) a
		JOIN 
		(
		SELECT @runningBal1 := ifNULL(SUM(-1*adt_amount),0) openBalance FROM account_trans_details
		INNER JOIN account_transactions ON act_id=adt_trans_id
		WHERE $actOpenDate
		adt_ledger_id=49 AND act_active = 1 AND adt_active=1 AND adt_status=1 AND
		adt_trans_ref_id=$agtId
		) r";
		$recordSet	 = DBUtil::queryAll($sql);
		return $recordSet;
	}

	public static function getPartnerWalletData($agentId, $fromDate, $toDate)
	{
		$agentAmount		 = AccountTransDetails::model()->accountTotalSummary($agentId, '', $toDate);
		$openingAmount		 = AccountTransDetails::model()->accountTotalSummary($agentId, '', '', $fromDate);
		$transactionList	 = AccountTransDetails::walletTransactionList($agentId, $fromDate, $toDate);
		$lastPaymentReceived = AccountTransDetails::getLastPaymentReceived($agentId, 3);
		//        /* @var $html2pdf \Spipu\Html2Pdf\Html2Pdf */

		$data = [
			'agentAmount'		 => $agentAmount,
			'openingAmount'		 => $openingAmount,
			'agentList'			 => $transactionList,
			'fromDate'			 => $fromDate,
			'toDate'			 => $toDate,
			'lastTransaction'	 => $lastPaymentReceived
		];

		return $data;
	}

	public static function getOpeningBalance($relVndIds, $actDate)
	{
		$sql		 = "SELECT ifNULL(SUM(adt_amount), 0) openBalance
						FROM account_trans_details
						INNER JOIN account_transactions ON act_id = adt_trans_id
						WHERE     1
						AND (act_date) < '$actDate 00:00:00'
						AND act_date >= '2021-04-01 00:00:00' 
						AND adt_ledger_id = 14
						AND adt_status = 1
						AND adt_active = 1
						AND act_status = 1
						AND act_active = 1
						AND adt_trans_ref_id IN ({$relVndIds})";
		$recordSet	 = DBUtil::queryScalar($sql, DBUtil::SDB());
		return $recordSet != null ? $recordSet : 0;
	}

	public function getbyVendorId($vndId)
	{
		$vndIds	 = Vendors::getRelatedIds($vndId);
		$sql	 = "SELECT act.*, atd.adt_addt_params
			FROM account_trans_details atd 
			INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id 
				AND act.act_active=1 AND act.act_status=1 AND atd.adt_active=1 AND atd.adt_status=1 
			INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id 
				AND atd1.adt_ledger_id = 28 AND atd1.adt_type = 1 
				AND atd1.adt_active=1 AND atd1.adt_status=1 AND ((atd.adt_amount>0 
				AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
			WHERE act.act_active=1 AND atd.adt_ledger_id = 14 AND atd.adt_type = 2 
			AND atd.adt_trans_ref_id IN ({$vndIds}) ";
		$count	 = DBUtil::queryScalar("SELECT COUNT(1) FROM ($sql) abc");

		$dataprovider = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['act_amount'],
				'defaultOrder'	 => 'act_date DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	public function walletRefund($amount)
	{

		$actId				 = $this->adt_trans_id;
		$this->adt_amount	 = $amount;

		$actModel	 = AccountTransactions::model()->findByPk($actId);
		$bkgmodel	 = Booking::model()->findByPk($actModel->act_ref_id);
		$refundTrans = AccountTransDetails::model()->refundTransaction($this, $bkgmodel);
		return $refundTrans;
	}

	public static function getWalletEntryUsedForbooking($bkgid)
	{
		$param	 = ['bkgid' => $bkgid];
		$sql	 = "SELECT  *
				FROM account_trans_details adt
				INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND  adt.adt_ledger_id IN (47,1) 
				INNER JOIN booking book ON book.bkg_id = act.act_ref_id AND book.bkg_status IN(2, 3, 5, 6, 7, 9, 10) AND act.act_active = 1
				 
				where book.bkg_id=:bkgid AND adt.adt_active	=1";
		$res	 = DBUtil::query($sql, null, $param);
		return $res;
	}

	public static function getGozoCoinEntryUsedForbooking($bkgid)
	{
		$param	 = ['bkgid' => $bkgid];
		$sql	 = "SELECT  *
				FROM account_trans_details adt
				INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND  adt.adt_ledger_id IN (36) 
				INNER JOIN booking book ON book.bkg_id = act.act_ref_id AND book.bkg_status IN(2, 3, 5, 6, 7, 9, 10) AND act.act_active = 1
				 
				where book.bkg_id=:bkgid AND adt.adt_active	=1";
		$res	 = DBUtil::query($sql, null, $param);
		return $res;
	}

	public static function getWalletList()
	{
		$sql = "SELECT   concat(usr.usr_name,' ',usr.usr_lname) user_name, 
				adt.adt_trans_ref_id, -sum(adt.adt_amount) wallet_balance
			FROM     account_trans_details adt 
			JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND act.act_active = 1 
				AND act.act_status = 1 AND adt.adt_active = 1 AND adt.adt_status = 1
			JOIN users usr ON usr.user_id = adt.adt_trans_ref_id
			WHERE adt.adt_trans_ref_id IS NOT NULL  AND adt.adt_ledger_id = 47
			GROUP BY adt.adt_trans_ref_id";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc");
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['defaultOrder' => 'user_name'],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public static function getTransactionbyBookingid($refid, $paymentOnly = false)
	{

		$params		 = ['bkgid' => $refid];
		$condition	 = ' ';
		if ($paymentOnly)
		{
			$condition = " AND atd1.adt_amount < 0";
		}
		$ledgerList	 = implode(',', Accounting::getBookingPaymentSource(false));
		$sql		 = "
SELECT   atd.adt_ledger_id , atd1.adt_ledger_id adt1_ledger_id,  atd1.adt_trans_ref_id, atd1.adt_amount  ,if(atd.adt_ledger_id = 47,'1','0') isWallet, atd.*
FROM account_trans_details atd 
INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id AND act.act_active=1 AND atd.adt_active=1  
INNER JOIN account_trans_details atd1 
	ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id IN (13) 
		AND atd1.adt_trans_ref_id =:bkgid
WHERE  atd.adt_ledger_id IN($ledgerList) $condition";
		return DBUtil::queryAll($sql, null, $params);
	}

	public static function getBalancebyBookingid($refid, $onlyBalance = false)
	{
		$params		 = ['bkgid' => $refid, 'refType' => Accounting::LI_BOOKING];
		$ledgerList	 = implode(',', Accounting::getBookingPaymentSource(false));
		$sql		 = "
			SELECT     atd1.adt_trans_ref_id bkgid,sum(-atd1.adt_amount  ) balance,sum(if(atd1.adt_amount<0,-atd1.adt_amount,0)) advance,sum(if(atd1.adt_amount>0,atd1.adt_amount,0)) refund
			FROM account_trans_details atd 
			INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id AND act.act_active=1 AND atd.adt_active=1  
			INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id 
				AND atd1.adt_ledger_id =:refType 
				AND atd1.adt_trans_ref_id =:bkgid  
				AND atd1.adt_active=1  
			WHERE  atd.adt_ledger_id IN($ledgerList) 
			GROUP BY atd1.adt_trans_ref_id";
		$res		 = DBUtil::queryRow($sql, null, $params);
		if ($onlyBalance)
		{
			return $res['balance'];
		}
		return $res;
	}

	public static function getWalletBalancebyUserid($refid)
	{
		$params = ['userid' => $refid, 'refType' => Accounting::LI_WALLET];

		$sql = "
SELECT     sum(adt.adt_amount )   
FROM     account_trans_details adt 
JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND act.act_active = 1 AND act.act_status = 1 AND adt.adt_active = 1 AND adt.adt_status = 1
JOIN users usr ON usr.user_id = adt.adt_trans_ref_id
WHERE    adt.adt_trans_ref_id IS NOT NULL  AND adt.adt_ledger_id =:refType AND adt.adt_trans_ref_id =:userid
GROUP BY adt.adt_trans_ref_id
 ";
		$res = DBUtil::queryScalar($sql, null, $params);

		return -1 * $res;
	}

	public static function getWalletTransactionByBooking($bkgid)
	{
		$params	 = ['bkgid' => $bkgid];
		$sql	 = "SELECT atd1.adt_ledger_id,atd.adt_ledger_id ,  
		atd1.adt_trans_ref_id,
		SUM(if(atd1.adt_amount>0,atd1.adt_amount,0  )) refundedToWallet ,
		SUM(if(atd1.adt_amount<0,atd1.adt_amount,0  )) paidThroughWallet
			FROM account_trans_details atd 
		INNER JOIN account_transactions act 
			ON atd.adt_trans_id=act.act_id 
			AND act.act_active=1 AND atd.adt_active=1  
		INNER JOIN account_trans_details atd1 
			ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1 
			AND atd1.adt_ledger_id IN (13) AND atd1.adt_trans_ref_id IN (:bkgid	)
		WHERE atd.adt_ledger_id = 47   
		GROUP BY atd1.adt_trans_ref_id";
		$res	 = DBUtil::queryRow($sql, null, $params);
		return $res;
	}

	public static function getBankTransactionByBooking($bkgid)
	{
		$params	 = ['bkgid' => $bkgid];
		$sql	 = "SELECT atd1.adt_ledger_id,atd.adt_ledger_id ,  
		atd1.adt_trans_ref_id,
		SUM(if(atd1.adt_amount>0,atd1.adt_amount,0  )) refundedToBank ,
		SUM(if(atd1.adt_amount<0,atd1.adt_amount,0  )) paidThroughBank
			FROM account_trans_details atd 
		INNER JOIN account_transactions act 
			ON atd.adt_trans_id=act.act_id 
			AND act.act_active=1 AND atd.adt_active=1  
		INNER JOIN account_trans_details atd1 
			ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1 
			AND atd1.adt_ledger_id IN (13) AND atd1.adt_trans_ref_id IN (:bkgid	)
		WHERE atd.adt_ledger_id IN (29,30,23)  
		GROUP BY atd1.adt_trans_ref_id";
		$res	 = DBUtil::queryRow($sql, null, $params);
		return $res;
	}

	public function getPenaltyReport($command = false, $transDate1 = '', $transDate2 = '', $bookingId = '', $vendorId = '', $removalDate1 = '', $removalDate2 = '', $penalty_status = '')
	{
		if ($transDate1 != '' && $transDate2 != '')
		{
			$fromTime		 = '00:00:00';
			$toTime			 = '23:59:59';
			$fromDateTime	 = $transDate1 . ' ' . $fromTime;
			$toDateTime		 = $transDate2 . ' ' . $toTime;
		}
		if ($removalDate1 != '' && $removalDate2 != '')
		{
			$fromRevTime	 = '00:00:00';
			$toRevTime		 = '23:59:59';
			$fromRevDateTime = $removalDate1 . ' ' . $fromRevTime;
			$toRevDateTime	 = $removalDate2 . ' ' . $toRevTime;
		}
		$dateRange		 = '';
		$refId			 = '';
		$vndId			 = '';
		$penaltyStatus	 = '';
		if (($transDate1 != '' && $transDate1 != '1970-01-01') && ($transDate2 != '' && $transDate2 != '1970-01-01'))
		{
			$dateRange .= " AND act.act_date>='$fromDateTime' AND act.act_date<='$toDateTime'";
		}
		if (($removalDate1 != '' && $removalDate1 != '1970-01-01') && ($removalDate2 != '' && $removalDate2 != '1970-01-01'))
		{
			$dateRange .= " AND adt1.adt_modified>='$fromRevDateTime' AND adt1.adt_modified<='$toRevDateTime' AND act.act_active = 0";
		}
		if ($penalty_status != '' && $penalty_status == 1)
		{
			$penaltyStatus .= " AND act.act_active = 1 AND adt.adt_amount > 0";
		}
		if ($penalty_status != '' && $penalty_status == 2)
		{
			$penaltyStatus .= " AND (act.act_active = 0 OR (act.act_active = 1 AND adt.adt_amount < 0))";
		}
		if ($bookingId != '')
		{
			$refId .= " AND adt1.adt_trans_ref_id='$bookingId'";
		}
		if ($vendorId != '')
		{
			$vndId .= " AND adt.adt_trans_ref_id='$vendorId'";
		}
		if ($command == true)
		{
			$orderBy .= " order by act.act_created desc";
		}
		$sql = "SELECT act.act_id,act.act_user_type,
                blg_desc,
				act.act_date,
                act.act_created,
                adt.adt_amount ledgerAmt,
                adt1.adt_amount ledgerAmt1,
                act.act_remarks,
                adt1.adt_trans_ref_id,
				'' AS bkg_agent_ref_code,
                act.act_active,
                act.act_user_id,
                adt1.adt_modified,
               adm.adm_fname, 
			   adm.adm_lname,
				adt.adt_addt_params,
                adt.adt_remarks,
                adm.adm_id,
				adm1.adm_id adm_id1,
				adm1.adm_fname adm_fname1, 
			    adm1.adm_lname adm_lname1,
				vnd.vnd_code,
				adt1.adt_type
                FROM account_trans_details adt
                INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id =14 AND adt.adt_type =2 $vndId
                INNER JOIN account_trans_details adt1 ON act.act_id = adt1.adt_trans_id AND adt1.adt_ledger_id =28 $refId
				INNER JOIN vendors vnd ON vnd.vnd_id = adt.adt_trans_ref_id
                LEFT JOIN booking_log  ON blg_booking_id=adt1.adt_trans_ref_id AND blg_event_id = 105 
				AND JSON_VALUE(`blg_additional_params`,'$.act_id')= act.act_id AND JSON_VALUE(`blg_additional_params`,'$.act_id') IS NOT  NULL	
				LEFT JOIN admins adm ON adm.adm_id = booking_log.blg_user_id AND blg_event_id = 105
				LEFT JOIN admins adm1 ON adm1.adm_id = act.act_user_id
				WHERE act.act_active IN(0,1) AND adt.adt_active IN(0,1) $dateRange $penaltyStatus $orderBy 
                ";
		if ($command == false)
		{
			$defaultOrder	 = 'act.act_created desc';
			$pageSize		 = 100;
			$count			 = Yii::app()->db1->createCommand("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => Yii::app()->db1,
				'totalItemCount' => $count,
				'sort'			 =>
				['attributes'	 =>
					['act.act_created', 'act.act_date',
						'adt.adt_amount',
						'adt1.adt_amount',
						'act.act_remarks',
						'adt1.adt_trans_ref_id',
						'act.act_active',
						'act.act_user_id',
						'adt1.adt_modified',
						'admins.adm_fname',
						'admins.adm_lname'],
					'defaultOrder'	 => $defaultOrder,
				],
				//'keyField'		 => 'vnd_id',
				'pagination'	 => ['pageSize' => $pageSize],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
	}

	public static function getAccountTLeadgerList($model, $type = '')
	{
		$where		 = "";
		$subWhere	 = "";
		$date1		 = $model->trans_create_date1;
		$date2		 = $model->trans_create_date2;
		if ($date1 != '' && $date2 != '')
		{
			$currentdate = "'$date1 00:00:00'";
			$condition	 = " AND act_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59' ";
		}
		else
		{
			$currentdate = "CONCAT(DATE(DATE_SUB(NOW(), INTERVAL 30 DAY)),' 00:00:00')";
			$condition	 = " AND (act_date BETWEEN $currentdate AND NOW()) ";
		}

		if ($model->apg_ledger_type_ids != "")
		{
			$conledger2 = " AND atd1.adt_ledger_id IN ($model->apg_ledger_type_ids)";
		}


		$sql = "SELECT act_date, al.ledgerName, al1.ledgerId, booking.bkg_booking_id,booking.bkg_id,b1.bkg_id as bkg_id2, b1.bkg_booking_id as bkg_booking_id2, al1.ledgerName as ledgerName2, atd.adt_trans_ref_id as vendorId,atd1.adt_trans_ref_id as vendorId2, v.vnd_name as vendorName,v2.vnd_code as vnd_code2 ,v2.vnd_name as vendorName2,v.vnd_code,
				a.agt_company as agent, a1.agt_company as agent2,atd1.adt_trans_ref_id as adtTripId,
				(IF(abs(atd.adt_amount)<=abs(atd1.adt_amount), atd.adt_amount, atd1.adt_amount*-1)) as amount, act_remarks
				FROM account_trans_details atd
				INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 
				AND atd.adt_ledger_id IN ($model->apg_ledger_type_id) AND atd.adt_status=1
				INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id  AND atd1.adt_active=1 AND atd1.adt_status=1 AND atd1.adt_active=1 $conledger2
				AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0))
				INNER JOIN account_ledger al ON al.ledgerId=atd.adt_ledger_id 
				INNER JOIN account_ledger al1 ON al1.ledgerId=atd1.adt_ledger_id
				LEFT JOIN booking On bkg_id=atd.adt_trans_ref_id AND atd.adt_type=1
				LEFT JOIN booking b1 On b1.bkg_id=atd1.adt_trans_ref_id AND atd1.adt_type=1
				LEFT JOIN vendors v ON v.vnd_id= atd.adt_trans_ref_id AND atd.adt_type=2
				LEFT JOIN vendors v1 ON v.vnd_ref_code = v1.vnd_id 
				LEFT JOIN vendors v2 ON v2.vnd_id= atd1.adt_trans_ref_id AND atd1.adt_type=2
				LEFT JOIN agents a ON a.agt_id = atd.adt_trans_ref_id AND atd.adt_type=3
				LEFT JOIN agents a1 ON a1.agt_id = atd1.adt_trans_ref_id AND atd1.adt_type=3
				WHERE 1  $condition";
		if ($type == 'Command')
		{
			$count = DBUtil::queryScalar("SELECT COUNT(1) FROM ($sql) abc", DBUtil::SDB());

			$pageSize		 = 500;
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes' => ['ledgerName'], 'defaultOrder' => 'act_date DESC'],
				'pagination'	 => ['pageSize' => $pageSize],
			]);

			return $dataprovider;
		}
		else
		{
			$recordset = DBUtil::query($sql, DBUtil::SDB());
			return $recordset;
		}
	}

	public static function refundUserTransaction($model, $userid)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			$ptpStr	 = PaymentType::model()->getList()[$model->adt_ledger_id];
			$ptpId	 = PaymentType::model()->ptpList($model->adt_ledger_id);

			$paymentGateway						 = new PaymentGateway();
			$paymentGateway->apg_booking_id		 = null;
			$paymentGateway->apg_acc_trans_type	 = Accounting::AT_ONLINEPAYMENT;
			$paymentGateway->apg_trans_ref_id	 = $userid;
			$paymentGateway->apg_ledger_id		 = $model->adt_ledger_id;
			$paymentGateway->apg_ptp_id			 = $ptpId;
			$paymentGateway->apg_amount			 = -1 * $model->adt_amount;
			$paymentGateway->apg_mode			 = 1;
			$paymentGateway->apg_remarks		 = $model->adt_remarks . ' Reference.Id: ' . $model->refrence_id;
			$paymentGateway->apg_ref_id			 = '';
			$paymentGateway->apg_user_type		 = UserInfo::TYPE_ADMIN;
			$paymentGateway->apg_user_id		 = Yii::app()->user->getId();
			$paymentGateway->apg_status			 = 1;
			$paymentGateway->apg_date			 = new CDbExpression('NOW()');
			$paymentGateway->apg_ref_id			 = 0;
			$pgModel							 = $paymentGateway->payment($model->adt_ledger_id);
			$pgModel->refresh();

			$bankRefId	 = $paymentGateway->apg_id;
			$refType	 = Accounting::AT_ONLINEPAYMENT;
			$remarks	 = $paymentGateway->apg_remarks;
			$ledgerType	 = Accounting::LI_WALLET;
			$accType	 = Accounting::AT_USER;
			$date		 = new CDbExpression('NOW()');
			AccountTransactions::model()->addAmountGozoReceiver($paymentGateway, $userid, $bankRefId, $remarks, $refType, $accType, $ledgerType, $date, null);
			$success	 = true;
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$success = false;
		}
		return $success;
	}

	public static function processAllTransactionData($arrFilters)
	{
//		echo "<pre>";
//		print_r($arrFilters);

		$fromDate		 = trim($arrFilters['from_date']);
		$toDate			 = trim($arrFilters['to_date']);
		$fromLedgerId	 = trim($arrFilters['from_ledger_id']);
		$toLedgerId		 = trim($arrFilters['to_ledger_id']);
		$grpByType		 = trim($arrFilters['groupby_type']);
		#$grpByPeriod	 = trim($arrFilters['groupby_period']);

		if ($toLedgerId != '')
		{
			$whereToLedger = " AND atd1.adt_ledger_id IN ({$toLedgerId}) ";
		}

		$transactionTable	 = "AllTransactions_" . rand();
		#$transactionTable	 = "AllTransactions_1637943237";
		#$sqlTransactions	 = " DROP TABLE IF EXISTS {$transactionTable}; CREATE TABLE {$transactionTable} ";
		$sqlTransactions	 .= "SELECT DATE_FORMAT(act.act_date, '%Y-%m-%d') date, atd.adt_ledger_id from_ledger_id, 
					atd1.adt_ledger_id to_ledger_id, atd.adt_trans_ref_id, atd.adt_amount balance 
				FROM account_trans_details atd 
				INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 
					AND atd.adt_active=1 AND atd.adt_status=1 AND atd.adt_ledger_id = {$fromLedgerId} 
				INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1 AND atd1.adt_status=1 
					AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
					{$whereToLedger} 
				WHERE 1 AND (act_date BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59') ";

		#DBUtil::execute($sqlTransactions);
		DBUtil::dropTempTable($transactionTable);
		DBUtil::createTempTable($transactionTable, $sqlTransactions);

		// Entity
		if ($grpByType != '')
		{
			$tempTableEntityData = "ALedgerEntityData_" . rand();
			#$tempTableEntityData = "ALedgerEntityData_505570789";
			#$sqlEntity			 = "DROP TABLE IF EXISTS {$tempTableEntityData}; CREATE TABLE {$tempTableEntityData} ";

			if ($grpByType == 'partner')
			{
				$sqlEntity .= "SELECT IFNULL(agt_company, CONCAT(agt_fname, ' ', agt_lname)) as entityName, a.* 
					FROM {$transactionTable} a 
					INNER JOIN agents agt ON a.adt_trans_ref_id = agt.agt_id";
			}
			elseif ($grpByType == 'vendor')
			{
				$sqlEntity .= "SELECT vnd.vnd_name as entityName, a.* 
					FROM vendors vnd 
					INNER JOIN {$transactionTable} a ON a.adt_trans_ref_id = vnd.vnd_id ";
			}

			#DBUtil::execute($sqlEntity);
			DBUtil::dropTempTable($tempTableEntityData);
			DBUtil::createTempTable($tempTableEntityData, $sqlEntity);

			$transactionTable = $tempTableEntityData;
		}

		$sqlData = "SELECT a.*, a1.ledgerName fromLedgerName, a2.ledgerName toLedgerName 
			FROM {$transactionTable} a 
			LEFT JOIN account_ledger a1 ON a1.ledgerId = a.from_ledger_id 
			LEFT JOIN account_ledger a2 ON a2.ledgerId = a.to_ledger_id";

//		echo "<br><br>sqlTransactions == " . $sqlTransactions;
//		echo "<br><br>sqlEntity == " . $sqlEntity;
//		echo "<br><br>sqlData == " . $sqlData;
//		die(); 

		return DBUtil::query($sqlData, DBUtil::SDB());
	}

	public static function processLedgerData($arrFilters)
	{
		#echo "<pre>";
		#print_r($arrFilters);

		$fromDate		 = trim($arrFilters['from_date']);
		$toDate			 = trim($arrFilters['to_date']);
		$fromLedgerId	 = trim($arrFilters['from_ledger_id']);
		$toLedgerId		 = trim($arrFilters['to_ledger_id']);
		$grpByType		 = trim($arrFilters['groupby_type']);
		$grpByPeriod	 = trim($arrFilters['groupby_period']);

		if ($grpByPeriod == 'all')
		{
			return self::processAllTransactionData($arrFilters);
		}

		$periodFieldName = ($grpByPeriod != '' ? $grpByPeriod : 'month');
		$periodFormat	 = (($grpByPeriod == '' || $grpByPeriod == 'month') ? "'%Y-%m'" : "'%Y-%m-%d'");

		$whereDate			 = " AND (act_date BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59') ";
		$whereOpeningDate	 = " AND act_date < '{$fromDate} 00:00:00' ";
		$whereFromLedger	 = " AND atd.adt_ledger_id = {$fromLedgerId} ";

		$groupByType = " GROUP BY atd.adt_ledger_id, atd1.adt_ledger_id ";

		if ($toLedgerId != '')
		{
			$whereToLedger = " AND atd1.adt_ledger_id IN ({$toLedgerId}) ";
		}
		if ($grpByType != '')
		{
			$groupByType .= ", atd.adt_trans_ref_id ";
		}

		// Opening ## 14: Operator/ Vendor, 15: Partner, 47: Wallet, 3: Advance Payment
		#if (($grpByType != '' && $fromLedgerId != 14) || ($fromLedgerId == 14 && $grpByPeriod ==''))
		if (in_array($fromLedgerId, array(3, 14, 15, 47)) && $grpByPeriod == '')
		{
			$openingTable	 = "LedgerOpeningBalance_" . rand();
			#$openingTable	 = "ALedgerOpeningBalance_852730611";
			#$sqlOpening	 = "DROP TABLE IF EXISTS {$openingTable}; CREATE TABLE {$openingTable} ";
			$sqlOpening		 .= "SELECT atd.adt_ledger_id from_ledger_id, atd1.adt_ledger_id to_ledger_id, atd.adt_trans_ref_id, 
										SUM(IF(abs(atd1.adt_amount)<abs(atd.adt_amount), atd1.adt_amount, atd.adt_amount*-1)) AS opening 
									FROM account_trans_details atd 
									INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 
										{$whereFromLedger} 
									INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1 {$whereToLedger} 
										AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
										{$whereOpeningDate} 
										{$groupByType} 
										HAVING opening<>0";
			#DBUtil::execute($sqlOpening);
			DBUtil::dropTempTable($openingTable);
			DBUtil::createTempTable($openingTable, $sqlOpening);
		}

		if ($grpByPeriod != '')
		{
			$groupByType .= ", {$grpByPeriod} ";
		}

		// Transaction Debit/ Credit
		$transactionTable	 = "LedgerBalance_" . rand();
		#$transactionTable	 = "ALedgerBalance_1637943237";
		#$sqlTrans			 = " DROP TABLE IF EXISTS {$transactionTable}; CREATE TABLE {$transactionTable} ";
		$sqlTrans			 .= "SELECT atd.adt_ledger_id from_ledger_id, atd1.adt_ledger_id to_ledger_id, atd.adt_trans_ref_id, 
					DATE_FORMAT(act.act_date, {$periodFormat}) {$periodFieldName}, 
					SUM(IF(atd.adt_amount > 0, IF(abs(atd1.adt_amount)<abs(atd.adt_amount), atd1.adt_amount*-1, atd.adt_amount), 0)) AS debit, 
					SUM(IF(atd.adt_amount < 0, IF(abs(atd1.adt_amount)<abs(atd.adt_amount), atd1.adt_amount*-1, atd.adt_amount), 0)) AS credit, 
					SUM(IF(abs(atd1.adt_amount)<abs(atd.adt_amount), atd1.adt_amount*-1, atd.adt_amount)) AS currentTotal 
				FROM account_trans_details atd 
				INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 
					{$whereFromLedger} 
				INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1 
					AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) {$whereToLedger} 
					{$whereDate} 
					{$groupByType} ";
		#DBUtil::execute($sqlTrans);
		DBUtil::dropTempTable($transactionTable);
		DBUtil::createTempTable($transactionTable, $sqlTrans);

		if ($grpByType != '')
		{
			$transRefJoin	 = " AND u.adt_trans_ref_id = t.adt_trans_ref_id";
			$openingRefJoin	 = " AND u.adt_trans_ref_id = o.adt_trans_ref_id";
		}

		// Balance
		$actTransData = "ALedgerData_" . rand();
		#$actTransData	 = "ALedgerData_505570283";
		#$sqlLedger		 = "DROP TABLE IF EXISTS {$actTransData}; CREATE TABLE {$actTransData} ";
		#if (($grpByType != '' && $fromLedgerId != 14) || ($fromLedgerId == 14 && $grpByPeriod ==''))
		if (in_array($fromLedgerId, array(3, 14, 15, 47)) && $grpByPeriod == '')
		{
			$sqlLedger .= "SELECT from_ledger_id, to_ledger_id, '' as {$periodFieldName}, adt_trans_ref_id, 
				IFNULL(ld.opening,0) opening, IFNULL(ld.debit,0) debit, IFNULL(ld.credit,0) credit, IFNULL(ld.currentTotal,0) currentTotal, 
				(IFNULL(ld.opening,0) + IFNULL(ld.currentTotal,0)) balance 
				FROM (
						SELECT u.from_ledger_id, u.to_ledger_id, 
							u.adt_trans_ref_id, 
							IFNULL(o.opening,0) opening, IFNULL(t.debit,0) debit, IFNULL(t.credit,0) credit, IFNULL(t.currentTotal,0) currentTotal, 
							(IFNULL(o.opening,0) + IFNULL(t.currentTotal,0)) balance 
						FROM 
							(SELECT from_ledger_id, to_ledger_id, adt_trans_ref_id 
							FROM {$openingTable} 
							UNION 
							SELECT from_ledger_id, to_ledger_id, adt_trans_ref_id 
							FROM {$transactionTable}) as u 
							LEFT JOIN {$openingTable} o ON 
								u.from_ledger_id = o.from_ledger_id AND u.to_ledger_id = o.to_ledger_id $openingRefJoin 
							LEFT JOIN {$transactionTable} t ON 
								(u.from_ledger_id=t.from_ledger_id AND u.to_ledger_id=t.to_ledger_id $transRefJoin) 
							WHERE (o.opening <> 0 OR t.credit <> 0 OR t.debit <> 0)	
					) ld
				";
		}
		else
		{
			$sqlLedger .= "SELECT a.from_ledger_id, a.to_ledger_id, {$periodFieldName}, a.adt_trans_ref_id,
				IFNULL(a.debit,0) debit, IFNULL(a.credit,0) credit, IFNULL(a.currentTotal,0) currentTotal, 
				(IFNULL(a.currentTotal,0)) balance 
				FROM {$transactionTable} a 
				WHERE (a.credit <> 0 OR a.debit <> 0) ";
		}

		#DBUtil::execute($sqlLedger);
		DBUtil::dropTempTable($actTransData);
		DBUtil::createTempTable($actTransData, $sqlLedger);

		// Entity
		if ($grpByType != '')
		{
			$tempTableEntityData = "ALedgerEntityData_" . rand();
			#$tempTableEntityData = "ALedgerEntityData_505570789";
			#$sqlEntity			 = "DROP TABLE IF EXISTS {$tempTableEntityData}; CREATE TABLE {$tempTableEntityData} ";

			if ($grpByType == 'partner')
			{
				$sqlEntity .= "SELECT IFNULL(agt_company, CONCAT(agt_fname, ' ', agt_lname)) as entityName, a.* 
					FROM {$actTransData} a 
					INNER JOIN agents agt ON a.adt_trans_ref_id = agt.agt_id";
			}
			elseif ($grpByType == 'vendor')
			{
//				$sqlEntity .= "SELECT v1.vnd_name as entityName, 
//						a.from_ledger_id, a.to_ledger_id, a.{$periodFieldName}, a.adt_trans_ref_id, 
//						SUM(a.opening) opening, SUM(a.debit) debit, SUM(a.credit) credit, SUM(a.currentTotal) currentTotal, SUM(a.balance) balance 
//						FROM vendors v2 
//						INNER JOIN vendors v1 ON v2.vnd_ref_code = v1.vnd_id 
//						INNER JOIN {$actTransData} a ON a.adt_trans_ref_id = v1.vnd_id 
//						GROUP BY v1.vnd_ref_code, from_ledger_id, to_ledger_id";
				$sqlEntity .= "SELECT v1.vnd_name as entityName, 
						a.from_ledger_id, a.to_ledger_id, a.{$periodFieldName}, a.adt_trans_ref_id, 
						SUM(a.opening) opening, SUM(a.debit) debit, SUM(a.credit) credit, SUM(a.currentTotal) currentTotal, SUM(a.balance) balance 
						FROM {$actTransData} a 
						INNER JOIN vendors v1 ON v1.vnd_id = a.adt_trans_ref_id 
						GROUP BY v1.vnd_ref_code, from_ledger_id, to_ledger_id";
			}

			#DBUtil::execute($sqlEntity);
			DBUtil::dropTempTable($tempTableEntityData);
			DBUtil::createTempTable($tempTableEntityData, $sqlEntity);

			$actTransData = $tempTableEntityData;
		}

		$sqlData = "SELECT a.*, a1.ledgerName fromLedgerName, a2.ledgerName toLedgerName 
			FROM {$actTransData} a 
			LEFT JOIN account_ledger a1 ON a1.ledgerId = a.from_ledger_id 
			LEFT JOIN account_ledger a2 ON a2.ledgerId = a.to_ledger_id";

		/* echo "<br><br>sqlOpening == " . $sqlOpening;
		  echo "<br><br>sqlTrans == " . $sqlTrans;
		  echo "<br><br>sqlLedger == " . $sqlLedger;
		  echo "<br><br>sqlEntity == " . $sqlEntity;
		  echo "<br><br>sqlData == " . $sqlData;
		  die(); */

		return DBUtil::query($sqlData, DBUtil::SDB());
	}

	/**
	 * 
	 * @param type $model
	 * @param type $bkgmodel
	 * @return type
	 */
	public static function addCompensation($model, $bkgmodel)
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$ptpId		 = PaymentType::model()->ptpList($model->adt_ledger_id);
			$bkgId		 = $model->adt_trans_ref_id;
			$userId		 = $bkgmodel->bkgUserInfo->bkg_user_id;
			$maxUseType	 = $model->ucrMaxuseType;
			$creditType	 = $model->ucrCreditType;
			$validity	 = new CDbExpression("DATE_ADD(NOW(), INTERVAL 12 MONTH)");
			//$model->adt_amount		 = -1 * $model->adt_amount;
			if ($model->adt_ledger_id == Accounting::LI_WALLET)
			{
				$accTransModel1				 = new AccountTransactions();
				$accTransModel1->act_amount	 = $model->adt_amount;
				$accTransModel1->act_date	 = new CDbExpression('NOW()');
				$accTransModel1->act_type	 = Accounting::LI_COMPENSATION;
				$accTransModel1->act_ref_id	 = $bkgmodel->bkg_id;
				$accTransModel1->act_remarks = "Compensation given against booking id :" . $bkgmodel->bkg_id . '. ' . $model->adt_remarks;

				$returnSet = AccountTransactions::model()->compensationAmount($accTransModel1->act_date, $accTransModel1->act_amount, $bkgmodel->bkg_id, PaymentType::TYPE_WALLET, $accTransModel1->act_remarks, '', UserInfo::getInstance(), Accounting::LI_COMPENSATION);
			}
			else if ($model->adt_ledger_id == Accounting::LI_GOZOCOINS)
			{
				$adtAmount = ($model->adt_amount > 0) ? -1 * $model->adt_amount : $model->adt_amount;

				$gozoCoinRefunded			 = true;
				$accTransModel1				 = new AccountTransactions();
				$accTransModel1->act_amount	 = $model->adt_amount;
				$accTransModel1->act_date	 = new CDbExpression('NOW()');
				$accTransModel1->act_type	 = Accounting::AT_BOOKING;
				$accTransModel1->act_ref_id	 = $bkgmodel->bkg_id;
				$accTransModel1->act_remarks = "Compensation given gozo coins against booking id:" . $bkgmodel->bkg_id . '. ' . $model->adt_remarks;

				$returnSet = AccountTransactions::model()->compensationAmount($accTransModel1->act_date, $accTransModel1->act_amount, $bkgmodel->bkg_id, PaymentType::TYPE_GOZO_COINS, $accTransModel1->act_remarks, '', UserInfo::getInstance());
			}
			else
			{
				$paymentGateway						 = new PaymentGateway();
				$paymentGateway->apg_booking_id		 = $bkgId;
				$paymentGateway->apg_acc_trans_type	 = Accounting::LI_COMPENSATION;
				$paymentGateway->apg_trans_ref_id	 = $bkgId;
				$paymentGateway->apg_ledger_id		 = $model->adt_ledger_id;
				$paymentGateway->apg_ptp_id			 = $ptpId;
				$paymentGateway->apg_amount			 = -1 * $model->adt_amount;
				$paymentGateway->apg_mode			 = 1;
				$paymentGateway->apg_remarks		 = "Compensation given against booking id:" . $bkgmodel->bkg_id . '. ' . $model->adt_remarks;
				$paymentGateway->apg_ref_id			 = '';
				$paymentGateway->apg_user_type		 = UserInfo::TYPE_ADMIN;
				$paymentGateway->apg_user_id		 = Yii::app()->user->getId();
				$paymentGateway->apg_status			 = 1;
				$paymentGateway->apg_date			 = new CDbExpression('NOW()');
				$paymentGateway->apg_ref_id			 = 0;
				$pgModel							 = $paymentGateway->payment($model->adt_ledger_id);
				$pgModel->refresh();

				//$amount = -1 * $paymentGateway->apg_amount;
				$returnSet = AccountTransactions::model()->compensationAmount($paymentGateway->apg_date, $model->adt_amount, $paymentGateway->apg_trans_ref_id, $paymentGateway->apg_ptp_id, $paymentGateway->apg_remarks, $paymentGateway, UserInfo::getInstance(), Accounting::LI_COMPENSATION);
			}
			if ($gozoCoinRefunded)
			{
				$userCredits = new UserCredits();
				$returnSet	 = $userCredits->creditGozoCoins($userId, $creditType, $adtAmount, 'Compensation given gozo coins against booking', $maxUseType, $bkgId, $validity);
			}

			if ($returnSet->getStatus())
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Compensation given against booking id:" . $bkgId);
				DBUtil::commitTransaction($transaction);
				return $returnSet;
			}
			else
			{
				DBUtil::rollbackTransaction($transaction);
				return $returnSet;
			}
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->setErrors($e->getMessage());
			return $returnSet;
		}
	}

	/**
	 * modify additional param 
	 * @param type $transactionId
	 * @param type $totalWaivedOff
	 */
	public static function modifyAdditionalParam($transactionId, $totalWaivedOff)
	{

		$modelAccTransDetails = AccountTransDetails::model()->findAll('adt_trans_id=:act_id AND adt_active=1 AND adt_status=1', ['act_id' => $transactionId]);
		foreach ($modelAccTransDetails as $accTransDet)
		{
			$param	 = ["adtId" => $accTransDet->adt_id, "totalWaivedOff" => $totalWaivedOff];
			$sql	 = "UPDATE account_trans_details SET adt_addt_params = JSON_SET(adt_addt_params, '$.totalWaivedOff', :totalWaivedOff) WHERE adt_id =:adtId";
			$result	 = DBUtil::execute($sql, $param);
		}
	}

	/**
	 * function for add manual balance from customer bank account to gozo account.
	 * @param type $model
	 * @param type $userid
	 * @return boolean
	 */
	public static function addManualBalance($model, $userid)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			$ptpStr	 = PaymentType::model()->getList()[$model->adt_ledger_id];
			$ptpId	 = PaymentType::model()->ptpList($model->adt_ledger_id);

			$paymentGateway						 = new PaymentGateway();
			$paymentGateway->apg_booking_id		 = null;
			$paymentGateway->apg_acc_trans_type	 = Accounting::AT_ONLINEPAYMENT;
			$paymentGateway->apg_trans_ref_id	 = $userid;
			$paymentGateway->apg_ledger_id		 = $model->adt_ledger_id;
			$paymentGateway->apg_ptp_id			 = $ptpId;
			$paymentGateway->apg_amount			 = $model->adt_amount;
			$paymentGateway->apg_mode			 = 1;
			$paymentGateway->apg_remarks		 = $model->adt_remarks . ' Reference.Id: ' . $model->refrence_id;
			$paymentGateway->apg_ref_id			 = '';
			$paymentGateway->apg_user_type		 = UserInfo::TYPE_ADMIN;
			$paymentGateway->apg_user_id		 = Yii::app()->user->getId();
			$paymentGateway->apg_status			 = 1;
			$paymentGateway->apg_date			 = new CDbExpression('NOW()');
			$paymentGateway->apg_ref_id			 = 0;
			$pgModel							 = $paymentGateway->payment($model->adt_ledger_id);
			$pgModel->refresh();
//			$pgModel->addToWallet();
			$bankRefId							 = $paymentGateway->apg_id;
			$refType							 = Accounting::AT_ONLINEPAYMENT;
			$remarks							 = $paymentGateway->apg_remarks;
			$ledgerType							 = Accounting::LI_WALLET;
			$accType							 = Accounting::AT_USER;
			$date								 = new CDbExpression('NOW()');
			AccountTransactions::model()->addAmountGozoReceiver($paymentGateway, $userid, $bankRefId, $remarks, $refType, $accType, $ledgerType, $date, null);
			$success							 = true;
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$success = false;
		}
		return $success;
	}

	public static function getTransactionByVndId($vndId)
	{
		$param	 = ['vndId' => $vndId];
		$sql	 = "SELECT vnd.vnd_id, v1.totTrans, v1.adt_trans_ref_id, vnp.vnp_is_freeze, vnp.vnp_cod_freeze,
			vnp.vnp_gozonow_enabled, vnp.vnp_low_rating_freeze,vnp.vnp_doc_pending_freeze,vnp.vnp_manual_freeze
			FROM `vendors` vnd
			INNER JOIN `vendor_pref` vnp ON vnp.vnp_vnd_id=vnd.vnd_id
			LEFT JOIN
			 (
				SELECT SUM(atd.adt_amount) AS totTrans, atd.adt_trans_ref_id 
				FROM `account_trans_details` atd
				JOIN `account_transactions` act ON act.act_id = atd.adt_trans_id AND act.act_active = 1
				WHERE atd.adt_active = 1 AND atd.adt_status = 1 AND atd.adt_type = 2 
					AND atd.adt_ledger_id = 14 AND atd.adt_trans_ref_id = :vndId
				GROUP BY atd.adt_trans_ref_id
			) AS v1 ON v1.adt_trans_ref_id = vnd.vnd_id
			WHERE vnd.vnd_id =:vndId
			GROUP BY vnd.vnd_id;";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $param);
	}

	public static function getCountCompensation($bkgId, $vndId)
	{
		$param	 = ['bkgId' => $bkgId, 'vndId' => $vndId];
		$sql	 = "SELECT COUNT(act_id) as bkgcnt
				FROM account_trans_details atd
                INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id  AND atd.adt_ledger_id=27
                INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id AND atd1.adt_ledger_id = 14
                WHERE atd1.adt_active = 1 AND atd1.adt_status=1
                AND atd.adt_active = 1 AND atd.adt_status=1 
                AND act.act_active = 1 AND act.act_status=1
                AND atd.adt_trans_ref_id =:bkgId AND atd1.adt_trans_ref_id =:vndId";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
	}

	/**
	 * 
	 * @param type $entityId
	 * @param type $entityType
	 * @param \Beans\common\dateRange $dateRangeObj
	 * @param \Beans\common\PageRef $pageRef
	 * @param type $ledgers
	 * @return type
	 * @throws Exception
	 */
	public static function entityTransactionListDesc($relEntIds, $entityType, $dateRangeObj = null, $pageRef = null, $ledgers = '')
	{
		if ($relEntIds == null || $relEntIds == "" || $entityType == null || $entityType == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}


		$reedemPenaltyData	 = json_decode(Config::get('vendor.ReedemPenalty'));
		$penaltyStartDate	 = $reedemPenaltyData[0]->startDate;

		$nowDateTime = Filter::getDBDateTime();

		$limit = " LIMIT 0,20 ";
		if ($pageRef != null)
		{
			/** @var \Beans\common\PageRef $pageRef */
			$pageNumber	 = $pageRef->pageCount;
			$pageSize	 = $pageRef->pageSize;
			$offset		 = $pageNumber * $pageSize;
			$limit		 = " LIMIT {$offset},{$pageSize} ";
		}
		$dateRange	 = '';
		$actOpenDate = '';
		$today		 = date("Y-m-d", strtotime($nowDateTime));
		/** @var \Beans\common\dateRange $dateRangeObj */
		if ($dateRangeObj == null || (!$dateRangeObj->fromDate || !$dateRangeObj->toDate))
		{
			$startDate		 = date("Y-m-d", strtotime($nowDateTime . ' -3 MONTH'));
			$dateRange		 = "  AND (act_date)<= '$today 23:59:59' AND (act_date)>= '$startDate 00:00:00' ";
			$actOpenDate	 = "  AND (act_date)<'$startDate 00:00:00'";
			$actCloseDate	 = "  AND act_date<'$today 23:59:59'";
		}
		else
		{
			$fromDate	 = $dateRangeObj->fromDate;
			$toDate		 = $dateRangeObj->toDate;

			$dateRange		 = " AND (act_date)<= '$toDate 23:59:59' AND (act_date)>= '$fromDate 00:00:00' ";
			$actOpenDate	 = "  AND (act_date)<'$fromDate 00:00:00'";
			$actCloseDate	 = "  AND act_date<'$toDate 23:59:59'";
		}

		if ($ledgers != '')
		{
			$ledgersID	 = "AND atd1.adt_ledger_id IN ($ledgers)";
			$and		 = '';
		}
		else
		{
			$and = ' and atd1.adt_ledger_id=22 ';
		}

		if ($entityType == UserInfo::TYPE_VENDOR)
		{
			$accountLedgerId = Accounting::LI_OPERATOR;
		}
		$ledgerBankCharge = Accounting::LI_BANKCHARGE;

		$sql = "SELECT *, Round(@runningBal1 := @runningBal1 + @prevTrans,2) as runningBalance, @prevTrans:=adt_amount , 
				Round(@runningBal1 * -1,2) as vendorRunningBalance
			FROM (
					SELECT act_id,  bankTrans.adt_amount bankCharge, act_date,act_created, 
					atd.adt_id, atd.adt_ledger_id,booking.bkg_id, 
					act_type,act_ref_id,
					atd1.adt_ledger_id refLedger,
					atd1.adt_trans_ref_id refId,
					atd1.adt_type refType,
					IF(atd1.adt_ledger_id=13,  atd.adt_remarks , atd1.adt_remarks ) transRemarks, 
					bkg_net_advance_amount as bkg_advance_amount,
					act.act_date as ven_trans_date,booking.bkg_booking_id,
					GROUP_CONCAT(atd1.adt_ledger_id) as ledgerIds,
					GROUP_CONCAT(
						CASE atd1.adt_ledger_id	
							WHEN 13 THEN CONCAT(account_ledger.ledgerName, ': ', booking.bkg_booking_id, ' (', booking.bkg_bcb_id ,')')
							WHEN 22 THEN CONCAT(account_ledger.ledgerName, ': ', booking_cab.bcb_id)
							ELSE account_ledger.ledgerName
							END SEPARATOR ', ') as ledgerNames,
					account_ledger.ledgerName , 
					atd.adt_amount ,
					atd1.adt_amount transAmount,
					atd.adt_addt_params,
					IF(atd1.adt_ledger_id =28 ,IFNULL(REPLACE(json_extract(atd1.adt_addt_params, '$.totalWaivedOff'),'\"',''),0),0) as totalPenaltyWaivedOff,
					IF(atd1.adt_ledger_id =28 
						AND atd.adt_amount > 0 
						AND act_date > '$penaltyStartDate 00:00:00' 
						AND IFNULL(REPLACE(json_extract(atd1.adt_addt_params, '$.totalWaivedOff'),'\"',''),0) < atd.adt_amount
						AND DATEDIFF('$nowDateTime',act_date) < 60 ,1,0) as penaltyRemovable,							 
					IF(atd1.adt_ledger_id =28 
						AND atd.adt_amount > 0 
						AND IFNULL(REPLACE(json_extract(atd1.adt_addt_params, '$.totalWaivedOff'),'\"',''),0) < atd.adt_amount
						AND DATEDIFF('$nowDateTime',act_date) < 30 ,1,0) as raiseDispute,

					IF(atd1.adt_ledger_id=22, MIN(bcbbkg.bkg_pickup_date), booking.bkg_pickup_date) bkg_pickup_date,
					CONCAT(admins.adm_fname, ' ', admins.adm_lname) AS adm_name  
				FROM account_transactions act
				INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id AND atd.adt_ledger_id=$accountLedgerId
					AND atd.adt_trans_ref_id IN ({$relEntIds}) AND  adt_status=1 
					AND adt_active=1 AND act_status=1 AND act_active=1 $dateRange
				INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id 
					AND ((abs(atd.adt_amount)=atd.adt_amount AND atd1.adt_amount<0)  OR (abs(atd.adt_amount)<>atd.adt_amount AND atd1.adt_amount>0) )
				LEFT JOIN `admins` ON admins.adm_id = act.act_user_id AND act.act_user_type = 4
				LEFT JOIN account_trans_details bankTrans ON act.act_id = bankTrans.adt_trans_id 
					AND bankTrans.adt_ledger_id={$ledgerBankCharge}

				INNER JOIN account_ledger ON atd1.adt_ledger_id=account_ledger.ledgerId $ledgersID
				LEFT JOIN booking ON (atd1.adt_ledger_id=13) AND atd1.adt_trans_ref_id=booking.bkg_id
				LEFT JOIN booking_invoice ON booking_invoice.biv_bkg_id=booking.bkg_id
				LEFT JOIN booking_cab ON atd1.adt_trans_ref_id=booking_cab.bcb_id $and 
				LEFT JOIN booking bcbbkg ON bcbbkg.bkg_bcb_id = booking_cab.bcb_id AND booking_cab.bcb_active = 1

				WHERE   act_active = 1 AND act_status=1 AND act_date >= '2021-04-01 00:00:00' 
				GROUP BY atd.adt_id 
				ORDER BY act_date DESC,act_created DESC  
			) a
			JOIN (SELECT @prevTrans := 0) prevTrans
			JOIN (SELECT   @runningBal1 := ifNULL(SUM(adt_amount),0) openBalance FROM account_trans_details
			INNER JOIN account_transactions ON act_id=adt_trans_id
			WHERE 1 $actCloseDate 
				AND adt_ledger_id=$accountLedgerId  AND adt_status=1 AND adt_active=1 AND act_status=1 
				AND act_active=1 AND act_date >= '2021-04-01 00:00:00' 
				AND adt_trans_ref_id IN ({$relEntIds}) ) r $limit";

		$recordset = DBUtil::query($sql, DBUtil::SDB());

		return $recordset;
	}

	public static function entityTransactionList($relEntIds, $entityType, $dateRangeObj = null, $pageRef = null, $ledgers = '')
	{
		if ($relEntIds == null || $relEntIds == "" || $entityType == null || $entityType == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}


		$reedemPenaltyData	 = json_decode(Config::get('vendor.ReedemPenalty'));
		$penaltyStartDate	 = $reedemPenaltyData[0]->startDate;

		$nowDateTime = Filter::getDBDateTime();

		$limit = " LIMIT 0,20 ";
		if ($pageRef != null)
		{
			/** @var \Beans\common\PageRef $pageRef */
			$pageNumber	 = $pageRef->pageCount;
			$pageSize	 = $pageRef->pageSize;
			$offset		 = $pageNumber * $pageSize;
			$limit		 = " LIMIT {$offset},{$pageSize} ";
		}
		$dateRange	 = '';
		$actOpenDate = '';
		/** @var \Beans\common\dateRange $dateRangeObj */
		if ($dateRangeObj == null || (!$dateRangeObj->fromDate || !$dateRangeObj->toDate))
		{
			$today		 = date("Y-m-d", strtotime($nowDateTime));
			$startDate	 = date("Y-m-d", strtotime($nowDateTime . ' -3 MONTH'));
			$dateRange	 = "  AND (act_date)<= '$today 23:59:59' AND (act_date)>= '$startDate 00:00:00' ";
			$actOpenDate = "  AND (act_date)<'$startDate 00:00:00'";
		}
		else
		{
			$fromDate	 = $dateRangeObj->fromDate;
			$toDate		 = $dateRangeObj->toDate;

			$dateRange	 = " AND (act_date)<= '$toDate 23:59:59' AND (act_date)>= '$fromDate 00:00:00' ";
			$actOpenDate = "  AND (act_date)<'$fromDate 00:00:00'";
		}

		if ($ledgers != '')
		{
			$ledgersID	 = "AND atd1.adt_ledger_id IN ($ledgers)";
			$and		 = '';
		}
		else
		{
			$and = ' and atd1.adt_ledger_id=22 ';
		}

		if ($entityType == UserInfo::TYPE_VENDOR)
		{
			$accountLedgerId = Accounting::LI_OPERATOR;
		}
		$ledgerBankCharge = Accounting::LI_BANKCHARGE;

		$sql = "SELECT *, Round(@runningBal1 := @runningBal1 + adt_amount,2) as runningBalance,  
				Round(@runningBal1 * -1,2) as vendorRunningBalance
			FROM (
					SELECT act_id,  bankTrans.adt_amount bankCharge, act_date,act_created, 
					atd.adt_id, atd.adt_ledger_id,booking.bkg_id, 
					act_type,act_ref_id,
					atd1.adt_ledger_id refLedger,
					atd1.adt_trans_ref_id refId,
					atd1.adt_type refType,
					IF(atd1.adt_ledger_id=13,  atd.adt_remarks , atd1.adt_remarks ) transRemarks, 
					bkg_net_advance_amount as bkg_advance_amount,
					act.act_date as ven_trans_date,booking.bkg_booking_id,
					GROUP_CONCAT(atd1.adt_ledger_id) as ledgerIds,
					GROUP_CONCAT(
						CASE atd1.adt_ledger_id	
							WHEN 13 THEN CONCAT(account_ledger.ledgerName, ': ', booking.bkg_booking_id, ' (', booking.bkg_bcb_id ,')')
							WHEN 22 THEN CONCAT(account_ledger.ledgerName, ': ', booking_cab.bcb_id)
							ELSE account_ledger.ledgerName
							END SEPARATOR ', ') as ledgerNames,
					account_ledger.ledgerName , 
					atd.adt_amount ,
					atd1.adt_amount transAmount,
					atd.adt_addt_params,
					IF(atd1.adt_ledger_id =28 ,IFNULL(REPLACE(json_extract(atd1.adt_addt_params, '$.totalWaivedOff'),'\"',''),0),0) as totalPenaltyWaivedOff,
					IF(atd1.adt_ledger_id =28 
						AND atd.adt_amount > 0 
						AND act_date > '$penaltyStartDate 00:00:00' 
						AND IFNULL(REPLACE(json_extract(atd1.adt_addt_params, '$.totalWaivedOff'),'\"',''),0) < atd.adt_amount
						AND DATEDIFF('$nowDateTime',act_date) < 60 ,1,0) as penaltyRemovable,							 
					IF(atd1.adt_ledger_id =28 
						AND atd.adt_amount > 0 
						AND IFNULL(REPLACE(json_extract(atd1.adt_addt_params, '$.totalWaivedOff'),'\"',''),0) < atd.adt_amount
						AND DATEDIFF('$nowDateTime',act_date) < 30 ,1,0) as raiseDispute,

					IF(atd1.adt_ledger_id=22, MIN(bcbbkg.bkg_pickup_date), booking.bkg_pickup_date) bkg_pickup_date,
					CONCAT(admins.adm_fname, ' ', admins.adm_lname) AS adm_name  
				FROM account_transactions act
				INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id AND atd.adt_ledger_id=$accountLedgerId
					AND atd.adt_trans_ref_id IN ({$relEntIds}) AND  adt_status=1 
					AND adt_active=1 AND act_status=1 AND act_active=1 $dateRange
				INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id 
					AND ((abs(atd.adt_amount)=atd.adt_amount AND atd1.adt_amount<0)  OR (abs(atd.adt_amount)<>atd.adt_amount AND atd1.adt_amount>0) )
				LEFT JOIN `admins` ON admins.adm_id = act.act_user_id AND act.act_user_type = 4
				LEFT JOIN account_trans_details bankTrans ON act.act_id = bankTrans.adt_trans_id 
					AND bankTrans.adt_ledger_id={$ledgerBankCharge}

				INNER JOIN account_ledger ON atd1.adt_ledger_id=account_ledger.ledgerId $ledgersID
				LEFT JOIN booking ON (atd1.adt_ledger_id=13) AND atd1.adt_trans_ref_id=booking.bkg_id
				LEFT JOIN booking_invoice ON booking_invoice.biv_bkg_id=booking.bkg_id
				LEFT JOIN booking_cab ON atd1.adt_trans_ref_id=booking_cab.bcb_id $and 
				LEFT JOIN booking bcbbkg ON bcbbkg.bkg_bcb_id = booking_cab.bcb_id AND booking_cab.bcb_active = 1

				WHERE   act_active = 1 AND act_status=1 AND act_date >= '2021-04-01 00:00:00' 
				GROUP BY atd.adt_id 
				ORDER BY act_date,act_created ASC  
			) a
			JOIN (SELECT   @runningBal1 := ifNULL(SUM(adt_amount),0) openBalance FROM account_trans_details
			INNER JOIN account_transactions ON act_id=adt_trans_id
			WHERE 1 $actOpenDate 
				AND adt_ledger_id=$accountLedgerId  AND adt_status=1 AND adt_active=1 AND act_status=1 
				AND act_active=1 AND act_date >= '2021-04-01 00:00:00' 
				AND adt_trans_ref_id IN ({$relEntIds}) ) r $limit";

		$recordset = DBUtil::query($sql, DBUtil::SDB());

		return $recordset;
	}

	/**
	 * 
	 * @param type $vendorId
	 * @return type
	 */
	public static function getTotalClosingbyVendorId($relVndIds)
	{
		$sql			 = "SELECT (sum(adt_amount) *-1) closingBalance , 
			    vendor_stats.vrs_locked_amount as locked_amount,vnp_is_freeze,vnd_active 
                FROM   account_trans_details adt 
				INNER JOIN vendors ON adt.adt_trans_ref_id = vnd_id
				INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id
                INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
                INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id
                WHERE  adt.adt_active = 1 AND act.act_active=1  AND act.act_status = 1
                AND adt.adt_status = 1
                AND adt.adt_ledger_id = 14
                AND adt.adt_type = 2 AND act.act_date >= '2021-04-01 00:00:00' 
                AND vendors.vnd_id IN ({$relVndIds}) ";
		$closingBalance	 = DBUtil::queryScalar($sql, DBUtil::SDB());
		return $closingBalance;
	}

	public static function calTotalTdsByVendorId($relVndIds)
	{
		$PreYear	 = $NextYear	 = "";
		if (date('m') >= 4)
		{
			$PreYear	 = date('Y') . "-04-01 00:00:00";
			$NextYear	 = (date('Y') + 1) . "-03-31 23:59:59";
		}
		else
		{
			$PreYear	 = (date('Y') - 1) . "-04-01 00:00:00";
			$NextYear	 = (date('Y')) . "-03-31 23:59:59";
		}
		$sql = "SELECT atd1.adt_trans_ref_id as vbl_vnd_id,
			(sum(atd1.adt_amount) * -1) AS vendor_amount,
            (sum(if(atd.adt_ledger_id = 22, round(atd1.adt_amount)* 0.01, 0)) * -1) AS totalTDS,
			(sum(if(atd.adt_ledger_id IN(37,55), atd1.adt_amount, 0))* -1)AS alreadypaid
			FROM account_trans_details atd 
			INNER JOIN account_transactions act 
				ON atd.adt_trans_id = act.act_id AND atd.adt_active = 1 AND atd.adt_status = 1
					AND act.act_active = 1 AND act.act_status = 1 AND atd.adt_ledger_id IN(22,37,55)
			INNER JOIN account_trans_details atd1 
				ON act.act_id = atd1.adt_trans_id 
					AND atd1.adt_active = 1 AND atd1.adt_status = 1  AND atd1.adt_ledger_id = 14
			WHERE act.act_date between '$PreYear' and '$NextYear' AND 
				 atd1.adt_trans_ref_id IN ({$relVndIds}) ";

		$recordSet = DBUtil::queryRow($sql, DBUtil::SDB());
		return $recordSet;
	}

	public static function getPaidTdsByVendorId($relVndIds, $tripId = null)
	{

		if ($relVndIds == null || $relVndIds == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}

		$tripVal = '';
		$params	 = [];
		if ($tripId > 0)
		{
			$tripVal			 = " AND atd.adt_ledger_id=:tripId AND atd.adt_type=5 ";
			$params['tripId']	 = $tripId;
		}

		$sql = "SELECT  SUM( atd1.adt_amount) paid  
			FROM account_trans_details atd 
			INNER JOIN account_transactions act 
				ON atd.adt_trans_id = act.act_id AND atd.adt_active = 1 AND atd.adt_status = 1
					AND act.act_active = 1 AND act.act_status = 1 AND atd.adt_ledger_id IN( 37,55)
			INNER JOIN account_trans_details atd1 
				ON act.act_id = atd1.adt_trans_id AND act.act_date >= '2021-04-01 00:00:00'
					AND atd1.adt_active = 1 AND atd1.adt_status = 1  AND atd1.adt_ledger_id = 14
			WHERE  atd1.adt_trans_ref_id IN ({$relVndIds}) $tripVal  ";

		$recordSet = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $recordSet;
	}

	public static function getBookingPaymentDetails($bkgId)
	{
		$sql		 = "SELECT * FROM account_trans_details 
	JOIN account_transactions ON act_id = adt_trans_id 
	JOIN account_ledger ON account_ledger.ledgerId = adt_ledger_id 
		AND account_transactions.act_type=1 
	LEFT JOIN payment_gateway ON payment_gateway.apg_id = account_trans_details.adt_trans_ref_id 
		AND account_ledger.accountGroupId =28
	WHERE act_active=1 AND act_type = 1 
		AND act_ref_id = :bkgId
		AND account_ledger.accountGroupId IN (27, 28)";
		$params		 = ['bkgId' => $bkgId];
		$recordSet	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $recordSet;
	}

	public static function getPartnerWalletEntryForbooking($bkgId)
	{
		$sql		 = "SELECT * FROM account_trans_details 
		JOIN account_transactions ON act_id = adt_trans_id 
		JOIN account_ledger ON account_ledger.ledgerId = adt_ledger_id 
			AND account_transactions.act_type=1  
		WHERE act_active=1 AND act_type = 1 
			AND act_ref_id = :bkgId
			AND account_ledger.accountGroupId = 27 ";
		$params		 = ['bkgId' => $bkgId];
		$recordSet	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $recordSet;
	}

	public static function getPartnerBalanceForbooking($bkgId)
	{
		$sql	 = "SELECT sum(atd.adt_amount) balance FROM account_trans_details atd
		JOIN account_transactions act ON act.act_id = atd.adt_trans_id 
		JOIN account_ledger ON account_ledger.ledgerId = adt_ledger_id 
			AND act.act_type=1  
		WHERE act.act_active=1 AND act.act_status=1 AND act.act_type = 1 
			AND act.act_ref_id = :bkgId
			AND account_ledger.accountGroupId = 27";
		$params	 = ['bkgId' => $bkgId];
		$balance = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $balance;
	}

	public static function calTotalAmountByRelVendors($relVndIds, $fromDate = '', $toDate = '')
	{
		$adtQry	 = '';
		$actQry	 = '';
		if ($fromDate != '')
		{
			$fromDate		 = DateTimeFormat::DatePickerToDate($fromDate);
			$fromTime		 = '00:00:00';
			$fromDateTime	 = $fromDate . ' ' . $fromTime;
			$actQry			 .= " AND act_date>='$fromDateTime'";
		}
		if ($toDate != '')
		{
			$toDate		 = DateTimeFormat::DatePickerToDate($toDate);
			$toTime		 = '23:59:59';
			$toDateTime	 = $toDate . ' ' . $toTime;
			$actQry		 .= " AND act_date<='$toDateTime'";
		}
		if ($openingDate != '')
		{
			$openingDate	 = DateTimeFormat::DatePickerToDate($openingDate);
			$fromTime		 = '00:00:00';
			$fromDateTime	 = $openingDate . ' ' . $fromTime;
			$actQry			 .= " AND act_date < '$fromDateTime'";
		}

		$sql1 = "SELECT (sum(adt_amount) * -1)  vendor_amount 
                FROM vendors  
				LEFT JOIN account_trans_details adt ON adt.adt_trans_ref_id = vnd_id
					AND adt.adt_active = 1 AND adt.adt_ledger_id = 14
					AND adt.adt_type = 2  AND adt.adt_status = 1
					{$adtQry}
                LEFT JOIN account_transactions act ON act.act_id = adt.adt_trans_id 
					AND act.act_active=1 AND act.act_status = 1 
					AND  act.act_date >= '2021-04-01 00:00:00' 
					{$actQry}
                WHERE vendors.vnd_id IN ({$relVndIds}) ";

		$params			 = [];
		$vendorBalance	 = DBUtil::queryScalar($sql1, DBUtil::SDB(), $params);

		$primeVndRow = Vendors::getPrimaryByIds($relVndIds);

		$vndId = $primeVndRow['vnd_id'];

		$objVnd = Vendors::model()->findByPk($vndId);

		$calculateAmt						 = array();
		$calculateAmt['vendor_amount']		 = $vendorBalance;
		$calculateAmt['vnd_security_amount'] = AccountTransactions::getSecurityAmount($relVndIds);
		$calculateAmt['locked_amount']		 = $objVnd->vendorStats->vrs_locked_amount;
		$calculateAmt['vnp_is_freeze']		 = $objVnd->vendorPrefs->vnp_is_freeze;
		$calculateAmt['vnd_active']			 = $objVnd->vnd_active;
		$calculateAmt['vnd_name']			 = $objVnd->vnd_name;
		$calculateAmt['vnd_code']			 = $objVnd->vnd_code;
		if ($vendorBalance < 0)
		{
			$calculateAmt['withdrawable_balance']	 = 0;
			$calculateAmt['vendor_amount_type']		 = 'Payable';
		}
		else
		{
			$Withdrawable_Balance					 = ($calculateAmt['vnp_is_freeze'] != 0 || $calculateAmt['vnd_active'] != 1) ? 0 : max([$vendorBalance - $calculateAmt['locked_amount'], 0]);
			$calculateAmt['withdrawable_balance']	 = $Withdrawable_Balance;
			$calculateAmt['vendor_amount_type']		 = 'Receivable';
		}
		return $calculateAmt;
	}

	public static function calTotalAmountByVendorIdOld($relVndIds, $fromDate = '', $toDate = '')
	{


		$sql = "SELECT  
					SUM(vrs_security_amount) as vnd_security_amount,
			    SUM(vendor_stats.vrs_locked_amount) as locked_amount,
				vnp_is_freeze,
				vnd_active,
				vnd_name,vnd_code,vnd_id
                FROM vendors  
				INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id
                INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id	
				WHERE vendors.vnd_id IN ({$relVndIds}) ";

		$recordSet = DBUtil::queryRow($sql, DBUtil::SDB());

		$adtQry	 = '';
		$actQry	 = '';
		if ($fromDate != '')
		{
			$fromDate		 = DateTimeFormat::DatePickerToDate($fromDate);
			$fromTime		 = '00:00:00';
			$fromDateTime	 = $fromDate . ' ' . $fromTime;
			$actQry			 .= " AND act_date>='$fromDateTime'";
		}
		if ($toDate != '')
		{
			$toDate		 = DateTimeFormat::DatePickerToDate($toDate);
			$toTime		 = '23:59:59';
			$toDateTime	 = $toDate . ' ' . $toTime;
			$actQry		 .= " AND act_date<='$toDateTime'";
		}
		if ($openingDate != '')
		{
			$openingDate	 = DateTimeFormat::DatePickerToDate($openingDate);
			$fromTime		 = '00:00:00';
			$fromDateTime	 = $openingDate . ' ' . $fromTime;
			$actQry			 .= " AND act_date < '$fromDateTime'";
		}

		$sql1 = "SELECT (sum(adt_amount) * -1)  vendor_amount 
                FROM vendors  
				LEFT JOIN account_trans_details adt ON adt.adt_trans_ref_id = vnd_id
					AND adt.adt_active = 1 AND adt.adt_ledger_id = 14
					AND adt.adt_type = 2  AND adt.adt_status = 1
					{$adtQry}
                LEFT JOIN account_transactions act ON act.act_id = adt.adt_trans_id 
					AND act.act_active=1 AND act.act_status = 1 
					AND  act.act_date >= '2021-04-01 00:00:00' 
					{$actQry}
                WHERE vendors.vnd_id IN ({$relVndIds}) ";

		$params								 = [];
		$recordSetAct						 = DBUtil::queryRow($sql1, DBUtil::SDB(), $params);
		$calculateAmt						 = array();
		$vendorBalance						 = $recordSetAct['vendor_amount'];
		$calculateAmt['vnd_security_amount'] = $recordSet['vnd_security_amount'];
		$calculateAmt['vendor_amount']		 = $recordSet['vendor_amount'];
		$calculateAmt['locked_amount']		 = $recordSet['locked_amount'];
		$calculateAmt['vnp_is_freeze']		 = $recordSet['vnp_is_freeze'];
		$calculateAmt['vnd_active']			 = $recordSet['vnd_active'];
		$calculateAmt['vnd_name']			 = $recordSet['vnd_name'];
		$calculateAmt['vnd_code']			 = $recordSet['vnd_code'];

		if ($vendorBalance < 0)
		{
			$calculateAmt['withdrawable_balance']	 = 0;
			$calculateAmt['vendor_amount_type']		 = 'Payable';
		}
		else
		{
			$Withdrawable_Balance					 = ($recordSet['vnp_is_freeze'] != 0 || $recordSet['vnd_active'] != 1) ? 0 : max([$vendorBalance - $recordSet['locked_amount'], 0]);
			$calculateAmt['withdrawable_balance']	 = $Withdrawable_Balance;
			$calculateAmt['vendor_amount_type']		 = 'Receivable';
		}

		return $calculateAmt;
	}

	/**
	 * 
	 * @param type $entityId
	 * @param type $entityType
	 * @param \Beans\common\dateRange $dateRangeObj
	 * @param \Beans\common\PageRef $pageRef
	 * @param type $ledgers
	 * @return type
	 * @throws Exception
	 */
	public static function vendorTripTransactionList($vndId, $tripId = null, $dateRangeObj = null, $pageRef = null)
	{
		$nowDateTime = Filter::getDBDateTime();
		$limit		 = " LIMIT 0,20 ";
		if ($pageRef != null)
		{
			/** @var \Beans\common\PageRef $pageRef */
			$pageNumber	 = $pageRef->pageCount;
			$pageSize	 = $pageRef->pageSize;
			$offset		 = $pageNumber * $pageSize;
			$limit		 = " LIMIT {$offset},{$pageSize} ";
		}
		$dateRange	 = $tripVal	 = '';
		$params		 = ['vndId' => $vndId];
		if ($tripId > 0)
		{
			$tripVal			 = " AND (bkg.bkg_bcb_id =:tripId OR  bcb.bcb_id=:tripId)";
			$params['tripId']	 = $tripId;
		}
		else
		{
			/** @var \Beans\common\dateRange $dateRangeObj */
			$today		 = date("Y-m-d", strtotime($nowDateTime));
			$startDate	 = date("Y-m-d", strtotime($nowDateTime . ' -6 MONTH'));
			$dateRange	 = "  AND (act_date)<= '$today 23:59:59' AND (act_date)>= '$startDate 00:00:00' ";
			if ($dateRangeObj->fromDate && $dateRangeObj->toDate)
			{
				$fromDate	 = $dateRangeObj->fromDate;
				$toDate		 = $dateRangeObj->toDate;
				$dateRange	 = " AND (act.act_date)<= '$toDate 23:59:59' AND (act.act_date)>= '$fromDate 00:00:00' ";
			}
		}

		$sql		 = "SELECT  act.act_id,adt.adt_id,act.act_ref_id,
					act.act_date,act.act_created,
					(adt.adt_amount ) transAmount , 
					act.act_remarks, adt.adt_trans_ref_id refId, adt.adt_type refType,					
					GROUP_CONCAT(DISTINCT bkg.bkg_id) bkgId, 
					GROUP_CONCAT(DISTINCT bkg.bkg_booking_id) bookingId, 
					IFNULL(bkg.bkg_bcb_id, bcb.bcb_id) as tripId , account_ledger.ledgerName  
				FROM `account_trans_details` adt 
			INNER JOIN `account_trans_details` adt1 
				ON adt1.adt_trans_id = adt.adt_trans_id  AND adt1.adt_ledger_id = 14 
					AND adt1.adt_active = 1 AND adt1.adt_status =1 
			INNER JOIN account_transactions act 
				ON adt.adt_trans_id = act.act_id AND act.act_active = 1 AND act.act_status=1
			INNER JOIN account_ledger ON adt.adt_ledger_id=account_ledger.ledgerId  
			LEFT JOIN booking bkg 
				ON bkg.bkg_id = adt.adt_trans_ref_id AND adt.adt_type=1
			LEFT JOIN booking_cab bcb 
				ON bcb.bcb_id = adt.adt_trans_ref_id AND adt.adt_type=5 			
			WHERE adt1.adt_trans_ref_id = :vndId AND adt.adt_ledger_id IN (13,22,28,55) 
				AND adt.adt_active = 1 AND adt.adt_status =1 
				$tripVal $dateRange
			GROUP BY adt_id ORDER BY tripId,act.act_date,adt_id  $limit";
		$recordset	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $recordset;
	}

	public static function driverBonusList($driverId, $dateRangeObj = null, $pageRef = null)
	{
		$nowDateTime = Filter::getDBDateTime();
		$limit		 = " LIMIT 0,20 ";
		if ($pageRef != null)
		{
			/** @var \Beans\common\PageRef $pageRef */
			$pageNumber	 = $pageRef->pageCount;
			$pageSize	 = $pageRef->pageSize;
			$offset		 = $pageNumber * $pageSize;
			$limit		 = " LIMIT {$offset},{$pageSize} ";
		}

		$params = ['drvId' => $driverId];

		/** @var \Beans\common\dateRange $dateRangeObj */
		$today		 = date("Y-m-d", strtotime($nowDateTime));
		$startDate	 = date("Y-m-d", strtotime($nowDateTime . ' -6 MONTH'));
		$dateRange	 = "  AND (act_date)<= '$today 23:59:59' AND (act_date)>= '$startDate 00:00:00' ";
		if ($dateRangeObj->fromDate && $dateRangeObj->toDate)
		{
			$fromDate	 = $dateRangeObj->fromDate;
			$toDate		 = $dateRangeObj->toDate;
			$dateRange	 = " AND (act.act_date)<= '$toDate 23:59:59' AND (act.act_date)>= '$fromDate 00:00:00' ";
		}

		$sql		 = "SELECT *, @runningBal1 := @runningBal1 + drv_bonus_amount as runningBalance
							FROM (
							SELECT booking.bkg_booking_id AS booking_id,
							act.act_date AS trans_date,
							act.act_created AS drv_createdate,
							atd1.adt_amount AS drv_bonus_amount,
							act.act_remarks AS drv_remarks,
							concat(admins.adm_fname, ' ', admins.adm_lname) AS adm_name,
							 IF(atd1.adt_ledger_id=41, booking.bkg_booking_id , account_ledger.ledgerName ) ledgerNames						 
								FROM drivers drv1 
						INNER JOIN drivers drv ON drv.drv_ref_code = drv1.drv_ref_code
						INNER JOIN `account_trans_details` atd
							ON  atd.adt_ledger_id = 40
							   AND atd.adt_status = 1
							   AND atd.adt_active = 1 AND atd.adt_trans_ref_id = drv.drv_id
						 INNER JOIN `account_transactions` act ON  act.act_id = atd.adt_trans_id AND act.act_status = 1
							   AND act.act_active = 1
						 INNER JOIN account_trans_details atd1
							   ON act.act_id = atd1.adt_trans_id 
								AND ((abs(atd.adt_amount) <> atd.adt_amount AND atd1.adt_amount > 0)
								   OR (abs(atd.adt_amount) = atd.adt_amount AND atd1.adt_amount < 0)) 
						LEFT JOIN booking ON atd1.adt_ledger_id = 41
							  AND atd1.adt_trans_ref_id = booking.bkg_id     
						LEFT JOIN `admins` ON admins.adm_id = act.act_user_id AND act.act_user_type = 4
						  INNER JOIN `account_ledger`
						 ON atd1.adt_ledger_id = account_ledger.ledgerId
						  INNER JOIN `drivers`
						 ON drivers.drv_id = atd.adt_trans_ref_id
					 WHERE act_active = 1 AND act_status = 1 AND act.act_type = 6
					 GROUP BY atd.adt_id ORDER BY act.act_date ASC
					) a
					   JOIN (SELECT @runningBal1 := ifNULL(SUM(adt_amount),0) openBalance FROM account_trans_details
					   INNER JOIN account_transactions ON act_id=adt_trans_id
					   WHERE act_date<'2016-06-01 00:00:00'
					   AND adt_ledger_id=40 AND adt_status=1 AND adt_active=1 AND act_status=1 AND act_active=1 AND
					adt_trans_ref_id=:drvId) r";
		$recordSet	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $recordSet;
	}

	public static function DcoTransactionList($vendorId, $fromDate = '', $toDate = '')
	{
		//echo $fromDate.'--'.$toDate;exit;
		if ($vendorId == null || $vendorId == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}

		// Getting Merged VendorIds
//		$vndIds = Vendors::getVndIdsByRefCode($vendorId);
		$vndIds = Vendors::getRelatedIds($vendorId);

		if ($vndIds == null || $vndIds == "")
		{
			throw new Exception("Vendor not found", ReturnSet::ERROR_INVALID_DATA);
		}

		$dateRange = '';
		
		if (($fromDate != '' && $fromDate != '1970-01-01') && ($toDate != '' && $toDate != '1970-01-01'))
		{
		
			
			$dateRange	 .= " AND (act_date) >='$fromDate 00:00:00' AND (act_date) <= '$toDate 23:59:59' ";
			$actOpenDate = " AND (act_date) < '$fromDate 00:00:00'";
			if ($ledgers != '')
			{
				$ledgersID	 = "AND atd1.adt_ledger_id IN ($ledgers)";
				$and		 = '';
			}
			else
			{
				$and = ' and atd1.adt_ledger_id=22 ';
			}
		}
		$vndAmt = '';
		if ($vendorAmt == '0')
		{
			$vndAmt .= "  AND (adt.adt_amount<>0)";
		}
		
		$sql = "SELECT *, Round(@runningBal1 := @runningBal1 + ven_trans_amount,2) as runningBalance,  
				Round(@runningBal1 * -1,2) as vendorRunningBalance
			FROM (
				SELECT atd2.adt_amount bank_charge, act_date,act_created, act_id, 
				atd.adt_id, atd.adt_ledger_id,booking.bkg_id, act_type,act_ref_id,
					IF(atd1.adt_ledger_id=13,  atd.adt_remarks , atd1.adt_remarks ) ven_trans_remarks, bkg_net_advance_amount as bkg_advance_amount,
					act.act_date as ven_trans_date,booking.bkg_booking_id,bcbbkg.bkg_bcb_id ,
					GROUP_CONCAT(atd1.adt_ledger_id) as ledgerIds,
					GROUP_CONCAT(CASE atd1.adt_ledger_id	
							WHEN 13 THEN CONCAT(account_ledger.ledgerName, ': ', booking.bkg_booking_id, ' (', booking.bkg_bcb_id ,')')
							WHEN 22 THEN CONCAT(account_ledger.ledgerName, ': ', booking_cab.bcb_id)
							ELSE account_ledger.ledgerName
						END SEPARATOR ', ') as ledgerNames,
					account_ledger.ledgerName entityType, atd.adt_amount ven_trans_amount,atd.adt_addt_params,
					IF(atd1.adt_ledger_id=22, MIN(bcbbkg.bkg_pickup_date), booking.bkg_pickup_date) bkg_pickup_date,
					CONCAT(admins.adm_fname, ' ', admins.adm_lname) AS adm_name,
					GROUP_CONCAT(concat(c1.cty_name, ' - ', c2.cty_name) SEPARATOR ' - ') from_city,
					account_ledger.ledgerId As lid
				FROM account_transactions act
				INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id 
					AND atd.adt_ledger_id=14 AND atd.adt_trans_ref_id IN ({$vndIds}) AND  adt_status=1 
					AND adt_active=1 AND act_status=1 
					AND act_active=1 $dateRange 
				INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id 
					AND ((abs(atd.adt_amount)<>atd.adt_amount AND atd1.adt_amount>0) OR (abs(atd.adt_amount)=atd.adt_amount 
						AND atd1.adt_amount<0))
				LEFT JOIN account_trans_details atd2 ON act.act_id = atd2.adt_trans_id 
					AND atd2.adt_ledger_id=31  
				INNER JOIN account_ledger ON atd1.adt_ledger_id=account_ledger.ledgerId $ledgersID
				LEFT JOIN booking ON (atd1.adt_ledger_id=13) AND atd1.adt_trans_ref_id=booking.bkg_id
				LEFT JOIN booking_invoice ON booking_invoice.biv_bkg_id=booking.bkg_id
				LEFT JOIN booking_cab ON  atd1.adt_trans_ref_id=booking_cab.bcb_id $and 
				LEFT JOIN booking bcbbkg ON bcbbkg.bkg_bcb_id = booking_cab.bcb_id AND booking_cab.bcb_active = 1
				LEFT JOIN `admins` ON admins.adm_id = act.act_user_id AND act.act_user_type = 4
				LEFT JOIN `cities` AS c1 ON c1.cty_id = if(atd1.adt_ledger_id=22, bcbbkg.bkg_from_city_id, booking.bkg_from_city_id)
				LEFT JOIN `cities` AS c2 ON c2.cty_id = if(atd1.adt_ledger_id=22, bcbbkg.bkg_to_city_id, booking.bkg_to_city_id)
				WHERE act_active = 1 AND act_date >= '2021-04-01 00:00:00' AND act_status=1 
				GROUP BY atd.adt_id ORDER BY act.act_date ASC) a
				JOIN 
				(SELECT @runningBal1 := ifNULL(SUM(adt_amount),0) openBalance FROM account_trans_details
					INNER JOIN account_transactions ON act_id=adt_trans_id
					WHERE 1 $actOpenDate 
						AND adt_ledger_id=14  AND adt_status=1 AND adt_active=1 AND act_status=1 AND act_active=1 
						AND act_date >= '2021-04-01 00:00:00' 
						AND adt_trans_ref_id IN ({$vndIds})  
					ORDER BY act_date,act_created ASC) r";
				
		$sqlCount = "SELECT  COUNT(DISTINCT atd1.adt_id) AS cnt
					FROM account_transactions act
					INNER JOIN account_trans_details atd ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 14 
							AND atd.adt_trans_ref_id IN ({$vndIds}) AND adt_active = 1 
					INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id 
							AND ((abs(atd.adt_amount) <> atd.adt_amount AND atd1.adt_amount > 0) OR (abs(atd.adt_amount) = atd.adt_amount AND atd1.adt_amount < 0)) AND atd1.adt_active=1
					WHERE act_active = 1 AND act_date >= '2021-04-01 00:00:00' $ledgersID $dateRange";

		
			$recordset	 = DBUtil::query($sql, DBUtil::SDB());
			$resultSet	 = [];
			$i			 = 0;
			foreach ($recordset as $val)
			{
				$resultSet[$i]						 = $val;
				$resultSet[$i]['penaltyRedeemFlag']	 = self::model()->checkFlagStatus($val['act_date'], $val['lid'], $val['ven_trans_amount'], $val['adt_addt_params']); // for checking penalty will be reeedem or not
				$i++;
			}

			return $resultSet;
		
	}
	/**
	 * function is used to create CSV data in API response
	 * @param type $vendorId
	 * @param type $fromDate
	 * @param type $toDate
	 * @param type $email
	 */
	public function GenerateLedgerXLS($vendorId, $fromDate, $toDate, $email = 0)
	{
		//$fromDate = date("d/m/Y", strtotime($fromDate));
		//$toDate = date("d/m/Y", strtotime($toDate));
		
		$data = AccountTransDetails::model()->getLedgerxls($vendorId, $fromDate, $toDate);
		

		header('Content-type: text/csv');
		header("Content-Disposition: attachment; filename=\"LedgerDetails" . date('Ymdhis') . ".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");
		/**$filename	 = "LedgerDetails_" . date('YmdHi') . ".csv";
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
		 $csv = $backup_file; 
		$handle= fopen($csv, 'w');*/ // this code is used to create file
		$file_pointer = fopen("php://output", 'w');// File pointer in writable mode 
		fputcsv($file_pointer, ['TransactionDate', 'Description','TransactionAmount', 'ReffId']);
		
	  // Traverse through the associative 
	  // array using for each loop 
	  foreach($data['vendorList'] as $vendor){ 
		
			$bkgcode		 = ($vendor['ledgerNames'] == NULL) ? "none" : trim($vendor['ledgerNames']);
			$bkgId			 = ($vendor['bkg_id'] == NULL) ? "none" : trim($vendor['bkg_id']);
			$drvCabDetails	 = ($vendor['bcb_cab_number'] != '' && $vendor['drv_name'] != '') ? $vendor['bcb_cab_number'] . " / " . $vendor['drv_name'] : "";
			$notes			 = (trim($vendor['ven_trans_remarks']) == '') ? '' : '(' . trim($vendor['ven_trans_remarks']) . ')';
			$advance		 = ($vendor['bkg_advance_amount'] == '') ? '0' : round($vendor['bkg_advance_amount']);
			$gozoAmount		 = ($vendor['gozo_amount'] == '') ? '0' : round($vendor['gozo_amount']);
			$netAmount		 = ($gozoAmount - $advance);
			$pickupDate		 = ($vendor['bkg_pickup_date'] == '' || date('d-m-Y', strtotime($vendor['bkg_pickup_date'])) == '01-01-1970') ? '-' : date('d-m-Y h:iA', strtotime($vendor['bkg_pickup_date']));
			$vendorCollected = ($vendor['bkg_vendor_collected'] == '') ? '0' : round($vendor['bkg_vendor_collected']);
			$transactionDate = ($vendor['act_date'] != '') ? date('d-m-Y', strtotime($vendor['act_date'])) : '';
			$toCities		 = ($vendor['from_city'] != '') ? $vendor['from_city'] : '';
			$rowArray['transactionDate']   = $transactionDate;
			$rowArray['Description']   = $bkgcode.''.$notes;
			$rowArray['transactionAmount']  = $vendor['ven_trans_amount'];
			//$rowArray['runningBalance']   = $vendor['runningBalance'];
			if($vendor['act_type']==1)
			{
				$type= "BookingId:".' '.$vendor['act_ref_id'];
			}
			else if($vendor['act_type']==5)
			{
				$type= "TripId:".' '.$vendor['act_ref_id'];
			}
			else
			{
				$type= "PartnerId:".' '.$vendor['act_ref_id'];
			}
			$rowArray['reffId']       = $type;
		  // Write the data to the CSV file 
		   $row1					 = array_values($rowArray);
		   fputcsv($file_pointer, $row1);
		 // fputcsv($file_pointer, $vendor); 
	  } 
		
		fclose($file_pointer);
		exit;
	}

	/**
	 * function used to  show PDF data
	 * @param type $vendorId
	 * @param type $fromDate
	 * @param type $toDate
	 * @param type $email
	 * @return string
	 */
	public function GenerateLedgerPdf($vendorId, $fromDate, $toDate, $email = 0)
	{
		//$fromDate = date("d/m/Y", strtotime($fromDate));
		//$toDate = date("d/m/Y", strtotime($toDate));
		$data = AccountTransDetails::model()->getLedgerPdf($vendorId, $fromDate, $toDate);
		#print_r($data['pdf']);exit;
		$vendorList					 = $data['vendorList'];
		$vendorAmount				 = $data['vendorAmount'];
		$openingAmount				 = $data['openingAmount'];
		$record						 = $data['record'];
		$fromDate					 = $data['fromDate'];
		$toDate						 = $data['toDate'];
		$html2pdf					 = $data['pdf'];
		$address					 = Config::getGozoAddress(Config::Corporate_address, true);
		$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
		$html2pdf->writeHTML($css, 1);
		$html2pdf->setAutoTopMargin	 = 'stretch';
		$html2pdf->setHTMLHeader('<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
                  <tbody>
                  <tr>
                  <td style="text-align: left"><img src="http://www.aaocab.com/images/print-logo.png" style="height: 60px"/><br><span style="font-weight: normal; font-size: 12pt">Gozo Technologies Private Limited</span></td>
                  <td style="text-align: right"><table class="borderless"><tr><td style="text-align: left; font-size: 10pt">
                  <strong>Corporate Office:</strong><br>
					' . $address . '
                  </td></tr></table></td>
                  </tr></tbody></table><hr>');
		$html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');
		$html2pdf->writeHTML($this->renderPartial('generateledgerpdf', array(
					'vendorList'	 => $vendorList,
					'vendorAmount'	 => $vendorAmount,
					'openingAmount'	 => $openingAmount,
					'record'		 => $record,
					'fromDate'		 => $fromDate,
					'toDate'		 => $toDate
						), true));
		if ($email == 1)
		{
			$filename		 = $vendorId . '-ledger-' . date('Y-m-d') . '.pdf';
			$fileBasePath	 = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors';
			if (!is_dir($fileBasePath))
			{
				mkdir($fileBasePath);
			}
			$filePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendorId;
			if (!is_dir($filePath))
			{
				mkdir($filePath);
			}
			$file = $filePath . DIRECTORY_SEPARATOR . $filename;
			$html2pdf->Output($file, 'F');
			return $file;
		}
		else
		{
			$html2pdf->Output();
		}
	}

}
