<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class AccountsController extends BaseController
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter + create',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
		);
	}

	public function actions()
	{
		return array(
			'REST.' => 'RestfullYii.actions.ERestActionProvider',
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
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array(),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array();
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.getAccountTransactions.render', function () {
			return $this->renderJSON($this->getAccountTransactions());
		});
		$this->onRest('req.post.getTripTransactions.render', function () {
			return $this->renderJSON($this->getTripTransactions());
		});
		$this->onRest('req.post.getCoinTransactions.render', function () {
			return $this->renderJSON($this->getCoinTransactions());
		});
		$this->onRest('req.post.redeemCoin.render', function () {
			return $this->renderJSON($this->redeemCoin());
		});
		$this->onRest('req.post.getAccountTransactionsFile.render', function () {
			return $this->renderJSON($this->getAccountTransactionsFile());
		});
		
	}

	public function getAccountTransactions()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$vendorId = $this->getVendorId(false);
			if (!$vendorId)
			{
				throw new Exception("Invalid vendor", ReturnSet::ERROR_UNAUTHORISED);
			}

			$vndModel = Vendors::model()->findByPk($vendorId);
			if ($vndModel->vnd_active != 1)
			{
				$statusList	 = $vndModel->getStatusList();
				$status		 = $statusList[$vndModel->vnd_active];
				throw new Exception("Your account is $status.", ReturnSet::ERROR_UNAUTHORISED);
			}

			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();

			/** @var Beans\accounts\AccountLedgerDetails() $reqData */
			$reqData = $jsonMapper->map($reqObj, new Beans\accounts\AccountLedgerDetails())->getRequest();

			$entityType = UserInfo::TYPE_VENDOR;

			$dateRangeObj	 = $reqData->dateRange;
			$pageRef		 = $reqData->pageRef;
			$toDate			 = '';
			$nowDateTime	 = Filter::getDBDateTime();
			if ($dateRangeObj == null || (!$dateRangeObj->fromDate || !$dateRangeObj->toDate))
			{
				$today		 = date("Y-m-d", strtotime($nowDateTime));
				$startDate	 = date("Y-m-d", strtotime($nowDateTime . ' -3 MONTH'));

				$dateRangeObj->fromDate	 = $startDate;
				$dateRangeObj->toDate	 = $today;
			}
			if ($dateRangeObj->toDate)
			{
				$toDate = DateTimeFormat::DateToDatePicker($dateRangeObj->toDate);
			}
			$relVndList = \Vendors::getRelatedIds($vendorId);

			$vendorAmounts	 = \AccountTransDetails::calTotalAmountByRelVendors($relVndList, '', $toDate);
			$closingBalance	 = \AccountTransDetails::getTotalClosingbyVendorId($relVndList);

			$tdsAmount		 = \AccountTransDetails::calTotalTdsByVendorId($relVndList);
			$openingBalance	 = \AccountTransDetails::getOpeningBalance($relVndList, $dateRangeObj->fromDate) * -1;

			/** @var Beans\accounts\AccountLedgerDetails() $accountObj */
			$accountObj = \Beans\accounts\AccountLedgerDetails::setData($vendorAmounts, $openingBalance, $closingBalance, $tdsAmount);

			$result = AccountTransDetails::entityTransactionList($relVndList, $entityType, $dateRangeObj, $pageRef);

			$objTrans = [];

			foreach ($result as $transRow)
			{
				$objTrans[] = \Beans\accounts\TransactionStatement::setData($transRow);
			}

			$accountObj->statements = $objTrans;

			$returnSet->setData($accountObj);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	
	public function getAccountTransactionsFile()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			//$requestData = Yii::app()->request->getParam('data');
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$vendorId = $this->getVendorId(false);
			if (!$vendorId)
			{
				throw new Exception("Invalid vendor", ReturnSet::ERROR_UNAUTHORISED);
			}

			$vndModel = Vendors::model()->findByPk($vendorId);
			if ($vndModel->vnd_active != 1)
			{
				$statusList	 = $vndModel->getStatusList();
				$status		 = $statusList[$vndModel->vnd_active];
				throw new Exception("Your account is $status.", ReturnSet::ERROR_UNAUTHORISED);
			}
			
			
			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();

			/** @var Beans\accounts\AccountLedgerDetails() $reqData */
			$reqData = $jsonMapper->map($reqObj, new Beans\accounts\AccountLedgerDetails())->getRequest();

			$entityType = UserInfo::TYPE_VENDOR;

			$dateRangeObj	 = $reqData->dateRange;
			$pageRef		 = $reqData->pageRef;
			
			$nowDateTime	 = Filter::getDBDateTime();
		
			//$relVndList = \Vendors::getRelatedIds($vendorId);

			
			$startDate	 = date("Y-m-d", strtotime($dateRangeObj->fromDate));
			$toDate		 = date("Y-m-d", strtotime($dateRangeObj->toDate));
			//$file = AccountTransDetails::model()->GenerateLedgerPdf($vendorId, $startDate, $toDate,0);
			$file = AccountTransDetails::model()->GenerateLedgerXLS($vendorId, $startDate, $toDate,0);
			$returnSet->setData($file);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getTripTransactions()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$vendorId = $this->getVendorId(false);
			if (!$vendorId)
			{
				throw new Exception("You are not an operator", ReturnSet::ERROR_UNAUTHORISED);
			}

			$vndModel = Vendors::model()->findByPk($vendorId);
			if ($vndModel->vnd_active != 1)
			{
				$statusList	 = $vndModel->getStatusList();
				$status		 = $statusList[$vndModel->vnd_active];
				throw new Exception("Your account is $status.", ReturnSet::ERROR_UNAUTHORISED);
			}

			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();

			/** @var Beans\accounts\TripLedgerDetails() $reqData */
			$reqData = $jsonMapper->map($reqObj, new Beans\accounts\TripLedgerDetails())->getRequest();

			$dateRangeObj	 = $reqData->dateRange;
			$pageRef		 = $reqData->pageRef;
			$tripId			 = $reqData->tripId;

			$fromDate	 = $dateRangeObj->fromDate;
			$toDate		 = $dateRangeObj->toDate;

			if ($dateRangeObj == null || (!$dateRangeObj->fromDate || !$dateRangeObj->toDate))
			{
				$nowDateTime = Filter::getDBDateTime();
				$toDate		 = date("Y-m-d", strtotime($nowDateTime));
				$fromDate	 = date("Y-m-d", strtotime($nowDateTime . ' -6 MONTH'));
			}
			$relVndList = \Vendors::getRelatedIds($vendorId);

			$result = AccountTransDetails::vendorTransactionList1($relVndList, $fromDate, $toDate, $tripId, $pageRef);

//			$result = AccountTransDetails::vendorTripTransactionList($vendorId, $tripId, $dateRangeObj, $pageRef);

			$objTrans		 = [];
			$tripCount		 = 0;
			$profitAmount	 = 0;
			$totalTripAmount = 0;
			foreach ($result as $transRow)
			{
				$objTrans[]		 = \Beans\accounts\TripStatement::setData($transRow);
				$tripCount++;
				$profitAmount	 += ($transRow['trip_vendor_collected'] - $transRow['trip_amount']);
				$totalTripAmount += $transRow['trip_amount'];
			}


			$tdsAmount	 = AccountTransDetails::getPaidTdsByVendorId($relVndList, $tripId);
			/** @var Beans\accounts\TripLedgerDetails() $accountObj */
			$accountObj	 = \Beans\accounts\TripLedgerDetails::setData($totalTripAmount, $tdsAmount, $tripCount, $profitAmount);

			$accountObj->statements = $objTrans;

			$returnSet->setData($accountObj);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getCoinTransactions()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$vendorId = $this->getVendorId(false);
			if (!$vendorId)
			{
				throw new Exception("Invalid vendor", ReturnSet::ERROR_UNAUTHORISED);
			}
			/** @var Vendors $vndModel */
			$vndModel = Vendors::model()->findByPk($vendorId);
			if ($vndModel->vnd_active != 1)
			{
				$statusList	 = $vndModel->getStatusList();
				$status		 = $statusList[$vndModel->vnd_active];
				throw new Exception("Your account is $status.", ReturnSet::ERROR_UNAUTHORISED);
			}

			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();

			/** @var \Beans\accounts\CoinTransactionDetails $reqData */
			$reqData = $jsonMapper->map($reqObj, new \Beans\accounts\CoinTransactionDetails())->getRequest();

			$dateRangeObj	 = $reqData->dateRange;
			$pageRef		 = $reqData->pageRef;
			$tripId			 = $reqData->tripId;
			$relVndList		 = \Vendors::getRelatedIds($vendorId);

			$resultSet = \VendorCoins::getTransactionList($relVndList, $tripId, $dateRangeObj, $pageRef);

			$totCoin	 = \VendorCoins::totalCoin($relVndList);
			$accountObj	 = \Beans\accounts\CoinTransactionDetails::setData($resultSet, $totCoin);

			$returnSet->setData($accountObj);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function redeemCoin()
	{
		$returnSet = new \ReturnSet();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", \ReturnSet::ERROR_INVALID_DATA);
			}
			$vendorId = $this->getVendorId();

			$reqObj		 = CJSON::decode($requestData, false);
			$accTransId	 = $reqObj->id;
			$returnSet	 = VendorCoins::redeemPenalty($accTransId, $vendorId);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}
	
	
}
