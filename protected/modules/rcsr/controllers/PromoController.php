<?php

class PromoController extends Controller
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
			['allow', 'actions' => ['add'], 'roles' => ['promoAdd', 'promoEdit']],
			['allow', 'actions' => ['list'], 'roles' => ['promoList']],
			['allow', 'actions' => ['delpromo'], 'roles' => ['promoDelete']],
			['allow', 'actions' => ['getpromodiscount', 'validatecode'], 'users' => ['@']],
			['deny', 'users' => ['*']],
		);
	}

	public function actionAdd()
	{
		$this->pageTitle = "Add Promo";
		$oldData		 = false;
		$ftype			 = 'Add';
		$id				 = Yii::app()->request->getParam('promoid');
		$selected		 = [];
		if ($id != "")
		{
			$model						 = Promotions::model()->findByPk($id);
			$selected					 = array_values(explode(",", $model->prm_source_type));
			$model->prm_source_type_show = $selected;
			$this->pageTitle			 = "Edit Promo";
			$ftype						 = 'Edit';
			$oldData					 = $model->attributes;
		}
		else
		{
			$model = new Promotions();
		}
		$status = "";
		if (isset($_REQUEST['Promotions']))
		{
			$arr1				 = Yii::app()->request->getParam('Promotions');
			$model->attributes	 = $arr1;

			$model->prm_valid_from = ($arr1['prm_valid_from'] == '') ? '' : DateTimeFormat::DatePickerToDate($arr1['prm_valid_from']);

			$model->prm_valid_from_time = $arr1['prm_valid_from_time'];

			$model->prm_valid_upto = ($arr1['prm_valid_upto'] == '') ? '' : DateTimeFormat::DatePickerToDate($arr1['prm_valid_upto']);

			$model->prm_valid_upto_time	 = $arr1['prm_valid_upto_time'];
			$selected_source			 = array_values($arr1['prm_source_type_show']);
			$arr						 = implode(',', $selected_source);
			$model->prm_source_type		 = $arr;
			$newData					 = $model->attributes;
			$result						 = CActiveForm::validate($model);
			if ($result == '[]')
			{
				if ($model->scenario == 'update')
				{
					$model->prm_log = $model->addLog($oldData, $newData);
				}
				$model->save();
				if ($id != "")
				{
					$status = "Promo Modified Successfully";
				}
				else
				{
					$status = "Promo Added Successfully";
				}
			}
		}
		$this->render('add', array('model' => $model, 'status' => $status, 'isNew' => $ftype));
	}

	public function actionList()
	{
		$this->pageTitle				 = "Promo List";
		/* var $model Promotions */
		$model							 = new Promotions();
		$model->prm_source_type			 = '0';
		$model->prm_applicable_user_type = ['0', '1'];
		$model->prm_applicable_type		 = ['0', '1'];
		$model->prm_applicable_trip_type = ['0', '1'];
		if (isset($_REQUEST['Promotions']))
		{
			$model->attributes = Yii::app()->request->getParam('Promotions');
		}
		$model->resetScope();
		//$dataProvider = $model->promoListing();
		$dataProvider = $model->search();
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionDelpromo()
	{

		$id = Yii::app()->request->getParam('pid');
		if ($id != '')
		{
			$model = Promotions::model()->findByPk($id);
			if (count($model) == 1)
			{

				$model->prm_active = 0;
				$model->update();
			}
		}
		$this->redirect(array('list'));
	}

	public function actionGetpromodiscount()
	{
		$success		 = false;
		$params			 = $_GET;
		$promoCredits	 = 0;
		$model			 = new Promotions();
		$code			 = Yii::app()->request->getParam('code');
		//$userid = Yii::app()->request->getParam('userId');
		$amount			 = Yii::app()->request->getParam('amount');
		$pdate			 = Yii::app()->request->getParam('pickupDate');
		$ptime			 = Yii::app()->request->getParam('pickupTime');
		$bkgId			 = Yii::app()->request->getParam('bkgId');
		$fromCityId		 = Yii::app()->request->getParam('fromCityId');
		$toCityId		 = Yii::app()->request->getParam('toCityId');
		$date			 = DateTimeFormat::DatePickerToDate($pdate);
		$time			 = date('H:i:00', strtotime($ptime));
		$discount		 = $model->getPromoDiscount($code, $userid, $amount, $date . ' ' . $time, 2, $fromCityId, $toCityId);
		$promoModel		 = Promos::model()->getByCode($code);

		if ($discount > 0)
		{
			$success = true;
		}
		if ($promoModel != '' && ($promoModel->prm_type == 2 || $promoModel->prm_type == 3) && $promoModel->prm_activate_on != 1)
		{
			$promoCredits	 = $discount;
			$discount		 = 0;
			if($promoModel->prm_type == 3){
				$discount = $discount;
			}
		}
		if ($promoModel != '' && $promoModel->prm_activate_on == 1 && ($promoModel->prm_type == 2  || $promoModel->prm_type == 3))
		{
			$promoCredits	 = $discount;
			$discount		 = 0;
			if($promoModel->prm_type == 3){
				$discount = $discount;
			}
			$success		 = false;
		}
		if ($promoModel != '' && $promoModel->prm_activate_on == 1  && ($promoModel->prm_type == 2  || $promoModel->prm_type == 3))
		{
			$promoCredits	 = 0;
			$discount		 = 0;
				if($promoModel->prm_type == 3){
				$discount = $discount;
			}
			$success		 = false;
		}

		$data = $params + ['discount' => $discount, 'promoCredits' => $promoCredits];
		echo CJSON::encode(['success' => $success, 'data' => $data]);
	}

	public function actionValidatecode()
	{
		$model	 = new Promotions();
		$code	 = Yii::app()->request->getParam('code');
		$userid	 = Yii::app()->request->getParam('userid');
		$email	 = Yii::app()->request->getParam('email');
		$bkgid	 = Yii::app()->request->getParam('bkgid');
		$result	 = $model->validateCode($code, $userid);

		if ($result)
		{

			$promomodel	 = Promos::model()->getByCode($code);
			$expdate	 = date('d/m/Y', strtotime($promomodel->prm_valid_upto));
			$damount	 = ($promomodel->prm_value_type == 1) ? $promomodel->prm_value . '%' : 'Rs. ' . $promomodel->prm_value;
			$email1		 = new emailWrapper();
			$email1->sendPromocode($email, $code, $bkgid, $expdate, $damount);
			//Create Log
			$bkgModel	 = Booking::model()->findByPk($bkgid);
			$eventid	 = BookingLog::DISCOUNT_CODE_SENT;
			$desc		 = 'Discount code sent to user';
			$userInfo	 = UserInfo::getInstance();
			BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $bkgModel);
			$status		 = 'true';
		}
		else
		{
			$status = 'false';
		}

		echo CJSON::encode(array('status' => $status));
	}

}
