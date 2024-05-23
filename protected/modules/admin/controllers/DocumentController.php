<?php

class DocumentController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;
	public $pageTitle;

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
			['allow', 'actions' => ['add', 'create']],
			['allow', 'actions' => ['Upload']],
			['allow', 'actions' => ['']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('view', 'docsList', 'Showdoc', 'Approvedoc', 'docapprovallist', 'showdocument'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'json', 'getnames'),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionUpload()
	{
		$request			 = Yii::app()->request;
		$this->pageTitle	 = "Upload Documents";
		$documents			 = $request->getParam('Document', null);
		$cttId				 = $request->getParam('ctt_id');
		$docType			 = $request->getParam('doc_type');
		$viewType			 = $request->getParam('viewType');
		$model				 = new Document();
		$contactModel		 = Contact::model()->findByPk($cttId);
		$contProfileModel	 = ContactProfile::model()->findByContactId($cttId);
		if ($documents == null)
		{
			goto view;
		}
		$model->attributes	 = $documents;
		$identity			 = $documents['identity_no'];
		$transaction		 = DBUtil::beginTransaction();
		try
		{
			$model->doc_type			 = $docType;
			$model->doc_temp_approved	 = $documents['doc_temp_approved'] == 1 ? 1 : 0;
			$model->entity_id			 = $cttId;
			if ($viewType == "driver")
			{
				$model->drv_id = $contProfileModel->cr_is_driver;
			}
			else if ($viewType == "vendor")
			{
				$model->vndname = $contProfileModel->cr_is_vendor;
			}
			$success = $model->add();
			if (!$success)
			{
				throw new CHttpException(1, json_encode($model->getErrors()));
			}
			$docId = $model->doc_id;
			Contact::model()->updateContact($docId, $model->doc_type, $cttId, $identity);

			/* Iread comment On 
			  Ireaddocs::add($docId, $docType = 1, $type    = 1); */

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$model->addError('doc_id', $ex->getMessage());
//			if (count($model->getErrors('doc_id'))>0)
//			{
//				$json = json_decode($model->getErrors('doc_id')[0], true);
//				$errorMsg=$json['doc_file_front_path'][0]!=""?$json['doc_file_front_path'][0]:"";
//				$errorMsg.=$json['doc_file_back_path'][0]!=""?"<br>".$json['doc_file_back_path'][0]:"";
//			}
			Yii::app()->user->setFlash('notice', $errorMsg);
			DBUtil::rollbackTransaction($transaction);
		}
		$params = [];
		if ($viewType != "")
		{
			$params = ['viewType' => $viewType];
		}
		$this->redirect(array('document/view', 'ctt_id' => $cttId) + $params);

		view:
		$this->renderPartial('upload', array('ctt_id' => $cttId, 'doctype' => $docType, 'viewtype' => $viewType, 'conmodel' => $contactModel, 'model' => $model), false, true);
	}

	public function actionView()
	{
		$this->pageTitle = "Document Details";
		$request		 = Yii::app()->request;
		$docTypename	 = VendorDocs::model()->docTypeName;
		$cttId			 = trim($request->getParam('ctt_id'));
		$viewType		 = $request->getParam('viewType');
		$docByContactId	 = Document::model()->getAllDocsbyContact($cttId, $viewType);
		$model			 = new Document();
		$this->render('view', array('model' => $model, 'docpath' => $docByContactId, 'cttid' => $cttId, 'viewType' => $viewType));
	}

	public function actiondocsList()
	{
		$request		 = Yii::app()->request;
		$cttId			 = trim($request->getParam('id', NULL));
		$model			 = new Document();
		$this->pageTitle = "Document Pending Approval";
		$arr			 = [];
		if (isset($_REQUEST['Document']))
		{
			$arr				 = $request->getParam('Document');
			$model->doc_type	 = trim($arr['doc_type']);
			$model->contactname	 = trim($arr['contactname']);
		}
		$arr['cttId']							 = $cttId;
		$dataProvider							 = $model->getFetchList($arr);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$outputJs								 = $request->isAjaxRequest;
		$method									 = "render" . ( $outputJs ? "Partial" : "");
		$this->render('docslist', array('model' => $model, 'dataProvider' => $dataProvider), false, $outputJs);
	}

	public function actionShowdoc()
	{
		$this->pageTitle = "Show Document";
		$request		 = Yii::app()->request;
		$ctt_id			 = trim($request->getParam('ctt_id'));
		$doctype		 = trim($request->getParam('doctype'));
		$page			 = trim($request->getParam('page'));
		$docid			 = trim($request->getParam('docid'));
		$sidetype		 = trim($request->getParam('sidetype'));
		$objModel		 = Contact::model()->findByPk($ctt_id);
		$objdmodel		 = Drivers::model()->findByDriverContactID($ctt_id);
		$contactType	 = 0;
		if (!$objModel)
		{
			throw new CHttpException(404, 'Contact not found');
		}
		if ($objdmodel != null)
		{
			$contactType			 = 1;
			$objModel->ctt_trip_type = $objdmodel->drv_trip_type;
		}
		$objModelDocument	 = Document:: model()->getDocumnetByDocIdDocType($docid, $doctype, $sidetype);
		$outputJs			 = $request->isAjaxRequest;
		$method				 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('picshow', ['model' => $objModel, 'docModel' => $objModelDocument, 'sidetype' => $sidetype, 'page' => $page, 'doctype' => $doctype, 'contactType' => $contactType], false, $outputJs);
	}

	public function actionApprovedoc()
	{
		$this->pageTitle	 = "Approve Document";
		$request			 = Yii::app()->request;
		$btnType			 = $request->getParam('btntype');
		$arrContactModel	 = $request->getParam('Contact');
		$arrDocumentModel	 = $request->getParam('Document');
		$objModelContact	 = Contact::model()->findByPK($arrContactModel['ctt_id']);
		$returnSet			 = new ReturnSet();
		if (!$objModelContact)
		{
			throw new CHttpException(404, "Contact not found");
		}
		$objModeldocument = Document::model()->findByPK($arrDocumentModel['doc_id']);
		//$objModeldocument->scenario="approved";
		if (!$objModeldocument)
		{
			throw new CHttpException(404, 'Document not found');
		}
		$returnSet = Document::model()->setStatus($arrContactModel, $objModelContact, $arrDocumentModel, $objModeldocument, $btnType);
		if($returnSet->getStatus())
		{
			// update scq of that vendor
			$refType			 =  UserInfo::TYPE_VENDOR;
			$vendor			 = ContactProfile::getEntityById($arrContactModel['ctt_id'], $refType);
			$vendorId = $vendor['id'];
			$isSCQ = ServiceCallQueue::showUpdateVendorSCQ($vendor['id'],$vendorId);
			
		}
		echo CJSON::encode(array('success' => $returnSet->getStatus(), 'message' => $returnSet->getStatus() ? $returnSet->getData() : $this->getError($returnSet)));
		Yii::app()->end();
	}

	public function getError($returns)
	{
		if ($returns->hasErrors())
		{
			if (count($returns->getError('id')))
			{
				return $returns->getError('id')[0];
			}
		}
	}

	public function actionDocapprovallist()
	{
		$request		 = Yii::app()->request;
		$model			 = new Document();
		$modelVendor	 = new Vendors();
		$this->pageTitle = "Vendor Pending Doc Approval";
		$arr			 = [];
		$contactId		 = $request->getParam('ctt_id');
		if ($contactId != "")
		{
			#$Vendor				 = Vendors::model()->findByVendorContactID($contactId);
			#$modelVendor->vnd_id = $Vendor->vnd_id;
			#$arrVen['vnd_id']	 = $Vendor->vnd_id;
			$Vendor				 = ContactProfile::getProfileByCttId($contactId);
			if(!$Vendor || $Vendor['cr_is_vendor'] == null || $Vendor['cr_is_vendor'] == 0)
			{
				$Vendor['cr_is_vendor'] = 0;
			}
			$modelVendor->vnd_id = $Vendor['cr_is_vendor'];
			$arrVen['vnd_id']	 = $Vendor['cr_is_vendor'];
		}
		if ($request->getParam('Document'))
		{
			$arr				 = $request->getParam('Document');
			$arrVen				 = $request->getParam('Vendors');
			$model->doc_type	 = $arr['doc_type'];
			$model->contactname	 = $arr['contactname'];
			$modelVendor->vnd_id = $arrVen['vnd_id'];
		}

		if (!$request->getParam('Document') && $contactId == "")
		{
			$model->doc_type = 2;
		}

		$vendorIds								 = BookingCab::getVendorList();
		$arrVen['vendorIds']					 = $vendorIds != null ? rtrim($vendorIds, ",") : 0;
		$dataProvider							 = $model->getUnapproved($arrVen);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$outputJs								 = $request->isAjaxRequest;
		$method									 = "render" . ( $outputJs ? "Partial" : "");
		$this->render('docapprovallist', array('modelVendor' => $modelVendor, 'model' => $model, 'dataProvider' => $dataProvider), false, $outputJs);
	}

	public function actionShowdocument()
	{
		$document = Yii::app()->request->getParam('docid');

		$objModeldocument	 = Document::model()->findByPK($document);
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$method				 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('showdocument', ['document' => $objModeldocument], false, $outputJs);
	}

}
