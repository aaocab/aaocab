<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class RatingController extends CController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';

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
		$pass = uniqid(rand(), TRUE);
		return array(
			'oauth'		 => array(
				// the list of additional properties of this action is below
				'class'				 => 'ext.hoauth.HOAuthAction',
				// Yii alias for your user's model, or simply class name, when it already on yii's import path
				// default value of this property is: User
				'model'				 => 'Users',
				'alwaysCheckPass'	 => false,
				// map model attributes to attributes of user's social profile
				// model attribute => profile attribute
				// the list of avaible attributes is below
				'attributes'		 => array(
					'usr_email'			 => 'email',
					'username'			 => 'displayName',
					// you can also specify additional values,
					// that will be applied to your model (eg. account activation status)
					'usr_email_verify'	 => 1,
					'user_type'			 => 1,
					'new_password'		 => $pass,
					'repeat_password'	 => $pass,
					'tnc'				 => 1,
				),
			),
			// this is an admin action that will help you to configure HybridAuth
			// (you must delete this action, when you'll be ready with configuration, or
			// specify rules for admin role. User shouldn't have access to this action!)
			'oauthadmin' => array(
				'class' => 'ext.hoauth.HOAuthAdminAction',
			),
			'REST.'		 => 'RestfullYii.actions.ERestActionProvider',
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
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
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

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function()
		{
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function($validation)
		{
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array();
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});


		$this->onRest('req.post.customer.render', function()
		{
			$rating_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($rating_sync_data, true);
			$returninfo			 = Ratings::model()->addCustomerRating($data);
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success' => $returninfo
						),
			]);
		});
	}

}
