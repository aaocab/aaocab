<?php

/**
 * This is the model class for table "account_ledger".
 *
 * The followings are the available columns in table 'account_ledger':
 * @property string $ledgerId
 * @property string $accountGroupId
 * @property string $ledgerName
 * @property string $openingBalance
 * @property integer $isDefault
 * @property string $crOrDr
 * @property string $narration
 * @property string $mailingName
 * @property string $address
 * @property string $phone
 * @property string $mobile
 * @property string $email
 * @property integer $creditPeriod
 * @property string $creditLimit
 * @property string $pricinglevelId
 * @property integer $billByBill
 * @property string $tin
 * @property string $cst
 * @property string $pan
 * @property string $routeId
 * @property string $bankAccountNumber
 * @property string $branchName
 * @property string $branchCode
 * @property string $extraDate
 * @property string $extra1
 * @property string $extra2
 * @property string $areaId
 */
class AccountLedger extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'account_ledger';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('isDefault, creditPeriod, billByBill', 'numerical', 'integerOnly' => true),
			array('accountGroupId, pricinglevelId, routeId, areaId', 'length', 'max' => 20),
			array('openingBalance, creditLimit', 'length', 'max' => 18),
			array('ledgerName, crOrDr, narration, mailingName, address, phone, mobile, email, tin, cst, pan, bankAccountNumber, branchName, branchCode, extraDate, extra1, extra2', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ledgerId, accountGroupId, ledgerName, openingBalance, isDefault, crOrDr, narration, mailingName, address, phone, mobile, email, creditPeriod, creditLimit, pricinglevelId, billByBill, tin, cst, pan, routeId, bankAccountNumber, branchName, branchCode, extraDate, extra1, extra2, areaId', 'safe', 'on' => 'search'),
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
			'ledgerId'			 => 'Ledger',
			'accountGroupId'	 => 'Account Group',
			'ledgerName'		 => 'Ledger Name',
			'openingBalance'	 => 'Opening Balance',
			'isDefault'			 => 'Is Default',
			'crOrDr'			 => 'Cr Or Dr',
			'narration'			 => 'Narration',
			'mailingName'		 => 'Mailing Name',
			'address'			 => 'Address',
			'phone'				 => 'Phone',
			'mobile'			 => 'Mobile',
			'email'				 => 'Email',
			'creditPeriod'		 => 'Credit Period',
			'creditLimit'		 => 'Credit Limit',
			'pricinglevelId'	 => 'Pricinglevel',
			'billByBill'		 => 'Bill By Bill',
			'tin'				 => 'Tin',
			'cst'				 => 'Cst',
			'pan'				 => 'Pan',
			'routeId'			 => 'Route',
			'bankAccountNumber'	 => 'Bank Account Number',
			'branchName'		 => 'Branch Name',
			'branchCode'		 => 'Branch Code',
			'extraDate'			 => 'Extra Date',
			'extra1'			 => 'Extra1',
			'extra2'			 => 'Extra2',
			'areaId'			 => 'Area',
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

		$criteria->compare('ledgerId', $this->ledgerId, true);
		$criteria->compare('accountGroupId', $this->accountGroupId, true);
		$criteria->compare('ledgerName', $this->ledgerName, true);
		$criteria->compare('openingBalance', $this->openingBalance, true);
		$criteria->compare('isDefault', $this->isDefault);
		$criteria->compare('crOrDr', $this->crOrDr, true);
		$criteria->compare('narration', $this->narration, true);
		$criteria->compare('mailingName', $this->mailingName, true);
		$criteria->compare('address', $this->address, true);
		$criteria->compare('phone', $this->phone, true);
		$criteria->compare('mobile', $this->mobile, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('creditPeriod', $this->creditPeriod);
		$criteria->compare('creditLimit', $this->creditLimit, true);
		$criteria->compare('pricinglevelId', $this->pricinglevelId, true);
		$criteria->compare('billByBill', $this->billByBill);
		$criteria->compare('tin', $this->tin, true);
		$criteria->compare('cst', $this->cst, true);
		$criteria->compare('pan', $this->pan, true);
		$criteria->compare('routeId', $this->routeId, true);
		$criteria->compare('bankAccountNumber', $this->bankAccountNumber, true);
		$criteria->compare('branchName', $this->branchName, true);
		$criteria->compare('branchCode', $this->branchCode, true);
		$criteria->compare('extraDate', $this->extraDate, true);
		$criteria->compare('extra1', $this->extra1, true);
		$criteria->compare('extra2', $this->extra2, true);
		$criteria->compare('areaId', $this->areaId, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccountLedger the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function ledgerIds($ledger = '')
	{
		$enum = Array(
			'Cash'			 => 1,
			'Booking'		 => 13,
			'Operator'		 => 14,
			'Partner'		 => 15,
			'Paytm'			 => 16,
			'Mobikwik'		 => 17,
			'Freecharge'	 => 18,
			'Zaakpay'		 => 19,
			'Payu'			 => 20,
			'EBS'			 => 21,
			'Trip'			 => 22,
			'Bank'			 => 23,
			'Discount'		 => 24,
			'Cancellation'	 => 25,
			'PartnerCoins'	 => 26,
			'Compensation'	 => 27,
			'Penalty'		 => 28,
			'HDFC'			 => 29,
			'ICICI'			 => 30,
			'BankCharge'	 => 31,
			'Advance Tax'	 => 37,
			'RazorPay'		 => Accounting::LI_RAZORPAY,
			'PaytmApp'		 => 54,
			'TDS'			 => 55,
			'EaseBuzz'		 => 58
		);
		if ($ledger != '')
		{
			return $enum[$ledger];
		}
	}

	public static function getGozoPiadLedgerIds()
	{
		$sql		 = "SELECT account_ledger.ledgerId as ledgerId, account_ledger.ledgerName as ledgerName FROM `account_ledger` INNER JOIN account_group ON account_group.accountGroupId = account_ledger.accountGroupId WHERE account_group.accountGroupId IN (3,6,13,14,15,27,28,25) AND account_ledger.isDefault <> 0 AND account_ledger.ledgerId NOT IN(47,6,7,26,33,4,8,28)";
		$resultset	 = DBUtil::queryAll($sql);
		foreach ($resultset as $row)
		{
			$arrType[] = array("id" => $row['ledgerId'], "text" => $row['ledgerName']);
		}
		$data = CJSON::encode($arrType);
		return $data;
	}

	public static function getPaymentLedgers($bankCharge = true, $partnerCoins = true, $gozoCoins = false, $wallet = false)
	{
		$strCond = "";
		if (!$bankCharge)
		{
			$strCond = " AND ledgerId<>" . Accounting::LI_BANKCHARGE;
		}
		if (!$partnerCoins)
		{
			$strCond = " AND ledgerId<>" . Accounting::LI_PARTNERWALLET;
		}
		if (!$gozoCoins)
		{
			$strCond .= " AND ledgerId<>" . Accounting::LI_GOZOCOINS;
		}
		if (!$wallet)
		{
			$strCond .= " AND ledgerId<>" . Accounting::LI_WALLET;
		}

		$sql		 = "SELECT account_ledger.ledgerId as ledgerId, account_ledger.ledgerName as ledgerName FROM `account_ledger` INNER JOIN account_group ON account_group.accountGroupId = account_ledger.accountGroupId WHERE account_group.accountGroupId IN (27,28) AND account_ledger.ledgerId NOT IN(33,26) AND account_ledger.isDefault <> 0" . $strCond;
		$resultset	 = DBUtil::queryAll($sql);
		foreach ($resultset as $row)
		{
			$arrType[] = array("id" => $row['ledgerId'], "text" => $row['ledgerName']);
		}
		$data = CJSON::encode($arrType);
		return $data;
	}

	public static function getGozoReceiverLedgerIds()
	{
		$sql		 = "SELECT account_ledger.ledgerId as ledgerId, account_ledger.ledgerName as ledgerName FROM `account_ledger` INNER JOIN account_group ON account_group.accountGroupId = account_ledger.accountGroupId WHERE account_group.accountGroupId IN (6,27,14,3,15,28,13,25) AND account_ledger.ledgerId NOT IN(7,12,6,26,33,27,24,43) AND  account_ledger.isDefault <> 0";
		$resultset	 = DBUtil::queryAll($sql);
		foreach ($resultset as $row)
		{
			$arrType[] = array("id" => $row['ledgerId'], "text" => $row['ledgerName']);
		}
		$data = CJSON::encode($arrType);
		return $data;
	}

	public static function getLedgerIds()
	{
		$sql		 = "SELECT account_ledger.ledgerId as ledgerId, account_ledger.ledgerName as ledgerName FROM `account_ledger` INNER JOIN account_group ON account_group.accountGroupId = account_ledger.accountGroupId WHERE account_group.accountGroupId IN (3,6,13,14,15,27,28) AND account_ledger.isDefault <> 0 ";
		$resultset	 = DBUtil::queryAll($sql);
		foreach ($resultset as $row)
		{
			$arrType[] = array("id" => $row['ledgerId'], "text" => $row['ledgerName']);
		}
		$data = CJSON::encode($arrType);
		return $data;
	}

	public static function getAllLedgerIds()
	{
		$sql		 = "SELECT
                                    account_ledger.ledgerId AS ledgerId,
                                    account_ledger.ledgerName AS ledgerName
                                FROM
                                    `account_ledger`
                                INNER JOIN account_group ON account_group.accountGroupId = account_ledger.accountGroupId
                                WHERE 
                                     account_ledger.isDefault <> 0";
		$resultset	 = DBUtil::queryAll($sql);
		foreach ($resultset as $row)
		{
			$arrType[] = array("id" => $row['ledgerId'], "text" => $row['ledgerName']);
		}
		$data = CJSON::encode($arrType);
		return $data;
	}

	public static function getRefundLedgerIds()
	{
		$sql		 = "SELECT account_ledger.ledgerId as ledgerId, account_ledger.ledgerName as ledgerName FROM `account_ledger` INNER JOIN account_group ON account_group.accountGroupId = account_ledger.accountGroupId WHERE  account_ledger.ledgerId IN(16,20,23,29,30,53,58) AND  account_ledger.isDefault <> 0";
		$resultset	 = DBUtil::queryAll($sql);
		foreach ($resultset as $row)
		{
			$arrType[] = array("id" => $row['ledgerId'], "text" => $row['ledgerName']);
		}
		$data = CJSON::encode($arrType);
		return $data;
	}

	public function getById($id = "")
	{
		if ($id != '')
		{
			$where = "WHERE ledgerId = $id";
		}

		$sql	 = "select ledgerName FROM account_ledger $where ";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB());
		return $data['ledgerName'];
	}

	public static function getAllLedgerId($ledgerId = '')
	{
		$sql		 = "SELECT
                                    account_ledger.ledgerId AS ledgerId,
                                    account_ledger.ledgerName AS ledgerName
                                FROM
                                    `account_ledger`
                                ";
		$resultset	 = DBUtil::query($sql);
		foreach ($resultset as $row)
		{
			$ledger[$row['ledgerId']] = $row['ledgerName'];
		}

		return $ledger;
	}

	public static function getCompensationLedgerIds()
	{
		$sql		 = "SELECT account_ledger.ledgerId as ledgerId, account_ledger.ledgerName as ledgerName
						FROM `account_ledger`
						WHERE  account_ledger.ledgerId IN(47,23,29,30,36) AND  account_ledger.isDefault <> 0";
		$resultset	 = DBUtil::query($sql);
		foreach ($resultset as $row)
		{
			$arrType[] = array("id" => $row['ledgerId'], "text" => $row['ledgerName']);
		}
		$data = CJSON::encode($arrType);
		return $data;
	}

}
