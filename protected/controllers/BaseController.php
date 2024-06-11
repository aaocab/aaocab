<?php

/**
 * @property BookFormRequest $pageRequest
 * 
 * */
class BaseController extends Controller
{

	/** @var BookFormRequest $pageRequest */
	public $pageRequest		 = null;
	protected $_identity;
	public $pageHeader		 = '';
	public $current_page	 = '';
	public $email_receipient;
	public $newHome			 = '';
	public $layoutSufix		 = '';
	public $ampPageEnabled	 = 0;
	public $mobile			 = 0;
	public $fixedTop		 = '';
	public $pageDesc		 = 'A delight to travel! Delhi - Chandigarh | Delhi - Jaipur | Book Online. Fares starting Rs. 1999. Book Indica, Indigo, Innova, Etios, Dzire.';

	public function beforeAction($action)
	{
		$host = preg_replace('/:[0-9]+/', '', $_SERVER["HTTP_HOST"]);
		$module = (isset($this->module)) ? $this->module->id : "default";
		$allowedHost = Config::getAllowedHost($module);
		if($allowedHost!=null && !preg_grep('/'.$host.'/', $allowedHost))
		{
			$isAllowed = Filter::checkIpAllowed(Config::getInternalIPs());
			if(!$isAllowed)
			{
				$u = $_SERVER;
				$u['HTTP_HOST'] = $allowedHost[0];
				$url = Filter::getOriginURL($u);
				$this->redirect($url, true, 301);
			}
		}
		
		
		if (isset($this->id) && isset($action->id))
		{
			$module = (isset($this->module)) ? $this->module->id : "";

			Logger::setActionCategory($module, $this->id, $action->id);
		}

		$this->pageTitle = "Hire outstation Cab in India | Awesome service. Best rates. Great reviews";
		if (!Yii::app()->user->isGuest)
		{
			$sess = Yii::app()->getSession()->getSessionId();
			UserLog::model()->updateLastActive($sess);
		}
		if (!Yii::app()->user->isGuest && Yii::app()->request->getParam('personalize') != null && Yii::app()->request->getParam('personalize') != '')
        {
            Yii::app()->user->setPersonalization(Yii::app()->request->getParam('personalize'));
        }
        $this->checkTheme();
  //  Yii::app()->request->cookies->clear();
    

        if (!Yii::app()->request->cookies['tkrid']->value)
        {
            $this->ustToken();
        }
        return true;
    }

	public function afterAction($action)
	{
		if (isset($this->id) && isset($action->id))
		{
			$module = (isset($this->module)) ? $this->module->id : "";
			Logger::unsetActionCategory($module, $this->id, $action->id);
		}
	}

	public function behaviors()
	{
		return array(
			'seo' => array('class' => 'application.components.SeoControllerBehavior'),
		);
	}

	/**
	 * Function for detecting mobile device and setting template prefix
	 */
	protected function checkTheme()
	{
		if (isset($_REQUEST['amp']) && $_REQUEST['amp'] == 1)
		{
			Yii::app()->getClientScript()->enableJavaScript	 = false;
			Yii::app()->theme								 = "amp/v2";
			if (!defined("IS_AMP"))
			{
				define("IS_AMP", 1);
			}
			return;
		}
		$this->checkV3Theme();
	}

	protected function checkV3Theme()
	{
		Yii::app()->theme				 = "desktop/v3";
		$app							 = Yii::app();
		$app->clientScript->scriptMap	 = Yii::app()->params['script']['desktopV3'];
	}

	protected function checkV2Theme()
	{
		if (isset($_REQUEST['amp']) && $_REQUEST['amp'] == 1)
		{
			Yii::app()->getClientScript()->enableJavaScript	 = false;
			Yii::app()->theme								 = "amp/v2";
			if (!defined("IS_AMP"))
			{
				define("IS_AMP", 1);
			}
			return;
		}
		$this->checkForDesktopTheme();
		$this->checkForMobileTheme();
	}

	protected function checkForDesktopTheme()
	{
		$app							 = Yii::app();
		$app->clientScript->scriptMap	 = Yii::app()->params['script']['desktopV2'];
		Yii::app()->theme				 = "desktop/v2";
	}

	protected function checkForMobileTheme()
	{
		$detect			 = Yii::app()->mobileDetect;
		$isMobileDetect	 = $detect->isMobile();

		if ($isMobileDetect == 1)
		{
			/** @var CWebApplication $app */
			$app = Yii::app();
			if (!defined("IS_MOBILE"))
			{
				define("IS_MOBILE", 1);
			}
			$app->clientScript->scriptMap	 = Yii::app()->params['script']['mobileB2C'];
			Yii::app()->theme				 = "mobile/B2C";
		}
	}

	protected function enableClarity()
	{
		/** @var CWebApplication $app */
		$app = Yii::app();
		if (!$app->request->isAjaxRequest && $app->params['enableTracking'] == true)
		{

			$app->clientScript->registerScript("enableClarity", <<<JS
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="http://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "fqii915fsm");
					window.clarity('consent');
JS
					, CClientScript::POS_HEAD);
		}
	}
    protected function ustToken()
    {
        $trackID                              = md5(Yii::app()->getSession()->getSessionId() . Filter::getDBDateTime());
        $utsCookie                            = new CHttpCookie('tkrid', $trackID);
        $utsCookie->expire                    = time() + 60 * 60 * 24 *10;
        //$utsCookie->httpOnly = true;
        Yii::app()->request->cookies['tkrid'] = $utsCookie;
    }

}
