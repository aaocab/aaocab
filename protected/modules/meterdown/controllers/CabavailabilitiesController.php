<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class CabavailabilitiesController extends BaseController
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout		 = 'column1';
    public $email_receipient, $useUserReturnUrl;
    public $current_page	 = '';

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
	    $pos	 = false;
	    $arr	 = $this->getURIAndHTTPVerb();
	    $ri	 = array('cabavailabilities');
	    foreach ($ri as $value)
	    {
		if (strpos($arr[0], $value))
		{
		    $pos = true;
		}
	    }
	    return $validation ? $validation : ($pos != false);
	});

	$this->onRest('req.post.add.render', function()
	{    
		Logger::create('57 add ', CLogger::LEVEL_TRACE);
	    $process_sync_data	 = Yii::app()->request->getParam('data');
        //$process_sync_data = '{"cav_cab_id":"56957","cav_from_city":"30893","cav_to_cities":"31975","cav_driver_id":"66857","cav_date_time":"","cav_amount":"2580","cav_duration":"4"}';
	    $data			 = CJSON::decode($process_sync_data, true);
		$result			 = Vehicles::model()->getVendorDetails($data['cav_cab_id']);
		$cavModel		 = new CabAvailabilities();
	    //$data['cav_vendor_id']	 = Yii::app()->user->getId();
	    $data['cav_vendor_id']	 = Yii::app()->user->getEntityId();
		$success = $cavModel->add($data);
	    if ($success)
	    {
		$apiURL = 'http://www.taxiotaxi.com/api/victoria/cabAvailability?api=UXVFya4Ov64qsuAVAx81xU9AAweaUE9x';
		$requestParamList	 = ['senderPhone' => $result['vnd_phone'], 'cabNumber' => $result['vhc_number'], 'senderCountryCode' => '91', 'fromCityId' => $data['cav_from_city'], 'toCityId' => $data['cav_to_cities'], 'dateTime' => $data['cav_date_time'], 'amount' => $data['cav_amount']];
		$jsonData		 = json_encode($requestParamList);
		$ch			 = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json'
			)
		);
		$jsonResponse		 = curl_exec($ch);
		$responseParamList	 = json_decode($jsonResponse, true);
		$response = $responseParamList["success"];
	    }
	    $data = [];
	    if (!$success)
	    {
		$data = ['errors' => $cavModel->getErrors()];
	    }
	    else
	    {
		$data = ['data' => ['id' => $cavModel->cav_id]];
	    }
	    return $this->renderJSON([
			'type'	 => 'raw',
			'data'	 => array(
		    'success' => $success,
			) + $data
	    ]);
	});

	$this->onRest('req.post.remove.render', function()
	{
		Logger::create('58 remove ', CLogger::LEVEL_TRACE);
	    $process_sync_data	 = Yii::app()->request->getParam('data');
	    $data			 = CJSON::decode($process_sync_data, true);
	    $cavModel		 = CabAvailabilities::model()->remove($data);
	    $data			 = [];
	    if ($cavModel->hasErrors())
	    {
		$success = false;
		$data	 = ['errors' => $cavModel->getErrors()];
	    }
	    else
	    {
		$success = true;
		$data	 = ['data' => ['id' => $cavModel->cav_id]];
	    }
	    return $this->renderJSON([
			'type'	 => 'raw',
			'data'	 => array(
		    'success' => $success,
			) + $data
	    ]);
	});
    }

}
