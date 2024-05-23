<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class VehicleController extends BaseController
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
			$ri = array('cab_list');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});
		
		

		$this->onRest('req.get.add_cab.render', function() {
			$process_sync_data = Yii::app()->request->getParam('data');
			$data = CJSON::decode($process_sync_data, true);
			$vendor_id = Yii::app()->user->getId();
			$vhcModel = new Vehicles();
			$success = $vhcModel->addFromJson($data, $vendor_id);
			$data = [];
			if (!$success)
			{
				$data = ['errors' => $vhcModel->getErrors()];
			}
			else
			{
				$data = ['data' => ['id' => $vhcModel->vhc_id]];
			}
			return $this->renderJSON([
						'type' => 'raw',
						'data' => array(
					'success' => $success,
						) + $data
			]);
		});

		$this->onRest('req.get.cab_driver_list.render', function() {
			$vendorId = Yii::app()->user->getId();
			$data = [];
			$decision = Yii::app()->request->getParam('decision');
			if ($decision == "" || $decision == "cab")
			{
				$cabModel = Vehicles::model()->getAvailabilities($vendorId);
				if ($cabModel)
				{
					$success = true;
					$data = ['data' => ['cabs' => $cabModel]];
				}
				else
				{
					$success = false;
					$data = ['errors' => ['error' => ["Error occured while fetching list"]]];
				}
			}
			if ($decision == "" || $decision == "driver")
			{
				$driverModel = Drivers::model()->getdriverDetails($vendorId);
				if ($driverModel)
				{
					$success = true;
					$data = ['data' => ['drivers' => $driverModel]];
				}
				else
				{
					$success = false;
					$data = ['errors' => ['error' => ["Error occured while fetching list"]]];
				}
			}
			return $this->renderJSON([
						'type' => 'raw',
						'data' => array(
					'success' => $success,
						) + $data
			]);
		});

		$this->onRest('req.post.cab_list.render', function() {
			Logger::create('38 cab_list ', CLogger::LEVEL_TRACE);
			$token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result = Vendors::model()->authoriseVendor($token);
			
			
				if(!empty($result))
				{
					$vendorId = Yii::app()->user->getEntityId();
					

					//$page_no = (int) Yii::app()->request->getParam('page_no');
					$process_sync_data	 = Yii::app()->request->getParam('data');
					$data		 = CJSON::decode($process_sync_data, true);			
					$page_no	 = (int) $data['page_no'];
				
				
				   
				
				$count = Vehicles::model()->getAvailabilitiesCount($vendorId);
				if ($page_no != 0)
				{

					$cabModel = Vehicles::model()->getAvailabilitiesPerPage($vendorId, $page_no);
					if ($cabModel != [])
					{
						$success = true;
						$error = null;
					}
					else
					{
						$success = false;
						$error = "No records found";
					}
				}
				else
				{
					$cabModel = Vehicles::model()->getAvailabilitiesPerPage($vendorId);
					if ($cabModel != [])
					{
						$success = true;
						$error = null;
					}
					else
					{
						$success = false;
						$error = "No records found";
					}
				}
				if ($count != 0)
				{
					$pageCount = ceil($count / 20);
				}
			}
			else
			{
				$success =false;
				$error = 'Unauthorised User';
			}

			return $this->renderJSON([
						'type' => 'raw',
						'data' => array(
							'success' => $success,
							'error' => $error,
							'data' => $cabModel,
							'total_pages' => $pageCount,
							'count' => $count,
						)
			]);
		});
	}

}
