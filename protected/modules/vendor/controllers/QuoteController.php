<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class QuoteController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'column1';
	public $email_receipient;
	public $current_page = '';

	//public $layout = '//layouts/column2';

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
		$this->onRest('req.cors.access.control.allow.methods', function()
		{
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function($validation)
		{
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array('');


			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});



		$this->onRest('req.post.get_custom_list.render', function()
		{
			return $this->getCustomList();
		});


		$this->onRest('req.post.bid_custom_quote.render', function()
		{
			return $this->bidCustomQuote();
		});
	}

	public function getCustomList()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonObj	 = CJSON::decode($data, false);
		$transaction = DBUtil::beginTransaction();
		try
		{

			$pageSize	 = ($jsonObj->pageSize > 0) ? $jsonObj->pageSize : 30;
			$pageCount	 = ($jsonObj->currentPage > 0) ? $jsonObj->currentPage : 1;
			$search_txt	 = $jsonObj->search_txt;

			$totalCount	 = CustomQuote::getListForVendor(UserInfo::getEntityId(), $search_txt, true);
			$quotList	 = CustomQuote::getListForVendor(UserInfo::getEntityId(), $search_txt, false, $pageSize, $pageCount);

			if (!$quotList)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			} 

			$qtData = new Stub\vendor\CustomQuoteList();
			$qtData->getListData($quotList, $totalCount, $pageSize, $pageCount);


			$response = Filter::removeNull($qtData);
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		return $this->renderJSON($returnSet);
	}

	public function bidCustomQuote()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($jsonObj->isInterested)
			{
				$obj = $jsonMapper->map($jsonObj, new \Stub\vendor\BidQuote());
			}
			else
			{
				$obj = $jsonMapper->map($jsonObj, new \Stub\vendor\RejectQuote());
			}
			/** @var VendorQuote $model */
			$model = $obj->getModel();
			 
			if (!$model)
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			$resModel = $model->addNew();
			if ($resModel->hasErrors())
			{
				$errors = $resModel->getErrors();
				throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus(true);
			$message = ($resModel->vqt_status == 1 ) ? 'Bid registered successfully' : 'Bid denied successfully';
			$returnSet->setMessage($message);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		return $this->renderJSON($returnSet);
	}

}
