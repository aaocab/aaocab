<?php

class UserController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;
	public $ven_date_type;
	public $ven_to_date;
	public $ven_from_date;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete1', // we only allow deletion via POST request
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
			['allow', 'actions' => ['travellers'], 'roles' => ['TravellersReport']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('referralBonous'),
				'roles'		 => array('GeneralReport'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index'),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionTravellers()
	{
		$row = Report::getRoleAccess(38);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Travellers Monthly Report";
		$model			 = new Booking();
		$month			 = 1;
		if (isset($_REQUEST['Booking']))
		{
			$arr	 = Yii::app()->request->getParam('Booking');
			$month	 = $arr['monthcount'];
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$month = Yii::app()->request->getParam('monthcount');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"Travellers_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "Travellers_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = $model->travellersMonthly($month, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['User Name', 'Contact', 'Email', 'First date of pickup', 'Last date of pickup', 'No. of days',
				'Count']);
			foreach ($rows as $row)
			{
				$rowArray					 = array();
				$rowArray['traveller_name']	 = $row['name'];
				$rowArray['bkg_contact_no']	 = $row['phone'];
				$rowArray['bkg_user_email']	 = $row['email'];
				$rowArray['min_date']		 = $row['min_date'];
				$rowArray['max_date']		 = $row['max_date'];
				$rowArray['no_of_days']		 = $row['no_of_days'];
				$rowArray['count_trip']		 = $row['count_trip'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$model->monthcount						 = $month;
		$dataProvider							 = $model->travellersMonthly($month);
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('travellers_monthly', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row), false, $outputJs);
	}

	public function actionReferralBonous()
	{
		$row = Report::getRoleAccess(57);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$model			 = new Users();
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ReferralBonous_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "ReferralBonous_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = $model->getReferralBonousList('command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Invitee', 'Inviter', 'Bonus Amount', 'Bonus Date', 'Remarks']);
			foreach ($rows as $row)
			{	
				$rowArray					 = array();
				$rowArray['referralName']	 = $row['referralName'];
				$rowArray['inviteeName']	 = $row['inviteeName'];
				$rowArray['act_amount']		 = $row['act_amount'];
				$rowArray['act_date']		 = $row['act_date'];
				$rowArray['act_remarks']	 = $row['act_remarks'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$this->pageTitle = "Referral Bonus";
		$request		 = Yii::app()->request;
		$dataProvider	 = $model->getReferralBonousList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('referral_bonous', array('model' => $model, 'dataProvider' => $dataProvider, 'roles'			 => $row));
	}

	public function actionProcessedPayments()
	{

		$model			 = new OnlineBanking();
		$dataProvider	 = OnlineBanking::fetchList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('processed_payments', array('dataProvider' => $dataProvider, 'model' => $model));
	}

}
