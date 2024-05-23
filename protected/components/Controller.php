<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/column1';

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();

	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs	 = array();
	public $current_page = '';

	/**
	 * Renders a view.
	 *
	 * The named view refers to a PHP script (resolved via {@link getViewFile})
	 * that is included by this method. If $data is an associative array,
	 * it will be extracted as PHP variables and made available to the script.
	 *
	 * This method differs from {@link render()} in that it does not
	 * apply a layout to the rendered result. It is thus mostly used
	 * in rendering a partial view, or an AJAX response.
	 *
	 * @param string $view name of the view to be rendered. See {@link getViewFile} for details
	 * about how the view script is resolved.
	 * @param array $data data to be extracted into PHP variables and made available to the view script
	 * @param boolean $return whether the rendering result should be returned instead of being displayed to end users
	 * @param boolean $processOutput whether the rendering result should be postprocessed using {@link processOutput}.
	 * @return string the rendering result. Null if the rendering result is not required.
	 * @throws CException if the view does not exist
	 * @see getViewFile
	 * @see processOutput
	 * @see render
	 */
	public function renderAuto($view, $data = null, $return = false, $processOutput = false)
	{
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method($view, $data, $return, ($outputJs || $processOutput));
	}

	public function renderDynamicDelay($callback, $params = null)
	{
//		$params	 = func_get_args();
//		array_shift($params);
		ob_start();
		$this->renderDynamic($callback, $params);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
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
	public function redirect($route, $terminate = true, $statusCode = 302)
	{
		if (isset(parse_url($route)['host']))
		{
			$url = $route;
		}
		else
		{
			$url = $this->getURL($route);
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$returnSet = new ReturnSet();
			$returnSet->setStatus(true);
			$returnSet->setData(["url" => $url, 'statusCode' => $statusCode]);
			if ($terminate)
			{
				echo json_encode($returnSet);
				Yii::app()->end();
			}
			return json_encode($returnSet);
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

	public function getURL($url)
	{
		if (is_array($url))
		{
			$route	 = isset($url[0]) ? $url[0] : '';
			$url	 = $this->createUrl($this->createUrl($route, array_splice($url, 1)));
		}
		else
		{
			$url = Yii::app()->createUrl($url);
		}
		return $url;
	}

	public function getAbsoluteURL($url)
	{
		if (is_array($url))
		{
			$route	 = isset($url[0]) ? $url[0] : '';
			$url	 = $this->createAbsoluteUrl($this->createUrl($route, array_splice($url, 1)));
		}
		else
		{
			$url = Yii::app()->createAbsoluteUrl($url);
		}
		return $url;
	}

	public function getTripUrl($tripType, $fcity = null, $tcity = null, $type = null)
	{
		$fpath	 = null;
		$tpath	 = null;
		if ($fcity != null && $fcity > 0)
		{
			$fpath = Cities::getAliasPath($fcity);
		}
		if ($tcity != null && $tcity > 0)
		{
			$tpath = Cities::getAliasPath($tcity);
		}

		return $this->getTripUrlFromPath($tripType, $fpath, $tpath, $type);
	}

	public function getTripUrlFromPath($tripType, $fcity = null, $tcity = null, $type = null)
	{
		$params = ['booking/itinerary', "bkgType" => $tripType];

		if ($type != null && $type > 0)
		{
			$params += ["type" => $type];
		}

		if ($fcity != null)
		{
			$params += ["fcity" => $fcity];
		}
		if ($tcity != null)
		{
			$params += ["tcity" => $tcity];
		}

		return $this->getURL($params);
	}

	public function getOneWayUrl($fcity = null, $tcity = null)
	{
		return $this->getTripUrl(1, $fcity, $tcity);
	}

	public function getRoundTripUrl($fcity = null, $tcity = null)
	{
		return $this->getTripUrl(2, $fcity, $tcity);
	}

	public function getMultiTripUrl($fcity = null, $tcity = null)
	{
		return $this->getTripUrl(3, $fcity, $tcity);
	}

	public function getDailyRentalUrl($fcity = null, $tcity = null)
	{
		return $this->getTripUrl(10, $fcity, $tcity);
	}

	public function getAirportLocalUrl($fcity = null, $tcity = null, $type = null)
	{
		return $this->getTripUrl(4, $fcity, $tcity, $type);
	}

	public function getAirportOutstationUrl($fcity = null, $tcity = null, $type = null)
	{
		return $this->getTripUrl(1, $fcity, $tcity, $type);
	}

	public function getOneWayUrlFromPath($fcity = null, $tcity = null)
	{
		return $this->getTripUrlFromPath(1, $fcity, $tcity);
	}
}
