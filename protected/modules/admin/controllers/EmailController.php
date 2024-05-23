<?php

class EmailController extends Controller
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
		'actions'	 => array('sendemail', 'list', 'showemail'),
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

    public function actionSendemail()
    {
	$this->pageTitle = "Send Email";
	$model		 = new EmailLog();
	if (isset($_REQUEST['EmailLog']))
	{
	    $model->attributes = Yii::app()->request->getParam('EmailLog');
	    if ($model->validate())
	    {
		$model->save();
	    }
	}
	$this->render('sendemail', array('model' => $model));
    }

    public function actionList()
    {
	$this->pageTitle = "Emails";
	$pageSize	 = Yii::app()->params['listPerPage'];
	$model		 = new EmailLog('search');

     $dbarchive	 = Yii::app()->getRequest()->getParam('type');
	$bookingId	 = Yii::app()->getRequest()->getParam('bookingId');
	if ($bookingId != "")
	{
	    $model->elg_booking_id = $bookingId;
	}
	if (isset($_REQUEST['EmailLog']))
	{
	    $model->attributes = Yii::app()->request->getParam('EmailLog');
	}
	else{
		$model->sendDate1 = date("Y-m-d", strtotime("-1 day", time()));
		$model->sendDate2 = date('Y-m-d');
	}
	$dataProvider = $model->fetchList($dbarchive);
	$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
	$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
	$this->render('list', array('model' => $model, 'dataProvider' => $dataProvider));
    }

    public function actionShowEmail()
	{
		$this->pageTitle = "Email Body";
		$elgId			 = Yii::app()->request->getParam('elgId');
		$model			 = EmailLog::model()->findByPk($elgId);
		if (!$model)
		{
			$sql = " SELECT * FROM gozo_archive.email_log WHERE elg_id  = $elgId";
			$row = DBUtil::queryRow($sql);

			$refId	 = $row['elg_ref_id'];
			$refType = $row['elg_ref_type'];
			$elgType = $row['elg_type'];
		}
		else
		{
			$refId	 = $model->elg_ref_id;
			$refType = $model->elg_ref_type;
			$elgType = $model->elg_type;
		}

		#Logger::warning("{$refId} - {$refType} - {$elgType} - ".UserInfo::getUserId(), true);
		if ($refType == 1 && $elgType == EmailLog::EMAIL_BOOKING_CREATED)
		{
			$bkgModel = Booking::model()->findByPk($refId);
			
			#Logger::warning($bkgModel->bkg_status . " - ".$bkgModel->bkgTrail->bkg_assign_csr, true);
			
			if(!$bkgModel)
			{
				echo "Booking error!!!";
				exit();
			}
			
			if ($bkgModel && $bkgModel->bkg_status == 15 && !Yii::app()->user->checkAccess("bookingContactAccess") && UserInfo::getUserId() != $bkgModel->bkgTrail->bkg_assign_csr)
			{
				echo "You are not authorized!!!";
				exit();
			}
		}

		$this->renderPartial('showemail', array('model' => $model, 'row' => $row), false, false);
	}

}
