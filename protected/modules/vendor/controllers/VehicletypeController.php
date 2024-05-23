<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class VehicletypeController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'column1';
	public $email_receipient, $useUserReturnUrl;
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
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array('/vendor_cab_type_list', '/zone_list');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		/*
		 * old service : vendor_cab_type_list  (  method : GET  )
		 * new service : getVendorCabList      (  method : GET )
		 */
		$this->onRest('req.get.getVendorCabList.render', function () {
			return $this->renderJSON($this->getVendorCabList());
		});

		$this->onRest('req.get.vendor_cab_type_list.render', function () {

			Logger::create('31 vendor_cab_type_list ', CLogger::LEVEL_TRACE);
			$cabTypeModel = VehicleTypes::model()->getcabTypeDetails();
			if ($cabTypeModel != [])
			{
				$success = true;
				$error	 = null;
			}
			else
			{
				$success = false;
				$error	 = "Error occured while fetching list";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error,
					'data'		 => $cabTypeModel,
				)
			]);
		});

		$this->onRest('req.post.zone_list.render', function () {

			Logger::create('53 zone_list ', CLogger::LEVEL_TRACE);
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$zonId				 = $data1['zon_id'];
			//$zonId	 = Yii::app()->request->getParam('zon_id');
			$zones				 = Zones::model()->getAllZoneListforApp($zonId);
			$data				 = [];
			if ($zones)
			{
				$success = true;
				$data	 = ['data' => ['zones' => $zones]];
			}
			else
			{
				$success = false;
				$zonModel->addError('zon_id', "Error occured while fetching list");
				$data	 = ['errors' => $zonModel->getErrors()];
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
			'success' => $success,
				) + $data
			]);
		});

		/* ========================= Vendor app (included and excluded zone)================= */
		$this->onRest('req.post.zone_list_latest.render', function () {
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Vendors::model()->authoriseVendor($token);
			$returnSet	 = new ReturnSet();
			$success	 = false;
			$data		 = [];
			$error		 = [];
			try
			{
				$vndId = UserInfo::getEntityId();
				if (!$result || !$vndId)
				{
					throw new Exception('Not authorised', ReturnSet::ERROR_UNAUTHORISED);
				}

				$zones = Zones::model()->getIncludedExcludedZoneListforApp($vndId);

				if ($zones)
				{
					$success = true;
					$data	 = ['data' => ['included_zones' => $zones['included_zones'], 'excluded_zones' => $zones['excluded_zones']]];
					$returnSet->setStatus(true);
				}
				else
				{
					throw new Exception('Error occured while fetching list', ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			catch (Exception $ex)
			{
				$returnSet	 = ReturnSet::setException($ex);
				$error		 = ['error' => $returnSet->getErrors()[0]];
			}



			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array('success' => $success) + $data + $error
			]);
		});
		/* =========================== end code ===================================== */

		/* ========================= Vendor app (update vendor zone)================= */
		$this->onRest('req.post.zone_update.render', function () {

			Logger::create('55 zone_update ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$zonId				 = $data1['zon_id'];
				$flag				 = $data1['flag'];
				$vndId				 = UserInfo::getEntityId();
				$model				 = VendorPref::model()->getByVendorId($vndId);

				$accepted_zones = $model->vnp_accepted_zone;
				if ($flag == 1 || $flag === NULL)
				{
					$success = false;
					$error	 = "Please Contact our Admin department if you want to serve in this Zone and want to add it";
					goto skipAll;
				}
				else
				{
					$accepted_zones	 = explode(',', $accepted_zones);
					$pos			 = array_search($zonId, $accepted_zones);
					unset($accepted_zones[$pos]);
					$accepted_zones	 = implode(',', $accepted_zones);
				}

				$accepted_zones = trim($accepted_zones, ',');

				$model->vnp_accepted_zone = $accepted_zones;
				if ($model->update())
				{
					$success = true;
					$error	 = "";
				}
				else
				{
					$success = false;
					$error	 = "Something went wrong";
				}
			}
			else
			{
				$success = false;
				$error	 = "Vendor Unauthorised";
			}
			skipAll:
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error,
				)
			]);
		});
		/* =========================== end code ===================================== */


		$this->onRest('req.get.list.render', function () {
			$vhtModel		 = new VehicleTypes();
			$cabTypeModel	 = $vhtModel->getcabTypeDetails();
			$data			 = [];
			if ($cabTypeModel)
			{
				$success = true;
				$data	 = ['data' => ['cabtypes' => $cabTypeModel]];
			}
			else
			{
				$success = false;
				$vhtModel->addError('vht_id', "Error occured while fetching list");
				$data	 = ['errors' => $vhtModel->getErrors()];
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
			'success' => $success,
				) + $data
			]);
		});
		/* ======================================================================= */
	}

	public function getVendorCabList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vendorId	 = UserInfo::getEntityId();
			$userId		 = UserInfo::getUserId();
			if (!$userId)
			{
				throw new Exception("User Unauthorised.", ReturnSet::ERROR_INVALID_DATA);
			}
			if (!$vendorId)
			{
				throw new Exception("Vendor Unauthorised.", ReturnSet::ERROR_INVALID_DATA);
			}

			$cabTypeModel = VehicleTypes::model()->getcabTypeDetails();
			if (!$cabTypeModel)
			{
				throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			foreach ($cabTypeModel as $res)
			{
				$responseSet[] = array("id" => (int) $res['vht_id'], "make" => $res['vht_make'], "model" => $res['vht_model']);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($responseSet);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

}
