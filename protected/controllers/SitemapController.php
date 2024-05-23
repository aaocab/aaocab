<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class SitemapController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = '//layouts/column1';
	public $pageHeader	 = '';

	public function actionIndex()
	{
		$this->pageTitle = "Sitemap";
		exit;
		$mainList		 = $this->getMainList();
		$otherList		 = $this->getOtherList();
		$usersList		 = Route::model()->ListWithAliasProvider(24);
		$routeList		 = $usersList->getData();
		$this->render('index', ['mainList' => $mainList, 'otherList' => $otherList, 'routeList' => $routeList, 'usersList' => $usersList]);
		 
		
	}

	public function actionXml($page = 0)
	{
		header('Content-Type: text/xml');
		$list	 = [];
		$length	 = 45000;

		if ($page == 0)
		{
			$list[]	 = $this->createSitemapEntry('/', [], 'daily', '1');
			$list[]	 = $this->createSitemapEntry('/users/signin', [], 'monthly', '0.7');
			$list[]	 = $this->createSitemapEntry('/users/signup', [], 'monthly', '0.7');
			$list[]	 = $this->createSitemapEntry('/index/aboutus', [], 'monthly', '0.5');
			$list[]	 = $this->createSitemapEntry('/index/faqs', [], 'monthly', '0.5');
			$list[]	 = $this->createSitemapEntry('/index/contactus', [], 'monthly', '0.8');
			$list[]	 = $this->createSitemapEntry('/index/openings', [], 'monthly', '0.5');
			$list[]	 = $this->createSitemapEntry('/index/terms', [], 'monthly', '0.5');
			$list[]	 = $this->createSitemapEntry('/index/disclaimer', [], 'monthly', '0.5');
			$list[]	 = $this->createSitemapEntry('/index/testimonial', [], 'daily', '0.7');
			$list[]	 = $this->createSitemapEntry('/blog', [], 'daily', '0.7');

			$drCities = Cities::model()->getListWithAlias($start, $length);
			foreach ($drCities as $row)
			{
				$list[] = $this->createSitemapEntry('/car-rental/' . $row['cty_alias_path']);
			}
		}

		$start = $page * $length;
		$this->populateSitemap($list, $start, $length);

		echo $data = $this->renderFile(Yii::getPathOfAlias("application.views.sitemap.xml") . ".php", ['list' => $list], true);
	}

	public function populateSitemap(&$list, $start, $length)
	{
		$drRoutes = Route::model()->getListWithAlias($start, $length);
		foreach ($drRoutes as $row)
		{
			$list[] = $this->createSitemapEntry('/book-taxi/' . $row['rut_name']);
		}
	}

	public function createSitemapEntry($route, $params = [], $frequency = 'daily', $priority = '0.8')
	{
		return ['loc'		 => $this->createAbsoluteUrl($route, $params),
			'frequency'	 => $frequency,
			'priority'	 => $priority];
	}

	public function getMainList()
	{
		$app	 = Yii::app();
		$main	 = [];
		$main[]	 = $this->createList($app->createAbsoluteUrl('/'), 'Home', 'daily', '1');
		$main[]	 = $this->createList($app->createAbsoluteUrl('/whygozo'), 'Why Gozo Cabs', 'monthly', '0.7');
		$main[]	 = $this->createList($app->createAbsoluteUrl('/blog'), 'Blog', 'daily', '0.7');
		$main[]	 = $this->createList($app->createAbsoluteUrl('/about'), 'About Us', 'monthly', '0.5');
		$main[]	 = $this->createList($app->createAbsoluteUrl('/faq'), 'FAQS', 'monthly', '0.5');
		$main[]	 = $this->createList($app->createAbsoluteUrl('/contactus'), 'Contact Us', 'monthly', '0.8');
		$main[]	 = $this->createList($app->createAbsoluteUrl('/openings'), 'Openings', 'monthly', '0.5');
		$main[]	 = $this->createList($app->createAbsoluteUrl('/terms'), 'Terms and Conditions', 'monthly', '0.5');
		$main[]	 = $this->createList($app->createAbsoluteUrl('/disclaimer'), 'Disclaimer', 'monthly', '0.5');

		$main[] = $this->createList($app->createAbsoluteUrl('/privacy'), 'Privacy Policy', 'monthly', '0.7');
		return $main;
	}

	public function getOtherList()
	{
		$app	 = Yii::app();
		$main	 = [];
		$main[]	 = $this->createList($app->createAbsoluteUrl('/signin'), 'Signin', 'monthly', '0.7');
		$main[]	 = $this->createList($app->createAbsoluteUrl('/signup'), 'Register', 'monthly', '0.7');
		$main[]	 = $this->createList($app->createAbsoluteUrl('/index/testimonial'), 'Testimonial', 'daily', '0.7');
		$main[]	 = $this->createList('http://www.facebook.com/gozocabs', 'Facebook', 'daily', '0.7');
		$main[]	 = $this->createList('http://www.twitter.com/gozocabs', 'Twitter', 'daily', '0.7');
		#$main[]	 = $this->createList('http://www.plus.google.com/+Gozocabs', 'Google+', 'daily', '0.7');
		return $main;
	}

	public function getRouteList()
	{
		/* @var $route Route */
		$app	 = Yii::app();
		$main	 = [];
		$routes	 = Route::model()->fetchListWithAlias();
		foreach ($routes as $route)
		{
			$main[] = $this->createList($app->createAbsoluteUrl('/book-taxi/' . $route['rut_name']), $route['from_city_name'] . ' to ' . $route['to_city_name'], 'monthly', '0.7');
		}
		return $main;
	}

	public function createList($url, $title, $frequency = 'daily', $priority = '0.8')
	{
		return ['url' => $url, 'title' => $title, 'frequency' => $frequency, 'priority' => $priority];
	}

}
