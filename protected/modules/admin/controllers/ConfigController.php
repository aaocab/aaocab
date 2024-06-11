<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class ConfigController extends Controller
{

	public $layout = 'admin1';

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + flush', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', 'actions'	 => array('list', 'changestatus', 'add', 'filelist', 'download'), 'roles'		 => array('manageConfig'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionList($qry = [])
	{
		$this->pageTitle = "Config List";
		$model			 = new Config('search');
		$request		 = Yii::app()->request;
		if ($request->getParam('Config'))
		{
			$data				 = $request->getParam('Config');
			$model->attributes	 = $request->getParam('Config');
			$model->cfg_active	 = $data['cfg_active'];
		}
		$dataProvider = $model->getConfigList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionChangeStatus()
	{
		$cfgId		 = Yii::app()->request->getParam('cfg_id');
		$cfgActive	 = Yii::app()->request->getParam('cfg_active');
		$success	 = false;
		if ($cfgActive == 1)
		{
			$model						 = Config::model()->resetScope()->findByPk($cfgId);
			$model->cfg_active			 = 0;
			$model->cfg_modified_by		 = UserInfo::getUserId();
			$model->cfg_modified_date	 = DBUtil::getCurrentTime();
			$model->update();
			$success					 = true;
		}
		else if ($cfgActive == 0)
		{
			$model						 = Config::model()->resetScope()->findByPk($cfgId);
			$model->cfg_active			 = 1;
			$model->cfg_modified_by		 = UserInfo::getUserId();
			$model->cfg_modified_date	 = DBUtil::getCurrentTime();
			$model->update();
			$success					 = true;
		}
		echo json_encode(["success" => $success]);
		Yii::app()->end();
	}

	public function actionAdd()
	{
		$cfgId		 = Yii::app()->request->getParam('cfg_id');
		$model		 = Config::model()->findByPk($cfgId);
		$pagetitle	 = "Modify Config";
		$ftype		 = 'Modify';
		if ($model == '')
		{
			$cfgId		 = 0;
			$model		 = new Config('save');
			$pagetitle	 = "Add Config";
			$ftype		 = 'Add';
		}
		$this->pageTitle = $pagetitle;
		if (isset($_POST['Config']))
		{
			$arrRequest = $_POST['Config'];

			unset($arrRequest['cfg_id']);
			$model->attributes	 = $arrRequest;
			$model->cfg_name	 = $arrRequest['cfg_name'];
			$model->cfg_env		 = $arrRequest['cfg_env'];
			if ($cfgId != '')
			{
				$model->cfg_modified_by = UserInfo::getUserId();
			}
			$model->cfg_modified_date	 = DBUtil::getCurrentTime();
			$result						 = CActiveForm::validate($model);

			if ($result)
			{
				if (!$model->save())
				{
					goto last;
				}
				$id = $model->cfg_id; //Yii::app()->db->getLastInsertID();				
				if ($cfgId == 0)
				{
					Yii::app()->user->setFlash('success', "Config added successfully.");
					$this->redirect(array('config/list'));
				}
				else
				{
					Yii::app()->user->setFlash('success', "Config updated successfully.");
					$this->redirect(array('config/list'));
				}
			}
		}
		last:
		$this->renderPartial('add', array('model' => $model, "isNew" => $ftype, "post" => $_POST), false, true);
	}

	public function actionFileList()
	{
		$this->pageTitle = "File List";

		$sourceDirectory = Yii::app()->runtimePath;

		Logger::trace("Module.admin.config.filelist" . $sourceDirectory);
		$fileList1			 = glob($sourceDirectory . DIRECTORY_SEPARATOR . '*.log');
		$fileList2			 = glob($sourceDirectory . DIRECTORY_SEPARATOR . '*.log.*');
		$fileList			 = array_merge($fileList1, $fileList2);
		Logger::trace("Module.admin.config.filelist" . CJSON::encode($fileList));
		$sortCol			 = trim(Yii::app()->request->getParam('sort', "desc"));
		$sort				 = new CSort();
		$sort->defaultOrder	 = 'modified_date DESC';
		$sort->attributes	 = array('modified_date');
		$data				 = array();
		if (strpos($sortCol, "desc") !== false)
		{
			usort($fileList, function ($x, $y) {
				return filemtime($x) < filemtime($y);
			});
		}
		else
		{
			usort($fileList, function ($x, $y) {
				return filemtime($x) > filemtime($y);
			});
		}

		foreach ($fileList as $filename)
		{
			if (!is_dir($filename))
			{
				$arr	 = array(
					"filename"		 => basename($filename),
					"type"			 => pathinfo($filename, PATHINFO_EXTENSION),
					"size"			 => ceil(filesize($filename) / 1024),
					//"modified_date"	 => date("F d Y H:i:s", filemtime($filename)),
					"modified_date"	 => date("Y-m-d H:i:s", filemtime($filename)),
					"download_url"	 => basename($filename),
				);
				$data[]	 = $arr;
			}
		}
		Logger::trace("Module.admin.config.filelist" . CJSON::encode($data));
		$list	 = new CArrayDataProvider($data, array('pagination' => array('pageSize' => 100), 'sort' => $sort));
		$models	 = $list->getData();
		$this->render('filelist', array('models' => $models, 'list' => $list));
	}

	public function actionDownload()
	{
		#$file = Yii::getPathOfAlias('application') . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . trim(Yii::app()->request->getParam('filename'));
		$file = Yii::app()->runtimePath . DIRECTORY_SEPARATOR . trim(Yii::app()->request->getParam('filename'));
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename=' . basename($file));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
	//	header('Content-Length: ' . filesize($file));
		header("Content-Type: text/plain");
		readfile($file);
	}

}
