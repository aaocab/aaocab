<?php

class DocumentController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;
	public static $cabTypeList;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete,pickup,rates', // we only allow deletion via POST request
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
			array('allow',
				'actions'	 => array('CSRVerificationSummary'),
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

	public function actionCSRVerificationSummary()
	{
		$this->pageTitle = "";

		$docModel			 = new Document();
		$request			 = Yii::app()->request;
		$showExport			 = false;
		$docModel->appDate1	 = date('Y-m-d', strtotime('- 1 WEEK + 1 DAY'));
		$docModel->appDate2	 = date('Y-m-d');
		if ($request->getParam('Document'))
		{
			$data = $request->getParam('Document');

			$docModel->appDate1	 = $data['appDate1'];
			$docModel->appDate2	 = $data['appDate2'];
			$docModel->groupType = $data['groupType'];
			$showExport			 = true;
		}
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{

			$dataReader	 = $docModel->getData('export');
			$filename	 = "CSRVerificationSummary" . date('YmdHis') . ".csv";
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Pragma: no-cache");
			header("Expires: 0");

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

			$rowHead = [
				'VerifiedOn',
				'VerifiedBy',
				'TotalDocsApproved',
				'TotalDocsRejected',
				'VotersApproved',
				'VotersRejected',
				'PANApproved',
				'PANRejected',
				'AadharApproved',
				'AadharRejected',
				'DrivingLicenseApproved',
				'DrivingLicenseRejected',
				'CabInsuranceApproved',
				'CabInsuranceRejected',
				'CabRCApproved',
				'CabRCRejected',
				'PUCApproved',
				'PUCRejected',
				'PermitApproved',
				'PermitRejected',
				'FitnessApproved',
				'FitnessRejected'
			];
			switch ($docModel->groupType)
			{
				case 1:
					unset($rowHead['VerifiedOn']);
					break;

				case 2:
					unset($rowHead['VerifiedBy']);
					break;
			}

			$handle = fopen("php://output", 'w');
			fputcsv($handle, $rowHead);
			foreach ($dataReader as $data)
			{
				$rowArray = array();
				if ($docModel->groupType != 1)
				{
					$rowArray['VerifiedOn'] = $data['appdate'];
				}
				if ($docModel->groupType != 2)
				{
					$rowArray['VerifiedBy'] = $data['approvedBy'];
				}
				$rowArray['TotalDocsApproved']		 = $data['totApproved'];
				$rowArray['TotalDocsRejected']		 = $data['totRejected'];
				$rowArray['VotersApproved']			 = $data['approveVoter'];
				$rowArray['VotersRejected']			 = $data['rejectVoter'];
				$rowArray['PANApproved']			 = $data['approvePAN'];
				$rowArray['PANRejected']			 = $data['rejectPAN'];
				$rowArray['AadharApproved']			 = $data['approveAadhar'];
				$rowArray['AadharRejected']			 = $data['rejectAadhar'];
				$rowArray['DrivingLicenseApproved']	 = $data['approveLicense'];
				$rowArray['DrivingLicenseRejected']	 = $data['rejectLicense'];
				$rowArray['CabInsuranceApproved']	 = $data['approveInsurance'];
				$rowArray['CabInsuranceRejected']	 = $data['rejectInsurance'];
				$rowArray['CabRCApproved']			 = $data['approveRC'];
				$rowArray['CabRCRejected']			 = $data['rejectRC'];
				$rowArray['PUCApproved']			 = $data['approvePUC'];
				$rowArray['PUCRejected']			 = $data['rejectPUC'];
				$rowArray['PermitApproved']			 = $data['approvePermit'];
				$rowArray['PermitRejected']			 = $data['rejectPermit'];
				$rowArray['FitnessApproved']		 = $data['approveFitness'];
				$rowArray['FitnessRejected']		 = $data['rejectFitness'];
//				var_dump($rowArray);exit;

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = $docModel->getData();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('getApprovalSummary', array('dataProvider' => $dataProvider, 'docModel' => $docModel, 'showExport' => $showExport));
	}

}
