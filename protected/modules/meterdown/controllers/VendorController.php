<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class VendorController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'column1';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';
     public $newHome = '';

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
				'actions' => array(),
				'users' => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array(
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users' => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array(),
				'users' => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function() {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function($validation) {

			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri = array();
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.vendor_details.render', function() {
			$vendorId = Yii::app()->user->getId();
			$agtModel = new Vendors();
			$vendor_details = $agtModel->vendordetails($vendorId);
			$data = [];
			if ($vendor_details)
			{
				$success = true;
				$data = ['data' => ['details' => $vendor_details]];
			}
			else
			{
				$success = false;
				$agtModel->addError('vnd_id', "Vendor not found");
				$data = ['errors' => $agtModel->getErrors()];
			}
			return $this->renderJSON([
						'type' => 'raw',
						'data' => array(
					'success' => $success,
						) + $data
			]);
		});
	}

}
