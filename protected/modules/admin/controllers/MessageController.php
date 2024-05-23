<?php

class MessageController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'admin1';
    public $email_receipient;

    /**
     * @return array action filters
     */
    public function filters()
    {
	return array(
	    'accessControl', // perform access control for CRUD operations
	    'postOnly + delete', // we only allow deletion via POST request
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
		'actions'	 => array('sendmessage', 'list', 'tripOtpList'),
		'users'		 => array('@'),
	    ),
	    array('allow', // allow authenticated user to perform 'create' and 'update' actions
		'actions'	 => array('index',),
		'users'		 => array('*'),
	    ),
	    array('allow', // allow admin user to perform 'admin' and 'delete' actions
		'actions'	 => array('admin'),
		'users'		 => array('admin'),
	    ),
	    array('deny', // deny all users
		'users' => array('*'),
	    ),
	);
    }

    public function actionSendmessage()
    {
	$this->pageTitle = "Send Message";
	$model		 = new SmsLog();
	if (isset($_REQUEST['SmsLog']))
	{
	    $model->attributes = Yii::app()->request->getParam('SmsLog');
	    if ($model->validate())
	    {
		$model->save();
	    }
	}
	$this->render('sendmsg', array('model' => $model));
    }

    public function actionList()
    {
	$this->pageTitle = "Sms";
	$request	 = Yii::app()->request;
	$model		 = new SmsLog('search');
	$bookingId	 = $request->getParam('bookingId');
	if ($bookingId != "")
	{
	    $model->booking_id = $bookingId;
	}
	if ($request->getParam('SmsLog'))
	{
	    $model->attributes = $request->getParam('SmsLog');
	}
	else{
		$model->sendDate1 = date("Y-m-d", strtotime("-1 day", time()));
		$model->sendDate2 = date('Y-m-d');
	}
	$dataProvider = $model->fetchList();
	$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
	$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
	$this->render('list', array('model'		 => $model,   'dataProvider'	 => $dataProvider));
    }

    public function actionTripOtpList()
    {
	$this->pageTitle = "Trip Otp List";
	$pageSize	 = Yii::app()->params['listPerPage'];
	$model		 = new TripOtplog('search');
	if (isset($_REQUEST['TripOtplog']))
	{
	    $model->attributes = Yii::app()->request->getParam('TripOtplog');
	}
	$dataProvider = $model->fetchList();
	$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
	$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
	$this->render('tripotplog', array('model'		 => $model,
	    'dataProvider'	 => $dataProvider));
    }

}
