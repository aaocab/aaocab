<?php

class BaseController extends Controller
{

	protected $_identity;
	public $pageHeader	 = '';
	public $current_page = '';
	public $email_receipient;
	public $fixedTop	 = '';
	public $subTitle	 = "";

	public function beforeAction($action)
	{

		if (!Yii::app()->user->isGuest)
		{
			$sess = Yii::app()->getSession()->getSessionId();
			UserLog::model()->updateLastActive($sess);
		}
		if (!Yii::app()->user->isGuest && Yii::app()->request->getParam('personalize') != null && Yii::app()->request->getParam('personalize') != '')
		{
			Yii::app()->user->setPersonalization(Yii::app()->request->getParam('personalize'));
		}
//        if (Yii::app()->controller->id == 'booking' && Yii::app()->user->getCompanyName() == '') {
//			$this->redirect('/agent/users/additionaldetails');
//        }
		return true;
	}

	/**
	 * Redirects the browser to the specified URL or route (controller/action).
	 * @param mixed $url the URL to be redirected to. If the parameter is an array,
	 * the first element must be a route to a controller action and the rest
	 * are GET parameters in name-value pairs.
	 * @param boolean $terminate whether to terminate the current application after calling this method. Defaults to true.
	 * @param integer $statusCode the HTTP status code. Defaults to 302. See {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html}
	 * for details about HTTP status code.
	 */
	public function redirect($url, $terminate = true, $statusCode = 302)
	{
		if (is_array($url))
		{
			$route	 = isset($url[0]) ? $url[0] : '';
			$url	 = $this->createUrl($this->createUrl($route, array_splice($url, 1)));
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$returnSet = new ReturnSet();
			$returnSet->setStatus(true);
			$returnSet->setData(["url" => $url, 'statusCode' => $statusCode]);
			echo json_encode($returnSet);
			if ($terminate)
			{
				Yii::app()->end();
			}
		}
		else
		{
			if ($url == '')
			{
				$url = '/';
			}
			Yii::app()->getRequest()->redirect($url, $terminate, $statusCode);
		}
	}

}
