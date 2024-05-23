<?php

class NotesController extends Controller
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
				'actions'	 => array('add','add1','edit', 'list', 'editStatus','delNotes','modifyNote'),
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
		    ['allow', 'actions' => ['editStatus'], 'roles' => ['noteEdit']],
		);
	}

	public function actionlist()
	{
		$this->pageTitle = "Notes";
		$model			 = new DestinationNote('search');
		
		$fromDate = "";
		$todate = "";
		#print_r($_REQUEST['DestinationNote']);exit;
		if (isset($_REQUEST['DestinationNote']))
		{
			$arr		= Yii::app()->request->getParam('DestinationNote');
                        $model->attributes	 = $arr;
			
			$model->dnt_area_id = "";
                        if($arr['dnt_area_id'] !="")
			{
				
				$model->dnt_area_id= $arr['dnt_area_id'];
                                
			}
			
                        if($arr['dnt_area_type'] !="")
			{
				$qry['area_type'] = $arr['dnt_area_type'];
                                $model->dnt_area_type= $arr['dnt_area_type'];
			}
                        
			if($arr['dnt_valid_from_date']!="")
			{
				$fromDate =  DateTimeFormat::DatePickerToDate($arr['dnt_valid_from_date']);
				$fromDate =$fromDate.' '.'00-00-00';
                                $qry['fromDate'] = $arr['dnt_valid_from_date'];
                               
			}
			
			if($arr['dnt_valid_to_date']!="")
			{
				$todate =  DateTimeFormat::DatePickerToDate($arr['dnt_valid_to_date']);
				$todate =$todate.' '.'00-00-00';
				$qry['todate'] = $arr['dnt_valid_to_date'];
			}
			if($arr['dnt_created_date']!="")
			{
			$createdDate =  DateTimeFormat::DatePickerToDate($arr['dnt_created_date']);
			 $qry['createdDate'] = $arr['dnt_created_date'];
			}
			if($arr['dnt_area_type'] ==6)
			{
				$arr['dnt_area_id'] = 0;
                                $qry['area_type'] = 6;
                                 
			}
                       
                        if($arr['dnt_show_note_to']!="")
                        {
                             $qry['show_note_to'] = $arr['dnt_show_note_to'];
                        }
                        if($arr['dnt_status']!="")
                        {
                             $qry['dnt_status'] = $arr['dnt_status'];
                        }
                        
			$request_arr = array("area_id"=>$arr['dnt_area_id'],
				              "area_type"=>$arr['dnt_area_type'],
						"show_note_to"=>$arr['dnt_show_note_to'],
						"createdDate"=>$createdDate,
						"fromDate"=>$fromDate,
						"toDate"=>$todate,
                                                 "status"=>$arr['dnt_status']);
			
		}
               
                
		$dataProvider = DestinationNote::fetchNotedDetalis($request_arr); //Fetches all the data
      
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
        
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model, 'qry' => $qry));
		
	}
	/*
	 * @deprecated
	 * new function modifyNote
	 * */
	 
	public function actionedit()
	{
		$dntid			 = Yii::app()->request->getParam('dnt_id');
		$success = false;
		if ($dntid > 0)
		{
			$this->pageTitle = "Edit Notes";
			$model		 = DestinationNote::model()->findByPk($dntid);
			
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = UserInfo::getUserId();
			$dnt_note			 = Yii::app()->request->getParam('dnt_note');
			if($dnt_note!='')
			{
				$model->dnt_note = $dnt_note;
				$model->update();
				$success = true;
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('edit', array('model' => $model, 'success' => $success), null, $outputJs);
		
	}
	public function actionaddold()
	{
		$this->pageTitle = "Add Notes";
		$noteModel		 = new DestinationNote();
			
		$model			 = new Route('search');
		$stateModel		 = new States('search');
		
		//$dataProvider  = $stateModel->getList();
		//print_r($stateModel);exit;
		$dataProvider	 = $model->fetchList();
		
		if (isset($_REQUEST['DestinationNote']))
		{
		
			$noteModel->attributes = $_POST['DestinationNote'];
			
			$fromDate =  DateTimeFormat::DatePickerToDate($_POST['DestinationNote']['dnt_valid_from_date']);
			$fromTime = DateTime::createFromFormat('h:i A',$_POST['DestinationNote']['dnt_valid_from_time'])->format('H:i:00');
			#print_r($fromTime);exit;
			$noteModel->dnt_valid_from = $fromDate .' '.$fromTime;
			
			$toDate		 = DateTimeFormat::DatePickerToDate($_POST['DestinationNote']['dnt_valid_to_date']);
			$toTime = DateTime::createFromFormat('h:i A',$_POST['DestinationNote']['dnt_valid_to_time'])->format('H:i:00');
			$noteModel->dnt_valid_to = $toDate .' '.$toTime;
		     # print_r(strtotime($noteModel->dnt_valid_from));
			#exit;
			$noteModel->dnt_status			 = 1;
			$noteModel->dnt_created_by		 = UserInfo::getUserId();
			$noteModel->dnt_created_by_role	 = 1;
			$noteModel->dnt_area_type = $_POST['DestinationNote']['dnt_area_type'];
			$noteModel->scenario = 'dateTimeValid';
			$result							 = CActiveForm::validate($noteModel);
			
           
			if ($result == '[]')
			{
				$noteModel->save();
				$this->redirect(array('list'));
				
			}
			else
            {
                $return = ['success' => false, 'errors' => CJSON::decode($result)];
            }
		}

		#$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));
		$this->render('add', array('model' => $model,'stateModel'=>$stateModel,'status' => $status, 'noteModel' => $noteModel, 'dataProvider' => $dataProvider, 'isNew' => $isNew));
	}
	/**
	 * This function is used for adding notes
	 */
	public function actionadd()
	{
		$this->pageTitle = "Add Notes";
		$success = false;
		if (isset($_REQUEST["DestinationNote"]))
		{
			$fromDate 	=  DateTimeFormat::DatePickerToDate($_POST['DestinationNote']['dnt_valid_from_date']);
			$fromTime 	= DateTime::createFromFormat('h:i A',$_POST['DestinationNote']['dnt_valid_from_time'])->format('H:i:00');

			$toDate		= DateTimeFormat::DatePickerToDate($_POST['DestinationNote']['dnt_valid_to_date']);
			$toTime 	= DateTime::createFromFormat('h:i A',$_POST['DestinationNote']['dnt_valid_to_time'])->format('H:i:00');

			$areaType 	= $_POST['DestinationNote']['dnt_area_type'];
			if($areaType != 3) 
			{
				$arrAreaIds = explode(",", $_POST["DestinationNote"]["dnt_area_id"]);	
			} 
			else 
			{
				$arrAreaIds = $_POST["DestinationNote"]["dnt_area_id2"];	
			}			
			$showNoteTo 	= $_POST['DestinationNote']['dnt_show_note_to'];

			foreach ($arrAreaIds as $areaId)
			{
				$model		 				 = new DestinationNote();
				
				$model->dnt_valid_from 		 = $fromDate .' '.$fromTime;
				$model->dnt_valid_to 		 = $toDate .' '.$toTime;
				$model->dnt_status		 = 1;
				$model->dnt_created_by		 = UserInfo::getUserId();
				$model->dnt_created_by_role	 = 1;
				$model->dnt_show_note_to         = $showNoteTo;
				$model->dnt_area_type 		 = $areaType;
				$model->dnt_area_id			 = ($areaId > 0) ? $areaId : 0;
                $model->scenario 			 = 'addValid';
				$result						 = CActiveForm::validate($model);
				if($result != "[]")
				{
					$return = ['success' => false, 'errors' => CJSON::decode($result)];
				}
				
				$model->save();
				$success = true;
			}
			if($success == true)
			{
			   $this->redirect(array('list'));
			}
			else
			{
				$return = ['success' => false, 'errors' => CJSON::decode($result)];
			}
		}
		
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$model		 = new DestinationNote();
		$this->$method('add1', array('model' => $model, 'success' => $success), false, $outputJs);
	}

	/**
	 * This function is used for modify notes with new logic
	 * in case of edit old note expired and new notes created.
	 */
	public function actionmodifyNote()
	{
		$this->pageTitle = "Modify Notes";
		$success = false;
		$dntid			 = Yii::app()->request->getParam('dnt_id');
		
		if ($dntid > 0)
		{
		    $notesModel		 = DestinationNote::model()->findByPk($dntid);
		}
		
		if (isset($_REQUEST["DestinationNote"]))
		{
		   
		   
			$fromDate 	=  DateTimeFormat::DatePickerToDate($_POST['DestinationNote']['dnt_valid_from_date']);
			$fromTime 	= DateTime::createFromFormat('h:i A',$_POST['DestinationNote']['dnt_valid_from_time'])->format('H:i:00');

			$toDate		= DateTimeFormat::DatePickerToDate($_POST['DestinationNote']['dnt_valid_to_date']);
			$toTime 	= DateTime::createFromFormat('h:i A',$_POST['DestinationNote']['dnt_valid_to_time'])->format('H:i:00');

			$areaType 	= $_POST['DestinationNote']['dnt_area_type'];
			if($areaType != 3) 
			{
				$arrAreaIds = explode(",", $_POST["DestinationNote"]["dnt_area_id"]);	
			} 
			else 
			{
				$arrAreaIds = $_POST["DestinationNote"]["dnt_area_id2"];	
			}			
			$showNoteTo 	= $_POST['DestinationNote']['dnt_show_note_to'];
			
			if($_REQUEST['dnt_id']!="")
			{
			    $expNote = DestinationNote::expNote($_REQUEST['dnt_id']);
			}
			
			foreach ($arrAreaIds as $areaId)
			{
				$model		 				 = new DestinationNote();
				
				$model->dnt_valid_from 		 = $fromDate .' '.$fromTime;
				$model->dnt_valid_to 		 = $toDate .' '.$toTime; 
				$model->dnt_status		 = 1;
				$model->dnt_created_by		 = UserInfo::getUserId();
				$model->dnt_created_by_role	 = 1;
				$model->dnt_show_note_to         = $showNoteTo;
				$model->dnt_area_type 		 = $areaType;
				$model->dnt_area_id			 = ($areaId > 0) ? $areaId : 0;
				$model->scenario 			 = 'addValid';
				
				$result						 = CActiveForm::validate($model);
				if($result != "[]")
				{
					$return = ['success' => false, 'errors' => CJSON::decode($result)];
				}
				
				$model->save();
				$success = true;
			}
			if($success == true)
			{
			   $this->redirect(array('list'));
			}
			else
			{
				$return = ['success' => false, 'errors' => CJSON::decode($result)];
			}
		}
		
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$model		 = new DestinationNote();
		$this->$method('modify', array( 'success' => $success, 'notes'=>$notesModel), false, $outputJs);
	}

	public function actiondelNotes()
	{

		$id = Yii::app()->request->getParam('note_id');
		if ($id != '')
		{
			$model = DestinationNote::model()->findByPk($id);
			
			if (count($model) == 1)
			{

				$model->dnt_active = 0;
				$model->update();
				
			}
		}
		$this->redirect(array('list'));
	}
	public function actioneditStatus()
	{
		$dnt_id	 = Yii::app()->request->getParam('note_id');
		$success = false;
		$model	 = DestinationNote::model()->findByPk($dnt_id);
		
		if ($model != '')
		{
			$model->dnt_status	 = 1;
			$userInfo			 = UserInfo::getInstance();
			$model->dnt_approve_by		 = UserInfo::getUserId();
			$model->dnt_approve_date = DBUtil::getCurrentTime();
			$model->update();
			$success			 = true;
		}

		$data = $success;
		if (Yii::app()->request->isAjaxRequest)
		{
			echo $data;
			Yii::app()->end();
		}
		$this->redirect(array('list'));
	}

}
