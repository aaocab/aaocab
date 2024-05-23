<?php

class AdminController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';

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
			['allow', 'actions' => ['list'], 'roles' => ['adminList']],
			['allow', 'actions' => ['add'], 'roles' => ['adminAdd']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('del', 'changestatus', 'showlog', 'command', 'log', 'kill', 'dailer', 'getDeptCatByTeam', 'adminLogTime', 'autoAllocateLead'),
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionList()
	{
		$this->pageTitle = "Admin List";
		$model			 = new Admins('search');
		$model->isActive = 0;
		if (isset($_REQUEST['Admins']))
		{
			$model->attributes			 = Yii::app()->request->getParam('Admins');
			$model->adp_emp_code		 = Yii::app()->request->getParam('Admins')['adp_emp_code'];
			$model->tea_name			 = Yii::app()->request->getParam('Admins')['tea_name'];
			$model->adp_team_leader_id	 = Yii::app()->request->getParam('Admins')['adp_team_leader_id'];
			$model->teamId				 = Yii::app()->request->getParam('Admins')['teamId'];
			$model->dpt_name			 = Yii::app()->request->getParam('Admins')['dpt_name'];
			$model->cat_name			 = Yii::app()->request->getParam('Admins')['cat_name'];
			$model->isAjax				 = Yii::app()->request->isAjaxRequest;
			$model->isActive			 = Yii::app()->request->getParam('Admins')['isActive'] == null ? 0 : 1;
		}
		$dataProvider = $model->resetScope()->search();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->getPagination()->setPageSize(150);
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionAdd($status = null)
	{
		$this->pageTitle = "Add Admin";
		$admid			 = Yii::app()->request->getParam('admid');
		$oldData		 = false;
		$admauthitem	 = Admins::model()->getAuthItemList($itemList);
		if ($admid > 0)
		{
			$model	 = Admins::model()->findByPk($admid);
			$pmodel	 = AdminProfiles::model()->getByAdminID($admid);
			if (!$pmodel)
			{
				$pmodel = new AdminProfiles();
			}
			$pmodel->scenario	 = 'update1';
			$oldData			 = $model->attributes;
			$model->scenario	 = 'update';
			$this->pageTitle	 = "Edit Admin";
			$ftype				 = 'Edit';
		}
		else
		{
			$model					 = new Admins();
			$pmodel					 = new AdminProfiles();
			$pmodel->scenario		 = 'update1';
			$model->adm_attempt		 = 0;
			$model->adm_chk_local	 = 0;
			$model->adm_last_login	 = date("Y-m-d");
			$ftype					 = 'Add';
		}


		if (isset($_REQUEST['Admins']))
		{
			$auth		 = Yii::app()->authManager;
			$authassign	 = $_POST[Admins][adm_attempt];

			$arrRolesList	 = Admins::getRolesList();
			$arr			 = $auth->getAuthAssignments($_REQUEST['admid']);
			$roles			 = $auth->getRoles($_REQUEST['admid']);
			foreach ($roles as $role)
			{
				if (in_array($role->getName(), $arrRolesList))
				{
					$auth->revoke($role->getName(), $_REQUEST['admid']);
				}
			}
			$arr				 = Yii::app()->request->getParam('Admins');
			$model->attributes	 = $arr;
			$arrProf			 = Yii::app()->request->getParam('AdminProfiles');

			$model->adm_region		 = $arr['adm_region'] != null ? implode(",", $arr['adm_region']) : "1,2,3,4,5,6,7";
			$model->adm_booking_type = $arr['adm_booking_type'] != null ? implode(",", $arr['adm_booking_type']) : null;
			$model->adm_teams		 = $arr['adm_teams'];
			$newData				 = $model->attributes;
			$result					 = CActiveForm::validate($model, null, false);
			if ($result == '[]')
			{
				$sepauthassign = explode(",", $authassign);

				if ($model->scenario == 'update')
				{
					$model->adm_log = $model->adminLog($oldData, $newData);

					foreach ($sepauthassign as $sepauth)
					{
						if (!$auth->isAssigned($sepauth, $_REQUEST['admid']))
						{
							$roles = $auth->getRoles($_REQUEST['admid']);

							foreach ($roles as $role)
							{
								$auth->revoke($sepauth, $_REQUEST['admid']);
							}
							if ($auth->assign($sepauth, $_REQUEST['admid']))
							{
								$auth->save();
							}
						}
					}
				}

				$model->save();
				$pmodel->updateData($model->adm_id, $arrProf);
				$insertid = $model->adm_id;

				foreach ($sepauthassign as $sepauth)
				{
					if (!$auth->isAssigned($sepauth, $insertid))
					{

						if ($auth->assign($sepauth, $insertid))
						{
							$auth->save();
						}
					}
				}
				$data = ['success' => true];
				if (Yii::app()->request->isAjaxRequest)
				{
					echo $data;
					Yii::app()->end();
				}
				$this->redirect(array('list'));
			}
			else
			{
				$data = ['success' => false, 'errors' => $result];
				if (Yii::app()->request->isAjaxRequest)
				{
					echo $data;
					Yii::app()->end();
				}
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('add', array('model' => $model, 'pmodel' => $pmodel, 'isNew' => $ftype, 'autharray' => $admauthitem), false, $outputJs);
	}

	public function actionChangestatus()
	{
		$admid		 = Yii::app()->request->getParam('adm_id');
		$adm_active	 = Yii::app()->request->getParam('adm_active');
		$success	 = false;
		if ($adm_active == 1)
		{
			$model				 = Admins::model()->resetScope()->findByPk($admid);
			$model->adm_active	 = 2;
			$model->update();
			$success			 = true;
		}
		else if ($adm_active == 2)
		{
			$model				 = Admins::model()->resetScope()->findByPk($admid);
			$model->adm_active	 = 1;
			$model->update();
			$success			 = true;
		}
		echo json_encode(["success" => $success]);
		Yii::app()->end();
	}

	public function actionDel()
	{
		$id = Yii::app()->request->getParam('admid');
		if ($id != '')
		{
			$model				 = Admins::model()->resetScope()->findByPk($id);
			$model->adm_active	 = 0;
			$model->scenario	 = 'del';
			if ($model->validate())
			{
				$model->save();
			}
		}
		$this->redirect(array('list'));
	}

	public function actionShowlog()
	{
		$admid		 = Yii::app()->request->getParam('admid');
		$logList	 = Admins::getLog($admid);
		$modelList	 = new CArrayDataProvider($logList, array('pagination' => array('pageSize' => 10),));
		$models		 = $modelList->getData();
		$this->renderPartial('showlog', array('lmodel' => $models, 'usersList' => $modelList), false, true);
	}

	public function actionCommand()
	{
		$cmd	 = Yii::app()->request->getParam('cmd');
		$action	 = Yii::app()->request->getParam('action');
		// echo $command = dirname(PHP_BINARY).DIRECTORY_SEPARATOR."php ".realpath(PUBLIC_PATH).DIRECTORY_SEPARATOR."cron.php $cmd $action";
		echo $command = "php " . realpath(PUBLIC_PATH) . DIRECTORY_SEPARATOR . "cron.php $cmd $action";
		$output	 = shell_exec($command);

		echo "<pre>" . $output . "</pre>";
		Yii::app()->end();
	}

	public function actionLog()
	{
		$this->pageTitle = "Admin Log";
		$model			 = new AdminLog();
		if (isset($_REQUEST['AdminLog']))
		{
			$arr				 = Yii::app()->request->getParam('AdminLog');
			$model->attributes	 = $arr;
		}
		$dataProvider = $model->fetchList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('log', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionKill()
	{
		$log_id	 = Yii::app()->request->getParam('adm_log_id');
		$model	 = AdminLog::model()->findByPk($log_id);
		//$sess_file	 = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'sess' . DIRECTORY_SEPARATOR . 'sess_' . $model->adm_log_session;

		$sessPath	 = session_save_path();
		$sess_file	 = $sessPath . 'sess_' . $model->adm_log_session;

		if (file_exists($sess_file) == 1)
		{
			if (unlink($sess_file))
			{
				$model->adm_log_out_time = new CDbExpression('NOW()');
				$model->update();
				$this->redirect(array('log', 'error' => '', 'flag' => 1));
			}
			else
			{
				$this->redirect(array('log', 'error' => 'Something went wrong', 'flag' => 0));
			}
		}
		else
		{
			$model->adm_log_out_time = new CDbExpression('NOW()');
			$model->update();
			$this->redirect(array('log', 'error' => 'Session not found', 'flag' => 0));
		}
	}

	public function actionDailer()
	{
		$admid	 = Yii::app()->request->getParam('admid');
		$model	 = Admins::model()->findByPk($admid);
		if (isset($_REQUEST['Admins']))
		{

			$arr				 = Yii::app()->request->getParam('Admins');
			$model->attributes	 = $arr;
			$result				 = CActiveForm::validate($model, null, false);
			if ($result == '[]')
			{
				$model->save();

				$data = ['success' => true];
				if (Yii::app()->request->isAjaxRequest)
				{
					echo $data;
					Yii::app()->end();
				}
				$this->redirect(array('list'));
			}
			else
			{
				$data = ['success' => false, 'errors' => $result];
				if (Yii::app()->request->isAjaxRequest)
				{
					echo $data;
					Yii::app()->end();
				}
			}
		}
		$this->renderPartial('dailer', array('model' => $model, 'usersList' => $modelList), false, true);
	}

	public function actionadminLogTime()
	{
		$request	 = Yii::app()->request;
		$csrId		 = $request->getParam('csrId');
		$date		 = $request->getParam('date');
		$fromDate	 = $request->getParam('fromDate');
		$toDate		 = $request->getParam('toDate');
		$cbrcomcls	 = $request->getParam('cbrcomcls');
		if ($cbrcomcls != '')
		{
			$date1	 = $fromDate;
			$date2	 = $toDate;
		}
		else
		{
			$date1	 = DateTimeFormat::DatePickerToDate($date) . ' 00:00:00';
			$date2	 = DateTimeFormat::DatePickerToDate($date) . ' 23:59:59';
		}
		$dataProvider							 = AdminOnoff::adminLogTime($csrId, $date1, $date2);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('adminlogtime', ['dataProvider' => $dataProvider], false, true);
	}

	public function actionAutoAllocateLead()
	{
		$admid	 = Yii::app()->request->getParam('adm_id');
		$type	 = Yii::app()->request->getParam('type');
		$success = false;
		$model	 = AdminProfiles::model()->getByAdminID($admid);
		if ($type != null && $model)
		{
			$model->adp_auto_allocated	 = $type == 1 ? 0 : 1;
			$model->update();
			$success					 = true;
		}
		echo json_encode(["success" => $success]);
		Yii::app()->end();
	}

}
