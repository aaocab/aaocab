<?php

class PayController extends Controller
{

	public $layout = 'pay_column2';

	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete,pickup,rates', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('register', 'regstatus', 'transaction', 'transtatus',
					'accstatement', 'balanceinq', 'list'),
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionRegister()
	{

		$regparams		 = ['AGGRNAME',
			'AGGRID',
			'CORPID',
			'USERID',
			'URN'];
		$title			 = 'New Registration';
		$this->pageTitle = $title;
		$arr			 = Yii::app()->request->getParam('Pay');
		if (isset($arr))
		{
			$url		 = ICICIIB::getRegistrationUrl();
			$response	 = Yii::app()->icici->trans($arr, $url);
			$this->renderAuto('response', ['responseText' => $response->response]);
			Yii::app()->end();
		}
		$paramList = Yii::app()->icici->loadCommon($regparams);

		$this->renderAuto('form', ['model' => $paramList, 'title' => $title]);
	}

	public function actionRegstatus()
	{

		$regparams = ['AGGRNAME',
			'AGGRID',
			'CORPID',
			'USERID',
			'URN'];

		$title			 = 'Registration Status';
		$this->pageTitle = $title;
		$arr			 = Yii::app()->request->getParam('Pay');
		if (isset($arr))
		{
			$url		 = ICICIIB::getRegistrationStatusUrl();
			$response	 = Yii::app()->icici->trans($arr, $url);
			$this->renderAuto('response', ['responseText' => $response->response]);
			Yii::app()->end();
		}
		$paramList = Yii::app()->icici->loadCommon($regparams);

		$this->renderAuto('form', ['model' => $paramList, 'title' => $title]);
	}

	public function actionTransaction()
	{



		$txnparams = [
			'AGGRID',
			'AGGRNAME',
			'CORPID',
			'USERID',
			'URN',
			'UNIQUEID',
			'DEBITACC',
			'CREDITACC',
			'IFSC',
			'AMOUNT',
			'REMARKS',
			'CURRENCY',
			'TXNTYPE',
			'PAYEENAME'];



		$title = 'New Transaction';

		$this->pageTitle = $title;
		$arr			 = Yii::app()->request->getParam('Pay');
		if (isset($arr))
		{

			$added = OnlineBanking::addNew($arr);
			if ($added)
			{
				$uniqueId	 = $arr['UNIQUEID'];
				$url		 = ICICIIB::getTransactionUrl();
				$response	 = Yii::app()->icici->trans($arr, $url);

				$updated = OnlineBanking::updateStatusByUniqueId($uniqueId, $response);
				$this->renderAuto('response', ['responseText' => $response->response]);
				Yii::app()->end();
			}
		}
		$paramList				 = Yii::app()->icici->loadCommon($txnparams);
		$paramList['UNIQUEID']	 = round(microtime(true) * 1000);

		$this->renderAuto('form', ['model' => $paramList, 'title' => $title]);
	}

	public function actionAccstatement()
	{

		$txnparams	 = ['AGGRID',
			'CORPID',
			'USERID',
			'ACCOUNTNO',
			'FROMDATE',
			'TODATE',
			'URN'
		];
		$title		 = 'Account Statement';

		$this->pageTitle = $title;

		$arr = Yii::app()->request->getParam('Pay');
		if (isset($arr))
		{
			$url		 = ICICIIB::getAccountStatementUrl();
			$response	 = Yii::app()->icici->trans($arr, $url, true);
//			$response->response	 = '{
//  "URN": "SR186122666",
//  "AGGR_ID": "CUST0286",
//  "USER_ID": "CIBTESTING6",
//  "CORP_ID": "CIBNEXT",
//  "Record": [
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "100.00",
//      "CHEQUENO": "",
//      "TXNDATE": "24-10-2017 18:09:15",
//      "REMARKS": "ATM\/CASH WDL\/24-10-17\/0",
//      "TRANSACTIONID": "S27788478",
//      "TYPE": "DR",
//      "BALANCE": "55,558.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "2,600.00",
//      "CHEQUENO": "",
//      "TXNDATE": "24-10-2017 18:10:21",
//      "REMARKS": "ATM\/CASH WDL\/24-10-17\/0",
//      "TRANSACTIONID": "S27788480",
//      "TYPE": "DR",
//      "BALANCE": "52,958.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "2,000.00",
//      "CHEQUENO": "",
//      "TXNDATE": "24-10-2017 18:13:15",
//      "REMARKS": "ATM\/CASH WDL\/24-10-17\/0",
//      "TRANSACTIONID": "S27788482",
//      "TYPE": "DR",
//      "BALANCE": "50,958.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "1,200.00",
//      "CHEQUENO": "",
//      "TXNDATE": "24-10-2017 18:14:29",
//      "REMARKS": "ATM\/CASH WDL\/24-10-17\/0",
//      "TRANSACTIONID": "S27788483",
//      "TYPE": "DR",
//      "BALANCE": "49,758.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "1,200.00",
//      "CHEQUENO": "",
//      "TXNDATE": "24-10-2017 18:14:57",
//      "REMARKS": "ATM\/WDL RVSL\/24-10-17\/R\/000405001611",
//      "TRANSACTIONID": "S27788485",
//      "TYPE": "CR",
//      "BALANCE": "50,958.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "5,000.00",
//      "CHEQUENO": "",
//      "TXNDATE": "24-10-2017 18:15:41",
//      "REMARKS": "ATM\/CASH WDL\/24-10-17\/0",
//      "TRANSACTIONID": "S27788486",
//      "TYPE": "DR",
//      "BALANCE": "45,958.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "5,000.00",
//      "CHEQUENO": "",
//      "TXNDATE": "24-10-2017 18:18:45",
//      "REMARKS": "ATM\/CASH WDL\/24-10-17\/0",
//      "TRANSACTIONID": "S27788490",
//      "TYPE": "DR",
//      "BALANCE": "40,958.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "5,200.00",
//      "CHEQUENO": "",
//      "TXNDATE": "24-10-2017 18:22:51",
//      "REMARKS": "ATM\/CASH WDL\/24-10-17\/0",
//      "TRANSACTIONID": "S27788501",
//      "TYPE": "DR",
//      "BALANCE": "35,758.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "5,200.00",
//      "CHEQUENO": "",
//      "TXNDATE": "24-10-2017 18:23:39",
//      "REMARKS": "ATM\/WDL RVSL\/24-10-17\/R\/000405001611",
//      "TRANSACTIONID": "S27788504",
//      "TYPE": "CR",
//      "BALANCE": "40,958.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "5,000.00",
//      "CHEQUENO": "",
//      "TXNDATE": "24-10-2017 18:24:10",
//      "REMARKS": "ATM\/CASH WDL\/24-10-17\/0",
//      "TRANSACTIONID": "S27788505",
//      "TYPE": "DR",
//      "BALANCE": "35,958.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "3,000.00",
//      "CHEQUENO": "",
//      "TXNDATE": "24-10-2017 18:25:07",
//      "REMARKS": "ATM\/CASH WDL\/24-10-17\/0",
//      "TRANSACTIONID": "S27788507",
//      "TYPE": "DR",
//      "BALANCE": "32,958.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "500.00",
//      "CHEQUENO": "",
//      "TXNDATE": "06-11-2017 18:18:36",
//      "REMARKS": "CAM\/CASH WDL\/GACHIBOWLI TOWER P2",
//      "TRANSACTIONID": "S27881109",
//      "TYPE": "DR",
//      "BALANCE": "32,458.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "100.00",
//      "CHEQUENO": "",
//      "TXNDATE": "06-11-2017 18:20:35",
//      "REMARKS": "ATM\/XFR DR \/06-11-17\/0",
//      "TRANSACTIONID": "S27881578",
//      "TYPE": "DR",
//      "BALANCE": "32,358.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "100.00",
//      "CHEQUENO": "",
//      "TXNDATE": "06-11-2017 18:21:15",
//      "REMARKS": "ATM\/XFR RVSL\/06-11-17\/R\/000405001611",
//      "TRANSACTIONID": "S27881596",
//      "TYPE": "CR",
//      "BALANCE": "32,458.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "100.00",
//      "CHEQUENO": "",
//      "TXNDATE": "06-11-2017 18:23:41",
//      "REMARKS": "ATM\/XFR DR \/06-11-17\/0",
//      "TRANSACTIONID": "S27881662",
//      "TYPE": "DR",
//      "BALANCE": "32,358.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "100.00",
//      "CHEQUENO": "",
//      "TXNDATE": "06-11-2017 18:24:33",
//      "REMARKS": "ATM\/XFR DR \/06-11-17\/0",
//      "TRANSACTIONID": "S27881685",
//      "TYPE": "DR",
//      "BALANCE": "32,258.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "100.00",
//      "CHEQUENO": "",
//      "TXNDATE": "06-11-2017 18:25:32",
//      "REMARKS": "ATM\/XFR DR \/06-11-17\/0",
//      "TRANSACTIONID": "S27881816",
//      "TYPE": "DR",
//      "BALANCE": "32,158.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "100.00",
//      "CHEQUENO": "",
//      "TXNDATE": "06-11-2017 18:26:34",
//      "REMARKS": "ATM\/XFR DR \/06-11-17\/0",
//      "TRANSACTIONID": "S27881741",
//      "TYPE": "DR",
//      "BALANCE": "32,058.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "100.00",
//      "CHEQUENO": "",
//      "TXNDATE": "06-11-2017 18:27:29",
//      "REMARKS": "ATM\/XFR DR \/06-11-17\/0",
//      "TRANSACTIONID": "S27881766",
//      "TYPE": "DR",
//      "BALANCE": "31,958.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "100.00",
//      "CHEQUENO": "",
//      "TXNDATE": "06-11-2017 18:28:29",
//      "REMARKS": "ATM\/XFR DR \/06-11-17\/0",
//      "TRANSACTIONID": "S27882093",
//      "TYPE": "DR",
//      "BALANCE": "31,858.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "500.00",
//      "CHEQUENO": "",
//      "TXNDATE": "06-11-2017 18:29:32",
//      "REMARKS": "ATM\/XFR DR \/06-11-17\/0",
//      "TRANSACTIONID": "S27882120",
//      "TYPE": "DR",
//      "BALANCE": "31,358.53"
//    },
//    {
//      "VALUEDATE": "10-01-2016",
//      "AMOUNT": "100.00",
//      "CHEQUENO": "",
//      "TXNDATE": "06-11-2017 18:30:31",
//      "REMARKS": "ATM\/XFR DR \/06-11-17\/0",
//      "TRANSACTIONID": "S27882151",
//      "TYPE": "DR",
//      "BALANCE": "31,258.53"
//    }
//  ],
//  "RESPONSE": "SUCCESS",
//  "ACCOUNTNO": "000405001611",
//  "httpcode": 200
//}';


			$this->renderAuto('response', ['responseText' => $response->response, 'type' => 'statement']);
			Yii::app()->end();
		}
		$paramList = Yii::app()->icici->loadCommon($txnparams);

		$this->renderAuto('form', ['model' => $paramList, 'title' => $title]);
	}

	public function actionBalanceinq()
	{

		$txnparams = [
			'AGGRID',
			'CORPID',
			'USERID',
			'URN',
			'ACCOUNTNO',
		];

		$title			 = 'Balance Inquiry';
		$this->pageTitle = $title;
		$arr			 = Yii::app()->request->getParam('Pay');
		if (isset($arr))
		{
			$url		 = ICICIIB::getBalanceInquiryUrl();
			$response	 = Yii::app()->icici->query($arr, $url);
			$this->renderAuto('response', ['responseText' => $response->response]);
			Yii::app()->end();
		}
		$paramList = Yii::app()->icici->loadCommon($txnparams);

		$this->renderAuto('form', ['model' => $paramList, 'title' => $title]);
	}

	public function actionTranstatus()
	{

		$txnparams = [
			'AGGRID',
			'CORPID',
			'USERID',
			'UNIQUEID',
			'URN',
		];

		$title			 = 'Transaction Inquiry';
		$this->pageTitle = $title;
		$arr			 = Yii::app()->request->getParam('Pay');
		if (isset($arr))
		{
			$url		 = ICICIIB::getTransactionInquiryUrl();
			$response	 = Yii::app()->icici->query($arr, $url);
			$this->renderAuto('response', ['responseText' => $response->response]);
			Yii::app()->end();
		}
		$paramList	 = Yii::app()->icici->loadCommon($txnparams);
		$uniqueids	 = OnlineBanking::fetchUniqueIds();

		$this->renderAuto('form', ['model' => $paramList, 'title' => $title, 'uniqueids' => $uniqueids]);
	}

	public function actionAdd()
	{
		$params	 = array(
			'AGGRID'	 => 'CUST0286',
			'AGGRNAME'	 => 'GOZO',
			'CORPID'	 => 'PRACHICIB1',
			'USERID'	 => 'USER3',
			'URN'		 => 'SR186122666',
			'DEBITACC'	 => '000905024927',
			'CREDITACC'	 => '000405002777',
			'IFSC'		 => 'ICIC0000011',
			'AMOUNT'	 => '1',
			'CURRENCY'	 => 'INR',
			'TXNTYPE'	 => 'TPA'
		);
		$defArr	 = ["AGGRID"	 => "CUST0286",
			"AGGRNAME"	 => "GOZO",
			"CORPID"	 => "PRACHICIB1",
			"USERID"	 => "USER3",
			"URN"		 => "SR186122666",
			"DEBITACC"	 => "000905024927",
			"CREDITACC"	 => "000405002777",
			"IFSC"		 => "ICIC0000011",
			"AMOUNT"	 => "1"];
//		$url	 = "https://apigwuat.icicibank.com:8443/api/Corporate/CIB/v1/Transaction";





		$regparams	 = ['AGGRNAME',
			'AGGRID',
			'CORPID',
			'USERID',
			'URN'];
		$regStatus	 = ['AGGRNAME',
			'AGGRID',
			'CORPID',
			'USERID',
			'URN'
		];

		$paramList = [];
		foreach ($regStatus as $param)
		{
			$paramList[$param] = $defArr[$param];
		}
//		$paramList["REQUESTFROM"]	 = "AGTR";
//		$paramList["REQUESTTYPE"]	 = "AGREG";
//		$paramList["BANKID"]		 = "ICI";
		$url = ICICIIB::getRegistrationUrl();

		$res = Yii::app()->icici->trans($paramList, $url, true);
		echo $res;
		exit;
//"AGGRID":"CUST0286"
//
//"AGGRNAME":"GOZO"
//
//"CORPID":"PRACHICIB1"
//
//"USERID":"USER3"
//
//"URN":"SR186122666"
//
//"DEBITACC":"000905024927"
//
//"CREDITACC":"000405002777"
//
//"IFSC" : "ICIC0000011" (OWN&TPA),"DLXB0000092"(IFS&RGS),"SBIN0003060"(RTG)
//
//"AMOUNT" : "1"



		$regparams	 = ['AGGRNAME',
			'AGGRID',
			'CORPID',
			'USERID',
			'URN'];
		$regStatus	 = ['AGGRNAME',
			'AGGRID',
			'CORPID',
			'USERID',
			'URN'
		];

		$paramList = [];
		foreach ($regStatus as $param)
		{
			$paramList[$param] = $defArr[$param];
		}

		$url = ICICIIB::getRegistrationStatusUrl();

		ICICIIB::callAPICurl($paramList, $url);
		$this->render('form', array());
	}

	public function actionTest()
	{
		Yii::app()->icici->processResponse();
	}

	public function actionTestupi()
	{
		$merchantId	 = Yii::app()->icici->mid;
		$params		 = ['merchantId'	 => $merchantId,
			'terminalId'	 => 5411,
			'merchantTranId' => round(microtime(true) * 1000),
			'billNumber'	 => rand(100000, 99999),
			'Amount'		 => 1.00];
		Yii::app()->icici->demoUpi($params);
	}

	public function actionList()
	{
		$this->layout	 = 'admin1';
		$model			 = new OnlineBanking();
		$dataProvider	 = OnlineBanking::fetchList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));
	}

}
