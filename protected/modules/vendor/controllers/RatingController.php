<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class RatingController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'column1';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';

	//public $layout = '//layouts/column2';
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter + create',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions' => array(),
				'users' => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array(
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users' => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array(),
				'users' => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function() {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri = array();
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.vendor_rating.render', function() {
			
			Logger::create('32 vendor_rating ', CLogger::LEVEL_TRACE);
			
			Logger::create('32 vendor_rating ', CLogger::LEVEL_TRACE);
			$rating_sync_data = Yii::app()->request->getParam('data');
			$data = CJSON::decode($rating_sync_data, true);
			//$vendor_id = Yii::app()->user->getId();
			$vendor_id = UserInfo::getEntityId();
			$returninfo = Ratings::model()->addVendorRating($data, $vendor_id);
			return $this->renderJSON([
						'type' => 'raw',
						'data' => array(
							'success' => $returninfo
						),
			]);
		});

		/* ============================================================================= */
		$this->onRest('req.post.rating.render', function() {
			$rating_sync_data = Yii::app()->request->getParam('data');
			$data = CJSON::decode($rating_sync_data, true);
			$vendor_id = Yii::app()->user->getId();
			$result = Ratings::model()->add($data, $vendor_id);
			return $this->renderJSON(['type' => 'raw', 'data' => $result]);
		});
		/* ============================================================================= */
		
		$this->onRest('req.get.fetchRating.render', function() {
			$vendorId = UserInfo::getEntityId();
			Logger::create('32 $vendorId ' . $vendorId, CLogger::LEVEL_TRACE);
			if ($vendorId > 0)
			{

				$rating		 = VendorStats::fetchRating($vendorId);
				$bkgId		 = VendorProfile::model()->getBkgByVndId($vendorId);
				$count	 = count($bkgId);
				$vndRating	 = ($count < 0) ? '5' : $rating;
			}
			return $this->renderJSON(['type' => 'raw', 'data' => ['rating' => $vndRating]]);
		});
	}

}
