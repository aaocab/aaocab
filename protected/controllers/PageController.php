<?php
include_once(dirname(__FILE__) . '/BaseController.php');
class PageController extends BaseController
{
	//show view for golden landing page
	public function actionGoldenRoute()
	{
		$this->pageTitle = "Cheapest oneway outstation cabs";
        $this->metaDescription = "Get the best and cheapest one-way cab service here from Gozo Cabs. Including Zero cancellation fee and 24 x 7 customer support for your hassle less journey. ";
		$this->render('GoldenRoute', array());
        
	}
}

