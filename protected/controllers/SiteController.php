<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class SiteController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column1';
	public $userBusiness;

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the signUp page
			'captcha'	 => array(
				'class'		 => 'CCaptchaAction',
				'backColor'	 => 0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			'page'		 => array(
				'class' => 'CViewAction',
			),
		);
	}

	public function actionCommingsoon()
	{
		$this->redirect(Yii::app()->homeUrl);
		exit();
		$this->layout	 = 'searchLayout';
		// USER LOCATIONS
		$userLoc		 = Yii::app()->db->createCommand()
				->select('*')
				->from('imp_locations')
				->queryAll();

		$this->pageTitle = "Comming Soon";
		$this->loadUserBusiness();

		$this->render('commingsoon', array(
			'userLoc' => $userLoc
		));
	}

	public function actionAboutus()
	{
		// USER LOCATIONS
		$this->redirect(Yii::app()->homeUrl);
		exit();
		$userLoc = Yii::app()->db->createCommand()
				->select('*')
				->from('imp_locations')
				->queryAll();

		$this->pageTitle = "About Us";
		$this->loadUserBusiness();

		$this->layout = 'searchLayout';
		$this->render('aboutus', array(
			'userLoc' => $userLoc
		));
	}

	public function actionHelp()
	{
		// USER LOCATIONS
		$this->redirect(Yii::app()->homeUrl);
		exit();
		$userLoc = Yii::app()->db->createCommand()
				->select('*')
				->from('imp_locations')
				->queryAll();

		$this->pageTitle = "Help";
		$this->loadUserBusiness();

		$this->layout = 'searchLayout';
		$this->render('help', array(
			'userLoc' => $userLoc
		));
	}

	public function actionUsingimpind()
	{
		// USER LOCATIONS
		$this->redirect(Yii::app()->homeUrl);
		exit();
		$userLoc = Yii::app()->db->createCommand()
				->select('*')
				->from('imp_locations')
				->queryAll();

		$this->pageTitle = "Help";
		$this->loadUserBusiness();

		$this->layout = 'searchLayout';
		$this->render('usingimpind', array(
			'userLoc' => $userLoc
		));
	}

	public function actionReviews()
	{
		// USER LOCATIONS
		$this->redirect(Yii::app()->homeUrl);
		exit();
		$userLoc = Yii::app()->db->createCommand()
				->select('*')
				->from('imp_locations')
				->queryAll();

		$this->pageTitle = "Help";
		$this->loadUserBusiness();

		$this->layout = 'searchLayout';
		$this->render('reviews', array(
			'userLoc' => $userLoc
		));
	}

	public function actionBusinessinfo()
	{
		// USER LOCATIONS
		$this->redirect(Yii::app()->homeUrl);
		exit();
		$userLoc = Yii::app()->db->createCommand()
				->select('*')
				->from('imp_locations')
				->queryAll();

		$this->pageTitle = "Help";
		$this->loadUserBusiness();

		$this->layout = 'searchLayout';
		$this->render('businessinfo', array(
			'userLoc' => $userLoc
		));
	}

	public function actionBusinessowner()
	{
		// USER LOCATIONS
		$this->redirect(Yii::app()->homeUrl);
		exit();
		$userLoc = Yii::app()->db->createCommand()
				->select('*')
				->from('imp_locations')
				->queryAll();

		$this->pageTitle = "Help";
		$this->loadUserBusiness();

		$this->layout = 'searchLayout';
		$this->render('businessowner', array(
			'userLoc' => $userLoc
		));
	}

	/**
	 * Application starts execution from here.
	 */
	public function actionIndex()
	{
		$this->redirect(Yii::app()->homeUrl);
		exit();
		$this->layout	 = 'head';
		// USER LOCATIONS
		$userLoc		 = Yii::app()->db->createCommand()
				->select('*')
				->from('imp_locations')
				->queryAll();

		$this->pageTitle = "Search a business";
		$this->loadUserBusiness();

		// USER PALACE
		if (!Yii::app()->user->isGuest)
		{
			$userPlace = Yii::app()->db->createCommand()
					->select('*')
					->from('imp_user_places')
					->where('user_id=' . Yii::app()->user->getId())
					->queryAll();
		}
		else
		{
			$userPlace = null;
		}
		$statusnotfound	 = Yii::app()->request->getParam('notfound');
		$pmodel			 = new UserPlaces();
		$this->render('index', array(
			'userLoc'		 => $userLoc,
			'userPlace'		 => $userPlace,
			'pmodel'		 => $pmodel,
			'statusnotfound' => $statusnotfound,
		));
	}

	public function actionGetlocation()
	{
		$this->redirect(Yii::app()->homeUrl);
		exit();
		header('Content-type: application/json');
		// USER LOCATIONS
		$userLoc = Yii::app()->db->createCommand()
				->select('*')
				->from('imp_locations')
				->queryAll();
		echo CJSON::encode($userLoc);
		Yii::app()->end();
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{

		$this->layout	 = 'head';
		if ($error			 = Yii::app()->errorHandler->error)
		{
			$exception = Yii::app()->errorHandler->exception;
			if ($exception != null && $exception instanceof Exception)
			{
				ReturnSet::setException($exception);
			}
			$data	 = [];
			$arr	 = ['REMOTE_HOST', 'REMOTE_ADDR', 'HTTP_REFERER', 'HTTP_USER_AGENT', 'REQUEST_TIME', 'REQUEST_METHOD', 'HTTPS', 'REQUEST_URI', 'QUERY_STRING', 'HTTP_COOKIE'];
			foreach ($arr as $val)
			{
				$data[$val] = isset($_SERVER[$val]) ? $_SERVER[$val] : "";
			}
			$data["POST"]	 = (count($_POST) > 0) ? json_encode($_POST) : "";
			$arr[]			 = "POST";

			$path = Yii::app()->runtimePath . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR;

			if (!file_exists($path))
			{
				mkdir($path);
			}

			foreach ($error as $key => $val)
			{
				$arr[] = $key;
				if (is_array($val))
				{
					$val = json_encode($val);
				}
				$data[$key] = $val;
			}
			$files = glob($path . "error_*.csv");
			if (is_array($files) && count($files) > 0)
			{
				$files		 = array_combine($files, array_map("filemtime", $files));
				arsort($files);
				$latest_file = key($files);
			}
			else
			{
				$latest_file = $path . "error_" . date('YmdHis') . ".csv";
			}
			$size = filesize($latest_file);
			if ($size > 4194304)
			{
				$latest_file = $path . "error_" . date('YmdHis') . ".csv";
			}
			$fhandle = fopen($latest_file, "a+");
			if ($size == 0)
			{
				fputcsv($fhandle, $arr);
			}
			fputcsv($fhandle, $data);
			fclose($fhandle);
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
			{
				if ($error['code'] == 404)
				{
					$this->pageTitle = 'Error 404';
					$this->render('error404', $error);
				}
				else
				{
					$this->pageTitle = 'Error ' . $error['code'];
					$this->render('error', $error);
				}
			}
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$this->redirect(Yii::app()->homeUrl);
		exit();
		$model = new ContactForm;
		if (isset($_REQUEST['ContactForm']))
		{
			$model->attributes = Yii::app()->request->getParam('ContactForm');
			if ($model->validate())
			{
				$name	 = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
				$subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
				$headers = "From: $name <{$model->email}>\r\n" .
						"Reply-To: {$model->email}\r\n" .
						"MIME-Version: 1.0\r\n" .
						"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
				Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact', array('model' => $model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->redirect(Yii::app()->homeUrl);
		exit();
		$model = new LoginForm;

		// if it is ajax validation request
		if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] === 'login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if (isset($_REQUEST['LoginForm']))
		{
			$model->attributes = Yii::app()->request->getParam('LoginForm');
			// validate user input and redirect to the previous page if valid
			if ($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login', array('model' => $model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		$this->redirect(Yii::app()->homeUrl);
		exit();
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionWhyimpind()
	{
		$this->redirect(Yii::app()->homeUrl);
		exit();
		$this->pageTitle = "Why Impind?";
		$this->loadUserBusiness();

		$this->layout	 = 'searchLayout';
		$tab			 = "1";
		if (isset($_REQUEST['tab']) && $_REQUEST['tab'] != '' && $_REQUEST['tab'] > 0)
		{
			$tab = Yii::app()->request->getParam('tab');
		}
		$this->render('whyimpind', array('tab' => $tab));
	}

	public function actionMain1()
	{
		$this->layout = '//layouts/column2';
		$this->render('whyimpind');
	}

	public function actionUnauthorized()
	{
		$this->layout	 = 'head';
		$this->pageTitle = 'Error 401';
		$this->render('error401', $error);
	}

	public function actionBadrequest()
	{
		$this->layout	 = 'head';
		$this->pageTitle = 'Error 400';
		$this->render('error400', $error);
	}

}
