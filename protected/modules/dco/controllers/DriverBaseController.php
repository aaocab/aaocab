<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class DriverBaseController extends BaseController
{
	public function beforeAction($action)
	{
		return parent::beforeAction($action);
	}

		}
