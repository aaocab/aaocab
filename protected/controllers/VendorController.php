<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



include_once(dirname(__FILE__) . '/BaseController.php');

class VendorController extends BaseController
{
	public $newHome	 = '';
	public $layout	 = '//layouts/column1';
	public $afterVal = '';
	public $email_receipient;

	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter + create, signin',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array
			(
			array
				("allow", // allow all users to perform "index" and "view" actions
				"actions"	 => array(),
				"users"		 => array("@"),
			),
			array
				("allow", // allow authenticated user to perform "create" and "update" actions
				"actions"	 => array
				(
					"REST.GET", "REST.PUT", "REST.POST", "REST.DELETE", "REST.OPTIONS", "uploads"
				),
				"users"		 => array("*"),
			),
			array
				("allow", // allow admin user to perform "admin" and "delete" actions
				"actions"	 => array("registerVendor"),
				"users"		 => array("*"),
			),
			array
				("deny", // deny all users
				"users" => array("*"),
			),
		);
	}

	/**
	 * This function is used for verifying the email link
	 */
	public function actionRegisterVendor()
	{
		$requestInstance = Yii::app()->request;

		//This function is used for updating and inserting new mappings
		if ($requestInstance->isAjaxRequest)
		{
			$receivedData	 = $requestInstance->getParam("dataToMap");
			$return			 = Vendors::registerVendor($receivedData);

			echo json_encode($return);
			exit;
		}
	}

	/**
	 * This function is used for validating the licenseNo
	 * @param type $param
	 */
	public function actionCheckCarNumber()
	{
		$requestInstance = Yii::app()->request;

		//This function is used for updating and inserting new mappings
		if ($requestInstance->isAjaxRequest)
		{
			$receivedData	 = $requestInstance->getParam("dataToCheck");
			$licenseNo		 = $receivedData->carNumber;
			$return			 = Vehicles::checkCarNumber($licenseNo);

			echo json_encode($return);
			exit;
		}
	}
}

?>
