<?php

class VoucherController extends Controller
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
			//['allow', 'actions' => ['list','add','view','addpartner','listpartner','agentlistbyquery','changestatus'], 'roles' => ['voucherAssign']],
			['allow', 'actions' => ['list','add','view','addpartner','listpartner','agentlistbyquery','changestatus'], 'users' => ['@']],
			['deny', 'users' => ['*']],
		);
	}

	public function actionAdd()
	{
		$data = [];		
		$voucherId			 = Yii::app()->request->getParam('voucherid');
		$voucherType         = Vouchers::getAllType();
		$voucherPartner      = ['Specific Partner','All Partners'];
		$voucherUser         = ['Specific User','All Users'];
		$allpromoList        = Promos::model()->getActivePromoCode();			
		foreach($allpromoList as $v)
		{			
			$promoList[] = array("id" => $v['prm_id'], "text" => $v['prm_code']);
		}

		if ($voucherId == '' || $voucherId == null)
		{
			$this->pageTitle	= "Add Voucher";
			$model				= new Vouchers();		
			$model->scenario = "add";
		}
		else
		{
			$this->pageTitle = "Edit Voucher";
			$model			 = Vouchers::model()->findByPk($voucherId);
			$model->scenario = "edit";
		}
		if (!empty($_POST['Vouchers']))
		{
			$offerValidityFrom	 = Yii::app()->request->getParam('offerValidityFrom');
			$offerValidityTo	 = Yii::app()->request->getParam('offerValidityTo');
			$arr1	 = Yii::app()->request->getParam('Vouchers');			
			$model->attributes  = $arr1; 
			$model->vch_desc = $arr1['vch_desc'];
			if ($arr1['vch_valid_from_date'] != '' && $offerValidityFrom == 1)
			{
				$validFromDate			 = DateTimeFormat::DatePickerToDate($arr1['vch_valid_from_date']) . " 00:00:00";
				$model->vch_valid_from	 = $validFromDate;
			}
			else 
			{
				$model->vch_valid_from	 = null;
			}
			if ($arr1['vch_valid_to_date'] != '' && $offerValidityTo == 1)
			{
				$validUptoDate			 = DateTimeFormat::DatePickerToDate($arr1['vch_valid_to_date']) . " 00:00:00";
				$model->vch_valid_to	 = $validUptoDate;
			}
			else 
			{
				$model->vch_valid_to	 = null;
			}
						
			if($model->save())
			{
				$data = ["success"=> 1];
			}
			else 
			{				
				$errors = "";
				foreach($model->errors as $v)
				{
					foreach($v as $v1)
					{
						$errors .= $v1."";
					}					
				}				
				$data = ["success"=> 0,"error"=> $errors];
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{			
			echo json_encode($data);
			Yii::app()->end();
		}
		
		$this->render('add', 
			array(	'model' => $model,
					'voucherType' => $voucherType,
					'voucherPartner'=>$voucherPartner,
					'voucherUser'=>$voucherUser,
					'promoList' => CJSON::encode($promoList),
					), false, true);
	}
	
	public function actionList()
	{
		$this->pageTitle				 = "Voucher List";
		$model							 = new Vouchers();		
		if (isset($_REQUEST['Vouchers']))
		{
			$model->attributes	 = Yii::app()->request->getParam('Vouchers');			
		}
		$model->resetScope();		
		$dataProvider = $model->getList();
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionView()
	{
		$this->pageTitle = "Voucher View";
		$id				 = Yii::app()->request->getParam('voucherid');
		$voucherModel    = Vouchers::model()->findByPk($id);	
		$allpromoList    = Promos::model()->getActivePromoCode();	
		foreach($allpromoList as $v)
		{			
			$promoList[$v['prm_id']] = $v['prm_code'];
		}
		$this->renderPartial('view', array('voucherModel' => $voucherModel,"promoList"=>$promoList));
	}
	public function actionAddPartner()
	{
		$data = [];
		$this->pageTitle = "Add Partner";
		$id				 = Yii::app()->request->getParam('voucherid');
		$voucherModel    = Vouchers::model()->findByPk($id);
		$model			 = new VoucherPartner();
		
		
		if (!empty($_POST['VoucherPartner']))
		{			
			$arr1	 = Yii::app()->request->getParam('VoucherPartner');				
			$model->attributes  = $arr1; 
			if ($arr1['vpr_valid_till'] != '')
			{
				$validTillDate			 = DateTimeFormat::DatePickerToDate($arr1['vpr_valid_till']) . " 00:00:00";
				$model->vpr_valid_till	 = $validTillDate;
			}			
			$model->scenario = "add";
			if(trim($model->vpr_partner_id))
			{
				$res = VoucherPartner::checkIfPartnerExists($model->vpr_partner_id,$model->vpr_vch_id);
				if($res['cnt'] > 0)
				{
					$data = ["success"=> 3];
					goto endLine;
				}
				$maxAllow  = VoucherPartner::countPartners($model->vpr_vch_id);
				if($maxAllow['cnt'] >= $model->vpr_max_allowed)
				{
					$data = ["success"=> 4];
					goto endLine;
				}
			}
			if($voucherModel->vch_valid_to !="")
			{
				if(strtotime($model->vpr_valid_till) > strtotime($voucherModel->vch_valid_to))
				{
					$vdate = date("F j, Y",strtotime($voucherModel->vch_valid_to));    
					$data = ["success"=> 5,"error"=> "Validity Date should not exceed ".$vdate];
					goto endLine;
				}
			}
			if($model->save())
			{
				$data = ["success"=> 1];				
			}
			else 
			{				
				$errors = "";
				foreach($model->errors as $v)
				{
					foreach($v as $v1)
					{
						$errors .= $v1."";
					}					
				}				
				$data = ["success"=> 0,"error"=> $errors];		
			}
			endLine:
			echo json_encode($data);
			Yii::app()->end();
		}		
		$this->renderPartial('addpartner', array('voucherModel' => $voucherModel,'voucherid'=>$id,'model'=>$model), false, true);
	}

	public function actionListpartner()
    {
		$outputJs = 1;
		$this->pageTitle = "Partner List";
		$vid				 = Yii::app()->request->getParam('voucherid');		
		$model				 = new VoucherPartner();
		$dataProvider		 = VoucherPartner::getPartnerList($vid);
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params	 = array_filter($_REQUEST);
		$success			 = false;
		
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('listpartner', ['dataProvider' => $dataProvider,'model' => $model], false, true);
    }

	public function actionAgentlistbyquery()
	{
		header('Cache-Control: max-age=28800, public', true);
		$query = Yii::app()->request->getParam('q');		
		$data	 = Yii::app()->cache->get("allagentlistbyQuery1_{$query}_");
		if ($data === false)
		{
			$data = Agents::model()->getJSONAllPartnersbyQuery($query);			
			Yii::app()->cache->set("allagentlistbyQuery1_{$query}_", $data, 21600);
		}
		echo $data;
		Yii::app()->end();		
	}

	public function actionChangestatus()
	{		
		$actid	 = Yii::app()->request->getParam('activateid');
		$inactid = Yii::app()->request->getParam('disableid');
		if ($actid > 0)
		{
			$model = Vouchers::model()->findByPk($actid); 
			if (count($model) == 1)
			{
				$model->vch_active = 2;
				$model->save();
			}
		}
		if ($inactid > 0)
		{
			$model = Vouchers::model()->findByPk($inactid); 
			if (count($model) == 1)
			{
				$model->vch_active = 1;
				$model->save();
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo "true";
			Yii::app()->end();
		}
		$this->redirect(array('list'));
	}


#
}


