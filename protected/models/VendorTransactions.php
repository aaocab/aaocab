<?php

/**
 * This is the model class for table "vendor_transactions".
 *
 * The followings are the available columns in table 'vendor_transactions':
 * @property integer $ven_trans_id
 * @property integer $ven_ptp_id
 * @property integer $trans_vendor_id
 * @property integer $ven_booking_id
 * @property integer $ven_trip_id
 * @property string $ven_trans_code
 * @property integer $ven_trans_mode
 * @property integer $ven_trans_type
 * @property string $ven_trans_remarks
 * @property double $ven_trans_amount
 * @property double $ven_tds_amount
 * @property integer $ven_trans_active
 * @property string $ven_trans_created
 * @property integer $ven_admin_id
 * @property string $ven_trans_response_details
 * @property string $ven_trans_response_code
 * @property string $ven_trans_response_message
 * @property string $ven_trans_txn_id
 * @property integer $ven_trans_status
 * @property string $ven_trans_complete_date
 * @property string $ven_trans_date
 */
class VendorTransactions extends CActiveRecord
{

	const GOZO_PAID_OPERATOR	 = 1;
	const OPERATOR_PAID_GOZO	 = -1;

	public $bank_chq_no, $bank_chq_dated, $bank_name, $bank_ifsc, $bank_branch, $ven_from_date, $ven_to_date, $ven_date_type, $ven_operator_id;
	public $ven_is_invoice;
	public $bankTransType	 = [1 => 'Cash', 2 => 'Cheque', 3 => 'NEFT/RTGS'];
	public $modeList		 = [1 => 'Debit', 2 => 'Credit'];
	public $operatorList	 = [0 => 'Operator Paid Gozo', 1 => 'Gozo Paid Operator', 2 => 'Gozo Lend To Operator', 3 => 'Operator Lend To Gozo'];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_transactions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ven_trans_amount, ven_ptp_id', 'required', 'on' => 'create'),
			array('ven_trans_amount, ven_ptp_id, ven_trans_date, ven_trans_remarks', 'required', 'on' => 'vendor_transaction_insert'),
			array('ven_trans_remarks', 'required', 'on' => 'vendor_notes'),
			array('trans_vendor_id,ven_from_date,ven_to_date', 'required', 'on' => 'transaction_search'),
			array('ven_from_date,ven_to_date', 'required', 'on' => 'search2'),
			// array('ven_trans_amount', 'required'),
			// ['ven_trans_amount, ven_ptp_id', 'required', 'on' => 'create'],
			// ['ven_trans_amount, ven_ptp_id, ven_trans_remarks', 'required', 'on' => 'vendor_transaction_insert'],
			array('ven_ptp_id, trans_vendor_id, ven_booking_id, ven_trans_mode,ven_trans_type,ven_trip_id, ven_trans_active', 'numerical', 'integerOnly' => true),
			array('ven_trans_amount, ven_tds_amount', 'numerical'),
			array('ven_trans_code', 'length', 'max' => 50),
			array('ven_trans_remarks', 'length', 'max' => 200),
			array('ven_trans_response_details', 'length', 'max' => 5000),
			array('ven_trans_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ven_trans_id, ven_ptp_id, trans_vendor_id, ven_tds_amount, ven_booking_id,ven_trip_id, ven_trans_code, ven_trans_mode,ven_trans_type, ven_trans_remarks, ven_trans_amount, ven_trans_active, ven_trans_created, ven_trans_response_details, ven_trans_date,bank_chq_no,bank_chq_dated,bank_name,bank_ifsc,bank_branch,ven_admin_id,ven_from_date,ven_to_date,ven_date_type,ven_operator_id,ven_is_invoice, ven_trans_response_code, ven_trans_response_message, ven_trans_txn_id, ven_trans_status, ven_trans_complete_date', 'safe', 'on' => 'search'),
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
			'ven_trans_id'				 => 'Ven Trans',
			'ven_ptp_id'				 => 'Payment Type',
			'trans_vendor_id'			 => 'Vendor',
			'ven_booking_id'			 => 'Booking',
			'ven_trans_code'			 => 'Trans Code',
			'ven_trans_mode'			 => 'Payment Mode',
			'ven_trans_remarks'			 => 'Remarks',
			'ven_trans_amount'			 => 'Vendor Amount',
			'ven_tds_amount'			 => 'Vendor TDS',
			'ven_trans_active'			 => 'Trans Active',
			'ven_trans_created'			 => 'Trans Created',
			'ven_trans_response_details' => 'Trans Response Details',
			'ven_trans_date'			 => 'Trans Date',
			'ven_from_date'				 => 'From Date',
			'ven_to_date'				 => 'To Date',
			'ven_date_type'				 => '',
			'ven_is_invoice'			 => 'Send Invoice',
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

		$criteria->compare('ven_trans_id', $this->ven_trans_id);
		$criteria->compare('ven_ptp_id', $this->ven_ptp_id);
		$criteria->compare('trans_vendor_id', $this->trans_vendor_id);
		$criteria->compare('ven_booking_id', $this->ven_booking_id);
		$criteria->compare('ven_trans_code', $this->ven_trans_code, true);
		$criteria->compare('ven_trans_mode', $this->ven_trans_mode);
		$criteria->compare('ven_trans_remarks', $this->ven_trans_remarks, true);
		$criteria->compare('ven_trans_amount', $this->ven_trans_amount);
		$criteria->compare('ven_trans_active', $this->ven_trans_active);
		$criteria->compare('ven_trans_created', $this->ven_trans_created, true);
		$criteria->compare('ven_trans_response_details', $this->ven_trans_response_details, true);
		$criteria->compare('ven_trans_date', $this->ven_trans_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
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

	public function getByCode($code)
	{
		if ($code)
		{
			$criteria	 = new CDbCriteria();
			$criteria->compare('ven_trans_code', $code);
			$vTransModel = $this->find($criteria);
			return $vTransModel;
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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorTransactions the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function vendorAccount($date1 = '', $date2 = '', $vendorId = '', $type = 'data')
	{

		if ($date1 != '' && $date2 != '')
		{
			if ($type == 'data')
			{
				$fromDate	 = DateTimeFormat::DatePickerToDate($date1);
				$toDate		 = DateTimeFormat::DatePickerToDate($date2);
			}
			else
			{
				$fromDate	 = $date1;
				$toDate		 = $date2;
			}
		}
		$status	 = [3, 5, 6, 7];
		$status	 = implode(',', $status);
		$sql1	 = '';
		if (($date1 != '' && $date1 != '1970-01-01') && ($date2 != '' && $date2 != '1970-01-01'))
		{
			$sql1 .= " AND date(ven_trans_date) BETWEEN '$fromDate' AND '$toDate'";
		}
		$sql = "SELECT c.vnd_name,c.vnd_id,c.vnd_email,c.vnd_owner,
            current_payable,vendorAmount,ven_tds_amount,
            c.vnd_total_trips as vendorTotalTrips, countTrips,trans_amount,
            pastDues , c.vnd_overall_rating as vendor_rating,c.vnd_credit_limit as credit_limit
                FROM (
                    SELECT trans_vendor_id, SUM(ven_trans_amount) as current_payable, round(sum(ven_tds_amount),2) as ven_tds_amount  FROM `vendor_transactions`
                    WHERE 1=1 AND vendor_transactions.ven_trans_active=1 AND vendor_transactions.ven_trans_status = 1 $sql1 GROUP BY trans_vendor_id
                ) a
                RIGHT JOIN `vendors` as c ON a.trans_vendor_id=c.vnd_id
                LEFT JOIN (
                    SELECT trans_vendor_id, SUM(ven_trans_amount) as trans_amount
                    FROM `vendor_transactions`
                    WHERE 1=1 AND vendor_transactions.ven_trans_active=1 AND vendor_transactions.ven_trans_status = 1
                    GROUP BY trans_vendor_id
                )trans ON trans.trans_vendor_id=c.vnd_id
                LEFT JOIN
                (
                    SELECT  sum(bkg_vendor_amount) as vendorAmount, ven.trans_vendor_id
                    FROM `booking`
                    INNER JOIN
                    (
                        SELECT distinct ven_trip_id,trans_vendor_id  FROM vendor_transactions
                        WHERE date(ven_trans_date) BETWEEN '$fromDate' AND '$toDate'
                        AND ven_trip_id IS NOT NULL
                        GROUP BY ven_trip_id
                    ) ven ON ven.ven_trip_id = bkg_bcb_id
                    WHERE bkg_status IN ($status)
                    group by  ven.trans_vendor_id
                )ven1 ON ven1.trans_vendor_id=c.vnd_id
                LEFT JOIN
                (
                    SELECT bcb_vendor_id, COUNT(DISTINCT bcb_id) as countTrips
                    FROM  `booking_cab` WHERE bcb_active=1
                    AND  bcb_id IN  (
                        SELECT bkg_id FROM booking WHERE  booking.bkg_status IN ($status)
                    ) GROUP BY booking_cab.bcb_vendor_id
                ) bcb ON bcb_vendor_id=c.vnd_id
                LEFT JOIN (
                    SELECT trans_vendor_id,SUM(ven_trans_amount) as pastDues
                    FROM `vendor_transactions`
                    WHERE date(ven_trans_date)<'$fromDate'
                    GROUP BY `trans_vendor_id`
                ) d ON d.trans_vendor_id=c.vnd_id
                WHERE 1=1
                AND c.vnd_active IN (1,2)
                AND c.vnd_name IS NOT NULL";


		if ($vendorId != '' && $vendorId != '0')
		{
			$sql .= " AND c.vnd_id IN (" . $vendorId . ")";
		}

		if ($type == 'data')
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['vnd_name', 'vendorAmount', 'ven_tds_amount', 'vendorTotalTrips', 'current_payable', 'pastDues', 'credit_limit'],
					'defaultOrder'	 => 'current_payable DESC'], 'pagination'	 => ['pageSize' => 25],
			]);
			return $dataprovider;
		}
		else if ($type == 'command')
		{
			$recordSet = DBUtil::queryAll($sql);
			return $recordSet;
		}
	}

	public function vendorTransactionList($vendorId, $fromDate = '', $toDate = '', $vendorAmt = '0', $orderBy = 'DESC')
	{

		$sql = "SELECT vendor_transactions.ven_trans_remarks,
                vendor_transactions.ven_trans_amount,
                vendor_transactions.ven_trans_mode,
                vendor_transactions.ven_tds_amount as tds_amount,
                booking.bkg_user_name,
                booking.bkg_user_lname,
                GROUP_CONCAT(booking.bkg_id SEPARATOR ', ') as bkg_id,
                group_concat(booking.bkg_booking_id SEPARATOR ', ') bkg_booking_id,
                 group_concat(concat(c1.cty_name,' - ',c2.cty_name) SEPARATOR ' - ') from_city,
                vendor_transactions.ven_trip_id,
                booking.bkg_pickup_date,
                sum(booking.bkg_base_amount) bkg_base_amount,
                sum(booking.bkg_total_amount) bkg_total_amount,
                round((sum(booking.bkg_advance_amount) + sum(IFNULL(booking.bkg_credits_used,0)) - sum(IFNULL(booking.bkg_refund_amount,0)))) as advance_amount,

                c2.`cty_name` as to_city,
                DATE_FORMAT(vendor_transactions.ven_trans_date,'%Y-%m-%d') ven_trans_date,
                DATE_FORMAT(vendor_transactions.ven_trans_created,'%Y-%m-%d') ven_trans_created,
                vendors.vnd_name,concat(admins.adm_fname,' ',admins.adm_lname) as adm_name,
                bcb2.bcb_cab_number,
                bcb2.bcb_vendor_amount as vendor_amount,
                bcb2.bcb_driver_name as drv_name,
                sum(IFNULL(booking.bkg_vendor_collected,0)) as bkg_vendor_collected
                FROM `vendor_transactions`
                LEFT JOIN `booking_cab` bcb2 ON bcb2.bcb_id=vendor_transactions.ven_trip_id AND bcb2.bcb_active=1
                LEFT JOIN `booking` ON booking.bkg_bcb_id=bcb2.bcb_id AND booking.bkg_status IN (6,7)

                LEFT JOIN `cities` as c1 ON c1.cty_id=booking.bkg_from_city_id
                LEFT JOIN `cities` as c2 ON c2.cty_id=booking.bkg_to_city_id
                LEFT JOIN `vendors` ON vendors.vnd_id=bcb2.bcb_vendor_id
                LEFT JOIN `admins` ON admins.adm_id=vendor_transactions.ven_admin_id
                WHERE 1=1
                AND
                    vendor_transactions.ven_trans_active = 1 AND vendor_transactions.ven_trans_status = 1
                ";
		if (($fromDate != '' && $fromDate != '1970-01-01') && ($toDate != '' && $toDate != '1970-01-01'))
		{
			if ($vendorAmt == '0')
			{
				$fromDate	 = DateTimeFormat::DatePickerToDate($fromDate);
				$toDate		 = DateTimeFormat::DatePickerToDate($toDate);
			}
			//$sql .= " AND  date(trans.ven_trans_date) BETWEEN '$fromDate' AND '$toDate'";
			$sql .= " AND date(vendor_transactions.ven_trans_date) BETWEEN '$fromDate' AND '$toDate'";
		}
		if ($vendorAmt == '0')
		{
			$sql .= "  AND (vendor_transactions.ven_trans_amount<>0)";
		}
		if ($vendorId != '0')
		{
			$sql .= " AND vendor_transactions.trans_vendor_id=" . $vendorId;
		}
		$sql .= "  GROUP BY vendor_transactions.trans_vendor_id,vendor_transactions.ven_trans_id
                   ORDER BY vendor_transactions.`ven_trans_date` $orderBy, vendor_transactions.`ven_trans_created` $orderBy";

		$recordSet = DBUtil::queryAll($sql);
		return $recordSet;
	}

	public function generatePdf($fromDate, $toDate, $vendorId)
	{

		$record			 = Vendors::model()->getDrillDownInfo($vendorId);
		$vendorAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId, '', $toDate);
		$openingAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId, '', '', $fromDate);
		$vendorList		 = AccountTransDetails::model()->vendorTransactionList($vendorId, $fromDate, $toDate, '0', 'ASC');
		//$vendorList = new CArrayDataProvider($recordSet, array('pagination' => array('pageSize' => $pageSize),));
		//$vendorModels = $vendorList->getData();
		//        /* @var $html2pdf \Spipu\Html2Pdf\Html2Pdf */
		$html2pdf		 = Yii::app()->ePdf->mPdf();
		$data			 = ['record'		 => $record,
			'vendorAmount'	 => $vendorAmount,
			'openingAmount'	 => $openingAmount,
			'vendorList'	 => $vendorList,
			'pdf'			 => $html2pdf,
			'fromDate'		 => $fromDate,
			'toDate'		 => $toDate];
		return $data;
	}

	public function getLedgerData($vendorId, $fromDate, $toDate)
	{
		$record			 = Vendors::model()->getDrillDownInfo($vendorId);
		$vendorAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId, '', $toDate);
		$openingAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId, '', '', $fromDate);
		$transactionList = AccountTransDetails::model()->vendorTransactionList($vendorId, $fromDate, $toDate, '0', 'ASC');

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

	public function bulkReport($pagination = '', $type = '')
	{
		$size	 = ($pagination != '') ? $pagination : 10;
		$sql	 = "SELECT vendors.vnd_id, vendors.vnd_name AS vendor_name,vendors.vnd_phone as vendorPhone,
                vendors.vnd_email as vendorEmail,vendors.vnd_overall_rating as vendorRating,vendors.vnd_total_trips as vendorTotalTrips,
                SUM(vendor_transactions.ven_trans_amount) AS vendor_amount,vendors.vnd_credit_limit,countFlag
                FROM  vendors
                LEFT JOIN vendor_transactions ON vendors.vnd_id=vendor_transactions.trans_vendor_id  AND vendor_transactions.ven_trans_active = 1 AND vendor_transactions.ven_trans_status = 1
                LEFT JOIN (
                        SELECT booking_cab.bcb_vendor_id,COUNT(1) as countFlag
                        FROM booking
                        INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
                        WHERE booking.bkg_status IN (3,5,6,7) AND booking.bkg_active=1 AND booking.bkg_account_flag=1
                        GROUP BY booking_cab.bcb_vendor_id
                ) a ON a.bcb_vendor_id=vendors.vnd_id
                WHERE 1=1
                AND ven_trans_active = 1 AND ven_trans_status = 1
                AND vendors.vnd_id IS NOT NULL AND vendors.vnd_active IN (1)
                GROUP BY vendors.vnd_id";
		if ($type == 'command')
		{
			return DBUtil::queryAll($sql);
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['vendor_name', 'vendor_amount', 'countFlag', 'vendorTotalTrips', 'vnd_credit_limit', 'vendorPhone', 'vendorEmail', 'vendorRating'],
					'defaultOrder'	 => 'vendor_name ASC'], 'pagination'	 => ['pageSize' => $size],
			]);
			return $dataprovider;
		}
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
		$cdb->where	 = 'date(ven_trans_created) = CURDATE()';
		$cnt		 = $cdb->queryScalar();
		return $cnt;
	}

	public function getLastEntrybyBkgid($bkgid)
	{
		$criteria		 = new CDbCriteria;
		$criteria->compare('ven_booking_id', $bkgid);
		$criteria->order = "ven_trans_date DESC, ven_trans_id DESC";
		$criteria->limit = 1;
		return $this->find($criteria);
	}

	//reFactor Done
	public function getNetDueByTripnBkgID($bkgid = '', $bcbid = '')
	{

		$whereArr = [];
		if ($bkgid != '')
		{
			$whereArr[] = " ven_booking_id= $bkgid ";
		}
		if ($bcbid != '')
		{
			$whereArr[] = " ven_trip_id= $bcbid ";
		}
		$where	 = implode(' OR ', $whereArr);
		$sql	 = "SELECT max(ven_trans_id) ven_trans_id, ven_booking_id,ven_trip_id, trans_vendor_id,
            max(ven_trans_date) as transDate, (
            SELECT  SUM(ven_trans_amount) FROM vendor_transactions  WHERE ( $where )) as netAmount,
            SUM(ven_tds_amount) as tdsAmount FROM vendor_transactions
                    WHERE ( $where ) AND ven_trans_active = 1 AND ven_trans_status = 1
                    GROUP BY ven_trip_id,ven_booking_id, trans_vendor_id";


		return DBUtil::queryAll($sql);
	}

	public function getAdjustableAmount($vendorId, $fromDate, $toDate)
	{
		$fromDate	 = DateTimeFormat::DatePickerToDate($fromDate);
		$toDate		 = DateTimeFormat::DatePickerToDate($toDate);
		$sql		 = "SELECT  SUM(IF((adt_amount<0),adt_amount,'0')) as adjust_amount,
                        SUM(adt_amount) as transaction_amount
                        FROM account_trans_details adt
                        LEFT JOIN account_transactions act ON act.act_id = adt.adt_trans_id
                        WHERE act.act_active=1 AND adt.adt_ledger_id IN(14) AND adt.adt_trans_ref_id=$vendorId AND adt.adt_type=2 AND adt.adt_status=1 AND adt.adt_active=1 AND date(act.act_date) BETWEEN '$fromDate' AND '$toDate' ";

		return DBUtil::queryRow($sql);
	}

	

	public function add($bkgId, $user = 0)
	{
		$model		 = Booking::model()->findByPk($bkgId);
		$cabmodel	 = $model->getBookingCabModel();
		$success	 = false;
		if (($cabmodel->getLowestBookingStatus() == 7 || $cabmodel->getLowestBookingStatus() == 6) && $cabmodel->bcb_pending_status == 0)
		{
			$amountsbyTrip		 = $cabmodel->getTripTotalBookingAmounts();
			$tripVendorAmount	 = ($cabmodel->bcb_vendor_amount > 0) ? $cabmodel->bcb_vendor_amount : $amountsbyTrip['bkg_vendor_amount'];
			$gozoTripAmount		 = $amountsbyTrip['bkg_total_amount'] - $tripVendorAmount;
			$gozoTripAdvance	 = $amountsbyTrip['bkg_advance_amount'] + $amountsbyTrip['bkg_credits_used'] - $amountsbyTrip['bkg_refund_amount'];
			// $gozoTripDue = $gozoTripAmount - $gozoTripAdvance;
			$gozoTripDue		 = $amountsbyTrip['bkg_vendor_collected'] - $cabmodel->bcb_vendor_amount;

			$paymentGateway						 = new PaymentGateway();
			$paymentGateway->apg_booking_id		 = $model->bkg_id;
			$paymentGateway->apg_acc_trans_type	 = Accounting::AT_BOOKING;
			$paymentGateway->apg_trans_ref_id	 = $model->bkg_id;
			$paymentGateway->apg_ptp_id			 = $paymentType;
			$paymentGateway->apg_amount			 = $amount;
			$paymentGateway->apg_remarks		 = "Payment Initiated";
			$paymentGateway->apg_ref_id			 = '';
			$paymentGateway->apg_user_type		 = UserInfo::TYPE_CONSUMER;
			$paymentGateway->apg_user_id		 = $model->bkg_user_id;
			$paymentGateway->apg_status			 = 0;
			$paymentGateway->apg_date			 = new CDbExpression('NOW()');
			$bankLedgerId						 = PaymentType::model()->ledgerList($paymentType);
			$paymentGateway						 = $paymentGateway->payment($bankLedgerId);
		}
	}

	public function addPendingTransactionDataModify($bkid)
	{
		$model		 = Booking::model()->findByPk($bkid);
		$cabmodel	 = $model->getBookingCabModel();
		$success	 = false;
		if (($model->bkg_status == 7 || $model->bkg_status == 6 || $model->bkg_status == 5) || $cabmodel->bcb_pending_status == 1)
		{
			$amountsbyTrip					 = $cabmodel->getTripTotalBookingAmounts();
			$tripVendorAmount				 = ($cabmodel->bcb_vendor_amount > 0) ? $cabmodel->bcb_vendor_amount : $amountsbyTrip['bkg_vendor_amount'];
			$gozoTripAmount					 = $amountsbyTrip['bkg_total_amount'] - $tripVendorAmount;
			$gozoTripAdvance				 = $amountsbyTrip['bkg_advance_amount'] + $amountsbyTrip['bkg_credits_used'] - $amountsbyTrip['bkg_refund_amount'];
			$gozoTripDue					 = $gozoTripAmount - $gozoTripAdvance;
			$vTransModel					 = new VendorTransactions();
			$vTransModel->ven_ptp_id		 = PaymentType::TYPE_CASH;
			$vTransModel->trans_vendor_id	 = $cabmodel->bcb_vendor_id;
			$vTransModel->ven_trip_id		 = $model->bkg_bcb_id;
			$vTransModel->ven_trans_code	 = $vTransModel->generateTranscode();
			$vTransModel->ven_trans_mode	 = ($gozoTripDue < 0) ? 1 : 2;
			$vTransModel->ven_trans_amount	 = $gozoTripDue;
			$tripEndDatetime				 = $cabmodel->getTripEndDatetime();
			$vTransModel->ven_trans_date	 = $tripEndDatetime;
			$vTransModel->ven_admin_id		 = Yii::app()->user->getId();
			$vTransModel->scenario			 = 'create';
			if ($vTransModel->validate())
			{
				$vTransModel->save();
				$success = true;
			}
		}

		return $success;
	}

	public function udpdateResponseByCode($response, $success = 0)
	{

		$responseArr = [];
		$responseArr = json_decode($response, true);
		if ($this->ven_ptp_id == PaymentType::TYPE_PAYTM)
		{
			$this->ven_trans_response_code		 = $responseArr['RESPCODE'];
			$this->ven_trans_response_message	 = $responseArr['RESPMSG'];
			$this->ven_trans_txn_id				 = $responseArr['TXNID'];
			$this->ven_trans_remarks			 = "Online payment received (Transaction ID: " . $this->ven_trans_code . ")";
		}
		if ($this->ven_ptp_id == PaymentType::TYPE_PAYUMONEY)
		{
			$this->ven_trans_response_code		 = $responseArr['error'];
			$errorMsg1							 = ($responseArr['error_Message'] == '') ? '' : ', ' . $responseArr['error_Message'];
			$errorMsg							 = ($responseArr['result'][0]['status']) ? $responseArr['result'][0]['status'] : $responseArr['message'];
			$this->ven_trans_response_message	 = $responseArr['field9'] . $errorMsg . $errorMsg1 . $responseArr['DESCRIPTION'];
			$this->ven_trans_txn_id				 = $responseArr['payuMoneyId'] . $responseArr['result'][0]['paymentId'];
			$this->ven_trans_remarks			 = "Online payment received (Transaction ID: " . $this->ven_trans_code . ")";
		}
		$this->ven_trans_response_details	 = $response;
		$this->ven_trans_status				 = $success;
		$this->ven_trans_complete_date		 = new CDbExpression('NOW()');
		if ($this->save())
		{
			if ($this->ven_trans_status == 1)
			{
				$this->addProcessingCharge($this->trans_vendor_id, $this->ven_trans_amount, $this->ven_trans_code);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

}
